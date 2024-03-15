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
                                <h4 class="modal-title" id="myModalLabel"><?php echo lang("add_week"); ?></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label><?php echo lang("lbl_name"); ?></label>
                                    <input type="text" name="name" ng-model="editWeekModel.name" required="required" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label><?php echo lang("lbl_start_date"); ?></label>
                                    <input type="text" name="startDate" ng-model="editWeekModel.startDate" required="required" class="form-control mydatepicker-autoclose" />
                                </div>
                                <div class="form-group">
                                    <label><?php echo lang("lbl_end_date"); ?></label>
                                    <input type="text" name="endDate" ng-model="editWeekModel.endDate" required="required" class="form-control mydatepicker-autoclose" />
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
            <!----./Edit Week modal---->
            
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
                                    <select class="form-control" ng-model="editWeekDetailModel.status">
                                        <option value="Pending"><?php echo lang("lbl_pending"); ?></option>
                                        <option value="Done"><?php echo lang("lbl_done");?> </option>
                                        <option value="Skip"><?php echo lang("lbl_skip");?></option>
                                        <option value="Partially Done"><?php echo lang("partially_done");?></option>
                                        <option value="Reschedule"><?php echo lang("reschedule");?></option>
                                        <option value="Totally Done"><?php echo lang("totally_done");?></option>
                                    </select>
                                </div>
                                
                                <div class="form-group" ng-if="editWeekDetailModel.status=='Partially Done'">
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
                                        <option value="Totally Done"><?php echo lang("totally_done");?></option>
                                    </select>
                                </div>
                                
                                <div class="form-group" ng-if="addWeekDetailModel.status=='Partially Done'">
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
            <div class="white-box">
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
                                <label class="control-label"><?php echo lang('lbl_subjects') ?></label>
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

            <div class="row">
                <div class="col-md-12" id="syllabusContainer">
                    <div class="white-box">
                        <div class="row">
                            <div class="well panel-default" style="width:24.5%; margin-right:5px; display: inline-block" ng-repeat="row in weeklySyllabus">
                                <strong>{{ row.week }}</strong> &nbsp;(<small>{{row.start_date}}/{{row.end_date}}</small>)
                                <hr />
                                <div ng-repeat="d in row.data">
                                    <div class="col-md-12" ng-class="{custom_bottom_border: d.status !== 'Partially Done'}" ng-if="d.week_detail_id != NULL">
                                        <label>{{d.topic}}</label> 
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#editWeekDetailModal" ng-click="initEditWeekDetailModal(d)">
                                            <small><?php echo lang("lbl_edit"); ?></small>
                                        </a>
                                        <br/>(<small>{{d.day}}</small>)
                                        <div class="btn-group pull-right m-b-20" style="margin-top: -20px;">
                                            <button type="button" class="btn waves-effect waves-light btn-sm btn-outline-primary dropdown-toggle" style="font-size: 12px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <small>{{d.status}}</small>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="javascript:void(0)" ng-click="changeStatus('Pending',d.week_detail_id)"><?php echo lang("lbl_pending"); ?></a>
                                                <a class="dropdown-item" href="javascript:void(0)" ng-click="changeStatus('Done',d.week_detail_id)"><?php echo lang("lbl_done"); ?></a>
                                                <a class="dropdown-item" href="javascript:void(0)" ng-click="changeStatus('Skip',d.week_detail_id)"><?php echo lang("lbl_skip"); ?></a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="javascript:void(0)" ng-click="changeStatus('Partially Done',d.week_detail_id)"><?php echo lang("partially_done"); ?></a>
                                                <a class="dropdown-item" href="javascript:void(0)" ng-click="changeStatus('Reschedule',d.week_detail_id)"><?php echo lang("reschedule"); ?></a>
                                                <a class="dropdown-item" href="javascript:void(0)" ng-click="changeStatus('Totally Done',d.week_detail_id)"><?php echo lang("totally_done"); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div ng-show="d.status === 'Partially Done'"  class="col-md-12" style="border-bottom: 1px solid #e5e5e5; margin-bottom: 10px;">
                                        <label><?php echo lang("lbl_comments"); ?></label>
                                        <br/>
                                        <p>{{d.comments}}</p>
                                    </div>
                                    <p style="text-align: center;" ng-if="d.week_detail_id == NULL && d.is_working_day">
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#addWeekDetailModal" ng-click="initWeekDetailModal(row.id, d.date)" class="btn waves-effect waves-light btn-outline-success btn-block"><i class="fa fa-plus"></i> <?php echo lang("add_syllabus"); ?> '{{d.day}}'</a>
                                    </p>
                                    <p style="text-align: center;" ng-if="!d.is_working_day">
                                        <a href="javascript:void(0)" class="btn waves-effect waves-light btn-outline-secondary custom_disable btn-block"></i> {{d.day}}</a>
                                    </p>
                                </div>

                            </div>
                            <div class="col-md-2" ng-show="isClick"><a href="javascript:void(0)" data-toggle="modal" data-target="#addWeekModal" class="btn waves-effect waves-light btn-secondary"><i class="fa fa-plus"></i> <?php echo lang("add_new_week"); ?></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--./row-->
        <!--page content end here-->
    </div>
</div>
<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>