<?php

namespace WpListings;

class App
{
    public static $uploadDir;

    public function __construct()
    {
        add_action('plugins_loaded', array($this, 'init'));
    }

    public function init()
    {
        if (!function_exists('get_field') && !function_exists('acf_add_options_page')) {
            add_action('admin_notices', function () {
                echo '<div class="notice notice-success is-dismissible"><p>', __('WP Listings: Please activate Advanced Custom Fields plugin to be able to use WP Listings.', 'wp-listings'), '</p></div>';
            });

            return;
        }

        // Register js
        add_filter('wp_enqueue_scripts', function () {
            wp_register_script('wp-listings', WPLISTINGS_URL . '/dist/js/wp-listings.min.js', null, '1.0.0', true);
        });

        self::$uploadDir = $this->getUploadDir();
        add_filter('acf/settings/load_json', array($this, 'jsonLoadPath'));

        new \WpListings\Listings();
        new \WpListings\FrontendForm();
        new \WpListings\SearchForm();
        new \WpListings\ContactForm();
        new \WpListings\Search();
        new \WpListings\Archive();
        new \WpListings\Single();
        new \WpListings\Options();
    }

    /**
     * Set ACF json load paths
     * @param  array $paths  Original paths
     * @return array         Modified paths
     */
    public function jsonLoadPath($paths)
    {
        $paths[] = WPLISTINGS_PATH . 'source/acf-exports';
        $paths[] = $this->getUploadDir();

        return $paths;
    }

    /**
     * Get upload directory path
     * @return string
     */
    public function getUploadDir()
    {
        if (isset(self::$uploadDir)) {
            return self::$uploadDir;
        }

        $uploadDir = wp_upload_dir();
        $uploadDir = $uploadDir['basedir'] . '/wp-listings-fields';

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777);
        }

        return $uploadDir;
    }

    /**
     * Makes sure to enqueu the js in the footer
     * @return void
     */
    public static function enqueueJs()
    {
        add_action('wp_footer', function () {
            wp_print_scripts('wp-listings');
        }, 9999);
    }
}
