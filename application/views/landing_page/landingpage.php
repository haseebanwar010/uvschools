
<?php include (APPPATH.'views/landing_page/landing_header.php');?>
            <!-- /Row -->
          
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
          <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
          <li data-target="#myCarousel" data-slide-to="1"></li>
          <li data-target="#myCarousel" data-slide-to="2"></li>
          <li data-target="#myCarousel" data-slide-to="3"></li>
        </ol>
      
        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
          <div class="item active">
            <img src="<?= base_url().'assets/sliders/' ?>/School Management Software.jpg" alt="School">
          </div>
      
          <div class="item">
            <img src="<?= base_url().'assets/sliders/' ?>/a.jpg" alt="parent">
          </div>
      
          <div class="item">
            <img src="<?= base_url().'assets/sliders/' ?>/desktop.jpg" alt="Desktop">
          </div>
          <div class="item">
            <img src="<?= base_url().'assets/sliders/' ?>/mobile app.jpg" alt="Mobile">
          </div>
          
      
        <!--</div>-->
      
        <!-- Left and right controls -->
        <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
          <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
          <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
      <div class="row">
                <div class="col-md-12 bg-danger1">
                    <div class="fix-width">
                        <div class="row">
                            <div class="col-md-12  m-t-20 m-b-20">
                                <?php
                                if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                                    <h1 class="fonts" style="color: white; "><?php echo lang('landing_lbl_start');?>  <span style="margin-top:-5px;">
                                    
        <a class="button1" href="<?php echo site_url('signup'); ?>" title="Get Started!"><?php echo lang('landing_lbl_free_trial');?> </a>
                                </span></h1><?php } ?>
                                <?php if ($this->session->userdata('site_lang') == "arabic" ) {?>
                                    <h1 class="fonts" style="color: white; "><?php echo lang('landing_lbl_start');?>  <span style="margin-top:-5px;">
                                    
        <a class="button1" href="<?php echo site_url('signup'); ?>" title="Get Started!"><?php echo lang('landing_lbl_free_trial');?> </a>
                                </span></h1><?php } ?>
                               
                             
                            </div>
                        </div>
                    </div>
                </div>
            </div>

      <br>
      <br>
      
            
            <div class="row">
  
                
            
            <div class="col-md-12 p-t-20" >
                <?php
                    if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                        <h1 class="text-center heading1"><?php echo lang('landing_lbl_system_feature_head'); 
                    } else {?>
                        <h1 class="text-center" style="font-size: 35px"><?php echo lang('landing_lbl_system_feature_head');
                    }
                ?>
                </h1></div><br><br><br><br>
                <div class="row m-r-40 m-l-40 p-t-20" style="display: contents;">
                    <?php
                        if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                            <div class="col-md-4 hdetails feature-box">
                                <h3 class="text-center"><?php echo lang('landing_lbl_simple_easy'); ?><br/> <?php echo lang('landing_lbl_use'); ?></h3>
                                <p class="text-center"><?php echo lang('landing_txt_system_user'); ?></p>
                            </div>
                            <div class="col-md-4 hdetails feature-box">
                                <h3 class="text-center"><?php echo lang('landing_lbl_united_license'); ?></h3>
                                <p class="text-center"><?php echo lang('landing_txt_unlimited_feature');?></p>
                            </div>
                            <div class="col-md-4 hdetails feature-box">
                                <h3 class="text-center"><?php echo lang('landing_lbl_powerful_feature'); ?></h3>
                                <p class="text-center"><?php echo lang('landing_txt_educational_institute');?> </p>
                            </div>
                    <?php  
                    } 
                        if ($this->session->userdata('site_lang') == "arabic" ) {?>
                            <div class="col-md-4 hdetails feature-box">
                                <h2 class="text-center"><?php echo lang('landing_lbl_simple_easy'); ?><br/> <?php echo lang('landing_lbl_use'); ?></h2>
                                <p class="text-center" style="font-size: 22px;"><?php echo lang('landing_txt_system_user'); ?></p>
                            </div>
                            <div class="col-md-4 hdetails feature-box">
                                <h2 class="text-center"><?php echo lang('landing_lbl_united_license'); ?></h2>
                                <p class="text-center" style="font-size: 22px;"><?php echo lang('landing_txt_unlimited_feature');?></p>
                            </div>
                            <div class="col-md-4 hdetails feature-box">
                                <h2 class="text-center"><?php echo lang('landing_lbl_powerful_feature'); ?></h2>
                                <p class="text-center" style="font-size: 22px;"><?php echo lang('landing_txt_educational_institute');?> </p>
                            </div>

                    <?php  
                        } ?>
              
                </div> 
            </div>
            <!-- /.row -->
            <br><br>
    <hr style="width: 30%">

             <!-- .row -->
             <div class="bg-texture" style="background-image: url('<?= base_url().'assets/landind page Background/' ?>/Azaming feature-01.jpg');background-position: center;background-size: cover; ">
            <div class="m-t-60 m-b-40" >
                <div class="col-md-12 m-t-20" id="features">
                <?php
                    if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                        <h1 class="text-center" style="color: white;"><?php echo lang('landing_lbl_flexibilty'); 
                    } else {?>
                        <h1 class="text-center" style="font-size: 45px; color:white"><?php echo lang('landing_lbl_flexibilty');
                    }
                ?>
                </h1></div>
            </div>
            <!-- /.row -->
            <br><br>
            <!-- .row -->
            <div class="container c-features">
                <div class="row m-t-60">
                    <!-- .col -->
                    <div class="col-xs-12 col-sm-6 col-lg-4">
			<div class="box">							
				<div class="icon">
					<div class="image"><img src="<?= base_url().'assets/new_Icons/' ?>/Multiuser.png" alt="parent"></div>
					<div class="info">
                    <?php 
                            if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                                <h4 class="title"><?php echo lang('landing_lbl_multiuser'); ?></h4>
                                <p class="fixed-lines"><?php echo lang('detailed_txt_multiuser'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/multiuser" title="Title Link">
                            <?php echo lang('lbl_read_more'); ?> <i class="fa fa-angle-double-right"></i>
							</a>
						</div>
                        <?php   }  
                            if ($this->session->userdata('site_lang') == "arabic" ) {?>
                                <h2 class="title" style="margin: 10px 0px -5px;"><?php echo lang('landing_lbl_multiuser'); ?></h2>
                                <p class="fixed-lines" style="font-size: 18px"><?php echo lang('detailed_txt_multiuser'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/multiuser" style="font-size: 16px;">
                            <i class="fa fa-angle-double-left"></i> <?php echo lang('lbl_read_more'); ?> 
							</a>
						</div>
                        <?php } ?>
					
					</div>
				</div>
				<div class="space"></div>
			</div> 
		</div>
			
        <div class="col-xs-12 col-sm-6 col-lg-4">
			<div class="box">							
				<div class="icon">
                <div class="image"><img src="<?= base_url().'assets/new_Icons/' ?>/Multilanguage.png" alt="parent"></div>
					<div class="info">
                    <?php 
                            if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                                <h4 class="title"><?php echo lang('landing_lbl_multi_language'); ?></h4>
                                <p class="fixed-lines"><?php echo lang('detailed_txt_multilang'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/multilanguage" title="Title Link">
                            <?php echo lang('lbl_read_more'); ?> <i class="fa fa-angle-double-right"></i>
							</a>
						</div>
                        <?php   }  
                            if ($this->session->userdata('site_lang') == "arabic" ) {?>
                                <h2 class="title" style="margin: 10px 0px -5px;"><?php echo lang('landing_lbl_multi_language'); ?></h2>
                                <p class="fixed-lines" style="font-size: 18px"><?php echo lang('detailed_txt_multilang'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/multilanguage" style="font-size: 16px;">
                            <i class="fa fa-angle-double-left"></i> <?php echo lang('lbl_read_more'); ?> 
							</a>
						</div>
                        <?php } ?>
						
					</div>
				</div>
				<div class="space"></div>
			</div> 
		</div>
			
        <div class="col-xs-12 col-sm-6 col-lg-4">
			<div class="box">							
				<div class="icon">
                <div class="image"><img src="<?= base_url().'assets/new_Icons/' ?>/attendance.png" alt="parent"></div>
					<div class="info">
                    <?php 
                            if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                                <h4 class="title"><?php echo lang('landing_lbl_student_employee_ttendance'); ?></h4>
                                <p class="fixed-lines"><?php echo lang('detailed_txt_s&e_attendance'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/dattendance" title="Title Link">
                            <?php echo lang('lbl_read_more'); ?> <i class="fa fa-angle-double-right"></i>
							</a>
						</div>
                       <?php   }  
                            if ($this->session->userdata('site_lang') == "arabic" ) {?>
                                <h2 class="title"  style="margin: 10px 0px -5px;"><?php echo lang('landing_lbl_student_employee_ttendance'); ?></h2>
                                <p class="fixed-lines" style="font-size: 18px"><?php echo lang('detailed_txt_s&e_attendance'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/dattendance" style="font-size: 16px;">
                            <i class="fa fa-angle-double-left"></i> <?php echo lang('lbl_read_more'); ?> 
							</a>
						</div>
                        <?php } ?>
						
					</div>
				</div>
				<div class="space"></div>
			</div> 
		</div>		    
        <div class="col-xs-12 col-sm-6 col-lg-4">
			<div class="box">							
				<div class="icon">
					<div class="image"><img src="<?= base_url().'assets/new_Icons/' ?>/Classes.png" alt="parent"></div>
					<div class="info">
                    <?php 
                            if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                                <h4 class="title"><?php echo lang('landing_lbl_classes_batches'); ?></h4>
                                <p class="fixed-lines"><?php echo lang('detailed_txt_class_batches'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/class_batches" title="Title Link">
                            <?php echo lang('lbl_read_more'); ?> <i class="fa fa-angle-double-right"></i>
							</a>
						</div>
                        <?php   }  
                            if ($this->session->userdata('site_lang') == "arabic" ) {?>
                                <h2 class="title" style="margin: 10px 0px -5px;"><?php echo lang('landing_lbl_classes_batches'); ?></h2>
                                <p class="fixed-lines" style="font-size: 18px"><?php echo lang('detailed_txt_class_batches'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/class_batches" style="font-size: 16px;">
                            <i class="fa fa-angle-double-left"></i> <?php echo lang('lbl_read_more'); ?> 
							</a>
						</div>
                                <?php } ?>
						
					</div>
				</div>
				<div class="space"></div>
			</div> 
        </div>
        <div class="col-xs-12 col-sm-6 col-lg-4">
			<div class="box">							
				<div class="icon">
					<div class="image"><img src="<?= base_url().'assets/new_Icons/' ?>/messaging.png" alt="parent"></div>
					<div class="info">
                    <?php 
                            if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                                <h4 class="title"><?php echo lang('landing_lbl_messaging'); ?></h4>
                                <p class="fixed-lines"><?php echo lang('detailed_txt_messaging'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/messaging" title="Title Link">
                            <?php echo lang('lbl_read_more'); ?> <i class="fa fa-angle-double-right"></i>
							</a>
						</div>
                        <?php   }  
                            if ($this->session->userdata('site_lang') == "arabic" ) {?>
                                <h2 class="title"  style="margin: 10px 0px -5px;"><?php echo lang('landing_lbl_messaging'); ?></h2>
                                <p class="fixed-lines" style="font-size: 18px"><?php echo lang('detailed_txt_messaging'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/messaging" style="font-size: 16px;">
                            <i class="fa fa-angle-double-left"></i> <?php echo lang('lbl_read_more'); ?> 
							</a>
						</div>
                                <?php } ?>
						
					</div>
				</div>
				<div class="space"></div>
			</div> 
        </div>
        <div class="col-xs-12 col-sm-6 col-lg-4">
			<div class="box">							
				<div class="icon">
					<div class="image"><img src="<?= base_url().'assets/new_Icons/' ?>/time table.png" alt="parent"></div>
					<div class="info">
                    <?php 
                            if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                                <h4 class="title"><?php echo lang('landing_lbl_timetable'); ?></h4>
                                <p class="fixed-lines"><?php echo lang('detailed_txt_timetable'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/dtimetable" title="Title Link">
                            <?php echo lang('lbl_read_more'); ?> <i class="fa fa-angle-double-right"></i>
							</a>
						</div>
                        <?php   }  
                            if ($this->session->userdata('site_lang') == "arabic" ) {?>
                                <h2 class="title"  style="margin: 10px 0px -5px;"><?php echo lang('landing_lbl_timetable'); ?></h2>
                                <p class="fixed-lines" style="font-size: 18px"><?php echo lang('detailed_txt_timetable'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/dtimetable" style="font-size: 16px;">
                            <i class="fa fa-angle-double-left"></i> <?php echo lang('lbl_read_more'); ?> 
							</a>
						</div>
                                <?php } ?>
						
					</div>
				</div>
				<div class="space"></div>
			</div> 
        </div>
        <div class="col-xs-12 col-sm-6 col-lg-4">
			<div class="box">							
				<div class="icon">
					<div class="image"><img src="<?= base_url().'assets/new_Icons/' ?>/trash.png" alt="parent"></div>
					<div class="info">
                    <?php 
                            if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                                <h4 class="title"><?php echo lang('lbl_trash'); ?></h4>
                                <p class="fixed-lines"><?php echo lang('detailed_txt_trash'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/dtrash" title="Title Link">
                            <?php echo lang('lbl_read_more'); ?> <i class="fa fa-angle-double-right"></i>
							</a>
						</div>
                       <?php   }  
                            if ($this->session->userdata('site_lang') == "arabic" ) {?>
                                <h2 class="title"  style="margin: 10px 0px -5px;"><?php echo lang('lbl_trash'); ?></h2>
                                <p class="fixed-lines" style="font-size: 18px"><?php echo lang('detailed_txt_trash'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/dtrash" style="font-size: 16px;">
                            <i class="fa fa-angle-double-left"></i> <?php echo lang('lbl_read_more'); ?> 
							</a>
						</div>
                                <?php } ?>
						
					</div>
				</div>
				<div class="space"></div>
			</div> 
        </div>     <div class="col-xs-12 col-sm-6 col-lg-4">
			<div class="box">							
				<div class="icon">
					<div class="image"><img src="<?= base_url().'assets/new_Icons/' ?>/reports.png" alt="parent"></div>
					<div class="info">
                    <?php 
                            if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                                <h4 class="title" ><?php echo lang('reports_all'); ?></h4>
                                <p class="fixed-lines"><?php echo lang('detailed_txt_reports'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/dreports" title="Title Link">
                            <?php echo lang('lbl_read_more'); ?> <i class="fa fa-angle-double-right"></i>
							</a>
						</div>
                        <?php   }  
                            if ($this->session->userdata('site_lang') == "arabic" ) {?>
                                <h2 class="title"  style="margin: 10px 0px -5px;"><?php echo lang('reports_all'); ?></h2>
                                <p class="fixed-lines" style="font-size: 18px"><?php echo lang('detailed_txt_reports'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/dreports" style="font-size: 16px;">
                            <i class="fa fa-angle-double-left"></i> <?php echo lang('lbl_read_more'); ?> 
							</a>
						</div>
                                <?php } ?>
						
					</div>
				</div>
				<div class="space"></div>
			</div> 
        </div>
        <div class="col-xs-12 col-sm-6 col-lg-4">
			<div class="box">							
				<div class="icon">
					<div class="image"><img src="<?= base_url().'assets/new_Icons/' ?>/accounts.png" alt="parent"></div>
					<div class="info">
                    <?php 
                            if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                                <h4 class="title"><?php echo lang('landing_lbl_accounts'); ?></h4>
                                <p class="fixed-lines"><?php echo lang('detailed_txt_accounts'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/daccounts" title="Title Link">
                            <?php echo lang('lbl_read_more'); ?> <i class="fa fa-angle-double-right"></i>
							</a>
						</div>
                        <?php   }  
                            if ($this->session->userdata('site_lang') == "arabic" ) {?>
                                <h2 class="title"  style="margin: 10px 0px -5px;"><?php echo lang('landing_lbl_accounts'); ?></h2>
                                <p class="fixed-lines" style="font-size: 18px"><?php echo lang('detailed_txt_accounts'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/daccounts" style="font-size: 16px;">
                            <i class="fa fa-angle-double-left"></i> <?php echo lang('lbl_read_more'); ?> 
							</a>
						</div>
                                <?php } ?>
						
					</div>
				</div>
				<div class="space"></div>
			</div> 
        </div>
        <div class="col-xs-12 col-sm-6 col-lg-4">
			<div class="box">							
				<div class="icon">
					<div class="image"><img src="<?= base_url().'assets/new_Icons/' ?>/payroll.png" alt="parent"></div>
					<div class="info">
                    <?php 
                            if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                                <h4 class="title"><?php echo lang('lbl_payroll'); ?></h4>
                                <p class="fixed-lines"><?php echo lang('detailed_txt_payroll'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/payroll" title="Title Link">
                            <?php echo lang('lbl_read_more'); ?> <i class="fa fa-angle-double-right"></i>
							</a>
						</div>
                        <?php   }  
                            if ($this->session->userdata('site_lang') == "arabic" ) {?>
                                <h2 class="title"  style="margin: 10px 0px -5px;"><?php echo lang('lbl_payroll'); ?></h2>
                                <p class="fixed-lines" style="font-size: 18px"><?php echo lang('detailed_txt_payroll'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/payroll" style="font-size: 16px;">
                            <i class="fa fa-angle-double-left"></i> <?php echo lang('lbl_read_more'); ?> 
							</a>
						</div>
                                <?php } ?>
						
					</div>
				</div>
				<div class="space"></div>
			</div> 
        </div>
        <div class="col-xs-12 col-sm-6 col-lg-4">
			<div class="box">							
				<div class="icon" >
					<div class="image"><img src="<?= base_url().'assets/new_Icons/' ?>/announcement.png" alt="parent"></div>
					<div class="info" >
                    <?php 
                            if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                                <h4 class="title"><?php echo lang('lbl_announcements'); ?></h4>
                                <p class="fixed-lines"><?php echo lang('detailed_txt_announcements'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/announcements" title="Title Link">
                            <?php echo lang('lbl_read_more'); ?> <i class="fa fa-angle-double-right"></i>
							</a>
						</div>
                       <?php   }  
                            if ($this->session->userdata('site_lang') == "arabic" ) {?>
                                <h2 class="title"  style="margin: 10px 0px -5px;"><?php echo lang('lbl_announcements'); ?></h2>
                                <p class="fixed-lines" style="font-size: 18px"><?php echo lang('detailed_txt_announcements'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/announcements" style="font-size: 16px;">
                            <i class="fa fa-angle-double-left"></i> <?php echo lang('lbl_read_more'); ?> 
							</a>
						</div>
                                <?php } ?>
						
					</div>
				</div>
				<div class="space"></div>
			</div> 
        </div>
        <div class="col-xs-12 col-sm-6 col-lg-4">
			<div class="box">							
				<div class="icon">
					<div class="image"><img src="<?= base_url().'assets/new_Icons/' ?>/user management.png" alt="parent"></div>
					<div class="info">
                    <?php 
                            if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                                <h4 class="title"><?php echo lang('landing_lbl_user_managment'); ?></h4>
                                <p class="fixed-lines"><?php echo lang('detailed_txt_user_managment'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/user_managment" title="Title Link">
                            <?php echo lang('lbl_read_more'); ?> <i class="fa fa-angle-double-right"></i>
							</a>
						</div>
                        <?php   }  
                            if ($this->session->userdata('site_lang') == "arabic" ) {?>
                                <h2 class="title"  style="margin: 10px 0px -5px;"><?php echo lang('landing_lbl_user_managment'); ?></h2>
                                <p class="fixed-lines" style="font-size: 18px"><?php echo lang('detailed_txt_user_managment'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/user_managment" style="font-size: 16px;">
                            <i class="fa fa-angle-double-left"></i> <?php echo lang('lbl_read_more'); ?> 
							</a>
						</div>
                                <?php } ?>
						
					</div>
				</div>
				<div class="space"></div>
			</div> 
        </div>
        <div class="col-xs-12 col-sm-6 col-lg-4">
			<div class="box">							
				<div class="icon">
					<div class="image"><img src="<?= base_url().'assets/new_Icons/' ?>/bookshop.png" alt="parent"></div>
					<div class="info">
                    <?php 
                            if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                                <h4 class="title"><?php echo lang('lbl_book_shop'); ?></h4>
                                <p class="fixed-lines"><?php echo lang('detailed_txt_bookshop'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/dbookshop" title="Title Link">
                            <?php echo lang('lbl_read_more'); ?> <i class="fa fa-angle-double-right"></i>
							</a>
						</div>
                       <?php   }  
                            if ($this->session->userdata('site_lang') == "arabic" ) {?>
                                <h2 class="title"  style="margin: 10px 0px -5px;"><?php echo lang('lbl_book_shop'); ?></h2>
                                <p class="fixed-lines" style="font-size: 18px"><?php echo lang('detailed_txt_bookshop'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/dbookshop" style="font-size: 16px;">
                            <i class="fa fa-angle-double-left"></i> <?php echo lang('lbl_read_more'); ?> 
							</a>
						</div>
                                <?php } ?>
						
					</div>
				</div>
				<div class="space"></div>
			</div> 
        </div>
        <div class="col-xs-12 col-sm-6 col-lg-4">
			<div class="box">							
				<div class="icon">
					<div class="image"><img src="<?= base_url().'assets/new_Icons/' ?>/study plan.png" alt="parent"></div>
					<div class="info">
                    <?php 
                            if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                                <h4 class="title"><?php echo lang('lbl_syllabus'); ?></h4>
                                <p class="fixed-lines"><?php echo lang('detailed_txt_study_plan'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/studyplan" title="Title Link">
                            <?php echo lang('lbl_read_more'); ?> <i class="fa fa-angle-double-right"></i>
							</a>
						</div>
                       <?php   }  
                            if ($this->session->userdata('site_lang') == "arabic" ) {?>
                                <h2 class="title"  style="margin: 10px 0px -5px;"><?php echo lang('lbl_syllabus'); ?></h2>
                                <p class="fixed-lines" style="font-size: 18px"><?php echo lang('detailed_txt_study_plan'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/studyplan" style="font-size: 16px;">
                            <i class="fa fa-angle-double-left"></i> <?php echo lang('lbl_read_more'); ?> 
							</a>
						</div>
                                <?php } ?>
						
					</div>
				</div>
				<div class="space"></div>
			</div> 
        </div>
        
        <div class="col-xs-12 col-sm-6 col-lg-4">
			<div class="box">							
				<div class="icon">
					<div class="image"><img src="<?= base_url().'assets/new_Icons/' ?>/examination.png" alt="parent"></div>
					<div class="info">
                    <?php 
                            if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                                <h4 class="title"><?php echo lang('landing_lbl_examination'); ?></h4>
                                <p class="fixed-lines"><?php echo lang('detailed_txt_online_examination'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/dexamination" title="Title Link">
                            <?php echo lang('lbl_read_more'); ?> <i class="fa fa-angle-double-right"></i>
							</a>
						</div>
                        <?php   }  
                            if ($this->session->userdata('site_lang') == "arabic" ) {?>
                                <h2 class="title"  style="margin: 10px 0px -5px;"><?php echo lang('landing_lbl_examination'); ?></h2>
                                <p class="fixed-lines" style="font-size: 18px"><?php echo lang('detailed_txt_online_examination'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/dexamination" style="font-size: 16px;">
                            <i class="fa fa-angle-double-left"></i> <?php echo lang('lbl_read_more'); ?> 
							</a>
						</div>
                                <?php } ?>
						
					</div>
				</div>
				<div class="space"></div>
			</div> 
        </div>
        <div class="col-xs-12 col-sm-6 col-lg-4">
			<div class="box">							
				<div class="icon">
					<div class="image"><img src="<?= base_url().'assets/new_Icons/' ?>/Finance.png" alt="parent"></div>
					<div class="info">
                    <?php 
                            if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                                <h4 class="title"><?php echo lang('landing_lbl_fee_collection'); ?></h4>
                                <p class="fixed-lines"><?php echo lang('detailed_txt_fee_collection'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/fee_collection" title="Title Link">
                            <?php echo lang('lbl_read_more'); ?> <i class="fa fa-angle-double-right"></i>
							</a>
						</div>
                        <?php   }  
                            if ($this->session->userdata('site_lang') == "arabic" ) {?>
                                <h2 class="title"  style="margin: 10px 0px -5px;"><?php echo lang('landing_lbl_fee_collection'); ?></h2>
                                <p class="fixed-lines" style="font-size: 18px"><?php echo lang('detailed_txt_fee_collection'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/fee_collection" style="font-size: 16px;">
                            <i class="fa fa-angle-double-left"></i> <?php echo lang('lbl_read_more'); ?> 
							</a>
						</div>
                                <?php } ?>
						
					</div>
				</div>
				<div class="space1"></div>
			</div> 
        </div>
        <div class="col-xs-12 col-sm-6 col-lg-4">
			<div class="box">							
				<div class="icon">
					<div class="image"><img src="<?= base_url().'assets/new_Icons/' ?>/study material.png" alt="parent"></div>
					<div class="info">
                    <?php 
                            if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                                <h4 class="title"><?php echo lang('landing_lbl_study_material'); ?></h4>
                                <p class="fixed-lines"><?php echo lang('detailed_txt_study_material'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/studymaterial" title="Title Link">
                            <?php echo lang('lbl_read_more'); ?> <i class="fa fa-angle-double-right"></i>
							</a>
						</div>
                        <?php   }  
                            if ($this->session->userdata('site_lang') == "arabic" ) {?>
                                <h2 class="title"  style="margin: 10px 0px -5px;"><?php echo lang('landing_lbl_study_material'); ?></h2>
                                <p class="fixed-lines" style="font-size: 18px"><?php echo lang('detailed_txt_study_material'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/studymaterial" style="font-size: 16px;">
                            <i class="fa fa-angle-double-left"></i> <?php echo lang('lbl_read_more'); ?> 
							</a>
						</div>
                                <?php } ?>
						
					</div>
				</div>
				<div class="space1"></div>
			</div> 
        </div>
        <div class="col-xs-12 col-sm-6 col-lg-4">
			<div class="box">							
				<div class="icon">
					<div class="image"><img src="<?= base_url().'assets/new_Icons/' ?>/online registration.png" alt="parent"></div>
					<div class="info">
                    <?php 
                            if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                                <h4 class="title"><?php echo lang('landing_lbl_onlineadmission'); ?></h4>
                                <p class="fixed-lines"><?php echo lang('detailed_txt_online_admission'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/onlineadmission" title="Title Link">
                            <?php echo lang('lbl_read_more'); ?> <i class="fa fa-angle-double-right"></i>
							</a>
						</div>
                        <?php   }  
                            if ($this->session->userdata('site_lang') == "arabic" ) {?>
                                <h2 class="title"  style="margin: 10px 0px -5px;"><?php echo lang('landing_lbl_onlineadmission'); ?></h2>
                                <p class="fixed-lines" style="font-size: 18px"><?php echo lang('detailed_txt_online_admission'); ?></p>
                                <div class="more">
							<a href="<?= base_url();?>multiuser/onlineadmission" style="font-size: 16px;">
                            <i class="fa fa-angle-double-left"></i> <?php echo lang('lbl_read_more'); ?> 
							</a>
						</div>
                                <?php } ?>
						
					</div>
				</div>
				<div class="space1"></div>
			</div> 
        </div>
                    <!-- /.col -->
                </div>
            </div>
             </div>
             
    

            
          
      <div class=" partners " style="background-color:white; display:contents">
            <div class=""  >
  <h2 style="padding-top:5%; font-weight:bolder; font-size:44px"><?php echo lang('lbl_our_clients'); ?></h2>
   <section class="customer-logos slider custom-logo" style="margin-top: 5%;margin-right:2%"> 
      
      <?php 

      for($i=0; $i<count($requests); $i++){?>
          <div class="slide"><div class="jumbotron client" style="margin-left: 50px;"><img class="client-img" style="height:150px; width:150px; border-radius:8px;" src="uploads/logos/<?php echo $requests[$i] -> logo; ?>"> </div><p class="customp" style="text-align: center; margin-left:50px"><?php echo $requests[$i] -> name; ?></p><p class="customp" style="text-align: center;margin-left:50px"><?php echo $requests[$i] -> country; ?></p> </div>
      <?php } ?>

   </section>
</div>
</div>



</div>
            <div class="row" style="background-image: url('<?= base_url().'assets/landind page Background/' ?>/Greenlight kb-01.jpg');background-position: center;background-size: cover; ">
              
            <div class="col-md-12 ">
                    <div class="fix-width " id="mobile-app">
                        
                    <?php
                        if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                            <div class="row">
                                <div class="col-md-6 auto-img1">
                                  <!-- <img src="<?= base_url().'assets/new_Icons/' ?>App mockup.png" class="app"> -->
                                  <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner app-slider"  style="padding-left: 100px;">
    <div class="carousel-item active">
    <img style="height: 480px; width:250px; border-radius:20px" src="<?= base_url().'assets/landingpage/' ?>images/1.png" class="app">
    </div>
    <div class="carousel-item">
    <img style="height: 480px;width:250px; border-radius:20px" src="<?= base_url().'assets/landingpage/' ?>images/2.png" class="app">
    </div>
    <div class="carousel-item">
    <img style="height: 480px;width:250px; border-radius:20px" src="<?= base_url().'assets/landingpage/' ?>images/3.png" class="app">
    </div>
    <div class="carousel-item">
    <img style="height: 480px;width:250px; border-radius:20px" src="<?= base_url().'assets/landingpage/' ?>images/4.png" class="app">
    </div>
    <div class="carousel-item">
    <img style="height: 480px;width:250px; border-radius:20px" src="<?= base_url().'assets/landingpage/' ?>images/5.png" class="app">
    </div>
    <div class="carousel-item">
    <img style="height: 480px;width:250px; border-radius:20px" src="<?= base_url().'assets/landingpage/' ?>images/6.png" class="app">
    </div>
    <div class="carousel-item">
    <img style="height: 480px;width:250px; border-radius:20px" src="<?= base_url().'assets/landingpage/' ?>images/7.png" class="app">
    </div>
    <div class="carousel-item">
    <img style="height: 480px;width:250px; border-radius:20px" src="<?= base_url().'assets/landingpage/' ?>images/8.png" class="app">
    </div>
    <div class="carousel-item">
    <img style="height: 480px;width:250px; border-radius:20px" src="<?= base_url().'assets/landingpage/' ?>images/9.png" class="app">
    </div>
    <div class="carousel-item">
    <img style="height: 480px;width:250px; border-radius:20px" src="<?= base_url().'assets/landingpage/' ?>images/10.png" class="app">
    </div>
  </div>
  <br><br>
  <br>  
</div>
                                
                                </div>
                                <div class="col-md-6 demo-text">   
                                    <h2 class="font-500" style="color: white;"><?php echo lang('landing_lbl_mobileApp'); ?> </h2>
                                    <h4 class="m-t-30 m-b-30" style="color: white;"><?php echo lang('landing_txt_mobileApp'); ?></h4>
                                    <div class="row col-12 text-center">
                                        
                                        <div class="col-md-6 app-icon"><a href="<?= base_url();?>multiuser/app_store" target="_blank"><img style="height:100px;" src="<?= base_url().'assets/new_Icons/' ?>Android App.png" /></a></div>
                                        <div class="col-md-6 app-icon"><a href="https://apps.apple.com/pk/app/uvschools/id1484920485" target="_blank"><img  style="height:100px; margin-bottom:80px;"  src="<?= base_url().'assets/new_Icons/' ?>ios app.png" /></a></div>
                                    </div>
                                    
                                </div>
                            </div>
                    <?php } ?>
                    <?php
                    if ($this->session->userdata('site_lang') == "arabic" ) {?>
                        <div class="row">
                            <div class="col-md-7 auto-img" style="margin-right: -25px;padding-right:100px; margin-top: 20px;"><img src="<?= base_url().'assets/new_Icons/' ?>App mockup.png" class="app" /></div>
  
                            <div class="col-md-4 demo-text">   
                                <h1 class=" m-t-30 font-500 app-arabic" style="font-size: 35px; font-weight:bolder;"><?php echo lang('landing_lbl_mobileApp'); ?> </h1>
                                <h3 class="m-t-30 m-b-30 app-arabic" style="margin-left:100px; font-size:30px"><?php echo lang('landing_txt_mobileApp'); ?></h3>
                                <div class="row col-12 text-center app-arabic">
                                        
                                        <div class="col-md-6 app-icon" ><a href="<?= base_url();?>multiuser/app_store" target="_blank"><img style="height:100px;" src="<?= base_url().'assets/new_Icons/' ?>Android App.png" /></a></div>
                                        <div class="col-md-6 app-icon" ><a href="https://apps.apple.com/pk/app/uvschools/id1484920485" target="_blank"><img  style="height:100px; margin-bottom:80px;"  src="<?= base_url().'assets/new_Icons/' ?>ios app.png" /></a></div>
                                    </div>
                            </div>
                            
                        </div>
                    <?php } ?>
                               
                                
                     </div>
                </div> 
                      
            </div>


<?php 

         
    $countryName = "Pakistan";
 ?>

        
            <div class="row" id="pricing">

<!--<section class="price-sec" >-->
<!--        <div class="container">-->
<!--            <div class="container">-->
<!--            <h1 style="text-align: center; font-weight:700 !important; margin-bottom:2%;">Our Pricing</h1>-->
<!--                <div class="row">-->
<!--                    <div class="col-sm-4 price-table">-->
<!--                        <div class="card text-center">-->
<!--                            <div class="title">-->
<!--                                <i class="fa fa-paper-plane"></i>-->
<!--                                <h2>Basic</h2>-->
<!--                            </div>-->
<!--                            <div class="price">-->
<!--                                <h4>Free</h4>-->
<!--                            </div>-->
<!--                            <div class="option">-->
<!--                                <ul>-->
<!--                                    <li><i class="fa fa-check"></i> 10 Basic Modules</li>-->
<!--                                    <li><i class="fa fa-check"></i> Call & Email Support</li>-->
<!--                                    <li><i class="fa fa-check"></i> Online Training</li>-->
<!--                                    <li><i class="fa fa-check"></i> Real Time Chat</li>-->
<!--                                    <li><i class="fa fa-times"></i> User Managment</li>-->
<!--                                    <li><i class="fa fa-times"></i> Student Transfer</li>-->
<!--                                </ul>-->
<!--                            </div>-->
<!--                            <a href="#" id="1" data-toggle="modal" data-target="#exampleModalLong">Choose Your Plan</a>-->
<!--                        </div>-->
<!--                    </div>-->
                    

                    <!-- (1) ===================================-->
<!--                       <div class="col-sm-4 price-table">-->
<!--                        <div class="card text-center">-->
<!--                            <div class="title">-->
<!--                                <i class="fa fa-plane"></i>-->
<!--                                <h2>Standard</h2>-->
<!--                            </div>-->
<!--                            <div class="price">-->
<!--                                <h4>Paid</h4>-->
<!--                            </div>-->
<!--                            <div class="option">-->
<!--                                <ul>-->
<!--                                    <li><i class="fa fa-check"></i> 17 Standard Modules</li>-->
<!--                                    <li><i class="fa fa-check"></i> Call & Email Support</li>-->
<!--                                    <li><i class="fa fa-check"></i> Online Training</li>-->
<!--                                    <li><i class="fa fa-check"></i> Real Time Chat</li>-->
<!--                                    <li><i class="fa fa-check"></i> User Managment</li>-->
<!--                                    <li><i class="fa fa-check"></i> Student Transfer</li>-->
<!--                                </ul>-->
<!--                            </div>-->
<!--                            <a href="#" id="2" data-toggle="modal" data-target="#exampleModalLong2">Choose Your Plan</a>-->
<!--                        </div>-->
<!--                    </div>-->
                    <!-- (2) ===================================-->
<!--                       <div class="col-sm-4 price-table">-->
<!--                        <div class="card text-center">-->
<!--                            <div class="title">-->
<!--                                <i class="fa fa-rocket"></i>-->
<!--                                <h2>Premium</h2>-->
<!--                            </div>-->
<!--                            <div class="price">-->
<!--                                <h4>Paid</h4>-->
<!--                            </div>-->
<!--                            <div class="option">-->
<!--                                <ul>-->
<!--                                    <li><i class="fa fa-check"></i> 22 Standard Modules</li>-->
<!--                                    <li><i class="fa fa-check"></i> Call & Email Support</li>-->
<!--                                    <li><i class="fa fa-check"></i> Online Training</li>-->
<!--                                    <li><i class="fa fa-check"></i> Real Time Chat</li>-->
<!--                                    <li><i class="fa fa-check"></i> User Managment</li>-->
<!--                                    <li><i class="fa fa-check"></i> Student Transfer</li>-->
<!--                                </ul>-->
<!--                            </div>-->
<!--                            <a href="#" id="3" data-toggle="modal" data-target="#exampleModalLong3">Choose Your Plan</a>-->
<!--                        </div>-->
<!--                    </div>-->
                    <!-- (3) ===================================-->

<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </section>-->
 

<!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" >
    <div class="modal-content">
      <div class="modal-header" >
        <h2 class="modal-title text-center" id="exampleModalLongTitle"  >Basic Plan (Access to 10 Basic Modules)</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
    
        <div class="container my-5">
 
  <div class="row flex justify-content-between">
        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;"src="<?= base_url().'assets/new_Icons/' ?>/setting.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Settings</a></h5>
            </div>
          </div>



        </div>

        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <div > <img style="height: 50px; width:50px;"src="<?= base_url().'assets/new_Icons/' ?>/employee.png" alt="parent"></div>
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Employee</a></h5>
              
            </div>
          </div>
        </div>

        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <div > <img style="height: 50px; width:50px;"src="<?= base_url().'assets/new_Icons/' ?>/student.png" alt="parent"></div>
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Student</a></h5>
              
            </div>
          </div>
        </div>

        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/parents.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Guardian</a></h5>
              
            </div>
          </div>
        </div>

        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/attendance.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Attendance</a></h5>
             
            </div>
          </div>
        </div>

        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/academics.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Academics</a></h5>
             
            </div>
          </div>
        </div>

        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/Finance.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Fee</a></h5>
             
            </div>
          </div>
        </div>

        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/forms.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Forms</a></h5>
              
            </div>
          </div>
        </div>
        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/reports.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Reports</a></h5>
              
            </div>
          </div>
        </div>
        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/examination.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Examination</a></h5>
              
            </div>
          </div>
        </div>
        <div class="col-md-3 p-2 text-center ignore">
          <div class=" px-3 py-5">
          <svg height="40px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                viewBox="0 0 60.002 60.002" style="enable-background:new 0 0 60.002 60.002;" xml:space="preserve">
              <g>
                <path d=""/>
                
              </g>
          </svg>
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#"></a></h5>
              
            </div>
          </div>
        </div>
        <div class="col-md-3 p-2 text-center ignore">
          <div class=" px-3 py-5">
          <svg height="40px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                viewBox="0 0 60.002 60.002" style="enable-background:new 0 0 60.002 60.002;" xml:space="preserve">
              <g>
                <path d=""/>
                
              </g>
          </svg>
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#"></a></h5>
              
            </div>
          </div>
        </div>
        
        

  </div>
</div>
      </div>
     
      <div class="">
      
        <hr>
        <h3 style="text-align: center;">Contact Us</h3>
         <?php if($countryName == 'Pakistan') { ?>
          <div class="">
            <div class="col-sm-6 modal-contact1" >
            <h2 style="padding-top: 20px; margin-top:0px;">Pakistan</h2>
            <i class="fa fa-whatsapp" style="color: green;font-size:20px;" aria-hidden="true"></i> <a href="#" dir='ltr'><?php echo lang('landing_pakistan_contact'); ?></a><br><br>
            <a href="#"><i class="fa fa-envelope" style="font-size:20px;" aria-hidden="true"></i> <?php echo lang('landing_united_vision_email'); ?></a></p>
            </div>
    <div class="col-sm-6 modal-contact2">
             <h2>Get Your Free</h2>
             <a class="button1" href="<?php echo site_url('signup'); ?>" title="Get Started!"><?php echo lang('landing_lbl_free_trial');?> </a>

    </div>
          </div>

         <?php } elseif($countryName == 'Libya') { ?>
          <div class="">
            <div class="col-sm-6 modal-contact1" >
            <h2 style="padding-top: 20px; margin-top:0px;">Libya</h2>
            <i class="fa fa-whatsapp" style="color: green;font-size:20px;" aria-hidden="true"></i> <a href="#" dir='ltr'><?php echo lang('landing_libya_contact'); ?></a><br><br>
            <a href="#"><i class="fa fa-envelope" style="font-size:20px;" aria-hidden="true"></i> <?php echo lang('landing_united_vision_email'); ?></a></p>
            </div>
    <div class="col-sm-6 modal-contact2">
             <h2>Get Your Free</h2>
             <a class="button1" href="<?php echo site_url('signup'); ?>" title="Get Started!"><?php echo lang('landing_lbl_free_trial');?> </a>

    </div>
          </div>
          <?php } elseif($countryName == 'Sudan') { ?>
            <div class="">
            <div class="col-sm-6 modal-contact1" >
            <h2 style="padding-top: 20px; margin-top:0px;">Sudan</h2>
            <i class="fa fa-whatsapp" style="color: green;font-size:20px;" aria-hidden="true"></i> <a href="#" dir='ltr'><?php echo lang('landing_sudan_contact'); ?></a><br><br>
            <a href="#"><i class="fa fa-envelope" style="font-size:20px;" aria-hidden="true"></i> <?php echo lang('landing_united_vision_email'); ?></a></p>
            </div>
    <div class="col-sm-6 modal-contact2">
             <h2>Get Your Free</h2>
             <a class="button1" href="<?php echo site_url('signup'); ?>" title="Get Started!"><?php echo lang('landing_lbl_free_trial');?> </a>

    </div>
          </div>
          <?php } else { ?>
            <div class="">
            <div class="col-sm-6 modal-contact1" >
            <h2 style="padding-top: 20px; margin-top:0px;">Pakistan</h2>
            <i class="fa fa-whatsapp" style="color: green;font-size:20px;" aria-hidden="true"></i> <a href="#" dir='ltr'><?php echo lang('landing_pakistan_contact'); ?></a><br><br>
            <a href="#"><i class="fa fa-envelope" style="font-size:20px;" aria-hidden="true"></i> <?php echo lang('landing_united_vision_email'); ?></a></p>
            </div>
    <div class="col-sm-6 modal-contact2">
             <h2>Get Your Free</h2>
             <a class="button1" href="<?php echo site_url('signup'); ?>" title="Get Started!"><?php echo lang('landing_lbl_free_trial');?> </a>

    </div>
          </div>
          <?php } ?>
      </div>
    </div>
  </div>
</div>
<!-- Modal2 -->
<div class="modal fade" id="exampleModalLong2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" >
    <div class="modal-content">
      <div class="modal-header" >
        <h2 class="modal-title text-center" id="exampleModalLongTitle"  >Standard Plan (Access to 16 Standard Modules)</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
    
        <div class="container my-5">
 
  <div class="row flex justify-content-between">
        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
         <div > <img style="height: 50px; width:50px;"src="<?= base_url().'assets/new_Icons/' ?>/setting.png" alt="parent"></div>
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Settings</a></h5>
           
            </div>
          </div>
        </div>

        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <div > <img style="height: 50px; width:50px;"src="<?= base_url().'assets/new_Icons/' ?>/employee.png" alt="parent"></div>
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Employee</a></h5>
              
            </div>
          </div>
        </div>

        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <div > <img style="height: 50px; width:50px;"src="<?= base_url().'assets/new_Icons/' ?>/student.png" alt="parent"></div>
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Student</a></h5>
              
            </div>
          </div>
        </div>

        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/parents.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Guardian</a></h5>
              
            </div>
          </div>
        </div>

        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/attendance.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Attendance</a></h5>
             
            </div>
          </div>
        </div>

        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/academics.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Academics</a></h5>
             
            </div>
          </div>
        </div>

        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/messaging.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Messages</a></h5>
             
            </div>
          </div>
        </div>

        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/study material.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Study Material</a></h5>
              
            </div>
          </div>
        </div>
        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/bookshop.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Bookshop</a></h5>
              
            </div>
          </div>
        </div>
        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/Finance.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Fee Collection</a></h5>
              
            </div>
          </div>
        </div>
        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/forms.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Forms</a></h5>
              
            </div>
          </div>
        </div>
        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/reports.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Reports</a></h5>
              
            </div>
          </div>
        </div>
        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/examination.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Examination</a></h5>
              
            </div>
          </div>
        </div>
        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/study plan.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Study Plan</a></h5>
              
            </div>
          </div>
        </div>
        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <svg height="40px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                viewBox="0 0 60.002 60.002" style="enable-background:new 0 0 60.002 60.002;" xml:space="preserve">
              <g>
                <path d=""/>
                
              </g>
          </svg>
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Application</a></h5>
              
            </div>
          </div>
        </div>
        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/trash.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Trash</a></h5>
              
            </div>
          </div>
        </div>

  </div>
</div>
      </div>
     
      <div class="">
      
        <hr>
        <h3 style="text-align: center;">Contact Us</h3>
        <?php if($countryName == 'Pakistan') { ?>
          <div class="">
            <div class="col-sm-6 modal-contact1" >
            <h2 style="padding-top: 20px; margin-top:0px;">Pakistan</h2>
            <i class="fa fa-whatsapp" style="color: green;font-size:20px;" aria-hidden="true"></i> <a href="#" dir='ltr'><?php echo lang('landing_pakistan_contact'); ?></a><br><br>
            <a href="#"><i class="fa fa-envelope" style="font-size:20px;" aria-hidden="true"></i> <?php echo lang('landing_united_vision_email'); ?></a></p>
            </div>
    <div class="col-sm-6 modal-contact2">
             <h2>Get Your Free</h2>
             <a class="button1" href="<?php echo site_url('signup'); ?>" title="Get Started!"><?php echo lang('landing_lbl_free_trial');?> </a>

    </div>
          </div>

         <?php } elseif($countryName == 'Libya') { ?>
          <div class="">
            <div class="col-sm-6 modal-contact1" >
            <h2 style="padding-top: 20px; margin-top:0px;">Libya</h2>
            <i class="fa fa-whatsapp" style="color: green;font-size:20px;" aria-hidden="true"></i> <a href="#" dir='ltr'><?php echo lang('landing_libya_contact'); ?></a><br><br>
            <a href="#"><i class="fa fa-envelope" style="font-size:20px;" aria-hidden="true"></i> <?php echo lang('landing_united_vision_email'); ?></a></p>
            </div>
    <div class="col-sm-6 modal-contact2">
             <h2>Get Your Free</h2>
             <a class="button1" href="<?php echo site_url('signup'); ?>" title="Get Started!"><?php echo lang('landing_lbl_free_trial');?> </a>

    </div>
          </div>
          <?php } elseif($countryName == 'Sudan') { ?>
            <div class="">
            <div class="col-sm-6 modal-contact1" >
            <h2 style="padding-top: 20px; margin-top:0px;">Sudan</h2>
            <i class="fa fa-whatsapp" style="color: green;font-size:20px;" aria-hidden="true"></i> <a href="#" dir='ltr'><?php echo lang('landing_sudan_contact'); ?></a><br><br>
            <a href="#"><i class="fa fa-envelope" style="font-size:20px;" aria-hidden="true"></i> <?php echo lang('landing_united_vision_email'); ?></a></p>
            </div>
    <div class="col-sm-6 modal-contact2">
             <h2>Get Your Free</h2>
             <a class="button1" href="<?php echo site_url('signup'); ?>" title="Get Started!"><?php echo lang('landing_lbl_free_trial');?> </a>

    </div>
          </div>
          <?php } else { ?>
            <div class="">
            <div class="col-sm-6 modal-contact1" >
            <h2 style="padding-top: 20px; margin-top:0px;">Pakistan</h2>
            <i class="fa fa-whatsapp" style="color: green;font-size:20px;" aria-hidden="true"></i> <a href="#" dir='ltr'><?php echo lang('landing_pakistan_contact'); ?></a><br><br>
            <a href="#"><i class="fa fa-envelope" style="font-size:20px;" aria-hidden="true"></i> <?php echo lang('landing_united_vision_email'); ?></a></p>
            </div>
    <div class="col-sm-6 modal-contact2">
             <h2>Get Your Free</h2>
             <a class="button1" href="<?php echo site_url('signup'); ?>" title="Get Started!"><?php echo lang('landing_lbl_free_trial');?> </a>

    </div>
          </div>
          <?php } ?>
      </div>
    </div>
  </div>
</div>

<!-- Modal3 -->
<div class="modal fade" id="exampleModalLong3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" >
    <div class="modal-content">
      <div class="modal-header" >
        <h2 class="modal-title text-center" id="exampleModalLongTitle"  >Premium Plan (Access to 20 Premium Modules)</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
    
        <div class="container my-5">
 
  <div class="row flex justify-content-between">
        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
         <div > <img style="height: 50px; width:50px;"src="<?= base_url().'assets/new_Icons/' ?>/setting.png" alt="parent"></div>
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Settings</a></h5>
           
            </div>
          </div>
        </div>

        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <div > <img style="height: 50px; width:50px;"src="<?= base_url().'assets/new_Icons/' ?>/employee.png" alt="parent"></div>
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Employee</a></h5>
              
            </div>
          </div>
        </div>

        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <div > <img style="height: 50px; width:50px;"src="<?= base_url().'assets/new_Icons/' ?>/student.png" alt="parent"></div>
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Student</a></h5>
              
            </div>
          </div>
        </div>

        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/parents.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Guardian</a></h5>
              
            </div>
          </div>
        </div>

        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/attendance.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Attendance</a></h5>
             
            </div>
          </div>
        </div>

        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/academics.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Academics</a></h5>
             
            </div>
          </div>
        </div>

        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/messaging.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Messages</a></h5>
             
            </div>
          </div>
        </div>

        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/study material.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Study Material</a></h5>
              
            </div>
          </div>
        </div>
        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/bookshop.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Bookshop</a></h5>
              
            </div>
          </div>
        </div>
        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/Finance.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Fee Collection</a></h5>
              
            </div>
          </div>
        </div>
  
        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/forms.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Forms</a></h5>
              
            </div>
          </div>
        </div>
        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/reports.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Reports</a></h5>
              
            </div>
          </div>
        </div>
        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/examination.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Examination</a></h5>
              
            </div>
          </div>
        </div>
        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/student evaluation.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Study Evaluation</a></h5>
              
            </div>
          </div>
        </div>
        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/study plan.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Study Plan</a></h5>
              
            </div>
          </div>
        </div>
        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <svg height="40px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                viewBox="0 0 60.002 60.002" style="enable-background:new 0 0 60.002 60.002;" xml:space="preserve">
              <g>
                <path d=""/>
                
              </g>
          </svg>
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Applications</a></h5>
              
            </div>
          </div>
        </div>
        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/trash.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Trash</a></h5>
              
            </div>
          </div>
        </div>
        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/payroll.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Payroll</a></h5>
              
            </div>
          </div>
        </div>
        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/accounts.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Accounts</a></h5>
              
            </div>
          </div>
        </div>
        <div class="col-md-3 p-2 text-center">
          <div class="bg-light px-3 py-5">
          <img style="height: 50px; width:50px;" src="<?= base_url().'assets/new_Icons/' ?>/online classes.png" alt="parent">
            <div class="mt-3">
              <h5 class="mb-2" style="font-weight: 600;"><a href="#">Online Classes</a></h5>
              
            </div>
          </div>
        </div>

  </div>
</div>
      </div>
     
      <div class="">
      
        <hr>
        <h3 style="text-align: center;">Contact Us</h3>
        <?php if($countryName == 'Pakistan') { ?>
          <div class="">
            <div class="col-sm-6 modal-contact1" >
            <h2 style="padding-top: 20px; margin-top:0px;">Pakistan</h2>
            <i class="fa fa-whatsapp" style="color: green;font-size:20px;" aria-hidden="true"></i> <a href="#" dir='ltr'><?php echo lang('landing_pakistan_contact'); ?></a><br><br>
            <a href="#"><i class="fa fa-envelope" style="font-size:20px;" aria-hidden="true"></i> <?php echo lang('landing_united_vision_email'); ?></a></p>
            </div>
    <div class="col-sm-6 modal-contact2">
             <h2>Get Your Free</h2>
             <a class="button1" href="<?php echo site_url('signup'); ?>" title="Get Started!"><?php echo lang('landing_lbl_free_trial');?> </a>

    </div>
          </div>

         <?php } elseif($countryName == 'Libya') { ?>
          <div class="">
            <div class="col-sm-6 modal-contact1" >
            <h2 style="padding-top: 20px; margin-top:0px;">Libya</h2>
            <i class="fa fa-whatsapp" style="color: green;font-size:20px;" aria-hidden="true"></i> <a href="#" dir='ltr'><?php echo lang('landing_libya_contact'); ?></a><br><br>
            <a href="#"><i class="fa fa-envelope" style="font-size:20px;" aria-hidden="true"></i> <?php echo lang('landing_united_vision_email'); ?></a></p>
            </div>
    <div class="col-sm-6 modal-contact2">
             <h2>Get Your Free</h2>
             <a class="button1" href="<?php echo site_url('signup'); ?>" title="Get Started!"><?php echo lang('landing_lbl_free_trial');?> </a>

    </div>
          </div>
          <?php } elseif($countryName == 'Sudan') { ?>
            <div class="">
            <div class="col-sm-6 modal-contact1" >
            <h2 style="padding-top: 20px; margin-top:0px;">Sudan</h2>
            <i class="fa fa-whatsapp" style="color: green;font-size:20px;" aria-hidden="true"></i> <a href="#" dir='ltr'><?php echo lang('landing_sudan_contact'); ?></a><br><br>
            <a href="#"><i class="fa fa-envelope" style="font-size:20px;" aria-hidden="true"></i> <?php echo lang('landing_united_vision_email'); ?></a></p>
            </div>
    <div class="col-sm-6 modal-contact2">
             <h2>Get Your Free</h2>
             <a class="button1" href="<?php echo site_url('signup'); ?>" title="Get Started!"><?php echo lang('landing_lbl_free_trial');?> </a>

    </div>
          </div>
          <?php } else { ?>
            <div class="">
            <div class="col-sm-6 modal-contact1" >
            <h2 style="padding-top: 20px; margin-top:0px;">Pakistan</h2>
            <i class="fa fa-whatsapp" style="color: green;font-size:20px;" aria-hidden="true"></i> <a href="#" dir='ltr'><?php echo lang('landing_pakistan_contact'); ?></a><br><br>
            <a href="#"><i class="fa fa-envelope" style="font-size:20px;" aria-hidden="true"></i> <?php echo lang('landing_united_vision_email'); ?></a></p>
            </div>
    <div class="col-sm-6 modal-contact2">
             <h2>Get Your Free</h2>
             <a class="button1" href="<?php echo site_url('signup'); ?>" title="Get Started!"><?php echo lang('landing_lbl_free_trial');?> </a>

    </div>
          </div>
          <?php } ?>
      </div>
    </div>
  </div>
</div>

        
</div>

            <!-- .row -->
            <div>
                <div class="col-md-12 bg-danger">
                    <div class="fix-width">
                        <div class="row">
                            <div class="col-md-12  m-t-40 m-b-40">
                                <?php
                                if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                                    <h1 class="fonts" style="color: white; "><?php echo lang('landing_lbl_satisfied_offer'); ?>   <span style="float: right;">
                                <a href="https://twitter.com/UvSchools" class="icon-button twitter"><i class="icon-twitter"></i><span></span></a>
                                <a href="https://www.facebook.com/uvschools.co/" class="icon-button facebook"><i class="icon-facebook"></i><span></span></a>
                                <a href="https://www.instagram.com/uvschools/?hl=en" class="icon-button youtube"><i class="fa fa-instagram" id="insta" aria-hidden="true"></i><span></span></a>
                                </span><?php
                                } else {?>
                                     <h1 style="color: white; font-size: 45px"><?php echo lang('landing_lbl_satisfied_offer');
                                }
                                ?>
                                </h1>
                             
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row " id="contact-us">
                <div class="col-md-12">
                       
                    <br><br>
                    <?php
                        if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                            <h1 class="box-title m-b-0 text-center"><?php echo lang('landing_lbl_contact_us'); 
                        } else {?>
                             <h1 class="box-title m-b-0 text-center" style="font-size: 45px"><?php echo lang('landing_lbl_contact_us');
                        }
                        ?>
                     </h1>
                    <br><br>
                    
                    <div class="row">
                        <div class="col-md-4" style="border-left: solid; ">
                            <h3><?php echo lang('landing_name_united_vision'); ?> - <?php echo lang('landing_country_sudan'); ?> – </h3>
                            <p>  
                            <?php
                            if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                                <a><i class="fa fa-map-marker"style="font-size:20px;"  aria-hidden="true"></i> <?php echo lang('landing_address_sudan');  
                            } else {?>
                                  <a style="font-size: 20px;"><i class="fa fa-map-marker" style="font-size:20px;" aria-hidden="true"></i> <?php echo lang('landing_address_sudan'); 
                            }
                            ?> 
                            </a>
                            <i class="fa fa-whatsapp" style="color: green;font-size:20px;"aria-hidden="true"></i> <a href="#" dir='ltr'> <?php echo lang('landing_sudan_contact'); ?></a><br><br>
                                <a href="#"><i class="fa fa-envelope" style="font-size:20px;" aria-hidden="true"></i> <?php echo lang('landing_united_vision_email'); ?></a></p>
                        </div>
                        <div class="col-md-4" style="border-left: solid; ">
                            <h3><?php echo lang('landing_name_united_vision'); ?> - <?php echo lang('landing_country_libya'); ?> </h3>
                            <p>
                            <?php
                            if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                                <a><i class="fa fa-map-marker" style="font-size:20px;" aria-hidden="true"></i> <?php echo lang('landing_address_libya');  
                            } else {?>
                                  <a style="font-size: 20px;"><i class="fa fa-map-marker" style="font-size:20px;"aria-hidden="true"></i> <?php echo lang('landing_address_libya'); 
                            }
                            ?> 
                                </a>  
                                <i class="fa fa-whatsapp" style="color: green;font-size:20px;"aria-hidden="true"></i> <a href="#" dir='ltr'><?php echo lang('landing_libya_contact'); ?></a><br><br>
                                <a href="#"><i class="fa fa-envelope" style="font-size:20px;" aria-hidden="true"></i> <?php echo lang('landing_united_vision_email'); ?></a></p>
                        </div>
                        <div class="col-md-4" style="border-left: solid; ">
                            <h3><?php echo lang('landing_name_united_vision'); ?> - <?php echo lang('landing_country_pakistan'); ?></h3>
                            <p>
                             <?php
                            if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                                <a><i class="fa fa-map-marker" style="font-size:20px;"aria-hidden="true"></i> <?php echo lang('landing_address_pakistan');  
                            } else {?>
                                  <a style="font-size: 20px;"><i class="fa fa-map-marker" style="font-size:20px;"aria-hidden="true"></i> <?php echo lang('landing_address_pakistan'); 
                            }
                            ?> 
                                </a>  
                                <i class="fa fa-whatsapp" style="color: green;font-size:20px;" aria-hidden="true"></i> <a href="#" dir='ltr'><?php echo lang('landing_pakistan_contact'); ?></a><br><br>
                                <a href="#"><i class="fa fa-envelope" style="font-size:20px;" aria-hidden="true"></i> <?php echo lang('landing_united_vision_email'); ?></a></p>
                        </div>

                    </div>



                    <br><br><br>
                      
                    </div>

            </div>
            </div>


     
          
            <script type="text/javascript">
           
                $(document).ready(function(){
                
              
    $('.customer-logos').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 1500,
        arrows: false,
        dots: false,
        pauseOnHover: false,
        responsive: [{
            breakpoint: 768,
            settings: {
                slidesToShow: 1
            }
        }, {
            breakpoint: 520,
            settings: {
                slidesToShow: 1
            }
        }]
    });

 

});
            </script>
            <!-- /.row -->
            <!-- .row -->
            
            <?php include (APPPATH.'views/landing_page/landing_footer.php');?>

        
 