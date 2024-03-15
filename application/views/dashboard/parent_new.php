<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<div id="page-wrapper" ng-controller="Parent_dashboardCtrl" ng-init="init_parent_children_list(); get_Today_Events(); checkPendingApplictions();get_announcement();">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?= lang("lbl_dashboard"); ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="dashboard/"><?= lang("lbl_dashboard"); ?></a></li>
                    <li class="active"><?= lang("lbl_dashboard"); ?></li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <!-- modal to view event details for parent -->
        <div class="modal fade" id="exampleModalCenter">
            <div class="modal-dialog">
                <div class="modal-content" style="margin-top: 33%;">
                    <div class="panel panel-primary" style="margin-bottom: 0;">
                        <div class="panel-heading">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo lang("lbl_calendar_close") ?></span></button>
                            <div class="modal-title"></div>
                        </div>
                        <div class="panel-body">
                            <div class="error"></div>
                            <form class="form-material" id="crud-form">
                            <input type="hidden" id="start">
                            <input type="hidden" id="end">
                                <div class="form-group">
                                    <label class="control-label" for="title"></label>
                                    <input id="tit" name="title" type="text" class="form-control" placeholder="<?php echo lang('lbl_calendar_title') ?>" readonly/>
                                </div>                            
                                <div class="form-group">
                                    <label class="control-label" for="description"></label>
                                    <textarea class="form-control" id="desc" name="description" rows="3" cols="50" placeholder="<?php echo lang('lbl_calendar_description') ?>" readonly></textarea>
                                </div>
                                
                            </form>
                            <div class="modal-footer panel-footer" style="border:0; padding-right:0;">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("lbl_calendar_cancel") ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--End modal to view event details for parent -->
        <?php echo $this->session->flashdata('alert_no_permission'); ?>
        <div class="hint"><?php echo lang('help_dashboard'); ?></div>
        <div class="alert alert-success" ng-show="has_online_application" style="position: fixed; bottom: 0; margin-bottom:0; left: 0; width: 100%; z-index: 1000;"><strong>Your online admission application is in pending!</strong></div>
        
        <div class="row" style="margin-top: 10px;">
        <div class="col-md-12" style="padding-right:0; padding-left:0;">
                    <div class="col-md-8">
                        <div class="white-box"  style="border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; color:#042954; border-radius: 10px;">
                            <div class="box-title" style="color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>">Calendar</div>
                            <div class="alert" style="position: fixed; top: 0; left: 0; width: 100%; z-index: 1000;"></div>
                            <div id='calendar'></div>
                        </div>
                    </div>

                    <!-- Column -->
                    <div class="col-lg-4 col-md-12">
                        <div class="row">
                            <!-- Column -->
                            <div class="col-md-12">
                                <div class="card" style="background:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; height: 250px; border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px;">
                                    <div class="card-body ">
                                        <div class="row weather">
                                            <div class="col-7 display-4 text-white custom-wrap" style="margin-top: 65px;" ng-if="calendarEvents.records.length>0">{{calendarEvents.records[0].title}}</div>
                                            <div class="col-7 display-4 text-white custom-wrap" style="margin-top: 65px;" ng-if="calendarEvents.records.length==0"><?php echo lang('lbl_calendar_sidEvent') ?></div>
                                            <div class="col-5 text-right">
                                                <h1 class=""><i class="fa fa-calendar"></i></h1>
                                                <b class="text-white">{{calendarEvents.current_day_of_week}}</b>
                                                <p class="op-5 text-white">{{calendarEvents.month }}, {{calendarEvents.day}}</p>
                                            </div>
                                            <div class="col-12">
                                                <p class="text-white custom-wrap" style="line-height: 80px;">{{calendarEvents.records[0].description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-12">
                                <div class="card" style="height: 317px; background: <?php echo hex2rgba($this->session->userdata('userdata')['theme_color']); ?>;
background: linear-gradient(294deg, <?php echo hex2rgba($this->session->userdata('userdata')['theme_color']); ?> 0%, rgba(9,100,121,1) 35%, <?php echo hex2rgba($this->session->userdata('userdata')['theme_color']); ?> 100%); border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px; ">
                                    <div class="card-body">
                                        <div id="myCarouse2" class="carousel slide" data-ride="carousel">
                                            <!-- Carousel items -->
                                            <div class="carousel-inner">
                                                <div class="carousel-item active" ng-if="calendarEvents.records.lenght>0">
                                                    <h4 class="display-4 text-white cmin-height">Events</h4>
                                                    <marquee onmouseover="this.stop();" onmouseout="this.start();" width="100%" direction="up" scrollamount="2" height="230px">
                                                        <ul class="text-white" ng-repeat="c in calendarEvents.records" >
                                                            
                                                            <li>{{c.title}}</li>
                                                            
                                                        </ul>
                                                    </marquee>
                                                   
                                                </div>
                                                <div class="carousel-item active" ng-if="calendarEvents.records.length==0">
                                                    <h3 class="text-white text-center" style="margin-top: 120px;"><?php echo lang('lbl_calendar_sidNoDbEvent') ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                        </div>
                    </div>
                </div>
        
            
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">          
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs customtab" role="tablist" style="background: #ddd;">
                        <li class="nav-item" ng-repeat="(key,child) in childerns"> 
                            <a class="nav-link" ng-class="{'active':$index===0}" data-toggle="tab" href="#tabs_{{child.student_id}}" ng-click="filter(child.student_id)" role="tab">
                                <span class="hidden-sm-up"><img src="uploads/user/{{ child.avatar }}" style="width:30px; height: 30px; border: 1px solid #e5e5e5; border-radius: 100px;"/></i></span> 
                                <span class="hidden-xs-down"><img src="uploads/user/{{ child.avatar }}" style="width:30px; height: 30px; border: 1px solid #e5e5e5; border-radius: 100px;"/></span>
                                <span class="hidden-xs-down">{{child.name}}</span> 
                            </a> 
                        </li>
                    </ul>
                    
                    <!-- Tab panes -->
                    <div class="tab-content" style="margin-top: 0; padding-top:15px; background-color: #ecf0f3;">
                        <div class="tab-pane" ng-repeat="child in childerns" ng-class="{'active':$index===0}" id="#tabs_{{child.student_id}}" role="tabpanel"> 
                            
                            <div class="col-md-12" style="background-color: #ffffff; padding: 0px 15px 0px 15px; border-radius:  10px; margin-bottom: 15px; border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>">
                                <h4 style="font-weight: 600;text-decoration: none; margin-left: 0px; color: <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>"><?php echo lang('lbl_timetable'); ?></h4>
                                 <div class="row">
                                     
                                     <div class="col-lg-3 col-md-6" ng-repeat="p in current_day_periods">
                                        <div class="card" style="border:1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; display: block; height: 100px; box-shadow: 2px 2px; border-radius: 5px;">
                                            <div class="card-body">
                                                <div class="d-flex no-block">
                                                    <div class="round align-self-center round-success"><i class="ti-time"></i></div>
                                                    <div class="m-l-10 align-self-center">
                                                        <h4 class="m-b-0"><b>{{p.title}}</b></h4>
                                                        <h5 class="text-muted m-b-0">{{p.start_time}} - {{p.end_time}}</h5>
                                                        <h6 class="text-muted m-b-0" ng-repeat="tb in current_day_timetable" style="font-weight: bold;">
                                                            <span ng-if="tb.period_id == p.id">{{ tb.sub_name }}</span>
                                                            <strong  ng-if="tb.period_id == p.id" class="text-primary">-{{ tb.teacher_name }}</strong>
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                 </div>
                            </div>
                            
                            <div class="col-md-6" style="padding:0;">
                                <div class="col-md-12 white-box p-0" style="border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px; margin-right: 10px;">
                                    <h4 style="font-weight: 600;text-decoration: none; padding-left: 15px; color: <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>"><?php echo lang('lbl_fee_summary'); ?></h4>
                                </div>
                                <div class="col-md-6" style="padding-left:0;">
                                    <div class="card" style="border:1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; display: block; height: 150px; box-shadow: 2px 2px; border-radius: 5px;">
                                        <div class="card-body">
                                            <div class="d-flex no-block">
                                                <div class="round align-self-center round-success m-l-10"><i class="fa fa-dollar fa-3x" style="color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>;"></i></div>
                                                <div class="text-right m-r-10" style="width: 100%;">
                                                    <h2 class="m-b-0"><b><?php echo lang('fully_paid'); ?></b></h2>
                                                    <h1 class="text-muted m-b-0">{{fee_data['total_paid']}}</h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6" style="padding-left: 0;">
                                    <div class="card" style="border:1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; height: 150px; display: block; box-shadow: 2px 2px; border-radius: 5px;">
                                        <div class="card-body">
                                            <div class="d-flex no-block">
                                                <div class="round align-self-center round-success m-l-10"><i class="fa fa-dollar fa-3x" style="color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>;"></i></div>
                                                <div class="text-right m-r-10" style="width: 100%;">
                                                    <h2 class="m-b-0"><b><?php echo lang('partial_paid'); ?></b></h2>
                                                    <h1 class="text-muted m-b-0">{{fee_data['total_partial']}}</h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12" style="padding-left: 0;">
                                    <div class="card" style="border:1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; display: block; height: 150px; box-shadow: 2px 2px; border-radius: 5px;">
                                        <div class="card-body">
                                            <div class="d-flex no-block">
                                            <div class="round align-self-center round-success m-l-10"><i class="fa fa-dollar fa-3x"  style="color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>;"></i></div>
                                                <div class="text-right m-r-10" style="width: 100%;">
                                                    <h2 class="m-b-0"><b><?php echo lang('lbl_due_chart'); ?></b></h2>
                                                    <h1 class="text-muted m-b-0">{{fee_data['total_due']}}</h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" style="padding-right:0;">
                                <div class="card" style="border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px;">
                                    <div class="card-body">
                                        <h4 class="card-title text-info">
                                            <?php echo lang("lbl_fee_chart"); ?>
                                        </h4>
                                        <div class="d-flex no-block">
                                            <div class="align-self-center" style="margin-left: 35px;">
                                                <div class="card" style="background-color: transparent; height: 320px; margin-top: -25px;">
                                                    <div class="card-body" style="padding-top:0;">
                                                        <div id="fee-graph"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6" style="padding:0;">
                                <div class="card" style="border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px;" id="study-plan-statictics-container">
                                    <div class="card-body">
                                        <h4 class="card-title text-info">
                                            <?php echo lang("lbl_study_plan_summary"); ?>
                                            <button type="button" ng-click="show_studyplan_overall_graph(studyplan_overall)" class="mb-2 pull-right btn btn-primary btn-sm"><i class="fa fa-bar-chart"></i> <?php echo lang("lbl_overall"); ?></button> 
                                        </h4>
                                        <span ng-if="studyplan.length===0" class="text-danger"><?php echo lang("no_record"); ?></span>
                                        <div ng-if="studyplan.length!==0" class="table-responsive" style="height: 355px; overflow:auto;">
                                            <div ng-repeat="s in studyplan">
                                                <div class="col-md-12 pl-0 pr-1">
                                                    <p>
                                                        <strong>{{s.class_name}}</strong> - {{s.batch_name}}
                                                    </p>
                                                </div>
                                                <table ng-if="s.subjects.length>0" class="table table-bordered table-striped">
                                                    <tr>
                                                        <th><?php echo lang("lbl_subject"); ?></th>
                                                        <th><?php echo lang("lbl_details"); ?></th>
                                                    </tr>
                                                    <tr ng-repeat="ss in s.subjects">
                                                        <td><strong>{{ss.name}}</strong></td>
                                                        <td ng-if="ss.syllabus.data.length===0"><span class="text-danger"><?= lang("no_record"); ?></span></td>
                                                        <td ng-if="ss.syllabus.data.length>0">
                                                            <table class="table table-default">
                                                                <tr>
                                                                    <th ng-repeat="(key,sss) in ss.syllabus.counts">{{sss.name}}</th>
                                                                </tr>
                                                                <tr>
                                                                    <td ng-if="ss.syllabus.data.length>0" ng-repeat="ssss in ss.syllabus.counts">{{ssss.count}}</td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <div ng-if="s.subjects.length===0" class="col-md-12 well"><span class="text-danger"><?= lang("no_record"); ?></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" style="padding:0px 0px 0px 15px;">
                                <div class="card" style="border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px;">
                                    <div class="card-body">
                                        <h4 class="card-title text-info"><?php echo lang("lbl_study_plan_chart"); ?> {{selected_class_batch}}</h4>
                                        <div id="morris-bar-chart" style="height: 360px;margin-left: -24px;"></div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6" style="padding:0;">
                                <div class="card" style="border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px;">
                                    <div class="card-body" id="today_student2_attendance_table" ng-class="{setheight:student_attendance.length==0}">
                                        <div class="col-md-12" ng-if="student_attendance.length>0">
                                            <h4 class="card-title text-info">
                                                <?php echo lang('today_students') ?>
                                                <button type="button" ng-click="show_std_attendance_individal_graph(std_overall)" class="mb-2 pull-right btn btn-primary btn-sm"><i class="fa fa-bar-chart"></i> <?php echo lang("lbl_overall"); ?></button> 
                                            </h4>
                                            <div class="table-responsive at-length">
                                                <table ng-if="student_attendance.length > 0" class="table table-striped table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo lang('lbl_class') ?></th>
                                                            <th><?php echo lang('lbl_present') ?></th>
                                                            <th><?php echo lang('lbl_absent') ?></th>
                                                            <th><?php echo lang('lbl_leave') ?></th>
                                                            <th><?php echo lang('lbl_late') ?></th>
                                                            <th><?php echo lang('lbl_action') ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr ng-repeat="s_att in student_attendance">
                                                            <td>{{ s_att.classname }}</td>
                                                            <td>{{ s_att.present }}</td>
                                                            <td>{{ s_att.absent }}</td>
                                                            <td>{{ s_att.leav }}</td>
                                                            <td>{{ s_att.late }}</td>
                                                            <td><button type="button" ng-click="show_std_attendance_individal_graph(s_att)" class="btn btn-secondary btn-sm"><i class="fa fa-bar-chart"></i></button> </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-12" style="height: 405px;" ng-if="student_attendance.length==0">
                                            <span class="text-danger"><?php echo lang('lbl_today_students_attendance_not_found'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" style="padding:0px 0px 0px 15px;">
                                <div class="card" style="border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px;">
                                    <div class="card-body">
                                        <h4 class="card-title text-info" ng-if="selected_class_for_graph===''"><?php echo lang("lbl_attendance_chart"); ?> <?php echo lang("lbl_overall"); ?></h4>
                                        <h4 class="card-title text-info" ng-if="selected_class_for_graph!==''"><?php echo lang("lbl_attendance_chart"); ?> {{ selected_class_for_graph }}</h4>
                                        <div class="d-flex m-b-40 align-items-center no-block">
                                            <div class="ml-auto">
                                                <ul class="list-inline font-12">
                                                    <li><i class="fa fa-circle text-success"></i> <?php echo lang("lbl_present"); ?></li>
                                                    <li><i class="fa fa-circle text-info"></i> <?php echo lang("lbl_leave"); ?></li>
                                                    <li><i class="fa fa-circle text-warning"></i> <?php echo lang("lbl_late"); ?></li>
                                                    <li><i class="fa fa-circle text-danger"></i> <?php echo lang("lbl_absent"); ?></li>
                                                    <li><i class="fa fa-circle text-inverse"></i> <?php echo lang("lbl_unknown"); ?></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div id="morris-area-chart-student-attendance" style="height: 300px;"></div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12" style="border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px; background-color: #fff;">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title text-info"><?php echo lang("lbl_students_active_academic_year_attendance_chart"); ?></h4>
                                        <div class="d-flex m-b-40 align-items-center no-block">
                                            <div class="ml-auto">
                                                <ul class="list-inline font-12">
                                                    <li><i class="fa fa-circle text-success"></i> <?php echo lang("lbl_present"); ?></li>
                                                    <li><i class="fa fa-circle text-info"></i> <?php echo lang("lbl_leave"); ?></li>
                                                    <li><i class="fa fa-circle text-warning"></i> <?php echo lang("lbl_late"); ?></li>
                                                    <li><i class="fa fa-circle text-danger"></i> <?php echo lang("lbl_absent"); ?></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div id="morris-area-chart-std-academic-wise-attendance" style="height: 300px;"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
               
            </div>
        </div>
    </div>
        <!-- .right-sidebar -->
        <!-- Include yout right sidebar here -->
        <!-- /.right-sidebar -->
    </div>
</div>
    
    <?php include(APPPATH . "views/inc/footer.php"); ?>
    <script src='assets/fullcalendar/js/main.php'></script>