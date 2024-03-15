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
        display: none;
        padding: 1px;
        text-align: center;
        font-weight: bold;
        cursor: pointer;
        font-size:20px;
    }
    .rating .filled {
        color: #FBB22A;
        display: inline-block;
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

    <div class="container-fluid" ng-controller="childEvaluationCardController">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Evaluation Report</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="<?php echo site_url('/dashboard') ?>"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li><a href="<?php echo site_url('/attendance') ?>"><?php echo lang('lbl_examination') ?></a></li>
                    <li class="active">Students Report</li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div class="hint">help_students_report</div>
        <!--.row-->
        <div class="white-box well">
            <form class="form-material" name="evaluateFilterForm" ng-submit="onSubmit(evaluateFilterForm.$valid)" novalidate="">
                <div class="row">
                    <div class="col-md-12 p-0 m-0">
                        <div class="col-md-3" id="evaluateFilterAcademicYears">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang("lbl_academic_year"); ?></label>
                                <select class="form-control" name="academic_year_id" ng-model="filterModel.academic_year_id" required="" ng-init="initAcademicYears()" ng-change="initGetParentChild(filterModel.academic_year_id)">
                                    <option value="-1"><?php echo lang("lbl_select_academic_year"); ?></option>
                                    <option ng-repeat="ay in academicyears" value="{{ ay.id }}">{{ ay.name }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3" id="marksFilterClasses">
                            <div class="form-group">
                            <label class="control-label"><?php echo "Child"; ?></label>
                            <select class="form-control" id="cls" ng-model="filterModel.student_id" ng-change="initBatches(filterModel.student_id, filterModel.academic_year_id)" required="">
                                <option value=""><?php echo "Select a child" ?></option>
                                <option ng-repeat="c in parentchild" value="{{c.student_id}}">{{c.name}}</option>
                            </select>
                            </div>
                        </div>

                        
                        

                        <div class="col-md-3" id="evaluationDropdown">
                            <div class="form-group">
                                <label class="control-label">Evaluation Type</label>
                                <select class="form-control" ng-model="filterModel.evaluation_type"  required="">
                                    <option value="">Select Evaluation Type</option>
                                    <option value="all" ng-if="evaluations.length > 1">All</option>
                                    <option ng-repeat="ev in evaluations" value="{{ev.id}}">{{ev.evaluation_name}}</option>
                                </select>
                            </div>
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
        
        <div class="white-box" ng-show="students.length != 0">
            <b>Evaluation Type:</b> {{evaluation}}<br> <br>
            <div class="row">
                <div class="col-md-12 table-responsive">





                    <table id="myTable" class="table table-striped table-bordered" ng-if="all_evaluation">
                        <thead>
                            <tr>
                                <th class="text-center">Sr#.</th>
                                <th class="text-center"><?php echo lang('imp_std_roll_no') ?></th>
                                <th class="text-center"><?php echo lang('lbl_avatar') ?></th>
                                <th class="text-center"><?php echo lang('lbl_name') ?></th>
                                <th class="text-center">Activity</th>
                                <th class="text-center" ng-repeat="s in subjects">{{s.name}}</th>
                                <th class="text-center">Over All Evaluation</th>
                                <th class="text-center">Final Evaluation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat-start="(key, std) in students">
                                <td class="text-center" style="padding: 5px;" rowspan="{{name_span}}">
                                    {{ $index+1 }}
                                </td>
                                <td class="text-center" style="padding: 5px;" rowspan="{{name_span}}">{{ std.rollno }}</td>
                                <td class="text-center" style="padding: 5px;" rowspan="{{name_span}}"><span class="round"><img src="<?php echo base_url(); ?>uploads/user/{{ std.student_avatar }}" width="50px" alt="student-profile"/></span></td>
                                <td class="text-center" style="padding: 5px;" rowspan="{{name_span}}">{{ std.name }}</td>
                                <td class="text-center" style="padding:5px;">
                                    {{std.activities[0].category_name}}
                                </td>
                                <td class="text-center custom_disable" style="padding:2px;min-width: 120px" ng-repeat="ev in std.evaluations" rowspan="{{ev.is_read == 'true' ? '1' : span}}">
                                    <span ng-if="ev.is_read == 'true' && ev.report.length != 0"><div star-rating rating-value="ev.report[0]" max="5"></div></span>
                                    <span ng-if="ev.is_read == 'false'">-</span>
                                </td>
                                <td class="text-center custom_disable" style="padding:5px;" rowspan="{{overall_span}}">
                                    <div star-rating rating-value="std.final_avg" max="5"></div>
                                    ({{std.final_avg_number | number:2}}/5)
                                </td>
                                <td class="text-center custom_disable" style="padding:5px;min-width: 120px;" rowspan="{{name_span}}">
                                    <div star-rating rating-value="std.f_avg" max="5"></div>
                                    ({{std.f_avg_number | number:2}}/5)<br>
                                    {{std.f_grade}}
                                </td>
                            </tr>
                            <tr  ng-repeat="value in std.activities.slice(1)">
                                <td class="text-center" style="padding:5px;">
                                    {{value.category_name}}
                                </td>
                                <td class="text-center custom_disable" style="padding:2px;min-width: 120px" ng-repeat="ev2 in std.evaluations" ng-if="ev2.is_read== 'true'">
                                    <div star-rating rating-value="ev2.report[$parent.$parent.$index + 1]" max="5"></div>
                                </td>
                                <td class="text-center" style="padding:5px;" ng-show="$last">
                                    {{std.legend}}
                                </td>
                            </tr>
                            <tr ng-repeat-end>
                                <td colspan="{{non_subject_span}}">
                                    <table id="myTable" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>

                                                <th class="text-center">Activity</th>
                                                <th class="text-center" ng-repeat="s in subjects2">{{s.name}}</th>
                                                <th class="text-center">Over All Evaluation</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>

                                                <td class="text-center" style="padding:5px;">
                                                    {{std.activities2[0].category_name}}
                                                </td>
                                                <td class="text-center custom_disable" style="padding:2px;min-width: 120px" ng-repeat="ev in std.evaluations2" rowspan="{{ev.is_read == 'true' ? '1' : span}}">
                                                    <span ng-if="ev.is_read == 'true' && ev.report.length != 0"><div star-rating rating-value="ev.report[0]" max="5"></div></span>
                                                    <span ng-if="ev.is_read == 'false'">-</span>
                                                </td>
                                                <td class="text-center custom_disable" style="padding:5px;" rowspan="{{overall_span2}}">
                                                    <div star-rating rating-value="std.final_avg2" max="5"></div>
                                                    ({{std.final_avg_number2 | number:2}}/5)
                                                </td>
                                            </tr>
                                            <tr ng-repeat="value in std.activities2.slice(1)">
                                                <td class="text-center" style="padding:5px;">
                                                    {{value.category_name}}
                                                </td>
                                                <td class="text-center custom_disable" style="padding:2px;min-width: 120px" ng-repeat="ev2 in std.evaluations2" ng-if="ev2.is_read== 'true'">
                                                    <div star-rating rating-value="ev2.report[$parent.$parent.$index + 1]" max="5"></div>
                                                </td>
                                                <td class="text-center" style="padding:5px;" ng-show="$last">
                                                    {{std.legend2}}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table id="myTable" class="table table-striped table-bordered" ng-if="!all_evaluation">
                        <thead>
                            <tr>
                                <th class="text-center">Sr#.</th>
                                <th class="text-center"><?php echo lang('imp_std_roll_no') ?></th>
                                <th class="text-center"><?php echo lang('lbl_avatar') ?></th>
                                <th class="text-center"><?php echo lang('lbl_name') ?></th>
                                <th class="text-center">Activity</th>
                                <th class="text-center" ng-repeat="s in subjects">{{s.name}}</th>
                                <th class="text-center">Over All Evaluation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat-start="(key, std) in students">
                                <td class="text-center" style="padding: 5px;" rowspan="{{span}}">
                                    {{ $index+1 }}
                                </td>
                                <td class="text-center" style="padding: 5px;" rowspan="{{span}}">{{ std.rollno }}</td>
                                <td class="text-center" style="padding: 5px;" rowspan="{{span}}"><span class="round"><img src="<?php echo base_url(); ?>uploads/user/{{ std.student_avatar }}" width="50px" alt="student-profile"/></span></td>
                                <td class="text-center" style="padding: 5px;" rowspan="{{span}}">{{ std.name }}</td>
                                <td class="text-center" style="padding:5px;">
                                    {{std.activities[0].category_name}}
                                </td>
                                <td class="text-center custom_disable" style="padding:2px;min-width: 120px" ng-repeat="ev in std.evaluations" rowspan="{{ev.is_read == 'true' ? '1' : span}}">
                                    <span ng-if="ev.is_read == 'true' && ev.report.length != 0"><div star-rating rating-value="ev.report[0]" max="5"></div></span>
                                    <span ng-if="ev.is_read == 'false'">-</span>
                                </td>
                                <td class="text-center custom_disable" style="padding:5px;" rowspan="{{overall_span}}">
                                    <div star-rating rating-value="std.final_avg" max="5"></div>
                                    ({{std.final_avg_number | number:2}}/5)
                                </td>
                            </tr>
                            <tr ng-repeat-end ng-repeat="value in std.activities.slice(1)">
                                <td class="text-center" style="padding:5px;">
                                    {{value.category_name}}
                                </td>
                                <td class="text-center custom_disable" style="padding:2px;min-width: 120px" ng-repeat="ev2 in std.evaluations" ng-if="ev2.is_read== 'true'">
                                    <div star-rating rating-value="ev2.report[$parent.$parent.$index + 1]" max="5"></div>
                                </td>
                                <td class="text-center" style="padding:5px;" ng-show="$last">
                                    {{std.legend}}
                                </td>
                            </tr>
                        </tbody>
                    </table>


                </div>

            </div>
        </div>
        
        

        

    </div>
</div>

<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>
