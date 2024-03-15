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

    .overlay {
        height: 100%;
        width: 0;
        position: fixed;
        z-index: 1000;
        top: 0;
        left: 0;
        background-color: rgb(0,0,0);
        background-color: rgba(0,0,0, 0.9);
        overflow-x: hidden;
        transition: 0.5s;
    }

    .overlay-content {
        position: relative;
        top: 10%;
        width: 100%;
        text-align: center;
        margin-top: 0;
    }

    .overlay a {
        padding: 8px;
        text-decoration: none;
        font-size: 36px;
        color: #818181;
        display: block;
        transition: 0.3s;
    }

    .overlay a:hover, .overlay a:focus {
        color: #f1f1f1;
    }

    .overlay .closebtn {
        position: absolute;
        top: 20px;
        right: 45px;
        font-size: 60px;
    }

    .overlay .printAllBtn {
        position: absolute;
        top: 20px;
        left:45px;
    }

    .arabic{
        margin-right: 20%;
    }

    .other{
        margin-left: 20%;
    }

    .result_card_container{
        width: 60%;
        background: white;
        padding: 60px;
        margin-bottom: 10px;
        border: 3px solid #464f61;
    }

    @media screen and (max-height: 450px) {
        .overlay a {font-size: 20px}
        .overlay .closebtn {
            font-size: 40px;
            top: 15px;
            right: 35px;
        }
    }
    @media screen and (max-width: 768px) {
        .overlay .closebtn{top:0px;}
        .result_card_container{width: 80%; margin-left: 10%; padding: 25px;}
    }
    @media screen and (max-width: 375px) {
        .overlay .closebtn{top:0px;}
        .result_card_container{width: 80%; margin-left: 10%; padding: 25px;}
    }

    @media screen and (max-width: 414px) {
        .overlay .closebtn{top:0px;}
        .result_card_container{width: 80%; margin-left: 10%; padding: 25px;}
    }
    @media screen and (max-width: 411px) {
        .overlay .closebtn{top:0px;}
        .result_card_container{width: 80%; margin-left: 10%; padding: 25px;}
    }

    @media screen and (max-width: 320px) {
        .overlay .closebtn{top:-10px;}
        .overlay .printAllBtn {top:0px;}
        .result_card_container{width: 80%; margin-left: 10%; padding: 25px;}
    }
</style>
<script>
    function customPrint2(id) {
        $("#" + id).print({
            globalStyles: false,
            mediaPrint: false,
            stylesheet: "<?php echo base_url(); ?>assets/css/custom-std-evaluation-card.css?v=<?php echo date('h.i.s'); ?>",
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
<div id="page-wrapper">

    <div class="container-fluid" ng-controller="reportCardController">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('evaluation_report'); ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="<?php echo site_url('/dashboard') ?>"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li><a href="<?php echo site_url('/attendance') ?>"><?php echo lang('lbl_examination') ?></a></li>
                    <li class="active"><?php echo lang('student_report');?></li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div class="hint"><?php echo lang('help_students_report'); ?></div>
        <!-- overlay for evaluation report -->
        <div id="myNav" class="overlay">
            <a href="javascript:onclick=customPrint2('overlay-content')" class="printAllBtn no-print"><i class="fa fa-print"></i></a>
            <a href="javascript:void(0)" class="closebtn no-print" ng-click="closeNav()">&times;</a>
            <div class="overlay-content" id="overlay-content">
                <div ng-repeat="card in multi_result_cards" ng-bind-html="card"></div>
            </div>
        </div>
        <!-- ./overlay for evaluation report -->
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
                                <select class="form-control" ng-model="filterModel.batch_id" required="">
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
                                <label class="control-label"><?php echo lang('evaluation_type');?></label>
                                <select class="form-control" ng-model="filterModel.evaluation_type"  required="">
                                    <option value=""><?php echo lang('select_evaluation_type');?></option>
                                    <option value="all" ng-if="evaluations.length > 1"><?php echo lang('lbl_all');?></option>
                                    <option ng-repeat="ev in evaluations" value="{{ev.id}}">{{ev.evaluation_name}}</option>
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
        
            <div class="white-box" ng-show="students.length != 0">
                <div class="row">  
                    <div clas="col-md-12" style="width: 100%; margin-bottom: 15px;">
                        <div class="col-md-6"><b><?php echo lang('evaluation_type');?>:</b> <span>{{evaluation}}</span></div>
                        <div class="col-md-6 text-right">
                            <button type="button" ng-click="showAllResultsInReportForm(filterModel)" class="btn btn-primary"><i class="fa fa-tv"></i> <?php echo lang('menu_view_all'); ?></button>
                        </div>
                    </div>

                    <div class="col-md-12">
                        
                        <div class="table-responsive">
                            <table id="myTable" class="table table-striped table-bordered" ng-if="all_evaluation">
                                <thead>
                                    <tr>
                                        <th class="text-center"><?php echo lang('imp_sr');?></th>
                                        <th class="text-center"><?php echo lang('imp_std_roll_no') ?></th>
                                        <th class="text-center"><?php echo lang('lbl_avatar') ?></th>
                                        <th class="text-center"><?php echo lang('lbl_name') ?></th>
                                        <th class="text-center"><?php echo lang('lbl_activity');?></th>
                                        <th class="text-center" ng-repeat="s in subjects_th">{{s.name}}</th>
                                        <th class="text-center"><?php echo lang('over_all_evaluation');?></th>
                                        <th class="text-center"><?php echo lang('final_evaluation');?></th>
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

                                                        <th class="text-center"><?php echo lang('lbl_activity'); ?></th>
                                                        <th class="text-center" ng-repeat="s in subjects2">{{s.name}}</th>
                                                        <th class="text-center"><?php echo lang('over_all_evaluation'); ?></th>
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
                        </div>
                        
                        <div class="table-responsive">
                            <table id="myTable" class="table table-striped table-bordered" ng-if="!all_evaluation">
                                <thead>
                                    <tr>
                                        <th class="text-center"><?php echo lang('imp_sr');?></th>
                                        <th class="text-center"><?php echo lang('imp_std_roll_no') ?></th>
                                        <th class="text-center"><?php echo lang('lbl_avatar') ?></th>
                                        <th class="text-center"><?php echo lang('lbl_name') ?></th>
                                        <th class="text-center"><?php echo lang('lbl_activity');?></th>
                                        <th class="text-center" ng-repeat="s in subjects_th">{{s.name}}</th>
                                        <th class="text-center"><?php echo lang('over_all_evaluation');?></th>
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
</div>

<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>
