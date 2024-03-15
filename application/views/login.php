<!DOCTYPE html>
<html lang="en" <?php if($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')){ echo 'dir="ltr"';}else{    echo 'dir="rtl"';}?>>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/plugins/images/favicon.png">
    <title><?php echo lang('btn_login'); ?></title>
    <base href = "<?php echo base_url(); ?>" />
    <!-- Bootstrap Core CSS -->
    <link href="assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css" rel="stylesheet">
    <!-- animation CSS -->
    <link href="assets/css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/style.php?color=''" rel="stylesheet">
    <!-- color CSS -->
    <link href="assets/css/colors/default.css" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
        <!-- jQuery -->
        <script src="assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
        <!-- Loading overlay -->
        <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@1.5.4/src/loadingoverlay.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@1.5.4/extras/loadingoverlay_progress/loadingoverlay_progress.min.js"></script>  
    </head>
    <body ng-app="myschool">
        <!-- Preloader -->
        <div class="preloader">
            <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
            </svg>
        </div>
        <section id="wrapper" ng-controller="loginCtrl" class="login-register" style="background:url(assets/plugins/images/login-register.jpg) center center/cover no-repeat!important;height:100%;position:fixed;">
            <div class="login-box login-sidebar" id="loginDiv">
                <div class="white-box" style="border-top: 9px solid #7B1FA2; border-radius:4px;" ng-init="formModel.sh_id = <?php echo $id ?>">
                    <form class="form-horizontal form-material" ng-submit="onSubmit(loginForm.$valid)" name="loginForm"  id="loginform" novalidate="">
                        <a href="javascript:void(0)" class="text-center db">
                            <img src="uploads/logos/<?php echo $logo; ?>" alt="Home" style="max-height:100px;"/>
                            <h2><?php echo $name; ?></h2>
                        </a>
                        <?php echo $this->session->flashdata('success_activation'); ?>
                        <div class="form-group m-t-40">
                            <div class="col-xs-12">
                                <input class="form-control" name="email" type="text" ng-model="formModel.email" required="" placeholder="<?php echo lang('plc_email_login'); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" name="password" type="password" ng-model="formModel.password" required="" placeholder="<?php echo lang('plc_password'); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <!--                                <div class="checkbox checkbox-primary pull-left p-t-0">
                                                                    <input id="checkbox-signup" name="rememberme" ng-model="formModel.rememberme" type="checkbox">
                                                                    <label for="checkbox-signup"> <?php echo lang('lbl_remember_me'); ?> </label>
                                                                </div>-->
                                <a href="javascript:void(0)" id="to-recover" class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> <?php echo lang('lbl_forgot_pwd'); ?></a> 
                            </div>
                        </div>

                        <div class="alert {{alert.class}}" ng-show="alert.hasMessage">
                            <!--<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>-->
                            <strong>{{alert.title}} </strong> {{ alert.message }}  
                        </div>

                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light login-btn" type="submit"><?php echo lang('btn_login'); ?></button>
                            </div>
                        </div>

                        <!--                            <div class="form-group m-b-0">
                                                        <div class="col-sm-12 text-center">
                                                            <p><?php echo lang('lbl_dont_have_account'); ?> <a href="signup" class="text-primary m-l-5"><b><?php echo lang('lbl_sign_up'); ?></b></a></p>
                                                        </div>
                                                    </div>-->
                        <a href="javascript:void(0)" class="text-center db"><img src="assets/plugins/images/web-logo-full.png" alt="Home"/></a>
                    </form>
                    <div id="recoverformdiv">
                        <form class="form-horizontal" id="recoverform">
                            <div class="form-group ">
                                <div class="col-xs-12">
                                    <h3><?php echo lang('lbl_recover_password'); ?></h3>
                                    <p class="text-muted"><?php echo lang('recovery_password_page_description'); ?> </p>
                                </div>
                            </div>
                            <input type="hidden" id="sh_url" value="<?php echo $sh_url ?>">
                            <div class="form-group ">
                                <div class="col-xs-12">
                                    <input class="form-control" type="text" id="reset_email" placeholder="<?php echo lang('plc_email'); ?>">
                                </div>
                            </div>

                            <div class="alert" id="reset_alert" style="display: none">
                                <!--<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>-->

                            </div>

                            <div class="form-group text-center m-t-20">
                                <div class="col-xs-12">
                                    <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" id="reset_btn" type="submit"><?php echo lang('lbl_reset'); ?></button>
                                    <button class="btn btn-default btn-lg btn-block text-uppercase waves-effect waves-light" id="to-login" type="button"><?php echo lang('btn_cancel'); ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- Angular js -->
        <script src="assets/angularjs/angular.min.js"></script>
        <!-- Angular auto validator lib -->
        <script src="assets/angularjs-auto-validator/dist/jcs-auto-validate.min.php"></script>
        <!-- App js -->
        <script src="assets/js/app.php?v=<?php echo date("h.i.s"); ?>"></script>
        <!-- jQuery -->
        <!--<script src="assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>-->
        <!-- Bootstrap Core JavaScript -->
        <script src="assets/bootstrap/dist/js/tether.min.js"></script>
        <script src="assets/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="assets/plugins/bower_components/bootstrap-extension/js/bootstrap-extension.min.js"></script>
        <!-- Menu Plugin JavaScript -->
        <script src="assets/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
        <!--slimscroll JavaScript -->
        <script src="assets/js/jquery.slimscroll.js"></script>
        <!--Wave Effects -->
        <script src="assets/js/waves.js"></script>
        <!-- Custom Theme JavaScript -->
        <script src="assets/js/custom.min.js"></script>
        <!--Style Switcher -->
        <script src="assets/plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#reset_btn').click(function () {
                    $(this).attr("disabled", "disabled");
                    Loading("#loginDiv", "Loading", "", "show");
                    if (!isEmail($('#reset_email').val())) {
                        Loading("#loginDiv", "Loading", "", "hide");
                        $('#reset_btn').removeAttr("disabled");
                        $('#reset_alert').removeClass('alert-success').addClass('alert-danger').html("<?php echo lang('invalid_email') ?>").show();
                    } else {
                        $.ajax({
                            type: 'POST',
                            data: {email: $('#reset_email').val(), school: $('#sh_url').val()},
                            dataType: "json",
                            url: '<?php echo site_url('login/recovery/') ?>',
                            success: function (response) {
                                if (response.success) {
                                    Loading("#loginDiv", "Loading", "", "hide");
                                    $('#reset_alert').removeClass('alert-danger').addClass('alert-success').html("<?php echo lang('recovery_mail_sent') ?>").show();

                                } else {
                                    Loading("#loginDiv", "Loading", "", "hide");
                                    $('#reset_btn').removeAttr("disabled");
                                    $('#reset_alert').removeClass('alert-success').addClass('alert-danger').html("<?php echo lang('email_not_exist') ?>").show();
                                }
                            }
                        });
                    }
                })
            })
            function isEmail(email) {
                var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                return regex.test(email);
            }
        </script>
    </body>
</html>
