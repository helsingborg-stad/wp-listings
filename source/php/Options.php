<?php

namespace WpListings;

class Options
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'createOptionsPage'));
    }

    public function createOptionsPage()
    {
        acf_add_options_page(array(
            'page_title' => __('Options', 'wp-listings'),
            'menu_slug' => 'listings-options',
            'capability' => 'edit_posts',
            'parent_slug' => 'edit.php?post_type=listing'
        ));
    }
}
