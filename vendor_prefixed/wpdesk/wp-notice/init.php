<?php

namespace ShopMagicMultilingualVendor;

require_once __DIR__ . '/vendor/autoload.php';
if (!\class_exists('ShopMagicMultilingualVendor\\WPDesk\\Notice\\AjaxHandler')) {
    require_once __DIR__ . '/src/WPDesk/Notice/AjaxHandler.php';
}
if (!\class_exists('ShopMagicMultilingualVendor\\WPDesk\\Notice\\Notice')) {
    require_once __DIR__ . 'src/WPDesk/Notice/Notice.php';
}
if (!\class_exists('ShopMagicMultilingualVendor\\WPDesk\\Notice\\PermanentDismissibleNotice')) {
    require_once __DIR__ . '/src/WPDesk/Notice/PermanentDismissibleNotice.php';
}
if (!\class_exists('ShopMagicMultilingualVendor\\WPDesk\\Notice\\Factory')) {
    require_once __DIR__ . '/src/WPDesk/Notice/Factory.php';
}
require_once __DIR__ . '/src/WPDesk/notice-functions.php';
