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

        do_action('wp-listings/contact/before_submit');

        $listing = get_post($_POST['listing_id']);

        if (!$listing) {
            return false;
        }

        $fromName = sanitize_text_field($_POST['name']);
        $fromEmail = sanitize_text_field($_POST['email']);

        $seller = get_post_meta($listing->ID, 'listing_seller_email', true);
        $subject = apply_filters('wp-listings/contact/email/subject', __('Re: ' . $listing->post_title), $listing);
        $message = apply_filters(
            'wp-listings/contact/email/message',
            sprintf(
                '<strong>' . __('You have got a new message about your ad "%1$s" from %2$s, <%3$s>') . '</strong><br><br>%4$s',
                '<a href="' . get_permalink($listing->ID) . '">' . $listing->post_title . '</a>',
                $fromName,
                $fromEmail,
                $_POST['message']
            ),
            $listing
        );
        $headers = array('Content-type: text/html; charset=UTF-8', 'From: ' . $fromName . ' <' . $fromEmail . '>');

        $mail = wp_mail($seller, $subject, $message, $headers);

        do_action('wp-listings/contact/after_submit');

        return $mail;
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
