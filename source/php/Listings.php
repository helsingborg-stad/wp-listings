<?php

namespace WpListings;

class Listings extends \WpListings\Entity\PostType
{
    public static $postTypeSlug;
    public static $taxonomySlug;
    public static $placesTaxonomySlug;

    public function __construct()
    {
        self::$postTypeSlug = $this->postType();
        self::$taxonomySlug = $this->categories();

        self::$placesTaxonomySlug = $this->placesTaxonomy();

        add_action('init', array($this, 'removeListingWithPassword'));

        add_action('created_term', array($this, 'createTermsFieldJson'), 10, 3);
        add_action('edited_term', array($this, 'createTermsFieldJson'), 10, 3);

        add_action('publish_listing', array($this, 'published'), 10, 2);
        add_action('delete_listing', array($this, 'unpublish'));

        //Price field
        add_filter('acf/load_field/key=field_585ce00694033', array($this, 'requirePrice'));
        add_filter('acf/load_value/name=listing_documents', array($this, 'removeHiddenDocuments'), 10, 3);

        //Default contact
        add_filter('acf/load_value/name=listing_seller_name', array($this, 'defaultSellerName'), 10, 3);
        add_filter('acf/load_value/name=listing_seller_email', array($this, 'defaultSellerEmail'), 10, 3);

        //Hide terms
        add_filter('get_the_terms', array($this, 'hideCategoryTerm'), 10, 3);
        add_filter('get_the_terms', array($this, 'hideLocationTerm'), 10, 3);

        // Only one taxonomy (place and categories)
        add_filter('wp_terms_checklist_args', array($this, 'termsChecklistArgs'));

        // Templates
        add_filter('template_include', array($this, 'loadTemplate'));

        add_action('wp', function () {
            if (get_post_type() === \WpListings\Listings::$postTypeSlug && in_array('view_logged_in', (array)get_field('listing_access_restrictions', 'option')) && !is_user_logged_in()) {
                do_action('wp-listings/view/require_login');

                global $wp_query;
                $wp_query->set_404();
                status_header(404);
            }
        });
    }

