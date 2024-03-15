<?php
$UserData = $this->session->userdata('userdata');
$profile = $this->profile_modal->getUserProfileDetail($UserData['user_id']);
$uri = $this->uri->segment(2);
$role_id = $UserData['role_id'];
?>
<!-- Left navbar-header -->
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse slimscrollsidebar">
        <div class="user-profile">
            <div class="dropdown user-pro-body">
                <?php if ($profile->avatar) { ?>
                    <div><img src="<?php echo base_url() ?>uploads/user/<?php echo $profile->avatar; ?>" alt="user-img" class="img-circle"></div>
                <?php } else { ?>
                    <div><img src="assets/plugins/images/users/profile.png" alt="user-img" class="img-circle"></div>
                <?php } ?>
                <a href="#" class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $UserData['name']; ?> <span class="caret"></span></a>
                <ul class="dropdown-menu animated flipInY">
                    <li><a href="<?php echo site_url('profile'); ?>"><i class="fa fa-user"></i> <?php echo lang("my_profle"); ?></a></li>
                    <li><a href="licenses/"><i class="fa fa-credit-card"></i> <?php echo lang("licenses"); ?></a></li>
                    <li><a href="logout/"><i class="fa fa-power-off"></i> <?php echo lang("btn_logout"); ?></a></li>
                </ul>
            </div>
        </div>
        <ul class="nav" id="side-menu">
            <li class="sidebar-search hidden-sm hidden-md hidden-lg">
                <!-- input-group -->
                <div class="input-group custom-search-form">
                    <input type="text" class="form-control" placeholder="<?php echo lang("search"); ?>...">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button"> <i class="fa fa-search"></i> </button>
                    </span> </div>
                <!-- /input-group -->
            </li>
            <li class="nav-small-cap m-t-10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo lang("main_menu"); ?></li>
            <li> <a href="<?php echo base_url('dashboard');?>" class="waves-effect"> <i class="fa fa-tachometer"></i> <span class="hide-menu"> <b><?php echo lang("lbl_dashboard") ?></b>  </span> </a></li>
            
             
            <?php if($role_id === '1'){ ?> 
                <li> <a href="#" class="waves-effect"> <i class="fa fa-gear"></i> <span class="hide-menu"> <b><?php echo lang("settings") ?></b><span class="fa arrow"></span>  </span> </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo site_url('settings/general') ?>"> <?php echo lang("general_setting"); ?></a> </li>
                        <li> <a href="<?php echo site_url('settings/hr') ?>"><?php echo lang("menu_hr_setting"); ?></a> </li>
                        <li> <a href="<?php echo site_url('settings/academic') ?>"><?php echo lang("menu_academic_settings"); ?></a> </li>
                        <li> <a href="<?php echo site_url('settings/exam') ?>"><?php echo lang("menu_examination_settings"); ?></a> </li>
                        <li> <a href="<?php echo site_url('fee/show') ?>"><?php echo lang("crumb_fee_settings"); ?></a> </li>
                        <li> <a href="<?php echo base_url('import/index'); ?>"><?php echo lang("import_students"); ?></a> </li>
                    </ul>
                </li>
                
<!-- Employee menus-->   
             <?php }?>
               <?php $ci = & get_instance();
               $arr = $ci->session->userdata("userdata")['persissions'];
               $array = json_decode($arr);
               if(isset($array)){
                   $emp = 0;
                   $add_emp = 0;
                    foreach ($array as $key => $value) {
                       if(in_array('employee-all',array($value->permission)) && $value->val == 'true'){
                           $emp = 1;
                       }
                       if(in_array('employee-add',array($value->permission)) && $value->val == 'true'){
                           $add_emp = 1;
                       }
                    }
               }
               ?>
               <?php if($role_id == '4' && isset($emp) || isset($add_emp)){
                    if($emp == '1' || $add_emp == '1'){?> 
                    <li> <a href="/dashboard" class="waves-effect"> <i class="fa fa-user-secret"></i> <span class="hide-menu"> <b><?php echo lang("menu_employee") ?></b><span class="fa arrow"></span> </span> </a>
                        <ul class="nav nav-second-level"> 
                            <?php if($emp == '1') {?>
                            <li> <a href="<?php echo site_url('employee/all') ?>" ><?php echo lang("menu_employee_list"); ?></a></li>
                            <?php } if($add_emp == '1') {?>
                            <li> <a href="<?php echo site_url('employee/add') ?>" ><?php echo lang("menu_employee_add"); ?></a></li>
                            <?php }?>
                        </ul>
                    </li>
                <?php }} else if($role_id ==='1'){ ?> 
                <li> <a href="/dashboard" class="waves-effect"> <i class="fa fa-user-secret"></i> <span class="hide-menu"> <b><?php echo lang("menu_employee") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo site_url('employee/all') ?>" ><?php echo lang("menu_employee_list"); ?></a></li>
                        <li> <a href="<?php echo site_url('employee/add') ?>" ><?php echo lang("menu_employee_add"); ?></a></li>
                    </ul>

                </li>
            <?php }?>
