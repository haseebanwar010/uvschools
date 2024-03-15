<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<style>
    span.b {
        display: inline-block;
        width: 20px;
        height: 20px;
        padding: 0px;
        margin-right: 5px; 
        vertical-align: top;
        border-radius: 5px;
        margin-bottom: 5px;
    }
    .row-content-center{
        display: flex; 
        justify-content: center; 
        align-items: center;
    }
    .center-vertical{
        vertical-align: middle !important;
    }
    .table-theme{
        background-color: #e5e5e5;
    }
    @media screen and (max-width: 768px) {
        span.b { width: 20px; height: 20px; padding:0; border-radius: 50%; }
    }
    @media screen and (max-width: 375px) {
        span.b { width: 15px; height: 15px; padding:0; border-radius: 50%; }
    }

    @media screen and (max-width: 414px) {
        span.b { width: 15px; height: 15px; padding:0; border-radius: 50%; }
    }
    @media screen and (max-width: 411px) {
        span.b { width: 15px; height: 15px; padding:0; border-radius: 50%; }
    }

    @media screen and (max-width: 320px) {
        span.b { width: 12px; height: 12px; padding:0; border-radius: 50%; }
    }
    @media screen and (max-height: 450px) {
        span.b { width: 15px; height: 15px; padding:0; border-radius: 50%; }
    }