    /**
     * Create post type
     * @return void
     */
    public function postType() : string
    {
        // Create posttype
        $postType = new \WpListings\Entity\PostType(
            _x('Listings', 'Post type plural', 'wp-listings'),
            _x('Listing', 'Post type singular', 'wp-listings'),
            'listing',
            array(
                'description'          =>   __('WP Listings could be used for any type of "buy and sell" ads.', 'wp-listings'),
                'menu_icon'            =>   'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHRpdGxlPnRyYWRlPC90aXRsZT48cGF0aCBkPSJNNS41MiA2MC40OGE0LjY0IDQuNjQgMCAwIDEtNC42Mi00QTQ5LjIxIDQ5LjIxIDAgMCAxIDkuODYgMjFDMjUuNjQ2LS43IDU1LjgwNi01Ljk1NCA3OCA5LjEzbDIuMDktMi44OWExLjUgMS41IDAgMCAxIDIuNjIuMzRsNS41MiAxNC4zM2ExLjUgMS41IDAgMCAxLTEuNDcgMmwtMTUuMzMtLjc1YTEuNSAxLjUgMCAwIDEtMS4xNC0yLjM4TDcyLjM2IDE3QzU0LjUxMiA0Ljk5MyAzMC4zNjQgOS4yNTIgMTcuNyAyNi42NGEzOS41NyAzOS41NyAwIDAgMC0zIDQuOTNoMTIuNWEuOTEuOTEgMCAwIDEgLjggMS4zNiA3LjExIDcuMTEgMCAwIDAtLjY0IDUuNCA2LjMyIDYuMzIgMCAwIDAgMy44NSAzLjggMTEuNzggMTEuNzggMCAwIDAgOS43OS0uNzRsOS40LTUuMjZhLjY1LjY1IDAgMCAxIC44Mi4xNWwxOS42NSAyNCAyLjQzIDIuOTNhNCA0IDAgMCAxLS44OSA2IDQuMDYgNC4wNiAwIDAgMS00LjkzLS42MWwtLjMzLS4zMmEuMDkuMDkgMCAwIDAtLjE0LjA4IDQgNCAwIDAgMS0xLjcyIDQuMDggNC4xIDQuMSAwIDAgMS01LjA3LS41OGwtLjMtLjI5YS4wOS4wOSAwIDAgMC0uMTQuMDggNCA0IDAgMCAxLTEuNzIgNC4wOCA0LjEgNC4xIDAgMCAxLTUuMDYtLjU2bC0uMy0uMjlhLjA5LjA5IDAgMCAwLS4xNC4wOCA0IDQgMCAwIDEtNi4yIDRsLjM4LS42N2E3LjIyIDcuMjIgMCAwIDAtMS04LjcgNyA3IDAgMCAwLTMuODMtMS45MyA2LjkzIDYuOTMgMCAwIDAtNC4wNS0zLjE2QTYuOTQgNi45NCAwIDAgMCAzMyA1OS43MWE2LjkgNi45IDAgMCAwLTIuNzYtMy44NyA3LjMzIDcuMzMgMCAwIDAtOS40OSAxLjU0bC0yLjU0IDMuMTFINS41MnYtLjAxem0yMi41NS0xLjgyYTMuNDggMy40OCAwIDAgMC00LjQzLjg5TDIxIDYyLjc5YTMuMzQgMy4zNCAwIDAgMCAuNTMgNC43NWwuNC4zMmEzLjM0IDMuMzQgMCAwIDAgMy43NC4yNi4xNi4xNiAwIDAgMSAuMjQuMTcgMy4zNSAzLjM1IDAgMCAwIDEuMjIgMy4yOGwuMzMuMjZhMy4zNiAzLjM2IDAgMCAwIDMuNjEuMzUuMTcuMTcgMCAwIDEgLjI1LjE3IDMuMzcgMy4zNyAwIDAgMCAxLjI3IDNsLjIzLjE4YTMuMzkgMy4zOSAwIDAgMCAzLjY1LjM0LjE5LjE5IDAgMCAxIC4yOS4xNGMuMTMuODQuNTczIDEuNiAxLjI0IDIuMTNsLjE0LjExYTMuNDIgMy40MiAwIDAgMCA1LjA4LTFsLjQ5LS44N2EzLjUzIDMuNTMgMCAwIDAtLjU5LTQuMzggMy40IDMuNCAwIDAgMC0zLjctLjU4LjEuMSAwIDAgMS0uMTQtLjFBMy4zNiAzLjM2IDAgMCAwIDM0LjY5IDY4Yy0uNS4wOS0uMzktLjM1LS4zOS0uMzVhMy4zNiAzLjM2IDAgMCAwLTQuNzMtNC4yMmMtLjM4LjA1LS4zMy0uMjktLjMzLS4yOWEzLjM0IDMuMzQgMCAwIDAtMS4xNy00LjQ4em02OS4zNi0yMi44N2wtLjMyLTFhNC42NyA0LjY3IDAgMCAwLTQuNDMtMy4xOGgtMTFhMTEuODUgMTEuODUgMCAwIDEtNC4yMi0uNzhMNTYuNzcgMjIuOWExMi45MSAxMi45MSAwIDAgMC0xMi40MiAxLjc5bC0xMyA5Ljg3YTIuNDcgMi40NyAwIDAgMCAuNjUgNC4wOCA4LjE1IDguMTUgMCAwIDAgNy4yMy0uMzZsMTEuMDYtNi4xOWEyIDIgMCAwIDEgMi40OS40N2wyNC4xMSAyOS4zYS42MS42MSAwIDAgMCAuODYuMDlsMS0uODNhMi44MiAyLjgyIDAgMCAxIDEuNzgtLjYzaDcuODNhMzkuODQgMzkuODQgMCAwIDEtNjIuNTIgMjEuMjVMMjguMDUgNzlhMS41IDEuNSAwIDAgMC0xLTIuNDRMMTEuNzggNzVhMS41IDEuNSAwIDAgMC0xLjU4IDJsNC43MSAxNC42MWExLjUgMS41IDAgMCAwIDIuNTkuMzlsMi4yNC0yLjc3YzE3LjAzMiAxMy4xMzMgNDAuNTk0IDEzLjc3MyA1OC4zMTQgMS41ODQgMTcuNzItMTIuMTkgMjUuNTUtMzQuNDIgMTkuMzc2LTU1LjAyNHoiIGZpbGw9IiMwMDAiIGZpbGwtcnVsZT0iZXZlbm9kZCIvPjwvc3ZnPg==',
                'public'               =>   true,
                'publicly_queriable'   =>   true,
                'show_ui'              =>   true,
                'show_in_nav_menus'    =>   true,
                'has_archive'          =>   true,
                'rewrite'              =>   array(
                    'slug'       =>   __('listing', 'wp-listings'),
                    'with_front' =>   false
                ),
                'hierarchical'          =>  false,
                'exclude_from_search'   =>  false,
                'taxonomies'            =>  array(),
                'supports'              =>  array('title', 'revisions', 'editor', 'thumbnail')
            )
        );

        $postType->addTableColumn(
            'category',
            __('Category'),
            true,
            function ($column, $postId) {
                $i = 0;
                $categories = get_the_terms($postId, self::$taxonomySlug);
                foreach ((array)$categories as $category) {
                    if ($i > 0) {
                        echo ', ';
                    }

                    echo isset($category->name) ? $category->name : '';
                    $i++;
                }
            }
        );

        $postType->addTableColumn(
            'place',
            __('Place'),
            true,
            function ($column, $postId) {
                $i = 0;
                $places = get_the_terms($postId, self::$placesTaxonomySlug);
                foreach ((array)$places as $place) {
                    if ($i > 0) {
                        echo ', ';
                    }

                    echo isset($place->name) ? $place->name : '';
                    $i++;
                }
            }
        );

        return $postType->slug;
    }

