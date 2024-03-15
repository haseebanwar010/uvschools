<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<!-- Page Content -->
<div id="page-wrapper" ng-controller="licenserenewController">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Licenses renew</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="dashboard/">Dashboard</a></li>
                    <li class="active">Licenses renew</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <div class="modal fade" id="renew_edit_modal" tabindex="-1" role="dialog" aria-labelledby="renew_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form name="reneweditmodalform" ng-submit="updateLicense()" novalidate="" class="form-material">
                        <div class="form-body">
                            <div class="panel panel-primary">
                                <div class="panel-heading">License Edit Form</div>
                                <div class="panel-body">
                                    <div ng-show="alert.message">
                                        <div class="alert {{alert.status}}">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a> 
                                           {{ alert.message }}  
                                       </div>
                                    </div>
                                    <div class="form-group">
                                        <lable>End date</lable>
                                        <input class="form-control mydatepicker-autoclose" ng-value="selectedValues.end_date" type="text" name="end_date" />
                                    </div>
                                    <button type="submit" class="btn btn-primary">Renew License</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <div class="table-responsive">
                        <table class="myTable display nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <!--<th>Sr#.</th>-->
                                    <th>Logo</th>
                                    <th>School Name</th>
                                    <!--<th>School email</th>-->
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Type</th>
                                    <th>Expire Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <!--<th>Sr#.</th>-->
                                    <th>Logo</th>
                                    <th>School Name</th>
                                    <!--<th>School email</th>-->
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Type</th>
                                    <th>Expire Date</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php foreach ($schools as $sh) { ?>
                                <tr>
                                    <!--<td><?= $sh->sid; ?></td>-->
                                    <td><img src="uploads/logos/<?= $sh->logo; ?>" style="width: 70px;" class="img-responsive"/></td>
                                    <td><?= $sh->sname; ?></td>
                                    <!--<td><?= $sh->smail; ?></td>-->
                                    <td><?= to_html_date($sh->start_date); ?></td>
                                    <td><?= to_html_date($sh->end_date); ?></td>
                                    <td><?= $sh->type; ?></td>
                                    <td><strong><?php $rr = countExpiry($sh->end_date); echo $rr["years"]."y-". $rr["months"]."m-".$rr["days"]."d"?></strong></td>
                                    <td>
                                        <a type="button" href="javascript:void(0)" ng-click="setSelectedVlu('<?php echo $sh->sid; ?>','<?php echo to_html_date($sh->end_date); ?>','<?php echo $sh->id; ?>')" data-toggle="modal" data-target="#renew_edit_modal" class="btn btn-info btn-circle" ><i class="fa fa-pencil"></i></a>
                                        <a href="javascript:void(0)" value="<?php echo $sh->sid; ?>,licensesrenew/delete" class="sa-warning btn btn-danger btn-circle text-white"><i class="fa  fa-trash-o"></i></a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include(APPPATH . "views/inc/footer.php"); ?>