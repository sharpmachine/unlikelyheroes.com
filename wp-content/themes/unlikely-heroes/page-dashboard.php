<?php get_header(); ?>
<div class="jumbotron">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h1>
					<span><?php the_title(); ?></span>
				</h1>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="col-lg-12 main-content">
			<?php get_template_part( 'loop', 'page' ); ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
