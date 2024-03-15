<!DOCTYPE html>
<html lang="en" <?php
if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {
    echo 'dir="ltr"';
} else {
    echo 'dir="rtl"';
}
?>>
 <head>
        
        <title><?php echo SITENAME; ?></title>
        
        <link href="<?php echo base_url();?>assets/uv-landing/stylesheets/my_style.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url();?>assets/uv-landing/stylesheets/style.css" media="screen" rel="stylesheet" type="text/css" />
        <script src="<?php echo base_url();?>assets/uv-landing/javascripts/jquery-1.11.2.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/uv-landing/stylesheets/pageserver.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
        <style type="text/css">
            .large.scrolled {
              background-color: #fff !important;
              transition: background-color 200ms linear;
              box-shadow: 0 2px 5px rgba(50, 50, 50, 0.25);
            }
            
        </style>
</head>

<body style="background-color: #fff;">
<div id="row">
<!-- Menu Start -->

<section>
<header class="large" style="z-index: 999; background: none;">
    <div id="header" style="margin:0px; padding-top: 10px; padding-bottom: 10px; background: none;">
        <div class="wrapper" style="width:95%;">
            <div id="nav-holder">
                <span id="logo"><a href="#"><img src="<?php echo base_url();?>assets/uv-landing/images/uv_logo.png" alt="UVSchools ­ School Management Software" style="height: 20px; padding-top: 9px;"></a></span>
                
                <a  class="contact_phone_icon"></a>
                <div id="contact_popup">
                    <span class="button b-close"></span>
                    <h5>Call us now</h5>
                    <h6>+91 - 8047 0918 07</h6>
                    <span class="divider_section">OR</span>
                    <a href="#"> Let us call you !</a>
                </div>

                <!-- Button trigger modal -->
                <?php
                    if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {?>
                <ul id="primary-nav" class="list-nav-hor dropdown">
                    <li class="primary-nav-item">
                        <a href="#uv_home_section" class="primary-nav-header" style="background: none;"><?php echo lang('lbl_home');?></a>
                    </li>
                    <li class="primary-nav-item">
                        <a href="#uv_Features_section" class="primary-nav-header" style="background: none;"><?php echo lang('lbl_features');?></a>

                    </li>
                    <li class="primary-nav-item">
                        <a href="#uv_Pricing_section" class=" primary-nav-header" style="background: none;"><?php echo lang('lbl_pricing');?></a>
                    </li>
                    <li class="primary-nav-item">
                        <a href="#uv_Test_section" class="primary-nav-header" style="background: none;"><?php echo lang('lbl_clients');?></a>
                    </li>
                    <li class="primary-nav-item">
                        <a href="#uv_contact_section" class="primary-nav-header" style="background: none;"><?php echo lang('lbl_contact');?></a>
                    </li>
                    <?php if ($this->session->userdata("userdata")) { ?>
                    <li class="primary-nav-item">
                        <a href="contact.html" class="primary-nav-header" style="background: none;"><?php echo strtoupper(lang('btn_logout')); ?></a>

                    </li>
                    <?php } else { ?>
                    <li class="primary-nav-item">
                        <a href="contact.html" class="primary-nav-header" style="background: none;"><?php echo strtoupper(lang('btn_get_started')); ?></a>
                    </li>
                    <li class="primary-nav-item">
                        <a href="contact.html" class="primary-nav-header" style="background: none;"><?php echo lang('btn_login'); ?><?php } ?></a>
                    </li>
                    <li class="primary-nav-item" style="padding-top: 12px;">
                      <a>
                        <select class="form-control lang_select" onchange="javascript:window.location.href = '<?php echo base_url(); ?>LanguageSwitcher/switchLang/' + this.value;" style="border-radius: 25px;">
                            <option value="english" <?php if ($this->session->userdata('site_lang') == 'english') echo 'selected="selected"'; ?>>English</option>
                            <option value="arabic" <?php if ($this->session->userdata('site_lang') == 'arabic') echo 'selected="selected"'; ?>>Arabic</option>
                            <option value="urdu" <?php if ($this->session->userdata('site_lang') == 'urdu') echo 'selected="selected"'; ?>>Urdu</option>
                        </select>
                    </a>
                </li>
                </ul>
                <?php } ?>
                <?php
                if ($this->session->userdata('site_lang') == "arabic" || $this->session->userdata('site_lang') == "urdu") {?>
                <ul id="primary-nav" class="list-nav-hor dropdown">
                    <li class="primary-nav-item" style="padding-top: 12px;">
                        <a>
                            <select class="form-control lang_select" onchange="javascript:window.location.href = '<?php echo base_url(); ?>LanguageSwitcher/switchLang/' + this.value;" style="border-radius: 25px;">
                                <option value="english" <?php if ($this->session->userdata('site_lang') == 'english') echo 'selected="selected"'; ?>>English</option>
                                <option value="arabic" <?php if ($this->session->userdata('site_lang') == 'arabic') echo 'selected="selected"'; ?>>Arabic</option>
                                <option value="urdu" <?php if ($this->session->userdata('site_lang') == 'urdu') echo 'selected="selected"'; ?>>Urdu</option>
                            </select>
                        </a>
                    </li>
                    <?php if ($this->session->userdata("userdata")) { ?>
                    <li class="primary-nav-item">
                        <a href="contact.html" class="primary-nav-header" style="background: none;"><?php echo strtoupper(lang('btn_logout')); ?></a>

                    </li>
                    <?php } else { ?>
                    <li class="primary-nav-item">
                        <a href="contact.html" class="primary-nav-header" style="background: none;"><?php echo strtoupper(lang('btn_get_started')); ?></a>
                    </li>
                    <li class="primary-nav-item">
                        <a href="contact.html" class="primary-nav-header" style="background: none;"><?php echo lang('btn_login'); ?><?php } ?></a>
                    </li>
                    <li class="primary-nav-item">
                        <a href="#uv_contact_section" class="primary-nav-header" style="background: none;"><?php echo lang('lbl_contact');?></a>
                    </li>
                    <li class="primary-nav-item">
                        <a href="#uv_Test_section" class="primary-nav-header" style="background: none;"><?php echo lang('lbl_clients');?></a>
                    </li>
                    <li class="primary-nav-item">
                        <a href="#uv_Pricing_section" class=" primary-nav-header" style="background: none;"><?php echo lang('lbl_pricing');?></a>
                    </li>
                    <li class="primary-nav-item">
                        <a href="#uv_Features_section" class="primary-nav-header" style="background: none;"><?php echo lang('lbl_features');?></a>
                    </li>
                    <li class="primary-nav-item">
                        <a href="#uv_home_section" class="primary-nav-header" style="background: none;"><?php echo lang('lbl_home');?></a>
                    </li>
                </ul>
                <?php } ?>  
        </div>
    </div>
</header>
</section>

<!-- Menu End -->

<!-- Slider Start -->

