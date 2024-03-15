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
        <div class="container-fluid" ng-controller="parentAdmissionController" >
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?php echo lang('add_parent') ?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                    <ol class="breadcrumb">
                        <li><a href="#"><?php echo lang('crumb_dashboard') ?></a></li>
                        <li><a href="#"><?php echo lang('crumb_parents') ?></a>
                        </li>
                        <li class="active"><?php echo lang('add_parent') ?></li>

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
            <div class="hint"><?php echo lang('help_parent_add'); ?></div>
            <!-- .row -->
            <!-- row -->
            <div id="myModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
            <div class="row" id='stdForm'>
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-wrapper collapse in" aria-expanded="true">
                            <div class="panel-body m-l-20 m-r-20">
                                <form name="parentAddmissionForm" ng-submit="onSubmit(parentAddmissionForm.$valid,image3.resized)" novalidate="" class="form-material">
                                    <div class="form-body ">
                                        <h3 class="box-title"><?php echo lang('personal_details') ?></h3>
                                        <hr style="border-color: black" />
                                        <span>
                                            <img style="width: 100px;height:100px" ng-show="resImageDataURI" ng-src="{{resImageDataURI}}"/>
                                        </span>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_avatar') ?></label><br/>
                                    <button type="button" data-toggle="modal" data-target=".bs-example-modal-lg" class="btn btn-default"/><i class="fa fa-image"></i> <?php echo lang('choose_image') ?></button>
<!--                                                    <span>
                                                        <img style="width: auto;" ng-show="image3" ng-src="{{image3.resized.dataURL}}"/>
                                                    </span>-->
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_name') ?></label>
                                                    <input type="text" id="pname" class="form-control" ng-model="formModel.pName"  required="">
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <div class="row">

                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_gender') ?></label>
                                                    <select class="form-control" ng-model="formModel.pGender" required="" ng-init="formModel.pGender=''" required="">
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
                                                    <input type="text" class="form-control mydatepicker-autoclose" ng-model="formModel.pDob" ng-pattern="/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/">
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_occupation') ?></label>
                                                    <input type="text" id="occupation" class="form-control" ng-model="formModel.pOccupation" >
                                                </div>
                                            </div>
                                            <!--/span-->

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_income') ?></label>
                                                    <input type="text" id="income" class="form-control"  ng-model="formModel.pIncome" >
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo lang('lbl_email') ?></label>
                                                    <input type="email" class="form-control" ng-init="formModel.pEmail=''" ng-model="formModel.pEmail" required="" ng-pattern="/^[a-z]+[a-z0-9._+]+@[a-z]+\.[a-z.]{2,5}$/">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo lang('lbl_country') ?>*</label>
                                                    <div class="col-xs-12" style="margin-bottom:20px;" >
                                                    <!--<select name="time_zone" required="" ng-model="formModel.country" class="form-control countrySelect" required=""  onchange="getVal(this.value)">-->
                                                    <select name="pCountry" required="" ng-model="formModel.pCountry" class="form-control countrySelect" required="" >
                                                        <option></option>
                                                        <?php foreach ($countries as $zone1) { ?>
                                                            
                                                        <option value="<?= $zone1->country_code; ?>" ><?= $zone1->country_name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                            
                                        <div class="row">
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo lang('lbl_city') ?></label>
                                                    <input type="text" class="form-control" ng-model="formModel.pCity" ng-pattern="/^[a-zA-Z\u0600-\u06FF  ]*$/">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group" >
                                                    <label><?php echo lang('lbl_phone') ?></label>
                                                    <div class="col-xs-12" id="remove_instance" style="margin-bottom: 20px; position: absolute; width: auto; ">
                                                    <input class="form-control" type="text" name="phone" ng-model="formModel.pPhone" id="phone"  placeholder="<?php echo lang('lbl_phone_number'); ?>" >
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!--/span-->
                                            <!--<div class="col-md-6">-->
                                            <!--    <div class="form-group">-->
                                            <!--        <label><?php echo lang('lbl_phone') ?></label>-->
                                            <!--        <input type="text" class="form-control" id="contact" pattern="^[+]*[0-9]{2,3}-/{0,1}[0-9]{2,3}-/{0,1}[0-9]{5,8}$" ng-model="formModel.pPhone">-->
                                            <!--    </div>-->
                                            <!--</div> -->
                                        </div>
                                        <!--/span-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo lang('lbl_street') ?></label>
                                                    <input type="text" class="form-control" ng-model="formModel.pStreet">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo lang('lbl_ic_number') ?></label>
                                                    <input type="text" name="pId" class="form-control" ng-model="formModel.pIdNumber">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <!--<div class="col-md-6">-->
                                            <!--    <div class="form-group">-->
                                            <!--        <label><?php echo lang('lbl_country') ?></label>-->
                                            <!--        <select name="pCountry" ng-model="formModel.pCountry" required="" ng-init="formModel.pCountry=''" class="form-control">-->
                                            <!--            <option value=""><?php echo lang('select_country') ?></option>-->
                                            <!--            <?php foreach ($countries as $country) { ?>-->
                                            <!--                <option value="<?php echo $country->id; ?>"><?php echo $country->country_name; ?></option>-->
                                            <!--            <?php } ?>-->
                                            <!--        </select>-->
                                            <!--    </div>-->
                                            <!--</div>-->
                                            <!--/span-->
                                            
                                            <!--/span-->
                                        </div>
                                        <h3 class="box-title"><?php echo lang('secondary_guardian_details'); ?></h3>
                                        <hr style="border-color: black" />
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_name') ?></label>
                                                    <input type="text" id="pname" class="form-control" ng-model="formModel.pName2">
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_relation') ?></label>
                                                    <select class="form-control" ng-model="formModel.relation" ng-init="formModel.relation=''">
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
                                                    <input type="text" class="form-control" id="contact2" pattern="^[+]*[0-9]{2,3}-/{0,1}[0-9]{2,3}-/{0,1}[0-9]{5,8}$" ng-model="formModel.pPhone2">
                                                </div>
                                            </div>
                                            <!--/span-->
                                            
                                            <!--/span-->
                                        </div>
                                        
                                        <div class="row" style="display: none;">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" name="school_country_code" id="school_country_code" value="<?php echo $school_country_code; ?>" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row" style="display: none;">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" name="country_change_code" id="country_change_code" class="form-control">
                                                </div>
                                            </div>
                                        </div>


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
        document.getElementById('remove_instance').innerHTML = '<div onchange="getphone()"><input class="form-control" style="width:100%;" type="text" name="phone" id="phone" ng-model="formModel.pPhone"  ></div>';
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
        console.log(code+" "+phone);
    }
            
</script>

<script>
    $(document).ready(function () {
        
        var country_value3="";
        document.getElementById('remove_instance').textContent = '';
        document.getElementById('remove_instance').innerHTML = '<input class="form-control" style="width:100%;" type="text" name="phone" id="phone" ng-model="formModel.pPhone">';
        
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
        $('#country_change_code').val(country_value3);
        
        input.addEventListener('countrychange', function(e) {
            country_value3 = iti.getSelectedCountryData().iso2;
            $('#country_change_code').val(country_value3);
        });
        
        
        
    });
</script>
        