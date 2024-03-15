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

<div>
    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid" ng-controller="stdAdmissionController" >
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?php echo lang('add_student') ?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                    <ol class="breadcrumb">
                        <li><a href="#"><?php echo lang('crumb_dashboard') ?></a></li>
                        <li><a href="#"><?php echo lang('menu_students') ?></a>
                        </li>
                        <li class="active"><?php echo lang('add_student') ?></li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <!-- Page Content -->

            <!------------------ UI Image cropper ------------------->

            <!-- sample modal content -->
            <div id="myModal" class="modal fade bs-student-add-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                            <a href=""><span class="glyphicon glyphicon-repeat" id="rotateIcon" data-ng-init="rd=1;" data-ng-click="RotateImage('stdProfile',rd);rd=rd+1;rd==4?rd=0:''"></span></a>
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

                                        <!-- /.row -->
                                        <div class="alert alert-dismissable {{alert.type}}" ng-if="alert.message"> 
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>     
                                            {{ alert.message }}
                                        </div>
                                        <div class="hint"><?php echo lang('help_std_add'); ?></div>
                                        <!-- .row -->
                                        <!-- row -->
                                        <div class="row" id='stdForm'>
                                            <div class="col-md-12">
                                                <div class="panel">
                                                    <div class="panel-wrapper collapse in" aria-expanded="true">
                                                        <div class="panel-body m-l-20 m-r-20">
                                                            <form name="stdAddmissionForm" ng-submit="onSubmit(stdAddmissionForm.$valid)" novalidate="" class="form-material">
                                                                <div class="form-body ">
                                                                    <h3 class="box-title"><?php echo lang('personal_details') ?></h3>
                                                                    <hr style="border-color: black" />
                                                                    <span>
                                                                        <img style="width: 100px; height:100px;" ng-show="resImageDataURI" ng-src="{{resImageDataURI}}" id="stdProfile"/>
                                                                    </span>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="control-label"><?php echo lang('lbl_avatar') ?></label><br/>
                                                                                <button type="button" data-toggle="modal" data-target=".bs-student-add-modal-lg" class="btn btn-default"/><i class="fa fa-image"></i> <?php echo lang('choose_image') ?></button>
                <!--                                    <input id="inputImage2" 
                                                           type="file" 
                                                           accept="image/*" 
                                                           image="image2" 
                                                           class="form-control"
                                                           resize-max-height="300"
                                                           resize-max-width="350"
                                                           style="font-size: 12px;"
                                                           resize-quality="0.7" />-->
                                                           <!--<input type="hidden"  class="form-control" id="avatar" name="avatar" value="<?php echo $employee->avatar; ?>">-->
                                                       </div>
                                                   </div>
                                                   <!--/span-->
                                                   <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_religion') ?></label>
                                                        <input type="text" id="religion" ng-model="formModel.religion" class="form-control" ng-pattern="/^[a-zA-Z\u0600-\u06FF ]*$/">
                                                    </div>
                                                </div>
                                                <!--/span-->
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_full_name') ?></label>
                                                        <input type="text" id="firstName" ng-model="formModel.firstname" class="form-control"  required="">
                                                    </div>
                                                </div>
                                                <!--/span-->
