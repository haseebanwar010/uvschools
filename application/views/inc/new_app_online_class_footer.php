<!-- /.container-fluid -->
<footer class="footer text-center"><?php echo lang('copy_right') ?></footer>
</div>
<!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->
<!-- Angular js -->
<script src="assets/angularjs/angular.min.js"></script>
<!-- Angular Image uploader -->
<script src="assets/angularjs/imageupload.js" type="text/javascript"></script>
<!-- Angular file uploader -->
<script src="assets/angular-file-upload/dist/angular-file-upload.min.js" type="text/javascript"></script>
<!-- Angular auto validator lib -->
<script src="assets/angularjs-auto-validator/dist/jcs-auto-validate.min.php"></script>
<!-- UI-Cropper -->
<script type="text/javascript" src="assets/ui_cropper/compile/minified/ui-cropper.js?v=1"></script>
<!-- App js -->
<script src="assets/js/app.php?v=<?= date("h.i.s") ?>"></script>
<!-- jQuery -->
<!--<script src="assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>-->
<!-- Bootstrap Core JavaScript -->
<script src="assets/bootstrap/dist/js/tether.min.js"></script>
<script src="assets/bootstrap/dist/js/bootstrap.min.js"></script>
<?php if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) { ?>
    <script src="assets/plugins/bower_components/bootstrap-extension/js/bootstrap-extension.min.js"></script>
<?php } else { ?>
    <script src="assets/plugins/bower_components/bootstrap-rtl-master/dist/js/bootstrap-rtl.min.js"></script>
<?php } ?>
<!-- Menu Plugin JavaScript -->
<script src="assets/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
<!--slimscroll JavaScript -->
<script src="assets/js/jquery.slimscroll.js?v=1.1"></script>
<!--Wave Effects -->
<script src="assets/js/waves.js"></script>
<!--Counter js -->
<script src="assets/plugins/bower_components/waypoints/lib/jquery.waypoints.js"></script>
<script src="assets/plugins/bower_components/counterup/jquery.counterup.min.js"></script>
<!--Morris JavaScript -->
<script src="assets/plugins/bower_components/raphael/raphael-min.js"></script>
<!--<script src="assets/plugins/bower_components/morrisjs/morris.js"></script>-->
<!-- Custom Theme JavaScript -->
<script src="assets/js/custom.min.js"></script>
<!-- Plugin JavaScript -->
<script src="assets/plugins/bower_components/moment/moment.js"></script>

<!-- calendar -->
<script src="assets/fullcalendar/js/bootstrapValidator.min.js"></script>
<script src="assets/fullcalendar/js/fullcalendar.min.js"></script>
<script src='assets/fullcalendar/js/bootstrap-colorpicker.min.js'></script>
<!-- <script src='assets/fullcalendar/js/main.php'></script> -->
<script src="assets/fullcalendar/js/lang-all.js"></script>
<!-- <script src='assets/fullcalendar/js/moment.min.js'></script>
<script src="assets/fullcalendar/js/jquery.min.js"></script> -->

<script type="text/javascript">
jQuery.event.special.touchstart = {
    setup: function( _, ns, handle ) {
        this.addEventListener('touchstart', handle, { passive: !ns.includes('noPreventDefault') });
    }
};
jQuery.event.special.touchmove = {
    setup: function( _, ns, handle ) {
        this.addEventListener('touchmove', handle, { passive: !ns.includes('noPreventDefault') });
    }
};
window.addEventListener('mousewheel', event => {
}, {passive: true });

    $(document).ready(function(){
        $('#slybuss_modal_request').on('shown.bs.modal', function () {
           $("#calendar").fullCalendar('render');
        });
    });
</script>
<script src='assets/fullcalendar/js/all_application.php'></script>


