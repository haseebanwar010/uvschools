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
        <div class="container-fluid" ng-controller="uploadController" ng-init = "init();getMaterials();getNewMaterials();getHomework()">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?php echo lang('lbl_upload') ?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                    <ol class="breadcrumb">
                        <li><a href="#"><?php echo lang('lbl_dashboard') ?></a></li>
                        <li><a href="#"><?php echo lang('study_material') ?></a></li>
                        <li class="active"><?php echo lang('lbl_upload') ?></li>


                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <div class="hint"><?php echo lang('help_upload'); ?></div>
            <!-- Page Content start here -->
            <!--.row-->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">

                        <div class="panel-wrapper collapse in" aria-expanded="true">
                            <div class="panel-body">

                                <ul class="nav customtab nav-tabs" role="tablist">
                                    <li role="presentation" class="nav-item"><a href="#general" class="nav-link active"
                                        aria-controls="profile" role="tab"
                                        data-toggle="tab"
                                        aria-expanded="true"><span
                                        class="visible-xs"><i class="fa fa-user"></i></span><span
                                        class="hidden-xs"><?php echo lang('material_list') ?></span></a>
                                    </li>
                                    <li role="presentation" class="nav-item"><a href="#assignments" class="nav-link"
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
                                   <!--  <ul class="ml-auto">
                                        <li class="nav-item navbar-nav ">
                                            <button class="fcbtn btn  btn-primary " data-toggle="modal"
                                            data-target="#upload" ng-click="resetModal()"><?php echo lang('lbl_upload') ?>
                                        </button>
                                    </li>
                                </ul> -->
                                </ul>

                            <!--tab content start here-->

                            <div class="tab-content">
                                <div class="tab-pane active" id="general">
                                    <div class="well" id="download_search_filter">
                                            <div class="row">
                                                <!--row -->
                                                <div class="col-md-3">
                                                    <div class="form-group form-material">
                                                        <label class="control-label"><?php echo lang('lbl_class'); ?></label>
                                                        <select class="form-control " name="grade-type" required="" ng-model="study.class" ng-change="getSections_upload()">
                                                            <option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                                                            <option ng-repeat="classi in study.classes" value="{{classi.id}}">{{classi.name}}</option>
                                                        </select>
                                                        <div style="color: red" ng-show="class_error"><?php echo lang('lbl_field_required') ?></div>
                                                    </div>
                                                </div>
                                                <!--/span-->
                                                <div class="col-md-3">
                                                    <div class="form-group form-material">
                                                        <label class="control-label"><?php echo lang('lbl_batch'); ?></label>

                                                        <select class="form-control " name="grade-type" required="" ng-model="study.section" ng-change="getSubjects_upload()">
                                                            <option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                                                            <!-- ng-show="study.batches.length > 1" -->
                                                            <option ng-show="study.batches.length > 1" value="all"><?php echo lang('option_all'); ?></option>
                                                            <option ng-repeat="batch in study.batches" value="{{batch.id}}">{{batch.name}}</option>

                                                        </select>
                                                        <div style="color: red" ng-show="section_error"><?php echo lang('lbl_field_required') ?></div>

                                                    </div>
                                                </div>
                                                <!--/span-->
                                                <div class="col-md-2">
                                                    <div class="form-group form-material">
                                                        <label class="control-label"><?php echo lang('lbl_subject'); ?></label>
                                                        <select class="form-control " name="grade-type" ng-model="study.subject" ng-change="subjectChanged_upload()">
                                                            <option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                                                            <option ng-show="study.subjects.length > 1" value="all"><?php echo lang('option_all'); ?></option>
                                                            <option ng-repeat="subject in study.subjects" value="{{subject.id}}">{{subject.name}}</option>

                                                        </select>
                                                        <div style="color: red" ng-show="subject_error"><?php echo lang('lbl_field_required') ?></div>
                                                    </div>
                                                </div>
                                                <!--/span-->
                                                <div class="col-md-2">
                                                    <div class="form-group form-material">
                                                        <label class="control-label"><?php echo lang('material_type'); ?></label>
                                                        <select class="form-control " name="grade-type" ng-model="study.type" ng-change="typeChanged_upload()">
                                                            <option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                                                            <option value="Assignment"><?php echo lang('lbl_assignment'); ?></option>
                                                            <option value="Homework"><?php echo lang('lbl_homework'); ?></option>
                                                            <option value="Study Material"><?php echo lang('study_material'); ?></option>
                                                            <option value="Classwork"><?php echo lang('lbl_class_work') ?></option>
                                                        </select>
                                                        <div style="color: red" ng-show="type_error"><?php echo lang('lbl_field_required') ?></div>
                                                    </div>
                                                </div>
                                                <!--/span-->
                                               <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_date') ?></label>
                                                        <input type="text" ng-model="study.date" class="form-control mydatepicker-autoclose" placeholder="<?php echo "Select date"; ?>" style="border-left: none;border-right:none;border-top:none;  "/>
                                                        <div style="color: red" ng-show="date_error"><?php echo lang('lbl_field_required') ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col-md-12">
                                                    <button class="btn btn-sm btn-primary" ng-click="filter_upload_material_general()"><?php echo lang('search'); ?></button>
                                                    <button class="btn btn-sm btn-default" ng-click="removeFilter_upload()"><?php echo lang('remove_filters'); ?></button>
                                                </div>

                                            </div>
                                        </div>
                                    <div class="row">
                                        <button class="fcbtn btn  btn-primary" style="margin-bottom: 10px; margin-left: auto; margin-right: 20px;" data-toggle="modal" data-target="#upload" ng-click="resetModal()">Upload                                            </button>
                                        <div class="col-md-12 text-danger" ng-show="study.materials.length == 0"><?php echo lang('no_record'); ?></div>



                                        <div class="table-responsive col-md-12" >
                                            <table id="myTable" class="table color-table" ng-show="study.materials.length > 0">
                                             
                                                <thead>
                                                    <tr>

                                                        <th><?php echo lang('material_title') ?></th>
                                                        <th><?php echo lang('material_type') ?></th>
                                                        <th><?php echo lang('lbl_date') ?></th>
                                                        <th><?php echo lang('lbl_class') ?></th>
                                                        <th><?php echo lang('lbl_batch') ?></th>
                                                        <th><?php echo lang('lbl_subject') ?></th>
                                                        <th><?php echo lang('uploaded_by') ?></th>
                                                        <th><?php echo lang('lbl_action') ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr ng-repeat="mat in study.materials">
                                                                  
                                                        <td>{{mat.title}}</td>
                                                        <td>{{mat.content_type}}</td>
                                                        <td>{{mat.uploaded_time}}</td>
                                                        <td>{{mat.class_name}}</td> 
                                                        <td>
                                                            <!--<div>{{mat}}</div>  -->
                                                            <div ng-if="mat.filterbatch_name=='' ">
                                                                <div ng-if="mat.batch_id.length >= 5"> <?php echo lang('option_all'); ?></div>
                                                                <div ng-if="mat.batch_id.length < 5"> {{mat.batch_name}}</div>
                                                            </div>
                                                            
                                                            <div ng-if="mat.filterbatch_name!='' ">
                                                                <!--<div> {{mat.batch_name}}</div>-->
                                                                <div> {{mat.filterbatch_name}}</div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            
                                                            <div ng-if="mat.filtersubject_name=='' ">
                                                                <div ng-if="mat.subject_code.length >= 15"> <?php echo lang('option_all'); ?></div>
                                                                <div ng-if="mat.subject_code.length < 15"> {{mat.subject_name}}</div>
                                                            </div>
                                                            
                                                            <div ng-if="mat.filtersubject_name!='' ">
                                                                <div>{{mat.filtersubject_name}}</div>
                                                            </div>
                                                            
                                                        </td>
                                                        <td>{{mat.name}}</td>
                                                        <td >
                                                            <button type="button" class="btn btn-success btn-circle" data-toggle="modal"
                                                            data-target="#details" ng-click="details_set(mat)"><i
                                                            class="fa fa-eye "></i></button>
                                                                <button type="button" class="btn btn-info btn-circle"  data-toggle="modal"
                                                                data-target="#editupload" ng-click="editData(mat)"><i
                                                                class="fa  fa-pencil "></i></button>
                                                                <button type="button" class="btn btn-danger btn-circle" data-toggle="modal"
                                                                data-target="#deleteMaterial" ng-click="deleteId(mat.id)"><i
                                                                class="fa  fa-trash-o"></i></button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- assignment tab -->
                                    <div class="tab-pane" id="assignments">
                                        <div class="well" id="download_search_filter">
                                            <div class="row">
                                                <!--row -->
                                                <div class="col-md-3">
                                                    <div class="form-group form-material">
                                                        <label class="control-label"><?php echo lang('lbl_class'); ?></label>
                                                        <select class="form-control " name="grade-type" ng-model="study.class" ng-change="getSections_upload()">
                                                            <option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                                                            <option ng-repeat="classi in study.classes" value="{{classi.id}}">{{classi.name}}</option>
                                                        </select>
                                                        <div style="color: red" ng-show="class_error"><?php echo lang('lbl_field_required') ?></div>
                                                    </div>
                                                </div>
                                                <!--/span-->
                                                <div class="col-md-3">
                                                    <div class="form-group form-material">
                                                        <label class="control-label"><?php echo lang('lbl_batch'); ?></label>

                                                        <select class="form-control " name="grade-type" ng-model="study.section" ng-change="getSubjects_upload()">
                                                            <option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                                                            <!-- <option ng-show="study.batches.length > 1" value="all"><?php echo lang('option_all'); ?></option> -->
                                                            <option ng-repeat="batch in study.batches" value="{{batch.id}}">{{batch.name}}</option>

                                                        </select>
                                                        <div style="color: red" ng-show="section_error"><?php echo lang('lbl_field_required') ?></div>

                                                    </div>
                                                </div>
                                                <!--/span-->
                                                <div class="col-md-2">
                                                    <div class="form-group form-material">
                                                        <label class="control-label"><?php echo lang('lbl_subject'); ?></label>
                                                        <select class="form-control " name="grade-type" ng-model="study.subject" ng-change="subjectChanged_upload()">
                                                            <option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                                                            <option ng-repeat="subject in study.subjects" value="{{subject.id}}">{{subject.name}}</option>

                                                        </select>
                                                        <div style="color: red" ng-show="subject_error"><?php echo lang('lbl_field_required') ?></div>
                                                    </div>
                                                </div>
                                                <!--/span-->
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Date From</label>
                                                        <input type="text" ng-model="study.f_date" required="" class="form-control mydatepicker-autoclose" placeholder="<?php echo "Select date"; ?>" style="border-left: none;border-right:none;border-top:none;  "/>
                                                        <div style="color: red" ng-show="date_error"><?php echo lang('lbl_field_required') ?></div>
                                                    </div>
                                                </div>
                                                <!--/span-->
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Date To</label>
                                                        <input type="text" ng-model="study.t_date" required="" class="form-control mydatepicker-autoclose" placeholder="<?php echo "Select date"; ?>" style="border-left: none;border-right:none;border-top:none;  "/>
                                                        <div style="color: red" ng-show="date_error"><?php echo lang('lbl_field_required') ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col-md-12">
                                                    <button class="btn btn-sm btn-primary" ng-click="assignment_upload()"><?php echo lang('search'); ?></button>
                                                    <button class="btn btn-sm btn-default" ng-click="removeFilter_upload()"><?php echo lang('remove_filters'); ?></button>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <button class="fcbtn btn  btn-primary" style="margin-bottom: 10px; margin-left: auto; margin-right: 20px;" data-toggle="modal"
                                            data-target="#assignment" ng-click="resetModalAssignment()"><?php echo lang('lbl_upload') ?>
                                            </button>
                                            <div class="col-md-12 text-danger" ng-show="study.newMaterials.length == 0"><?php echo lang('no_record'); ?></div>



                                            <div class="table-responsive col-md-12" ng-hide="study.newMaterials.length == 0" >
                                                <table id="myTable" class="table color-table">
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo lang('assignment_title') ?></th>
                                                            <th><?php echo lang('material_type') ?></th>
                                                            <th><?php echo lang('lbl_date') ?></th>
                                                            <th><?php echo lang('due_date') ?></th>
                                                            <th><?php echo lang('lbl_class') ?></th>
                                                            <th><?php echo lang('lbl_batches') ?></th>
                                                            <th><?php echo lang('lbl_subject') ?></th>
                                                            <th><?php echo lang('uploaded_by') ?></th>
                                                            <th><?php echo lang('lbl_action') ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr ng-repeat="mat in study.newMaterials">
                                                            <td>{{mat.title}}</td>
                                                            <td>{{mat.content_type}}</td>
                                                            <td>{{mat.published_date}}</td>
                                                            <td>{{mat.due_date}}</td>
                                                            <td>{{mat.class_name}}</td>
                                                            <td>{{mat.sections}}</td>
                                                            <td>{{mat.subject_name}}</td>
                                                            <td>{{mat.name}}</td> 
                                                            <!-- <td>
                                                                <div ng-show="mat.batch_id == 0"><?php echo lang('option_all') ?></div>
                                                                <div ng-show="mat.batch_id != 0">{{mat.batch_name}}</div>
                                                            </td>
                                                            <td>
                                                                <div ng-show="mat.subject_id == 0"><?php echo lang('option_all') ?></div>
                                                                <div ng-show="mat.subject_id != 0">{{mat.subject_name}}</div>
                                                            </td>
                                                            <td>{{mat.name}}</td> -->
                                                            <td >
                                                                <button type="button" class="btn btn-success btn-circle" data-toggle="modal"
                                                                data-target="#AssignmentDetails" ng-click="details_set(mat)"><i
                                                                class="fa fa-eye "></i></button>
                                                                <button type="button" class="btn btn-info btn-circle"  data-toggle="modal"
                                                                data-target="#edituploadAssignment" ng-click="editDataAssignment(mat)"><i
                                                                class="fa  fa-pencil "></i></button>
                                                                <button type="button" class="btn btn-danger btn-circle" data-toggle="modal"
                                                                data-target="#deleteAssignment" ng-click="deleteId(mat.id)"><i
                                                                class="fa  fa-trash-o"></i></button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>  
                                    </div>
                                    <!-- assignment tab ends here -->

                                    <!-- homework tab starts here -->
                                    <div class="tab-pane" id="homework">
                                        <div class="well" id="download_search_filter">
                                            <div class="row">
                                                <!--row -->
                                                <div class="col-md-3">
                                                    <div class="form-group form-material">
                                                        <label class="control-label"><?php echo lang('lbl_class'); ?></label>
                                                        <select class="form-control " name="grade-type" ng-model="study.class" ng-change="getSections_upload()">
                                                            <option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                                                            <option ng-repeat="classi in study.classes" value="{{classi.id}}">{{classi.name}}</option>
                                                        </select>
                                                        <div style="color: red" ng-show="class_error"><?php echo lang('lbl_field_required') ?></div>
                                                    </div>
                                                </div>
                                                <!--/span-->
                                                <div class="col-md-3">
                                                    <div class="form-group form-material">
                                                        <label class="control-label"><?php echo lang('lbl_batch'); ?></label>

                                                        <select class="form-control " name="grade-type" ng-model="study.section" ng-change="getSubjects_upload()">
                                                            <option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                                                            <!-- <option ng-show="study.batches.length > 1" value="all"><?php echo lang('option_all'); ?></option> -->
                                                            <option ng-repeat="batch in study.batches" value="{{batch.id}}">{{batch.name}}</option>

                                                        </select>
                                                        <div style="color: red" ng-show="section_error"><?php echo lang('lbl_field_required') ?></div>

                                                    </div>
                                                </div>
                                                <!--/span-->
                                                <div class="col-md-2">
                                                    <div class="form-group form-material">
                                                        <label class="control-label"><?php echo lang('lbl_subject'); ?></label>
                                                        <select class="form-control " name="grade-type" ng-model="study.subject" ng-change="subjectChanged_upload()">
                                                            <option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                                                            <option ng-repeat="subject in study.subjects" value="{{subject.id}}">{{subject.name}}</option>

                                                        </select>
                                                        <div style="color: red" ng-show="subject_error"><?php echo lang('lbl_field_required') ?></div>
                                                    </div>
                                                </div>
                                                <!--/span-->
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Date From</label>
                                                        <input type="text" ng-model="study.f_date" class="form-control mydatepicker-autoclose" placeholder="<?php echo "Select date"; ?>" style="border-left: none;border-right:none;border-top:none;  "/>
                                                        <div style="color: red" ng-show="date_error"><?php echo lang('lbl_field_required') ?></div>
                                                    </div>
                                                </div>
                                                <!--/span-->
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Date To</label>
                                                        <input type="text" ng-model="study.t_date" required="" class="form-control mydatepicker-autoclose" placeholder="<?php echo "Select date"; ?>" style="border-left: none;border-right:none;border-top:none;  "/>
                                                        <div style="color: red" ng-show="date_error"><?php echo lang('lbl_field_required') ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col-md-12">
                                                    <button class="btn btn-sm btn-primary" ng-click="homework_upload()"><?php echo lang('search'); ?></button>
                                                    <button class="btn btn-sm btn-default" ng-click="removeFilter_upload()"><?php echo lang('remove_filters'); ?></button>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <button class="fcbtn btn  btn-primary" style="margin-bottom: 10px; margin-left: auto; margin-right: 20px;" data-toggle="modal"
                                            data-target="#homework_modal" ng-click="resetModalHomework()"><?php echo lang('lbl_upload') ?>
                                            </button>
                                            <div class="col-md-12 text-danger" ng-show="study.newHomework.length == 0"><?php echo lang('no_record'); ?></div>



                                            <div class="table-responsive col-md-12" ng-hide="study.newHomework.length == 0">
                                                <table id="myTable" class="table color-table">
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo lang('homeWork_title') ?></th>
                                                            <th><?php echo lang('material_type') ?></th>
                                                            <th><?php echo lang('lbl_date') ?></th>
                                                            <th><?php echo lang('due_date') ?></th>
                                                            <th><?php echo lang('lbl_class') ?></th>
                                                            <th><?php echo lang('lbl_batches') ?></th>
                                                            <th><?php echo lang('lbl_subject') ?></th>
                                                            <th><?php echo lang('uploaded_by') ?></th>
                                                            <th><?php echo lang('lbl_action') ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                       <tr ng-repeat="hom in study.newHomework">
                                                            <td>{{hom.title}}</td>
                                                            <td>{{hom.content_type}}</td>
                                                            <td>{{hom.published_date}}</td>
                                                             <td>{{hom.due_date}}</td>
                                                            <td>{{hom.class_name}}</td>
                                                            <td>{{hom.sections}}</td>
                                                            <td>{{hom.subject_name}}</td>
                                                            <td>{{hom.name}}</td> 
                                                            <!-- <td>
                                                                <div ng-show="mat.batch_id == 0"><?php echo lang('option_all') ?></div>
                                                                <div ng-show="mat.batch_id != 0">{{mat.batch_name}}</div>
                                                            </td>
                                                            <td>
                                                                <div ng-show="mat.subject_id == 0"><?php echo lang('option_all') ?></div>
                                                                <div ng-show="mat.subject_id != 0">{{mat.subject_name}}</div>
                                                            </td>
                                                            <td>{{mat.name}}</td> -->
                                                            <td >
                                                                <button type="button" class="btn btn-success btn-circle" data-toggle="modal"
                                                                data-target="#homeworkDetails" ng-click="details_set(hom)"><i
                                                                class="fa fa-eye "></i></button>
                                                                <button type="button" class="btn btn-info btn-circle"  data-toggle="modal"
                                                                data-target="#edituploadHomework" ng-click="editDataHomework(hom)"><i
                                                                class="fa  fa-pencil "></i></button>
                                                                <button type="button" class="btn btn-danger btn-circle" data-toggle="modal"
                                                                data-target="#deleteHomework" ng-click="deleteId(hom.id)"><i
                                                                class="fa  fa-trash-o"></i></button>
                                                            </td>
                                                        </tr> 
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- homework tab ends here -->
                                </div>
                            <!--tab content end here-->
                        </div>
                            
                            <div class="modal fade" id="upload" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading"><?php echo lang('study_material') ?></div>
                                        <div class="panel-body">


                                            <form name="upload_form">
                                                <div class="form-body">

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label"><?php echo lang('material_title') ?></label>
                                                                <input type="text" id="firstName" ng-model="study1.title"
                                                                class="form-control "
                                                                ></div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('lbl_date') ?></label>
                                                                    <input type="text" ng-model="study1.uploaded_at" class="form-control  mydatepicker-autoclose"
                                                                    ></div>
                                                                </div>

                                                            </div>
                                                            <!--/span-->
                                                            <div class="row">
                                                             <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('material_type') ?></label>
                                                                    <select class="form-control " name="grade-type" ng-model="study1.type" >
                                                                        <option value="" disabled="">--<?php echo lang('lbl_select') ?>--</option>
                                                                        <option value="Assignment"><?php echo lang('lbl_assignment') ?></option>
                                                                        <option value="Homework"><?php echo lang('lbl_homework') ?></option>
                                                                        <option value="Study Material"><?php echo lang('study_material') ?></option>
                                                                        <option value="Classwork"><?php echo lang('lbl_class_work') ?></option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                                                    <select class="form-control " name="grade-type" ng-model="study1.class" ng-change="getSections1()">
                                                                        <option value="" disabled="">--<?php echo lang('lbl_select') ?>--</option>
                                                                        <option ng-repeat="classi in study.classes" value="{{classi.id}}">{{classi.name}}</option>

                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <!--/span-->
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('lbl_batch') ?></label>
                                                                    
                                                                    <select class="form-control " name="grade-type" ng-model="study1.section" ng-change="getSubjects1()">
                                                                        <option value="" disabled="">--<?php echo lang('lbl_select') ?>--</option>
                                                                        <option ng-show="study1.batches.length > 1"value="all"><?php echo lang('option_all') ?></option>
                                                                        <option ng-repeat="batch in study1.batches" value="{{batch.id}}">{{batch.name}}</option>

                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">

                                                                    <label class="control-label"><?php echo lang('lbl_subject') ?></label>
                                                                    <select class="form-control " name="grade-type" ng-model="study1.subject">
                                                                        <option value="" disabled="">--<?php echo lang('lbl_select') ?>--</option>
                                                                        <option ng-show="study1.subjects.length > 1" value="all"><?php echo lang('option_all') ?></option>
                                                                        <option ng-repeat="subject in study1.subjects" value="{{subject.id}}">{{subject.name}}</option>

                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('document_storage');?></label><br>
                                                                    <?php if(file_exists($credentials_fileid) && $enable_gd==1){ ?>
                                                                    <!--Google Drive-->
                                                                        <img src="assets/googledrive_guide/images/gd_logo.png" alt="Google Drive" style="height: 50px;"/>
                                                                    <?php } else { ?>
                                                                    <!--Local Drive-->
                                                                        <img src="assets/googledrive_guide/images/ld_logo.png" alt="Local Drive" style="height: 50px;"/>
                                                                    <?php } ?>
                                                                </div>
                                                        </div>
                                                    </div>
                                                        
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('lbl_details') ?></label>
                                                                    <textarea class="textarea_editor form-control" rows="5" placeholder="" ></textarea>


                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                         <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label"><?php echo lang('upload_content') ?></label>
                                                                <!-- <div class="dropzone" id="my-awesome-dropzone" dropzone="dropzoneConfig"></div> -->
                                                                <div class="dropzone" id="my-awesome-dropzone" dropzone="materialNewDropzoneConfigUpload"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="alert" id="message_alert" style="display: none"></div>
                                                    <!--/span-->
                                                </div>
                                                <div class="row pull-right">
                                                    <div style="margin-right: 8px">
                                                        <button type="button" class="btn btn-default"
                                                        data-dismiss="modal"><?php echo lang('lbl_close') ?>
                                                    </button>
                                                </div>
                                                <div>
                                                    <button type="button" class="btn btn-primary" id="upload_material">
                                                        <?php echo lang('btn_save') ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="modal fade" id="assignment" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading"><?php echo lang('lbl_assignment') ?></div>
                                        <div class="panel-body">


                                            <form name="upload_form">
                                                <div class="form-body">

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label"><?php echo lang('assignment_title') ?></label>
                                                                <input type="text" id="firstName" ng-model="study3.title"
                                                                class="form-control">
                                                                <input type="hidden" ng-init="study3.type='Assignment'"
                                                                class="form-control">
                                                            </div>
                                                        </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('lbl_date') ?></label>
                                                                    <input type="text" ng-model="study3.uploaded_at" class="form-control mydatepicker-autoclose"
                                                                    ></div>
                                                            </div>

                                                            </div>
                                                            <!--/span-->
                                                            <div class="row">
                                                             <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Due Date</label>
                                                                    <input type="text" ng-model="study3.due_date" class="form-control mydatepicker-autoclose-op"
                                                                    ></div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                                                    <select class="form-control " name="grade-type" ng-model="study3.class" ng-change="getSectionsForAssignment()">
                                                                        <option value="" disabled="">--<?php echo lang('lbl_select') ?>--</option>
                                                                        <option ng-repeat="classi in study.classes" value="{{classi.id}}">{{classi.name}}</option>

                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <!--/span-->
                                                        <div class="row">
                                                            <div class="col-md-6" id="select2-section">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('lbl_batch') ?></label>
                                                                    <select class="form-control yasir-ann-select2" style="width: 100%;" multiple="multiple" name="grade-type" ng-model="study3.section" ng-change="getSubjectsForassignments();" id="select2-section">

                                                                        <option ng-repeat="batch in study1.batches" value="{{batch.id}}">{{batch.name}}</option>

                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group" id="select2-subject">
                                                                    <label class="control-label"><?php echo lang('lbl_subject') ?></label>
                                                                    <select class="form-control" ng-change="getStudentsForAss();" name="grade-type" ng-model="study3.subject">
                                                                        <option value="" disabled="">--<?php echo lang('lbl_select') ?>--</option>
                                                                        <option ng-repeat="subject in AssSubjects" value="{{subject.id}}">{{subject.name}}</option>

                                                                    </select>
                                                                </div>
                                                            </div>
                                                            </div>
                                                            <div class="row">
                                                            <div class="col-md-12" id="select2-students">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('menu_students') ?></label>
                                                                    <div class="checkbox checkbox-info">
                                                                        <input id="student_selectall" ng-disabled="AssStudents.length==0 || AssStudents==undefined" type="checkbox" name="studentSelectAll" ng-model="studentSelectAll">
                                                                        <label for="student_selectall">Select All</label>
                                                                    </div>
                                                                    <div id="studentsDiv">
                                                                        <select class="form-control yasir-ann-select2" style="width: 100%;" multiple="multiple" ng-model="study3.students" name="grade-type" id="select2-students">
                                                                            <option ng-repeat="student in AssStudents" value="{{student.id}}">{{student.section_name}}-{{student.name}}</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <script>
                                                                    $(document).ready(function(){
                                                                        $("#student_selectall").click(function(){
                                                                            if($("#student_selectall").is(':checked') ){
                                                                                $("#select2-students > option").select2().prop("selected",true).trigger('change');
                                                                                //$("#studentsDiv").hide();

                                                                            }else{
                                                                                $("#select2-students > option").select2().removeAttr("selected").trigger('change');
                                                                                //$("#studentsDiv").show();
                                                                            }
                                                                        });
                                                                    });
                                                                </script>
                                                            </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <input type="checkbox" id="checkMarks" class="form-check-input">
                                                                    <label class="control-label" style="padding-left: 18px;">Marks</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                       <label class="control-label pull-right">Total Marks</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <input type="number" id="marks" disabled="disabled" ng-model="study3.marks" class="form-control">
                                                                </div>
                                                            </div>
                                                        
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('lbl_details') ?></label>
                                                                    <textarea class="textarea_editor1 form-control" rows="5" placeholder="" ></textarea>


                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('description') ?></label>
                                                                    <textarea class="form-control" ng-model="study3.material_details" id="material_description" rows="5" style="height: 150px;" placeholder="" ></textarea>


                                                                </div>
                                                            </div>
                                                             <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('upload_content') ?></label>
                                                                    <div
                                                                    class="dropzone"
                                                                    id="my-awesome-dropzone_assignment" dropzone="dropzoneConfigAssignment"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <div class="alert" id="message_alert_a" style="display: none"></div>
                                                    <!--/span-->
                                                </div>
                                                <div class="row pull-right">
                                                    <div style="margin-right: 8px">
                                                        <button type="button" class="btn btn-default"
                                                        data-dismiss="modal"><?php echo lang('lbl_close') ?>
                                                    </button>
                                                </div>
                                                <div>
                                                    <button type="button" class="btn btn-primary" id="upload_assignment">
                                                        <?php echo lang('btn_save') ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="homework_modal" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading"><?php echo lang('homework_list') ?></div>
                                        <div class="panel-body">


                                            <form name="upload_form">
                                                <div class="form-body">    
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label"><?php echo lang('homeWork_title') ?></label>
                                                                <input type="text" id="firstName" ng-model="study4.title"
                                                                class="form-control "
                                                                ></div>
                                                                <input type="hidden" ng-init="study4.type='Homework'"
                                                                class="form-control">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Date</label>
                                                                    <input type="text" ng-model="study4.uploaded_at" class="form-control  mydatepicker-autoclose"
                                                                    ></div>
                                                                </div>

                                                            </div>
                                                            <!--/span-->
                                                            <div class="row">
                                                                 <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Due Date</label>
                                                                        <input type="text" ng-model="study4.due_date" class="form-control  mydatepicker-autoclose">
                                                                    </div>
                                                                </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                                                    <select class="form-control " name="grade-type" ng-model="study4.class" ng-change="getSectionsForHomework()">
                                                                        <option value="" disabled="">--<?php echo lang('lbl_select') ?>--</option>
                                                                        <option ng-repeat="classi in study.classes" value="{{classi.id}}">{{classi.name}}</option>

                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <!--/span-->
                                                        <div class="row">
                                                            <div class="col-md-6" id="select2-section_homework">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('lbl_batch') ?></label>
                                                                    <select class="form-control yasir-ann-select2" style="width: 100%;" multiple="multiple" name="grade-type" ng-model="study4.section" ng-change="getSubjectsForHomework();" id="select2-section_homework">

                                                                        <option ng-repeat="batch in study1.batches" value="{{batch.id}}">{{batch.name}}</option>

                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6" id="select2-subject_homework">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('lbl_subject') ?></label>
                                                                    <select class="form-control" ng-change="getStudentsForHomework();" name="grade-type" ng-model="study4.subject">
                                                                        <option value="" disabled="">--<?php echo lang('lbl_select') ?>--</option>
                                                                        <option ng-repeat="subject in AssSubjects" value="{{subject.id}}">{{subject.name}}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12" id="select2-students_homework">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('menu_students') ?></label>
                                                                    <div class="checkbox checkbox-info">
                                                                        <input id="student_selectall_homework" ng-disabled="AssStudents.length==0 || AssStudents==undefined" type="checkbox" name="studentSelectAllHomework" ng-model="studentSelectAllHomework">
                                                                        <label for="student_selectallHomework">Select All</label>
                                                                    </div>
                                                                    <select class="form-control yasir-ann-select2" style="width: 100%;" multiple="multiple" ng-model="study4.students" name="grade-type" id="select2-students_homework">
                                                                        <option ng-repeat="student in AssStudents" value="{{student.id}}">{{student.section_name}}-{{student.name}}</option>
                                                                    </select>
                                                                    <script>
                                                                        $(document).ready(function(){
                                                                            $("#student_selectall_homework").click(function(){
                                                                                if($("#student_selectall_homework").is(':checked') ){
                                                                                    $("#select2-students_homework > option").select2().prop("selected",true).trigger('change');
                                                                                //$("#studentsDiv").hide();

                                                                            }else{
                                                                                $("#select2-students_homework > option").select2().removeAttr("selected").trigger('change');
                                                                                //$("#studentsDiv").show();
                                                                            }
                                                                        });
                                                                        });
                                                                    </script>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <input type="checkbox" id="checkMarks1" class="form-check-input">
                                                                <label class="control-label" style="padding-left: 18px;">Marks</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                   <label class="control-label pull-right">Total Marks</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" id="marks1" disabled="disabled" ng-model="study4.marks" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('description') ?></label>
                                                                    <textarea class="textarea_editor3 form-control" ng-model="study4.details" rows="5" style="height: 150px;" placeholder="" ></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('description') ?></label>
                                                                    <textarea class="form-control" ng-model="study4.material_details" rows="5" style="height: 150px;" placeholder="" ></textarea>


                                                                </div>
                                                            </div>
                                                             <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('upload_content') ?></label>
                                                                    <div
                                                                    class="dropzone"
                                                                    id="my-awesome-dropzone_homework" dropzone="dropzoneConfigHomework"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <div class="alert" id="message_alert_h" style="display: none"></div>
                                                    <!--/span-->
                                                </div>
                                                <div class="row pull-right">
                                                    <div style="margin-right: 8px">
                                                        <button type="button" class="btn btn-default"
                                                        data-dismiss="modal"><?php echo lang('lbl_close') ?>
                                                    </button>
                                                </div>
                                                <div>
                                                    <button type="button" class="btn btn-primary" id="upload_homework">
                                                        <?php echo lang('btn_save') ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                <div class="modal fade" id="editupload" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="panel panel-primary">
                            <div class="panel-heading"><?php echo lang('edit_study_material') ?></div>
                            <div class="panel-body">


                                <form >
                                    <div class="form-body">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('material_title') ?></label>
                                                    <input type="text" id="firstName" ng-model="study2.title"
                                                    class="form-control "
                                                    ></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_date') ?></label>
                                                        <input type="text" ng-model="study2.uploaded_at" class="form-control  mydatepicker-autoclose"
                                                        ></div>
                                                    </div>

                                                </div>
                                            <!--/span-->
                                            <div class="row">
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('material_type') ?></label>
                                                    <select class="form-control " name="grade-type" ng-model="study2.type" >
                                                        <option value="" disabled="">--<?php echo lang('lbl_select') ?>--</option>
                                                        <option value="Assignment"><?php echo lang('lbl_assignment') ?></option>
                                                        <option value="Homework"><?php echo lang('lbl_homework') ?></option>
                                                        <option value="Study Material"><?php echo lang('study_material') ?></option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                                    <select class="form-control " name="grade-type" ng-model="study2.class" ng-change="getSectionsForEdit()">
                                                        <option value="" disabled="">--<?php echo lang('lbl_select') ?>--</option>
                                                        <option ng-repeat="classi in study.classes" value="{{classi.id}}">{{classi.name}}</option>

                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <!--/span-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_batch') ?></label>
                                                    <select class="form-control " name="grade-type" ng-model="study2.section" ng-change="getSubjectsForEdit()">
                                                        <option value="" disabled="">--<?php echo lang('lbl_select') ?>--</option>
                                                        <option ng-show="study2.batches.length > 1" value="all"><?php echo lang('option_all') ?></option>
                                                        <option ng-repeat="batch in study2.batches" value="{{batch.id}}">{{batch.name}}</option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_subject') ?></label>
                                                    <select class="form-control " name="grade-type" ng-model="study2.subject">
                                                        <option value="" disabled="">--<?php echo lang('lbl_select') ?>--</option>
                                                        <option ng-show="study2.subjects.length > 1" value="all"><?php echo lang('option_all') ?></option>
                                                        <option ng-repeat="subject in study2.subjects" value="{{subject.id}}">{{subject.name}}</option>

                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('document_storage');?></label><br>
                                                        <?php if(file_exists($credentials_fileid) && $enable_gd==1){ ?>
                                                        <!--Google Drive-->
                                                            <img src="assets/googledrive_guide/images/gd_logo.png" alt="Google Drive" style="height: 50px;"/>
                                                        <?php } else { ?>
                                                        <!--Local Drive-->
                                                            <img src="assets/googledrive_guide/images/ld_logo.png" alt="Local Drive" style="height: 50px;"/>
                                                        <?php } ?>
                                                    </div>
                                            </div>
                                        </div>
                                        
                                         <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_details') ?></label>
                                                    <textarea class="textarea_editor2 form-control" rows="5" placeholder="" ></textarea>


                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <b>Attachments</b><hr>
                                                <!-- <p ng-repeat="f in study2.old_files"><a  href="uploads/study_material/{{f.name}}" download="">{{f.name}}</a> <button class="btn btn-link text-danger" ng-click="removeFile(f.name)" type="button">Remove</button></p> -->
                                                <div ng-if="study2.storage_type == 1">
                                                    <!--<p ng-repeat="f in study2.old_files">-->
                                                    <!--    <a  href="uploads/study_material/{{f.name}}" download="">{{study2.file_names[$index]}}</a> -->
                                                    <!--    <button class="btn btn-link text-danger" ng-click="removeFile(f.name)" type="button">Remove</button>-->
                                                    <!--</p>-->
                                                    
                                                    
                                                <div style="width:100%; overflow-x: auto;">
                                                    <table>
                                                        <tr style="overflow-x: auto;" class="test">
                                                          
                                                      <!--<td ng-repeat="thumbx_link in details.thumbnail_links" style="padding: 5px">-->
                                                        <td ng-repeat="(key,file) in study2.old_files" style="width: 149px;">
                                                            
                                                            <div ng-if="['jpg','JPG','jpeg','JPEG','png','PNG','gif','GIF','tif','TIF','tiff','TIFF','bmp','BMP'].includes(file.name.split('.').pop().toLowerCase())">
                                                                <!--<img class="client-img img-thumbnail" src="assets/googledrive_guide/images/image_icon.png" style="height:150px; width:150px; border-radius:8px;" />-->
                                                                <img class="client-img img-thumbnail" src="uploads/study_material/{{file.name}}" style="height:150px; width:150px; border-radius:8px;" />
                                                            </div>
                                                            
                                                            <div ng-if="['pdf','PDF'].includes(file.name.split('.').pop().toLowerCase())">
                                                                <img class="client-img img-thumbnail" src="assets/googledrive_guide/images/adobe_icon.png" style="height:150px; width:150px; border-radius:8px;" />
                                                            </div>
                                                            
                                                            <div ng-if="['doc','DOC','docx','DOCX'].includes(file.name.split('.').pop().toLowerCase())">
                                                                <img class="client-img img-thumbnail" src="assets/googledrive_guide/images/docx_icon.png" style="height:150px; width:150px; border-radius:8px;" />
                                                            </div>
                                                                                                            
                                                            <div ng-if="['ppt','PPT','pptx','PPTX'].includes(file.name.split('.').pop().toLowerCase())">
                                                                <img class="client-img img-thumbnail" src="assets/googledrive_guide/images/ppt_icon.jpg" style="height:150px; width:150px; border-radius:8px;" />
                                                            </div>
                                                                                                                                                            
                                                            <div ng-if="['xlsm','XLSM','xlsx','XLSX','xlt','XLT','xls','XLS'].includes(file.name.split('.').pop().toLowerCase())">
                                                                <img class="client-img img-thumbnail" src="assets/googledrive_guide/images/excel_icon.jpg" style="height:150px; width:150px; border-radius:8px;" />
                                                            </div>
                                                                                                                                                                                                            
                                                            <div ng-if="['mp4','MP4','avi','AVI','flv','FLV','wmv','WMV','mov','MOV','webm','WEBM'].includes(file.name.split('.').pop().toLowerCase())">
                                                                <img class="client-img img-thumbnail" src="assets/googledrive_guide/images/videoicon.png" style="height:150px; width:150px; border-radius:8px;" />
                                                            </div>
                                                        
                                                            <div style="text-align: center !important; width: 141px; overflow: hidden; text-overflow: ellipsis;">
                                                                <!--<a  href="<?php echo base_url() ?>uploads/study_material/{{file.name}}" target="_blank">{{study2.file_names[key]}}</a> -->
                                                                
                                                                <a  href="<?php echo base_url() ?>uploads/study_material/{{file.name}}" target="_blank" ng-if="study2.file_names[key]!=''">{{study2.file_names[key]}}</a>
                                                                <a  href="<?php echo base_url() ?>uploads/study_material/{{file.name}}" target="_blank" ng-if="study2.file_names==''">{{file.name}}</a>
                                                                
                                                                <!--<a  href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank" ng-if="details.file_names[key]!=''">{{details.file_names[key]}}</a> -->
                                                                <!--<a  href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank" ng-if="details.file_names==''">{{file}}</a>-->
                                                                <button class="btn btn-link text-danger" ng-click="removeFile(file.name)" type="button">Remove</button>
                                                            </div>
                                                            
                                                        </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                    
                                                    
                                                </div>
                                                <div ng-if="study2.storage_type == 2" >
                                                    <div style="width:100%; overflow-x: auto;">
                                                          <table>
                                                          <tr style="overflow-x: auto;" class="test">
                                                              
                                                          <!--<td ng-repeat="thumbx_link in study2.thumbnail_links" style="padding: 5px">-->
                                                            <td ng-repeat="(key,f) in study2.old_files" style="width: 149px;">
                                                                
                                                                
                                                                <div ng-if="study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'mp4' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'MP4' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'avi' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'AVI' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'flv' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'FLV' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'wmv' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'WMV' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'mov' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'MOV' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'webm'  || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'WEBM' ">
                                                                    <img class="client-img img-thumbnail" src="assets/googledrive_guide/images/videoicon.png" style="height:150px; width:320px; border-radius:8px;" />
                                                                </div>
                                                
                                                                <div ng-if="study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'pdf' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'PDF' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'doc' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'DOC' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'docx' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'DOCX' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'ppt' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'PPT' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'pptx' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'PPTX' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'png' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'PNG' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'tif' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'TIF' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'tiff' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'TIFF' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'bmp' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'BMP' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'jpg' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'JPG' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'jpeg' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'JPEG' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'gif' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'GIF' || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'eps'  || study2.file_names[key].substr(study2.file_names[key].lastIndexOf('.') + 1) == 'EPS' ">
                                                                    <img class="client-img img-thumbnail" ng-src="{{study2.thumbnail_links[key]}}" style="height:150px; width:320px; border-radius:8px;" />
                                                                </div>
                                                                <div style="text-align: center !important; width: 141px; overflow: hidden; text-overflow: ellipsis;">
                                                                    <a  href="{{study2.filesurl[key]}}" target="_blank">{{study2.file_names[key]}}</a>
                                                                    <button class="btn btn-link text-danger" ng-click="removeFile(f.name)" type="button">Remove</button>
                                                                </div>
                                                                
                                                            </td>
                                                          </tr>
                                                        </table>
                                                    </div>
                                                 </div>
                                            </div>
                                           <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang('upload_content') ?></label>
                                                <div
                                                class="dropzone"
                                                id="my-awesome-dropzone3" dropzone="materialEditDropzoneConfigUpload"></div>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="alert" id="edit_alert" style="display: none"></div>

                                        <!--/span-->
                                    </div>
                                    <div class="row pull-right">
                                        <div style="margin-right: 8px">
                                            <button type="button" class="btn btn-default"
                                            data-dismiss="modal"><?php echo lang('lbl_close') ?>
                                        </button>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-primary" id="update_material_btn">
                                            <?php echo lang('btn_update') ?>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="edituploadAssignment" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="panel panel-primary">
                            <div class="panel-heading"><?php echo lang('edit_study_material') ?></div>
                            <div class="panel-body">

                                    <form name="upload_form">
                                        <div class="form-body">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('assignment_title') ?></label>
                                                        <input type="text" ng-model="study7.title"
                                                        class="form-control">
                                                        <input type="hidden" ng-init="study7.type='Assignment'"
                                                        class="form-control">
                                                    </div>
                                                </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_date') ?></label>
                                                            <input type="text" ng-model="study7.uploaded_at" class="form-control mydatepicker-autoclose"
                                                            ></div>
                                                    </div>

                                                    </div>
                                                    <!--/span-->
                                                    <div class="row">
                                                     <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Due Date</label>
                                                            <input type="text" ng-model="study7.due_date" class="form-control mydatepicker-autoclose-op"
                                                            ></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                                            <select class="form-control " name="grade-type" ng-model="study7.class" ng-change="getSectionsForAssignmentEdit()">
                                                                <option value="" disabled="">--<?php echo lang('lbl_select') ?>--</option>
                                                                <option ng-repeat="classi in study.classes" value="{{classi.id}}">{{classi.name}}</option>

                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>
                                                <!--/span-->
                                                <div class="row">
                                                    <div class="col-md-6" id="select2-section-edit-assignmnet">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_batch') ?></label>
                                                            <select class="form-control yasir-ann-select2" style="width: 100%;" multiple="multiple" name="grade-type" ng-model="study7.section" ng-change="getSubjectsForassignmentsEdit();" id="select2-section-edit-assignmnet" value={{study7.section}}>

                                                                <option ng-repeat="batch in study7.batches" value="{{batch.id}}">{{batch.name}}</option>

                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" id="select2-subject-edit-assignmnet">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_subject') ?></label>
                                                            <select class="form-control" ng-change="getStudentsForAssEdit();" name="grade-type" ng-model="study7.subject">
                                                                <option value="" disabled="">--<?php echo lang('lbl_select') ?>--</option>
                                                                <option ng-repeat="subject in study7.subj" value="{{subject.id}}">{{subject.name}}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    </div>
                                                    <div class="row">
                                                    <div class="col-md-12" id="select2-students-edit-assignment">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('menu_students') ?></label>
                                                            <div class="checkbox checkbox-info">
                                                                <input id="students_selectall" ng-disabled="students.length==0 || students==undefined" type="checkbox" name="studentsSelectAll" ng-model="studentsSelectAll">
                                                                <label for="students_selectall">Select All</label>
                                                            </div>
                                                            <select class="form-control yasir-ann-select2" style="width: 100%;" multiple="multiple" ng-model="study7.students" name="grade-type" id="select2-students-edit-assignment" value="{{study7.students}}">
                                                                <option ng-repeat="student in students" value="{{student.id}}">{{student.section_name}}-{{student.name}}</option>
                                                            </select>
                                                            <script>
                                                                $(document).ready(function(){
                                                                    $("#students_selectall").click(function(){
                                                                        if($("#students_selectall").is(':checked') ){
                                                                            $("#select2-students-edit-assignment > option").select2().prop("selected",true).trigger('change');
                                                                                //$("#studentsDiv").hide();

                                                                            }else{
                                                                                $("#select2-students-edit-assignment > option").select2().removeAttr("selected").trigger('change');
                                                                                //$("#studentsDiv").show();
                                                                            }
                                                                        });
                                                                });
                                                            </script>
                                                        </div>
                                                    </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <input type="checkbox" class="form-check-input" id="checkMarks2">
                                                            <label class="control-label" style="padding-left: 18px;">Marks</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                               <label class="control-label pull-right">Total Marks</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="number" id="marks2" disabled="disabled" ng-model="study7.marks" class="form-control">
                                                        </div>
                                                    </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_details') ?></label>
                                                            <textarea class="textarea_editor7 form-control" rows="5" placeholder="" ></textarea>


                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('description') ?></label>
                                                            <textarea class="form-control" ng-model="study7.material_details" id="material_description" rows="5" style="height: 150px;" placeholder="" ></textarea>


                                                        </div>
                                                        <div class="col-md-6">
                                                        </div>
                                                    </div>
                                                     <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <p ng-repeat="f in study7.old_files"><a  href="uploads/study_material/{{f.name}}" download="">{{f.name}}</a> <button class="btn btn-link text-danger" ng-click="removeFileAssignment(f.name)" type="button">Remove</button></p>
                                                            </div>
                                                            <label class="control-label"><?php echo lang('upload_content') ?></label>
                                                            <div
                                                            class="dropzone"
                                                            id="my-awesome-dropzone" dropzone="dropzoneConfigAssignmentEdit"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <div class="alert" id="message_alert_a_e" style="display: none"></div>
                                            <!--/span-->
                                        </div>
                                        <div class="row pull-right">
                                            <div style="margin-right: 8px">
                                                <button type="button" class="btn btn-default"
                                                data-dismiss="modal"><?php echo lang('lbl_close') ?>
                                            </button>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-primary" id="upload_assignment_edit">
                                                <?php echo lang('btn_save') ?>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="edituploadHomework" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="panel panel-primary">
                            <div class="panel-heading"><?php echo lang('edit_study_material') ?></div>
                            <div class="panel-body">


                                    <form name="upload_form">
                                        <div class="form-body">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('assignment_title') ?></label>
                                                        <input type="text" ng-model="study8.title"
                                                        class="form-control">
                                                        <input type="hidden" ng-init="study8.type='Assignment'"
                                                        class="form-control">
                                                    </div>
                                                </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_date') ?></label>
                                                            <input type="text" ng-model="study8.uploaded_at" class="form-control mydatepicker-autoclose"
                                                            ></div>
                                                    </div>

                                                    </div>
                                                    <!--/span-->
                                                    <div class="row">
                                                     <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Due Date</label>
                                                            <input type="text" ng-model="study8.due_date" class="form-control mydatepicker-autoclose-op"
                                                            ></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                                            <select class="form-control " name="grade-type" ng-model="study8.class" ng-change="getSectionsForHomeworkEdit()">
                                                                <option value="" disabled="">--<?php echo lang('lbl_select') ?>--</option>
                                                                <option ng-repeat="classi in study.classes" value="{{classi.id}}">{{classi.name}}</option>

                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>
                                                <!--/span-->
                                                <div class="row">
                                                    <div class="col-md-6" id="select2-section-edit-homework">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_batch') ?></label>
                                                            <select class="form-control yasir-ann-select2" style="width: 100%;" multiple="multiple" name="grade-type" ng-model="study8.section" ng-change="getSubjectsForhomeworkEdit();" id="select2-section-edit-homework" value={{study8.section}}>

                                                                <option ng-repeat="batch in study8.batches" value="{{batch.id}}">{{batch.name}}</option>

                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" id="select2-subject-edit-homework">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_subject') ?></label>
                                                            <select class="form-control" name="grade-type" ng-change="getStudentsForHomEdit();" ng-model="study8.subject">
                                                                <option value="" disabled="">--<?php echo lang('lbl_select') ?>--</option>
                                                                <option ng-repeat="subject in study8.subj" value="{{subject.id}}">{{subject.name}}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    </div>
                                                    <div class="row">
                                                    <div class="col-md-12" id="select2-students-edit-homework">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('menu_students') ?></label>
                                                            <div class="checkbox checkbox-info">
                                                                <input id="students_selectall_homework" ng-disabled="students.length==0 || students==undefined" type="checkbox" name="studentsSelectAllHomework" ng-model="studentsSelectAllHomework">
                                                                <label for="students_selectallHomework">Select All</label>
                                                            </div>
                                                            <select class="form-control yasir-ann-select2" style="width: 100%;" multiple="multiple" ng-model="study8.students" name="grade-type" id="select2-students-edit-homework" value="{{study8.students}}">
                                                                <option ng-repeat="student in students" value="{{student.id}}">{{student.section_name}}-{{student.name}}</option>
                                                            </select>
                                                            <script>
                                                                $(document).ready(function(){
                                                                    $("#students_selectall_homework").click(function(){
                                                                        if($("#students_selectall_homework").is(':checked') ){
                                                                            $("#select2-students-edit-homework > option").select2().prop("selected",true).trigger('change');
                                                                                //$("#studentsDiv").hide();

                                                                            }else{
                                                                                $("#select2-students-edit-homework > option").select2().removeAttr("selected").trigger('change');
                                                                                //$("#studentsDiv").show();
                                                                            }
                                                                        });
                                                                });
                                                            </script>
                                                        </div>
                                                    </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <input type="checkbox" id="checkMarks3" class="form-check-input">
                                                            <label class="control-label" style="padding-left: 18px;">Marks</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                               <label class="control-label pull-right">Total Marks</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="number" id="marks3" disabled="disabled" ng-model="study8.marks" class="form-control">
                                                        </div>
                                                    </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_details') ?></label>
                                                            <textarea class="textarea_editor8 form-control" rows="5" placeholder="" ></textarea>


                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('description') ?></label>
                                                            <textarea class="form-control" ng-model="study8.material_details" id="material_description" rows="5" style="height: 150px;" placeholder="" ></textarea>


                                                        </div>
                                                        <div class="col-md-6">
                                                        </div>
                                                    </div>
                                                     <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <p ng-repeat="f in study8.old_files"><a  href="uploads/study_material/{{f.name}}" download="">{{f.name}}</a> <button class="btn btn-link text-danger" ng-click="removeFileHomework(f.name)" type="button">Remove</button></p>
                                                            </div>
                                                            <label class="control-label"><?php echo lang('upload_content') ?></label>
                                                            <div
                                                            class="dropzone"
                                                            id="my-awesome-dropzone" dropzone="dropzoneConfigHomeworkEdit"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <div class="alert" id="message_alert_h_e" style="display: none"></div>
                                            <!--/span-->
                                        </div>
                                        <div class="row pull-right">
                                            <div style="margin-right: 8px">
                                                <button type="button" class="btn btn-default"
                                                data-dismiss="modal"><?php echo lang('lbl_close') ?>
                                            </button>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-primary" id="upload_homework_edit">
                                                <?php echo lang('btn_save') ?>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="details" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
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
                                    
                                <div ng-if="details.storage_type == 1">
                                   <!--<div  ng-repeat="(key,file) in details.files">-->
                                   <!--     <li ng-if="['jpg','jpeg','png','gif'].includes(file.split('.').pop().toLowerCase())"><a href="<?php echo base_url() ?>uploads/study_material/{{file}}" data-lightbox="image-attach">{{details.file_names[key]}}</a></li>-->

                                   <!--     <li ng-if="!['jpg','jpeg','png','gif'].includes(file.split('.').pop().toLowerCase())"><a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{details.file_names[key]}}</a></li>-->
                                   <!-- </div>-->
                                    
                                    <div style="width:100%; overflow-x: auto;">
                                          <table>
                                              <!--<div>{{details.files}}</div>-->
                                          <tr style="overflow-x: auto;" class="test">
                                              
                                          <!--<td ng-repeat="thumbx_link in details.thumbnail_links" style="padding: 5px">-->
                                            <td ng-repeat="(key,file) in details.files" style="width: 149px;">
                                                
                                                <div ng-if="['jpg','JPG','jpeg','JPEG','png','PNG','gif','GIF','tif','TIF','tiff','TIFF','bmp','BMP'].includes(file.split('.').pop().toLowerCase())">
                                                    <!--<img class="client-img img-thumbnail" src="assets/googledrive_guide/images/image_icon.png" style="height:150px; width:150px; border-radius:8px;" />-->
                                                    <img class="client-img img-thumbnail" src="uploads/study_material/{{file}}" style="height:150px; width:150px; border-radius:8px;" />
                                                </div>
                                                
                                                <div ng-if="['pdf','PDF'].includes(file.split('.').pop().toLowerCase())">
                                                    <img class="client-img img-thumbnail" src="assets/googledrive_guide/images/adobe_icon.png" style="height:150px; width:150px; border-radius:8px;" />
                                                </div>
                                                
                                                <div ng-if="['doc','DOC','docx','DOCX'].includes(file.split('.').pop().toLowerCase())">
                                                    <img class="client-img img-thumbnail" src="assets/googledrive_guide/images/docx_icon.png" style="height:150px; width:150px; border-radius:8px;" />
                                                </div>
                                                                                                
                                                <div ng-if="['ppt','PPT','pptx','PPTX'].includes(file.split('.').pop().toLowerCase())">
                                                    <img class="client-img img-thumbnail" src="assets/googledrive_guide/images/ppt_icon.jpg" style="height:150px; width:150px; border-radius:8px;" />
                                                </div>
                                                                                                                                                
                                                <div ng-if="['xlsm','XLSM','xlsx','XLSX','xlt','XLT','xls','XLS'].includes(file.split('.').pop().toLowerCase())">
                                                    <img class="client-img img-thumbnail" src="assets/googledrive_guide/images/excel_icon.jpg" style="height:150px; width:150px; border-radius:8px;" />
                                                </div>
                                                                                                                                                                                                
                                                <div ng-if="['mp4','MP4','avi','AVI','flv','FLV','wmv','WMV','mov','MOV','webm','WEBM'].includes(file.split('.').pop().toLowerCase())">
                                                    <img class="client-img img-thumbnail" src="assets/googledrive_guide/images/videoicon.png" style="height:150px; width:150px; border-radius:8px;" />
                                                </div>
                                            
                                                <div style="text-align: center !important; width: 141px; overflow: hidden; text-overflow: ellipsis;">
                                                    <a  href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank" ng-if="details.file_names[key]!=''">{{details.file_names[key]}}</a> 
                                                    <a  href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank" ng-if="details.file_names==''">{{file}}</a> 
                                                    <!--<a  href="<?php echo base_url() ?>uploads/study_material/{{file}}" data-lightbox="image-attach">{{details.file_names[key]}}</a>-->
                                                    <!--<button class="btn btn-link text-danger" ng-click="removeFile(f.name)" type="button">Remove</button>-->
                                                </div>
                                                
                                            </td>
                                          </tr>
                                        </table>
                                    </div>
                                    
                                </div>
                                
                                </ul>

                               <!--  <div  ng-repeat="file in details.files">
                                    <li ng-if="['jpg','jpeg','png','gif'].includes(file.split('.').pop().toLowerCase())"><a href="<?php echo base_url() ?>uploads/study_material/{{file}}" data-lightbox="image-attach">{{file}}</a></li>
                                    <li ng-if="!['jpg','jpeg','png','gif'].includes(file.split('.').pop().toLowerCase())"><a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a></li>
                                    
                                </div> -->
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


                    <div class="row pull-right">
                        <div style="margin-right: 8px">
                            <button type="button" class="btn btn-default"
                            data-dismiss="modal"><?php echo lang('lbl_close') ?>
                        </button>
                    </div>
                    <div ng-show="details.files[0].length > 0">
                        <button type="button" class="btn btn-primary" ng-click="download()">
                            <?php echo lang('download_files') ?>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
        <div class="modal fade" id="AssignmentDetails" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
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
                            data-dismiss="modal"><?php echo lang('lbl_close') ?>
                        </button>
                    </div>
                    <div ng-show="details.files[0].length > 0">
                        <button type="button" class="btn btn-primary" ng-click="download()">
                            <?php echo lang('download_files') ?>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
        <div class="modal fade" id="homeworkDetails" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
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
                            data-dismiss="modal"><?php echo lang('lbl_close') ?>
                        </button>
                    </div>
                    <div ng-show="details.files[0].length > 0">
                        <button type="button" class="btn btn-primary" ng-click="download()">
                            <?php echo lang('download_files') ?>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>

