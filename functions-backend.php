<?php

/**
 * Delete unneeded roles
 */
add_action( 'after_switch_theme', 'lfr_delete_roles' );
function lfr_delete_roles() {
	remove_role( 'editor' );
	remove_role( 'author' );
	remove_role( 'contributor' );
	remove_role( 'subscriber' );
}

/**
 * Add admin stylesheet
 */
add_action( 'admin_enqueue_scripts', 'otm_admin_css' );
add_action( 'wp_enqueue_scripts', 'otm_admin_css' );
function otm_admin_css() {
	if ( ! current_user_can( 'administrator' ) ) {
		wp_enqueue_style( 'otm-admin', get_stylesheet_directory_uri() . '/admin.css' );
	}
}

/**
 * Cleanup roles list
 */
add_filter( 'editable_roles', 'otm_cleanup_roles_list' );
function otm_cleanup_roles_list( $all_roles ) {
	if ( ! current_user_can( 'administrator' ) ) {
		unset( $all_roles['administrator'] );
	}

	return $all_roles;
}

/**
 * Edit toolbar items
 */
add_action( 'admin_bar_menu', 'otm_edit_toolbar', 100 );
function otm_edit_toolbar( WP_Admin_Bar $admin_bar ) {
	global $pagenow, $typenow;

	if ( current_user_can( 'administrator' ) ) {
		return;
	}

	// Add 'Home' link
	$admin_bar->add_node( array(
		'id'    => 'home',
		'title' => 'Home',
		'href'  => get_home_url(),
		'meta'  => array( 'class' => 'manager' ),
	) );

	if ( ! current_user_can( 'manager' ) ) {
		return;
	}

	// Add 'Users' link
	$admin_bar->add_node( array(
		'id'    => 'users',
		'title' => 'Users',
		'href'  => admin_url( 'users.php' ),
		'meta'  => array( 'class' => 'manager' ),
	) );

	if ( is_active_widget( false, false, 'metaslider_widget' ) ) {
		// Add 'Image Slides' link
		$admin_bar->add_node( array(
			'id'    => 'imageslides',
			'title' => 'Image Slides',
			'href'  => admin_url( 'admin.php?page=metaslider&id=18817' ),
			'meta'  => array( 'class' => 'manager' ),
		) );

		// Add 'Text Slides' link
		$admin_bar->add_node( array(
			'id'    => 'textslides',
			'title' => 'Text Slides',
			'href'  => admin_url( 'admin.php?page=metaslider&id=18820' ),
			'meta'  => array( 'class' => 'manager' ),
		) );
	}

	// Reorder 'Edit' link at the end
	if ( $edit = $admin_bar->get_node( 'edit' ) ) {
		$admin_bar->remove_node( 'edit' );
		$edit->meta = array( 'class' => 'manager' );
		$admin_bar->add_node( $edit );
	}

	// Reorder 'View' link at the end
	if ( $view = $admin_bar->get_node( 'view' ) ) {
		$admin_bar->remove_node( 'view' );
		$view->meta = array( 'class' => 'manager' );
		$admin_bar->add_node( $view );
	}

	// Remove 'Edit' link on the Documents archive page
	if ( is_post_type_archive( 'document' ) && 'edit.php' != $pagenow ) {
		$admin_bar->remove_node( 'edit' );
	}

	// Add 'Edit Documents' link on the Documents archive page
	if ( is_post_type_archive( 'document' ) && 'edit.php' != $pagenow ) {
		$admin_bar->add_node( array(
			'id'    => 'edit_documents',
			'title' => 'Edit Documents',
			'href'  => admin_url( 'edit.php?post_type=document' ),
			'meta'  => array( 'class' => 'manager' ),
		) );
	}

	// Add 'View Documents' link on the All Documents page and the Edit Document pages
	if ( 'document' === $typenow && ( 'edit.php' === $pagenow || 'post.php' === $pagenow || 'post-new.php' === $pagenow ) ) {
		$admin_bar->add_node( array(
			'id'    => 'view_documents',
			'title' => 'View Documents',
			'href'  => home_url( '/documents/' ),
			'meta'  => array( 'class' => 'manager' ),
		) );

		// Remove 'View Document' link on the Edit Document page
		if ( $view = $admin_bar->get_node( 'view' ) ) {
			$admin_bar->remove_node( 'view' );
		}
	}
}

/**
 * Set default hidden meta boxes
 *
 * Hide Features Image and Page Attributes
 */
add_filter( 'default_hidden_meta_boxes', 'otm_set_default_hidden_meta_boxes', 10, 2 );
function otm_set_default_hidden_meta_boxes( $hidden, $screen ) {
	return array_unique( array_merge( $hidden, array( 'postimagediv', 'pageparentdiv' ) ) );
}

/**
 * Set default hidden columns
 *
 * Hide Date
 */
add_filter( 'default_hidden_columns', 'otm_set_default_hidden_columns', 10, 2 );
function otm_set_default_hidden_columns( $hidden, $screen ) {
	return array_unique( array_merge( $hidden, array( 'date' ) ) );
}

/**
 * Add PDF filter on the Media page
 */
add_filter( 'post_mime_types', 'otm_add_pdf_filter' );
function otm_add_pdf_filter( $post_mime_types ) {
	$post_mime_types['application/pdf'] = array(
		__( 'PDF' ),
		__( 'Manage PDFs' ),
		_n_noop( 'PDF <span class="count">(%s)</span>', 'PDFs <span class="count">(%s)</span>' )
	);

	return $post_mime_types;
}
