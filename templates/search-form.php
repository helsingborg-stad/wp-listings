<div class="wp-listings-search">
    <form action="" method="get">
        <div class="grid grid-table">
            <div class="grid-auto">
                <div class="form-group">
                    <input type="search" name="s" placeholder="<?php _e('Search'); ?>…">
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
                            'hierarchical' => true
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
                            'name' => 'palce',
                            'id' => 'palce',
                            'orderby' => 'name',
                            'hide_empty' => false,
                            'hierarchical' => true
                        ));
                    ?>
                </div>
            </div>
            <div class="grid-fit-content">
                <input type="submit" value="<?php _e('Search'); ?>" class="btn btn-primary">
            </div>
        </div>
    </form>
</div>
