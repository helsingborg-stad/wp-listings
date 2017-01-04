# WP Listings

A simple listings plugin to use for example buy and sell.

## Getting started

1. Install and activate the plugin.
2. In wp-admin go to *"listings -> places"* and add your wanted places.
3. In wp-admin go to *"listings -> categories"* and add your wanted categories.
4. In wp-admin go to *"listings -> options"* and change the options to your prefered settings.
5. Use the shortcode ```[wp-listings-form]``` to display the listings submission form in any post or page.

## Templates

You probably want to create a ```archive-listings``` and a ```single-listings``` in your theme.

- ```archive-listings``` will handle the search and listing of the listings.
- ```single-listings``` will handle displaying a single listing.

## Shortcodes

#### [wp-listings-form]

Displays the listings submission form.

#### [wp-listings-search-form]

Displays the search form. You can use this shortcode in your ```archive-listings``` template to display the search form there.

```php
<?php echo do_shortcode('[wp-listings-search-form]'); ?>
```

#### [wp-listings-contact-form]

Displays the contact form which you can contact a seller through. You can use this in your ```single-listings``` template.

```php
<?php echo do_shortcode('[wp-listings-contact-form]'); ?>
```

