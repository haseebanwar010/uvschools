<?php 
$UserData = $this->session->userdata('userdata');
$role_id = $UserData['role_id'];
?>
<link href="assets/plugins/bower_components/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">
<div class="table-responsive">
    <table class="myTable display nowrap" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th><?php echo lang('imp_sr') ?></th>
                <th><?php echo lang('lbl_avatar') ?></th>
                <th><?php echo lang('heading_name') ?></th>
                <th><?php echo lang('heading_position') ?></th>
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
                        <?php if(isset($role_id) && $role_id == 4 && isset($edit) || isset($view)){
                            if($view ==1 ){?>
                                <a type="button" href="<?php echo site_url();?>employee/view?id=<?php echo encrypt($employee->id);?>" target="_blank" class="btn btn-success btn-circle" ><i class="fa fa-eye"></i></a>
                            <?php } if($edit ==1 ){?>
                                <a type="button" href="<?php echo site_url();?>employee/edit?id=<?php echo encrypt($employee->id);?>" target="_blank" class="btn btn-info btn-circle" ><i class="fa fa-pencil"></i></a>
                            <?php }} else if(isset($role_id) ){
                                if($role_id == 1){?>
                                    <a type="button" href="<?php echo site_url();?>employee/view?id=<?php echo encrypt($employee->id);?>" target="_blank" class="btn btn-success btn-circle" ><i class="fa fa-eye"></i></a>
                                    <a type="button" href="<?php echo site_url();?>employee/edit?id=<?php echo encrypt($employee->id);?>" target="_blank" class="btn btn-info btn-circle" ><i class="fa fa-pencil"></i></a>
                                    <a href="javascript:void(0)" value="<?php echo encrypt($employee->id); ?>,employee/delete" class="sa-warning btn btn-danger btn-circle text-white"><i class="fa  fa-trash-o"></i></a>
                                <?php }}?>
                            </td>
                        </tr>
                        <?php
                        $count++;
                    }
                } else {
                    ?>
                    <tr><td colspan="6" style="text-align:center;"><?php echo lang('no_record') ?></td></tr>
                <?php }
                ?>

            </tbody>
        </table>
    </div>

<script>
    $('.myTable').DataTable({
        // dom: 'Bfrtip',
        buttons: [

        ],
        "language": {

            "decimal": "",
            "emptyTable": '<?php echo lang("no_data_table"); ?>',
            "info": '<?php echo lang("data_info"); ?>',
            "infoEmpty": '<?php echo lang("infoempty"); ?>',
            "infoFiltered": '<?php echo lang("filter_datatable"); ?>',
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": '<?php echo lang("show_datatable"); ?>',
            "loadingRecords": '<?php echo lang("loading_datatable"); ?>',
            "processing": '<?php echo lang("processing_datatable"); ?>',
            "search": '<?php echo lang("search"); ?>:',
            "zeroRecords": '<?php echo lang("no_record_datatable"); ?>',
            "paginate": {
                "first": '<?php echo lang("first"); ?>',
                "last": '<?php echo lang("last"); ?>',
                "next": '<?php echo lang("btn_next"); ?>',
                "previous": '<?php echo lang("previous"); ?>'
            },
            "aria": {
                "sortAscending": ": activate to sort column ascending",
                "sortDescending": ": activate to sort column descending"
            }
        }
    });
</script>
<script src="assets/plugins/bower_components/sweetalert/sweetalert.min.js"></script>  
<script src="assets/plugins/bower_components/sweetalert/jquery.sweet-alert.custom.php"></script>
