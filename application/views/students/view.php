<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('heading_view_student') ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="#"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li><a href="#"><?php echo lang('crumb_employee') ?></a></li>
                    <li class="active"><?php echo lang('heading_view_student') ?></li>

                </ol>
            </div>
        </div>
        
        <div class="hint"><?php echo lang('help_std_view'); ?></div>
        
        <div class="row">

            <div class="col-md-12 col-xs-12">
                <div class="white-box">
                    <ul class="nav customtab nav-tabs" role="tablist">
                        <li role="presentation" class="nav-item"><a href="#info" class="nav-link active"                                                                    aria-controls="profile" role="tab"
                            data-toggle="tab" aria-expanded="true"><span
                            class="visible-xs"><i class="fa fa-user"></i></span><span class="hidden-xs"><?php echo lang('tab_info') ?></span></a>
                        </li>
                        <li role="presentation" class="nav-item"><a href="#academic" class="nav-link"
                            aria-controls="profile" role="tab"
                            data-toggle="tab" aria-expanded="false"><span
                            class="visible-xs"><i class="fa fa-graduation-cap"></i></span> <span
                            class="hidden-xs"><?php echo lang('tab_academic') ?></span></a></li>
                            <li role="presentation" class="nav-item"><a href="#attachments" class="nav-link"
                                aria-controls="profile" role="tab"
                                data-toggle="tab" aria-expanded="false"><span
                                class="visible-xs"><i class="fa fa-university"></i></span> <span
                                class="hidden-xs"><?php echo lang('lbl_attachments') ?></span></a></li>
                                <li role="presentation" class="nav-item"><a href="#history" class="nav-link"
                                    aria-controls="profile" role="tab"
                                    data-toggle="tab" aria-expanded="false"><span
                                    class="visible-xs"><i class="fa fa-university"></i></span> <span
                                    class="hidden-xs"><?php echo lang('history'); ?></span></a></li>

                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="info" >

                                    <form action="" method="post" class="form-material" id="settings" enctype="multipart/form-data">
                                        <div class="form-body">

                                            <div class="row">
                                                <h2 class="box-title"><?php echo lang('personal_details') ?></h2>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <img lass="form-control" style="width: 100px;" src="<?php echo base_url() ?>uploads/user/<?php echo $student->avatar; ?>"/>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_name') ?></label>
                                                            <p><?php echo $student->name; ?></p>

                                                        </div>
                                                    </div>
                                                    <!--/span-->

                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_gender') ?></label>

                                                            <p><?php echo $student->gender; ?></p>
                                                        </div>

                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_dob') ?></label>


                                                            <p><?php echo $student->dobn; ?></p>

                                                        </div>
                                                    </div>
                                                    <!--/span-->

                                                </div>
                                                <!--/row-->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('blood_group') ?></label>

                                                            <p><?php echo $student->blood; ?></p>
                                                        </div>
                                                    </div>
                                                    <!--/span-->

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('birth_place') ?></label>

                                                            <p><?php echo $student->birthplace; ?></p>

                                                        </div>

                                                    </div>
                                                    <!--/span-->
                                                </div>
                                                <!--/row-->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_nationality') ?></label>
                                                            <p><?php echo $student->nationality; ?></p>

                                                        </div>
                                                    </div>
                                                    <!--/span-->

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_language') ?></label>
                                                            <p><?php echo $student->language; ?></p>
                                                        </div>
                                                    </div>
                                                    <!--/span-->

                                                </div>
                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_religion') ?></label>
                                                            <p><?php echo $student->religion; ?></p>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">

                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('national_number') ?></label>

                                                            <p><?php echo $student->ic_number; ?></p>
                                                        </div>
                                                    </div>

                                                    <!--/span-->

                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_city') ?></label>

                                                            <p><?php echo $student->city; ?></p>
                                                        </div>
                                                    </div>
                                                   <!--  <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_country') ?></label>

                                                            <p><?php echo $student->country_name; ?></p>
                                                        </div>
                                                    </div> -->
                                                </div>

                                                <div class="row">
                                                    <h2 class="box-title"><?php echo lang('contact_details') ?></h1>
                                                    </div>
                                                    <div class="row">
                                                     <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_address') ?></label>

                                                            <p><?php echo $student->address; ?></p>

                                                        </div>
                                                    </div>


                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_email') ?></label>

                                                            <p><?php echo $student->email; ?></p>

                                                        </div>

                                                    </div>
                                                    <!--/span-->


                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_phone') ?></label>
                                                            <p><?php echo $student->contact; ?></p>

                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="row">
                                                    <h2 class="box-title"><?php echo lang('courses_batch_details') ?></h1>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label"><?php echo lang('lbl_course') ?></label>

                                                                <p><?php echo $student->classname; ?></p>

                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label"><?php echo lang('lbl_batch') ?></label>
                                                                <p><?php echo $student->batchname; ?></p>

                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                    </div>

                                                    <!--/row-->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label"><?php echo lang('subject_groups') ?></label>

                                                                <p><?php echo $student->group_name; ?></p>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label"><?php echo lang('lbl_rollno') ?></label>

                                                                <p><?php echo $student->rollno; ?></p>

                                                            </div>
                                                        </div>

                                                        

                                                        <!--/span-->


                                                    </div>
                                                    <div class="row">
                                                        <!-- <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label"><?php echo lang('lbl_discount');?></label>

                                                                <p><?php echo $student->discount; ?></p>

                                                            </div>
                                                        </div> -->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label"><?php echo lang('admission_date');?></label>

                                                                <p><?php echo $student->adm_date; ?></p>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="row">
                                                        <h2 class="box-title"><?php echo lang('lbl_guardian') ?></h1>
                                                    </div>

                                                    
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label"><?php echo lang('imp_parent_name') ?></label>
                                                                <?php if(sizeof($parent) > 0)
                                                                { 
                                                                    foreach($parent as $par)
                                                                    {
                                                                ?>
                                                                <!--<p><?php echo $parent->name; ?> <br/><small><a href="<?php echo base_url(); ?>parents/view/<?= encrypt($parent->id); ?>"><?php echo lang('lbl_details') ?></a></small></p>-->
                                                                
                                                                <p><?php echo $par->name; ?> <br/><small><a href="<?php echo base_url(); ?>parents/view/<?= encrypt($par->id); ?>"><?php echo lang('lbl_details') ?></a></small></p>
                                                            <?php } } else{ ?>
                                                                <p class="error"><?php echo lang('no_guardian'); ?></p>
                                                            <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>




                                                </div>
                                            </form>
                                        </div>
                                        <div class="tab-pane " id="academic">


                                        </div>
                                        <div class="tab-pane" id="attachments">
                                    <div class="hint"><?php echo lang('help_std_attach'); ?></div>
                                    
                                    <?php echo $attachments ; ?>                         
                                </div>
                                <div class="tab-pane" id="history">
                                                <div class="hint"><?php echo lang('help_std_history'); ?></div>
                                                <div class="row">
                                                    <ul class="timeline">
                                                        <?php $i = 1; ?>
                                                        <?php foreach ($history as $temp) { ?>



                                                            <li <?php if($i%2==0) echo "class='timeline-inverted'"?>>
                                                                <div class="timeline-badge primary">
                                                                    <i class="fa fa-map-marker"></i>
                                                                </div>
                                                                <div class="timeline-panel">
                                                                    <div class="timeline-heading">
                                                                        <h4 class="timeline-title">
                                                                            <?php echo $temp->description; ?>
                                                                        </h4>
                                                                        <p>
                                                                            <small class="text-muted">
                                                                                <i class="fa fa-clock-o"></i>
                                                                                <?php if($i == 1 && $temp->adm_date != NULL){
                                                                                    echo $temp->adm_date;
                                                                                }else{
                                                                                    echo $temp->shiftDate;
                                                                                }  ?></small>
                                                                            </p>
                                                                        </div>
                                                                        <div class="timeline-body">
                                                                            <?php if($temp->reason != null && $temp->reason != ""){ ?>
                                                                            <p><b><?php echo lang('reason'); ?></b>:  <?php echo $temp->reason; ?></p>
                                                                        <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </li>

                                                                


                                                                <?php $i++;} ?>
                                                                <li class="timeline-inverted" style="visibility: hidden">
                                                                    <div class="timeline-badge success">
                                                                        <i class="fa fa-graduation-cap"></i>
                                                                    </div>
                                                                    <div class="timeline-panel">
                                                                        <div class="timeline-heading">
                                                                            <h4 class="timeline-title">Lorem ipsum dolor</h4>
                                                                        </div>
                                                                        <div class="timeline-body">
                                                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deserunt obcaecati,
                                                                                quaerat tempore officia voluptas debitis consectetur culpa amet
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>
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
