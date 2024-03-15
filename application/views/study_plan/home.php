<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
    <style type="text/css">
        .disabledbutton {
    pointer-events: none;
    opacity: 0.8;
}

    </style>
<div id="page-wrapper" ng-controller="syllabusControllerTest">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?= lang("lbl_syllabus"); ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="dashboard/"><?= lang("lbl_dashboard"); ?></a></li>
                    <li class="active"><?= lang("lbl_dashboard"); ?></li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
         
        <div class="alert" style="position: fixed; top: 0; left: 0; width: 100%; z-index: 1000;"></div>
            
            <div class="white-box" id="syllabus_search_filter">
                <form class="form-material" name="syllabusFilterForm" ng-submit="onSubmitNew(syllabusFilterForm.$valid)" novalidate="">
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
                                    <option value=""><?php echo lang('select_batch') ?> {{requestId}}</option>
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
            
            <div class="container col-md-12 white-box">
                        <!-- Notification -->   
                
                <div class="col-md-12" ng-show="requestStatus">
                    <h4 class="card-title mt-3">Status Color</h4>
                    
                    <span class="badge badge-secondary" style="background-color: red;">skip</span>
                    <span class="badge badge-success">Done</span>
                    <span class="badge badge-danger">partially Done</span>
                    <span class="badge badge-warning">Pending</span>
                    <span class="badge badge-info">Reschedual</span>
                      <hr>
                    </div>
              
                <div class="row clearfix" id="cal">
                    <div id="calendar"></div>
                </div>
            </div>
            
            <div class="col-md-12">
                
                

                <a href="javascript:void(0)" data-toggle="modal" data-target="#requestModel" ng-click="request(requestId,'inprocess')" ng-show="(requestStatus=='draft' || requestStatus=='not-approved')" class="btn btn-info"><i class="fa fa-send-o"></i> <?php echo lang('submit_for_approval') ?></a>
                <a href="javascript:void(0)" data-toggle="modal" data-target="#requestModel" ng-click="request(requestId,'edit-request')" ng-show="(requestStatus=='approved')" class="btn btn-info"><i class="fa fa-pencil-o"></i> <?php echo lang('request_for_edit') ?></a>
                <br /><br />
                <div class="alert alert-danger" ng-if="requestStatus=='not-approved'"><?php echo lang('syllabus_not_approved') ?></div>
                <div class="alert alert-info" ng-if="requestStatus=='inprocess'"><?php echo lang('syllabus_awaiting') ?></div>
                
                <div class="alert alert-info" ng-if="requestStatus=='edit-request'"><?php echo lang('syllabus_pending') ?></div> 
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
                                    <label class="control-label" for="title"><?php echo lang('lbl_calendar_title') ?></label>
                                    <input id="tit" name="title" type="text" class="form-control" disabled readonly />
                                </div>                            
                                <div class="form-group">
                                    <label class="control-label" for="description"><?php echo lang('lbl_calendar_description') ?></label>
                                    <textarea class="form-control" id="desc" name="description" rows="3" cols="50" disabled readonly></textarea>
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
        
        <!----Week Detail add modal---->
        <div class="modal fade" id="addWeekDetailModal">
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
                                    <label><?php echo lang("lbl_topic"); ?></label>
                                    <textarea cols="4" rows="4" name="topic" id="topic" required="required" class="form-control"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="status"><?php echo lang("lbl_status"); ?></label>
                                    <select class="form-control" name="details_status" id="details_status">
                                        <option value="Pending"><?php echo lang("lbl_pending"); ?></option>
                                        <option value="Done"><?php echo lang("lbl_done");?> </option>
                                        <option value="Skip"><?php echo lang("lbl_skip");?></option>
                                        <option value="Partially Done"><?php echo lang("partially_done");?></option>
                                        <option value="Reschedule"><?php echo lang("reschedule");?></option>
                                    </select>
                                </div>
                                <!-- <div class="form-group">
                                    <label class="control-label" for="color"><?php //echo lang("lbl_calendar_Tag") ?></label>
                                    <input id="color" name="color" type="text" class="form-control" readonly="readonly" />
                                </div> -->
                                <div class="form-group" id="comment_field">
                                    <label><?php echo lang("lbl_comment"); ?></label>
                                    <textarea cols="4" rows="4" name="comment" id="comment" required="required" class="form-control"></textarea>
                                </div>

                                
                            </form>
                            <div class="modal-footer  panel-footer" id="custom-footer" style="border:0; padding-right:0;">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("lbl_calendar_cancel") ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!----./Week Detail add modal---->
        <!-- request modal to approve and edit ---> 

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
                         <a type="button" id="send_request" class="waves-effect waves-light btn btn-success" ng-click="confirm_request('new')"><?php echo lang('lbl_save'); ?></a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- request modal to approve and edit ---> 
        
        <div class="row"></div>
        </div>
        
<?php include(APPPATH . "views/inc/footer.php"); ?>
<script src='assets/fullcalendar/js/studyplan.php'></script>
 <script>
$(document).ready(function(){
        $("#details_status").change(function()
        {
        if($(this).val() == "Partially Done" || $(this).val() == "Reschedule" )
        {
        $("#comment_field").show();
        }
        else
        {
        $("#comment_field").hide();
        }
            });
        $("#comment_field").hide();
        $('.fc-time').hide();
});
//$("#abc123").hide();
// $("#calendar").addClass("disabledbutton");
</script>




