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
 * Move the Event taxonomy meta box from the side column to the main column
 */
add_action( 'do_meta_boxes', 'otm_documents_change_event_meta_box_position' );
function otm_documents_change_event_meta_box_position( $post_type ) {
	$tax_name = 'event';

	if ( $post_type != 'document' ) {
		return;
	}

	remove_meta_box( $tax_name . 'div', $post_type, 'side' );

	$taxonomy = get_taxonomy( $tax_name );
	if ( ! $taxonomy->show_ui || false === $taxonomy->meta_box_cb ) {
		return;
	}

	add_meta_box( $tax_name . 'box', $taxonomy->labels->singular_name, $taxonomy->meta_box_cb, null, 'advanced', 'core',
		array( 'taxonomy' => $tax_name ) );
}

/**
 * Order Events taxonomy terms by term_id
 *
 * so that the last event added will be on the top of the list
 */
add_filter( 'get_terms_args', 'otm_documents_reorder_events_by_term_id', 10, 2 );
function otm_documents_reorder_events_by_term_id( $args, $taxonomies ) {
	if ( 'event' == $taxonomies[0] ) {
		$args['orderby'] = 'term_id';
		$args['order']   = 'DESC';
	}

	return $args;
}
