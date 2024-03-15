<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<style>
    /*.select2-container--default .select2-selection--multiple{
        max-height: 35px;
        overflow: auto;
    }
    .select2-selection__rendered {
        max-height: 30px;
        overflow: auto;
    }
    .select2-container .select2-selection--single {
        max-height: 30px;
        overflow: auto;
    }
    .select2-selection__arrow {
        max-height: 30px;
        overflow: auto;
        }*/
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            font-size: 10px !important;
        }
    </style>
    <!-- Page Content -->
    <div>
        <div id="page-wrapper"  ng-controller="feeReportController">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title"><?php echo ucfirst(strtolower(lang('fee_report'))); ?></h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                        <ol class="breadcrumb">
                            <li><a href="<?php echo site_url('/dashboard') ?>"><?php echo lang('crumb_dashboard') ?></a></li>
                            <li><a href="<?php echo site_url('/reports/all') ?>"><?= lang('fee_report') ?></a></li>
                            <li class="active"><a href="<?php echo site_url('/reports/fee') ?>"><?= lang('heading_all_fee') ?></a></li>
                        </ol>
                    </div>
                </div>
                <!-- End alert message -->
                <!-- .row -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-tabs customtab" role="tablist">
                                    <li class="nav-item">
                                     <a class="nav-link active" data-toggle="tab" href="#profile2" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Fee Summary</span></a> 
                                 </li>
                                 <li class="nav-item">
                                     <a class="nav-link" data-toggle="tab" href="#home2" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Fee Report</span></a> 
                                 </li>
                             </ul>
                             <div class="tab-content">
                                <div class="tab-pane" id="home2" role="tabpanel">
                                    <div class="row">
                                      <div class="col-sm-12">
                                        <div class="white-box">
                                            <!-- new changes by Yasir 01-03-2018 -->
                                            <div id="empReprotDev">
                                                <form class="form-material" name="attFilterForm" ng-submit="onsubmit(attFilterForm.$valid)" novalidate="">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label class="control-label"><?php echo lang('lbl_name') ?></label>
                                                                <input type="text" ng-model="filterModel.name"  class="form-control " placeholder="Search" />
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-2">
                                                            <div class="form-group" ng-init="initAcdamicYearFeeReport()">
                                                                <label><?php echo lang('lbl_academic_year') ?></label>
                                                                <select class="form-control" id="academicyearsReport" ng-change="initClasses(reportAcdamicYear_id)" ng-model="reportAcdamicYear_id" name="reportAcdamicYear_id">
                                                                     <option value="-1"><?php echo lang("lbl_select_academic_year"); ?></option>
                                                                    <option ng-repeat="year in academicyearsReport" ng-value="year.id">{{year.name}}</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2" id="attFilterClasses">     
                                                            <div class="form-group">
                                                                <label><?php echo lang('lbl_classes') ?></label>
                                                                <select id="reportselect2Classes" class="form-control yasir2-payroll-select2" style="width: 100%;" multiple="" ng-change="initBatches(); iniType(reportAcdamicYear_id)" ng-model="reportclasses">
                                                                    <option ng-repeat="cls in classes" ng-value="cls.id">{{cls.name}}</option>
                                                                </select> 
                                                                <div class="checkbox checkbox-info">
                                                                    <input id="Feeclasses_selectall" type="checkbox">
                                                                    <label for="Feeclasses_selectall">Select All</label>
                                                                </div>
                                                                <script>
                                                                    $(document).ready(function(){
                                                                        $("#Feeclasses_selectall").click(function(){
                                                                            $( ".select2-container--default" ).scrollTop( 0 );
                                                                            if($("#Feeclasses_selectall").is(':checked') ){
                                                                                $("#reportselect2Classes > option").select2().prop("selected",true);
                                                                            }else{
                                                                                $("#reportselect2Classes > option").select2().removeAttr("selected").trigger('change');
                                                                                $("#Report_batches_selectall").attr("checked",false);
                                                                            }
                                                                            $("#reportselect2Classes").select2().trigger('change');
                                                                        });
                                                                    });
                                                                </script>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2" id="initReportBatchesSummary">
                                                            <div class="form-group">
                                                                <label><?php echo lang('lbl_batches') ?></label>
                                                                <select id="reportselect2Batches" class="form-control yasir2-payroll-select2" style="width: 100%;" multiple="" ng-model="batchesSelect">
                                                                    <option ng-repeat="bth in batches" ng-value="bth.id">{{bth.name}} - {{bth.class_name}}</option>
                                                                </select> 
                                                                <div class="checkbox checkbox-info">
                                                                    <input id="Report_batches_selectall" ng-disabled="batches.length==0 || batches==undefined" type="checkbox" name="isBatchesSelectAll" ng-model="batchesSelectAllReport">
                                                                    <label for="Report_batches_selectall">Select All</label>
                                                                </div>
                                                                <script>
                                                                    $(document).ready(function(){
                                                                        $("#Report_batches_selectall").click(function(){
                                                                            if($("#Report_batches_selectall").is(':checked') ){
                                                                                $("#reportselect2Batches > option").select2().prop("selected",true).trigger('change');
                                                                            }else{
                                                                                $("#reportselect2Batches > option").select2().removeAttr("selected").trigger('change');
                                                                            }
                                                                        });
                                                                    });
                                                                </script>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2" id="filterFeeType">
                                                            <div class="form-group">
                                                                <label><?php echo lang('lbl_fee_types') ?></label>
                                                                <select id="feeTypeselect2" class="form-control yasir2-payroll-select2" style="width: 100%;" multiple="" ng-model="FeeTypeSelect">
                                                                    <option ng-repeat="ft in feeType" ng-value="ft.id">{{ft.name}}</option>
                                                                </select> 
                                                                <div class="checkbox checkbox-info">
                                                                    <input id="feeType_selectall" ng-disabled="feeType.length==0 || feeType==undefined" type="checkbox" name="isBatchesSelectAll" ng-model="FeeTypeSelectAll">
                                                                    <label for="feeType_selectall">Select All</label>
                                                                </div>
                                                                <script>
                                                                    $(document).ready(function(){
                                                                        $("#feeType_selectall").click(function(){
                                                                            if($("#feeType_selectall").is(':checked') ){
                                                                                $("#feeTypeselect2 > option").select2().prop("selected",true).trigger('change');
                                                                            }else{
                                                                                $("#feeTypeselect2 > option").select2().removeAttr("selected").trigger('change');
                                                                            }
                                                                        });
                                                                    });
                                                                </script>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-2" id="filterFeeDiscount">
                                                            <div class="form-group">
                                                                <label><?php echo lang('lbl_discount_type') ?></label>
                                                                <select id="discountselect2" ng-init="iniDiscountType()" class="form-control yasir2-payroll-select2" style="width: 100%;" multiple="" ng-model="discountSelect">
                                                                    <option ng-repeat="ds in feeDiscount" ng-value="ds.id">{{ds.name}}</option>
                                                                </select> 
                                                                <div class="checkbox checkbox-info">
                                                                    <input id="discount_selectall" ng-disabled="feeDiscount.length==0 || feeDiscount==undefined" type="checkbox" name="isBatchesSelectAll" ng-model="discountSelectAll">
                                                                    <label for="discount_selectall">Select All</label>
                                                                </div>
                                                                <script>
                                                                    $(document).ready(function(){
                                                                        $("#discount_selectall").click(function(){
                                                                            if($("#discount_selectall").is(':checked') ){
                                                                                $("#discountselect2 > option").select2().prop("selected",true).trigger('change');
                                                                            }else{
                                                                                $("#discountselect2 > option").select2().removeAttr("selected").trigger('change');
                                                                            }
                                                                        });
                                                                    });
                                                                </script>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-2" id="filterCollector">
                                                            <div class="form-group">
                                                                <label><?php echo lang('lbl_collector') ?></label>
                                                                <select id="collectorselect2" ng-init="iniCollector()" class="form-control yasir2-payroll-select2" style="width: 100%;" multiple="" ng-model="collectorSelect">
                                                                    <option ng-repeat="cltr in collectors" ng-value="cltr.id">{{cltr.name}}</option>
                                                                </select> 
                                                                <div class="checkbox checkbox-info">
                                                                    <input id="collector_selectall" ng-disabled="collectors.length==0 || collectors==undefined" type="checkbox" name="isBatchesSelectAll" ng-model="collectorSelectAll">
                                                                    <label for="collector_selectall">Select All</label>
                                                                </div>
                                                                <script>
                                                                    $(document).ready(function(){
                                                                        $("#collector_selectall").click(function(){
                                                                            if($("#collector_selectall").is(':checked') ){
                                                                                $("#collectorselect2 > option").select2().prop("selected",true).trigger('change');
                                                                            }else{
                                                                                $("#collectorselect2 > option").select2().removeAttr("selected").trigger('change');
                                                                            }
                                                                        });
                                                                    });
                                                                </script>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label class="control-label"><?php echo lang('lbl_mode') ?></label>
                                                                <select class="form-control" ng-model="filterModel.mode">
                                                                    <option value=""><?php echo lang('lbl_all') ?></option>
                                                                    <option value="cash"><?php echo lang('option_cash') ?></option>
                                                                    <option value="cheque"><?php echo lang('option_cheque') ?></option>
                                                                    <option value="DD"><?php echo lang('lbl_dd') ?></option>
                                                                </select>
                                                            </div>
                                                        </div>


                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label class="control-label"><?php echo lang('lbl_fee_from_date') ?></label>
                                                                <input type="text" ng-model="filterModel.from" style="height:38;" class="form-control mydatepicker-autoclose-report" placeholder="<?php echo date('d/m/Y'); ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label class="control-label"><?php echo lang('lbl_fee_to_date') ?></label>
                                                                <input type="text" ng-model="filterModel.to" style="height:38;" class="form-control mydatepicker-autoclose-report" placeholder="<?php echo date('d/m/Y'); ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group has-success">
                                                                <div class="checkbox checkbox-info">
                                                                    <input id="check_defaulter" type="checkbox" name="isDue" ng-model="filterModel.isDue" class="ng-valid ng-dirty ng-valid-parse ng-touched ng-empty" autocomplete="off">
                                                                    <label for="check_defaulter"><?php echo lang('lbl_defaulters');?></label>

                                                                </div>
                                                                <div class="checkbox checkbox-info">

                                                                 <input id="check_partial" type="checkbox" name="isDue" ng-model="filterModel.partial" class="ng-valid ng-dirty ng-valid-parse ng-touched ng-empty" autocomplete="off">
                                                                 <label for="check_partial"><?php echo lang('lbl_fee_partially_paid');?></label>
                                                             </div>
                                                         </div>
                                                     </div>
                                                     <div class="col-md-2">
                                                        <button type="submit" class="btn btn-primary pull-right"><?php echo lang('search') ?></button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>                

                                    <div class="" id="stdTableContianer">
                                        <div class="row col-md-12">
                                            <label style="margin-right:10px;"><?php echo lang('lbl_columns') ?> :</label>
                                            <input type="checkbox" id="lbl_nationality" style ="cursor: pointer;" class="showHideCol btn btn-outline-info" data-cloumnsindex = "4"/><label class="custom-control-label " style ="cursor: pointer;  margin-right: 15px; margin-left: 5px" for="lbl_nationality" ><?php echo lang('lbl_nationality') ?></label>
                                            <input type="checkbox" id="customCheck2" style ="cursor: pointer;" class="showHideCol btn btn-outline-info" data-cloumnsindex = "7"/><label class="custom-control-label " style ="cursor: pointer;  margin-right: 15px; margin-left: 5px" for="customCheck2" ><?php echo lang('lbl_fee_type_amount') ?></label>
                                            <input type="checkbox" id="customCheck1" style ="cursor: pointer;" class="showHideCol btn btn-outline-info" data-cloumnsindex = "8"/><label class="custom-control-label " style ="cursor: pointer; margin-right: 15px; margin-left: 5px" for="customCheck1" ><?php echo lang('lbl_discount_type') ?></label>
                                            <input type="checkbox" id="customCheck3" style ="cursor: pointer;" class="showHideCol btn btn-outline-info" data-cloumnsindex = "9"/><label class="custom-control-label " style ="cursor: pointer;  margin-right: 15px; margin-left: 5px" for="customCheck3" ><?php echo lang('lbl_fee_discount_amount') ?></label>
                                            <input type="checkbox" id="customCheck4" style ="cursor: pointer;" class="showHideCol btn btn-outline-info" data-cloumnsindex = "14"/><label class="custom-control-label " style ="cursor: pointer;  margin-right: 15px; margin-left: 5px" for="customCheck4" ><?php echo lang('lbl_mode') ?></label>
                                            <input type="checkbox" id="customCheck5" style ="cursor: pointer;" class="showHideCol btn btn-outline-info" data-cloumnsindex = "12"/><label class="custom-control-label " style ="cursor: pointer;  margin-right: 15px; margin-left: 5px" for="customCheck4" ><?php echo lang("father_name");?></label>
                                            <input type="checkbox" id="customCheck6" style ="cursor: pointer;" class="showHideCol btn btn-outline-info" data-cloumnsindex = "13"/><label class="custom-control-label " style ="cursor: pointer;  margin-right: 15px; margin-left: 5px" for="customCheck4" ><?php echo lang("imp_parent_contact");?></label>

                                            <div class="row table-responsive" >
                                                <div class="col-md-12" >
                                                    <div class="white-box " >
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

                        <div class="tab-pane active" id="profile2" role="tabpanel">
                            <div class="col-md-12" id="profile22">
                                <!-- new changes by Yasir 01-03-2018 -->
                                <div id="empReprotDev">
                                    <form class="" name="sumFilterForm" ng-submit="onsubmit_feesummary(sumFilterForm.$valid)" novalidate="">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang("lbl_academic_year"); ?></label>
                                                    <select class="form-control" name="academic_year_id" ng-model="academic_year_id" required="" ng-init="initAcademicYears()" ng-change="initClassesWithAcdmicYear(academic_year_id)">
                                                        <option value="-1"><?php echo lang("lbl_select_academic_year"); ?></option>
                                                        <option ng-repeat="ay in academicyears" value="{{ ay.id }}">{{ ay.name }}</option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-md-3" id="attFilterClasses_feesummary">     
                                                <div class="form-group">
                                                    <label><?php echo lang('lbl_classes') ?></label>
                                                    <select id="yasirselect2" class="form-control yasir2-payroll-select2" style="width: 100%;" multiple="" ng-change="initBatchesSummary()" ng-model="yasirclasses" required="">
                                                        <option ng-repeat="cls in classeswithacdmicyear" ng-value="cls.id">{{cls.name}}</option>
                                                    </select> 
                                                    <div class="checkbox checkbox-info">
                                                        <input id="classes_selectall" type="checkbox" >
                                                        <label for="classes_selectall">Select All</label>
                                                    </div>
                                                    <script>
                                                        $(document).ready(function(){
                                                            $("#classes_selectall").click(function(){
                                                                $( ".select2-container--default" ).scrollTop( 0 );
                                                                if($("#classes_selectall").is(':checked') ){
                                                                    $("#yasirselect2 > option").select2().prop("selected",true);
                                                                }else{
                                                                    $("#yasirselect2 > option").select2().removeAttr("selected").trigger('change');
                                                                    $("#batches_selectall").attr("checked",false);
                                                                }
                                                                $("#yasirselect2").select2().trigger('change');
                                                            });
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                            <div class="col-md-3" id="initBatchesSummary">
                                                <div class="form-group">
                                                    <label><?php echo lang('lbl_batches') ?></label>
                                                    <select id="rizwanselect2" class="form-control yasir2-payroll-select2" style="width: 100%;" multiple="" ng-model="rizwanBatchSelect" required="">
                                                        <option ng-repeat="bth in batches_summary" ng-value="bth.id">{{bth.name}} - {{bth.class_name}}</option>
                                                    </select> 
                                                    <div class="checkbox checkbox-info">
                                                        <input id="batches_selectall" ng-disabled="batches_summary.length==0 || batches_summary==undefined" type="checkbox" name="isBatchesSelectAll" ng-model="batchesSelectAll">
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
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <select class="form-control" ng-model="feeSummaryStatus" ng-init="feeSummaryStatus='all'">
                                                        <option value="all">all</option>
                                                        <option value="paid">paid</option>
                                                        <option value="unpaid">unpaid</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary pull-right"><?php echo lang('search') ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="" id="stdTableContianer">
                                <div class="row col-md-12" style="margin-bottom: 15px; padding-right:0; padding-left:0;">
                                    <div style="margin-left: 25px;">
                                        <label style="margin-right:10px;"><?php echo lang('lbl_columns') ?> :</label>
                                        <input type="checkbox" id="customCheck02_feeSummary" style ="cursor: pointer;" class="showHideCol_feeSummary btn btn-outline-info" data-cloumnsindex = "1"/><label class="custom-control-label " style ="cursor: pointer; margin-right: 15px; margin-left: 5px" for="customCheck02_feeSummary" ><?php echo lang('lbl_avatar') ?></label>
                                        <input type="checkbox" id="customCheck03_feeSummary" style ="cursor: pointer;" class="showHideCol_feeSummary btn btn-outline-info" data-cloumnsindex = "5"/><label class="custom-control-label " style ="cursor: pointer; margin-right: 15px; margin-left: 5px" for="customCheck03_feeSummary" ><?php echo lang('lbl_paid_amount') ?></label>
                                        <input type="checkbox" id="customCheck04_feeSummary" style ="cursor: pointer;" class="showHideCol_feeSummary btn btn-outline-info" data-cloumnsindex = "6"/><label class="custom-control-label " style ="cursor: pointer; margin-right: 15px; margin-left: 5px" for="customCheck04_feeSummary" ><?php echo lang('lbl_total_amount') ?></label>
                                        <input type="checkbox" id="customCheck8_feeSummary" style ="cursor: pointer;" class="showHideCol_feeSummary btn btn-outline-info" data-cloumnsindex = "8"/><label class="custom-control-label " style ="cursor: pointer; margin-right: 15px; margin-left: 5px" for="customCheck5_feeSummary" ><?php echo lang('lbl_balance') ?></label>
                                        <input type="checkbox" id="customCheck9_feeSummary" style ="cursor: pointer;" class="showHideCol_feeSummary btn btn-outline-info" data-cloumnsindex = "9"/><label class="custom-control-label " style ="cursor: pointer; margin-right: 15px; margin-left: 5px" for="customCheck9_feeSummary" ><?php echo lang("father_name");?></label>
                                        <input type="checkbox" id="customCheck10_feeSummary" style ="cursor: pointer;" class="showHideCol_feeSummary btn btn-outline-info" data-cloumnsindex = "10"/><label class="custom-control-label " style ="cursor: pointer; margin-right: 15px; margin-left: 5px" for="customCheck10_feeSummary" ><?php echo lang("imp_parent_contact");?></label>
                                    </div>

                                    <div class="table-responsive">
                                        <div class="white-box" >
                                            <div style="overflow-x:auto">
                                                <table id="myTablee_feeSummary" class="display" cellspacing="0" width="100%"></table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--page content end-->

    </div>
</div>
</div>
</div>
<style>
    .changeColor{
        color : Green;
    }

</style>
<?php include(APPPATH . "views/inc/footer.php"); ?>
<script>

    $('.showHideCol').on('click', function () {
        var tableColumn = $('#myTablee').DataTable().column($(this).attr('data-cloumnsindex'));
        tableColumn.visible(!tableColumn.visible());

    });

    $('.showHideCol_feeSummary').on('click', function () {
        var tableColumn = $('#myTablee_feeSummary').DataTable().column($(this).attr('data-cloumnsindex'));
        tableColumn.visible(!tableColumn.visible());

    });

</script>