    /**
     * Create category taxonomy
     * @return void
     */
    public function categories() : string
    {
        $categories = new \WpListings\Entity\Taxonomy(
            __('Category', 'wp-listings'),
            __('Categories', 'wp-listings'),
            'listing-category',
            array('listing'),
            array(
                'hierarchical' => true
            )
        );

        return $categories->slug;
    }

    public function placesTaxonomy()
    {
        $places = new \WpListings\Entity\Taxonomy(
            __('Place', 'wp-listings'),
            __('Places', 'wp-listings'),
            'listing-places',
            array('listing'),
            array(
                'hierarchical' => true
            )
        );

        return $places->slug;
    }

    /**
     * Get fields for all categories
     * @return array
     */
    public static function getCategoryFieldgroups() : array
    {
        // Get all fieldgroups from ACF
        $fieldgroups = acf_get_field_groups();

        // Filter the fieldgroups to only include wp-listings fieldgroups
        $fieldgroups = array_values(array_filter($fieldgroups, function ($item) {
            return substr($item['key'], 0, 11) === 'wp-listings';
        }));

        // Get fields for each fieldgroup
        foreach ($fieldgroups as &$fieldgroup) {
            $fieldgroup['fields'] = acf_get_fields($fieldgroup['key']);
        }

        return apply_filters('wp-listings/get_category_fieldgroups', $fieldgroups);
    }

    /**
     * Create ACF JSON exports for category specific fields
     * @param  int    $termId    Term id
     * @param  int    $taxId     Tax id
     * @param  string $tax       Tax slug
     * @return void
     */
    public function createTermsFieldJson($termId, $taxId, $tax)
    {
        $terms = get_terms(
            $tax,
            array(
                'hide_empty' => false
            )
        );

        foreach ($terms as $term) {
            $fields = get_field('listing_category_fields', $tax . '_' . $term->term_id);

            if (empty($fields)) {
                continue;
            }

            $json = $this->getCategoryFields($tax, $term, $fields);

            $filename = \WpListings\App::$uploadDir . '/' . $tax . '-' . $term->slug . '.json';

            $fp = fopen($filename, 'w');
            fwrite($fp, $json);
            fclose($fp);
        }
    }

