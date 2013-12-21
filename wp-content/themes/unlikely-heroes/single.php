<?php get_header(); ?>

<div class="jumbotron jumbotron-about">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h1>
					<span>Heroic Updates</span>
				</h1>
			</div>
		</div>
	</div>
</div>

	<div class="container">
		<div class="row main-content">
			<div class="col-lg-8">
				<?php get_template_part( 'loop', 'single' ); ?>
			</div>
			<div class="col-lg-4">
				<div class="well">
				<h3>Recent Heroic Updates:</h3>
				<?php $args = array( 'post_type' => 'lastest_updates'); ?>
						<?php $latest_updates = new WP_Query( $args ); ?>

						<?php if ( $latest_updates->have_posts() ) : ?>

						<?php while ( $latest_updates->have_posts() ) : $latest_updates->the_post(); ?>
						<?php the_date(); ?><br>
						 <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><br>
					<?php endwhile; ?>

					<?php wp_reset_postdata(); ?>

				<?php else:  ?>
				<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
			<?php endif; ?>
			</div>
			</div>
		</div>
	</div>


<?php get_footer(); ?>
