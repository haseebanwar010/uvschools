<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<!-- Page Content -->
<div>
<div id="page-wrapper"  ng-controller="employeeReportController" ng-init="onsubmit()">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('heading_all_employee') ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="<?php echo site_url('/dashboard') ?>"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li><a href="<?php echo site_url('/reports/all') ?>"><?= lang('reports_all') ?></a></li>
                    <li class="active"><a href="<?php echo site_url('/reports/employees') ?>"><?= lang('heading_all_employee') ?></a></li>

                </ol>
            </div>
        </div>
        <!-- End alert message -->
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <!-- new changes by Yasir 01-03-2018 -->
                    <div id="empReprotDev">
                <form class="form-material" name="attFilterForm" ng-submit="onsubmit(attFilterForm.$valid)" novalidate="">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang('lbl_name') ?></label>
                            <input type="text" id="filterName" ng-model="filterModel.name"  class="form-control " placeholder="Name" />
                        </div>
                    </div>
                    <div class="col-md-2" id="attFilterDepartments">
                        <div class="form-group">
                            <label style="padding-top: 4px; height: 65px; font-size: 1em;" class="btn-sm dropdown-toggle form-control" data-toggle="dropdown"><?php echo lang('title_department') ?><span class="caret pull-right" ></span></label>
                            <!-- <p><span class="dropdownTags" ng-repeat =" seltd in DeptSelected">{{seltd.name}} </span></p> -->
                            <ul class="dropdown-menu"  ng-init="getDepartments()" style="overflow-y: scroll; max-height: 300px">
                                <li><a href="javascipt:void(0)" ><input type="checkbox" ng-model="DeptAll" ng-click="checkAllDpt()" name=""/>&nbsp;&nbsp;<?php echo lang('lbl_select_all') ?></a></li>
                                <hr/>
                                <li ng-repeat="dep in departments"><a href="javascipt:void(0)" tabIndex="-1"><input type="checkbox" name="" ng-model="DeptSelected" ng-checked="existDpt(dep)" ng-click="toggleDpt(dep)"/>&nbsp;&nbsp;{{dep.name}}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-2" id="FilterCategories">
                        <div class="form-group">
                            <label style="padding-top: 2px;  height: 65px; font-size: 1em;"  class=" btn-sm dropdown-toggle form-control" data-toggle="dropdown"><?php echo lang('title_category') ?><span class="caret pull-right"  ></span></label>
                           <!--  <p><span class="dropdownTags" ng-repeat =" seltd in CategorySelected">{{seltd.category}} </span></p> -->
                            <ul class="dropdown-menu"  style="overflow-y: scroll; max-height: 300px">
                                <li ><a href="javascipt:void(0)" ><input type="checkbox" ng-model="categoryAll" ng-click="checkAll1Category()" name=""/>&nbsp;&nbsp;<?php echo lang('lbl_select_all') ?></a></li>
                                <hr/>
                                <li ng-repeat="cat in categories"><a href="javascipt:void(0)" tabIndex="-1"><input type="checkbox" name="" ng-model="CategorySelected" ng-checked="existCategory(cat)" ng-click="toggleCategory(cat)" />&nbsp;&nbsp;{{cat.category}}<span class="pull-right" style="font-size: 12px; ">-{{cat.dept_name}}</span></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang('lbl_gender') ?></label>
                            <select class="form-control" ng-model="filterModel.gender">
                                <option value=""><?php echo lang('lbl_all') ?></option>
                                <option value="male"><?php echo lang('option_male') ?></option>
                                <option value="female"><?php echo lang('option_female') ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang('lbl_from') ?></label>
                            <input type="text" ng-model="filterModel.from" style="height:38;" class="form-control mydatepicker-autoclose" placeholder="<?php echo date('d/m/Y'); ?>" />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang('lbl_to') ?></label>
                            <input type="text" ng-model="filterModel.to" style="height:38;" class="form-control mydatepicker-autoclose" placeholder="<?php echo date('d/m/Y'); ?>" />
                        </div>
                    </div>
                   
                </div>
                <div class="row">
                    <div class="col-md-8">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary pull-right"><?php echo lang('search') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>                
                    
        <div class="" id="stdTableContianer">
            <div class="row col-sm-12">
                <label style="margin-right:10px;"><?php echo lang('lbl_columns') ?> :</label>
                <input type="checkbox" id="customCheck1" style ="cursor: pointer;" class="showHideCol btn btn-outline-info" data-cloumnsindex = "3"/><label class="custom-control-label " style ="cursor: pointer; margin-right: 15px; margin-left: 5px" for="customCheck1" ><?php echo lang('lbl_job_type') ?></label>
                <input type="checkbox" id="customCheck2" style ="cursor: pointer;" class="showHideCol btn btn-outline-info" data-cloumnsindex = "4"/><label class="custom-control-label " style ="cursor: pointer;  margin-right: 15px; margin-left: 5px" for="customCheck2" ><?php echo lang('lbl_email') ?></label>
                <input type="checkbox" id="customCheck3" style ="cursor: pointer;" class="showHideCol btn btn-outline-info" data-cloumnsindex = "5"/><label class="custom-control-label " style ="cursor: pointer;  margin-right: 15px; margin-left: 5px" for="customCheck3" ><?php echo lang('lbl_gender') ?></label>
                <input type="checkbox" id="customCheck4" style ="cursor: pointer;" class="showHideCol btn btn-outline-info" data-cloumnsindex = "6"/><label class="custom-control-label " style ="cursor: pointer;  margin-right: 15px; margin-left: 5px" for="customCheck4" ><?php echo lang('lbl_mobile') ?></label>
                <input type="checkbox" id="customCheck5" style ="cursor: pointer;" class="showHideCol btn btn-outline-info" data-cloumnsindex = "7"/><label class="custom-control-label " style ="cursor: pointer;  margin-right: 15px; margin-left: 5px" for="customCheck5" ><?php echo lang('lbl_qualification') ?></label>
                <input type="checkbox" id="customCheck6" style ="cursor: pointer;" class="showHideCol btn btn-outline-info" data-cloumnsindex = "8"/><label class="custom-control-label " style ="cursor: pointer;  margin-right: 15px; margin-left: 5px" for="customCheck6" ><?php echo lang('lbl_passport_number') ?></label>
                
