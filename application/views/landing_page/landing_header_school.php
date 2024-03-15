<!DOCTYPE html>
<html lang="en">

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
        <link href="assets/landingpage/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css" rel="stylesheet">
        <!--My admin Custom CSS -->
        <link href="assets/plugins/bower_components/owl.carousel/owl.carousel.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/bower_components/owl.carousel/owl.theme.default.css" rel="stylesheet" type="text/css" />
        <!-- animation CSS -->
        <link href="assets/landingpage/css/animate.css" rel="stylesheet">
        <!-- Custom CSS -->
        <link href="assets/landingpage/css/style.css" rel="stylesheet">
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
                <div class="row">
                    <div class="col-md-12 navbar-fixed">
                        <div class="fix-width">
                            <nav class="navbar navbar-default">
                                <!-- Brand and toggle get grouped for better mobile display -->
                                <div class="navbar-header">
                                    
                                    <a class="navbar-brand" href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>assets/landingpage/images/uvs.png" alt="UVSchools" style="height:48px; width: 170px;" /></a>
                                </div>
                                <!-- Collect the nav links, forms, and other content for toggling -->
                              <!--   <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                    <ul class="nav navbar-nav custom-nav navbar-right m-t-10">

                                        <li><a href="<?php echo base_url(); ?>"><?php echo lang('lbl_home');?></a></li>
                                        <li><a href="#features"><?php echo lang('lbl_features');?></a></li>
                                        <li><a href="<?php echo site_url('pricing'); ?>"><?php echo lang('lbl_pricing');?></a></li>
                                        <li><a href="#contact-us"><?php echo lang('lbl_contact');?></a></li>
                                        <li><a href="<?php echo site_url('faq'); ?>"><?php echo lang('lbl_faq');?></a></li>
                                        <?php if ($this->session->userdata("userdata")) { ?>
                                            <li><a href="<?php echo site_url('logout'); ?>"><?php echo lang('btn_logout'); ?></a></li>  
                                        <?php } else { ?>
                                            <li><a href="<?php echo site_url('login/signup'); ?>"><?php echo lang('btn_get_started'); ?></a></li>  
                                            <li><a href="<?php echo site_url('login'); ?>"><?php echo lang('btn_login'); ?></a></li>  
                                        <?php } ?>

                                        <li><a><select class="form-control lang_select" onchange="javascript:window.location.href = '<?php echo base_url(); ?>LanguageSwitcher/switchLang/' + this.value;">
                                            <option value="english" <?php if ($this->session->userdata('site_lang') == 'english') echo 'selected="selected"'; ?>>English</option>
                                            <option value="urdu" <?php if ($this->session->userdata('site_lang') == 'urdu') echo 'selected="selected"'; ?>>Urdu</option>
                                        </select></a>
                                        </li>
                                    </ul>
                                   
                                </div> -->
                                <!-- /.navbar-collapse -->
                            </nav>
                        </div>
                    </div>
                </div>
