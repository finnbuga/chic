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
			if ($taxonomy->name != 'event') {
				$term_names = otm_get_terms_names( get_the_ID(), $taxonomy->name );
				$term_ids = otm_get_terms_ids( get_the_ID(), $taxonomy->name );
				if ($term_names) {
				?>
					<span class="<?php print $taxonomy->name . ' ' . implode(' ', $term_ids); ?>"><?php print implode(', ', $term_names); ?></span>
				<?php
				}
			}
		}
		?>
	</footer>
</article>
