<html>
    <head>
        <title><?php echo lang('print_out') ?></title>
        <base href = "<?php echo base_url(); ?>" />
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <style>
            @media print
            {    
                .no-print, .no-print *
                {
                    display: none !important;
                }
            }
            
            @media print{
                .result_card_container {
                    margin-top: -50px;
                }
                #fee_card_container{
                    margin-top: -50px;
                }
            }
            
            .result_card_container {
                font-size: 12px; 
                padding-top:25px;
                height: 27.5cm;
                margin-bottom: 5px;
                max-height:27.5cm;
                padding-left: 25px; 
                padding-right: 25px; 
                /*border: 3px solid black; */
                font-family: sans-serif;
            }
            #footer-table{
                margin-top:25px;
            }
            #fee_card_container {
                font-size: 12px; 
                height: 27.5cm; 
                max-height:27.5cm;
                padding-left: 25px; 
                padding-right: 25px; 
                border: 0px solid black; 
                font-family: sans-serif;
            }
            .hidden_row{
                display:none;
            }
            .border{
                border: 1px solid;
            }
            .border-top{
                border-top: 1px solid;
            }
            .border-right{
                border-right: 1px solid;
            }
            .border-bottom{
                border-bottom: 1px solid;
            }
            .border-left{
                border-left: 1px solid;
            }
        </style>
        <script>
        
            function loadFormLanguageWise(lang_id,lang_name,tag, class_id, batch_id, exam_id, std_id,fee_id, class_name, batch_name, emp_id, salary_type_id) {
                
                var formData = {
                    "lang_id": lang_id,
                    "tag": tag, 
                    "class_id": class_id, 
                    "batch_id": batch_id,
                    "exam_id": exam_id,
                    "student_id": std_id,
                    "fee_id": fee_id,
                    "class_name": class_name,
                    "batch_name": batch_name,
                    "employee_id": emp_id,
                    "salary_type_id": salary_type_id
                };
                
                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>forms/get_form_language_wise",
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        if(data.status === "success"){
                            $("#myContainer").html(data.data);
                            if(lang_name === 'english'){
                                $("#myContainer").css({"direction":"ltr"});
                            }else{
                                $("#myContainer").css({"direction":"rtl"});
                            }
                        } else if(data.status === "error"){
                            $("#myContainer").html(data.message);
                        }
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            }
       </script>
    </head>
    <body>
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="javascript:void(0)"><?= isset($template->name) ? $template->name : ''; ?></a>
                </div>
                <ul class="nav navbar-nav" <?php if($request_page == "forms" || $is_print_all == 'true') { echo "style='display:none;'"; } else { echo "style='display:block;'"; } ?>>
                    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0)"><b>Select a language</b> <span class="caret"></span></a>
                      <ul class="dropdown-menu">
                          <?php if(count($languages) > 0 ) {  foreach($languages as $lang) { ?>
                          <li><a href="javascript:void(0)" onclick="loadFormLanguageWise(<?php echo $lang->id; ?>,'<?php echo strtolower($lang->name); ?>','<?php echo $template->tag; ?>',<?= $class_id ?>, <?= $batch_id ?>, <?= $exam_id ?>, <?= $student_id ?>,<?= $fee_get_id_from_url; ?>,'<?= isset($class_name)?$class_name:NULL; ?>','<?= isset($batch_name)?$batch_name:NULL; ?>', <?= $employee_id; ?>, <?= $salary_type_id; ?>)"><?php echo $lang->name; ?></a></li>
                          <?php } } else {  ?>
                          <li><a href="javascript:void(0)" onclick="loadFormLanguageWise(1,'english','<?php echo $template->tag; ?>',<?= $class_id ?>, <?= $batch_id ?>, <?= $exam_id ?>, <?= $student_id ?>,<?= $fee_get_id_from_url; ?>,'<?= isset($class_name)?$class_name:NULL; ?>','<?= isset($batch_name)?$batch_name:NULL; ?>',<?= $employee_id; ?>,<?= $salary_type_id; ?>)">English</a></li>
                          <li><a href="javascript:void(0)" onclick="loadFormLanguageWise(2,'arabic','<?php echo $template->tag; ?>',<?= $class_id ?>, <?= $batch_id ?>, <?= $exam_id ?>, <?= $student_id ?>,<?= $fee_get_id_from_url; ?>,'<?= isset($class_name)?$class_name:NULL; ?>','<?= isset($batch_name)?$batch_name:NULL; ?>',<?= $employee_id; ?>,<?= $salary_type_id; ?>)">Arabic</a></li>
                          <?php } ?>
                      </ul>
                    </li>
                  </ul>
                  <a href="javascript:onclick=window.print();" class="btn btn-primary navbar-btn pull-right"><i class="fa fa-print"></i> <?php echo lang('lbl_print'); ?></a>
            </div>
        </nav>
        <div class="container" style="margin-top:50px">
            <div class="row">
                <div class="col-md-12" id="myContainer">
                    <?php if($request_page == 'result_card' && $is_print_all == "true") { ?>
                        <?php foreach($html as $yval) { ?>
                            <?= $yval; ?>
                        <?php } ?>
                    <?php } else { ?>
                        <?= $html; ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </body>
</html>