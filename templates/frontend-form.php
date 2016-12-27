<div class="wp-listings-form">
    <form action="?wp-listing=add">
        <?php wp_nonce_field('wp-listing-add', 'nonce', true, true); ?>

        <div class="grid">
            <div class="grid-md-12">
                <div class="form-group">
                    <label for="title"><?php _e('Title', 'wp-listings'); ?></label>
                    <input type="text" name="title" id="title">
                </div>
            </div>
        </div>

        <div class="grid">
            <div class="grid-md-12">
                <div class="form-group">
                    <label for="category"><?php _e('Category', 'wp-listings'); ?></label>
                    <?php
                    wp_dropdown_categories(array(
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
                    <label for="<?php echo $field['key']; ?>"><?php echo $field['label']; ?></label>
                    <?php echo self::getFieldMarkup($field); ?>
                </div>
                <?php endforeach; ?>
            </div>
        </fieldset>
        <?php endforeach; ?>

        <div class="grid">
            <div class="grid-md-12">
                <div class="form-group">
                    <label for="description"><?php _e('Description', 'wp-listings'); ?></label>
                    <textarea name="description" id="description" rows="10"></textarea>
                </div>
            </div>
        </div>

        <div class="grid">
            <div class="grid-md-12">
                <div class="form-group">
                    <label for="price"><?php _e('Price', 'wp-listings'); ?></label>
                    <div class="input-group">
                        <input type="number" name="price" min="0" step="1" class="form-control">
                        <span class="input-group-addon"><?php echo apply_filters('wp-listings/currency', 'SEK'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid">
            <div class="grid-md-12">
                <div class="form-group">
                    <label for="name"><?php _e('Your name', 'wp-listings'); ?></label>
                    <input type="text" name="name">
                </div>
            </div>
        </div>

        <div class="grid">
            <div class="grid-md-6">
                <div class="form-group">
                    <label for="email"><?php _e('Your email address', 'wp-listings'); ?></label>
                    <input type="email" name="email">
                </div>
            </div>
            <div class="grid-md-6">
                <div class="form-group">
                    <label for="phone"><?php _e('Your phone number', 'wp-listings'); ?></label>
                    <input type="tel" name="phone">
                </div>
            </div>
        </div>

        <div class="grid">
            <div class="grid-md-12">
                <input type="submit" class="btn btn-primary" value="<?php _e('Submit', 'wp-listings'); ?>">
            </div>
        </div>
    </form>
</div>
