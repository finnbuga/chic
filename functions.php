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

/**
 * Display 30 documents per page
 */
add_action( 'pre_get_posts', 'otm_display_30_documents_per_page', 1 );
function otm_display_30_documents_per_page( $query ) {
	global $pagenow;

	if ( is_post_type_archive('document') && 'edit.php' != $pagenow || is_search()) {
		$query->set( 'posts_per_page', 30 );
		return;
	}
}

/**
 * Restrict search to documents
 */
add_filter('pre_get_posts','otm_restrict_search_to_documents');
function otm_restrict_search_to_documents($query) {
	if ($query->is_search && !is_admin()) {
		$query->set( 'post_type', array('document') );
	}
	return $query;
}

/**
 * Set default options
 */
add_action( 'init', 'otm_set_default_options' );
//add_action( 'after_switch_theme', 'otm_set_default_options' );
function otm_set_default_options( $query ) {
	update_option( 'default_comment_status', 'closed' );
	update_option( 'uploads_use_yearmonth_folders', false );
}

/**
 * Remove / add sidebars
 */
add_action( 'widgets_init', 'otm_manage_sidebars', 11 );
function otm_manage_sidebars(){
	unregister_sidebar( 'sidebar-2' );
	unregister_sidebar( 'sidebar-3' );
	register_sidebar( array(
		'name' => __( 'Front Page Header', 'otm' ),
		'id' => 'front',
		'description' => __( 'Appears on Front Page', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
