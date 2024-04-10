<?php

namespace Intranet;

class Users
{
    public function __construct(Intranet $intranet)
    {

	    add_filter('manage_users_columns', array($this, 'admin_posts_column'), 10);
	    add_filter('manage_users_custom_column', array($this, 'pages_data'), 10, 3);

    }

    public function admin_posts_column(array $columns): array
    {
        $columns['pages'] = __('Pages', 'default');
        return $columns;
    }

    public function pages_data($output, $column_id, $user_id): string
    {
        if ($column_id == 'pages') {
            $output = count_user_posts($user_id, "page");
        }
        return $output;
    }
}