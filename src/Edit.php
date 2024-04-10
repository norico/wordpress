<?php

namespace Intranet;

class Edit {

	public function __construct(  Intranet $intranet ) {
		add_filter('manage_posts_columns', array($this, 'thumbnail_column'));
		add_action('manage_posts_custom_column', array($this, 'thumbnail_data'));

	}

	public function thumbnail_column($columns ): array {
		if (current_theme_supports('post-thumbnails') )
		{
			$columns['thumbnail'] = __( 'Thumbnail', 'default' );
		}
		return $columns;
	}

	public function thumbnail_data($column_name ): void {
		if (current_theme_supports('post-thumbnails') ) {
			if ( $column_name == 'thumbnail' ) {
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( "medium" );
				} else {
					echo '<div class="thumbnail">' . ucfirst( __( 'none' , 'default') ) . '</div>';
				}
			}
		}
	}

}