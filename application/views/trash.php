<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?= lang("lbl_trash"); ?></h4>
            </div>
            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-4">
                <ol class="breadcrumb">
                    <li><a href="dashboard/"><?= lang("lbl_dashboard"); ?></a></li>
                    <li class="active"><?= lang("lbl_dashboard"); ?></li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>


        <?php if ($this->session->flashdata('recover_message') != null) { ?>
            <div class="alert alert-info">
                <?php echo $this->session->flashdata("recover_message"); ?>
            </div>
        <?php } ?>

        <div class="hint"><?php echo lang('lbl_trash_helping'); ?></div>
        <?php //if (count($academic_years) > 0 || count($academic_years) > 0 || count($class_levels) > 0 || count($classes) > 0 || count($batches) > 0 || count($subjects) > 0 || count($subject_groups) > 0 || count($periods) > 0 || count($grades) > 0 || count($fee_types) > 0 || count($fee_discount) > 0 || count($employees) > 0 || count($students) > 0 || count($guardians) > 0 || count($study_materials) > 0 || count($book_shops) > 0 || count($form_categories) > 0 || count($forms) > 0 || count($exams) > 0 || count($exam_details) > 0 || count($passing_rules) > 0 || count($syllabus_weeks) > 0 || count($syllabus_week_details) > 0 || count($fee_collections) > 0) { ?>
            <!--<div class="col-md-12 well">
                <div class="card-title pull-left">
                    <h4 class="p-l-10 m-b-0"><b>All deleted items</b></h4>
                </div>
                <div class="btn-toolbar mb-1 pull-right" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="btn-group mr-2" role="group" aria-label="First group">
                        <a href="trash?type=list" class="btn btn-secondary" title="List view"><i class="ti-list"></i></a>
                        <a href="trash?type=grid" class="btn btn-secondary" title="Grid view"><i class="ti-layout-grid4"></i></a>
                    </div>
                </div>
            </div>-->
        <?php //} ?>
        <!-- permissions -->
        <?php
        if ($this->session->userdata("userdata")["role_id"] == EMPLOYEE_ROLE_ID) {
            $ci = & get_instance();
            $arr = $ci->session->userdata("userdata")['persissions'];
            $array = json_decode($arr);
            if (isset($array)) {
                $recover = $delete = 0;
                foreach ($array as $key => $value) {
                    if (in_array('trash-recover', array($value->permission)) && $value->val == 'true') {
                        $recover = 1;
                    }
                    if (in_array('trash-delete', array($value->permission)) && $value->val == 'true') {
                        $delete = 1;
                    }
                }
echo $recover;
echo "string";
echo $delete;
            }
        }
        ?>
        <div class="row">
            <div class="col-md-12">
                <?php if (count($academic_years) == 0 && count($academic_years) == 0 && count($class_levels) == 0 && count($classes) == 0 && count($batches) == 0 && count($subjects) == 0 && count($subject_groups) == 0 && count($periods) == 0 && count($grades) == 0 && count($fee_types) == 0 && count($fee_discount) == 0 && count($employees) == 0 && count($students) == 0 && count($guardians) == 0 && count($study_materials) == 0 && count($book_shops) == 0 && count($form_categories) == 0 && count($forms) == 0 && count($exams) == 0 && count($exam_details) == 0 && count($passing_rules) == 0 && count($syllabus_weeks) == 0 && count($syllabus_week_details) == 0 && count($fee_collections) == 0) { ?>
                    <div class="white-box">
                        <span class="text-danger">Trash is empty!</span>
                    </div>
                <?php } ?>
                
                <?php if($view_type == 'list') { ?>
                
                    <?php if (count($academic_years) > 0) { ?>
                        <div class="white-box">
                            <div class="col-md-6">
                                <h3 class="text-danger"><i class="fa fa-trash"></i> <?php echo lang('academic_years') ?></h3>
                            </div>
                            <div class="col-md-6 text-right" style="padding-top:10px">
                            <?php if($role_id == '1' || ($role_id == '4' && ($recover == '1' || $delete == '1'))){?>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo lang('actions') ?>
                                    </button>
                                    <div class="dropdown-menu">
                                      <h6 class="dropdown-header"><?php echo lang('filter_actions') ?></h6>
                                    <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>  
                                      <a class="dropdown-item sa-warning" href="javascript:void(0);" value="academic_years,trash/delete_all"><?php echo lang('delete_all') ?></a>
                                    <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>  
                                      <a class="dropdown-item sa-trash-recover-all" href="javascript:void(0);" value="trash/recover_all,academic_years"><?php echo lang('recover_all') ?></a>
                                    <?php } ?>  
                                    </div>
                                </div> 
                            <?php } ?>
                            </div>
                            <div class="table-responsive" style="max-height: 350px; overflow: auto;">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <th style="width: 50px; text-align: center"><?php echo lang('lbl_sr') ?></th>
                                        <th><?php echo lang('subject_name_title') ?></th>
                                        <th style="width: 200px; text-align: center"><?php echo lang('datetime') ?></th>
                                        <th style="width: 100px; text-align: center"><?php echo lang('th_action') ?></th>
                                    </tr>
                                        <?php $index = 1; foreach ($academic_years as $ay) { ?>
                                        <tr>
                                            <td style="width: 50px; text-align: center">
                                                <?php echo $index++; ?>
                                            </td>
                                            <td><?php echo $ay->name; ?></td>
                                            <td class="text-center"><?php echo $ay->deleted_at; ?></td>
                                            <td style="width: 100px; text-align: center">
                                            <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>
                                                <a href="javascript:void(0);" class="btn btn-danger btn-circle text-white sa-trash-delete-single" value="trash/delete,academic_years,<?php echo $ay->id; ?>"><i class="fa fa-trash"></i></a>
                                            <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>
                                                <a href="javascript:void(0);" class="btn btn-info btn-circle text-white sa-trash-recover-single" value="trash/recover,academic_years,<?php echo $ay->id; ?>"><i class="fa fa-recycle"></i></a>
                                            <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>  
                    <?php } ?>

                    <?php if (count($class_levels) > 0) { ?>
                        <div class="white-box">
                            <div class="col-md-6">
                                <h3 class="text-danger"><i class="fa fa-trash"></i> <?php echo lang('class_levels') ?></h3>
                            </div>
                            <div class="col-md-6 text-right" style="padding-top:10px">
                            <?php if($role_id == '1' || ($role_id == '4' && ($recover == '1' || $delete == '1'))){?>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo lang('actions') ?>
                                    </button>
                                    <div class="dropdown-menu">
                                      <h6 class="dropdown-header"><?php echo lang('filter_actions') ?></h6>
                                    <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>
                                      <a class="dropdown-item sa-warning" href="javascript:void(0);" value="class_levels,trash/delete_all"><?php echo lang('delete_all') ?></a>
                                    <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>
                                      <a class="dropdown-item sa-trash-recover-all" href="javascript:void(0);" value="trash/recover_all,class_levels"><?php echo lang('recover_all') ?></a>
                                    <?php } ?>  
                                    </div>
                                </div>
                            <?php } ?>     
                            </div>
                            <div class="table-responsive" style="max-height: 350px; overflow: auto;">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <th style="width: 50px; text-align: center"><?php echo lang('lbl_sr') ?></th>
                                        <th><?php echo lang('subject_name_title') ?></th>
                                        <th style="width: 200px; text-align: center"><?php echo lang('datetime') ?></th>
                                        <th style="width: 100px; text-align: center"><?php echo lang('th_action') ?></th>
                                    </tr>
                                        <?php $index1 = 1; foreach ($class_levels as $cl) { ?>
                                        <tr>
                                            <td style="width: 50px; text-align: center">
                                                <?php echo $index1++; ?>
                                            </td>
                                            <td><?php echo $cl->name; ?></td>
                                            <td class="text-center"><?php echo $cl->deleted_at; ?></td>
                                            <td style="width: 100px; text-align: center">
                                            <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>    
                                                <a href="javascript:void(0);" class="btn btn-danger btn-circle text-white sa-trash-delete-single" value="trash/delete,class_levels,<?php echo $cl->id; ?>"><i class="fa fa-trash"></i></a>
                                            <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                                <a href="javascript:void(0);" class="btn btn-info btn-circle text-white sa-trash-recover-single" value="trash/recover,class_levels,<?php echo $cl->id; ?>"><i class="fa fa-recycle"></i></a>
                                            <?php } ?>    
                                                <!--<a href="<?php echo base_url(); ?>trash/recover/class_levels/<?php echo $cl->id; ?>" class="btn btn-danger btn-circle text-white"><i class="fa fa-recycle"></i></a>-->
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (count($classes) > 0) { ?>
                        <div class="white-box">
                            <div class="col-md-6">
                                <h3 class="text-danger"><i class="fa fa-trash"></i> <?php echo lang('lbl_classes') ?></h3>
                            </div>
                            <div class="col-md-6 text-right" style="padding-top:10px">
                            <?php if($role_id == '1' || ($role_id == '4' && ($recover == '1' || $delete == '1'))){?>    
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo lang('actions') ?>
                                    </button>
                                    <div class="dropdown-menu">
                                      <h6 class="dropdown-header"><?php echo lang('filter_actions') ?></h6>
                                    <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>  
                                      <a class="dropdown-item sa-warning" href="javascript:void(0);" value="classes,trash/delete_all"><?php echo lang('delete_all') ?></a>
                                    <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>  
                                      <a class="dropdown-item sa-trash-recover-all" href="javascript:void(0);" value="trash/recover_all,classes"><?php echo lang('recover_all') ?></a>
                                    <?php } ?>    
                                    </div>
                                </div> 
                            <?php } ?>      
                            </div>
                            <div class="table-responsive" style="max-height: 350px; overflow: auto;">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <th style="width: 50px; text-align: center"><?php echo lang('lbl_sr') ?></th>
                                        <th><?php echo lang('subject_name_title') ?></th>
                                        <th style="width: 200px; text-align: center"><?php echo lang('datetime') ?></th>
                                        <th style="width: 100px; text-align: center"><?php echo lang('th_action') ?></th>
                                    </tr>
                                    <?php $index2 = 1;
                                    foreach ($classes as $cc) {
                                        ?>
                                        <tr>
                                            <td style="width: 50px; text-align: center">
                                                <?php echo $index2++; ?>
                                            </td>
                                            <td><?php echo $cc->name; ?></td>
                                            <td class="text-center"><?php echo $cc->deleted_at; ?></td>
                                            <td style="width: 100px; text-align: center">
                                            <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>    
                                                <a href="javascript:void(0);" class="btn btn-danger btn-circle text-white sa-trash-delete-single" value="trash/delete,classes,<?php echo $cc->id; ?>"><i class="fa fa-trash"></i></a>
                                            <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                                <a href="javascript:void(0);" class="btn btn-info btn-circle text-white sa-trash-recover-single" value="trash/recover,classes,<?php echo $cc->id; ?>"><i class="fa fa-recycle"></i></a>
                                            <?php } ?>    
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (count($batches) > 0) { ?>
                        <div class="white-box">
                            <div class="col-md-6">
                                <h3 class="text-danger"><i class="fa fa-trash"></i> <?php echo lang('lbl_batches') ?></h3>
                            </div>
                            <div class="col-md-6 text-right" style="padding-top:10px">
                            <?php if($role_id == '1' || ($role_id == '4' && ($recover == '1' || $delete == '1'))){?>    
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo lang('actions') ?>
                                    </button>
                                    <div class="dropdown-menu">
                                      <h6 class="dropdown-header"><?php echo lang('filter_actions') ?></h6>
                                    <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>  
                                      <a class="dropdown-item sa-warning" href="javascript:void(0);" value="batches,trash/delete_all"><?php echo lang('delete_all') ?></a>
                                    <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>  
                                      <a class="dropdown-item sa-trash-recover-all" href="javascript:void(0);" value="trash/recover_all,batches"><?php echo lang('recover_all') ?></a>
                                    <?php } ?>  
                                    </div>
                                </div>
                            <?php } ?>     
                            </div>
                            <div class="table-responsive" style="max-height: 350px; overflow: auto;">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <th style="width: 50px; text-align: center"><?php echo lang('lbl_sr') ?></th>
                                        <th><?php echo lang('subject_name_title') ?></th>
                                        <th style="width: 200px; text-align: center"><?php echo lang('datetime') ?></th>
                                        <th style="width: 100px; text-align: center"><?php echo lang('th_action') ?></th>
                                    </tr>
                                    <?php $index3 = 1;
                                    foreach ($batches as $bb) {
                                        ?>
                                        <tr>
                                            <td style="width: 50px; text-align: center">
                                                <?php echo $index3++; ?>
                                            </td>
                                            <td><?php echo $bb->name; ?></td>
                                            <td class="text-center"><?php echo $bb->deleted_at; ?></td>
                                            <td style="width: 100px; text-align: center">
                                            <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>    
                                                <a href="javascript:void(0);" class="btn btn-danger btn-circle text-white sa-trash-delete-single" value="trash/delete,batches,<?php echo $bb->id; ?>"><i class="fa fa-trash"></i></a>
                                            <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                                <a href="javascript:void(0);" class="btn btn-info btn-circle text-white sa-trash-recover-single" value="trash/recover,batches,<?php echo $bb->id; ?>"><i class="fa fa-recycle"></i></a>
                                            <?php } ?>    
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (count($subjects) > 0) { ?>
                        <div class="white-box">
                            <div class="col-md-6">
                                <h3 class="text-danger"><i class="fa fa-trash"></i> <?php echo lang('lbl_subjects') ?></h3>
                            </div>
                            <div class="col-md-6 text-right" style="padding-top:10px">
                            <?php if($role_id == '1' || ($role_id == '4' && ($recover == '1' || $delete == '1'))){?>    
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo lang('actions') ?>
                                    </button>
                                    <div class="dropdown-menu">
                                      <h6 class="dropdown-header"><?php echo lang('filter_actions') ?></h6>
                                    <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>  
                                      <a class="dropdown-item sa-warning" href="javascript:void(0);" value="subjects,trash/delete_all"><?php echo lang('delete_all') ?></a>
                                    <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>  
                                      <a class="dropdown-item sa-trash-recover-all" href="javascript:void(0);" value="trash/recover_all,subjects"><?php echo lang('recover_all') ?></a>
                                    <?php } ?>  
                                    </div>
                                </div>
                            <?php } ?>     
                            </div>
                            <div class="table-responsive" style="max-height: 350px; overflow: auto;">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <th style="width: 50px; text-align: center"><?php echo lang('lbl_sr') ?></th>
                                        <th><?php echo lang('subject_name_title') ?></th>
                                        <th style="width: 200px; text-align: center"><?php echo lang('datetime') ?></th>
                                        <th style="width: 100px; text-align: center"><?php echo lang('th_action') ?></th>
                                    </tr>
                                    <?php $index4 = 1;
                                    foreach ($subjects as $sub) {
                                        ?>
                                        <tr>
                                            <td style="width: 50px; text-align: center">
                                                <?php echo $index4++; ?>
                                            </td>
                                            <td><?php echo $sub->name; ?></td>
                                            <td class="text-center"><?php echo $sub->deleted_at; ?></td>
                                            <td style="width: 100px; text-align: center">
                                            <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>    
                                                <a href="javascript:void(0);" class="btn btn-danger btn-circle text-white sa-trash-delete-single" value="trash/delete,subjects,<?php echo $sub->id; ?>"><i class="fa fa-trash"></i></a>
                                            <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                                <a href="javascript:void(0);" class="btn btn-info btn-circle text-white sa-trash-recover-single" value="trash/recover,subjects,<?php echo $sub->id; ?>"><i class="fa fa-recycle"></i></a>
                                            <?php } ?>    
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (count($subject_groups) > 0) { ?>
                        <div class="white-box">
                            <div class="col-md-6">
                                <h3 class="text-danger"><i class="fa fa-trash"></i> <?php echo lang('subject_groups') ?></h3>
                            </div>
                            <div class="col-md-6 text-right" style="padding-top:10px">
                            <?php if($role_id == '1' || ($role_id == '4' && ($recover == '1' || $delete == '1'))){?>    
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo lang('actions') ?>
                                    </button>
                                    <div class="dropdown-menu">
                                        <h6 class="dropdown-header"><?php echo lang('filter_actions') ?></h6>
                                    <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>  
                                        <a class="dropdown-item sa-warning" href="javascript:void(0);" value="subject_groups,trash/delete_all"><?php echo lang('delete_all') ?></a>
                                    <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>     
                                        <a class="dropdown-item sa-trash-recover-all" href="javascript:void(0);" value="trash/recover_all,subject_groups"><?php echo lang('recover_all') ?></a>
                                    <?php } ?>    
                                    </div>
                                </div>
                            <?php } ?>     
                            </div>
                            <div class="table-responsive" style="max-height: 350px; overflow: auto;">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <th style="width: 50px; text-align: center"><?php echo lang('lbl_sr') ?></th>
                                        <th><?php echo lang('subject_name_title') ?></th>
                                        <th style="width: 200px; text-align: center"><?php echo lang('datetime') ?></th>
                                        <th style="width: 100px; text-align: center"><?php echo lang('th_action') ?></th>
                                    </tr>
                                    <?php $index5 = 1; foreach ($subject_groups as $subg) { ?>
                                        <tr>
                                            <td style="width: 50px; text-align: center">
                                            <?php echo $index5++; ?>
                                            </td>
                                            <td><?php echo $subg->name; ?></td>
                                            <td class="text-center"><?php echo $subg->deleted_at; ?></td>
                                            <td style="width: 100px; text-align: center">
                                            <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>    
                                                <a href="javascript:void(0);" class="btn btn-danger btn-circle text-white sa-trash-delete-single" value="trash/delete,subject_groups,<?php echo $subg->id; ?>"><i class="fa fa-trash"></i></a>
                                            <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                                <a href="javascript:void(0);" class="btn btn-info btn-circle text-white sa-trash-recover-single" value="trash/recover,subject_groups,<?php echo $subg->id; ?>"><i class="fa fa-recycle"></i></a>
                                            <?php } ?>    
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (count($periods) > 0) { ?>
                        <div class="white-box">
                            <div class="col-md-6">
                                <h3 class="text-danger"><i class="fa fa-trash"></i> <?php echo lang('tab_periods') ?></h3>
                            </div>
                            <div class="col-md-6 text-right" style="padding-top:10px">
                            <?php if($role_id == '1' || ($role_id == '4' && ($recover == '1' || $delete == '1'))){?>    
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo lang('actions') ?>
                                    </button>
                                    <div class="dropdown-menu">
                                        <h6 class="dropdown-header"><?php echo lang('filter_actions') ?></h6>
                                    <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>  
                                        <a class="dropdown-item sa-warning" href="javascript:void(0);" value="periods,trash/delete_all"><?php echo lang('delete_all') ?></a>
                                    <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                        <a class="dropdown-item sa-trash-recover-all" href="javascript:void(0);" value="trash/recover_all,periods"><?php echo lang('recover_all') ?></a>
                                    <?php } ?>    
                                    </div>
                                </div>
                            <?php } ?>     
                            </div>
                            <div class="table-responsive" style="max-height: 350px; overflow: auto;">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <th style="width: 50px; text-align: center"><?php echo lang('lbl_sr') ?></th>
                                        <th><?php echo lang('subject_name_title') ?></th>
                                        <th style="width: 200px; text-align: center"><?php echo lang('datetime') ?></th>
                                        <th style="width: 100px; text-align: center"><?php echo lang('th_action') ?></th>
                                    </tr>
                                    <?php $index6 = 1; foreach ($periods as $per) { ?>
                                        <tr>
                                            <td style="width: 50px; text-align: center">
                                                <?php echo $index6++; ?>
                                            </td>
                                            <td><?php echo $per->name; ?></td>
                                            <td class="text-center"><?php echo $per->deleted_at; ?></td>
                                            <td style="width: 100px; text-align: center">
                                            <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>    
                                                <a href="javascript:void(0);" class="btn btn-danger btn-circle text-white sa-trash-delete-single" value="trash/delete,periods,<?php echo $per->id; ?>"><i class="fa fa-trash"></i></a>
                                            <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                                <a href="javascript:void(0);" class="btn btn-info btn-circle text-white sa-trash-recover-single" value="trash/recover,periods,<?php echo $per->id; ?>"><i class="fa fa-recycle"></i></a>
                                            <?php } ?>    
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (count($grades) > 0) { ?>
                        <div class="white-box">
                            <div class="col-md-6">
                                <h3 class="text-danger"><i class="fa fa-trash"></i> <?php echo lang('lbl_grades') ?></h3>
                            </div>
                            <div class="col-md-6 text-right" style="padding-top:10px">
                            <?php if($role_id == '1' || ($role_id == '4' && ($recover == '1' || $delete == '1'))){?>    
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo lang('actions') ?>
                                    </button>
                                    <div class="dropdown-menu">
                                      <h6 class="dropdown-header"><?php echo lang('filter_actions') ?></h6>
                                    <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>  
                                        <a class="dropdown-item sa-warning" href="javascript:void(0);" value="grades,trash/delete_all"><?php echo lang('delete_all') ?></a>
                                    <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>  
                                        <a class="dropdown-item sa-trash-recover-all" href="javascript:void(0);" value="trash/recover_all,grades"><?php echo lang('recover_all') ?></a>
                                    <?php } ?>    
                                    </div>
                                </div> 
                            <?php } ?>    
                            </div>
                            <div class="table-responsive" style="max-height: 350px; overflow: auto;">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <th style="width: 50px; text-align: center"><?php echo lang('lbl_sr') ?></th>
                                        <th><?php echo lang('subject_name_title') ?></th>
                                        <th style="width: 100px; text-align: center"><?php echo lang('th_action') ?></th>
                                    </tr>
                                    <?php $index7 = 1; foreach ($grades as $gar) { ?>
                                        <tr>
                                            <td style="width: 50px; text-align: center">
                                                <?php echo $index7++; ?>
                                            </td>
                                            <td><?php echo $gar->name; ?></td>
                                            <td style="width: 100px; text-align: center">
                                            <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>    
                                                <a href="javascript:void(0);" class="btn btn-danger btn-circle text-white sa-trash-delete-single" value="trash/delete,grades,<?php echo $gar->id; ?>"><i class="fa fa-trash"></i></a>
                                            <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                                <a href="javascript:void(0);" class="btn btn-info btn-circle text-white sa-trash-recover-single" value="trash/recover,grades,<?php echo $gar->id; ?>"><i class="fa fa-recycle"></i></a>
                                            <?php } ?>    
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (count($fee_types) > 0) { ?>
                        <div class="white-box">
                            <div class="col-md-6">
                                <h3 class="text-danger"><i class="fa fa-trash"></i> <?php echo lang('feeType') ?></h3>
                            </div>
                            <div class="col-md-6 text-right" style="padding-top:10px">
                            <?php if($role_id == '1' || ($role_id == '4' && ($recover == '1' || $delete == '1'))){?>    
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo lang('actions') ?>
                                    </button>
                                    <div class="dropdown-menu">
                                        <h6 class="dropdown-header"><?php echo lang('filter_actions') ?></h6>
                                    <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>   
                                        <a class="dropdown-item sa-warning" href="javascript:void(0);" value="fee_types,trash/delete_all"><?php echo lang('delete_all') ?></a>
                                    <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                        <a class="dropdown-item sa-trash-recover-all" href="javascript:void(0);" value="trash/recover_all,fee_types"><?php echo lang('recover_all') ?></a>
                                    <?php } ?>    
                                    </div>
                                </div> 
                            <?php } ?>    
                            </div>
                            <div class="table-responsive" style="max-height: 350px; overflow: auto;">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <th style="width: 50px; text-align: center"><?php echo lang('lbl_sr') ?></th>
                                        <th><?php echo lang('subject_name_title') ?></th>
                                        <th style="width: 200px; text-align: center"><?php echo lang('datetime') ?></th>
                                        <th style="width: 100px; text-align: center"><?php echo lang('th_action') ?></th>
                                    </tr>
                                    <?php $index8 = 1; foreach ($fee_types as $ft) { ?>
                                        <tr>
                                            <td style="width: 50px; text-align: center">
                                                <?php echo $index8++; ?>
                                            </td>
                                            <td><?php echo $ft->name; ?></td>
                                            <td class="text-center"><?php echo $ft->deleted_at; ?></td>
                                            <td style="width: 100px; text-align: center">
                                            <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>     
                                                <a href="javascript:void(0);" class="btn btn-danger btn-circle text-white sa-trash-delete-single" value="trash/delete,fee_types,<?php echo $ft->id; ?>"><i class="fa fa-trash"></i></a>
                                            <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                                <a href="javascript:void(0);" class="btn btn-info btn-circle text-white sa-trash-recover-single" value="trash/recover,fee_types,<?php echo $ft->id; ?>"><i class="fa fa-recycle"></i></a>
                                            <?php } ?>    
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (count($fee_discount) > 0) { ?>
                        <div class="white-box">
                            <div class="col-md-6">
                                <h3 class="text-danger"><i class="fa fa-trash"></i> <?php echo lang('fee_discounts') ?></h3>
                            </div>
                            <div class="col-md-6 text-right" style="padding-top:10px">
                            <?php if($role_id == '1' || ($role_id == '4' && ($recover == '1' || $delete == '1'))){?>    
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo lang('actions') ?>
                                    </button>
                                    <div class="dropdown-menu">
                                        <h6 class="dropdown-header"><?php echo lang('filter_actions') ?></h6>
                                    <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>  
                                        <a class="dropdown-item sa-warning" href="javascript:void(0);" value="fee_discount,trash/delete_all"><?php echo lang('delete_all') ?></a>
                                    <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                        <a class="dropdown-item sa-trash-recover-all" href="javascript:void(0);" value="trash/recover_all,fee_discount"><?php echo lang('recover_all') ?></a>
                                    <?php } ?>    
                                    </div>
                                </div> 
                            <?php } ?>    
                            </div>
                            <div class="table-responsive" style="max-height: 350px; overflow: auto;">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <th style="width: 50px; text-align: center"><?php echo lang('lbl_sr') ?></th>
                                        <th><?php echo lang('subject_name_title') ?></th>
                                        <th style="width: 200px; text-align: center"><?php echo lang('datetime') ?></th>
                                        <th style="width: 100px; text-align: center"><?php echo lang('th_action') ?></th>
                                    </tr>
                                    <?php $index9 = 1; foreach ($fee_discount as $fd) { ?>
                                        <tr>
                                            <td style="width: 50px; text-align: center">
                                                <?php echo $index9++; ?>
                                            </td>
                                            <td><?php echo $fd->name; ?></td>
                                            <td><?php echo $fd->deleted_at; ?></td>
                                            <td style="width: 100px; text-align: center">
                                            <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>    
                                                <a href="javascript:void(0);" class="btn btn-danger btn-circle text-white sa-trash-delete-single" value="trash/delete,fee_discount,<?php echo $fd->id; ?>"><i class="fa fa-trash"></i></a>
                                            <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                                <a href="javascript:void(0);" class="btn btn-info btn-circle text-white sa-trash-recover-single" value="trash/recover,fee_discount,<?php echo $fd->id; ?>"><i class="fa fa-recycle"></i></a>
                                            <?php } ?>    
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (count($employees) > 0) { ?>
                        <div class="white-box">
                            <div class="col-md-6">
                                <h3 class="text-danger"><i class="fa fa-trash"></i> <?php echo lang('employeees') ?></h3>
                            </div>
                            <div class="col-md-6 text-right" style="padding-top:10px">
                            <?php if($role_id == '1' || ($role_id == '4' && ($recover == '1' || $delete == '1'))){?>    
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo lang('actions') ?>
                                    </button>
                                    <div class="dropdown-menu">
                                        <h6 class="dropdown-header"><?php echo lang('filter_actions') ?></h6>
                                    <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>  
                                        <a class="dropdown-item sa-warning" href="javascript:void(0);" value="employees,trash/delete_all"><?php echo lang('delete_all') ?></a>
                                    <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                        <a class="dropdown-item sa-trash-recover-all" href="javascript:void(0);" value="trash/recover_all,employees"><?php echo lang('recover_all') ?></a>
                                    <?php } ?>    
                                    </div>
                                </div> 
                            <?php } ?>
                            </div>
                            <div class="table-responsive" style="max-height: 350px; overflow: auto;">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <th style="width: 50px; text-align: center"><?php echo lang('lbl_sr') ?></th>
                                        <th><?php echo lang('subject_name_title') ?></th>
                                        <th style="width: 200px; text-align: center"><?php echo lang('datetime') ?></th>
                                        <th style="width: 100px; text-align: center"><?php echo lang('th_action') ?></th>
                                    </tr>
                                    <?php $index10 = 1; foreach ($employees as $emp) { ?>
                                        <tr>
                                            <td style="width: 50px; text-align: center">
                                                <?php echo $index10++; ?>
                                            </td>
                                            <td><?php echo $emp->name; ?></td>
                                            <td class="text-center"><?php echo $emp->deleted_at; ?></td>
                                            <td style="width: 100px; text-align: center">
                                            <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>    
                                                <a href="javascript:void(0);" class="btn btn-danger btn-circle text-white sa-trash-delete-single" value="trash/delete,users,<?php echo $emp->id; ?>"><i class="fa fa-trash"></i></a>
                                            <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                                <a href="javascript:void(0);" class="btn btn-info btn-circle text-white sa-trash-recover-single" value="trash/recover,users,<?php echo $emp->id; ?>"><i class="fa fa-recycle"></i></a>
                                            <?php } ?>    
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (count($students) > 0) { ?>
                        <div class="white-box">
                            <div class="col-md-6">
                                <h3 class="text-danger"><i class="fa fa-trash"></i> <?php echo lang('menu_students') ?></h3>
                            </div>
                            <div class="col-md-6 text-right" style="padding-top:10px">
                            <?php if($role_id == '1' || ($role_id == '4' && ($recover == '1' || $delete == '1'))){?>    
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo lang('actions') ?>
                                    </button>
                                    <div class="dropdown-menu">
                                        <h6 class="dropdown-header"><?php echo lang('filter_actions') ?></h6>
                                    <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>  
                                        <a class="dropdown-item sa-warning" href="javascript:void(0);" value="students,trash/delete_all"><?php echo lang('delete_all') ?></a>
                                    <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                        <a class="dropdown-item sa-trash-recover-all" href="javascript:void(0);" value="trash/recover_all,students"><?php echo lang('recover_all') ?></a>
                                    <?php } ?>    
                                    </div>
                                </div> 
                            <?php } ?>    
                            </div>
                            <div class="table-responsive" style="max-height: 350px; overflow: auto;">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <th style="width: 50px; text-align: center"><?php echo lang('lbl_sr') ?></th>
                                        <th><?php echo lang('subject_name_title') ?></th>
                                        <th style="width: 200px; text-align: center"><?php echo lang('datetime') ?></th>
                                        <th style="width: 100px; text-align: center"><?php echo lang('th_action') ?></th>
                                    </tr>
                                    <?php $index11 = 1; foreach ($students as $std) { ?>
                                        <tr>
                                            <td style="width: 50px; text-align: center">
                                                <?php echo $index11++; ?>
                                            </td>
                                            <td><?php echo $std->name; ?></td>
                                            <td class="text-center"><?php echo $std->deleted_at; ?></td>
                                            <td style="width: 100px; text-align: center">
                                            <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>     
                                                <a href="javascript:void(0);" class="btn btn-danger btn-circle text-white sa-trash-delete-single" value="trash/delete,users,<?php echo $std->id; ?>"><i class="fa fa-trash"></i></a>
                                            <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                                <a href="javascript:void(0);" class="btn btn-info btn-circle text-white sa-trash-recover-single" value="trash/recover,student_class_relation,<?php echo $std->id; ?>"><i class="fa fa-recycle"></i></a>
                                            <?php } ?>    
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (count($guardians) > 0) { ?>
                        <div class="white-box">
                            <div class="col-md-6">
                                <h3 class="text-danger"><i class="fa fa-trash"></i> <?php echo lang('menu_parent') ?></h3>
                            </div>
                            <div class="col-md-6 text-right" style="padding-top:10px">
                            <?php if($role_id == '1' || ($role_id == '4' && ($recover == '1' || $delete == '1'))){?>    
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo lang('actions') ?>
                                    </button>
                                    <div class="dropdown-menu">
                                        <h6 class="dropdown-header"><?php echo lang('filter_actions') ?></h6>
                                    <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>  
                                        <a class="dropdown-item sa-warning" href="javascript:void(0);" value="guardians,trash/delete_all"><?php echo lang('delete_all') ?></a>
                                    <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                        <a class="dropdown-item sa-trash-recover-all" href="javascript:void(0);" value="trash/recover_all,guardians"><?php echo lang('recover_all') ?></a>
                                    <?php } ?>                                        
                                    </div>
                                </div>
                            <?php } ?>     
                            </div>
                            <div class="table-responsive" style="max-height: 350px; overflow: auto;">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <th style="width: 50px; text-align: center"><?php echo lang('lbl_sr') ?></th>
                                        <th><?php echo lang('subject_name_title') ?></th>
                                        <th style="width: 200px; text-align: center"><?php echo lang('datetime') ?></th>
                                        <th style="width: 100px; text-align: center"><?php echo lang('th_action') ?></th>
                                    </tr>
                                    <?php $index12 = 1; foreach ($guardians as $gua) { ?>
                                        <tr>
                                            <td style="width: 50px; text-align: center">
                                                <?php echo $index12++; ?>
                                            </td>
                                            <td><?php echo $gua->name; ?></td>
                                            <td class="text-center"><?php echo $gua->deleted_at; ?></td>
                                            <td style="width: 100px; text-align: center">
                                            <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>        
                                                <a href="javascript:void(0);" class="btn btn-danger btn-circle text-white sa-trash-delete-single" value="trash/delete,users,<?php echo $gua->id; ?>"><i class="fa fa-trash"></i></a>
                                            <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>        
                                                <a href="javascript:void(0);" class="btn btn-info btn-circle text-white sa-trash-recover-single" value="trash/recover,users,<?php echo $gua->id; ?>"><i class="fa fa-recycle"></i></a>
                                            <?php } ?>     
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (count($study_materials) > 0) { ?>
                        <div class="white-box">
                            <div class="col-md-6">
                                <h3 class="text-danger"><i class="fa fa-trash"></i> <?php echo lang('study_materials') ?></h3>
                            </div>
                            <div class="col-md-6 text-right" style="padding-top:10px">
                            <?php if($role_id == '1' || ($role_id == '4' && ($recover == '1' || $delete == '1'))){?>    
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo lang('actions') ?>
                                    </button>
                                    <div class="dropdown-menu">
                                        <h6 class="dropdown-header"><?php echo lang('filter_actions') ?></h6>
                                    <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>  
                                        <a class="dropdown-item sa-warning" href="javascript:void(0);" value="study_material,trash/delete_all"><?php echo lang('delete_all') ?></a>
                                    <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                        <a class="dropdown-item sa-trash-recover-all" href="javascript:void(0);" value="trash/recover_all,study_material"><?php echo lang('recover_all') ?></a>
                                    <?php } ?>    
                                    </div>
                                </div>
                            <?php } ?>     
                            </div>
                            <div class="table-responsive" style="max-height: 350px; overflow: auto;">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <th style="width: 50px; text-align: center"><?php echo lang('lbl_sr') ?></th>
                                        <th><?php echo lang('subject_name_title') ?></th>
                                        <th style="width: 100px; text-align: center"><?php echo lang('th_action') ?></th>
                                    </tr>
                                    <?php $index13 = 1; foreach ($study_materials as $sm) { ?>
                                        <tr>
                                            <td style="width: 50px; text-align: center">
                                                <?php echo $index13++; ?>
                                            </td>
                                            <td><?php echo $sm->name; ?></td>
                                            <td style="width: 100px; text-align: center">
                                            <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>    
                                                <a href="javascript:void(0);" class="btn btn-danger btn-circle text-white sa-trash-delete-single" value="trash/delete,study_material,<?php echo $sm->id; ?>"><i class="fa fa-trash"></i></a>
                                            <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                                <a href="javascript:void(0);" class="btn btn-info btn-circle text-white sa-trash-recover-single" value="trash/recover,study_material,<?php echo $sm->id; ?>"><i class="fa fa-recycle"></i></a>
                                            <?php } ?>    
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (count($book_shops) > 0) { ?>
                        <div class="white-box">
                            <div class="col-md-6">
                                <h3 class="text-danger"><i class="fa fa-trash"></i> <?php echo lang('lbl_book_shop') ?></h3>
                            </div>
                            <div class="col-md-6 text-right" style="padding-top:10px">
                            <?php if($role_id == '1' || ($role_id == '4' && ($recover == '1' || $delete == '1'))){?>    
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo lang('actions') ?>
                                    </button>
                                    <div class="dropdown-menu">
                                        <h6 class="dropdown-header"><?php echo lang('filter_actions') ?></h6>
                                    <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>  
                                        <a class="dropdown-item sa-warning" href="javascript:void(0);" value="book_shop,trash/delete_all"><?php echo lang('delete_all') ?></a>
                                    <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                        <a class="dropdown-item sa-trash-recover-all" href="javascript:void(0);" value="trash/recover_all,book_shop"><?php echo lang('recover_all') ?></a>
                                    <?php } ?>    
                                    </div>
                                </div>
                            <?php } ?>     
                            </div>
                            <div class="table-responsive" style="max-height: 350px; overflow: auto;">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <th style="width: 50px; text-align: center"><?php echo lang('lbl_sr') ?></th>
                                        <th><?php echo lang('subject_name_title') ?></th>
                                        <th style="width: 200px; text-align: center"><?php echo lang('datetime') ?></th>
                                        <th style="width: 100px; text-align: center"><?php echo lang('th_action') ?></th>
                                    </tr>
                                    <?php $index14 = 1; foreach ($book_shops as $bs) { ?>
                                        <tr>
                                            <td style="width: 50px; text-align: center">
                                                <?php echo $index14++; ?>
                                            </td>
                                            <td><?php echo $bs->name; ?></td>
                                            <td class="text-center"><?php echo $bs->deleted_at; ?></td>
                                            <td style="width: 100px; text-align: center">
                                            <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>        
                                                <a href="javascript:void(0);" class="btn btn-danger btn-circle text-white sa-trash-delete-single" value="trash/delete,book_shop,<?php echo $bs->id; ?>"><i class="fa fa-trash"></i></a>
                                            <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                                <a href="javascript:void(0);" class="btn btn-info btn-circle text-white sa-trash-recover-single" value="trash/recover,book_shop,<?php echo $bs->id; ?>"><i class="fa fa-recycle"></i></a>
                                            <?php } ?>        
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (count($form_categories) > 0) { ?>
                        <div class="white-box">
                            <div class="col-md-6">
                                <h3 class="text-danger"><i class="fa fa-trash"></i> <?php echo lang('lbl_form_categories') ?></h3>
                            </div>
                            <div class="col-md-6 text-right" style="padding-top:10px">
                            <?php if($role_id == '1' || ($role_id == '4' && ($recover == '1' || $delete == '1'))){?>    
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo lang('actions') ?>
                                    </button>
                                    <div class="dropdown-menu">
                                        <h6 class="dropdown-header"><?php echo lang('filter_actions') ?></h6>
                                    <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>  
                                        <a class="dropdown-item sa-warning" href="javascript:void(0);" value="form_categories,trash/delete_all"><?php echo lang('delete_all') ?></a>
                                    <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                        <a class="dropdown-item sa-trash-recover-all" href="javascript:void(0);" value="trash/recover_all,form_categories"><?php echo lang('recover_all') ?></a>
                                    <?php } ?>                                        
                                    </div>
                                </div>
                            <?php } ?>     
                            </div>
                            <div class="table-responsive" style="max-height: 350px; overflow: auto;">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <th style="width: 50px; text-align: center"><?php echo lang('lbl_sr') ?></th>
                                        <th><?php echo lang('subject_name_title') ?></th>
                                        <th style="width: 200px; text-align: center"><?php echo lang('datetime') ?></th>
                                        <th style="width: 100px; text-align: center"><?php echo lang('th_action') ?></th>
                                    </tr>
                                    <?php $index15 = 1; foreach ($form_categories as $fcat) { ?>
                                        <tr>
                                            <td style="width: 50px; text-align: center">
                                                <?php echo $index15++; ?>
                                            </td>
                                            <td><?php echo $fcat->name; ?></td>
                                            <td class="text-center"><?php echo $fcat->deleted_at; ?></td>
                                            <td style="width: 100px; text-align: center">
                                            <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>    
                                                <a href="javascript:void(0);" class="btn btn-danger btn-circle text-white sa-trash-delete-single" value="trash/delete,form_categories,<?php echo $fcat->id; ?>"><i class="fa fa-trash"></i></a>
                                            <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                                <a href="javascript:void(0);" class="btn btn-info btn-circle text-white sa-trash-recover-single" value="trash/recover,form_categories,<?php echo $fcat->id; ?>"><i class="fa fa-recycle"></i></a>
                                            <?php } ?>    
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (count($forms) > 0) { ?>
                        <div class="white-box">
                            <div class="col-md-6">
                                <h3 class="text-danger"><i class="fa fa-trash"></i> <?php echo lang('custom_forms') ?></h3>
                            </div>
                            <div class="col-md-6 text-right" style="padding-top:10px">
                            <?php if($role_id == '1' || ($role_id == '4' && ($recover == '1' || $delete == '1'))){?>    
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo lang('actions') ?>
                                    </button>
                                    <div class="dropdown-menu">
                                        <h6 class="dropdown-header"><?php echo lang('filter_actions') ?></h6>
                                    <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>  
                                        <a class="dropdown-item sa-warning" href="javascript:void(0);" value="templates,trash/delete_all"><?php echo lang('delete_all') ?></a>
                                    <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                        <a class="dropdown-item sa-trash-recover-all" href="javascript:void(0);" value="trash/recover_all,templates"><?php echo lang('recover_all') ?></a>
                                    <?php } ?>    
                                    </div>
                                </div>
                            <?php } ?>     
                            </div>
                            <div class="table-responsive" style="max-height: 350px; overflow: auto;">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <th style="width: 50px; text-align: center"><?php echo lang('lbl_sr') ?></th>
                                        <th><?php echo lang('subject_name_title') ?></th>
                                        <th style="width: 200px; text-align: center"><?php echo lang('datetime') ?></th>
                                        <th style="width: 100px; text-align: center"><?php echo lang('th_action') ?></th>
                                    </tr>
                                    <?php $index16 = 1; foreach ($forms as $f) { ?>
                                        <tr>
                                            <td style="width: 50px; text-align: center">
                                                <?php echo $index16++; ?>
                                            </td>
                                            <td><?php echo $f->name; ?></td>
                                            <td class="text-center"><?php echo $f->deleted_at; ?></td>
                                            <td style="width: 100px; text-align: center">
                                            <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>    
                                                <a href="javascript:void(0);" class="btn btn-danger btn-circle text-white sa-trash-delete-single" value="trash/delete,templates,<?php echo $f->id; ?>"><i class="fa fa-trash"></i></a>
                                            <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                                <a href="javascript:void(0);" class="btn btn-info btn-circle text-white sa-trash-recover-single" value="trash/recover,templates,<?php echo $f->id; ?>"><i class="fa fa-recycle"></i></a>
                                            <?php } ?>    
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (count($exams) > 0) { ?>
                        <div class="white-box">
                            <div class="col-md-6">
                                <h3 class="text-danger"><i class="fa fa-trash"></i> <?php echo lang('lbl_exams') ?></h3>
                            </div>
                            <div class="col-md-6 text-right" style="padding-top:10px">
                            <?php if($role_id == '1' || ($role_id == '4' && ($recover == '1' || $delete == '1'))){?>    
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo lang('actions') ?>
                                    </button>
                                    <div class="dropdown-menu">
                                        <h6 class="dropdown-header"><?php echo lang('filter_actions') ?></h6>
                                    <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>  
                                        <a class="dropdown-item sa-warning" href="javascript:void(0);" value="exams,trash/delete_all"><?php echo lang('delete_all') ?></a>
                                    <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                        <a class="dropdown-item sa-trash-recover-all" href="javascript:void(0);" value="trash/recover_all,exams"><?php echo lang('recover_all') ?></a>
                                     <?php } ?>    
                                    </div>
                                </div>
                             <?php } ?>     
                            </div>
                            <div class="table-responsive" style="max-height: 350px; overflow: auto;">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <th style="width: 50px; text-align: center"><?php echo lang('lbl_sr') ?></th>
                                        <th><?php echo lang('subject_name_title') ?></th>
                                        <th style="width: 200px; text-align: center"><?php echo lang('datetime') ?></th>
                                        <th style="width: 100px; text-align: center"><?php echo lang('th_action') ?></th>
                                    </tr>
                                    <?php $index17 = 1; foreach ($exams as $e) { ?>
                                        <tr>
                                            <td style="width: 50px; text-align: center">
                                                <?php echo $index17++; ?>
                                            </td>
                                            <td><?php echo $e->name; ?></td>
                                            <td class="text-center"><?php echo $e->deleted_at; ?></td>
                                            <td style="width: 100px; text-align: center">
                                            <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>    
                                                <a href="javascript:void(0);" class="btn btn-danger btn-circle text-white sa-trash-delete-single" value="trash/delete,exams,<?php echo $e->id; ?>"><i class="fa fa-trash"></i></a>
                                            <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                                <a href="javascript:void(0);" class="btn btn-info btn-circle text-white sa-trash-recover-single" value="trash/recover,exams,<?php echo $e->id; ?>"><i class="fa fa-recycle"></i></a>
                                            <?php } ?>    
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (count($exam_details) > 0) { ?>
                        <div class="white-box">
                            <div class="col-md-6">
                                <h3 class="text-danger"><i class="fa fa-trash"></i> <?php echo lang('lbl_exam_details') ?></h3>
                            </div>
                            <div class="col-md-6 text-right" style="padding-top:10px">
                            <?php if($role_id == '1' || ($role_id == '4' && ($recover == '1' || $delete == '1'))){?>    
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo lang('actions') ?>
                                    </button>
                                    <div class="dropdown-menu">
                                        <h6 class="dropdown-header"><?php echo lang('filter_actions') ?></h6>
                                    <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>  
                                        <a class="dropdown-item sa-warning" href="javascript:void(0);" value="exam_details,trash/delete_all"><?php echo lang('delete_all') ?></a>
                                    <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                        <a class="dropdown-item sa-trash-recover-all" href="javascript:void(0);" value="trash/recover_all,exam_details"><?php echo lang('recover_all') ?></a>
                                    <?php } ?>    
                                    </div>
                                </div> 
                            <?php } ?>    
                            </div>
                            <div class="table-responsive" style="max-height: 350px; overflow: auto;">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <th style="width: 50px; text-align: center"><?php echo lang('lbl_sr') ?></th>
                                        <th><?php echo lang('subject_name_title') ?></th>
                                        <th style="width: 200px; text-align: center"><?php echo lang('datetime') ?></th>
                                        <th style="width: 100px; text-align: center"><?php echo lang('th_action') ?></th>
                                    </tr>
                                    <?php $index18 = 1; foreach ($exam_details as $ed) { ?>
                                        <tr>
                                            <td style="width: 50px; text-align: center">
                                                <?php echo $index18++; ?>
                                            </td>
                                            <td>
                                                <h4><?php echo $ed->exam_name; ?></h4>
                                                <p>
                                                    <label><?php echo lang('lbl_par_Class') ?>: </label><span><?php echo $ed->class_name; ?></span>
                                                    <br/><label><?php echo lang('lbl_Section_par') ?>: </label><span><?php echo $ed->batch_name; ?></span>
                                                    <br/><label><?php echo lang('lbl_subject') ?>: </label><span><?php echo $ed->subject_name; ?></span>
                                                    <br/><label><?php echo lang('deleted') ?>: </label><span><?php echo $ed->deleted_at; ?></span>
                                                </p>
                                            </td>
                                            <td class="text-center"><?php echo $ed->deleted_at; ?></td>
                                            <td style="width: 100px; text-align: center">
                                            <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>    
                                                <a href="javascript:void(0);" class="btn btn-danger btn-circle text-white sa-trash-delete-single" value="trash/delete,exam_details,<?php echo $ed->id; ?>"><i class="fa fa-trash"></i></a>
                                            <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>     
                                                <a href="javascript:void(0);" class="btn btn-info btn-circle text-white sa-trash-recover-single" value="trash/recover,exam_details,<?php echo $ed->id; ?>"><i class="fa fa-recycle"></i></a>
                                            <?php } ?>    
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (count($passing_rules) > 0) { ?>
                        <div class="white-box">
                            <div class="col-md-6">
                                <h3 class="text-danger"><i class="fa fa-trash"></i> <?php echo lang('passing_rules') ?></h3>
                            </div>
                            <div class="col-md-6 text-right" style="padding-top:10px">
                            <?php if($role_id == '1' || ($role_id == '4' && ($recover == '1' || $delete == '1'))){?>    
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo lang('actions') ?>
                                    </button>
                                    <div class="dropdown-menu">
                                        <h6 class="dropdown-header"><?php echo lang('filter_actions') ?></h6>
                                    <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>  
                                        <a class="dropdown-item sa-warning" href="javascript:void(0);" value="passing_rules,trash/delete_all"><?php echo lang('delete_all') ?></a>
                                    <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                        <a class="dropdown-item sa-trash-recover-all" href="javascript:void(0);" value="trash/recover_all,passing_rules"><?php echo lang('recover_all') ?></a>
                                    <?php } ?>    
                                    </div>
                                </div> 
                            <?php } ?>    
                            </div>
                            <div class="table-responsive" style="max-height: 350px; overflow: auto;">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <th style="width: 50px; text-align: center"><?php echo lang('lbl_sr') ?></th>
                                        <th><?php echo lang('subject_name_title') ?></th>
                                        <th style="width: 200px; text-align: center"><?php echo lang('datetime') ?></th>
                                        <th style="width: 100px; text-align: center"><?php echo lang('th_action') ?></th>
                                    </tr>
                                    <?php $index19 = 1; foreach ($passing_rules as $pr) { ?>
                                        <tr>
                                            <td style="width: 50px; text-align: center">
                                                <?php echo $index19++; ?>
                                            </td>
                                            <td>
                                                <h4><?php echo $pr->exam_name; ?></h4>
                                                <p>
                                                    <label><?php echo lang('minimum_percentage') ?>: </label><span><?php echo $pr->minimum_percentage; ?></span>
                                                    <br/><label><?php echo lang('minimum_subject') ?>: </label><span><?php echo $pr->minimum_subjects; ?></span>
                                                    <br/><label><?php echo lang('lbl_class') ?>: </label><span><?php echo $pr->class_name; ?></span>
                                                    <br/><label><?php echo lang('lbl_batch') ?>: </label><span><?php echo $pr->batch_name; ?></span>
                                                    <br/><label><?php echo lang('deleted') ?>: </label><span><?php echo $pr->deleted_at; ?></span>
                                                </p>
                                            </td>
                                            <td class="text-center"><?php echo $pr->deleted_at; ?></td>
                                            <td style="width: 100px; text-align: center">
                                            <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>    
                                                <a href="javascript:void(0);" class="btn btn-danger btn-circle text-white sa-trash-delete-single" value="trash/delete,passing_rules,<?php echo $pr->id; ?>"><i class="fa fa-trash"></i></a>
                                            <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                                <a href="javascript:void(0);" class="btn btn-info btn-circle text-white sa-trash-recover-single" value="trash/recover,passing_rules,<?php echo $pr->id; ?>"><i class="fa fa-recycle"></i></a>
                                            <?php } ?>    
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (count($syllabus_weeks) > 0) { ?>
                        <div class="white-box">
                            <div class="col-md-6">
                                <h3 class="text-danger"><i class="fa fa-trash"></i> <?php echo lang('study_plan_week') ?></h3>
                            </div>
                            <div class="col-md-6 text-right" style="padding-top:10px">
                            <?php if($role_id == '1' || ($role_id == '4' && ($recover == '1' || $delete == '1'))){?>    
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo lang('actions') ?>
                                    </button>
                                    <div class="dropdown-menu">
                                        <h6 class="dropdown-header"><?php echo lang('filter_actions') ?></h6>
                                    <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>
                                        <a class="dropdown-item sa-warning" href="javascript:void(0);" value="syllabus_weeks,trash/delete_all"><?php echo lang('delete_all') ?></a>
                                    <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                        <a class="dropdown-item sa-trash-recover-all" href="javascript:void(0);" value="trash/recover_all,syllabus_weeks"><?php echo lang('recover_all') ?></a>
                                    <?php } ?>    
                                    </div>
                                </div>
                            <?php } ?>     
                            </div>
                            <div class="table-responsive" style="max-height: 350px; overflow: auto;">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <th style="width: 50px; text-align: center"><?php echo lang('lbl_sr') ?></th>
                                        <th><?php echo lang('subject_name_title') ?></th>
                                        <th style="width: 100px; text-align: center"><?php echo lang('th_action') ?></th>
                                    </tr>
                                    <?php $index20 = 1; foreach ($syllabus_weeks as $sw) { ?>
                                        <tr>
                                            <td style="width: 50px; text-align: center">
                                                <?php echo $index20++; ?>
                                            </td>
                                            <td>
                                                <h4><?php echo $sw->name; ?></h4>
                                                <p>
                                                    <label><?php echo lang('lbl_class') ?>: </label><span><?php echo $sw->class_name; ?></span>
                                                    <br/><label><?php echo lang('lbl_batch') ?>: </label><span><?php echo $sw->batch_name; ?></span>
                                                    <br/><label><?php echo lang('subject_name_title') ?>: </label><span><?php echo $sw->subject_name; ?></span>
                                                    <br/><label><?php echo lang('deleted') ?>: </label><span><?php echo $sw->deleted_at; ?></span>
                                                </p>
                                            </td>
                                            <td style="width: 100px; text-align: center">
                                            <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>    
                                                <a href="javascript:void(0);" class="btn btn-danger btn-circle text-white sa-trash-delete-single" value="trash/delete,syllabus_weeks,<?php echo $sw->id; ?>"><i class="fa fa-trash"></i></a>
                                            <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                                <a href="javascript:void(0);" class="btn btn-info btn-circle text-white sa-trash-recover-single" value="trash/recover,syllabus_weeks,<?php echo $sw->id; ?>"><i class="fa fa-recycle"></i></a>
                                            <?php } ?>    
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (count($syllabus_week_details) > 0) { ?>
                        <div class="white-box">
                            <div class="col-md-6">
                                <h3 class="text-danger"><i class="fa fa-trash"></i> <?php echo lang('study_plan_week_details') ?></h3>
                            </div>
                            <div class="col-md-6 text-right" style="padding-top:10px">
                            <?php if($role_id == '1' || ($role_id == '4' && ($recover == '1' || $delete == '1'))){?>    
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo lang('actions') ?>
                                    </button>
                                    <div class="dropdown-menu">
                                        <h6 class="dropdown-header"><?php echo lang('filter_actions') ?></h6>
                                    <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>  
                                        <a class="dropdown-item sa-warning" href="javascript:void(0);" value="syllabus_week_details,trash/delete_all"><?php echo lang('delete_all') ?></a>
                                    <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                        <a class="dropdown-item sa-trash-recover-all" href="javascript:void(0);" value="trash/recover_all,syllabus_week_details"><?php echo lang('recover_all') ?></a>
                                    <?php } ?>    
                                    </div>
                                </div>
                            <?php } ?>     
                            </div>
                            <div class="table-responsive" style="max-height: 350px; overflow: auto;">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <th style="width: 50px; text-align: center"><?php echo lang('lbl_sr') ?></th>
                                        <th><?php echo lang('subject_name_title') ?></th>
                                        <th style="width: 100px; text-align: center"><?php echo lang('th_action') ?></th>
                                    </tr>
                                    <?php $index21 = 1; foreach ($syllabus_week_details as $swd) { ?>
                                        <tr>
                                            <td style="width: 50px; text-align: center">
                                                <?php echo $index21++; ?>
                                            </td>
                                            <td>
                                                <h4><?php echo $swd->week_name; ?></h4>
                                                <p>
                                                    <label><?php echo lang('lbl_topic') ?>: </label><span><?php echo $swd->topic; ?></span>
                                                    <br/><label><?php echo lang('lbl_class') ?>: </label><span><?php echo $swd->class_name; ?></span>
                                                    <br/><label><?php echo lang('lbl_batch') ?>: </label><span><?php echo $swd->batch_name; ?></span>
                                                    <br/><label><?php echo lang('subject_name_title') ?>: </label><span><?php echo $swd->subject_name; ?></span>
                                                    <br/><label><?php echo lang('deleted') ?>: </label><span><?php echo $swd->deleted_at; ?></span>
                                                </p>
                                            </td>
                                            <td style="width: 100px; text-align: center">
                                            <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>    
                                                <a href="javascript:void(0);" class="btn btn-danger btn-circle text-white sa-trash-delete-single" value="trash/delete,syllabus_week_details,<?php echo $swd->id; ?>"><i class="fa fa-trash"></i></a>
                                            <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                                <a href="javascript:void(0);" class="btn btn-info btn-circle text-white sa-trash-recover-single" value="trash/recover,syllabus_week_details,<?php echo $swd->id; ?>"><i class="fa fa-recycle"></i></a>
                                            <?php } ?>    
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    <?php } ?>
                    
                    <?php if (count($fee_collections) > 0) { ?>
                        <div class="white-box">
                            <div class="col-md-6">
                                <h3 class="text-danger"><i class="fa fa-trash"></i> <?php echo lang('crumb_fee_collection') ?></h3>
                            </div>
                            <div class="col-md-6 text-right" style="padding-top:10px">
                            <?php if($role_id == '1' || ($role_id == '4' && ($recover == '1' || $delete == '1'))){?>    
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo lang('actions') ?>
                                    </button>
                                    <div class="dropdown-menu">
                                        <h6 class="dropdown-header"><?php echo lang('filter_actions') ?></h6>
                                    <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?>  
                                        <a class="dropdown-item sa-warning" href="javascript:void(0);" value="fee_collection,trash/delete_all"><?php echo lang('delete_all') ?></a>
                                    <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                        <a class="dropdown-item sa-trash-recover-all" href="javascript:void(0);" value="trash/recover_all,fee_collection"><?php echo lang('recover_all') ?></a>
                                    <?php } ?>    
                                    </div>
                                </div>
                            <?php } ?>     
                            </div>
                            <div class="table-responsive" style="max-height: 350px; overflow: auto;">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <th style="width: 50px; text-align: center"><?php echo lang('lbl_sr') ?></th>
                                        <th><?php echo lang('subject_name_title') ?></th>
                                        <th style="width: 200px; text-align: center"><?php echo lang('datetime') ?></th>
                                        <th style="width: 100px; text-align: center"><?php echo lang('th_action') ?></th>
                                    </tr>
                                    <?php $index22 = 1; foreach ($fee_collections as $fcc) { ?>
                                        <tr>
                                            <td style="width: 50px; text-align: center">
                                                <?php echo $index22++; ?>
                                            </td>
                                            <td>
                                                <h4><?php echo $fcc->feetype_name; ?></h4>
                                                <p>
                                                    <label><?php echo lang('collect_by') ?>: </label><span><?php echo $fcc->collector_name; ?></span>
                                                    <br/><label><?php echo lang('student_name') ?>: </label><span><?php echo $fcc->student_name; ?></span>
                                                    <br/><label><?php echo lang('lbl_class') ?>: </label><span><?php echo $fcc->class_name; ?></span>
                                                    <br/><label><?php echo lang('batch') ?>: </label><span><?php echo $fcc->batch_name; ?></span>
                                                    <br/><label><?php echo lang('deleted') ?>: </label><span><?php echo $fcc->deleted_at; ?></span>
                                                </p>
                                            </td>
                                            <td class="text-center"><?php echo $fcc->deleted_at; ?></td>
                                            <td style="width: 100px; text-align: center">
                                            <?php if($role_id == '1' || ($role_id == '4' &&  $delete == '1')){?> 
                                                <a href="javascript:void(0);" class="btn btn-danger btn-circle text-white sa-trash-delete-single" value="trash/delete,fee_collection,<?php echo $fcc->id; ?>"><i class="fa fa-trash"></i></a>
                                            <?php } if($role_id == '1' || ($role_id == '4' && $recover == '1' )){?>    
                                                <a href="javascript:void(0);" class="btn btn-info btn-circle text-white sa-trash-recover-single" value="trash/recover,fee_collection,<?php echo $fcc->id; ?>"><i class="fa fa-recycle"></i></a>
                                            <?php } ?>    
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    <?php } ?>
                
                <?php } else if($view_type == 'grid') { ?>
                    
                    <div class="row el-element-overlay">
                        <?php if(count($academic_years) > 0) { ?>
                            <?php foreach($academic_years as $ayy) { ?>
                                <div class="col-lg-3">
                                    <div class="card">
                                        <div class="el-card-item">
                                            <div class="el-card-content pt-5">
                                                <h3 class="box-title"><?php echo lang('lbl_academic_year') ?></h3> 
                                                <span><?php echo lang('lbl_name') ?>: <?php echo $ayy->name; ?></span><br/>
                                                <small><?php echo lang('deleted') ?>: <?php echo $ayy->deleted_at; ?></small><br/>
                                                <a href="javascript:void(0);"><?php echo lang('recover') ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                
                <?php } ?>

            </div>
        </div>
        <!-- .right-sidebar -->
        <!-- Include yout right sidebar here -->
        <!-- /.right-sidebar -->
    </div>
<?php include(APPPATH . "views/inc/footer.php"); ?>