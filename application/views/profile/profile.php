<?php
$UserData = $this->session->userdata('userdata');
$profile = $this->profile_modal->getUserProfileDetail($UserData['user_id']);
$uri = $this->uri->segment(2);
$role_id = $UserData['role_id'];
?>
<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('h_profile');?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="#"><?php echo lang('lbl_dashboard');?></a></li>
                    <li class="active"><?php echo lang('h_profile');?></li>

                </ol>
            </div>
        </div>
        <!-- modal -->

        <div class="modal fade" id="requestReasonModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" id="addfeetype-content" style="border-radius: 16px" >
                    <div class="panel panel-primary" style="border-radius: 16px">
                        <div class="panel-heading" id="mySmallModalLabel" style="border-top-right-radius: 16px; border-top-left-radius: 16px"><?php echo lang('lbl_update_password') ?>
                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                            <div class="panel-body">   
                               <div class="tab-pane" id="password">
                            
                            <form class="form-material" id="change_password">
                                <div clas s="form-body">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <!-- /.row -->
        <!-- Page Content -->
        <div class="hint"><?php echo lang('help_profile'); ?></div>
        <!-- .row -->
        
        <div class="row">
            <div class="col-md-3 col-xs-12">
                <div class="white-box">
                    <div class="user-bg">
                        <?php if($profile->avatar){ ?>
                        <img width="100%" alt="user" src="<?php echo base_url()?>uploads/user/<?php echo $profile->avatar;?>">
                        <?php }else{ ?>
                        <img width="100%" alt="user" src="assets/plugins/images/large/img1.jpg">
                        <?php } ?>
                        <div class="overlay-box">
                            <div class="user-content">
                                <?php if($profile->avatar){ ?>
                                <img src="<?php echo base_url()?>uploads/user/<?php echo $profile->avatar;?>" class="thumb-lg img-circle" alt="img">   
                             <?php }else{ ?>
                                <img src="assets/plugins/images/users/genu.jpg" class="thumb-lg img-circle" alt="img">
                                <?php } ?>
                                <h4 class="text-white"><?php echo $profile->name; ?></h4>
                                <h5 class="text-white"><?php echo $profile->job_title; ?></h5></div>
                        </div>
                    </div>
