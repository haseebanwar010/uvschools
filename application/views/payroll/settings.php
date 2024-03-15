<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<style>
.item {
    position:relative;
    padding-top:20px;
    display:inline-block;
    margin-right: 10px;
}
.notify-badge{
    position: absolute;
    right:-10px;
    top:10px;
    background:red;
    text-align: center;
    border-radius: 30px 30px 30px 30px;
    color:white;
    padding:5px 6px;
    font-size:6px;
}
.yasir-custom-border-style{
    border: 1px dashed; 
    background: #e5e5e5;
}

</style>
<div>
    <!-- Page Content -->
    <div id="page-wrapper" ng-controller="payrollController" ng-init="initSalaryTypes(); initSalaryGroups(); initEmployees();">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?php echo lang('lbl_payroll_settings') ?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="#"><?= lang('crumb_dashboard') ?></a></li>
                        <li><a href="#"><?php echo lang('crumb_settings') ?></a></li>
                        <li class="active"><?php echo lang('lbl_payroll_settings') ?></li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
           
            <?php $role_id = $this->session->userdata("userdata")["role_id"]; 
                if ($role_id == EMPLOYEE_ROLE_ID) {
                $ci = & get_instance();
                $arr = $ci->session->userdata("userdata")['persissions'];
                $array = json_decode($arr);
                if (isset($array)) {
                    $settingsadd = 0;
                    $settingsedit = 0;
                    $settingsdelete = 0;
                    foreach ($array as $key => $value) {
                        if (in_array('payroll-settingsadd', array($value->permission)) && $value->val == 'true') {
                            $settingsadd = 1;
                        } if (in_array('payroll-settingsedit', array($value->permission)) && $value->val == 'true') {
                            $settingsedit = 1;
                        }if (in_array('payroll-settingsdelete', array($value->permission)) && $value->val == 'true') {
                            $settingsdelete = 1;
                        }
                    }
                }
            }?>
            <!-- Salary Type Varients Modal -->
            <div class="modal animated slideInDown" id="salaryTypeVarientModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;" ng-init="initClasses()">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content" id="salartypevarientmodal-contents">
                        <div class="panel panel-primary">     
                            <div class="panel-heading">
                                <h4 class="modal-title text-white" id="myLargeModalLabel"><?php echo lang('lbl_payroll_group_variants') ?><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button></h4>
                            </div>
                            <div class="panel-body">
                                <?php if(isset($settingsadd) && $settingsadd == '1'){?>
                                    <button type="button" ng-click="setVarientFormValue(true)" class="btn btn-primary mb-2"><i class="fa fa-plus"></i> <?php echo lang('lbl_add'); ?></button>
                                <?php } else if($role_id == '1') { ?>
                                    <button type="button" ng-click="setVarientFormValue(true)" class="btn btn-primary mb-2"><i class="fa fa-plus"></i> <?php echo lang('lbl_add'); ?></button>
                                <?php } ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped">
                                        <tr style="background: #dbdadf !important; border:#e5e5e5;">
                                            <th>#</th>
                                            <th><?php echo lang('lbl_name') ?></th>
                                            <th><?php echo lang('description') ?></th>
                                            <th><?php echo lang('type_dt') ?></th>
                                            <th><?php echo lang('lbl_effect') ?></th>
                                            <th><?php echo lang('amount') ?></th>
                                            <th class="text-center"><?php echo lang('lbl_tbl_action') ?></th>
                                        </tr>
                                        <tr ng-if="selectedSalaryTypeVarients.length>0" ng-repeat="stv in selectedSalaryTypeVarients">
                                            <td>{{$index+1}}</td>
                                            <td>{{stv.name}}</td>
                                            <td>{{stv.description}}</td>
                                            <td>{{stv.type}}</td>
                                            <td>{{stv.effect}}</td>
                                            <td>{{stv.amount}}</td>
                                            <td width="100px" class="text-center">
                                            <?php if($role_id == '1' || (isset($settingsedit) && $settingsedit == '1')){?>    
                                                <a href="javascript:void(0)" class="btn btn-circle btn-info text-white" ng-click="setSalaryVarientForm(true, stv)"><i class="fa fa-pencil"></i></a>
                                            <?php }?>    
                                            <?php if($role_id == '1' || (isset($settingsdelete) && $settingsdelete == '1')){?>    
                                                <a href="javascript:void(0)" class="btn btn-circle btn-danger text-white" ng-click="deleteSalaryTypeVarient(stv.id)"><i class="fa fa-trash-o"></i></a>
                                            <?php }?>    
                                            </td>
                                        </tr>
                                        <tr ng-if="selectedSalaryTypeVarients.length==0">
                                            <td colspan="7"><span class="text-danger"><?php echo lang("no_record"); ?></span></td>
                                        </tr>
                                    </table>
                                </div>

                                <form name="varientSalaryTypeForm" ng-if="isVarientFormShow" ng-submit="saveSalaryTypeVarient(varientSalaryTypeForm.$valid)" novalidate="" class="form-material">     
                                    <h3><?php echo lang('lbl_addNew_VariantForm') ?></h3>
                                    <hr class="mt-0"/>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_variant_name') ?></label>
                                                <input type="text" name="name" required="" ng-model="stvModel.name" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('type_dt') ?></label>
                                                <select class="form-control" required="" ng-model="stvModel.type">
                                                    <option value="">---<?php echo lang('lbl_select_type') ?>---</option>
                                                    <option value="number"><?php echo lang('lbl_number') ?></option>
                                                    <option value="percentage"><?php echo lang('percentage') ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_effect') ?></label>
                                                <select class="form-control" required="" ng-model="stvModel.effect">
                                                    <option value="">---<?php echo lang('lbl_select_effect') ?>---</option>
                                                    <option value="positive"><?php echo lang('lbl_positive') ?></option>
                                                    <option value="negative"><?php echo lang('lbl_negative') ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('amount') ?></label>
                                                <input type="number" name="name" required="" ng-model="stvModel.amount" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('description') ?></label>
                                                <textarea cols="5" rows="3" ng-model="stvModel.description" class="form-control"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-12 pull-right text-right">
                                            <button type="button" class="btn btn-default" ng-click="setVarientFormValue(false)"><?php echo lang('btn_back') ?> </button>
                                            <button type="submit" class="btn btn-primary"><?php echo lang('btn_save_bank') ?> </button>
                                        </div>
                                    </div>
                                </form>

                                <form name="varientPayrollGroupEditForm" id="varientPayrollGroupEditForm" ng-if="isVarientFormEditShow" ng-submit="updatePayrollGroupVarient(varientPayrollGroupEditForm.$valid)" novalidate="" class="form-material">     
                                    <h3><?php echo lang('lbl_variant_editForm') ?></h3>
                                    <hr class="mt-0"/>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_variant_name') ?></label>
                                                <input type="text" name="name" required="" ng-model="estvModel.name" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('type_dt') ?></label>
                                                <select class="form-control" required="" ng-model="estvModel.type">
                                                    <option value="">---<?php echo lang('lbl_select_type') ?>---</option>
                                                    <option value="number"><?php echo lang('lbl_number') ?></option>
                                                    <option value="percentage"><?php echo lang('percentage') ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_effect') ?></label>
                                                <select class="form-control" required="" ng-model="estvModel.effect">
                                                    <option value="">---<?php echo lang('lbl_select_effect') ?>---</option>
                                                    <option value="positive"><?php echo lang('lbl_positive') ?></option>
                                                    <option value="negative"><?php echo lang('lbl_negative') ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('amount') ?></label>
                                                <input type="number" name="name" required="" ng-model="estvModel.amount" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('description') ?></label>
                                                <textarea cols="5" rows="3" ng-model="estvModel.description" class="form-control"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-12 pull-right text-right">
                                            <button type="button" class="btn btn-default" ng-click="setSalaryVarientForm(false, {})"><?php echo lang('btn_back') ?> </button>
                                            <button type="submit" class="btn btn-primary"><?php echo lang('btn_profile_update') ?> </button>
                                        </div>
                                    </div>
                                </form>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal -->
            
            <!-- Add Modal Content -->
            <div class="modal animated slideInDown" id="salaryTypeAddModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content" id="addsalarytype-contents">
                        <div class="panel panel-primary"> 
                            <button type="button" class="close" style="margin: 20px;" data-dismiss="modal" aria-hidden="true">×</button>
                            <div class="panel-heading"><?php echo lang('lbl_salary_typeForm') ?></div>
                            <div class="panel-body">
                                <form name="addSalaryTypeForm" ng-submit="addSalaryType(addSalaryTypeForm.$valid)" novalidate="" class="form-material ">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_salary_name') ?></label>
                                                    <input type="text" name="name" required="" ng-model="stModel.name" class="form-control">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_payroll_groups') ?></label>
                                                    <select class="form-control yasir-payroll-select2" style="width:100%;" multiple="multiple" required="" ng-model="stModel.payroll_group_ids">
                                                        <option ng-repeat="grp in payrollGroups" value="{{grp.id}}">{{grp.name}}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!--<div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">Amount</label>
                                                    <input type="number" name="amount" required="" ng-model="stModel.amount" class="form-control">
                                                </div>
                                            </div>-->

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('due_date') ?></label>
                                                    <input type="text" name="date" ng-model="stModel.date" class="form-control customdatpicker-formodal">
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('description') ?></label>
                                                    <textarea name="description" ng-model="stModel.description" class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row pull-right">
                                        <div style="margin-right: 8px">
                                            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('modal_btn_close') ?> </button>
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-primary"><?php echo lang('lbl_add') ?> </button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal -->

            <!-- Add Payroll Group Modal Content -->
            <div class="modal animated slideInDown" id="payrollGroupAddModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content" id="addpayrollgroup-contents">
                        <div class="panel panel-primary"> 
                            <button type="button" class="close" style="margin: 20px;" data-dismiss="modal" aria-hidden="true">×</button>
                            <div class="panel-heading"><?php echo lang('lbl_payrollGroup_form') ?></div>
                            <div class="panel-body">
                                <form name="addPayrollGroupForm" ng-submit="addPayrollGroup(addPayrollGroupForm.$valid)" novalidate="" class="form-material ">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('level_name') ?></label>
                                                    <input type="text" name="name" required="" ng-model="pgModel.name" class="form-control">
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('heading_all_employee') ?></label>
                                                    <select class="form-control yasir-payroll-select2" required="" multiple="multiple" style="width:100%;" ng-model="pgModel.employees">
                                                        <option ng-repeat="emp in employees" value="{{emp.id}}">{{emp.name}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('description') ?></label>
                                                    <textarea name="description" required="" ng-model="pgModel.description" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                    </div>
                                    <div class="row pull-right">
                                        <div style="margin-right: 8px">
                                            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('modal_btn_close') ?> </button>
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-primary"><?php echo lang('lbl_add') ?> </button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal -->

            <!-- Edit Payroll Group Modal Content -->
            <div class="modal animated slideInDown" id="payrollGroupEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content" id="editpayrollgroup-contents">
                        <div class="panel panel-primary"> 
                            <button type="button" class="close" style="margin: 20px;" data-dismiss="modal" aria-hidden="true">×</button>
                            <div class="panel-heading"><?php echo lang('lbl_payrollGroup_editForm') ?></div>
                            <div class="panel-body">
                                <form name="updatePayrollGroupForm" ng-submit="updatePayrollGroup(updatePayrollGroupForm.$valid)" novalidate="" class="form-material ">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('level_name') ?></label>
                                                    <input type="text" name="name" required="" ng-model="pgEditModel.name" class="form-control">
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-12" ng-class="{'yasir-custom-border-style':pgEditModel.employee_details.length>0}">
                                                <div class="form-group" ng-if="pgEditModel.employee_details.length>0">
                                                    <label class="control-label text-info"><?php echo lang('lbl_already_selectedMembers') ?></label><br/>
                                                    <div class="item" ng-repeat="value in pgEditModel.employee_details">
                                                        <a href="javascript:void(0);" ng-click="removeEmployeeFromGroup(value)">
                                                            <span class="notify-badge"><i class="fa fa-minus"></i></span>
                                                            <img class="img-circle" src="uploads/user/{{value.avatar}}" title="{{value.name}}" style="width:30px; margin-left:10px;"/>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-12" style="margin-bottom: 10px;"> 
                                                <label class="control-label"><?php echo lang('heading_all_employee') ?></label>
                                                <select class="form-control yasir-payroll-select2" ng-required="pgEditModel.employee_details.length===0" multiple='multiple' style="width:100%;" ng-model="editEmp">
                                                    <option ng-repeat="emp in employees" value="{{emp.id}}">{{emp.name}}</option>
                                                </select>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('description') ?></label>
                                                    <textarea name="description" ng-model="pgEditModel.description" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                    </div>
                                    <div class="row pull-right">
                                        <div style="margin-right: 8px">
                                            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('modal_btn_close') ?> </button>
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-primary"><?php echo lang('lbl_calendar_updateBtn') ?> </button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal -->

            <!-- Edit Modal Content -->
            <div class="modal animated slideInDown" id="salaryTypeEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content" id="editsalarytype-contents">
                        <div class="panel panel-primary"> 
                            <button type="button" class="close" style="margin: 20px;" data-dismiss="modal" aria-hidden="true">×</button>
                            <div class="panel-heading"><?php echo lang('lbl_edit_salaryType') ?></div>
                            <div class="panel-body">
                                <form name="editSalaryTypeForm" ng-submit="updateSalaryType(editSalaryTypeForm.$valid)" novalidate="" class="form-material ">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_salary_name') ?></label>
                                                    <input type="text" name="name" required="" ng-model="myModel2.name" class="form-control">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_payroll_groups') ?></label>
                                                    <select class="form-control" required="" ng-model="myModel2.payroll_group_id" ng-init="initSalaryGroups()">
                                                        <option value="">---<?php echo lang('lbl_select_a_payroll_group') ?>---</option>
                                                        <option ng-repeat="grp in payrollGroups" value="{{grp.id}}">{{grp.name}}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('due_date') ?></label>
                                                    <input type="text" name="date" ng-model="myModel2.formated_date" class="form-control customdatpicker-formodal">
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('description') ?></label>
                                                    <textarea name="description" ng-model="myModel2.description" class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row pull-right">
                                        <div style="margin-right: 8px">
                                            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('modal_btn_close') ?> </button>
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-primary"><?php echo lang('lbl_calendar_updateBtn') ?> </button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal -->

            <div class="hint"><?php echo lang('lbl_help_payroll_settings') ?></div>
            <!-- Page Content start here -->
            <!--.row-->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">

                        <div class="panel-wrapper collapse in" aria-expanded="true">
                            <div class="panel-body">
                                <ul class="nav customtab nav-tabs table-responsive" role="tablist">
                                    <li role="presentation" class="nav-item"><a href="#groups" class="nav-link active" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="fa fa-calendar"></i></span><span class="hidden-xs"><?php echo lang('lbl_payroll_groups') ?></span></a></li>
                                    <li role="presentation" class="nav-item"><a href="#salary" class="nav-link" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="fa fa-object-group"></i></span><span class="hidden-xs"><?php echo lang('lbl_salary_types') ?></span></a></li>
                                    <li role="presentation" class="nav-item"><a href="#increment_roles" class="nav-link" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="fa fa-object-group"></i></span><span class="hidden-xs"><?php echo lang('lbl_increment_rule') ?></span></a></li>
                                </ul>
                                <!--tab content start here-->
                                <div class="tab-content">
                                    <div class="tab-pane active" id="groups">
                                    <?php if($role_id == '1' || isset($settingsadd) && $settingsadd == '1'){?>
                                        <button type="button" ng-click="showPayrollGroupAddModal()" class="btn btn-primary mb-2"><i class="fa fa-plus"></i> <?php echo lang('lbl_add'); ?></button>
                                    <?php } ?>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover">
                                                <tr style="background: #dbdadf !important; border:#e5e5e5;">
                                                    <th class="text-center" style="width:50px;">#</th>
                                                    <th style="width: 200px;"><?php echo lang("lbl_name"); ?></th>
                                                    <th><?php echo lang("description"); ?></th>
                                                    <th style="width: 350px;"><?php echo lang("heading_all_employee"); ?></th>
                                                    <th class="text-center" style="width:200px;"><?php echo lang("th_action"); ?></th>
                                                </tr>
                                                <tr ng-if="payrollGroups.length>0" ng-repeat="g in payrollGroups">
                                                    <td class="text-center">{{$index+1}}</td>
                                                    <td>{{g.name}}</td>
                                                    <td>{{g.description}}</td>
                                                    <td>
                                                        <span><img class="img-circle mb-2" ng-repeat="value in g.employee_details" title="{{value.name}}" src="uploads/user/{{value.avatar}}" style="width:30px; margin-left:2px;"/></span>
                                                    </td>
                                                    <td class="text-center">
                                                        <!-- <?php ?> -->
                                                        <button type="button" class="btn btn-rounded btn-secondary" ng-click="showPayrollSalaryTypeVarientModal(g)" title="Add Salary Varient"><?php echo lang('lbl_variants') ?></button>
                                                        <?php if($role_id == '1' || isset($settingsedit) && $settingsedit == '1'){?>
                                                        <button type="button" class="btn btn-info btn-circle" ng-click="showPayrollGroupEditModel(g)"><i class="fa fa-pencil"></i></button>
                                                        <?php }?>
                                                        <?php if($role_id == '1' || isset($settingsdelete) && $settingsdelete == '1'){?>
                                                        <button type="button" class="btn btn-danger btn-circle" ng-click="showConfirmationAlertForPayrollGroup(g.id)"><i class="fa  fa-trash-o"></i></button>
                                                    <?php }?>
                                                    </td>
                                                </tr>
                                                <tr ng-if="payrollGroups.length==0">
                                                    <td colspan="5"><?php echo lang("no_record"); ?></td>
                                                </tr>
                                            </table>
                                        </div>

                                        <?php //echo $groups; ?>
                                    </div>
                                    <div class="tab-pane" id="salary">
                                        <!--<button type="button" ng-click="showSalaryTypeAddModal()" class="btn btn-primary mb-2"><i class="fa fa-plus"></i> Add</button>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover">
                                                <tr style="background: #dbdadf !important; border:#e5e5e5;">
                                                    <th class="text-center" style="width:50px;">#</th>
                                                    <th><?php //echo lang("lbl_name"); ?></th>
                                                    <th><?php //echo lang("description"); ?></th>
                                                    <th><?php //echo lang("lbl_payroll_group"); ?></th>
                                                    <th><?php //echo lang("due_date"); ?></th>
                                                    <th class="text-center" style="width:200px;"><?php //echo lang("th_action"); ?></th>
                                                </tr>
                                                <tr ng-if="salaryTypes.length>0" ng-repeat="st in salaryTypes">
                                                    <td class="text-center">{{$index+1}}</td>
                                                    <td>{{st.name}}</td>
                                                    <td>{{st.description}}</td>
                                                    <td>{{st.group_name}}</td>
                                                    <td>{{st.formated_date}}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-info btn-circle" ng-click="showPayrollSalaryTypeEditModel(st)"><i class="fa fa-pencil"></i></button>
                                                        <button type="button" class="btn btn-danger btn-circle" ng-click="showConfirmationAlertForSalaryType(st.id)"><i class="fa  fa-trash-o"></i></button>
                                                    </td>
                                                </tr>
                                                <tr ng-if="salaryTypes.length==0">
                                                    <td colspan="7"><?php //echo lang("no_record"); ?></td>
                                                </tr>
                                            </table>
                                        </div>-->
                                        <?php echo $salary_types; ?>
                                    </div>

                                    <div class="tab-pane" id="increment_roles">
                                        <?php echo $increment_rules; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--page content end here-->
    </div>
</div>
<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>
