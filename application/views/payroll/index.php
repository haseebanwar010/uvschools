<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<!-- Page Content -->
<div id="page-wrapper" ng-controller="payrollController">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('lbl_payroll') ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="#"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li class="active"><?php echo lang('lbl_payroll') ?></li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <?php
        if ($this->session->userdata("userdata")["role_id"] == EMPLOYEE_ROLE_ID) {
            $ci = & get_instance();
            $arr = $ci->session->userdata("userdata")['persissions'];
            $array = json_decode($arr);
            if (isset($array)) {
                $pay_salary = 0;
                $delete_salary = 0;
                foreach ($array as $key => $value) {
                    if (in_array('payroll-pay', array($value->permission)) && $value->val == 'true') {
                        $pay_salary = 1;
                    }
                    if (in_array('payroll-delete', array($value->permission)) && $value->val == 'true') {
                        $delete_salary = 1;
                    }
                }
            }
        }
        ?>
        <div class="hint"><?php echo lang('lbl_help_payroll'); ?></div>
        <!-- Page Content start here -->

        <!-- start remarks modal -->
        <div id="payrollRemarksModal" class="modal animated slideInDown" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&Cross;</button>
                            <?php echo lang("lbl_comments"); ?>
                        </div>
                        <div class="panel-body"> 
                            <h4 class='card-title text-primary'><i class="fa fa-comment-o"></i> <?php echo lang("lbl_remarks"); ?></h4>
                            <div id="payrollRemarks"></div>
                            <h4 class='card-title text-primary'><i class="fa fa-comment-o"></i> <?php echo lang("lbl_dedection_remarks"); ?></h4>
                            <div id="payrollDeducationRemarks"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end remarks modal ---->

        <!-- Start update basic salary modal -->
        <div id="updateBasicSalaryModal" class="modal animated slideInDown" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content" id="basicsalary-model-contents">
                    <div class="panel panel-primary">
                        <form name="updateSalaryForm" ng-submit="updateSalary(updateSalaryForm.$valid)" class="form-material" novalidate>
                            <div class="panel-heading">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&Cross;</button>
                                <?php echo lang("lbl_basic_salary_form"); ?>
                            </div>
                            <div class="panel-body"> 
                                <div class="form-group custom_disable" id="yasirPayrollBasicSalary">
                                    <label><?php echo lang("lbl_employee_basic_salary"); ?></label>
                                    <input required="" type="number" ng-model="updateBasicSalary" class="form-control" />
                                </div>
                                <div class="form-group custom_disable" id="yasirPayrollGroups">
                                    <label class="control-label"><?php echo lang("lbl_payroll_groups"); ?></label>
                                    <select class="form-control" required="" ng-model="payrollGroupID" ng-init="initSalaryGroups();">
                                        <option value="">---<?php echo lang("lbl_select_a_payroll_group"); ?>---</option>
                                        <option ng-repeat="grp in payrollGroups" value="{{grp.id}}">{{grp.name}}</option>
                                    </select>
                                </div>                        
                            </div>
                            <div class="panel-footer text-right">
                                <button type="submit" class="btn btn-primary"><?php echo lang("btn_profile_update"); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End update basic salary modal ---->

        <!-- start edit fee collection modal -->
        <div class="modal fade" id="payrollEditModel" tabindex="-1" role="dialog" aria-labelledby="payrollEditModel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" id="payroll-model-contents">
                    <form name="payrollEditModelForm" ng-submit="collectPayrollUpdate(payrollEditModelForm.$valid)" novalidate="" class="form-material">
                        <div class="form-body">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&Cross;</button>
                                    <?= lang("lbl_payroll_update_form"); ?>
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-12 p-0 m-0">
                                        <div class="col-md-6 p-0 m-0">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_date"); ?></label>
                                                    <input type="text" ng-model="uModel.due_date" ng-value="editModel.due_date" class="form-control mydatepicker-autoclose">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_other_deductions"); ?></label>
                                                    <input type="number" class="form-control" ng-model="uModel.other_deducation" />
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_additional_payment"); ?></label>
                                                    <input type="number" class="form-control" ng-model="uModel.additional_payment" ng-init="uModel.additional_payment=0" />
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_discounted_amount"); ?></label>
                                                    <div class="form-control p-t-10" disabled="" step="0.01">{{editModel.payable_amount + uModel.additional_payment - uModel.other_deducation | number:2 }}</div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group visible-xs">
                                                    <label><?= lang("lbl_payment_mode"); ?></label>
                                                    <br/>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode3" id="rbcash" ng-model="uModel.mode" value="cash" checked="checked" />
                                                        <label for="rbcash"> <?= lang("lbl_cash"); ?> </label>
                                                    </span>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode3" id="rbcheque" ng-model="uModel.mode" value="cheque" />
                                                        <label for="rbcheque"> <?= lang("lbl_cheque"); ?> </label>
                                                    </span>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode3" ng-model="uModel.mode" id="rbdd" value="dd" />
                                                        <label for="rbdd"> <?= lang("lbl_dd"); ?> </label>
                                                    </span>
                                                </div>

                                                <div class="form-group hidden-xs">
                                                    <label><?= lang("lbl_payment_mode"); ?></label><br/>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode4" id="rbcash" ng-model="uModel.mode" value="cash" checked="checked" />
                                                        <label for="rbcash"> <?= lang("lbl_cash"); ?> </label>
                                                    </span>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode4" id="rbcheque" ng-model="uModel.mode" value="cheque" />
                                                        <label for="rbcheque"> <?= lang("lbl_cheque"); ?> </label>
                                                    </span>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode4" ng-model="uModel.mode" id="rbdd" value="dd" />
                                                        <label for="rbdd"> <?= lang("lbl_dd"); ?> </label>
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                        
                                        <div class="col-md-6 p-0 m-0">
                                            <div class="col-md-12">
                                                
                                                <div class="form-group">
                                                    <label><?= lang("lbl_payable_amount"); ?></label>
                                                    <input type="number" 
                                                        required="" 
                                                        ng-model="uModel.paid_amount" 
                                                        max="{{(-1*editModel.balance) + uModel.additional_payment - uModel.other_deducation}}" 
                                                        min="0" 
                                                        class="form-control" 
                                                    />
                                                    <small class="text-info"><b><?php echo lang('total_payable_amount'); ?>: </b>{{(-1*editModel.balance) + uModel.additional_payment - uModel.other_deducation}}</small>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_other_deduction_remarks"); ?></label>
                                                    <input type="text" class="form-control" ng-model="uModel.other_deduction_remarks"/>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_additional_payment_remarks"); ?></label>
                                                    <input type="text" class="form-control" ng-model="uModel.additional_payment_remarks"/>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?php echo lang('lbl_comment') ?></label>
                                                    <input type="text" class="form-control" ng-model="uModel.comment" />
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group mb-0">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox4" type="checkbox" ng-model="uModel.sendEmailToEmployee" />
                                                        <label for="checkbox4"> <?php echo lang("lbl_send_mail_to_employee"); ?> </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        

                                    </div>
                                </div>
                                <div class="panel-footer" style="text-align: right;">
                                    <button type="submit" class="btn btn-primary" ng-disabled="update_loading"><?= lang("lbl_pay"); ?></button>
                                    <button type="reset" class="btn btn-default" data-dismiss="modal"><?= lang("btn_cancel"); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- end edit fee collection modal -->

        <!-- start pay collection modal -->
        <div class="modal fade" id="payrollAddModel" tabindex="-1" role="dialog" aria-labelledby="payrollAddModel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" id="payroll-model-contents">
                    <form name="payrollAddModelForm" ng-submit="collectPayroll(payrollAddModelForm.$valid)" novalidate="" class="form-material">
                        <div class="form-body">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&Cross;</button>
                                    <?= lang("lbl_pay_form"); ?>
                                </div>
                                <div class="panel-body">
                                    
                                    <div class="col-md-12 p-0 m-0">
                                        <div class="col-md-6 p-0 m-0">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_date"); ?></label>
                                                    <input type="text" ng-value="afcModel.due_date" disabled="" class="form-control mydatepicker-autoclose">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_other_deductions"); ?></label>
                                                    <input type="number" class="form-control" ng-model="other_deducation" />
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_additional_payment"); ?></label>
                                                    <input type="number" class="form-control" ng-model="additional_payment" />
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_paid_amount"); ?></label>
                                                    <input type="number" class="form-control" max="{{afcModel.payable_amount-other_deducation+additional_payment}}" min="0" required="" ng-model="paid_amount" />
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group visible-xs">
                                                    <label><?= lang("lbl_payment_mode"); ?></label>
                                                    <br/>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode2" id="rbcash3" ng-model="mode" value="cash" checked="checked" />
                                                        <label for="rbcash3"> <?= lang("lbl_cash"); ?> </label>
                                                    </span>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode2" id="rbcheque3" ng-model="mode" value="cheque" />
                                                        <label for="rbcheque3"> <?= lang("lbl_cheque"); ?> </label>
                                                    </span>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode2" ng-model="mode" id="rbdd3" value="dd" />
                                                        <label for="rbdd3"> <?= lang("lbl_dd"); ?> </label>
                                                    </span>
                                                </div>

                                                <div class="form-group hidden-xs">
                                                    <label><?= lang("lbl_payment_mode"); ?></label><br/>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode" id="rbcash2" ng-model="mode" value="cash" checked="" />
                                                        <label for="rbcash2"> <?= lang("lbl_cash"); ?> </label>
                                                    </span>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode" id="rbcheque2" ng-model="mode" value="cheque" />
                                                        <label for="rbcheque2"> <?= lang("lbl_cheque"); ?> </label>
                                                    </span>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode" ng-model="mode" id="rbdd2" value="dd" />
                                                        <label for="rbdd2"> <?= lang("lbl_dd"); ?> </label>
                                                    </span>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        
                                        <div class="col-md-6 p-0 m-0">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_payable_amount"); ?></label>
                                                    <input type="text" class="form-control" disabled="" ng-value="afcModel.payable_amount-other_deducation+additional_payment" />
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_other_deduction_remarks"); ?></label>
                                                    <input type="text" class="form-control" ng-model="other_deduction_remarks"/>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_additional_payment_remarks"); ?></label>
                                                    <input type="text" class="form-control" ng-model="additional_payment_remarks"/>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_comment"); ?></label>
                                                    <input type="text" class="form-control" ng-model="comment"/>
                                                </div>
                                            </div>
                                            
                                            
                                            
                                            <div class="col-md-12">
                                                <div class="form-group mb-0">
                                                    <div class="checkbox checkbox-info">
                                                        <input id="checkbox42" type="checkbox" name="isSendEmailToEmployee" ng-model="isSendEmailToEmployee" />
                                                        <label for="checkbox42"> <?php echo lang("lbl_send_mail_to_employee"); ?> </label>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-0">
                                                    <div class="checkbox checkbox-info">
                                                        <input id="checkbox52" type="checkbox" name="isSendSMSToEmployee" ng-model="isSendSMSToEmployee" />
                                                        <label for="checkbox52"> <?php echo lang("lbl_send_sms_to_employee"); ?> </label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>                                    
                                </div>
                                <div class="panel-footer" style="text-align: right;">
                                    <button type="submit" class="btn btn-primary" id="payRollPayBtn" ng-disabled="loading"><?= lang("lbl_pay"); ?></button>
                                    <button type="reset" class="btn btn-default" data-dismiss="modal"><?= lang("btn_cancel"); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- end pay collection modal -->

        <!--Partially Payroll detail Model-->
        <div id="payrollInfoModel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&Cross;</button>
                            <h4 class="text-white"><?php echo "Partially Payroll Details"; ?></h4>
                            <span class="text-white">{{ partiallyPayrollDetailModel.salary_name }} </span>
                            <small>({{ partiallyPayrollDetailModel.due_date }})</small>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <th class="text-center"><?php echo lang("imp_sr"); ?></th>
                                        <th class="text-center"><?php echo lang("lbl_paid_by"); ?></th>
                                        <th class="text-center"><?php echo lang("lbl_date"); ?></th>
                                        <th class="text-center"><?php echo lang("lbl_paid"); ?></th>
                                        <th>Receipt No</th>
                                        <th class='text-center'><?php echo lang("th_action"); ?></th>
                                    </tr>
                                    <tr class="text-center" ng-repeat="row in partiallyPayrollDetailModel.payroll_record">
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ row.paid_by_name }}</td>
                                        <td>{{ row.created_at }}</td>
                                        <td><?php echo $this->session->userdata('userdata')['currency_symbol'] ?>{{row.paid_amount}}</td>
                                        <td>{{row.receipt_no}} <a ng-if="row.remarks !== '' && row.remarks !== 'undefined'" href="javascript:void(0)" data-html="true" data-toggle="tooltip" data-placement="top" title="{{row.remarks}}"><i class="fa fa-info-circle"></i></a></td>
                                        <td>
                                            <a href="javascript:void(0)" ng-click="showConfirmationAlert(row,'null')" class="btn-sm btn btn-danger btn-circle text-white"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Partially detail model end-->

    <!--.row-->
    <div class="row" id="payroll" ng-init="initDepertments();initAcademicYears()">
        <div class="col-md-12"> 
            <div class="white-box">
                <form class="form-material" id="payroll_search_filter" name="payrollFilterForm" ng-submit="fetchPayrolls(payrollFilterForm.$valid)" novalidate="">
                    <!--/row-->
                    <div class="row">

                        <!--/span-->
                        <!-- Acdemic year filter added by sheraz #AYP -->
                        <div class="col-md-3" id="filterAcademicYears">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang("lbl_academic_year"); ?></label>
                                <select class="form-control" name="academic_year_id" ng-model="filterModel.academic_year_id">
                                    <option value="-1"><?php echo lang("lbl_select_academic_year"); ?></option>
                                    <option ng-repeat="ay in academicyears" value="{{ ay.id }}">{{ ay.name }}</option>
                                </select>
                            </div>
                        </div>
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
                        <!--/span-->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang("serach_by_keyword"); ?></label>
                                <input type="text" class="form-control" name="searchBy" placeholder="<?php echo lang('lbl_name_employee_id'); ?>" ng-model="filterModel.searchBy">
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="checkbox checkbox-info">
                                        <input id="checkbox4" type="checkbox" name="unpaid" ng-model="filterModel.unpaid" />
                                        <label for="checkbox4"> <?php echo lang('lbl_only_upadid_employee'); ?> </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-search "></i> <?php echo lang("search"); ?></button>
                        </div>
                    </div>
                    <!--/row-->
                </form>
            </div>

        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div id="payrollContainer1">
                <div class="white-box">
                    <div class="table-responsive" ng-if="payrollEmployees.length>0">
                        <table id="myTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center"><?php echo lang("imp_sr"); ?></th>
                                    <th><?php echo lang("lbl_avatar"); ?></th>
                                    <th><?php echo lang("lbl_name"); ?></th>
                                    <th><?php echo lang("emp_id"); ?></th>
                                    <th><?php echo lang("heading_position"); ?></th>
                                    <th><?php echo lang("title_category"); ?></th>
                                    <th><?php echo lang("title_department"); ?></th>
                                    <th><?php echo lang("lbl_status"); ?></th>
                                    <th><?php echo lang("th_action"); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="emp in payrollEmployees">
                                    <td class="text-center">{{$index+1}}</td>
                                    <td><img ng-src="uploads/user/{{emp.avatar}}" style="width:50px; height:50px;" class="thumb-lg img-circle" alt="employee-img"></td>
                                    <td>{{emp.name}}</td>
                                    <td>{{emp.rollno}}</td>
                                    <td>{{emp.job_title}}</td>
                                    <td>{{emp.category}}</td>
                                    <td>{{emp.department}}</td>
                                    <td>
                                        <!--<button ng-if="emp.stCount > 0 && emp.stCount!=emp.scCount" class="btn btn-danger btn-xs"><?php echo lang('lbl_due') ?>-->
                                        <!--</button>-->
                                        <!--<button ng-if="emp.stCount > 0 && emp.stCount==emp.scCount" class="btn btn-success btn-xs"><?php echo lang("lbl_paid"); ?>-->
                                        <!--</button>-->
                                        <!--<button ng-if="emp.stCount <= 0" class="btn btn-danger btn-xs"><?php echo lang('lbl_due') ?></button>-->
                                        
                                        <button ng-if="emp.payroll_status == 0" class="btn btn-danger btn-xs"><?php echo lang('lbl_due') ?></button>
                                        <button ng-if="emp.payroll_status == 1" class="btn btn-success btn-xs"><?php echo lang("lbl_paid"); ?></button>
                                        
                                    </td>
                                    <td><button class="btn btn-primary btn-xs" ng-click="showDetails(emp)"><?php echo lang('btn_view') ?></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <span ng-if="payrollEmployees.length===0" class="text-danger"><?php echo lang("no_record"); ?></span>
                </div>
            </div>

            <div class="payrollContainer2 hidden">
                <div class="white-box">
                    <div class="row">
                        <div class="col-md-12 p-0 mb-2">
                            <a href="javascript:void(0);" ng-click="back()" class="btn btn-default"><i class="fa fa-reply " aria-hidden="true"></i> <?php echo lang("btn_back"); ?></a>
                        </div>

                        <div class="col-md-12 well">

                            <div class="col-md-2 text-center visible-xs">
                                <img ng-src="uploads/user/{{selectedEmp.avatar}}" class="thumb-lg img-circle" alt="employee-img">
                            </div>
                            <div class="col-md-2 text-center hidden-xs">
                                <img ng-src="uploads/user/{{selectedEmp.avatar}}" class="thumb-lg img-circle" alt="employee-img">
                            </div>

                            <div class="col-md-10">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="table-responsive m-t-10  col-md-12">
                                        <table class="table-sm col-md-12">
                                                <tr>
                                                    <th><?php echo lang("lbl_name"); ?></th><td>{{ selectedEmp.name }}</td>
                                                    <th><?php echo lang("title_category"); ?></th><td>{{ selectedEmp.category }}</td>
                                                </tr>
                                                <tr>
                                                    <th><?php echo lang("lbl_phone_number"); ?></th><td>{{ selectedEmp.mobile_phone }}</td>
                                                    <th><?php echo lang("lbl_joining_date"); ?></th><td>{{ selectedEmp.joining_date }}</td>
                                                </tr>
                                                <tr>
                                                    <th><?php echo lang("emp_id"); ?></th><td>{{ selectedEmp.rollno }}</td>
                                                    <th><?php echo lang("lbl_payroll_group"); ?></th><td>{{ selectedEmp.group_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th><?php echo lang("lbl_basic_salary"); ?></th><td>{{ selectedEmp.basic_salary }}</td>
                                                    <th><?php echo lang("lbl_currency_symbol"); ?></th><td><?php echo $this->session->userdata('userdata')['currency_symbol'] ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-12 well" id="attandance_record_container">
                            <div class="col-md-12">
                                <div class="form-group row" id="arFilterMonth">
                                    <label class="control-label text-right col-md-4"><?php echo lang('lbl_month') ?></label>
                                    <div class="col-md-4">
                                        <select class="form-control" id="ymonth" ng-change="getEmployeeAttendanceRecord(ymonth)" ng-model="ymonth" required="">
                                            <option value=""><?php echo lang('lbl_select_month') ?></option>
                                            <option ng-repeat="(key, month) in months" value="{{key}}">{{month}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <div class="table-responsive" ng-if="empAttendanceRecord.Present">
                                    <table class="table table-bordered table-hovered table-striped">
                                        <tr>
                                            <th class="text-center"><?php echo lang("lbl_present"); ?></th>
                                            <th class="text-center"><?php echo lang("lbl_absent"); ?></th>
                                            <th class="text-center"><?php echo lang("lbl_late"); ?></th>
                                            <th class="text-center"><?php echo lang("lbl_leave"); ?></th>
                                        </tr>
                                        <tr class="text-center" style="font-family:Arial, Helvetica, sans-serif;">
                                            <td><strong class="text-success">{{empAttendanceRecord.Present}}</strong></td>
                                            <td><strong class="text-danger">{{empAttendanceRecord.Absent}}</strong></td>
                                            <td><strong class="text-warning">{{empAttendanceRecord.Late}}</strong></td>
                                            <td><strong class="text-danger">{{empAttendanceRecord.Leave}}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                                <p class="text-center text-danger" ng-if="!empAttendanceRecord.Present"><?php echo lang("no_record"); ?></p>
                            </div>
                        </div>

                        <br/>

                        <div class="col-md-12 p-0">
                            <div ng-if="empPayrollRecords.status==='error'">
                                <span class="text-danger">{{empPayrollRecords.message}}</span>
                                <a href="<?php echo site_url('payroll/settings'); ?>">Payroll Settings</a>
                            </div>
                            <div class="table-responsive" ng-if="empPayrollRecords.status==='success'">
                                <table id="myTable" class="table table-striped table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th><?php echo lang("lbl_salary_type"); ?></th>
                                            <th><?php echo lang("lbl_paid_by"); ?></th>
                                            <th><?php echo lang("lbl_status"); ?></th>
                                            <th><?php echo lang("lbl_annual_increment") ?></th>
                                            <th><?php echo lang("lbl_allowances"); ?></th>
                                            <th><?php echo lang("lbl_dedections"); ?></th>
                                            <th><?php echo lang("lbl_other_deductions"); ?></th>
                                            <th><?php echo lang("lbl_additional_payment"); ?></th>                                            
                                            <th><?php echo lang("lbl_gross_salary"); ?></th>
                                            <th><?php echo lang("lbl_paid_amount"); ?></th>
                                            <th><?php echo lang("paid_date"); ?></th>
                                            <th><?php echo lang("lbl_receipt_no"); ?></th>
                                            <th><?php echo lang("lbl_balance"); ?></th>
                                            <th><?php echo lang("lbl_remarks"); ?></th>
                                            <th class="text-center"><?php echo lang("lbl_action"); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="(key,rcd) in empPayrollRecords.data">
                                            <td><b><em>{{rcd.salary_name}}</em></b> <br/><small>({{rcd.due_date}})</small></td>
                                            <td>
                                                <span ng-if="rcd.payroll_id==='null'"></span>
                                                <span ng-if="rcd.payroll_id!=='null'">{{rcd.payroll_record[0].paid_by_name}}</span>
                                            </td>
                                            <td>
                                                <span ng-if="rcd.payroll_record[0].status===0" class="text-danger">Unpaid</span>
                                                <span ng-if="rcd.payroll_record[rcd.payroll_record.length-1].status==='1'" class="text-success">Paid</span>
                                                <span ng-if="rcd.payroll_record[rcd.payroll_record.length-1].status==='2'" class="text-warning">Partially Paid</span>
                                            </td>
                                            <!--<td><span><?php //echo $this->session->userdata('userdata')['currency_symbol'] ?> {{rcd.amount}}</span></td>-->
                                            <td><span>{{rcd.anual_increment}}</span></td>
                                            <td><span>{{rcd.allowance}}</span></td>
                                            <td><span>{{rcd.deducation}}</span></td>
                                            <td><span>{{rcd.other_deductions}}</span></td>
                                            <td><span>{{rcd.additional_payment}}</span></td>
                                            <td>
                                                <span>{{rcd.payable_amount - parseInt(rcd.other_deductions) + parseInt(rcd.additional_payment)}}</span>
                                            </td>
                                            <td>
                                                <span ng-if="rcd.payroll_id==='null'"></span>
                                                <span ng-if="rcd.payroll_id!=='null'">{{rcd.total_paid_amount}}</span>
                                            </td>
                                            <td>
                                                <span ng-if="rcd.payroll_id === 'null'"></span>
                                                <span ng-if="rcd.payroll_id !== 'null'">{{rcd.payroll_record[rcd.payroll_record.length-1].created_at}}</span>
                                            </td>
                                            <td>
                                                <span ng-if="rcd.payroll_record[0].payroll_id === 'null'"></span>
                                                <span ng-if="rcd.payroll_record[0].payroll_id !== 'null'">{{rcd.payroll_record[0].receipt_no}}</span>
                                            </td>
                                            <td>
                                                <span>{{rcd.balance}}</span>
                                            </td>
                                            <td>
                                                <span ng-if="rcd.payroll_record[0].payroll_id==='null'"></span>
                                                <a href="javascript:void(0)" ng-if="rcd.payroll_record[0].payroll_id !== 'null'" ng-click="showRemarksModal(rcd)" data-toggle="modal" data-target=".bs-example-modal-sm">Remarks</a>
                                            </td>
                                            <td style="min-width:150px;" class='text-center'>
                                                <div ng-if="rcd.payroll_record[0].payroll_id!=='null'">
                                                    <a href="javascript:void(0)" ng-if="rcd.payroll_record.length>1" ng-click="showPartiallyPayrollDeatils(rcd)" data-toggle="modal" data-target="#payrollInfoModel" class="btn-sm btn btn-primary btn-circle text-white"><i class="fa fa-info"></i></a>

                                                    <a href="javascript:void(0)" ng-if="rcd.payroll_record[rcd.payroll_record.length-1].status==='2'" class="btn-sm btn btn-info btn-circle text-white" data-toggle="modal" data-target="#payrollEditModel" ng-click="setEditPayrollCollectionModel(rcd)" ><i class="fa fa-plus"></i></a>
                                                    <?php if($role_id == '1' || (isset($delete_salary) && $delete_salary == '1')){?>
                                                        <a ng-if="rcd.payroll_id !=='null'" href="javascript:void(0)" class="btn-sm btn btn-danger btn-circle text-white" ng-click="showConfirmationAlert(rcd,'all')"><i class="fa fa-trash-o"></i></a>
                                                     <?php }?>    
                                                    <a ng-if="rcd.payroll_id !=='null'" href="<?php echo base_url(); ?>forms/show?id={{rcd.employee_id}}&salary_type_id={{rcd.salary_type_id}}&requested_page=single_payroll" target="_blank" class="btn-sm btn btn-success btn-circle text-white"><i class="fa fa-print"></i></a>
                                                </div>
                                                <?php if($role_id == '1' || (isset($pay_salary) && $pay_salary == '1')){?>
                                                    <a ng-if="rcd.payroll_record[0].payroll_id==='null'" href="javascript:void(0);" ng-click="loadDataInModel(rcd)" data-toggle="modal" data-target="#payrollAddModel" class='btn btn-primary btn-sm text-white'><?php echo lang("lbl_pay"); ?></a>
                                                <?php }?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-center"><b><?php echo lang('lbl_total'); ?></b></td>
                                            <td>{{empPayrollRecords.data[0].anual_increment * empPayrollRecords.data.length}}</td>
                                            <td>{{total_allowances}}</td>
                                            <td>{{total_deductions}}</td>
                                            <td>{{empPayrollRecords.data[0].total_other_deductions}}</td>
                                            <td>{{empPayrollRecords.data[0].total_additional_payment}}</td>
                                            <td>{{total_gross_salary}}</td>
                                            <td>{{total_paid_amount}}</td>
                                            <td colspan="5" class="text-center">{{total_balance}}</td>
                                        </tr>
                                    </tbody>
                                </table>
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
<!--/panel wrapper-->
</div>
<!--/panel-->
</div>
</div>
<!--./row-->
<!--page content end here-->
</div>
<?php include(APPPATH . "views/inc/footer.php"); ?>