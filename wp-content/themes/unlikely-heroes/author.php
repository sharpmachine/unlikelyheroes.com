<?php get_header(); ?>

<div id="author-landing" class="page">
	<div class="container">
		<div class="row">
			<div class="col-lg-8">
				<?php
					if ( have_posts() )
						the_post();
				?>

					<h1 class="page-title">
						<?php printf( __( 'Author Archives: %s', 'smm' ), "<span class='vcard'><a class='url fn n' href='" . get_author_posts_url( get_the_author_meta( 'ID' ) ) . "' title='" . esc_attr( get_the_author() ) . "' rel='me'>" . get_the_author() . "</a></span>" ); ?>
					</h1>

				<?php get_template_part( 'author-info' ); ?>
					<?php rewind_posts(); ?>
				<?php get_template_part( 'loop', 'blog' ); ?>
				
			</div>
			<div class="col-lg-4">
				<?php get_sidebar(); ?>
			</div>
		</div>
	</div>
</div><!-- .page -->

<?php get_footer(); ?>
