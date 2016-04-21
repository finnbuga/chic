<?php

/**
 * Check if current user is editor
 */
function otm_is_current_user_editor() {
	return current_user_can( 'editor' ) && ! current_user_can( 'administrator' ) ? true : false;
}

/**
 * Add admin stylesheet
 */
add_action( 'admin_enqueue_scripts', 'otm_admin_css' );
add_action( 'wp_enqueue_scripts', 'otm_admin_css' );
function otm_admin_css() {
	if ( current_user_can( 'administrator' ) ) {
		return;
	}
	wp_enqueue_style( 'otm-admin', get_stylesheet_directory_uri() . '/admin.css' );
}

/**
 * Edit toolbar items
 */
add_action( 'admin_bar_menu', 'otm_edit_toolbar', 100 );
function otm_edit_toolbar( WP_Admin_Bar $admin_bar ) {
	if ( current_user_can( 'administrator' ) ) {
		return;
	}

	// Add 'Home' link
	$admin_bar->add_menu( array(
		'id'    => 'home',
		'title' => 'Home',
		'href'  => get_home_url(),
		'meta'  => array( 'class' => 'editor' ),
	) );

	if (! otm_is_current_user_editor() ) {
		return;
	}

	// Add 'Users' link
	$admin_bar->add_menu( array(
		'id'    => 'users',
		'title' => 'Users',
		'href'  => admin_url( 'users.php' ),
		'meta'  => array( 'class' => 'editor' ),
	) );

	// Reorder 'Edit' link at the end
	if ( $edit = $admin_bar->get_node( 'edit' ) ) {
		$admin_bar->remove_node( 'edit' );
		$edit->meta = array( 'class' => 'editor' );
		$admin_bar->add_menu( $edit );
	}

	// Reorder 'View' link at the end
	if ( $view = $admin_bar->get_node( 'view' ) ) {
		$admin_bar->remove_node( 'view' );
		$view->meta = array( 'class' => 'editor' );
		$admin_bar->add_menu( $view );
	}

	// Add 'Edit Documents' link on the Documents archive page
	global $pagenow, $typenow;
	if ( is_post_type_archive('document') && 'edit.php' != $pagenow ) {
		$admin_bar->add_menu( array(
			'id'    => 'edit_documents',
			'title' => 'Edit Documents',
			'href'  => admin_url( 'edit.php?post_type=document' ),
			'meta'  => array( 'class' => 'editor' ),
		) );
	}

	// Add 'View Documents' link on the All Documents page and the Edit Document pages
	if( 'document' === $typenow && ('edit.php' === $pagenow || 'post.php' === $pagenow || 'post-new.php' === $pagenow )) {
		$admin_bar->add_menu( array(
			'id'    => 'view_documents',
			'title' => 'View Documents',
			'href'  => home_url( '/documents/' ),
			'meta'  => array( 'class' => 'editor' ),
		) );

		// Remove 'View Document' link on the Edit Document page
		if ( $view = $admin_bar->get_node( 'view' ) ) {
			$admin_bar->remove_node( 'view' );
		}
	}
}

/**
 * Set default hidden meta boxes
 */
add_filter( 'default_hidden_meta_boxes', 'otm_set_default_hidden_meta_boxes', 10, 2 );
function otm_set_default_hidden_meta_boxes( $hidden, $screen ) {
	if (! otm_is_current_user_editor() ) {
		return array();
	}

	$hide = array( 'tagsdiv-post_tag', 'tagsdiv', 'postimagediv', 'formatdiv', 'pageparentdiv', '');
	return array_unique(array_merge($hidden, $hide));
}

/**
 * Set default hidden columns
 */
add_filter( 'default_hidden_columns', 'otm_set_default_hidden_columns', 10, 2 );
function otm_set_default_hidden_columns( $hidden, $screen ) {
	if (! otm_is_current_user_editor() ) {
		return array();
	}

	$hide = array( 'author', 'tags', 'comments', 'date', 'posts' );
	return array_unique( array_merge( $hidden, $hide ) );
}

/**
 * Allow editors to manage network users
 */
add_action( 'admin_init', 'otm_allow_editors_to_manage_network_users');
function otm_allow_editors_to_manage_network_users() {
	$role = get_role( 'editor' );
	$role->add_cap( 'manage_network_users' );
}
