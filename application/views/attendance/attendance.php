<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<!-- Page Content -->
<div id="page-wrapper" ng-controller="attendanceController">
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

    <div id="confirmModalP" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
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
                    <button type="button" class="btn btn-success waves-effect waves-light"  ng-click="savePendingAttendance(stdAttendanceFormP.$valid)"><?php echo lang('lbl_save') ?></button>
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
                    <li class="active"><?php echo lang('lbl_student_attendance') ?></li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div class="hint"><?php echo lang('help_std_attendence'); ?></div>
        <!--.row-->
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
                            <a href="javascript:void(0);"  data-toggle="modal" data-target="#requestModel" ><small ng-if="action == 'draft' || action == 'not-approved' " class="text-info"><?php echo lang('lbl_request_for_edit'); ?></small></a>
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
                                        <input class="form-control" type="text" name="comment" ng-model="attendModel.comments[std.id]" ng-init="attendModel.comments[std.id]=students[$index].comment" placeholder="<?php echo lang('lbl_comment_attendance');?>">
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
                                        <input class="form-control" type="text" name="comment" ng-model="attendModelP.comments[std.id]" ng-init="attendModelP.comments[std.id]=students[$index].comment" placeholder="<?php echo lang('lbl_comment_attendance');?>">
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    </div>
                    
                    <div class="col-md-12">
                        <button data-toggle="modal"
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

<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>
