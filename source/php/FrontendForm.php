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
