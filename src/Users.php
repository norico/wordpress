<?php

namespace Intranet;

class Users
{
    public function __construct(Intranet $intranet)
    {
	    add_filter('manage_users_columns', array($this, 'admin_posts_column'), 10);
	    add_filter('manage_users_custom_column', array($this, 'pages_data'), 10, 3);

	    add_action( 'wp_login', array($this, 'user_last_login_update_meta'), 10, 2 );
	    if ( is_multisite() && $intranet->plugin->isNetwork() ) {
		    add_filter( 'wpmu_users_columns', array( $this, 'admin_users_column' ),11);
	    }
	    else {
		    add_filter( 'manage_users_columns', array( $this, 'admin_users_column' ),11);
	    }
	    add_filter( 'manage_users_custom_column', array( $this, 'last_login_data' ), 11, 3 );
    }

    public function admin_posts_column(array $columns): array
    {
        $columns['pages'] = __('Pages', 'default');
        return $columns;
    }

	public function pages_data($output, $column_id, $user_id): string
	{
		if ($column_id == 'pages') {
			$count = count_user_posts($user_id, 'page', true);
			$output = !empty($count) ? $count : '0';
		}
		return $output;
	}

	public function user_last_login_update_meta($user_login, $user): void
	{
		update_user_meta( $user->ID, 'last_login', current_time( 'timestamp' ) );
	}

	public function admin_users_column(array $columns) : array
	{
		$columns['last_login'] = __('Last Login', 'default');
		return $columns;
	}

	public function last_login_data( $output, $column_id, $user_id ): string {
		if ( $column_id == 'last_login' ) {
			$output      = __( 'No items.', 'default' );
			$last_login  = get_user_meta( $user_id, 'last_login', true );
			$date_format = get_option( 'date_format' );
			$time_format = get_option( 'time_format' );
			$output      = $last_login ? date_i18n( $date_format, $last_login ).' '. __('at', 'intranet').' '.date_i18n( $time_format, $last_login ): $output;
		}
		return $output;
	}

}