<!-- Sparkline chart JavaScript -->
<!--morris JavaScript -->
<script src="assets/plugins/bower_components/raphael/raphael-min.js"></script>
<script src="assets/plugins/bower_components/morrisjs/morris.min.js"></script>
<!-- Select JavaScript -->
<script src="assets/plugins/bower_components/select2files/dist/js/select2.min.js"></script>
<!-- Dropzone Plugin JavaScript -->
<script src="assets/plugins/bower_components/dropzone-master/dist/dropzone.js"></script>
<!-- Sparkline JavaScript -->
<script src="assets/plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js"></script>
<script src="assets/plugins/bower_components/jquery-sparkline/jquery.charts-sparkline.js"></script>
<script src="assets/plugins/bower_components/toast-master/js/jquery.toast.js"></script>
<!-- Form Wizard JavaScript -->
<script src="assets/plugins/bower_components/jquery-wizard-master/dist/jquery-wizard.min.js"></script>
<!-- Sweet-Alert  -->
<script src="assets/plugins/bower_components/sweetalert/sweetalert.min.js"></script>  
<script src="assets/plugins/bower_components/sweetalert/jquery.sweet-alert.custom.php"></script>
<!-- Custom Theme JavaScript -->
<script src="assets/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.js"></script>
<script src="assets/plugins/bower_components/switchery/dist/switchery.min.js"></script>

<script src="assets/plugins/bower_components/bootstrap-select/bootstrap-select.min.js" type="text/javascript"></script>
<script src="assets/plugins/bower_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
<script src="assets/plugins/bower_components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js" type="text/javascript"></script>
<script type="text/javascript" src="assets/plugins/bower_components/multiselect/js/jquery.multi-select.js"></script>
<!-- icheck -->
<script src="assets/plugins/bower_components/icheck/icheck.min.js"></script>
<script src="assets/plugins/bower_components/icheck/icheck.init.js"></script>
<!-- Pusher js -->
<script src="https://js.pusher.com/4.1/pusher.min.js"></script>
<script src="assets/plugins/bower_components/spectrum/spectrum.js"></script>
<script src="assets/lightbox/dist/js/lightbox.js"></script>
<script src="assets/js/jQuery.print.js"></script>
<script src="assets/js-xlsx-master/dist/xlsx.full.min.js"></script>
<script src="assets/FileSaver/FileSaver.js"></script>
<script src="assets/plugins/bower_components/dropify/dist/js/dropify.min.js"></script>
<!-- Date Picker Plugin JavaScript -->
<!--<script src="assets/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
    //jQuery(".mydatepicker-autoclose").datepicker();
    jQuery(".mydatepicker-autoclose").datepicker({
        autoclose: true,
        todayHighlight: true
    });
    
    

    jQuery('.clockpicker').clockpicker({
            donetext: 'Done',
            template: 'modal'
        }).find('input').change(function() {
        console.log(this.value);
    });

    
    
</script>-->

<script>
    $(document).ready(function () {
        $("form :input").attr("autocomplete", "off");
        $("input:password").attr("autocomplete", "new-password");
        $('.breadcrumb').hide();
<?php if ($this->session->userdata('userdata')["side_bar"] == false) { ?>
            setTimeout(function () {
                $("#side_bar_btn").click();
                $('#official').hide();
            }, 1);
<?php } else { ?>
            $('#official').show();
<?php } ?>
    });

    $("#side_bar_btn").mouseup(function (e) {
       if(e.which == 1){
<?php if ($this->session->userdata('userdata')["side_bar"] == false) { ?>
           setTimeout(function () {

               $('#official').toggle();

           }, 1);
<?php } else { ?>
           $('#official').toggle();

<?php } ?>
       $.ajax({url: "study_material/sidebar", success: function (result) {

           }});
   }
   });

    function edit(id) {
        //$('#edit-department').on('shown.bs.modal', function (e) {
        Loading("body", "", "", "show");

        //});

        $.ajax({
            url: "settings/getDepartment",
            type: "POST",
            dataType: "json",
            data: {"id": id},
            success: function (data) {
                Loading("body", "", "", "hide");
                //alert("");
                //console.log(data);
                //$('#edit-department').modal('show');
                $("#edit-department-name").val(data.name);
                $("#edit-code").val(data.code);
                $("#edit-dept-id").val(data.id);
            },
            error: function (error) {
                Loading("body", "", "", "hide");
                console.log(error);
            }
        });



        $('#edit-department').on('hidden.bs.modal', function (e) {

            $("#edit-department-name").val("");
            $("#edit-code").val("");
            $("#edit-dept-id").val("");
        });



    }
