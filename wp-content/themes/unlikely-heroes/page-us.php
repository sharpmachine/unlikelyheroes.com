<?php get_header(); ?>

<div class="jumbotron jumbotron-about">
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
	<div class="row main-content">
		<h2 class="text-center">Our Team</h2>
		<div class="col-md-offset-1 col-md-10">
			

			<?php if(get_field('team_member')): ?>
				<?php while(has_sub_field('team_member')): ?>

					<div class="row team-member">
						<div class="col-sm-4">
							<img src="<?php the_sub_field('team_member_headshot'); ?>" class="img-responsive img-circle" alt="Headshot">
						</div>
						<div class="col-sm-8">
							<h3 class="team-member-name"><?php the_sub_field('team_member_name'); ?></h3>
							<div class="team-member-position"><?php the_sub_field('team_member_position'); ?></div>
							<?php the_sub_field('team_member_bio'); ?>
						</div>
					</div>
					<hr>
				<?php endwhile; ?>
			<?php endif; ?>

		</div>
	</div>
</div>

<?php get_footer(); ?>