</style>
<link href="assets/dist/css/pages/user-card.css" rel="stylesheet">
<!-- Page Content -->
<div id="page-wrapper" ng-controller="monitoringController" ng-init="initAcademicYears()">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?= lang("lbl_academic_monitoring"); ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="dashboard/"><?= lang("lbl_dashboard"); ?></a></li>
                    <li class="active"><?= lang("lbl_academic_monitoring"); ?></li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <div class="hint"><?php echo lang('help_academic_monitoring'); ?></div>
        <!--row-->
        <div class="white-box well" id="academic-monitoring-container">
            <form class="form-material" name="acMFilter" ng-submit="onSubmit(acMFilter.$valid)" novalidate="">
                <div class="row">
                    <div class="col-md-4" id="acMFilterAcademicYears">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang("lbl_academic_year"); ?></label>
                            <select class="form-control" name="academic_year_id" ng-model="filterModel.academic_year_id" required="" ng-change="initClasses(filterModel.academic_year_id)">
                                <option value=""><?php echo lang("lbl_select_academic_year"); ?></option>
                                <option ng-repeat="ay in academicyears" value="{{ ay.id }}">{{ ay.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4" id="acMFilterClasses">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang('lbl_class') ?></label>
                            <select class="form-control" ng-model="filterModel.class_id" required="">
                                <option value=""><?php echo lang('select_course') ?></option>
                                <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                            </select>
                        </div>
                    </div> 
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang('lbl_date') ?></label>
                            <input type="text" ng-model="filterModel.date" required="" class="form-control mydatepicker-autoclose" placeholder="<?php echo date('d/m/Y'); ?>" />
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
        <!--row-->
        <div class="row m-0">
            <div class="col-md-12 p-0" ng-if="is_holiday">
                <div class="white-box">
                    <span class="text-danger">{{message}}</span>
                </div>
            </div>
            <div class="col-md-12 p-0" ng-if="monitoring.length>0 && !is_holiday">
                <div class="white-box">
                    <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center"><?php echo lang('lbl_batches');?></th>
                                <th class="text-center"><?php echo lang('lbl_date');?></th>
                                <th class="text-center"><?php echo lang('lbl_attendance');?></th>
                                <th class="text-center" colspan="{{monitoring[0].batches[0].subjects.length}}"><?php echo lang('lbl_subjects');?></th>
                                <th class="text-center"><?php echo lang('lbl_summary');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center" ng-repeat="b in monitoring[0].batches">
                                <td class="text-center">{{b.name}}</td>
                                <td class="text-center">{{filterModel.date}}</td>
                                <td>
                                    <div class="row row-content-center">
                                        <span class="b" style="background-image: url('assets/images/attendance.png'); background-repeat: no-repeat; background-size: 20px 20px;" title="<?php echo lang('lbl_attendance');?>"></span> 
                                        <span class="b">
                                            <i ng-if="b.is_attendance_marked==='false'" class="fa fa-close text-danger"></i>
                                            <i ng-if="b.is_attendance_marked==='true'" class="fa fa-check text-primary"></i>
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center" colspan="{{monitoring[0].batches[0].subjects.length}}" style="padding:0;" ng-class="{'center-vertical': b.subjects.length==0}">
                                    <!--<table width="100%" ng-if="b.subjects.length>0" class="table table-default" class="borderless" style="background-color:transparent;">
                                        <tr>
                                            <td>-->
                                                <table class="table table-default" style="margin-bottom:0; border:0;" ng-if="b.subjects.length>0">
                                                    <tr class="table-theme">
                                                        <th class="text-center" ng-repeat="m2 in b.subjects" style="min-width:52px;">{{m2.name}}</th>
                                                    </tr>
                                                    <tr>
                                                        <td ng-repeat="m3 in b.subjects">
                                                            <img ng-show="m3.teacher_info.id" src="uploads/user/{{m3.teacher_info.avatar}}" title="{{m3.teacher_info.name}}" width="30" class="img-circle"/>
                                                            <img ng-show="!m3.teacher_info.id" src="uploads/user/profile.png" title="Teacher Name" width="30" class="img-circle"/>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td ng-repeat="mm in b.subjects" width="{{col_width}}%" class="text-center">
                                                            <!--<div class="row row-content-center">
                                                                <span class="b" style="background-image: url('assets/images/attendance.png'); background-repeat: no-repeat; background-size: 20px 20px;" title="<?php echo lang('lbl_attendance');?>"></span> 
                                                                <span class="b" ng-if="mm.id==bsub.id" ng-repeat="bsub in b.subjects">
                                                                    <i ng-if="b.is_attendance_marked==='false'" class="fa fa-close text-danger"></i>
                                                                    <i ng-if="b.is_attendance_marked==='true'" class="fa fa-check text-primary"></i>
                                                                </span>
                                                            </div>-->
                                                            <div class="row row-content-center">
                                                                <span class="b" style="background-image: url('assets/images/assignment.jpeg'); background-repeat: no-repeat; background-size: 20px 20px;" title="<?php echo lang('lbl_assignment');?>"></span> 
                                                                <span class="b" ng-if="mm.id==bsub.id" ng-repeat="bsub in b.subjects">
                                                                    <i ng-if="bsub.sm_summary['assignment_count']==0" class="fa fa-close text-danger"></i>
                                                                    <i ng-if="bsub.sm_summary['assignment_count']>0" class="fa fa-check text-primary"></i>
                                                                </span>
                                                            </div>
                                                            <div class="row row-content-center">
                                                                <span class="b" style="background-image: url('assets/images/classwork1.jpeg'); background-repeat: no-repeat; background-size: 20px 20px;" title="<?php echo lang('lbl_class_work');?>"></span> 
                                                                <span class="b" ng-if="mm.id==bsub.id" ng-repeat="bsub in b.subjects">
                                                                    <i ng-if="bsub.sm_summary['classwork_count']==0" class="fa fa-close text-danger"></i>
                                                                    <i ng-if="bsub.sm_summary['classwork_count']>0" class="fa fa-check text-primary"></i>
                                                                </span>
                                                            </div>
                                                            <div class="row row-content-center">
                                                                <span class="b" style="background-image: url('assets/images/homework.jpeg'); background-repeat: no-repeat; background-size: 20px 20px;" title="<?php echo lang('lbl_homework');?>"></span> 
                                                                <span class="b" ng-if="mm.id==bsub.id" ng-repeat="bsub in b.subjects">
                                                                    <i ng-if="bsub.sm_summary['homework_count']==0" class="fa fa-close text-danger"></i>
                                                                    <i ng-if="bsub.sm_summary['homework_count']>0" class="fa fa-check text-primary"></i>
                                                                </span>
                                                            </div>
                                                            <div class="row row-content-center">
                                                                <span class="b" style="background-image: url('assets/images/studymaterial.jpeg'); background-repeat: no-repeat; background-size: 20px 20px;" title="<?php echo lang('study_material');?>"></span> 
                                                                <span class="b" ng-if="mm.id==bsub.id" ng-repeat="bsub in b.subjects">
                                                                    <i ng-if="bsub.sm_summary['studymaterial_count']==0" class="fa fa-close text-danger"></i>
                                                                    <i ng-if="bsub.sm_summary['studymaterial_count']>0" class="fa fa-check text-primary"></i>
                                                                </span>
                                                            </div>
                                                            <div class="row row-content-center">
                                                                <span class="b" style="background-image: url('assets/images/studyplan.png'); background-repeat: no-repeat; background-size: 20px 20px;" title="<?php echo lang('lbl_syllabus');?>"></span> 
                                                                <span class="b" ng-if="mm.id==bsub.id" ng-repeat="bsub in b.subjects">
                                                                    <i ng-if="bsub.sp_summary.length==0" class="fa fa-close text-danger"></i>
                                                                    <i ng-if="bsub.sp_summary.length>0" class="fa fa-check text-primary"></i>
                                                                </span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            <!--</td>
                                        </tr>
                                    </table>-->
                                    <span ng-if="b.subjects.length == 0" class="text-danger"><i><?php echo lang("lbl_no_subject_found"); ?></i></span>
                                </td>
                                <td style="width: 200px;">
                                    <div class="card-body text-center">
                                        <div class="row">
                                            <div class="col">
                                                <h3 class="m-b-0">{{b.sp_summary.length}}</h3>
                                                <h5 class="font-light text-success"><?php echo lang("lbl_syllabus_done");?></h5>
                                            </div>
                                            <div class="col">
                                                <h3 class="m-b-0">{{b.sm_summary.length}}</h3>
                                                <h5 class="font-light text-info"><?php echo lang('study_materials_uploaded'); ?></h5>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
        <!--./row-->
        <!-- .right-sidebar -->
        <!-- Include yout right sidebar here -->
        <!-- /.right-sidebar -->
    </div>
<?php include(APPPATH . "views/inc/footer.php"); ?>