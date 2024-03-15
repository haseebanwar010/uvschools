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
                <a href="#" class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $UserData['name']; ?> - <?php echo lang(ucfirst($UserData['role_name'])); ?> <span class="caret"></span></a>
                <ul class="dropdown-menu animated flipInY">
                    <li><a href="<?php echo site_url('profile'); ?>"><i class="fa fa-user"></i> <?php echo lang("my_profle"); ?></a></li>
                    <?php if($role_id == '1') { ?>
                    <li><a href="licenses/"><i class="fa fa-credit-card"></i> <?php echo lang("licenses"); ?></a></li>
                    <?php } ?>
                    <li><a href="logout/"><i class="fa fa-power-off"></i> <?php echo lang("btn_logout"); ?></a></li>
                </ul>
               
            </div>
        </div>
        <ul class="nav" id="side-menu">
            <li class="sidebar-search hidden-sm hidden-md hidden-lg">
                <!-- input-group -->
                <form class="form form-material">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="<?php echo lang('search'); ?>..." style="border-bottom: 1px solid #<?php echo substr($this->session->userdata('userdata')["theme_color"], 1) ?>;">
                      <div class="input-group-append">
                        <button class="btn btn-outline-primary-custom" type="button"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                </form>
                <!-- /input-group -->
            </li>
            <li class="nav-small-cap m-t-10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo lang("main_menu"); ?></li>
            <li> <a href="<?php echo base_url('dashboard'); ?>" class="waves-effect"> <i class="fa fa-tachometer"></i> <span class="hide-menu"> <b><?php echo lang("lbl_dashboard") ?></b>  </span> </a></li>

            <?php

            $ci = & get_instance();
            $arr = $ci->session->userdata("userdata")['persissions'];
            $array = json_decode($arr);
            if (isset($array)) {
                $cls_lvl = $cls = $sec = $sub = $sg = $at = $per = 0;
                foreach ($array as $key => $value) {
                    if (in_array('classes', array($value->permission)) && $value->val == 'true') {
                        $cls_lvl = 1;
                    }
                    if (in_array('classes', array($value->permission)) && $value->val == 'true') {
                        $cls = 1;
                    }
                    if (in_array('sections', array($value->permission)) && $value->val == 'true') {
                        $sec = 1;
                    }
                    if (in_array('subjects', array($value->permission)) && $value->val == 'true') {
                        $sub = 1;
                    }
                    if (in_array('subject-groups', array($value->permission)) && $value->val == 'true') {
                        $sg = 1;
                    }
                    if (in_array('assign-teachers', array($value->permission)) && $value->val == 'true') {
                        $at = 1;
                    }
                    if (in_array('periods', array($value->permission)) && $value->val == 'true') {
                        $per = 1;
                    }
                }
            }
            ?>

            <?php if(($role_id === '4') && ($cls == '1' || $sec == '1' || $sub == '1' || $sg == '1' || $at == '1'|| $per == '1')) { ?>
                <li> <a href="#" class="waves-effect"> <i class="fa fa-gear"></i> <span class="hide-menu"> <b><?php echo lang("settings") ?></b><span class="fa arrow"></span>  </span> </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo site_url('settings/academic') ?>"><?php echo lang("menu_academic_settings"); ?></a> </li>
                    </ul>
                </li>
            <?php } ?>

            <?php if ($role_id === '1') { ?> 
                <li> <a href="#" class="waves-effect"> <i class="fa fa-gear"></i> <span class="hide-menu"> <b><?php echo lang("settings") ?></b><span class="fa arrow"></span>  </span> </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo site_url('settings/general') ?>"> <?php echo lang("general_setting"); ?></a> </li>
                        <li> <a href="<?php echo site_url('settings/hr') ?>"><?php echo lang("menu_hr_setting"); ?></a> </li>
                        <li> <a href="<?php echo site_url('settings/academic') ?>"><?php echo lang("menu_academic_settings"); ?></a> </li>
                        <li> <a href="<?php echo site_url('settings/exam') ?>"><?php echo lang("menu_examination_settings"); ?></a> </li>
                        <!--ism id 108-->
                        <?php if( $UserData["sh_id"] != 108) : ?>
                        <li> <a href="<?php echo site_url('fee/show') ?>"><?php echo lang("crumb_fee_settings"); ?></a> </li>
                    <?php endif; ?>
                        <li> <a href="<?php echo base_url('import/index'); ?>"><?php echo lang("import_students"); ?></a> </li>
                        <li> <a href="<?php echo base_url('promotion/index'); ?>"><?php echo lang("lbl_promotion"); ?></a> </li>
                        <!-- <li> <a href="<?php echo base_url('settings/page_settings2'); ?>"><?php echo lang("lbl_page_settings"); ?></a> </li> -->
                        <li> <a href="<?php echo base_url('settings/new_page_settings'); ?>"><?php echo lang("new_page_settings"); ?></a> </li>
                        <!-- added by sheraz -->
                        <!-- <li> <a href="<?php echo base_url('settings/user_management'); ?>"><?php echo lang("lbl_user_management"); ?> <span class="badge badge-pill pull-right badge-warning">New</span></a> </li> -->
                        <li><a href="<?php echo site_url('settings/online_admissions');?>"><?php echo lang("online_admissions") ?>  <small class="badge badge-pill pull-right badge-warning"><?php echo lang('new') ?></small> </a></li>
                        <li><a href="<?php echo site_url('settings/google_drive') ?>"> <img src="assets/googledrive_guide/images/gd_logo.png" style="width: 20px;"/> <?php echo lang('gdrive') ?> <small class="badge badge-pill pull-right badge-warning"><?php echo lang('new') ?></small></a> </li>
                                <!-- added by zafar -->
                        <!--<li> <a href="<?php echo base_url('settings/email_settings'); ?>"><?php echo lang("email_setting"); ?></a> </li>-->
                    </ul>
                </li>

                <!-- Employee menus-->   
            <?php } ?>
            <?php  if ($role_id == '2'){?>
                <li> <a href="#" class="waves-effect"> <i class="fa fa-graduation-cap"></i> <span class="hide-menu"> <b><?php echo lang('lbl_chidren_list') ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo site_url('parents/child_list') ?>" ><?php echo lang('lbl_chidren_view_list') ?></a></li>
                    </ul>
                </li>
            <?php } ?>
            <?php
            $ci = & get_instance();
            $arr = $ci->session->userdata("userdata")['persissions'];
            $array = json_decode($arr);
            if (isset($array)) {
                $emp = $add_emp = 0;
                foreach ($array as $key => $value) {
                    if (in_array('employee-all', array($value->permission)) && $value->val == 'true') {
                        $emp = 1;
                    }
                    if (in_array('employee-add', array($value->permission)) && $value->val == 'true') {
                        $add_emp = 1;
                    }
                }
            }
            ?>
            <?php
            if ($role_id == '4' && isset($emp) || isset($add_emp)) {
                if ($emp == '1' || $add_emp == '1') {
                    ?> 
                    <li> <a href="javascript:void(0);" class="waves-effect"> <i class="fa fa-user-secret"></i> <span class="hide-menu"> <b><?php echo lang("menu_employee") ?></b><span class="fa arrow"></span> </span> </a>
                        <ul class="nav nav-second-level"> 
                            <?php if ($emp == '1') { ?>
                                <li> <a href="<?php echo site_url('employee/all') ?>" ><?php echo lang("menu_employee_list"); ?></a></li>
                            <?php } if ($add_emp == '1') { ?>
                                <li> <a href="<?php echo site_url('employee/add') ?>" ><?php echo lang("menu_employee_add"); ?></a></li>
        <?php } ?>
                        </ul>
                    </li>
                <?php }
            } else if ($role_id === '1') {
                ?> 
                <li> <a href="javascript:void(0);" class="waves-effect"> <i class="fa fa-user-secret"></i> <span class="hide-menu"> <b><?php echo lang("menu_employee") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo site_url('employee/all') ?>" ><?php echo lang("menu_employee_list"); ?></a></li>
                        <li> <a href="<?php echo site_url('employee/add') ?>" ><?php echo lang("menu_employee_add"); ?></a></li>
                    </ul>

                </li>
            <?php } ?>

            <!-- Students menus-->
            <?php
            if (isset($array)) {
                $show = $add_std = $std_shift = 0;
                foreach ($array as $key => $value) {
                    if (in_array('students-show', array($value->permission)) && $value->val == 'true') {
                        $show = 1;
                    }
                    if (in_array('students-add', array($value->permission)) && $value->val == 'true') {
                        $add_std = 1;
                    }
                    if (in_array('students-shift', array($value->permission)) && $value->val == 'true') {
                        $std_shift = 1;
                    }
                }
            }
            ?>
            <?php
            if ($role_id == '4' && isset($show) || isset($add_std)) {
                if ($show == '1' || $add_std == '1' || $std_shift == '1') {
                    ?>
                    <li>
                        <a href="javascript:void(0);" class="waves-effect"> <i class="fa fa-users"></i> <span class="hide-menu"> <b><?php echo lang("menu_students") ?></b><span class="fa arrow"></span> </span> </a>
                        <ul class="nav nav-second-level">
                            <?php if ($show == '1') { ?>
                                <li> <a href="<?php echo site_url('students/show') ?>" ><?php echo lang("menu_view_all"); ?></a></li>
                            <?php } if ($add_std == '1') { ?>
                                <li> <a href="<?php echo site_url('students/add') ?>" ><?php echo lang("menu_admission"); ?></a></li>
        <?php } if ($std_shift == '1') { ?>
                                <li> <a href="<?php echo site_url('students/shift') ?>" ><?php echo lang("shift_students"); ?></a></li>
                    <?php } ?>
                        </ul>
                    </li>
    <?php }
    } else if ($role_id == '1') {
        ?>
                    <li>
                        <a href="javascript:void(0);" class="waves-effect"> <i class="fa fa-users"></i> <span class="hide-menu"> <b><?php echo lang("menu_students") ?></b><span class="fa arrow"></span> </span> </a>
                        <ul class="nav nav-second-level">
                            <li> <a href="<?php echo site_url('students/show') ?>" ><?php echo lang("menu_view_all"); ?></a></li>
                            <li> <a href="<?php echo site_url('students/add') ?>" ><?php echo lang("menu_admission"); ?></a></li>
                            <li> <a href="<?php echo site_url('students/shift') ?>" ><?php echo lang("shift_students"); ?></a></li>
                            <li> <a href="<?php echo site_url('students/online_admissions') ?>" ><?php echo lang("online_admissions"); ?></a></li>
                            
                        </ul>
                    </li>
    <?php } ?>




            <!-- Parent menus-->   
            <?php
            if (isset($array)) {
                $p = $add_p = 0;
                foreach ($array as $key => $value) {
                    if (in_array('parents-all', array($value->permission)) && $value->val == 'true') {
                        $p = 1;
                    }
                    if (in_array('parents-add', array($value->permission)) && $value->val == 'true') {
                        $add_p = 1;
                    }
                }
            }
            ?>     
                    <?php
                    if ($role_id == '4' && isset($p) || isset($add_p)) {
                        if ($p == '1' || $add_p == '1') {
                            ?>
                    <li> <a href="javascript:void(0);" class="waves-effect"> <i class="fa fa-user"></i> <span class="hide-menu"> <b><?php echo lang('menu_parent') ?><?php echo lang("menu_parents") ?></b><span class="fa arrow"></span> </span> </a>
                        <ul class="nav nav-second-level">
        <?php if ($p == '1') { ?>
                                <li> <a href="<?php echo site_url('parents/all') ?>" ><?php echo lang("menu_view_all"); ?></a></li>
                    <?php } if ($add_p == '1') { ?>
                                <li> <a href="<?php echo site_url('parents/add') ?>" ><?php echo lang("add_parent"); ?><?php echo lang("menu_parents_list"); ?></a></li>
        <?php } ?>
                        </ul>
                    </li>
    <?php }
} else if ($role_id == '1') {
    ?>
                <li> <a href="javascript:void(0);" class="waves-effect"> <i class="fa fa-user"></i> <span class="hide-menu"> <b><?php echo lang('menu_parent') ?><?php echo lang("menu_parents") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo site_url('parents/all') ?>" ><?php echo lang("menu_view_all"); ?></a></li>
                        <li> <a href="<?php echo site_url('parents/add') ?>" ><?php echo lang("add_parent"); ?><?php echo lang("menu_parents_list"); ?></a></li>
                    </ul>
                </li>
            <?php } ?>
            <!-- Attendance menus-->   
            <?php
            if (isset($array)) {
                $att_std =  $std_rpt = $att_emp = $emp_rpt = $timeTable = $monitoring = 0;
                foreach ($array as $key => $value) {
                    if (in_array('attendance-show', array($value->permission)) && $value->val == 'true') {
                        $att_std = 1;
                    }
                    if (in_array('attendance-report', array($value->permission)) && $value->val == 'true') {
                        $std_rpt = 1;
                    }
                    if (in_array('attendance-employee', array($value->permission)) && $value->val == 'true') {
                        $att_emp = 1;
                    }
                    if (in_array('attendance-emp_report', array($value->permission)) && $value->val == 'true') {
                        $emp_rpt = 1;
                    }if (in_array('timetable-show', array($value->permission)) && $value->val == 'true') {
                        $timeTable = 1;
                    }if (in_array('monitoring-index', array($value->permission)) && $value->val == 'true') {
                        $monitoring = 1;
                    }
                }
            }
            ?> 
                <?php
                    if ($role_id == '4' && isset($att_std) || isset($std_rpt) || isset($att_emp) || isset($emp_rpt)) {
                        if ($att_std == '1' || $std_rpt == '1' || $att_emp == '1' || $emp_rpt == '1') {
                            ?>
                    <li> <a href="#" class="waves-effect"> <i class="fa fa fa-signal"></i> <span class="hide-menu"> <b><?php echo lang("lbl_attendance") ?></b><span class="fa arrow"></span> </span> </a>
                        <ul class="nav nav-second-level">
                            <?php if ($att_std == '1') { ?>
                                <li> <a href="<?php echo site_url('attendance/show') ?>" ><?php echo lang("lbl_student_attendance"); ?></a></li>
                            <?php } if ($std_rpt == '1') { ?>
                                <li> <a href="<?php echo site_url('attendance/report') ?>" ><?php echo lang("lbl_attendance_report"); ?></a></li>
                            <?php } if ($att_emp == '1') { ?>
                                <li> <a href="<?php echo site_url('attendance/employee') ?>" ><?php echo lang("lbl_employee_attendance"); ?></a></li>
                            <?php } if ($emp_rpt == '1') { ?>
                                <li> <a href="<?php echo site_url('attendance/emp_report') ?>" ><?php echo lang("lbl_employee_report"); ?></a></li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php }
                } else if ($role_id == '1') { ?>
                <li> <a href="#" class="waves-effect"> <i class="fa fa fa-signal"></i> <span class="hide-menu"> <b><?php echo lang("lbl_attendance") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo site_url('attendance/show') ?>" ><?php echo lang("lbl_student_attendance"); ?></a></li>
                        <li> <a href="<?php echo site_url('attendance/report') ?>" ><?php echo lang("lbl_attendance_report"); ?></a></li>
                        <li> <a href="<?php echo site_url('attendance/employee') ?>" ><?php echo lang("lbl_employee_attendance"); ?></a></li>
                        <li> <a href="<?php echo site_url('attendance/emp_report') ?>" ><?php echo lang("lbl_employee_report"); ?></a></li>

                    </ul>
                </li>
            <?php } else if ($role_id == '2'){ ?>
                <li> <a href="#" class="waves-effect"> <i class="fa fa fa-signal"></i> <span class="hide-menu"> <b><?php echo lang("lbl_attendance") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">
                        
                        <li> <a href="<?php echo site_url('attendance/parent_report') ?>" ><?php echo lang("lbl_attendance_report"); ?></a></li>
                       

                    </ul>
                </li>
                <?php } else if ($role_id == '3'){ ?>
                <li> <a href="#" class="waves-effect"> <i class="fa fa fa-signal"></i> <span class="hide-menu"> <b><?php echo lang("lbl_attendance") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">
                        
                        <li> <a href="<?php echo site_url('attendance/student_report') ?>" ><?php echo lang("lbl_attendance_report"); ?></a></li>
                       
                    </ul>
                </li>
            <?php } ?>
            <!-- time Table menus-->     
            <!-- <?php
            if (isset($array)) {
                $timeTable = 0;
                $assignTeacher = 0;
                foreach ($array as $key => $value) {
                    if (in_array('timetable-show', array($value->permission)) && $value->val == 'true') {
                        $timeTable = 1;
                    }
                    if (in_array('assign-teacher', array($value->permission)) && $value->val == 'true') {
                        $assignTeacher = 1;
                    }
                }
            }
            ?> -->
            <?php
            if ($role_id == '4' && (isset($timeTable)) || isset($monitoring)) {
                
                    ?>                   
                    <li> <a href="#" class="waves-effect"> <i class="fa fa-graduation-cap"></i> <span class="hide-menu"> <b><?php echo lang("lbl_academics") ?></b><span class="fa arrow"></span> </span> </a>
                        <ul class="nav nav-second-level">
                            <?php if ($timeTable == '1') { ?>
                                <li> <a href="<?php echo site_url('timetable/show') ?>" ><?php echo lang("lbl_timetable"); ?></a></li>
                            <?php } if ($monitoring == '1') { ?>    
                                <li> <a href="<?php echo site_url('monitoring/index') ?>" ><?php echo lang("lbl_academic_monitoring"); ?></a></li>
                            <?php }  ?>
                        </ul>
                    </li>
                    <?php
                } else if ($role_id == '1') {
                    ?>
                <li> <a href="#" class="waves-effect"> <i class="fa fa-graduation-cap"></i> <span class="hide-menu"> <b><?php echo lang("lbl_academics") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo site_url('timetable/show') ?>" ><?php echo lang("lbl_timetable"); ?></a></li>
                        <li> <a href="<?php echo site_url('monitoring/index') ?>" ><?php echo lang("lbl_academic_monitoring"); ?></a></li>
                    </ul>
                </li>
           <?php } else if ($role_id == '2'){?>
                <li> <a href="#" class="waves-effect"> <i class="fa fa-graduation-cap"></i> <span class="hide-menu"> <b><?php echo lang("lbl_academics") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo site_url('timetable/showTimeTable_parent') ?>" ><?php echo lang("lbl_timetable"); ?></a></li>
                    </ul>
                </li>
                <?php } else if ($role_id == '3'){?>
                <li> <a href="#" class="waves-effect"> <i class="fa fa-graduation-cap"></i> <span class="hide-menu"> <b><?php echo lang("lbl_academics") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo site_url('timetable/showTimeTable_student') ?>" ><?php echo lang("lbl_timetable"); ?></a></li>
                    </ul>
                </li>
            <?php } ?>
            <!-- Study Material menus-->  
            <?php
            if (isset($array)) {
                $upload =  $download = $shop = $std_fee = $statistics = $messageEmployee = $messageParent = $messageStudent = 0;
                foreach ($array as $key => $value) {
                    if (in_array('study_material-upload', array($value->permission)) && $value->val == 'true') {
                        $upload = 1;
                    }
                    if (in_array('study_material-download', array($value->permission)) && $value->val == 'true') {
                        $download = 1;
                    }
                    if (in_array('study_material-book_shop', array($value->permission)) && $value->val == 'true') {
                        $shop = 1;
                    }if (in_array('fee-collection', array($value->permission)) && $value->val == 'true') {
                        $std_fee = 1;
                    }
                    if (in_array('fee-statistics', array($value->permission)) && $value->val == 'true') {
                        $statistics = 1;
                    }
                    if (in_array('messages-employee', array($value->permission)) && $value->val == 'true') {
                        $messageEmployee = 1;
                    }
                    if (in_array('messages-parent', array($value->permission)) && $value->val == 'true') {
                        $messageParent = 1;
                    }
                    if (in_array('messages-student', array($value->permission)) && $value->val == 'true') {
                        $messageStudent = 1;
                    }
                }
            }
            ?>

        <?php if($role_id == '1'){ ?>
                <li> <a href="#" class="waves-effect"> <i class="fa fa-envelope"></i> <span class="hide-menu"> <b><?php echo lang("crumb_messages") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo site_url('messages/show') ?>" ><?php echo lang("crumb_all_messages"); ?></a></li>
                    </ul>
                </li>
            <?php }else if($role_id == '4' && ($messageEmployee == '1' || $messageParent == '1' || $messageStudent == '1')){?>
                <li> 
                    <a href="#" class="waves-effect"> <i class="fa fa-envelope"></i> <span class="hide-menu"> <b><?php echo lang("crumb_messages") ?></b><span class="fa arrow"></span> </span></a>
                    <ul class="nav nav-second-level">
                        <li> 
                            <a href="<?php echo site_url('messages/show') ?>" ><?php echo lang("crumb_all_messages"); ?></a>
                        </li>
                    </ul>
                </li>
             <?php } else {?>
                    <li> <a href="#" class="waves-effect"> <i class="fa fa-envelope"></i> <span class="hide-menu"> <b><?php echo lang("crumb_messages") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo site_url('messages/show') ?>" ><?php echo lang("crumb_all_messages"); ?></a></li>
                    </ul>
                </li>
            <?php } ?>
                
<?php
if ($role_id == '4' && (isset($upload) || isset($download) || isset($shop))) {
    
    if ($upload == '1' || $download == '1') {
        ?>
                    <li> <a href="javascipt:void(0)" class="waves-effect"> <i class="fa fa-download"></i> <span class="hide-menu"> <b><?php echo lang("study_material") ?></b><span class="fa arrow"></span> </span> </a>
                        <ul class="nav nav-second-level">
        <?php if ($upload == '1') { ?>
                                <li> <a href="<?php echo site_url('study_material/upload') ?>" ><?php echo lang("lbl_upload"); ?></a></li>
                    <?php } if ($download == '1') { ?>
                                <li> <a href="<?php echo site_url('study_material/download') ?>" ><?php echo lang("lbl_download"); ?></a></li>                                   
                    <?php } ?>
                                <li> <a href="<?php echo site_url('study_material/class_work') ?>" ><?php echo lang("class_work"); ?></a></li>
                        </ul>
                    </li>
    <?php } ?>
    <?php if ($shop == '1') { ?>
                    <li> <a href="javascipt:void(0)" class="waves-effect"> <i class="fa fa-book"></i> <span class="hide-menu"> <b><?php echo lang('lbl_book_shop'); ?></b><span class="fa arrow"></span> </span> </a>
                        <ul class="nav nav-second-level">
                            <li> <a href="<?php echo site_url('bookshop') ?>" ><?php echo lang('lbl_book_shop'); ?></a></li>
                        </ul>
                    </li>
    <?php } ?>

<?php } else if($role_id == '1') { 
// echo 'in 1 '.$role_id;
?>
                <li> <a href="javascipt:void(0)" class="waves-effect"> <i class="fa fa-download"></i> <span class="hide-menu"> <b><?php echo lang("study_material") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo site_url('study_material/upload') ?>" ><?php echo lang("lbl_upload"); ?></a></li>
                        <li> <a href="<?php echo site_url('study_material/download') ?>" ><?php echo lang("lbl_download"); ?></a></li>
                        <li> <a href="<?php echo site_url('study_material/class_work') ?>" ><?php echo lang("class_work"); ?></a></li>
                    </ul>
                </li>
                <li> <a href="javascipt:void(0)" class="waves-effect"> <i class="fa fa-book"></i> <span class="hide-menu"> <b><?php echo lang('lbl_book_shop'); ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo site_url('bookshop') ?>" ><?php echo lang('lbl_book_shop'); ?></a></li>
                    </ul>
                </li>
        <!-- <li> <a href="<?php echo site_url('study_material/book_shop') ?>" ><?php echo lang('lbl_book_shop'); ?></a></li> -->
<?php }  else if($role_id == '2' || $role_id == 2){ 

?>
       <li> <a href="javascipt:void(0)" class="waves-effect"> <i class="fa fa-download"></i> <span class="hide-menu"> <b><?php echo lang("study_material") ?></b><span class="fa arrow"></span> </span> </a>
            <ul class="nav nav-second-level">
               
                <li> <a href="<?php echo site_url('study_material/parent_download') ?>" ><?php echo lang("lbl_download"); ?></a></li>
                <li> <a href="<?php echo site_url('study_material/parent_class_work') ?>" ><?php echo lang("class_work"); ?></a></li>
            </ul>
        </li>
        <!-- bookshop module for students and parents added by sheraz #BSI -->
        <li> <a href="javascipt:void(0)" class="waves-effect"> <i class="fa fa-book"></i> <span class="hide-menu"> <b><?php echo lang('lbl_book_shop'); ?></b><span class="fa arrow"></span> </span> </a>
            <ul class="nav nav-second-level">
                <li> <a href="<?php echo site_url('study_material/book_shop_for_parent') ?>" ><?php echo lang('lbl_book_shop'); ?></a></li>
            </ul>
        </li>

<?php } else if($role_id == '3'){ ?>
       <li> <a href="javascipt:void(0)" class="waves-effect"> <i class="fa fa-download"></i> <span class="hide-menu"> <b><?php echo lang("study_material") ?></b><span class="fa arrow"></span> </span> </a>
            <ul class="nav nav-second-level">
               
                <li> <a href="<?php echo site_url('study_material/student_download') ?>" ><?php echo lang("lbl_download"); ?></a></li>
                <li> <a href="<?php echo site_url('study_material/student_class_activities') ?>" ><?php echo lang("class_activities"); ?></a></li>
            </ul>
        </li>
        <!-- bookshop module for students and parents added by sheraz #BSI -->
        <li> <a href="javascipt:void(0)" class="waves-effect"> <i class="fa fa-book"></i> <span class="hide-menu"> <b><?php echo lang('lbl_book_shop'); ?></b><span class="fa arrow"></span> </span> </a>
            <ul class="nav nav-second-level">
                <li> <a href="<?php echo site_url('study_material/book_shop_for_student') ?>" ><?php echo lang('lbl_book_shop'); ?></a></li>
            </ul>
        </li>

<?php } ?>

            <!-- Student Fee menus-->    
            <!-- <?php
            if (isset($array)) {
                $std_fee = 0;
                $statistics = 0;
                foreach ($array as $key => $value) {
                    if (in_array('fee-collection', array($value->permission)) && $value->val == 'true') {
                        $std_fee = 1;
                    }
                    if (in_array('fee-statistics', array($value->permission)) && $value->val == 'true') {
                        $statistics = 1;
                    }
                }
            }
            ?> -->
<?php if ($role_id == '4' && isset($std_fee) && $UserData["sh_id"] != 108) { ?>
    <?php if ($std_fee == '1' || $statistics == '1') { ?> 
                    <li> <a href="javascipt:void(0)" class="waves-effect"> <i class="fa fa-dollar"></i> <span class="hide-menu"> <b><?php echo lang("crumb_fee") ?></b><span class="fa arrow"></span> </span> </a>
                        <ul class="nav nav-second-level">
                           <?php if($std_fee == '1'){?>
                                <li> <a href="<?php echo site_url('fee/collection') ?>" ><?php echo lang("crumb_fee_collection") ?></a></li>
                            <?php }?>
                            <?php if($statistics == '1'){?>
                                <li> <a href="<?php echo site_url('fee/statistics') ?>" ><?php echo lang('fee_statistics');?></a></li>
                            <?php }?>
                        </ul>
                    </li>
                <?php }
            } else if ($role_id == '1' && $UserData["sh_id"] != 108) {
                ?>
                <li> <a href="javascipt:void(0)" class="waves-effect"> <i class="fa fa-dollar"></i> <span class="hide-menu"> <b><?php echo lang("crumb_fee") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo site_url('fee/collection') ?>" ><?php echo lang("crumb_fee_collection") ?></a></li>
                        <li> <a href="<?php echo site_url('fee/statistics') ?>" ><?php echo lang('fee_statistics');?></a></li>
                    </ul>
                </li>
            <?php } else if($role_id == '2' &&  $UserData["sh_id"] != 52){ ?>
                <li> <a href="javascipt:void(0)" class="waves-effect"> <i class="fa fa-dollar"></i> <span class="hide-menu"> <b><?php echo lang("crumb_fee") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo site_url('fee/collection_parent') ?>" ><?php echo lang("crumb_fee_collection") ?></a></li>
                        
                    </ul>
                </li>
                
            <?php } ?>
            <!-- Forms menus-->    
            <?php
            if (isset($array)) {
                $all = $crt = $cat = $reports = $syllabus = 0;
                foreach ($array as $key => $value) {
                    if (in_array('forms-all', array($value->permission)) && $value->val == 'true') {
                        $all = 1;
                    }if (in_array('forms-create', array($value->permission)) && $value->val == 'true') {
                        $crt = 1;
                    }if (in_array('forms-category_create', array($value->permission)) && $value->val == 'true') {
                        $cat = 1;
                    }if (in_array('reports-all', array($value->permission)) && $value->val == 'true') {
                        $reports = 1;
                    }if (in_array('syllabus-add', array($value->permission)) && $value->val == 'true') {
                        $syllabus = 1;
                    }
                }
            }
            ?>
<?php if ($role_id == '4') {

    if ($all == '1' || $crt == '1' || $cat == '1') {
        ?>

                    <li> <a href="#" class="waves-effect" > <i class="fa fa-file"></i> <span class="hide-menu"> <b> <?php echo lang("lbl_forms") ?></b><span class="fa arrow"></span> </span> </a>
                        <ul class="nav nav-second-level">

        <?php if ($all == '1') { ?>  

                                <li> <a href="<?php echo site_url('forms/all'); ?>" ><?php echo lang("menu_view_all"); ?></a></li>

        <?php } if ($crt == '1') { ?>

                                <li> <a href="<?php echo site_url('forms/create'); ?>" ><?php echo lang("lbl_new_form"); ?></a></li>

        <?php }if ($cat == '1') { ?> 

                                <li> <a href="<?php echo site_url('forms/category_create'); ?>" ><?php echo lang("lbl_form_categories"); ?></a></li>

                    <?php } ?> 

                        </ul>
                    </li>
                <?php }
            } else if ($role_id == '1') { ?>
                <li> <a href="#" class="waves-effect" > <i class="fa fa-file"></i> <span class="hide-menu"> <b> <?php echo lang("lbl_forms") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">

                        <li> <a href="<?php echo site_url('forms/all'); ?>" ><?php echo lang("menu_view_all"); ?></a></li>
                        <li> <a href="<?php echo site_url('forms/create'); ?>" ><?php echo lang("lbl_new_form"); ?></a></li>
                        <li> <a href="<?php echo site_url('forms/category_create'); ?>" ><?php echo lang("lbl_form_categories"); ?></a></li>

                    </ul>
                </li>
<?php } ?>


<?php if ($role_id == '4' && $reports == '1') { ?>

                <li> <a href="#" class="waves-effect" > <i class="fa fa-file-text"></i> <span class="hide-menu"> <b> <?php echo lang("reports_all") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">

                        <li> <a href="<?php echo site_url('reports/all'); ?>" ><?php echo lang("reports_all"); ?></a></li>

                    </ul>
                </li>

            <?php } else if ($role_id == '1') { ?>

                <li> <a href="#" class="waves-effect" > <i class="fa fa-file-text"></i> <span class="hide-menu"> <b> <?php echo lang("reports_all") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">

                        <li> <a href="<?php echo site_url('reports/all'); ?>" ><?php echo lang("reports_all"); ?></a></li>

                    </ul>
                </li>
            <?php } ?>

            <?php
            if (isset($array)) {

                $exams = $publish_exam = $marks = $sheet = $evalSettings = $evalStudent = $evalReport = $viewtrash = $trashrecover = $trashdelete = $application =0;
                foreach ($array as $key => $value) {
                    if (in_array('examination-add', array($value->permission)) && $value->val == 'true') {
                        $exams = '1';
                    }if (in_array('examination-publish_result', array($value->permission)) && $value->val == 'true') {
                        $publish_exam = '1';
                    }if (in_array('examination-marks', array($value->permission)) && $value->val == 'true') {
                        $marks = '1';
                    }if (in_array('examination-majorSheet', array($value->permission)) && $value->val == 'true') {
                        $sheet = '1';
                    }
                     if (in_array('settings-evaluation', array($value->permission)) && $value->val == 'true') {
                        $evalSettings = '1';
                    }if (in_array('students-evaluate', array($value->permission)) && $value->val == 'true') {
                        $evalStudent = '1';
                    }if (in_array('students-report_card', array($value->permission)) && $value->val == 'true') {
                        $evalReport = '1';
                    }if (in_array('trash-index', array($value->permission)) && $value->val == 'true') {
                        $viewtrash = '1';
                    }if (in_array('trash-recover', array($value->permission)) && $value->val == 'true') {
                        $trashrecover = 1;
                    }
                    if (in_array('trash-delete', array($value->permission)) && $value->val == 'true') {
                        $trashdelete = 1;
                    }
                    if (in_array('applications-all', array($value->permission)) && $value->val == 'true') {
                        $application = 1;
                    }
                }
               
            }
            ?>


            <?php if ($role_id == '4' && ($exams == '1' || $marks == '1' || $sheet == '1')) { ?>

                <li>
                    <a href="#" class="waves-effect" > <i class="fa fa-check"></i> <span class="hide-menu"> <b> <?php echo lang("lbl_examination") ?></b><span class="fa arrow"></span> </span> 
                    </a>
                    <ul class="nav nav-second-level">
                    <?php if ($exams == '1') { ?>
                            <li> <a href="<?php echo site_url('examination/add'); ?>" ><?php echo lang("lbl_manage_exam"); ?></a></li>
                    <?php } ?>
                    <?php if ($publish_exam == '1') { ?>
                            <li> <a href="<?php echo site_url('examination/publish_result'); ?>" ><?php echo lang("lbl_publish_result"); ?></a></li>
                    <?php } ?>
                    <?php if ($marks == '1') { ?>
                            <li> <a href="<?php echo site_url('examination/marks'); ?>" ><?php echo lang("lbl_mark_exam"); ?></a></li>
                    <?php } ?>
                    <?php if ($sheet == '1') { ?>
                            <li> <a href="<?php echo site_url('examination/majorSheet'); ?>" ><?php echo lang("lbl_major_sheet"); ?></a></li>
                    <?php } ?>
                    </ul>
                </li>
            <?php } else if ($role_id == '1') { ?>
                <li>
                    <a href="#" class="waves-effect" > <i class="fa fa-check"></i> <span class="hide-menu"> <b> <?php echo lang("lbl_examination") ?></b><span class="fa arrow"></span> </span> 
                    </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo site_url('examination/add'); ?>" ><?php echo lang("lbl_manage_exam"); ?></a></li>
                        <li> <a href="<?php echo site_url('examination/publish_result'); ?>" ><?php echo lang("lbl_publish_result"); ?></a></li>   
                        <li> <a href="<?php echo site_url('examination/marks'); ?>" ><?php echo lang("lbl_mark_exam"); ?></a></li>
                        <li> <a href="<?php echo site_url('examination/majorSheet'); ?>" ><?php echo lang("lbl_major_sheet"); ?></a></li>
                    </ul>
                </li>
<?php } else if($role_id == '2'){ ?>
        <li>
                    <a href="#" class="waves-effect" > <i class="fa fa-check"></i> <span class="hide-menu"> <b> <?php echo lang("lbl_examination") ?></b><span class="fa arrow"></span> </span> 
                    </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo site_url('examination/majorSheetForParent'); ?>" ><?php echo lang("result_card"); ?></a></li>
                    </ul>
                </li>
    <?php } if ($role_id == '4' && ($evalSettings == '1' || $evalStudent == '1' || $evalReport == '1')){?>
                <li>
                    <a href="#" class="waves-effect" > <i class="fa fa-edit"></i> <span class="hide-menu"> <b><?php echo lang('student_evaluation'); ?></b><span class="fa arrow"></span> </span> 
                    </a>
                    <ul class="nav nav-second-level">
                    <?php if($evalSettings == '1'){?>
                        <li> <a href="<?php echo base_url('settings/evaluation'); ?>"><?php echo lang('evaluation_settings'); ?></a> </li>
                    <?php } if($evalStudent == '1'){?>
                        <li> <a href="<?php echo site_url('students/evaluate') ?>" ><?php echo lang('evaluate_students'); ?></a></li>
                    <?php } if($evalReport == '1'){?>
                        <li> <a href="<?php echo site_url('students/report_card') ?>" ><?php echo lang('evaluation_report'); ?></a></li>
                    <?php }?>
                    </ul>
                </li>
    <?php } else if ($role_id == '1'){?>
                <li>
                    <a href="#" class="waves-effect" > <i class="fa fa-edit"></i> <span class="hide-menu"> <b><?php echo lang('student_evaluation'); ?></b><span class="fa arrow"></span> </span> 
                    </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo base_url('settings/evaluation'); ?>"><?php echo lang('evaluation_settings'); ?></a> </li>
                        <li> <a href="<?php echo site_url('students/evaluate') ?>" ><?php echo lang('evaluate_students'); ?></a></li>
                        <li> <a href="<?php echo site_url('students/report_card') ?>" ><?php echo lang('evaluation_report'); ?></a></li>
                    </ul>
                </li>
    <?php } else if($role_id == '2') { ?> 

         <li>
                    <a href="#" class="waves-effect" > <i class="fa fa-edit"></i> <span class="hide-menu"> <b><?php echo lang('lbl_chidren_evaluation'); ?></b><span class="fa arrow"></span> </span> 
                    </a>
                    <ul class="nav nav-second-level">
                       
                        <li> <a href="<?php echo site_url('students/report_cardForParent') ?>" ><?php echo lang('lbl_chidren__evaluation_report'); ?></a></li>
                    </ul>
                </li>

<?php } if ($role_id == '4' && $syllabus == '1') { ?>

                <li> <a href="#" class="waves-effect" > <i class="fa fa-clipboard"></i> <span class="hide-menu"> <b> <?php echo lang("lbl_syllabus") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">

                        <li>
                            <a href="<?php echo site_url('syllabus/add'); ?>" ><?php echo lang("lbl_manage_syllabus"); ?>
                                <small class="badge badge-pill pull-right badge-danger" style="font-size: 8px;" data-toggle="tooltip" title="will depreciated. Please don't use it"><?php echo lang('lbl_depreciate') ?></small>
                            </a>
                        </li>
                        <li> 
                            <a href="<?php echo site_url('study_plan/add_study_plan'); ?>" ><?php echo lang('lbl_manage_syllabus');?>
                            <small class="badge badge-pill pull-right badge-success" data-toggle="tooltip" title="Use this New one Available... "><?php echo lang('new') ?></small>
                            </a>
                        </li>

                    </ul>
                </li>

<?php } else if ($role_id == '1') { ?>

                <li> <a href="#" class="waves-effect" > <i class="fa fa-clipboard"></i> <span class="hide-menu"> <b> <?php echo lang("lbl_syllabus") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">
                        <li> 
                            <a href="<?php echo site_url('syllabus/add'); ?>" ><?php echo lang("lbl_manage_syllabus"); ?>
                                <small class="badge badge-pill pull-right badge-danger"  style="font-size: 8px;" data-toggle="tooltip" title="will depreciated. Please don't use it"><?php echo lang('lbl_depreciate') ?></small>
                            </a>
                        </li>
                        <li> 
                            <a href="<?php echo site_url('study_plan/add_study_plan'); ?>" ><?php echo lang('lbl_manage_syllabus');?> <small class="badge badge-pill pull-right badge-success" data-toggle="tooltip" title="Use this New one Available... "><?php echo lang('new') ?></small>
                            
                            </a>
                        </li>

                    </ul>
                </li>
<?php } else if ($role_id == '2'){ ?>
        <li> <a href="#" class="waves-effect" > <i class="fa fa-clipboard"></i> <span class="hide-menu"> <b> <?php echo lang("lbl_syllabus") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">

                        <li> <a href="<?php echo site_url('syllabus/viewStudyPlanForParent'); ?>" ><?php echo lang("lbl_manage_syllabus"); ?></a></li>

                    </ul>
                </li>

<?php } ?>
        
<?php if ($role_id == '4'  && $application == '1') { ?>

                <li> <a href="#" class="waves-effect" > <i class="fa fa-file-text-o"></i> <span class="hide-menu"> <b> <?php echo lang("applications") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">

                        <li> <a href="<?php echo site_url('applications/all'); ?>" ><?php echo lang("all_applications"); ?></a></li>

                    </ul>
                </li>

<?php } else if ($role_id == '1') { ?>

                <li> <a href="#" class="waves-effect" > <i class="fa fa-file-text-o"></i> <span class="hide-menu"> <b> <?php echo lang("applications") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">

                        <li> <a href="<?php echo site_url('applications/all'); ?>" ><?php echo lang("all_applications"); ?></a></li>

                    </ul>
                </li>
                
                <?php if($role_id == '1' || login_user()->user->role_id == ADMIN_ROLE_ID) { ?>
                    <li> 
                        <a href="<?php echo site_url('trash'); ?>" class="waves-effect"> 
                            <i class="fa fa-trash-o"></i> 
                            <span class="hide-menu"><b><?php echo lang("lbl_trash") ?></b></span>
                        </a>
                    </li>
                <?php } ?>
                 
<?php } ?>
                <?php if($role_id == '4' && ($viewtrash == '1' || $trashrecover == '1' || $trashdelete == '1')) { ?>
                    <li> 
                        <a href="<?php echo site_url('trash'); ?>" class="waves-effect"> 
                            <i class="fa fa-trash-o"></i> 
                            <span class="hide-menu"><b><?php echo lang("lbl_trash") ?></b></span>
                        </a>
                    </li>
                <?php } ?>

            <!-- <?php if(get_acountant_dept_id() == login_user()->user->department_id || $role_id == '1') { ?>
                <li> 
                    <a href="#" class="waves-effect"> <i class="fa fa-dollar"></i> <span class="hide-menu"> <b><?php echo lang("lbl_payroll") ?></b> <span class="badge badge-pill pull-right badge-warning">New</span> <span class="fa arrow"></span>  </span> </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo base_url('payroll/settings') ?>"> <?php echo lang("settings"); ?></a> </li>
                        <li> <a href="<?php echo base_url('payroll') ?>"><?php echo lang("lbl_manage"); ?></a> </li>
                    </ul>
                </li>
            <?php } ?> -->
            <?php
            if (isset($array)) {
                $viewSalary = $paySalary = $deleteSalary = $payrollSettings = $accDashboard = $accCollect  = $accPay  = $accDeposite  = $accDepositeEdit  = $accWithdraw  = $incomeSettings  = $incomeAdd  = $incomeEdit  =  $incomeDelete =  $expSettings  = $expenseAdd  = $expenseEdit  = $expenseDelete  = $virtualAccounts  = $virtualAdd  = $virtualEdit  = $virtualDelete = $viewEmployees = $viewStudents = $viewGuardians = $changeEmpStatus = $changeStdStatus = $changeGuardianStatus = $announcements= 0;
                foreach ($array as $key => $value) {
                    if (in_array('payroll-index', array($value->permission)) && $value->val == 'true') {
                        $viewSalary = '1';
                    }if (in_array('payroll-pay', array($value->permission)) && $value->val == 'true') {
                        $paySalary = '1';
                    }if (in_array('payroll-delete', array($value->permission)) && $value->val == 'true') {
                        $deleteSalary = '1';
                    }if (in_array('payroll-settings', array($value->permission)) && $value->val == 'true') {
                        $payrollSettings = '1';
                    }
                     // account module
                    if (in_array('accounts-dashboard', array($value->permission)) && $value->val == 'true') {
                        $accDashboard = '1';
                    }
                    if (in_array('accounts-collect', array($value->permission)) && $value->val == 'true') {
                        $accCollect = '1';
                    }
                    if (in_array('accounts-pay', array($value->permission)) && $value->val == 'true') {
                        $accPay = '1';
                    }
                    if (in_array('accounts-deposit', array($value->permission)) && $value->val == 'true') {
                        $accDeposite = '1';
                    }
                    if (in_array('accounts-depositEdit', array($value->permission)) && $value->val == 'true') {
                        $accDepositeEdit = '1';
                    }
                    if (in_array('accounts-withdraw', array($value->permission)) && $value->val == 'true') {
                        $accWithdraw = '1';
                    }
                    if (in_array('accounts-income_settings', array($value->permission)) && $value->val == 'true') {
                        $incomeSettings = '1';
                    }
                    if (in_array('accounts-incomeAdd', array($value->permission)) && $value->val == 'true') {
                        $incomeAdd = '1';
                    }
                    if (in_array('accounts-incomeEdit', array($value->permission)) && $value->val == 'true') {
                        $incomeEdit = '1';
                    }
                    if (in_array('accounts-incomeDelete', array($value->permission)) && $value->val == 'true') {
                        $incomeDelete = '1';
                    }
                    if (in_array('accounts-expense_settings', array($value->permission)) && $value->val == 'true') {
                        $expSettings = '1';
                    }
                    if (in_array('accounts-expenseAdd', array($value->permission)) && $value->val == 'true') {
                        $expenseAdd = '1';
                    }
                    if (in_array('accounts-expenseEdit', array($value->permission)) && $value->val == 'true') {
                        $expenseEdit = '1';
                    }
                    if (in_array('accounts-expenseDelete', array($value->permission)) && $value->val == 'true') {
                        $expenseDelete = '1';
                    }
                    if (in_array('accounts-virtual_accounts', array($value->permission)) && $value->val == 'true') {
                        $virtualAccounts = '1';
                    }
                    if (in_array('accounts-virtualAdd', array($value->permission)) && $value->val == 'true') {
                        $virtualAdd = '1';
                    }
                    if (in_array('accounts-virtualEdit', array($value->permission)) && $value->val == 'true') {
                        $virtualEdit = '1';
                    }
                    if (in_array('accounts-virtualDelete', array($value->permission)) && $value->val == 'true') {
                        $virtualDelete = '1';
                    }

                    // user management
                    if (in_array('manage-viewEmployees', array($value->permission)) && $value->val == 'true') {
                        $viewEmployees = '1';
                    }
                    if (in_array('manage-viewStduents', array($value->permission)) && $value->val == 'true') {
                        $viewStudents = '1';
                    }
                    if (in_array('manage-viewGuardians', array($value->permission)) && $value->val == 'true') {
                        $viewGuardians = '1';
                    }
                    if (in_array('manage-changeEmpStatus', array($value->permission)) && $value->val == 'true') {
                        $changeEmpStatus = '1';
                    }
                    if (in_array('manage-changeStdStatus', array($value->permission)) && $value->val == 'true') {
                        $changeStdStatus = '1';
                    }
                    if (in_array('manage-changeGuardianStatus', array($value->permission)) && $value->val == 'true') {
                        $changeGuardianStatus = '1';
                    }
                    if (in_array('announcements-index', array($value->permission)) && $value->val == 'true') {
                        $announcements = '1';
                    }
                }
            }
            ?>
            <?php if ($role_id == '4' && ($viewSalary == '1' || $payrollSettings == '1') && $UserData["sh_id"] != 108) {  ?>
                <li> 
                    <a href="#" class="waves-effect"> <i class="fa fa-dollar"></i> <span class="hide-menu"> <b><?php echo lang("lbl_payroll") ?></b> <span class="badge badge-pill pull-right badge-warning"><?php echo lang('new') ?></span> <span class="fa arrow"></span>  </span> </a>
                    <ul class="nav nav-second-level">
                        <?php if($payrollSettings == '1'){?>
                            <li> <a href="<?php echo base_url('payroll/settings') ?>"> <?php echo lang("settings"); ?></a> </li>
                        <?php } if($viewSalary){?>    
                            <li> <a href="<?php echo base_url('payroll') ?>"><?php echo lang("lbl_manage"); ?></a> </li>
                        <?php }?>
                    </ul>
                </li>
            <?php } else if($role_id == '1' &&  $UserData["sh_id"] != 108) {?>
                <li> 
                    <a href="#" class="waves-effect"> <i class="fa fa-dollar"></i> <span class="hide-menu"> <b><?php echo lang("lbl_payroll") ?></b> <span class="badge badge-pill pull-right badge-warning"><?php echo lang('new') ?></span> <span class="fa arrow"></span>  </span> </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo base_url('payroll/settings') ?>"> <?php echo lang("settings"); ?></a> </li>
                        <li> <a href="<?php echo base_url('payroll') ?>"><?php echo lang("lbl_manage"); ?></a> </li>
                    </ul>
                </li>
            <?php }?>
            <?php //if(($role_id == '1' || get_acountant_dept_id() == login_user()->user->department_id) && $UserData["sh_id"] != 108){ ?>
            <?php if($role_id == '1' && $UserData["sh_id"] != 108){ ?>
            <li> 
                <a href="#" class="waves-effect"> <i class="fa fa-dollar"></i> <span class="hide-menu"> <b><?php echo lang('accounts');?></b> <span class="badge badge-pill pull-right badge-warning"><?php echo lang('new') ?></span> <span class="fa arrow"></span>  </span> </a>
                <ul class="nav nav-second-level">
                    <li> <a href="<?php echo base_url('accounts/dashboard') ?>"><?php echo lang('crumb_dashboard');?></a> </li>
                    <li> <a href="<?php echo base_url('accounts/income_settings') ?>"><?php echo lang('income_settings');?></a> </li>
                    <li> <a href="<?php echo base_url('accounts/expense_settings') ?>"><?php echo lang('expense_settings');?></a> </li>
                    <li> <a href="<?php echo base_url('accounts/virtual_accounts') ?>"><?php echo lang('virtual_accounts');?></a> </li>
                </ul>
            </li>
        <?php } else if($role_id == '4' && $UserData["sh_id"] != 108 && ($accDashboard == '1' || $accCollect  == '1' || $accPay  == '1' || $accDeposite  == '1' || $accDepositeEdit  == '1' || $accWithdraw  == '1' || $incomeSettings  == '1' || $incomeAdd  == '1' || $incomeEdit  == '1' ||  $incomeDelete  == '1' ||  $expSettings  == '1' || $expenseAdd  == '1' || $expenseEdit  == '1' || $expenseDelete  == '1' || $virtualAccounts  == '1' || $virtualAdd  == '1' || $virtualEdit  == '1' || $virtualDelete  == '1') ){ ?>
            <li> 
                <a href="#" class="waves-effect"> <i class="fa fa-dollar"></i> <span class="hide-menu"> <b><?php echo lang('accounts');?></b> <span class="badge badge-pill pull-right badge-warning"><?php echo lang('new') ?></span> <span class="fa arrow"></span>  </span> </a>
                <ul class="nav nav-second-level">
                    <?php if($accDashboard == '1' || $accCollect  == '1' || $accPay  == '1' || $accDeposite  == '1' || $accDepositeEdit  == '1' || $accWithdraw  == '1'){?>
                    <li> <a href="<?php echo base_url('accounts/dashboard') ?>"><?php echo lang('crumb_dashboard');?></a> </li>
                <?php } if($incomeSettings  == '1' || $incomeAdd  == '1' || $incomeEdit  == '1' ||  $incomeDelete  == '1'){?>
                    <li> <a href="<?php echo base_url('accounts/income_settings') ?>"><?php echo lang('income_settings');?></a> </li>
                <?php } if($expSettings  == '1' || $expenseAdd  == '1' || $expenseEdit  == '1' || $expenseDelete  == '1'){?>
                    <li> <a href="<?php echo base_url('accounts/expense_settings') ?>"><?php echo lang('expense_settings');?></a> </li>
                <?php } if($virtualAccounts  == '1' || $virtualAdd  == '1' || $virtualEdit  == '1' || $virtualDelete  == '1'){?>
                    <li> <a href="<?php echo base_url('accounts/virtual_accounts') ?>"><?php echo lang('virtual_accounts');?></a> </li>
                <?php } ?>
                </ul>
            </li>
        <?php } ?>

            <?php if($role_id == '1' || ($role_id == '4' && $announcements == '1')) { ?>
                <li>
                    <a href="javascript:void(0);" class="waves-effect"> <i class="fa fa-bullhorn"></i> <span class="hide-menu"> <b><?php echo lang("menu_announcements") ?></b><span class="fa arrow"></span> </span> </a>
                    <ul class="nav nav-second-level">
                        <li> <a href="<?php echo site_url('announcements') ?>" ><?php echo lang("menu_view_all"); ?></a></li>
                    </ul>
                </li>
            <?php  } ?>
            <?php if ($role_id == '4' && ($viewEmployees == '1' || $viewStudents == '1' || $viewGuardians == '1' || $changeEmpStatus == '1' || $changeStdStatus == '1' || $changeGuardianStatus == '1') ) {  ?>
                <li> 
                    <a href="<?php echo base_url('usermanagement/index'); ?>" class="waves-effect"> <i class="fa fa-users"></i> <span class="hide-menu"> <b><?php echo lang("lbl_user_management") ?></b>  <span class="badge badge-pill pull-right badge-warning"><?php echo lang('new') ?></span> </span></a>
                </li>
            
            <?php }else if($role_id == '1') { ?>
                <li> 
                    <a href="<?php echo base_url('usermanagement/index'); ?>" class="waves-effect"> <i class="fa fa-users"></i> <span class="hide-menu"> <b><?php echo lang("lbl_user_management") ?></b>  <span class="badge badge-pill pull-right badge-warning"><?php echo lang('new') ?></span> </span></a>
                </li>
            <?php } ?>
            <?php if($role_id == '1' || $role_id == '3' || $this->session->userdata("userdata")["department_id"] == get_teacher_dept_id() ) { ?>
                    <li> 
                        <a href="<?php echo site_url('online_classes'); ?>" class="waves-effect"> 
                            <i class="fa fa-video-camera"></i> 
                            <span class="hide-menu"><b><?php echo lang('lbl_online_classes');?></b> <span class="badge badge-pill pull-right badge-warning"><?php echo lang('new') ?></span></span>
                        </a>
                    </li>
                <?php } ?>

                <!-- online examination -->
            <?php if (isset($array)) {
                $OexamSetting = $OexamEdit = $OexamDelete = $addQuestion = $updateQuestion = $publishOexam  = $unpublishOexam  = $OmajorSheet = 0;
                foreach ($array as $key => $value) {
                    if (in_array('online_exams-settings', array($value->permission)) && $value->val == 'true') {
                        $onlineExam = $OexamSetting = '1';
                    }if (in_array('online_exams-settingsEdit', array($value->permission)) && $value->val == 'true') {
                         $onlineExam = $OexamEdit = '1';
                    }if (in_array('online_exams-settingsDelete', array($value->permission)) && $value->val == 'true') {
                         $onlineExam = $OexamDelete = '1';
                    }if (in_array('online_exams-add_question', array($value->permission)) && $value->val == 'true') {
                         $onlineExam = $addQuestion = '1';
                    }if (in_array('online_exams-update_question', array($value->permission)) && $value->val == 'true') {
                         $onlineExam = $updateQuestion = '1';
                    }if (in_array('online_exams-publish_papers', array($value->permission)) && $value->val == 'true') {
                         $onlineExam = $publishOexam = '1';
                    }if (in_array('online_exams-unpublish_papers', array($value->permission)) && $value->val == 'true') {
                         $onlineExam = $unpublishOexam = '1';
                    }if (in_array('online_exams-results', array($value->permission)) && $value->val == 'true') {
                         $onlineExam = $OmajorSheet = '1';
                    }
                } }?>

            <?php if($this->session->userdata("userdata")["department_id"] == get_teacher_dept_id() && ($OexamSetting == '1' || $addQuestion == '1' || $publishOexam == '1' || $unpublishOexam == '1')){?>
                    <li>
                        <a href="javascript:void(0);" class="waves-effect"> <i class="fa fa-check"></i> <span class="hide-menu"> <b><?php echo lang('lbl_online_exam');?></b><span class="badge badge-pill pull-right badge-warning"><?php echo lang('new') ?></span><span class="fa arrow"></span> </span> </a>
                        <ul class="nav nav-second-level">
                        <?php if($OexamSetting == '1'){?>
                            <li> <a href="<?php echo site_url('online_exams/settings') ?>" ><?php echo lang('settings');?></a></li>
                        <?php } if($addQuestion == '1'){?>
                            <li> <a href="<?php echo site_url('online_exams/add_question') ?>" ><?php echo lang('add_questions');?></a></li>
                        <?php } if($publishOexam == '1'){?>
                            <li> <a href="<?php echo site_url('online_exams/publish_papers') ?>" ><?php echo lang('publish_paper');?></a></li>
                        <?php } if($OmajorSheet == '1'){?>
                            <li> <a href="<?php echo site_url('online_exams/results') ?>" ><?php echo lang('mark_sheet');?></a></li>
                        <?php }?>
                            <li> <a href="<?php echo site_url('online_exams/major_sheet') ?>" ><?php echo lang('lbl_major_sheet');?></a></li>
                        </ul>
                    </li>
                   <!-- $this->session->userdata("userdata")["department_id"] == get_teacher_dept_id())  -->
                <?php } if($role_id == '1') { ?>
                    <li>
                        <a href="javascript:void(0);" class="waves-effect"> <i class="fa fa-check"></i> <span class="hide-menu"> <b><?php echo lang('lbl_online_exam');?></b><span class="badge badge-pill pull-right badge-warning"><?php echo lang('new') ?></span><span class="fa arrow"></span> </span> </a>
                        <ul class="nav nav-second-level">
                            <li> <a href="<?php echo site_url('online_exams/settings') ?>" ><?php echo lang('settings');?></a></li>
                            <li> <a href="<?php echo site_url('online_exams/add_question') ?>" ><?php echo lang('add_questions');?></a></li>
                            <li> <a href="<?php echo site_url('online_exams/publish_papers') ?>" ><?php echo lang('publish_paper');?></a></li>
                            <li> <a href="<?php echo site_url('online_exams/results') ?>" ><?php echo lang('mark_sheet');?></a></li>
                            <li> <a href="<?php echo site_url('online_exams/major_sheet') ?>" ><?php echo lang('lbl_major_sheet');?></a></li>
                        </ul>
                    </li>
                <?php } ?>
                <?php if($role_id == '3') { ?>
                    <li> 
                        <a href="<?php echo site_url('online_exams/student'); ?>" class="waves-effect"> 
                            <i class="fa fa-check"></i> 
                            <span class="hide-menu"><b><?php echo lang('lbl_online_exam');?></b> <span class="badge badge-pill pull-right badge-warning"><?php echo lang('new') ?></span></span>
                        </a>
                    </li>
                <?php } ?>
                
            <li>
                <a class="logo" href="dashboard/" id="official" style="text-align:center">
                    <!--This is dark logo text-->
                    <img src="assets/landingpage/images/uvs.png" alt="home" class="dark-logo" style="height:40px;" />
                    <!--This is light logo text-->
                </a>
            </li>
            <li style="visibility: hidden;">
                <a class="logo2" href="dashboard/" id="official" style="text-align:center;height:200px;">
                </a>
            </li>


        </ul>
    </div>
</div>
<!-- Left navbar-header end -->