<article class="document <?php print implode( ' ', otm_get_all_terms_ids( get_the_ID() )); ?>">
	<header class="document-header">
		<h3 class="document-title">
			<?php if ( current_user_can( 'read_private_posts' ) ): ?>
				<a href="<?php print otm_document_get_attachment_url(); ?>"><?php the_title(); ?></a>
			<?php else: ?>
				<a onclick="alert('Members only.');"><?php the_title(); ?></a>
			<?php endif; ?>
		</h3>
	</header>

	<footer class="document-meta">
		<?php
		$taxonomies = get_object_taxonomies( 'document', 'objects' );
		foreach ($taxonomies as $taxonomy) {
			$term_names = otm_get_terms_names( get_the_ID(), $taxonomy->name );
			if ($term_names) {
				if ($taxonomy->name == 'event') {
					$event_date = substr($term_names[0], 0, 7);
					?>
					<span class="event-date"><?php print $event_date; ?></span>
					<?php
				} else {
					?>
					<span class="<?php print $taxonomy->name; ?>"><?php print implode(', ', $term_names); ?></span>
					<?php
				}
			}
		}
		?>
	</footer>
</article>
