<option value="all"><?php echo lang('option_all'); ?></option>
 <?php if(count($categories)>0){ foreach ($categories as $category){ ?>
 <option value="<?php echo encrypt($category->id); ?>"><?php echo $category->category; ?></option>
 <?php } } ?>