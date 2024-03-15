<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<!-- Page Content -->
<div id="page-wrapper" ng-controller="attendanceEmployeeCtrl">

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
                    <button type="button" class="btn btn-success waves-effect waves-light"  ng-click="saveAttendanceEmp(empAttendanceForm.$valid)"><?php echo lang('lbl_save') ?></button>
                </div>
            </div>
        </div>
    </div>

    <div id="requestModel" class="modal fade edit_attendance_request_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                        <textarea class="textarea_editor form-control" id="requestText" ng-model="requestText" rows="5" placeholder="<?php echo lang('lbl_reason_placeholder') ?>"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <p class="text-danger" id="request_error" style="display: none; margin-right: 10%;"><?php echo lang('lbl_reason_error') ?></p>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang('btn_cancel') ?></button>
                    <button type="button" class="btn btn-success waves-effect waves-light"  ng-click="inProcessAttendance()"><?php echo lang('lbl_save') ?></button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('lbl_attendance') ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="<?php echo site_url('/dashboard') ?>"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li><a href="<?php echo site_url('/attendance') ?>"><?php echo lang('lbl_attendance') ?></a></li>
                    <li class="active"><?php echo lang('lbl_employee_attendance') ?></li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div class="hint"><?php echo lang('help_emp_attendence'); ?></div>
        <!--.row-->
        <div class="white-box well" id="empAtt_search_filter">
            <form class="form-material" name="attFilterForm" ng-submit="onSubmitEmp(attFilterForm.$valid)" novalidate="">
                <div class="row">

                    



                    <div class="col-md-4" id="emp_departments">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang('title_department') ?></label>
                            <select class="form-control" ng-model="filterModel.department_id" ng-init="getDepartments(); filterModel.department_id='all'" ng-change="getDepartmentCategories(filterModel.department_id)">
                                <option value="all"><?php echo lang('lbl_all') ?></option>
                                <option ng-repeat="d in departments" value="{{d.id}}">{{d.name}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4" id="emp_categories">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang('title_category'); ?></label>
                            <select class="form-control" ng-model="filterModel.category_id" ng-init="filterModel.category_id='all'">
                                <option value="all"><?php echo lang('lbl_all') ?></option>
                                <option ng-repeat="cat in deptCategories" value="{{cat.id}}">{{cat.category}}</option>
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
                    <div class="col-md-8">
                        <p class="error" ng-show="message"><b>{{ message }}</b></p>
                        <p>
                           <!--  <a href="javascript:void(0);" ng-click="inProcessAttendance()" ><small ng-if="action == 'draft' " class="text-info"><?php echo lang('lbl_request_for_edit'); ?></small></a> -->
                            <a href="javascript:void(0);"  data-toggle="modal" data-target="#requestModel" ><small ng-if="action == 'draft' || action == 'not-approved' " class="text-info"><?php echo lang('lbl_request_for_edit'); ?></small></a>
                            <small ng-if="action == 'inprocess' " ng-class="{custom_disable:action}" class="text-warning"><?php echo lang('lbl_request_in_process'); ?></small>
                            <small ng-if="action == 'approved' " ng-class="{custom_disable:action}" class="text-success"><?php echo lang('lbl_request_for_approved'); ?></small>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary pull-right"><?php echo lang('lbl_start_taking_attendance') ?></button>
                    </div>
                </div>
            </form>
        </div>
        <!--./row-->

        <div class="white-box" id="attEmployeeTable" ng-show="employees[0].name">
            <form name="empAttendanceForm" class="form-material" ng-class="{custom_disable:disable == 'TRUE' && marked == 0}">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <h3><?php echo lang('lbl_employee_attendance') ?> ({{selectedDate}})</h3>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-default table-bordered">
                            <tr>
                                <th>#</th>
                                <th><?php echo lang('lbl_name') ?></th>
                                <th><?php echo lang('lbl_designation') ?></th>
                                <th><?php echo lang('lbl_attendance') ?></th>
                                <th><?php echo lang('lbl_remarks') ?></th>
                            </tr>
                            <tr ng-repeat="(key, emp) in employees" ng-class="{custom_disable:(emp.marked == 'yes' && disable == 'TRUE')||(emp.marked == 'yes' && !message)}" ng-style="emp.marked == 'yes' && {'background-color': '#f8f8f8'} ">
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
                                <td>
                                    <div class="form-group" ng-class="{custom_disable:disable == 'TRUE'}">
                                        <input class="form-control" type="text" name="comment" ng-model="attendModel.comments[emp.id]" ng-value="employees[$index].comment" placeholder="<?php echo lang('lbl_comment_attendance');?>">
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <button  data-toggle="modal" data-target="#confirmModal" class="btn btn-primary pull-right"><?php echo lang('save_attendance') ?></button>
                    </div>
                </div>


            </form>
        </div>

        <div class="white-box" id="attStudentsTable" ng-show="employees.length==0">
            <div class="row">
                <div class="col-md-12"><?php echo lang('no_record') ?></div>
            </div>
        </div>

    </div>
</div>



<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>
