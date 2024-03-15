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

<?php
$UserData = $this->session->userdata('userdata');
$role_id = $UserData['role_id'];

?>
<!-- Page Content -->
<div id="page-wrapper" ng-controller="empEditCtrl">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('heading_edit_employee') ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="#"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li><a href="#"><?php echo lang('crumb_employee') ?></a></li>
                    <li class="active"><?php echo lang('heading_edit_employee') ?></li>
                </ol>
            </div>
        </div>

        <!------------------ UI Image cropper ------------------->

        <!-- sample modal content -->
        <div id="myModal" class="modal fade bs-employee-edit-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

                                                                                        <a data-ng-click="callTestFuntion()" data-dismiss="modal" aria-hidden="true" class="btn btn-info mr-1"><i class=""></i> <?php echo lang('crop') ?></a>
                                                                                        <a class="btn btn-success" data-dismiss="modal" aria-hidden="true"><i class=""></i> <?php echo lang('lbl_close') ?></a>
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
                                                </div>	-->
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <!-- /.modal -->


                                    <!------------------ UI Image cropper ------------------->

                                    <?php
                                    $error = $this->session->flashdata('alert');
                                    if (!empty($error)) {
                                        ?>
                                        <div class="alert alert-dismissable <?php
                                        if ($this->session->flashdata('alert')['status'] == 'error') {
                                            echo 'alert-danger';
                                            } else {
                                                echo 'alert-success';
                                            }
                                            ?>"> 
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>     
                                            <?= $this->session->flashdata("alert")['message']; ?> 
                                        </div>
                                    <?php } ?>
                                    <!-- /.row -->
                                    <!-- Page Content -->
                                    <!-- .row -->
                                    
                                    <?php echo $this->session->flashdata('error-image'); ?>
                                    <div class="hint"><?php echo lang('help_emp_edit'); ?></div>
                                    <div class="row">

                                        <div class="col-md-12 col-xs-12">
                                            <div class="white-box">
                                                <ul class="nav customtab nav-tabs" role="tablist">
                                                    <li role="presentation" class="nav-item"><a href="#personal" class="nav-link <?php
                                                    if ($this->session->flashdata('emp_selected_tab') == "personal") {
                                                        echo 'active';
                                                        } else if ($this->session->flashdata("emp_selected_tab") == null) {
                                                            echo 'active';
                                                        }
                                                        ?>"
                                                        aria-controls="profile" role="tab"
                                                        data-toggle="tab" aria-expanded="true"><span
                                                        class="visible-xs"><i class="fa fa-user"></i></span><span class="hidden-xs"> <?php echo lang('tab_personal') ?></span></a>
                                                    </li>
                                                    <li role="presentation" class="nav-item"><a href="#professional" class="nav-link <?php
                                                    if ($this->session->flashdata('emp_selected_tab') == "perfessional") {
                                                        echo 'active';
                                                    }
                                                    ?>"
                                                    aria-controls="profile" role="tab"
                                                    data-toggle="tab" aria-expanded="false"><span
                                                    class="visible-xs"><i class="fa fa-graduation-cap"></i></span> <span
                                                    class="hidden-xs"><?php echo lang('tab_professional') ?></span></a></li>
                                                    <li role="presentation" class="nav-item"><a href="#permission" class="nav-link <?php
                                                    if ($this->session->flashdata('emp_selected_tab') == "permissions") {
                                                        echo 'active';
                                                    }
                                                    ?>"
                                                    aria-controls="profile" role="tab"
                                                    data-toggle="tab" aria-expanded="false"><span
                                                    class="visible-xs"><i class="fa fa-lock"></i></span> <span
                                                    class="hidden-xs"><?php echo lang('tab_permissions') ?></span></a></li>
                                                    <li role="presentation" class="nav-item"><a href="#banks" class="nav-link <?php
                                                    if ($this->session->flashdata('emp_selected_tab') == "banks") {
                                                        echo 'active';
                                                    }
                                                    ?>"
                                                    aria-controls="profile" role="tab"
                                                    data-toggle="tab" aria-expanded="false"><span
                                                    class="visible-xs"><i class="fa fa-university"></i></span> <span
                                                    class="hidden-xs"><?php echo lang('tab_banks') ?></span></a></li>
                                                    <li role="presentation" class="nav-item"><a href="#password_tab" class="nav-link <?php
                                                    if ($this->session->flashdata('emp_selected_tab') == "password") {
                                                        echo 'active';
                                                    }
                                                    ?>"
                                                    aria-controls="profile" role="tab"
                                                    data-toggle="tab" aria-expanded="false"><span
                                                    class="visible-xs"><i class="fa fa-key"></i></span> <span
                                                    class="hidden-xs"><?php echo lang('tab_password') ?></span></a></li>
                                                    <li role="presentation" class="nav-item"><a href="#attachments" class="nav-link"
                        aria-controls="profile" role="tab"
                        data-toggle="tab" aria-expanded="false"><span
                        class="visible-xs"><i class="fa fa-university"></i></span> <span
                        class="hidden-xs"><?php echo lang('lbl_attachments') ?></span></a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane <?php
                                                    if ($this->session->flashdata('emp_selected_tab') == "personal") {
                                                        echo 'active';
                                                        } else if ($this->session->flashdata("emp_selected_tab") == null) {
                                                            echo 'active';
                                                        }
                                                        ?>" id="personal">
                                                        <div class="hint"><?php echo lang('help_emp_edit_personal'); ?></div>

                                                        <form action="<?php echo site_url('employee/update'); ?>" method="post" class="form-material" id="settings employee_update_form_set" enctype="multipart/form-data">
                                                            <div class="form-body">

                                                                <input type="hidden" name="id" value="<?php echo $employee->id; ?>"/>

                                                                <span ng-show="!resImageDataURI">
                                                                    <img style="width: 100px; height: 100px;" src="uploads/user/<?php echo $employee->avatar; ?>"/>
                                                                </span>
                                                                <span ng-show="resImageDataURI">
                                                                    <img style="width: 100px;height:100px" ng-show="resImageDataURI" ng-src="{{resImageDataURI}}"/>
                                                                </span>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label"><?php echo lang('lbl_avatar') ?></label><br/>
                                                                            <button type="button" data-toggle="modal" data-target=".bs-employee-edit-modal-lg" class="btn btn-default"/><i class="fa fa-image"></i> <?php echo lang('choose_image') ?></button>
            <!--                                    <input id="inputImage2" 
                                                       type="file" 
                                                       accept="image/*" 
                                                       image="image2" 
                                                       class="form-control"
                                                       resize-max-height="300"
                                                       resize-max-width="350"
                                                       style="font-size: 12px;"
                                                       resize-quality="0.7" />-->
                                                       <input type="hidden"  class="form-control" name="avatar" value="<?php echo $employee->avatar; ?>">
                                                       <input type="hidden"  class="form-control" id="avatar" name="avatar2" value="<?php echo $employee->avatar; ?>">
                                                   </div>
                                               </div>
