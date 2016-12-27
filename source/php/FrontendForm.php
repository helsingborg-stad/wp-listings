<?php

namespace WpListings;

class FrontendForm
{
    public function __construct()
    {
        // Register js
        add_action('init', function () {
            wp_register_script('wp-listings', WPLISTINGS_URL . '/dist/js/wp-listings.min.js', null, '1.0.0', true);
        });

        // Add shortcode
        add_shortcode('wp-listings-form', array($this, 'showForm'));
    }

    /**
     * Shows the frontend form
     * @return void
     */
    public function showForm()
    {
        $this->enqueueJs();
        $fieldgroups = \WpListings\Listings::getCategoryFieldsgroups();

        $template = apply_filters('wp-listings/form_template', WPLISTINGS_TEMPLATE_PATH . '/frontend-form.php');

        include_once $template;
    }

    /**
     * Makes sure to enqueu the js in the footer
     * @return void
     */
    public function enqueueJs()
    {
        add_action('wp_footer', function () {
            wp_print_scripts('wp-listings');
        }, 9999);
    }
}
