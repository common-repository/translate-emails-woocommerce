<?php

namespace ShopMagicMultilingualVendor\WPDesk\PluginBuilder\Storage;

class StorageFactory
{
    /**
     * @return PluginStorage
     */
    public function create_storage()
    {
        return new \ShopMagicMultilingualVendor\WPDesk\PluginBuilder\Storage\WordpressFilterStorage();
    }
}
