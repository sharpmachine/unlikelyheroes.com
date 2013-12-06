<header id="header">
	<div class="news-ticker">
		<div class="container">
			<div class="row">
				<div class="col-sm-6 col-md-9">
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
		<div class="col-sm-6 col-md-3 text-right">
		<a href="http://twitter.com/uheroes"><i class="fa fa-twitter"></i></a>
		<a href="http://www.facebook.com/pages/Unlikely-Heroes/127675453978638"><i class="fa fa-facebook"></i></a> 
		<a href="http://vimeo.com/unlikelyheroes"><i class="fa fa-instagram"></i></a>
		<a href="http://vimeo.com/unlikelyheroes"><i class="fa fa-youtube-play"></i></a>
	</div>
	</div>

</div>
</div>
<div class="navbar navbar-default">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php bloginfo('url') ?>"></a>
		</div>
	</div>
	<div class="collapse navbar-collapse">
		<ul class="nav navbar-nav">
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'nav', 'items_wrap' => '%3$s', 'walker' => new Bootstrap_Menu_Walker ) ); ?>
		</ul>
	</div><!--/.nav-collapse -->
</div>
</header>