<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<style>
    .custom_bottom_border{
        border-bottom: 1px solid #e5e5e5; 
        margin-bottom: 10px;
    }
</style>
<div>
    <!-- Page Content -->
    <div id="page-wrapper" ng-controller="syllabusController">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?php echo lang('lbl_syllabus') ?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="#"><?= lang('crumb_dashboard') ?></a></li>
                        <li><a href="#"><?php echo lang('lbl_syllabus') ?></a></li>
                        <li class="active"><?php echo lang('lbl_manage_syllabus') ?></li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <div class="hint"><?php echo lang('help_manage_syllabus'); ?></div>
            <!-- Page Content start here -->
            <!--.row-->

            <!----Comment add modal---->
            <div id="addCommentModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; margin-top: 50px;">
                <form name="addCommentForm" ng-submit="saveComment(addCommentForm.$valid)" class="form-material" novalidate="" style="padding-top:50px;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel"><?php echo lang("add_comment"); ?></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label><?php echo lang("lbl_comment"); ?></label>
                                    <textarea rows="4" cols='10' name="comment" ng-model="addCommentModel.comment" required="required" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-info waves-effect"><?php echo lang("modal_btn_save"); ?></button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                </form>
                <!-- /.modal-dialog -->
            </div>
            <!----./Week add modal---->

            <!----Study plan copy modal---->
            <div id="copyWeekModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <form name="copyWeekForm" ng-submit="saveCopiedWeek(copyWeekForm.$valid)" class="form-material" novalidate="">
                    <div class="modal-dialog">
                        <div class="modal-content" id="save-copied-week-model-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel">Where to copy study plan</h4>
                                <button type="button" class="close pull-right" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                                
                                <div class="col-md-12" id="cModelClasses">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                        <select class="form-control" ng-model="ccModel.class_id" ng-init="initClasses2()" ng-change="initBatches2(ccModel.class_id)" required="">
                                            <option value=""><?php echo lang('select_course') ?></option>
                                            <option ng-repeat="cls in cModelClasses" value="{{cls.id}}">{{cls.name}}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12" id="cModelBatches">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('lbl_batch') ?></label>
                                        <select class="form-control" ng-model="ccModel.batch_id" ng-change="initSubjects2(ccModel.class_id, ccModel.batch_id)" required="">
                                            <option value=""><?php echo lang('select_batch') ?></option>
                                            <option ng-repeat="bth in cModelBatches" value="{{bth.id}}">{{bth.name}}</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-12" id="cModelSubjects">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('lbl_subject') ?></label>
                                        <select class="form-control" ng-model="ccModel.subject_id" required="">
                                            <option value=""><?php echo lang('lbl_select_subject') ?></option>
                                            <option ng-repeat="sub in cModelSubjects" value="{{sub.id}}">{{sub.name}}</option>
                                        </select>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-info waves-effect"><?php echo lang("modal_btn_save"); ?></button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                </form>
                <!-- /.modal-dialog -->
            </div>
            <!----./Week add modal---->
            
            <!----Week add modal---->
            <div id="addWeekModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <form name="addWeekForm" ng-submit="saveWeek(addWeekForm.$valid)" class="form-material" novalidate="">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel"><?php echo lang("add_week"); ?></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label><?php echo lang("lbl_name"); ?></label>
                                    <input type="text" name="name" ng-model="addWeekModel.name" required="required" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label><?php echo lang("lbl_start_date"); ?></label>
                                    <input type="text" name="startDate" ng-model="addWeekModel.startDate" required="required" class="form-control mydatepicker-autoclose" />
                                </div>
                                <div class="form-group">
                                    <label><?php echo lang("lbl_end_date"); ?></label>
                                    <input type="text" name="endDate" ng-model="addWeekModel.endDate" required="required" class="form-control mydatepicker-autoclose" />
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-info waves-effect"><?php echo lang("modal_btn_save"); ?></button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                </form>
                <!-- /.modal-dialog -->
            </div>
            <!----./Week add modal---->

            <!----Edit Week modal---->
            <div id="editWeekModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <form name="editWeekForm" ng-submit="saveEditWeek(editWeekForm.$valid)" class="form-material" novalidate="">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel"><?php echo lang("lbl_edit_week"); ?></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label><?php echo lang("lbl_name"); ?></label>
                                    <input type="text" name="name" ng-model="editWeekModel.week" required="required" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label><?php echo lang("lbl_start_date"); ?></label>
                                    <input type="text" name="startDate" ng-model="editWeekModel.start_date" required="required" class="form-control mydatepicker-autoclose" />
                                </div>
                                <div class="form-group">
                                    <label><?php echo lang("lbl_end_date"); ?></label>
                                    <input type="text" name="endDate" ng-model="editWeekModel.end_date" required="required" class="form-control mydatepicker-autoclose" />
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-info waves-effect"><?php echo lang("btn_profile_update"); ?></button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                </form>
                <!-- /.modal-dialog -->
            </div>
            <!----./Edit Week modal---->
            
            <!--- Confimation modal for syllabus done --->
            <div id="doneSyllabusModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog" style="margin-top: 50px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"><?php echo lang('lbl_syllabus_status_confirmation') ?></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <p><?php echo lang("lbl_syllabus_status_confirmation_body"); ?></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang('modal_btn_close') ?></button>
                            <button type="button" class="btn btn-danger waves-effect waves-light" ng-click="changeStatus('Done', confirmDoneId)"><?php echo lang('lbl_yes') ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <!--- End --->
            
            <!----Edit Week Day Syllabus modal---->
            <div id="editWeekDetailModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <form name="editWeekDetailForm" ng-submit="updateWeekDetail(editWeekDetailForm.$valid)" class="form-material" novalidate="">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel"><?php echo lang("lbl_edit_day_detail"); ?></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label><?php echo lang("lbl_topic"); ?></label>
                                    <textarea cols="4" rows="4" name="topic" ng-model="editWeekDetailModel.topic" required="required" class="form-control"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="status"><?php echo lang("lbl_status"); ?></label>
                                    <select class="form-control" ng-model="editWeekDetailModel.status" ng-class="{custom_disable : (editWeekDetailModel.status=='Done')}">
                                        <option value="Pending"><?php echo lang("lbl_pending"); ?></option>
                                        <option value="Done"><?php echo lang("lbl_done");?> </option>
                                        <option value="Skip"><?php echo lang("lbl_skip");?></option>
                                        <option value="Partially Done"><?php echo lang("partially_done");?></option>
                                        <option value="Reschedule"><?php echo lang("reschedule");?></option>
                                    </select>
                                </div>
                                
                                <div class="form-group" ng-if="editWeekDetailModel.status=='Partially Done' || editWeekDetailModel.status=='Reschedule'">
                                    <label><?php echo lang("lbl_comment"); ?></label>
                                    <textarea cols="4" rows="4" name="topic" ng-model="editWeekDetailModel.comments" required="required" class="form-control"></textarea>
                                </div>
                                
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-info waves-effect"><?php echo lang("modal_btn_save"); ?></button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                </form>
                <!-- /.modal-dialog -->
            </div>
            <!----./Edit Week Day Syllabus modal---->
            
            <!----Week Detail add modal---->
            <div id="addWeekDetailModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <form name="addWeekDetailForm" ng-submit="saveWeekDetail(addWeekDetailForm.$valid)" class="form-material" novalidate="">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel"><?php echo lang("add_day_detail"); ?></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="selectedWeekId" ng-model="addWeekDetailModel.selectedWeekId" required="required" class="form-control"/>
                                <input type="hidden" name="selectedDate" ng-model="addWeekDetailModel.selectedDate" required="required" class="form-control"/>
                                
                                <div class="form-group">
                                    <label><?php echo lang("lbl_topic"); ?></label>
                                    <textarea cols="4" rows="4" name="topic" ng-model="addWeekDetailModel.topic" required="required" class="form-control"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="status"><?php echo lang("lbl_status"); ?></label>
                                    <select class="form-control" ng-model="addWeekDetailModel.status" ng-init="addWeekDetailModel.status='Pending'">
                                        <option value="Pending"><?php echo lang("lbl_pending"); ?></option>
                                        <option value="Done"><?php echo lang("lbl_done");?> </option>
                                        <option value="Skip"><?php echo lang("lbl_skip");?></option>
                                        <option value="Partially Done"><?php echo lang("partially_done");?></option>
                                        <option value="Reschedule"><?php echo lang("reschedule");?></option>
                                    </select>
                                </div>
                                
                                <div class="form-group" ng-if="addWeekDetailModel.status=='Partially Done' || addWeekDetailModel.status=='Reschedule'">
                                    <label><?php echo lang("lbl_comment"); ?></label>
                                    <textarea cols="4" rows="4" name="topic" ng-model="addWeekDetailModel.comment" required="required" class="form-control"></textarea>
                                </div>
                                
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-info waves-effect"><?php echo lang("modal_btn_save"); ?></button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                </form>
                <!-- /.modal-dialog -->
            </div>
            <!----./Week Detail add modal---->

            <!--.row-->
            <div class="white-box" id="syllabus_search_filter">
                <form class="form-material" name="syllabusFilterForm" ng-submit="onSubmit(syllabusFilterForm.$valid)" novalidate="">
                    <div class="row">

                        <div class="col-md-4" id="marksFilterClasses">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                <select class="form-control" ng-model="filterModel.class_id" ng-init="initClasses()" ng-change="initBatches(filterModel.class_id)" required="">
                                    <option value=""><?php echo lang('select_course') ?></option>
                                    <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4" id="syllabusFilterBatches">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('lbl_batch') ?></label>
                                <select class="form-control" ng-model="filterModel.batch_id" ng-change="initSubjects(filterModel.class_id, filterModel.batch_id)" required="">
                                    <option value=""><?php echo lang('select_batch') ?></option>
                                    <option ng-repeat="bth in batches" value="{{bth.id}}">{{bth.name}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4" id="syllabusFilterSubjects">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('lbl_subject') ?></label>
                                <select class="form-control" ng-model="filterModel.subject_id" required="">
                                    <option value=""><?php echo lang('lbl_select_subject') ?></option>
                                    <option ng-repeat="sub in subjects" value="{{sub.id}}">{{sub.name}}</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <p class="error" ng-show="message"><b>{{ message }}</b></p>
                        </div>
                        <div class="col-md-4">
                            <input type="submit" class="btn btn-primary pull-right" value="<?php echo lang('search') ?>" />
                        </div>
                    </div>
                </form>
            </div>
            
            <!--./row-->
            <div class="row" ng-show="isClick">
                
                <div class="col-md-12" id="syllabusContainer">
                    <div class="white-box">
                        
                        <div class="row">
                            <div class="well panel-info" style="padding:0; margin-right:5px; display: inline-block; max-width: 300px;" ng-repeat="row in weeklySyllabus">
                                
                                <div class="panel-heading">
                                    <strong>{{ row.week }}</strong> 
                                    <div class="pull-right" ng-show="syllabusCanEdit">
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#copyWeekModal" ng-click="initCopyData(row)" title="Copy"><i class="fa fa-copy"></i></a>
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#editWeekModal" ng-click="initEditWeekModal(row)"><i class="fa fa-pencil"></i></a>
                                        <a href="javascript:void(0);" ng-click="deleteSyllabusOfWeek(row.id)"><i class="fa fa-trash-o"></i></a>
                                    </div>
                                    <br />
                                    (<small>{{row.start_date}}&nbsp;To&nbsp;{{row.end_date}}</small>)
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

                                            <div class="btn-group" style="margin-top: 10px; margin-bottom: 10px;" ng-class="{custom_disable : (requestStatus=='inprocess' || requestStatus=='edit-request' || d.status=='Done')}">
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
                                                    <a class="dropdown-item" href="javascript:void(0)" ng-click="changeStatusWithConfirmation(d.week_detail_id)"><?php echo lang("lbl_done"); ?></a>
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
                                    
                                    <p style="text-align: center;" ng-if="d.week_detail_id == NULL && d.is_working_day" ng-class="{custom_disable : (requestStatus=='inprocess' || requestStatus=='edit-request' || requestStatus=='approved')}">
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#addWeekDetailModal" ng-click="initWeekDetailModal(row.id, d.date)" class="btn waves-effect waves-light btn-outline-success btn-block"><i class="fa fa-plus"></i> <?php echo lang("add_syllabus"); ?> '{{d.day}}'</a>
                                    </p>

                                    <p style="text-align: center;" ng-if="!d.is_working_day">
                                        <a href="javascript:void(0)" class="btn waves-effect waves-light btn-outline-secondary custom_disable btn-block"> {{d.day}}</a>
                                    </p>
 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2" ng-show="isClick && requestStatus=='draft'"><a href="javascript:void(0)" data-toggle="modal" data-target="#addWeekModal" class="btn waves-effect waves-light btn-secondary"><i class="fa fa-plus"></i> <?php echo lang("add_new_week"); ?></a></div>
                    </div>
                        
                </div>
            </div>
                
            <div class="col-md-12">
                                
                <a href="javascript:void(0)" data-toggle="modal" data-target="#requestModel" ng-click="request(requestId,'inprocess')" ng-show="weeklySyllabus.length!=0 && (requestStatus=='draft' || requestStatus=='not-approved')" class="btn btn-info"><i class="fa fa-send-o"></i> <?php echo lang('submit_for_approval') ?></a>
                <a href="javascript:void(0)" data-toggle="modal" data-target="#requestModel" ng-click="request(requestId,'edit-request')" ng-show="weeklySyllabus.length!=0 && (requestStatus=='approved')" class="btn btn-info"><i class="fa fa-pencil-o"></i> <?php echo lang('request_for_edit') ?></a>
<br /><br />
                <div class="alert alert-danger" ng-show="requestStatus=='not-approved'"><?php echo lang('syllabus_not_approved') ?></div>
                <div class="alert alert-info" ng-show="requestStatus=='inprocess'"><?php echo lang('syllabus_awaiting') ?></div>
                
                <div class="alert alert-info" ng-show="requestStatus=='edit-request'"><?php echo lang('syllabus_pending') ?></div> 
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
                        <button type="button" id="send_request" class="btn btn-success waves-effect waves-light" ng-click="confirm_request('old')"><?php echo lang('lbl_save') ?></button>
                    </div>
                </div>
            </div>
        </div>
        
</div>
</div>
<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>