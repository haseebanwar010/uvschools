<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('all_students') ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="#"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li><a href="#"><?php echo lang('menu_students') ?></a></li>
                    <li class="active"><?php echo lang('import') ?></li>

                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div class="hint"><?php echo lang('help_import_student'); ?></div>
        <!-- row -->
        <div class="row">
            <!-- Left sidebar -->
            <div class="col-md-12">
                <div class="white-box">
                    <?php if($this->session->flashdata('import_error')){ ?>
                       <div class="alert alert-danger" style="color: #fff; font-size: 18px;"> <?php  echo $this->session->flashdata('import_error');  ?></div>
                    <?php } ?>
                    <!-- row -->
                    <div class="row" >
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="inbox-center table-responsive" id="inbox_div">
                                <div class="col-md-6"><?php echo lang('select_csv_file') ?>
                                    <form action="import/show" method="post" enctype="multipart/form-data" name="form1" id="form1"> 
                                        <div id="dvImportSegments" class="fileupload ">
                                        <label class="btn btn-info" style="border-radius: 5px;">
                                            <i class="fa fa-paperclip"></i>&nbsp;<?= lang("choese_file_here"); ?><input type="file" name="csv" id="csv" class="form-control" required accept=".csv" style="display: none;"/>
                                         </label>
                                            <!-- <input type="file" name="csv" id="csv" class="form-control" required accept=".csv" /> -->
                                        </div>
                                        <input type="submit" name="import_csv" id="import_csv" value="<?php echo lang('show_csv_data') ?>" class="btn btn-primary mt-2 mb-2" id="import_csv_btn">
                                    </form>

                                    <a href="uploads/templates_new/import_student_betch_file.csv" download="import_student_betch_file.csv">
                                        <span class="btn btn-success"><i class="fa fa-download"></i><?php echo lang('download_template') ?></span>
                                    </a>
                                </div>
                            </div>  
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                           <div class="inbox-center table-responsive" id="inbox_div">
                               <h4><?php echo lang('ins_import_student') ?></h4>
                               <div class="alert alert-danger" style="padding: 10px; margin: 0px;">
                                   <ul>
                                       <li><?php echo lang('ins_import_dummy_data') ?></li>
                                       <li><?php echo lang('ins_import_class_batches') ?></li>
                                   </ul>
                               </div>
                               <ul>
                                   <li><?php echo lang('ins_import_hr_settings') ?></li>
                                   <li><?php echo lang('ins_import_teacher_department') ?></li>
                                   <li><?php echo lang('ins_import_academic_settings') ?></li>
                                   <li><?php echo lang('ins_import_download_csv') ?>
                                       <ul>
                                           <li><?php echo lang('ins_import_update_csv') ?></li>
                                           <li><?php echo lang('ins_import_roll_email') ?></li>
                                           <li><?php echo lang('ins_import_field_required') ?></li>

                                       </ul>
                                   </li>
                                   <li><?php echo lang('ins_import_show_student') ?></li>
                                   <li><?php echo lang('ins_import_save_student') ?></li>
                               </ul>

                           </div>
                       </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <!--page content end-->
        </div>
    </div>


    <!-- /.container-fluid -->
    <?php include(APPPATH . "views/inc/footer.php"); ?>
