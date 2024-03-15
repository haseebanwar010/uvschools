<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<!-- Page Content -->
<div id="page-wrapper">
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




        <!-- Alert for trial users -->
        <?php if ($this->session->userdata("userdata")["licence_type"] == "trial") { ?>
            <div class="alert alert-danger"> 
                <?= lang("remaining_days"); ?> 
                <?php echo $this->session->userdata('userdata')['remaining_days']['days'] ?> 
            </div>
        <?php } ?>
        <!-- End of alert -->
        <div class="hint"><?php echo lang('help_dashboard'); ?></div>
<!--        <div class="well">
            <h4>Admin::<?= lang("lbl_welcome_dash") ?></h4>
        </div>-->

        <div class="card-group">
            <div class="card">
                <div class="card-body" style="padding: 15px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex no-block align-items-center">
                                <div>
                                    <h3><i class="fa fa-graduation-cap"></i></h3>
                                    <p class="text-muted">TOTAL STUDENTS</p>
                                </div>
                                <div class="ml-auto">
                                    <h2 class="counter text-primary"><?php echo $users[2]->count; ?></h2>
                                    <!--<span><pre><?php print_r($users); ?></pre></span>-->
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="progress">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <!-- Column -->
            <div class="card">
                <div class="card-body"style="padding: 15px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex no-block align-items-center">
                                <div>
                                    <h3><i class="fa fa-user-secret"></i></h3>
                                    <p class="text-muted">TOTAL TEACHERS</p>
                                </div>
                                <div class="ml-auto">
                                    <h2 class="counter text-cyan">169</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="progress">
                                <div class="progress-bar bg-cyan" role="progressbar" style="width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <!-- Column -->
            <div class="card">
                <div class="card-body" style="padding: 15px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex no-block align-items-center">
                                <div>
                                    <h3><i class="fa fa-user"></i></h3>
                                    <p class="text-muted">TOTAL PARENTS</p>
                                </div>
                                <div class="ml-auto">
                                    <h2 class="counter text-success">431</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <div class="card">
                <div class="card-body" style="padding: 15px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex no-block align-items-center">
                                <div>
                                    <h3><i class="fa fa-money"></i></h3>
                                    <p class="text-muted">TOTAL DUE FEE</p>
                                </div>
                                <div class="ml-auto">
                                    <h2 class="counter text-purple">157</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="progress">
                                <div class="progress-bar bg-purple" role="progressbar" style="width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
        </div>

    </div>
    <?php include(APPPATH . "views/inc/footer.php"); ?>