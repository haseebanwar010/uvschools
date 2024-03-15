<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<!-- Page Content -->
<div id="page-wrapper" ng-controller="employeeCtrl">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Profile</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="#">Dashboard</a></li>
                    <li class="active">Profile</li>

                </ol>
            </div>
        </div>
        
        <!-- /.row -->
        <div class="alert alert-dismissable {{alert.type}}" ng-if="alert.message"> 
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>     
            {{ alert.message }}
        </div>
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel-group wiz-aco form-material " id="accordion" role="tablist" aria-multiselectable="true">
                    <form name="empForm" id="empForm" ng-submit="onSubmit(empForm.$valid, image2.resized)" novalidate="">    
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Personal Info
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                <div class="panel-body">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Avatar</label>
                                                <input id="inputImage2" 
                                                   type="file" 
                                                   accept="image/*" 
                                                   image="image2" 
                                                   class="form-control"
                                                   resize-max-height="300"
                                                   resize-max-width="350"
                                                   style="font-size: 12px;"
                                                   resize-quality="0.7" />
                                                   <span>
                                                       <img style="width: auto;" ng-show="image2" ng-src="{{image2.resized.dataURL}}"/>
                                                   </span>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Gender</label>
                                                <select class="form-control" ng-model="formModel.gender" required="" name="gender">
                                                    <option value="">Please select a gender</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Name</label>
                                                <input type="text" id="user-name" name="name" class="form-control" ng-model="formModel.name" required="" placeholder="John doe">
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Marital Status</label>
                                                <select class="form-control" ng-model="formModel.marital_status" required="" name="marital_status">
                                                    <option value="">Please select your martial status</option>
                                                    <option value="married">Married</option>
                                                    <option value="single">UnMarried</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <!--/row-->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Contact</label>
                                                <input type="text" class="form-control" ng-model="formModel.contact" required="" name="contact">
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Language</label>
                                                <select class="form-control" ng-model="formModel.language" required="" name="language">
                                                    <option value="">Select a language</option>
                                                    <option value="english">English</option>
                                                    <option value="arabic">Arabic</option>
                                                    <option value="dutch">Dutch</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <!--/row-->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Email</label>
                                                <input type="email" id="user-email" name="email" ng-model="formModel.email" required="" class="form-control" placeholder="someone@xyz.com">
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Date Of birth</label>
                                                <input type="date" name="dob" ng-model="formModel.dob" required="" class="form-control"/>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Password</label>
                                                <input type="password" id="password" ng-model="formModel.password" required="" name="password" class="form-control">
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Nationality</label>
                                                <select class="form-control" ng-model="formModel.nationality" required="" name="nationality">
                                                    <option>Select your nationality</option>
                                                    <option value="british">British</option>
                                                    <option value="american">American</option>
                                                    <option value="arab">Arab</option>
                                                    <option value="indian">Indian</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!--/span-->

                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Identity Card Number</label>
                                                <input type="text" name="ic" ng-model="formModel.ic" required="" class="form-control" placeholder="----- --- -----">
                                            </div>
                                        </div>
                                        <!--/span-->

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Passport no</label>
                                                <input type="number" name="passport" ng-model="formModel.passport" required="" id="user-passport" class="form-control" placeholder="---- ---- ---- ----">
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                                <label>Street</label>
                                                <input type="text" name="street" ng-model="formModel.street" required="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Fax</label>
                                                <input type="text" id="lastName" ng-model="formModel.fax" required="" name="fax" class="form-control">
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Country</label>
                                                <select name="country" ng-model="formModel.country" required="" class="form-control">
                                                    <option>Select your country</option>
                                                    <?php foreach ($countries as $country) { ?>
                                                        <option value="<?php echo $country->country_name; ?>"><?php echo $country->country_name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>City</label>
                                                <select name="city" ng-model="formModel.city" required="" class="form-control">
                                                    <option>Select your city</option>
                                                    <option name="london">London</option>
                                                    <option name="paris">Paris</option>
                                                    <option name="newyork">New York</option>
                                                    <option name="london">Sydney</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <!--row-->
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingTwo">
                                    <h4 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Professional Info
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Category</label>
                                                    <select class="form-control" ng-model="formModel.category" ng-change="fetchDepartments()" required="" name="category">
                                                        <?php if(count($categories) > 0) {  foreach($categories as $cat) { ?>
                                                        <option value="<?php echo $cat->id; ?>"><?php echo $cat->category; ?></option>
                                                        <?php } } else {?>
                                                        <option>Not exists any category.</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Department</label>
                                                    <select class="form-control" ng-model="formModel.department" required="" name="department">
                                                        <option ng-repeat="dept in departments" value="{{dept.id}}">{{dept.name}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Joining Date</label>
                                                    <input type="Date" ng-model="formModel.join_date" required="" id="user-joining-date" name="join_date" class="form-control">
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Job title</label>
                                                    <input type="text" name="job_title" ng-model="formModel.job_title" required="" class="form-control" name="job_title">
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Qualification</label>
                                                    <select class="form-control" ng-model="formModel.qualification" required="" name="qualification">
                                                        <option>Select your qualification</option>
                                                        <option value="ma">MA</option>
                                                        <option value="ba">BA</option>
                                                        <option value="fa">FA</option>
                                                        <option value="matric">Matric</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--/span--> 
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Experience Duration</label>
                                                    <textarea class="form-control" ng-model="formModel.experience_duration" required="" name="experience_duration"> </textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">Experience Info</label>
                                                    <textarea class="form-control" ng-model="formModel.experience_info" required="" name="experience_info"> </textarea>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingThree">
                                        <h4 class="panel-title">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                Permissions
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                        <div class="panel-body">
                                        <div class="row">
                                               <div class="col-md-3">
                                                   <h3 class="box-title m-b-0">Checkbox Square</h3>
                                                   <p class="text-muted font-13 m-b-30"> Bootstrap brand colors </p>
                                                   <div class="form-group checkbox checkbox-info checkbox-circle" style="margin-bottom: 15px;" ng-repeat="p in permissions">
                                                       <input ng-model="p.val" type="checkbox">
                                                       <label> {{p.label}} </label>
                                                   </div>
                                               </div>
                                               <div class="col-md-3">
                                                   <h3 class="box-title m-b-0">Checkbox Square</h3>
                                                   <p class="text-muted font-13 m-b-30"> Bootstrap brand colors </p>
                                               </div>
                                               <div class="col-md-3">
                                                   <h3 class="box-title m-b-0">Checkbox Square</h3>
                                                   <p class="text-muted font-13 m-b-30"> Bootstrap brand colors </p>
                                               </div>

                                               <div class="col-md-3">
                                                   <h3 class="box-title m-b-0">Checkbox Square</h3>
                                                   <p class="text-muted font-13 m-b-30"> Bootstrap brand colors </p>
                                               </div>
                                           </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.row -->
            </div>
        </div>
        <!--page content end-->
    </div>
<?php include(APPPATH . "views/inc/footer.php"); ?>