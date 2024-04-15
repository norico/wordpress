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
    private string $dir;
    private string $url;
    private string $basename;
    private string $slug;

    private bool $network;

    /**
     * @param string $plugin_filename
     */
    public function __construct(private string $plugin_filename)
    {
        if( ! function_exists('get_plugin_data') ){
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }

        $this->dir = plugin_dir_path($plugin_filename);
        $this->url = plugin_dir_url($plugin_filename);
        $this->basename = plugin_basename($plugin_filename);
        $this->slug = dirname(plugin_basename($plugin_filename));
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
        $this->load_dependencies();
        $this->create_admin_menu();

    }

    private function create_admin_menu()
    {
        if ( is_multisite() && $this->isNetwork() ){
            add_filter('network_admin_plugin_action_links_'.$this->getBasename(), [$this, 'add_action_links']);
            add_action('network_admin_menu', [$this, 'admin_menu'], 10);
        }
        else {
            add_filter('plugin_action_links_'.$this->getBasename(), [$this, 'add_action_links']);
            add_action('admin_menu', [$this, 'admin_menu'], 10);
        }
    }


    public function add_action_links( array $links ): array {
        $admin_url  = (is_multisite() &&  $this->isNetwork() ) ? "network_admin_url" : "admin_url";
        $admin_path = (is_multisite() && $this->isNetwork() )  ? "network.php" : "admin.php";
        $links[] = sprintf("<a href='%s'>%s</a>", $admin_url( $admin_path.'?page='.$this->getSlug() ), __('Settings', 'default'));
        return $links;
    }

    public function admin_menu(): void {
        $args = array(
            'network' => $this->isNetwork(),
            'icon' => 'dashicons-admin-multisite',
            'position' => 90,
        );
        $this->add_admin_menu( $args );
    }

    public function add_admin_menu( array $args ): void
    {
        $defaults = array(
            'network' => false,
            'icon' => 'dashicons-admin-generic',
            'position' => 90,
        );
        $args = wp_parse_args( $args, $defaults );
        $admin_page = add_menu_page($this->getName(), $this->getName(), 'manage_options', $this->getSlug(), [$this, 'render_admin_page'], $args['icon'], $args['position']);
    }

    public function render_admin_page(): void {
        $filename = $this->getDir().'admin/'.$this->getSlug().'.php';
        echo '<div class="wrap">';
        printf('<h1>%s</h1><hr/>', get_admin_page_title() );
        if ( file_exists($filename) )
        {
            require_once($filename);
        }
        else {
            printf('<p>%s</p>', __('Missing admin page', 'intranet') );
            printf('<p>%s</p>', __('Please check your file intranet.php, in /admin directory', 'intranet'));
        }
        echo '</div>';
    }

	/**
	 * load dependencies for plugin. Insert css and js files, depend on WordPress location (front or back).
	 * @param $filename
	 * @return void
	 */
	public function load_dependencies($filename = null): void
	{
		$handle = $filename ?: $this->getSlug();
		$dir = is_admin() ? 'admin/' : '';
		$filename_css = $this->getDir() . "assets/css/{$dir}{$handle}.css";
		if (file_exists($filename_css)) {
			add_action("admin_enqueue_scripts", function() use ($handle) {$this->load_dependencies_css($handle);}, 20);
			add_action("wp_enqueue_scripts", function() use ($handle) {$this->load_dependencies_css($handle);}, 20);
			add_action( 'login_enqueue_scripts', function() use ($handle){$this->load_dependencies_css($handle);},20);
		}
		$filename_js = $this->getDir() . "assets/js/{$dir}{$handle}.js";
		if (file_exists($filename_js)) {
			add_action("admin_enqueue_scripts", function() use ($handle) {$this->load_dependencies_script($handle);}, 10);
			add_action("wp_enqueue_scripts", function() use ($handle) { $this->load_dependencies_script($handle);}, 10);
			add_action( 'login_enqueue_scripts', function() use ($handle){$this->load_dependencies_css($handle);},20);
		}
	}

	public function load_dependencies_css($handle): void {
		$dir = is_admin() ? 'admin/': null;
		wp_enqueue_style($handle, $this->getUrl() .'assets/css/'.$dir.$handle.'.css' , [], $this->getVersion(), 'all');
	}

	public function load_dependencies_script($handle): void {
		$dir = is_admin() ? 'admin/': null;
		wp_enqueue_script($handle, $this->getUrl() .'assets/js/'.$dir.$handle.'.js', ['jquery'], $this->getVersion(), true);
	}





    /* Getters */

    public function getFilename(): string
    {
        return $this->plugin_filename;
    }

    public function getBasename(): string
    {
        return $this->basename;
    }

    public function getDir(): string
    {
        return $this->dir;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getSlug(): string
    {
        return $this->slug;
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

	public function get_current_user_role(): string
	{
		$wp_roles = new \WP_Roles();
		$current_user = wp_get_current_user();
		$first_role = $current_user->roles[0];
		return translate_user_role( $wp_roles->roles[ $first_role ]['name']) ?? __('Unknown', 'default');
	}

	public  function get_network_details() {
		$sites = get_sites();
		$site_objects = array();

		foreach ($sites as $site) {
			$site_details = get_blog_details($site->blog_id);

			$site_object = new \stdClass();
			$site_object->blog_id = $site_details->blog_id;

			switch_to_blog($site->blog_id);
			$site_object->{'posts'} = wp_count_posts('post')->publish;
			$site_object->{'pages'} = wp_count_posts('page')->publish;
			restore_current_blog();



			foreach ($site_details as $key => $value) {
				$site_object->{$key} = $value;
			}
			$site_object->{'user_count'} = count_users( null,  $site_details->blog_id);
			$site_objects[] = $site_object;

		}
		return $site_objects;
	}

}