<?php

namespace Intranet;

class Setup
{

    private Intranet $intranet;
    private string $plugin_filename;
    private string $name;
    private string $version;
    private string $description;
    private string $textDomain;

    public function __construct(Intranet $intranet)
    {
        $this->intranet = $intranet;
        if( ! function_exists('get_plugin_data') ){
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }
        $plugin_data = get_plugin_data($this->intranet->plugin_filename);
        $this->name = $plugin_data['Name'];
        $this->version = $plugin_data['Version'];
        $this->description = $plugin_data['Description'];
        $this->textDomain = $plugin_data['TextDomain'];
    }

    public function load_text_domain(): void {
        load_plugin_textdomain($this->textDomain, false, $this->intranet->plugin_filename . '/languages');
    }

}