<?php

namespace Intranet;

class Adminbar {

	public function __construct( private Intranet $intranet ) {
		add_filter('get_avatar', '__return_false');
		add_action('admin_bar_menu', [$this, 'cleanup_wp_menu'],20);
		add_action('admin_bar_menu', [$this, 'cleanup_user_account'],20);


	}

	public function cleanup_wp_menu($wp_admin_bar) {
		$wp_logo = $wp_admin_bar->get_node('wp-logo');
		$wp_logo->href = null;
		$wp_admin_bar->add_node($wp_logo);
		$remove_nodes = array('wp-admin-bar-wporg','contribute','about','wporg','support-forums','feedback','learn');
		foreach ($remove_nodes as $node) {
			$wp_admin_bar->remove_node($node);
		}
		$wp_documentation = $wp_admin_bar->get_node('documentation');
		$wp_documentation->href = esc_url( "/documentation/" );
		$wp_admin_bar->add_node($wp_documentation);

	}

	public function cleanup_user_account($wp_admin_bar) {
		$my_account = $wp_admin_bar->get_node('my-account');
		$my_account->href = '';
		$wp_admin_bar->add_node( $my_account );

		$user_info = $wp_admin_bar->get_node('user-info');
		$user_info->href = null;
		$user_info->title = $this->intranet->plugin->get_current_user_role();
		$wp_admin_bar->add_node( $user_info );


		/* work before 6.5
		$user_info = $wp_admin_bar->get_node('user-info');
		$user_info->href = null;
		$wp_admin_bar->add_node( $user_info );
		$profile = $wp_admin_bar->get_node('edit-profile');
		$profile = $wp_admin_bar->get_node('edit-profile');
		$profile->href = null;
		$profile->title = $this->intranet->plugin->get_current_user_role();
		$wp_admin_bar->add_node( $profile );
		*/
	}

}