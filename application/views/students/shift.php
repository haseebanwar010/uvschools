<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<?php
$UserData = $this->session->userdata('userdata');
$role_id = $UserData['role_id'];
?>
<div>
    <!-- Page Content -->
    <div id="page-wrapper" ng-controller="stdShiftController">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?php echo lang('shift_students'); ?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="#"><?php echo lang('crumb_dashboard') ?></a></li>
                        <li><a href="#"><?php echo lang('menu_students') ?></a></li>
                        <li class="active"><?php echo lang('shift_students'); ?></li>
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
            <div class="hint"><?php echo lang('help_shift_students'); ?></div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="white-box well well-sm"  id="std_search_filter">
                        <form class="form-material" name="stdShiftSearch" ng-submit="fetchAllStdsOfClassAndBatch(stdShiftSearch.$valid)" novalidate="">
                            <div class="row form-material">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo lang('lbl_class') ?></label>
                                        <select class="form-control" ng-model="course" ng-init="course=''" required="" ng-change="fetchClassBatches(course)">
                                            <option value=""><?php echo lang('select_course') ?></option>
                                            <?php if (count($classes) > 0) { ?>
                                                <?php foreach ($classes as $cls) { ?>
                                                    <option value="<?= $cls->id; ?>"><?= $cls->name; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6" id="dropdownBatches">
                                    <div class="form-group">
                                        <label><?php echo lang('lbl_batch') ?></label>
                                        <select class="form-control" ng-model="batch" ng-init="batch=''" required="">
                                            <option value=""><?php echo lang('select_batch') ?></option>
                                            <option ng-repeat="batch in batches" value="{{batch.id}}">{{batch.name}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary btn-sm"><?php echo lang('search') ?></button>
                                <!--<a href="" class="btn btn-info btn-sm">Import Students</a>-->
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!--.row-->
            <div class="row" id="stdTableContianer" ng-show="check">
                <div class="col-md-12">
                    <div class="white-box">
                        <div class="table-responsive">
                            <table class="myTableNew display nowrap text-center" cellspacing="0" width="100%">
                                <tfoot class="text-center">
                                    <th></th>
                                    <th><?php echo lang("imp_sr");?></th>
                                    <th><?php echo lang("lbl_avatar");?></th>
                                    <th><?php echo lang("lbl_name");?></th>
                                    <th><?php echo lang("lbl_guardian");?></th>
                                    <th><?php echo lang("lbl_rollno");?></th>
                                    <th><?php echo lang("lbl_teacher");?></th>
                                    <th><?php echo lang("lbl_action");?></th>
                                </tfoot>
                            </table>
                            <div class="col-xs-12 col-sm-12 col-md-12" style="border-top: 1px solid #e3e3e3;margin-top: 15px;padding-left: 0px;padding-top: 10px;">
                                <button class="btn btn-primary" disabled="true" id="shift_btn" ng-show="std_count != 0" data-toggle="modal" data-target="#shiftModal" ng-click="populate_modal()" ng-disabled="temp.length==0"><?php echo lang('btn_shift'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/.row-->
        </div>

        <div class="modal fade" id="shiftModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="panel panel-primary">
                        <div class="panel-heading">{{message}}</div>
                        <div class="panel-body">
                            <form name="shiftForm" ng-submit="shiftStudents(shiftForm.$valid)" novalidate="" class="">
                                <div class="form-body">
                                    <div class="col-md-12">
                                        <small class="text-info">{{level_msg}}</small>
                                        <div class="form-group">
                                            <label><?php echo lang('new_class'); ?></label>
                                            <select class="form-control" ng-model="new_course" ng-init="new_course=''" required="" ng-change="fetchNewClassBatches()">
                                                <option value=""><?php echo lang('select_new_class'); ?></option>
                                                <option ng-repeat="class in new_classes" value="{{class.id}}">{{class.name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div class="col-md-12" id="newdropdownBatches">
                                        <div class="form-group">
                                            <label><?php echo lang('new_batch'); ?></label>
                                            <select class="form-control" ng-model="new_batch" ng-init="new_batch=''" required="">
                                                <option value=""><?php echo lang('select_new_batch'); ?></option>
                                                <option ng-repeat="batch in new_batches" value="{{batch.id}}">{{batch.name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?php echo lang('reason'); ?></label>
                                            <textarea class="form-control" rows="4" ng-model="reason"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="pull-right m-r-5">
                                    <button type="button" class="btn btn-info waves-effect text-left" data-dismiss="modal"><?php echo lang('lbl_close'); ?></button>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i><?php echo lang('btn_shift'); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--./row-->
        <!--page content end here-->
    </div>
</div>
<script>
    $(document).on('change', 'input[name="checked_std[]"]', function () {
        var current = $(this);
        if (current.prop("checked") == true) {
            customscope.$apply(function () {
                customscope.temp.push(parseInt(current.val()));
            });
        } else if (current.prop("checked") == false) {
            customscope.$apply(function () {
                position = customscope.temp.indexOf(parseInt(current.val()));

                if (~position)
                    customscope.temp.splice(position, 1);
            });
        }
    });
</script>
<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>