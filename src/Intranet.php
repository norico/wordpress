<?php

namespace Intranet;

class Intranet {

	public Plugin $plugin;

    public function __construct(private string $plugin_filename) {
        $this->plugin = new Plugin($this->plugin_filename);
        new Users($this);
	    new Edit($this);
		new Dashboard($this);
	    new Adminbar($this);
	    new Login($this);
	    new Multisite($this);
    }

	public function run(): void
    {
        add_action('plugins_loaded', [$this, 'load_text_domain'], 10);
	}

    public function load_text_domain(): void {
        load_plugin_textdomain( $this->plugin->getTextdomain(), false, $this->plugin->getDomainpath() );
    }
}