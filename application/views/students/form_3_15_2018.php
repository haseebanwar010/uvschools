<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<div>
    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid" ng-controller="stdAdmissionController" >
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?php echo lang('add_student') ?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                    <ol class="breadcrumb">
                        <li><a href="#"><?php echo lang('crumb_dashboard') ?></a></li>
                        <li><a href="#"><?php echo lang('menu_students') ?></a>
                        </li>
                        <li class="active"><?php echo lang('add_student') ?></li>

                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <!-- Page Content -->
            <!-- /.row -->
            <div class="alert alert-dismissable {{alert.type}}" ng-if="alert.message"> 
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>     
                {{ alert.message }}
            </div>
            <!-- .row -->
            <!-- row -->
            <div class="row" id='stdForm'>
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-wrapper collapse in" aria-expanded="true">
                            <div class="panel-body m-l-20 m-r-20">
                                <form name="stdAddmissionForm" ng-submit="onSubmit(stdAddmissionForm.$valid,image2.resized,image3.resized)" novalidate="" class="form-material">
                                    <div class="form-body ">
                                        <h3 class="box-title"><?php echo lang('personal_details') ?></h3>
                                        <hr style="border-color: black" />
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_avatar') ?></label>
                                                    <input id="inputImage2" 
                                                           type="file" 
                                                           accept="image/*" 
                                                           image="image2" 
                                                           class="form-control"
                                                           resize-max-height="300"
                                                           resize-max-width="350"
                                                           style="font-size: 12px;"
                                                           resize-quality="0.7" />
                                                    <span>
                                                        <img style="width: auto;" ng-show="image2" ng-src="{{image2.resized.dataURL}}"/>
                                                    </span>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_religion') ?></label>
                                                    <input type="text" id="religion" ng-model="formModel.religion" class="form-control" placeholder="Religion">
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('first_name') ?></label>
                                                    <input type="text" id="firstName" ng-model="formModel.firstname" class="form-control" placeholder="John " required="">
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('last_name') ?></label>
                                                    <input type="text" id="lastName" ng-model="formModel.lastname" class="form-control" placeholder="doe" required="">
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_gender') ?></label>
                                                    <select class="form-control" ng-model="formModel.gender" required="" ng-init="formModel.gender=''" required="">
                                                        <option value=""><?php echo lang('select_gender') ?></option>
                                                        <option value="male"><?php echo lang('option_male') ?></option>
                                                        <option value="female"><?php echo lang('option_female') ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_dob') ?></label>
                                                    <input type="text" name="dob" class="form-control mydatepicker-autoclose" ng-model="formModel.dob" placeholder="dd/mm/yyyy" required="">
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('blood_group') ?></label>
                                                    <select class="form-control" ng-model="formModel.blood" ng-init="formModel.blood=''">
                                                        <option value=""><?php echo lang('select_blood') ?></option>
                                                        <option value="A+">A +ve</option>
                                                        <option value="B+">B +ve</option>
                                                        <option value="A-">A -ve</option>
                                                        <option value="B-">B -ve</option>
                                                        <option value="AB+">AB +ve</option>
                                                        <option value="AB-">AB -ve</option>
                                                        <option value="O+">O +ve</option>
                                                        <option value="O-">O -ve</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--/span-->

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('birth_place') ?></label>
                                                    <input type="text" id="birthPlace" ng-model="formModel.birthPlace" class="form-control" placeholder="">
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_nationality') ?></label>
                                                    <input type="text" id="nationality" ng-model="formModel.nationality" class="form-control" placeholder="Nationality">
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_language') ?></label>
                                                    <input type="text" id="language" ng-model="formModel.language" class="form-control" placeholder="Language">
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>

                                        <h3 class="title-danger"><?php echo lang('contact_details') ?></h3>
                                        <hr style="border-color: black" />

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo lang('lbl_email') ?></label>
                                                    <input type="email" ng-model="formModel.email" class="form-control" required="">
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo lang('lbl_phone') ?></label>
                                                    <input type="text" class="form-control" ng-model="formModel.phone" required="">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo lang('lbl_country') ?></label>
                                                    <select name="country" ng-model="formModel.country" ng-init="formModel.country=''" class="form-control" required="">
                                                        <option value=""><?php echo lang('select_country') ?></option>
                                                        <?php foreach ($countries as $country) { ?>
                                                            <option value="<?php echo $country->country_name; ?>"><?php echo $country->country_name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo lang('lbl_city') ?></label>
                                                    <input type="text" class="form-control" ng-model="formModel.city" required="">
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>

                                        <div class="row">
                                            <!--/span-->
                                            <div class="col-md-12 ">
                                                <div class="form-group">
                                                    <label><?php echo lang('lbl_address') ?></label>
                                                    <input type="text" class="form-control" ng-model="formModel.address" required="">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                        <h3 class="title-danger"><?php echo lang('courses_batch_details') ?></h3>
                                        <hr style="border-color: black" />
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo lang('lbl_course') ?></label>
                                                    <select class="form-control" ng-model="formModel.course" ng-init="formModel.course=''" required="" ng-change="fetchClassBatches(formModel.course)">
                                                        <option value=""><?php echo lang('select_course') ?></option>
                                                        <?php if (count($classes) > 0) {
                                                            foreach ($classes as $cls) {
                                                                ?>
                                                                <option value="<?= $cls->id; ?>"><?= $cls->name; ?></option>
                                                            <?php }
                                                        } else {
                                                            ?>
                                                            <option><?php echo lang('no_record') ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6" id="frmBatches">
                                                <div class="form-group">
                                                    <label><?php echo lang('lbl_batch') ?></label>
                                                    <select class="form-control" ng-model="formModel.batch" ng-init="formModel.batch=''" required="">
                                                        <option value=""><?php echo lang('select_batch') ?></option>
                                                        <option ng-repeat="batch in batches" value="{{batch.id}}">{{batch.name}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo lang('lbl_rollno') ?></label>
                                                    <input type="text" class="form-control" ng-model="formModel.rollno">
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="discount_div" ng-init="fetchDiscounts()">
                                                <div class="form-group">
                                                    <label><?php echo lang('lbl_discount') ?></label>
                                                    <select class="form-control" ng-model="formModel.discount_id" required="">
                                                        <option value="">---<?= lang("lbl_select_a_discount"); ?>---</option>
                                                        <option ng-repeat="discount in discounts" value="{{discount.id}}">{{discount.name}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                       
                                        <h3 class="box-title"><?php echo lang('lbl_guardian') ?> - <?php echo lang('personal_details') ?></h3>
                                        <hr style="border-color: black" />
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_avatar') ?></label>
                                                    <input id="inputImage3" 
                                                           type="file" 
                                                           accept="image/*" 
                                                           image="image3" 
                                                           class="form-control"
                                                           resize-max-height="300"
                                                           resize-max-width="350"
                                                           style="font-size: 12px;"
                                                           resize-quality="0.7" />
                                                    <span>
                                                        <img style="width: auto;" ng-show="image3" ng-src="{{image3.resized.dataURL}}"/>
                                                    </span>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_name') ?></label>
                                                    <input type="text" id="pname" class="form-control" ng-model="formModel.pName" placeholder="John Doe " required="">
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_gender') ?></label>
                                                    <select class="form-control" ng-model="formModel.pGender" required="" ng-init="formModel.pGender=''" required="">
                                                        <option value=""><?php echo lang('select_gender') ?></option>
                                                        <option value="male"><?php echo lang('option_male') ?></option>
                                                        <option value="female"><?php echo lang('option_female') ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_relation') ?></label>
                                                    <select class="form-control" required="" ng-model="formModel.pRelation" ng-init="formModel.pRelation=''" required="">
                                                        <option value=""><?php echo lang('select_relation') ?></option>
                                                        <option value="father">Father</option>
                                                        <option value="mother">Mother</option>
                                                        <option value="uncle">Uncle</option>
                                                        <option value="brother">Brother</option>
                                                        <option value="grandfather">Grand Father</option>
                                                        <option value="grandmother">Grand Mother</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_dob') ?></label>
                                                    <input type="text" class="form-control mydatepicker-autoclose" ng-model="formModel.pDob" placeholder="dd/mm/yyyy">
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_occupation') ?></label>
                                                    <input type="text" id="occupation" class="form-control" ng-model="formModel.pOccupation" placeholder="Occupation">
                                                </div>
                                            </div>
                                            <!--/span-->

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo lang('lbl_income') ?></label>
                                                    <input type="text" id="income" class="form-control" placeholder="10,000$" ng-model="formModel.pIncome" >
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo lang('lbl_email') ?></label>
                                                    <input type="email" class="form-control" ng-model="formModel.pEmail" required="">
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo lang('lbl_phone') ?></label>
                                                    <input type="text" class="form-control" ng-model="formModel.pPhone" >
                                                </div>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo lang('lbl_street') ?></label>
                                                    <input type="text" class="form-control" ng-model="formModel.pStreet">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo lang('lbl_ic_number') ?></label>
                                                    <input type="number" name="pId" class="form-control" ng-model="formModel.pIdNumber">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo lang('lbl_country') ?></label>
                                                    <select name="pCountry" ng-model="formModel.pCountry" required="" ng-init="formModel.pCountry=''" class="form-control">
                                                        <option value=""><?php echo lang('select_country') ?></option>
                                                        <?php foreach ($countries as $country) { ?>
                                                            <option value="<?php echo $country->country_name; ?>"><?php echo $country->country_name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo lang('lbl_city') ?></label>
                                                    <input type="text" class="form-control" ng-model="formModel.pCity">
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>

                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> <?php echo lang('btn_save') ?></button>
                                            <button type="button" class="btn btn-default"><?php echo lang('btn_cancel') ?></button>
                                        </div>

                                    </div>
                                </form>
                            </div>   
                        </div>
                    </div>
                </div>
            </div>
            <!--./row-->



            <!--page content end-->
        </div>
        <!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>