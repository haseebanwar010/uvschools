<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<style>
    .slick_block_sett
    {
        height: 150px !important;
    }
    .slick_block_sett img
    {
        width: 100% !important;
    }
  .test .ng-scope{
      padding: 5px; min-width: 149px !important;
  }
</style>
<!-- Page Content -->
<div id="page-wrapper">
	<div class="container-fluid" ng-controller="uploadController" ng-init="init();initSubmittedAss();initSubmittedHom();">
		<div class="row bg-title">
			<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
				<h4 class="page-title"><?php echo lang('class_work') ?></h4>
			</div>
			<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

				<ol class="breadcrumb">
					<li><a href="javascript:void(0)"><?php echo lang('lbl_dashboard') ?></a></li>
					<li><a href="javascript:void(0)"><?php echo lang('study_material') ?></a></li>
					<li class="active"><?php echo lang('lbl_upload') ?></li>


				</ol>
			</div>
		</div>
		<!-- /.row -->
		<div class="hint"><?php echo lang('help_class_work'); ?></div>

		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<div class="panel panel-primary">
					<div class="panel-wrapper collapse in" aria-expanded="true">
						<div class="panel-body">
							<ul class="nav customtab nav-tabs" role="tablist">
								<li role="presentation" class="nav-item"><a href="#assignment" class="nav-link active"
									aria-controls="profile" role="tab"
									data-toggle="tab"
									aria-expanded="true"><span
									class="visible-xs"><i class="fa fa-user"></i></span><span
									class="hidden-xs"><?php echo lang('assignment_list') ?></span></a>
								</li>
								<li role="presentation" class="nav-item"><a href="#homework" class="nav-link"
									aria-controls="profile" role="tab"
									data-toggle="tab"
									aria-expanded="true"><span
									class="visible-xs"><i class="fa fa-user"></i></span><span
									class="hidden-xs"><?php echo lang('homework_list') ?></span></a>
								</li>
							</ul>
							<div class="tab-content">
								<!-- assignment tab -->
								<div class="tab-pane active" id="assignment">
									<div class="well" >
										<form name="filterAssignments" ng-submit="submittedAssignments(filterAssignments.$valid)" novalidate="">
											<div class="row">
												<!--row -->	 
												<div class="col-md-2">
													<div class="form-group form-material">
														<label class="control-label"><?php echo lang('lbl_class'); ?></label>
														<select class="form-control " name="grade-type" ng-model="study.class" ng-change="getSections_upload()" required="required">
															<option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
															<option ng-repeat="classi in study.classes" value="{{classi.id}}">{{classi.name}}</option>
														</select>
														<div style="color: red" ng-show="class_error"><?php echo lang('lbl_field_required') ?></div>
													</div>
												</div>
												<div class="col-md-2" id="getBatch_filter">
													<div class="form-group form-material">
														<label class="control-label"><?php echo lang('lbl_batch'); ?></label>

														<select class="form-control"  name="grade-type" ng-model="study.section" ng-change="getSubjects_upload()" required="required">
															<option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
															<!-- <option ng-show="study.batches.length > 1" value="all"><?php echo lang('option_all'); ?></option> -->
															<option ng-repeat="batch in study.batches" value="{{batch.id}}">{{batch.name}}</option>

														</select>
														<div style="color: red" ng-show="section_error"><?php echo lang('lbl_field_required') ?></div>

													</div>
												</div>
												<div class="col-md-2" id="getSubjects_filter">
													<div class="form-group form-material">
														<label class="control-label"><?php echo lang('lbl_subject'); ?></label>
														<select class="form-control "  name="grade-type" ng-model="study.subject" ng-change="subjectChanged_upload()" required="required">
															<option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
															<option ng-repeat="subject in study.subjects" value="{{subject.id}}">{{subject.name}}</option>

														</select>
														<div style="color: red" ng-show="subject_error"><?php echo lang('lbl_field_required') ?></div>
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<label class="control-label"><?php echo lang('lbl_from'); ?></label>
														<input type="text" ng-model="study.from" style="border-left: none; border-right: none; border-top: none;" class="form-control mydatepicker-autoclose"
														></div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<label class="control-label"><?php echo lang('lbl_to'); ?></label>
															<input type="text" ng-model="study.to" style="border-left: none; border-right: none; border-top: none;" class="form-control mydatepicker-autoclose"
															></div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-6">
															<div class="form-group">
																<!-- <label class="control-label">Search by Name or Roll No.</label> -->
																<input type="text" placeholder="<?php echo lang('search_by_name_roll_no'); ?>" class="form-control" style="border-left: none; border-right: none; border-top: none; border-bottom: 1px solid;" ng-model="study.searchKey" />
															</div>
														</div>
														<!-- <div class="col-md-2" style="padding-top: 32px;">
															<div class="form-group">
																<input type="checkbox" name="status" class="form-check-input" id="done_asgn"  ng-model="study.done" >
																<label class="control-label" for="done_asgn" style="padding-left: 18px;">Done</label>
															</div>
														</div> -->
														<div class="col-md-4" style="padding-top: 22px; ">
															<div class="form-group">
																<input type="checkbox"  name="status" class="form-check-input" id="due_asgn"  ng-model="study.due">
																<label class="control-label"  for="due_asgn" style="padding-left: 18px; margin: 0 20px 0 0;"><?php echo lang('du_assignmnet'); ?></label>
															</div>
														</div>
														<div class="col-md-2" style="padding-top: 32px;">
															<button class="btn btn-primary"  style="float: right;"><i class="fa fa-search"></i> <?php echo lang('search'); ?></button>
															<!-- <div class="form-group">
																<input type="checkbox"  name="status" class="form-check-input" id="pending_asgn" ng-model="study.pending">
																<label class="control-label" for="pending_asgn" style="padding-left: 18px;">Pending</label>
															</div> -->
														</div>
														
													</div>
												</form>
											</div>
											<div class="AssignmentsContainer2 hidden">
												<!-- <div class="white-box"> -->
													<div class="row">
														<div class="col-md-12 p-0 mb-2">
															<a href="javascript:void(0);" ng-click="backAllAssignments();" class="btn btn-default"><i class="fa fa-reply " aria-hidden="true"></i> <?php echo lang("btn_back"); ?></a>
														</div>

														<div class="col-md-12 well">

															<div class="col-md-2 text-center">
																<img ng-src="uploads/user/{{Assinged_student_detials.avatar}}" class="thumb-lg img-circle" alt="student-img">
															</div>
													<!-- <div class="col-md-2 text-center">
														<img ng-src="uploads/user/profile.png" ng-if="" class="thumb-lg img-circle" alt="student-img">
													</div> -->

													<div class="col-md-10">
														<div class="col-md-12">
															<div class="row">
																<div class="table-responsive m-t-10  col-md-12">
																	<table class="table-sm col-md-12">
																		<tr>
																			
																			<th><?php echo lang("lbl_name"); ?></th><td>{{Assinged_student_detials.name}}</td>
																			<th><?php echo lang("lbl_rollno"); ?></th><td>{{Assinged_student_detials.rollno}}</td>
																		</tr>
																		<tr>
																			<th><?php echo lang("imp_std_class"); ?></th><td>{{Assinged_student_detials.class_name}}</td>
																			<th><?php echo lang("imp_std_section"); ?></th><td>{{Assinged_student_detials.batch_name}}</td>
																		</tr>
																		<tr>
																			<th><?php echo lang("lbl_subject_msg"); ?></th><td>{{Assinged_student_detials.subject_name}}</td>
																			<th><?php echo lang("lbl_Father_Name_prt"); ?></th><td>{{Assinged_student_detials.guardian_name}}</td>
																		</tr>
																	</table>
																</div>
															</div>
														</div>
													</div>

												</div>

												<br/>

												<div class="col-md-12">
													<div class="table-responsive">
														<table id="myTable" class="table table-striped table-hover table-bordered">
															<thead>
																<tr>
																	<!-- <th>Type</th> -->
																	<th><?php echo lang("assignment_title"); ?></th>
																	<th><?php echo lang("total_marks"); ?></th>
																	<th><?php echo lang("lbl_publish_date"); ?></th>
																	<th><?php echo lang("due_date"); ?></th>
																	<th><?php echo lang("lbl_remarks"); ?></th>
																	<th><?php echo lang("lbl_assignment"); ?></th>
																	<th><?php echo lang("lbl_tbl_action"); ?></th>
																</tr>
															</thead>
															<tbody>
																<tr ng-repeat="ass in show_Assignments">
																	<!-- <td>{{ass.content_type}}</td> -->
																	<td>{{ass.title}}</td>
																	<td>{{ass.total_marks}}</td>
																	<td>{{ass.published_date}}</td>
																	<td>{{ass.due_date}}</td>
																	<td>{{ass.remarks}}</td>
																	<td style="text-align: center;">{{ass.obtained_marks}}<a ng-if="ass.status=='not_submit'" href="javascript:void(0)">
																		<img ng-src="uploads/study_material/icons/not_submit.png" style="width:50px; height:50px; margin-top: -9px;" class="thumb-lg img-circle" alt="student-img"></a>
																		<a ng-if="ass.status=='submitted' && ass.viewed == 'false'" href="javascript:void(0)">
																			<img ng-src="uploads/study_material/icons/icon_mark_pending.png" style="width:50px; height:50px; margin-top: -9px;" class="thumb-lg img-circle" alt="student-img"></a>
																			<a ng-if="ass.status=='late'" href="javascript:void(0)">
																				<img ng-src="uploads/study_material/icons/late.png" style="width:38px; height:38px; margin-top: -9px;" class="thumb-lg img-circle" alt="student-img"></a>
																				<a ng-if="ass.viewed=='true' && ass.status!= 'late'" href="javascript:void(0)">
																					<img ng-src="uploads/study_material/icons/icon_mark.png" style="width:50px; height:50px; margin-top: -9px;" class="thumb-lg img-circle" alt="student-img"></a>
																				</td>
																				<td>
																					<a href="javascript:void(0);" ng-if="ass.student_id != null && ass.obtained_marks == null && ass.remarks == null" data-toggle="modal" data-target="#markAssignment" class="btn btn-primary btn-sm text-white" ng-click="showAssignmentDetail(ass)"><?php echo lang("lbl_mark"); ?></a>
																					<a href="javascript:void(0);" ng-if="ass.obtained_marks != null || ass.remarks != null" data-toggle="modal" data-target="#markAssignmentView" class="btn btn-success btn-sm text-white" ng-click="showAssignmentDetail(ass)"><?php echo lang("btn_view"); ?></a>
																				</td>
																			</tr>

																		</tbody>
																	</table>
																</div>
															</div>
														</div>
													<!-- </div> -->
												</div>
												<div class="row AssignmentsContainer1">
													<div class="col-md-12">			
														<!-- <div class="white-box"> -->
															<div class="table-responsive" ng-if="stduent_submitted_assignment.length > 0">
																<table id="myTable" class="table table-striped table-bordered">
																	<thead>
																		<tr>
																			<th class="text-center"><?php echo lang("imp_sr"); ?></th>
																			<th><?php echo lang("lbl_avatar"); ?></th>
																			<th><?php echo lang("lbl_name"); ?></th>
																			<th><?php echo lang("lbl_rollno"); ?></th>
																			<th><?php echo lang("imp_std_class"); ?></th>
																			<th><?php echo lang("imp_std_section"); ?></th>
																			<th><?php echo lang("lbl_subject_msg"); ?></th>
																			<th><?php echo lang("lbl_status"); ?></th>
																			<th><?php echo lang("th_action"); ?></th>
																		</tr>
																	</thead>
																	<tbody>
																		<tr ng-repeat="std in stduent_submitted_assignment">
																			<td class="text-center">{{$index +1}}</td>
																			<td><img ng-src="uploads/user/{{std.student.avatar}}" style="width:50px; height:50px;" class="thumb-lg img-circle" alt="student-img"></td>
																			<td>{{std.student.name}}</td>
																			<td>{{std.student.rollno}}</td>
																			<td>{{std.student.class_name}}</td>
																			<td>{{std.student.batch_name}}</td>
																			<td>{{std.student.subject_name}}</td>
																			<td>
																				<button ng-if="std.student.allCount==std.student.submit_count" class="btn btn-success btn-xs" style="width: 40px; padding-left: 5px;"><?php echo lang('done') ?></button>
																				<button ng-if="std.student.allCount!=std.student.submit_count" class="btn btn-danger btn-xs"><?php echo lang('lbl_due') ?></button>
																				<p>{{std.student.submit_count}}/{{std.student.allCount}}</p>
																			</td>
																			<td><button class="btn btn-primary btn-xs" ng-click="showAllAssignments(std);"><?php echo lang('btn_view') ?></button></td>
																		</tr>
																	</tbody>
																</table>
															</div>
															<span ng-if="stduent_submitted_assignment.length===0" class="text-danger"><?php echo lang("no_record"); ?></span>
														<!-- </div> -->
													</div>
												</div>  
											</div>
											<!-- assignment tab ends here -->

											<!-- Homework tab -->
											<div class="tab-pane" id="homework">
												<div class="well">
													<form name="filterHomeworks" ng-submit="submittedHomework(filterHomeworks.$valid)" novalidate="">
														<div class="row">
															<!--row -->	 
															<div class="col-md-2">
																<div class="form-group form-material">
																	<label class="control-label"><?php echo lang('lbl_class'); ?></label>
																	<select class="form-control " name="grade-type" ng-model="studyHom.class" ng-change="getSections_homework()" required="required">
																		<option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
																		<option ng-repeat="classi in study.classes" value="{{classi.id}}">{{classi.name}}</option>
																	</select>
																	<div style="color: red" ng-show="class_error"><?php echo lang('lbl_field_required') ?></div>
																</div>
															</div>
															<div class="col-md-2" id="getBatch_filter">
																<div class="form-group form-material">
																	<label class="control-label"><?php echo lang('lbl_batch'); ?></label>

																	<select class="form-control"  name="grade-type" ng-model="studyHom.section" ng-change="getSubjects_homework()" required="required">
																		<option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
																		<!-- <option ng-show="study.batches.length > 1" value="all"><?php echo lang('option_all'); ?></option> -->
																		<option ng-repeat="batch in studyHom.batches" value="{{batch.id}}">{{batch.name}}</option>

																	</select>
																	<div style="color: red" ng-show="section_error"><?php echo lang('lbl_field_required') ?></div>

																</div>
															</div>
															<div class="col-md-2" id="getSubjects_filter">
																<div class="form-group form-material">
																	<label class="control-label"><?php echo lang('lbl_subject'); ?></label>
																	<select class="form-control "  name="grade-type" ng-model="studyHom.subject" ng-change="subjectChanged_upload()" required="required">
																		<option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
																		<option ng-repeat="subject in studyHom.subjects" value="{{subject.id}}">{{subject.name}}</option>

																	</select>
																	<div style="color: red" ng-show="subject_error"><?php echo lang('lbl_field_required') ?></div>
																</div>
															</div>
															<div class="col-md-3">
																<div class="form-group">
																	<label class="control-label"><?php echo lang('lbl_from') ?></label>
																	<input type="text" ng-model="studyHom.from" style="border-left: none; border-right: none; border-top: none;" class="form-control mydatepicker-autoclose"
																	></div>
																</div>
																<div class="col-md-3">
																	<div class="form-group">
																		<label class="control-label"><?php echo lang('lbl_to') ?></label>
																		<input type="text" ng-model="studyHom.to" style="border-left: none; border-right: none; border-top: none;" class="form-control mydatepicker-autoclose"
																		></div>
																	</div>
																</div>
																<div class="row">
																	<div class="col-md-6">
																		<div class="form-group">
																			<!-- <label class="control-label">Search by Name or Roll No.</label> -->
																			<input type="text" placeholder="<?php echo lang('search_by_name_roll_no') ?>" class="form-control" style="border-left: none; border-right: none; border-top: none; border-bottom: 1px solid;" ng-model="studyHom.searchKey" />
																		</div>
																	</div>
																<!-- <div class="col-md-2" style="padding-top: 32px;">
																	<div class="form-group">
																		<input type="checkbox" name="status" class="form-check-input" id="done_asgn"  ng-model="study.done" >
																		<label class="control-label" for="done_asgn" style="padding-left: 18px;">Done</label>
																	</div>
																</div> -->
																<div class="col-md-4" style="padding-top: 22px;">
																	<div class="form-group">
																		<input type="checkbox"  name="status" class="form-check-input" id="due_asgn"  ng-model="studyHom.due">
																		<label class="control-label"  for="due_asgn" style="padding-left: 18px; margin: 0 20px 0 0;"><?php echo lang('du_homework') ?></label>
																	</div>
																</div>
																<div class="col-md-2" style="padding-top: 32px;">
																	<button class="btn btn-primary"  style="float: right;"><i class="fa fa-search"></i> <?php echo lang('search') ?></button>
																	<!-- <div class="form-group">
																		<input type="checkbox"  name="status" class="form-check-input" id="pending_asgn" ng-model="study.pending">
																		<label class="control-label" for="pending_asgn" style="padding-left: 18px;">Pending</label>
																	</div> -->
																</div>	
															</div>
														</form>
													</div>
													<div class="HomeworkContainer2 hidden">
														<div class="white-box">
															<div class="row">
																<div class="col-md-12 p-0 mb-2">
																	<a href="javascript:void(0);" ng-click="backAllHomework();" class="btn btn-default"><i class="fa fa-reply " aria-hidden="true"></i> <?php echo lang("btn_back"); ?></a>
																</div>

																<div class="col-md-12 well">

																	<div class="col-md-2 text-center">
																		<img ng-src="uploads/user/{{Assinged_student_detials1.avatar}}" class="thumb-lg img-circle" alt="student-img">
																	</div>

																	<div class="col-md-10">
																		<div class="col-md-12">
																			<div class="row">
																				<div class="table-responsive m-t-10  col-md-12">
																					<table class="table-sm col-md-12">
																						<tr>

																							<th><?php echo lang("lbl_name"); ?></th><td>{{Assinged_student_detials1.name}}</td>
																							<th><?php echo lang("lbl_rollno"); ?></th><td>{{Assinged_student_detials1.rollno}}</td>
																						</tr>
																						<tr>
																							<th><?php echo lang("imp_std_class"); ?></th><td>{{Assinged_student_detials1.class_name}}</td>
																							<th><?php echo lang("imp_std_section"); ?></th><td>{{Assinged_student_detials1.batch_name}}</td>
																						</tr>
																						<tr>
																							<th><?php echo lang("lbl_subject_msg"); ?></th><td>{{Assinged_student_detials1.subject_name}}</td>
																							<th><?php echo lang("lbl_Father_Name_prt"); ?></th><td>{{Assinged_student_detials1.guardian_name}}</td>
																						</tr>
																					</table>
																				</div>
																			</div>
																		</div>
																	</div>

																</div>

																<br/>

																<div class="col-md-12 p-0">
																	<div class="table-responsive">
																		<table id="myTable" class="table table-striped table-hover table-bordered">
																			<thead>
																				<tr>
																					<!-- <th>Type</th> -->
																					<th><?php echo lang("homeWork_title"); ?></th>
																					<th><?php echo lang("total_marks"); ?></th>
																					<th><?php echo lang("lbl_publish_date"); ?></th>
																					<th><?php echo lang("due_date"); ?></th>
																					<th><?php echo lang("lbl_remarks"); ?></th>
																					<th><?php echo lang("homework_list"); ?></th>
																					<th><?php echo lang("th_action"); ?></th>
																				</tr>
																			</thead>
																			<tbody>
																				<tr ng-repeat="hom in show_Homeworks">
																					<!-- <td>{{ass.content_type}}</td> -->
																					<td>{{hom.title}}</td>
																					<td>{{hom.total_marks}}</td>
																					<td>{{hom.published_date}}</td>
																					<td>{{hom.due_date}}</td>
																					<td>{{hom.remarks}}</td>
																					<td style="text-align: center;">{{hom.obtained_marks}}<a ng-if="hom.status=='not_submit'" href="javascript:void(0)">
																						<img ng-src="uploads/study_material/icons/not_submit.png" style="width:50px; height:50px; margin-top: -9px;" class="thumb-lg img-circle" alt="student-img"></a>
																						<a ng-if="hom.status=='submitted' && hom.viewed == 'false'" href="javascript:void(0)">
																							<img ng-src="uploads/study_material/icons/icon_mark_pending.png" style="width:50px; height:50px; margin-top: -9px;" class="thumb-lg img-circle" alt="student-img"></a>
																							<a ng-if="hom.status=='late'" href="javascript:void(0)">
																								<img ng-src="uploads/study_material/icons/late.png" style="width:38px; height:38px; margin-top: -9px;" class="thumb-lg img-circle" alt="student-img"></a>
																								<a ng-if="hom.viewed=='true' && hom.status!= 'late'" href="javascript:void(0)">
																									<img ng-src="uploads/study_material/icons/icon_mark.png" style="width:50px; height:50px; margin-top: -9px;" class="thumb-lg img-circle" alt="student-img"></a>
																								</td>
																								<td>
																									<a href="javascript:void(0);" ng-if="hom.student_id != null && hom.obtained_marks == null && hom.remarks == null" data-toggle="modal" data-target="#markHomework" class="btn btn-primary btn-sm text-white" ng-click="showHomeworkDetail(hom)"><?php echo lang("lbl_mark"); ?></a>
																									<a href="javascript:void(0);" ng-if="hom.obtained_marks != null || hom.remarks != null" data-toggle="modal" data-target="#markHomeworkView" class="btn btn-success btn-sm text-white" ng-click="showHomeworkDetail(hom)"><?php echo lang("btn_view"); ?></a>
																								</td>
																							</tr>
																						</tbody>
																					</table>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="row HomeworkContainer1">
																	<div class="col-md-12">			
																		<div class="white-box">
																			<div class="table-responsive">
																				<table id="myTable" class="table table-striped table-bordered" ng-if="stduent_submitted_homework.length != 0">
																					<thead>
																						<tr>
																							<th class="text-center"><?php echo lang("imp_sr"); ?></th>
																							<th><?php echo lang("lbl_avatar"); ?></th>
																							<th><?php echo lang("lbl_name"); ?></th>
																							<th><?php echo lang("lbl_rollno"); ?></th>
																							<th><?php echo lang("imp_std_class"); ?></th>
																							<th><?php echo lang("imp_std_section"); ?></th>
																							<th><?php echo lang("lbl_subject_msg"); ?></th>
																							<th><?php echo lang("lbl_status"); ?></th>
																							<th><?php echo lang("th_action"); ?></th>
																						</tr>
																					</thead>
																					<tbody>
																						<tr ng-repeat="std_hom in stduent_submitted_homework">
																							<td class="text-center">{{$index +1}}</td>
																							<td><img ng-src="uploads/user/{{std_hom.student.avatar}}" style="width:50px; height:50px;" class="thumb-lg img-circle" alt="student-img"></td>
																							<td>{{std_hom.student.name}}</td>
																							<td>{{std_hom.student.rollno}}</td>
																							<td>{{std_hom.student.class_name}}</td>
																							<td>{{std_hom.student.batch_name}}</td>
																							<td>{{std_hom.student.subject_name}}</td>
																							<td>
																								<button ng-if="std_hom.student.allCount==std_hom.student.submit_count" class="btn btn-success btn-xs" style="width: 40px; padding-left: 5px;"><?php echo lang('done') ?></button>
																								<button ng-if="std_hom.student.allCount!=std_hom.student.submit_count" class="btn btn-danger btn-xs"><?php echo lang('lbl_due') ?></button>
																								<p>{{std_hom.student.submit_count}}/{{std_hom.student.allCount}}</p>
																							</td>
																							<td><button class="btn btn-primary btn-xs" ng-click="showAllHomework(std_hom);"><?php echo lang('btn_view') ?></button></td>
																						</tr>
																					</tbody>
																				</table>
																			</div>
																			<span ng-if="stduent_submitted_homework.length===0" class="text-danger"><?php echo lang("no_record"); ?></span>
																		</div>
																	</div>
																</div>
															</div>  
														</div>
													</div>
												</div>
												<!-- tabs end here -->
											</div>
										</div>
									</div>
									<!-- assignment model -->
							<div class="modal fade" id="markAssignment" tabindex="-1" role="dialog"
							aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="panel panel-primary">
										<div class="panel-heading"><?php echo lang("lpl_assignment_title"); ?></div>
										<form ng-submit="SaveAssignmentMark()">
											<div class="panel-body">
												<div class="row">
													<div class="col-md-12">
														<div class="card mb-0 bg-light col-md-12" style="background-color: white !important; margin-top: 10px; border: 1px solid #e3e3e3; box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);">
															<div class="card-body">
																<div class="row">
																	<div class="col-md-8">

																		<div class="row">
																			<div class="col-md-6" style="padding-top: 10px;">
																				<img src="<?php echo base_url(); ?>uploads/user/{{Assignment_details.teacher_avatar}}" alt="user-img" style="width: 40px;" class="img-circle"> <b>{{Assignment_details.teacher_name}}</b>
																			</div>


																			<div class="col-md-6" style="padding-top: 10px;">
																				<b></b>{{Assignment_details.published_date  | date:'medium'}}
																			</div>

		                                        						<!-- <div class="col-md-4" style="padding-top: 10px;">
		                                        							<b>Subject : </b>{{Assinged_student_detials.subject_name}}
		                                        						</div> -->
		                                        					</div>
		                                        					<div class="row">
		                                        						<p class="card-text" ng-bind-html="Assignment_details.details" style="padding-left: 22px; padding-bottom: 4px; padding-top: 4px"></p>
		                                        					</div>
		                                        					
		                                        					<div class="row">
		                                        						<div style="padding-bottom: 10px;">
		                                        							<b style="padding-left: 4px;"><?php echo lang("lbl_attachments"); ?> :</b>
		                                        						</div>
		                                        					</div>
		                                        					<div class="row" style="overflow-wrap: anywhere;">
		                                        					    
		                                        					    
		                                        						<!--<ul>-->
		                                        						<!--	<div ng-repeat="file in Assignment_details.files">-->
		                                        						<!--		<li ng-if="['jpg','jpeg','png','gif','PNG'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" data-lightbox="image-attach">-->
		                                        						<!--				<img src="<?php echo base_url(); ?>uploads/study_material/{{file}}" style="height: 33px; width: 30px;">-->
		                                        						<!--			</a>-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" data-lightbox="image-attach">{{file}}</a>-->
		                                        						<!--		</li>-->
		                                        						<!--		<li ng-if="['pdf'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">-->
		                                        						<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/pdf_icon.png" style="height: 33px; width: 30px;">-->
		                                        						<!--			</a>-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>-->
		                                        						<!--		</li>-->
		                                        						<!--		<li ng-if="['doc','docx'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">-->
		                                        						<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/doc_icon.png" style="height: 33px; width: 30px;">-->
		                                        						<!--			</a>-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>-->
		                                        						<!--		</li>-->
		                                        						<!--		<li ng-if="['xlsx'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">-->
		                                        						<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/excel_icon.jpg" style="height: 33px; width: 30px;">-->
		                                        						<!--			</a>-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>-->
		                                        						<!--		</li>-->
		                                        						<!--		<li ng-if="['ppt','pptx'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">-->
		                                        						<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/ppt_icon.png" style="height: 33px; width: 30px;">-->
		                                        						<!--			</a>-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>-->
		                                        						<!--		</li>-->
		                                        						<!--	</div>                                         					-->
		                                        						<!--</ul>   -->
		                                        						
		                                        						<ul>
		                                        						    <div ng-if="Assignment_details.storage_type == 1">
    		                                        							<div ng-repeat="file in Assignment_details.files">
    		                                        								<li ng-if="['jpg','JPG','jpeg','JPEG','png','PNG','gif','GIF','tif','TIF','tiff','TIFF','bmp','BMP'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" data-lightbox="image-attach">
    		                                        										<img src="<?php echo base_url(); ?>uploads/study_material/{{file}}" style="height: 33px; width: 30px;">
    		                                        									</a>
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" data-lightbox="image-attach">{{file}}</a>
    		                                        								</li>
    		                                        								<li ng-if="['pdf','PDF'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">
    		                                        										<img src="<?php echo base_url(); ?>uploads/study_material/icons/pdf_icon.png" style="height: 33px; width: 30px;">
    		                                        									</a>
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>
    		                                        								</li>
    		                                        								<li ng-if="['doc','DOC','docx','DOCX'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">
    		                                        										<img src="<?php echo base_url(); ?>uploads/study_material/icons/doc_icon.png" style="height: 33px; width: 30px;">
    		                                        									</a>
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>
    		                                        								</li>
    		                                        								<li ng-if="['xlsm','XLSM','xlsx','XLSX','xlt','XLT','xls','XLS'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">
    		                                        										<img src="<?php echo base_url(); ?>uploads/study_material/icons/excel_icon.jpg" style="height: 33px; width: 30px;">
    		                                        									</a>
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>
    		                                        								</li>
    		                                        								<li ng-if="['ppt','PPT','pptx','PPTX'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">
    		                                        										<img src="<?php echo base_url(); ?>uploads/study_material/icons/ppt_icon.png" style="height: 33px; width: 30px;">
    		                                        									</a>
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>
    		                                        								</li>
    		                                        							</div> 
    		                                        						</div>
		                                        						</ul> 
		                                        						
		                                        						<!-- google drive -->
                                                                        <div ng-if="Assignment_details.storage_type == 2" style="overflow-x: auto;">
                                                                            <div style="width:100%; overflow-x: auto;">
                                                                                  <table>
                                                                                  <tr style="overflow-x: auto;" class="test">
                                                                                      
                                                                                  <!--<td ng-repeat="thumbx_link in details.thumbnail_links" style="padding: 5px">-->
                                                                                    <td ng-repeat="(key,f) in Assignment_details.files" style="width: 149px;">
                                                                                        
                                                                                        <div ng-if="Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'mp4' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'MP4' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'avi' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'AVI' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'flv' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'FLV' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'wmv' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'WMV' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'mov' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'MOV' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'webm'  || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'WEBM' ">
                                                                                            <img class="client-img img-thumbnail" src="assets/googledrive_guide/images/videoicon.png" style="height:150px; width:320px; border-radius:8px;" />
                                                                                        </div> 
                                                                                        
                                                                                        <div ng-if="Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'pdf' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'PDF' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'doc' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'DOC' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'docx' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'DOCX' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'ppt' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'PPT' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'pptx' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'PPTX' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'png' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'PNG' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'tif' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'TIF' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'tiff' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'TIFF' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'bmp' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'BMP' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'jpg' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'JPG' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'jpeg' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'JPEG' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'gif' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'GIF' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'eps'  || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'EPS' ">
                                                                                            <img class="client-img img-thumbnail" ng-src="{{Assignment_details.thumbnail_links[key]}}" style="height:150px; width:320px; border-radius:8px;" />
                                                                                        </div>
                                                                                            <div style="text-align: center !important; width: 141px; overflow: hidden; text-overflow: ellipsis;">
                                                                                                <a  href="{{Assignment_details.filesurl[key]}}" target="_blank">{{Assignment_details.file_names[key]}}</a> 
                                                                                                <!--<button class="btn btn-link text-danger" ng-click="removeFile(f.name)" type="button">Remove</button>-->
                                                                                            </div>
                                                                                        
                                                                                    </td>
                                                                                  </tr>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                        <!-- google drive -->
		                                        						
		                                        					</div>
		                                        					<div class="row">
		                                        						<b style="padding-left: 4px;"><?php echo lang("lbl_details"); ?> :</b>
		                                        					</div>
		                                        					<p class="card-text" style="margin-top: -5px; padding-left: 15px;">{{Assignment_details.material_details}}</p>
		                                        					<hr>
		                                        					<div class="col-md-12" style="padding-top: 10px;">
		                                        						<img src="<?php echo base_url(); ?>uploads/user/{{Assignment_details.student_avatar}}" alt="user-img" style="width: 40px;" class="img-circle"> <b>{{Assignment_details.student_name}}</b>
		                                        						<div class="row" style="padding-top: 8px;">
		                                        							<div style="padding-bottom: 10px;">
		                                        								<b style="padding-left: 4px;"><?php echo lang("lbl_attachments"); ?> :</b>
		                                        							</div>
		                                        						</div>
		                                        						<div class="row" style="overflow-wrap: anywhere;">
		                                        							<!--<ul>-->
		                                        							<!--	<div  ng-repeat="std_file in Assignment_details.submitted_files">-->
		                                        							<!--		<li ng-if="['jpg','jpeg','png','gif','PNG'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" data-lightbox="image-attach">-->
		                                        							<!--				<img src="<?php echo base_url(); ?>uploads/study_material/{{std_file}}" style="height: 33px; width: 30px;">-->
		                                        							<!--			</a>-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" data-lightbox="image-attach">{{std_file}}</a>-->
		                                        							<!--		</li>-->
		                                        							<!--		<li ng-if="['pdf'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">-->
		                                        							<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/pdf_icon.png" style="height: 33px; width: 30px;">-->
		                                        							<!--			</a>-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>-->
		                                        							<!--		</li>-->
		                                        							<!--		<li ng-if="['doc','docx'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">-->
		                                        							<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/doc_icon.png" style="height: 33px; width: 30px;">-->
		                                        							<!--			</a>-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>-->
		                                        							<!--		</li>-->
		                                        							<!--		<li ng-if="['xlsx'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">-->
		                                        							<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/excel_icon.jpg" style="height: 33px; width: 30px;">-->
		                                        							<!--			</a>-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>-->
		                                        							<!--		</li>-->
		                                        							<!--		<li ng-if="['ppt','pptx'].includes(std_file.split('.').pop().toLowerCase())" style="height: 33px; width: 30px;">-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">-->
		                                        							<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/ppt_icon.png" style="height: 33px; width: 30px;">-->
		                                        							<!--			</a>-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>-->
		                                        							<!--		</li>-->
		                                        							<!--	</div>                                         					-->
		                                        							<!--</ul>   -->
		                                        							
		                                        							<ul>
		                                        							    <div ng-if="Assignment_details.storage_type == 1">
    		                                        								<div  ng-repeat="std_file in Assignment_details.submitted_files">
    		                                        									<li ng-if="['jpg','JPG','jpeg','JPEG','png','PNG','gif','GIF','tif','TIF','tiff','TIFF','bmp','BMP'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" data-lightbox="image-attach">
    		                                        											<img src="<?php echo base_url(); ?>uploads/study_material/{{std_file}}" style="height: 33px; width: 30px;">
    		                                        										</a>
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" data-lightbox="image-attach">{{std_file}}</a>
    		                                        									</li>
    		                                        									<li ng-if="['pdf','PDF'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">
    		                                        											<img src="<?php echo base_url(); ?>uploads/study_material/icons/pdf_icon.png" style="height: 33px; width: 30px;">
    		                                        										</a>
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>
    		                                        									</li>
    		                                        									<li ng-if="['doc','DOC','docx','DOCX'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">
    		                                        											<img src="<?php echo base_url(); ?>uploads/study_material/icons/doc_icon.png" style="height: 33px; width: 30px;">
    		                                        										</a>
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>
    		                                        									</li>
    		                                        									<li ng-if="['xlsm','XLSM','xlsx','XLSX','xlt','XLT','xls','XLS'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">
    		                                        											<img src="<?php echo base_url(); ?>uploads/study_material/icons/excel_icon.jpg" style="height: 33px; width: 30px;">
    		                                        										</a>
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>
    		                                        									</li>
    		                                        									<li ng-if="['ppt','PPT','pptx','PPTX'].includes(std_file.split('.').pop().toLowerCase())" style="height: 33px; width: 30px;">
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">
    		                                        											<img src="<?php echo base_url(); ?>uploads/study_material/icons/ppt_icon.png" style="height: 33px; width: 30px;">
    		                                        										</a>
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>
    		                                        									</li>
    		                                        								</div>  
    		                                        							</div>
		                                        							</ul>
		                                        							
		                                        							<!-- google drive -->
                                                                            <div ng-if="Assignment_details.storage_type == 2" style="overflow-x: auto;">
                                                                                <div style="width:100%; overflow-x: auto;">
                                                                                      <table>
                                                                                      <tr style="overflow-x: auto;" class="test">
                                                                                          
                                                                                      <!--<td ng-repeat="thumbx_link in details.thumbnail_links" style="padding: 5px">-->
                                                                                        <td ng-repeat="(key,f) in Assignment_details.submitted_files" style="width: 149px;">
                                                                                            
                                                                                            <div ng-if="Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'mp4' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'MP4' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'avi' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'AVI' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'flv' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'FLV' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'wmv' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'WMV' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'mov' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'MOV' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'webm'  || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'WEBM' ">
                                                                                                <img class="client-img img-thumbnail" src="assets/googledrive_guide/images/videoicon.png" style="height:150px; width:320px; border-radius:8px;" />
                                                                                            </div> 
                                                                                            
                                                                                            <div ng-if="Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'pdf' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'PDF' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'doc' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'DOC' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'docx' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'DOCX' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'ppt' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'PPT' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'pptx' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'PPTX' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'png' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'PNG' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'tif' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'TIF' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'tiff' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'TIFF' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'bmp' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'BMP' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'jpg' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'JPG' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'jpeg' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'JPEG' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'gif' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'GIF' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'eps'  || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'EPS' ">
                                                                                                <img class="client-img img-thumbnail" ng-src="{{Assignment_details.submitted_thumbnail_links[key]}}" style="height:150px; width:320px; border-radius:8px;" />
                                                                                            </div>
                                                                                                <div style="text-align: center !important; width: 141px; overflow: hidden; text-overflow: ellipsis;">
                                                                                                    <a  href="{{Assignment_details.submitted_filesurl[key]}}" target="_blank">{{Assignment_details.submitted_file_names[key]}}</a> 
                                                                                                    <!--<button class="btn btn-link text-danger" ng-click="removeFile(f.name)" type="button">Remove</button>-->
                                                                                                </div>
                                                                                            
                                                                                        </td>
                                                                                      </tr>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                            <!-- google drive -->
		                                        							
		                                        						</div>
		                                        						<div class="row">
		                                        							<b style="padding-left: 4px;"><?php echo lang("description"); ?> :</b>
		                                        						</div>
		                                        						<p class="card-text" style="padding-left: 18px;" ng-bind-html="Assignment_details.submitted_details"></p>
		                                        					</div>
		                                        				</div>
		                                        				<!-- right content area -->
		                                        				<div class="col-md-4" style="border-left: 1px solid;">
		                                        					<div class="row">
		                                        						<div class="col-md-12" style="padding-top: 10px;">
		                                        							<b><?php echo lang("lpl_assignment_type"); ?> : </b>{{Assignment_details.content_type}}
		                                        						</div>
		                                        					</div>
		                                        					<div class="row">
		                                        						<div class="col-md-12" style="padding-top: 10px;">
		                                        							<b><?php echo lang("assignment_title"); ?> : </b>{{Assignment_details.title}}
		                                        						</div>
		                                        					</div>
		                                        					
		                                        					<div class="row">
		                                        						<div class="col-md-12" style="padding-top: 10px;">
		                                        							<b><?php echo lang("total_marks"); ?> : </b>{{Assignment_details.total_marks}}
		                                        						</div>
		                                        						<div class="col-md-12" style="padding-top: 10px;">
		                                        							<b><?php echo lang("due_date"); ?> : </b>{{Assignment_details.due_date}}
		                                        						</div>
		                                        					</div>
		                                        					<br>
		                                        					<div class="row" ng-if="Assignment_details.total_marks != '0'">
		                                        						<div class="col-md-12">
		                                        							<div class="form-group">
		                                        								<label class="control-label"><?php echo lang("obtained_marks"); ?></label>
		                                        								<input type="number" class="form-control" ng-model="Assignment_details.obtained_marks" min="0" max="{{Assignment_details.total_marks}}" required>
		                                        							</div>
		                                        						</div>

		                                        					</div>

		                                        					<div class="row">
		                                        						<div class="col-md-12">
		                                        							<div class="form-group">
		                                        								<textarea class="form-control" ng-model="Assignment_details.remarks" rows="5" placeholder="Remarks" required></textarea>
		                                        							</div>
		                                        						</div>
		                                        					</div>
		                                        					<div class="pull-right">
		                                        						<button type="submit" class="btn btn-primary">
		                                        							<?php echo lang("submit"); ?>
		                                        						</button>
		                                        					</div>
		                                        				</div>
		                                        			</div>
		                                        		</div>
		                                        	</div>
		                                        </div>
		                                    </div><br>

		                                    <div class="container">
		                                    	<h3 style="text-align: center; font-size: 21px; font-family: none; text-decoration: underline;">Comments</h3>
		                                    	<div class="row">
		                                    		<div class="col-md-12">
		                                    			<div class="card mb-0 bg-light col-md-12" style="background-color: white !important; border: 1px solid #e3e3e3; box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);">
		                                    				<div class="col-md-12">
		                                    					<div class="container" ng-repeat="coment in comments">
		                                    						<div class="container black_comments1" ng-if="coment.user_id!=coment.sender_id">
		                                    							<div class="row">
		                                    								<div class="col-md-9" style="padding-top: 10px;">
		                                    									<img src="<?php echo base_url(); ?>uploads/user/{{coment.avatar}}" alt="user-img" style="width: 40px;" class="img-circle"> <b>{{coment.name}}</b>
		                                    								</div>

		                                    							</div>

		                                    							<div class="row" style="padding-top: 8px;" ng-show="coment.files[0] != '' ">
		                                    								<div style="padding-bottom: 10px;">
		                                    									<b style="padding-left: 4px;"><?php echo lang("lbl_attachments"); ?> :</b>
		                                    								</div>
		                                    							</div>

		                                    							<div class="row" style="overflow-wrap: anywhere;">
		                                    								<ul>
		                                    									<div  ng-repeat="cmnt_file in coment.files">
		                                    										<li ng-if="['jpg','jpeg','png','gif','PNG'].includes(cmnt_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" data-lightbox="image-attach">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/{{cmnt_file}}" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" data-lightbox="image-attach">{{cmnt_file}}</a>
		                                    										</li>
		                                    										<li ng-if="['pdf'].includes(cmnt_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/pdf_icon.png" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">{{cmnt_file}}</a>
		                                    										</li>
		                                    										<li ng-if="['doc','docx'].includes(cmnt_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/doc_icon.png" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">{{cmnt_file}}</a>
		                                    										</li>
		                                    										<li ng-if="['xlsx'].includes(cmnt_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/excel_icon.jpg" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">{{cmnt_file}}</a>
		                                    										</li>
		                                    										<li ng-if="['ppt','pptx'].includes(cmnt_file.split('.').pop().toLowerCase())" style="height: 33px; width: 30px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/ppt_icon.png" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">{{cmnt_file}}</a>
		                                    										</li>
		                                    									</div>                                         					
		                                    								</ul>   
		                                    							</div>
		                                    							<div class="row">
		                                    								<b style="padding-left: 4px;"><?php echo lang("crumb_messages"); ?> :</b>
		                                    							</div>
		                                    							<p class="card-text" ng-bind-html="coment.comment_body" style="padding-left: 18px;"></p>

		                                    						</div>
		                                    						<div class="container black_comments" ng-if="coment.user_id==coment.sender_id">
		                                    							<div class="row">
		                                    								<div class="col-md-9" style="padding-top: 10px;">
		                                    									<img src="<?php echo base_url(); ?>uploads/user/{{coment.avatar}}" alt="user-img" style="width: 40px;" class="img-circle"> <b>{{coment.name}}</b>
		                                    								</div>

		                                    							</div>

		                                    							<div class="row" style="padding-top: 8px;" ng-show="coment.files[0] != '' ">
		                                    								<div style="padding-bottom: 10px;">
		                                    									<b style="padding-left: 4px;"><?php echo lang("lbl_attachments"); ?> :</b>
		                                    								</div>
		                                    							</div>

		                                    							<div class="row" style="overflow-wrap: anywhere;">
		                                    								<ul>
		                                    									<div  ng-repeat="cmnt_file in coment.files">
		                                    										<li ng-if="['jpg','jpeg','png','gif','PNG'].includes(cmnt_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" data-lightbox="image-attach">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/{{cmnt_file}}" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" data-lightbox="image-attach">{{cmnt_file}}</a>
		                                    										</li>
		                                    										<li ng-if="['pdf'].includes(cmnt_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/pdf_icon.png" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">{{cmnt_file}}</a>
		                                    										</li>
		                                    										<li ng-if="['doc','docx'].includes(cmnt_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/doc_icon.png" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">{{cmnt_file}}</a>
		                                    										</li>
		                                    										<li ng-if="['xlsx'].includes(cmnt_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/excel_icon.jpg" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">{{cmnt_file}}</a>
		                                    										</li>
		                                    										<li ng-if="['ppt','pptx'].includes(cmnt_file.split('.').pop().toLowerCase())" style="height: 33px; width: 30px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/ppt_icon.png" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">{{cmnt_file}}</a>
		                                    										</li>
		                                    									</div>                                         					
		                                    								</ul>   
		                                    							</div>
		                                    							<div class="row">
		                                    								<b style="padding-left: 4px;"><?php echo lang("crumb_messages"); ?> :</b>
		                                    							</div>
		                                    							<p class="card-text" ng-bind-html="coment.comment_body" style="padding-left: 18px;"></p>

		                                    						</div>
		                                    						<hr>
		                                    					</div>
		                                    					<!-- Comments assignmnet div start here -->
		                                    					<div class="container">
		                                    						<div ng-controller="commentsController" id="CommentsDIV" style="display: none; margin-bottom: 5px; margin-top: 14px;">
		                                    							<div class="row">
		                                    								<div class="col-md-12">
		                                    									<div class="form-group">
		                                    										<label class="control-label"><?php echo lang("crumb_messages"); ?></label>
		                                    										<textarea class="textarea_editor form-control" rows="5" placeholder="" ></textarea>
		                                    									</div>
		                                    								</div>
		                                    							</div>
		                                    							<div class="row">
		                                    								<div class="col-md-12">
		                                    									<div class="form-group">
		                                    										<label class="control-label"><?php echo lang('upload_content') ?></label>
		                                    										<div class="dropzone" id="my-awesome-dropzone_message" dropzone="DropzoneConfigMessage"></div>
		                                    									</div>
		                                    								</div>
		                                    							</div>
		                                    							<div class="alert" id="message_alert" style="display: none"></div>
		                                    							<div class="text-center">
		                                    								<button type="button" name="{{Assignment_details.submit_id}}" class="btn btn-primary" id="send_message"><i class="fa fa-comments"></i>
		                                    									<?php echo lang("btn_send"); ?>
		                                    								</button>
		                                    							</div><br>
		                                    						</div>	
		                                    					</div>
		                                    					<!-- Comments assignmnet div Ends here -->
		                                    				</div>		                                    	
		                                    			</div>
		                                    		</div>
		                                    	</div>
		                                    </div>

		                                    <br><br>
		                                    <div class="row pull-right" style="margin-top: 12px;">
		                                    	<!-- Comments div hide show button -->
		                                    	<button type="button"  onclick="showComments()" class="btn btn-primary" style="margin-right: 4px; width: 55px;">
		                                    		<i class="fa fa-comments-o"></i>
		                                    	</button>
		                                    	<div style="margin-right: 8px">
		                                    		<button type="button" ng-click="reset_assignmentMarkValue()" class="btn btn-default"
		                                    		data-dismiss="modal"><?php echo lang('lbl_close') ?>
		                                    		</button>
		                                    	</div>
		                                	</div>
		                            	</div>				
		                        	</form>
		                        	</div>
		                    	</div>
		                	</div>
		            		</div>
		            <!-- assignment model -->
		            <!-- assignment view model -->
		            <div class="modal fade" id="markAssignmentView" tabindex="-1" role="dialog"
		            aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		            <div class="modal-dialog modal-lg" role="document">
		            	<div class="modal-content">
		            		<div class="panel panel-primary">
		            			<div class="panel-heading"><?php echo lang('lpl_assignment_title') ?></div>
		            			<div class="panel-body">
		            				<div class="row">
		            					<div class="col-md-12">
		            						<div class="card mb-0 bg-light col-md-12" style="background-color: white !important; margin-top: 10px; border: 1px solid #e3e3e3; box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);">
		            							<div class="card-body">
		            								<div class="row">
		            									<div class="col-md-8">
		            										<div class="row">
		            											<div class="col-md-6" style="padding-top: 10px;">
		            												<img src="<?php echo base_url(); ?>uploads/user/{{Assignment_details.teacher_avatar}}" alt="user-img" style="width: 40px;" class="img-circle"> <b>{{Assignment_details.teacher_name}}</b>
		            											</div>


		            											<div class="col-md-6" style="padding-top: 10px;">
		            												<b></b>{{Assignment_details.published_date  | date:'medium'}}
		            											</div>

		                                        						<!-- <div class="col-md-4" style="padding-top: 10px;">
		                                        							<b>Subject : </b>{{Assinged_student_detials.subject_name}}
		                                        						</div> -->
		                                        					</div>
		                                        					<div class="row">
		                                        						<p class="card-text" style="padding-top: 4px; padding-left: 22px;" ng-bind-html="Assignment_details.details"></p>
		                                        					</div>
		                                        					
		                                        					<div class="row">
		                                        						<div style="padding-bottom: 10px;">
		                                        							<b style="padding-left: 4px;"><?php echo lang('lbl_attachments') ?> :</b>
		                                        						</div>
		                                        					</div>
		                                        					<div class="row" style="overflow-wrap: anywhere;">
		                                        						<!--<ul>-->
		                                        						<!--	<div  ng-repeat="file in Assignment_details.files">-->
		                                        						<!--		<li ng-if="['jpg','jpeg','png','gif','PNG'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" data-lightbox="image-attach">-->
		                                        						<!--				<img src="<?php echo base_url(); ?>uploads/study_material/{{file}}" style="height: 33px; width: 30px;">-->
		                                        						<!--			</a>-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" data-lightbox="image-attach">{{file}}</a>-->
		                                        						<!--		</li>-->
		                                        						<!--		<li ng-if="['pdf'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">-->
		                                        						<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/pdf_icon.png" style="height: 33px; width: 30px;">-->
		                                        						<!--			</a>-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>-->
		                                        						<!--		</li>-->
		                                        						<!--		<li ng-if="['doc','docx'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">-->
		                                        						<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/doc_icon.png" style="height: 33px; width: 30px;">-->
		                                        						<!--			</a>-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>-->
		                                        						<!--		</li>-->
		                                        						<!--		<li ng-if="['xlsx'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">-->
		                                        						<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/excel_icon.jpg" style="height: 33px; width: 30px;">-->
		                                        						<!--			</a>-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>-->
		                                        						<!--		</li>-->
		                                        						<!--		<li ng-if="['ppt','pptx'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">-->
		                                        						<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/ppt_icon.png" style="height: 33px; width: 30px;">-->
		                                        						<!--			</a>-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>-->
		                                        						<!--		</li>-->
		                                        						<!--	</div>                                         					-->
		                                        						<!--</ul>   -->
		                                        						
		                                        						<ul>
		                                        						    <div ng-if="Assignment_details.storage_type == 1">
    		                                        							<div  ng-repeat="file in Assignment_details.files">
    		                                        								<li ng-if="['jpg','jpeg','png','gif','PNG'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" data-lightbox="image-attach">
    		                                        										<img src="<?php echo base_url(); ?>uploads/study_material/{{file}}" style="height: 33px; width: 30px;">
    		                                        									</a>
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" data-lightbox="image-attach">{{file}}</a>
    		                                        								</li>
    		                                        								<li ng-if="['pdf'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">
    		                                        										<img src="<?php echo base_url(); ?>uploads/study_material/icons/pdf_icon.png" style="height: 33px; width: 30px;">
    		                                        									</a>
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>
    		                                        								</li>
    		                                        								<li ng-if="['doc','docx'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">
    		                                        										<img src="<?php echo base_url(); ?>uploads/study_material/icons/doc_icon.png" style="height: 33px; width: 30px;">
    		                                        									</a>
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>
    		                                        								</li>
    		                                        								<li ng-if="['xlsx'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">
    		                                        										<img src="<?php echo base_url(); ?>uploads/study_material/icons/excel_icon.jpg" style="height: 33px; width: 30px;">
    		                                        									</a>
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>
    		                                        								</li>
    		                                        								<li ng-if="['ppt','pptx'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">
    		                                        										<img src="<?php echo base_url(); ?>uploads/study_material/icons/ppt_icon.png" style="height: 33px; width: 30px;">
    		                                        									</a>
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>
    		                                        								</li>
    		                                        							</div>     
    		                                        						</div>
		                                        						</ul> 
		                                        						
		                                        						<!-- google drive -->
                                                                        <div ng-if="Assignment_details.storage_type == 2" style="overflow-x: auto;">
                                                                            <div style="width:100%; overflow-x: auto;">
                                                                                  <table>
                                                                                  <tr style="overflow-x: auto;" class="test">
                                                                                      
                                                                                  <!--<td ng-repeat="thumbx_link in details.thumbnail_links" style="padding: 5px">-->
                                                                                    <td ng-repeat="(key,f) in Assignment_details.files" style="width: 149px;">
                                                                                        
                                                                                        <div ng-if="Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'mp4' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'MP4' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'avi' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'AVI' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'flv' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'FLV' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'wmv' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'WMV' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'mov' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'MOV' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'webm'  || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'WEBM' ">
                                                                                            <img class="client-img img-thumbnail" src="assets/googledrive_guide/images/videoicon.png" style="height:150px; width:320px; border-radius:8px;" />
                                                                                        </div> 
                                                                                        
                                                                                        <div ng-if="Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'pdf' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'PDF' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'doc' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'DOC' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'docx' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'DOCX' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'ppt' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'PPT' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'pptx' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'PPTX' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'png' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'PNG' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'tif' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'TIF' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'tiff' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'TIFF' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'bmp' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'BMP' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'jpg' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'JPG' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'jpeg' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'JPEG' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'gif' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'GIF' || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'eps'  || Assignment_details.file_names[key].substr(Assignment_details.file_names[key].lastIndexOf('.') + 1) == 'EPS' ">
                                                                                            <img class="client-img img-thumbnail" ng-src="{{Assignment_details.thumbnail_links[key]}}" style="height:150px; width:320px; border-radius:8px;" />
                                                                                        </div>
                                                                                            <div style="text-align: center !important; width: 141px; overflow: hidden; text-overflow: ellipsis;">
                                                                                                <a  href="{{Assignment_details.filesurl[key]}}" target="_blank">{{Assignment_details.file_names[key]}}</a> 
                                                                                                <!--<button class="btn btn-link text-danger" ng-click="removeFile(f.name)" type="button">Remove</button>-->
                                                                                            </div>
                                                                                        
                                                                                    </td>
                                                                                  </tr>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                        <!-- google drive -->
		                                        						
		                                        						
		                                        					</div>
		                                        					<div class="row">
		                                        						<b style="padding-left: 4px;"><?php echo lang('lbl_details') ?> :</b>
		                                        					</div>
		                                        					<p class="card-text" style="margin-top: -5px; padding-left: 15px;">{{Assignment_details.material_details}}</p>
		                                        					<hr>
		                                        					<div class="col-md-12" style="padding-top: 10px;">
		                                        						<img src="<?php echo base_url(); ?>uploads/user/{{Assignment_details.student_avatar}}" alt="user-img" style="width: 40px;" class="img-circle"> <b>{{Assignment_details.student_name}}</b>
		                                        						<div class="row" style="padding-top: 8px;"	>
		                                        							<div style="padding-bottom: 10px;">
		                                        								<b style="padding-left: 4px;"><?php echo lang('lbl_attachments') ?> :</b>
		                                        							</div>
		                                        						</div>
		                                        						<div class="row" style="overflow-wrap: anywhere;">
		                                        							<!--<ul>-->
		                                        							<!--	<div  ng-repeat="std_file in Assignment_details.submitted_files">-->
		                                        							<!--		<li ng-if="['jpg','jpeg','png','gif','PNG'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" data-lightbox="image-attach">-->
		                                        							<!--				<img src="<?php echo base_url(); ?>uploads/study_material/{{std_file}}" style="height: 33px; width: 30px;">-->
		                                        							<!--			</a>-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" data-lightbox="image-attach">{{std_file}}</a>-->
		                                        							<!--		</li>-->
		                                        							<!--		<li ng-if="['pdf'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">-->
		                                        							<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/pdf_icon.png" style="height: 33px; width: 30px;">-->
		                                        							<!--			</a>-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>-->
		                                        							<!--		</li>-->
		                                        							<!--		<li ng-if="['doc','docx'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">-->
		                                        							<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/doc_icon.png" style="height: 33px; width: 30px;">-->
		                                        							<!--			</a>-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>-->
		                                        							<!--		</li>-->
		                                        							<!--		<li ng-if="['xlsx'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">-->
		                                        							<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/excel_icon.jpg" style="height: 33px; width: 30px;">-->
		                                        							<!--			</a>-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>-->
		                                        							<!--		</li>-->
		                                        							<!--		<li ng-if="['ppt','pptx'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">-->
		                                        							<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/ppt_icon.png" style="height: 33px; width: 30px;">-->
		                                        							<!--			</a>-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>-->
		                                        							<!--		</li>-->
		                                        							<!--	</div>                                         					-->
		                                        							<!--</ul>   -->
		                                        							
		                                        							<ul>
		                                        							    <div ng-if="Assignment_details.storage_type == 1">
    		                                        								<div  ng-repeat="std_file in Assignment_details.submitted_files">
    		                                        									<li ng-if="['jpg','jpeg','png','gif','PNG'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" data-lightbox="image-attach">
    		                                        											<img src="<?php echo base_url(); ?>uploads/study_material/{{std_file}}" style="height: 33px; width: 30px;">
    		                                        										</a>
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" data-lightbox="image-attach">{{std_file}}</a>
    		                                        									</li>
    		                                        									<li ng-if="['pdf'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">
    		                                        											<img src="<?php echo base_url(); ?>uploads/study_material/icons/pdf_icon.png" style="height: 33px; width: 30px;">
    		                                        										</a>
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>
    		                                        									</li>
    		                                        									<li ng-if="['doc','docx'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">
    		                                        											<img src="<?php echo base_url(); ?>uploads/study_material/icons/doc_icon.png" style="height: 33px; width: 30px;">
    		                                        										</a>
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>
    		                                        									</li>
    		                                        									<li ng-if="['xlsx'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">
    		                                        											<img src="<?php echo base_url(); ?>uploads/study_material/icons/excel_icon.jpg" style="height: 33px; width: 30px;">
    		                                        										</a>
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>
    		                                        									</li>
    		                                        									<li ng-if="['ppt','pptx'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">
    		                                        											<img src="<?php echo base_url(); ?>uploads/study_material/icons/ppt_icon.png" style="height: 33px; width: 30px;">
    		                                        										</a>
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>
    		                                        									</li>
    		                                        								</div>  
    		                                        							</div>
		                                        							</ul> 
		                                        							
		                                        							<!-- google drive -->
                                                                            <div ng-if="Assignment_details.storage_type == 2" style="overflow-x: auto;">
                                                                                <div style="width:100%; overflow-x: auto;">
                                                                                      <table>
                                                                                      <tr style="overflow-x: auto;" class="test">
                                                                                          
                                                                                      <!--<td ng-repeat="thumbx_link in details.thumbnail_links" style="padding: 5px">-->
                                                                                        <td ng-repeat="(key,f) in Assignment_details.submitted_files" style="width: 149px;">
                                                                                            
                                                                                            <div ng-if="Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'mp4' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'MP4' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'avi' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'AVI' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'flv' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'FLV' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'wmv' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'WMV' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'mov' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'MOV' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'webm'  || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'WEBM' ">
                                                                                                <img class="client-img img-thumbnail" src="assets/googledrive_guide/images/videoicon.png" style="height:150px; width:320px; border-radius:8px;" />
                                                                                            </div> 
                                                                                            
                                                                                            <div ng-if="Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'pdf' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'PDF' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'doc' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'DOC' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'docx' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'DOCX' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'ppt' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'PPT' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'pptx' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'PPTX' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'png' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'PNG' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'tif' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'TIF' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'tiff' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'TIFF' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'bmp' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'BMP' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'jpg' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'JPG' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'jpeg' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'JPEG' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'gif' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'GIF' || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'eps'  || Assignment_details.submitted_file_names[key].substr(Assignment_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'EPS' ">
                                                                                                <img class="client-img img-thumbnail" ng-src="{{Assignment_details.submitted_thumbnail_links[key]}}" style="height:150px; width:320px; border-radius:8px;" />
                                                                                            </div>
                                                                                                <div style="text-align: center !important; width: 141px; overflow: hidden; text-overflow: ellipsis;">
                                                                                                    <a  href="{{Assignment_details.submitted_filesurl[key]}}" target="_blank">{{Assignment_details.submitted_file_names[key]}}</a> 
                                                                                                    <!--<button class="btn btn-link text-danger" ng-click="removeFile(f.name)" type="button">Remove</button>-->
                                                                                                </div>
                                                                                            
                                                                                        </td>
                                                                                      </tr>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                            <!-- google drive -->
		                                        							
		                                        							
		                                        						</div>
		                                        						<div class="row">
		                                        							<b style="padding-left: 4px;"><?php echo lang('description') ?> :</b>
		                                        						</div>
		                                        						<p class="card-text" style="margin-top: 4px; padding-left: 22px;" ng-bind-html="Assignment_details.submitted_details"></p>
		                                        					</div>
		                                        				</div>
		                                        				<!-- right content area -->
		                                        				<div class="col-md-4" style="border-left: 1px solid;">
		                                        					<div class="row">
		                                        						<div class="col-md-12" style="padding-top: 10px;">
		                                        							<b><?php echo lang('lpl_assignment_type') ?> : </b>{{Assignment_details.content_type}}
		                                        						</div>
		                                        					</div>
		                                        					<div class="row">
		                                        						<div class="col-md-12" style="padding-top: 10px;">
		                                        							<b><?php echo lang('lpl_assignment_title') ?> : </b>{{Assignment_details.title}}
		                                        						</div>
		                                        					</div>
		                                        					
		                                        					<div class="row">
		                                        						<div class="col-md-12" style="padding-top: 10px;">
		                                        							<b><?php echo lang('total_marks') ?> : </b>{{Assignment_details.total_marks}}
		                                        						</div>
		                                        						<div class="col-md-12" style="padding-top: 10px;">
		                                        							<b><?php echo lang('due_date') ?> : </b>{{Assignment_details.due_date}}
		                                        						</div>
		                                        					</div>
		                                        					<br>
		                                        					<div class="row" ng-if="Assignment_details.total_marks != '0'">
		                                        						<div class="col-md-12">
		                                        							<div class="form-group">
		                                        								<label class="control-label"><?php echo lang('obtained_marks') ?></label>
		                                        								<input type="number" class="form-control" ng-model="Assignment_details.obtained_marks" value="{{Assignment_details.obtained_marks}}" disabled="disabled">
		                                        							</div>
		                                        						</div>

		                                        					</div>

		                                        					<div class="row">
		                                        						<div class="col-md-12">
		                                        							<div class="form-group">
		                                        								<textarea class="form-control" ng-model="Assignment_details.remarks" rows="5" placeholder="Remarks" disabled="disabled"></textarea>
		                                        							</div>
		                                        						</div>
		                                        					</div>
		                                        				</div>
		                                        			</div>
		                                        		</div>
		                                        	</div>
		                                        </div>
		                                    </div>
		                                    <br>

		                                    <div class="container">
		                                    	<h3 style="text-align: center; font-size: 21px; font-weight: 700; font-family: none; text-decoration: underline;">Comments</h3>
		                                    	<div class="row">
		                                    		<div class="col-md-12">
		                                    			<div class="card mb-0 bg-light col-md-12" style="background-color: white !important; border: 1px solid #e3e3e3; box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);">
		                                    				<div class="col-md-12">
		                                    					<div class="container" ng-repeat="coment in comments">
		                                    						<div class="container black_comments1" ng-if="coment.user_id!=coment.sender_id">
		                                    							<div class="row">
		                                    								<div class="col-md-9" style="padding-top: 10px;">
		                                    									<img src="<?php echo base_url(); ?>uploads/user/{{coment.avatar}}" alt="user-img" style="width: 40px;" class="img-circle"> <b>{{coment.name}}</b>
		                                    								</div>

		                                    							</div>

		                                    							<div class="row" style="padding-top: 8px;" ng-show="coment.files[0] != '' ">
		                                    								<div style="padding-bottom: 10px;">
		                                    									<b style="padding-left: 4px;"><?php echo lang("lbl_attachments"); ?> :</b>
		                                    								</div>
		                                    							</div>

		                                    							<div class="row" style="overflow-wrap: anywhere;">
		                                    								<ul>
		                                    									<div  ng-repeat="cmnt_file in coment.files">
		                                    										<li ng-if="['jpg','jpeg','png','gif','PNG'].includes(cmnt_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" data-lightbox="image-attach">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/{{cmnt_file}}" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" data-lightbox="image-attach">{{cmnt_file}}</a>
		                                    										</li>
		                                    										<li ng-if="['pdf'].includes(cmnt_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/pdf_icon.png" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">{{cmnt_file}}</a>
		                                    										</li>
		                                    										<li ng-if="['doc','docx'].includes(cmnt_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/doc_icon.png" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">{{cmnt_file}}</a>
		                                    										</li>
		                                    										<li ng-if="['xlsx'].includes(cmnt_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/excel_icon.jpg" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">{{cmnt_file}}</a>
		                                    										</li>
		                                    										<li ng-if="['ppt','pptx'].includes(cmnt_file.split('.').pop().toLowerCase())" style="height: 33px; width: 30px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/ppt_icon.png" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">{{cmnt_file}}</a>
		                                    										</li>
		                                    									</div>                                         					
		                                    								</ul>   
		                                    							</div>
		                                    							<div class="row">
		                                    								<b style="padding-left: 4px;"><?php echo lang("crumb_messages"); ?> :</b>
		                                    							</div>
		                                    							<p class="card-text" ng-bind-html="coment.comment_body" style="padding-left: 18px;"></p>

		                                    						</div>
		                                    						<div class="container black_comments" ng-if="coment.user_id==coment.sender_id">
		                                    							<div class="row">
		                                    								<div class="col-md-9" style="padding-top: 10px;">
		                                    									<img src="<?php echo base_url(); ?>uploads/user/{{coment.avatar}}" alt="user-img" style="width: 40px;" class="img-circle"> <b>{{coment.name}}</b>
		                                    								</div>

		                                    							</div>

		                                    							<div class="row" style="padding-top: 8px;" ng-show="coment.files[0] != '' ">
		                                    								<div style="padding-bottom: 10px;">
		                                    									<b style="padding-left: 4px;"><?php echo lang("lbl_attachments"); ?> :</b>
		                                    								</div>
		                                    							</div>

		                                    							<div class="row" style="overflow-wrap: anywhere;">
		                                    								<ul>
		                                    									<div  ng-repeat="cmnt_file in coment.files">
		                                    										<li ng-if="['jpg','jpeg','png','gif','PNG'].includes(cmnt_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" data-lightbox="image-attach">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/{{cmnt_file}}" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" data-lightbox="image-attach">{{cmnt_file}}</a>
		                                    										</li>
		                                    										<li ng-if="['pdf'].includes(cmnt_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/pdf_icon.png" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">{{cmnt_file}}</a>
		                                    										</li>
		                                    										<li ng-if="['doc','docx'].includes(cmnt_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/doc_icon.png" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">{{cmnt_file}}</a>
		                                    										</li>
		                                    										<li ng-if="['xlsx'].includes(cmnt_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/excel_icon.jpg" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">{{cmnt_file}}</a>
		                                    										</li>
		                                    										<li ng-if="['ppt','pptx'].includes(cmnt_file.split('.').pop().toLowerCase())" style="height: 33px; width: 30px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/ppt_icon.png" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file}}" target="_blank">{{cmnt_file}}</a>
		                                    										</li>
		                                    									</div>                                         					
		                                    								</ul>   
		                                    							</div>
		                                    							<div class="row">
		                                    								<b style="padding-left: 4px;"><?php echo lang("crumb_messages"); ?> :</b>
		                                    							</div>
		                                    							<p class="card-text" ng-bind-html="coment.comment_body" style="padding-left: 18px;"></p>

		                                    						</div>
		                                    						<hr>
		                                    					</div>
		                                    					<!-- Comments assignmnet div start here -->
		                                    					<div class="container">
		                                    						<div ng-controller="commentsController" id="CommentsDIVView" style="display: none; margin-bottom: 5px; margin-top: 14px;">
		                                    							<div class="row">
		                                    								<div class="col-md-12">
		                                    									<div class="form-group">
		                                    										<label class="control-label"><?php echo lang("crumb_messages"); ?></label>
		                                    										<textarea class="textarea_editor2 form-control" rows="5" placeholder="" ></textarea>
		                                    									</div>
		                                    								</div>
		                                    							</div>
		                                    							<div class="row">
		                                    								<div class="col-md-12">
		                                    									<div class="form-group">
		                                    										<label class="control-label"><?php echo lang('upload_content') ?></label>
		                                    										<div class="dropzone" id="my-awesome-dropzone_message_view" dropzone="DropzoneConfigMessageView"></div>
		                                    									</div>
		                                    								</div>
		                                    							</div>
		                                    							<div class="alert" id="message_alert_view" style="display: none"></div>
		                                    							<div class="text-center">
		                                    								<button type="button" name="{{Assignment_details.submit_id}}" class="btn btn-primary" id="send_message_view"><i class="fa fa-comments"></i>
		                                    									<?php echo lang("btn_send"); ?>
		                                    								</button>
		                                    							</div><br>
		                                    						</div>	
		                                    					</div>
		                                    					<!-- Comments assignmnet div Ends here -->
		                                    				</div>
		                                    			</div>
		                                    		</div>
		                                    	</div>
		                                    </div>



		                                    <div class="row pull-right" style="margin-top: 12px;">
		                                    	<!-- Comments div hide show button -->
		                                    	<button type="button"  onclick="showCommentsView()" class="btn btn-primary" style="margin-right: 4px; width: 55px;">
		                                    		<i class="fa fa-comments-o"></i>
		                                    	</button>
		                                    	<div style="margin-right: 8px">
		                                    		<button type="button" class="btn btn-default"
		                                    		data-dismiss="modal"><?php echo lang('lbl_close') ?>
		                                    	</button>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		            <!-- assignment view model -->
		            <!-- Homework view model -->
		            <div class="modal fade" id="markHomeworkView" tabindex="-1" role="dialog"
		            aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		            <div class="modal-dialog modal-lg" role="document">
		            	<div class="modal-content">
		            		<div class="panel panel-primary">
		            			<div class="panel-heading"><?php echo lang('lpl_homework_title') ?></div>
		            			<div class="panel-body">
		            				<div class="row">
		            					<div class="col-md-12">
		            						<div class="card mb-0 bg-light col-md-12" style="background-color: white !important; margin-top: 10px; border: 1px solid #e3e3e3; box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);">
		            							<div class="card-body">
		            								<div class="row">
		            									<div class="col-md-8">
		            										<div class="row">
		            											<div class="col-md-6" style="padding-top: 10px;">
		            												<img src="<?php echo base_url(); ?>uploads/user/{{Homework_details.teacher_avatar}}" alt="user-img" style="width: 40px;" class="img-circle"> <b>{{Homework_details.teacher_name}}</b>
		            											</div>


		            											<div class="col-md-6" style="padding-top: 10px;">
		            												<b></b>{{Homework_details.published_date  | date:'medium'}}
		            											</div>

		                                        						<!-- <div class="col-md-4" style="padding-top: 10px;">
		                                        							<b>Subject : </b>{{Assinged_student_detials.subject_name}}
		                                        						</div> -->
		                                        					</div>
		                                        					<div class="row">
		                                        						
		                                        						<p class="card-text" style="padding-top: 4px; padding-left: 22px;" ng-bind-html="Homework_details.details"></p>
		                                        					</div>
		                                        					
		                                        					<div class="row">
		                                        						<div style="padding-bottom: 10px;">
		                                        							<b style="padding-left: 4px;"><?php echo lang('lbl_attachments') ?> :</b>
		                                        						</div>
		                                        					</div>
		                                        					<div class="row" style="overflow-wrap: anywhere;">
		                                        					    
		                                        						<!--<ul>-->
		                                        						<!--	<div ng-repeat="file in Homework_details.files">-->
		                                        						<!--		<li ng-if="['jpg','jpeg','png','gif','PNG'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" data-lightbox="image-attach">-->
		                                        						<!--				<img src="<?php echo base_url(); ?>uploads/study_material/{{file}}" style="height: 33px; width: 30px;">-->
		                                        						<!--			</a>-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" data-lightbox="image-attach">{{file}}</a>-->
		                                        						<!--		</li>-->
		                                        						<!--		<li ng-if="['pdf'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">-->
		                                        						<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/pdf_icon.png" style="height: 33px; width: 30px;">-->
		                                        						<!--			</a>-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>-->
		                                        						<!--		</li>-->
		                                        						<!--		<li ng-if="['doc','docx'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">-->
		                                        						<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/doc_icon.png" style="height: 33px; width: 30px;">-->
		                                        						<!--			</a>-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>-->
		                                        						<!--		</li>-->
		                                        						<!--		<li ng-if="['xlsx'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">-->
		                                        						<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/excel_icon.jpg" style="height: 33px; width: 30px;">-->
		                                        						<!--			</a>-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>-->
		                                        						<!--		</li>-->
		                                        						<!--		<li ng-if="['ppt','pptx'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">-->
		                                        						<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/ppt_icon.png" style="height: 33px; width: 30px;">-->
		                                        						<!--			</a>-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>-->
		                                        						<!--		</li>-->
		                                        						<!--	</div>                                         					-->
		                                        						<!--</ul> -->
		                                        						
		                                        						<ul>
		                                        						    <div ng-if="Homework_details.storage_type == 1">
    		                                        							<div ng-repeat="file in Homework_details.files">
    		                                        								<li ng-if="['jpg','jpeg','png','gif','PNG'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" data-lightbox="image-attach">
    		                                        										<img src="<?php echo base_url(); ?>uploads/study_material/{{file}}" style="height: 33px; width: 30px;">
    		                                        									</a>
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" data-lightbox="image-attach">{{file}}</a>
    		                                        								</li>
    		                                        								<li ng-if="['pdf'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">
    		                                        										<img src="<?php echo base_url(); ?>uploads/study_material/icons/pdf_icon.png" style="height: 33px; width: 30px;">
    		                                        									</a>
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>
    		                                        								</li>
    		                                        								<li ng-if="['doc','docx'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">
    		                                        										<img src="<?php echo base_url(); ?>uploads/study_material/icons/doc_icon.png" style="height: 33px; width: 30px;">
    		                                        									</a>
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>
    		                                        								</li>
    		                                        								<li ng-if="['xlsx'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">
    		                                        										<img src="<?php echo base_url(); ?>uploads/study_material/icons/excel_icon.jpg" style="height: 33px; width: 30px;">
    		                                        									</a>
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>
    		                                        								</li>
    		                                        								<li ng-if="['ppt','pptx'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">
    		                                        										<img src="<?php echo base_url(); ?>uploads/study_material/icons/ppt_icon.png" style="height: 33px; width: 30px;">
    		                                        									</a>
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>
    		                                        								</li>
    		                                        							</div>
    		                                        						</div>
		                                        						</ul>
		                                        						
		                                        						<!-- google drive -->
                                                                        <div ng-if="Homework_details.storage_type == 2" style="overflow-x: auto;">
                                                                            <div style="width:100%; overflow-x: auto;">
                                                                                  <table>
                                                                                  <tr style="overflow-x: auto;" class="test">
                                                                                      
                                                                                  <!--<td ng-repeat="thumbx_link in details.thumbnail_links" style="padding: 5px">-->
                                                                                    <td ng-repeat="(key,f) in Homework_details.files" style="width: 149px;">
                                                                                        
                                                                                        <div ng-if="Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'mp4' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'MP4' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'avi' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'AVI' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'flv' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'FLV' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'wmv' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'WMV' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'mov' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'MOV' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'webm'  || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'WEBM' ">
                                                                                            <img class="client-img img-thumbnail" src="assets/googledrive_guide/images/videoicon.png" style="height:150px; width:320px; border-radius:8px;" />
                                                                                        </div> 
                                                                                        
                                                                                        <div ng-if="Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'pdf' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'PDF' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'doc' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'DOC' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'docx' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'DOCX' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'ppt' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'PPT' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'pptx' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'PPTX' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'png' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'PNG' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'tif' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'TIF' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'tiff' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'TIFF' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'bmp' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'BMP' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'jpg' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'JPG' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'jpeg' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'JPEG' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'gif' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'GIF' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'eps'  || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'EPS' ">
                                                                                            <img class="client-img img-thumbnail" ng-src="{{Homework_details.thumbnail_links[key]}}" style="height:150px; width:320px; border-radius:8px;" />
                                                                                        </div>
                                                                                            <div style="text-align: center !important; width: 141px; overflow: hidden; text-overflow: ellipsis;">
                                                                                                <a  href="{{Homework_details.filesurl[key]}}" target="_blank">{{Homework_details.file_names[key]}}</a> 
                                                                                                <!--<button class="btn btn-link text-danger" ng-click="removeFile(f.name)" type="button">Remove</button>-->
                                                                                            </div>
                                                                                        
                                                                                    </td>
                                                                                  </tr>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                        <!-- google drive -->
		                                        						
		                                        						
		                                        					</div>
		                                        					<div class="row">
		                                        						<b style="padding-left: 4px;"><?php echo lang('lbl_details') ?> :</b>
		                                        					</div>
		                                        					<p class="card-text" style="margin-top: 4px; padding-left: 22px;">{{Homework_details.material_details}}</p>
		                                        					<hr>
		                                        					<div class="col-md-12" style="padding-top: 10px;">
		                                        						<img src="<?php echo base_url(); ?>uploads/user/{{Homework_details.student_avatar}}" alt="user-img" style="width: 40px;" class="img-circle"> <b>{{Homework_details.student_name}}</b>
		                                        						<div class="row" style="padding-top: 8px;">
		                                        							<div style="padding-bottom: 10px;">
		                                        								<b style="padding-left: 4px;"><?php echo lang('lbl_attachments') ?> :</b>
		                                        							</div>
		                                        						</div>
		                                        						<div class="row" style="overflow-wrap: anywhere;">
		                                        							<!--<ul>-->
		                                        							<!--	<div  ng-repeat="std_file in Homework_details.submitted_files">-->
		                                        							<!--		<li ng-if="['jpg','jpeg','png','gif','PNG'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" data-lightbox="image-attach">-->
		                                        							<!--				<img src="<?php echo base_url(); ?>uploads/study_material/{{std_file}}" style="height: 33px; width: 30px;">-->
		                                        							<!--			</a>-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" data-lightbox="image-attach">{{std_file}}</a>-->
		                                        							<!--		</li>-->
		                                        							<!--		<li ng-if="['pdf'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">-->
		                                        							<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/pdf_icon.png" style="height: 33px; width: 30px;">-->
		                                        							<!--			</a>-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>-->
		                                        							<!--		</li>-->
		                                        							<!--		<li ng-if="['doc','docx'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">-->
		                                        							<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/doc_icon.png" style="height: 33px; width: 30px;">-->
		                                        							<!--			</a>-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>-->
		                                        							<!--		</li>-->
		                                        							<!--		<li ng-if="['xlsx'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">-->
		                                        							<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/excel_icon.jpg" style="height: 33px; width: 30px;">-->
		                                        							<!--			</a>-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>-->
		                                        							<!--		</li>-->
		                                        							<!--		<li ng-if="['ppt','pptx'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">-->
		                                        							<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/ppt_icon.png" style="height: 33px; width: 30px;">-->
		                                        							<!--			</a>-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>-->
		                                        							<!--		</li>-->
		                                        							<!--	</div>                                         					-->
		                                        							<!--</ul>   -->
		                                        							
		                                        							<ul>
		                                        							    <div ng-if="Homework_details.storage_type == 1">
    		                                        								<div  ng-repeat="std_file in Homework_details.submitted_files">
    		                                        									<li ng-if="['jpg','jpeg','png','gif','PNG'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" data-lightbox="image-attach">
    		                                        											<img src="<?php echo base_url(); ?>uploads/study_material/{{std_file}}" style="height: 33px; width: 30px;">
    		                                        										</a>
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" data-lightbox="image-attach">{{std_file}}</a>
    		                                        									</li>
    		                                        									<li ng-if="['pdf'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">
    		                                        											<img src="<?php echo base_url(); ?>uploads/study_material/icons/pdf_icon.png" style="height: 33px; width: 30px;">
    		                                        										</a>
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>
    		                                        									</li>
    		                                        									<li ng-if="['doc','docx'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">
    		                                        											<img src="<?php echo base_url(); ?>uploads/study_material/icons/doc_icon.png" style="height: 33px; width: 30px;">
    		                                        										</a>
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>
    		                                        									</li>
    		                                        									<li ng-if="['xlsx'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">
    		                                        											<img src="<?php echo base_url(); ?>uploads/study_material/icons/excel_icon.jpg" style="height: 33px; width: 30px;">
    		                                        										</a>
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>
    		                                        									</li>
    		                                        									<li ng-if="['ppt','pptx'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">
    		                                        											<img src="<?php echo base_url(); ?>uploads/study_material/icons/ppt_icon.png" style="height: 33px; width: 30px;">
    		                                        										</a>
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>
    		                                        									</li>
    		                                        								</div>        
    		                                        							</div>
		                                        							</ul> 
		                                        							
		                                        							<!-- google drive -->
                                                                            <div ng-if="Homework_details.storage_type == 2" style="overflow-x: auto;">
                                                                                <div style="width:100%; overflow-x: auto;">
                                                                                      <table>
                                                                                      <tr style="overflow-x: auto;" class="test">
                                                                                          
                                                                                      <!--<td ng-repeat="thumbx_link in details.thumbnail_links" style="padding: 5px">-->
                                                                                        <td ng-repeat="(key,f) in Homework_details.submitted_files" style="width: 149px;">
                                                                                            
                                                                                            <div ng-if="Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'mp4' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'MP4' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'avi' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'AVI' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'flv' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'FLV' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'wmv' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'WMV' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'mov' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'MOV' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'webm'  || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'WEBM' ">
                                                                                                <img class="client-img img-thumbnail" src="assets/googledrive_guide/images/videoicon.png" style="height:150px; width:320px; border-radius:8px;" />
                                                                                            </div> 
                                                                                            
                                                                                            <div ng-if="Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'pdf' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'PDF' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'doc' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'DOC' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'docx' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'DOCX' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'ppt' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'PPT' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'pptx' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'PPTX' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'png' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'PNG' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'tif' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'TIF' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'tiff' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'TIFF' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'bmp' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'BMP' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'jpg' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'JPG' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'jpeg' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'JPEG' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'gif' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'GIF' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'eps'  || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'EPS' ">
                                                                                                <img class="client-img img-thumbnail" ng-src="{{Homework_details.submitted_thumbnail_links[key]}}" style="height:150px; width:320px; border-radius:8px;" />
                                                                                            </div>
                                                                                                <div style="text-align: center !important; width: 141px; overflow: hidden; text-overflow: ellipsis;">
                                                                                                    <a  href="{{Homework_details.submitted_filesurl[key]}}" target="_blank">{{Homework_details.submitted_file_names[key]}}</a> 
                                                                                                    <!--<button class="btn btn-link text-danger" ng-click="removeFile(f.name)" type="button">Remove</button>-->
                                                                                                </div>
                                                                                            
                                                                                        </td>
                                                                                      </tr>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                            <!-- google drive -->
		                                        							
		                                        						</div>
		                                        						<div class="row">
		                                        							<b style="padding-left: 4px;"><?php echo lang('description') ?> :</b>
		                                        						</div>
		                                        						<p class="card-text" style="padding-left: 22px;" ng-bind-html="Homework_details.submitted_details"></p>
		                                        					</div>
		                                        				</div>
		                                        				<!-- right content area -->
		                                        				<div class="col-md-4" style="border-left: 1px solid;">
		                                        					<div class="row">
		                                        						<div class="col-md-12" style="padding-top: 10px;">
		                                        							<b><?php echo lang('lpl_assignment_type') ?> : </b>{{Homework_details.content_type}}
		                                        						</div>
		                                        					</div>
		                                        					<div class="row">
		                                        						<div class="col-md-12" style="padding-top: 10px;">
		                                        							<b><?php echo lang('lpl_homework_title') ?> : </b>{{Homework_details.title}}
		                                        						</div>
		                                        					</div>
		                                        					
		                                        					<div class="row">
		                                        						<div class="col-md-12" style="padding-top: 10px;">
		                                        							<b><?php echo lang('total_marks') ?> : </b>{{Homework_details.total_marks}}
		                                        						</div>
		                                        						<div class="col-md-12" style="padding-top: 10px;">
		                                        							<b><?php echo lang('due_date') ?> : </b>{{Homework_details.due_date}}
		                                        						</div>
		                                        					</div>
		                                        					<br>
		                                        					<div class="row">
		                                        						<div class="col-md-12" ng-if="Homework_details.total_marks != '0'">
		                                        							<div class="form-group">
		                                        								<label class="control-label"><?php echo lang('obtained_marks') ?></label>
		                                        								<input type="number" disabled="disabled" class="form-control" ng-model="Homework_details.obtained_marks" value="{{Homework_details.obtained_marks}}">
		                                        							</div>
		                                        						</div>

		                                        					</div>

		                                        					<div class="row">
		                                        						<div class="col-md-12">
		                                        							<div class="form-group">
		                                        								<textarea class="form-control" ng-model="Homework_details.remarks" disabled="disabled" rows="5" placeholder="Remarks"></textarea>
		                                        							</div>
		                                        						</div>
		                                        					</div>
		                                        				</div>
		                                        			</div>
		                                        		</div>
		                                        	</div>
		                                        </div>
		                                    </div>
		                                    <br>

		                                    <div class="container">
		                                    	<h3 style="text-align: center; font-size: 21px; font-weight: 700;  font-family: none; text-decoration: underline;">Comments</h3>
		                                    	<div class="row">
		                                    		<div class="col-md-12">
		                                    			<div class="card mb-0 bg-light col-md-12" style="background-color: white !important; border: 1px solid #e3e3e3; box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);">
		                                    				<div class="col-md-12">
		                                    					<div class="container" ng-repeat="comentHom in HomComments">
		                                    						<div class="container black_comments1" ng-if="comentHom.user_id!=comentHom.sender_id">
		                                    							<div class="row">
		                                    								<div class="col-md-9" style="padding-top: 10px;">
		                                    									<img src="<?php echo base_url(); ?>uploads/user/{{comentHom.avatar}}" alt="user-img" style="width: 40px;" class="img-circle"> <b>{{comentHom.name}}</b>
		                                    								</div>

		                                    							</div>

		                                    							<div class="row" style="padding-top: 8px;" ng-show="comentHom.files[0] != '' ">
		                                    								<div style="padding-bottom: 10px;">
		                                    									<b style="padding-left: 4px;"><?php echo lang('lbl_attachments') ?> :</b>
		                                    								</div>
		                                    							</div>

		                                    							<div class="row" style="overflow-wrap: anywhere;">
		                                    								<ul>
		                                    									<div  ng-repeat="cmnt_file1 in comentHom.files">
		                                    										<li ng-if="['jpg','jpeg','png','gif','PNG'].includes(cmnt_file1.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" data-lightbox="image-attach">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/{{cmnt_file1}}" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" data-lightbox="image-attach">{{cmnt_file1}}</a>
		                                    										</li>
		                                    										<li ng-if="['pdf'].includes(cmnt_file1.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/pdf_icon.png" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">{{cmnt_file1}}</a>
		                                    										</li>
		                                    										<li ng-if="['doc','docx'].includes(cmnt_file1.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/doc_icon.png" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">{{cmnt_file1}}</a>
		                                    										</li>
		                                    										<li ng-if="['xlsx'].includes(cmnt_file1.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/excel_icon.jpg" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">{{cmnt_file}}</a>
		                                    										</li>
		                                    										<li ng-if="['ppt','pptx'].includes(cmnt_file1.split('.').pop().toLowerCase())" style="height: 33px; width: 30px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/ppt_icon.png" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">{{cmnt_file1}}</a>
		                                    										</li>
		                                    									</div>                                         					
		                                    								</ul>   
		                                    							</div>
		                                    							<div class="row">
		                                    								<b style="padding-left: 4px;"><?php echo lang('crumb_messages') ?> :</b>
		                                    							</div>
		                                    							<p class="card-text" ng-bind-html="comentHom.comment_body" style="padding-left: 18px;"></p>
		                                    						</div>
		                                    						<div class="container black_comments" ng-if="comentHom.user_id==comentHom.sender_id">
		                                    							<div class="row">
		                                    								<div class="col-md-9" style="padding-top: 10px;">
		                                    									<img src="<?php echo base_url(); ?>uploads/user/{{comentHom.avatar}}" alt="user-img" style="width: 40px;" class="img-circle"> <b>{{comentHom.name}}</b>
		                                    								</div>

		                                    							</div>

		                                    							<div class="row" style="padding-top: 8px;" ng-show="comentHom.files[0] != '' ">
		                                    								<div style="padding-bottom: 10px;">
		                                    									<b style="padding-left: 4px;"><?php echo lang('lbl_attachments') ?> :</b>
		                                    								</div>
		                                    							</div>

		                                    							<div class="row" style="overflow-wrap: anywhere;">
		                                    								<ul>
		                                    									<div  ng-repeat="cmnt_file1 in comentHom.files">
		                                    										<li ng-if="['jpg','jpeg','png','gif','PNG'].includes(cmnt_file1.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" data-lightbox="image-attach">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/{{cmnt_file1}}" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" data-lightbox="image-attach">{{cmnt_file1}}</a>
		                                    										</li>
		                                    										<li ng-if="['pdf'].includes(cmnt_file1.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/pdf_icon.png" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">{{cmnt_file1}}</a>
		                                    										</li>
		                                    										<li ng-if="['doc','docx'].includes(cmnt_file1.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/doc_icon.png" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">{{cmnt_file1}}</a>
		                                    										</li>
		                                    										<li ng-if="['xlsx'].includes(cmnt_file1.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/excel_icon.jpg" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">{{cmnt_file1}}</a>
		                                    										</li>
		                                    										<li ng-if="['ppt','pptx'].includes(cmnt_file1.split('.').pop().toLowerCase())" style="height: 33px; width: 30px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/ppt_icon.png" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">{{cmnt_file1}}</a>
		                                    										</li>
		                                    									</div>                                         					
		                                    								</ul>   
		                                    							</div>
		                                    							<div class="row">
		                                    								<b style="padding-left: 4px;"><?php echo lang('crumb_messages') ?> :</b>
		                                    							</div>
		                                    							<p class="card-text" ng-bind-html="comentHom.comment_body" style="padding-left: 18px;"></p>
		                                    						</div>
		                                    						<hr>
		                                    					</div>
		                                    					<!-- Comments assignmnet div start here -->
		                                    					<div class="container">
		                                    						<div ng-controller="commentsController" id="CommentsDIVHomView" style="display: none; margin-bottom: 5px; margin-top: 14px;">
		                                    							<div class="row">
		                                    								<div class="col-md-12">
		                                    									<div class="form-group">
		                                    										<label class="control-label"><?php echo lang('crumb_messages') ?></label>
		                                    										<textarea class="textarea_editor3 form-control" rows="5" placeholder="" ></textarea>
		                                    									</div>
		                                    								</div>
		                                    							</div>
		                                    							<div class="row">
		                                    								<div class="col-md-12">
		                                    									<div class="form-group">
		                                    										<label class="control-label"><?php echo lang('upload_content') ?></label>
		                                    										<div class="dropzone" id="my-awesome-dropzone_message_hom_view" dropzone="DropzoneConfigMessageHomView"></div>
		                                    									</div>
		                                    								</div>
		                                    							</div>
		                                    							<div class="alert" id="message_alert_hom_view" style="display: none"></div>
		                                    							<div class="text-center">
		                                    								<button type="button" name="{{Homework_details.submit_id}}" class="btn btn-primary" id="send_message_hom_view">
		                                    									<i class="fa fa-comments"></i> Send
		                                    								</button>
		                                    							</div><br>
		                                    						</div>
		                                    					</div>
		                                    					<!-- Comments assignmnet div Ends here -->
		                                    				</div>		                                    		
		                                    			</div>
		                                    		</div>
		                                    	</div>
		                                    </div><br>



		                                    <div class="row pull-right" style="margin-top: 12px;">
		                                    	<div style="margin-right: 8px">
		                                    		<button type="button"  onclick="showCommentsHomeView()" class="btn btn-primary" style="margin-right: 4px; width: 55px;">
		                                    			<i class="fa fa-comments-o"></i>
		                                    		</button>
		                                    		<button type="button" class="btn btn-default"
		                                    		data-dismiss="modal"><?php echo lang('lbl_close') ?>
		                                    	</button>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		            <!-- homework view model -->


		            <!-- Homework model -->
		            <div class="modal fade" id="markHomework" tabindex="-1" role="dialog"
		            aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		            <div class="modal-dialog modal-lg" role="document">
		            	<div class="modal-content">
		            		<div class="panel panel-primary">
		            			<div class="panel-heading"><?php echo lang('lpl_homework_title') ?></div>
		            			<form ng-submit="SaveHomeworkMark()">
		            				<div class="panel-body">
		            					<div class="row">
		            						<div class="col-md-12">
		            							<div class="card mb-0 bg-light col-md-12" style="background-color: white !important; margin-top: 10px; border: 1px solid #e3e3e3; box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);">
		            								<div class="card-body">
		            									<div class="row">
		            										<div class="col-md-8">
		            											<div class="row">
		            												<div class="col-md-6" style="padding-top: 10px;">
		            													<img src="<?php echo base_url(); ?>uploads/user/{{Homework_details.teacher_avatar}}" alt="user-img" style="width: 40px;" class="img-circle"> <b>{{Homework_details.teacher_name}}</b>
		            												</div>


		            												<div class="col-md-6" style="padding-top: 10px;">
		            													<b></b>{{Homework_details.published_date  | date:'medium'}}
		            												</div>

		                                        						<!-- <div class="col-md-4" style="padding-top: 10px;">
		                                        							<b>Subject : </b>{{Assinged_student_detials.subject_name}}
		                                        						</div> -->
		                                        					</div>
		                                        					<div class="row">
		                                        						<p class="card-text" style="padding-top: 4px; padding-left: 22px;" ng-bind-html="Homework_details.details"></p>
		                                        					</div>
		                                        					
		                                        					<div class="row">
		                                        						<div style="padding-bottom: 10px;">
		                                        							<b style="padding-left: 4px;"><?php echo lang('lbl_attachments') ?> :</b>
		                                        						</div>
		                                        					</div>
		                                        					<div class="row" style="overflow-wrap: anywhere;">
		                                        						<!--<ul>-->
		                                        						<!--	<div ng-repeat="file in Homework_details.files">-->
		                                        						<!--		<li ng-if="['jpg','jpeg','png','gif','PNG'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" data-lightbox="image-attach">-->
		                                        						<!--				<img src="<?php echo base_url(); ?>uploads/study_material/{{file}}" style="height: 33px; width: 30px;">-->
		                                        						<!--			</a>-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" data-lightbox="image-attach">{{file}}</a>-->
		                                        						<!--		</li>-->
		                                        						<!--		<li ng-if="['pdf'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">-->
		                                        						<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/pdf_icon.png" style="height: 33px; width: 30px;">-->
		                                        						<!--			</a>-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>-->
		                                        						<!--		</li>-->
		                                        						<!--		<li ng-if="['doc','docx'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">-->
		                                        						<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/doc_icon.png" style="height: 33px; width: 30px;">-->
		                                        						<!--			</a>-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>-->
		                                        						<!--		</li>-->
		                                        						<!--		<li ng-if="['xlsx'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">-->
		                                        						<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/excel_icon.jpg" style="height: 33px; width: 30px;">-->
		                                        						<!--			</a>-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>-->
		                                        						<!--		</li>-->
		                                        						<!--		<li ng-if="['ppt','pptx'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">-->
		                                        						<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/ppt_icon.png" style="height: 33px; width: 30px;">-->
		                                        						<!--			</a>-->
		                                        						<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>-->
		                                        						<!--		</li>-->
		                                        						<!--	</div>                                         					-->
		                                        						<!--</ul>   -->
		                                        						
		                                        						<ul>
		                                        						    <div ng-if="Homework_details.storage_type == 1">
    		                                        							<div ng-repeat="file in Homework_details.files">
    		                                        								<li ng-if="['jpg','jpeg','png','gif','PNG'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" data-lightbox="image-attach">
    		                                        										<img src="<?php echo base_url(); ?>uploads/study_material/{{file}}" style="height: 33px; width: 30px;">
    		                                        									</a>
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" data-lightbox="image-attach">{{file}}</a>
    		                                        								</li>
    		                                        								<li ng-if="['pdf'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">
    		                                        										<img src="<?php echo base_url(); ?>uploads/study_material/icons/pdf_icon.png" style="height: 33px; width: 30px;">
    		                                        									</a>
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>
    		                                        								</li>
    		                                        								<li ng-if="['doc','docx'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">
    		                                        										<img src="<?php echo base_url(); ?>uploads/study_material/icons/doc_icon.png" style="height: 33px; width: 30px;">
    		                                        									</a>
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>
    		                                        								</li>
    		                                        								<li ng-if="['xlsx'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">
    		                                        										<img src="<?php echo base_url(); ?>uploads/study_material/icons/excel_icon.jpg" style="height: 33px; width: 30px;">
    		                                        									</a>
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>
    		                                        								</li>
    		                                        								<li ng-if="['ppt','pptx'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">
    		                                        										<img src="<?php echo base_url(); ?>uploads/study_material/icons/ppt_icon.png" style="height: 33px; width: 30px;">
    		                                        									</a>
    		                                        									<a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a>
    		                                        								</li>
    		                                        							</div>
    		                                        						</div>
		                                        						</ul> 
		                                        						
		                                        						
		                                        						<!-- google drive -->
                                                                        <div ng-if="Homework_details.storage_type == 2" style="overflow-x: auto;">
                                                                            <div style="width:100%; overflow-x: auto;">
                                                                                  <table>
                                                                                  <tr style="overflow-x: auto;" class="test">
                                                                                      
                                                                                  <!--<td ng-repeat="thumbx_link in details.thumbnail_links" style="padding: 5px">-->
                                                                                    <td ng-repeat="(key,f) in Homework_details.files" style="width: 149px;">
                                                                                        
                                                                                        <div ng-if="Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'mp4' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'MP4' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'avi' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'AVI' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'flv' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'FLV' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'wmv' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'WMV' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'mov' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'MOV' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'webm'  || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'WEBM' ">
                                                                                            <img class="client-img img-thumbnail" src="assets/googledrive_guide/images/videoicon.png" style="height:150px; width:320px; border-radius:8px;" />
                                                                                        </div> 
                                                                                        
                                                                                        <div ng-if="Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'pdf' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'PDF' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'doc' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'DOC' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'docx' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'DOCX' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'ppt' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'PPT' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'pptx' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'PPTX' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'png' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'PNG' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'tif' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'TIF' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'tiff' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'TIFF' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'bmp' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'BMP' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'jpg' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'JPG' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'jpeg' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'JPEG' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'gif' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'GIF' || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'eps'  || Homework_details.file_names[key].substr(Homework_details.file_names[key].lastIndexOf('.') + 1) == 'EPS' ">
                                                                                            <img class="client-img img-thumbnail" ng-src="{{Homework_details.thumbnail_links[key]}}" style="height:150px; width:320px; border-radius:8px;" />
                                                                                        </div>
                                                                                            <div style="text-align: center !important; width: 141px; overflow: hidden; text-overflow: ellipsis;">
                                                                                                <a  href="{{Homework_details.filesurl[key]}}" target="_blank">{{Homework_details.file_names[key]}}</a> 
                                                                                                <!--<button class="btn btn-link text-danger" ng-click="removeFile(f.name)" type="button">Remove</button>-->
                                                                                            </div>
                                                                                        
                                                                                    </td>
                                                                                  </tr>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                        <!-- google drive -->
		                                        						
		                                        						
		                                        					</div>
		                                        					<div class="row">
		                                        						<b style="padding-left: 4px;"><?php echo lang('lbl_details') ?> :</b>
		                                        					</div>
		                                        					<p class="card-text" style="margin-top: 4px; padding-left: 22px;">{{Homework_details.material_details}}</p>
		                                        					<hr>
		                                        					<div class="col-md-12" style="padding-top: 10px;">
		                                        						<img src="<?php echo base_url(); ?>uploads/user/{{Homework_details.student_avatar}}" alt="user-img" style="width: 40px;" class="img-circle"> <b>{{Homework_details.student_name}}</b>
		                                        						<div class="row" style="padding-top: 8px;">
		                                        							<div style="padding-bottom: 10px;">
		                                        								<b style="padding-left: 4px;"><?php echo lang('lbl_attachments') ?> :</b>
		                                        							</div>
		                                        						</div>
		                                        						<div class="row" style="overflow-wrap: anywhere;">
		                                        							<!--<ul>-->
		                                        							<!--	<div  ng-repeat="std_file in Homework_details.submitted_files">-->
		                                        							<!--		<li ng-if="['jpg','jpeg','png','gif','PNG'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" data-lightbox="image-attach">-->
		                                        							<!--				<img src="<?php echo base_url(); ?>uploads/study_material/{{std_file}}" style="height: 33px; width: 30px;">-->
		                                        							<!--			</a>-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" data-lightbox="image-attach">{{std_file}}</a>-->
		                                        							<!--		</li>-->
		                                        							<!--		<li ng-if="['pdf'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">-->
		                                        							<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/pdf_icon.png" style="height: 33px; width: 30px;">-->
		                                        							<!--			</a>-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>-->
		                                        							<!--		</li>-->
		                                        							<!--		<li ng-if="['doc','docx'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">-->
		                                        							<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/doc_icon.png" style="height: 33px; width: 30px;">-->
		                                        							<!--			</a>-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>-->
		                                        							<!--		</li>-->
		                                        							<!--		<li ng-if="['xlsx'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">-->
		                                        							<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/excel_icon.jpg" style="height: 33px; width: 30px;">-->
		                                        							<!--			</a>-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>-->
		                                        							<!--		</li>-->
		                                        							<!--		<li ng-if="['ppt','pptx'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">-->
		                                        							<!--				<img src="<?php echo base_url(); ?>uploads/study_material/icons/ppt_icon.png" style="height: 33px; width: 30px;">-->
		                                        							<!--			</a>-->
		                                        							<!--			<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>-->
		                                        							<!--		</li>-->
		                                        							<!--	</div>                                         					-->
		                                        							<!--</ul>   -->
		                                        							
		                                        							
		                                        							<ul>
		                                        							    <div ng-if="Homework_details.storage_type == 1">
    		                                        								<div  ng-repeat="std_file in Homework_details.submitted_files">
    		                                        									<li ng-if="['jpg','jpeg','png','gif','PNG'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" data-lightbox="image-attach">
    		                                        											<img src="<?php echo base_url(); ?>uploads/study_material/{{std_file}}" style="height: 33px; width: 30px;">
    		                                        										</a>
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" data-lightbox="image-attach">{{std_file}}</a>
    		                                        									</li>
    		                                        									<li ng-if="['pdf'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">
    		                                        											<img src="<?php echo base_url(); ?>uploads/study_material/icons/pdf_icon.png" style="height: 33px; width: 30px;">
    		                                        										</a>
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>
    		                                        									</li>
    		                                        									<li ng-if="['doc','docx'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">
    		                                        											<img src="<?php echo base_url(); ?>uploads/study_material/icons/doc_icon.png" style="height: 33px; width: 30px;">
    		                                        										</a>
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>
    		                                        									</li>
    		                                        									<li ng-if="['xlsx'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">
    		                                        											<img src="<?php echo base_url(); ?>uploads/study_material/icons/excel_icon.jpg" style="height: 33px; width: 30px;">
    		                                        										</a>
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>
    		                                        									</li>
    		                                        									<li ng-if="['ppt','pptx'].includes(std_file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">
    		                                        											<img src="<?php echo base_url(); ?>uploads/study_material/icons/ppt_icon.png" style="height: 33px; width: 30px;">
    		                                        										</a>
    		                                        										<a href="<?php echo base_url() ?>uploads/study_material/{{std_file}}" target="_blank">{{std_file}}</a>
    		                                        									</li>
    		                                        								</div> 
    		                                        							</div>
		                                        							</ul> 
		                                        							
		                                        							
		                                        							<!-- google drive -->
                                                                            <div ng-if="Homework_details.storage_type == 2" style="overflow-x: auto;">
                                                                                <div style="width:100%; overflow-x: auto;">
                                                                                      <table>
                                                                                      <tr style="overflow-x: auto;" class="test">
                                                                                          
                                                                                      <!--<td ng-repeat="thumbx_link in details.thumbnail_links" style="padding: 5px">-->
                                                                                        <td ng-repeat="(key,f) in Homework_details.submitted_files" style="width: 149px;">
                                                                                            
                                                                                            <div ng-if="Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'mp4' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'MP4' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'avi' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'AVI' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'flv' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'FLV' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'wmv' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'WMV' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'mov' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'MOV' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'webm'  || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'WEBM' ">
                                                                                                <img class="client-img img-thumbnail" src="assets/googledrive_guide/images/videoicon.png" style="height:150px; width:320px; border-radius:8px;" />
                                                                                            </div> 
                                                                                            
                                                                                            <div ng-if="Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'pdf' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'PDF' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'doc' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'DOC' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'docx' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'DOCX' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'ppt' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'PPT' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'pptx' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'PPTX' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'png' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'PNG' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'tif' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'TIF' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'tiff' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'TIFF' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'bmp' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'BMP' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'jpg' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'JPG' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'jpeg' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'JPEG' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'gif' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'GIF' || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'eps'  || Homework_details.submitted_file_names[key].substr(Homework_details.submitted_file_names[key].lastIndexOf('.') + 1) == 'EPS' ">
                                                                                                <img class="client-img img-thumbnail" ng-src="{{Homework_details.submitted_thumbnail_links[key]}}" style="height:150px; width:320px; border-radius:8px;" />
                                                                                            </div>
                                                                                                <div style="text-align: center !important; width: 141px; overflow: hidden; text-overflow: ellipsis;">
                                                                                                    <a  href="{{Homework_details.submitted_filesurl[key]}}" target="_blank">{{Homework_details.submitted_file_names[key]}}</a> 
                                                                                                    <!--<button class="btn btn-link text-danger" ng-click="removeFile(f.name)" type="button">Remove</button>-->
                                                                                                </div>
                                                                                            
                                                                                        </td>
                                                                                      </tr>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                            <!-- google drive -->
		                                        							
		                                        							
		                                        						</div>
		                                        						<div class="row">
		                                        							<b style="padding-left: 4px;"><?php echo lang('description') ?> :</b>
		                                        						</div>
		                                        						<p class="card-text" style="padding-left: 22px;" ng-bind-html="Homework_details.submitted_details"></p>
		                                        					</div>
		                                        				</div>
		                                        				<!-- right content area -->
		                                        				<div class="col-md-4" style="border-left: 1px solid;">
		                                        					<div class="row">
		                                        						<div class="col-md-12" style="padding-top: 10px;">
		                                        							<b><?php echo lang('lpl_assignment_type') ?> : </b>{{Homework_details.content_type}}
		                                        						</div>
		                                        					</div>
		                                        					<div class="row">
		                                        						<div class="col-md-12" style="padding-top: 10px;">
		                                        							<b><?php echo lang('assignment_title') ?> : </b>{{Homework_details.title}}
		                                        						</div>
		                                        					</div>
		                                        					
		                                        					<div class="row">
		                                        						<div class="col-md-12" style="padding-top: 10px;">
		                                        							<b><?php echo lang('total_marks') ?> : </b>{{Homework_details.total_marks}}
		                                        						</div>
		                                        						<div class="col-md-12" style="padding-top: 10px;">
		                                        							<b><?php echo lang('due_date') ?> : </b>{{Homework_details.due_date}}
		                                        						</div>
		                                        					</div>
		                                        					<br>
		                                        					<div class="row" ng-if="Homework_details.total_marks != '0' ">
		                                        						<div class="col-md-12">
		                                        							<div class="form-group">
		                                        								<label class="control-label"><?php echo lang('obtained_marks') ?></label>
		                                        								<input type="number" class="form-control" ng-model="Homework_details.obtained_marks" min="0" max="{{Homework_details.total_marks}}" required>
		                                        							</div>
		                                        						</div>

		                                        					</div>

		                                        					<div class="row">
		                                        						<div class="col-md-12">
		                                        							<div class="form-group">
		                                        								<textarea class="form-control" ng-model="Homework_details.remarks" rows="5" placeholder="Remarks" required></textarea>
		                                        							</div>
		                                        						</div>
		                                        					</div>
		                                        					<div class="row pull-right">
		                                        						<button type="submit" class="btn btn-primary">
		                                        							<?php echo lang('lpl_submit') ?>
		                                        						</button>
		                                        					</div>
		                                        				</div>
		                                        			</div>
		                                        		</div>
		                                        	</div>
		                                        </div>
		                                    </div><br>


		                                    <div class="container">
		                                    	<h3 style="text-align: center; font-size: 21px; font-family: none; text-decoration: underline;">Comments</h3>
		                                    	<div class="row">
		                                    		<div class="col-md-12">
		                                    			<div class="card mb-0 bg-light col-md-12" style="background-color: white !important; border: 1px solid #e3e3e3; box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);">
		                                    				<div class="col-md-12">
		                                    					<div class="container" ng-repeat="comentHom in HomComments">
		                                    						<div class="container black_comments1" ng-if="comentHom.user_id!=comentHom.sender_id">
		                                    							<div class="row">
		                                    								<div class="col-md-9" style="padding-top: 10px;">
		                                    									<img src="<?php echo base_url(); ?>uploads/user/{{comentHom.avatar}}" alt="user-img" style="width: 40px;" class="img-circle"> <b>{{comentHom.name}}</b>
		                                    								</div>

		                                    							</div>

		                                    							<div class="row" style="padding-top: 8px;" ng-show="comentHom.files[0] != '' ">
		                                    								<div style="padding-bottom: 10px;">
		                                    									<b style="padding-left: 4px;"><?php echo lang('lbl_attachments') ?> :</b>
		                                    								</div>
		                                    							</div>

		                                    							<div class="row" style="overflow-wrap: anywhere;">
		                                    								<ul>
		                                    									<div  ng-repeat="cmnt_file1 in comentHom.files">
		                                    										<li ng-if="['jpg','jpeg','png','gif','PNG'].includes(cmnt_file1.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" data-lightbox="image-attach">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/{{cmnt_file1}}" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" data-lightbox="image-attach">{{cmnt_file1}}</a>
		                                    										</li>
		                                    										<li ng-if="['pdf'].includes(cmnt_file1.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/pdf_icon.png" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">{{cmnt_file1}}</a>
		                                    										</li>
		                                    										<li ng-if="['doc','docx'].includes(cmnt_file1.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/doc_icon.png" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">{{cmnt_file1}}</a>
		                                    										</li>
		                                    										<li ng-if="['xlsx'].includes(cmnt_file1.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/excel_icon.jpg" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">{{cmnt_file}}</a>
		                                    										</li>
		                                    										<li ng-if="['ppt','pptx'].includes(cmnt_file1.split('.').pop().toLowerCase())" style="height: 33px; width: 30px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/ppt_icon.png" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">{{cmnt_file1}}</a>
		                                    										</li>
		                                    									</div>                                         					
		                                    								</ul>   
		                                    							</div>
		                                    							<div class="row">
		                                    								<b style="padding-left: 4px;"><?php echo lang('crumb_messages') ?> :</b>
		                                    							</div>
		                                    							<p class="card-text" ng-bind-html="comentHom.comment_body" style="padding-left: 18px;"></p>
		                                    						</div>
		                                    						<div class="container black_comments" ng-if="comentHom.user_id==comentHom.sender_id">
		                                    							<div class="row">
		                                    								<div class="col-md-9" style="padding-top: 10px;">
		                                    									<img src="<?php echo base_url(); ?>uploads/user/{{comentHom.avatar}}" alt="user-img" style="width: 40px;" class="img-circle"> <b>{{comentHom.name}}</b>
		                                    								</div>

		                                    							</div>

		                                    							<div class="row" style="padding-top: 8px;" ng-show="comentHom.files[0] != '' ">
		                                    								<div style="padding-bottom: 10px;">
		                                    									<b style="padding-left: 4px;"><?php echo lang('lbl_attachments') ?> :</b>
		                                    								</div>
		                                    							</div>

		                                    							<div class="row" style="overflow-wrap: anywhere;">
		                                    								<ul>
		                                    									<div  ng-repeat="cmnt_file1 in comentHom.files">
		                                    										<li ng-if="['jpg','jpeg','png','gif','PNG'].includes(cmnt_file1.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" data-lightbox="image-attach">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/{{cmnt_file1}}" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" data-lightbox="image-attach">{{cmnt_file1}}</a>
		                                    										</li>
		                                    										<li ng-if="['pdf'].includes(cmnt_file1.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/pdf_icon.png" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">{{cmnt_file1}}</a>
		                                    										</li>
		                                    										<li ng-if="['doc','docx'].includes(cmnt_file1.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/doc_icon.png" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">{{cmnt_file1}}</a>
		                                    										</li>
		                                    										<li ng-if="['xlsx'].includes(cmnt_file1.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/excel_icon.jpg" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">{{cmnt_file}}</a>
		                                    										</li>
		                                    										<li ng-if="['ppt','pptx'].includes(cmnt_file1.split('.').pop().toLowerCase())" style="height: 33px; width: 30px;">
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">
		                                    												<img src="<?php echo base_url(); ?>uploads/study_material/icons/ppt_icon.png" style="height: 33px; width: 30px;">
		                                    											</a>
		                                    											<a href="<?php echo base_url() ?>uploads/study_material/{{cmnt_file1}}" target="_blank">{{cmnt_file1}}</a>
		                                    										</li>
		                                    									</div>                                         					
		                                    								</ul>   
		                                    							</div>
		                                    							<div class="row">
		                                    								<b style="padding-left: 4px;"><?php echo lang('crumb_messages') ?> :</b>
		                                    							</div>
		                                    							<p class="card-text" ng-bind-html="comentHom.comment_body" style="padding-left: 18px;"></p>
		                                    						</div>
		                                    						<hr>
		                                    					</div>
		                                    					<!-- Comments assignmnet div start here -->
		                                    					<div class="container">
		                                    						<div ng-controller="commentsController" id="CommentsDIVHom" style="display: none; margin-bottom: 5px; margin-top: 14px;">
		                                    							<div class="row">
		                                    								<div class="col-md-12">
		                                    									<div class="form-group">
		                                    										<label class="control-label"><?php echo lang('crumb_messages') ?></label>
		                                    										<textarea class="textarea_editor1 form-control" rows="5" placeholder="" ></textarea>
		                                    									</div>
		                                    								</div>
		                                    							</div>
		                                    							<div class="row">
		                                    								<div class="col-md-12">
		                                    									<div class="form-group">
		                                    										<label class="control-label"><?php echo lang('upload_content') ?></label>
		                                    										<div class="dropzone" id="my-awesome-dropzone_message_hom" dropzone="DropzoneConfigMessageHom"></div>
		                                    									</div>
		                                    								</div>
		                                    							</div>
		                                    							<div class="alert" id="message_alert" style="display: none"></div>
		                                    							<div class="text-center">
		                                    								<button type="button" name="{{Homework_details.submit_id}}" class="btn btn-primary" id="send_message_hom">
		                                    									<i class="fa fa-comments"></i> Send
		                                    								</button>
		                                    							</div><br>
		                                    						</div>
		                                    					</div>
		                                    					<!-- Comments assignmnet div Ends here -->
		                                    				</div>		                                    		
		                                    			</div>
		                                    		</div>
		                                    	</div>
		                                    </div>
		                                    

		                                    <div class="row pull-right" style="margin-top: 12px;">
		                                    	<div style="margin-right: 8px">
		                                    		<button type="button"  onclick="showCommentsHome()" class="btn btn-primary" style="margin-right: 4px; width: 55px;">
		                                    			<i class="fa fa-comments-o"></i>
		                                    		</button>
		                                    		<button type="button" ng-click="reset_homeworkMarkValue" class="btn btn-default"
		                                    		data-dismiss="modal"><?php echo lang('lbl_close') ?>
		                                    	</button>
		                                    </div>
		                                </div>
		                            </div>
		                        </form>
		                        </div>
		                    </div>
		                </div>
		            </div>
		            <!-- homework view model -->
					<!-- end Controller -->
				</div>
			</div>

<?php include(APPPATH . "views/inc/footer.php"); ?>

<script type="text/javascript">
	function showComments() {
    var x = document.getElementById("CommentsDIV");
    if (x.style.display === "none") {
      x.style.display = "block";
    } else {
      x.style.display = "none";
    }
  }
  function showCommentsView() {
    var x = document.getElementById("CommentsDIVView");
    if (x.style.display === "none") {
      x.style.display = "block";
    } else {
      x.style.display = "none";
    }
  }
  function showCommentsHome() {
    var x = document.getElementById("CommentsDIVHom");
    if (x.style.display === "none") {
      x.style.display = "block";
    } else {
      x.style.display = "none";
    }
  }
  function showCommentsHomeView() {
    var x = document.getElementById("CommentsDIVHomView");
    if (x.style.display === "none") {
      x.style.display = "block";
    } else {
      x.style.display = "none";
    }
  }
</script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.textarea_editor').wysihtml5();
		$('.textarea_editor1').wysihtml5();
		$('.textarea_editor2').wysihtml5();
		$('.textarea_editor3').wysihtml5();
	})
</script>