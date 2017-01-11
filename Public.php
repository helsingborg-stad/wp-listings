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

if (!function_exists('wp_listings_add_listing_button')) {
    function wp_listings_add_listing_button($additionalClasses = array())
    {
        if (!get_field('listing_add_button', 'option') || !get_field('listing_add_button_link', 'option')) {
            return;
        }

        $url = apply_filters('wp-listings/add_button/link', get_field('listing_add_button_link', 'option'));
        $text = apply_filters('wp-listings/add_button/text', __('Add listing', 'wp-listings'));
        $classes = (array) apply_filters('wp-listings/add_button/classes', array('btn', 'btn-primary'));

        if (is_array($additionalClasses)) {
            $classes = array_merge($classes, $additionalClasses);
        }

        $markup = '<a href="' . $url . '" class="' . implode(' ', $classes) . '">' . $text . '</a>';

        return apply_filters('wp-listings/add_button/markup', $markup, $text, $url);
    }
}
