<?php
/**
* Background Image Right Widget
*/
class Fh_Content_Background_Right_Widget extends WP_Widget {
	/**
	* Register widget with WordPress
	*/
	function __construct() {
		parent::__construct(
			'fh_content_background_right_widget',
			__('500 Content Background Right Widget', 'fivehundred'),
			array('description' => __('Right background image with headline and text to the left', 'fivehundred')),
			array('width' => 400));
	}
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	function widget($args, $instance) {
		if (!empty($instance)) {
			$title = $instance['title'];
			$text = html_entity_decode($instance['text']);
			if (isset($instance['image'])) {
				$image = html_entity_decode($instance['image']);
			}
			else {
				$image = '';
			}
			if (isset($instance['padding_top'])) {
				$padding_top = html_entity_decode($instance['padding_top']);
			}
			else {
				$padding_top = '';
			}
			if (isset($instance['padding_bottom'])) {
				$padding_bottom = html_entity_decode($instance['padding_bottom']);
			}
			else {
				$padding_bottom = '';
			}
			if (isset($instance['text_color'])) {
				$text_color = html_entity_decode($instance['text_color']);
			}
			else {
				$text_color = '';
			}
			if (isset($instance['custom_class'])) {
				$custom_class = html_entity_decode($instance['custom_class']);
			}
			else {
				$custom_class = '';
			}
			echo '<div class="ign-content-normal bg_imageright cf '.$custom_class.'" style="background-image: url('.$image.'); padding-top:'.$padding_top.'px; padding-bottom:'.$padding_bottom.'px; color:'.$text_color.' !important;">';
			echo '<h3>'.$title.'</h3>';
			echo '<div class="ign-content-text">'.$text.'</div></div>';
		}
	}
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	function form($instance) {
		if (isset($instance['image'])) {
			$image = $instance['image'];
		}
		if (isset($instance['text'])) {
			$text = $instance['text'];
		}
		if (isset($instance['title'])) {
			$title = $instance['title'];
		}
		if (isset($instance['padding_top'])) {
			$padding_top = $instance['padding_top'];
		}
		if (isset($instance['padding_bottom'])) {
			$padding_bottom = $instance['padding_bottom'];
		}
		if (isset($instance['text_color'])) {
			$text_color = $instance['text_color'];
		}
		if (isset($instance['custom_class'])) {
			$custom_class = $instance['custom_class'];
		}
		$form = '<p>';
		$form .= '<label for="'.$this->get_field_id( 'title' ).'">'.__('Title', 'fivehundred').':';
		$form .= '<input class="widefat" type="text" id="'.$this->get_field_id( 'title' ).'" name="'.$this->get_field_name( 'title' ).'" value="'.(isset($title) ? $title : '').'"/>';
		$form .= '</label></p>';
		$form .= '<p>';
		$form .= '<label for="'.$this->get_field_id( 'image' ).'">'.__('Image', 'fivehundred').':</label>';
		$form .= '<input type="text" class="widefat alert-image" id="'.$this->get_field_id( 'image' ).'" name="'.$this->get_field_name( 'image' ).'" value="'.(!empty($image) ? $image : '').'">';
		$form .= '<button class="button fh_media_button" name="fh_media_button" id="'.$this->get_field_id( 'media' ).'">'.__('Add Image', 'fivehundred').'</button>';
		$form .= '</p>';
		$form .= '<p>';
		$form .='<label for="'.$this->get_field_id( 'text_color' ).'">'.__('Text Color (Hex Color including # - ie: #ffffff) ', 'fivehundred').':';
		$form .= '<input class="widefat" type="text" id="'.$this->get_field_id( 'text_color' ).'" name="'.$this->get_field_name( 'text_color' ).'" value="'.(isset($text_color) ? $text_color : '').'"/>';
		$form .= '<div style="width: 49.5%; display: inline-block; margin-right: 1%;"><label for="'.$this->get_field_id( 'padding_top' ).'">'.__('Padding Top', 'fivehundred').':';
		$form .= '<input class="widefat" type="text" id="'.$this->get_field_id( 'padding_top' ).'" name="'.$this->get_field_name( 'padding_top' ).'" value="'.(isset($padding_top) ? $padding_top : '').'"/></div>';
		$form .= '<div style="width: 49.5%; display: inline-block;"><label for="'.$this->get_field_id( 'padding_bottom' ).'">'.__('Padding Bottom', 'fivehundred').':';
		$form .= '<input class="widefat" type="text" id="'.$this->get_field_id( 'padding_bottom' ).'" name="'.$this->get_field_name( 'padding_bottom' ).'" value="'.(isset($padding_bottom) ? $padding_bottom : '').'"/>';
		$form .= '</div><span style="font-size: 90%; color: #666;">'.__('Numbers only, measured in pixels.', 'fivehundred').'</span></p>';
		$form .= '<textarea class="widefat" rows="16" cols="20" id="'.$this->get_field_id( 'text' ).'" name="'.$this->get_field_name( 'text' ).'">';
		$form .= (!empty($text) ? $text : '');
		$form .= '</textarea>';
		$form .='<label for="'.$this->get_field_id( 'custom_class' ).'">'.__('Custom Class Name (not required)', 'fivehundred').':';
		$form .= '<input class="widefat" type="text" id="'.$this->get_field_id( 'custom_class' ).'" name="'.$this->get_field_name( 'custom_class' ).'" value="'.(isset($custom_class) ? $custom_class : '').'"/>';
		echo $form;
	}
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = esc_attr(strip_tags($new_instance['title']));
		$instance['text'] = esc_attr($new_instance['text']);
		$instance['image'] = esc_attr($new_instance['image']);
		$instance['padding_top'] = esc_attr($new_instance['padding_top']);
		$instance['padding_bottom'] = esc_attr($new_instance['padding_bottom']);
		$instance['text_color'] = esc_attr($new_instance['text_color']);
		$instance['custom_class'] = esc_attr($new_instance['custom_class']);
		return $instance;
	}
}
?>