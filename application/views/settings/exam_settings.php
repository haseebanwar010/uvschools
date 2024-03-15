<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<div>
    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?php echo lang('menu_examination_settings') ?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="#"><?= lang('crumb_dashboard') ?></a></li>
                        <li><a href="#"><?php echo lang('crumb_settings') ?></a></li>
                        <li class="active"><?php echo lang('menu_examination_settings') ?></li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <div class="hint"><?php echo lang('help_exam_setting'); ?></div>
            <!-- Page Content start here -->
            <!--.row-->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">

                        <div class="panel-wrapper collapse in" aria-expanded="true">
                            <div class="panel-body">

                                <ul class="nav customtab nav-tabs" role="tablist">
                                    <li role="presentation" class="nav-item">
                                        <a href="#grades" class="nav-link active"
                                        aria-controls="profile" role="tab"
                                        data-toggle="tab"
                                        aria-expanded="true"><span
                                        class="visible-xs"><i class="fa fa-calculator"></i></span><span
                                        class="hidden-xs"><?php echo lang('lbl_grades') ?></span></a>
                                    </li>
                                </ul>
                                <!--tab content start here-->
                                <div class="tab-content">
                                    <div class="tab-pane active" id="grades" >
                                        <!-- row -->
                                        <div class="hint"><?php echo lang('help_grades'); ?></div>
                                        <?php echo $grades; ?>
                                        <!--/row-->
                                    </div>
                                </div>
                            </div>
                            <!--tab content end here-->
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
<?php include(APPPATH . "views/inc/footer.php"); ?>
