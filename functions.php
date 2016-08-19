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
add_action( 'after_switch_theme', 'otm_set_default_options' );
function otm_set_default_options( $query ) {
	// WP Core
	update_option( 'uploads_use_yearmonth_folders', false );

	// Theme My Login plugin
	update_option( 'theme_my_login', array(
		'enable_css'     => true,
		'login_type'     => 'default',
		'active_modules' => array(
			'custom-redirection/custom-redirection.php',
			'security/security.php',
			'themed-profiles/themed-profiles.php',
		),
		'version'        => '6.4.5',
	) );
	update_option( 'theme_my_login_redirection', array (
		'administrator' =>
			array (
				'login_type' => 'referer',
				'login_url' => '',
				'logout_type' => 'referer',
				'logout_url' => '',
			),
		'editor' =>
			array (
				'login_type' => 'referer',
				'login_url' => '',
				'logout_type' => 'referer',
				'logout_url' => '',
			),
		'author' =>
			array (
				'login_type' => 'referer',
				'login_url' => '',
				'logout_type' => 'referer',
				'logout_url' => '',
			),
		'contributor' =>
			array (
				'login_type' => 'referer',
				'login_url' => '',
				'logout_type' => 'referer',
				'logout_url' => '',
			),
		'subscriber' =>
			array (
				'login_type' => 'referer',
				'login_url' => '',
				'logout_type' => 'referer',
				'logout_url' => '',
			),
		'manager' =>
			array (
				'login_type' => 'referer',
				'login_url' => '',
				'logout_type' => 'referer',
				'logout_url' => '',
			),
		'member' =>
			array (
				'login_type' => 'referer',
				'login_url' => '',
				'logout_type' => 'referer',
				'logout_url' => '',
			),
	) );
	update_option( 'theme_my_login_security', array(
		'private_site'  => false,
		'private_login' => true,
		'failed_login'  =>
			array(
				'threshold'               => 5,
				'threshold_duration'      => 1,
				'threshold_duration_unit' => 'hour',
				'lockout_duration'        => 24,
				'lockout_duration_unit'   => 'hour',
			),
	) );
	update_option( 'theme_my_login_themed_profiles', array(
		'administrator' =>
			array(
				'theme_profile'  => true,
				'restrict_admin' => false,
			),
		'editor'        =>
			array(
				'theme_profile'  => true,
				'restrict_admin' => false,
			),
		'author'        =>
			array(
				'theme_profile'  => true,
				'restrict_admin' => true,
			),
		'contributor'   =>
			array(
				'theme_profile'  => true,
				'restrict_admin' => true,
			),
		'subscriber'    =>
			array(
				'theme_profile'  => true,
				'restrict_admin' => true,
			),
		'manager'       =>
			array(
				'theme_profile'  => true,
				'restrict_admin' => false,
			),
		'member'        =>
			array(
				'theme_profile'  => true,
				'restrict_admin' => true,
			),
	) );
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

/**
 * Cleanup the widgets list
 */
add_action( 'widgets_init', 'otm_unregister_default_widgets', 11 );
function otm_unregister_default_widgets() {
	unregister_widget( 'WP_Widget_Pages' );
	unregister_widget( 'WP_Widget_Calendar' );
	unregister_widget( 'WP_Widget_Archives' );
	unregister_widget( 'WP_Widget_Links' );
	unregister_widget( 'WP_Widget_Meta' );
	unregister_widget( 'WP_Widget_Search' );
	unregister_widget( 'WP_Widget_Categories' );
	unregister_widget( 'WP_Widget_Recent_Posts' );
	unregister_widget( 'WP_Widget_Recent_Comments' );
	unregister_widget( 'WP_Widget_RSS' );
	unregister_widget( 'WP_Widget_Tag_Cloud' );
	unregister_widget( 'WP_Nav_Menu_Widget' );
}

