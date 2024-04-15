<?php

namespace Intranet;

class Multisite {

	public function __construct( private readonly Intranet $intranet ) {
		if( ! is_multisite() )
			return;
		$this->intranet->plugin->load_dependencies('multisite');
		add_filter( 'wpmu_blogs_columns', [ $this, 'add_columns' ] );
		add_filter( 'manage_sites_custom_column', [ $this, 'column_data' ], 10, 2 );
		add_filter( 'admin_footer_text', [ $this, 'update_footer' ], 99 );
	}
	public function add_columns( $columns ): array {
		$columns['site_id'] = __( 'Site ID' );
		return $columns;
	}
	public function column_data( $column_name, $blog_id ): void {
		if ( $column_name === "site_id" ) {
			echo $blog_id;
		}
	}
	public function update_footer( $string ): string {
		return sprintf( __( '%s - %s - %s' ), get_network()->site_name, get_bloginfo('Name'), get_current_blog_id() );
	}
}