
 <?php if(count($departments)>0){ foreach ($departments as $department){ ?>
 <option value="<?php echo $department->id; ?>"><?php echo $department->name; ?></option>
 <?php } } ?>