<!-- Students menus-->   
               <?php if(isset($array)){
                   $show = 0;
                   $add_std = 0;
                    foreach ($array as $key => $value) {
                       if(in_array('students-show',array($value->permission)) && $value->val == 'true'){
                           $show = 1;
                       }
                       if(in_array('students-add',array($value->permission)) && $value->val == 'true'){
                           $add_std = 1;
                       }
                    }
               }
               ?>
                <?php if($role_id == '4' && isset($show) || isset($add_std)){
                    if($show == '1' || $add_std == '1'){?>                   
                    <li> 
                        <a href="/dashboard" class="waves-effect"> <i class="fa fa-users"></i> <span class="hide-menu"> <b><?php echo lang("menu_students") ?></b><span class="fa arrow"></span> </span> </a>
                        <ul class="nav nav-second-level"> 
                            <?php if($show == '1') {?>
                                <li> <a href="<?php echo site_url('students/show') ?>" ><?php echo lang("menu_view_all"); ?></a></li>
                            <?php } if($add_std == '1') {?>
                                <li> <a href="<?php echo site_url('students/add') ?>" ><?php echo lang("menu_admission"); ?></a></li>
                            <?php }?>
                        </ul>
                    </li>
                <?php }} else if($role_id == '1'){?>
                        <li> 
                        <a href="/dashboard" class="waves-effect"> <i class="fa fa-users"></i> <span class="hide-menu"> <b><?php echo lang("menu_students") ?></b><span class="fa arrow"></span> </span> </a>
                        <ul class="nav nav-second-level">
                            <li> <a href="<?php echo site_url('students/show') ?>" ><?php echo lang("menu_view_all"); ?></a></li>
                            <li> <a href="<?php echo site_url('students/add') ?>" ><?php echo lang("menu_admission"); ?></a></li>
                        </ul>
                    </li>
                <?php }?>
 <!-- Parent menus-->   
                <?php if(isset($array)){
                   $p = 0;
                   $add_p = 0;
                    foreach ($array as $key => $value) {
                       if(in_array('parents-all',array($value->permission)) && $value->val == 'true'){
                           $p = 1;
                       }
                       if(in_array('parents-add',array($value->permission)) && $value->val == 'true'){
                           $add_p = 1;
                       }
                    }
               }
               ?>     
            <?php if($role_id == '4' && isset($p) || isset($add_p)){
                  if($p == '1' || $add_p == '1'){?>
                <li> <a href="/dashboard" class="waves-effect"> <i class="fa fa-user"></i> <span class="hide-menu"> <b><?php echo lang('menu_parent') ?><?php echo lang("menu_parents") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">
                        <?php if($p == '1') {?>
                            <li> <a href="<?php echo site_url('parents/all') ?>" ><?php echo lang("menu_view_all"); ?></a></li>
                        <?php } if($add_p == '1') {?>
                            <li> <a href="<?php echo site_url('parents/add') ?>" ><?php echo lang("menu_employee_add"); ?><?php echo lang("menu_parents_list"); ?></a></li>
                        <?php }?>
                    </ul>
                </li>
            <?php }} else if($role_id == '1'){?>
                <li> <a href="/dashboard" class="waves-effect"> <i class="fa fa-user"></i> <span class="hide-menu"> <b><?php echo lang('menu_parent') ?><?php echo lang("menu_parents") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo site_url('parents/all') ?>" ><?php echo lang("menu_view_all"); ?></a></li>
                        <li> <a href="<?php echo site_url('parents/add') ?>" ><?php echo lang("menu_employee_add"); ?><?php echo lang("menu_parents_list"); ?></a></li>
                    </ul>
                </li>
            <?php }?>
