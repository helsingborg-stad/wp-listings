<?php

namespace WpListings;

class Archive
{
    private $buttonPrinted = false;

    public function __construct()
    {
        add_action( 'wp-listings/archive/before', array($this, 'addBeforeListing') );
        add_action( 'wp_footer', array($this, 'addSubmitNewModal') );
    }

     public function addSubmitNewModal () {
        if($this->isArchiveListing()) {
            include apply_filters('wp-listings/submit_new_modal', WPLISTINGS_TEMPLATE_PATH . '/add-new-modal.php');
        }
    }

    public function addBeforeListing () {
        if($this->isArchiveListing()) {
            include apply_filters('wp-listings/before_archive_template', WPLISTINGS_TEMPLATE_PATH . '/description.php');
        }
    }

    private function isArchiveListing() : bool {
        global $wp_query;
        if(!is_admin() && is_post_type_archive(\WpListings\Listings::$postTypeSlug) && $wp_query->is_main_query() ) {
            return true;
        }
        return false;
    }

}
