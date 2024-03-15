<?php include (APPPATH . 'views/landing_page/landing_header.php'); ?>
<div class="container">

    <h1 style="color:#FD9675">Knowledge Base</h1>
    <br>

    <?php
    foreach ($categories as $category) {

        if (count($category) > 0) {

            echo "<h3>" . $category[0]['name'] . "</h3>";

            foreach ($category as $article) {
                ?>

                <h5><a style='color:#00cccc' href="<?php echo base_url(); ?>knowledge_base/<?= $article['slug'] ?>" ><?= $article['title'] ?></a></h5>


                <?php
            }
        }

        echo "<br>";
    }
    ?>
</div>
    <?php include (APPPATH . 'views/landing_page/landing_footer.php'); ?>

