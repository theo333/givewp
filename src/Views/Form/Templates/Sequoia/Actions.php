<?php

namespace Give\Views\Form\Templates\Sequoia;

use Give_Donate_Form;
use function Give\Helpers\Form\Template\get as getTheme;


/**
 * Class Actions
 *
 * @since 2.7.0
 * @package Give\Views\Form\Templates\Sequoia
 */
class Actions {

	protected $templateOptions;

	/**
	 * Initialize
	 *
	 * @since 2.7.0
	 */
	public function init() {
		// Get Template options
		$this->templateOptions = getTheme();

		// Set zero number of decimal.
		add_filter( 'give_get_currency_formatting_settings', [ $this, 'setupZeroNumberOfDecimalInCurrencyFormattingSetting' ], 1 );
		add_filter( 'give_get_option_number_decimals', [ $this, 'setupZeroNumberOfDecimal' ], 1 );

		// Handle personal section html template.
		add_action( 'wp_ajax_give_cancel_login', [ $this, 'cancelLoginAjaxHanleder' ], 9 );
		add_action( 'wp_ajax_nopriv_give_cancel_login', [ $this, 'cancelLoginAjaxHanleder' ], 9 );
		add_action( 'wp_ajax_nopriv_give_checkout_register', [ $this, 'cancelLoginAjaxHanleder' ], 9 );

		// Handle common hooks.
		add_action( 'give_donation_form', [ $this, 'loadCommonHooks' ], 9, 2 );

		// Setup hooks.
		add_action( 'give_pre_form_output', [ $this, 'loadHooks' ], 1, 3 );
	}

	/**
	 * Set zero as number of decimals in currency formatting setting.
	 *
	 * As per design requirement we want to format donation amount with zero decimal whether or not number of decimal admin setting set to zero.
	 *
	 * @since 2.7.0
	 * @param array $currencyFormattingSettings
	 * @return array
	 */
	public function setupZeroNumberOfDecimalInCurrencyFormattingSetting( $currencyFormattingSettings ) {
		$currencyFormattingSettings['number_decimals'] = 0;
		return $currencyFormattingSettings;
	}


	/**
	 * Return zero as number of decimals setting value or currency formatting value.
	 *
	 * As per design requirement we want to format donation amount with zero decimal whether or not number of decimal admin setting set to zero.
	 *
	 * @since 2.7.0
	 * @return int|array
	 */
	public function setupZeroNumberOfDecimal() {
		return 0;
	}

	/**
	 * Handle cancel login and checkout register ajax request.
	 *
	 * @since 2.7.0
	 * @return void
	 */
	public function cancelLoginAjaxHanleder() {
		// add_action( 'give_donation_form_before_personal_info', [ $this, 'getIntroductionSectionTextSubSection' ] );
	}

	/**
	 * Setup common hooks
	 *
	 * @param int   $formId
	 * @param array $args
	 */
	public function loadCommonHooks( $formId, $args ) {
		remove_action( 'give_donation_form_register_login_fields', 'give_show_register_login_fields' );
	}

	/**
	 * Setup hooks
	 *
	 * @param int              $formId
	 * @param array            $args
	 * @param Give_Donate_Form $form
	 */
	public function loadHooks( $formId, $args, $form ) {
		/**
		 * Add hooks
		 */
		add_action( 'give_pre_form', [ $this, 'getNavigator' ], 0, 3 );
		add_action( 'give_post_form', [ $this, 'getFooterSection' ], 99998, 0 );
		add_action( 'give_donation_form_top', [ $this, 'getIntroductionSection' ], 0, 3 );
		add_action( 'give_donation_form_top', [ $this, 'getStartWrapperHTMLForAmountSection' ], 0 );
		add_action( 'give_donation_form_top', [ $this, 'getCloseWrapperHTMLForAmountSection' ], 99998 );
		add_action( 'give_payment_mode_top', 'give_show_register_login_fields' );
		add_action( 'give_donation_form_before_personal_info', [ $this, 'getStartWrapperHTMLForPaymentSection' ] );
		add_action( 'give_donation_form_after_submit', [ $this, 'getCloseWrapperHTMLForPaymentSection' ] );

		/**
		 * Remove actions
		 */
		// Remove goal.
		remove_action( 'give_pre_form', 'give_show_goal_progress', 10 );

		// Remove intermediate continue button which appear when display style set to other then onpage.
		remove_action( 'give_after_donation_levels', 'give_display_checkout_button', 10 );

		// Hide title.
		add_filter( 'give_form_title', '__return_empty_string' );
	}

