	<div class="news-ticker">
	<div class="container">
		<div class="row">
			<div class="col-sm-6 col-md-6">
				<span>Lastest Update:</span>
					<?php $args = array( 'post_type' => 'lastest_updates', 'posts_per_page' => 1); ?>
					<?php $latest_updates = new WP_Query( $args ); ?>

					<?php if ( $latest_updates->have_posts() ) : ?>

						<?php while ( $latest_updates->have_posts() ) : $latest_updates->the_post(); ?>
							<?php the_title(); ?> <a href="<?php the_permalink(); ?>" class="read-more">Read More &#8594;</a>
						<?php endwhile; ?>

					<?php wp_reset_postdata(); ?>

					<?php else:  ?>
							<?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
					<?php endif; ?>
			</div>
			<div class="col-sm-6 col-md-5 text-right">
				<?php get_template_part('social','media'); ?>
				<?php if (is_user_logged_in()): ?>
				<a href="<?php echo wp_logout_url( home_url() ); ?>" class="btn btn-xs">Logout</a>
			<?php else: ?>
			<a href="<?php bloginfo('url'); ?>/dashboard" class="btn btn-xs">Hero Login</a>
		<?php endif; ?>
			</div>
		</div>
	</div>
</div>