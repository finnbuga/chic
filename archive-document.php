<?php get_header(); ?>

<section id="primary" class="site-content">
	<div id="content" role="main">

		<header class="entry-header">
			<h1 class="entry-title"><?php _e( 'Documents', 'otm' ); ?></h1>
		</header>

		<form class="documents-filter" action="/documents">
			<?php otm_taxonomies_select( 'document' ); ?>
			<input type="submit" value="Filter">
		</form>
		<?php get_search_form(); ?>

		<div class="documents-list">
			<?php
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();
					get_template_part( 'content', 'document' );
				}
			} else {
				_e('Nothing found', 'otm');
			}

			the_posts_pagination( array(
				'prev_text' => __( 'Previous Documents', 'otm' ),
				'next_text' => __( 'Next Documents', 'otm' ),
			) );
			?>
		</div>

	</div><!-- #content -->
</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