<div id="deleteMaterial" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo lang('lbl_delete_confirmation') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <p><?php echo lang('delete_confirmation_message') ?></p>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang('btn_close_bank') ?></button>
                <button type="button" class="btn btn-danger waves-effect waves-light" ng-click="deleteMaterial()"><?php echo lang('btn_delete_bank') ?></button>
            </div>
        </div>
    </div>
</div>

<div id="deleteAssignment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo lang('lbl_delete_confirmation') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <p><?php echo lang('delete_confirmation_message') ?></p>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang('btn_close_bank') ?></button>
                <button type="button" class="btn btn-danger waves-effect waves-light" ng-click="deleteAssignment()"><?php echo lang('btn_delete_bank') ?></button>
            </div>
        </div>
    </div>
</div>

<div id="deleteHomework" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo lang('lbl_delete_confirmation') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <p><?php echo lang('delete_confirmation_message') ?></p>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang('btn_close_bank') ?></button>
                <button type="button" class="btn btn-danger waves-effect waves-light" ng-click="deleteHomework()"><?php echo lang('btn_delete_bank') ?></button>
            </div>
        </div>
    </div>
</div>


<!--/Add new end here-->

<!--/panel body-->
</div>
<!--/panel wrapper-->
</div>
<!--/panel-->
</div>
</div>
<!--./row-->
<!--page content end here-->