<!-- Attendance menus-->   
<?php if(isset($array)){
                   $att_std = 0;
                   $std_rpt = 0;
                   $att_emp = 0;
                   $emp_rpt = 0;
                    foreach ($array as $key => $value) {
                       if(in_array('attendance-show',array($value->permission)) && $value->val == 'true'){
                           $att_std = 1;
                       }
                       if(in_array('attendance-report',array($value->permission)) && $value->val == 'true'){
                           $std_rpt = 1;
                       }
                       if(in_array('attendance-employee',array($value->permission)) && $value->val == 'true'){
                           $att_emp = 1;
                       }
                       if(in_array('attendance-report_employee',array($value->permission)) && $value->val == 'true'){
                           $emp_rpt = 1;
                       }
                    }
               }?> 
            <?php if($role_id == '4' && isset($att_std) || isset($std_rpt) || isset($att_emp) || isset($emp_rpt)){
                if($att_std == '1' || $std_rpt == '1' || $att_emp == '1' || $emp_rpt == '1'){?>
                    <li> <a href="#" class="waves-effect"> <i class="fa fa fa-signal"></i> <span class="hide-menu"> <b><?php echo lang("lbl_attendance") ?></b><span class="fa arrow"></span> </span> </a>
                        <ul class="nav nav-second-level">
                            <?php if($att_std == '1') {?>
                                <li> <a href="<?php echo site_url('attendance/show') ?>" ><?php echo lang("lbl_student_attendance"); ?></a></li>
                            <?php } if($std_rpt == '1') {?>
                                <li> <a href="<?php echo site_url('attendance/report') ?>" ><?php echo lang("lbl_attendance_report"); ?></a></li>
                            <?php } if($att_emp == '1') {?>
                                <li> <a href="<?php echo site_url('attendance/employee') ?>" ><?php echo lang("lbl_employee_attendance"); ?></a></li>
                            <?php } if($emp_rpt == '1') {?>
                                <li> <a href="<?php echo site_url('attendance/report_employee') ?>" ><?php echo lang("lbl_employee_report"); ?></a></li>
                            <?php }?>
                        </ul>
                    </li>
            <?php }} else if($role_id == '1'){?>
                <li> <a href="#" class="waves-effect"> <i class="fa fa fa-signal"></i> <span class="hide-menu"> <b><?php echo lang("lbl_attendance") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo site_url('attendance/show') ?>" ><?php echo lang("lbl_student_attendance"); ?></a></li>
                        <li> <a href="<?php echo site_url('attendance/report') ?>" ><?php echo lang("lbl_attendance_report"); ?></a></li>
                        <li> <a href="<?php echo site_url('attendance/employee') ?>" ><?php echo lang("lbl_employee_attendance"); ?></a></li>
                        <li> <a href="<?php echo site_url('attendance/report_employee') ?>" ><?php echo lang("lbl_employee_report"); ?></a></li>

                    </ul>
                </li>
            <?php } ?>
