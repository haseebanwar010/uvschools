<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('heading_view_guardian') ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="#"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li><a href="#"><?php echo lang('crumb_employee') ?></a></li>
                    <li class="active"><?php echo lang('heading_view_guardian') ?></li>

                </ol>
            </div>
        </div>
        
        <div class="hint"><?php echo lang('help_guardian_view'); ?></div>

        
        <div class="row">

            <div class="col-md-12 col-xs-12">
                <div class="white-box">
                    <ul class="nav customtab nav-tabs" role="tablist">
                        <li role="presentation" class="nav-item"><a href="#info" class="nav-link active"                                                                    aria-controls="profile" role="tab"
                            data-toggle="tab" aria-expanded="true"><span
                            class="visible-xs"><i class="fa fa-user"></i></span><span class="hidden-xs"><?php echo lang('tab_info') ?></span></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="info" >

                            <form action="" method="post" class="form-material" id="settings" enctype="multipart/form-data">
                                <div class="form-body">

                                    <div class="row">
                                        <h2 class="box-title"><?php echo lang('lbl_guardian_details') ?></h2>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <img lass="form-control" style="width: 100px;" src="<?php echo base_url(); ?>uploads/user/<?php echo $guardian[0]->avatar; ?>"/>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_name') ?></label>
                                                    <p><?php echo $guardian[0]->name; ?></p>

                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_gender') ?></label>

                                                    <p><?php echo $guardian[0]->gender; ?></p>
                                                </div>

                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_dob') ?></label>


                                                    <p><?php echo $guardian[0]->dob; ?></p>

                                                </div>
                                            </div>
                                            <!--/span-->

                                        </div>
                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('blood_group') ?></label>

                                                    <p><?php echo $guardian[0]->blood; ?></p>
                                                </div>
                                            </div>
                                            <!--/span-->

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('birth_place') ?></label>

                                                    <p><?php echo $guardian[0]->birthplace; ?></p>

                                                </div>

                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_nationality') ?></label>
                                                    <p><?php echo $guardian[0]->nationality; ?></p>

                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_religion') ?></label>
                                                    <p><?php echo $guardian[0]->religion; ?></p>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_city') ?></label>

                                                    <p><?php echo $guardian[0]->city; ?></p>
                                                </div>
                                            </div>

                                            <!--/span-->
                                        </div>


                                        <div class="row">
                                            <h2 class="box-title"><?php echo lang('lbl_contact_details') ?></h2>
                                            </div>
                                            <div class="row">
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_address') ?></label>

                                                    <p><?php echo $guardian[0]->address; ?></p>

                                                </div>
                                            </div>


                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_email') ?></label>

                                                    <p><?php echo $guardian[0]->email; ?></p>
                                                </div>
                                            </div>
                                            <!--/span-->


                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_phone') ?></label>
                                                    <p><?php echo $guardian[0]->contact; ?></p>

                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        </div>
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