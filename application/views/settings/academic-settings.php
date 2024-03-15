<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<div>
    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?php echo lang('crumb_academic_setting') ?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="#"><?= lang('crumb_dashboard') ?></a></li>
                        <li><a href="#"><?php echo lang('crumb_settings') ?></a></li>
                        <li class="active"><?php echo lang('crumb_academic_setting') ?></li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <div class="hint"><?php echo lang('help_academic_setting'); ?></div>
            <!-- Page Content start here -->
            <!--.row-->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">

                        <div class="panel-wrapper collapse in" aria-expanded="true">
                            <div class="panel-body">

                                <ul class="nav customtab nav-tabs table-responsive" role="tablist">
                                    <li role="presentation" class="nav-item"><a href="#academicyears" class="nav-link <?php if ($selected_tab === 'tab_academicyears') {
                                        echo "active";
                                    } else if ($selected_tab === "") {
                                        echo "active";
                                    } ?>" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="fa fa-calendar"></i></span><span class="hidden-xs"><?php echo lang('lbl_academic_year') ?></span></a></li>
                                    <li role="presentation" class="nav-item"><a href="#class_levels" class="nav-link" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="fa fa-object-group"></i></span><span class="hidden-xs"><?php echo lang('class_level_settings'); ?></span></a></li>
                                    <li role="presentation" class="nav-item"><a href="#class" class="nav-link <?php if ($selected_tab === 'tab_classes') {
                                        echo "active";
                                    } ?>" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="fa fa-columns"></i></span><span class="hidden-xs"><?php echo lang('lbl_classes') ?></span></a></li> 
                                    <li role="presentation" class="nav-item"><a href="#batch" class="nav-link <?php if ($selected_tab === 'tab_batches') {
                                        echo "active";
                                    } ?>" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="fa fa-street-view"></i></span><span class="hidden-xs"><?php echo lang('lbl_batches') ?></span></a></li> 
                                    <li role="presentation" class="nav-item"><a href="#subjects" class="nav-link <?php if ($selected_tab === 'tab_subjects') {
                                        echo "active";
                                    } ?>" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="fa fa-book"></i></span><span class="hidden-xs"><?php echo lang('lbl_subjects') ?></span></a></li> 
                                    <li role="presentation" class="nav-item"><a href="#subject_groups" class="nav-link <?php if ($selected_tab === 'tab_subject_groups') {
                                        echo "active";
                                    } ?>" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="fa fa-book"></i></span><span class="hidden-xs"><?php echo lang('subject_groups');?></span></a></li> 
                                    <li role="presentation" class="nav-item"><a href="#assignsubjects" class="nav-link <?php if ($selected_tab === 'tab_assignsubjects') {
                                        echo "active";
                                    } ?>" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="fa fa-bookmark"></i></span><span class="hidden-xs"><?php echo lang('lbl_assign_subjects') ?></span></a></li> 
                                    <li role="presentation" class="nav-item"><a href="#periods" class="nav-link <?php if ($selected_tab === 'tab_periods') {
                                        echo "active";
                                    } ?>" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="fa fa-clock-o"></i></span><span class="hidden-xs"><?php echo lang('tab_periods') ?></span></a></li> 
                                </ul>
                                <!--tab content start here-->
                                <div class="tab-content">
                                    <div class="tab-pane <?php if ($selected_tab === 'tab_academicyears') {
                                        echo "active";
                                    } else if ($selected_tab === "") {
                                        echo "active";
                                    } ?>" id="academicyears">
                                    <div class="hint"><?php echo lang('help_academic_years'); ?></div>
                                    <?= $academic_years; ?>
                                </div>
                                <div class="tab-pane" id="class_levels">
                                    <div class="hint"><?php echo lang('help_class_levels'); ?></div>
                                    <?php echo $class_levels; ?>
                                </div>

                                <div class="tab-pane <?php if ($selected_tab === 'tab_classes') {
                                    echo "active";
                                } ?>" id="class">
                                <div class="hint"><?php echo lang('help_classes'); ?></div>
                                <?= $classes; ?>
                            </div>

                            <div class="tab-pane <?php if ($selected_tab === 'tab_batches') {
                                echo "active";
                            } ?>" id="batch" ng-controller="batchController" ng-init="init()">
                            <div class="hint"><?php echo lang('help_sections'); ?></div>
                            <div class="well" style="background:#e4e7ea;">
                                <!-- row -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                            <select class="form-control" ng-model="selectedClass" ng-click="init()" ng-init="selectedClass='<?php echo $tab_batches_selected_class_id; ?>'">
                                                <option value="all"><?php echo lang('option_all') ?></option>
                                                <option ng-repeat="class in allClasses" value="{{class.id}}">{{class.name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <!--<button class="btn btn-sm btn-info" ng-click="updateTable()">Search</button>-->
                                        <a class="btn btn-sm btn-primary text-white" href="<?php echo base_url() ?>settings/academic?tab=tab_batches&class_id={{selectedClass}}"><?php echo lang('search') ?></a>
                                    </div>
                                </div>
                            </div>
                            <div ng-show='!dynamicTable'><?= $batches ?></div>
                            <div ng-bind-html='dynamicTable'></div>
                        </div>

                        <div class="tab-pane <?php if ($selected_tab === 'tab_subjects') {
                            echo "active";
                        } ?>" id="subjects" ng-controller="subjectController">
                        <div class="hint"><?php echo lang('help_subjects'); ?></div>
                        <div class="well" style="background:#e4e7ea;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                        <select class="form-control" id="classes" ng-model="selecedVal" ng-init="selecedVal='<?= $tab_subjects_selected_class_id; ?>';fetchClasses(selecedVal)" ng-change="loadClassBatches(selecedVal)">
                                            <option value="all"><?php echo lang('option_all') ?></option>
                                            <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6" id="yasir_batches">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('lbl_batches') ?></label>
                                        <select class="form-control" ng-model="selecedVal2" id="batches" ng-init="selecedVal2='<?= $tab_subjects_selected_batch_id; ?>'">
                                            <option value="all"><?php echo lang('option_all') ?></option>
                                            <option ng-repeat="batch in batches" value="{{batch.id}}">{{batch.name}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- row -->
                            <div class="row">
                                <div class="col-md-12">
                                    <!--<button class="btn btn-sm btn-info" ng-click="loadSubjects()">Search</button>-->
                                    <a class="btn btn-sm btn-primary text-white" href="<?php echo base_url() ?>settings/academic?tab=tab_subjects&class_id={{selecedVal}}&batch_id={{selecedVal2}}"><?php echo lang('search') ?></a>
                                </div>
                            </div>
                        </div>
                        <div ng-show="!myDiv"><?= $subjects; ?></div>
                        <div ng-bind-html="myDiv"></div>
                    </div>
                    <div class="tab-pane <?php if ($selected_tab === 'tab_subject_groups') {
                            echo "active";
                        } ?>" id="subject_groups" ng-controller="subjectGroupsController">
                        <div class="hint"><?php echo lang('help_subjects'); ?></div>
                        <div class="well" style="background:#e4e7ea;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                        <select class="form-control" id="classes" ng-model="selecedVal" ng-init="selecedVal='<?= $tab_subject_groups_class_id; ?>';fetchClasses(selecedVal)" ng-change="loadClassBatches(selecedVal)">
                                            <option value="all"><?php echo lang('option_all') ?></option>
                                            <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6" id="yasir_batches">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('lbl_batches') ?></label>
                                        <select class="form-control" ng-model="selecedVal2" id="batches" ng-init="selecedVal2='<?= $tab_subject_groups_batch_id; ?>'">
                                            <option value="all"><?php echo lang('option_all') ?></option>
                                            <option ng-repeat="batch in batches" value="{{batch.id}}">{{batch.name}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- row -->
                            <div class="row">
                                <div class="col-md-12">
                                    <!--<button class="btn btn-sm btn-info" ng-click="loadSubjects()">Search</button>-->
                                    <a class="btn btn-sm btn-primary text-white" href="<?php echo base_url() ?>settings/academic?tab=tab_subject_groups&class_id={{selecedVal}}&batch_id={{selecedVal2}}"><?php echo lang('search') ?></a>
                                </div>
                            </div>
                        </div>
                        <div ng-show="!myDiv"><?php echo $subject_groups; ?></div>
                        <div ng-bind-html="myDiv"></div>
                    </div>

                    <div class="tab-pane <?php if ($selected_tab === 'tab_assignsubjects') {
                        echo "active";
                    } ?>" id="assignsubjects" ng-controller="asController">


                    <!-- Modal --> 
                    <div class="modal fade" id="exampleModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <form class="form-material" name="assignSubjectForm" ng-submit="saveSubjectAssignments(assignSubjectForm.$valid)" novalidate="">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                        <h5 class="modal-title text-white" id="exampleModalLabel"><?php echo lang("lbl_teachers"); ?></h5>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label for="teacher">Teacher List</label>
                                            <select class="form-control yasir-assignsubjects-select2" style="width: 100%;" ng-model="selectedSubjectsToAssign">
                                                <option value="0">--<?php echo lang('lbl_none') ?>--</option>
                                                <option ng-repeat="t in teachers" value="{{t.id}}">{{t.name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="panel-footer text-right">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo lang("modal_btn_close"); ?></button>
                                        <button type="submit" class="btn btn-primary"><?php echo lang("modal_btn_update"); ?></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                      </div>
                    </div>
                    <!-- Modal -->


                    <div class="hint"><?php echo lang('help_assign_subjects'); ?></div>
                    <div class="well" style="background:#e4e7ea;" id="assignteacherfilterContainer">
                        <form name="filterForm" novalidate="" ng-submit="onSubmitFetchSubAndThr(filterForm.$valid)">    
                            <div class="row">
                                <div class="col-md-6" id="assignteacherClasses">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                        <select class="form-control" id="classes" ng-model="selecedVal11" ng-init="fetchClasses();selecedVal11=''" ng-change="loadClassBatches(selecedVal11)" required="">
                                            <option value="">--<?php echo lang('select_course') ?>--</option>
                                            <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6" id="assignteacherBatches">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('lbl_batches') ?></label>
                                        <select class="form-control" ng-model="selecedVal22" ng-init="selecedVal22=''" id="batches" required="">
                                            <option value="">--<?php echo lang('select_batch') ?>--</option>
                                            <option ng-repeat="batch in batches" value="{{batch.id}}">{{batch.name}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- row -->
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-sm btn-primary text-white"><?php echo lang('search') ?></button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="row" ng-show="subjects.length>0">
                        <div class="col-md-12">
                                <div class="white-box">
                                    <div class="row" style="width: 80%; margin-left: 10%;">
                                        <table class="table table-bordered table-striped table-hover">
                                            <tr>
                                                <th><?php echo lang('lbl_subject') ?></th>
                                                <th class="text-center" style="width: 50%;"><?php echo lang('lbl_teacher') ?></th>
                                            </tr>
                                            <tr ng-repeat="sub in subjects">
                                                <td><b>{{ sub.name }}</b></td>
                                                <td class="text-center">
                                                    <div style="display: inline-block; margin-right: 15px;">
                                                        <img ng-if="sub.teacher_avatar" src="uploads/user/{{sub.teacher_avatar}}" title="{{sub.teacher_name}}" data-toggle="modal" data-target="#exampleModal" ng-click="setSelectedOptionValue(1, sub.subject_id_orginal,sub.teacher_id)" style="width:40px; border: 1px solid;" class="img-circle">
                                                        <img ng-if="!sub.teacher_avatar" src="uploads/no-image.png" title="No teacher selected." data-toggle="modal" data-target="#exampleModal" ng-click="setSelectedOptionValue(1, sub.subject_id_orginal,sub.teacher_id)" style="width:40px; border: 1px solid;" class="img-circle">
                                                        <br/><small class="badge badge-pill badge-info" style="font-size:10px;"><?php echo lang("lbl_teacher"); ?></small>
                                                        <br/><small class="badge badge-pill badge-info" style="font-size:10px;">{{sub.teacher_name}}</small>
                                                    </div>
                                                    <div style="display: inline-block;">
                                                        <img ng-if="sub.assistant_avatar" src="uploads/user/{{sub.assistant_avatar}}" title="{{sub.assistant_name}}" data-toggle="modal" data-target="#exampleModal" ng-click="setSelectedOptionValue(2, sub.subject_id_orginal,sub.assistant_id)" style="width:40px; border: 1px solid;"  class="img-circle">
                                                        <img ng-if="!sub.assistant_avatar" src="uploads/no-image.png" title="No teacher selected." data-toggle="modal" data-target="#exampleModal" ng-click="setSelectedOptionValue(2, sub.subject_id_orginal,sub.assistant_id)" style="width:40px; border: 1px solid;"  class="img-circle">
                                                        <br/><small class="badge badge-pill badge-warning" style="font-size:10px;"><?php echo lang("lbl_assistant"); ?></small>
                                                        <br/><small class="badge badge-pill badge-warning" style="font-size:10px;">{{sub.assistant_name}}</small>
                                                    </div>
                                                    
                                                    <!--<div class="form-group">
                                                        <div ng-repeat="al in alreadyExists">
                                                            <small ng-if="al.subject_id == sub.id" class="text-info"><?php //echo lang('assigned_to') ?>{{al.thr_name}}</small>
                                                        </div>
                                                        <select name="users" ng-options="user.name for user in teachers" class="form-control" ng-change="selectedValues(userselected,sub.id)" ng-model="userselected[sub.id]">
                                                            <option value="">--<?php //echo lang('select_teacher') ?>--</option>
                                                        </select>
                                                    </div>-->
                                                </td>
                                            </tr>

                                            <!--<tr>
                                                <td colspan="2">
                                                    <button type="submit" class="btn btn-primary"><?php //echo lang('lbl_save') ?></button>
                                                </td>
                                            </tr>-->
                                        </table>

                                    </div>

                                </div>

                        </div>
                    </div>
                    <br/>
                    <!--<div class="row" ng-if="subjects.length === 0">
                        <div class="col-md-12 text-danger"><?php //echo lang("lbl_no_subject_found"); ?></div>
                    </div>-->
                    
                    <div class="row" ng-if="error.message">
                        <div class="col-md-12 text-danger">{{error.message}}</div>
                    </div>
                </div>

                <div class="tab-pane <?php if ($selected_tab === 'tab_periods') {
                    echo "active";
                } ?>" id="periods" ng-controller="periodController">
                <div class="hint"><?php echo lang('help_periods'); ?></div>
                <div class="well" style="background:#e4e7ea;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                <select class="form-control" id="classes" ng-model="selecedVal" ng-init="selecedVal='<?= $tab_periods_selected_class_id; ?>';fetchClasses(selecedVal)" ng-change="loadClassBatches(selecedVal)">
                                    <option value="all"><?php echo lang('option_all') ?></option>
                                    <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" id="yasir_batches">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('lbl_batches') ?></label>
                                <select class="form-control" ng-model="selecedVal2" id="batches" ng-init="selecedVal2='<?= $tab_periods_selected_batch_id; ?>'">
                                    <option value="all"><?php echo lang('option_all') ?></option>
                                    <option ng-repeat="batch in batches" value="{{batch.id}}">{{batch.name}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- row -->
                    <div class="row">
                        <div class="col-md-12">
                            <a class="btn btn-sm btn-primary text-white" href="<?php echo base_url() ?>settings/academic?tab=tab_periods&class_id={{selecedVal}}&batch_id={{selecedVal2}}"><?php echo lang('search') ?></a>
                            <!--<button class="btn btn-sm btn-info" ng-click="loadPeriods()">Search</button>-->
                        </div>
                    </div>
                </div>
                <div ng-show="!myDiv"><?= $periods; ?></div>
                <div ng-bind-html="myDiv"></div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
<!--page content end here-->
</div>
</div>
<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>
