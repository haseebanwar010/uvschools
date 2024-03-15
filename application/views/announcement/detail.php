<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<!-- Page Content -->
<div id="page-wrapper" ng-controller="dashboardCtrl">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?= lang("lbl_dashboard"); ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="dashboard/"><?= lang("lbl_dashboard"); ?></a></li>
                    <li class="active"><?= lang("lbl_dashboard"); ?></li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <div class="hint"><?php echo lang('help_dashboard'); ?></div>
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <?php echo $announcement; ?>
                </div>
            </div>
        </div>
        <!-- .right-sidebar -->
        <!-- Include yout right sidebar here -->
        <!-- /.right-sidebar -->
    </div>
<?php include(APPPATH . "views/inc/footer.php"); ?>