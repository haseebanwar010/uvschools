<!DOCTYPE html>
<html>
    <head>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!-- Angular js -->
        <script src="assets/angularjs/angular.min.js"></script>
        <!-- Angular auto validator lib -->
        <!-- <script src="assets/angularjs-auto-validator/dist/jcs-auto-validate.min.php"></script> -->
        <link href="assets/xcrud/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
        <link href="assets/xcrud/plugins/timepicker/jquery-ui-timepicker-addon.css" rel="stylesheet"/>
        <link href="assets/plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css" rel="stylesheet">
        <link href="assets/css/style.php?color=''" rel="stylesheet">
        <style type="text/css">
            body{
                background: white;
            }
        </style>
        <title>Admin Panel</title>
    </head>
    <body ng-app="adminpanel" ng-controller="passcode" ng-cloak>

        <div class="container" ng-if="license">

            <div class="form-group">
                &nbsp;
            </div>
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#licenses">Licenses</a></li>
                <li><a data-toggle="tab" href="#maintenance">Maintenance</a></li>
                <li><a data-toggle="tab" href="#other">Other</a></li>
            </ul>

            <div class="tab-content">
                <div id="licenses" class="tab-pane fade in active">
                    <div ng-bind-html="license"></div>
                </div>
                <div id="maintenance" class="tab-pane fade">
                    <div ng-bind-html="maintenance"></div>
                </div>
                <div id="other" class="tab-pane fade">
                    <button class="btn btn-primary" ng-click="resolve_collation_issues()" id="issue_button">Resolve Collation Issues</button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Passcode</h4>

                    </div>
                    <form ng-submit="submit(passcodeForm.$valid)" name="passcodeForm">
                        <div class="modal-body mx-3">
                            <div class="form-group md-form mb-5">

                                <input type="password" class="form-control validate" ng-model="formModel.passcode" required="" ng-change="resetError()">

                            </div>


                        </div>

                        <div class="modal-footer d-flex justify-content-center">
                            <span class="text-danger pull-left">{{errorMsg}}</span>
                            <button class="btn btn-deep-orange" id="loginBtn">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function () {
                $('#myModal').modal({
                    backdrop: 'static',
                    keyboard: false
                })
                $('#myModal').modal('show');
            });
        </script>

        <script type="text/javascript">
            //var base_url = "https://"+window.location.host+"/uv/myschool2-master/";
            var base_url = "https://" + window.location.host + "/";


            var app = angular.module('adminpanel', []);
            // var app = angular.module('adminpanel', ['jcs-autoValidate']);
            var config = {
                headers: {
                    'Content-Type': 'application/json;charset=utf-8;'
                }
            };

            app.controller("passcode", function ($scope, $http, $sce) {
                $scope.submit = function (valid) {
                    if (valid) {
                        $('#loginBtn').attr("disabled", true);
                        $http.post(base_url + 'adminpanel/auth', $scope.formModel, config).then(function (response) {

                            if (response.data.success) {

                                $scope.license = $sce.trustAsHtml(response.data.license);
                                $scope.maintenance = $sce.trustAsHtml(response.data.maintenance);
                                $('#myModal').modal('hide');
                            } else {
                                $('#loginBtn').attr("disabled", false);

                                $scope.errorMsg = response.data.error;
                            }


                        }, function (error) {
                            console.log(error.data);
                        });
                    }
                }

                $scope.resetError = function () {
                    $scope.errorMsg = '';
                }

                $scope.resolve_collation_issues = function(){
                    $('#issue_button').attr("disabled", true);
                        $http.post(base_url + 'adminpanel/resolve_collation_issues', '', config).then(function (response) {

                            if (response.data.success) {

                                alert('Collation issues resolved!');
                                $('#issue_button').attr("disabled", false);
                            } else {
                                $('#issue_button').attr("disabled", false);

                                alert('Some problem occurred');
                            }


                        }, function (error) {
                            console.log(error.data);
                        });
                }
            });
        </script>


    </body>
</html>