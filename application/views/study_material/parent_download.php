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
        <div class="container-fluid" ng-controller="downloadParentController" ng-init="init();">
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
                                        class="hidden-xs"><?php echo lang('lbl_download'); ?></span></a>
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
                                                            <option value="Study Material"><?php echo lang('study_material'); ?></option>
                                                            <option value="Classwork"><?php echo lang('lbl_class_work') ?></option>
                                                        </select>
                                                        <div style="color: red" ng-show="type_error"><?php echo lang('lbl_field_required') ?></div>
                                                    </div>
                                                </div>
                                                <!--/span-->

                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button class="btn btn-sm btn-primary" ng-click="filter()"><?php echo lang('search'); ?></button>
                                                    
                                                </div>

                                            </div>
                                        </div>


                                        <div class="row">

                                            <div class="col-md-12 text-danger" ng-show="study.materials.length == 0"><?php echo lang('no_record'); ?></div>

                                            <div class="table-responsive col-md-12" ng-show="study.materials.length > 0">
                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>

                                                            <th><?php echo lang('material_title'); ?></th>
                                                            <th><?php echo lang('material_type'); ?></th>
                                                            <th><?php echo lang('lbl_date'); ?></th>
                                                            <th><?php echo lang('lbl_class'); ?></th>
                                                            <th><?php echo lang('lbl_batch'); ?></th>
                                                            <th><?php echo lang('lbl_subject'); ?></th>
                                                            <th><?php echo lang('uploaded_by'); ?></th>
                                                            <th style="text-align: center;"><?php echo lang('lbl_action'); ?></th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <tr ng-repeat="mat in study.materials">

                                                            <td>{{mat.title}}</td>
                                                            <td>{{mat.content_type}}</td>
                                                            <td>{{mat.uploaded_time}}</td>
                                                            <td>{{mat.class_name}}</td> 
                                                            <td><div ng-show="mat.batch_id == 0"><?php echo lang('option_all'); ?></div>
                                                                <div ng-show="mat.batch_id != 0">{{mat.batch_name}}</div>
                                                            </td>
                                                            <td>{{mat.subject_name}}</td>
                                                            <td>{{mat.name}}</td>
                                                            <td class="text-center">
                                                                <button type="button" class="btn btn-success btn-circle" data-toggle="modal" data-target="#details" ng-click="details_set(mat)"><i
                                                                    class="fa  fa-eye "></i></button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
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

                                                            <!--<ul >-->
                                                            <!--    <div  ng-repeat="file in details.files">-->
                                                            <!--        <li ng-if="['jpg','jpeg','png','gif'].includes(file.split('.').pop().toLowerCase())"><a href="<?php echo base_url() ?>uploads/study_material/{{file}}" data-lightbox="image-attach">{{file}}</a></li>-->
                                                            <!--        <li ng-if="!['jpg','jpeg','png','gif'].includes(file.split('.').pop().toLowerCase())"><a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a></li>-->
                                                                    
                                                            <!--    </div>-->
                                                            <!--</ul>   -->
                                                            
                                                            
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
