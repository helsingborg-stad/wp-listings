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

        add_action('init', array($this, 'submitForm'));
    }

    /**
     * Handle form submissions
     * @return void
     */
    public function submitForm()
    {
        if (!wp_verify_nonce($_REQUEST['wp-listing-nonce'], 'wp-listing-add')) {
            return false;
        }

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
                'lising_seller_email' => isset($_POST['email']) && !empty($_POST['email']) ? $_POST['email'] : null,
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
        $this->enqueueJs();
        $fieldgroups = \WpListings\Listings::getCategoryFieldgroups();

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

    /**
     * Get markup for a field
     * @param  array $field  Acf field args
     * @return string
     */
    public static function getFieldMarkup($field)
    {
        switch ($field['type']) {
            case 'select':
                return self::getSelectField($field);

            default:
                return self::getTextField($field);
        }
    }

    /**
     * Get markup for select field
     * @param  array $args  Field args (acf)
     * @return string       Field markup
     */
    public static function getSelectField($args) : string
    {
        $markup .= '<select name="' . $args['name'] . '" id="' . $args['key'] . '" placeholder="' . $args['placeholder'] . '" ';

        if ($args['required']) {
            $markup .= 'required';
        }

        $markup .= '>';

        foreach ($args['choices'] as $choice) {
            $markup .= '<option value="' . $choice . '">' . $choice . '</option>';
        }

        $markup .= '</select>';

        return $markup;
    }

    /**
     * Get markup for text field
     * @param  array $args  Field args (acf)
     * @return string       Field markup
     */
    public static function getTextField($args)
    {
        $markup = '<input type="text" name="' . $args['name'] . '" id="' . $args['key'] . '" placeholder="' . $args['placeholder'] . '" ';

        if ($args['required']) {
            $markup .= 'required';
        }

        $markup .= '>';

        return $markup;
    }
}
