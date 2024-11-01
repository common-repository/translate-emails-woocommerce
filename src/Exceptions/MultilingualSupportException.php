<?php

namespace WPDesk\ShopMagicMultilingual\Exceptions;

use WPDesk\ShopMagic\Exception\ShopMagicException;

/**
 * Thrown when no plugin supporting multilingual extension found.
 */
final class MultilingualSupportException extends \RuntimeException implements ShopMagicException {}
