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
<div>
    
    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid" ng-controller="downloadParentController" ng-init="initSubjectsForStudent();initAssignmentsForStudents();initHomeworkForStudents();">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?php echo lang('lbl_download'); ?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                    <ol class="breadcrumb">
                        <li><a href="#"><?php echo lang('lbl_dashboard'); ?></a></li>
                        <li><a href="#"><?php echo lang('study_material'); ?></a></li>
                        <li class="active"><?php echo lang('lbl_download'); ?></li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <div class="hint"><?php echo lang('help_download'); ?>
            </div>
            <div class="row">
            	<div class="col-md-12">
            		<div class="panel panel-primary">
        			<div class="panel-wrapper collapse in" aria-expanded="true">
            				<div class="panel-body">
            					<ul class="nav customtab nav-tabs" role="tablist">
            						<li role="presentation" class="nav-item"><a href="#assignment" class="nav-link active"
            							aria-controls="profile" role="tab"
            							data-toggle="tab"
            							aria-expanded="true"><span
            							class="visible-xs"><i class="fa fa-user"></i></span><span
            							class="hidden-xs"><?php echo lang('assignment_list'); ?></span></a>
            						</li>
            						<li role="presentation" class="nav-item"><a href="#homework" class="nav-link"
            							aria-controls="profile" role="tab"
            							data-toggle="tab"
            							aria-expanded="true"><span
            							class="visible-xs"><i class="fa fa-user"></i></span><span
            							class="hidden-xs"><?php echo lang('lbl_homework'); ?></span></a>
            						</li>
            					</ul>
            					<!--tab content start here-->
            					<div class="tab-content">
            						<div class="tab-pane active" id="assignment">
            							<!--row -->
            							<div class="well" id="assignment_search_filter">
            								<div class="row">
                                                <div class="col-md-3">
                                                	<div class="form-group form-material">
                                                		<label class="control-label"><?php echo lang('lbl_subject'); ?></label>
                                                		<select class="form-control " name="grade-type" ng-model="subject_id" >
                                                			<option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                                                			<option ng-repeat="sub in subjects" value="{{sub.id}}">{{sub.name}}</option>
                                                		</select>
                                                		<div style="color: red" ng-show="subject_error"><?php echo lang('lbl_field_required') ?></div>
                                                	</div>
                                                </div>
                                            </div>
                                            <div class="row">
                                            	<div class="col-md-12">
                                            		<button class="btn btn-sm btn-primary" ng-click="filterForStudentAssignment(subject_id)"><?php echo lang('search'); ?></button>
                                            	</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                        	<div class="col-md-12 text-danger" ng-show="study.materials2.length == 0"><?php echo lang('no_record'); ?></div>
                                        	<!-- <div class="table-responsive col-md-12" ng-show="study.materials.length > 0">
                                        		<table class="table table-striped table-bordered">
                                        			<thead>
                                        				<tr>
                                        					<th><?php echo lang('material_title'); ?></th>
                                        					<th><?php echo lang('material_type'); ?></th>
                                        					<th><?php echo lang('lbl_date'); ?></th>
                                        					<th><?php echo lang('due_date'); ?></th>
                                        					<th><?php echo lang('lbl_class'); ?></th>
                                        					<th><?php echo lang('lbl_batch'); ?></th>
                                        					<th><?php echo lang('lbl_subject'); ?></th>
                                        					<th><?php echo lang('uploaded_by'); ?></th>
                                        					<th><?php echo lang('lbl_status'); ?></th>
                                        					<th style="text-align: center;"><?php echo lang('lbl_action'); ?></th>
                                        				</tr>
                                        			</thead>
                                        			<tbody>
                                        				<tr ng-repeat="matd in study.materials2">
                                        					<td>{{matd.title}}</td>
                                        					<td>{{matd.content_type}}</td>
                                        					<td>{{matd.published_date}}</td>
                                        					<td>{{matd.due_date}}</td>
                                        					<td>{{matd.class_name}}</td>
                                        					<td>{{matd.section}}</td>
                                        					<td>{{matd.subject_name}}</td>
                                        					<td>{{matd.teacher_name}}</td>
                                        					<td ng-if="matd.status=='Submitted'" class="text text-success">{{matd.status}}</td>
                                        					<td ng-if="matd.status=='Due'" class="text text-danger">{{matd.status}}</td>
                                        					<td><button type="button" class="btn btn-success btn-circle" data-toggle="modal" data-target="#details" ng-click="details_set1(matd)"><i
                                                                    class="fa  fa-eye "></i></button></td>
                                        				</tr>
                                        			</tbody>
                                        		</table>
                                        	</div> -->
                                        	<div class="col-md-12" ng-repeat="matd in study.materials2">
                                        		<div class="card mb-0 bg-light col-md-12" style="background-color: white !important; margin-top: 10px; border: 1px solid #e3e3e3; box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);">
                                        		<div class="card-body">
                                        			<div class="row">
                                        				<div class="col-md-8"><img src="<?php echo base_url(); ?>uploads/user/{{matd.avatar}}" alt="user-img" style="width: 40px;" class="img-circle"> <b>{{matd.teacher_name}}</b>
                                        					<div class="row">
                                        						<div class="col-md-4" style="padding-top: 10px;">
                                        							<b>Date : </b>{{matd.published_date}}
                                        						</div>
                                        						<div class="col-md-4" style="padding-top: 10px;">
                                        							<b>Due Date : </b>{{matd.due_date}}
                                        						</div>
                                        						<div class="col-md-4" style="padding-top: 10px;">
                                        							<b>Subject : </b>{{matd.subject_name}}
                                        						</div>
                                        					</div>
                                        					<div class="row">
                                        						<div class="col-md-4" style="padding-top: 10px;">
                                        							<b>Type : </b>{{matd.content_type}}
                                        						</div>
                                        						<div class="col-md-4" style="padding-top: 10px;">
                                        							<b>Class : </b>{{matd.class_name}}
                                        						</div>
                                        						<div class="row col-md-4" style="padding-top: 10px;">
                                        							<b>Status :</b><button style="margin-left: 10px; height: 33px; margin-top: -7px;" ng-if="matd.status=='Submitted'" class="btn btn-success btn-xs">{{matd.status}}</button><button style="margin-left: 10px; height: 33px; margin-top: -7px;" ng-if="matd.status=='Due'" class="btn btn-danger btn-xs">{{matd.status}}</button>
                                        						</div>
                                        					</div>
                                        					<div class="row" ng-if="matd.total_marks != 0">
                                        						<div class="col-md-4" style="padding-top: 10px;">
                                        							<b>Total Marks : </b>{{matd.total_marks}}
                                        						</div>
                                        						<div class="row col-md-8" style="padding-top: 10px;">
                                        							<b>Obtained Marks : </b>
                                                                    <button style="margin-left: 10px; height: 33px; margin-top: -4px;" ng-if="matd.obtained_marks=='Submit Your Assignment First'" class="btn btn-danger btn-xs">{{matd.obtained_marks}}</button>
                                                                    <button style="margin-left: 10px; height: 33px; margin-top: -4px;" ng-if="matd.obtained_marks=='Waiting'" class="btn btn-warning btn-xs">&nbsp;{{matd.obtained_marks}}</button>
                                                                    <button style="margin-left: 10px; height: 33px; margin-top: -4px;" ng-if="matd.obtained_marks!='Waiting' && matd.obtained_marks != 'Submit Your Assignment First'" class="btn btn-success btn-xs">&nbsp;{{matd.obtained_marks}}</button>
                                        						</div>
                                        					</div>
                                        					<h4 class="card-title">{{matd.title}}</h4>
                                        					<p class="card-text" ng-bind-html="matd.details"></p>
                                        				</div>
                                        				<div class="col-md-4" style="border-left: 1px solid;">
                                        					<div style="padding-bottom: 10px;">
                                        						<b style="padding-left: 4px;"> Attachments</b>
                                        					</div>
                                        					<ul>
                                        						<div  ng-repeat="file in matd.files">
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
                                        					</ul>   
                                        				</div>
                                        			</div>
                                        			<div style="margin-top: 15px;">
                                        				<button type="button" class="btn btn-success" ng-hide="matd.status=='Submitted'" data-toggle="modal" id="submitBtn" data-target="#openAssignment" ng-click="details_set1(matd); (details);">Submit</button>
                                        			</div>
                                        		</div>
                                        	</div>
                                        	</div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="homework">
            							<!--row -->
            							<div class="well" id="homework_search_filter">
            								<div class="row">
                                                <div class="col-md-3">
                                                	<div class="form-group form-material">
                                                		<label class="control-label"><?php echo lang('lbl_subject'); ?></label>
                                                		<select class="form-control " name="grade-type" ng-model="subject_id" >
                                                			<option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                                                			<option ng-repeat="sub in subjects" value="{{sub.id}}">{{sub.name}}</option>
                                                		</select>
                                                		<div style="color: red" ng-show="subject_error"><?php echo lang('lbl_field_required') ?></div>
                                                	</div>
                                                </div>
                                            </div>
                                            <div class="row">
                                            	<div class="col-md-12">
                                            		<button class="btn btn-sm btn-primary" ng-click="filterForStudentHomework(subject_id)"><?php echo lang('search'); ?></button>
                                            	</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                        	<div class="col-md-12 text-danger" ng-show="study.materials1.length == 0"><?php echo lang('no_record'); ?>
                                        	</div>
                                        	<!-- <div class="table-responsive col-md-12" ng-show="study.materials.length > 0">
                                        		<table class="table table-striped table-bordered">
                                        			<thead>
                                        				<tr>
                                        					<th><?php echo lang('material_title'); ?></th>
                                        					<th><?php echo lang('material_type'); ?></th>
                                        					<th><?php echo lang('lbl_date'); ?></th>
                                        					<th><?php echo lang('due_date'); ?></th>
                                        					<th><?php echo lang('lbl_class'); ?></th>
                                        					<th><?php echo lang('lbl_batch'); ?></th>
                                        					<th><?php echo lang('lbl_subject'); ?></th>
                                        					<th><?php echo lang('uploaded_by'); ?></th>
                                        					<th><?php echo lang('lbl_status'); ?></th>
                                        					<th style="text-align: center;"><?php echo lang('lbl_action'); ?></th>
                                        				</tr>
                                        			</thead>
                                        			<tbody>
                                        				<tr ng-repeat="matr in study.materials1">
                                        					<td>{{matr.title}}</td>
                                        					<td>{{matr.content_type}}</td>
                                        					<td>{{matr.published_date}}</td>
                                        					<td>{{matr.due_date}}</td>
                                        					<td>{{matr.class_name}}</td>
                                        					<td>{{matr.section}}</td>
                                        					<td>{{matr.subject_name}}</td>
                                        					<td>{{matr.teacher_name}}</td>
                                        					<td ng-if="matr.status=='Submitted'" class="text text-success">{{matr.status}}</td>
                                        					<td ng-if="matr.status=='Due'" class="text text-danger">{{matr.status}}</td>
                                        					<td><button type="button" class="btn btn-success btn-circle" data-toggle="modal" data-target="#detailsHomework" ng-click="details_set2(matr)"><i
                                                                    class="fa  fa-eye "></i></button></td>
                                        				</tr>
                                        			</tbody>
                                        		</table>
                                        	</div> -->

                                        	<div class="col-md-12" ng-repeat="matr in study.materials1">
                                        		<div class="card mb-0 bg-light col-md-12" style="background-color: white !important; margin-top: 10px; border: 1px solid #e3e3e3; box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);">
                                        		<div class="card-body">
                                        			<div class="row">
                                        				<div class="col-md-8"><img src="<?php echo base_url(); ?>uploads/user/{{matr.avatar}}" alt="user-img" style="width: 40px;" class="img-circle"> <b>{{matr.teacher_name}}</b>
                                        					<div class="row">
                                        						<div class="col-md-4" style="padding-top: 10px;">
                                        							<b>Date : </b>{{matr.published_date}}
                                        						</div>
                                        						<div class="col-md-4" style="padding-top: 10px;">
                                        							<b>Due Date : </b>{{matr.due_date}}
                                        						</div>
                                        						<div class="col-md-4" style="padding-top: 10px;">
                                        							<b>Subject : </b>{{matr.subject_name}}
                                        						</div>
                                        					</div>
                                        					<div class="row">
                                        						<div class="col-md-4" style="padding-top: 10px;">
                                        							<b>Type : </b>{{matr.content_type}}
                                        						</div>
                                        						<div class="col-md-4" style="padding-top: 10px;">
                                        							<b>Class : </b>{{matr.class_name}}
                                        						</div>
                                        						<div class="row col-md-4" style="padding-top: 10px;">
                                        							<b>Status :</b><button style="margin-left: 10px; height: 33px;" ng-if="matr.status=='Submitted'" class="btn btn-success btn-xs">{{matr.status}}</button><button style="margin-left: 10px; height: 33px;" ng-if="matr.status=='Due'" class="btn btn-danger btn-xs">{{matr.status}}</button>
                                        						</div>
                                        					</div>
                                        					<div class="row" ng-if="matr.total_marks != 0">
                                        						<div class="col-md-4" style="padding-top: 10px;">
                                        							<b>Total Marks : </b>{{matr.total_marks}}
                                        						</div>
                                        						<div class="row col-md-8" style="padding-top: 10px;">
                                        							<b>Obtained Marks : </b><button style="margin-left: 10px; height: 33px; margin-top: -4px;" ng-if="matr.obtained_marks=='Submit Your Assignment First'" class="btn btn-danger btn-xs">{{matr.obtained_marks}}</button><button style="margin-left: 10px; height: 33px; margin-top: -4px;" ng-if="matr.obtained_marks=='Waiting'" class="btn btn-warning btn-xs">&nbsp;{{matr.obtained_marks}}</button><button style="margin-left: 10px; height: 33px; margin-top: -4px;" ng-if="matr.obtained_marks!='Waiting' && matr.obtained_marks != 'Submit Your Assignment First'" class="btn btn-success btn-xs">&nbsp;{{matr.obtained_marks}}</button>
                                        						</div>
                                        					</div>
                                        					<h4 class="card-title">{{matr.title}}</h4>
                                        					<p class="card-text" ng-bind-html="matr.details"></p>
                                        				</div>
                                        				<div class="col-md-4" style="border-left: 1px solid;">
                                        					<div style="padding-bottom: 10px;">
                                        						<b style="padding-left: 4px;"> Attachments</b>
                                        					</div>
                                        					<ul>
                                        						<div  ng-repeat="file in matr.files">
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
                                        					</ul>   
                                        				</div>
                                        			</div>
                                        			<div style="margin-top: 15px;">
                                        				<button type="button" class="btn btn-success" ng-hide="matr.status=='Submitted'" data-toggle="modal" data-target="#openHomework" ng-click="details_set2(matr);showMaterial(details);">Submit</button>
                                        			</div>
                                        		</div>
                                        	</div>
                                        	</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="modal fade" id="details" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content" style="width: 98%;">
                                            <div class="panel panel-primary">
                                                <div class="panel-heading">{{details.title}} - {{details.content_type}}</div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-6"><b>{{details.subject_name}}</b></div>
                                                        <div class="col-md-6 text-right">{{details.uploaded_time}}</div>

                                                    </div>
                                                    <div class="row" style="margin-top: 15px;margin-bottom:10px">
                                                        <div class="col-md-12">
                                                            <span ng-bind-html="details.details"></span>
                                                        </div>

                                                    </div>

                                                    <div class="row" style="margin-top: 10px" ng-show="details.files[0].length > 0">
                                                        <div class="col-md-12">

                                                            <ul >
                                                                <div  ng-repeat="file in details.files">
                                                                    <li ng-if="['jpg','jpeg','png','gif'].includes(file.split('.').pop().toLowerCase())"><a href="<?php echo base_url() ?>uploads/study_material/{{file}}" data-lightbox="image-attach">{{file}}</a></li>
                                                                    <li ng-if="!['jpg','jpeg','png','gif'].includes(file.split('.').pop().toLowerCase())"><a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a></li>
                                                                    
                                                                </div>
                                                            </ul>   

                                                        </div>

                                                    </div>


                                                    <div class="row pull-right">
                                                        <div style="margin-right: 8px">
                                                            <button type="button" class="btn btn-default"
                                                            data-dismiss="modal"><?php echo lang('lbl_close'); ?>
                                                        </button>
                                                    </div>
                                                    <div>
                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#openAssignment" ng-click="showMaterial(details)">
                                                            <?php echo lang('open'); ?>
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->

                            <!-- <div class="modal fade" id="detailsHomework" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content" style="width: 98%;">
                                            <div class="panel panel-primary">
                                                <div class="panel-heading">{{details.title}} - {{details.content_type}}</div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-6"><b>{{details.subject_name}}</b></div>
                                                        <div class="col-md-6 text-right">{{details.uploaded_time}}</div>
                                                    </div>
                                                    <div class="row" style="margin-top: 15px;margin-bottom:10px">
                                                        <div class="col-md-12">
                                                            <span ng-bind-html="details.details"></span>
                                                        </div>

                                                    </div>

                                                    <div class="row" style="margin-top: 10px" ng-show="details.files[0].length > 0">
                                                        <div class="col-md-12">

                                                            <ul >
                                                                <div  ng-repeat="file in details.files">
                                                                    <li ng-if="['jpg','jpeg','png','gif'].includes(file.split('.').pop().toLowerCase())"><a href="<?php echo base_url() ?>uploads/study_material/{{file}}" data-lightbox="image-attach">{{file}}</a></li>
                                                                    <li ng-if="!['jpg','jpeg','png','gif'].includes(file.split('.').pop().toLowerCase())"><a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a></li>
                                                                    
                                                                </div>
                                                            </ul>   

                                                        </div>

                                                    </div>


                                                    <div class="row pull-right">
                                                        <div style="margin-right: 8px">
                                                            <button type="button" class="btn btn-default"
                                                            data-dismiss="modal"><?php echo lang('lbl_close'); ?>
                                                        </button>
                                                    </div>
                                                    <div>
                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#openHomework" ng-click="showMaterial(details)">
                                                            <?php echo lang('open'); ?>
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                             
                        	<div class="modal fade" id="openAssignment" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">{{details.title}} - {{details.content_type}}</div>
                                    	</div>
                                    	<input type="hidden" name="teacher_id" value="details.teacher_id">
                                    	<div class="panel-body">
	                                    	<div class="row">
		                                        <div class="col-md-6"><img src="<?php echo base_url(); ?>uploads/user/{{details.teacher_avatar}}" alt="user-img" style="width: 40px;" class="img-circle"> <b>{{details.teacher_name}}</b></div>
		                                        <div class="col-md-6 text-right"><b>Due Date : </b>{{details.due_date}}</div>
		                                    </div>
		                                    <div class="row">
		                                    	<div class="col-md-12">
		                                    		<p ng-bind-html="details.details"></p>
		                                    	</div>
		                                    </div>
		                                    <div class="row" style="margin-top: 10px" ng-show="details.files[0].length > 0">
                                                <div class="col-md-12">
                                                	<div style="padding-bottom: 10px;">
                                                		<i class="fa fa-paperclip" aria-hidden="true"></i><b style="padding-left: 4px;"> Attachments</b>
                                                	</div>
                                                	
                                                    <ul>
                                                        <div ng-if="details.storage_type == 1">
                                                            <div  ng-repeat="file in details.files">
                                                                <li ng-if="['jpg','JPG','jpeg','JPEG','png','PNG','gif','GIF','tif','TIF','tiff','TIFF','bmp','BMP'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
                                                                    <a href="<?php echo base_url() ?>uploads/study_material/{{file}}" ng-click="download();" data-lightbox="image-attach">
                                                                        <img src="<?php echo base_url(); ?>uploads/study_material/{{file}}" style="height: 33px; width: 30px;">
                                                                    </a>
                                                                    <a href="<?php echo base_url() ?>uploads/study_material/{{file}}" ng-click="download();" data-lightbox="image-attach">{{file}}</a>
                                                                </li>
                                                                <li ng-if="['pdf','PDF'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
                                                                    <a href="<?php echo base_url() ?>uploads/study_material/{{file}}" ng-click="download();">
                                                                        <img src="<?php echo base_url(); ?>uploads/study_material/icons/pdf_icon.png" style="height: 33px; width: 30px;">
                                                                    </a>
                                                                    <a href="<?php echo base_url() ?>uploads/study_material/{{file}}" ng-click="download();">{{file}}</a>
                                                                </li>
                                                                <li ng-if="['doc','DOC','docx','DOCX'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
                                                                    <a href="<?php echo base_url() ?>uploads/study_material/{{file}}" ng-click="download();">
                                                                        <img src="<?php echo base_url(); ?>uploads/study_material/icons/doc_icon.png" style="height: 33px; width: 30px;">
                                                                    </a>
                                                                    <a href="<?php echo base_url() ?>uploads/study_material/{{file}}" ng-click="download();">{{file}}</a>
                                                                </li>
                                                                <li ng-if="['xlsm','XLSM','xlsx','XLSX','xlt','XLT','xls','XLS'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
                                                                    <a href="<?php echo base_url() ?>uploads/study_material/{{file}}" ng-click="download();">
                                                                        <img src="<?php echo base_url(); ?>uploads/study_material/icons/excel_icon.jpg" style="height: 33px; width: 30px;">
                                                                    </a>
                                                                    <a href="<?php echo base_url() ?>uploads/study_material/{{file}}" ng-click="download();">{{file}}</a>
                                                                </li>
                                                                <li ng-if="['ppt','PPT','pptx','PPTX'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
                                                                    <a href="<?php echo base_url() ?>uploads/study_material/{{file}}" ng-click="download();">
                                                                        <img src="<?php echo base_url(); ?>uploads/study_material/icons/ppt_icon.png" style="height: 33px; width: 30px;">
                                                                    </a>
                                                                    <a href="<?php echo base_url() ?>uploads/study_material/{{file}}" ng-click="download();">{{file}}</a>
                                                                </li>
                                                            </div>
                                                        </div>
                                                    </ul> 
                                                    
                                                    
                                                    
                                                    <!-- google drive -->
                                                    <div ng-if="details.storage_type == 2" style="overflow-x: auto;">
                                                        <div style="width:100%; overflow-x: auto;">
                                                              <table>
                                                              <tr style="overflow-x: auto;" class="test">
                                                                  
                                                              <!--<td ng-repeat="thumbx_link in details.thumbnail_links" style="padding: 5px">-->
                                                                <td ng-repeat="(key,f) in details.files" style="width: 149px;">
                                                                    
                                                                    <div ng-if="details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'mp4' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'MP4' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'avi' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'AVI' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'flv' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'FLV' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'wmv' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'WMV' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'mov' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'MOV' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'webm'  || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'WEBM' ">
                                                                        <img class="client-img img-thumbnail" src="assets/googledrive_guide/images/videoicon.png" style="height:150px; width:320px; border-radius:8px;" />
                                                                    </div> 
                                                                    
                                                                    <div ng-if="details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'pdf' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'PDF' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'doc' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'DOC' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'docx' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'DOCX' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'ppt' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'PPT' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'pptx' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'PPTX' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'png' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'PNG' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'tif' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'TIF' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'tiff' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'TIFF' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'bmp' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'BMP' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'jpg' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'JPG' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'jpeg' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'JPEG' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'gif' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'GIF' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'eps'  || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'EPS' ">
                                                                        <img class="client-img img-thumbnail" ng-src="{{details.thumbnail_links[key]}}" style="height:150px; width:320px; border-radius:8px;" />
                                                                    </div>
                                                                        <div style="text-align: center !important; width: 141px; overflow: hidden; text-overflow: ellipsis;">
                                                                            <a  href="{{details.filesurl[key]}}" target="_blank">{{details.file_names[key]}}</a> 
                                                                            <!--<button class="btn btn-link text-danger" ng-click="removeFile(f.name)" type="button">Remove</button>-->
                                                                        </div>
                                                                    
                                                                </td>
                                                              </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <!-- google drive -->
                                                    
                                                    
                                                    
                                                </div>
                                            </div>
                                            <div class="row">
		                                    	<div class="col-md-12">
		                                    		<p>{{details.material_details}}</p>
		                                    	</div>
		                                    </div><hr>
		                                    <div class="row">
		                                    	<div class="col-md-12">
		                                    		<textarea class="textarea_editor form-control" rows="5" placeholder="Enter Text" ></textarea>
		                                    	</div>
		                                    </div>
		                                    <div class="row">
		                                    	<div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('upload_content') ?></label>
                                                        <div
                                                        class="dropzone"
                                                        id="my-awesome-dropzone_submit_ass" dropzone="dropzoneConfigStdAss"></div>
                                                    </div>
                                                </div>
		                                    </div>
		                                    <div class="alert" id="assignmentAns" style="display: none"></div>
		                                    <div class="row pull-right">
                                                <div style="margin-right: 8px">
                                                    <button type="button" class="btn btn-default"
                                                    data-dismiss="modal"><?php echo lang('lbl_close'); ?>
                                                </button>
                                            	</div>
	                                            <div>
	                                                <button type="button" class="btn btn-primary" id="submitAssignment">
	                                                    Send
	                                                </button>
	                                            </div>
		                                	</div>
                                		</div>
                            		</div>
                        		</div>
                        	</div>
                        	<div class="modal fade" id="openHomework" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">{{details.title}} - {{details.content_type}}</div>   
                                    	</div>
                                    	<div class="panel-body">
	                                    	<div class="row">
		                                        <div class="col-md-6"><img src="<?php echo base_url(); ?>uploads/user/{{details.teacher_avatar}}" alt="user-img" style="width: 40px;" class="img-circle"> <b>{{details.teacher_name}}</b></div>
		                                        <div class="col-md-6 text-right"><b>Due Date : </b>{{details.due_date}}</div>
		                                    </div>
		                                    <div class="row">
		                                    	<div class="col-md-12">
		                                    		<p ng-bind-html="details.details"></p>
		                                    	</div>
		                                    </div>
		                                    <div class="row" style="margin-top: 10px" ng-show="details.files[0].length > 0">
                                                <div class="col-md-12">
                                                	<div style="padding-bottom: 10px;">
                                                		<i class="fa fa-paperclip" aria-hidden="true"></i><b style="padding-left: 4px;"> Attachments</b>
                                                	</div>
                                                	
                                                    <ul>
                                                        <div ng-if="details.storage_type == 1">
                                                            <div  ng-repeat="file in details.files">
                                                                <li ng-if="['jpg','JPG','jpeg','JPEG','png','PNG','gif','GIF','tif','TIF','tiff','TIFF','bmp','BMP'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
                                                                    <a href="<?php echo base_url() ?>uploads/study_material/{{file}}" ng-click="download();" data-lightbox="image-attach">
                                                                        <img src="<?php echo base_url(); ?>uploads/study_material/{{file}}" style="height: 33px; width: 30px;">
                                                                    </a>
                                                                    <a href="<?php echo base_url() ?>uploads/study_material/{{file}}" ng-click="download();" data-lightbox="image-attach">{{file}}</a>
                                                                </li>
                                                                <li ng-if="['pdf','PDF'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
                                                                    <a href="<?php echo base_url() ?>uploads/study_material/{{file}}" ng-click="download();">
                                                                        <img src="<?php echo base_url(); ?>uploads/study_material/icons/pdf_icon.png" style="height: 33px; width: 30px;">
                                                                    </a>
                                                                    <a href="<?php echo base_url() ?>uploads/study_material/{{file}}" ng-click="download();">{{file}}</a>
                                                                </li>
                                                                <li ng-if="['doc','DOC','docx','DOCX'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
                                                                    <a href="<?php echo base_url() ?>uploads/study_material/{{file}}" ng-click="download();">
                                                                        <img src="<?php echo base_url(); ?>uploads/study_material/icons/doc_icon.png" style="height: 33px; width: 30px;">
                                                                    </a>
                                                                    <a href="<?php echo base_url() ?>uploads/study_material/{{file}}" ng-click="download();">{{file}}</a>
                                                                </li>
                                                                <li ng-if="['xlsm','XLSM','xlsx','XLSX','xlt','XLT','xls','XLS'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
                                                                    <a href="<?php echo base_url() ?>uploads/study_material/{{file}}" ng-click="download();">
                                                                        <img src="<?php echo base_url(); ?>uploads/study_material/icons/excel_icon.jpg" style="height: 33px; width: 30px;">
                                                                    </a>
                                                                    <a href="<?php echo base_url() ?>uploads/study_material/{{file}}" ng-click="download();">{{file}}</a>
                                                                </li>
                                                                <li ng-if="['ppt','PPT','pptx','PPTX'].includes(file.split('.').pop().toLowerCase())" style="margin-bottom: 5px;">
                                                                    <a href="<?php echo base_url() ?>uploads/study_material/{{file}}" ng-click="download();">
                                                                        <img src="<?php echo base_url(); ?>uploads/study_material/icons/ppt_icon.png" style="height: 33px; width: 30px;">
                                                                    </a>
                                                                    <a href="<?php echo base_url() ?>uploads/study_material/{{file}}" ng-click="download();">{{file}}</a>
                                                                </li>
                                                            </div>
                                                        </div>
                                                    </ul>  
                                                    
                                                    <!-- google drive -->
                                                    <div ng-if="details.storage_type == 2" style="overflow-x: auto;">
                                                        <div style="width:100%; overflow-x: auto;">
                                                              <table>
                                                              <tr style="overflow-x: auto;" class="test">
                                                                  
                                                              <!--<td ng-repeat="thumbx_link in details.thumbnail_links" style="padding: 5px">-->
                                                                <td ng-repeat="(key,f) in details.files" style="width: 149px;">
                                                                    
                                                                    <div ng-if="details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'mp4' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'MP4' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'avi' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'AVI' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'flv' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'FLV' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'wmv' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'WMV' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'mov' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'MOV' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'webm'  || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'WEBM' ">
                                                                        <img class="client-img img-thumbnail" src="assets/googledrive_guide/images/videoicon.png" style="height:150px; width:320px; border-radius:8px;" />
                                                                    </div> 
                                                                    
                                                                    <div ng-if="details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'pdf' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'PDF' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'doc' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'DOC' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'docx' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'DOCX' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'ppt' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'PPT' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'pptx' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'PPTX' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'png' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'PNG' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'tif' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'TIF' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'tiff' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'TIFF' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'bmp' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'BMP' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'jpg' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'JPG' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'jpeg' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'JPEG' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'gif' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'GIF' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'eps'  || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'EPS' ">
                                                                        <img class="client-img img-thumbnail" ng-src="{{details.thumbnail_links[key]}}" style="height:150px; width:320px; border-radius:8px;" />
                                                                    </div>
                                                                        <div style="text-align: center !important; width: 141px; overflow: hidden; text-overflow: ellipsis;">
                                                                            <a  href="{{details.filesurl[key]}}" target="_blank">{{details.file_names[key]}}</a> 
                                                                            <!--<button class="btn btn-link text-danger" ng-click="removeFile(f.name)" type="button">Remove</button>-->
                                                                        </div>
                                                                    
                                                                </td>
                                                              </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <!-- google drive -->
                                                    
                                                    
                                                    
                                                </div>
                                            </div>
                                            <div class="row">
		                                    	<div class="col-md-12">
		                                    		<p>{{details.material_details}}</p>
		                                    	</div>
		                                    </div><hr>
		                                    <div class="row">
		                                    	<div class="col-md-12">
		                                    		<textarea class="textarea_editor1 form-control" rows="5" placeholder="Enter Text" ></textarea>
		                                    	</div>
		                                    </div>
		                                    <div class="row">
		                                    	<div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('upload_content') ?></label>
                                                        <div
                                                        class="dropzone"
                                                        id="my-awesome-dropzone_submit_hom" dropzone="dropzoneConfigStdHom"></div>
                                                    </div>
                                                </div>
		                                    </div>
                                            <div class="alert" id="homeworkAns" style="display: none"></div>
		                                    <div class="row pull-right">
                                                <div style="margin-right: 8px">
                                                    <button type="button" class="btn btn-default"
                                                    data-dismiss="modal"><?php echo lang('lbl_close'); ?>
                                                </button>
                                            	</div>
	                                            <div>
	                                                <button type="button" class="btn btn-primary" id="submitHomework">
	                                                    Send
	                                                </button>
	                                            </div>
		                                	</div>
                                		</div>
                            		</div>
                        		</div>
                        	</div>
                            <!--tab content end here-->
                        </div>
                    </div>
                    <!--/panel wrapper-->
                </div>
                <!--/panel-->
            </div>
        </div>
        </div>
    </div>
</div>
<?php include(APPPATH . "views/inc/footer.php"); ?>
<script type="text/javascript">
    $(document).ready(function(){
      $('.textarea_editor').wysihtml5();
      $('.textarea_editor1').wysihtml5();
  })
</script>