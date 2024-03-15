<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<style>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}

</style>
<div>
    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?php echo lang('menu_examination_settings') ?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="#"><?= lang('crumb_dashboard') ?></a></li>
                        <li><a href="#"><?php echo lang('crumb_settings') ?></a></li>
                        <li class="active"><?php echo lang('menu_examination_settings') ?></li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <div class="hint"><?php echo lang('help_exam_setting'); ?></div>
            <!-- Page Content start here -->
            <!--.row-->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">

                        <div class="panel-wrapper collapse in" aria-expanded="true">
                            <div class="panel-body">

                                <ul class="nav customtab nav-tabs" role="tablist">
                                    <li role="presentation" class="nav-item">
                                        <a href="#exams" class="nav-link <?php if ($selected_tab == 'exams') { echo "active"; } else if($selected_tab == NULL) { echo "active"; }?>"
                                        aria-controls="profile" role="tab"
                                        data-toggle="tab"
                                        aria-expanded="true"><span
                                        class="visible-xs"><i class="fa fa-calculator"></i></span><span
                                        class="hidden-xs"><?php echo lang('lbl_exams') ?></span></a>
                                    </li>

                                    <li role="presentation" class="nav-item">
                                        <a href="#exam_activities" class="nav-link <?php if ($selected_tab == 'exam_activities') { echo "active"; } ?>"
                                        aria-controls="profile" role="tab"
                                        data-toggle="tab"
                                        aria-expanded="true"><span
                                        class="visible-xs"><i class="fa fa-indent"></i></span><span
                                        class="hidden-xs"><?php echo lang('exam_activities') ?></span></a>
                                    </li>

                                    <li role="presentation" class="nav-item">
                                        <a href="#exam_details" class="nav-link <?php if ($selected_tab == 'exam_details') { echo "active"; } ?>"
                                        aria-controls="profile" role="tab"
                                        data-toggle="tab"
                                        aria-expanded="true"><span
                                        class="visible-xs"><i class="fa fa-indent"></i></span><span
                                        class="hidden-xs"><?php echo lang('lbl_exam_details') ?></span></a>
                                    </li>
                                    <li role="presentation" class="nav-item">
                                        <a href="#passing_rules" class="nav-link <?php if ($selected_tab == 'passing_rules') { echo "active"; } ?>"
                                        aria-controls="profile" role="tab"
                                        data-toggle="tab"
                                        aria-expanded="true"><span
                                        class="visible-xs"><i class="fa fa-indent"></i></span><span
                                        class="hidden-xs"><?php echo lang('passing_rules');?></span></a>
                                    </li>
                                </ul>
                                <!--tab content start here-->
                                <div class="tab-content">
                                    <div class="tab-pane <?php if ($selected_tab === 'exams') { echo "active"; }else if($selected_tab == NULL) { echo "active"; } ?>" id="exams">
                                        <!-- row -->
                                        <div class="hint"><?php echo lang('help_exams'); ?></div>
                                        <?php echo $exams_new; ?>
                                        <!--/row-->
                                    </div>

                                    <div class="tab-pane <?php if ($selected_tab === 'exam_activities') { echo "active"; } ?>" id="exam_activities">
                                        <!-- row -->
                                        <div class="hint"><?php echo lang('help_exam_activities'); ?></div>

                                        <?php echo $exams_activities; ?>
                                        <!--/row-->
                                    </div>



                                    <div class="tab-pane <?php if ($selected_tab === 'exam_details') { echo "active"; } ?>" id="exam_details" ng-controller="examActivities" ng-init="init();initExamDetails();">
                                        <!-- row -->
                                            <div class="hint"><?php echo lang('help_exam_details'); ?></div>
                                            <div class="well" id="download_search_filter">
                                                <div class="row">
                                                    <!--row -->
                                                    <div class="col-md-6">
                                                        <div class="form-group form-material">
                                                            <label class="control-label"><?php echo lang('lbl_class'); ?></label>
                                                            <select class="form-control " name="grade-type" required="" ng-model="study.class" ng-change="getSections_upload()">
                                                                <option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                                                                <option ng-repeat="classi in study.classes" value="{{classi.id}}">{{classi.name}}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6" id="section_search_filter">
                                                        <div class="form-group form-material">
                                                            <label class="control-label"><?php echo lang('lbl_batch'); ?></label>
                                                            <select class="form-control " name="grade-type" required="" ng-model="study.section">
                                                                <option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                                                                <option ng-repeat="batch in study.batches" value="{{batch.id}}">{{batch.name}}</option>

                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!--/span-->

                                                    <div class="row">

                                                        <div class="col-md-12">
                                                            <button class="btn btn-sm btn-primary" ng-click="search_examDetails()"><?php echo lang('search'); ?></button>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="table-responsive" id="tableDiv">
                                                <button class="btn btn-primary" ng-click="add_exam()" style="margin-bottom: 2%;"><i class="fa fa-plus" aria-hidden="true"></i> <?php echo lang('lbl_calendar_AddBtn'); ?></button>
                                                <table id="exam_details_table" class="display nowrap" cellspacing="0" width="100%"></table>
                                                <!-- <table id="exam_details_table" class="display">     
                                                    <thead>
                                                        <tr>
                                                            <th>Exam Name</th>
                                                            <th>Class</th>
                                                            <th>Section</th>
                                                            <th>Subject</th>
                                                            <th>Exam Date</th>
                                                            <th>Start Time</th>
                                                            <th>End Time</th>
                                                            <th>Type</th>
                                                            <th>Total Marks</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr ng-repeat="details in examDetails">
                                                            <td>{{details.exam_title}}</td>
                                                            <td>{{details.class_name}}</td>
                                                            <td>{{details.batch_name}}</td>
                                                            <td>{{details.subject_name}}</td>
                                                            <td>{{details.exam_date}}</td>
                                                            <td>{{details.start_time}}</td>
                                                            <td>{{details.end_time}}</td>
                                                            <td>{{details.type}}</td>
                                                            <td>{{details.total_marks}}</td>
                                                            <td></td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th>Exam Name</th>
                                                            <th>Class</th>
                                                            <th>Section</th>
                                                            <th>Subject</th>
                                                            <th>Exam Date</th>
                                                            <th>Start Time</th>
                                                            <th>End Time</th>
                                                            <th>Type</th>
                                                            <th>Total Marks</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </tfoot>
                                                </table> -->
                                            </div>
                                            <div id="add_exam" style="display: none;">
                                                <form name="exam_form" ng-submit="saveExam()">
                                                    <div class="form-body">    
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="control-label"><?php echo lang("lbl_exam_session");?></label>
                                                                <select class="form-control" required="" ng-model="exam_details.title">
                                                                    <option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                                                                    <option ng-repeat="exam in exams" value="{{exam.id}}">{{exam.title}}</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="control-label"><?php echo lang("lbl_par_Class");?></label>
                                                                <select class="form-control " required="" ng-model="exam_details.class" ng-change="getSections()">
                                                                    <option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                                                                    <option ng-repeat="classi in study.classes" value="{{classi.id}}">{{classi.name}}</option>
                                                                </select>
                                                            </div>
                                                        </div><br>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="control-label"><?php echo lang("lbl_Section_par");?></label>
                                                                <select class="form-control" required="" ng-model="exam_details.section" ng-change="getSubjects()">
                                                                    <option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                                                                    <option ng-repeat="section in sections" value="{{section.id}}">{{section.name}}</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="control-label"><?php echo lang("lbl_subject");?></label>
                                                                <select class="form-control" required="" ng-model="exam_details.subject" ng-change="getActivities()">
                                                                    <option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                                                                    <option ng-repeat="subject in subjects" value="{{subject.id}}">{{subject.name}}</option>
                                                                </select>
                                                            </div>
                                                        </div><br>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="control-label"><?php echo lang("lbl_exam_date");?></label>
                                                                <input type="text" ng-model="exam_details.date" class="form-control mydatepicker-autoclose">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="control-label"><?php echo lang("start_time");?></label>
                                                                <input type="text" id="slider_example_4" ng-model="exam_details.start_time" class="form-control">
                                                            </div>
                                                        </div><br>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="control-label"><?php echo lang("end_time");?></label>
                                                                <input type="text" id="slider_example_5" ng-model="exam_details.end_time" class="form-control">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="control-label"><?php echo lang("type_dt");?></label>
                                                                <select class="form-control" ng-model="exam_details.type" required>
                                                                    <option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                                                                    <option value="number"><?php echo lang('lbl_number');?></option>
                                                                    <option value="grade"><?php echo lang('lbl_grade');?></option>
                                                                </select>
                                                            </div>
                                                        </div><br>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="control-label"><?php echo lang("total_marks");?></label>
                                                                <input type="number" required="" ng-model="exam_details.total_marks" class="form-control" min="0">
                                                            </div>
                                                            <div class="col-md-6" id="acChecked" style="padding-top: 33px">
                                                                <input type="checkbox" id="checkActivities" class="form-check-input">
                                                                <label class="control-label" style="padding-left: 18px"><?php echo lang("lbl_activities");?></label>
                                                            </div>
                                                        </div><br>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="control-label"><?php echo lang("lbl_passing_marks");?></label>
                                                                <input type="number" required="" min="0" id="passing_marks" mg-model="exam_details.passing_marks" class="form-control" min="0">
                                                            </div>
                                                        </div><br>
                                                        <div id="AcDiv" style="display: none;">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label class="control-label"><?php echo lang("lbl_exam_marks");?></label>
                                                                    <input type="number" min="0" id="total_exam_marks" mg-model="exam_details.total_exam_marks" class="form-control">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="control-label"><?php echo lang("lbl_activity_marks");?></label>
                                                                    <input type="number" min="0" id="total_activity_marks" mg-model="exam_details.total_activity_marks" class="form-control">
                                                                </div>
                                                            </div><br>
                                                            <div class="row">
                                                                <div class="col-md-6" ng-repeat="activity in activities">
                                                                    <label class="control-label">{{activity.activity_name}}</label>
                                                                    <input type="number" ng-model="activity.marks" class="form-control" min="0" placeholder="{{activity.activity_name}} Marks">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        </div><br>
                                                        <div class="alert alert-danger" role="alert" id="errorMessage" style="display: none;">
                                                            {{existMessage}}
                                                        </div><br>
                                                        <div class="row pull-right">
                                                            <div style="margin-right: 8px">
                                                                <button type="submit" class="btn btn-primary"><?php echo lang("modal_btn_save");?></button>
                                                                <button type="button" ng-click="backTable()" class="btn btn-default"><?php echo lang("btn_back");?></button>
                                                            </div>
                                                        </div>
                                                </form>
                                            </div>
                                        
                                            <div id="edit_exam" style="display: none;" ng-init="getExams();">
                                                <form name="exam_form" ng-submit="updateExam()">
                                                    <div class="form-body">    
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="control-label"><?php echo lang("lbl_exam_session");?></label>
                                                                <select class="form-control" required="" ng-model="exam_details1.title">
                                                                    <option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                                                                    <option ng-repeat="exam in exams1" value="{{exam.id}}">{{exam.title}}</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="control-label"><?php echo lang("lbl_par_Class");?></label>
                                                                <select class="form-control " required="" ng-model="exam_details1.class" ng-change="getSectionsEdit()">
                                                                    <option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                                                                    <option ng-repeat="classi in study.classes" value="{{classi.id}}">{{classi.name}}</option>
                                                                </select>
                                                            </div>
                                                        </div><br>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="control-label"><?php echo lang("lbl_Section_par");?></label>
                                                                <select class="form-control" required="" ng-model="exam_details1.section" ng-change="getSubjectsEdit()">
                                                                    <option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                                                                    <option ng-repeat="section in sections1" value="{{section.id}}">{{section.name}}</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="control-label"><?php echo lang("lbl_subject");?></label>
                                                                <select class="form-control" required="" ng-model="exam_details1.subject" ng-change="getActivitiesEdit()">
                                                                    <option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                                                                    <option ng-repeat="subject in subjects1" value="{{subject.id}}">{{subject.name}}</option>
                                                                </select>
                                                            </div>
                                                        </div><br>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="control-label"><?php echo lang("lbl_exam_date");?></label>
                                                                <input type="text" ng-model="exam_details1.date" class="form-control mydatepicker-autoclose-ex">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="control-label"><?php echo lang("start_time");?></label>
                                                                <input type="text" id="slider_example_6" ng-model="exam_details1.start_time" class="form-control">
                                                            </div>
                                                        </div><br>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="control-label"><?php echo lang("end_time");?></label>
                                              
                                                                <input type="text" id="slider_example_7" ng-model="exam_details1.end_time" class="form-control">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="control-label"><?php echo lang("type_dt");?></label>
                                                                <select class="form-control" ng-model="exam_details1.type" required>
                                                                    <option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                                                                    <option value="number">Number</option>
                                                                    <option value="grade">Grade</option>
                                                                </select>
                                                            </div>
                                                        </div><br>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="control-label"><?php echo lang("total_marks");?></label>
                                                                <input type="number" ng-model="exam_details1.total_marks" value="{{exam_details1.total_marks}}" class="form-control" min="0">
                                                            </div>
                                                            <div class="col-md-6" ng-if="activities1.length > 0" style="padding-top: 33px">
                                                                <input type="checkbox" id="checkActivitiesEdit" class="form-check-input" ng-checked="activities1[0].marks>0" onchange="test(this)">
                                                                <label class="control-label" style="padding-left: 18px"><?php echo lang("lbl_activities");?></label>
                                                            </div>
                                                        </div><br>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="control-label"><?php echo lang("lbl_passing_marks");?></label>
                                                                <input type="number" id="passing_marks" ng-model="exam_details1.passing_marks" ng-value="exam_details1.passing_marks" class="form-control" min="0">
                                                            </div>
                                                        </div><br>
                                                        <div id="AcDivEdit" style="display: none;">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label class="control-label"><?php echo lang("lbl_exam_marks");?></label>
                                                                    <input type="number" id="total_exam_marks" ng-model="exam_details1.total_exam_marks" ng-value="exam_details1.total_exam_marks" class="form-control" min="0">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="control-label"><?php echo lang("lbl_activity_marks");?></label>
                                                                    <input type="number" id="total_activity_marks" ng-model="exam_details1.total_activity_marks" ng-value="exam_details1.total_activity_marks" class="form-control" min="0">
                                                                </div>
                                                            </div><br>
                                                            <div class="row">
                                                                <div class="col-md-6" ng-repeat="activity in activities1">
                                                                    <label class="control-label">{{activity.activity_name}}</label>
                                                                    <input type="number" ng-model="activity.marks" class="form-control" ng-value="activity.marks" min="0" placeholder="{{activity.activity_name}} Marks">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        </div><br>
                                                        <div class="alert alert-danger" role="alert" id="errorEditMessage" style="display: none;">
                                                            {{existEditMessage}}
                                                        </div><br>
                                                        <div class="row pull-right">
                                                            <div style="margin-right: 8px">
                                                                <button type="submit" class="btn btn-primary"><?php echo lang("lbl_calendar_updateBtn");?></button>
                                                                <button type="button" ng-click="backTable()" class="btn btn-default"><?php echo lang("btn_back");?></button>
                                                            </div>
                                                        </div>
                                                </form>
                                            </div>

                                            <div id="view_exam" style="display: none;">
                                                <div class="form-body">    
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="control-label"><?php echo lang("lbl_exam_session");?></label>
                                                            <p>{{viewExam.exam_title}}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="control-label"><?php echo lang("lbl_par_Class");?></label>
                                                            <p>{{viewExam.class_name}}</p>
                                                        </div>
                                                    </div><br>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="control-label"><?php echo lang("lbl_Section_par");?></label>
                                                            <p>{{viewExam.batch_name}}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="control-label"><?php echo lang("lbl_subject");?></label>
                                                            <p>{{viewExam.subject_name}}</p>
                                                        </div>
                                                    </div><br>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="control-label"><?php echo lang("lbl_exam_date");?></label>
                                                            <p>{{viewExam.exam_date}}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="control-label"><?php echo lang("start_time");?></label>
                                                            <p>{{viewExam.start_time}}</p>
                                                        </div>
                                                    </div><br>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="control-label"><?php echo lang("end_time");?></label>
                                                            <p>{{viewExam.end_time}}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="control-label"><?php echo lang("type_dt");?></label>
                                                            <p>{{viewExam.type}}</p>
                                                        </div>
                                                    </div><br>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="control-label"><?php echo lang("total_marks");?></label>
                                                            <p>{{viewExam.total_marks}}</p>
                                                        </div>
                                                    </div><br>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="control-label"><?php echo lang("lbl_passing_marks");?></label>
                                                            <p>{{viewExam.passing_marks}}</p>
                                                        </div>
                                                    </div><br>
                                                    <div ng-if="viewExam.activities != ''">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="control-label"><?php echo lang("lbl_exam_marks");?></label>
                                                                <p>{{viewExam.total_exam_marks}}</p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="control-label"><?php echo lang("lbl_activity_marks");?></label>
                                                                <p>{{viewExam.totoal_activity_marks}}</p>
                                                            </div>
                                                        </div><br>
                                                        <div class="row">
                                                            <div class="col-md-6" ng-repeat="activity in viewActivities">
                                                                <label class="control-label">{{activity.activity_name}}</label>
                                                                <p>{{activity.marks}}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><br>
                                                <div class="row pull-right">
                                                    <div style="margin-right: 8px">
                                                        <button type="button" ng-click="backTable()" class="btn btn-default"><?php echo lang("btn_back");?></button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="deleteExamDetails" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title"><?php echo lang('lbl_delete_confirmation') ?></h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p><?php echo lang('delete_confirmation_message') ?></p>


                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang('btn_close_bank') ?></button>
                                                            <button type="button" class="btn btn-danger waves-effect waves-light" ng-click="deleteExamDetails()"><?php echo lang('btn_delete_bank') ?></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <?php echo $exam_details; ?> -->
                                            <!--/row-->
                                        </div>


                                    <div class="tab-pane <?php if ($selected_tab === 'passing_rules') { echo "active"; } ?>" id="passing_rules">
                                        <!-- row -->
                                        <div class="hint"><?php echo lang('help_passing_rules'); ?></div>

                                        <div class="well" style="background:#e4e7ea;" ng-controller="subjectController">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                                        <select class="form-control" id="classes" ng-model="selecedVal" ng-init="selecedVal='<?= $tab_rules_selected_class_id; ?>';fetchClasses(selecedVal)" ng-change="loadClassBatches(selecedVal)">
                                                            <option value="all"><?php echo lang('option_all') ?></option>
                                                            <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" id="yasir_batches">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_batches') ?></label>
                                                        <select class="form-control" ng-model="selecedVal2" id="batches" ng-init="selecedVal2='<?= $tab_rules_selected_batch_id; ?>'">
                                                            <option value="all"><?php echo lang('option_all') ?></option>
                                                            <option ng-repeat="batch in batches" value="{{batch.id}}">{{batch.name}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- row -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <!--<button class="btn btn-sm btn-info" ng-click="loadSubjects()">Search</button>-->
                                                    <a class="btn btn-sm btn-primary text-white" href="<?php echo base_url() ?>examination/add?tab=passing_rules&class_id={{selecedVal}}&batch_id={{selecedVal2}}"><?php echo lang('search') ?></a>
                                                </div>
                                            </div>
                                        </div>

                                        <?php echo $passing_rules; ?>
                                        <!--/row-->
                                    </div>

                                </div>
                            </div>
                            <!--tab content end here-->
                        </div>
                        <!--/panel body-->
                    </div>
                    <!--/panel wrapper-->
                </div>
                <!--/panel-->
            </div>
        </div>
        <!--./row-->
        <!--page content end here-->
    </div>
</div>
<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>
<script type="text/javascript">

    var datepicker_config = {
        changeMonth: true,
        changeYear: true,
        showSecond: false,
        controlType: 'select',
        yearRange: "-50:+10",
        dateFormat: 'dd/mm/yy',
        timeFormat: 'hh:mm tt'
    };

    $(".mydatepicker-autoclose").datepicker(datepicker_config);

    $('#slider_example_4').timepicker({
        controlType: 'select',
        timeFormat: 'hh:mm tt'
    });

    $('#slider_example_5').timepicker({
        controlType: 'select',
        timeFormat: 'hh:mm tt'
    });

    $('#slider_example_6').timepicker({
        controlType: 'select',
        timeFormat: 'hh:mm tt'
    });

    $('#slider_example_7').timepicker({
        controlType: 'select',
        timeFormat: 'hh:mm tt'
    });

    $('#checkActivities').click(function() {
      if ($(this).is(':checked')) {
        $("#AcDiv").show();
      } else {
        $("#p_marks").show();
        $("#AcDiv").hide();
      }
    });

    function test(element) {
        if(element.checked == true){
            $("#AcDivEdit").show();
        } else {
            $("#p_marksEdit").show();
            $("#AcDivEdit").hide();
        }
    }

    $(document).ready(function(){
        $("#back").click(function(){
            $("#AcDiv").show();
    });
  });

</script>
