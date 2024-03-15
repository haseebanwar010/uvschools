<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<?php $userData = $this->session->userdata('userdata'); ?>
<!-- Page Content -->
<div id="page-wrapper" ng-controller="profileEditController">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('h_profile'); ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="#"><?php echo lang('lbl_dashboard'); ?></a></li>
                    <li class="active"><?php echo lang('h_profile'); ?></li>

                </ol>
            </div>
        </div>
        <!-- /.row -->
        <!-- Page Content -->
        <div class="hint"><?php echo lang('help_profile_edit'); ?></div>
        
        
        <!------------------ UI Image cropper ------------------->

        <!-- sample modal content -->
        <div id="myModal" class="modal fade bs-user-profile-edit-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel"><?php echo lang('image_cropper');?></h4>
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

                                        <a data-ng-click="callTestFuntion()" data-dismiss="modal" aria-hidden="true" class="btn btn-info mr-1"><i class=""></i> <?php echo lang('crop');?></a>
                                        <a class="btn btn-success" data-dismiss="modal" aria-hidden="true"><i class=""></i> <?php echo lang('lbl_close');?></a>
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
        
        <!-- .row -->
        <?php echo $this->session->flashdata('success-image'); ?>
        <?php echo $this->session->flashdata('success'); ?>
        <div class="alert alert-success " id="change_alert" style="display: none"><p id="alert_msg"></p></div>

        <div class="row">
            <div class="col-md-3 col-xs-12">
                <div class="white-box">
                    <input type="hidden" name="user_id" id="user_id" value="<?php echo $profile->id; ?>" />
                    <div class="user-bg">
                        <?php if ($profile->avatar) { ?>
                            <span ng-show="resImageDataURI">
                                <img width="100%" alt="user" ng-src="{{resImageDataURI}}"/>
                            </span>
                            <span ng-show="!resImageDataURI">
                                <img width="100%" alt="user" src="<?php echo base_url() ?>uploads/user/<?php echo $profile->avatar; ?>">
                            </span>
                            
                        <?php } else { ?>
                            <img width="100%" alt="user" src="assets/plugins/images/large/img1.jpg">
                        <?php } ?>
                        <div class="overlay-box">
                            <div class="user-content">
                                <?php if ($profile->avatar) { ?>
                                    <span ng-show="resImageDataURI">
                                        <img class="thumb-lg img-circle" alt="img" style="width: 100px; height: 100px;" ng-src="{{resImageDataURI}}"/>
                                    </span>
                                    <span ng-show="!resImageDataURI">
                                        <img src="<?php echo base_url() ?>uploads/user/<?php echo $profile->avatar; ?>" class="thumb-lg img-circle" alt="img">   
                                    </span>
                                <?php } else { ?>
                                    <img src="assets/plugins/images/users/genu.jpg" class="thumb-lg img-circle" alt="img">
                                <?php } ?>
                                <br>
                                <br>
                                <button type="button" data-toggle="modal" data-target=".bs-user-profile-edit-modal-lg" class="fcbtn btn btn-default btn-primary btn-1d btn-rounded"/><i class="fa fa-image"></i> <?php echo lang('choose_image');?></button>
                                <!--<input type="hidden" name="editImage" id="edit-image" value="" />-->
