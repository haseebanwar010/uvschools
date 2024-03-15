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
        <div class="container-fluid" ng-controller="downloadParentController" ng-init="initClasswork()">
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
            <div class="hint"><?php echo lang('help_download'); ?></div>
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
                                        class="hidden-xs"><?php echo lang("class_work"); ?></span></a>
                                    </li>


                                </ul>

                                <!--tab content start here-->

                                <div class="tab-content">
                                    <div class="tab-pane active" id="general">

                                        <!--row -->
                                        <div class="well" id="download_search_filter">
                                            <div class="row">
                                                <!--row -->

                                                <div class="col-md-3">
                                                    <div class="form-group form-material">
                                                        <label class="control-label"><?php echo lang('lbl_child') ?></label>
                                                        <select class="form-control " name="grade-type" ng-model="student_id" ng-change="initSubjects(student_id)" required="">
                                                            <option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                                                            <option ng-repeat="cls in parentchild" value="{{cls.student_id}}">{{cls.name}}</option>
                                                        </select>
                                                        <div style="color: red" ng-show="class_error"><?php echo lang('lbl_field_required') ?></div>
                                                    </div>
                                                </div>
                                                <!--/span-->
                                                
                                                <!--/span-->
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
                                                <!--/span-->
                                                <div class="col-md-3">
                                                    <div class="form-group form-material">
                                                        <label class="control-label"><?php echo lang('material_type'); ?></label>
                                                        <select class="form-control " name="grade-type" ng-model="type">
                                                            <option value="" disabled="">--<?php echo lang('lbl_select'); ?>--</option>
                                                            <option value="Assignment"><?php echo lang('lbl_assignment'); ?></option>
                                                            <option value="Homework"><?php echo lang('lbl_homework'); ?></option>
                                                        </select>
                                                        <div style="color: red" ng-show="type_error"><?php echo lang('lbl_field_required') ?></div>
                                                    </div>
                                                </div>
                                                <!--/span-->

                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button class="btn btn-sm btn-primary" ng-click="filterClassWork()"><?php echo lang('search'); ?></button>
                                                    
                                                </div>

                                            </div>
                                        </div>


                                            <div class="col-md-12 text-danger" ng-show="study.materials.length == 0"><?php echo lang('no_record'); ?></div>
                                            <div class="col-md-12" ng-repeat="matd in study.materials">
                                                <div class="card mb-0 bg-light col-md-12" style="background-color: white !important; margin-top: 10px; border: 1px solid #e3e3e3; box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-8"><img src="<?php echo base_url(); ?>uploads/user/{{matd.avatar}}" alt="user-img" style="width: 40px;" class="img-circle"> <b>{{matd.teacher_name}}</b>
                                                                <div class="row">
                                                                    <div class="col-md-4" style="padding-top: 10px;">
                                                                        <b><?php echo lang('lbl_date'); ?> : </b>{{matd.published_date}}
                                                                    </div>
                                                                    <div class="col-md-4" style="padding-top: 10px;">
                                                                        <b><?php echo lang('due_date'); ?> : </b>{{matd.due_date}}
                                                                    </div>
                                                                    <div class="col-md-4" style="padding-top: 10px;">
                                                                        <b><?php echo lang('lbl_subject_msg'); ?> : </b>{{matd.subject_name}}
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4" style="padding-top: 10px;">
                                                                        <b><?php echo lang('type'); ?> : </b>{{matd.content_type}}
                                                                    </div>
                                                                    <div class="col-md-4" style="padding-top: 10px;">
                                                                        <b><?php echo lang('lbl_class'); ?> : </b>{{matd.class_name}}
                                                                    </div>
                                                                    <div class="row col-md-4" style="padding-top: 10px;">
                                                                        <b><?php echo lang('lbl_status'); ?> :</b><button style="margin-left: 10px; height: 33px; margin-top: -7px;" ng-if="matd.status=='Submitted'" class="btn btn-success btn-xs">{{matd.status}}</button><button style="margin-left: 10px; height: 33px; margin-top: -7px;" ng-if="matd.status=='Due'" class="btn btn-danger btn-xs">{{matd.status}}</button>
                                                                    </div>
                                                                </div>
                                                                <div class="row" ng-if="matd.total_marks != 0">
                                                                    <div class="col-md-4" style="padding-top: 10px;">
                                                                        <b><?php echo lang('total_marks'); ?> : </b>{{matd.total_marks}}
                                                                    </div>
                                                                    <div class="row col-md-8" style="padding-top: 10px;">
                                                                        <b><?php echo lang('obtained_marks'); ?> : </b>
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
                                                                    <b style="padding-left: 4px;"> <?php echo lang('lbl_attachments'); ?></b>
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
                                                            <button type="button" class="btn btn-primary" ng-if="matd.submit_material_id == null" data-toggle="modal" id="submitBtn" data-target="#openAssignment" ng-click="details_set1(matd);"><?php echo lang('btn_view'); ?></button>
                                                            <button type="button" class="btn btn-primary" ng-if="matd.submit_material_id != null" data-toggle="modal" id="submitBtn" data-target="#openAssignmentView" ng-click="details_set1(matd);"><?php echo lang('btn_view'); ?></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>


                                    <div class="modal fade" id="details" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                                                <div ng-if="['mp3','MP3', 'avi', 'AVI', 'aac', 'AAC'].includes(file.split('.').pop().toLowerCase())">
                                                                                    <img class="client-img img-thumbnail" src="assets/googledrive_guide/images/audioicon.png" style="height:150px; width:150px; border-radius:8px;" />
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
                                                                            
                                                                            <div ng-if="details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'mp3' ||  details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'MP3' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'avi' ||  details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'AVI' || details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'aac' ||  details.file_names[key].substr(details.file_names[key].lastIndexOf('.') + 1) == 'AAC'">
                                                                                <img class="client-img img-thumbnail" src="assets/googledrive_guide/images/audioicon.png" style="height:150px; width:320px; border-radius:8px;" />
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
                                                            data-dismiss="modal"><?php echo lang('lbl_close'); ?>
                                                        </button>
                                                    </div>
                                                    <div ng-show="details.files[0].length > 0">
                                                        <button type="button" class="btn btn-primary" ng-click="download()">
                                                            <?php echo lang('download_files'); ?>
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!--tab content end here-->
                        </div>


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
                                                <div class="col-md-6 text-right"><b><?php echo lang('due_date'); ?> : </b>{{details.due_date}}</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p ng-bind-html="details.details" style="padding-top: 5px; padding-left: 22px;"></p>
                                                </div>
                                            </div>
                                            <div class="row" ng-show="details.files[0].length > 0">
                                                <div class="col-md-12">
                                                    <div style="padding-bottom: 10px;">
                                                        <i class="fa fa-paperclip" aria-hidden="true"></i><b style="padding-left: 4px;"> <?php echo lang('lbl_attachments'); ?></b>
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
                                                    <p style="padding-left: 22px;">{{details.material_details}}</p>
                                                </div>
                                            </div><hr>
                                            <div class="alert" id="assignmentAns" style="display: none"></div>
                                            <div class="row pull-right">
                                                <div style="margin-right: 8px">
                                                    <button type="button" class="btn btn-default"
                                                    data-dismiss="modal"><?php echo lang('lbl_close'); ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="modal fade" id="openAssignmentView" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">{{details.title}} - {{details.content_type}}</div>
                                        </div>
                                        <input type="hidden" name="teacher_id" value="details.teacher_id">
                                        <div class="panel-body">
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-md-6"><img src="<?php echo base_url(); ?>uploads/user/{{details.teacher_avatar}}" alt="user-img" style="width: 40px;" class="img-circle"> <b>{{details.teacher_name}}</b></div>
                                                    <div class="col-md-6 text-right"><b><?php echo lang('due_date'); ?> : </b>{{details.due_date}}</div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <p ng-bind-html="details.details" style="padding-top: 5px; padding-left: 22px;"></p>
                                                    </div>
                                                </div>
                                                <div class="row" ng-show="details.files[0].length > 0">
                                                    <div class="col-md-12">
                                                        <div style="padding-bottom: 10px;">
                                                            <i class="fa fa-paperclip" aria-hidden="true"></i><b style="padding-left: 4px;"> <?php echo lang('lbl_attachments'); ?></b>
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
                                                        <p style="padding-left: 22px;">{{details.material_details}}</p>
                                                    </div>
                                                </div><hr>

                                                <div class="row">
                                                    <div class="col-md-6"><img src="<?php echo base_url(); ?>uploads/user/{{details.student_avatar}}" alt="user-img" style="width: 40px;" class="img-circle"> <b>{{details.student_name}}</b>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12" style="padding-top: 8px;">
                                                        <b style="padding-left: 4px;"><?php echo lang('description'); ?> : </b>
                                                        <p ng-bind-html="details.submitted_details" style="padding-left: 22px;"></p>
                                                    </div>
                                                </div>
                                                <div class="row" ng-show="details.submitted_files[0].length > 0">
                                                    <div class="col-md-12">
                                                        <div style="padding-bottom: 10px;">
                                                            <i class="fa fa-paperclip" aria-hidden="true"></i><b style="padding-left: 4px;"> <?php echo lang('lbl_attachments'); ?></b>
                                                        </div>
                                                        
                                                       
                                                        <ul>
                                                            <div ng-if="details.storage_type == 1">
                                                                <div  ng-repeat="std_file in details.submitted_files">
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
                                                        <div ng-if="details.storage_type == 2" style="overflow-x: auto;">
                                                            <div style="width:100%; overflow-x: auto;">
                                                                  <table>
                                                                  <tr style="overflow-x: auto;" class="test">
                                                                      
                                                                    <td ng-repeat="(key,f) in details.submitted_files" style="width: 149px;">
                                                                        <div ng-if="details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'mp4' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'MP4' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'avi' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'AVI' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'flv' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'FLV' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'wmv' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'WMV' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'mov' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'MOV' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'webm'  || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'WEBM' ">
                                                                            <img class="client-img img-thumbnail" src="assets/googledrive_guide/images/videoicon.png" style="height:150px; width:320px; border-radius:8px;" />
                                                                        </div> 
                                                                        
                                                                        <div ng-if="details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'pdf' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'PDF' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'doc' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'DOC' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'docx' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'DOCX' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'ppt' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'PPT' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'pptx' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'PPTX' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'png' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'PNG' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'tif' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'TIF' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'tiff' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'TIFF' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'bmp' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'BMP' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'jpg' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'JPG' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'jpeg' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'JPEG' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'gif' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'GIF' || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'eps'  || details.submitted_file_names[key].substr(details.submitted_file_names[key].lastIndexOf('.') + 1) == 'EPS' ">
                                                                            <img class="client-img img-thumbnail" ng-src="{{details.submitted_thumbnail_links[key]}}" style="height:150px; width:320px; border-radius:8px;" />
                                                                        </div>
                                                                            <div style="text-align: center !important; width: 141px; overflow: hidden; text-overflow: ellipsis;">
                                                                                <a  href="{{details.submitted_filesurl[key]}}" target="_blank">{{details.submitted_file_names[key]}}</a> 
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
                                            </div>
                                            <hr>

                                            <div class="container">
                                                <h3 style="text-align: center; font-size: 21px;">Comments</h3>
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

                                                            </div>
                                                                                                              
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <br><br>
                                            
                                            <div class="row pull-right">
                                                <div style="margin-right: 8px">
                                                    <button type="button" class="btn btn-default"
                                                    data-dismiss="modal"><?php echo lang('lbl_close'); ?>
                                                    </button>
                                                </div>
                                            </div>
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
</div>

<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>
