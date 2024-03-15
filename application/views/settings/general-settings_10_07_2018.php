<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<div>
    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?= lang('crumb_general_settings') ?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="#"><?= lang('crumb_dashboard') ?></a></li>
                        <li><a href="#"><?= lang('crumb_settings') ?></a></li>
                        <li class="active"><?= lang('crumb_general_settings') ?></li>
                    </ol>
                </div>
            </div>
            
            <?php $error = $this->session->flashdata('alert'); if(!empty($error)) { ?>
                <div class="alert alert-dismissable <?php if($this->session->flashdata('alert')['status'] == 'error') { echo 'alert-danger'; } else {echo 'alert-success'; }?>"> 
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>     
                    <?= $this->session->flashdata("alert")['message']; ?> 
                </div>
            <?php } ?>
            <!-- /.row -->
            <!-- Page Content start here -->
            <div class="hint"><?php echo lang('help_general_setting'); ?></div>
            <!--.row-->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-inverse">
                        <!--<div class="panel-heading">General Settings</div>-->
                        <div class="panel-wrapper collapse in" aria-expanded="true">
                            <div class="panel-body">
                                <ul class="nav customtab nav-tabs" role="tablist">
                                    <li role="presentation" class="nav-item">
                                        <a href="#general" class="nav-link <?php if($this->session->flashdata('selected_tab') == 'general') { echo 'active';} else if(empty($this->session->flashdata('selected_tab'))){ echo 'active';  } ?>" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false">
                                            <span class="visible-xs"><i class="fa fa-user"></i></span>
                                            <span class="hidden-xs"><?= lang('tab_school_info') ?></span>
                                        </a>
                                    </li>
                                    <li role="presentation" class="nav-item">
                                        <a href="#address" class="nav-link <?php if($this->session->flashdata('selected_tab') == 'address') { echo 'active';} ?>" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false">
                                            <span class="visible-xs"><i class="fa fa-phone"></i></span> 
                                            <span class="hidden-xs"><?= lang('tab_address') ?></span>
                                        </a>
                                    </li>
                                    <li role="presentation" class="nav-item">
                                        <a href="#others" class="nav-link <?php if($this->session->flashdata('selected_tab') == 'others') { echo 'active';} ?>" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false">
                                            <span class="visible-xs"><i class="fa fa-home"></i></span> 
                                            <span class="hidden-xs"><?= lang('tab_other_information') ?></span>
                                        </a>
                                    </li>
                                </ul>  
                                <!--tab content start here-->
                                <div class="tab-content">
                                    <div class="tab-pane <?php if($this->session->flashdata('selected_tab') == 'general') { echo 'active';} else if(empty($this->session->flashdata('selected_tab'))){ echo 'active';  } ?>" id="general">
                                        <?php foreach ($generalSettings as $setting) { ?>
                                        <div class="hint"><?php echo lang('help_school_info'); ?></div>
                                        <form action="settings/saveGeneralInfo" method="post" enctype="multipart/form-data" class="form-material ">
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label"><?= lang('lbl_school_name') ?></label>
                                                                <input type="text" id="name" name="name" class="form-control" required="" placehodler="School name" value="<?= $setting->name; ?>">
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label"><?= lang('lbl_institution_type') ?></label>
                                                                <select class="form-control"  required="" name="institution_type" data-placeholder="Choose a Category" tabindex="1">
                                                                    <option value="school" <?php if($setting->institution_type == "school"){ echo "selected"; } ?>><?= lang('option_school') ?></option>
<!--                                                                    <option value="university" <?php if($setting->institution_type == "university"){ echo "selected"; } ?>><?= lang('option_university') ?></option>
                                                                    <option value="college" <?php if($setting->institution_type == "college"){ echo "selected"; } ?>><?= lang('option_college') ?></option>-->
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                    </div>
                                                    <!--/row-->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label"><?= lang('plc_school_email'); ?></label>
                                                                <input type="text" id="email" name="email" required="" class="form-control" value="<?= $setting->email; ?>" placeholder="info@yahoo.com">
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label"><?= lang('lbl_phone') ?></label>
                                                                <input type="text" name="phone" class="form-control" required="" value="<?= $setting->phone; ?>" placeholder="<?= lang('lbl_phone') ?>">
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                    </div>
                                                    <!--/row-->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label"><?= lang('lbl_web_site') ?></label>
                                                                <input type="text" class="form-control" name="website" value="<?= $setting->website; ?>" placeholder="http://www.school.com">
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label"><?= lang('lbl_url_setting') ?></label>
                                                                <div class="input-group">
                                                                    <span class="input-group-addon" id="basic-addon3" style="background:transparent; padding-left:5px; border-left:0; border-top:0;">https://uvschools.com/</span>
                                                                    <input type="text" class="form-control" required="" name="url" disabled="" value="<?= $setting->url; ?>" placeholder="http://www.uvschools.com/school">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label"><?= lang('lbl_logo_setting')." <small>(100px*25px)</small>" ?></label>
                                                                <input type="file" name="fileToUpload" class="form-control">
                                                            </div>
                                                            <img src="uploads/logos/<?= $setting->logo; ?>" alt="school-image" style="max-width:300px;"/>
                                                        </div>
                                                        <!--/span-->

                                                    </div>

                                                    <div class="form-actions pull-right">
                                                        <button type="submit" class="btn btn-primary "> 
                                                            <i class="fa fa-check"></i> <?= lang('btn_save') ?>
                                                        </button>
                                                        <button type="button" class="btn btn-default"><?= lang('btn_cancel') ?></button>
                                                    </div>
                                                </div>
                                            </form>
                                        <?php } ?>
                                    </div>
                                    <div class="tab-pane <?php if($this->session->flashdata('selected_tab') == 'address') { echo 'active';} ?>" id="address">
                                        <div class="hint"><?php echo lang('help_school_address'); ?></div>
                                        <?php foreach ($generalSettings as $setting1) { ?>
                                            <form action="settings/saveGeneralInfo" method="post" class="form-material" id="Address">
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-8 ">
                                                            <div class="form-group">
                                                                <label><?= lang('lbl_address') ?></label>
                                                                <input type="text" class="form-control" required="" name="address" value="<?= $setting1->address ?>" placeholder="<?php echo lang('lbl_address'); ?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!--row-->
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label><?= lang('lbl_country') ?></label>
                                                                <select name="country" required="" class="form-control">
                                                                    <?php foreach($countries as $country) { ?>
                                                                    <option value="<?= $country->country_name; ?>" <?php if($setting1->country == $country->country_name) { echo "selected"; } ?>><?= $country->country_name; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--row-->

                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label><?= lang('lbl_city') ?></label>
                                                                <input type="text" class="form-control" required="" name="city" value="<?= $setting1->city ?>" placeholder="City">
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>

                                                <div class="form-actions pull-right">
                                                    <button type="submit" class="btn btn-primary "> <i class="fa fa-check"></i> <?= lang('btn_save') ?></button>
                                                    <button type="button" class="btn btn-default"><?= lang('btn_cancel') ?></button>
                                                </div>
                                            </form>
                                        <?php } ?>
                                    </div>             
                                    <div class="tab-pane <?php if($this->session->flashdata('selected_tab') == 'others') { echo 'active';} ?>" id="others">
                                        <div class="hint"><?php echo lang('help_school_other_information'); ?></div>
                                        <?php foreach ($generalSettings as $setting2) { ?>
                                            <form  action="settings/saveGeneralInfo" method="post" class="form-material" id="Others">
                                                <div class="form-body">

                                                    <div class="row">
                                                        <div class="col-md-6 ">
                                                            <div class="form-group">
                                                                <label><?= lang('lbl_time_zone') ?></label>
                                                                <select name="time_zone" required="" class="form-control">
                                                                    <?php foreach ($timezones as $zone) { ?>
                                                                    <option value="<?= $zone; ?>" <?php if($setting2->time_zone == $zone){ echo "selected"; } ?>><?= $zone; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label><?= lang('lbl_currency_symbol') ?></label>
                                                                <select name="currency_symbol" required="" class="form-control">
                                                                    <?php foreach ($symbols as $symbol) { ?>
                                                                    <option value="<?php echo $symbol->code ?>" <?php if($setting2->currency_symbol == $symbol->code){ echo "selected"; } ?>><?php echo $symbol->symbol?> - <?php echo $symbol->name?></option>
                                                                    <?php } ?> 
                                                                </select>
                                                                <!--<select name="currency_symbol" required="" class="form-control">
                                                                    <option value="dollar" <?php if($setting2->currency_symbol == "dollar"){ echo "selected"; } ?>>$ <?= lang('dollar') ?></option>
                                                                    <option value="euro" <?php if($setting2->currency_symbol == "euro"){ echo "selected"; } ?>>€ <?= lang('euro') ?></option>
                                                                    <option value="ld" <?php if($setting2->currency_symbol == "ld"){ echo "selected"; } ?>><?= lang('dinar') ?></option>
                                                                    <option value="pounds" <?php if($setting2->currency_symbol == "pounds"){ echo "selected"; } ?>>£ <?= lang('pound') ?></option>
                                                                </select>-->
                                                            </div>
                                                        </div>
                                                        <!--/span-->

                                                    </div>


                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label><?= lang('lbl_start_day_week') ?></label>
                                                                <select name="start_day_of_the_week" required="" class="form-control">
                                                                    <option value="">---Select a day---</option>
                                                                    <option value="Monday" <?php if($setting2->start_day_of_the_week==='Monday'){ echo "selected"; }?>>Monday</option>
                                                                    <option value="Tuesday" <?php if($setting2->start_day_of_the_week==='Tuesday'){ echo "selected"; }?>>Tuesday</option>
                                                                    <option value="Wednesday" <?php if($setting2->start_day_of_the_week==='Wednesday'){ echo "selected"; }?>>Wednesday</option>
                                                                    <option value="Thrusday" <?php if($setting2->start_day_of_the_week==='Thrusday'){ echo "selected"; }?>>Thrusday</option>
                                                                    <option value="Friday" <?php if($setting2->start_day_of_the_week==='Friday'){ echo "selected"; }?>>Friday</option>
                                                                    <option value="Saturday" <?php if($setting2->start_day_of_the_week==='Saturday'){ echo "selected"; }?>>Saturday</option>
                                                                    <option value="Sunday" <?php if($setting2->start_day_of_the_week==='Sunday'){ echo "selected"; }?>>Sunday</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label><?= lang('lbl_start_date_financial') ?></label>
                                                                
                                                                <input type="text" name="financial_year_start_date" class="form-control mydatepicker-autoclose" required="" value="<?php echo ($setting2->financial_year_start_date=='0000-00-00')?"":to_html_date($setting2->financial_year_start_date); ?>" placeholder="<?= lang('lbl_start_date_financial') ?>" />
                                                                <!--<input type="date" name="financial_year_start_date" required="" value="<?= $setting2->financial_year_start_date; ?>" placeholder="Financial year start date" class="form-control">-->
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label><?= lang('lbl_theme_color') ?></label>
                                                                <div class="input-group">
                                                                    <input type="text" name="theme_color" id="hexcode" class="form-control" placeholder="#000000" readonly="" value="<?php echo $setting2->theme_color; ?>"/>
                                                                    <!--<select name="theme_color" required="" class="form-control">
                                                                        <option value="light" <?php if($setting2->theme_color == "light"){ echo "selected"; } ?>>Light</option>
                                                                        <option value="dark" <?php if($setting2->theme_color == "dark"){ echo "selected"; } ?>>Dark</option>
                                                                    </select>-->
                                                                    <span class="input-group-btn" id="basic-addon1">
                                                                        <input type='text' style="background-color: white" id='customcolor'/>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
<!--                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label><?= lang('lbl_theme_color') ?></label>
                                                                <input type="text" name="theme_color" class="form-control" placeholder="#000000" value="<?php echo $setting2->theme_color; ?>"/>
                                                            </div>
                                                        </div>-->
                                                        <!--/span-->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label><?= lang('lbl_end_date_financial') ?></label>
                                                                <input type="text" name="financial_year_end_date" class="form-control mydatepicker-autoclose" value="<?php echo ($setting2->financial_year_end_date=='0000-00-00')?"":to_html_date($setting2->financial_year_end_date); ?>"  placeholder="<?= lang('lbl_end_date_financial') ?>" />
                                                                <!--<input type="date" name="financial_year_end_date" required="" value="<?= $setting2->financial_year_end_date; ?>" placeholder="Financial year end date" class="form-control">-->
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                    </div>
<!--                                                    value="<?php echo to_html_date($setting2->financial_year_end_date); ?>"-->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label><?= lang('lbl_teacher_department') ?></label>
                                                                <select name="teacher_dept_id" required="" class="form-control">
                                                                    <option value="">---<?php echo lang('lbl_select_teacher_department'); ?>---</option>
                                                                    <?php if(count($departments) > 0){ foreach($departments as $dept) { ?>
                                                                    <option value="<?php echo $dept->id; ?>" <?php if($setting2->teacher_dept_id == $dept->id){ echo "selected"; } ?>><?php echo $dept->name; ?></option>
                                                                    <?php } } else { ?>
                                                                        <option value=""><?php echo lang("no_record"); ?></option>
                                                                    <?php } ?>
                                                                    
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                        <div class="col-md-6">
                                                            <div class="form-group " style="margin-bottom: 15px;" >
                                                                <label><?php echo lang('working_days') ?></label>
                                                                <div class="checkbox checkbox-info checkbox-circle">
                                                                <?php foreach ($working_days as $key) { ?>
                                                                <div class="col-md-3">
                                                                <input value="<?php echo $key->label ?>" name="working_days[]" 
                                                                <?php echo ($key->val == 'true')?'checked':"" ?> type="checkbox">
                                                                <label > <?php echo $key->label ?></label>
                                                            </div>
                                                                <?php } ?>
                                                            </div>
                                                             </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="form-actions pull-right">
                                                    <button type="submit" class="btn btn-primary "> <i class="fa fa-check"></i> <?= lang('btn_save') ?></button>
                                                    <button type="button" class="btn btn-default"><?= lang('btn_cancel') ?></button>
                                                </div>
                                            </form>
                                        <?php } ?>
                                    </div>
                                </div>
                                <!--tab content end here-->
                            </div>
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
    <!-- /.container-fluid -->
    
    <script type="text/javascript">
        $(document).ready(function(){
         $('#customcolor').spectrum({
            preferredFormat: "hex",
            showInput: true,
            color:$('#hexcode').val(),
            change: function(tinycolor){
                $('#hexcode').val($('#customcolor').spectrum('get'));
            }
        });
     });
    </script> 
    <?php include(APPPATH . "views/inc/footer.php"); ?>
