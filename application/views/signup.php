<!DOCTYPE html>
<html lang="en" <?php if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {
                    echo 'dir=""';
                } else {
                    echo 'dir="rtl"';
                } ?>>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/plugins/images/favicon.png">
    <title><?php echo lang('lbl_sign_up'); ?></title>

    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js" integrity="sha512-DNeDhsl+FWnx5B1EQzsayHMyP6Xl/Mg+vcnFPXGNjUZrW28hQaa1+A4qL9M+AiOMmkAhKAWYHh1a+t6qxthzUw==" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" integrity="sha512-gxWow8Mo6q6pLa1XH/CcH8JyiSDEtiwJV78E+D+QP0EVasFs8wKXq16G8CLD4CJ2SnonHr4Lm/yY2fSI2+cbmw==" crossorigin="anonymous" />
    <script src="assets/loading/loadingoverlay.min.js?v=<?php echo date("his");?>"></script>
    <script src="assets/loading/loadingoverlay_progress.min.js?v=<?php echo date("his");?>"></script>
    <?php if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                     <link href="assets/css/signup.css?v=<?php echo date("his");?>" rel="stylesheet"> 
               <?php } else {?>
                    <link href="assets/css/signup-rtl.css?v=<?php echo date("his");?>" rel="stylesheet">
                <?php } ?>
   
    
    <script>
        $(document).ready(function() {
            // $(".error3").hide();
            $(".error2").hide();
            var current_fs, next_fs, previous_fs;
            var left, opacity, scale;
            var animating;
            $(".steps").validate({
                errorClass: 'invalid',
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.insertAfter(element.next('span').children());
                },
                highlight: function(element) {
                    $(element).next('span').show();
                },
                unhighlight: function(element) {
                    $(element).next('span').hide();
                }
            });
            $("#next1").click(function() {
               
                $(".error3").hide();
                const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                
                if($('#email').val()!="" && re.test($('#email').val().toLowerCase())){
                    var $this = $(this);
                    var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Sending...';
                    var error = "false"
                    $.ajax({
                        type: 'POST',
                        data: {
                             email: $('#email').val()
                            },
                        dataType: "json",
                        async: false,
                        url: '<?php echo site_url('login/VerifyEmail')  ?>',
                        success: function (response) {
                            // alert(response['code'])
                            if(response['status']=="success"){
                            console.log(response['code'],"hello");
                            sessionStorage.setItem("code", response['code']);
                            }else{
                                error = "true";
                               
                                $(".error3").show();
                            }
                            }
                        });
                  if(error=="false"){
                if ($(this).html() !== loadingText) {
                $this.data('original-text', $(this).html());
                $this.html(loadingText);
                }
                
                        setTimeout(function() {
                                $this.html($this.data('original-text'));
                                }, 2000);
                        //clear session after 5 min after function call
                      setTimeout(function() { sessionStorage.clear(); }, (10 * 60 * 1000));
                $(".steps").validate({
                    errorClass: 'invalid',
                    errorElement: 'span',
                    errorPlacement: function(error, element) {
                        error.insertAfter(element.next('span').children());
                    },
                    highlight: function(element) {
                        $(element).next('span').show();
                    },
                    unhighlight: function(element) {
                        $(element).next('span').hide();
                    }
                });
              
             
                animating = true;
                current_fs = $(this).parent();
                next_fs = $(this).parent().next();
                $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
                setTimeout(function() {
                    next_fs.show();
                    $(".firststep").hide();
                                }, 2000);
                }
                }
            });
            $('#next2').click(function(e) {
            e.preventDefault();
            $(".error2").hide();
            let code = $('#code').val()
           //alert (sessionStorage.getItem("code"))

            if(code == sessionStorage.getItem("code")){
                current_fs = $(this).parent();
                next_fs = $(this).parent().next();
                email = $("#email").val();
                $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
                $(".lastemail").val(email);
                    next_fs.show();
                    $(".scndstep").hide();
                    
                              
            }else{
                // document.getElementsByClassName("erro1").style.display = "initial";
                $(".error2").show();
            }
        });
        $('#previous').click(function() {
            // alert("hhh")
            location.reload();
                
                });
           
                
                });
    </script>
