<?php

if (!function_exists('wp_listings_use_price')) {
    function wp_listings_use_price()
    {
        return is_null(get_field('listing_price', 'option')) || get_field('listing_price', 'option');
    }
}

if (!function_exists('wp_listings_show_contact_form')) {
    function wp_listings_show_contact_form()
    {
        return get_field('listing_contact_seller_form', 'option');
    }
}

if (!function_exists('wp_listings_get_documents')) {
    function wp_listings_get_documents()
    {
        return get_field('listing_documents');
    }
}

if (!function_exists('wp_listings_get_meta_fields')) {
    function wp_listings_get_meta_fields()
    {
        global $post;

        if ($post->post_type !== \WpListings\Listings::$postTypeSlug) {
            return;
        }

        $category = wp_get_post_terms($post->ID, \WpListings\Listings::$taxonomySlug);
        if (!$category) {
            return;
        }

        $category = $category[0]->slug;

        $fieldgroups = array_filter(\WpListings\Listings::getCategoryFieldgroups(), function ($fieldgroup) use ($category) {
            $key = $fieldgroup['key'];
            $key = str_replace('wp-listings_' . \WpListings\Listings::$taxonomySlug . '_', '', $key);
            $key = explode('_', $key)[0];

            return $key === $category;
        });

        $fields = array_filter($fieldgroups[0]['fields'], function ($field) {
            return !isset($field['wp_listings_options']) || in_array('show', $field['wp_listings_options']);
        });

        return $fields;
    }
}

if (!function_exists('wp_listings_delete_listing_button')) {
    function wp_listings_delete_listing_button()
    {
        $markup = '<a href="#removeListing">' . __('Remove listing', 'wp-listings') . '</a>';
        $markup = apply_filters('wp-listings/delete_listing_button', $markup);
        include WPLISTINGS_TEMPLATE_PATH . 'remove-modal.php';
        return $markup;
    }
}
