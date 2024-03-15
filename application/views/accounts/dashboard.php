<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<style>
    .modal {
      overflow-y:auto;
  }
</style>
<?php 
        $UserData = $this->session->userdata('userdata');
        $role_id = $UserData['role_id'];

            $ci = & get_instance();
            $arr = $ci->session->userdata("userdata")['persissions'];
            $array = json_decode($arr);
            if (isset($array)) {
                $accDashboard = $accCollect = $accPay = $accDeposite = $accDepositeEdit = $accWithdraw = $expenseEdit =$expenseDelete = $virtual_accounts = 0;
                foreach ($array as $key => $value) {
                    if (in_array('accounts-dashboard', array($value->permission)) && $value->val == 'true') {
                        $accDashboard = '1';
                    }
                    if (in_array('accounts-collect', array($value->permission)) && $value->val == 'true') {
                        $accCollect = '1';
                    }
                    if (in_array('accounts-pay', array($value->permission)) && $value->val == 'true') {
                        $accPay = '1';
                    }
                    if (in_array('accounts-deposit', array($value->permission)) && $value->val == 'true') {
                        $accDeposite = '1';
                    }
                    if (in_array('accounts-depositEdit', array($value->permission)) && $value->val == 'true') {
                        $accDepositeEdit = '1';
                    }
                    if (in_array('accounts-withdraw', array($value->permission)) && $value->val == 'true') {
                        $accWithdraw = '1';
                    }
                    if (in_array('accounts-expenseEdit', array($value->permission)) && $value->val == 'true') {
                        $expenseEdit = '1';
                    }
                    if (in_array('accounts-expenseDelete', array($value->permission)) && $value->val == 'true') {
                        $expenseDelete = '1';
                    }
                    if (in_array('accounts-virtual_accounts', array($value->permission)) && $value->val == 'true') {
                        $virtual_accounts = '1';
                    }

                }
            }
            ?>
