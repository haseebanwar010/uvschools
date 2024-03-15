<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<!-- Page Content -->
<div id="page-wrapper" ng-controller="addDeptController" ng-init="initDeperments(); initCategories()">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('heading_hr_settings') ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="#"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li><a href="#"><?php echo lang('crumb_settings') ?></a></li>
                    <li class="active"><?php echo lang('crumb_hr_settings') ?></li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <!-- Alert message -->
        <?php $error = $this->session->flashdata('alert'); if(!empty($error)) { ?>
        <div class="alert alert-dismissable <?php if($this->session->flashdata('alert')['status'] == 'error') { echo 'alert-danger'; } else {echo 'alert-success'; }?>"> 
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>     
            <?= $this->session->flashdata("alert")['message']; ?> 
        </div>
        <?php } ?>
        <!-- End alert message -->
        <div class="hint"><?php echo lang('help_hr_setting'); ?></div>
        <!-- Model For department -->

         <!-- add department model -->
        <div id="responsive-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content" id="addDeptModal">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><?php echo lang('lbl_new_department_form') ?></div>
                        <div class="panel-body">
                         
                            <form name="addDeptForm" ng-submit="onSubmit(addDeptForm.$valid)" novalidate="">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="recipient-name" class="control-label"><?php echo lang("lbl_department_name"); ?></label>
                                        <input type="text" name="name" required="required" ng-model="formModel.name" placeholder="<?= lang('lbl_department_name'); ?>" class="form-control" id="department-name" />
                                    </div>
                                    <div class="form-group">
                                        <label for="code-text" class="control-label"><?php echo lang("lbl_department_code"); ?></label>
                                        <input type="text" required="required" name="code" ng-model="formModel.code" placeholder="<?= lang('lbl_department_code')?>" class="form-control" id="code">
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo lang('lbl_save_as'); ?></label>
                                        <br/>
                                        <span class="radio radio-primary">
                                            <input type="radio" id="teacher" ng-model="formModel.type" value="teacher" />
                                            <label for="teacher"><?php echo lang('lbl_teacher_department'); ?></label>
                                        </span>
                                        <span class="radio radio-primary">
                                            <input type="radio" id="accounts" ng-model="formModel.type" value="accounts" />
                                            <label for="accounts"><?php echo lang('lbl_account_deprtment'); ?></label>
                                        </span>
                                        <span class="radio radio-primary">
                                            <input type="radio" id="other_department" ng-model="formModel.type" ng-init="formModel.type = 'other'" value="other" />
                                            <label for="other_department"><?php echo lang('lbl_other_department'); ?></label>
                                        </span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang("lbl_close"); ?></button>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light"><?php echo lang("lbl_save"); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- edit department model -->
        <div id="edit-department" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content" id="edit-department-content">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><?php echo lang('edit_form') ?></div>
                        <div class="panel-body">
                            
                            <form method="post" name="updateDepartmentForm" ng-submit="updateDepartment(updateDepartmentForm.$valid)">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="recipient-name" class="control-label"><?php echo lang("lbl_department_name"); ?></label>
                                        <input type="text" name="name" ng-model="updateModel.name"required="required" placeholder="<?= lang('lbl_department_name'); ?>" class="form-control" id="edit-department-name" />
                                    </div>
                                    <div class="form-group">
                                        <label for="code-text" class="control-label"><?php echo lang("lbl_department_code"); ?></label>
                                        <input type="text" name="code"  ng-model="updateModel.code" required="required" placeholder="<?= lang('lbl_department_code')?>" class="form-control" id="edit-code">
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo lang('lbl_save_as');?></label>
                                        <br/>
                                        <span class="radio radio-primary">
                                            <input type="radio" id="teacher1" ng-model="updateModel.type" value="teacher" checked="checked" />
                                            <label for="teacher1"><?php echo lang('lbl_teacher_department'); ?></label>
                                        </span>
                                        <span class="radio radio-primary">
                                            <input type="radio" id="accounts1" ng-model="updateModel.type" value="accounts" />
                                            <label for="accounts1"><?php echo lang('lbl_account_deprtment'); ?></label>
                                        </span>
                                        <span class="radio radio-primary">
                                            <input type="radio" id="other_department1" ng-model="updateModel.type" value="other" />
                                            <label for="other_department1"><?php echo lang('lbl_other_department'); ?></label>
                                        </span>
                                    </div>
                                    <input type="hidden" name="id" value="" id="edit-dept-id"/>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang("lbl_close"); ?></button>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light"><?php echo lang("lbl_save"); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

         <!-- add category Modal -->
    <div id="add-category"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" style="<?php if($this->session->userdata("site_lang") == "arabic"){echo "margin-top:25%";} ?>">
            <div class="modal-content" id="add-category-content">
                <div class="panel panel-primary">
                    <div class="panel-heading"><?php echo lang('modal_add_cat_title') ?></div>
                    <div class="panel-body">
                        <form name="addCategoryForm" ng-submit="addCategory(addCategoryForm.$valid)" novalidate="">
                        <div class="modal-body">
                            <input type="hidden" name="school_id" id="school_id" value="<?php echo $UserData['sh_id'];?>"/>
                            <div class="form-group">
                                <label for="category" class="control-label"><?php echo lang("modal_input_cat_name_lbl"); ?></label>
                                <input type="text" name="category" required="" onkeyup="$(this).removeAttr('style');" class="form-control" ng-model="catModel.category" >
                            </div>
                            
                            
                            <div class="form-group">
                                <label class="control-label"><?php echo lang("title_department"); ?></label>
                                <select class="form-control" required="" ng-model="catModel.department_id" id="department_id" ng-init="getDepartments()">  <option value=""><?php echo lang("select_department");?></option> 
                                    <option value="{{dept.id}}" ng-repeat="dept in all_departments">{{dept.name}}</option>
                                   
                                </select> 
                            </div>
                            <hr/>
                            <div class="row">
                                
                                <h4 class="text-center" s><?php echo lang("tab_permissions"); ?></h4> 
                                <div id ="permissionsDiv">
                        <!-- first row  -->
                        <div class="row">
                            <!--{{permissions}}-->
                            <div class="col-md-3">
                                <h5 class="box-title m-b-0"><?php echo lang('lbl_students') ?></h5>
                                <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(0, 7)">
                                    <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">

                                    <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                                   
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5 class="box-title m-b-0"><?php echo lang('crumb_parents') ?></h5>
                                <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(7, 11)">
                                    <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">

                                    <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                                   
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5 class="box-title m-b-0"><?php echo lang('heading_all_employee') ?></h5>
                                <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(11, 16)">
                                    <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">

                                    <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5 class="box-title m-b-0"><?php echo lang('menu_academic_settings') ?></h5>
                                <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(16, 19)">
                                    <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">

                                    <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <!-- second row -->
                        <div class="row">
                            <!--{{permissions}}-->
                             <div class="col-md-3">
                                <h5 class="box-title m-b-0"><?php echo lang('syllabus') ?></h5>
                                
                                <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(19, 23)">
                                    <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">

                                    <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5 class="box-title m-b-0"><?php echo lang('profile_settings') ?></h5>
                                <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(23, 30)">
                                    <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                    <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5 class="box-title m-b-0"><?php echo lang('lbl_evaluation') ?></h5>                                
                                <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(30, 36)">
                                    <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">

                                    <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5 class="box-title m-b-0"><?php echo lang('menu_examination_settings') ?></h5>                                
                                <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(36, 40)">
                                    <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">

                                    <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                                </div>
                            </div>
                           
                        </div>    
                        <hr/>
                        <!-- third row -->
                        <div class="row">
                            <div class="col-md-3">
                               <h5 class="box-title m-b-0"><?php echo lang('lbl_online_examination') ?></h5>
                               <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(40, 47)">
                                   <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                   <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                               </div>
                           </div>
                            <div class="col-md-3">
                               <h5 class="box-title m-b-0"><?php echo lang('lbl-app-permission'); ?></h5>
                               <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(47, 51)">
                                   <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                   <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                               </div>
                           </div>
                           <div class="col-md-3">
                               <h5 class="box-title m-b-0"><?php echo lang('forms_settings'); ?></h5>
                               <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(51, 55)">
                                   <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                   <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                               </div>
                           </div>
                           <div class="col-md-3">
                               <h5 class="box-title m-b-0"><?php echo lang('lbl_user_management'); ?></h5>
                               <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(55, 61)">
                                   <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                   <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                               </div>
                           </div>
                        </div> 
                        <hr/>
                        <!-- fourth row -->
                       <div class="row">
                            <div class="col-md-3">
                               <h5 class="box-title m-b-0"><?php echo lang('lbl_trash'); ?></h5>
                               <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(61, 64)">
                                   <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                   <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                               </div>
                           </div>
                           <div class="col-md-3">
                               <h5 class="box-title m-b-0"><?php echo lang('lbl_fee'); ?></h5>
                               <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(64, 71)">
                                   <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                   <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                               </div>
                           </div>
                           
                           <div class="col-md-3">
                               <h5 class="box-title m-b-0"><?php echo lang('lbl_payroll'); ?></h5>
                               <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(71, 78)">
                                   <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                   <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                               </div>
                           </div>
                          <div class="col-md-3">
                               <h5 class="box-title m-b-0"><?php echo lang('lbl_accounts'); ?></h5>
                               <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(78, 96)">
                                   <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                   <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                               </div>
                           </div>
                       </div>
                        </div>  
                                </div>
                            </div>
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang("modal_btn_close"); ?></button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light"><?php echo lang("modal_btn_save"); ?></button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Category Modal End 20-12-2017 By Shahzaib -->
    <!-- Modal -->
    <div id="edit-category" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="edit-category-content">
              <div class="panel panel-primary">
                <div class="panel-heading"><?php echo lang('modal_edit_cat_title') ?></div>
                <div class="panel-body">
                    <form name="updateCategoryForm" ng-submit="updateCategory()">
                        <div class="modal-body">
                            <input type="hidden" name="category_id" id="category_id" value=""/>
                            <div class="form-group">
                                <label for="name" class="control-label"><?php echo lang("modal_input_cat_name_lbl"); ?></label>
                                <input type="text" class="form-control" ng-model="updateModel.category" onkeyup="$(this).removeAttr('style');" id="category_name_edit">
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?php echo lang("title_department"); ?></label>
                                <select class="form-control" id="department_id_edit"  ng-model="updateModel.department_id" >
                                    <option ng-repeat="dept in all_departments" value="{{dept.id}}">{{dept.name}}</option>
                                </select> 
                            </div>
                            <div class="row">
                                
                        <h4 class="text-center" s><?php echo lang("tab_permissions"); ?></h4>

                        <div id ="permissionsDiv" ng-if="updateModel.permissions.length !=0">
                            <div class="row">
                            <!--{{permissions}}-->
                            <div class="col-md-3">
                                <h5 class="box-title m-b-0"><?php echo lang('lbl_students') ?></h5>
                                <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in updateModel.permissions.slice(0, 7)">
                                    <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">

                                    <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                                   
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5 class="box-title m-b-0"><?php echo lang('crumb_parents') ?></h5>
                                <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in updateModel.permissions.slice(7, 11)">
                                    <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">

                                    <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                                   
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5 class="box-title m-b-0"><?php echo lang('heading_all_employee') ?></h5>
                                <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in updateModel.permissions.slice(11, 16)">
                                    <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">

                                    <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5 class="box-title m-b-0"><?php echo lang('menu_academic_settings') ?></h5>
                                <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in updateModel.permissions.slice(16, 19)">
                                    <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">

                                    <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                                </div>
                            </div>
                        </div>
                        <hr/>
                            <!--{{permissions}}-->
                            <div class="row">
                             <div class="col-md-3">
                                <h5 class="box-title m-b-0"><?php echo lang('syllabus') ?></h5>
                                
                                <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in updateModel.permissions.slice(19, 23)">
                                    <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">

                                    <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5 class="box-title m-b-0"><?php echo lang('profile_settings') ?></h5>
                                <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in updateModel.permissions.slice(23, 30)">
                                    <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                    <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                                </div>

                            </div>
                            <div class="col-md-3">
                                <h5 class="box-title m-b-0"><?php echo lang('lbl_evaluation') ?></h5>                            
                                <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in updateModel.permissions.slice(30, 36)">
                                    <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">

                                    <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                                </div>
                            </div>
                            
                             <div class="col-md-3">
                               <h5 class="box-title m-b-0"><?php echo lang('menu_examination_settings') ?></h5>
                               <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in updateModel.permissions.slice(36, 40)">
                                   <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                   <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                               </div>
                           </div> 
                       </div>
                       <hr/>
                       <div class="row">
                           <div class="col-md-3">
                               <h5 class="box-title m-b-0"><?php echo lang('lbl_online_examination') ?></h5>
                               <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in updateModel.permissions.slice(40, 47)">
                                   <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                   <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                               </div>
                           </div>
                           <div class="col-md-3">
                               <h5 class="box-title m-b-0"><?php echo lang('lbl-app-permission') ?></h5>
                               <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in updateModel.permissions.slice(47, 51)">
                                   <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                   <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                               </div>
                           </div>
                           <div class="col-md-3">
                               <h5 class="box-title m-b-0"><?php echo lang('forms_settings'); ?></h5>
                               <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in updateModel.permissions.slice(51, 55)">
                                   <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                   <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                               </div>
                           </div>
                           <div class="col-md-3">
                               <h5 class="box-title m-b-0"><?php echo lang('lbl_user_management'); ?></h5>
                               <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in updateModel.permissions.slice(55, 61)">
                                   <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                   <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                               </div>
                           </div> 
                       </div>
                       <hr/>
                       <div class="row">
                           <div class="col-md-3">
                               <h5 class="box-title m-b-0"><?php echo lang('lbl_trash'); ?></h5>
                               <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in updateModel.permissions.slice(61, 64)">
                                   <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                   <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                               </div>
                           </div>
                           <div class="col-md-3">
                               <h5 class="box-title m-b-0"><?php echo lang('lbl_fee'); ?></h5>
                               <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in updateModel.permissions.slice(64, 71)">
                                   <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                   <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                               </div>
                           </div>
                           <div class="col-md-3">
                               <h5 class="box-title m-b-0"><?php echo lang('lbl_payroll'); ?></h5>
                               <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in updateModel.permissions.slice(71, 78)">
                                   <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                   <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                               </div>
                           </div>
                           <div class="col-md-3">
                               <h5 class="box-title m-b-0"><?php echo lang('lbl_accounts'); ?></h5>
                               <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in updateModel.permissions.slice(78, 96)">
                                   <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                   <label style="font-size: 12px; font-weight: 100;" for="per{{p.permission}}"> {{p.label}} </label>
                               </div>
                           </div>
                          
                       </div>
                        </div>     
                    </div>
                        </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang("modal_btn_close"); ?></button>
                                <button type="submit" class="btn btn-primary waves-effect waves-light"><?php echo lang("modal_btn_update"); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
        
        <!-- End Model -->
        <!-- Page Content start here -->
        <!--.row-->
        <div id="alert_cat" style="display: none;" class="alert  alert-dismissable"><button class="close" aria-hidden="true" type="button" data-dismiss="alert">×</button></div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-wrapper collapse in" aria-expanded="true">
                        <div class="panel-body">

                            <ul class="nav customtab nav-tabs" role="tablist">
                                <li role="presentation" class="nav-item"><a href="#emp-dept" class="nav-link <?php if ($this->session->flashdata('hr_selected_tab') == "department") { echo 'active'; } else if ($this->session->flashdata("hr_selected_tab") == null) { echo 'active'; } ?>"
                                    aria-controls="profile" role="tab"
                                    data-toggle="tab" aria-expanded="false"><span
                                    class="visible-xs"><i class="ti-layout-accordion-merged"></i></span> <span
                                    class="hidden-xs"><?php echo lang('tab_employee_departments') ?></span></a></li>

                                    <li role="presentation" class="nav-item"><a href="#emp-cat" class="nav-link <?php if ($this->session->flashdata('hr_selected_tab') == "emp_categories") { echo 'active'; } ?>"
                                        aria-controls="profile" role="tab"
                                        data-toggle="tab"
                                        aria-expanded="true"><span
                                        class="visible-xs"><i class="ti-layout-media-right-alt"></i></span><span
                                        class="hidden-xs"><?php echo lang("tab_employee_categories"); ?></span></a>
                                    </li>
                                </ul>

                                <!--tab content start here-->

                                <div class="tab-content">
                                    <div class="tab-pane <?php if ($this->session->flashdata('hr_selected_tab') == "emp_categories") { echo 'active'; } ?>" id="emp-cat">
                                        <div class="hint"><?php echo lang('help_emp_categories'); ?></div>
                                        <div class="row">
                                            <div class="table-responsive col-md-12">
                                                <table id="myTable" class="table " ng-If="all_categories.length !== 0">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th><?php echo lang("lbl_department_name"); ?></th>
                                                            <th><?php echo lang("th_cat_name"); ?></th>
                                                            <th class="text-right "><?php echo lang("th_action"); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="categories">
                                                        <tr ng-repeat="cat in all_categories">
                                                            <td>{{$index+1}}</td>
                                                            <td>{{cat.department_name}}</td>
                                                            <td>{{cat.category}}</td>
                                                            <td class="text-right ">
                                                                <button type="button" class="btn btn-info btn-circle" data-toggle="modal" data-target="#edit-category" ng-click="getCategory(cat.rid)"><i class="fa fa-pencil"></i></button>
                                                                <button type="button" ng-click="removeCategory(cat.rid)" class="btn btn-danger btn-circle"><i class="fa  fa-trash-o"></i></button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                 <div class="col-md-12" ng-If="all_categories.length == 0">
                                                    <p class="text-danger"><?php echo lang("categories_not_exists"); ?></p>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <!--/row-->
                                            <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-category"><?php echo lang("btn_add_new_cat"); ?>
                                            </button>
                                        </div>

                                    </div>


                                    <div class="tab-pane <?php if ($this->session->flashdata('hr_selected_tab') == "department") { echo 'active'; } else if ($this->session->flashdata("hr_selected_tab") == null) { echo 'active'; }?>" id="emp-dept">
                                        <div class="hint"><?php echo lang('help_emp_departments'); ?></div>
                                        <div class="row">
                                            <div class="table-responsive col-md-12">
                                                <table id="myTable" class="table" ng-If="all_departments.length !== 0">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th><?php echo lang("lbl_department_name"); ?></th>
                                                            <th><?php echo lang("lbl_department_code"); ?></th>
                                                            <th class="text-right"><?php echo lang("lbl_action"); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr ng-repeat="dept in all_departments">
                                                            <td>{{$index+1}}</td>
                                                            <td>{{dept.name}}</td>
                                                            <td>{{dept.code}}</td>
                                                            <td class="text-right">
                                                                <button type="button" data-toggle="modal" data-target="#edit-department" ng-click="getDepartment(dept.id)" class="btn btn-info btn-circle"><i class="fa fa-pencil"></i></button>
                                                                <a href="javascript:void(0)" ng-click="deleteDepartment(dept.id)" class="sa-warning btn btn-danger btn-circle text-white"><i class="fa  fa-trash-o"></i></a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div class="col-md-12" ng-If="all_departments.length == 0">
                                                    <p class="text-danger"><?php echo lang("msg_department_not_exists"); ?></p>
                                                </div>
                                                       
                                            </div>
                                        </div>
                                        <div>
                                            <!--/row-->
                                            <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#responsive-modal" class="model_img img-responsive"><?php echo lang("lbl_add_new_department")?></button>
                                        </div>
                                    </div>


                                </div>


                                <!--tab content end here-->
                            </div>
                            <!--/panel body-->
                        </div>
                        <!--/panel wrapper-->
                    </div>
                    <!--/panel-->
                </div>
            </div>
        </div>
            <!--./row-->
            <!--page content end here-->
        </div>
        </div>
    </div>
    
    <!-- Category Modal Start 20-12-2017 By Shahzaib -->
    
<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>