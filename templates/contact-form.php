<form method="post" action="" class="creamy gutter gutter-lg">
    <?php if (isset($_GET['wp-listings-form']) && $_GET['wp-listings-form'] == 'success') : ?>
    <div class="grid gutter gutter-bottom">
        <div class="grid-md-12">
            <div class="notice success">
                <?php _e('Your message has been delivered to the seller.', 'wp-lisings'); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php wp_nonce_field('contact_seller', 'wp-listings', true, true); ?>
    <input type="hidden" name="listing_id" value="<?php echo get_the_id(); ?>">

    <h2><?php _e('Contact the seller', 'municipio'); ?></h2>

    <?php if (get_field('listing_display_seller_phone_number')) : ?>
    <strong><?php _e('Phone number', 'municipio'); ?>:</strong> <?php echo get_post_meta(get_the_id(), 'listing_seller_phone_number', true); ?>
    <?php endif; ?>

    <h3 class="gutter gutter-lg gutter-vertical"><?php _e('Send email', 'municipio'); ?></h3>
    <div class="grid">
        <div class="grid-md-6">
            <div class="form-group">
                <label for="contact-name"><?php _e('Your name', 'municipio'); ?><span class="text-danger">*</span></label>
                <input type="text" name="name" id="contact-name" required>
            </div>
        </div>
        <div class="grid-md-6">
            <div class="form-group">
                <label for="contact-email"><?php _e('Your email', 'municipio'); ?><span class="text-danger">*</span></label>
                <input type="email" name="email" id="contact-email" required>
            </div>
        </div>
    </div>

    <div class="grid">
        <div class="grid-md-12">
            <div class="form-group">
                <label for="contact-message"><?php _e('Message', 'municipio'); ?><span class="text-danger">*</span></label>
                <textarea name="message" id="contact-message" rows="10" required></textarea>
            </div>
        </div>
    </div>

    <div class="grid">
        <div class="grid-md-12">
            <input type="submit" class="btn btn-primary" value="<?php _e('Send', 'municipio'); ?>">
        </div>
    </div>
</form>
