<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<!-- Page Content -->
<div id="page-wrapper" ng-controller="onlineAdmissionCtrl" ng-init="initoASettings()">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?= lang("online_admission_settings"); ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="dashboard/"><?= lang("lbl_dashboard"); ?></a></li>
                    <li class="active"><?= lang("online_admission_settings"); ?></li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <?php echo $this->session->flashdata('alert_no_permission'); ?>
        
        
        <div class="hint"><?php echo lang('online_admission_settings'); ?></div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <form name="oaForm" ng-submit="saveOrReplaceOnlineAdmissionSettings()" id="oASettingForm" novalidate>
                        <div class="form-body">
                            
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label><?php echo lang("online_admission_instructions_or_terms_and_conditions"); ?></label>
                                    <textarea class="textarea_editor form-control" cols="100" rows="10" id="yasir_textarea_editor"></textarea>
                                </div>
                            </div>

                            <!--<div class="col-12 col-md-12" ng-show="exisiting_attachments.length>0">-->
                            <!--    <div class="form-group">-->
                            <!--        <label class="control-label"><?php echo lang("existing_attachements"); ?></label><br/>-->
                            <!--        <span ng-repeat="e in exisiting_attachments" style="display: inline-table;">-->
                            <!--            <img src="uploads/attachment/{{e.file}}" ng-if="e.type == 'png' || e.type == 'jpg' || e.type == 'jpeg'" class="img-thumbnail" style="width: 50px; height: 50px; margin-right: 15px;" />-->
                            <!--            <a href="uploads/announcements/{{e.file}}" title="{{e.file}}" ng-if="e.type == 'docx' || e.type == 'xlsx' || e.type == 'txt' || e.type == 'ppt' || e.type == 'pptx' || e.type=='pdf'">-->
                            <!--                <img src="assets/images/pdf_file_icon.svg" style="width: 50px; height: 50px;"/><br/><span class="custom-wrap" style="max-width: 60px;">{{e.file}}</span>-->
                            <!--            </a>-->
                            <!--        </span>-->
                            <!--    </div>-->
                            <!--</div>-->
                            
                            <div class="col-12 col-md-12" ng-show="exisiting_attachments.length>0">
                                <div class="form-group">
                                    <label class="control-label"><?php echo lang("existing_attachements"); ?></label><br/>
                                    <span ng-repeat="e in exisiting_attachments" style="display: inline-table;">
                                        <a href="uploads/attachment/{{e.file}}" target="_blank" ng-if="e.type == 'png' || e.type == 'jpg' || e.type == 'jpeg' || e.type == 'gif'">
                                            <img src="uploads/attachment/{{e.file}}" class="img-thumbnail" style="width: 50px; height: 50px; margin-right: 15px;" /><br/><span class="custom-wrap" style="max-width: 60px;">{{e.file}}</span>
                                        </a>
                                        <a href="uploads/attachment/{{e.file}}" target="_blank" title="{{e.file}}" ng-if="e.type == 'pdf'">
                                            <img src="uploads/study_material/icons/pdf_icon.png" style="width: 50px; height: 50px;"/><br/><span class="custom-wrap" style="max-width: 60px;">{{e.file}}</span>
                                        </a>
                                        <a href="uploads/attachment/{{e.file}}" target="_blank" title="{{e.file}}" ng-if="e.type == 'docx'">
                                            <img src="uploads/study_material/icons/doc_icon.png" style="width: 50px; height: 50px;"/><br/><span class="custom-wrap" style="max-width: 60px;">{{e.file}}</span>
                                        </a>
                                        <a href="uploads/attachment/{{e.file}}" target="_blank" title="{{e.file}}" ng-if="e.type == 'xlsx'">
                                            <img src="uploads/study_material/icons/excel_icon.png" style="width: 50px; height: 50px;"/><br/><span class="custom-wrap" style="max-width: 60px;">{{e.file}}</span>
                                        </a>
                                        <a href="uploads/attachment/{{e.file}}" target="_blank" title="{{e.file}}" ng-if="e.type == 'ppt || pptx'">
                                            <img src="uploads/study_material/icons/ppt_icon.png" style="width: 50px; height: 50px;"/><br/><span class="custom-wrap" style="max-width: 60px;">{{e.file}}</span>
                                        </a>
                                    </span>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="col-12 col-md-6 p-l-0">
                                    <div class="form-group">
                                        <div id="message" style="display: none;">
                                            <span class="text-danger" style="font-weight: bold;"><?php echo lang('not_allowed_file');?></span>
                                        </div>
                                        <label class="control-label"><?= lang("lbl_attachments"); ?></label></br>
                                        <label class="btn btn-info" style="border-radius: 5px;">
                                            <i class="fa fa-paperclip"></i>&nbsp;<?= lang("choese_file_here"); ?><input type="file" nv-file-select="" uploader="uploader" class="form-control" id="uploadfile" style="display: none;"/>
                                         </label>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-6 p-0" ng-show="uploader.queue.length>0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th><?php echo lang("file_name"); ?></th>
                                                    <th><?php echo lang("progress"); ?></th>
                                                    <th><?php echo lang("lbl_status"); ?></th>
                                                    <th class="text-center" style="width: 150px;"><?php echo lang("th_action"); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr ng-repeat="item in uploader.queue">
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
                                                    <td nowrap style="width: 150px;" class="text-center">
                                                        <button type="button" class="btn btn-success btn-xs btn-circle" ng-click="item.upload()" ng-disabled="item.isReady || item.isUploading || item.isSuccess">
                                                            <span class="glyphicon glyphicon-upload"></span>
                                                        </button>
                                                        <button type="button" class="btn btn-warning btn-xs btn-circle" ng-click="item.cancel()" ng-disabled="!item.isUploading">
                                                            <span class="glyphicon glyphicon-ban-circle"></span>
                                                        </button>
                                                        <button type="button" id="remove" class="btn btn-danger btn-xs btn-circle" ng-click="item.remove(); removeFromAttachmets(item)">
                                                            <span class="glyphicon glyphicon-trash"></span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="form-footer" style="padding-left: 7.5px; padding-right: 7.5px;">
                            <button type="submit" class="btn btn-primary"><?php echo lang("save_settings"); ?></button>
                            <a href="javascript:void(0)" value="<?php echo $this->session->userdata('userdata')['sh_id']; ?>,online_admission/softDeleteSettings" class="btn btn-danger sa-warning"><?php echo lang("delete_settings"); ?></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- .right-sidebar -->
        <!-- Include yout right sidebar here -->
        <!-- /.right-sidebar -->
    </div>
<?php include(APPPATH . "views/inc/footer.php"); ?>
<script type="text/javascript">
    $(document).ready(function(){
        $('.textarea_editor').wysihtml5();
    });
</script>