	/**
	 * Add form navigator / header
	 *
	 * @since 2.7.0
	 *
	 * @param $formId
	 * @param $args
	 * @param $form
	 */
	public function getNavigator( $formId, $args, $form ) {
		include 'sections/form-navigator.php';
	}

	/**
	 * Add introduction form section
	 *
	 * @since 2.7.0
	 *
	 * @param $formId
	 * @param $args
	 * @param $form
	 */
	public function getIntroductionSection( $formId, $args, $form ) {
		include 'sections/introduction.php';
	}

	/**
	 * Add form footer
	 *
	 * @since 2.7.0
	 */
	public function getFooterSection() {
		include 'sections/footer.php';
	}

	/**
	 * Add checkout button
	 *
	 * @since 2.7.0
	 */
	public function getCheckoutButton() {

		$label = isset( $this->templateOptions['payment_information']['checkout_label'] ) ? $this->templateOptions['payment_information']['checkout_label'] : __( 'Donate Now', 'give' );

		return sprintf(
			'<div class="give-submit-button-wrap give-clearfix">
		    <input type="submit" class="give-submit give-btn" id="give-purchase-button" name="give-purchase" value="%1$s" data-before-validation-label="Donate Now">
				<span class="give-loading-animation"></span>
		  </div>',
			$label
		);
	}

	/**
	 * Add load next sections button
	 *
	 * @since 2.7.0
	 */
	public function getNextButton( $id ) {

		$label = ! empty( $this->templateOptions['introduction']['donate_label'] ) ? $this->templateOptions['introduction']['donate_label'] : __( 'Donate Now', 'give' );

		printf(
			'<div class="give-section"><button class="give-btn advance-btn">%1$s</button></div>',
			$label
		);
	}

	/**
	 * Add wrapper and introduction text to payment information section
	 *
	 * @since 2.7.0
	 *
	 * @param int $formId
	 */
	public function getStartWrapperHTMLForPaymentSection( $formId ) {
		$headline    = isset( $this->templateOptions['payment_information']['headline'] ) ? $this->templateOptions['payment_information']['headline'] : __( 'Tell us a bit about yourself.', 'give' );
		$description = isset( $this->templateOptions['payment_information']['description'] ) ? $this->templateOptions['payment_information']['description'] : __( 'We’ll never share this information with anyone.', 'give' );

		if ( ! empty( $headline ) || ! empty( $description ) ) {
			printf(
				'<div class="give-section payment"><div class="heading">%1$s</div><div class="subheading">%2$s</div>',
				$headline,
				$description
			);
		}
	}

	/**
	 * Close wrapper for payment information section
	 *
	 * @since 2.7.0
	 */
	public function getCloseWrapperHTMLForPaymentSection() {
		echo '</div>';
	}

	/**
	 * Start choose amount section
	 *
	 * @since 2.7.0
	 */
	public function getStartWrapperHTMLForAmountSection() {
		$content = isset( $this->templateOptions['payment_amount']['content'] ) ? $this->templateOptions['payment_amount']['content'] : __( 'As a contributor to Save the Whales we make sure your money gets put to work. How much would you like to donate? Your donation goes directly to supporting our cause.', 'give' );
		$label   = ! empty( $this->templateOptions['introduction']['donate_label'] ) ? $this->templateOptions['introduction']['donate_label'] : __( 'Donate Now', 'give' );

		echo "<button class='give-btn advance-btn'>{$label}</button></div>";

		if ( ! empty( $content ) ) {
			echo "<div class='give-section choose-amount'><p class='content'>{$content}</p>";
		} else {
			echo "<div class='give-section choose-amount'>";
		}
	}

	/**
	 * Close choose amount section
	 *
	 * @since 2.7.0
	 */
	public function getCloseWrapperHTMLForAmountSection() {
		$label = isset( $this->templateOptions['payment_amount']['next_label'] ) ? $this->templateOptions['payment_amount']['next_label'] : __( 'Continue', 'give' );
		echo "<button class='give-btn advance-btn'>{$label}</button></div>";
	}

}