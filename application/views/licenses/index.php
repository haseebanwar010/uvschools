<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('licenses') ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="dashboard/"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li class="active"><?php echo lang('licenses') ?></li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <div class="hint"><?php echo lang('help_license'); ?></div>


        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th><?php echo lang('license_type') ?></th>
                                    <th><?php echo lang('license_key') ?></th>
                                    <th><?php echo lang('start_date') ?></th>
                                    <th><?php echo lang('end_date') ?></th>
                                    <th><?php echo lang('amount_paid') ?></th>
                                    <th><?php echo lang('payment_status') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($myLicenses) > 0) { ?>
                                    <?php foreach ($myLicenses as $license) { ?>
                                        <tr>
                                            <td><?= $license->licence_type; ?></td>
                                            <td><?= $license->licence_key; ?></td>
                                            <td><?= $license->start_date; ?></td>
                                            <td><?= $license->end_date; ?></td>
                                            <td><?= $license->amount_paid; ?></td>
                                            <?php if ($license->payment_status == "pending") { ?>
                                                <td><?php echo lang('not_yet_paid') ?></td>
                                            <?php } else { ?>
                                                <td><?php echo lang('lbl_paid') ?></td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td><?php echo lang('licenses_not_found') ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- .right-sidebar -->
        <!-- Include yout right sidebar here -->
        <!-- /.right-sidebar -->
    </div>
    <?php include(APPPATH . "views/inc/footer.php"); ?>