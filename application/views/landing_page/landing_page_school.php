<!doctype html>
<html class="no-js" lang="zxx">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title><?php echo $this->session->userdata("userdata")["sh_name"]; ?></title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" sizes="16x16" href="assets/plugins/images/favicon.png">
	<!-- Google font (font-family: 'Dosis', Roboto;) -->
	<link href="https://fonts.googleapis.com/css?family=Dosis:400,500,600,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">
	
	<!-- Stylesheets -->
	<link rel="stylesheet" href="assets/landingpage/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/landingpage/css/plugins.css">
	<link rel="stylesheet" href="assets/landingpage/newstyle.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js" integrity="sha512-XtmMtDEcNz2j7ekrtHvOVR4iwwaD6o/FUJe6+Zq+HgcCsk3kj4uSQQR8weQ2QVj1o0Pk6PwYLohm206ZzNfubg==" crossorigin="anonymous"></script>
	<!-- Cusom css -->
	<link rel="stylesheet" href="assets/landingpage/css/custom.css">
	
	<!-- Modernizer js -->
	<script src="assets/landingpage/js/vendor/modernizr-3.5.0.min.js"></script>
</head>
<body>
	<!--[if lte IE 9]>
		<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
	<![endif]-->

	<!-- Add your site or application content here -->
	
	<!-- <div class="fakeloader"></div> -->

	<!-- Main wrapper -->
	<div class="wrapper" id="wrapper">
		<!-- Header -->
		<header id="header" class="jnr__header header--2 clearfix">
			<!-- Start Header Top Area -->
			<div class="junior__header__top" style="background-color: <?php echo $school->theme_color; ?>;">
				<div class="container">
					<div class="row">
						<div class="col-md-8 col-lg-6 col-sm-9 col-12">
							<div class="jun__header__top__left">
							<?php
                                if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
								<ul class="top__address d-flex justify-content-start align-items-center flex-wrap flex-lg-nowrap flex-md-nowrap">
									<li><h6 style="color: white;"><?php echo lang('lbl_email_address')?> : <?php echo $school->email; ?></h6></li>
									<li><h6 style="color: white;"><?php echo lang('lbl_contact_now')?> : <?php echo $school->phone;?></h6></li>
								</ul>
							</div>
						</div>
						<div class="col-md-4 col-lg-6 col-sm-3 col-12">
							<div class="jun__header__top__right">
								<ul class="accounting d-flex justify-content-lg-end justify-content-md-end justify-content-start align-items-center">

									<li><a href="<?php echo base_url(); ?>online_admission?tag=<?php echo $school->url; ?>"><h6 style="color: white;"><?php echo lang('landing_lbl_onlineadmission'); ?></h6></a></li>

									<li><a href="<?php echo base_url(); ?><?php echo $school->url; ?>/login"><h6 style="color: white;"><?php echo lang('btn_login'); ?></h6></a></li>
									<!-- <li><a class="accountbox-trigger" href="#">Register</a></li> -->
									<li>
                                            <a><select class="form-control lang_select" onchange="javascript:window.location.href = '<?php echo base_url(); ?>LanguageSwitcher/switchLang/' + this.value;"  style="background: #1b4b0a; color:white">
                                                <option value="english" <?php if ($this->session->userdata('site_lang') == 'english') echo 'selected="selected"'; ?>>English</option>
                                                <option value="arabic" <?php if ($this->session->userdata('site_lang') == 'arabic') echo 'selected="selected"'; ?>>Arabic</option>
                                                <!-- <option value="urdu" <?php //if ($this->session->userdata('site_lang') == 'urdu') echo 'selected="selected"'; ?>>Urdu</option> -->
                                            </select></a>
                                        </li>
								</ul>
								<?php } ?>
								<?php
                                if ($this->session->userdata('site_lang') == "arabic" || $this->session->userdata('site_lang') == "urdu") {?>
								<ul class="top__address d-flex justify-content-start align-items-center flex-wrap flex-lg-nowrap flex-md-nowrap">
									<li><h6 style="color: white;"><?php echo lang('lbl_email_address')?> : <?php echo $school->email; ?></h6></li>
									<li><h6 style="color: white;"><?php echo lang('lbl_contact_now')?> : <?php echo $school->phone;?></h6></li>
								</ul>
							</div>
						</div>
						<div class="col-md-4 col-lg-6 col-sm-3 col-12">
							<div class="jun__header__top__right">
								<ul class="accounting d-flex justify-content-lg-end justify-content-md-end justify-content-start align-items-center">

									<li><a href="<?php echo base_url(); ?>online_admission?tag=<?php echo $school->url; ?>"><h6 style="color: white;"><?php echo lang('landing_lbl_onlineadmission'); ?></h6></a></li>

									<li><a href="<?php echo base_url(); ?><?php echo $school->url; ?>/login"><h6 style="color: white;"><?php echo lang('btn_login'); ?></h6></a></li>
									<!-- <li><a class="accountbox-trigger" href="#">Register</a></li> -->
									<li>
                                            <a><select class="form-control lang_select" onchange="javascript:window.location.href = '<?php echo base_url(); ?>LanguageSwitcher/switchLang/' + this.value;">
                                                <option value="english" <?php if ($this->session->userdata('site_lang') == 'english') echo 'selected="selected"'; ?>>English</option>
                                                <option value="arabic" <?php if ($this->session->userdata('site_lang') == 'arabic') echo 'selected="selected"'; ?>>Arabic</option>
                                                <!-- <option value="urdu" <?php //if ($this->session->userdata('site_lang') == 'urdu') echo 'selected="selected"'; ?>>Urdu</option> -->
                                            </select></a>
                                        </li>
								</ul>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End Header Top Area -->
			<!-- Start Mainmenu Area -->
			<div class="mainmenu__wrapper bg--white sticky__header">
				<div class="container">
					<div class="row d-none d-lg-flex">
						<div class="col-sm-4 col-md-6 col-lg-2 order-1 order-lg-1">
							<div class="logo">
								<a href="#">
									<img src="uploads/logos/<?php echo $school->logo; ?>" alt="logo images">
								</a>
							</div>
						</div>
						<div class="col-sm-4 col-md-2 col-lg-9 order-3 order-lg-2">
							<div class="mainmenu__wrap">
								<nav class="mainmenu__nav">
									<ul class="mainmenu">
										<li>
											<a href="#home"><?php echo lang('lbl_home')?></a>
										</li>
										<li>
											<a href="#class"><?php echo lang('lbl_classes')?></a>
										</li>
										<li>
											<a href="#teachers"><?php echo lang('lbl_teachers')?></a>
										</li>
										<li>
											<a href="#gallery"><?php echo lang('lbl_gallery')?></a>
										</li>
										<li class="drop">
											<a href="#newsevents"><?php echo lang('lbl_news')?></a>
										</li>
										<li>
											<a href="#footer"><?php echo lang('lbl_contacts')?></a>
										</li>
									</ul>
								</nav>
							</div>
						</div>
					</div>
					<!-- Mobile Menu -->
					<div class="mobile-menu d-block d-lg-none">
						<div class="logo">
							<a href="#"><img src="uploads/logos/<?php echo $school->logo; ?>" alt="logo"></a>
						</div>
					</div>
					<!-- Mobile Menu -->
				</div>
			</div>
			<!-- End Mainmenu Area -->
		</header>
		<!-- //Header -->
		<!-- Strat Slider Area -->
		<div class="slide__carosel owl-carousel owl-theme" id="home">
			<?php if( count($slider) == 0 ) {  ?>
			<div class="slider__area slider--two bg-pngimage--4 d-flex slider__fixed--height justify-content-end align-items-center">
				<div class="container">
					<div class="row">
						<div class="col-lg-6 offset-lg-6 offset-md-3 col-md-9 col-sm-12">
							<div class="slider__activation">
								<!-- Start Single Slide -->
								<div class="slide">
									<div class="slide__inner">
										<h6 style="color: white;">Create New Things</h6>
										<h1 style="color: white;">Play & learn, Create Beautiful Heaven World</h1>
										<p style="color: white;">Lorem ipsum dolor sit amet, consectetur adipisic ming elit, sed do ei Excepteur.Tnam Bajki      vntoccaecat cupida proident, sunt in culpa qui dese runt mol .</p>
									</div>
								</div>
								<!-- End Single Slide -->
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="slider__area slider--two bg-pngimage--4 d-flex slider__fixed--height justify-content-end align-items-center">
				<div class="container">
					<div class="row">
						<div class="col-lg-6 offset-lg-6 offset-md-3 col-md-9 col-sm-12">
							<div class="slider__activation">
								<!-- Start Single Slide -->
								<div class="slide">
									<div class="slide__inner">
										<h6 style="color: white;">Create New Things</h6>
										<h1 style="color: white;">Play & learn, Create Beautiful Heaven World</h1>
										<p style="color: white;">Lorem ipsum dolor sit amet, consectetur adipisic ming elit, sed do ei Excepteur.Tnam Bajki      vntoccaecat cupida proident, sunt in culpa qui dese runt mol .</p>
									</div>
								</div>
								<!-- End Single Slide -->
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- End Slider Area -->

			<?php } ?>
			<?php $cnt = 0; ?>
			<?php foreach ($slider as $slide) { ?>
			<?php if( $cnt < 6 ) { ?>
			<div class="slider__area slider--two d-flex slider__fixed--height justify-content-end align-items-center" style="background-image: url(<?php echo base_url(); ?>uploads/<?php echo $slide->image; ?>); background-size: cover; background-repeat: no-repeat;">
				<div class="container">
					<div class="row">
						<div class="col-lg-6 offset-lg-6 offset-md-3 col-md-9 col-sm-12">
							<div class="slider__activation">
								<!-- Start Single Slide -->
								<div class="slide" style="padding-right: 10px;">
									<div class="slide__inner" style="background-color: white; opacity: 0.79; border-radius: 5px;">
										<h6 style="font-weight: bold; padding-left: 15px; font-size: <?php echo $heading_font_size; ?>px; color: <?php echo $heading_color; ?>;"><?php echo $slide->title; ?></h6>
										<h1 style="color: black; padding-left: 15px; font-size: <?php echo $sub_heading_font_size; ?>px; color: <?php echo $sub_heading_color; ?>;"><?php echo $slide->sub_title; ?></h1>
										<p style="font-weight: bolder; font-family: sans-serif; padding-left: 15px; font-size: <?php echo $description_font_size ?>px; color: <?php echo $description_color; ?>;"><?php echo $slide->description; ?></p>
									</div>
								</div>
								<!-- End Single Slide -->
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>
			<?php $cnt++ ?>
			<?php } ?>
		</div>
		<!-- End Slider Area -->
		
		<!-- news and events -->
		<section class="footer__wrapper poss-relative  bg--white" id="newsevents">
			<div class="container-fluid " style="padding: 10%;">
				<div class="row">
					<div class="col-lg-12 col-sm-12 col-md-12">
						<div class="section__title text-center">
							<h2 class="title__line"><?php echo lang('lbl_news_and_events')?></h2>
						</div>
					</div>
				</div>
				<div class="text-center row">
					<div class="col-md-6 col-sm-6 col-12">
						<h1 style="border-bottom: 1px solid #ccc; background-color: <?php echo $school->theme_color; ?>; padding-left: 3px; color: white;"><?php echo lang('lbl_news')?></h1>
						<marquee direction="up" scrollamount="2" style="height: 300px; border: 1px solid #ccc; padding-left: 3px;">
							<?php foreach($news as $new) { ?>
							<h4><?php echo $new->title; ?></h4><p><?php echo $new->description;?></p>
							<?php } ?>
						</marquee>
					</div>
					<div class="col-md-6 col-sm-6 col-12">
						<h1 style="border-bottom: 1px solid #ccc; background-color: <?php echo $school->theme_color; ?>; padding-left: 3px; color: white;"><?php echo lang('lbl_events')?></h1>
						<marquee direction="up" scrollamount="2" style="height: 300px; border: 1px solid #ccc; padding-left: 3px;">
							<?php foreach($events as $event) { ?>
							<h4><?php echo $event->title; ?></h4><p><?php echo $event->description; ?></p>
							<?php } ?>
						</marquee>
					</div>
				</div>	
			</div>
		</section>
		<!-- news and events -->
		<!-- Start Our Service Area -->
		<?php if(count($classes) < 4 ) { ?>
		<!-- Start Our Service Area -->
		<div class="section__title text-center" id="class">
			<h2 class="title__line">Classes</h2>
		</div>
		<section class="junior__service service--2 bg-image--1 section-padding--bottom section--padding--xlg--top" id="class">
			<div class="container">
				<div class="row">
					<!-- Start Single Service -->
					<div class="col-lg-3 col-md-6 col-sm-6 col-12">
						<div class="service border__color border__color--5 bg__cat--4">
							<div class="service__icon">
								<img src="assets/landingpage/images/shape/sm-icon/classes.png" style="height: 50px; width: 45px;" alt="icon images">
							</div>
							<div class="service__details">
								<h6><a><?php echo lang('lbl_drawing_class')?></a></h6>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor am quaerat voluptatem.</p>
								<div class="service__btn">
								</div>
							</div>
						</div>
					</div>
					<!-- End Single Service -->
					<!-- Start Single Service -->
					<div class="col-lg-3 col-md-6 col-sm-6 col-12 xs-mt-60">
						<div class="service border__color border__color--6 bg__cat--5">
							<div class="service__icon">
								<img src="assets/landingpage/images/shape/sm-icon/classes.png" style="height: 50px; width: 45px;" alt="icon images">
							</div>
							<div class="service__details">
								<h6><a><?php echo lang('lbl_active_learning')?></a></h6>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor am quaerat voluptatem.</p>
								<div class="service__btn">
								</div>
							</div>
						</div>
					</div>
					<!-- End Single Service -->
					<!-- Start Single Service -->
					<div class="col-lg-3 col-md-6 col-sm-6 col-12 md-mt-60 sm-mt-60">
						<div class="service border__color border__color--7 bg__cat--6">
							<div class="service__icon">
								<img src="assets/landingpage/images/shape/sm-icon/classes.png" style="height: 50px; width: 45px;" alt="icon images">
							</div>
							<div class="service__details">
								<h6><a><?php echo lang('lbl_creative_lesson')?></a></h6>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor am quaerat voluptatem.</p>
								<div class="service__btn">
								</div>
							</div>
						</div>
					</div>
					<!-- End Single Service -->
					<!-- Start Single Service -->
					<div class="col-lg-3 col-md-6 col-sm-6 col-12 md-mt-60 sm-mt-60">
						<div class="service border__color border__color--8 bg__cat--7">
							<div class="service__icon">
								<img src="assets/landingpage/images/shape/sm-icon/classes.png" style="height: 50px; width: 45px;" alt="icon images">
							</div>
							<div class="service__details">
								<h6><a><?php echo lang('lbl_yoga_class')?></a></h6>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor am quaerat voluptatem.</p>
								<div class="service__btn">
								</div>
							</div>
						</div>
					</div>
					<!-- End Single Service -->
				</div>
			</div>
		</section>
		<!-- End Our Service Area -->
		<?php } else { ?>
		<?php if(count($classes_background) == 0) { ?>
		<section class="junior__service service--2 bg-white section-padding--bottom section--padding--xlg--top">
			<?php } else { ?>
			<?php $cnt = 0; ?>
			<?php foreach($classes_background as $background) { ?>
			<?php if( $cnt < 1 ) { ?>
			<section class="junior__service service--2 section-padding--bottom section--padding--xlg--top " style="background-image: url(<?php echo base_url(); ?>uploads/<?php echo $background->image; ?>); background-repeat: no-repeat; background-position: center; background-size: cover;" id="class">
				<?php $cnt++; ?>
				<?php } ?>
				<?php } } } ?>
				<div class="container">
					<div class="row classesSlider slider">
						<!-- Start Single Service -->
						<?php if(count($classes) > 3){?>
						<?php $cnt = 0; ?>
						
						<?php foreach($classes as $class) { ?>
						<div class="col-lg-3 col-md-6 col-sm-6 col-12 slide" >
							<div class="service border__color border__color--<?php echo 5+$cnt; ?> bg__cat--<?php echo 4+$cnt; ?>" style="height: 318px; margin-top: 65px;">
								<div class="service__icon" style="padding-left: 20px; padding-top: 17px;">
									<img src="assets/landingpage/images/shape/sm-icon/classes.png" style="height: 50px; width: 45px;" alt="icon images">
								</div>
								<div class="service__details">
									<h6><a><?php echo $class->class_name; ?></a></h6>
									<p><?php echo $class->description; ?></p>
								</div>
							</div>
						</div>
						
						<?php  $cnt++; ?>
						<?php } }?>
						<!-- End Single Service -->
					</div>
				</div>
			</section>
			<!-- End Our Service Area -->

			<?php if(count($video) == 0 ) { ?>
			<!-- Start Welcame Area -->
			<section class="junior__welcome__area welcome--2 bg-image--9 section-padding--lg">
				<div class="container">
					<div class="row">
						<div class="col-lg-12 col-sm-12 col-md-12">
							<div class="section__title text-center">
								<h2 class="title__line"><?php echo $school->name; ?></h2>
							</div>
						</div>
					</div>
					<div class="row jn__welcome__wrapper align-items-center">
						<div class="col-md-12 col-lg-6 col-sm-12">
							<div class="jnr__Welcome__thumb">
								<img src="assets/landingpage/images/wel-come/2.jpg" alt="images">
								<a class="play__btn" href="#"><i class="fa fa-play"></i></a>
							</div>
						</div>
						<div class="col-md-12 col-lg-6 col-sm-12 md-mt-40 sm-mt-40">
							<div class="welcome__juniro__inner">
								<h3><?php echo lang('lbl_welcome_to_our_school')?></h3>
								<p class="wow flipInX">Lorem ipsum dolor sit amet, consectetur adipisic mExcepteur sint occaecat cupida proident, sunt in culpaoc caecacupidatat non proident, sunt in culpa qui officia destotarem aperiam, eaque ipsa quae ab illo voluptatemseamet occaecat proident, sunt in culpaoc caecacupidatat non proident, destotarem</p>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- End Welcame Area -->
			<?php } else { ?>

			<!-- Start Welcame Area -->
			<?php $cnt = 0; ?>
			<?php foreach($video_background as $v_background) { ?>
			<?php if( $cnt < 1 ) { ?>
			<section class="junior__welcome__area welcome--2 section-padding--lg" style="background-image: url(<?php echo base_url(); ?>uploads/<?php echo $v_background->image; ?>); background-repeat: no-repeat; background-position: center; background-size: 1920px 100%;">
				<?php } ?>
				<?php $cnt++; ?>
				<?php } ?>
				<div class="container">
					<div class="row">
						<div class="col-lg-12 col-sm-12 col-md-12">
							<div class="section__title text-center">
								<h2 class="title__line"><?php echo $school->name; ?></h2>
							</div>
						</div>
					</div>
					<div class="videoSlider slider">
						<?php foreach($video as $vid) { ?>
						<div class="slider-content">
							<div class="row  jn__welcome__wrapper align-items-center">
								<div class="col-md-12 col-lg-6 col-sm-12">
									<div class="jnr__Welcome__thumb">
										<img src="<?php echo base_url(); ?>uploads/<?php echo $vid->image; ?>" style="width: 600px; height: 100%" alt="images">

										<?php $link = explode(".", $vid->link);
											if ($link[1] == 'youtube') {
											$video = implode(".", $link); ?>

										<a class="play__btn" id="youtube" href="<?php echo $video; ?>"><i class="fa fa-play"></i></a>

										<?php } else { 
											$video = implode(".", $link); ?>

											<a class="play__btn" id="drive" target="_blank" href="<?php echo $video; ?>"><i class="fa fa-play"></i></a>

										<?php } ?>

									</div>
								</div>
								<div class="col-md-12 col-lg-6 col-sm-12 md-mt-40 sm-mt-40">
									<div class="welcome__juniro__inner">
										<h3 style="font-weight: revert; color: white;"><?php echo $vid->title; ?></h3>
										<p class="wow flipInX" style="color: white; font-family: sans-serif; font-weight: bold; "><?php echo $vid->description; ?></p>
									</div>
								</div>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
			</section>
			<?php } ?>
			<!-- End Welcame Area -->

			<!-- Start Our Gallery Area -->
			<section class="junior__gallery__area gallery--2 bg--white section-padding--lg" id="gallery">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12 col-sm-12 col-md-12">
							<div class="section__title text-center">
								<h2 class="title__line"><?php echo lang('lbl_our_gallery')?></h2>
							</div>
						</div>
					</div>
					<?php if(count($images) < 4 ) { ?>
					<div class="row galler__wrap mt--40">
						<!-- Start Single Gallery -->
						<div class="col-lg-3 col-md-6 col-sm-6 col-12">
							<div class="gallery">
								<div class="gallery__thumb">
									<a href="#">
										<img src="assets/landingpage/images/gallery/gallery-2/1.jpg" alt="gallery images">
									</a>
								</div>
								<div class="gallery__hover__inner">
									<div class="gallery__hover__action">
										<ul class="gallery__zoom">
											<li><a href="assets/landingpage/images/gallery/big-img/1.jpg" data-lightbox="grportimg" data-title="My caption"><i class="fa fa-search"></i></a></li>
										</ul>
									</div>
								</div>
							</div>	
						</div>	
						<!-- End Single Gallery -->
						<!-- Start Single Gallery -->
						<div class="col-lg-3 col-md-6 col-sm-6 col-12">
							<div class="gallery">
								<div class="gallery__thumb">
									<a href="#">
										<img src="assets/landingpage/images/gallery/gallery-2/2.jpg" alt="gallery images">
									</a>
								</div>
								<div class="gallery__hover__inner">
									<div class="gallery__hover__action">
										<ul class="gallery__zoom">
											<li><a href="assets/landingpage/images/gallery/big-img/2.jpg" data-lightbox="grportimg" data-title="My caption"><i class="fa fa-search"></i></a></li>
										</ul>
									</div>
								</div>
							</div>	
						</div>	
						<!-- End Single Gallery -->
						<!-- Start Single Gallery -->
						<div class="col-lg-3 col-md-6 col-sm-6 col-12">
							<div class="gallery">
								<div class="gallery__thumb">
									<a href="#">
										<img src="assets/landingpage/images/gallery/gallery-2/3.jpg" alt="gallery images">
									</a>
								</div>
								<div class="gallery__hover__inner">
									<div class="gallery__hover__action">
										<ul class="gallery__zoom">
											<li><a href="assets/landingpage/images/gallery/big-img/3.jpg" data-lightbox="grportimg" data-title="My caption"><i class="fa fa-search"></i></a></li>
										</ul>
									</div>
								</div>
							</div>	
						</div>	
						<!-- End Single Gallery -->
						<!-- Start Single Gallery -->
						<div class="col-lg-3 col-md-6 col-sm-6 col-12">
							<div class="gallery">
								<div class="gallery__thumb">
									<a href="#">
										<img src="assets/landingpage/images/gallery/gallery-2/4.jpg" alt="gallery images">
									</a>
								</div>
								<div class="gallery__hover__inner">
									<div class="gallery__hover__action">
										<ul class="gallery__zoom">
											<li><a href="assets/landingpage/images/gallery/big-img/4.jpg" data-lightbox="grportimg" data-title="My caption"><i class="fa fa-search"></i></a></li>
										</ul>
									</div>
								</div>
							</div>	
						</div>	
						<!-- End Single Gallery -->
					</div>
					<?php } else { ?>
					<div class="row galler__wrap mt--40 gallerySlider slider">
						<!-- Start Single Gallery -->
						<?php foreach ($images as $img) { ?>
						<div class="col-lg-3 col-md-6 col-sm-6 col-12">
							<div class="gallery">
								<div class="gallery__thumb">
									<a href="#">
										<img style="width: 450px; height: 320px;" src="<?php echo base_url(); ?>uploads/<?php echo $img->image; ?>" alt="gallery images">
									</a>
								</div>
								<div class="gallery__hover__inner">
									<div class="gallery__hover__action">
										<ul class="gallery__zoom">
											<li><a href="<?php echo base_url(); ?>uploads/<?php echo $img->image; ?>" data-lightbox="grportimg" data-title="My caption"><i class="fa fa-search"></i></a></li>
										</ul>
									</div>
								</div>
							</div>			
						</div>
						<?php } ?>
						<?php } ?>	
						<!-- End Single Gallery -->
					</div>	
				</div>
			</section>
			<!-- End Our Gallery Area -->

			<!-- Start Team Area -->
			<section class="dcare__team__area pb--150 bg--white " id="teachers">
				<div class="container ">
					<div class="row">
						<div class="col-lg-12 col-sm-12 col-md-12">
							<div class="section__title text-center">
								<h2 class="title__line"><?php echo lang('lbl_our_teachers')?></h2>
							</div>
						</div>
					</div>
					<div class="row mt--40 teachersSlider slider">
						<?php if(count($teachers) < 3 ) { ?>
						<!-- Start Single Team -->
						<div class="col-lg-4 col-md-6 col-sm-6 col-12">
							<div class="team__style--3 team--4 hover-color-2">
								<div class="team__thumb">
									<img src="assets/landingpage/images/team-3/1.jpg" alt="team images">
								</div>
								<div class="team__hover__action">
									<div class="team__details">
										<div class="team__info">
											<h6><a><?php echo lang('lbl_teacher_name')?></a></h6>
											<span><?php echo lang('lbl_designation')?></span>
										</div>
										<p>Lorem ipsum dolor sit amet, consecteturadmodi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.</p>
									</div>
								</div>
							</div>
						</div>
						<!-- End Single Team -->
						<!-- Start Single Team -->
						<div class="col-lg-4 col-md-6 col-sm-6 col-12">
							<div class="team__style--3 team--4 hover-color-2">
								<div class="team__thumb">
									<img src="assets/landingpage/images/team-3/1.jpg" alt="team images">
								</div>
								<div class="team__hover__action">
									<div class="team__details">
										<div class="team__info">
											<h6><a><?php echo lang('lbl_teacher_name')?></a></h6>
											<span><?php echo lang('lbl_designation')?></span>
										</div>
										<p>Lorem ipsum dolor sit amet, consecteturadmodi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.</p>
									</div>
								</div>
							</div>
						</div>
						<!-- End Single Team -->
						<!-- Start Single Team -->
						<div class="col-lg-4 col-md-6 col-sm-6 col-12">
							<div class="team__style--3 team--4 hover-color-2">
								<div class="team__thumb">
									<img src="assets/landingpage/images/team-3/1.jpg" alt="team images">
								</div>
								<div class="team__hover__action">
									<div class="team__details">
										<div class="team__info">
											<h6><a><?php echo lang('lbl_teacher_name')?></a></h6>
											<span><?php echo lang('lbl_designation')?></span>
										</div>
										<p>Lorem ipsum dolor sit amet, consecteturadmodi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.</p>
									</div>
								</div>
							</div>
						</div>
						<!-- End Single Team -->
						<?php } else { ?>
						<?php foreach($teachers as $teacher) { ?>
						<div class="col-lg-4 col-md-6 col-sm-6 col-12 slide">
							<div class="team__style--3 team--4 hover-color-2">
								<div class="team__thumb ">
									<img src="<?php echo base_url(); ?>uploads/<?php echo $teacher->image; ?>" style="width: 100%; height: 526px;" alt="team images">
								</div>
								<div class="team__hover__action">
									<div class="team__details">
										<div class="team__info">
											<h6><a><?php echo $teacher->name; ?></a></h6>
											<span><?php echo $teacher->designation; ?></span>
										</div>
										<p><?php echo $teacher->description; ?></p>
									</div>
								</div>
							</div>
						</div>
						<?php } ?>
						<?php } ?>
						<!-- End Single Team -->
					</div>
				</div>
			</section>
			<!-- End Team Area -->
			<!-- Start Counter Up Area -->
			<?php $cnt=0; ?>
			<?php foreach($stats_background as $s_background) { ?>
			<?php if( $cnt < 1 ) { ?>
			<section class="dcare__counterup__area section-padding--lg" style="background-image: url(<?php echo base_url(); ?>uploads/<?php echo $s_background->image; ?>); background-repeat: no-repeat; background-position: center; background-size: 1920px 100%;">
				<?php } ?>
				<?php $cnt++; ?>
				<?php }?>
				<?php  if($total_students > 0 || $total_classes > 0 || $total_bus > 0 || $total_employees > 0 ) {?>
				<div class="container">
					<div class="row">
						<div class="col-md-12 col-lg-12 col-sm-12">
							<div class="counterup__wrapper d-flex flex-wrap flex-lg-nowrap flex-md-nowrap justify-content-between">
								<!-- Start Single Fact -->
								<div class="funfact">
									<div class="fact__icon">
										<img src="assets/landingpage/images/funfact/1.png" alt="flat icon">
									</div>
									<div class="fact__count ">
										<span class="count"><?php echo $total_students; ?></span>
									</div>
									<div class="fact__title">
										<h2><?php echo lang('lbl_students')?></h2>
									</div>
								</div> 
								<!-- End Single Fact -->
								<!-- Start Single Fact -->
								<div class="funfact">
									<div class="fact__icon">
										<img src="assets/landingpage/images/funfact/2.png" alt="flat icon">
									</div>
									<div class="fact__count ">
										<span class="count color--2"><?php echo $total_classes; ?></span>
									</div>
									<div class="fact__title">
										<h2><?php echo lang('lbl_classes')?></h2>
									</div>
								</div> 
								<!-- End Single Fact -->
								<!-- Start Single Fact -->
								<div class="funfact">
									<div class="fact__icon">
										<img src="assets/landingpage/images/funfact/3.png" alt="flat icon">
									</div>
									<div class="fact__count ">
										<span class="count color--3"><?php echo $total_employees; ?></span>
									</div>
									<div class="fact__title">
										<h2><?php echo lang('lbl_employees')?></h2>
									</div>
								</div> 
								<!-- End Single Fact -->
								<!-- Start Single Fact -->
								<div class="funfact">
									<div class="fact__icon">
										<img src="assets/landingpage/images/funfact/4.png" alt="flat icon">
									</div>
									<div class="fact__count">
										<span class="count color--4"><?php echo $total_bus; ?></span>
									</div>
									<div class="fact__title">
										<h2><?php echo lang('lbl_school_bus')?></h2>
									</div>
								</div> 
								<!-- End Single Fact -->
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
			</section>
			<!-- End Counter Up Area -->
			<!-- End Subscribe Area -->
			<!-- Footer Area -->
			<footer id="footer" class="footer-area">
				<div class="footer__wrapper poss-relative ftr__btm__image section-padding--lg bg--white">
					<div class="container">
						<div class="row">
							<!-- Start Single Widget -->
							<div class="col-lg-3 col-md-6 col-sm-12">
								<div class="footer__widget">
									<div class="ft__logo">
										<a href="#">
											<img style="height: 60px; width: 62px;" src="uploads/logos/<?php echo $school->logo; ?>" alt="logo images">
										</a>
									</div>
									<div class="ftr__details">
									</div>
									<div class="ftr__address__inner">
										<div class="ftr__address">
											<div class="ftr_icon">
												<i class="fa fa-home"></i>
											</div>
											<div class="ftr__contact">
												<p><?php echo $school->address; ?></p>
											</div>
										</div>
										<div class="ftr__address">
											<div class="ftr_icon">
												<i class="fa fa-phone"></i>
											</div>
											<div class="ftr__contact">
												<p><?php echo $school->phone;?></p>
											</div>
										</div>
										<div class="ftr__address">
											<div class="ftr_icon">
												<i class="fa fa-envelope"></i>
											</div>
											<div class="ftr__contact">
												<p><?php echo $school->email; ?></p>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- End Single Widget -->
							<!-- Start Single Widget -->
							<div class="col-lg-3 col-md-6 col-sm-12 sm-mt-40">
								<div class="footer__widget">
									<h4><?php echo lang('lbl_events')?></h4>
									<div class="footer__innner">
										<div class="ftr__latest__post">
											<!-- Start Single -->
											<?php $cnt = 0; ?>
											<?php foreach($events as $event) { ?>
											<?php if($cnt < 3 ) { ?>
											<div class="single__ftr__post d-flex">
												<div class="ftr__post__details">
													<h6><?php echo $event->title; ?></h6>
													<span style="color: <?php echo $school->theme_color; ?>;"><i class="fa fa-calendar"></i><?php echo $event->start; ?></span>
												</div>
											</div>
											<?php } ?>
											<?php $cnt++; ?>
											<?php } ?>
											<!-- End Single -->
										</div>
									</div>
								</div>
							</div>
							<!-- End Single Widget -->
							<!-- Start Single Widget -->
							<div class="col-lg-3 col-md-6 col-sm-12 md-mt-40 sm-mt-40">
								<div class="footer__widget">
									<h4><?php echo lang('lbl_social_links')?></h4>
									<div class="footer__innner">
										<div class="dcare__twit__wrap">
											<!-- Start Single -->
											<div class="dcare__twit d-flex">
												<div class="dcare__twit__icon">
													<i style="color: #3b5998;" class="fa fa-facebook"></i>
												</div>
												<?php $cnt = 0; ?>
												<?php foreach($facebook as $fb) { ?>
												<?php if($cnt<1) { ?>
												<div class="dcare__twit__details">
													<a style="color: #3b5998;" href="<?php echo $fb->link; ?>" target="blank">Facebook</a>
												</div>
												<?php } ?>
												<?php $cnt++; ?>
												<?php } ?>
											</div>
											<!-- End Single -->
											<!-- Start Single -->
											<div class="dcare__twit d-flex">
												<div class="dcare__twit__icon">
													<i style="color: #00aced;" class="fa fa-twitter"></i>
												</div>
												<?php $cnt = 0; ?>
												<?php foreach($twitter as $tw) { ?>
												<?php if($cnt<1) { ?>
												<div class="dcare__twit__details">
													<a style="color: #00aced;" href="<?php echo $tw->link; ?>" target="blank">Twitter</a>
												</div>
												<?php } ?>
												<?php $cnt++; ?>
												<?php } ?>
											</div>
											<!-- End Single -->
											<!-- Start Single -->
											<div class="dcare__twit d-flex">
												<div class="dcare__twit__icon">
													<i style="color: #007bb6;" class="fa fa-linkedin"></i>
												</div>
												<?php $cnt = 0; ?>
												<?php foreach($linkedin as $li) { ?>
												<?php if($cnt<1) { ?>
												<div class="dcare__twit__details">
													<a style="color: #007bb6;" href="<?php echo $li->link; ?>" target="blank">LinkedIn</a>
												</div>
												<?php } ?>
												<?php $cnt++; ?>
												<?php } ?>
											</div>
											<!-- End Single -->
											<!-- Start Single -->
											<div class="dcare__twit d-flex">
												<div class="dcare__twit__icon">
													<i style="color: #bb0000;" class="fa fa-youtube"></i>
												</div>
												<?php $cnt = 0; ?>
												<?php foreach($youtube as $yt) { ?>
												<?php if($cnt<1) { ?>
												<div class="dcare__twit__details">
													<a style="color: #bb0000;" href="<?php echo $yt->link; ?>" target="blank">YouTube</a>
												</div>
												<?php } ?>
												<?php $cnt++; ?>
												<?php } ?>
											</div>
											<!-- End Single -->
										</div>
									</div>
								</div>
							</div>
							<!-- End Single Widget -->
							<!-- Start Single Widget -->
							<div class="col-lg-3 col-md-6 col-sm-12 md-mt-40 sm-mt-40">
								<div class="footer__widget">
									<h4><?php echo lang('lbl_from_gallery')?></h4>
									<div class="footer__innner">
										<div class="dcare__twit__wrap">
											<ul class="sm__gallery__list d-flex flex-wrap">
												<?php if(count($images) == 0 ) { ?>
												<li><img src="assets/landingpage/images/gallery/sm-gallery/1.jpg" alt="gallery images"></li>
												<li><img src="assets/landingpage/images/gallery/sm-gallery/1.jpg" alt="gallery images"></li>
												<li><img src="assets/landingpage/images/gallery/sm-gallery/1.jpg" alt="gallery images"></li>
												<li><img src="assets/landingpage/images/gallery/sm-gallery/1.jpg" alt="gallery images"></li>
												<li><img src="assets/landingpage/images/gallery/sm-gallery/1.jpg" alt="gallery images"></li>
												<li><img src="assets/landingpage/images/gallery/sm-gallery/1.jpg" alt="gallery images"></li>
												<li><img src="assets/landingpage/images/gallery/sm-gallery/1.jpg" alt="gallery images"></li>
												<li><img src="assets/landingpage/images/gallery/sm-gallery/1.jpg" alt="gallery images"></li>
												<li><img src="assets/landingpage/images/gallery/sm-gallery/1.jpg" alt="gallery images"></li>
												<?php } else { ?>
												<?php $cnt = 0; ?>
												<?php foreach ($images as $img) { ?>
												<?php if ($cnt < 9 ) { ?>
												<li><img src="<?php echo base_url(); ?>uploads/<?php echo $img->image; ?>" alt="gallery images" style="width: 100%; height: 85px;"></li>
												<?php $cnt++; ?>
												<?php } ?>
												<?php } ?>
												<?php } ?>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<!-- End Single Widget -->
						</div>
					</div>
				</div>
				<?php include(APPPATH . "views/inc/footer.php"); ?>
			</footer>
			<!-- //Footer Area -->

		</div><!-- //Main wrapper -->
               
		<!-- JS Files -->
		<script src="assets/landingpage/js/vendor/jquery-3.2.1.min.js"></script>
		<script src="assets/landingpage/js/popper.min.js"></script>
		<script src="assets/landingpage/js/bootstrap.min.js"></script>
		<script src="assets/landingpage/js/plugins.js"></script>
		<script src="assets/landingpage/js/active.js"></script>
		<script type="text/javascript">

			$(document).ready(function(){


				$('.classesSlider').slick({
					slidesToShow: 4,
					slidesToScroll: 1,
					autoplay: true,
					autoplaySpeed: 2000,
					arrows: false,
					dots: false,
					pauseOnHover: true,
					responsive: [{
						breakpoint: 1000,
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
				//
				$('.teachersSlider').slick({
					slidesToShow: 3,
					slidesToScroll: 1,
					autoplay: true,
					autoplaySpeed: 2000,
					arrows: false,
					dots: false,
					pauseOnHover: true,
					responsive: [{
						breakpoint: 1000,
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

				$('.videoSlider').slick({
					slidesToShow: 1,
					slidesToScroll: 1,
					autoplay: true,
					autoplaySpeed: 2000,
					arrows: false,
					dots: false,
					pauseOnHover: true,
					responsive: [{
						breakpoint: 1000,
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

				$('.gallerySlider').slick({
					slidesToShow: 4,
					slidesToScroll: 1,
					autoplay: true,
					autoplaySpeed: 1000,
					arrows: false,
					dots: false,
					pauseOnHover: true,
					responsive: [{
						breakpoint: 1000,
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
	</body>
	</html>

<!-- <html>
<head>
	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
	<base href="<?php echo base_url(); ?>" />
	<title>Home Page</title>
	<style>
		*{
			margin:0;
			padding:0;
		}
		.goog-logo-link {
		   display:none !important;
		} 

		.goog-te-gadget{
		   color: transparent !important;
		}
		.goog-te-gadget-icon{
			display: none !important; 
		}
		..goog-te-gadget-simple{
			border-radius: 80px;
		}
		.goog-te-banner-frame.skiptranslate {
		    display: none !important;
		    } 
		body {
		    top: 0px !important; 
		    }
	</style>
</head>
<body>
	<div id="google_translate_element" style="margin-bottom:-15px; margin-top: 18px; float: right;"></div>
<?php 
echo $page_html; ?>
<script type="text/javascript">
		function googleTranslateElementInit() {
		  	//new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
		  	new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'ar,en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, multilanguagePage: true, autoDisplay: false }, 'google_translate_element');
		  	$('.goog-te-menu-value').on('DOMSubtreeModified', 'span', function(){
                   language = $(".goog-te-menu-value span").html();

                    if (language == "Arabic"){
                        $('.footer-container').attr('direction', 'rtl');
                    }
                    else{
                        $('.footer-container').css('direction', 'ltr');
                    }
                });
			  	$(".goog-te-gadget-simple").css({
			  		'border-radius': '25px',
			  		'margin-top': '4px',
			  		'margin-right': '6px'
			  	});
			  }
</script>

</body>
</html> -->