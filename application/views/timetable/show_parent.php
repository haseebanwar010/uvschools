<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<div>
    <!-- Page Content -->
    <div id="page-wrapper" ng-controller="parenttimeTableCtrl">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?php echo lang('lbl_timetable') ?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="#"><?php echo lang('crumb_dashboard') ?></a></li>
                        <li class="active"><?php echo lang('lbl_timetable') ?></li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <div class="hint"><?php echo lang('help_timetable'); ?></div>
            

            
            
            <div class="white-box well" id="timetable_search_filter">
                <div class="row">
                    <div class="col-md-12">
                        <form name="timeTableForm" ng-submit="fetchSubjects(timeTableForm.$valid)" novalidate="" class="form-material">
                            <div class="row">
                        <div class="col-md-3" id="tbFilterChild">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('lbl_child') ?></label>
                                    <select class="form-control" ng-model="tbModel.student_id" ng-init="initGetParentChild()" required="">
                                    <option value=""><?php echo lang('lbl_select_child') ?></option>
                                <option ng-repeat="c in parentchild" value="{{c.student_id}}">{{c.name}}</option>
                                </select>
                            </div>
                        </div>
                            
                        </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary pull-right"><?php echo lang('search') ?></button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            <div class="row" ng-show="error">
                <div class="col-md-12">
                    <div class="white-box text-danger">{{ error.message }}</div>
                </div>
            </div>
            
            <!--.row-->
            <div class="row" ng-if="!error && error != null">
                <div class="col-sm-12">
                    <div class="white-box">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <tr>
                                    <th></th>
                                    <th ng-repeat="sb in periods">
                                        <p>
                                            {{ sb.title }} <br/>
                                            <span class="small">
                                            {{ sb.start_time2 }} - {{ sb.end_time2 }}
                                        </span>
                                        </p>
                                        <!--<p ng-if="sb.is_break==='N'">
                                            
                                        </p>
                                        <p ng-if="sb.is_break==='Y'">
                                            Break <br/>
                                            {{ sb.start_time }} - {{ sb.end_time }}
                                        </p>-->
                                    </th>
                                </tr>
                                
                                <tr ng-repeat="(k,yy) in timeTable">
                                    <th>{{ k }}</th>
                                    <td ng-repeat="yyy in yy">
                                        <p class="text-center" ng-if="yyy.timetable_id !== null && yyy.is_break === 'N'" data-toggle="modal" onMouseOver="this.style.cursor='pointer'" data-target=".edit-time-table-model" ng-click="selectedValues2(yyy)">
                                            {{ yyy.sub_name }} <br/>
                                            <small>{{ yyy.teacher_name }}</small><br/>
                                            <?php echo lang('room_no');?>: {{ yyy.room_no }}
                                        </p>
                                        <p class="text-center" ng-if="yyy.is_break === 'Y'">
                                            <span class="text-danger"><br />-</span>
                                        </p>
                                        
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <!--/.row-->
    </div>
    <!--./row-->
    <!--page content end here-->
</div>
</div>
<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>