<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<style>
    .hidden_row{
        border:0;
    }
    .cut-text { 
        text-overflow: ellipsis;
        overflow: hidden; 
        width: 115px; 
        height: 1.2em; 
        white-space: nowrap;
    }
    .setPointer:hover{
        cursor: pointer;
        font-size: 13px;
        font-weight: 700;
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

    @media screen and (max-height: 450px) {
        .overlay a {font-size: 20px}
        .overlay .closebtn {
            font-size: 40px;
            top: 15px;
            right: 35px;
        }
    }
    .result_card_container{
        width: 60%;
        <?php if ($this->session->userdata("userdata")["language"] == 'english') { ?>
            <?php echo "margin-left: 20%;margin-right: 20%;"; ?>
        <?php } else { ?>
            <?php echo "margin-right: 20%; margin-left: 20%;"; ?>
        <?php } ?>
        background: white;
        padding: 60px;
        margin-bottom: 10px;
        border: 3px solid #464f61;
    }
    #footer-table{
        margin-top: 25px;
    }
    @media screen and (max-width: 768px) {
        .overlay .closebtn{top:0px;}
        .result_card_container{width: 80%; margin-left: 10%; padding: 25px;}
    }
    @media screen and (max-width: 375px) {
        .overlay .closebtn{top:0px;}
        .result_card_container{width: 100%; margin-left: 0px; padding: 25px;}
    }

    @media screen and (max-width: 414px) {
        .overlay .closebtn{top:0px;}
        .result_card_container{width: 100%; margin-left: 0px; padding: 25px;}
    }
    @media screen and (max-width: 411px) {
        .overlay .closebtn{top:0px;}
        .result_card_container{width: 100%; margin-left: 0px; padding: 25px;}
    }

    @media screen and (max-width: 320px) {
        .overlay .closebtn{top:-10px;}
        .overlay .printAllBtn {top:0px;}
        .result_card_container{width: 100%; margin-left: 0px; padding: 25px;}
        #profile-img{margin-left:  5px;}
        #std-info-table{margin: -10px; margin-top: 10px}
        #footer-table {margin: -10px; margin-top: 25px;margin-bottom: 20px;}
        #other-info-table{margin: -8px;}
    }
</style>
<script>
    // function customPrint2(id) {
       
    //     $("#" + id).print({
    //         globalStyles: false,
    //         mediaPrint: false,
    //         stylesheet: "<?php echo base_url(); ?>assets/css/custom-result-card.css?v=<?= date("h.i.s") ?>",
    //         noPrintSelector: ".no-print",
    //         iframe: true,
    //         append: null,
    //         prepend: null,
    //         manuallyCopyFormValues: true,
    //         deferred: $.Deferred(),
    //         timeout: 560,
    //         title: null,
    //         doctype: '<!doctype html>'
    //     });
       
    // }
    function customPrint2(id) {
        $("#" + id).print({
            globalStyles: false,
            mediaPrint: false,
            stylesheet: "<?php echo base_url(); ?>assets/css/custom-result-card.css?v=<?= date("h.i.s") ?>",
            noPrintSelector: ".no-print",
            iframe: true,
            append: null,
            prepend: null,
            manuallyCopyFormValues: true,
            deferred: $.Deferred(),
            timeout: 1000,
            title: null,
            doctype: '<!doctype html>'
        });
    }
    
    
