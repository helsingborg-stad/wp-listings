<?php

namespace WpListings;

class FrontendForm
{
    public function __construct()
    {
        add_shortcode('wp-listings-form', array($this, 'showForm'));
    }

    public function showForm()
    {
        include_once(WPLISTINGS_TEMPLATE_PATH . '/frontend-form.php');
    }
}
