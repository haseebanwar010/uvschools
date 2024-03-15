<!DOCTYPE html>
<html lang="en" <?php if($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')){ echo 'dir="ltr"';}else{    echo 'dir="rtl"';}?>>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" type="image/png" sizes="16x16" href="assets/plugins/images/favicon.png">
        <title><?php echo lang('new_password'); ?></title>
        <base href = "<?php echo base_url(); ?>" />
        <!-- Bootstrap Core CSS -->
        <link href="assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css" rel="stylesheet">
        <!-- animation CSS -->
        <link href="assets/css/animate.css" rel="stylesheet">
        <!-- Custom CSS -->
        <link href="assets/css/style.css" rel="stylesheet">
        <!-- color CSS -->
        <link href="assets/css/colors/default.css" id="theme" rel="stylesheet">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    </head>

    <body ng-app="myschool">
        <!-- Preloader -->
        <div class="preloader">
            <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
            </svg>
        </div>
        <section id="wrapper" ng-controller="loginCtrl" class="login-register" style="background:url(assets/plugins/images/login-register.jpg) center center/cover no-repeat!important;height:100%; width:100%; text-align: -webkit-center; padding-top:5%; position:fixed;">
            <div class="login-box login-sidebar" style="width:30%; background-color:white; height:370px; border-radius:10px;">
                <div class="white-box" style="margin:10% 5%;">
                    <form class="form-horizontal form-material" id="password_reset">
                        <a href="javascript:void(0)" class="text-center db"><img src="assets/plugins/images/web-logo-full.png" style="margin:20px" alt="Home"/>
                        <div class="form-group m-t-40">
                            <div class="col-xs-12">
                                <input class="form-control" name="password" type="password" required="" placeholder="<?php echo lang('enter_new_pswrd') ?>" style="height:48px">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" name="confirmpassword" type="password" required="" placeholder="<?php echo lang('confirm_new_pswrd') ?>" style="height:48px">
                            </div>
                        </div>
                        <input type="hidden" name="key" value="<?php echo $key ?>">
                        <div class="alert" id="reset_alert" style="display: none">
                            <!--<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>-->
                            
                        </div>
                        
                        
                        
                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" style="height:48px" id="change_btn" type="submit"><?php echo lang('password_reset') ?></button>
                            </div>
                               <div class="col-xs-12">
                                <a href="<?php echo site_url('login'); ?>" class="btn btn-default btn-lg btn-block text-uppercase waves-effect waves-light" style="height:48px; margin-top:2%;" id="change_btn1" ><?php echo lang('btn_cancel') ?></a>
                            </div>
                        </div>

                       
                    </form>
                  
                </div>
            </div>
        </section>

       
        <!-- Angular js -->
        <script src="assets/angularjs/angular.min.js"></script>
        <!-- Angular auto validator lib -->
        <script src="assets/angularjs-auto-validator/dist/jcs-auto-validate.min.js"></script>
        <!-- App js -->
        <script src="assets/js/app.js"></script>
        <!-- jQuery -->
        <script src="assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
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
            $(document).ready(function(){
                $('#change_btn').click(function(){
                    var formdata = $( '#password_reset').serialize() ;
                    event.preventDefault();
                    $.ajax({
                        type : 'POST',
                        data : formdata,
                        dataType: "json",
                        url : '<?php echo site_url('login/resetPassword/')?>',
                        success: function(response){
                            console.log(response);

                            if(response.success){
                                $('#reset_alert').removeClass('alert-danger').addClass('alert-success').html("<?php echo lang('success_message') ?>").show();
                                
                                window.location.href='<?php echo site_url($school.'/login') ?>';
                                
                            }
                            else{
                                $('#reset_alert').removeClass('alert-success').addClass('alert-danger').html(response.error).show();
                                
                            }
                            
                        }
            });
                })
            })
        </script>
        
    </body>
</html>
