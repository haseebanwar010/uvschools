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
<div id="page-wrapper" ng-controller="feeCollectionparentController">
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
                $fee_coll = 0;
                foreach ($array as $key => $value) {
                    if (in_array('collection-allow', array($value->permission)) && $value->val == 'true') {
                        $fee_coll = 1;
                    }
                }
            }
        }
        ?>
        <div class="hint"><?php echo lang('help_fee_collection'); ?></div>
        <!-- Page Content start here -->

       
       

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
                                <th>Receipt No</th>
                                <th class='text-center'><?php echo lang("th_action"); ?></th>
                            </tr>
                            <tr class="text-center" ng-repeat="row in partiallyFeeDetailModel">
                                <td>{{ $index+1 }}</td>
                                <td>{{ row.collector_name }}</td>
                                <td>{{ row.paid_date }}</td>
                                <td><?php echo $this->session->userdata('userdata')['currency_symbol'] ?>{{row.paid_amount}}</td>
                                <td> {{ (row.paid_amount * 100) / (row.amount-(row.amount*(row.discount/100))) | number:1 }}%</td>
                                <td>{{row.receipt_no}}</td>
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

    <!--.row-->
    <div class="row" id="feecollection" ng-init="initAcademicYears()">
        <div class="col-md-12"> 
            <div class="white-box">
                <form class="form-material" id="feeCollection_search_filter" name="feecollectionFilterForm" ng-submit="fetchFeeCollections(feecollectionFilterForm.$valid)" novalidate="">
                    <!--/row-->
                    <div class="row">

                        <div class="col-md-3" id="feeFilterAcademicYears">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang("lbl_academic_year"); ?></label>
                                <select class="form-control" name="academic_year_id" ng-model="fcModel.academic_year_id" required="" ng-change="initGetParentChild(fcModel.academic_year_id)">
                                    <option value="-1"><?php echo lang("lbl_select_academic_year"); ?></option>
                                    <option ng-repeat="ay in academicyears" value="{{ ay.id }}">{{ ay.name }}</option>
                                </select>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-3" id="feeFilterChilds">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('lbl_child') ?></label>
                                <select class="form-control" name="student_id" ng-model="fcModel.student_id" required="">
                                    <option value=""><?php echo lang('lbl_select_child') ?></option>
                                    <option ng-repeat="cls in parentchild" value="{{ cls.student_id }}">{{ cls.name }}</option>
                                </select>
                            </div>
                        </div>



                        <!--/span-->
                        
                       
                         

                        <div class="col-md-12">
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
                                                    <th><?php echo lang("lbl_discount"); ?></th><td>{{ selectedStd.discount_name }}</td>
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
                                                <a href="javascript:void(0)" ng-if="rcd[0].exemption_status=='' && rcd[0].feetype_status!=1 && rcd[0].fee_collection_id==='NULL'" data-toggle="modal" data-target="#exemptionModel" ng-click="setFeeValues(rcd)">
                                                    
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
                                                <p ng-if="rcd[0].exemption_status=='inprocess' || rcd[0].exemption_status=='approved'">{{rcd[0].exemption_amount}}</p>
                                            </td>
                                            <td>
                                                <strong ng-if="rcd[0].fee_collection_id==='NULL'"><span ng-if="rcd[0].discounted_amount !=0">-</span>{{rcd[0].discounted_amount }} <?php echo $this->session->userdata('userdata')['currency_symbol'] ?></strong>
                                                <strong ng-if="rcd[0].fee_collection_id!=='NULL'">{{calculateBalance(rcd)}}</strong>
                                            </td>
                                            <td><p ng-repeat="r in rcd">{{r.receipt_no}}</p></td>
                                            
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