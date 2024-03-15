<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<style>
    .custom_bottom_border{
        border-bottom: 1px solid #e5e5e5; 
        margin-bottom: 10px;
    }
</style>
<div>
    <!-- Page Content -->
    <div id="page-wrapper" ng-controller="syllabusParentViewController">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?php echo lang('lbl_syllabus') ?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="#"><?= lang('crumb_dashboard') ?></a></li>
                        <li><a href="#"><?php echo lang('lbl_syllabus') ?></a></li>
                        <li class="active"><?php echo lang('lbl_manage_syllabus') ?></li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <div class="hint"><?php echo lang('help_manage_syllabus'); ?></div>
            <!-- Page Content start here -->
            <!--.row-->

            

            <!--.row-->
            <div class="white-box" id="syllabus_search_filter">
                <form class="form-material" name="syllabusFilterForm" ng-submit="onSubmit(syllabusFilterForm.$valid)" novalidate="">
                    <div class="row">

                        <div class="col-md-4" id="marksFilterClasses">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('lbl_child'); ?></label>
                                <select class="form-control" ng-model="filterModel.student_id" ng-init="initGetParentChild()" ng-change="initSubjects(filterModel.student_id)" required="">
                                    <option value=""><?php echo lang('select_course') ?></option>
                                    <option ng-repeat="cls in parentchild" value="{{cls.student_id}}">{{cls.name}}</option>
                                </select>
                            </div>     
                        </div>

                        

                        <div class="col-md-4" id="syllabusFilterSubjects">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('lbl_subject') ?></label>
                                <select class="form-control" ng-model="filterModel.subject_id" required="">
                                    <option value=""><?php echo lang('lbl_select_subject') ?></option>
                                    <option ng-repeat="sub in subjects" value="{{sub.id}}">{{sub.name}}</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <p class="error" ng-show="message"><b>{{ message }}</b></p>
                        </div>
                        <div class="col-md-4">
                            <input type="submit" class="btn btn-primary pull-right" value="<?php echo lang('search') ?>" />
                        </div>
                    </div>
                </form>
            </div>
            
            <!--./row-->
            <div class="row" ng-show="isClick">
                
                <div class="col-md-12" id="syllabusContainer">
                    <div class="white-box">
                        
                        <div class="row">
                            <div class="well panel-info" style="padding:0; margin-right:5px; display: inline-block; max-width: 300px;" ng-repeat="row in weeklySyllabus">
                                
                                <div class="panel-heading">
                                    <strong>{{ row.week }}</strong> 
                                    
                                    <br />
                                    (<small>{{row.start_date}}&nbsp;To&nbsp;{{row.end_date}}</small>)
                                </div>
                                
                                <div  class="panel-body">
                                    <div ng-repeat="d in row.data">
                                    
                                        <div class="col-md-12" style="padding-left:0; padding-right:0;" ng-class="{custom_bottom_border: (d.status != 'Partially Done' && d.status != 'Reschedule')}" ng-if="d.week_detail_id != NULL">
                                            
                                            <label>{{d.topic}}</label> 
                                            <br/>(<small>{{d.day}}</small>)

                                            <br />

                                            <div class="btn-group" style="margin-top: 10px; margin-bottom: 10px;" ng-class="{custom_disable : (requestStatus=='inprocess' || requestStatus=='edit-request' || d.status=='Done')}">
                                                <button type="button" class="btn waves-effect waves-light btn-sm" ng-class="{
                                                            'btn-outline-info': d.status=='Pending', 
                                                            'btn-outline-success': d.status=='Done', 
                                                            'btn-outline-danger': d.status=='Skip', 
                                                            'btn-outline-warning': d.status=='Partially Done',
                                                            'btn-outline-primary': d.status=='Reschedule'
                                                        }" 
                                                        style="font-size: 12px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <small>{{d.status}}</small>
                                                </button>

                                                
                                                
                                                
                                            </div>
                                    </div>
                                    
                                    
                                    
                                   

                                    <p style="text-align: center;" ng-if="!d.is_working_day">
                                        <a href="javascript:void(0)" class="btn waves-effect waves-light btn-outline-secondary custom_disable btn-block"> {{d.day}}</a>
                                    </p>
 
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