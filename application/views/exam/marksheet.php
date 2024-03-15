<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<style>
    .hideBorder{
        border: 0;
        background: none;
    }
</style>
<script>
    function customPrint(id) {
        $("#" + id).print({
            globalStyles: true,
            mediaPrint: false,
            stylesheet: null,
            noPrintSelector: ".no-print",
            iframe: true,
            append: null,
            prepend: null,
            manuallyCopyFormValues: true,
            deferred: $.Deferred(),
            timeout: 750,
            title: null,
            doctype: '<!doctype html>'
        });
    }
</script>
<!-- Page Content -->
<div id="page-wrapper" ng-controller="marksheetController">
    <div id="requestModelMarksheet" class="modal fade edit_attendance_request_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 16px">
                <div class="panel panel-primary" style="border-radius: 16px">
                    <div class="modal-header panel-heading" style="border-top-right-radius: 16px; border-top-left-radius: 16px">
                        <?php echo lang('lbl_application_request_reason') ?>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
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
                    <button type="button" class="btn btn-success waves-effect waves-light"  ng-click="inProcessMarkSheet()"><?php echo lang('lbl_save') ?></button>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('lbl_mark_exam') ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="<?php echo site_url('/dashboard') ?>"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li><a href="<?php echo site_url('/attendance') ?>"><?php echo lang('lbl_examination') ?></a></li>
                    <li class="active"><?php echo lang('lbl_mark_exam') ?></li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div class="hint"><?php echo lang('lbl_help_marks'); ?></div>
        <!--.row-->
        <div class="white-box well">
            <form class="form-material" name="marktsFilterForm" ng-submit="onSubmit(marktsFilterForm.$valid)" novalidate="">
                <div class="row">
                        <div class="col-md-3" id="marksFilterAcademicYears">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang("lbl_academic_year"); ?></label>
                                <select class="form-control" name="academic_year_id" ng-model="filterModel.academic_year_id" required="" ng-init="initAcademicYears()" ng-change="initClasses(filterModel.academic_year_id)">
                                    <option value="-1"><?php echo lang("lbl_select_academic_year"); ?></option>
                                    <option ng-repeat="ay in academicyears" value="{{ ay.id }}">{{ ay.name }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3" id="marksFilterClasses">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                <select class="form-control" ng-model="filterModel.class_id" ng-change="initBatches(filterModel.class_id, filterModel.academic_year_id)" required="">
                                    <option value=""><?php echo lang('select_course') ?></option>
                                    <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3" id="marksFilterBatches">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('lbl_batch') ?></label>
                                <select class="form-control" ng-model="filterModel.batch_id" ng-change="initSubjects(filterModel.class_id, filterModel.batch_id, filterModel.academic_year_id)" required="">
                                    <option value=""><?php echo lang('select_batch') ?></option>
                                    <option ng-repeat="bth in batches" value="{{bth.id}}">{{bth.name}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3" id="marksFilterSubjects">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('lbl_subject') ?></label>
                                <select class="form-control" ng-model="filterModel.subject_id" ng-change="initExams(filterModel.class_id, filterModel.batch_id, filterModel.subject_id, filterModel.academic_year_id)" required="">
                                    <option value=""><?php echo lang('lbl_select_subject') ?></option>
                                    <option ng-repeat="sub in subjects" value="{{sub.id}}">{{sub.name}}</option>
                                </select>
                            </div>
                        </div>
                    
                    
                        <div class="col-md-3" id="marksFilterExams">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('lbl_exam_session') ?></label>
                                <select class="form-control" ng-show="exams.length == 0" ng-model="filterModel.exam_id">
                                    <option value=""><?php echo lang("no_record"); ?></option>
                                </select>
                                <select class="form-control" ng-show="exams.length != 0" ng-model="filterModel.exam_detail_id" ng-change="saveExamId(filterModel.exam_detail_id)" required="">
                                    <option value=""><?php echo lang('lbl_select_exam') ?></option>
                                    <option ng-repeat="em in exams" value="{{em.id}}">{{em.title}}</option>
                                </select>
                            </div>
                        </div>
                    
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <span class="error" ng-show="message"><b>{{ message }}</b></span>
                        <br/><span>
                            <a href="javascript:void(0);" data-toggle="modal" data-target="#requestModelMarksheet"  >
                                <small ng-if="action == 'draft' || action == 'not-approved' " class="text-info"><?php echo lang('lbl_request_for_edit'); ?></small>
                            </a>
                            <small ng-if="action == 'inprocess' " ng-class="{custom_disable:action}" class="text-warning"><?php echo lang('lbl_request_in_process'); ?></small>
                            <small ng-if="action == 'approved' " ng-class="{custom_disable:action}" class="text-success"><?php echo lang('lbl_request_for_approved'); ?></small>
                        </span>
                    </div>
                    <div class="col-md-4">
                        <input type="submit" class="btn btn-primary pull-right" value="<?php echo lang('search') ?>" />
                    </div>
                </div>
            </form>
        </div>
        <!--./row-->
        
        <div class="white-box" id="marksStudentsTable" ng-show="students[0].name">
            <div class="row">
                <div class="col-md-12 mb-2">
                    <button type="button" onclick="customPrint('marksheet_table')" class="btn btn-info pull-right" ng-show="exist"><i class="fa fa-print"></i></button>
                </div>
                <div class="col-md-12">
                        <div class="row" id="marksheet_table">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="table-responsive m-t-10  col-md-12">
                                        <table id="myTable" style="width:100%; border-spacing: 5px; border-collapse: separate;">
                                            <tr class="text-center"><td colspan="4"><img class="mb-5" src="<?php echo base_url(); ?>uploads/logos/<?php echo $this->session->userdata("userdata")["sh_logo"]; ?>" width="100px" /></td></tr>
                                            <tr>
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
                                            <tr ng-if="printing_details.total_exam_marks != 0">
                                                <th><?= lang("total_exam_marks"); ?></th>
                                                <td><u>{{printing_details.total_exam_marks}}</u></td>
                                                <th><?= lang("total_activity_marks"); ?></th>
                                                <td><u>{{printing_details.total_activity_marks}}</u></td>
                                            </tr>
                                            <tr><th><?= lang("plc_school_name"); ?></th><td colspan="3"><u><?php echo $this->session->userdata("userdata")["sh_name"]; ?></u></td></tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-12" style="overflow: auto;">
                        <form name="marksheetForm" style="width: 100%;" ng-submit="saveMarksheet(marksheetForm.$valid)" novalidate="" ng-class="{custom_disable:disable == 'TRUE'}">
                            <div class="row" id="marksheet_table">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="m-t-10 col-md-12">
                                        <table id="myTable" class="table table-striped table-bordered table-responsive" style="display: inline-table;">
                                            <thead>
                                                <tr>
                                                    <th><?php echo lang('lbl_sr'); ?></th> 
                                                    <th><?php echo lang('imp_std_roll_no') ?></th>
                                                    <th><?php echo lang('lbl_avatar') ?></th>
                                                    <th><?php echo lang('lbl_name') ?></th>
                                                    <th ng-if="printing_details.type == 'number' "><?php echo lang('obtained_marks') ?></th>
                                                    <th ng-if="printing_details.type == 'grade' "><?php echo lang('lbl_grade'); ?></th>
                                                    <th ng-if="printing_details.total_exam_marks != 0 && printing_details.total_exam_marks != null && Object.keys(activities[0]).length !=0" ng-repeat="activity in activities[0]">{{activity.activity_name}}</th>
                                                    <th ng-if="printing_details.type == 'grade' "><?php echo lang('total_grade') ?></th>
                                                    <th><?php echo lang('lbl_total'); ?></th>
                                                    <th><?php echo lang('lbl_remarks') ?></th> 
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr ng-repeat="(key, std) in students_group_wise">
                                                    <td style="padding: 5px;">
                                                        {{ $index+1 }}
                                                    </td>
                                                    <td style="padding: 5px;">{{ std.rollno }}</td>
                                                    <td style="padding: 5px;"><span class="round"><img src="<?php echo base_url(); ?>uploads/user/{{ std.student_avatar }}" width="30px" alt="student-profile"/></span></td>
                                                    <td style="padding: 5px;">{{ std.name }}</td>
                                                    <td style="padding:5px; width: 200px;">
                                                        <div class="form-group" style="margin-bottom: 0;" ng-if="printing_details.type == 'number' && printing_details.total_exam_marks == '0' ">
                                                            <input type="number" required="" min="0" max="{{printing_details.total_marks}}" ng-model="std.obtained_marks" ng-class="{hideBorder:disable == 'TRUE'}" id="id_{{key}}" ng-keypress="moveNext($event,'remarks_',key)" class="form-control"/>
                                                        </div>
                                                        <div class="form-group" style="margin-bottom: 0;"  ng-if="printing_details.type == 'number' && printing_details.total_exam_marks != '0' ">
                                                            <input type="number" required="" min="0" max="{{printing_details.total_exam_marks}}" ng-model="std.obtained_marks" ng-class="{hideBorder:disable == 'TRUE'}" id="id_{{key}}" ng-keypress="moveNext($event,'remarks_',key)" class="form-control"/>
                                                        </div>
                                                        <div class="form-group" style="margin-bottom: 0;" ng-if="printing_details.type == 'grade' ">
                                                            <input type="text" required="" ng-model="std.grade" ng-class="{hideBorder:disable == 'TRUE'}" id="id_{{key}}" ng-keypress="moveNext($event,'remarks_',key)" class="form-control"/>
                                                        </div>
                                                    </td>
                                                    <td style="padding:5px; width: 200px;" ng-if="printing_details.total_exam_marks != 0 && printing_details.total_exam_marks != null && Object.keys(std.activities).length != 0" ng-repeat="activity2 in std.activities">
                                                        <div class="form-group" ng-if="printing_details.type == 'number' ">
                                                            <input type="number" required="" min="0" max="{{activity2.marks}}" ng-model='activity2[$index].obtained_marks' ng-change="total(key);" ng-class="{hideBorder:disable == 'TRUE'}" class="form-control" />
                                                        </div>
                                                        <div class="form-group" ng-if="printing_details.type == 'grade' ">
                                                            <input type="text" required="" ng-model='activity2[$index].obtained_marks' ng-class="{hideBorder:disable == 'TRUE'}" class="form-control" />
                                                        </div>
                                                    </td>
                                                    <td ng-if="printing_details.type == 'grade' " style="padding:5px; width: 220px;">
                                                        <input type="text" required="" ng-model="total_grade" ng-value="std.total_grade" id="total_grade_{{key}}" class="form-control" ng-class="{hideBorder:disable == 'TRUE'}" ng-keypress="moveNext($event,'id_',(key+1))"/>
                                                    </td>
                                                    <!-- total calculation sheraz -->
                                                    <td style="width: 220px;">
                                                        {{std.obtained_marks+students_group_wise[key].total_activity}}
                                                    </td>
                                                    <!-- <td ng-if="std.total_obtained_marks != null" style="width: 220px;">
                                                        {{std.total_obtained_marks}}
                                                    </td> -->
                                                    <td style="padding:5px; width: 220px;">
                                                        <input type="text" ng-model="remarks" ng-value="std.remarks" id="remarks_{{key}}" class="form-control" ng-class="{hideBorder:disable == 'TRUE'}" ng-keypress="moveNext($event,'id_',(key+1))"/>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <input type="submit" ng-hide="disable=='TRUE'" class="btn btn-primary pull-right no-print" value="<?php echo lang('save_marksheet'); ?>" />
                            </div>
                        </div>
                </form>
                </div>
            </div>
            </div>
        </div>

        <div class="white-box" id="marksStudentsTable" ng-show="students.length==0">
            <div class="row">
                <div class="col-md-12 text-danger"><?php echo lang('no_record') ?></div>
            </div>
        </div>

    </div>
</div>

<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>
   