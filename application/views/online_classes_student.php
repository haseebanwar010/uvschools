<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<!-- <script src="https://online.uvschools.com/external_api.js"></script> -->
<script>
    Object.defineProperty(window.navigator, 'userAgent', {
      get: function () { return 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/80.0.3987.163 Chrome/80.0.3987.163 Safari/537.36'; }
    });
  </script>
<script src="https://meet.jit.si/external_api.js"></script>
<!-- Page Content -->
<div id="page-wrapper" ng-controller="onlineClassesCtrl" ng-init="checkClasses()">
    

    

   
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('lbl_online_classes');?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="<?php echo site_url('/dashboard') ?>"><?php echo lang('crumb_dashboard') ?></a></li>
                    <li class="active"><?php echo lang('lbl_online_classes');?></li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div class="hint"><?php echo lang('help_online_classes');?></div>
        
        
        <div class="white-box" id="callDiv" style="min-height: 100px;">
            <button class="btn btn-success btn-sm" ng-if="classData.class_found && showJoinBtn" ng-click="joinClassAsStudent(classData.class_name, classData.student_name, classData.subject)" style="margin-bottom: 10px;">Join {{classData.subject}} by {{classData.teacher}}</button>
            <p class="text-danger" ng-if="!classData.class_found"><?php echo lang('No_active_class_found!');?></p><br>
            <button class="btn btn-info btn-sm" ng-click="checkClasses()" style="margin-bottom: 10px;"><?php echo lang('refresh');?></button>
            

        </div>
    </div>
</div>

<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>
