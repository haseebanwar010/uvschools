<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('lbl_page_settings');?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="#"><?php echo lang('lbl_dashboard');?></a></li>
                    <li class="active"><?php echo lang('crumb_all_messages');?></li>

                </ol>
            </div>
        </div>

        <!-- Page Content -->

        <!-- row -->
        <div class="hint"><?php echo lang('help_page_settings'); ?></div>
        <div class="row" ng-controller="landingPageControler" ng-init="initStats(); initTheme();">
            <!-- Left sidebar -->
            <div class="col-md-12">
                <div class="white-box">
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12 list-group mail-list m-t-5">
                            <a href="<?php echo base_url(); ?><?php echo ($this->session->userdata("userdata")["sh_url"]); ?>" class="btn btn-primary" target="blank" style="margin-bottom: 15px;"><i class="fa fa-eye"></i><?php echo lang('view_page'); ?></a>
                            <a href="javascript:void(0)" class="list-group-item active" id="slider" ng-click="slider()"><?php echo lang('lbl_slider'); ?></a>
                            <a href="javascript:void(0)" class="list-group-item" id="theme" ng-click="theme()"><?php echo lang('slider_theme_settings'); ?></a>
                            <a href="javascript:void(0)" class="list-group-item" id="news" ng-click="news()"><?php echo lang('lbl_news'); ?></a>
                            <a href="javascript:void(0)" class="list-group-item" id="classes" ng-click="classes()"><?php echo lang('lbl_classes'); ?></a>
                            <a href="javascript:void(0)" class="list-group-item" id="video" ng-click="video()"><?php echo lang('lbl_video_section'); ?></a>
                            <a href="javascript:void(0)" class="list-group-item" id="gallery" ng-click="gallery()"><?php echo lang('lbl_gallery'); ?></a>
                            <a href="javascript:void(0)" class="list-group-item" id="teachers" ng-click="teachers()"><?php echo lang('lbl_teachers'); ?></a>
                            <a href="javascript:void(0)" class="list-group-item" id="stats" ng-click="stats()"><?php echo lang('school_statistics'); ?></a>
                            <a href="javascript:void(0)" class="list-group-item" id="background_images" ng-click="background_images()"><?php echo lang('lbl_background_image'); ?></a>
                            <a href="javascript:void(0)" class="list-group-item" id="links" ng-click="links()"><?php echo lang('lbl_social_links'); ?></a>
                            
                        </div>
                        <div class="col-lg-10 col-md-9 col-sm-12 col-xs-12">
                            <div id="sliderDiv">
                                <?php echo $slider; ?>
                            </div>
                            <div id="newsDiv" style="display: none">
                                <?php echo $news; ?>
                            </div>
                            <div id="classesDiv" style="display: none">
                                <span class="text-danger"><?php echo lang('classes_message_alert'); ?></span>
                                <?php echo $classes; ?>
                            </div>
                            <div id="videoDiv" style="display: none">
                                <?php echo $video; ?>
                            </div>
                            <div id="galleryDiv" style="display: none;">
                                <span class="text-danger"><?php echo lang('gallery_message_alert'); ?></span>
                                <?php echo $gallery; ?>
                            </div>
                            <div id="teachersDiv" style="display: none">
                                <span class="text-danger"><?php echo lang('teachers_message_alert'); ?></span>
                                <?php echo $teachers; ?>
                            </div>
                            <div id="statsDiv" style="display: none">
                                <div class="alert alert-dismissable {{alert.type}}" ng-if="alert.message"> 
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>     
                                    {{ alert.message }}
                                </div>
                                <form ng-submit="update(filterModel)">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('total_students'); ?></label>
                                        <input type="number" ng-model="filterModel.students" value="{{filterModel.students}}" name="passport_number" class="form-control" >
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('total_classes'); ?></label>
                                        <input type="number" ng-model="filterModel.classes" value="{{filterModel.classes}}" name="passport_number" class="form-control" >
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('total_employees'); ?></label>
                                        <input type="number" ng-model="filterModel.emp" value="{{filterModel.emp}}" name="passport_number" class="form-control" >
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('total_busses'); ?></label>
                                        <input type="number" ng-model="filterModel.bus" value="{{filterModel.bus}}" name="passport_number" class="form-control" >
                                    </div>
                                    <div>
                                        <button type="submit" class="btn-1d  fcbtn btn  btn-primary btn-1b pull-right"><?php echo lang('btn_profile_update'); ?></button> 
                                        <div class="clear"></div>
                                    </div>
                                </form>
                            </div>
                            <div id="backgroundDiv" style="display: none">
                                <?php echo $background; ?>
                            </div>
                            <div id="linksDiv" style="display: none">
                                <?php echo $social; ?>
                            </div>
                            <div id="themeDiv" style="display: none">
                                <div class="alert alert-dismissable {{alert.type1}}" ng-if="alert.message1"> 
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>     
                                    {{ alert.message1 }}
                                </div>
                                <form ng-submit="updateTheme(filterModel)">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo lang('heading_font_size'); ?></label>
                                                <input type="number" ng-model="filterModel.heading_size" value="{{filterModel.heading_size}}" class="form-control">
                                            </div>  
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo lang('heading_color'); ?></label>
                                                <div class="input-group">
                                                    <input type="text" id="hexcode" class="form-control" value="{{filterModel.heading_color}}" placeholder="#000000" readonly="" autocomplete="off">
                                                    <span class="input-group-btn" id="basic-addon1">
                                                        <input type='text' style="background-color: white" id='customcolor'/>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo lang('sub_heading_font_size'); ?></label>
                                                <input type="number" ng-model="filterModel.sub_heading_size" value="{{filterModel.sub_heading_size}}" class="form-control">
                                            </div>  
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo lang('sub_heading_color'); ?></label>
                                                <div class="input-group">
                                                    <input type="text" id="hexcode1" class="form-control" value="{{filterModel.sub_heading_color}}" placeholder="#000000" readonly="" autocomplete="off">
                                                    <span class="input-group-btn" id="basic-addon1">
                                                        <input type='text' style="background-color: white" id='customcolor1'/>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo lang('description_font_size'); ?></label>
                                                <input type="number" ng-model="filterModel.description_size" value="{{filterModel.description_size}}" class="form-control">
                                            </div>  
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo lang('description_color'); ?></label>
                                                <div class="input-group">
                                                    <input type="text" id="hexcode2" class="form-control" value="{{filterModel.description_color}}" placeholder="#000000" readonly="" autocomplete="off">
                                                    <span class="input-group-btn" id="basic-addon1">
                                                        <input type='text' style="background-color: white" id='customcolor2'/>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn-1d  fcbtn btn  btn-primary btn-1b pull-right"><?php echo lang('btn_profile_update'); ?></button> 
                                        <div class="clear"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
        $(document).ready(function(){
         $('#customcolor1').spectrum({
            preferredFormat: "hex",
            showInput: true,
            color:$('#hexcode1').val(),
            change: function(tinycolor){
                $('#hexcode1').val($('#customcolor1').spectrum('get'));
            }
        });
     });
        $(document).ready(function(){
         $('#customcolor2').spectrum({
            preferredFormat: "hex",
            showInput: true,
            color:$('#hexcode2').val(),
            change: function(tinycolor){
                $('#hexcode2').val($('#customcolor2').spectrum('get'));
            }
        });
     });
</script> 
<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>