<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<div>
    <!-- Page Content -->
    <div id="page-wrapper" ng-controller="onlineExamsController" ng-init="initExamsForStudent();">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?php echo lang('lbl_online_exam');?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="#"><?= lang('crumb_dashboard') ?></a></li>
                        <li><a href="#">Online Exams</a></li>
                        <li class="active">Student</li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <div class="hint"><?php echo lang ('help_online_exams_student');?></div>
            <!-- Page Content start here -->
            <!--.row-->
            <div class="row">
                <?php if($this->session->flashdata("success_message") != null) { ?>
                    <div class="col-md-12">
                        <div class="alert alert-success"><?php echo $this->session->flashdata("success_message"); ?></div>
                    </div>
                <?php } ?>

                <?php if($this->session->flashdata("published_message") != null) { ?>
                    <div class="col-md-12">
                        <div class="alert alert-danger"><?php echo $this->session->flashdata("published_message"); ?></div>
                    </div>
                <?php } ?>

                <?php if($this->session->flashdata("submitted_message") != null) { ?>
                    <div class="col-md-12">
                        <div class="alert alert-danger"><?php echo $this->session->flashdata("submitted_message"); ?></div>
                    </div>
                <?php } ?>

                <?php if($this->session->flashdata("paper_message") != null) { ?>
                    <div class="col-md-12">
                        <div class="alert alert-danger"><?php echo $this->session->flashdata("paper_message"); ?></div>
                    </div>
                <?php } ?>
                
                <div class="col-md-12">
                    <div class="white-box">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <tr style="background-color: #e3e3e3;">
                                    <th class="text-center"><span class="text-muted">#</span></th>
                                    <th><?php echo lang('lbl_subject');?> /<?php echo lang('lbl_paper');?></th>
                                    <th><?php echo lang('exam_type');?></th>
                                    <th><?php echo lang('start_date');?></th>
                                    <th><?php echo lang('end_date');?></th>
                                    <th><?php echo lang('number_of_questions');?></th>
                                    <th><?php echo lang('duration');?></th>
                                    <th><?php echo lang('lbl_attempts');?></th>
                                    <th><?php echo lang('lbl_status');?></th>
                                    <th class="text-center"><?php echo lang('lbl_tbl_action');?></th>
                                </tr>
                                <?php if(count($exams) > 0) {
                                   $index=1;
                                   foreach($exams as $e) { ?>
                                    <tr class="text-center">
                                        <td class="text-center"><span class="text-muted"><?php echo $index++; ?></span></td>
                                        <td><?php echo $e->subject_name ." / ". $e->paper_name;  ?></td>
                                        <td><?php echo $e->title;  ?></td>
                                        <td><?php echo $e->start_date;  ?></td>
                                        <td><?php echo $e->end_date;  ?></td>
                                        <td><?php echo $e->number_of_questions;  ?></td>
                                        <td><?php echo $e->duration_in_minutes;  ?></td>
                                        <td><?php echo $e->attempts;  ?></td>
                                        <td>
                                            <?php if(date("Y-m-d") >= $e->start_date && date("Y-m-d") <= $e->end_date) { ?>
                                                <span class="text-success"><?php echo lang('open');?></span>
                                                <!-- <img src="assets/images/new-icon-gif-3.jpg" height="15px" /> -->
                                            <?php } else { ?>
                                                <span class="text-danger"><?php echo lang('modal_btn_close');?></span>
                                            <?php } ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if(!(date("Y-m-d") >= $e->start_date && date("Y-m-d") <= $e->end_date)){ if($e->exam_submited) { ?>
                                                <span class="text-success"><?php echo lang ('exam_submitted');?></span>
                                            <?php }else {  ?>
                                                <span class="text-danger">Exam not submitted</span>
                                            <?php }}else if(!$e->exam_submited) { ?>
                                                <a href="online_exams/start_exam/<?php echo $e->exam_id; ?>/<?php echo $e->id; ?>" class="btn btn-sm btn-primary"><?php echo lang('start_exam');?></a>
                                            <?php } else { ?>
                                                <span class="text-success"><?php echo lang ('exam_submitted');?></span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } } else { ?>
                                    <tr>
                                        <td colspan="9"><span class="text-danger">No online exam found!</span></td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                </div>
                
            <!--./row-->
            <!--page content end here-->
        </div>
    </div>
    <!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>