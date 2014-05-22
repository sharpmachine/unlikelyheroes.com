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

			<?php $args = array( 'post_type' => 'campaigns', 'showposts' => 3); ?>
				<?php $campaigns = new WP_Query( $args ); ?>

				<?php if ( $campaigns->have_posts() ) : ?>

					<?php while ( $campaigns->have_posts() ) : $campaigns->the_post(); ?>

						<?php get_template_part('grouprev', 'listing'); ?>

					<?php endwhile; ?>

					<?php wp_reset_postdata(); ?>

				<?php else:  ?>
					<?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
				<?php endif; ?>
				<?php wp_reset_query(); ?>

				<?php get_template_part('grouprev-create','campaign'); ?>
		</div><!-- .row -->
	</div><!-- .section -->

		<?php echo bootstrap_pagination(); ?>

</div><!-- .container-->

<?php get_footer(); ?>
