<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<!-- Page Content -->
<div id="page-wrapper" ng-controller="asController">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?= lang("lbl_assign_subjects"); ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="dashboard/"><?= lang("lbl_dashboard"); ?></a></li>
                    <li class="active"><?= lang("lbl_assign_subjects"); ?></li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        
        <?php echo $this->session->flashdata('alert_no_permission'); ?>
        <div class="hint"><?php echo lang('help_assign_subjects'); ?></div>
        <div class="row">
            
            <!-- Modal --> 
            <div class="modal fade" id="exampleModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <form class="form-material" name="assignSubjectForm" ng-submit="saveSubjectAssignments(assignSubjectForm.$valid)" novalidate="">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                                <h5 class="modal-title text-white" id="exampleModalLabel"><?php echo lang("lbl_teachers"); ?></h5>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="teacher">Teacher List</label>
                                    <select class="form-control yasir-assignsubjects-select2" style="width: 100%;" ng-model="selectedSubjectsToAssign">
                                        <option value="0">--<?php echo lang('lbl_none') ?>--</option>
                                        <option ng-repeat="t in teachers" value="{{t.id}}">{{t.name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="panel-footer text-right">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo lang("modal_btn_close"); ?></button>
                                <button type="submit" class="btn btn-primary"><?php echo lang("modal_btn_update"); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
                </div>
            </div>
            <!-- Modal -->
            
            <div class="col-md-12">       
                <div class="white-box" id="assignteacherfilterContainer">
                    <form name="filterForm" novalidate="" ng-submit="onSubmitFetchSubAndThr(filterForm.$valid)">    
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" id="assignteacherClasses">
                                    <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                    <select class="form-control" id="classes" ng-model="selecedVal11" ng-init="fetchClasses();selecedVal11=''" ng-change="loadClassBatches(selecedVal11)" required="">
                                        <option value="">--<?php echo lang('select_course') ?>--</option>
                                        <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" id="assignteacherBatches">
                                <div class="form-group">
                                    <label class="control-label"><?php echo lang('lbl_batches') ?></label>
                                    <select class="form-control" ng-model="selecedVal22" ng-init="selecedVal22=''" id="batches" required="">
                                        <option value="">--<?php echo lang('select_batch') ?>--</option>
                                        <option ng-repeat="batch in batches" value="{{batch.id}}">{{batch.name}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- row -->
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-sm btn-primary text-white"><?php echo lang('search') ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="col-md-12" ng-show="subjects.length>0">
                    <div class="white-box">
                        <div class="row" style="width: 80%; margin-left: 10%;">
                            <table class="table table-bordered table-striped table-hover">
                                <tr>
                                    <th><?php echo lang('lbl_subject') ?></th>
                                    <th class="text-center" style="width: 50%;"><?php echo lang('lbl_teacher') ?></th>
                                </tr>
                                <tr ng-repeat="sub in subjects">
                                    <td><b>{{ sub.name }}</b></td>
                                    <td class="text-center">
                                        <div style="display: inline-block; margin-right: 15px;">
                                            <img ng-if="sub.teacher_avatar" src="uploads/user/{{sub.teacher_avatar}}" title="{{sub.teacher_name}}" data-toggle="modal" data-target="#exampleModal" ng-click="setSelectedOptionValue(1, sub.subject_id_orginal,sub.teacher_id)" style="width:40px; border: 1px solid;" class="img-circle">
                                            <img ng-if="!sub.teacher_avatar" src="uploads/no-image.png" title="No teacher selected." data-toggle="modal" data-target="#exampleModal" ng-click="setSelectedOptionValue(1, sub.subject_id_orginal,sub.teacher_id)" style="width:40px; border: 1px solid;" class="img-circle">
                                            <br/><small class="badge badge-pill badge-info" style="font-size:10px;"><?php echo lang("lbl_teacher"); ?></small>
                                            <br/><small class="badge badge-pill badge-info" style="font-size:10px;">{{sub.teacher_name}}</small>
                                        </div>
                                        <div style="display: inline-block;">
                                            <img ng-if="sub.assistant_avatar" src="uploads/user/{{sub.assistant_avatar}}" title="{{sub.assistant_name}}" data-toggle="modal" data-target="#exampleModal" ng-click="setSelectedOptionValue(2, sub.subject_id_orginal,sub.assistant_id)" style="width:40px; border: 1px solid;"  class="img-circle">
                                            <img ng-if="!sub.assistant_avatar" src="uploads/no-image.png" title="No teacher selected." data-toggle="modal" data-target="#exampleModal" ng-click="setSelectedOptionValue(2, sub.subject_id_orginal,sub.assistant_id)" style="width:40px; border: 1px solid;"  class="img-circle">
                                            <br/><small class="badge badge-pill badge-warning" style="font-size:10px;"><?php echo lang("lbl_assistant"); ?></small>
                                            <br/><small class="badge badge-pill badge-warning" style="font-size:10px;">{{sub.assistant_name}}</small>
                                        </div>
                                        
                                        <!--<div class="form-group">
                                            <div ng-repeat="al in alreadyExists">
                                                <small ng-if="al.subject_id == sub.id" class="text-info"><?php //echo lang('assigned_to') ?>{{al.thr_name}}</small>
                                            </div>
                                            <select name="users" ng-options="user.name for user in teachers" class="form-control" ng-change="selectedValues(userselected,sub.id)" ng-model="userselected[sub.id]">
                                                <option value="">--<?php //echo lang('select_teacher') ?>--</option>
                                            </select>
                                        </div>-->
                                    </td>
                                </tr>

                                <!--<tr>
                                    <td colspan="2">
                                        <button type="submit" class="btn btn-primary"><?php //echo lang('lbl_save') ?></button>
                                    </td>
                                </tr>-->
                            </table>

                        </div>

                    </div>

            </div>
            <br/>

            <div class="row" ng-if="error.message">
                <div class="col-md-12 text-danger">{{error.message}}</div>
            </div>

        </div>
        <!-- .right-sidebar -->
        <!-- Include yout right sidebar here -->
        <!-- /.right-sidebar -->
    </div>
<?php include(APPPATH . "views/inc/footer.php"); ?>