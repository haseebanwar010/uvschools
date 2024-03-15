<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<div id="page-wrapper" ng-controller="announcementsCtrl" ng-init="initAnnouncements(); initClasses(); initDepartments()">
    <div class="container-fluid" style="padding-bottom:0;">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?= lang("lbl_announcements"); ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="dashboard/"><?= lang("lbl_dashboard"); ?></a></li>
                    <li class="active"><?= lang("lbl_announcements"); ?></li>
                </ol>
            </div>
        </div>
      
        <!-- Browse Modal -->
        <div class="modal animated slideInDown" id="annBrowseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" id="ann-browse-modal-contents">
                    <div class="panel panel-primary"> 
                        <button type="button" class="close" style="margin: 20px;" data-dismiss="modal" aria-hidden="true">×</button>
                        <div class="panel-heading"><?php echo lang('lbl_announement_details'); ?></div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label"><?= lang('lbl_tbl_title'); ?></label>
                                        <p>{{bModel.title}}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label"><?= lang('start_date'); ?></label>
                                        <p>{{bModel.from_date}}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label"><?= lang('end_date'); ?></label>
                                        <p>{{bModel.to_date}}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label"><?= lang("lbl_level"); ?></label>
                                        <p>{{bModel.level}}</p>
                                    </div>
                                </div>
                                <div class="col-md-6" ng-show="bModel.level == 'employees'">
                                    <div class="form-group">
                                        <label class="control-label"><?= lang("departments"); ?></label>
                                        <p>{{bModel.department_names_string}}</p>
                                    </div>
                                </div>
                                <div class="col-md-6" ng-show="bModel.level == 'employees'">
                                    <div class="form-group">
                                        <label class="control-label"><?= lang("title_category"); ?></label>
                                        <p>{{bModel.category_names_string}}</p>
                                    </div>
                                </div>
                                <div class="col-md-6" ng-show="bModel.level == 'employees'">
                                    <div class="form-group">
                                        <label class="control-label"><?= lang("heading_all_employee"); ?></label>
                                        <p>{{bModel.employee_names_string}}</p>
                                    </div>
                                </div>
                                <div class="col-md-6" ng-show="bModel.level == 'students'">
                                    <div class="form-group">
                                        <label class="control-label"><?= lang("lbl_classes"); ?></label>
                                        <p>{{bModel.class_names_string}}</p>
                                    </div>
                                </div>
                                <div class="col-md-6" ng-show="bModel.level == 'students'">
                                    <div class="form-group">
                                        <label class="control-label"><?= lang("lbl_batches"); ?></label>
                                        <p>{{bModel.section_names_string}}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label"><?= lang("lbl_status"); ?></label>
                                        <p>{{bModel.status}}</p>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label"><?= lang('lbl_details'); ?></label>
                                        <p><span ng-bind-html="bModel.details"></span></p>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label"><?= lang("lbl_created_at"); ?></label>
                                        <p>{{bModel.created_at}}</p>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label"><?= lang("lbl_attachments"); ?></label>
                                        <p>
                                            <img ng-if="bModel.img_or_document_type == 'png' || bModel.img_or_document_type == 'jpg' || bModel.img_or_document_type == 'jpeg'" src="uploads/announcements/{{bModel.img_or_document}}" class="img-thumbnail" style="width:100px;" />
                                            <a href="uploads/announcements/{{bModel.img_or_document}}" ng-if="bModel.img_or_document_type == 'docx' || bModel.img_or_document_type == 'xlxs' || bModel.img_or_document_type == 'txt' || bModel.img_or_document_type == 'ppt' || bModel.img_or_document_type == 'pptx'">{{bModel.img_or_document}}</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row pull-right">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal -->

        <!-- Add Modal -->
        <div class="modal animated slideInDown" id="annAddModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" id="ann-add-modal-contents">
                    <div class="panel panel-primary"> 
                        <button type="button" class="close" style="margin: 20px;" data-dismiss="modal" aria-hidden="true">×</button>
                        <div class="panel-heading"><?php echo lang('lbl_add_announements'); ?></div>
                        <div class="panel-body">
                            <form name="annAddForm" ng-submit="save(annAddForm.$valid)" novalidate="" class="form-material">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label"><?= lang('lbl_tbl_title'); ?></label>
                                                <input type="text" name="title" ng-model="sModel.title" class="form-control" maxlength="50" required/>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?= lang('start_date'); ?></label>
                                                <input type="text" name="title" ng-model="sModel.from_date" class="form-control mydatepicker-autoclose" required/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?= lang('end_date'); ?></label>
                                                <input type="text" name="title" ng-model="sModel.to_date" class="form-control mydatepicker-autoclose" required/>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label"><?= lang("lbl_level"); ?></label>
                                                <select class="form-control" ng-model="sModel.level" ng-init="sModel.level='all'" ng-change="initsModal()">
                                                    <option value="all"><?php echo lang('option_public');?></option>
                                                    <option value="employees"><?php echo lang('option_private');?></option>
                                                    <option value="parents"><?php echo lang('lbl_parent_dsh');?></option>
                                                    <option value="students"><?php echo lang('menu_students');?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12" ng-show="sModel.level == 'employees'">
                                            <div class="form-group" id="select2-departments-container">
                                                <label class="control-label"><?= lang("departments"); ?></label>
                                                <select class="form-control yasir-ann-select2" style="width: 100%;" id="select2-departments" multiple="multiple" ng-change="initRoleCategories()" ng-model="sModel.departments">
                                                    <option ng-repeat="dpt in departments" value="{{dpt.id}}">{{dpt.name}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12" ng-show="sModel.level == 'employees'">
                                            <div class="form-group" id="select2-categories-container">
                                                <label class="control-label"><?= lang("title_category"); ?></label>
                                                <select class="form-control yasir-ann-select2" style="width: 100%;" id="select2-categories" multiple="multiple" ng-change="initEmployees()" ng-model="sModel.categories">
                                                    <option ng-repeat="cat in categories" value="{{cat.id}}">{{cat.category}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12" ng-show="sModel.level == 'employees'">
                                            <div class="form-group" id="select2-employees-container">
                                                <label class="control-label"><?= lang("heading_all_employee"); ?></label>
                                                <select class="form-control yasir-ann-select2" style="width: 100%;" multiple="multiple"  id="select2-employees" ng-model="sModel.employees">
                                                    <option ng-repeat="emp in employees" value="{{emp.id}}">{{emp.name}}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12" ng-show="sModel.level == 'students'">
                                            <div class="form-group" id="select2-classes-container">
                                                <label class="control-label"><?= lang("lbl_classes"); ?></label>
                                                <select class="form-control yasir-ann-select2" style="width: 100%;" multiple="multiple" ng-model="sModel.classes" id="select2-classes" ng-change="initBatches()">
                                                    <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12" id="select2-section-container" ng-show="sModel.level == 'students'">
                                            <div class="form-group">
                                                <label class="control-label"><?= lang("lbl_batches"); ?></label>
                                                <select class="form-control yasir-ann-select2" style="width: 100%;" multiple="multiple" ng-model="sModel.section" id="select2-section">
                                                    <option ng-repeat="b in batches" value="{{b.id}}">{{b.name}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label"><?= lang("lbl_status"); ?></label>
                                                <select class="form-control" ng-model="sModel.status" ng-init="sModel.status='active'">
                                                    <option value="Active"><?php echo lang('lbl_active');?></option>
                                                    <option value="In-Active"><?php echo lang('option_disable');?></option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label"><?= lang('lbl_details'); ?></label>
                                                <textarea class="textarea_editor_announcement form-control" style="border: 1px solid #e5e5e5;" rows="5" placeholder=""></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <table class="table table-default">
                                                <tbody>
                                                    <tr ng-repeat="item in uploader.queue" style="word-break: break-all;">
                                                        <td><strong>{{ response }}</strong></td>
                                                        <td style="width: 50%;"><strong>{{ item.file.name }}</strong></td>
                                                        <td style="width: 20%;" ng-show="uploader.isHTML5">
                                                            <div class="progress" style="margin-bottom: 0;">
                                                                <div class="progress-bar" role="progressbar" ng-style="{ 'width': item.progress + '%' }"></div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <span ng-show="item.isSuccess"><i class="glyphicon glyphicon-ok"></i></span>
                                                            <span ng-show="item.isCancel"><i class="glyphicon glyphicon-ban-circle"></i></span>
                                                            <span ng-show="item.isError"><i class="glyphicon glyphicon-remove"></i></span>
                                                        </td>
                                                        <td nowrap>
                                                            <button type="button" class="btn btn-success btn-xs btn-circle" ng-click="item.upload()" ng-disabled="item.isReady || item.isUploading || item.isSuccess">
                                                                <span class="glyphicon glyphicon-upload"></span>
                                                            </button>
                                                            <button type="button" class="btn btn-warning btn-xs btn-circle" ng-click="item.cancel()" ng-disabled="!item.isUploading">
                                                                <span class="glyphicon glyphicon-ban-circle"></span>
                                                            </button>
                                                            <button type="button" id="remove" class="btn btn-danger btn-xs btn-circle" ng-click="item.remove()">
                                                                <span class="glyphicon glyphicon-trash"></span>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <div id="message" style="display: none;">
                                                <span class="text-danger" style="font-weight: bold;"><?php echo lang('error_file_invalid'); ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label"><?= lang("lbl_attachments"); ?></label>
                                                <input type="file" nv-file-select="" uploader="uploader" class="form-control" id="uploadfile" style="visibility:hidden;" />
                                                <button type="button" style="display:block;width:auto; height:auto" class="btn btn-info" onclick="document.getElementById('uploadfile').click()"><?php echo lang('choose_image') ;?></button>
                                                <!--<button type="button" style="display:block;width:120px; height:30px;" class="btn btn-info" onclick="document.getElementById('uploadfile').click()"><?php echo lang('choose_image') ;?></button>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                
                                <div class="row pull-right">
                                    <div style="margin-right: 8px">
                                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('modal_btn_close');?> </button>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary"><?php echo lang('lbl_add');?> </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal -->

        <!-- Edit Modal -->
        <div class="modal animated slideInDown" id="annEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" id="ann-edit-modal-contents">
                    <div class="panel panel-primary"> 
                        <button type="button" class="close" style="margin: 20px;" data-dismiss="modal" aria-hidden="true">×</button>
                        <div class="panel-heading"><?php echo lang('lbl_edit_announements'); ?></div>
                        <div class="panel-body">
                            <form name="annEditForm" ng-submit="update(annEditForm.$valid)" novalidate="" class="form-material">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label"><?= lang('lbl_tbl_title'); ?></label>
                                                <input type="text" name="title" ng-model="eModel.title" class="form-control" required/>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?= lang('start_date'); ?></label>
                                                <input type="text" name="title" ng-model="eModel.from_date" class="form-control mydatepicker-autoclose" required/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"><?= lang('end_date'); ?></label>
                                                <input type="text" name="title" ng-model="eModel.to_date" class="form-control mydatepicker-autoclose" required/>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label"><?= lang("lbl_level"); ?></label>
                                                <select class="form-control" ng-model="eModel.level" ng-change="initeModal()">
                                                    <option value="all">Public</option>
                                                    <option value="employees">Private</option>
                                                    <option value="parents">Parents</option>
                                                    <option value="students">Students</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12" ng-show="eModel.level == 'employees'">
                                            <div class="form-group" id="select2-edit-departments-container">
                                                <label class="control-label"><?= lang("departments"); ?></label>
                                                <select class="form-control" style="width: 100%;" id="select2-edit-departments" multiple="multiple" ng-change="initEditRoleCategories()" ng-model="eModel.departments">
                                                    <option ng-repeat="dpt in departments" value="{{dpt.id}}">{{dpt.name}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12" ng-show="eModel.level == 'employees'">
                                            <div class="form-group" id="select2-edit-categories-container2">
                                                <label class="control-label"><?= lang("title_category"); ?></label>
                                                <select class="form-control" style="width: 100%;" id="select2-edit-categories" multiple="multiple" ng-change="initEditEmployees()" ng-model="eModel.categories">
                                                    <option ng-repeat="cat in categories" value="{{cat.id}}">{{cat.category}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12" ng-show="eModel.level == 'employees'">
                                            <div class="form-group" id="select2-edit-employees-container2">
                                                <label class="control-label"><?= lang("heading_all_employee"); ?></label>
                                                <select class="form-control" style="width: 100%;" multiple="multiple"  id="select2-edit-employees" ng-model="eModel.employees">
                                                    <option ng-repeat="emp in employees" value="{{emp.id}}">{{emp.name}}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12" ng-show="eModel.level == 'students'">
                                            <div class="form-group" id="select2-edit-classes-container">
                                                <label class="control-label"><?= lang("lbl_classes"); ?></label>
                                                <select class="form-control" style="width: 100%;" multiple="multiple" ng-model="eModel.classes" id="select2-edit-classes" ng-change="initEditBatches()">
                                                    <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12" id="select2-edit-section-container" ng-show="eModel.level == 'students'">
                                            <div class="form-group">
                                                <label class="control-label"><?= lang("lbl_batches"); ?></label>
                                                <select class="form-control" style="width: 100%;" multiple="multiple" ng-model="eModel.section" id="select2-edit-section">
                                                    <option ng-repeat="b in batches" value="{{b.id}}">{{b.name}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label"><?= lang("lbl_status"); ?></label>
                                                <select class="form-control" ng-model="eModel.status">
                                                    <option value="Active"><?php echo lang('lbl_active');?></option>
                                                    <option value="In-Active"><?php echo lang('option_disable');?></option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label"><?= lang('lbl_details'); ?></label>
                                                <textarea class="textarea_editor_announcement1 form-control" style="border: 1px solid #e5e5e5;" rows="5" placeholder=""></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <table class="table table-default">
                                                <tbody>
                                                    <tr ng-repeat="item in uploader2.queue">
                                                        <td style="width: 50%;"><strong>{{ item.file.name }}</strong></td>
                                                        <td style="width: 20%;" ng-show="uploader2.isHTML5">
                                                            <div class="progress" style="margin-bottom: 0;">
                                                                <div class="progress-bar" role="progressbar" ng-style="{ 'width': item.progress + '%' }"></div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <span ng-show="item.isSuccess"><i class="glyphicon glyphicon-ok"></i></span>
                                                            <span ng-show="item.isCancel"><i class="glyphicon glyphicon-ban-circle"></i></span>
                                                            <span ng-show="item.isError"><i class="glyphicon glyphicon-remove"></i></span>
                                                        </td>
                                                        <td nowrap>
                                                            <button type="button" class="btn btn-success btn-xs btn-circle" ng-click="item.upload()" ng-disabled="item.isReady || item.isUploading || item.isSuccess">
                                                                <span class="glyphicon glyphicon-upload"></span>
                                                            </button>
                                                            <button type="button" class="btn btn-warning btn-xs btn-circle" ng-click="item.cancel()" ng-disabled="!item.isUploading">
                                                                <span class="glyphicon glyphicon-ban-circle"></span>
                                                            </button>
                                                            <button type="button" id="removeEdit" class="btn btn-danger btn-xs btn-circle" ng-click="item.remove()">
                                                                <span class="glyphicon glyphicon-trash"></span>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            
                                            <div class="form-group">
                                                <div id="messageEdit" style="display: none;">
                                                    <span class="text-danger" style="font-weight: bold;"><?php echo lang('error_file_invalid'); ?></span>
                                                </div>
                                                <img ng-if="eModel.img_or_document_type == 'png' || eModel.img_or_document_type == 'jpg' || eModel.img_or_document_type == 'jpeg'" src="uploads/announcements/{{eModel.img_or_document}}" class="img-thumbnail" style="width:80px; height: 80px;" />
                                                <a href="uploads/announcements/{{eModel.img_or_document}}" ng-if="eModel.img_or_document_type == 'docx' || eModel.img_or_document_type == 'xlsx' || eModel.img_or_document_type == 'txt' || eModel.img_or_document_type == 'ppt' || eModel.img_or_document_type == 'pptx'" class="custom-wrap" style="width: 100px;" title="{{eModel.img_or_document}}">{{eModel.img_or_document}}</a>
                                                <br/>
                                                <label class="control-label"><?= lang("lbl_attachments"); ?></label>
                                                
                                                <input type="file" nv-file-select="" uploader="uploader2" class="form-control" id="uploadfile2" style="visibility:hidden;"/>
                                                
                                                <!--<button style="display:block;width:120px; height:30px;" class="btn btn-info" onclick="document.getElementById('uploadfile2').click()"><?php echo lang('choose_image') ;?></button>-->
                                                <button type="button" style="display:block;width:auto; height:auto;" class="btn btn-info" onclick="document.getElementById('uploadfile2').click()"><?php echo lang('choose_image') ;?></button>
                                                
                                                        <!--<input type='file' id="getFile" nv-file-select="" uploader="uploader2" class="form-control" id="uploadfile2" style="display:none">-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="row pull-right">
                                    <div style="margin-right: 8px">
                                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('modal_btn_close'); ?> </button>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary"><?php echo lang('btn_update'); ?> </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal -->

        <!-- alert box -->
        <?php $error = $this->session->flashdata('alert'); if(!empty($error)) { ?>
            <div class="alert alert-dismissable <?php if($this->session->flashdata('alert')['status'] == 'error') { echo 'alert-danger'; } else {echo 'alert-success'; }?>"> 
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>     
                <?= $this->session->flashdata("alert")['message']; ?> 
            </div>
        <?php } ?>
        <!-- end alert box -->

        <div class="hint"><?php echo lang('help_announcements'); ?></div>
        <div class="row">
            <div class="col-md-12">
                <div class="white-box" id="announcements-table">
                    <div class="col-md-12 mb-2 p-0">
                        <div class="col-md-9 p-0">
                            <button type="button" class="btn btn-primary mb-2" ng-click="showAddModal()"><i class="fa fa-plus"></i> <?php echo lang('lbl_add');?></button>
                        </div>
                        <div class="col-md-3 p-0">
                            <input type="text" ng-model="searchedValue" placeholder="<?= lang("lbl_enter_some_text_to_search"); ?>" class="form-control" />
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th><?= lang('imp_sr'); ?></th>
                                    <th><?= lang('lbl_tbl_title'); ?></th>
                                    <th><?= lang('lbl_details'); ?></th>
                                    <th><?= lang('start_date'); ?></th>
                                    <th><?= lang('end_date'); ?></th>
                                    <th><?= lang('lbl_level'); ?></th>
                                    <th><?= lang('departments'); ?></th>
                                    <th><?= lang('heading_all_employee'); ?></th>
                                    <th><?= lang('lbl_classes'); ?></th>
                                    <th><?= lang('lbl_batches'); ?></th>
                                    <th><?= lang('lbl_attachments'); ?></th>
                                    <th><?= lang('lbl_status'); ?></th>
                                    <!--<th><?= lang('lbl_created_at'); ?></th>-->
                                    <th class="text-center" style="min-width: 120px;"><?= lang('lbl_action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-show="(yannouncements | filter:searchedValue).length == 0" >
                                    <td class="text-left no-print" colspan="13"><?= lang("no_record"); ?></td>
                                </tr>
                                <tr dir-paginate="ann in yannouncements | itemsPerPage: 10 | filter:searchedValue" pagination-id="ann">
                                    <td class="text-center text-muted">{{ann.index}}</td>
                                    <td>{{ann.title}}</td>
                                    <td><p class="custom-wrap" style="width: 300px;" ng-bind-html="ann.details"></p></td>
                                    <td>{{ann.from_date}}</td>
                                    <td>{{ann.to_date}}</td>
                                    <td>{{ann.level}}</td>
                                    <td class="text-center">
                                        <span ng-show="ann.department_names.length>1" title="{{ann.department_names_string}}"><i class="fa fa-building-o fa-2x"></i></span>
                                        <span ng-show="ann.department_names.length==1">{{ann.department_names_string}}</span>
                                    </td>
                                    <td class="text-center">
                                        <img ng-show="ann.employee_avatars.length==1" src="uploads/user/{{ann.employee_avatars[0]}}" style="width: 40px; height: 40px;" class="img-circle"/>
                                        <span ng-show="ann.employee_avatars.length>1" title="{{ann.employee_names_string}}"><i class="fa fa-users fa-2x"></i></span>
                                    </td>
                                    <td><span class="custom-wrap" style="max-width: 100px;">{{ann.class_names_string}}</span></td>
                                    <td><span class="custom-wrap" style="max-width: 100px;">{{ann.section_names_string}}</span></td>
                                    <td class="text-center">
                                        <img ng-if="ann.img_or_document_type == 'png' || ann.img_or_document_type == 'jpg' || ann.img_or_document_type == 'jpeg'" src="uploads/announcements/{{ann.img_or_document}}" class="img-circle" style="width:40px; height: 40px;" />

                                        <!-- <a href="uploads/announcements/{{ann.img_or_document}}" ng-if="ann.img_or_document_type == 'docx' || ann.img_or_document_type == 'xlsx' || ann.img_or_document_type == 'txt' || ann.img_or_document_type == 'ppt' || ann.img_or_document_type == 'pptx || ann.img_or_document_type == 'pdf' | ann.img_or_document_type == 'pdf'" class="custom-wrap" style="width: 100px;" title="{{ann.img_or_document}}">{{ann.img_or_document}}</a> -->

                                          <a href="uploads/announcements/{{ann.img_or_document}}" ng-if="ann.img_or_document_type != 'png' || ann.img_or_document_type != 'jpg' || ann.img_or_document_type != 'jpeg'" class="custom-wrap" style="width: 100px;" title="{{ann.img_or_document}}">{{ann.img_or_document}}</a>
                                    </td>
                                    <td>
                                        <span ng-class="{'text-danger': ann.status=='In-Active', 'text-success': ann.status=='Active'}">{{ann.status}}</span>
                                    </td>
                                    <td style="width: 115px;" class="text-center">
                                        <button type="button" class="btn btn-success btn-sm btn-circle" ng-click="setBrowseModel(ann)"><i class="fa fa-eye"></i></button>
                                        <button type="button" class="btn btn-info btn-sm btn-circle" ng-click="setEditModel(ann)"><i class="fa fa-pencil"></i></button>
                                        <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-circle sa-warning" value="{{ann.id}},announcements/softDelete"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <dir-pagination-controls pagination-id="ann"></dir-pagination-controls>
                    </div>
                </div>  
            </div>
        </div>

    </div>
<?php include(APPPATH . "views/inc/footer.php"); ?>

<script type="text/javascript">
    $(document).ready(function(){

      $('.textarea_editor_announcement').wysihtml5();
      $('.textarea_editor_announcement1').wysihtml5();
  })
</script>
