<?php include (APPPATH.'views/landing_page/landing_header.php');?>

<div class="jumbotron">  
<div class="clearfix">

    <?php 
    if($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) { ?>
  <div class="iconbb">
  <img class="aligncenter size-full wp-image-19768" src="<?= base_url().'assets/detailed_img/' ?>/Attendence-01.png" alt="one" width="105" height="105" />
  </div>
  <div class="textbb">
      <h1><?php echo lang('landing_lbl_student_employee_ttendance'); ?></h1>
                  
      <p style="text-align: left; font-size: 13px; padding-right: 35px;"><?php echo lang('detailed_txt_s&e_attendance'); ?></p>
   
        <div class='bble'>
        <div class="buttonContainer">
        <a class="button" href="<?php echo site_url('signup'); ?>" ><?php echo lang('btn_get_started'); ?></a></div>
</div>
    </div>
    <?php } ?>
  
   <?php if ($this->session->userdata('site_lang') == "arabic" ) { ?>
    <div class="row col-md-12"> 
    <div class="col-md-4" style="text-align: center;">
    <img class="aligncenter size-full wp-image-19768" src="<?= base_url().'assets/detailed_img/' ?>/Attendence-01.png" alt="one" width="105" height="105" />
  </div>
    <div class=" col-md-8" >
      <h1><?php echo lang('landing_lbl_student_employee_ttendance'); ?></h1>
                       
        <p dir='rtl' style=" font-size: 18px; "><?php echo lang('detailed_txt_s&e_attendance'); ?> </p>
  
        <div class='bble'>
        <div class="buttonContainer">
        <a class="button" href="<?php echo site_url('signup'); ?>" ><?php echo lang('btn_get_started'); ?></a></div>
</div>
    </div>
  
  </div>
    <?php } ?>
</div>
</div>
<section class="kt-listing content-wrapper container">
  <h1 class="kt-listing__heading"><?php echo lang('lbl_features'); ?> <?php echo lang('landing_lbl_student_employee_ttendance'); ?></h1>
  <?php if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
  <ul class="kt-list">
    <li class="kt-list__item"><?php echo lang('detailed_f1_s&e_attendance'); ?></li>
    <li class="kt-list__item"><?php echo lang('detailed_f2_s&e_attendance'); ?></li>
    <li class="kt-list__item"><?php echo lang('detailed_f3_s&e_attendance'); ?></li>
    <li class="kt-list__item"><?php echo lang('detailed_f4_s&e_attendance'); ?></li>
  </ul>
  <?php  }  
        if ($this->session->userdata('site_lang') == "arabic" ) {?> 
          <ul class="kt-list">
    <li class="kt-list__item" style="font-size: 17px;"><?php echo lang('detailed_f1_s&e_attendance'); ?></li>
    <li class="kt-list__item" style="font-size: 17px;"> <?php echo lang('detailed_f2_s&e_attendance'); ?></li>
    <li class="kt-list__item" style="font-size: 17px;"><?php echo lang('detailed_f3_s&e_attendance'); ?></li>
    <li class="kt-list__item" style="font-size: 17px;"><?php echo lang('detailed_f4_s&e_attendance'); ?></li>
  </ul>
  <?php } ?>
