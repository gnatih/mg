<?php

if (! function_exists('mix')) {
  function mix($path)
  {
    $pathWithOutSlash = ltrim($path, '/');
    $pathWithSlash = '/'.ltrim($path, '/');
    $manifestFile = get_theme_file_path('mix-manifest.json');

    if (! $manifestFile) {
      return get_template_directory_uri().'/'.$pathWithOutSlash;
    }

    $manifestArray = json_decode(file_get_contents($manifestFile), true);

    if (file_exists(get_template_directory().'/hot')) {
      return 'http://localhost:8080/'.$pathWithOutSlash;
    }

    if (array_key_exists($pathWithSlash, $manifestArray)) {
      return get_template_directory_uri().'/'.ltrim($manifestArray[$pathWithSlash], '/');
    }

    return get_template_directory_uri().'/'.$pathWithOutSlash;
  }
}

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

add_filter('woocommerce_add_to_cart_validation', function ($passed, $pid, $quantity, $vid = '', $variations = '') {
  if (! WC()->cart->is_empty()) {
    WC()->cart->empty_cart();
  }

  return $passed;
}, 10, 5);

add_filter('wc_add_to_cart_message_html', function ($message) {
  return '';
});
