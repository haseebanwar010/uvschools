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
        <div id="page-wrapper"  ng-controller="accountsReportController">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title"><?php echo lang('lbl_accounts'); ?></h4>
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
                                     <a class="nav-link active" data-toggle="tab" href="#profile2" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Income</span></a> 
                                 </li>
                                 <li class="nav-item">
                                     <a class="nav-link" data-toggle="tab" href="#home2" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Expense</span></a> 
                                 </li>
                             </ul>
                             <div class="tab-content">
                                <div class="tab-pane" id="home2" role="tabpanel">
                                    <div class="row">
                                      <div class="col-md-12">
                                       
                                            <!-- expese tab componenets -->
                                            <div id="expensepayroll" ng-init="getExpenseTypes();getExpensePaidBy();">
                                            <form class="form-material" id="expense_search_filter" ng-submit="expensePayroll();">
                                        <div class="row">
                                             <div class="col-md-3" id="expenseType">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_expense_type') ?></label>
                                                        <select class="form-control"
                                                        ng-model="expenseModel.expense_id" ng-init="expenseModel.expense_id='all'" 
                                                        ng-change="getExpenseCategories(expenseModel.expense_id)">
                                                            <option value="all"><?php echo lang('lbl_all') ?></option>
                                                            <option ng-repeat="i in expense_types" value="{{i.id}}">{{i.name}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3" id="expenseCategory">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_expense_category') ?></label>
                                                        <select class="form-control" ng-model="expenseModel.category_id" ng-init="expenseModel.category_id='all'" >
                                                        <option value="all"><?php echo lang('lbl_all') ?></option>
                                                        <option ng-repeat="cat in expense_categories" value="{{cat.id}}" >{{cat.category_name}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-3" id="expensePaidBy">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_expense_paid_by') ?></label>
                                                        <select class="form-control" ng-model="expenseModel.paidBy_id" ng-init="expenseModel.paidBy_id='all'">
                                                        <option value="all"><?php echo lang('lbl_all') ?></option>
                                                        <option ng-repeat="col in expense_paidBy" value="{{col.paid_by_id}}" >{{col.paid_by}}</option>   
                                                    </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3" id="payrollFilterDepartments">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_expense_date') ?></label>
                                                        <input type="text" ng-model="filterModel.from" style="height:38;" class="form-control mydatepicker-autoclose-report"
                                                         placeholder="<?php echo date('d/m/Y'); ?>" />
                                                    </div>
                                                </div>
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary pull-right"><?php echo lang('search') ?></button>
                                            </div>
                                        </div>
                                    </form>
                                        </div>
                                                   

                                    <div class="" id="stdTableContianer">
                                       
                                            <div class="row table-responsive" >
                                                <div class="col-md-12" >
                                                    <div class="white-box " >
                                                        <div style="overflow-x:auto">
                                                            <table id="expenseTableReport" class="display"  style="text-align: center;" cellspacing="0" width="100%"></table>
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
                               <!-- income tab componenets -->
                                <div id="incomepayroll" ng-init="getIncomeTypes();getIncomeCollectedBy();">
                                    <form class="form-material" id="income_search_filter" ng-submit="incomePayroll();">
                                        <div class="row">
                                             <div class="col-md-3" id="incomeType">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_income_type') ?></label>
                                                        <select class="form-control"
                                                        ng-model="incomeModel.income_id" ng-init="incomeModel.income_id='all'" 
                                                        ng-change="getIncomeCategories(incomeModel.income_id)">
                                                            <option value="all"><?php echo lang('lbl_all') ?></option>
                                                            <option ng-repeat="i in income_types" value="{{i.id}}">{{i.name}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3" id="incomeCategory">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_income_category') ?></label>
                                                        <select class="form-control" ng-model="incomeModel.category_id" ng-init="incomeModel.category_id='all'" >
                                                        <option value="all"><?php echo lang('lbl_all') ?></option>
                                                        <option ng-repeat="cat in income_categories" value="{{cat.id}}" >{{cat.category_name}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-3" id="incomeCollectedBy">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_income_collected_by') ?></label>
                                                        <select class="form-control" ng-model="incomeModel.collectedBy_id" ng-init="incomeModel.collectedBy_id='all'">
                                                        <option value="all"><?php echo lang('lbl_all') ?></option>
                                                        <option ng-repeat="col in income_collectedBy" value="{{col.collected_by_id}}" >{{col.collected_by}}</option>   
                                                    </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3" id="payrollFilterDepartments">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_income_date') ?></label>
                                                        <input type="text" ng-model="filterModel.from" style="height:38;" class="form-control mydatepicker-autoclose-report"
                                                         placeholder="<?php echo date('d/m/Y'); ?>" />
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
                                   
                                    <div class="row table-responsive">
                                    <div class="col-md-12" >
                                        <div class="white-box" >
                                            <div style="overflow-x:auto">
                                                <table id="incomeReportTable" class="display" cellspacing="0" width="100%"></table>
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
        var tableColumn = $('#incomeReportTable').DataTable().column($(this).attr('data-cloumnsindex'));
        tableColumn.visible(!tableColumn.visible());

    });

</script>

