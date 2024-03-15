<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<style>
    .googledrive_btn{
        background-color:#1a73e8;
        color:#fff;
        border: none;
    }
    .googledrive_btn:hover{
        -webkit-box-shadow: 0 8px 6px -6px #1a73e8;
	   -moz-box-shadow: 0 8px 6px -6px #1a73e8;
	    box-shadow: 0 8px 6px -6px #1a73e8;
    }
</style>
<div>
    
    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?= lang('crumb_general_settings') ?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="#"><?= lang('crumb_dashboard') ?></a></li>
                        <li><a href="#"><?= lang('crumb_settings') ?></a></li>
                        <li class="active"><?= lang('crumb_general_settings') ?></li>
                    </ol>
                </div>
            </div>
            
            <?php $error = $this->session->flashdata('alert'); if(!empty($error)) { ?>
                <div class="alert alert-dismissable <?php if($this->session->flashdata('alert')['status'] == 'error') { echo 'alert-danger'; } else {echo 'alert-success'; }?>"> 
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>     
                    <?= $this->session->flashdata("alert")['message']; ?> 
                </div>
            <?php } ?>
            <!-- /.row -->
            <!-- Page Content start here -->
            <div class="hint"><?php echo lang('enable_google_drive'); ?></div>
            <!--.row-->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <!--<div class="panel-heading">General Settings</div>-->
                        <div class="panel-wrapper collapse in" aria-expanded="true">
                            <div class="panel-heading">
                            <?php if(file_exists($credentials_fileid)){ ?>
                                <?php if($enable_gd==1){ ?>
                                    
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p style="font-size: 14px;"><img src="assets/googledrive_guide/images/enable.png" style="width: 40px;"/> Google Drive integrated successfully</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <a href="disable_gd" style="float:right; "><button type="button" class="btn btn-lg btn-danger">Remove Google Drive</button></a>
                                        </div>
                                    </div>
                            <?php } else if($enable_gd==0) { ?>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p>Click below button to enable Google Drive</p>
                                        <a href="<?php echo base_url('settings/enable_googledrive');?>"><button type="button" class="btn googledrive_btn"> <img src="assets/googledrive_guide/images/gd_logo.png" style="width: 20px;"/> &nbsp;Enable Google Drive</button></a>
                                        <a href="<?php echo base_url('settings/remove_credentialsfile');?>"><button type="button" class="btn btn-danger">Remove File</button></a>
                                    </div>
                                </div>
                                
                            <?php } } else { ?>
                            <form action="settings/upload_credentialsfile" method="post" enctype="multipart/form-data" class="form-material">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label"><?= lang('credentials_file')?></label>
                                            <input type="file" name="gd_credentials" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <br>
                                        <button type="submit" class="btn btn-primary" style="float:right"><i class="fa fa-check"></i> <?= lang('btn_save') ?></button>
                                    </div>
                                </div>
                            </form>
                                
                            <?php } ?>
                            </div>
                            
                            <div class="panel-body">
                            
                                <!--///////////Google Drive Settings//////////-->
                                                    
                                                    <div class="row">
                                                        
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="inbox-center table-responsive" id="">
                                                                <!--<h4>Google Drive Status</h4>-->
                                                                <?php if(file_exists($credentials_fileid)){ ?>
                                                                    <?php if($enable_gd==0){ ?>
                                                                        <div class="col-sm-4">
                                                                            
                                                                        </div>
                                                                    <?php } elseif($enable_gd==1){ ?>
                                                                        <!--<div class="col-sm-12">-->
                                                                        <!--    <p style="font-size: 14px;"><img src="assets/googledrive_guide/images/enable.png" style="width: 40px;"/> Google Drive integrated successfully</p>-->
                                                                        <!--    <button type="button" class="btn btn-success" disabled="disabled">Enabled Google Drive</button>-->
                                                                        <!--</div>-->
                                                                        
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                        
                                                       <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">-->
                                                       <!--    <div class="inbox-center table-responsive" id="">-->
                                                       <!--        <h4>Google Drive Integration</h4>-->
                                                               
                                                       <!--         <a href="gd_guide" target="_blank"><button type="button" class="btn btn-primary "> -->
                                                       <!--             <i class="fa fa-check"></i> User Guide-->
                                                       <!--         </button></a>-->
                                
                                                       <!--    </div>-->
                                                       <!--</div>-->
                                                       
                                                    </div>


                                                </div>
                                           
                                <!--//////////Google Drive Settings///////////-->
                                
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div style="width:100%;">
                                                    <h4 style="margin-bottom: 20px; text-align: center;"><img src="assets/googledrive_guide/images/gd_logo.png" style="width:50px; padding-right: 15px;"/>How to integrate Google Drive? Please follow instructions below.</h4>
                                                    <?php include(APPPATH . "views/googledrive_userguide/index.php"); ?>
                                                </div>
                                                <!--<div style="width:100%; text-align:center !important;">-->
                                                <!--    <h4 style="margin-bottom: 20px;"><img src="assets/googledrive_guide/images/gd_logo.png" style="width:50px; padding-right: 15px;"/>How to integrate Google Drive? Please follow instructions below.</h4>-->
                                                <!--    <iframe src="assets/googledrive_guide/userguide.pdf#page=1&zoom=160" height="768" width="100%"></iframe>-->
                                                <!--</div>-->
                                            </div>
                                        </div>

                            </div>
                            <!--/panel body-->
                        </div>
                        <!--/panel wrapper-->
                    </div>
                    <!--/panel-->
                </div>
            </div>
            <!--./row-->
            <!--page content end here-->
        </div>
    </div>
    <!-- /.container-fluid -->
    
    <script type="text/javascript">
        $(document).ready(function(){
         $('#customcolor').spectrum({
            preferredFormat: "hex",
            showInput: true,
            color:$('#hexcode').val(),
            change: function(tinycolor){
                $('#hexcode').val($('#customcolor').spectrum('get'));
            }
        });
     });
    </script> 
    <?php include(APPPATH . "views/inc/footer.php"); ?>
