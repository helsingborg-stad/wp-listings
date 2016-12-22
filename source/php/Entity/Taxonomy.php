<?php

namespace WpListings\Entity;

abstract class Taxonomy
{
    public $namePlural;
    public $nameSingular;
    public $slug;
    public $args;
    public $postTypes;

    public function __construct($namePlural, $nameSingular, $slug, $args, $postTypes)
    {
        $this->namePlural = $namePlural;
        $this->nameSingular = $nameSingular;
        $this->slug = $slug;
        $this->args = $args;
        $this->postTypes = $postTypes;

        add_action('init', array($this, 'registerTaxonomy'));
    }

    public function registerTaxonomy()
    {
        $labels = array(
            'name'              => $this->namePlural,
            'singular_name'     => $this->nameSingular,
            'search_items'      => sprintf(__('Search %s', 'wp-listings'), $this->namePlural),
            'all_items'         => sprintf(__('All %s', 'wp-listings'), $this->namePlural),
            'parent_item'       => sprintf(__('Parent %s:', 'wp-listings'), $this->nameSingular),
            'parent_item_colon' => sprintf(__('Parent %s:', 'wp-listings'), $this->nameSingular) . ':',
            'edit_item'         => sprintf(__('Edit %s', 'wp-listings'), $this->nameSingular),
            'update_item'       => sprintf(__('Update %s', 'wp-listings'), $this->nameSingular),
            'add_new_item'      => sprintf(__('Add New %s', 'wp-listings'), $this->nameSingular),
            'new_item_name'     => sprintf(__('New %s Name', 'wp-listings'), $this->nameSingular),
            'menu_name'         => $this->nameSingular,
        );

        $this->args['labels'] = $labels;

        register_taxonomy($this->slug, $this->postTypes, $args);
    }
}
