<article class="document <?php print implode( ' ', otm_get_all_terms_ids( get_the_ID() )); ?>">
	<header class="document-header">
		<h1 class="document-title">
			<?php if ( current_user_can( 'read_private_posts' ) ): ?>
				<a href="<?php print otm_document_get_attachment_url(); ?>"><?php the_title(); ?></a>
			<?php else: ?>
				<a onclick="alert('Members only.');"><?php the_title(); ?></a>
			<?php endif; ?>
		</h1>
	</header>

	<footer class="document-meta">
		<?php
		$taxonomies = get_object_taxonomies( 'document', 'objects' );
		foreach ($taxonomies as $taxonomy) {
			if ($taxonomy->name != 'event') {
				?>
				<span class="<?php print $taxonomy->name . ' ' . implode(' ', otm_get_terms_ids( get_the_ID(), $taxonomy->name )); ?>"><?php print implode(', ', otm_get_terms_names( get_the_ID(), $taxonomy->name ));; ?></span>
				<?php
			}
		}
		?>
	</footer>
</article>