    /**
     * Adds fields for category
     * @param string $tax    Taxonomy slug
     * @param object $term   Term object
     * @param array $fields  Fields data
     */
    public function getCategoryFields($tax, $term, $fields) : string
    {
        // Add local field group for category
        $fieldgroup = array(
            'key' => 'wp-listings_' . $tax . '_' . $term->slug . '_' . $term->term_id,
            'title' => $term->name,
            'fields' => array(),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'listing',
                    ),
                    array(
                        'param' => 'post_taxonomy',
                        'operator' => '==',
                        'value' => $tax . ':' . $term->slug,
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => 1,
            'description' => '',
        );

        foreach ($fields as $field) {
            $fieldgroup['fields'][] = $this->getFieldArray($field);
        }

        return json_encode($fieldgroup);
    }

    /**
     * Get field array acf style
     * @param  array $field Field params
     * @return array        Acf field array
     */
    public function getFieldArray($field) : array
    {
        $array = array(
            'key' => 'field_' . uniqid(),
            'label' => $field['label'],
            'name' => sanitize_title($field['label']),
            'instructions' => '',
            'required' => in_array('required', $field['options']),
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
            'wp_listings_options' => $field['options']
        );

        switch ($field['type']) {
            case 'select':
                $array['type'] = 'select';
                $array['choices'] = array();
                foreach ($field['alternatives'] as $alternative) {
                    $array['choices'][$alternative['value']] = $alternative['value'];
                }
                break;

            case 'text':
                $array['type'] = 'text';
                break;
        }

        return $array;
    }

    /**
     * Get places from taxonomy
     * @return array Places
     */
    public static function places() : array
    {
        return get_terms(
            self::$placesTaxonomySlug,
            array(
                'hide_empty' => false
            )
        );
    }

    /**
     * Loads correct template
     * @param  string $template Default template
     * @return string           Template to use
     */
    public function loadTemplate($template) : string
    {
        if (get_post_type() !== self::$postTypeSlug) {
            return $template;
        }

        $templateType = '';

        if (is_archive() || is_post_type_archive()) {
            $templateType = 'archive';
        } elseif (is_single()) {
            $templateType = 'single';
        }

        return apply_filters('wp-listings/' . $templateType . '_template', $template);
    }

    /**
     * What happens when a listing post is publisched
     * @param  int $postId    The post id
     * @param  WP_Post $post  WP post object
     * @return void
     */
    public function published($postId, $post)
    {
        if (isset(\WpListings\App::$postTypeSlug) && $post->post_type !== \WpListings\App::$postTypeSlug) {
            return;
        }

        $listingPassword = wp_generate_password(8, false, false);
        update_post_meta($postId, '_listing_password', sha1($listingPassword));

        // Send notification mail to seller
        $sellerName = get_post_meta($postId, 'listing_seller_name', true);
        $sellerEmail = get_post_meta($postId, 'listing_seller_email', true);

        $mailTemplate = get_field('listing_published_message', 'option');
        if (is_null($mailTemplate)) {
            $mailTemplate = __('Congratulations %1$s! Your listing is now approved and published.<br>Your password to remove the ad is: <strong>%3$s</strong><br>You can see your listing here: %2$s', 'wp-listings');
        }

        $headers = array('Content-type: text/html; charset=UTF-8');

        wp_mail(
            $sellerEmail,
            __('Listing published', 'wp-listings'),
            sprintf(
                $mailTemplate,
                $sellerName,
                get_permalink($postId),
                $listingPassword
            ),
            $headers
        );

        // Schedule deletion after X days
        $daysToDelete = (int) get_field('listing_days_valid', 'option');
        if (!empty($daysToDelete) && is_numeric($daysToDelete)) {
            $timestamp = time() + ($daysToDelete * (3600 * 24));
            wp_schedule_single_event($timestamp, 'delete_listing', array(
                $postId
            ));
        } else {
            wp_clear_scheduled_hook('delete_listing');
        }
    }

