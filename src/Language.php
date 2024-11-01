<?php

namespace WPDesk\ShopMagicMultilingual;

/**
 * Language-aware support for automations.
 */
interface Language {

	/**
	 * Get language of automation post.
	 *
	 * @param int $automation_id
	 *
	 * @return string
	 */
	public function automation_language( int $automation_id ): string;

	/**
	 * Get base language set for the site if none other specified.
	 *
	 * @return string
	 */
	public function default_language(): string;

	/**
	 * Get list of languages currently supported by the website.
	 *
	 * @return string[]
	 */
	public function supported_languages(): array;
}
