<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<!-- Page Content -->
<div id="page-wrapper" ng-controller="">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('reports_all') ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="<?php echo site_url('/dashboard') ?>"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li><a href="<?php echo site_url('/reports') ?>"><?php echo lang('reports_all') ?></a></li>
                </ol>
            </div>
        </div>
       
        <div class="white-box well">
            <div class="row m-t-40">
                    <!-- Column -->
                    <a href="#" class="col-md-6 col-lg-3 col-xlg-3">
                        <div class="card">
                            <div class="box bg-info text-center">
                                <h1 class="font-light text-white">2,064</h1>
                                <h6 class="text-white"><?php echo lang('lbl_students') ?></h6>
                            </div>
                        </div>
                    </a>
                    <!-- Column -->
                    <a href="#" class="col-md-6 col-lg-3 col-xlg-3">
                        <div class="card">
                            <div class="box bg-primary text-center">
                                <h1 class="font-light text-white">1,738</h1>
                                <h6 class="text-white"><?php echo lang('heading_all_employee') ?></h6>
                            </div>
                        </div>
                    </a>
                    <!-- Column -->
                    <a href="#" class="col-md-6 col-lg-3 col-xlg-3">
                        <div class="card">
                            <div class="box bg-success text-center">
                                <h1 class="font-light text-white">1100</h1>
                                <h6 class="text-white"><?php echo lang('lbl_admissions') ?></h6>
                            </div>
                        </div>
                    </a>
                    <!-- Column -->
                    
                    <a href="#" class="col-md-6 col-lg-3 col-xlg-3">
                        <div class="card">
                            <div class="box bg-danger text-center">
                                <h1 class="font-light text-white"><?php echo lang('lbl_pending') ?></h1>
                                <h6 class="text-white"><?php echo lang('lbl_pending') ?></h6>
                            </div>
                        </div>
                    </a>
                        
                    <!-- Column -->
                </div>
        </div>
        <!--./row-->

<!--        <div class="white-box" id="attStudentsTable" ng-show="">
            
        </div>

        <div class="white-box" id="attStudentsTable" ng-show="">
            <div class="row">
                <div class="col-md-12" style="display:none">No record found.</div>
            </div>
        </div>-->

    </div>
</div>

<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>
   