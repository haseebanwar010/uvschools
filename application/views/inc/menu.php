<?php
$UserData = $this->session->userdata('userdata');
$profile = $this->profile_modal->getUserProfileDetail($UserData['user_id']);
$uri = $this->uri->segment(2);
$role_id = $UserData['role_id'];
?>
<div id="wrapper" ng-controller="profileController" ng-init="allNotifications(); countNotifications();get_announcement()">
    
    <!-- Announcement modal start -->
    <div id="myNavAnnouncement" class="overlay-announcements">
        <a href="javascript:void(0)" class="closebtn no-print" ng-click="closeNav()">&times;</a>
        <div class="overlay-announcements-content" id="overlay-announcements-content">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10 text-center">
                    <div class="col-lg-4 col-md-4 col-xlg-2 col-xs-12" ng-repeat="ann in announcement">
                        <div class="ribbon-wrapper card" style="border-radius: 15px; border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>;">
                            <div class="ribbon ribbon-primary" style="background: <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>;width:-webkit-fill-available;"><b class="custom-wrap" title="{{ann.title}}"><i class="fa fa-bullhorn"></i> {{ann.title}}</b></div>
                            <div class="ribbon-content">
                                <p  ng-bind-html="ann.details"></p>
                                <p style="font-size: 18px; padding-top: 3px;">Date : {{ann.from_date}}</p>
                                <p style="color: #9e99a3; font-size: 16px;">More Details: <br/><a href="announcements/details/{{ann.id}}" target="blank" style="font-weight: normal; font-size: 26px; color: <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>;">Click Here</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ./.Announcement modal start -->    
    
    <!-- Google Drive Status modal start -->
    <div id="gd_anouncement" class="overlay-announcements" >
        <!--<a href="javascript:void(0)" class="closebtn no-print" ng-click="closeNav()">&times;</a>-->
        <div class="overlay-announcements-content" id="">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10 text-center">
                    <div class="col-lg-4 col-md-4 col-xlg-2 col-xs-12">
                        <div class="ribbon-wrapper card" style="border-radius: 15px; border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>;">
                            <div class="ribbon ribbon-primary" style="background: <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>;width:-webkit-fill-available;"><b class="custom-wrap"><i class="fa fa-bullhorn"></i> Google Drive Integration</b></div>
                            <div class="ribbon-content">
                                <p style="font-size: 14px;">Dear admin please integrate Google Drive to proceed further</p>
                                <p style="color: #9e99a3; font-size: 16px;"><a href="settings/googledrive_integration" style="font-weight: normal; font-size: 26px; color: <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>;">Click Here</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ./.Google Drive Status modal start -->
    
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top m-b-0">
        <div class="navbar-header"> <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse"><i class="ti-menu"></i></a>
            <div class="top-left-part">
                <?php if ($this->session->userdata("userdata")["sh_logo"] === null) { ?>
                    <a class="logo" href="dashboard/">
                        <b>
                            <!--This is dark logo icon-->
                            <img src="assets/plugins/images/logo-icon1.png" alt="home" class="dark-logo" />
                            <!--This is light logo icon-->
                            <img src="assets/plugins/images/logo-icon1.png" alt="home" class="light-logo"/>
                        </b>
                        <span class="hidden-xs">
                            <!--This is dark logo text-->
                            <img src="assets/plugins/images/logo-text.png" alt="home" class="dark-logo"/>
                            <!--This is light logo text-->
                            <img src="assets/plugins/images/logo-text.png" alt="home" class="light-logo" />
                        </span>
                    </a>
                <?php } else { ?>
                    <a class="logo" href="dashboard/">
                        <b>
                            <!--This is dark logo icon-->
                            <img src="uploads/logos/<?php echo $this->session->userdata('userdata')['sh_logo']; ?>" style="width:50px; height: 50px;" alt="home" class="dark-logo" />
                            <!--This is light logo icon-->
                            <img src="uploads/logos/<?php echo $this->session->userdata('userdata')['sh_logo']; ?>" style="width:50px; height: 50px;" alt="home" alt="home" class="light-logo"/>
                        </b>
                      <!--   <span class="hidden-xs">
                           
                            <img src="uploads/logos/<?php echo $this->session->userdata('userdata')['sh_logo']; ?>" alt="home" class="dark-logo" style="width:108px; max-height: 25px;"/>
                            
                            <img src="uploads/logos/<?php echo $this->session->userdata('userdata')['sh_logo']; ?>" alt="home" class="light-logo"  style="width:108px; max-height: 25px;"/>
                        </span> -->
                    </a>
                <?php } ?>
            </div>
            <ul class="nav navbar-top-links navbar-left hidden-xs">
                <li><a href="javascript:void(0)" class="open-close hidden-xs waves-effect waves-light" id="side_bar_btn"><i class="icon-arrow-left-circle ti-menu"></i></a></li>
