<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<style>
    .rating {
        color: #a9a9a9;
        margin: 0;
        padding: 0;
    }
    ul.rating {
        display: inline-block;
    }
    .rating li {
        list-style-type: none;
        display: inline-block;
        padding: 1px;
        text-align: center;
        font-weight: bold;
        cursor: pointer;
        font-size:30px;
    }
    .rating .filled {
        color: #FBB22A;
    }
    .clear{
      margin-top:20px;
  }
    .table > tbody > tr > td {
     vertical-align: middle;
}
</style>
<!-- Page Content -->
<div id="page-wrapper">
    
    <div class="container-fluid" ng-controller="evaluateController">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('evaluate_students'); ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="<?php echo site_url('/dashboard') ?>"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li><a href="<?php echo site_url('/attendance') ?>"><?php echo lang('lbl_examination') ?></a></li>
                    <li class="active"><?php echo lang('evaluate_students'); ?></li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div class="hint"><?php echo lang('help_evaluate_students'); ?></div>
        <!--.row-->
        <div class="white-box well">
            <form class="form-material" name="evaluateFilterForm" ng-submit="onSubmit(evaluateFilterForm.$valid)" novalidate="">
                <div class="row">
                    
                        <div class="col-md-3" id="evaluateFilterAcademicYears">
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
                                <select class="form-control" ng-model="filterModel.class_id" ng-change="initBatches(filterModel.class_id, filterModel.academic_year_id, filterModel.term_id)" required="">
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
                        <div class="col-md-3" id="termsFilter">
                            <div class="form-group">
                                <label class="control-label">Evaluation Term</label>
                                <select class="form-control" ng-model="filterModel.term_id" ng-change="getEvaluationsByTerm(filterModel.class_id, filterModel.academic_year_id, filterModel.term_id)" required="">
                                    <option value="">Select evaluation term</option>
                                    <option ng-repeat="t in terms" value="{{t.id}}">{{t.term_name}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3" id="evaluationDropdown">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('evaluation_type') ;?></label>
                                <select class="form-control" ng-model="filterModel.evaluation_type"  required="" ng-change="setType()">
                                    <option value=""><?php echo lang('select_evaluation_type'); ?></option>
                                    <option ng-repeat="ev in evaluations" value="{{ev.id}}">{{ev.evaluation_name}}</option>
                                </select>
                            </div>
                        </div>
                        
                    
                    
                        <div class="col-md-3" id="marksFilterSubjects" ng-if="filterModel.type == 'subject'">
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
                        <span class="error" ng-show="message"><b>{{ message }}</b></span>
                        <br/>

                    </div>
                    <div class="col-md-4">
                        <input type="submit" class="btn btn-primary pull-right" value="<?php echo lang('search') ?>" />
                    </div>
                </div>

                
            </form>
        </div>
        <!--./row-->
        
            <div class="white-box" ng-show="students_group_wise.length != 0">
                <b><?php echo lang('evaluation_type');?>:</b> {{evaluation}}<span ng-if="filterModel.type == 'subject'">, <b><?php echo lang('lbl_subject');?>:</b> {{subject}}</span><br> <br>
                <div class="row">
                    <div class="col-md-12 table-responsive">
                        
                        
                        
                        

                        <table id="myTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center"><?php echo lang('imp_sr');?></th>
                                    <th class="text-center"><?php echo lang('imp_std_roll_no') ?></th>
                                    <th class="text-center"><?php echo lang('lbl_avatar') ?></th>
                                    <th class="text-center"><?php echo lang('lbl_name') ?></th>
                                    <th class="text-center"><?php echo lang('lbl_activity');?></th>
                                    <th class="text-center"><?php echo lang('lbl_evaluation');?></th>
                                    <th class="text-center"><?php echo lang('over_all_evaluation');?></th>
                                    <th class="text-center"><?php echo lang('lbl_grade');?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat-start="(key, std) in students_group_wise">
                                    <td class="text-center" style="padding: 5px;" rowspan="{{getspan(std.report.length)}}">
                                        {{ $index+1 }}
                                    </td>
                                    <td class="text-center" style="padding: 5px;" rowspan="{{getspan(std.report.length)}}">{{ std.rollno }}</td>
                                    <td class="text-center" style="padding: 5px;" rowspan="{{getspan(std.report.length)}}"><span class="round"><img src="<?php echo base_url(); ?>uploads/user/{{ std.student_avatar }}" width="50px" alt="student-profile"/></span></td>
                                    <td class="text-center" style="padding: 5px;" rowspan="{{getspan(std.report.length)}}">{{ std.name }}</td>
                                    <td class="text-center" style="padding:5px;">
                                        {{std.report[0].category_name}}
                                    </td>
                                    <td class="text-center" style="padding:5px;">
                                        <div star-rating rating-value="std.report[0].stars" max="5" ng-if="std.report.length != 0"></div>
                                    </td>
                                    <td class="text-center custom_disable" style="padding: 5px;" rowspan="{{getspan(std.report.length)}}">
                                        <span ng-if="std.report.length != 0">
                                        <div star-rating rating-value="getAvg(std.report) | number" max="5" ></div>
                                        ({{getAvg2(std.report) | number : 2}}/5 )
                                    </span>
                                    </td>
                                    <td class="text-center" style="padding:5px;" rowspan="{{getspan(std.report.length)}}">
                                        <span  ng-if="std.report.length != 0">{{getGrade(std.report)}}<br>{{getLegend(std.report)}}</span>
                                    </td>
                                </tr>
                                <tr ng-repeat-end ng-repeat="value in std.report.slice(1)">
                                    <td class="text-center" style="padding:5px;">{{value.category_name}}</td>
                                    <td class="text-center" style="padding:5px;">
                                    <div star-rating rating-value="value.stars" max="5"></div>

                                </td>

                                </tr>
                            </tbody>
                        </table>
                        
                        
                    </div>
                    <div class="col-md-12">
                        <button class="btn btn-primary pull-right no-print" ng-if = "is_category && !is_db" ng-click="saveEvaluation()"><?php echo lang('btn_save');?></button>
                        <button class="btn btn-primary pull-right no-print" ng-if = "is_category && is_db" ng-click="saveEvaluation(true)"><?php echo lang('btn_update');?></button>
                    </div>
                </div>
            </div>
            <div class="white-box" ng-show="students_group_wise.length==0 && evaluation">
                <div class="row">
                    <div class="col-md-12 text-danger"><?php echo lang('no_record') ?></div>
                </div>
            </div>
        

        

    </div>
</div>

<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>
   