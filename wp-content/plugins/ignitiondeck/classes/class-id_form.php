<?php

class ID_Form {

	var $fields;

	function __construct(
		$fields = null
		) 
	{
		$this->fields = $fields;
	}

	function build_form() {
		$output = '<ul>';
		foreach ($this->fields as $field) {
			if (isset($field['label'])) {
				$label = $field['label'];
			}
			else {
				$label = '';
			}
			$name = $field['name'];
			if (isset($field['id'])) {
				$id = $field['id'];
			}
			else {
				$id = null;
			}
			if (isset($field['wclass'])) {
				$wclass = $field['wclass'];
			}
			else {
				$wclass = null;
			}
			if (isset($field['class'])) {
				$class= $field['class'];
			}
			else {
				$class = $name;
			}
			$type = $field['type'];
			if (isset($field['options'])) {
				$options = $field['options'];
			}
			else {
				$options = null;
			}
			if (isset($field['value'])) {
				$value = $field['value'];
			}
			else {
				$value = null;
			}
			if (isset($field['misc'])) {
				$misc = $field['misc'];
			}
			else {
				$misc = '';
			}
			// Start Building
			if (isset($field['before'])) {
				$output .= $field['before'];
			}
			$output .= '<li '.(isset($wclass) ? 'class="'.$wclass.'"' : '').'>';
			switch($type) {
				case 'text':
					if (!empty($label)) {
						$output .= '<p><label for="'.$id.'">'.$label.'</label>';
					}
					$output .= '<input type="text" id="'.$id.'" name="'.$name.'" class="'.$class.'" value="'.$value.'" '.$misc.'/>';
					if (!empty($label)) {
						$output .= '</p>';
					}
					break;
				case 'email':
					if (!empty($label)) {
						$output .= '<p><label for="'.$id.'">'.$label.'</label>';
					}
					$output .= '<input type="email" id="'.$id.'" name="'.$name.'" class="'.$class.'" value="'.$value.'" '.$misc.'/>';
					if (!empty($label)) {
						$output .= '</p>';
					}
					break;
				case 'number':
					if (!empty($label)) {
						$output .= '<p><label for="'.$id.'">'.$label.'</label>';
					}
					$output .= '<input type="number" id="'.$id.'" name="'.$name.'" class="'.$class.'" value="'.$value.'" '.$misc.'/>';
					if (!empty($label)) {
						$output .= '</p>';
					}
					break;
				case 'password':
					if (!empty($label)) {
						$output .= '<p><label for="'.$id.'">'.$label.'</label>';
					}
					$output .= '<input type="password" id="'.$id.'" name="'.$name.'" class="'.$class.'" value="'.$value.'" '.$misc.'/>';
					if (!empty($label)) {
						$output .= '</p>';
					}
					break;
				case 'file':
					if (!empty($label)) {
						$output .= '<p><label for="'.$id.'">'.$label.'</label>';
					}
					$output .= '<input type="file" id="'.$id.'" name="'.$name.'" class="'.$class.'" value="'.$value.'" '.$misc.'/>';
					if (!empty($label)) {
						$output .= '</p>';
					}
					break;
				case 'date':
					if (!empty($label)) {
						$output .= '<p><label for="'.$id.'">'.$label.'</label>';
					}
					$output .= '<input type="date" id="'.$id.'" name="'.$name.'" class="'.$class.'" value="'.$value.'" '.$misc.'/>';
					if (!empty($label)) {
						$output .= '</p>';
					}
					break;
				case 'tel':
					if (!empty($label)) {
						$output .= '<p><label for="'.$id.'">'.$label.'</label>';
					}
					$output .= '<input type="tel" id="'.$id.'" name="'.$name.'" class="'.$class.'" value="'.$value.'" '.$misc.'/>';
					if (!empty($label)) {
						$output .= '</p>';
					}
					break;
				case 'hidden':
					$output .= '<input type="hidden" name="'.$name.'" value="'.$value.'" '.$misc.'/>';
					break;
				case 'select':
					if (!empty($label)) {
						$output .= '<p><label for="'.$id.'">'.$label.'</label>';
					}
					$output .= '<select id="'.$id.'" name="'.$name.'" class="'.$class.'" >';
					foreach ($options as $option) {
						$output .= '<option value="'.$option['value'].'" '.($option['value'] == $value ? 'selected="selected"' : '').' '.$misc.'>'.$option['title'].'</option>';
					}
					$output .='</select></p>';
					break;
				case 'checkbox':
					if (!empty($label)) {
						$output .= '<p><label for="'.$id.'">'.$label.'</label>';
					}
					$output .= '<input type="checkbox" id="'.$id.'" name="'.$name.'" class="'.$class.'"  value="'.$value.'" '.$misc.'/>';
					if (!empty($label)) {
						$output .= '</p>';
					}
					break;
				case 'radio':
					$output .= '<input type="radio" id="'.$id.'" name="'.$name.'" class="'.$class.'" value="'.$value.'" '.$misc.'/>';
					if (!empty($label)) {
						$output .= ' <label for="'.$id.'">'.$label.'</label>';
					}
					break;
				case 'textarea':
					if (!empty($label)) {
						$output .= '<p><label for="'.$id.'">'.$label.'</label>';
					}
					$output .= '<textarea id="'.$id.'" name="'.$name.'" class="'.$class.'" '.$misc.'>'.$value.'</textarea>';
					if (!empty($label)) {
						$output .= '</p>';
					}
					break;
				case 'submit':
					$output .= '<p><input type="submit" id="'.$id.'" name="'.$name.'" class="'.$class.'" value="'.$value.'"/>';
					if (!empty($label)) {
						$output .= '</p>';
					}
			}
			$output .= '</li>';
			if (isset($field['after'])) {
				$output .= $field['after'];
			}
		}
		$output .= '</ul>';
		return $output;
	}
}
?>