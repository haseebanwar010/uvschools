<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Fee Settings</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="#">Dashboard</a></li>
                    <li><a href="#">Settings</a></li>
                    <li class="active">Fee Settings</li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <!-- Page Content start here -->
        <!--.row-->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">

                    <div class="panel-wrapper collapse in" aria-expanded="true">
                        <div class="panel-body">

                            <ul class="nav customtab nav-tabs" role="tablist">
                                <li role="presentation" class="nav-item"><a href="#feetype" class="nav-link active"
                                                                            aria-controls="profile" role="tab"
                                                                            data-toggle="tab"
                                                                            aria-expanded="true"><span
                                            class="visible-xs"><i class="fa fa-bars"></i></span><span
                                            class="hidden-xs">Fee Type</span></a>
                                </li>
                                <li role="presentation" class="nav-item"><a href="#feediscount" class="nav-link"
                                                                            aria-controls="profile" role="tab"
                                                                            data-toggle="tab"
                                                                            aria-expanded="true"><span
                                            class="visible-xs"><i class="fa fa-percent"></i></span><span
                                            class="hidden-xs">Fee Discount</span></a>
                                </li>
                            </ul>


                            <!--tab content start here-->
                            <div class="tab-content">

                                <!-- Fee type -->
                                <div class="tab-pane active" id="feetype" ng-controller="feetypeConroller" ng-init="initClasses()">
                                    <form class="form-material" name="feetypeFilerForm" ng-submit="onSubmitFetchClassFeeTypes(feetypeFilerForm.$valid)" novalidate="">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Classes</label>
                                                    <select class="form-control" required="" ng-model="fModel.class_id">
                                                        <option value="">---Select a class---</option>
                                                        <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-info">Search</button>
                                            </div>
                                        </div>
                                    </form>
                                    <br/>

                                    <div class="col-md-12" id="feetypecontainer" style="padding:0;">
                                        <div ng-if="classFeetypes.length>0">
                                            <div class="table-responsive">
                                                <table id="myTable" class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Name</th>
                                                            <th>Due date</th>
                                                            <th>Amount</th>
                                                            <th>Description</th>
                                                            <th class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr ng-repeat="(key,cft) in classFeetypes">
                                                            <td>{{ key+1 }}</td>
                                                            <td>{{ cft.name }}</td>
                                                            <td>{{ cft.due_date }}</td>
                                                            <td>{{ cft.amount }}</td>
                                                            <td>{{ cft.description }}</td>
                                                            <td class="text-center ">
                                                                <button type="button" class="btn btn-info btn-circle" data-toggle="modal" ng-click="setEditValues(cft)" data-target="#eidtFeetypeModal"><i class="fa fa-pencil"></i></button>
                                                                <button type="button" class="btn btn-danger btn-circle" ng-click="showConfirmationAlert(cft.id)"><i class="fa  fa-trash-o"></i></button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="col-md-12" style="padding: 0;">
                                                <button type="button" data-toggle="modal" data-target="#addfeetype" class="fcbtn btn btn-outline btn-info btn-1e"><i class="fa fa-plus "></i>Add Fee type</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div ng-if="classFeetypes.length===0">
                                        <span class="text-danger">No record found</span><br/><br/>
                                        <button type="button" data-toggle="modal" data-target="#addfeetype" class="fcbtn btn btn-outline btn-info btn-1e"><i class="fa fa-plus "></i>Add Fee type</button>
                                    </div>
                                    <!--/row-->
                                    <!-- Add new Modal Content -->
                                    <div class="modal fade" id="addfeetype" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content" id="addfeetype-content">
                                                <div class="panel panel-info">
                                                    <div class="panel-heading">Add Fee Type</div>
                                                    <div class="panel-body">
                                                        <form name="addFeetypeForm" ng-submit="saveFeetype(addFeetypeForm.$valid)" novalidate="" class="form-material ">
                                                            <div class="form-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Name</label>
                                                                            <input type="text" required="" ng-model="adModel.name" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Amount</label>
                                                                            <input type="number" required="" ng-model="adModel.amount" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <!--/span-->
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Due date</label>
                                                                            <input type="text" required="" ng-model="adModel.due_date" placeholder="mm/dd/yyyy" class="form-control mydatepicker-autoclose">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Description</label>
                                                                            <textarea  ng-model="adModel.description" class="form-control"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                            </div>
                                                            <div class="row pull-right">
                                                                <div style="margin-right: 8px">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                </div>
                                                                <div>
                                                                    <button type="submit" class="btn btn-info">Save</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/Add new end here-->

                                    <!-- Modal Content -->
                                    <div class="modal fade" id="eidtFeetypeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content" id="editfeetype-contents">
                                                <div class="panel panel-info">
                                                    <div class="panel-heading">Edit Fee Type</div>
                                                    <div class="panel-body">
                                                        <form name="editFeetypeForm" ng-submit="updateFeetype(editFeetypeForm.$valid)" novalidate="" class="form-material ">
                                                            <div class="form-body">
                                                                <input type="hidden" name="id" ng-value="editModel.id" />
                                                                <input type="hidden" name="date" ng-value="editModel.date" />
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Name</label>
                                                                            <input type="text" name="name" ng-value="editModel.name" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Amount</label>
                                                                            <input type="number" name="amount" ng-value="editModel.amount" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Due date</label>
                                                                            <input type="text" name="due_date" ng-value="editModel.due_date" placeholder="mm/dd/yyyy" class="form-control mydatepicker-autoclose">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Description</label>
                                                                            <textarea name="description" ng-value="editModel.description" class="form-control"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--/span-->

                                                            </div>
                                                            <div class="row pull-right">
                                                                <div style="margin-right: 8px">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close </button>
                                                                </div>
                                                                <div>
                                                                    <button type="submit" class="btn btn-info">Update </button>
                                                                </div>
                                                            </div>

                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                    <!--/Edit Modal end here-->

                                </div>
                                <!-- /Fee type-->

                                <!-- fee discount -->
                                <div class="tab-pane" id="feediscount">
                                    <?php echo $discount; ?>
                                </div>
                                <!-- /fee discount -->
                            </div>
                            <!-- /Fee Setup -->
                        </div>
                    </div>
                    <!--tab content end here-->
                </div>
                <!--/panel body-->
            </div>
            <!--/panel wrapper-->
        </div>
        <!--/panel-->
    </div>
</div>
<!--./row-->
<!--page content end here-->
</div>
<?php include(APPPATH . "views/inc/footer.php"); ?>
