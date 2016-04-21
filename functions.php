<?php

include_once 'functions-backend.php';
include_once 'functions-helpers.php';

/**
 * Add stylesheets and scripts
 */
add_action( 'wp_enqueue_scripts', 'otm_enqueue_styles_and_scripts' );
function otm_enqueue_styles_and_scripts() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'dashicons' );
	wp_enqueue_script( 'otm-script', get_stylesheet_directory_uri() . '/script.js', array(), '1.0.0', true );
	wp_enqueue_style( 'selectric-style', get_stylesheet_directory_uri() . '/selectric.css' );
	wp_enqueue_script( 'selectric-script', get_stylesheet_directory_uri() . '/jquery.selectric.min.js', array(), '1.0.0', true );
}

/**
 * Disable admin bar for subscribers
 */
add_filter( 'show_admin_bar', 'otm_disable_admin_bar_for_subscribers' );
function otm_disable_admin_bar_for_subscribers() {
	return current_user_can( 'editor' ) ? true : false;
}

/**
 * Display unlimited number of documents on the Documents archive page
 */
add_action( 'pre_get_posts', 'otm_unlimited_documents', 1 );
function otm_unlimited_documents( $query ) {
	if ( is_post_type_archive( 'document' ) ) {
		$query->set( 'posts_per_page', -1 );
		return;
	}
}
