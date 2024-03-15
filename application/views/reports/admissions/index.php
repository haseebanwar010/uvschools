<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<!-- Page Content -->
<div id="page-wrapper" ng-controller="">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('reports_all') ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="<?php echo site_url('/dashboard') ?>"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li><a href="<?php echo site_url('/reports') ?>"><?php echo lang('reports_all') ?></a></li>
                     <li><a href="<?php echo site_url('/reports/admission') ?>"><?php echo lang('lbl_admission') ?></a></li>
                </ol>
            </div>
        </div>
       
        <div class="white-box well" >
            <div ng-controller="AdmissionReports">
               <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <!-- new changes by Yasir 01-03-2018 -->
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
                            <label class="control-label"><?php echo lang('title_department') ?></label>
                            <select class="form-control" id="department" onchange="getCategories($(this).val())">
                                    <option value="all"><?php echo lang('option_all') ?></option>
                                    <?php if(count($departments)>0){ foreach($departments as $department) { ?>
                                    <option value="<?php echo encrypt($department->id) ?>"><?php echo $department->name; ?></option>
                                    <?php } }?>
                                </select>
                        </div>
                    </div>
                    <div class="col-md-2" id="attFilterBatches">
                        <div class="form-group">
                                <label class="control-label"><?php echo lang('title_category') ?></label>
                                <select class="form-control" name="categories" id="categories" onchange=";">
                                  <option value="all"><?php echo lang('option_all') ?></option>
                              </select>
                          </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang('lbl_start_date') ?></label>
                            <input type="text" ng-model="filterModel.start_date"  class="form-control mydatepicker-autoclose" placeholder="<?php echo date('d/m/Y'); ?>" />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang('lbl_end_date') ?></label>
                            <input type="text" ng-model="filterModel.end_date"  class="form-control mydatepicker-autoclose" placeholder="<?php echo date('d/m/Y'); ?>" />
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
            <div class="row">

                <div class="table-responsive">
                    <table class="myTable display nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th><?php echo lang('heading_name') ?></th>
                                <th><?php echo lang('heading_position') ?></th>
                                <th><?php echo lang('heading_email') ?></th>
                                <th><?php echo lang('heading_ic_number') ?></th>
                                <th><?php echo lang('heading_country') ?></th>
                                <th><?php echo lang('heading_action') ?></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th><?php echo lang('heading_name') ?></th>
                                <th><?php echo lang('heading_position') ?></th>
                                <th><?php echo lang('heading_email') ?></th>
                                <th><?php echo lang('heading_ic_number') ?></th>
                                <th><?php echo lang('heading_country') ?></th>
                                <th><?php echo lang('heading_action') ?></th>
                            </tr>
                        </tfoot>
                        <tbody id="myTableBody">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> 
                
                
            </div>
        </div>
        <!--./row-->

<!--        <div class="white-box" id="attStudentsTable" ng-show="">
            
        </div>

        <div class="white-box" id="attStudentsTable" ng-show="">
            <div class="row">
                <div class="col-md-12" style="display:none">No record found.</div>
            </div>
        </div>-->

    </div>
</div>

<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>
   