<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('heading_all_employee') ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="#"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li><a href="#"><?= lang('crumb_employee') ?></a></li>
                    <li class="active"><?php echo lang('heading_all_employee') ?></li>

                </ol>
            </div>
        </div>
        <!-- /.row -->
        <!-- Alert message -->
        <?php $error = $this->session->flashdata('alert'); if(!empty($error)) { ?>
        <div class="alert alert-dismissable <?php if($this->session->flashdata('alert')['status'] == 'error') { echo 'alert-danger'; } else {echo 'alert-success'; }?>"> 
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>     
            <?= $this->session->flashdata("alert")['message']; ?> 
        </div>
        <?php } ?>
        <!-- End alert message -->
        <!-- .row -->
        <?php echo $this->session->flashdata('success-image'); ?>
        <div class="hint"><?php echo lang('help_emp_all'); ?></div>
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <!-- new changes by Yasir 01-03-2018 -->
                    <div class="well" id="emp_search_filter" style="background:#e4e7ea;">
                        <div class="row" ng-controller="empyFilterController">
                            <div class="col-md-6">
                               <div class="form-group">
                                <label class="control-label"><?php echo lang('title_department') ?></label>
                                <select class="form-control" id="department" onchange="getCategories($(this).val())">
                                    <option value="all"><?php echo lang('option_all') ?></option>
                                    <?php if(count($departments)>0){ foreach($departments as $department) { ?>
                                    <option value="<?php echo encrypt($department->id) ?>"><?php echo $department->name; ?></option>
                                    <?php } }?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('title_category') ?></label>
                                <select class="form-control" name="categories" id="categories" onchange=";">
                                  <option value="all"><?php echo lang('option_all') ?></option>
                              </select>
                          </div>
                      </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-sm btn-primary" onClick="filter()"><?php echo lang('search') ?></button>
                    </div>

                </div>
            </div>
            <div class="row" id="empContainer">

                <div class="table-responsive">
                    <table class="myTable display nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>

                                <th><?php echo lang('imp_sr') ?></th>
                                <th><?php echo lang('lbl_avatar') ?></th>
                                <th><?php echo lang('heading_name') ?></th>
                                <th><?php echo lang('heading_position') ?></th>
                                <?php if(get_acountant_dept_id() == login_user()->user->department_id || login_user()->user->role_id == ADMIN_ROLE_ID) { ?>
                                    <th><?php echo lang('lbl_basic_salary') ?></th>
                                <?php } ?>
                                <th><?php echo lang('title_category') ?></th>
                                <th><?php echo lang('title_department') ?></th>
                                <th><?php echo lang('heading_ic_number') ?></th>
                                <th><?php echo lang('heading_country') ?></th>
                                <th><?php echo lang('heading_action') ?></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th><?php echo lang('imp_sr') ?></th>
                                <th><?php echo lang('lbl_avatar') ?></th>
                                <th><?php echo lang('heading_name') ?></th>
                                <th><?php echo lang('heading_position') ?></th>
                                <?php if(get_acountant_dept_id() == login_user()->user->department_id || login_user()->user->role_id == ADMIN_ROLE_ID) { ?>
                                    <th><?php echo lang('lbl_basic_salary') ?></th>
                                <?php } ?>
                                <th><?php echo lang('title_category') ?></th>
                                <th><?php echo lang('title_department') ?></th>
                                <th><?php echo lang('heading_ic_number') ?></th>
                                <th><?php echo lang('heading_country') ?></th>
                                <th><?php echo lang('heading_action') ?></th>
                            </tr>
                        </tfoot>
                        <tbody id="myTableBody">
                            <?php
                            if (count($employees) > 0) {
                                $count = 1;
                                foreach ($employees as $employee) {
                                    ?>
                                    <tr>
                                        <td><?php echo $count; ?></td>
                                        <td><img src="<?php echo base_url() ?>uploads/user/<?php echo $employee->avatar; ?>" alt="user-img" class="img-circle" style="height: 60px;width: 60px"></td>
                                        <td><?php echo $employee->name; ?></td>
                                        <td><?php echo $employee->job_title; ?></td>
                                        <?php if(get_acountant_dept_id() == login_user()->user->department_id || login_user()->user->role_id == ADMIN_ROLE_ID) { ?>
                                            <td><?php echo $this->session->userdata("userdata")["currency_symbol"]; ?><?php echo $employee->basic_salary; ?></td>
                                        <?php } ?>
                                        <td><?php echo $employee->category_name; ?></td>
                                        <td><?php echo $employee->department_name; ?></td>
                                        <td><?php echo $employee->ic_number; ?></td>
                                        <td><?php echo $employee->country; ?></td>
                                        <td>
                                            <?php $ci = & get_instance();
                                                $arr = $ci->session->userdata("userdata")['persissions'];
                                                $array = json_decode($arr);
                                                if(isset($array)){
                                                    $view = 0;
                                                    $edit = 0;
                                                     foreach ($array as $key => $value) {
                                                        if(in_array('employee-view',array($value->permission)) && $value->val == 'true'){
                                                            $view = 1;
                                                        }
                                                        if(in_array('employee-edit',array($value->permission)) && $value->val == 'true'){
                                                            $edit = 1;
                                                        }
                                                     }
                                                }
                                                ?>
                                                <?php if($role_id == '4' && isset($edit) || isset($view)){
                                                    if($view ==1 ){?>
                                                        <a type="button" href="<?php echo site_url();?>employee/view?id=<?php echo encrypt($employee->id);?>" target="_blank" class="btn btn-success btn-circle" ><i class="fa fa-eye"></i></a>
                                                    <?php } if($edit ==1 ){?>
                                                        <a type="button" href="<?php echo site_url();?>employee/edit?id=<?php echo encrypt($employee->id);?>" target="_blank" class="btn btn-info btn-circle" ><i class="fa fa-pencil"></i></a>
                                                <?php }} else if($role_id == '1'){?>
                                                        <a type="button" href="<?php echo site_url();?>employee/view?id=<?php echo encrypt($employee->id);?>" target="_blank" class="btn btn-success btn-circle" ><i class="fa fa-eye"></i></a>
                                                        <a type="button" href="<?php echo site_url();?>employee/edit?id=<?php echo encrypt($employee->id);?>" target="_blank" class="btn btn-info btn-circle" ><i class="fa fa-pencil"></i></a>
                                                        <a href="javascript:void(0)" value="<?php echo encrypt($employee->id); ?>,employee/delete" class="sa-warning btn btn-danger btn-circle text-white"><i class="fa  fa-trash-o"></i></a>
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <?php
                                    $count++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--page content end-->
</div>
<?php include(APPPATH . "views/inc/footer.php"); ?>
<script>
    function getCategories(department){
        Loading("#categories", '<?php echo lang("loading_datatable"); ?>', "", "show");
        $.post('<?php echo site_url("employee/getCategories");?>',{department:department}).done(function(res){
            Loading("#categories", '<?php echo lang("loading_datatable"); ?>', "", "hide");
            //filter($("#categories").val());
            $("select[name='categories']").html(res);             
        });
    }

    function filter() {
        var val = $("#categories").val();
        var department = $("#department").val();
        Loading("#emp_search_filter", '<?php echo lang("loading_datatable"); ?>', "", "show");
        $.post('<?php echo site_url("employee/getDepartmentWithCategoryFilter");?>',{category: val,department:department}).done(function(res){
            Loading("#emp_search_filter", '<?php echo lang("loading_datatable"); ?>', "", "hide");
            $("#empContainer").html(res);               
        });
    }

</script>
