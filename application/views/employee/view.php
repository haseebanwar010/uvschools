<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('heading_view_employee') ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="#"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li><a href="#"><?php echo lang('crumb_employee') ?></a></li>
                    <li class="active"><?php echo lang('heading_view_employee') ?></li>

                </ol>
            </div>
        </div>
        <?php
        $error = $this->session->flashdata('alert');
        if (!empty($error)) {
            ?>
            <div class="alert alert-dismissable <?php
            if ($this->session->flashdata('alert')['status'] == 'error') {
                echo 'alert-danger';
            } else {
                echo 'alert-success';
            }
            ?>"> 
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>     
            <?= $this->session->flashdata("alert")['message']; ?> 
        </div>
        <?php } ?>
        <!-- /.row -->
        <!-- Page Content -->
        <!-- .row -->
        <?php echo $this->session->flashdata('success-image'); ?>
        <div class="hint"><?php echo lang('help_emp_view'); ?></div>
        <div class="row">

            <div class="col-md-12 col-xs-12">
                <div class="white-box">
                    <ul class="nav customtab nav-tabs" role="tablist">
                        <li role="presentation" class="nav-item"><a href="#personal" class="nav-link <?php
                        if ($this->session->flashdata('emp_selected_tab') == "personal") {
                            echo 'active';
                        } else if ($this->session->flashdata("emp_selected_tab") == null) {
                            echo 'active';
                        }
                        ?>"
                        aria-controls="profile" role="tab"
                        data-toggle="tab" aria-expanded="true"><span
                        class="visible-xs"><i class="fa fa-user"></i></span><span class="hidden-xs"> <?php echo lang('tab_personal') ?></span></a>
                    </li>
                    <li role="presentation" class="nav-item"><a href="#professional" class="nav-link <?php
                    if ($this->session->flashdata('emp_selected_tab') == "perfessional") {
                        echo 'active';
                    }
                    ?>"
                    aria-controls="profile" role="tab"
                    data-toggle="tab" aria-expanded="false"><span
                    class="visible-xs"><i class="fa fa-graduation-cap"></i></span> <span
                    class="hidden-xs"><?php echo lang('tab_professional') ?></span></a></li>
                    <li role="presentation" class="nav-item"><a href="#permission" class="nav-link <?php
                    if ($this->session->flashdata('emp_selected_tab') == "permissions") {
                        echo 'active';
                    }
                    ?>"
                    aria-controls="profile" role="tab"
                    data-toggle="tab" aria-expanded="false"><span
                    class="visible-xs"><i class="fa fa-lock"></i></span> <span
                    class="hidden-xs"><?php echo lang('tab_permissions') ?></span></a></li>
                    <li role="presentation" class="nav-item"><a href="#banks" class="nav-link <?php
                    if ($this->session->flashdata('emp_selected_tab') == "banks") {
                        echo 'active';
                    }
                    ?>"
                    aria-controls="profile" role="tab"
                    data-toggle="tab" aria-expanded="false"><span
                    class="visible-xs"><i class="fa fa-university"></i></span> <span
                    class="hidden-xs"><?php echo lang('tab_banks') ?></span></a></li>
                    <li role="presentation" class="nav-item"><a href="#attachments" class="nav-link"
                        aria-controls="profile" role="tab"
                        data-toggle="tab" aria-expanded="false"><span
                        class="visible-xs"><i class="fa fa-university"></i></span> <span
                        class="hidden-xs"><?php echo lang('lbl_attachments') ?></span></a></li>
                        
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane <?php
                        if ($this->session->flashdata('emp_selected_tab') == "personal") {
                            echo 'active';
                        } else if ($this->session->flashdata("emp_selected_tab") == null) {
                            echo 'active';
                        }
                        ?>" id="personal">
                        <div class="hint"><?php echo lang('help_emp_view_personal'); ?></div>

                        <form action="" method="post" class="form-material" id="settings" enctype="multipart/form-data">
                            <div class="form-body">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            
                                            
                                            
                                            <img lass="form-control" style="width: 100px;" src="<?php echo base_url() ?>uploads/user/<?php echo $employee->avatar; ?>"/>
                                            
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php echo lang('lbl_gender') ?></label>
                                            <p><?php echo $employee->gender; ?></p>
                                            
                                        </div>
                                    </div>
                                    <!--/span-->

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php echo lang('lbl_name') ?></label>
                                            
                                            <p><?php echo $employee->name; ?></p>
                                        </div>

                                    </div>
                                    <!--/span-->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php echo lang('lbl_marital_status') ?></label>
                                            
                                            
                                            <p><?php echo $employee->marital_status; ?></p>

                                        </div>
                                    </div>
                                    <!--/span-->

                                </div>
                                <!--/row-->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php echo lang('lbl_contact') ?></label>
                                            
                                            <p><?php echo $employee->contact; ?></p>
                                        </div>
                                    </div>
                                    <!--/span-->

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php echo lang('lbl_language') ?></label>
                                            
                                            <p><?php echo $employee->language; ?></p>
                                            
                                        </div>

                                    </div>
                                    <!--/span-->
                                </div>
                                <!--/row-->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php echo lang('plc_email') ?></label>
                                            <p><?php echo $employee->email; ?></p>

                                        </div>
                                    </div>
                                    <!--/span-->

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php echo lang('lbl_dob') ?></label>
                                            <p><?php echo ($employee->dobn=='0000-00-00')?"":to_html_date($employee->dobn); ?></p>
                                        </div>
                                    </div>
                                    <!--/span-->

                                </div>
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php echo lang('lbl_nationality') ?></label>
                                            <p><?php echo $employee->nationality; ?></p>
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php echo lang('lbl_passport_number') ?></label>
                                            <p><?php echo $employee->passport_number; ?></p>
                                            

                                        </div>

                                    </div>
                                    
                                    <!--/span-->

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php echo lang('lbl_fax') ?></label>
                                            
                                            <p><?php echo $employee->fax; ?></p>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php echo lang('lbl_identity_card_number') ?></label>
                                            
                                            <p><?php echo $employee->ic_number; ?></p>

                                        </div>
                                    </div>
                                    <!--/span-->

                                    


                                    <!--/span-->

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo lang('lbl_country') ?></label>
                                            <p><?php echo $employee->country; ?></p>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label><?php echo lang('lbl_city') ?></label>
                                            
                                            <p><?php echo $employee->city; ?></p>
                                        </div>
                                    </div>
                                    <!--/span-->
                                    
                                    <!--/span-->

                                </div>

                                <div class="row">
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label><?php echo lang('lbl_street') ?></label>
                                            <p><?php echo $employee->address; ?></p>
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label><?php echo lang('lbl_basic_salary') ?></label>
                                            <p><?php echo $this->session->userdata("userdata")["currency_symbol"]; ?><?php echo $employee->basic_salary; ?></p>
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>

                                
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane " id="professional">
                        <div class="hint"><?php echo lang('help_emp_view_professional'); ?></div>


                        <form action="" method="post" class="form-material" id="settings" enctype="multipart/form-data">
                            <div class="form-body">
                               
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php echo lang('title_category') ?></label>
                                            
                                            
                                            <?php
                                            if (count($categories) > 0) {
                                                foreach ($categories as $cat) {
                                                    ?>
                                                    <p> <?php if ($employee->role_category_id == $cat->id) { echo $cat->category; } ?></p>
                                                    <?php } } ?>    
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('title_department') ?></label>
                                                    
                                                    <?php
                                                    if (count($departments) > 0) {
                                                        foreach ($departments as $department) {
                                                            ?>
                                                            <p> <?php if ($employee->department_id == $department->id) { echo $department->name; } ?></p>
                                                            <?php } } ?>           
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                </div>

                                                <!--/row-->
                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('job_title') ?></label>
                                                            
                                                            <p><?php echo $employee->job_title; ?></p>

                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_experience_duration') ?></label>
                                                            
                                                            <p><?php echo $employee->experience_duration; ?></p>
                                                        </div>
                                                    </div>

                                                </div>
                                                <!--/row-->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_qualification') ?></label>
                                                            
                                                            <p><?php echo $employee->qualification; ?></p>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_experience_info') ?></label>
                                                            
                                                            <p><?php echo $employee->experience_info; ?></p>
                                                        </div>
                                                    </div>
                                                    <!--/span-->


                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('emp_id') ?></label>
                                                            
                                                            <p><?php echo $employee->rollno; ?></p>
                                                        </div>
                                                    </div>
                                                </div>

                                                

                                            </div>

                                        </form>

                                    </div>
                                    <div class="tab-pane <?php
                        if ($this->session->flashdata('emp_selected_tab') == "permissions") {
                            echo 'active';
                        }
                        ?>" id="permission">
                        <div class="hint"><?php echo lang('help_emp_edit_permissions'); ?></div>

                        <form class="form-material" method="post" action="employee/updatePermissions" id="settings">
                            <input type="hidden" name="employee_id" value="<?php echo $employee->id; ?>"/>
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php echo lang('lbl_students'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i < 7){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id = "pe<?= $i; ?>" disabled="" name="<?= $i; ?>"  type="checkbox" <?php 
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for = "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div> 
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php echo lang('crumb_parents'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >=7 && $i < 11 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id = "pe<?= $i; ?>" disabled="" name="<?= $i; ?>"  type="checkbox" <?php 
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for = "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div>
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php echo lang('heading_all_employee'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >=11 && $i <16 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id = "pe<?= $i; ?>" disabled="" name="<?= $i; ?>"  type="checkbox" <?php
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for = "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div>
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php echo lang('menu_academic_settings'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >=16 && $i <19 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id = "pe<?= $i; ?>" disabled="" name="<?= $i; ?>"  type="checkbox" <?php
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for= "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div>
                                </div>
                                <hr/>
                                <div class="row">
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php echo lang('syllabus'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >=19 && $i <23 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id="pe<?= $i; ?>" disabled=""  name="<?= $i; ?>" type="checkbox" <?php
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for= "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div>
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php echo lang('profile_settings'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >=23 && $i < 30 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id= "pe<?= $i; ?>" disabled="" name="<?= $i; ?>" type="checkbox" <?php
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for= "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }
                                        } ?> 
                                    </div>  
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php echo lang('lbl_evaluation'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >=30 && $i <36 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id= "pe<?= $i; ?>" disabled="" name="<?= $i; ?>" type="checkbox" <?php
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for= "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div>
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php echo lang('menu_examination_settings'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >=36 && $i <40 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id= "pe<?= $i; ?>" disabled="" name="<?= $i; ?>" type="checkbox" <?php
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for= "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div>
                                </div>

                                <hr/>
                               <div class="row">
                                   <div class="col-md-3">
                                       <h3 class="box-title m-b-0"><?php echo lang('lbl_online_examination'); ?></h3>
                                       <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                       <?php
                                       $count = count($emp_permissions);
                                       for($i=0; $i< $count; $i++){
                                           if($i >=40 && $i <47 ){?>
                                             <div class="checkbox checkbox-info checkbox-circle">
                                               <input id="pe<?= $i; ?>" disabled="" name="<?= $i; ?>" type="checkbox" <?php
                                               if ($emp_permissions[$i]->val == "true") {
                                                   echo "checked";
                                               }
                                               ?>/>
                                               <label for= "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                           </div>
                                       <?php }} ?>
                                   </div>
                                   <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php echo lang('lbl-app-permission'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >=47 && $i <51 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id="pe<?= $i; ?>" disabled="" name="<?= $i; ?>" type="checkbox" <?php
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for= "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div>
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php echo lang('forms_settings'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >=51 && $i <55 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id="pe<?= $i; ?>" disabled="" name="<?= $i; ?>" type="checkbox" <?php
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for= "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div>
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php echo lang('lbl_user_management'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >=55 && $i <61 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id="pe<?= $i; ?>" disabled="" name="<?= $i; ?>" type="checkbox" <?php
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for= "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div>
                               </div>
                               <hr/>
                                <div class="row">
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php echo lang("lbl_trash"); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >=61 && $i <64 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id="pe<?= $i; ?>" disabled=""  name="<?= $i; ?>" type="checkbox" <?php
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for= "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div>
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php  echo lang('lbl_fee'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >=64 && $i <71 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id="pe<?= $i; ?>" disabled=""  name="<?= $i; ?>" type="checkbox" <?php
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for= "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div>
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php  echo lang('lbl_payroll'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >=71 && $i <78 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id="pe<?= $i; ?>" disabled=""  name="<?= $i; ?>" type="checkbox" <?php
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for= "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div>
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php  echo lang('lbl_accounts'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >=78 && $i <96 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id="pe<?= $i; ?>" disabled=""  name="<?= $i; ?>" type="checkbox" <?php
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for= "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div>
                                    
                                   
                                </div>
                                
                            </div>
                        </form>
                    </div>
                                <div class="tab-pane" id="banks">
                                    <div class="hint"><?php echo lang('help_emp_view_banks'); ?></div>
                                    <?php include(APPPATH . "views/employee/banks_view.php"); ?>                           
                                </div>

                                <div class="tab-pane" id="attachments">
                                    <div class="hint"><?php echo lang('help_emp_attach'); ?></div>
                                    
                                    <?php echo $attachments ; ?>                         
                                </div>
                                


                            </div>

                        </div>
                    </div>
                </div>
                <!--page content end-->
            </div>
            <!-- /.container-fluid -->
            <?php include(APPPATH . "views/inc/footer.php"); ?>
            <script>
                function getDepartments(category) {
                    var school_id = '<?php echo $employee->school_id; ?>';
                    $.post('<?php echo site_url("employee/getDepartmentsByCatIDEdit"); ?>', {school_id: school_id, category: category}).done(function (res) {
                        $("select[name='department_id']").html(res);

                    });
                }
            </script>

            <script id="new_bank_template" type="text/template">
                <tr>
                    <td> {{bank_name}} </td>
                    <td> {{account_number}}</td>
                    <td> {{beneficiary_name}}</td>
                    <td> {{swift_code}} </td>
                    <td> {{iban_code}} </td>
                    <td class='text-center'><i class='fa {{primary_class}}' id='primary'></i> </td>
                    <td class='text-right'><button type='button' class='btn btn-info btn-circle editBank' data-toggle='modal' data-target='#editBankModal' id={{bank_id}}><i class='fa fa-pencil'></i></button><button type='button' class='btn btn-danger btn-circle deleteBank' data-toggle='modal' data-target='#deleteBankModal' id={{bank_id}}><i class='fa  fa-trash-o'></i></button></td>
                </tr>
            </script>

            <script id="updated_bank_template" type="text/template">

                <td> {{bank_name}} </td>
                <td> {{account_number}}</td>
                <td> {{beneficiary_name}}</td>
                <td> {{swift_code}} </td>
                <td> {{iban_code}} </td>
                <td class='text-center'><i class='fa {{primary_class}}' id='primary'></i> </td>
                <td class='text-right'><button type='button' class='btn btn-info btn-circle editBank' data-toggle='modal' data-target='#editBankModal' id={{bank_id}}><i class='fa fa-pencil'></i></button><button type='button' class='btn btn-danger btn-circle deleteBank' data-toggle='modal' data-target='#deleteBankModal' id={{bank_id}}><i class='fa  fa-trash-o'></i></button></td>

            </script>

            <!--// Mustache Templates -->



            <script type="text/javascript">
                $(document).ready(function () {

                    $('#addnewbank').click(function () {
                        $(this).attr("disabled", "disabled");

                        var formdata = $('#newbankform').serialize();
                        $.ajax({
                            type: 'POST',
                            data: formdata,
                            dataType: "json",
                            url: '<?php echo site_url('profile/addNewBank/') ?>',
                            success: function (response) {
                                console.log(response);

                                if (response.success) {
                                    $('#bank_add_alert_outside').removeClass('alert-danger').addClass('alert-success').html(response.success_message).show();
                                    $('#addBank').modal('toggle');
                                    $('#primary_bank').removeAttr('checked');
                                    $('#name').val('');
                                    $('#accountno').val('');
                                    $('#beneficiary').val('');
                                    $('#iban').val('');
                                    $('#swift').val('');

                                    $('#addnewbank').removeAttr("disabled");

                                    var name = response.data.bank_name;
                                    var account_number = response.data.account_number;
                                    var beneficiary_name = response.data.beneficiary_name;
                                    var swift_code = response.data.swift_code;
                                    var iban_code = response.data.iban_code;
                                    var is_primary = response.data.is_primary;
                                    var id = response.data.id;

                                    if (is_primary == "Y") {
                                        $('td i').each(function () {
                                            $(this).removeClass('fa-check');
                                        });

                                        var template = $("#new_bank_template").html();

                                        var dynamichtml = Mustache.render(template, {
                                            bank_name: name,
                                            account_number: account_number,
                                            beneficiary_name: beneficiary_name,
                                            swift_code: swift_code,
                                            iban_code: iban_code,
                                            primary_class: "fa-check",
                                            bank_id: id
                                        });

                                        $('#myTable tbody').append(dynamichtml);

                                    } else {
                                        var template = $("#new_bank_template").html();

                                        var dynamichtml = Mustache.render(template, {
                                            bank_name: name,
                                            account_number: account_number,
                                            beneficiary_name: beneficiary_name,
                                            swift_code: swift_code,
                                            iban_code: iban_code,
                                            primary_class: "",
                                            bank_id: id
                                        });

                                        $('#myTable tbody').append(dynamichtml);
                                    }




                                } else {
                                    $('#addnewbank').removeAttr("disabled");
                                    $('#bank_add_alert').removeClass('alert-success').addClass('alert-danger').html(response.error).show();


                                }




                            }


                        });




                    })

$('table').on('click', 'button.editBank', function () {
    $('#bank_delete_alert').hide();
    $('#bank_add_alert').hide();
    $('#bank_add_alert_outside').hide();
    $('#bank_edit_alert').hide();
    $('#bank_update_alert_outside').hide();
    var id = $(this).attr('id');
    $('#editprimary_bank').removeAttr('checked');
    $('#editname').val('');
    $('#editaccountno').val('');
    $('#editbeneficiary').val('');
    $('#editiban').val('');
    $('#editswift').val('');
    $('#bank_edit_id').val(id);
    $.ajax({

        type: 'POST',
        data: {id: id},
        dataType: "json",
        url: '<?php echo site_url('profile/getBankDetails/') ?>',
        success: function (response) {
            console.log(response);
            if (response.success) {

                var name = response.data.bank_name;
                var account_number = response.data.account_number;
                var beneficiary_name = response.data.beneficiary_name;
                var swift_code = response.data.swift_code;
                var iban_code = response.data.iban_code;
                var is_primary = response.data.is_primary;
                var id = response.data.id;


                $('#bank_edit_id').val(response.data.id);
                if (is_primary == "Y") {
                    $('#editprimary_bank').prop('checked', true);
                } else {
                    $('#editprimary_bank').prop('checked', false);
                }

                $('#editname').val(name);
                $('#editaccountno').val(account_number);
                $('#editbeneficiary').val(beneficiary_name);
                $('#editiban').val(iban_code);
                $('#editswift').val(swift_code);
            }
        }

    })
})

$('#updatebank').click(function () {
    $(this).attr("disabled", "disabled");

    var formdata = $('#editbankform').serialize();
    $.ajax({
        type: 'POST',
        data: formdata,
        dataType: "json",
        url: '<?php echo site_url('profile/updateBank/') ?>',
        success: function (response) {
            console.log(response);

            if (response.success) {
                $('#bank_update_alert_outside').removeClass('alert-danger').addClass('alert-success').html(response.success_message).show();
                $('#editBankModal').modal('toggle');
                $('#editprimary_bank').removeAttr('checked');
                $('#editname').val('');
                $('#editaccountno').val('');
                $('#editbeneficiary').val('');
                $('#editiban').val('');
                $('#editswift').val('');

                $('#updatebank').removeAttr("disabled");

                var name = response.data.bank_name;
                var account_number = response.data.account_number;
                var beneficiary_name = response.data.beneficiary_name;
                var swift_code = response.data.swift_code;
                var iban_code = response.data.iban_code;
                var is_primary = response.data.is_primary;
                var id = response.data.id;




                var updatedRow = $('#' + id).parents('tr');
                if (is_primary == "Y") {

                    $('td i').each(function () {
                        $(this).removeClass('fa-check');
                    });

                    var template = $("#updated_bank_template").html();

                    var dynamichtml = Mustache.render(template, {
                        bank_name: name,
                        account_number: account_number,
                        beneficiary_name: beneficiary_name,
                        swift_code: swift_code,
                        iban_code: iban_code,
                        primary_class: "fa-check",
                        bank_id: id
                    });

                    updatedRow.html(dynamichtml);

                } else {
                    var template = $("#updated_bank_template").html();

                    var dynamichtml = Mustache.render(template, {
                        bank_name: name,
                        account_number: account_number,
                        beneficiary_name: beneficiary_name,
                        swift_code: swift_code,
                        iban_code: iban_code,
                        primary_class: "",
                        bank_id: id
                    });

                    updatedRow.html(dynamichtml);
                }

            } else {
                $('#bank_edit_alert').removeClass('alert-success').addClass('alert-danger').html(response.error).show();
                $('#updatebank').removeAttr("disabled");

            }
        }
    })
})

$('#add_bank_btn').click(function () {
    $('#bank_delete_alert').hide();
    $('#bank_add_alert').hide();
    $('#bank_add_alert_outside').hide();
    $('#bank_edit_alert').hide();
    $('#bank_update_alert_outside').hide();
    $('#primary_bank').removeAttr('checked');
    $('#name').val('');
    $('#accountno').val('');
    $('#beneficiary').val('');
    $('#iban').val('');
    $('#swift').val('');
})
$('#close_add_bank').click(function () {
    $('#bank_add_alert').hide();
});

$('#change_password_btn').click(function () {
    event.preventDefault();
    var formdata = $('#change_password').serialize();
    $.ajax({
        type: 'POST',
        data: formdata,
        dataType: "json",
        url: '<?php echo site_url('employee/changePassword/') ?>',
        success: function (response) {
            console.log(response);

            if (response.success) {
                $('#change_alert').removeClass('alert-danger').addClass('alert-success').show();
                $('#alert_msg').html("<?php echo lang('pswrd_changed_success') ?>");

                $('#current_password').val('');
                $('#new_password').val('');
                $('#confirm_password').val('');



            } else {
                $('#change_alert').removeClass('alert-success').addClass('alert-danger').show();

                $('#alert_msg').html(response.error);
            }

        }
    });
});




$('table').on('click', 'button.deleteBank', function () {
    $('#bank_delete_alert').hide();
    $('#bank_add_alert_outside').hide();
    $('#bank_edit_alert').hide();
    $('#bank_update_alert_outside').hide();
    var id = $(this).attr('id');
    $('#bank_delete_id').val(id);

});




$('#confirm_delete').click(function () {
    var tr_id = '#' + $('#bank_delete_id').val();

    $.ajax({
        type: 'POST',
        data: {id: $('#bank_delete_id').val()},
        dataType: "json",
        url: '<?php echo site_url('profile/deleteBank/') ?>',
        success: function (response) {
            console.log(response);
            if (response.success) {
                $(tr_id).parents('tr').remove();

                $('#deleteBankModal').modal('toggle');

                $('#bank_delete_alert').removeClass('alert-danger').addClass('alert-success').html(response.success_message).show();
            } else {
                $('#bank_delete_alert').removeClass('alert-success').addClass('alert-danger').html(response.error).show();


            }

            if (response.primary == "Y") {
                $('tbody tr:first #primary').addClass('fa-check');
            }

        }
    })
});
})
</script>
