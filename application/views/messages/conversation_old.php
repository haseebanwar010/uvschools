<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title"><?php echo lang('crumb_view_message');?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="#"><?php echo lang('lbl_dashboard');?></a></li>
                    <li><a href="#"><?php echo lang('crumb_messages');?></a>
                    </li>
                    <li class="active"><?php echo lang('crumb_view_message');?></li>

                </ol>
            </div>
        </div>
        <!-- /.row -->
        <!-- Page Content -->

        <!-- row -->

        <div class="row">
            <!-- Left sidebar -->
            <div class="col-md-9" ng-controller="conversation" ng-init="conver.id='<?php echo $con_info->id;?>';init();">
                <div class="white-box">

                    <h4 class="font-bold m-t-0"><?php echo $con_info->subject ; ?></h4>
                    <hr>
                    <div class="white-box" ng-repeat="mess in messages">

                        <a class="pull-left" href="#"> <img class="media-object thumb-md img-circle" ng-src="<?php echo base_url() ?>uploads/user/{{mess.avatar}}" alt=""> </a>
                        <div class="media-body"> <span class="media-meta pull-right">{{mess.created_at}}</span>
                            <h4 class="text-danger p-l-10">{{mess.name}}</h4>
                        </div><br>

                        <div ng-bind-html="mess.text"></div>

                        <div ng-show="mess.attachments!=''">   
                         <h4> <i class="fa fa-paperclip m-r-10 m-b-10"></i> <?php echo lang('lbl_attachments') ?> </h4>
                         <div class="row">
                            <ul>
                                <div ng-repeat="attach in mess.attachments">
                                <li ng-if="['jpg','jpeg','png','gif'].includes(attach.split('.').pop().toLowerCase())"><a href="<?php echo base_url() ?>uploads/attachment/{{attach}}" data-lightbox="image-attach-{{$parent.$parent.$index}}">{{attach}}</a></li>
                                <li ng-if="!['jpg','jpeg','png','gif'].includes(attach.split('.').pop().toLowerCase())"><a href="<?php echo base_url() ?>uploads/attachment/{{attach}}" download>{{attach}}</a></li>
                            </div>
                            </ul>   
                        </div>
                    </div>


                    <hr>
                </div>






                <div class="white-box" id="compose">
                   <div class="row">

                       <div class="col-md-1">
                           <img class="media-object thumb-md img-circle" src="<?php echo base_url() ?>uploads/user/<?php echo $user_image; ?>" alt=""></div>

                           <div class="col-md-11">

                               <div class="form-group">
                                <textarea class="textarea_editor form-control" rows="10" placeholder="Enter text ..."></textarea>
                            </div>
                            <h4><i class="ti-link"></i> <?php echo lang('lbl_attachment') ?></h4>
                            <div class="dropzone" id="my-awesome-dropzone" dropzone="dropzoneConfig">

                            </div>
                            <hr>
                            <div class="alert" id="message_alert" style="display: none"></div>
                            <div class="pull-right">
                                <button type="submit" class="btn btn-primary" id="saveButton"><i class="fa fa-envelope-o"></i> <?php echo lang('btn_send');?></button>
                                <button class="btn btn-default" id="discard"><i class="fa fa-times"></i> <?php echo lang('btn_cancel');?> </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="white-box">
                <label><?php echo lang('lbl_conversation_started');?></label>
                <p><?php echo $con_info->name ;?></p>
                <label><?php echo lang('lbl_participants');?></label>
                <?php foreach($participants as $part){ ?>
                <p><?php echo $part->name ?></p>
                <?php } ?>
                <label><?php echo lang('lbl_started_time');?></label>
                <p><?php echo $con_info->created_at ;?></p>

            </div>
        </div>
    </div>
    <!-- /.row -->

    <!--./row-->   
    <!--page content end-->
</div>
</div>
<!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>

<script type="text/javascript">
    $(document).ready(function(){

        $('.textarea_editor').wysihtml5();

        $('#discard').click(function(){
            Dropzone.forElement("#my-awesome-dropzone").removeAllFiles(true);
            $('.textarea_editor').data("wysihtml5").editor.clear();
            $('#message_alert').hide();

        });


    });
</script>



