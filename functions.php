<?php
/**
 * Ortografía Cerebral functions and definitions
 *
 * @package Ortografía_Cerebral
 */

if ( ! function_exists( 'oc_setup' ) ) :
	function oc_setup() {

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );

		register_nav_menus( array(
			'top' => esc_html__( 'Top Menu', 'oc' ),
		) );
	}
endif;
add_action( 'after_setup_theme', 'oc_setup' );


/**
 * Enqueue scripts and styles.
 */
function oc_scripts() {
	wp_enqueue_style( 'oc-style', get_stylesheet_uri() );
	// wp_enqueue_script( 'oc-vue-js', 'http://localhost:8080/js/build.js', array(), false, true );
	wp_enqueue_script( 'oc-vue-js', get_template_directory_uri() . '/js/build.js', array(), false, true );
}
add_action( 'wp_enqueue_scripts', 'oc_scripts' );

/**
 * Adding API routes & methods
 */
require_once get_template_directory() . '/api/email.php';
require_once get_template_directory() . '/api/menu.php';