<!--                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('last_name') ?></label>
                                                        <input type="text" id="lastName" ng-model="formModel.lastname" class="form-control"  required="">
                                                    </div>
                                                </div>-->
                                                <!--/span-->
                                            </div>
                                            <!--/row-->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_gender') ?></label>
                                                        <select class="form-control" ng-model="formModel.gender" required="" ng-init="formModel.gender=''" required="">
                                                            <option value=""><?php echo lang('select_gender') ?></option>
                                                            <option value="male"><?php echo lang('option_male') ?></option>
                                                            <option value="female"><?php echo lang('option_female') ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <!--/span-->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_dob') ?></label>
                                                        <input type="text" name="dob" class="form-control mydatepicker-autoclose" ng-model="formModel.dob" ng-pattern="/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/">
                                                    </div>
                                                </div>
                                                <!--/span-->
                                            </div>
                                            <!--/row-->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('blood_group') ?></label>
                                                        <select class="form-control" ng-model="formModel.blood" ng-init="formModel.blood=''">
                                                            <option value=""><?php echo lang('select_blood') ?></option>
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
                                                <!--/span-->

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('birth_place') ?></label>
                                                        <input type="text" id="birthPlace" ng-model="formModel.birthPlace" class="form-control" placeholder="">
                                                    </div>
                                                </div>
                                                <!--/span-->
                                            </div>
                                            <!--/row-->
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_nationality') ?></label>
                                                        <!--<select name="nationality" ng-model="formModel.nationality" ng-init="formModel.nationality=''" class="form-control countrySelect" required="" onchange="getVal(this.value)">-->
                                                        <select name="nationality" ng-model="formModel.nationality" ng-init="formModel.nationality=''" class="form-control countrySelect" required="">
                                                            <option value=""><?php echo lang('select_nationality') ?></option>
                                                            <?php foreach ($countries as $country) { ?>
                                                                <option value="<?php echo $country->country_code; ?>"><?php echo $country->country_name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                
                                                <!--/span-->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_language') ?></label>
                                                        <input type="text" id="language" ng-model="formModel.language" class="form-control" >
                                                    </div>
                                                </div>
                                                <!--/span-->
                                            </div>

                                            <!--/row-->
                                            <div class="row">

                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('national_number');?></label>
                                                        <input type="text" ng-model="formModel.ic_number" class="form-control" >
                                                    </div>
                                                </div>
                                              
                                            </div>
                                        <!-- contact details -->
                                    <h3 class="title-danger"><?php echo lang('contact_details') ?></h3>
                                            <hr style="border-color: black" />

                                             <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo lang('lbl_email') ?></label>
                                                        <input type="email" ng-model="formModel.email" ng-pattern="/^[a-z]+[a-z0-9._+]+@[a-z]+\.[a-z.]{2,5}$/" class="form-control">
                                                    </div>
                                                </div>
                                                     <div class="col-md-6">
                                                     <div class="form-group">
                                                        <label><?php echo lang('lbl_phone') ?></label>
                                                        <div class="col-xs-12" id="remove_instance" style="margin-bottom: 20px; position: absolute;  width: 100%; ">
                                                    
                                                            <input type="text" class="form-control" name="phone" id="phone" ng-model="formModel.phone">
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo lang('lbl_city') ?></label>
                                                        <input type="text" class="form-control" ng-model="formModel.city" required="" ng-pattern="/^[a-zA-Z\u0600-\u06FF  ]*$/">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo lang('lbl_address') ?></label>
                                                        <input type="text" class="form-control" ng-model="formModel.address" required="">
                                                    </div>
                                                </div>      
                                                </div>
                                                <!-- mother details -->

                                            <h3 class="title-danger"><?php echo lang('mother_details') ?></h3>
                                            <hr style="border-color: black" />
                                            <div class="row">            
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo lang('mother_name') ?></label>
                                                        <div class="col-xs-12">
                                                            <input type="text" class="form-control"ng-model="formModel.mother_name">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo lang('lbl_phone') ?></label>
                                                        <div class="col-xs-12 new"  style="margin-bottom: 20px; position: absolute;  width: 100%;">
                                                            <input type="number" class="form-control"ng-model="formModel.mother_phone">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--    <div class="row" style="display: none;">
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" name="mother_phone_code" id="mother_phone_code" ng-model="formModel.mother_phone_code">
                                                </div>
                                            </div> -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo lang('ic_number') ?></label>
                                                        <div class="col-xs-12">
                                                            <input type="text" class="form-control"ng-model="formModel.mother_ic">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- mother section -->
                                            <div class="row" style="display: none;">
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" name="parent_phone_code" id="parent_phone_code" ng-model="formModel.parent_phone_code">
                                                </div>
                                                 <div class="col-sm-12">
                                                    <input type="text" class="form-control" name="mother_phone_code" id="mother_phone_code" ng-model="formModel.mother_phone_code">
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <!-- <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo lang('lbl_country') ?></label>
                                                        <select name="country" ng-model="formModel.country" ng-init="formModel.country=''" class="form-control" required="">
                                                            <option value=""><?php echo lang('select_country') ?></option>
                                                            <?php foreach ($countries as $country) { ?>
                                                                <option value="<?php echo $country->id; ?>"><?php echo $country->country_name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div> -->
                                                <!--/span-->
                                                <!-- <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo lang('lbl_city') ?></label>
                                                        <input type="text" class="form-control" ng-model="formModel.city" required="" ng-pattern="/^[a-zA-Z\u0600-\u06FF  ]*$/">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo lang('lbl_address') ?></label>
                                                        <input type="text" class="form-control" ng-model="formModel.address" required="">
                                                    </div>
                                                </div> -->
                                                <!--/span-->
                                            </div>

                                            <h3 class="title-danger"><?php echo lang('courses_batch_details') ?></h3>
                                            <hr style="border-color: black" />
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo lang('lbl_course') ?></label>
                                                        <select class="form-control" ng-model="formModel.course" ng-init="formModel.course=''" required="" ng-change="fetchClassBatches(formModel.course)">
                                                            <option value=""><?php echo lang('select_course') ?></option>
                                                            <?php
                                                            if (count($classes) > 0) {
                                                                foreach ($classes as $cls) {
                                                                    ?>
                                                                    <option value="<?= $cls->id; ?>"><?= $cls->name; ?></option>
                                                                    <?php
                                                                }
                                                            } else {
                                                                ?>
                                                                <option><?php echo lang('no_record') ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <!--/span-->
                                                <div class="col-md-6" id="frmBatches">
                                                    <div class="form-group">
                                                        <label><?php echo lang('lbl_batch') ?></label>
                                                        <select class="form-control" ng-model="formModel.batch" ng-init="formModel.batch=''" ng-change="fetchSubjectGroups()" required="">
                                                            <option value=""><?php echo lang('select_batch') ?></option>
                                                            <option ng-repeat="batch in batches" value="{{batch.id}}">{{batch.name}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <!--/span-->
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6" id="frmGroups">
                                                    <div class="form-group">
                                                        <label><?php echo lang('subject_group_std_form') ?></label>
                                                        <select class="form-control" ng-model="formModel.subject_group" ng-init="formModel.subject_group=''" >
                                                            <option value=""><?php echo lang('select_subject_group') ?></option>
                                                            <option ng-repeat="group in groups" value="{{group.id}}">{{group.group_name}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group" id="rollno">
                                                        <label><?php echo lang('lbl_rollno') ?></label>
                                                        <div class="input-group mb-3">
                                                          <div class="input-group-prepend" style="margin-right:5px;">
                                                            <button class="btn btn-outline-primary" type="button" disabled="" style="margin-top:2px;"><?php echo $url; ?></button>
                                                        </div>
                                                        <input type="text" class="form-control" ng-model="formModel.rollno" required="">
                                                    </div>
                                                    </div>
                                                </div>
                                              
                                                <!--/span-->
                                            </div>
                                            <div class="row">
                                                  <!-- <div class="col-md-6" id="discount_div">
                                                    <div class="form-group">
                                                        <label><?php echo lang('lbl_discount') ?></label>
                                                        <select class="form-control yasir-ann-select2" style="width: 100%;" multiple="multiple" ng-model="formModel.discount_id" ng-init="loadDiscounts()">
                                                            <option value="">---<?= lang("lbl_select_a_discount"); ?>---</option>
                                                            <option ng-repeat="discount in discounts" value="{{discount.id}}">{{discount.name}}</option>
                                                        </select>
                                                    </div>
                                                </div> -->
                                               <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('admission_date');?></label>
                                                    <input type="text" name="adm_date" class="form-control mydatepicker-autoclose" ng-model="formModel.adm_date" ng-pattern="/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row" style="display: none;">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" name="school_country_code" id="school_country_code" value="<?php echo $school_country_code; ?>" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        


                                        <!--/row-->
                                        
                                        <!--<h3 class="box-title"><?php echo lang('health') ?></h3>-->
                                        <!--<hr style="border-color: black" />-->
                                        
                                        
                                        <!--<div class="row">-->
                                            
                                        <!--    <div class="col-md-6">-->
                                        <!--        <div class="form-group">-->
                                        <!--            <label class="control-label"><?php echo lang('health_status') ?></label>-->
                                        <!--            <select class="form-control" name="health_status" ng-model="formModel.health_status">-->
                                        <!--                <option value="" selected="selected"><?php echo lang('select_health_status') ?></option>-->
                                        <!--                <option value="Normal"><?php echo lang('health_normal') ?></option>-->
                                        <!--                <option value="Abnormal"><?php echo lang('health_abnormal') ?></option>-->
                                        <!--            </select>-->
                                        <!--        </div>-->
                                        <!--    </div>-->
                                            
                                        <!--    <div class="col-md-6">-->
                                        <!--        <div class="form-group">-->
                                        <!--            <label class="control-label"><?php echo lang('health_remark') ?></label>-->
                                        <!--            <input type="text" name="health_remark" class="form-control" ng-model="formModel.health_remark">-->
                                        <!--        </div>-->
                                        <!--    </div>-->
                                            
                                        <!--    <div class="col-md-12">-->
                                        <!--        <div class="form-group">-->
                                        <!--            <label class="control-label"><?php echo lang('health_other_notes') ?></label>-->
                                        <!--            <input type="text" name="health_other_notes" class="form-control" ng-model="formModel.health_other_notes">-->
                                        <!--        </div>-->
                                        <!--    </div>-->
                                        <!--</div>-->
                                        <!--/row-->

                                        <h3 class="box-title"><?php echo lang('lbl_guardian') ?></h3>
                                        <hr style="border-color: black" />


                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_name'); ?></label>
                                                    <select class="form-control js-data-example-ajax"  ng-model="formModel.guardian" style="width: 100%" multiple="" required=""></select>

                                                </div>
                                                <small>*<?php echo lang('add_parent_hint') ?></small>
                                                <a href="<?php echo base_url(); ?>parents/add" target="_blank"><small><?php echo lang('add_parent') ?></small></a>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_relation') ?></label>
                                                    <select class="form-control" required="" ng-model="formModel.relation" ng-init="formModel.relation=''" required="">
                                                        <option value=""><?php echo lang('select_relation') ?></option>
                                                        <option value="father"><?php echo lang('father') ?></option>
                                                        <option value="mother"><?php echo lang('mother') ?></option>
                                                        <option value="uncle"><?php echo lang('uncle') ?></option>
                                                        <option value="brother"><?php echo lang('brother') ?></option>
                                                        <option value="grandfather"><?php echo lang('grand_father') ?></option>
                                                        <option value="grandmother"><?php echo lang('grand_mother') ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--/span-->

                                            <!--/span-->
                                        </div>
                                        <!--/row-->

                                        <!--/row-->


                                        <!--/span-->
                                        <div class="form-actions pull-right">
                                            <button type="button" class="btn btn-default"><?php echo lang('btn_cancel') ?></button>
                                            <button type="submit" class="btn btn-primary"> <i class="fa fa-check"></i> <?php echo lang('btn_save') ?></button>

                                        </div>
                                    </div>
                                </form>
                            </div>   
                        </div>
                    </div>
                </div>
            </div>
            <!--./row-->



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
        document.getElementById('remove_instance').textContent = ''; 
        document.getElementById('remove_instance').innerHTML = '<div onchange="getphone()"><input class="form-control" style="width:100%;" type="text" name="phone" id="phone" ng-model="formModel.phone" ></div>';
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
        document.getElementById('remove_instance').innerHTML = '<input class="form-control" style="width:100%;" type="text" name="phone" id="phone" ng-model="formModel.phone" >';
        
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
        
        
        
        <script type="text/javascript">
            $(".js-data-example-ajax").select2({
                maximumSelectionLength: 1,
                ajax: {
                    url: '<?php echo site_url('messages/getRecipients') ?>',
                    dataType: 'json',

                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page,
                            role: 2
                        };
                    },
                    processResults: function (data, params) {
                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        params.page = params.page || 1;

                        return {
                            results: data.rec,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 1,
                templateResult: formatRepo,
                templateSelection: formatRepoSelection
            });

            function formatRepo(repo) {
                if (repo.loading) {
                    return repo.text;
                }
                var markup;


                markup = "<div class='row' style='width:99%;'>" +
                "<div class='col-md-1'>" + "<img class='img-responsive' src='<?php echo base_url() ?>uploads/user/" + repo.avatar + "'> " + "</div>" +
                "<div class='col-md-11'>" + "<div class='row'>" +
                "<div class='col-md-12'><b>" + repo.user_name.toUpperCase() + "</b></div>" +
                "</div>" + "<div class='row'>" +
                "<div class='col-md-12'>Parent" + "</div>" +
                "</div>" + "</div>";









                return markup;
            }

            function formatRepoSelection(repo) {
                return repo.user_name;
            }


        </script>
        
