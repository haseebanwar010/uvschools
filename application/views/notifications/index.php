<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<style>
    .myclass{
        background: #f7f7f7;
    }
</style>
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('crumb_notifications'); ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="#"><?php echo lang('lbl_dashboard'); ?></a></li>
                    <li class="active"><?php echo lang('crumb_all_notifications'); ?></li>

                </ol>
            </div>
        </div>
        <!-- /.row -->
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <div ng-show="notifications.length == 0"><?php echo lang('no_notifications'); ?></div>
                    <div class="white-box" ng-show="notifications.length > 0">

                        <h3 class="box-title m-b-0"><?php echo lang('crumb_all_notifications'); ?></h3>
                            <!--<p class="text-muted m-b-30">Data table example</p>-->
                        <p class="text-muted m-b-30"><?php echo lang('lbl_your_have'); ?> {{ notifications.length }} <?php echo lang('crumb_notifications'); ?> 

                        </p>
                        <div class="table-responsive" >
                            <table class="table table-default m-t-20" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo lang('lbl_mark'); ?></th>
                                        <th><?php echo lang('lbl_tbl_title'); ?></th>
                                        <th><?php echo lang('lbl_tbl_sender'); ?></th>
                                        <th><?php echo lang('lbl_tbl_dataTime'); ?></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr  dir-paginate="n in notifications| itemsPerPage: 10" ng-class="{myclass:{{n.is_read}}===1}">
                                        <td>
                                            <a href="javascript:void(0);" ng-if="n.is_read==0" ng-click="show(n.id)" class="p-0 m-4" data-toggle="tooltip" data-placement="left" title="Mark as Read!"><i class="glyphicon glyphicon-envelope"  aria-hidden="true"></i></span></a>
                                            <a href="javascript:void(0);" ng-if="n.is_read==1" class="p-0 m-4"><i class="glyphicon glyphicon-ok-circle" aria-hidden="true"></i></span></a>
                                        </td>
                                        <td><a href="<?php echo base_url("{{n.notiUrl}}"); ?>" ng-click="show(n.id)"/>{{n.message}}</td>
                                        <td><a href="<?php echo base_url("{{n.notiUrl}}"); ?>" ng-click="show(n.id)"/>{{n.sender}}</td>
                                        <td><a href="<?php echo base_url("{{n.notiUrl}}"); ?>" ng-click="show(n.id)"/>{{n.dateTime}}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <dir-pagination-controls class="pull-right">
                            </dir-pagination-controls>

                        </div>
                    </div>  
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
    <!--page content end-->
</div>

<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>
