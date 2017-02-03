<div id="removeListing" class="modal modal-small modal-backdrop-1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-content">
        <form action="" method="post">
            <div class="modal-header">
                <a class="btn btn-close" href="#close"></a>
                <h2 class="modal-title"><?php _e("Remove listing", 'wp-listings'); ?></h2>
            </div>
            <div class="modal-body">
                <div class="gutter">
                    <?php if (isset($_GET['fail']) && $_GET['fail'] == 'true') : ?>
                    <div class="notice danger gutter gutter-bottom gutter-margin">
                        <i class="pricon pricon-notice-warning pricon-sm"></i> <?php _e('Listing password incorrect.', 'wp-listings'); ?>
                    </div>
                    <?php endif; ?>

                    <?php wp_nonce_field('remove_listing'); ?>
                    <input type="hidden" name="post_id" value="<?php the_id(); ?>">

                    <p>
                        <?php _e('To remove the listing you need to have the listing removal password at hand. Fill in the password below and click the remove button.'); ?>
                    </p>
                    <p style="margin: 15px 0;">
                        <?php _e('Note: This will permanently remove the listing.'); ?>
                    </p>

                    <div class="form-group">
                        <label for="remove-password">Password</label>
                        <input type="password" id="remove-password" name="remove-password">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-danger" value="<?php _e('Remove listing', 'wp-listings'); ?>">
                <a href="#close" class="btn"><?php _e('Cancel'); ?></a>
            </div>
        </form>
    </div><!-- /.modal-content -->

    <a href="#close" class="backdrop"></a>
</div><!-- /.modal -->
