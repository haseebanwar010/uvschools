<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<div>
    <!-- Page Content -->
    <div id="page-wrapper" ng-controller="studentReportController" ng-init="onsubmit()">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?php echo lang('reports_all') ?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                    <ol class="breadcrumb">
                        <li><a href="<?php echo site_url('/dashboard') ?>"><?php echo lang('crumb_dashboard') ?></a></li>
                        <li><a href="<?php echo site_url('/reports/all') ?>"><?php echo lang('reports_all') ?></a></li>
                        <li><a href="<?php echo site_url('/reports/students') ?>"><?php echo lang('lbl_students') ?></a></li>
                    </ol>
                </div>
            </div>

            <div class="white-box well" id="stdReprotDev">
                <form class="form-material" name="attFilterForm" ng-submit="onsubmit(attFilterForm.$valid)" novalidate="">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('lbl_name') ?></label>
                                <input type="text" ng-model="filterModel.name"  class="form-control " placeholder="Search" />
                            </div>
                        </div>
                        <div class="col-md-2" id="attFilterClasses">     
                            <div class="form-group">
                                <label style="padding-top: 4px; height: 65px; font-size: 1em;" class="btn-sm dropdown-toggle form-control" data-toggle="dropdown"><?php echo lang('lbl_classes') ?><span class="caret pull-right" ></span></label>
                                <!-- <p><span class="dropdownTags" ng-repeat =" seltd in selected">{{seltd.name}}</span></p> -->
                                <ul class="dropdown-menu"  ng-init="initClasses()" style="overflow-y: scroll; max-height: 300px">
                                    <li><a href="javascipt:void(0)" ><input type="checkbox" ng-model="selectAll" ng-click="checkAll()" id="chk_all_classes"/><?php echo lang('lbl_select_all') ?></a></li>
                                    <hr/>
                                    <li ng-repeat="cls in classes" style="padding-left: 10px;"><a href="javascipt:void(0)" ><input type="checkbox" id="chk_dropdown" ng-model="selected" ng-checked="exist(cls)" ng-click="toggleSelection(cls)"/>&nbsp;&nbsp;{{cls.name}}</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-2" id="attFilterBatches">
                            <div class="form-group">
                                <label style="padding-top: 2px;  height: 65px; font-size: 1em;"  class=" btn-sm dropdown-toggle form-control" data-toggle="dropdown"><?php echo lang('lbl_batches') ?><span class="caret pull-right"  ></span></label>
                                <!-- <p><span  class="dropdownTags" ng-repeat =" seltd in selected1">{{seltd.name}} </span></p> -->
                                <ul class="dropdown-menu" style="overflow-y: scroll; max-height: 300px">
                                    <li ><a href="javascipt:void(0)" ><input type="checkbox" ng-model="selectAll1" ng-click="checkAll1()" name=""/>&nbsp;&nbsp;<?php echo lang('lbl_select_all') ?></a></li>
                                    <hr/>
                                    <li ng-repeat="bth in batches" style="padding-left: 10px;"><a href="javascipt:void(0)" ><input type="checkbox" name="" ng-model="selected1" ng-checked="exist1(bth)" ng-click="toggleSelection1(bth)" />&nbsp;&nbsp;{{bth.name}}<span class="pull-right" style="font-size: 12px; ">-{{bth.class_name}}</span></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('lbl_gender') ?></label>
                                <select class="form-control" ng-model="filterModel.gender" style="height:37px;">
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
            <!--./row-->
            <div class="" id="stdTableContianer">
                <div class="row col-sm-12">
                    <label style="margin-right:10px;"><?php echo lang('lbl_columns') ?> :</label>
                    <input type="checkbox" id="customCheck01" style ="cursor: pointer;" class="showHideCol btn btn-outline-info" data-cloumnsindex = "4"/><label class="custom-control-label " style ="cursor: pointer; margin-right: 15px; margin-left: 5px" for="customCheck01" ><?php echo lang('lbl_gender') ?></label>
                    <input type="checkbox" id="customCheck02" style ="cursor: pointer;" class="showHideCol btn btn-outline-info" data-cloumnsindex = "5"/><label class="custom-control-label " style ="cursor: pointer; margin-right: 15px; margin-left: 5px" for="customCheck02" ><?php echo lang('lbl_email') ?></label>
                    <input type="checkbox" id="customCheck03" style ="cursor: pointer;" class="showHideCol btn btn-outline-info" data-cloumnsindex = "6"/><label class="custom-control-label " style ="cursor: pointer; margin-right: 15px; margin-left: 5px" for="customCheck03" ><?php echo lang('lbl_city') ?></label>
                    <input type="checkbox" id="customCheck04" style ="cursor: pointer;" class="showHideCol btn btn-outline-info" data-cloumnsindex = "7"/><label class="custom-control-label " style ="cursor: pointer; margin-right: 15px; margin-left: 5px" for="customCheck04" ><?php echo lang('lbl_mobile') ?></label>
                    <input type="checkbox" id="customCheck5" style ="cursor: pointer;" class="showHideCol btn btn-outline-info" data-cloumnsindex = "8"/><label class="custom-control-label " style ="cursor: pointer; margin-right: 15px; margin-left: 5px" for="customCheck5" ><?php echo lang('lbl_religion') ?></label>
                    <input type="checkbox" id="customCheck06" style ="cursor: pointer;" class="showHideCol btn btn-outline-info" data-cloumnsindex = "9"/><label class="custom-control-label " style ="cursor: pointer; margin-right: 15px; margin-left: 5px" for="customCheck06" ><?php echo lang('lbl_dob') ?></label>
                    <input type="checkbox" id="customCheck07" style ="cursor: pointer;" class="showHideCol btn btn-outline-info" data-cloumnsindex = "10"/><label class="custom-control-label " style ="cursor: pointer; margin-right: 15px; margin-left: 5px" for="customCheck07" ><?php echo lang('lbl_nationality') ?></label>
                    <input type="checkbox" id="customCheck08" style ="cursor: pointer;" class="showHideCol btn btn-outline-info" data-cloumnsindex = "11"/><label class="custom-control-label " style ="cursor: pointer; margin-right: 15px; margin-left: 5px" for="customCheck08" ><?php echo lang('lbl_passport_number') ?></label>

                    <!--            <a class="showHideCol btn btn-outline-info" data-cloumnsindex = "4" style=" padding: 10px;cursor: pointer; color : blue;">gender</a>
                                    <a class="showHideCol btn btn-outline-info" data-cloumnsindex = "5" style=" padding: 10px;cursor: pointer; color : blue;">Email</a>
                                    <a class="showHideCol btn btn-outline-info" data-cloumnsindex = "6" style=" padding: 10px;cursor: pointer; color : blue;">City</a>
                                    <a class="showHideCol btn btn-outline-info" data-cloumnsindex = "7" style=" padding: 10px;cursor: pointer; color : blue;">Mobile</a>
                                    <a class="showHideCol btn btn-outline-info" data-cloumnsindex = "8" style=" padding: 10px;cursor: pointer; color : blue;">Religion</a>
                                    <a class="showHideCol btn btn-outline-info" data-cloumnsindex = "9" style=" padding: 10px;cursor: pointer; color : blue;">DOB</a>
                                    <a class="showHideCol btn btn-outline-info" data-cloumnsindex = "10" style=" padding: 10px;cursor: pointer; color : blue;">Nationality</a>
                                    <a class="showHideCol btn btn-outline-info" data-cloumnsindex = "11" style=" padding: 10px;cursor: pointer; color : blue;">Passport</a> -->

                    <div class="table-responsive">
                        <div class="white-box" >
                            <div style="overflow-x:auto">
                                <table id="myTablee" class="display" cellspacing="0" width="100%"></table>
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

<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>
<!-- <script>

    $('.showHideCol').on('click', function () {
        e.preventDefault();
        var tableColumn = $('#myTablee').DataTable().column($(this).attr('data-cloumnsindex'));
        tableColumn.visible(!tableColumn.visible());

    });
</script> -->

<script>
        
       $('.showHideCol').on('click', function(){
          var tableColumn = $('#myTablee').DataTable().column($(this).attr('data-cloumnsindex')); 
          tableColumn.visible(!tableColumn.visible());
          tableColumn.visible().css("background-color", "yellow");
              
    }); 
    
</script>



