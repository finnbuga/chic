<?php
/*
 * Cleanup Backend
 */

/**
 * Add Manager and Member roles
 */
add_action( 'after_switch_theme', 'otm_documents_add_roles' );
function otm_documents_add_roles() {
	$editor_capabilities  = get_role( 'editor' )->capabilities;
	$manager_capabilities = array_merge(
		$editor_capabilities,
		array(
			'list_users'           => true,
			'create_users'         => true,
			'add_users'            => true,
			'edit_users'           => true,
			'delete_users'         => true,
			'remove_users'         => true,
			'promote_users'        => true,
			'manage_network_users' => true,
		)
	);
	//remove_role('manager'); // If role exists it will not be overridden
	add_role( 'manager', __( 'Manager' ), $manager_capabilities );

	$subscriber_capabilities = get_role( 'subscriber' )->capabilities;
	$member_capabilities     = array_merge(
		$subscriber_capabilities,
		array(
			'read_private_pages' => true
		)
	);
	//remove_role('member'); // If role exists it will not be overridden
	add_role( 'member', __( 'Member' ), $member_capabilities );
}

/**
 * Set default role
 */
add_action( 'after_switch_theme', 'otm_set_default_role' );
function otm_set_default_role( $query ) {

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
 * Set default hidden meta boxes
 *
 * Hide Features Image and Page Attributes
 */
add_filter( 'default_hidden_meta_boxes', 'otm_set_default_hidden_meta_boxes', 10, 2 );
function otm_set_default_hidden_meta_boxes( $hidden, $screen ) {
	return array_unique( array_merge( $hidden, array( 'postimagediv', 'pageparentdiv' ) ) );
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

/**
 * Cleanup the backend for non-admins
 */
add_action( 'init', 'cleanup_admin' );
function cleanup_admin() {
	if ( ! current_user_can( 'administrator' ) ) {
		add_action( 'admin_enqueue_scripts', 'otm_add_admin_css' );
		add_action( 'wp_enqueue_scripts', 'otm_add_admin_css' );
		add_filter( 'editable_roles', 'otm_cleanup_roles_list' );
		add_action( 'admin_bar_menu', 'otm_customise_toolbar', 100 );
	}
}

/**
 * Add admin stylesheet
 */
function otm_add_admin_css() {
	wp_enqueue_style( 'otm-admin', get_stylesheet_directory_uri() . '/admin.css' );
}

/**
 * Cleanup roles list
 */
function otm_cleanup_roles_list( $all_roles ) {
	unset( $all_roles['administrator'] );
	unset( $all_roles['editor'] );
	unset( $all_roles['author'] );
	unset( $all_roles['contributor'] );
	unset( $all_roles['subscriber'] );

	return $all_roles;
}

/**
 * Customise toolbar
 */
function otm_customise_toolbar( WP_Admin_Bar $admin_bar ) {
	global $pagenow, $typenow;

	// Add 'Home' link
	$admin_bar->add_node( array(
		'id'    => 'home',
		'title' => 'Home',
		'href'  => get_home_url(),
		'meta'  => array( 'class' => 'manager' ),
	) );

	// Add 'Users' link
	if ( current_user_can( 'list_users' ) ) {
		$admin_bar->add_node( array(
			'id'    => 'users',
			'title' => 'Users',
			'href'  => admin_url( 'users.php' ),
			'meta'  => array( 'class' => 'manager' ),
		) );
	}

	// Add 'Sliders' link
	if ( current_user_can( 'edit_others_posts' ) ) {
		$admin_bar->add_node( array(
			'id'    => 'sliders',
			'title' => 'Sliders',
			'href'  => admin_url( 'admin.php?page=metaslider' ),
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

	// Remove 'Edit' (term) link on the Documents archive page
	if ( is_post_type_archive( 'document' ) && 'edit.php' != $pagenow ) {
		$admin_bar->remove_node( 'edit' );
	}

	// Add 'Edit Documents' link on the Documents archive page
	if ( is_post_type_archive( 'document' ) && 'edit.php' != $pagenow && current_user_can( 'edit_others_posts' ) ) {
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
