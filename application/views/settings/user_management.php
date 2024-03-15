<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<style>
    .myactive>a>.card{
        background: <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>;
    }
    .myactive>a>.card>i{
        color: white;
    }
    .myactive>a>.card>.card-body>.counter{
        color: white !important;
    }
    .myactive>a>.card>.card-body>.custom-wrap{
        color: white !important;
    }
    #myTablee_users td.details-control {
        background: url('<?php echo base_url(); ?>assets/images/plus-circle.png') no-repeat center center;
        cursor: pointer;
        background-size: 24px 24px;
    }
    #myTablee_users tr.shown td.details-control {
        background: url('<?php echo base_url(); ?>assets/images/minus-circle.png') no-repeat center center;
        background-size: 24px 24px;
    }
</style>

    <!-- permissions -->
    <?php 
        $role_id = $ci->session->userdata("userdata")['role_id'];
        $fechUserById = "";
        if($role_id == '4'){
            $ci = & get_instance();
            $arr = $ci->session->userdata("userdata")['persissions'];
            $array = json_decode($arr);
            if (isset($array)) {
                $viewEmployees = $viewStudents = $viewGuardians = $changeEmpStatus = $changeStdStatus = $changeGuardianStatus = 0;
            foreach ($array as $key => $value) {
                    
                    if (in_array('manage-viewGuardians', array($value->permission)) && $value->val == 'true') {
                        $viewGuardians = '1';
                        $fechUserById = PARENT_ROLE_ID;
                    }
                    if (in_array('manage-changeGuardianStatus', array($value->permission)) && $value->val == 'true') {
                        $changeGuardianStatus = '1';
                        $fechUserById = PARENT_ROLE_ID;
                    }
                    if (in_array('manage-viewEmployees', array($value->permission)) && $value->val == 'true') {
                        $viewEmployees = '1';
                        $fechUserById = EMPLOYEE_ROLE_ID;
                    }
                    if (in_array('manage-changeEmpStatus', array($value->permission)) && $value->val == 'true') {
                        $changeEmpStatus = '1';
                        $fechUserById = EMPLOYEE_ROLE_ID;
                    }
                    if (in_array('manage-viewStduents', array($value->permission)) && $value->val == 'true') {
                        $viewStudents = '1';
                        $fechUserById = STUDENT_ROLE_ID;
                    }
                    if (in_array('manage-changeStdStatus', array($value->permission)) && $value->val == 'true') {
                        $changeStdStatus = '1';
                        $fechUserById = STUDENT_ROLE_ID;
                    }
           }
        }
    }else if($role_id == '1'){
        $fechUserById = STUDENT_ROLE_ID;
    } ?>


	<!-- Page content -->
	<div id="page-wrapper" ng-controller="userManagementCrtl" ng-init="getUsers(<?php echo $fechUserById; ?>)">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?php echo lang('lbl_user_management') ?></h4>
                </div>
            </div>
            <!--<div class="hint"><?php //echo lang('help_user_management'); ?></div>-->
        </div>

        <div class="row">
            <script>
                $(window).resize(function(){
                    if($(window).width()<500) {
                        $('#umMainDiv').removeClass('d-flex');
                        $('#umMainDiv').removeClass('justify-content-center');
                        $(".card").css({"margin-right": "6px"});
                    } else {
                        $('#umMainDiv').addClass('d-flex');
                        $('#umMainDiv').addClass('justify-content-center');
                    }
                });
            </script>
            <div class="col-md-12 d-flex justify-content-center col-12" id="umMainDiv">
                <?php if ($role_id == '1' || ($role_id == '4' && ($viewStudents == '1' || $changeStdStatus == '1'))) {  ?>
                <div class="col-md-2 col-12" id="studentTab">
                    <a href="javascript:void(0);" ng-click="getUsers(<?php echo STUDENT_ROLE_ID; ?>)">
                        <div class="card text-center btn btn-default btn-sm" style="padding-top: 15px;padding-bottom: 10px;border: 2px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px;">
                            <i class="fa fa-users fa-5x"></i>
                            <div class="card-body" style="padding-top: 0;">
                                <h3 class="counter" style="margin-bottom: 0; color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>;"><b><?php echo $student_count ?></b></h3>
                                <span class="text-muted custom-wrap"><?php echo lang('total_students') ?></span>
                            </div>
                        </div>
                    </a>
                </div>
                 <?php } ?>
            <?php if ($role_id == '1' || ($role_id == '4' && ($viewEmployees == '1' || $changeEmpStatus == '1'))) {
                ?>
                
                <div class="col-md-2 col-12" id="employeeTab">
                    <a href="javascript:void(0);" ng-click="getUsers(<?php echo EMPLOYEE_ROLE_ID; ?>)">
                        <div class="card text-center btn btn-default btn-sm" style="padding-top: 15px;padding-bottom: 10px;border: 2px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px;">
                            <i class="fa fa-graduation-cap fa-5x"></i>
                            <div class="card-body" style="padding-top: 0;">
                                <h3 class="counter" style="margin-bottom: 0; color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>;"><b><?php echo $employee_count ?></b></h3>
                                <span class="text-muted custom-wrap"><?php echo lang('total_emploeyees') ?></span>
                            </div>
                        </div>
                    </a>
                </div>
                <?php } ?>
            <?php if ($role_id == '1' || ($role_id == '4' && ($viewGuardians == '1' || $changeGuardianStatus == '1'))) {?>
                <div class="col-md-2 col-12" id="parentTab">
                    <a href="javascript:void(0);" ng-click="getUsers(<?php echo PARENT_ROLE_ID; ?>)">
                        <div class="card text-center btn btn-default btn-sm" style="padding-top: 15px;padding-bottom: 10px;border: 2px solid <?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>; border-radius: 10px;">
                            <i class="fa fa-user fa-5x"></i>
                            <div class="card-body" style="padding-top: 0;">
                                <h3 class="counter" style="margin-bottom: 0; color:<?php echo '#'.substr($this->session->userdata('userdata')['theme_color'], 1); ?>;"><b><?php echo $parent_count ?></b></h3>
                                <span class="text-muted custom-wrap"><?php echo lang('total_parents') ?></span>
                            </div>
                        </div>
                    </a>
                </div>
                  <?php }?>
            </div>
        </div>

        <div class="container-fluid hide" id="tableContainer">
            <div class="row">
                <div class="col-sm-12">
                    <div class="white-box">
                        <div class="table-responsive">
                            <script>
                                function format ( d ) {
                                    if(d.role_id == <?php echo STUDENT_ROLE_ID; ?>){
                                        return '<div class="row">'+
                                            '<div class="col-md-3"><div class="form-group"><img lass="form-control" style="width: 100px;" src="<?php echo base_url(); ?>uploads/user/'+d.avatar+'"></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_name") ?></label><p>'+d.name+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_gender") ?></label><p>'+d.gender+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_dob") ?></label><p>'+d.dob+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("blood_group") ?></label><p>'+d.blood+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("birth_place") ?></label><p>'+d.birthplace+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_nationality") ?></label><p>'+d.nationality+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_religion") ?></label><p>'+d.religion+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_roll_no") ?></label><p>'+d.rollno+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_city") ?></label><p>'+d.city+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_course") ?></label><p>'+d.class_name+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_address") ?></label><p>'+d.address+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_email") ?></label><p>'+d.email+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_phone") ?></label><p>'+d.contact+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("father_name") ?></label><p>'+d.guardian_name+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("imp_parent_contact") ?></label><p>'+d.guardian_contact+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_Guardian_Relation_prt") ?></label><p>'+d.guardian_relation+'</p></div></div>'+
                                        '</div>';
                                    } else if(d.role_id == <?php echo EMPLOYEE_ROLE_ID; ?>){
                                        return '<div class="row">'+
                                            '<div class="col-md-3"><div class="form-group"><img lass="form-control" style="width: 100px;" src="<?php echo base_url(); ?>uploads/user/'+d.avatar+'"></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_name") ?></label><p>'+d.name+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_gender") ?></label><p>'+d.gender+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_dob") ?></label><p>'+d.dob+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("blood_group") ?></label><p>'+d.blood+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("birth_place") ?></label><p>'+d.birthplace+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_nationality") ?></label><p>'+d.nationality+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_religion") ?></label><p>'+d.religion+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("heading_position") ?></label><p>'+d.job_title+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_city") ?></label><p>'+d.city+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("title_category") ?></label><p>'+d.role_category+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_address") ?></label><p>'+d.address+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_email") ?></label><p>'+d.email+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_phone") ?></label><p>'+d.contact+'</p></div></div>'+
                                        '</div>';
                                    } else if(d.role_id == <?php echo PARENT_ROLE_ID?>){
                                        return '<div class="row">'+
                                            '<div class="col-md-3"><div class="form-group"><img lass="form-control" style="width: 100px;" src="<?php echo base_url(); ?>uploads/user/'+d.avatar+'"></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_name") ?></label><p>'+d.name+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_gender") ?></label><p>'+d.gender+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_dob") ?></label><p>'+d.dob+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("blood_group") ?></label><p>'+d.blood+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("birth_place") ?></label><p>'+d.birthplace+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_nationality") ?></label><p>'+d.nationality+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_religion") ?></label><p>'+d.religion+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_city") ?></label><p>'+d.city+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_address") ?></label><p>'+d.address+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_email") ?></label><p>'+d.email+'</p></div></div>'+
                                            '<div class="col-md-3"><div class="form-group"><label><?php echo lang("lbl_phone") ?></label><p>'+d.contact+'</p></div></div>'+
                                        '</div>';
                                    }
                                }
                            </script>
                            <table id="myTablee_users" class="display nowrap" cellspacing="0" width="100%"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page content end here -->

<?php include(APPPATH . "views/inc/footer.php"); ?>