<?php

// unregister_taxonomy('theme');

add_action('wp_enqueue_scripts', function () {
  wp_enqueue_style('eduma-style', get_template_directory_uri().'/style.css');
  wp_enqueue_style('mg-style', get_stylesheet_directory_uri().'/style.css', ['eduma-style']);
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
