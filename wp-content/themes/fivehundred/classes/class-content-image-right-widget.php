<?php
/**
* Image Right Widget
*/
class Fh_Content_Image_Right_Widget extends WP_Widget {
	/**
	* Register widget with WordPress
	*/
	function __construct() {
		parent::__construct(
			'fh_content_image_right_widget',
			__('500 Content Image Right Widget', 'fivehundred'),
			array('description' => __('Right image with headline and text to the left', 'fivehundred')),
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
			$image = html_entity_decode($instance['image']);
			if (isset($instance['custom_class'])) {
				$custom_class = html_entity_decode($instance['custom_class']);
			}
			else {
				$custom_class = '';
			}
			echo '<div class="ign-content-normal imageright cf '.$custom_class.'"><img src="'.$image.'">';
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
		$instance['custom_class'] = esc_attr($new_instance['custom_class']);
		return $instance;
	}
}
?>