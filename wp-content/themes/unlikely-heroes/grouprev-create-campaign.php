<div class="col-sm-6 col-md-3 box-one create-campaign-cta">
	<div class="box-one-inner">
		<a href="http://www.grouprev.com/signup/unlikelyheroes" target="_blank">
			<div class="box-one-img box-one-action">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/township.jpg" alt="" class="img-responsive">
				<div class="plus"></div>
			</div>
			<h3>Create a campaign</h3>
			<p>Create your own campaign and share it with the world!  All the money you raise goes towards our mission of ending human trafficking.</p>
		</a>
	</div>
</div>

<?php if (is_home() || is_front_page()): ?>
<div class="clearfix"></div>
<div class="text-center">
	<a href="<?php bloginfo('url'); ?>/campaigns" class="btn btn-lg">All Campaigns</a>
</div>
<?php endif; ?>