<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<style>
    .googledrive_btn{
        background-color:#1a73e8;
        color:#fff;
        border: none;
    }
    .googledrive_btn:hover{
        -webkit-box-shadow: 0 8px 6px -6px #1a73e8;
	   -moz-box-shadow: 0 8px 6px -6px #1a73e8;
	    box-shadow: 0 8px 6px -6px #1a73e8;
    }
    .iti{
        width: 100% !important;
    }
    .iti--allow-dropdown input
    {
        padding-left: 100px !important; 
    }
</style>

<?php $site_language=$this->session->userdata('site_lang');
 if($site_language==" arabic " || $site_language=="arabic"){ ?>
 
<style>
    .iti__country-list
    {
        text-align: right !important;
    }
</style>

<?php } ?>

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
                                            <span class="visible-xs"><i class="fa fa-institution"></i></span>
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
                                            <span class="visible-xs"><i class="ti-wallet"></i></span> 
                                            <span class="hidden-xs"><?= lang('tab_other_information') ?></span>
                                        </a>
                                    </li>
                                    <li role="presentation" class="nav-item">
                                        <a href="#autonumbers" class="nav-link" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false">
                                            <span class="visible-xs"><i class="ti-palette"></i></span> 
                                            <span class="hidden-xs"><?php echo lang('auto_gen') ?></span>
                                        </a>
                                    </li>
                                    <li role="presentation" class="nav-item">
                                        <a href="#currency" class="nav-link" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false">
                                            <span class="visible-xs"><i class="ti-menu"></i></span> 
                                            <span class="hidden-xs"><?php echo lang('school_currencies') ?></span>
                                        </a>
                                    </li>
                                    
                                    <?php if($logged_roleid==1){ ?>
                                    <!--<li role="presentation" class="nav-item">-->
                                    <!--    <a href="#googledrive" class="nav-link" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false">-->
                                    <!--        <span class="visible-xs"><i class="ti-menu"></i></span> -->
                                    <!--        <span class="hidden-xs"><?php echo lang('gdrive') ?></span>-->
                                    <!--    </a>-->
                                    <!--</li>-->
                                    <?php } ?>
                                    
                                </ul>  
                                <!--tab content start here-->
                                <div class="tab-content">
                                    <div class="tab-pane <?php if($this->session->flashdata('selected_tab') == 'general') { echo 'active';} else if(empty($this->session->flashdata('selected_tab'))){ echo 'active';  } ?>" id="general">
                                        <?php foreach ($generalSettings as $setting) { ?>
                                        <div class="hint"><?php echo lang('help_school_info'); ?></div>
                                        <form action="settings/saveGeneralInfo" id="schools_settings_submit_rec" method="post" enctype="multipart/form-data" class="form-material ">
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
                                                                <select class="form-control"  required="" name="institution_type"  tabindex="1">
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
                                                                <input type="email" id="email" name="email" required="" class="form-control" value="<?= $setting->email; ?>" >
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label"><?= lang('lbl_phone') ?></label>
                                                                <div class="col-xs-12" id="remove_settings_instance" style="margin-bottom: 20px; position: absolute; width: auto; ">
                                                                    <input type="text" name="phone" class="form-control" id="phone" required="" value="<?= $setting->phone; ?>" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        
                                                        <!--/span-->
                                                    </div>
                                                    <!--/row-->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label"><?= lang('lbl_web_site') ?></label>
                                                                <input type="text" class="form-control" name="website" value="<?= $setting->website; ?>" >
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label"><?= lang('lbl_url_setting') ?></label>
                                                                <div class="input-group">
                                                                    <span class="input-group-addon" id="basic-addon3" style="background:transparent; padding-left:5px; border-left:0; border-top:0;">https://uvschools.com/</span>
                                                                    <input type="text" class="form-control" required="" name="url" disabled="" value="<?= $setting->url; ?>" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label"><?= lang('lbl_logo_setting')." <small>(100px*100px)</small>" ?></label>
                                                                <input type="file" name="fileToUpload" class="form-control">
                                                            </div>
                                                            <img src="uploads/logos/<?= $setting->logo; ?>" alt="school-image" style="max-width:300px;"/>
                                                        </div>
                                                        <!--/span-->

                                                    </div>
                                                    
                                                    <div class="row" style="display: none;">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <input type="text" name="school_number" id="school_number" class="form-control" value="<?php echo $setting->u_phone_number; ?>" />
                                                            </div>
                                                        </div>
                                                    </div>    
                                                    
                                                    <div class="row" style="display: none;">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <input type="text" name="full_school_number" id="full_school_number" class="form-control" value="<?php echo $setting->phone; ?>" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    
                                                    <div class="row" style="display: none;">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <input type="text" name="def_coun_code" id="def_coun_code" class="form-control" value="<?php echo $default_country_code; ?>" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    

                                                    <div class="form-actions pull-right">
                                                        <button type="submit" class="btn btn-primary " id="school_update_info"> 
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
                                                                <input type="text" class="form-control" required="" name="address" value="<?= $setting1->address ?>" >
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
                                                                <input type="text" class="form-control" required="" name="city" pattern="^[a-zA-Z\u0600-\u06FF ]*$" value="<?= $setting1->city ?>" >
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
                                                        <!-- <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label><?= lang('lbl_currency_symbol') ?></label>
                                                                <select name="currency_symbol" required="" class="form-control">
                                                                    <?php foreach ($symbols as $symbol) { ?>
                                                                    <option value="<?php echo $symbol->code ?>" <?php if($setting2->currency_symbol == $symbol->code){ echo "selected"; } ?>><?php echo $symbol->symbol?> - <?php echo $symbol->name?></option>
                                                                    <?php } ?> 
                                                                </select>
                                                            </div>
                                                        </div> -->
                                                        <!--/span-->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label><?= lang('lbl_start_day_week') ?></label>
                                                                <select name="start_day_of_the_week" required="" class="form-control">
                                                                    <option value="">---<?php echo lang('select_a_day');?>---</option>
                                                                    <option value="Monday" <?php if($setting2->start_day_of_the_week==='Monday'){ echo "selected"; }?>><?php echo lang('monday') ?></option>
                                                                    <option value="Tuesday" <?php if($setting2->start_day_of_the_week==='Tuesday'){ echo "selected"; }?>><?php echo lang('tuesday') ?></option>
                                                                    <option value="Wednesday" <?php if($setting2->start_day_of_the_week==='Wednesday'){ echo "selected"; }?>><?php echo lang('wednesday') ?></option>
                                                                    <option value="Thrusday" <?php if($setting2->start_day_of_the_week==='Thrusday'){ echo "selected"; }?>><?php echo lang('thursday') ?></option>
                                                                    <option value="Friday" <?php if($setting2->start_day_of_the_week==='Friday'){ echo "selected"; }?>><?php echo lang('friday') ?></option>
                                                                    <option value="Saturday" <?php if($setting2->start_day_of_the_week==='Saturday'){ echo "selected"; }?>><?php echo lang('saturday') ?></option>
                                                                    <option value="Sunday" <?php if($setting2->start_day_of_the_week==='Sunday'){ echo "selected"; }?>><?php echo lang('sunday') ?></option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                    </div>


                                                    <div class="row">
                                                        
                                                        <!--/span-->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label><?= lang('lbl_start_date_financial') ?></label>
                                                                
                                                                <input type="text" name="financial_year_start_date" class="form-control mydatepicker-autoclose" required="" value="<?php echo ($setting2->financial_year_start_date=='0000-00-00')?"":to_html_date($setting2->financial_year_start_date); ?>"  />
                                                                <!--<input type="date" name="financial_year_start_date" required="" value="<?= $setting2->financial_year_start_date; ?>" placeholder="Financial year start date" class="form-control">-->
                                                            </div>
                                                        </div>
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
                                                        <!--/span-->
                                                    </div>
                                                    <div class="row">
                                                        
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
                                                                <input type="text" name="financial_year_end_date" required="" class="form-control mydatepicker-autoclose" value="<?php echo ($setting2->financial_year_end_date=='0000-00-00')?"":to_html_date($setting2->financial_year_end_date); ?>"   />
                                                                <!--<input type="date" name="financial_year_end_date" required="" value="<?= $setting2->financial_year_end_date; ?>" placeholder="Financial year end date" class="form-control">-->
                                                            </div>
                                                        </div>
                                                        <!-- <div class="col-md-6">
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
                                                        </div> -->
                                                        <!--/span-->
                                                        <div class="col-md-6">
                                                            <div class="form-group " style="margin-bottom: 15px;" >
                                                                <label><?php echo lang('working_days') ?></label>
                                                                <div class="checkbox checkbox-info checkbox-circle">
                                                                <?php foreach ($working_days as $key) { ?>
                                                                <div class="col-md-3">
                                                                <input id="day<?php echo $key->label ?>" value="<?php echo $key->label ?>" name="working_days[]" 
                                                                <?php echo ($key->val == 'true')?'checked':"" ?> type="checkbox">
                                                                <label for="day<?php echo $key->label ?>"> <?php echo lang(strtolower(substr($key->label, 0,3))) ?></label>
                                                            </div>
                                                                <?php } ?>
                                                            </div>
                                                             </div>
                                                        </div>
                                                    </div>
<!--                                                    value="<?php echo to_html_date($setting2->financial_year_end_date); ?>"-->
                                                    <!-- <div class="row"> -->
                                                        
                                                        <!--/span-->
                                                        
                                                        <!-- <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label><?php echo lang('lbl_account_deprtment'); ?></label>
                                                                <select name="accounts_dept_id" required="" class="form-control">
                                                                    <option value="">---Select Accounts Department---</option>
                                                                    <?php if(count($departments) > 0){ foreach($departments as $dept) { ?>
                                                                    <option value="<?php echo $dept->id; ?>" <?php if($setting2->accounts_dept_id == $dept->id){ echo "selected"; } ?>><?php echo $dept->name; ?></option>
                                                                    <?php } } else { ?>
                                                                        <option value=""><?php echo lang("no_record"); ?></option>
                                                                    <?php } ?>
                                                                    
                                                                </select>
                                                            </div>
                                                        </div> -->

                                                        
                                                    <!-- </div> -->

                                                </div>
                                                <div class="form-actions pull-right">
                                                    <button type="submit" class="btn btn-primary "> <i class="fa fa-check"></i> <?= lang('btn_save') ?></button>
                                                    <button type="button" class="btn btn-default"><?= lang('btn_cancel') ?></button>
                                                </div>
                                            </form>
                                        <?php } ?>
                                    </div>
                                    
                                    <div class="tab-pane" id="autonumbers">
                                        <div class="hint"><?php echo lang('help_auto_generated') ?></div>
                                        <?php echo $auto_numbers ?>
                                    </div>
                                    <div class="tab-pane" id="currency">
                                        <div class="hint"><?php echo lang('help_school_currencies') ?></div>
                                        <?php echo $school_currencies ?>
                                    </div>
                                    
                                    <!--Google Drive Settings Starts-->
                                    <?php if($logged_roleid==1){ ?>
                                    
                                    <!--<div class="tab-pane <?php if($this->session->flashdata('selected_tab') == 'enable_googledrive') { echo 'active';} ?>" id="googledrive">-->
                                    <!--    <div class="hint"><?php echo lang('enable_google_drive') ?></div>-->
                                    <!--        <form action="settings/upload_credentialsfile" method="post" enctype="multipart/form-data" class="form-material]">-->
                                    <!--            <div class="form-body">-->
                                                    
                                    <!--                <div class="row">-->
                                                        
                                    <!--                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">-->
                                    <!--                        <div class="inbox-center table-responsive" id="">-->
                                    <!--                            <h4>Google Drive Status</h4>-->
                                    <!--                            <?php if(file_exists($credentials_fileid)){ ?>-->
                                    <!--                                <?php if($enable_gd==0){ ?>-->
                                    <!--                                    <div class="col-sm-4">-->
                                    <!--                                        <a href="<?php echo base_url('settings/enable_googledrive');?>"><button type="button" class="btn googledrive_btn">Enable Google Drive</button></a>-->
                                    <!--                                    </div>-->
                                    <!--                                <?php } elseif($enable_gd==1){ ?>-->
                                    <!--                                    <div class="col-sm-4">-->
                                    <!--                                        <button type="button" class="btn btn-success" disabled="disabled">Enabled Google Drive</button>-->
                                    <!--                                    </div>-->
                                    <!--                                    <br>-->
                                    <!--                                    <div class="row">-->
                                    <!--                                        <div class="col-sm-6">-->
                                    <!--                                            <p>Do you want to disabel/remove google drive?</p>-->
                                    <!--                                            <a href="disable_gd"><button type="button" class="btn btn-danger">Disable/Remove</button></a>-->
                                    <!--                                        </div>-->
                                    <!--                                    </div>-->
                                                                        
                                                                        
                                    <!--                                <?php } ?>-->
                                    <!--                            <?php } else { ?>-->
                                    <!--                                <div class="col-md-6">-->
                                    <!--                                    <div class="form-group">-->
                                    <!--                                        <label class="control-label"><?= lang('credentials_file')?></label>-->
                                    <!--                                        <input type="file" name="gd_credentials" class="form-control" required>-->
                                    <!--                                    </div>-->
                                    <!--                                </div>-->
                                    <!--                            <?php } ?>-->
                                    <!--                        </div>-->
                                    <!--                    </div>-->
                                                        
                                    <!--                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">-->
                                    <!--                       <div class="inbox-center table-responsive" id="">-->
                                    <!--                           <h4>Google Drive Integration</h4>-->
                                                               <!--<h4><?php echo lang('gd_live_server_integration') ?></h4>-->
                                                               <!--<div class="alert alert-danger" style="padding: 10px; margin: 0px;">-->
                                                               <!--    <ul>-->
                                                               <!--        <li><?php echo lang('ins_import_dummy_data') ?></li>-->
                                                               <!--        <li><?php echo lang('ins_import_class_batches') ?></li>-->
                                                               <!--    </ul>-->
                                                               <!--</div>-->
                                                               
                                    <!--                            <a href="gd_guide" target="_blank"><button type="button" class="btn btn-primary "> -->
                                    <!--                                <i class="fa fa-check"></i> User Guide-->
                                    <!--                            </button></a>-->
                                                                
                                                                <!--<a href="gd_guide" target="_blank"><button type="button" class="btn btn-primary "> -->
                                                                <!--    <i class="fa fa-check"></i> <?= lang('gd_guide') ?>-->
                                                                <!--</button></a>-->
                                                                
                                                               <!--<ul>-->
                                                               <!--    <li><?php echo lang('gd_live_settings') ?>-->
                                                               <!--        <ul>-->
                                                               <!--            <li><?php echo lang('gd_live_settings1') ?></li>-->
                                                               <!--            <li><?php echo lang('gd_live_settings2') ?></li>-->
                                                               <!--            <li><?php echo lang('gd_live_settings3') ?></li>-->
                                                               <!--            <li><?php echo lang('gd_live_settings4') ?></li>-->
                                                               <!--            <li><?php echo lang('gd_live_settings5') ?></li>-->
                                                               <!--            <li><?php echo lang('gd_live_settings6') ?></li>-->
                                
                                                               <!--        </ul>-->
                                                               <!--    </li>   -->
                                                                   
                                                               <!--    <li><?php echo lang('gd_server_settings') ?>-->
                                                               <!--        <ul>-->
                                                               <!--            <li><?php echo lang('gd_server_settings1') ?></li>-->
                                                               <!--            <li><?php echo lang('gd_server_settings2') ?></li>-->
                                                               <!--            <li><?php echo lang('gd_server_settings3') ?></li>-->
                                
                                                               <!--        </ul>-->
                                                               <!--    </li>-->
                                                               <!--</ul>-->
                                
                                    <!--                       </div>-->
                                    <!--                   </div>-->
                                                       
                                    <!--                </div>-->

                                    <!--                <?php if(!file_exists($credentials_fileid)){ ?>-->
                                    <!--                    <div class="form-actions pull-right">-->
                                    <!--                        <button type="submit" class="btn btn-primary "> -->
                                    <!--                            <i class="fa fa-check"></i> <?= lang('btn_save') ?>-->
                                    <!--                        </button>-->
                                    <!--                        <button type="button" class="btn btn-default"><?= lang('btn_cancel') ?></button>-->
                                    <!--                    </div>-->
                                    <!--                <?php } ?>-->

                                    <!--            </div>-->
                                    <!--        </form>-->
                                    <!--</div>-->
                                    
                                    <?php } ?>
                                    <!--Google Drive Settings Ends-->
                                    
                                    
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js" integrity="sha512-DNeDhsl+FWnx5B1EQzsayHMyP6Xl/Mg+vcnFPXGNjUZrW28hQaa1+A4qL9M+AiOMmkAhKAWYHh1a+t6qxthzUw==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" integrity="sha512-gxWow8Mo6q6pLa1XH/CcH8JyiSDEtiwJV78E+D+QP0EVasFs8wKXq16G8CLD4CJ2SnonHr4Lm/yY2fSI2+cbmw==" crossorigin="anonymous" />

    <script type="text/javascript">
        $(document).ready(function(){
            
            country_value3="";
            document.getElementById('remove_settings_instance').textContent = '';
            document.getElementById('remove_settings_instance').innerHTML = '<input class="form-control" style="width:100%; padding-left: 90px; !important" type="text" name="phone" id="phone" required="">';
            
            var us_country=$('#def_coun_code').val();
            var complete_phone=$('#full_school_number').val();
            
            globalval= us_country;
            var input = document.querySelector("#phone");
            var iti = window.intlTelInput(input, {
            initialCountry: globalval,
            separateDialCode:true,
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/utils.js",
            });
            //console.log(iti);
            
            $("#phone").val($('#school_number').val());
            
            
            country_value3 = iti.getSelectedCountryData().iso2;
            $('#def_coun_code').val(country_value3);
            
            
            
            var dial_selectedCode = iti.getSelectedCountryData().dialCode;
            var array_comPhone=complete_phone.split('+');
            if(complete_phone!='')
            {
                if(array_comPhone[1])
                {
                    var my_phoneN=array_comPhone[1];
                    var onenumbers=my_phoneN.substring(0, 1);
                    var twonumbers=my_phoneN.substring(0, 2);
                    var threenumbers=my_phoneN.substring(0, 3);
                    if(dial_selectedCode==twonumbers)
                    {
                        var new_assig2phone=my_phoneN.split(dial_selectedCode);
                        if(new_assig2phone)
                        {
                            $("#phone").val(new_assig2phone[1]);
                        }
                    }
                    else if(dial_selectedCode==threenumbers)
                    {
                        var new_assig3phone=my_phoneN.split(dial_selectedCode);
                        if(new_assig3phone)
                        {
                            $("#phone").val(new_assig3phone[1]);
                        }
                    }
                    else if(dial_selectedCode==onenumbers)
                    {
                        var new_assig1phone=my_phoneN.split(dial_selectedCode);
                        if(new_assig1phone)
                        {
                            $("#phone").val(new_assig1phone[1]);
                        }
                    }
                }
                else
                {
                    var my_phoneM=complete_phone;
                    var onemnumbers=my_phoneM.substring(0, 1);
                    var twomnumbers=my_phoneM.substring(0, 2);
                    var threemnumbers=my_phoneM.substring(0, 3);
                    if(dial_selectedCode==twomnumbers)
                    {
                        var new_assig2phone=my_phoneM.split(dial_selectedCode);
                        if(new_assig2phone)
                        {
                            $("#phone").val(new_assig2phone[1]);
                        }
                    }
                    else if(dial_selectedCode==threemnumbers)
                    {
                        var new_assig3phone=my_phoneM.split(dial_selectedCode);
                        if(new_assig3phone)
                        {
                            $("#phone").val(new_assig3phone[1]);
                        }
                    }
                    else if(dial_selectedCode==onemnumbers)
                    {
                        var new_assig1phone=my_phoneM.split(dial_selectedCode);
                        if(new_assig1phone)
                        {
                            $("#phone").val(new_assig1phone[1]);
                        }
                    }
                    
                }
            }
            
            
            
            
            input.addEventListener('countrychange', function(e) {
                country_value3 = iti.getSelectedCountryData().iso2;
                $('#def_coun_code').val(country_value3);
            });
            
            
            
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
    
<script>
    
    $( "#school_update_info" ).click(function() {
        var cntry_code = $(".iti__selected-dial-code").text();
        var cntry_phone1 = $("#phone").val();
        var final_countryPhone = cntry_code+cntry_phone1;
        
        $('#school_number').val(final_countryPhone);
        
      $( "#schools_settings_submit_rec" ).submit();
    });
    
    
</script>
    
    
    <?php include(APPPATH . "views/inc/footer.php"); ?>