<script src="<?php echo base_url();?>assets/uv-landing/javascripts/jssor.slider-28.0.0.min.js" type="text/javascript"></script>
 <script type="text/javascript">
        window.jssor_1_slider_init = function() {

            var jssor_1_SlideoTransitions = [
              [{b:-1,d:1,kX:16}],
              [{b:-1,d:1,y:200,rY:-360,sX:0.5,sY:0.5,p:{y:{o:32,d:1,dO:9},rY:{c:0}}},{b:0,d:3000,y:0,o:1,rY:0,sX:1,sY:1,e:{y:1,o:13,rY:1,sX:1,sY:1},p:{y:{dl:0},o:{dl:0.1,rd:3},rY:{dl:0.1,o:33},sX:{dl:0.1,o:33},sY:{dl:0.1,o:33}}}],
              [{b:-1,d:1,y:200,rY:-360,sX:0.5,sY:0.5,p:{y:{o:32,d:1,dO:9},rY:{c:0}}},{b:0,d:3000,y:0,o:1,rY:0,sX:1,sY:1,e:{y:1,o:13,rY:1,sX:1,sY:1},p:{y:{dl:0},o:{dl:0.1,rd:3},rY:{dl:0.1,o:33},sX:{dl:0.1,o:33},sY:{dl:0.1,o:33}}}],
              [{b:-1,d:1,y:100,rY:-360,sX:0.5,sY:0.5,p:{y:{o:32,d:1,dO:9},rY:{c:0}}},{b:0,d:3000,y:0,o:1,rY:0,sX:1,sY:1,e:{y:1,o:13,rY:1,sX:1,sY:1},p:{y:{dl:0},o:{dl:0.02,rd:3},rY:{dl:0.02,o:33},sX:{dl:0.02,o:33},sY:{dl:0.02,o:33}}}],
              [{b:2000,d:1000,y:50,e:{y:3}}],
              [{b:-1,d:1,bl:[8]},{b:2000,d:1000,bl:[3],e:{bl:3}}],
              [{b:-1,d:1,rp:1},{b:2000,d:1000,o:0.6},{b:2000,d:1000,rp:0}],
              [{b:-1,d:1,sX:0.7}],
              [{b:1000,d:2000,y:195,e:{y:3}}],
              [{b:600,d:2000,y:195,e:{y:3}}],
              [{b:1400,d:2000,y:195,e:{y:3}}],
              [{b:-1,d:1,sX:0.7,ls:2},{b:0,d:800,o:1,ls:0,e:{ls:6}}],
              [{b:-1,d:801,rp:1}],
              [{b:-1,d:1,kY:-6}],
              [{b:-1,d:1,x:30,kY:-10},{b:1400,d:1500,x:0,o:1,e:{x:27,o:6}}],
              [{b:-1,d:1,c:{t:0}},{b:1400,d:1500,c:{t:339},e:{c:{t:3}}}],
              [{b:-1,d:1,x:30,kY:-10},{b:1700,d:1500,x:0,o:1,e:{x:27,o:6}}],
              [{b:-1,d:1,c:{t:0}},{b:1700,d:1500,c:{t:339},e:{c:{t:3}}}],
              [{b:-1,d:1,sX:0.3,sY:0.3},{b:400,d:1000,o:1,sX:1,sY:1,e:{sX:3,sY:3}}],
              [{b:-1,d:1,sX:0.3,sY:0.3},{b:0,d:1800,x:-347,y:-94,o:1,sX:1,sY:1,e:{x:3,y:3,sX:3,sY:3}}],
              [{b:-1,d:1,sX:0.3,sY:0.3},{b:180,d:1520,x:-230,y:-217,o:1,sX:1,sY:1,e:{x:3,y:3,sX:3,sY:3}}],
              [{b:-1,d:1,sX:0.3,sY:0.3},{b:400,d:1500,x:-120,y:-179,o:1,sX:1,sY:1,e:{x:3,y:3,sX:3,sY:3}}],
              [{b:-1,d:1,sX:0.3,sY:0.3},{b:500,d:1600,x:120,y:-167,o:1,sX:1,sY:1,e:{x:3,y:3,sX:3,sY:3}}],
              [{b:-1,d:1,sX:0.3,sY:0.3},{b:800,d:800,x:301,y:-100,o:1,sX:1,sY:1,e:{x:3,y:3,sX:3,sY:3}}],
              [{b:-1,d:1,sX:0.3,sY:0.3},{b:600,d:1000,x:312,y:-92,o:1,sX:1,sY:1,e:{x:3,y:3,sX:3,sY:3}}],
              [{b:-1,d:1,sX:0.3,sY:0.3},{b:100,d:800,x:388,y:-161,o:1,sX:1,sY:1,e:{x:3,y:3,sX:3,sY:3}}]
            ];

            var jssor_1_options = {
              $AutoPlay: 1,
              $SlideDuration: 800,
              $SlideEasing: $Jease$.$OutQuint,
              $CaptionSliderOptions: {
                $Class: $JssorCaptionSlideo$,
                $Transitions: jssor_1_SlideoTransitions
              },
              $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$
              },
              $BulletNavigatorOptions: {
                $Class: $JssorBulletNavigator$,
                $SpacingX: 16,
                $SpacingY: 16
              }
            };

            var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);

            /*#region responsive code begin*/

            var MAX_WIDTH = 1366;

            function ScaleSlider() {
                var containerElement = jssor_1_slider.$Elmt.parentNode;
                var containerWidth = containerElement.clientWidth;

                if (containerWidth) {

                    var expectedWidth = Math.min(MAX_WIDTH || containerWidth, containerWidth);

                    jssor_1_slider.$ScaleWidth(expectedWidth);
                }
                else {
                    window.setTimeout(ScaleSlider, 30);
                }
            }

            ScaleSlider();

            $Jssor$.$AddEvent(window, "load", ScaleSlider);
            $Jssor$.$AddEvent(window, "resize", ScaleSlider);
            $Jssor$.$AddEvent(window, "orientationchange", ScaleSlider);
            /*#endregion responsive code end*/
        };
    </script>
    <style>
        /*jssor slider loading skin spin css*/
        .jssorl-009-spin img {
            animation-name: jssorl-009-spin;
            animation-duration: 1.6s;
            animation-iteration-count: infinite;
            animation-timing-function: linear;
        }

        @keyframes jssorl-009-spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /*jssor slider bullet skin 057 css*/
        .jssorb057 .i {position:absolute;cursor:pointer;}
        .jssorb057 .i .b {fill:none;stroke:#fff;stroke-width:2200;stroke-miterlimit:10;stroke-opacity:0.4;}
        .jssorb057 .i:hover .b {stroke-opacity:.7;}
        .jssorb057 .iav .b {stroke-opacity: 1;}
        .jssorb057 .i.idn {opacity:.3;}

        /*jssor slider arrow skin 051 css*/
        .jssora051 {display:block;position:absolute;cursor:pointer;}
        .jssora051 .a {fill:none;stroke:#fff;stroke-width:360;stroke-miterlimit:10;}
        .jssora051:hover {opacity:.8;}
        .jssora051.jssora051dn {opacity:.5;}
        .jssora051.jssora051ds {opacity:.3;pointer-events:none;}
    </style>     

    <svg viewbox="0 0 0 0" width="0" height="0" style="display:block;position:relative;left:0px;top:0px;">
        <defs>
            <filter id="jssor_1_flt_1" x="-50%" y="-50%" width="200%" height="200%">
                <feGaussianBlur id="jssor_1_gbl_1" stddeviation="8" data-t="5"></feGaussianBlur>
            </filter>
            <mask id="jssor_1_msk_2">
                <path fill="#ffffff" d="M15.3,6.8C16.4,6.4 17.5,6 18.5,5.7C27.1,2.9 39-4.1 47.1,3.3C50.4,6.3 53.4,22.2 51.3,25.6C45.9,34.5 32.6,27.4 25.1,26.1C18.9,25 10.1,32.8 4.4,28.4C-5.6,20.7 8.9,9.3 15.3,6.8Z" x="0" y="0" style="position:absolute;overflow:visible;"></path>
            </mask>
            <filter id="jssor_1_flt_3" x="-50%" y="-50%" width="200%" height="200%">
                <feGaussianBlur stddeviation="2" result="r1"></feGaussianBlur>
                <feColorMatrix in="r1" type="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 3 -1" result="r2"></feColorMatrix>
            </filter>
            <filter id="jssor_1_flt_4" x="-50%" y="-50%" width="200%" height="200%">
                <feGaussianBlur stddeviation="10"></feGaussianBlur>
            </filter>
            <filter id="jssor_1_flt_5" x="-50%" y="-50%" width="200%" height="200%">
                <feGaussianBlur stddeviation="10"></feGaussianBlur>
            </filter>
            <filter id="jssor_1_flt_6" x="-50%" y="-50%" width="200%" height="200%">
                <feGaussianBlur stddeviation="10"></feGaussianBlur>
            </filter>
            <mask id="jssor_1_msk_8">
                <text data-to="130px 50px" fill="#ffffff" id="jssor_1_lyr_7" text-anchor="middle" x="130" y="100" data-t="11" style="position:absolute;opacity:0;font-family:Arial,Helvetica,sans-serif;font-size:130px;font-weight:900;letter-spacing:2em;overflow:visible;">
                </text>
            </mask>
            <mask id="jssor_1_msk_9">
                <image data-load="href" width="980" height="380" x="0" y="0" style="position:absolute;max-width:980px;" href="<?php echo base_url();?>assets/uv-landing/img/makeup-mask.png"></image>
            </mask>
        </defs>
    </svg>
    <div id="jssor_1" style="position:relative;margin:0 auto;top:0px;left:0px;width:980px;height:380px;overflow:hidden;visibility:hidden;">
        <!-- Loading Screen -->
        <div data-u="loading" class="jssorl-009-spin" style="position:absolute;top:0px;left:0px;width:100%;height:100%;text-align:center;background-color:rgba(0,0,0,0.7);">
            <img style="margin-top:-19px;position:relative;top:50%;width:38px;height:38px;" src="<?php echo base_url();?>assets/uv-landing/img/spin.svg" />
        </div>
        <div data-u="slides" style="cursor:default;position:relative;top:0px;left:0px;width:980px;height:380px;overflow:hidden;">
            <div data-p="680">
                <img data-u="image" src="<?php echo base_url();?>assets/uv-landing/img/sh_slider.jpg" />
                <div data-to="50% 50%" data-ts="flat" data-p="680" data-po="80% 50%" data-t="0" style="left:0px;top:0px;width:980px;height:380px;position:absolute;">
                    <div data-to="50% 80%" data-arr="1" style="left:480px;top:85px;width:300px;height:48px;position:absolute;opacity:0;color:#99958a;font-size:38px;line-height:1.2;letter-spacing:0.1em;">UVSchools</div>
                    <div data-to="50% 80%" data-arr="2" style="left:495px;top:165px;width:350px;height:26px;position:absolute;opacity:0;color:#4d4b45;font-size:22px;line-height:1.2;letter-spacing:0.2em;text-align:center;">OUR VISION</div>
                    <div data-to="50% 80%" data-arr="3" style="left:498px;top:200px;width:400px;height:50px;position:absolute;opacity:0;color:#000;font-size:10px;line-height:1.2;letter-spacing:0.2em;"><div>Combine Innvoation<br />To digital future,</div><div>To Knowledge<br />To Learn & Educate Smartly Under one roof</div></div>
                </div>
            </div>
            <div data-p="680">
                <img data-u="image" src="<?php echo base_url();?>assets/uv-landing/img/banner_juice.jpg" />
                <img data-to="50% 50%" data-t="4" style="left:595px;top:-267px;width:271px;height:266px;position:absolute;max-width:271px;" src="<?php echo base_url();?>assets/uv-landing/img/juce-pomegranate.png" />
                <svg viewbox="0 0 52 31" width="52" height="31" data-tchd="jssor_1_msk_2" style="left:766px;top:322px;display:block;position:absolute;overflow:visible;">
                    <g fill="rgb(0, 0, 0)" stroke="none" stroke-width="1" mask="url(#jssor_1_msk_2)">
                        <image data-load="href" width="63" height="20" filter="url(#jssor_1_flt_1)" x="-20" y="10" data-t="6" data-tsep="jssor_1_gbl_1" style="position:absolute;opacity:0;max-width:63px;" href="<?php echo base_url();?>assets/uv-landing/img/juce-pomegranate-shadow.png"></image>
                    </g>
                </svg>
                

                
                <svg viewbox="0 0 350 100" data-to="50% 50%" width="350" height="100" data-t="7" style="left:496px;top:108px;display:block;position:absolute;font-family:Arial,Helvetica,sans-serif;font-size:20px;font-weight:800;overflow:visible;">
                    <text fill="#6b0215" text-anchor="middle" x="175" y="100">Transform Good School to Best School
                    </text>
                </svg>
                <svg viewbox="0 0 260 130" data-ts="preserve-3d" width="260" height="130" data-t="12" data-tchd="jssor_1_msk_8" style="left:115px;top:100px;display:block;position:absolute;overflow:visible;">
                    <g mask="url(#jssor_1_msk_8)">
                        <path fill="#b10626" d="M260,0L260,130L0,130L0,0Z" x="0" y="0" style="position:absolute;overflow:visible;"></path>
                        <svg viewbox="0 0 260 130" data-ts="preserve-3d" x="0" y="0" width="260" height="130" style="position:absolute;overflow:visible;">
                            <g filter="url(#jssor_1_flt_3)">
                                <!-- <image data-load="href" width="370" height="100" x="-66" y="35" style="position:absolute;max-width:370px;" href="img/fruit-splash.png"></image> -->
                                <path data-to="25px -30px" filter="url(#jssor_1_flt_4)" fill="#d55a7d" d="M-5-30C-5-46.56854 8.43146-60 25-60C41.56854-60 55-46.56854 55-30C55-13.43146 41.56854,0 25,0C8.43146,0 -5-13.43146 -5-30Z" x="-5" y="-60" data-t="8" style="position:absolute;opacity:0.4;overflow:visible;"></path>
                                <path data-to="94px -20px" filter="url(#jssor_1_flt_5)" fill="#d55a7d" d="M54-20C54-42.09139 71.90861-60 94-60C116.09139-60 134-42.09139 134-20C134,2.09139 116.09139,20 94,20C71.90861,20 54,2.09139 54-20Z" x="54" y="-60" data-t="9" style="position:absolute;opacity:0.4;overflow:visible;"></path>
                                <path data-to="200px -10px" filter="url(#jssor_1_flt_6)" fill="#d55a7d" d="M150-10C150-37.61424 172.38576-60 200-60C227.61424-60 250-37.61424 250-10C250,17.61424 227.61424,40 200,40C172.38576,40 150,17.61424 150-10Z" x="150" y="-60" data-t="10" style="position:absolute;opacity:0.4;overflow:visible;"></path>
                            </g>
                        </svg>
                    </g>
                </svg>
            </div>
            <div data-p="680">
                <img data-u="image" src="<?php echo base_url();?>assets/uv-landing/img/uv-mobile.jpg" />
                <div data-to="50% 50%" data-ts="preserve-3d" data-t="13" style="left:505px;top:83px;width:400px;height:150px;position:absolute;">
                    <div data-to="50% 50%" data-ts="preserve-3d" data-t="15" data-arr="14" style="left:46px;top:15px;width:339px;height:40px;position:absolute;opacity:0;color:#fff;font-size:20px;line-height:2;letter-spacing:0.08em;padding:0px 0px 0px 10px;box-sizing:border-box;background-color:#bf7c00;background-clip:padding-box;">100% Cloud-based</div>
                    <div data-to="50% 50%" data-ts="preserve-3d" data-t="17" data-arr="16" style="left:16px;top:95px;width:339px;height:40px;position:absolute;opacity:0;color:#fff;font-size:20px;line-height:2;letter-spacing:0.1em;padding:0px 0px 0px 10px;box-sizing:border-box;background-color:#bf7c00;background-clip:padding-box;">360 Degree Visibility</div>
                </div>
                <svg viewbox="0 0 980 380" data-ts="preserve-3d" width="980" height="380" style="left:0px;top:0px;display:block;position:absolute;overflow:visible;">
                    <g>
                        <svg viewbox="0 0 980 380" data-ts="preserve-3d" x="0" y="0" width="980" height="380" data-tchd="jssor_1_msk_9" style="position:absolute;overflow:visible;">
                            <g mask="url(#jssor_1_msk_9)">
                             <!--   <image data-load="href" width="980" height="380" data-to="484.0px 428.0px" x="-6" y="10" data-t="18" style="position:absolute;opacity:0;max-width:980px;" href="<?php //echo base_url();?>assets/uv-landing/img/makeup-items.png"></image>
                                <image data-load="href" width="96" height="45" data-to="423px 366px" x="375" y="344" data-t="19" style="position:absolute;opacity:0;max-width:96px;" href="<?php //echo base_url();?>assets/uv-landing/img/makeup-lipstick-1.png"></image>
                                <image data-load="href" width="98" height="86" data-to="424px 387px" x="375" y="344" data-t="20" style="position:absolute;opacity:0;max-width:98px;" href="<?php //echo base_url();?>assets/uv-landing/img/makeup-lipstick-2.png"></image>
                                <image data-load="href" width="101" height="122" data-to="425px 381px" x="375" y="320" data-t="21" style="position:absolute;opacity:0;max-width:101px;" href="<?php //echo base_url();?>assets/uv-landing/img/makeup-lipstick-3.png"></image>
                                <image data-load="href" width="52" height="77" data-to="482px 428px" x="456" y="390" data-t="22" style="position:absolute;opacity:0;max-width:52px;" href="<?php //echo base_url();?>assets/uv-landing/img/makeup-lipstick-4.png"></image>
                                <image data-load="href" width="23" height="18" data-to="497px 435px" x="486" y="426" data-t="23" style="position:absolute;opacity:0;max-width:23px;" href="<?php //echo base_url();?>assets/uv-landing/img/makeup-petal-1.png"></image>
                                <image data-load="href" width="19" height="19" data-to="502px 434px" x="493" y="425" data-t="24" style="position:absolute;opacity:0;max-width:19px;" href="<?php //echo base_url();?>assets/uv-landing/img/makeup-petal-2.png"></image>
                                <image data-load="href" width="15" height="18" data-to="514px 422px" x="507" y="413" data-t="25" style="position:absolute;opacity:0;max-width:15px;" href="<?php //echo base_url();?>assets/uv-landing/img/makeup-petal-3.png"></image> -->
                            </g>
                        </svg>
                    </g>
                </svg>
            </div>
        </div><a data-scale="0" href="#" style="display:none;position:absolute;">web design</a>
        <!-- Bullet Navigator -->
        <div data-u="navigator" class="jssorb057" style="position:absolute;bottom:16px;right:16px;" data-autocenter="1" data-scale="0.5" data-scale-bottom="0.75">
            <div data-u="prototype" class="i" style="width:14px;height:14px;">
                <svg viewbox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                    <circle class="b" cx="8000" cy="8000" r="5000"></circle>
                </svg>
            </div>
        </div>
        <!-- Arrow Navigator -->
        <div data-u="arrowleft" class="jssora051" style="width:65px;height:65px;top:0px;left:25px;" data-autocenter="2" data-scale="0.75" data-scale-left="0.75">
            <svg viewbox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                <polyline class="a" points="11040,1920 4960,8000 11040,14080 "></polyline>
            </svg>
        </div>
        <div data-u="arrowright" class="jssora051" style="width:65px;height:65px;top:0px;right:25px;" data-autocenter="2" data-scale="0.75" data-scale-right="0.75">
            <svg viewbox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                <polyline class="a" points="4960,1920 11040,8000 4960,14080 "></polyline>
            </svg>
        </div>
    </div>


<script type="text/javascript">
  jssor_1_slider_init();

</script>

<!-- Slider End -->

<div class="section" id="products" style="z-index: 2; background-color: #f5f5f5; padding-bottom: 20px; padding-top: 20px;">
    <div class="container">  
        <div class="row no-gutters" style="margin-left: 125px;">
            <div class="col-md-5">
                <div class="package" style="border: red 1px solid;">
                    <div class="package__header">
                        <div class="package__icon i-c-17x">
                            <img src="https://campus365.io/wp-content/themes/campus365/assets/img/new_icons/school.svg" style="width: 136px; height: 136px;">
                        </div>
                        <div class="package__title mh-1x">
                            <h3 style="font-size: 32px;"><?php echo lang('lbl_school_tab_title'); ?></h3>
                        </div>
                        <div class="package__content">
                        <p><?php echo lang('lbl_school_tab_desc'); ?></p>
                      </div>
                        <div class="package__price">
                            <ul class="list list--icon list--square text-left" style="list-style: none;">
                                <li><?php echo lang('lbl_school_tab_3'); ?></li>
                                <li><?php echo lang('lbl_school_tab_1'); ?></li>
                                <li><?php echo lang('lbl_school_tab_2'); ?></li>
                            </ul>
                            
                        </div>
                        
                    </div>
                    
                    <a href="/school365" class="package__action">
                        <button class="btn btn--primary btn--outline" style="border: red 1px solid; height: 55px; width: 200px; background:#fff;"><span class="btn__text" style="color: #000"><?php echo lang('lbl_school_tab_buttons'); ?></span></button>
                    </a>
                </div>
            </div>
          <br>
            <div class="col-md-5" style="margin-top: -19px;">
                <div class="package">
                    <div class="package__header">
                        <div class="package__icon i-c-17x">
                            <img src="https://campus365.io/wp-content/themes/campus365/assets/img/new_icons/college.svg" style="width: 136px; height: 136px;">
                        </div>
                        <div class="package__title mh-8x">
                            <h3 style="font-size: 32px;"><?php echo lang('lbl_college_tab_title'); ?></h3>
                        </div>
                       
                      <div class="package__content">
                        <p><?php echo lang('lbl_college_tab_desc'); ?></p>
                      </div>
                        <div class="package__price">
                            <ul class="list list--icon list--square text-left">
                                <li><?php echo lang('lbl_college_tab_3'); ?></li>
                                <li><?php echo lang('lbl_college_tab_1'); ?></li>
                                <li><?php echo lang('lbl_college_tab_2'); ?></li>
                                </br>
                            </ul>
                            
                        </div>
                    </div>
                    <a href="/college365" class="package__action">
                        <button class="btn btn--primary btn--outline" style="border: red 1px solid; height: 55px; width: 200px; background:#fff;"><span class="btn__text" style="color: #000"><?php echo lang('lbl_school_tab_buttons'); ?></span></button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Modules -->
<div class="modules" id="uv_Features_section">
        <h2 id="core_modules"><span></span> <?php echo lang('lbl_frsection_title'); ?> <span></span></h2>
        <ul>
            <li><a href="#" title="Configure and manage multiple courses and batches according to your institution’s systems and process.">
                    <span class="mod-icon course-batch"></span><?php echo lang('lbl_frsection_1'); ?></a></li>
            <li><a href="#" title="Record and organize all employee details enabling quick access to employee information, and effective management of employee payroll and leave.">
                    <span class="mod-icon hr"></span><?php echo lang('lbl_frsection_2'); ?> </a></li>
            <li><a href="#" title="Mark and track student attendance quickly to enable teachers to focus on the lesson at hand.">
                    <span class="mod-icon attendance"></span><?php echo lang('lbl_frsection_3'); ?> </a></li>
            <li><a href="#" title="Create clear and error free timetables within minutes ensuring the best utilization and optimization of teachers and employees across your institution.">
                    <span class="mod-icon timetable"></span><?php echo lang('lbl_frsection_4'); ?> </a></li>
            <li><a href="#" title="From supporting grading systems such as ICSE, CCE, CWA, and GPA to generating various student examinations reports, schedule and manage examinations effortlessly to fit the needs of your institution.">
                    <span class="mod-icon exam"></span><?php echo lang('lbl_frsection_5'); ?> </a></li>
            <li><a href="#" title=" Simple, yet beautifully designed live dashboards for each user role featuring customizable ‘dashlets’ that allows quick access to your favorite and most important information, and an innovative search bar with 'Action Search' functionality.">
                    <span class="mod-icon dashborad"></span><?php echo lang('lbl_frsection_6'); ?></a></li>
            <li><a href="#" title="Efficiently manage the complete student admission process using customized admission forms and help welcome applicants that are the best fit for your institution.">
                    <span class="mod-icon admission"></span><?php echo lang('lbl_frsection_7'); ?></a></li>
            <li><a href="#" title="Four user roles—Administrator, Employee, Student, and Parent—that determine what the user can and cannot do within UVSchools. Administrators can assign and manage employee privileges based on the role played in the institution.">
                    <span class="mod-icon user"></span><?php echo lang('lbl_frsection_8'); ?></a></li>
            <li><a href="#" title="Inform, and be informed of events happening on and off campus by glancing at your color coded UVSchools calendar.">
                    <span class="mod-icon calendar"></span><?php echo lang('lbl_frsection_9'); ?></a></li>
            <li><a href="#" title="Secure, comprehensive, and robust—the Finance module provides a fast and efficient way to register financial transactions, automate transactions, and generate financial reports that will help you gain financial insight on managing costs and expenditures.">
                    <span class="mod-icon finance"></span><?php echo lang('lbl_frsection_10'); ?> </a></li>
            <li><a href="#student_info_tag" title="A quick and easy way to search and access all your student records, both current and archived using various filters.">
                    <span class="mod-icon info"></span><?php echo lang('lbl_frsection_11'); ?></a></li>
            <li><a href="#" title=" Secure personalized login credentials for every student and parent; to empower all students to make the most of UVSchools and achieve their educational goals, while facilitating parents to monitor and track their ward’s progress reports, results, attendance, and many more.">
                    <span class="mod-icon sp-login"></span><?php echo lang('lbl_frsection_12'); ?></a></li>
            <li><a href="#" title="Secure personalized login credentials for every employee, allowing them to perform their tasks efficiently depending on the privileges assigned by their roles.">
                    <span class="mod-icon t-login"></span><?php echo lang('lbl_frsection_13'); ?> </a></li>
            <li><a href="#" title="An inbuilt messaging system for quick and easy communication between employees, students, and parents that also acts as a reminder system by sending the user prompt reminders.">
                    <span class="mod-icon messaging"></span><?php echo lang('lbl_frsection_14'); ?> </a></li>
            <li><a href="#" title="Generate various reports on your students, employees, courses, and fee schedules.">
                    <span class="mod-icon report-center"></span><?php echo lang('lbl_frsection_15'); ?> </a></li>
            <li><a href="#" title="Provide feedback and general comments about a student’s performance in class and in examinations.">
                    <span class="mod-icon remarks"></span><?php echo lang('lbl_frsection_16'); ?> </a></li>
            <li><a href="#" title="Distribute and manage homework/assignments with defined due dates, subject notes, and resources to your students instantly.">
                    <span class="mod-icon assignment"></span><?php echo lang('lbl_frsection_17'); ?></a></li>
            
            <li><a href="#" title="Enable students, teachers, and other employees to collaborate, interact, and exchange information and ideas. Encourage thoughtful dialogue by allowing members to post messages and reply to other messages.">
                    <span class="mod-icon discussion"></span><?php echo lang('lbl_frsection_18'); ?></a></li>
            <li><a href="#" title="Record and track disciplinary incidents to ensure that students are held accountable for their actions.">
                    <span class="mod-icon descpline"></span><?php echo lang('lbl_frsection_19'); ?></a></li>
            <li><a href="#" title="A quick and easy way to allow applicants register for a course online using customized admission forms. Enabling online course registration saves hundreds of hours of data entry, improves employee efficiency, and helps tracking applicants.">
                    <span class="mod-icon registration"></span><?php echo lang('lbl_frsection_20'); ?></a></li>
            <li><a href="#" title="Make it quick and save yourself much time and effort performing common data entry tasks. Upload data in to UVSchools in bulk using predefined CSV files.">
                    <span class="mod-icon custom-import"></span><?php echo lang('lbl_frsection_21'); ?></a></li>
            <li><a href="#" title="Conveniently backup your UVSchools data for data security.">
                    <span class="mod-icon data-export"></span><?php echo lang('lbl_frsection_22'); ?></a></li>
            <li><a href="#" title="In addition to generating specific student and employee reports, you can create custom reports using a wide range of filters.">
                    <span class="mod-icon custom-report"></span><?php echo lang('lbl_frsection_23'); ?></a></li>
            <li><a href="#" title="Assign and manage student fee collections with just a few clicks.">
                    <span class="mod-icon fee-import"></span><?php echo lang('lbl_frsection_24'); ?></a></li>
            <li><a href="#" title="Collect and manage instant fee payments in a few simple steps without having to schedule fee collection dates.">
                    <span class="mod-icon instant-fee"></span><?php echo lang('lbl_frsection_25'); ?></a></li>
            <li><a href="#" title="Customize your email settings to communicate more efficiently with employees, students, and parents. Set automatic email alerts to be sent when admitting students and employees, publishing exam schedules and exam results, nearing fees due dates, and many more.">
                    <span class="mod-icon email-integration"></span><?php echo lang('lbl_frsection_26'); ?></a></li>
            
        
        </ul>
</div>
<!-- End Features Modules -->

<!-- Start App Block --> 
<div class="page-block page page2" id="page_block_header">
  <div class="color-overlay" style="background-color: #24135a;"></div>
  <div class="border-holder">
    <div class="block-inner">
      <div class="page-element widget-container page-element-type-image widget-image" id="element-127" >
        <!-- <div class="contents"> <img src="<?php //echo base_url();?>assets/uv-landing/images/uv_logo.jpeg" style="margin-top: ;" /> </div> -->
      </div>
      <div class="page-element widget-container page-element-type-headline widget-headline " id="element-128" >
        <div class="contents">
          <h1>
            <p style="text-align: center;  ">
              <font color="#ffffff">
                <b>UVSchool Parent APP Features</b>
              </font>
            </p>
          </h1> </div>
      </div>
      <div class="page-element widget-container page-element-type-image widget-image " id="element-180" >
        <style type="text/css">
        #element-180 .cropped {
          background-image: url('<?php echo base_url();?>assets/uv-landing/images/4087942-0-DARKERSHADOW.png');
          background-position: 0px -335px;
          width: 288px;
          height: 40px;
          background-size: 287px 375px;
        }
        
        @media screen and (max-width: 620px), screen and (max-width: 959px) and (-webkit-min-device-pixel-ratio: 1.5) {
          #element-180 .cropped {
            height: 40px;
            width: 287px;
            background-size: 286px 366px;
            background-position: 0px -327px;
          }
        }
        </style>
        <div class="contents">
          <div class='cropped' alt=""></div>
        </div>
      </div>
      <div class="page-element widget-container page-element-type-button widget-button " id="element-130" >
        <style type="text/css">
        #element-130 .dynamic-button {
          background-color: #ffffff;
          color: #e74c3c;
          box-sizing: border-box;
          border: 2px solid #ffffff;
        }
        
        #element-130 .dynamic-button:active {}
        
        #element-130 .dynamic-button:hover {
          color: #e74c3c;
          background-color: #ecf0f1;
        }
        
        #element-130 .dynamic-button,
        #element-130 .image-button {
          width: 285px;
          height: 59px;
          z-index: 24;
          line-height: 24px;
          font-size: 20px;
          font-weight: 700;
          font-family: Open Sans;
          border-radius: 40px;
        }
        
        @media screen and ( max-width: 620px), screen and ( max-width: 999px) and ( -webkit-min-device-pixel-ratio: 1.5) and ( max-device-width: 1280px) and ( max-device-height: 720px), screen and ( max-width: 999px) and ( -webkit-min-device-pixel-ratio: 1.5) and ( max-device-width: 1000px) {
          body:not( .tablet) #page_block_header #element-130 .dynamic-button, body:not( .tablet) #page_block_header #element-130 .image-button {
            width: 287px;
            height: 57px;
            z-index: 24;
            line-height: 57px;
            font-size: 20px;
          }
        }
        </style>
        <div class="conversion_wrapper">
          <a href="../contact9cae.html?utm_source=instapage&amp;utm_medium=landingpage&amp;utm_campaign=UVSchools-mobile" class="url-link" style="width: 285px; height: 59px;" target="_blank" data-wid="130">
            <div class="btn submit-button button_submit dynamic-button   "><?php echo lang('lbl_mapsection_button'); ?></div>
          </a>
        </div>
      </div>
      <div class="page-element widget-container page-element-type-image widget-image " id="element-132" >
        <div class="contents"> 
          <img src="<?php echo base_url();?>assets/uv-landing/images/1578562600-4369452-386x666-4368167-386x666-iPhone5.png" style="margin-top: 0px;" alt="" /> 
          </div>
      </div>
      <div class="page-element widget-container page-element-type-image widget-image " id="element-133" >
        <div class="contents"> 
          <img src="<?php echo base_url();?>assets/uv-landing/images/1578562608-20070811-246x426x246x426x0x0-Mobile.png" style="margin-top: ;" alt="" /> 
          </div>
      </div>
      <div class="page-element widget-container page-element-type-image widget-image " id="element-193" >
        <div class="contents"> 
          <img src="<?php echo base_url();?>assets/uv-landing/images/1578562613-38685706-40x40-Customization.png" style="margin-top: 0px;" alt="" /> 
          </div>
      </div>
      <div class="page-element widget-container page-element-type-image widget-image " id="element-194" >
        <div class="contents"> 
          <img src="<?php echo base_url();?>assets/uv-landing/images/1578562613-38685731-44x38-Student.png" style="margin-top: 0px;" alt="" /> 
          </div>
      </div>
      <div class="page-element widget-container page-element-type-image widget-image " id="element-195" >
        <div class="contents"> 
          <img src="<?php echo base_url();?>assets/uv-landing/images/1578562613-38685721-52x42-Announcement.png" style="margin-top: 0px;" alt="" /> 
          </div>
      </div>
      <div class="page-element widget-container page-element-type-image widget-image " id="element-200" >
        <div class="contents"> 
          <img src="<?php echo base_url();?>assets/uv-landing/images/1578562614-38685681-29x39-Leave.png" style="margin-top: 0px;" alt="" /> 
          </div>
      </div>
      <div class="page-element widget-container page-element-type-image widget-image " id="element-203" >
        <div class="contents"> 
          <img src="<?php echo base_url();?>assets/uv-landing/images/1578562614-38685686-35x31-Teacher.png" style="margin-top: 0px;" alt="" /> 
          </div>
      </div>
      <div class="page-element widget-container page-element-type-image widget-image " id="element-196" >
        <div class="contents"> 
          <img src="<?php echo base_url();?>assets/uv-landing/images/1578562615-38685716-31x39-Alert.png" style="margin-top: 0px;" alt="" /> 
          </div>
      </div>
      <div class="page-element widget-container page-element-type-image widget-image " id="element-199" >
        <div class="contents"> 
          <img src="<?php echo base_url();?>assets/uv-landing/images/1578562615-38685696-42x35-Attendance.png" style="margin-top: 0px;" alt="" /> 
          </div>
      </div>
      <div class="page-element widget-container page-element-type-image widget-image " id="element-204" >
        <div class="contents"> 
          <img src="<?php echo base_url();?>assets/uv-landing/images/1578562616-38685671-38x46-Whitelabel.png" style="margin-top: 0px;" alt="" /> 
          </div>
      </div>
      <div class="page-element widget-container page-element-type-image widget-image " id="element-205" >
        <div class="contents">
         <img src="<?php echo base_url();?>assets/uv-landing/images/1578562616-38685811-34x34-Timetable.png" style="margin-top: 0px;" alt="" /> 
        </div>
      </div>
      <div class="page-element widget-container page-element-type-text widget-text " id="element-207" >
        <div class="contents">
          <p style=" "><font color="#ffffff"><a href="#" rel="null" type="onpage" id="link-eyjvcv4urip" class="onpage-link"><?php echo lang('lbl_mapsection_1'); ?></a></font></p>
        </div>
      </div>
      <div class="page-element widget-container page-element-type-text widget-text " id="element-208" >
        <div class="contents">
          <p style=" "><font color="#ffffff"><a href="#" rel="null" type="onpage" id="link-2sairbhiezx" class="onpage-link"><?php echo lang('lbl_mapsection_2'); ?> </a></font></p>
        </div>
      </div>
      <div class="page-element widget-container page-element-type-text widget-text " id="element-209" >
        <div class="contents">
          <p style=" "><font color="#ffffff"><a href="#" rel="null" type="onpage" id="link-td6dv7eyoab" class="onpage-link"><?php echo lang('lbl_mapsection_3'); ?></a>
          </font></p>
        </div>
      </div>
      <div class="page-element widget-container page-element-type-image widget-image " id="element-201" >
        <div class="contents"> 
          <img src="<?php echo base_url();?>assets/uv-landing/images/1578562617-38685691-36x34-Message.png" style="margin-top: 0px;" alt="" />
         </div>
      </div>
      <div class="page-element widget-container page-element-type-text widget-text " id="element-210" >
        <div class="contents">
          <p style=" "><font color="#ffffff"><a href="#="null" type="onpage" id="link-c5jhxx9xba" class="onpage-link"><?php echo lang('lbl_mapsection_4'); ?></a>
          </font></p>
        </div>
      </div>
      <div class="page-element widget-container page-element-type-text widget-text " id="element-211" >
        <div class="contents">
          <p style=" "><font color="#ffffff"><a href="# rel="null" type="onpage" id="link-ksl44po1zwo" class="onpage-link"><?php echo lang('lbl_mapsection_5'); ?>
          </a></font></p>
        </div>
      </div>
      <div class="page-element widget-container page-element-type-text widget-text " id="element-212" >
        <div class="contents">
          <p style=" "><font color="#ffffff"><a href="#" rel="null" type="onpage" id="link-xy9ffzodn6h" class="onpage-link"><?php echo lang('lbl_mapsection_6'); ?></a></font></p>
        </div>
      </div>
      <div class="page-element widget-container page-element-type-text widget-text " id="element-213" >
        <div class="contents">
          <p style=" "><font color="#ffffff"><a href="#" rel="null" type="onpage" id="link-yh6st5xy8j" class="onpage-link"><?php echo lang('lbl_mapsection_7'); ?></a>
          </font></p>
        </div>
      </div>
      <div class="page-element widget-container page-element-type-text widget-text " id="element-214" >
        <div class="contents">
          <p style=" "><font color="#ffffff"><?php echo lang('lbl_mapsection_8'); ?></font></p>
        </div>
      </div>
      <div class="page-element widget-container page-element-type-text widget-text " id="element-216" >
        <div class="contents">
          <p style=" "><font color="#ffffff"><a href="#" rel="null" type="onpage" id="link-7d6tbul24fq" class="onpage-link"><?php echo lang('lbl_mapsection_9'); ?></a></font></p>
        </div>
      </div>
      <div class="page-element widget-container page-element-type-image widget-image " id="element-202" >
        <div class="contents"> <img src="<?php echo base_url();?>assets/uv-landing/images/1578562617-38685676-42x46-Parent.png" style="margin-top: 0px;" alt="" /> </div>
      </div>
      <div class="page-element widget-container page-element-type-text widget-text " id="element-217" >
        <div class="contents">
          <p style=" "><font color="#ffffff"><a href="#page_block_below_fold" rel="null" type="onpage" id="link-ablsidklcv" class="onpage-link"><?php echo lang('lbl_mapsection_10'); ?></a></font></p>
        </div>
      </div>
      <div class="page-element widget-container page-element-type-text widget-text " id="element-218" >
        <div class="contents">
          <p style=" "><font color="#ffffff"><a href="#page_block_below_fold" rel="null" type="onpage" id="link-s19giwxb98" class="onpage-link"><?php echo lang('lbl_mapsection_11'); ?></a></font></p>
        </div>
      </div>
      <div class="page-element widget-container page-element-type-image widget-image " id="element-220" >
        <div class="contents"> <img src="<?php echo base_url();?>assets/uv-landing/images/1578562617-38685701-46x32-Fee.png" style="margin-top: 0px;" alt="" /> </div>
      </div>
      <div class="page-element widget-container page-element-type-text widget-text " id="element-219" >
        <div class="contents">
          <p style=" "><font color="#ffffff"><a href="#page-block-meu0hn11o6n" rel="null" type="onpage" id="link-dc1b23mtgsr" class="onpage-link"><?php echo lang('lbl_mapsection_12'); ?></a></font></p>
        </div>
      </div>
    </div>
  </div>