</head>

<body ng-app="myschool" class="frame">


    <form class="steps " accept-charset="UTF-8" ng-controller="signupCtrl"  enctype="multipart/form-data" novalidate="">
        <?php if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
            <ul id="progressbar" class="jumbotron" >
                <li class="active"><?= lang('signup_enter_email');?></li>
                <li><?= lang('signup_verify_email');?></li>
                <li><?= lang('signup_register_signup');?></li>
            </ul>
        <?php } else {?>
            <ul id="progressbar" class="jumbotron" reversed>
                <li class="active"><?= lang('signup_enter_email');?></li>
                <li><?= lang('signup_verify_email');?></li>
                <li><?= lang('signup_register_signup');?></li>
            </ul>
        <?php } ?>
        


        <!-- Step One Enter Email -->
        <fieldset class="firststep">
            <h2 class="fs-title"><?= lang('signup_enter_your_email_here');?></h2>
            <h3 class="fs-subtitle"><?= lang('signup_authentic_email');?></h3>


            <div class="hs_email field hs-form-field" id="sigup-email-label">

                <label for="email"><?= lang('signup_whats_your_email');?></label>

                <input id="email" name="email" required="required" type="email" value="" placeholder="" data-rule-required="true" data-msg-required="<?= lang('signup_enter_valid_email');?>">
                <span class="error1" style="display: none;">
                    <i class="error-log fa fa-exclamation-triangle"></i>
                </span>
                <span class="error3">
                    <i class="error-log fa fa-exclamation-triangle" style="color: red;"><?= lang('signup_email_already_register');?></i>
                </span>
            </div>
            <button type="button" id="next1" data-page="1" name="next" class="btn btn-primary btn-lg next action-button"><?= lang('signup_send_code');?></button>
            <!-- <input type="button" data-page="1" name="next" class="next btn btn-primary btn-lg" value="Send Code" /> -->

        </fieldset>


        <!-- Step Two Enter Code to Verify Email -->
        <fieldset class="scndstep">
            <h2 class="fs-title"><?= lang('signup_enter_varification_code');?></h2>
            <h3 class="fs-subtitle"><?= lang('signup_varification_code_send');?></h3>

            <div class="form-item webform-component field hs-form-field" id="webform-component-acquisition--amount-2">

                <label for="code "><?= lang('signup_enter_varification_code');?>*</label>

                <input id="code" class="form-text hs-input" name="code"  onchange="rmverror2()"   value="" placeholder="" >
                <span class="error2" >
                    <i class="error-log fa fa-exclamation-triangle"><?= lang('signup_invalid_varification');?></i>
                </span>
            </div>

            <input type="button" id="previous" data-page="2" name="previous" class="previous action-button" value="<?= lang('previous');?>"  />
            <input type="button" id="next2" data-page="2" name="next" class="next action-button " value="<?= lang('btn_next');?>" />

        </fieldset>



        <!-- Step Three Register -->
        <fieldset class="thrdstep">
            
            
            <form class="form-horizontal form-material" ng-controller="signupCtrl" ng-submit="register(signupForm.$valid)" name="signupForm" >
                    <div class="text-center db">
                        <a href="<?php echo base_url() ?>">
                            <img src="assets/plugins/images/web-logo-full.png" alt="Home"/>
                        </a>
                        <h3 class="box-title m-t-40 m-b-0"><?php echo lang('lbl_register'); ?></h3>
                        <small><?php echo lang('lbl_create_account'); ?></small>
                    </div>

                        <div class="form-group">
                            <div class="input-group row">
                                <span class="input-group-addon col-sm-5" id="basic-addon3" style="background:transparent; height:42px;padding-top:12px; padding-left:32px; border-left:0;border-top:0;">https://uvschools.com/</span>
                                <input pattern="[a-zA-Z]+" ng-pattern-restrict class="col-sm-7 uniqueurl" name="schoolurl" ng-model="formModel.schoolurl" type="text" required="" placeholder="<?php echo lang('plc_school_url'); ?>" minlength="3" maxlength="6">
                            </div>
                            <small ng-if="formModel.schoolurl" style="margin-left: 15px;">https://uvschools.com/{{formModel.schoolurl}}</small>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control schoolname" name="schoolname" ng-model="formModel.schoolname" type="text" required="" placeholder="<?php echo lang('plc_school_name'); ?>">
                            </div>
                        </div>

                        <div class="form-group m-t-20">
                            <div class="col-xs-12">
                                <input class="form-control yourname" name="yourname" ng-model="formModel.yourname" type="text" required="" placeholder="<?php echo lang('plc_your_name'); ?>">
                            </div>
                        </div>
                       
                        
                        <div class="form-group">
                            <div class="col-xs-12" style="margin-bottom:20px;" >
                                <select name="time_zone" required="" ng-model="formModel.country" class="form-control countrySelect"  onchange="getVal(this.value)">
                                    <option></option>
                                    <?php foreach ($countries as $zone1) { ?>
                                        
                                    <option value="<?= $zone1->country_code; ?>" ><?= $zone1->country_name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" >
                            <div class="col-xs-12" id="remove_instance" style="margin-bottom: 60px;">
                                <input class="form-control" type="text" name="phone" ng-model="formModel.phone"  id="phone" required=""  placeholder="<?php echo lang('lbl_phone_number'); ?>" >
                              
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-xs-12" style="margin-bottom:20px;" >
                                <select name="time_zone" required="" ng-model="formModel.timezone" class="form-control timezoneSelect" >
                                    <option></option>
                                    <?php foreach ($timezones as $zone) { ?>
                                    <option value="<?= $zone; ?>" ><?= $zone; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control lastemail" disabled name="email" ng-model="formModel.email" type="email" required="" placeholder="<?php echo lang('plc_your_email'); ?>">
                            </div>
                        </div>
                       
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control password" name="password" ng-model="formModel.password" type="password" required="" placeholder="<?php echo lang('plc_password'); ?>" ng-minlength="8">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control cnfrmpassword" name="confirmpassword" confirm-password="formModel.password" ng-model="formModel.confirmpassword" type="password" required="" placeholder="<?php echo lang('plc_confirm_password'); ?>">
                            </div>
                        </div>

                        <div ng-show="alert.hasMessage">
                            <div class="alert {{alert.class}}">
                               <!-- <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a> -->
                               {{ alert.message }}  
                           </div>
                            <div ng-show="alert.class=='success'">
                           <button class="btn btn-success btn-lg btn-block text-uppercase waves-effect waves-light" ng-click="resendEmail()" type="button"><?= lang('signup_resend_email');?></button>
                            </div>
                        </div>
                    
                       <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button ng-disabled="isLoading" class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" id="subbtn" onclick="register()" type="submit"><?php echo lang('lbl_sign_up'); ?></button>
                            <button class="btn btn-danger btn-lg btn-block text-uppercase waves-effect waves-light" onclick="rel()" type="button"><?= lang('btn_cancel');?></button>
                            <!-- <button class="btn btn-success btn-lg btn-block text-uppercase waves-effect waves-light" ng-click="register()" type="button">Resend Email</button> -->
                            <div class="alert alert-success alert-white rounded" id="success">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <div class="icon"><i class="fa fa-check"></i></div>
                            <strong><?= lang('success_app');?>!</strong> 
                            </div>
                            <div class="alert alert-danger alert-white rounded" id="error">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <div class="icon"><i class="fa fa-times-circle"></i></div>
                            <strong><?= lang('error_app');?>!</strong> 
                        </div>
                        </div>
    
                    </div>
                    <div class="form-group m-b-0">
                        <div class="col-xs-12 text-center">
                            <p><?php echo lang('lbl_already_have'); ?> <a href="login" class="text-primary m-l-5"><b><?php echo lang('lbl_sign_in'); ?></b></a></p>
                        </div>
                    </div>
                </form>
        </fieldset>
    </form>
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
    <link href="assets/plugins/bower_components/select2files/dist/css/select2.min.css" rel="stylesheet" />
    <script src="assets/plugins/bower_components/select2files/dist/js/select2.min.js"></script>
    <!-- Start::Select to for timezone and country -->
    <script type="text/javascript">
    function rel(){
        location.reload();
    }
       function getVal(value){
                
            var globalval="";
                document.getElementById('remove_instance').textContent = '';
                document.getElementById('remove_instance').innerHTML = '<div onchange="getphone()"><input class="form-control" style="width:450px;" type="text" name="phone" id="phone"  ng-model="formModel.phone" required=""  ></div>';
            globalval= value;
            var input = document.querySelector("#phone");
            var iti = window.intlTelInput(input, {
            initialCountry: globalval,
            separateDialCode:true,
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/utils.js",
            });
            console.log('yup');
            console.log(iti);
            }
            function getphone(){
               
                console.log(code+" "+phone);
            }
            function rmverror2(){
                    $(".error2").hide();
                }
            function register(){
                document.getElementById("subbtn").disabled = true;
                var code = $(".iti__selected-dial-code").text();
                var phone1 = $("#phone").val();
                var phone = code+" "+phone1;
                if($('.cnfrmpassword').val()=="" || $('.uniqueurl').val()=="" || $('.timezoneSelect').val()=="" || $("#phone").val()=="" || $('.countrySelect option:selected').text()=="" || $('.schoolname').val()=="" || $('.yourname').val()==""){
                    alert("Please Fill all the fields");
                    document.getElementById("subbtn").disabled = false;
                }else if($('.password').val()==$('.cnfrmpassword').val()){
                $.ajax({
                            type: 'POST',
                            data: {
                                country: $('.countrySelect option:selected').text() ,
                                schoolurl: $('.uniqueurl').val().toLowerCase(),
                                schoolname: $('.schoolname').val(),
                                yourname:$('.yourname').val() ,
                                timezone: $('.timezoneSelect').val() ,
                                email: $('#email').val(),
                                phone: phone ,
                                password: $('.password').val()
                            },
                            dataType: "json",
                            url: '<?php echo site_url('login/register') ?>',
                            success: function (response) {
                              
                               console.log(response);
                               if(response.status=="success"){
                                //    $("#success").text(response.message);
                                //    $("#success").show();
                                $("#error").hide();
                                window.location.replace("<?php echo site_url('login'); ?>");
                               }else if(response.status=="error"){
                                document.getElementById("subbtn").disabled = false;
                                   $("#error").text(response.message);
                                   $("#error").show();
                               }
                               
                              
                            }
                        });
                }else{
                    alert("Password doesn't match");
                    document.getElementById("subbtn").disabled = false;
                }
            }
        $(document).ready(function() {
            $("#success").hide();
            $("#error").hide()
            // var intlNumber = $("#phone").value;
            // console.log(intlNumber);
            $('.countrySelect').select2({
                placeholder: '<?php echo lang("lbl_country"); ?>'

            });
            $('.timezoneSelect').select2({
                placeholder: '<?php echo lang("lbl_time_zone"); ?>'

            });
        });
    </script>
    <!-- End::Select to for timezone and country -->
    <script src="assets/plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-recaptcha/4.2.0/angular-recaptcha.min.js" integrity="sha512-54HkJKOAJ9Sq+XuSVNMTarNkBeLZz6sPXjRBZNq9pqe8JKDgnZLF5nfFyzmlFZSj/ot7TexPyFbaICAnzpaX2w==" crossorigin="anonymous"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-recaptcha/4.2.0/angular-recaptcha.js" integrity="sha512-2fpdDBTokV5zj3FPXmrAxRj6Q1mHLjzJ7jVpdDXIDtbx/EMLsYW1u1dx0C70/CPoLGCMfUBKvCRcfKTwH2X+qw==" crossorigin="anonymous"></script>
                <script type="text/javascript">
                                var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
                                (function(){
                                    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
                                    s1.async=true;
                                    s1.src='https://embed.tawk.to/5f29292d2da87279037e4309/default';
                                    s1.charset='UTF-8';
                                    s1.setAttribute('crossorigin','*');
                                    s0.parentNode.insertBefore(s1,s0);
                                })();
                            </script>
</body>

</html>