</section>
<div class="container">
  <h2><?php echo lang('lbl_other_features'); ?></h2>
  <br>
   <section class="customer-logos slider">
   <?php if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
      <div class="slide"><a href="<?= base_url();?>multiuser/dtimetable" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/time table.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/dtimetable" ><?php echo lang('landing_lbl_timetable'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/class_batches" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/classes and section.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/class_batches" ><?php echo lang('landing_lbl_classes_batches'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/multilanguage" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/Multilanguage.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/multilanguage" ><?php echo lang('landing_lbl_multi_language'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/dexamination" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/examination.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/dexamination" ><?php echo lang('landing_lbl_examination'); ?></a></div></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/fee_collection" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/Finance.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/fee_collection" ><?php echo lang('landing_lbl_fee_collection'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/studymaterial" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/study material.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/studymaterial" ><?php echo lang('landing_lbl_study_material'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/messaging" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/messaging.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/messaging" ><?php echo lang('landing_lbl_messaging'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/payroll" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/payroll.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/payroll" ><?php echo lang('lbl_payroll'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/onlineadmission" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/online admissions.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/onlineadmission" ><?php echo lang('landing_lbl_onlineadmission'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/studyplan" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/study plan.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/studyplan" ><?php echo lang('lbl_syllabus'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/dtrash" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/trash.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/dtrash" ><?php echo lang('lbl_trash'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/announcements" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/announcement.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/announcements" ><?php echo lang('lbl_announcements'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/user_managment" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/user management.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/user_managment" ><?php echo lang('landing_lbl_user_managment'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/daccounts" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/Accounts.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/daccounts" ><?php echo lang('landing_lbl_accounts'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/dbookshop" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/bookshop.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/dbookshop" ><?php echo lang('lbl_book_shop'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/dreports" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/reports.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/dreports" ><?php echo lang('reports_all'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/multiuser" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/1.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/multiuser" ><?php echo lang('landing_lbl_multiuser'); ?></a></div></a></div>
      <?php  }  
        if ($this->session->userdata('site_lang') == "arabic" ) {?> 
      <div class="slide"><a href="<?= base_url();?>multiuser/dtimetable" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/time table.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/dtimetable" style="font-size: 18px; padding-left:20px;"><?php echo lang('landing_lbl_timetable'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/class_batches" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/classes and section.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/class_batches" style="font-size: 18px; padding-left:20px;"><?php echo lang('landing_lbl_classes_batches'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/multilanguage" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/Multilanguage.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/multilanguage" style="font-size: 18px;padding-left:20px;"><?php echo lang('landing_lbl_multi_language'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/dexamination" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/examination.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/dexamination" style="font-size: 18px; padding-left:20px;"><?php echo lang('landing_lbl_examination'); ?></a></div></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/fee_collection" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/Finance.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/fee_collection" style="font-size: 18px; padding-left:20px;"><?php echo lang('landing_lbl_fee_collection'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/studymaterial" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/study material.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/studymaterial" style="font-size: 18px; padding-left:20px;"><?php echo lang('landing_lbl_study_material'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/messaging" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/messaging.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/messaging" style="font-size: 18px; padding-left:20px;"><?php echo lang('landing_lbl_messaging'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/payroll" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/payroll.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/payroll" style="font-size: 18px; padding-left:20px;"><?php echo lang('lbl_payroll'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/onlineadmission" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/online admissions.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/onlineadmission" style="font-size: 18px; padding-left:20px;"><?php echo lang('landing_lbl_onlineadmission'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/studyplan" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/study plan.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/studyplan" style="font-size: 18px; padding-left:20px;"><?php echo lang('lbl_syllabus'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/dtrash" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/trash.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/dtrash" style="font-size: 18px; padding-left:20px;"><?php echo lang('lbl_trash'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/announcements" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/announcement.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/announcements" style="font-size: 18px; padding-left:20px;"><?php echo lang('lbl_announcements'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/user_managment" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/user management.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/user_managment" style="font-size: 18px; padding-left:20px;"><?php echo lang('landing_lbl_user_managment'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/daccounts" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/Accounts.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/daccounts" style="font-size: 18px; padding-left:20px;"><?php echo lang('landing_lbl_accounts'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/dbookshop" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/bookshop.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/dbookshop" style="font-size: 18px; padding-left:20px;"><?php echo lang('lbl_book_shop'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/dreports" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/reports.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/dreports" style="font-size: 18px; padding-left:20px;"><?php echo lang('reports_all'); ?></a></div></a></div>
      <div class="slide"><a href="<?= base_url();?>multiuser/multiuser" class="title"><img class="slide_icon" src="<?= base_url().'assets/iconsnew/' ?>/1.png"><div class="buttonContainer1"> <a class="button2" href="<?= base_url();?>multiuser/multiuser"  style="font-size: 18px; padding-left:20px;"><?php echo lang('landing_lbl_multiuser'); ?></a></div></a></div>
      <?php } ?>
   </section>
</div>
<div class="row m-t-30">
                <div class="col-md-12 bg-danger">
                    <div class="fix-width" style=" margin-bottom:0px;">
                        <div class="row">
                            <div class="col-md-12  m-t-40 m-b-40">
                                <?php
                                if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                                    <h1 class="fonts" style="color: white; "><?php echo lang('landing_lbl_satisfied_offer'); ?>   <span style="float: right;">
                                    <a href="https://twitter.com/UvSchools" class="icon-button twitter"><i class="icon-twitter"></i><span></span></a>
                                <a href="https://www.facebook.com/uvschools.co/" class="icon-button facebook"><i class="icon-facebook"></i><span></span></a>
                                <a href="https://www.instagram.com/uvschools/?hl=en" class="icon-button youtube"><i class="fa fa-instagram"></i><span></span></a>
                                </span><?php
                                } else {?>
                                     <h1 style="color: white; font-size: 45px"><?php echo lang('landing_lbl_satisfied_offer');?></h1>
                                     <!-- <span style="float: left; margin-top:-60px">
                                <a href="#" class="icon-button twitter"><i class="icon-twitter"></i><span></span></a>
                                <a href="#" class="icon-button facebook"><i class="icon-facebook"></i><span></span></a>
                                <a href="#" class="icon-button youtube"><i class="fa fa-instagram"></i><span></span></a>
                                </span> -->
                             <?php   }
                                ?>
                             
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
                                <a href="#"><i class="fa fa-whatsapp" style="color: green;font-size:20px;"aria-hidden="true"></i> <?php echo lang('landing_libya_contact'); ?></a><br><br>
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
                                <a href="#"><i class="fa fa-whatsapp" style="color: green;font-size:20px;" aria-hidden="true"></i> <?php echo lang('landing_pakistan_contact'); ?></a><br><br>
                                <a href="#"><i class="fa fa-envelope" style="font-size:20px;" aria-hidden="true"></i> <?php echo lang('landing_united_vision_email'); ?></a></p>
                        </div>

                    </div>



                    <br><br><br>
                      
                    </div>

            </div>
            </div>


            <script type="text/javascript">
 var lang = "<?php echo $this->session->userdata('site_lang'); ?>"; 
    $(document).ready(function(){
       
        var trigger = false;
            if(lang == "arabic"){
            trigger = true;
        }

        $('.customer-logos').slick({
            slidesToShow: 6,
            slidesToScroll: 1,
            autoplay: true,
            rtl:trigger,
            autoplaySpeed: 1500,
            arrows: false,
            dots: false,
            pauseOnHover: false,
            responsive: [{
                breakpoint: 768,
                settings: {
                    slidesToShow: 4
                }
            }, {
                breakpoint: 520,
                settings: {
                    slidesToShow: 3
                }
            }]
        });
    });
</script>
<?php include (APPPATH.'views/landing_page/landing_footer.php');?>