</div>        
<!-- End App Block -->

<!-- Start Pricing Module -->
<div id="uv_Pricing_section">
<div id="wrapper">
      <div id="offer_cont_prime_page">
          <div id="prime_offer_main">
              <div class="exclusive_offer_message">
                  <div class="prime_offer_image">
                      <img  src="<?php echo base_url();?>assets/uv-landing/images/prime_bengaluru.jpg" alt="UVSchools | Exclusive packages for Namma Bengaluru">
                  </div>
                  <div class="prime_offer_logo"> 
                      <img alt="UVSchools prime - School ERP" class="prime-logo_bengaluru" src="<?php echo base_url();?>assets/uv-landing/images/Prime_Logo07dd.png?1517564382" />
                      <div class="offer_prime_logo">
                          UVSchools is built and  based in Bengaluru. To make the best of our proximity,
                          We have launched <b>exclusive onsite services and packages</b> for institutions in Bengaluru.
                      </div>
                      <div class="prime_contact_link">
                          <a href="contact23a9.html?loc=bangalore&amp;plan=prime" class="know-more-button know-more-button-prime_plan">Contact Us for a Free Onsite Demo</a>
                      </div>
                  </div>
              </div>
          </div>
          <hr>
      </div>  

      <div class="pricing_plans_content">
        <div class="page-header">
            <h1><?php echo lang("lbl_pricingsec_text1"); ?></h1>
        </div>

        <div class="generous-features">
          <div class="feature">
              <div class="icon students"></div>
              <h2><?php echo lang("lbl_pricingsec_text2"); ?></h2>
          </div>
          <div class="feature">
              <div class="icon logins"></div>
              <h2><?php echo lang("lbl_pricingsec_text3"); ?></h2>
              <p><?php echo lang("lbl_pricingsec_text4"); ?></p>
          </div>
          <div class="feature">
              <div class="icon courses"></div>
              <h2><?php echo lang("lbl_pricingsec_text5"); ?> <br> <?php echo lang("lbl_pricingsec_text6"); ?> </h2>
          </div>
          <div class="feature">
              <div class="icon hosting"></div>
              <h2> <?php echo lang("lbl_pricingsec_text7"); ?> </h2>
          </div>
          <div class="feature">
              <div class="icon secured"></div>
              <h2><?php echo lang("lbl_pricingsec_text8"); ?></h2>
          </div>
        </div>

        <div class="page-header">
            <h1><?php echo lang("lbl_pricingsec_text9"); ?> </h1>
        </div>

        <div class="pricing bangalore_updation">
          <div class="col3 pro bangalore_pro">
              <div class="plan-head">
                  <h5><?php echo lang("lbl_pricingsec_protitle"); ?></h5>
                  
                    <h4>$ 0.0</h4>
                  
                  <h6><?php echo lang("lbl_pricingsec_pro1"); ?></h6>
              </div>
              <div class="plan-details pro">
                  <ul class="plan-features">
                      <li><a href="#"><?php echo lang("update_pricingpanel_box"); ?></a></li>
                      <li><a href="#"><?php echo lang("update_pricingpanel_box1"); ?></a></li>
                      <li><?php echo lang("lbl_pricingsec_pro4"); ?></li>
                      
                  </ul>
                  <a href="contact7aaa.html?plan=pro" class="btn btn-lg btn-red" style="background-color: #e341c2;"><?php echo lang("lbl_pricingsec_probutton"); ?></a>
              </div>
          </div>
          
          <div class="col3 pro-plus bangalore_pro_plus">
                  <div>
                      <div class="plan-head green">
                          
                            <div class="most-poular-area">
                                <span class="most-popular"> <?php echo lang("lbl_pricingsec_pro6"); ?>  </span>
                            </div>
                          
                          <h5><?php echo lang("lbl_pricingsec_proplus"); ?></h5>
                          
                            <h4>$ 4.5</h4>
                          
                          <h6><?php echo lang("lbl_pricingsec_pro1"); ?></h6>
                      </div>
                  </div>
                  <div class="plan-details pro-plus">
                      <ul class="plan-features">
                      		<li><a href="#"><?php echo lang("update_pricingpanel_box2"); ?></a></li>
                          <li><a href="#"><?php echo lang("lbl_pricingsec_pro2"); ?></a></li>
                          <li><a href="#"><?php echo lang("lbl_pricingsec_pro3"); ?></a></li>
                          <li><a href="#"><?php echo lang("lbl_pricingsec_pro4"); ?></a></li>
                            <li><?php echo lang("lbl_pricingsec_pro8"); ?></li>
                            <li><?php echo lang("lbl_pricingsec_pro9"); ?></li>
                            <li><?php echo lang("lbl_pricingsec_pro5"); ?></li>
                      </ul>
                        <a href="contact7ba6.html?plan=proplus" class="btn btn-lg btn-green"><?php echo lang("lbl_pricingsec_proplusbutton"); ?></a>
                  </div>
          </div>
              
          <div class="col3 enterprise">
                <div class="plan-head  enterprise-color">

                    <img alt="UVSchools enterprise - School ERP" class="plan-enterprise-logo" src="<?php echo base_url();?>assets/uv-landing/images/EnterpriseLogo0d98.png?1448536955" />
                    <h4>$ 6</h4>
                    <h6><?php echo lang("lbl_pricingsec_pro1"); ?></h6>
                </div>
                <div class="plan-details">
                    <ul class="plan-features">
                        <li><a href="#core_modules"><?php echo lang("update_pricingpanel_box3"); ?></a></li>
                        <li><a href="#core_modules"><?php echo lang("lbl_pricingsec_pro2"); ?></a></li>
                        <li><a href="#pro_modules"><?php echo lang("lbl_pricingsec_pro3"); ?></a></li>
                        <li><a href="#premium_modules"><?php echo lang("lbl_pricingsec_pro11"); ?></a></li>
                        
                        <li><a style="font-weight: normal" id="priority_support" title=""><?php echo lang("lbl_pricingsec_pro13"); ?></a></li>
                        <li class="enterprise-color enterprise-highlighted"><a style="font-weight: normal" id="onsite_training" title=""><?php echo lang("lbl_pricingsec_pro14"); ?></a></li>
                        <li class="enterprise-color enterprise-highlighted"><a style="font-weight: normal" id="data_entry_services" title=""><?php echo lang("lbl_pricingsec_pro15"); ?></a></li>
                       
                    </ul>
                    <a href="contacte2a6.html?plan=enterprise" class="btn btn-lg btn-enterprise"><?php echo lang('lbl_pricingsec_proepbutton'); ?></a>
                </div>
          </div>
        </div>
        
        <div class="link_to_oem">
            <div class="image_show_case">
              <img src="<?php echo base_url();?>assets/uv-landing/images/OEM_Pricing_Illustration.png" alt="UVSchools School management software OEM Partner illustration">
              </img>
            </div>
            <div class="oem_desc">
                <?php echo lang('lbl_pricingsec_rebrand1'); ?> <br />
                <?php echo lang('lbl_pricingsec_rebrand2'); ?> <b><?php echo lang('lbl_pricingsec_rebrand3'); ?></b> <?php echo lang('lbl_pricingsec_rebrand6'); ?> <br />
                <?php echo lang('lbl_pricingsec_rebrand4'); ?>
            </div>
            <div class="oem_link">
                <a href="oem_partner.html" class="know-more-button know-more-button-pricing_plan"><?php echo lang('lbl_pricingsec_rebrand5'); ?></a>
            </div>
        </div>
      </div>
