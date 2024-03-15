<?php
$UserData = $this->session->userdata('userdata');
$role_id = $UserData['role_id'];
?>
<link href="assets/plugins/bower_components/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">
<div class="col-sm-12">
    <div class="white-box">        
        <div class="table-responsive">
            <table class="myTable display nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th><?php echo lang('imp_sr') ?></th>
                        <th><?php echo lang('lbl_avatar') ?></th>
                        <th><?php echo lang('lbl_name') ?></th>
                        <th><?php echo lang('lbl_guardian') ?></th>
                        <th><?php echo lang('lbl_phone') ?></th>
                        <th><?php echo lang('lbl_class') ?></th>
                        <th><?php echo lang('lbl_batch') ?></th>
                        <th><?php echo lang('subject_groups') ?></th>
                        <th><?php echo lang('lbl_rollno') ?></th>
                        <th><?php echo lang('lbl_teacher') ?></th>
                        <th><?php echo lang('lbl_status') ?></th>
                        <th><?php echo lang('lbl_action') ?></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th><?php echo lang('imp_sr') ?></th>
                        <th><?php echo lang('lbl_avatar') ?></th>
                        <th><?php echo lang('lbl_name') ?></th>
                        <th><?php echo lang('lbl_guardian') ?></th>
                        <th><?php echo lang('lbl_phone') ?></th>
                        <th><?php echo lang('lbl_class') ?></th>
                        <th><?php echo lang('lbl_batch') ?></th>
                        <th><?php echo lang('subject_groups') ?></th>
                        <th><?php echo lang('lbl_rollno') ?></th>
                        <th><?php echo lang('lbl_teacher') ?></th>
                        <th><?php echo lang('lbl_status') ?></th>
                        <th><?php echo lang('lbl_action') ?></th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    if (count($students) > 0) {
                        $count = 1;
                        foreach ($students as $std) {
                            ?>
                            <tr>
                                <td><?php echo $count; ?></td>
                                <td><img src="<?php echo base_url() ?>uploads/user/<?php echo $std->avatar; ?>" alt="user-img" class="img-circle" style="height: 60px;width: 60px"></td> 
                                <!--<td><img src="<?php echo base_url() ?>uploads/user/<?php echo $std->avatar; ?>" alt="user-img" class="img-circle" style="height: 60px;width: 60px"> <?php if($std->health_status=='Abnormal'){ ?> <img src="<?php echo base_url() ?>uploads/student_status/plus.png" style="width: 32% !important; display: flow-root; margin-left: 44px; margin-top: -19px;"><?php } ?></td> -->
                                <td><?php echo $std->name; ?></td>
                                <td><?php echo $std->father_name; ?></td>
                                <td><?php echo $std->contact; ?></td>
                                <td><?php echo $std->class_name; ?></td>
                                <td><?php echo $std->batch_name; ?></td>
                                <td><?php echo $std->group_name; ?></td>
                                <td><?php echo $std->rollno; ?></td>
                                <td><?php echo $std->teacher_name; ?></td>
                                <?php if ($std->status == '0') { ?>
                                    <td><?php echo lang('option_active') ?></td>
                                <?php } else if($std->status == '1'){ ?>
                                    <td><?php echo lang('option_disabled') ?></td>
                                <?php } ?>
                                <td>
                                    <?php
                                    $ci = & get_instance();
                                    $arr = $ci->session->userdata("userdata")['persissions'];
                                    $array = json_decode($arr);
                                    if (isset($array)) {
                                        $view = 0;
                                        $edit = 0;
                                        foreach ($array as $key => $value) {
                                            if (in_array('students-edit', array($value->permission)) && $value->val == 'true') {
                                                $edit = 1;
                                            }
                                            if (in_array('students-view', array($value->permission)) && $value->val == 'true') {
                                                $view = 1;
                                            }
                                        }
                                    }
                                    ?>
                                    <?php if (isset($role_id) && $role_id == 4 && isset($edit)) {?>
                                        <a type="button" href="#" data-toggle="modal" data-target="#compose" id="<?php echo $std->id; ?>" onclick="setStudentId(this.id)" class="btn btn-success btn-circle" style="background: #13dafe; border: #13dafe"; ><i class="fa fa-paper-plane" aria-hidden="true"></i></a> 
                                        <?php
                                        if ($edit == 1 || $view == 1) {
                                            ?> 
                                            <?php if ($view == 1) { ?>
                                                <a type="button" href="<?php echo base_url(); ?>students/view/<?php echo encrypt($std->id); ?>" target="_blank" class="btn btn-success btn-circle" ><i class="fa fa-eye"></i></a>
                                            <?php } if ($edit == 1) { ?> 
                                                <a type="button" href="<?php echo base_url(); ?>students/edit/<?php echo encrypt($std->id); ?>/<?php echo encrypt($std->class_id); ?>" target="_blank" class="btn btn-info btn-circle" ><i class="fa fa-pencil"></i></a>
                <?php } ?>
            <?php }
        } else if (isset($role_id) && $role_id == 1) { ?>
                                        <a type="button" href="#" data-toggle="modal" data-target="#compose" id="<?php echo $std->id; ?>" onclick="setStudentId(this.id)" class="btn btn-success btn-circle" style="background: #13dafe; border: #13dafe"><i class="fa fa-paper-plane" aria-hidden="true"></i></a> 
                                        <a type="button" href="<?php echo base_url(); ?>students/view/<?php echo encrypt($std->id); ?>" target="_blank" class="btn btn-success btn-circle" ><i class="fa fa-eye"></i></a>        
                                        <a type="button" href="<?php echo base_url(); ?>students/edit/<?php echo encrypt($std->id); ?>/<?php echo encrypt($std->class_id); ?>" target="_blank" class="btn btn-info btn-circle" ><i class="fa fa-pencil"></i></a>
                                        <a href="javascript:void(0)" value="<?php echo encrypt($std->id); ?>,students/delete" class="sa-warning btn btn-danger btn-circle text-white"><i class="fa  fa-trash-o"></i></a>
                            <?php } ?>
                                </td>
                            </tr>
        <?php $count++;
    }
} else { ?>
                        <tr><td colspan="12"><?php echo lang('no_record') ?></td></tr>
<?php } ?>
                </tbody>
            </table>
        </div>
    </div>
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
<script>
    function setStudentId(id){
        // alert(id)
        document.getElementById("hidden").value = id;
       
        document.getElementById("message_alert").value = "";
    }
</script>
