<?php

namespace WpListings;

class SearchForm
{
    public function __construct()
    {
        // Add shortcode
        add_shortcode('wp-listings-search-form', array($this, 'showForm'));
    }

    /**
     * Shows the frontend form
     * @return void
     */
    public function showForm()
    {
        \WpListings\App::enqueueJs();
        $fieldgroups = \WpListings\Listings::getCategoryFieldgroups();

        $template = apply_filters('wp-listings/search_template', WPLISTINGS_TEMPLATE_PATH . '/search-form.php');
        include_once $template;
    }
}
