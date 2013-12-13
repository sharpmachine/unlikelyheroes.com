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

			<?php $args = array( 'post_type' => 'ignition_product','posts_per_page' => 15, 'paged' => $paged); ?>

			<?php $all_campaigns = new WP_Query( $args ); ?>
			<?php if ( $all_campaigns->have_posts() ) : ?>
				<?php while ( $all_campaigns->have_posts() ) : $all_campaigns->the_post(); ?>
					<?php get_template_part('campaign','listing'); ?>
				<?php endwhile; ?>

			<?php get_template_part('create','campaign'); ?>

			<?php wp_reset_postdata(); ?>

			<?php else:  ?>
				<?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
			<?php endif; ?>
		</div><!-- .row -->
	</div><!-- .section -->

		<?php echo bootstrap_pagination(); ?>

</div><!-- .container-->

<?php get_footer(); ?>
