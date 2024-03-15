<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<div>
    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid" ng-controller="promotionController" >
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?php echo lang('lbl_promotion') ?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="#"><?php echo lang('crumb_dashboard') ?></a></li>
                        <li><a href="#"><?php echo lang('lbl_promotion') ?></a></li>
                        <li class="active"><?php echo lang('lbl_promotion') ?></li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
            
            
            <!-- sample modal content -->
            <div id="bs-promotion-modal-sm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content" id="promotion-modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="mySmallModalLabel"><?php echo lang("lbl_wehre_to_promote"); ?></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&Cross;</button>
                        </div>
                        <div class="modal-body">
                            <form name="promotionForm" class="form-material" ng-submit="onSubmitPromotionForm(promotionForm.$valid)" novalidate="">

                                <div class="col-md-12" id="promotionFilterClasses2">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                        <select class="form-control" ng-model="filterModel2.class_id" ng-init="initActiveAcademicYearClasses()" ng-change="initBatches2(filterModel2.class_id)" required="">
                                            <option value=""><?php echo lang('select_course') ?></option>
                                            <option ng-repeat="cls in classes2" value="{{cls.id}}">{{cls.name}}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12" id="promotionFilterBatches2">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('lbl_batch') ?></label>
                                        <select class="form-control" ng-model="filterModel2.batch_id" ng-change="initSubjectGroups(filterModel2.academic_year_id, filterModel2.class_id, filterModel2.batch_id)" required="">
                                            <option value=""><?php echo lang('select_batch') ?></option>
                                            <option ng-repeat="bth in batches2" value="{{bth.id}}">{{bth.name}}</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-12" id="promotionFilterSubjectGroups2">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('subject_groups') ?></label>
                                        <select class="form-control" ng-model="filterModel2.subject_group_id" required="">
                                            <option value=""><?php echo lang('select_subject_group') ?></option>
                                            <option ng-repeat="grp in subjectgroups" value="{{grp.id}}">{{grp.group_name}}</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-12" id="promotionFilterReason">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('reason') ?></label>
                                        <textarea class="form-control" ng-model="filterModel2.reason" required=""></textarea>
                                    </div>
                                </div>
                                
                                <div class="pull-right">
                                    <input type="button" ng-click="resetModal()" data-dismiss="modal" aria-hidden="true" value="<?php echo lang('btn_cancel'); ?>" class="btn btn-default" />
                                    <input type="submit" value="<?php echo lang("lbl_promote"); ?>" class="btn btn-success" />
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
            
            
            <!-- Page Content -->
            <!-- /.row -->
            <div class="alert alert-dismissable alert-info" ng-show="alert2.length>0">
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                <ul style="list-style-type: none; margin: 0; padding: 0;">
                    <li ng-repeat="a in alert2"><strong>{{a.status}} </strong> {{ a.message }}</li>
                </ul>
            </div>
            
            <div class="hint"><?php echo lang('lbl_promotion_help'); ?></div>
            <!-- .row -->

            <div class="row well p-0 ml-0 mr-0 mt-0">
                <div class="col-md-12 p-0 m-0">
                    <div class="white-box">
                        
                        
                        <!--.row-->
                        <form class="form-material" name="promotionFilterForm" ng-submit="onSubmitFilter(promotionFilterForm.$valid)" novalidate="">
                            <div class="row">

                                <div class="col-md-3" id="promotionFilterAcademicYears">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('lbl_academic_year') ?></label>
                                        <select class="form-control" ng-model="filterModel.academic_year_id" ng-change="initClasses(filterModel.academic_year_id)" ng-init="initAcademicYears()" required="">
                                            <option value=""><?php echo lang('lbl_select_academic_year') ?></option>
                                            <option ng-repeat="ay in academicyears" value="{{ay.id}}">{{ay.name}}</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-3" id="promotionFilterClasses">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                        <select class="form-control" ng-model="filterModel.class_id" ng-change="initBatches(filterModel.class_id, filterModel.academic_year_id)" required="">
                                            <option value=""><?php echo lang('select_course') ?></option>
                                            <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3" id="promotionFilterBatches">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('lbl_batch') ?></label>
                                        <select class="form-control" ng-model="filterModel.batch_id" required="">
                                            <option value=""><?php echo lang('select_batch') ?></option>
                                            <option ng-repeat="bth in batches" value="{{bth.id}}">{{bth.name}}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('search'); ?></label>
                                        <input type="text" placeholder="<?php echo lang('search_by_name_roll_no'); ?>" class="form-control" ng-model="filterModel.searchKey" />
                                    </div>                 
                                </div>

                                
                            </div>

                            <div class="row">
                                <div class="col-md-8">