<!--                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_avatar') ?></label>
                                                <input type="file"  class="form-control" name="avatar" value="<?php echo $employee->avatar; ?>">
                                            </div>
                                        </div>-->
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_gender') ?></label>
                                                <select class="form-control" name="gender" required="">
                                                    <option value="male" <?php
                                                    if ($employee->gender == 'male') {
                                                        echo 'selected="selected"';
                                                    }
                                                    ?>><?php echo lang('option_male') ?></option>
                                                    <option value="female" <?php
                                                    if ($employee->gender == 'female') {
                                                        echo 'selected="selected"';
                                                    }
                                                    ?>><?php echo lang('option_female') ?></option>
                                                </select>

                                            </div>
                                        </div>
                                        <!--/span-->

                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_name') ?></label>
                                                <input type="text"  name="name" class="form-control" value="<?php echo $employee->name; ?>" pattern="^[a-zA-Z\u0600-\u06FF ]+$" required="">

                                            </div>

                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_marital_status') ?></label>
                                                <select class="form-control" name="marital_status" required="">
                                                    <option value="single" <?php
                                                    if ($employee->marital_status == 'single') {
                                                        echo 'selected="selected"';
                                                    }
                                                    ?>><?php echo lang('option_single') ?></option>
                                                    <option value="married" <?php
                                                    if ($employee->marital_status == 'married') {
                                                        echo 'selected="selected"';
                                                    }
                                                    ?>><?php echo lang('option_married') ?></option>

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

                                                <input type="email"  name="email" ng-pattern="/^[a-z]+[a-z0-9._+]+@[a-z]+\.[a-z.]{2,5}$/" class="form-control" value="<?php echo $employee->email; ?>" required="">


                                            </div>
                                        </div>
                                        
                                        <!--/span-->

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_language') ?></label>
                                                <select class="form-control" name="language" required="">
                                                    <option value="english" <?php
                                                    if ($employee->language == 'english') {
                                                        echo 'selected="selected"';
                                                    }
                                                    ?>>English</option>
                                                    <option value="arabic" <?php
                                                    if ($employee->language == 'arabic') {
                                                        echo 'selected="selected"';
                                                    }
                                                    ?>>العَرَبِيَّة</option>
                                                    

                                                </select>


                                            </div>

                                        </div>
                                        <!--/span-->
                                    </div>
                                    <!--/row-->
                                    <!--<div class="row">-->
                                        
                                        <!--/span-->

                                        
                                        <!--/span-->

                                    <!--</div>-->
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_nationality') ?></label>
                                                <select name="nationality" class="form-control" required="">
                                                    <option value=""><?php echo lang('select_nationality') ?></option>
                                                    <?php
                                                    if (count($countries) > 0) {
                                                        foreach ($countries as $country) {
                                                            ?>
                                                            <option value="<?php echo $country->id; ?>" <?php
                                                            if ($employee->nationality == $country->id) {
                                                                echo 'selected="selected"';
                                                            }
                                                            ?>><?php echo $country->country_name; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>   
                                                </select>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_passport_number') ?></label>

                                                <input pattern="^[a-zA-Z0-9]+$" name="passport_number" class="form-control" value="<?php echo $employee->passport_number; ?>"/>


                                            </div>

                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_fax') ?></label>
                                                <input type="number" name="fax" class="form-control" value="<?php echo $employee->fax; ?>"/>

                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_identity_card_number') ?></label>
                                                <input type="text" name="ic_number" class="form-control" value="<?php echo $employee->ic_number; ?>" />


                                            </div>
                                        </div>
                                        <!--/span-->

                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo lang('lbl_country') ?></label>
                                                <!--<select name="country" id="user_country" class="form-control" required="" onchange="getVal(this.value)">-->
                                                <select name="country" id="user_country" class="form-control" required="">
                                                    <option value=""><?php echo lang('select_country') ?></option>
                                                    <?php
                                                    if (count($countries) > 0) {
                                                        foreach ($countries as $country) {
                                                            ?>
                                                            <option value="<?php echo $country->country_code; ?>" <?php
                                                            if ($employee->country == $country->id) {
                                                                echo 'selected="selected"';
                                                            }
                                                            ?>><?php echo $country->country_name; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>   
                                                </select>
                                            </div>
                                        </div>

                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_contact') ?></label>
                                                <div class="col-xs-12" id="remove_parent_instance" style="margin-bottom: 20px; position: absolute; width: auto; ">
                                                    <!--<input type="text" class="form-control" name="contact" value="<?php echo $employee->contact; ?>" id="contact" pattern="^[+]*[0-9]{2,3}-/{0,1}[0-9]{2,3}-/{0,1}[0-9]{5,8}$" required="" />-->
                                                    <input type="text" class="form-control" name="contact" value="<?php echo $employee->contact; ?>" id="phone" />
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                  

                                    <div class="row" style="display: none;">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="parent_phone_code" id="parent_phone_code" value="<?php echo $employee->parent_phone_code; ?>" />
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label><?php echo lang('lbl_city') ?></label>
                                                <input type="text" name="city" class="form-control" value="<?php echo $employee->city; ?>" pattern="^[a-zA-Z\u0600-\u06FF ]+$" required="" />
                                            </div>
                                        </div>

                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                                <label><?php echo lang('lbl_street') ?></label>
                                                <input type="text" name="address" class="form-control" value="<?php echo $employee->address; ?>" pattern="^[a-zA-Z\u0600-\u06FF0-9 ]+$" required="" />
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_dob') ?></label>
                                                <input type="text" name="dob" class="dob-edit form-control mydatepicker-autoclose" value="<?php echo ($employee->dob=='0000-00-00')?"":to_html_date($employee->dob); ?>" pattern="^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$">
                                            </div>
                                        </div>
                                        
                                        <!--/span-->
                                        
                                        <?php if(get_acountant_dept_id() == login_user()->user->department_id || login_user()->user->role_id == ADMIN_ROLE_ID) { ?>
                                            <div class="col-md-6 ">
                                                <div class="form-group">
                                                    <label><?php echo lang('lbl_basic_salary') ?></label>
                                                    <input type="number" name="basic_salary" class="form-control" value="<?php echo $employee->basic_salary; ?>" required="" />
                                                </div>
                                            </div>
                                            <!--/span-->
                                        <?php } ?>

                                    </div>
                                    
                                    <div class="row" style="display: none;">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Phone</label>
                                                <input type="text" name="onlynumber" id="onlynumber" class="form-control" value="<?php echo $employee->u_phone_number; ?>" />
                                            </div>
                                        </div>
                                    </div>                                    
                                    
                                    <div class="row" style="display: none;">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Phone</label>
                                                <input type="text" name="emp_c_number" id="emp_c_number" class="form-control" value="<?php echo $employee->contact; ?>" /> 
                                            </div>
                                        </div>
                                    </div>

                                    <!--row-->
                                    <div >
                                        <button type="submit" id="employee_update_info" class="btn btn-primary pull-right"><?php echo lang('btn_update') ?></button>
                                        <div class="clear"></div>
                                    </div>
                                    <!--/row-->
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane <?php
                        if ($this->session->flashdata('emp_selected_tab') == "perfessional") {
                            echo 'active';
                        }
                        ?>" id="professional">
                        <div class="hint"><?php echo lang('help_emp_edit_professional'); ?></div>

                        <form action="<?php echo site_url('employee/update'); ?>" method="post" class="form-material" id="settings" enctype="multipart/form-data">
                            <div class="form-body">
                                <input type="hidden" name="id" value="<?php echo $employee->id; ?>"/>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php echo lang('title_department') ?></label>
                                            <select class="form-control" name="department_id" id="department" onchange="getCategories2($(this).val())">
                                                <?php
                                                if (count($departments) > 0) {
                                                    foreach ($departments as $department) {
                                                        ?>
                                                        <option value="<?php echo encrypt($department->id) ?>" <?php
                                                        if ($employee->department_id == $department->id) {
                                                            echo "selected";
                                                        }
                                                        ?>><?php echo $department->name; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php echo lang('title_category') ?></label>
                                            <select class="form-control" name="role_category_id" id="categories"></select>
                                        </div>
                                    </div>
                                        
                                    </div>

                                    <!--/row-->
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_joining_date') ?></label>
                                                <input type="text" name="joining_date" required="" value="<?php echo to_html_date($employee->joining_date); ?>" id="user-joining-date" class="form-control mydatepicker-autoclose"  pattern="^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$">
                                                <!--<input type="text" class="form-control" name="joining_date"  />-->
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('job_title') ?></label>
                                                <input type="text" class="form-control" name="job_title" value="<?php echo $employee->job_title; ?>" />
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <!--/row-->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_experience_duration') ?></label>
                                                <input type="text" class="form-control" name="experience_duration"  value="<?php echo $employee->experience_duration; ?>" required="" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_qualification') ?></label>
                                                <input type="text" class="form-control" name="qualification"  value="<?php echo $employee->qualification; ?>" required="" />
                                            </div>
                                        </div>
                                        <!--/span-->                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_experience_info') ?></label>
                                                <textarea class="form-control" name="experience_info"> <?php echo $employee->experience_info; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('emp_id') ?></label>
                                                <input type="text" class="form-control" name="rollno" value="<?php echo $employee->rollno; ?>">
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>

                                    <!--row-->
                                    <div>
                                        <button type="submit" class="btn btn-primary pull-right"><?php echo lang('btn_update') ?></button>
                                        <div class="clear"></div>
                                    </div>
                                    <!--/row-->

                                </div>

                            </form>

                        </div>
                        <div class="tab-pane <?php
                        if ($this->session->flashdata('emp_selected_tab') == "permissions") {
                            echo 'active';
                        }
                        ?>" id="permission">
                        <div class="hint"><?php echo lang('help_emp_edit_permissions'); ?></div>

                        <form class="form-material" method="post" action="employee/updatePermissions" id="settings">
                            <input type="hidden" name="employee_id" value="<?php echo $employee->id; ?>"/>
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php echo lang('lbl_students'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i <7){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id = "pe<?= $i; ?>" name="<?= $i; ?>"  type="checkbox" <?php 
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for = "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div>
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php echo lang('crumb_parents'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >=7 && $i <11 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id = "pe<?= $i; ?>" name="<?= $i; ?>"  type="checkbox" <?php 
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for = "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div>
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php echo lang('heading_all_employee'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >=11 && $i <16 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id = "pe<?= $i; ?>" name="<?= $i; ?>"  type="checkbox" <?php
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for = "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div>
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php echo lang('menu_academic_settings'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >=16 && $i <26 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id = "pe<?= $i; ?>" name="<?= $i; ?>"  type="checkbox" <?php
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for= "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div>

                                </div>
                                <hr/>
                                <div class="row">
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php echo lang('syllabus'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >=26 && $i <30 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id="pe<?= $i; ?>"  name="<?= $i; ?>" type="checkbox" <?php
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for= "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div>
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php echo lang('profile_settings'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >=30 && $i <37 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id= "pe<?= $i; ?>" name="<?= $i; ?>" type="checkbox" <?php
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for= "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }
                                        } ?> 
                                    </div>  
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php echo lang('lbl_evaluation'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >=37 && $i <43 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id= "pe<?= $i; ?>" name="<?= $i; ?>" type="checkbox" <?php
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for= "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div>
                                    
                                    <div class="col-md-3">
                                       <h3 class="box-title m-b-0"><?php echo lang('menu_examination_settings'); ?></h3>
                                       <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                       <?php
                                       $count = count($emp_permissions);
                                       for($i=0; $i< $count; $i++){
                                           if($i >=43 && $i <47 ){?>
                                             <div class="checkbox checkbox-info checkbox-circle">
                                               <input id= "pe<?= $i; ?>" name="<?= $i; ?>" type="checkbox" <?php
                                               if ($emp_permissions[$i]->val == "true") {
                                                   echo "checked";
                                               }
                                               ?>/>
                                               <label for= "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                           </div>
                                       <?php }} ?>
                                   </div>
 
                                </div>
                                <hr/>
                                <div class="row">
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php echo lang('lbl_online_examination'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >=47 && $i <54 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id="pe<?= $i; ?>"  name="<?= $i; ?>" type="checkbox" <?php
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for= "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div>
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php echo lang('lbl-app-permission'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >=54 && $i <58 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id="pe<?= $i; ?>"  name="<?= $i; ?>" type="checkbox" <?php
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for= "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div>
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php echo lang('forms_settings'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >=58 && $i <62 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id="pe<?= $i; ?>"  name="<?= $i; ?>" type="checkbox" <?php
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for= "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div>
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php echo lang('lbl_user_management'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >=62 && $i <68 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id="pe<?= $i; ?>"  name="<?= $i; ?>" type="checkbox" <?php
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for= "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div>
                                </div>

                                <hr/>
                                <div class="row">
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php echo lang("lbl_trash"); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >=68 && $i <71 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id="pe<?= $i; ?>"  name="<?= $i; ?>" type="checkbox" <?php
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for= "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div>
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php  echo lang('lbl_fee'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >=71 && $i <78 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id="pe<?= $i; ?>"  name="<?= $i; ?>" type="checkbox" <?php
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for= "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div>
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php  echo lang('lbl_payroll'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >=78 && $i <85 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id="pe<?= $i; ?>"  name="<?= $i; ?>" type="checkbox" <?php
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for= "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div>
                                    <div class="col-md-3">
                                        <h3 class="box-title m-b-0"><?php  echo lang('lbl_accounts'); ?></h3>
                                        <p class="text-muted font-13 m-b-30"><?php echo lang('tab_permissions'); ?></p>
                                        <?php 
                                        $count = count($emp_permissions); 
                                        for($i=0; $i< $count; $i++){
                                            if($i >= 85 && $i < 103 ){?>
                                              <div class="checkbox checkbox-info checkbox-circle">
                                                <input id="pe<?= $i; ?>"  name="<?= $i; ?>" type="checkbox" <?php
                                                if ($emp_permissions[$i]->val == "true") {
                                                    echo "checked";
                                                }
                                                ?>/>
                                                <label for= "pe<?= $i; ?>"> <?= lang($emp_permissions[$i]->permission); ?> </label>
                                            </div>   
                                        <?php }} ?> 
                                    </div>
                                    
                                   
                                </div>
                                <div >
                                    <button type="submit" class="btn btn-primary pull-right"><?php echo lang('btn_update'); ?></button>
                                    <div class="clear"></div>
                                </div>
                                <!--/row-->

                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="banks">
                        <div class="hint"><?php echo lang('help_emp_edit_banks'); ?></div>
                        <?php include(APPPATH . "views/employee/banks.php"); ?>                           
                    </div>
                    <div class="tab-pane" id="password_tab">
                        <div class="hint"><?php echo lang('help_emp_edit_password'); ?></div>

                        <div class="alert alert-success" id="change_alert" style="display: none"><p id="alert_msg"></p></div>
                        <form class="form-material" id="change_password">
                            <div class="form-body">

                                    <!--/row-->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="hidden" name="emp_id" value="<?php echo $employee->id; ?>">
                                                <label><?php echo lang('new_password') ?></label>
                                                <input type="password" name="password" id="new_password" placeholder="" required="" class="form-control"> </div>
                                            </div>

                                        </div>
                                        <!--/row-->

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo lang('confirm_password') ?></label>
                                                    <input type="password" name="confirm_password" id="confirm_password" required="" placeholder="" class="form-control"> </div>
                                                </div>

                                            </div>
                                            <!--/row-->
                                            <div>
                                                <button type="submit" class="btn-1d  fcbtn btn  btn-primary btn-1b pull-right" id="change_password_btn"><?php echo lang('update_pswrd_btn') ?></button>
                                                <div class="clear"></div>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane" id="attachments">
                                    <div class="hint"><?php echo lang('help_emp_attach'); ?></div>
                                    
                                    <?php echo $attachments ; ?>                         
                                </div>


                            </div>

                        </div>
                    </div>
                </div>
                <!--page content end-->
            </div>
            <!-- /.container-fluid -->
            <?php include(APPPATH . "views/inc/footer.php"); ?>
            
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js" integrity="sha512-DNeDhsl+FWnx5B1EQzsayHMyP6Xl/Mg+vcnFPXGNjUZrW28hQaa1+A4qL9M+AiOMmkAhKAWYHh1a+t6qxthzUw==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" integrity="sha512-gxWow8Mo6q6pLa1XH/CcH8JyiSDEtiwJV78E+D+QP0EVasFs8wKXq16G8CLD4CJ2SnonHr4Lm/yY2fSI2+cbmw==" crossorigin="anonymous" />
    
<script>
    function getVal(value)
    {
        var globalval="";
        document.getElementById('remove_parent_instance').textContent = '';
        document.getElementById('remove_parent_instance').innerHTML = '<div onchange="getphone()"><input class="form-control" style="width:100%;" type="text" name="contact" id="phone"></div>';
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
                    document.getElementById('remove_parent_instance').textContent = '';
                    document.getElementById('remove_parent_instance').innerHTML = '<input class="form-control" style="width:100%;" type="text" name="contact" id="phone" >';
                    
                    var us_country=$('#parent_phone_code').val();
                    var complete_phone=$('#emp_c_number').val();
                    
                    
                    globalval= us_country;
                    var input = document.querySelector("#phone");
                    var iti = window.intlTelInput(input, {
                    initialCountry: globalval,
                    separateDialCode:true,
                    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/utils.js",
                    });
                    console.log(iti);
                    
                    var on_number=$('#onlynumber').val();
                    
                    $("#phone").val(on_number);
                    
                    
                    country_value3 = iti.getSelectedCountryData().iso2;
                    $('#parent_phone_code').val(country_value3);
                    
                    var dial_selectedCode = iti.getSelectedCountryData().dialCode;
                    
                    if(complete_phone!='')
                    {
                        var array_comPhone=complete_phone.split('+');
                        if(array_comPhone[1])
                        {
                            console.log('here in if');
                            console.log(complete_phone);
                            console.log(array_comPhone);
                            return false;
                            var my_phoneN=array_comPhone[1];
                            var onenumbers=my_phoneN.substring(0, 1);
                            var twonumbers=my_phoneN.substring(0, 2);
                            var threenumbers=my_phoneN.substring(0, 3);
                            if(dial_selectedCode==twonumbers)
                            {
                                var new_assig2phone=my_phoneN.split(dial_selectedCode);
                                if(new_assig2phone)
                                {
                                    $("#phone").val(new_assig2phone[1]);
                                }
                            }
                            else if(dial_selectedCode==threenumbers)
                            {
                                var new_assig3phone=my_phoneN.split(dial_selectedCode);
                                if(new_assig3phone)
                                {
                                    $("#phone").val(new_assig3phone[1]);
                                }
                            }
                            else if(dial_selectedCode==onenumbers)
                            {
                                var new_assig1phone=my_phoneN.split(dial_selectedCode);
                                if(new_assig1phone)
                                {
                                    $("#phone").val(new_assig1phone[1]);
                                }
                            }
                        }
                        else
                        {
                            var my_phoneM=complete_phone;
                            
                            var onemnumbers=my_phoneM.substring(0, 1);
                            var twomnumbers=my_phoneM.substring(0, 2);
                            var threemnumbers=my_phoneM.substring(0, 3);
                            if(dial_selectedCode==twomnumbers)
                            {
                                var new_assig2phone=my_phoneM.split(dial_selectedCode);
                                if(new_assig2phone)
                                {
                                    $("#phone").val(new_assig2phone[1]);
                                }
                            }
                            else if(dial_selectedCode==threemnumbers)
                            {
                                var new_assig3phone=my_phoneM.split(dial_selectedCode);
                                if(new_assig3phone)
                                {
                                    $("#phone").val(new_assig3phone[1]);
                                }
                            }
                            else if(dial_selectedCode==onemnumbers)
                            {
                                var new_assig1phone=my_phoneM.split(dial_selectedCode);
                                if(new_assig1phone)
                                {
                                    $("#phone").val(new_assig1phone[1]);
                                }
                            }
                            
                        }
                    }
                    
                    
                    
                    
                    
                    
                    
                    input.addEventListener('countrychange', function(e) {
                        country_value3 = iti.getSelectedCountryData().iso2;
                        $('#parent_phone_code').val(country_value3);
                    });
                    
                    
                    
                    getCategories($("#department").val());
                });
                function getCategories(department) {
                    Loading("#categories", '<?php echo lang("loading_datatable"); ?>', "", "show");
                    $.post('<?php echo site_url("employee/getCategories"); ?>', {department: department}).done(function (res) {
                        Loading("#categories", '<?php echo lang("loading_datatable"); ?>', "", "hide");
                        $("select[name='role_category_id']").html(res);
                        $("select[name='role_category_id'] option[value='all']").remove();
                        $("select[name='role_category_id']").val('<?php echo encrypt($employee->role_category_id); ?>').change();
                    });
                }
                function getCategories2(department) {
                    Loading("#categories", '<?php echo lang("loading_datatable"); ?>', "", "show");
                    $.post('<?php echo site_url("employee/getCategories"); ?>', {department: department}).done(function (res) {
                        Loading("#categories", '<?php echo lang("loading_datatable"); ?>', "", "hide");
                        $("select[name='role_category_id']").html(res);
                        $("select[name='role_category_id'] option[value='all']").remove();
                    });
                }
            </script>
            
<script>
    
$( "#employee_update_info" ).click(function() {
    var cntry_code = $(".iti__selected-dial-code").text();
    var cntry_phone1 = $("#phone").val();
    var final_countryPhone = cntry_code+cntry_phone1;
    
    $('#onlynumber').val(final_countryPhone);
    
  $( "#employee_update_form_set" ).submit();
});
    
    
</script>


            <script id="new_bank_template" type="text/template">
                <tr>
                    <td> {{bank_name}} </td>
                    <td> {{account_number}}</td>
                    <td> {{beneficiary_name}}</td>
                    <td> {{swift_code}} </td>
                    <td> {{iban_code}} </td>
                    <td class='text-center'><i class='fa {{primary_class}}' id='primary'></i> </td>
                    <td class='text-right'><button type='button' class='btn btn-info btn-circle editBank' data-toggle='modal' data-target='#editBankModal' id={{bank_id}}><i class='fa fa-pencil'></i></button><button type='button' class='btn btn-danger btn-circle deleteBank' data-toggle='modal' data-target='#deleteBankModal' id={{bank_id}}><i class='fa  fa-trash-o'></i></button></td>
                </tr>
            </script>

            <script id="updated_bank_template" type="text/template">

                <td> {{bank_name}} </td>
                <td> {{account_number}}</td>
                <td> {{beneficiary_name}}</td>
                <td> {{swift_code}} </td>
                <td> {{iban_code}} </td>
                <td class='text-center'><i class='fa {{primary_class}}' id='primary'></i> </td>
                <td class='text-right'><button type='button' class='btn btn-info btn-circle editBank' data-toggle='modal' data-target='#editBankModal' id={{bank_id}}><i class='fa fa-pencil'></i></button><button type='button' class='btn btn-danger btn-circle deleteBank' data-toggle='modal' data-target='#deleteBankModal' id={{bank_id}}><i class='fa  fa-trash-o'></i></button></td>

            </script>

            <!--// Mustache Templates -->
            <script type="text/javascript">
                $(document).ready(function () {

                    $('#addnewbank').click(function () {

                        $(this).attr("disabled", "disabled");
                        Loading("#bank_modal_content", '<?php echo lang("loading_datatable"); ?>', "", "show");

                        var formdata = $('#newbankform').serialize();
                        $.ajax({
                            type: 'POST',
                            data: formdata,
                            dataType: "json",
                            url: '<?php echo site_url('profile/addNewBank/') ?>',
                            success: function (response) {
                                console.log(response);

                                if (response.success) {
                                    Loading("#bank_modal_content", "", "", "hide");
                                    showNotification("Success", response.success_message, "success");
                                    $('#addBank').modal('toggle');
                                    $('#primary_bank').removeAttr('checked');
                                    $('#name').val('');
                                    $('#accountno').val('');
                                    $('#beneficiary').val('');
                                    $('#iban').val('');
                                    $('#swift').val('');

                                    $('#addnewbank').removeAttr("disabled");

                                    var name = response.data.bank_name;
                                    var account_number = response.data.account_number;
                                    var beneficiary_name = response.data.beneficiary_name;
                                    var swift_code = response.data.swift_code;
                                    var iban_code = response.data.iban_code;
                                    var is_primary = response.data.is_primary;
                                    var id = response.data.id;

                                    if (is_primary == "Y") {
                                        $('td i').each(function () {
                                            $(this).removeClass('fa-check');
                                        });

                                        var template = $("#new_bank_template").html();

                                        var dynamichtml = Mustache.render(template, {
                                            bank_name: name,
                                            account_number: account_number,
                                            beneficiary_name: beneficiary_name,
                                            swift_code: swift_code,
                                            iban_code: iban_code,
                                            primary_class: "fa-check",
                                            bank_id: id
                                        });

                                        $('#myTable tbody').append(dynamichtml);

                                    } else {
                                        var template = $("#new_bank_template").html();

                                        var dynamichtml = Mustache.render(template, {
                                            bank_name: name,
                                            account_number: account_number,
                                            beneficiary_name: beneficiary_name,
                                            swift_code: swift_code,
                                            iban_code: iban_code,
                                            primary_class: "",
                                            bank_id: id
                                        });

                                        $('#myTable tbody').append(dynamichtml);
                                    }




                                } else {
                                    Loading("#bank_modal_content", "", "", "hide");
                                    $('#addnewbank').removeAttr("disabled");
                                    $('#bank_add_alert').removeClass('alert-success').addClass('alert-danger').html(response.error).show();


                                }




                            }


                        });




                    })

$('table').on('click', 'button.editBank', function () {
    Loading("body", '<?php echo lang("loading_datatable"); ?>', "", "show");
    $('#bank_delete_alert').hide();
    $('#bank_add_alert').hide();
    $('#bank_add_alert_outside').hide();
    $('#bank_edit_alert').hide();
    $('#bank_update_alert_outside').hide();
    var id = $(this).attr('id');
    $('#editprimary_bank').removeAttr('checked');
    $('#editname').val('');
    $('#editaccountno').val('');
    $('#editbeneficiary').val('');
    $('#editiban').val('');
    $('#editswift').val('');
    $('#bank_edit_id').val(id);
    $.ajax({

        type: 'POST',
        data: {id: id},
        dataType: "json",
        url: '<?php echo site_url('profile/getBankDetails/') ?>',
        success: function (response) {
            console.log(response);
            if (response.success) {

                var name = response.data.bank_name;
                var account_number = response.data.account_number;
                var beneficiary_name = response.data.beneficiary_name;
                var swift_code = response.data.swift_code;
                var iban_code = response.data.iban_code;
                var is_primary = response.data.is_primary;
                var id = response.data.id;


                $('#bank_edit_id').val(response.data.id);
                if (is_primary == "Y") {
                    $('#editprimary_bank').prop('checked', true);
                } else {
                    $('#editprimary_bank').prop('checked', false);
                }

                $('#editname').val(name);
                $('#editaccountno').val(account_number);
                $('#editbeneficiary').val(beneficiary_name);
                $('#editiban').val(iban_code);
                $('#editswift').val(swift_code);



            }

            Loading("body", '<?php echo lang("loading_datatable"); ?>', "", "hide");
        }

    })
})

$('#updatebank').click(function () {
    $(this).attr("disabled", "disabled");
    Loading("#bank-model-content", '<?php echo lang("loading_datatable"); ?>', "", "show");
    var formdata = $('#editbankform').serialize();
    $.ajax({
        type: 'POST',
        data: formdata,
        dataType: "json",
        url: '<?php echo site_url('profile/updateBank/') ?>',
        success: function (response) {
            console.log(response);

            if (response.success) {
                Loading("#bank-model-content", "", "", "hide");
                showNotification("Success", response.success_message, "success");
                $('#editBankModal').modal('hide');
                $('#editprimary_bank').removeAttr('checked');
                $('#editname').val('');
                $('#editaccountno').val('');
                $('#editbeneficiary').val('');
                $('#editiban').val('');
                $('#editswift').val('');

                $('#updatebank').removeAttr("disabled");

                var name = response.data.bank_name;
                var account_number = response.data.account_number;
                var beneficiary_name = response.data.beneficiary_name;
                var swift_code = response.data.swift_code;
                var iban_code = response.data.iban_code;
                var is_primary = response.data.is_primary;
                var id = response.data.id;




                var updatedRow = $('#' + id).parents('tr');
                if (is_primary == "Y") {

                    $('td i').each(function () {
                        $(this).removeClass('fa-check');
                    });

                    var template = $("#updated_bank_template").html();

                    var dynamichtml = Mustache.render(template, {
                        bank_name: name,
                        account_number: account_number,
                        beneficiary_name: beneficiary_name,
                        swift_code: swift_code,
                        iban_code: iban_code,
                        primary_class: "fa-check",
                        bank_id: id
                    });

                    updatedRow.html(dynamichtml);

                } else {
                    var template = $("#updated_bank_template").html();

                    var dynamichtml = Mustache.render(template, {
                        bank_name: name,
                        account_number: account_number,
                        beneficiary_name: beneficiary_name,
                        swift_code: swift_code,
                        iban_code: iban_code,
                        primary_class: "",
                        bank_id: id
                    });

                    updatedRow.html(dynamichtml);
                }

            } else {
                Loading("#bank-model-content", "", "", "hide");
                $('#bank_edit_alert').removeClass('alert-success').addClass('alert-danger').html(response.error).show();
                $('#updatebank').removeAttr("disabled");

            }
        }
    })
})

$('#add_bank_btn').click(function () {
    $('#bank_delete_alert').hide();
    $('#bank_add_alert').hide();
    $('#bank_add_alert_outside').hide();
    $('#bank_edit_alert').hide();
    $('#bank_update_alert_outside').hide();
    $('#primary_bank').removeAttr('checked');
    $('#name').val('');
    $('#accountno').val('');
    $('#beneficiary').val('');
    $('#iban').val('');
    $('#swift').val('');
})
$('#close_add_bank').click(function () {
    $('#bank_add_alert').hide();
});

$('#change_password_btn').click(function () {
    $('change_alert').hide();
    Loading("#password_tab", '<?php echo lang("loading_datatable"); ?>', "", "show");
    event.preventDefault();
    var formdata = $('#change_password').serialize();
    $.ajax({
        type: 'POST',
        data: formdata,
        dataType: "json",
        url: '<?php echo site_url('employee/changePassword/') ?>',
        success: function (response) {
            console.log(response);

            if (response.success) {
                Loading("#password_tab", '<?php echo lang("loading_datatable"); ?>', "", "hide");
                $('#change_alert').removeClass('alert-danger').addClass('alert-success').show();
                $('#alert_msg').html("<?php echo lang('pswrd_changed_success') ?>");

               
                $('#new_password').val('');
                $('#confirm_password').val('');



            } else {
                Loading("#password_tab", '<?php echo lang("loading_datatable"); ?>', "", "hide");
                $('#change_alert').removeClass('alert-success').addClass('alert-danger').show();

                $('#alert_msg').html(response.error);
            }

        }
    });
});




$('table').on('click', 'button.deleteBank', function () {
    $('#bank_delete_alert').hide();
    $('#bank_add_alert_outside').hide();
    $('#bank_edit_alert').hide();
    $('#bank_update_alert_outside').hide();
    var id = $(this).attr('id');
    $('#bank_delete_id').val(id);

});




$('#confirm_delete').click(function () {
    Loading("#deleteBankModal", '<?php echo lang("loading_datatable"); ?>', "", "show");
    var tr_id = '#' + $('#bank_delete_id').val();

    $.ajax({
        type: 'POST',
        data: {id: $('#bank_delete_id').val()},
        dataType: "json",
        url: '<?php echo site_url('profile/deleteBank/') ?>',
        success: function (response) {
            console.log(response);
            if (response.success) {
                $(tr_id).parents('tr').remove();
                Loading("#deleteBankModal", "Loading", "", "hide");
                $('#deleteBankModal').modal('toggle');
                showNotification("Success", response.success_message, "success");

            } else {
                $('#bank_delete_alert').removeClass('alert-success').addClass('alert-danger').html(response.error).show();


            }

            if (response.primary == "Y") {
                $('tbody tr:first #primary').addClass('fa-check');
            }

        }
    })
});
})
</script>
