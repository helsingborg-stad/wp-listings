<?php
    $settings = array(
        'description' => get_field('listing_archive_page_description','option'),
        'addnew' => get_field('listing_add_new_button','option')
    );

    if (!empty($settings['description']) && $settings['addnew']) {
        $fullWidth = false;
    } else {
        $fullWidth = true;
    }
?>

<?php if (!empty($settings['description']) || $settings['addnew']) { ?>
    <div class="grid">

        <?php if (!empty($settings['description']))  { ?>
            <div class="grid-xs-<?php echo $fullWidth ? '12' : '8'; ?>">
                <article>
                    <?php echo apply_filters('the_content', $settings['description']); ?>
                </article>
            </div>
        <?php } ?>

        <?php if ($settings['addnew'])  { ?>
            <div class="grid-xs-<?php echo $fullWidth ? '12' : '4'; ?>">
                <a href="<?php echo get_post_type_archive_link('listing'); ?>#addNewListing" class="btn pull-right"><?php _e("Add new listing", 'wp-listings'); ?></a>
            </div>
        <?php } ?>

    </div>
<?php } ?>
