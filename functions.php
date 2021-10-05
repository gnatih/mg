<?php
/**
 * Mapping Governance Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package mg
 */

add_action( 'wp_enqueue_scripts', 'eduma_parent_theme_enqueue_styles' );

/**
 * Enqueue scripts and styles.
 */
function eduma_parent_theme_enqueue_styles() {
	wp_enqueue_style( 'eduma-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'mg-style',
		get_stylesheet_directory_uri() . '/style.css',
		[ 'eduma-style' ]
	);
}
