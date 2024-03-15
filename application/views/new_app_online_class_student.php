<?php include(APPPATH . "views/inc/header.php"); ?>


<!-- <script src="https://online.uvschools.com/external_api.js"></script> -->
<script>
    Object.defineProperty(window.navigator, 'userAgent', {
      get: function () { return 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/80.0.3987.163 Chrome/80.0.3987.163 Safari/537.36'; }
    });
  </script>
<script src="https://meet.jit.si/external_api.js"></script>
<!-- Page Content -->
<!-- ng-init="newAppCheckClasses()" -->
<div id="page-wrapper" ng-controller="onlineClassesCtrl" >
    

    

   
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Online Classes</h4>
            </div>
            
        </div>
      

         <div class="white-box" id="callDiv" style="min-height: 100px;">
            <?php if($class_found){?>
           
                <button class="btn btn-success btn-sm" ng-if="<?php echo $class_found; ?> && <?php echo $showJoinBtn; ?>" ng-click="joinClassAsStudent('<?php echo $class_name; ?>', '<?php echo $student_name; ?>', '<?php echo $subject; ?>')" style="margin-bottom: 10px;">Join <?php echo $subject;  ?> by <?php echo $teacher;  ?> </button>
            <?php } else{?>
                <p class="text-danger">No active class found!</p><br>
                <a class="btn btn-info btn-sm" ng-click="newAppOnlineClassRefresh()" style="margin-bottom: 10px;">Refresh</a> 
            <?php } ?>
            

        </div>
    </div>
</div>

<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/new_app_online_class_footer.php"); ?>