</script>
<!-- Page Content -->
<div id="page-wrapper" ng-controller="majorSheetController">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('lbl_major_sheet') ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="<?php echo site_url('/dashboard') ?>"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li><a href="<?php echo site_url('/attendance') ?>"><?php echo lang('lbl_examination') ?></a></li>
                    <li class="active"><?php echo lang('lbl_major_sheet') ?></li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div class="hint"><?php echo lang('lbl_major_sheet_help'); ?></div>
        <!--.row-->

        <!-- sample modal content -->
        <div id="bs-teacher-remarks-modal-sm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="mySmallModalLabel"><?php echo lang('add_teacher_remarks'); ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                    </div>
                    <div class="modal-body">
                        <form name="teacherRemarksForm" ng-submit="saveTeacherRemarks(teacherRemarksForm.$valid)" novalidate="">
                            <div class="form-group">
                                <label><?php echo lang('lbl_remarks'); ?></label>
                                <textarea cols="3" class="form-control" rows="4" required="" ng-model="remarksModel.remark"></textarea>
                            </div>
                            <div class="pull-right">
                                <input type="reset" data-dismiss="modal" aria-hidden="true" value="<?php echo lang('btn_cancel'); ?>" class="btn btn-default" />
                                <input type="submit" value="<?php echo lang('btn_save'); ?>" class="btn btn-success" />
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!-- sample modal content -->
        <div id="bs-update-teacher-remarks-modal-sm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="mySmallModalLabel"><?php echo lang('update_teacher_remarks'); ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                    </div>
                    <div class="modal-body">
                        <form name="teacherRemarksUpdateForm" ng-submit="updateTeacherRemarks(teacherRemarksUpdateForm.$valid)" novalidate="">
                            <div class="form-group">
                                <label><?php echo lang('lbl_remarks'); ?></label>
                                <textarea cols="3" class="form-control" rows="4" required="" ng-model="showModel.remark">{{showModel.remark}}</textarea>
                            </div>
                            <div class="pull-right">
                                <input type="reset" data-dismiss="modal" aria-hidden="true" value="<?php echo lang('btn_cancel'); ?>" class="btn btn-default" />
                                <input type="submit" value="<?php echo lang('btn_update'); ?>" class="btn btn-info" />
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->


        <div class="white-box well" id="majorSheetFilterForm">
            <form class="form-material" name="majorSheetFilterForm" ng-submit="onSubmit(majorSheetFilterForm.$valid)" novalidate="">
                <div class="row">
                    
                    <div class="col-md-3" id="majorSheetFilterAcademicYears">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang("lbl_academic_year"); ?></label>
                            <select class="form-control" name="academic_year_id" ng-model="filterModel.academic_year_id" required="" ng-init="initAcademicYears()" ng-change="initClasses(filterModel.academic_year_id);initExams(filterModel.academic_year_id)">
                                <option value="-1"><?php echo lang("lbl_select_academic_year"); ?></option>
                                <option ng-repeat="ay in academicyears" value="{{ ay.id }}">{{ ay.name }}</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3" id="majorSheetFilterClasses">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang('lbl_class') ?></label>
                            <select class="form-control" ng-model="filterModel.class_id" ng-change="initBatches(filterModel.class_id, filterModel.academic_year_id)" required="">
                                <option value=""><?php echo lang('select_course') ?></option>
                                <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3" id="majorSheetFilterBatches">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang('lbl_batch') ?></label>
                            <select class="form-control" ng-model="filterModel.batch_id" required="">
                                <option value=""><?php echo lang('select_batch') ?></option>
                                <option ng-repeat="bth in batches" value="{{bth.id}}">{{bth.name}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3" id="majorSheetFilterExams">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang('lbl_exam_session') ?></label>
                            <select class="form-control" ng-model="filterModel.exam_id" required="">
                                <option value=""><?php echo lang('lbl_select_exam') ?></option>
                                <option ng-repeat="em in exams" value="{{em.id}}">{{em.title}}</option>
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
        <div class="white-box" id="majorSheetTable" ng-show="status=='success' && students[0].is_all_subjects_marks_added=='true'">
            <div class="row no-print">
                <div class="col-md-12">
                    <div class="col-md-9 p-0">
                        <div class="form-group">
                            <button type="button" ng-click="showAllResultCards(student_ids,filterModel)" class="btn btn-primary"><i class="fa fa-tv"></i> <?php echo lang('menu_view_all'); ?></button>
                            <button type="button" ng-click="majorsheetPrint('marjorsheet_print_container','<?php echo $this->session->userdata("userdata")["sh_logo"]; ?>','<?php echo $this->session->userdata("userdata")["sh_name"]; ?>','<?php if($this->session->userdata("site_lang") != "english") { echo "direction:rtl;"; }?>')" class="btn btn-info"><i class="fa fa-print"></i> <?php echo lang("lbl_print_majorsheet"); ?></button>
                            <button type="button" class="btn btn-warning" id="button-a"><i class="fa fa-file-excel-o"></i> <?php echo lang("lbl_export_to_excel"); ?></button>
                        </div>
                    </div>
                    <div class="col-md-3 p-0">
                        <input type="text" ng-model="searchedValue" placeholder="<?= lang("lbl_enter_some_text_to_search"); ?>" class="form-control" />
                    </div>
                </div>
            </div>
            <table id="mytabletemp" style="display: none;">
                <thead>
                    <tr>
                        <th class="text-center"><?php echo lang('lbl_sr'); ?></th>
                        <th><?php echo lang('student_name') ?></th>
                        <th><?php echo lang('imp_std_roll_no') ?></th>
                        <th class="text-center" ng-repeat="sub in students[0].subjects">{{sub.subject_name}}<small>({{sub.exams[0].total_marks}})</small></th>
                        <th><?php echo lang('total_marks'); ?></th>
                        <th><?php echo lang('percentage'); ?></th>
                        <th><?php echo lang('exam_position'); ?></th>
                        <th><?php echo lang('lbl_status'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="std in students_shift_wise | orderBy:'position'" ng-if="std.is_shifted == 0">
                        <td> {{$index+1}}</td>
                        <td>{{std.student_name}}</td>
                        <td>{{std.rollno}}</td>
                        <td ng-repeat="sub2 in std.subjects">
                            <span ng-show="sub2.exams[0].obtained_marks!== null && sub2.exams[0].type == 'number'">
                                {{sub2.exams[0].obtained_marks}}
                            </span>
                            <span ng-show="sub2.exams[0].grade!== null && sub2.exams[0].type == 'grade'">
                                {{sub2.exams[0].grade}}
                            </span>
                        </td>
                        <td>{{ std.obtained_total }}</td>
                        <td>{{ std.percentage.replace('%','') }}</td>
                        <td><span>{{std.new_position}}</span></td>
                        <td>
                            <span ng-if="std.result=='-'" class='text-info'>-</span>
                            <span ng-if="std.result=='<?php echo lang("fail"); ?>'" class='text-danger'><?php echo lang('fail'); ?></span>
                            <span ng-if="std.result=='<?php echo lang("pass"); ?>'" class='text-success'><?php echo lang('pass'); ?></span>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <form action="#">
                <div class="row" style="overflow:auto;">
                    <div class="col-md-12" id="marjorsheet_print_container" style="<?php if($this->session->userdata('site_lang') != 'english') { echo 'direction:rtl;'; }?>">
                        <table id="mjshttable" class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th class="text-center"><?php echo lang('lbl_sr'); ?></th>
                                    <th class="text-center"><?php echo lang('imp_std_roll_no') ?></th>
                                    <th class="text-center"><?php echo lang('lbl_avatar') ?></th>
                                    <th class="text-center"><?php echo lang('student_name') ?></th>
                                    <th class="text-center" ng-repeat="sub in students[0].subjects">{{sub.subject_name}} <br/><small ng-if="sub.exams[0].type == 'number'">{{sub.exams[0].total_marks}}</small></th>
                                    <th class="text-center"><?php echo lang('total_marks'); ?> <!--<br/><small>{{ overalltotal }}</small>--></th>
                                    <th class="text-center"><?php echo lang('percentage'); ?>
                                    <th class="text-center"><?php echo lang('exam_position'); ?></th>
                                    <th class="text-center"><?php echo lang('lbl_status'); ?></th>
                                    <th class="text-center"><?php echo lang('teacher_remarks'); ?></th>
                                    <th class="text-center no-print" style="min-width:120px"><?php echo lang('lbl_action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-show="(students | filter:searchedValue).length == 0" >
                                    <td class="text-left no-print" colspan="{{students[0].subjects.length + 10}}"><?= lang("no_record"); ?></td>
                                </tr>
                                <tr ng-repeat="std in students | filter:searchedValue | orderBy:'position'">
                                    <td> {{$index+1}}</td>
                                    <td> {{std.rollno}}</td>
                                    <td>
                                        <span class="round">
                                        <object data="<?php echo base_url(); ?>uploads/user/{{std.student_avatar}}" width="50px" type="image/png">
                                            <img src="<?php echo base_url(); ?>uploads/user/profile.png" alt="user-image" width="50px"/>
                                          </object>
                                        </span>
                                        
                                    </td>
                                    <td>
                                        {{std.student_name}} 
                                        <a ng-show="std.is_shifted" href="javascript:void(0)" data-html="true" data-toggle="tooltip" data-placement="top" title="Student has been shifted to another section."><i class="fa fa-info-circle"></i></a>
                                    </td>
                                    <td ng-repeat="sub2 in std.subjects">
                                     <!--  sheraz update 09-06-2021 search std.subjects => add  && sub2.exams[0].activities.length != 0" -->
                                        <table width="100%" ng-show="sub2.exams[0].obtained_marks!= null && sub2.exams[0].obtained_marks!= 0 && sub2.exams[0].activities.length != 0">
                                            <tr>
                                                <th class="text-center">Exam Marks</th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span ng-show="sub2.exams[0].type == 'number' && sub2.exams[0].obtained_marks!= null">
                                                        {{sub2.exams[0].obtained_marks}}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                        <!--  sheraz update 09-06-2021 search  ng-if="sub2.exams[0] => activities.length != 0 -->
                                        <table ng-if="sub2.exams[0].activities.length != 0 && sub2.exams[0].activities[0][0].obtained_marks != null">
                                            <tr>
                                                <th ng-repeat="act in sub2.exams[0].activities">{{act.activity_name}}</th>
                                            </tr>
                                            <tr>
                                                <td ng-repeat="act in sub2.exams[0].activities track by $index">{{act[$index].obtained_marks}}</td>
                                            </tr>
                                        </table>
                                         <!-- //sheraz update 09-06-2021 search ng-show="sub2.exams[0] =>  && sub2.exams[0].exam_detail_id != null && sub2.exams[0].activities.length != 0  -->
                                        <table width="100%" ng-show="sub2.exams[0].total_obtained_marks!= 'null' && sub2.exams[0].exam_detail_id != null && sub2.exams[0].activities.length != 0 ">
                                            <tr>
                                                <th class="text-center"><?php echo lang('total_obtained_marks'); ?></th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span ng-show=" sub2.exams[0].total_obtained_marks!= null && sub2.exams[0].type == 'number' ">
                                                        {{sub2.exams[0].total_obtained_marks}}
                                                        <small ng-if="sub2.exams[0].marksheet_status == 'Fail'" class="text-danger">F</small>
                                                        <small ng-if="sub2.exams[0].marksheet_status == 'Pass'" class="text-success">P</small>
                                                    </span>
                                                    <span ng-show="sub2.exams[0].total_obtained_marks==null && sub2.exams[0].type == 'number' ">
                                                        {{sub2.exams[0].obtained_marks}}
                                                        <small ng-if="sub2.exams[0].marksheet_status == 'Fail'" class="text-danger">F</small>
                                                        <small ng-if="sub2.exams[0].marksheet_status == 'Pass'" class="text-success">P</small>
                                                    </span>
                                                    <span ng-show="sub2.exams[0].total_grade!== null && sub2.exams[0].type == 'grade'">
                                                        {{sub2.exams[0].total_grade}}
                                                    </span>    
                                                </td>
                                            </tr>
                                        </table>
                                        <span ng-show="sub2.exams[0].total_obtained_marks=== null && sub2.exams[0].obtained_marks === null " class="text-danger no-print">-</span>
                                      <!--   // sheraz update 09-06-2021 search  ng-show="sub2.exams[0].total_obtained_marks=== null" => add below -->
                                        <div ng-if="sub2.exams[0].activities.length == 0">
                                            <span ng-show=" sub2.exams[0].total_obtained_marks!= null && sub2.exams[0].type == 'number' ">
                                                {{sub2.exams[0].total_obtained_marks}}
                                                <small ng-if="sub2.exams[0].marksheet_status == 'Fail'" class="text-danger">F</small>
                                                <small ng-if="sub2.exams[0].marksheet_status == 'Pass'" class="text-success">P</small>
                                            </span>
                                            <span ng-show="sub2.exams[0].total_obtained_marks==null && sub2.exams[0].type == 'number' ">
                                                {{sub2.exams[0].obtained_marks}}
                                                <small ng-if="sub2.exams[0].marksheet_status == 'Fail'" class="text-danger">F</small>
                                                <small ng-if="sub2.exams[0].marksheet_status == 'Pass'" class="text-success">P</small>
                                            </span>
                                            <span ng-show="sub2.exams[0].total_grade!== null && sub2.exams[0].type == 'grade'">
                                                {{sub2.exams[0].total_grade}}
                                            </span>    
                                        </div>
                                        <!-- // sheraz update 09-06-2021 search  ng-show="sub2.exams[0].total_obtained_marks=== null" => add above -->
                                    </td>
                                    <td>{{ std.obtained_total }}</td>
                                    <td>{{ std.percentage }}</td>
                                    <td><span>{{std.new_position}}</span></td>
                                    <td>
                                        <span ng-if="std.result=='-'" class='text-info'>-</span>
                                        <span ng-if="std.result=='<?php echo lang("fail"); ?>'" class='text-danger'><?php echo lang('fail'); ?></span>
                                        <span ng-if="std.result=='<?php echo lang("pass"); ?>'" class='text-success'><?php echo lang('pass'); ?></span>
                                    </td>
                                    <td style="max-width:100px;">
                                        <p style="white-space: nowrap; overflow: hidden;text-overflow: ellipsis;" class="setPointer"><small ng-if="std.teacher_remark_id!=null" data-toggle="modal" data-target="#bs-update-teacher-remarks-modal-sm" ng-click="showTeacherRemark(std.teacher_remark,std.teacher_remark_id)">{{std.teacher_remark}}</small></p>
                                        <a href="javascript:void();" ng-if="std.teacher_remark_id==null" ng-click="setSelectedStudentForRemarks(std.student_id, std.subjects[0].exams[0].exam_id)" data-toggle="modal" data-target="#bs-teacher-remarks-modal-sm" class="btn btn-primary btn-circle no-print"><i class="fa fa-plus"></i></a>
                                    </td>
                                    <td class="no-print">
                                      
                                        <a target="_blank" href="<?php echo base_url() ?>forms/show?id={{std.student_id}}&exam_id={{std.subjects[0].exams[0].exam_id}}&requested_page=result_card&class_id={{std.class_id}}&batch_id={{std.batch_id}}&academic_year_id={{filterModel.academic_year_id}}" ng-class="{'custom_disable':is_all_subject_marks_added(std.subjects)}" class="btn btn-success btn-circle"><i class="fa fa-eye"></i></a>
                                       
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#editMarks" ng-click="loadEditData(std)" class="btn btn-info btn-circle"><i class="fa fa-pencil"></i></a>
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#deleteMarks" ng-click="loadDeleteData(std)" class="btn btn-danger btn-circle"><i class="fa fa-trash-o"></i></a>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>


        <div id="myNav" class="overlay">
            <a href="javascript:onclick=customPrint2('overlay-content')" class="printAllBtn no-print"><i class="fa fa-print"></i></a>
            <a href="javascript:void(0)" class="closebtn no-print" ng-click="closeNav()">&times;</a>
            <div class="overlay-content" id="overlay-content">
                <div ng-repeat="card in multi_result_cards" ng-bind-html="card"></div>
            </div>
        </div>



        <div class="white-box" ng-show="status=='success' && students[0].is_all_subjects_marks_added=='false'">
            <div class="row">
                <div class="col-md-12 text-danger"><?php echo lang("lbl_all_subjects_marks_not_added"); ?></div>
            </div>
        </div>
        <div class="white-box" ng-show="status=='error'">
            <div class="row">
                <div class="col-md-12 text-danger"><?php echo lang("no_record"); ?></div>
            </div>
        </div>

    </div>
    <div id="editMarks" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><?php echo lang('edit_marks'); ?> ({{editData.student_name}} - {{editData.rollno}})</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                   <form class="form" name="editMarksForm">

                    <div ng-repeat="subj in editData.subjects">
                        <div ng-if="subj.is_subject && subj.exams[0].exam_detail_id != null">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label>{{subj.subject_name}}</label>
                                    <input type="number" ng-init="editData.subjects[$index].exams[0].new_marks = editData.subjects[$index].exams[0].obtained_marks" ng-model="editData.subjects[$index].exams[0].new_marks" ng-value="editData.subjects[$index].exams[0].obtained_marks" min="0" max="{{editData.subjects[$index].exams[0].total_marks}}" class="form-control" ng-if="subj.exams[0].type == 'number'">
                                    <input type="text" ng-init="editData.subjects[$index].exams[0].new_grade = editData.subjects[$index].exams[0].total_grade" ng-model="editData.subjects[$index].exams[0].new_grade" ng-value="editData.subjects[$index].exams[0].total_grade" class="form-control" ng-if="subj.exams[0].type == 'grade'">
                                </div>
                                <div ng-if="editData.subjects[$index].exams[0].activities.lenght != '0'" class="form-group col-md-6">
                                    <div class="col-md-3" ng-repeat="activity in editData.subjects[$index].exams[0].activities">
                                        <!-- set font size to smaller -->
                                        <label style="font-size: 13px;">{{activity.activity_name}}</label>
                                        <input type="number" ng-model="activity[$index].obtained_marks" class="form-control" min="0" max="{{activity.marks}}" ng-if="subj.exams[0].type == 'number'">
                                        <input type="text" ng-model="activity[$index].obtained_marks" class="form-control" ng-if="subj.exams[0].type == 'grade'">
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label><?php echo lang('lbl_remarks'); ?></label>
                                    <input type="text" ng-init="editData.subjects[$index].exams[0].new_remarks = editData.subjects[$index].exams[0].remarks" ng-model="editData.subjects[$index].exams[0].new_remarks" ng-value="editData.subjects[$index].exams[0].remarks" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang('btn_cancel') ?></button>
                <button type="button" ng-click="editsaveMarksheet(editMarksForm.$valid)" class="btn btn-success waves-effect waves-light" ng-click=""><?php echo lang('lbl_save') ?></button>
            </div>
        </div>
    </div>
</div>

<div id="deleteMarks" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">{{deleteModel.student_name}} - {{deleteModel.rollno}}</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>
                                        <div class="modal-body">
                                            <p><?php echo lang('delete_marks'); ?></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang('btn_cancel') ?></button>
                                            <button type="button" class="btn btn-danger waves-effect waves-light" ng-click="deleteMarks()"><?php echo lang('yes_delete');?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
</div>

<script type="text/javascript">
    
        function s2ab(s) {
                        var buf = new ArrayBuffer(s.length);
                        var view = new Uint8Array(buf);
                        for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                        return buf;
        }
        $("#button-a").click(function(){
        var wb = XLSX.utils.table_to_book(document.getElementById('mytabletemp'), {sheet:"Sheet JS"});
        var wbout = XLSX.write(wb, {bookType:'xlsx', bookSST:true, type: 'binary'});
        saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), 'test.xlsx');
        });
var tableToExcel = (function() {
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name) {
    if (!table.nodeType) table = document.getElementById(table)
    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    window.location.href = uri + base64(format(template, ctx))
  }
})()
</script>

<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>
   