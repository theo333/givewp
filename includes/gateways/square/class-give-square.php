<?php
/**
 * Give - Square Core
 *
 * @since 2.6.0
 *
 * @package    Give
 * @subpackage Square Core
 * @copyright  Copyright (c) 2019, GiveWP
 * @license    https://opensource.org/licenses/gpl-license GNU Public License
 */

// Exit, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Give_Square' ) ) {

	/**
	 * Class Give_Square
	 */
	class Give_Square {

		/**
		 * Give_Square() constructor.
		 *
		 * @since  2.6.0
		 * @access public
		 *
		 * @return void
		 */
		public function __construct() {

			add_filter( 'give_payment_gateways', array( $this, 'register_gateway' ) );

			$this->includes();
		}

		/**
		 * This function is used to include the related Square core files.
		 *
		 * @since  2.6.0
		 * @access public
		 *
		 * @return void
		 */
		public function includes() {

			// Include files which are necessary to load in admin but not in context of `is_admin`.
			$this->include_admin_files();

			// Include frontend files.
			$this->include_frontend_files();
		}

		/**
		 * This function is used to include admin files.
		 *
		 * @since  2.6.0
		 * @access public
		 *
		 * @return void
		 */
		public function include_admin_files() {

		}

		/**
		 * This function will be used to load frontend files.
		 *
		 * @since  2.6.0
		 * @access public
		 *
		 * @return void
		 */
		public function include_frontend_files() {


		}

		/**
		 * Register the payment methods supported by Square.
		 *
		 * @access public
		 * @since  2.6.0
		 *
		 * @param array $gateways List of registered gateways.
		 *
		 * @return array
		 */
		public function register_gateway( $gateways ) {

			// Square - On page credit card.
			$gateways['square'] = array(
				'admin_label'    => __( 'Square Gateway', 'give-square' ),
				'checkout_label' => __( 'Credit Card', 'give-square' ),
			);

			return $gateways;
		}
	}
}

new Give_Square();