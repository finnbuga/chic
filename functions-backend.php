<?php

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
	if ( current_user_can( 'manager' ) && ! current_user_can( 'administrator' ) ) {
		unset($all_roles['administrator']);
		unset($all_roles['editor']);
		unset($all_roles['contributor']);
		unset($all_roles['author']);
		unset($all_roles['subscriber']);

		$member = $all_roles['member'];
		unset($all_roles['member']);
		$all_roles['member'] = $member;
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
	$admin_bar->add_menu( array(
		'id'    => 'home',
		'title' => 'Home',
		'href'  => get_home_url(),
		'meta'  => array( 'class' => 'manager' ),
	) );

	if ( ! current_user_can( 'manager' ) ) {
		return;
	}

	// Add 'Users' link
	$admin_bar->add_menu( array(
		'id'    => 'users',
		'title' => 'Users',
		'href'  => admin_url( 'users.php' ),
		'meta'  => array( 'class' => 'manager' ),
	) );

	// Reorder 'Edit' link at the end
	if ( $edit = $admin_bar->get_node( 'edit' ) ) {
		$admin_bar->remove_node( 'edit' );
		$edit->meta = array( 'class' => 'manager' );
		$admin_bar->add_menu( $edit );
	}

	// Reorder 'View' link at the end
	if ( $view = $admin_bar->get_node( 'view' ) ) {
		$admin_bar->remove_node( 'view' );
		$view->meta = array( 'class' => 'manager' );
		$admin_bar->add_menu( $view );
	}

	// Remove 'Edit' link on the Documents archive page
	if ( is_post_type_archive('document') && 'edit.php' != $pagenow ) {
		$admin_bar->remove_node( 'edit' );
	}

	// Add 'Edit Documents' link on the Documents archive page
	if ( is_post_type_archive('document') && 'edit.php' != $pagenow ) {
		$admin_bar->add_menu( array(
			'id'    => 'edit_documents',
			'title' => 'Edit Documents',
			'href'  => admin_url( 'edit.php?post_type=document' ),
			'meta'  => array( 'class' => 'manager' ),
		) );
	}

	// Add 'View Documents' link on the All Documents page and the Edit Document pages
	if( 'document' === $typenow && ('edit.php' === $pagenow || 'post.php' === $pagenow || 'post-new.php' === $pagenow )) {
		$admin_bar->add_menu( array(
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
 */
add_filter( 'default_hidden_meta_boxes', 'otm_set_default_hidden_meta_boxes', 10, 2 );
function otm_set_default_hidden_meta_boxes( $hidden, $screen ) {
	if ( current_user_can( 'manager' ) && ! current_user_can( 'administrator' ) ) {
		$hidden = array_unique( array_merge( $hidden, array( 'tagsdiv-post_tag', 'tagsdiv', 'postimagediv', 'formatdiv', 'pageparentdiv', '') ) );
	}
	return $hidden;
}

/**
 * Set default hidden columns
 */
add_filter( 'default_hidden_columns', 'otm_set_default_hidden_columns', 10, 2 );
function otm_set_default_hidden_columns( $hidden, $screen ) {
	if ( current_user_can( 'manager' ) && ! current_user_can( 'administrator' ) ) {
		$hidden = array_unique( array_merge( $hidden, array( 'author', 'tags', 'comments', 'date', 'posts' ) ) );
	}
	return $hidden;
}

/**
 * Add PDF filter on the Media page
 */
add_filter( 'post_mime_types', 'otm_add_pdf_filter' );
function otm_add_pdf_filter( $post_mime_types ) {
	$post_mime_types['application/pdf'] = array( __( 'PDFs' ), __( 'Manage PDFs' ), _n_noop( 'PDF <span class="count">(%s)</span>', 'PDFs <span class="count">(%s)</span>' ) );
	return $post_mime_types;
}
