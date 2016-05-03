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
	wp_enqueue_style( 'selectric-style', get_stylesheet_directory_uri() . '/bower_components/jquery-selectric/public/selectric.css' );
	wp_enqueue_script( 'selectric-script', get_stylesheet_directory_uri() . '/bower_components/jquery-selectric/public/jquery.selectric.min.js', array(), '1.0.0', true );
}

/**
 * Disable admin bar for non-managers
 */
add_filter( 'show_admin_bar', 'otm_disable_admin_bar_for_subscribers' );
function otm_disable_admin_bar_for_subscribers() {
	return current_user_can( 'manager' ) ? true : false;
}
