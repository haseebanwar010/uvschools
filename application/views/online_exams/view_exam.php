<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<div>
    <!-- Page Content -->
    <div id="page-wrapper" ng-controller="onlineExamsController" ng-init="initStartExam(<?php echo $exam_id; ?>, <?php echo $paper_id; ?>);">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">View Exams</h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="#"><?= lang('crumb_dashboard') ?></a></li>
                        <li><a href="#">View Exams</a></li>
                        <li class="active">Student</li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <div class="hint">help_online_exams_view</div>
            <!-- Page Content start here -->
            <!--.row-->
            <div class="row">
            
                <div class="col-md-12">
                    <div class="white-box">
                        <div class="row">
                        <div class="col-md-4">
                            <h4>Total Questions: {{questionModel["0"].number_of_questions}}</h4>
                        </div>
                        <div class="col-md-4 text-center">
                            <h4><b>{{questionModel["0"].paper_name}}</b></h4>
                        </div>
                        <div class="col-md-4"><h4 class="text-danger text-right"><i class="fa fa-clock-o"></i> <span id="timer"></span></h4></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12" ng-repeat="q in questionModel">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            {{q.question}}
                            <div class="pull-right text-info">Question # {{q.question_no}}</div>
                        </div>
                        <div class="panel-body">
                        

                            <div ng-if="q.question_type == 'single_answer'">
                                <div class="form-group" ng-repeat="(key, value) in q.options" ng-if="$index != 4">
                                    <span class="radio radio-info">
                                        <input type="radio" id="{{q.id}}{{$index}}" ng-model="q.answer" value="{{q.alphabets[key]}}"/>
                                        <label for="{{q.id}}{{$index}}">{{value}}</label>
                                    </span>
                                </div>
                            </div>

                            <div ng-if="q.question_type == 'true_false'">
                                <div class="form-group">
                                    <span class="radio radio-info">
                                        <input type="radio" id="{{q.id}}true" ng-model="q.answer" value="true"/>
                                        <label for="{{q.id}}true">True</label>
                                    </span>
                                    <span class="radio radio-info radio-inline">
                                        <input type="radio" id="{{q.id}}false" ng-model="q.answer" value="false" />
                                        <label for="{{q.id}}false">False</label>
                                    </span>
                                </div>
                            </div>

                            <div ng-if="q.question_type == 'multi_answer'">
                                    <div class="form-group" ng-repeat="(key, value) in q.options">
                                        <span class="checkbox checkbox-info">
                                            <input type="checkbox" id="{{q.id}}{{$index}}" ng-model="q.answer[$index]" />
                                            <label for="{{q.id}}{{$index}}">{{value}}</label>
                                        </span>
                                    </div>
                            </div>

                            <div ng-if="q.question_type == 'single_fill_in_the_blank'">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Enter your filling the blak answer" ng-model="q.answer">
                                </div>
                            </div>

                            <div ng-if="q.question_type == 'double_fill_in_the_blank'">
                                <div class="form-group">
                                    <input type="text" class="form-control mb-2" placeholder="Enter your first answer" ng-model="q.answer['first']">
                                    <input type="text" class="form-control" placeholder="Enter your second answer" ng-model="q.answer['second']">
                                </div>
                            </div>


                        </div>
                    </div>  
                </div>
                
                <div class="col-md-12 text-center">
                    <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#confirmModal" ng-if="!submitted" style="width: 300px;">Save & Submit Paper</button>
                </div>

                <div id="confirmModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Confirmation</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                                <p>Do you want to submit? You won't be able to undo this.</p>


                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang('btn_cancel') ?></button>
                                <button type="button" class="btn btn-success waves-effect waves-light"  ng-click="submitPaper()">Yes</button>
                            </div>
                        </div>
                    </div>
                </div>
            <!--./row-->
            <!--page content end here-->
        </div>
    </div>
    <!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>