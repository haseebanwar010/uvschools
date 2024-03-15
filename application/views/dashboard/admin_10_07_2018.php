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

        <div class="row">
            <!-- Start::Counter Section -->
            <div class="col-md-12">
                <div class="card-group">
                    <div class="card" style="border-right: 1px solid #e5e5e5;">
                        <div class="card-body" style="padding: 15px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h3><i class="fa fa-graduation-cap"></i></h3>
                                            <p class="text-muted"><?php echo lang('total_students') ?></p>
                                        </div>
                                        <div class="ml-auto">
                                            <h2 class="counter text-info"><?php if(isset($users[2]->count)){ echo $users[2]->count; } else { echo "0"; }  ?></h2>
                                            <!--<span><pre><?php print_r($users); ?></pre></span>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="card" style="border-right: 1px solid #e5e5e5;">
                        <div class="card-body"style="padding: 15px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h3><i class="fa fa-user-secret"></i></h3>
                                            <p class="text-muted"><?php echo lang('total_emploeyees') ?></p>
                                        </div>
                                        <div class="ml-auto">
                                            <h2 class="counter text-info"><?php if(isset($users[3]->count)){ echo $users[3]->count; } else { echo "0"; }  ?></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="card" style="border-right: 1px solid #e5e5e5;">
                        <div class="card-body" style="padding: 15px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h3><i class="fa fa-user"></i></h3>
                                            <p class="text-muted"><?php echo lang('total_parents') ?></p>
                                        </div>
                                        <div class="ml-auto">
                                            <h2 class="counter text-info"><?php if(isset($users[1]->count)){ echo $users[1]->count; } else { echo "0"; } ?></h2>
                                        </div>
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
                                            <p class="text-muted"><?php echo lang('total_due_fee') ?></p>
                                        </div>
                                        <div class="ml-auto">
                                            <h2 class="counter text-info">157</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                </div>
            </div>
            <!-- End::Counter Section -->

            <div class="col-md-12 p-0">
                <!-- Start::Inbox Section-->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div style="padding:5px 0px 15px 0px;">
                                <span class="card-title"><?php echo lang('new_messages_dashboard') ?></span>
                                <a href="<?php echo base_url('messages/show'); ?>" class="btn btn-info btn-small btn-sm pull-right"><?php echo lang('inbox') ?></a>
                            </div>
                            <div class="message-box">
                                <div class="message-widget message-scroll">
                                    <!-- Message -->
                                    <?php if(count($inbox) > 0) {$index = 1; foreach ($inbox as $in) {
                                        if ($in["is_read"] == 0 && $index <= 5) { ?>
                                        <a href="<?php echo base_url()?>messages/view/<?php echo $in["id"]; ?>">
                                            <div class="user-img"> <img src="uploads/user/<?= $in["avatar"]; ?>" alt="user" class="img-circle"> 
                                                <span class="profile-status online pull-right"></span> 
                                            </div>
                                            <div class="mail-contnet">
                                                <h5><?= $in["name"]; ?></h5> 
                                                <span class="mail-desc"><?= $in["subject"] ?></span> 
                                                <span class="time"><?= $in["created_at"]; ?></span> 
                                            </div>
                                        </a>
                                        <?php $index++; } } } else { ?>
                                        <span class="text-danger"><?php echo lang('no_messages') ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End::Inbox Section-->
                    <div class="col-md-8">
                        <div class="white-box">
                            <h5 class="box-title"><?php echo lang('calendar_view') ?></h5>
                            <span class="text-info"><?php echo lang('coming_soon') ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 p-0">
                    <div class="col-md-6">
                        <div class="white-box">
                            <h5 class="box-title"><?php echo lang('today_employee') ?></h5>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><?php echo lang('title_department') ?></th>
                                        <th><?php echo lang('lbl_present') ?></th>
                                        <th><?php echo lang('lbl_absent') ?></th>
                                        <th><?php echo lang('lbl_leave') ?></th>
                                        <th><?php echo lang('lbl_late') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($emp_attendances) > 0) {
                                        foreach ($emp_attendances as $att2) { ?>
                                        <tr>
                                            <td><?= $att2->departmentname; ?></td>
                                            <td><?= $att2->present; ?></td>
                                            <td><?= $att2->absent; ?></td>
                                            <td><?= $att2->leav; ?></td>
                                            <td><?= $att2->late; ?></td>
                                        </tr>
                                        <?php }
                                    } else { ?>
                                    <tr>
                                        <td colspan="5"><?php echo lang('no_record'); ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="white-box">
                            <h5 class="box-title"><?php echo lang('today_students') ?></h5>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><?php echo lang('lbl_class') ?></th>
                                        <th><?php echo lang('lbl_present') ?></th>
                                        <th><?php echo lang('lbl_absent') ?></th>
                                        <th><?php echo lang('lbl_leave') ?></th>
                                        <th><?php echo lang('lbl_late') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($attendances) > 0) {
                                        foreach ($attendances as $att) { ?>
                                        <tr>
                                            <td><?= $att->classname; ?></td>
                                            <td><?= $att->present; ?></td>
                                            <td><?= $att->absent; ?></td>
                                            <td><?= $att->leav; ?></td>
                                            <td><?= $att->late; ?></td>
                                        </tr>
                                        <?php }
                                    } else { ?>
                                    <tr>
                                        <td colspan="5"><?php echo lang('no_record'); ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>

                <div class="col-md-12 p-0">
                    <div class="col-md-12 custom_disable">
                        <div class="white-box">
                            <h5 class="box-title"><?php echo lang('today_attendance_graph') ?></h5>
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex m-b-40 align-items-center no-block">
                                        <h5 class="card-title text-info"><?php echo lang('coming_soon') ?></h5>
                                        <div class="ml-auto">
                                            <ul class="list-inline font-12">
                                                <li><i class="fa fa-circle text-cyan"></i> A</li>
                                                <li><i class="fa fa-circle text-primary"></i> B</li>
                                                <li><i class="fa fa-circle text-purple"></i> C</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div id="morris-area-chart" style="height: 300px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    

<!--                    <div class="col-md-6 custom_disable">
                        <div class="white-box">
                            <h5 class="box-title"><?php echo lang('today_attendance_graph') ?></h5>
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex m-b-40 align-items-center no-block">
                                        <h5 class="card-title text-info"><?php echo lang('coming_soon') ?></h5>
                                        <div class="ml-auto">
                                            <ul class="list-inline font-12">
                                                <li><i class="fa fa-circle text-cyan"></i> A</li>
                                                <li><i class="fa fa-circle text-primary"></i> B</li>
                                                <li><i class="fa fa-circle text-purple"></i> C</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div id="morris-area-chart2" style="height: 300px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>-->
                </div>

                <div class="col-md-12 p-0">
                    <div class="col-md-6">
                        <div class="white-box">

                            <h5 class="box-title"><?php echo lang('fee_report') ?></h5>
                            <span class="text-info"><?php echo lang('coming_soon') ?></span>
                   <!-- <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Class</th>
                                <th>Present</th>
                                <th>Absent</th>
                                <th>Leave</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1st</td>
                                <td>30</td>
                                <td>20</td>
                                <td>10</td>
                            </tr>
                        </tbody>
                    </table>-->
                </div>
            </div>
            
            <div class="col-md-6 custom_disable">
                <div class="white-box">
                    <h5 class="box-title"><?php echo lang('fee_report_graph') ?></h5>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex m-b-40 align-items-center no-block">
                                <h5 class="card-title text-info"><?php echo lang('coming_soon') ?></h5>
                                <div class="ml-auto">
                                    <ul class="list-inline font-12">
                                        <li><i class="fa fa-circle text-cyan"></i> A</li>
                                        <li><i class="fa fa-circle text-primary"></i> B</li>
                                        <li><i class="fa fa-circle text-purple"></i> C</li>
                                    </ul>
                                </div>
                            </div>
                            <div id="morris-area-chart3" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="white-box">
                <h5 class="box-title text-info"><?php echo lang('time_table') ?> -<?php echo date("l")?></h5>
                <ul class="list-group" style="height: 400px; overflow-y: auto;">
                    <?php foreach ($classes as $clss) { ?>
                    <li class="list-group-item">
                        <h4 class="box-title">Class-<?php echo $clss->name; ?></h4>
                        <?php foreach ($batches as $bth) { if($bth->class_id == $clss->id) { ?>
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th style="width:150px;"><?php echo $bth->name; ?></th>
                                <?php foreach ($timetables as $tb) { ?>
                                <?php if($tb->batch_id == $bth->id && $tb->day_of_week == strtolower(date("l"))) { ?>
                                <td>
                                    <b><?php echo $tb->title; ?></b><br/>
                                    <b><?php echo $tb->subjectName; ?></b><br/>
                                    <small><?php echo $tb->start_time ."-". $tb->end_time; ?></small>
                                </td>
                                <?php } ?>  
                                <?php } ?>
                            </tr>
                        </table>
                        <?php } } ?>
                    </li>
                    <?php } ?>
                </ul>
                
                
            </div>
        </div>
    </div>



</div>
<?php include(APPPATH . "views/inc/footer.php"); ?>