</div>
</div>
<!-- End Pricing Module -->

<div style="clear:both"></div>

<!-- Start Testimonial Block -->
<div class="container" id="#uv_Test_section" style="padding-bottom: 20px;">
  <h2 style="text-align:center; padding: 20px;"><?php echo lang('lbl_clients'); ?></h2>
   <section class="customer-logos slider">
      <div class="slide"><img src="<?php echo base_url();?>assets/uv-landing/images/luxury-letter-e-logo-design_1017-8903.jpg"></div>
      <div class="slide"><img src="<?php echo base_url();?>assets/uv-landing/images/logo_footer.png"></div>
      <div class="slide"><img src="<?php echo base_url();?>assets/uv-landing/images/3d-box-logo_1103-876.jpg"></div>
      <div class="slide"><img src="<?php echo base_url();?>assets/uv-landing/images/blue-tech-logo_1103-822.jpg"></div>
      <div class="slide"><img src="<?php echo base_url();?>assets/uv-landing/images/colors-curl-logo-template_23-2147536125.jpg"></div>
      <div class="slide"><img src="<?php echo base_url();?>assets/uv-landing/images/abstract-cross-logo_23-2147536124.jpg"></div>
      <div class="slide"><img src="<?php echo base_url();?>assets/uv-landing/images/football-logo-background_1195-244.jpg"></div>
      <div class="slide"><img src="<?php echo base_url();?>assets/uv-landing/images/background-of-spots-halftone_1035-3847.jpg"></div>
      <div class="slide"><img src="<?php echo base_url();?>assets/uv-landing/images/retro-label-on-rustic-background_82147503374.jpg"></div>
   </section>
