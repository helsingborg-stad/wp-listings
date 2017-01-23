<?php

namespace WpListings;

class Single
{
    private $buttonPrinted = false;
    private $documentPrinted = false;

    public function __construct()
    {
        add_filter('the_title', array($this, 'addGoBackButton'), 10, 2);
    }

    public function addGoBackButton($title, $post_id = null) : string
    {
        if ($this->isSingleListing($post_id) && !$this->buttonPrinted) {

            //Do not show again
            $this->buttonPrinted = true;

            //Get label
            $label = !empty(get_field('listing_back_button_label', 'option')) ? get_field('listing_back_button_label', 'option') : __("Back to listing", 'wp-listings');

            //Return button markup
            return $title . "" . '<a href="' . get_post_type_archive_link(\WpListings\Listings::$postTypeSlug) . '" class="btn btn-sm pull-right" style="font-size: 15px">' . $label . '</a>';

        }
        return $title;
    }

    private function isSingleListing($post_id) : bool
    {
        global $wp_query;
        if (!is_archive() && is_numeric($post_id) && is_single($post_id) && get_post_type($post_id) == \WpListings\Listings::$postTypeSlug && $wp_query->is_main_query()) {
            return true;
        }
        return false;
    }
}
