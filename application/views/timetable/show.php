<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<div>
    <!-- Page Content -->
    <div id="page-wrapper" ng-controller="timeTableCtrl">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?php echo lang('lbl_timetable') ?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="#"><?php echo lang('crumb_dashboard') ?></a></li>
                        <li class="active"><?php echo lang('lbl_timetable') ?></li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <div class="hint"><?php echo lang('help_timetable'); ?></div>
            <!-- Model add time table -->
            <div class="add-time-table-model modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content" id="add-time-table-model-content">
                        <div class="modal-header">
                            <h4 class="modal-title"><?php echo lang('set_timetable') ?></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <form name="addTimetableForm" class="form-material" ng-submit="onSubmitAddTimeTable(addTimetableForm.$valid)" novalidate="">
                            <div class="modal-body">
                                <input type="hidden" name="peroid_id" value="{{crModel.period_id}}">
                                <input type="hidden" name="day_of_week" value="{{crModel.day_of_week}}">
                                <div class="form-group">
                                    <label class="control-label"><?php echo lang('lbl_subject') ?></label>
                                    <select class="form-control" name="subject_id" required="" ng-model="crModel.sub_id" ng-init="crModel.subject_id=''">
                                        <option value="">---<?php echo lang('lbl_select_subject') ?>---</option>
                                        <option ng-repeat="ssub in selectedCBSubjects" value="{{ssub.id}}">{{ ssub.name }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"><?php echo lang('room_no') ?></label>
                                    <input type="text" name="room_no" ng-model="crModel.room_no" class="form-control" required=""/>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang("lbl_close"); ?></button>
                                <button type="submit" class="btn btn-primary waves-effect waves-light"><?php echo lang("lbl_save"); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End of model -->

            <!-- Model edit time table -->
            <div class="edit-time-table-model modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content" id="edit-time-table-model-content">
                        <div class="modal-header">
                            <h4 class="modal-title"><?php echo lang('edit_timetable') ?></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <form name="editTimetableForm" ng-submit="onSubmitUpdateTimetable(editTimetableForm.$valid)" novalidate="">
                            <div class="modal-body">
                                <input type="hidden" name="timetable_id" value="{{etModel.timetable_id}}">
                                <input type="hidden" name="edit_peroid_id" value="{{etModel.period_id}}">
                                <input type="hidden" name="edit_day_of_week" value="{{etModel.day_of_week}}">
                                <div class="form-group">
                                    <label class="control-label"><?php echo lang('lbl_subject') ?></label>
                                    <select class="form-control" name="edit_subject_id" required="required" ng-model="etModel.sub_id">
                                        <option value="">---<?php echo lang('lbl_select_subject') ?>---</option>
                                        <option ng-repeat="ssub in selectedCBSubjects" value="{{ssub.id}}">{{ ssub.name }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"><?php echo lang('room_no') ?></label>
                                    <input type="text" name="edit_room_no" ng-model="etModel.new_room_no" class="form-control" />
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang("lbl_close"); ?></button>
                                <button type="submit" class="btn btn-primary waves-effect waves-light"><?php echo lang("lbl_save"); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End of model -->
            
            <div class="white-box well" id="timetable_search_filter">
                <div class="row">
                    <div class="col-md-12">
                        <form name="timeTableForm" ng-submit="fetchSubjects(timeTableForm.$valid)" novalidate="" class="form-material">
                            <div class="row">
                            <div class="col-md-6" id="tbFilterClasses">
                                <div class="form-group">
                                    <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                    <select class="form-control" ng-model="tbModel.class_id" ng-init="initClasses()" ng-change="initBatches(tbModel.class_id)" required="">
                                        <option value=""><?php echo lang('select_course') ?></option>
                                        <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" id="tbFilterBatches">
                                <div class="form-group">
                                    <label class="control-label"><?php echo lang('lbl_batch') ?></label>
                                    <select class="form-control" ng-model="tbModel.batch_id" required="" ng-init="tbModel.batch_id=''">
                                        <option value=""><?php echo lang('select_batch') ?></option>
                                        <option ng-repeat="bth in batches" value="{{bth.id}}">{{bth.name}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary pull-right"><?php echo lang('search') ?></button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            <div class="row" ng-show="error">
                <div class="col-md-12">
                    <div class="white-box text-danger">{{ error.message }}</div>
                </div>
            </div>
            
            <!--.row-->
            <div class="row" ng-if="!error && error != null">
                <div class="col-sm-12">
                    <div class="white-box">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <tr>
                                    <th></th>
                                    <th ng-repeat="sb in periods">
                                        <p>
                                            {{ sb.title }} <br/>
                                            <span class="small">
                                            {{ sb.start_time2 }} - {{ sb.end_time2 }}
                                        </span>
                                        </p>
                                        <!--<p ng-if="sb.is_break==='N'">
                                            
                                        </p>
                                        <p ng-if="sb.is_break==='Y'">
                                            Break <br/>
                                            {{ sb.start_time }} - {{ sb.end_time }}
                                        </p>-->
                                    </th>
                                </tr>
                                
                                <tr ng-repeat="(k,yy) in timeTable">
                                    <th>{{ k }}</th>
                                    <td ng-repeat="yyy in yy">
                                        <p class="text-center" ng-if="yyy.timetable_id !== null && yyy.is_break === 'N'" data-toggle="modal" onMouseOver="this.style.cursor='pointer'" data-target=".edit-time-table-model" ng-click="selectedValues2(yyy)">
                                            {{ yyy.sub_name }} <br/>
                                            <small>{{ yyy.teacher_name }}</small><br/>
                                            <?php echo lang('room_no');?>: {{ yyy.room_no }}
                                        </p>
                                        <p class="text-center" ng-if="yyy.is_break === 'Y'">
                                            <span class="text-danger"><br />-</span>
                                        </p>
                                        <?php if(login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() ==  login_user()->user->teacher_dept_id && $edit_time_table==false) { ?>
                                        <p ng-if="yyy.timetable_id === null && yyy.is_break==='N'">
                                            <span class="text-danger"><?php echo lang('not_set') ?></span>
                                        </p>
                                        <?php } else { ?>
                                        <p ng-if="yyy.timetable_id === null && yyy.is_break==='N'">
                                            <button type="button" class="btn btn-primary" data-toggle="modal" ng-click="selectedValues(yyy)" data-target=".add-time-table-model"><?php echo lang('set_period') ?></button>
                                        </p>
                                        <?php } ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <!--/.row-->
    </div>
    <!--./row-->
    <!--page content end here-->
</div>
</div>
<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>