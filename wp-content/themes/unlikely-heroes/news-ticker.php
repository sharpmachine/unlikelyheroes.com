<div class="news-ticker">
	<div class="container">
		<div class="row">
			<div class="col-sm-6 col-md-6 hidden-sm hidden-xs">
				<span>Lastest Update:</span>
					<?php $args = array( 'post_type' => 'lastest_updates', 'posts_per_page' => 1); ?>
					<?php $latest_updates = new WP_Query( $args ); ?>

					<?php if ( $latest_updates->have_posts() ) : ?>

						<?php while ( $latest_updates->have_posts() ) : $latest_updates->the_post(); ?>
							<?php the_short_title(35); ?> <a href="<?php the_permalink(); ?>" class="read-more">Read More &#8594;</a>
						<?php endwhile; ?>

					<?php wp_reset_postdata(); ?>

					<?php else:  ?>
							<?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
					<?php endif; ?>
			</div>
			<div class="col-sm-6 col-md-6 visible-sm">
				<span>Lastest Update:</span>
					<?php $args = array( 'post_type' => 'lastest_updates', 'posts_per_page' => 1); ?>
					<?php $latest_updates = new WP_Query( $args ); ?>

					<?php if ( $latest_updates->have_posts() ) : ?>

						<?php while ( $latest_updates->have_posts() ) : $latest_updates->the_post(); ?>
							<?php the_short_title(15); ?> <a href="<?php the_permalink(); ?>" class="read-more">Read More &#8594;</a>
						<?php endwhile; ?>

					<?php wp_reset_postdata(); ?>

					<?php else:  ?>
							<?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
					<?php endif; ?>
			</div>

			<div class="col-sm-6 col-md-5 text-right">
				<?php get_template_part('social','media'); ?>
				<?php if (is_user_logged_in()): ?>
				<!-- <a href="<?php echo wp_logout_url( home_url() ); ?>" class="btn btn-xs">Logout</a> -->
				<a href="<?php bloginfo('url' ); ?>/dashboard/?creator_projects=1" class="btn btn-xs">My Campaign</a>
			<?php else: ?>
			<a href="<?php bloginfo('url'); ?>/dashboard" class="btn btn-xs">Hero Login</a>
		<?php endif; ?>
			</div>
		</div>
	</div>
</div>