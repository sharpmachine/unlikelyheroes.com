<?php 
/*
* Template Name: Project Homes
*/
get_header(); ?>

<div class="jumbotron jumbotron-project-homes">
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
	<div class="row main-content sixteen-vr">
		<div class="col-md-offset-1 col-md-10">
			<h2 class="text-center home-name"><?php the_field('home_name'); ?></h2>
			<?php get_template_part( 'loop', 'page' ); ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
