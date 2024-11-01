<?php
declare(strict_types=1);

namespace WPDesk\ShopMagicMultilingual;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use ShopMagicMultilingualVendor\WPDesk\Notice\Notice;
use ShopMagicMultilingualVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin;
use ShopMagicMultilingualVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use ShopMagicMultilingualVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
use ShopMagicMultilingualVendor\WPDesk_Plugin_Info;
use WPDesk\ShopMagic\Action\Action;
use WPDesk\ShopMagic\Automation\Automation;
use WPDesk\ShopMagic\Automation\AutomationRunner;
use WPDesk\ShopMagic\Event\Event;
use WPDesk\ShopMagic\Exception\CannotProvideCustomerException;
use WPDesk\ShopMagic\Integration\ExternalPluginsAccess;
use WPDesk\ShopMagicMultilingual\Customer\CustomerLanguagePersistence;
use WPDesk\ShopMagicMultilingual\Exceptions\MultilingualSupportException;
use WPDesk\ShopMagicMultilingual\Validator\CustomerLanguageValidator;

/**
* Main plugin class. The most important flow decisions are made here.
*
* @package WPDesk\ShopMagicMultilingual
*/
final class Plugin extends AbstractPlugin implements LoggerAwareInterface, HookableCollection {
	use LoggerAwareTrait;
	use HookableParent;

	public function __construct( WPDesk_Plugin_Info $plugin_info ) {
		/** @noinspection PhpParamsInspection */
		parent::__construct( $plugin_info );

		$this->docs_url    = 'https://docs.shopmagic.app/?utm_source=user-site&utm_medium=quick-link&utm_campaign=docs';
		$this->support_url = 'https://shopmagic.app/support/?utm_source=user-site&utm_medium=quick-link&utm_campaign=support';
	}

	public function hooks() {
		parent::hooks();

		add_action(
			'shopmagic/core/initialized/v2',
			static function ( ExternalPluginsAccess $external_plugin ) {
				if ( version_compare( $external_plugin->get_version(), '3', '>=' ) ) {
					new Notice(
						sprintf(
							// translators: %s ShopMagic version.
							__(
								'This version of ShopMagic Multilingual plugin is not compatible with ShopMagic %s. Please upgrade ShopMagic Multilingual to the newest version.',
								'translate-emails-woocommerce'
							),
							$external_plugin->get_version()
						)
					);
					return;
				}

				try {
					$language_handler = ( new LanguageStrategyFactory() )->create_language_handler();
				} catch ( MultilingualSupportException $e ) {
					if ( is_admin() ) {
						new Notice( $e->getMessage(), Notice::NOTICE_TYPE_ERROR, true );
					}
					return;
				}

				$external_plugin->set_validator( new CustomerLanguageValidator( $language_handler ) );

				try {
					if ( ! is_admin() ) {
						( new CustomerLanguagePersistence( $external_plugin->get_customer_provider()->get_customer(), $language_handler ) )->hooks();
					}
				} catch ( CannotProvideCustomerException $e ) { // phpcs:ignore
				}

				add_action(
					'shopmagic/core/action/before_execution',
					static function ( Action $action, Automation $automation, Event $event ) use ( $language_handler ) {
						global $woocommerce_wpml;
						if ( $woocommerce_wpml && ! is_null( $woocommerce_wpml->emails ) ) {
							$language = $language_handler->automation_language( $automation->get_id() );
							/** @var $woocommerce_wpml \woocommerce_wpml */
							$woocommerce_wpml->emails->change_email_language( $language );
						}
					},
					10,
					3
				);
			}
		);
	}
}