</div>

<!-- End Testimonial Block -->

<!-- Start Footer Uper -->

<div class="page-block page page2" id="page-block-vlbkit82f1b81tt9">
  <div class="color-overlay"></div>
    <div class="border-holder">
      <div class="block-inner">
        <div class="page-element widget-container page-element-type-headline widget-headline"
          id="element-45" >
          <div class="contents">
            <h1>
              <p style="text-align: center;  "><?php echo lang('lbl_mapavail_text1'); ?></p>
            </h1>
          </div>
        </div>
        <div class="page-element widget-container page-element-type-text widget-text "
          id="element-70" >
          <div class="contents">
            <p style="text-align: center;  "><?php echo lang('lbl_mapavail_text2'); ?></p>
              <p style="text-align: center;  "><br></p>
          </div>
        </div>
        <div class="page-element widget-container page-element-type-image widget-image "
          id="element-164" >
          <div class="contents">
            <img src="<?php echo base_url();?>assets/uv-landing/images/1578562599-21403356-45x55-if-android-16085012x.png" style="margin-top: 0px;" alt="" />
          </div>
        </div>
        <div class="page-element widget-container page-element-type-image widget-image " id="element-191" >
          <div class="contents">
            <img src="<?php echo base_url();?>assets/uv-landing/images/1578562600-21403361-47x54-if-apple-2917272x.png" style="margin-top: 0px;" alt="" />
          </div>
        </div>
        <div class="page-element widget-container page-element-type-image widget-image " id="element-256" >
          <div class="contents">
            <a href="#" class="url-link" style="width: 100%; height: 100%;" target="_blank">
              <img src="<?php echo base_url();?>assets/uv-landing/images/1578562630-27231362-225x105-unnamed.png"
                style="margin-top: 0px;" alt="" />
            </a>
          </div>
        </div>
        <div class="page-element widget-container page-element-type-image widget-image "
          id="element-257" >
          <div class="contents">
            <a href="#" class="url-link" style="width: 100%; height: 100%;" target="_blank">
              <img src="<?php echo base_url();?>assets/uv-landing/images/1578562631-27231352-225x105-unnamed-1.png"
                  style="margin-top: 0px;" alt="" />
            </a>
          </div>
        </div>
      </div>
    </div>