<!--                <a class="showHideCol btn btn-outline-info" data-cloumnsindex = "3" style=" padding: 10px;cursor: pointer; color : blue;">Job Type</a>
                <a class="showHideCol btn btn-outline-info" data-cloumnsindex = "4" style=" padding: 10px;cursor: pointer; color : blue;">Email</a>
                <a class="showHideCol btn btn-outline-info" data-cloumnsindex = "5" style=" padding: 10px;cursor: pointer; color : blue;">Gender</a>
                <a class="showHideCol btn btn-outline-info" data-cloumnsindex = "6" style=" padding: 10px;cursor: pointer; color : blue;">Mobile</a>
                <a class="showHideCol btn btn-outline-info" data-cloumnsindex = "7" style=" padding: 10px;cursor: pointer; color : blue;">Qualification</a>
                <a class="showHideCol btn btn-outline-info" data-cloumnsindex = "8" style=" padding: 10px;cursor: pointer; color : blue;">Passport</a>-->
                    
                <div class="table-responsive ">
                    <div class="white-box" >
                        <div style="overflow-x:auto">
                            <table id="myTablee" class="display" cellspacing="0" width="100%"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
<!--page content end-->
</div>
    </div>
</div>
</div>
    <style>
        .changeColor{
         color : Green;
    }

    </style>
<?php include(APPPATH . "views/inc/footer.php"); ?>
    <script>
        
       $('.showHideCol').on('click', function(){
          var tableColumn = $('#myTablee').DataTable().column($(this).attr('data-cloumnsindex')); 
          tableColumn.visible(!tableColumn.visible());
          tableColumn.visible().css("background-color", "yellow");
    }); 
    
    </script>	    