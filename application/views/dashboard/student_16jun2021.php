<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick-theme.min.css" rel="stylesheet" />
<style>
     /* [role="tablist"] {
	 display: grid;
	 grid-template-columns: repeat(auto-fit, minmax(207px, 0fr));
	 grid-gap: 10px;
} */
#home-tab {
  width:95.5%;
  border-radius: 8px;
  margin-left: 6.5%;
  
}
#profile-tab {
  width:95.5%;
  border-radius: 1px;
  margin-left: 2%;
}
#contact-tab {
  width:95.5%;
  border-radius: 1px;
  margin-left: -4%;
}

@media only screen and (max-width: 768px) {
  /* For mobile phones: */
  #home-tab {
  width:100%;
  border-radius: 1px;
  margin-left: 0px !important;
  
}
#profile-tab {
  width:100%;
  border-radius: 1px;
  font-size: 14px;
  text-align: justify !important;
  padding-right: 1px !important;
  margin-left: 0px !important;
}
#contact-tab {
  width:100%;
  border-radius: 1px;
  margin-left: 0px !important;
}
[dir=rtl] .slick-prev {
    right: -30px;
    left: auto;
}
[dir=rtl] .slick-next {
    right: auto;
    left: -11px;
}
}
.nav-tabs .nav-link {
  color: black;
  background: white;
  border-style: solid;
  border-color: green;
}
.nav-link.active{
  background: #<?php echo substr($this->session->userdata('userdata')["theme_color"], 1) ?> !important;
	 color: #fff !important;
}
/* .nav-link.active:hover{
  color: black !important;
  background: white !important;
  border-style: solid !important;
  border-color: green !important;
} */
.nav-link:hover{
  background: #<?php echo substr($this->session->userdata('userdata')["theme_color"], 1) ?> !important;
	 color: #fff !important;
}
.nav-tabs {
    border-bottom: 0px !important;
}
.wrap-modal-slider {
  padding: 0 20px;
  opacity: 0;
  transition: all 0.3s;
}

.wrap-modal-slider.open {
  opacity: 1;
}

.your-class {
  width: auto;
  margin: 0px auto;
  margin-left: 10px;
  position: relative;
  padding: 10px 18px !important;
}
/* Arrows */
.slick-prev,
.slick-next
{
  color: transparent;
  font-size: 26px !important;
    line-height: 0;

    position: absolute;
    top: 40%;

    display: block;
    margin-right: 22px;
    width: 20px;
    height: 20px;
    padding: 0;
    -webkit-transform: translate(0, -50%);
    -ms-transform: translate(0, -50%);
    transform: translate(0, -50%);
}


.slick-prev:before,
.slick-next:before {
  color: #1b4b0a;
  font-size: 32px !important;

}

.adjusted .slick-arrow:before {font-family:initial!important; font-size: 26px !important; color:yellow;}

.card{
  border-radius: 5%;
  padding-left: 0.5%;
  padding-right: 0.5%;
  background-color: white;
  margin-right: 10px;
  font-size: 14px;
  border: 1px solid #1b4b0a;
  
}
/* .card-text{
  height: 100px;
  display: block;
  overflow: auto;
   text-overflow: ellipsis;
   display: -webkit-box;
   -webkit-line-clamp: 2; 
   -webkit-box-orient: vertical;
} */
.hint {
  
    border: 1px solid #1b4b0a;
    border-radius: 10px;
    padding: 20px 20px 0px;
    margin-bottom: 15px;
    background-color: white;
    display: none;
}
.tab-content {
    margin-top: 0px;
}
.online_class {
  
    border: 1px solid #1b4b0a;
    border-radius: 10px;
}
</style>
<!-- Page Content -->