</div>
</div>
<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>
<script type="text/javascript">
    $(document).ready(function(){




      $('.textarea_editor').wysihtml5();
      $('.textarea_editor1').wysihtml5();
      $('.textarea_editor2').wysihtml5();
      $('.textarea_editor3').wysihtml5();
      $('.textarea_editor7').wysihtml5();
      $('.textarea_editor8').wysihtml5();
  })
</script>
<script type="text/javascript">
   $('#checkMarks').click(function() {
      if ($(this).is(':checked')) {
        $("#marks").removeAttr('disabled');
      } else {
        $('#marks').attr('disabled', 'disabled');
      }
    });

   $('#checkMarks1').click(function() {
      if ($(this).is(':checked')) {
        $("#marks1").removeAttr('disabled');
      } else {
        $('#marks1').attr('disabled', 'disabled');
      }
    });

   $('#checkMarks2').click(function() {
      if ($(this).is(':checked')) {
        $("#marks2").removeAttr('disabled');
      } else {
        $('#marks2').attr('disabled', 'disabled');
      }
    });

   $('#checkMarks3').click(function() {
      if ($(this).is(':checked')) {
        $("#marks3").removeAttr('disabled');
      } else {
        $('#marks3').attr('disabled', 'disabled');
      }
    });
</script>