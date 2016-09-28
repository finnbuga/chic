<?php
/*
 * Customise the OTM Documents plugin
 */

/**
 * Restrict search to documents
 */
add_filter( 'pre_get_posts', 'otm_restrict_search_to_documents' );
function otm_restrict_search_to_documents( $query ) {
	if ( $query->is_search && ! is_admin() ) {
		$query->set( 'post_type', array( 'document' ) );
	}

	return $query;
}

/**
 * Set default role
 */
add_action( 'after_switch_theme', 'otm_set_default_role' );
function otm_set_default_role( $query ) {
	update_option( 'default_role', 'member' );
}