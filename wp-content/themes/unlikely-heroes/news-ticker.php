<div class="news-ticker">
	<div class="container">
		<div class="row">
			<div class="col-sm-6 col-md-6 hidden-sm hidden-xs">
				<span>Latest Update:</span>
				<?php $args = array( 'post_type' => 'lastest_updates', 'showposts' => 1); ?>
				<?php $latest_updates = new WP_Query( $args ); ?>

				<?php if ( $latest_updates->have_posts() ) : ?>

					<?php while ( $latest_updates->have_posts() ) : $latest_updates->the_post(); ?>
						<?php the_short_title(35); ?> <a href="<?php the_permalink(); ?>" class="read-more">Read More &#8594;</a>
					<?php endwhile; ?>

					<?php wp_reset_postdata(); ?>

				<?php else:  ?>
					<?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
				<?php endif; ?>
				<?php wp_reset_query(); ?>
			</div>
			<div class="col-sm-6 col-md-6 visible-sm">
				<span>Lastest Update:</span>
				<?php $args = array( 'post_type' => 'lastest_updates', 'showposts' => 1); ?>
				<?php $latest_updates = new WP_Query( $args ); ?>

				<?php if ( $latest_updates->have_posts() ) : ?>

					<?php while ( $latest_updates->have_posts() ) : $latest_updates->the_post(); ?>
						<?php the_short_title(15); ?> <a href="<?php the_permalink(); ?>" class="read-more">Read More &#8594;</a>
					<?php endwhile; ?>

					<?php wp_reset_postdata(); ?>

				<?php else:  ?>
					<?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
				<?php endif; ?>
				<?php wp_reset_query(); ?>
			</div>

			<div class="col-sm-6 col-md-5 text-right hidden-xs">
				<?php get_template_part('social','media'); ?>
				<a href="https://unlikelyheroes.webconnex.com/onetime" target="_blank" class="btn btn-xs">Donate Now</a>
			</div>

			<div class="col-xs-8 col-sm-6 col-md-5 text-left visible-xs">
				<?php get_template_part('social','media'); ?>
			</div>
			<div class="col-xs-4 visible-xs text-right">
				<a href="https://unlikelyheroes.webconnex.com/onetime" target="_blank" class="btn btn-xs">Donate Now</a>
			</div>
			
		</div>
	</div>
</div>