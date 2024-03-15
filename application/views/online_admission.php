<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <base href = "<?php echo base_url(); ?>" />
    <link rel="icon" type="image/png" sizes="16x16" href="assets/plugins/images/favicon.png">
    <title>Online Admission</title>
    <!-- Bootstrap Core CSS -->
    <link href="assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--<link href="assets/plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css" rel="stylesheet">-->
    <!-- animation CSS -->
    <link href="assets/css/animate.css" rel="stylesheet">
    <!-- Wizard CSS -->
    <link href="assets/plugins/bower_components/register-steps/steps.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/dist/css/style.css" rel="stylesheet">
    <!-- color CSS -->
    <link href="assets/css/colors/default.css" id="theme" rel="stylesheet">
    <!-- icheck CSS -->
    <link href="assets/plugins/bower_components/icheck/skins/all.css" rel="stylesheet">
    <!-- jQuery UI CSS -->
    <link href="assets/xcrud/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
    <!-- jQuery UI Timepicker CSS -->
    <link href="assets/xcrud/plugins/timepicker/jquery-ui-timepicker-addon.css" rel="stylesheet"/>
    <!-- custom phone number format css -->
    <link rel="stylesheet" href="assets/intl-tel-input/build/css/intlTelInput.css?1590403638580">
    <link rel="stylesheet" href="assets/intl-tel-input/examples/css/isValidNumber.css?1590403638580">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    <script>
    (function(i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function() {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-19175540-9', 'auto');
    ga('send', 'pageview');
    </script>
    <style>
        .longwidth {
            width: 33% !important;
        }
        .overlay {
            height: 100%;
            width: 100%;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0, 0.7);
            overflow-x: hidden;
            transition: 0.5s;
        }

        .overlay-content {
            position: relative;
            top: 10%;
            width: 100%;
            margin-top: 0;
            padding-bottom: 50px;
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
        .custom-wrap {
            display: inline-block;
            width: 100%;
            white-space: nowrap;
            overflow: hidden !important;
            text-overflow: ellipsis;
            font-size: 12px;
        }
        .rtol{
            float: right !important;
        }
        .pull-right {
            float: right;
        }
        .pull-left {
            float: left;
        }
    </style>
</head>
<?php 
    if(isset($this->lang->is_loaded)){
        for($i=0; $i<=sizeof($this->lang->is_loaded); $i++){
            unset($this->lang->is_loaded[$i]);
        }
    }

    $this->lang->load('message',$language);
    $site_lang = $language; 
?>
<body ng-app="myschool2" ng-controller="onlineAdmissionCtrl" ng-init="school_id=<?php echo $school_id; ?>;initCountries();initClasses(<?php echo $school_id; ?>); selectedlng='<?php echo $site_lang; ?>'" dir="<?php if($site_lang == 'urdu' || $site_lang == 'arabic') { echo 'rtl'; } ?>">
    
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
        </div>
        <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!------------------ UI Image cropper ------------------->


    

    <!-- Announcement modal start -->
    <?php if($term_conditions && !empty($term_conditions->description)) { ?>
        <div id="myNav" class="overlay">
            <div class="overlay-content" id="overlay-content">
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10" style="background-color:white; color:black; padding: 15px; border-radius: 15px; border: 1px solid red;">
                        <h3 ng-class="{'text-right': selectedlng == 'arabic' || selectedlng == 'urdu'}" style="line-height: 65px;">
                            <img src="uploads/logos/<?php echo $logo; ?>" width="100px" class="img-circle" alt="Home" ng-class="{'pull-left': selectedlng=='arabic', 'pull-right': selectedlng=='english'}"/>
                            <?php echo lang("online_admission_instructions_or_terms_and_conditions"); ?>
                        </h3>
                        <div class="col-md-12" ng-class="{'text-right': selectedlng == 'arabic' || selectedlng == 'urdu'}"><p><?php echo $term_conditions->description; ?></p></div>
                        
                        <!--<div class="col-md-12 p-l-0" ng-class="{'text-right': selectedlng == 'arabic' || selectedlng == 'urdu'}">-->
                        <!--    <?php foreach($term_conditions->attachments as $att) { ?>-->
                        <!--        <span style="display: inline-table;">-->
                        <!--            <?php if($att["type"] == "png" || $att["type"] == "jpg" || $att["type"] == "jpeg") { ?>-->
                        <!--                <a href="uploads/attachment/<?php echo $att['file']; ?>" target="_blank">-->
                        <!--                    <img src="uploads/attachment/<?php echo $att['file']; ?>" class="img-thumbnail" style="width: 50px; height: 50px; margin-right: 15px;" />-->
                        <!--                </a>-->
                        <!--            <?php } else if ($att["type"] == "docx" || $att["type"] == "xlxs" || $att["type"] == "txt" || $att["type"] == "ppt" || $att["type"] == "pptx" || $att["type"] == "pdf") { ?>-->
                        <!--                <a href="uploads/attachment/<?php echo $att['file']; ?>" title="<?php echo $att['file']; ?>">-->
                        <!--                    <img src="assets/images/pdf_file_icon.svg" style="width: 50px; height: 50px;"/><br/><span style="font-size: 12px;"><?php echo $att['file']; ?></span>-->
                        <!--                </a>-->
                        <!--            <?php } ?>-->
                        <!--        </span>-->
                        <!--    <?php } ?>-->
                        <!--</div>-->
                        
                        <div class="col-md-12 p-l-0" ng-class="{'text-right': selectedlng == 'arabic' || selectedlng == 'urdu'}">
                            <?php foreach($term_conditions->attachments as $att) { ?>
                                <span style="display: inline-table;">
                                    <?php if($att["type"] == "png" || $att["type"] == "jpg" || $att["type"] == "jpeg") { ?>
                                        <a href="uploads/attachment/<?php echo $att['file']; ?>" target="_blank">
                                            <img src="uploads/attachment/<?php echo $att['file']; ?>" class="img-thumbnail" style="width: 50px; height: 50px; margin-right: 15px;" />
                                            <br/><span class="custom-wrap" style="max-width: 60px;"><?php echo $att['file']?></span>
                                        </a>
                                    <?php } if ($att["type"] == "pdf") { ?>
                                        <a href="uploads/attachment/<?php echo $att['file']; ?>" title="<?php echo $att['file']; ?>">
                                            <img src="assets/images/pdf_file_icon.svg" style="width: 50px; height: 50px;"/><br/><span class="custom-wrap" style="font-size: 12px; max-width: 60px;"><?php echo $att['file']; ?></span>
                                        </a>
                                    <?php } ?>
                                    <?php if ($att["type"] == "docx") { ?>
                                        <a href="uploads/attachment/<?php echo $att['file']; ?>" title="<?php echo $att['file']; ?>">
                                            <img src="uploads/study_material/icons/doc_icon.png" style="width: 50px; height: 50px;"/><br/><span class="custom-wrap" style="font-size: 12px; max-width: 60px;"><?php echo $att['file']; ?></span>
                                        </a>
                                    <?php } ?>
                                    <?php if ($att["type"] == "xlsx") { ?>
                                        <a href="uploads/attachment/<?php echo $att['file']; ?>" title="<?php echo $att['file']; ?>">
                                            <img src="uploads/study_material/icons/excel_icon.png" style="width: 50px; height: 50px;"/><br/><span class="custom-wrap" style="font-size: 12px; max-width: 60px;"><?php echo $att['file']; ?></span>
                                        </a>
                                    <?php } ?>
                                    <?php if ($att["type"] == "ppt" || $att["type"] == "pptx") { ?>
                                        <a href="uploads/attachment/<?php echo $att['file']; ?>" title="<?php echo $att['file']; ?>">
                                            <img src="uploads/study_material/icons/ppt_icon.png" style="width: 50px; height: 50px;"/><br/><span class="custom-wrap" style="font-size: 12px; max-width: 60px;"><?php echo $att['file']; ?></span>
                                        </a>
                                    <?php } ?>
                                </span>
                            <?php } ?>
                        </div>
                        
                        <div class="col-md-12" ng-class="{'text-right': selectedlng == 'arabic' || selectedlng == 'urdu'}">
                            <button type="button" class="btn btn-success" ng-click="setTermAndCondition('agree')"><?php echo lang("btn_agree"); ?></button>
                            <button type="button" class="btn btn-danger" ng-click="setTermAndCondition('disagree')"><?php echo lang("btn_disagree"); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <!-- ./.Announcement modal start -->

    <!-- Preloader -->
    
    <!--<ul class="nav navbar-nav custom-nav navbar-right m-t-10 m-r-10" style="float:right;">
        <li>
            <select class="form-control" ng-model="selectedlng" ng-init="selectedlng='<?php //echo $site_lang; ?>'" ng-change="changeLanguage()">
                <option value="english">English</option>
                <option value="arabic">Arabic</option>
            </select>
        </li>
    </ul>-->

    <section id="wrapper" class="step-register">
        <div class="register-box" style="margin-top: 60px;">
            <div class="text-center">
                <a href="javascript:void(0)" class="text-center db m-b-40">
                    <img src="uploads/logos/<?php echo $logo; ?>" width="100px" alt="Home"/>
                </a>
                <h2 class="box-title" style="padding: 15px;"><?php echo lang("online_admission_form"); ?></h2>
                <!-- multistep form -->
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6"><div class="alert alert-{{finalServerResponse.status}}" ng-class="{'text-right': selectedlng == 'arabic' || selectedlng == 'urdu'}" style="margin: 15px 25px 15px 25px;" ng-show="finalServerResponse.message"><strong><?php echo lang('alert'); ?></strong> {{finalServerResponse.message}}</div></div>
                </div>
                <form id="msform" name="msform" class="form-material" novalidate="">
                    <!-- progressbar -->
                    <ul id="eliteregister" ng-class="{'p-0': selectedlng == 'arabic'}">
                        <li class="active" ng-class="{'longwidth': selectedOption=='opt2', 'rtol': selectedlng=='arabic' || selectedlng=='urdu', 'arabic-language-connector': selectedlng=='arabic' || selectedlng=='urdu'}"><?php echo lang("choose_option"); ?></li>
                        <li ng-class="{'longwidth': selectedOption=='opt2', 'rtol': selectedlng=='arabic' || selectedlng=='urdu'}"><?php echo lang("search_email"); ?></li>
                        <li ng-class="{'longwidth': selectedOption=='opt2', 'rtol': selectedlng=='arabic' || selectedlng=='urdu'}"><?php echo lang("child_personal_details"); ?></li>
                        <li ng-show="selectedOption=='opt1'" ng-class="{'rtol': selectedlng=='arabic' || selectedlng=='urdu'}"><?php echo lang("parent_personal_details"); ?></li>
                    </ul>
                    <!-- fieldsets -->
                    <fieldset>
                        <!--<h2 class="fs-title">Parent Existance</h2>-->
                        <div class="col-md-12 row m-b-10">
                            <div class="col-md-1"></div>
                            <div class="col-md-5">
                                <label><input type="radio" name="flat-radio" value="opt1" ng-model="selectedOption">
                                    <?php echo lang("new_parent"); ?></label>
                            </div>
                            <div class="col-md-5">
                                <label><input type="radio" name="flat-radio" value="opt2" ng-model="selectedOption">
                                    <?php echo lang("parent_already_exists"); ?></label>
                            </div>
                        </div>
                        <input type="button" name="next" class="next btn btn btn-info" value="<?php echo lang('btn_next'); ?>" />
                    </fieldset>
                    
                    <fieldset>
                        <!--<h2 class="fs-title">Enter Parent Email Address</h2>-->
                        <div class="alert alert-{{serverResponse.status}}" ng-class="{'text-right': selectedlng == 'arabic' || selectedlng == 'urdu'}" ng-show="serverResponse.message"><strong><?php echo lang("alert"); ?></strong> {{serverResponse.message}}</div>
                        <div class="form-group">
                            <input type="email" name="name" ng-model="parentEmail" class="form-control" placeholder="<?php echo lang('enter_parent_email_address'); ?>"/>
                        </div>
                        <input type="button" name="previous" class="previous btn btn btn-danger" value="<?php echo lang('previous'); ?>" />
                        <input type="button" name="next" ng-click="checkparent()" ng-show="parentEmail && selectedOption=='opt2' || selectedOption=='opt1'" class=" btn btn btn-info" value="<?php echo lang('btn_next'); ?>" />
                        <input type="button" name="next" ng-show="selectedOption=='opt1'" id="sNext" class="next btn btn btn-info" style="display: none;"  value="<?php echo lang('btn_next'); ?>" />
                    </fieldset>

                    <fieldset>
                        <!--<h2 class="fs-title">CHILD PERSONAL DETAILS</h2>-->
                        <!--<hr style="border-color: black">-->
                        <div ng-show="selectedOption==='opt2'">
                            <table width="100%">
                                <tr>
                                    <td ng-repeat="child in childrens" style="vertical-align:top;">
                                        <img src="uploads/user/{{child.avatar}}" class="img-circle m-b-5" width="60px" alt="profile.png" style="border: 1px solid #01c0c8; padding:5px;" />
                                        <br/><strong>{{child.name}}</strong><br/>
                                        <small>{{child.class_name}}-{{child.batch_name}}</small>
                                    </td>
                                </tr>
                            </table>
                            <h2 class="fs-title m-t-15" ng-class="{'text-left': selectedlng=='english', 'text-right': selectedlng=='arabic'}"><?php echo lang("add_child"); ?></h2>
                        </div>

                        <div class="row">
                            <div class="col-md-6" ng-class="{'text-left': selectedlng=='english', 'text-right': selectedlng=='arabic'}">
                                <span>
                                    <img style="width: 100px; height:100px; margin-bottom:5px;" ng-show="resImageDataURI" ng-src="{{resImageDataURI}}"/>
                                </span>
                                <div class="form-group" ng-class="{'text-left': selectedlng=='english', 'text-right': selectedlng=='arabic'}">
                                    <button type="button" data-toggle="modal" data-target=".bs-student-add-modal-lg" class="btn btn-info"/><i class="fa fa-image"></i> <?php echo lang('choose_image') ?></button>
                                </div>
                            </div>
                        
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="<?php echo lang('lbl_religion'); ?>" ng-model="formModel.child_religion">
                                </div>
                            </div>
                        
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="<?php echo lang('lbl_full_name'); ?>" ng-model="formModel.child_name" required>
                                </div>
                            </div>
                        
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select ng-model="formModel.child_gender" class="form-control" required>
                                        <option value=""><?php echo lang('select_gender'); ?></option>
                                        <option value="male"><?php echo lang('option_male'); ?></option>
                                        <option value="female"><?php echo lang('option_female'); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control mydatepicker-autoclose" ng-model="formModel.child_dob" placeholder="DD/MM/YYYY" ng-pattern="/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <select ng-model="formModel.child_blood_group" class="form-control">
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

                            <div class="col-md-6">    
                                <div class="form-group">
                                    <input type="text" placeholder="<?php echo lang('birth_place'); ?>" class="form-control" ng-model="formModel.child_birth_place">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <select name="countries" ng-model="formModel.child_nationality_id" class="form-control" required="">
                                        <option value=""><?php echo lang('select_nationality'); ?></option>
                                        <option ng-repeat="c in countries" value="{{c.id}}">{{c.country_name}}</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" placeholder="<?php echo lang('lbl_language'); ?>" class="form-control" ng-model="formModel.child_language">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" placeholder="<?php echo lang('national_number'); ?>" class="form-control" ng-model="formModel.child_nic">
                                </div>
                            </div>
                        
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select name="class" class="form-control" ng-model="formModel.child_class_id"  required>
                                        <option value=""><?php echo lang('select_course'); ?></option>
                                        <option ng-repeat="cl in classes" value="{{cl.id}}">{{cl.name}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="email" class="form-control" ng-model="formModel.child_email" placeholder="<?php echo lang('enter_child_email_address'); ?>" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-xs-12" id="remove_instance" style="margin-bottom: 20px; position: absolute;">
                                        <input class="form-control" id="phone" type="tel" ng-model="formModel.child_phone_no">
                                    </div>
                                    <span id="valid-msg" class="hide">✓ Valid</span>
                                    <span id="error-msg" class="hide"></span>
                                </div>
                            </div>
                            
                            
                            
                            
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" ng-model="formModel.child_city" placeholder="<?php echo lang('enter_child_city'); ?>">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" ng-model="formModel.child_address" placeholder="<?php echo lang('enter_child_address'); ?>">
                                </div>
                            </div>
                            
                            <div class="col-sm-12" style="display: none;">
                                <input class="form-control" id="country_change_code1" type="text" value="">
                            </div>
                        </div>
                        <input type="button" name="previous" class="previous btn btn btn-danger" id="emailcheckprevbtn" value="<?php echo lang('previous'); ?>" />
                        <input type="button" name="next" ng-show="selectedOption=='opt1'" ng-disabled="!formModel.child_name || formModel.child_gender===undefined || formModel.child_nationality_id==undefined || formModel.child_class_id==undefined" class="next btn btn-info" value="<?php echo lang('btn_next'); ?>" />
                        <input type="button" name="submit" ng-show="selectedOption=='opt2'" ng-disabled="!formModel.child_name || formModel.child_gender===undefined || formModel.child_nationality_id==undefined || formModel.child_class_id==undefined" ng-click="details()" class="btn btn btn-success" value="<?php echo lang('submit_application'); ?>" />
                    </fieldset>
                    
                    <fieldset>
                        <!--<h2 class="fs-title">PARENT PERSONAL DETAILS</h2>-->
                        
                        <div class="row" style="margin-bottom: 30px;">
                            <!--<div class="col-md-6">
                                <img src="uploads/user/{{formModel.avatar}}" ng-show="formModel.avatar !== ''" width="100px" style="border: 1px inset;"/>
                                <div class="form-group">
                                    <input type="file" name="parent_image" class="form-control" fileread="formModel.avatar"/>
                                </div>
                            </div>-->

                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" ng-model="formModel.parent_name" placeholder="<?php echo lang('enter_parent_name'); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control mydatepicker-autoclose" placeholder="DD/MM/YYYY" ng-pattern="/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/" ng-model="formModel.parent_dob">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select class="form-control" ng-model="formModel.parent_gender" required>
                                        <option value="" selected="selected"><?php echo lang("select_gender"); ?></option>
                                        <option value="male"><?php echo lang("option_male"); ?></option>
                                        <option value="female"><?php echo lang("option_female"); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" ng-model="formModel.parent_occupation" placeholder="<?php echo lang('enter_parent_occupation'); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="number" class="form-control" ng-model="formModel.parent_income" placeholder="<?php echo lang('enter_parent_monthly_income'); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" ng-model="formModel.parent_street" placeholder="<?php echo lang('enter_parent_street_address'); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" ng-model="formModel.parent_ic_number" placeholder="<?php echo lang('enter_parent_ic_number'); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select name="countries" class="form-control" ng-model="formModel.parent_country_id" required="">
                                        <option value=""><?php echo lang("select_a_country"); ?></option>
                                        <option ng-repeat="c in countries" value="{{c.id}}">{{c.country_name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" ng-model="formModel.parent_city" placeholder="<?php echo lang("enter_parent_city"); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-xs-12" id="remove_phone2_instance" style="margin-bottom: 20px; position: absolute;">
                                        <input type="tel" class="form-control" id="phone2" maxlength="17" ng-model="formModel.parent_phone_no">
                                    </div>
                                    <span id="valid-msg2" class="hide">✓ Valid</span>
                                    <span id="error-msg2" class="hide"></span>
                                </div>
                            </div>
                            
                            <div class="col-sm-12" style="display: none;">
                                <input class="form-control" id="country_change_code2" type="text" value="">
                            </div>
                            
                            <div class="col-md-12">
                                <h2 class="fs-title" ng-class="{'text-left': selectedlng=='english', 'text-right': selectedlng=='arabic'}"><?php echo lang("secondary_guardian_details"); ?></h2>
                                <!--<hr style="border-color: black">-->
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" ng-model="formModel.s_g_name" placeholder="<?php echo lang('enter_second_guardian_name'); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select class="form-control" ng-model="formModel.s_g_relation">
                                        <option value="" selected="selected"><?php echo lang("select_relation"); ?></option>
                                        <option value="father"><?php echo lang("father"); ?></option>
                                        <option value="mother"><?php echo lang("mother"); ?></option>
                                        <option value="uncle"><?php echo lang("uncle"); ?></option>
                                        <option value="brother"><?php echo lang("brother"); ?></option>
                                        <option value="grand_father"><?php echo lang("grand_father"); ?></option>
                                        <option value="grand_mother"><?php echo lang("grand_mother"); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-xs-12" id="remove_phone3_instance" style="margin-bottom: 20px; position: absolute;">
                                        <input type="text" class="form-control" id="phone3" ng-model="formModel.s_g_phone_no">
                                    </div>
                                    <span id="valid-msg3" class="hide">✓ Valid</span>
                                    <span id="error-msg3" class="hide"></span>
                                </div>
                            </div>
                            
                            <div class="col-sm-12" style="display: none;">
                                <input class="form-control" id="country_change_code3" type="text" value="">
                            </div>
                            
                            <div class="col-sm-12" style="display: none;">
                                <input class="form-control" id="school_country_code" type="text" value="<?php echo $school_country_code; ?>">
                            </div>
                            
                            
                            
                        </div>
                        

                        <input type="button" name="previous" class="previous btn btn btn-danger" value="<?php echo lang('previous'); ?>" />
                        <input type="button" name="submit" ng-disabled="!formModel.parent_name || formModel.parent_gender===undefined || formModel.parent_country_id===undefined" ng-click="details()" class="btn btn btn-success" value="<?php echo lang('submit_application')?>" />
                    </fieldset>
                </form>
                <div class="clear"></div>
            </div>
        </div>
    </section>
    <!-- jQuery -->
    <script src="assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Angular js -->
    <script src="assets/angularjs/angular.min.js"></script>
    <!-- Angular file uploader -->
    <script src="assets/angular-file-upload/dist/angular-file-upload.min.js" type="text/javascript"></script>
    <!-- Angular Image uploader -->
    <script src="assets/angularjs/imageupload.js" type="text/javascript"></script>
    <!-- Angular auto validator lib -->
    <script src="assets/angularjs-auto-validator/dist/jcs-auto-validate.min.php"></script>
    <!-- UI-Cropper -->
    <script type="text/javascript" src="assets/ui_cropper/compile/minified/ui-cropper.js?v=1"></script>
    <!-- dirPagination -->
    <script src="assets/js/dirPagination.js"></script>
    <!-- App js -->
    <script src="assets/js/app.php?v=<?= date("h.i.s") ?>"></script>
    <!-- my custom datepicker method -->
    <script type="text/javascript">
        if ($(".xcrud-container").length == 0) {
            $.getScript("assets/xcrud/plugins/jquery-ui/jquery-ui.min.js").done(function () {
                $.getScript("assets/xcrud/plugins/timepicker/jquery-ui-timepicker-addon.js").done(function () {
                    var datepicker_config = {
                        changeMonth: true,
                        changeYear: true,
                        showSecond: false,
                        controlType: 'select',
                        yearRange: "-50:+10",
                        dateFormat: 'dd/mm/yy',
                        timeFormat: 'hh:mm tt'
                    };

                    $(".mydatepicker-autoclose").datepicker(datepicker_config);
                });
            });
        }
        if ($.trim($('.hint').html()).length) {
            $('.hint').show();
        }
    </script>
    <!-- Bootstrap Core JavaScript -->
    <script src="assets/bootstrap/dist/js/tether.min.js"></script>
    <script src="assets/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/plugins/bower_components/bootstrap-extension/js/bootstrap-extension.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="assets/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <script src="assets/plugins/bower_components/register-steps/jquery.easing.min.js"></script>
    <script src="assets/plugins/bower_components/register-steps/register-init.js"></script>
    <!-- icheck -->
    <script src="assets/plugins/bower_components/icheck/icheck.min.js"></script>
    <script src="assets/plugins/bower_components/icheck/icheck.init.js"></script>
    <!--slimscroll JavaScript -->
    <script src="assets/js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="assets/js/waves.js"></script>
    <!--Style Switcher -->
    <script src="assets/plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
    <!-- intl-tel-input js -->
    <script src="assets/intl-tel-input/examples/js/prism.js"></script>
    <script src="assets/intl-tel-input/build/js/intlTelInput.js?1590403638580"></script>
    
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js" integrity="sha512-DNeDhsl+FWnx5B1EQzsayHMyP6Xl/Mg+vcnFPXGNjUZrW28hQaa1+A4qL9M+AiOMmkAhKAWYHh1a+t6qxthzUw==" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" integrity="sha512-gxWow8Mo6q6pLa1XH/CcH8JyiSDEtiwJV78E+D+QP0EVasFs8wKXq16G8CLD4CJ2SnonHr4Lm/yY2fSI2+cbmw==" crossorigin="anonymous" />
    
    <script>
    
    var country_value1="";
    var country_value2="";
    var country_value3="";
    
        $(document).ready(function(){
            var input = document.querySelector("#phone"),
            errorMsg = document.querySelector("#error-msg"),
            validMsg = document.querySelector("#valid-msg");

            var input2 = document.querySelector("#phone2"),
            errorMsg2 = document.querySelector("#error-msg2"),
            validMsg2 = document.querySelector("#valid-msg2");

            var input3 = document.querySelector("#phone3"),
            errorMsg3 = document.querySelector("#error-msg3"),
            validMsg3 = document.querySelector("#valid-msg3");

            // here, the index maps to the error code returned from getValidationError - see readme
            var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];
            // initialise plugin
            var iti = window.intlTelInput(input, {
                utilsScript: "assets/intl-tel-input/build/js/utils.js?1590403638580"
            });

            var iti2 = window.intlTelInput(input2, {
                utilsScript: "assets/intl-tel-input/build/js/utils.js?1590403638580"
            });

            var iti3 = window.intlTelInput(input3, {
                utilsScript: "assets/intl-tel-input/build/js/utils.js?1590403638580"
            });

            var reset = function() {
                input.classList.remove("error");
                errorMsg.innerHTML = "";
                errorMsg.classList.add("hide");
                validMsg.classList.add("hide");
            };
            
            
            document.getElementById('remove_instance').textContent = '';
            document.getElementById('remove_instance').innerHTML = '<input class="form-control" style="width:100%;" type="tel" id="phone" ng-model="formModel.child_phone_no" >';
            
           
            var us_country=$('#school_country_code').val();
            // var us_country="PK";
            
            globalval= us_country;
            var input = document.querySelector("#phone");
            var iti = window.intlTelInput(input, {
            initialCountry: globalval,
            separateDialCode:true,
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/utils.js",
            });
            console.log(iti); 
            
            country_value1 = iti.getSelectedCountryData().iso2;
            $('#country_change_code1').val(country_value1);
            
            input.addEventListener('countrychange', function(e) {
                country_value1 = iti.getSelectedCountryData().iso2;
                $('#country_change_code1').val(country_value1);
            });
            

            // on blur: validate
            // input.addEventListener('blur', function() {
            // reset();
            // if (input.value.trim()) {
            //         if (iti.isValidNumber()) {
            //             validMsg.classList.remove("hide");
            //         } else {
            //             input.classList.add("error");
            //             var errorCode = iti.getValidationError();
            //             errorMsg.innerHTML = errorMap[errorCode];
            //             errorMsg.classList.remove("hide");
            //         }
            //     }
            // });
            // on keyup / change flag: reset
            // input.addEventListener('change', reset);
            // input.addEventListener('keyup', reset);

            

            var reset2 = function() {
                input2.classList.remove("error");
                errorMsg2.innerHTML = "";
                errorMsg2.classList.add("hide");
                validMsg2.classList.add("hide");
            };

            
            document.getElementById('remove_phone2_instance').textContent = '';
            document.getElementById('remove_phone2_instance').innerHTML = '<input class="form-control" style="width:100%;" type="tel" id="phone2" ng-model="formModel.parent_phone_no" >';
            // var us_country=$('#school_country_code').val();
            var us_country="PK";
            
            globalval= us_country;
            var input2 = document.querySelector("#phone2");
            var iti2 = window.intlTelInput(input2, {
            initialCountry: globalval,
            separateDialCode:true,
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/utils.js",
            });
            console.log(iti2);
            
            country_value2 = iti2.getSelectedCountryData().iso2;
            $('#country_change_code2').val(country_value2);
            
            input2.addEventListener('countrychange', function(e) {
                country_value2 = iti2.getSelectedCountryData().iso2;
                $('#country_change_code2').val(country_value2);
            });
            
            

            // on blur: validate
            // input2.addEventListener('blur', function() {
            // reset2();
            // if (input2.value.trim()) {
            //         if (iti2.isValidNumber()) {
            //             validMsg2.classList.remove("hide");
            //         } else {
            //             input2.classList.add("error");
            //             var errorCode = iti2.getValidationError();
            //             errorMsg2.innerHTML = errorMap[errorCode];
            //             errorMsg2.classList.remove("hide");
            //         }
            //     }
            // });
            // on keyup / change flag: reset
            // input2.addEventListener('change', reset2);
            // input2.addEventListener('keyup', reset2);

            var reset3 = function() {
                input3.classList.remove("error");
                errorMsg3.innerHTML = "";
                errorMsg3.classList.add("hide");
                validMsg3.classList.add("hide");
            };
            
            document.getElementById('remove_phone3_instance').textContent = '';
            document.getElementById('remove_phone3_instance').innerHTML = '<input class="form-control" style="width:100%;" type="tel" id="phone3" ng-model="formModel.s_g_phone_no" >';
            
           
            // var us_country=$('#school_country_code').val();
            var us_country="PK";
            
            globalval= us_country;
            var input3 = document.querySelector("#phone3");
            var iti3 = window.intlTelInput(input3, {
            initialCountry: globalval,
            separateDialCode:true,
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/utils.js",
            });
            console.log(iti3);
            
            country_value3 = iti3.getSelectedCountryData().iso2;
            $('#country_change_code3').val(country_value3);
            
            input3.addEventListener('countrychange', function(e) {
                country_value3 = iti3.getSelectedCountryData().iso2;
                $('#country_change_code3').val(country_value3);
            });
            // on blur: validate
            // input3.addEventListener('blur', function() {
            // reset3();
            // if (input3.value.trim()) {
            //         if (iti3.isValidNumber()) {
            //             validMsg3.classList.remove("hide");
            //         } else {
            //             input3.classList.add("error");
            //             var errorCode = iti3.getValidationError();
            //             errorMsg3.innerHTML = errorMap[errorCode];
            //             errorMsg3.classList.remove("hide");
            //         }
            //     }
            // });
            // on keyup / change flag: reset
            // input3.addEventListener('change', reset3);
            // input3.addEventListener('keyup', reset3);
        });
    </script>
</body>

</html>