<!--                                    <p class="error" ng-show="message"><b>{{ message }}</b></p>
                                    <p>
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#requestModelMarksheet"  ><small ng-if="action == 'draft' || action == 'not-approved' " class="text-info"><?php echo lang('lbl_request_for_edit'); ?></small></a>
                                        <small ng-if="action == 'inprocess' " ng-class="{custom_disable:action}" class="text-warning"><?php echo lang('lbl_request_in_process'); ?></small>
                                        <small ng-if="action == 'approved' " ng-class="{custom_disable:action}" class="text-success"><?php echo lang('lbl_request_for_approved'); ?></small>
                                    </p>-->
                                </div>
                                <div class="col-md-4">
                                    <input type="submit" class="btn btn-primary pull-right" value="<?php echo lang('search') ?>" />
                                </div>
                            </div>
                        </form>
                        <!--./row-->
                    </div>
                </div>
            </div>
            
            <div class="row" id="promotionContainer">
                <div class="col-md-12">
                    <div class="white-box">
                        <div ng-if="students[stdArrayFirstKey].student_id">
                            
<!--                            <div class="row mb-4">
                                <div class="col-md-8"></div>
                                <div class="col-md-4">
                                    <input type="text" ng-model="searchedValue" ng-keyup="search(searchedValue)" placeholder="<?= lang("lbl_enter_some_text_to_search"); ?>" class="form-control" />
                                </div>
                            </div>-->
                            
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <th>
                                        <div class="checkbox checkbox-info m-0">
                                            <input id="selectall" type="checkbox" name="selectall" ng-click="selectallstudents()" />
                                            <label for="selectall"></label>
                                        </div>
                                    </th>
                                    <th><?php echo lang("lbl_avatar"); ?></th>
                                    <th><?php echo lang("lbl_name"); ?></th>
                                    <th><?php echo lang("lbl_rollno"); ?></th>
                                    <th><?php echo lang("lbl_guardian"); ?></th>
                                    <th style="width:110px;"><?php echo lang("lbl_dob"); ?></th>
                                    <th colspan="{{students[stdArrayFirstKey].exams.length}}"><?php echo lang("lbl_exams"); ?></th>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="std in students">
                                        <td>
                                            <div class="form-group">
                                                <div class="checkbox checkbox-info">
                                                    <input id="checkbox_{{std.student_id}}" type="checkbox" name="checkbox_{{std.student_id}}" ng-disabled="std.is_promoted==true" ng-click="set_chechboxarray(std.student_id)" />
                                                    <label for="checkbox_{{std.student_id}}"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="round">
                                                <object data="<?php echo base_url(); ?>uploads/user/{{ std.student_avatar }}" width="50px" type="image/png">
                                                    <img src="<?php echo base_url(); ?>uploads/user/profile.png" alt="user-image" width="50px"/>
                                                </object>
                                            </span>
                                        </td>
                                        <td>{{std.student_name}}</td>
                                        <td>{{std.rollno}}</td>
                                        <td>{{std.guardian_name}}</td>
                                        <td>{{std.dob}}</td>
                                        <td ng-repeat="e in std.exams">{{e.exam_name}}<br/><small ng-class="{'text-success':e.result=='Pass', 'text-danger':e.result=='Fail', 'text-info':e.result=='-'}">{{e.result}}</small></td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="submit">
                                <button type="botton" class="btn btn-success" ng-disabled="checkboxarray.length===0" data-toggle="modal" data-target="#bs-promotion-modal-sm">Promote Students</button>
                            </div>
                        </div>
                        <span ng-if="!students[stdArrayFirstKey].student_id" class="text-danger"><?php echo lang("no_record"); ?></span>
                        
                    </div>
                </div>
            </div>
            <!--./row-->
            <!--page content end-->
        </div>
        <!-- /.container-fluid -->
    <?php include(APPPATH . "views/inc/footer.php"); ?>