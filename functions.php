<?php

include_once 'includes.php';

add_action('wp_enqueue_scripts', function () {
  wp_enqueue_style('eduma-style', get_template_directory_uri().'/style.css');
  wp_enqueue_style('mg-style', mix('/style.css'), ['eduma-style']);

  wp_enqueue_script('mg-script', mix('/script.js'), ['jquery']);
});

add_filter('learn-press/profile-tabs', function ($defaults) {
  unset($defaults['orders']);
  unset($defaults['settings']['sections']['avatar']);
  $defaults['lp_orders_woocommerce']['title'] = 'My Maps';

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

add_action('learn-press/course-buttons', function () {
  $page = get_page_by_path('membership/all-maps');

  if ($page) {
    echo '<a class="lp-button button" href="'.get_permalink($page->ID).'" style="margin-left: 1em">Pricing plans</a>';
  }
}, 5);

add_filter('gettext', function ($translated_text, $text, $domain) {
  switch ($translated_text) {
        case 'Return to shop':
            $translated_text = 'View all maps';
            break;
    }

  return $translated_text;
}, 20, 3);

add_filter('woocommerce_return_to_shop_redirect', function () {
  return esc_url(get_bloginfo('url').'/all-maps');
});

add_filter('woocommerce_new_pass_redirect', function ($user) {
  return esc_url(get_bloginfo('url').'/profile');
});


add_shortcode('profile_links', function () {
  $output = '<div class="widget_login-popup"><div class="thim-link-login">';

  if (is_user_logged_in()) {
    $output .= '<a class="profile" href="'.get_bloginfo('url').'/profile">Profile</a><a class="logout" href="'.wp_logout_url('/').'">Logout</a>';
  } else {
    $output .= '<a href="'.get_bloginfo('url').'/login">Login</a>';
  }

  $output .= '</div></div>';

  echo $output;
});

add_filter('lostpassword_url', function () {
  return site_url('account/?action=lostpassword');
}, 10, 0);

add_filter('login_redirect', function ($redirect_to, $request, $user) {
  return home_url().'/profile';
}, 10, 3);

add_filter('wp_login_failed', function ($username) {
  $referer = $_SERVER['HTTP_REFERER'];

  if (! empty($referer) && ! strstr($referer, 'wp-login') && ! strstr($referer, 'wp-admin')) {
    wp_redirect(preg_replace('/\?.*/', '', $referer).'/?login=failed');
    exit;
  }
});

// add_filter('authenticate', function ($username, $pwd) {
//   $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;

//   if ((empty($username) || empty($pwd)) && ! empty($referer)) {
//     if (! strstr($referer, 'wp-login') && ! strstr($referer, 'wp-admin')) {
//       wp_redirect(preg_replace('/\?.*/', '', $referer).'/?login=failed');
//       exit;
//     }
//   }
// }, 10, 2);

add_shortcode('login_fail_message', function () {
  if (isset($_GET['login']) && $_GET['login'] == 'failed') {
    echo '<div class="login_fail_message" style="font-size: 14px; background-color: #ca5151;color: #ffffff;display: block; text-align: center;padding: 9px 15px; width: fit-content;margin: 0 auto;">
			<span style="color: #ca5151;background-color: #fff;width: 20px;height: 20px;display: inline-flex;align-items: center;justify-content: center;font-weight: 900;border-radius: 50%;margin-right: 10px;">!</span>Oops! Looks like you have entered the wrong username or password. Please check your login details and try again.
		</div>';
  }
});

add_action('admin_menu', function () {
  global $menu;
  global $submenu;

  $menu[5][0] = 'Sources';
  $submenu['edit.php'][5][0] = 'All Sources';
  $submenu['edit.php'][10][0] = 'Add Source';
  // unset($submenu['edit.php'][15][0]);
});

add_action('init', function () {
  unregister_taxonomy_for_object_type('post_tag', 'post');
  unregister_taxonomy_for_object_type('category', 'post');

  global $wp_post_types;
  $labels = &$wp_post_types['post']->labels;
  $labels->name = 'Sources';
  $labels->singular_name = 'Source';
  $labels->add_new = 'Add Source';
  $labels->add_new_item = 'Add Source';
  $labels->edit_item = 'Edit Source';
  $labels->new_item = 'Source';
  $labels->view_item = 'View Source';
  $labels->search_items = 'Search Sources';
  $labels->name_admin_bar = 'Sources';
  $labels->menu_name = 'Sources';
});

function mg_ajax_add_to_cart()
{
  $product_id = absint($_POST['product_id']);
  $quantity = 1;
  $product_status = get_post_status($product_id);

  if (WC()->cart->add_to_cart($product_id, $quantity) && 'publish' == $product_status) {
    wc_add_to_cart_message([$product_id => $quantity], true);
    WC_AJAX :: get_refreshed_fragments();
  } else {
    echo wp_send_json([
      'error' => true,
      'product_url' => get_permalink($product_id)
    ]);
    wp_die();
  }
}

add_action('wp_ajax_mg_add_to_cart', 'mg_ajax_add_to_cart');
add_action('wp_ajax_nopriv_mg_add_to_cart', 'mg_ajax_add_to_cart');
