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
                    <label for="description"><?php _e('Description', 'wp-listings'); ?></label>
                    <textarea name="description" id="description" rows="10"></textarea>
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
    </form>
</div>
