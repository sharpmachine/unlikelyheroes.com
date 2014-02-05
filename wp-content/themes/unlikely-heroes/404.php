<?php get_header(); ?>

<div class="jumbotron">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h1>
					<?php _e( 'The page you requested could not be found.', 'smm' ); ?>
					<span>AHHHHHHHHHH!!</span>
				</h1>
			</div>
		</div>
	</div>
</div>

<div id="error-four0four">
	<div class="container">
		<div class="row">
			<div class="col-sm-10 col-sm-offset-1">
				<div id="post-0" class="post error404 not-found">
					<div class="entry-content text-center">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/home-alone.jpg" class="img-responsive" alt="Not Found">
					</div><!-- .entry-content -->
				</div><!-- #post-0 -->
			</div>
		</div>
	</div>
</div><!-- #page -->

<script type="text/javascript">
	// focus on search field after it has loaded
	document.getElementById('s') && document.getElementById('s').focus();
</script>

<?php get_footer(); ?>