<!--                    <div class="user-btm-box">
                        <div class="col-md-4 col-sm-4 text-center">
                            <p class="text-purple"><i class="ti-facebook"></i></p>
                            <h3>258</h3></div>
                        <div class="col-md-4 col-sm-4 text-center">
                            <p class="text-blue"><i class="ti-twitter"></i></p>
                            <h3>125</h3></div>
                        <div class="col-md-4 col-sm-4 text-center">
                            <p class="text-danger"><i class="ti-dribbble"></i></p>
                            <h3>556</h3>
                        </div>
                    </div>-->
                </div>
            </div>
            <div class="col-md-9 col-xs-12">
                <div class="white-box">
                    <ul class="nav customtab nav-tabs" role="tablist">
                        <li role="presentation" class="nav-item">
                            <a href="#personal" class="nav-link active" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true">
                                <span class="visible-xs"><i class="fa fa-user"></i></span><span class="hidden-xs"> <?php echo lang('tab_peronal');?></span></a>
                        </li>
                        <li role="presentation" class="nav-item">
                           <a href="#contact1" class="nav-link" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false">
                               <span class="visible-xs"><i class="fa fa-phone"></i></span> <span class="hidden-xs"><?php echo lang('tab_contact_info');?></span></a>
                       </li>
                        <li role="presentation" class="nav-item">
                            <a href="#address" class="nav-link" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false">
                                <span class="visible-xs"><i class="fa fa-home"></i></span> <span class="hidden-xs"><?php echo lang('tab_address');?></span></a>
                        </li>
                        <li role="presentation" class="nav-item">
                            <a href="#pother" class="nav-link" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false">
                                <span class="visible-xs"><i class="fa fa-bars"></i></span> <span class="hidden-xs"><?php echo lang('lbl_other');?></span></a>
                        </li>
                        <?php if($UserData['role_id']==3){ ?>

                            <li role="presentation" class="nav-item">
                            <a href="#pas" class="nav-link" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false">
                                <span class="visible-xs"><i class="fa fa-bars"></i></span> <span class="hidden-xs">Password</span></a>
                        </li>
                            <?php } ?> 
                        <?php if($role_id == '2'){ ?>
                            <ul class="ml-auto"> 
                            
                            <li class= "nav-item navbar-nav ">
                                
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#requestReasonModel" data-dismiss="modal"><span class="visible-xs"><i class="fa fa-edit"></i></span> <span class="hidden-xs"><?php echo lang('lbl_update_password') ?></span></button>
                            </li>
<!--                            <li class= "nav-item navbar-nav ">
                                <a class="fcbtn btn  btn-primary  btn-1d " href="<?php echo site_url("profile/edit"); ?>">
                                    <?php echo lang('btn_edit_profile'); ?></a>
                            </li>-->
                        </ul>
                        <?php } ?>                       
                        <?php
                            $ci = & get_instance();
                            $arr = $ci->session->userdata("userdata")['persissions'];
                            $array = json_decode($arr);
                            if (isset($array)) {
                                $profile_edit = 0;
                                foreach ($array as $key => $value) {
                                    if (in_array('profile-edit', array($value->permission)) && $value->val == 'true') {
                                        $profile_edit = 1;
                                    }
                                }
                            }
                       ?>
                        
                        <?php if($UserData['role_id']==1 || (isset($profile_edit) && $profile_edit == 1)){?>
                        <ul class="ml-auto"> 
                            
                            <li class= "nav-item navbar-nav ">
                                <a class="fcbtn btn  btn-primary  btn-1d " href="<?php echo site_url("profile/edit"); ?>"><span class="visible-xs"><i class="fa fa-edit"></i></span> <span class="hidden-xs"><?php echo lang('btn_edit_profile'); ?></span>    </a>
                            </li>
<!--                            <li class= "nav-item navbar-nav ">
                                <a class="fcbtn btn  btn-primary  btn-1d " href="<?php echo site_url("profile/edit"); ?>">
                                    <?php echo lang('btn_edit_profile'); ?></a>
                            </li>-->
                        </ul>
                        <?php } ?>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="personal">
                            <div class="hint"><?php echo lang('help_profile_personal'); ?></div>
                            <div class="row" style="margin-left:5%">
                                <div class="col-md-6 col-xs-6 "><strong><?php echo lang('lbl_full_name');?></strong>
                                    <br>
                                    <p class="text-muted"><?php echo $profile->name; ?></p>
                                </div>
                                <div class="col-md-6 col-xs-6 "><strong><?php echo lang('lbl_designation');?></strong>
                                    <br>
                                    <p class="text-muted"><?php echo $profile->job_title; ?></p>
                                </div>
                            </div>
                            <!--                                span-->
                            <div class="row" style="margin-left:5%">
                                <div class="col-md-6 col-xs-6 "><strong><?php echo lang('lbl_gender');?></strong>
                                    <br>
                                    <p class="text-muted"><?php echo $profile->gender; ?></p>
                                </div>
                                <div class="col-md-6 col-xs-6"><strong><?php echo lang('lbl_marital_status');?></strong>
                                    <br>
                                    <p class="text-muted"><?php echo $profile->marital_status; ?></p>
                                </div>
                            </div>
                            <div class="row" style="margin-left:5%">
                                <div class="col-md-6 col-xs-6 "><strong><?php echo lang('lbl_qualification');?></strong>
                                    <br>
                                    <p class="text-muted"><?php echo $profile->qualification; ?></p>
                                </div>
                                <div class="col-md-6 col-xs-6 "><strong><?php echo lang('lbl_nationality');?></strong>
                                    <br>
                                    <p class="text-muted"><?php echo $profile->textNationality; ?></p>
                                </div>

                            </div>
                            <div class="row" style="margin-left:5%">
                                <div class="col-md-6 col-xs-6 "><strong><?php echo lang('lbl_dob');?></strong>
                                    <br>
                                    <p class="text-muted"><?php echo ($profile->dobn=='0000-00-00')?"":to_html_date($profile->dobn); ?></p>
                                </div>
                                <div class="col-md-6 col-xs-6 "><strong><?php echo lang('lbl_passport_number');?></strong>
                                    <br>
                                    <p class="text-muted"><?php echo $profile->passport_number; ?></p>
                                </div>

                            </div>
                            <div class="row" style="margin-left:5%">
                                <div class="col-md-6 col-xs-6 "><strong><?php echo lang('lbl_ic_number');?></strong>
                                    <br>
                                    <p class="text-muted"><?php echo $profile->ic_number; ?></p>
                                </div>
                               
                                <div class="col-md-6 col-xs-6 "><strong><?php echo lang('lbl_user_role');?></strong>
                                    <br>
                                    <p class="text-muted"><strong>
                                        <?php 
                                            switch ($profile->role_id){
                                                case 1:
                                                    echo lang('lbl_admin');
                                                    break;
                                                case 2:
                                                    echo lang('lbl_parent');;
                                                    break;
                                                case 3:
                                                    echo lang('lbl_student');
                                                    break;
                                                case 4:
                                                    echo lang('menu_employee');
                                                    break;
                                            } 
                                        ?>
                                        </strong>
                                    </p>
                                </div>
                            </div>
                            
                        </div>
                        <div class="tab-pane" id="contact1">
                            <div class="hint"><?php echo lang('help_profile_contact'); ?></div>
                            <div class="row" style="margin-left:5%">
                                <div class="col-md-6 col-xs-6 "><strong><?php echo lang('lbl_phone_number');?></strong>
                                    <br>
                                    <p class="text-muted"><?php echo $profile->mobile_phone; ?> </p>
                                </div>
                                <div class="col-md-6 col-xs-6 "><strong><?php echo lang('lbl_office_phone_number');?></strong>
                                    <br>
                                    <p class="text-muted"><?php echo $profile->office_phone; ?></p>
                                </div>
                            </div>
                           
                            <div class="row" style="margin-left:5%">
                                <div class="col-md-6 col-xs-6 "><strong><?php echo lang('lbl_fax');?></strong>
                                    <br>
                                    <p class="text-muted"><?php echo $profile->fax; ?></p>
                                </div>
                                <div class="col-md-6 col-xs-6 "><strong><?php echo lang('lbl_email');?></strong>

                                    <p class="text-muted"><?php echo $profile->email; ?></p>
                                </div>
                                
                            </div>

                        </div>
                        <div class="tab-pane" id="address">
                            <div class="hint"><?php echo lang('help_profile_address'); ?></div>
                            <div class="row" style="margin-left:5%">
                                <div class="col-md-6 col-xs-6 "><strong><?php echo lang('lbl_address');?></strong>
                                    <br>
                                    <p class="text-muted"><?php echo $profile->address; ?></p>
                                </div>
                                <div class="col-md-6 col-xs-6 "><strong><?php echo lang('lbl_city');?></strong>
                                    <br>
                                    <p class="text-muted"><?php echo $profile->city; ?></p>
                                </div>
                            </div>
                            <!--                                span-->
                            <div class="row" style="margin-left:5%">
                                <div class="col-md-6 col-xs-6 "><strong><?php echo lang('lbl_country');?></strong>
                                    <br>
                                    <p class="text-muted"><?php echo $profile->textCountry; ?></p>
                                </div>
                               
                            </div>

                        </div>
                       
                        <div class="tab-pane" id="pother">
                            <div class="row" style="margin-left:5%">
                                <div class="col-md-6 col-xs-6 ">
                                    
                                    <strong><?php echo lang('font_size');?></strong>
                                    <br>
                                    <p class="text-muted"><?php echo $profile->font_size . "px"; ?></p>
                               
                                </div>
                            </div>
                        </div>

       <!-- pssword tab start -->
       <div class="tab-pane" id="pas">
                            <div class="row" ng-controller="stdAdmissionEditController" style="margin-left:5%">
                                <div class="col-md-6 col-xs-6 "> 
                                <form class="form-material" id="change_password" ng-submit="changePasswordStudent('<?php echo $profile->id; ?>')">
                                <div clas s="form-body">
                                    <div class="row">
                                        <div class="col-md-9 ">
                                            <div class="form-group">
                                                <label><?php echo lang('current_password') ?></label>
                                                <input type="password" name="current_password" id="current_password" ng-model="formModel.c_password" placeholder="" required="" class="form-control"> </div>
                                        </div>
                                    </div>
                                    <!--/row-->
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label><?php echo lang('new_password') ?></label>
                                                <input type="password" name="password" id="new_password" ng-model="formModel.n_password" placeholder="" required="" minlength="8" class="form-control"> </div>
                                        </div>

                                    </div>
                                    <!--/row-->

                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label><?php echo lang('confirm_password') ?></label>
                                                <input type="password" name="confirm_password" id="confirm_password" ng-model="formModel.co_password" required="" placeholder="" minlength="8" class="form-control"> </div>
                                        </div>

                                    </div>
                                    <!--/row-->
                                    <div class="alert alert-success " id="change_alert" style="display: none"><p id="alert_msg"></p></div>
                                    <div>
                                       
                                        <button type="submit" class="btn-1d  fcbtn btn  btn-primary btn-1b pull-right"><?php echo lang('lbl_update_password') ?></button>
                                        <!-- <button type="submit" class="btn-1d  fcbtn btn  btn-primary btn-1b pull-right" style="margin-right: 5%;" id="change_password_btn_cancel">Cancel</button> -->
                                        
                                    </div>
                                </div>
                            </form>
                               
                                </div>
                            </div>
                        </div>
                    
       <!-- pssword tab start end -->

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
        });
});
   
    $(function () {
        $("#fileupload").change(function () {
            $("#image-form").submit();
        });
    });
    $(function() {
            $('#change_password_btn').click(function() {
            $('#requestReasonModel').modal('hide');
        });
    });
</script>