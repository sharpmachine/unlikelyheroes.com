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
			$this->form = array(
					array(
						'before' => '<h3>Project Creation</h3>',
						'label' => __('Project Title', 'ignitiondeck'),
						'value' => (isset($vars['project_name']) ? $vars['project_name'] : ''),
						'name' => 'project_name',
						'type' => 'text',
						'class' => 'required',
						'wclass' => 'form-row twothird left'
						),
					array(
						'label' => __('Goal Amount', 'ignitiondeck'),
						'value' => (isset($vars['project_goal']) ? $vars['project_goal'] : ''),
						'name' => 'project_goal',
						'type' => 'number',
						'class' => 'required',
						'wclass' => 'form-row third'
						),
					array(
						'label' => __('Start Date', 'ignitiondeck'),
						'value' => (isset($vars['project_start']) ? $vars['project_start'] : ''),
						'name' => 'project_start',
						'type' => 'date',
						'class' => 'required',
						'wclass' => 'form-row third left'
						),
					array(
						'label' => __('End Date', 'ignitiondeck'),
						'value' => (isset($vars['project_end']) ? $vars['project_end'] : ''),
						'name' => 'project_end',
						'type' => 'date',
						'wclass' => 'form-row third left'
						),
					array(
						'label' => __('Anticipated Ship Date', 'ignitiondeck'),
						'value' => (isset($vars['project_ship_date']) ? $vars['project_ship_date'] : ''),
						'name' => 'project_ship_date',
						'type' => 'date',
						'wclass' => 'form-row third'
						),
					array(
						'label' => __('Project Fund Type', 'ignitiondeck'),
						'value' => (isset($vars['project_fund_type']) ? $vars['project_fund_type'] : ''),
						'name' => 'project_fund_type',
						'type' => 'select',
						'wclass' => 'form-row',
						'options' => array(array('value' => 'capture', 'title' => 'Capture'), array('value' => 'preauth', 'title' => 'Pre-Order'))
						),
					/*array(
						'before' => '<div class="form-row half"><h3>Project Type</h3>',
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
						'before' => '<div class="form-row half"><h3>Campaign End Options</h3>',
						'label' => __('Close on End', 'ignitiondeck'),
						'name' => 'project_end_type',
						'id'	=> 'closed',
						'type' => 'radio',
						'value' => 'closed',
						'wclass' => 'half radio',
						'misc' => ((isset($vars['project_end_type']) && $vars['project_end_type'] == 'closed') || !isset($vars['project_end_type']) ? 'checked="checked"' : '')
						),
					array(
						'label' => __('Leave Open', 'ignitiondeck'),
						'name' => 'project_end_type',
						'id' => 'open',
						'type' => 'radio',
						'value' => 'open',
						'wclass' => 'half radio',
						'misc' => (isset($vars['project_end_type']) && $vars['project_end_type'] == 'open' ? 'checked="checked"' : ''),
						'after' => '</div>'
						),
					array(
						'before' => '<br/><h3>Project Details</h3>',
						'label' => __('Project Short Description', 'ignitiondeck'),
						'value' => (isset($vars['project_short_description']) ? $vars['project_short_description'] : ''),
						'name' => 'project_short_description',
						'type' => 'text',
						'class' => 'required',
						'wclass' => 'form-row'
						),
					array(
						'label' => __('Project Long Description', 'ignitiondeck'),
						'value' => (isset($vars['project_long_description']) ? $vars['project_long_description'] : ''),
						'name' => 'project_long_description',
						'type' => 'textarea',
						'wclass' => 'form-row'
						),
					array(
						'label' => __('Project Video', 'ignitiondeck'),
						'value' => (isset($vars['project_video']) ? $vars['project_video'] : ''),
						'name' => 'project_video',
						'type' => 'textarea',
						'wclass' => 'form-row'
						),
					array(
						'label' => __('Featured Image', 'ignitiondeck'),
						'value' => (isset($vars['project_hero']) ? $vars['project_hero'] : ''),
						'misc' => (isset($vars['project_hero']) ? 'data-url="'.$vars['project_hero'].'"' : ''),
						'name' => 'project_hero',
						'type' => 'file',
						'wclass' => 'form-row half left'
						),
					array(
						'label' => __('Project Image 2', 'ignitiondeck'),
						'value' => (isset($vars['project_image2']) ? $vars['project_image2'] : ''),
						'misc' => (isset($vars['project_image2']) ? 'data-url="'.$vars['project_image2'].'"' : ''),
						'name' => 'project_image2',
						'type' => 'file',
						'wclass' => 'form-row half'
						),
					array(
						'label' => __('Project Image 3', 'ignitiondeck'),
						'value' => (isset($vars['project_image3']) ? $vars['project_image3'] : ''),
						'misc' => (isset($vars['project_image3']) ? 'data-url="'.$vars['project_image3'].'"' : ''),
						'name' => 'project_image3',
						'type' => 'file',
						'wclass' => 'form-row half left'
						),
					array(
						'label' => __('Project Image 4', 'ignitiondeck'),
						'value' => (isset($vars['project_image4']) ? $vars['project_image4'] : ''),
						'misc' => (isset($vars['project_image4']) ? 'data-url="'.$vars['project_image4'].'"' : ''),
						'name' => 'project_image4',
						'type' => 'file',
						'wclass' => 'form-row half'
						),
					array(
						'before' => '<h3>Project Reward Levels</h3>',
						'label' => __('Number of Levels', 'ignitiondeck'),
						'value' => (isset($vars['project_levels']) ? $vars['project_levels'] : '1'),
						'name' => 'project_levels',
						'type' => 'number',
						'wclass' => 'form-row half',
						'class' => 'required',
						'misc' => 'min="1"'
						)
					);
					if (empty($vars) || $vars['project_levels'] == 1) {
						$this->form[] = array(
							'before' => '<div class="form-level">',
							'label' => __('Level Title', 'ignitiondeck'),
							'value' => (isset($vars['levels'][0]['title']) ? $vars['levels'][0]['title'] : ''),
							'name' => 'project_level_title[]',
							'type' => 'text',
							'wclass' => 'form-row'
							);
						$this->form[] =array(
							'label' => __('Level Price', 'ignitiondeck'),
							'value' => (isset($vars['levels'][0]['price']) ? $vars['levels'][0]['price'] : ''),
							'name' => 'project_level_price[]',
							'type' => 'number',
							'wclass' => 'form-row half left'
							);
						$this->form[] =array(
							'label' => __('Level Limit', 'ignitiondeck'),
							'value' => (isset($vars['levels'][0]['limit']) ? $vars['levels'][0]['limit'] : ''),
							'name' => 'project_level_limit[]',
							'type' => 'number',
							'wclass' => 'form-row half'
							);
						$this->form[] =array(
							'label' => __('Level Description', 'ignitiondeck'),
							'value' => (isset($vars['levels'][0]['short']) ? $vars['levels'][0]['short'] : ''),
							'name' => 'level_description[]',
							'type' => 'text',
							'wclass' => 'form-row'
							);
						$this->form[] =array(
							'label' => __('Level Long Description', 'ignitiondeck'),
							'value' => (isset($vars['levels'][0]['long']) ? $vars['levels'][0]['long'] : ''),
							'name' => 'level_long_description[]',
							'type' => 'textarea',
							'wclass' => 'form-row',
							'after' => '</div>'
							);
					}
					else if (isset($vars['project_levels']) && $vars['project_levels'] > 1) {
						for ($i = 0; $i <= $vars['project_levels'] - 1; $i++) {
							$this->form[] = array(
							'before' => '<div class="form-level">',
							'label' => __('Level Title', 'ignitiondeck'),
							'value' => (isset($vars['levels'][$i]['title']) ? $vars['levels'][$i]['title'] : ''),
							'name' => 'project_level_title[]',
							'type' => 'text',
							'wclass' => 'form-row'
							);
						$this->form[] = array(
							'label' => __('Level Price', 'ignitiondeck'),
							'value' => (isset($vars['levels'][$i]['price']) ? $vars['levels'][$i]['price'] : ''),
							'name' => 'project_level_price[]',
							'type' => 'number',
							'wclass' => 'form-row half left'
							);
						$this->form[] = array(
							'label' => __('Level Limit', 'ignitiondeck'),
							'value' => (isset($vars['levels'][$i]['limit']) ? $vars['levels'][$i]['limit'] : ''),
							'name' => 'project_level_limit[]',
							'type' => 'number',
							'wclass' => 'form-row half'
							);
						$this->form[] = array(
							'label' => __('Level Description', 'ignitiondeck'),
							'value' => (isset($vars['levels'][$i]['short']) ? $vars['levels'][$i]['short'] : ''),
							'name' => 'level_description[]',
							'type' => 'text',
							'wclass' => 'form-row'
							);
						$this->form[] = array(
							'label' => __('Level Long Description', 'ignitiondeck'),
							'value' => (isset($vars['levels'][$i]['long']) ? $vars['levels'][$i]['long'] : ''),
							'name' => 'level_long_description[]',
							'type' => 'textarea',
							'wclass' => 'form-row',
							'after' => '</div>'
							);
						}
					}
					$this->form[] = array(
							'value' => __('Submit', 'ignitiondeck'),
							'name' => 'project_fesubmit',
							'type' => 'submit'
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