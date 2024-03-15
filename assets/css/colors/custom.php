<?php header("Content-type: text/css; charset: UTF-8"); ?>
@import url(https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700);
/*Theme Colors*/
/*bootstrap Color*/
/*Normal Color*/
/*Border radius*/
/*Preloader*/
.preloader {
  width: 100%;
  height: 100%;
  top: 0px;
  position: fixed;
  z-index: 99999;
  background: #fff;
}
.preloader .cssload-speeding-wheel {
  position: absolute;
  top: calc(50% - 3.5px);
  left: calc(50% - 3.5px);
}
/* This is for popins font for firefox */
@font-face {
  font-family: 'Poppins';
  font-style: normal;
  font-weight: 400;
  src: url(https://fonts.gstatic.com/s/poppins/v1/2fCJtbhSlhNNa6S2xlh9GyEAvth_LlrfE80CYdSH47w.woff2) format('woff2');
  unicode-range: U+02BC, U+0900-097F, U+1CD0-1CF6, U+1CF8-1CF9, U+200B-200D, U+20A8, U+20B9, U+25CC, U+A830-A839, U+A8E0-A8FB;
}
/* This is for popins font for firefox */
@font-face {
  font-family: 'Poppins';
  font-style: normal;
  font-weight: 400;
  src: url(https://fonts.gstatic.com/s/poppins/v1/UGh2YG8gx86rRGiAZYIbVyEAvth_LlrfE80CYdSH47w.woff2) format('woff2');
  unicode-range: U+0100-024F, U+1E00-1EFF, U+20A0-20AB, U+20AD-20CF, U+2C60-2C7F, U+A720-A7FF;
}
/* This is for popins font for firefox */
@font-face {
  font-family: 'Poppins';
  font-style: normal;
  font-weight: 400;
  src: url(https://fonts.gstatic.com/s/poppins/v1/yQWaOD4iNU5NTY0apN-qj_k_vArhqVIZ0nv9q090hN8.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
}
/*Just change your choise color here its theme Colors*/
body {
  background: #fff;
}
/*Top Header Part*/
.logo i {
  color: #ffffff;
}
.top-left-part .light-logo {
  display: inline-block;
}
.top-left-part .dark-logo {
  display: none;
}
.navbar-header {
  background: #<?php echo $_GET['color'] ?>;
}
.navbar-top-links > li > a {
  color: #ffffff;
}
/*Right panel*/
.right-sidebar .rpanel-title {
  background: #<?php echo $_GET['color'] ?>;
}
/*Bread Crumb*/
.bg-title .breadcrumb .active {
  color: #<?php echo $_GET['color'] ?>;
}
/*Sidebar*/
.sidebar {
  //background: #042954;
  background: #fff;
  box-shadow: 1px 0px 20px rgba(0, 0, 0, 0.08);
}
.sidebar .label-custom {
  background: #01c0c8;
}
#side-menu li a {
  color: #54667a;
}
#side-menu li a {
  color: #54667a;
  border-left: 0px solid #fff;
}
#side-menu > li > a:hover,
#side-menu > li > a:focus {
  background: rgba(0, 0, 0, 0.03);
}
#side-menu > li > a.active {
  border-left: 3px solid #<?php echo $_GET['color'] ?>;
  color: #<?php echo $_GET['color'] ?>;
  font-weight: 500;
}
#side-menu > li > a.active i {
  color: #<?php echo $_GET['color'] ?>;
}
#side-menu ul > li > a:hover {
  color: #<?php echo $_GET['color'] ?>;
}
#side-menu ul > li > a.active {
  color: #<?php echo $_GET['color'] ?>;
  font-weight: 500;
}
.sidebar #side-menu .user-pro .nav-second-level a:hover {
  color: #<?php echo $_GET['color'] ?>;
}
/*themecolor*/
.bg-theme {
  background-color: #707cd2 !important;
}
.bg-theme-dark {
  background-color: #<?php echo $_GET['color'] ?> !important;
}
/*Chat widget*/
.chat-list .odd .chat-text {
  background: #<?php echo $_GET['color'] ?>;
}
/*Button*/
.btn-custom {
  background: #<?php echo $_GET['color'] ?>;
  border: 1px solid #<?php echo $_GET['color'] ?>;
  color: #ffffff;
}
.btn-custom:hover {
  background: #<?php echo $_GET['color'] ?>;
  opacity: 0.8;
  color: #ffffff;
  border: 1px solid #<?php echo $_GET['color'] ?>;
}
/*Custom tab*/
.customtab li.active a,
.customtab li.active a:hover,
.customtab li.active a:focus {
  border-bottom: 2px solid #<?php echo $_GET['color'] ?>;
  color: #<?php echo $_GET['color'] ?>;
}
.tabs-vertical li.active a,
.tabs-vertical li.active a:hover,
.tabs-vertical li.active a:focus {
  background: #<?php echo $_GET['color'] ?>;
  border-right: 2px solid #<?php echo $_GET['color'] ?>;
}
/*Nav-pills*/
.nav-pills > li.active > a,
.nav-pills > li.active > a:focus,
.nav-pills > li.active > a:hover {
  background: #<?php echo $_GET['color'] ?>;
  color: #ffffff;
}
.btn-primary, .btn-primary.disabled {
    background: #<?php echo $_GET['color'] ?>;
    border: 1px solid #<?php echo $_GET['color'] ?>;
}

.bg-primary{
  background: #<?php echo $_GET['color'] ?> !important;
  background-color: #<?php echo $_GET['color'] ?> !important;
}

.panel-darkblue .panel-heading, .panel-primary .panel-heading {
    border-color: #<?php echo $_GET['color'] ?>;
    color: #fff;
    background-color: #<?php echo $_GET['color'] ?>;
}

.btn-outline-primary-custom{
  color: #<?php echo $_GET['color'] ?>;
  background-image: none;
  background-color: transparent;
  border-color: #<?php echo $_GET['color'] ?>;
}

.btn-outline-primary-custom:hover {
  color: #fff;
  background-color: #<?php echo $_GET['color'] ?>;
  border-color: #<?php echo $_GET['color'] ?>;
}