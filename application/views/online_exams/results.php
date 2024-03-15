<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<div>
    <!-- Page Content -->
    <div id="page-wrapper" ng-controller="onlineExamsController">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?= lang('mark_sheet') ?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="#"><?= lang('crumb_dashboard') ?></a></li>
                        <li><a href="#"><?= lang('lbl_online_exam') ?></a></li>
                        <li class="active"><?= lang('mark_sheet') ?></li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <div class="hint"><?= lang('help_online_exams_results') ?></div>
            <!-- Page Content start here -->
            

            <!-- View Modal -->
            <div id="resutl-view-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"><?= lang('result_view') ?></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="row" ng-repeat="q in browseModel.paper_record">
                                <div class="col-md-12">
                                    <div class="panel panel-info" style="border:1px solid;border-radius: 15px;">
                                        <div class="panel-heading" style="padding: 5px 5px;border-top-left-radius: 14px;border-top-right-radius: 14px;">
                                            <div class="pull-left">Q. {{q.question_no}}</div>
                                            <div class="text-center">{{q.question}}</div>
                                        </div>
                                        <div class="panel-body" style="padding: 5px;">
                                            <div class="row text-center" style="border-bottom: 1px solid lightgray; margin: 0">
                                                <div class="col-6" style="border-right: 1px solid lightgray;"><b><?= lang('lbl_answer') ?></b></div>
                                                <div class="col-6">
                                                    <b><?= lang('correct_answer') ?></b>
                                                </div>
                                            </div>
                                            <div class="row text-center" style="margin: 0">
                                                <div class="col-6" style="border-right: 1px solid lightgray;">
                                                    
                                                    <p style="font-size: 16px;" ng-show="q.question_type=='single_answer' || q.question_type=='true_false' || q.question_type=='single_fill_in_the_blank' || q.question_type=='multi_answer'"><span ng-class="{'text-danger': q.correct_answer.toLowerCase()!=q.answer.toLowerCase(), 'text-success': q.correct_answer.toLowerCase()==q.answer.toLowerCase()}">{{q.answer}}</span></p>
                                                    <p style="font-size: 16px;" ng-show="q.question_type=='double_fill_in_the_blank'">
                                                        <span ng-class="{'text-danger': q.correct_answer_1.toLowerCase()!=q.answer.toLowerCase(), 'text-success': q.correct_answer_1.toLowerCase()==q.answer.toLowerCase()}">{{q.answer}}</span> <br/> <span ng-class="{'text-danger': q.correct_answer_2.toLowerCase()!=q.second_answer.toLowerCase(), 'text-success': q.correct_answer_2.toLowerCase()==q.second_answer.toLowerCase()}">{{q.second_answer}}</span>
                                                    </p>
                                                </div>
                                                <div class="col-6 text-success">
                                                    
                                                    <p style="font-size: 16px;" ng-show="q.question_type=='single_answer' || q.question_type=='true_false' || q.question_type=='single_fill_in_the_blank'">{{q.correct_answer}}</p>
                                                    <p style="font-size: 16px;" ng-show="q.question_type=='double_fill_in_the_blank'">
                                                        <span>{{q.correct_answer_1}}</span>
                                                        <br/>
                                                        <span>{{q.correct_answer_2}}</span>
                                                    </p>
                                                    <p style="font-size: 16px;" ng-show="q.question_type=='multi_answer'">
                                                        <span>{{q.correct_answer}}</span>
                                                    </p>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-md-6">
                                    <div class="panel panel-info" style="border:1px solid;border-radius: 15px;">
                                        <div class="panel-heading" style="padding: 5px 5px;border-top-left-radius: 14px;border-top-right-radius: 14px;">
                                            Q. {{q.question_no}}
                                            <div class="pull-right">Correct Answer</div>
                                        </div>
                                        <div class="panel-body" style="padding: 5px;">
                                            <p ng-show="q.question_type=='single_answer' || q.question_type=='true_false' || q.question_type=='single_fill_in_the_blank'">{{q.correct_answer}}</p>
                                            <p ng-show="q.question_type=='double_fill_in_the_blank'">
                                                <span>{{q.correct_answer_1}}</span>
                                                <br/>
                                                <span>{{q.correct_answer_2}}</span>
                                            </p>
                                            <p ng-show="q.question_type=='multi_answer'">
                                                <span>{{q.correct_answer}}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang('btn_cancel') ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End::View Modal -->

            <!-- Edit Modal -->
            <div id="resutl-edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-lg">
                    <form name="resultUpdateModal" id="result-update-modal-form" ng-submit="updateResult(resultUpdateModal.$valid)">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title"><?php echo lang('edit_result');?></h4>
                                <button type="button" class="close pull-right" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">

                                <div class="panel panel-info" ng-repeat="q in editModel.paper_record" style="border:1px solid;">
                                    <div class="panel-heading">
                                        {{q.question}}
                                        <div class="pull-right">Q. {{q.question_no}}</div>
                                    </div>
                                    <div class="panel-body">
                                        
                                        <div ng-if="q.question_type == 'single_answer'">
                                            <div class="form-group" ng-repeat="(key, value) in q.options" ng-if="$index != 4">
                                                <span class="radio radio-info">
                                                    <input type="radio" id="radio_{{q.id}}{{$index}}" name="radio_{{q.id}}" value="{{key}}" ng-model="q.answer"/>
                                                    <label for="radio_{{q.id}}{{$index}}">{{value}}</label>
                                                </span>
                                            </div>
                                        </div>

                                        <div ng-if="q.question_type == 'true_false'">
                                            <div class="form-group">
                                                <span class="radio radio-info">
                                                    <input type="radio" id="{{q.id}}true" ng-model="q.answer" value="true"/>
                                                    <label for="{{q.id}}true"><?php echo lang('true_for_exam');?></label>
                                                </span>
                                                <span class="radio radio-info radio-inline">
                                                    <input type="radio" id="{{q.id}}false" ng-model="q.answer" value="false" />
                                                    <label for="{{q.id}}false"><?php echo lang('flase_for_exam');?></label>
                                                </span>
                                            </div>
                                        </div>

                                        <div ng-if="q.question_type == 'multi_answer'">
                                            <div class="form-group" ng-repeat="(key, value) in q.options">
                                                <span class="checkbox checkbox-info">
                                                    <input type="checkbox" id="checkbox_{{q.id}}{{$index}}" ng-value="key" ng-model="q.updated_answers[key]" ng-checked="q.updated_answers[key]" />
                                                    <label for="checkbox_{{q.id}}{{$index}}">({{q.alphabets_reference[$index]}}) {{value}}</label>
                                                </span>
                                            </div>
                                        </div>

                                        <div ng-if="q.question_type == 'single_fill_in_the_blank'">
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Enter your filling the blak answer" ng-model="q.answer">
                                            </div>
                                        </div>

                                        <div ng-if="q.question_type == 'double_fill_in_the_blank'">
                                            <div class="form-group">
                                                <input type="text" class="form-control mb-2" placeholder="Enter your first answer" ng-model="q.answer">
                                                <input type="text" class="form-control" placeholder="Enter your second answer" ng-model="q.second_answer">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary waves-effect"><?php echo lang('btn_update') ?></button>
                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang('btn_cancel') ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End::Edit Modal -->

            <!--.row-->
            <div class="white-box well">
                <form class="form-material" name="marktsFilterForm" ng-submit="getResults()" novalidate="">
                    <div class="row">
                        <div class="col-md-3" id="marksFilterAcademicYears">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang("lbl_academic_year"); ?></label>
                                <select class="form-control" name="academic_year_id" ng-model="resultModel.academic_year_id" required="" ng-init="initAcademicYears()" ng-change="initClasses(resultModel.academic_year_id)">
                                    <option value="-1"><?php echo lang("lbl_select_academic_year"); ?></option>
                                    <option ng-repeat="ay in academicyears" value="{{ ay.id }}">{{ ay.name }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3" id="marksFilterClasses">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                <select class="form-control" ng-model="resultModel.class_id" ng-change="initBatches(resultModel.class_id, resultModel.academic_year_id)" required="">
                                    <option value=""><?php echo lang('select_course') ?></option>
                                    <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3" id="marksFilterBatches">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('lbl_batch') ?></label>
                                <select class="form-control" ng-model="resultModel.batch_id" ng-change="initSubjects(resultModel.class_id, resultModel.batch_id, resultModel.academic_year_id)" required="">
                                    <option value=""><?php echo lang('select_batch') ?></option>
                                    <option ng-repeat="bth in batches" value="{{bth.id}}">{{bth.name}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3" id="marksFilterSubjects">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('lbl_subject') ?></label>
                                <select class="form-control" ng-model="resultModel.subject_id" ng-change="initExams2(resultModel.class_id, resultModel.batch_id, resultModel.subject_id, resultModel.academic_year_id)" required="">
                                    <option value=""><?php echo lang('lbl_select_subject') ?></option>
                                    <option ng-repeat="sub in subjects" value="{{sub.id}}">{{sub.name}}</option>
                                </select>
                            </div>
                        </div>
                    
                    
                        <div class="col-md-3" id="marksFilterExams">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('lbl_exam_session') ?></label>
                                <select class="form-control" ng-show="exams2.length == 0" ng-model="resultModel.exam_id">
                                    <option value=""><?php echo lang("no_record"); ?></option>
                                </select>
                                <select class="form-control" ng-show="exams2.length != 0" ng-model="resultModel.exam_detail_id" ng-change="saveExamId(resultModel.exam_detail_id)" required="">
                                    <option value=""><?php echo lang('lbl_select_exam') ?></option>
                                    <option ng-repeat="em in exams2" value="{{em.id}}">{{em.title}}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <input type="submit" class="btn btn-primary pull-right" value="<?php echo lang('search') ?>" />
                        </div>
                    </div>

                </form>
            </div>
            <!--./row-->

            <div class="white-box" ng-show="results.length>0">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <tr>
                                    <th>#</th>
                                    <th><?= lang('lbl_avatar') ?></th>
                                    <th><?= lang('lbl_name') ?></th>
                                    <th><?= lang('imp_std_roll_no') ?></th>
                                    <th ng-repeat="n in selected_paper_number_of_questions" style="min-width: 58px;">Q. {{n}}</th>
                                    <th class="text-center text-success"><?= lang('correct') ?></th>
                                    <th class="text-center text-danger"><?= lang('incorrect') ?></th>
                                    <th class="text-center"><?= lang('obtained_marks') ?></th>
                                    <th class="text-center"><?= lang('lbl_attempts') ?></th>
                                    <th class="text-center" style="min-width: 162px;"><?= lang('th_action') ?></th>
                                </tr>
                                <tr ng-repeat="r in results">
                                    <td>{{$index+1}}</td>
                                    <td><img src="uploads/user/{{r.avatar}}" width="50px" height="50px" class="img-circle" /></td>
                                    <td>{{r.name}}</td>
                                    <td>{{r.rollno}}</td>
                                    <td ng-repeat="nn in r.paper_record">
                                        <span ng-show="nn.question_type =='single_answer' || nn.question_type == 'true_false' || nn.question_type =='single_fill_in_the_blank' || nn.question_type =='multi_answer'">
                                            <span ng-class="{'text-danger': nn.correct_answer.toLowerCase()!=nn.answer.toLowerCase(), 'text-success': nn.correct_answer.toLowerCase()==nn.answer.toLowerCase()}">{{nn.answer}}</span>
                                        </span>
                                        <span ng-show="nn.question_type == 'double_fill_in_the_blank'">
                                            <span ng-class="{'text-danger': nn.correct_answer_1.toLowerCase()!=nn.answer.toLowerCase(), 'text-success': nn.correct_answer_1.toLowerCase()==nn.answer.toLowerCase()}">{{nn.answer}}</span> <br/> <span ng-class="{'text-danger': nn.correct_answer_2.toLowerCase()!=nn.second_answer.toLowerCase(), 'text-success': nn.correct_answer_2.toLowerCase()==nn.second_answer.toLowerCase()}">{{nn.second_answer}}</span>
                                        </span>
                                    </td>
                                    <td class="text-center"><span class="text-success">{{r.number_of_correct_answers}}</span></td>
                                    <td class="text-center"><span class="text-danger">{{r.number_of_incorrect_answers}}</span></td>
                                    <td class="text-center"><strong>{{r.obtained_marks}}</strong></td>
                                    <td class="text-center"><strong>{{r.attempts}}</strong></td>
                                    <td class="text-center" style="min-width: 162px;">
                                        <button href="javascript:void(0);" class="btn btn-rounded btn-warning btn-sm sa-warning-online-exam" value="{{r.id}},{{resultModel.exam_id}},{{resultModel.exam_detail_id}},online_exams/softDelete" ng-show="r.paper_record[0].id != null" ng-if="r.retake_status == 'approved'" ng-disabled="r.edit_status == 'inprocess' || r.retake_status == 'inprocess'"><?php echo lang('btn_retake');?></button>
                                        <button href="javascript:void(0);" class="btn btn-rounded btn-warning btn-sm"  data-toggle="modal" data-target="#requestModelRetake" ng-if="r.retake_status != 'approved'" ng-disabled="r.retake_status == 'inprocess' || r.edit_status == 'inprocess'" ng-click="setRetakeRequestData(r)" ng-show="r.paper_record[0].id != null" title="Needed Approval"><?php echo lang('btn_retake');?> <i class="fa fa-info-circle" ng-if="r.retake_status == 'inprocess'"></i></button>

                                        <a href="javascript:void(0);" ng-show="r.paper_record[0].id != null" class="btn btn-sm btn-success btn-circle" data-toggle="modal" data-target="#resutl-view-modal" ng-click="setResultViewModal(r)"><i class="fa fa-eye"></i></a>

                                        <button href="javascript:void(0);" ng-show="r.paper_record[0].id != null" class="btn btn-sm btn-info btn-circle" data-toggle="modal" data-target="#resutl-edit-modal" ng-click="setResultEditModal(r)" ng-if="r.edit_status == 'approved'" ng-disabled="r.edit_status == 'inprocess' || r.retake_status == 'inprocess'"><i class="fa fa-pencil"></i></button>
                                        <button href="javascript:void(0);" ng-show="r.paper_record[0].id != null" class="btn btn-sm btn-info btn-circle" data-toggle="modal" data-target="#requestModelEdit" ng-if="r.edit_status != 'approved'" ng-disabled="r.edit_status == 'inprocess' || r.retake_status == 'inprocess'" ng-click="setEditRequestData(r)" title="Needed Approval"><i class="fa fa-pencil"></i> <i class="fa fa-info-circle" ng-if="r.edit_status == 'inprocess'"></i></button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="col-md-12" ng-show="results.length==0">
                        <p class="text-danger"><?= lang('paper_not_ready_yet') ?></p>
                    </div>
                
                    <!--./row-->
                    <!--page content end here-->
                </div>
            </div>

            <div id="requestModelEdit" class="modal fade edit_attendance_request_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 16px">
                <div class="panel panel-primary" style="border-radius: 16px">
                    <div class="modal-header panel-heading" style="border-top-right-radius: 16px; border-top-left-radius: 16px">
                        <?php echo lang('lbl_application_request_reason') ?>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
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
                    <button type="button" class="btn btn-success waves-effect waves-light"  ng-click="inProcessEdit()"><?= lang('request_for_edit') ?></button>
                </div>
            </div>
        </div>
    </div>

    <div id="requestModelRetake" class="modal fade edit_attendance_request_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 16px">
                <div class="panel panel-primary" style="border-radius: 16px">
                    <div class="modal-header panel-heading" style="border-top-right-radius: 16px; border-top-left-radius: 16px">
                    <?= lang('write_reason_retake') ?> 
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <textarea class="textarea_editor form-control" id="requestText" ng-model="requestText2" rows="5" placeholder="<?php echo lang('lbl_reason_placeholder') ?>"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <p class="text-danger" id="request_error" style="display: none; margin-right: 10%;"><?php echo lang('lbl_reason_error') ?></p>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang('btn_cancel') ?></button>
                    <button type="button" class="btn btn-success waves-effect waves-light"  ng-click="inProcessRetake()"><?= lang('request_for_retake') ?></button>
                </div>
            </div>
        </div>
    </div>

        </div>
    <!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>