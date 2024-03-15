<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<div>
    <!-- Page Content -->
    <div id="page-wrapper" ng-controller="addexamConroller">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?php echo lang('online_exam_settings');?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="#"><?= lang('crumb_dashboard') ?></a></li>
                        <li><a href="#"><?php echo lang('lbl_online_exam');?></a></li>
                        <li class="active"><?php echo lang('settings');?></li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <div class="hint"><?php echo lang('help_online_exams_settings');?></div>
            <!-- Page Content start here -->
            <!--.row-->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">

                        <div class="panel-wrapper collapse in" aria-expanded="true">
                            <div class="panel-body">

                                <ul class="nav customtab nav-tabs" role="tablist">
                                    <li role="presentation" class="nav-item">
                                        <a href="#exams" class="nav-link active"
                                        aria-controls="profile" role="tab"
                                        data-toggle="tab"
                                        aria-expanded="true"><span
                                        class="visible-xs"><i class="fa fa-calculator"></i></span><span
                                        class="hidden-xs"><?php echo lang('lbl_exams') ?></span></a>
                                    </li>

                                    <li role="presentation" class="nav-item">
                                        <a href="#exam_details" class="nav-link"
                                        aria-controls="profile" role="tab"
                                        data-toggle="tab"
                                        aria-expanded="true"><span
                                        class="visible-xs"><i class="fa fa-indent"></i></span><span
                                        class="hidden-xs"><?php echo lang('lbl_exam_details') ?></span></a>
                                    </li>
                                    
                                    <?php if($_SERVER['REMOTE_ADDR']=='137.59.228.255'){ ?>
                                    <!--<li role="presentation" class="nav-item">-->
                                    <!--    <a href="#exam_details_other" class="nav-link"-->
                                    <!--    aria-controls="profile" role="tab"-->
                                    <!--    data-toggle="tab"-->
                                    <!--    aria-expanded="true"><span-->
                                    <!--    class="visible-xs"><i class="fa fa-indent"></i></span><span-->
                                    <!--    class="hidden-xs">Exam Details 1</span></a>-->
                                    <!--</li>-->
                                    <?php } ?>
                                </ul>
                                <!--tab content start here-->
                                <div class="tab-content">
                                    <div class="tab-pane active" id="exams">
                                        <!-- row -->
                                        <div class="hint"><?php echo lang('help_online_exams_settings');?></div>
                                        <?php if(isset($exams_xcrud)){ echo $exams_xcrud; } else{ echo lang('you_dont_have_access') ; }?>
                                        <!--/row-->
                                    </div>

                                    <div class="tab-pane" id="exam_details">
                                        <!-- row -->
                                        <div class="hint"><?php echo lang('help_online_exam_details') ?></div>

                                        

                                        <?php echo $exam_details; ?>
                                        <!--/row-->
                                    </div>
                                    
                                    
                                    <div class="tab-pane" id="exam_details_other">
                                        <div class="hint"><?php echo lang('help_online_exam_details') ?></div>
                                        <button type="button" data-toggle="modal" data-target="#addexam_settings" class="fcbtn btn btn-primary btn-1e"><i class="fa fa-plus "></i><?php echo lang('add_fee_type') ?></button>
                                    </div>


                                    

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
        
        

                                    <!-- Add new Modal Content -->
                                    <div class="modal fade" id="addexam_settings" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content" id="addexam_settings-content">
                                                <div class="panel panel-primary">
                                                    <div class="panel-heading">Add Exam Detail Settings</div>
                                                    <div class="panel-body">
                                                        <form name="addexam_settingsForm" ng-submit="saveexam_settings(addexam_settingsForm)" novalidate="" class="form-material ">
                                                        <!--<form name="addFeetypeForm" ng-submit="saveFeetype(addFeetypeForm.$valid)" novalidate="" class="form-material ">-->
                                                            <div class="form-body">
                                                                
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Exam Name</label>
                                                                            <!--<input type="text" required="" ng-model="adModel.examname" class="form-control">-->
                                                                            <select class="form-control " name="examname" required="" ng-model="examname" ng-change="getclasses_examdetail()">
                                                                                <option value="" disabled="" selected>--<?php echo lang('lbl_select'); ?>--</option>
                                                                                <?php 
                                                                                    foreach($pending_exams as $pending_exam)
                                                                                    {?>
                                                                                        <option value="<?php echo $pending_exam->id;?>"><?php echo $pending_exam->title;?></option>
                                                                                <?php } ?>
                                                                                
                                                                            </select>
                                                                            <!--<div style="color: red" ng-show="examname_error"><?php echo lang('lbl_field_required') ?></div>-->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Class</label>
                                                                            <select class="form-control " name="online_Eclass" required="" ng-model="online_Eclass" ng-change="getSubjects_upload()">
                                                                                <option value="" disabled="" selected>--<?php echo lang('lbl_select'); ?>--</option>
                                                                                <option ng-repeat="oe_class in online_Eclass" value="{{oe_class.id}}">{{oe_class.name}}</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Section</label>
                                                                            <input type="text" required="" ng-model="adModel.section" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Subject</label>
                                                                            <input type="text" required="" ng-model="adModel.subject" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Paper Name</label>
                                                                            <input type="text" required="" ng-model="adModel.papername" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Number Of Questions</label>
                                                                            <input type="number" required="" ng-model="adModel.numberofqus" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Duration In Minutes</label>
                                                                            <input type="text" required="" ng-model="adModel.totaltime" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <!--/span-->
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Start Time</label>
                                                                            <input type="text" ng-model="adModel.starttime" placeholder="" required="" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                                                                                
                                                                <!--/span-->
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">End Time</label>
                                                                            <input type="text" ng-model="adModel.endtime" placeholder="" required="" class="form-control">
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
        
        
        
        
    </div>
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

<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>
