<?php
add_filter('ign_cmb_meta_boxes', 'ign_meta_boxes');
// Include & setup custom metabox and fields

function ign_meta_boxes(array $meta_boxes) {
	require 'languages/text_variables.php';
	$prefix = 'ign_';
	$meta_boxes[] = array(
	    'id' => 'product_meta',
	    'title' => $tr_Project,
	    'pages' => array('ignition_product'), // post type
		'context' => 'normal',
		'priority' => 'high',
		'class' => $prefix . 'projectmeta',
	    'fields' => array(
			array(
				'name' => $tr_Project_Type,
				'desc' => $tr_Project_Type_Desc,
				'id' => $prefix.'project_type',
				'class' => $prefix . 'projectmeta_left',
				'show_help' => true,
				'options' => array(
					array(
						'name' => $tr_Pwyw,
						'id' => 'pwyw',
						'value' => 'pwyw'
					),
					array(
						'name' => $tr_Level_Based,
						'id' => 'level-based',
						'value' => 'level-based'
					)
				),
				'type' => 'radio'
			),
			array(
				'name' => $tr_End_Type,
				'desc' => $tr_End_Type_Desc,
				'id' => $prefix.'end_type',
				'class' => $prefix . 'projectmeta_right',
				'show_help' => true,
				'options' => array(
					array(
						'name' => $tr_Open,
						'id' => 'open',
						'value' => 'open'
					),
					array(
						'name' => $tr_Closed,
						'id' => 'closed',
						'value' => 'closed'
					)
				),
				'type' => 'radio'
			),
			array(
		        'name' => $tr_Product_Name,
		        'desc' => $tr_meta_name_desc,
		        'id' => $prefix . 'product_name',
		        'class' => $prefix . 'projectmeta_left',
		        'show_help' => true,
		        'type' => 'text'
		    ),
		    array(
		        'name' => $tr_meta_end_date,
		        'desc' => $tr_meta_end_det,
		        'id' => $prefix . 'fund_end',
		        'class' => $prefix . 'projectmeta_right',
		        'show_help' => true,
		        'type' => 'text_date'
		    ),
		    array(
		        'name' => $tr_Funding_Goal,
		        'desc' => $tr_meta_goal_det,
		        'id' => $prefix . 'fund_goal',
		        'class' => $prefix . 'projectmeta_left',
		        'show_help' => true,
		        'type' => 'text_money'
		    ),
		    array(
		        'name' => $tr_meta_ship_date,
		        'desc' => $tr_meta_ship_det,
		        'id' => $prefix . 'proposed_ship_date',
		        'class' => $prefix . 'projectmeta_right',
		        'show_help' => true,
		        'type' => 'text_date'
		    ),
			array(
		        'name' => $tr_meta_project_description,
		        'desc' => $tr_meta_project_description_det,
		        'id' => $prefix . 'project_description',
		        'class' => $prefix . 'projectmeta_full',
		        'show_help' => true,
		        'type' => 'textarea_medium'
		    ),
			array(
		        'name' => $tr_meta_project_long_description,
		        'desc' => $tr_meta_project_long_description_det,
		        'id' => $prefix . 'project_long_description',
		        'class' => $prefix . 'projectmeta_full',
		        'show_help' => true,
		        'type' => 'textarea_medium'
		    ),
		    array(
		        'name' => $tr_meta_video_name,
		        'desc' => $tr_meta_video_det,
		        'id' => $prefix . 'product_video',
		        'class' => $prefix . 'projectmeta_full',
		        'show_help' => true,
		        'type' => 'textarea_small'
		    ),
		    array(
		        'name' => $tr_meta_first_image_name,
		        'desc' => $tr_meta_first_image_det,
		        'id' => $prefix . 'product_image1',
		        'class' => $prefix . 'projectmeta_left',
		        'show_help' => true,
		        'type' => 'file'
		    ),
		    array(
		        'type' => 'headline1',
		        'class' => $prefix . 'projectmeta_headline1'
		    ),
		    array(
		        'type' => 'level1wraptop',
		        'class' => 'projectmeta_none'
		    ),
		    array(
		        'name' => $tr_Product_Title,
		        'desc' => $tr_meta_title_det,
		        'id' => $prefix . 'product_title',
		        'class' => $prefix . 'projectmeta_reward_title',
		        'show_help' => false,
		        'type' => 'text'
		    ),
			array(
		        'name' => $tr_meta_price_det,
		        'desc' => $tr_meta_price_desc,
		        'id' => $prefix . 'product_price',
		        'class' => $prefix . 'projectmeta_reward_price',
		        'show_help' => false,
		        'type' => 'text_money'
		    ),
		    array(
		        'name' => $tr_meta_level_short_det,
		        'desc' => $tr_meta_level_short_det,
		        'id' => $prefix . 'product_short_description',
		        'class' => $prefix . 'projectmeta_reward_desc',
		        'show_help' => false,
		        'type' => 'textarea_small'
		    ),
		    array(
		        'name' => $tr_meta_level_det,
		        'desc' => $tr_meta_level_det,
		        'id' => $prefix . 'product_details',
		        'class' => $prefix . 'projectmeta_reward_desc',
		        'show_help' => false,
		        'type' => 'textarea_small'
		    ),
		    array(
		        'name' => $tr_Product_Limit,
		        'desc' => $tr_meta_limit_det,
		        'id' => $prefix . 'product_limit',
		        'class' => $prefix . 'projectmeta_reward_limit',
		        'show_help' => false,
		        'type' => 'text_small'
		    ),
		    array(
		    	'name' => $tr_Level_Order,
		    	'desc' => $tr_level_order_desc,
		    	'id' => $prefix.'projectmeta_level_order',
		    	'class' => $prefix . 'projectmeta_reward_limit',
		    	'show_help' => false,
		    	'type' => 'text_small'
		    ),
			array(
			    'type' => 'level1wrapbottom',
			    'class' => 'projectmeta_none'
			),
		    
		   
			array(
	            'name' => $tr_meta_next_level_name,
	            'desc' => $tr_meta_next_level_det,
				'std' => '',
	            'id' => $prefix . 'level',
	            'class' => $prefix . 'projectmeta_full new_levels',
	            'show_help' => true,
	            'type' => 'product_levels'
	        ),	
	        array(
	            'name' => $tr_Add_Level,
	            'desc' => $tr_Level_Description,
	            'id' => $prefix . 'addlevels',
	            'class' => $prefix . 'projectmeta_full new_level',
	            'type' => 'add_levels',
	        ),
	        array(
	            'type' => 'headline2',
	            'class' => $prefix . 'projectmeta_headline2'
	        ),
			array(
		        'name' => $tr_meta_second_image_name,
		        'desc' => $tr_meta_second_image_det,
		        'id' => $prefix . 'product_image2',
		        'class' => $prefix . 'projectmeta_left',
		        'show_help' => true,
		        'type' => 'file'
		    ),
			array(
		        'name' => $tr_meta_third_image_name,
		        'desc' => $tr_meta_third_image_det,
		        'id' => $prefix . 'product_image3',
		        'class' => $prefix . 'projectmeta_left',
		        'show_help' => true,
		        'type' => 'file'
		    ),
			array(
		        'name' => $tr_meta_fourth_image_name,
		        'desc' => $tr_meta_fourth_image_det,
		        'id' => $prefix . 'product_image4',
		        'class' => $prefix . 'projectmeta_left',
		        'show_help' => true,
		        'type' => 'file'
		    ),
			array(
	            'name' => $tr_meta_faq_name,
	            'desc' => $tr_meta_faq_det,
	            'id' => $prefix . 'faqs',
	            'class' => $prefix . 'projectmeta_full',
	            'show_help' => true,
	            'type' => 'textarea_medium'
	        ),
			array(
	            'name' => $tr_meta_update_name,
	            'desc' => $tr_meta_update_det,
	            'id' => $prefix . 'updates',
	            'class' => $prefix . 'projectmeta_full',
	            'show_help' => true,
	            'type' => 'textarea_medium'
	        ),
			array(
	            'name' => $tr_meta_disc_name,
	            'desc' => $tr_meta_disc_det,
				'std' => $tr_meta_disc_std,
	            'id' => $prefix . 'disclaimer',
	            'class' => $prefix . 'projectmeta_full',
	            'show_help' => true,
	            'type' => 'textarea_medium'
	        ),
			/*array(
	            'name' => 'Product Goals',
	            'desc' => 'A brief description about what you are trying to achieve with this new product / service',
	            'id' => $prefix . 'prod_goals',
	            'type' => 'textarea_small'
	        ),
	        array(
	            'name' => 'Funding Description',
	            'desc' => 'A walkthrough of WHY you need funding. Paying bills? Developers?',
	            'id' => $prefix . 'fund_desc',
	            'type' => 'textarea_small'
	        ),*/
	    )
	);
	return apply_filters('id_postmeta_boxes', $meta_boxes);
}
require_once('ign_metabox/init.php');
?>