</div>

<div class="page-block page page2" id="page-block-07z4naotfp5sif6r">
  <div class="color-overlay"></div>
    <div class="border-holder">
      <div class="block-inner">
        <div class="page-element widget-container page-element-type-headline widget-headline "
          id="element-78" >
            <div class="contents">
              <h2>
                <p style="text-align: center; color: #24135a;"><?php echo lang('lbl_mapavail_text3'); ?></p>
                <p style="text-align: center;  "><br></p>
              </h2>
            </div>
        </div>

        <div class="page-element widget-container page-element-type-text widget-text "
          id="element-79" >
          <div class="contents">
            <p style="text-align: center;  "><?php echo lang('lbl_mapavail_text4'); ?><br></p>
              <p style="text-align: center;  "><br></p>
          </div>
        </div>
        <div class="page-element widget-container page-element-type-image widget-image "
          id="element-100" >
            <style type="text/css">

              #element-100 .cropped {
                background-image: url('<?php echo base_url();?>assets/uv-landing/images/4087942-0-DARKERSHADOW.png');
                background-position: 0px -335px;
                width: 291px;
                height: 40px;
                background-size: 291px 375px;
              }

              @media
                    screen and (max-width: 620px),
                    screen and (max-width: 959px) and (-webkit-min-device-pixel-ratio: 1.5) {
                #element-100 .cropped {
                  height: 40px;
                  width: 291px;
                  background-size: 291px 375px;
                  background-position: 0px -335px;
                }
              }
            </style>
              <div class="contents">
                <div class='cropped' alt=""></div>
              </div>
        </div>
        <div class="page-element widget-container page-element-type-button widget-button " id="element-101" >
          <style type="text/css">
                #element-101 .dynamic-button {
                background-color: #24135a;
                color: #ffffff;
              }

              #element-101 .dynamic-button:active {
              }

              #element-101 .dynamic-button:hover {
                color: #ffffff;
                background-color: #c0392b;
              }

              #element-101 .dynamic-button, #element-101 .image-button {
                width: 285px;
                height: 59px;
                z-index: 13;
                line-height: 24px;
                font-size: 20px;
                font-weight: 700;
                font-family: Open Sans;
                border-radius: 40px;
              }

              @media
                  screen and ( max-width: 620px ),
                  screen and ( max-width: 999px ) and ( -webkit-min-device-pixel-ratio: 1.5 ) and ( max-device-width: 1280px ) and ( max-device-height: 720px ),
                  screen and ( max-width: 999px ) and ( -webkit-min-device-pixel-ratio: 1.5 ) and ( max-device-width: 1000px ) {
                body:not( .tablet ) #page-block-07z4naotfp5sif6r #element-101 .dynamic-button, body:not( .tablet ) #page-block-07z4naotfp5sif6r #element-101 .image-button {
                  width: 365px;
                  height: 69px;
                  z-index: 13;
                  line-height: 69px;
                  font-size: 20px;
                }
              }
  
        </style>
          <div class="conversion_wrapper">
            <a href="#" class="url-link" style="width: 285px; height: 59px;" target="_blank" data-wid="101">
              <div class="btn submit-button button_submit dynamic-button"><?php echo lang('lbl_mapsection_button'); ?></div>
            </a>
          </div>
        </div>
      </div>
    </div>
