<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

        <?php
            $ci = & get_instance();
            $arr = $ci->session->userdata("userdata")['persissions'];
            $array = json_decode($arr);
            if (isset($array)) {
                $app_student = 0;
                $app_employee = 0;
                $app_studyplan = 0;
                $app_marksheet = 0;
                $permission_error = 1;
                foreach ($array as $key => $value) {
                    if (in_array('applications-student', array($value->permission)) && $value->val == 'true') {
                        $app_student = 1;
                        $permission_error = 0;
                    }
                    if (in_array('applications-employee', array($value->permission)) && $value->val == 'true') {
                        $app_employee = 1;
                        $permission_error = 0;
                    }
                    if (in_array('applications-studyplan', array($value->permission)) && $value->val == 'true') {
                        $app_studyplan = 1;
                        $permission_error = 0;
                    }
                    if (in_array('applications-marksheet', array($value->permission)) && $value->val == 'true') {
                        $app_marksheet = 1;
                        $permission_error = 0;
                    }
                }

            }
            ?>
<!-- Page Content -->
<style type="text/css">      
    .disabledbutton {
    pointer-events: none;
    opacity: 0.8;
}</style>
<div id="page-wrapper">
    <div class="container-fluid" ng-controller="appCtrl">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?= lang('all_applications'); ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="dashboard/"><?= lang('applications'); ?></a></li>
                    <li class="active"><?= lang('all_applications'); ?></li>
                </ol>
            </div>

            <!-- /.col-lg-12 -->
        </div>

        <!-- Exemption Modal start -->


        <div id="exemptionModel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width:1100px">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title"><?php echo "Details"; ?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="white-box" id="exemptionStudentTable">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-2 text-center visible-xs">
                                        <img ng-src="uploads/user/{{appViewModel.avatar}}" class="thumb-lg img-circle" alt="student-img">
                                    </div>
                                    <div class="col-md-2 text-center hidden-xs">
                                        <img ng-src="uploads/user/{{appViewModel.avatar}}" class="thumb-lg img-circle" alt="student-img">
                                    </div>
                                        <div class="row" id="exemption_table">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="table-responsive m-t-10  col-md-12">
                                                        <table id="myTable" style="width:100%; border-spacing: 5px; border-collapse: separate;">
                                                                <th><?= lang("lbl_name"); ?></th>
                                                                <td><u>{{appViewModel.name}}</u></td>
                                                                <th><?= lang("lbl_class"); ?></th>
                                                                <td><u>{{appViewModel.class_name}}</u></td>
                                                            </tr>
                                                            <tr>
                                                                <th><?= lang("father_name"); ?></th>
                                                                <td><u>{{appViewModel.guardian_name}}</u></td>
                                                                <th><?= lang("imp_std_section"); ?></th>
                                                                <td><u>{{appViewModel.batch_name}}</u></td>
                                                            </tr>
                                                            <tr>
                                                                <th><?= lang("lbl_phone_number"); ?></th>
                                                                <td><u>{{appViewModel.mobile_phone}}</u></td>
                                                                <th><?= lang("lbl_rollno"); ?></th>
                                                                <td><u>{{appViewModel.rollno}}</u></td>
                                                            </tr>
                                                            <tr>
                                                                <th><?= lang("lbl_discount"); ?></th>
                                                                <td><u>{{appViewModel.discount_name}}</u></td>
                                                            </tr>
                                                            <tr><th><?= lang("plc_school_name"); ?></th><td colspan="1"><u><?php echo $this->session->userdata("userdata")["sh_name"]; ?></u></td></tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><br>
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12 table-responsive">
                                                     <form name="marksheetForm" ng-class="{custom_disable:disable == 'TRUE'}">
                                                        <table id="myTable" class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th><?php echo lang('fee_type') ?></th>
                                                                    <th><?php echo lang('lbl_discounted_amount') ?></th>
                                                                    <th><?php echo lang('discounted_amount') ?></th>
                                                                    <th><?php echo lang('exemption_amount') ?></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td style="padding: 5px;">{{appViewModel.feetype_name}}</td>
                                                                    <td style="padding: 5px;">{{appViewModel.fee_amount}}</td>
                                                                    <td style="padding: 5px;">{{appViewModel.discounted_amount}}</td>
                                                                    <td style="padding: 5px;">{{appViewModel.exemption_amount}}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        </form>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>


        <!-- Exemption Modal Ends  -->

        <!-- attenance modal content -->
        <div id="attmodal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width:1100px">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel"><?php echo lang('attendance_detail'); ?></h4><h5><?php echo lang('lbl_date'); ?>: {{selectedDate}}</h5>
                        <p><strong><?php echo lang('lbl_class'); ?>:</strong><span>&nbsp;&nbsp;{{students_marked[0].class_name}}</span> &nbsp;&nbsp;&nbsp;<strong><?php echo lang('lbl_batch'); ?>:</strong>&nbsp;&nbsp;<span>{{students_marked[0].batch_name}}</span></p>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive" id="attStudents_markedTable" ng-show="students_marked[0].name">
                            <form name="stdAttendanceForm" class="form-material" ng-submit="saveAttendance(stdAttendanceForm.$valid)" ng-class="{custom_disable:disable == 'TRUE'}">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-default table-bordered">
                                            <tr>
                                                <th>#</th>
                                                <th><?php echo lang('imp_std_roll_no') ?></th>
                                                <th><?php echo lang('lbl_name') ?></th>
                                                <th><?php echo lang('lbl_attendance') ?></th>
                                            </tr>
                                            <tr ng-repeat="(key, std) in students_marked" ng-style="{'background-color': '#f8f8f8'} ">
                                                <td>{{ key+1 }}</td>
                                                <td>{{ std.rollno }}</td>
                                                <td>{{ std.name }}</td>
                                                <td style="width: 400px;">
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
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /. attendance modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- Large modal -->

        <!-- <div id="slybuss_modal_request" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div id="calendar"></div>
            </div>
          </div>
        </div> -->
        <div class="modal fade" id="slybuss_modal_request" role="dialog">
            <div class="modal-dialog modal-lg" style="max-width:1100px">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><?php echo lang('syllabus_detail') ?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div id="calendar"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('lbl_close') ?></button>
                    </div>
                </div>
                <!-- End Modal content-->
            </div>
        </div>
        <!-- Small modal -->
         <!-- /.modal marksheet -->
        <div id="marksheetModel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width:1100px">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title"><?php echo "Details"; ?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="white-box" id="marksStudentsTable" ng-show="students[0].name">
                            <div class="row">
                                <div class="col-md-12">
                                        <div class="row" id="marksheet_table">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="table-responsive m-t-10  col-md-12">
                                                        <table id="myTable" style="width:100%; border-spacing: 5px; border-collapse: separate;">
                                                                <th><?= lang("lbl_class"); ?></th>
                                                                <td><u>{{printing_details.class_name}}</u></td>
                                                                <th><?= lang("lbl_batch"); ?></th>
                                                                <td><u>{{printing_details.batch_name}}</u></td>
                                                            </tr>
                                                            <tr>
                                                                <th><?= lang("lbl_subject"); ?></th>
                                                                <td><u>{{printing_details.subject_name}}</u></td>
                                                                <th><?= lang("lbl_exam_session"); ?></th>
                                                                <td><u>{{printing_details.exam_name}}</u></td>
                                                            </tr>
                                                            <tr>
                                                                <th><?= lang("lbl_teacher"); ?></th>
                                                                <td><u>{{printing_details.teacher_name}}</u></td>
                                                                <th><?= lang("lbl_exam_date"); ?></th>
                                                                <td><u>{{printing_details.exam_date}}</u></td>
                                                            </tr>
                                                            <tr>
                                                                <th><?= lang("start_time"); ?></th>
                                                                <td><u>{{printing_details.start_time}}</u></td>
                                                                <th><?= lang("end_time"); ?></th>
                                                                <td><u>{{printing_details.end_time}}</u></td>
                                                            </tr>
                                                            <tr>
                                                                <th><?= lang("total_marks"); ?></th>
                                                                <td><u>{{printing_details.total_marks}}</u></td>
                                                                <th><?= lang("lbl_passing_marks"); ?></th>
                                                                <td><u>{{printing_details.passing_marks}}</u></td>
                                                            </tr>
                                                            <tr><th><?= lang("plc_school_name"); ?></th><td colspan="1"><u><?php echo $this->session->userdata("userdata")["sh_name"]; ?></u></td></tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-12 table-responsive">
                                                         <form name="marksheetForm" ng-class="{custom_disable:disable == 'TRUE'}">
                                                            <table id="myTable" class="table table-striped table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Sr#.</th>
                                                                        <th><?php echo lang('imp_std_roll_no') ?></th>
                                                                        <th><?php echo lang('lbl_avatar') ?></th>
                                                                        <th><?php echo lang('lbl_name') ?></th>
                                                                        <th><?php echo lang('obtained_marks') ?></th>
                                                                        <th><?php echo lang('lbl_remarks') ?></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr ng-repeat="(key, std) in students">
                                                                        <td style="padding: 5px;">{{ key+1 }}</td>
                                                                        <td style="padding: 5px;">{{ std.rollno }}</td>
                                                                        <td style="padding: 5px;"><span class="round"><img src="<?php echo base_url(); ?>uploads/user/{{ std.student_avatar }}" width="30px" alt="student-profile"/></span></td>
                                                                        <td style="padding: 5px;">{{ std.name }}</td>
                                                                        <td style="padding:5px; width: 200px;">
                                                                            <div class="form-group" style="margin-bottom: 0;">
                                                                                <input type="number" required="" min="0" max="{{printing_details.total_marks}}" ng-model="std.obtained_marks" ng-class="{hideBorder:disable == 'TRUE'}" id="id_{{key}}" ng-keypress="moveNext($event,'remarks_',key)" class="form-control"/>
                                      
                                                                        </td>
                                                                        <td style="padding:5px; width: 220px;">
                                                                            <input type="text" ng-model="remarks" ng-value="std.remarks" id="remarks_{{key}}" class="form-control" ng-class="{hideBorder:disable == 'TRUE'}" ng-keypress="moveNext($event,'id_',(key+1))"/>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            </form>
                                                        </div>
                                                    
                                                </div>
                                            </div>
                                        </div>


                           
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /. marksheet modal-content -->
            </div>
            <!-- /.modal-dialoge -->
        </div>

        <!--employee attenance modal content -->
        <div id="attmodal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width:1100px">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel"><?php echo lang('attendance_detail'); ?></h4>
                        <h5><?php echo lang('lbl_date'); ?>: {{selectedDate}}</h5>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive white-box" id="attEmployeeTable" ng-show="employees[0].name">
                            <form name="empAttendanceForm" class="form-material custom_disable">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-default table-bordered">
                                            <tr>
                                                <th>#</th>
                                                <th><?php echo lang('lbl_name') ?></th>
                                                <th><?php echo lang('lbl_designation') ?></th>
                                                <th><?php echo lang('lbl_attendance') ?></th>
                                            </tr>
                                            <tr ng-repeat="(key, emp) in employees" ng-class="{custom_disable:emp.marked == 'yes' && disable == 'TRUE'}" ng-style="emp.marked == 'yes' && {'background-color': '#f8f8f8'} ">
                                                <td>{{ key+1 }}</td>
                                                <td>{{ emp.name }}</td>
                                                <td>{{emp.job_title}}</td>
                                                <td style="width: 400px;">
                                                    <label class="custom-control custom-radio">
                                                        <input name="status_{{emp.id}}" class="custom-control-input" ng-init="attendModel.statuss[emp.id]=emp.status" value="Present" ng-model="attendModel.statuss[emp.id]" type="radio">
                                                        <span class="custom-control-indicator"></span>
                                                        <span class="custom-control-description text-success"><?php echo lang('lbl_present') ?></span>
                                                    </label>
                                                    <label class="custom-control custom-radio">
                                                        <input name="status_{{emp.id}}" class="custom-control-input" ng-init="attendModel.statuss[emp.id]=emp.status" ng-model="attendModel.statuss[emp.id]" value="Late" type="radio">
                                                        <span class="custom-control-indicator"></span>
                                                        <span class="custom-control-description text-danger"><?php echo lang('lbl_late') ?></span>
                                                    </label>
                                                    <label class="custom-control custom-radio">
                                                        <input name="status_{{emp.id}}" class="custom-control-input" ng-init="attendModel.statuss[emp.id]=emp.status" ng-model="attendModel.statuss[emp.id]" value="Absent" type="radio">
                                                        <span class="custom-control-indicator"></span>
                                                        <span class="custom-control-description text-warning"><?php echo lang('lbl_absent') ?></span>
                                                    </label>
                                                    <label class="custom-control custom-radio">
                                                        <input name="status_{{emp.id}}" class="custom-control-input" ng-init="attendModel.statuss[emp.id]=emp.status" ng-model="attendModel.statuss[emp.id]" value="Leave" type="radio">
                                                        <span class="custom-control-indicator"></span>
                                                        <span class="custom-control-description text-justify"><?php echo lang('lbl_leave') ?></span>
                                                    </label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </form>
                        </div>   
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info waves-effect" data-dismiss="modal"><?php echo lang('lbl_calendar_close') ?></button>
                    </div>
                </div>
                <!-- /.employee attendance modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <div class="hint row"><?php echo lang('all_applications_help'); ?></div>

        
         <?php if (count($requests) > 0) { 
            $att = $emp_att = $marksheet = $syllabus = false;
            foreach ($requests as $req) {
                if($req->type =='attendance' ){
                    $att = true;
                }
                if($req->type =='emp_attendance'){
                    $emp_att = true;
                }
                if($req->type =='mark_sheet'){
                    $marksheet = true;
                }
                if($req->type == 'syllabus'){
                    $syll = true;
                }
                if($req->type == 'fee_exemption') {
                    $exemption = true;
                }
            }
        } ?>                    
    
    

    <div class="row">
         
    <?php if($this->session->userdata('userdata')['role_id'] == 1 || $this->session->userdata('userdata')['role_id'] == 4){?>                   
        <div class="col-md-12 white-box" >
            <h5><?php echo lang("student_attendance_requests"); ?></h5>
            <div class="row table-responsive" style="max-height: 400px; overflow-y: auto;" >             
                <?php if (isset($att) && $att == true) { ?>
                    <table  id="myTable" class="table table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th><?php echo lang('sr_no'); ?></th>
                                <th><?php echo lang('req_from'); ?></th>
                                <th><?php echo lang('lbl_type'); ?></th>
                                <th><?php echo lang('lbl_status'); ?></th>
                                <th><?php echo lang('created_date'); ?></th>
                               <!--  <th><?php echo lang('approved_date'); ?></th> -->
                                <th><?php echo lang('lbl_class'); ?></th>
                                <th><?php echo lang('lbl_batch'); ?></th>
                                <?php if ($this->session->userdata('userdata')['role_id'] == 1 || in_array("attendance",login_user()->req_types)) { ?><th><?php echo lang("lbl_action"); ?></th><?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($requests as $req) { ?>
                                 <?php if ($req->type == "attendance") { ?>
                                <tr>
                                    <td><?php echo rand(0, 100000) . $req->id; ?></td>
                                    <td><?php echo $req->username; ?></td>
                                   <td><?php echo lang("$req->type");?></td>
                                    <td><?php echo lang("$req->status");?>  
                                        <?php if($this->session->userdata("userdata")["role_id"] == 4 && isset($req->response) && $req->response != null){?>
                                            <a href="javascript:void(0);" data-toggle="modal" ng-click="responseReason(<?php echo $req->id; ?>)" data-target="#requestReasonModel" class="m-2"><i class="fa fa-question-circle" aria-hidden="true"></i></span></a>
                                        <?php } ?>  
                                    </td>
                                    <td><?php echo $req->request_time; ?></td>
                                   <!--  <td><?php echo $req->updated_at; ?></td> -->
                                    <td><?php echo $req->classname; ?></td>
                                    <td><?php echo $req->batchname; ?></td>
                                    <?php if ($this->session->userdata('userdata')['role_id'] == 1 || in_array("attendance",login_user()->req_types) ){ ?>
                                        <td width="280">
                                            <?php if ($req->type == "attendance") { ?>
                                            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#attmodal" ng-click="viewAttendance('<?php echo $req->date; ?>',<?php echo $req->class_id; ?>,<?php echo $req->batch_id; ?>)"><?php echo lang('lbl_view') ?></button>

                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#responseModel" ng-click="appNotification('approved','<?php echo $req->notification_id; ?>','<?php echo $req->id; ?>','<?php echo $req->type; ?>','<?php echo $req->date; ?>')" class="btn btn-sm btn-success"><?php echo lang('lbl_approve') ?></a>
                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#responseModel" ng-click="appNotification('not-approved','<?php echo $req->notification_id; ?>','<?php echo $req->id; ?>','<?php echo $req->type; ?>','<?php echo $req->date; ?>')" class="btn btn-sm btn-danger"><?php echo lang('lbl_reject') ?></a>
                                                <a href="javascript:void(0);" data-toggle="modal" ng-click="resquestReason(<?php echo $req->id; ?>)" data-target="#requestReasonModel" class="m-2"><i class="fa fa-question-circle" aria-hidden="true"></i></span></a>
                                           
                                                <?php if ($req->status == 'approved') { ?>
                                                    <span class="text-success"><?php echo strtoupper($req->status); ?></span>
                                                <?php } else if ($req->status == 'not-approved') { ?>
                                                    <span class="text-danger"><?php echo strtoupper($req->status); ?></span>

                                                <?php } ?>
                                            <?php } ?>
                                        </td>
                                    <?php } ?>
                                     <?php } ?>
                                </tr>
                               <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <span class="text-danger"><?php echo lang("no_record"); ?></span>
                <?php } ?>
            </div>
        </div>
        <?php }?> 
        <!-- attendance request -->
        <?php if($this->session->userdata('userdata')['role_id'] == 1 || $this->session->userdata('userdata')['role_id'] == 4 ){?>
        <div class="col-md-12 white-box">
            <h5><?php echo lang("employee_attendance_requests"); ?></h5>
            <div class="row table-responsive" style="max-height: 400px; overflow-y: auto;">             
                <?php if (isset($emp_att) && $emp_att == true) { ?>
                    <table  id="myTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th><?php echo lang('sr_no'); ?></th>
                                <th><?php echo lang('req_from'); ?></th>
                                <th><?php echo lang('lbl_type'); ?></th>
                                <th><?php echo lang('lbl_status'); ?></th>
                                <th><?php echo lang('created_date'); ?></th>
                                <!-- <th><?php echo lang('approved_date'); ?></th> -->
                                <th><?php echo lang('title_department'); ?></th>
                                <th><?php echo lang('title_category'); ?></th>
                                <?php if ($this->session->userdata('userdata')['role_id'] == 1 || in_array("emp_attendance",login_user()->req_types) ) { ?><th><?php echo lang("lbl_action"); ?></th><?php } ?>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php foreach ($requests as $req) { ?>
                                <?php if ($req->type == "emp_attendance") { ?>
                                <tr>
                                    <td><?php echo rand(0, 100000) . $req->id; ?></td>
                                    <td><?php echo $req->username; ?></td>
                                    <td><?php echo lang("$req->type");?></td>
                                    <td><?php echo lang("$req->status");?>  
                                        <?php if($this->session->userdata("userdata")["role_id"] == 4 && $req->response != null){?>
                                            <a href="javascript:void(0);" data-toggle="modal" ng-click="responseReason(<?php echo $req->id; ?>)" data-target="#requestReasonModel" class="m-2"><i class="fa fa-question-circle" aria-hidden="true"></i></span></a>
                                        <?php } ?>  
                                    </td>
                                    <td><?php echo $req->request_time; ?></td>
                                    <!-- <td><?php echo $req->updated_at; ?></td> -->
                                    <td><?php echo $req->depart_name; ?></td>
                                    <td><?php echo $req->category; ?></td>
                                    <?php if ($this->session->userdata('userdata')['role_id'] == 1 || in_array("emp_attendance",login_user()->req_types)) { ?>
                                        <td width="280">
                                            
                                                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#attmodal1" ng-click="viewEmpAttendance('<?php echo $req->date; ?>', '<?php echo $req->department_id; ?>', '<?php echo $req->category_id; ?>')"><?php echo lang('lbl_view') ?></button>
                                               
                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#responseModel" ng-click="appNotification('approved','<?php echo $req->notification_id; ?>','<?php echo $req->id; ?>','<?php echo $req->type; ?>','<?php echo $req->date; ?>')" class="btn btn-sm btn-success"><?php echo lang('lbl_approve') ?></a>
                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#responseModel" ng-click="appNotification('not-approved','<?php echo $req->notification_id; ?>','<?php echo $req->id; ?>','<?php echo $req->type; ?>','<?php echo $req->date; ?>')" class="btn btn-sm btn-danger"><?php echo lang('lbl_reject') ?></a>
                                                <a href="javascript:void(0);" data-toggle="modal" ng-click="resquestReason(<?php echo $req->id; ?>)" data-target="#requestReasonModel" class="m-2"><i class="fa fa-question-circle" aria-hidden="true"></i></span></a>
                            
                                                <?php if ($req->status == 'approved') { ?>
                                                    <span class="text-success"><?php echo strtoupper($req->status); ?></span>
                                                <?php } else if ($req->status == 'not-approved') { ?>
                                                    <span class="text-danger"><?php echo strtoupper($req->status); ?></span>

                                              <?php }?>
                                        </td>
                                         <?php }?>
                                    <?php } ?>
                                </tr>
                                <?php }  ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <span class="text-danger"><?php echo lang("no_record"); ?></span>
                <?php } ?>
            </div>
        </div>
        <?php }?> 
        <!-- marksheet request -->
        <?php if($this->session->userdata('userdata')['role_id'] == 1 || $this->session->userdata('userdata')['role_id'] == 4){?>
        <div class="col-md-12 white-box">
            <h5><?php echo lang("marksheet_requests"); ?></h5>
            <div class="row table-responsive" style="max-height: 400px; overflow-y: auto;">             
                <?php if (isset($marksheet) && $marksheet == true) { ?>
                    <table  id="myTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th><?php echo lang('sr_no'); ?></th>
                                <th><?php echo lang('req_from'); ?></th>
                                <th><?php echo lang('lbl_type'); ?></th>
                                <th><?php echo lang('lbl_status'); ?></th>
                                <th><?php echo lang('created_date'); ?></th>
                                <!-- <th><?php echo lang('approved_date'); ?></th> -->
                                <th><?php echo lang('lbl_class'); ?></th>
                                <th><?php echo lang('lbl_batch'); ?></th>
                                <th><?php echo lang('lbl_subject'); ?></th>
                                <th><?php echo lang('lbl_exam'); ?></th>
                                <?php if ($this->session->userdata('userdata')['role_id'] == 1 || in_array("mark_sheet",login_user()->req_types) )  { ?><th><?php echo lang("lbl_action"); ?></th><?php } ?>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php foreach ($requests as $req) { ?>
                                <?php if ($req->type == "mark_sheet") { ?>
                                <tr>
                                    <td><?php echo rand(0, 100000) . $req->id; ?></td>
                                    <td><?php echo $req->username; ?></td>
                                    <td><?php echo lang("$req->type");?></td>
                                    <td><?php echo lang("$req->status");?>  
                                        <?php if($this->session->userdata("userdata")["role_id"] == 4 && $req->response != null){?>
                                            <a href="javascript:void(0);" data-toggle="modal" ng-click="responseReason(<?php echo $req->id; ?>)" data-target="#requestReasonModel" class="m-2"><i class="fa fa-question-circle" aria-hidden="true"></i></span></a>
                                        <?php } ?>  
                                    </td>
                                    <td><?php echo $req->request_time; ?></td>
                                    <!-- <td><?php echo $req->updated_at; ?></td> -->
                                    <td><?php echo $req->classname; ?></td>
                                    <td><?php echo $req->batchname; ?></td>
                                    <td><?php echo $req->subjectname; ?></td>
                                    <td><?php echo $req->title; ?></td>
                                    <?php if ($this->session->userdata('userdata')['role_id'] == 1 || in_array("mark_sheet",login_user()->req_types)) { ?>
                                        <td width="280">
                                            
                                                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#marksheetModel" ng-click="viewMarkSheet(<?php echo $req->class_id ?>,<?php echo $req->batch_id ?>,<?php echo $req->subject_id ?>,<?php echo $req->exam_detail_id; ?>,'<?php echo $req->subjectname ?>', <?php echo $this->session->userdata('userdata')['academic_year']; ?>)"><?php echo lang('lbl_view') ?></button>

                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#responseModel" ng-click="appNotification('approved','<?php echo $req->notification_id; ?>','<?php echo $req->id; ?>','<?php echo $req->type; ?>','<?php echo $req->date; ?>')" class="btn btn-sm btn-success"><?php echo lang('lbl_approve') ?></a>

                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#responseModel" ng-click="appNotification('not-approved','<?php echo $req->notification_id; ?>','<?php echo $req->id; ?>','<?php echo $req->type; ?>','<?php echo $req->date; ?>')" class="btn btn-sm btn-danger"><?php echo lang('lbl_reject') ?></a>

                                                <a href="javascript:void(0);" data-toggle="modal" ng-click="resquestReason(<?php echo $req->id; ?>)" data-target="#requestReasonModel" class="m-2"><i class="fa fa-question-circle" aria-hidden="true"></i></span></a>
                                         
                                                <?php if ($req->status == 'approved') { ?>
                                                    <span class="text-success"><?php echo strtoupper($req->status); ?></span>
                                                <?php } else if ($req->status == 'not-approved') { ?>
                                                    <span class="text-danger"><?php echo strtoupper($req->status); ?></span>

                                                <?php } ?>
                                            <?php } ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                                 <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <span class="text-danger"><?php echo lang("no_record"); ?></span>
                <?php } ?>
            </div>
        </div>
        <?php }?> 
        <!-- syllabus request  -->
        
        <?php if($this->session->userdata('userdata')['role_id'] == 1 || $this->session->userdata('userdata')['role_id'] == 4){?>
        <div class="col-md-12 white-box">
            <h5><?php echo lang("syllabus_requests"); ?></h5>
            <div class="row table-responsive" style="max-height: 400px; overflow-y: auto;">             
                <?php if (isset($syll) && $syll == true) { 
                 ?>
                    <table  id="myTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th><?php echo lang('sr_no'); ?></th>
                                <th><?php echo lang('req_from'); ?></th>
                                <th><?php echo lang('lbl_type'); ?></th>
                                <th><?php echo lang('lbl_status'); ?></th>
                                <th><?php echo lang('created_date'); ?></th>
                                <!-- <th><?php echo lang('approved_date'); ?></th> -->
                                <th><?php echo lang('lbl_class'); ?></th>
                                <th><?php echo lang('lbl_batch'); ?></th>
                                <th><?php echo lang('lbl_subject'); ?></th>
                                <?php if ($this->session->userdata('userdata')['role_id'] == 1 || in_array("syllabus",login_user()->req_types) )  { ?><th><?php echo lang("lbl_action"); ?></th><?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($requests as $req) { ?>
                                <?php if ($req->type == "syllabus") { ?>
                                <tr>
                                    <td><?php echo rand(0, 100000) . $req->id; ?></td>
                                    <td><?php echo $req->username; ?></td>
                                    <td><?php echo lang("$req->type");?></td>
                                    <td><?php echo lang("$req->status");?> 
                                        <?php if($this->session->userdata("userdata")["role_id"] == 4 && $req->response != null){?>
                                            <a href="javascript:void(0);" data-toggle="modal" ng-click="responseReason(<?php echo $req->id; ?>)" data-target="#requestReasonModel" class="m-2"><i class="fa fa-question-circle" aria-hidden="true"></i></span></a>
                                        <?php } ?>  
                                    </td>
                                    <td><?php echo $req->request_time; ?></td>
                                    <!-- <td><?php echo $req->updated_at; ?></td> -->
                                    <td><?php echo $req->classname; ?></td>
                                    <td><?php echo $req->batchname; ?></td>
                                    <td><?php echo $req->subjectname; ?></td>
                                    <?php if ($this->session->userdata('userdata')['role_id'] == 1 || in_array("syllabus",login_user()->req_types)) { ?>
                                        <td width="280">
                                            <?php if ($req->status == 'inprocess') { ?>
                                                <?php if ($req->type == "syllabus") { ?>
                                                    <?php if($req->syllabus_state == 'old'){?>
                                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#myModal" ng-click="onView(<?php echo $req->class_id ?>,<?php echo $req->batch_id ?>,<?php echo $req->subject_id ?>,'<?php echo $req->subjectname ?>','<?php echo $req->syllabus_state ?>' )"><?php echo lang('lbl_view') ?></button>
                                                    <?php }else if($req->syllabus_state == 'new'){?>
                                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#slybuss_modal_request" ng-click="onView(<?php echo $req->class_id ?>,<?php echo $req->batch_id ?>,<?php echo $req->subject_id ?>,'<?php echo $req->subjectname ?>','<?php echo $req->syllabus_state ?>')"><?php echo lang('lbl_view') ?></button>
                                                    <?php } ?>
                                                <?php } ?>
                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#responseModel" ng-click="appNotification('approved','<?php echo $req->notification_id; ?>','<?php echo $req->id; ?>','<?php echo $req->type; ?>','<?php echo $req->date; ?>')" class="btn btn-sm btn-success"><?php echo lang('lbl_approve') ?></a>
                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#responseModel" ng-click="appNotification('not-approved','<?php echo $req->notification_id; ?>','<?php echo $req->id; ?>','<?php echo $req->type; ?>','<?php echo $req->date; ?>')" class="btn btn-sm btn-danger"><?php echo lang('lbl_reject') ?></a>
                                                <a href="javascript:void(0);" data-toggle="modal" ng-click="resquestReason(<?php echo $req->id; ?>)" data-target="#requestReasonModel" class="m-2"><i class="fa fa-question-circle" aria-hidden="true"></i></span></a>

                                            <?php } elseif ($req->status == 'edit-request') { ?>
                                                <?php if ($req->type == "syllabus") { ?>
                                                    <?php if($req->syllabus_state == 'old'){?>
                                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#myModal" ng-click="onView(<?php echo $req->class_id ?>,<?php echo $req->batch_id ?>,<?php echo $req->subject_id ?>,'<?php echo $req->subjectname ?>','<?php echo $req->syllabus_state ?>')"><?php echo lang('lbl_view') ?></button>
                                                    <?php }else if($req->syllabus_state == 'new'){?>
                                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#slybuss_modal_request" ng-click="onView(<?php echo $req->class_id ?>,<?php echo $req->batch_id ?>,<?php echo $req->subject_id ?>,'<?php echo $req->subjectname ?>','<?php echo $req->syllabus_state ?>')"><?php echo lang('lbl_view') ?></button>
                                                    <?php } ?>
                                                <?php } ?>
                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#responseModel" ng-click="appNotification('draft','<?php echo $req->notification_id; ?>','<?php echo $req->id; ?>','<?php echo $req->type; ?>','<?php echo $req->date; ?>')" class="btn btn-sm btn-success"><?php echo lang('lbl_allow') ?></a>

                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#responseModel" ng-click="appNotification('approved','<?php echo $req->notification_id; ?>','<?php echo $req->id; ?>','<?php echo $req->type; ?>','<?php echo $req->date; ?>')" class="btn btn-sm btn-danger"><?php echo lang('lbl_disallow') ?></a>

                                                <a href="javascript:void(0);" data-toggle="modal" ng-click="resquestReason(<?php echo $req->id; ?>)" data-target="#requestReasonModel" class="m-2"><i class="fa fa-question-circle" aria-hidden="true"></i></span></a>

                                            <?php } else { ?>
                                                <?php if ($req->status == 'approved') { ?>
                                                    <span class="text-success"><?php echo strtoupper($req->status); ?></span>
                                                <?php } else if ($req->status == 'not-approved') { ?>
                                                    <span class="text-danger"><?php echo strtoupper($req->status); ?></span>

                                                <?php } ?>
                                            <?php } ?>

                                        </td>
                                    <?php } ?>
                                </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <span class="text-danger"><?php echo lang("no_record"); ?></span>
                <?php } ?>
            </div>
        </div>
        <?php }?> 

        <!-- syllabus request  -->
        
        <?php if($this->session->userdata('userdata')['role_id'] == 1 || $this->session->userdata('userdata')['role_id'] == 4){?>
        <div class="col-md-12 white-box">
            <h5><?php echo lang('online_exam_request');?></h5>
            <div class="row table-responsive" style="max-height: 400px; overflow-y: auto;">             
                
                    <table  id="myTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th><?php echo lang('sr_no'); ?></th>
                                <th><?php echo lang('req_from'); ?></th>
                                <th><?php echo lang('lbl_type'); ?></th>
                                <!-- <th><?php echo lang('lbl_status'); ?></th> -->
                                <th><?php echo lang('created_date'); ?></th>
                                <th><?php echo lang('lbl_student');?></th>
                                <!-- <th><?php echo lang('approved_date'); ?></th> -->
                                <th><?php echo lang('lbl_class'); ?></th>
                                <th><?php echo lang('lbl_batch'); ?></th>
                                <th><?php echo lang('lbl_paper');?></th>
                                <th><?php echo lang('lbl_attempts');?></th>
                                <?php if ($this->session->userdata('userdata')['role_id'] == 1 || in_array("syllabus",login_user()->req_types) )  { ?><th><?php echo lang("lbl_action"); ?></th><?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($requests as $req) { ?>
                                <?php if ($req->type == "online_exam_edit" || $req->type == "online_exam_retake") { ?>
                                <tr>
                                    <td><?php echo rand(0, 100000) . $req->id; ?></td>
                                    <td><?php echo $req->username; ?></td>
                                    <td><?php echo lang("$req->type");?></td>
                                    <!-- <td><?php echo lang("$req->status");?>  -->
                                        <?php if($this->session->userdata("userdata")["role_id"] == 4 && $req->response != null){?>
                                            <a href="javascript:void(0);" data-toggle="modal" ng-click="responseReason(<?php echo $req->id; ?>)" data-target="#requestReasonModel" class="m-2"><i class="fa fa-question-circle" aria-hidden="true"></i></span></a>
                                        <?php } ?>  
                                    </td>
                                    <td><?php echo $req->request_time; ?></td>
                                    <td><?php echo $req->student_name;?> <?php echo $req->rollno;?></td>
                                    <!-- <td><?php echo $req->updated_at; ?></td> -->
                                    <td><?php echo $req->classname; ?></td>
                                    <td><?php echo $req->batchname; ?></td>
                                    <td><?php echo $req->paper_name; ?></td>
                                    <td><?php echo $req->attempts; ?></td>
                                    <td width="280">

                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#responseModel" ng-click="appNotification('approved','<?php echo $req->notification_id; ?>','<?php echo $req->id; ?>','<?php echo $req->type; ?>','<?php echo $req->date; ?>')" class="btn btn-sm btn-success"><?php echo lang('lbl_approve') ?></a>
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#responseModel" ng-click="appNotification('not-approved','<?php echo $req->notification_id; ?>','<?php echo $req->id; ?>','<?php echo $req->type; ?>','<?php echo $req->date; ?>')" class="btn btn-sm btn-danger"><?php echo lang('lbl_reject') ?></a>
                                        <a href="javascript:void(0);" data-toggle="modal" ng-click="resquestReason(<?php echo $req->id; ?>)" data-target="#requestReasonModel" class="m-2"><i class="fa fa-question-circle" aria-hidden="true"></i></span></a>



                                    </td>
                                </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                
            </div>
        </div>
        <?php }?>



        <!-- fee exemption request  -->
        
        <?php if($this->session->userdata('userdata')['role_id'] == 1 || $this->session->userdata('userdata')['role_id'] == 4){?>
        <div class="col-md-12 white-box">
            <h5>Fee Exemption Requests</h5>
            <div class="row table-responsive" style="max-height: 400px; overflow-y: auto;">             
                
                    <?php if (isset($exemption) && $exemption == true) { ?>
                    <table  id="myTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th><?php echo lang('sr_no'); ?></th>
                                <th><?php echo lang('req_from'); ?></th>
                                <th><?php echo lang('lbl_type'); ?></th>
                                <th><?php echo lang('lbl_status'); ?></th>
                                <th><?php echo lang('created_date'); ?></th>
                                <!-- <th><?php echo lang('approved_date'); ?></th> -->
                                <th><?php echo lang('lbl_class'); ?></th>
                                <th><?php echo lang('lbl_batch'); ?></th>
                                <!-- <th><?php echo lang('lbl_subject'); ?></th> -->
                                <!-- <th><?php echo lang('lbl_exam'); ?></th> -->
                                <?php if ($this->session->userdata('userdata')['role_id'] == 1 || in_array("fee_exemption",login_user()->req_types) )  { ?><th><?php echo lang("lbl_action"); ?></th><?php } ?>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php foreach ($requests as $req) { ?>
                                <?php if ($req->type == "fee_exemption") { ?>
                                <tr>
                                    <td><?php echo rand(0, 100000) . $req->id; ?></td>
                                    <td><?php echo $req->username; ?></td>
                                    <td><?php echo lang("$req->type");?></td>
                                    <td><?php echo lang("$req->status");?>  
                                        <?php if($this->session->userdata("userdata")["role_id"] == 4 && $req->response != null){?>
                                            <a href="javascript:void(0);" data-toggle="modal" ng-click="responseReason(<?php echo $req->id; ?>)" data-target="#requestReasonModel" class="m-2"><i class="fa fa-question-circle" aria-hidden="true"></i></span></a>
                                        <?php } ?>  
                                    </td>
                                    <td><?php echo $req->request_time; ?></td>
                                    <!-- <td><?php echo $req->updated_at; ?></td> -->
                                    <td><?php echo $req->classname; ?></td>
                                    <td><?php echo $req->batchname; ?></td>
                                    <!-- <td><?php echo $req->subjectname; ?></td> -->
                                    <!-- <td><?php echo $req->title; ?></td> -->
                                    <?php if ($this->session->userdata('userdata')['role_id'] == 1 || in_array("fee_exemption",login_user()->req_types)) { ?>
                                        <td width="280">
                                            
                                                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#exemptionModel" ng-click="viewExemptions(<?php echo $req->feetype_id ?>,<?php echo $req->school_id ?>,<?php echo $req->student_id ?>, <?php echo $this->session->userdata('userdata')['academic_year']; ?>)"><?php echo lang('lbl_view') ?></button>

                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#responseModel" ng-click="appNotification('approved','<?php echo $req->notification_id; ?>','<?php echo $req->id; ?>','<?php echo $req->type; ?>','<?php echo $req->date; ?>')" class="btn btn-sm btn-success"><?php echo lang('lbl_approve') ?></a>

                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#responseModel" ng-click="appNotification('not-approved','<?php echo $req->notification_id; ?>','<?php echo $req->id; ?>','<?php echo $req->type; ?>','<?php echo $req->date; ?>')" class="btn btn-sm btn-danger"><?php echo lang('lbl_reject') ?></a>

                                                <a href="javascript:void(0);" data-toggle="modal" ng-click="resquestReason(<?php echo $req->id; ?>)" data-target="#requestReasonModel" class="m-2"><i class="fa fa-question-circle" aria-hidden="true"></i></span></a>
                                         
                                                <?php if ($req->status == 'approved') { ?>
                                                    <span class="text-success"><?php echo strtoupper($req->status); ?></span>
                                                <?php } else if ($req->status == 'not-approved') { ?>
                                                    <span class="text-danger"><?php echo strtoupper($req->status); ?></span>

                                                <?php } ?>
                                            <?php } ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                                 <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <span class="text-danger"><?php echo lang("no_record"); ?></span>
                <?php } ?>
                
            </div>
        </div>
        <?php }?>

    </div>



    <?php
    if($this->session->userdata('userdata')['role_id'] == 1 || $this->session->userdata('userdata')['role_id'] == 4){ 
     if(count($log_history) > 0 ){ 
            $log_std = $log_emp = $log_marksheet = $log_syllabus = false;
            foreach ($log_history as $req) {
                if($req->type =='attendance' ){
                    $log_std = true;
                }
                if($req->type =='emp_attendance'){
                    $log_emp = true;
                }
                if($req->type =='mark_sheet'){
                    $log_marksheet = true;
                }
                if($req->type =='syllabus'){
                    $log_syllabus = true;
                }
            }
        } ?>  
    <div class="row">
        <div class="col-md-12 white-box">
         <h4 class="text-center"><?php echo lang("log_request_history"); ?></h4>
                <div class="row">
                    <h5><?php echo lang("student_attendance_requests"); ?></h5>  
                    <div class="row table-responsive" style="max-height: 400px; overflow-y: auto;" >             
                    <?php if (isset($log_std) && $log_std == true) { ?>
                        <table  id="myTable" class="table table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th><?php echo lang('sr_no'); ?></th>
                                    <th><?php echo lang('req_from'); ?></th>
                                    <th><?php echo lang('response_by'); ?></th>
                                    <th><?php echo lang('lbl_type'); ?></th>
                                    <th><?php echo lang('lbl_status'); ?></th>
                                    <th><?php echo lang('created_date'); ?></th>
                                    <th><?php echo lang('approved_date'); ?></th>
                                    <th><?php echo lang('lbl_class'); ?></th>
                                    <th><?php echo lang('lbl_batch'); ?></th>
                                    <th><?php echo lang("reason"); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($log_history as $req) { ?>
                                     <?php if ($req->type == "attendance") { ?>
                                    <tr>
                                        <td><?php echo rand(0, 100000) . $req->id; ?></td>
                                        <td><?php echo $req->username; ?></td>
                                        <td><?php echo $req->response_by; ?></td>
                                        <td><?php echo lang("$req->type");?></td>
                                        <td><?php echo lang("$req->status");?>
                                            <a href="javascript:void(0);" data-toggle="modal" ng-click="responseReason(<?php echo $req->id; ?>)" data-target="#requestReasonModel" class="m-2"><i class="fa fa-question-circle" aria-hidden="true"></i></span></a>                                    </td>
                                        <td><?php echo $req->request_time; ?></td>
                                       <td><?php echo $req->updated_at; ?></td>
                                        <td><?php echo $req->classname; ?></td>
                                        <td><?php echo $req->batchname; ?></td>
                                        <td><a href="javascript:void(0);" data-toggle="modal" ng-click="resquestReason(<?php echo $req->id; ?>)" data-target="#requestReasonModel" class="m-2"><i class="fa fa-question-circle" aria-hidden="true"></i></span></a>
                                            </td>
                                       
                                        <?php } ?>
                                    </tr>
                                   <?php } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <span class="text-danger"><?php echo lang("no_record"); ?></span>
                    <?php } ?>
                </div>
            </div>
          
            <div class="row">
                <h5><?php echo lang("employee_attendance_requests"); ?></h5>  
                <div class="row table-responsive" style="max-height: 400px; overflow-y: auto;" >             
                <?php if (isset($log_emp) && $log_emp == true ) { ?>
                    <table  id="myTable" class="table table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th><?php echo lang('sr_no'); ?></th>
                                <th><?php echo lang('req_from'); ?></th>
                                <th><?php echo lang('response_by'); ?></th>
                                <th><?php echo lang('lbl_type'); ?></th>
                                <th><?php echo lang('lbl_status'); ?></th>
                                <th><?php echo lang('created_date'); ?></th>
                                <th><?php echo lang('approved_date'); ?></th>
                                <th><?php echo lang('title_department'); ?></th>
                                <th><?php echo lang('title_category'); ?></th>
                                <th><?php echo lang("reason"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($log_history as $req) { ?>
                                <?php if ($req->type == "emp_attendance") { ?>
                                <tr>
                                    <td><?php echo rand(0, 100000) . $req->id; ?></td>
                                    <td><?php echo $req->username; ?></td>
                                    <td><?php echo $req->response_by; ?></td>
                                    <td><?php echo lang("$req->type");?></td>
                                    <td><?php echo lang("$req->status");?> 
                                        <a href="javascript:void(0);" data-toggle="modal" ng-click="responseReason(<?php echo $req->id; ?>)" data-target="#requestReasonModel" class="m-2"><i class="fa fa-question-circle" aria-hidden="true"></i></span></a>                                    </td>
                                    <td><?php echo $req->request_time; ?></td>
                                    <td><?php echo $req->updated_at; ?></td>
                                    <td><?php echo $req->depart_name; ?></td>
                                    <td><?php echo $req->category; ?></td>
                                    <td><a href="javascript:void(0);" data-toggle="modal" ng-click="resquestReason(<?php echo $req->id; ?>)" data-target="#requestReasonModel" class="m-2"><i class="fa fa-question-circle" aria-hidden="true"></i></span></a>
                                    </td>
                                  
                                     <?php } ?>
                                </tr>
                               <?php } ?>
                        </tbody>
                    </table>
                    <?php } else { ?>
                        <span class="text-danger"><?php echo lang("no_record"); ?></span>
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <h5><?php echo lang("marksheet_requests"); ?></h5>  
                <div class="row table-responsive" style="max-height: 400px; overflow-y: auto;" >             
                <?php if (isset($log_marksheet) && $log_marksheet == true) { ?>
                    <table  id="myTable" class="table table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th><?php echo lang('sr_no'); ?></th>
                                <th><?php echo lang('req_from'); ?></th>
                                <th><?php echo lang('response_by'); ?></th>
                                <th><?php echo lang('lbl_type'); ?></th>
                                <th><?php echo lang('lbl_status'); ?></th>
                                <th><?php echo lang('created_date'); ?></th>
                                <th><?php echo lang('approved_date'); ?></th>
                                <th><?php echo lang('lbl_subject'); ?></th>
                                <th><?php echo lang('lbl_exam'); ?></th>
                                <th><?php echo lang("reason"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($log_history as $req) { ?>
                                <?php if ($req->type == "mark_sheet") { ?>
                                <tr>
                                    <td><?php echo rand(0, 100000) . $req->id; ?></td>
                                    <td><?php echo $req->username; ?></td>
                                    <td><?php echo $req->response_by; ?></td>
                                    <td><?php echo lang("$req->type");?></td>
                                    <td><?php echo lang("$req->status");?> 
                                        <a href="javascript:void(0);" data-toggle="modal" ng-click="responseReason(<?php echo $req->id; ?>)" data-target="#requestReasonModel" class="m-2"><i class="fa fa-question-circle" aria-hidden="true"></i></span></a>                                    </td>
                                    <td><?php echo $req->request_time; ?></td>
                                    <td><?php echo $req->updated_at; ?></td>
                                    <td><?php echo $req->subjectname; ?></td>
                                    <td><?php echo $req->title; ?></td>
                                    <td><a href="javascript:void(0);" data-toggle="modal" ng-click="resquestReason(<?php echo $req->id; ?>)" data-target="#requestReasonModel" class="m-2"><i class="fa fa-question-circle" aria-hidden="true"></i></span></a>
                                        </td>
                                    
                                     <?php } ?>
                                </tr>
                               <?php } ?>
                        </tbody>
                    </table>
                    <?php } else { ?>
                        <span class="text-danger"><?php echo lang("no_record"); ?></span>
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <h5><?php echo lang("syllabus_requests"); ?></h5>  
                <div class="row table-responsive" style="max-height: 400px; overflow-y: auto;" >             
                <?php if (isset($log_syllabus) && $log_syllabus == true) { ?>
                    <table  id="myTable" class="table table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th><?php echo lang('sr_no'); ?></th>
                                <th><?php echo lang('req_from'); ?></th>
                                <th><?php echo lang('response_by'); ?></th>
                                <th><?php echo lang('lbl_type'); ?></th>
                                <th><?php echo lang('lbl_status'); ?></th>
                                <th><?php echo lang('created_date'); ?></th>
                                <th><?php echo lang('approved_date'); ?></th>
                                <th><?php echo lang('lbl_class'); ?></th>
                                <th><?php echo lang('lbl_batch'); ?></th>
                                <th><?php echo lang('lbl_subject'); ?></th>
                                <th><?php echo lang("reason"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($log_history as $req) { ?>
                                <?php if ($req->type == "syllabus") { ?>
                                <tr>
                                    <td><?php echo rand(0, 100000) . $req->id; ?></td>
                                    <td><?php echo $req->username; ?></td>
                                    <td><?php echo $req->response_by; ?></td>
                                    <td><?php echo lang("$req->type");?></td>
                                    <td><?php echo lang("$req->status");?> 
                                        <a href="javascript:void(0);" data-toggle="modal" ng-click="responseReason(<?php echo $req->id; ?>)" data-target="#requestReasonModel" class="m-2"><i class="fa fa-question-circle" aria-hidden="true"></i></span></a>                                    </td>
                                    <td><?php echo $req->request_time; ?></td>
                                    <td><?php echo $req->updated_at; ?></td>
                                    <td><?php echo $req->classname; ?></td>
                                    <td><?php echo $req->batchname; ?></td>
                                    <td><?php echo $req->subjectname; ?></td>
                                    <td><a href="javascript:void(0);" data-toggle="modal" ng-click="resquestReason(<?php echo $req->id; ?>)" data-target="#requestReasonModel" class="m-2"><i class="fa fa-question-circle" aria-hidden="true"></i></span></a>
                                    </td>
                                    
                                    

                                     <?php } ?>
                                </tr>
                               <?php } ?>
                        </tbody>
                    </table>
                    <?php } else { ?>
                        <span class="text-danger"><?php echo lang("no_record"); ?></span>
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <h5><?php echo lang("fee_exemption_requests"); ?></h5>  
                <div class="row table-responsive" style="max-height: 400px; overflow-y: auto;" >             
                    <?php if (isset($log_std) && $log_std == true) { ?>
                    <table  id="myTable" class="table table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th><?php echo lang('sr_no'); ?></th>
                                <th><?php echo lang('req_from'); ?></th>
                                <th><?php echo lang('response_by'); ?></th>
                                <th><?php echo lang('lbl_type'); ?></th>
                                <th><?php echo lang('lbl_status'); ?></th>
                                <th><?php echo lang('created_date'); ?></th>
                                <th><?php echo lang('approved_date'); ?></th>
                                <th><?php echo lang('lbl_class'); ?></th>
                                <th><?php echo lang('lbl_batch'); ?></th>
                                <th><?php echo lang("reason"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($log_history as $req) { ?>
                            <?php if ($req->type == "fee_exemption") { ?>
                            <tr>
                                <td><?php echo rand(0, 100000) . $req->id; ?></td>
                                <td><?php echo $req->username; ?></td>
                                <td><?php echo $req->response_by; ?></td>
                                <td><?php echo lang("$req->type");?></td>
                                <td><?php echo lang("$req->status");?>
                                    <a href="javascript:void(0);" data-toggle="modal" ng-click="responseReason(<?php echo $req->id; ?>)" data-target="#requestReasonModel" class="m-2"><i class="fa fa-question-circle" aria-hidden="true"></i></span></a>                                    </td>
                                    <td><?php echo $req->request_time; ?></td>
                                    <td><?php echo $req->updated_at; ?></td>
                                    <td><?php echo $req->classname; ?></td>
                                    <td><?php echo $req->batchname; ?></td>
                                    <td><a href="javascript:void(0);" data-toggle="modal" ng-click="resquestReason(<?php echo $req->id; ?>)" data-target="#requestReasonModel" class="m-2"><i class="fa fa-question-circle" aria-hidden="true"></i></span></a>
                                    </td>

                                    <?php } ?>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php } else { ?>
                        <span class="text-danger"><?php echo lang("no_record"); ?></span>
                        <?php } ?>
                    </div>
                </div>
        </div>
    </div>

<?php }?>

        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg" style="max-width:1100px">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">{{subjectname}} <?php echo lang('syllabus_detail') ?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12" id="syllabusContainer">
                                <div class="white-box">

                                    <div class="row custom_disable">
                                        <div class="col-md-3" ng-repeat="row in weeklySyllabus">
                                            <div class="col-md-12 well panel-info" style=" padding:0; display: inline-block" >


                                                <div class="panel-heading">
                                                    <strong>{{ row.week }}</strong> 
                                                    <div class="pull-right" ng-show="syllabusCanEdit">
                                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#editWeekModal" ng-click="initEditWeekModal(row)"><i class="fa fa-pencil"></i></a>
                                                        <a href="javascript:void(0);" ng-click="deleteSyllabusOfWeek(row.id)"><i class="fa fa-trash-o"></i></a>
                                                    </div>
                                                    <br />
                                                    (<small>{{row.start_date}}&nbsp;<?php echo lang('lbl_to') ?>&nbsp;{{row.end_date}}</small>)
                                                </div>

                                                <div  class="panel-body">
                                                    <div ng-repeat="d in row.data">

                                                        <div class="col-md-12" style="padding-left:0; padding-right:0;" ng-class="{custom_bottom_border: (d.status != 'Partially Done' && d.status != 'Reschedule')}" ng-if="d.week_detail_id != NULL">
                                                            <div class="pull-right">
                                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#editWeekDetailModal" ng-click="initEditWeekDetailModal(d)">
                                                                    <i class="fa fa-pencil" ng-if="syllabusCanEdit"></i>
                                                                </a>
                                                                <a href="javascript:void(0)" ng-click="deleteSyllabusOfDay(d)" class="text-danger">
                                                                    <i class="fa fa-trash-o" ng-if="syllabusCanEdit"></i>
                                                                </a>
                                                            </div>
                                                            <label>{{d.topic}}</label> 
                                                            <br/>(<small>{{d.day}}</small>)

                                                            <br />

                                                            <div class="btn-group" style="margin-top: 10px; margin-bottom: 10px;" ng-class="{custom_disable : (requestStatus=='inprocess' || requestStatus=='not-approved')}">
                                                                <button type="button" class="btn waves-effect waves-light btn-sm dropdown-toggle" ng-class="{
                                                                        'btn-outline-info': d.status=='Pending', 
                                                                        'btn-outline-success': d.status=='Done', 
                                                                        'btn-outline-danger': d.status=='Skip', 
                                                                        'btn-outline-warning': d.status=='Partially Done',
                                                                        'btn-outline-primary': d.status=='Reschedule'
                                                                        }" 
                                                                        style="font-size: 12px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <small>{{d.status}}</small>
                                                                </button>

                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item" href="javascript:void(0)" ng-click="changeStatus('Pending',d.week_detail_id)"><?php echo lang("lbl_pending"); ?></a>
                                                                    <a class="dropdown-item" href="javascript:void(0)" ng-click="changeStatus('Done',d.week_detail_id)"><?php echo lang("lbl_done"); ?></a>
                                                                    <a class="dropdown-item" href="javascript:void(0)" ng-click="changeStatus('Skip',d.week_detail_id)"><?php echo lang("lbl_skip"); ?></a>
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item" href="javascript:void(0)" ng-click="changeStatus('Partially Done',d.week_detail_id)"><?php echo lang("partially_done"); ?></a>
                                                                    <a class="dropdown-item" href="javascript:void(0)" ng-click="changeStatus('Reschedule',d.week_detail_id)"><?php echo lang("reschedule"); ?></a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div ng-show="d.status === 'Partially Done' || d.status==='Reschedule'"  class="col-md-12" style="border-bottom: 1px solid #e5e5e5; padding-left:0; margin-bottom: 5px;">
                                                            <label><?php echo lang("lbl_comments"); ?></label>
                                                            <br/>
                                                            <p>{{d.comments}}</p>
                                                        </div>

                                                        <p style="text-align: center;" ng-if="d.week_detail_id == NULL && d.is_working_day" ng-class="{custom_disable : (requestStatus=='inprocess' || requestStatus=='not-approved')}">
                                                            <a href="javascript:void(0)" data-toggle="modal" data-target="#addWeekDetailModal" ng-click="initWeekDetailModal(row.id, d.date)" class="btn waves-effect waves-light btn-outline-danger btn-block">'{{d.day}}' <?php echo lang('not_added') ?></a>
                                                        </p>

                                                        <p style="text-align: center;" ng-if="!d.is_working_day">
                                                            <a href="javascript:void(0)" class="btn waves-effect waves-light btn-outline-secondary custom_disable btn-block"> {{d.day}}</a>
                                                        </p>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2" ng-show="isClick && (requestStatus=='approved' || requestStatus=='draft')"><a href="javascript:void(0)" data-toggle="modal" data-target="#addWeekModal" class="btn waves-effect waves-light btn-secondary"><i class="fa fa-plus"></i> <?php echo lang("add_new_week"); ?></a></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('lbl_close') ?></button>
                    </div>
                </div>
                <!-- End Modal content-->
            </div>
        </div>
         <!-- response reason modal -->
        <div id="responseModel" class="modal fade response_model_application" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="border-radius: 16px">
                    <div class="panel panel-primary" style="border-radius: 16px">
                        <div class="modal-header panel-heading" style="border-top-right-radius: 16px; border-top-left-radius: 16px">
                            <?php echo lang('lbl_application_response_reason') ?>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <textarea class="textarea_editor form-control" id="responseText" ng-model="responseText" rows="5" placeholder="<?php echo lang('lbl_reason_placeholder') ?>"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <p class="text-danger" id="response_error" style="display: none; margin-right: 10%;"><?php echo lang('lbl_reason_error') ?></p>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang('btn_cancel') ?></button>
                        <button type="button" id="send_response" class="btn btn-success waves-effect waves-light"><?php echo lang('btn_send') ?></button>
                    </div>
                </div>
            </div>
        </div>
         <!-- end response reason modal -->
        <!-- sample modal content -->
         <!-- <div class="modal fade bs-example-modal-sm " id="requestReasonModel" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h4 class="modal-title" id="mySmallModalLabel"><?php echo lang('reason') ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body"><h5 class=""> {{reason}} </h5></div>
                </div>
            
            </div>
        </div>   -->


         <div class="modal fade bs-example-modal-sm" id="requestReasonModel" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content" id="addfeetype-content" style="border-radius: 16px" >
                    <div class="panel panel-primary" style="border-radius: 16px">
                        <div class="panel-heading" id="mySmallModalLabel" style="border-top-right-radius: 16px; border-top-left-radius: 16px"><?php echo lang('reason') ?></div>
                            <div class="panel-body">   
                                <p style="word-wrap: break-word;"> {{reason}} </p>                        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                                
                               
    </div>
   
    <?php include(APPPATH . "views/inc/footer.php"); ?>