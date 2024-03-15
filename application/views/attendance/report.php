<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<!-- Page Content -->
<div id="page-wrapper" ng-controller="arController">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('lbl_attendance_report') ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="<?php echo site_url('/dashboard') ?>"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li><a href="<?php echo site_url('/attendance') ?>"><?php echo lang('lbl_attendance') ?></a></li>
                    <li class="active"><?php echo lang('lbl_attendance_report') ?></li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div class="hint"><?php echo lang('help_attendence_report'); ?></div>
        <!--.row-->
        <div class="white-box well" id="attReport_search_filter">
            <form class="form-material" name="arFilterForm" ng-submit="onSubmitFetchReport(arFilterForm.$valid)" novalidate="">
                <div class="row">
                    <div class="col-md-3" id="arFilterClasses">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang('lbl_class') ?></label>
                            <select class="form-control" id="cls" ng-model="arModel.class_id" ng-init="initClasses()" ng-change="initBatches(arModel.class_id)" required="">
                                <option value=""><?php echo lang('select_course') ?></option>
                                <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3" id="arFilterBatches">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang('lbl_batch') ?></label>
                            <select class="form-control" id="bth" ng-model="arModel.batch_id" required="">
                                <option value=""><?php echo lang('select_batch') ?></option>
                                <option ng-repeat="bth in batches" value="{{bth.id}}">{{bth.name}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3" id="arFilterMonth">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang('lbl_month') ?></label>
                            <select class="form-control" id="month" ng-init="initMonths()" ng-model="arModel.month" required="">
                                <option value=""><?php echo lang('lbl_select_month') ?></option>
                                <option ng-repeat="(key, month) in months" value="{{key}}">{{month}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3" id="arFilterName">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang('serach_by_keyword') ?></label>
                            <input type="text" name="name" ng-model="arModel.name" ng-init="arModel.name=''" class="form-control" placeholder="<?php echo lang('lbl_name') ?>"/>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary pull-right"><?php echo lang('search') ?></button>
                    </div>
                </div>
            </form>
        </div>
        
        <!--./row-->
        <div class="white-box attendance-table" ng-show="finalReport[0].attendance">
            <div class="row">
                <div class="col-md-12 p-b-10" style="text-align: right;">
                    <button type="button" class="btn btn-sm btn-default" onclick="printD()"><i class="fa fa-print"></i></button>
                </div>
                <div class="col-md-12" id="sheet">
                    <div class="table-responsive">
                            <table id="attendance-table" class="table table-striped table-hover" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo lang('lbl_name') ?></th>
                                        <th ng-repeat="val in finalReport[0].attendance">{{$index+1}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="fr in finalReport">
                                        <td>{{fr.name}}</td>
                                        <td ng-style="(status == 'Absent' || status=='غائب') && {'color': 'red'} ||
                                        (status == 'Present' || status == 'حاضر' )&& {'color': '#5cb85c'} ||
                                        (status == 'Late' || status == 'متاخر') && {'color': 'orange'}" ng-repeat="status in fr.attendance">{{ status }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
        </div>
        
        <div class="white-box attendance-table" id="arTableEmpty" ng-show="finalReport.length==0">
            <div class="row">
                <div class="col-md-12"><?php echo lang('no_record') ?></div>
            </div>
        </div>

    </div>
</div>
<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>
   