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
        <div class="container-fluid" ng-controller="parentEditController" >
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><th><?php echo lang('edit_parent') ?></th></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                    <ol class="breadcrumb">
                        <li><a href="#"><?php echo lang('crumb_dashboard') ?></a></li>
                        <li><a href="#"><?php echo lang('crumb_parents') ?></a>
                        </li>
                        <li class="active"><?php echo lang('edit_parent') ?></li>

                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <!-- Page Content -->
            <!-- /.row -->
            <div class="alert alert-dismissable {{alert.type}}" ng-if="alert.message"> 
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>     
                {{ alert.message }}
            </div>
            <!-- .row -->
            <div class="hint"><?php echo lang('help_parent_edit'); ?></div>
            <!-- row -->
                        <!------------------ UI Image cropper ------------------->

            <!-- reset password modal  -->

            <div id="resetPassword" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"><?php echo lang('reset_confirmation'); ?></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <p><?php echo lang('confirmation_message'); ?></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang('btn_close_bank') ?></button>
                            <button type="button" class="btn btn-danger waves-effect waves-light" ng-click="resetPassword()"><?php echo lang('lbl_reset'); ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- reset password modal ends here  -->

        <!-- sample modal content -->
        <div id="myModal" class="modal fade bs-employee-edit-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel"><?php echo lang('image_cropper') ?></h4>
                    </div>

                    <div class="modal-body">
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
                                                </div>  -->
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->


        <!-- UI Image cropper -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">

                    <div class="panel-wrapper collapse in" aria-expanded="true">
                        <div class="panel-body">

                            <ul class="nav customtab nav-tabs table-responsive" role="tablist">
                                <li role="presentation" class="nav-item"><a href="#personal" class="nav-link <?php if ($selected_tab === 'personal') {
                                    echo "active";
                                } else if ($selected_tab === "") {
                                    echo "active";
                                } ?>" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="fa fa-calendar"></i></span><span class="hidden-xs"><?php echo lang('parent_personal_details'); ?></span></a></li>
                                <li role="presentation" class="nav-item"><a href="#reset_password" class="nav-link <?php if ($selected_tab === 'reset_password') {
                                    echo "active";
                                } else if ($selected_tab === "") {
                                    echo "active";
                                } ?>" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="fa fa-calendar"></i></span><span class="hidden-xs"><?php echo lang('password_reset'); ?></span></a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane <?php if ($selected_tab === 'personal') {
                                    echo "active";
                                } else if ($selected_tab === "") {
                                    echo "active";
                                } ?>" id="personal">
                                <div class="row" id='stdForm' ng-init="fetchParent(<?php echo $parent_id; ?>)">
                                    <div class="col-md-12">
                                        <form name="parentAddmissionForm" ng-submit="onSubmit(parentAddmissionForm.$valid,image3.resized)" novalidate="" class="form-material">
                                            <div class="form-body ">
                                                <h3 class="box-title"><?php echo lang('personal_details') ?></h3>
                                                <hr style="border-color: black" />
                                                <span ng-show="resImageDataURI">
                                                    <img style="width: 100px; height: 100px;" ng-src="{{resImageDataURI}}"/>
                                                </span>
                                                <span ng-show="!resImageDataURI">
                                                    <img style="width: 100px; height: 100px;" ng-src="<?php echo base_url(); ?>uploads/user/{{formModel.avatar}}" />
                                                </span>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_avatar') ?></label><br/>
                                                            <button type="button" data-toggle="modal" data-target=".bs-employee-edit-modal-lg" class="btn btn-default"/><i class="fa fa-image"></i> <?php echo lang('choose_image') ?></button>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_name') ?></label>
                                                            <input type="text" id="pname" class="form-control" ng-model="formModel.name"  required="">
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                </div>
                                                <div class="row">

                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_gender') ?></label>
                                                            <select class="form-control" ng-model="formModel.gender" required="" ng-init="formModel.pGender=''" required="">
                                                                <option value=""><?php echo lang('select_gender') ?></option>
                                                                <option value="male"><?php echo lang('option_male') ?></option>
                                                                <option value="female"><?php echo lang('option_female') ?></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!--/span-->

                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_dob') ?></label>
                                                            <input type="text" class="form-control mydatepicker-autoclose" ng-model="formModel.dob" ng-pattern="/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/">
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                </div>
                                                <!--/row-->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_occupation') ?></label>
                                                            <input type="text" id="occupation" class="form-control" ng-model="formModel.occupation">
                                                        </div>
                                                    </div>
                                                    <!--/span-->

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_income') ?></label>
                                                            <input type="text" id="income" class="form-control"  ng-model="formModel.income" >
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                </div>
                                                <!--/row-->

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><?php echo lang('lbl_email') ?></label>
                                                            <input type="email" class="form-control" ng-model="formModel.email" required="" ng-pattern="/^[a-z]+[a-z0-9._+]+@[a-z]+\.[a-z.]{2,5}$/">
                                                        </div>
                                                    </div>




                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><?php echo lang('lbl_country') ?></label>
                                                            <select name="pCountry" ng-model="formModel.country" required="" ng-init="formModel.country=''" class="form-control">
                                                                <option value=""><?php echo lang('select_country') ?></option>
                                                                <?php foreach ($countries as $country) { ?>
                                                                <option value="<?php echo $country->country_code; ?>"><?php echo $country->country_name; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>


                                                    <!--/span-->

                                                </div>
                                                <!--/span-->
                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><?php echo lang('lbl_city') ?></label>
                                                            <input type="text" class="form-control" ng-model="formModel.city" ng-pattern="/^[a-zA-Z\u0600-\u06FF  ]*$/">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><?php echo lang('lbl_phone') ?></label>
                                                            <div class="col-xs-12" id="remove_parent_instance" style="margin-bottom: 20px; position: absolute; width: auto; ">
                                                                <!--<input type="text" class="form-control" id="phone" pattern="^[+]*[0-9]{2,3}-/{0,1}[0-9]{2,3}-/{0,1}[0-9]{5,8}$" ng-model="formModel.contact">-->
                                                                <!--<input type="text" class="form-control" id="phone" name="phone" pattern="/^[0-9]*$/" ng-model="formModel.contact">-->
                                                                <input type="text" class="form-control" id="phone" name="phone" ng-model="formModel.contact">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" style="display: none;">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="parent_phone_code" name="parent_phone_code">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><?php echo lang('lbl_street') ?></label>
                                                            <input type="text" class="form-control" ng-model="formModel.address">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><?php echo lang('lbl_ic_number') ?></label>
                                                            <input type="text" name="pId" class="form-control" ng-model="formModel.ic_number">
                                                        </div>
                                                    </div>
                                                </div>
                                                <h3 class="box-title"><?php echo lang('secondary_guardian_details'); ?></h3>
                                                <hr style="border-color: black" />
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_name') ?></label>
                                                            <input type="text" id="pname" class="form-control" ng-model="formModel.guardian2_name">
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_relation') ?></label>
                                                            <select class="form-control" ng-model="formModel.guardian2_relation" ng-init="formModel.relation=''">
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
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><?php echo lang('lbl_phone') ?></label>
                                                            <input type="text" class="form-control" id="contact2" pattern="^[+]*[0-9]{2,3}-/{0,1}[0-9]{2,3}-/{0,1}[0-9]{5,8}$" ng-model="formModel.guardian2_contact">
                                                        </div>
                                                    </div>
                                                    <!--/span-->

                                                    <!--/span-->
                                                </div>

                                                <div class="form-actions pull-right">
                                                    <button type="submit" class="btn btn-primary"> <i class="fa fa-check"></i> <?php echo lang('btn_update') ?></button>
                                                </div>
                                            </div>
                                        </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane <?php if ($selected_tab === 'reset_password') {
                                    echo "active";
                                } else if ($selected_tab === "") {
                                    echo "active";
                                } ?>" id="reset_password">
                                    <div class="row" id='stdForm' ng-init="fetchParent(<?php echo $parent_id; ?>)">
                                        <div class="col-md-12">
                                            <div class="form-actions pull-left">
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#resetPassword"><?php echo lang('password_reset') ?></button>
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
        document.getElementById('remove_parent_instance').textContent = '';
        document.getElementById('remove_parent_instance').innerHTML = '<div onchange="getphone()"><input class="form-control" style="width:100%;" type="text" name="phone" id="phone" ng-model="formModel.contact"  ></div>';
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
        