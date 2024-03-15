<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('virtual_accounts') ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="#"><?php echo lang('lbl_dashboard') ?></a></li>
                    <li><a href="#"><?php echo lang('accounts') ?></a></li>
                    <li class="active"><?php echo lang('virtual_accounts') ?></li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <!-- Page Content start here -->
        <div class="hint"><?php echo lang('help_virtual_accounts') ?></div>
        <!--.row-->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">

                    <div class="panel-wrapper collapse in" aria-expanded="true">
                        <div class="panel-body">

                            <ul class="nav customtab nav-tabs" role="tablist">
                                <li role="presentation" class="nav-item"><a href="#virtual_accounts" class="nav-link active"
                                                                            aria-controls="profile" role="tab"
                                                                            data-toggle="tab"
                                                                            aria-expanded="true"><span
                                            class="visible-xs"><i class="fa fa-bars"></i></span><span
                                            class="hidden-xs"><?php echo lang('virtual_accounts') ?></span></a>
                                </li>
                            </ul>


                            <!--tab content start here-->
                            <div class="tab-content">
                                <div class="tab-pane active" id="expense_settings">
                                    <?php echo $virtual_accounts; ?>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    

<?php include(APPPATH . "views/inc/footer.php"); ?>