    /**
     * Unpublish after x days (cron)
     * @param  int $postId    Post id
     * @return boolean
     */
    public function unpublish($postId) : boolean
    {
        $post = get_post($postId);

        $daysToDelete = 30;

        // Notify seller
        $sellerName = get_post_meta($postid, 'listing_seller_name', true);
        $sellerEmail = get_post_meta($postId, 'listing_seller_email', true);

        $mailTemplate = get_field('listing_unpublished_message', 'option');
        if (is_null($mailTemplate)) {
            $mailTemplate = __('Hi %s! Your listing "%s" is now %d days old and therefor it has beed unpublished.', 'wp-listings');
        }

        $headers = array('Content-type: text/html; charset=UTF-8');

        wp_mail(
            $sellerEmail,
            __('Listing unpublished', 'wp-listings'),
            sprintf(
                $mailTemplate,
                $sellerName,
                $daysToDelete
            ),
            $headers
        );

        // Trash the post
        return wp_trash_post($postId);
    }

    public function termsChecklistArgs($args) : array
    {
        if (isset($args['taxonomy']) && in_array($args['taxonomy'], array(self::$taxonomySlug, self::$placesTaxonomySlug))) {
            $args['walker'] = new \WpListings\CategoryChecklistWalker;
            $args['checked_ontop'] = false;
        }

        return $args;
    }

    /**
     * Check if price should be a required field
     * @return array
     */
    public function requirePrice($field) : array
    {
        if (get_field('listing_price', 'option')) {
            $field['required'] = 1;
        } else {
            $field['required'] = 0;
        }
        return $field;
    }

     /**
     * Check if price should be a required field
     * @return string
     */

    public function removeHiddenDocuments($value, $post_id, $field)
    {
        if (!is_admin() && is_array($value)) {
            foreach ($value as $key => $item) {
                if ($item['field_5881dfb87d60c'] == 0) {
                    unset($value[$key]);
                }
            }
        }

        return $value;
    }

    /**
     * Fill form with default contact name
     * @return array
     */

    public function defaultSellerName($value, $post_id, $field)
    {
        if (empty($value) && get_field('listings_default_contact', 'option')) {
            return get_field('listings_default_contact_name', 'option');
        }

        return $value;
    }

    /**
     * Fill form with default contact email
     * @return array
     */

    public function defaultSellerEmail($value, $post_id, $field)
    {
        if (empty($value) && get_field('listings_default_contact', 'option')) {
            return get_field('listings_default_contact_email', 'option');
        }

        return $value;
    }

    public function hideCategoryTerm($terms, $post_id, $taxonomy)
    {
        if ($taxonomy == \WpListings\Listings::$placesTaxonomySlug && !is_admin() && !get_field('listings_show_categories', 'option')) {
            return array();
        }
        return $terms;
    }

    public function hideLocationTerm($terms, $post_id, $taxonomy)
    {
        if ($taxonomy == \WpListings\Listings::$taxonomySlug && !is_admin() && !get_field('listings_show_location', 'option')) {
            return array();
        }
        return $terms;
    }

    public function removeListingWithPassword()
    {
        if (!isset($_POST['remove-password'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['_wpnonce'], 'remove_listing')) {
            return;
        }

        $postId = $_POST['post_id'];
        $listingPassword = get_post_meta($postId, '_listing_password', true);

        if ($listingPassword !== sha1($_POST['remove-password'])) {
            wp_redirect($_SERVER['HTTP_REFERER'] . '?fail=true');
            exit;
        }

        wp_trash_post($postId);

        wp_redirect(get_post_type_archive_link('listing'));
        exit;
    }
}
