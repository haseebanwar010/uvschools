<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<style>
    .at-length{
        height: 362px;
        overflow: auto;
    }
    .modal {
      overflow-y:auto;
  }
  @media (min-width: 768px) and (max-width: 1024px) {

    .at-length{
        height: 340px;
        overflow: auto;
    }
}
.floating-btn{
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
}
.floating-btn2{
    position: fixed;
    bottom: 20px;
    left: 20px;
    z-index: 1000;
}
.floating2-btn{
    position: fixed;
    bottom: 75px;
    right: 20px;
    z-index: 1000;
}
.floating2-btn2{
    position: fixed;
    bottom: 75px;
    left: 20px;
    z-index: 1000;
}
</style>
<?php 
$ci = & get_instance();
$arr = $ci->session->userdata("userdata")['persissions'];
$array = json_decode($arr);
$upload_floating_btn = 0;
$att_std_btn = 0;
if(isset($array)){
    foreach ($array as $key => $value) {
        if (in_array('study_material-upload', array($value->permission)) && $value->val == 'true') {
            $upload_floating_btn = 1;
        }
        if (in_array('attendance-show', array($value->permission)) && $value->val == 'true') {
            $att_std_btn = 1;
        }
    }
}
?>
<!-- Page Content -->
<div id="page-wrapper" ng-controller="dashboardCtrl" ng-init="get_Today_Events()">
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

        <div class="row"><div class="col-md-12"><div class="white-box"><?php echo lang('help_dashboard'); ?></div></div></div>
        <div class="row" style="margin-top: 10px;">
            <div class="col-md-12" style="padding-right:0; padding-left:0;">
                <div class="col-md-8">
                    <div class="white-box"  style="border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; color:#042954;">
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
                            <div class="card" style="background:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; height: 250px; border: 1px solid;">
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
                            background: linear-gradient(294deg, <?php echo hex2rgba($this->session->userdata('userdata')['theme_color']); ?> 0%, rgba(9,100,121,1) 35%, <?php echo hex2rgba($this->session->userdata('userdata')['theme_color']); ?> 100%); border: 1px solid;">
                            <div class="card-body">
                                <div id="myCarouse2" class="carousel slide" data-ride="carousel">
                                    <!-- Carousel items -->
                                    <div class="carousel-inner">
                                        <div class="carousel-item active" ng-if="calendarEvents.records.lenght>=0">
                                            <h4 class="display-4 text-white cmin-height">Events</h4>
                                            <marquee onmouseover="this.stop();" onmouseout="this.start();" width="100%" direction="up" scrollamount="2" height="230px">
                                                <ul class="text-white" ng-repeat="c in calendarEvents.records" >
                                                    
                                                    <li>{{c.title}}</li>
                                                    
                                                </ul>
                                            </marquee>
                                            <div class="carousel-item active" ng-if="calendarEvents.records.length==0">
                                                <h3 class="text-white text-center" style="margin-top: 120px;"><?php echo lang('lbl_calendar_sidNoDbEvent') ?></h3>
                                            </div>
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
            <div class="white-box">
                <?php if (count($classes) > 0) { ?>
                    <h5 class="box-title text-info"><?php echo lang('time_table') ?> -<?php echo lang(strtolower(date("l"))); ?></h5>
                    <ul class="list-group" style="height: 400px; overflow-y: auto;">
                        <?php foreach ($classes as $clss) { ?>
                            <li class="list-group-item">
                                <h4 class="box-title"><?php echo lang('lbl_class'); ?>-<?php echo $clss->name; ?></h4>
                                <?php foreach ($batches as $bth) { ?>
                                    <?php if ($bth->class_id == $clss->id) { ?>
                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <th style="width:150px;"><?php echo $bth->name; ?></th>
                                                <?php foreach ($timetables as $tb) { ?>
                                                    <?php if ($tb->batch_id == $bth->id && $tb->day_of_week == strtolower(date("l"))) { ?>
                                                        <td>
                                                            <b><?php echo $tb->title; ?></b><br/>
                                                            <b><?php echo $tb->subjectName; ?></b><br/>
                                                            <small><?php echo $tb->teacher_name; ?></small><br/>
                                                            <small><?php echo $tb->start_time . "-" . $tb->end_time; ?></small>
                                                        </td>
                                                    <?php } ?>  
                                                <?php } ?>
                                            </tr>
                                        </table>
                                    <?php } ?>
                                <?php } ?>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } else { ?>
                    <p class="text-danger"><?php echo lang("lbl_today_timetable_not_found"); ?></p>
                <?php } ?>
            </div>
        </div>
    </div>
    
    <div class="row">            
        <div class="col-md-6" ng-init="init_student_today_attenance()">
            <div class="card">
                <div class="card-body" id="today_student2_attendance_table">
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
                    <div class="col-md-12" ng-if="student_attendance.length==0">
                        <span class="text-danger"><?php echo lang('lbl_today_students_attendance_not_found'); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
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
    </div>
    
    <div class="row" ng-init="init_study_plan_statictics()">
        <div class="col-md-6">
            <div class="card" id="study-plan-statictics-container">
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
                                    <button type="button" ng-if="s.subjects.length!==0" class="btn btn-secondary btn-sm pull-right" ng-click="show_studyplan_indivial_graph(s)"><i class="fa fa-bar-chart"></i></button>
                                </p>
                            </div>
                            <table ng-if="s.subjects.length>0" class="table table-bordered table-striped">
                                <tr>
                                    <th><?php echo lang("lbl_subject_msg"); ?></th>
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

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-info"><?php echo lang("lbl_study_plan_chart"); ?> {{selected_class_batch}}</h4>
                    <div id="morris-bar-chart" style="height: 355px;"></div>
                </div>
            </div>
        </div>
        
    </div>
    
</div>
<?php if($upload_floating_btn == 1){ ?>
    <div ng-controller="uploadController" ng-init="init();">
        <button class="btn btn-primary btn-circle btn-lg <?php if($this->session->userdata('site_lang') != 'english') { echo 'floating-btn2'; } else { echo 'floating-btn'; } ?>" data-toggle="modal"
            data-target="#upload" ng-click="resetModalDashboard()"><i class="fa fa-upload"></i>
        </button>
        <div class="modal fade" id="upload" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="panel panel-primary">
                    <div class="panel-heading"><?php echo lang('study_material') ?></div>
                    <div class="panel-body">


                        <form action="#" >
                            <div class="form-body">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php echo lang('material_title') ?></label>
                                            <input type="text" id="firstName" ng-model="study.title"
                                            class="form-control "
                                            ></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Date</label>
                                                <input type="text" ng-model="study.uploaded_at" class="form-control  mydatepicker-autoclose"
                                                ></div>
                                            </div>

                                        </div>
                                        <!--/span-->
                                        <div class="row">
                                           <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('material_type') ?></label>
                                                <select class="form-control " name="grade-type" ng-model="study.type" >
                                                    <option value="" disabled="">--<?php echo lang('lbl_select') ?>--</option>
                                                    <option value="Assignment"><?php echo lang('lbl_assignment') ?></option>
                                                    <option value="Homework"><?php echo lang('lbl_homework') ?></option>
                                                    <option value="Study Material"><?php echo lang('study_material') ?></option>
                                                    <option value="Classwork"><?php echo lang('lbl_class_work') ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                                <select class="form-control " name="grade-type" ng-model="study.class" ng-change="getSections()">
                                                    <option value="" disabled="">--<?php echo lang('lbl_select') ?>--</option>
                                                    <option ng-repeat="classi in study.classes" value="{{classi.id}}">{{classi.name}}</option>

                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <!--/span-->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_batch') ?></label>
                                                <select class="form-control " name="grade-type" ng-model="study.section" ng-change="getSubjects()">
                                                    <option value="" disabled="">--<?php echo lang('lbl_select') ?>--</option>
                                                    <option ng-show="study.batches.length > 1" value="all"><?php echo lang('option_all') ?></option>
                                                    <option ng-repeat="batch in study.batches" value="{{batch.id}}">{{batch.name}}</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_subject') ?></label>
                                                <select class="form-control " name="grade-type" ng-model="study.subject">
                                                    <option value="" disabled="">--<?php echo lang('lbl_select') ?>--</option>
                                                    <option ng-repeat="subject in study.subjects" value="{{subject.id}}">{{subject.name}} ({{subject.batch_name}})</option>

                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('document_storage');?></label><br>
                                                <?php if(file_exists($credentials_fileid) && $enable_gd==1){ ?>
                                                <!--Google Drive-->
                                                    <img src="assets/googledrive_guide/images/gd_logo.png" alt="Google Drive" style="height: 50px;"/>
                                                <?php } else { ?>
                                                <!--Local Drive-->
                                                    <img src="assets/googledrive_guide/images/ld_logo.png" alt="Local Drive" style="height: 50px;"/>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_details') ?></label>
                                                <textarea class="textarea_editor form-control" rows="5" placeholder="" ></textarea>


                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                       <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label"><?php echo lang('upload_content') ?></label>
                                            <div
                                            class="dropzone"
                                            id="my-awesome-dropzone2" dropzone="dropzoneConfigDashboard"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="alert" id="message_alert" style="display: none"></div>
                                <!--/span-->
                            </div>
                            <div class="row pull-right">
                                <div style="margin-right: 8px">
                                    <button type="button" class="btn btn-default"
                                    data-dismiss="modal"><?php echo lang('lbl_close') ?>
                                </button>
                            </div>
                            <div>
                                <button type="button" class="btn btn-primary" id="upload_material_dashboard">
                                    <?php echo lang('btn_save') ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<?php } ?>
<?php if($att_std_btn == 1){  ?>
    <div ng-controller="attendanceController">
        <button class="btn btn-info btn-circle btn-lg <?php if($this->session->userdata('site_lang') != 'english') { echo 'floating2-btn2'; } else { echo 'floating2-btn'; } ?>" data-toggle="modal"
            data-target="#attendanceModal" ng-click="resetModalDashboard()"><i class="fa fa-edit"></i>
        </button>
        <!-- attendance code -->
        <div id="confirmModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><?php echo lang('att_confirmation') ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <p><?php echo lang('attendance_confirmation') ?></p>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang('btn_cancel') ?></button>
                        <button type="button" class="btn btn-success waves-effect waves-light"  ng-click="saveAttendance(stdAttendanceForm.$valid)"><?php echo lang('lbl_save') ?></button>
                    </div>
                </div>
            </div>
        </div>

        <div id="requestModel" class="modal fade edit_attendance_request_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content" style="border-radius: 16px">
                    <div class="panel panel-primary" style="border-radius: 16px">
                        <div class="modal-header panel-heading" style="border-top-right-radius: 16px; border-top-left-radius: 16px">
                            <?php echo lang('lbl_application_request_reason') ?>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <textarea class="form-control" id="requestText" ng-model="requestText" rows="5" placeholder="<?php echo lang('lbl_reason_placeholder') ?>"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <p class="text-danger" id="request_error" style="display: none; margin-right: 10%;"><?php echo lang('lbl_reason_error') ?></p>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"data-toggle="modal" data-target="#attendanceModal"><?php echo lang('btn_cancel') ?></button>
                        <button type="button" class="btn btn-success waves-effect waves-light" ng-click="inProcessAttendance2()"><?php echo lang('lbl_save') ?></button>
                    </div>
                </div>
            </div>
        </div>


        <div id="confirmModalP" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><?php echo lang('att_confirmation') ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <p><?php echo lang('attendance_confirmation') ?></p>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" data-toggle="modal" data-target="#attendanceModal"><?php echo lang('btn_cancel') ?></button>
                        <button type="button" class="btn btn-success waves-effect waves-light"  ng-click="savePendingAttendance2(stdAttendanceFormP.$valid)"><?php echo lang('lbl_save') ?></button>
                    </div>
                </div>
            </div>
        </div>


        <div id="attendanceModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><?php echo lang('lbl_student_attendance'); ?></div>
                        <div class="panel-body">
                            
                            
                            <div class="white-box well" id="att_search_filter">
                                <form class="form-material" name="attFilterForm" ng-submit="onSubmit(attFilterForm.$valid)" novalidate="">
                                    <div class="row">
                                        <div class="col-md-4" id="attFilterClasses">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                                <select class="form-control" ng-model="filterModel.class_id" ng-init="initClasses()" ng-change="initBatches(filterModel.class_id)" required="">
                                                    <span>{{classes}}</span>
                                                    <option value=""><?php echo lang('select_course') ?></option>
                                                    <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4" id="attFilterBatches">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_batch') ?></label>
                                                <select class="form-control" ng-model="filterModel.batch_id" required="">
                                                    <option value=""><?php echo lang('select_batch') ?></option>
                                                    <option ng-repeat="bth in batches" value="{{bth.id}}">{{bth.name}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_attendance_date') ?></label>
                                                <input type="text" ng-model="filterModel.date" required="" class="form-control mydatepicker-autoclose" placeholder="<?php echo date('d/m/Y'); ?>" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8" ng-show="students_marked!=0">
                                            <p class="error" ng-show="message"><b>{{ message }}</b></p>
                                            <p>
                                                <a href="javascript:void(0);" data-dismiss="modal" data-toggle="modal" data-target="#requestModel" ><small ng-if="action == 'draft' || action == 'not-approved' " class="text-info"><?php echo lang('lbl_request_for_edit'); ?></small></a>
                                                <small ng-if="action == 'inprocess' " ng-class="{custom_disable:action}" class="text-warning"><?php echo lang('lbl_request_in_process'); ?></small>
                                                <small ng-if="action == 'approved' " ng-class="{custom_disable:action}" class="text-success"><?php echo lang('lbl_request_for_approved'); ?></small>
                                            </p>
                                        </div>
                                        <div class="col-md-8" ng-show="students_marked==0"></div>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-primary pull-right"><?php echo lang('lbl_start_taking_attendance') ?></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!--./row-->
                            <div class="white-box" id="attStudentsTable" ng-show="students_marked[0].name">
                                <form name="stdAttendanceForm" class="form-material">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <h3><?php echo lang('lbl_student_attendance') ?> ({{selectedDate}})</h3>
                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label></label>
                                                <input type="text" ng-model="searchedValue" placeholder="<?= lang("lbl_enter_some_text_to_search"); ?>" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">

                                            <div class="table-responsive m-t-10  col-md-12">
                                                <table id="myTable" class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th><?php echo lang('lbl_rollno') ?></th>
                                                            <th><?php echo lang('lbl_name') ?></th>
                                                            <th><?php echo lang('lbl_attendance') ?></th>
                                                            <th><?php echo lang('lbl_remarks') ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr ng-show="(students_marked |filter:searchedValue).length == 0" >
                                                            <td class="text-left" colspan="5"><?= lang("no_record"); ?></td>
                                                        </tr>
                                                        <tr ng-repeat="(key, std) in students_marked | filter:searchedValue" >
                                                            <td>{{ key+1 }}</td>
                                                            <td>{{ std.rollno }}</td>
                                                            <td>{{ std.name }} <a ng-if="std.message != ''" href="javascript:void(0)" data-html="true" data-toggle="tooltip" data-placement="top" title="{{std.message}}"><i class="fa fa-info-circle"></i></a></td>
                                                            <td style="width: 400px;" ng-class="{custom_disable:disable == 'TRUE'}">
                                                                <label class="custom-control custom-radio">
                                                                    <input name="status_{{std.id}}" class="custom-control-input" ng-init="attendModel.statuss[std.id]=std.status" value="Present" ng-model="attendModel.statuss[std.id]" type="radio">
                                                                    <span class="custom-control-indicator"></span>
                                                                    <span class="custom-control-description text-success"><?php echo lang('lbl_present') ?></span>
                                                                </label>
                                                                <label class="custom-control custom-radio">
                                                                    <input name="status_{{std.id}}" class="custom-control-input" ng-init="attendModel.statuss[std.id]=std.status" ng-model="attendModel.statuss[std.id]" value="Late" type="radio">
                                                                    <span class="custom-control-indicator"></span>
                                                                    <span class="custom-control-description text-danger"><?php echo lang('lbl_late') ?></span>
                                                                </label>
                                                                <label class="custom-control custom-radio">
                                                                    <input name="status_{{std.id}}" class="custom-control-input" ng-init="attendModel.statuss[std.id]=std.status" ng-model="attendModel.statuss[std.id]" value="Absent" type="radio">
                                                                    <span class="custom-control-indicator"></span>
                                                                    <span class="custom-control-description text-warning"><?php echo lang('lbl_absent') ?></span>
                                                                </label>
                                                                <label class="custom-control custom-radio">
                                                                    <input name="status_{{std.id}}" class="custom-control-input" ng-init="attendModel.statuss[std.id]=std.status" ng-model="attendModel.statuss[std.id]" value="Leave" type="radio">
                                                                    <span class="custom-control-indicator"></span>
                                                                    <span class="custom-control-description text-justify"><?php echo lang('lbl_leave') ?></span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <div class="form-group" ng-class="{custom_disable:disable == 'TRUE'}">
                                                                    <input class="form-control" type="text" name="comment" ng-model="attendModel.comments[std.id]" ng-value="students[$index].comment" placeholder="<?php echo lang('lbl_comment_attendance');?>">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-12" ng-show="action == 'approved'">
                                            <button data-toggle="modal"
                                            data-target="#confirmModal" class="btn btn-primary pull-right"><?php echo lang('update_attendance'); ?></button>
                                        </div>
                                    </div>


                                </form>
                            </div>
                            <div class="white-box" id="attStudentsTable" ng-show="students_pending[0].name">
                                <h2 ng-show="students_marked.length!=0"><?php echo lang('pending_attendance'); ?></h2>

                                <form name="stdAttendanceFormP" class="form-material">
                                    <div class="row">

                                        <div class="col-md-6" ng-show="students_marked.length == 0">
                                            <h3><?php echo lang('lbl_student_attendance') ?> ({{selectedDate}})</h3>
                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label></label>
                                                <input type="text" ng-model="searchedValuePending" placeholder="<?= lang("lbl_enter_some_text_to_search"); ?>" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">

                                            <div class="table-responsive m-t-10  col-md-12">
                                                <table id="myTable" class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th><?php echo lang('lbl_rollno') ?></th>
                                                            <th><?php echo lang('lbl_name') ?></th>
                                                            <th><?php echo lang('lbl_attendance') ?></th>
                                                            <th><?php echo lang('lbl_remarks') ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr ng-show="(students_pending |filter:searchedValuePending).length == 0" >
                                                            <td class="text-left" colspan="5"><?= lang("no_record"); ?></td>
                                                        </tr>
                                                        <tr ng-repeat="(key, std) in students_pending | filter:searchedValuePending">
                                                            <td>{{ key+1 }}</td>
                                                            <td>{{ std.rollno }}</td>
                                                            <td>{{ std.name }}</td>
                                                            <td style="width: 400px;">
                                                                <label class="custom-control custom-radio">
                                                                    <input name="status_{{std.id}}" class="custom-control-input" ng-init="attendModelP.statuss[std.id]=std.status" value="Present" ng-model="attendModelP.statuss[std.id]" type="radio">
                                                                    <span class="custom-control-indicator"></span>
                                                                    <span class="custom-control-description text-success"><?php echo lang('lbl_present') ?></span>
                                                                </label>
                                                                <label class="custom-control custom-radio">
                                                                    <input name="status_{{std.id}}" class="custom-control-input" ng-init="attendModelP.statuss[std.id]=std.status" ng-model="attendModelP.statuss[std.id]" value="Late" type="radio">
                                                                    <span class="custom-control-indicator"></span>
                                                                    <span class="custom-control-description text-danger"><?php echo lang('lbl_late') ?></span>
                                                                </label>
                                                                <label class="custom-control custom-radio">
                                                                    <input name="status_{{std.id}}" class="custom-control-input" ng-init="attendModelP.statuss[std.id]=std.status" ng-model="attendModelP.statuss[std.id]" value="Absent" type="radio">
                                                                    <span class="custom-control-indicator"></span>
                                                                    <span class="custom-control-description text-warning"><?php echo lang('lbl_absent') ?></span>
                                                                </label>
                                                                <label class="custom-control custom-radio">
                                                                    <input name="status_{{std.id}}" class="custom-control-input" ng-init="attendModelP.statuss[std.id]=std.status" ng-model="attendModelP.statuss[std.id]" value="Leave" type="radio">
                                                                    <span class="custom-control-indicator"></span>
                                                                    <span class="custom-control-description text-justify"><?php echo lang('lbl_leave') ?></span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <div class="form-group">
                                                                    <input class="form-control" type="text" name="comment" ng-model="attendModelP.comments[std.id]" ng-value="students[$index].comment" placeholder="<?php echo lang('lbl_comment_attendance');?>">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <button data-dismiss="modal" data-toggle="modal"
                                            data-target="#confirmModalP" class="btn btn-primary pull-right"><?php echo lang('save_attendance') ?></button>
                                        </div>
                                    </div>


                                </form>
                            </div>

                            <div class="white-box" id="attStudentsTable" ng-show="students_marked.length==0 && students_pending.length==0">
                                <div class="row">
                                    <div class="col-md-12 text-danger"><?php echo lang('no_record') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang('lbl_close') ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<script type="text/javascript">
    $(document).ready(function(){




      $('.textarea_editor').wysihtml5();
  })
</script>
<?php include(APPPATH . "views/inc/footer.php"); ?>
<script src='assets/fullcalendar/js/main.php'></script>