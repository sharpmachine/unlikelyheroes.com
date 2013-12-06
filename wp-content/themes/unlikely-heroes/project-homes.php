<?php 
/*
* Template Name: Project Homes
*/
get_header(); ?>

<div class="jumbotron jumbotron-project-homes">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h1><?php the_title(); ?>
					<span><?php the_field('home_name'); ?></span>
				</h1>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="row main-content sixteen-vr">
		<div class="col-md-offset-1 col-md-10">
			<?php the_content(); ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
