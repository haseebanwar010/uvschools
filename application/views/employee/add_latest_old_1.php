<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<!-- Page Content -->
<div id="page-wrapper" ng-controller="employeeCtrl">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('crumb_add_employee') ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="#"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li><a href="#"><?= lang('crumb_employee') ?></a></li>
                    <li class="active"><?php echo lang('crumb_add_employee') ?></li>

                </ol>
            </div>
        </div>
        
        <!-- /.row -->
        <div class="alert alert-dismissable {{alert.type}}" ng-if="alert.message"> 
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>     
            {{ alert.message }}
        </div>
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel-group wiz-aco form-material " id="accordion" role="tablist" aria-multiselectable="true">
                    <form name="empForm" id="empForm" ng-submit="onSubmit(empForm.$valid, image2.resized)" novalidate="">    
                        <span ng-show="areFieldsNotFilled">
                            <div class="alert alert-dismissable alert-danger"> 
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>     
                                <ul>    
                                    <li ng-repeat="(key,err) in empForm.$error.required">{{ err.$name }} field is required.</li>
                                </ul>
                            </div>
                        </span>
                        
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <?php echo lang('tab_personal_info') ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                <div class="panel-body">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_avatar') ?></label>
                                                <input id="inputImage2" 
                                                   type="file" 
                                                   accept="image/*" 
                                                   image="image2" 
                                                   class="form-control"
                                                   resize-max-height="300"
                                                   resize-max-width="350"
                                                   style="font-size: 12px;"
                                                   resize-quality="0.7" />
                                                   <span>
                                                       <img style="width: auto;" ng-show="image2" ng-src="{{image2.resized.dataURL}}"/>
                                                   </span>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_gender') ?></label>
                                                <select class="form-control" ng-model="formModel.gender" required="" name="gender">
                                                    <option value=""><?php echo lang('please_gender') ?></option>
                                                    <option value="male"><?php echo lang('option_male') ?></option>
                                                    <option value="female"><?php echo lang('option_female') ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_name_employee') ?></label>
                                                <input type="text" id="user-name" name="name" class="form-control" ng-model="formModel.name" required="" placeholder="John doe">
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_marital_status') ?></label>
                                                <select class="form-control" ng-model="formModel.marital_status" required="" name="marital_status">
                                                    <option value=""><?php echo lang('please_marital') ?></option>
                                                    <option value="married"><?php echo lang('option_married') ?></option>
                                                    <option value="single"><?php echo lang('option_single') ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <!--/row-->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_contact_edit') ?></label>
                                                <input type="number" class="form-control" ng-model="formModel.contact" pattern="^[0-9]+$"  required="" name="contact">
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_language') ?></label>
                                                <select class="form-control" ng-model="formModel.language" required="" name="language">
                                                    <option value=""><?php echo lang('please_language') ?></option>
                                                    <option value="english">English</option>
                                                    <option value="arabic">Arabic</option>
                                                    <option value="dutch">Dutch</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <!--/row-->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('plc_email') ?></label>
                                                <input type="email" id="user-email" name="email" ng-model="formModel.email" required="" class="form-control" placeholder="someone@xyz.com">
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_dob') ?></label>
                                                <input type="text" name="dob" ng-model="formModel.dob" class="form-control mydatepicker-autoclose" placeholder="mm/dd/yyyy">
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_password_employee') ?></label>
                                                <input type="password" id="password" ng-model="formModel.password" pattern=".{8,32}" required="" name="password" class="form-control">
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_nationality') ?></label>
                                                <input type="text" id="nationality" ng-model="formModel.nationality" pattern="^[a-zA-Z]+$" required="" name="nationality" class="form-control">
                                            </div>
                                        </div>
                                        <!--/span-->

                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_identity_card_number') ?></label>
                                                <input type="text" name="ic" ng-model="formModel.ic" required="" pattern="^[0-9]+$" class="form-control" placeholder="34602-7625745-8">
                                            </div>
                                        </div>
                                        <!--/span-->

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_passport_number') ?></label>
                                                <input type="text" name="passport" ng-model="formModel.passport" required="" pattern="^[a-zA-Z0-9]+$" id="user-passport" class="form-control" placeholder="PAK456789">
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                                <label><?php echo lang('lbl_street') ?></label>
                                                <input type="text" name="street" ng-model="formModel.street" required="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_fax') ?></label>
                                                <input type="number" id="lastName" ng-model="formModel.fax" required="" name="fax" class="form-control">
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo lang('lbl_country') ?></label>
                                                <select name="country" ng-model="formModel.country" required="" class="form-control">
                                                    <option>Select your country</option>
                                                    <?php foreach ($countries as $country) { ?>
                                                        <option value="<?php echo $country->country_name; ?>"><?php echo $country->country_name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo lang('lbl_city') ?></label>
                                                <input type="text" id="city" ng-model="formModel.city" pattern="^[a-zA-Z]+$" required="" name="city" class="form-control">                                                
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <!--row-->
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingTwo">
                                    <h4 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            <?php echo lang('tab_professional_info') ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                    <div class="panel-body">
                                        <div class="row">
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group" id="afdepartments">
                                                    <label class="control-label"><?php echo lang('title_department') ?></label>
                                                    <select class="form-control" ng-model="formModel.department" ng-change="fetchCategories()" required="" name="department">
                                                        <?php if(count($departments) > 0) { foreach($departments as $department) { ?>
                                                        <option value="<?php echo encrypt($department->id); ?>"><?php echo $department->name; ?></option>
                                                        <?php } } else { ?>
                                                        <option value="">Not exists any department</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('title_category') ?></label>
                                                    <select class="form-control" ng-model="formModel.category" id="categories" name="category">
                                                        <option value="0">Select a category</option>
                                                        <option ng-repeat="cat in categories" value="{{cat.id}}">{{cat.category}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_joining_date') ?></label>
                                                    <input type="text" name="joining_date" ng-model="formModel.joining_date" required="" id="user-joining-date" class="form-control mydatepicker-autoclose" placeholder="mm/dd/yyyy">
                                                    <!--<input type="Date" ng-model="formModel.join_date" required="" id="user-joining-date" name="join_date" class="form-control">-->
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('job_title') ?></label>
                                                    <input type="text" name="job_title" ng-model="formModel.job_title" required="" class="form-control" name="job_title">
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_qualification') ?></label>
                                                    <input type="text" name="qualification" ng-model="formModel.qualification" required="" class="form-control" name="qualification">
                                                </div>
                                            </div>
                                            <!--/span--> 
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_experience_duration') ?></label>
                                                    <textarea class="form-control" ng-model="formModel.experience_duration" required="" name="experience_duration"> </textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_experience_info') ?></label>
                                                    <textarea class="form-control" rows="5" ng-model="formModel.experience_info" required="" name="experience_info"> </textarea>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingThree">
                                        <h4 class="panel-title">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                <?php echo lang('tab_permissions') ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                        <div class="panel-body">
                                        <div class="row">
                                               <div class="col-md-3">
                                                   <h3 class="box-title m-b-0">Checkbox Square</h3>
                                                   <p class="text-muted font-13 m-b-30"> Bootstrap brand colors </p>
                                                   <div class="form-group checkbox checkbox-info checkbox-circle" style="margin-bottom: 15px;" ng-repeat="p in permissions">
                                                       <input ng-model="p.val" type="checkbox">
                                                       <label> {{p.label}} </label>
                                                   </div>
                                               </div>
                                               <div class="col-md-3">
                                                   <h3 class="box-title m-b-0">Checkbox Square</h3>
                                                   <p class="text-muted font-13 m-b-30"> Bootstrap brand colors </p>
                                               </div>
                                               <div class="col-md-3">
                                                   <h3 class="box-title m-b-0">Checkbox Square</h3>
                                                   <p class="text-muted font-13 m-b-30"> Bootstrap brand colors </p>
                                               </div>

                                               <div class="col-md-3">
                                                   <h3 class="box-title m-b-0">Checkbox Square</h3>
                                                   <p class="text-muted font-13 m-b-30"> Bootstrap brand colors </p>
                                               </div>
                                           </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.row -->
            </div>
        </div>
        <!--page content end-->
    </div>
<?php include(APPPATH . "views/inc/footer.php"); ?>