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
    
    else
    {
      $data = (object)$r;  
    
    }
    
    // getting school id for showing the weekdays in calendar
    $school_id = $CI->session->userdata("userdata")["sh_id"];
    $school_days = "SELECT working_days FROM sh_school WHERE id='$school_id'";
    $data2 = $CI->admin_model->dbQuery($school_days);
    $noneWorkingDays = array();
    
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
   
?>
var selectedDated = "";
var selectedDatedRequest = "";
var editDate = "";
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
    //var base_url='https://localhost/uv/myschool2/';
    var base_url = "https://"+window.location.host+"/";
    var global1 =     angular.element(document.querySelector('[ng-controller="syllabusControllerTest"]')).scope().filterModel;
    
    // Fullcalendar
    $('#calendar').fullCalendar({
        displayEventTime : false,
        height: 800,
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
        
        <?php
        $noneWorkingDaysHide = array(); 
        foreach($data2[0] as $value) {
        
         foreach(json_decode($value) as $value1) {
            if($value1->val=='false'){
                if($value1->label == 'Monday'){
                     $noneWorkingDaysHide[] = '1';
                  
                }
                if($value1->label == 'Tuesday'){
                   $noneWorkingDaysHide[] = "2";
                }
                if($value1->label == 'Wednesday'){
                     $noneWorkingDaysHide[] = "3";
                  
                }
                if($value1->label == 'Thursday'){
                  $noneWorkingDaysHide[] = "4";
                }
                if($value1->label == 'Friday'){
                    // array_push($noneWorkingDaysHide, array('day' => 5));
                    $noneWorkingDaysHide[] = "5";
                  
                }
                if($value1->label == 'Saturday'){
                  // array_push($noneWorkingDaysHide, array('day' => 6));
                    $noneWorkingDaysHide[] = "6";
                }
                if($value1->label == 'Sunday'){
                     $noneWorkingDaysHide[] = "0";
                  
                }
                

                // $noneWorkingDaysHide[] = $value1->label;
         }
        }  
    }
    $hideDays = implode(',', $noneWorkingDaysHide);
         ?>
        hiddenDays: [<?php echo $hideDays; ?>],
        header: {
            left: 'prev, next, today',
            center: 'title',
            right: 'month, basicWeek, basicDay'
        },
        //defaultDate: moment(new Date('<?php echo $data->start_date; ?>')),
        
        
        // Get all events stored in database
        eventLimit: true, 
        
        
        events: {
         url : base_url+'study_plan/getSyllabusCalendarRander',
         type: 'POST',
         data: {
            global1 : global1,
        },
        success: function(data) {
            selectedDated = [];
            selectedDatedRequest = [];
                data.forEach(myFunction);
                function myFunction(item, index) {
                    selectedDated.push(item.start);
                    selectedDatedRequest.push({'data': item.start, 'request_status': item.request_status});
            
                }
                //console.log(selectedDatedRequest[0].request_status);
        },
        error: function() {},

    eventRender: function(event, element) {
      
    },
        },
       
        selectable: true,
        selectHelper: true,
        editable: true, // Make the event resizable true           
            select: function(start, end) {
                var startDate = start.format('YYYY-MM-DD HH:mm:ss');
                //console.log(startDate);
                $('#start').val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
                $('#end').val(moment(end).format('YYYY-MM-DD HH:mm:ss'));
                 // Open modal to add event
                 <?php if($role_id == '1' || $role_id== '4'){ ?>
                
                if(selectedDated.includes(startDate) && selectedDatedRequest[0].request_status == "inprocess"){
                    $('#topic').prop("disabled", true);
                    $('#details_status').prop("disabled", true);
                }else if(selectedDated.includes(startDate) && selectedDatedRequest[0].request_status == "approved"){
                    $('#topic').prop("disabled", true);
                    $('#details_status').prop("disabled", true);
                }else if(selectedDated.includes(startDate) && selectedDatedRequest[0].request_status == "edit-request"){
                    $('#topic').prop("disabled", true);
                    $('#details_status').prop("disabled", true);
                }
                else{
            modal({
                // Available buttons when adding
                
                buttons: {
                    add: {
                        id: 'add-event', // Buttons id
                        css: 'btn-success', // Buttons class
                        label: '<?php echo lang("lbl_studyplan_AddBtn") ?>', // Buttons label
                        
                    }
                },
                title: '<?php echo lang("lbl_studyplan_AddBtn") ?>', // Modal title
                
            });
                        $('#topic').prop("disabled", false);
                        $('#details_status').prop("disabled", false);
                }

            <?php } ?>
            }, 
         eventDrop: function(event, delta, revertFunc,start,end) { 
            <?php if($role_id=='1' || $role_id== '4'){?> 
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
                $('.alert').addClass('alert-success animated slideInDown').text('<?php echo lang("lbl_studyplan_updateMessage"); ?>');
                loadCalander();
                hide_notify();
            });
        <?php } ?>


          },
          eventResize: function(event,dayDelta,minuteDelta,revertFunc) { 
               <?php if($role_id=='1' || $role_id== '4'){?>     
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
                $('.alert').addClass('alert-success animated slideInDown').text('<?php echo lang("lbl_studyplan_updateMessage"); ?>');
                loadCalander();
                hide_notify();

            });
        <?php } ?>
            },
        // Event Mouseover
        eventMouseover: function(calEvent, jsEvent, view){ 
            
            var tooltip = '<div class="event-tooltip">' + calEvent.description + '</div>';
            $("body").append(tooltip);
            var tooltip2 = '<div class="tooltiptopicevent">' + 'Topic ' + ': ' + calEvent.title + '</div>';
            $("body").append(tooltip2);
            $(this).mouseover(function(e) {
                $('.tooltiptopicevent').fadeIn('500');
                $('.tooltiptopicevent').fadeTo('10', 1.9);
                $(this).css('z-index', 10000);
                $('.event-tooltip').fadeIn('500');
                $('.event-tooltip').fadeTo('10', 1.9);
            }).mousemove(function(e) {
                $('.tooltiptopicevent').css('top', e.pageY + 10);
                $('.tooltiptopicevent').css('left', e.pageX + 20);
                $('.event-tooltip').css('top', e.pageY + 10);
                $('.event-tooltip').css('left', e.pageX + 20);
            });
        },
        eventMouseout: function(calEvent, jsEvent) {
            $(this).css('z-index', 8);
            $('.event-tooltip').remove();
            $('.tooltiptopicevent').remove();
             
        },
        // Handle Existing Event Click
        eventClick: function(calEvent, jsEvent, view) {
            // Set currentEvent variable according to the event clicked in the calendar
            currentEvent = calEvent;
            <?php if($role_id=='1' || $role_id== '4') { ?>
            
            // Open modal to edit or delete event
            var startDate = calEvent.start.format('YYYY-MM-DD HH:mm:ss');
            if(selectedDated.includes(startDate) && selectedDatedRequest[0].request_status == "inprocess" ){
                modal({
                    // Available buttons when editing
                    
                    buttons: {
                        
                    },
                    title: '<?php echo lang("lbl_calendar_EditEventmsg"); ?> "' + calEvent.title + '"',
                    event: calEvent,

                });
                $('#topic').prop("disabled", true);
                $('#details_status').prop("disabled", true);
            }else if(selectedDated.includes(startDate) && selectedDatedRequest[0].request_status == "approved"){
                modal({
                    // Available buttons when editing
                    
                    buttons: {
                        
                    },
                    title: '<?php echo lang("lbl_calendar_EditEventmsg"); ?> "' + calEvent.title + '"',
                    event: calEvent,
               
                });
                $('#topic').prop("disabled", true);
                $('#details_status').prop("disabled", false);
                $('#comment_field').prop("disabled", true);

            }else if(selectedDated.includes(startDate) && selectedDatedRequest[0].request_status == "edit-request"){
                modal({
                    // Available buttons when editing
                    
                    buttons: {
                        
                    },
                    title: '<?php echo lang("lbl_calendar_EditEventmsg"); ?> "' + calEvent.title + '"',
                    event: calEvent,
               
                });
                $('#topic').prop("disabled", true);
                $('#details_status').prop("disabled", true);
                $('#comment').prop("disabled", true);
            }
            else{
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
                $('#topic').prop("disabled", false);
                $('#details_status').prop("disabled", false);
                $('#comment').prop("disabled", false);
            }
            
            <?php }?>
            
        },

    });

    if($.isEmptyObject(global1)){
      
         $("#calendar").hide();
        
        }
    //$('#calendar').fullCalendar("refetchEvents");
    
     
    // Prepares the modal window according to data passed
    function modal(data) {
        
        <?php if($role_id == '4'){ ?>
             console.log(data.event);
        //if
        $('.modal-title').html(data.title);
        // Clear buttons except Cancel
        $('.modal-footer button:not(".btn-default")').remove();
        // Set input values
        if(data.event == undefined){

        }
        else if(data.event.status == 'Done'){
                $('#topic').addClass("custom_disable");
                $('#details_status').addClass("custom_disable");
                $('#custom-footer').addClass("custom_disable");
            } 

        $('#topic').val(data.event ? data.event.title : '');        
        $('#details_status').val(data.event ? data.event.status : 'Pending');
        $('#color').val(data.event ? data.event.color : '#3a87ad');
        $('#comment').val(data.event ? data.event.comment : '');
        // Create Butttons
        $.each(data.buttons, function(index, button){
            $('#custom-footer').prepend('<button type="button" id="' + button.id  + '" class="btn ' + button.css + '">' + button.label + '</button>')
        })
    
        //Show Modal
        $('#addWeekDetailModal').modal('show');
         
        <?php } else if($role_id == '1'){ ?>
        // Set modal title
        $('.modal-title').html(data.title);
        // Clear buttons except Cancel
        $('.modal-footer button:not(".btn-default")').remove();
        // Set input values
        $('#topic').val(data.event ? data.event.title : '');        
        $('#details_status').val(data.event ? data.event.status : 'Pending');
        $('#color').val(data.event ? data.event.color : '#3a87ad');
        $('#comment').val(data.event ? data.event.comment : '');
        // Create Butttons
        $.each(data.buttons, function(index, button){
            $('#custom-footer').prepend('<button type="button" id="' + button.id  + '" class="btn ' + button.css + '">' + button.label + '</button>')
        })
        //Show Modal
        $('#addWeekDetailModal').modal('show');
        <?php } ?>
        
    }

    
    $('#addWeekDetailModal').on('click', '#add-event',  function(e){

            var details_status = document.getElementById("details_status");
            var eventTypeSelected = details_status.options[details_status.selectedIndex].value; 
            console.log(eventTypeSelected);
            var scopevar =     angular.element(document.querySelector('[ng-controller="syllabusControllerTest"]')).scope().filterModel;
            
        if(validator(['topic', 'details_status'])) {
            //console.log($('input[name="mode"]:checked').val());
            $.post(base_url+'study_plan/saveWeekDetail_new', {
                scopevar: scopevar,
                topic: $('#topic').val(),
                start: $('#start').val(), 
                end: $('#end').val(),
                details_status: eventTypeSelected,
                comment: $('#comment').val(),
                color: $('#color').val(),
                
                
            }, function(result){
                $('.alert').addClass('alert-success animated slideInDown').text('<?php echo lang("lbl_studyplan_updateMessage"); ?>');
                $('#addWeekDetailModal').modal('hide');
                $('#calendar').fullCalendar("refetchEvents");
                loadCalander();
            });
        }
    });

    // Handle click on Update Button
    $('#addWeekDetailModal').on('click', '#update-event',  function(e){

            
            var details_status = document.getElementById("details_status");
            var eventTypeSelected = details_status.options[details_status.selectedIndex].value; 
            console.log(eventTypeSelected);
            var scopevar =     angular.element(document.querySelector('[ng-controller="syllabusControllerTest"]')).scope().filterModel; 
            var otherprameter = angular.element(document.querySelector('[ng-controller="syllabusControllerTest"]')).scope().addWeekModel;
            console.log(otherprameter);
        if(validator(['topic', 'details_status'])) {
            $.post(base_url+'study_plan/updateWeekDetails', { 
                id: currentEvent._id,
                scopevar: scopevar,
                topic: $('#topic').val(),
                color: $('#color').val(),
                details_status: eventTypeSelected,
                comment: $('#comment').val(),
            }, function(result){
                $('.alert').addClass('alert-success animated slideInDown').text('<?php echo lang("lbl_studyplan_updateMessage"); ?>');
                $('#addWeekDetailModal').modal('hide');
                $('#calendar').fullCalendar("refetchEvents");
                hide_notify();
                loadCalander();
                
            });
        }
    } );


    // Handle Click on Delete Button
    
    $('#addWeekDetailModal').on('click', '#delete-event',  function(e){
        $.get(base_url+'study_plan/delete_sylabus?id=' + currentEvent._id, function(result){
            $('.alert').addClass('alert-success animated slideInDown').text('<?php echo lang("lbl_studyplan_deleteMessage"); ?>');
            $('#addWeekDetailModal').modal('hide');
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
            $('.error').html('<?php echo lang("lbl_studyplan_validationError"); ?>');
            return false;
        }
        return true;
    }
 });


function loadCalander()
    {
        angular.element(document.getElementById('page-wrapper')).scope().get_Today_Events();
    }
function dayClick()
{
    element('.fc-button-today').click();
}
