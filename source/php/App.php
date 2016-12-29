<?php

namespace WpListings;

class App
{
    public static $uploadDir;

    public function __construct()
    {
        self::$uploadDir = $this->getUploadDir();
        add_filter('acf/settings/load_json', array($this, 'jsonLoadPath'));

        new \WpListings\Listings();
        new \WpListings\FrontendForm();
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
}
