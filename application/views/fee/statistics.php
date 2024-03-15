<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-4 col-xs-6">
                <h4 class="page-title"><?php echo lang('fees_statistics') ?></h4>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-8 col-xs-6">
                <ol class="breadcrumb">
                    <li><a href="<?php echo site_url('/dashboard') ?>"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li><a href="<?php echo site_url('/fee/statistics') ?>"><?php echo lang('fee_statistics') ?></a></li>
                </ol>
            </div>
        </div>

        <div ng-controller="duefeeController" ng-init="feeStatistics();initAcademicYears()">
            <div class="row white-box form-material" >
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang("lbl_academic_year"); ?></label>
                            <select class="form-control" name="academic_year_id" ng-model="filterModel.academic_year_id" ng-change="initClasses(filterModel.academic_year_id)">
                                <option value="-1"><?php echo lang("lbl_select_academic_year"); ?></option>
                                <option ng-repeat="ay in academicyears" value="{{ ay.id }}">{{ ay.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4" id="dfclasses">
                        <div class="form-group">
                            <label><?php echo lang('lbl_class') ?></label>
                            <select class="form-control" ng-model="filterModel.class_id"  ng-init="filterModel.class_id = 'all'"  ng-change="filterBatches(class_id);">
                                <option value="all"><?php echo lang('option_all'); ?></option>
                                <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                            </select>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-4" id="dropdownBatches">
                        <div class="form-group">
                            <label><?php echo lang('lbl_batch') ?></label>
                            <select class="form-control" ng-model="filterModel.batch_id" ng-init="filterModel.batch_id = 'all'">
                                <option value="all"><?php echo lang('option_all'); ?></option>                         
                                <option ng-repeat="batch in batches" value="{{batch.id}}">{{batch.name}}</option>
                            </select>
                        </div>
                    </div>
                
                <div class="col-md-12 p-0">
                    <div class="col-md-6 pull-left">
                        <div class="form-actions">
                            <button type="button" ng-click="feeStatistics(course, batch)" class="btn btn-primary btn-sm"><?php echo lang('search') ?></button>
                        </div>
                    </div>
                </div>
            </div>            

            <div class="row white-box">
                <div class="col-md-12">
                    <div class="row m-t-40"> 
                        <a href="javascipt:void(0)" class="col-md-6 col-lg-3 col-xlg-3" >
                            <div class="card">
                                <div class="box bg-success text-center">
                                    <h1 class="font-light text-white">{{total_school_fee}}</h1>
                                    <h4 class="text-white"><?php echo lang('total_school_fees'); ?></h4>
                                </div>
                            </div>
                        </a>
                        <a href="javascipt:void(0)" class="col-md-6 col-lg-3 col-xlg-3" >
                            <div class="card">
                                <div class="box bg-danger text-center">
                                    <h1 class="font-light text-white">{{due_fees}}</h1>
                                    <h4 class="text-white"><?php echo lang('total_due_fees'); ?></h4>
                                </div>
                            </div>
                        </a>
                        <a href="javascipt:void(0)" class="col-md-6 col-lg-3 col-xlg-3" >
                            <div class="card">
                                <div class="box bg-primary text-center">
                                    <h1 class="font-light text-white">{{total_full_paid_fee}}</h1>
                                    <h4 class="text-white"><?php echo lang('total_paid_fees'); ?></h4>
                                </div>
                            </div>
                        </a>
                        <a href="javascipt:void(0)" class="col-md-6 col-lg-3 col-xlg-3" >
                            <div class="card">
                                <div class="box bg-info text-center">
                                    <h1 class="font-light text-white">{{fully_collected_fee}}</h1>
                                    <h4 class="text-white"><?php echo lang('full_collected_fees'); ?></h4>
                                </div>
                            </div>
                        </a>

                        <a href="javascipt:void(0)" class="col-md-6 col-lg-3 col-xlg-3" >
                            <div class="card">
                                <div class="box bg-warning text-center">
                                    <h1 class="font-light text-white">{{partially_collected_fee}}</h1>
                                    <h4 class="text-white"><?php echo lang('Partially_collected_fees'); ?></h4>
                                </div>
                            </div>
                        </a>
                        <a href="javascipt:void(0)" class="col-md-6 col-lg-3 col-xlg-3" >
                            <div class="card">
                                <div class="box bg-danger text-center">
                                    <h1 class="font-light text-white">{{total_fee_defaulters}}</h1>
                                    <h4 class="text-white"><?php echo lang('total_fee_defaulter');?></h4>
                                </div>
                            </div>
                        </a>
                        <a href="javascipt:void(0)" class="col-md-6 col-lg-3 col-xlg-3" >
                            <div class="card">
                                <div class="box bg-info text-center">
                                    <h1 class="font-light text-white">{{total_paid_cash}} <?php echo $this->session->userdata("userdata")['currency_symbol']; ?></h1>
                                    <h4 class="text-white"><?php echo lang('total_paid_cash'); ?></h4>
                                </div>
                            </div>
                        </a>
                        <a href="javascipt:void(0)" class="col-md-6 col-lg-3 col-xlg-3" >
                            <div class="card">
                                <div class="box bg-danger text-center">
                                    <h1 class="font-light text-white">{{remaining_fee_amount}} <?php echo $this->session->userdata("userdata")['currency_symbol']; ?></h1>
                                    <h4 class="text-white"><?php echo lang('remaining_fee_amount'); ?></h4>
                                </div>
                            </div>
                        </a>
                        <a href="javascipt:void(0)" class="col-md-6 col-lg-3 col-xlg-3" >
                            <div class="card">
                                <div class="box bg-primary text-center">
                                    <h1 class="font-light text-white">{{total_fee_amount}} <?php echo $this->session->userdata("userdata")['currency_symbol']; ?></h1>
                                    <h4 class="text-white"><?php echo lang('total_fee_amount'); ?></h4>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!--./row-->

    </div>
</div>

<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>
   