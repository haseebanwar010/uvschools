<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <link rel="icon" href="<?= base_url().'assets/landingpage/' ?>images/favicon.jpg">
    <title>Google Play</title>
<style>
  html,body{
    overflow-x: hidden;
}
.container{
	
	margin-top: 80px;
}
body{
	background-color: #e9ecef !important;
}
.jumbotron{
	background-color: white !important;
	height: auto !important;
	width: auto !important;
}

.holder {
	width: 900px;
	margin: 0 auto;
  }
  .box-set__knight {
	width: 10px;
	padding: 0 10px 0 0;
	float: left;
  }
  .box-set__knight-image {
	  height: 200px;
	width: 200px;
  }
  .box-set__header {

	color: black;
	padding-top: 50px;
    padding-left: 200px;
  }
  .box-set__header h1 {
	font-size: 46px;
	font-weight: bold;
	font-family: "SF Pro Display","SF Pro Icons","Apple WebExp Icons Custom","Helvetica Neue",Helvetica,Arial,sans-serif;

  }
  .col-h2 {
	font-size: 16px;
	display: block;
	
	color: green;  font-weight: bolder; margin-top: 5px; margin-left: 10px;
  }

  .button{
	background-color: green;margin-left: 50px; font-size: 16px; color: white; padding: 6px; border-radius: 5px; width: 100px;
  }

  .rating{
	  margin-top: -5px;
	  margin-left: -60px;
  }


  .scrolling-wrapper {
	width: 980px;
	display: flex;
	margin-left: 40px;
  flex-wrap: nowrap;
  overflow-x: auto;
}
	.card {
		width: 230px;
		height: 500px;
		margin: 5px;
		border-radius: 25px !important;
		flex: 0 0 auto;
	}
  
.headings{
	margin-bottom: 17px;
    -webkit-flex-shrink: 1;
    -ms-flex-negative: 1;
    flex-shrink: 1;
    font-size: 20px;
    line-height: 1.2;
    font-weight: 700;
	letter-spacing: .024em;
	margin-left: 40px;
    font-family: "SF Pro Display","SF Pro Icons","Apple WebExp Icons Custom","Helvetica Neue",Helvetica,Arial,sans-serif;

}
.detail{
	width: 980px; margin-left: 40px;
}
.review{
	width: 500px ;
	margin-left: 40px;
}
.column {
	float: left;
	width: 33.33%;
	padding: 10px;
	 /* Should be removed. Only for demonstration */
  }
  
  /* Clear floats after the columns */
  .row:after {
	content: "";
	display: table;
	clear: both;
  }

  @media (max-width: 770px){
	html,body{
		overflow-x: hidden;
	}
	.container{
	
		margin-top: 40px;
	}

	  .jumbotron{
		  width: 340px !important;
		  margin-left: 10px !important;
	  }
	  .box-set__knight-image {
		height: 100px;
	  width: 100px;
	}
	.box-set__header h1{
		font-size: 25px;
		margin-left: -100px;
		margin-top: -25px;
	}
	  .col-h2 {
		font-size: 14px !important;
		margin-left: 16px;
		margin-top: -10px;
		display: block;
	  }
	  .button{
		  margin-left: -80px;
		  width: 300px;
	  }
	
	.scrolling-wrapper{
		width: 300px !important;
		margin-left: 5px;
		overflow-x: scroll !important;
	}
     .card{
		 width: 150px;
		 height: 300px;
	 }
	 .headings{
		 margin-left: 7px;
	 }
	 .detail{
		width: 300px; margin-left: 7px;
	 }
	 .review img{
         width: 300px !important;
	 }
	 .review{
		 margin-left: 7px;
	 }
  }
  @media screen and (max-width: 600px) {
	.column {
	  width: 100%;
	  margin-left: -110px;
	}
  }

</style>
    
</head>
<body>

    <div class="container jumbotron">

      <div class="holder">
        <div class="box-set">
          <div class="box-set__knight"><img class="box-set__knight-image" src="<?= base_url().'assets/landingpage/' ?>images/logo.png" alt="knight icon" /></div>
          <div class="box-set__header">
            <h1>UVSchools</h1>
      
            <div class="row">
              <div class="column" >
                <h2 class="col-h2">Education</h2>
               
              </div>
              <div class="column" >
                <img class="rating" src="<?= base_url().'assets/landingpage/' ?>images/rating.JPG" alt="">
              </div>
              <div class="column" >
				<a href="https://uvschools.com/testing/uvsapp/UVSchools.apk" target="_blank"><button class="button">Install</button></a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <br>
      <hr>
      <br>

      <h2 class="headings">Screenshots</h2>
      <div class="scrolling-wrapper">
        <img src="<?= base_url().'assets/landingpage/' ?>images/1.png" class="card" alt="">
        <img src="<?= base_url().'assets/landingpage/' ?>images/2.png" class="card" alt="">
        <img src="<?= base_url().'assets/landingpage/' ?>images/3.png" class="card" alt="">
        <img src="<?= base_url().'assets/landingpage/' ?>images/4.png" class="card" alt="">
        <img src="<?= base_url().'assets/landingpage/' ?>images/5.png" class="card" alt="">
        <img src="<?= base_url().'assets/landingpage/' ?>images/6.png" class="card" alt="">
        <img src="<?= base_url().'assets/landingpage/' ?>images/7.png" class="card" alt="">
        <img src="<?= base_url().'assets/landingpage/' ?>images/8.png" class="card" alt="">
        <img src="<?= base_url().'assets/landingpage/' ?>images/9.png" class="card" alt="">
        <img src="<?= base_url().'assets/landingpage/' ?>images/10.png" class="card" alt="">
     
      </div>
      <br>
      <hr>
      <br>
      <h2 class="headings">Description</h2>
      <div class="detail">
        <p>UV Schools is an integrated school management system which aims to bring much needed simplicity to Administration of an Enterprise especially in the field of Education. To achieve this UV Schools is a flexible and scalable educational management system where every user can discover and realize their potential to achieve overall development. UV Schools promises quick and secure flow of information and fast response times. We build, design, develop, and deliver UV Schools to help educational institutions make their work easier and simple.
        </p>
      </div>
      <br>
      <hr>
      <br>
      <div class="review">
        <img src="<?= base_url().'assets/landingpage/' ?>images/reviews.JPG" alt="">
      </div>
    </div>

</body>

</html>