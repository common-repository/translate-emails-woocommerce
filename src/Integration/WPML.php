<?php

namespace WPDesk\ShopMagicMultilingual\Integration;

use WPDesk\ShopMagicMultilingual\Language;

/**
 * Add compatibility layer for WPML plugin.
 */
final class WPML implements Language {

	public function automation_language( int $automation_id ): string {
		return wpml_get_language_information( null, $automation_id )['language_code'] ?? $this->default_language();
	}

	public function default_language(): string {
		return wpml_get_default_language();
	}

	/** @return string[] */
	public function supported_languages(): array {
		global $sitepress;
		return $sitepress instanceof \SitePress ? array_keys( $sitepress->get_active_languages() ) : []; // @phpstan-ignore-line
	}

}
