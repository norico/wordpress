<?php

namespace Intranet;

class Dashboard {

	public function __construct( private readonly Intranet $intranet ) {
		add_action( 'wp_dashboard_setup', [ $this, 'remove_dashboard_widgets' ] );
		add_action( 'wp_dashboard_setup', [ $this, 'add_dashboard_widgets' ] );

		if ( is_multisite() && $this->intranet->plugin->isNetwork() )
		{
			add_action( 'wp_network_dashboard_setup', [ $this, 'remove_network_dashboard_widgets' ] );
			add_action( 'wp_network_dashboard_setup', [ $this, 'add_network_dashboard_widgets' ] );
		}
	}

	public function remove_dashboard_widgets() {
		remove_meta_box('dashboard_primary', 'dashboard', 'side');
		remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
	}

	public function add_dashboard_widgets() {
		$panel = 'posts-pending';
		$template_file = $this->intranet->plugin->getDir()."templates".DIRECTORY_SEPARATOR . "panels/" . $panel . ".php";
		if( file_exists($template_file) ) {
			wp_add_dashboard_widget( $panel, __( 'Pending Review', 'default' ), array($this,'select_panel'), null, array( 'template' => $template_file ) );
		}
	}

	public function remove_network_dashboard_widgets() {
		remove_meta_box('dashboard_primary', 'dashboard-network', 'side');
	}

	public function add_network_dashboard_widgets(): void{
		$panel = 'statistics';
		$template_file = $this->intranet->plugin->getDir()."templates".DIRECTORY_SEPARATOR . "panels/" . $panel . ".php";
		if( file_exists($template_file) ) {
			wp_add_dashboard_widget( $panel, __( 'Statistics', 'intranet' ), array($this,'select_panel'), '123', array( 'template' => $template_file ) );
		}
	}



	public function select_panel($null, $panel) {
		$panel_id = $panel['id'];
		$template = $panel['args']['template'];
		switch ($panel_id) :
			case 'posts-pending':
				$this->load_panel_posts_pending($panel_id, $template);
				break;
			case 'statistics':
				$this->load_panel_statistics($panel_id, $template);
				break;
		endswitch;;

	}


	private function load_panel_posts_pending($panel_id, $template_file ): void {
		echo'<div id="posts-pending">';
		echo '    <ul>';
		$args = array(
			'post_type'      => 'post',
			'post_status'    => 'pending',
			'orderby'        => 'modified',
			'order'          => 'DESC',
			'posts_per_page' => -1,
		);
		$query = new \WP_Query( $args );
		if ( $query->have_posts() ) :
			while ( $query->have_posts() ) : $query->the_post();
				$post = get_post();
				load_template($template_file, false, $args = $post );
			endwhile;
			wp_reset_postdata();
		else :
			_e('Sorry, no such post.', 'default');
		endif;
		echo '    </ul>';
		echo '</div>';
	}


	private function load_panel_statistics($panel_id, $template_file): void {
		echo'<div id="network-stats">';
		echo '<table class="wp-list-table widefat fixed striped table-view-list posts">';
		echo '<tr><th class="id">id</th><th class="column-title">'.__('Sites list').'</th><th class="num">'. __('Users') .'</th><th class="num">'. __('Posts') .'</th><th class="num">'. __('Pages') .'</th></tr>';
		load_template($template_file, false, $args = $this->intranet->plugin->get_network_details() );
		echo '    </table>';
		echo '</div>';
	}


}
