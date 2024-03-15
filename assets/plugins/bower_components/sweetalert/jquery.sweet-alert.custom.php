<?php
    header('Content-Type: application/javascript');
    ob_start();
    require_once($_SERVER['DOCUMENT_ROOT'] . '/uv/master/index.php');
    //require_once($_SERVER['DOCUMENT_ROOT'].'/index.php');
    ob_end_clean();
?>

!function($) {
    "use strict";

    var SweetAlert = function() {};

    //examples 
    SweetAlert.prototype.init = function() {
       
        

        swal.setDefaults({
            cancelButtonText: '<?php echo lang("btn_cancel") ?>'
        });
        
    //Basic
    $('#sa-basic').click(function(){
        swal("Here's a message!");
    });

    //A title with a text under
    $('#sa-title').click(function(){
        swal("Here's a message!", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lorem erat eleifend ex semper, lobortis purus sed.")
    });

    //Success Message
    $('#sa-success').click(function(){
        swal("Good job!", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lorem erat eleifend ex semper, lobortis purus sed.", "success")
    });


    // added by sheraz for fee
    //Warning Message
    $("body").on('click', '.fee-status', function(){
        var val = $(this).attr("value");
        val = val.split(",");
        var id = val[0];
        var method = val[2];
        var student_id = val[1];
        swal({   
            title: "<?php echo lang('are_you_sure') ?>",   
            text: "<?php echo lang('fee_deactivate_status') ?>",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "<?php echo lang('yes_activate') ?>",   
            closeOnConfirm: true 
        }, function(){
            $.ajax({
                url: method,
                type: "POST",
                dataType: "html",
                data: { "id": id, "student_id": student_id},
                dataType: "json",
                success: function(data){
                    if(data.status == "success"){
                        angular.element(document.getElementById('page-wrapper')).scope().getfeeStatusRecords();
                        showNotification('<?php echo lang("success_app") ?>', data.message, "success");
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });
        });
    });

    //Warning Message
    $("body").on('click', '.fee-status1', function(){
        var val = $(this).attr("value");
        val = val.split(",");
        var id = val[0];
        var method = val[2];
        var student_id = val[1];
        var obj = val[3];
        swal({   
            title: "<?php echo lang('are_you_sure') ?>",   
            text: "<?php echo lang('fee_activate_status') ?>",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "<?php echo lang('yes_deactivte') ?>",   
            closeOnConfirm: true 
        }, function(){
            $.ajax({
                url: method,
                type: "POST",
                dataType: "html",
                data: { "id": id, "student_id": student_id},
                dataType: "json",
                success: function(data){
                    if(data.status == "success"){
                        angular.element(document.getElementById('page-wrapper')).scope().getfeeStatusRecords();
                        showNotification('<?php echo lang("success_app") ?>', data.message, "success");
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });
        });
    });

    //fee warnings end here


    // added by sheraz
    //Warning Message
    $("body").on('click', '.sa-status', function(){
        var val = $(this).attr("value");
        val = val.split(",");
        var id = val[0];
        var method = val[1];
        var role_id = val[2];
        swal({   
            title: "<?php echo lang('are_you_sure') ?>",   
            text: "<?php echo lang('status_message') ?>",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "<?php echo lang('yes_deactivte') ?>",   
            closeOnConfirm: true 
        }, function(){
            $.ajax({
                url: method,
                type: "POST",
                dataType: "html",
                data: { "id": id},
                dataType: "json",
                success: function(data){
                    if(data.status == "success"){
                        angular.element(document.getElementById('page-wrapper')).scope().getUsers(role_id);
                        showNotification('<?php echo lang("success_app") ?>', data.message, "success");
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });
        });
    });

    //Warning Message
    $("body").on('click', '.sa-status1', function(){
        var val = $(this).attr("value");
        val = val.split(",");
        var id = val[0];
        var method = val[1];
        var role_id = val[2];
        swal({   
            title: "<?php echo lang('are_you_sure') ?>",   
            text: "<?php echo lang('status_message1') ?>",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "<?php echo lang('yes_activate') ?>",   
            closeOnConfirm: true 
        }, function(){
            $.ajax({
                url: method,
                type: "POST",
                dataType: "html",
                data: { "id": id},
                dataType: "json",
                success: function(data){
                    if(data.status == "success"){
                        angular.element(document.getElementById('page-wrapper')).scope().getUsers(role_id);
                        showNotification('<?php echo lang("success_app") ?>', data.message, "success");
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });
        });
    });
    // added by sheraz end

    //Warning Message
    $("body").on('click', '.sa-warning', function(){
        var val = $(this).attr("value");
        val = val.split(",");
        var id = val[0];
        var method = val[1];
        swal({   
            title: "<?php echo lang('are_you_sure') ?>",   
            text: "<?php echo lang('delete_message') ?>",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "<?php echo lang('yes_delete') ?>",   
            closeOnConfirm: false 
        }, function(){
            $.ajax({
                url: method,
                type: "POST",
                dataType: "html",
                data: { "id": id},
                success: function(data){
                    if(data === "success"){
                        location.reload();
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });
        });
    });

    //added by yasir for online exam taken soft delete
    //Warning Message
    $("body").on('click', '.sa-warning-online-exam', function(){
        var val = $(this).attr("value");
        val = val.split(",");
        var id = val[0];
        var exam_id = val[1];
        var exam_detail_id = val[2];
        var method = val[3];
        swal({   
            title: "<?php echo lang('are_you_sure') ?>",   
            text: "<?php echo lang('delete_message') ?>",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "<?php echo lang('yes_delete') ?>",   
            closeOnConfirm: true 
        }, function(){
            $.ajax({
                url: method,
                type: "POST",
                dataType: "html",
                data: {"id": id, "exam_id": exam_id, "exam_detail_id":exam_detail_id},
                success: function(data){
                    if(data == "success"){
                        angular.element(document.getElementById('page-wrapper')).scope().getResults();
                        showNotification('<?php echo lang("success_app") ?>', '<?php echo lang("success_paper_taked_deleted"); ?>', "success");
                    }
                },
                error: function(error){
                    console.log(error.getMessage());
                }
            });
        });
    });
    
    //Warning Message
    $("body").on('click', '.sa-trash-recover-all', function(){
        var val = $(this).attr("value");
        val = val.split(",");
        var method = val[0];
        var table = val[1];
        swal({   
            title: "<?php echo lang('are_you_sure') ?>",   
            text: "<?php echo lang('recovery_message') ?>",   
            type: "info",   
            showCancelButton: true,   
            confirmButtonColor: "#5b5dea",   
            confirmButtonText: "<?php echo lang('yes_recover') ?>",   
            closeOnConfirm: false 
        }, function(){
            $.ajax({
                url: method,
                type: "POST",
                dataType: "html",
                data: { "table": table},
                success: function(data){
                    if(data === "success"){
                        location.reload();
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });
        });
    });

    //Warning Message
    $("body").on('click', '.sa-trash-recover-single', function(){
        var val = $(this).attr("value");
        val = val.split(",");
        var method = val[0];
        var table = val[1];
        var id = val[2];
        swal({   
            title: "<?php echo lang('are_you_sure') ?>",   
            text: "<?php echo lang('recovery_message') ?>",   
            type: "info",   
            showCancelButton: true,   
            confirmButtonColor: "#5b5dea",   
            confirmButtonText: "<?php echo lang('yes_single_recover') ?>",   
            closeOnConfirm: false 
        }, function(){
            $.ajax({
                url: method,
                type: "POST",
                dataType: "html",
                data: { "table": table, "id": id},
                success: function(data){
                    if(data === "success"){
                        location.reload();
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });
        });
    });

    //Warning Message
    $("body").on('click', '.sa-trash-delete-single', function(){
        var val = $(this).attr("value");
        val = val.split(",");
        var method = val[0];
        var table = val[1];
        var id = val[2];
        swal({   
            title: "<?php echo lang('are_you_sure') ?>",   
            text: "<?php echo lang('delete_message') ?>",   
            type: "info",   
            showCancelButton: true,   
            confirmButtonColor: "#5b5dea",   
            confirmButtonText: "<?php echo lang('yes_delete') ?>",   
            closeOnConfirm: false 
        }, function(){
            $.ajax({
                url: method,
                type: "POST",
                dataType: "html",
                data: { "table": table, "id": id},
                success: function(data){
                    if(data === "success"){
                        location.reload();
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });
        });
    });

    //-----------------
    $("body").on('click', '.sa-warning-others', function(){
        var val = $(this).attr("value");
        val = val.split(",");
        var id = val[0];
        var method = val[1];
        swal({   
            title: "<?php echo lang('are_you_sure') ?>",   
            text: "<?php echo lang('restore_message') ?>",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "<?php echo lang('lbl_yes') ?>",   
            closeOnConfirm: false 
        }, function(){
            $.ajax({
                url: method,
                type: "POST",
                dataType: "html",
                data: { "id": id},
                success: function(data){
                    if(data === "success"){
                        location.reload();
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });
        });
    });

    //Parameter
    $('#sa-params').click(function(){
        swal({   
            title: "Are you sure?",   
            text: "You will not be able to recover this imaginary file!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Yes, delete it!",   
            cancelButtonText: "No, cancel plx!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) {     
                swal("Deleted!", "Your imaginary file has been deleted.", "success");   
            } else {     
                swal("Cancelled", "Your imaginary file is safe :)", "error");   
            } 
        });
    });

    //Custom Image
    $('#sa-image').click(function(){
        swal({   
            title: "Govinda!",   
            text: "Recently joined twitter",   
            imageUrl: "../plugins/images/users/govinda.jpg" 
        });
    });

    //Auto Close Timer
    $('#sa-close').click(function(){
         swal({   
            title: "Auto close alert!",   
            text: "I will close in 2 seconds.",   
            timer: 2000,   
            showConfirmButton: false 
        });
    });


    },
    //init
    $.SweetAlert = new SweetAlert, $.SweetAlert.Constructor = SweetAlert
}(window.jQuery),

//initializing 
function($) {
    "use strict";
    $.SweetAlert.init()
}(window.jQuery);