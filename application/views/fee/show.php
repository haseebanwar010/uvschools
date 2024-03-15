<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('crumb_fee_settings') ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="#"><?php echo lang('lbl_dashboard') ?></a></li>
                    <li><a href="#"><?php echo lang('settings') ?></a></li>
                    <li class="active"><?php echo lang('crumb_fee_settings') ?></li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <!-- Page Content start here -->
        <div class="hint"><?php echo lang('help_fee_setting'); ?></div>
        <!--.row-->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">

                    <div class="panel-wrapper collapse in" aria-expanded="true">
                        <div class="panel-body">

                            <ul class="nav customtab nav-tabs" role="tablist">
                                <li role="presentation" class="nav-item"><a href="#feetype" class="nav-link active"
                                                                            aria-controls="profile" role="tab"
                                                                            data-toggle="tab"
                                                                            aria-expanded="true"><span
                                            class="visible-xs"><i class="fa fa-money"></i></span><span
                                            class="hidden-xs"><?php echo lang('fee_type') ?></span></a>
                                </li>
                                <li role="presentation" class="nav-item"><a href="#feediscount" class="nav-link"
                                                                            aria-controls="profile" role="tab"
                                                                            data-toggle="tab"
                                                                            aria-expanded="true"><span
                                            class="visible-xs"><i class="fa fa-percent"></i></span><span
                                            class="hidden-xs"><?php echo lang('fee_discount') ?></span></a>
                                </li>
                            </ul>


                            <!--tab content start here-->
                            <div class="tab-content">

                                <!-- Fee type -->
                                <div class="tab-pane active" id="feetype" ng-controller="feetypeConroller" ng-init="initClasses(); initFeetypes(); initCountries()">
                                    <div class="hint"><?php echo lang('help_fee_types'); ?></div>
                                    <form class="form-material" name="feetypeFilerForm" ng-submit="onSubmitFetchClassFeeTypes(feetypeFilerForm.$valid)" novalidate="">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                                    <select class="form-control" required="" ng-model="fModel.class_id">
                                                        <option value="">---<?php echo lang('select_course') ?>---</option>
                                                        <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary"><?php echo lang('search') ?></button>
                                            </div>
                                        </div>
                                    </form>
                                    <br/>

                                    <div class="col-md-12" id="feetypecontainer" style="padding:0;">
                                        <div ng-if="classFeetypes.length>0 && classFeetypes[0]!='<'">
                                            <div class="table-responsive">
                                                <table id="myTable" class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th><?php echo lang('fee_type') ?></th>
                                                            <th><?php echo lang('due_date') ?></th>
                                                            <th><?php echo lang('amount') ?></th>
                                                            <th><?php echo lang('description') ?></th>
                                                            <th class="text-center"><?php echo lang('lbl_action') ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr ng-repeat="(key,cft) in classFeetypes">
                                                            <td>{{ key+1 }}</td>
                                                            <td>{{ cft.name }}</td>
                                                            <td>{{ cft.due_date }}</td>
                                                            <td><?php echo $this->session->userdata('userdata')['currency_symbol'] ?>{{ cft.amount }}</td>
                                                            <td>{{ cft.description }}</td>
                                                            <td class="text-center ">
                                                                <button type="button" class="btn btn-rounded btn-secondary" data-toggle="modal" data-target="#addFeeVariants" ng-click="saveSelectFeetypeID(cft.id)" title="Add Varient types"><?php echo lang('lbl_varients');?></i></button>
                                                                <button type="button" class="btn btn-info btn-circle" data-toggle="modal" ng-click="setEditValues(cft)" data-target="#eidtFeetypeModal"><i class="fa fa-pencil"></i></button>
                                                                <button type="button" class="btn btn-danger btn-circle" ng-click="showConfirmationAlert(cft.id)"><i class="fa  fa-trash-o"></i></button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>


                                        </div>
                                        <div class="col-md-12" style="padding: 0;">
                                            <button type="button" data-toggle="modal" data-target="#addfeetype" class="fcbtn btn btn-primary btn-1e"><i class="fa fa-plus "></i><?php echo lang('add_fee_type') ?></button>
                                        </div>
                                    </div>

                                    <div ng-if="classFeetypes.length===0">
                                        <span class="text-danger"><?php echo lang('no_record') ?></span><br/><br/>
                                        <!--<button type="button" data-toggle="modal" data-target="#addfeetype" class="fcbtn btn btn-outline btn-info btn-1e"><i class="fa fa-plus "></i>Add Fee type</button>-->
                                    </div>
                                    <!--/row-->


                                    <!-- Add Variants Modal Content -->
                                    <div class="modal fade" id="addFeeVariants" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content" id="addFeeVariants-content">
                                                <div class="panel panel-primary">
                                                    <div class="panel-heading"><?php echo lang('lbl_feetype_varients_form') ?></div>
                                                    <div class="panel-body">
                                                        <form name="addFeeVariantsForm" ng-submit="saveVariants(addFeeVariantsForm.$valid)" novalidate="" class="form-material ">
                                                            <div class="form-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label"><?php echo lang('fee_type') ?></label>
                                                                            <select class="form-control feetypes_select2"  ng-model="vModel.fee_types" style="width: 100%" multiple="" required="">
                                                                                <option ng-repeat="c in classFeetypes" value="{{c.id}}">{{c.name}}</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label"><?php echo lang('lbl_name') ?></label>
                                                                            <input type="text" class="form-control" ng-model="vModel.title" required="">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label"><?php echo lang('lbl_admission_from') ?></label>
                                                                            <input type="text" required="" ng-model="vModel.admission_from" class="form-control mydatepicker-autoclose">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label"><?php echo lang('lbl_admission_to') ?></label>
                                                                            <input type="text" required="" ng-model="vModel.admission_to" class="form-control mydatepicker-autoclose">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label"><?php echo lang('lbl_nationality') ?></label>
                                                                            <select class="form-control js-data-example-ajax"  ng-model="vModel.nationality" style="width: 100%" multiple="" required="">
                                                                                <option ng-repeat="c in countries" value="{{c.id}}">{{c.country_name}}</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label"><?php echo lang('amount') ?></label>
                                                                            <input type="number" required="" ng-model="vModel.percentage" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                            </div>
                                                            <div class="row" style="margin-left: 0;">
                                                                <div style="margin-right: 8px">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('lbl_close') ?></button>
                                                                </div>
                                                                <div>
                                                                    <button type="submit" class="btn btn-primary"><?php echo lang('lbl_save') ?></button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                        <div class="col-md-12" style="padding:0; margin-top:20px; max-height: 250px; overflow-y: auto;" ng-if="selectedFeeTypeVarients.length>0">
                                                            <table class="table table-bordered table-striped table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th><?php echo lang('lbl_name'); ?></th>
                                                                        <th><?php echo lang('lbl_admission_from');?></th>
                                                                        <th><?php echo lang('lbl_admission_to');?></th>
                                                                        <th><?php echo lang('lbl_nationality');?></th>
                                                                        <th><?php echo lang('amount');?></th>
                                                                        <th><?php echo lang('lbl_action');?></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr ng-repeat="sfts in selectedFeeTypeVarients">
                                                                        <td>{{$index+1}}</td>
                                                                        <td>{{sfts.title}}</td>
                                                                        <td>{{sfts.admission_from}}</td>
                                                                        <td>{{sfts.admission_to}}</td>
                                                                        <td>{{sfts.nationality}}</td>
                                                                        <td>{{sfts.percentage}}</td>
                                                                        <td>
                                                                            <button type="button" class="btn btn-info" data-toggle="modal"data-target="#editFeeVariants" ng-click="setEditVariant(sfts)" data-backdrop="static" data-keyboard="false"><i class="fa fa-pencil"></i></button>
                                                                            <button href="javascript:void(0)" ng-click="deleteVarient(sfts.id)" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                                                            
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <div ng-if="selectedFeeTypeVarients.length==0" style="padding:0; margin-top:20px;"  class="col-md-12 text-danger"><?php echo lang('no_record'); ?></div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/Add new end here-->


                                    <!-- Add Variants Modal Content -->
                                    <div class="modal fade" id="editFeeVariants" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="panel panel-primary">
                                                    <div class="panel-heading">Edit Fee Type Variant</div>
                                                    <div class="panel-body">
                                                        <form name="editFeeVariantsForm" ng-submit="updateVariant(editFeeVariantsForm.$valid)" novalidate="" class="form-material ">
                                                            <div class="form-body">

                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label"><?php echo lang('lbl_name') ?></label>
                                                                            <input type="text" required="" ng-model="editVariant.title" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <!--/span-->
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label"><?php echo lang('lbl_admission_from') ?></label>
                                                                            <input type="text" required="" ng-model="editVariant.admission_from" class="form-control mydatepicker-autoclose">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label"><?php echo lang('lbl_admission_to') ?></label>
                                                                            <input type="text" required="" ng-model="editVariant.admission_to" class="form-control mydatepicker-autoclose">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label"><?php echo lang('lbl_nationality') ?></label>
                                                                            <select class="form-control"  ng-model="editVariant.n_id" required="">
                                                                                <option ng-repeat="c in countries" value="{{c.id}}">{{c.country_name}}</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label"><?php echo lang('amount') ?></label>
                                                                            <input type="number" required="" ng-model="editVariant.percentage" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                            </div>
                                                            <div class="row" style="margin-left: 0;">
                                                                <div style="margin-right: 8px">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('lbl_close') ?></button>
                                                                </div>
                                                                <div>
                                                                    <button type="submit" class="btn btn-primary"><?php echo lang('lbl_save') ?></button>
                                                                </div>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <!-- Add new Modal Content -->
                                    <div class="modal fade" id="addfeetype" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content" id="addfeetype-content">
                                                <div class="panel panel-primary">
                                                    <div class="panel-heading"><?php echo lang('add_fee_type') ?></div>
                                                    <div class="panel-body">
                                                        <form name="addFeetypeForm" ng-submit="saveFeetype(addFeetypeForm.$valid)" novalidate="" class="form-material ">
                                                            <div class="form-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label"><?php echo lang('fee_type') ?></label>
                                                                            <input type="text" required="" ng-model="adModel.name" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label"><?php echo lang('amount') ?></label>
                                                                            <input type="number" required="" ng-model="adModel.amount" class="form-control" >
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <!--/span-->
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label"><?php echo lang('due_date') ?></label>
                                                                            <input type="text" ng-model="adModel.due_date" placeholder="mm/dd/yyyy" required="" class="form-control mydatepicker-autoclose">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!--/span-->
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label"><?php echo lang('description') ?></label>
                                                                            <textarea  ng-model="adModel.description" class="form-control"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="col-md-6">
                                                                            <label><?php echo lang('select_classes') ?></label></div>
                                                                        <div class="col-md-6">
                                                                            <div class="pull-right  form-group checkbox checkbox-info checkbox-circle">

                                                                                <input  ng-model="adModel.selectall" ng-change="select_all()" id="allC" type="checkbox">
                                                                                <label for="allC"><?php echo lang('select_all') ?></label>
                                                                            </div>
                                                                        </div>
                                                                        <div style="color:#a94442" ng-show="adModel.class_error"><?php echo lang('select_atleast_class') ?></div>
                                                                        <div ng-repeat="cls in classes">
                                                                            <div class="col-md-4 form-group checkbox checkbox-info checkbox-circle"  >

                                                                                <input ng-model="adModel.checkall[cls.id]" name="foo" type="checkbox" id="cl{{cls.id}}">
                                                                                <label for="cl{{cls.id}}">{{cls.name}}</label>
                                                                            </div>
                                                                        </div>


                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                            </div>
                                                            <div class="row pull-right">
                                                                <div style="margin-right: 8px">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('lbl_close') ?></button>
                                                                </div>
                                                                <div>
                                                                    <button type="submit" class="btn btn-primary"><?php echo lang('lbl_save') ?></button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/Add new end here-->

                                    <!-- Modal Content -->
                                    <div class="modal fade" id="eidtFeetypeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content" id="editfeetype-contents">
                                                <div class="panel panel-info">
                                                    <div class="panel-heading"><?php echo lang('edit_fee_type') ?></div>
                                                    <div class="panel-body">
                                                        <form name="editFeetypeForm" ng-submit="updateFeetype(editFeetypeForm.$valid)" novalidate="" class="form-material ">
                                                            <div class="form-body">
                                                                <input type="hidden" name="id" ng-value="editModel.id" />
                                                                <input type="hidden" name="date" ng-value="editModel.date" />
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label"><?php echo lang('fee_type') ?></label>
                                                                            <input type="text" name="name" required="" ng-value="editModel.name" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label"><?php echo lang('amount') ?></label>
                                                                            <input type="number" name="amount" required="" ng-value="editModel.amount" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label"><?php echo lang('due_date') ?></label>
                                                                            <input type="text" name="due_date" required="" ng-value="editModel.due_date" placeholder="mm/dd/yyyy" class="form-control mydatepicker-autoclose">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label"><?php echo lang('description') ?></label>
                                                                            <textarea name="description" ng-value="editModel.description" class="form-control"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->

                                                            </div>
                                                            <div class="row pull-right">
                                                                <div style="margin-right: 8px">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('lbl_close') ?> </button>
                                                                </div>
                                                                <div>
                                                                    <button type="submit" class="btn btn-primary"><?php echo lang('btn_update') ?> </button>
                                                                </div>
                                                            </div>

                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                    <!--/Edit Modal end here-->

                                </div>
                                <!-- /Fee type-->

                                <!-- fee discount -->
                                <div class="tab-pane" id="feediscount" ng-controller="feeDiscountController" ng-init="init()">

                                    <!-- Add new Modal Content -->
                                    <div class="modal fade" id="addDiscount" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content" id="addfeetype-content">
                                                <div class="panel panel-primary">
                                                    <div class="panel-heading"><?php echo lang('add_discount_type') ?></div>
                                                    <div class="panel-body">
                                                        <form name="saveDiscountform" ng-submit="onSaveDiscount(saveDiscountform.$valid)" novalidate="" class="form-material ">
                                                            <div class="form-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label"><?php echo lang('discount_name') ?></label>
                                                                            <input type="text" required="" ng-model="discountModel.name" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--<div class="row">
                                                                    <div class="form-group col-md-12">
                                                                        <label class="control-label"><?php echo lang('lbl_class') ?></label> 
                                                                        <select style="width: 100%" class="form-control js-example-basic-multiple" required="" multiple="multiple" ng-model="discountModel.class_id">
                                                                            <option value="">---<?php echo lang('select_course') ?>---</option>
                                                                            <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                                                                        </select>
                                                                    </div>
                                                                </div>-->
                                                                <!--/span-->
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label"><?php echo lang('description') ?></label>
                                                                            <textarea  required="" ng-model="discountModel.description" class="form-control"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->                                                                                                                                <div class="row">
