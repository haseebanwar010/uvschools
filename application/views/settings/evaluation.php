<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<!-- Page Content -->
<div id="page-wrapper" ng-controller="addDeptController" ng-init="initDeperments(); initCategories()">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('evaluation_settings'); ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="#"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li><a href="#"><?php echo lang('crumb_settings') ?></a></li>
                    <li class="active"><?php echo lang('student_evaluation'); ?></li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        
        <div class="hint"><?php echo lang('help_student_evaluation'); ?></div>
        
        
    


    
        
       
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-wrapper collapse in" aria-expanded="true">
                        <div class="panel-body">

                            <ul class="nav customtab nav-tabs" role="tablist">
                                <li role="presentation" class="nav-item"><a href="#terms" class="nav-link active"
                                    aria-controls="profile" role="tab"
                                    data-toggle="tab" aria-expanded="false"><span
                                    class="visible-xs"><i class="ti-layout-accordion-merged"></i></span> <span
                                    class="hidden-xs"><?php echo lang('evaluation_terms'); ?></span></a></li>

                                    <li role="presentation" class="nav-item"><a href="#emp-dept" class="nav-link"
                                    aria-controls="profile" role="tab"
                                    data-toggle="tab" aria-expanded="false"><span
                                    class="visible-xs"><i class="ti-layout-accordion-merged"></i></span> <span
                                    class="hidden-xs"><?php echo lang('evaluation_types'); ?></span></a></li>

                                    <li role="presentation" class="nav-item"><a href="#emp-cat" class="nav-link"
                                        aria-controls="profile" role="tab"
                                        data-toggle="tab"
                                        aria-expanded="true"><span
                                        class="visible-xs"><i class="ti-layout-media-right-alt"></i></span><span
                                        class="hidden-xs"><?php echo lang('evaluation_categories'); ?></span></a>
                                    </li>
                                </ul>

                                <!--tab content start here-->

                                <div class="tab-content">
                                    <div class="tab-pane active" id="terms">
                                        <div class="hint"><?php echo lang('help_student_evaluation_tab'); ?></div>
                                        
                                        <?php echo $evaluation_terms; ?>

                                    </div>

                                    <div class="tab-pane" id="emp-dept">
                                        <div class="hint"><?php echo lang('help_student_evaluation_tab'); ?></div>
                                        
                                        <?php echo $evaluation; ?>

                                    </div>


                                    <div class="tab-pane" id="emp-cat">
                                        <div class="hint"><?php echo lang('help_evaluation_categories'); ?></div>
                                        <?php echo $category; ?>
                                        
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
        </div>
            <!--./row-->
            <!--page content end here-->
        </div>
        </div>
    </div>
    
    <!-- Category Modal Start 20-12-2017 By Shahzaib -->
    
<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>