</div>
<!-- end footer Uper -->

<!-- Start Footer Part -->
<section style="background-color: #24135a;">
  <div class="section group red_section align_center">
      <h3 style="padding: 45px 0px 0px 0;"><?php echo lang('lbl_mapavail_text5'); ?></h3>
      <h4>UVSchools</h4>
      <h5>1.1</h5>
      <div class="new_fedena_release" style="width: 100%;">
          <h6 style="margin-left: 60px;"><?php echo lang('lbl_mapavail_text6'); ?></h6>
          <h6 style="margin-right: 60px;"><?php echo lang('lbl_mapavail_text7'); ?></h6>
      </div>
      <a href="demo.html" class="btn btn-large btn-white" style="height: 30px;width: 200px;padding-top: 25px;margin-bottom: 50px;"><span><?php echo lang('lbl_mapavail_text8'); ?></span></a>
      <a href="#" target="_blank" class="btn btn-large btn-transparent" style="height: 30px;width: 200px;padding-top: 25px;margin-bottom: 50px;"><span><?php echo lang('lbl_mapavail_text9'); ?></span></a>
  </div>
</section>
<!-- Start Contact US BLOCK -->
<div class="container" id="uv_contact_section">
  <div class="row " id="contact-us">
    <div class="col-md-12">
      <br><br>
        <h1 class="box-title m-b-0 text-center">CONTACT US</h1>
      <br><br>
        <div class="row">
          <div class="col-md-3" style="border-left: solid;margin-left: 45px;">
            <h3 style="line-height: 30px;font-size: 24px;">United Vision - Sudan</h3>
              <p style="line-height: 1.6; margin-top: 0;margin-bottom: 1rem;">  
                <a>Flat # 51 – 5th floor<br>
                Al-Malika Tower<br>
                Bashir Elnefeidi Street,<br>
                Khartoum, Sudan<br><br> 
                </a>
               <i class="fa fa-whatsapp" aria-hidden="true"></i>&nbsp;&nbsp;Whatsapp: 
               <a href="#">+92 315 4071 704</a><br>
               <i class="fa fa-phone" aria-hidden="true"></i>&nbsp;&nbsp;Phone:
               <a href="#">+92 315 4071 704</a><br>
               <i class="fa fa-envelope-o" aria-hidden="true"></i>&nbsp;&nbsp;Email:
               <a href="#">Info@united-vision.net</a>
              </p>
           </div>
           <div class="col-md-3" style="border-left: solid;margin-left: 45px;">
            <h3 style="line-height: 30px;font-size: 24px;">United Vision - Libya </h3>
              <p style="line-height: 1.6; margin-top: 0;margin-bottom: 1rem;">  
                <a>Sofyan Althory street<br>
                Zawiyat Al Dahmani<br>
                Tripoli, Libya<br><br> 
                </a>  
               
               <i class="fa fa-whatsapp" aria-hidden="true"></i>&nbsp;&nbsp;Whatsapp: 
               <a href="#">+92 315 4071 704</a><br>
               <i class="fa fa-phone" aria-hidden="true"></i>&nbsp;&nbsp;Phone:
               <a href="#">+218 92 0200 211</a><br>
               <i class="fa fa-envelope-o" aria-hidden="true"></i>&nbsp;&nbsp;Email:
               <a href="#">Info@united-vision.net</a>
              </p>
           </div>
          <div class="col-md-3" style="border-left: solid;margin-left: 45px;">
            <h3 style="line-height: 30px;font-size: 24px;">United Vision - Pakistan</h3>
              <p style="line-height: 1.6; margin-top: 0;margin-bottom: 1rem;">  
               <a>150-J1<br>
               Johar Town,<br>
               Lahore, Pakistan<br><br> 
               </a>
			   <i class="fa fa-whatsapp" aria-hidden="true"></i>&nbsp;&nbsp;Whatsapp: 
               <a href="#">+92 315 4071 704</a><br>
               <i class="fa fa-phone" aria-hidden="true"></i>&nbsp;&nbsp;Phone:
               <a href="#">+218 92 0200 211</a><br>
               <i class="fa fa-envelope-o" aria-hidden="true"></i>&nbsp;&nbsp;Email:
               <a href="#">Info@united-vision.net</a>
              </p>
          </div>

        </div>
          <br><br><br>
    </div>
  </div>
