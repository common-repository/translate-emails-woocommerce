<?php

namespace WPDesk\ShopMagicMultilingual\Validator;

use WPDesk\ShopMagic\Automation\Automation;
use WPDesk\ShopMagic\Automation\AutomationValidator;
use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagicMultilingual\Language;

/**
 * Match Customer language against current automation language.
 */
final class CustomerLanguageValidator extends AutomationValidator {

	/** @var Language */
	private $language;

	public function __construct( Language $language ) {
		$this->language = $language;
	}

	public function valid(): bool {
		$customer   = $this->provided_data[ Customer::class ];
		$automation = $this->provided_data[ Automation::class ];

		if ( $this->language->automation_language( $automation->get_id() ) !== $customer->get_language() ) {
			return false;
		}

		return parent::valid();
	}
}
