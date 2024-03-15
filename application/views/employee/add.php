<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<style>
    .iti{
        width: 100% !important;
    }
</style>

<?php $site_language=$this->session->userdata('site_lang');
 if($site_language==" arabic " || $site_language=="arabic"){ ?>
 
<style>
    .iti__country-list
    {
        text-align: right !important;
    }
</style>

<?php } ?>

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
        <div class="hint"><?php echo lang('help_emp_add'); ?></div>
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

                    <!------------------ UI Image cropper ------------------->

                    <!-- sample modal content -->
                    <div id="myModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h4 class="modal-title" id="myModalLabel"><?php echo lang('image_cropper') ?></h4>
                                </div>

                                <div class="modal-body">
                                    <script src="https://cdn.jsdelivr.net/npm/exif-js"></script>
                                    <div ng-init="enableCrop=true;">
                                        <div class="container">
                                            <div class="row">
                                                <div data-ng-if="enableCrop" class="col-md-12 cropArea" data-ng-class="{'big':size=='big', 'medium':size=='medium', 'small':size=='small'}">
                                                    <ui-cropper
                                                        image="imageDataURI"
                                                        result-image="$parent.resImageDataURI"
                                                        result-blob="$parent.resBlob"
                                                        url-blob="$parent.urlBlob"
                                                        change-on-fly="changeOnFly"
                                                        area-type="{{type}}"
                                                        area-min-size="selMinSize"
                                                        area-init-size="selInitSize"
                                                        result-image-format="{{resImgFormat}}"
                                                        result-image-quality="resImgQuality"
                                                        result-image-size="resImgSize"
                                                        aspect-ratio="aspectRatio"
                                                        allow-crop-resize-on-corners="false"
                                                        disable-keyboard-access="false"
                                                        init-max-area="true"
                                                        chargement="'Testing Message'"
                                                        on-change="onChange($dataURI)"
                                                        on-load-begin="onLoadBegin()"
                                                        on-load-done="onLoadDone()"
                                                        on-load-error="onLoadError()"
                                                        live-view="blockingObject"
                                                        area-coords="myAreaCoords"
                                                        canvas-scalemode="true">
                                                    </ui-cropper>
                                                </div>

<!--                                                <div class="col-md-6">
                                                    <img style="float: right;" data-ng-src="{{resImageDataURI}}"/>
                                                </div>-->


                                                <div class="col-md-12">
                                                    <form data-ng-show="enableCrop" class="form-material">
                                                        <input type="file" id="fileInput" accept="image/*" class="form-control mb-2"/>
                                                    </form>

                                                    <a data-ng-click="callTestFuntion()" data-dismiss="modal" aria-hidden="true" class="btn btn-info mr-1"><i class=""></i><?php echo lang('crop') ?></a>
                                                    <a class="btn btn-success" data-dismiss="modal" aria-hidden="true"><i class=""></i><?php echo lang('lbl_close') ?></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

<!--                        <div class="row">
                                <div class="col-lg-6">
                                    <img data-ng-src="{{resImageDataURI}}"/>
                                </div>
                                <div class="col-lg-6">
                                    <img data-ng-src="{{urlBlob}}"/>
                                </div>
                            </div>  -->
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->


                    <!------------------ UI Image cropper ------------------->


                    <form name="empForm" class="form-material" id="empForm" ng-submit="onSubmit(empForm.$valid)" novalidate="">    

                        <!-- <span ng-show="areFieldsNotFilled">
                            <div class="alert alert-dismissable alert-danger"> 
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>     
                                <ul>    
                                    <li ng-repeat="(key,err) in empForm.$error.required">{{ err.$name }} field is required.</li>
                                </ul>
                            </div>
                        </span> -->

                        <h3 class="box-title"><?php echo lang('tab_personal_info') ?></h3>
                        <hr style="border-color: black;" />
                        <span>
                            <img style="width: 100px;height:100px" ng-show="resImageDataURI" ng-src="{{resImageDataURI}}"/>
                        </span>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?php echo lang('lbl_avatar') ?></label><br/>
                                    <button type="button" data-toggle="modal" data-target=".bs-example-modal-lg" class="btn btn-default"/><i class="fa fa-image"></i> <?php echo lang('choose_image') ?></button>
