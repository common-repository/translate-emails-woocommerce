<?php

namespace WPDesk\ShopMagicMultilingual\Customer;

use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Customer\Customer2;
use WPDesk\ShopMagic\Guest\GuestDAO;
use WPDesk\ShopMagicMultilingual\Language;

/**
 * Stores metadata about Customer language preferences.
 */
final class CustomerLanguagePersistence {

	/** @var Customer2 */
	private $customer;

	/** @var Language */
	private $language;

	public function __construct( Customer2 $customer, Language $language ) {
		$this->customer = $customer;
		$this->language = $language;
	}

	/** @return void */
	public function hooks() {
		add_action( 'shutdown', [ $this, 'persist_language' ], 99 );
	}

	/**
	 * @return void
	 * @internal
	 */
	public function persist_language() {
		if ( $this->customer->is_guest() ) {
			$this->persist_guest_language();
		} else {
			update_user_meta( (int) ( $this->customer->get_id() ), Customer::USER_LANGUAGE_META, $this->get_preferred_language() );
		}
	}

	/**
	 * @return string First matched language processed from request header. If none matched, use default site language.
	 */
	private function get_preferred_language(): string {
		$preferred_languages = isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) : '';
		$preferred_languages = $this->parse_preferred_browser_languages( $preferred_languages );

		$matching_languages = $this->get_matching_languages( $preferred_languages );

		return $matching_languages[0] ?? $this->language->default_language();
	}

	/** @return string[] */
	private function parse_preferred_browser_languages( string $accepted_languages ): array {
		$result = [];
		foreach ( explode( ',', $accepted_languages ) as $language_qualifier ) {
			$language_qualifier = array_map( 'trim', explode( ';', $language_qualifier ) );
			$result[]           = $language_qualifier[0];
		}
		return $result;
	}

	/** @return void */
	private function persist_guest_language() {
		global $wpdb;
		$guest_dao = new GuestDAO( $wpdb );
		$guest     = $guest_dao->get_by_email( $this->customer->get_email() );
		$guest->set_meta_value( Customer::USER_LANGUAGE_META, $this->get_preferred_language() );
		$guest_dao->save( $guest );
	}

	private function get_matching_languages( array $preferred_languages ): array {
		return array_values( array_intersect( $preferred_languages, $this->language->supported_languages() ) );
	}

}
