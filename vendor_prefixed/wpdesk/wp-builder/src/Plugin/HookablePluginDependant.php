<?php

namespace ShopMagicMultilingualVendor\WPDesk\PluginBuilder\Plugin;

interface HookablePluginDependant extends \ShopMagicMultilingualVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * Set Plugin.
     *
     * @param AbstractPlugin $plugin Plugin.
     *
     * @return null
     */
    public function set_plugin(\ShopMagicMultilingualVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin $plugin);
    /**
     * Get plugin.
     *
     * @return AbstractPlugin.
     */
    public function get_plugin();
}
