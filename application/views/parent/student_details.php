<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<!-- Page Content -->
<div id="page-wrapper" >
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
        <!-- /.row -->
        <!-- Page Content -->
        <div class="hint"><?php echo lang('help_profile'); ?></div>
        <!-- .row -->
        <div class="row">
            <div class="col-md-3 col-xs-12">
                <div class="white-box">
                    <!-- <div class="user-bg">
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
                    </div> -->
                    <div class="user-btm-box">
                            
                    <div class="box box-primary card">
                        <div class="box-body box-profile">
                            <img class="profile-user-img img-responsive img-circle center-block" src="<?php echo base_url(); ?>uploads/user/<?php echo $details->avatar; ?>" alt="User profile picture" style="margin-top: 5px;">
                            <h3 class="profile-username text-center">

                                <?php echo $details->name; ?>
           
                            </h3>
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b><?php echo lang('lbl_D_O_B') ?></b> <a class="pull-right text-aqua"><?php echo $details->dob; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b><?php echo lang('lbl_Roll_Number') ?></b> <a class="pull-right text-aqua"><?php echo $details->rollno; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b><?php echo lang('lbl_par_Class') ?></b> <a class="pull-right text-aqua"><?php echo $details->class_name; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b></b><?php echo lang('lbl_Section_par') ?> <a class="pull-right text-aqua"><?php echo $details->batch_name; ?></a>
                                </li>
                                
                            </ul>
                        </div>
                    </div>
                
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-xs-12">
                <div class="nav-tabs-custom">

                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="nav-item">
                            <a href="#personal" class="nav-link active" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true">
                                <span class="visible-xs"><i class="fa fa-user"></i></span><span class="hidden-xs"> <?php echo lang('tab_peronal');?></span></a>
                        </li>
                       
                       
                        
                       
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
                            
                            <div class="tshadow mb25 bozero">
                                <div class="table-responsive around10 pt0">
                                    <table class="table table-striped table-hover tmb0">
                                        <tbody> 
                                            <tr>
                                                <td><?php echo lang('lbl_Admission_Date') ?></td>
                                                <td><?php echo $details->joining_date; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo lang('lbl_Date_Of_Birth_prt') ?></td>
                                                <td><?php echo $details->dob; ?></td>
                                            </tr>
                                            
                                            <tr>
                                                <td><?php echo lang('lbl_Mobile_Number_prt') ?></td>
                                                <td><?php echo $details->contact; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo lang('lbl_Religion_prt') ?></td>
                                                <td><?php echo $details->religion; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo lang('lbl_Email_prt') ?></td>
                                                <td><?php echo $details->email; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div></div>
                                <div class="tshadow mb25 bozero">
                                <h3 class="pagetitleh2"><?php echo lang('lbl_Address_prt') ?></h3>
                                <div class="table-responsive around10 pt0">
                                    <table class="table table-hover table-striped tmb0"><tbody>
                                            <tr>
                                                <td><?php echo lang('lbl_Current_Address_prt') ?></td>
                                                <td><?php echo $details->address; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo lang('lbl_Permanent_Address_prt') ?></td>
                                                <td><?php echo $details->address; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div></div>
                                
                            <div class="tshadow mb25 bozero">
                                <h3 class="pagetitleh2"><?php echo lang('lbl_MiscellaneousDetails_prt') ?></h3>
                                <div class="table-responsive around10 pt0">
                                    <table class="table table-hover table-striped tmb0">
                                        <tbody>
                                            <tr>
                                                <td><?php echo lang('lbl_BloodGroup_prt') ?></td>
                                                <td><?php echo $details->blood; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo lang('lbl_Student_BirthPlace_prt') ?></td>
                                                <td><?php echo $details->birthplace; ?></td>
                                            </tr>
                                            
                                               <tr>
                                              <td><?php echo lang('lbl_Gender_prt') ?></td>
                                              <td><?php echo $details->gender; ?></td>
                                            </tr>
                                            
                                            <tr>
                                                <td><?php echo lang('lbl_NationalI_Number_par') ?></td>
                                                <td><?php echo $details->passport_number; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo lang('lbl_Local_Identification_Number_par') ?></td>
                                                <td><?php echo $details->ic_number; ?></td>
                                            </tr>
                                            
                                            
                                        </tbody>
                                    </table>
                                </div></div>
                            <!--                                span-->
                            
                            
                            
                            
                            
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
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--page content end-->

</div>
<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>
