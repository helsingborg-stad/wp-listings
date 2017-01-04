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