<!-- time Table menus-->     
            <?php if(isset($array)){
                $timeTable = 0;
                 foreach ($array as $key => $value) {
                    if(in_array('timetable-show',array($value->permission)) && $value->val == 'true'){
                        $timeTable = 1;
                    }
                 }
            } ?>
            <?php if($role_id == '4' && isset($timeTable)){
                if($timeTable == '1'){?>                   
                <li> <a href="#" class="waves-effect"> <i class="fa fa-graduation-cap"></i> <span class="hide-menu"> <b><?php echo lang("lbl_academics") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo site_url('timetable/show') ?>" ><?php echo lang("lbl_timetable"); ?></a></li>
                    </ul>
                </li>
            <?php }} else if($role_id == '1'){?>
                <li> <a href="#" class="waves-effect"> <i class="fa fa-graduation-cap"></i> <span class="hide-menu"> <b><?php echo lang("lbl_academics") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo site_url('timetable/show') ?>" ><?php echo lang("lbl_timetable"); ?></a></li>
                    </ul>
                </li>
            <?php } ?>
                
            <li> <a href="#" class="waves-effect"> <i class="fa fa-envelope"></i> <span class="hide-menu"> <b><?php echo lang("crumb_messages") ?></b><span class="fa arrow"></span> </span> </a>
                <ul class="nav nav-second-level">
                    <li> <a href="<?php echo site_url('messages/show') ?>" ><?php echo lang("crumb_all_messages"); ?></a></li>
                </ul>
            </li>
<!-- Study Material menus-->  
            <?php if(isset($array)){
                   $upload = 0;
                   $download = 0;
                   $shop = 0;
                    foreach ($array as $key => $value) {
                       if(in_array('study_material-upload',array($value->permission)) && $value->val == 'true'){
                           $upload = 1;
                       }
                       if(in_array('study_material-download',array($value->permission)) && $value->val == 'true'){
                           $download = 1;
                       }
                       if(in_array('study_material-book_shop',array($value->permission)) && $value->val == 'true'){
                           $shop = 1;
                       }
                    }
               }
               ?>
            <?php if($role_id == '4' && isset($upload) || isset($download) || isset($shop)){
                if($upload == '1' || $download == '1' || $shop == '1'){?>
                    <li> <a href="javascipt:void(0)" class="waves-effect"> <i class="fa fa-download"></i> <span class="hide-menu"> <b><?php echo lang("study_material") ?></b><span class="fa arrow"></span> </span> </a>
                        <ul class="nav nav-second-level">
                            <?php if($upload == '1') {?>
                                <li> <a href="<?php echo site_url('study_material/upload') ?>" ><?php echo lang("lbl_upload"); ?></a></li>
                            <?php } if($download == '1') {?>
                                <li> <a href="<?php echo site_url('study_material/download') ?>" ><?php echo lang("lbl_download"); ?></a></li>
                            <?php } if($shop == '1') {?>
                                <li> <a href="<?php echo site_url('study_material/book_shop') ?>" ><?php echo lang('lbl_book_shop'); ?></a></li>
                            <?php }?>
                        </ul>
                    </li>
            <?php }} else if($role_id == '1'){?>
                <li> <a href="javascipt:void(0)" class="waves-effect"> <i class="fa fa-download"></i> <span class="hide-menu"> <b><?php echo lang("study_material") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo site_url('study_material/upload') ?>" ><?php echo lang("lbl_upload"); ?></a></li>
                        <li> <a href="<?php echo site_url('study_material/download') ?>" ><?php echo lang("lbl_download"); ?></a></li>
                    </ul>
                </li>
                <li> <a href="<?php echo site_url('study_material/book_shop') ?>" class="waves-effect"> <i class="fa fa-book"></i> <span class="hide-menu"> <b><?php echo lang('lbl_book_shop'); ?></b> </span> </a>
                <!--<li> <a href="<?php echo site_url('study_material/book_shop') ?>" ><?php echo lang('lbl_book_shop'); ?></a></li>-->
            <?php } ?>
