<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('view_parent') ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="#"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li><a href="#"><?php echo lang('crumb_parents') ?></a></li>
                    <li class="active"><?php echo lang('view_parent') ?></li>

                </ol>
            </div>
        </div>
        
        <div class="hint"><?php echo lang('help_parent_view'); ?></div>
        <div class="row">

            <div class="col-md-12 col-xs-12">
                <div class="white-box">
                    <ul class="nav customtab nav-tabs" role="tablist">
                        <li role="presentation" class="nav-item"><a href="#info" class="nav-link active"                                                                    aria-controls="profile" role="tab"
                            data-toggle="tab" aria-expanded="true"><span
                            class="visible-xs"><i class="fa fa-user"></i></span><span class="hidden-xs"><?php echo lang('tab_info') ?></span></a>
                        </li>
                        <li role="presentation" class="nav-item"><a href="#attachments" class="nav-link"
                            aria-controls="profile" role="tab"
                            data-toggle="tab" aria-expanded="false"><span
                            class="visible-xs"><i class="fa fa-university"></i></span> <span
                            class="hidden-xs"><?php echo lang('lbl_attachments') ?></span></a></li>

                            <li role="presentation" class="nav-item"><a href="#children" class="nav-link"
                                aria-controls="profile" role="tab"
                                data-toggle="tab" aria-expanded="false"><span
                                class="visible-xs"><i class="fa fa-users"></i></span> <span
                                class="hidden-xs"><?php echo lang('lbl_children') ?></span></a></li>

                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="info" >

                                    <form action="" method="post" class="form-material" id="settings" enctype="multipart/form-data">
                                        <div class="form-body">


                                            <h3 class="box-title"><?php echo lang('personal_details') ?></h3>
                                            <hr style="border-color: black" />

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <img lass="form-control" style="width: 100px;" src="<?php echo base_url() ?>uploads/user/<?php echo $parent->avatar; ?>"/>
                                                    </div>
                                                </div>
                                                <!--/span-->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_name') ?></label>
                                                        <p><?php echo $parent->name; ?></p>

                                                    </div>
                                                </div>
                                                <!--/span-->

                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_gender') ?></label>

                                                        <p><?php echo $parent->gender; ?></p>
                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_dob') ?></label>

                                                        <p><?php echo ($parent->dob=='0000-00-00')?"":to_html_date($parent->dob); ?></p>
                                                    </div>
                                                </div>
                                                <!--/span-->

                                                <!--/span-->

                                            </div>
                                            <!--/row-->
                                            <div class="row">

                                                <!--/span-->

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_occupation') ?></label>

                                                        <p><?php echo $parent->occupation; ?></p>

                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_income') ?></label>
                                                        <p><?php echo $parent->income; ?></p>

                                                    </div>
                                                </div>
                                                <!--/span-->
                                            </div>
                                            <!--/row-->
                                            <div class="row">

                                                <!--/span-->

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_email') ?></label>
                                                        <p><?php echo $parent->email; ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_phone') ?></label>
                                                        <p><?php echo $parent->contact; ?></p>
                                                    </div>
                                                </div>
                                                <!--/span-->

                                            </div>
                                            <div class="row">


                                                <!--/span-->
                                                <div class="col-md-6">

                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_street') ?></label>

                                                        <p><?php echo $parent->address; ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_ic_number') ?></label>

                                                        <p><?php echo $parent->ic_number; ?></p>
                                                    </div>
                                                </div>

                                                <!--/span-->

                                            </div>

                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_country') ?></label>

                                                        <p><?php echo $parent->country; ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_city') ?></label>

                                                        <p><?php echo $parent->city; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <h3 class="box-title"><?php echo lang('secondary_guardian_details'); ?></h3>
                                            <hr style="border-color: black" />
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_name') ?></label>
                                                        <p><?php echo $parent->guardian2_name; ?></p>
                                                    </div>
                                                </div>
                                                <!--/span-->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_relation') ?></label>
                                                        <p><?php echo $parent->guardian2_relation; ?></p>
                                                    </div>
                                                </div>
                                                <!--/span-->
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo lang('lbl_phone') ?></label>
                                                        <p><?php echo $parent->guardian2_contact; ?></p>
                                                    </div>
                                                </div>
                                                <!--/span-->

                                                <!--/span-->
                                            </div>

                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane" id="attachments">
                                    <div class="hint"><?php echo lang('help_par_attach'); ?></div>
                                    
                                    <?php echo $attachments ; ?>                         
                                </div>


                                <div class="tab-pane" id="children">

                                    <div class="table-responsive">
                                        <?php if(count($children) > 0) { ?>
                                            <table class="myTable display nowrap" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th><?php echo lang('lbl_name') ?></th>
                                                        <th><?php echo lang('lbl_email') ?></th>
                                                        <th><?php echo lang('lbl_class') ?></th>
                                                        <th><?php echo lang('lbl_batch') ?></th>
                                                        <th><?php echo lang('lbl_status') ?></th>
                                                        <th style="text-align: center;"><?php echo lang('lbl_action') ?></th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th><?php echo lang('lbl_name') ?></th>
                                                        <th><?php echo lang('lbl_email') ?></th>
                                                        <th><?php echo lang('lbl_class') ?></th>
                                                        <th><?php echo lang('lbl_batch') ?></th>
                                                        <th><?php echo lang('lbl_status') ?></th>
                                                        <th style="text-align: center;"><?php echo lang('lbl_action') ?></th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                    <?php foreach($children as $child) { ?>
                                                        <tr>
                                                            <td><?php echo $child->name; ?></td>
                                                            <td><?php echo $child->email; ?></td>
                                                            <td><?php echo $child->class_name; ?></td>
                                                            <td><?php echo $child->batch_name; ?></td>
                                                            <?php if ($child->status == 1) { ?>
                                                                <td><?php echo lang('option_active') ?></td>
                                                            <?php } else { ?>
                                                                <td><?php echo lang('option_disabled') ?></td>
                                                            <?php } ?>
                                                            <td class="text-center"><a type="button" href="<?php echo base_url(); ?>students/view/<?php echo encrypt($child->id); ?>" class="btn btn-success btn-circle" ><i class="fa fa-eye"></i></a></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        <?php }else { ?>
                                            <?php echo "<span class='text-danger'>".lang("no_record")."</span>"; ?>
                                        <?php } ?>
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
