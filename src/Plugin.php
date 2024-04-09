<?php

namespace Intranet;

class Plugin
{
    private string $name;
    private string $version;
    private string $description;
    private string $authorname;
    private string $textdomain;
    private string $domainpath;
    private string $wordpress;
    private string $php;
    private bool $network;

    /**
     * @param string $plugin_filename
     */
    public function __construct(string $plugin_filename)
    {
        if( ! function_exists('get_plugin_data') ){
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }
        $plugin_data = get_plugin_data($plugin_filename);
        if($plugin_data) {
            $this->name        = $plugin_data['Name'];
            $this->version     = $plugin_data['Version'];
            $this->description = $plugin_data['Description'];
            $this->authorname  = $plugin_data['AuthorName'];
            $this->textdomain  = $plugin_data['TextDomain'];
            $this->domainpath  = $plugin_data['DomainPath'];
            $this->network     = $plugin_data['Network'];
            $this->wordpress   = $plugin_data['RequiresWP'];
            $this->php         = $plugin_data['RequiresPHP'];
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAuthorname(): string
    {
        return $this->authorname;
    }

    public function getTextdomain(): string
    {
        return $this->textdomain;
    }

    public function getDomainpath(): string
    {
        return $this->domainpath;
    }

    public function getWordpress(): string
    {
        return $this->wordpress;
    }

    public function getPhp(): string
    {
        return $this->php;
    }

    public function isNetwork(): bool
    {
        return $this->network;
    }
}