<!--                                                                    <div class="col-md-12">
                                                                        <div class="col-md-6">
                                                                            <label><?php echo lang('select_classes') ?></label></div>
                                                                        <div class="col-md-6">
                                                                            <div class="pull-right  form-group checkbox checkbox-info checkbox-circle">

                                                                                <input  ng-model="discountModel.selectall" ng-change="select_all()" id="allC_dis" type="checkbox">
                                                                                <label for="allC_dis"><?php echo lang('select_all') ?></label>
                                                                            </div>
                                                                        </div>
                                                                        <div style="color:#a94442" ng-show="discountModel.class_error"><?php echo lang('select_atleast_class') ?></div>
                                                                        <div ng-repeat="cls in classes">
                                                                            <div class="col-md-4 form-group checkbox checkbox-info checkbox-circle"  >

                                                                                <input ng-model="discountModel.checkall[cls.id]" name="foo" type="checkbox" id="dis{{cls.id}}">
                                                                                <label for="dis{{cls.id}}">{{cls.name}}</label>
                                                                            </div>
                                                                        </div>


                                                                    </div>-->
                                                                </div>
                                                                <!--/span-->
                                                            </div>
                                                            <div class="row pull-right">
                                                                <div style="margin-right: 8px">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('lbl_close') ?></button>
                                                                </div>
                                                                <div>
                                                                    <button type="submit" class="btn btn-primary"><?php echo lang('lbl_save') ?></button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/Add new end here-->
                                    <!-- discount filters -->
                                    <div class="hint"><?php echo lang('help_fee_discount'); ?></div>