<!-- Student Fee menus-->    
            <?php if(isset($array)){
                $std_fee = 0;
                 foreach ($array as $key => $value) {
                    if(in_array('fee-collection',array($value->permission)) && $value->val == 'true'){
                        $std_fee = 1;
                    }
                 }
            } ?>
            <?php if($role_id == '4' && isset($std_fee)){?>
                <?php if($std_fee == '1'){?> 
                    <li> <a href="javascipt:void(0)" class="waves-effect"> <i class="fa fa-dollar"></i> <span class="hide-menu"> <b><?php echo lang("crumb_fee") ?></b><span class="fa arrow"></span> </span> </a>
                        <ul class="nav nav-second-level">
                            <li> <a href="<?php echo site_url('fee/collection') ?>" ><?php echo lang("crumb_fee_collection") ?></a></li>
                        </ul>
                    </li>
            <?php }} else if($role_id == '1'){?>
                <li> <a href="javascipt:void(0)" class="waves-effect"> <i class="fa fa-dollar"></i> <span class="hide-menu"> <b><?php echo lang("crumb_fee") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo site_url('fee/collection') ?>" ><?php echo lang("crumb_fee_collection") ?></a></li>
                    </ul>
                </li>
            <?php } ?>
<!-- Forms menus-->    
            <?php if(isset($array)){
                $all = 0;
                $crt = 0;
                $cat = 0;
                 foreach ($array as $key => $value) {
                    if(in_array('forms-all',array($value->permission)) && $value->val == 'true'){
                        $all = 1;
                    }if(in_array('forms-create',array($value->permission)) && $value->val == 'true'){
                        $crt = 1;
                    }if(in_array('forms/category_create',array($value->permission)) && $value->val == 'true'){
                        $cat = 1;
                    }
                 }
            } ?>
            <?php if($role_id == '4' && isset($all) || isset($crt) || isset($cat) ){?>
                 <li> <a href="#" class="waves-effect" > <i class="fa fa-file"></i> <span class="hide-menu"> <b> <?php echo lang("lbl_forms") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">
                        <?php if($all == '1'){?>  
                            <li> <a href="<?php echo site_url('forms/all');?>" ><?php echo lang("menu_view_all"); ?></a></li>
                        <?php } if($crt == '1'){?>
                            <li> <a href="<?php echo site_url('forms/create');?>" ><?php echo lang("lbl_new_form"); ?></a></li>
                        <?php }if($cat == '1'){?> 
                            <li> <a href="<?php echo site_url('forms/category_create');?>" ><?php echo lang("lbl_form_categories"); ?></a></li>
                        <?php }?> 
                    </ul>
                </li>
                <?php } else if($role_id == '1'){?>
                <li> <a href="#" class="waves-effect" > <i class="fa fa-file"></i> <span class="hide-menu"> <b> <?php echo lang("lbl_forms") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo site_url('forms/all');?>" ><?php echo lang("menu_view_all"); ?></a></li>
                        <li> <a href="<?php echo site_url('forms/create');?>" ><?php echo lang("lbl_new_form"); ?></a></li>
                        <li> <a href="<?php echo site_url('forms/category_create');?>" ><?php echo lang("lbl_form_categories"); ?></a></li>
                    </ul>
                </li>
            <?php } ?>
            <li> <a href="#" class="waves-effect" > <i class="fa fa-file-text"></i> <span class="hide-menu"> <b> <?php echo lang("reports_all") ?></b><span class="fa arrow"></span> </span> </a>
                <ul class="nav nav-second-level">
                    <li> <a href="<?php echo site_url('reports/all');?>" ><?php echo lang("reports_all"); ?></a></li>

                </ul>
            </li>
            <li> <a href="#" class="waves-effect" > <i class="fa fa-clipboard"></i> <span class="hide-menu"> <b> <?php echo lang("lbl_examination") ?></b><span class="fa arrow"></span> </span> </a>
                <ul class="nav nav-second-level">
                    <li> <a href="<?php echo site_url('examination/add');?>" ><?php echo lang("lbl_manage_exam"); ?></a></li>
                    <li> <a href="<?php echo site_url('examination/marks');?>" ><?php echo lang("lbl_mark_exam"); ?></a></li>
                </ul>
            </li>
            <li> <a href="#" class="waves-effect" > <i class="fa fa-clipboard"></i> <span class="hide-menu"> <b> <?php echo lang("lbl_syllabus") ?></b><span class="fa arrow"></span> </span> </a>
                <ul class="nav nav-second-level">
                    <li> <a href="<?php echo site_url('syllabus/add');?>" ><?php echo lang("lbl_manage_syllabus"); ?></a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- Left navbar-header end -->