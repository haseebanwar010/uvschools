<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid" ng-controller="importCtrl" ng-init="init_csv('<?php echo isset($import_json)?$import_json:''; ?>')">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('all_students') ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="#"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li><a href="#"><?php echo lang('menu_students') ?></a></li>
                    <li class="active"><a href="#"><?php echo lang('import_students') ?></a></li>
                </ol>
            </div>
        </div>

        <div id="error_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="panel panel-danger">
                        <div class="modal-header panel-heading">
                            Errors
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <ol>
                            <li ng-repeat="e in errors">{{e}}</li>
                        </ol>

                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang('lbl_close') ?></button>

                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
        <!-- row -->
        <div class="row">  
            <div class="col-md-12">
                <div class="white-box" >
                    <div class="row" >
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php if(isset($import_error)){?>
                                <div class="alert alert-danger" style="color: #fff; font-size: 18px;"> <?php  echo $import_error;  ?> <?php echo lang('error_file') ?></div>
                            <?php } ?> 
                            <div class="alert alert-dismissable danger"> 
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>     
                                {{ alert.message }}
                            </div>
                            <?php if($this->session->flashdata('message_name')){ ?>
                                <div class="alert alert-danger" style="color: #fff; font-size: 18px;"> <?php echo  $this->session->flashdata('invalid_file'); ?></div>
                           <?php } ?>
                            <div ng-if="csv_students.length === 0 " class="alert alert-success" style="color: #fff; font-size: 18px;"><?php echo lang('success_import') ?></div>
                            
                            <div class="inbox-center" id="inbox_div" ng-if="csv_students.length > 0">
                                <button class="btn btn-primary" ng-disabled="selected_array.length == 0" ng-click="process_students()">Process</button>
                                <span ng-if="selected_array.length != 0">{{selected_array.length}} student (s) selected</span>
                                <span ng-if="selected_array.length == 0">No student selected</span>
                                <div class="table-responsive" class="container-fluid"   >
                                   <table class="table table-default m-t-20" id="dataTbl" cellspacing="0" width="100%">
                                       <thead>
                                        <tr>
                                            <th>
                                                <div class="checkbox checkbox-info">
                                                    <input type="checkbox" id="checkAll" ng-click="selectAllStudents($event)" />
                                                    <label for="checkAll"></label>
                                                </div>
                                            </th>
                                            <th><?php echo lang('import') ?></th>
                                            
                                            <th><?php echo lang('imp_first_name') ?></th>
                                            <th><?php echo lang('imp_last_name') ?></th>
                                            <th><?php echo lang('imp_gender') ?></th>
                                            <th><?php echo lang('imp_dob') ?></th>
                                            <th><?php echo lang('imp_blood_group') ?></th>
                                            <th><?php echo lang('imp_std_religion') ?></th>
                                            <th><?php echo lang('imp_birth_place') ?></th>
                                            <th><?php echo lang('lbl_nationality') ?></th>
                                            <th><?php echo lang('imp_std_lang') ?></th>
                                            <th><?php echo lang('imp_std_email') ?></th>
                                            <th><?php echo lang('imp_contact') ?></th>
                                            <th><?php echo lang('imp_country') ?></th>
                                            <th><?php echo lang('imp_city') ?></th>
                                            <th><?php echo lang('imp_address') ?></th>
                                            <th><?php echo lang('imp_std_class') ?></th>
                                            <th><?php echo lang('imp_std_section') ?></th>
                                            <th><?php echo lang('imp_std_roll_no') ?></th>
                                            <th><?php echo lang('imp_parent_name') ?></th>
                                            <th><?php echo lang('imp_parent_gender') ?></th>
                                            <th><?php echo lang('imp_parent_relation') ?></th>
                                            <th><?php echo lang('imp_dob') ?></th>
                                            <th><?php echo lang('imp_parent_occupation') ?></th>
                                            <th><?php echo lang('imp_parent_income') ?></th>
                                            <th><?php echo lang('imp_parent_email') ?></th>
                                            <th><?php echo lang('imp_parent_contact') ?></th>
                                            <th><?php echo lang('imp_address') ?></th>
                                            <th><?php echo lang('imp_ic_number') ?></th>
                                            <th><?php echo lang('imp_country') ?></th>
                                            <th><?php echo lang('imp_city') ?></th>
                                            <th><?php echo lang('admission_date');?></th>
                                            <th><?php echo lang('national_number');?></th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        <tr   dir-paginate="d in csv_students| itemsPerPage: 10">
                                            <td>
                                                <div class="checkbox checkbox-info">
                                                    <input type="checkbox" id="sel{{d.id}}" ng-disabled="d.any_error" ng-click="selectStudent(d)" />
                                                    <label for="sel{{d.id}}"></label>
                                                </div>
                                            </td>
                                            <td ng-if="d.any_error"><a href="javascript:void(0)"><input type="button" data-toggle="modal" data-target="#error_modal" ng-click="ShowErrors(d.errors)" value="{{d.errors.length}} Error (s)" class="btn btn-danger" style="width:100px"></a></td>
                                            <td ng-if="d.is_exist == '1' && !d.any_error"><a href="javascript:void(0)"><input type="button" ng-click="ShowUpdate(d)" name="import_csv_btn" value="<?php echo lang('btn_update') ?>" class="btn btn-success " id="import_csv_btn" style="width:100px"></a></td>
                                            <td ng-if="d.is_exist == '0' && !d.any_error"><a href="javascript:void(0)"><input type="button" name="import_csv_btn" value="<?php echo lang('import') ?>" ng-click="ShowImport(d);" class="btn btn-info " id="import_csv_btn" style="width:100px"></a></td>
                                            <td>{{d.First_Name}}</td>
                                            <td>{{d.Last_Name}}</td>
                                            <td>{{d.Gender2}}</td>
                                            <td>{{d.DOB}}</td>
                                            <td>{{d.Blood_Group}}</td>
                                            <td>{{d.Religion}}</td>
                                            <td>{{d.Birth_Place}}</td>
                                            <td>{{d.Nationality2}}</td>
                                            <td>{{d.Language}}</td>
                                            <td>{{d.Email}}</td>
                                            <td>{{d.Contact}}</td>
                                            <td>{{d.Country2}}</td>
                                            <td>{{d.City}}</td>
                                            <td>{{d.Address}}</td>
                                            <td>{{d.Class}}</td>
                                            <td>{{d.Section}}</td>
                                            <td>{{d.RollNo}}</td>
                                            <td>{{d.Parent_Name}}</td>
                                            <td>{{d.Parent_Gender2}}</td>
                                            <td>{{d.Relation2}}</td>
                                            <td>{{d.Parent_DOB}}</td>
                                            <td>{{d.Parent_Occupation}}</td>
                                            <td>{{d.Parent_Income}}</td>
                                            <td>{{d.Parent_Email}}</td>
                                            <td>{{d.Parent_Contact}}</td>
                                            <td>{{d.Parent_Address}}</td>
                                            <td>{{d.IC_Number}}</td>
                                            <td>{{d.Parent_Country2}}</td>
                                            <td>{{d.Parent_City}}</td>
                                            <td>{{d.Admission_Date}}</td>
                                            <td>{{d.NIC}}</td>
                                        </tr>
                                    </tbody>

                                </table> 
                                <dir-pagination-controls class="pull-right">
                                </dir-pagination-controls>
                            </div>

                        </div> 
                        <!-- .row -->
                        <!-- row -->
                        <div class="row" id='stdForm' style="display: none">

                            <div class="col-md-12">
                                <div class="panel">
                                    <div class="panel-wrapper collapse in" aria-expanded="true">
                                        <div class="panel-body m-l-20 m-r-20">
                                            <form name="stdAddmissionForm" ng-submit="onSave(stdAddmissionForm.$valid,image2.resized,image3.resized)" novalidate="" class="form-material">        

                                               <div class="form-body" >
                                                <h3 class="box-title"><?php echo lang('personal_details') ?></h3>
                                                <hr style="border-color: black" />
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_avatar') ?></label>
                                                            <input id="inputImage2"
                                                            ng-model="showstd.avatar"
                                                            ng-init ="showstd.pAvatar = default.png"
                                                            type="file" 
                                                            accept="image/*" 
                                                            image="image2" 
                                                            class="form-control"
                                                            resize-max-height="300"
                                                            resize-max-width="350"
                                                            style="font-size: 12px;"
                                                            resize-quality="0.7"/>
                                                            <span>
                                                                <img style="width: auto;" ng-show="image2" ng-src="{{image2.resized.dataURL}}"/>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_religion') ?></label>
                                                            <input type="text" id="religion" ng-model="showstd.Religion" class="form-control" placeholder="Religion">
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('first_name') ?></label>
                                                            <input type="text" id="firstName" ng-model="showstd.First_Name" class="form-control" placeholder="John " required="">
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('last_name') ?></label>
                                                            <input type="text" id="lastName" ng-model="showstd.Last_Name" class="form-control" placeholder="doe" required="">
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                </div>
                                                <!--/row-->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_gender') ?></label>
                                                            <select class="form-control" ng-model="showstd.Gender" required="" ng-init="showstd.Gender='showstd.Gender'" required="">
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
                                                            <input type="text" class="form-control mydatepicker-autoclose" ng-model="showstd.DOB" placeholder="dd/mm/yyyy" required="" ng-pattern="/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/">
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                </div>
                                                <!--/row-->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('blood_group') ?></label>
                                                            <select class="form-control" ng-model="showstd.Blood_Group" ng-init="showstd.Blood_Group=showstd.Blood_Group">
                                                                <option value=""><?php echo lang('select_blood') ?></option>
                                                                <option value="A+">A+</option>
                                                                <option value="B+">B+</option>
                                                                <option value="A-">A-</option>
                                                                <option value="B-">B-</option>
                                                                <option value="AB+">AB+</option>
                                                                <option value="AB-">AB-</option>
                                                                <option value="O+">O+</option>
                                                                <option value="O-">O-</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!--/span-->

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('birth_place') ?></label>
                                                            <input type="text" id="birthPlace" ng-model="showstd.Birth_Place" class="form-control" placeholder="">
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                </div>
                                                <!--/row-->
                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_nationality') ?></label>
                                                            <select  ng-model="showstd.Nationality" class="form-control" id="nationality" required="">
                                                                <option value=""><?php echo lang('select_nationality') ?></option>
                                                                <?php foreach ($countries as $country) { ?>
                                                                    <option value="<?php echo $country->id; ?>"><?php echo $country->country_name; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_language') ?></label>
                                                            <input type="text" id="language" ng-model="showstd.Language" class="form-control" placeholder="Language">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('national_number') ?></label>
                                                            <input type="text" id="nic" ng-model="showstd.NIC" class="form-control" placeholder="NIC">
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
                                                            <input type="email" ng-model="showstd.Email" class="form-control" required="" >
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><?php echo lang('lbl_phone') ?></label>
                                                            <input type="text" class="form-control" ng-model="showstd.Contact" required="">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><?php echo lang('lbl_country') ?></label>
                                                            <select name="country" ng-model="showstd.Country" class="form-control" required="" id="country">
                                                                <option value=""><?php echo lang('select_country') ?></option>
                                                                <?php foreach ($countries as $country) { ?>
                                                                    <option value="<?php echo $country->id; ?>"><?php echo $country->country_name; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <!--                                                     <input type="text" class="form-control" ng-model="showstd.Country" required="">-->
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><?php echo lang('lbl_city') ?></label>
                                                            <input type="text" class="form-control" ng-model="showstd.City" required="">
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                </div>

                                                <div class="row">
                                                    <!--/span-->
                                                    <div class="col-md-12 ">
                                                        <div class="form-group">
                                                            <label><?php echo lang('lbl_address') ?></label>
                                                            <input type="text" class="form-control" ng-model="showstd.Address" required="">
                                                        </div>
                                                    </div>
                                                </div>


                                                <h3 class="title-danger"><?php echo lang('courses_batch_details') ?></h3>
                                                <hr style="border-color: black" />
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><?php echo lang('lbl_course') ?></label>
                                                            <select class="form-control" ng-model="showstd.class_id" required="" ng-change="fetchClassBatches(showstd.class_id)">
                                                                <option value=""><?php echo lang('select_course') ?></option>
                                                                <?php if (count($classes) > 0) { ?>
                                                                    <?php foreach ($classes as $cls) { ?>
                                                                        <option value="<?= $cls->id; ?>"><?= $cls->name; ?></option>
                                                                    <?php } ?> 
                                                                <?php } else { ?>
                                                                    <option><?php echo lang('no_record') ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6" id="frmBatches">
                                                        <div class="form-group">
                                                            <label><?php echo lang('lbl_batch') ?></label>
                                                            <select class="form-control" ng-model="showstd.section_id" required="">
                                                                <option value=""><?php echo lang('select_batch') ?></option>
                                                                <option ng-repeat="batch in batches" value="{{batch.id}}">{{batch.name}}</option>
                                                            </select>
                                                            <!--                                                    <input type="text" class="form-control" ng-model="showstd.Section">-->
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><?php echo lang('lbl_rollno') ?></label>
                                                            <input type="text" class="form-control" ng-model="showstd.RollNo" required="">
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('admission_date');?></label>
                                                            <input type="text" name="adm_date" class="form-control mydatepicker-autoclose" ng-model="showstd.Admission_Date" ng-pattern="/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/">
                                                        </div>
                                                    </div>
                                                </div>

                                                <h3 class="box-title"><?php echo lang('lbl_guardian') ?> - <?php echo lang('personal_details') ?></h3>
                                                <hr style="border-color: black" />


                                                <div ng-if="showstd.parentIdResponse == '0' " class="alert alert-danger" style="color: #fff; font-size: 18px;"><?php echo lang('imp_parent_exist') ?></div>
                                                <div ng-show="showstd.parentIdResponse != '0' " id="parentDiv" >
                                                    <div class="row" >
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label"><?php echo lang('lbl_avatar') ?></label>
                                                                <input id="inputImage3"
                                                                ng-model="showstd.pAvatar"
                                                                ng-init ="showstd.pAvatar = default.png"
                                                                type="file" 
                                                                accept="image/*" 
                                                                image="image3" 
                                                                class="form-control"
                                                                resize-max-height="300"
                                                                resize-max-width="350"
                                                                style="font-size: 12px;"
                                                                resize-quality="0.7" />
                                                                <span>
                                                                    <img style="width: auto;"  ng-show="image3" ng-src="{{image3.resized.dataURL}}"/>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label"><?php echo lang('lbl_name') ?></label>
                                                                <input type="text" id="pname" class="form-control" ng-model="showstd.Parent_Name" placeholder="John Doe " required="">
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label"><?php echo lang('lbl_gender') ?></label>
                                                                <select class="form-control" ng-model="showstd.Parent_Gender" required="" ng-init="showstd.Parent_Gender='showstd.Parent_Gender'" required="">
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
                                                                <select class="form-control" required="" ng-model="showstd.Relation" ng-init="showstd.Relation='showstd.Relation'" required="">
                                                                    <option value=""><?php echo lang('select_relation') ?></option>
                                                                    <option value="father"><?php echo lang('father') ?></option>
                                                                    <option value="mother"><?php echo lang('mother') ?></option>
                                                                    <option value="uncle"><?php echo lang('uncle') ?></option>
                                                                    <option value="brother"><?php echo lang('brother') ?></option>
                                                                    <option value="grandfather"><?php echo lang('grand_father') ?></option>
                                                                    <option value="grandmother"><?php echo lang('grand_mother') ?></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label"><?php echo lang('lbl_dob') ?></label>
                                                                <input type="text" class="form-control mydatepicker-autoclose" ng-model="showstd.Parent_DOB" placeholder="dd/mm/yyyy" ng-pattern="/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/">
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                    </div>
                                                    <!--/row-->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label"><?php echo lang('lbl_occupation') ?></label>
                                                                <input type="text" id="occupation" class="form-control" ng-model="showstd.Parent_Occupation" placeholder="Occupation">
                                                            </div>
                                                        </div>
                                                        <!--/span-->

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label"><?php echo lang('lbl_income') ?></label>
                                                                <input type="text" id="income" class="form-control" placeholder="10,000$" ng-model="showstd.Parent_Income" >
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                    </div>
                                                    <!--/row-->

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label><?php echo lang('lbl_email') ?></label>
                                                                <input type="email" class="form-control" ng-model="showstd.Parent_Email" required="">
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label><?php echo lang('lbl_phone') ?></label>
                                                                <input type="text" class="form-control" ng-model="showstd.Parent_Contact">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label><?php echo lang('lbl_address') ?></label>
                                                                <input type="text" class="form-control" ng-model="showstd.Parent_Address">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label><?php echo lang('lbl_ic_number') ?></label>
                                                                <input type="text" name="pId" class="form-control" ng-model="showstd.IC_Number">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label><?php echo lang('lbl_country') ?></label>
                                                                <select name="country" ng-model="showstd.Parent_Country" class="form-control" required="" id="parent_country">
                                                                    <option value=""><?php echo lang('select_country') ?></option>
                                                                    <?php foreach ($countries as $country) { ?>
                                                                        <option value="<?php echo $country->id; ?>"><?php echo $country->country_name; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label><?php echo lang('lbl_city') ?></label>
                                                                <input type="text" class="form-control" ng-model="showstd.Parent_City">
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                    </div>
                                                </div> 
                                                <div class="form-actions">
                                                    <button type="submit" class="btn btn-success" > <i class="fa fa-check"></i> <?php echo lang('btn_save') ?></button>
                                                    <button type="button" ng-click="ShowList()" class="btn btn-default"><?php echo lang('btn_cancel') ?></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>   
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--- Start::Updated code --->

                    <div class="row" id='updateForm' style="display: none">

                        <div class="col-md-12">
                            <div class="panel">
                                <div class="panel-wrapper collapse in" aria-expanded="true">
                                    <div class="panel-body m-l-20 m-r-20">
                                        <form name="stdAddmissionForm" ng-submit="onUpdate(stdAddmissionForm.$valid,image2.resized,image3.resized)" novalidate="" class="form-material">

                                           <div class="form-body" >
                                            <h3 class="box-title"><?php echo lang('personal_details') ?></h3>
                                            <hr style="border-color: black" />
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_avatar') ?></label>
                                                        <input id="inputImage2"
                                                        ng-model="showstd.avatar"
                                                        ng-init ="showstd.pAvatar = default.png"
                                                        type="file" 
                                                        accept="image/*" 
                                                        image="image2" 
                                                        class="form-control"
                                                        resize-max-height="300"
                                                        resize-max-width="350"
                                                        style="font-size: 12px;"
                                                        resize-quality="0.7"/>
                                                        <span>
                                                            <img style="width: auto;" ng-show="image2" ng-src="{{image2.resized.dataURL}}"/>
                                                        </span>
                                                    </div>
                                                </div>
                                                <!--/span-->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_religion') ?></label>
                                                        <input type="text" id="religion" ng-model="showstd.Religion" class="form-control" placeholder="Religion">
                                                    </div>
                                                </div>
                                                <!--/span-->
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('first_name') ?></label>
                                                        <input type="text" id="firstName" ng-model="showstd.First_Name" class="form-control" placeholder="John " required="">
                                                    </div>
                                                </div>
                                                <!--/span-->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('last_name') ?></label>
                                                        <input type="text" id="lastName" ng-model="showstd.Last_Name" class="form-control" placeholder="doe" required="">
                                                    </div>
                                                </div>
                                                <!--/span-->
                                            </div>
                                            <!--/row-->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_gender') ?></label>
                                                        <select class="form-control" ng-model="showstd.Gender" required="" ng-init="showstd.Gender='showstd.Gender'" required="">
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
                                                        <input type="text" class="form-control mydatepicker-autoclose" ng-model="showstd.DOB" placeholder="dd/mm/yyyy" required="" ng-pattern="/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/">
                                                    </div>
                                                </div>
                                                <!--/span-->
                                            </div>
                                            <!--/row-->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('blood_group') ?></label>
                                                        <select class="form-control" ng-model="showstd.Blood_Group" ng-init="showstd.Blood_Group=showstd.Blood_Group">
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
                                                        <input type="text" id="birthPlace" ng-model="showstd.Birth_Place" class="form-control" placeholder="">
                                                    </div>
                                                </div>
                                                <!--/span-->
                                            </div>
                                            <!--/row-->
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_nationality') ?></label>
                                                        <select  ng-model="showstd.Nationality"class="form-control" id="nationality" required="">
                                                            <option value=""><?php echo lang('select_nationality') ?></option>
                                                            <?php foreach ($countries as $country) { ?>
                                                                <option value="<?php echo $country->id; ?>"><?php echo $country->country_name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <!--/span-->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('lbl_language') ?></label>
                                                        <input type="text" id="language" ng-model="showstd.Language" class="form-control" placeholder="Language">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('national_number') ?></label>
                                                        <input type="text" id="nic" ng-model="showstd.NIC" class="form-control" placeholder="NIC">
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
                                                        <input type="email" ng-model="showstd.Email" class="form-control" required="" >
                                                    </div>
                                                </div>
                                                <!--/span-->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo lang('lbl_phone') ?></label>
                                                        <input type="text" class="form-control" ng-model="showstd.Contact" required="">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo lang('lbl_country') ?></label>
                                                        <select name="country" ng-model="showstd.Country" class="form-control" required="" id="country">
                                                            <option value=""><?php echo lang('select_country') ?></option>
                                                            <?php foreach ($countries as $country) { ?>
                                                                <option value="<?php echo $country->id; ?>"><?php echo $country->country_name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <!--                                                     <input type="text" class="form-control" ng-model="showstd.Country" required="">-->
                                                    </div>
                                                </div>
                                                <!--/span-->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo lang('lbl_city') ?></label>
                                                        <input type="text" class="form-control" ng-model="showstd.City" required="">
                                                    </div>
                                                </div>
                                                <!--/span-->
                                            </div>

                                            <div class="row">
                                                <!--/span-->
                                                <div class="col-md-12 ">
                                                    <div class="form-group">
                                                        <label><?php echo lang('lbl_address') ?></label>
                                                        <input type="text" class="form-control" ng-model="showstd.Address" required="">
                                                    </div>
                                                </div>
                                            </div>


                                            <h3 class="title-danger"><?php echo lang('courses_batch_details') ?></h3>
                                            <hr style="border-color: black" />
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo lang('lbl_course') ?></label>
                                                        <select class="form-control" ng-model="showstd.class_id" required="" ng-change="fetchClassBatches(showstd.class_id)">
                                                            <option value=""><?php echo lang('select_course') ?></option>
                                                            <?php if (count($classes) > 0) { ?>
                                                                <?php foreach ($classes as $cls) { ?>
                                                                    <option value="<?= $cls->id; ?>"><?= $cls->name; ?></option>
                                                                <?php } ?> 
                                                            <?php } else { ?>
                                                                <option><?php echo lang('no_record') ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <!--/span-->
                                                <div class="col-md-6" id="frmBatches">
                                                    <div class="form-group">
                                                        <label><?php echo lang('lbl_batch') ?></label>
                                                        <select class="form-control" ng-model="showstd.section_id"  required="">
                                                            <option value=""><?php echo lang('select_batch') ?></option>
                                                            <option ng-repeat="batch in batches" value="{{batch.id}}">{{batch.name}}</option>
                                                        </select>
                                                        <!--                                                    <input type="text" class="form-control" ng-model="showstd.Section">-->
                                                    </div>
                                                </div>
                                                <!--/span-->
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo lang('lbl_rollno') ?></label>
                                                        <input type="text" class="form-control" ng-model="showstd.RollNo" required="">
                                                    </div>
                                                </div>
                                                <!--/span-->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo lang('admission_date');?></label>
                                                        <input type="text" name="adm_date" class="form-control mydatepicker-autoclose" ng-model="showstd.Admission_Date" ng-pattern="/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/">
                                                    </div>
                                                </div>
                                            </div>

                                            <h3 class="box-title"><?php echo lang('lbl_guardian') ?> - <?php echo lang('personal_details') ?></h3>
                                            <hr style="border-color: black" />


                                            <div id="parentDiv" >
                                                <div class="row" >
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_avatar') ?></label>
                                                            <input id="inputImage3"
                                                            ng-model="showstd.pAvatar"
                                                            ng-init ="showstd.pAvatar = default.png"
                                                            type="file" 
                                                            accept="image/*" 
                                                            image="image3" 
                                                            class="form-control"
                                                            resize-max-height="300"
                                                            resize-max-width="350"
                                                            style="font-size: 12px;"
                                                            resize-quality="0.7" />
                                                            <span>
                                                                <img style="width: auto;"  ng-show="image3" ng-src="{{image3.resized.dataURL}}"/>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_name') ?></label>
                                                            <input type="text" id="pname" class="form-control" ng-model="showstd.Parent_Name" placeholder="John Doe " required="">
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_gender') ?></label>
                                                            <select class="form-control" ng-model="showstd.Parent_Gender" required="" ng-init="showstd.Parent_Gender='showstd.Parent_Gender'" required="">
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
                                                            <select class="form-control" required="" ng-model="showstd.Relation" ng-init="showstd.Relation='showstd.Relation'" required="">
                                                                <option value=""><?php echo lang('select_relation') ?></option>
                                                                <option value="father"><?php echo lang('father') ?></option>
                                                                <option value="mother"><?php echo lang('mother') ?></option>
                                                                <option value="uncle"><?php echo lang('uncle') ?></option>
                                                                <option value="brother"><?php echo lang('brother') ?></option>
                                                                <option value="grandfather"><?php echo lang('grand_father') ?></option>
                                                                <option value="grandmother"><?php echo lang('grand_mother') ?></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_dob') ?></label>
                                                            <input type="text" class="form-control mydatepicker-autoclose" ng-model="showstd.Parent_DOB" placeholder="dd/mm/yyyy" ng-pattern="/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/">
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                </div>
                                                <!--/row-->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_occupation') ?></label>
                                                            <input type="text" id="occupation" class="form-control" ng-model="showstd.Parent_Occupation" placeholder="Occupation">
                                                        </div>
                                                    </div>
                                                    <!--/span-->

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo lang('lbl_income') ?></label>
                                                            <input type="text" id="income" class="form-control" placeholder="10,000$" ng-model="showstd.Parent_Income" >
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                </div>
                                                <!--/row-->

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><?php echo lang('lbl_email') ?></label>
                                                            <input type="email" class="form-control" ng-model="showstd.Parent_Email" required="">
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><?php echo lang('lbl_phone') ?></label>
                                                            <input type="text" class="form-control" ng-model="showstd.Parent_Contact">
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--/span-->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><?php echo lang('lbl_address') ?></label>
                                                            <input type="text" class="form-control" ng-model="showstd.Parent_Address">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><?php echo lang('lbl_ic_number') ?></label>
                                                            <input type="text" name="pId" class="form-control" ng-model="showstd.IC_Number">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><?php echo lang('lbl_country') ?></label>
                                                            <select name="country" ng-model="showstd.Parent_Country" class="form-control" required="" id="parent_country">
                                                                <option value=""><?php echo lang('select_country') ?></option>
                                                                <?php foreach ($countries as $country) { ?>
                                                                    <option value="<?php echo $country->id; ?>"><?php echo $country->country_name; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><?php echo lang('lbl_city') ?></label>
                                                            <input type="text" class="form-control" ng-model="showstd.Parent_City">
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                </div>
                                            </div> 
                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-success" > <i class="fa fa-check"></i> <?php echo lang('btn_update') ?></button>
                                                <button type="button" ng-click="ShowList()" class="btn btn-default"><?php echo lang('btn_cancel') ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>   
                            </div>
                        </div>
                    </div>
                </div>

                <!--- End::Updated code --->

            </div>
        </div>
    </div>
</div>
<!-- /.row -->
<!--page content end-->
</div>
</div>
<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>
