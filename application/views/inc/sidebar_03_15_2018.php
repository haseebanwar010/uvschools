<?php
$UserData = $this->session->userdata('userdata');
$profile = $this->profile_modal->getUserProfileDetail($UserData['user_id']);
$uri = $this->uri->segment(2);
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
                    <li><a href="#"><i class="fa fa-envelope-o"></i> <?php echo lang("inbox"); ?></a></li>
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

            <li> <a href="#" class="waves-effect"> <i class="fa fa-gear"></i> <span class="hide-menu"> <b><?php echo lang("settings") ?></b><span class="fa arrow"></span>  </span> </a>
                <ul class="nav nav-second-level">
                    <li> <a href="<?php echo site_url('settings/general') ?>"> <?php echo lang("general_setting"); ?></a> </li>
                    <li> <a href="<?php echo site_url('settings/hr') ?>"><?php echo lang("menu_hr_setting"); ?></a> </li>
                    <li> <a href="<?php echo site_url('settings/academic') ?>"><?php echo lang("menu_academic_settings"); ?></a> </li>
                    <li> <a href="<?php echo site_url('settings/exam') ?>"><?php echo lang("menu_examination_settings"); ?></a> </li>
                    <li> <a href="<?php echo site_url('fee/show') ?>"><?php echo lang("crumb_fee_settings"); ?></a> </li>
                </ul>

            </li>
            <li> <a href="/dashboard" class="waves-effect"> <i class="fa fa-user-secret"></i> <span class="hide-menu"> <b><?php echo lang("menu_employee") ?></b><span class="fa arrow"></span> </span> </a>
                <ul class="nav nav-second-level">
                    <li> <a href="<?php echo site_url('employee/all') ?>" ><?php echo lang("menu_employee_list"); ?></a></li>
                    <li> <a href="<?php echo site_url('employee/add') ?>" ><?php echo lang("menu_employee_add"); ?></a></li>
                </ul>

            </li>

            <li> 
                <a href="/dashboard" class="waves-effect"> <i class="fa fa-users"></i> <span class="hide-menu"> <b><?php echo lang("menu_students") ?></b><span class="fa arrow"></span> </span> </a>
                <ul class="nav nav-second-level">
                    <li> <a href="<?php echo site_url('students/show') ?>" ><?php echo lang("menu_view_all"); ?></a></li>
                    <li> <a href="<?php echo site_url('students/add') ?>" ><?php echo lang("menu_admission"); ?></a></li>
                </ul>
            </li>
            <li> <a href="/dashboard" class="waves-effect"> <i class="fa fa-user"></i> <span class="hide-menu"> <b><?php echo lang('menu_parent') ?><?php echo lang("menu_parents") ?></b><span class="fa arrow"></span> </span> </a>
                <ul class="nav nav-second-level">
                    <li> <a href="<?php echo site_url('students/parents') ?>" ><?php echo lang('menu_list_all') ?><?php echo lang("menu_parents_list"); ?></a></li>
                </ul>

            </li>

            <li> <a href="#" class="waves-effect"> <i class="fa fa fa-signal"></i> <span class="hide-menu"> <b><?php echo lang("lbl_attendance") ?></b><span class="fa arrow"></span> </span> </a>
                <ul class="nav nav-second-level">
                    <li> <a href="<?php echo site_url('attendance/show') ?>" ><?php echo lang("lbl_student_attendance"); ?></a></li>
                    <li> <a href="<?php echo site_url('attendance/report') ?>" ><?php echo lang("lbl_attendance_report"); ?></a></li>

                </ul>

            </li>

            <li> <a href="#" class="waves-effect"> <i class="fa fa-graduation-cap"></i> <span class="hide-menu"> <b><?php echo lang("lbl_academics") ?></b><span class="fa arrow"></span> </span> </a>
                <ul class="nav nav-second-level">
                    <li> <a href="<?php echo site_url('timetable/show') ?>" ><?php echo lang("lbl_timetable"); ?></a></li>
                </ul>
            </li>

            <li> <a href="#" class="waves-effect"> <i class="fa fa-envelope"></i> <span class="hide-menu"> <b><?php echo lang("crumb_messages") ?></b><span class="fa arrow"></span> </span> </a>
                <ul class="nav nav-second-level">
                    <li> <a href="<?php echo site_url('messages/show') ?>" ><?php echo lang("crumb_all_messages"); ?></a></li>
                </ul>
            </li>

            <li> <a href="javascipt:void(0)" class="waves-effect"> <i class="fa fa-download"></i> <span class="hide-menu"> <b><?php echo lang("study_material") ?></b><span class="fa arrow"></span> </span> </a>
                <ul class="nav nav-second-level">
                    <li> <a href="<?php echo site_url('study_material/upload') ?>" ><?php echo lang("lbl_upload"); ?></a></li>
                    <li> <a href="<?php echo site_url('study_material/download') ?>" ><?php echo lang("lbl_download"); ?></a></li>
                </ul>

            </li>

            <li> <a href="javascipt:void(0)" class="waves-effect"> <i class="fa fa-dollar"></i> <span class="hide-menu"> <b><?php echo lang("crumb_fee") ?></b><span class="fa arrow"></span> </span> </a>
                <ul class="nav nav-second-level">
                    <li> <a href="<?php echo site_url('fee/collection') ?>" ><?php echo lang("crumb_fee_collection") ?></a></li>
                </ul>
            </li>
            <!--Notifications-->
<!--            <li> <a href="#" class="waves-effect" ><span class="hide-menu"> <b><?php echo lang("crumb_notifications") ?></b><span class="fa arrow"></span> </span> </a>
                <ul class="nav nav-second-level">
                    <li> <a href="<?php echo site_url('notification') ?>" ><?php echo lang("crumb_all_notifications"); ?></a></li>
                </ul>
            </li>-->

            <!--<li class="nav-small-cap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Support</li>-->
        </ul>
    </div>
</div>
<!-- Left navbar-header end -->