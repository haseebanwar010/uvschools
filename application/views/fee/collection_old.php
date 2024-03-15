<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<!-- Page Content -->
<div id="page-wrapper">
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
        <?php if ($this->session->userdata("userdata")["role_id"] == EMPLOYEE_ROLE_ID) { 
                $ci = & get_instance();
                $arr = $ci->session->userdata("userdata")['persissions'];
                $array = json_decode($arr);
                if (isset($array)) {
                    $fee_coll = 0;
                    foreach ($array as $key => $value) {
                        if (in_array('collection-allow', array($value->permission)) && $value->val == 'true') {
                            $fee_coll = 1;
                        }
                    }
                }
            }?>
        <div class="hint"><?php echo lang('help_fee_collection'); ?></div>
        <!-- Page Content start here -->
        <!--.row-->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-wrapper collapse in" aria-expanded="true">
                        <div class="panel-body">

                            <ul class="nav customtab nav-tabs" role="tablist">
                                <li role="presentation" class="nav-item active">
                                    <a href="#feecollection" class="nav-link" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="true">
                                        <span class="visible-xs"><i class="fa fa-percent"></i></span>
                                        <span class="hidden-xs"><?php echo lang('crumb_fee_collection') ?></span>
                                    </a>
                                </li>
                            </ul>

                            <!--tab content start here-->
                            <div class="tab-content">

                                <!-- Fee Collection -->
                                <div class="tab-pane active" id="feecollection" ng-controller="feeCollectionController" ng-init="initFeetypes()">
                                    <div id="feeCollectionContainer1">
                                        <form class="form-material" id="feeCollection_search_filter" name="feecollectionFilterForm" ng-submit="fetchFeeCollections(feecollectionFilterForm.$valid)" novalidate="">
                                            <!--/row-->
                                            <div class="row">
                                                
                                                <div class="col-md-3" id="feeFilterAcademicYears">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang("lbl_academic_year"); ?></label>
                                                        <select class="form-control" name="academic_year_id" ng-model="fcModel.academic_year_id" ng-init="initAcademicYears()" ng-change="initClasses(fcModel.academic_year_id)">
                                                            <option value="">--<?php echo lang("lbl_select_academic_year"); ?>--</option>
                                                            <option ng-repeat="ay in academicyears" value="{{ ay.id }}">{{ ay.name }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <!--/span-->
                                                <div class="col-md-3" id="feeFilterClasses">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang("lbl_class"); ?></label>
                                                        <select class="form-control" name="class_id" ng-model="fcModel.class_id" ng-change="initBatches(fcModel.class_id, fcModel.academic_year_id)">
                                                            <option value="">---<?php echo lang("select_course"); ?>--</option>
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
                                                        <input type="text" class="form-control" name="searchBy" placeholder="<?php echo lang('name_roll');?>" ng-model="fcModel.searchBy">
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
                                                    <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-search "></i> <?php echo lang("search"); ?></button>
                                                </div>
                                            </div>
                                            <!--/row-->
                                        </form>
                                        <div class="table-responsive" ng-if="feeCollectionStudents.length>0" style="margin-top:10px;">
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
                                                        <th><?php echo lang("th_action"); ?></th>
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
                                                        <td ng-if="std.ftCount==std.fcCount"><button class="btn btn-success btn-xs"><?php echo lang("lbl_paid"); ?></button></td>
                                                        <td ng-if="std.ftCount!=std.fcCount"><button class="btn btn-danger btn-xs"><?php echo lang('lbl_due') ?></button></td>
                                                        <td><button class="btn btn-primary btn-xs" ng-click="showDetails(std)"><?php echo lang('btn_view') ?></button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-md-12" ng-if="feeCollectionStudents.length===0">
                                            <span><?php echo lang("no_record"); ?></span>
                                        </div>
                                    </div>

                                    <div id="feeCollectionContainer2" class="hidden">
                                        <div class="row col-md-12">
                                            <div class="row pull-right" style="width: 100%; padding-bottom: 10px; padding-left:7px;">
                                                <a href="javascript:void(0);" ng-click="back()" class="btn btn-default pull-right"><i class="fa fa-reply " aria-hidden="true"></i></a>
                                            </div>
                                        </div>
                                        <div class="col-md-12" style="width:100%;">
                                            <div class="row well">
                                                <div class="col-md-2 text-center visible-xs">
                                                    <img ng-src="uploads/user/{{selectedStd.avatar}}" class="thumb-lg img-circle" alt="student-img">
                                                </div>
                                                <div class="col-md-2 text-center hidden-xs">
                                                    <img ng-src="uploads/user/{{selectedStd.avatar}}" class="thumb-lg img-circle" alt="student-img">
                                                </div>
                                                <div class="col-md-10" style="margin-top: 10px;">
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
                                                                        <th><?php echo lang("lbl_discount"); ?></th><td>{{ selectedStd.discount_name }}</td>
                                                                        <!--<th><?php echo lang('discount_amount') ?></th><td>{{ selectedStd.discount_amount }}%</td>-->
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br/>
                                        
                                        <div class="col-md-12 p-0">
                                            <div class="table-responsive">
                                                <table id="myTable" class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo lang("fees_group"); ?></th>
                                                            <th><?php echo lang("collected_by"); ?></th>
                                                            <th><?php echo lang("imp_std_class"); ?></th>
                                                            <th><?php echo lang("imp_std_section"); ?></th>
                                                            <th><?php echo lang("lbl_status"); ?></th>
                                                            <th><?php echo lang("amount"); ?></th>
                                                            <th><?php echo lang("lbl_date"); ?></th>
                                                            <th><?php echo lang("lbl_discount"); ?></th>
                                                            <th><?php echo lang("lbl_paid"); ?></th>
                                                            <th><?php echo lang("lbl_paid_fee_percentage"); ?></th>
                                                            <th><?php echo lang("lbl_balance"); ?></th>
                                                            <?php if ($this->session->userdata("userdata")["role_id"] == ADMIN_ROLE_ID) { ?>
                                                                <th class='text-center'><?php echo lang("th_action"); ?></th>
                                                            <?php }?>
                                                            <?php if(isset($fee_coll) && $fee_coll == '1' && $this->session->userdata("userdata")["role_id"] == EMPLOYEE_ROLE_ID){?>
                                                                <th class='text-center'><?php echo lang("th_action"); ?></th>
                                                            <?php }?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr ng-repeat="(key,rcd) in stdFeeRecords">
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
                                                            <td>{{rcd[0].paid_date}}</td>
                                                            <td>{{ rcd[0].discount + '%' }}</td>
                                                            <td><?php echo $this->session->userdata('userdata')['currency_symbol'] ?>{{ countPaidAmount(rcd) }}</td>
                                                            <td ng-if="rcd[0].paid_amount==='-'">-</td>
                                                            <td> {{ calculatePaidFeePercentage(rcd) | number:1 }}%</td>
                                                            <td>
                                                                <strong ng-if="rcd[0].fee_collection_id==='NULL'">{{ '-'+rcd[0].discounted_amount }} <?php echo $this->session->userdata('userdata')['currency_symbol'] ?></strong>
                                                                <strong ng-if="rcd[0].fee_collection_id!=='NULL'">{{calculateBalance(rcd)}}</strong>
                                                            </td>
                                                            <?php if ($this->session->userdata("userdata")["role_id"] == ADMIN_ROLE_ID) { ?>
                                                                <td style="min-width:150px;" class='text-center'>
                                                                    <a href="javascript:void(0)" ng-show="rcd.length>1" class="btn-sm btn btn-primary btn-circle text-white" ng-click="showPartiallyFeeDeatils(rcd)"><i class="fa fa-info"></i></a>
                                                                    <a href="javascript:void(0)" class="btn-sm btn btn-info btn-circle text-white" ng-click="setEditFeeCollectionModel(rcd)" data-toggle="modal" data-target="#feeCollectionEditModel" ng-class="{'custom_disable':isAllFeePaid(rcd)}" ng-show="rcd[0].fee_collection_id!='NULL'"><i class="fa fa-plus"></i></a>
                                                                    <a href="javascript:void(0)" ng-click="showConfirmationAlert(rcd,'all')" ng-show="rcd[0].fee_collection_id!=='NULL'" class="btn-sm btn btn-danger btn-circle text-white"><i class="fa fa-trash-o"></i></a>
                                                                    <a href="<?php echo base_url(); ?>forms/show?id={{rcd[0].fee_collection_id}}&requested_page=single_fee&class_name={{rcd[0].class_name}}&batch_name={{rcd[0].batch_name}}" target="_blank" ng-if="rcd[0].fee_collection_id !== 'NULL'" class="btn-sm btn btn-success btn-circle text-white"><i class="fa fa-print"></i></a>
                                                                    <a href="javascript:void(0);" ng-if="rcd.length==0 || rcd[0].fee_collection_id === 'NULL'" ng-click="setAddFeeCollectionModel(rcd[0])" data-toggle="modal" data-target="#feeCollectionAddModel" class='btn btn-primary btn-sm text-white'><?php echo lang("collect_fee"); ?></a>
                                                                </td>
                                                            <?php } ?>
                                                            <?php if(isset($fee_coll) && $fee_coll == '1' && $this->session->userdata("userdata")["role_id"] == EMPLOYEE_ROLE_ID){?>
                                                                <td style="min-width:150px;" class='text-center'>
                                                                    <a href="javascript:void(0)" ng-show="rcd.length>1" class="btn-sm btn btn-primary btn-circle text-white" ng-click="showPartiallyFeeDeatils(rcd)"><i class="fa fa-info"></i></a>
                                                                    <a href="javascript:void(0)" class="btn-sm btn btn-info btn-circle text-white" ng-click="setEditFeeCollectionModel(rcd)" data-toggle="modal" data-target="#feeCollectionEditModel" ng-class="{'custom_disable':isAllFeePaid(rcd)}" ng-show="rcd[0].fee_collection_id!='NULL'"><i class="fa fa-plus"></i></a>
                                                                    <a href="javascript:void(0)" ng-click="showConfirmationAlert(rcd,'all')" ng-show="rcd[0].fee_collection_id!=='NULL'" class="btn-sm btn btn-danger btn-circle text-white"><i class="fa fa-trash-o"></i></a>
                                                                    <a href="<?php echo base_url(); ?>forms/show?id={{rcd[0].fee_collection_id}}&requested_page=single_fee&class_name={{rcd[0].class_name}}&batch_name={{rcd[0].batch_name}}" target="_blank" ng-if="rcd[0].fee_collection_id !== 'NULL'" class="btn-sm btn btn-success btn-circle text-white"><i class="fa fa-print"></i></a>
                                                                    <a href="javascript:void(0);" ng-if="rcd.length==0 || rcd[0].fee_collection_id === 'NULL'" ng-click="setAddFeeCollectionModel(rcd[0])" data-toggle="modal" data-target="#feeCollectionAddModel" class='btn btn-primary btn-sm text-white'><?php echo lang("collect_fee"); ?></a>
                                                                </td>
                                                            <?php } ?>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- start edit fee collection modal -->
                                    <div class="modal fade" id="feeCollectionEditModel" tabindex="-1" role="dialog" aria-labelledby="feeCollectionEditModel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content" id="fee-collection-model-contents">
                                                <form name="feeCollectionEditModelForm" ng-submit="collectFeeUpdate(feeCollectionEditModelForm.$valid)" novalidate="" class="form-material">
                                                    <div class="form-body">
                                                        <div class="panel panel-primary">
                                                            <div class="panel-heading"><?= lang("lbl_update_collected_fee_form"); ?></div>
                                                            <div class="panel-body">
                                                                <div class="form-group">
                                                                    <label><?= lang("lbl_date"); ?></label>
                                                                    <input type="text" ng-model="editModel.paid_date" ng-value="editModel.paid_date" class="form-control mydatepicker-autoclose">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label><?= lang("lbl_discounted_amount"); ?></label>
                                                                    <input type="text" class="form-control" disabled="" ng-value="(editModel.amount-(editModel.amount*(editModel.discount/100)))" />
                                                                </div>
                                                                <div class="form-group">
                                                                    <label><?= lang("lbl_discount"); ?></label>
                                                                    <input type="text" class="form-control" disabled="" ng-value="editModel.discount+'%'" />
                                                                </div>
                                                                <div class="form-group">
                                                                    <label><?= lang("lbl_payable_amount"); ?></label>
                                                                    <input type="number" name="editPaidAmount" required="" ng-model="editPaidAmount" max="{{maxEditPaidAmount}}" min="0" class="form-control" />
                                                                </div>
                                                                <div class="form-group visible-xs">
                                                                    <label><?= lang("lbl_payment_mode"); ?></label>
                                                                    <div class="radio radio-primary">
                                                                        <input type="radio" name="mode" id="rbcash" ng-model="editModel.mode" value="cash" checked="checked" />
                                                                        <label for="rbcash"> <?= lang("lbl_cash"); ?> </label>
                                                                    </div>
                                                                    <div class="radio radio-primary">
                                                                        <input type="radio" name="mode" id="rbcheque" ng-model="editModel.mode" value="cheque" />
                                                                        <label for="rbcheque"> <?= lang("lbl_cheque"); ?> </label>
                                                                    </div>
                                                                    <div class="radio radio-primary">
                                                                        <input type="radio" name="mode" ng-model="editModel.mode" id="rbdd" value="dd" />
                                                                        <label for="rbdd"> <?= lang("lbl_dd"); ?> </label>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group hidden-xs">
                                                                    <label><?= lang("lbl_payment_mode"); ?></label>
                                                                    <div class="form-inline" style="margin-top: 10px; margin-left: 20px;">
                                                                        <div class="radio radio-primary" style="padding-left:5px;">
                                                                            <input type="radio" name="mode" id="rbcash" ng-model="editModel.mode" value="cash" checked="" />
                                                                            <label for="rbcash"> <?= lang("lbl_cash"); ?> </label>
                                                                        </div>
                                                                        <div class="radio radio-primary">
                                                                            <input type="radio" name="mode" id="rbcheque" ng-model="editModel.mode" value="cheque" />
                                                                            <label for="rbcheque"> <?= lang("lbl_cheque"); ?> </label>
                                                                        </div>
                                                                        <div class="radio radio-primary">
                                                                            <input type="radio" name="mode" ng-model="editModel.mode" id="rbdd" value="dd" />
                                                                            <label for="rbdd"> <?= lang("lbl_dd"); ?> </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="checkbox checkbox-info">
                                                                        <input id="checkbox4" type="checkbox" name="isSendEmailToParent" ng-model="isSendEmailToParentEdit" />
                                                                        <label for="checkbox4"> <?php echo lang("lbl_send_email_to_parent"); ?> </label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="checkbox checkbox-info">
                                                                        <input id="checkbox5" type="checkbox" name="isSendSMSToParent" ng-model="isSendSMSToParentEdit" />
                                                                        <label for="checkbox5"> <?php echo lang("lbl_send_sms_to_parent"); ?> </label>
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

                                    <!-- start fee collection modal -->
                                    <div class="modal fade" id="feeCollectionAddModel" tabindex="-1" role="dialog" aria-labelledby="feeCollectionAddModel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content" id="fee-collection-model-contents">
                                                <form name="feeCollectionAddModelForm" ng-submit="collectFee(feeCollectionAddModelForm.$valid)" novalidate="" class="form-material">
                                                    <div class="form-body">
                                                        <div class="panel panel-primary">
                                                            <div class="panel-heading"><?= lang("lbl_fee_form"); ?></div>
                                                            <div class="panel-body">
                                                                <div class="form-group">
                                                                    <label><?= lang("lbl_date"); ?></label>
                                                                    <input type="text" ng-value="afcModel.created_at" disabled="" class="form-control mydatepicker-autoclose">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label><?= lang("lbl_discounted_amount"); ?></label>
                                                                    <input type="text" class="form-control" disabled="" ng-value="afcModel.discounted_amount" />
                                                                </div>
                                                                <div class="form-group">
                                                                    <label><?= lang("lbl_discount"); ?></label>
                                                                    <input type="text" class="form-control" disabled="" ng-value="afcModel.discount+'%'" />
                                                                </div>
                                                                <div class="form-group">
                                                                    <label><?= lang("lbl_paid_amount"); ?></label>
                                                                    <input type="number" class="form-control" max="{{afcModel.discounted_amount}}" min="0" required="" ng-model="paid_amount" />
                                                                </div>

                                                                <div class="form-group visible-xs">
                                                                    <label><?= lang("lbl_payment_mode"); ?></label>
                                                                    <div class="radio radio-primary">
                                                                        <input type="radio" name="mode" id="rbcash" ng-model="mode" value="cash" checked="checked" />
                                                                        <label for="rbcash"> <?= lang("lbl_cash"); ?> </label>
                                                                    </div>
                                                                    <div class="radio radio-primary">
                                                                        <input type="radio" name="mode" id="rbcheque" ng-model="mode" value="cheque" />
                                                                        <label for="rbcheque"> <?= lang("lbl_cheque"); ?> </label>
                                                                    </div>
                                                                    <div class="radio radio-primary">
                                                                        <input type="radio" name="mode" ng-model="mode" id="rbdd" value="dd" />
                                                                        <label for="rbdd"> <?= lang("lbl_dd"); ?> </label>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group hidden-xs">
                                                                    <label><?= lang("lbl_payment_mode"); ?></label>
                                                                    <div class="form-inline" style="margin-top: 10px; margin-left: 20px;">
                                                                        <div class="radio radio-primary" style="padding-left:5px;">
                                                                            <input type="radio" name="mode" id="rbcash" ng-model="mode" value="cash" checked="" />
                                                                            <label for="rbcash"> <?= lang("lbl_cash"); ?> </label>
                                                                        </div>
                                                                        <div class="radio radio-primary">
                                                                            <input type="radio" name="mode" id="rbcheque" ng-model="mode" value="cheque" />
                                                                            <label for="rbcheque"> <?= lang("lbl_cheque"); ?> </label>
                                                                        </div>
                                                                        <div class="radio radio-primary">
                                                                            <input type="radio" name="mode" ng-model="mode" id="rbdd" value="dd" />
                                                                            <label for="rbdd"> <?= lang("lbl_dd"); ?> </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="checkbox checkbox-info">
                                                                        <input id="checkbox4" type="checkbox" name="isSendEmailToParent" ng-model="isSendEmailToParent" />
                                                                        <label for="checkbox4"> <?php echo lang("lbl_send_email_to_parent"); ?> </label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="checkbox checkbox-info">
                                                                        <input id="checkbox5" type="checkbox" name="isSendSMSToParent" ng-model="isSendSMSToParent" />
                                                                        <label for="checkbox5"> <?php echo lang("lbl_send_sms_to_parent"); ?> </label>
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
                                    
                                    <!--Partially Fee detail Model-->
                                    <div id="myModalYasir" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content" style='top:<?php if($this->session->userdata("site_lang") == "english") { echo "112px"; } else { echo "-180px"; } ?>'>
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
                                                                <th class='text-center'><?php echo lang("th_action"); ?></th>
                                                            </tr>
                                                            <tr class="text-center" ng-repeat="row in partiallyFeeDetailModel">
                                                                <td>{{ $index+1 }}</td>
                                                                <td>{{ row.collector_name }}</td>
                                                                <td>{{ row.paid_date }}</td>
                                                                <td><?php echo $this->session->userdata('userdata')['currency_symbol'] ?>{{row.paid_amount}}</td>
                                                                <td> {{ (row.paid_amount * 100) / (row.amount-(row.amount*(row.discount/100))) | number:1 }}%</td>
                                                                <td>
                                                                    <!--<a href="javascript:void(0)" class="btn-sm btn btn-info btn-circle text-white" ng-click="setEditFeeCollectionModel(row)" data-toggle="modal" data-target="#feeCollectionEditModel"><i class="fa fa-pencil"></i></a>-->
                                                                    <a href="javascript:void(0)" ng-click="showConfirmationAlert(row,'null')" class="btn-sm btn btn-danger btn-circle text-white"><i class="fa fa-trash-o"></i></a>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--Partially detail model end-->
                                    
                                </div>
                                <!-- /Fee Collection -->

                            </div>
                            <!-- /Fee Setup -->
                        </div>
                    </div>
                    <!--tab content end here-->
                </div>
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
<?php include(APPPATH . "views/inc/footer.php"); ?>