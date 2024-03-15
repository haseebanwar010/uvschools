<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<?php 
$UserData = $this->session->userdata('userdata');
$role_id = $UserData['role_id'];
?>
<div>
    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?php echo lang('all_students') ?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="#"><?php echo lang('crumb_dashboard') ?></a></li>
                        <li><a href="#"><?php echo lang('menu_students') ?></a></li>
                        <li class="active"><?php echo lang('all_students') ?></li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->

            <?php $error = $this->session->flashdata('alert'); if(!empty($error)) { ?>
                <div class="alert alert-dismissable <?php if($this->session->flashdata('alert')['status'] == 'error') { echo 'alert-danger'; } else {echo 'alert-success'; }?>"> 
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>     
                    <?= $this->session->flashdata("alert")['message']; ?> 
                </div>
            <?php } ?>
            <div class="hint"><?php echo lang('help_std_all'); ?></div>
            <div class="row" ng-controller="stdFilterController" ng-init="fetchAllStdsOfClassAndBatch(-1,-1,-1,true)">
                <div class="col-sm-12">
                    <div class="white-box well well-sm"  id="std_search_filter">
                        <div class="row form-material">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><?php echo lang('lbl_class') ?></label>
                                    <select class="form-control" ng-model="course" ng-init="course='all'" required="" ng-change="fetchClassBatches(course)">
                                        <option value="all"><?php echo lang('option_all') ?></option>
                                        <?php if (count($classes) > 0) { foreach ($classes as $cls) { ?>
                                            <option value="<?= $cls->id; ?>"><?= $cls->name; ?></option>
                                        <?php } } else { ?>
                                            <option><?php echo lang('no_record') ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-4" id="dropdownBatches">
                                <div class="form-group">
                                    <label><?php echo lang('lbl_batch') ?></label>
                                    <select class="form-control" ng-model="batch" ng-init="batch='all'" required="">
                                        <option value="all"><?php echo lang('option_all') ?></option>
                                        <option ng-repeat="batch in batches" value="{{batch.id}}">{{batch.name}}</option>
                                    </select>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><?php echo lang('lbl_status') ?></label>
                                    <select class="form-control" ng-model="status" ng-init="status='all'" required="">
                                        <option value="all"><?php echo lang('option_all') ?></option>
                                        <option value="1"><?php echo lang('option_active') ?></option>
                                        <option value="0"><?php echo lang('option_disabled') ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="button" ng-click="fetchAllStdsOfClassAndBatch(course, batch, status)" class="btn btn-primary btn-sm"><?php echo lang('search') ?></button>
                            <!--<a href="" class="btn btn-info btn-sm">Import Students</a>-->
                        </div>
                    </div>
                </div>
                     <!-- Compose Modal  #04c-->
     <div class="modal fade bs-example-modal-lg" id="compose" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" >
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">


                            <div class="panel panel-primary">
                                <div class="panel-heading"><?php echo lang('compose_new');?>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="panel-body">


                                <form action="#" name="message">
                                    <input type="hidden" id="hidden" >
                                    <div class="form-body">
                            
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_subject_msg');?></label>
                                                    <input type="text" id="email-subject" ng-model="message.subject"  
                                                    class="form-control"
                                                    ></div>
                                                </div></div>
                                                <!--/span-->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_message');?></label>
                                                            <textarea class="textarea_editor form-control" ng-model="message.text" rows="5"  ></textarea>


                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="dropzone" id="my-awesome-dropzone" dropzone="dropzoneConfigsFromStdSide"></div>
                                                    </div>
                                                </div>
                                                <div class="alert" style="margin-top:6px" id="message_alert" style="display: none"></div>


                                                <div class="row pull-right" style="margin-top: 12px">
                                                    <div style="margin-right: 8px">
                                                        <button type="button" class="btn btn-default"
                                                        data-dismiss="modal"><?php echo lang('btn_cancel');?>
                                                    </button>
                                                </div>
                                                <div>
                                                    <button type="button"  class="btn btn-primary" id="saveButton" ><?php echo lang('btn_send');?>
                                                </button>
                                            </div>
                                        </div></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!--/compose modal-->
            </div>

            <div class="row" id="stdTableContianerError" style="display:none;">
                <div class="col-sm-12">
                    <div class="white-box">
                        <span class="text-danger"><?php echo lang("no_record"); ?></span>
                    </div>
                </div>
            </div>
            
            <!--.row-->
            <div class="row" id="stdTableContianer" style="display:none;">
                <div class="col-sm-12">
                    <div class="white-box">
                        <div class="table-responsive">
                            <table class="myTable display nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo lang('imp_sr') ?></th>
                                        <th><?php echo lang('lbl_avatar') ?></th>
                                        <th><?php echo lang('lbl_name') ?></th>
                                        <th><?php echo lang('lbl_guardian') ?></th>
                                        <th><?php echo lang('lbl_phone') ?></th>
                                        <th><?php echo lang('lbl_class') ?></th>
                                        <th><?php echo lang('lbl_batch') ?></th>
                                        <th><?php echo lang('subject_groups') ?></th>
                                        <th><?php echo lang('lbl_rollno') ?></th>
                                        <th><?php echo lang('lbl_teacher') ?></th>
                                        <th><?php echo lang('lbl_status') ?></th>
                                        <th><?php echo lang('lbl_action') ?></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th><?php echo lang('imp_sr') ?></th>
                                        <th><?php echo lang('lbl_avatar') ?></th>
                                        <th><?php echo lang('lbl_name') ?></th>
                                        <th><?php echo lang('lbl_guardian') ?></th>
                                        <th><?php echo lang('lbl_phone') ?></th>
                                        <th><?php echo lang('lbl_class') ?></th>
                                        <th><?php echo lang('lbl_batch') ?></th>
                                        <th><?php echo lang('subject_groups') ?></th>
                                        <th><?php echo lang('lbl_rollno') ?></th>
                                        <th><?php echo lang('lbl_teacher') ?></th>
                                        <th><?php echo lang('lbl_status') ?></th>
                                        <th><?php echo lang('lbl_action') ?></th>
                                    </tr>
                                </tfoot>
                                <tbody id="myTableBody2">
                                    <?php if (count($students) > 0) {
                                        $count = 1;
                                        foreach ($students as $std) { ?>
                                            <tr>
                                                <td><?php echo $count; ?></td>
                                                <td><img src="<?php echo base_url() ?>uploads/user/<?php echo $std->avatar; ?>" alt="user-img" class="img-circle" style="height: 60px;width: 60px"></td>
                                                <td><?php echo $std->name; ?></td>
                                                <td><?php echo $std->father_name; ?></td>
                                                <td><?php echo $std->contact; ?></td>
                                                <td><?php echo $std->class_name; ?></td>
                                                <td><?php echo $std->batch_name; ?></td>
                                                <td><?php echo $std->group_name; ?></td>
                                                <td><?php echo $std->rollno; ?></td>
                                                <td><?php echo $std->teacher_name; ?></td>
                                                <?php if ($std->status == 1) { ?>
                                                    <td><?php echo lang('option_active') ?></td>
                                                <?php } else { ?>
                                                    <td><?php echo lang('option_disabled') ?></td>
                                                <?php } ?>
                                                <td>
                                                    <?php $ci = & get_instance();
                                                    $arr = $ci->session->userdata("userdata")['persissions'];
                                                    $array = json_decode($arr);
                                                    if(isset($array)){
                                                        $view = 0;
                                                        $edit = 0;
                                                        foreach ($array as $key => $value) {
                                                            if(in_array('students-edit',array($value->permission)) && $value->val == 'true'){
                                                                $edit = 1;
                                                            }
                                                            if(in_array('students-view',array($value->permission)) && $value->val == 'true'){
                                                                $view = 1;
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <?php if($role_id == '4' && isset($edit) || isset($view)){
                                                        
                                                        if($edit == '1' || $view == '1'){?> 
                                                            <?php if($view == '1') {?>
                                                                <a type="button" href="<?php echo base_url(); ?>students/view/<?php echo encrypt($std->id); ?>" target="_blank" class="btn btn-success btn-circle" ><i class="fa fa-eye"></i></a>
                                                            <?php } if($edit == '1'){?> 
                                                                <a type="button" href="<?php echo base_url(); ?>students/edit/<?php echo $std->id; ?>/<?php echo $std->class_id; ?>" target="_blank" class="btn btn-info btn-circle" ><i class="fa fa-pencil"></i></a>
                                                            <?php }?>

                                                        <?php }} else if($role_id ==='1'){ ?> 
                                                            <a type="button" href="<?php echo base_url(); ?>students/view/<?php echo encrypt($std->id); ?>" target="_blank" class="btn btn-success btn-circle" ><i class="fa fa-eye"></i></a>        
                                                            <a type="button" href="<?php echo base_url(); ?>students/edit/<?php echo $std->id; ?>" target="_blank" class="btn btn-info btn-circle" ><i class="fa fa-pencil"></i></a>
                                                            <a href="javascript:void(0)" value="<?php echo encrypt($std->id); ?>,students/delete" class="sa-warning btn btn-danger btn-circle text-white"><i class="fa  fa-trash-o"></i></a>
                                                        <?php }?>
                                                    </td>
                                                </tr>
                                                <?php $count++;} } else { ?>
                                                    <tr><td colspan="6"><?php echo lang('no_record') ?></td></tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/.row-->
                    </div>
                    <!--./row-->
                    <!--page content end here-->
                </div>
            </div>
            <!-- /.container-fluid -->

            <script>
                
                $( document ).ready(function() {
                   $('.textarea_editor').wysihtml5();
                });
                 
            </script>
            <?php include(APPPATH . "views/inc/footer.php"); ?>