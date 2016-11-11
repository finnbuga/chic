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

/**
 * Override the admin notification email that is sent when a new user registers.
 *
 * The default address that is used by the plugin is the address that is set in the WordPress General Settings
 * (admin menu Settings > General).
 * This filter can set some other address and can also be used to send to multiple addresses.
 */
add_filter( 'wpmem_notify_addr', 'otm_admin_email' );
function otm_admin_email( $email ) {

	// single email example
	$email = 'networks@otmconsulting.com';

	// multiple emails example
	// $email = 'notify1@mydomain.com, notify2@mydomain.com';

	// take the default and append a second address to it example:
	// $email = $email . ', notify2@mydomain.com';

	// return the result
	return $email;
}
