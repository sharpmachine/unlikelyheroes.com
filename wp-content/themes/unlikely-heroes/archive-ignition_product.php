<?php get_header(); ?>

<div class="jumbotron">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h1>
					<span>Heroic Campaigns</span>
				</h1>
			</div>
		</div>
	</div>
</div>

<div class="container">

	<div class="section boxes-one">
		<div class="row">

			<div id="project-grid">
				<?php 
				if (is_archive('ignition_product')) {
					get_template_part('loop', 'project');
					get_template_part('create','campaign');
				}
				else {
					$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
					$query = new WP_Query(array('paged' => $paged, 'posts_per_page' =>9));
					// Start the loop
					if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
						get_template_part('entry');
						endwhile;
						endif; 
					wp_reset_postdata();
				}
				?>
			</div>
		</div><!-- .row -->
	</div><!-- .section -->

		<?php echo bootstrap_pagination(); ?>

</div><!-- .container-->

<?php get_footer(); ?>