</script>
<!-- Add Employee Custom JS -->

<script src="assets/plugins/bower_components/datatables/jquery.dataTables.min.js"></script>
<!-- start - This is for export functionality only -->
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.colVis.min.js"></script>
<!-- end - This is for export functionality only -->
<script>


    $('.myTable').DataTable({
        dom: 'Bfrtip',
        buttons: [

        ],
        "language": {

            "decimal": "",
            "emptyTable": '<?php echo lang("no_data_table"); ?>',
            "info": '<?php echo lang("data_info"); ?>',
            "infoEmpty": '<?php echo lang("infoempty"); ?>',
            "infoFiltered": '<?php echo lang("filter_datatable"); ?>',
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": '<?php echo lang("show_datatable"); ?>',
            "loadingRecords": '<?php echo lang("loading_datatable"); ?>',
            "processing": '<?php echo lang("processing_datatable"); ?>',
            "search": '<?php echo lang("search"); ?>:',
            "zeroRecords": '<?php echo lang("no_record_datatable"); ?>',
            "paginate": {
                "first": '<?php echo lang("first"); ?>',
                "last": '<?php echo lang("last"); ?>',
                "next": '<?php echo lang("btn_next"); ?>',
                "previous": '<?php echo lang("previous"); ?>'
            },
            "aria": {
                "sortAscending": ": activate to sort column ascending",
                "sortDescending": ": activate to sort column descending"
            }
        }

    });
    /*$('#all_student').DataTable({
     dom: 'Bfrtip',
     buttons: [
     'copy', 'csv', 'excel', 'pdf', 'print'
     ]
     });*/
</script>

<!-- Start Added BY Azeem -->

<script>
    $("[required]").siblings('label').append("*");
</script>
<script src="assets/js/dirPagination.js"></script>
<!-- End Added BY Azeem -->


<script type="text/javascript">
    if ($(".xcrud-container").length == 0) {
        $.getScript("assets/xcrud/plugins/jquery-ui/jquery-ui.min.js").done(function () {
            $.getScript("assets/xcrud/plugins/timepicker/jquery-ui-timepicker-addon.js").done(function () {
                var datepicker_config = {
                    changeMonth: true,
                    changeYear: true,
                    showSecond: false,
                    controlType: 'select',
                    yearRange: "-50:+10",
                    dateFormat: 'dd/mm/yy',
                    timeFormat: 'hh:mm tt'
                };

                $(".mydatepicker-autoclose").datepicker(datepicker_config);

                var datepicker1_config = {
                    changeMonth: true,
                    changeYear: true,
                    showSecond: false,
                    controlType: 'select',
                    yearRange: "-50:+10",
                    dateFormat: 'dd/mm/yy',
                    timeFormat: 'hh:mm tt'
                };

                $(".mydatepicker-autoclose-op").datepicker(datepicker1_config);
                
            });
        });
    }
    if ($.trim($('.hint').html()).length) {
        $('.hint').show();
    }
</script>



<!-- wysuhtml5 Plugin JavaScript -->

<script src="assets/plugins/bower_components/html5-editor/wysihtml5-0.3.0.js"></script>
<script src="assets/plugins/bower_components/html5-editor/bootstrap-wysihtml5.js"></script>
<script src="assets/plugins/bower_components/tinymce/tinymce.min.js"></script>
<script>
    $(document).ready(function () {
        $('.y_editor').wysihtml5();
        if ($(".mymce").length > 0) {

          tinymce.init({
              selector: 'textarea',
              height: 500,
              theme: 'modern',
              plugins: 'print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern',
              toolbar1: 'insertfile undo redo | styleselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | emoticons | formatselect | bold italic strikethrough forecolor backcolor | link | removeformat',
              image_advtab: true,
              templates: [
              { title: 'Test template 1', content: 'Test 1' },
              { title: 'Test template 2', content: 'Test 2' }
              ],
              content_css: [
              '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
              '//www.tinymce.com/css/codepen.min.css'
              ]
          });
      }
  });
