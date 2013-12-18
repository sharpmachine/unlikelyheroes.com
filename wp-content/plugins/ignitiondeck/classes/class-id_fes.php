<?php
/*
Things we will need
user info (before or after reg?)
*/

/*
Meta Keys:

FAQ
Update
T&C

*/
class ID_FES {

	var $form;
	var $vars;

	function __construct($form=null, $vars = null) {
		if (empty($form)) {
			if (empty($vars['status']) || strtoupper($vars['status']) !== 'PUBLISH') {
				$this->form = array(
					array(
						'label' => __('Campaign Title', 'ignitiondeck'),
						'value' => (isset($vars['project_name']) ? $vars['project_name'] : ''),
						'name' => 'project_name',
						'type' => 'text',
						'class' => 'form-control required',
						'wclass' => 'col-md-12',
						'before' => '<h3 class="text-center">Create a Campaign</h3><div class="row">',
						'after' => '</div>'
						),
					array(
						'label' => __('Goal Amount', 'ignitiondeck'),
						'value' => (isset($vars['project_goal']) ? $vars['project_goal'] : ''),
						'name' => 'project_goal',
						'type' => 'text',
						'class' => 'form-control required',
						'wclass' => 'col-md-4',
						'before' => '<div class="row">',
						),
					array(
						'label' => __('Start Date', 'ignitiondeck'),
						'value' => (isset($vars['project_start']) ? $vars['project_start'] : ''),
						'name' => 'project_start',
						'type' => 'text',
						'class' => 'form-control required date',
						'wclass' => 'col-md-4'
						),
					array(
						'label' => __('End Date', 'ignitiondeck'),
						'value' => (isset($vars['project_end']) ? $vars['project_end'] : ''),
						'name' => 'project_end',
						'type' => 'text',
						'class' => 'form-control required date',
						'wclass' => 'col-md-4',
						'after' => '</div>'
						),
					array(
						'label' => __('Fund Type', 'ignitiondeck'),
						'value' => (isset($vars['project_fund_type']) ? $vars['project_fund_type'] : ''),
						'name' => 'project_fund_type',
						'class' => 'form-control',
						'type' => 'select',
						'before' => '<div class="row">',
						'after' => '<span class="help-block">If you choose Pledge, only the first level will be used. If you choose Level Based, you can create as many levels as you need.</span></div>',
						'wclass' => 'col-md-12',
						'options' => array(array('value' => 'capture', 'title' => 'Level Based'), array('value' => 'preauth', 'title' => 'Pledge'))
						),
					/*array(
						'before' => '<div class="form-group half"><h3>Project Type</h3>',
						'label' => __('Level Based', 'ignitiondeck'),
						'name' => 'project_type',
						'id'	=> 'level-based',
						'type' => 'radio',
						'value' => 'level-based',
						'wclass' => 'half radio',
						'misc' => 'checked="checked"'
						),
					array(
						'label' => __('Pledge What You Want', 'ignitiondeck'),
						'name' => 'project_type',
						'id'	=> 'pwyw',
						'type' => 'radio',
						'value' => 'pwyw',
						'wclass' => 'half radio',
						'after' => '</div>'
						),*/
					array(
						'label' => __('Close after end date', 'ignitiondeck'),
						'name' => 'project_end_type',
						'id'	=> 'closed',
						'before' => '<div class="row project-close-when"><div class="col-md-6">',
						'after' => '</div>',
						'type' => 'radio',
						'value' => 'closed',
						'wclass' => 'radio',
						'misc' => ((isset($vars['project_end_type']) && $vars['project_end_type'] == 'closed') || !isset($vars['project_end_type']) ? 'checked="checked"' : '')
						),
					array(
						'label' => __('Leave open after end date', 'ignitiondeck'),
						'name' => 'project_end_type',
						'id' => 'open',
						'type' => 'radio',
						'before' => '<div class="col-md-6">',
						'value' => 'open',
						'wclass' => 'radio',
						'misc' => (isset($vars['project_end_type']) && $vars['project_end_type'] == 'open' ? 'checked="checked"' : ''),
						'after' => '</div></div></div><div class="clearfix"></div>'
						),
					array(
						'before' => '<div class="col-md-12"><div class="row">',
						'label' => __('Short Description', 'ignitiondeck'),
						'value' => (isset($vars['project_short_description']) ? $vars['project_short_description'] : ''),
						'name' => 'project_short_description',
						'type' => 'text',
						'class' => 'form-control required',
						'wclass' => 'col-md-12',
						'after' => '</div>'
						),
					array(
						'label' => __('Long Description', 'ignitiondeck'),
						'value' => (isset($vars['project_long_description']) ? $vars['project_long_description'] : ''),
						'name' => 'project_long_description',
						'type' => 'textarea',
						'class' => 'form-control',
						'wclass' => 'col-md-12',
						'before' => '<div class="row">',
						'after' => '</div>'
						),
					array(
						'label' => __('Video (optional)', 'ignitiondeck'),
						'value' => (isset($vars['project_video']) ? $vars['project_video'] : ''),
						'name' => 'project_video',
						'type' => 'textarea',
						'class' => 'form-control',
						'wclass' => 'col-md-12',
						'before' => '<div class="row">',
						'after' => '<span class="help-block">Copy the embed code for your video from YouTube or Vimeo and paste it here.</span></div>'
						),
					array(
						'label' => __('Featured Image', 'ignitiondeck'),
						'value' => (isset($vars['project_hero']) ? $vars['project_hero'] : ''),
						'misc' => (isset($vars['project_hero']) ? 'data-url="'.$vars['project_hero'].'"' : ''),
						'name' => 'project_hero',
						'type' => 'file',
						'class' => 'form-control',
						'wclass' => 'col-md-12',
						'before' => '<div class="row">',
						'after' => '</div>'
						),
					array(
						'before' => '<h4 class="text-center">Rewards</h4>',
						'label' => __('Number of Rewards', 'ignitiondeck'),
						'value' => (isset($vars['project_levels']) ? $vars['project_levels'] : '1'),
						'name' => 'project_levels',
						'type' => 'number',
						'wclass' => 'form-group half',
						'class' => 'form-control required',
						'misc' => 'min="1"'
						)
					);
					if (empty($vars['project_levels']) || $vars['project_levels'] == 1) {
						$this->form[] = array(
							'before' => '<div class="form-level">',
							'label' => __('Reward Title', 'ignitiondeck'),
							'value' => (isset($vars['levels'][0]['title']) ? $vars['levels'][0]['title'] : ''),
							'name' => 'project_level_title[]',
							'type' => 'text',
							'class' => 'form-control',
							'wclass' => 'col-md-12',
							'before' => '<div class="row">',
							'after' => '</div>'
							);
						$this->form[] =array(
							'label' => __('Price', 'ignitiondeck'),
							'value' => (isset($vars['levels'][0]['price']) ? $vars['levels'][0]['price'] : ''),
							'name' => 'project_level_price[]',
							'type' => 'number',
							'class' => 'form-control',
							'wclass' => 'col-md-6',
							'before' => '<div class="row">'
							);
						$this->form[] =array(
							'label' => __('Limit', 'ignitiondeck'),
							'value' => (isset($vars['levels'][0]['limit']) ? $vars['levels'][0]['limit'] : ''),
							'name' => 'project_level_limit[]',
							'type' => 'number',
							'class' => 'form-control',
							'wclass' => 'col-md-6',
							'after' => '<span class="help-block text-right">How many of this reward will be available?  If unlimited, leave blank</span></div>'
							);
						$this->form[] =array(
							'label' => __('Description', 'ignitiondeck'),
							'value' => (isset($vars['levels'][0]['long']) ? $vars['levels'][0]['long'] : ''),
							'name' => 'level_long_description[]',
							'type' => 'textarea',
							'class' => 'form-control',
							'wclass' => 'col-md-12',
							'before' => '<div class="row">',
							'after' => '</div></div>'
							);
					}
					else if (isset($vars['project_levels']) && $vars['project_levels'] > 1) {
						for ($i = 0; $i <= $vars['project_levels'] - 1; $i++) {
							$this->form[] = array(
							'label' => __('Level Title', 'ignitiondeck'),
							'value' => (isset($vars['levels'][$i]['title']) ? $vars['levels'][$i]['title'] : ''),
							'name' => 'project_level_title[]',
							'type' => 'text',
							'class' => 'form-control',
							'wclass' => 'col-md-12',
							'before' => '<div class="row">',
							'after' => '</div>'
							);
						$this->form[] = array(
							'label' => __('Level Price', 'ignitiondeck'),
							'value' => (isset($vars['levels'][$i]['price']) ? $vars['levels'][$i]['price'] : ''),
							'name' => 'project_level_price[]',
							'type' => 'number',
							'class' => 'form-control',
							'wclass' => 'col-md-6',
							'before' => '<div class="row">',
							);
						$this->form[] = array(
							'label' => __('Level Limit', 'ignitiondeck'),
							'value' => (isset($vars['levels'][$i]['limit']) ? $vars['levels'][$i]['limit'] : ''),
							'name' => 'project_level_limit[]',
							'type' => 'number',
							'class' => 'form-control',
							'wclass' => 'col-md-6',
							'after' => '</div>'
							);
						$this->form[] = array(
							'label' => __('Level Long Description', 'ignitiondeck'),
							'value' => (isset($vars['levels'][$i]['long']) ? $vars['levels'][$i]['long'] : ''),
							'name' => 'level_long_description[]',
							'type' => 'textarea',
							'class' => 'form-control',
						'wclass' => 'col-md-12',
						'before' => '<div class="row">',
						'after' => '</div>'
							);
						}
					}
			}
			else {
				$this->form = array(
					array(
						'before' => '<h3>Team Information</h3>',
						'label' => __('Company Name', 'ignitiondeck'),
						'value' => (isset($vars['company_name']) ? $vars['company_name'] : ''),
						'name' => 'company_name',
						'type' => 'text',
						'class' => 'form-control required',
						'wclass' => 'form-group half left'
					),
					array(
						'label' => __('Company Logo', 'ignitiondeck'),
						'value' => (isset($vars['company_logo']) ? $vars['company_logo'] : ''),
						'misc' => (isset($vars['company_logo']) ? 'data-url="'.$vars['company_logo'].'"' : ''),
						'name' => 'company_logo',
						'type' => 'file',
						'wclass' => 'form-group half'
					),
					array(
						'label' => __('Company Location', 'ignitiondeck'),
						'value' => (isset($vars['company_location']) ? $vars['company_location'] : ''),
						'name' => 'company_location',
						'type' => 'text',
						'class' => 'form-control required',
						'wclass' => 'form-group half left'
					),
					array(
						'label' => __('Company URL', 'ignitiondeck'),
						'value' => (isset($vars['company_url']) ? $vars['company_url'] : ''),
						'name' => 'company_url',
						'type' => 'text',
						'class' => 'form-control required',
						'wclass' => 'form-group half'
					),
					array(
						'label' => __('Company Facebook', 'ignitiondeck'),
						'value' => (isset($vars['company_fb']) ? $vars['company_fb'] : ''),
						'name' => 'company_fb',
						'type' => 'text',
						'class' => 'form-control required',
						'wclass' => 'form-group half left'
					),
					array(
						'label' => __('Company Twitter', 'ignitiondeck'),
						'value' => (isset($vars['company_twitter']) ? $vars['company_twitter'] : ''),
						'name' => 'company_twitter',
						'type' => 'text',
						'class' => 'form-control required',
						'wclass' => 'form-group half'
					),
					array(
						'before' => '<h3>Project Details</h3>',
						'label' => __('Project Short Description', 'ignitiondeck'),
						'value' => (isset($vars['project_short_description']) ? $vars['project_short_description'] : ''),
						'name' => 'project_short_description',
						'type' => 'text',
						'class' => 'form-control required',
						'wclass' => 'form-group'
						),
					array(
						'label' => __('Project Long Description', 'ignitiondeck'),
						'value' => (isset($vars['project_long_description']) ? $vars['project_long_description'] : ''),
						'name' => 'project_long_description',
						'type' => 'textarea',
						'wclass' => 'form-group'
						),
					array(
						'label' => __('Project FAQ', 'ignitiondeck'),
						'value' => (isset($vars['project_faq']) ? $vars['project_faq'] : ''),
						'name' => 'project_faq',
						'type' => 'textarea',
						'wclass' => 'form-group'
						),
					array(
						'label' => __('Project Updates', 'ignitiondeck'),
						'value' => (isset($vars['project_updates']) ? $vars['project_updates'] : ''),
						'name' => 'project_updates',
						'type' => 'textarea',
						'wclass' => 'form-group'
						),
					array(
						'label' => __('Project Video', 'ignitiondeck'),
						'value' => (isset($vars['project_video']) ? $vars['project_video'] : ''),
						'name' => 'project_video',
						'type' => 'textarea',
						'wclass' => 'form-group'
						),
					array(
						'label' => __('Featured Image', 'ignitiondeck'),
						'value' => (isset($vars['project_hero']) ? $vars['project_hero'] : ''),
						'misc' => (isset($vars['project_hero']) ? 'data-url="'.$vars['project_hero'].'"' : ''),
						'name' => 'project_hero',
						'type' => 'file',
						'wclass' => 'form-group half left'
						),
					array(
						'label' => __('Project Image 2', 'ignitiondeck'),
						'value' => (isset($vars['project_image2']) ? $vars['project_image2'] : ''),
						'misc' => (isset($vars['project_image2']) ? 'data-url="'.$vars['project_image2'].'"' : ''),
						'name' => 'project_image2',
						'type' => 'file',
						'wclass' => 'form-group half'
						),
					array(
						'label' => __('Project Image 3', 'ignitiondeck'),
						'value' => (isset($vars['project_image3']) ? $vars['project_image3'] : ''),
						'misc' => (isset($vars['project_image3']) ? 'data-url="'.$vars['project_image3'].'"' : ''),
						'name' => 'project_image3',
						'type' => 'file',
						'wclass' => 'form-group half left'
						),
					array(
						'label' => __('Project Image 4', 'ignitiondeck'),
						'value' => (isset($vars['project_image4']) ? $vars['project_image4'] : ''),
						'misc' => (isset($vars['project_image4']) ? 'data-url="'.$vars['project_image4'].'"' : ''),
						'name' => 'project_image4',
						'type' => 'file',
						'wclass' => 'form-group half'
						)
				);
			}
			$this->form[] = array(
					'value' => __('Submit', 'ignitiondeck'),
					'name' => 'project_fesubmit',
					'type' => 'submit',
					'class' => 'btn',
					'wclass' => 'text-center'
					);
			if (isset($vars['post_id']) && $vars['post_id'] > 0) {
				$this->form[] = array(
					'value' => $vars['post_id'],
					'name' => 'project_post_id',
					'type' => 'hidden');
			}
		}
		else {
			$this->form = $form;
		}
	}

	function display_form() {
		$id_form = new ID_Form($this->form);
		$output = $id_form->build_form();
		return $output;
	}
}
?>