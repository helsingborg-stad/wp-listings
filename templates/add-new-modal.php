
<?php if (get_field('listing_add_new_button','option'))  { ?>

    <div id="addNewListing" class="modal modal-medium modal-backdrop-1" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="modal-header">
                <a class="btn btn-close" href="#close"></a>
                <h2 class="modal-title"><?php _e("Add new listing", 'wp-listings'); ?></h2>
            </div>
            <div class="modal-body gutter-bottom">
                <?php echo do_shortcode('[wp-listings-form]'); ?>
            </div>
        </div><!-- /.modal-content -->

        <a href="#close" class="backdrop"></a>
    </div><!-- /.modal -->

<?php } ?>
