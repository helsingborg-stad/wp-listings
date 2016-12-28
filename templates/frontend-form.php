<div class="wp-listings-form gutter gutter-top">
    <?php if (isset($_GET['wp-listings-form']) && $_GET['wp-listings-form'] == 'success') : ?>
    <div class="grid">
        <div class="grid-md-12">
            <div class="notice success">
                Tack, vi har tagit emot din annons. Den väntar nu på granskning innan den publiceras.
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="grid">
        <div class="grid-md-12">
            <form action="" method="post">
                <?php wp_nonce_field('wp-listing-add', 'wp-listing-nonce', true, true); ?>

                <div class="grid">
                    <div class="grid-md-12">
                        <div class="form-group">
                            <label for="title"><?php _e('Title', 'wp-listings'); ?><span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" minlength="2" required>
                        </div>
                    </div>
                </div>

                <div class="grid">
                    <div class="grid-md-12">
                        <div class="form-group">
                            <label for="place"><?php _e('Place', 'wp-listings'); ?><span class="text-danger">*</span></label>
                            <select name="place" id="place" required>
                                <?php foreach (\WpListings\Listings::places() as $place) : ?>
                                <option value="<?php echo $place; ?>"><?php echo $place; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="grid">
                    <div class="grid-md-12">
                        <div class="form-group">
                            <label for="category"><?php _e('Category', 'wp-listings'); ?><span class="text-danger">*</span></label>
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
                            <label for="<?php echo $field['key']; ?>">
                                <?php echo $field['label']; ?><?php if ($field['required']) : ?><span class="text-danger">*</span><?php endif; ?>
                            </label>
                            <?php echo self::getFieldMarkup($field); ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </fieldset>
                <?php endforeach; ?>

                <div class="grid">
                    <div class="grid-md-12">
                        <div class="form-group">
                            <label for="description"><?php _e('Description', 'wp-listings'); ?><span class="text-danger">*</span></label>
                            <textarea name="description" id="description" rows="10" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="grid">
                    <div class="grid-md-12">
                        <div class="form-group">
                            <label for="price"><?php _e('Price', 'wp-listings'); ?><span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="price" min="0" step="1" class="form-control" required>
                                <span class="input-group-addon"><?php echo apply_filters('wp-listings/currency', 'SEK'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid">
                    <div class="grid-md-12">
                        <div class="form-group">
                            <label for="name"><?php _e('Your name', 'wp-listings'); ?><span class="text-danger">*</span></label>
                            <input type="text" name="name" required>
                        </div>
                    </div>
                </div>

                <div class="grid">
                    <div class="grid-md-6">
                        <div class="form-group">
                            <label for="email"><?php _e('Your email address', 'wp-listings'); ?><span class="text-danger">*</span></label>
                            <input type="email" name="email" required>
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
    </div>
</div>
