<!DOCTYPE html>
<html lang="en" <?php if($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')){ echo 'dir="ltr"';}else{    echo 'dir="rtl"';}?>>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/plugins/images/favicon.png">
    <title><?php echo SITENAME; ?></title>
        <base href = "<?php echo base_url(); ?>" />
    <!-- Bootstrap Core CSS -->
   
    <?php if($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')){?>
    <link href="assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
      <link href="assets/plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <?php }else{ ?>
     
    <link href="assets/plugins/bower_components/bootstrap-rtl-master/dist/css/bootstrap-rtl.min.css" rel="stylesheet">
     <link href="assets/css/stylertl.css" rel="stylesheet">
    <?php } ?>
     <link href="assets/plugins/bower_components/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <!-- Menu CSS -->
    <link href="assets/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
    <!-- toast CSS -->
    <link href="assets/plugins/bower_components/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- morris CSS -->
    <link href="assets/plugins/bower_components/morrisjs/morris.css" rel="stylesheet">
    <!-- animation CSS -->
    <link href="assets/css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css" rel="stylesheet" />
    <link href="assets/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/bower_components/custom-select/custom-select.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/bower_components/switchery/dist/switchery.min.css" rel="stylesheet" />
    <link href="assets/plugins/bower_components/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <link href="assets/plugins/bower_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet" />
    <link href="assets/plugins/bower_components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    <link href="assets/plugins/bower_components/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
    <!-- Wizard CSS -->
    <link href="assets/plugins/bower_components/jquery-wizard-master/css/wizard.css" rel="stylesheet">
   <!--alerts CSS -->
    <link href="assets/plugins/bower_components/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">
    <!-- color CSS -->
    <link href="assets/css/colors/blue-dark.css" id="theme" rel="stylesheet">
    <link href="assets/plugins/bower_components/icheck/skins/all.css" rel="stylesheet">
    <!-- Date and time picker plugins css -->
    <link href="assets/plugins/bower_components/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="assets/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/animate.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
    
    <link href="assets/plugins/bower_components/select2files/dist/css/select2.min.css" rel="stylesheet" />
    <script src="assets/plugins/bower_components/select2files/dist/js/select2.min.js"></script>
    <!-- wysihtml5 CSS -->
    <link rel="stylesheet" href="assets/plugins/bower_components/html5-editor/bootstrap-wysihtml5.css" />
    <script src="assets/plugins/bower_components/html5-editor/wysihtml5-0.3.0.js"></script>
    <script src="assets/plugins/bower_components/html5-editor/bootstrap-wysihtml5.js"></script>
    <!-- Dropzone Plugin JavaScript -->
    <script src="assets/plugins/bower_components/dropzone-master/dist/dropzone.js"></script>
    <!-- Dropzone css -->
    <link href="assets/plugins/bower_components/dropzone-master/dist/dropzone.css" rel="stylesheet" type="text/css" />
		
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- Loading overlay -->
    <script src="assets/loading/loadingoverlay.min.js"></script>
    <script src="assets/loading/loadingoverlay_progress.min.js"></script>  
    <script>
    (function(i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function() {
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
</head>

<body ng-app="myschool2" ng-cloak>
    <!-- Preloader -->
<!--    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>-->