<!--                <li><a href="javascript:void(0)" style="text-decoration: none;">Academic Year : <?php echo $this->session->userdata("userdata")["academic_year_name"];?></a></li>-->
                <li><span class="text-white" style="margin-top:15px; font-size: 14px; line-height: 57px; font-weight: 600;"><?php echo lang("lbl_academic_year")?>: <?php echo $this->session->userdata("userdata")["academic_year_name"];?></span></li>
                <!-- <li>
                    <form role="search" class="app-search hidden-xs">
                        <input type="text" placeholder="<?= lang('search') ?>..." class="form-control">
                        <a href=""><i class="fa fa-search"></i></a>
                    </form>
                </li> -->
                <?php if ($this->session->userdata("userdata")["maintenance_msg"] != "") { ?>
                <li style="margin-top:18px"><span class="label label-warning"><?php echo $this->session->userdata("userdata")["maintenance_msg"]; ?></span></li>
                <?php } ?>
            </ul>
            <ul class="nav navbar-top-links navbar-right pull-right">
                <li class="dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <span class="hidden-sm hidden-xs visible-lg visible-md"><?php echo lang(ucfirst($this->session->userdata("site_lang"))) ; ?> &nbsp;<i class="fa fa-angle-down"></i></span>
                        <span class="visible-sm visible-xs hidden-md hidden-lg"><?php echo substr(ucfirst($this->session->userdata("site_lang")),0,2); ?> &nbsp;<i class="fa fa-angle-down"></i></span>
                         </a>
                    
                    <div class="dropdown-menu dropdown-menu-right animated flipInY" style="min-width: 92px; padding-left: 24px;">
                        <a href="<?php echo base_url(); ?>LanguageSwitcher/switchLang/english" class="dropdown-item"><?php echo lang("detailed_f1_multilang")?></a>
                        <a href="<?php echo base_url(); ?>LanguageSwitcher/switchLang/arabic" class="dropdown-item"> <?php echo lang("detailed_f2_multilang")?></a>
                    </div>
                </li>
                <li><h5 id="date-section" class="text-white text-center" style="margin-top:15px; font-size: 12px; margin-right: 10px;"></h5></li>
                <li><h4 id="time-section" class="text-white" style="margin-top:19px;margin-left: 7px"></h4></li>
                <li class="cal15"><a href="<?php echo base_url('calendar/index') ?>" title="<?php echo $this->lang->line('calendar') ?>"><i class="fa fa fa-calendar"></i></a></li>
                <li class="cal15"><a href="javascript:showAnnouncements()" title="<?php echo $this->lang->line('menu_announcements') ?>"><i class="fa fa fa-bullhorn"></i>
                        <div class="notify" ng-if="announcement.length > 0" ><span class="heartbit" ></span><span class="point"></span></div>
                    </a>
                </li>
                <!--<?php //if($role_id=='2'){ ?>
                    <li>
                    
                </li>
                <?php //} else if($role_id == '1' || login_user()->user->role_id == ADMIN_ROLE_ID) { ?>
                <li>
                    <a class="waves-effect waves-light" href="trash">
                        <i class="ti-trash"></i>
                        <div class="notify">
                            <span class="heartbit" ></span>
                            <span class="point"></span>
                        </div>
                    </a>
                </li>
            <?php //} ?>-->
                <li class="dropdown"> <a class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#"><i class="fa fa-bell-o"></i>
                        <div class="notify" ng-if="countNotification > 0" ><span class="heartbit" ></span><span class="point"></span></div>
                    </a>
                    <ul class="dropdown-menu mailbox animated bounceInDown" style="max-height: 400px;">
                        <li>
                            <div class="drop-title"><?php echo lang('lbl_your_have'); ?> {{countNotification}} <?php echo lang('lbl_new_Notification'); ?>
                                
                            </div>
                        </li>

                        <li>
                           <div class="message-center">
                                <div class="mail-contnet" ng-repeat="n in notifications" ng-show="n.is_read == 0" style="border-bottom: 1px solid #eee;">
                                    <div class="row">
                                        <div class="col-sm-10 p-0 m-0">
                                            <?php if($role_id == '2'){ ?>
                                                <a href="<?php echo base_url("{{n.notiUrl}}"); ?>" ng-click="show(n.id)" style="border-bottom: 0px;">
                                       <img src="<?php echo base_url() ?>uploads/user/{{n.user_img}}" slt="user-img" class="img-circle" style="width:40px; height:40px;">
                                   <?php } else { ?>
                                            <a href="<?php echo base_url("{{n.notiUrl}}"); ?>" ng-click="show(n.id)" style="border-bottom: 0px;">
                                       <img src="<?php echo base_url() ?>uploads/user/{{n.user_img}}" slt="user-img" class="img-circle" style="width:40px; height:40px;">
                                   <?php } ?>
                                       <span style="font-family: inherit; font-size: 12px; font-weight:bold; margin-left: 10px">{{n.sender}}</span>
                                       <span style="padding: 0px;" class="time pull-right">{{n.dateTime}}</span> 
                                   </a>
                                        </div>
                                        <div class="col-sm-2 text-center" style="vertical-align: center">
                                            <a href="javascript:void(0);"  ng-click="show(n.id)" class="p-0 m-4" data-toggle="tooltip" data-placement="left" title="Mark as Read!"><i class="glyphicon glyphicon-remove" aria-hidden="true"></i></span></a>
                                        </div>
                                    </div>
                                    <!-- <a href="<?php echo base_url("{{n.notiUrl}}"); ?>" ng-click="show(n.id)" style="border-bottom: 0px;">
                                       <img src="<?php echo base_url() ?>uploads/user/{{n.user_img}}" slt="user-img" class="img-circle" style="width:40px; height:40px;">
                                       <span style="font-family: inherit; font-size: 12px; font-weight:bold; margin-left: 10px">{{n.sender}}</span> <span style="padding: 0px;" class="time pull-right">{{n.dateTime}}</span>
                                   </a> -->
                                    <p style="color:#000; padding-left: 10px;">{{n.message}}</p>
                                     
                                </div>
                              
                           </div>
                       </li>
                       <?php if($role_id == '2'){ ?>
                        <li>
                            <a class="text-center"> <strong><?php echo lang('lbl_all_notification'); ?></strong> <i class="fa fa-angle-right"></i> </a>
                        </li>
                    <?php } else{ ?> 
                        <li>
                            <a class="text-center" href="<?php echo base_url("notification/index"); ?>"> <strong><?php echo lang('lbl_all_notification'); ?></strong> <i class="fa fa-angle-right"></i> </a>
                        </li>
                    <?php } ?>
                    </ul>
                    <!-- /.dropdown-messages -->
                </li>
                <!-- /.dropdown -->
          <!--       <li class="dropdown"> <a class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#"><i class="icon-note"></i>
                        <div class="notify"><span class="heartbit"></span><span class="point"></span></div>
                    </a>
                    <ul class="dropdown-menu dropdown-tasks animated slideInUp">
                        <li>
                            <a href="#">
                                <div>
                                    <p> <strong>Task 1</strong> <span class="pull-right text-muted">40% Complete</span> </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"> <span class="sr-only">40% Complete (success)</span> </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <p> <strong>Task 2</strong> <span class="pull-right text-muted">20% Complete</span> </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%"> <span class="sr-only">20% Complete</span> </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <p> <strong>Task 3</strong> <span class="pull-right text-muted">60% Complete</span> </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%"> <span class="sr-only">60% Complete (warning)</span> </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <p> <strong>Task 4</strong> <span class="pull-right text-muted">80% Complete</span> </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%"> <span class="sr-only">80% Complete (danger)</span> </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="#"> <strong>See All Tasks</strong> <i class="fa fa-angle-right"></i> </a>
                        </li>
                    </ul>
                    
                </li> --> 
                <!--<li class="right-side-toggle"> <a class="waves-effect waves-light" href="javascript:void(0)"><i class="ti-settings"></i></a></li>-->
                <!-- /.dropdown -->
            </ul>
        </div>
        <!-- /.navbar-header -->
        <!-- /.navbar-top-links -->
        <!-- /.navbar-static-side -->
    </nav>