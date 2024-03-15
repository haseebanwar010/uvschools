<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<div ng-controller="academicCtrl" ng-init="get_years()">
	<!-- Page Content -->
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row bg-title">
				<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
					<h4 class="page-title"><?php echo lang('lbl_academic_year_setup');?></h4>
				</div>
				<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
					<ol class="breadcrumb">
						<li><a href="#"><?php echo lang('settings');?></a></li>
						<li><a href="active"><?php echo lang('lbl_academic_year_setup');?></a></li>
					</ol>
				</div>
			</div>

			<div class="hint"><?php echo lang('lbl_help_academic_year_setup');?></div>
			
				<div class="white-box well" ng-if="found">
					<a href="settings/academic"><i class="fa fa-arrow-left"></i><?php echo lang('lbl_go_back_to_acad_setting');?> </a>
					<h4>{{academic_year}}</h4>
					<div class="row">
						<div class="col-md-3">
							<a href="javascipt:void(0)">
								<div class="card">
									<div class="box bg-primary text-center">
										<h1 class="font-light text-white">{{classes}}</h1>
										<h4 class="text-white"></i><?php echo lang('lbl_classes');?></h4>
									</div>
								</div>
							</a>
							<div class="form-group checkbox checkbox-info checkbox" ng-if="classes == 0">
								<input value="" id="check_classes" type="checkbox" disabled="" ng-model="myModel.class">
								<label for="check_classes"><?php echo lang('lbl_import_classes');?> </label>
							</div>
						</div>

						<div class="col-md-3">
							<a href="javascipt:void(0)">
								<div class="card">
									<div class="box bg-danger text-center">
										<h1 class="font-light text-white">{{batches}}</h1>
										<h4 class="text-white"><?php echo lang('lbl_batches');?></h4>
									</div>
								</div>
							</a>
							<div class="form-group checkbox checkbox-info checkbox" ng-if="classes == 0">
								<input value="" id="check_batches" type="checkbox" ng-model="myModel.batch" ng-change="batch_changed()">
								<label for="check_batches"><?php echo lang('lbl_import_batches');?></label>
							</div>
						</div>
						<div class="col-md-3">
							<a href="javascipt:void(0)">
								<div class="card">
									<div class="box bg-info text-center">
										<h1 class="font-light text-white">{{fee_types}}</h1>
										<h4 class="text-white"><?php echo lang('lbl_fee_types');?></h4>
									</div>
								</div>
							</a>
							<div class="form-group checkbox checkbox-info checkbox" ng-if="classes == 0">
								<input value="" id="check_fee" type="checkbox" ng-model="myModel.fee">
								<label for="check_fee"><?php echo lang('lbl_import_fee_types');?></label>
							</div>
						</div>
						<div class="col-md-3">
							<a href="javascipt:void(0)">
								<div class="card">
									<div class="box bg-warning text-center">
										<h1 class="font-light text-white">{{subjects}}</h1>
										<h4 class="text-white"><?php echo lang('lbl_subjects');?></h4>
									</div>
								</div>
							</a>
							<div class="form-group checkbox checkbox-info checkbox" ng-if="classes == 0">
								<input value="" id="check_subject" type="checkbox" ng-model="myModel.subject" ng-disabled="myModel.batch == false" ng-change="subject_changed()">
								<label for="check_subject"><?php echo lang('lbl_import_subjects');?></label>
							</div>
						</div>
						<div class="col-md-3">
							<a href="javascipt:void(0)">
								<div class="card">
									<div class="box bg-success text-center">
										<h1 class="font-light text-white">{{teachers}}</h1>
										<h4 class="text-white"><?php echo lang('lbl_teacher_assigned');?></h4>
									</div>
								</div>
							</a>
							<div class="form-group checkbox checkbox-info checkbox" ng-if="classes == 0">
								<input value="" id="check_teacher" type="checkbox" ng-model="myModel.teacher" ng-disabled="myModel.subject == false">
								<label for="check_teacher"><?php echo lang('lbl_import_teacher_assigned');?> </label>
							</div>
						</div>
						<div class="col-md-3">
							<a href="javascipt:void(0)">
								<div class="card">
									<div class="box bg-primary text-center">
										<h1 class="font-light text-white">{{subject_groups}}</h1>
										<h4 class="text-white"><?php echo lang('lbl_subject_group');?></h4>
									</div>
								</div>
							</a>
							<div class="form-group checkbox checkbox-info checkbox" ng-if="classes == 0">
								<input value="" id="check_subject_group" type="checkbox" ng-model="myModel.subject_group" ng-disabled="(myModel.batch == false) || (myModel.subject == false)">
								<label for="check_subject_group"><?php echo lang('lbl_import_subject_group');?></label>
							</div>
						</div>
						<div class="col-md-3">
							<a href="javascipt:void(0)">
								<div class="card">
									<div class="box bg-danger text-center">
										<h1 class="font-light text-white">{{periods}}</h1>
										<h4 class="text-white"><?php echo lang('tab_periods');?></h4>
									</div>
								</div>
							</a>
							<div class="form-group checkbox checkbox-info checkbox" ng-if="classes == 0">
								<input value="" id="check_period" type="checkbox" ng-model="myModel.period" ng-disabled="myModel.batch == false" ng-change="period_changed()">
								<label for="check_period"><?php echo lang('lbl_import_period');?></label>
							</div>
						</div>
						<div class="col-md-3">
							<a href="javascipt:void(0)">
								<div class="card">
									<div class="box bg-info text-center">
										<h1 class="font-light text-white">{{timetables}}</h1>
										<h4 class="text-white"><?php echo lang('lbl_timetable_period');?></h4>
									</div>
								</div>
							</a>
							<div class="form-group checkbox checkbox-info checkbox" ng-if="classes == 0">
								<input value="" id="check_table" type="checkbox" ng-model="myModel.timetable" ng-disabled="(myModel.batch == false) || (myModel.period == false) || (myModel.subject == false)">
								<label for="check_table"><?php echo lang('lbl_imported_timetable');?></label>
							</div>
						</div>
					</div>
					<button class="btn btn-primary" data-toggle="modal" data-target="#importModal" ng-if="classes == 0"><?php echo lang('import');?></button>
					<a href="javascript:void(0)" value="<?php echo $this->session->userdata("userdata")["academic_year"]; ?>,settings/revert_academic_year" class="sa-warning btn btn-danger text-white" ng-if="classes != 0"><i class="fa  fa-trash-o"></i><?php echo lang('lbl_revert');?></a>
				</div>
			
				<div class="white-box well" ng-if="!found">
					<h5 class="text-danger"><?php echo lang('lbl_no_active_record_found');?></h5>
					<a href="settings/academic"><i class="fa fa-arrow-left"></i><?php echo lang('lbl_go_back_to_acad_setting');?></a>
				</div>
			

			<div id="importModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content" id="import-modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class=""><?php echo lang('lbl_import_data_form');?></h4>
						</div>
						<div class="modal-body">
							<div ng-repeat="y in years">
								<label class="custom-control custom-radio">
                                        <input name="year" class="custom-control-input" value="{{y.id}}" ng-model="myModel.year" type="radio">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">{{y.name}}</span>
                                </label>
                            </div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang('btn_cancel') ?></button>
							<button type="button" id="send_response" class="btn btn-success waves-effect waves-light" ng-disabled="!myModel.year" ng-click="shift_data()"><?php echo lang('import');?></button>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<?php include(APPPATH . "views/inc/footer.php"); ?>