<?php
namespace Intranet;

class RelativeUrl {

	/*
	 * Plugin: Relative URL
	 * Plugin URI: https://sparanoid.com/work/relative-url/
	 * Description: Relative URL applies wp_make_link_relative function to links (posts, categories, pages and etc.)
	 * to convert them to relative URLs. Useful for developers when debugging local WordPress instance
	 * on a mobile device (iPad. iPhone, etc.).
	 */

	public function __construct( private readonly Intranet $intranet ) {
		add_action('registered_taxonomy', [$this, 'buffer_start_relative_url']);
		add_action('shutdown', [$this, 'buffer_end_relative_url']);
	}

	public function buffer_start_relative_url() {
		ob_start([$this, 'callback_relative_url']);
	}
	public function buffer_end_relative_url() {
		if (ob_get_length())
			@ob_end_flush();
	}

	public function callback_relative_url($buffer) {
		// Replace normal URLs
		$home_url = esc_url(home_url('/'));
		$home_url_relative = wp_make_link_relative($home_url);

		// Replace URLs in inline scripts
		$home_url_escaped = str_replace('/', '\/', $home_url);
		$home_url_escaped_relative = str_replace('/', '\/', $home_url_relative);

		$buffer = str_replace($home_url, $home_url_relative, $buffer);
		$buffer = str_replace($home_url_escaped, $home_url_escaped_relative, $buffer);

		return $buffer;
	}

}