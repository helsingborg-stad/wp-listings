<div class="wp-listings-form gutter gutter-top">
    <?php if (isset($_GET['wp-listings-form']) && $_GET['wp-listings-form'] == 'success') : ?>
    <div class="grid">
        <div class="grid-md-12">
            <div class="notice success">
                <?php if (get_field('lising_review', 'option')) {
                    _e('Thank you, we are now reviewing your listing. You will receive a email when your listing is published.', 'wp-listings');
                } else {
                    _e('Thank you, your listing is now published.', 'wp-listings');
                }
                ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="grid">
        <div class="grid-md-12">
            <form action="" method="post" class="gutter gutter-vertical">
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
                            <?php
                            wp_dropdown_categories(array(
                                'taxonomy' => \WpListings\Listings::$placesTaxonomySlug,
                                'name' => 'place',
                                'id' => 'place',
                                'orderby' => 'name',
                                'hide_empty' => false,
                                'hierarchical' => true
                            ));
                            ?>
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
                        <div class="form-group gutter gutter-bottom gutter-sm">
                            <label><?php _e('Images', 'wp-listings'); ?></label>
                            <span class="text-sm">
                                <?php _e('Note: The first image will be the main image for the listing.', 'wp-listings'); ?><br>
                                <?php _e('Max filesize', 'wp-listings'); ?>: 2mb<br>
                                <?php _e('Allowed filetypes', 'wp-listings'); ?>: JPG, PNG
                            </span>
                        </div>

                        <div class="image-upload inline-block" data-max-files="1" data-max-size="2000" data-preview-image="true" style="width:250px;height:250px;">
                            <div class="placeholder">
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-picture-o fa-stack-2x"></i>
                                    <i class="fa fa-plus-circle fa-stack-1x"></i>
                                </span>
                                <div class="placeholder-text">
                                    <span class="placeholder-text-drag"><?php _e('Drag a photo here', 'wp-listings'); ?></span>
                                    <span class="placeholder-text-browse">
                                        <em class="placeholder-text-or"><?php _e('or', 'wp-listings'); ?></em>
                                        <label for="listing-image-1" class="btn btn-secondary btn-select-file"><?php _e('Select a photo', 'wp-listings'); ?></label>
                                    </span>
                                </div>
                            </div>
                            <div class="placeholder placeholder-is-dragover">
                                <span><?php _e('Drop here', 'wp-listings'); ?></span>
                            </div>
                            <div class="selected-file"></div>
                            <input type="file" id="listing-image-1" name="listing-image[]" class="hidden">
                        </div>

                        <div class="image-upload inline-block" data-max-files="1" data-max-size="2000" data-preview-image="true" style="width:250px;height:250px;">
                            <div class="placeholder">
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-picture-o fa-stack-2x"></i>
                                    <i class="fa fa-plus-circle fa-stack-1x"></i>
                                </span>
                                <div class="placeholder-text">
                                    <span class="placeholder-text-drag"><?php _e('Drag a photo here', 'wp-listings'); ?></span>
                                    <span class="placeholder-text-browse">
                                        <em class="placeholder-text-or"><?php _e('or', 'wp-listings'); ?></em>
                                        <label for="listing-image-2" class="btn btn-secondary btn-select-file"><?php _e('Select a photo', 'wp-listings'); ?></label>
                                    </span>
                                </div>
                            </div>
                            <div class="placeholder placeholder-is-dragover">
                                <span><?php _e('Drop here', 'wp-listings'); ?></span>
                            </div>
                            <div class="selected-file"></div>
                            <input type="file" id="listing-image-2" name="listing-image[]" class="hidden">
                        </div>

                        <div class="image-upload inline-block" data-max-files="1" data-max-size="2000" data-preview-image="true" style="width:250px;height:250px;">
                            <div class="placeholder">
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-picture-o fa-stack-2x"></i>
                                    <i class="fa fa-plus-circle fa-stack-1x"></i>
                                </span>
                                <div class="placeholder-text">
                                    <span class="placeholder-text-drag"><?php _e('Drag a photo here', 'wp-listings'); ?></span>
                                    <span class="placeholder-text-browse">
                                        <em class="placeholder-text-or"><?php _e('or', 'wp-listings'); ?></em>
                                        <label for="listing-image-3" class="btn btn-secondary btn-select-file"><?php _e('Select a photo', 'wp-listings'); ?></label>
                                    </span>
                                </div>
                            </div>
                            <div class="placeholder placeholder-is-dragover">
                                <span><?php _e('Drop here', 'wp-listings'); ?></span>
                            </div>
                            <div class="selected-file"></div>
                            <input type="file" id="listing-image-3" name="listing-image[]" class="hidden">
                        </div>
                    </div>
                </div>

                <?php if (wp_listings_use_price()) : ?>
                <div class="grid">
                    <div class="grid-md-12">
                        <div class="form-group">
                            <label for="price"><?php _e('Price', 'wp-listings'); ?><span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="price" min="0" step="1" class="form-control" required>
                                <span class="input-group-addon"><?php echo apply_filters('wp-listings/currency', ':-'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

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
                        <div class="form-group">
                            <label class="checkbox">
                                <input type="checkbox" name="hide_phone" value="1"> <?php _e('Hide my phone number', 'wp-listings'); ?>
                            </label>
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
