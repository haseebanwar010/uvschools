<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<!-- Page Content -->
<div id="page-wrapper" ng-controller="dashboardCtrl" ng-init="get_Today_Events()">
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
        <!-- modal to view event details for parent -->
        <div class="modal fade" id="exampleModalCenter">
            <div class="modal-dialog">
                <div class="modal-content" style="margin-top: 33%;">
                    <div class="panel panel-primary" style="margin-bottom: 0;">
                        <div class="panel-heading">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo lang("lbl_calendar_close") ?></span></button>
                            <div class="modal-title"></div>
                        </div>
                        <div class="panel-body">
                            <div class="error"></div>
                            <form class="form-material" id="crud-form">
                            <input type="hidden" id="start">
                            <input type="hidden" id="end">
                                <div class="form-group">
                                    <label class="control-label" for="title"></label>
                                    <input id="tit" name="title" type="text" class="form-control" placeholder="<?php echo lang('lbl_calendar_title') ?>" readonly/>
                                </div>                            
                                <div class="form-group">
                                    <label class="control-label" for="description"></label>
                                    <textarea class="form-control" id="desc" name="description" rows="3" cols="50" placeholder="<?php echo lang('lbl_calendar_description') ?>" readonly></textarea>
                                </div>
                                
                            </form>
                            <div class="modal-footer panel-footer" style="border:0; padding-right:0;">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("lbl_calendar_cancel") ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--End modal to view event details for parent -->
        
        <?php echo $this->session->flashdata('alert_no_permission'); ?>
        
        
        <div class="hint"><?php echo lang('help_dashboard'); ?></div>
        <div class="row" style="margin-top: 10px;">
        <div class="col-md-12" style="padding-right:0; padding-left:0;">
                    <div class="col-md-8">
                        <div class="white-box"  style="border: 1px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; color:#042954;">
                            <div class="box-title" style="color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>">Calendar</div>
                            <div class="alert" style="position: fixed; top: 0; left: 0; width: 100%; z-index: 1000;"></div>
                            <div id='calendar'></div>
                        </div>
                    </div>

                    <!-- Column -->
                    <div class="col-lg-4 col-md-12">
                        <div class="row">
                            <!-- Column -->
                            <div class="col-md-12">
                                <div class="card" style="background:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; height: 250px; border: 1px solid;">
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
                                <div class="card" style="height: 317px; background: <?php echo hex2rgba($this->session->userdata('userdata')['theme_color']); ?>;
background: linear-gradient(294deg, <?php echo hex2rgba($this->session->userdata('userdata')['theme_color']); ?> 0%, rgba(9,100,121,1) 35%, <?php echo hex2rgba($this->session->userdata('userdata')['theme_color']); ?> 100%); border: 1px solid;">
                                    <div class="card-body">
                                        <div id="myCarouse2" class="carousel slide" data-ride="carousel">
                                            <!-- Carousel items -->
                                            <div class="carousel-inner">
                                                <div class="carousel-item active" ng-if="calendarEvents.records.lenght>0">
                                                    <h4 class="display-4 text-white cmin-height">Events</h4>
                                                    <marquee onmouseover="this.stop();" onmouseout="this.start();" width="100%" direction="up" scrollamount="2" height="230px">
                                                        <ul class="text-white" ng-repeat="c in calendarEvents.records" >
                                                            
                                                            <li>{{c.title}}</li>
                                                            
                                                        </ul>
                                                    </marquee>
                                                   
                                                </div>
                                                <div class="carousel-item active" ng-if="calendarEvents.records.length==0">
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
        
            
        </div>
        <div class="row" ng-init="init_fee_summary()">
            <div class="col-md-8">
                <div class="card" id="fee-summary-container">
                    <div class="card-body">
                        <h4 class="card-title text-info"><?php echo lang("lbl_fee_summary"); ?><button type="button" class="btn btn-primary btn-sm pull-right" ng-click="show_fee_graph(overall)"><i class="fa fa-bar-chart"></i> <?php echo lang("lbl_overall"); ?></button></h4><br>
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

            <div class="col-md-4">
                <div class="card" style="border: 1px solid blue;">
                    <div class="card-body">
                        <h4 class="card-title text-info"><?php echo lang("lbl_fee_chart"); ?> {{selected_class_batch_fee}}</h4>
                        <div id="fee-graph" style="height: 355px;"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- .right-sidebar -->
        <!-- Include yout right sidebar here -->
        <!-- /.right-sidebar -->
    </div>
    <?php include(APPPATH . "views/inc/footer.php"); ?>
    <script src='assets/fullcalendar/js/main.php'></script>