<?php

namespace Intranet;

class Intranet {

	private string $plugin_filename;

	public function __construct($plugin_filename) {
		$this->plugin_filename = $plugin_filename;
	}

	public function run() {
		$this->plugin->load_text_domain();
	}
}