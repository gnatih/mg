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
    $page = get_page_by_path('profile', OBJECT, 'page');
    wp_safe_redirect(get_permalink($page->ID));
    exit;
  }
});

add_action('elementor/widgets/widgets_registered', function () {
  require __DIR__.'/widgets/product-button.php';
  \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Product_Button_Widget());
});

add_shortcode('woocommerce_cart_icon', function () {
  ob_start();

  $cart_count = WC()->cart->cart_contents_count;
  $cart_url = wc_get_cart_url();

  echo '<li><a class="menu-item cart-contents" href="'.$cart_url.'" title="Cart"><i class="fa fa-shopping-cart"></i>';

  if ($cart_count > 0) {
    echo '<span class="cart-contents-count" style="display:inline-block;">'.$cart_count.'</span>';
  }

  echo '</a></li>';

  return ob_get_clean();
});

// add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
//   ob_start();

//   $cart_count = WC()->cart->cart_contents_count;
//   $cart_url = wc_get_cart_url();

//   echo '<a class="cart-contents menu-item" href="'.$cart_url.'" title="View Cart"><i class="fa fa-shopping-cart"></i>';

//   if ($cart_count > 0) {
//     echo '<span class="cart-contents-count">'.$cart_count.'</span>';
//   }
//   echo '</a>';

//   $fragments['a.cart-contents'] = ob_get_clean();

//   return $fragments;
// });

add_filter('wp_nav_menu_items', function ($menu, $args) {
  if ($args->theme_location == 'primary') {
    $cart = do_shortcode('[woocommerce_cart_icon]');

    return $menu.$cart;
  }

  return $menu;
}, 10, 2);


add_filter('gettext', function ($translated_text, $text, $domain) {
  if ($translated_text == 'Return to shop') {
    $translated_text = 'View all Maps';
  }

  return $translated_text;
}, 20, 3);
