<li class="myprojects">
	<div class="project-item project-thumb"><div class="image" style="<?php echo (!empty($thumb) ? 'background-image: url('.$thumb.');' : ''); ?>"></div></div>
	<div class="project-item project-name"><?php echo $the_project->product_name; ?></div>
	<div class="project-item option-list">
		<a href="?edit_project=<?php echo $post_id; ?>"><?php _e('EDIT', 'memberdeck'); ?></a>
		<a href="<?php echo get_permalink($post_id); ?>"><?php _e('VIEW', 'memberdeck'); ?></a>
	</div>
	<div class="project-item project-status"><?php echo (strtoupper($post->post_status) == 'PUBLISH' ? __('PUBLISHED', 'memberdeck') : $post->post_status); ?></div>
</li>