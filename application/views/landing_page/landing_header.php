<!DOCTYPE html>
<html lang="en" <?php
if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {
    echo 'dir="ltr"';
} else {
    echo 'dir="rtl"';
}
?>>

<head>
    <meta charset="utf-8">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="UVSchools">
    <meta name="author" content="">
    <meta name="keyword" content="UVSchools">
    <title><?php echo SITENAME; ?></title>
    <base href = "<?php echo base_url(); ?>" />
    <link rel="icon" type="image/png" sizes="16x16" href="assets/plugins/images/favicon.png">
    <title>UVSchools</title>
    <!-- Bootstrap Core CSS -->
      
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js" integrity="sha512-XtmMtDEcNz2j7ekrtHvOVR4iwwaD6o/FUJe6+Zq+HgcCsk3kj4uSQQR8weQ2QVj1o0Pk6PwYLohm206ZzNfubg==" crossorigin="anonymous"></script>
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link href="assets/landingpage/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css" rel="stylesheet">
    <!--My admin Custom CSS -->
    <link href="assets/plugins/bower_components/owl.carousel/owl.carousel.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/bower_components/owl.carousel/owl.theme.default.css" rel="stylesheet" type="text/css" />
    <!-- animation CSS -->
    <link href="assets/landingpage/css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/landingpage/css/style.css" rel="stylesheet">
    <!-- <link href="assets/landingpage/css/stylertl.css" rel="stylesheet"> -->
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
   
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-19175540-9', 'auto');
        ga('send', 'pageview');
        
    </script>
    <!-- <script src="assets/landingpage/js/button.js"></script> -->
    <!--End of Zopim Live Chat Script-->
    <style type="text/css">
    .lang_select{
        margin-top:-3%;
        padding-top: 0;
        padding-bottom: 0;
        border-radius: 16px
    }
</style>
</head>

<body class="">
    <!-- Preloader -->
    <div id="wrapper">
        <div class="container-fluid">

            <!-- Row -->
            <div class="row" >
                <div class="col-md-12 navbar-fixed">
                    <div class="fix-width">
                        <nav class="navbar navbar-default navbar-fixed-top">
                            <!-- Brand and toggle get grouped for better mobile display -->


                            <div class="navbar-header <?php if($this->session->userdata('site_lang')=='arabic' || $this->session->userdata('site_lang')=='urdu') echo 'pull-right' ?>" id="my_nav">
                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false" onclick="(function(){$('#my_nav').removeClass('pull-right')})();return false;">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <a class="navbar-brand" href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>assets/landingpage/images/uvs.png" alt="UVSchools" style="height:48px; width: 170px;" /></a>
                            </div>
                            


                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                                <?php
                                if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                                    <ul class="nav navbar-nav custom-nav navbar-right m-t-10">

                                        <li><a href="#myCarousel"><?php echo lang('lbl_home');?></a></li>
                                        <li><a href="#features"><?php echo lang('lbl_features');?></a></li>
                                        <!--<li><a href="#pricing"><?php echo lang('lbl_pricing');?></a></li>-->
                                        <li><a href="#mobile-app"><?php echo strtoupper(lang('lbl_App'));?></a></li>
                                        <li><a href="#contact-us"><?php echo lang('lbl_contact');?></a></li>
                                        <!--                                        <li><a href="<?php echo site_url('faq'); ?>"><?php echo lang('lbl_faq');?></a></li>-->
                                        <?php if ($this->session->userdata("userdata")) { ?>
                                            <li><a href="<?php echo site_url('logout'); ?>"><?php echo strtoupper(lang('btn_logout')); ?></a></li>  
                                        <?php } else { ?>
                                            <li><a href="<?php echo site_url('signup'); ?>"><?php echo strtoupper(lang('btn_get_started')); ?></a></li>  
                                            <li><a href="<?php echo site_url('login'); ?>"><?php echo lang('btn_login'); ?></a></li>  
                                        <?php } ?>
                                        <li>
                                            <a><select class="form-control lang_select" onchange="javascript:window.location.href = '<?php echo base_url(); ?>LanguageSwitcher/switchLang/' + this.value;">
                                                <option value="english" <?php if ($this->session->userdata('site_lang') == 'english') echo 'selected="selected"'; ?>>English</option>
                                                <option value="arabic" <?php if ($this->session->userdata('site_lang') == 'arabic') echo 'selected="selected"'; ?>>Arabic</option>
                                                <option value="urdu" <?php if ($this->session->userdata('site_lang') == 'urdu') echo 'selected="selected"'; ?>>Urdu</option>
                                            </select></a>
                                        </li>
                                    </ul>
                                <?php } ?>
                                <?php
                                if ($this->session->userdata('site_lang') == "arabic" || $this->session->userdata('site_lang') == "urdu") {?>
                                 <ul class="nav navbar-nav custom-nav navbar-left m-t-10">
                                    <li><a><select class="form-control lang_select" onchange="javascript:window.location.href = '<?php echo base_url(); ?>LanguageSwitcher/switchLang/' + this.value;">
                                        <option value="english" <?php if ($this->session->userdata('site_lang') == 'english') echo 'selected="selected"'; ?>>English</option>
                                        <option value="arabic" <?php if ($this->session->userdata('site_lang') == 'arabic') echo 'selected="selected"'; ?>>Arabic</option>
                                        <option value="urdu" <?php if ($this->session->userdata('site_lang') == 'urdu') echo 'selected="selected"'; ?>>Urdu</option>
                                    </select></a>
                                </li>

                                <?php if ($this->session->userdata("userdata")) { ?>
                                    <li><a style="font-size: 18px;" href="<?php echo site_url('logout'); ?>"><?php echo lang('btn_logout'); ?></a></li>  
                                <?php } else { ?>
                                    <li><a style="font-size: 18px;" href="<?php echo site_url('signup'); ?>"><?php echo lang('btn_get_started'); ?></a></li>  
                                    <li><a style="font-size: 18px;" href="<?php echo site_url('login'); ?>"><?php echo lang('btn_login'); ?></a></li>
                                <?php } ?>
                                <li><a style="font-size: 18px;" href="#contact-us"><?php echo lang('lbl_contact');?></a></li>
                                <!--                                        <li><a href="<?php echo site_url('faq'); ?>"><?php echo lang('lbl_faq');?></a></li>-->
                                <!--<li><a style="font-size: 18px;" href="<?php //echo site_url('pricing'); ?>"><?php //echo lang('lbl_pricing');?></a></li>-->
                                <li><a style="font-size: 18px;" href="#features"><?php echo lang('lbl_features');?></a></li>
                                <li ><a style="font-size: 18px;" href="<?php echo base_url(); ?>"><?php echo lang('lbl_home');?></a></li>
                                </ul>

                                <?php } ?>

                            </div>
                            <!-- /.navbar-collapse -->
                        </nav>
                    </div>
                </div>
            </div>
