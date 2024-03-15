<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<?php
$UserData = $this->session->userdata('userdata');
$role_id = $UserData['role_id'];
?>

<div>
    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?php echo lang('all_parents') ?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="#"><?php echo lang('crumb_dashboard') ?></a></li>
                        <li><a href="#"><?php echo lang('crumb_parents') ?></a></li>
                        <li class="active"><?php echo lang('all_parents') ?></li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <?php $error = $this->session->flashdata('alert'); if(!empty($error)) { ?>
            <div class="alert alert-dismissable <?php if($this->session->flashdata('alert')['status'] == 'error') { echo 'alert-danger'; } else {echo 'alert-success'; }?>"> 
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>     
                <?= $this->session->flashdata("alert")['message']; ?> 
            </div>
            <?php } ?>
            <div class="hint"><?php echo lang('help_parent_all'); ?></div>

            <!--.row-->
            <div class="row" id="stdTableContianer">
                <div class="col-sm-12">
                    <div class="white-box">
                        <div class="table-responsive">
                            <table class="myTable display nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo lang('lbl_name') ?></th>
                                        <th><?php echo lang('lbl_email') ?></th>
                                        <th><?php echo lang('imp_contact') ?></th>
                                        <th><?php echo lang('lbl_occupation') ?></th>
                                        <th><?php echo lang('lbl_children') ?></th>
                                        <th><?php echo lang('lbl_action') ?></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th><?php echo lang('lbl_name') ?></th>
                                        <th><?php echo lang('lbl_email') ?></th>
                                        <th><?php echo lang('imp_contact') ?></th>
                                        <th><?php echo lang('lbl_occupation') ?></th>
                                        <th><?php echo lang('lbl_children') ?></th>
                                        <th><?php echo lang('lbl_action') ?></th>
                                    </tr>
                                </tfoot>
                                <tbody id="myTableBody2">
                                    <?php if (count($parents) > 0) {
                                        foreach ($parents as $par) { ?>
                                        <tr>
                                            <td><?php echo $par->name; ?></td>
                                            <td><?php echo $par->email; ?></td>
                                            <td><?php echo $par->contact; ?></td>
                                            <td><?php echo $par->occupation; ?></td>
                                            <td><?php echo $par->children; ?></td>

                                            <td class="text-center">
                                                
                                                 <?php $ci = & get_instance();
                                                    $arr = $ci->session->userdata("userdata")['persissions'];
                                                    $array = json_decode($arr);
                                                    if(isset($array)){
                                                        $view = 0;
                                                        $edit = 0;
                                                         foreach ($array as $key => $value) {
                                                             if(in_array('parents-view',array($value->permission)) && $value->val == 'true'){
                                                                $view = 1;
                                                            }
                                                            if(in_array('parents-edit',array($value->permission)) && $value->val == 'true'){
                                                                $edit = 1;
                                                            }
                                                         }
                                                    }
                                                    ?>
                                                        <?php if($role_id == '4' && isset($edit) || isset($view)){
                                                            if($edit == '1' || $view == '1'){?> 
                                                                <?php if($view == '1') {?>
                                                                    <a type="button" href="<?php echo base_url(); ?>parents/view/<?php echo encrypt($par->id); ?>" target="_blank" class="btn btn-success btn-circle" ><i class="fa fa-eye"></i></a>
                                                                <?php } if($edit == '1') {?>
                                                                    <a type="button" href="<?php echo base_url(); ?>parents/edit/<?php echo encrypt($par->id); ?>" target="_blank" class="btn btn-info btn-circle" ><i class="fa fa-pencil"></i></a>
                                                                <?php }?>

                                                        <?php }} else if($role_id ==='1'){ ?> 
                                                                    <a type="button" href="<?php echo base_url(); ?>parents/view/<?php echo encrypt($par->id); ?>" target="_blank" class="btn btn-success btn-circle" ><i class="fa fa-eye"></i></a>
                                                            <a type="button" href="<?php echo base_url(); ?>parents/edit/<?php echo encrypt($par->id); ?>" target="_blank" class="btn btn-info btn-circle" ><i class="fa fa-pencil"></i></a>
                                                            <a href="javascript:void(0)" value="<?php echo encrypt($par->id); ?>,parents/delete" class="sa-warning btn btn-danger btn-circle text-white"><i class="fa  fa-trash-o"></i></a>
                                                    <?php }?>
                                                
                                                 
                                            </td>
                                        </tr>
                                        <?php } } else { ?>
                                        <tr><td colspan="6"><?php echo lang('no_record') ?></td></tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
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