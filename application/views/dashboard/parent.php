<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<?php
    $UserData = $this->session->userdata('userdata');
?>
<!-- Page Content -->
<div id="page-wrapper" ng-controller="Parent_dashboardCtrl" ng-init="init_parent_children_list()">
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

        
        <?php echo $this->session->flashdata('alert_no_permission'); ?>
        
        
        <div class="hint"><?php echo lang('help_dashboard'); ?></div>
       
        <div class="well">
            <h4><?php echo lang("lbl_parent_dsh") ?>::<?= lang("lbl_welcome_dash") ?></h4>
        </div>


       <div class="row">
        



<div class="container">  
        <ul class="nav nav-tabs">
            <li ng-repeat="c in childerns">
                <a data-toggle="tab" href="#{{c.student_id}}" ng-click="filter(c.student_id)">{{c.name}}</a>
            </li>
        </ul>

    <!-- <div class="tab-content">
        <div id="#264" class="tab-pane fade in active">
            <h3>HOME</h3>
            <p>Some content {{stdid}}</p>
        </div>
        <div id="#432" class="tab-pane fade in active">
            <h3>HOME</h3>
            <p>Some content.Some content.Some content.</p>
        </div>
    </div> -->
        <div class="row" >
            <div class="col-md-6">
                <div class="card" id="study-plan-statictics-container">
                    <div class="card-body">
                        <h4 class="card-title text-info">
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
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-info"><?php echo lang("lbl_study_plan_chart"); ?> {{selected_class_batch}}</h4>
                        <div id="morris-bar-chart" style="height: 355px;"></div>
                    </div>
                </div>
            </div>
        </div>
    <?php if( $UserData["sh_id"] != 108){ ?>
         <div class="row">
            <div class="col-md-6">
                <div class="card" id="fee-summary-container">
                    <div class="card-body">
                        <h4 class="card-title text-info"><?php echo lang("lbl_fee_summary"); ?>
                        <span class="pull-right">
                        <button type="button" ng-click="refreshFees()" class="mb-2 btn btn-info btn-sm"><i class="fa fa-refresh"></i> Refresh</button>
                        
                    </span></h4><br>
                        <span ng-if="fee_data.length===0" class="text-danger"><?php echo lang("no_record"); ?></span>
                        <div ng-if="fee_data.length!==0" class="table-responsive" style="height: 335px; overflow:auto;">
                            

                           

                            
                                    
                                    <div class="col-md-12 pl-0 pr-1">
                                        
                                    </div>
                                    <table class="table table-bordered table-striped">
                                        <tr>
                                            
                                            <td><?php echo lang('fully_paid'); ?></td>
                                            <td><?php echo lang('partial_paid'); ?></td>
                                            <td><?php echo lang('lbl_due_chart'); ?></td>
                                        </tr>
                                        <tr>
                                            
                                            <td>{{fee_data['total_paid']}}</td>
                                            <td>{{fee_data['total_partial']}}</td>
                                            <td>{{fee_data['total_due']}}</td>
                                        </tr>
                                    </table>
                                
                            
                        </div>
                    </div>
                </div>
            </div>
           
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title text-info"><?php echo lang("lbl_fee_chart"); ?> {{selected_class_batch_fee}}</h4>
                        <div id="fee-graph" style="height: 355px;"></div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
        <!-- .right-sidebar -->
        <!-- Include yout right sidebar here -->
        <!-- /.right-sidebar -->
    </div>

</div>

    <?php include(APPPATH . "views/inc/footer.php"); ?>

