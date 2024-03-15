<?php include (APPPATH . 'views/landing_page/landing_header.php'); ?>
<div class="container">

    <h3 style='color:#00cccc'><?= $article['title'] ?></h3>
    <br>

    <p class="text-success"><?= $article['content'] ?></p>


    <input type="button" class="btn btn-info" value="Back" onclick="location.href = '<?php echo base_url() ?>knowledge_base';">
    <br><br>
</div>
<?php include (APPPATH . 'views/landing_page/landing_footer.php'); ?>

