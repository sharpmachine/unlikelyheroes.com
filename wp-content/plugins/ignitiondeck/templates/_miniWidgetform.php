<!--<p>
<a href="#insert-project-link" class="insert-project-link">Insert Link</a>
</p>
<p><input class="product-url-container" name="<?php echo $this->get_field_name('product_mini'); ?>" type="text" id="id_project_URL" value="<?php=get_post_meta($post->ID, 'id_project_URL', true)?>"></p>
<p>-->
<?php echo $tr_Show_Mini; ?>
</p>
<p>
<input type="radio" name="<?php echo $this->get_field_name('widget_options'); ?>" id="showwidget1" value="projectpage" /><?php echo $tr_Widget_Option_Page; ?></p><p><input type="radio" name="<?php echo $this->get_field_name('widget_options'); ?>" id="showwighet2" value="allpages" />On All Pages</p>
<p><input type="radio" name="<?php echo $this->get_field_name('widget_options'); ?>" id="showwighet3" value="miniwidget" /><?php echo $tr_Widget_Option_Small; ?></p>



<?php
//$i = 1;
echo popupInsertLink($i);
//echo "hi";
//$i++;

?>