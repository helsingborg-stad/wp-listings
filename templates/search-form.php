<div class="wp-listings-form wp-listings-search-form">
    <form action="" method="get">
        <div class="grid grid-table">
            <div class="grid-auto">
                <div class="form-group">
                    <input type="search" name="s" placeholder="<?php _e('Search'); ?>…" value="<?php echo get_search_query(); ?>">
                </div>
            </div>
            <div class="grid-auto">
                <div class="form-group">
                    <?php
                        wp_dropdown_categories(array(
                            'show_option_all' => __('All categories', 'wp-listings'),
                            'taxonomy' => \WpListings\Listings::$taxonomySlug,
                            'name' => 'category',
                            'id' => 'category',
                            'orderby' => 'name',
                            'hide_empty' => false,
                            'hierarchical' => true,
                            'selected' => isset($_GET['category']) ? $_GET['category'] : null
                        ));
                    ?>
                </div>
            </div>
            <div class="grid-auto">
                <div class="form-group">
                    <?php
                        wp_dropdown_categories(array(
                            'show_option_all' => __('All places', 'wp-listings'),
                            'taxonomy' => \WpListings\Listings::$placesTaxonomySlug,
                            'name' => 'place',
                            'id' => 'place',
                            'orderby' => 'name',
                            'hide_empty' => false,
                            'hierarchical' => true,
                            'selected' => isset($_GET['place']) ? $_GET['place'] : null
                        ));
                    ?>
                </div>
            </div>
            <div class="grid-fit-content">
                <input type="submit" value="<?php _e('Search'); ?>" class="btn btn-primary">
            </div>
        </div>

        <?php
        foreach ($fieldgroups as $fieldgroup) :
            $fieldgroupKey = explode('_', $fieldgroup['key']);
            $fieldgroupKey = $fieldgroupKey[count($fieldgroupKey)-1];
        ?>
        <fieldset class="grid" data-fieldgroup-key="<?php echo $fieldgroupKey; ?>" style="display: none;" disabled>
            <div class="grid-md-12">
                <?php foreach ($fieldgroup['fields'] as $field) : ?>
                <div class="form-group">
                    <label for="<?php echo $field['key']; ?>">
                        <?php echo $field['label']; ?>
                    </label>
                    <?php echo \WpListings\FrontendForm::getFieldMarkup($field, false, __('All', 'wp-listings'), isset($_GET[$field['name']]) ? $_GET[$field['name']] : false); ?>
                </div>
                <?php endforeach; ?>
            </div>
        </fieldset>
            <?php endforeach; ?>
    </form>
</div>
