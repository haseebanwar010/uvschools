<?php 
    $ci = & get_instance();
    if($ci->session->userdata("userdata")["role_id"] == ADMIN_ROLE_ID) {  
?>
    <div class="xcrud-top-actions">
        <a href="announcements" class="xcrud-button xcrud-orange btn btn-warning text-white"><i class="fa fa-reply"></i></a>
    </div>
<?php } ?>
<h3 class="text-danger"><i class="fa fa fa-bullhorn"></i> Important Announcement</h3>
<hr/>
<?php //echo "<pre>"; print_r($this->fields_output); echo "</pre>"; ?>
<?php foreach($this->fields_output as $data) { ?>
    
    <?php if($data["name"] == 'sh_announcements.title') { ?>
        <h4 class="text-success"><b><u><?php echo $data["value"]?></u></b></h4>
    <?php } ?>
   
    
    <?php if($data["name"] == 'sh_announcements.details') { ?>
        <p><?php echo $data["value"]?></p>
    <?php } ?>
    
    
    <?php if($data["name"] == 'sh_announcements.to_date') { ?>
        <h4>Date: <?php echo $data["value"]?> </h4>
    <?php } ?>

    <?php if($data["name"] == 'sh_announcements.img_or_document') { if(!is_null($data["value"]) || !empty($data["value"]) || $data["value"] != null) { ?>
        <h5 class="text-primary">Attachments</h5>
        <p>
            <?php
                if($data["value"]){
                    $val = explode(".",$data["value"]);
                    if($val[1] == "jpg" || $val[1] == "png" || $val == "jpeg" || $val == "gif"){
                        echo "<img style='width: 100%;' src='".base_url()."uploads/announcements/".$data["value"]."' />";
                    } else {
                        echo "<a href='uploads/announcements/".$data["value"]."'>".$data["value"]."</a>";
                    }
                } else {
                    echo "<span class='text-muted'>No attachment found.</span>";
                }
            ?>
        </p>
    <?php } } ?>
   
<?php } ?>