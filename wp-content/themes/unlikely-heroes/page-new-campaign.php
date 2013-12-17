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
			<form name="fes" id="fes" action="" method="POST" enctype="multipart/form-data"><ul><h3>Project Creation</h3><li class="form-row twothird left"><p><label for="">Project Title</label><input type="text" id="" name="project_name" class="required" value="" /></p></li><li class="form-row third"><p><label for="">Goal Amount</label><input type="number" id="" name="project_goal" class="required" value="" /></p></li><li class="form-row third left"><p><label for="">Start Date</label><input type="date" id="" name="project_start" class="required" value="" /></p></li><li class="form-row third left"><p><label for="">End Date</label><input type="date" id="" name="project_end" class="project_end" value="" /></p></li><li class="form-row third"><p><label for="">Anticipated Ship Date</label><input type="date" id="" name="project_ship_date" class="project_ship_date" value="" /></p></li><li class="form-row"><p><label for="">Project Fund Type</label><select id="" name="project_fund_type" class="project_fund_type" ><option value="capture" >Capture</option><option value="preauth" >Pre-Order</option></select></p></li><div class="form-row half"><h3>Campaign End Options</h3><li class="half radio"><input type="radio" id="closed" name="project_end_type" class="project_end_type" value="closed" checked="checked"/> <label for="closed">Close on End</label></li><li class="half radio"><input type="radio" id="open" name="project_end_type" class="project_end_type" value="open" /> <label for="open">Leave Open</label></li></div><br/><h3>Project Details</h3><li class="form-row"><p><label for="">Project Short Description</label><input type="text" id="" name="project_short_description" class="required" value="" /></p></li><li class="form-row"><p><label for="">Project Long Description</label><textarea id="" name="project_long_description" class="project_long_description" ></textarea></p></li><li class="form-row"><p><label for="">Project Video</label><textarea id="" name="project_video" class="project_video" ></textarea></p></li><li class="form-row half left"><p><label for="">Featured Image</label><input type="file" id="" name="project_hero" class="project_hero" value="" /></p></li><li class="form-row half"><p><label for="">Project Image 2</label><input type="file" id="" name="project_image2" class="project_image2" value="" /></p></li><li class="form-row half left"><p><label for="">Project Image 3</label><input type="file" id="" name="project_image3" class="project_image3" value="" /></p></li><li class="form-row half"><p><label for="">Project Image 4</label><input type="file" id="" name="project_image4" class="project_image4" value="" /></p></li><h3>Project Reward Levels</h3><li class="form-row half"><p><label for="">Number of Levels</label><input type="number" id="" name="project_levels" class="required" value="1" min="1"/></p></li><div class="form-level"><li class="form-row"><p><label for="">Level Title</label><input type="text" id="" name="project_level_title[]" class="project_level_title[]" value="" /></p></li><li class="form-row half left"><p><label for="">Level Price</label><input type="number" id="" name="project_level_price[]" class="project_level_price[]" value="" /></p></li><li class="form-row half"><p><label for="">Level Limit</label><input type="number" id="" name="project_level_limit[]" class="project_level_limit[]" value="" /></p></li><li class="form-row"><p><label for="">Level Description</label><input type="text" id="" name="level_description[]" class="level_description[]" value="" /></p></li><li class="form-row"><p><label for="">Level Long Description</label><textarea id="" name="level_long_description[]" class="level_long_description[]" ></textarea></p></li></div><li class="form-row"><p><input type="submit" id="" name="project_fesubmit" class="project_fesubmit" value="Submit"/></li></ul></form>
		</div>
	</div>
</div>

<?php get_footer(); ?>
