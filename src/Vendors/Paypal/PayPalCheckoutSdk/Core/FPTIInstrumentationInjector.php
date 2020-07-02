<?php

namespace Give\Vendor\Paypal\PayPalCheckoutSdk\Core;

use Give\Vendor\Paypal\PayPalHttp\Injector;
class FPTIInstrumentationInjector implements \Give\Vendor\Paypal\PayPalHttp\Injector {

	public function inject( $request ) {
		$request->headers['sdk_name']             = 'Checkout SDK';
		$request->headers['sdk_version']          = '1.0.1';
		$request->headers['sdk_tech_stack']       = 'PHP ' . \PHP_VERSION;
		$request->headers['api_integration_type'] = 'PAYPALSDK';
	}
}
