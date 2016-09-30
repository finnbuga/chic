<?php

include_once 'functions-fontend.php';
include_once 'functions-backend.php';
include_once 'functions-documents.php';
include_once 'functions-helpers.php';

/**
 * Add logo support
 */
add_action( 'after_setup_theme', 'otm_add_logo_support' );
function otm_add_logo_support() {
	add_theme_support( 'custom-logo', array(
		'height'     => 32,
		'flex-width' => true,
	) );
}

/**
 * Redirect user to homepage on login / logout
 */
add_filter( 'login_redirect', create_function( '$url,$query,$user', 'return home_url();' ), 10, 3 );
add_filter( 'logout_redirect', create_function( '$url,$query,$user', 'return home_url();' ), 10, 3 );


/**
 * Add / remove sidebars

 * Remove sidebar-2 and sidebar-3. Add new sidebar: Front Page Header.
 */
add_action( 'widgets_init', 'otm_manage_sidebars', 11 );
function otm_manage_sidebars() {
	unregister_sidebar( 'sidebar-2' );
	unregister_sidebar( 'sidebar-3' );

	register_sidebar( array(
		'id'            => 'front',
		'name'          => __( 'Front Page Header', 'otm' ),
		'description'   => __( 'Appears on Front Page', 'otm' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}

/**
 * Disable organising uploads into month- and year-based folders
 */
add_action( 'after_switch_theme', 'otm_disable_yearmonth_folders' );
function otm_disable_yearmonth_folders( $query ) {
	update_option( 'uploads_use_yearmonth_folders', false );
}

/**
 * Theme My Login plugin - Enable Security and Themed Profiles modules
 */
add_filter( 'tml_default_options', 'otm_set_tml_default_options' );
function otm_set_tml_default_options() {
	return array(
		'enable_css'     => true,
		'login_type'     => 'default',
		'active_modules' => array(
			'security/security.php',
			'themed-profiles/themed-profiles.php',
		),
	);
}

/**
 * Theme My Login plugin - Security module - Enable private login
 */
add_action( 'after_switch_theme', 'otm_set_tml_security_default_options' );
function otm_set_tml_security_default_options( $query ) {
	if ( !method_exists('Theme_My_Login_Security','default_options') ) {
		return;
	}

	$theme_my_login_security = Theme_My_Login_Security::default_options();
	$theme_my_login_security['private_login'] = true;
	update_option( 'theme_my_login_security', $theme_my_login_security);
}
