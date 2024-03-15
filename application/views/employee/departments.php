<option value=""><?php echo lang('option_all'); ?></option>
 <?php if(count($departments)>0){ foreach ($departments as $department){ ?>
 <option value="<?php echo encrypt($department->id); ?>"><?php echo $department->name; ?></option>
 <?php } } ?>