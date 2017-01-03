<?php

namespace WpListings;

class Search
{
    protected $skip = array('category', 'place', 's');

    public function __construct()
    {
        add_filter('pre_get_posts', array($this, 'taxonomy'));
        add_filter('pre_get_posts', array($this, 'meta'));
    }

    public function shouldFilter(\WP_Query $query)
    {
        if (!is_admin() && is_post_type_archive(\WpListings\Listings::$postTypeSlug) && $query->is_main_query() && $query->is_search()) {
            return true;
        }

        return false;
    }

    public function taxonomy($query)
    {
        if (!$this->shouldFilter($query)) {
            return $query;
        }

        $taxQuery = array();

        if (isset($_GET['category']) && intval($_GET['category']) !== 0) {
            $taxQuery[] = array(
                'taxonomy' => \WpListings\Listings::$taxonomySlug,
                'field' => 'id',
                'terms' => intval($_GET['category']),
            );
        }

        if (isset($_GET['place']) && intval($_GET['place']) !== 0) {
            $taxQuery[] = array(
                'taxonomy' => \WpListings\Listings::$placesTaxonomySlug,
                'field' => 'term_id',
                'terms' => intval($_GET['place']),
            );
        }

        if (!empty($taxQuery)) {
            $query->set('tax_query', array_merge(array('relation' => 'AND'), $taxQuery));
        }

        return $query;
    }

    public function meta($query)
    {
        if (!$this->shouldFilter($query)) {
            return $query;
        }

        $metaQuery = array('relation' => 'AND');
        $fields = array_diff_key($_GET, array_flip($this->skip));

        foreach ($fields as $field => $value) {
            $metaQuery[] = array(
                'key' => $field,
                'value' => $value,
                'compare' => '='
            );
        }

        $query->set('meta_query', $metaQuery);
        return $query;
    }
}
