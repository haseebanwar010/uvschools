<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<style>
    .at-length{
        height: 362px;
        overflow: auto;
    }
    @media (min-width: 768px) and (max-width: 1024px) {  
        .at-length{
            height: 340px;
            overflow: auto; 
        }
    }
    .setheight{
        height: 430px;
    }
</style>
<!-- Page Content -->
<div id="page-wrapper" ng-controller="dashboardCtrl" ng-init="get_Today_Events();get_googledrivestatus();">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-md-4">
                <form class="form form-material">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="<?php echo lang('search'); ?>..." style="border-bottom: 1px solid #<?php echo substr($this->session->userdata('userdata')["theme_color"], 1) ?>;">
                        
                </div>
                
            </div>
            <div class="col-md-1" style="padding-left: 0px;">
                <button class="btn btn-outline-primary-custom" type="button"><i class="fa fa-search"></i></button>
                </form>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="dashboard/"><?= lang("lbl_dashboard"); ?></a></li>
                    <li class="active"><?= lang("lbl_dashboard"); ?></li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <!-- calendar modal --->
         <div class="modal fade" id="calendar-modal">
            <div class="modal-dialog">
                <div class="modal-content" style="margin-top: 33%;">
                    <div class="panel panel-primary" style="margin-bottom: 0;">
                        <div class="panel-heading">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">close</span></button>
                            <div class="modal-title"></div>
                        </div>
                        <div class="panel-body">
                            <div class="error"></div>
                            <form class="form-material" id="crud-form">
                            <input type="hidden" id="start">
                            <input type="hidden" id="end">
                                <div class="form-group">
                                    <label class="control-label" for="title"></label>
                                    <input id="title" name="title" type="text" class="form-control" placeholder="Enter Title" />
                                </div>                            
                                <div class="form-group">
                                    <label class="control-label" for="description"></label>
                                    <textarea class="form-control" id="description" name="description" rows="3" cols="50" placeholder="Enter Description"></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="color">Tag</label>
                                    <input id="color" name="color" type="text" class="form-control" readonly="readonly" />
                                </div>
                                
                                <div class="form-group">
                                    <label>Event Type</label>
                                    <select class="form-control" name="event_type" id="event_type" required="">
                                        <option>Choose Event Type</option>
                                        <option value="Event">Event</option>
                                        <option value="Holiday">Holiday</option>
                                    </select>
                                </div>

                                <div class="holiday box" id="showholidaytype">
                                    <div class="form-group">
                                    <label>Holiday Type</label>
                                    <select class="form-control" name="holiday_type" id="holiday_type" required="">
                                        <option>Choose Holiday Type</option>
                                        <option value="private_holiday">Event Holiday</option>
                                        <option value="public_holiday">Normal Holiday</option>
                                    </select>
                                </div>
                                </div>

                                <div class="form-group">
                                    <label>Access</label>
                                    <ul class="list-group">
                                        <li class="list-group-item" style="border:0px;">
                                            <div class="radio radio-primary radio-circle">
                                                <input type="radio" name="mode" value="personel" checked>
                                                <label>Personel</label><br>
                                                <input type="radio" name="mode" value="private" checked>
                                                <label>Private</label><br>
                                                <input type="radio" name="mode" value="public">
                                                <label>Public</label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </form>
                            <div class="modal-footer panel-footer" style="border:0; padding-right:0;">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- calendar modal end -->

        <!-- Alert for trial users -->
        <?php if ($this->session->userdata("userdata")["licence_type"] == "trial") { ?>
            <?php if ($this->session->userdata('userdata')['remaining_days']['years'] == 0 && $this->session->userdata('userdata')['remaining_days']['months'] == 0 && $this->session->userdata('userdata')['remaining_days']['days'] <= 7) { ?>
                <div class="alert alert-danger"> 
                    <?= lang("remaining_days"); ?> 
                    <?php echo $this->session->userdata('userdata')['remaining_days']['days'] ?> 
                </div>
            <?php } ?>
        <?php } ?>
        <!-- End of alert -->

        <!-- Request Details Modal --->
        <div id="requestDetailModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel"><?php echo lang("lbl_details") ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th><?php echo lang("lbl_class") ?></th>
                                    <th><?php echo lang("lbl_batch") ?></th>
                                    <th><?php echo lang("lbl_username") ?></th>
                                    <th><?php echo lang("lbl_date") ?></th>
                                    <th><?php echo lang("lbl_status") ?></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                     <th><?php echo lang("lbl_class") ?></th>
                                    <th><?php echo lang("lbl_batch") ?></th>
                                    <th><?php echo lang("lbl_username") ?></th>
                                    <th><?php echo lang("lbl_date") ?></th>
                                    <th><?php echo lang("lbl_status") ?></th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <tr ng-repeat="d in requestResponse">
                                    <td>{{d.class_id}}</td>
                                    <td>{{d.batch_id}}</td>
                                    <td>{{d.user_id}}</td>
                                    <td>{{d.date}}</td>
                                    <td>{{d.status}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- ./Request Details Modal --->

        <style type="text/css">
            .fc-day:hover{
                background: <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>;
                opacity: 0.4;
            }
        </style>
        <!-- <div class="hint"><?php echo lang('help_dashboard'); ?></div> -->

        <div id="accordion">
          <div class="card" style="border-radius: 10px;">
            <div class="card-header bg-primary" id="headingOne" style="border-top-left-radius: 10px;border-top-right-radius: 10px;padding: 0.2rem 1rem">
              <h4 class="mb-0 text-white">
                
                  <i class="fa fa-tachometer" style="font-size: 1.5em;"></i> <b><?= lang("lbl_dashboard"); ?></b>

              <span class="pull-right">
                  <button class="btn btn-link text-white collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      <?= lang("lbl_help"); ?> <i class="fa fa-arrow-down"></i>
                  </button>
                </span>
          </h4>
      </div>

      <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
          <div class="card-body">
            <?php echo lang('help_dashboard'); ?>
        </div>
    </div>
</div>
</div>
        
        <div class="row">
            
                <div class="col-md-12" style="padding-right:0; padding-left:0;">

                    <div class="col-md-2">
                        <a href="<?php echo base_url(); ?>students/show">
                            <div class="card text-center" style="padding-top: 15px;padding-bottom: 10px;border: 2px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px;">
                            <i class="fa fa-users fa-5x"></i>
                            <div class="card-body" style="padding-top: 0;">
                                <h3 class="counter" style="margin-bottom: 0; color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>;"><b><?php echo $student_count ?></b></h3>
                                <span class="text-muted custom-wrap"><?php echo lang('total_students') ?></span>
                            </div>
                        </div>
                        </a>
                    </div>

                    <div class="col-md-2">
                        <a href="<?php echo base_url(); ?>employee/all">
                            <div class="card text-center" style="padding-top: 15px;padding-bottom: 10px;border: 2px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px;">
                                <i class="fa fa-graduation-cap fa-5x"></i>
                                <div class="card-body" style="padding-top: 0;">
                                    <h3 class="counter" style="margin-bottom: 0; color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>;"><b><?php echo $employee_count ?></b></h3>
                                    <span class="text-muted custom-wrap"><?php echo lang('total_emploeyees') ?></span>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-2">
                        <a href="<?php echo base_url(); ?>parents/all">
                            <div class="card text-center" style="padding-top: 15px;padding-bottom: 10px;border: 2px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px;">
                                <i class="fa fa-user fa-5x"></i>
                                <div class="card-body" style="padding-top: 0;">
                                    <h3 class="counter" style="margin-bottom: 0; color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>;"><b><?php echo $parent_count ?></b></h3>
                                    <span class="text-muted custom-wrap"><?php echo lang('total_parents') ?></span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php if( $UserData["sh_id"] != 108){ ?>
                    <div class="col-md-2">
                        <a href="<?php echo base_url(); ?>fee/statistics">
                            <div class="card text-center" style="padding-top: 15px;padding-bottom: 10px;border: 2px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px;">
                                <i class="fa fa-calculator fa-5x"></i>
                                <div class="card-body" style="padding-top: 0;">
                                    <h3 class="counter" style="margin-bottom: 0; color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>;"><b><?php echo $dueFees["unpaid_count"]; ?></b></h3>
                                    <span class="text-muted custom-wrap"><?php echo lang('total_due_fee') ?></span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php } ?>    
                    <div class="col-md-2">
                        <a href="<?php echo base_url(); ?>announcements">
                            <div class="card text-center" style="padding-top: 15px;padding-bottom: 10px;border: 2px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px;">
                                <i class="fa fa-bullhorn fa-5x"></i>
                                <div class="card-body" style="padding-top: 0;">
                                    <h3 class="counter" style="margin-bottom: 0; color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>;"><b>{{announcement.length}}</b></h3>
                                    <span class="text-muted custom-wrap"><?php echo lang('lbl_announcements') ?></span>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-2">
                        <a href="<?php echo base_url(); ?>messages/show">
                            <div class="card text-center" style="padding-top: 15px;padding-bottom: 10px;border: 2px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px;">
                                <i class="fa fa-envelope fa-5x"></i>
                                <div class="card-body" style="padding-top: 0;">
                                    <h3 class="counter" style="margin-bottom: 0; color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>;"><b><?php echo  count($inbox); ?></b></h3>
                                    <span class="text-muted custom-wrap"><?php echo lang("lbl_total_messages"); ?></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-12" style="padding-right:0; padding-left:0;">
                    <div class="col-md-8">
                        <div class="white-box"  style="border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px; color:#042954;">
                            <div class="box-title" style="color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>"><?php echo lang('lbl_calendar')?></div>
                            <div class="alert" style="position: fixed; top: 0; left: 0; width: 100%; z-index: 1000;"></div>
                            <div id='calendar'></div>
                        </div>
                    </div>

                    <!-- Column -->
                    <div class="col-lg-4 col-md-12">
                        <div class="row">
                            <!-- Column -->
                            <div class="col-md-12">
                                <div class="card" style="background:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; height: 250px; border-radius: 10px; border: 1px solid;">
                                    <div class="card-body ">
                                        <div class="row weather">
                                            <div class="col-7 display-4 text-white custom-wrap" style="margin-top: 65px;" ng-if="calendarEvents.records.length>0">{{calendarEvents.records[0].title}}</div>
                                            <div class="col-7 display-4 text-white custom-wrap" style="margin-top: 65px;" ng-if="calendarEvents.records.length==0"><?php echo lang('lbl_calendar_sidEvent') ?></div>
                                            <div class="col-5 text-right">
                                                <h1 class=""><i class="fa fa-calendar"></i></h1>
                                                <b class="text-white">{{calendarEvents.current_day_of_week}}</b>
                                                <p class="op-5 text-white">{{calendarEvents.month }}, {{calendarEvents.day}}</p>
                                            </div>
                                            <div class="col-12">
                                                
                                                <p class="text-white custom-wrap" style="line-height: 80px;">{{calendarEvents.records[0].description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-12">
                                <div class="card" style="height: 317px; border-radius: 10px; background: <?php echo hex2rgba($this->session->userdata('userdata')['theme_color']); ?>;
background: linear-gradient(294deg, <?php echo hex2rgba($this->session->userdata('userdata')['theme_color']); ?> 0%, rgba(9,100,121,1) 35%, <?php echo hex2rgba($this->session->userdata('userdata')['theme_color']); ?> 100%); border: 1px solid;">
                                    <div class="card-body">
                                        <div id="myCarouse2" class="carousel slide" data-ride="carousel">
                                            <!-- Carousel items -->
                                            <div class="carousel-inner">
                                                <div class="carousel-item active" ng-if="calendarEvents.records.length != 0">
                                                    <h4 class="display-4 text-white cmin-height"><?php echo lang('lbl_calendar_sidNEvent') ?></h4>
                                                    <marquee onmouseover="this.stop();" onmouseout="this.start();" width="100%" direction="up" scrollamount="2" height="230px">
                                                        <ul class="text-white" ng-repeat="c in calendarEvents.records">
                                                            
                                                            <li>{{c.title}}</li>
                                                            
                                                        </ul>
                                                    </marquee>
                                                   
                                                </div>
                                                <div class="carousel-item active" ng-if="calendarEvents.records.length == 0 ">
                                                    <h3 class="text-white text-center" style="margin-top: 120px;"><?php echo lang('lbl_calendar_sidNoDbEvent') ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                        </div>
                    </div>
                </div>
        
            <!-- Start::Counter Section -->
            <!--<div class="col-md-12">
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
                                            <h2 class="counter text-info"><?php echo $student_count ?></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
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
                                            <h2 class="counter text-info"><?php echo $employee_count ?></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
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
                                            <h2 class="counter text-info"><?php echo $parent_count ?></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
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
                                            <h2 class="counter text-info"><?php echo $dueFees["unpaid_count"]; ?></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>-->
        </div>
        <!-- End::Counter Section -->
        <!--<div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div style="padding:5px 0px 15px 0px;">
                            <span class="card-title"><?php echo str_replace('5', $countUnread['unread'], lang('new_messages_dashboard')) ?></span>
                            <a href="<?php echo base_url('messages/show'); ?>" class="btn btn-info btn-small btn-sm pull-right"><?php echo lang('inbox') ?></a>
                        </div>
                        <div class="message-box">
                            <div class="message-widget message-scroll">
                                <?php
                                if (count($inbox) > 0) {
                                    $index = 1;
                                    foreach ($inbox as $in) {
                                        if ($in["is_read"] == 0 && $index <= 5) {
                                            ?>
                                            <a href="<?php echo base_url() ?>messages/view/<?php echo $in["id"]; ?>">
                                                <div class="user-img"> <img src="uploads/user/<?= $in["avatar"]; ?>" alt="user" class="img-circle"> 
                                                    <span class="profile-status online pull-right"></span> 
                                                </div>
                                                <div class="mail-contnet">
                                                    <h5><?= $in["name"]; ?></h5> 
                                                    <span class="mail-desc"><?= $in["subject"] ?></span> 
                                                    <span class="time"><?= $in["created_at"]; ?></span> 
                                                </div>
                                            </a>
                                            <?php
                                            $index++;
                                        }
                                    }
                                } else {
                                    ?>
                                    <span class="text-danger"><?php echo lang('no_messages') ?></span>
<?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>-->
        
        <div class="row">
            <div class="col-md-12">
            <div class="white-box" style="border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px; color:#042954;">
                <h5 class="box-title" style="color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>"><?php echo lang('time_table') ?> -<?php echo lang(strtolower(date("l"))); ?></h5>
                <ul class="list-group" style="height: 400px; overflow-y: auto;">
                        <?php foreach ($timetables as $tb) { ?>
                        <li class="list-group-item">
                            <h4 class="box-title"><?php echo lang('lbl_class'); ?>-<?php echo $tb->name; ?></h4>
                                    <?php foreach ($tb->batches as $bth) { ?>
                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <th style="width:150px;"><?php echo $bth->name; ?></th>
        <?php foreach ($bth->timetables as $tb2) { ?>

                                            <td>
                                                <b><?php echo $tb2->title; ?></b><br/>
                                                <b><?php echo $tb2->subject; ?></b><br/>
                                                <?php if($tb2->subject != ""): ?>
                                                <small><b>T: </b><?php echo $tb2->teacher_name; ?></small><br/>
                                                <small><b>TA: </b><?php echo $tb2->assistant_name; ?></small><br/>
                                                <?php else: ?>
                                                <br/>
                                                <br/>
                                                <?php endif; ?>
                                                <small><?php echo $tb2->start_time . "-" . $tb2->end_time; ?></small>
                                            </td>
                                <?php } ?>
                                    </tr>
                                </table>
    <?php } ?>
                        </li>
<?php } ?>
                </ul>
            </div>
        </div>
        </div>


        <!--<div class="col-md-12">
            <div class="white-box">
                <h5 class="box-title text-info"><?php echo lang('time_table') ?> -<?php echo date("l") ?></h5>
                <ul class="list-group" style="height: 400px; overflow-y: auto;">
        <?php foreach ($classes as $clss) { ?>
                            <li class="list-group-item">
                                <h4 class="box-title">Class-<?php echo $clss->name; ?></h4>
    <?php foreach ($batches as $bth) {
        if ($bth->class_id == $clss->id) {
            ?>
                                                <table class="table table-bordered table-striped">
                                                    <tr>
                                                        <th style="width:150px;"><?php echo $bth->name; ?></th>
            <?php foreach ($timetables as $tb) { ?>
                <?php if ($tb->batch_id == $bth->id && $tb->day_of_week == strtolower(date("l"))) { ?>
                                                                        <td>
                                                                            <b><?php echo $tb->title; ?></b><br/>
                                                                            <b><?php echo $tb->subjectName; ?></b><br/>
                                                                            <small><?php echo $tb->teacher_name; ?></small><br/>
                                                                            <small><?php echo $tb->start_time . "-" . $tb->end_time; ?></small>
                                                                        </td>
                        <?php } ?>  
                    <?php } ?>
                                                    </tr>
                                                </table>
        <?php }
    }
    ?>
                            </li>
<?php } ?>
                </ul>
            </div>
        </div>-->

        <!--<div class="col-md-12">
            <div class="white-box">
                <h5 class="box-title text-info"><?php echo lang('time_table') ?> -<?php echo date("l") ?></h5>
                <ul class="list-group" style="height: 400px; overflow-y: auto;">
<?php foreach ($classes as $clss) { ?>
                            <li class="list-group-item">
                                <h4 class="box-title">Class-<?php echo $clss->name; ?></h4>
            <?php foreach ($batches as $bth) {
                if ($bth->class_id == $clss->id) {
                    ?>
                                                <table class="table table-bordered table-striped">
                                                    <tr>
                                                        <th style="width:150px;"><?php echo $bth->name; ?></th>
            <?php foreach ($timetables as $tb) { ?>
                        <?php if ($tb->batch_id == $bth->id && $tb->day_of_week == strtolower(date("l"))) { ?>
                                                                        <td>
                                                                            <b><?php echo $tb->title; ?></b><br/>
                                                                            <b><?php echo $tb->subjectName; ?></b><br/>
                                                                            <small><?php echo $tb->teacher_name; ?></small><br/>
                                                                            <small><?php echo $tb->start_time . "-" . $tb->end_time; ?></small>
                                                                        </td>
                        <?php } ?>  
                    <?php } ?>
                                                    </tr>
                                                </table>
        <?php }
    }
    ?>
                            </li>
<?php } ?>
                </ul>
            </div>
        </div>-->

        <div class="row" ng-init="init_employee_today_attenance()">
            <div class="col-md-6">
                <div class="card"  style="border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px; color:#042954;">
                    <div class="card-body" id="today_employee_attendance_table" ng-class="{setheight:employee_attendance.length==0}">
                        <div class="col-md-12" ng-if="employee_attendance.length>0">
                            <h4 class="card-title" style="color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>">
                                <?php echo lang('today_employee') ?>
                                <button type="button" ng-click="show_emp_attendance_individal_graph(emp_overall)" class="mb-2 pull-right btn btn-primary btn-sm"><i class="fa fa-bar-chart"></i> <?php echo lang("lbl_overall"); ?></button> 
                            </h4>
                            <div class="table-responsive at-length">
                                <table ng-if="employee_attendance.length > 0" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><?php echo lang('title_department') ?></th>
                                            <th><?php echo lang('lbl_present') ?></th>
                                            <th><?php echo lang('lbl_absent') ?></th>
                                            <th><?php echo lang('lbl_leave') ?></th>
                                            <th><?php echo lang('lbl_late') ?></th>
                                            <th><?php echo lang('lbl_action') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="e_att in employee_attendance">
                                            <td>{{ e_att.departmentname }}</td>
                                            <td>{{ e_att.present }}</td>
                                            <td>{{ e_att.absent }}</td>
                                            <td>{{ e_att.leav }}</td>
                                            <td>{{ e_att.late }}</td>
                                            <td><button type="button" ng-click="show_emp_attendance_individal_graph(e_att)" class="btn btn-secondary btn-sm"><i class="fa fa-bar-chart"></i></button> </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12" ng-if="employee_attendance.length==0">
                            <span class="text-danger"><?php echo lang('lbl_today_employees_attendance_not_found'); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card" style="border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px; color:#042954;">
                    <div class="card-body">
                        <h4 class="card-title" style="color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>" ng-if="selected_department_for_graph===''"><?php echo lang("lbl_attendance_chart"); ?> </h4>
                        <h4 class="card-title" style="color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>" ng-if="selected_department_for_graph!==''"><?php echo lang("lbl_attendance_chart"); ?> {{ selected_department_for_graph }}</h4>
                        <div class="d-flex m-b-40 align-items-center no-block">
                            <div class="ml-auto">
                                <ul class="list-inline font-12">
                                    <li><i class="fa fa-circle text-success"></i> <?php echo lang("lbl_present"); ?></li>
                                    <li><i class="fa fa-circle text-info"></i> <?php echo lang("lbl_leave"); ?></li>
                                    <li><i class="fa fa-circle text-warning"></i> <?php echo lang("lbl_late"); ?></li>
                                    <li><i class="fa fa-circle text-danger"></i> <?php echo lang("lbl_absent"); ?></li>
                                    <li><i class="fa fa-circle text-inverse"></i> <?php echo lang("lbl_unknown"); ?></li>
                                </ul>
                            </div>
                        </div>
                        <div id="morris-area-chart-emp-attendance" style="height: 300px;"></div>
                    </div>
                </div>
            </div>

        </div>
        
        <div class="row" ng-init="init_academic_wise_emp_att_graph()">
            <div class="col-md-12">
                <div class="card"  style="border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px; color:#042954;">
                    <div class="card-body">
                        <h4 class="card-title" style="color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>"><?php echo lang("lbl_employees_active_academic_year_attendance_chart"); ?></h4>
                        <div class="d-flex m-b-40 align-items-center no-block">
                            <div class="ml-auto">
                                <ul class="list-inline font-12">
                                    <li><i class="fa fa-circle text-success"></i> <?php echo lang("lbl_present"); ?></li>
                                    <li><i class="fa fa-circle text-info"></i> <?php echo lang("lbl_leave"); ?></li>
                                    <li><i class="fa fa-circle text-warning"></i> <?php echo lang("lbl_late"); ?></li>
                                    <li><i class="fa fa-circle text-danger"></i> <?php echo lang("lbl_absent"); ?></li>
                                </ul>
                            </div>
                        </div>
                        <div id="morris-area-chart-emp-academic-wise-attendance" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" ng-init="init_student_today_attenance()">

            <div class="col-md-6">
                <div class="card" style="border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px; color:#042954;">
                    <div class="card-body" id="today_student2_attendance_table" ng-class="{setheight:student_attendance.length==0}">
                        <div class="col-md-12" ng-if="student_attendance.length>0">
                            <h4 class="card-title" style="color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>">
                                <?php echo lang('today_students') ?>
                                <button type="button" ng-click="show_std_attendance_individal_graph(std_overall)" class="mb-2 pull-right btn btn-primary btn-sm"><i class="fa fa-bar-chart"></i> <?php echo lang("lbl_overall"); ?></button> 
                            </h4>
                            <div class="table-responsive at-length">
                                <table ng-if="student_attendance.length > 0" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><?php echo lang('lbl_class') ?></th>
                                            <th><?php echo lang('lbl_present') ?></th>
                                            <th><?php echo lang('lbl_absent') ?></th>
                                            <th><?php echo lang('lbl_leave') ?></th>
                                            <th><?php echo lang('lbl_late') ?></th>
                                            <th><?php echo lang('lbl_action') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="s_att in student_attendance">
                                            <td>{{ s_att.classname }}</td>
                                            <td>{{ s_att.present }}</td>
                                            <td>{{ s_att.absent }}</td>
                                            <td>{{ s_att.leav }}</td>
                                            <td>{{ s_att.late }}</td>
                                            <td><button type="button" ng-click="show_std_attendance_individal_graph(s_att)" class="btn btn-secondary btn-sm"><i class="fa fa-bar-chart"></i></button> </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12" ng-if="student_attendance.length==0">
                            <span class="text-danger"><?php echo lang('lbl_today_students_attendance_not_found'); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card" style="border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px; color:#042954;">
                    <div class="card-body">
                        <h4 class="card-title" style="color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>" ng-if="selected_class_for_graph===''"><?php echo lang("lbl_attendance_chart"); ?> <?php echo lang("lbl_overall"); ?></h4>
                        <h4 class="card-title" style="color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>" ng-if="selected_class_for_graph!==''"><?php echo lang("lbl_attendance_chart"); ?> {{ selected_class_for_graph }}</h4>
                        <div class="d-flex m-b-40 align-items-center no-block">
                            <div class="ml-auto">
                                <ul class="list-inline font-12">
                                    <li><i class="fa fa-circle text-success"></i> <?php echo lang("lbl_present"); ?></li>
                                    <li><i class="fa fa-circle text-info"></i> <?php echo lang("lbl_leave"); ?></li>
                                    <li><i class="fa fa-circle text-warning"></i> <?php echo lang("lbl_late"); ?></li>
                                    <li><i class="fa fa-circle text-danger"></i> <?php echo lang("lbl_absent"); ?></li>
                                    <li><i class="fa fa-circle text-inverse"></i> <?php echo lang("lbl_unknown"); ?></li>
                                </ul>
                            </div>
                        </div>
                        <div id="morris-area-chart-student-attendance" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row" ng-init="init_academic_wise_std_att_graph()">
            <div class="col-md-12">
                <div class="card" style="border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px; color:#042954;">
                    <div class="card-body">
                        <h4 class="card-title" style="color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>"><?php echo lang("lbl_students_active_academic_year_attendance_chart"); ?></h4>
                        <div class="d-flex m-b-40 align-items-center no-block">
                            <div class="ml-auto">
                                <ul class="list-inline font-12">
                                    <li><i class="fa fa-circle text-success"></i> <?php echo lang("lbl_present"); ?></li>
                                    <li><i class="fa fa-circle text-info"></i> <?php echo lang("lbl_leave"); ?></li>
                                    <li><i class="fa fa-circle text-warning"></i> <?php echo lang("lbl_late"); ?></li>
                                    <li><i class="fa fa-circle text-danger"></i> <?php echo lang("lbl_absent"); ?></li>
                                </ul>
                            </div>
                        </div>
                        <div id="morris-area-chart-std-academic-wise-attendance" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" ng-init="init_study_plan_statictics()">
            <div class="col-md-6">
                <div class="card" id="study-plan-statictics-container"  style="border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px; color:#042954;">
                    <div class="card-body">
                        <h4 class="card-title" style="color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>">
                            <?php echo lang("lbl_study_plan_summary"); ?>
                            <button type="button" ng-click="show_studyplan_overall_graph(studyplan_overall)" class="mb-2 pull-right btn btn-primary btn-sm"><i class="fa fa-bar-chart"></i> <?php echo lang("lbl_overall"); ?></button> 
                        </h4>
                        <span ng-if="studyplan.length===0" class="text-danger"><?php echo lang("no_record"); ?></span>
                        <div ng-if="studyplan.length!==0" class="table-responsive" style="height: 355px; overflow:auto;">
                            <div ng-repeat="s in studyplan">
                                <div class="col-md-12 pl-0 pr-1">
                                    <p>
                                        <strong>{{s.class_name}}</strong> - {{s.batch_name}}
                                        <button type="button" ng-if="s.subjects.length!==0" class="btn btn-secondary btn-sm pull-right" ng-click="show_studyplan_indivial_graph(s)"><i class="fa fa-bar-chart"></i></button>
                                    </p>
                                </div>
                                <table ng-if="s.subjects.length>0" class="table table-bordered table-striped">
                                    <tr>
                                        <th><?php echo lang("lbl_subject"); ?></th>
                                        <th><?php echo lang("lbl_details"); ?></th>
                                    </tr>
                                    <tr ng-repeat="ss in s.subjects">
                                        <td><strong>{{ss.name}}</strong></td>
                                        <td ng-if="ss.syllabus.data.length===0"><span class="text-danger"><?= lang("no_record"); ?></span></td>
                                        <td ng-if="ss.syllabus.data.length>0">
                                            <table class="table table-default">
                                                <tr>
                                                    <th ng-repeat="(key,sss) in ss.syllabus.counts">{{sss.name}}</th>
                                                </tr>
                                                <tr>
                                                    <td ng-if="ss.syllabus.data.length>0" ng-repeat="ssss in ss.syllabus.counts">{{ssss.count}}</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <div ng-if="s.subjects.length===0" class="col-md-12 well"><span class="text-danger"><?= lang("no_record"); ?></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card"  style="border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px; color:#042954;">
                    <div class="card-body">
                        <h4 class="card-title" style="color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>"><?php echo lang("lbl_study_plan_chart"); ?> {{selected_class_batch}}</h4>
                        <div id="morris-bar-chart" style="height: 355px;"></div>
                    </div>
                </div>
            </div>
        </div>
    <?php if( $UserData["sh_id"] != 108){ ?>
        <div class="row" ng-init="init_fee_summary()">
            <div class="col-md-6">
                <div class="card" id="fee-summary-container"  style="border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px; color:#042954;">
                    <div class="card-body">
                        <h4 class="card-title" style="color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>"><?php echo lang("lbl_fee_summary"); ?>
                        <span class="pull-right">
                        <button type="button" ng-click="refreshFees()" class="mb-2 btn btn-info btn-sm"><i class="fa fa-refresh"></i><?php echo lang('refresh'); ?></button>
                        <button type="button" class="mb-2 btn btn-primary btn-sm" ng-click="show_fee_graph(overall)"><i class="fa fa-bar-chart"></i> <?php echo lang("lbl_overall"); ?></button>
                    </span></h4><br>
                        <span ng-if="fee_data.length===0" class="text-danger"><?php echo lang("no_record"); ?></span>
                        <div ng-if="fee_data.length!==0" class="table-responsive" style="height: 335px; overflow:auto;">
                            <div ng-repeat="f in fee_data">
                                <div ng-repeat = "d in f.batches">
                                    <div class="col-md-12 pl-0 pr-1">
                                        <p>
                                            <strong>{{f.name}}</strong> - {{d.name}}
                                            <button type="button" class="btn btn-secondary btn-sm pull-right" ng-click="show_fee_graph(d)"><i class="fa fa-bar-chart"></i></button>
                                        </p>
                                    </div>
                                    <table class="table table-bordered table-striped">
                                        <tr>
                                            <td><?php echo lang('total_students_chart'); ?></td>
                                            <td><?php echo lang('fully_paid'); ?></td>
                                            <td><?php echo lang('partial_paid'); ?></td>
                                            <td><?php echo lang('lbl_due_chart'); ?></td>
                                        </tr>
                                        <tr>
                                            <td>{{d.total_students}}</td>
                                            <td>{{d.total_paid}}</td>
                                            <td>{{d.total_partial}}</td>
                                            <td>{{d.total_due}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card" style="border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px; color:#042954;">
                    <div class="card-body">
                        <h4 class="card-title" style="color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>"><?php echo lang("lbl_fee_chart"); ?> {{selected_class_batch_fee}}</h4>
                        <div id="fee-graph" style="height: 355px;"></div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>


        
    </div>
</div>
<?php include(APPPATH . "views/inc/footer.php"); ?>
<script src='assets/fullcalendar/js/main.php'></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#event_type").change(function()
        {
        if($(this).val() == "Holiday")
        {
        $("#showholidaytype").show();
        }
        else if($(this).val() == "Event")
        {
            $("#showholidaytype").hide();
        }
        });
        $("#showholidaytype").hide();
});
</script>