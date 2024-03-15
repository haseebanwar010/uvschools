<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-4 col-xs-6">
                <h4 class="page-title"><?php echo lang('reports_all') ?></h4>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-8 col-xs-6">

                <ol class="breadcrumb">
                    <li><a href="<?php echo site_url('/dashboard') ?>"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li><a href="<?php echo site_url('/reports') ?>"><?php echo lang('reports_all') ?></a></li>
                </ol>
            </div>
        </div>
       
        <div class="white-box well" style="border-radius: 10px; box-shadow: 5px 5px 5px;">
            <div class="row m-t-40">
                 
                    <a href="reports/students" class="col-md-6 col-lg-3 col-xlg-3" style="margin-top: 15px;">
                        <div class="card">
                            <div class="box bg-info text-center" style="border-radius: 10px; box-shadow: 5px 5px 5px">
                                <h1 class="font-light text-white"><?php echo lang('lbl_students'); ?></h1>
                                <h6 class="text-white"><?php if(isset($count_std)){echo $count_std; } ?></h6>
                            </div>
                        </div>
                    </a>
                    
                    <a href="reports/employees" class="col-md-6 col-lg-3 col-xlg-3" style="margin-top: 15px;">
                        <div class="card">
                            <div class="box bg-warning text-center" style="border-radius: 10px; box-shadow: 5px 5px 5px;">
                                <h1 class="font-light text-white"><?php echo lang('heading_all_employee'); ?></h1>
                                <h6 class="text-white"><?php if(isset($count_emp)){echo $count_emp; } ?></h6>
                            </div>
                        </div>
                    </a>
                    
                    <a href="reports/accounts_report" class="col-md-6 col-lg-3 col-xlg-3" style="margin-top: 15px;">
                        <div class="card">
                            <div class="box bg-primary text-center" style="border-radius: 10px; box-shadow: 5px 5px 5px;">
                                <h1 class="font-light text-white"><?php echo lang('lbl_accounts'); ?></h1>
                                <h6 class="text-white"><?php echo lang('lbl_accounts_reports'); ?></h6>
                            </div>
                        </div>
                    </a>

                    <a href="reports/exam_report" class="col-md-6 col-lg-3 col-xlg-3" style="margin-top: 15px;">
                        <div class="card">
                            <div class="box bg-danger text-center" style="border-radius: 10px;
    box-shadow: 5px 5px 5px;">
                                <h1 class="font-light text-white"><?php echo lang('lbl_exams'); ?></h1>
                                <h6 class="text-white"><?php echo lang('lbl_examination'); ?></h6>
                            </div>
                        </div>
                    </a>

                    <a href="reports/fee" class="col-md-6 col-lg-3 col-xlg-3" style="margin-top: 15px;">
                        <div class="card">
                            <div class="box bg-success text-center" style="border-radius: 10px;
    box-shadow: 5px 5px 5px;">
                                <h1 class="font-light text-white"><?php echo lang('fee_report'); ?></h1>
                                <h6 class="text-white"><?php echo lang('fee_report'); ?></h6>
                            </div>
                        </div>
                    </a>
    <!--                <a href="javascipt:void(0)" class="col-md-6 col-lg-3 col-xlg-3" style="margin-top: 15px;">-->
    <!--                    <div class="card">-->
    <!--                        <div class="box bg-warning text-center" style="border-radius: 10px;-->
    <!--box-shadow: 5px 5px 5px;">-->
    <!--                            <h1 class="font-light text-white"><?php echo lang('lbl_salary'); ?></h1>-->
    <!--                            <h6 class="text-white"><?php echo lang('salary_report'); ?></h6>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                </a>-->

    <!--                <a href="javascipt:void(0)" class="col-md-6 col-lg-3 col-xlg-3" style="margin-top: 15px;">-->
    <!--                    <div class="card">-->
    <!--                        <div class="box bg-info text-center" style="border-radius: 10px;-->
    <!--box-shadow: 5px 5px 5px;">-->
    <!--                            <h1 class="font-light text-white"><?php echo lang('lbl_expenses'); ?></h1>-->
    <!--                            <h6 class="text-white"><?php echo lang('expenses_report'); ?></h6>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                </a>-->

                    <a href="reports/payroll_report" class="col-md-6 col-lg-3 col-xlg-3" style="margin-top: 15px;">
                        <div class="card">
                            <div class="box bg-info text-center" style="border-radius: 10px; box-shadow: 5px 5px 5px;">
                                <h1 class="font-light text-white"><?php echo lang('lbl_payroll'); ?></h1>
                                <h6 class="text-white"><?php echo lang('payroll_report'); ?></h6>
                            </div>
                        </div>
                    </a>
                    
                </div>
        </div>
        <!--./row-->

    </div>
</div>

<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>
   