<!-- Page Content -->
<div id="page-wrapper" ng-controller="accountController" ng-init="getAllData();initAcademicYears();">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('accounts_dashboard') ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="#"><?php echo lang('lbl_dashboard') ?></a></li>
                    <li><a href="#"><?php echo lang('accounts') ?></a></li>
                    <li class="active"><?php echo lang('accounts_dashboard') ?></li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <!-- Page Content start here -->
        <div class="hint"><?php echo lang('help_accounts_dashboard') ?></div>
        <div class="row">
           <div class="col-md-12 white-box">
                <form class="form-material" ng-submit="fetchAcademicYearData()" novalidate="">
                   <div class="col-md-6 offset-md-3" id="feeFilterAcademicYears">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang("lbl_academic_year"); ?></label>
                            <select class="form-control" name="academic_year_id" ng-model="fcModel.academic_year_id">
                                <option value="-1"><?php echo lang("lbl_select_academic_year"); ?></option>
                                <option ng-repeat="ay in academicyears" value="{{ ay.id }}">{{ ay.name }}</option>
                            </select>
                        </div>
                    </div><br>
                    <div class="col-md-4 offset-md-5" style="padding-left: 3%;">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search "></i> <?php echo lang("search"); ?></button>
                    </div>
                </form>
           </div> 
        </div>
        <?php if($role_id == '1' ){?>
        <div class="row white-box">
            <div class="col-md-12 text-center">
                <button class="btn btn-lg button btn-success" data-toggle="modal" data-target="#collectModal" ng-click="setCollectModal()" style="width: 200px;"><?php echo lang('collect') ?></button>
                <button class="btn btn-lg btn-info" data-toggle="modal" data-target="#payModal" ng-click="setPayModal()" style="width: 200px;"><?php echo lang('pay') ?></button>
            </div>
        </div>
        <?php } else if($role_id == '4' && ($accCollect =='1' || $accPay =='1')){?>
            <div class="row white-box">
            <div class="col-md-12 text-center">
            <?php if($accCollect =='1'){?>
                <button class="btn btn-lg button btn-success" data-toggle="modal" data-target="#collectModal" ng-click="setCollectModal()" style="width: 200px;"><?php echo lang('collect') ?></button>
            <?php } if($accPay =='1'){?>
                <button class="btn btn-lg btn-info" data-toggle="modal" data-target="#payModal" ng-click="setPayModal()" style="width: 200px;"><?php echo lang('pay') ?></button>
            <?php } ?>    
            </div>
        </div>
    <?php } ?>
        <div class="row white-box" style="margin-bottom: -42px;">
            <div class="col-md-3"></div>
                <div class="col-md-6">
                    <a href="javascipt:void(0)" style="height: 200px;">
                        <div class="card">
                            <div class="box text-center" style="background-color: darkcyan;">
                                <h1 class="font-light text-white">{{total_balance}}{{school_currency}}</h1>
                                <h4 class="text-white"><?php echo lang('total_balance') ?></h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="font-light text-white" style="font-size: 12px; font-weight: bold;"><?php echo lang('lbl_cash') ?> : {{total_balance_cash}}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="font-light text-white" style="font-size: 12px; font-weight: bold;"><?php echo lang('lbl_cheque') ?> : {{total_balance_cheque}}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="font-light text-white" style="font-size: 12px; font-weight: bold;"><?php echo lang('lbl_dd') ?> : {{total_balance_dd}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <div class="col-md-3"></div>
        </div> 
        <div class="row">
            <div class="col-md-6 white-box">
            <a href="javascipt:void(0)" class="col-md-6" style="height: 120px;">
                <div class="card">
                    <div class="box bg-success text-center">
                        <h1 class="font-light text-white">{{total_income}}{{school_currency}}</h1>
                        <h4 class="text-white"><?php echo lang('total_income') ?></h4>
                        <div class="row">
                            <div class="col-md-4">
                                <p class="font-light text-white" style="font-size: 12px; font-weight: bold;"><?php echo lang('lbl_cash') ?> : {{total_income_cash}}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-light text-white" style="font-size: 12px; font-weight: bold;"><?php echo lang('lbl_cheque') ?> : {{total_income_cheque}}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-light text-white" style="font-size: 12px; font-weight: bold;"><?php echo lang('lbl_dd') ?> : {{total_income_dd}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <a href="javascipt:void(0)" class="col-md-6" style="height: 120px; margin-bottom: 5%;">
                <div class="card">
                    <div class="box bg-primary text-center">
                        <h1 class="font-light text-white">{{fee_collected}}{{school_currency}}</h1>
                        <h4 class="text-white"><?php echo lang('total_fee_collected') ?></h4>
                        <div class="row">
                            <div class="col-md-4">
                                <p class="font-light text-white" style="font-size: 12px; font-weight: bold;"><?php echo lang('lbl_cash') ?> : {{total_fee_cash}}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-light text-white" style="font-size: 12px; font-weight: bold;"><?php echo lang('lbl_cheque') ?> : {{total_fee_cheque}}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-light text-white" style="font-size: 12px; font-weight: bold;"><?php echo lang('lbl_dd') ?> : {{total_fee_dd}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <a href="javascipt:void(0)" class="col-md-6" style="height: 120px;">
                <div class="card">
                    <div class="box bg-primary text-center">
                        <h1 class="font-light text-white">{{total_deductions}}{{school_currency}}</h1>
                        <h4 class="text-white"><?php echo lang('total_deductions') ?></h4>
                        <div class="row">
                            <div class="col-md-4">
                                <p class="font-light text-white" style="font-size: 12px; font-weight: bold;"><?php echo lang('lbl_cash') ?> : {{total_deduction_cash}}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-light text-white" style="font-size: 12px; font-weight: bold;"><?php echo lang('lbl_cheque') ?> : 0</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-light text-white" style="font-size: 12px; font-weight: bold;"><?php echo lang('lbl_dd') ?> : 0</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <a href="javascipt:void(0)" class="col-md-6" style="height: 120px;">
                <div class="card">
                    <div class="box bg-primary text-center">
                        <h1 class="font-light text-white">{{income_total}}{{school_currency}}</h1>
                        <h4 class="text-white"><?php echo lang('other_incomes') ?></h4>
                        <div class="row">
                            <div class="col-md-4">
                                <p class="font-light text-white" style="font-size: 12px; font-weight: bold;"><?php echo lang('lbl_cash') ?> : {{total_other_income_cash}}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-light text-white" style="font-size: 12px; font-weight: bold;"><?php echo lang('lbl_cheque') ?> : {{total_other_income_cheque}}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-light text-white" style="font-size: 12px; font-weight: bold;"><?php echo lang('lbl_dd') ?> : {{total_other_income_dd}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 white-box">
            <a href="javascipt:void(0)" class="col-md-12" style="height: 120px; margin-bottom: 5%;">
                <div class="card">
                    <div class="box bg-danger text-center">
                        <h1 class="font-light text-white">{{total_expense}}{{school_currency}}</h1>
                        <h4 class="text-white"><?php echo lang('total_expense') ?></h4>
                        <div class="row">
                            <div class="col-md-4">
                                <p class="font-light text-white" style="font-size: 12px; font-weight: bold;"><?php echo lang('lbl_cash') ?> : {{total_all_exp_cash}}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-light text-white" style="font-size: 12px; font-weight: bold;"><?php echo lang('lbl_cheque') ?> : {{total_all_exp_cheque}}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-light text-white" style="font-size: 12px; font-weight: bold;"><?php echo lang('lbl_dd') ?> : {{total_all_exp_dd}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            
            <a href="javascipt:void(0)" class="col-md-6" style="height: 120px;">
                <div class="card">
                    <div class="box bg-info text-center">
                        <h1 class="font-light text-white">{{payroll_amount}}{{school_currency}}</h1>
                        <h4 class="text-white"><?php echo lang('total_payroll_amount') ?></h4>
                        <div class="row">
                            <div class="col-md-4">
                                <p class="font-light text-white" style="font-size: 12px; font-weight: bold;"><?php echo lang('lbl_cash') ?> : {{total_payroll_cash}}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-light text-white" style="font-size: 12px; font-weight: bold;"><?php echo lang('lbl_cheque') ?> : {{total_payroll_cheque}}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-light text-white" style="font-size: 12px; font-weight: bold;"><?php echo lang('lbl_dd') ?> : {{total_payroll_dd}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <a href="javascipt:void(0)" class="col-md-6" style="height: 120px;">
                <div class="card">
                    <div class="box bg-info text-center">
                        <h1 class="font-light text-white">{{expense_total}}{{school_currency}}</h1>
                        <h4 class="text-white"><?php echo lang('other_expenses') ?></h4>
                        <div class="row">
                            <div class="col-md-4">
                                <p class="font-light text-white" style="font-size: 12px; font-weight: bold;"><?php echo lang('lbl_cash') ?> : {{total_other_expense_cash}}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-light text-white" style="font-size: 12px; font-weight: bold;"><?php echo lang('lbl_cheque') ?> : {{total_other_expense_cheque}}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-light text-white" style="font-size: 12px; font-weight: bold;"><?php echo lang('lbl_dd') ?> : {{total_other_expense_dd}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        </div>

        <div class="row white-box">
            <div class="col-md-12">
                <h3 id="acch3"><u><i class="fa fa-plus" style="color: green;"></i> <?php echo lang('incomes') ?></u>
                    <a href="<?php echo base_url();?>accounts/getIncomesInExcel/{{fcModel.academic_year_id}}" target="_blank" class="float-right"><button id="expp" class="btn btn-success"><i class="fa fa-file-excel-o"></i> <?php echo lang('export_as_excel') ?></button></a>
                </h3>
                <div style="max-height: 300px;overflow: auto;">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?php echo lang('income_type') ?></th>
                                <th><?php echo lang('title_category') ?></th>
                                <th><?php echo lang('amount') ?></th>
                                <th><?php echo lang('lbl_date') ?></th>
                                <th><?php echo lang('collected_by') ?></th>
                                <th><?php echo lang('th_action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="i in incomes">
                                <td>{{i.income_type}}</td>
                                <td>{{i.category_name}}</td>
                                <td>{{i.amount}}{{i.symbol}}</td>
                                <td>{{i.date}}</td>
                                <td>{{i.collected_by}}</td>
                                <td ng-if="i.income == 'true'">
                                    <button class="btn btn-primary btn-sm" data-dismiss="modal" data-toggle="modal" data-target="#viewCollectModal" ng-click="setCollectViewData(i)"><i class="fa fa-eye"></i></button>
                                    <button class="btn btn-info btn-sm" data-dismiss="modal" data-toggle="modal" data-target="#editCollectModal" ng-click="setCollectEditData(i)"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-danger btn-sm" data-dismiss="modal" data-toggle="modal" data-target="#deleteIncome" ng-click="setIncomeDeleteId(i.id)"><i class="fa fa-trash"></i></button>
                                </td>
                                <td ng-if="i.income == 'false'">
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#feeModal" ng-click="fetchfeesformodal(i.date_f,i.date)" ng-if="i.type == 'fee'"><?php echo lang('lbl_details') ?></button>

                                    <button class="btn btn-primary" data-toggle="modal" data-target="#deductionModal" ng-click="fetchdeductions(i.date_f,i.date)" ng-if="i.type == 'payroll'"><?php echo lang('lbl_details') ?></button>
                                </td>

                            </tr>
                        </tbody>
                    </table>

                </div>
                <br>
                <b><?php echo lang('lbl_total'); ?>: </b>{{total_income}}{{school_currency}}
            </div>
        </div>

        <div class="row white-box">
            <div class="col-md-12">

                    <h3 id="acch3"><u><i class="fa fa-list" style="color: green;"></i> <?php echo lang('crumb_fee_collection') ?> ({{fee_date}})</u>
                        
                    <a href="<?php echo base_url();?>accounts/getFeesInExcel/{{fee_date}}/{{fcModel.academic_year_id}}" target="_blank" class="float-right"><button id="expp" class="btn btn-success"><i class="fa fa-file-excel-o"></i> <?php echo lang('export_as_excel') ?></button></a>
                    <div class="col-md-4 input-group float-right">
                            <input type="text" ng-model="fee_date_search" class="form-control mydatepicker-autoclose" placeholder="<?php echo date('d/m/Y'); ?>" />
                            <div class="input-group-btn">
                                <button class="btn btn-default" ng-click="fetchfeedetails()">
                                <i class="glyphicon glyphicon-search"></i>
                            </button>
                        </div>
                    </div>
                    </h3>

                <div class="clear" style="max-height: 300px;overflow: auto;" ng-if="fees.length > 0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?php echo lang('student_name') ?></th>
                                <th><?php echo lang('imp_std_class') ?></th>
                                <th><?php echo lang('lbl_batch') ?></th>
                                <th><?php echo lang('fee_type') ?></th>
                                <th><?php echo lang('lbl_total') ?></th>
                                <th><?php echo lang('lbl_paid') ?></th>
                                <th><?php echo lang('collected_by') ?></th>
                                <th><?php echo lang('lbl_receipt_no') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="f in fees">
                                <td>{{f.name}}</td>
                                <td>{{f.class_name}}</td>
                                <td>{{f.batch_name}}</td>
                                <td>{{f.fee_type}} <a ng-if="fcModel.academic_year_id!=f.academic_year_id" href="javascript:void(0)" data-html="true" data-toggle="tooltip" data-placement="top" title="<?php echo lang('fee_collected_from') ?> {{f.academic_year_name}} <?php echo lang('lbl_academic_year') ?>"><i class="fa fa-info-circle"></i></a></td>
                                <td>{{f.feetype_amount}}{{school_currency}}</td>
                                <td>{{f.paid_amount}}{{school_currency}}</td>
                                <td>{{f.collected_by}}</td>
                                <td>{{f.receipt_no}}</td>


                            </tr>
                        </tbody>
                    </table>

                </div>
                <div ng-if="fees.length == 0">
                    <p class="text-danger"><?php echo lang('no_record_found') ?></p>
                </div>
                <br>
                <b><?php echo lang('lbl_total') ?>: </b>{{fee_total}}{{school_currency}}
            </div>
        </div>

        

        <div class="row white-box">
            <div class="col-md-12">
                <h3 id="acch3"><u><i class="fa fa-minus" style="color:red"></i> <?php echo lang('lbl_expenses') ?></u>
                    <a href="<?php echo base_url();?>accounts/getExpensesInExcel/{{fcModel.academic_year_id}}" target="_blank" class="float-right"><button id="expp" class="btn btn-success"><i class="fa fa-file-excel-o"></i> <?php echo lang('export_as_excel') ?></button></a>
                </h3>
                <div style="max-height: 300px;overflow: auto;">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?php echo lang('expense_type') ?></th>
                                <th><?php echo lang('title_category') ?></th>
                                <th><?php echo lang('amount') ?></th>
                                <th><?php echo lang('lbl_date') ?></th>
                                <th><?php echo lang('lbl_paid_by') ?></th>
                                <th><?php echo lang('th_action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="e in expenses">
                                <td>{{e.expense_type}}</td>
                                <td>{{e.category_name}}</td>
                                <td>{{e.amount}}{{e.symbol}}</td>
                                <td>{{e.date}}</td>
                                <td>{{e.paid_by}}</td>
                                <td ng-if="e.expense == 'true'">
                                    <button class="btn btn-primary btn-sm" data-dismiss="modal" data-toggle="modal" data-target="#viewPayModal" ng-click="setPayViewData(e)"><i class="fa fa-eye"></i></button>
                                <?php if($role_id == '1' || ($role_id == '4' && $expenseEdit == '1')){?>
                                    <button class="btn btn-info btn-sm" data-dismiss="modal" data-toggle="modal" data-target="#editPayModal" ng-click="setPayEditData(e)"><i class="fa fa-edit"></i></button>
                                <?php } if($role_id == '1' || ($role_id == '4' && $expenseDelete == '1')){?>
                                    <button class="btn btn-danger btn-sm" data-dismiss="modal" data-toggle="modal" data-target="#deleteExpense" ng-click="setExpenseDeleteId(e.id)"><i class="fa fa-trash"></i></button>
                                <?php } ?>
                                </td>
                                <td ng-if="e.expense == 'false'">
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#payrollModal" ng-click="fetchpayrollformodal(e.date_f,e.date)"><?php echo lang('lbl_details') ?></button>
                                </td>
                                <td ng-if="e.expense == 'fee'">
                                    <button class="btn-sm btn btn-primary" data-toggle="modal" data-target="#feeDetailModal" ng-click="getFeeDetails(e.id)"><i class="fa fa-eye"></i></button>
                                    <button class="btn-sm btn btn-danger" data-toggle="modal" data-target="#feeDeleteModal" ng-click="setFeeId(e.id)"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
                <br>
                <b><?php echo lang('lbl_total'); ?>: </b>{{total_expense}}{{school_currency}}
            </div>
        </div>

        <div class="row white-box">
            <div class="col-md-12">
                <h3 id="acch3"><u><i class="fa fa-list" style="color: green;"></i> <?php echo lang('lbl_payroll'); ?> ({{payroll_date}})</u>
                    <a href="<?php echo base_url();?>accounts/getPayrollsInExcel/{{payroll_date}}/{{fcModel.academic_year_id}}" target="_blank" class="float-right"><button id="expp" class="btn btn-success"><i class="fa fa-file-excel-o"></i> <?php echo lang('export_as_excel') ?></button></a>
                    <div class="col-md-4 input-group float-right">
                            <input type="text" ng-model="payroll_date_search" class="form-control mydatepicker-autoclose" placeholder="<?php echo date('d/m/Y'); ?>" />
                            <div class="input-group-btn">
                                <button class="btn btn-default" ng-click="fetchpayrolldetails()">
                                <i class="glyphicon glyphicon-search"></i>
                            </button>
                        </div>
                    </div>
                </h3>
                <div class="clear" style="max-height: 300px;overflow: auto;" ng-if="payrolls.length > 0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?php echo lang('employee_name') ?></th>
                                <th><?php echo lang('lbl_salary') ?></th>
                                <th><?php echo lang('amount_paid') ?></th>
                                <th><?php echo lang('total_pay') ?></th>
                                <th><?php echo lang('lbl_paid_by') ?></th>
                                <th><?php echo lang('lbl_receipt_no') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="p in payrolls">
                                <td>{{p.name}}</td>
                                <td>{{p.salary}}</td>
                                <td>{{p.amount_paid}}{{school_currency}}</td>
                                <td>{{p.total_amount}}{{school_currency}}</td>
                                <td>{{p.paid_by}}</td>
                                <td>{{p.receipt_no}}</td>


                            </tr>
                        </tbody>
                    </table>

                </div>
                <div ng-if="payrolls.length == 0">
                    <p class="text-danger"><?php echo lang('no_record_found') ?></p>
                </div>
                <br>
                <b><?php echo lang('lbl_total') ?>: </b>{{pay_total}}{{school_currency}}
            </div>
        </div>

        
        <?php if($role_id == '1' ){?>
        <div class="row white-box">
            <div class="col-md-12 text-center">
                <button class="btn btn-lg button btn-success" data-toggle="modal" data-target="#collectModal" ng-click="setCollectModal()" style="width: 200px;"><?php echo lang('collect') ?></button>
                <button class="btn btn-lg btn-info" data-toggle="modal" data-target="#payModal" ng-click="setPayModal()" style="width: 200px;"><?php echo lang('pay') ?></button>
            </div>
        </div>
        <?php } else if($role_id == '4' && ($accCollect =='1' || $accPay =='1')){?>
            <div class="row white-box">
                <div class="col-md-12 text-center">
                    <?php if($accCollect =='1'){?>
                    <button class="btn btn-lg button btn-success" data-toggle="modal" data-target="#collectModal" ng-click="setCollectModal()" style="width: 200px;"><?php echo lang('collect') ?></button>
                    <?php } if($accPay =='1'){?>
                    <button class="btn btn-lg btn-info" data-toggle="modal" data-target="#payModal" ng-click="setPayModal()" style="width: 200px;"><?php echo lang('pay') ?></button>
                <?php }?>
                </div>
            </div>
        <?php } ?>
        <?php if($role_id == '1' || ($role_id == '4' && $virtual_accounts == '1')) { ?>
        <div class="row white-box">
            <div class="col-md-12">
                <h3><u><?php echo lang('virtual_bank_accounts') ?></u></h3>
            </div>
            <div class="col-md-12" ng-if="accounts.length == 0">
                <p class="text-danger"><?php echo lang('no_virtual_account_exist') ?></p>
            </div>
            <div class="col-md-6" ng-repeat="ac in accounts">
                <div class="card" style="border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px; color:#042954;">
                    <div class="card-body">
                        <div class="col-md-12">
                            <h4 class="card-title">
                                Bank Name: {{ac.bank_name}}
                            </h4>
                            
                            <p><b>Account Name:</b> {{ac.account_name}}</p>
                            <p><b>Account No.:</b> {{ac.account_no}}</p>
                            <p><b>Current Balance:</b> {{ac.balance}}</p>
                            <p><b>Last Deposit:</b> <span ng-if="ac.last_deposit_amount == ''">-</span><span ng-if="ac.last_deposit_amount != ''">{{ac.last_deposit_amount}}{{ac.last_deposit_symbol}} ({{ac.last_deposit_date}})</span></p>
                            <p><b>Last Withdraw:</b> <span ng-if="ac.last_withdraw_amount == ''">-</span><span ng-if="ac.last_withdraw_amount != ''">{{ac.last_withdraw_amount}}{{ac.last_withdraw_symbol}} ({{ac.last_withdraw_date}})</span></p>


                            <?php if($role_id == '1'){?>
                            <div class="text-center">
                                <button class="btn btn-success" data-toggle="modal" data-target="#depositModal" ng-click="setDepositId(ac.account_id)"><i class="fa fa-plus-square"></i><?php echo lang('deposit'); ?> </button>
                                <button class="btn btn-danger" data-toggle="modal" data-target="#withdrawModal" ng-click="setWithdrawId(ac.account_id,ac.balance)"><i class="fa fa-minus-square"></i> <?php echo lang('withdraw'); ?></button>
                                <button class="btn btn-info" data-toggle="modal" data-target="#detailsModal" ng-click="setDetails(ac.account_id,ac.deposits,ac.withdraws)"><?php echo lang('view_details');?></button>
                            </div>
                        <?php } else if($role_id == '4' || ($accDeposite == '1' || $accDepositeEdit == '1' || $accWithdraw == '1')){?>
                             <div class="text-center">
                                <?php if($accDeposite == '1'){ ?>
                                <button class="btn btn-success" data-toggle="modal" data-target="#depositModal" ng-click="setDepositId(ac.account_id)"><i class="fa fa-plus-square"></i> <?php echo lang('deposit'); ?></button>
                                 <?php } if($accDepositeEdit == '1'){ ?>
                                <button class="btn btn-danger" data-toggle="modal" data-target="#withdrawModal" ng-click="setWithdrawId(ac.account_id,ac.balance)"><i class="fa fa-minus-square"></i> <?php echo lang('withdraw'); ?></button>
                                 <?php } if($accWithdraw == '1'){ ?>
                                <button class="btn btn-info" data-toggle="modal" data-target="#detailsModal" ng-click="setDetails(ac.account_id,ac.deposits,ac.withdraws)"><?php echo lang('view_details');?></button>
                                 <?php } ?>
                            </div>
                        <?php } ?>
                        </div>
                        
                    </div>
                </div>
            </div>

        </div>
        <?php } ?>
    </div>

    <!-- Modal -->
    <div id="collectModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header bg-success">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-white"><?php echo lang('collect') ?></h4>
        </div>
        <div class="modal-body">
            <form name="income_form">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo lang('income_type') ?></label>
                            <select class="form-control yasir-payroll-select2" ng-model="income.income_id" required="" style="width: 100%;" ng-change="getIncomeCategories()">
                                <option value=""><?php echo lang('select_income_type') ?></option>
                                <option value="{{i.id}}" ng-repeat="i in income_types">{{i.name}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo lang('th_cat_name') ?></label>
                            <select class="form-control yasir-payroll-select2" ng-model="income.income_category_id" required="" style="width: 100%;" ng-change="setMaximumIncome(income.income_category_id)">
                                <option value=""><?php echo lang('select_cat_name') ?></option>
                                <option value="{{ic.id}}" ng-repeat="ic in income_categories">{{ic.category_name}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo lang('lbl_date') ?></label>
                            <input type="text" class="form-control mydatepicker-autoclose" ng-model="income.date" required="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo lang('amount') ?></label>
                            <input type="number" disabled="disabled" class="form-control" value="{{income.maximum}}" ng-if="income.fixed == 'Yes'">
                            <!-- <input type="number" class="form-control" ng-model="income.amount" ng-init="income.amount='{{income.maximum}}'" required="" min=1 max="{{income.maximum}}" ng-if="income.fixed == 'Yes'"> -->
                            <input type="number" class="form-control" ng-model="income.amount" required="" min="{{income.min_price}}" max="{{income.max_price}}" ng-if="income.fixed == 'No'">
                            <span ng-if="income.fixed == 'Yes'" class="text-danger">Fixed amount can't change</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo lang('currency') ?></label>
                            <select class="form-control yasir-payroll-select2 currency-dropdown" ng-model="income.currency" required="" style="width: 100%;">
                                <option value=""><?php echo lang('select_currency') ?></option>
                                <option value="{{c.currency_id}}" ng-repeat="c in currencies">{{c.symbol}} - {{c.name}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo lang('lbl_comment') ?></label>
                            <input type="text" ng-model="income.comment" class="form-control">
                        </div>
                    </div>
                    
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?= lang("lbl_payment_mode"); ?></label><br/>
                            <span class="radio radio-primary radio-inline">
                                <input type="radio" name="mode" id="cash3" ng-model="income.mode" value="cash" checked="" />
                                <label for="cash3"> <?= lang("lbl_cash"); ?> </label>
                            </span>
                            <span class="radio radio-primary radio-inline">
                                <input type="radio" name="mode" id="cheque3" ng-model="income.mode" value="cheque" />
                                <label for="cheque3"> <?= lang("lbl_cheque"); ?> </label>
                            </span>
                            <span class="radio radio-primary radio-inline">
                                <input type="radio" name="mode" ng-model="income.mode" id="dd3" value="dd" />
                                <label for="dd3"> <?= lang("lbl_dd"); ?> </label>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <b><?php echo lang('lbl_attachments') ?></b><hr>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang('add_files') ?></label>
                            <div class="dropzone" id="my-awesome-dropzone3" dropzone="incomeDropzoneConfig"></div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('modal_btn_close') ?></button>
                <button type="button" class="btn btn-primary" ng-disabled="income_form.$invalid" id="incomeBtn"><?php echo lang('collect') ?></button>
            </div>
        </form>
    </div>

</div>
</div>

<!-- Modal -->


<!-- delete confirmation modal -->

<div id="feeDeleteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo lang('lbl_delete_confirmation') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <p><?php echo lang('delete_confirmation_message') ?></p>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang('btn_close_bank') ?></button>
                <button type="button" class="btn btn-danger waves-effect waves-light" ng-click="deleteCollectedFee()"><?php echo lang('btn_delete_bank') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- ends here -->


<!--  Alert income Modal added by sheraz ends -->
    <div id="incomeAlert" class="modal fade" role="dialog" style="padding-top: 3%;">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-white"><?php echo lang('alert') ?></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <span><?php echo lang('accounts_alert') ?></span>
                        </div>
                    </div><br><br>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" ng-click="newIncome()"><?php echo lang('collect') ?></button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Alert income Modal added by sheraz ends -->


<!--  Alert pay Modal added by sheraz ends -->
    <div id="payAlert" class="modal fade" role="dialog" style="padding-top: 3%;">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-white"><?php echo lang('alert') ?></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <span><?php echo lang('accounts_alert') ?></span>
                        </div>
                    </div><br><br>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" ng-click="newExpense()"><?php echo lang('pay') ?></button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Alert pay Modal added by sheraz ends -->


<div id="viewCollectModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-success">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-white"><?php echo lang('view_income') ?></h4>
    </div>
    <div class="modal-body">
        <form name="view_income_form">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('income_type') ?></label>
                        <input class="form-control" type="text" ng-model="viewIncome.income_type" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('th_cat_name') ?></label>
                        <input class="form-control" type="text" ng-model="viewIncome.category_name" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('lbl_date') ?></label>
                        <input type="text" class="form-control" ng-model="viewIncome.date_f" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('amount') ?></label>
                        <input type="text" class="form-control" ng-model="viewIncome.amount" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('currency') ?></label>
                        <input type="text" class="form-control" ng-model="viewIncome.full_currency" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('lbl_comment') ?></label>
                        <input type="text" class="form-control" ng-model="viewIncome.comment" readonly>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label><?= lang("lbl_payment_mode"); ?></label><br/>
                        <span class="radio radio-primary radio-inline">
                            <input type="radio" name="mode" ng-model="viewIncome.mode" value="cash" checked="" disabled>
                            <label> <?= lang("lbl_cash"); ?> </label>
                        </span>
                        <span class="radio radio-primary radio-inline">
                            <input type="radio" name="mode" ng-model="viewIncome.mode" value="cheque" disabled>
                            <label> <?= lang("lbl_cheque"); ?> </label>
                        </span>
                        <span class="radio radio-primary radio-inline">
                            <input type="radio" name="mode" ng-model="viewIncome.mode" value="dd" disabled>
                            <label> <?= lang("lbl_dd"); ?> </label>
                        </span>
                    </div>
                </div>
                <div class="col-md-12">
                    <b><?php echo lang('lbl_attachments') ?></b><hr>
                    <p ng-repeat="f in viewIncome.old_files"><a  href="uploads/study_material/{{f.name}}" download="">{{f.name}}</a></p>
                </div>
            </div>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('modal_btn_close') ?></button>
        </div>
    </form>
</div>

</div>
</div>

<!-- Modal -->
<div id="viewPayModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-success">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-white"><?php echo lang('view_expense') ?></h4>
    </div>
    <div class="modal-body">
        <form name="view_expense_form">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('expense_type') ?></label>
                        <input class="form-control" type="text" ng-model="viewExpense.expense_type" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('th_cat_name') ?></label>
                        <input class="form-control" type="text" ng-model="viewExpense.category_name" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('lbl_date') ?></label>
                        <input type="text" class="form-control" ng-model="viewExpense.date_f" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('amount') ?></label>
                        <input type="text" class="form-control" ng-model="viewExpense.amount" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('currency') ?></label>
                        <input type="text" class="form-control" ng-model="viewExpense.full_currency" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('lbl_comment') ?></label>
                        <input type="text" class="form-control" ng-model="viewExpense.comment" readonly>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label><?= lang("lbl_payment_mode"); ?></label><br/>
                        <span class="radio radio-primary radio-inline">
                            <input type="radio" name="mode" ng-model="viewExpense.mode" value="cash" checked="" disabled>
                            <label> <?= lang("lbl_cash"); ?> </label>
                        </span>
                        <span class="radio radio-primary radio-inline">
                            <input type="radio" name="mode" ng-model="viewExpense.mode" value="cheque" disabled>
                            <label> <?= lang("lbl_cheque"); ?> </label>
                        </span>
                        <span class="radio radio-primary radio-inline">
                            <input type="radio" name="mode" ng-model="viewExpense.mode" value="dd" disabled>
                            <label> <?= lang("lbl_dd"); ?> </label>
                        </span>
                    </div>
                </div>
                <div class="col-md-12">
                    <b><?php echo lang('lbl_attachments') ?></b><hr>
                    <p ng-repeat="f in viewExpense.old_files"><a  href="uploads/study_material/{{f.name}}" download="">{{f.name}}</a></p>
                </div>
            </div>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('modal_btn_close') ?></button>
        </div>
    </form>
</div>

</div>
</div>

<!-- Modal -->
<div id="editCollectModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-success">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-white"><?php echo lang('edit_collect') ?></h4>
    </div>
    <div class="modal-body">
        <form name="edit_income_form">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('income_type') ?></label>
                        <select class="form-control yasir-payroll-select2" ng-model="editIncome.income_id" required="" style="width: 100%;" ng-change="getIncomeCategoriesforEdit(editIncome)">
                            <option value=""><?php echo lang('select_income_type') ?></option>
                            <option value="{{i.id}}" ng-repeat="i in income_types">{{i.name}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('th_cat_name') ?></label>
                        <select class="form-control yasir-payroll-select2 secondSelect" ng-model="editIncome.income_category_id" required="" style="width: 100%;" ng-change="setMaximumIncomeEdit(editIncome.income_category_id)">
                            <option value=""><?php echo lang('select_cat_name') ?></option>
                            <option value="{{ic.id}}" ng-repeat="ic in editIncome.income_categories">{{ic.category_name}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('lbl_date') ?></label>
                        <input type="text" class="form-control mydatepicker-autoclose" ng-model="editIncome.date_f" required="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('amount') ?></label>
                        <input type="number" disabled="disabled" class="form-control" value="{{editIncome.maximum}}" ng-if="editIncome.fixed == 'Yes'">
                        <!-- <input type="number" class="form-control" ng-model="editIncome.amount" required="" min=1 max="{{editIncome.maximum}}" ng-if="editIncome.fixed == 'Yes'"> -->
                        <input type="number" class="form-control" ng-model="editIncome.amount" required="" min={{editIncome.min_price}} max="{{editIncome.max_price}}" ng-if="editIncome.fixed == 'No'">
                        <span ng-if="editIncome.fixed == 'Yes'" class="text-danger">Fixed amount can't change</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('currency') ?></label>
                        <select class="form-control yasir-payroll-select2 currency-dropdown" ng-model="editIncome.currency" required="" style="width: 100%;">
                            <option value=""><?php echo lang('select_currency') ?></option>
                            <option value="{{c.currency_id}}" ng-repeat="c in currencies">{{c.symbol}} - {{c.name}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('lbl_comment') ?></label>
                        <input type="text" ng-model="editIncome.comment" class="form-control">
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label><?= lang("lbl_payment_mode"); ?></label><br/>
                        <span class="radio radio-primary radio-inline">
                            <input type="radio" name="mode" id="cash7" ng-model="editIncome.mode" value="cash" checked="" />
                            <label for="cash7"> <?= lang("lbl_cash"); ?> </label>
                        </span>
                        <span class="radio radio-primary radio-inline">
                            <input type="radio" name="mode" id="cheque7" ng-model="editIncome.mode" value="cheque" />
                            <label for="cheque7"> <?= lang("lbl_cheque"); ?> </label>
                        </span>
                        <span class="radio radio-primary radio-inline">
                            <input type="radio" name="mode" ng-model="editIncome.mode" id="dd7" value="dd" />
                            <label for="dd7"> <?= lang("lbl_dd"); ?> </label>
                        </span>
                    </div>
                </div>
                <div class="col-md-12">
                    <b><?php echo lang('lbl_attachments') ?></b><hr>
                    <p ng-repeat="f in editIncome.old_files"><a  href="uploads/study_material/{{f.name}}" download="">{{f.name}}</a> <button class="btn btn-link text-danger" ng-click="removeIncomeFile(f.name)" type="button">Remove</button></p>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label"><?php echo lang('add_files') ?></label>
                        <div class="dropzone" id="my-awesome-dropzone7" dropzone="editIncomeDropzoneConfig"></div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('modal_btn_close') ?></button>
            <button type="button" class="btn btn-primary" ng-disabled="edit_income_form.$invalid" id="editIncomeBtn"><?php echo lang('modal_btn_update') ?></button>
        </div>
    </form>
</div>

</div>
</div>

<!-- Modal -->
<div id="payModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-white"><?php echo lang('pay') ?></h4>
    </div>
    <div class="modal-body">
        <form name="expense_form">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('expense_type') ?></label>
                        <select class="form-control yasir-payroll-select2" ng-model="expense.expense_id" required="" style="width: 100%;" ng-change="getExpenseCategories()">
                            <option value=""><?php echo lang('select_expense_type') ?></option>
                            <option value="{{e.id}}" ng-repeat="e in expense_types">{{e.name}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('th_cat_name') ?></label>
                        <select class="form-control yasir-payroll-select2" ng-model="expense.expense_category_id" required="" style="width: 100%;" ng-change="setMaximumExpense(expense.expense_category_id)">
                            <option value=""><?php echo lang('select_cat_name') ?></option>
                            <option value="{{ec.id}}" ng-repeat="ec in expense_categories">{{ec.category_name}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('lbl_date') ?></label>
                        <input type="text" class="form-control mydatepicker-autoclose" ng-model="expense.date" required="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('amount') ?></label>
                        <input type="number" class="form-control" ng-model="expense.amount" required="" min=1 max="{{expense.maximum}}" ng-if="expense.fixed == 'Yes'">
                        <input type="number" class="form-control" ng-model="expense.amount" required="" min=1 ng-if="expense.fixed == 'No'">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('currency') ?></label>
                        <select class="form-control yasir-payroll-select2 currency-dropdown" ng-model="expense.currency" required="" style="width: 100%;">
                            <option value=""><?php echo lang('select_currency') ?></option>
                            <option value="{{c.currency_id}}" ng-repeat="c in currencies">{{c.symbol}} - {{c.name}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('lbl_comment') ?></label>
                        <input type="text" ng-model="expense.comment" class="form-control">
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label><?= lang("lbl_payment_mode"); ?></label><br/>
                        <span class="radio radio-primary radio-inline">
                            <input type="radio" name="mode" id="cash6" ng-model="expense.mode" value="cash" checked="" />
                            <label for="cash6"> <?= lang("lbl_cash"); ?> </label>
                        </span>
                        <span class="radio radio-primary radio-inline">
                            <input type="radio" name="mode" id="cheque6" ng-model="expense.mode" value="cheque" />
                            <label for="cheque6"> <?= lang("lbl_cheque"); ?> </label>
                        </span>
                        <span class="radio radio-primary radio-inline">
                            <input type="radio" name="mode" ng-model="expense.mode" id="dd6" value="dd" />
                            <label for="dd6"> <?= lang("lbl_dd"); ?> </label>
                        </span>
                    </div>
                </div>
                <div class="col-md-12">
                    <b><?php echo lang('lbl_attachments') ?></b><hr>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label"><?php echo lang('add_files') ?></label>
                        <div class="dropzone" id="my-awesome-dropzone4" dropzone="expenseDropzoneConfig"></div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('modal_btn_close') ?></button>
            <button type="button" class="btn btn-primary" ng-disabled="expense_form.$invalid" id="expenseBtn"><?php echo lang('pay') ?></button>
        </div>
    </form>
</div>

</div>
</div>

<!-- Modal -->
<div id="editPayModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-white"><?php echo lang('edit_pay') ?></h4>
    </div>
    <div class="modal-body">
        <form name="edit_expense_form">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('expense_type') ?></label>
                        <select class="form-control yasir-payroll-select2" ng-model="editExpense.expense_id" required="" style="width: 100%;" ng-change="getExpenseCategoriesforEdit(editExpense)">
                            <option value=""><?php echo lang('select_expense_type') ?></option>
                            <option value="{{e.id}}" ng-repeat="e in expense_types">{{e.name}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('th_cat_name') ?></label>
                        <select class="form-control yasir-payroll-select2 secondSelect" ng-model="editExpense.expense_category_id" required="" style="width: 100%;" ng-change="setMaximumExpenseEdit(editExpense.expense_category_id)">
                            <option value=""><?php echo lang('select_cat_name') ?></option>
                            <option value="{{ec.id}}" ng-repeat="ec in editExpense.expense_categories">{{ec.category_name}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('lbl_date') ?></label>
                        <input type="text" class="form-control mydatepicker-autoclose" ng-model="editExpense.date_f" required="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('amount') ?></label>
                        <input type="number" class="form-control" ng-model="editExpense.amount" required="" min=1 max="{{editExpense.maximum}}" ng-if="editExpense.fixed == 'Yes'">
                        <input type="number" class="form-control" ng-model="editExpense.amount" required="" min=1 ng-if="editExpense.fixed == 'No'">

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('currency') ?></label>
                        <select class="form-control yasir-payroll-select2 currency-dropdown" ng-model="editExpense.currency" required="" style="width: 100%;">
                            <option value=""><?php echo lang('select_currency') ?></option>
                            <option value="{{c.currency_id}}" ng-repeat="c in currencies">{{c.symbol}} - {{c.name}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('lbl_comment') ?></label>
                        <input type="text" ng-model="editExpense.comment" class="form-control">
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label><?= lang("lbl_payment_mode"); ?></label><br/>
                        <span class="radio radio-primary radio-inline">
                            <input type="radio" name="mode" id="cash8" ng-model="editExpense.mode" value="cash" checked="" />
                            <label for="cash8"> <?= lang("lbl_cash"); ?> </label>
                        </span>
                        <span class="radio radio-primary radio-inline">
                            <input type="radio" name="mode" id="cheque8" ng-model="editExpense.mode" value="cheque" />
                            <label for="cheque8"> <?= lang("lbl_cheque"); ?> </label>
                        </span>
                        <span class="radio radio-primary radio-inline">
                            <input type="radio" name="mode" ng-model="editExpense.mode" id="dd8" value="dd" />
                            <label for="dd8"> <?= lang("lbl_dd"); ?> </label>
                        </span>
                    </div>
                </div>
                <div class="col-md-12">
                    <b><?php echo lang('lbl_attachments') ?></b><hr>
                    <p ng-repeat="f in editExpense.old_files"><a  href="uploads/study_material/{{f.name}}" download="">{{f.name}}</a> <button class="btn btn-link text-danger" ng-click="removeExpenseFile(f.name)" type="button">Remove</button></p>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label"><?php echo lang('add_files') ?></label>
                        <div class="dropzone" id="my-awesome-dropzone8" dropzone="expenseEditDropzoneConfig"></div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('modal_btn_close') ?></button>
            <button type="button" class="btn btn-primary" ng-disabled="edit_expense_form.$invalid" id="editExpenseBtn"><?php echo lang('pay') ?></button>
        </div>
    </form>
</div>

</div>
</div>

<!-- Modal -->
<div id="depositModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-success">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-white"><i class="fa fa-plus-square"></i> <?php echo lang('deposit') ?></h4>
    </div>
    <div class="modal-body">
        <form name="deposit_form">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('deposit_amount') ?></label>
                        <input type="number" class="form-control" ng-model="deposit.amount" required="" min=1>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('deposit_by') ?></label>
                        <select class="form-control yasir-payroll-select2" ng-model="deposit.deposit_by" required="" style="width: 100%;">
                            <option value=""><?php echo lang('select_deposit_by') ?></option>
                            <option value="{{e.id}}" ng-repeat="e in employees">{{e.name}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('lbl_date') ?></label>
                        <input type="text" class="form-control mydatepicker-autoclose" ng-model="deposit.date" required="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('lbl_comment') ?></label>
                        <input type="text" ng-model="deposit.comment" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('collected_by') ?></label>
                        <input type="text" ng-model="deposit.collected_by" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('deposit_currency') ?></label>
                        <select class="form-control yasir-payroll-select2 currency-dropdown" ng-model="deposit.currency" required="" style="width: 100%;">
                            <option value=""><?php echo lang('select_currency') ?></option>
                            <option value="{{c.currency_id}}" ng-repeat="c in currencies">{{c.symbol}} - {{c.name}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?= lang("lbl_payment_mode"); ?></label><br/>
                        <span class="radio radio-primary radio-inline">
                            <input type="radio" name="mode" id="cash" ng-model="deposit.mode" value="cash" checked="" />
                            <label for="cash"> <?= lang("lbl_cash"); ?> </label>
                        </span>
                        <span class="radio radio-primary radio-inline">
                            <input type="radio" name="mode" id="cheque" ng-model="deposit.mode" value="cheque" />
                            <label for="cheque"> <?= lang("lbl_cheque"); ?> </label>
                        </span>
                        <span class="radio radio-primary radio-inline">
                            <input type="radio" name="mode" ng-model="deposit.mode" id="dd" value="dd" />
                            <label for="dd"> <?= lang("lbl_dd"); ?> </label>
                        </span>
                    </div>
                </div>
                <div class="col-md-12">
                    <b><?php echo lang('lbl_attachments') ?></b><hr>

                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label"><?php echo lang('add_files') ?></label>
                        <div class="dropzone" id="my-awesome-dropzone" dropzone="depositDropzoneConfig"></div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('modal_btn_close') ?></button>
            <button type="button" class="btn btn-primary" ng-disabled="deposit_form.$invalid" id="depositBtn"><?php echo lang('deposit') ?></button>
        </div>
    </form>
</div>

</div>
</div>

<!-- Modal -->
<div id="editDepositModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-success">
        <button type="button" class="close" data-dismiss="modal" data-toggle="modal" data-target="#detailsModal">&times;</button>
        <h4 class="modal-title text-white"><i class="fa fa-plus-square"></i> <?php echo lang('deposit_edit') ?></h4>
    </div>
    <div class="modal-body">
        <form name="edit_deposit_form">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('deposit_amount') ?></label>
                        <input type="number" class="form-control" ng-model="depositEdit.amount" required="" min=1>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('deposit_by') ?></label>
                        <select class="form-control yasir-payroll-select2" ng-model="depositEdit.deposit_by" required="" style="width: 100%;">
                            <option value=""><?php echo lang('select_deposit_by') ?></option>
                            <option value="{{e.id}}" ng-repeat="e in employees">{{e.name}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('lbl_date') ?></label>
                        <input type="text" class="form-control mydatepicker-autoclose" ng-model="depositEdit.date_f" required="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('lbl_comment') ?></label>
                        <input type="text" ng-model="depositEdit.comment" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('collected_by') ?></label>
                        <input type="text" ng-model="depositEdit.collected_by" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('deposit_currency') ?></label>
                        <select class="form-control yasir-payroll-select2 currency-dropdown" ng-model="depositEdit.currency" required="" style="width: 100%;">
                            <option value=""><?php echo lang('select_currency') ?></option>
                            <option value="{{c.currency_id}}" ng-repeat="c in currencies">{{c.symbol}} - {{c.name}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?= lang("lbl_payment_mode"); ?></label><br/>
                        <span class="radio radio-primary radio-inline">
                            <input type="radio" name="mode" id="cash4" ng-model="depositEdit.mode" value="cash" checked="" />
                            <label for="cash4"> <?= lang("lbl_cash"); ?> </label>
                        </span>
                        <span class="radio radio-primary radio-inline">
                            <input type="radio" name="mode" id="cheque4" ng-model="depositEdit.mode" value="cheque" />
                            <label for="cheque4"> <?= lang("lbl_cheque"); ?> </label>
                        </span>
                        <span class="radio radio-primary radio-inline">
                            <input type="radio" name="mode" ng-model="depositEdit.mode" id="dd4" value="dd" />
                            <label for="dd4"> <?= lang("lbl_dd"); ?> </label>
                        </span>
                    </div>
                </div>
                <div class="col-md-12">
                    <b><?php echo lang('lbl_attachments') ?></b><hr>
                    <p ng-repeat="f in depositEdit.old_files"><a  href="uploads/study_material/{{f.name}}" download="">{{f.name}}</a> <button class="btn btn-link text-danger" ng-click="removeDepositFile(f.name)" type="button">Remove</button></p>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label"><?php echo lang('add_files') ?></label>
                        <div class="dropzone" id="my-awesome-dropzone5" dropzone="editDepositDropzoneConfig"></div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#detailsModal"><?php echo lang('modal_btn_close') ?></button>
            <button type="button" class="btn btn-primary" ng-disabled="edit_deposit_form.$invalid" id="editDepositBtn"><?php echo lang('modal_btn_update') ?></button>
        </div>
    </form>
</div>

</div>
</div>

<!-- Modal -->
<div id="feeModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-white"><?php echo lang('crumb_fee_collection') ?> ({{fee_date_modal}})</h4>
    </div>
    <div class="modal-body">

        <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?php echo lang('student_name') ?></th>
                                <th><?php echo lang('lbl_par_Class') ?></th>
                                <th><?php echo lang('lbl_Section_par') ?></th>
                                <th><?php echo lang('fee_type') ?></th>
                                <th><?php echo lang('lbl_total') ?></th>
                                <th><?php echo lang('lbl_paid') ?></th>
                                <th><?php echo lang('collected_by') ?></th>
                                <th><?php echo lang('lbl_receipt_no') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="f in fees_modal">
                                <td>{{f.name}}</td>
                                <td>{{f.class_name}}</td>
                                <td>{{f.batch_name}}</td>
                                <td>{{f.fee_type}}</td>
                                <td>{{f.feetype_amount}}{{school_currency}}</td>
                                <td>{{f.paid_amount}}{{school_currency}}</td>
                                <td>{{f.collected_by}}</td>
                                <td>{{f.receipt_no}}</td>


                            </tr>
                        </tbody>
                    </table>
                    <br>
                <b><?php echo lang('lbl_total'); ?>: </b>{{fee_total_modal}}{{school_currency}}

    </div>
    <div class="modal-footer">
        <a href="<?php echo base_url();?>accounts/getFeesInExcel/{{fee_date_f}}/{{fcModel.academic_year_id}}" target="_blank" class="float-right"><button class="btn btn-success"><i class="fa fa-file-excel-o"></i> <?php echo lang('export_as_excel') ?></button></a>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('modal_btn_close') ?></button>
    </div>
</div>

</div>
</div>

<!-- Modal -->

<!-- in expense fee modal -->

<div id="feeDetailModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-white"><?php echo lang('crumb_fee_collection') ?></h4>
    </div>
    <div class="modal-body">
        <table class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th><?php echo lang('student_name') ?></th>
                                <th><?php echo lang('lbl_par_Class') ?></th>
                                <th><?php echo lang('lbl_Section_par') ?></th>
                                <th><?php echo lang('fee_type') ?></th>
                                <th><?php echo lang('lbl_total') ?></th>
                                <th><?php echo lang('lbl_paid') ?></th>
                                <th><?php echo lang('lbl_paid_by') ?></th>
                                <th><?php echo lang('lbl_receipt_no') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="feee in nfModel">
                                <td>{{feee.name}}</td>
                                <td>{{feee.class_name}}</td>
                                <td>{{feee.batch_name}}</td>
                                <td>{{feee.fee_type}}</td>
                                <td>{{feee.feetype_amount}}{{school_currency}}</td>
                                <td>{{feee.paid_amount}}{{school_currency}}</td>
                                <td>{{feee.collected_by}}</td>
                                <td>{{feee.receipt_no}}</td>


                            </tr>
                        </tbody>
                    </table>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('modal_btn_close') ?></button>
    </div>
</div>

</div>
</div>
    

<!-- ends here -->


<div id="payrollModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-white"><?php echo lang('payroll_logs') ?> ({{payroll_date_modal}})</h4>
    </div>
    <div class="modal-body">

        <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?php echo lang('employee_name') ?></th>
                                <th><?php echo lang('lbl_salary') ?></th>
                                <th><?php echo lang('amount_paid') ?></th>
                                <th><?php echo lang('total_pay') ?></th>
                                <th><?php echo lang('lbl_paid_by') ?></th>
                                <th><?php echo lang('lbl_receipt_no') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="p in payrolls_modal">
                                <td>{{p.name}}</td>
                                <td>{{p.salary}}</td>
                                <td>{{p.amount_paid}}{{school_currency}}</td>
                                <td>{{p.total_amount}}{{school_currency}}</td>
                                <td>{{p.paid_by}}</td>
                                <td>{{p.receipt_no}}</td>


                            </tr>
                        </tbody>
                    </table>
                    <br>
                <b><?php echo lang('lbl_total') ?>: </b>{{payroll_total_modal}}{{school_currency}}

    </div>
    <div class="modal-footer">
        <a href="<?php echo base_url();?>accounts/getPayrollsInExcel/{{payroll_date_f}}/{{fcModel.academic_year_id}}" target="_blank" class="float-right"><button class="btn btn-success"><i class="fa fa-file-excel-o"></i> <?php echo lang('export_as_excel') ?></button></a>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('modal_btn_close') ?></button>
    </div>
</div>

</div>
</div>


<!-- Modal -->
<div id="deductionModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-white"><?php echo lang('deduction_logs') ?> ({{deduction_date_modal}})</h4>
    </div>
    <div class="modal-body">

        <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?php echo lang('employee_name') ?></th>
                                <th><?php echo lang('lbl_salary') ?></th>
                                <th><?php echo lang('amount_paid') ?></th>
                                <th><?php echo lang('total_pay') ?></th>
                                <th><?php echo lang('deductions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="d in deductions">
                                <td>{{d.name}}</td>
                                <td>{{d.salary}}</td>
                                <td>{{d.amount_paid}}{{school_currency}}</td>
                                <td>{{d.total_amount}}{{school_currency}}</td>
                                <td>{{d.deductions}}</td>


                            </tr>
                        </tbody>
                    </table>
                    <br>
                <b>Total: </b>{{total_deductions}}{{school_currency}}

    </div>
    <div class="modal-footer">
        <a href="<?php echo base_url();?>accounts/getDeductionsInExcel/{{deduction_date}}" target="_blank" class="float-right"><button class="btn btn-success"><i class="fa fa-file-excel-o"></i> <?php echo lang('export_as_excel') ?></button></a>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('modal_btn_close') ?></button>
    </div>
</div>

</div>
</div>
<!-- Modal -->
<div id="detailsModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-white"><?php echo lang('account_transactions') ?></h4>
    </div>
    <div class="modal-body">

        <h3><?php echo lang('deposits') ?></h3>
        <div style="height: 200px;overflow: auto;">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><?php echo lang('deposit_by') ?></th>
                        <th><?php echo lang('amount') ?></th>
                        <th><?php echo lang('lbl_date') ?></th>
                        <th><?php echo lang('collected_by') ?></th>
                        <th><?php echo lang('actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="d in deposits">
                        <td>{{d.name}}</td>
                        <td>{{d.amount}}{{d.symbol}}</td>
                        <td>{{d.date_f}}</td>
                        <td>{{d.collected_by}}</td>
                        <td>
                            <button class="btn btn-info btn-sm" data-dismiss="modal" data-toggle="modal" data-target="#editDepositModal" ng-click="setDepositEditData(d)"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-danger btn-sm" data-dismiss="modal" data-toggle="modal" data-target="#deleteDeposit" ng-click="setDepositDeleteId(d.id)"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <h3><?php echo lang('withdraws') ?></h3>
        <div style="height: 200px;overflow: auto;">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><?php echo lang('withdraw_by') ?></th>
                        <th><?php echo lang('amount') ?></th>
                        <th><?php echo lang('lbl_date') ?></th>
                        <th><?php echo lang('lbl_paid_by') ?></th>
                        <th><?php echo lang('th_action') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="w in withdraws">
                        <td>{{w.name}}</td>
                        <td>{{w.amount}}{{w.symbol}}</td>
                        <td>{{w.date_f}}</td>
                        <td>{{w.paid_by}}</td>
                        <td>
                            <button class="btn btn-info btn-sm" data-dismiss="modal" data-toggle="modal" data-target="#editWithdrawModal" ng-click="setWithdrawEditData(w)"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-danger btn-sm" data-dismiss="modal" data-toggle="modal" data-target="#deleteWithdraw" ng-click="setWithdrawDeleteId(w.id)"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('modal_btn_close') ?></button>
    </div>
</div>

</div>
</div>

<!-- Modal -->
<div id="withdrawModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-white"><i class="fa fa-minus-square"></i> <?php echo lang('withdraw') ?></h4>
    </div>
    <div class="modal-body">
        <form name="withdraw_form">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('withdraw_amount') ?></label>
                        <input type="number" class="form-control" ng-model="withdraw.amount" required="" min=1 max="{{withdraw.balance}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('withdraw_by') ?></label>
                        <select class="form-control yasir-payroll-select2" ng-model="withdraw.withdraw_by" required="" style="width: 100%;">
                            <option value=""><?php echo lang('select_withdraw_by') ?></option>
                            <option value="{{e.id}}" ng-repeat="e in employees">{{e.name}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('lbl_date') ?></label>
                        <input type="text" class="form-control mydatepicker-autoclose" ng-model="withdraw.date" required="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('lbl_comment') ?></label>
                        <input type="text" class="form-control" ng-model="withdraw.comment">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('lbl_paid_by') ?></label>
                        <input type="text" class="form-control" ng-model="withdraw.paid_by">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('withdraw_currency') ?></label>
                        <select class="form-control yasir-payroll-select2 currency-dropdown" ng-model="withdraw.currency" required="" style="width: 100%;">
                            <option value=""><?php echo lang('select_currency') ?></option>
                            <option value="{{c.currency_id}}" ng-repeat="c in currencies">{{c.symbol}} - {{c.name}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?= lang("lbl_payment_mode"); ?></label><br/>
                        <span class="radio radio-primary radio-inline">
                            <input type="radio" name="mode" id="cash2" ng-model="withdraw.mode" value="cash" checked="" />
                            <label for="cash2"> <?= lang("lbl_cash"); ?> </label>
                        </span>
                        <span class="radio radio-primary radio-inline">
                            <input type="radio" name="mode" id="cheque2" ng-model="withdraw.mode" value="cheque" />
                            <label for="cheque2"> <?= lang("lbl_cheque"); ?> </label>
                        </span>
                        <span class="radio radio-primary radio-inline">
                            <input type="radio" name="mode" ng-model="withdraw.mode" id="dd2" value="dd" />
                            <label for="dd2"> <?= lang("lbl_dd"); ?> </label>
                        </span>
                    </div>
                </div>
                <div class="col-md-12">
                    <b><?php echo lang('lbl_attachments') ?></b><hr>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label"><?php echo lang('add_files') ?></label>
                        <div class="dropzone" id="my-awesome-dropzone2" dropzone="withdrawDropzoneConfig"></div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('modal_btn_close') ?></button>
            <button type="button" class="btn btn-primary" ng-disabled="withdraw_form.$invalid" id="withdrawBtn"><?php echo lang('withdraw') ?></button>
        </div>
    </form>
</div>

</div>
</div>

<!-- Modal -->
<div id="editWithdrawModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <button type="button" class="close" data-dismiss="modal" data-toggle="modal" data-target="#detailsModal">&times;</button>
        <h4 class="modal-title text-white"><i class="fa fa-minus-square"></i> <?php echo lang('edit_withdraw') ?></h4>
    </div>
    <div class="modal-body">
        <form name="edit_withdraw_form">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('edit_withdraw') ?></label>
                        <input type="number" class="form-control" ng-model="withdrawEdit.amount" required="" min=1 max="{{withdrawEdit.balance}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('withdraw_by') ?></label>
                        <select class="form-control yasir-payroll-select2" ng-model="withdrawEdit.withdraw_by" required="" style="width: 100%;">
                            <option value=""><?php echo lang('select_withdraw_by') ?></option>
                            <option value="{{e.id}}" ng-repeat="e in employees">{{e.name}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('lbl_date') ?></label>
                        <input type="text" class="form-control mydatepicker-autoclose" ng-model="withdrawEdit.date_f" required="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('lbl_comment') ?></label>
                        <input type="text" class="form-control" ng-model="withdrawEdit.comment">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('lbl_paid_by') ?></label>
                        <input type="text" class="form-control" ng-model="withdrawEdit.paid_by">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('withdraw_currency') ?></label>
                        <select class="form-control yasir-payroll-select2 currency-dropdown" ng-model="withdrawEdit.currency" required="" style="width: 100%;">
                            <option value=""><?php echo lang('select_currency') ?></option>
                            <option value="{{c.currency_id}}" ng-repeat="c in currencies">{{c.symbol}} - {{c.name}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?= lang("lbl_payment_mode"); ?></label><br/>
                        <span class="radio radio-primary radio-inline">
                            <input type="radio" name="mode" id="cash5" ng-model="withdrawEdit.mode" value="cash" checked="" />
                            <label for="cash5"> <?= lang("lbl_cash"); ?> </label>
                        </span>
                        <span class="radio radio-primary radio-inline">
                            <input type="radio" name="mode" id="cheque5" ng-model="withdrawEdit.mode" value="cheque" />
                            <label for="cheque5"> <?= lang("lbl_cheque"); ?> </label>
                        </span>
                        <span class="radio radio-primary radio-inline">
                            <input type="radio" name="mode" ng-model="withdrawEdit.mode" id="dd5" value="dd" />
                            <label for="dd5"> <?= lang("lbl_dd"); ?> </label>
                        </span>
                    </div>
                </div>
                <div class="col-md-12">
                    <b><?php echo lang('lbl_attachments') ?></b><hr>
                    <p ng-repeat="f in withdrawEdit.old_files"><a  href="uploads/study_material/{{f.name}}" download="">{{f.name}}</a> <button class="btn btn-link text-danger" ng-click="removeWithdrawFile(f.name)" type="button">Remove</button></p>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label"><?php echo lang('add_files') ?></label>
                        <div class="dropzone" id="my-awesome-dropzone6" dropzone="withdrawEditDropzoneConfig"></div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#detailsModal"><?php echo lang('modal_btn_close') ?></button>
            <button type="button" class="btn btn-primary" ng-disabled="edit_withdraw_form.$invalid" id="editWithdrawBtn"><?php echo lang('withdraw') ?></button>
        </div>
    </form>
</div>

</div>
</div>

<div id="deleteIncome" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo lang('lbl_delete_confirmation') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <p><?php echo lang('delete_confirmation_message') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang('modal_btn_close') ?></button>
                <button type="button" class="btn btn-danger waves-effect waves-light" ng-click="deleteIncome()"><?php echo lang('btn_delete_bank') ?></button>
            </div>
        </div>
    </div>
</div>

<div id="deleteExpense" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo lang('lbl_delete_confirmation') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <p><?php echo lang('delete_confirmation_message') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang('') ?><?php echo lang('modal_btn_close') ?></button>
                <button type="button" class="btn btn-danger waves-effect waves-light" ng-click="deleteExpense()"><?php echo lang('btn_delete_bank') ?></button>
            </div>
        </div>
    </div>
</div>

<div id="deleteDeposit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo lang('lbl_delete_confirmation') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" data-toggle="modal" data-target="#detailsModal">×</button>
            </div>
            <div class="modal-body">
                <p><?php echo lang('delete_confirmation_message') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" data-toggle="modal" data-target="#detailsModal"><?php echo lang('modal_btn_close') ?></button>
                <button type="button" class="btn btn-danger waves-effect waves-light" ng-click="deleteDeposit()"><?php echo lang('btn_delete_bank') ?></button>
            </div>
        </div>
    </div>
</div>

<div id="deleteWithdraw" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo lang('lbl_delete_confirmation') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" data-toggle="modal" data-target="#detailsModal">×</button>
            </div>
            <div class="modal-body">
                <p><?php echo lang('delete_confirmation_message') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" data-toggle="modal" data-target="#detailsModal"><?php echo lang('modal_btn_close') ?></button>
                <button type="button" class="btn btn-danger waves-effect waves-light" ng-click="deleteWithdraw()"><?php echo lang('btn_delete_bank') ?></button>
            </div>
        </div>
    </div>
</div>


<?php include(APPPATH . "views/inc/footer.php"); ?>