</script>

<!-- Loading overlay -->
<script src="assets/loading/loadingoverlay.min.js"></script>
<script src="assets/loading/loadingoverlay_progress.min.js"></script>
<!--Style Switcher -->
<script src="assets/plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mustache.js/2.3.0/mustache.min.js"></script>

<!-- Start::Chart -->

<script>
    $(document).ready(function () {
        if ($(window).width() <= 1024) {
            $('.logo2').hide();
            $('#official').hide();
        } else {
            $('.slimScrollDiv').css('position', 'fixed');
            $('.navbar-header').css('position', 'fixed');
            $('.slimScrollDiv').css('width', 'inherit');
            $('.slimScrollBar').css('width', '10px');
            $('.slimScrollBar').addClass('bg-primary');
            
            <?php if ($this->session->userdata('site_lang') == "arabic" ) { ?>
                $(".slimScrollBar").css({right: ''});
                $('.slimScrollBar').css('left', '0px');
            <?php } ?>
        }
    });
</script>
<script src="assets/js/mask.js"></script>
<script src="assets/js/jquery.mask.js"></script>
<script>
    $('document', function () {
        $('#contact').mask("+999-999-99999999", {placeholder: "+123-123-12345678"}, {reverse: true});
        $('#contact2').mask("+999-999-99999999", {placeholder: "+123-123-12345678"}, {reverse: true});
    });
</script>

<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip(); 
});
jQuery(document).on("xcrudbeforerequest", function(event, container) {
    if (container) {
        jQuery(container).find("select").select2("destroy");
    } else {
        jQuery(".xcrud").find("select").select2("destroy");
    }
});

jQuery(document).on("ready xcrudafterrequest", function(event, container) {
    if (container) {
        jQuery(container).find("select").select2();
    } else {
        jQuery(".xcrud").find("select").select2();
    }
});

jQuery(document).on("xcrudbeforedepend", function(event, container, data) {
    jQuery(container).find('select[name="' + data.name + '"]').select2("destroy");
});
jQuery(document).on("xcrudafterdepend", function(event, container, data) {
    jQuery(container).find('select[name="' + data.name + '"]').select2();
});
</script>



<script>
    $(document).ready(function(){
        $(".yasir-payroll-select2").select2();
        $(".yasir-assignsubjects-select2").select2();
    });
    $(document).ready(function(){  
        $(".yasir2-payroll-select2").select2({  tags: true  });
        $(".yasir-ann-select2").select2();  
    });
    function showAnnouncements(){
        $("#myNavAnnouncement").css({"width":"100%"});
    }
</script>
<script>
// document.onkeydown = function(e) {
//   if(event.keyCode == 123) {
//     console.log('You cannot inspect Element');
//      return false;
//   }
//   if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
//     console.log('You cannot inspect Element');
//     return false;
//   }
//   if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
//     console.log('You cannot inspect Element');
//     return false;
//   }
//   if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
//     console.log('You cannot inspect Element');
//     return false;
//   }
//   if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
//     console.log('You cannot inspect Element');
//     return false;
//   }
// } 
// // prevents right clicking
// document.addEventListener('contextmenu', e => e.preventDefault());
</script>
<!-- Magnific popup JavaScript -->
<script src="assets/plugins/bower_components/Magnific-Popup-master/dist/jquery.magnific-popup.min.js"></script>
<script src="assets/plugins/bower_components/Magnific-Popup-master/dist/jquery.magnific-popup-init.js"></script>
<!-- Dashboard .js -->
<!-- <script src="assets/dist/js/dashboard1.js"></script> -->

</body>

</html>