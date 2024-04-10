<?php

namespace Intranet;

class Intranet {

    private Plugin $plugin;

    public function __construct(private string $plugin_filename) {
        $this->plugin = new Plugin($this->plugin_filename);
        $users = new Users($this);
	    $edit  = new Edit($this);

    }

	public function run(): void
    {
        add_action('plugins_loaded', [$this, 'load_text_domain'], 10);
	}

    public function load_text_domain(): void {
        load_plugin_textdomain( $this->plugin->getTextdomain(), false, $this->plugin->getDomainpath() );
    }
}