<?php

include_once 'includes.php';

add_action('wp_enqueue_scripts', function () {
  wp_enqueue_style('eduma-style', get_template_directory_uri().'/style.css');
  wp_enqueue_style('mg-style', mix('/style.css'), ['eduma-style']);
});

add_filter('learn-press/profile-tabs', function ($defaults) {
  $defaults['orders']['title'] = 'My Maps';
  unset($defaults['settings']['sections']['avatar']);

  return $defaults;
}, 1001);

add_action('admin_init', function () {
  add_filter('manage_posts_columns', function ($columns) {
    unset($columns['categories']);
    unset($columns['tags']);

    return $columns;
  }, 10, 1);
});

add_filter('learn-press/override-templates', function () {
  return true;
});

add_filter('registration_redirect', function () {
  return home_url();
});


add_filter('woocommerce_loop_add_to_cart_link', function ($add_to_cart_html) {
  return str_replace('Add to cart', 'Buy now!', $add_to_cart_html);
});

add_filter('woocommerce_is_sold_individually', function ($true, $product) {
  $true = true;

  return $true;
}, 10, 2);

add_filter('woocommerce_product_single_add_to_cart_text', function ($product) {
  return 'Buy now!';
});

// add_filter('woocommerce_add_to_cart_validation', function ($passed, $pid, $quantity, $vid = '', $variations = '') {
//   if (! WC()->cart->is_empty()) {
//     WC()->cart->empty_cart();
//   }

//   return $passed;
// }, 10, 5);

add_filter('wc_add_to_cart_message_html', function ($message) {
  return '';
});

add_action('woocommerce_thankyou', function ($order_id) {
  $order = wc_get_order($order_id);

  if (! $order->has_status('failed')) {
    wp_safe_redirect('/');
    exit;
  }
});

add_action('elementor/widgets/widgets_registered', function () {
  require __DIR__.'/widgets/product-button.php';
  \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Product_Button_Widget());
});
