<?php

namespace WpListings\Entity;

abstract class PostType
{
    public $namePlural;
    public $nameSingular;
    public $slug;
    public $args;

    public $tableColumns = array();
    public $tableSortableColumns = array();
    public $tableColumnsContentCallback = array();

    /**
     * Registers a custom post type
     * @param string $namePlural
     * @param string $nameSingular
     * @param string $slug
     * @param array  $args
     */
    public function __construct($namePlural, $nameSingular, $slug, $args = array())
    {
        $this->namePlural = $namePlural;
        $this->nameSingular = $nameSingular;
        $this->slug = $slug;
        $this->args = $args;

        add_action('init', array($this, 'registerPostType'));
    }

    /**
     * Register the actual post type
     * @return string Registered post type slug
     */
    public function registerPostType()
    {
        $labels = array(
            'name'                => $this->nameSingular,
            'singular_name'       => $this->nameSingular,
            'add_new'             => sprintf(__('Add new %s', 'wp-listings'), $this->nameSingular),
            'add_new_item'        => sprintf(__('Add new %s', 'wp-listings'), $this->nameSingular),
            'edit_item'           => sprintf(__('Edit %s', 'wp-listings'), $this->nameSingular),
            'new_item'            => sprintf(__('New %s', 'wp-listings'), $this->nameSingular),
            'view_item'           => sprintf(__('View %s', 'wp-listings'), $this->nameSingular),
            'search_items'        => sprintf(__('Search %s', 'wp-listings'), $this->namePlural),
            'not_found'           => sprintf(__('No %s found', 'wp-listings'), $this->namePlural),
            'not_found_in_trash'  => sprintf(__('No %s found in trash', 'wp-listings'), $this->namePlural),
            'parent_item_colon'   => sprintf(__('Parent %s:', 'wp-listings'), $this->nameSingular),
            'menu_name'           => $this->namePlural,
        );

        $this->args['labels'] = $labels;

        register_post_type($this->slug, $this->args);
        return $this->slug;
    }
}
