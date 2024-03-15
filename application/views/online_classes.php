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
<div id="page-wrapper" ng-controller="onlineClassesCtrl">
    <?php $sess_started_by = $this->session->userdata("userdata")['user_id']; ?>

    

   
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
        <!--.row-->
        <div class="white-box well" id="att_search_filter">
            <form class="form-material" name="classFilterForm" ng-submit="onSubmit(classFilterForm.$valid)" novalidate="">
                <div class="row">
                    <div class="col-md-4" id="attFilterClasses">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang('lbl_class') ?></label>
                            <select class="form-control" ng-model="filterModel.class_id" ng-init="initClasses()" ng-change="initBatches(filterModel.class_id)" required="">
                                <span>{{classes}}</span>
                                <option value=""><?php echo lang('select_course') ?></option>
                                <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4" id="attFilterBatches">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang('lbl_batch') ?></label>
                            <select class="form-control" ng-model="filterModel.batch_id" required="">
                                <option value=""><?php echo lang('select_batch') ?></option>
                                <option ng-repeat="bth in batches" value="{{bth.id}}">{{bth.name}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label></label>
                        <button type="submit" class="btn btn-primary btn-block"><?php echo lang('search');?></button>
                    </div>
                </div>
            </form>
        </div>
        <div class="white-box" ng-if="activeData.class_id != undefined" id="callDiv">
            <button class="btn btn-info" ng-if="!activeData.class_found && showStart" ng-click="startClass()"><?php echo lang('start_class_for');?> {{activeData.subject}}</button>
            <button class="btn btn-success" id="join_class_of" ng-if="activeData.class_found && showStart" ng-click="joinClassAsTeacher(activeData.class_name, activeData.user_name, activeData.subject)"><?php echo lang('join_class_of');?> {{activeData.subject}}</button>
            <button class="btn btn-danger"  ng-if="activeData.class_found && activeData.end_class" ng-click="endClass()"><?php echo lang('end_class');?></button>
            
        </div>
      
    </div>
</div>

<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>
