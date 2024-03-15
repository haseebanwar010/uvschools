<div id="wrapper" ng-controller="profileController" ng-init="allNotifications(); countNotifications()">
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
                            <img src="assets/plugins/images/logo-icon1.png" alt="home" class="dark-logo" />
                            <!--This is light logo icon-->
                            <img src="assets/plugins/images/logo-icon1.png" alt="home" class="light-logo"/>
                        </b>
                        <span class="hidden-xs">
                            <!--This is dark logo text-->
                            <img src="uploads/logos/<?php echo $this->session->userdata('userdata')['sh_logo']; ?>" alt="home" class="dark-logo" style="width:108px; max-height: 25px;"/>
                            <!--This is light logo text-->
                            <img src="uploads/logos/<?php echo $this->session->userdata('userdata')['sh_logo']; ?>" alt="home" class="light-logo"  style="width:108px; max-height: 25px;"/>
                        </span>
                    </a>
                <?php } ?>
            </div>
            <ul class="nav navbar-top-links navbar-left hidden-xs">
                <li><a href="javascript:void(0)" class="open-close hidden-xs waves-effect waves-light" id="side_bar_btn"><i class="icon-arrow-left-circle ti-menu"></i></a></li>
                <!-- <li>
                    <form role="search" class="app-search hidden-xs">
                        <input type="text" placeholder="<?= lang('search') ?>..." class="form-control">
                        <a href=""><i class="fa fa-search"></i></a>
                    </form>
                </li> -->
            </ul>
            <ul class="nav navbar-top-links navbar-right pull-right">
                
                <li><h5 id="date-section" class="text-white text-center" style="margin-top:15px; font-size: 12px; margin-right: 10px;"></h5></li>
                <li><h4 id="time-section" class="text-white" style="margin-top:19px;margin-left: 7px"></h4></li>
                
                <li class="dropdown"> <a class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#"><i class="icon-envelope"></i>
                        <div class="notify" ng-if="countNotification > 0" ><span class="heartbit" ></span><span class="point"></span></div>
                    </a>
                    <ul class="dropdown-menu mailbox animated bounceInDown" style="max-height: 400px;">
                        <li>
                            <div class="drop-title"><?php echo lang('lbl_your_have'); ?> {{countNotification}} <?php echo lang('lbl_new_Notification'); ?></div>
                        </li>

                        <li>
                            <div class="message-center">
                                <a href="<?php echo base_url("{{n.notiUrl}}"); ?>" ng-click="show(n.id)"  ng-repeat="n in notifications" ng-show="n.is_read == 0" >

                                    <div class="mail-contnet">
                                        <h5>{{n.sender}}</h5>
                                        <span class="mail-desc">{{n.message}}</span> <span class="time">{{n.dateTime}}</span> </div>
                                </a>
                            </div>
                        </li>
                        <li>
                            <a class="text-center" href="<?php echo base_url("notification/index"); ?>"> <strong><?php echo lang('lbl_all_notification'); ?></strong> <i class="fa fa-angle-right"></i> </a>
                        </li>
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