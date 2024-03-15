<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<!-- Page Content -->
<div id="page-wrapper" ng-controller="payrollController">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('heading_all_payroll') ?></h4>
            </div>
        </div>
    </div>

	<div class="container-fluid row" id="payroll" ng-init="initDepertments();initSalaryTypesSheraz();initAdmins();">
        <div class="col-md-12"> 
            <div class="white-box">
                <form class="form-material" id="payroll_search_filter" ng-submit="payrollReport();">
                    <!--/row-->
                    <div class="row">

                        <!--/span-->
                        <div class="col-md-3" id="payrollFilterDepartments">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('title_department') ?></label>
                                <select class="form-control" ng-model="filterModel.department_id" ng-init="filterModel.department_id='all'" ng-change="getDepartmentCategories(filterModel.department_id)">
                                    <option value="all"><?php echo lang('lbl_all') ?></option>
                                    <option ng-repeat="d in departments" value="{{d.id}}">{{d.name}}</option>
                                </select>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-3" id="payrollFilterCategories">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('title_category'); ?></label>
                                <select class="form-control" ng-model="filterModel.category_id" ng-init="filterModel.category_id='all'">
                                    <option value="all"><?php echo lang('lbl_all') ?></option>
                                    <option ng-repeat="cat in deptCategories" value="{{cat.id}}">{{cat.category}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2" id="payrollFilterSalaryTypes">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('lbl_salary_type'); ?></label>
                                <select class="form-control" ng-model="filterModel.salary_type_id" ng-init="filterModel.salary_type_id='all'">
                                    <option value="all"><?php echo lang('lbl_all') ?></option>
                                    <option ng-repeat="type in salaryTypes" value="{{type.id}}">{{type.name}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2" id="payrollFilterPaidBy">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('lbl_paid_by'); ?></label>
                                <select class="form-control" ng-model="filterModel.admin_id" ng-init="filterModel.admin_id='all'">
                                    <option value="all"><?php echo lang('lbl_all') ?></option>
                                    <option ng-repeat="pb in paid_by" value="{{pb.id}}">{{pb.name}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('lbl_status'); ?></label>
                                <select class="form-control" ng-model="filterModel.status_id" ng-init="filterModel.status_id='all'">
                                    <option value="all"><?php echo lang('lbl_all') ?></option>
                                    <option value="0">UnPaid</option>
                                    <option value="1">Paid</option>
                                    <option value="2">Partially Paid</option>
                                </select>
                            </div>
                        </div>



                        <!--/span-->

                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-search "></i> <?php echo lang("search"); ?></button>
                        </div>
                    </div>
                    <!--/row-->
                </form>
            </div>

        </div>
    </div>

    <div class="table-responsive container-fluid">
        <div class="white-box" >
            <div style="overflow-x:auto">
                <table id="empReportTable" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
    
</div>


</div>

<?php include(APPPATH . "views/inc/footer.php"); ?>