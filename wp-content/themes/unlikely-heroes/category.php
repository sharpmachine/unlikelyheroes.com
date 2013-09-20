<?php get_header(); ?>

<div id="category-landing" class="page">
	<div class="container">
		<div class="row">
			<div class="col-lg-8">
				<h1 class="page-title">
					<?php printf( __( 'Category Archives: %s', 'smm' ), '<span>' . single_cat_title( '', false ) . '</span>' );?>
				</h1>
				
				<?php
					$category_description = category_description();
					if ( ! empty( $category_description ) )
						echo '<div class="archive-meta">' . $category_description . '</div>';
				
					get_template_part( 'loop', 'blog' ); ?>
			
			</div>
			<div class="col-lg-4">
				<?php get_sidebar(); ?>
			</div>
		</div>
	</div>

</div><!-- .page -->

<?php get_footer(); ?>