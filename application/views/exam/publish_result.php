<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<div>
    <!-- Page Content -->
    <div id="page-wrapper" ng-controller="examActivities" ng-init="init();">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?php echo lang('lbl_publish_result') ?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="#"><?= lang('crumb_dashboard') ?></a></li>
                        <li><a href="#"><?php echo lang('crumb_settings') ?></a></li>
                        <li class="active"><?php echo lang('help_publish_result') ?></li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <div class="hint"><?php echo lang('help_publish_result'); ?></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-wrapper collapse in" aria-expanded="true">
                        	<div class="panel-body">
                        		<div class="well" id="download_search_filter">
                        			<div class="row">
                        				<!--row -->
                        				<div class="col-md-6">
                        					<div class="form-group form-material">
                        						<label class="control-label"><?php echo lang('lbl_class'); ?></label>
                        						<select class="form-control " name="grade-type" required="" ng-model="study.class" ng-change="getSections_upload()">
                        							<option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                        							<option ng-repeat="classi in study.classes" value="{{classi.id}}">{{classi.name}}</option>
                        						</select>
                        					</div>
                        				</div>
                        				<!--/span-->
                        				<div class="col-md-6" id="section_search_filter">
                        					<div class="form-group form-material">
                        						<label class="control-label"><?php echo lang('lbl_batch'); ?></label>
                        						<select class="form-control " name="grade-type" required="" ng-model="study.section">
                        							<option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                        							<option ng-repeat="batch in study.batches" value="{{batch.id}}">{{batch.name}}</option>

                        						</select>
                        					</div>
                        				</div>
                        				<!--/span-->

                        				<div class="row">

                        					<div class="col-md-12">
                        						<button class="btn btn-sm btn-primary" ng-click="search_publishExams()"><?php echo lang('search'); ?></button>
                        					</div>

                        				</div>
                        			</div>
                        		</div>
                        		<div class="col-md-12">
                        			<div class="row">
                        				<div class="col-md-12 white-box" style="padding-left: 1px; padding-right: 1px;">
                        					<div class="table-responsive">
                        						<table class="table table-bordered table-hover text-center" ng-if="publishExams.length > 0">
                        							<tr>
                        								<th class="text-center"><?php echo lang("lbl_exam_session");?></th>
                        								<th class="text-center"><?php echo lang("lbl_par_Class");?></th>
                        								<th class="text-center"><?php echo lang("lbl_Section_par");?></th>
                        								<th class="text-center"><?php echo lang("lbl_subject");?></th>
                        								<th class="text-center"><?php echo lang("lbl_exam_date");?></th>
                        								<th class="text-center"><?php echo lang("start_time");?></th>
                        								<th class="text-center"><?php echo lang("end_time");?></th>
                        								<th class="text-center"><?php echo lang("type_dt");?></th>
                        								<th class="text-center"><?php echo lang("total_marks");?></th>
                        								<th class="text-center"><?php echo lang("lbl_action");?></th>
                        							</tr>
                        							<tr ng-repeat="exam in publishExams">
                        								<td>{{exam.exam_title}}</td>
                        								<td>{{exam.class_name}}</td>
                        								<td>{{exam.batch_name}}</td>
                        								<td>{{exam.subject_name}}</td>
                        								<td>{{exam.exam_date}}</td>
                        								<td>{{exam.start_time}}</td>
                        								<td>{{exam.end_time}}</td>
                        								<td>{{exam.type}}</td>
                        								<td>{{exam.total_marks}}</td>
                        								<td>
                                                            <button ng-if="exam.published == 'no'" ng-click="publish_result(exam.id)" class="btn btn-success btn-rounded"><?php echo lang('publish_result') ?></button>
                                                            <button ng-if="exam.published == 'yes'" ng-click="unpublished_result(exam.id)" class="btn btn-danger btn-rounded"><?php echo lang('un_publish_result') ?></button>                        
                                                        </td>
                        							</tr>
                        						</table>
                        					</div>
                        				</div>
                        				<div class="col-md-12 white-box" ng-if="publishExams.length == 0">
                        					<p class="text-danger"><?php echo lang('no_exam_found') ?></p>
                        				</div>
                        			</div>
                        		</div>
                        	</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include(APPPATH . "views/inc/footer.php"); ?>