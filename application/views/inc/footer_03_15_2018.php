<!-- /.container-fluid -->
<footer class="footer text-center"> 2017 &copy; Elite Admin brought to you by themedesigner.in </footer>
</div>
<!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->
<!-- Angular js -->
<script src="assets/angularjs/angular.min.js"></script>
<!-- Angular Image uploader -->
<script src="assets/angularjs/imageupload.js" type="text/javascript"></script>
<!-- Angular auto validator lib -->
<script src="assets/angularjs-auto-validator/dist/jcs-auto-validate.min.js"></script>
<!-- App js -->
<script src="assets/js/app.js?v=<?= date("h.i.s")?>"></script>
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
<script src="assets/js/jquery.slimscroll.js"></script>
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
<!--<script src="assets/js/dashboard1.js"></script>-->
<!-- Sparkline chart JavaScript -->
<script src="assets/plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js"></script>
<script src="assets/plugins/bower_components/jquery-sparkline/jquery.charts-sparkline.js"></script>
<script src="assets/plugins/bower_components/toast-master/js/jquery.toast.js"></script>
<!-- Form Wizard JavaScript -->
<script src="assets/plugins/bower_components/jquery-wizard-master/dist/jquery-wizard.min.js"></script>
<!-- Sweet-Alert  -->
<script src="assets/plugins/bower_components/sweetalert/sweetalert.min.js"></script>  
<script src="assets/plugins/bower_components/sweetalert/jquery.sweet-alert.custom.js"></script>
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
<!-- Date Picker Plugin JavaScript -->
<script src="assets/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
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

    
    
</script>


<script>
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
<!-- end - This is for export functionality only -->
<script>
    $('.myTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
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
    var pusher = new Pusher('9bf1a37f0210a046cca3', {
      cluster: 'ap2',
      encrypted: true
    });
         
        var recipient = <?php echo $this->session->userdata("userdata")["user_id"]; ?> ;
        var channel = pusher.subscribe('mychanal-'+recipient);
        channel.bind('my-event', function(data) {
            showNotificationTo("Notification!", data, "info");
            angular.element(document.getElementById('wrapper')).scope().allNotifications();
            angular.element(document.getElementById('wrapper')).scope().countNotifications();
        });
  </script>
  <script>
       $("[required]").siblings('label').append("*");
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();

        if(dd<10) {
            dd = '0'+dd
        }

        if(mm<10) {
            mm = '0'+mm
        }

        today = mm + '/' + dd + '/' + yyyy;
        $(document).ready(function(){
            $("input[name='dob']").val(today);
        });
  </script>
<script src="assets/js/dirPagination.js"></script>
<!-- End Added BY Azeem -->

<!--Style Switcher -->
<script src="assets/plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mustache.js/2.3.0/mustache.min.js"></script>
</body>

</html>