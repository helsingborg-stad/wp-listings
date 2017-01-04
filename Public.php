<?php

if (!function_exists('wp_listings_use_price')) {
    function wp_listings_use_price()
    {
        return is_null(get_field('listing_price', 'option')) || get_field('listing_price', 'option');
    }
}
