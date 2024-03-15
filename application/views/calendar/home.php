<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<div id="page-wrapper" ng-controller="dashboardCtrl" ng-inti="get_Today_Events()">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?= lang("lbl_calendar"); ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="dashboard/"><?= lang("lbl_dashboard"); ?></a></li>
                    <li class="active"><?= lang("lbl_dashboard"); ?></li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="alert" style="position: fixed; top: 0; left: 0; width: 100%; z-index: 1000;"></div>
        <div class="container col-md-12 white-box">
                    <!-- Notification -->
            <div class="row clearfix">
                <div class="col-md-12 column">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
        <!-- modal to view event details for parent -->
        <div class="modal fade" id="exampleModalCenter">
            <div class="modal-dialog">
                <div class="modal-content" style="margin-top: 33%;">
                    <div class="panel panel-primary" style="margin-bottom: 0;">
                        <div class="panel-heading">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo lang("lbl_calendar_close") ?></span></button>
                            <div class="modal-title"></div>
                        </div>
                        <div class="panel-body">
                            <div class="error"></div>
                            <form class="form-material" id="crud-form">
                            <input type="hidden" id="start">
                            <input type="hidden" id="end">
                                <div class="form-group">
                                    <label class="control-label" for="title"><?php echo lang('lbl_calendar_title') ?></label>
                                    <input id="tit" name="title" type="text" class="form-control" disabled readonly />
                                </div>                            
                                <div class="form-group">
                                    <label class="control-label" for="description"><?php echo lang('lbl_calendar_description') ?></label>
                                    <textarea class="form-control" id="desc" name="description" rows="3" cols="50" disabled readonly></textarea>
                                </div>
                                
                            </form>
                            <div class="modal-footer panel-footer" style="border:0; padding-right:0;">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("lbl_calendar_cancel") ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--End modal to view event details for parent -->
        <div class="modal fade" id="calendar-modal">
            <div class="modal-dialog">
                <div class="modal-content" style="margin-top: 33%;">
                    <div class="panel panel-primary" style="margin-bottom: 0;">
                        <div class="panel-heading">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo lang("lbl_calendar_close") ?></span></button>
                            <div class="modal-title"></div>
                        </div>
                        <div class="panel-body">
                            <div class="error"></div>
                            <form class="form-material" id="crud-form">
                            <input type="hidden" id="start">
                            <input type="hidden" id="end">
                                <div class="form-group">
                                    <label class="control-label" for="title"></label>
                                    <input id="title" name="title" type="text" class="form-control" placeholder="<?php echo lang('lbl_calendar_title') ?>" />
                                </div>                            
                                <div class="form-group">
                                    <label class="control-label" for="description"></label>
                                    <textarea class="form-control" id="description" name="description" rows="3" cols="50" placeholder="<?php echo lang('lbl_calendar_description') ?>"></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="color"><?php echo lang("lbl_calendar_Tag") ?></label>
                                    <input id="color" name="color" type="text" class="form-control" readonly="readonly" />
                                </div>

                                <div class="form-group">
                                    <label>Event Type</label>
                                    <select class="form-control" name="event_type" id="event_type" required="">
                                        <option>Choose Event Type</option>
                                        <option value="Event">Event</option>
                                        <option value="Holiday">Holiday</option>
                                    </select>
                                </div>

                                <div class="holiday box" id="showholidaytype">
                                    <div class="form-group">
                                    <label>Holiday Type</label>
                                    <select class="form-control" name="holiday_type" id="holiday_type"required="">
                                        <option>Choose Holiday Type</option>
                                        <option value="private_holiday">Event Holiday</option>
                                        <option value="public_holiday">Normal Holiday</option>
                                    </select>
                                </div>
                                </div>

                                <div class="form-group">
                                    <label>Access</label>
                                    <ul class="list-group">
                                        <li class="list-group-item" style="border:0px;">
                                            <div class="radio radio-primary radio-circle">
                                                <input type="radio" name="mode" value="personel" checked>
                                                <label>Personel</label><br>
                                                <input type="radio" name="mode" value="private">
                                                <label>Private</label><br>
                                                <input type="radio"  name="mode" value="public">
                                                <label>Public</label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </form>
                            <div class="modal-footer panel-footer" style="border:0; padding-right:0;">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("lbl_calendar_cancel") ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        
        <div class="row"></div>
        </div>

<?php include(APPPATH . "views/inc/footer.php"); ?>
<script src='assets/fullcalendar/js/main.php'></script>
 <script>
// $(document).ready(function(){
//     $("select").change(function(){
//         $(this).find("option:selected").each(function(){
//             var optionValue = $(this).attr("value");
//             if(optionValue){
//                 $(".box").not("." + optionValue).hide();
//                 $("." + optionValue).show();
//             } else{
//                 $(".box").hide();
//             }
//         });
//     }).change();
// });
$(document).ready(function(){
        $("#event_type").change(function()
        {
        if($(this).val() == "Holiday")
        {
        $("#showholidaytype").show();
        }
        else if($(this).val() == "Event")
        {
            $("#showholidaytype").hide();
        }
        });
        $("#showholidaytype").hide();
});
</script>   