<!--                                    <form class="form-material" name="discountsFilterForm" ng-submit="getDiscounts(discountsFilterForm.$valid)" novalidate="">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                                    <select class="form-control" required="" ng-model="selectedClass" ng-init="selectedClass = ''">
                                                        <option value="">---<?php echo lang('select_course') ?>---</option>
                                                        <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary"><?php echo lang('search') ?></button>
                                            </div>
                                        </div>
                                    </form>
                                    <br/>-->
                                    <div class="table-responsive">
                                        <table id="myTable" ng-show="discounts.length>0" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th><?php echo lang('discount_name') ?></th>
                                                    <!--<th><?php echo lang('lbl_class') ?></th>-->
                                                    <th><?php echo lang('description') ?></th>
                                                    <th class="text-center"><?php echo lang('lbl_action') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr ng-repeat="(key,disc) in discounts">
                                                    <td>{{ key+1 }}</td>
                                                    <td>{{ disc.name }}</td>
                                                    <!--<td>{{ disc.class_name }}</td>-->
                                                    <td>{{ disc.description }}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-rounded btn-secondary" data-toggle="modal" data-target="#feeDiscountVarientModal" ng-click="discouts(disc.id)" title="Add Varient types" data-backdrop="static" data-keyboard="false"><?php echo lang('lbl_varients');?></i></button>
                                                        <button type="button" class="btn btn-info btn-circle" data-toggle="modal" ng-click="onUpdateDiscount(disc)" data-target="#editDiscount333" data-backdrop="static" data-keyboard="false"><i class="fa fa-pencil"></i></button>
                                                        <button type="button" class="btn btn-danger btn-circle" ng-click="showConfirmationAlert(disc.id)"><i class="fa  fa-trash-o"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12" id="feetypecontainer" style="padding:0;">
                                        <!-- ngIf: classFeetypes.length>0 && classFeetypes[0]!='<' -->
                                        <div class="col-md-12" style="padding: 0;">
                                            <button type="button" data-toggle="modal" data-target="#addDiscount" class="fcbtn btn btn-primary btn-1e"><i class="fa fa-plus "></i><?php echo lang("add_discount_type"); ?></button>
                                        </div>
                                    </div>
                                    <div ng-if="discounts.length===0">
                                        <span class="text-danger"><?php echo lang('no_record') ?></span><br/><br/>
                                    </div>


                                    <!-- Modal Content -->
                                    <div class="modal fade" id="editDiscount333" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content" id="editfeetype-contents">
                                                <div class="panel panel-info"> 
                                                    <button type="button" class="close" style="margin: 20px;" data-dismiss="modal" aria-hidden="true">×</button>
                                                    <div class="panel-heading"><?php echo lang('edit_discount_type') ?></div>
                                                    <div class="panel-body">
                                                        <form name="editDiscountForm" ng-submit="updateDiscount(editDiscountForm.$valid)" novalidate="" class="form-material ">
                                                            <div class="form-body">
                                                                <!--<input type="hidden" name="id" ng-value="myModel2.id" />-->
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label"><?php echo lang('discount_name') ?></label>
                                                                            <input type="text" name="name" required="" ng-model="myModel2.name" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--<div class="row">
                                                                    <div class="form-group col-md-12">
                                                                        <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                                                        <select class="form-control" required="" ng-model="myModel2.class_id">
                                                                            <option value="">---<?php echo lang('select_course') ?>---</option>
                                                                            <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                                                                        </select>
                                                                    </div>
                                                                </div>-->
                                                                <!--/span-->
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label"><?php echo lang('description') ?></label>
                                                                            <textarea name="description" ng-model="myModel2.description" class="form-control"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->                                                                                                                                <div class="row">
                                                                </div>
                                                                <!--/span-->
                                                            </div>
                                                            <div class="row pull-right">
                                                                <div style="margin-right: 8px">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('lbl_close') ?> </button>
                                                                </div>
                                                                <div>
                                                                    <button type="submit" class="btn btn-primary"><?php echo lang('btn_update') ?> </button>
                                                                </div>
                                                            </div>

                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.modal -->


                                    <!-- sample modal content -->
                                    <div class="modal fade" id="feeDiscountVarientModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;" ng-init="initClasses()">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title text-center" id="myLargeModalLabel"><?php echo lang('lbl_discount_varients') ?><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button></h4>
                                                    <div class="text-center"><a href="" class="btn "><small><?php echo lang('discount_name');?>:-  </small> {{discount_name}}</a></div>

                                                </div>

                                                <div class="modal-body">
                                                    <div class="row text-danger" ng-if="selectedDiscountVarients.message">{{selectedDiscountVarients.message}}</div>
                                                    <div class="row" ng-hide="selectedDiscountVarients.message">
                                                        <form style="width: 100%" name="dis_var_form">
                                                            <div style="padding: 0px 10%;">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                                                    <select class="form-control" required="" ng-model="class" ng-change="getDiscountVarients()">
                                                                        <option value="">---<?php echo lang('select_course') ?>---</option>
                                                                        <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <table class="table table-bordered text-center" ng-show="selectedDiscountVarients.length!=0">
                                                                <thead>
                                                                    <tr>
                                                                        <th><?php echo lang('fee_type') ?></th>
                                                                        <th><?php echo lang('lbl_type') ?></th>
                                                                        <th style="width: 100px;"><?php echo lang('lbl_amount') ?></th>
                                                                        <th><?php echo lang('th_action') ?></th>
                                                                    </tr>
                                                                </thead>
                                                                <tfoot>
                                                                    <tr>
                                                                        <th><?php echo lang('fee_type') ?></th>
                                                                        <th><?php echo lang('lbl_type') ?></th>
                                                                        <th style="width: 100px;"><?php echo lang('lbl_amount') ?></th>
                                                                        <th><?php echo lang('th_action') ?></th>
                                                                    </tr>
                                                                </tfoot>
                                                                <tbody>  
                                                                    <tr ng-repeat="d in selectedDiscountVarients">
                                                                        
                                                                        <td>
                                                                            <div class="form-group">
                                                                                <p><strong>{{d.fee_type}}</strong></p>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group">
                                                                                <select class="form-control" ng-model="d.type" value="d.type">
                                                                                    <option value=""><?php echo lang("lbl_select_amount_type"); ?></option>
                                                                                    <option value="number"><?php echo lang("lbl_number"); ?></option>
                                                                                    <option value="percentage"><?php echo lang("percentage"); ?></option>
                                                                                </select>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group">
                                                                                <!--<input type="number" min="0" max="100" placeholder="Between (0 - 100)%" class="form-control" ng-model="d.percentage"  value= "{{d.percentage}}">-->
                                                                                <input type="number" class="form-control" ng-model="d.percentage"  value= "{{d.percentage}}">
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <button ng-click="resetVarents(d.varient_id, d.discount_id);" class="btn btn-danger btn-circle" ><i class="fa fa-trash-o"></i></button>
                                                                        </td>
                                                                    </tr> 
                                                                </tbody>
                                                            </table>

                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal"><?php echo lang('lbl_close');?></button>
                                                        <button ng-show="selectedDiscountVarients.length!=0" type="button" class="btn btn-success" ng-click="saveDiscountVarents();"><i class="fa fa-check"></i> <?php echo lang('btn_save') ?></button>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->

                                    </div>
                                    <!-- /fee discount -->
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
<script>
    var datepicker_config = {
        changeMonth: true,
        changeYear: true,
        showSecond: false,
        controlType: 'select',
        yearRange: "-50:+10",
        dateFormat: 'dd/mm/yy',
        timeFormat: 'hh:mm tt'
    };

    $(".mydatepicker-autoclose").datepicker(datepicker_config);
</script> 

<script type="text/javascript">
    $(document).ready(function () {

        $('.js-example-basic-multiple').select2({
            placeholder: '<?php echo lang("select_course"); ?>'
        });
    });
</script>
<script>
    $(document).ready(function () {

        $('#selectItem').on('click', function () {
            $('#selectItem').prop('disabled', 'false');
        });

        $(".js-data-example-ajax").select2();
        $(".feetypes_select2").select2();

    });
</script>

<?php include(APPPATH . "views/inc/footer.php"); ?>

