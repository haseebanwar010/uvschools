<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<div>
    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid" ng-controller="bookshopCtrl" ng-init="getAllBooksForStudents()">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">

                    <h4 class="page-title"><?php echo lang('lbl_book_shop'); ?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                    <ol class="breadcrumb">
                        <li><a href="#"><?php echo lang('lbl_dashboard'); ?></a></li>
                        <li><a href="#"><?php echo lang('study_material'); ?></a></li>
                        <li class="active"><?php echo lang('lbl_book_shop'); ?></li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <div class="hint"><?php echo lang('help_book_shop') ?></div>
            <!-- Page Content start here -->
            <!--.row-->
            
        <div class="well" style="background:#e4e7ea;">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label"><?php echo lang('lbl_child') ?></label>
                        <select class="form-control" id="cls" ng-model="arModel.student_id" ng-init="initGetParentChild()">
                            <option value=""><?php echo lang('lbl_select_child') ?></option>
                            <option ng-repeat="c in parentchild" value="{{c.student_id}}">{{c.name}}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <!--<button class="btn btn-sm btn-info" ng-click="updateTable()">Search</button>-->
                    <button class="btn btn-primary text-white" ng-click="getBookshopOfStudent()"><?php echo lang('search') ?></button>
                    <!-- <button class="btn btn-warning text-white" ng-click="removeFilters()" ng-disabled="selectedClass=='all' && (seletedClass.length!=0 && !removeBtn)"><?php echo lang('remove_filters') ?></button> -->
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-md-12">
                <div class="panel panel-primary">

                    <div class="panel-wrapper collapse in" aria-expanded="true">
                        <div class="panel-body">
                            <div ng-bind-html="bookshop"></div>


                            <!--tab content end here-->
                        </div>





                        <!--/Add new end here-->

                        <!--/panel body-->
                    </div>
                    <!--/panel wrapper-->
                </div>
                <!--/panel-->
            </div>
        </div>
        <!--./row-->
        <!--page content end here-->

    </div>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function() {

        $('.js-example-basic-multiple').select2({
            placeholder : '<?php echo lang("select_course"); ?>'
        });
    });
</script>
<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>