</div>
<!-- End Contact US BLOCK -->
<div id="footer">
            <div class="footer" style="width: 82%;">
                <div class="section group">
                    <div class="col span_1_of_6">
                        <h6><?php echo lang('lbl_footersect_li12'); ?></h6>
                        <a href="http://united-vision.net/united-vision/about/" style="color: #fff;"><?php echo lang('lbl_footersect_li13'); ?></a>
                        <a href="http://united-vision.net/united-vision/about/" style="color: #fff;"><?php echo lang('lbl_footersect_li14'); ?></a>
                        <a href="http://united-vision.net/united-vision/news/" target="_blank" style="color: #fff;"><?php echo lang('lbl_footersect_li15'); ?></a>
                        <a href="#" style="color: #fff;"><?php echo lang('lbl_footersect_li16'); ?></a>
                        
                    </div>
                    <div class="col span_1_of_6">
                        <h6><?php echo lang('lbl_footersect_li9'); ?></h6>
                        <a href="#uv_contact_section" style="color: #fff;"><?php echo lang('lbl_footersect_li10'); ?></a>
                        <a href="http://united-vision.net/united-vision/services/web-development/" style="color: #fff;"><?php echo lang('lbl_footersect_li11'); ?></a>
                    </div>
                    
                    <div class="col-md-3" style="margin-top: 12px;">
                        <h6><?php echo lang('lbl_footersect_li1'); ?></h6>
                        <p style="color: #fff; text-align: justify; font-family: 'IBM Plex Sans',-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Noto Sans,Ubuntu,Droid Sans,Helvetica Neue,sans-serif;font-size: 15px;font-weight: 400;line-height: 22px;color: #fff;overflow-x: hidden;-webkit-font-smoothing: antialiased;">We are offering you to become part of next generation schooling by providing a soft & simple management system, the perfect solution of all needs and requirements of your organization.</p>
                    </div>
                    <div class="col span_2_of_6">
                        <div class="contact-info">
                            <div class="address">
                              <p>United Vsion Pvt. Ltd.,</p>
                              <p>150-F , Block J1,</p>
                              <p>Johar Town, </p>
                              <p>Lahore ,</p>
                              <p>Pakistan</p>
                            </div>
                            <div class="mob-no">+92 423 4004 805</div>
                            <div class="contact-button" style="width:80%; padding-left: 20px;"><a href="#uv_contact_section">Contact Us</a></div>
                        </div>

                    </div>

                </div>
            </div>
            <div id="social_icons">
                <p>&copy;2020 <a href="#">United Vision Pvt. Ltd.</a>. All Rights Reserved. <a href="#">Terms</a> & <a href="#">Privacy</a></p>
                <div><ul class="social_icons">
                <li class="fb"><a href="#" target="_blank"></a></li>
                <li class="tw"><a href="#" target="_blank"></a></li>
                <!--<li class="gp"><a href="https://plus.google.com/109634566879572883664/" target="_blank"></a></li>-->
                <li class="in"><a href="#" target="_blank"></a></li>
                <li class="yt"><a href="#" target="_blank"></a></li>
                    </ul></div>
            </div>
</div>

<!-- End Footer Part -->
</div>

<script>
    $(function () {
      $('a[href*=#]:not([href=#])').click(function () {
          if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {

              var target = $(this.hash);
              target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
              if (target.length) {
                  $('html,body').animate({
                      scrollTop: target.offset().top - 75
                  }, 1000);
                  return false;
              }
          }
      });
  });
  if ("false") {
      $(".testimonial-reference").addClass("indian_testimonial_ref");
      $(".testimonial-plan").addClass("indian_testimonial_plan");
  }

  is_bangalore = "false"
  if (is_bangalore == 'false'){
      $('.pro').removeClass('bangalore_pro')
      $('.pricing').removeClass('bangalore_updation')
      $('.pro-plus').removeClass('bangalore_pro_plus')
      $('.prime').removeClass('bangalore_prime')

  }

</script>
<script type="text/javascript">
    $(function() {
  $(document).scroll(function() {
    var $nav = $(".large");
    $nav.toggleClass('scrolled', $(this).scrollTop() > $nav.height());
  });
});
</script>
<script type="text/javascript">
    $(document).ready(function(){
    $('.customer-logos').slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        autoplay: true,
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
</body>

</html>