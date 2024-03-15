<?php
    header('Content-Type: application/javascript');
    ob_start();
    require_once($_SERVER['DOCUMENT_ROOT'] . '/uv/master/index.php');
    //require_once($_SERVER['DOCUMENT_ROOT'].'/index.php');
    ob_end_clean();
    $CI =& get_instance();
    $role_id = $CI->session->userdata("userdata")["role_id"];
    $academic_year =  $CI->session->userdata("userdata")["academic_year"];
    $result = "SELECT * FROM sh_academic_years WHERE id='$academic_year'";
    $res = $CI->admin_model->dbQuery($result);
    
    $r = array();
    $r['start_date'] = "0";
    $r['end_date'] = "0";

    if(!empty($res)){
        $data = $res[0];
        $date1 = $data->start_date;
        $date2 = $data->end_date;
        $ts1 = strtotime($date1);
        $ts2 = strtotime($date2);
        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);
        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);
        $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
        $rr = explode("/",to_html_date($data->start_date));
        $ss = explode("/",to_html_date($data->end_date));
        $data->start_date = $rr[2]."/".$rr[1]."/".$rr[0]." 00:00:00"; 
        $data->end_date = $ss[2]."/".$ss[1]."/".$ss[0]." 00:00:00"; 
    }
    // print_r($data);die();
    // if(isset($data) count($data) > 0) {
    //     $date1 = $data->start_date;
    //     $date2 = $data->end_date;
    //     $ts1 = strtotime($date1);
    //     $ts2 = strtotime($date2);
    //     $year1 = date('Y', $ts1);
    //     $year2 = date('Y', $ts2);
    //     $month1 = date('m', $ts1);
    //     $month2 = date('m', $ts2);
    //     $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
    //     $rr = explode("/",to_html_date($data->start_date));
    //     $data->start_date = $rr[2]."/".$rr[1]."/".$rr[0]." 00:00:00";   
    // }
    else
    {
      $data = (object)$r;  
    
    }
    
    // getting school id for showing the weekdays in calendar
    $school_id = $CI->session->userdata("userdata")["sh_id"];
    //print_r($school_id);

    $school_days = "SELECT working_days FROM sh_school WHERE id='$school_id'";
    $data2 = $CI->admin_model->dbQuery($school_days);
    $noneWorkingDays = array();
    //$day = 
    foreach($data2[0] as $value) {
        
         foreach(json_decode($value) as $value1) {
            if($value1->val=='false'){
                if($value1->label == 'Monday'){
                     $noneWorkingDays[] = 'fc-mon';
                  
                }
                if($value1->label == 'Tuesday'){
                   $noneWorkingDays[] = "fc-tue";
                }
                if($value1->label == 'Wednesday'){
                     $noneWorkingDays[] = "fc-wed";
                  
                }
                if($value1->label == 'Thursday'){
                  $noneWorkingDays[] = "fc-thu";
                }
                if($value1->label == 'Friday'){
                    // array_push($noneWorkingDays, array('day' => 5));
                    $noneWorkingDays[] = "fc-fri";
                  
                }
                if($value1->label == 'Saturday'){
                  // array_push($noneWorkingDays, array('day' => 6));
                    $noneWorkingDays[] = "fc-sat";
                }
                if($value1->label == 'Sunday'){
                     $noneWorkingDays[] = "fc-sun";
                  
                }
                

                // $noneWorkingDays[] = $value1->label;
         }
        }  
    }
     // print_r($noneWorkingDays);
     // die();
   