<!--                                <form id="image-form" action="<?php echo site_url('profile/uploadImage'); ?>" method="POST" enctype="multipart/form-data">
                                    <input type="file" id="fileupload" name="profile_image" style="display: none;"/>
                                    <button type="button" class="fcbtn btn btn-default btn-primary btn-1d btn-rounded" onclick="$('#fileupload').click()"><?php echo lang('btn_change_image'); ?></button>
                                    <br><small style="color: #f8f5f4;"><?php echo lang('note_profile_image'); ?></small>
                                </form>-->

                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-9 col-xs-12">
                <div class="white-box">
                    <ul class="nav customtab nav-tabs" role="tablist">
                        <li role="presentation" class="nav-item">
                            <a href="#personal" class="nav-link <?php if($this->session->flashdata('ep_selected_tab') == 'personal') { echo 'active';} else if(empty($this->session->flashdata('ep_selected_tab'))){ echo 'active';  } ?>" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="true">
                                <span class="visible-xs"><i class="fa fa-user"></i></span>
                                <span class="hidden-xs"> <?php echo lang('tab_peronal'); ?></span>
                            </a>
                        </li>
                        <li role="presentation" class="nav-item">
                            <a href="#contacttab" class="nav-link <?php if($this->session->flashdata('ep_selected_tab') == 'contact') { echo 'active';} ?>" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false">
                                <span class="visible-xs"><i class="fa fa-phone"></i></span> 
                                <span class="hidden-xs"><?php echo lang('tab_contact_info'); ?></span>
                            </a>
                        </li>
                        <li role="presentation" class="nav-item">
                            <a href="#address" class="nav-link <?php if($this->session->flashdata('ep_selected_tab') == 'address') { echo 'active';} ?>" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false">
                                <span class="visible-xs"><i class="fa fa-home"></i></span> 
                                <span class="hidden-xs"><?php echo lang('tab_address'); ?></span>
                            </a>
                        </li>
                        <li role="presentation" class="nav-item">
                            <a href="#password" class="nav-link" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false">
                                <span class="visible-xs"><i class="fa fa-key"></i></span> 
                                <span class="hidden-xs"><?php echo lang('tab_password');?></span>
                            </a>
                        </li>
                        <li role="presentation" class="nav-item">
                            <a href="#epothers" class="nav-link" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false">
                                <span class="visible-xs"><i class="fa fa-bars"></i></span> 
                                <span class="hidden-xs"><?php echo lang('lbl_other');?></span>
                            </a>
                        </li>
                    </ul>
                    
                    <div class="tab-content">
                        <div class="tab-pane <?php if($this->session->flashdata('ep_selected_tab') == 'personal') { echo 'active';} else if(empty($this->session->flashdata('ep_selected_tab'))){ echo 'active';  } ?>" id="personal">
                            <div class="hint"><?php echo lang('help_profile_personal'); ?></div>
                            <form action="<?php echo site_url('profile/update'); ?>" name="frm" method="POST" class="form-material " ng-app="" ng-init = "name = '<?php echo $profile->name; ?>'; designation = '<?php echo $profile->job_title; ?>';dob = '<?php echo ($profile->dob=='0000-00-00')?"":to_html_date($profile->dob); ?>'; qualification= '<?php echo $profile->qualification; ?>'; nationality = '<?php echo $profile->nationality; ?>'; passport='<?php echo $profile->passport_number; ?>'; icno='<?php echo $profile->ic_number; ?>'; language='<?php echo $profile->language; ?>'" novalidate>
                                <input type="hidden" name="info_type" value="personal"/>
                                <div class="form-body">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_full_name'); ?></label>
                                                <input type="text" name="name" class="form-control" required="required" ng-model="name"  ng-pattern="/^[a-z A-Z u0600-\u06FF]*$/">

                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_designation'); ?></label>
                                                <input type="text" name="job_title" class="form-control" required="required" ng-model="designation">  </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <!--/row-->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_gender'); ?></label>
                                                <select class="form-control" name="gender">
                                                    <option value="male" <?php
                                                    if ($profile->gender == 'male') {
                                                        echo 'selected="selected"';
                                                    }
                                                    ?>><?php echo lang('option_male');?></option>
                                                    <option value="female" <?php
                                                    if ($profile->gender == 'female') {
                                                        echo 'selected="selected"';
                                                    }
                                                    ?>><?php echo lang('option_female');?></option>
                                                </select> </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_dob'); ?></label>
                                                <input type="text" name="dob" class="dob-edit form-control mydatepicker-autoclose" ng-model="dob" required="required" > </div>
                                        </div>
                                        <!--/span-->

                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_qualification'); ?></label>
                                                <input type="text" name="qaulification" required="required" class="form-control" ng-model="qualification"></div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_nationality'); ?></label>
                                                
                                                <select class="form-control  form-control-line" name="nationality">
                                                    <?php
                                                    if (count($countries) > 0) {
                                                        foreach ($countries as $country) {
                                                            ?>
                                                            <option value="<?php echo $country->id; ?>" <?php
                                                            if ($profile->nationality == $country->id) {
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
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_passport_number'); ?></label>
                                                <input type="text" name="passport_number" class="form-control" ng-model="passport" pattern="^[a-zA-Z0-9]+$"></div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_ic_number'); ?></label>
                                                <input type="text" name="ic_number"  class="form-control" ng-model="icno" pattern="^[0-9]+$">  </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label "><?php echo lang('lbl_marital_status'); ?></label>
                                                <select class="form-control form-control-line" name="marital_status">
                                                    <option value="single"  <?php
                                                    if ($profile->marital_status == 'single') {
                                                        echo 'selected="selected"';
                                                    }
                                                    ?>><?php echo lang('option_single');?></option>
                                                    <option value="married" <?php
                                                    if ($profile->marital_status == 'married') {
                                                        echo 'selected="selected"';
                                                    }
                                                    ?>><?php echo lang('option_married');?></option>
                                                </select> </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_language') ?></label>
                                                <select class="form-control" required="" name="language" ng-model="language">
                                                    <option value=""><?php echo lang('please_language') ?></option>
                                                    <option value="english">English</option>
                                                    <option value="arabic">العَرَبِيَّة</option>
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div>
                                        <button  type="submit" class="btn-1d  fcbtn btn  btn-primary btn-1b pull-right"  ng-disabled="frm.$invalid"><?php echo lang('lbl_update_personal_info'); ?></button> 
                                        <div class="clear"></div>
                                    </div> 
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane <?php if($this->session->flashdata('ep_selected_tab') == 'contact') { echo 'active';} ?>" id="contacttab">
                            <div class="hint"><?php echo lang('help_profile_contact'); ?></div>
                            <form action="<?php echo site_url('profile/update'); ?>" method="POST" name="frm2" ng-app class="form-material" id="settings">
                                <input type="hidden" name="info_type" value="contact" ng-init = "email='<?php echo $profile->email; ?>'; phone='<?php echo $profile->mobile_phone; ?>'; office_phone='<?php echo $profile->office_phone; ?>';fax='<?php echo $profile->fax; ?>'"; />
                                <div class="form-body">
                                    <div class="row">
                                    
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_phone_number'); ?></label>
                                                <input type="text" name="mobile_phone" required="required" class="form-control" pattern="^\+?\d+$" ng-model="phone">  </div>
                                        </div>

                                           <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_office_phone_number'); ?></label>
                                                <input type="text" name="office_phone" required="required" class="form-control" pattern="^[0-9]+$" ng-model="office_phone"></div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                     
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('lbl_fax'); ?></label>
                                                <input type="text" name="fax" class="form-control" pattern="^[0-9]+$" ng-model="fax">  </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <!--/span-->

                                    <!--/row-->
                                    <div>
                                        <button  type="submit" class="btn-1d  fcbtn btn  btn-primary btn-1b pull-right" ng-disabled="frm2.$invalid"><?php echo lang('lbl_update_contact'); ?></button>
                                        <div class="clear"></div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="tab-pane <?php if($this->session->flashdata('ep_selected_tab') == 'address') { echo 'active';} ?>" id="address">
                            <div class="hint"><?php echo lang('help_profile_address'); ?></div>
                            <form action="<?php echo site_url('profile/update'); ?>" method="POST" name="frm3" class="form-material" id="other-detail" ng-app ng-init="address = '<?php echo $profile->address; ?>' ; city = '<?php echo $profile->city; ?>' ">
                                <input type="hidden" name="info_type" value="address"/>
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-12 ">
                                            <div class="form-group">
                                                <label><?php echo lang('lbl_address'); ?></label>
                                                <input type="text" name="address" required="required" class="form-control" pattern="([a-zA-Z\u0600-\u06FF0-9 ]| |/|\\|@|#|\$|%|&|,|.)+" ng-model='address'> </div>
                                        </div>
                                    </div>
                                    <!--/row-->
                                    <div class="row">

                                        <div class="col-sm-12">
                                            <div class="form-group ">
                                                <label><?php echo lang('lbl_country'); ?></label>
                                                <select class="form-control  form-control-line" name="country">
                                                    <?php
                                                    if (count($countries) > 0) {
                                                        foreach ($countries as $country) {
                                                            ?>
                                                            <option value="<?php echo $country->id; ?>" <?php
                                                            if ($profile->country == $country->id) {
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

                                    </div>
                                    <!--/row-->
                                    <div class="row">
                                        <div class="col-md-12 ">
                                            <div class="form-group">
                                                <label><?php echo lang('lbl_city'); ?></label>
                                                <input type="text" name="city" required="required" class="form-control" ng-pattern="/^[a-zA-Z\u0600-\u06FF ]*$/" ng-model="city"> </div>
                                        </div>
                                    </div>
                                    <!--/row-->
                                    <div>
                                        <button  type="submit" class="btn-1d  fcbtn btn  btn-primary btn-1b pull-right" ng-disabled="frm3.$invalid"><?php echo lang('lbl_update_address'); ?></button>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <div class="tab-pane" id="password">
                            <div class="hint"><?php echo lang('help_profile_password'); ?></div>
                            <form class="form-material" id="change_password">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-9 ">
                                            <div class="form-group">
                                                <label><?php echo lang('current_password') ?></label>
                                                <input type="password" name="current_password" id="current_password" placeholder="" required="" class="form-control"> </div>
                                        </div>
                                    </div>
                                    <!--/row-->
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label><?php echo lang('new_password') ?></label>
                                                <input type="password" name="password" id="new_password" placeholder="" required="" class="form-control"> </div>
                                        </div>

                                    </div>
                                    <!--/row-->

                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label><?php echo lang('confirm_password') ?></label>
                                                <input type="password" name="confirm_password" id="confirm_password" required="" placeholder="" class="form-control"> </div>
                                        </div>

                                    </div>
                                    <!--/row-->
                                    <div>
                                        <button type="submit" class="btn-1d  fcbtn btn  btn-primary btn-1b pull-right" id="change_password_btn"><?php echo lang('lbl_update_password') ?></button>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <div class="tab-pane <?php if($this->session->flashdata('ep_selected_tab') == 'epothers') { echo 'active';} ?>" id="epothers">
                            <form action="<?php echo site_url('profile/update'); ?>" method="POST" class="form-material ">
                                <input type="hidden" name="info_type" value="epothers"/>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo lang('font_size');?></label>
                                            <select  type="text" name="fontsize"class="form-control">
                                                <option value="10" <?php if($profile->font_size == 10){ echo "selected"; } ?>>10px</option>
                                                <option value="12" <?php if($profile->font_size == 12){ echo "selected"; } ?>>12px</option>
                                                <option value="14" <?php if($profile->font_size == 14){ echo "selected"; } ?>>14px</option>
                                                <option value="16" <?php if($profile->font_size == 16){ echo "selected"; } ?>>16px</option>
                                                <option value="18" <?php if($profile->font_size == 18){ echo "selected"; } ?>>18px</option>
                                                <option value="20" <?php if($profile->font_size == 20){ echo "selected"; } ?>>20px</option>
                                            </select>
                                            <!--<input pattern="([0-9]| |/|\\|@|#|\$|%|&|,|.)+" />-->
                                            <small><?php echo lang('default_font');?></small>
                                        </div>
                                    </div>
                                </div>
                                <!--/row-->
                                <input type="submit" class="btn btn-primary" value="<?php echo lang('update_settings');?>" />
                            </form>
                            
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <!--page content end-->
</div>
<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>
<script type="text/javascript">

    $(document).ready(function () {

        $('#change_password_btn').click(function () {
            $('change_alert').hide();
            Loading("#password", "Loading", "", "show");
            event.preventDefault();
            var formdata = $('#change_password').serialize();
            $.ajax({
                type: 'POST',
                data: formdata,
                dataType: "json",
                url: '<?php echo site_url('profile/changePassword/') ?>',
                success: function (response) {
                    console.log(response);

                    if (response.success) {
                        Loading("#password", "Loading", "", "hide");

                        $('#change_alert').removeClass('alert-danger').addClass('alert-success').show();
                        $('#alert_msg').html("<?php echo lang('pswrd_changed_success') ?>");

                        $('#current_password').val('');
                        $('#new_password').val('');
                        $('#confirm_password').val('');



                    } else {
                        Loading("#password", "Loading", "", "hide");
                        $('#change_alert').removeClass('alert-success').addClass('alert-danger').show();

                        $('#alert_msg').html(response.error);
                    }

                }
            });
        })

    });
    $(function () {
        $("#fileupload").change(function () {
            $("#image-form").submit();
        });
    });
</script>
