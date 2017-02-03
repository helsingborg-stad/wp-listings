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

        $mailTemplate = get_field('new_contact_form_message', 'option');
        if (is_null($mailTemplate)) {
            $mailTemplate = '<strong>' . __('You have got a new message about your ad "%1$s" from %2$s, <%3$s>') . '</strong><br><br>%4$s';
        }

        $message = apply_filters(
            'wp-listings/contact/email/message',
            sprintf(
                $mailTemplate,
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

        // All done, lets redirect back to form
        $redirect = $_SERVER['HTTP_REFERER'];
        if (strpos($redirect, '?') === false) {
            $redirect .= '?';
        } else {
            $redirect .= '&';
        }

        $redirect .= 'wp-listings-form=success';

        wp_redirect($redirect);
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

        if (!wp_listings_show_contact_form()) {
            return;
        }

        $template = apply_filters('wp-listings/contact_template', WPLISTINGS_TEMPLATE_PATH . '/contact-form.php');

        ob_start();
        include $template;
        $form = ob_get_clean();

        return $form;
    }
}
