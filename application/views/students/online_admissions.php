<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js" integrity="sha512-DNeDhsl+FWnx5B1EQzsayHMyP6Xl/Mg+vcnFPXGNjUZrW28hQaa1+A4qL9M+AiOMmkAhKAWYHh1a+t6qxthzUw==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" integrity="sha512-gxWow8Mo6q6pLa1XH/CcH8JyiSDEtiwJV78E+D+QP0EVasFs8wKXq16G8CLD4CJ2SnonHr4Lm/yY2fSI2+cbmw==" crossorigin="anonymous" />
<style>
    .overlay {
        height: 100%;
        width: 0;
        position: fixed; 
        z-index: 1000;
        top: 0;
        left: 0;
        background-color: rgb(0,0,0);
        background-color: rgba(0,0,0, 0.9);
        overflow-x: hidden;
        transition: 0.5s;
    }

    .overlay-content {
        position: relative;
        top: 10%;
        width: 100%;
        text-align: center;
        margin-top: 0;
    }

    .overlay a {
        padding: 8px;
        text-decoration: none;
        font-size: 36px;
        color: #818181;
        display: block;
        transition: 0.3s;
    }

    .overlay a:hover, .overlay a:focus {
        color: #f1f1f1;
    }

    .overlay .closebtn {
        position: absolute;
        top: 20px;
        right: 45px;
        font-size: 60px;
    }

    .overlay .printAllBtn {
        position: absolute;
        top: 20px;
        left:45px;
    }

    .result_card_container {
        background: white;
        width: 60%;
        height: auto;
        padding: 25px;
    }

    @media screen and (max-height: 450px) {
        .overlay a {font-size: 20px}
        .overlay .closebtn {
            font-size: 40px;
            top: 15px;
            right: 35px;
        }
    }
    @media screen and (max-width: 768px) {
        .overlay .closebtn{top:0px;}
        .result_card_container{width: 80%; margin-left: 10%; padding: 25px;}
    }
    @media screen and (max-width: 375px) {
        .overlay .closebtn{top:0px;}
        .result_card_container{width: 100%; margin-left: 0px; padding: 25px;}
    }

    @media screen and (max-width: 414px) {
        .overlay .closebtn{top:0px;}
        .result_card_container{width: 100%; margin-left: 0px; padding: 25px;}
    }
    @media screen and (max-width: 411px) {
        .overlay .closebtn{top:0px;}
        .result_card_container{width: 100%; margin-left: 0px; padding: 25px;}
    }

    @media screen and (max-width: 320px) {
        .overlay .closebtn{top:-10px;}
        .overlay .printAllBtn {top:0px;}
        .result_card_container{width: 100%; margin-left: 0px; padding: 25px;}
    }
