<?php get_header(); ?>

<section id="primary" class="site-content">
	<div id="content" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="entry-header">
				<h1 class="entry-title"><?php _e( 'Documents', 'otm' ); ?></h1>
			</header>

			<form class="documents-filter">
				<?php otm_taxonomies_select( 'document' ); ?>
				<input type="submit" value="Filter"></input>
			</form>

			<div class="documents-list">
				<?php
				while ( have_posts() ) : 
					the_post();
					get_template_part( 'content', 'document' );
				endwhile;
				?>
			</div>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

	</div><!-- #content -->
</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
