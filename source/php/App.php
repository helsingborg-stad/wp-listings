<?php

namespace WpListings;

class App
{
    public function __construct()
    {
        add_filter('acf/settings/load_json', array($this, 'jsonLoadPath'));

        new \WpListings\Listings();
    }

    public function jsonLoadPath($paths)
    {
        $paths[] = WPLISTINGS_PATH . 'source/acf-exports';
        return $paths;
    }
}
