<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<!-- Page Content -->
<div id="page-wrapper" ng-controller="parentviewchildCtrl" ng-init="init_parent_children_list()">
    <div class="container-fluid">
    <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Children List</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="<?php echo site_url('/dashboard') ?>"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li><a href="<?php echo site_url('/attendance') ?>"><?php echo lang('lbl_examination') ?></a></li>
                    <li class="active">Students Report</li>
                </ol>
            </div>
        </div>

     
                <div class="col-sm-12">
                    <div class="white-box">
                        <div class="table-responsive">
                            <table class="myTable display nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                       
                                        <th><?php echo lang('lbl_avatar') ?></th>
                                        <th><?php echo lang('lbl_name') ?></th>
                                        <th><?php echo lang('lbl_rollno') ?></th>
                                        <th><?php echo lang('lbl_phone') ?></th>
                                        <th><?php echo lang('lbl_class') ?></th>
                                        <th><?php echo lang('lbl_batch') ?></th>
                                        
                                        <th><?php echo lang('lbl_Gender_prt'); ?></th>
                                        <th><?php echo lang('lbl_action') ?></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        
                                        <th><?php echo lang('lbl_avatar') ?></th>
                                        <th><?php echo lang('lbl_name') ?></th>
                                        <th><?php echo lang('lbl_rollno') ?></th>
                                        <th><?php echo lang('lbl_phone') ?></th>
                                        <th><?php echo lang('lbl_class') ?></th>
                                        <th><?php echo lang('lbl_batch') ?></th>
                                        
                                        <th><?php echo "Gender"; ?></th>
                                        <th><?php echo lang('lbl_action') ?></th>
                                    </tr>
                                </tfoot>
                                <tbody ng-repeat="c in childerns">
                                    
                                            <tr>
                                                
                                                <td><img src="<?php echo base_url(); ?>uploads/user/{{c.avatar}}" alt="user-img" class="img-circle" style="height: 60px;width: 60px" /></td>
                                                <td>{{c.name}}</td>
                                                <td>{{c.rollno}}</td>
                                                <td>{{c.contact}}</td>
                                                <td>{{c.class_name}}</td>
                                                <td>{{c.batch_name}}</td>
                                                
                                                <td>{{c.gender}}</td>
                                                
                                                <td style="text-align: center;"><a type="button" href="<?php echo site_url('parents/student_details/{{c.student_id}}'); ?>" target="_blank" class="btn btn-success btn-circle" ><i class="fa fa-eye"></i></a></td>
                                               
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        
        <!-- .right-sidebar -->
        <!-- Include yout right sidebar here -->
        <!-- /.right-sidebar -->
    </div>
</div>
    <?php include(APPPATH . "views/inc/footer.php"); ?>