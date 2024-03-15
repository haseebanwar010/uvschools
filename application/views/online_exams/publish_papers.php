<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<div>

    <?php
        $role_id = $this->session->userdata('userdata')['role_id'];
        if($role_id == '4' && get_teacher_dept_id() ==  login_user()->user->teacher_dept_id){
            $ci = & get_instance();
            $arr = $ci->session->userdata("userdata")['persissions'];

            $array = json_decode($arr);
            if (isset($array)) {
                $publish_papers = 0;
                foreach ($array as $key => $value) {
                    if (in_array('online_exams-publish_papers', array($value->permission)) && $value->val == 'true') {
                        $publish_papers = '1';
                    }
                }
            }
        }
        ?>
    <!-- Page Content -->
    <div id="page-wrapper" ng-controller="onlineExamsController">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?php echo lang('publish_paper');?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="#"><?= lang('crumb_dashboard') ?></a></li>
                        <li><a href="#"><?php echo lang('lbl_online_exam');?></a></li>
                        <li class="active"><?php echo lang('publish_paper');?></li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <div class="hint"><?php echo lang('help_online_exams_publish_papers');?></div>
            <!-- Page Content start here -->
            <!--.row-->
            <div class="row">
                
                <div class="col-md-12">
                    <form name="publishModelForm" class="form-material" id="filterForm" ng-submit="getPapersForPublish(publishModelForm.$valid)" novalidate>
                        <div class="white-box">
                            <div class="row">
                                <div class="col-md-6" id="marksFilterAcademicYears">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang("lbl_academic_year"); ?></label>
                                        <select class="form-control" name="academic_year_id" ng-model="publishModel.academic_year_id" required="" ng-init="initAcademicYearsForPublish()" ng-change="initClasses(publishModel.academic_year_id)">
                                            <option value="-1"><?php echo lang("lbl_select_academic_year"); ?></option>
                                            <option ng-repeat="ay in academicyears" value="{{ ay.id }}">{{ ay.name }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6" id="marksFilterClasses">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                        <select class="form-control" ng-model="publishModel.class_id" required="">
                                            <option value=""><?php echo lang('select_course') ?></option>
                                            <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-sm btn-primary" type="submit"><?php echo lang('search') ?></button>
                                </div>
                            </div>    
                        </div>
                    </form>
                </div>

                
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12 white-box" ng-if="papers_to_publish.length > 0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover text-center">
                                    <tr>
                                        <th class="text-center"><?php echo lang('paper_name') ?></th>
                                        <th class="text-center"><?php echo lang('lbl_class') ?></th>
                                        <th class="text-center"><?php echo lang('lbl_batches') ?></th>
                                        <th class="text-center"><?php echo lang('lbl_subject') ?></th>
                                        <th class="text-center"><?php echo lang('total_question_for_exam') ?></th>
                                        <th class="text-center"><?php echo lang('Added_Questions') ?></th>
                                        <th class="text-center"><?php echo lang('lbl_status') ?></th>
                                        <th class="text-center"><?php echo lang('lbl_tbl_action') ?></th>
                                    </tr>
                                    <tr ng-repeat="p in papers_to_publish">
                                        <td><b>{{p.paper_name}}</b></td>
                                        <td>{{p.class_name}}</td>
                                        <td>{{p.batch_name}}</td>
                                        <td>{{p.subject_name}}</td>
                                        <td>{{p.number_of_questions}}</td>
                                        <td>{{p.added_questions}}</td>
                                        <td>
                                            <span ng-if="!p.ready"><button class="btn btn-rounded btn-outline-danger" disabled=""><?php echo lang('not_ready') ?></button></span>
                                            <span ng-if="p.ready"><button class="btn btn-rounded btn-outline-success" disabled=""><?php echo lang('lbl_ready') ?></button></span>
                                        </td>
                                        <td>
                                        <?php if($role_id =='4' && (isset($publish_papers) && $publish_papers == '1')) { ?>
                                            <button class="btn btn-success btn-rounded" ng-if="p.ready && p.published == 'no'" ng-click="publishPaper(p.id, 'yes')"><?php echo lang('published_for_exam') ?></button>
                                            <button class="btn btn-danger btn-rounded" ng-if="p.ready && p.published == 'yes'" ng-click="publishPaper(p.id, 'no')"><?php echo lang('unpublish_for_exam') ?></button>
                                        <?php } if($role_id == '1') { ?>
                                             <button class="btn btn-success btn-rounded" ng-if="p.ready && p.published == 'no'" ng-click="publishPaper(p.id, 'yes')"><?php echo lang('published_for_exam') ?></button>
                                            <button class="btn btn-danger btn-rounded" ng-if="p.ready && p.published == 'yes'" ng-click="publishPaper(p.id, 'no')"><?php echo lang('unpublish_for_exam') ?></button>
                                        <?php } ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12 white-box" ng-if="papers_to_publish.length == 0">
                            <p class="text-danger"><?php echo lang('no_paper_found') ?></p>
                        </div>
                    </div>
                </div>
                  
                
                
            <!--./row-->
            <!--page content end here-->
        </div>
    </div>
    <!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>