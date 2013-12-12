<p>
     <label for="<?php echo $this->get_field_id('product'); ?>"><?php echo $tr_Product; ?>:</label>
     <select id="<?php echo $this->get_field_id('product'); ?>" name="<?php echo $this->get_field_name('product'); ?>" >
         <?php foreach($this->_products as $product):?>
         <option value="<?php echo $product->id; ?>" <?php echo ($selectedProduct == $product->id)?'selected="selected"':''; ?>>
            <?php echo $product->product_name; ?>
         </option>
         <?php endforeach; ?>
     </select>
</p>