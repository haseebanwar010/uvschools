<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('crumb_messages');?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="#"><?php echo lang('lbl_dashboard');?></a></li>
                    <li class="active"><?php echo lang('crumb_all_messages');?></li>

                </ol>
            </div>
        </div>
        <!-- /.row -->
       
        <!-- row -->
        <div class="hint"><?php echo lang('help_messages'); ?></div>
        <div class="row" ng-controller="inbox" ng-init='init();initClasses();'>
            <!-- Left sidebar -->
            <div class="col-md-12">
                <div class="white-box">
                    <!-- row -->
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 inbox-panel">
                            <div><a href="#" class="btn btn-primary btn-block  waves-effect waves-light"  data-toggle="modal" data-target="#compose" id="composeButton" ng-click="resetCompose()"><?php echo lang('btn_compose');?></a>
                                <div class="list-group mail-list m-t-20"> <a href="javascript:void(0)" class="list-group-item active" id="inboxButton" ng-click="inbox()"><?php echo lang('lbl_inbox') ?><span class="label label-rouded label-success pull-right" ng-if="unread != 0">{{unread}}</span></a>
                                    <a href="javascript:void(0)" class="list-group-item" id="sentButton" ng-click="sentM()"><?php echo lang('lbl_sent') ?></a>
                                    <a href="javascript:void(0)" class="list-group-item" id="trashButton" ng-click="trash()"><?php echo lang('btn_archive');?><!-- <span class="label label-rouded label-default pull-right">{{trash_count}}</span> --></a>

                                </div>

                                <hr class="m-t-5">
                                <div class="list-group b-0 mail-list">
                                    <a href="javascript:void(0)" class="list-group-item"><span class="fa fa-circle text-info m-r-10"></span><?php echo lang('lbl_admin');?></a> 
                                    <a href="javascript:void(0)" class="list-group-item"><span class="fa fa-circle text-warning m-r-10"></span><?php echo lang('menu_employee');?></a> 
                                    <a href="javascript:void(0)" class="list-group-item"><span class="fa fa-circle text-purple m-r-10"></span><?php echo lang('lbl_student');?></a>  
                                    <a href="javascript:void(0)" class="list-group-item"><span class="fa fa-circle text-success m-r-10"></span><?php echo lang('lbl_parent');?></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12 mail_listing">

                            <div class="inbox-center">
                                <div class="table-responsive hidden-xs">
                                    <table class="table table-hover table-striped">
                                        <tbody>
                                            <tr ng-show="conversations.length == 0"><td colspan="4"><?php echo lang('no_messages');?></td></tr>
                                            <tr ng-show="conversations.length != 0" ng-class="{true: 'unread', false: ''}[con.is_read == 0]" ng-repeat="con in conversations">
                                                <td style="width: 20px;"> 
                                                    <span ng-class="{'fa fa-circle text-warning m-r-10':con.role_id == 4, 'fa fa-circle text-info m-r-10':con.role_id == 1 ,'fa fa-circle text-success m-r-10':con.role_id == 2,'fa fa-circle text-purple m-r-10':con.role_id == 3}" ></span> 
                                                </td>
                                                <td> 
                                                    <a href="<?php echo site_url('messages/view');?>/{{con.id}}" >
                                                        <span ng-if="con.att>0"><i class='fa fa-paperclip fa-lg'></i></span>
                                                        <span>{{ con.subject | limitTo: 50 }}{{con.subject.length > 50 ? '...' : ''}}</span><br>
                                                        <span style="font-size: 10px;"><strong><?php echo lang('lbl_tbl_sender');?>: </strong>{{con.name}}</span><br>
                                                        <span style="font-size: 10px;">{{con.created_at}}</span>
                                                    </a>
                                                </td>
                                                <!-- <td style="width:100px;">{{con.last_time}}</td> -->
                                                <td style="width:100px;"> 
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle waves-effect waves-light m-r-5" data-toggle="dropdown" aria-expanded="false"> <?php echo lang('lbl_action');?> <b class="caret"></b> </button>
                                                        <ul class="dropdown-menu" role="menu">
                                                            <li><a href="javascript:void(0)"  data-toggle="modal" data-target="#deleteConversation" ng-click="deleteConId(con.id)"><?php echo lang('btn_archive');?></a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row p-0 visible-xs"  ng-class="{true: 'unread', false: ''}[con.is_read == 0]" ng-repeat="con in conversations" style="border-bottom: 1px solid #eee;">
                                    <div class="col-md-12 col-sm-12 col-xs-12" >
                                        <div class="col-md-6 col-sm-6 col-xs-6" >
                                            <a href="<?php echo site_url('messages/view');?>/{{con.id}}" style="width: 80%;">
                                                <!-- {{ con.subject | limitTo: 50 }}{{con.subject.length > 50 ? '...' : ''}} -->
                                                <span>{{ con.subject | limitTo: 50 }}{{con.subject.length > 50 ? '...' : ''}}</span>
                                                <br>
                                                <span style="font-size: 10px;"><strong><?php echo lang('lbl_tbl_sender');?>: </strong>{{con.name}} </span>
                                                <br>
                                                <span style="font-size: 10px;">{{con.created_at}}</span>
                                            </a>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <p>
                                                <!-- <span>{{con.last_time}}</span> -->
                                                <span ng-if="con.att>0"><i class='fa fa-paperclip fa-lg'></i></span>
                                            </p>
                                            <div class="btn-group pull-right" style="margin-top: -40px;position: relative;padding-left: 87%;padding-top: -20%;display: inline;">
                                                <button type="button" class="pull-right btn btn-primary btn-sm dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false"><b class="caret"></b> </button>
                                                <ul class="dropdown-menu" role="menu" style="    padding: 0px;margin: 0px;width: 20px;">
                                                    <li><a href="javascript:void(0)"  data-toggle="modal" data-target="#deleteConversation" ng-click="deleteConId(con.id)"><?php echo lang('btn_archive');?></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="inbox-center" id="trashDiv" style="display: none">
                                <div class="table-responsive hidden-xs">
                                    <table class="table table-hover table-striped">

                                        <tbody>
                                            <tr ng-show="trashes.length == 0"><td colspan="4"><?php echo lang('no_messages');?></td></tr>
                                            <tr  ng-repeat="trash in trashes">

                                                <td style="width: 20px;"> 
                                                    <span ng-class="{'fa fa-circle text-warning m-r-10':trash.role_id == 4, 'fa fa-circle text-info m-r-10':trash.role_id == 1 ,'fa fa-circle text-success m-r-10':trash.role_id == 2,'fa fa-circle text-purple m-r-10':trash.role_id == 3}" ></span> 
                                                </td>


                                                <td> 
                                                    <a href="<?php echo site_url('messages/view');?>/{{trash.id}}" >
                                                        <span ng-if="trash.att>0"><i class='fa fa-paperclip fa-lg'></i></span>
                                                        <span>{{ trash.subject | limitTo: 50 }}{{trash.subject.length > 50 ? '...' : ''}}</span>
                                                        <br>
                                                        <span style="font-size: 10px;"><strong><?php echo lang('lbl_tbl_sender');?>: </strong>{{trash.name}} </span>
                                                        <br>
                                                        <span style="font-size: 10px;">{{trash.created_at}}</span>
                                                    </a>
                                                </td>
                                                <!-- <td style="width:100px;">{{con.last_time}}</td> -->
                                                <td style="width:100px;"> 
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle waves-effect waves-light m-r-5" data-toggle="dropdown" aria-expanded="false"> <?php echo lang('lbl_action');?> <b class="caret"></b> </button>
                                                        <ul class="dropdown-menu" role="menu">
                                                            <li><a href="javascript:void(0)"  data-toggle="modal" data-target="#restoreConversation" ng-click="restoreConId(trash.id)"><?php echo lang('btn_restore');?></a></li>

                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row p-0 visible-xs"  ng-class="{true: 'unread', false: ''}[trash.is_read == 0]" ng-repeat="trash in trashes" style="border-bottom: 1px solid #eee;">
                                    <div class="col-md-12 col-sm-12 col-xs-12" >
                                        <div class="col-md-6 col-sm-6 col-xs-6" >
                                            <a href="<?php echo site_url('messages/view');?>/{{trash.id}}" style="width: 80%;">
                                                <!-- {{ trash.subject | limitTo: 50 }}{{trash.subject.length > 50 ? '...' : ''}} -->
                                                <span>{{ trash.subject | limitTo: 50 }}{{trash.subject.length > 50 ? '...' : ''}}</span>
                                                <br>
                                                <span style="font-size: 10px;"><strong><?php echo lang('lbl_tbl_sender');?>: </strong>{{trash.name}} </span>
                                                <br>
                                                <span style="font-size: 10px;">{{trash.created_at}}</span>
                                            </a>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <p>
                                                <!-- <span>{{trash.last_time}}</span> -->
                                                <span ng-if="trash.att>0"><i class='fa fa-paperclip fa-lg'></i></span>
                                            </p>
                                            <div class="btn-group pull-right" style="margin-top: -40px;position: relative;padding-left: 87%;padding-top: -20%;display: inline;">
                                                <button type="button" class="pull-right btn btn-primary btn-sm dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false"><b class="caret"></b> </button>
                                                <ul class="dropdown-menu" role="menu" style="    padding: 0px;margin: 0px;width: 20px;">
                                                    <li><a href="javascript:void(0)"  data-toggle="modal" data-target="#restoreConversation" ng-click="restoreConId(trash.id)"><?php echo lang('btn_restore');?></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="inbox-center" id="sentDiv" style="display: none">
                                <div class="table-responsive hidden-xs">
                                    <table class="table table-hover table-striped">

                                        <tbody>
                                            <tr ng-show="sentMessages.length == 0"><td colspan="4"><?php echo lang('no_messages');?></td></tr>
                                            <tr  ng-repeat="temp in sentMessages">

                                                <td style="width: 20px;"> 
                                                    <span ng-class="{'fa fa-circle text-warning m-r-10':temp.role_id == 4, 'fa fa-circle text-info m-r-10':temp.role_id == 1 ,'fa fa-circle text-success m-r-10':temp.role_id == 2,'fa fa-circle text-purple m-r-10':temp.role_id == 3}" ></span> 
                                                </td>
                                                <td> 
                                                    <a href="<?php echo site_url('messages/view');?>/{{temp.id}}" >
                                                        <span ng-if="temp.att>0"><i class='fa fa-paperclip fa-lg'></i></span>
                                                        <span>{{ temp.subject | limitTo: 50 }}{{temp.subject.length > 50 ? '...' : ''}}</span>
                                                        <br>
                                                        <span style="font-size: 10px;"><strong><?php echo lang('lbl_tbl_sender');?>: </strong>{{temp.name}}</span>
                                                        <br>
                                                        <span style="font-size: 10px;">{{temp.created_at}}</span>
                                                    </a>
                                                </td>
                                                <!-- <td style="width:100px;">{{temp.last_time}}</td> -->
                                                <td style="width:100px;"> 
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle waves-effect waves-light m-r-5" data-toggle="dropdown" aria-expanded="false"> <?php echo lang('lbl_action');?> <b class="caret"></b> </button>
                                                        <ul class="dropdown-menu" role="menu">
                                                            <li><a href="javascript:void(0)"  data-toggle="modal" data-target="#deleteConversation" ng-click="deleteConId(temp.id)"><?php echo lang('btn_archive');?></a></li>

                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>


                                        </tbody>
                                    </table>
                                </div>

                                <div class="row p-0 visible-xs"  ng-class="{true: 'unread', false: ''}[temp.is_read == 0]" ng-repeat="temp in sentMessages" style="border-bottom: 1px solid #eee;">
                                    <div class="col-md-12 col-sm-12 col-xs-12" >
                                        <div class="col-md-6 col-sm-6 col-xs-6" >
                                            <a href="<?php echo site_url('messages/view');?>/{{temp.id}}" style="width: 80%;">
                                                <!-- {{ temp.subject | limitTo: 50 }}{{temp.subject.length > 50 ? '...' : ''}} -->
                                                <span>{{ temp.subject | limitTo: 50 }}{{temp.subject.length > 50 ? '...' : ''}}</span>
                                                <br>
                                                <span style="font-size: 10px;"><strong><?php echo lang('lbl_tbl_sender');?>: </strong>{{temp.name}} </span><br>
                                                <span style="font-size: 10px;">{{temp.created_at}}</span>
                                            </a>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <p>
                                                <!-- <span>{{temp.last_time}}</span> -->
                                                <span ng-if="temp.att>0"><i class='fa fa-paperclip fa-lg'></i></span>
                                            </p>
                                            <div class="btn-group pull-right" style="margin-top: -40px;position: relative;padding-left: 87%;padding-top: -20%;display: inline;">
                                                <button type="button" class="pull-right btn btn-primary btn-sm dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false"><b class="caret"></b> </button>
                                                <ul class="dropdown-menu" role="menu" style="    padding: 0px;margin: 0px;width: 20px;">
                                                    <li><a href="javascript:void(0)"  data-toggle="modal" data-target="#deleteConversation" ng-click="deleteConId(temp.id)"><?php echo lang('btn_archive');?></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- /.row -->
            <?php

            $UserData = $this->session->userdata('userdata');
            $role_id = $UserData['role_id'];
            $arr = $UserData['persissions'];

            $array = json_decode($arr);

            if (isset($array)) {
                $messageEmployee = $messageParent = $messageStudent = 0;
                foreach ($array as $key => $value) {
                    if (in_array('messages-employee', array($value->permission)) && $value->val == 'true') {
                        $messageEmployee = 1;
                    }
                    if (in_array('messages-parent', array($value->permission)) && $value->val == 'true') {
                        $messageParent = 1;
                    }
                    if (in_array('messages-student', array($value->permission)) && $value->val == 'true') {
                        $messageStudent = 1;
                    }
                }
            } ?>

                    <!-- Compose Modal -->
                    <div class="modal fade bs-example-modal-lg" id="compose" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">


                            <div class="panel panel-primary">
                                <div class="panel-heading"><?php echo lang('compose_new');?>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="panel-body">
                                <form action="#" name="message">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_select_role') ?></label>
                                                    <select class="form-control" id="roleSelect" ng-model="sModel.level" ng-init="sModel.level='all'">
                                                        <?php if($role_id == '1'){?>
                                                            <option value="all"><?php echo lang('option_all') ?></option>
                                                            <option value="4"><?php echo lang('menu_employee') ?></option>
                                                            <option value="3"><?php echo lang('lbl_student') ?></option>
                                                            <option value="2"><?php echo lang('lbl_parent') ?></option>
                                                        <?php }else if($role_id == '4'){ ?>
                                                            <option value="1"><?php echo lang('lbl_admin') ?></option>
                                                            <?php if($messageEmployee == '1'){ ?>
                                                                <option value="4"><?php echo lang('menu_employee') ?></option>
                                                            <?php } ?> 
                                                            <?php if($messageStudent == '1'){ ?>
                                                                <option value="3"><?php echo lang('lbl_student') ?></option>
                                                            <?php } ?> 
                                                            <?php if($messageParent == '1'){ ?>
                                                                <option value="2"><?php echo lang('lbl_parent') ?></option>
                                                            <?php } ?>    
                                                         <?php } else if ($role_id == '2') {?>
                                                            <option value="1"><?php echo lang('lbl_admin') ?></option>
                                                            <option value="4"><?php echo lang('menu_employee') ?></option>
                                                        <?php } else if ($role_id == '3') {?>
                                                            <option value="4"><?php echo lang('lbl_teacher') ?></option>
                                                        <?php } ?>
                                                    </select>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" ng-show="sModel.level==3">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">Class</label>
                                                    <select class="form-control" style="width: 100%;" ng-model="sModel.class" ng-change="getSections_upload()">
                                                        <option value="" disabled="">--Select Class--</option>
                                                        <option ng-repeat="class in study.classes" value="{{class.id}}">{{class.name}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">Section</label>
                                                    <select class="form-control" style="width: 100%;" ng-model="sModel.batch" ng-change="getStudentsForMsg();">
                                                        <option value="" disabled="">--Select Section--</option>
                                                        <option ng-repeat="batch in study.batches" value="{{batch.id}}">{{batch.name}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12" id="select2-students">
                                                <div class="form-group">
                                                    <label class="control-label">Students</label>
                                                    <div class="checkbox checkbox-info">
                                                        <input id="student_selectall" ng-disabled="AssStudents.length==0 || AssStudents==undefined" type="checkbox" name="studentSelectAll" ng-model="studentSelectAll">
                                                        <label for="student_selectall">Select All</label>
                                                    </div>
                                                    <div id="studentsDiv">
                                                        <select class="form-control yasir-ann-select2" style="width: 100%;" multiple="multiple" ng-model="message.to" name="grade-type" id="select2-students">
                                                            <option ng-repeat="student in AssStudents" value="{{student.id}}">{{student.name}}</option>
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
                                        <div class="row" ng-show="sModel.level!=3">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_to');?></label>
                                                    <select class="form-control js-data-example-ajax" id="reci" ng-model="message.to" style="width: 100%" multiple="multiple" ></select>

                                                </div>
                                            </div>
                                        </div>
                                        <!--/span-->
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
                                                        <div
                                                        class="dropzone"
                                                        id="my-awesome-dropzone" dropzone="dropzoneConfig"></div>
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
                                                    <button type="button" class="btn btn-primary" id="saveButton" ><?php echo lang('btn_send');?>
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
        </div>
        <div id="deleteConversation" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><?php echo lang('archive_confirmation');?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <p><?php echo lang('archive_message');?></p>

                        <input type="hidden" id="bank_delete_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang('btn_close_bank') ?></button>
                        <button type="button" class="btn btn-danger waves-effect waves-light" id="confirm_delete" ng-click="deleteCon()"><?php echo lang('btn_archive');?></button>
                    </div>
                </div>
            </div>
        </div>
        <div id="restoreConversation" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><?php echo lang('restore_confirmation');?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <p><?php echo lang('restore_message');?></p>

                        <input type="hidden" id="bank_delete_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang('btn_close_bank') ?></button>
                        <button type="button" class="btn btn-danger waves-effect waves-light" id="confirm_delete" ng-click="restoreCon()"><?php echo lang('btn_restore');?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.row -->

<!-- .row -->







<!--page content end-->

</div>
<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>


<script type="text/javascript">
    $(document).ready(function(){




      $('.textarea_editor').wysihtml5();
      $(".js-data-example-ajax").select2({
          ajax: {
            url: '<?php echo site_url('messages/getRecipients')?>',
            dataType: 'json',

            data: function (params) {
              return {
        q: params.term, // search term
        page: params.page,
        role: $('#roleSelect').val()
    };
},
processResults: function (data, params) {
      // parse the results into the format expected by Select2
      // since we are using custom formatting functions we do not need to
      // alter the remote JSON data, except to indicate that infinite
      // scrolling can be used
      params.page = params.page || 1;

      return {
        results: data.rec,
        pagination: {
          more: (params.page * 30) < data.total_count
      }
  };
},
cache: true
},
  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
  minimumInputLength: 1,
  templateResult: formatRepo,
  templateSelection: formatRepoSelection
});

      function formatRepo (repo) {
          if (repo.loading) {
            return repo.text;
        }
        var markup;
        if(repo.role_id == 1){
          markup = "<div class='row' style='width:99%;'>" +
          "<div class='col-md-1'>" +"<img class='img-responsive' src='<?php echo base_url() ?>uploads/user/" +repo.avatar + "'> " + "</div>" +
          "<div class='col-md-11'>" +"<div class='row'>"+
          "<div class='col-md-12'><b>" + repo.user_name.toUpperCase()+ "</b></div>"+
          "</div>"+ "<div class='row'>"+
          "<div class='col-md-12'>Admin</div>"+
          "</div>"+ "</div>" ;
      }
      else if(repo.role_id == 2){
          markup = "<div class='row' style='width:99%;'>" +
          "<div class='col-md-1'>" +"<img class='img-responsive' src='<?php echo base_url() ?>uploads/user/" +repo.avatar + "'> " + "</div>" +
          "<div class='col-md-11'>" +"<div class='row'>"+
          "<div class='col-md-12'><b>" + repo.user_name.toUpperCase()+ "</b></div>"+
          "</div>"+ "<div class='row'>"+
          "<div class='col-md-12'>Parent"+"</div>"+
          "</div>"+ "</div>" ;
      }

      else if(repo.role_id == 3){
          markup = "<div class='row' style='width:99%;'>" +
          "<div class='col-md-1'>" +"<img class='img-responsive' src='<?php echo base_url() ?>uploads/user/" +repo.avatar + "'> " + "</div>" +
          "<div class='col-md-11'>" +"<div class='row'>"+
          "<div class='col-md-12'><b>" + repo.user_name.toUpperCase()+ "</b></div>"+
          "</div>"+ "<div class='row'>"+
          "<div class='col-md-12'>Student  Class:"+ repo.class_name + "  Section:"+repo.batch_name+"</div>"+
          "</div>"+ "</div>" ;
      }

      else if(repo.role_id == 4){
          markup = "<div class='row' style='width:99%;'>" +
          "<div class='col-md-1'>" +"<img class='img-responsive' src='<?php echo base_url() ?>uploads/user/" +repo.avatar + "'> " + "</div>" +
          "<div class='col-md-11'>" +"<div class='row'>"+
          "<div class='col-md-12'><b>" + repo.user_name.toUpperCase()+ "</b></div>"+
          "</div>"+ "<div class='row'>"+
          "<div class='col-md-12'>Employee  Department:"+ repo.department + "  Category:"+repo.category+"</div>"+
          "</div>"+ "</div>" ;
      }






      return markup;
  }

  function formatRepoSelection (repo) {
      return repo.user_name;
  }
})

    $('#composeButton').click(function(){

        $('#saveButton').prop('disabled',false);

    });
</script>