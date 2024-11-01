<?php

namespace WPDesk\ShopMagicMultilingual;

use WPDesk\ShopMagic\Helper\WordPressPluggableHelper;
use WPDesk\ShopMagicMultilingual\Exceptions\MultilingualSupportException;
use WPDesk\ShopMagicMultilingual\Integration\WPML;

/**
 * Choose language handler support based on active plugins.
 */
final class LanguageStrategyFactory {

	public function create_language_handler(): Language {
		if ( WordPressPluggableHelper::is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
			return new WPML();
		}
		throw new MultilingualSupportException( esc_html__( 'Your site has no supported multilingual plugin active. Activate one to enable ShopMagic work with multilingual automations.', 'translate-emails-woocommerce' ) );
	}
}