<div id="page-wrapper" ng-controller="downloadParentController" ng-init = "studyMaterialForStudentDashboard()" >
    <div class="container-fluid" >
 
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?= lang("lbl_dashboard"); ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="dashboard/"><?= lang("lbl_dashboard"); ?></a></li>
                    <li class="active"><?= lang("lbl_dashboard"); ?></li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <?php echo $this->session->flashdata('alert_no_permission'); ?>
        
        <div class="white-box online_class">
            <?php if($online_class == true){?>
            <a href="<?php echo site_url('online_classes'); ?>" class="btn btn-success" ><?php echo lang('join_class_std_dashbaord');?></a>
            <?php }else{ ?>
                <button class="btn btn-default"><?php echo lang('no_class_std_dashbaord');?></button>
           <?php } ?>
        </div>
        
        <div class="hint" >
          <ul class="nav nav-tabs row" id="myTab" role="tablist">
            <li class="nav-item col-lg-4 col-md-4 col-sm-4 col-xs-4" style="text-align: center;">
              <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Study Material</a>
            </li>
            <!-- <li class="nav-item col-lg-4 col-md-4 col-sm-4 col-xs-4" style="text-align: center;">
              <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Assignment</a>
            </li>
            <li class="nav-item col-lg-4 col-md-4 col-sm-4 col-xs-4" style="text-align: center;">
              <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Home Work</a>
            </li> -->
          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
              <div class="your-class" >
            <?php 
            
            if(count($materials)<1){ ?>
                 <div style="text-align: center;">
                   <h2>No Study Material for today</h2>
                 </div>
            <?php  } else{
             $id=0;
             foreach($materials as $mat){ 
            
               ?>
             
              <div class="card" style="height:340px"  >
                    <!-- <img class="card-img-top img-responsive" src="../assets/images/big/img1.jpg" alt="Card image cap"> -->
               
                    <!-- <h3>Teacher Name: <?php echo $mat['name']; ?></h3> -->
                    <p style="font-size: 16px; margin-top:5%;margin-bottom:-7px; font-weight:bold;">Teacher : <span style="font-size: 14px;"><?php echo $mat['name']; ?></span></p>
                        <div class="card-body">
                          <h5 class="card-title">Title: <?php echo $mat['title']; ?></h5>
                          <!-- <p class="card-text"><?php echo $mat['content_type']; ?></p> -->
                          <p class="card-title">Details:</p>
                          <div style="height: 100px; display:block; overflow: auto;">
                          <p class="card-text"><?php echo $mat['details']; ?></p></div>
                          <h5 class="card-title">Attached Files:</h5>
                          <section class="customer-logos slider m-t-10">
                          <?php 
                          $count = 0;
                          foreach($mat['files'] as $file){
                            $ext = pathinfo($file, PATHINFO_EXTENSION);
                            $count= $count+1;
                          if($ext == 'pdf') {?>
                         <div class="slide"> <a href="<?= base_url().'uploads/study_material/'.$file ?>" target="_blank" ><img class="slide_icon" style="height: 40px; width:35px" src="<?= base_url().'assets/iconsnew/icons' ?>/pdf_icon.png"></a> </div>
                          <?php }else if($ext == 'docx' || $ext == 'doc') { ?>
                            <div class="slide"><a href="<?= base_url().'uploads/study_material/'.$file ?>" target="_blank" ><img class="slide_icon" style="height: 40px; width:35px" src="<?= base_url().'assets/iconsnew/icons' ?>/doc_icon.png"></a> </div>
                            <?php }else if($ext =='webm' || $ext == 'mp4' || $ext == 'mov' || $ext == 'wmv' || $ext == 'flv' || $ext == 'avi'){ ?>
                              <div class="slide"><a href="<?= base_url().'uploads/study_material/'.$file ?>" target="_blank" ><img class="slide_icon" style="height: 40px; width:35px" src="<?= base_url().'assets/iconsnew/icons' ?>/multimedia.png"></a></div>
                              <?php }else if($ext == 'ppt' || $ext == 'pptx') { ?>
                                <div class="slide"> <a href="<?= base_url().'uploads/study_material/'.$file ?>" target="_blank" ><img class="slide_icon" style="height: 40px; width:35px" src="<?= base_url().'assets/iconsnew/icons' ?>/ppt_icon.png"></a> </div>
                                <?php }else if($ext == 'jpg' || $ext == 'png' || $ext == 'JPG' || $ext == 'PNG' || $ext == 'jpeg' || $ext == 'JPEG') { ?>
                                  <div class="slide"> <a href="<?= base_url().'uploads/study_material/'.$file ?>" target="_blank" data-lightbox="image-attach" ><img class="slide_icon" style="height: 40px; width:35px" src="<?= base_url().'assets/iconsnew/icons' ?>/image.png"></a><p style="margin-left: 5%;"><?php echo $count; ?></p>  </div>
                                <?php } else if($file == "") { ?>
                                  <div class="slide" style="height: 40px; width:35px;">  </div>
                                  <?php } else { ?>
                                  <div class="slide">  <a href="<?= base_url().'/uploads/study_material/'.$file ?>" target="_blank" ><i class="fa fa-file" aria-hidden="true" style="font-size: 32px;"></i></a> </div>
                                  <?php }} ?>
  
                          </section>

                          <a href="#" id="<?php echo $id; ?>" style="float: left; margin-top:10%;" data-toggle="modal" data-target="#details" ng-click="detailsStd_studymaterial_dashbaord('<?php echo $id; ?>')">View Details</a>
                          <?php $id = $id + 1; ?>
                          <p class="card-text" style="float: right; margin-top:10%;"><small class="text-muted"><?php echo $mat['uploaded_time']; ?></small></p>
                        </div>
              </div>
          <?php }} ?>
              </div>
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
              <div class="your-class">
                <!-- <div><img src="http://via.placeholder.com/242x300/ffffff/000000" alt=""></div>
                <div><img src="http://via.placeholder.com/242x300" alt=""></div>
                <div><img src="http://via.placeholder.com/242x300/000000/ffffff" alt=""></div>
                <div><img src="http://via.placeholder.com/242x300/ffffff/000000" alt=""></div>
                <div><img src="http://via.placeholder.com/242x300" alt=""></div>
                <div><img src="http://via.placeholder.com/242x300/000000/ffffff" alt=""></div>
                <div><img src="http://via.placeholder.com/242x300/ffffff/000000" alt=""></div>
                <div><img src="http://via.placeholder.com/242x300" alt=""></div>
                <div><img src="http://via.placeholder.com/242x300/000000/ffffff" alt=""></div> -->
              </div>
            </div>
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
              <div class="your-class">
                <!-- <div><img src="http://via.placeholder.com/242x300/ffffff/000000" alt=""></div>
                <div><img src="http://via.placeholder.com/242x300" alt=""></div>
                <div><img src="http://via.placeholder.com/242x300/000000/ffffff" alt=""></div>
                <div><img src="http://via.placeholder.com/242x300/ffffff/000000" alt=""></div>
                <div><img src="http://via.placeholder.com/242x300" alt=""></div>
                <div><img src="http://via.placeholder.com/242x300/000000/ffffff" alt=""></div>
                <div><img src="http://via.placeholder.com/242x300/ffffff/000000" alt=""></div>
                <div><img src="http://via.placeholder.com/242x300" alt=""></div>
                <div><img src="http://via.placeholder.com/242x300/000000/ffffff" alt=""></div> -->
              </div>
            </div>
          </div>
                  </div>
                  <div class="modal fade" id="details" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="panel panel-primary">
                    <div class="panel-heading">{{details.title}} - {{details.content_type}}</div>
                    <div class="panel-body">
                        
                        <div class="row">
                            <div class="col-md-6"><b>{{details.subject_name}}</b></div>
                            <div class="col-md-6 text-right">{{details.uploaded_time}}</div>

                        </div>
                        <div class="row" style="margin-top: 15px;margin-bottom:10px">
                            <div class="col-md-12">
                                <span ng-bind-html="details.details"></span>
                            </div>

                        </div>

                        <div class="row" style="margin-top: 10px" ng-show="details.files[0].length > 0">
                            <div class="col-md-12">

                                <ul >

                                   <div  ng-repeat="file in details.files">
                                    <li ng-if="['jpg','jpeg','png','gif'].includes(file.split('.').pop().toLowerCase())"><a href="<?php echo base_url() ?>uploads/study_material/{{file}}" data-lightbox="image-attach">{{file}}</a></li>
                                    <li ng-if="!['jpg','jpeg','png','gif'].includes(file.split('.').pop().toLowerCase())"><a href="<?php echo base_url() ?>uploads/study_material/{{file}}" target="_blank">{{file}}</a></li>
                                    
                                </div>

                            </ul>   

                        </div>

                    </div>


                    <div class="row pull-right">
                        <div style="margin-right: 8px">
                            <button type="button" class="btn btn-default"
                            data-dismiss="modal"><?php echo lang('lbl_close') ?>
                        </button>
                    </div>
                    <div ng-show="details.files[0].length > 0">
                        <button type="button" class="btn btn-primary" ng-click="download()">
                            <?php echo lang('download_files') ?>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
              </div>
      
              <script>
 var lang = "<?php echo $this->session->userdata('site_lang'); ?>"; 

$(document).ready(function(){
  var trigger = false;
            if(lang == "arabic"){
            trigger = true;
        }

  $('.your-class').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            autoplay: false,
            rtl:trigger,
            autoplaySpeed: 1500,
            arrows: true,
            dots: false,
            pauseOnHover: true,
            responsive: [{
                breakpoint: 768,
                settings: {
                    slidesToShow: 2
                }
            }, {
                breakpoint: 520,
                settings: {
                    slidesToShow: 1
                }
            }]
        });
        $('.customer-logos').slick({
            slidesToShow: 6,
            slidesToScroll: 1,
            autoplay: true,
            rtl:trigger,
            autoplaySpeed: 1500,
            arrows: false,
            dots: false,
            pauseOnHover: true,
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


// $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
//   $('.your-class').slick('setPosition');
// });
 </script>
<?php include(APPPATH . "views/inc/footer.php"); ?>