<!--                                    <input id="inputImage2" 
                                           type="file" 
                                           accept="image/*" 
                                           image="image2" 
                                           class="form-control"
                                           resize-max-height="300"
                                           resize-max-width="350"
                                           style="font-size: 12px;"
                                           resize-quality="0.7" />-->
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
                                    <input type="text" id="user-name" name="name" class="form-control" ng-model="formModel.name" required="">
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
                                    <label class="control-label"><?php echo lang('lbl_nationality') ?></label>
                                    
                                    <select ng-model="formModel.nationality" required="" class="form-control">
                                        <option value=""><?php echo lang('select_nationality') ?></option>
                                        <?php foreach ($countries as $country) { ?>
                                            <option value="<?php echo $country->id; ?>"><?php echo $country->country_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            
                            
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?php echo lang('lbl_language') ?></label>
                                    <select class="form-control" ng-model="formModel.language" required="" name="language">
                                        <option value=""><?php echo lang('please_language') ?></option>
                                        <option value="english">English</option>
                                         <option value="arabic">العَرَبِيَّة</option>
                                        
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
                                    <input type="email" id="user-email" name="email" ng-model="formModel.email" required="" class="form-control" ng-pattern="/^[a-z]+[a-z0-9._+]+@[a-z]+\.[a-z.]{2,5}$/">
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?php echo lang('lbl_dob') ?></label>
                                    <input type="text" name="dob" ng-model="formModel.dob" autocomplete="on" class="form-control mydatepicker-autoclose" ng-pattern="/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/">
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?php echo lang('lbl_password_employee') ?></label>
                                    <input type="password" id="password" placeholder="Password at least 8 characters" ng-model="formModel.password" pattern=".{8,32}" required="" name="password" class="form-control" autocomplete="new-password">
                                </div>
                            </div>
                            <!--/span-->
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?php echo lang('lbl_identity_card_number') ?></label>
                                    <input type="text" name="ic" ng-model="formModel.ic" pattern="^[0-9]+$" class="form-control" >
                                </div>
                            </div>
                            <!--/span-->

                        </div>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?php echo lang('lbl_passport_number') ?></label>
                                    <input type="text" name="passport" ng-model="formModel.passport" pattern="^[a-zA-Z0-9]+$" id="user-passport" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?php echo lang('lbl_fax') ?></label>
                                    <input type="text" id="lastName" pattern="^[0-9]+$" ng-model="formModel.fax" name="fax" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo lang('lbl_country') ?></label>
                                    <!--<select name="country" ng-model="formModel.country" required="" class="form-control countrySelect" onchange="getVal(this.value)">-->
                                    <select name="country" ng-model="formModel.country" required="" class="form-control countrySelect">
                                        <option value=""><?php echo lang('select_country') ?></option>
                                        <?php foreach ($countries as $country) { ?>
                                            <option value="<?php echo $country->country_code; ?>"><?php echo $country->country_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?php echo lang('lbl_contact_edit') ?></label>
                                    <div class="col-xs-12" id="remove_instance" style="margin-bottom: 20px; position: absolute; width: auto; ">
                                        <!--<input type="text" class="form-control" ng-model="formModel.contact" id="contact" pattern="^[+]*[0-9]{2,3}-/{0,1}[0-9]{2,3}-/{0,1}[0-9]{5,8}$" required="" name="contact">-->
                                        <input type="text" name="phone" class="form-control" ng-model="formModel.contact" id="phone">
                                    </div>
                                </div>
                            </div>

                            <!--/span-->
                        </div>
            
                        
                        <div class="row" style="display: none;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="parent_phone_code" name="parent_phone_code">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo lang('lbl_city') ?></label>                                             
                                    <input type="text" id="city" ng-model="formModel.city" pattern="^[a-zA-Z\u0600-\u06FF ]+$" required="" name="city" class="form-control">                                                
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label><?php echo lang('lbl_street') ?></label>
                                    <input type="text" name="street" ng-model="formModel.street" required="" class="form-control">
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <!--row-->
                        
                        <div class="row" style="display: none;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="school_country_code" id="school_country_code" value="<?php echo $school_country_code; ?>" class="form-control">
                                </div>
                            </div>
                        </div>

                        <h3 class="box-title"><?php echo lang('tab_professional_info') ?></h3>
                        <hr style="border-color: black;" />

                        <div class="row">
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group" id="afdepartments">
                                    <label class="control-label"><?php echo lang('title_department') ?></label>
                                    <select class="form-control" ng-model="formModel.department" ng-change="fetchCategories()" required="" name="department">
                                        <option value="">---<?php echo lang('select_department') ?>---</option>
                                        <?php
                                        if (count($departments) > 0) {
                                            foreach ($departments as $department) {
                                                ?>
                                                <option value="<?php echo encrypt($department->id); ?>"><?php echo $department->name; ?></option>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <option value=""><?php echo lang('no_department_exists') ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?php echo lang('title_category') ?></label>
                                    <select class="form-control" ng-model="formModel.category" id="categories" name="category" ng-change="getCategoryPermissions()">
                                        <option value=""><?php echo lang('select_category') ?></option>
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
                                    <input type="text" name="joining_date" ng-model="formModel.joining_date" required="" id="user-joining-date" class="form-control mydatepicker-autoclose">
                                    <!--<input type="Date" ng-model="formModel.join_date" required="" id="user-joining-date" name="join_date" class="form-control">-->
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?php echo lang('job_title') ?></label>
                                    <input type="text" name="job_title" ng-model="formModel.job_title" class="form-control" name="job_title">
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
                                    <input type="text" class="form-control" ng-model="formModel.experience_duration" required="" name="experience_duration">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label"><?php echo lang('lbl_experience_info') ?></label>
                                    <textarea class="form-control" rows="5" ng-model="formModel.experience_info" name="experience_info"> </textarea>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?php echo lang('emp_id') ?></label>
                                    <input type="text" class="form-control" ng-model="formModel.emp_id" name="employee_id">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?php echo lang('lbl_basic_salary');?></label>
                                    <input type="number" class="form-control" ng-model="formModel.basic_salary">
                                </div>
                            </div>
                        </div>
                        <!--/row-->

                        <h3 class="box-title"><?php echo lang('tab_permissions') ?></h3>
                        <hr style="border-color:black" /> 
                        <div class="row">
                            <!--{{permissions}}-->
                            <div class="col-md-3">
                                <h3 class="box-title m-b-0"><?php echo lang('lbl_students') ?></h3>
                                <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions') ?></p>
                                <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(0, 7)">
                                    <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">

                                    <label for="per{{p.permission}}"> {{p.label}} </label>
                                   
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h3 class="box-title m-b-0"><?php echo lang('crumb_parents') ?></h3>
                                <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions') ?></p>
                                <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(7, 11)">
                                    <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">

                                    <label for="per{{p.permission}}"> {{p.label}} </label>
                                   
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h3 class="box-title m-b-0"><?php echo lang('heading_all_employee') ?></h3>
                                <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions') ?></p>
                                <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(11, 16)">
                                    <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">

                                    <label for="per{{p.permission}}"> {{p.label}} </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h3 class="box-title m-b-0"><?php echo lang('menu_academic_settings') ?></h3>
                                <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions') ?></p>
                                <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(16, 26)">
                                    <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">

                                    <label for="per{{p.permission}}"> {{p.label}} </label>
                                </div>
                            </div>
                        </div>
                        <hr/>

                        <div class="row">
                            <!--{{permissions}}-->
                             <div class="col-md-3">
                                <h3 class="box-title m-b-0"><?php echo lang('syllabus') ?></h3>
                                <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions') ?></p>
                                <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(26, 30)">
                                    <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">

                                    <label for="per{{p.permission}}"> {{p.label}} </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h3 class="box-title m-b-0"><?php echo lang('profile_settings') ?></h3>
                                <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions') ?></p>
                                <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(30, 37)">
                                    <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                    <label for="per{{p.permission}}"> {{p.label}} </label>
                                </div>

                            </div>
                            <div class="col-md-3">
                                <h3 class="box-title m-b-0"><?php echo lang('lbl_evaluation') ?></h3>
                                <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions') ?></p>
                                <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(37, 43)">
                                    <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">

                                    <label for="per{{p.permission}}"> {{p.label}} </label>
                                </div>
                            </div>
                             <div class="col-md-3">
                               <h3 class="box-title m-b-0"><?php echo lang('menu_examination_settings') ?></h3>
                               <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions') ?></p>
                               <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(43, 47)">
                                   <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                   <label for="per{{p.permission}}"> {{p.label}} </label>
                               </div>
                           </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-md-3">
                               <h3 class="box-title m-b-0"><?php echo lang('lbl_online_examination') ?></h3>
                               <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions') ?></p>
                               <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(47, 54)">
                                   <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                   <label for="per{{p.permission}}"> {{p.label}} </label>
                               </div>
                           </div>
                           <div class="col-md-3">
                               <h3 class="box-title m-b-0"><?php echo lang('lbl-app-permission') ?></h3>
                               <p class="text-muted font-13 m-b-30"><?php echo "Permissions"//lang('tab_permissions') ?></p>
                               <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(54, 58)">
                                   <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                   <label for="per{{p.permission}}"> {{p.label}} </label>
                               </div>
                           </div>
                           <div class="col-md-3">
                               <h3 class="box-title m-b-0"><?php echo lang('forms_settings') ?></h3>
                               <p class="text-muted font-13 m-b-30"><?php echo "Permissions"//lang('tab_permissions') ?></p>
                               <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(58, 62)">
                                   <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                   <label for="per{{p.permission}}"> {{p.label}} </label>
                               </div>
                           </div>
                           <div class="col-md-3">
                               <h3 class="box-title m-b-0"><?php echo lang('lbl_user_management') ?></h3>
                               <p class="text-muted font-13 m-b-30"><?php echo "Permissions"//lang('tab_permissions') ?></p>
                               <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(62, 68)">
                                   <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                   <label for="per{{p.permission}}"> {{p.label}} </label>
                               </div>
                           </div>
                        </div>

                        <hr/>
                        <div class="row">
                            <div class="col-md-3">
                               <h3 class="box-title m-b-0"><?php echo lang('lbl_trash'); ?></h3>
                               <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions') ?></p>
                               <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(68, 71)">
                                   <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                   <label for="per{{p.permission}}"> {{p.label}} </label>
                               </div>
                           </div>
                           <div class="col-md-3">
                               <h3 class="box-title m-b-0"><?php echo lang('lbl_fee'); ?></h3>
                               <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions') ?></p>
                               <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(71, 78)">
                                   <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                   <label for="per{{p.permission}}"> {{p.label}} </label>
                               </div>
                           </div>
                           <div class="col-md-3">
                               <h3 class="box-title m-b-0"><?php echo lang('lbl_payroll'); ?></h3>
                               <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions') ?></p>
                               <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(78, 85)">
                                   <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                   <label for="per{{p.permission}}"> {{p.label}} </label>
                               </div>
                           </div>
                           <div class="col-md-3">
                               <h3 class="box-title m-b-0"><?php echo lang('lbl_accounts'); ?></h3>
                               <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions') ?></p>
                               <div class="form-group checkbox checkbox-info checkbox" style="margin-bottom: 15px; " ng-repeat="p in permissions.slice(85, 103)">
                                   <input ng-model="p.val" id="per{{p.permission}}" checked="" type="checkbox">
                                   <label for="per{{p.permission}}"> {{p.label}} </label>
                               </div>
                           </div>
                        </div>

                        <div class="form-actions pull-right">
                            <button type="reset" class="btn btn-default "><?php echo lang('btn_cancel') ?></button>
                            <button type="submit" ng-click="chkMissingFields()" class="btn btn-primary "><?php echo lang('btn_save') ?></button>


                        </div>
                        <div class="clear"></div>

                    </form>
                </div>
                <!-- /.row -->
            </div>
        </div>
        <!--page content end-->
    </div>
    <?php include(APPPATH . "views/inc/footer.php"); ?>
    

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js" integrity="sha512-DNeDhsl+FWnx5B1EQzsayHMyP6Xl/Mg+vcnFPXGNjUZrW28hQaa1+A4qL9M+AiOMmkAhKAWYHh1a+t6qxthzUw==" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" integrity="sha512-gxWow8Mo6q6pLa1XH/CcH8JyiSDEtiwJV78E+D+QP0EVasFs8wKXq16G8CLD4CJ2SnonHr4Lm/yY2fSI2+cbmw==" crossorigin="anonymous" />
    
<script>
    function getVal(value)
    {
        var globalval="";
        document.getElementById('remove_instance').textContent = ''; 
        document.getElementById('remove_instance').innerHTML = '<div onchange="getphone()"><input class="form-control" style="width:100%;" type="text" name="phone" id="phone" ng-model="formModel.contact" ></div>';
        globalval= value;
        var input = document.querySelector("#phone");
        var iti = window.intlTelInput(input, {
        initialCountry: globalval,
        separateDialCode:true,
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/utils.js",
        });
        console.log(iti);
    }
    function getphone()
    {
        // console.log(code+" "+phone);
    }
            
</script>


<script>
    $(document).ready(function () {
        var country_value3="";
        document.getElementById('remove_instance').textContent = '';
        document.getElementById('remove_instance').innerHTML = '<div onchange="getphone()"><input class="form-control" style="width:100%;" type="text" name="phone" id="phone" ng-model="formModel.contact" ></div>';
        
        var us_country=$('#school_country_code').val();
        
        globalval= us_country;
        var input = document.querySelector("#phone");
        var iti = window.intlTelInput(input, {
        initialCountry: globalval,
        separateDialCode:true,
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/utils.js",
        });
        console.log(iti);
        
        
        country_value3 = iti.getSelectedCountryData().iso2;
        $('#parent_phone_code').val(country_value3);
        
        input.addEventListener('countrychange', function(e) {
            country_value3 = iti.getSelectedCountryData().iso2;
            $('#parent_phone_code').val(country_value3);
        });
        
        
    });
</script>