</style>
<!-- Page Content -->
<div id="page-wrapper" ng-controller="onlineAdmissionCtrl" ng-init="getOnlineAdmissions(); initCountries(); initClasses2(); initDiscounts()">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?= lang("lbl_online_admissions"); ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="dashboard/"><?= lang("lbl_dashboard"); ?></a></li>
                    <li class="active"><?= lang("lbl_online_admissions"); ?></li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <div id="myNav" class="overlay">
            <a href="javascript:onclick=customPrintForOnlineAdmission('overlay-content')" class="printAllBtn no-print"><i class="fa fa-print"></i></a>
            <a href="javascript:void(0)" class="closebtn no-print" ng-click="closeNav()">&times;</a>
            <div class="overlay-content" id="overlay-content">
                <!------------------ UI Image cropper ------------------->
                <div id="myModal" class="modal fade bs-student-add-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 2000;">
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

                                        <div class="col-md-12">
                                            <form data-ng-show="enableCrop" class="form-material">
                                                <input type="file" id="fileInput" accept="image/*" class="form-control mb-2"/>
                                            </form>

                                            <a data-ng-click="callTestFuntion()" data-dismiss="modal" aria-hidden="true" class="btn btn-info"><i class=""></i> <?php echo lang('crop') ?></a>
                                            <a class="btn btn-success" data-dismiss="modal" aria-hidden="true"><i class=""></i> <?php echo lang('lbl_close') ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>         
                    </div>
                    <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!------------------ UI Image cropper ------------------->
                <form name="application_update_from" ng-show="updateModel.id" ng-submit="update_application(application_update_from.$valid)" novalidate="">
                    
                    <div class="row white-box" id="application_update_form" style="margin-left: 20%; margin-right: 20%;">
                        <div class="col-md-12 text-left">
                            <h2 class="box-title">PARENT PERSONAL DETAILS</h2>
                        </div>
                        <!-- ./span -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="email" class="form-control" ng-model="updateModel.parent_email" placeholder="Enter parent email" required>
                            </div>
                        </div>
                        <!-- ./span -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" ng-model="updateModel.parent_name" placeholder="Enter parent name" required>
                            </div>
                        </div>
                        <!-- ./span -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control mydatepicker-autoclose" placeholder="DD/MM/YYYY" ng-pattern="/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/" ng-model="updateModel.parent_dob">
                            </div>
                        </div>
                        <!-- ./span -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <select class="form-control" ng-model="updateModel.parent_gender" required>
                                    <option value="" selected="selected">Select a gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                        <!-- ./span -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" ng-model="updateModel.parent_occupation" placeholder="Enter your occupation">
                            </div>
                        </div>
                        <!-- ./span -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="number" class="form-control" ng-model="updateModel.parent_income" placeholder="Enter your monthly income">
                            </div>
                        </div>
                        <!-- ./span -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" ng-model="updateModel.parent_street" placeholder="Enter your street no#.">
                            </div>
                        </div>
                        <!-- ./span -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" ng-model="updateModel.parent_ic_no" placeholder="Enter your ic number">
                            </div>
                        </div>
                        <!-- ./span -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <select name="countries" class="form-control" ng-model="updateModel.parent_country_id" required="">
                                    <option value="">Select a country</option>
                                    <option ng-repeat="c in countries" value="{{c.id}}">{{c.country_name}}</option>
                                </select>
                            </div>
                        </div>
                        <!-- ./span -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" ng-model="updateModel.parent_city" placeholder="Enter your city">
                            </div>
                        </div>
                        <!-- ./span -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <div id="remove_instance" style="margin-bottom: 20px; position: absolute;">
                                    <input type="text" class="form-control" id="phone1" ng-model="updateModel.parent_phone_no">
                                    <!--<input type="text" class="form-control" placeholder="+123-123-12345678" pattern="^[+]*[0-9]{2,3}-/{0,1}[0-9]{2,3}-/{0,1}[0-9]{5,8}$" maxlength="17" ng-model="updateModel.parent_phone_no">-->
                                </div> 
                            </div>
                        </div>
                        
                        <div class="col-sm-12" style="display: none;">
                            <input class="form-control" id="u_phone_number" type="text" ng-model="updateModel.u_phone_number">
                        </div>
                        <div class="col-sm-12" style="display: none;">
                            <input class="form-control" id="parent_phone_code" type="text" ng-model="updateModel.parent_phone_code">
                        </div>
                        
                        
                        
                        <!-- ./span -->
                        <div class="col-md-12 text-left" style="margin-top: 20px;">
                            <h2 class="box-title">SECONDARY GUARDIAN DETAILS</h2>
                        </div>
                        <!-- ./span -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" ng-model="updateModel.second_parent_name" placeholder="Enter second guardian name">
                            </div>
                        </div>
                        <!-- ./span -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <select class="form-control" ng-model="updateModel.second_parent_relation">
                                    <option value="" selected="selected">Select a Relation</option>
                                    <option value="father">Father</option>
                                    <option value="mother">Mother</option>
                                    <option value="uncle">Uncle</option>
                                    <option value="brother">Brother</option>
                                    <option value="grand_father">Grand Father</option>
                                    <option value="grand_mother">Grand Mother</option>
                                </select>
                            </div>
                        </div>
                        <!-- ./span -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <div id="remove_instance1" style="margin-bottom: 20px; position: absolute;">
                                    <input type="text" class="form-control" id="phone2" ng-model="updateModel.second_parent_phone_no">
                                    <!--<input type="text" class="form-control" placeholder="+123-123-12345678" pattern="^[+]*[0-9]{2,3}-/{0,1}[0-9]{2,3}-/{0,1}[0-9]{5,8}$" ng-model="updateModel.second_parent_phone_no">-->
                                </div> 
                            </div>
                        </div>
                        
                        <div class="col-sm-12" style="display: none;">
                            <input class="form-control" id="second_phone_number" type="text" ng-model="updateModel.second_phone_number">
                        </div>
                        <div class="col-sm-12" style="display: none;">
                            <input class="form-control" id="s_g_phone_code" type="text" ng-model="updateModel.s_g_phone_code">
                        </div>
                        
                        <!-- ./span -->
                        <div class="col-md-12 text-left" style="margin-top: 20px;">
                            <h2 class="box-title">CHILD INORMATION</h2>
                        </div>
                        <!-- .span -->
                        <div class="col-md-6 text-left">
                            <span>
                                <img style="width: 100px; height:100px; margin-bottom:5px;" ng-show="updateModel.std_avatar && !resImageDataURI" src="uploads/user/{{updateModel.std_avatar}}"/>
                            </span>
                            <span>
                                <img style="width: 100px; height:100px; margin-bottom:5px;" ng-show="resImageDataURI" ng-src="{{resImageDataURI}}"/>
                            </span>
                            <div class="form-group text-left">
                                <button type="button" data-toggle="modal" data-target=".bs-student-add-modal-lg" class="btn btn-info"/><i class="fa fa-image"></i> <?php echo lang('choose_image') ?></button>
                            </div>
                        </div>
                        <!-- .span -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="<?php echo lang('lbl_religion'); ?>" ng-model="updateModel.std_religion">
                            </div>
                        </div>
                        <!-- .span -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="<?php echo lang('lbl_full_name'); ?>" ng-model="updateModel.std_full_name" required>
                            </div>
                        </div>
                        <!-- .span -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <select ng-model="updateModel.std_gender" class="form-control" required>
                                    <option value=""><?php echo lang('select_gender'); ?></option>
                                    <option value="male"><?php echo lang('option_male'); ?></option>
                                    <option value="female"><?php echo lang('option_female'); ?></option>
                                </select>
                            </div>
                        </div>
                        <!-- .span -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control mydatepicker-autoclose" ng-model="updateModel.std_dob" placeholder="DD/MM/YYYY" ng-pattern="/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/">
                            </div>
                        </div>
                        <!-- .span -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <select ng-model="updateModel.std_blood_group" class="form-control">
                                    <option value=""><?php echo lang('select_blood'); ?></option>
                                    <option value="A+">A +ve</option>
                                    <option value="B+">B +ve</option>
                                    <option value="A-">A -ve</option>
                                    <option value="B-">B -ve</option>
                                    <option value="AB+">AB +ve</option>
                                    <option value="AB-">AB -ve</option>
                                    <option value="O+">O +ve</option>
                                    <option value="O-">O -ve</option>
                                </select>
                            </div>
                        </div>
                        <!-- .span -->
                        <div class="col-md-6">    
                            <div class="form-group">
                                <input type="text" placeholder="<?php echo lang('birth_place'); ?>" class="form-control" ng-model="updateModel.std_birth_place">
                            </div>
                        </div>
                        <!-- .span -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <select name="countries" ng-model="updateModel.std_country_id" class="form-control" required="">
                                    <option value="">Select a nationality</option>
                                    <option ng-repeat="c in countries" value="{{c.id}}">{{c.country_name}}</option>
                                </select>
                            </div>
                        </div>
                        <!-- .span -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" placeholder="<?php echo lang('lbl_language'); ?>" class="form-control" ng-model="updateModel.std_language">
                            </div>
                        </div>
                        <!-- .span -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" placeholder="<?php echo lang('national_number'); ?>" class="form-control" ng-model="updateModel.std_nic">
                            </div>
                        </div>
                        <!-- .span -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <select name="class" class="form-control" ng-model="updateModel.std_class_id"  required>
                                    <option value=""><?php echo lang('select_course'); ?></option>
                                    <option ng-repeat="cl in classes" value="{{cl.id}}">{{cl.name}}</option>
                                </select>
                            </div>
                        </div> 
                        <!-- .span -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="email" class="form-control" ng-model="updateModel.std_email" placeholder="Enter child email address" autocomplete="off">
                            </div>
                        </div>
                        <!-- .span -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <div id="remove_instance2" style="margin-bottom: 20px; position: absolute;">
                                    <input type="text" class="form-control" id="phone3" ng-model="updateModel.std_phone_no">
                                    <!--<input type="text" class="form-control" placeholder="Phone no (+123-123-12345678)" pattern="^[+]*[0-9]{2,3}-/{0,1}[0-9]{2,3}-/{0,1}[0-9]{5,8}$" ng-model="updateModel.std_phone_no">-->
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-12" style="display: none;">
                            <input class="form-control" id="std_phone_number" type="text" ng-model="updateModel.std_phone_number">
                        </div>
                        <div class="col-sm-12" style="display: none;">
                            <input class="form-control" id="child_phone_code" type="text" ng-model="updateModel.child_phone_code">
                        </div>
                        
                        <!-- .span -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" ng-model="updateModel.std_city" placeholder="Enter child city">
                            </div>
                        </div>
                        <!-- .span -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" class="form-control" ng-model="updateModel.std_address" placeholder="Enter child address">
                            </div>
                        </div>
                        <!-- .span -->
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success">Update Application</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="shiftModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Class Section Details</div>
                        <div class="panel-body">
                            <form name="shiftForm" ng-submit="shiftStudents(shiftForm.$valid)" novalidate="" class="form-material">
                                <div class="form-body">
                                    <div class="row">
                                        
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo lang('new_class'); ?></label>
                                                <select class="form-control" ng-model="new_course" ng-init="new_course=''" required="" ng-change="fetchNewClassBatches()">
                                                    <option value=""><?php echo lang('select_new_class'); ?></option>
                                                    <option ng-repeat="class in classes" value="{{class.id}}">{{class.name}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <!--/span-->
                                        <div class="col-md-6" id="newdropdownBatches">
                                            <div class="form-group">
                                                <label><?php echo lang('new_batch'); ?></label>
                                                <select class="form-control" ng-model="new_batch" ng-init="new_batch=''" ng-change="fetchSubjectGroups(new_batch)" required="">
                                                    <option value=""><?php echo lang('select_new_batch'); ?></option>
                                                    <option ng-repeat="batch in new_batches" value="{{batch.id}}">{{batch.name}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <!--/span-->
                                        <div class="col-md-6" id="newdropdownSubectGroups">
                                            <div class="form-group">
                                                <label><?php echo lang('subject_groups'); ?></label>
                                                <select class="form-control" ng-model="new_subject_group_id" ng-init="new_subject_group_id=''" required="">
                                                    <option value=""><?php echo lang('select_subject_group'); ?></option>
                                                    <option ng-repeat="group in groups" value="{{group.id}}">{{group.group_name}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <!--/span-->
                                        <!--<div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo lang('lbl_rollno'); ?></label>
                                                <input type="text" name="rollno" ng-model="rollno" class="form-control" placeholder="<?php echo lang('lbl_rollno'); ?>"/>
                                            </div>
                                        </div>-->

                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo lang('admission_date'); ?></label>
                                                <input type="text" name="admission_date" ng-model="admission_date" class="form-control mydatepicker-autoclose" placeholder="<?php echo lang('admission_date'); ?>" />
                                            </div>
                                        </div>

                                        <!--/span-->
                                        <div class="col-md-12" id="newdropdownDiscount">
                                            <div class="form-group">
                                                <label><?php echo lang('lbl_discount'); ?></label>
                                                <select class="form-control" ng-model="new_discount_id" ng-init="new_discount_id=''">
                                                    <option value=""><?php echo lang('lbl_select_a_discount'); ?></option>
                                                    <option ng-repeat="discount in discounts" value="{{discount.id}}">{{discount.name}}</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="pull-right m-r-5">
                                    <button type="button" class="btn btn-info waves-effect text-left" data-dismiss="modal"><?php echo lang('lbl_close'); ?></button>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i><?php echo lang('btn_admit'); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="hint"><?php echo lang('help_dashboard'); ?></div>
        
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box well well-sm"  id="std_search_filter">
                    <form name="online_admission_filter" ng-submit="fetchAllStdsOfClassAndBatch()" novalidate="">
                        <div class="row form-material">
                            <!--<div class="col-md-4" id="feeFilterAcademicYears">
                                <div class="form-group">
                                    <label class="control-label"><?php echo lang("lbl_academic_year"); ?></label>
                                    <select class="form-control" name="academic_year_id" ng-model="filterModel.academic_year_id" required="" ng-change="initClasses2(filterModel.academic_year_id)">
                                        <option value="-1"><?php echo lang("lbl_select_academic_year"); ?></option>
                                        <option ng-repeat="ay in academicyears" value="{{ ay.id }}">{{ ay.name }}</option>
                                    </select>
                                </div>
                            </div>-->
                            <!--/span-->
                            <div class="col-md-6" id="oAFilterClasses">
                                <div class="form-group">
                                    <label class="control-label"><?php echo lang("lbl_class"); ?></label>
                                    <select class="form-control" name="class_id" ng-model="filterModel.class_id" required="" ng-init="filterModel.class_id='all'">
                                        <option value="all"><?php echo lang("option_all"); ?></option>
                                        <option ng-repeat="cls in classes" value="{{ cls.id }}">{{ cls.name }}</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo lang('serach_by_keyword') ?></label>
                                    <input type="text" name="searchBy" ng-model="filterModel.searchBy" placeholder="<?php echo lang('serach_by_keyword'); ?>"  class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-sm"><?php echo lang('search') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row white-box" style="margin-left: 2px; margin-right: 2px;">
            <div class="col-md-12 p-0">
                <div class="table-responsive">
                    <div style="overflow-x:auto">
                        <table id="myTablee_onlineAdmission" class="display nowrap" cellspacing="0" width="100%"></table>
                    </div>
                </div>
            </div>
            <div class="col-md-12 p-0 m-t-15">
                <button id="mysubmitbtn" type="button" data-toggle="modal" data-target="#shiftModal" class="btn btn-primary custom_disable"><?php echo lang("submit"); ?></button>
            </div>
        </div>
            
        <!-- .right-sidebar -->
        <!-- Include yout right sidebar here -->
        <!-- /.right-sidebar -->
    </div>
    


<?php include(APPPATH . "views/inc/footer.php"); ?>
<script>
    $('#myModal').on('shown.bs.modal', function (e) {
        $(".modal-backdrop").removeClass("show");
        $(".modal-backdrop").addClass("hide");
    });
</script>