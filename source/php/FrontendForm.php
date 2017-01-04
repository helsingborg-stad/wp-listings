<?php

namespace WpListings;

class FrontendForm
{
    public function __construct()
    {
        // Add shortcode
        add_shortcode('wp-listings-form', array($this, 'showForm'));

        add_action('init', array($this, 'submitForm'));
    }

    /**
     * Handle form submissions
     * @return void
     */
    public function submitForm()
    {
        if (!isset($_REQUEST['wp-listing-nonce']) || !wp_verify_nonce($_REQUEST['wp-listing-nonce'], 'wp-listing-add')) {
            return false;
        }

        do_action('wp-listings/frontend_form/before_submit');

        $postId = wp_insert_post(array(
            'post_type' => \WpListings\Listings::$postTypeSlug,
            'post_title' => $_POST['title'],
            'post_content' => $_POST['description'],
            'post_status' => get_field('lising_review', 'option') ? 'draft' : 'publish',
            'tax_input' => array(
                \WpListings\Listings::$taxonomySlug => $_POST['category'],
                \WpListings\Listings::$placesTaxonomySlug => $_POST['place']
            ),
            'meta_input' => array(
                'listing_price' => isset($_POST['price']) && !empty($_POST['price']) ? $_POST['price'] : 0,
                'listing_seller_name' => isset($_POST['name']) && !empty($_POST['name']) ? $_POST['name'] : null,
                'listing_seller_email' => isset($_POST['email']) && !empty($_POST['email']) ? $_POST['email'] : null,
                'listing_seller_phone_number' => isset($_POST['phone']) && !empty($_POST['phone']) ? $_POST['phone'] : null,
                'listing_display_seller_phone_number' => isset($_POST['hide_phone']) && $_POST['hide_phone'] == 1 ? 0 : 1
            )
        ), false);

        $defaultKeys = array(
            'title',
            'description',
            'price',
            'name',
            'place',
            'email',
            'phone',
            '_wp_http_referer',
            'wp-listing-nonce',
            'category'
        );

        $additionalMeta = array_filter($_POST, function ($key) use ($defaultKeys) {
            return !in_array($key, $defaultKeys);
        }, ARRAY_FILTER_USE_KEY);

        foreach ($additionalMeta as $key => $value) {
            update_post_meta($postId, $key, $value);
        }

        // Upload images
        $images = isset($_POST['image_uploader_file']) && is_array($_POST['image_uploader_file']) ? $_POST['image_uploader_file'] : null;
        $uploadedImages = array();

        if ($images) {
            $uploader = new \WpListings\ImageUploader();

            $i = 0;
            foreach ($images as $base64image) {
                $image = $uploader->uploadBase64($base64image);
                $uploadedImages[] = $image['url'];

                // Set as thumbnail if first image
                if ($i == 0) {
                    $attachmentId = wp_insert_attachment(
                        array(
                            'guid' => $image['url'],
                            'post_mime_type' => wp_check_filetype(basename($image['url']), null)['type'],
                            'post_title' => $_POST['title'],
                            'post_content' => '',
                            'post_status' => 'inherit'
                        ),
                        $image['path'],
                        $postId
                    );

                    set_post_thumbnail($postId, $attachmentId);
                }

                $i++;
            }
        }

        update_post_meta($postId, 'listings_images', $uploadedImages);

        do_action('wp-listings/frontend_form/after_submit');

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

    /**
     * Shows the frontend form
     * @return void
     */
    public function showForm()
    {
        if (in_array('submission_logged_in', (array)get_field('listing_access_restrictions', 'option')) && !is_user_logged_in()) {
            $msg = '<div class="grid"><div class="grid-md-12">' . __('You need to login to use the listing submission form.', 'wp-lisings') . '</div></div>';
            $msg = apply_filters('wp-listings/form/login_required', $msg);

            echo $msg;
            return;
        }

        \WpListings\App::enqueueJs();
        $fieldgroups = \WpListings\Listings::getCategoryFieldgroups();

        $template = apply_filters('wp-listings/form_template', WPLISTINGS_TEMPLATE_PATH . '/frontend-form.php');

        include_once $template;
    }

    /**
     * Get markup for a field
     * @param  array $field  Acf field args
     * @return string
     */
    public static function getFieldMarkup($field, $forceRequired = null, $allOption = false, $current = false)
    {
        switch ($field['type']) {
            case 'select':
                return self::getSelectField($field, $forceRequired, $allOption, $current);

            default:
                return self::getTextField($field, $forceRequired, $current);
        }
    }

    /**
     * Get markup for select field
     * @param  array $args  Field args (acf)
     * @return string       Field markup
     */
    public static function getSelectField($args, $forceRequired = null, $allOption = false, $current = false) : string
    {
        $markup .= '<select name="' . $args['name'] . '" id="' . $args['key'] . '" placeholder="' . $args['placeholder'] . '" ';

        if ($forceRequired === true || ($forceRequired === null && $args['required'])) {
            $markup .= 'required';
        }

        $markup .= '>';

        if (is_string($allOption)) {
            $markup .= '<option value="0">' . $allOption . '</option>';
        }

        foreach ($args['choices'] as $choice) {
            $markup .= '<option value="' . $choice . '" ' . selected($choice, $current, false) . '>' . $choice . '</option>';
        }

        $markup .= '</select>';

        return $markup;
    }

    /**
     * Get markup for text field
     * @param  array $args  Field args (acf)
     * @return string       Field markup
     */
    public static function getTextField($args, $forceRequired = null, $current = false)
    {
        $markup = '<input type="text" name="' . $args['name'] . '" id="' . $args['key'] . '" placeholder="' . $args['placeholder'] . '" ';

        if ($forceRequired === true || ($forceRequired === null && $args['required'])) {
            $markup .= 'required';
        }

        if ($current) {
            $markup .= ' value="' . $current . '"';
        }

        $markup .= '>';

        return $markup;
    }
}
