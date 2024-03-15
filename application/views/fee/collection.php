<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<style type="text/css">
    .disabled{
        background-color: grey;
    }
    .bg-color-change{
        background-color: #ffc8c8 !important;
    }
</style>

<!-- Page Content -->
<div id="page-wrapper" ng-controller="feeCollectionController">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('crumb_fee_collection') ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="#"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li><a href="#"><?php echo lang('crumb_fee') ?></a></li>
                    <li class="active"><?php echo lang('crumb_fee_collection') ?></li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <?php
        if ($this->session->userdata("userdata")["role_id"] == EMPLOYEE_ROLE_ID) {
            $ci = & get_instance();
            $arr = $ci->session->userdata("userdata")['persissions'];
            $array = json_decode($arr);
            if (isset($array)) {
                $fee_coll = $view_coll_detail = $fee_exemption = $fee_disable = $fee_discounts = 0;
                foreach ($array as $key => $value) {
                    if (in_array('collection-allow', array($value->permission)) && $value->val == 'true') {
                        $fee_coll = 1;
                    }
                    if (in_array('view-collection', array($value->permission)) && $value->val == 'true') {
                        $view_coll_detail = 1;
                    }
                    if (in_array('fee-exemption', array($value->permission)) && $value->val == 'true') {
                        $fee_exemption = 1;
                    }
                    if (in_array('fee-disable', array($value->permission)) && $value->val == 'true') {
                        $fee_disable = 1;
                    }
                    if (in_array('fee-discounts', array($value->permission)) && $value->val == 'true') {
                        $fee_discounts = 1;
                    }
                }
            }
        }
        ?>
        <div class="hint"><?php echo lang('help_fee_collection'); ?></div>
        <!-- Page Content start here -->

        <!-- start edit fee collection modal -->
        <div class="modal fade" id="feeCollectionEditModel" tabindex="-1" role="dialog" aria-labelledby="feeCollectionEditModel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" id="fee-collection-model-contents">
                    <form name="feeCollectionEditModelForm" ng-submit="collectFeeUpdate(feeCollectionEditModelForm.$valid)" novalidate="" class="form-material">
                        <div class="form-body">
                            <div class="panel panel-primary">
                                <div class="panel-heading"><?= lang("lbl_update_collected_fee_form"); ?></div>
                                <div class="panel-body">

                                    <div class="col-md-12 p-0 m-0">
                                        <div class="col-md-6 p-0 m-0">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_date"); ?></label>
                                                    <input type="text" ng-model="editModel.paid_date" ng-value="editModel.paid_date" class="form-control mydatepicker-autoclose">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_discounted_amount"); ?></label>
                                                    <input type="text" class="form-control" disabled="" ng-value="(editModel.amount-editModel.discount) | number : 2" />
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_discount"); ?></label>
                                                    <input type="text" class="form-control" disabled="" ng-value="editModel.discount" />
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_comment"); ?></label>
                                                    <input type="text" class="form-control" ng-model="comment" />
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 p-0 m-0">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_payable_amount"); ?></label>
                                                    <input type="number" name="editPaidAmount" required="" ng-model="editPaidAmount" max="{{maxEditPaidAmount}}" min="0" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group visible-xs">
                                                    <label><?= lang("lbl_payment_mode"); ?></label>
                                                    <br/>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode3" id="rbcash" ng-model="editModel.mode" value="cash" checked="checked" />
                                                        <label for="rbcash"> <?= lang("lbl_cash"); ?> </label>
                                                    </span>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode3" id="rbcheque" ng-model="editModel.mode" value="cheque" />
                                                        <label for="rbcheque"> <?= lang("lbl_cheque"); ?> </label>
                                                    </span>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode3" ng-model="editModel.mode" id="rbdd" value="dd" />
                                                        <label for="rbdd"> <?= lang("lbl_dd"); ?> </label>
                                                    </span>
                                                </div>

                                                <div class="form-group hidden-xs">
                                                    <label><?= lang("lbl_payment_mode"); ?></label><br/>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode4" id="rbcash" ng-model="editModel.mode" value="cash" checked="" />
                                                        <label for="rbcash"> <?= lang("lbl_cash"); ?> </label>
                                                    </span>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode4" id="rbcheque" ng-model="editModel.mode" value="cheque" />
                                                        <label for="rbcheque"> <?= lang("lbl_cheque"); ?> </label>
                                                    </span>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode4" ng-model="editModel.mode" id="rbdd" value="dd" />
                                                        <label for="rbdd"> <?= lang("lbl_dd"); ?> </label>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group mb-0">
                                                    <div class="checkbox checkbox-info">
                                                        <input id="checkbox4" type="checkbox" name="isSendEmailToParent" ng-model="isSendEmailToParentEdit" />
                                                        <label for="checkbox4"> <?php echo lang("lbl_send_email_to_parent"); ?> </label>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-0">
                                                    <div class="checkbox checkbox-info">
                                                        <input id="checkbox5" type="checkbox" name="isSendSMSToParent" ng-model="isSendSMSToParentEdit" />
                                                        <label for="checkbox5"> <?php echo lang("lbl_send_sms_to_parent"); ?> </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="checkbox checkbox-info">
                                                        <input id="checkbox6" type="checkbox" name="isSendNotificationToParent" ng-model="isSendNotificationToParentEdit" />
                                                        <label for="checkbox6"> <?php echo lang("lbl_send_notification_to_parent"); ?> </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer" style="text-align: right;">
                                    <button type="submit" class="btn btn-primary" ng-disabled="update_loading"><?= lang("lbl_update_collected_fee"); ?></button>
                                    <button type="reset" class="btn btn-default" data-dismiss="modal"><?= lang("btn_cancel"); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- end edit fee collection modal -->

        <div class="modal fade" id="editFeeCollectionModel" tabindex="-1" role="dialog" aria-labelledby="editFeeCollectionModel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" id="fee-collection-model-contents">
                    <form name="editFeeCollectionModelForm" ng-submit="editCollectFee(editFeeCollectionModelForm.$valid)" novalidate="" class="form-material">
                        <div class="form-body">
                            <div class="panel panel-primary">
                                <div class="panel-heading"><?= lang("lbl_fee_form"); ?></div>
                                <div class="panel-body">

                                    <div class="col-md-12 p-0 m-0">
                                        <div class="col-md-6 p-0 m-0">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_date"); ?></label>
                                                    <input type="text" ng-value="feeEditModel.created_at" disabled="" class="form-control mydatepicker-autoclose">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_discounted_amount"); ?></label>
                                                    <input type="text" class="form-control" disabled="" ng-value="feeEditModel.new_discounted_amount" />
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_discount"); ?></label>
                                                    <input type="text" class="form-control" disabled="" ng-value="feeEditModel.discount" />
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_comment"); ?></label>
                                                    <input type="text" class="form-control" ng-value="feeEditModel.comment" ng-model="feeEditModel.comment" />
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 p-0 m-0">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_paid_amount"); ?></label>
                                                    <input type="number" class="form-control" max="{{feeEditModel.new_discounted_amount}}" min="0" required="" ng-model="feeEditModel.paid_amount" />
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group visible-xs">
                                                    <label><?= lang("lbl_payment_mode"); ?></label>
                                                    <br/>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode2" id="rbcash3" ng-model="feeEditModel.mode" value="cash" checked="" />
                                                        <label for="rbcash3"> <?= lang("lbl_cash"); ?> </label>
                                                    </span>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode2" id="rbcheque3" ng-model="feeEditModel.mode" value="cheque" />
                                                        <label for="rbcheque3"> <?= lang("lbl_cheque"); ?> </label>
                                                    </span>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode2" ng-model="feeEditModel.mode" id="rbdd3" value="dd" />
                                                        <label for="rbdd3"> <?= lang("lbl_dd"); ?> </label>
                                                    </span>
                                                </div>

                                                <div class="form-group hidden-xs">
                                                    <label><?= lang("lbl_payment_mode"); ?></label><br/>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode" id="rbcash2" ng-model="feeEditModel.mode" value="cash" checked="" />
                                                        <label for="rbcash2"> <?= lang("lbl_cash"); ?> </label>
                                                    </span>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode" id="rbcheque2" ng-model="feeEditModel.mode" value="cheque" />
                                                        <label for="rbcheque2"> <?= lang("lbl_cheque"); ?> </label>
                                                    </span>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode" ng-model="feeEditModel.mode" id="rbdd2" value="DD" />
                                                        <label for="rbdd2"> <?= lang("lbl_dd"); ?> </label>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group mb-0">
                                                    <div class="checkbox checkbox-info">
                                                        <input id="checkbox42" type="checkbox" name="isSendEmailToParent" ng-model="isSendEmailToParent" />
                                                        <label for="checkbox42"> <?php echo lang("lbl_send_email_to_parent"); ?> </label>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-0">
                                                    <div class="checkbox checkbox-info">
                                                        <input id="checkbox52" type="checkbox" name="isSendSMSToParent" ng-model="isSendSMSToParent" />
                                                        <label for="checkbox52"> <?php echo lang("lbl_send_sms_to_parent"); ?> </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="checkbox checkbox-info">
                                                        <input id="checkbox62" type="checkbox" name="isSendNotificationToParent" ng-model="isSendNotificationToParent" />
                                                        <label for="checkbox62"> <?php echo lang("lbl_send_notification_to_parent"); ?> </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="panel-footer" style="text-align: right;">
                                    <button type="submit" class="btn btn-primary" ng-disabled="loading"><?= lang("update_fee"); ?></button>
                                    <button type="reset" class="btn btn-default" data-dismiss="modal"><?= lang("btn_cancel"); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- start fee collection modal -->
        <div class="modal fade" id="feeCollectionAddModel" tabindex="-1" role="dialog" aria-labelledby="feeCollectionAddModel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" id="fee-collection-model-contents">
                    <form name="feeCollectionAddModelForm" ng-submit="collectFee(feeCollectionAddModelForm.$valid)" novalidate="" class="form-material">
                        <div class="form-body">
                            <div class="panel panel-primary">
                                <div class="panel-heading"><?= lang("lbl_fee_form"); ?></div>
                                <div class="panel-body">

                                    <div class="col-md-12 p-0 m-0">
                                        <div class="col-md-6 p-0 m-0">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_date"); ?></label>
                                                    <input type="text" ng-value="afcModel.created_at" disabled="" class="form-control mydatepicker-autoclose">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_discounted_amount"); ?></label>
                                                    <input type="text" class="form-control" disabled="" ng-value="afcModel.discounted_amount" />
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_discount"); ?></label>
                                                    <input type="text" class="form-control" disabled="" ng-value="afcModel.discount" />
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_comment"); ?></label>
                                                    <input type="text" class="form-control" ng-model="comment" />
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 p-0 m-0">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_paid_amount"); ?></label>
                                                    <input type="number" class="form-control" max="{{afcModel.discounted_amount}}" min="0" required="" ng-model="paid_amount" />
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group visible-xs">
                                                    <label><?= lang("lbl_payment_mode"); ?></label>
                                                    <br/>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode2" id="rbcash3" ng-model="mode" value="cash" checked="checked" />
                                                        <label for="rbcash3"> <?= lang("lbl_cash"); ?> </label>
                                                    </span>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode2" id="rbcheque3" ng-model="mode" value="cheque" />
                                                        <label for="rbcheque3"> <?= lang("lbl_cheque"); ?> </label>
                                                    </span>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode2" ng-model="mode" id="rbdd3" value="dd" />
                                                        <label for="rbdd3"> <?= lang("lbl_dd"); ?> </label>
                                                    </span>
                                                </div>

                                                <div class="form-group hidden-xs">
                                                    <label><?= lang("lbl_payment_mode"); ?></label><br/>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode" id="rbcash2" ng-model="mode" value="cash" checked="" />
                                                        <label for="rbcash2"> <?= lang("lbl_cash"); ?> </label>
                                                    </span>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode" id="rbcheque2" ng-model="mode" value="cheque" />
                                                        <label for="rbcheque2"> <?= lang("lbl_cheque"); ?> </label>
                                                    </span>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode" ng-model="mode" id="rbdd2" value="dd" />
                                                        <label for="rbdd2"> <?= lang("lbl_dd"); ?> </label>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group mb-0">
                                                    <div class="checkbox checkbox-info">
                                                        <input id="checkbox42" type="checkbox" name="isSendEmailToParent" ng-model="isSendEmailToParent" />
                                                        <label for="checkbox42"> <?php echo lang("lbl_send_email_to_parent"); ?> </label>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-0">
                                                    <div class="checkbox checkbox-info">
                                                        <input id="checkbox52" type="checkbox" name="isSendSMSToParent" ng-model="isSendSMSToParent" />
                                                        <label for="checkbox52"> <?php echo lang("lbl_send_sms_to_parent"); ?> </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="checkbox checkbox-info">
                                                        <input id="checkbox62" type="checkbox" name="isSendNotificationToParent" ng-model="isSendNotificationToParent" />
                                                        <label for="checkbox62"> <?php echo lang("lbl_send_notification_to_parent"); ?> </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="panel-footer" style="text-align: right;">
                                    <button type="submit" class="btn btn-primary" ng-disabled="loading"><?= lang("collect_fee"); ?></button>
                                    <button type="reset" class="btn btn-default" data-dismiss="modal"><?= lang("btn_cancel"); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- end fee collection modal -->


        <!-- start exemption modal -->
        <div class="modal fade" id="exemptionModel" tabindex="-1" role="dialog" aria-labelledby="exemptionModel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form ng-submit="setExemption();" class="form-material">
                        <div class="form-body">
                            <div class="panel panel-primary">
                                <div class="panel-heading"><?= lang("lbl_exemption"); ?></div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?= lang("exemption_amount"); ?></label>
                                            <input type="number" ng-model="exemptionModel.exemption_amount" min="0" max="{{exemptionModel.discounted_amount}}" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?= lang("reason"); ?></label>
                                            <textarea ng-model="exemptionModel.exemption_reason" class="form-control" rows="5"></textarea>
                                        </div>
                                    </div>
                                                                        
                                </div>
                                <div class="panel-footer" style="text-align: right;">
                                    <button type="reset" class="btn btn-default" data-dismiss="modal"><?= lang("btn_cancel"); ?></button>
                                    <button type="submit" class="btn btn-success"><?= lang("btn_send"); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
        <!-- end fee collection modal -->


        <!--Partially Fee detail Model-->
        <div id="myModalYasir" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" style='top:<?php
                if ($this->session->userdata("site_lang") == "english") {
                    echo "112px";
                } else {
                    echo "-180px";
                }
                ?>'>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&Cross;</button>
                    <h4><b><?php echo "Partially Fee Details"; ?></b></h4>
                    <h5>{{ partiallyFeeDetailModel[0].feetype }} </h5>
                    <h6>({{ partiallyFeeDetailModel[0].due_date }})</h6>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th class="text-center"><?php echo lang("imp_sr"); ?></th>
                                <th class="text-center"><?php echo lang("collected_by"); ?></th>
                                <th class="text-center"><?php echo lang("lbl_date"); ?></th>
                                <th class="text-center"><?php echo lang("lbl_paid"); ?></th>
                                <th class="text-center"><?php echo lang("lbl_paid_fee_percentage"); ?></th>
                                <th><?php echo lang("lbl_receipt_no"); ?></th>
                                <th class='text-center'><?php echo lang("th_action"); ?></th>
                            </tr>
                            <tr class="text-center" ng-repeat="row in partiallyFeeDetailModel">
                                <td>{{ $index+1 }}</td>
                                <td>{{ row.collector_name }}</td>
                                <td>{{ row.paid_date }}</td>
                                <td><?php echo $this->session->userdata('userdata')['currency_symbol'] ?>{{row.paid_amount}}</td>
                                <td> {{ (row.paid_amount * 100) / (row.amount - row.discount) | number:2 }}%</td>
                                <td>{{row.receipt_no}} <a ng-if="row.comment != '' && row.comment != undefined" href="javascript:void(0)" data-html="true" data-toggle="tooltip" data-placement="top" title="{{row.comment}}"><i class="fa fa-info-circle"></i></a></td>
                                <td>
                                    <!--<a href="javascript:void(0)" class="btn-sm btn btn-info btn-circle text-white" ng-click="setEditFeeCollectionModel(row)" data-toggle="modal" data-target="#feeCollectionEditModel"><i class="fa fa-pencil"></i></a>-->
                                    <!-- <a href="javascript:void(0)" ng-click="showConfirmationAlert(row,'null')" class="btn-sm btn btn-circle text-white" style="background-color: #98592e;"><i class="fa fa-recycle"></i></a> -->
                                    <!-- <a href="javascript:void(0)" ng-click="showConfirmationAlert(row,'null')" class="btn-sm btn btn-danger btn-circle text-white"><i class="fa fa-trash-o"></i></a> -->
                                    <a href="javascript:void(0)" style="background-color: #2d7d44;" class="btn-sm btn btn btn-circle text-white" ng-click="editPartiallyFeeCollectionModel(row,partiallyFeeDetailModel)" data-toggle="modal" data-target="#partialEditFeeCollectionModel"><i class="fa fa-pencil"></i></a>
                                    <a href="javascript:void(0)" ng-click="showConfirmationAlertRefund(row,'null')" class="btn-sm btn btn-circle text-white" style="background-color: #98592e;"><i class="fa fa-recycle"></i></a>
                                    <a href="javascript:void(0)" ng-click="showConfirmationAlert(row,'null')" class="btn-sm btn btn-danger btn-circle text-white"><i class="fa fa-trash-o"></i></a>
                                    <a href="<?php echo base_url(); ?>forms/show?id={{row.fee_collection_id}}&requested_page=single_fee&class_name={{row.class_name}}&batch_name={{row.batch_name}}" target="_blank" ng-if="row.fee_collection_id !== 'NULL'" class="btn-sm btn btn-success btn-circle text-white"><i class="fa fa-print"></i></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Partially detail model end-->

    <div class="modal fade" id="partialEditFeeCollectionModel" tabindex="-1" role="dialog" aria-labelledby="partialEditFeeCollectionModel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" id="fee-collection-model-contents">
                    <form name="partialEditFeeCollectionModelForm" ng-submit="editPartailCollectFee(partialEditFeeCollectionModelForm.$valid)" novalidate="" class="form-material">
                        <div class="form-body">
                            <div class="panel panel-primary">
                                <div class="panel-heading"><?= lang("lbl_fee_form"); ?></div>
                                <div class="panel-body">

                                    <div class="col-md-12 p-0 m-0">
                                        <div class="col-md-6 p-0 m-0">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_date"); ?></label>
                                                    <input type="text" ng-value="feePartialEditModel.created_at" disabled="" class="form-control mydatepicker-autoclose">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_discounted_amount"); ?></label>
                                                    <input type="text" class="form-control" disabled="" ng-value="feePartialEditModel.new_discounted_amount" />
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_discount"); ?></label>
                                                    <input type="text" class="form-control" disabled="" ng-value="feePartialEditModel.discount" />
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_comment"); ?></label>
                                                    <input type="text" class="form-control" ng-value="feePartialEditModel.comment" ng-model="feePartialEditModel.comment" />
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 p-0 m-0">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= lang("lbl_paid_amount"); ?></label>
                                                    <input type="number" class="form-control" max="{{maxEditPiadAmount}}" min="0" required="" ng-model="feePartialEditModel.paid_amount" />
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group visible-xs">
                                                    <label><?= lang("lbl_payment_mode"); ?></label>
                                                    <br/>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode2" id="rbcash3" ng-model="feePartialEditModel.mode" value="cash" checked="" />
                                                        <label for="rbcash3"> <?= lang("lbl_cash"); ?> </label>
                                                    </span>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode2" id="rbcheque3" ng-model="feePartialEditModel.mode" value="cheque" />
                                                        <label for="rbcheque3"> <?= lang("lbl_cheque"); ?> </label>
                                                    </span>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode2" ng-model="feePartialEditModel.mode" id="rbdd3" value="dd" />
                                                        <label for="rbdd3"> <?= lang("lbl_dd"); ?> </label>
                                                    </span>
                                                </div>

                                                <div class="form-group hidden-xs">
                                                    <label><?= lang("lbl_payment_mode"); ?></label><br/>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode" id="rbcash2" ng-model="feePartialEditModel.mode" value="cash" checked="" />
                                                        <label for="rbcash2"> <?= lang("lbl_cash"); ?> </label>
                                                    </span>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode" id="rbcheque2" ng-model="feePartialEditModel.mode" value="cheque" />
                                                        <label for="rbcheque2"> <?= lang("lbl_cheque"); ?> </label>
                                                    </span>
                                                    <span class="radio radio-primary">
                                                        <input type="radio" name="mode" ng-model="feePartialEditModel.mode" id="rbdd2" value="DD" />
                                                        <label for="rbdd2"> <?= lang("lbl_dd"); ?> </label>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group mb-0">
                                                    <div class="checkbox checkbox-info">
                                                        <input id="checkbox42" type="checkbox" name="isSendEmailToParent" ng-model="isSendEmailToParent" />
                                                        <label for="checkbox42"> <?php echo lang("lbl_send_email_to_parent"); ?> </label>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-0">
                                                    <div class="checkbox checkbox-info">
                                                        <input id="checkbox52" type="checkbox" name="isSendSMSToParent" ng-model="isSendSMSToParent" />
                                                        <label for="checkbox52"> <?php echo lang("lbl_send_sms_to_parent"); ?> </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="checkbox checkbox-info">
                                                        <input id="checkbox62" type="checkbox" name="isSendNotificationToParent" ng-model="isSendNotificationToParent" />
                                                        <label for="checkbox62"> <?php echo lang("lbl_send_notification_to_parent"); ?> </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="panel-footer" style="text-align: right;">
                                    <button type="submit" class="btn btn-primary" ng-disabled="loading"><?= lang("update_fee"); ?></button>
                                    <button type="reset" class="btn btn-default" data-dismiss="modal"><?= lang("btn_cancel"); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <!--.row-->
    <div class="row" id="feecollection" ng-init="initFeetypes();initAcademicYears()">
        <div class="col-md-12"> 
            <div class="white-box">
                <form class="form-material" id="feeCollection_search_filter" name="feecollectionFilterForm" ng-submit="fetchFeeCollections(feecollectionFilterForm.$valid)" novalidate="">
                    <!--/row-->
                    <div class="row">

                        <div class="col-md-3" id="feeFilterAcademicYears">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang("lbl_academic_year"); ?></label>
                                <select class="form-control" name="academic_year_id" ng-model="fcModel.academic_year_id" required="" ng-change="initClasses(fcModel.academic_year_id)">
                                    <option value="-1"><?php echo lang("lbl_select_academic_year"); ?></option>
                                    <option ng-repeat="ay in academicyears" value="{{ ay.id }}">{{ ay.name }}</option>
                                </select>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-3" id="feeFilterClasses">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang("lbl_class"); ?></label>
                                <select class="form-control" name="class_id" ng-model="fcModel.class_id" ng-init="fcModel.class_id='all'" ng-change="initBatches(fcModel.class_id, fcModel.academic_year_id)">
                                    <option value="all"><?php echo lang("option_all"); ?></option>
                                    <option ng-repeat="cls in classes" value="{{ cls.id }}">{{ cls.name }}</option>
                                </select>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-3" id="feeFilterBatches">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang("lbl_batches"); ?></label>
                                <select class="form-control" name="batch_id" ng-model="fcModel.batch_id" ng-init="fcModel.batch_id='all'">
                                    <option value="all"><?php echo lang("option_all"); ?></option>
                                    <option ng-repeat="batch in batches" value="{{batch.id}}">{{batch.name}}</option>
                                </select>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang("serach_by_keyword"); ?></label>
                                <input type="text" class="form-control" name="searchBy" placeholder="<?php echo lang('name_roll'); ?>" ng-model="fcModel.searchBy">
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="checkbox checkbox-info">
                                        <input id="checkbox4" type="checkbox" name="isDue" ng-model="fcModel.isDue" />
                                        <label for="checkbox4"> <?php echo lang("only_fee_due_students"); ?> </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3" ng-show="fcModel.isDue">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{selectedSpecificFeetype}}
                                    </button>
                                    <div class="dropdown-menu" style="max-height: 200px; overflow: auto;">
                                        <a class="dropdown-item" style="padding: 10px;" href="javascript:void(0);" ng-click="setSpecificFeetype({id:'all', name:'All Feetypes'})"><?php echo lang('all_fee_types'); ?></a>
                                        <a class="dropdown-item" ng-repeat="feetype in feetypes" style="padding: 10px;" href="javascript:void(0);" ng-click="setSpecificFeetype(feetype)">{{feetype.name}} <small>({{feetype.classname}})</small></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <!--<button type="button" class="btn btn-info" ng-click="refreshFees()"><i class="fa fa-refresh "></i> <?php echo lang('refresh');?></button>-->
                            <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-search "></i> <?php echo lang("search"); ?></button>
                        </div>
                    </div>
                    <!--/row-->
                </form>
            </div>

        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div id="feeCollectionContainer1">
                <div class="white-box">
                    <div class="table-responsive" ng-if="feeCollectionStudents.length>0">
                        <table id="myTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th><?php echo lang("lbl_class"); ?></th>
                                    <th><?php echo lang("lbl_batch"); ?></th>
                                    <th><?php echo lang("lbl_rollno"); ?></th>
                                    <th><?php echo lang("student_name"); ?></th>
                                    <th><?php echo lang("father_name"); ?></th>
                                    <!-- <th><?php echo lang("lbl_phone_number"); ?></th> -->
                                    <th><?php echo lang("lbl_status"); ?></th>
                                    <?php if ($this->session->userdata("userdata")["role_id"] == EMPLOYEE_ROLE_ID && isset($view_coll_detail) && $view_coll_detail == '1') { ?>
                                        <th><?php echo lang("th_action"); ?></th>
                                    <?php } if($this->session->userdata("userdata")["role_id"] == ADMIN_ROLE_ID){ ?>
                                        <th><?php echo lang("th_action"); ?></th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="std in feeCollectionStudents">
                                    <td>{{std.class_name}}</td>
                                    <td>{{std.batch_name}}</td>
                                    <td>{{std.rollno}}</td>
                                    <td>{{std.name}}</td>
                                    <td>{{std.father_name}}</td>
                                    <!-- <td>{{std.mobile_phone}}</td> -->

                                   <!--  <td ng-if="std.ftCount==std.fcCount"><button class="btn btn-success btn-xs"><?php echo lang("lbl_paid"); ?></button></td>
                                    <td ng-if="std.ftCount!=std.fcCount"><button class="btn btn-danger btn-xs"><?php echo lang('lbl_due') ?></button></td> -->

                                    <td ng-if="std.ftCount==std.fcCount && std.ftCount != 0"><button class="btn btn-success btn-xs"><?php echo lang("lbl_paid"); ?></button></td>
                                    <td ng-if="std.ftCount!=std.fcCount || std.ftCount==0"><button class="btn btn-danger btn-xs"><?php echo lang('lbl_due') ?></button></td>

                                    <!-- <td ng-if="std.ftCount==0"><button class="btn btn-danger btn-xs"><?php echo lang('lbl_due') ?></button></td> -->

                                    
                                    <?php if ($this->session->userdata("userdata")["role_id"] == EMPLOYEE_ROLE_ID && isset($view_coll_detail) && $view_coll_detail == '1') { ?>
                                        <td>
                                            <button class="btn btn-primary btn-xs" ng-click="showDetails(std)"><?php echo lang('btn_view') ?></button>
                                        </td>
                                    <?php } if($this->session->userdata("userdata")["role_id"] == ADMIN_ROLE_ID){ ?>
                                        <td>
                                            <button class="btn btn-primary btn-xs" ng-click="showDetails(std)"><?php echo lang('btn_view') ?></button>
                                        </td>
                                    <?php } ?>
                                    
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <span ng-if="feeCollectionStudents.length===0" class="text-danger"><?php echo lang("no_record"); ?></span>
                </div>
            </div>

            <div class="feeCollectionContainer2 hidden">
                <div class="white-box">
                    <div class="row">
                        <div class="col-md-12 p-0 mb-2">
                            <a href="javascript:void(0);" ng-click="back()" class="btn btn-default"><i class="fa fa-reply " aria-hidden="true"></i> <?php echo lang("btn_back"); ?></a>
                        </div>

                        <div class="col-md-12 well">

                            <div class="col-md-2 text-center visible-xs">
                                <img ng-src="uploads/user/{{selectedStd.avatar}}" class="thumb-lg img-circle" alt="student-img">
                            </div>
                            <div class="col-md-2 text-center hidden-xs">
                                <img ng-src="uploads/user/{{selectedStd.avatar}}" class="thumb-lg img-circle" alt="student-img">
                            </div>

                            <div class="col-md-10">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="table-responsive m-t-10  col-md-12">
                                            <table class="table-sm col-md-12">
                                                <tr>
                                                    <th><?php echo lang("lbl_name"); ?></th><td>{{ selectedStd.name }}</td>
                                                    <th><?php echo lang("lbl_class"); ?></th><td>{{ selectedStd.class_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th><?php echo lang("father_name"); ?></th><td>{{ selectedStd.father_name }}</td>
                                                    <th><?php echo lang("lbl_batch"); ?></th><td>{{ selectedStd.batch_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th><?php echo lang("lbl_phone_number"); ?></th>
                                                    <td>
                                                        <span ng-show="selectedStd.contact != ''">{{ selectedStd.contact }}</span>
                                                        <span ng-show="selectedStd.contact==''">---</span>
                                                    </td>
                                                    <th><?php echo lang("lbl_rollno"); ?></th><td>{{ selectedStd.rollno }}</td>
                                                </tr>
                                                
                                                <tr>
                                                    <!-- <th style="padding-bottom: 10px;"><?php echo lang("lbl_discount"); ?></th> --><!-- <td>{{ selectedStd.discount_name }}</td> -->
                                                    <td style="padding-top: 28px;" style="width: 100%;">
                                                        <div class="form-group">
                                                            <select class="form-control yasir-ann-select2" style="width: 100%;" multiple="multiple" ng-model="discount_ids" ng-init="loadDiscounts()"  <?php if(isset($fee_discounts) && $fee_discounts != '1'){ ?> disabled <?php } ?>>
                                                                <!-- <option value="" disabled="">---<?= lang("lbl_select_a_discount"); ?>---</option> -->
                                                                <option ng-repeat="discount in discounts" value="{{discount.id}}">{{discount.name}}</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php if((isset($fee_discounts) && $fee_discounts == '1') || $this->session->userdata("userdata")["role_id"] == ADMIN_ROLE_ID){ ?>
                                                        
                                                            <div style="padding-left: 8%;">
                                                                <button class="btn btn-primary" ng-click="applyDiscounts()">Apply Discounts</button>
                                                            </div>
                                                       
                                                         <?php } ?>
                                                     </td>
                                                    <!--<th><?php echo lang('discount_amount') ?></th><td>{{ selectedStd.discount_amount }}%</td>-->
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
                                <table id="myTable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th><?php echo lang("fees_group"); ?></th>
                                            <th><?php echo lang("collected_by"); ?></th>
                                            <th><?php echo lang("imp_std_class"); ?></th>
                                            <th><?php echo lang("imp_std_section"); ?></th>
                                            <th><?php echo lang("lbl_status"); ?></th>
                                            <th><?php echo lang("amount"); ?></th>
                                            <th><?php echo lang("lbl_discount"); ?></th>
                                            <th><?php echo lang("discounted_amount"); ?></th>
                                            <th><?php echo lang("lbl_date"); ?></th>
                                            <th><?php echo lang("lbl_paid"); ?></th>
                                            <th><?php echo lang("lbl_paid_fee_percentage"); ?></th>
                                            <th><?php echo lang("lbl_exemption"); ?></th>
                                            <th><?php echo lang("lbl_balance"); ?></th>
                                            <th><?php echo lang("lbl_receipt_no"); ?></th>
                                            <?php if ($this->session->userdata("userdata")["role_id"] == ADMIN_ROLE_ID) { ?>
                                                <th class='text-center'><?php echo lang("th_action"); ?></th>
                                            <?php } ?>
                                            <?php if (isset($fee_coll) && $fee_coll == '1' && $this->session->userdata("userdata")["role_id"] == EMPLOYEE_ROLE_ID) { ?>
                                                <th class='text-center'><?php echo lang("th_action"); ?></th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr></tr>
                                        <tr ng-repeat="(key,rcd) in stdFeeRecords" ng-class="{'bg-color-change': rcd[0].feetype_status == '1'}">
                                           
                                            <td>{{rcd[0].feetype}} <br/><small>({{rcd[0].due_date}})</small></td>
                                            <td>{{rcd[0].collector_name}}</td>
                                            <td>
                                                <span ng-show="rcd[0].class_name=='NULL'"></span>
                                                <span ng-show="rcd[0].class_name!='NULL'">{{rcd[0].class_name}}</span>
                                            </td>
                                            <td>
                                                <span ng-show="rcd[0].batch_name=='NULL'"></span>
                                                <span ng-show="rcd[0].batch_name!='NULL'">{{rcd[0].batch_name}}</span>
                                            </td>
                                            <td class="font-weight-normal">
                                                <span ng-if="calculateStatus(rcd)===2" class="text-warning"><?= lang("lbl_fee_partially_paid"); ?> </span>
                                                <span ng-if="calculateStatus(rcd)===0" class="text-danger"><?php echo lang("lbl_unpaid"); ?></span>
                                                <span ng-if="calculateStatus(rcd)===1" class="text-success"><?php echo lang("lbl_paid"); ?></span>
                                            </td>
                                            <td><?php echo $this->session->userdata('userdata')['currency_symbol'] ?>{{rcd[0].amount}}  <a ng-if="rcd[0].variant" href="javascript:void(0)" data-html="true" data-toggle="tooltip" data-placement="top" title="Nationality: {{rcd[0].nationality}}, Admission Date: {{rcd[0].admission_date}}"><i class="fa fa-info-circle"></i></a></td>
                                            <td>{{ rcd[0].discount }} <a ng-if="rcd[0].discount_type=='percentage'" href="javascript:void(0)" data-html="true" data-toggle="tooltip" data-placement="top" title="<?php echo lang('lbl_discount_calculated_in_percentage'); ?>"><i class="fa fa-info-circle"></i></a></td>
                                            <td><?php echo $this->session->userdata('userdata')['currency_symbol'] ?>{{rcd[0].amount - rcd[0].discount}}</td>
                                            <td>{{rcd[0].paid_date}}</td>
                                            <td><?php echo $this->session->userdata('userdata')['currency_symbol'] ?>{{ countPaidAmount(rcd) }}</td>
                                            <td ng-if="rcd[0].paid_amount==='-'">-</td>
                                            <td> {{ calculatePaidFeePercentage(rcd) | number:1 }}%</td>
                                            <td style="text-align: center;">
                                                <?php if($this->session->userdata("userdata")["role_id"] == ADMIN_ROLE_ID || (isset($fee_exemption) && $fee_exemption == '1') ) { ?>
                                                    <a href="javascript:void(0)" ng-if="rcd[0].exemption_status=='' && rcd[0].feetype_status!='1' && rcd[0].fee_collection_id==='NULL'" data-toggle="modal" data-target="#exemptionModel" ng-click="setFeeValues(rcd)">
                                                        
                                                        <i class="fa fa-info-circle fa-2x" ng-if="rcd[0].exemption_status==''" style="color: blue;"></i>
                                                    </a>
                                                    <a href="javascript:void(0)" ng-if="rcd[0].exemption_status=='approved'" >
                                                        <i class="fa fa-info-circle fa-2x" ng-if="rcd[0].exemption_status=='approved'" style="color: green;"></i>
                                                    </a>
                                                    <a href="javascript:void(0)" ng-if="rcd[0].exemption_status=='not-approved' && rcd[0].fee_collection_id==='NULL'" data-toggle="modal" data-target="#exemptionModel" ng-click="setFeeValues(rcd)">
                                                        <i class="fa fa-info-circle fa-2x" ng-if="rcd[0].exemption_status=='not-approved' && rcd[0].fee_collection_id==='NULL'" style="color: red;"></i>
                                                    </a>
                                                    <a href="javascript:void(0)" ng-if="rcd[0].exemption_status=='not-approved' && rcd[0].fee_collection_id!='NULL' && calculateBalance(rcd)!=0" data-toggle="modal" data-target="#exemptionModel" ng-click="setFeeValues(rcd)">
                                                        <i class="fa fa-info-circle fa-2x" ng-if="rcd[0].exemption_status=='not-approved' && rcd[0].fee_collection_id!=='NULL'" style="color: red;"></i>
                                                    </a>
                                                    <a href="javascript:void(0)" ng-if="rcd[0].exemption_status=='inprocess' && rcd[0].fee_collection_id!=='NULL'" data-toggle="modal" data-target="#exemptionModel" ng-click="setFeeValues(rcd)">
                                                        <i class="fa fa-info-circle fa-2x" ng-if="rcd[0].exemption_status=='inprocess' && rcd[0].fee_collection_id!=='NULL'" style="color: yellow;"></i>
                                                    </a>
                                                    <a href="javascript:void(0)" ng-if="rcd[0].exemption_status=='inprocess' && rcd[0].fee_collection_id==='NULL'" data-toggle="modal" data-target="#exemptionModel" ng-click="setFeeValues(rcd)">
                                                        <i class="fa fa-info-circle fa-2x" ng-if="rcd[0].exemption_status=='inprocess' && rcd[0].fee_collection_id==='NULL'" style="color: yellow;"></i>
                                                    </a>
                                                <?php } ?>    
                                                <p ng-if="rcd[0].exemption_status=='inprocess' || rcd[0].exemption_status=='approved'">{{rcd[0].exemption_amount}}</p>
                                            </td>
                                            <td>
                                                <strong ng-if="rcd[0].fee_collection_id==='NULL'"><span ng-if="rcd[0].discounted_amount !=0">-</span>{{rcd[0].discounted_amount }} <?php echo $this->session->userdata('userdata')['currency_symbol'] ?></strong>
                                                <strong ng-if="rcd[0].fee_collection_id!=='NULL'">{{calculateBalance(rcd)}}</strong>
                                            </td>
                                            <td style="min-width: 80px"><p ng-repeat="r in rcd">{{r.receipt_no}} <a ng-if="r.comment != '' && r.comment != undefined" href="javascript:void(0)" data-html="true" data-toggle="tooltip" data-placement="top" title="{{r.comment}}"><i class="fa fa-info-circle"></i></a></p></td>
                                            <?php if ($this->session->userdata("userdata")["role_id"] == ADMIN_ROLE_ID) { ?>
                                                <td style="min-width:235px;" class='text-center'>
                                                    <a href="javascript:void(0)" ng-show="rcd.length>1" class="btn-sm btn btn-primary btn-circle text-white" ng-click="showPartiallyFeeDeatils(rcd)"><i class="fa fa-info"></i></a>
                                                    <a href="javascript:void(0)" class="btn-sm btn btn-info btn-circle text-white" ng-click="setEditFeeCollectionModel(rcd)" data-toggle="modal" data-target="#feeCollectionEditModel" ng-class="{'custom_disable':isAllFeePaid(rcd)}" ng-show="rcd[0].fee_collection_id!='NULL'"><i class="fa fa-plus"></i></a>
                                                    <a href="javascript:void(0)" style="background-color: #2d7d44;" class="btn-sm btn btn btn-circle text-white" ng-click="editFeeCollectionModel(rcd)" data-toggle="modal" data-target="#editFeeCollectionModel" ng-class="{'hide':isPartiallyPaid(rcd)}" ng-show="rcd[0].fee_collection_id!='NULL'"><i class="fa fa-pencil"></i></a>
                                                    <a href="javascript:void(0)" ng-click="showConfirmationAlertRefund(rcd,'all')" ng-show="rcd[0].fee_collection_id!=='NULL'" class="btn-sm btn btn-circle text-white" style="background-color: #98592e;"><i class="fa fa-recycle"></i></a>
                                                    <a href="javascript:void(0)" ng-click="showConfirmationAlert(rcd,'all')" ng-show="rcd[0].fee_collection_id!=='NULL'" class="btn-sm btn btn-danger btn-circle text-white"><i class="fa fa-trash-o"></i></a>
                                                    <a href="<?php echo base_url(); ?>forms/show?id={{rcd[0].fee_collection_id}}&requested_page=single_fee&class_name={{rcd[0].class_name}}&batch_name={{rcd[0].batch_name}}" target="_blank" ng-if="rcd[0].fee_collection_id !== 'NULL'" class="btn-sm btn btn-success btn-circle text-white"><i class="fa fa-print"></i></a>
                                                    <a href="javascript:void(0);" ng-if="(rcd.length==0 || rcd[0].fee_collection_id === 'NULL') && rcd[0].feetype_status!='1'" ng-click="setAddFeeCollectionModel(rcd[0])" data-toggle="modal" data-target="#feeCollectionAddModel" class='btn btn-primary btn-sm text-white'><?php echo lang("collect_fee"); ?></a>
                                                    
                                                    <a href="javascript:void(0);" ng-if="rcd[0].feetype_status=='1' && rcd[0].fee_collection_id==='NULL'" title='Activate the fee' class='btn-sm btn btn-success btn-circle text-white fee-status' value="{{rcd[0].feetype_id}},{{selectedStd.id}},fee/feeActivate"><i class="fa fa-check"></i></a>
                                                    <a href="javascript:void(0);" ng-if="rcd[0].feetype_status!='1' && rcd[0].fee_collection_id==='NULL'" title='Deactivate the fee' class='btn-sm btn btn-danger btn-circle text-white fee-status1' value="{{rcd[0].feetype_id}},{{selectedStd.id}},fee/feeDeactivate"><i class="fa fa-times"></i></a>

                                                </td>
                                            <?php } ?>
                                            <?php if (isset($fee_coll) && $fee_coll == '1' && $this->session->userdata("userdata")["role_id"] == EMPLOYEE_ROLE_ID) { ?>
                                                <td style="min-width:150px;" class='text-center'>
                                                    <a href="javascript:void(0)" ng-show="rcd.length>1" class="btn-sm btn btn-primary btn-circle text-white" ng-click="showPartiallyFeeDeatils(rcd)"><i class="fa fa-info"></i></a>
                                                    <a href="javascript:void(0)" class="btn-sm btn btn-info btn-circle text-white" ng-click="setEditFeeCollectionModel(rcd)" data-toggle="modal" data-target="#feeCollectionEditModel" ng-class="{'custom_disable':isAllFeePaid(rcd)}" ng-show="rcd[0].fee_collection_id!='NULL'"><i class="fa fa-plus"></i></a>
                                                    <a href="javascript:void(0)" ng-click="showConfirmationAlertRefund(rcd,'all')" ng-show="rcd[0].fee_collection_id!=='NULL'" class="btn-sm btn btn-circle text-white" style="background-color: #98592e;"><i class="fa fa-recycle"></i></a>
                                                    <a href="javascript:void(0)" ng-click="showConfirmationAlert(rcd,'all')" ng-show="rcd[0].fee_collection_id!=='NULL'" class="btn-sm btn btn-danger btn-circle text-white"><i class="fa fa-trash-o"></i></a>
                                                    <a href="<?php echo base_url(); ?>forms/show?id={{rcd[0].fee_collection_id}}&requested_page=single_fee&class_name={{rcd[0].class_name}}&batch_name={{rcd[0].batch_name}}" target="_blank" ng-if="rcd[0].fee_collection_id !== 'NULL'" class="btn-sm btn btn-success btn-circle text-white"><i class="fa fa-print"></i></a>
                                                    <a href="javascript:void(0);" ng-if="(rcd.length==0 || rcd[0].fee_collection_id === 'NULL') && rcd[0].feetype_status!='1'" ng-click="setAddFeeCollectionModel(rcd[0])" data-toggle="modal" data-target="#feeCollectionAddModel" class='btn btn-primary btn-sm text-white'><?php echo lang("collect_fee"); ?></a>
                                                <?php if(isset($fee_disable) && $fee_disable == '1'){ ?>
                                                    <a href="javascript:void(0);" ng-if="rcd[0].feetype_status=='1' && rcd[0].fee_collection_id==='NULL'" title='Activate the fee' class='btn-sm btn btn-success btn-circle text-white fee-status' value="{{rcd[0].feetype_id}},{{selectedStd.id}},fee/feeActivate"><i class="fa fa-check"></i></a>
                                                    <a href="javascript:void(0);" ng-if="rcd[0].feetype_status!='1' && rcd[0].fee_collection_id==='NULL'" title='Deactivate the fee' class='btn-sm btn btn-danger btn-circle text-white fee-status1' value="{{rcd[0].feetype_id}},{{selectedStd.id}},fee/feeDeactivate"><i class="fa fa-times"></i></a>
                                                <?php } ?>

                                                </td>
                                            <?php } ?>
                                        </tr>

                                        <tr class="text-center bg-primary " style="color:#ffffff; ">
                                            <td colspan="5"><?php echo lang("lbl_total"); ?></td>
                                            <td><?php echo $this->session->userdata('userdata')['currency_symbol'] ?>{{totalSum.fee}}</td>
                                            <td></td>
                                            <td><?php echo $this->session->userdata('userdata')['currency_symbol'] ?>{{totalSum.discounted}}</td>
                                            <td></td>
                                            <td><?php echo $this->session->userdata('userdata')['currency_symbol'] ?>{{totalSum.paid}}</td>
                                            <td></td>
                                            <td>{{totalSum.exemption}}</td>
                                            <td>{{totalSum.balance}} <?php echo $this->session->userdata('userdata')['currency_symbol'] ?></td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="feeCollectionContainer2 hidden col-lg-12 col-md-6 col-xlg-2 col-xs-12" ng-show="notesLength>0">
            <div class="ribbon-wrapper card">
                <div class="ribbon ribbon-primary <?php if($this->session->userdata('userdata')['language'] != 'english') { echo 'ribbon-right'; } ?>"><?php echo lang("lbl_previous_fee_record"); ?></div>
                <div class="ribbon-content">

                    <div class="table-responsive" ng-repeat="(key,val) in notes">
                        <h4><b><?php echo lang("lbl_academic_year"); ?> - {{key}}</b></h4>
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th><?php echo lang("fee_type"); ?></th>
                                <th><?php echo lang("fee_amount"); ?></th>
                                <th><?php echo lang("due_date"); ?></th>
                                <th><?php echo lang("lbl_fee_status"); ?></th>
                                <th><?php echo lang("lbl_balance"); ?></th>
                            </tr>
                            <tr ng-repeat="(k,un) in val.unpaid">
                                <td>{{un.name}}</td>
                                <td>{{un.amount}}</td>
                                <td>{{un.due_date}}</td>
                                <td><span class="text-danger"><?php echo lang("lbl_fee_unpaid"); ?></span></td>
                                <td>{{0-un.amount}}</td>
                            </tr>
                            <tr ng-repeat="(kk,par) in val.partial">
                                <td>{{par.name}}</td>
                                <td>{{par.amount}}</td>
                                <td>{{par.due_date}}</td>
                                <td><span class="text-warning"><?php echo lang("lbl_fee_partially_paid"); ?></span></td>
                                <td>{{par.total_paid-par.amount}}</td>
                            </tr>

                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</div>


</div>
<!--/panel wrapper-->
</div>
<!--/panel-->
</div>
</div>
<!--./row-->
<!--page content end here-->
</div>
<?php include(APPPATH . "views/inc/footer.php"); ?>