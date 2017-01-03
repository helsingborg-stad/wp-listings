<?php

namespace WpListings;

class ContactForm
{
    public function __construct()
    {
        add_shortcode('wp-listings-contact-form', array($this, 'showForm'));
        add_action('init', array($this, 'submit'));
    }

    public function submit()
    {
        if (!isset($_POST['message']) || !wp_verify_nonce($_POST['wp-listings'], 'contact_seller')) {
            return;
        }

        var_dump("CONTACT SELLER");
        exit;
    }

    public function showForm()
    {
        if (in_array('submission_logged_in', (array)get_field('listing_access_restrictions', 'option')) && !is_user_logged_in()) {
            $msg = '<div class="grid"><div class="grid-md-12">' . __('You need to login to use the listing submission form.', 'wp-lisings') . '</div></div>';
            $msg = apply_filters('wp-listings/form/login_required', $msg);

            echo $msg;
            return;
        }

        $template = apply_filters('wp-listings/contact_template', WPLISTINGS_TEMPLATE_PATH . '/contact-form.php');

        include_once $template;
    }
}