?>
$(function(){
    var selectedLang = '<?php echo $CI->session->userdata("site_lang"); ?>';
    if(selectedLang == 'english'){
        selectedLang = 'en';
    } else if(selectedLang == 'arabic'){
        selectedLang = 'ar-ma';
    }

    var currentDate; // Holds the day clicked when adding a new event
    var currentEvent; // Holds the event object when editing an event

    $('#color').colorpicker(); // Colopicker
    
    // Here i define the base_url
    // var base_url='https://localhost/uv/myschool2/';
    var base_url = "https://"+window.location.host+"/";

    // Fullcalendar
    $('#calendar').fullCalendar({
        height: 500,
        lang: selectedLang,
        viewRender: function(view,element) {
            <?php foreach ($noneWorkingDays as $nd){ ?>
                
               $('.<?php echo $nd; ?>').addClass("non-working-day");
                
            
            <?php }?>
            <?php if($data->start_date == "0" || $data->end_date == "0" ) { ?>
                    
                $("#calendar").html('<?php echo lang("lbl_calendar_acdYearNotSet"); ?>');
            <?php } else{ ?>
            var start_date = new Date('<?php echo $data->start_date; ?>');
            var end_date = new Date('<?php echo $data->end_date; ?>');
            var now= new Date('<?php echo $data->start_date; ?>');
            var end = new Date('<?php echo $data->end_date; ?>');
            
            end.setMonth(end.getMonth() + <?php echo $diff; ?>); //Adjust as needed

            var int_start = view.intervalStart.toDate();

            int_start.setHours(0);
            int_start.setMinutes(0);

            var int_end = view.intervalEnd.toDate();

            int_end.setHours(0);
            int_end.setMinutes(0);

            int_end.setDate(int_end.getDate() - 1);


            if(start_date < int_start){
                $("#calendar .fc-prev-button").show();
            }else{
                $("#calendar .fc-prev-button").hide();
            }

            if(int_end < end_date){
                $("#calendar .fc-next-button").show();
            }else{
                $("#calendar .fc-next-button").hide();
            }

            
            
            
            
            
        <?php } ?>
            
        },
        
        
        header: {
            left: 'prev, next, today',
            center: 'title',
            right: 'month, basicWeek, basicDay'
        },
        //defaultDate: moment(new Date('<?php echo $data->start_date; ?>')),
        
        
        // Get all events stored in database
        eventLimit: true, // allow "more" link when too many events
        events: base_url+'calendar/getEvents',
        selectable: true,
        selectHelper: true,
        editable: true, // Make the event resizable true           
            select: function(start, end) {
                    
                $('#start').val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
                $('#end').val(moment(end).format('YYYY-MM-DD HH:mm:ss'));
                
                 // Open modal to add event
                 <?php if($role_id == '1'){ ?>
            modal({
                // Available buttons when adding
                
                buttons: {
                    add: {
                        id: 'add-event', // Buttons id
                        css: 'btn-success', // Buttons class
                        label: '<?php echo lang("lbl_calendar_AddBtn") ?>', // Buttons label
                        
                    }
                },
                title: '<?php echo lang("lbl_calendar_addEventHeading") ?>', // Modal title
                
            });
            <?php } ?>
            }, 

            eventRender: function(event, element)
                { 
                    element.find('.fc-title').append("<br/>" + event.event_type); 
                },

         eventDrop: function(event, delta, revertFunc,start,end) { 
            <?php if($role_id=='1'){?> 
            start = event.start.format('YYYY-MM-DD HH:mm:ss');
            if(event.end){
                end = event.end.format('YYYY-MM-DD HH:mm:ss');
            }else{
                end = start;
            }         
                       
               $.post(base_url+'calendar/dragUpdateEvent',{                            
                id:event.id,
                start : start,
                end :end
            }, function(result){
                $('.alert').addClass('alert-success animated slideInDown').text('<?php echo lang("lbl_calendar_updateMessage"); ?>');
                loadCalander();
                hide_notify();
            });
        <?php } ?>


          },
          eventResize: function(event,dayDelta,minuteDelta,revertFunc) { 
               <?php if($role_id=='1'){?>     
                start = event.start.format('YYYY-MM-DD HH:mm:ss');
            if(event.end){
                end = event.end.format('YYYY-MM-DD HH:mm:ss');
            }else{
                end = start;
            }         
                       
               $.post(base_url+'calendar/dragUpdateEvent',{                            
                id:event.id,
                start : start,
                end :end
            }, function(result){
                $('.alert').addClass('alert-success animated slideInDown').text('<?php echo lang("lbl_calendar_updateMessage"); ?>');
                loadCalander();
                hide_notify();

            });
        <?php } ?>
            },
          
        // Event Mouseover
        eventMouseover: function(calEvent, jsEvent, view){

            var tooltip = '<div class="event-tooltip">' + calEvent.description + '</div>';
            $("body").append(tooltip);

            $(this).mouseover(function(e) {
                $(this).css('z-index', 10000);
                $('.event-tooltip').fadeIn('500');
                $('.event-tooltip').fadeTo('10', 1.9);
            }).mousemove(function(e) {
                $('.event-tooltip').css('top', e.pageY + 10);
                $('.event-tooltip').css('left', e.pageX + 20);
            });
        },
        eventMouseout: function(calEvent, jsEvent) {
            $(this).css('z-index', 8);
            $('.event-tooltip').remove();
        },
        // Handle Existing Event Click
        eventClick: function(calEvent, jsEvent, view) {
            // Set currentEvent variable according to the event clicked in the calendar
            currentEvent = calEvent;
            
            // Open modal to edit or delete event
            modal({
                // Available buttons when editing
                
                buttons: {
                    delete: {
                        id: 'delete-event',
                        css: 'btn-danger',
                        label: '<?php echo lang("lbl_calendar_delBtn"); ?>'
                    },
                    update: {
                        id: 'update-event',
                        css: 'btn-success',
                        label: '<?php echo lang("lbl_calendar_updateBtn"); ?>'
                    }
                },
                title: '<?php echo lang("lbl_calendar_EditEventmsg"); ?> "' + calEvent.title + '"',
                event: calEvent,
           
            });
            
            
        },

    });

    // Prepares the modal window according to data passed
    function modal(data) {
        <?php if($role_id == '2' || $role_id == '4'){ ?>
            //console.log("1");
            //alert("1");
            $('.modal-title').html(data.title);
        // Clear buttons except Cancel
        $('.modal-footer button:not(".btn-default")').remove();
        // Set input values
        $('#tit').val(data.event ? data.event.title : '');        
        $('#desc').val(data.event ? data.event.description : '');
            $('#exampleModalCenter').modal('show');
        <?php } else{ ?>
        // Set modal title
        $('.modal-title').html(data.title);
        // Clear buttons except Cancel
        $('.modal-footer button:not(".btn-default")').remove();
        // Set input values
        $('#title').val(data.event ? data.event.title : '');        
        $('#description').val(data.event ? data.event.description : '');
        $('#color').val(data.event ? data.event.color : '#3a87ad');
        $('#event_type').val(data.event ? data.event.event_type : 'Event');
        $('#mode').val(data.event ? data.event.mode : 'private');
        // Create Butttons
        $.each(data.buttons, function(index, button){
            $('.modal-footer').prepend('<button type="button" id="' + button.id  + '" class="btn ' + button.css + '">' + button.label + '</button>')
        })
        //Show Modal
        $('#calendar-modal').modal('show');
        <?php } ?>
        
    }

    



    // Handle Click on Add Button
    
    $('#calendar-modal').on('click', '#add-event',  function(e){

         var event_type = document.getElementById("event_type");
            var eventTypeSelected = event_type.options[event_type.selectedIndex].value; 

            var holiday_type = document.getElementById("holiday_type");
            var holidaytypeSelected = holiday_type.options[holiday_type.selectedIndex].value; 

        if(validator(['title', 'description'])) {
            //console.log($('input[name="mode"]:checked').val());
            $.post(base_url+'calendar/addEvent', {
                title: $('#title').val(),
                description: $('#description').val(),
                color: $('#color').val(),
                start: $('#start').val(),
                end: $('#end').val(),
                event_type: eventTypeSelected,
                holiday_type: holidaytypeSelected,
                mode:$('input[name="mode"]:checked').val()
                
            }, function(result){
                $('.alert').addClass('alert-success animated slideInDown').text('<?php echo lang("lbl_calendar_updateMessage"); ?>');
                $('#calendar-modal').modal('hide');
                $('#calendar').fullCalendar("refetchEvents");
                hide_notify();
                loadCalander();
            });
        }
    });



    // Handle click on Update Button
    $('#calendar-modal').on('click', '#update-event',  function(e){
        var event_type = document.getElementById("event_type");
            var eventTypeSelected = event_type.options[event_type.selectedIndex].value; 

            var holiday_type = document.getElementById("holiday_type");
            var holidaytypeSelected = holiday_type.options[holiday_type.selectedIndex].value; 

        if(validator(['title', 'description'])) {
            $.post(base_url+'calendar/updateEvent', {
                id: currentEvent._id,
                title: $('#title').val(),
                description: $('#description').val(),
                color: $('#color').val(),
                event_type: eventTypeSelected,
                holiday_type: holidaytypeSelected,
                mode:$('input[name="mode"]:checked').val(),
            }, function(result){
                $('.alert').addClass('alert-success animated slideInDown').text('<?php echo lang("lbl_calendar_updateMessage"); ?>');
                $('#calendar-modal').modal('hide');
                $('#calendar').fullCalendar("refetchEvents");
                hide_notify();
                loadCalander();
                
            });
        }
    } );


    // Handle Click on Delete Button
    $('#calendar-modal').on('click', '#delete-event',  function(e){
        $.get(base_url+'calendar/deleteEvent?id=' + currentEvent._id, function(result){
            $('.alert').addClass('alert-success animated slideInDown').text('<?php echo lang("lbl_calendar_deleteMsg"); ?>');
            $('#calendar-modal').modal('hide');
            $('#calendar').fullCalendar("refetchEvents");
            hide_notify();
            loadCalander();
        });
    });

    function hide_notify()
    {
        setTimeout(function() {
                    $('.alert').removeClass('alert-success animated slideInDown').text('');
                }, 2000);
    }


    // Dead Basic Validation For Inputs
    function validator(elements) {
        var errors = 0;
        $.each(elements, function(index, element){
            if($.trim($('#' + element).val()) == '') errors++;
        });
        if(errors) {
            $('.error').html('<?php echo lang("lbl_calendar_validationError"); ?>');
            return false;
        }
        return true;
    }
});

function loadCalander()
{
    angular.element(document.getElementById('page-wrapper')).scope().get_Today_Events();
}