<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
​
<div id="page-wrapper">
    <div class="container-fluid" style="padding-bottom:0;">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?= lang("lbl_announcements"); ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="dashboard/"><?= lang("lbl_dashboard"); ?></a></li>
                    <li class="active"><?= lang("lbl_announcements"); ?></li>
                </ol>
            </div>
        </div>
​
        <div class="hint"><?php echo lang('help_announcements'); ?></div>
    </div>
​
    <!--<div class="container-fluid row">
        <div class="col-sm-12">
            <div class="white-box well well-sm">
                <div class="row form-material">
                    <div class="col-md-5">
                        <div class="form-group">
                            <select class="form-control">
                                <option>Search by Date</option>
                            </select>
                        </div>
                    </div>
          
                    <div class="col-md-5">
                        <div class="form-group">
                            <select class="form-control">
                                <option>Search by Title</option>
                            </select>
                        </div>
                    </div>
                    
​
                    <div class="col-md-2">
		                <div class="form-actions">
		                    <button type="button" class="btn btn-primary btn-sm"><?php echo lang('search') ?></button>
		                </div>
		            </div>
​
                </div>
            </div>
        </div>
    </div>-->
    
	<div class="container-fluid">
	    <div class="row">
	        <div class="col-sm-12">
	            <div class="white-box">
				    <?= $announcements; ?>
				</div>
			</div>
		</div>
	</div>
</div>
​
<?php include(APPPATH . "views/inc/footer.php"); ?>
