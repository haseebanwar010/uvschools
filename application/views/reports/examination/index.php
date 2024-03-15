<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<!-- Page Content -->
<div id="page-wrapper" ng-controller="reportExamination" ng-init="loadingExaminationReport()">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('reports_all') ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="<?php echo site_url('/dashboard') ?>"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li><a href="<?php echo site_url('/reports') ?>"><?php echo lang('reports_all') ?></a></li>
                    <li><a href="<?php echo site_url('/reports/admission') ?>"><?php echo lang('lbl_admission') ?></a></li>
                </ol>

            </div>
        </div>
       
        <div class="white-box well" >
            <div>
               <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    
                    <div class="white-box well" id="stdReprotDev">
                <form class="form-material" name="ExamReportForm" ng-submit="onsubmit2(ExamReportForm.$valid)" novalidate="">
                <div class="row">
                    <div class="col-md-3" id="examReportFilterAcademicYears">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang("lbl_academic_year"); ?></label>
                            <select class="form-control" name="academic_year_id" ng-model="filterModel.academic_year_id" required="" ng-init="initAcademicYears()" ng-change="initClasses(filterModel.academic_year_id)">
                                <option value=""><?php echo lang("lbl_select_academic_year"); ?></option>
                                <option ng-repeat="ay in academicyears" value="{{ ay.id }}">{{ ay.name }}</option>
                            </select>
                        </div>
                    </div>
                    <!-- <div class="col-md-3" id="examFilterClasses">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang('lbl_class') ?></label>
                            <select class="form-control yasir2-payroll-select2" id="examClasses" ng-model="classes" ng-change="initBatches()" required="" style="width: 100%;" multiple>
                                <option ng-repeat="cls in classeswithacdmicyear" value="{{cls.id}}">{{cls.name}}</option>
                            </select>
                            <div class="checkbox checkbox-info">
                                <input id="classes_selectall" type="checkbox" name="isClassesSelectAll" ng-model="classesSelectAll">
                                <label for="classes_selectall">Select All</label>
                            </div>
                        </div>
                    </div> -->
                   <!--  <script>
                        $(document).ready(function(){
                            $("#classes_selectall").click(function(){
                                $( ".select2-container--default" ).scrollTop( 0 );
                                if($("#classes_selectall").is(':checked') ){
                                    $("#examClasses > option").select2().prop("selected",true);
                                }else{
                                    $("#examClasses > option").select2().removeAttr("selected").trigger('change');
                                    $("#batches_selectall").attr("checked",false);
                                }
                                $("#examClasses").select2().trigger('change');
                            });
                        });
                    </script> -->
                    <div class="col-md-3" id="examFilterClasses">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang('lbl_class') ?></label>
                            <select class="form-control" id="examClasses" ng-model="classes" ng-change="initBatches()" required="" ng-init="classes = ''">
                                <option value=""><?php echo lang('select_course') ?></option>
                                <option ng-repeat="cls in classeswithacdmicyear" value="{{cls.id}}">{{cls.name}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3" id="examFilterBatches">
                        <div class="form-group">
                            <label><?php echo lang('lbl_batches') ?></label>
                            <select id="rizwanselect2" class="form-control yasir2-payroll-select2" style="width: 100%;" multiple="" ng-model="BatchSelect" required="">
                                <option ng-repeat="bth in batches" ng-value="bth.id">{{bth.name}}</option>
                            </select> 
                            <div class="checkbox checkbox-info">
                                <input id="batches_selectall" ng-disabled="batches.length==0 || batches==undefined" type="checkbox" name="isBatchesSelectAll" ng-model="batchesSelectAll">
                                <label for="batches_selectall">Select All</label>
                            </div>
                            <script>
                                $(document).ready(function(){
                                    $("#batches_selectall").click(function(){
                                        if($("#batches_selectall").is(':checked') ){
                                            $("#rizwanselect2 > option").select2().prop("selected",true).trigger('change');
                                        }else{
                                            $("#rizwanselect2 > option").select2().removeAttr("selected").trigger('change');
                                        }
                                    });
                                });
                            </script>
                        </div>
                    </div>
                    <div class="col-md-3" id="examFilterExam">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang('lbl_exam_session') ?></label>
                            <select class="form-control" ng-model="filterModel.exam_id" required="" >
                                <option value=""><?php echo lang('lbl_select_exam') ?></option>
                                <option ng-repeat="em in exams" value="{{em.id}}">{{em.title}}</option>
                            </select>
                        </div>
                    </div>
                   
                </div>

                <div class="row">
                    <div class="col-md-8">
                       
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary pull-right"><?php echo lang('search') ?></button>
                    </div>
                </div>
            </form>
        </div>
            <div class="table-responsive">
               
                    <div style="overflow-x:auto">
                        <table id="myTablee" class="display"  style="text-align: center;" cellspacing="0" width="100%"></table>
                    </div>
              
            </div>
        </div>
     
    </div>
</div> 
                
                
            </div>
        </div>
 

    </div>
</div>

<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>
   