<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">HR Settings</h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                    <ol class="breadcrumb">
                        <li><a href="#">Dashboard</a></li>
                        <li><a href="#">Settings</a></li>
                        <li class="active">HR Settings</li>


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
        
        <!-- Model For department -->
        <div id="responsive-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><?php echo lang("lbl_new_department_form"); ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form action="<?= site_url('settings/saveDepartment')?>" method="post">
                    <div class="modal-body">
                            <div class="form-group">
                               <label for="recipient-name" class="control-label"><?= lang("lbl_role_category"); ?></label>
                               <select class="form-control" name="role_category_id">
                                   <?php foreach($role_categories as $val) { ?>
                                   <option value="<?= $val->id; ?>"><?= $val->category; ?></option>
                                   <?php } ?>
                               </select>
                           </div>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label"><?php echo lang("lbl_department_name"); ?></label>
                                <input type="text" name="name" placeholder="<?= lang('lbl_department_name'); ?>" class="form-control" id="department-name" />
                            </div>
                            <div class="form-group">
                                <label for="code-text" class="control-label"><?php echo lang("lbl_department_code"); ?></label>
                                <input type="text" name="code" placeholder="<?= lang('lbl_department_code')?>" class="form-control" id="code">
                            </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang("lbl_close"); ?></button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light"><?php echo lang("lbl_save"); ?></button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div id="edit-department" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="margin-top:80px;">
                        <h4 class="modal-title">Edit form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form action="<?= site_url('settings/updateDepartment')?>" method="post">
                    <div class="modal-body">
                            <div class="form-group">
                               <label for="recipient-name" class="control-label"><?= lang("lbl_role_category"); ?></label>
                               <select class="form-control" name="role_category_id" id="edit-dept-cat">
                                   <?php foreach($role_categories as $val) { ?>
                                   <option value="<?= $val->id; ?>"><?= $val->category; ?></option>
                                   <?php } ?>
                               </select>
                           </div>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label"><?php echo lang("lbl_department_name"); ?></label>
                                <input type="text" name="name" placeholder="<?= lang('lbl_department_name'); ?>" class="form-control" id="edit-department-name" />
                            </div>
                            <div class="form-group">
                                <label for="code-text" class="control-label"><?php echo lang("lbl_department_code"); ?></label>
                                <input type="text" name="code" placeholder="<?= lang('lbl_department_code')?>" class="form-control" id="edit-code">
                            </div>
                        <input type="hidden" name="id" value="" id="edit-dept-id"/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang("lbl_close"); ?></button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light"><?php echo lang("lbl_save"); ?></button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End Model -->
            <!-- Page Content start here -->
            <!--.row-->
            <div id="alert_cat" style="display: none;" class="alert  alert-dismissable"><button class="close" aria-hidden="true" type="button" data-dismiss="alert">×</button></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">

                        <div class="panel-wrapper collapse in" aria-expanded="true">
                            <div class="panel-body">

                                <ul class="nav customtab nav-tabs" role="tablist">
                                    <li role="presentation" class="nav-item"><a href="#emp-cat" class="nav-link <?php if($this->session->flashdata('hr_selected_tab')){ echo ''; } else { echo 'active'; }?>"
                                                                                aria-controls="profile" role="tab"
                                                                                data-toggle="tab"
                                                                                aria-expanded="true"><span
                                                    class="visible-xs"><i class="fa fa-user"></i></span><span
                                                    class="hidden-xs"><?php echo lang("tab_employee_categories"); ?></span></a>
                                    </li>
                                    <li role="presentation" class="nav-item"><a href="#emp-dept" class="nav-link <?php if($this->session->flashdata('hr_selected_tab')){ echo 'active'; } ?>"
                                                                                aria-controls="profile" role="tab"
                                                                                data-toggle="tab" aria-expanded="false"><span
                                                    class="visible-xs"><i class="fa fa-phone"></i></span> <span
                                                    class="hidden-xs">Employee Departments</span></a></li>

                                </ul>

                                <!--tab content start here-->

                                <div class="tab-content">
                                    <div class="tab-pane <?php if($this->session->flashdata('hr_selected_tab')){ echo ''; } else { echo 'active'; }?>" id="emp-cat">
                                        <div class="row">

                                            <div class="table-responsive col-md-12">
                                                <table id="myTable" class="table ">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th><?php echo lang("th_cat_name"); ?></th>
                                                        <th class="text-right "><?php echo lang("th_action"); ?></th>

                                                    </tr>
                                                    </thead>
                                                    <tbody id="categories">
                                                        <?php if(count($role_categories)>0){ foreach ($role_categories as $cat) {?>
                                                        <tr id="tr_<?php echo $cat->id; ?>">
                                                        <td><?php echo $cat->id; ?></td>
                                                        <td><?php echo $cat->category; ?></td>
                                                        <td class="text-right ">
                                                            <button type="button" class="btn btn-info btn-circle" onclick="editCategoryModal(<?php echo $cat->id; ?>)"><i class="fa fa-pencil"></i></button>
                                                            <button type="button" onclick="removeCategory(<?php echo $cat->id; ?>)" class="btn btn-danger btn-circle"><i class="fa  fa-trash-o"></i></button>
                                                        </td>
                                                    </tr>
                                                        <?php  } } ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="row p-l-20">
                                            <!--/row-->
                                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#add-category"><?php echo lang("btn_add_new_cat"); ?>
                                            </button>

                                        </div>

                                    </div>


                                     <div class="tab-pane <?php if($this->session->flashdata('hr_selected_tab')){ echo 'active'; }?>" id="emp-dept">
                                    <div class="row">
                                        <div class="table-responsive col-md-12">
                                            <table id="myTable" class="table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th><?php echo lang("lbl_department_name"); ?></th>
                                                        <th><?php echo lang("lbl_department_code"); ?></th>
                                                        <th class="text-right"><?php echo lang("lbl_action"); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(count($departments) > 0) { ?>
                                                        <?php $count=1; foreach($departments as $dept) { ?>
                                                        <tr>
                                                            <td><?php echo $count++; ?></td>
                                                            <td><?php echo $dept->name; ?></td>
                                                            <td><?php echo $dept->code; ?></td>
                                                            <td class="text-right">
                                                                <button type="button" data-toggle="modal" onclick="edit('<?php echo $dept->id; ?>')" class="btn btn-info btn-circle"><i class="fa fa-pencil"></i></button>
                                                                <a href="javascript:void(0)" value="<?php echo $dept->id; ?>" class="sa-warning btn btn-danger btn-circle text-white"><i class="fa  fa-trash-o"></i></a>
                                                            </td>
                                                        </tr>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                    <tr>
                                                        <td colspan="4"><?php echo lang("msg_department_not_exists"); ?></td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row p-l-20">
                                        <!--/row-->
                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#responsive-modal" class="model_img img-responsive"><?php echo lang("lbl_add_new_department")?></button>
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

        </div>
    </div>
    
    <!-- Category Modal Start 20-12-2017 By Shahzaib -->
    


<!-- Modal -->
<div id="add-category" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title"><?php echo lang("modal_add_cat_title"); ?></h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="school_id" id="school_id" value="<?php echo $UserData['sh_id'];?>"/>
                                                <div class="form-group">
                                                    <label for="name" class="control-label"><?php echo lang("modal_input_cat_name_lbl"); ?></label>
                                                    <input type="text" onkeyup="$(this).removeAttr('style');" class="form-control" id="category_name" name="category_name">
                                                </div>
                                                
                                       
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang("modal_select_role_lbl"); ?></label>
                                                <select class="form-control" name="role" id="role_id">
                                                    <?php if(count($roles)>0){ foreach ($roles as $role) { ?>
                                                    <option value="<?php echo $role->id;?>"><?php echo $role->name; ?></option>
                                                    <?php } } ?>
                                                </select> 
                                            </div>
                                       
                                                
                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang("modal_btn_close"); ?></button>
                                            <button type="button" onclick="addCategory()" class="btn btn-danger waves-effect waves-light"><?php echo lang("modal_btn_save"); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>


    <!-- Category Modal End 20-12-2017 By Shahzaib -->
    <!-- Modal -->
<div id="edit-category" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title"><?php echo lang("modal_edit_cat_title"); ?></h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="category_id" id="category_id" value=""/>
                                                <div class="form-group">
                                                    <label for="name" class="control-label"><?php echo lang("modal_input_cat_name_lbl"); ?></label>
                                                    <input type="text" class="form-control" onkeyup="$(this).removeAttr('style');" id="category_name_edit">
                                                </div>
                                                
                                       
                                            <div class="form-group">
                                                <label class="control-label"><?php echo lang("modal_select_role_lbl"); ?></label>
                                                <select class="form-control" name="role" id="role_id_edit">
                                                    <?php if(count($roles)>0){ foreach ($roles as $role) { ?>
                                                    <option value="<?php echo $role->id;?>"><?php echo $role->name; ?></option>
                                                    <?php } } ?>
                                                </select> 
                                            </div>
                                       
                                                
                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang("modal_btn_close"); ?></button>
                                            <button type="button" onclick="editCategory()" class="btn btn-danger waves-effect waves-light"><?php echo lang("modal_btn_update"); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
    <!-- /.container-fluid -->
     <?php include(APPPATH . "views/inc/footer.php"); ?>
    <script type="text/javascript">
    function addCategory(){
        var school_id = $("#school_id").val();
        var category = $("#category_name").val();
        var role_id = $("#role_id").val();
        if(category!=''){
        $.post("<?php echo site_url('settings/addCategory'); ?>", {school_id:school_id,category:category,role_id:role_id}).done(function(response){
            //alert(response);
            $("#categories").append('<tr id="tr_'+response+'"><td>'+response+'</td><td>'+category+'</td><td class="text-right"><button type="button" class="btn btn-info btn-circle" onclick="editCategoryModal('+response+')"><i class="fa fa-pencil"></i></button> <button type="button" onclick="removeCategory('+response+')" class="btn btn-danger btn-circle"><i class="fa  fa-trash-o"></i></button></td></tr>');
            $('#add-category').modal('hide');
            $('#alert_cat').addClass('alert-success').html('<?php echo lang("msg_cat_added"); ?>').fadeIn();
            setTimeout(function(){ $('#alert_cat').removeClass('alert-success').fadeOut(); }, 5000);
            
        });
        }else{
        $("#category_name").css("border", "2px solid red");
        }
    }
    
    function removeCategory(cat_id){
    
    var x = confirm("<?php echo lang("confirm_delete"); ?>");
  if(x){
      $.post("<?php echo site_url('settings/removeCategory'); ?>", {cat_id:cat_id}).done(function(response){
            $("#tr_"+cat_id).hide();
            $('#alert_cat').addClass('alert-danger').html('<?php echo lang("msg_cat_deleted"); ?>').fadeIn();
            setTimeout(function(){ $('#alert_cat').removeClass('alert-danger').fadeOut(); }, 5000);
        });
  }else{
    return false;
        }
    
    }
    
    function editCategoryModal(cat_id){
    $("#category_id").val(cat_id);
    //$("#tr_"+cat_id).hide();
     $.post("<?php echo site_url('settings/editCategory'); ?>", {cat_id:cat_id}).done(function(response){
       var data = JSON.parse(response);
            $("#category_name_edit").val(data.category);
            $("#role_id_edit").val(data.role_id);
            
        });
    $('#edit-category').modal('show');
    }
    
    function editCategory(){
        var cat_id = $("#category_id").val();
        var category = $("#category_name_edit").val();
        var role_id = $("#role_id_edit").val();
        if(category!=''){
        $.post("<?php echo site_url('settings/editCategorySucess'); ?>", {cat_id:cat_id,category:category,role_id:role_id}).done(function(response){
            if(response=='updated'){
            $("#tr_"+cat_id).html('<td>'+cat_id+'</td><td>'+category+'</td><td class="text-right"><button type="button" class="btn btn-info btn-circle" onclick="editCategoryModal('+cat_id+')"><i class="fa fa-pencil"></i></button> <button type="button" onclick="removeCategory('+cat_id+')" class="btn btn-danger btn-circle"><i class="fa  fa-trash-o"></i></button></td>');
            $('#edit-category').modal('hide');
            $('#alert_cat').addClass('alert-success').html('<?php echo lang("msg_cat_updated"); ?>').fadeIn();
            setTimeout(function(){ $('#alert_cat').removeClass('alert-success').fadeOut(); }, 5000);
        }
        });
        }else{
        $("#category_name_edit").css("border", "2px solid red");
        }
    }
    </script>
