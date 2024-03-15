    <br><br>
    <div class="col-md-12 bg-inverse "style="position: absolute; left: 0;bottom: 0; width: 100%;">
    <br><br>
    <div class="container">
        <span>Copyright 2017. All Rights Reserved by <a class="text-white" href="http://united-vision.net/">United Vision</a></span>
        <span class="pull-right">Design & Developed by <a class="text-white" href="http://united-vision.net/">United Vision</a></span><br><br></div>
                        
    </div>
</div>


    <!-- /#wrapper -->
    <!-- jQuery -->
    <script src="<?= base_url().'assets/' ?>plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <!-- Bootstrap Core JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
    <script src="<?= base_url().'assets/landingpage/' ?>bootstrap/dist/js/tether.min.js"></script>
    <script src="<?= base_url().'assets/landingpage/' ?>bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?= base_url().'assets/' ?>plugins/bower_components/bootstrap-extension/js/bootstrap-extension.min.js"></script>
    <!-- jQuery for carousel -->
    <script src="<?= base_url().'assets/' ?>plugins/bower_components/owl.carousel/owl.carousel.min.js"></script>
    <script src="<?= base_url().'assets/landingpage/' ?>js/custom.js"></script>
    
    <!-- jQuery for typing -->
    <script src="<?= base_url().'assets/' ?>plugins/bower_components/typed.js-master/dist/typed.min.js"></script>
    <script>
    $(function() {
        $(".banner-small-text").typed({
            strings: ['<?php echo lang('landing_txt_system_descrition'); ?>'],
            typeSpeed: 50,
            loop: false
        });
    });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            if($(window).width()<=768){
                $('#my_nav').removeClass('pull-right');
            }
            // if($(window).width()<=600){
            //     $('#SchLand').css('display', 'none');
            // }
            
        });
       
                            </script>
                            <script type="text/javascript">
                                var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
                                (function(){
                                    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
                                    s1.async=true;
                                    s1.src='https://embed.tawk.to/5f29292d2da87279037e4309/default';
                                    s1.charset='UTF-8';
                                    s1.setAttribute('crossorigin','*');
                                    s0.parentNode.insertBefore(s1,s0);
                                })();
                            </script>
</body>

</html>

