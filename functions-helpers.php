<?php


/**
 * Displays a <select> element for a given taxonomy
 */
function otm_taxonomy_select( $taxonomy ) {
	$terms = get_terms( array( 'taxonomy' => $taxonomy->name, 'hide_empty' => true, ) );
	?>
	<select name="<?php print $taxonomy->name; ?>" class="filters-select ">
		<option value="">Filter by <?php print $taxonomy->name; ?></option>
		<?php foreach ($terms as $term): ?>
			<option value="<?php print $term->slug; ?>" <?php if (isset($_GET[$taxonomy->name]) && $_GET[$taxonomy->name] == $term->slug) print 'selected="selected"'; ?>><?php print $term->name; ?></option>
		<?php endforeach; ?>
	</select>
	<?php
}

/**
 * Displays <select> elements for all taxonomies of a given object
 */
function otm_taxonomies_select( $object ) {
	$taxonomies = get_object_taxonomies( $object, 'objects' );
	foreach ( $taxonomies as $taxonomy ) {
		$has_terms = !empty(get_terms( array( 'taxonomy' => $taxonomy->name, 'hide_empty' => true, 'number' => 1) ));
		if ( $has_terms ) {
			otm_taxonomy_select( $taxonomy );
		}
	}
}

/**
 * Get terms ids
 */
function otm_get_terms_ids( $post, $taxonomy ) {
	$terms = get_the_terms( $post, $taxonomy );
	if ( !$terms || is_wp_error( $terms ) ) {
		return array();
	}

	return array_map( function ( $term ) {
		return $term->term_id;
	}, $terms );
}

/**
 * Get terms names
 */
function otm_get_terms_names( $post, $taxonomy ) {
	$terms = get_the_terms( $post, $taxonomy );
	if ( !$terms || is_wp_error( $terms ) ) {
		return array();
	}

	return array_map( function ( $term ) {
		return $term->name;
	}, $terms );
}

/**
 * Get all terms ids
 */
function otm_get_all_terms_ids( $post ) {
	$ids = array();

	$taxonomies = get_object_taxonomies( get_post_type( $post ), 'objects' );
	foreach ($taxonomies as $taxonomy) {
		$ids = array_merge( $ids, otm_get_terms_ids( $post, $taxonomy->name ));
	}

	return $ids;
}

/**
 * Get user profile link
 */
function otm_get_user_profile_link( $class ) {
	$user_id      = get_current_user_id();
	$current_user = wp_get_current_user();

	if ( ! $user_id ) {
		return 'Visitor';
	}

	if ( current_user_can( 'read' ) ) {
		$profile_url = get_edit_profile_url( $user_id );
	} elseif ( is_multisite() ) {
		$profile_url = get_dashboard_url( $user_id, 'profile.php' );
	} else {
		$profile_url = false;
	}

	if ( ! $profile_url ) {
		return $current_user->display_name;
	} else {
		return '<a class="'. $class . '" href="' . $profile_url . '">' . $current_user->display_name . '</a>';
	}
}
