<?php
    header('Content-Type: application/javascript');
    ob_start();
    //require_once($_SERVER['DOCUMENT_ROOT'] . '/uv/myschool2-master/index.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/index.php');
    ob_end_clean();
?>

var base_url = "https://"+window.location.host+"/";
//var base_url = "https://"+window.location.host+"/uv/myschool2-master/";

var global_signup_date = {};
var global_online_admission_object = [];
var app = angular.module('myschool', ['jcs-autoValidate']);
var config = {
    headers: {
        'Content-Type': 'application/json;charset=utf-8;'
    }
};

function getCurrentDate(){
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1;
    var yyyy = today.getFullYear();

    if (dd < 10) {
        dd = '0' + dd;
    }

    if (mm < 10) {
        mm = '0' + mm;
    }

    return today = dd + '/' + mm + '/' + yyyy;
}

app.controller("signupCtrl", function ($scope, $http, $window, $timeout) {
    $scope.formModel = {};
    $scope.alert = {};
    $scope.isLoading = false;
    $scope.onSubmit = function (valid) {
        if (valid) {
            Loading("#signupForm", "", "", "show");
            $scope.isLoading = true;
            $http.post(base_url + 'login/register', $scope.formModel, config).then(function (response) {
                Loading("#signupForm", "", "", "hide");
                $scope.displayMessage(response);
                $scope.isLoading = false;
            }, function (error) {
                console.log(error.data);
                $scope.isLoading = false;
            });
        }
    };
    $scope.reset = function () {
        $scope.formModel = {};
        $scope.signupForm.$setUntouched();
        $scope.signupForm.$setPristine();
    };
    $scope.displayMessage = function (response) {
        $window.scrollTo(0, 0);
        /*$timeout(function () {
            $scope.alert.hasMessage = false;
        }, 5000);*/
        if (response.data.status === "success") {
            global_signup_date = $scope.formModel;
            $scope.reset();
            $scope.alert.hasMessage = true;
            $scope.alert.title = "Success";
            $scope.alert.class = "alert-success";
            $scope.alert.message = response.data.message;
        } else if (response.data.status === "error") {
            $scope.alert.hasMessage = true;
            $scope.alert.title = "Error";
            $scope.alert.class = "alert-danger";
            $scope.alert.message = response.data.message;
        }
    };
    
    $scope.resendEmail = function(){
        $http.post(base_url + 'login/resendEmail', global_signup_date, config).then(
            function(success){
                $scope.alert.hasMessage = true;
                $scope.alert.title = "Success";
                $scope.alert.class = "alert-success";
                $scope.alert.message = success.data.message;
            },
            function(error){
                console.log(error.data);
            }
        );
    };
});

app.run(function (defaultErrorMessageResolver) {
    defaultErrorMessageResolver.getErrorMessages().then(function (errorMessages) {
        errorMessages['tooYoung'] = 'You must be at least {0} years old to use this site';
        errorMessages['tooOld'] = 'You must be {0} years old or less to use this site';
        errorMessages['badUsername'] = 'Username can only contain numbers and letters and underscore';
    });
});

function ConfirmPasswordValidatorDirective(defaultErrorMessageResolver) {
    defaultErrorMessageResolver.getErrorMessages().then(function (errorMessages) {
        errorMessages['confirmPassword'] = '<?php echo lang("password_match"); ?>';
    });
    return {
        restrict: 'A',
        require: 'ngModel',
        scope: {
            confirmPassword: '=confirmPassword'
        },
        link: function (scope, element, attributes, ngModel) {
            ngModel.$validators.confirmPassword = function (modelValue) {
                return modelValue === scope.confirmPassword;
            };
            scope.$watch('confirmPassword', function () {
                ngModel.$validate();
            });
        }
    };
}

ConfirmPasswordValidatorDirective.$inject = [
    'defaultErrorMessageResolver'
];
app.directive('confirmPassword', ConfirmPasswordValidatorDirective);

app.controller("loginCtrl", function ($scope, $http, $location, $window, $timeout) {
    $scope.formModel = {};
    
    $scope.alert = {};
    
    $scope.onSubmit = function (valid) {
        if (valid) {
            Loading("#loginform", "", "", "show");
            $http.post(base_url + 'login/auth', $scope.formModel, config).then(function (response) {
                Loading("#loginform", "", "", "hide");
                $scope.displayMessage(response);
            }, function (error) {
                console.log(error.data);
            });
        }
    };
    
    $scope.onSubmitDefault = function (valid) {
        if (valid) {
            Loading("#loginform", "", "", "show");
            $http.post(base_url + 'default_login/auth', $scope.formModel, config).then(function (response) {
                Loading("#loginform", "", "", "hide");
                $scope.displayMessage(response);
            }, function (error) {
                console.log(error.data);
            });
        }
    };

    $scope.displayMessage = function (response) {
        $timeout(function () {
            $scope.alert.hasMessage = false;
        }, 5000);
        if (response.data.status === "success") {
            $window.location = base_url + 'dashboard';
        } else if (response.data.status === "error") {
            $scope.alert.hasMessage = true;
            $scope.alert.title = "";
            $scope.alert.class = "alert-danger";
            $scope.alert.message = response.data.message;
        }
    };
});

function Loading(selector, text, image, action) {
    $.LoadingOverlaySetup({
        color: "rgba(255, 255, 255, 0.7)",
        maxSize: "26px",
        minSize: "10px",
        resizeInterval: 0
    });
    var customElement = $("<div>", {
        id: "customElement",
        css: {
            "font-size": "12px"
        },
        text: text
    });

    $(selector).LoadingOverlay(action, {
        image: image,
        fontawesome: "fa fa-circle-o-notch fa-spin",
        custom: customElement
    });
}

var app2 = angular.module('myschool2', ['jcs-autoValidate', 'angularUtils.directives.dirPagination', 'uiCropper', 'angularFileUpload']);

app2.controller("schInfoCtrl", function ($scope, $http) {
    $scope.formModel = {};
    /*$http.post(base_url + 'login/auth', $scope.formModel, config)
     .then(function (response) {
     $scope.displayMessage(response);
     }, function (error) {
     console.log(error.data);
     });*/
});

app2.controller("employeeCtrl", function ($scope, $http, $window, $location, $filter) {

//    var today = new Date();
//    var dd = today.getDate();
//    var mm = today.getMonth() + 1; //January is 0!
//    var yyyy = today.getFullYear();
//
//    if (dd < 10) {
//        dd = '0' + dd;
//    }
//
//    if (mm < 10) {
//        mm = '0' + mm;
//    }
//
//    today = dd + '/' + mm + '/' + yyyy;

    $scope.formModel = {
        gender: '',
        name: '',
        marital_status: '',
        contact: '',
        language: '',
        email: '',
        dob: '',
        nationality: '',
        ic: '',
        passport: '',
        street: '',
        fax: '',
        country: '',
        city: '',
        department: '',
        category: '',
        joining_date: '',
        job_title: '',
        qualification: '',
        experience_duration: '',
        experience_info: '',
        emp_id:'',
        basic_salary:0
    };
    $scope.alert = {};
    $scope.permissions = {};
    $scope.errLength;
    $scope.areFieldsNotFilled = false;
    $scope.permissions = [
       {label: '<?php echo lang("students-show");?>', permission: 'students-show', val: true},
       {label: '<?php echo lang("students-add");?>', permission: 'students-add', val: false},
       {label: '<?php echo lang("students-edit");?>', permission: 'students-edit', val: false},
       {label: '<?php echo lang("students-view");?>', permission: 'students-view', val: false},
       {label: '<?php echo lang("attendance-show");?>', permission: 'attendance-show', val: true},
       {label: '<?php echo lang("attendance-report");?>', permission: 'attendance-report', val: true},

       {label: '<?php echo lang("parents-all");?>', permission: 'parents-all', val: true},
       {"label": '<?php echo lang("parents-add");?>', "permission": "parents-add", "val": false},
       {"label": '<?php echo lang("parents-edit");?>', "permission": "parents-edit", "val": false},
       {label: '<?php echo lang("parents-view");?>', permission: 'parents-view', val: false},

       {"label": '<?php echo lang("attendance-employee");?>', "permission": "attendance-employee", "val": false},
       {"label": '<?php echo lang("attendance-emp_report");?>', "permission": "attendance-emp_report", "val": false},
       {"label": '<?php echo lang("employee-all");?>', "permission": "employee-all", "val": false},
       {"label": '<?php echo lang("employee-view");?>', "permission": "employee-view", "val": false},
       {"label": '<?php echo lang("employee-add");?>', "permission": "employee-add", "val": false},
       {"label": '<?php echo lang("employee-edit");?>', "permission": "employee-edit", "val": false},

       {label: '<?php echo lang("study_material-upload");?>', permission: 'study_material-upload', val: true},
       {label: '<?php echo lang("study_material-download");?>', permission: 'study_material-download', val: true},
       {label: '<?php echo lang("study_material-book_shop");?>', permission: 'study_material-book_shop', val: true},

       {"label": '<?php echo lang("forms-all");?>', "permission": "forms-all", "val": true},
       {"label": '<?php echo lang("forms-create");?>', "permission": "forms-create", "val": false},
       {"label": '<?php echo lang("forms-edit");?>', "permission": "forms-edit", "val": false},
       {"label": '<?php echo lang("forms-category_create");?>', "permission": "forms-category_create", "val": false},

       {"label": '<?php echo lang("profile-index");?>', "permission": "profile-index", "val": true},
       {"label": '<?php echo lang("profile-edit");?>', "permission": "profile-edit", "val": false},
       {label: '<?php echo lang("students-shift");?>', permission: 'students-shift', val: false},
       {label: '<?php echo lang("fee-collection");?>', permission: 'fee-collection', val: false},
       {label: '<?php echo lang("collection-allow");?>', permission: 'collection-allow', val: false},
       {label: '<?php echo lang("view-collection");?>', permission: 'view-collection', val: false},
       {"label": '<?php echo lang("fee-statistics");?>', "permission": "fee-statistics", "val": false},


       {label: '<?php echo lang("timetable-show");?>', permission: 'timetable-show', val: true},
       {label: '<?php echo lang("timetable-edit");?>', permission: 'timetable-edit', val: true},

       {label: '<?php echo lang("reports-all");?>', permission: 'reports-all', val: false},

       {label: '<?php echo lang("syllabus-add");?>', permission: 'syllabus-add', val: false},
       {label: '<?php echo lang("assign-teacher");?>', permission: 'assign-teacher', val: false},

       
       
       {label: '<?php echo lang("examination-add");?>', permission: 'examination-add', val: false},
       {label: '<?php echo lang("examination-marks");?>', permission: 'examination-marks', val: false},
       {label: '<?php echo lang("examination-majorSheet");?>', permission: 'examination-majorSheet', val: false},
       {label: '<?php echo lang("applications-student");?>', permission: 'applications-student', val: false},
       {label: '<?php echo lang("applications-employee");?>', permission: 'applications-employee', val: false},
       {label: '<?php echo lang("applications-studyplan");?>', permission: 'applications-studyplan', val: false},
       {label: '<?php echo lang("applications-marksheet");?>', permission: 'applications-marksheet', val: false},
       {label: '<?php echo lang("payroll-index");?>', permission: 'payroll-index', val: false},
       {label: '<?php echo lang("payroll-pay");?>', permission: 'payroll-pay', val: false},
       {label: '<?php echo lang("payroll-delete");?>', permission: 'payroll-delete', val: false},
       {label: '<?php echo lang("payroll-settings");?>', permission: 'payroll-settings', val: false},
       {label: '<?php echo lang("payroll-settingsadd");?>', permission: 'payroll-settingsadd', val: false},
       {label: '<?php echo lang("payroll-settingsedit");?>', permission: 'payroll-settingsedit', val: false},
       {label: '<?php echo lang("payroll-settingsdelete");?>', permission: 'payroll-settingsdelete', val: false},
       {label: '<?php echo lang("settings-evaluation");?>', permission: 'settings-evaluation', val: false},
       {label: '<?php echo lang("settings-evaladd");?>', permission: 'settings-evaladd', val: false},
       {label: '<?php echo lang("settings-evaledit");?>', permission: 'settings-evaledit', val: false},
       {label: '<?php echo lang("settings-evaledelete");?>', permission: 'settings-evaledelete', val: false},
       {label: '<?php echo lang("students-evaluate");?>', permission: 'students-evaluate', val: false},
       {label: '<?php echo lang("students-report_card");?>', permission: 'students-report_card', val: false},
       {label: '<?php echo lang("trash-index");?>', permission: 'trash-index', val: false},
       {label: '<?php echo lang("trash-recover");?>', permission: 'trash-recover', val: false},
       {label: '<?php echo lang("trash-delete");?>', permission: 'trash-delete', val: false},
       
       {label: '<?php echo lang("accounts-dashboard");?>', permission: 'accounts-dashboard', val: false},
       {label: '<?php echo lang("accounts-collect");?>', permission: 'accounts-collect', val: false},
       {label: '<?php echo lang("accounts-pay");?>', permission: 'accounts-pay', val: false},
       {label: '<?php echo lang("accounts-deposit");?>', permission: 'accounts-deposit', val: false},
       {label: '<?php echo lang("accounts-depositEdit");?>', permission: 'accounts-depositEdit', val: false},
       {label: '<?php echo lang("accounts-withdraw");?>', permission: 'accounts-withdraw', val: false},

       {label: '<?php echo lang("accounts-income_settings");?>', permission: 'accounts-income_settings', val: false},
       {label: '<?php echo lang("accounts-incomeAdd");?>', permission: 'accounts-incomeAdd', val: false},
       {label: '<?php echo lang("accounts-incomeEdit");?>', permission: 'accounts-incomeEdit', val: false},
       {label: '<?php echo lang("accounts-incomeDelete");?>', permission: 'accounts-incomeDelete', val: false},

       {label: '<?php echo lang("accounts-expense_settings");?>', permission: 'accounts-expense_settings', val: false},
       {label: '<?php echo lang("accounts-expenseAdd");?>', permission: 'accounts-expenseAdd', val: false},
       {label: '<?php echo lang("accounts-expenseEdit");?>', permission: 'accounts-expenseEdit', val: false},
       {label: '<?php echo lang("accounts-expenseDelete");?>', permission: 'accounts-expenseDelete', val: false},

       {label: '<?php echo lang("accounts-virtual_accounts");?>', permission: 'accounts-virtual_accounts', val: false},
       {label: '<?php echo lang("accounts-virtualAdd");?>', permission: 'accounts-virtualAdd', val: false},
       {label: '<?php echo lang("accounts-virtualEdit");?>', permission: 'accounts-virtualEdit', val: false},
       {label: '<?php echo lang("accounts-virtualDelete");?>', permission: 'accounts-virtualDelete', val: false},

        // user management
        {label: '<?php echo lang("manage-viewEmployees");?>', permission: 'manage-viewEmployees', val: false},
        {label: '<?php echo lang("manage-changeEmpStatus");?>', permission: 'manage-changeEmpStatus', val: false},
        {label: '<?php echo lang("manage-viewStduents");?>', permission: 'manage-viewStduents', val: false},
        {label: '<?php echo lang("manage-changeStdStatus");?>', permission: 'manage-changeStdStatus', val: false},
        {label: '<?php echo lang("manage-viewGuardians");?>', permission: 'manage-viewGuardians', val: false},
        {label: '<?php echo lang("manage-changeGuardianStatus");?>', permission: 'manage-changeGuardianStatus', val: false},
       
        // online Examination
        {label: '<?php echo lang("online_exams-settings");?>', permission: 'online_exams-settings', val: false},
        {label: '<?php echo lang("online_exams-addSettings");?>', permission: 'online_exams-addSettings', val: false},
        {label: '<?php echo lang("online_exams-settingsEdit");?>', permission: 'online_exams-settingsEdit', val: false},
        {label: '<?php echo lang("online_exams-settingsDelete");?>', permission: 'online_exams-settingsDelete', val: false},
        {label: '<?php echo lang("online_exams-add_question");?>', permission: 'online_exams-add_question', val: false},
        {label: '<?php echo lang("online_exams-publish_papers");?>', permission: 'online_exams-publish_papers', val: false},
        {label: '<?php echo lang("online_exams-results");?>', permission: 'online_exams-results', val: false},

       // calander
        {label: '<?php echo lang("calandar-view");?>', permission: 'calandar-view', val: false},
        {label: '<?php echo lang("calandar-add");?>', permission: 'calandar-add', val: false},
        {label: '<?php echo lang("calandar-edit");?>', permission: 'calandar-edit', val: false},
        {label: '<?php echo lang("calandar-delete");?>', permission: 'calandar-delete', val: false}
    ];

    

    $scope.categories = [];
    $scope.chkMissingFields = function () {
        if ($scope.empForm.$error.required.length > 0) {
            $scope.areFieldsNotFilled = true;
        } else {
            $scope.areFieldsNotFilled = false;
        }
    };
    $scope.fetchCategories = function () {
        Loading("#categories", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'employee/getCategoriesByDepartmentID', {department: $scope.formModel.department}, config).then(
                function (success) {
                    $scope.categories = success.data;
                    $scope.formModel.category = '';
                    Loading("#categories", '<?php echo lang("loading_datatable") ?>', "", "hide");
                },
                function (error) {
                    console.log(error.data);
                    Loading("#categories", '<?php echo lang("loading_datatable") ?>', "", "hide");
                }
        );
    };

    $scope.getCategoryPermissions = function(){

        $http.post(base_url + "settings/getCategory", {"id": $scope.formModel.category}, config).then(
            function(success){
            $scope.permissions = success.data.permissions;
            //console.log(success.data.permissions);
            //$scope.permissions = JSON.parse($scope.updateModel.permissions);
            },function(error){
            console.log(error.data);
        }
        );
    }; 
    $scope.onSubmit = function (valid) {
        if (valid) {
            //console.log($scope.formModel);
            if ($scope.imageDataURI) {
                $scope.formModel.avatar = $scope.resImageDataURI;
            } else {
                $scope.formModel.avatar = "default";
            }
            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
            $scope.formModel.permissions = $scope.permissions;
            //console.log($scope.formModel);

            $http.post(base_url + 'employee/save', $scope.formModel, config).then(
                    function (success) {
                        $window.scrollTo(0, 0);
                        if (success.data.status === "success") {
                            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                            $scope.formModel = {
                                gender: '',
                                name: '',
                                marital_status: '',
                                contact: '',
                                language: '',
                                email: '',
                                dob: '',
                                nationality: '',
                                ic: '',
                                passport: '',
                                street: '',
                                fax: '',
                                country: '',
                                city: '',
                                department: '',
                                category: '',
                                joining_date: '',
                                job_title: '',
                                qualification: '',
                                experience_duration: '',
                                experience_info: '',
                                emp_id:'',
                                basic_salary:0
                            };
                            $scope.resImageDataURI = '';
                            $scope.empForm.$setUntouched();
                            $scope.empForm.$setPristine();
                            $scope.alert.message = success.data.message;
                            $scope.alert.type = "alert-success";

                            $scope.image2 = false;
                            $scope.areFieldsNotFilled = false;
                            //$location.path('employee/');
                            //console.log(success.data);
                        } else if (success.data.status === "error") {
                            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                            $scope.alert.message = success.data.message;
                            $scope.alert.type = "alert-danger";
                            //console.log(success.data);
                        }
                    },
                    function (error) {
                        console.log(error.data);
                    }
            );
        } else {
            console.log("some form errors");
        }
    };


    /*--- Image cropper ---*/
    $scope.blockingObject = {block: true};
    $scope.callTestFuntion = function () {
        $scope.blockingObject.render(function (dataURL) {
            //console.log($scope.blockingObject);
            //console.log('via render');
            //console.log(dataURL.length);
        });
    };
    $scope.blockingObject.callback = function (dataURL) {
        //console.log('via function');
        //console.log(dataURL.length);
    };
    $scope.size = 'small';
    $scope.type = 'square';
    $scope.imageDataURI = '';
    $scope.resImageDataURI = '';
    $scope.resBlob = {};
    $scope.urlBlob = {};
    $scope.resImgFormat = 'image/jpeg';
    $scope.resImgQuality = 0.9;
    $scope.selMinSize = 100;
    $scope.selInitSize = [{w: 200, h: 80}];
    $scope.resImgSize = [{w: 300, h: 300}, {w: 300, h: 300}];
    //$scope.aspectRatio=1.2;
    $scope.onChange = function ($dataURI) {
        //console.log('onChange fired');
    };
    $scope.onLoadBegin = function () {
        //console.log('onLoadBegin fired');
    };
    $scope.onLoadDone = function () {
        //console.log('onLoadDone fired');
    };
    $scope.onLoadError = function () {
        //console.log('onLoadError fired');
    };
    $scope.getBlob = function () {
        //console.log($scope.resBlob);
    };
    var handleFileSelect = function (evt) {
        var file = evt.currentTarget.files[0],
                reader = new FileReader();
        if (navigator.userAgent.match(/iP(hone|od|ad)/i)) {
            var canvas = document.createElement('canvas'),
                    mpImg = new MegaPixImage(file);

            canvas.width = mpImg.srcImage.width;
            canvas.height = mpImg.srcImage.height;

            EXIF.getData(file, function () {
                var orientation = EXIF.getTag(this, 'Orientation');

                mpImg.render(canvas, {
                    maxHeight: $scope.resImgSize,
                    orientation: orientation
                });
                setTimeout(function () {
                    var tt = canvas.toDataURL("image/jpeg", 1);
                    $scope.$apply(function ($scope) {
                        $scope.imageDataURI = tt;
                    });
                }, 100);
            });
        } else {
            reader.onload = function (evt) {
                $scope.$apply(function ($scope) {
                    //console.log(evt.target.result);
                    $scope.imageDataURI = evt.target.result;
                });
            };
            reader.readAsDataURL(file);
        }
    };
    angular.element(document.querySelector('#fileInput')).on('change', handleFileSelect);
    $scope.$watch('resImageDataURI', function () {
        //console.log('Res image', $scope.resImageDataURI);
    });
    /*--- Image cropper ---*/
});


app2.controller("profileEditController", function($scope,$http){
    /*--- Image cropper ---*/
    $scope.uploadedImage = {};
    $scope.blockingObject = {block: true};
    $scope.callTestFuntion = function () {
        $scope.blockingObject.render(function (dataURL) {
            //$scope.uploadedImage = dataURL;
            //console.log(dataURL);
            //console.log('via render');
            //console.log(dataURL.length);
        });
    };
    $scope.blockingObject.callback = function (dataURL) {
        //console.log('via function');
        //console.log(dataURL);
    };
    $scope.size = 'small';
    $scope.type = 'square';
    $scope.imageDataURI = '';
    $scope.resImageDataURI = '';
    $scope.resBlob = {};
    $scope.urlBlob = {};
    $scope.resImgFormat = 'image/jpeg';
    $scope.resImgQuality = 0.9;
    $scope.selMinSize = 100;
    $scope.selInitSize = [{w: 200, h: 80}];
    $scope.resImgSize = [{w: 300, h: 300}, {w: 300, h: 300}];
    //$scope.aspectRatio=1.2;
    $scope.onChange = function ($dataURI) {
        //console.log('onChange fired');
    };
    $scope.onLoadBegin = function () {
        //console.log('onLoadBegin fired');
    };
    $scope.onLoadDone = function () {
        //console.log('onLoadDone fired');
    };
    $scope.onLoadError = function () {
        //console.log('onLoadError fired');
    };
    $scope.getBlob = function () {
        //console.log($scope.resBlob);
    };
    var handleFileSelect = function (evt) {
        var file = evt.currentTarget.files[0],
                reader = new FileReader();
        if (navigator.userAgent.match(/iP(hone|od|ad)/i)) {
            var canvas = document.createElement('canvas'),
                    mpImg = new MegaPixImage(file);

            canvas.width = mpImg.srcImage.width;
            canvas.height = mpImg.srcImage.height;

            EXIF.getData(file, function () {
                var orientation = EXIF.getTag(this, 'Orientation');

                mpImg.render(canvas, {
                    maxHeight: $scope.resImgSize,
                    orientation: orientation
                });
                setTimeout(function () {
                    var tt = canvas.toDataURL("image/jpeg", 1);
                    $scope.$apply(function ($scope) {
                        $scope.imageDataURI = tt;
                    });
                }, 100);
            });
        } else {
            reader.onload = function (evt) {
                $scope.$apply(function ($scope) {
                    //console.log(evt.target.result);
                    $scope.imageDataURI = evt.target.result;
                });
            };
            reader.readAsDataURL(file);
        }
    };
    angular.element(document.querySelector('#fileInput')).on('change', handleFileSelect);
    $scope.$watch('resImageDataURI', function () {
        if($scope.resImageDataURI){
            var user_id = $("#user_id").val();
            $http.post(base_url + 'profile/changepicture', {'user_id': user_id, 'image': $scope.resImageDataURI}, config).then(
                function (success) {
                    $('.bs-user-profile-edit-modal-lg').modal('hide');
                },
                function (error) {
                    console.log(error.data);
                },
            );
        } 
    });
    /*--- Image cropper ---*/
});

app2.controller("empEditCtrl", function ($scope) {
    /*--- Image cropper ---*/
    $scope.blockingObject = {block: true};
    $scope.callTestFuntion = function () {
        $scope.blockingObject.render(function (dataURL) {
            //console.log($scope.blockingObject);
            //console.log('via render');
            //console.log(dataURL.length);
        });
    };
    $scope.blockingObject.callback = function (dataURL) {
        //console.log('via function');
        //console.log(dataURL.length);
    };
    $scope.size = 'small';
    $scope.type = 'square';
    $scope.imageDataURI = '';
    $scope.resImageDataURI = '';
    $scope.resBlob = {};
    $scope.urlBlob = {};
    $scope.resImgFormat = 'image/jpeg';
    $scope.resImgQuality = 0.9;
    $scope.selMinSize = 100;
    $scope.selInitSize = [{w: 200, h: 80}];
    $scope.resImgSize = [{w: 300, h: 300}, {w: 300, h: 300}];
    //$scope.aspectRatio=1.2;
    $scope.onChange = function ($dataURI) {
        //console.log('onChange fired');
    };
    $scope.onLoadBegin = function () {
        //console.log('onLoadBegin fired');
    };
    $scope.onLoadDone = function () {
        //console.log('onLoadDone fired');
    };
    $scope.onLoadError = function () {
        //console.log('onLoadError fired');
    };
    $scope.getBlob = function () {
        //console.log($scope.resBlob);
    };
    var handleFileSelect = function (evt) {
        var file = evt.currentTarget.files[0],
                reader = new FileReader();
        if (navigator.userAgent.match(/iP(hone|od|ad)/i)) {
            var canvas = document.createElement('canvas'),
                    mpImg = new MegaPixImage(file);

            canvas.width = mpImg.srcImage.width;
            canvas.height = mpImg.srcImage.height;

            EXIF.getData(file, function () {
                var orientation = EXIF.getTag(this, 'Orientation');

                mpImg.render(canvas, {
                    maxHeight: $scope.resImgSize,
                    orientation: orientation
                });
                setTimeout(function () {
                    var tt = canvas.toDataURL("image/jpeg", 1);
                    $scope.$apply(function ($scope) {
                        $scope.imageDataURI = tt;
                    });
                }, 100);
            });
        } else {
            reader.onload = function (evt) {
                $scope.$apply(function ($scope) {
                    //console.log(evt.target.result);
                    $scope.imageDataURI = evt.target.result;

                });
            };
            reader.readAsDataURL(file);
        }
    };
    angular.element(document.querySelector('#fileInput')).on('change', handleFileSelect);
    $scope.$watch('resImageDataURI', function () {
        //console.log('Res image', $scope.resImageDataURI);
        $("#avatar").val($scope.resImageDataURI);
    });
    /*--- Image cropper ---*/
});

//////////////////////// 01-02-2018 /////////////////////

function showNotification(heading, message, icon) {
    $.toast({
        heading: heading,
        showHideTransition: 'slide',
        text: message,
        textColor: "#ffffff",
        position: 'bottom-right',
        loaderBg: '#fff',
        icon: icon,
        hideAfter: 3000,
        stack: 10
    });
}

app2.controller("addDeptController", function ($scope, $http, $window) {
    $scope.formModel = {};
    $scope.updateModel = {};
    $scope.departments = {};
    $scope.deptCategories = {};
    $scope.catModel = {};
    $scope.all_departments = {};
    $scope.permissions = [
       {label: '<?php echo lang("students-show");?>', permission: 'students-show', val: false},
       {label: '<?php echo lang("students-add");?>', permission: 'students-add', val: false},
       {label: '<?php echo lang("students-edit");?>', permission: 'students-edit', val: false},
       {label: '<?php echo lang("students-view");?>', permission: 'students-view', val: false},
       {label: '<?php echo lang("attendance-show");?>', permission: 'attendance-show', val: true},
       {label: '<?php echo lang("attendance-report");?>', permission: 'attendance-report', val: true},

       {label: '<?php echo lang("parents-all");?>', permission: 'parents-all', val: false},
       {"label": '<?php echo lang("parents-add");?>', "permission": "parents-add", "val": false},
       {"label": '<?php echo lang("parents-edit");?>', "permission": "parents-edit", "val": false},
       {label: '<?php echo lang("parents-view");?>', permission: 'parents-view', val: false},



       {"label": '<?php echo lang("attendance-employee");?>', "permission": "attendance-employee", "val": false},
       {"label": '<?php echo lang("attendance-emp_report");?>', "permission": "attendance-emp_report", "val": false},
       {"label": '<?php echo lang("employee-all");?>', "permission": "employee-all", "val": false},
       {"label": '<?php echo lang("employee-view");?>', "permission": "employee-view", "val": false},
       {"label": '<?php echo lang("employee-add");?>', "permission": "employee-add", "val": false},
       {"label": '<?php echo lang("employee-edit");?>', "permission": "employee-edit", "val": false},

       {label: '<?php echo lang("study_material-upload");?>', permission: 'study_material-upload', val: false},
       {label: '<?php echo lang("study_material-download");?>', permission: 'study_material-download', val: false},
       {label: '<?php echo lang("study_material-book_shop");?>', permission: 'study_material-book_shop', val: false},

       {"label": '<?php echo lang("forms-all");?>', "permission": "forms-all", "val": false},
       {"label": '<?php echo lang("forms-create");?>', "permission": "forms-create", "val": false},
       {"label": '<?php echo lang("forms-edit");?>', "permission": "forms-edit", "val": false},
       {"label": '<?php echo lang("forms-category_create");?>', "permission": "forms-category_create", "val": false},

       {"label": '<?php echo lang("profile-index");?>', "permission": "profile-index", "val": false},
       {"label": '<?php echo lang("profile-edit");?>', "permission": "profile-edit", "val": false},
       {label: '<?php echo lang("students-shift");?>', permission: 'students-shift', val: false},
       {label: '<?php echo lang("fee-collection");?>', permission: 'fee-collection', val: false},
       {label: '<?php echo lang("collection-allow");?>', permission: 'collection-allow', val: false},
       {label: '<?php echo lang("view-collection");?>', permission: 'view-collection', val: false},
       {"label": '<?php echo lang("fee-statistics");?>', "permission": "fee-statistics", "val": false},


       {label: '<?php echo lang("timetable-show");?>', permission: 'timetable-show', val: false},
       {label: '<?php echo lang("timetable-edit");?>', permission: 'timetable-edit', val: false},

       {label: '<?php echo lang("reports-all");?>', permission: 'reports-all', val: false},

       {label: '<?php echo lang("syllabus-add");?>', permission: 'syllabus-add', val: false},
       {label: '<?php echo lang("assign-teacher");?>', permission: 'assign-teacher', val: false},
       
       {label: '<?php echo lang("examination-add");?>', permission: 'examination-add', val: false},
       {label: '<?php echo lang("examination-marks");?>', permission: 'examination-marks', val: false},
       {label: '<?php echo lang("examination-majorSheet");?>', permission: 'examination-majorSheet', val: false},
       {label: '<?php echo lang("applications-student");?>', permission: 'applications-student', val: false},
       {label: '<?php echo lang("applications-employee");?>', permission: 'applications-employee', val: false},
       {label: '<?php echo lang("applications-studyplan");?>', permission: 'applications-studyplan', val: false},
       {label: '<?php echo lang("applications-marksheet");?>', permission: 'applications-marksheet', val: false},
       
       {label: '<?php echo lang("payroll-index");?>', permission: 'payroll-index', val: false},
       {label: '<?php echo lang("payroll-pay");?>', permission: 'payroll-pay', val: false},
       {label: '<?php echo lang("payroll-delete");?>', permission: 'payroll-delete', val: false},
       {label: '<?php echo lang("payroll-settings");?>', permission: 'payroll-settings', val: false},
       {label: '<?php echo lang("payroll-settingsadd");?>', permission: 'payroll-settingsadd', val: false},
       {label: '<?php echo lang("payroll-settingsedit");?>', permission: 'payroll-settingsedit', val: false},
       {label: '<?php echo lang("payroll-settingsdelete");?>', permission: 'payroll-settingsdelete', val: false},

       {label: '<?php echo lang("settings-evaluation");?>', permission: 'settings-evaluation', val: false},
       {label: '<?php echo lang("settings-evaladd");?>', permission: 'settings-evaladd', val: false},
       {label: '<?php echo lang("settings-evaledit");?>', permission: 'settings-evaledit', val: false},
       {label: '<?php echo lang("settings-evaledelete");?>', permission: 'settings-evaledelete', val: false},
       {label: '<?php echo lang("students-evaluate");?>', permission: 'students-evaluate', val: false},
       {label: '<?php echo lang("students-report_card");?>', permission: 'students-report_card', val: false},
       {label: '<?php echo lang("trash-index");?>', permission: 'trash-index', val: false},
       {label: '<?php echo lang("trash-recover");?>', permission: 'trash-recover', val: false},
       {label: '<?php echo lang("trash-delete");?>', permission: 'trash-delete', val: false},
       // accounts dashboard
       {label: '<?php echo lang("accounts-dashboard");?>', permission: 'accounts-dashboard', val: false},
       {label: '<?php echo lang("accounts-collect");?>', permission: 'accounts-collect', val: false},
       {label: '<?php echo lang("accounts-pay");?>', permission: 'accounts-pay', val: false},
       {label: '<?php echo lang("accounts-deposit");?>', permission: 'accounts-deposit', val: false},
       {label: '<?php echo lang("accounts-depositEdit");?>', permission: 'accounts-depositEdit', val: false},
       {label: '<?php echo lang("accounts-withdraw");?>', permission: 'accounts-withdraw', val: false},
       // accounts collect
       {label: '<?php echo lang("accounts-income_settings");?>', permission: 'accounts-income_settings', val: false},
       {label: '<?php echo lang("accounts-incomeAdd");?>', permission: 'accounts-incomeAdd', val: false},
       {label: '<?php echo lang("accounts-incomeEdit");?>', permission: 'accounts-incomeEdit', val: false},
       {label: '<?php echo lang("accounts-incomeDelete");?>', permission: 'accounts-incomeDelete', val: false},
        // accounts expense
       {label: '<?php echo lang("accounts-expense_settings");?>', permission: 'accounts-expense_settings', val: false},
       {label: '<?php echo lang("accounts-expenseAdd");?>', permission: 'accounts-expenseAdd', val: false},
       {label: '<?php echo lang("accounts-expenseEdit");?>', permission: 'accounts-expenseEdit', val: false},
       {label: '<?php echo lang("accounts-expenseDelete");?>', permission: 'accounts-expenseDelete', val: false},
        // accounts bank virtual
       {label: '<?php echo lang("accounts-virtual_accounts");?>', permission: 'accounts-virtual_accounts', val: false},
       {label: '<?php echo lang("accounts-virtualAdd");?>', permission: 'accounts-virtualAdd', val: false},
       {label: '<?php echo lang("accounts-virtualEdit");?>', permission: 'accounts-virtualEdit', val: false},
       {label: '<?php echo lang("accounts-virtualDelete");?>', permission: 'accounts-virtualDelete', val: false},

       // user management
       {label: '<?php echo lang("manage-viewEmployees");?>', permission: 'manage-viewEmployees', val: false},
       {label: '<?php echo lang("manage-changeEmpStatus");?>', permission: 'manage-changeEmpStatus', val: false},
       {label: '<?php echo lang("manage-viewStduents");?>', permission: 'manage-viewStduents', val: false},
       {label: '<?php echo lang("manage-changeStdStatus");?>', permission: 'manage-changeStdStatus', val: false},
       {label: '<?php echo lang("manage-viewGuardians");?>', permission: 'manage-viewGuardians', val: false},
       {label: '<?php echo lang("manage-changeGuardianStatus");?>', permission: 'manage-changeGuardianStatus', val: false},
       
        // online Examination
        {label: '<?php echo lang("online_exams-settings");?>', permission: 'online_exams-settings', val: false},
        {label: '<?php echo lang("online_exams-addSettings");?>', permission: 'online_exams-addSettings', val: false},
        {label: '<?php echo lang("online_exams-settingsEdit");?>', permission: 'online_exams-settingsEdit', val: false},
        {label: '<?php echo lang("online_exams-settingsDelete");?>', permission: 'online_exams-settingsDelete', val: false},
        {label: '<?php echo lang("online_exams-add_question");?>', permission: 'online_exams-add_question', val: false},
        {label: '<?php echo lang("online_exams-publish_papers");?>', permission: 'online_exams-publish_papers', val: false},
        {label: '<?php echo lang("online_exams-results");?>', permission: 'online_exams-results', val: false},

        // calander
        {label: '<?php echo lang("calandar-view");?>', permission: 'calandar-view', val: false},
        {label: '<?php echo lang("calandar-add");?>', permission: 'calandar-add', val: false},
        {label: '<?php echo lang("calandar-edit");?>', permission: 'calandar-edit', val: false},
        {label: '<?php echo lang("calandar-delete");?>', permission: 'calandar-delete', val: false}
    ];
    $scope.onSubmit = function (valid) {
        if (valid) {
            Loading("body", "Saving", "", "show");
            $http.post(base_url + 'settings/saveDepartment', $scope.formModel, config).then(
                    function (success) {
                        
                        
                        Loading("body", "", "", "hide");
                        if (success.data.status === "error") {
                            showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                        } else {
                            $scope.initDeperments();
                            $('#responsive-modal').modal('hide');
                            showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                            $scope.formModel = {type : 'other'};
                            $scope.addDeptForm.$setUntouched();
                            $scope.addDeptForm.$setPristine();  
                            
                        }
                        
                    },
                    function (error) {
                        showNotification("Duplicate!", error.data, "info");
                        Loading("body", "", "", "hide");
                    }
            );
        }
    };

    $scope.getDepartment = function(id){
    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + "settings/getDepartment", {"id": id}, config).then(
            function(success){
                $scope.updateModel = success.data;
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
            },function(error){
            console.log(error.data);
        }
        );
    };

    $scope.updateDepartment = function(valid){
        if(valid){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + "settings/updateDepartment",  $scope.updateModel, config).then(
                function(success){
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    if(success.data.status === "error"){
                        showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                    }else{
                        $('#edit-department').modal('hide');
                        $scope.initDeperments();
                        
                        showNotification('<?php echo lang("success_app") ?>', success.data.message, success.data.status);
                        $scope.updateModel = {};
                    }
                    
                    
                },function(error){
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            });
        }
    };

    $scope.deleteDepartment = function (id) {
        swal({
            title: '<?php echo lang("are_you_sure") ?>',
            text: '<?php echo lang("delete_message") ?>',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55", confirmButtonText: '<?php echo lang("yes_delete") ?>',
            cancelButtonText: '<?php echo lang("btn_cancel") ?>',
            closeOnConfirm: true,
            closeOnCancel: true
        },
                function (isConfirm) {
                    if (isConfirm) {
                        $http.post(base_url + "settings/deleteDepartment", {"id": id}, config).then(
                                function (success) {
                                showNotification('<?php echo lang("success_app") ?>', success.data.message, success.data.status);
                                $scope.initDeperments();
                                },
                                function (error) {
                                    console.log(error.data);
                                }
                        );
                    }
                });
    };

    $scope.getDepartments = function(){
        Loading("#department_id", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'employee/getDepartments', "", config).then(
            function (success) {
                Loading("#department_id", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.departments = success.data.departments;
            }, 
            function(error){
                Loading("#department_id", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };
    
    $scope.initDeperments = function(){
            $http.post(base_url + 'settings/initDeperments', $scope.catModel, config).then(
                function (success) {
                   $scope.all_departments = success.data.departments;
                  // console.log($scope.all_departments);
                }, 
                function(error){
                    console.log(error);
                }
            );
    
        };

    $scope.initCategories = function(){
            $http.post(base_url + 'settings/initCategories', $scope.catModel, config).then(
                function (success) {
                    $scope.all_categories = success.data.categories;
                }, 
                function(error){
                    console.log(error);
                }
            );
    
        };

    $scope.addCategory = function(valid){
    if(valid){
        $scope.catModel.permissions = $scope.permissions;
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'settings/addCategory', $scope.catModel, config).then(
                function (success) {
                    $('#add-category').modal('hide');
                    $scope.initCategories();
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                     if (success.data.status === "error") {
                            showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                        } else {
                            showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                            
                        }
                    $scope.catModel = {};
                    $scope.addCategoryForm.$setUntouched();
                    $scope.addCategoryForm.$setPristine();
                }, 
                function(error){
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
            );
    
        }
    };
    $scope.getCategory = function(id){
       $http.post(base_url + "settings/getCategory", {"id": id}, config).then(
           function(success){
           console.log(success.data.permissions);
           $scope.updateModel = success.data;
           if(success.data.permissions == ''){
               $scope.updateModel.permissions = $scope.permissions;
           }else{
           $scope.updateModel.permissions = JSON.parse($scope.updateModel.permissions);
           }

           },function(error){
           console.log(error.data);
       }
       );
   }; 

    $scope.updateCategory = function(){
      
        console.log($scope.updateModel);
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + "settings/updateCategory",  $scope.updateModel, config).then(
                function(success){
                    $('#edit-category').modal('hide');
                    $scope.initCategories();
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    showNotification('<?php echo lang("success_app") ?>', success.data.message, success.data.status);
                    $scope.updateModel = {};
                    $scope.updateCategoryForm.$setUntouched();
                    $scope.updateCategoryForm.$setPristine();
                    
                },function(error){
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            });
        
    };
    
    $scope.removeCategory = function (id) {
        swal({
            title: '<?php echo lang("are_you_sure") ?>',
            text: '<?php echo lang("delete_message") ?>',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55", confirmButtonText: '<?php echo lang("yes_delete") ?>',
            cancelButtonText: '<?php echo lang("btn_cancel") ?>',
            closeOnConfirm: true,
            closeOnCancel: true
        },
            function (isConfirm) {
                if (isConfirm) {
                    $http.post(base_url + "settings/removeCategory", {"id": id}, config).then(
                        function (success) {
                        showNotification('<?php echo lang("success_app") ?>', success.data.message, success.data.status);
                        $scope.initCategories();
                        },
                        function (error) {
                            console.log(error.data);
                        }); 
                }
            });
        };
   
});

app2.controller("empyFilterController", function ($scope, $http, $window) {
    $scope.departments = [
        {id: '1', name: 'teacher'},
        {id: '2', name: 'teacher2'},
        {id: '3', name: 'teacher3'}
    ];
    $scope.categories = [
        {id: '1', category: 'category1'},
        {id: '2', category: 'category2'},
        {id: '3', category: 'category3'}
    ];

    $scope.fetchCatgories = function () {
        if ($scope.mySelectedDepartment !== null) {
            $window.alert($scope.mySelectedDepartment);
        }
    };
});

app2.controller("subjectController", function ($scope, $http, $sce) {
    $scope.classes = {};
    $scope.batches = {};
    $scope.selecedVal = 'all';
    $scope.selecedVal2 = 'all';
    $scope.myDiv = false;
    $scope.fetchClasses = function (id) {
        $http.post(base_url + 'settings/getSchoolClasses', "", config).then(
                function (success) {
                    $scope.classes = success.data;
                    if (id !== 'all') {
                        $scope.loadClassBatches(id);
                    }
                },
                function (error) {
                    console.log(error.data);
                }
        );
    };

    $scope.loadClassBatches = function (id) {
        if (id !== 'all') {
            //Loading("#yasir_batches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'settings/getClassBatches', {id: id}, config).then(
                    function (success) {
                        //Loading("#yasir_batches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.batches = success.data;
                    },
                    function (error) {
                        //Loading("#yasir_batches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        console.log(error.data);
                    }
            );
        } else {
            $scope.batches = {};
            $scope.selecedVal2 = 'all';
            $scope.loadSubjects();
        }
    };

    $scope.loadSubjects = function () {
        $http.post(base_url + 'settings/getSubjects', {class_id: $scope.selecedVal, batch_id: $scope.selecedVal2}, config).then(
                function (success) {
                    $scope.myDiv = $sce.trustAsHtml(success.data);
                },
                function (error) {
                    console.log(error.data);
                }
        );
    };
});

app2.controller("subjectGroupsController", function ($scope, $http, $sce) {
    $scope.classes = {};
    $scope.batches = {};
    $scope.selecedVal = 'all';
    $scope.selecedVal2 = 'all';
    $scope.myDiv = false;
    $scope.fetchClasses = function (id) {
        $http.post(base_url + 'settings/getSchoolClasses', "", config).then(
                function (success) {
                    $scope.classes = success.data;
                    if (id !== 'all') {
                        $scope.loadClassBatches(id);
                    }
                },
                function (error) {
                    console.log(error.data);
                }
        );
    };

    $scope.loadClassBatches = function (id) {
        if (id !== 'all') {
            //Loading("#yasir_batches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'settings/getClassBatches', {id: id}, config).then(
                    function (success) {
                        //Loading("#yasir_batches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.batches = success.data;
                    },
                    function (error) {
                        //Loading("#yasir_batches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        console.log(error.data);
                    }
            );
        } else {
            $scope.batches = {};
            $scope.selecedVal2 = 'all';
            $scope.loadSubjects();
        }
    };

    $scope.loadSubjects = function () {
        $http.post(base_url + 'settings/getSubjects', {class_id: $scope.selecedVal, batch_id: $scope.selecedVal2}, config).then(
                function (success) {
                    $scope.myDiv = $sce.trustAsHtml(success.data);
                },
                function (error) {
                    console.log(error.data);
                }
        );
    };
});

app2.controller('batchController', function ($scope, $http, $sce) {
    $scope.selectedClass = 'all';
    $scope.init = function () {
        $http.post(base_url + 'settings/getSchoolClasses', $scope.model).then(
                function (response) {
                    $scope.allClasses = response.data;
                });
    };

    $scope.updateTable = function () {
        console.log($scope.selectedClass);

        /*$http.post(base_url + 'settings/academic', {'id': $scope.selectedClass}).then(
         function (response) {
         console.log(response.data);
         //$scope.dynamicTable = $sce.trustAsHtml(response.data.batches);
         //alert('test');
         //jQuery(document).on("xcrudafterrequest",function(event,container){
         //console.log('test');
         //});
         }
         );*/

        //---------------old------------
        /*$http.post(base_url + 'settings/getClassBatch', {'id': $scope.selectedClass}).then(
         function (response) {
         console.log(response.data);
         //$scope.dynamicTable = $sce.trustAsHtml(response.data.batches);
         //alert('test');
         //jQuery(document).on("xcrudafterrequest",function(event,container){
         //console.log('test');
         //});
         }
         );*/
        //---------------old------------
    };
});

app2.controller('stdAdmissionController', function ($scope, $http, $window, $location) {
//    var today = new Date();
//    var dd = today.getDate();
//    var mm = today.getMonth() + 1; //January is 0!
//    var yyyy = today.getFullYear();
//
//    if (dd < 10) {
//        dd = '0' + dd;
//    }
//
//    if (mm < 10) {
//        mm = '0' + mm;
//    }
//
//    today = dd + '/' + mm + '/' + yyyy;
    $scope.formModel = {
        religion: '',
        firstname: '',
        birthPlace: '',
        nationality: '',
        language: '',
        email: '',
        address: '',
        city: '',
        phone: '',
        dob: '',
        adm_date: '',
        discount_id: '',
        subject_group: '',
        ic_number: ''
    };
    $scope.batches = {};
    $scope.discounts = {};
    $scope.groups = {};
    $scope.alert = {};
    $scope.loadDiscounts = function(){
        $http.post(base_url + 'fee/get_discounts', "", config).then(
        function (response) {
            $scope.discounts = response.data.discounts;
            $scope.formModel.discount_id = '';
        });
    };
    $scope.fetchSubjectGroups = function(){
        $http.post(base_url + 'students/get_subject_groups', {batch_id : $scope.formModel.batch}, config).then(
        function (response) {
            $scope.groups = response.data.groups;
            $scope.formModel.subject_group = '';
        });
    };
    $scope.onSubmit = function (valid) {
        $scope.formModel.guardian = $('.js-data-example-ajax').val();
        //console.log($scope.formModel);
        if (valid) {
            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
            if ($scope.resImageDataURI) {
                $scope.formModel.avatar = $scope.resImageDataURI;
            } else {
                $scope.formModel.avatar = null;
            }
            $http.post(base_url + 'students/save', $scope.formModel, config).then(
                    function (success) {
                        $window.scrollTo(0, 0);
                        if (success.data.status === "success") {
                            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                            $scope.formModel = {
                                religion: '',
                                firstname: '',
                                birthPlace: '',
                                nationality: '',
                                language: '',
                                email: '',
                                address: '',
                                city: '',
                                phone: '',
                                dob: '',
                                blood:'',
                                discount_id:'',
                                adm_date:'',
                                ic_number : ''
                            };
                            $scope.resImageDataURI = '';
                            $scope.stdAddmissionForm.$setUntouched();
                            $scope.stdAddmissionForm.$setPristine();
                            $scope.alert.message = success.data.message;
                            $scope.alert.type = "alert-success";
                            $scope.image2 = false;
                            $('.js-data-example-ajax').val(null).trigger('change.select2');
                            //console.log(success.data);
                        } else if (success.data.status === "error") {
                            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                            $scope.alert.message = success.data.message;
                            $scope.alert.type = "alert-danger";
                        }
                    },
                    function (error) {
                        console.log(error.data);
                        //$window.location.href = 'errors/' + error.status;
                    }
            );
            //console.log($scope.formModel);
        }
    };

    $scope.fetchClassBatches = function (class_id) {
        Loading("#frmBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'settings/getClassBatchesAndDiscounts', {id: class_id}, config).then(
                function (success) {
                    Loading("#frmBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.batches = success.data.batches;
                    $scope.formModel.batch = '';
                },
                function (error) {
                    Loading("#frmBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    };

    $scope.fetchDiscounts = function () {
        Loading("#discount_div", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'students/getDiscounts', "", config).then(
                function (success) {
                    Loading("#discount_div", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.discounts = success.data;
                    $scope.formModel.discount_id = '';
                },
                function (error) {
                    Loading("#discount_div", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    };

    /*--- Image cropper ---*/
    $scope.blockingObject = {block: true};
    $scope.callTestFuntion = function () {
        $scope.blockingObject.render(function (dataURL) {
            //console.log($scope.blockingObject);
            //console.log('via render');
            //console.log(dataURL.length);
        });
    };
    $scope.blockingObject.callback = function (dataURL) {
        //console.log('via function');
        //console.log(dataURL.length);
    };
    $scope.size = 'small';
    $scope.type = 'square';
    $scope.imageDataURI = '';
    $scope.resImageDataURI = '';
    $scope.resBlob = {};
    $scope.urlBlob = {};
    $scope.resImgFormat = 'image/jpeg';
    $scope.resImgQuality = 0.9;
    $scope.selMinSize = 100;
    $scope.selInitSize = [{w: 200, h: 80}];
    $scope.resImgSize = [{w: 300, h: 300}, {w: 300, h: 300}];
    //$scope.aspectRatio=1.2;
    $scope.onChange = function ($dataURI) {
        //console.log('onChange fired');
    };
    $scope.onLoadBegin = function () {
        //console.log('onLoadBegin fired');
    };
    $scope.onLoadDone = function () {
        //console.log('onLoadDone fired');
    };
    $scope.onLoadError = function () {
        //console.log('onLoadError fired');
    };
    $scope.getBlob = function () {
        //console.log($scope.resBlob);
    };
    var handleFileSelect = function (evt) {
        var file = evt.currentTarget.files[0],
                reader = new FileReader();
        if (navigator.userAgent.match(/iP(hone|od|ad)/i)) {
            var canvas = document.createElement('canvas'),
                    mpImg = new MegaPixImage(file);

            canvas.width = mpImg.srcImage.width;
            canvas.height = mpImg.srcImage.height;

            EXIF.getData(file, function () {
                var orientation = EXIF.getTag(this, 'Orientation');

                mpImg.render(canvas, {
                    maxHeight: $scope.resImgSize,
                    orientation: orientation
                });
                setTimeout(function () {
                    var tt = canvas.toDataURL("image/jpeg", 1);
                    $scope.$apply(function ($scope) {
                        $scope.imageDataURI = tt;
                    });
                }, 100);
            });
        } else {
            reader.onload = function (evt) {
                $scope.$apply(function ($scope) {
                    //console.log(evt.target.result);
                    $scope.imageDataURI = evt.target.result;
                });
            };
            reader.readAsDataURL(file);
        }
    };
    angular.element(document.querySelector('#fileInput')).on('change', handleFileSelect);
    $scope.$watch('resImageDataURI', function () {
        //console.log('Res image', $scope.resImageDataURI);
    });
    /*--- Image cropper ---*/
});

app2.controller('stdFilterController', function ($scope, $http) {
    $scope.batches = {};

    $scope.message = {};

    $scope.dropzoneConfigsFromStdSide = {
        'options': {// passed into the Dropzone constructor
            'url': base_url + 'messages/upload_attachments',
            'autoProcessQueue': false,
            'autoDiscover': false,
            'uploadMultiple': true,
            'parallelUploads': 5,
            'maxFilesize': 20, //MB
            'maxFiles': 100,
            'acceptedFiles': "image/*, application/pdf, .doc, .docx",
            'addRemoveLinks': true,
            'dictRemoveFile': '<?php echo lang("lbl_remove");?>',
            'dictDefaultMessage': '<?php echo lang("drop_here");?>',
            'dictFileTooBig': 'File is bigger than 20MB',
            init: function () {
                $('#message_alert').hide();
                var submitButton = document.querySelector("#saveButton");
                myDropzone = this;
                submitButton.addEventListener("click", function () {
                    $scope.message.to = document.getElementById("hidden").value.split(" ");;
                    $scope.message.role = "3";
                    if ($('.textarea_editor').val() == "" || typeof ($scope.message.to) == "undefined" ||
                            $scope.message.to == "" || typeof ($scope.message.subject) == "undefined" || $scope.message.subject == ""){
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger')
                                .html('<?php echo lang("to_req") ?>').show();

                    }else if (myDropzone.files.length == 0){
                        $scope.startConversation();
                        
                    }else if (myDropzone.files.length != 0){
                        myDropzone.processQueue();
                    }   

                });


                myDropzone.on("complete", function (file) {
                    if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {

                        $scope.startConversation();
                    }
                });


            }
        },
        'eventHandlers': {
            'sending': function (file, xhr, formData) {
            },
            'success': function (file, response) {
                $scope.message.files = response;


            }
        }
    };
    $scope.startConversation = function () {
        $('#saveButton').prop('disabled', true);
        Loading("#compose", '<?php echo lang("loading_datatable") ?>', "", "show");
        var text = $('.textarea_editor').val();
        $scope.message.text = text;
        console.log($scope.message);
        $http.post(base_url + "messages/startConversation/", $scope.message).then(
                function (response) {
                    
                    if (response.data.success) {
                        $('#saveButton').prop('disabled', false);
                        $('#message_alert').removeClass('alert-danger').addClass('alert-success').html(response.data.message).show();
                        Loading("#compose", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.message = {};
                        $('.textarea_editor').data("wysihtml5").editor.clear();
                        $('.js-data-example-ajax').val(null).trigger('change.select2');
                        $('#message_alert').hide();

                        showNotification('<?php echo lang("success_app") ?>', response.data.message, "success");
                        var otherData = {};    
                        publicNotificationViaPusher('new_conversation', otherData, response.data.part, 'messages/view/' + response.data.con_id, {'sender': response.data.sender});
                        

                    } else {
                        $('#saveButton').prop('disabled', false);
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html(response.data.message).show();
                        Loading("#compose", '<?php echo lang("loading_datatable") ?>', "", "hide");

                    }
                    $scope.resetCompose();
                },
                function (error) {
                   
                }
        );

    }

    $scope.resetCompose = function () {
        $scope.message = {};
        $('.textarea_editor').data("wysihtml5").editor.clear();
        $('.js-data-example-ajax').val(null).trigger('change.select2');
        $('#message_alert').hide();
        Dropzone.forElement("#my-awesome-dropzone").removeAllFiles(true);
        $('#compose').modal('hide');
    }
    
    $scope.fetchClassBatches = function (class_id) {
        Loading("#dropdownBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'attendance/getClassBatches', {class_id: class_id}, config).then(
                function (success) {
                    Loading("#dropdownBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.batches = success.data;
                    $scope.batch = 'all';
                },
                function (error) {
                    Loading("#dropdownBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    };
    $scope.fetchAllStdsOfClassAndBatch = function (class_id, batch_id, status, firsttime = 'false') {
        Loading("#std_search_filter", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'students/getClassBatchesStudents', {class_id: class_id, batch_id: batch_id, status: status}, config).then(
                function (success) {
                    Loading("#std_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    if(success.data !== 'null'){
                        $("#stdTableContianerError").css('display','none');
                        $("#stdTableContianer").html(success.data);
                        $("#stdTableContianer").css('display','block');
                    }else{
                        if(firsttime == 'false'){
                            $("#stdTableContianer").css('display','none');
                            $("#stdTableContianerError").css('display','block');
                        }
                    }
                },
                function (error) {
                    Loading("#std_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    };
});

app2.controller('stdAdmissionEditController', function ($scope, $http, $window, $location) {
    $scope.formModel = {};
    $scope.batches = {};
    $scope.alert = {};
    $scope.discounts = {};
    $scope.subjects = {};
    $scope.message = {};

    $scope.loadDiscounts = function(){
        $http.post(base_url + 'fee/get_discounts', "", config).then(
        function (response) {
            $scope.discounts = response.data.discounts;
        });
    };
    // reset student password in student profile
    $scope.changePassword = function (id) {
        $http.post(base_url + 'students/changeStudentPassword', $scope.formModel, config).then(
           
               function (success)
               {
                //Loading("#change_alert", '<?php echo lang("loading_datatable") ?>', "", "hide");
                   $scope.message = success.data;
                    $mes = success.data.message;
                   if($mes==1){
                      // $window.alert("Password Chanege Sucess!!!");
                      $('#change_alert').removeClass('alert-danger').addClass('alert-success').show();
                        $('#alert_msg').html("<?php echo lang('pswrd_changed_success') ?>");
                        $scope.formModel.password = '';
                        $scope.formModel.confirm_password = '';
                   }
                   else if($mes==2){
                   // $window.alert("Password and Confirm Password Don't Match!");
                   $('#change_alert').removeClass('alert-success').addClass('alert-danger').show();                       
                        $('#alert_msg').html("<?php echo lang('lbl_password_exist_error') ?>");
                        $scope.formModel.password = '';
                        $scope.formModel.confirm_password = '';
                 }
                   else{
                   // $window.alert("Password and Confirm Password Don't Match!");
                   $('#change_alert').removeClass('alert-success').addClass('alert-danger').show();                       
                        $('#alert_msg').html("<?php echo lang('lbl_password_error') ?>");
                        $scope.formModel.password = '';
                        $scope.formModel.confirm_password = '';
                 }
               },
               function (error)
               {
                //Loading("#change_alert", '<?php echo lang("loading_datatable") ?>', "", "hide");
                   console.log(error.data);
               }


        );
    };
    $scope.changePasswordStudent = function (id) {
        $scope.formModel.id=id;
        $http.post(base_url + 'students/changeStudentPassword2', $scope.formModel, config).then(
           
               function (success)
               {
                //Loading("#change_alert", '<?php echo lang("loading_datatable") ?>', "", "hide");
                   $scope.message = success.data;
                    $mes = success.data.message;
                   if($mes==1){
                      // $window.alert("Password Chanege Sucess!!!");
                      $('#change_alert').removeClass('alert-danger').addClass('alert-success').show();
                        $('#alert_msg').html("<?php echo lang('pswrd_changed_success') ?>");
                        $scope.formModel.c_password = '';
                        $scope.formModel.n_password = '';
                        $scope.formModel.co_password = '';
                   }
                   else if($mes==2){
                   // $window.alert("Password and Confirm Password Don't Match!");
                   $('#change_alert').removeClass('alert-success').addClass('alert-danger').show();                       
                        $('#alert_msg').html("<?php echo lang('lbl_current_pass_error') ?>");
                        $scope.formModel.c_password = '';
                        $scope.formModel.n_password = '';
                        $scope.formModel.co_password = '';
                 }
                   else if($mes==3){
                   // $window.alert("Password and Confirm Password Don't Match!");
                   $('#change_alert').removeClass('alert-success').addClass('alert-danger').show();                       
                        $('#alert_msg').html("<?php echo lang('lbl_password_exist_error') ?>");
                        $scope.formModel.c_password = '';
                        $scope.formModel.n_password = '';
                        $scope.formModel.co_password = '';
                 }
                   else{
                   // $window.alert("Password and Confirm Password Don't Match!");
                   $('#change_alert').removeClass('alert-success').addClass('alert-danger').show();                       
                        $('#alert_msg').html("<?php echo lang('lbl_password_error') ?>");
                        $scope.formModel.c_password = '';
                        $scope.formModel.n_password = '';
                        $scope.formModel.co_password = '';
                 }
               },
               function (error)
               {
                //Loading("#change_alert", '<?php echo lang("loading_datatable") ?>', "", "hide");
                   console.log(error.data);
               }


        );
    };

    
    $scope.fetchStudent = function (id) {
        $http.post(base_url + 'students/getStudent', {'student_id': id}, config).then(
                function (success) {
                    $scope.formModel = success.data;
                   
                    $scope.formModel.group = $scope.formModel.group_id;
                    if($scope.formModel.group == 0 || $scope.formModel.group == null){
                    $scope.formModel.group = '';
                }
                    $scope.groups = $scope.formModel.groups;
                    if($scope.formModel.country==0){
                        $scope.formModel.country='';
                    }
                    if($scope.formModel.discount_id == 0){
                        $scope.formModel.discount_id = '';
                }
                    if($scope.formModel.nationality==0){
                        $scope.formModel.nationality='';
                    }
                    //console.log(success.data);
                    $scope.formModel.guardian = [success.data.parentId];
                    if($scope.formModel.deleted_at != null){
                        $scope.formModel.guardian = null;
                        $scope.formModel.pRelation = "";
                    }
                    if($scope.formModel.discount_id == '0'){
                        $scope.formModel.discount_id = '';
                    }
                },
                function (error) {
                    //console.log(error.data)
                    $window.location.href = 'errors/' + error.status;
                }
        );
    };
    $scope.formModel.chgStdImage = false;
    $scope.formModel.chgParentImage = false;
    $scope.onSubmit = function (valid, image1) {

        $scope.formModel.guardian = $('.js-data-example-ajax').val();


        if (valid) {
            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
            if ($scope.resImageDataURI) {
                $scope.formModel.image2 = $scope.resImageDataURI;
                $scope.formModel.chgStdImage = true;
            }


            $http.post(base_url + 'students/update', $scope.formModel, config).then(
                    function (success) {
                        if (success.data.status === "success") {
                            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                            $window.scrollTo(0, 0);
                            $scope.alert.message = success.data.message;
                            $scope.alert.type = "alert-success";

                        } else if (success.data.status === "error") {
                            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                            $scope.alert.message = success.data.message;
                            $scope.alert.type = "alert-danger";
                            showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                        }
                    },
                    function (error) {
                        console.log(error.data);
                        //$window.location.href = 'errors/' + error.status;
                    }
            );
            //console.log($scope.formModel);
        }
    };

    $scope.fetchClassBatches = function (class_id) {
        Loading("#frmEditBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'settings/getClassBatchesAndDiscounts', {id: class_id}, config).then(
                function (success) {
                    Loading("#frmEditBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.batches = success.data.batches;
                },
                function (error) {
                    Loading("#frmEditBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    };

    $scope.fetchDiscounts = function () {
        Loading("#discount_div", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'students/getDiscounts', "", config).then(
                function (success) {
                    Loading("#discount_div", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.discounts = success.data;
                    //$scope.formModel.discount_id = '';
                },
                function (error) {
                    Loading("#discount_div", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    };

    /*--- Image cropper ---*/
    $scope.blockingObject = {block: true};
    $scope.callTestFuntion = function () {
        $scope.blockingObject.render(function (dataURL) {
            //console.log($scope.blockingObject);
            //console.log('via render');
            //console.log(dataURL.length);
        });
    };
    $scope.blockingObject.callback = function (dataURL) {
        //console.log('via function');
        //console.log(dataURL.length);
    };
    $scope.size = 'small';
    $scope.type = 'square';
    $scope.imageDataURI = '';
    $scope.resImageDataURI = '';
    $scope.resBlob = {};
    $scope.urlBlob = {};
    $scope.resImgFormat = 'image/jpeg';
    $scope.resImgQuality = 0.9;
    $scope.selMinSize = 100;
    $scope.selInitSize = [{w: 200, h: 80}];
    $scope.resImgSize = [{w: 300, h: 300}, {w: 300, h: 300}];
    //$scope.aspectRatio=1.2;
    $scope.onChange = function ($dataURI) {
        //console.log('onChange fired');
    };
    $scope.onLoadBegin = function () {
        //console.log('onLoadBegin fired');
    };
    $scope.onLoadDone = function () {
        //console.log('onLoadDone fired');
    };
    $scope.onLoadError = function () {
        //console.log('onLoadError fired');
    };
    $scope.getBlob = function () {
        //console.log($scope.resBlob);
    };
    var handleFileSelect = function (evt) {
        var file = evt.currentTarget.files[0],
                reader = new FileReader();
        if (navigator.userAgent.match(/iP(hone|od|ad)/i)) {
            var canvas = document.createElement('canvas'),
                    mpImg = new MegaPixImage(file);

            canvas.width = mpImg.srcImage.width;
            canvas.height = mpImg.srcImage.height;

            EXIF.getData(file, function () {
                var orientation = EXIF.getTag(this, 'Orientation');

                mpImg.render(canvas, {
                    maxHeight: $scope.resImgSize,
                    orientation: orientation
                });
                setTimeout(function () {
                    var tt = canvas.toDataURL("image/jpeg", 1);
                    $scope.$apply(function ($scope) {
                        $scope.imageDataURI = tt;
                    });
                }, 100);
            });
        } else {
            reader.onload = function (evt) {
                $scope.$apply(function ($scope) {
                    //console.log(evt.target.result);
                    $scope.imageDataURI = evt.target.result;
                });
            };
            reader.readAsDataURL(file);
        }
    };
    angular.element(document.querySelector('#fileInput')).on('change', handleFileSelect);
    $scope.$watch('resImageDataURI', function () {
        //console.log('Res image', $scope.resImageDataURI);
    });
    /*--- Image cropper ---*/
});

function findObjectByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return array[i];
        }
    }
    return null;
}

function toTime(timeString) {
    var timeTokens = timeString.split(':');
    return new Date(1970, 0, 1, timeTokens[0], timeTokens[1], timeTokens[2]);
}

app2.controller("attendanceController", function ($scope, $http, $window, $location) {
    $scope.classes = {};
    $scope.batches = {};
    $scope.students = [];
    $scope.statuss = {};
    $scope.message;
    $scope.selectedDate;
    $scope.attendModel = {};
    $scope.attendModelP = {};
    $scope.data = [];
    $scope.comments = {};

    $scope.initClasses = function () {
        Loading("#attFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'attendance/getClasses', "", config).then(
                function (success) {
                    Loading("#attFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.classes = success.data;
                    //sconsole.log(success.data);
                },
                function (error) {
                    Loading("#attFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
        );
    };
    
    $scope.initBatches = function (class_id) {
        Loading("#attFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (class_id) {
            Loading("#attFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'attendance/getClassBatches', {class_id: class_id}, config).then(
                    function (success) {
                        Loading("#attFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.batches = success.data;
                        $scope.filterModel.batch_id = "";
                    },
                    function (error) {
                        console.log(error.data);
                        //Loading("#attFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        //$window.location.href = 'errors/' + error.status;
                    }
            );
        }
    };
    
    $scope.onSubmit = function (valid) {
       if (valid) {
           Loading("#att_search_filter", '<?php echo lang("loading_datatable") ?>', "", "show");
           $http.post(base_url + 'attendance/fetchStudentsAttendance', $scope.filterModel, config).then(
                   function (success) {
                       Loading("#att_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                       $scope.students = success.data.students;
                       $scope.students_marked = success.data.students_marked;
                       $scope.students_pending = success.data.students_pending;
                       $scope.message = success.data.message;
                       $scope.disable = success.data.disable;
                       
                       if($scope.disable === "TRUE") {
                           $("#attStudentsTable").keydown(function (event) { return false; });
                       } else {
                           $("#attStudentsTable").keydown(function (event) { return true; });
                       }
                       
                       $scope.selectedDate = $scope.filterModel.date;
                       $scope.action = success.data.edit;
                       $('body').tooltip({
                            selector: '[rel=tooltip]'
                        });
                   },
                   function (error) {
                       Loading("#att_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $window.location.href = 'errors/' + error.status;
                       // console.log(erro.status);
                   }
           );
       }
   };

   $scope.onSubmit2 = function (class_id,batch_id,date) {
       
           Loading("#att_search_filter", '<?php echo lang("loading_datatable") ?>', "", "show");
           $http.post(base_url + 'attendance/fetchStudentsAttendance', {class_id:class_id,batch_id:batch_id,date:date}, config).then(
                   function (success) {
                       Loading("#att_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                       $scope.students = success.data.students;
                       $scope.message = success.data.message;
                       $scope.disable = success.data.disable;
                       $scope.selectedDate = $scope.filterModel.date;
                       $scope.action = success.data.edit;
                     
                   },
                   function (error) {
                       Loading("#att_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $window.location.href = 'errors/' + error.status;
                       // console.log(erro.status);
                   }
           );
       
   };
   
    $scope.inProcessAttendance = function(){
    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
    $scope.filterModel.reason = $scope.requestText;
    if($scope.requestText != null){
            $http.post(base_url + 'attendance/inProcessAttendance', $scope.filterModel, config).then(
                function (success) {
                    $('.edit_attendance_request_model').modal('hide');
                    if(success.data.status === 'success'){
                        showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                        $scope.selectedDate = $scope.filterModel.date;
                        $scope.disable = success.data.disable;
                        $scope.action = success.data.edit;
                        $scope.r_id = success.data.r_id;
                        var otherData = {class_id:$scope.filterModel.class_id, batch_id: $scope.filterModel.batch_id};
                        $scope.getSchoolAdmins(otherData);
                        $scope.requestText ="";
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
            });
        }else{
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
        $('#request_error').show();
    }
    };

    $scope.resetModalDashboard = function(){
        $scope.filterModel = {};
        $scope.attFilterForm.$setUntouched();
        $scope.attFilterForm.$setPristine();
        $scope.students = [];
        $scope.students_marked = [];
        $scope.students_pending = [];
        $scope.message = '';
    }

    $scope.inProcessAttendance2 = function(){
    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
    $scope.filterModel.reason = $scope.requestText;
    if($scope.requestText != null){
            $http.post(base_url + 'attendance/inProcessAttendance', $scope.filterModel, config).then(
                function (success) {
                    $('.edit_attendance_request_model').modal('hide');
                    $('#attendanceModal').modal('toggle');
                    if(success.data.status === 'success'){
                        showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                        $scope.selectedDate = $scope.filterModel.date;
                        $scope.disable = success.data.disable;
                        $scope.action = success.data.edit;
                        $scope.r_id = success.data.r_id;
                        var otherData = {class_id:$scope.filterModel.class_id, batch_id: $scope.filterModel.batch_id};
                        $scope.getSchoolAdmins(otherData);
                        $scope.requestText ="";
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
            });
        }else{
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
        $('#request_error').show();
    }
    };

   $scope.getSchoolAdmins = function(otherData){
      $http.post(base_url + 'syllabus/getSchoolAdmins', {}, config).then(
          function(success){
              publicNotificationViaPusher("lbl_approval_student_atttence", otherData,  success.data.new_ids, "applications/all", {'sender': success.data.sender}, $scope.r_id);
          },
          function(error){
               console.log(error.data);
               //$window.location.href = 'errors/' + error.status;
          }
      );
  };
   
   $scope.saveAttendance = function (valid) {
      if (valid) {
          $scope.data = [];
          angular.forEach($scope.students, function (value, key) {
              $scope.data.push({id: value.id, class_id: value.class_id, batch_id: value.batch_id, date: $scope.filterModel.date, status: 'Present'});
          });

           if ($scope.attendModel.statuss) {
               angular.forEach($scope.data, function (val, key) {
                   angular.forEach($scope.attendModel.statuss, function (val2, key2) {
                       if (val.id === key2) {
                          val.status = val2;
                       }
                   });
               });
           }
           if($scope.attendModel.comments){
              angular.forEach($scope.data, function (val, key) {
                  angular.forEach($scope.attendModel.comments, function (val2, key2) {
                      if (val.id === key2) {
                           val.comment = val2;
                       }

                  });
              });
          }else{
          angular.forEach($scope.data, function (val, key) {

                           val.comment = '';

           });
      }

          Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
          $http.post(base_url + 'attendance/save', $scope.data, config).then(
                  function (success) {
                       $('#confirmModal').modal('toggle');
                       $scope.onSubmit($scope.data[0].class_id,$scope.data[0].batch_id,$scope.data[0].date);

                      Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                      showNotification('<?php echo lang("success_app") ?>', success.data.message, success.data.status);
                      $scope.attendModel = {};
                  },
                  function (error) {
                      Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                      showNotification('<?php echo lang("error_app") ?>', error.data.message, error.data.status);
                      $window.location.href = 'errors/' + error.status;
                  }
          );
      }
   };
   $scope.savePendingAttendance = function (valid) {
      if (valid) {
          $scope.data = [];
          angular.forEach($scope.students_pending, function (value, key) {
              $scope.data.push({id: value.id, class_id: value.class_id, batch_id: value.batch_id, date: $scope.filterModel.date, status: 'Present'});
          });

           if ($scope.attendModelP.statuss) {
               angular.forEach($scope.data, function (val, key) {
                   angular.forEach($scope.attendModelP.statuss, function (val2, key2) {
                       if (val.id === key2) {
                          val.status = val2;
                       }
                   });
               });
           }
           if($scope.attendModelP.comments){
              angular.forEach($scope.data, function (val, key) {
                  angular.forEach($scope.attendModelP.comments, function (val2, key2) {
                      if (val.id === key2) {
                           val.comment = val2;
                       }

                  });
              });
          }else{
          angular.forEach($scope.data, function (val, key) {

                           val.comment = '';

           });
      }
             Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
          $http.post(base_url + 'attendance/save', $scope.data, config).then(
                  function (success) {
                       $('#confirmModalP').modal('toggle');
                       $scope.onSubmit($scope.data[0].class_id,$scope.data[0].batch_id,$scope.data[0].date);

                      Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                      showNotification('<?php echo lang("success_app") ?>', success.data.message, success.data.status);
                      $scope.attendModelP = {};
                  },
                  function (error) {
                      Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                      showNotification('<?php echo lang("error_app") ?>', error.data.message, error.data.status);
                      console.log(error.data);
                      //$window.location.href = 'errors/' + error.status;
                  }
          );

          
      }
   };

   $scope.savePendingAttendance2 = function (valid) {
      if (valid) {
          $scope.data = [];
          angular.forEach($scope.students_pending, function (value, key) {
              $scope.data.push({id: value.id, class_id: value.class_id, batch_id: value.batch_id, date: $scope.filterModel.date, status: 'Present'});
          });

           if ($scope.attendModelP.statuss) {
               angular.forEach($scope.data, function (val, key) {
                   angular.forEach($scope.attendModelP.statuss, function (val2, key2) {
                       if (val.id === key2) {
                          val.status = val2;
                       }
                   });
               });
           }
           if($scope.attendModelP.comments){
              angular.forEach($scope.data, function (val, key) {
                  angular.forEach($scope.attendModelP.comments, function (val2, key2) {
                      if (val.id === key2) {
                           val.comment = val2;
                       }

                  });
              });
          }else{
          angular.forEach($scope.data, function (val, key) {

                           val.comment = '';

           });
      }
             Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
          $http.post(base_url + 'attendance/save', $scope.data, config).then(
                  function (success) {
                       $('#confirmModalP').modal('toggle');
                       $('#attendanceModal').modal('toggle');
                       $scope.onSubmit($scope.data[0].class_id,$scope.data[0].batch_id,$scope.data[0].date);

                      Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                      showNotification('<?php echo lang("success_app") ?>', success.data.message, success.data.status);
                      $scope.attendModelP = {};
                  },
                  function (error) {
                      Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                      showNotification('<?php echo lang("error_app") ?>', error.data.message, error.data.status);
                      console.log(error.data);
                      //$window.location.href = 'errors/' + error.status;
                  }
          );

          
      }
   };
});

app2.controller("arController", function ($scope, $http, $window, $location, $sce) {
    $scope.classes = {};
    $scope.batches = {};
    $scope.months = [];
    
    $scope.initMonths = function(){
        Loading("#arFilterMonth", '<?php echo lang("loading_datatable") ?>', "", "hide");
        $http.post(base_url + 'attendance/count_academic_year_months', "", config).then(
                function (success) {
                    Loading("#arFilterMonth", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.months = success.data;
                },
                function (error) {
                    Loading("#arFilterMonth", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.status);
                    //$window.location.href = 'errors/' + error.status;
                }
        );
    };
    
    //$scope.months = [
    //  {id: '01', name: 'jan'},
    //    {id: '02', name: 'feb'},
    //   {id: '03', name: 'mar'},
    //  {id: '04', name: 'apr'},
    //    {id: '05', name: 'may'},
    //    {id: '06', name: 'jun'},
    //    {id: '07', name: 'jul'},
    //    {id: '08', name: 'aug'},
    //    {id: '09', name: 'sep'},
    //    {id: '10', name: 'oct'},
    //    {id: '11', name: 'nov'},
    //    {id: '12', name: 'dec'}
    // ];
    $scope.report = {};
    $scope.range = [];
    $scope.finalReport = {};
    $scope.initClasses = function () {
        Loading("#arFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'attendance/getClasses', "", config).then(
                function (success) {
                    Loading("#arFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.classes = success.data;
                },
                function (error) {
                    Loading("#arFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
        );
    };
    $scope.initBatches = function (class_id) {
        Loading("#arFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (class_id) {
            Loading("#arFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'attendance/getClassBatches', {class_id: class_id}, config).then(
                    function (success) {
                        Loading("#arFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.batches = success.data;
                        $scope.arModel.batch_id = "";
                    },
                    function (error) {
                        Loading("#arFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $window.location.href = 'errors/' + error.status;
                    }
            );
        }
    };
    $scope.onSubmitFetchReport = function (valid) {
        if (valid) {
            /*angular.forEach($scope.batches, function (value) {
                if (value.id === $scope.arModel.batch_id) {
                    $scope.arModel.academic_year_id = value.academic_year_id;
                }
            });*/
            Loading("#attReport_search_filter", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + "attendance/generate_report", $scope.arModel, config).then(
                    function (success) {
                        Loading("#attReport_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.finalReport = success.data.att;
                        //console.log($scope.finalReport);
                    },
                    function (error) {
                        Loading("#attReport_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        console.log(error.data);
                    }
            );
        }
    };

    $scope.getRange = function (total) {
        var range = [];
        for (var i = 1; i <= total; i++) {
            range.push(i);
        }
        $scope.range = range;
    };
});

app2.controller("timeTableCtrl", function ($scope, $http, $window, $location, $sce) {
    $scope.classes = {};
    $scope.batches = {};
    $scope.tbModel = {};
    $scope.ttModel = {};
    $scope.etModel = {};
    $scope.crModel = {};
    $scope.timeTable = {};
    $scope.subjects = {};
    $scope.selectedCBSubjects = {};
    $scope.periods = {};
    $scope.error;
    $scope.yModel = {};
    $scope.yyModel = {};

    $scope.getFormatedTime = function (time) {
        var date = new Date(time);
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var ampm = hours >= 12 ? 'pm' : 'am';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0' + minutes : minutes;
        var strTime = hours + ':' + minutes + ' ' + ampm;
        return strTime;
    };

    $scope.getSubjects = function (class_id, batch_id) {
        $http.post(base_url + "timetable/getSubjects", {class_id: class_id, batch_id: batch_id}, config).then(
                function (success) {
                    //console.log(success.data);
                    $scope.selectedCBSubjects = success.data;
                }, function (error) {
            console.log(error.data);
        }
        );
    };

    $scope.initClasses = function () {
        Loading("#tbFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'attendance/getClasses', "", config).then(
                function (success) {
                    Loading("#tbFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.classes = success.data;
                },
                function (error) {
                    Loading("#tbFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
        );
    };

    $scope.initBatches = function (class_id) {
        Loading("#tbFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (class_id) {
            Loading("#tbFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'attendance/getClassBatches', {class_id: class_id}, config).then(
                    function (success) {
                        Loading("#tbFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.batches = success.data;
                        $scope.tbModel.batch_id = "";
                    },
                    function (error) {
                        Loading("#tbFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $window.location.href = 'errors/' + error.status;
                    }
            );
        }
    };

    $scope.fetchSubjects = function (valid) {
        if (valid) {
            Loading("#timetable_search_filter", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'timetable/getSubjectsWiseTimeTable', {class_id: $scope.tbModel.class_id, batch_id: $scope.tbModel.batch_id}, config).then(
                    function (success) {
                        Loading("#timetable_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        if (success.data.status === "success") {
                            $scope.timeTable = success.data.timetables;
                            $scope.periods = success.data.periods;
                            $scope.error = false;
                        } else if (success.data.status === "error") {
                            $scope.error = true;
                            $scope.error = success.data;
                        }
                    },
                    function (error) {
                        Loading("#timetable_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        console.log(error.data);
                        //$window.location.href = 'errors/' + error.status;
                    }
            );
        }
    };

    $scope.selectedValues = function (obj) {
        $scope.crModel = obj;
        $scope.getSubjects($scope.crModel.class_id, $scope.crModel.batch_id);
    };

    $scope.selectedValues2 = function (obj) {
        $scope.editTimetableForm.$setUntouched();
        $scope.editTimetableForm.$setPristine();
        $scope.etModel = obj;
        $scope.etModel.new_room_no = obj.room_no;
        $scope.getSubjects($scope.etModel.class_id, $scope.etModel.batch_id);
    };

    $scope.onSubmitAddTimeTable = function (valid) {
        if (valid) {
            $scope.yModel.peroid_id = $scope.addTimetableForm.$$element[0].peroid_id.value;
            $scope.yModel.day_of_week = $scope.addTimetableForm.$$element[0].day_of_week.value;
            $scope.yModel.subject_id = $scope.addTimetableForm.$$element[0].subject_id.value;
            $scope.yModel.room_no = $scope.addTimetableForm.$$element[0].room_no.value;
            
            $http.post(base_url + 'timetable/save', $scope.yModel, config).then(
                    function (success) {
                        if (success.data.status === "success") {
                            showNotification(success.data.status, success.data.message, success.data.status);
                            $('.add-time-table-model').modal('hide');
                            $scope.addTimetableForm.$setUntouched();
                            $scope.addTimetableForm.$setPristine();
                            $scope.fetchSubjects(true);
                            var n =success.data.notifications;
                            var otherData = {class_id:$scope.tbModel.class_id,batch_id:$scope.tbModel.batch_id,subject_id:$scope.yModel.subject_id};
                            publicNotificationViaPusher(n.keyword, otherData, n.id, 'notification/index', n.data);
                            $scope.yModel = {};
                        } else if (success.data.status === "error") {
                            showNotification(success.data.status, success.data.message, success.data.status);
                        }
                    },
                    function (error) {
                        console.log(error.data);
                    }
            );
        }
    };

    $scope.onSubmitUpdateTimetable = function (valid) {
       if (valid) {
           $scope.yyModel.timetable_id = $scope.editTimetableForm.$$element[0].timetable_id.value;
           $scope.yyModel.peroid_id = $scope.editTimetableForm.$$element[0].edit_peroid_id.value;
           $scope.yyModel.day_of_week = $scope.editTimetableForm.$$element[0].edit_day_of_week.value;
           $scope.yyModel.subject_id = $scope.editTimetableForm.$$element[0].edit_subject_id.value;
           $scope.yyModel.room_no = $scope.editTimetableForm.$$element[0].edit_room_no.value;

           $http.post(base_url + "timetable/update", $scope.yyModel, config).then(
                   function (success) {
                       if (success.data.status === "success") {
                           showNotification(success.data.status, success.data.message, success.data.status);
                           $('.edit-time-table-model').modal('hide');
                           $scope.editTimetableForm.$setUntouched();
                           $scope.editTimetableForm.$setPristine();
                           $scope.fetchSubjects(true);
                           var notifications =success.data.notifications;
                            angular.forEach(notifications, function (n) {
                                var otherData = {class_id:$scope.tbModel.class_id,batch_id:$scope.tbModel.batch_id,subject_id:$scope.yyModel.subject_id};
                                publicNotificationViaPusher(n.keyword, otherData, n.id, 'notification/index', n.data);
                            });
                            $scope.yyModel = {};
                       } else if (success.data.status === "error") {
                           showNotification(success.data.status, success.data.message, success.data.status);
                       }
                   },
                   function (error) {
                       console.log(error.data);
                   }
           );
       }
   };
});

app2.controller("asController", function ($scope, $http, $window) {
    $scope.classes = {};
    $scope.batches = {};
    $scope.teachers = {};
    $scope.subjects = [];
    $scope.alreadyExists = {};
    $scope.selectedSubjectsToAssign = '0';
    $scope.asSelectedOption = 0;
    $scope.asSelectedSubjectID = 0;

    $scope.fetchClasses = function () {
        Loading("#assignteacherClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'attendance/getClasses', "", config).then(
            function (success) {
                Loading("#assignteacherClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.classes = success.data;
            },
            function (error) {
                Loading("#assignteacherClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };
    $scope.loadClassBatches = function (id) {
        Loading("#assignteacherBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'attendance/getClassBatches', {class_id: id}, config).then(
            function (success) {
                Loading("#assignteacherBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.batches = success.data;
                $scope.selecedVal22 = '';
            },
            function (error) {
                Loading("#assignteacherBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };

    $scope.onSubmitFetchSubAndThr = function (valid) {
        if (valid) {
            Loading("#assignteacherfilterContainer", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + "assignsubjects/getSubjsThrs", {class_id: $scope.selecedVal11, batch_id: $scope.selecedVal22}, config).then(
                function (success) {
                    Loading("#assignteacherfilterContainer", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.subjects = success.data.subjects;
                    $scope.teachers = success.data.teachers;
                }, function (error) {
                    Loading("#assignteacherfilterContainer", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
            );
        }
    };

    $scope.setSelectedOptionValue = function(opt, subject_id, ta_id){
        $scope.asSelectedOption = opt;
        $scope.asSelectedSubjectID = subject_id;
        console.log(ta_id);
        if(ta_id != null){
            $scope.selectedSubjectsToAssign = ta_id;
            $('.yasir-assignsubjects-select2').val(ta_id).trigger('change.select2');
        }
    };

    $scope.saveSubjectAssignments = function (valid) {
        if (valid) {
            var formData = {
                user_id: $scope.selectedSubjectsToAssign,
                class_id: $scope.selecedVal11,
                batch_id:$scope.selecedVal22,
                subject_id: $scope.asSelectedSubjectID,
                option: $scope.asSelectedOption
            };
            $http.post(base_url + "assignsubjects/save", formData, config).then(
                function (success) {
                    if (success.data.status === "success") {
                        showNotification(success.data.status, success.data.message, success.data.status);
                        $scope.onSubmitFetchSubAndThr(true);
                        var notifications =success.data.notifications;
                        angular.forEach(notifications, function (n) {
                            var otherData = {class_id:$scope.selecedVal11,batch_id:$scope.selecedVal22, subject_id:n.subject_id};
                            publicNotificationViaPusher(n.keyword, otherData, n.id, 'notification/index', n.data);
                        });
                        $scope.selectedSubjectsToAssign = '0';
                        $scope.asSelectedOption = 0;
                        $("#exampleModal").modal("hide");
                    } else if (success.data.status === "error") {
                        showNotification(success.data.status, success.data.message, success.data.status);
                    }
                }, function (error) {
                    console.log(error.data);
                }
            );
        }
    };
    $scope.selectedValues = function (user, sub_id) {
        $scope.selectedSubjectsToAssign[sub_id] = user;
    };
});

app2.controller("feetypeConroller", function ($scope, $http, $window) {
    $scope.classes = {};
    $scope.fModel = {};
    $scope.adModel = {due_date: null};
    $scope.editModel = {};
    $scope.updatedModel = {};
    $scope.classFeetypes = {};
    $scope.adModel.checkall = {};
    $scope.countries = {};
    $scope.feeTypes = [];
    $scope.selectedFeeTypeId = 0;
    $scope.selectedFeeTypeVarients = [];
    $scope.vModel = {};
    $scope.editVariant = {};

    $scope.setEditVariant = function(obj){
        $scope.editVariant = angular.copy(obj);
        $scope.editVariant.percentage = parseInt($scope.editVariant.percentage);
    }

    $scope.updateVariant = function(valid){
        if(valid){
            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'fee/updateVariant', $scope.editVariant, config).then(
                    function (success) {
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $('#editFeeVariants').modal('hide');
                        $scope.getFeetypeVarients($scope.current_fee);
                        showNotification("Success", "Variant updated successfully", "success");
                    },
                    function (error) {
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $window.location.href = 'errors/' + error.status;
                    }
            );
        }
    }
    
    $scope.initClasses = function () {
        //Loading("#feesetupClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'attendance/getClasses', "", config).then(
                function (success) {
                    //Loading("#feesetupClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.classes = success.data;
                },
                function (error) {
                    //Loading("#feesetupClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
        );
    };
    
    $scope.initCountries = function () {
        $http.post(base_url + 'fee/getCountries', "", config).then(
            function (success) {
                $scope.countries = success.data;
            },
            function (error) {
                console.log(error.data);
            }
        );
    };
    
    $scope.saveSelectFeetypeID = function(id){
        $scope.current_fee = id;
        $scope.getFeetypeVarients(id);
    }
    
    $scope.getFeetypeVarients = function(id){
        $scope.vModel = {};
        $scope.vModel.fee_types = [id];
        $('.js-data-example-ajax').val(null).trigger('change.select2');
        $('.feetypes_select2').val([id]).trigger('change.select2');
        $scope.addFeeVariantsForm.$setUntouched();
        $scope.addFeeVariantsForm.$setPristine();
        $http.post(base_url + 'fee/getFeetypeVarients', {id:id}, config).then(
            function (success) {
                $scope.selectedFeeTypeVarients = success.data;
                $scope.selectedFeeTypeId = id;
                var temp= $scope.feeTypes.filter(obj => {
                  return obj.id === id
                });
                $scope.selectedFeeTypeName = temp[0].name;
            },
            function (error) {
                console.log(error.data);
            }
        );
    };
    
    $scope.saveVariants = function(){
        $scope.vModel.feetype_id = $scope.selectedFeeTypeId;
        Loading("#addFeeVariants", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'fee/saveVarients', $scope.vModel, config).then(
            function (success) {
                Loading("#addFeeVariants", '<?php echo lang("loading_datatable") ?>', "", "hide");
                if(success.data.status === 'success'){
                    showNotification(success.data.status, success.data.message, success.data.status);
                    $scope.getFeetypeVarients($scope.selectedFeeTypeId);
                    $scope.vModel = {};
                    $scope.vModel.feetype_id = $scope.selectedFeeTypeId;
                    $('.js-data-example-ajax').val(null).trigger('change.select2');
                    $('.feetypes_select2').val(null).trigger('change.select2');
                    $scope.addFeeVariantsForm.$setUntouched();
                    $scope.addFeeVariantsForm.$setPristine();
                }
            },
            function (error) {
                Loading("#addFeeVariants", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };
    
    $scope.deleteVarient = function(id){
        $http.post(base_url + 'fee/deleteVarient', {id:id}, config).then(
            function (success) {
                showNotification("Success", success.data.message, success.data.status);
                $scope.getFeetypeVarients($scope.selectedFeeTypeId);
            },
            function (error) {
                console.log(error.data);
            }
        );
    };
    
    $scope.initFeetypes = function () {
        $http.post(base_url + 'fee/getSchoolFeetypes', "", config).then(
            function (success) {
                $scope.feeTypes = success.data;
            },
            function (error) {
                console.log(error.data);
            }
        );
    };

    $scope.onSubmitFetchClassFeeTypes = function (valid) {
        if (valid) {
            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + "fee/getClassFeetypes", $scope.fModel, config).then(
                    function (success) {
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.classFeetypes = success.data;
                    },
                    function (error) {
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        console.log(error.data);
                    }
            );
        }
    };

    function isInArray(value, array) {
        return Object.values(array).indexOf(value) > -1;
    }

    $scope.select_all = function () {



        angular.forEach($scope.classes, function (cls) {
            id = cls.id;
            $scope.adModel.checkall[id] = $scope.adModel.selectall;
        });


    }

    $scope.saveFeetype = function (valid) {
        if (valid) {

            Loading("#addfeetype-content", '<?php echo lang("loading_datatable") ?>', "", "show");
            if ($scope.adModel.checkall == undefined || !isInArray(true, $scope.adModel.checkall)) {
                Loading("#addfeetype-content", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.adModel.class_error = true;
            } else {
                $scope.adModel.class_error = false;
                $http.post(base_url + "fee/save", $scope.adModel, config).then(
                        function (success) {
                            Loading("#addfeetype-content", '<?php echo lang("loading_datatable") ?>', "", "hide");
                            $('#addfeetype').modal('hide');
                            $scope.onSubmitFetchClassFeeTypes(true);
                            $scope.initFeetypes();
                            $scope.adModel = {};
                            $scope.adModel.checkall = {};
                            $scope.addFeetypeForm.$setUntouched();
                            $scope.addFeetypeForm.$setPristine();
                            showNotification(success.data.status, success.data.message, success.data.status);
                        },
                        function (error) {
                            //Loading("#addfeetype-content", '<?php echo lang("loading_datatable") ?>', "", "hide");
                            console.log(error.data);
                        }
                );
            }
        }
    };

    $scope.setEditValues = function (obj) {
        $scope.editModel = obj;
    };

    $scope.updateFeetype = function (valid) {
        if (valid) {
            Loading("#editfeetype-contents", '<?php echo lang("loading_datatable") ?>', "", "show");
            $scope.updatedModel.id = $scope.editFeetypeForm.$$element[0].id.value;
            $scope.updatedModel.due_date = $scope.editFeetypeForm.$$element[0].due_date.value;
            $scope.updatedModel.name = $scope.editFeetypeForm.$$element[0].name.value;
            $scope.updatedModel.amount = $scope.editFeetypeForm.$$element[0].amount.value;
            $scope.updatedModel.description = $scope.editFeetypeForm.$$element[0].description.value;

            $http.post(base_url + "fee/update", $scope.updatedModel, config).then(
                    function (success) {
                        if (success.data.status === "success") {
                            Loading("#editfeetype-contents", '<?php echo lang("loading_datatable") ?>', "", "hide");
                            $('#eidtFeetypeModal').modal('hide');
                            $scope.editModel = {};
                            $scope.updatedModel = {};
                            $scope.onSubmitFetchClassFeeTypes(true);
                            showNotification(success.data.status, success.data.message, success.data.status);
                        }
                    }, function (error) {
                Loading("#editfeetype-contents", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
            );
            //console.log($scope.updatedModel);
            //
        }
    };

    $scope.showConfirmationAlert = function (id) {
        swal({
            title: '<?php echo lang("are_you_sure") ?>',
            text: '<?php echo lang("delete_message") ?>',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55", confirmButtonText: '<?php echo lang("yes_delete") ?>',
            cancelButtonText: '<?php echo lang("btn_cancel") ?>',
            closeOnConfirm: true,
            closeOnCancel: true
        },
                function (isConfirm) {
                    if (isConfirm) {
                        $http.post(base_url + "fee/softDelete", {"id": id}, config).then(
                                function (success) {
                                    if (success.data.status === "success") {
                                        $scope.onSubmitFetchClassFeeTypes(true);
                                    }
                                },
                                function (error) {
                                    console.log(error.data);
                                }
                        );
                    }
                });
    };
});

app2.controller("duefeeController", function ($scope, $http, $window) {
    $scope.classes = {};
    $scope.batches = {};
    $scope.feetypes = {};
    $scope.dfModel = {};
    $scope.filterModel = {};
    $scope.total_school_fee = "";
    $scope.total_full_paid_fee = "";
    $scope.fully_collected_fee = "";
    $scope.partially_collected_fee = ""; 
    $scope.total_paid_cash = "";
    
    $scope.initClasses = function () {
        //Loading("#dfClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'attendance/getClasses', "", config).then(
                function (success) {
                    //Loading("#dfClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.classes = success.data;
                },
                function (error) {
                    //Loading("#dfClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
        );
    };

    $scope.initBatches = function (class_id) {
        //Loading("#dfBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (class_id) {
            //Loading("#dfBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'attendance/getClassBatches', {class_id: class_id}, config).then(
                    function (success) {
                        //Loading("#dfBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.batches = success.data;
                        $scope.dfModel.batch_id = "";
                    },
                    function (error) {
                        //Loading("#dfBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $window.location.href = 'errors/' + error.status;
                    }
            );
        }
    };

    $scope.filterBatches = function () {
        Loading("#dropdownBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
       
            Loading("#dropdownBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'attendance/getClassBatches', {class_id: $scope.filterModel.class_id}, config).then(
                    function (success) {
                        Loading("#dropdownBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.batches = success.data;
                        $scope.filterModel.batch_id = 'all';
                       
                    },
                    function (error) {
                        Loading("#dropdownBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $window.location.href = 'errors/' + error.status;
                    }
            );
        
    };

    $scope.initFeeTypes = function () {
        //Loading("#dfFeetypes", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + "fee/getSchoolFeetypes", $scope.fModel, config).then(
                function (success) {
                    //Loading("#dfFeetypes", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.feetypes = success.data;
                },
                function (error) {
                    //Loading("#dfFeetypes", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    };

    $scope.feeStatistics = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + "fee/fee_statistics", $scope.filterModel, config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                    $scope.total_school_fee = success.data.total_school_fee;
                    $scope.total_full_paid_fee = success.data.total_full_paid_fee;
                    $scope.fully_collected_fee = success.data.fully_collected_fee;
                    $scope.partially_collected_fee = success.data.partially_collected_fee;
                    $scope.total_paid_cash = success.data.total_paid_cash;
                    $scope.due_fees = success.data.due_fees;
                    $scope.total_fee_amount = success.data.total_fee_amount;
                    $scope.total_fee_defaulters = success.data.total_fee_defaulters;
                    $scope.remaining_fee_amount = success.data.total_fee_amount - success.data.total_paid_cash;
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    };
});

app2.controller("feeCollectionController", function ($scope, $http, $window, $filter) {
    $scope.classes = {};
    $scope.batches = {};
    $scope.fModel = {};  //add new fee collection model
    $scope.fcModel = {};  //fee collection (fc)
    $scope.afcModel = {}; //add fee collection (afc)
    $scope.feeCollectionStudents = [];
    $scope.selectedStd = {};
    $scope.stdFeeRecords = {};
    $scope.feetypes = {};
    $scope.today = $filter('date')(new Date(), 'dd/MM/yyyy');
    $scope.mode = 'cash';
    $scope.isSendEmailToParent = false;
    $scope.isSendEmailToParentEdit = false;
    $scope.isSendSMSToParent = false;
    $scope.isSendSMSToParentEdit = false;
    $scope.parent_id = 0;
    $scope.selectedSpecificFeetype = '<?php echo lang("all_fee_types");?>';
    $scope.fcModel.selectedFeetype = 'all';
    $scope.loading = false;
    $scope.update_loading = false;
    $scope.paid_amount = '';
    $scope.comment = '';
    $scope.partiallyFeeDetailModel = [];
    $scope.maxEditPaidAmount=0;
    $scope.academicyears = {};
    $scope.notes = [];
    $scope.notesLength = 0;
    $scope.totalSum = {};
    $scope.refreshFees = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'fee/refresh', "", config).then(
            function (success) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                if(success.data.success){
                    showNotification('<?php echo lang("success_app") ?>', success.data.msg, "success");
                }  
            },
            function (error) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $window.location.href = 'errors/' + error.status;
            }
        );
    }
    $scope.initAcademicYears =  function(){
        Loading("#feeFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'fee/getAcademicYears', "", config).then(
            function (success) {
                Loading("#feeFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.fcModel.academic_year_id = success.data.current_academic_year_id;
                $scope.initClasses(success.data.current_academic_year_id);
                $scope.academicyears = success.data.data;
                $scope.fcModel.class_id = "all";
                $scope.fcModel.batch_id = "all";
            },
            function (error) {
                Loading("#feeFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $window.location.href = 'errors/' + error.status;
            }
        );
    };
    
    $scope.initClasses = function (academic_year_id) {
        if(academic_year_id) { 
            Loading("#feeFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'fee/getClasses', { 'academic_year_id': academic_year_id }, config).then(
                function (success) {
                    Loading("#feeFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.classes = success.data;
                    $scope.fcModel.class_id = "all";
                },
                function (error) {
                    Loading("#feeFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
            );
        }
    };
    
    $scope.initBatches = function (class_id, academic_year_id) {
        Loading("#feeFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (class_id && academic_year_id) {
            Loading("#feeFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'fee/getClassBatches', {'class_id': class_id, 'academic_year_id':academic_year_id}, config).then(
                    function (success) {
                        Loading("#feeFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.batches = success.data;
                        $scope.fcModel.batch_id = "all";
                    },
                    function (error) {
                        console.log(error.data);
                        //Loading("#dfBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        //$window.location.href = 'errors/' + error.status;
                    }
            );
            
            $http.post(base_url + 'fee/getSpecificFeetypes', {'class_id': class_id, 'academic_year_id':academic_year_id}, config).then(
                function (success) {
                    $scope.feetypes = success.data;
                },
                function (error) {
                    console.log(error.data);
                }
            );
        }
    };
    
    $scope.initFeetypes = function(){
        $http.post(base_url + 'fee/getAllFeetypes', "", config).then(
            function (success) {
                $scope.feetypes = success.data;
            }, function (error){
                console.log(error.data);
            }
        );
    };

    $scope.fetchFeeCollections = function (valid) {
        if (valid) {
            var formData = {
                academic_year_id: $scope.fcModel.academic_year_id ? $scope.fcModel.academic_year_id : null,
                class_id: $scope.fcModel.class_id ? $scope.fcModel.class_id : null, 
                batch_id: $scope.fcModel.batch_id, searchBy: $scope.fcModel.searchBy ? $scope.fcModel.searchBy : null, 
                isDue: $scope.fcModel.isDue ? $scope.fcModel.isDue : 0, 
                specificFeeType: $scope.fcModel.selectedFeetype
            };
            Loading("#feeCollection_search_filter", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + "fee/fetchfeeCollectionStudents", formData, config).then(
                    function (success) {
                        $("#feeCollectionContainer1").removeClass("hidden");
                        $(".feeCollectionContainer2").addClass("hidden");
                        Loading("#feeCollection_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.feeCollectionStudents = success.data;
                    },
                    function (error) {
                        Loading("#feeCollection_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        console.log(error.data);
                    }
            );
        }
    };

    $scope.showDetails = function (std) {
        $("#feeCollectionContainer1").addClass("hidden");
        $(".feeCollectionContainer2").removeClass("hidden");
        $scope.selectedStd = std;
        $scope.fetchStudentFeeRecords($scope.selectedStd);
        $('body').tooltip({
            selector: '[rel=tooltip]'
        });
    };

    $scope.back = function () {
        $(".feeCollectionContainer2").addClass("hidden");
        $("#feeCollectionContainer1").removeClass("hidden");
        $scope.fetchFeeCollections(true);
    };

    $scope.fetchStudentFeeRecords = function (obj) {
        var formData = {
            std_id: obj.id, 
            class_id: obj.class_id, 
            school_id: obj.school_id, 
            discount_id: obj.discount_id, 
            discount_amount: obj.discount_amount,
            academic_year_id: $scope.fcModel.academic_year_id ? $scope.fcModel.academic_year_id : 0
        };
        $http.post(base_url + "fee/getStudentFeeRecrods", formData, config).then(
            function (success) {
                $scope.totalSum.fee = 0;
                $scope.totalSum.paid = 0;
                $scope.totalSum.balance = 0;
                $scope.totalSum.discounted = 0;
                angular.forEach(success.data.records, function (value, key) {
                    if(value[0].fee_collection_id == 'NULL' && value[0].discounted_amount == 0){
                        $scope.setAddFeeCollectionModel(value[0]);
                        $scope.paid_amount = 0;
                        $scope.payfeeautomatically(true);
                    }

                    
                    $scope.totalSum.discounted += parseFloat(value[0].amount - value[0].discount);
                    $scope.totalSum.fee += parseFloat(value[0].amount);
                    $scope.totalSum.balance += parseFloat($scope.calculateBalance(value));
                    angular.forEach(value,function(v,k){
                         if(v.fee_collection_id != 'NULL'){
                         $scope.totalSum.paid += parseFloat(v.paid_amount);
                     }
                })


                });
                $scope.stdFeeRecords = success.data.records;
                $scope.notes = success.data.notes;
                $scope.notesLength = Object.keys($scope.notes).length;
            },
            function (error) {
                console.log(error.data);
            }
        );
    };

    $scope.showPartiallyFeeDeatils = function(obj){
        $('#myModalYasir').modal('show');
        $scope.partiallyFeeDetailModel = obj;
    };
    
    $scope.calculateStatus = function(objArray){
        var status = 0;
        for(i=0; i < objArray.length; i++) {
            if(parseInt(objArray[i].status) === 1){
                status = 1;
                break;
            } else if(parseInt(objArray[i].status) === 2){
                status = 2;
            }
        }
        return status;
    };
    
    $scope.countPaidAmount = function (obj){
        var paid_amount = 0;
        if(obj.length == 1){
            paid_amount = parseInt(obj[0].paid_amount);
        } else {
            angular.forEach(obj, function (value, key) {
                paid_amount = parseInt(paid_amount) + parseInt(value.paid_amount);
            });
        }
        return paid_amount;
    };
    
    $scope.calculateBalance = function (obj){
        var balance = 0;
        if(obj.length == 1){
            if(obj[0].fee_collection_id == 'NULL'){
                balance = 0 - obj[0].discounted_amount;
            }else{
                balance =  obj[0].paid_amount - parseFloat(obj[0].feetype_amount - obj[0].discount);
            }     
        } else {
            var paid_amount = 0;
            angular.forEach(obj, function (value, key) {
                paid_amount = paid_amount + parseFloat(value.paid_amount);
            });
            balance = paid_amount - parseFloat(obj[0].feetype_amount - obj[0].discount);
        }
        return balance;
    };
    
    $scope.isAllFeePaid = function(obj){
        var is_all_paid = false;
        angular.forEach(obj, function (value, key) {
            if(value.status === "1"){
                is_all_paid = true;
            }
        });
        return is_all_paid;
    };
    
    $scope.calculatePaidFeePercentage = function (obj){
        var percentage = 0;
        if(obj.length == 1){
            if(obj[0].discount == 100 || obj[0].discounted_amount == 0){
                percentage = 100;
            } else {
                percentage = (obj[0].paid_amount * 100) / (obj[0].amount-obj[0].discount);
            }
        } else {
            var paid_amount = 0;
            angular.forEach(obj, function (value, key) {
                paid_amount = paid_amount + parseInt(value.paid_amount);
            });
            percentage = (paid_amount * 100) / (obj[0].amount-obj[0].discount);
        }
        return percentage;
    };
    
    $scope.payfeeautomatically = function(valid){
        var fee_name = $scope.afcModel.feetype;
        $scope.loading = true;
        if (valid) {
            $scope.afcModel.paid_amount = $scope.paid_amount;
            $scope.afcModel.mode = $scope.mode;
            $http.post(base_url + "fee/collectFee", {'obj': $scope.afcModel, 'class_id':$scope.selectedStd.class_id, 'batch_id':$scope.selectedStd.batch_id, 'is_send_email':$scope.isSendEmailToParent, 'comment' : ''}, config).then(
                function (success) {
                    if (success.data.status === "success") {
                        $scope.loading = false;
                        $scope.mode = 'cash';
                        $scope.paid_amount = "";
                        $scope.comment = "";
                        $scope.fetchStudentFeeRecords($scope.selectedStd);
                        showNotification('<?php echo lang("lbl_info"); ?>', fee_name + ' <?php echo lang("lbl_fee_auto_paid_due_to_full_discount"); ?>', "info");
                    }
                },
                function (error) {
                    $scope.loading = false;
                    console.log(error.data);
                }
            );
        }
    };
    
    $scope.setAddFeeCollectionModel = function (obj) {
        $scope.loading = false;
        $scope.afcModel = obj;
        $scope.paid_amount = $scope.afcModel.discounted_amount;
        $scope.afcModel.student_id = $scope.selectedStd.id;
        $scope.afcModel.created_at = $scope.today;
    };
    
    $scope.setEditFeeCollectionModel = function (obj) {
        $scope.maxEditPaidAmount = 0;
        $scope.editModel = obj[0];
        var total_paid_amount = 0;
        angular.forEach(obj, function (value, key) {
            total_paid_amount += parseFloat(value.paid_amount);
        });
        var discounted_amount = obj[0].feetype_amount - obj[0].discount;
        $scope.editPaidAmount = discounted_amount - total_paid_amount;
        $scope.maxEditPaidAmount = $scope.editPaidAmount;
        $scope.editModel.student_id = $scope.selectedStd.id;
    };
    
    /*$scope.setAddFeeCollectionModel = function (obj) {
        $scope.loading = false;
        $scope.afcModel = obj;
        $scope.afcModel.student_id = $scope.selectedStd.id;
        $scope.afcModel.created_at = $scope.today;
    };*/

    $scope.collectFee = function (valid) {
        $scope.loading = true;
        if (valid) {
            $scope.afcModel.paid_amount = $scope.paid_amount;
            $scope.afcModel.mode = $scope.mode;
            $http.post(base_url + "fee/collectFee", {'obj': $scope.afcModel,'class_id':$scope.selectedStd.class_id, 'batch_id':$scope.selectedStd.batch_id, 'is_send_email':$scope.isSendEmailToParent, 'comment':$scope.comment}, config).then(
                function (success) {
                    if (success.data.status === "success") {
                        $scope.loading = false;
                        $('#feeCollectionAddModel').modal('hide');
                        $scope.mode = 'cash';
                        $scope.paid_amount = "";
                        $scope.comment = "";
                        $scope.feeCollectionAddModelForm.$setUntouched();
                        $scope.feeCollectionAddModelForm.$setPristine();
                        if($scope.isSendNotificationToParent){
                            var otherData = {'class_id':$scope.selectedStd.class_id, 'batch_id':$scope.selectedStd.batch_id};    
                            $scope.getStudentGuardians($scope.afcModel.student_id, otherData);
                        }
                        $scope.fetchStudentFeeRecords($scope.selectedStd);
                        showNotification(success.data.status, success.data.message, success.data.status);
                    }
                },
                function (error) {
                    $scope.loading = false;
                    console.log(error.data);
                }
            );
        }
    };
    
    $scope.getStudentGuardians = function(student_id, otherData){
        $http.post(base_url + 'syllabus/getStudentGuardians', {student_id:student_id}, config).then(
            function(success){
                publicNotificationViaPusher("lbl_fee_collection_notification", otherData, success.data.new_ids, "fee/collection", {'sender': success.data.sender}, 0);
            },
            function(error){
                $window.location.href = 'errors/' + error.status;
            }
        );
    };

    $scope.collectFeeUpdate = function (valid) {
        $scope.update_loading = true;
        if (valid) {
            $scope.editModel.is_send_email = $scope.isSendEmailToParentEdit;
            $scope.editModel.is_send_sms = $scope.isSendSMSToParentEdit;
            $scope.editModel.paid_amount = $scope.editPaidAmount;
            $http.post(base_url + "fee/collectFee", {'obj':$scope.editModel,'class_id':$scope.selectedStd.class_id, 'batch_id':$scope.selectedStd.batch_id, 'comment':$scope.comment}, config).then(
                    function (success) {
                        if (success.data.status === "success") {
                            $scope.update_loading = false;
                            $scope.comment = "";
                            $('#feeCollectionEditModel').modal('hide');
                            if($scope.isSendNotificationToParentEdit){
                                var otherData = {'class_id':$scope.selectedStd.class_id, 'batch_id':$scope.selectedStd.batch_id};    
                                $scope.getStudentGuardians($scope.editModel.student_id, otherData);
                            }
                            $scope.fetchStudentFeeRecords($scope.selectedStd);
                            showNotification(success.data.status, success.data.message, success.data.status);
                        }
                    },
                    function (error) {
                        $scope.update_loading = false;
                        console.log(error.data);
                    }
            );
        }
    };
    
    $scope.setSpecificFeetype = function(feetype){
        $scope.fcModel.selectedFeetype = feetype.id;
        $scope.selectedSpecificFeetype = feetype.name;
    };
    
    $scope.showConfirmationAlert = function (obj, how_many) {
        var ids = [];
        if(how_many === 'all'){
            angular.forEach(obj, function (value, key) {
                ids.push(value.fee_collection_id);
            });
        } else if(how_many === 'null'){
            ids.push(obj.fee_collection_id);
        }
        
        swal({
            title: '<?php echo lang("are_you_sure") ?>',
            text: '<?php echo lang("delete_message") ?>',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55", confirmButtonText: '<?php echo lang("yes_delete") ?>',
            cancelButtonText: '<?php echo lang("btn_cancel") ?>',
            closeOnConfirm: true,
            closeOnCancel: true
        },
        function (isConfirm) {
            if (isConfirm) {
            $http.post(base_url + "fee/sofeDeleteCollectedFee", {"ids": ids, 'type':how_many}, config).then(
                    function (success) {
                        if (success.data.status === "success") {
                            $("#myModalYasir").modal('hide');
                            $scope.showDetails($scope.selectedStd);
                        }
                    },
                    function (error) {
                        console.log(error.data);
                    }
                );
            }
        });
    };
    
});
//*************************************//
//   Message Plugin & controllers     //
//************************************//

app2.directive('dropzone', function () {
    return function (scope, element, attrs) {
        Dropzone.autoDiscover = false;
        var config, dropzone;

        config = scope[attrs.dropzone];

        // create a Dropzone for the element with the given options
        dropzone = new Dropzone(element[0], config.options);

        // bind the given event handlers
        angular.forEach(config.eventHandlers, function (handler, event) {
            dropzone.on(event, handler);
        });
    };
});

app2.controller('inbox', function ($scope, $http) {
    $scope.delete = {};
    $scope.restore = {};
    $scope.init = function () {
        $http.post(base_url + 'messages/getConversations').then(
                function (response) {
                    $scope.conversations = response.data.conversations;
                    $scope.unread = response.data.count;
                    $scope.trashes = response.data.trashes;
                    $scope.trash_count = response.data.trash_count;
                    $scope.sentMessages = response.data.sent;
                })
    }

    $scope.resetCompose = function () {
        $scope.message = {};
        $('.textarea_editor').data("wysihtml5").editor.clear();
        $('.js-data-example-ajax').val(null).trigger('change.select2');
        $('#message_alert').hide();
        Dropzone.forElement("#my-awesome-dropzone").removeAllFiles(true);
    }

    $scope.dropzoneConfig = {
        'options': {// passed into the Dropzone constructor
            'url': base_url + 'messages/upload_attachments',
            'autoProcessQueue': false,
            'autoDiscover': false,
            'uploadMultiple': true,
            'parallelUploads': 5,
            'maxFilesize': 20, //MB
            'maxFiles': 100,
            'acceptedFiles': "image/*, application/pdf, .doc, .docx, .ppt, pptx",
            'addRemoveLinks': true,
            'dictRemoveFile': '<?php echo lang("lbl_remove");?>',
            'dictDefaultMessage': '<?php echo lang("drop_here");?>',
            'dictFileTooBig': 'File is bigger than 20MB',
            init: function () {
                var submitButton = document.querySelector("#saveButton");
                myDropzone = this;
                submitButton.addEventListener("click", function () {
                    if ($('.textarea_editor').val() == "" || typeof ($scope.message.to) == "undefined" ||
                            $scope.message.to == "" || typeof ($scope.message.subject) == "undefined" || $scope.message.subject == "")
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger')
                                .html('<?php echo lang("to_req") ?>').show();

                    else if (myDropzone.files.length == 0)
                        $scope.startConversation();

                    else if (myDropzone.files.length != 0)
                        myDropzone.processQueue();
                    // else
                    //     

                });




                myDropzone.on("complete", function (file) {
                    if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {

                        $scope.startConversation();
                    }
                });


            }
        },
        'eventHandlers': {
            'sending': function (file, xhr, formData) {
            },
            'success': function (file, response) {
                $scope.message.files = response;


            }
        }
    };


    $scope.trash = function () {
        $('#sentButton').removeClass('active');
        $('#inboxButton').removeClass('active');
        $('#trashButton').addClass('active');
        $('.inbox-center').hide();
        $('#sentDiv').hide();
        $('#trashDiv').show();
    }
    $scope.inbox = function () {
        $('#inboxButton').addClass('active');
        $('#trashButton').removeClass('active');
        $('#sentButton').removeClass('active');
        $('.inbox-center').show();
        $('#sentDiv').hide();
        $('#trashDiv').hide();
    }
    $scope.sentM = function () {
        $('#sentButton').addClass('active');
        $('#trashButton').removeClass('active');
        $('#inboxButton').removeClass('active');
        $('.inbox-center').hide();
        $('#trashDiv').hide();
        $('#sentDiv').show();

    }

    $scope.deleteConId = function (id) {
        $scope.delete.deleteId = id;
    }

    $scope.deleteCon = function () {




        $http.post(base_url + 'messages/deleteConversation/', $scope.delete).then(
                function (response) {
                    if (response.data.success) {
                        $('#deleteConversation').modal('toggle');
                        showNotification('<?php echo lang("success_app") ?>', response.data.message, "success");
                        $scope.init();
                        $scope.delete = {};
                    }
                    //console.log(response.data);
                },
                function (error) {
                    //console.log(error.data);
                }
        )
    }
    $scope.restoreConId = function (id) {
        $scope.restore.restoreId = id;
    }

    $scope.restoreCon = function () {




        $http.post(base_url + 'messages/restoreConversation/', $scope.restore).then(
                function (response) {
                    if (response.data.success) {
                        $('#restoreConversation').modal('toggle');
                        showNotification('<?php echo lang("success_app") ?>', response.data.message, "success");
                        $scope.init();
                        $scope.restore = {};
                    }
                    //console.log(response.data);
                },
                function (error) {
                    //console.log(error.data);
                }
        )
    }
    $scope.startConversation = function () {
        $('#saveButton').prop('disabled', true);
        Loading("#compose", '<?php echo lang("loading_datatable") ?>', "", "show");
        var text = $('.textarea_editor').val();
        $scope.message.text = text;
        console.log($scope.message);
        $http.post(base_url + "messages/startConversation/", $scope.message).then(
                function (response) {
                    // console.log(response.data);
                    if (response.data.success) {

                        $('#message_alert').removeClass('alert-danger').addClass('alert-success').html(response.data.message).show();
                        Loading("#compose", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.message = {};
                        $('.textarea_editor').data("wysihtml5").editor.clear();
                        $('.js-data-example-ajax').val(null).trigger('change.select2');
                        $('#message_alert').hide();
                        $('#compose').modal('hide');
                        $scope.init();
                        $scope.inbox();

                        showNotification('<?php echo lang("success_app") ?>', response.data.message, "success");
                        var otherData = {};    
                        publicNotificationViaPusher('new_conversation', otherData, response.data.part, 'messages/view/' + response.data.con_id, {'sender': response.data.sender});
                        // $scope.message.to="";
                        // $scope.message.subject="";
                        // $scope.message.text="";

                    } else {
                        $('#saveButton').prop('disabled', false);
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html(response.data.message).show();
                        Loading("#compose", '<?php echo lang("loading_datatable") ?>', "", "hide");

                    }

                },
                function (error) {
                    //console.log(error.data);
                }
        );

    }



});

app2.controller('conversation', function ($scope, $http, $sce, $window, $location) {
    $scope.init = function () {
        $http.post(base_url + 'messages/getMessages', $scope.conver).then(
                function (response) {
                    $scope.messages = response.data.messages;
                    for (var i = 0, len = $scope.messages.length; i < len; i++) {
                        $scope.messages[i]['text'] = $sce.trustAsHtml($scope.messages[i]['message_body']);
                    }
                })
    }

    $scope.back = function () {
       $window.location.href = 'messages/show';
    };
    
    $scope.dropzoneConfig = {
        'options': {// passed into the Dropzone constructor
            'url': base_url + 'messages/upload_attachments',
            'autoProcessQueue': false,
            'autoDiscover': false,
            'uploadMultiple': true,
            'parallelUploads': 5,
            'maxFilesize': 20, //MB
            'maxFiles': 100,
            'acceptedFiles': "image/*, application/pdf, .doc, .docx, .ppt, pptx",
            'addRemoveLinks': true,
            'dictRemoveFile': '<?php echo lang("lbl_remove");?>',
            'dictDefaultMessage': '<?php echo lang("drop_here");?>',
            'dictFileTooBig': 'File is bigger than 20MB',
            init: function () {
                var submitButton = document.querySelector("#saveButton");
                myDropzone = this;
                submitButton.addEventListener("click", function () {
                    if ($('.textarea_editor').val() == "")
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("msg_req") ?>').show();
                    else if (myDropzone.files.length == 0)
                        $scope.newMessage();

                    else if (myDropzone.files.length != 0)
                        myDropzone.processQueue();
                });
                myDropzone.on("complete", function (file) {
                    if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {

                        $scope.newMessage();
                    }
                });
            }
        },
        'eventHandlers': {
            'sending': function (file, xhr, formData) {
            },
            'success': function (file, response) {
                $scope.conver.files = response;
            }
        }
    };

    $scope.newMessage = function () {

        Loading("#compose", '<?php echo lang("loading_datatable") ?>', "", "show");
        var text = $('.textarea_editor').val();

        $scope.conver.text = text;

        $http.post(base_url + "messages/newMessage/", $scope.conver).then(
                function (response) {
                    // console.log(response.data);
                    if (response.data.success) {

                        $('#message_alert').removeClass('alert-danger').addClass('alert-success').html(response.data.message).show();
                        Dropzone.forElement("#my-awesome-dropzone").removeAllFiles(true);
                        Loading("#compose", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.conver.files = '';

                        $('.textarea_editor').data("wysihtml5").editor.clear();

                        $scope.init();
                        $('#message_alert').hide();
                        showNotification('<?php echo lang("success_app") ?>', response.data.message, "success");
                        var otherData = {};
                        publicNotificationViaPusher('new_conversation', otherData, response.data.part, 'messages/view/' + response.data.con_id, {'sender': response.data.sender});
                        // $scope.message.to="";
                        // $scope.message.subject="";
                        // $scope.message.text="";

                    } else {

                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html(response.data.message).show();
                        Loading("#compose", '<?php echo lang("loading_datatable") ?>', "", "hide");

                    }

                },
                function (error) {
                    //console.log(error.data);
                }
        );
    }
});

app2.controller("periodController", function ($scope, $http, $sce) {
    $scope.classes = {};
    $scope.batches = {};
    //$scope.selecedVal = 'all';
    //$scope.selecedVal2 = 'all';
    $scope.myDiv = false;
    $scope.fetchClasses = function (id) {
        $http.post(base_url + 'settings/getSchoolClasses', "", config).then(
                function (success) {
                    $scope.classes = success.data;
                    if (id !== 'all') {
                        $scope.loadClassBatches(id);
                    }
                },
                function (error) {
                    console.log(error.data);
                }
        );
    };
    $scope.loadClassBatches = function (id) {
        if (id !== 'all') {
            //Loading("#yasir_batches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'settings/getClassBatches', {id: id}, config).then(
                    function (success) {
                        //Loading("#yasir_batches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.batches = success.data;
                        //$scope.selecedVal2 = 'all';
                    },
                    function (error) {
                        //Loading("#yasir_batches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        console.log(error.data);
                    }
            );
        } else {
            $scope.batches = {};
            $scope.selecedVal2 = 'all';
            $scope.loadPeriods();
        }
    };

    $scope.loadPeriods = function () {
        $http.post(base_url + 'settings/getPeriods', {class_id: $scope.selecedVal, batch_id: $scope.selecedVal2}, config).then(
                function (success) {
                    $scope.myDiv = $sce.trustAsHtml(success.data);
                },
                function (error) {
                    console.log(error.data);
                }
        );
    };
});


app2.controller('addexamConroller', function ($scope, $http, $sce)
{
    $scope.getclasses_examdetail = function () {
        //console.log($scope.study.class);
        <!--$scope.examname_error = false;-->
        $http.post(base_url + 'online_exams/getclasses', {exam_id: $scope.examname}, config).then(
            function (response) {
                console.log(response.data.filter_classes);
                $scope.online_Eclass=response.data.filter_classes;
            }
        );
    };
});

app2.controller('uploadController', function ($scope, $http, $sce) {
    $scope.study = {
        class : '',
        section : '',
        subject : '',
        type : '',
        date : ''
    };
    $scope.study1 = {
        class : '',
        section : '',
        subject : '',
        type : '',
        date : ''
    };
    $scope.study2 = {
        class : '',
        section : '',
        subject : '',
        type : '',
        date : ''
    };
     $scope.study3 = {
        title : '',
        uploaded_at : '',
        due_date : '',
        class : '',
        section : '',
        subject : '',
        students : '',
        marks : '',
        details : '',
        type : '',
        material_description : ''
  };
    $scope.study4 = {
        title : '',
        uploaded_at : '',
        due_date : '',
        class : '',
        section : '',
        subject : '',
        students : '',
        marks : '',
        details : '',
        type : '',
        material_description : ''
  };
    $scope.study3.files = [];
    $scope.study4.files = [];
    $scope.study.files = [];
    $scope.study1.files = [];
    $scope.AssSubjects = [];
    $scope.AssStudents = [];
    $scope.edit = {};
    $scope.details = {};
    $scope.study_upload = {};

    // for homeword assignment
    $scope.Assinged_student_detials = {};
    $scope.Assignment_details = {};
    $scope.Assignment_details.files = [];

    $scope.getStudentsForHomework = function (){
      $scope.AssStudents = "";
      Loading("#select2-students_homework", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'study_material/getStusentsForAssignments', $scope.study4, config).then(
            function (success) {
                Loading("#select2-students_homework", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.AssStudents = success.data;
            },
            function (error) {
                Loading("#select2-students_homework", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
                //$window.location.href = 'errors/' + error.status;
            }
        );
  }

    $scope.getSubjectsForHomework = function () {
    $scope.AssSubjects = "";
    Loading("#select2-subject_homework", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'study_material/getSubjectsForAssignments', $scope.study4, config).then(
            function (success) {
                Loading("#select2-subject_homework", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.AssSubjects = success.data;
                $scope.AssStudents = "";
            },
            function (error) {
                Loading("#select2-subject_homework", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
                //$window.location.href = 'errors/' + error.status;
            }
        );

  };


    $scope.getStudentsForAss = function (){
      Loading("#select2-students", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'study_material/getStusentsForAssignments', $scope.study3, config).then(
            function (success) {
                Loading("#select2-students", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.AssStudents = success.data;
                $scope.study3.students = "";
            },
            function (error) {
                Loading("#select2-students", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
                //$window.location.href = 'errors/' + error.status;
            }
        );
  }

  $scope.getStudentsForAssEdit = function (){
      Loading("#select2-students-edit-assignment", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'study_material/getStusentsForAssignments', $scope.study7, config).then(
            function (success) {
                Loading("#select2-students-edit-assignment", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.students = success.data;
                $scope.study7.students = "";
            },
            function (error) {
                Loading("#select2-students-edit-assignment", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
                //$window.location.href = 'errors/' + error.status;
            }
        );
  }

  $scope.getStudentsForHomEdit = function (){
      Loading("#select2-students-edit-homework", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'study_material/getStusentsForAssignments', $scope.study8, config).then(
            function (success) {
                Loading("#select2-students-edit-homework", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.students = success.data;
                $scope.study8.students = "";
            },
            function (error) {
                Loading("#select2-students-edit-homework", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
                //$window.location.href = 'errors/' + error.status;
            }
        );
  }

    $scope.getSubjectsForassignments = function () {
    Loading("#select2-subject", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'study_material/getSubjectsForAssignments', $scope.study3, config).then(
            function (success) {
                Loading("#select2-subject", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.AssSubjects = success.data;
                $scope.study3.subject = "";
                $scope.AssStudents = "";
            },
            function (error) {
                Loading("#select2-subject", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
                //$window.location.href = 'errors/' + error.status;
            }
        );

  };

  $scope.getSubjectsForassignmentsEdit = function () {
    Loading("#select2-subject-edit-assignmnet", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'study_material/getSubjectsForAssignments', $scope.study7, config).then(
            function (success) {
                Loading("#select2-subject-edit-assignmnet", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.study7.subj = success.data;
                $scope.study7.subject = "";
            },
            function (error) {
                Loading("#select2-subject-edit-assignmnet", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
                //$window.location.href = 'errors/' + error.status;
            }
        );

  };

  $scope.getSubjectsForhomeworkEdit = function () {
    Loading("#select2-subject-edit-homework", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'study_material/getSubjectsForAssignments', $scope.study8, config).then(
            function (success) {
                Loading("#select2-subject-edit-homework", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.study8.subj = success.data;
                $scope.study8.subject = "";
            },
            function (error) {
                Loading("#select2-subject-edit-homework", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
                //$window.location.href = 'errors/' + error.status;
            }
        );

  };  

    $scope.getSections_upload = function () {
        //console.log($scope.study.class);
        $scope.class_error = false;
        $http.post(base_url + 'attendance/getClassBatches', {class_id: $scope.study.class}, config).then(
                function (response) {
                    $scope.study.batches = response.data;
                    $scope.study.subject = "";
                    $scope.study.subjects = "";
                    $scope.study.section = "";
                });
    };
    $scope.getSubjects_upload = function () {
        $scope.section_error = false;
        $http.post(base_url + 'study_material/getSubjects', {class: $scope.study.class, section: $scope.study.section}).then(
                function (response) {
                    $scope.study.subjects = response.data.subjects;
                    $scope.study.subject = "";
                });
    };
    $scope.subjectChanged_upload = function () {
        $scope.subject_error = false;
    };

    $scope.typeChanged_upload = function () {
        $scope.type_error = false;
    };
     $scope.filter_upload = function () {

        $scope.class_error = false;
        $scope.section_error = false;
        $scope.subject_error = false;
        $scope.type_error = false;
        $scope.date_error = false;
        $check = true;

        console.log($scope.study);
        /*if ($scope.study.class == undefined || $scope.study.class == "") {
            $scope.class_error = true;
            $check = false;
        }
        if ($scope.study.section == undefined || $scope.study.section == "") {
            $scope.section_error = true;
            $check = false;
        }
        if ($scope.study.subject == undefined || $scope.study.subject == "") {
            $scope.subject_error = true;
            $check = false;
        }
        if ($scope.study.type == undefined || $scope.study.type == "") {
            $scope.type_error = true;
            $check = false;
        }
        if ($scope.study.date == undefined || $scope.study.date == "") {
            $scope.date_error = true;
            $check = false;
        }*/

        if ($check) {
            Loading("body", "<?php echo lang("loading_datatable") ?>", "", "show");
            $http.post(base_url + 'study_material/filter', $scope.study).then(
            function (response) {

                $scope.study.materials = response.data.materials;
                for (var i = 0, len = $scope.study.materials.length; i < len; i++) {
                    $scope.study.materials[i]['details'] = $sce.trustAsHtml($scope.study.materials[i]['details']);
                }
                Loading("body", "<?php echo lang("loading_datatable") ?>", "", "hide");
            });
        }

    };
    
    $scope.filter_upload_material_general = function () {

$scope.class_error = false;
$scope.section_error = false;
$scope.subject_error = false;
$scope.type_error = false;
$scope.date_error = false;
$check = true;

if ($scope.study.class == undefined || $scope.study.class == "") {
    $scope.class_error = true;
    $check = false;
}
if ($scope.study.section == undefined || $scope.study.section == "") {
    $scope.section_error = true;
    $check = false;
}


if ($check) {
    Loading("body", "<?php echo lang("loading_datatable") ?>", "", "show");
    $http.post(base_url + 'study_material/filter_studymaterialGeneral', $scope.study).then(
    function (response) {
        $scope.study.materials = response.data.materials;
        for (var i = 0, len = $scope.study.materials.length; i < len; i++) {
            $scope.study.materials[i]['details'] = $sce.trustAsHtml($scope.study.materials[i]['details']);
        }
        Loading("body", "<?php echo lang("loading_datatable") ?>", "", "hide");
    });
}

};

    $scope.assignment_upload = function () {

        $scope.class_error = false;
        $scope.section_error = false;
        $scope.subject_error = false;
        $scope.f_date_error = false;
        $scope.t_date_error = false;
        $check = true;
        
        //console.log($scope.study);

        if ($check) {
            Loading("body", "<?php echo lang("loading_datatable") ?>", "", "show");
            $http.post(base_url + 'study_material/filter_assignments', $scope.study).then(
            function (response) {
              $scope.study.newMaterials = response.data.materials;
                for (var i = 0, len = $scope.study.newMaterials.length; i < len; i++) {
                    $scope.study.newMaterials[i]['details'] = $sce.trustAsHtml($scope.study.newMaterials[i]['details']);

                }

                Loading("body", "<?php echo lang("loading_datatable") ?>", "", "hide");
            });
        }
      };

    $scope.homework_upload = function () {

        $scope.class_error = false;
        $scope.section_error = false;
        $scope.subject_error = false;
        $scope.f_date_error = false;
        $scope.t_date_error = false;
        $check = true;

        //console.log($scope.study);

        if ($check) {
            Loading("body", "<?php echo lang("loading_datatable") ?>", "", "show");
            $http.post(base_url + 'study_material/filter_homework', $scope.study).then(
            function (response) {
            $scope.study.newHomework = response.data.materials;
                for (var i = 0, len = $scope.study.newHomework.length; i < len; i++) {
                    $scope.study.newHomework[i]['details'] = $sce.trustAsHtml($scope.study.newHomework[i]['details']);
            
                }
                Loading("body", "<?php echo lang("loading_datatable") ?>", "", "hide");
            });
        }
      };

    $scope.removeFilter_upload = function () {
        Loading("body", "", "", "show");
        $scope.getMaterials();
        Loading("body", "", "", "hide");
        $scope.class_error = false;
        $scope.section_error = false;
        $scope.subject_error = false;
        $scope.type_error = false;
        $scope.study.batches = [];
        $scope.study.subjects = [];
        $scope.study.class = '';
        $scope.study.section = '';
        $scope.study.subject = '';
        $scope.study.type = '';
        $scope.study.t_date = '';
        $scope.study.f_date = '';
        $scope.study.date = '';
        $scope.getHomework();
        $scope.getNewMaterials();
    };

    $scope.init = function () {
        $http.post(base_url + 'attendance/getClasses', "", config).then(
                function (response) {
                    $scope.study.classes = response.data;
                });
        $http.post(base_url + 'study_material/getToday', "", config).then(
                function (response) {
                    $scope.today = response.data.today;
                    $scope.study1.uploaded_at = $scope.today;
                    $scope.study3.uploaded_at = $scope.today;
                    $scope.study4.uploaded_at = $scope.today;
                });
    };

    $scope.editData = function (mat) {
        $('#edit_alert').hide();
        console.log(mat);
        $scope.study2.title = mat.title;
        $scope.study2.type = mat.content_type;
        $scope.study2.class = mat.class_id;
        $scope.study2.editId = mat.id;
        $scope.study2.subject = mat.subject_id;
        $scope.study2.section = mat.batch_id;
        $scope.study2.old_files = mat.old_files;
        //google drive
        $scope.study2.file_names = mat.file_names;
        $scope.study2.filesurl = mat.filesurl;
        $scope.study2.thumbnail_links = mat.thumbnail_links;
        $scope.study2.icon_links = mat.icon_links;
        $scope.study2.storage_type = mat.storage_type;
        // google drive
        $scope.study2.uploaded_at = mat.uploaded_time;
        $scope.study2.files = [];
        $scope.getSectionsEdit(mat);
        $('.textarea_editor2').data("wysihtml5").editor.setValue(mat.details);

    }

    $scope.editDataAssignment = function (mat) {
        $('#edit_alert').hide();
        //console.log(mat);
        $scope.study7.title = mat.title;
        $scope.study7.type = mat.content_type;
        $scope.study7.class = mat.class_id;
        $scope.study7.editId = mat.id;
        $scope.study7.subject = mat.subject_id;
        $scope.study7.section = mat.batch_ids;
        $scope.study7.old_files = mat.old_files;
        //google drive
        $scope.study7.file_names = mat.file_names;
        $scope.study7.filesurl = mat.filesurl;
        $scope.study7.thumbnail_links = mat.thumbnail_links;
        $scope.study7.storage_type = mat.storage_type;
        // google drive
        $scope.study7.uploaded_at = mat.uploaded_time;
        var dateAr = mat.due_date.split('-');
        var newDate = dateAr[2] + '/' + dateAr[1] + '/' + dateAr[0];
        $scope.study7.due_date = newDate;
        $scope.study7.material_details = mat.material_details;
        var marks = parseInt(mat.total_marks);
        $scope.study7.marks = marks;
        $scope.study7.students = mat.student_ids;
        $scope.study7.files = [];
        $scope.getSectionsEditAssignment(mat);
        $('.textarea_editor7').data("wysihtml5").editor.setValue(mat.details);
    }

    $scope.editDataHomework = function (hom) {
        $('#edit_alert').hide();
        //console.log(mat);
        $scope.study8.title = hom.title;
        $scope.study8.type = hom.content_type;
        $scope.study8.class = hom.class_id;
        $scope.study8.editId = hom.id;
        $scope.study8.subject = hom.subject_id;
        $scope.study8.section = hom.batch_ids;
        $scope.study8.old_files = hom.old_files;
        //google drive
        $scope.study8.file_names = hom.file_names;
        $scope.study8.filesurl = hom.filesurl;
        $scope.study8.thumbnail_links = hom.thumbnail_links;
        $scope.study8.storage_type = hom.storage_type;
        // google drive
        $scope.study8.uploaded_at = hom.uploaded_time;
        var dateAr = hom.due_date.split('-');
        var newDate = dateAr[2] + '/' + dateAr[1] + '/' + dateAr[0];
        $scope.study8.due_date = newDate;
        $scope.study8.material_details = hom.material_details;
        var marks = parseInt(hom.total_marks);
        $scope.study8.marks = marks;
        $scope.study8.students = hom.student_ids;
        $scope.study8.files = [];
        $scope.getSectionsEditHomework(hom);
        $('.textarea_editor8').data("wysihtml5").editor.setValue(hom.details);
        
    }

    

    $scope.removeFile = function(name){
        $scope.study2.old_files = $scope.study2.old_files.filter(function( obj ) {
                        return obj.name !== name;
                        $scope.$apply();
                    });
    }

    $scope.removeFileAssignment = function(name){
        $scope.study7.old_files = $scope.study7.old_files.filter(function( obj ) {
                        return obj.name !== name;
                        $scope.$apply();
                    });
    }

    $scope.removeFileHomework = function(name){
        $scope.study8.old_files = $scope.study8.old_files.filter(function( obj ) {
                        return obj.name !== name;
                        $scope.$apply();
                    });
    }

    $scope.getMaterials = function () {
    Loading("body", "<?php echo lang("loading_datatable") ?>", "", "show");
        $http.post(base_url + 'study_material/getMaterials').then(
                function (response) {
                    $scope.study.materials = response.data.materials;
                    $scope.today = response.data.today;
                    $scope.study.uploaded_at = $scope.today;
                    for (var i = 0, len = $scope.study.materials.length; i < len; i++) {

                        $scope.study.materials[i].details = $sce.trustAsHtml($scope.study.materials[i].details);

                    }
                    Loading("body", "<?php echo lang("loading_datatable") ?>", "", "hide");
                });
    };

    $scope.getNewMaterials = function () {
    Loading("body", "<?php echo lang("loading_datatable") ?>", "", "show");
        $http.post(base_url + 'study_material/getNewMaterials').then(
                function (response) {
                    $scope.study.newMaterials = response.data.materials;

                    for (var i = 0, len = $scope.study.newMaterials.length; i < len; i++) {

                        $scope.study.newMaterials[i].details = $sce.trustAsHtml($scope.study.newMaterials[i].details);

                    }
                    Loading("body", "<?php echo lang("loading_datatable") ?>", "", "hide");
                });
    };

    $scope.getHomework = function () {
    Loading("body", "<?php echo lang("loading_datatable") ?>", "", "show");
        $http.post(base_url + 'study_material/getNewHomework').then(
                function (response) {
                    $scope.study.newHomework = response.data.materials;

                    for (var i = 0, len = $scope.study.newHomework.length; i < len; i++) {

                        $scope.study.newHomework[i].details = $sce.trustAsHtml($scope.study.newHomework[i].details);

                    }
                    Loading("body", "<?php echo lang("loading_datatable") ?>", "", "hide");
                });
    };

    $scope.getSections = function () {
        $http.post(base_url + 'attendance/getClassBatches', {class_id: $scope.study.class}, config).then(
                function (response) {
                    $scope.study.batches = response.data;
                    $scope.study.subject = "";
                    $scope.study.subjects = "";
                    $scope.study.section = "";
                });
    };

    $scope.getSectionsForEdit = function () {
        $http.post(base_url + 'attendance/getClassBatches', {class_id: $scope.study2.class}, config).then(
                function (response) {
                    $scope.study2.batches = response.data;
                    $scope.study2.subject = "";
                    $scope.study2.subjects = "";
                    $scope.study2.section = "";
                });
    };

    $scope.getSections1 = function () {
        $http.post(base_url + 'attendance/getClassBatches', {class_id: $scope.study1.class}, config).then(
                function (response) {
                    $scope.study1.batches = response.data;
                    $scope.study1.subject = "";
                    $scope.study1.subjects = "";
                    $scope.study1.section = "";
                });
    };

    $scope.getSectionsForAssignment = function () {
        $http.post(base_url + 'attendance/getClassBatches', {class_id: $scope.study3.class}, config).then(
                function (response) {
                    $scope.study1.batches = response.data;
                    $scope.study3.section = "";
                    $scope.study3.subject = "";
                    $scope.AssStudents = "";
                });
    };

    $scope.getSectionsForAssignmentEdit = function () {
        $http.post(base_url + 'attendance/getClassBatches', {class_id: $scope.study7.class}, config).then(
                function (response) {
                    $scope.study7.batches = response.data;
                    $scope.study7.subject = "";
                    $scope.study7.subjects = "";
                    $scope.study7.section = "";
                    $scope.study7.students = "";
                });
    };

    $scope.getSectionsForHomeworkEdit = function () {
        $http.post(base_url + 'attendance/getClassBatches', {class_id: $scope.study8.class}, config).then(
                function (response) {
                    $scope.study8.batches = response.data;
                    $scope.study8.subject = "";
                    $scope.study8.subjects = "";
                    $scope.study8.section = "";
                    $scope.study8.students = "";
                });
    };

    $scope.getSectionsForHomework = function () {
        $http.post(base_url + 'attendance/getClassBatches', {class_id: $scope.study4.class}, config).then(
                function (response) {
                    $scope.study1.batches = response.data;
                    $scope.AssStudents = "";
                });
    };

    $scope.details_set = function (mat) {

        $scope.details.title = mat.title;
        $scope.details.content_type = mat.content_type;
        $scope.details.subject_name = mat.subject_name;
        $scope.details.uploaded_time = mat.uploaded_time;
        $scope.details.files = mat.files;
        $scope.details.details = mat.details;
        $scope.details.id = mat.id;
        // google drive
        $scope.details.storage_type=mat.storage_type;
        $scope.details.file_names=mat.file_names;
        $scope.details.filesurl=mat.filesurl;
        $scope.details.thumbnail_links = mat.thumbnail_links;
        $scope.details.icon_links = mat.icon_links;
    }

    $scope.download = function () {
        Loading("body", "", "", "show");
        $http.post(base_url + 'study_material/zip', $scope.details).then(
                function (response) {

                    //window.location.href = response.data.path;
                    paths = response.data.paths;
                    angular.forEach(paths, function (value, key) {
                        var link = document.createElement('a');
                        link.href = value;
                        link.download = key;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    });
                    Loading("body", "", "", "hide");
                })
    }

    $scope.getSectionsEdit = function (mat) {
        $http.post(base_url + 'attendance/getClassBatches', {class_id: mat.class_id}).then(
                function (response) {
                    $scope.study2.batches = response.data;
                    if (mat.batch_id.length > 5) {
                        $scope.study2.section = "all";
                    } else {
                        $scope.study2.section = mat.batch_id;
                    }

                    $scope.getSubjectsEdit(mat);
                })
    }

    $scope.getSectionsEditAssignment = function (mat) {
        $http.post(base_url + 'attendance/getClassBatchesForAssignmnet', {class_id: mat.class_id}).then(
                function (response) {
                    $scope.study7.batches = response.data;
                      $scope.study7.section = mat.batch_ids.split(",");
                      $scope.getSubjectsEditAssignment(mat);
                    });
    };

    $scope.getSectionsEditHomework = function (hom) {
        $http.post(base_url + 'attendance/getClassBatchesForAssignmnet', {class_id: hom.class_id}).then(
                function (response) {
                    $scope.study8.batches = response.data;
                      $scope.study8.section = hom.batch_ids.split(",");
                      $scope.getSubjectsEditHomework(hom);
                    });
    };

    $scope.getSubjectsEditAssignment = function (mat) {
        $http.post(base_url + 'study_material/getSubjectsForAssignmentEdit', {class: mat.class_id, section: mat.batch_ids}).then(
                function (response) {
                    $scope.study7.subj = response.data;
                    $scope.study7.subject = mat.subject_id;
                    $scope.getStudentsEditAssignment(mat);
                })
    }

    $scope.getSubjectsEditHomework = function (hom) {
        $http.post(base_url + 'study_material/getSubjectsForAssignmentEdit', {class: hom.class_id, section: hom.batch_ids}).then(
                function (response) {
                    $scope.study8.subj = response.data;
                    $scope.study8.subject = hom.subject_id;
                    $scope.getStudentsEditHomework(hom);
                })
    }

    $scope.getStudentsEditAssignment = function (mat) {
        $http.post(base_url + 'study_material/getStusentsForAssignmentsEdit', {class: mat.class_id, section: mat.batch_ids, subject: mat.subject_id}).then(
            function (response) {
                $scope.students = response.data;
                $scope.study7.students = mat.student_ids.split(",");
            });
    };

    $scope.getStudentsEditHomework = function (hom) {
        $http.post(base_url + 'study_material/getStusentsForAssignmentsEdit', {class: hom.class_id, section: hom.batch_ids, subject: hom.subject_id}).then(
            function (response) {
                $scope.students = response.data;
                $scope.study8.students = hom.student_ids.split(",");
            });
    };

    $scope.getSubjectsEdit = function (mat) {
      $http.post(base_url + 'study_material/getSubjects', $scope.study2).then(
        function (response) {
            $scope.study2.subjects = response.data.subjects;
          //  $scope.study2.subject = mat.subject_id;
            if (mat.subject_id.length > 5) {
                $scope.study2.subject = "all";
            } else {
                $scope.study2.subject = mat.subject_id;
            }
        })
      }

    $scope.getSubjects = function () {
        $http.post(base_url + 'study_material/getSubjects', $scope.study).then(
                function (response) {
                    $scope.study.subjects = response.data.subjects;
                    $scope.study.subject = "";
                })
    }

    $scope.getSubjectsForEdit = function () {
        $http.post(base_url + 'study_material/getSubjects', $scope.study2).then(
                function (response) {
                    $scope.study2.subjects = response.data.subjects;
                    $scope.study2.subject = "";
                })
    }

    $scope.getSubjects1 = function () {
        $http.post(base_url + 'study_material/getSubjects', $scope.study1).then(
                function (response) {
                    $scope.study1.subjects = response.data.subjects;
                    $scope.study1.subject = "";
                })
    }

    $scope.newMaterial = function () {
    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'study_material/newMaterial', $scope.study1).then(
                function (response) {
                var otherData = {
                        'class_id': $scope.study1.class,
                        'batch_id': $scope.study1.section,
                        'subject_id': $scope.study1.subject
                    };
                    $scope.getMaterials();
                    $scope.study1.files = [];
                    $('#upload').modal('toggle');
                    showNotification('Success', response.data.message, 'success');
                    publicNotificationViaPusher('new_study_material',otherData, response.data.part, 'study_material/upload', {'sender': response.data.sender});
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
    };

    $scope.assignmentMaterial = function () { 
    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'study_material/newAssignment', $scope.study3).then(
                function (response)
                {
                    if(response.data.uploaded=='true')
                    {
                        $scope.getNewMaterials();
                        $scope.study3.files = [];
                        $('#assignment').modal('toggle');
                        showNotification('Success', response.data.message, 'success');
                        var otherData = {};
                        publicNotificationViaPusher('new_assignment',otherData, response.data.part, 'study_material/student_class_activities', {'sender': response.data.sender});
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                    else
                    {
                        $scope.getNewMaterials();
                        $scope.study3.files = [];
                        $('#assignment').modal('toggle');
                        showNotification('Success', response.data.message, 'success');
                        var otherData = {};
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                });
    
    };

    $scope.homeworkMaterial = function () {
    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'study_material/newAssignment', $scope.study4).then(
                function (response)
                {
                    if(response.data.uploaded=='true')
                    {
                        $scope.getHomework();
                        $scope.study4.files = [];
                        $('#homework_modal').modal('toggle');
                        showNotification('Success', response.data.message, 'success');
                        var otherData = {};
                        publicNotificationViaPusher('new_homework',otherData, response.data.part, 'study_material/student_class_activities', {'sender': response.data.sender});
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                    else
                    {
                        $scope.getHomework();
                        $scope.study4.files = [];
                        $('#homework_modal').modal('toggle');
                        showNotification('Success', response.data.message, 'success');
                        var otherData = {};
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                });
    
    };

    $scope.updateMaterial = function () {
        $http.post(base_url + 'study_material/updateMaterial/', $scope.study2).then(
                function (response) {
                    if (response.data.updated == true) {
                        $('#editupload').modal('toggle');
                        $('.textarea_editor2').data("wysihtml5").editor.clear();
                        Dropzone.forElement("#my-awesome-dropzone3").removeAllFiles(true);
                        $scope.getMaterials();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        showNotification('Success', response.data.message, 'success');
                    } else {
                        $('#edit_alert').removeClass('alert-success').addClass('alert-danger').html(response.data.message).show();
                    }
                    //console.log(response.data);
                }
        )
    }
    
    $scope.newMaterial_general = function () {
    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'study_material/newMaterial_general', $scope.study1).then(
                function (response) {
                var otherData = {
                        'class_id': $scope.study1.class,
                        'batch_id': $scope.study1.section,
                        'subject_id': $scope.study1.subject
                    };
                   
                    $scope.study1.files = [];
                    $('#upload').modal('toggle');
                    showNotification('Success', response.data.message, 'success');
                    publicNotificationViaPusher('new_study_material',otherData, response.data.part, 'study_material/upload', {'sender': response.data.sender});
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.getMaterials();
                })
    }
    
     $scope.updateMaterial_general = function () {
        $http.post(base_url + 'study_material/updateMaterial_general/', $scope.study2).then(
                function (response) {
                    if (response.data.updated == true) {
                        $('#editupload').modal('toggle');
                        $('.textarea_editor2').data("wysihtml5").editor.clear();
                        Dropzone.forElement("#my-awesome-dropzone3").removeAllFiles(true);
                     // $scope.filter_upload_material_general();
                     $scope.getMaterials();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        showNotification('Success', response.data.message, 'success');
                    } else {
                        $('#edit_alert').removeClass('alert-success').addClass('alert-danger').html(response.data.message).show();
                    }
                    //console.log(response.data);
                }
        )
    }

    $scope.updateAssignment = function () {
        $http.post(base_url + 'study_material/updateAssignmnet', $scope.study7).then(
                function (response) {
                    if (response.data.updated == true) {
                        $('#edituploadAssignment').modal('toggle');
                        $('.textarea_editor7').data("wysihtml5").editor.clear();
                        Dropzone.forElement("#my-awesome-assignment-dropzone").removeAllFiles(true);
                        $scope.getNewMaterials();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        showNotification('Success', response.data.message, 'success');
                    } else {
                        $('#edit_alert').removeClass('alert-success').addClass('alert-danger').html(response.data.message).show();
                    }
                    //console.log(response.data);
                }
        );
    };

    $scope.updateHomework = function () {
        $http.post(base_url + 'study_material/updateAssignmnet', $scope.study8).then(
                function (response) {
                    if (response.data.updated == true) {
                        $('#edituploadHomework').modal('toggle');
                        $('.textarea_editor8').data("wysihtml5").editor.clear();
                        Dropzone.forElement("#my-awesome-homework-dropzone").removeAllFiles(true);
                        $scope.getHomework();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        showNotification('Success', response.data.message, 'success');
                    } else {
                        $('#edit_alert').removeClass('alert-success').addClass('alert-danger').html(response.data.message).show();
                    }
                    //console.log(response.data);
                }
        );
    };

    $scope.newMaterialDashboard = function () {
    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'study_material/newMaterial_general', $scope.study).then(
        <!--$http.post(base_url + 'study_material/newMaterial', $scope.study).then(-->
                function (response) {
                var otherData = {
                        'class_id': $scope.study.class,
                        'batch_id': $scope.study.section,
                        'subject_id': $scope.study.subject
                    };
                    $scope.study.files = [];
                    $('#upload').modal('toggle');
                    showNotification('Success', response.data.message, 'success');
                    publicNotificationViaPusher('new_study_material',otherData, response.data.part, 'study_material/upload', {'sender': response.data.sender});
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                })
    }

    $scope.deleteId = function (id) {
        $scope.study.deleteId = id;
    }


    $scope.deleteMaterial = function () {

        $http.post(base_url + 'study_material/deleteMaterial/', $scope.study).then(
                function (response) {
                    if (response.data.deleted == true) {
                        $('#deleteMaterial').modal('toggle');
                        $scope.getMaterials();
                        $scope.study.deleteId = "";
                        showNotification('Success', response.data.message, 'success');
                    }
                    //console.log(response.data);
                }
        )
    }

    $scope.deleteAssignment = function () {
        $http.post(base_url + 'study_material/deleteAssignment/', $scope.study).then(
                function (response) {
                    if (response.data.deleted == true) {
                        console.log("here");
                        $('#deleteAssignment').modal('toggle');
                        $scope.getNewMaterials();
                        $scope.study.deleteId = "";
                        showNotification('Success', response.data.message, 'success');
                    }
                    //console.log(response.data);
                }
        );
    };

    $scope.deleteHomework = function () {
        $http.post(base_url + 'study_material/deleteAssignment/', $scope.study).then(
                function (response) {
                    if (response.data.deleted == true) {
                        console.log("here");
                        $('#deleteHomework').modal('toggle');
                        $scope.getHomework();
                        $scope.study.deleteId = "";
                        showNotification('Success', response.data.message, 'success');
                    }
                    //console.log(response.data);
                }
        );
    };

    

    $scope.resetModal = function () {
        $scope.study1.files = [];
        $scope.study1.title = "";
        $scope.study1.type = "";
        $scope.study1.class = "";
        $scope.study1.section = "";
        $scope.study1.subject = "";
        $scope.study1.uploaded_at = $scope.today;
        $('.textarea_editor').data("wysihtml5").editor.clear();
        $('#message_alert').hide();
        Dropzone.forElement("#my-awesome-dropzone").removeAllFiles(true);
        $scope.upload_form.$setUntouched();
        $scope.upload_form.$setPristine();
    }

    $scope.resetModalAssignment = function () {
        $scope.study3.files = [];
        $scope.study3.title = "";
        $scope.study3.type = "Assignment";
        $scope.study3.due_date = "";
        $scope.study3.class = "";
        $scope.study3.subject = "";
        $scope.study3.marks = "";
        $scope.study3.material_details = "";
        $scope.study3.uploaded_at = $scope.today;
        $('.textarea_editor1').data("wysihtml5").editor.clear();
        $('#message_alert_a').hide();
        Dropzone.forElement("#my-awesome-dropzone_assignment").removeAllFiles(true);
        $(".yasir-ann-select2").val(null).trigger('change.select2'); 
        $scope.upload_form.$setUntouched();
        $scope.upload_form.$setPristine();
        // added for uncheck student and marks
        $("#student_selectall").prop("checked", false);
        $("#checkMarks").prop("checked", false);
        $('#marks').attr('disabled', 'disabled');
    }

    $scope.resetModalHomework = function () {
        $scope.study4.files = [];
        $scope.study4.title = "";
        $scope.study4.type = "Homework";
        $scope.study4.due_date = "";
        $scope.study4.class = "";
        $scope.study4.section = "";
        $scope.study4.subject = "";
        $scope.study4.students = "";
        $scope.study4.marks = "";
        $scope.study4.material_details = "";
        $scope.study4.uploaded_at = $scope.today;
        $('.textarea_editor3').data("wysihtml5").editor.clear();
        $('#message_alert_h').hide();
        Dropzone.forElement("#my-awesome-dropzone_homework").removeAllFiles(true);
        $(".yasir-ann-select2").val(null).trigger('change.select2');
        $scope.upload_form.$setUntouched();
        $scope.upload_form.$setPristine();
        // added for uncheck student and marks
        $("#student_selectall_homework").prop("checked", false);
        $("#checkMarks1").prop("checked", false);
        $('#marks1').attr('disabled', 'disabled');
    }

    $scope.resetModalDashboard = function () {
        $scope.study.files = [];
        $scope.study.title = "";
        $scope.study.type = "";
        $scope.study.class = "";
        $scope.study.section = "";
        $scope.study.subject = "";
        $scope.study.uploaded_at = $scope.today;
        $('.textarea_editor').data("wysihtml5").editor.clear();
        $('#message_alert').hide();
        Dropzone.forElement("#my-awesome-dropzone2").removeAllFiles(true);
    }

    $scope.stduent_submitted_assignment = "";

    $scope.materialEditDropzoneConfig = {
        'options': {// passed into the Dropzone constructor
            'url': base_url + 'study_material/upload_attachments',
            'autoProcessQueue': true,
            'autoDiscover': false,
            'uploadMultiple': true,
            'parallelUploads': 20,
            'maxFilesize': 1024, //MB
            'maxFiles': 100,
            'timeout': 0,
            'acceptedFiles': "image/*, application/pdf, .doc, .docx, .ppt, pptx, .mp4, .avi, .flv, .wmv, .mov, .webm",
            'addRemoveLinks': true,
            'dictRemoveFile': '<?php echo lang("lbl_remove");?>',
            'dictDefaultMessage': '<?php echo lang("drop_here");?>',
            'dictFileTooBig': 'File is bigger than 1GB',
            init: function () {
                var submitButton = document.querySelector("#update_material_btn");
                myDropzone = this;
                submitButton.addEventListener("click", function () {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                    var text = $('.textarea_editor2').val();
                    $scope.study2.text = text;

                    if ($scope.study2.title == "" || $scope.study2.title == undefined){
                        $('#edit_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("material_title_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                    else if ($scope.study2.type == "" || $scope.study2.type == undefined){
                        $('#edit_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("material_type_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                    else if ($scope.study2.class == "" || $scope.study2.class == undefined){
                        $('#edit_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("class_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                    else if ($scope.study2.section == "" || $scope.study2.section == undefined){
                        $('#edit_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("section_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                    else if ($scope.study2.subject == "" || $scope.study2.subject == undefined){
                        $('#edit_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("subject_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }

                    else if (myDropzone.getAcceptedFiles().length == 0 && $scope.study2.text != undefined && $scope.study2.text != ""){
                        $scope.updateMaterial();
                    }
                    else if (myDropzone.getAcceptedFiles().length == 0 && ($scope.study2.text == undefined || $scope.study2.text == "")){
                        $('#edit_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("write_details"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                    else if (myDropzone.getAcceptedFiles().length != 0 || $scope.study2.old_files.length != 0){
                        $scope.updateMaterial();
                    }
                    
                });
                myDropzone.on("complete", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
                myDropzone.on("addedfile", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                });
                myDropzone.on("removedfile", function (file) {
                    $scope.study2.files = $scope.study2.files.filter(function( obj ) {
                        return obj.name !== file.name;
                    });
                });
            }
        },
        'eventHandlers': {
            'sending': function (file, xhr, formData) {
            },
            'success': function (file, response) {
                
                $scope.study2.files = JSON.parse(response);
                $scope.$apply();
            }
        }
    };

    $scope.dropzoneConfig = {
        'options': {// passed into the Dropzone constructor
            'url': base_url + 'study_material/upload_attachments',
            'autoProcessQueue': true,
            'autoDiscover': false,
            'uploadMultiple': true,
            'parallelUploads': 20,
            'maxFilesize': 1024, //MB
            'maxFiles': 100,
            'timeout': 0,
            'acceptedFiles': "image/*, application/pdf, .doc, .docx, .ppt, pptx, .mp4, .avi, .flv, .wmv, .mov, .webm",
            'addRemoveLinks': true,
            'dictRemoveFile': '<?php echo lang("lbl_remove");?>',
            'dictDefaultMessage': '<?php echo lang("drop_here");?>',
            'dictFileTooBig': 'File is bigger than 1GB',
            init: function () {
                var submitButton = document.querySelector("#upload_material");
                myDropzone = this;
                submitButton.addEventListener("click", function () {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                    var text = $('.textarea_editor').val();
                    $scope.study1.text = text;

                    if ($scope.study1.title == "" || $scope.study1.title == undefined){
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("material_title_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                    else if ($scope.study1.type == "" || $scope.study1.type == undefined){
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("material_type_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                    else if ($scope.study1.class == "" || $scope.study1.class == undefined){
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("class_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                    else if ($scope.study1.section == "" || $scope.study1.section == undefined){
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("section_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                    else if ($scope.study1.subject == "" || $scope.study1.subject == undefined){
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("subject_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }

                    else if ($scope.study1.files.length == 0 && $scope.study1.text != undefined && $scope.study1.text != ""){
                        $scope.newMaterial();
                    }
                    else if ($scope.study1.files.length == 0 && ($scope.study1.text == undefined || $scope.study1.text == "")){
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("write_details"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                    else if ($scope.study1.files.length != 0){
                        $scope.newMaterial();
                    }
                    
                });
                myDropzone.on("complete", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
                myDropzone.on("addedfile", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                });
                myDropzone.on("removedfile", function (file) {
                    $scope.study1.files = $scope.study1.files.filter(function( obj ) {
                        return obj.name !== file.name;
                    });
                });
            }
        },
        'eventHandlers': {
            'sending': function (file, xhr, formData) {
            },
            'success': function (file, response) {
                $scope.study1.files = JSON.parse(response);
                $scope.$apply();
            }
        }
    };


    $scope.dropzoneConfigAssignment = {
        'options': {// passed into the Dropzone constructor
            'url': base_url + 'study_material/upload_attachments',
            'autoProcessQueue': true,
            'autoDiscover': false,
            'uploadMultiple': true,
            'parallelUploads': 20,
            'maxFilesize': 1024, //MB
            'maxFiles': 100,
            'timeout': 180000,
            'acceptedFiles': "image/*, application/pdf, .doc, .docx, .ppt, pptx, .mp4, .avi, .flv, .wmv, .mov, .webm",
            'addRemoveLinks': true,
            'dictRemoveFile': '<?php echo lang("lbl_remove");?>',
            'dictDefaultMessage': '<?php echo lang("drop_here");?>',
            'dictFileTooBig': 'File is bigger than 1GB',
            init: function () {
                var submitButton = document.querySelector("#upload_assignment");
                myDropzone = this;
                submitButton.addEventListener("click", function () {
                    var text = $('.textarea_editor1').val();
                    $scope.study3.details = text;
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");

                    if ($scope.study3.title == "" || $scope.study3.title == undefined){

                        $('#message_alert_a').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("material_title_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                    } else if ($scope.study3.due_date == "" || $scope.study3.due_date == undefined) {

                        $('#message_alert_a').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("material_due_date_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                    } else if ($scope.study3.class == "" || $scope.study3.class == undefined) {

                        $('#message_alert_a').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("class_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                    } else if ($scope.study3.section == "" || $scope.study3.section == undefined){
                        $('#message_alert_a').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("section_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                      else if ($scope.study3.subject == "" || $scope.study3.subject == undefined){
                        $('#message_alert_a').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("subject_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                    } else if ($scope.study3.students == "" || $scope.study3.students == undefined){

                        $('#message_alert_a').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("student_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                    } else if ($scope.study3.files.length == 0 && ($scope.study3.details == undefined || $scope.study3.details == "")){

                        $('#message_alert_a').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("write_details"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                    } else if ($scope.study3.files.length == 0 && $scope.study3.details != undefined && $scope.study3.details != ""){

                        $scope.assignmentMaterial();

                    } else if ($scope.study3.files.length != 0){
                        $scope.assignmentMaterial();
                    }


                });
                myDropzone.on("complete", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
                myDropzone.on("addedfile", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                });
                myDropzone.on("removedfile", function (file) {
                    $scope.study3.files = $scope.study3.files.filter(function( obj ) {
                        return obj.name !== file.name;
                    });
                });
            }
        },
        'eventHandlers': {
            'sending': function (file, xhr, formData) {
            },
            'success': function (file, response) {
                $scope.study3.files = $scope.study3.files.concat(JSON.parse(response));
                $scope.$apply();
            }
        }
    };

    

  $scope.dropzoneConfigHomework = {
        'options': {// passed into the Dropzone constructor
            'url': base_url + 'study_material/upload_attachments',
            'autoProcessQueue': true,
            'autoDiscover': false,
            'uploadMultiple': true,
            'parallelUploads': 20,
            'maxFilesize': 1024, //MB
            'maxFiles': 100,
            'timeout': 180000,
            'acceptedFiles': "image/*, application/pdf, .doc, .docx, .ppt, pptx, .mp4, .avi, .flv, .wmv, .mov, .webm",
            'addRemoveLinks': true,
            'dictRemoveFile': '<?php echo lang("lbl_remove");?>',
            'dictDefaultMessage': '<?php echo lang("drop_here");?>',
            'dictFileTooBig': 'File is bigger than 1GB',
            init: function () {
                var submitButton = document.querySelector("#upload_homework");
                myDropzone = this;
                submitButton.addEventListener("click", function () {
                    var text = $('.textarea_editor3').val();
                    $scope.study4.details = text;
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");

                    if ($scope.study4.title == "" || $scope.study4.title == undefined){

                        $('#message_alert_h').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("material_title_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                    } else if ($scope.study4.due_date == "" || $scope.study4.due_date == undefined) {

                        $('#message_alert_h').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("material_due_date_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                    } else if ($scope.study4.class == "" || $scope.study4.class == undefined) {

                        $('#message_alert_h').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("class_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                    } else if ($scope.study4.section == "" || $scope.study4.section == undefined){
                        $('#message_alert_h').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("section_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                      else if ($scope.study4.subject == "" || $scope.study4.subject == undefined){
                        $('#message_alert_h').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("subject_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                    } else if ($scope.study4.students == "" || $scope.study4.students == undefined){

                        $('#message_alert_h').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("student_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    } else if ($scope.study4.files.length == 0 && ($scope.study4.details == undefined || $scope.study4.details == "")){

                        $('#message_alert_a').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("write_details"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                    } else if ($scope.study4.files.length == 0 && $scope.study4.details != undefined && $scope.study4.details != ""){

                        $scope.homeworkMaterial();

                    } else if ($scope.study4.files.length != 0){
                        $scope.homeworkMaterial();
                    }


                });
                myDropzone.on("complete", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
                myDropzone.on("addedfile", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                });
                myDropzone.on("removedfile", function (file) {
                    $scope.study4.files = $scope.study4.files.filter(function( obj ) {
                        return obj.name !== file.name;
                    });
                });
            }
        },
        'eventHandlers': {
            'sending': function (file, xhr, formData) {
            },
            'success': function (file, response) {
                $scope.study4.files = $scope.study4.files.concat(JSON.parse(response));
                $scope.$apply();
            }
        }
    };
    // added new for monitring and material uplaods
    $scope.materialEditDropzoneConfigUpload = {
        'options': {// passed into the Dropzone constructor
            'url': base_url + 'study_material/upload_attachments',
            'autoProcessQueue': true,
            'autoDiscover': false,
            'uploadMultiple': true,
            'parallelUploads': 20,
            'maxFilesize': 1024, //MB
            'maxFiles': 100,
            'timeout': 0,
            'acceptedFiles': "image/*, application/pdf, .doc, .docx, .ppt, pptx, .mp4, .avi, .flv, .wmv, .mov, .webm",
            'addRemoveLinks': true,
            'dictRemoveFile': '<?php echo lang("lbl_remove");?>',
            'dictDefaultMessage': '<?php echo lang("drop_here");?>',
            'dictFileTooBig': 'File is bigger than 1GB',
            init: function () {
                var submitButton = document.querySelector("#update_material_btn");
                myDropzone = this;
                submitButton.addEventListener("click", function () {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                    var text = $('.textarea_editor2').val();
                    $scope.study2.text = text;

                    if ($scope.study2.title == "" || $scope.study2.title == undefined){
                        $('#edit_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("material_title_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                    else if ($scope.study2.type == "" || $scope.study2.type == undefined){
                        $('#edit_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("material_type_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                    else if ($scope.study2.class == "" || $scope.study2.class == undefined){
                        $('#edit_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("class_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                    else if ($scope.study2.section == "" || $scope.study2.section == undefined){
                        $('#edit_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("section_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                    else if ($scope.study2.subject == "" || $scope.study2.subject == undefined){
                        $('#edit_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("subject_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }

                    else if (myDropzone.getAcceptedFiles().length == 0 && $scope.study2.text != undefined && $scope.study2.text != ""){
                        $scope.updateMaterial_general();
                    }
                    else if (myDropzone.getAcceptedFiles().length == 0 && ($scope.study2.text == undefined || $scope.study2.text == "")){
                        $('#edit_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("write_details"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                    else if (myDropzone.getAcceptedFiles().length != 0 || $scope.study2.old_files.length != 0){
                        $scope.updateMaterial_general();
                    }
                    
                });
                myDropzone.on("complete", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
                myDropzone.on("addedfile", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                });
                myDropzone.on("removedfile", function (file) {
                    $scope.study2.files = $scope.study2.files.filter(function( obj ) {
                        return obj.name !== file.name;
                    });
                });
            }
        },
        'eventHandlers': {
            'sending': function (file, xhr, formData) {
            },
            'success': function (file, response) {
                <!--$scope.study2.files = JSON.parse(response);--> 
                 $scope.study2.files = $scope.study2.files.concat(JSON.parse(response)); 
                $scope.$apply();
            }
        }
    };

    $scope.dropzoneConfigAssignmentEdit = {
        'options': {// passed into the Dropzone constructor
            'url': base_url + 'study_material/upload_attachments',
            'autoProcessQueue': true,
            'autoDiscover': false,
            'uploadMultiple': true,
            'parallelUploads': 20,
            'maxFilesize': 1024, //MB
            'maxFiles': 100,
            'timeout': 180000,
            'acceptedFiles': "image/*, application/pdf, .doc, .docx, .ppt, pptx, .mp4, .avi, .flv, .wmv, .mov, .webm",
            'addRemoveLinks': true,
            'dictRemoveFile': '<?php echo lang("lbl_remove");?>',
            'dictDefaultMessage': '<?php echo lang("drop_here");?>',
            'dictFileTooBig': 'File is bigger than 1GB',
            init: function () {
                var submitButton = document.querySelector("#upload_assignment_edit");
                myDropzone = this;
                submitButton.addEventListener("click", function () {
                    var text = $('.textarea_editor7').val();
                    $scope.study7.details = text;
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");

                    if ($scope.study7.title == "" || $scope.study7.title == undefined){

                        $('#message_alert_a_e').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("material_title_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                    } else if ($scope.study7.due_date == "" || $scope.study7.due_date == undefined) {

                        $('#message_alert_a_e').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("material_due_date_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                    } else if ($scope.study7.class == "" || $scope.study7.class == undefined) {

                        $('#message_alert_a_e').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("class_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                    } else if ($scope.study7.section == "" || $scope.study7.section == undefined){
                        $('#message_alert_a_e').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("section_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                      else if ($scope.study7.subject == "" || $scope.study7.subject == undefined){
                        $('#message_alert_a_e').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("subject_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                    } else if ($scope.study7.students == "" || $scope.study7.students == undefined){

                        $('#message_alert_a_e').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("student_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    } else if ($scope.study7.files.length == 0 && ($scope.study7.details == undefined || $scope.study7.details == "")){

                        $('#message_alert_a_e').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("write_details"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                    } else if ($scope.study7.files.length == 0 && $scope.study7.details != undefined || $scope.study7.details != ""){

                        $scope.updateAssignment();

                    } else if ($scope.study7.files.length != 0){
                        $scope.updateAssignment();
                    }


                });
                myDropzone.on("complete", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
                myDropzone.on("addedfile", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                });
                myDropzone.on("removedfile", function (file) {
                    $scope.study7.files = $scope.study7.files.filter(function( obj ) {
                        return obj.name !== file.name;
                    });
                });
            }
        },
        'eventHandlers': {
            'sending': function (file, xhr, formData) {
            },
            'success': function (file, response) {
                $scope.study7.files = $scope.study7.files.concat(JSON.parse(response));
                $scope.$apply();
            }
        }
    };

    $scope.dropzoneConfigHomeworkEdit = {
        'options': {// passed into the Dropzone constructor
            'url': base_url + 'study_material/upload_attachments',
            'autoProcessQueue': true,
            'autoDiscover': false,
            'uploadMultiple': true,
            'parallelUploads': 20,
            'maxFilesize': 1024, //MB
            'maxFiles': 100,
            'timeout': 180000,
            'acceptedFiles': "image/*, application/pdf, .doc, .docx, .ppt, pptx, .mp4, .avi, .flv, .wmv, .mov, .webm",
            'addRemoveLinks': true,
            'dictRemoveFile': '<?php echo lang("lbl_remove");?>',
            'dictDefaultMessage': '<?php echo lang("drop_here");?>',
            'dictFileTooBig': 'File is bigger than 1GB',
            init: function () {
                var submitButton = document.querySelector("#upload_homework_edit");
                myDropzone = this;
                submitButton.addEventListener("click", function () {
                    var text = $('.textarea_editor8').val();
                    $scope.study8.details = text;
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");

                    if ($scope.study8.title == "" || $scope.study8.title == undefined){

                        $('#message_alert_h_e').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("material_title_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                    } else if ($scope.study8.due_date == "" || $scope.study8.due_date == undefined) {

                        $('#message_alert_h_e').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("material_due_date_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                    } else if ($scope.study8.class == "" || $scope.study8.class == undefined) {

                        $('#message_alert_h_e').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("class_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                    } else if ($scope.study8.section == "" || $scope.study8.section == undefined){
                        $('#message_alert_h_e').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("section_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                      else if ($scope.study8.subject == "" || $scope.study8.subject == undefined){
                        $('#message_alert_h_e').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("subject_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                    } else if ($scope.study8.students == "" || $scope.study8.students == undefined){

                        $('#message_alert_h_e').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("student_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    } else if ($scope.study8.files.length == 0 && ($scope.study8.details == undefined || $scope.study8.details == "")){

                        $('#message_alert_h_e').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("write_details"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                    } else if ($scope.study8.files.length == 0 && $scope.study8.details != undefined || $scope.study8.details != ""){

                        $scope.updateHomework();

                    } else if ($scope.study8.files.length != 0){
                        $scope.updateHomework();
                    }


                });
                myDropzone.on("complete", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
                myDropzone.on("addedfile", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                });
                myDropzone.on("removedfile", function (file) {
                    $scope.study8.files = $scope.study8.files.filter(function( obj ) {
                        return obj.name !== file.name;
                    });
                });
            }
        },
        'eventHandlers': {
            'sending': function (file, xhr, formData) {
            },
            'success': function (file, response) {
                $scope.study8.files = $scope.study8.files.concat(JSON.parse(response));
                $scope.$apply();
            }
        }
    };    

    
$scope.materialNewDropzoneConfigUpload = {
        'options': {// passed into the Dropzone constructor
            'url': base_url + 'study_material/upload_attachments',
            'autoProcessQueue': true,
            'autoDiscover': false,
            'uploadMultiple': true,
            'parallelUploads': 20,
            'maxFilesize': 2048, //MB
            'maxFiles': 100,
            'timeout': 0,
            'acceptedFiles': "image/*, application/pdf, .doc, .docx, .ppt, pptx, .mp4, .avi, .flv, .wmv, .mov, .webm",
            'addRemoveLinks': true,
            'dictRemoveFile': '<?php echo lang("lbl_remove");?>',
            'dictDefaultMessage': '<?php echo lang("drop_here");?>',
            'dictFileTooBig': 'File is bigger than 1GB',
            init: function () {
                var submitButton = document.querySelector("#upload_material");
                myDropzone = this;
                submitButton.addEventListener("click", function () {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                    var text = $('.textarea_editor').val();
                    $scope.study1.text = text;

                    if ($scope.study1.title == "" || $scope.study1.title == undefined){
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("material_title_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                    else if ($scope.study1.type == "" || $scope.study1.type == undefined){
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("material_type_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                    else if ($scope.study1.class == "" || $scope.study1.class == undefined){
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("class_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                    else if ($scope.study1.section == "" || $scope.study1.section == undefined){
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("section_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                    else if ($scope.study1.subject == "" || $scope.study1.subject == undefined){
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("subject_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }

                    else if ($scope.study1.files.length == 0 && $scope.study1.text != undefined && $scope.study1.text != ""){
                        $scope.newMaterial_general();
                    }
                    else if ($scope.study1.files.length == 0 && ($scope.study1.text == undefined || $scope.study1.text == "")){
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("write_details"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                    else if ($scope.study1.files.length != 0){
                        $scope.newMaterial_general();
                    }
                    
                });
                myDropzone.on("complete", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
                myDropzone.on("addedfile", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                });
                myDropzone.on("removedfile", function (file) {
                    $scope.study1.files = $scope.study1.files.filter(function( obj ) {
                        return obj.name !== file.name;
                    });
                });
            }
        },
        'eventHandlers': {
            'sending': function (file, xhr, formData) {
            },
            'success': function (file, response) {
                <!--$scope.study1.files = JSON.parse(response);-->
                 $scope.study1.files = $scope.study1.files.concat(JSON.parse(response)); 
                $scope.$apply();
            }
        }
    };

    $scope.dropzoneConfigDashboard = {
        'options': {// passed into the Dropzone constructor
            'url': base_url + 'study_material/upload_attachments',
            'autoProcessQueue': true,
            'autoDiscover': false,
            'uploadMultiple': true,
            'parallelUploads': 20,
            'maxFilesize': 1024, //MB
            'maxFiles': 100,
            'timeout': 0,
            'acceptedFiles': "image/*, application/pdf, .doc, .docx, .ppt, .pptx, .mp4, .avi, .flv, .wmv, .mov, .webm",
            'addRemoveLinks': true,
            'dictRemoveFile': '<?php echo lang("lbl_remove");?>',
            'dictDefaultMessage': '<?php echo lang("drop_here");?>',
            'dictFileTooBig': 'File is bigger than 1GB',
            init: function () {
                var submitButton = document.querySelector("#upload_material_dashboard");
                myDropzone = this;
                submitButton.addEventListener("click", function () {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                    var text = $('.textarea_editor').val();
                    $scope.study.text = text;

                    if ($scope.study.title == "" || $scope.study.title == undefined){
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("material_title_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                    else if ($scope.study.type == "" || $scope.study.type == undefined){
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("material_type_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                    else if ($scope.study.class == "" || $scope.study.class == undefined){
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("class_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                    else if ($scope.study.section == "" || $scope.study.section == undefined){
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("section_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                    else if ($scope.study.subject == "" || $scope.study.subject == undefined){
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("subject_req"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }

                    else if (myDropzone.getAcceptedFiles().length == 0 && $scope.study.text != undefined && $scope.study.text != ""){
                        $scope.newMaterialDashboard();
                    }
                    else if (myDropzone.getAcceptedFiles().length == 0 && ($scope.study.text == undefined || $scope.study.text == "")){
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("write_details"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    }
                    else if (myDropzone.getAcceptedFiles().length != 0){
                        $scope.newMaterialDashboard();
                    }
                    
                });
                myDropzone.on("complete", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
                myDropzone.on("addedfile", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                });
                myDropzone.on("removedfile", function (file) {
                    $scope.study.files = $scope.study.files.filter(function( obj ) {
                        return obj.name !== file.name;
                    });
                });
            }
        },
        'eventHandlers': {
            'sending': function (file, xhr, formData) {
            },
            'success': function (file, response) {
                $scope.study.files = $scope.study.files.concat(JSON.parse(response));
                <!--$scope.study.files = JSON.parse(response);-->
                $scope.$apply();
            }
        }
    };

    $scope.submittedAssignments = function(valid) {
      if(valid){
        Loading("body", "<?php echo lang("loading_datatable") ?>", "", "show");
        $http.post(base_url + 'study_material/filter_submitted_assignments1', $scope.study).then(
          function (response) {
              $scope.stduent_submitted_assignment = response.data;
              Loading("body", "<?php echo lang("loading_datatable") ?>", "", "hide");
              $(".AssignmentsContainer2").addClass("hidden");
              $(".AssignmentsContainer1").removeClass("hidden");
              //console.log($scope.stduent_submitted_assignment);
          });
      }
  };

  $scope.initSubmittedAss = function() {
      Loading("body", "<?php echo lang("loading_datatable") ?>", "", "show");
      $http.post(base_url + 'study_material/currentDateSubmittedAss', "", config).then(
                function (response) {
                  $scope.stduent_submitted_assignment = response.data;
                  $(".AssignmentsContainer2").addClass("hidden");
                  $(".AssignmentsContainer1").removeClass("hidden");
                  Loading("body", "<?php echo lang("loading_datatable") ?>", "", "hide");
                });
  };

  $scope.initSubmittedHom = function() {
      Loading("body", "<?php echo lang("loading_datatable") ?>", "", "show");
      $http.post(base_url + 'study_material/currentDateSubmittedHom', "", config).then(
                function (response) {
                  $scope.stduent_submitted_homework = response.data;
                  $(".HomeworkContainer2").addClass("hidden");
                  $(".HomeworkContainer1").removeClass("hidden");
                  console.log($scope.stduent_submitted_homework);
                  Loading("body", "<?php echo lang("loading_datatable") ?>", "", "hide");
                });
  };
  
  $scope.submittedHomework = function() {
      Loading("body", "<?php echo lang("loading_datatable") ?>", "", "show");
      $http.post(base_url + 'study_material/filter_submitted_homework', $scope.studyHom).then(
            function (response) {
            $scope.stduent_submitted_homework = response.data;
              Loading("body", "<?php echo lang("loading_datatable") ?>", "", "hide");
              $(".HomeworkContainer2").addClass("hidden");
              $(".HomeworkContainer1").removeClass("hidden");
              console.log($scope.stduent_submitted_homework);
            });
  };

  $scope.getSections_homework = function (){
        $scope.class_error = false;
         Loading("#getBatch_filter", "<?php echo lang("loading_datatable") ?>", "", "show");
            $http.post(base_url + 'attendance/getClassBatches', {class_id: $scope.studyHom.class}, config).then(
              function (response) {
                  $scope.studyHom.batches = response.data;
                  $scope.studyHom.subject = "";
              });
           Loading("#getBatch_filter", "<?php echo lang("loading_datatable") ?>", "", "hide");    
    };

  $scope.getSubjects_homework = function (){
        $scope.section_error = false;
        Loading("#getSubjects_filter", "<?php echo lang("loading_datatable") ?>", "", "show");
        $http.post(base_url + 'study_material/getSubjects', {class: $scope.studyHom.class, section: $scope.studyHom.section}).then(
          function (response) {
              $scope.studyHom.subjects = response.data.subjects;
              $scope.studyHom.subject = "";
          });
        Loading("#getSubjects_filter", "<?php echo lang("loading_datatable") ?>", "", "hide");
    };

    $scope.Assinged_student_detials = {};
    $scope.Assignment_details = {};
    $scope.Assignment_details.files = [];

    $scope.showAllAssignments = function(std){

      $scope.show_Assignments = std.assignments;
      
      $scope.Assinged_student_detials = std.student;

      $(".AssignmentsContainer1").addClass("hidden");
      $(".AssignmentsContainer2").removeClass("hidden");

  };

  $scope.showAssignmentDetail =  function(ass){
    
      $http.post(base_url + 'study_material/set_viewed_assignment_homework', {assignment_id: ass.submit_id}).then(
        function (response) {
            //console.log(response);
        });

    

    if(typeof ass.files === "string" && typeof ass.files === "string"){
        ass.files = ass.files.split(',');
        ass.submitted_files = ass.submitted_files.split(',');     
    }

    $scope.Assignment_details = ass;

    
   for (var i = 0, len = $scope.Assignment_details.details.length; i < len; i++) {
        $scope.Assignment_details.details[i].details = $sce.trustAsHtml($scope.Assignment_details.details[i].details);
    }
     
    //console.log($scope.Assignment_details);
  };

  $scope.showHomeworkDetail =  function(hom){
    
      $http.post(base_url + 'study_material/set_viewed_assignment_homework', {assignment_id: hom.submit_id}).then(
        function (response) {
            //console.log(response);
        });

    

    if(typeof hom.files === "string" && typeof hom.files === "string"){
        hom.files = hom.files.split(',');
        hom.submitted_files = hom.submitted_files.split(',');     
    }

    $scope.Homework_details = hom;

    
   for (var i = 0, len = $scope.Homework_details.details.length; i < len; i++) {
        $scope.Homework_details.details[i].details = $sce.trustAsHtml($scope.Homework_details.details[i].details);
    }
     
    console.log($scope.Homework_details);
  };

  $scope.SaveAssignmentMark  = function(){
    console.log($scope.Assignment_details);
    Loading("body", "<?php echo lang("loading_datatable") ?>", "", "show");
        $http.post(base_url + 'study_material/SaveAssignmentMark', $scope.Assignment_details).then(
          function (response) {
              $('#markAssignment').modal('toggle');
              showNotification('Success', response.data.message, 'success');
              Loading("body", "<?php echo lang("loading_datatable") ?>", "", "hide");
              $scope.msg = response.data.message;
          });
          Loading("body", "<?php echo lang("loading_datatable") ?>", "", "hide");
  };

  $scope.SaveHomeworkMark  = function(){
    console.log($scope.Homework_details);
    Loading("body", "<?php echo lang("loading_datatable") ?>", "", "show");
        $http.post(base_url + 'study_material/SaveHomeworkMark', $scope.Homework_details).then(
          function (response) {
              $('#markHomework').modal('toggle');
              showNotification('Success', response.data.message, 'success');
              Loading("body", "<?php echo lang("loading_datatable") ?>", "", "hide");
              $scope.msg = response.data.message;
          });
          Loading("body", "<?php echo lang("loading_datatable") ?>", "", "hide");
  };

  $scope.backAllAssignments = function(){

    $(".AssignmentsContainer1").removeClass("hidden");
    $(".AssignmentsContainer2").addClass("hidden");

  };

  $scope.showAllHomework = function(std_hom){

    $scope.show_Homeworks = std_hom.Homeworks;
      
    $scope.Assinged_student_detials1 = std_hom.student;

    $(".HomeworkContainer1").addClass("hidden");
    $(".HomeworkContainer2").removeClass("hidden");
  };

  $scope.backAllHomework = function(){
    $(".HomeworkContainer1").removeClass("hidden");
    $(".HomeworkContainer2").addClass("hidden");
  }; 

});

app2.controller('downloadController', function ($scope, $http, $sce) {
    $scope.study = {
        class : '',
        section : '',
        subject : '',
        type : '',
        date : ''
    };
    $scope.study1 = {
        class : '',
        section : '',
        subject : '',
        type : '',
        date : ''
    };
    $scope.study2 = {
        class : '',
        section : '',
        subject : '',
        type : '',
        date : ''
    };
    $scope.study.files = [];
    $scope.study1.files = [];
    $scope.edit = {};
    $scope.details = {};
    $scope.study_upload = {};
    $scope.temp = {};

    $scope.init = function () {
        $http.post(base_url + 'attendance/getClasses', "", config).then(
                function (response) {
                    $scope.study.classes = response.data;
                });
    };

    $scope.getSections = function () {
        //console.log($scope.study.class);
        $scope.class_error = false;
        $http.post(base_url + 'attendance/getClassBatches', {class_id: $scope.study.class}, config).then(
                function (response) {
                    $scope.study.batches = response.data;
                    $scope.study.subject = "";
                    $scope.study.subjects = "";
                    $scope.study.section = "";
                });
    };

    $scope.getSubjects = function () {
        $scope.section_error = false;
        $http.post(base_url + 'study_material/getSubjects', {class: $scope.study.class, section: $scope.study.section}).then(
                function (response) {
                    $scope.study.subjects = response.data.subjects;
                    $scope.study.subject = "";
                });
    };

    $scope.getMaterials = function () {
        $http.post(base_url + 'study_material/getDownloadMaterials').then(
                function (response) {
                    $scope.study.materials = response.data;

                    for (var i = 0, len = $scope.study.materials.length; i < len; i++) {
                        $scope.study.materials[i].details = $sce.trustAsHtml($scope.study.materials[i].details);
                    }
                });
    };

    $scope.subjectChanged = function () {
        $scope.subject_error = false;
    };

    $scope.typeChanged = function () {
        $scope.type_error = false;
    };

    $scope.filter = function () {

        $scope.class_error = false;
        $scope.section_error = false;
        $scope.subject_error = false;
        $scope.type_error = false;
        $scope.date_error = false;
        $check = true;


        if ($scope.study.class == undefined || $scope.study.class == "") {
            $scope.class_error = true;
            $check = false;
        }
        if ($scope.study.section == undefined || $scope.study.section == "") {
            $scope.section_error = true;
            $check = false;
        }
        if ($scope.study.subject == undefined || $scope.study.subject == "") {
            $scope.subject_error = true;
            $check = false;
        }
        if ($scope.study.type == undefined || $scope.study.type == "") {
            $scope.type_error = true;
            $check = false;
        }
        if ($scope.study.date == undefined || $scope.study.date == "") {
            $scope.date_error = true;
            $check = false;
        }

        if ($check) {
            Loading("#download_search_filter", "<?php echo lang("loading_datatable") ?>", "", "show");
            $http.post(base_url + 'study_material/filter', $scope.study).then(
            function (response) {
                $scope.study.materials = response.data.materials;
                for (var i = 0, len = $scope.study.materials.length; i < len; i++) {
                    $scope.study.materials[i]['details'] = $sce.trustAsHtml($scope.study.materials[i]['details']);
                }
                Loading("#download_search_filter", "<?php echo lang("loading_datatable") ?>", "", "hide");
            });
        }

    };
    $scope.filter_general = function () {

$scope.class_error = false;
$scope.section_error = false;
$scope.subject_error = false;
$scope.type_error = false;
$scope.date_error = false;
$check = true;

console.log($scope.study);
if ($scope.study.class == undefined || $scope.study.class == "") {
$scope.class_error = true;
$check = false;
}
if ($scope.study.section == undefined || $scope.study.section == "") {
$scope.section_error = true;
$check = false;
}

if ($check) {
Loading("body", "<?php echo lang("loading_datatable") ?>", "", "show");
$http.post(base_url + 'study_material/filter_studymaterialGeneral', $scope.study).then(
function (response) {
$scope.study.materials = response.data.materials;
for (var i = 0, len = $scope.study.materials.length; i < len; i++) {
$scope.study.materials[i]['details'] = $sce.trustAsHtml($scope.study.materials[i]['details']);
}
Loading("body", "<?php echo lang("loading_datatable") ?>", "", "hide");
});
}

};

    $scope.removeFilter = function () {
        Loading("body", "", "", "show");
        $scope.getMaterials();
        Loading("body", "", "", "hide");
        $scope.class_error = false;
        $scope.section_error = false;
        $scope.subject_error = false;
        $scope.type_error = false;
        $scope.study.class = '';
        $scope.study.section = '';
        $scope.study.subject = '';
        $scope.study.type = '';
    };

    $scope.details_set = function (mat) {

        $scope.details.title = mat.title;
        $scope.details.content_type = mat.content_type;
        $scope.details.subject_name = mat.subject_name;
        $scope.details.uploaded_time = mat.uploaded_time;
        $scope.details.files = mat.files;
        $scope.details.details = mat.details;
        $scope.details.id = mat.id;
        // google drive
        $scope.details.storage_type=mat.storage_type;
        $scope.details.file_names=mat.file_names;
        $scope.details.filesurl=mat.filesurl;
        $scope.details.thumbnail_links = mat.thumbnail_links;
        $scope.details.icon_links = mat.icon_links;
    };

    $scope.download = function () {
        Loading("body", "", "", "show");
        $http.post(base_url + 'study_material/zip', $scope.details).then(
                function (response) {
                    //window.location.href = response.data.path;
                    paths = response.data.paths;
                    angular.forEach(paths, function (value, key) {
                        var link = document.createElement('a');
                        link.href = value;
                        link.download = key;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    });
                    Loading("body", "", "", "hide");
                });
    };
});


//*************************************//
//   Notification Profile controllers //
//************************************//

app2.controller('profileController', function ($scope, $http, $sce) {
    $scope.countNotification = 0;
    $scope.notifications = {};
    $scope.allCount = 0;
    $scope.requestResponse = {};

    $scope.start_date = '';
    $scope.end_date = '';
    $scope.announcement = [];
    
    $scope.countNotifications = function () {

        $http.post(base_url + 'notification/countNotification', config).then(
                function (success) {
                    $scope.countNotification = success.data;
                    //console.log(success.data);
                },
                function (error) {
                    console.log(error.data);
                }
        );

    };
    $scope.allNotifications = function () {

        $http.post(base_url + "notification/allNotifications", config).then(
                function (success) {
                    $scope.notifications = success.data.allNotifications;
                    //console.log($scope.notifications);
                    //$scope.allCount = success.data.allInbox;

                    //console.log(success.data);
                },
                function (error) {
                    console.log(error.data);
                }
        );

    };

    $scope.show = function (id) {
        $http.post(base_url + "notification/show", {"id": id}, config).then(
                function (success) {
                    $scope.showNotification = success.data.showNotification;
                    console.log(success.data.showNotification);
                    $scope.countNotifications();
                    $scope.allNotifications();
                },
                function (error) {
                    console.log(error.data);
                }
        );
    };
    // notificatiion Test Method.
    $scope.clickMe = function () {
        alert("click me");
        var msg_key = "noti_due_fee";
        var recipient = ["89", "90", "81", "84"];
        var url = "base_url";
        var data = {'student_name': 'Azeem', 'fees_type': 'Re-Admission'};
        publicNotificationViaPusher(msg_key, recipient, url, data);
    };
    
    $scope.showDetails = function(id,cls_id, batch_id, type, date, request){
        var formModel = {id:id,class_id:cls_id, batch_id:batch_id, type:type, date:date, request_id:request};
        $http.post(base_url + "dashboard/fetchReqeustDetails", formModel, config).then(
            function (success) {
                $scope.requestResponse = success.data;
            },
            function (error) {
                console.log(error.data);
            }
        );
        console.log(id,cls_id, batch_id, type, date, request);
    };


   /* $scope.activeAcademicYear = function () {
       
           $http.post(base_url + "notification/activeAcademicYear", '', config).then(
                    function (success) {
                        $scope.start_date = success.datastart_date;
                        $scope.end_date = success.data.end_date;
                        
                    },
                    function (error) {
                        $window.location.href = 'errors/' + error.status;
                    }
            );
        };
        */
        
        $scope.closeNav = function (){
            $("#myNavAnnouncement").css({"width":"0"});
        };
    
        $scope.get_announcement = function(){
            $http.post(base_url + "announcements/get", config).then(
                function (success) {
                    if (success.data == "" ){
                        $("#myNavAnnouncement").css({"width":"0"});
                    } 
                    else {
                        $scope.announcement = success.data;

                        for (var i = 0, len = $scope.announcement.length; i < len; i++) {

                          $scope.announcement[i].details = $sce.trustAsHtml($scope.announcement[i].details);

                        }
                        var arr = window.location.href.split("/");
                        if(arr[arr.length-1] == "dashboard"){
                            $("#myNavAnnouncement").css({"width":"100%"});
                        }
                    }
                },
                function (error) {
                    console.log(error.data);
                }
            ); 
        };    
        


});

function publicNotificationViaPusher(msg_key, otherData, recipient, url, data, r_id) {
   $.ajax({
       url: base_url + "notification/sendNotificationViaPusher",
       method: "post",
       data: {msg_key: msg_key, recipient: recipient, url: url, data: data, r_id: r_id, other: otherData},
       success: function(success) {
           console.log(success);
       },
       error: function (error) {
           console.log(error.data);
       }
   });
}

function getClassName(id){
    var className = null;
    // Get Class name
    $.ajax({
        async: false,
        url: base_url + "common/getClassName",
        method: "post",
        global: false,
        data: { id:id },
        dataType: "json",
        success: function (result) {
            className = result.name;
        },
        error: function (result) {
            console.log(result);
        }
    });
    return className;
}

function getBatchName(id){
    var batchName = null;
    // Get Batch name
    $.ajax({
        async: false,
        url: base_url + "common/getBatchName",
        method: "post",
        global: false,
        data: { id:id },
        dataType: "json",
        success: function (result) {
            batchName = result.name;
        },
        error: function (result) {
            console.log(result);
        }
    });
    return batchName;
}

function printD(){
    var cls_id = $("#cls").val();
    var bth_id = $("#bth").val();
    var month = $("#month").val();
    
    var clsName = getClassName(cls_id);
    var bthName = getBatchName(bth_id);
    
    var header = "<p><label>Class name:</label>"+clsName+"<br/><label>Batch name:</label>"+bthName+"<br/><label>Month:</label>"+month+"</p>";
    
    $("#sheet").print({
        //Use Global styles
        globalStyles : false,
        //Add link with attrbute media=print
        mediaPrint : false,
        //Custom stylesheet
        stylesheet : "<?php echo base_url(); ?>assets/css/common.css?<?php echo date("h:i:s"); ?>",
        //Print in a hidden iframe
        iframe : false,
        //Don't print this
        noPrintSelector : ".avoid-this",
        //Add this at top
        prepend : header,
        //Add this on bottom
        //append : "<br/>Buh Bye!",
        //Log to console when printing is done via a deffered callback
        deferred: $.Deferred().done(function() { console.log('Printing done', arguments); })
    });
}

function printD2(){
    var month = $("#month").val();
    
    
    
    var header = "<p>Employee Attendance Report</p><p><label>Period:</label>"+month+"</p>";
    
    $("#sheet").print({
        //Use Global styles
        globalStyles : false,
        //Add link with attrbute media=print
        mediaPrint : false,
        //Custom stylesheet
        stylesheet : "<?php echo base_url(); ?>assets/css/common.css?<?php echo date("h:i:s"); ?>",
        //Print in a hidden iframe
        iframe : false,
        //Don't print this
        noPrintSelector : ".avoid-this",
        //Add this at top
        prepend : header,
        //Add this on bottom
        //append : "<br/>Buh Bye!",
        //Log to console when printing is done via a deffered callback
        deferred: $.Deferred().done(function() { console.log('Printing done', arguments); })
    });
}

//***************************************//
// End Notification Profile controllers  //
//***************************************//

app2.controller('parentAdmissionController', function ($scope, $http, $window, $location) {
/*--- Image cropper ---*/
    $scope.blockingObject = {block: true};
    $scope.callTestFuntion = function () {
        $scope.blockingObject.render(function (dataURL) {
            //console.log($scope.blockingObject);
            //console.log('via render');
            //console.log(dataURL.length);
        });
    };
    $scope.blockingObject.callback = function (dataURL) {
        //console.log('via function');
        //console.log(dataURL.length);
    };
    $scope.size = 'small';
    $scope.type = 'square';
    $scope.imageDataURI = '';
    $scope.resImageDataURI = '';
    $scope.resBlob = {};
    $scope.urlBlob = {};
    $scope.resImgFormat = 'image/jpeg';
    $scope.resImgQuality = 0.9;
    $scope.selMinSize = 100;
    $scope.selInitSize = [{w: 200, h: 80}];
    $scope.resImgSize = [{w: 300, h: 300}, {w: 300, h: 300}];
    //$scope.aspectRatio=1.2;
    $scope.onChange = function ($dataURI) {
        //console.log('onChange fired');
    };
    $scope.onLoadBegin = function () {
        //console.log('onLoadBegin fired');
    };
    $scope.onLoadDone = function () {
        //console.log('onLoadDone fired');
    };
    $scope.onLoadError = function () {
        //console.log('onLoadError fired');
    };
    $scope.getBlob = function () {
        //console.log($scope.resBlob);
    };
    var handleFileSelect = function (evt) {
        var file = evt.currentTarget.files[0],
                reader = new FileReader();
        if (navigator.userAgent.match(/iP(hone|od|ad)/i)) {
            var canvas = document.createElement('canvas'),
                    mpImg = new MegaPixImage(file);

            canvas.width = mpImg.srcImage.width;
            canvas.height = mpImg.srcImage.height;

            EXIF.getData(file, function () {
                var orientation = EXIF.getTag(this, 'Orientation');

                mpImg.render(canvas, {
                    maxHeight: $scope.resImgSize,
                    orientation: orientation
                });
                setTimeout(function () {
                    var tt = canvas.toDataURL("image/jpeg", 1);
                    $scope.$apply(function ($scope) {
                        $scope.imageDataURI = tt;
                    });
                }, 100);
            });
        } else {
            reader.onload = function (evt) {
                $scope.$apply(function ($scope) {
                    //console.log(evt.target.result);
                    $scope.imageDataURI = evt.target.result;
                });
            };
            reader.readAsDataURL(file);
        }
    };
    angular.element(document.querySelector('#fileInput')).on('change', handleFileSelect);
    $scope.$watch('resImageDataURI', function () {
        //console.log('Res image', $scope.resImageDataURI);
    });
    /*--- Image cropper ---*/
    $scope.formModel = {
        pCity: "",
        pDob: "",
        pOccupation: "",
        pIncome: "",
        pPhone: "",
        pStreet: "",
        pIdNumber: "",
        pCity: "",
        pName2:"",
        pPhone2:""
    };

    $scope.alert = {};
    $scope.onSubmit = function (valid, image, image1) {
        if (valid) {
            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");

            if ($scope.imageDataURI) {
                $scope.formModel.pAvatar = $scope.resImageDataURI;
            } else {
                $scope.formModel.pAvatar = null;
            }
           


            $http.post(base_url + 'parents/save', $scope.formModel, config).then(
                    function (success) {
                        $window.scrollTo(0, 0);
                        if (success.data.status === "success") {
                            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                            $scope.formModel = {
                                pCity: "",
                                pDob: "",
                                pOccupation: "",
                                pIncome: "",
                                pPhone: "",
                                pStreet: "",
                                pIdNumber: "",
                                pCity: "",
                                pEmail:"",
                                pName2:"",
                                pPhone2:"",
                                relation: ""
                            };
                            $scope.resImageDataURI = '';
                            $scope.parentAddmissionForm.$setUntouched();
                            $scope.parentAddmissionForm.$setPristine();
                            $scope.alert.message = success.data.message;
                            $scope.alert.type = "alert-success";
                            $scope.image2 = false;
                            $scope.image3 = false;
                            //console.log(success.data);
                        } else if (success.data.status === "error") {
                            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                            $scope.alert.message = success.data.message;
                            $scope.alert.type = "alert-danger";
                        }
                    },
                    function (error) {
                        console.log(error.data);
                        //$window.location.href = 'errors/' + error.status;
                    }
            );
            //console.log($scope.formModel);
        }
    };
});

app2.controller('parentEditController', function ($scope, $http, $window, $location) {
/*--- Image cropper ---*/
    $scope.blockingObject = {block: true};
    $scope.callTestFuntion = function () {
        $scope.blockingObject.render(function (dataURL) {
            //console.log($scope.blockingObject);
            //console.log('via render');
            //console.log(dataURL.length);
        });
    };
    $scope.blockingObject.callback = function (dataURL) {
        //console.log('via function');
        //console.log(dataURL.length);
    };
    $scope.size = 'small';
    $scope.type = 'square';
    $scope.imageDataURI = '';
    $scope.resImageDataURI = '';
    $scope.resBlob = {};
    $scope.urlBlob = {};
    $scope.resImgFormat = 'image/jpeg';
    $scope.resImgQuality = 0.9;
    $scope.selMinSize = 100;
    $scope.selInitSize = [{w: 200, h: 80}];
    $scope.resImgSize = [{w: 300, h: 300}, {w: 300, h: 300}];
    //$scope.aspectRatio=1.2;
    $scope.onChange = function ($dataURI) {
        //console.log('onChange fired');
    };
    $scope.onLoadBegin = function () {
        //console.log('onLoadBegin fired');
    };
    $scope.onLoadDone = function () {
        //console.log('onLoadDone fired');
    };
    $scope.onLoadError = function () {
        //console.log('onLoadError fired');
    };
    $scope.getBlob = function () {
        //console.log($scope.resBlob);
    };
    var handleFileSelect = function (evt) {
        var file = evt.currentTarget.files[0],
                reader = new FileReader();
        if (navigator.userAgent.match(/iP(hone|od|ad)/i)) {
            var canvas = document.createElement('canvas'),
                    mpImg = new MegaPixImage(file);

            canvas.width = mpImg.srcImage.width;
            canvas.height = mpImg.srcImage.height;

            EXIF.getData(file, function () {
                var orientation = EXIF.getTag(this, 'Orientation');

                mpImg.render(canvas, {
                    maxHeight: $scope.resImgSize,
                    orientation: orientation
                });
                setTimeout(function () {
                    var tt = canvas.toDataURL("image/jpeg", 1);
                    $scope.$apply(function ($scope) {
                        $scope.imageDataURI = tt;
                    });
                }, 100);
            });
        } else {
            reader.onload = function (evt) {
                $scope.$apply(function ($scope) {
                    //console.log(evt.target.result);
                    $scope.imageDataURI = evt.target.result;
                });
            };
            reader.readAsDataURL(file);
        }
    };
    angular.element(document.querySelector('#fileInput')).on('change', handleFileSelect);
    $scope.$watch('resImageDataURI', function () {
        //console.log('Res image', $scope.resImageDataURI);
    });
    /*--- Image cropper ---*/
    $scope.formModel = {};

    $scope.alert = {};


    $scope.fetchParent = function (id) {
        $http.post(base_url + 'parents/getParent', {'parent_id': id}, config).then(
                function (success) {
                    $scope.formModel = success.data;
                    if($scope.formModel.country==0){
                        $scope.formModel.country='';
                    }
                    //console.log(success.data);
                },
                function (error) {
                    $window.location.href = 'errors/' + error.status;
                }
        );
    };

    $scope.formModel.chgParentImage = false;
    $scope.onSubmit = function (valid, image1) {
        if (valid) {
            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");



            if ($scope.resImageDataURI) {
                $scope.formModel.avatar = $scope.resImageDataURI;
                $scope.formModel.chgParentImage = true;
            }


            $http.post(base_url + 'parents/update', $scope.formModel, config).then(
                    function (success) {
                        if (success.data.status === "success") {
                            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                            $window.scrollTo(0, 0);
                            $scope.alert.message = success.data.message;
                            $scope.alert.type = "alert-success";
                            setTimeout(function () {
                                $window.location.href = 'parents/all';
                            }, 1000);

                        } else if (success.data.status === "error") {
                            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                            $window.scrollTo(0, 0);
                            $scope.alert.message = success.data.message;
                            $scope.alert.type = "alert-danger";
                        }
                    },
                    function (error) {
                        $window.location.href = 'errors/' + error.status;
                    }
            );
            //console.log($scope.formModel);
        }
    };
});



//*************************************//
//    Import Student controller //
//************************************//

app2.controller('importCtrl', function ($scope, $http, $window) {
    $scope.csv_students = {};
    $scope.valid_students = 0;
    $scope.showstd = {};
    $scope.parentId = {};
    $scope.email = {};
    $scope.batches = {};
    $scope.parentIdResponse = [];
    $scope.final_students = [];
    $scope.import_std_class_id;
    $scope.selected_array = [];
    $scope.selected_students = [];

    $scope.init_csv = function (csv_data) {
        $scope.csv_students = angular.fromJson(csv_data);
        angular.forEach($scope.csv_students, function (value, key) {
            if(!value.any_error){
                $scope.valid_students++;
            }
        });
    };
    $scope.removeItem = function (item) {
        item_id = item.id;
        var index = $scope.selected_array.indexOf(item_id);
            if (index > -1) {
              $scope.selected_array.splice(index, 1);
               $scope.selected_students =  $scope.selected_students.filter(function(item){ return item.id != item_id });
            }
        var index = $scope.csv_students.indexOf(item);
        $scope.csv_students.splice(index, 1);
        if ($scope.csv_students.length === 0) {
            alert('<?php echo lang("new_import"); ?>');
            $window.location.href = base_url + 'students/show';
        }
    };

    $scope.removeItemBulk = function (item_id) {
        var index = $scope.selected_array.indexOf(item_id);
            if (index > -1) {
              $scope.selected_array.splice(index, 1);
               $scope.selected_students =  $scope.selected_students.filter(function(item){ return item.id != item_id });
               $scope.csv_students =  $scope.csv_students.filter(function(item){ return item.id != item_id });
            }
        
        
}

    $scope.fetchClassBatches = function (class_id) {
    if(class_id == undefined){class_id = 0}
     Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'settings/getClassBatches', {id: class_id}, config).then(
                function (success) {
                    $scope.batches = success.data;
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                },
                function (error) {
                    console.log(error.data);
                }
        ); 
    };

    
    

   $scope.get_prev_ids = function(rollno, email, p_email){
   Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
       $http.post(base_url + 'import/get_prev_ids', { rollno:rollno, email:email, p_email:p_email } , config).then(
           function(success){
               $scope.showstd.student_id = success.data.student_id;
               $scope.showstd.parent_id = success.data.parent_id;
               Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
           },
           function(error){
               console.log(success.data);
           }
       );
   };

   $scope.ShowErrors = function (errors){
        $scope.errors = errors;
    }

    
    $scope.ShowImport = function (data) {
        $scope.left = $window.scrollX;
        $scope.top = $window.scrollY;
        $window.scrollTo(0, 0);
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
       $scope.showstd = data;
       $http.post(base_url + 'import/parentId', {email : $scope.showstd.Parent_Email}, config).then(
               function (success) {
                   if(success.data.id){
                       $scope.showstd.parentId = success.data.id;
                       $scope.showstd.parentIdResponse = 0;
                   }else{
                       $scope.showstd.parentId = "";
                       $scope.showstd.parentIdResponse = 1;
                   }

               });
               console.log($scope.showstd);
       $scope.fetchClassBatches($scope.showstd.class_id);
       $('#stdForm').show();
       $('#inbox_div').hide();
   };

   $scope.process_students = function(){
   Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
    $http.post(base_url + 'import/process', $scope.selected_students , config).then(
               function (success) {
               var ids = success.data.row_ids;
               angular.forEach(ids, function (value, key) {
               $scope.removeItemBulk(value);
           });
           Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
           showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
           $('#checkAll').prop("checked", false);
           });
}

   $scope.selectStudent = function(d){
       if($scope.selected_array.includes(d.id)){
            var index = $scope.selected_array.indexOf(d.id);
            if (index > -1) {
              $scope.selected_array.splice(index, 1);
              $scope.selected_students = $scope.selected_students.filter(function(item){ return item.id != d.id });
            }
        }else{
        $scope.selected_array.push(d.id);
        $scope.selected_students.push(d);
        }

        if($scope.selected_array.length != $scope.valid_students){
            $('#checkAll').prop("checked", false);
        }else{
            $('#checkAll').prop("checked", true);
        }
    }

    $scope.selectAllStudents = function(event){
        check_status = event.target.checked;

        if(check_status){
            angular.forEach($scope.csv_students, function (value, key) {
                if(!value.any_error && !$scope.selected_array.includes(value.id)){
                    $scope.selected_array.push(value.id);
                    $scope.selected_students.push(value);
                    $('#sel'+value.id).prop("checked", true);
                }
            });
        }else{
            $scope.selected_array = [];
            $scope.selected_students = [];
            angular.forEach($scope.csv_students, function (value, key) {
                $('#sel'+value.id).prop("checked", false);
            });
        }

    }

   $scope.ShowList = function () {

      $('#inbox_div').show();
      $('#stdForm').hide();
      $('#updateForm').hide();
      $window.scrollTo($scope.left, $scope.top);

  };

   $scope.onSave = function (valid, image, image1) {
       if (valid) {
           if (image) {
               $scope.showstd.avatar = image.dataURL;
           } else {
               $scope.showstd.avatar = "default.png";
           }
           if (image1) {
               $scope.showstd.pAvatar = image1.dataURL;
           } else {
               $scope.showstd.pAvatar = "default.png";
           }

           //console.log($scope.csv_students);
           //console.log( $scope.showstd);
           Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
           $http.post(base_url + 'import/saveStudunt', $scope.showstd, config).then(
                   function (success) {
                       if (success.data.status === "success") {

                           Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                           $scope.removeItem($scope.showstd);
                           $scope.showstd = {};
                           $scope.ShowList();
                           showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                           $scope.stdAddmissionForm.$setUntouched();
                           $scope.stdAddmissionForm.$setPristine();
                           //$scope.alert.message = success.data.message;
                           //$scope.alert.type = "alert-success";
                           $scope.image2 = false;
                           $scope.image3 = false;
                           //console.log(success.data);
                       } else if (success.data.status === "error") {
                           Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                           showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                           //$scope.alert.message = success.data.message;
                           //$scope.alert.type = "alert-danger";
                       }
                   },
                   function (error) {
                       Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                       console.log(error);
                       //$window.location.href = 'errors/' + error.status;
                   }
           );
           //console.log($scope.csv_students);
       }
   };
   
   $scope.ShowUpdate = function(data){
   $scope.left = $window.scrollX;
    $scope.top = $window.scrollY;
    $window.scrollTo(0, 0);
   Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
   $window.scrollTo(0, 0);
        $scope.showstd = data;
        console.log($scope.showstd);
        $scope.fetchClassBatches($scope.showstd.class_id);
        $('#updateForm').show();
        $('#inbox_div').hide();
    };

   $scope.onUpdate = function (valid, image, image1) {
      if (valid) {
          if (image) {
              $scope.showstd.avatar = image.dataURL;
          } else {
              $scope.showstd.avatar = "default.png";
          }
          if (image1) {
              $scope.showstd.pAvatar = image1.dataURL;
          } else {
              $scope.showstd.pAvatar = "default.png";
          }

          Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");

          $http.post(base_url + 'import/updateStudunt', $scope.showstd, config).then(
                  function (success) {
                      if (success.data.status === "success") {
                          Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                          $scope.removeItem($scope.showstd);
                          $scope.showstd = {};
                          $scope.ShowList();
                          showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                          $scope.stdAddmissionForm.$setUntouched();
                          $scope.stdAddmissionForm.$setPristine();
                          //$scope.alert.message = success.data.message;
                          //$scope.alert.type = "alert-success";
                          $scope.image2 = false;
                          $scope.image3 = false;
                          //console.log(success.data);
                      } else if (success.data.status === "error") {
                          Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                          showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                          //$scope.alert.message = success.data.message;
                          //$scope.alert.type = "alert-danger";
                      }
                  },
                  function (error) {
                      Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                      console.log(error);
                      //$window.location.href = 'errors/' + error.status;
                  }
          );
          //console.log($scope.csv_students);
      }
  };
});
//*************************************//
//    End Import Student controller //
//************************************//



/* ---------------UI Image Cropper--------------- */
app2.controller('imageCropper', function ($scope) {
    $scope.blockingObject = {block: true};
    $scope.callTestFuntion = function () {
        $scope.blockingObject.render(function (dataURL) {
            console.log($scope.blockingObject);
            console.log('via render');
            console.log(dataURL.length);
        });
    };
    $scope.blockingObject.callback = function (dataURL) {
        console.log('via function');
        console.log(dataURL.length);
    };
    $scope.size = 'small';
    $scope.type = 'square';
    $scope.imageDataURI = '';
    $scope.resImageDataURI = '';
    $scope.resBlob = {};
    $scope.urlBlob = {};
    $scope.resImgFormat = 'image/jpeg';
    $scope.resImgQuality = 0.9;
    $scope.selMinSize = 100;
    $scope.selInitSize = [{w: 200, h: 80}];
    $scope.resImgSize = [{w: 300, h: 300}, {w: 300, h: 300}];
    //$scope.aspectRatio=1.2;
    $scope.onChange = function ($dataURI) {
        console.log('onChange fired');
    };
    $scope.onLoadBegin = function () {
        console.log('onLoadBegin fired');
    };
    $scope.onLoadDone = function () {
        console.log('onLoadDone fired');
    };
    $scope.onLoadError = function () {
        console.log('onLoadError fired');
    };
    $scope.getBlob = function () {
        console.log($scope.resBlob);
    };
    var handleFileSelect = function (evt) {
        var file = evt.currentTarget.files[0],
                reader = new FileReader();
        if (navigator.userAgent.match(/iP(hone|od|ad)/i)) {
            var canvas = document.createElement('canvas'),
                    mpImg = new MegaPixImage(file);

            canvas.width = mpImg.srcImage.width;
            canvas.height = mpImg.srcImage.height;

            EXIF.getData(file, function () {
                var orientation = EXIF.getTag(this, 'Orientation');

                mpImg.render(canvas, {
                    maxHeight: $scope.resImgSize,
                    orientation: orientation
                });
                setTimeout(function () {
                    var tt = canvas.toDataURL("image/jpeg", 1);
                    $scope.$apply(function ($scope) {
                        $scope.imageDataURI = tt;
                    });
                }, 100);
            });
        } else {
            reader.onload = function (evt) {
                $scope.$apply(function ($scope) {
                    console.log(evt.target.result);
                    $scope.imageDataURI = evt.target.result;
                });
            };
            reader.readAsDataURL(file);
        }
    };
    angular.element(document.querySelector('#fileInput')).on('change', handleFileSelect);
    $scope.$watch('resImageDataURI', function () {
        //console.log('Res image', $scope.resImageDataURI);
    });

    $scope.loadImageInView = function () {
        //$scope.image2 = $scope.resImageDataURI;
        //console.log($scope.image2);
    };
});
/* ---------------UI Image Cropper--------------- */

/*------Employee Attendance Controller--------*/
app2.controller("attendanceEmployeeCtrl", function ($scope, $http, $window, $location) {

    $scope.employees = {};
    $scope.statuss = {};
    $scope.message;
    $scope.selectedDate;
    $scope.attendModel = {};
    $scope.data = [];
    $scope.departments = {};
    $scope.deptCategories = {};
    
    $scope.getDepartments = function(){
    
        Loading("#emp_departments", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'employee/getDepartments', "", config).then(
            function (success) {
                Loading("#emp_departments", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.departments = success.data.departments;
                //console.log($scope.departments[0].id);
                //$scope.filterModel.department_id = ;
            }, 
            function(error){
                Loading("#emp_departments", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };
    
    $scope.getDepartmentCategories = function(id){
        Loading("#emp_categories", '<?php echo lang("loading_datatable") ?>', "", "show");
        
        if(id == 'all'){
            Loading("#emp_categories", '<?php echo lang("loading_datatable") ?>', "", "hide");
            $scope.filterModel.category_id = 'all';
            $scope.deptCategories = {};
        } else {
            $http.post(base_url + 'employee/getCategories2', {id:id}, config).then(
                function (success) {
                    Loading("#emp_categories", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.deptCategories = success.data.categories;
                }, 
                function(error){
                    Loading("#emp_categories", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
            );
        }
    };
    
    $scope.onSubmitEmp = function (valid) {
       if (valid) {
           Loading("#empAtt_search_filter", '<?php echo lang("loading_datatable") ?>', "", "show");
           $http.post(base_url + 'attendance/fetchEmployeesAttendance', $scope.filterModel, config).then(
                   function (success) {
                       Loading("#empAtt_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                       $scope.employees = success.data.employees;
                       $scope.marked = success.data.marked;
                       $scope.message = success.data.message;
                       $scope.disable = success.data.disable;
                       
                       if($scope.disable === "TRUE") {
                           $("#attEmployeeTable").keydown(function (event) { return false; });
                       } else {
                           $("#attEmployeeTable").keydown(function (event) { return true; });
                       }
                       
                       $scope.action = success.data.edit;
                       $scope.selectedDate = $scope.filterModel.date;
                   },
                   function (error) {
                       Loading("#empAtt_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                       //$window.location.href = 'errors/' + error.status;
                       console.log(error);
                   }
           );
       }
   };
    
    $scope.onSubmitEmp2 = function (obj) {
        //console.log(obj);
        Loading("#empAtt_search_filter", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'attendance/fetchEmployeesAttendance', { 'date':obj.date, 'department_id':obj.department_id, 'category_id':obj.category_id }, config).then(
               function (success) {
                   Loading("#empAtt_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                   $scope.employees = success.data.employees;
                   $scope.message = success.data.message;
                   $scope.disable = success.data.disable;
                   $scope.action = success.data.edit;
                   $scope.selectedDate = $scope.filterModel.date;
               },
               function (error) {
                   Loading("#empAtt_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                   //$window.location.href = 'errors/' + error.status;
                   console.log(error);
               }
        );
    };
    
    $scope.saveAttendanceEmp = function (valid) {
        if (valid) {
            $scope.data = [];
            angular.forEach($scope.employees, function (value, key) {
                $scope.data.push({id: value.id, date: $scope.filterModel.date, status: 'Present'});
            });

            if ($scope.attendModel.statuss) {
                angular.forEach($scope.data, function (val, key) {
                    angular.forEach($scope.attendModel.statuss, function (val2, key2) {
                        if (val.id === key2) {
                            val.status = val2;
                        }
                    });
                });
            }
            if($scope.attendModel.comments){
              angular.forEach($scope.data, function (val, key) {
                  angular.forEach($scope.attendModel.comments, function (val2, key2) {
                      if (val.id === key2) {
                           val.comment = val2;
                       }

                  });
              });
            }else{
                angular.forEach($scope.data, function (val, key) {
                    val.comment = '';
                });
            }

            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'attendance/saveEmployee', {'data': $scope.data, 'filter':$scope.filterModel}, config).then(
                function (success) {
                    $('#confirmModal').modal('toggle');
                    $scope.onSubmitEmp2($scope.filterModel);
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    if(success.data.status == 'error') {
                        showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                    } else if(success.data.status == 'success'){
                        showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                    }
                    //console.log(success.data);
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    //$window.location.href = 'errors/' + error.status;
                    console.log(error.data);
                }
            );
        }
    };
    
    $scope.inProcessAttendance = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $scope.filterModel.reason = $scope.requestText;
        if($scope.requestText != null){
            $http.post(base_url + 'attendance/inProcessEmpAttendance', $scope.filterModel, config).then(
                function (success) {
                $('.edit_attendance_request_model').modal('hide');
                    if(success.data.status === 'success'){
                        showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                        $scope.selectedDate = $scope.filterModel.date;
                        $scope.disable = success.data.disable;
                        $scope.action = success.data.edit;
                        $scope.r_id = success.data.r_id;
                        var otherData = {};
                        $scope.getSchoolAdmins(otherData);
                        $scope.requestText ="";
                    }
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                },
                function (error) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $window.location.href = 'errors/' + error.status;
                 //console.log(error);
                }
            );
        }else{
            $('#request_error').show();
        }
   };

    $scope.getSchoolAdmins = function(otherData){
      $http.post(base_url + 'syllabus/getSchoolAdmins', {}, config).then(
          function(success){
              publicNotificationViaPusher("lbl_approval_employee_atttence", otherData, success.data.new_ids, "applications/all", {'sender': success.data.sender}, $scope.r_id);
          },
          function(error){
               $window.location.href = 'errors/' + error.status;
          }
      );
  };
});

/* Student Report Controller */
app2.controller('studentReportController', function ($scope, $http) {
    $scope.classes = {};
    $scope.batches = {};
    $scope.students = {};
    $scope.selected = [];
     $scope.selectedClass = [];
    $scope.selectedBatches = [];
    $scope.selected1 = [];
    $scope.selectAll = [];
    $scope.selectAll1 = [];
    $scope.filterModel = {};
    $scope.cls_id = [];
    $scope.btch_id = [];

    $scope.initClasses = function () {
        Loading("#attFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'reports/getClasses', "", config).then(
                function (success) {
                    Loading("#attFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.classes = success.data;
                },
                function (error) {
                    Loading("#attFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
        );
    };
    // classes multi dropdown
    $scope.exist = function (item) {
        return $scope.selected.indexOf(item) > -1;
    };

    $scope.toggleSelection = function (item) {

        var idx = $scope.selected.indexOf(item);
        
        if (idx > -1) {
            $scope.selected.splice(idx, 1);
            $scope.cls_id.splice(idx, 1);
        } else {
            $scope.selected.push(item);
             $scope.cls_id.push(item.id);
        }
        count = $scope.classes.length;
        i = 0;
        angular.forEach($scope.classes, function (item) {
                idy = $scope.selected.indexOf(item);
                if (idy >= 0) {
                    i++;
                    return true;
                }else{
                $scope.selectAll = false;
            }
            });
        if(count == i){
            $scope.selectAll = true;
        }
        $scope.selectAll1 = false;
        $scope.initBatches($scope.selected);
    };

    $scope.checkAll = function () {
        if ($scope.selectAll) {
            angular.forEach($scope.classes, function (item) {
                idx = $scope.selected.indexOf(item);
                if (idx >= 0) {
                    return true;
                } else {
                    $scope.selected.push(item);
                    $scope.cls_id.push(item.id);
                }
            });
        } else {
            $scope.selected = [];
            $scope.cls_id = [];
        }
        $scope.selectAll1 = false;
        $scope.initBatches($scope.selected);
    };

    $scope.initBatches = function (class_id) {
        if (class_id) {
            Loading("#attFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            Loading("#attFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'reports/getClassBatches', {classes: class_id}, config).then(
                    function (success) {
                        Loading("#attFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.batches = success.data.batches;
                    },
                    function (error) {
                        console.log(error.data);
                        Loading("#attFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $window.location.href = 'errors/' + error.status;
                    }
            );
        }
    };
    //batches multi dropdown
    $scope.exist1 = function (item) {
        return $scope.selected1.indexOf(item) > -1;
    };
    $scope.toggleSelection1 = function (item) {

        var idx = $scope.selected1.indexOf(item);
        if (idx > -1) {
            $scope.selected1.splice(idx, 1);
            $scope.btch_id.splice(idx, 1);
        } else {
            $scope.selected1.push(item);
            $scope.btch_id.push(item.id);
        }

        count = $scope.batches.length;
        i = 0;
        angular.forEach($scope.batches, function (item) {
                idy = $scope.selected1.indexOf(item);
                if (idy >= 0) {
                    i++;
                    return true;
                }else{
                $scope.selectAll1 = false;
            }
            });
        if(count == i){
            $scope.selectAll1 = true;
        }
    };

    $scope.checkAll1 = function () {
        if ($scope.selectAll1) {
            angular.forEach($scope.batches, function (item) {
                idx = $scope.selected1.indexOf(item);
                if (idx >= 0) {
                    return true;
                } else {
                    $scope.selected1.push(item);
                    $scope.btch_id.push(item.id);
                }
            });
        } else {
            $scope.selected1 = [];
            $scope.btch_id = [];
        }
    };
    
    $scope.onsubmit = function () {
        
        $scope.filterModel.cls_id =  $scope.cls_id;
        $scope.filterModel.btch_id = $scope.btch_id;
        
        $('#myTablee').DataTable({
            "language": {

                "decimal":        "",
                "emptyTable":     '<?php echo lang("no_data_table"); ?>',
                "info":           '<?php echo lang("data_info"); ?>',
                "infoEmpty":      '<?php echo lang("infoempty"); ?>',
                "infoFiltered":   '<?php echo lang("filter_datatable"); ?>',
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     '<?php echo lang("show_datatable"); ?>',
                "loadingRecords": '<?php echo lang("loading_datatable"); ?>',
                "processing":     '<?php echo lang("processing_datatable"); ?>',
                "search":         '<?php echo lang("search"); ?>:',
                "zeroRecords":    '<?php echo lang("no_record_datatable"); ?>',
                "paginate": {
                    "first":      '<?php echo lang("first"); ?>',
                    "last":       '<?php echo lang("last"); ?>',
                    "next":       '<?php echo lang("btn_next"); ?>',
                    "previous":   '<?php echo lang("previous"); ?>'
                },
                "aria": {
                    "sortAscending":  ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                }
            },
            dom: 'Bfrtip',
            ajax: {
                type: "POST",
                url: base_url+'reports/fetchStudents',
                data: {'formData':$scope.filterModel},
                dataSrc: ''
            },
           
            columns: [
               
                {title: '<?php echo lang("lbl_name");?>', data: 'name' },
                {title: '<?php echo lang("lbl_rollno");?>', data: 'rollno' },
                {title: '<?php echo lang("lbl_class");?>', data: 'class'},
                {title: '<?php echo lang("lbl_batch");?>', data: 'batch'},
                {title: '<?php echo lang("lbl_gender");?>', data: 'gender'},
                {title: '<?php echo lang("lbl_email");?>', data: 'email'},
                {title: '<?php echo lang("lbl_city");?>', data: 'city'},
                {title: '<?php echo lang("lbl_mobile");?>', data: 'mobile_phone'},
                {title: '<?php echo lang("lbl_religion");?>', data: 'religion'},
                {title: '<?php echo lang("lbl_dob");?>', data: 'dob'},
                {title: '<?php echo lang("national_number");?>', data: 'ic_number'},
                {title: '<?php echo lang("lbl_nationality");?>', data: 'nationality'},
                {title: '<?php echo lang("lbl_passport_number");?>', data: 'passport_number'},
                {data:'id', render: function(id){return "<a type='button' href='students/view/"+id+"' class='btn btn-success btn-circle'><i class='fa fa-eye'></i></a>"; }}
                
            ],
            
                          
            buttons: [
                {
                extend: 'copyHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
                },
                {
                extend: 'excelHtml5'
                },
                {
                extend: 'csvHtml5'
                },
                 {
                extend: 'pdfHtml5'
                }
               ],
            destroy: true
        });
        $(this).html( '<input type="button" value="view"/>' );
    };
});

/* Employee Report Controller */
app2.controller('employeeReportController', function ($scope, $http, $window) {
    $scope.departments = {};
    $scope.categories = {};
    $scope.employees = {};
    $scope.DeptAll = [];
    $scope.categoryAll = [];
    $scope.DeptSelected = [];
    $scope.CategorySelected = [];
    $scope.filterModel = {};
    $scope.ids_dept = [];
    $scope.ids_cat = [];
    
    $scope.getDepartments = function () {
        Loading("#attFilterDepartments", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'reports/getDepartments', "", config).then(
                function (success) {
                    Loading("#attFilterDepartments", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.departments = success.data.departments;
                },
                function (error) {
                    Loading("#attFilterDepartments", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                    //console.log(error);
                }
        );
    };

    //departments multi dropdown
    $scope.existDpt = function (item) {
        return $scope.DeptSelected.indexOf(item) > -1;
    };
    $scope.toggleDpt = function (item) {

        var idx = $scope.DeptSelected.indexOf(item);
        if (idx > -1) {
            $scope.DeptSelected.splice(idx, 1);
            $scope.ids_dept.splice(idx, 1);
        } else {
            $scope.DeptSelected.push(item);
            $scope.ids_dept.push(item.id);
        }
        count = $scope.departments.length;
        i = 0;
        angular.forEach($scope.departments, function (item) {
                idy = $scope.DeptSelected.indexOf(item);
                if (idy >= 0) {
                    i++;
                    return true;
                }else{
                $scope.DeptAll = false;
            }
            });
        if(count == i){
            $scope.DeptAll = true;
        }
        $scope.categoryAll = false;
        $scope.getCategories($scope.DeptSelected);
    };

    $scope.checkAllDpt = function () {
        if ($scope.DeptAll) {
            angular.forEach($scope.departments, function (item) {
                idx = $scope.DeptSelected.indexOf(item);
                if (idx >= 0) {
                    return true;
                } else {
                    $scope.DeptSelected.push(item);
                    $scope.ids_dept.push(item.id);
                }
            });
        } else {
            $scope.DeptSelected = [];
            $scope.ids_dept = [];
        }
        $scope.categoryAll = false;
        $scope.getCategories($scope.DeptSelected);
    };
    
    $scope.getCategories = function (departments) {
        if (departments) {
            Loading("#FilterCategories", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'reports/getCategories', {departments: departments}, config).then(
                    function (success) {
                        Loading("#FilterCategories", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.categories = success.data.categories;
                    },
                    function (error) {
                        Loading("#FilterCategories", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $window.location.href = 'errors/' + error.status;
                    }
            );
        }
    };

    //departments multi dropdown
    $scope.existCategory = function (item) {
        return $scope.CategorySelected.indexOf(item) > -1;
    };
    $scope.toggleCategory = function (item) {

        var idx = $scope.CategorySelected.indexOf(item);
        if (idx > -1) {
            $scope.CategorySelected.splice(idx, 1);
            $scope.ids_cat.splice(idx, 1);
        } else {
            $scope.CategorySelected.push(item);
            $scope.ids_cat.push(item.id);
        }
        count = $scope.categories.length;
        i = 0;
        angular.forEach($scope.categories, function (item) {
                idy = $scope.CategorySelected.indexOf(item);
                if (idy >= 0) {
                    i++;
                    return true;
                }else{
                $scope.categoryAll = false;
            }
            });
        if(count == i){
            $scope.categoryAll = true;
        }
    };

    $scope.checkAll1Category = function () {
        if ($scope.categoryAll) {
            angular.forEach($scope.categories, function (item) {
                idx = $scope.CategorySelected.indexOf(item);
                if (idx >= 0) {
                    return true;
                } else {
                    $scope.CategorySelected.push(item);
                    $scope.ids_cat.push(item.id);
                }
            });
        } else {
            $scope.CategorySelected = [];
            $scope.ids_cat = [];
        }
    };
    
    $scope.onsubmit = function () {
        $scope.filterModel.dept = $scope.ids_dept;
        $scope.filterModel.cat = $scope.ids_cat;
        $('#myTablee').DataTable({
            "language": {

                "decimal":        "",
                "emptyTable":     '<?php echo lang("no_data_table"); ?>',
                "info":           '<?php echo lang("data_info"); ?>',
                "infoEmpty":      '<?php echo lang("infoempty"); ?>',
                "infoFiltered":   '<?php echo lang("filter_datatable"); ?>',
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     '<?php echo lang("show_datatable"); ?>',
                "loadingRecords": '<?php echo lang("loading_datatable"); ?>',
                "processing":     '<?php echo lang("processing_datatable"); ?>',
                "search":         '<?php echo lang("search"); ?>:',
                "zeroRecords":    '<?php echo lang("no_record_datatable"); ?>',
                "paginate": {
                    "first":      '<?php echo lang("first"); ?>',
                    "last":       '<?php echo lang("last"); ?>',
                    "next":       '<?php echo lang("btn_next"); ?>',
                    "previous":   '<?php echo lang("previous"); ?>'
                },
                "aria": {
                    "sortAscending":  ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                }
            },
            dom: 'Bfrtip',
            ajax: {
                type: "POST",
                url: base_url+'reports/fetchEmployees',
                data: {'formData':$scope.filterModel},
                dataSrc: ''
            },
           
            columns: [
                {title: '<?php echo lang("lbl_name"); ?>', data: 'name'},
                {title: '<?php echo lang("title_department"); ?>', data: 'department_name'},
                {title: '<?php echo lang("lbl_template_category"); ?>', data: 'category_name'},
                {title: '<?php echo lang("job_title"); ?>', data: 'job_title'},
                {title: '<?php echo lang("lbl_email"); ?>', data: 'email'},
                {title: '<?php echo lang("lbl_gender"); ?>', data: 'gender'},
                {title: '<?php echo lang("lbl_mobile"); ?>', data: 'mobile_phone'},
                {title: '<?php echo lang("lbl_qualification"); ?>', data: 'qualification'},
                {title: '<?php echo lang("lbl_passport_number"); ?>', data: 'passport_number'},
               
                {data:'id', render: function(id){return "<a type='button' href='employee/view?id="+id+"' class='btn btn-success btn-circle'><i class='fa fa-eye'></i></a>"; }}
                
            ],
            buttons: [
                {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
                },
                {
                extend: 'excelHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
                },
                {
                extend: 'csvHtml5'
                },
                 {
                extend: 'pdfHtml5'
                }
            ],
            
            destroy: true
        });
    };
});

// ---------fee Report 
app2.controller('feeReportController', function ($scope, $http, $window) {

    $scope.classes = {};
    $scope.classeswithacdmicyear = {};
    $scope.batches_summary = [];
    $scope.feeSummary_avatar = false;
    $scope.feeSummary_paidAmount = false;
    $scope.feeSummary_totalAmount = false;
    $scope.feeSummary_balance = false;
    $scope.feeType = [];
    $scope.collectors = [];
    $scope.feeDiscount = [];
    $scope.typeSelected = [];
    $scope.type_id = [];
    $scope.DiscountSelected = [];
    $scope.discount_id = [];
    $scope.collectorSelected = [];
    $scope.collect_id = [];
    $scope.filterModel = {};
    
    $scope.batches = {};
    $scope.students = {};
    $scope.selected = [];
    $scope.selectedClass = [];
    $scope.selectedBatches = [];
    $scope.selected1 = [];
    $scope.selectAll = [];
    $scope.selectAll1 = [];
  
    $scope.cls_id = [];
    $scope.btch_id = [];

    $scope.academicyears = {};
    $scope.reportAcdamicYear = {};
    $scope.reportAcdamicYear_id = {};
    $scope.classesSelectAll = [];
    

// Fee Report
    
    $scope.initAcdamicYearFeeReport =  function(){
        Loading("#academicyearsReport", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'reports/getAcademicYears_feeSummary', "", config).then(
            function (success) {
                Loading("#academicyearsReport", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.academicyearsReport = success.data.data;
                $scope.reportAcdamicYear_id = success.data.current_academic_year_id;
              
                $scope.initClasses(success.data.current_academic_year_id);
            },
            function (error) {
                Loading("#academicyearsReport", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $window.location.href = 'errors/' + error.status;
            }
        );
    };

    $scope.initClasses = function (academic_year_id) {
        
        Loading("#attFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'examination/getClasses', {'academic_year_id': academic_year_id}, config).then(
            function (success) {
                Loading("#attFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.classes = success.data;
            },
            function (error) {
                Loading("#attFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                //$window.location.href = 'errors/' + error.status;
                console.log(error);
            }
        );
    };
    
    $scope.initBatches = function () {

        if($scope.reportclasses == undefined){
            $scope.batches = [];
            $("#batches_selectall").attr("checked",false);
        }
        if($scope.reportclasses.length == $scope.classeswithacdmicyear.length){
            console.log("here");
            $("#Feeclasses_selectall").prop("checked",true);
        }
        if($scope.reportclasses.length != $scope.classeswithacdmicyear.length){
            $("#Feeclasses_selectall").prop("checked",false);
        }
        if($scope.reportclasses) {
            Loading("#initReportBatchesSummary", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'reports/getClassBatches', {classes: $scope.reportclasses}, config).then(
                    function (success) {
                        Loading("#initReportBatchesSummary", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.batches = success.data.batches;

                    },
                    function (error) {
                        console.log(error.data);
                        Loading("#initReportBatchesSummary", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $window.location.href = 'errors/' + error.status;
                    }
            );
        }
    };

    $scope.iniType = function (reportAcdamicYear_id) {

        
        if($scope.reportclasses == undefined){
            $scope.feeType = [];
            $("#feeType_selectall").attr("checked",false);
        }
        if($scope.reportclasses.length == $scope.classeswithacdmicyear.length){
            
            $("#Feeclasses_selectall").prop("checked",true);
        }
        if($scope.reportclasses.length != $scope.classeswithacdmicyear.length){
            $("#Feeclasses_selectall").prop("checked",false);
        }

        
        if($scope.FeeTypeSelect){
            Loading("#filterFeeType", '<?php echo lang("loading_datatable") ?>', "", "show");
                $http.post(base_url + 'reports/getFeeType', {classes: $scope.reportclasses, academic_year_id: reportAcdamicYear_id}, config).then(
                    function (success) {
                        $scope.feeType = success.data.feeTypes;
                        Loading("#filterFeeType", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    },
                    function (error) {
                        Loading("#filterFeeType", '<?php echo lang("loading_datatable") ?>', "", "hide");
                         //$window.location.href = 'errors/' + error.status;
                        console.log(error);
                    }
                );
             
            }
        
    };

    $scope.iniDiscountType = function () {
        $http.post(base_url + 'reports/getDiscountTypes', "", config).then(
                function (success) {
                    $scope.feeDiscount = success.data.feeDiscount;
                },
                function (error) {
                    //$window.location.href = 'errors/' + error.status;
                    console.log(error);
                }
        );
    };

    $scope.iniCollector = function () {
        $http.post(base_url + 'reports/getCollector', "", config).then(
                function (success) {
                    $scope.collectors = success.data.collectors;
                },
                function (error) {
                    //$window.location.href = 'errors/' + error.status;
                    console.log(error);
                }
        );
    };


   
    $scope.onsubmit = function () {
       
        if($scope.reportAcdamicYear_id != ""){
            $scope.filterModel.academic_year_id = $scope.reportAcdamicYear_id;
        }
        if($scope.reportclasses != ""){
            $scope.filterModel.class_id = $scope.reportclasses;
        }else{
             $scope.filterModel.class_id = '';;
        }
        if($scope.batchesSelect != ""){
                $scope.filterModel.btch_id = $scope.batchesSelect;
        }else{
             $scope.filterModel.btch_id = '';;
        }
        if($scope.FeeTypeSelect != ""){
                $scope.filterModel.types_id = $scope.FeeTypeSelect;
        }else{
             $scope.filterModel.types_id = '';;
        }
        if($scope.discountSelect != ""){
                $scope.filterModel.discounts_id = $scope.discountSelect;
        }else{
             $scope.filterModel.discounts_id = '';;
        }
        if($scope.collectorSelect != ""){
            $scope.filterModel.collects_id = $scope.collectorSelect;
        }else{
             $scope.filterModel.collects_id = '';;
        }
       
        if($scope.filterModel.length != 0){
           angular.forEach($scope.filterModel, function (value, key) {
               if(value.length == 0){
                   delete $scope.filterModel[key];
                }else if(value == false){
                    delete $scope.filterModel[key];
               }
           });
        }

    if(jQuery.isEmptyObject($scope.filterModel)){
        showNotification("Empty Search Filter", "Please select a filter to generate report", "info");
        
    }else{
    //console.log($scope.filterModel);

        $('#myTablee').DataTable({
            "language": {

                "decimal":        "",
                "emptyTable":     '<?php echo lang("no_data_table"); ?>',
                "info":           '<?php echo lang("data_info"); ?>',
                "infoEmpty":      '<?php echo lang("infoempty"); ?>',
                "infoFiltered":   '<?php echo lang("filter_datatable"); ?>',
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     '<?php echo lang("show_datatable"); ?>',
                "loadingRecords": '<?php echo lang("loading_datatable"); ?>',
                "processing":     '<?php echo lang("processing_datatable"); ?>',
                "search":         '<?php echo lang("search"); ?>:',
                "zeroRecords":    '<?php echo lang("no_record_datatable"); ?>',
                "paginate": {
                    "first":      '<?php echo lang("first"); ?>',
                    "last":       '<?php echo lang("last"); ?>',
                    "next":       '<?php echo lang("btn_next"); ?>',
                    "previous":   '<?php echo lang("previous"); ?>'
                },
                "aria": {
                    "sortAscending":  ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                }
            },
            dom: 'Bfrtip',
            ajax: {
                type: "POST",
                url: base_url+'reports/fetchFees',
                data: {'formData':$scope.filterModel},
                dataSrc: ''
            },
           
            "order": [[ 2, "asc" ],[3,"asc"]],
            columns: [
                {title: '<?php echo lang("lbl_student");?>', data: 'std_name'},
                {title: '<?php echo lang("lbl_class");?>', data: 'class_name'},
                {title: '<?php echo lang("lbl_batch");?>', data: 'batch'},
                {title: '<?php echo lang("lbl_rollno");?>', data: 'studentID'},
                {title: '<?php echo lang("lbl_nationality");?>', data: 'country_name'},
                {title: '<?php echo lang("lbl_fee");?>', data: 'type'},
                {title: '<?php echo lang("lbl_fee_varient");?>', data: 'title'},
                {title: '<?php echo lang("fee_amount");?>', data: '', "render": function (data, type, row) 
                    {
                        if(row.varient_fee === null){ 
                            if(row.feetype_amount === null){
                                return '' ;
                            }else{
                                return row.feetype_amount;
                            }   
                            
                        }else{
                            return row.varient_fee;
                        }   
                    }
                },
              
                {title: '<?php echo lang("lbl_discount_type");?>', data: '', "render": function (data, type, row) {
                        if (row.v_percentage != null){
                            return row.discount;
                       }else{
                            return '';
                        }
                   }
                },
                {title: '<?php echo lang("lbl_fee_discount_amount");?>', data: '', "render": function (data, type, row) {
                   if (row.v_percentage === null){
                       return '';
                       }
                   else {
                       return row.v_percentage+'%';
                       }
                   }
                },
                {title: '<?php echo lang("discounted_amount") ;?>', data: '', "render": function (data, type, row) 
                    {
                       if(row.varient_fee === null && row.paid_amount === null ){
                            if(row.discount_amount_varient != null){
                                return (row.feetype_amount - row.discount_amount_varient - row.discount_amount);
                            }else{
                                return (row.paid_amount - row.feetype_amount);
                            }
                            
                        }else if(row.varient_fee != null){
                            if(row.discount_amount_varient != null){
                                return (row.varient_fee - row.discount_amount_varient);
                            }else{
                                return (row.varient_fee);
                            }
                            
                        }else{
                        return '';
                        }
                    }
                },
                {title: '<?php echo lang("amount_paid");?>', data: '', "render": function (data, type, row) 
                    {
                       if(row.paid_amount === null){
                            return '';
                        }else{
                            return row.paid_amount;
                        }
                    }
                },
                /*
                {title: '<?php echo lang("lbl_status");?>', data: '', "render": function (data, type, row) {
                       if (row.status == '1'){
                       return '<a href="javascript:void(0);" class="text-success"><?php echo lang("lbl_paid"); ?></a>';
                       }
                       else if(row.status == '2'){
                            return '<a href="javascript:void(0);" class="text-warning"><?php echo lang("lbl_fee_partially_paid"); ?></a>';
                       }
                   else {
                       return '<a href="javascript:void(0);" class="text-danger"><?php echo lang("lbl_unpaid"); ?></a>';
                       }
                   }
                },
                */
                {title: '<?php echo lang("paid_mode");?>', data: 'mode'},
                {title: '<?php echo lang("due_date");?>', data: 'due_date'},
                {title: '<?php echo lang("lbl_fee_collection_date");?>', data: 'created_at'},
                {title: '<?php echo lang("lbl_receipt_no");?>', data: '',"render": function (data, type, row){
                   if (row.id === null){
                       return '';
                       }
                   else {
                       return row.receipt_no;
                       }
                    } 
                },
                {title: '<?php echo lang("lbl_collector");?>', data: 'collector'},
                
            ],
            buttons: [
                {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
                },
                {
                extend: 'excelHtml5',
                orientation: 'landscape'
                
                },
                {
                extend: 'csvHtml5'
                }
                 
            ],
            destroy: true
                
        });

        }
        
    };

// fee Summary Report

    $scope.initAcademicYears =  function(){
        Loading("#marksFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'reports/getAcademicYears_feeSummary', "", config).then(
            function (success) {
                Loading("#marksFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.academicyears = success.data.data;
                $scope.academic_year_id = success.data.current_academic_year_id;
                $scope.initClassesWithAcdmicYear(success.data.current_academic_year_id);
            },
            function (error) {
                Loading("#marksFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $window.location.href = 'errors/' + error.status;
            }
        );
    };

    $scope.initClassesWithAcdmicYear = function (academic_year_id) {
        Loading("#attFilterClasses_feesummary", '<?php echo lang("loading_datatable") ?>', "", "show");
        if(academic_year_id){
        $http.post(base_url + 'examination/getClasses', { 'academic_year_id': academic_year_id }, config).then(
                function (success) {
                    Loading("#attFilterClasses_feesummary", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.classeswithacdmicyear = success.data;
                },
                function (error) {
                    //$window.location.href = 'errors/' + error.status;
                    console.log(error);
                }
            );
        }
    };

    $scope.yasirclasses = [];

    $scope.initBatchesSummary = function () {

        if($scope.yasirclasses == undefined){
            $scope.batches_summary = [];
            $("#batches_selectall").attr("checked",false);
        }
        if($scope.yasirclasses.length == $scope.classeswithacdmicyear.length){
            console.log("here");
            $("#classes_selectall").prop("checked",true);
        }
        if($scope.yasirclasses.length != $scope.classeswithacdmicyear.length){
            $("#classes_selectall").prop("checked",false);
        }
        if($scope.yasirclasses){
            Loading("#initBatchesSummary", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'reports/getClassBatches_feesummary', {classes: $scope.yasirclasses}, config).then(
                function (success) {
                    Loading("#initBatchesSummary", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.batches_summary = success.data.batches;
                    //console.log($scope.batches_summary);
                },
                function (error) {
                    console.log(error.data);
                    Loading("#initBatchesSummary", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
            );
        }
    };

    $scope.fee_Summary_Print = function(id, logo, name, dirr){
        var classes = "";
        var batches = "";
        angular.forEach($scope.classes, function (item) {
            angular.forEach($scope.yasirclasses, function (id){
                if(id == item.id){
                    classes += item.name + ",";
                }
            });
        });

        angular.forEach($scope.batches_summary, function (item) {
            angular.forEach($scope.rizwanBatchSelect, function (id){
                if(id == item.id){
                    batches += item.name + ",";
                }
            });
        });
        var d = '<div style='+ dirr +'>'+
            '<p style="text-align:left;"><img src="<?php echo base_url(); ?>uploads/logos/'+logo+'" width="80px"/></p>'+
            '<h3 style="text-align:center;"><b>'+name+'</b></h3>'+
            '<table style="width:100%; border-spacing: 5px; margin-bottom: 10px; border-collapse: separate;">'+
            '<tr><td><strong><?php echo lang("class_name"); ?></strong></td><td><u>'+classes.slice(0,-1)+'</u></td>'+
            '<td><strong><?php echo lang("lbl_batch"); ?></strong></td><td><u>'+batches.slice(0,-1)+'</u></td>'+
            '</table>'+
        '</div>';
    
    
        $("#" + id).print({
            globalStyles: false,
            mediaPrint: false,
            stylesheet: "<?php echo base_url(); ?>assets/css/custom-feeSummaryReport.css",
            noPrintSelector: ".no-print",
            iframe: true,
            append: null,
            prepend: d,
            manuallyCopyFormValues: true,
            deferred: $.Deferred(),
            timeout: 750,
            title: null,
            doctype: '<!doctype html>'
        });
    };

    $scope.getAllBatches = function(){
        console.log($scope.yasirclasses);
    };
    


// Fee report filters
    $scope.reportclasses = [];
    $scope.batchesSelect = [];
    $scope.batchesSelectAll = [];
    $scope.batchesSelectAllReport = [];
    $scope.FeeTypeSelect = [];
    $scope.FeeTypeSelectAll = [];
    $scope.discountSelect = [];
    $scope.discountSelectAll = [];
    $scope.collectorSelect = [];
    $scope.collectorSelectAll = [];
    
    $scope.onsubmit_feesummary = function () {
    console.log('onsubmit_feesummary');
        $scope.filterModel.academic_year_id =  $scope.academic_year_id;
        $scope.filterModel.class_id = $scope.yasirclasses;        
        $scope.filterModel.batch_id = $scope.rizwanBatchSelect;        
        $scope.filterModel.status = $scope.feeSummaryStatus;
        
        $('#myTablee_feeSummary').DataTable({
            "language": {

                "decimal":        "",
                "emptyTable":     '<?php echo lang("no_data_table"); ?>',
                "info":           '<?php echo lang("data_info"); ?>',
                "infoEmpty":      '<?php echo lang("infoempty"); ?>',
                "infoFiltered":   '<?php echo lang("filter_datatable"); ?>',
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     '<?php echo lang("show_datatable"); ?>',
                "loadingRecords": '<?php echo lang("loading_datatable"); ?>',
                "processing":     '<?php echo lang("processing_datatable"); ?>',
                "search":         '<?php echo lang("search"); ?>:',
                "zeroRecords":    '<?php echo lang("no_record_datatable"); ?>',
                "paginate": {
                    "first":      '<?php echo lang("first"); ?>',
                    "last":       '<?php echo lang("last"); ?>',
                    "next":       '<?php echo lang("btn_next"); ?>',
                    "previous":   '<?php echo lang("previous"); ?>'
                },
                "aria": {
                    "sortAscending":  ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                }
            },
            dom: 'Bfrtip',

            ajax: {
                type: "POST",
                url: base_url+'reports/feSummary_method',
                data: {'formData':$scope.filterModel},
                dataSrc: ''
            },
           
            columns: [
                {title: '<?php echo lang("lbl_name");?>', data: 'student_name' },
                {title: '<?php echo lang("lbl_avatar");?>', data: '', render : function (data, type, row) {
                        return '<img src="<?php echo base_url(); ?>uploads/user/' +row.avatar+ '" class="img-circle" style="height: 60px;width: 60px" />';                  
                    }
                },
                {title: '<?php echo lang("lbl_rollno");?>', data: 'rollno' },
                {title: '<?php echo lang("lbl_class");?>', data: 'class_name' },
                {title: '<?php echo lang("lbl_batch");?>', data: 'batch_name' },
                {title: '<?php echo lang("lbl_paid_amount");?>', data: 'paid_amount' },
                {title: '<?php echo lang("lbl_total_amount");?>', data: 'amount' },
                {title: '<?php echo lang("imp_contact");?>', data: 'contact' },
                {title: '<?php echo lang("lbl_balance");?>', data: '', render : function (data, type, row) {
                        return row.paid_amount - row.amount;
                    }
                },
                {title: '<?php echo lang("lbl_status");?>', data: '', "render": function (data, type, row) {
                       if (row.status == 'Paid'){
                       return '<a href="javascript:void(0);" class="text-success"><?php echo lang("lbl_paid"); ?></a>';
                       }
                       else{
                            return '<a href="javascript:void(0);" class="text-danger"><?php echo lang("lbl_unpaid"); ?></a>';
                       }
                   }
                },   
            ],
            
                          
            buttons: [
                {
                    extend: 'copyHtml5',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: ':visible(:not(.not-export-col))'
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: ':visible(:not(.not-export-col))'
                    }
                },
                {
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: ':visible(:not(.not-export-col))'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: ':visible(:not(.not-export-col))'
                    },
                    customize : function(doc){
                        var colCount = new Array();
                        $("#myTablee_feeSummary").find('tbody tr:first-child td').each(function(){
                            if($(this).attr('colspan')){
                                for(var i=1;i<=$(this).attr('colspan');$i++){
                                    colCount.push('*');
                                }
                            }else{ colCount.push('*'); }
                        });
                        doc.content[1].table.widths = colCount;
                    }
                }
            ],
            columnDefs: [ {
                targets: -1,
                visible: false
            } ],
            destroy: true
        });
        $(this).html( '<input type="button" value="view"/>' );
    };

  

});

app2.controller("marksheetController", function ($scope, $http, $window, $location) {
    $scope.classes = {};
    $scope.batches = {};
    $scope.subjects = {};
    $scope.exams = {};
    $scope.students = [];
    $scope.filterModel = {};
    $scope.enteredMarks = {};
    $scope.message;
    $scope.printing_details = {};
    $scope.passing_marks = 0;
    $scope.students_group_wise = [];
    $scope.academicyears = {};
    
    $scope.initAcademicYears =  function(){
        Loading("#marksFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'examination/getAcademicYears', "", config).then(
            function (success) {
                Loading("#marksFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.initClasses(success.data.current_academic_year_id);
                $scope.academicyears = success.data.data;
                $scope.filterModel.academic_year_id = success.data.current_academic_year_id;
                $scope.filterModel.class_id = "";
                $scope.filterModel.batch_id = "";
            },
            function (error) {
                Loading("#marksFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $window.location.href = 'errors/' + error.status;
            }
        );
    };
    
    $scope.initClasses = function (academic_year_id) {
        if(academic_year_id) {    
            Loading("#marksFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'examination/getClasses', { 'academic_year_id': academic_year_id }, config).then(
                function (success) {
                    Loading("#marksFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.classes = success.data;
                    //sconsole.log(success.data);
                },
                function (error) {
                    Loading("#marksFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
            );
        }
    };
    
    
    $scope.initBatches = function (class_id, academic_year_id) {
        if (class_id && academic_year_id) {
            Loading("#marksFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'examination/getClassBatches', {'class_id': class_id, 'academic_year_id':academic_year_id}, config).then(
                function (success) {
                    Loading("#marksFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.batches = success.data;
                    $scope.filterModel.batch_id = "";
                },
                function (error) {
                    console.log(error.data);
                    Loading("#marksFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
            );
        }
    };
    
    $scope.initSubjects = function (class_id, batch_id, academic_year_id) {
        Loading("#marksFilterSubjects", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (class_id && batch_id && academic_year_id) {
            Loading("#marksFilterSubjects", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'examination/getSubjects', { 'class_id': class_id, 'batch_id':batch_id, 'academic_year_id': academic_year_id}, config).then(
                function (success) {
                    Loading("#marksFilterSubjects", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.subjects = success.data;
                    $scope.filterModel.subject_id = "";
                },
                function (error) {
                    console.log(error.data);
                }
            );
        }
    };
    
    $scope.initExams = function (class_id, batch_id, subject_id, academic_year_id) {
        if (class_id && batch_id && subject_id && academic_year_id) {
            Loading("#marksFilterExams", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'examination/getExams', {class_id: class_id, batch_id:batch_id, subject_id:subject_id, academic_year_id:academic_year_id}, config).then(
                function (success) {
                    Loading("#marksFilterExams", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.exams = success.data;
                    $scope.filterModel.exam_detail_id = "";
                },
                function (error) {
                    console.log(error.data);
                }
            );
        }
    };
    
    $scope.saveExamId = function (exam_detail_id){
        angular.forEach($scope.exams, function (value, key) {
            if(value.id == exam_detail_id){
                $scope.filterModel.exam_id = value.exam_id;
            }
        });
    };
    
    $scope.inProcessMarkSheet = function(){
    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
    $scope.filterModel.reason = $scope.requestText;
        if($scope.requestText != null){
            $http.post(base_url + 'examination/inProcessMarkSheet', $scope.filterModel, config).then(
                function (success) {
                    $('#requestModelMarksheet').modal('hide');
                    if(success.data.status === 'success'){
                        showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                        $scope.disable = success.data.disable;
                        $scope.action = success.data.edit;
                        $scope.r_id = success.data.r_id;
                        var otherData = {class_id:$scope.filterModel.class_id, batch_id:$scope.filterModel.batch_id, subject_id:$scope.filterModel.subject_id};
                        $scope.getSchoolAdmins(otherData);
                        $scope.requestText ="";
                     }
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                    //console.log(error);
                }
            );
        }else{
             $('#request_error').show();
        }
     };

   $scope.getSchoolAdmins = function(otherData){
      $http.post(base_url + 'syllabus/getSchoolAdmins', {}, config).then(
          function(success){
              publicNotificationViaPusher("lbl_approval_exam_marksheet", otherData,  success.data.new_ids, "applications/all", {'sender': success.data.sender}, $scope.r_id);
          },
          function(error){
               $window.location.href = 'errors/' + error.status;
          }
      );
  };

    $scope.onSubmit = function (valid) {
        if (valid) {
            Loading("#marksStudentsTable", '<?php echo lang("loading_datatable") ?>', "", "show");
            $scope.savePrintingDetails($scope.filterModel);
            $http.post(base_url + 'examination/fetchStudents', $scope.filterModel, config).then(
                    function (success) {
                        Loading("#marksStudentsTable", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.students = {};
                        $scope.students = success.data.students;
                        $scope.message = success.data.message;
                        $scope.passing_marks = success.data.passing_marks;
                        $scope.action = success.data.edit;
                        $scope.disable = success.data.disable;
                        $scope.students_group_wise = [];
                        angular.forEach($scope.students, function (value, key) {
                            if(value.is_read == 'true'){
                                $scope.students_group_wise.push(value);
                            }
                        });
                        
                        /*if($scope.disable == "TRUE") {
                            $("#marksStudentsTable").keydown(function (event) { return false; });
                        } else {
                            $("#marksStudentsTable").keydown(function (event) { return true; });
                        }*/
                        
                        $scope.exist = success.data.exist;
                    },
                    function (error) {
                        Loading("#marksStudentsTable", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        //$window.location.href = 'errors/' + error.status;
                        console.log(error);
                    }
            );
        }
    };
    
    $scope.savePrintingDetails = function(obj){
        angular.forEach($scope.classes, function (value, key) {
            if(value.id == $scope.filterModel.class_id){
                $scope.printing_details.class_name = value.name;
            }
        });
        angular.forEach($scope.batches, function (value, key) {
            if(value.id == $scope.filterModel.batch_id){
                $scope.printing_details.batch_name = value.name;
            }
        });
        angular.forEach($scope.subjects, function (value, key) {
            if(value.id == $scope.filterModel.subject_id){
                $scope.printing_details.subject_name = value.name;
            }
        });
        angular.forEach($scope.exams, function (value, key) {
            if(value.id == $scope.filterModel.exam_detail_id){
                $scope.printing_details.exam_name = value.title;
                $scope.printing_details.total_marks = value.total_marks;
                $scope.printing_details.passing_marks = value.passing_marks;
                $scope.printing_details.exam_date = value.exam_date;
                $scope.printing_details.start_time = value.start_time;
                $scope.printing_details.end_time = value.end_time;
                $scope.printing_details.type = value.type;
            }
        });
        $scope.getTeacherAndClassTeacher($scope.filterModel);
    };
    
    $scope.getTeacherAndClassTeacher = function(obj){
        $http.post(base_url + 'examination/get_teacher_of_class', obj, config).then(
            function (success) {
                $scope.printing_details.teacher_name = success.data.teacher_name;
            },
            function (error) {
                console.log(error.data);
            }
        );
    };
    
    $scope.saveMarksheet = function () {
        $scope.data = [];
        angular.forEach($scope.students_group_wise, function (value, key) {
            //obtained_marks = $("#id_"+key).val();
            remarks = $("#remarks_"+key).val();
            $scope.data.push({
                id: value.id, 
                class_id: value.class_id, 
                batch_id: value.batch_id, 
                obtain_marks:value.obtained_marks,
                grade: value.grade,
                subject_id:$scope.filterModel.subject_id,
                exam_detail_id:$scope.filterModel.exam_detail_id,
                exam_id:$scope.filterModel.exam_id,
                passing_marks: $scope.passing_marks,
                remarks: remarks
            });
        });
        
        angular.forEach($scope.students, function(val, k){
            if(val.is_read == 'false'){
                $scope.data.push({
                    id: val.id, 
                    class_id: val.class_id, 
                    batch_id: val.batch_id, 
                    obtain_marks: "null",
                    grade : '',
                    subject_id:$scope.filterModel.subject_id,
                    exam_detail_id:$scope.filterModel.exam_detail_id,
                    exam_id:$scope.filterModel.exam_id,
                    passing_marks: $scope.passing_marks,
                    remarks: "null"
                });
            }
        });
        
        Loading("#marksStudentsTable", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'examination/save', $scope.data, config).then(
                function (success) {
                    Loading("#marksStudentsTable", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                    $scope.onSubmit(true);
                },
                function (error) {
                    console.log(error.data);
                    //Loading("#marksStudentsTable", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    //$window.location.href = 'errors/' + error.status;
                }
        );
    };
    
    $scope.moveNext = function(keyEvent, next_id, next_key){
        if (keyEvent.which === 13) {
            keyEvent.preventDefault();
            $("#"+next_id+next_key).focus();
        }
    };
});


app2.directive('starRating', function () {
    return {
        restrict: 'A',
        template: '<ul class="rating">' +
            '<li ng-repeat="star in stars" ng-class="star" ng-click="toggle($index)">' +
            '\u2605' +
            '</li>' +
            '</ul>',
        scope: {
            ratingValue: '=',
            max: '=',
            onRatingSelected: '&'
        },
        link: function (scope, elem, attrs) {

            var updateStars = function () {
                scope.stars = [];
                for (var i = 0; i < scope.max; i++) {
                    scope.stars.push({
                        filled: i < scope.ratingValue
                    });
                }
            };

            scope.toggle = function (index) {
                scope.ratingValue = index + 1;
                scope.onRatingSelected({
                    rating: index + 1
                });
            };

            scope.$watch('ratingValue', function (newVal, oldVal) {
                    updateStars();
            });
        }
    }
});

app2.controller("evaluateController", function ($scope, $http, $window, $location, $filter) {
    $scope.classes = {};
    $scope.batches = {};
    $scope.subjects = {};
    $scope.academicyears = {};
    $scope.filterModel = {type: 'non-subject', subject_id: ''};
    $scope.currentModel = {};
    $scope.evaluations = {};
    $scope.students = {};
    $scope.students_group_wise = [];
    $scope.grades = [{stars:1,grade:'E',legend:'Weak'},
                    {stars:2,grade:'D',legend:'Fair'},
                    {stars:3,grade:'C',legend:'Good'},
                    {stars:4,grade:'B',legend:'Excellent'},
                    {stars:5,grade:'A',legend:'Exceptional'}
                    ];

    $scope.onSubmit = function (valid) {
        if (valid) {
            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'students/getStudentForEvaluation', $scope.filterModel, config).then(
                    function (success) {
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.evaluation = success.data.evaluation;
                        $scope.students = success.data.students;
                        $scope.is_category = success.data.is_category;
                        $scope.is_db = success.data.is_db;
                        $scope.subject = success.data.subject;
                        $scope.type = success.data.type;
                        $scope.students_group_wise = [];
                        angular.forEach($scope.students, function (value, key) {
                            if(value.is_read == 'true'){
                                $scope.students_group_wise.push(value);
                            }
                        });

                        
                        
                    },
                    function (error) {
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        //$window.location.href = 'errors/' + error.status;
                        console.log(error);
                    }
            );
        }
    };

    $scope.saveEvaluation = function (method = false) {
            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'students/saveEvaluation', {'data' : $scope.students_group_wise,'method' : method,'type' : $scope.type}, config).then(
                    function (success) {
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        showNotification('<?php echo lang("success_app") ?>', success.data.msg, "success");
                        $scope.onSubmit(true);


                        
                        
                    },
                    function (error) {
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        //$window.location.href = 'errors/' + error.status;
                        console.log(error);
                    }
            );
    };

    $scope.getAvg = function(report){
        if(report.length == 0){
            return 0;
        }
        sum = 0;
        total = report.length;
        angular.forEach(report, function(val, key) {
                        sum += parseInt(val.stars);
                    });
        return Math.round(sum / total);
    }

    $scope.getAvg2 = function(report){
        if(report.length == 0){
            return 0;
        }
        sum = 0;
        total = report.length;
        angular.forEach(report, function(val, key) {
                        sum += parseInt(val.stars);
                    });
        return sum / total;
    }

    $scope.getGrade = function(report){
        stars = $scope.getAvg(report);
        var result = $scope.grades.find(obj => {
          return obj.stars === stars
        })

        if(result == undefined){
            return "";
        }else{
            return result.grade;
        }

        
    }

    $scope.getLegend = function(report){
        stars = $scope.getAvg(report);
        var result = $scope.grades.find(obj => {
          return obj.stars === stars
        })

        if(result == undefined){
            return "";
        }else{
            return '(' + result.legend + ')';
        }

        
    }

    $scope.initAcademicYears =  function(){
        Loading("#marksFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'examination/getAcademicYears', "", config).then(
            function (success) {
                Loading("#marksFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.initClasses(success.data.current_academic_year_id);
                $scope.initEvaluationTerms(success.data.current_academic_year_id);
                $scope.academicyears = success.data.data;
                $scope.filterModel.academic_year_id = success.data.current_academic_year_id;
                $scope.filterModel.class_id = "";
                $scope.filterModel.batch_id = "";
            },
            function (error) {
                Loading("#marksFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $window.location.href = 'errors/' + error.status;
            }
        );
    };

    $scope.initEvaluationTerms = function(id){
      Loading("#termsFilter", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'students/getEvaluationTerms', {academic_year_id: id}, config).then(
            function (success) {
                Loading("#termsFilter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.terms = success.data.terms;
                $scope.filterModel.term_id = '';
            },
            function (error) {
                Loading("#termsFilter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $window.location.href = 'errors/' + error.status;
            }
        );
    }
    
    $scope.initClasses = function (academic_year_id) {
        if(academic_year_id) {    
            Loading("#marksFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'examination/getClasses', { 'academic_year_id': academic_year_id }, config).then(
                function (success) {
                    Loading("#marksFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.classes = success.data;
                    //sconsole.log(success.data);
                },
                function (error) {
                    Loading("#marksFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
            );
        }
    };

    $scope.getspan = function(num){
        if(num == 0){
            return 1;
        }else{
            return num
        }
    }

    $scope.setType = function(){
        angular.forEach($scope.evaluations, function (val, key) {
            if(val.id == $scope.filterModel.evaluation_type){
                $scope.filterModel.type = val.type;
        }

        });
    }
    
    
    $scope.initBatches = function (class_id, academic_year_id, term_id) {
        if (class_id && academic_year_id) {
            Loading("#marksFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            Loading("#evaluationDropdown", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'students/getClassBatchesandEvaluation', {'class_id': class_id, 'academic_year_id':academic_year_id, term_id: term_id}, config).then(
                function (success) {
                    Loading("#marksFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    Loading("#evaluationDropdown", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.batches = success.data.batches;
                    $scope.evaluations = success.data.evaluations;
                    $scope.filterModel.batch_id = "";
                    $scope.subjects = [];
                    $scope.filterModel.subject_id = "";
                    $scope.filterModel.evaluation_type = "";
                },
                function (error) {
                    console.log(error.data);
                    Loading("#marksFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    Loading("#evaluationDropdown", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
            );
        }
    };

    $scope.getEvaluationsByTerm = function (class_id, academic_year_id, term_id) {
        if (class_id && academic_year_id) {
            Loading("#evaluationDropdown", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'students/getEvaluationsByTerm', {'class_id': class_id, 'academic_year_id':academic_year_id, term_id: term_id}, config).then(
                function (success) {
                    Loading("#evaluationDropdown", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.evaluations = success.data.evaluations;
                    $scope.filterModel.subject_id = "";
                    $scope.filterModel.evaluation_type = "";
                },
                function (error) {
                    console.log(error.data);
                    Loading("#evaluationDropdown", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
            );
        }
    };
    
    $scope.initSubjects = function (class_id, batch_id, academic_year_id) {
        Loading("#marksFilterSubjects", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (class_id && batch_id && academic_year_id) {
            Loading("#marksFilterSubjects", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'examination/getSubjects', { 'class_id': class_id, 'batch_id':batch_id, 'academic_year_id': academic_year_id}, config).then(
                function (success) {
                    Loading("#marksFilterSubjects", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.subjects = success.data;
                    $scope.filterModel.subject_id = "";
                },
                function (error) {
                    console.log(error.data);
                }
            );
        }
    };

});



app2.controller("reportCardController", function ($scope, $http, $window, $location, $filter, $sce) {
    $scope.classes = {};
    $scope.batches = {};
    $scope.academicyears = {};
    $scope.filterModel = {};
    $scope.currentModel = {};
    $scope.evaluations = {};
    $scope.students = [];
    $scope.student_ids = [];
    $scope.grades = [{stars:1,grade:'E',legend:'Weak'},
        {stars:2,grade:'D',legend:'Fair'},
        {stars:3,grade:'C',legend:'Good'},
        {stars:4,grade:'B',legend:'Excellent'},
        {stars:5,grade:'A',legend:'Exceptional'}
    ];

    $scope.onSubmit = function (valid) {
        if (valid) {
            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'students/getStudentsForReportCard', $scope.filterModel, config).then(
                    function (success) {
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.evaluation = success.data.evaluation;
                        $scope.all_evaluation = success.data.all_evaluation;
                        $scope.students = success.data.students;
                        $scope.subjects = success.data.subjects;
                        $scope.subjects_th = angular.copy($scope.subjects);
                        $scope.subjects2 = success.data.subjects2;
                        $scope.span = success.data.span;
                        $scope.name_span = success.data.name_span;
                        $scope.non_subject_span = success.data.non_subject_span;
                        $scope.overall_span = success.data.overall_span;
                        $scope.overall_span2 = success.data.overall_span2;

                        
                        
                        
                    },
                    function (error) {
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        //$window.location.href = 'errors/' + error.status;
                        console.log(error);
                    }
            );
        }
    };

    $scope.initEvaluationTerms = function(id){
      Loading("#termsFilter", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'students/getEvaluationTerms', {academic_year_id: id}, config).then(
            function (success) {
                Loading("#termsFilter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.terms = success.data.terms;
                $scope.filterModel.term_id = '';
            },
            function (error) {
                Loading("#termsFilter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $window.location.href = 'errors/' + error.status;
            }
        );
    }

    $scope.getEvaluationsByTerm = function (class_id, academic_year_id, term_id) {
        if (class_id && academic_year_id) {
            Loading("#evaluationDropdown", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'students/getEvaluationsByTerm', {'class_id': class_id, 'academic_year_id':academic_year_id, term_id: term_id}, config).then(
                function (success) {
                    Loading("#evaluationDropdown", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.evaluations = success.data.evaluations;
                    $scope.filterModel.subject_id = "";
                    $scope.filterModel.evaluation_type = "";
                },
                function (error) {
                    console.log(error.data);
                    Loading("#evaluationDropdown", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
            );
        }
    };

    

    $scope.getAvg = function(report){
        if(report.length == 0){
            return 0;
        }
        sum = 0;
        total = report.length;
        angular.forEach(report, function(val, key) {
                        sum += parseInt(val.stars);
                    });
        return Math.round(sum / total);
    }

    $scope.getAvg2 = function(report){
        if(report.length == 0){
            return 0;
        }
        sum = 0;
        total = report.length;
        angular.forEach(report, function(val, key) {
                        sum += parseInt(val.stars);
                    });
        return sum / total;
    }

    $scope.getGrade = function(report){
        stars = $scope.getAvg(report);
        var result = $scope.grades.find(obj => {
          return obj.stars === stars
        })

        if(result == undefined){
            return "";
        }else{
            return result.grade;
        }

        
    }

    $scope.getLegend = function(report){
        stars = $scope.getAvg(report);
        var result = $scope.grades.find(obj => {
          return obj.stars === stars
        })

        if(result == undefined){
            return "";
        }else{
            return '(' + result.legend + ')';
        }

        
    }

    $scope.initAcademicYears =  function(){
        Loading("#marksFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'examination/getAcademicYears', "", config).then(
            function (success) {
                Loading("#marksFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.initClasses(success.data.current_academic_year_id);
                $scope.initEvaluationTerms(success.data.current_academic_year_id);
                $scope.academicyears = success.data.data;
                $scope.filterModel.academic_year_id = success.data.current_academic_year_id;
                $scope.filterModel.class_id = "";
                $scope.filterModel.batch_id = "";
            },
            function (error) {
                Loading("#marksFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $window.location.href = 'errors/' + error.status;
            }
        );
    };
    
    $scope.initClasses = function (academic_year_id) {
        if(academic_year_id) {    
            Loading("#marksFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'examination/getClasses', { 'academic_year_id': academic_year_id }, config).then(
                function (success) {
                    Loading("#marksFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.classes = success.data;
                    //sconsole.log(success.data);
                },
                function (error) {
                    Loading("#marksFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
            );
        }
    };
    
    
    $scope.initBatches = function (class_id, academic_year_id, term_id) {
        if (class_id && academic_year_id) {
            Loading("#marksFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            Loading("#evaluationDropdown", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'students/getClassBatchesandEvaluation', {'class_id': class_id, 'academic_year_id':academic_year_id, term_id: term_id}, config).then(
                function (success) {
                    Loading("#marksFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    Loading("#evaluationDropdown", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.batches = success.data.batches;
                    $scope.evaluations = success.data.evaluations;
                    $scope.filterModel.batch_id = "";
                    $scope.filterModel.evaluation_type = "";
                },
                function (error) {
                    console.log(error.data);
                    Loading("#marksFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    Loading("#evaluationDropdown", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
            );
        }
    };

    $scope.showAllResultsInReportForm = function(obj){
        $scope.multi_result_cards = [];
        angular.forEach($scope.classes, function (value) {
            if(value.id == obj.class_id){
                obj.class_name = value.name;
            }
        });
        angular.forEach($scope.batches, function (value) {
            if(value.id == obj.batch_id){
                obj.batch_name = value.name;
            }
        });
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $("#myNav").css({"width":"100%"});
        $http.post(base_url + 'forms/print_all_student_evaluation_cards', obj, config).then(
            function (success) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                angular.forEach(success.data.students, function (value) {
                    $scope.multi_result_cards.push($sce.trustAsHtml(value.html));
                });
                console.log($scope.multi_result_cards);
            },
            function (error) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error);
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };
    $scope.closeNav = function (){
        $("#myNav").css({"width":"0"});
    };
});

app2.controller("syllabusController", function ($scope, $http, $window, $location, $filter) {
    $scope.filterModel = {};
    $scope.classes = {};
    $scope.batches = {};
    $scope.subjects = {};
    $scope.weeks = {};
    $scope.addWeekModel = {};
    $scope.addCommentModel = {};
    $scope.addWeekDetailModel = {};
    $scope.editWeekDetailModel = {};
    $scope.editWeekModel = {};
    $scope.schoolWorkingDays = {};
    $scope.weeklySyllabus = {};
    $scope.workingDays = {};
    $scope.requestId="";
    $scope.requestStatus;
    $scope.syllabusCanEdit;
    $scope.isClick = false;
    $scope.adminIDs = [];
    $scope.confirmDoneId = '';
    $scope.ccModel = {};
    $scope.cModelClasses = [];
    $scope.cModelBatches = [];
    $scope.cModelSubjects = [];
    $scope.cModelWeeks = [];
    $scope.cModelWeekDetails = [];
    $scope.what_to_copy = {};
    
    $scope.initClasses = function () {
        Loading("#syllabusFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'attendance/getClasses', "", config).then(
            function (success) {
                Loading("#syllabusFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.classes = success.data;
                //sconsole.log(success.data);
            },
            function (error) {
                Loading("#syllabusFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.status);
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };
    
    $scope.initBatches = function (class_id) {
        Loading("#syllabusFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (class_id) {
            Loading("#syllabusFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'attendance/getClassBatches', {class_id: class_id}, config).then(
                    function (success) {
                        Loading("#syllabusFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.batches = success.data;
                        $scope.filterModel.batch_id = "";
                    },
                    function (error) {
                        console.log(error.data);
                    }
            );
        }
    };
    
    $scope.initSubjects = function (class_id, batch_id) {
        Loading("#syllabusFilterSubjects", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (class_id && batch_id) {
            Loading("#syllabusFilterSubjects", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'syllabus/getSubjects', {class_id: class_id, batch_id:batch_id}, config).then(
                function (success) {
                    Loading("#syllabusFilterSubjects", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.subjects = success.data;
                    $scope.filterModel.subject_id = "";
                },
                function (error) {
                    console.log(error.data);
                }
            );
        }
    };
    
    $scope.initClasses2 = function () {
        Loading("#cModelClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'attendance/getClasses', "", config).then(
            function (success) {
                Loading("#cModelClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.cModelClasses = success.data;
                //sconsole.log(success.data);
            },
            function (error) {
                Loading("#cModelClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.status);
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };
    
    $scope.initBatches2 = function (class_id) {
        Loading("#cModelBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (class_id) {
            Loading("#cModelBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'attendance/getClassBatches', {class_id: class_id}, config).then(
                function (success) {
                    Loading("#cModelBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.cModelBatches = [];
                    angular.forEach(success.data, function(val, key) {
                        if(val.id !== $scope.filterModel.batch_id){
                            $scope.cModelBatches.push(val);
                        }
                    });
                    $scope.ccModel.batch_id = "";
                },
                function (error) {
                    console.log(error.data);
                }
            );
        }
    };
    
    $scope.initSubjects2 = function (class_id, batch_id) {
        Loading("#cModelSubjects", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (class_id && batch_id) {
            Loading("#cModelSubjects", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'syllabus/getSubjects', {class_id: class_id, batch_id:batch_id}, config).then(
                function (success) {
                    Loading("#cModelSubjects", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.cModelSubjects = success.data;
                    $scope.ccModel.subject_id = "";
                },
                function (error) {
                    console.log(error.data);
                }
            );
        }
    };
    
    $scope.initCopyData = function(data){
        $scope.copyWeekForm.$setUntouched();
        $scope.copyWeekForm.$setPristine();
        $scope.ccModel = {};
        $scope.what_to_copy = data;
    };
    
    $scope.saveCopiedWeek = function(){
        Loading("#save-copied-week-model-content", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'syllabus/copySyllabus', {what:$scope.what_to_copy, 'where':$scope.ccModel}, config).then(
            function (success) {
                Loading("#save-copied-week-model-content", '<?php echo lang("loading_datatable") ?>', "", "hide");
                if(success.data.status == 'success'){
                    $("#copyWeekModal").modal("hide");
                    showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                    $scope.onSubmit();
                } else if(success.data.status == 'error'){
                    showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                }
                //console.log(success.data);
            },
            function (error) {
                Loading("#save-copied-week-model-content", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };
    
    $scope.onSubmit = function(){
        $scope.isClick = true;
        Loading("#syllabus_search_filter", '<?php echo lang("loading_datatable") ?>', "", "show");
        $scope.filterModel.type = 'syllabus';
        $http.post(base_url + 'syllabus/getSyllabus', $scope.filterModel, config).then(
            function (success) {
                Loading("#syllabus_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                //console.log(success.data);
                $scope.weeklySyllabus = success.data.syllabus;
                $scope.syllabusCanEdit = success.data.can_syllabus_edit;
                $scope.requestId = success.data.request_id;
                $scope.requestStatus = success.data.reqeust_status;
            },
            function (error) {
                Loading("#syllabus_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };
    
    $scope.saveWeek = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'syllabus/saveWeek', angular.extend($scope.addWeekModel, $scope.filterModel), config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    if(success.data.status == 'success'){
                        $("#addWeekModal").modal("hide");
                        showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                        $scope.onSubmit();
                    } else if(success.data.status == 'error'){
                        showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                    }
                    
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    };
    
    $scope.initWeekDetailModal = function(id, day){
        $scope.addWeekDetailModel.selectedDate = day;
        $scope.addWeekDetailModel.selectedWeekId = id; 
    };
    
    $scope.initEditWeekModal = function(week){
        var start_date = $filter('date')(new Date(week.start_date.split('-').join('/')), "dd/M/yyyy");
        var end_date = $filter('date')(new Date(week.end_date.split('-').join('/')), "dd/M/yyyy");
        
        $scope.editWeekModel.id = week.id;
        $scope.editWeekModel.class_id = week.class_id;
        $scope.editWeekModel.batch_id = week.batch_id;
        $scope.editWeekModel.start_date = start_date;
        $scope.editWeekModel.end_date = end_date;
        $scope.editWeekModel.subject_id = week.subject_id;
        $scope.editWeekModel.week = week.week;
    };
    
    $scope.saveEditWeek = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'syllabus/updateWeek', $scope.editWeekModel, config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    if(success.data.status === 'success'){
                        $("#editWeekModal").modal("hide");
                        $scope.onSubmit();
                    }else if(success.data.status === 'error'){
                        showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                    }
                    //console.log(success.data);
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    };
    
    $scope.saveWeekDetail = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'syllabus/saveWeekDetail', $scope.addWeekDetailModel, config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $("#addWeekDetailModal").modal("hide");
                    $scope.addWeekDetailModel.topic = "";
                    $scope.addWeekDetailModel.status = "Pending";
                    $scope.addWeekDetailModel.comment = "";
                    $scope.onSubmit();
                    //console.log(success.data);
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    };
    
    $scope.changeStatusWithConfirmation = function(id){
        $("#doneSyllabusModal").modal("show");
        $scope.confirmDoneId = id;
    };
    
    $scope.changeStatus = function(status, id){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        if(status === 'Partially Done' || status==='Reschedule'){
            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
            $scope.addCommentModel.status = status;
            $scope.addCommentModel.id = id;
            $("#addCommentModal").modal("show");
        } else {
            if(status == "Done"){
                $("#doneSyllabusModal").modal("hide");
                $scope.confirmDoneId = '';
            }
            $http.post(base_url + 'syllabus/changeSyllabusStatus', {status:status, id: id}, config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    if(success.data.status === "success"){
                        if(status == "Done"){
                            var otherData = {'class_id':$scope.filterModel.class_id, 'batch_id':$scope.filterModel.batch_id, subject_id:$scope.filterModel.subject_id};
                            $scope.getAllGuardians(otherData);
                        }
                        showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                        $scope.onSubmit();
                    } else {
                        showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                    }
                    //console.log(success.data);
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
            );
        }
    };
    
    $scope.saveComment = function(){
        $http.post(base_url + 'syllabus/addCommentAndChangeStatus', $scope.addCommentModel, config).then(
            function (success) {
                if(success.data.status === "success"){
                    $("#addCommentModal").modal("hide");
                    showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                    $scope.onSubmit();
                } else {
                    showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                }
                //console.log(success.data);
            },
            function (error) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };
    
    $scope.initEditWeekDetailModal = function(obj){
        $scope.editWeekDetailModel = obj;
        //$scope.editWeekDetailModel.syllabus_week_id = id;
    };
    
    $scope.updateWeekDetail = function(){
        $http.post(base_url + 'syllabus/updateWeekDetails', $scope.editWeekDetailModel, config).then(
            function (success) {
                if(success.data.status === "success"){
                    $("#editWeekDetailModal").modal("hide");
                    showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                    $scope.onSubmit();
                } else {
                    showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                }
                //console.log(success.data);
            },
            function (error) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };
    $scope.my_new_id = "";
    $scope.my_new_status = "";

    $scope.request = function(id, status){

        $scope.my_new_id = id;
        $scope.my_new_status = status;
    };

        $scope.confirm_request = function(state){
        
        console.log($scope.my_new_id);

            if($scope.requestText != null){
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                $http.post(base_url + 'syllabus/reqForApprovalSyls', {id:$scope.my_new_id,status:$scope.my_new_status,reason:$scope.requestText, state:state}, config).then(
                   function (success) {
                   $('.edit_attendance_request_model').modal('hide');
                        showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                        $scope.r_id = $scope.my_new_id;
                        var otherData = {class_id:$scope.filterModel.class_id,batch_id:$scope.filterModel.batch_id,subject_id:$scope.filterModel.subject_id};
                        $scope.getSchoolAdmins(otherData);
                        $scope.onSubmit();
                        
                        $scope.requestText ="";
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                   },
                   function (error) {
                       Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                       console.log(error.data);
                   });
            }else{
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $('#request_error').show();
            }
           };
    $scope.getSchoolAdmins = function(otherData){
        $http.post(base_url + 'syllabus/getSchoolAdmins', {}, config).then(
            function(success){
                publicNotificationViaPusher("lbl_approval_syllabus", otherData, success.data.new_ids, "applications/all", {'sender': success.data.sender}, $scope.r_id);
                //$scope.adminIDs = success.data;
            },
            function(error){
                console.log(error.data);
            }
        );
    };
    
    $scope.getAllGuardians = function(otherData){
        $http.post(base_url + 'syllabus/getAllGuardians', otherData, config).then(
            function(success){
                publicNotificationViaPusher("lbl_study_plan_status_change_to_done", otherData, success.data.new_ids, "applications/all", {'sender': success.data.sender}, $scope.r_id);
            },
            function(error){
                console.log(error.data);
            }
        );
    };
    
    $scope.deleteSyllabusOfDay = function(d){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'syllabus/deleteSyllabusOfDay', d, config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                    $scope.onSubmit();

                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    };
    
    
    $scope.deleteSyllabusOfWeek = function(id){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'syllabus/deleteSyllabusOfWeek', {id:id}, config).then(
            function (success) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                $scope.onSubmit();
            },
            function (error) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };
});

app2.controller("appCtrl", function ($scope, $http, $window, $location, $filter) {
$scope.classes = {};
    $scope.batches = {};
    $scope.subjects = {};
    $scope.exams = {};
    $scope.students = {};
    $scope.filterModel = {};
    $scope.enteredMarks = {};
    $scope.message;
    $scope.printing_details = {};
    $scope.passing_marks = 0;
    $scope.temp={};

    $scope.onView = function (class_id,batch_id,subject_id,name,state){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $scope.temp.class_id = class_id;
        $scope.temp.batch_id = batch_id;
        $scope.temp.subject_id = subject_id;
        $scope.temp.type = 'syllabus';
        $scope.temp.subjectname=name;
        //$scope.temp = viewvariable;
        console.log($scope.temp);
       
        if(state =='old'){

          $http.post(base_url + 'syllabus/getSyllabus', $scope.temp, config).then(
              function (success) {
                  Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                  console.log(success.data);
                  $scope.weeklySyllabus = success.data.syllabus;
                  $scope.syllabusCanEdit = success.data.can_syllabus_edit;
                  $scope.requestId = success.data.request_id;
                  $scope.requestStatus = success.data.reqeust_status;
                  $scope.subjectname=$scope.temp.subjectname;
              },
              function (error) {
                  Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                  console.log(error.data);
              }
          ); 

        }else{

          onSubmitStudyplan_viewbtn();
          $("#calendar").addClass("disabledbutton");
      }
         
         
         Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
    }

    $scope.appNotification = function (status, notification_id, req_id, req_type, req_date){
       

        $('#send_response').click(function(){
            if($scope.responseText != null && $scope.responseText != ''){
             Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                $("#response_error").hide();
                $http.post(base_url + "notification/appNotification", {"reason":$scope.responseText, "notification_id": notification_id, "status":status, "id": req_id, "type":req_type, "date":req_date}, config).then(
                    function (success){
                        $('.response_model_application').modal('hide');
                        var otherData = {};
                        publicNotificationViaPusher(success.data.message, otherData,  success.data.new_ids, "applications/all", {'sender': success.data.sender}, req_id);
                        $scope.responseText = '';
                        $window.location.href = 'applications/all';
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    },
                    function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        console.log(error.data);
                    }
                );
            }else{
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $("#response_error").show();
            }
        });
        
    };
    
    $scope.viewEmpAttendance = function(date, dept_id, cat_id){
       Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
       var flag = "true";
       $http.post(base_url + 'attendance/fetchEmployeesAttendance', {date,flag,'department_id':dept_id, 'category_id': cat_id}, config).then(
          function (success) {
              Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
              $scope.employees = success.data.employees;
              $scope.message = success.data.message;
              $scope.disable = success.data.disable;
              $scope.selectedDate = date;
              console.log($scope.employees);
          },
          function (error) {
              Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
              //$window.location.href = 'errors/' + error.status;
               console.log(error);
          }

      );
   }
    
    $scope.viewAttendance = function(date, class_id, batch_id){
       Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
       var flag = "true";
        $http.post(base_url + 'attendance/fetchStudentsAttendance', {date, class_id, batch_id, flag}, config).then(
           function (success) {
               Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
               $scope.students_marked = success.data.students_marked;
               $scope.message = success.data.message;
               $scope.disable = success.data.disable;
               $scope.selectedDate = date;
               console.log($scope.students);
           },
           function (error) {
               Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
               $window.location.href = 'errors/' + error.status;
               // console.log(error);
           }

       );
    };

    $scope.viewMarkSheet = function (class_id, batch_id, subject_id, exam_detail_id, subject_name, academic_year_id) {
        
            $scope.filterModel.class_id = class_id;
            $scope.filterModel.batch_id = batch_id;
            $scope.filterModel.subject_id = subject_id;
            $scope.filterModel.exam_detail_id = exam_detail_id;
            $scope.printing_details.subject_name = subject_name;
            $scope.filterModel.academic_year_id = academic_year_id;
            
            $scope.savePrintingDetails($scope.filterModel);
            $http.post(base_url + 'examination/fetchStudents', $scope.filterModel, config).then(
                    function (success) {
                        $scope.students = {};
                        $scope.students = success.data.students;
                        $scope.printing_details.class_name = success.data.class_name;
                        $scope.printing_details.batch_name = success.data.batch_name;
                        $scope.printing_details.exam_name = success.data.exam_name;
                        $scope.printing_details.total_marks = success.data.total_marks;
                        $scope.printing_details.passing_marks = success.data.passing_marks;
                        $scope.printing_details.exam_date = success.data.exam_date;
                        $scope.printing_details.start_time = success.data.start_time;
                        $scope.printing_details.end_time = success.data.end_time;
                        $scope.printing_details.type = success.data.type;
                        $scope.message = success.data.message;
                        $scope.passing_marks = success.data.passing_marks;
                        $scope.action = success.data.edit;
                        $scope.disable = success.data.disable;
                        
                        if($scope.disable === "TRUE") {
                            $("#marksStudentsTable").keydown(function (event) { return false; });
                        } else {
                            $("#marksStudentsTable").keydown(function (event) { return true; });
                        }
                        
                        $scope.exist = success.data.exist;
                    },
                    function (error) {
                        //$window.location.href = 'errors/' + error.status;
                        console.log(error);
                    }
            );
        };
    
    $scope.savePrintingDetails = function(obj){
    
        $http.post(base_url + 'examination/get_teacher_of_class', obj, config).then(
            function (success) {
                $scope.printing_details.teacher_name = success.data.teacher_name;
            },
            function (error) {
                console.log(error.data);
            }
        );
    };


    $scope.resquestReason = function(log_id){
        
         $http.post(base_url + 'attendance/resquestReason', {log_id:log_id}, config).then(
         function(success){
            $scope.reason = success.data.edit_reason;
            //console.log($scope.reason);
        },function(error){
             
        });
    };
    $scope.responseReason = function(log_id){
         $http.post(base_url + 'attendance/responseReason', {log_id:log_id}, config).then(
         function(success){
            $scope.reason = success.data.response;
            //console.log($scope.reason);
        },function(error){
             
        });
    };
 
});

app2.controller('bookshopCtrl', function ($scope, $http, $sce) {

    $scope.selectedClass = 'all';
    $scope.removeBtn = false;

    $scope.init = function () {
        $http.post(base_url + 'attendance/getClasses', "", config).then(
        function (response) {
            $scope.classes = response.data;
        });
    };

    $scope.getBookshop = function(){
    if($scope.selectedClass!='all'){
    $scope.removeBtn = false;
}

    $http.post(base_url + 'study_material/get_bookshop', {class:$scope.selectedClass}, config).then(
        function (response) {
            $scope.bookshop = $sce.trustAsHtml(response.data.bookshop);

        }
    );
    };

    $scope.removeFilters = function(){
    $('.js-example-basic-multiple').val(null).trigger('change.select2');
    
    $scope.init();
    $scope.removeBtn = false;
    $scope.selectedClass = 'all';
    $scope.getBookshop();
};

});

app2.controller("feeDiscountController", function ($scope, $http, $sce) {

    $scope.selectedDiscountVarients = [];
    $scope.discountModel ={};
    $scope.discountModel.checkall = [];
    $scope.editModel = {};
    $scope.discont = [];
    $scope.discount_name ={};
    
    $scope.initClasses = function () {
        $http.post(base_url + 'attendance/getClasses', "", config).then(
                function (success) {

                    $scope.classes = success.data;
                },
                function (error) {
                    $window.location.href = 'errors/' + error.status;
                }
        );
    };

    function isInArray(value, array) {
        return Object.values(array).indexOf(value) > -1;
    }

     $scope.select_all = function () {
        angular.forEach($scope.classes, function (cls) {
            id = cls.id;
            $scope.discountModel.checkall[id] = $scope.discountModel.selectall;
        });


    }
    
    $scope.discouts = function(id){
        $http.post(base_url + 'fee/discounts', {id,id}, config).then(
            function(success){
               if(success.data){
                    $scope.discount = success.data;
                    $scope.discount_name = success.data[0].name;
                    $scope.class ="";
                    $scope.dis_var_form.$setUntouched();
                    $scope.dis_var_form.$setPristine();
                    $scope.selectedDiscountVarients = [];
                }
            }
        );
     };
    
    $scope.onSaveDiscount = function(valid){
      if(valid){
           $scope.discountModel.class_error = false;
           $http.post(base_url + 'fee/saveDiscount',  $scope.discountModel, config).then(
               function(success){
                   $scope.discountModel = {};
                   $scope.init();
                   $scope.saveDiscountform.$setUntouched();
                   $scope.saveDiscountform.$setPristine();

                  $('#addDiscount').modal('toggle');
                 $scope.getDiscounts();
                  showNotification('<?php echo lang("success_app") ?>', success.data.message, success.data.status);

              },function(error){
                  $('#addDiscount').modal('toggle');
                  showNotification('<?php echo lang("error_app") ?>', success.data.message, success.data.status);
                  console.log(error.data);
              });
      }

   };
    $scope.onUpdateDiscount = function(data){
        if(data){
            $scope.myModel2 = data;
        }
    };

    $scope.editDiscount = function(valid){
        if(valid){
        Loading("#editDiscount333", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'fee/editDiscount', $scope.editModel, config).then(
                function(success){
                    $('#editDiscountForm').modal('toggle');
                    Loading("#editDiscount333", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    showNotification('<?php echo lang("success_app") ?>', success.data.message, success.data.status);
                    $("#editDiscount333").modal("hide");
                    $scope.getDiscounts();

                    $scope.editDiscountForm.$setUntouched();
                    $scope.editDiscountForm.$setPristine();
                    //$scope.editModel = {};
            },function(error){
                $('#editDiscountForm').modal('toggle');
                Loading("#editDiscount333", '<?php echo lang("loading_datatable") ?>', "", "hide");
                showNotification('<?php echo lang("success_app") ?>', success.data.message, success.data.status);
                 console.log(error.data);
            });
        }
    };

    $scope.updateDiscount = function(valid){
        if(valid){
            $http.post(base_url + 'fee/editDiscount', $scope.myModel2, config).then(
                function(success){
                    $('#editDiscountForm').modal('toggle');
                    showNotification('<?php echo lang("success_app") ?>', success.data.message, success.data.status);
                    $("#editDiscount333").modal("hide");
                    $scope.getDiscounts();
                    //$scope.myModel2 = {};
            },function(error){
                $('#editDiscountForm').modal('toggle');
                showNotification('<?php echo lang("success_app") ?>', success.data.message, success.data.status);
                 console.log(error.data);
            });
        }
    };

    $scope.showConfirmationAlert = function (id) {
        swal({
            title: '<?php echo lang("are_you_sure") ?>',
            text: '<?php echo lang("delete_message") ?>',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55", confirmButtonText: '<?php echo lang("yes_delete") ?>',
            cancelButtonText: '<?php echo lang("btn_cancel") ?>',
            closeOnConfirm: true,
            closeOnCancel: true
        },
                function (isConfirm) {
                    if (isConfirm) {
                        $http.post(base_url + "fee/softDeleteDiscount", {"id": id}, config).then(
                                function (success) {
                                    if (success.data.status === "success") {
                                        $scope.getDiscounts();
                                    }

                                },
                                function (error) {
                                    console.log(error.data);
                                }
                        );
                    }
                });
    };

    $scope.getDiscountVarients = function(){
        var class_id = $scope.class;
        var discount_id = $scope.discount[0].id;
        $http.post(base_url + 'fee/getDiscountVarients', {class_id:class_id, discount_id:discount_id}, config).then(
        function(success){
            if(success.data){
                $scope.discout = {};
                $scope.selectedDiscountVarients = success.data.feetypes;
            }else{
                $scope.selectedDiscountVarients = [];
                $scope.selectedDiscountVarients.message = success.data.message;
            }
        },
        function(error){
            console.log(error.data);
        });
    };

    $scope.saveDiscountVarents = function(){
       Loading("#feeDiscountVarientModal", '<?php echo lang("loading_datatable") ?>', "", "show");
       //var discount_id = $scope.discount[0].id;
      $http.post(base_url + 'fee/saveDiscountVarents', {varients: $scope.selectedDiscountVarients, class_id:$scope.class, discount_id:$scope.discount[0].id}, config).then(
          function(success){
               Loading("#feeDiscountVarientModal", '<?php echo lang("loading_datatable") ?>', "", "hide");
               $scope.selectedDiscountVarients = {};
               $scope.class = "";
             if(success.data.status == 'success'){
                   $("#feeDiscountVarientModal").modal("hide");
                   showNotification('<?php echo lang("success_app") ?>', success.data.message, success.data.status);
              }else{
                   showNotification('<?php echo lang("error_app") ?>', success.data.message, success.data.status);
              }

          },
          function(error){
          Loading("#feeDiscountVarientModal", '<?php echo lang("loading_datatable") ?>', "", "hide");
          showNotification('<?php echo lang("error_app") ?>', error.data.message, "error");
          $("#feeDiscountVarientModal").modal("hide");
           //console.log(error.data);
      });
    };
    
    $scope.resetVarents = function(data){

    $http.post(base_url + 'fee/resetVarents', {varient_id: data}, config).then(
            function(success){
               $scope.id = success.data[0].discount_id;
               console.log($scope.id );

               $scope.getDiscountVarients($scope.id);

           },
           function(error){
           showNotification('<?php echo lang("error_app") ?>', error.data.message, "error");
            //console.log(error.data);
       }
       );
   };


    $scope.selectedClass = 'all';
    $scope.removeBtn = false;

    $scope.init = function () {
       $http.post(base_url + 'fee/get_discounts', "", config).then(
       function (response) {
           $scope.discounts = response.data.discounts;
       });
    };

    $scope.getDiscounts = function(){
        if($scope.selectedClass!='all'){
            $scope.removeBtn = false;
        }
    
        $http.post(base_url + 'fee/get_discounts', {class:$scope.selectedClass}, config).then(
            function (response) {
            $scope.discounts = response.data.discounts;
        });
    };

    $scope.removeFilters = function(){
        $('.js-example-basic-multiple').val(null).trigger('change.select2');
    
        $scope.init();
        $scope.removeBtn = false;
        $scope.selectedClass = 'all';
        $scope.getDiscounts();
    };

});

app2.factory("commonService", function($http){
    return{
        getClasses : function(){
            var http = $http.post(base_url + 'attendance/getClasses', "", config).then(
                function (result) {
                   return result.data;
                }
            );
            return http;
        },
        getBatches : function(id){
            var http = $http.post(base_url + 'attendance/getClassBatches', {class_id: id}, config).then(
                function (result) {
                    return result.data;
                }
            );
            return http;
        }
    };
});

app2.controller("majorSheetController", function ($scope, $http, $window, $location, $sce) {
    $scope.classes = {};
    $scope.batches = {};
    $scope.subjects = {};
    $scope.exams = {};
    $scope.students = [];
    $scope.overalltotal = 0;
    $scope.remarksModel = {};
    $scope.showModel = {};
    $scope.positions = [];
    $scope.status = "";
    $scope.message_from_server = "";
    $scope.selected_student_for_teacher_remarks = {};
    $scope.total_obtained_marks_array = [];
    $scope.student_ids = [];
    $scope.exam_ids = [];
    $scope.class_ids = [];
    $scope.batch_ids = [];
    $scope.multi_result_cards = [];
    $scope.selectedClassName = "";
    $scope.selectedBatchName = "";
    $scope.selectedExamName = "";
    $scope.students_shift_wise = [];
    $scope.academicyears = {};
    $scope.filterModel = {};
    
    $scope.showAllResultCards = function(ids, obj){
        $scope.multi_result_cards = [];
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $("#myNav").css({"width":"100%"});
        $http.post(base_url + 'forms/print_all_result_cards', {"ids":ids,"obj":obj}, config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    
                    angular.forEach(success.data, function (value) {
                        $scope.multi_result_cards.push($sce.trustAsHtml(value));
                    });
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error);
                    //$window.location.href = 'errors/' + error.status;
                }
        );
    };

    $scope.loadEditData = function(std){
        $scope.editMarksForm.$setUntouched();
        $scope.editMarksForm.$setPristine();
        angular.forEach(std.subjects, function (value) {
            if(value.exams[0].obtained_marks == null){
                    value.exams[0].new_marks = '';
            }else {
                value.exams[0].obtained_marks = parseInt(value.exams[0].obtained_marks);
                value.exams[0].new_marks = value.exams[0].obtained_marks;
            }
            if(value.exams[0].remarks == null){
                    value.exams[0].new_remarks = '';
            }else {
                value.exams[0].remarks = value.exams[0].remarks;
                value.exams[0].new_remarks = value.exams[0].remarks;
            }
        });

        if(std.grouped_subjects != null){
        var total_subjects = std.grouped_subjects.split(",");
            angular.forEach(std.subjects, function (value) {
                if(total_subjects.indexOf(value.subject_id) != -1){
                    value.is_subject = true;
                }else {
                    value.is_subject = false;
                }
            });
        }else{
        angular.forEach(std.subjects, function (value) {
                    value.is_subject = true;      
            });
        }
        $scope.editData = std;
    }
    
    $scope.editsaveMarksheet = function(valid){
        if(valid){
            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'examination/update_marks', $scope.editData, config).then(
                function (success) {
                    $('#editMarks').modal('toggle');
                    $scope.onSubmit(true);
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                }
        );
    }
        
    };

    $scope.loadDeleteData = function (std){
        $scope.deleteModel = std;
        $scope.deleteModel.exam_id = $scope.deleteExamId;
    }

    $scope.deleteMarks = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'examination/delete_marks', $scope.deleteModel, config).then(
                function (success) {
                    $('#deleteMarks').modal('toggle');
                    $scope.onSubmit(true);
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                }
        );
}

    $scope.majorsheetPrint = function(id, logo, name, dirr){
        var d = '<div style='+ dirr +'>'+
            '<p style="text-align:center;"><img src="<?php echo base_url(); ?>uploads/logos/'+logo+'" width="150px"/></p>'+
            '<h3 style="text-align:center;"><b>'+name+'</b></h3>'+
            '<table style="width:100%; border-spacing: 5px; margin-bottom: 10px; border-collapse: separate;">'+
            '<tr><td><strong><?php echo lang("class_name"); ?></strong></td><td><u>'+$scope.selectedClassName+'</u></td>'+
            '<td><strong><?php echo lang("lbl_batch"); ?></strong></td><td><u>'+$scope.selectedBatchName+'</u></td>'+
            '</tr><tr><td><strong><?php echo lang("lbl_exam_session"); ?></strong></td><td><u>'+$scope.selectedExamName+'</u></td></tr>'+
            '</table>'+
        '</div>';
    
    
        $("#" + id).print({
            globalStyles: false,
            mediaPrint: false,
            stylesheet: "<?php echo base_url(); ?>assets/css/custom-majorsheet.css",
            noPrintSelector: ".no-print",
            iframe: true,
            append: null,
            prepend: d,
            manuallyCopyFormValues: true,
            deferred: $.Deferred(),
            timeout: 750,
            title: null,
            doctype: '<!doctype html>'
        });
    };
    
    $scope.is_all_subject_marks_added = function(subjects){
        var result = true;
        angular.forEach(subjects, function (value) {
            if(value.exams[0].obtained_marks != null) {
                result = false;
            }
        });
        return result;
    };
    
    $scope.closeNav = function (){
        $("#myNav").css({"width":"0"});
    };
    
    $scope.initAcademicYears =  function(){
        Loading("#majorSheetFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'examination/getAcademicYears', "", config).then(
            function (success) {
                Loading("#majorSheetFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.filterModel.academic_year_id = success.data.current_academic_year_id;
                $scope.initClasses(success.data.current_academic_year_id);
                $scope.initExams(success.data.current_academic_year_id);
                $scope.academicyears = success.data.data;
                $scope.filterModel.class_id = "";
                $scope.filterModel.batch_id = "";
            },
            function (error) {
                Loading("#majorSheetFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $window.location.href = 'errors/' + error.status;
            }
        );
    };
    
    $scope.initClasses = function (academic_year_id) {
        if(academic_year_id) {    
            Loading("#majorSheetFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'examination/getClasses', { 'academic_year_id': academic_year_id }, config).then(
                function (success) {
                    Loading("#majorSheetFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.classes = success.data;
                    //sconsole.log(success.data);
                },
                function (error) {
                    Loading("#majorSheetFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
            );
        }
    };
    
    
    $scope.initBatches = function (class_id, academic_year_id) {
        if (class_id && academic_year_id) {
            Loading("#majorSheetFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'examination/getClassBatches', {'class_id': class_id, 'academic_year_id':academic_year_id}, config).then(
                function (success) {
                    Loading("#majorSheetFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.batches = success.data;
                    $scope.filterModel.batch_id = "";
                },
                function (error) {
                    console.log(error.data);
                    Loading("#majorSheetFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
            );
        }
    };
    
    $scope.initExams = function (academic_year_id) {
        if(academic_year_id) {
            Loading("#majorSheetFilterExams", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'examination/getSchoolExams',{academic_year_id:academic_year_id}, config).then(
                function (success) {
                    Loading("#majorSheetFilterExams", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.exams = success.data;
                },
                function (error) {
                    console.log(error.data);
                }
            );
        }
    };
    
    $scope.onSubmit = function(valid){
        if(valid){
            $scope.students = [];
            $scope.students_shift_wise = [];
            $scope.student_ids = [];
            $scope.class_ids = [];
            $scope.batch_ids = [];
            $scope.exam_ids = [];
            Loading("#majorSheetFilterForm", '<?php echo lang("loading_datatable") ?>', "", "show");
            $scope.deleteExamId = $scope.filterModel.exam_id;
            $http.post(base_url + 'examination/getStudentsForMajorSheet', $scope.filterModel, config).then(
                function (success) {
                    Loading("#majorSheetFilterForm", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.overalltotal = success.data.exam_total_marks;
                    $scope.students = success.data.data;
                    $scope.status = success.data.status;
                    $scope.message_from_server = success.data.message;
                    angular.forEach($scope.students, function (value) {
                        $scope.student_ids.push(value.student_id);
                        $scope.class_ids.push(value.class_id);
                        $scope.batch_ids.push(value.batch_id);
                        $scope.exam_ids.push(value.subjects[0].exams[0].exam_id);
                    });
                    //console.log($scope.students);
                    angular.forEach($scope.students, function (value, key) {
                        if(value.is_shifted == 0){
                            $scope.students_shift_wise.push(value);
                        }
                    });
                    
                    angular.forEach($scope.classes, function (value) {
                        if(value.id == $scope.filterModel.class_id){
                            $scope.selectedClassName = value.name;
                        }
                    });
                    angular.forEach($scope.batches, function (value) {
                        if(value.id == $scope.filterModel.batch_id){
                            $scope.selectedBatchName = value.name;
                        }
                    });
                    angular.forEach($scope.exams, function (value) {
                        if(value.id == $scope.filterModel.exam_id){
                            $scope.selectedExamName = value.title;
                        }
                    });
                    
                    $('body').tooltip({
                        selector: '[rel=tooltip]'
                    });
                },
                function (error) {
                    Loading("#majorSheetFilterForm", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
            );
        }
    };
    
    
    
    $scope.setSelectedStudentForRemarks = function(id, exam_id){
        $scope.remarksModel = {};
        $scope.teacherRemarksForm.$setUntouched();
        $scope.teacherRemarksForm.$setPristine();
        $scope.selected_student_for_teacher_remarks.student_id = id;
        $scope.selected_student_for_teacher_remarks.exam_id = exam_id;
    };
    
    $scope.saveTeacherRemarks = function(){
        $scope.remarksModel.student_id = $scope.selected_student_for_teacher_remarks.student_id;
        $scope.remarksModel.exam_id = $scope.selected_student_for_teacher_remarks.exam_id;
        Loading("#bs-teacher-remarks-modal-sm", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'examination/save_teacher_remarks', $scope.remarksModel, config).then(
            function (success) {
                Loading("#bs-teacher-remarks-modal-sm", '<?php echo lang("loading_datatable") ?>', "", "hide");
                if(success.data.status == "success"){
                    $("#bs-teacher-remarks-modal-sm").modal("hide");
                    showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                    $scope.onSubmit(true);
                }if(success.data.status == "danger"){
                    showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                }
            },
            function (error) {
                Loading("#bs-teacher-remarks-modal-sm", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };
    
    $scope.showTeacherRemark = function(remark, id){
        $scope.showModel.remark = remark;
        $scope.showModel.id = id;
    };
    
    $scope.updateTeacherRemarks = function(){
        Loading("#bs-update-teacher-remarks-modal-sm", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'examination/update_teacher_remarks', $scope.showModel, config).then(
            function (success) {
                Loading("#bs-update-teacher-remarks-modal-sm", '<?php echo lang("loading_datatable") ?>', "", "hide");
                if(success.data.status == "success"){
                    $("#bs-update-teacher-remarks-modal-sm").modal("hide");
                    showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                    $scope.onSubmit(true);
                }if(success.data.status == "danger"){
                    showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                }
            },
            function (error) {
                Loading("#bs-update-teacher-remarks-modal-sm", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };
});

/*------employee Report Controller*/
app2.controller("reportEmployeeController", function ($scope, $http, $window, $location, $sce) {
    $scope.months = {};
    $scope.report = {};
    $scope.range = [];
    $scope.finalReport = {};

    $scope.initMonths = function(){
        Loading("#arFilterMonth", '<?php echo lang("loading_datatable") ?>', "", "hide");
        $http.post(base_url + 'attendance/count_academic_year_months', "", config).then(
                function (success) {
                    Loading("#arFilterMonth", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.months = success.data;
                },
                function (error) {
                    Loading("#arFilterMonth", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.status);
                    //$window.location.href = 'errors/' + error.status;
                }
        );
    };

    $scope.onSubmitFetchReport = function (valid) {
        if (valid) {
//            angular.forEach($scope.batches, function (value) {
//                if (value.id === $scope.arModel.batch_id) {
//                    $scope.arModel.academic_year_id = value.academic_year_id;
//                }
//            });
            Loading("#emp_attReport_search_filter", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + "attendance/generate_employee_report", $scope.arModel, config).then(
                    function (success) {
                        Loading("#emp_attReport_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.finalReport = success.data.att;
                        //console.log($scope.finalReport);
                    },
                    function (error) {
                        Loading("#emp_attReport_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        console.log(error.data);
                    }
            );
        }
    };

    $scope.getRange = function (total) {
        var range = [];
        for (var i = 1; i <= total; i++) {
            range.push(i);
        }
        $scope.range = range;
    };
});

var customscope;

app2.controller('stdShiftController', function ($scope, $http, $window, $location) {
    customscope = $scope;
    $scope.temp = [];
    $scope.std_count = 0;
    $scope.formModel = {};
    $scope.check = false;
    $scope.std = [];
    $scope.shift_check = true;

    $scope.fetchClassBatches = function (class_id) {
        Loading("#dropdownBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
    if($scope.course == undefined){
        $scope.batches = [];
        
        Loading("#dropdownBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
    }else{
        $http.post(base_url + 'attendance/getClassBatches', {class_id: class_id}, config).then(
                function (success) {
                    Loading("#dropdownBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.batches = success.data;
                    $scope.batch = '';
                },
                function (error) {
                    Loading("#dropdownBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    }
    };

    $scope.fetchAllStdsOfClassAndBatch = function () {
        $scope.temp = [];
        $scope.check = true;
        $scope.formModel.class_id = $scope.course;
        $scope.formModel.batch_id = $scope.batch;
        var table = $('.myTableNew').DataTable({
            "language": {
                "decimal":        "",
                "emptyTable":     '<?php echo lang("no_data_table"); ?>',
                "info":           '<?php echo lang("data_info"); ?>',
                "infoEmpty":      '<?php echo lang("infoempty"); ?>',
                "infoFiltered":   '<?php echo lang("filter_datatable"); ?>',
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     '<?php echo lang("show_datatable"); ?>',
                "loadingRecords": '<?php echo lang("loading_datatable"); ?>',
                "processing":     '<?php echo lang("processing_datatable"); ?>',
                "search":         '<?php echo lang("search"); ?>:',
                "zeroRecords":    '<?php echo lang("no_record_datatable"); ?>',
                "paginate": {
                    "first":      '<?php echo lang("first"); ?>',
                    "last":       '<?php echo lang("last"); ?>',
                    "next":       '<?php echo lang("btn_next"); ?>',
                    "previous":   '<?php echo lang("previous"); ?>'
                },
                "aria": {
                    "sortAscending":  ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                }
            },
         
            dom: 'Bfrtip',
            ajax: {
                type: "POST",
                url: base_url+'students/getShiftStudents',
                data: {'formData':$scope.formModel},
                dataSrc: ''
            },
            "initComplete": function(settings, json){ 
                var info = this.api().page.info();
                $scope.std_count = info.recordsTotal;
                $scope.$apply();
            },
            "order": [[ 0, "asc" ]],
            columns: [
                {title: "", className:"yasir", data: 'misc', render: function(misc){return "<div class='checkbox checkbox-primary'><input type='checkbox' name='checked_std[]' value='"+misc.id+"' id='count"+misc.sr_no+"' /><label for='count"+misc.sr_no+"'></label></div>"; } },
                {title: '<?php echo lang("imp_sr");?>', data: "misc.sr_no"},
                {title: '<?php echo lang("lbl_avatar");?>', data: 'avatar', render: function(avatar){ return "<img src='"+base_url+"uploads/user/"+avatar+"' alt='user-img' class='img-circle' style='height: 60px;width: 60px'>"}},
                {title: '<?php echo lang("lbl_name");?>', data: 'name'},
                {title: '<?php echo lang("lbl_guardian");?>', data: 'father_name'},
                {title: '<?php echo lang("lbl_rollno");?>', data: 'rollno'},
                {title: '<?php echo lang("lbl_teacher");?>', data: 'teacher_name'},
                {title: '<?php echo lang("lbl_action");?>', data:'new_id', render: function(new_id){return "<a type='button' target='_blank' href='students/view/"+new_id+"' class='btn btn-success btn-circle'><i class='fa fa-eye'></i></a>"; }}
            ],
            buttons: [
                /*{
                    text: 'Shift Students',
                    className: 'btn btn-primary',
                    action: function ( e, dt, node, config ) {
                        console.log(node);
                    }
                }*/
            ],
            destroy: true
        });
        //$(this).html( '<input type="button" value="view"/>' );
     
    };

    $scope.populate_modal = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $scope.students = $scope.temp;
        
        $scope.shiftForm.$setPristine();
        $scope.shiftForm.$setUntouched();

        $http.post(base_url + 'students/getNewClasses', {class_id: $scope.formModel.class_id,batch_id:$scope.formModel.batch_id,count:$scope.students.length}, config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.new_classes = success.data.classes;
                    $scope.message = success.data.message;
                    $scope.level_msg = success.data.level_msg;
                    $scope.new_course='';
                    $scope.new_batch='';
                    $scope.new_batches=[];
                    $scope.reason='';
                    //$scope.batch = '';
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );

}
    $scope.fetchNewClassBatches = function(){
    Loading("#newdropdownBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
    if($scope.new_course == undefined){
    $scope.new_course ='';
    $scope.new_batches = [];
    $scope.new_batch = '';
    Loading("#newdropdownBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
}else{
        $http.post(base_url + 'attendance/getClassBatches', {class_id: $scope.new_course}, config).then(
                function (success) {
                    Loading("#newdropdownBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.new_batches = success.data;
                    $scope.new_batches = $scope.new_batches.filter(function( obj ) {
                        return obj.id !== $scope.formModel.batch_id;
                    });
                    $scope.new_batch = '';
                },
                function (error) {
                    Loading("#newdropdownBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );}
    };

    $scope.shiftStudents = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'students/shiftStudents', {class_id:$scope.new_course,batch_id:$scope.new_batch,reason:$scope.reason,students:$scope.students}, config).then(
            function (success) {
            $scope.course =  $scope.formModel.class_id;
            $scope.batch = $scope.formModel.batch_id;
            $scope.fetchAllStdsOfClassAndBatch();
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $("#shiftModal").modal("hide");
                    showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                
            },
            function (error) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };


    
});

app2.controller('academicCtrl', function ($scope, $http, $window, $location) {
    $scope.myModel = {class: true, batch: false, fee: false, subject:false, subject_group:false, period:false, timetable:false, teacher:false};
    $scope.found = false;
    $scope.classes = 0;
    $scope.batches = 0;
    $scope.fee_types = 0;
    $scope.subjects = 0;
    $scope.subject_groups = 0;
    $scope.periods = 0;
    $scope.teachers = 0;
    $scope.get_years = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'settings/get_years', '', config).then(
            function (success) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.years = success.data.years;
                $scope.found = success.data.found;
                $scope.academic_year = success.data.academic_year;
                if($scope.found){
                $scope.classes = success.data.classes;
                $scope.batches = success.data.batches;
                $scope.fee_types = success.data.fee_types;
                $scope.subjects = success.data.subjects;
                $scope.subject_groups = success.data.subject_groups;
                $scope.periods = success.data.periods;
                $scope.timetables = success.data.timetables;
                $scope.teachers = success.data.teachers;
            }
            },
            function (error) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    }
    $scope.shift_data = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'settings/shift_data', $scope.myModel, config).then(
            function (success) {
                $scope.get_years();
                $('#importModal').modal('hide');
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
            },
            function (error) {
            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    }

    $scope.batch_changed = function(){
        if($scope.myModel.batch == false){
            $scope.myModel.subject = false;
            $scope.myModel.subject_group = false;
            $scope.myModel.period = false;
            $scope.myModel.timetable = false;
            $scope.myModel.teacher = false;
        }
    }

    $scope.subject_changed = function(){
        if($scope.myModel.subject == false){
            $scope.myModel.subject_group = false;
            $scope.myModel.timetable = false;
            $scope.myModel.teacher = false;
        }
    }

    $scope.period_changed = function(){
        if($scope.myModel.period == false){
            $scope.myModel.timetable = false;
        }
    }
});


app2.controller('promotionController', function ($scope, $http, $window, $location) {
    $scope.alert = {};
    $scope.alert2 = {};
    $scope.classes = {};
    $scope.classes2 = {};
    $scope.batches = {};
    $scope.batches2 = {};
    $scope.filterModel={};
    $scope.filterModel2={};
    $scope.academicyears = {};
    $scope.academicyears2 = {};
    $scope.subjectgroups = {};
    $scope.students = [];
    $scope.stdArrayFirstKey=0;
    $scope.checkboxarray=[];
    $scope.selected_academic_year = '';
    $scope.initAcademicYears =  function(){
        Loading("#promotionFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'promotion/getAcademicYears', "", config).then(
                function (success) {
                    Loading("#promotionFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.academicyears = success.data;
                },
                function (error) {
                    Loading("#promotionFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
        );
    };
    
    $scope.initAcademicYears2 =  function(){
        Loading("#promotionFilterAcademicYears2", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'promotion/getAcademicYears', "", config).then(
                function (success) {
                    Loading("#promotionFilterAcademicYears2", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.academicyears2 = success.data;
                },
                function (error) {
                    Loading("#promotionFilterAcademicYears2", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
        );
    };
    
    $scope.initClasses = function (academic_year_id) {
        Loading("#promotionFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
        if(academic_year_id) { 
            $http.post(base_url + 'promotion/getClasses', { 'academic_year_id': academic_year_id }, config).then(
                    function (success) {
                        Loading("#promotionFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.classes = success.data;
                    },
                    function (error) {
                        Loading("#promotionFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $window.location.href = 'errors/' + error.status;
                    }
            );
        }
    };
    
    $scope.initActiveAcademicYearClasses = function () {
        Loading("#promotionFilterClasses2", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'promotion/getActiveAcademicYearClasses', { }, config).then(
            function (success) {
                Loading("#promotionFilterClasses2", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.classes2 = success.data;
            },
            function (error) {
                Loading("#promotionFilterClasses2", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $window.location.href = 'errors/' + error.status;
            }
        );
    };
    
    $scope.initBatches = function (class_id, academic_year_id) {
        Loading("#promotionFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (class_id && academic_year_id) {
            Loading("#promotionFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'promotion/getClassBatches', {'class_id': class_id, 'academic_year_id':academic_year_id}, config).then(
                    function (success) {
                        Loading("#promotionFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.batches = success.data;
                        $scope.filterModel.batch_id = "";
                    },
                    function (error) {
                        console.log(error.data);
                        //Loading("#promotionFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        //$window.location.href = 'errors/' + error.status;
                    }
            );
        }
    };
    
    $scope.initBatches2 = function (class_id) {
        Loading("#promotionFilterBatches2", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (class_id) {
            Loading("#promotionFilterBatches2", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'promotion/getActiveAcademicYearClassBatches', {'class_id': class_id}, config).then(
                function (success) {
                    Loading("#promotionFilterBatches2", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.batches2 = success.data;
                },
                function (error) {
                    console.log(error.data);
                    Loading("#promotionFilterBatches2", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    //$window.location.href = 'errors/' + error.status;
                }
            );
        }
    };
    
    $scope.initSubjectGroups = function (academic_year_id, class_id, batch_id) {
        Loading("#promotionFilterSubjectGroups2", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (class_id && batch_id) {
            Loading("#promotionFilterSubjectGroups2", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'promotion/getSubjectgroups', {'batch_id':batch_id, 'class_id': class_id}, config).then(
                function (success) {
                    Loading("#promotionFilterSubjectGroups2", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.subjectgroups = success.data;
                },
                function (error) {
                    console.log(error.data);
                    Loading("#promotionFilterSubjectGroups2", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    //$window.location.href = 'errors/' + error.status;
                }
            );
        }
    };
    
    $scope.onSubmitFilter = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'promotion/getStudents', $scope.filterModel, config).then(
            function (success) {
                $scope.selected_academic_year = $scope.filterModel.academic_year_id;
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                if($("#selectall").is(":checked")){
                    $("#selectall").prop('checked', false);
                }
                angular.forEach($scope.checkboxarray, function (value, key) {
                    $("#checkbox_"+value).prop('checked', false);
                });
                $scope.checkboxarray.splice(0,$scope.checkboxarray.length);
                $scope.students = success.data;
                $scope.stdArrayFirstKey = Object.keys($scope.students)[0];
            },
            function (error) {
                console.log(error.data);
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };
    
    $scope.selectallstudents = function(){
        if($("#selectall").is(":checked")){
            angular.forEach($scope.students, function (value, key) {
                if(!value.is_promoted){
                    $scope.checkboxarray.push(value.student_id);
                    $("#checkbox_"+value.student_id).prop("checked", true);
                }
            });
        } else if(!$("#selectall").is(":checked")){
            angular.forEach($scope.students, function (value, key) {
                if(!value.is_promoted) {
                    $("#checkbox_"+value.student_id).prop("checked", false);
                    var index = $scope.checkboxarray.indexOf(value.student_id);
                    $scope.checkboxarray.splice(index,1);
                }
            });
        }
    };
    
    $scope.set_chechboxarray = function(id){
        if($("#checkbox_"+id).is(":checked")){
            $scope.checkboxarray.push(id);
        }else if(!$("#checkbox_"+id).is(":checked")){
            var index = $scope.checkboxarray.indexOf(id);
            $scope.checkboxarray.splice(index,1);
        }
    };
    
    $scope.resetModal = function(){
        $scope.filterModel2 = {};
        $scope.promotionForm.$setUntouched();
        $scope.promotionForm.$setPristine();
    };
    
    $scope.onSubmitPromotionForm = function(){
        Loading("#promotion-modal-content", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'promotion/promoteStudents', {'filter':$scope.filterModel2, 'students':$scope.checkboxarray, 'selected_academic_year' : $scope.selected_academic_year}, config).then(
            function (success) {
                Loading("#promotion-modal-content", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.alert2 = success.data;
                $scope.resetModal();
                $("#bs-promotion-modal-sm").modal('hide');
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $scope.onSubmitFilter($scope.filterModel);
            },
            function (error) {
                console.log(error.data);
                Loading("#promotion-modal-content", '<?php echo lang("loading_datatable") ?>', "", "hide");
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };
    
    /*$scope.search = function(value){
        if(value != ""){
            
        }
    };*/
    
});
app2.controller('parentviewchildCtrl', function ($scope, $http, $window, $location) {
    $scope.childerns = {};
    $scope.init_parent_children_list = function(){

     $http.get(base_url + 'parents/get_parent_childlist', "", config).then(
            function (success) { 
                console.log(success.data);
                $scope.childerns = success.data.student_ids;
            },
            function (error) {
                console.log(error.data);
                
            }
        );
    };
    



});
app2.controller('dashboardCtrl', function ($scope, $http, $window, $location, $sce) {
    $scope.studyplan = [];
    $scope.selected_class_batch="";
    $scope.selected_department_for_graph = "";
    $scope.selected_class_for_graph = "";
    $scope.employee_attendance = [];
    $scope.student_attendance = [];
    $scope.emp_overall = [];
    $scope.std_overall = [];
    $scope.studyplan_overall = [];
    $scope.calendarEvents = {};

    $scope.get_announcement = function(){
       $http.post(base_url + "dashboard/getAnnouncements", config).then(
                function (success) {
                    if (success.data == "" ){
                        $('#myModal').modal('hide');
                    } 
                    else {
                        $('#myModal').modal('show');
                        $scope.announcement = success.data;
                        for (var i = 0, len = $scope.announcement.length; i < len; i++) {

                          $scope.announcement[i].details = $sce.trustAsHtml($scope.announcement[i].details);

                        }
                    }
                },
                function (error) {
                    console.log(error.data);
                }
        ); 
    };
    
    $scope.get_googledrivestatus = function(){
       
            $http.post(base_url + "dashboard/googledrivestatus", config).then(
                function (success) {
                    if (success.data == "true" )
                    {
                        $("#gd_anouncement").css({"width":"0"});
                    } 
                    else if(success.data == "false")
                    {
                        <!--$("#gd_anouncement").css({"width":"100%"});-->
                    }
                },
                function (error) {
                    console.log(error.data);
                }
            ); 
    };

    $scope.refreshFees = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'fee/refresh', "", config).then(
            function (success) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                if(success.data.success){
                    showNotification('<?php echo lang("success_app") ?>', success.data.msg, "success");
                }  
            },
            function (error) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $window.location.href = 'errors/' + error.status;
            }
        );
    }
    
    $scope.init_academic_wise_emp_att_graph = function(){
        Loading("#morris-area-chart-emp-academic-wise-attendance", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'dashboard/get_emp_academic_wise_graph', "", config).then(
            function (success) {
                Loading("#morris-area-chart-emp-academic-wise-attendance", '<?php echo lang("loading_datatable") ?>', "", "hide");
                Morris.Line({
                    element: 'morris-area-chart-emp-academic-wise-attendance',
                    data: success.data,
                    xkey: 'month',
                    ykeys: ['Present','Late','LeaveStatus','Absent'],
                    labels: ['Present','Late','LeaveStatus','Absent'],
                    xLabels: 'month',
                    parseTime: false,
                    xLabelFormat: function (x) {
                        return x.src.monthyear;
                    },
                    pointSize: 4,
                    fillOpacity: 0,
                    pointStrokeColors: ['#5cb85c','#f0ad4e','#5bc0de','#d9534f'],
                    behaveLikeLine: true,
                    gridLineColor: '#e0e0e0',
                    lineWidth: 3,
                    hideHover: 'auto',
                    hoverCallback: function (index, options, content, row) {
                    return success.data[index].monthyear+"<br>Present "+success.data[index].Present+"<br>Leave "+success.data[index].LeaveStatus+"<br>Absent "+success.data[index].Absent+"<br>Late "+success.data[index].Late;
                    },
                    lineColors: ['#00c292','#fec107','#03a9f3','#fb9678'],
                    resize: true
                });
            },
            function (error) {
                console.log(error.data);
                Loading("#morris-area-chart-emp-academic-wise-attendance", '<?php echo lang("loading_datatable") ?>', "", "hide");
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };
    
    $scope.init_academic_wise_std_att_graph = function(){
        Loading("#morris-area-chart-std-academic-wise-attendance", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'dashboard/get_std_academic_wise_graph', "", config).then(
            function (success) {
                Loading("#morris-area-chart-std-academic-wise-attendance", '<?php echo lang("loading_datatable") ?>', "", "hide");
                
                Morris.Area({
                    element: 'morris-area-chart-std-academic-wise-attendance',
                    data: success.data,
                    xkey: 'month',
                    ykeys: ['Present','Late','LeaveStatus','Absent'],
                    labels: ['Present','Late','LeaveStatus','Absent'],
                    xLabels: 'month',
                    parseTime: false,
                    xLabelFormat: function (x) {
                        return x.src.monthyear;
                    },
                    pointSize: 4,
                    fillOpacity: 0,
                    pointStrokeColors: ['#5cb85c','#f0ad4e','#5bc0de','#d9534f'],
                    behaveLikeLine: true,
                    gridLineColor: '#e0e0e0',
                    lineWidth: 3,
                    hideHover: 'auto',
                    hoverCallback: function (index, options, content, row) {
                        return success.data[index].monthyear+"<br>Present "+success.data[index].Present+"<br>Leave "+success.data[index].LeaveStatus+"<br>Absent "+success.data[index].Absent+"<br>Late "+success.data[index].Late;
                    },
                    lineColors: ['#00c292','#fec107','#03a9f3','#fb9678'],
                    resize: true
                });
            },
            function (error) {
                console.log(error.data);
                Loading("#morris-area-chart-std-academic-wise-attendance", '<?php echo lang("loading_datatable") ?>', "", "hide");
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };
    
    $scope.init_study_plan_statictics = function(){
        Loading("#study-plan-statictics-container", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'dashboard/get_study_plan_statictics', "", config).then(
            function (success) {
                Loading("#study-plan-statictics-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.studyplan = success.data.all;
                $scope.studyplan_overall = success.data.overall;
                $scope.show_studyplan_overall_graph($scope.studyplan_overall);
            },
            function (error) {
                console.log(error.data);
                Loading("#study-plan-statictics-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };

    $scope.init_fee_summary = function(){
        Loading("#fee-summary-container", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'dashboard/get_fee_summary', "", config).then(
            function (success) {
                Loading("#fee-summary-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.fee_data = success.data.classes;
                $scope.overall = success.data.overall;
                $scope.show_fee_graph($scope.overall);
                
                
            },
            function (error) {
                console.log(error.data);
                Loading("#fee-summary-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };
    
    $scope.show_studyplan_indivial_graph = function(obj){
    
        $("#morris-bar-chart").empty();
        Loading("#morris-bar-chart", '<?php echo lang("loading_datatable") ?>', "", "show");
        $scope.selected_class_batch = " "+obj.class_name +" - "+ obj.batch_name;
        var dd = [];
        angular.forEach(obj.subjects, function (val, key) {
            var d = {
                'y':val.name,
                'done':val.syllabus.counts.done.count,
                'pending':val.syllabus.counts.pending.count,
                'skip':val.syllabus.counts.skip.count,
                'partially_done':val.syllabus.counts.partially_done.count,
                'reschedule':val.syllabus.counts.reschedule.count
            };
            dd.push(d);
        });
        // Morris bar chart
        Morris.Bar({
            element: 'morris-bar-chart',
            data:dd,
            xkey: 'y',
            ykeys: ['done', 'pending', 'skip','partially_done','reschedule'],
            labels: ['<?php echo lang("lbl_done"); ?>', '<?php echo lang("lbl_pending"); ?>', '<?php echo lang("lbl_skip"); ?>','<?php echo lang("partially_done"); ?>','<?php echo lang("reschedule"); ?>'],
            barColors: ['#55ce63', '#414755', '#40c4ff','#00cc00','#000000','#CC0000'],
            hideHover: 'auto',
            gridLineColor: '#eef0f2',
            resize: true
        });
        Loading("#morris-bar-chart", '<?php echo lang("loading_datatable") ?>', "", "hide"); 
        
    };
    
    $scope.show_studyplan_overall_graph = function(obj){       
        $("#morris-bar-chart").empty();
        Loading("#morris-bar-chart", '<?php echo lang("loading_datatable") ?>', "", "show");
        $scope.selected_class_batch = "<?php echo lang("lbl_overall") ?>";
        
        

        Morris.Donut({
          element: 'morris-bar-chart',
          data: obj,
          colors: ['#55ce63', '#414755', '#40c4ff','#00cc00','#000000']
        });
        Loading("#morris-bar-chart", '<?php echo lang("loading_datatable") ?>', "", "hide");
    
    };
    
    $scope.show_emp_attendance_individal_graph = function(obj){
        $("#morris-area-chart-emp-attendance").empty();
        Loading("#morris-area-chart-emp-attendance", '<?php echo lang("loading_datatable") ?>', "", "show");
        $scope.selected_department_for_graph = " "+obj.departmentname;
       
        var gData = [];
        if(Object.keys(obj).length === 6){
            gData = [
                {label: "<?php echo lang('lbl_present'); ?>", value: obj.present},
                {label: "<?php echo lang('lbl_absent'); ?>", value: obj.absent},
                {label: "<?php echo lang('lbl_leave'); ?>", value: obj.leav},
                {label: "<?php echo lang('lbl_late'); ?>", value: obj.late},
                {label: "<?php echo lang('lbl_unknown'); ?>", value: obj.unknown}
            ];
        } else {
            gData = [
                {label: "<?php echo lang('lbl_present'); ?>", value: obj.present},
                {label: "<?php echo lang('lbl_absent'); ?>", value: obj.absent},
                {label: "<?php echo lang('lbl_leave'); ?>", value: obj.leav},
                {label: "<?php echo lang('lbl_late'); ?>", value: obj.late}
            ];
        }
        
        Morris.Donut({
          element: 'morris-area-chart-emp-attendance',
          data: gData,
          colors: ['#00c292', '#fb9678', '#03a9f3','#fec107','#A9A9A9']
        });
        Loading("#morris-area-chart-emp-attendance", '<?php echo lang("loading_datatable") ?>', "", "hide");
    };
    
    $scope.show_std_attendance_individal_graph = function(obj){
        $("#morris-area-chart-student-attendance").empty();
        Loading("#morris-area-chart-student-attendance", '<?php echo lang("loading_datatable") ?>', "", "show");
        $scope.selected_class_for_graph = " "+obj.classname;
        
        var gData = [];
        if(Object.keys(obj).length === 6){
            gData = [
                {label: "<?php echo lang('lbl_present'); ?>", value: obj.present},
                {label: "<?php echo lang('lbl_absent'); ?>", value: obj.absent},
                {label: "<?php echo lang('lbl_leave'); ?>", value: obj.leav},
                {label: "<?php echo lang('lbl_late'); ?>", value: obj.late},
                {label: "<?php echo lang('lbl_unknown'); ?>", value: obj.unknown}
            ];
        } else {
            gData = [
                {label: "<?php echo lang('lbl_present'); ?>", value: obj.present},
                {label: "<?php echo lang('lbl_absent'); ?>", value: obj.absent},
                {label: "<?php echo lang('lbl_leave'); ?>", value: obj.leav},
                {label: "<?php echo lang('lbl_late'); ?>", value: obj.late}
            ];
        }
        Morris.Donut({
          element: 'morris-area-chart-student-attendance',
          data: gData,
          colors: ['#00c292', '#fb9678', '#03a9f3','#fec107','#A9A9A9']
        });
        Loading("#morris-area-chart-student-attendance", '<?php echo lang("loading_datatable") ?>', "", "hide");
    };
    
    $scope.init_employee_today_attenance = function(){
        Loading("#today_employee_attendance_table", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'dashboard/get_today_emp_attendance', "", config).then(
            function (success) {
                $scope.employee_attendance = success.data.all;
                $scope.emp_overall = success.data.overall;
                $scope.emp_overall.departmentname = "<?php echo lang("lbl_overall") ?>";
                $scope.show_emp_attendance_individal_graph($scope.emp_overall);
                Loading("#today_employee_attendance_table", '<?php echo lang("loading_datatable") ?>', "", "hide");
            },
            function (error) {
                console.log(error.data);
                Loading("#today_employee_attendance_table", '<?php echo lang("loading_datatable") ?>', "", "hide");
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };
    
    $scope.init_student_today_attenance = function(){
        Loading("#today_student2_attendance_table", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'dashboard/get_today_std_attendance', "", config).then(
            function (success) {
                $scope.student_attendance = success.data.all;
                $scope.std_overall = success.data.overall;
                $scope.std_overall.classname = "<?php echo lang("lbl_overall") ?>";
                $scope.show_std_attendance_individal_graph($scope.std_overall);
                Loading("#today_student2_attendance_table", '<?php echo lang("loading_datatable") ?>', "", "hide");
            },
            function (error) {
                console.log(error.data);
                Loading("#today_student2_attendance_table", '<?php echo lang("loading_datatable") ?>', "", "hide");
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };
    
    $scope.show_fee_graph = function(obj){
        $("#fee-graph").empty();
        Loading("#fee-graph", '<?php echo lang("loading_datatable") ?>', "", "show");
        $scope.selected_class_batch_fee = " "+obj.full_name;
       
        Morris.Donut({
          element: 'fee-graph',
          data: [
            {label: "<?php echo lang('fully_paid'); ?>", value: obj.total_paid},
            {label: "<?php echo lang('partial_paid'); ?>", value: obj.total_partial},
            {label: "<?php echo lang('lbl_due_chart'); ?>", value: obj.total_due}
          ],
          colors: ['#28a745', '#ffc107', '#dc3545']
        });
        Loading("#fee-graph", '<?php echo lang("loading_datatable") ?>', "", "hide");
    };

    $scope.get_Today_Events = function(){

        $http.post(base_url + "dashboard/getTodayEventsDshboard", config).then(
                function (success) {
                    $scope.calendarEvents = success.data;
                    //console.log($scope.calendarEvents);
                    

                    //console.log(success.data);
                },
                function (error) {
                    console.log(error.data);
                }
        );   
    };
    
});

app2.controller("pratController", function ($scope, $http, $window, $location, $sce) {
    $scope.parentchild = {};
    $scope.months = [];
    
    $scope.initMonths = function(){
        Loading("#arFilterMonth", '<?php echo lang("loading_datatable") ?>', "", "hide");
        $http.post(base_url + 'attendance/count_academic_year_months', "", config).then(
                function (success) {
                    Loading("#arFilterMonth", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.months = success.data;
                },
                function (error) {
                    Loading("#arFilterMonth", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.status);
                    //$window.location.href = 'errors/' + error.status;
                }
        );
    };
    
    /*$scope.months = [
        {id: '01', name: 'jan'},
        {id: '02', name: 'feb'},
        {id: '03', name: 'mar'},
        {id: '04', name: 'apr'},
        {id: '05', name: 'may'},
        {id: '06', name: 'jun'},
        {id: '07', name: 'jul'},
        {id: '08', name: 'aug'},
        {id: '09', name: 'sep'},
        {id: '10', name: 'oct'},
        {id: '11', name: 'nov'},
        {id: '12', name: 'dec'}
    ];*/
    $scope.report = {};
    $scope.range = [];
    $scope.finalReport = {};
    $scope.initGetParentChild = function () {
        Loading("#arFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'attendance/getChilds', "", config).then(
                function (success) {
                    Loading("#arFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.parentchild = success.data.student_ids;
                },
                function (error) {
                    Loading("#arFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
        );
    };
    
    $scope.onSubmitFetchReport = function (valid) {
        if (valid) {
            /*angular.forEach($scope.batches, function (value) {
                if (value.id === $scope.arModel.batch_id) {
                    $scope.arModel.academic_year_id = value.academic_year_id;
                }
            });*/
            Loading("#attReport_search_filter", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + "attendance/generate_reportForParent", $scope.arModel, config).then(
                    function (success) {
                        Loading("#attReport_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.finalReport = success.data.att;
                        console.log($scope.finalReport);
                    },
                    function (error) {
                        Loading("#attReport_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        console.log(error.data);
                    }
            );
        }
    };

    $scope.onSubmitFetchStudentReport = function (id) {
        $scope.arModel.id = id;
        
            /*angular.forEach($scope.batches, function (value) {
                if (value.id === $scope.arModel.batch_id) {
                    $scope.arModel.academic_year_id = value.academic_year_id;
                }
            });*/
            Loading("#attReport_search_filter", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + "attendance/generate_reportForStudent", $scope.arModel, config).then(
                    function (success) {
                        Loading("#attReport_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.finalReport = success.data.att;
                        console.log($scope.finalReport);
                    },
                    function (error) {
                        Loading("#attReport_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        console.log(error.data);
                    }
            );
        
    };

    $scope.getRange = function (total) {
        var range = [];
        for (var i = 1; i <= total; i++) {
            range.push(i);
        }
        $scope.range = range;
    };
});

/* Function to Show Time Table to Parent */


app2.controller("parenttimeTableCtrl", function ($scope, $http, $window, $location, $sce) {
    $scope.parentchild = {};
    $scope.classes = {};
    $scope.batches = {};
    $scope.tbModel = {};
    $scope.ttModel = {};
    $scope.etModel = {};
    $scope.crModel = {};
    $scope.timeTable = {};
    $scope.subjects = {};
    $scope.selectedCBSubjects = {};
    $scope.periods = {};
    $scope.error;
    $scope.yModel = {};
    $scope.yyModel = {};

    $scope.getFormatedTime = function (time) {
        var date = new Date(time);
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var ampm = hours >= 12 ? 'pm' : 'am';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0' + minutes : minutes;
        var strTime = hours + ':' + minutes + ' ' + ampm;
        return strTime;
    };

    $scope.getSubjects = function (class_id, batch_id) {
        $http.post(base_url + "timetable/getSubjects", {class_id: class_id, batch_id: batch_id}, config).then(
                function (success) {
                    //console.log(success.data);
                    $scope.selectedCBSubjects = success.data;
                }, function (error) {
            console.log(error.data);
        }
        );
    };


    $scope.initGetParentChild = function () {
        Loading("#tbFilterChild", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'attendance/getChilds', "", config).then(
                function (success) {
                    Loading("#tbFilterChild", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.parentchild = success.data.student_ids;
                },
                function (error) {
                    Loading("#arFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
        );
    };

    $scope.initClasses = function () {
        Loading("#tbFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'attendance/getClasses', "", config).then(
                function (success) {
                    Loading("#tbFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.classes = success.data;
                },
                function (error) {
                    Loading("#tbFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
        );
    };

    

    $scope.initBatches = function (student_id) {
        Loading("#tbFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (student_id) {
            Loading("#tbFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'timetable/getBatchesForParent', {student_id: student_id}, config).then(
                    function (success) {
                        Loading("#tbFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.batches = success.data.batches;
                        $scope.tbModel.batch_id = "";
                    },
                    function (error) {
                        Loading("#tbFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $window.location.href = 'errors/' + error.status;
                    }
            );
        }
    };

    $scope.fetchSubjects = function (valid) {
        if (valid) {
            Loading("#timetable_search_filter", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'timetable/getSubjectsWiseTimeTableForParent', {student_id: $scope.tbModel.student_id, batch_id: $scope.tbModel.batch_id}, config).then(
                    function (success) {
                        Loading("#timetable_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        if (success.data.status === "success") {
                            $scope.timeTable = success.data.timetables;
                            $scope.periods = success.data.periods;
                            $scope.error = false;
                        } else if (success.data.status === "error") {
                            $scope.error = true;
                            $scope.error = success.data;
                        }
                    },
                    function (error) {
                        Loading("#timetable_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        console.log(error.data);
                        //$window.location.href = 'errors/' + error.status;
                    }
            );
        }
    };
    $scope.fetchStudentTimetable = function (id) {
        
            Loading("#timetable_search_filter", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'timetable/getSubjectsWiseTimeTableForStudent', {student_id: id, batch_id: $scope.tbModel.batch_id}, config).then(
                    function (success) {
                        Loading("#timetable_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        if (success.data.status === "success") {
                            $scope.timeTable = success.data.timetables;
                            $scope.periods = success.data.periods;
                            $scope.error = false;
                        } else if (success.data.status === "error") {
                            $scope.error = true;
                            $scope.error = success.data;
                        }
                    },
                    function (error) {
                        Loading("#timetable_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        console.log(error.data);
                        //$window.location.href = 'errors/' + error.status;
                    }
            );
        
    };

    $scope.selectedValues = function (obj) {
        $scope.crModel = obj;
        $scope.getSubjects($scope.crModel.class_id, $scope.crModel.batch_id);
    };

    $scope.selectedValues2 = function (obj) {
        $scope.editTimetableForm.$setUntouched();
        $scope.editTimetableForm.$setPristine();
        $scope.etModel = obj;
        $scope.etModel.new_room_no = obj.room_no;
        $scope.getSubjects($scope.etModel.class_id, $scope.etModel.batch_id);
    };

    

   
});

/* End Function to Show Time Table to Parent */

/* function for fee collection for parent */

app2.controller("feeCollectionparentController", function ($scope, $http, $window, $filter) {
    $scope.parentchild = {};
    $scope.classes = {};
    $scope.batches = {};
    $scope.fModel = {};     //add new fee collection model
    $scope.fcModel = {};     //fee collection (fc)
    $scope.afcModel = {};     //add fee collection (afc)
    $scope.feeCollectionStudents = [];
    $scope.selectedStd = {};
    $scope.stdFeeRecords = {};
    $scope.feetypes = {};
    $scope.today = $filter('date')(new Date(), 'dd/MM/yyyy');
    $scope.mode = 'cash';
    $scope.isSendEmailToParent = false;
    $scope.isSendEmailToParentEdit = false;
    $scope.isSendSMSToParent = false;
    $scope.isSendSMSToParentEdit = false;
    $scope.parent_id = 0;
    $scope.selectedSpecificFeetype = '<?php echo lang("all_fee_types");?>';
    $scope.fcModel.selectedFeetype = 'all';
    $scope.loading = false;
    $scope.update_loading = false;
    $scope.paid_amount = '';
    $scope.partiallyFeeDetailModel = [];
    $scope.maxEditPaidAmount=0;
    $scope.academicyears = {};
    $scope.notes = [];
    $scope.notesLength = 0;
    $scope.totalSum = {};
    
    $scope.initAcademicYears =  function(){
        Loading("#feeFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'fee/getAcademicYearsParentFee', "", config).then(
            function (success) {
                Loading("#feeFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.fcModel.academic_year_id = success.data.current_academic_year_id;
                $scope.initClasses(success.data.current_academic_year_id);
                $scope.academicyears = success.data.data;
                $scope.fcModel.class_id = "all";
                $scope.fcModel.batch_id = "all";
                $scope.initGetParentChild(success.data.current_academic_year_id);
            },
            function (error) {
                Loading("#feeFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $window.location.href = 'errors/' + error.status;
            }
        );
    };

    $scope.initGetParentChild = function (academic_year_id) {
        if(academic_year_id) {
        Loading("#feeFilterChilds", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'fee/getChilds', { 'academic_year_id': academic_year_id }, config).then(
                function (success) {
                    Loading("#feeFilterChilds", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.parentchild = success.data.student_ids;
                    $scope.fcModel.student_id = "";

                },
                function (error) {
                    Loading("#feeFilterChilds", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
            );
        }
    };
    
    $scope.initClasses = function (academic_year_id) {
        if(academic_year_id) { 
            Loading("#feeFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'fee/getClasses', { 'academic_year_id': academic_year_id }, config).then(
                function (success) {
                    Loading("#feeFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.classes = success.data;
                    $scope.fcModel.class_id = "all";
                },
                function (error) {
                    Loading("#feeFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
            );
        }
    };
    $scope.initBatches = function (student_id,academic_year_id) {
        
        Loading("#feeFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (student_id && academic_year_id) {
            Loading("#feeFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'fee/getBatchesForParent', {'student_id':student_id, 'academic_year_id':academic_year_id}, config).then(
                    function (success) {
                        Loading("#feeFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.batches = success.data.batches;
                        $scope.fcModel.batch_id = "";
                        
                    },
                    function (error) {
                        console.log(error.data);
                        //Loading("#dfBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        //$window.location.href = 'errors/' + error.status;
                    }
            );
            
            
        }
    };
   
    
    $scope.initFeetypes = function(){
        $http.post(base_url + 'fee/getAllFeetypes', "", config).then(
            function (success) {
                $scope.feetypes = success.data;
            }, function (error){
                console.log(error.data);
            }
        );
    };

    $scope.fetchFeeCollections = function (valid) {
        if (valid) {
            var formData = {
                academic_year_id: $scope.fcModel.academic_year_id,
                class_id: $scope.fcModel.class_id ? $scope.fcModel.class_id : null,
                student_id: $scope.fcModel.student_id, 
                batch_id: $scope.fcModel.batch_id, 
                isDue: $scope.fcModel.isDue ? $scope.fcModel.isDue : 0, 
                specificFeeType: $scope.fcModel.selectedFeetype
            };
            Loading("#feeCollection_search_filter", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + "fee/fetchfeeCollectionforParents", formData, config).then(
                    function (success) {
                        $("#feeCollectionContainer1").removeClass("hidden");
                        $(".feeCollectionContainer2").addClass("hidden");
                        Loading("#feeCollection_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.feeCollectionStudents = success.data;
                    },
                    function (error) {
                        Loading("#feeCollection_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        console.log(error.data);
                    }
            );
        }
    };

    $scope.fetchFeeCollectionsStudent = function (id) {
        
        var formData = {
            academic_year_id: $scope.fcModel.academic_year_id,
            class_id: $scope.fcModel.class_id ? $scope.fcModel.class_id : null,
            student_id: id, 
            batch_id: $scope.fcModel.batch_id, 
            isDue: $scope.fcModel.isDue ? $scope.fcModel.isDue : 0, 
            specificFeeType: $scope.fcModel.selectedFeetype
        };
        
        Loading("#feeCollection_search_filter", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + "fee/fetchfeeCollectionforStudent", formData, config).then(
                function (success) {
                    $("#feeCollectionContainer1").removeClass("hidden");
                    $(".feeCollectionContainer2").addClass("hidden");
                    Loading("#feeCollection_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.feeCollectionStudents = success.data;
                },
                function (error) {
                    Loading("#feeCollection_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    
};

    $scope.showDetails = function (std) {
        $("#feeCollectionContainer1").addClass("hidden");
        $(".feeCollectionContainer2").removeClass("hidden");
        $scope.selectedStd = std;
        $scope.fetchStudentFeeRecords($scope.selectedStd);
        $('body').tooltip({
            selector: '[rel=tooltip]'
        });
    };

    $scope.back = function () {
        $(".feeCollectionContainer2").addClass("hidden");
        $("#feeCollectionContainer1").removeClass("hidden");
        $scope.fetchFeeCollections(true);
    };

    $scope.fetchStudentFeeRecords = function (obj) {
        var formData = {
            std_id: obj.id, 
            class_id: obj.class_id, 
            school_id: obj.school_id, 
            discount_id: obj.discount_id, 
            discount_amount: obj.discount_amount,
            academic_year_id: $scope.fcModel.academic_year_id ? $scope.fcModel.academic_year_id : 0
        };
        $http.post(base_url + "fee/getStudentFeeRecrodsForParent", formData, config).then(
            function (success) {
                $scope.totalSum.fee = 0;
                $scope.totalSum.paid = 0;
                $scope.totalSum.balance = 0;
                $scope.totalSum.discounted = 0;
                angular.forEach(success.data.records, function (value, key) {
                    if(value[0].fee_collection_id == 'NULL' && (value[0].discount == 100 || value[0].discounted_amount == 0)){
                        $scope.setAddFeeCollectionModel(value[0]);
                        $scope.paid_amount = 0;
                        $scope.payfeeautomatically(true);
                    }


                    $scope.totalSum.discounted += parseInt(value[0].discounted_amount);
                    $scope.totalSum.fee += parseInt(value[0].amount);
                    $scope.totalSum.balance += parseFloat($scope.calculateBalance(value));
                    angular.forEach(value,function(v,k){
                         if(v.fee_collection_id != 'NULL'){
                         $scope.totalSum.paid += parseFloat(v.paid_amount);
                     }
                })


                });
                $scope.stdFeeRecords = success.data.records;
                $scope.notes = success.data.notes;
                $scope.notesLength = Object.keys($scope.notes).length;
            },
            function (error) {
                console.log(error.data);
            }
        );
    };

    $scope.showPartiallyFeeDeatils = function(obj){
        $('#myModalYasir').modal('show');
        $scope.partiallyFeeDetailModel = obj;
    };
    
    $scope.calculateStatus = function(objArray){
        var status = 0;
        for(i=0; i < objArray.length; i++) {
            if(parseInt(objArray[i].status) === 1){
                status = 1;
                break;
            } else if(parseInt(objArray[i].status) === 2){
                status = 2;
            }
        }
        return status;
    };
    
    $scope.countPaidAmount = function (obj){
        var paid_amount = 0;
        if(obj.length == 1){
            paid_amount = parseInt(obj[0].paid_amount);
        } else {
            angular.forEach(obj, function (value, key) {
                paid_amount = parseInt(paid_amount) + parseInt(value.paid_amount);
            });
        }
        return paid_amount;
    };
    
    $scope.calculateBalance = function (obj){
        var balance = 0;
        if(obj.length == 1){
            if(obj[0].fee_collection_id == 'NULL'){
                balance = 0 - obj[0].discounted_amount;
            }else{
            balance =  obj[0].paid_amount - parseFloat(obj[0].feetype_amount - obj[0].feetype_amount*obj[0].discount/100);
        }
            
        } else {
            var paid_amount = 0;
            angular.forEach(obj, function (value, key) {
                paid_amount = paid_amount + parseFloat(value.paid_amount);
            });
            balance = paid_amount - parseFloat(obj[0].feetype_amount - obj[0].feetype_amount*obj[0].discount/100);
        }
        return balance;
    };
    
    $scope.isAllFeePaid = function(obj){
        var is_all_paid = false;
        angular.forEach(obj, function (value, key) {
            if(value.status === "1"){
                is_all_paid = true;
            }
        });
        return is_all_paid;
    };
    
    $scope.calculatePaidFeePercentage = function (obj){
        var percentage = 0;
        if(obj.length == 1){
            if(obj[0].discount == 100 || obj[0].discounted_amount == 0){
                percentage = 100;
            } else {
                percentage = (obj[0].paid_amount * 100) / (obj[0].amount-(obj[0].amount*(obj[0].discount/100)));
            }
        } else {
            var paid_amount = 0;
            angular.forEach(obj, function (value, key) {
                paid_amount = paid_amount + parseInt(value.paid_amount);
            });
            percentage = (paid_amount * 100) / (obj[0].amount-(obj[0].amount*(obj[0].discount/100)));
        }
        return percentage;
    };
    
    $scope.payfeeautomatically = function(valid){
        var fee_name = $scope.afcModel.feetype;
        $scope.loading = true;
        if (valid) {
            $scope.afcModel.paid_amount = $scope.paid_amount;
            $scope.afcModel.mode = $scope.mode;
            $http.post(base_url + "fee/collectFee", {'obj': $scope.afcModel, 'class_id':$scope.selectedStd.class_id, 'batch_id':$scope.selectedStd.batch_id, 'is_send_email':$scope.isSendEmailToParent}, config).then(
                function (success) {
                    if (success.data.status === "success") {
                        $scope.loading = false;
                        $scope.mode = 'cash';
                        $scope.paid_amount = "";
                        $scope.fetchStudentFeeRecords($scope.selectedStd);
                        showNotification('<?php echo lang("lbl_info"); ?>', fee_name + ' <?php echo lang("lbl_fee_auto_paid_due_to_full_discount"); ?>', "info");
                    }
                },
                function (error) {
                    $scope.loading = false;
                    console.log(error.data);
                }
            );
        }
    };
    
    $scope.setAddFeeCollectionModel = function (obj) {
        $scope.loading = false;
        $scope.afcModel = obj;
        $scope.afcModel.student_id = $scope.selectedStd.id;
        $scope.afcModel.created_at = $scope.today;
    };
    
    $scope.setEditFeeCollectionModel = function (obj) {
        $scope.maxEditPaidAmount = 0;
        $scope.editModel = obj[0];
        var total_paid_amount = 0;
        angular.forEach(obj, function (value, key) {
            total_paid_amount += parseFloat(value.paid_amount);
        });
        var discounted_amount = obj[0].feetype_amount - obj[0].discount;
        $scope.editPaidAmount = discounted_amount - total_paid_amount;
        $scope.maxEditPaidAmount = $scope.editPaidAmount;
        $scope.editModel.student_id = $scope.selectedStd.id;
    };
    
    $scope.setAddFeeCollectionModel = function (obj) {
        $scope.loading = false;
        $scope.afcModel = obj;
        $scope.afcModel.student_id = $scope.selectedStd.id;
        $scope.afcModel.created_at = $scope.today;
    };

    $scope.collectFee = function (valid) {
        $scope.loading = true;
        if (valid) {
            $scope.afcModel.paid_amount = $scope.paid_amount;
            $scope.afcModel.mode = $scope.mode;
            $http.post(base_url + "fee/collectFee", {'obj': $scope.afcModel,'class_id':$scope.selectedStd.class_id, 'batch_id':$scope.selectedStd.batch_id, 'is_send_email':$scope.isSendEmailToParent}, config).then(
                function (success) {
                    if (success.data.status === "success") {
                        $scope.loading = false;
                        $('#feeCollectionAddModel').modal('hide');
                        $scope.mode = 'cash';
                        $scope.paid_amount = "";
                        $scope.feeCollectionAddModelForm.$setUntouched();
                        $scope.feeCollectionAddModelForm.$setPristine();
                        $scope.fetchStudentFeeRecords($scope.selectedStd);
                        showNotification(success.data.status, success.data.message, success.data.status);
                    }
                },
                function (error) {
                    $scope.loading = false;
                    console.log(error.data);
                }
            );
        }
    };
    
    $scope.collectFeeUpdate = function (valid) {
        $scope.update_loading = true;
        if (valid) {
            $scope.editModel.is_send_email = $scope.isSendEmailToParentEdit;
            $scope.editModel.is_send_sms = $scope.isSendSMSToParentEdit;
            $scope.editModel.paid_amount = $scope.editPaidAmount;
            $http.post(base_url + "fee/collectFee", {'obj':$scope.editModel,'class_id':$scope.selectedStd.class_id, 'batch_id':$scope.selectedStd.batch_id}, config).then(
                    function (success) {
                        if (success.data.status === "success") {
                            $scope.update_loading = false;
                            $('#feeCollectionEditModel').modal('hide');
                            $scope.fetchStudentFeeRecords($scope.selectedStd);
                            showNotification(success.data.status, success.data.message, success.data.status);
                        }
                    },
                    function (error) {
                        $scope.update_loading = false;
                        console.log(error.data);
                    }
            );
        }
    };
    
    $scope.setSpecificFeetype = function(feetype){
        $scope.fcModel.selectedFeetype = feetype.id;
        $scope.selectedSpecificFeetype = feetype.name;
    };
    
    $scope.showConfirmationAlert = function (obj, how_many) {
        var ids = [];
        if(how_many === 'all'){
            angular.forEach(obj, function (value, key) {
                ids.push(value.fee_collection_id);
            });
        } else if(how_many === 'null'){
            ids.push(obj.fee_collection_id);
        }
        
        swal({
            title: '<?php echo lang("are_you_sure") ?>',
            text: '<?php echo lang("delete_message") ?>',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55", confirmButtonText: '<?php echo lang("yes_delete") ?>',
            cancelButtonText: '<?php echo lang("btn_cancel") ?>',
            closeOnConfirm: true,
            closeOnCancel: true
        },
        function (isConfirm) {
            if (isConfirm) {
            $http.post(base_url + "fee/sofeDeleteCollectedFee", {"ids": ids, 'type':how_many}, config).then(
                    function (success) {
                        if (success.data.status === "success") {
                            $("#myModalYasir").modal('hide');
                            $scope.showDetails($scope.selectedStd);
                        }
                    },
                    function (error) {
                        console.log(error.data);
                    }
                );
            }
        });
    };
    
});


    /* End function for fee collection for parent */

    app2.controller("majorSheetParentsController", function ($scope, $http, $window, $location, $sce) {
    $scope.parentchild = {};
    $scope.classes = {};
    $scope.batches = {};
    $scope.subjects = {};
    $scope.exams = {};
    $scope.students = [];
    $scope.overalltotal = 0;
    $scope.remarksModel = {};
    $scope.showModel = {};
    $scope.positions = [];
    $scope.status = "";
    $scope.message_from_server = "";
    $scope.selected_student_for_teacher_remarks = {};
    $scope.total_obtained_marks_array = [];
    $scope.student_ids = [];
    $scope.exam_ids = [];
    $scope.class_ids = [];
    $scope.batch_ids = [];
    $scope.multi_result_cards = [];
    $scope.selectedClassName = "";
    $scope.selectedBatchName = "";
    $scope.selectedExamName = "";
    $scope.students_shift_wise = [];
    $scope.academicyears = {};
    $scope.filterModel = {};
    $scope.academic_year_id = "";
    

    $scope.loadEditData = function(std){
        $scope.editMarksForm.$setUntouched();
        $scope.editMarksForm.$setPristine();
        angular.forEach(std.subjects, function (value) {
            if(value.exams[0].obtained_marks == null){
                    value.exams[0].new_marks = '';
            }else {
                value.exams[0].obtained_marks = parseInt(value.exams[0].obtained_marks);
                value.exams[0].new_marks = value.exams[0].obtained_marks;
            }
            if(value.exams[0].remarks == null){
                    value.exams[0].new_remarks = '';
            }else {
                value.exams[0].remarks = value.exams[0].remarks;
                value.exams[0].new_remarks = value.exams[0].remarks;
            }
        });

        if(std.grouped_subjects != null){
        var total_subjects = std.grouped_subjects.split(",");
            angular.forEach(std.subjects, function (value) {
                if(total_subjects.indexOf(value.subject_id) != -1){
                    value.is_subject = true;
                }else {
                    value.is_subject = false;
                }
            });
        }else{
        angular.forEach(std.subjects, function (value) {
                    value.is_subject = true;      
            });
        }
        $scope.editData = std;
    }
    
    $scope.editsaveMarksheet = function(valid){
        if(valid){
            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'examination/update_marks', $scope.editData, config).then(
                function (success) {
                    $('#editMarks').modal('toggle');
                    $scope.onSubmit(true);
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                }
        );
    }
        
    };

    $scope.loadDeleteData = function (std){
        $scope.deleteModel = std;
        $scope.deleteModel.exam_id = $scope.deleteExamId;
    }

    $scope.deleteMarks = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'examination/delete_marks', $scope.deleteModel, config).then(
                function (success) {
                    $('#deleteMarks').modal('toggle');
                    $scope.onSubmit(true);
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                }
        );
}

    $scope.majorsheetPrint = function(id, logo, name, dirr){
        var d = '<div style='+ dirr +'>'+
            '<p style="text-align:center;"><img src="<?php echo base_url(); ?>uploads/logos/'+logo+'" width="150px"/></p>'+
            '<h3 style="text-align:center;"><b>'+name+'</b></h3>'+
            '<table style="width:100%; border-spacing: 5px; margin-bottom: 10px; border-collapse: separate;">'+
            '<tr><td><strong><?php echo lang("class_name"); ?></strong></td><td><u>'+$scope.selectedClassName+'</u></td>'+
            '<td><strong><?php echo lang("lbl_batch"); ?></strong></td><td><u>'+$scope.selectedBatchName+'</u></td>'+
            '</tr><tr><td><strong><?php echo lang("lbl_exam_session"); ?></strong></td><td><u>'+$scope.selectedExamName+'</u></td></tr>'+
            '</table>'+
        '</div>';
    
    
        $("#" + id).print({
            globalStyles: false,
            mediaPrint: false,
            stylesheet: "<?php echo base_url(); ?>assets/css/custom-majorsheet.css",
            noPrintSelector: ".no-print",
            iframe: true,
            append: null,
            prepend: d,
            manuallyCopyFormValues: true,
            deferred: $.Deferred(),
            timeout: 750,
            title: null,
            doctype: '<!doctype html>'
        });
    };
    
    $scope.is_all_subject_marks_added = function(subjects){
        var result = true;
        angular.forEach(subjects, function (value) {
            if(value.exams[0].obtained_marks != null) {
                result = false;
            }
        });
        return result;
    };
    
    $scope.closeNav = function (){
        $("#myNav").css({"width":"0"});
    };
    
$scope.initGetParentChild = function (academic_year_id) {
     if(academic_year_id) {
        Loading("#majorSheetFilterChild", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'fee/getChilds', {'academic_year_id': academic_year_id }, config).then(
                function (success) {
                    Loading("#majorSheetFilterChild", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.parentchild = success.data.student_ids;
                },
                function (error) {
                    Loading("#majorSheetFilterChild", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
            );
        }
    };

    $scope.initAcademicYears =  function(){
        Loading("#majorSheetFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'examination/getAcademicYears', "", config).then(
            function (success) {
                Loading("#majorSheetFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.filterModel.academic_year_id = success.data.current_academic_year_id;
                $scope.academic_year_id = success.data.current_academic_year_id;
                $scope.initClasses(success.data.current_academic_year_id);
                $scope.initExams(success.data.current_academic_year_id);
                $scope.academicyears = success.data.data;
                $scope.filterModel.class_id = "";
                $scope.filterModel.batch_id = "";
                $scope.initGetParentChild(success.data.current_academic_year_id);
            },
            function (error) {
                Loading("#majorSheetFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $window.location.href = 'errors/' + error.status;
            }
        );
    };
    
    $scope.initClasses = function (academic_year_id) {
        if(academic_year_id) {    
            Loading("#majorSheetFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'examination/getClasses', { 'academic_year_id': academic_year_id }, config).then(
                function (success) {
                    Loading("#majorSheetFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.classes = success.data;
                    //sconsole.log(success.data);
                },
                function (error) {
                    Loading("#majorSheetFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
            );
        }
    };
    
    $scope.initBatches = function (student_id) {
        if (student_id) {
            Loading("#majorSheetFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'examination/getBatchesForParent', {'student_id': student_id}, config).then(
                function (success) {
                    Loading("#majorSheetFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.batches = success.data.batches;
                    $scope.filterModel.batch_id = "";
                },
                function (error) {
                    console.log(error.data);
                    Loading("#majorSheetFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
            );
        }
    };
    
    $scope.initExams = function (academic_year_id) {
        if(academic_year_id) {
            Loading("#majorSheetFilterExams", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'examination/getSchoolExamsForParent',{academic_year_id:academic_year_id}, config).then(
                function (success) {
                    Loading("#majorSheetFilterExams", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.exams = success.data;
                },
                function (error) {
                    console.log(error.data);
                }
            );
        }
    };
    
    $scope.onSubmit = function(valid){
        if(valid){
            $scope.students = [];
            $scope.students_shift_wise = [];
            $scope.student_ids = [];
            $scope.class_ids = [];
            $scope.batch_ids = [];
            $scope.exam_ids = [];
            Loading("#majorSheetFilterForm", '<?php echo lang("loading_datatable") ?>', "", "show");
            $scope.deleteExamId = $scope.filterModel.exam_id;
            console.log($scope.academic_year_id);
            console.log($scope.student_id);
            console.log($scope.exam_id);
            $http.post(base_url + 'examination/getStudentsForMajorSheetForParent', {academic_year_id: $scope.academic_year_id, student_id: $scope.student_id, exam_id: $scope.exam_id}, config).then(
                function (success) {
                    Loading("#majorSheetFilterForm", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.overalltotal = success.data.exam_total_marks;
                    $scope.students = success.data.data;
                    $scope.status = success.data.status;
                    $scope.message_from_server = success.data.message;
                    
                    angular.forEach($scope.students, function (value) {
                        $scope.student_ids.push(value.student_id);
                        $scope.class_ids.push(value.class_id);
                        $scope.batch_ids.push(value.batch_id);
                        $scope.exam_ids.push(value.subjects[0].exams[0].exam_id);
                    });
                    
                    angular.forEach($scope.students, function (value, key) {
                            if(value.is_shifted == 0){
                                $scope.students_shift_wise.push(value);
                            }
                    });
                    
                    angular.forEach($scope.classes, function (value) {
                        if(value.id == $scope.filterModel.class_id){
                            $scope.selectedClassName = value.name;
                        }
                    });
                    angular.forEach($scope.batches, function (value) {
                        if(value.id == $scope.filterModel.batch_id){
                            $scope.selectedBatchName = value.name;
                        }
                    });
                    angular.forEach($scope.exams, function (value) {
                        if(value.id == $scope.filterModel.exam_id){
                            $scope.selectedExamName = value.title;
                        }
                    });
                    
                    $('body').tooltip({
                        selector: '[rel=tooltip]'
                    });
                },
                function (error) {
                    Loading("#majorSheetFilterForm", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
            );
        }
    };
    
    
    $scope.showAllResultCards = function(student_id,academic_year_id,exam_id){
        console.log(academic_year_id);
        $scope.multi_result_cards = [];
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $("#myNav").css({"width":"100%"});
        $http.post(base_url + 'forms/print_result_cards_forParent',{"student_id":student_id,"academic_year_id":academic_year_id,"exam_id":exam_id}, config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    
                    angular.forEach(success.data, function (value) {
                        $scope.multi_result_cards.push($sce.trustAsHtml(value));
                    });
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error);
                    //$window.location.href = 'errors/' + error.status;
                }
        );
    };
    
    
    $scope.setSelectedStudentForRemarks = function(id, exam_id){
        $scope.remarksModel = {};
        $scope.teacherRemarksForm.$setUntouched();
        $scope.teacherRemarksForm.$setPristine();
        $scope.selected_student_for_teacher_remarks.student_id = id;
        $scope.selected_student_for_teacher_remarks.exam_id = exam_id;
    };
    
    $scope.saveTeacherRemarks = function(){
        $scope.remarksModel.student_id = $scope.selected_student_for_teacher_remarks.student_id;
        $scope.remarksModel.exam_id = $scope.selected_student_for_teacher_remarks.exam_id;
        Loading("#bs-teacher-remarks-modal-sm", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'examination/save_teacher_remarks', $scope.remarksModel, config).then(
            function (success) {
                Loading("#bs-teacher-remarks-modal-sm", '<?php echo lang("loading_datatable") ?>', "", "hide");
                if(success.data.status == "success"){
                    $("#bs-teacher-remarks-modal-sm").modal("hide");
                    showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                    $scope.onSubmit(true);
                }if(success.data.status == "danger"){
                    showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                }
            },
            function (error) {
                Loading("#bs-teacher-remarks-modal-sm", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };
    
    $scope.showTeacherRemark = function(remark, id){
        $scope.showModel.remark = remark;
        $scope.showModel.id = id;
    };
    
    $scope.updateTeacherRemarks = function(){
        Loading("#bs-update-teacher-remarks-modal-sm", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'examination/update_teacher_remarks', $scope.showModel, config).then(
            function (success) {
                Loading("#bs-update-teacher-remarks-modal-sm", '<?php echo lang("loading_datatable") ?>', "", "hide");
                if(success.data.status == "success"){
                    $("#bs-update-teacher-remarks-modal-sm").modal("hide");
                    showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                    $scope.onSubmit(true);
                }if(success.data.status == "danger"){
                    showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                }
            },
            function (error) {
                Loading("#bs-update-teacher-remarks-modal-sm", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };
});

 /* View Study Plan For Parent */

app2.controller("syllabusParentViewController", function ($scope, $http, $window, $location, $filter) {
    $scope.parentchild ={};
    $scope.filterModel = {};
    $scope.classes = {};
    $scope.batches = {};
    $scope.subjects = {};
    $scope.weeks = {};
    $scope.addWeekModel = {};
    $scope.addCommentModel = {};
    $scope.addWeekDetailModel = {};
    $scope.editWeekDetailModel = {};
    $scope.editWeekModel = {};
    $scope.schoolWorkingDays = {};
    $scope.weeklySyllabus = {};
    $scope.workingDays = {};
    $scope.requestId="";
    $scope.requestStatus;
    $scope.syllabusCanEdit;
    $scope.isClick = false;
    $scope.adminIDs = [];
    $scope.confirmDoneId = '';
    $scope.ccModel = {};
    $scope.cModelClasses = [];
    $scope.cModelBatches = [];
    $scope.cModelSubjects = [];
    $scope.cModelWeeks = [];
    $scope.cModelWeekDetails = [];
    $scope.what_to_copy = {};
    
    $scope.initClasses = function () {
        Loading("#syllabusFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'attendance/getClasses', "", config).then(
            function (success) {
                Loading("#syllabusFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.classes = success.data;
                //sconsole.log(success.data);
            },
            function (error) {
                Loading("#syllabusFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.status);
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };


    $scope.initGetParentChild = function () {
       
        Loading("#syllabusFilterChilds", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'syllabus/getChilds', "", config).then(
                function (success) {
                    Loading("#syllabusFilterChilds", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.parentchild = success.data.student_ids;
                    

                },
                function (error) {
                    Loading("#syllabusFilterChilds", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
            );
        
    };
    
    $scope.initBatches = function (student_id) {
        
        Loading("#syllabusFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (student_id) {
            Loading("#syllabusFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'syllabus/getBatchesForParent', {'student_id':student_id}, config).then(
                    function (success) {
                        Loading("#syllabusFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.batches = success.data.batches;
                        $scope.fcModel.batch_id = "";
                        
                    },
                    function (error) {
                        console.log(error.data);
                        
                    }
            );
            
            
        }
    };
    
   
    $scope.initSubjects = function (student_id) {
        Loading("#syllabusFilterSubjects", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (student_id) {
            Loading("#syllabusFilterSubjects", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'syllabus/getSubjectsForParent', {student_id: student_id}, config).then(
                function (success) {
                    Loading("#syllabusFilterSubjects", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.subjects = success.data;
                    $scope.filterModel.subject_id = "";
                },
                function (error) {
                    console.log(error.data);
                }
            );
        }
    };
    
    $scope.initClasses2 = function () {
        Loading("#cModelClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'attendance/getClasses', "", config).then(
            function (success) {
                Loading("#cModelClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.cModelClasses = success.data;
                //sconsole.log(success.data);
            },
            function (error) {
                Loading("#cModelClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.status);
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };
    
    $scope.initBatches2 = function (class_id) {
        Loading("#cModelBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (class_id) {
            Loading("#cModelBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'attendance/getClassBatches', {class_id: class_id}, config).then(
                function (success) {
                    Loading("#cModelBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.cModelBatches = [];
                    angular.forEach(success.data, function(val, key) {
                        if(val.id !== $scope.filterModel.batch_id){
                            $scope.cModelBatches.push(val);
                        }
                    });
                    $scope.ccModel.batch_id = "";
                },
                function (error) {
                    console.log(error.data);
                }
            );
        }
    };
    
    $scope.initSubjects2 = function (class_id, batch_id) {
        Loading("#cModelSubjects", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (class_id && batch_id) {
            Loading("#cModelSubjects", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'syllabus/getSubjects', {class_id: class_id, batch_id:batch_id}, config).then(
                function (success) {
                    Loading("#cModelSubjects", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.cModelSubjects = success.data;
                    $scope.ccModel.subject_id = "";
                },
                function (error) {
                    console.log(error.data);
                }
            );
        }
    };
    
    $scope.initCopyData = function(data){
        $scope.copyWeekForm.$setUntouched();
        $scope.copyWeekForm.$setPristine();
        $scope.ccModel = {};
        $scope.what_to_copy = data;
    };
    
    $scope.saveCopiedWeek = function(){
        Loading("#save-copied-week-model-content", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'syllabus/copySyllabus', {what:$scope.what_to_copy, 'where':$scope.ccModel}, config).then(
            function (success) {
                Loading("#save-copied-week-model-content", '<?php echo lang("loading_datatable") ?>', "", "hide");
                if(success.data.status == 'success'){
                    $("#copyWeekModal").modal("hide");
                    showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                    $scope.onSubmit();
                } else if(success.data.status == 'error'){
                    showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                }
                //console.log(success.data);
            },
            function (error) {
                Loading("#save-copied-week-model-content", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };
    
    $scope.onSubmit = function(){
        $scope.isClick = true;
        Loading("#syllabus_search_filter", '<?php echo lang("loading_datatable") ?>', "", "show");
        $scope.filterModel.type = 'syllabus';
        $http.post(base_url + 'syllabus/getSyllabusForParent', $scope.filterModel, config).then(
            function (success) {
                Loading("#syllabus_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                //console.log(success.data);
                $scope.weeklySyllabus = success.data.syllabus;
                $scope.syllabusCanEdit = success.data.can_syllabus_edit;
                $scope.requestId = success.data.request_id;
                $scope.requestStatus = success.data.reqeust_status;
            },
            function (error) {
                Loading("#syllabus_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };
    
    $scope.saveWeek = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'syllabus/saveWeek', angular.extend($scope.addWeekModel, $scope.filterModel), config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    if(success.data.status == 'success'){
                        $("#addWeekModal").modal("hide");
                        showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                        $scope.onSubmit();
                    } else if(success.data.status == 'error'){
                        showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                    }
                    
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    };
    
    $scope.initWeekDetailModal = function(id, day){
        $scope.addWeekDetailModel.selectedDate = day;
        $scope.addWeekDetailModel.selectedWeekId = id; 
    };
    
    $scope.initEditWeekModal = function(week){
        var start_date = $filter('date')(new Date(week.start_date.split('-').join('/')), "dd/M/yyyy");
        var end_date = $filter('date')(new Date(week.end_date.split('-').join('/')), "dd/M/yyyy");
        
        $scope.editWeekModel.id = week.id;
        $scope.editWeekModel.class_id = week.class_id;
        $scope.editWeekModel.batch_id = week.batch_id;
        $scope.editWeekModel.start_date = start_date;
        $scope.editWeekModel.end_date = end_date;
        $scope.editWeekModel.subject_id = week.subject_id;
        $scope.editWeekModel.week = week.week;
    };
    
    $scope.saveEditWeek = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'syllabus/updateWeek', $scope.editWeekModel, config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    if(success.data.status === 'success'){
                        $("#editWeekModal").modal("hide");
                        $scope.onSubmit();
                    }else if(success.data.status === 'error'){
                        showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                    }
                    //console.log(success.data);
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    };
    
    $scope.saveWeekDetail = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'syllabus/saveWeekDetail', $scope.addWeekDetailModel, config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $("#addWeekDetailModal").modal("hide");
                    $scope.addWeekDetailModel.topic = "";
                    $scope.addWeekDetailModel.status = "Pending";
                    $scope.addWeekDetailModel.comment = "";
                    $scope.onSubmit();
                    //console.log(success.data);
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    };
    
    $scope.changeStatusWithConfirmation = function(id){
        $("#doneSyllabusModal").modal("show");
        $scope.confirmDoneId = id;
    };
    
    $scope.changeStatus = function(status, id){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        if(status === 'Partially Done' || status==='Reschedule'){
            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
            $scope.addCommentModel.status = status;
            $scope.addCommentModel.id = id;
            $("#addCommentModal").modal("show");
        } else {
            if(status == "Done"){
                $("#doneSyllabusModal").modal("hide");
                $scope.confirmDoneId = '';
            }
            $http.post(base_url + 'syllabus/changeSyllabusStatus', {status:status, id: id}, config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    if(success.data.status === "success"){
                        showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                        $scope.onSubmit();
                    } else {
                        showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                    }
                    //console.log(success.data);
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
            );
        }
    };
    
    $scope.saveComment = function(){
        $http.post(base_url + 'syllabus/addCommentAndChangeStatus', $scope.addCommentModel, config).then(
            function (success) {
                if(success.data.status === "success"){
                    $("#addCommentModal").modal("hide");
                    showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                    $scope.onSubmit();
                } else {
                    showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                }
                //console.log(success.data);
            },
            function (error) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };
    
    $scope.initEditWeekDetailModal = function(obj){
        $scope.editWeekDetailModel = obj;
        //$scope.editWeekDetailModel.syllabus_week_id = id;
    };
    
    $scope.updateWeekDetail = function(){
        $http.post(base_url + 'syllabus/updateWeekDetails', $scope.editWeekDetailModel, config).then(
            function (success) {
                if(success.data.status === "success"){
                    $("#editWeekDetailModal").modal("hide");
                    showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                    $scope.onSubmit();
                } else {
                    showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                }
                //console.log(success.data);
            },
            function (error) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };
    
    $scope.request = function(id, status){
        $scope.my_new_id = id;
        $scope.my_new_status = status;
        };

        $scope.confirm_request = function(state){
            if($scope.requestText != null){
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                $http.post(base_url + 'syllabus/reqForApprovalSyls', {id:$scope.my_new_id,status:$scope.my_new_status,reason:$scope.requestText,state:state}, config).then(
                   function (success) {
                   $('.edit_attendance_request_model').modal('hide');
                        showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                        $scope.r_id = $scope.my_new_id;
                        $scope.getSchoolAdmins();
                        $scope.onSubmit();
                        
                        $scope.requestText ="";
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                   },
                   function (error) {
                       Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                       console.log(error.data);
                   });
            }else{
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $('#request_error').show();
            }
           };
    $scope.getSchoolAdmins = function(){
        $http.post(base_url + 'syllabus/getSchoolAdmins', {}, config).then(
            function(success){
                publicNotificationViaPusher("lbl_approval_syllabus",  success.data.new_ids, "applications/all", {'sender': success.data.sender}, $scope.r_id);
                //$scope.adminIDs = success.data;
            },
            function(error){
                console.log(error.data);
            }
        );
    };
    
    $scope.deleteSyllabusOfDay = function(d){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'syllabus/deleteSyllabusOfDay', d, config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                    $scope.onSubmit();

                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    };
    
    
    $scope.deleteSyllabusOfWeek = function(id){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'syllabus/deleteSyllabusOfWeek', {id:id}, config).then(
            function (success) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                $scope.onSubmit();
            },
            function (error) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };
    
    
    
});

    /* End View Study Plan For Parent */ 

/* child evaluation report */

app2.controller("childEvaluationCardController", function ($scope, $http, $window, $location, $filter) {
    $scope.parentchild = {};
    $scope.batches = {};
    $scope.academicyears = {};
    $scope.filterModel = {};
    $scope.currentModel = {};
    $scope.evaluations = {};
    $scope.students = [];
    $scope.grades = [{stars:1,grade:'E',legend:'Weak'},
                    {stars:2,grade:'D',legend:'Fair'},
                    {stars:3,grade:'C',legend:'Good'},
                    {stars:4,grade:'B',legend:'Excellent'},
                    {stars:5,grade:'A',legend:'Exceptional'}
                    ];

    $scope.initGetParentChild = function (academic_year_id) {
        if(academic_year_id){
        Loading("#marksFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'students/getChilds',{ 'academic_year_id': academic_year_id }, "", config).then(
                function (success) {
                    Loading("#marksFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.parentchild = success.data.student_ids;
                },
                function (error) {
                    Loading("#marksFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
            );
        }
    };







     $scope.onSubmit = function (valid) {
        if (valid) {
            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'students/getChildForParentReportCard', $scope.filterModel, config).then(
                    function (success) {
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.evaluation = success.data.evaluation;
                        $scope.all_evaluation = success.data.all_evaluation;
                        $scope.students = success.data.students;
                        $scope.subjects = success.data.subjects;
                        $scope.subjects2 = success.data.subjects2;
                        $scope.span = success.data.span;
                        $scope.name_span = success.data.name_span;
                        $scope.non_subject_span = success.data.non_subject_span;
                        $scope.overall_span = success.data.overall_span;
                        $scope.overall_span2 = success.data.overall_span2;

                        
                        
                        
                    },
                    function (error) {
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        //$window.location.href = 'errors/' + error.status;
                        console.log(error);
                    }
            );
        }
    };

    

    $scope.getAvg = function(report){
        if(report.length == 0){
            return 0;
        }
        sum = 0;
        total = report.length;
        angular.forEach(report, function(val, key) {
                        sum += parseInt(val.stars);
                    });
        return Math.round(sum / total);
    }

    $scope.getAvg2 = function(report){
        if(report.length == 0){
            return 0;
        }
        sum = 0;
        total = report.length;
        angular.forEach(report, function(val, key) {
                        sum += parseInt(val.stars);
                    });
        return sum / total;
    }

    $scope.getGrade = function(report){
        stars = $scope.getAvg(report);
        var result = $scope.grades.find(obj => {
          return obj.stars === stars
        })

        if(result == undefined){
            return "";
        }else{
            return result.grade;
        }

        
    }

    $scope.getLegend = function(report){
        stars = $scope.getAvg(report);
        var result = $scope.grades.find(obj => {
          return obj.stars === stars
        })

        if(result == undefined){
            return "";
        }else{
            return '(' + result.legend + ')';
        }

        
    }

    $scope.initAcademicYears =  function(){
        Loading("#marksFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'examination/getAcademicYears', "", config).then(
            function (success) {
                Loading("#marksFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.initGetParentChild(success.data.current_academic_year_id);
                $scope.academicyears = success.data.data;
                $scope.filterModel.academic_year_id = success.data.current_academic_year_id;
                $scope.filterModel.class_id = "";
                $scope.filterModel.batch_id = "";
            },
            function (error) {
                Loading("#marksFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $window.location.href = 'errors/' + error.status;
            }
        );
    };
    
    $scope.initBatches = function (student_id, academic_year_id) {
        if (student_id && academic_year_id) {
            Loading("#marksFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            Loading("#evaluationDropdown", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'students/getClassBatchesandEvaluationForParent', {'student_id': student_id, 'academic_year_id':academic_year_id}, config).then(
                function (success) {
                    Loading("#marksFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    Loading("#evaluationDropdown", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.batches = success.data.batches;
                    $scope.evaluations = success.data.evaluations;
                    $scope.filterModel.batch_id = "";
                    $scope.filterModel.evaluation_type = "";
                },
                function (error) {
                    console.log(error.data);
                    Loading("#marksFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    Loading("#evaluationDropdown", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    
                }
            );
        }
    };
    
    
   
    

    

});

/* End child evaluation report */

/* download for parent controller */


app2.controller('downloadParentController', function ($window,$scope, $http, $sce) {
    $scope.study = {};
    $scope.details = {};
    $scope.temp = {};
    $scope.parentchild ={};
    $scope.details = {};
    $scope.study2 = {};
    $scope.study2.files = [];
    $scope.study3 = {};
    $scope.study3.files = [];
    
    $scope.dropzoneConfigStdAss = {
        'options': {// passed into the Dropzone constructor
            'url': base_url + 'study_material/upload_attachments',
            'autoProcessQueue': true,
            'autoDiscover': false,
            'uploadMultiple': true,
            'parallelUploads': 20,
            'timeout': 180000,
            'maxFilesize': 1024, //MB
            'maxFiles': 100,
            'acceptedFiles': "image/*, application/pdf, .doc, .docx, .ppt, pptx, .mp4, .avi, .flv, .wmv, .mov, .webm",
            'addRemoveLinks': true,
            'dictRemoveFile': '<?php echo lang("lbl_remove");?>',
            'dictDefaultMessage': '<?php echo lang("drop_here");?>',
            'dictFileTooBig': 'File is bigger than 1GB',
            init: function () {
                var submitButton = document.querySelector("#submitAssignment");
                myDropzone = this;
                submitButton.addEventListener("click", function () {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                    var text = $('.textarea_editor').val();
                    $scope.study2.text = text;
                    $scope.study2.details = $scope.details;

                    if ($scope.study2.files.length == 0 && ($scope.study2.text == undefined || $scope.study2.text == "")){
                        $('#assignmentAns').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("write_details"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                    } else {
                      $scope.assignmentAnswer();
                    }
                    
                });
                myDropzone.on("complete", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
                myDropzone.on("addedfile", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                });
                myDropzone.on("removedfile", function (file) {
                    $scope.study2.files = $scope.study2.files.filter(function( obj ) {
                        return obj.name !== file.name;
                    });
                    
                });
            }
        },
        'eventHandlers': {
            'sending': function (file, xhr, formData) {
            },
            'success': function (file, response) {
                $scope.study2.files = $scope.study2.files.concat(JSON.parse(response));
                $scope.$apply();
            }
        }
    };

    $scope.dropzoneConfigStdHom = {
        'options': {// passed into the Dropzone constructor
            'url': base_url + 'study_material/upload_attachments',
            'autoProcessQueue': true,
            'autoDiscover': false,
            'uploadMultiple': true,
            'parallelUploads': 20,
            'maxFilesize': 1024, //MB
            'maxFiles': 100,
            'acceptedFiles': "image/*, application/pdf, .doc, .docx, .ppt, pptx, .mp4, .avi, .flv, .wmv, .mov, .webm",
            'addRemoveLinks': true,
            'dictRemoveFile': '<?php echo lang("lbl_remove");?>',
            'dictDefaultMessage': '<?php echo lang("drop_here");?>',
            'dictFileTooBig': 'File is bigger than 1GB',
            init: function () {
                var submitButton = document.querySelector("#submitHomework");
                myDropzone = this;
                submitButton.addEventListener("click", function () {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                    var text = $('.textarea_editor1').val();
                    $scope.study3.text = text;
                    $scope.study3.details = $scope.details;
                    if ($scope.study3.files.length == 0 && ($scope.study3.text == undefined || $scope.study3.text == "")){
                        $('#homeworkAns').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("write_details"); ?>').show();
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

                    } else {
                      $scope.homeworkAnswer();
                    }
                    
                });
                myDropzone.on("complete", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
                myDropzone.on("addedfile", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                });
                myDropzone.on("removedfile", function (file) {
                    $scope.study3.files = $scope.study3.files.filter(function( obj ) {
                        return obj.name !== file.name;
                    });
                    
                });
            }
        },
        'eventHandlers': {
            'sending': function (file, xhr, formData) {
            },
            'success': function (file, response) {
                $scope.study3.files = $scope.study3.files.concat(JSON.parse(response));
                $scope.$apply();
            }
        }
    };

    $scope.homeworkAnswer = function () {
      Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'study_material/submitAssignment', $scope.study3, config).then(
                function (response)
                {
                    if(response.data.uploaded='true')
                    {
                        $('#details').modal('toggle');
                        $('#openHomework').modal('toggle');
                        $scope.initHomeworkForStudents();
                        showNotification('Success', response.data.message, 'success');
                        var otherData = {};
                        publicNotificationViaPusher('sub_homework',otherData, response.data.part, 'study_material/class_work', {'sender': response.data.sender});
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $('.textarea_editor1').data("wysihtml5").editor.clear();
                        Dropzone.forElement("#my-awesome-dropzone_submit_hom").removeAllFiles(true);
                    }
                    else
                    {
                        $('#details').modal('toggle');
                        $('#openHomework').modal('toggle');
                        $scope.initHomeworkForStudents();
                        showNotification('Success', response.data.message, 'success');
                        var otherData = {};
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $('.textarea_editor1').data("wysihtml5").editor.clear();
                        Dropzone.forElement("#my-awesome-dropzone_submit_hom").removeAllFiles(true);
                    }
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    
                }
            );
  };

    $scope.assignmentAnswer = function () {

      Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'study_material/submitAssignment', $scope.study2, config).then(
                function (response)
                {
                    if(response.data.uploaded='true')
                    {
                        $('#details').modal('toggle');
                        $('#openAssignment').modal('toggle');
                        $scope.initAssignmentsForStudents();
                        showNotification('Success', response.data.message, 'success');
                        var otherData = {};
                        publicNotificationViaPusher('sub_assignment',otherData, response.data.part, 'study_material/class_work', {'sender': response.data.sender});
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $('.textarea_editor').data("wysihtml5").editor.clear();
                        Dropzone.forElement("#my-awesome-dropzone_submit_ass").removeAllFiles(true);
                    }
                    else
                    {
                        $('#details').modal('toggle');
                        $('#openAssignment').modal('toggle');
                        $scope.initAssignmentsForStudents();
                        showNotification('Success', response.data.message, 'success');
                        var otherData = {};
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $('.textarea_editor').data("wysihtml5").editor.clear();
                        Dropzone.forElement("#my-awesome-dropzone_submit_ass").removeAllFiles(true);
                    }
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    
                }
            );
  };

   $scope.init = function () {
       
        Loading("#syllabusFilterChilds", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'syllabus/getChilds', "", config).then(
                function (success) {
                    $scope.parentchild = success.data.student_ids;
                    $scope.study.materials = success.data.materials;
                    Loading("#syllabusFilterChilds", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    for (var i = 0, len = $scope.study.materials.length; i < len; i++) {
                    $scope.study.materials[i]['details'] = $sce.trustAsHtml($scope.study.materials[i]['details']);
                    }

                },
                function (error) {
                    Loading("#syllabusFilterChilds", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
            );
        
    };

    $scope.showMaterial = function (details) {
      $scope.details = details;
    };

    $scope.initSubjectsForStudent = function () {
        Loading("#syllabusFilterChilds", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'study_material/getSubjectsForStudent', "", config).then(
                function (success) {
                    Loading("#syllabusFilterChilds", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.subjects = success.data.subjects;
                    $scope.study.materials = success.data.materials;
                    for (var i = 0, len = $scope.study.materials.length; i < len; i++) {
                    $scope.study.materials[i]['details'] = $sce.trustAsHtml($scope.study.materials[i]['details']);
                }
                    

                },
                function (error) {
                    Loading("#syllabusFilterChilds", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
            );
        
    };

    $scope.getSections = function () {
        //console.log($scope.study.class);
        $scope.class_error = false;
        $http.post(base_url + 'attendance/getClassBatches', {class_id: $scope.study.class}, config).then(
                function (response) {
                    $scope.study.batches = response.data;
                    $scope.study.subject = "";
                    $scope.study.subjects = "";
                    $scope.study.section = "";
                });
    };


     $scope.initBatches = function (student_id) {
        
        Loading("#syllabusFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (student_id) {
            Loading("#syllabusFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'syllabus/getBatchesForParent', {'student_id':student_id}, config).then(
                    function (success) {
                        Loading("#syllabusFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.batches = success.data.batches;
                        $scope.fcModel.batch_id = "";
                        
                    },
                    function (error) {
                        console.log(error.data);
                        
                    }
            );
            
            
        }
    };


$scope.initSubjects = function (student_id) {
        Loading("#syllabusFilterSubjects", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (student_id) {
            Loading("#syllabusFilterSubjects", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'syllabus/getSubjectsForParent', {student_id: student_id}, config).then(
                function (success) {
                    Loading("#syllabusFilterSubjects", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.subjects = success.data;
                    //$scope.filterModel.subject_id = "";
                },
                function (error) {
                    console.log(error.data);
                }
            );
        }
    };

    $scope.getSubjects = function () {
        $scope.section_error = false;
        $http.post(base_url + 'study_material/getSubjects', {class: $scope.study.class, section: $scope.study.section}).then(
                function (response) {
                    $scope.study.subjects = response.data.subjects;
                    $scope.study.subject = "";
                });
    };

    $scope.getMaterials = function () {
        $http.post(base_url + 'study_material/getDownloadMaterials').then(
                function (response) {
                    $scope.study.materials = response.data;

                    for (var i = 0, len = $scope.study.materials.length; i < len; i++) {
                        $scope.study.materials[i].details = $sce.trustAsHtml($scope.study.materials[i].details);
                    }
                });
    };

    $scope.subjectChanged = function () {
        $scope.subject_error = false;
    };

    $scope.typeChanged = function () {
        $scope.type_error = false;
    };

     $scope.filter = function () {
        $scope.class_error = false;
        $scope.subject_error = false;
        $scope.type_error = false;
        $check = true;


        if ($scope.student_id == undefined || $scope.student_id == "") {
            $scope.class_error = true;
            $check = false;
        }
        if ($scope.subject_id == undefined || $scope.subject_id == "") {
            $scope.subject_error = true;
            $check = false;
        }
        if ($scope.type == undefined || $scope.type == "") {
            $scope.type_error = true;
            $check = false;
        }
       
     if ($check) {

            
        
            Loading("#download_search_filter", "<?php echo lang("loading_datatable") ?>", "", "show");

            $http.post(base_url + 'study_material/filter_parent', {type:$scope.type,student_id:$scope.student_id,subject_id:$scope.subject_id}).then(
            function (response) {
                $scope.study.materials = response.data.materials;
                for (var i = 0, len = $scope.study.materials.length; i < len; i++) {
                    $scope.study.materials[i]['details'] = $sce.trustAsHtml($scope.study.materials[i]['details']);
                }
                Loading("#download_search_filter", "<?php echo lang("loading_datatable") ?>", "", "hide");

            });
        }
        

    };

    $scope.filterForStudent = function () {
        $scope.subject_error = false;
        $scope.type_error = false;
        //zafar
        $scope.date_error = false;
        $check = true;


        //zafar
        //if ($scope.subject_id == undefined || $scope.subject_id == "") {
        //    $scope.subject_error = true;
        //        $check = false;
        //}
        //if ($scope.type == undefined || $scope.type == "") {
        //    $scope.type_error = true;
        //    $check = false;
        //}
       
     if ($check) {

            
        
            Loading("#download_search_filter", "<?php echo lang("loading_datatable") ?>", "", "show");

            $http.post(base_url + 'study_material/filter_student', {type:$scope.type,subject_id:$scope.subject_id,date:$scope.date}).then(
            function (response) {
                $scope.study.materials = response.data.materials;
                for (var i = 0, len = $scope.study.materials.length; i < len; i++) {
                    $scope.study.materials[i]['details'] = $sce.trustAsHtml($scope.study.materials[i]['details']);
                }
                Loading("#download_search_filter", "<?php echo lang("loading_datatable") ?>", "", "hide");

            });
        }
        

    };
    //zafar
    $scope.removeFilter_student = function () {
        Loading("body", "", "", "show");
        $scope.initSubjectsForStudent();
        Loading("body", "", "", "hide");
        $scope.subject_error = false;
        $scope.type_error = false;
        $scope.date_error = false;
        $scope.type = '';
        $scope.subject_id = '';
        $scope.date = '';
    };

    $scope.initAssignmentsForStudents = function () {

          $http.post(base_url + 'study_material/filter_student_all_assignment', "", config).then(
            function (response) {
                $scope.study.materials2 = response.data;

                for (var i = 0, len = $scope.study.materials2.length; i < len; i++) {
                    $scope.study.materials2[i]['details'] = $sce.trustAsHtml($scope.study.materials2[i]['details']);
                  }
                });
  }

    $scope.initHomeworkForStudents = function () {

          $http.post(base_url + 'study_material/filter_student_all_homework', "", config).then(
            function (response) {
                $scope.study.materials1 = response.data;
                for (var i = 0, len = $scope.study.materials1.length; i < len; i++) {
                    $scope.study.materials1[i]['details'] = $sce.trustAsHtml($scope.study.materials1[i]['details']);
                }
            });
  }

    $scope.filterForStudentAssignment = function () {
        $scope.subject_error = false;
        $check = true;
        
        if ($scope.subject_id == undefined || $scope.subject_id == "") {
            $scope.subject_error = true;
            $check = false;
        }
       
     if ($check) {

            
        
            Loading("#assignment_search_filter", "<?php echo lang("loading_datatable") ?>", "", "show");

            $http.post(base_url + 'study_material/filter_student_assignment', {subject_id:$scope.subject_id}).then(
            function (response) {
                $scope.study.materials2 = response.data;
                for (var i = 0, len = $scope.study.materials2.length; i < len; i++) {
                    $scope.study.materials2[i]['details'] = $sce.trustAsHtml($scope.study.materials2[i]['details']);
                }
                //console.log($scope.study.materials2);
                Loading("#assignment_search_filter", "<?php echo lang("loading_datatable") ?>", "", "hide");
            });
        }
        

    };

    $scope.filterForStudentHomework = function () {
        $scope.subject_error = false;
        $check = true;
        
        if ($scope.subject_id == undefined || $scope.subject_id == "") {
            $scope.subject_error = true;
            $check = false;
        }
       
     if ($check) {

            Loading("#homework_search_filter", "<?php echo lang("loading_datatable") ?>", "", "show");

            $http.post(base_url + 'study_material/filter_student_homework', {subject_id:$scope.subject_id}).then(
            function (response) {
                $scope.study.materials1 = response.data;
                for (var i = 0, len = $scope.study.materials1.length; i < len; i++) {
                    $scope.study.materials1[i]['details'] = $sce.trustAsHtml($scope.study.materials1[i]['details']);
                }
                Loading("#homework_search_filter", "<?php echo lang("loading_datatable") ?>", "", "hide");

            });
        }
        

    };

    $scope.removeFilter = function () {
        Loading("body", "", "", "show");
        $scope.getMaterials();
        Loading("body", "", "", "hide");
        $scope.class_error = false;
        $scope.section_error = false;
        $scope.subject_error = false;
        $scope.type_error = false;
        $scope.study.class = '';
        $scope.study.section = '';
        $scope.study.subject = '';
        $scope.study.type = '';
    };

    $scope.detailsStd_studymaterial_dashbaord = function (mat) {

        $scope.details.title = $scope.virtualstore[mat].title;
        $scope.details.content_type = $scope.virtualstore[mat].content_type;
        $scope.details.subject_name = $scope.virtualstore[mat].subject_name;
        $scope.details.uploaded_time = $scope.virtualstore[mat].uploaded_time;
        $scope.details.files = $scope.virtualstore[mat].files;
        $scope.details.details = $scope.virtualstore[mat].details;
        $scope.details.id = $scope.virtualstore[mat].id;
    }

    $scope.details_set = function (mat) {

        $scope.details.title = mat.title;
        $scope.details.content_type = mat.content_type;
        $scope.details.subject_name = mat.subject_name;
        $scope.details.uploaded_time = mat.uploaded_time;
        $scope.details.files = mat.files;
        $scope.details.details = mat.details;
        $scope.details.id = mat.id;

        // google drive
        $scope.details.storage_type=mat.storage_type;
        $scope.details.file_names=mat.file_names;
        $scope.details.filesurl=mat.filesurl;
        $scope.details.thumbnail_links = mat.thumbnail_links;
        $scope.details.icon_links = mat.icon_links;
    };

    $scope.details_set1 = function (matd) {
        $scope.details.title = matd.title;
        $scope.details.content_type = matd.content_type;
        $scope.details.subject_name = matd.subject_name;
        $scope.details.uploaded_time = matd.published_date;
        $scope.details.files = matd.files;
        // google drive
        $scope.details.storage_type=matd.storage_type;
        $scope.details.file_names=matd.file_names;
        $scope.details.filesurl=matd.filesurl;
        $scope.details.thumbnail_links = matd.thumbnail_links;
        // google drive
        $scope.details.details = matd.details;
        $scope.details.id = matd.id;
        $scope.details.due_date = matd.due_date;
        $scope.details.teacher_avatar = matd.avatar;
        $scope.details.teacher_name = matd.teacher_name;
        $scope.details.material_details = matd.material_details;
        $scope.details.teacher_id = matd.teacher_id;
    };

    $scope.details_set2 = function (matr) {

        $scope.details.title = matr.title;
        $scope.details.content_type = matr.content_type;
        $scope.details.subject_name = matr.subject_name;
        $scope.details.uploaded_time = matr.published_date;
        $scope.details.files = matr.files;
        // google drive
        $scope.details.storage_type=matr.storage_type;
        $scope.details.file_names=matr.file_names;
        $scope.details.filesurl=matr.filesurl;
        $scope.details.thumbnail_links = matr.thumbnail_links;
        // google drive
        $scope.details.details = matr.details;
        $scope.details.id = matr.id;
        $scope.details.due_date = matr.due_date;
        $scope.details.teacher_avatar = matr.avatar;
        $scope.details.teacher_name = matr.name;
        $scope.details.material_details = matr.material_details;
        $scope.details.teacher_id = matr.id;
    };

     $scope.download = function () {
        Loading("body", "", "", "show");
        $http.post(base_url + 'study_material/zip_parent', $scope.details).then(
                function (response) {
                    //window.location.href = response.data.path;
                    paths = response.data.paths;
                    angular.forEach(paths, function (value, key) {
                        var link = document.createElement('a');
                        link.href = value;
                        link.download = key;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    });
                    Loading("body", "", "", "hide");
                });
    };

    $scope.studyMaterialForStudentDashboard = function () {
        Loading("#syllabusFilterChilds", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'study_material/getMaterilasForStudentDashboard', "", config).then(
            function (success) {
                
                Loading("#syllabusFilterChilds", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.subjects = success.data.subjects;
                $scope.study.materials = success.data.materials;
                for (var i = 0, len = $scope.study.materials.length; i < len; i++) {
                $scope.study.materials[i]['details'] = $sce.trustAsHtml($scope.study.materials[i]['details']);
            }
            $scope.virtualstore = success.data.materials;
           
            },
            function (error) {
                Loading("#syllabusFilterChilds", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $window.location.href = 'errors/' + error.status;
            }
        );
    };
});


/* end download for parent controller */

/* parent dashboard */

app2.controller('Parent_dashboardCtrl', function ($scope, $http, $window, $location, $sce) {
    $scope.studyplan = [];
    $scope.selected_class_batch="";
    $scope.selected_department_for_graph = "";
    $scope.selected_class_for_graph = "";
    $scope.employee_attendance = [];
    $scope.student_attendance = [];
    $scope.emp_overall = [];
    $scope.std_overall = [];
    $scope.studyplan_overall = [];
    $scope.stdid = '';
    $scope.calendarEvents = {};

    $scope.announcement = [];
    $scope.get_announcement = function(){
        console.log("funcation called");
        $http.post(base_url + "dashboard/getAnnouncements", {}, config).then(
                function (success) {
                    console.log("yasir");
                    if (success.data == "" ){
                        $('#myModal').modal('hide');
                    } 
                    else {
                        $('#myModal').modal('show');
                        $scope.announcement = success.data;
                        for (var i = 0, len = $scope.announcement.length; i < len; i++) {

                          $scope.announcement[i].details = $sce.trustAsHtml($scope.announcement[i].details);

                        }
                    }
                },
                function (error) {
                    console.log(error.data);
                }
        ); 
    };

    
    $scope.refreshFees = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'fee/refresh', "", config).then(
            function (success) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                if(success.data.success){
                    showNotification('<?php echo lang("success_app") ?>', success.data.msg, "success");
                }  
            },
            function (error) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $window.location.href = 'errors/' + error.status;
            }
        );
    }

    $scope.get_Today_Events = function(){

        $http.post(base_url + "dashboard/getTodayEventsDshboard", config).then(
                function (success) {
                    $scope.calendarEvents = success.data;
                    //console.log($scope.calendarEvents);
                    

                    //console.log(success.data);
                },
                function (error) {
                    console.log(error.data);
                }
        );   
    };


    $scope.loadModel = function(id){
        $("#myModal").modal("show");
        $scope.filter(id);
    };

    $scope.childerns = [];
    $scope.init_parent_children_list = function(){

     $http.get(base_url + 'parents/get_parent_childlist', "", config).then(
            function (success) { 
                $scope.childerns = success.data.student_ids;
                $scope.filter($scope.childerns[0].student_id);
            },
            function (error) {
                console.log(error.data);
                
            }
        );
    };

     $scope.filter = function (student_id) {
        
            console.log(student_id);
            
        
            
        $scope.init_study_plan_statictics_forparent(student_id);
        $scope.init_fee_summary_forparent(student_id);
        $scope.init_timetable(student_id);
        $scope.init_student_today_attenance_forparentportal(student_id);
        $scope.init_academic_wise_std_att_graph(student_id);

    };

    $scope.init_student_today_attenance_forparentportal = function(student_id){
        Loading("#today_student2_attendance_table", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'dashboard/get_today_std_attendance_parentportal', {student_id: student_id}, config).then(
            function (success) {
                $scope.student_attendance = success.data.all;
                console.log($scope.student_attendance);
                $scope.std_overall = success.data.overall;
                $scope.std_overall.classname = "<?php echo lang("lbl_overall") ?>";
                $scope.show_std_attendance_individal_graph($scope.std_overall);
                Loading("#today_student2_attendance_table", '<?php echo lang("loading_datatable") ?>', "", "hide");
            },
            function (error) {
                console.log(error.data);
                Loading("#today_student2_attendance_table", '<?php echo lang("loading_datatable") ?>', "", "hide");
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };

    $scope.current_day_timetable = "";
    $scope.current_day_periods = "";
    $scope.init_timetable = function(student_id){
        Loading("#time_table_parentdashboard", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'parents/getTimetableOfDayForAllBatches',{student_id: student_id}, config).then(
            function (success) {
                Loading("#time_table_parentdashboard", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.current_day_timetable = success.data.timetables;
                    $scope.current_day_periods = success.data.periods;
            },
            function (error) {
                console.log(error.data);
                Loading("#time_table_parentdashboard", '<?php echo lang("loading_datatable") ?>', "", "hide");
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };
    
    $scope.init_academic_wise_emp_att_graph = function(){
        Loading("#morris-area-chart-emp-academic-wise-attendance", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'dashboard/get_emp_academic_wise_graph', "", config).then(
            function (success) {
                Loading("#morris-area-chart-emp-academic-wise-attendance", '<?php echo lang("loading_datatable") ?>', "", "hide");
                Morris.Line({
                    element: 'morris-area-chart-emp-academic-wise-attendance',
                    data: success.data,
                    xkey: 'month',
                    ykeys: ['Present','Late','LeaveStatus','Absent'],
                    labels: ['Present','Late','LeaveStatus','Absent'],
                    xLabels: 'month',
                    parseTime: false,
                    xLabelFormat: function (x) {
                        return x.src.monthyear;
                    },
                    pointSize: 4,
                    fillOpacity: 0,
                    pointStrokeColors: ['#5cb85c','#f0ad4e','#5bc0de','#d9534f'],
                    behaveLikeLine: true,
                    gridLineColor: '#e0e0e0',
                    lineWidth: 3,
                    hideHover: 'auto',
                    hoverCallback: function (index, options, content, row) {
                    return success.data[index].monthyear+"<br>Present "+success.data[index].Present+"<br>Leave "+success.data[index].LeaveStatus+"<br>Absent "+success.data[index].Absent+"<br>Late "+success.data[index].Late;
                    },
                    lineColors: ['#00c292','#fec107','#03a9f3','#fb9678'],
                    resize: true
                });
            },
            function (error) {
                console.log(error.data);
                Loading("#morris-area-chart-emp-academic-wise-attendance", '<?php echo lang("loading_datatable") ?>', "", "hide");
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };
    
    $scope.init_academic_wise_std_att_graph = function(student_id){
        Loading("#morris-area-chart-std-academic-wise-attendance", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'dashboard/get_std_academic_wise_graph_forparent', {student_id: student_id} , config).then(
            function (success) {
                $("#morris-area-chart-std-academic-wise-attendance").empty();
                Loading("#morris-area-chart-std-academic-wise-attendance", '<?php echo lang("loading_datatable") ?>', "", "hide");
                
                Morris.Area({
                    element: 'morris-area-chart-std-academic-wise-attendance',
                    data: success.data,
                    xkey: 'month',
                    ykeys: ['Present','Late','LeaveStatus','Absent'],
                    labels: ['Present','Late','LeaveStatus','Absent'],
                    xLabels: 'month',
                    parseTime: false,
                    xLabelFormat: function (x) {
                        return x.src.monthyear;
                    },
                    pointSize: 4,
                    fillOpacity: 0,
                    pointStrokeColors: ['#5cb85c','#f0ad4e','#5bc0de','#d9534f'],
                    behaveLikeLine: true,
                    gridLineColor: '#e0e0e0',
                    lineWidth: 3,
                    hideHover: 'auto',
                    hoverCallback: function (index, options, content, row) {
                        return success.data[index].monthyear+"<br>Present "+success.data[index].Present+"<br>Leave "+success.data[index].LeaveStatus+"<br>Absent "+success.data[index].Absent+"<br>Late "+success.data[index].Late;
                    },
                    lineColors: ['#00c292','#fec107','#03a9f3','#fb9678'],
                    resize: true
                });
            },
            function (error) {
                console.log(error.data);
                Loading("#morris-area-chart-std-academic-wise-attendance", '<?php echo lang("loading_datatable") ?>', "", "hide");
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };

    $scope.init_study_plan_statictics_forparent = function(student_id){
        Loading("#study-plan-statictics-container", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'dashboard/get_study_plan_statictics_forparent',{student_id: student_id}, config).then(
            function (success) {
            console.log(success.data);
                Loading("#study-plan-statictics-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.studyplan = success.data.all;

                $scope.studyplan_overall = success.data.overall;
                console.log($scope.studyplan_overall);
                $scope.show_studyplan_overall_graph($scope.studyplan_overall);
            },
            function (error) {
                console.log(error.data);
                Loading("#study-plan-statictics-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };


  $scope.show_studyplan_overall_graph = function(obj){ 
         
        $("#morris-bar-chart").empty();
        Loading("#morris-bar-chart", '<?php echo lang("loading_datatable") ?>', "", "show");

        $scope.selected_class_batch = "<?php echo lang("lbl_overall") ?>";
        
        

        Morris.Donut({
          element: 'morris-bar-chart',
          data: obj,
          colors: ['#55ce63', '#414755', '#40c4ff','#00cc00','#000000']
        });
        Loading("#morris-bar-chart", '<?php echo lang("loading_datatable") ?>', "", "hide");
    
    };
    
     $scope.show_studyplan_indivial_graph = function(obj){
    
        $("#morris-bar-chart").empty();
        Loading("#morris-bar-chart", '<?php echo lang("loading_datatable") ?>', "", "show");
        $scope.selected_class_batch = " "+obj.class_name +" - "+ obj.batch_name;
        var dd = [];
        angular.forEach(obj.subjects, function (val, key) {
            var d = {
                'y':val.name,
                'done':val.syllabus.counts.done.count,
                'pending':val.syllabus.counts.pending.count,
                'skip':val.syllabus.counts.skip.count,
                'partially_done':val.syllabus.counts.partially_done.count,
                'reschedule':val.syllabus.counts.reschedule.count
            };
            dd.push(d);
        });
        // Morris bar chart
        Morris.Bar({
            element: 'morris-bar-chart',
            data:dd,
            xkey: 'y',
            ykeys: ['done', 'pending', 'skip','partially_done','reschedule'],
            labels: ['<?php echo lang("lbl_done"); ?>', '<?php echo lang("lbl_pending"); ?>', '<?php echo lang("lbl_skip"); ?>','<?php echo lang("partially_done"); ?>','<?php echo lang("reschedule"); ?>'],
            barColors: ['#55ce63', '#414755', '#40c4ff','#00cc00','#000000','#CC0000'],
            hideHover: 'auto',
            gridLineColor: '#eef0f2',
            resize: true
        });
        Loading("#morris-bar-chart", '<?php echo lang("loading_datatable") ?>', "", "hide"); 
        
    };

    $scope.init_fee_summary = function(){
        Loading("#fee-summary-container", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'dashboard/get_fee_summary', "", config).then(
            function (success) {
                Loading("#fee-summary-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.fee_data = success.data.classes;
                $scope.overall = success.data.overall;
                $scope.show_fee_graph($scope.overall);
                
                
            },
            function (error) {
                console.log(error.data);
                Loading("#fee-summary-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };

     $scope.init_fee_summary_forparent = function(student_id){
        Loading("#fee-summary-container", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'dashboard/get_fee_summary_forparent',{student_id: student_id}, config).then(
            function (success) {
                Loading("#fee-summary-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(success.data);
                $scope.fee_data = success.data.overall;
                $scope.overall = success.data.overall;
                //console.log($scope.overall);
                $scope.show_fee_graph($scope.overall);
            },
            function (error) {
                console.log(error.data);
                Loading("#fee-summary-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };
    
   
    
   
    
    $scope.show_emp_attendance_individal_graph = function(obj){
        $("#morris-area-chart-emp-attendance").empty();
        Loading("#morris-area-chart-emp-attendance", '<?php echo lang("loading_datatable") ?>', "", "show");
        $scope.selected_department_for_graph = " "+obj.departmentname;
       
        var gData = [];
        if(Object.keys(obj).length === 6){
            gData = [
                {label: "<?php echo lang('lbl_present'); ?>", value: obj.present},
                {label: "<?php echo lang('lbl_absent'); ?>", value: obj.absent},
                {label: "<?php echo lang('lbl_leave'); ?>", value: obj.leav},
                {label: "<?php echo lang('lbl_late'); ?>", value: obj.late},
                {label: "<?php echo lang('lbl_unknown'); ?>", value: obj.unknown}
            ];
        } else {
            gData = [
                {label: "<?php echo lang('lbl_present'); ?>", value: obj.present},
                {label: "<?php echo lang('lbl_absent'); ?>", value: obj.absent},
                {label: "<?php echo lang('lbl_leave'); ?>", value: obj.leav},
                {label: "<?php echo lang('lbl_late'); ?>", value: obj.late}
            ];
        }
        
        Morris.Donut({
          element: 'morris-area-chart-emp-attendance',
          data: gData,
          colors: ['#00c292', '#fb9678', '#03a9f3','#fec107','#A9A9A9']
        });
        Loading("#morris-area-chart-emp-attendance", '<?php echo lang("loading_datatable") ?>', "", "hide");
    };
    
    $scope.show_std_attendance_individal_graph = function(obj){
        $("#morris-area-chart-student-attendance").empty();
        Loading("#morris-area-chart-student-attendance", '<?php echo lang("loading_datatable") ?>', "", "show");
        $scope.selected_class_for_graph = " "+obj.classname;
        
        var gData = [];
        if(Object.keys(obj).length === 6){
            gData = [
                {label: "<?php echo lang('lbl_present'); ?>", value: obj.present},
                {label: "<?php echo lang('lbl_absent'); ?>", value: obj.absent},
                {label: "<?php echo lang('lbl_leave'); ?>", value: obj.leav},
                {label: "<?php echo lang('lbl_late'); ?>", value: obj.late},
                {label: "<?php echo lang('lbl_unknown'); ?>", value: obj.unknown}
            ];
        } else {
            gData = [
                {label: "<?php echo lang('lbl_present'); ?>", value: obj.present},
                {label: "<?php echo lang('lbl_absent'); ?>", value: obj.absent},
                {label: "<?php echo lang('lbl_leave'); ?>", value: obj.leav},
                {label: "<?php echo lang('lbl_late'); ?>", value: obj.late}
            ];
        }
        Morris.Donut({
          element: 'morris-area-chart-student-attendance',
          data: gData,
          colors: ['#00c292', '#fb9678', '#03a9f3','#fec107','#A9A9A9']
        });
        Loading("#morris-area-chart-student-attendance", '<?php echo lang("loading_datatable") ?>', "", "hide");
    };
    
    $scope.init_employee_today_attenance = function(){
        Loading("#today_employee_attendance_table", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'dashboard/get_today_emp_attendance', "", config).then(
            function (success) {
                $scope.employee_attendance = success.data.all;
                $scope.emp_overall = success.data.overall;
                $scope.emp_overall.departmentname = "<?php echo lang("lbl_overall") ?>";
                $scope.show_emp_attendance_individal_graph($scope.emp_overall);
                Loading("#today_employee_attendance_table", '<?php echo lang("loading_datatable") ?>', "", "hide");
            },
            function (error) {
                console.log(error.data);
                Loading("#today_employee_attendance_table", '<?php echo lang("loading_datatable") ?>', "", "hide");
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };
    
    $scope.init_student_today_attenance = function(){
        Loading("#today_student2_attendance_table", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'dashboard/get_today_std_attendance', "", config).then(
            function (success) {
                $scope.student_attendance = success.data.all;
                $scope.std_overall = success.data.overall;
                $scope.std_overall.classname = "<?php echo lang("lbl_overall") ?>";
                $scope.show_std_attendance_individal_graph($scope.std_overall);
                Loading("#today_student2_attendance_table", '<?php echo lang("loading_datatable") ?>', "", "hide");
            },
            function (error) {
                console.log(error.data);
                Loading("#today_student2_attendance_table", '<?php echo lang("loading_datatable") ?>', "", "hide");
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };
    
    $scope.show_fee_graph = function(obj){
        $("#fee-graph").empty();
        Loading("#fee-graph", '<?php echo lang("loading_datatable") ?>', "", "show");
        $scope.selected_class_batch_fee = " "+obj.full_name;

        console.log($scope.selected_class_batch_fee);
       
        Morris.Donut({
          element: 'fee-graph',
          data: [
            {label: "<?php echo lang('fully_paid'); ?>", value: obj.total_paid},
            {label: "<?php echo lang('partial_paid'); ?>", value: obj.total_partial},
            {label: "<?php echo lang('lbl_due_chart'); ?>", value: obj.total_due}
          ],
          colors: ['#28a745', '#ffc107', '#dc3545']
        });
        Loading("#fee-graph", '<?php echo lang("loading_datatable") ?>', "", "hide");
    };
});

app2.controller("payrollController", function ($scope, $http, $window, $filter) {
    $scope.departments = {};
    $scope.deptCategories = {};
    $scope.fModel = {};  //add new fee collection model
    $scope.filterModel = {'searchBy':'', 'unpaid':0};  //fee collection (fc)
    $scope.afcModel = {mode:'cash'}; //add fee collection (afc)
    $scope.payrollEmployees = [];
    $scope.selectedEmp = {};
    $scope.empPayrollRecords = [];
    $scope.feetypes = {};
    $scope.today = $filter('date')(new Date(), 'dd/MM/yyyy');
    $scope.mode = 'cash';
    $scope.isSendSMSToEmployee = false;
    $scope.isSendEmailToEmployee = false;
    $scope.loading = false;
    $scope.update_loading = false;
    $scope.paid_amount = '';
    $scope.comment = '';
    $scope.partiallyPayrollDetailModel = [];
    $scope.academicyears = {};
    $scope.uModel = { additional_payment: 0, other_deducation: 0, other_deduction_remarks: "", additional_payment_remarks: ""};
    $scope.salaryTypes = [];
    $scope.payrollGroups = [];
    $scope.myModel2 = {};
    $scope.stvModel = {};
    $scope.estvModel = {};
    $scope.selectedSalaryType = {};
    $scope.isVarientFormShow = false;
    $scope.selectedSalaryTypeVarients = [];
    $scope.isVarientFormEditShow = false;
    $scope.additional_payment = 0;
    $scope.other_deducation = 0;
    $scope.other_deduction_remarks = "";
    $scope.additional_payment_remarks = "";
    $scope.employees = [];
    $scope.months = {};
    $scope.empAttendanceRecord = {};

    $scope.removeEmployeeFromGroup = function(obj){
        $scope.employees.push(obj);
        /*var arr = [];
        angular.forEach($scope.pgEditModel.employee_details, function (value, key) {
            if(value.id != obj.id){
                arr.push(value);
            }
        });
        $scope.pgEditModel.employee_details = arr;*/
        var index = $scope.pgEditModel.employee_details.indexOf(obj);
        $scope.pgEditModel.employee_details.splice(index, 1);
    };

    $scope.updatePayrollGroup = function(){
        //console.log($scope.editEmp);
        //console.log($scope.pgEditModel);
        angular.forEach($scope.editEmp, function (value, key) {
            var arr = {"id":value,"name":"New-Employee","avatar":"profile.png"};
            $scope.pgEditModel.employee_details.push(arr);
        });
        
        Loading("#editpayrollgroup-contents", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'payroll/updatePayrollGroup', $scope.pgEditModel, config).then(
            function (success) {
                Loading("#editpayrollgroup-contents", '<?php echo lang("loading_datatable") ?>', "", "hide");
                showNotification(success.data.status, success.data.message, success.data.status);
                setTimeout(function(){
                    if(success.data.status == 'success'){
                        $window.location.reload();
                    }
                }, 100);
            }, 
            function(error){
                Loading("#editpayrollgroup-contents", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };

    $scope.initEmployees = function (){
        $http.post(base_url + 'payroll/fetchSchoolEmployeesForPayroll', "", config).then(
            function (success) {
                $scope.employees = success.data.data;
            }, 
            function(error){
                console.log(error.data);
            }
        );
    };

    $scope.initSalaryGroups = function(){
        $http.post(base_url + 'payroll/fetchPayrollGroupsOfSchool', "", config).then(
            function (success) {
                $scope.payrollGroups = success.data.data;
            }, 
            function(error){
                console.log(error.data);
            }
        );
    };

    $scope.updatePayrollGroupVarient = function (){
        Loading("#varientSalaryTypeEditForm", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'payroll/updatePayrollGroupVarient', $scope.estvModel, config).then(
            function (success) {
                Loading("#varientSalaryTypeEditForm", '<?php echo lang("loading_datatable") ?>', "", "hide");
                showNotification(success.data.status, success.data.message, success.data.status);
                $scope.setSalaryVarientForm(false);
            }, 
            function(error){
                Loading("#varientSalaryTypeEditForm", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };

    $scope.setSalaryVarientForm = function (val, obj){
        if(obj){
            $scope.estvModel = obj;
            $scope.estvModel.amount = parseFloat($scope.estvModel.amount);    
        }
        $scope.isVarientFormEditShow = val;
    };
        
    $scope.initSalaryTypes = function(){
        Loading("#salary", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'payroll/fetchSalaryTypesOfSchool', "", config).then(
            function (success) {
                Loading("#salary", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.salaryTypes = success.data.data;
            }, 
            function(error){
                Loading("#salary", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };

    $scope.showPayrollSalaryTypeEditModel = function(obj){
        $scope.myModel2 = obj;
        $scope.myModel2.amount = parseInt($scope.myModel2.amount);
        $("#salaryTypeEditModal").modal("show");
    };

    $scope.showPayrollGroupEditModel = function(obj){
        $scope.pgEditModel = angular.copy(obj);
        $("#payrollGroupEditModal").modal("show");
    };

    $scope.showSalaryTypeAddModal = function(){
        $("#salaryTypeAddModal").modal("show");
    };

    $scope.showPayrollGroupAddModal = function(){
        $("#payrollGroupAddModal").modal("show");
    };
    

    $scope.showPayrollSalaryTypeVarientModal = function(obj){
        $scope.selectedSalaryType = obj;
        $scope.getSalaryTypeVarients();
        $scope.setSalaryVarientForm(false, {});
    };

    $scope.getSalaryTypeVarients = function (){
        Loading("#salartypevarientmodal-contents", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'payroll/fetchSalaryTypesVarients', $scope.selectedSalaryType, config).then(
            function (success) {
                Loading("#salartypevarientmodal-contents", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.selectedSalaryTypeVarients = success.data.data;
                $("#salaryTypeVarientModal").modal("show");
            }, 
            function(error){
                Loading("#salartypevarientmodal-contents", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };

    $scope.setVarientFormValue = function(val){
        $scope.isVarientFormShow = val;
        $scope.setSalaryVarientForm(false, {});
    };

    $scope.deleteSalaryTypeVarient = function (id){
        swal({
            title: '<?php echo lang("are_you_sure") ?>',
            text: '<?php echo lang("delete_message") ?>',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55", confirmButtonText: '<?php echo lang("yes_delete") ?>',
            cancelButtonText: '<?php echo lang("btn_cancel") ?>',
            closeOnConfirm: true,
            closeOnCancel: true
        },
        function (isConfirm) {
            if (isConfirm) {
            $http.post(base_url + "payroll/softDeleteSalaryTypeVarient", {"id": id}, config).then(
                    function (success) {
                        showNotification(success.data.status, success.data.message, success.data.status);
                        if (success.data.status === "success") {
                            $scope.getSalaryTypeVarients();
                        }
                    },
                    function (error) {
                        console.log(error.data);
                    }
                );
            }
        });
    };

    $scope.saveSalaryTypeVarient = function (){
        $scope.stvModel.payroll_group_id = $scope.selectedSalaryType.id;
        Loading("#salartypevarientmodal-contents", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'payroll/saveSalaryVarient', $scope.stvModel, config).then(
            function (success) {
                Loading("#salartypevarientmodal-contents", '<?php echo lang("loading_datatable") ?>', "", "hide");
                showNotification(success.data.status, success.data.message, success.data.status);
                if(success.data.status == "success"){
                    $scope.stvModel = {};
                    //$scope.varientSalaryTypeForm.$setUntouched();
                    //$scope.varientSalaryTypeForm.$setPristine();
                    $scope.setVarientFormValue(false);
                    $scope.getSalaryTypeVarients();
                }
            }, 
            function(error){
                Loading("#salartypevarientmodal-contents", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };

    $scope.addSalaryType = function (){
        Loading("#addsalarytype-contents", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'payroll/addNewSalaryType', $scope.stModel, config).then(
            function (success) {
                Loading("#addsalarytype-contents", '<?php echo lang("loading_datatable") ?>', "", "hide");
                angular.forEach(success.data, function (value, key) {
                    showNotification(value.status, value.message, value.status);
                });
                $("#salaryTypeAddModal").modal("hide");
                $scope.initSalaryTypes();
                $scope.stModel = {};
            }, 
            function(error){
                Loading("#addsalarytype-contents", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };

    $scope.addPayrollGroup = function (){
        Loading("#addpayrollgroup-contents", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'payroll/addNewPayrollGroup', $scope.pgModel, config).then(
            function (success) {
                Loading("#addpayrollgroup-contents", '<?php echo lang("loading_datatable") ?>', "", "hide");
                showNotification(success.data.status, success.data.message, success.data.status);
                if(success.data.status == "success"){
                    $("#payrollGroupAddModal").modal("hide");
                    $scope.pgModel = {};
                    $scope.initSalaryGroups();
                    $scope.initEmployees();
                }
            }, 
            function(error){
                Loading("#addpayrollgroup-contents", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };

    $scope.updateSalaryType = function (){
        Loading("#editsalarytype-contents", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'payroll/updateSalaryType', $scope.myModel2, config).then(
            function (success) {
                Loading("#editsalarytype-contents", '<?php echo lang("loading_datatable") ?>', "", "hide");
                showNotification(success.data.status, success.data.message, success.data.status);
                if(success.data.status == "success"){
                    $("#salaryTypeEditModal").modal("hide");
                    $scope.initSalaryTypes();
                }
            }, 
            function(error){
                Loading("#editsalarytype-contents", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    }

    $scope.initDepertments = function(){
        Loading("#payrollFilterDepartments", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'employee/getDepartments', "", config).then(
            function (success) {
                Loading("#payrollFilterDepartments", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.departments = success.data.departments;
                //console.log($scope.departments[0].id);
                //$scope.filterModel.department_id = ;
            }, 
            function(error){
                Loading("#payrollFilterDepartments", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };
    
    $scope.getDepartmentCategories = function(id){
        Loading("#payrollFilterCategories", '<?php echo lang("loading_datatable") ?>', "", "show");
        if(id == 'all'){
            Loading("#payrollFilterCategories", '<?php echo lang("loading_datatable") ?>', "", "hide");
            $scope.filterModel.category_id = 'all';
            $scope.categories = {};
        } else {
            $http.post(base_url + 'employee/getCategories2', {id:id}, config).then(
                function (success) {
                    Loading("#payrollFilterCategories", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.deptCategories = success.data.categories;
                }, 
                function(error){
                    Loading("#emp_categories", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
            );
        }
    };
    
    $scope.fetchPayrolls = function (valid) {
        if (valid) {
            Loading("#payroll_search_filter", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + "payroll/fetchPayrollEmployees", $scope.filterModel, config).then(
                function (success) {
                    $("#payrollContainer1").removeClass("hidden");
                    $(".payrollContainer2").addClass("hidden");
                    Loading("#payroll_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.payrollEmployees = success.data;
                },
                function (error) {
                    Loading("#payroll_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
            );
        }
    };

    $scope.showDetails = function (emp) {
        $("#payrollContainer1").addClass("hidden");
        $(".payrollContainer2").removeClass("hidden");
        $scope.selectedEmp = emp;
        $scope.fetchEmployeePayrollRecords($scope.selectedEmp);
        
        if($scope.selectedEmp.basic_salary == 0 && $scope.selectedEmp.payroll_group_id === null){
            $("#updateBasicSalaryModal").modal("show");
            $("#yasirPayrollBasicSalary").removeClass("custom_disable");
            $("#yasirPayrollGroups").removeClass("custom_disable");
        } else if($scope.selectedEmp.basic_salary != 0 && $scope.selectedEmp.payroll_group_id === null){
            $("#updateBasicSalaryModal").modal("show");
            $("#yasirPayrollGroups").removeClass("custom_disable");
            $scope.updateBasicSalary = parseInt($scope.selectedEmp.basic_salary);
        } else if($scope.selectedEmp.basic_salary == 0 && $scope.selectedEmp.payroll_group_id !== null){
            $("#updateBasicSalaryModal").modal("show");
            $("#yasirPayrollBasicSalary").removeClass("custom_disable");
            $scope.payrollGroupID = $scope.selectedEmp.payroll_group_id;
        }
            
        $('body').tooltip({
            selector: '[rel=tooltip]'
        });
    };

    $scope.updateSalary = function (){
        $scope.selectedEmp.basic_salary = $scope.updateBasicSalary;
        $scope.selectedEmp.payroll_group_id = $scope.payrollGroupID;
        Loading("#basicsalary-model-contents", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + "payroll/updateEmployeeBasicSalary", $scope.selectedEmp, config).then(
            function (success) {
                Loading("#basicsalary-model-contents", '<?php echo lang("loading_datatable") ?>', "", "hide");
                showNotification(success.data.status, success.data.message, success.data.status);
                $scope.fetchEmployeePayrollRecords($scope.selectedEmp);
                $scope.updateBasicSalary = "";
                $scope.payrollGroupID = "";
                $("#updateBasicSalaryModal").modal("hide");
            },
            function (error) {
                Loading("#basicsalary-model-contents", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };

    $scope.back = function () {
        $(".payrollContainer2").addClass("hidden");
        $("#payrollContainer1").removeClass("hidden");
        $scope.fetchPayrolls(true);
    };

    $scope.showEmployeeModal = function(){
        $("#bs-payroll-group-modal-sm").modal("show");
    };

    $scope.addEmployeeTemp = function(){
        console.log($scope.editEmp);
        $scope.employees.splice( $.inArray($scope.editEmp, $scope.employees), 1 );
        $scope.editEmp = "";
        $("#bs-payroll-group-modal-sm").modal("hide");
    };

    $scope.fetchEmployeePayrollRecords = function (obj) {
        var formData = {
            emp_id: obj.id, 
            department_id: obj.department_id, 
            category_id: obj.category_id,
            basic_salary: obj.basic_salary
        };
        $http.post(base_url + "payroll/getEmpPayrollRecrods", formData, config).then(
            function (success) {
                $scope.empPayrollRecords = success.data;
                $scope.total_basic_salary=0;
                $scope.total_allowances=0;
                $scope.total_deductions=0;
                $scope.total_gross_salary=0;
                $scope.total_paid_amount=0;
                $scope.total_balance =0;
                angular.forEach(success.data.data, function (value, key) {
                    $scope.total_basic_salary += parseInt(value.amount);
                    $scope.total_allowances += parseFloat(value.allowance);
                    $scope.total_deductions += parseFloat(value.deducation);
                    $scope.total_gross_salary += parseFloat(value.payable_amount);
                    $scope.total_paid_amount += parseFloat(value.total_paid_amount);
                    $scope.total_balance += parseFloat(value.balance);
                });
                $scope.initMonths();
            },
            function (error) {
                console.log(error.data);
            }
        );
    };

    $scope.loadDataInModel = function (obj){
        $scope.afcModel = obj;
        $scope.paid_amount=$scope.afcModel.payable_amount-$scope.other_deducation+$scope.additional_payment;
    };

    $scope.collectPayroll = function (valid) {
        if (valid) {
            $scope.afcModel.paid_amount = $scope.paid_amount;
            $scope.afcModel.payable_amount = $scope.afcModel.payable_amount-$scope.other_deducation+$scope.additional_payment;
            $scope.afcModel.mode = $scope.mode;
            $scope.afcModel.comment = $scope.comment;
            $scope.afcModel.other_deductions = $scope.other_deducation;
            $scope.afcModel.additional_payment = $scope.additional_payment;
            $scope.afcModel.other_deduction_remarks = $scope.other_deduction_remarks;
            $scope.afcModel.additional_payment_remarks = $scope.additional_payment_remarks;
            $scope.afcModel.is_send_email_to_employee = $scope.isSendEmailToEmployee;
            $scope.afcModel.basic_salary = parseInt($scope.selectedEmp.basic_salary);
            $http.post(base_url + "payroll/collect", $scope.afcModel, config).then(
                function (success) {
                     if (success.data.status === "success") {
                        $scope.loading = false;
                        $('#payrollAddModel').modal('hide');
                        $scope.mode = 'cash';
                        $scope.paid_amount = "";
                        $scope.comment = "";
                        $scope.other_deducation = 0;
                        $scope.additional_payment = 0;
                        $scope.other_dedection_remarks = "";
                        $scope.additional_payment_remarks = "";
                        $scope.payrollAddModelForm.$setUntouched();
                        $scope.payrollAddModelForm.$setPristine();
                        $scope.fetchEmployeePayrollRecords($scope.selectedEmp);
                        $scope.isSendEmailToEmployee = false;
                        showNotification(success.data.status, success.data.message, success.data.status);
                    }
                },
                function (error) {
                    $scope.loading = false;
                    console.log(error.data);
                }
            );
        }
    };

    $scope.showRemarksModal = function (obj){
        $("#payrollRemarksModal").modal("show");
        var records = obj.payroll_record;
        var remarks = "";
        var dremarks = "";
        angular.forEach(records, function (value, key) {
            if(value.remarks != "") {
                remarks += "<li class='list-group-item' style='border-radius: 0px 15px 15px 15px;'>"+value.remarks+"</li>";
            }
            if(value.dedection_remarks != ""){
                dremarks += "<li class='list-group-item' style='border-radius: 0px 15px 15px 15px;'>"+value.dedection_remarks+"</li>";
            }
        });
        $("#payrollRemarks").html(remarks);
        $("#payrollDeducationRemarks").html(dremarks);
    };

    $scope.showPartiallyPayrollDeatils = function(obj){
        $scope.partiallyPayrollDetailModel= obj;
    };

    $scope.setEditPayrollCollectionModel = function (obj) {
        $scope.editModel = obj;
        $scope.uModel.paid_amount = -1*$scope.editModel.balance;
        $scope.uModel.mode = 'cash';
        $scope.uModel.comment = '';
        $scope.uModel.due_date = $scope.editModel.due_date;
        $scope.uModel.sendEmailToEmployee = false;
    };
    
    
    $scope.collectPayrollUpdate = function (valid) {
        $scope.uModel.deducation = $scope.editModel.deducation;
        $scope.uModel.basic_salary = $scope.editModel.basic_salary; 
        $scope.uModel.payable_amount = $scope.editModel.payable_amount; 
        $scope.uModel.salary_type_id = $scope.editModel.salary_type_id;
        $scope.uModel.employee_id = $scope.editModel.employee_id;
        $scope.uModel.allowance = $scope.editModel.allowance;
        $scope.uModel.balance = $scope.editModel.balance;
        $scope.uModel.is_send_email_to_employee = $scope.uModel.sendEmailToEmployee;
         
        $scope.uModel.payroll_record = [{
            'payroll_id': $scope.editModel.payroll_record[0].payroll_id
        }];
        $scope.uModel.total_paid_amount = $scope.editModel.total_paid_amount;
        $scope.update_loading = true;
        if (valid) {
            $http.post(base_url + "payroll/collect", $scope.uModel, config).then(
                    function (success) {
                        if (success.data.status === "success") {
                            $scope.update_loading = false;
                            $scope.payrollEditModelForm.$setUntouched();
                            $scope.payrollEditModelForm.$setPristine();
                            $('#payrollEditModel').modal('hide');
                            $scope.uModel = {};
                            showNotification(success.data.status, success.data.message, success.data.status);
                            $scope.fetchEmployeePayrollRecords($scope.selectedEmp); 
                        }
                    },
                    function (error) {
                        $scope.update_loading = false;
                        console.log(error.data);
                    }
            );
        }
    };
    

    $scope.showConfirmationAlertForSalaryType = function (id) {
        swal({
            title: '<?php echo lang("are_you_sure") ?>',
            text: '<?php echo lang("delete_message") ?>',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55", confirmButtonText: '<?php echo lang("yes_delete") ?>',
            cancelButtonText: '<?php echo lang("btn_cancel") ?>',
            closeOnConfirm: true,
            closeOnCancel: true
        },
        function (isConfirm) {
            if (isConfirm) {
            $http.post(base_url + "payroll/softDeleteSalaryType", {"id": id}, config).then(
                    function (success) {
                        showNotification(success.data.status, success.data.message, success.data.status);
                        if (success.data.status === "success") {
                            $scope.initSalaryTypes();
                        }
                    },
                    function (error) {
                        console.log(error.data);
                    }
                );
            }
        });
    };

    $scope.showConfirmationAlertForPayrollGroup = function (id) {
        swal({
            title: '<?php echo lang("are_you_sure") ?>',
            text: '<?php echo lang("delete_message") ?>',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55", confirmButtonText: '<?php echo lang("yes_delete") ?>',
            cancelButtonText: '<?php echo lang("btn_cancel") ?>',
            closeOnConfirm: true,
            closeOnCancel: true
        },
        function (isConfirm) {
            if (isConfirm) {
            $http.post(base_url + "payroll/softDeletePayrollGroup", {"id": id}, config).then(
                    function (success) {
                        showNotification(success.data.status, success.data.message, success.data.status);
                        if (success.data.status === "success") {
                            setTimeout(function(){
                                $window.location.reload();
                            },100);
                        }
                    },
                    function (error) {
                        console.log(error.data);
                    }
                );
            }
        });
    };

    $scope.showConfirmationAlert = function (obj, how_many) {
        var ids = [];
        if(how_many === 'all'){
            angular.forEach(obj.payroll_record, function (value, key) {
                ids.push(value.payroll_id);
            });
        } else if(how_many === 'null'){
            ids.push(obj.payroll_id);
        }
        swal({
            title: '<?php echo lang("are_you_sure") ?>',
            text: '<?php echo lang("delete_message_for_collected_payroll") ?>',
            type: "error",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55", confirmButtonText: '<?php echo lang("yes_delete") ?>',
            cancelButtonText: '<?php echo lang("btn_cancel") ?>',
            closeOnConfirm: true,
            closeOnCancel: true
        },
        function (isConfirm) {
            if (isConfirm) {
            $http.post(base_url + "payroll/softDelete", {"ids": ids, 'type':how_many}, config).then(
                    function (success) {
                        if (success.data === "success") {
                            if(how_many == 'null'){
                                $("#payrollInfoModel").modal("hide");
                            }
                            $scope.fetchEmployeePayrollRecords($scope.selectedEmp);
                        }
                    },
                    function (error) {
                        console.log(error.data);
                    }
                );
            }
        });
    };
    
    $scope.initMonths = function(){
        Loading("#arFilterMonth", '<?php echo lang("loading_datatable") ?>', "", "hide");
        $http.post(base_url + 'attendance/count_academic_year_months', "", config).then(
            function (success) {
                Loading("#arFilterMonth", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.months = success.data;
                angular.forEach($scope.months, function (value, key) {
                    //console.log(key.split("to")[0].split("/")[1], getCurrentDate().split("/")[1], key.split("to")[0].split("/")[0], getCurrentDate().split("/")[2]);
                    if(key.split("to")[0].split("/")[1] == getCurrentDate().split("/")[1] && key.split("to")[0].split("/")[0] == getCurrentDate().split("/")[2]){
                        $scope.ymonth = key;
                        $scope.getEmployeeAttendanceRecord($scope.ymonth);
                    }
                });
            },
            function (error) {
                Loading("#arFilterMonth", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.status);
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };

    $scope.getEmployeeAttendanceRecord = function(month){
        Loading("#attandance_record_container", '<?php echo lang("loading_datatable") ?>', "", "show");
        var formData = {'employee_id':$scope.selectedEmp.id, 'month': month};
        $http.post(base_url + 'payroll/getEmployeeAttendanceReport', formData, config).then(
            function (success) {
                $scope.empAttendanceRecord = success.data.data;
                Loading("#attandance_record_container", '<?php echo lang("loading_datatable") ?>', "", "hide");
            },
            function (error) {
                Loading("#attandance_record_container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.status);
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };

    $scope.initAdmins = function(){
        Loading("#payrollFilterPaidBy", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'employee/getAdmins', "", config).then(
                function (success) {
                    Loading("#payrollFilterPaidBy", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.paid_by = success.data.paid_by;
                }, 
                function(error){
                    console.log(error.data);
                }
            );
    };

    $scope.initSalaryTypesSheraz = function(){
        Loading("#payrollFilterSalaryTypes", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'employee/getSalaryTypes', "", config).then(
                function (success) {
                    Loading("#payrollFilterSalaryTypes", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.salaryTypes = success.data.salaryTypes;
                }, 
                function(error){
                    console.log(error.data);
                }
            );
    };

    $scope.payrollReport = function(){
        $('#empReportTable').DataTable({
            "language": {

                "decimal":        "",
                "emptyTable":     '<?php echo lang("no_data_table"); ?>',
                "info":           '<?php echo lang("data_info"); ?>',
                "infoEmpty":      '<?php echo lang("infoempty"); ?>',
                "infoFiltered":   '<?php echo lang("filter_datatable"); ?>',
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     '<?php echo lang("show_datatable"); ?>',
                "loadingRecords": '<?php echo lang("loading_datatable"); ?>',
                "processing":     '<?php echo lang("processing_datatable"); ?>',
                "search":         '<?php echo lang("search"); ?>:',
                "zeroRecords":    '<?php echo lang("no_record_datatable"); ?>',
                "paginate": {
                    "first":      '<?php echo lang("first"); ?>',
                    "last":       '<?php echo lang("last"); ?>',
                    "next":       '<?php echo lang("btn_next"); ?>',
                    "previous":   '<?php echo lang("previous"); ?>'
                },
                "aria": {
                    "sortAscending":  ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                }
            },
            dom: 'Bfrtip',
            ajax: {
                type: "POST",
                url: base_url+'reports/fetchPayrollEmployees',
                data: {'formData':$scope.filterModel},
                dataSrc: '',
            },
            columns: [
                
                {title: 'Avatar', data: '', render : function (data, type, row) {
                    
                    return '<img src="<?php echo base_url(); ?>uploads/user/' +row.avatar+ '" class="img-circle" style="height: 60px;width: 60px" />';
                    
                    }
                },
                {title: 'name', data: 'name' },
                {title: 'Salary Type', data: 'salary_type_name' },
                {title: 'Paid By', data: 'admin_name' },
                {title: 'Status', data: '', render : function (data, type, row){
                        if (row.p_status == "1"){
                        return '<span class="text-success">Paid</span>';
                        } else if (row.p_status == "2"){
                        return '<span class="text-warning">Paritally Paid</span>';
                        }else {
                        return '<span class="text-danger">Unpaid</span>';
                    }
                    }
                },
                {title: 'Department', data: 'department_name' },
                {title: 'Category', data: 'category' },
                {title: 'Other Deduction', data: 'other_deductions' },
                {title: 'Additional Payment', data: 'additional_payment' },
                {title: 'Gross Salary', data: 'basic_salary' },
                {title: 'Paid Amount', data: 'amount_paid' },
                {title: 'Paid Date', data: 'date' },
                {title: 'Receipt No', data: 'receipt_no' },
                {title: 'Mode', data: 'mode' },
                {title: 'Remarks', data: 'p_remarks' }
                
            ],
            
                          
            buttons: [
                {
                extend: 'copyHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
                },
                {
                extend: 'excelHtml5'
                },
                {
                extend: 'csvHtml5'
                },
                 {
                extend: 'pdfHtml5'
                }
               ],
            destroy: true
        });
 
    };
});

app2.controller("accountController", function ($scope, $http,) {
    $scope.accounts = [];
    $scope.employees = [];
    $scope.today = '';
    $scope.deposits = [];
    $scope.withdraws = [];
    $scope.income_types = [];
    $scope.income_categories = [];
    $scope.expense_types = [];
    $scope.expense_categories = [];
    $scope.incomes = [];
    $scope.expenses = [];
    $scope.income_total = 0;
    $scope.expense_total = 0;
    $scope.currencies = [];
    $scope.default_currency = "";
    $scope.income = {
        income_id: '',
        income_category_id: '',
        date: $scope.today,
        amount: 0,
        currency: '',
        collected_by: '',
        comment: '',
        mode: 'cash',
        files: [],
        fixed: 'Yes'
    };
    $scope.expense = {
        expense_id: '',
        expense_category_id: '',
        date: $scope.today,
        amount: 0,
        currency: '',
        paid_by: '',
        comment: '',
        mode: 'cash',
        files: [],
        fixed: 'Yes'
    };
    $scope.deposit = {
        amount: 0,
        deposit_by: '',
        date: $scope.today,
        comment: '',
        collected_by: '',
        currency: '',
        mode: 'cash',
        account_id: 0,
        files: []
    };
    $scope.withdraw = {
        amount: 0,
        withdraw_by: '',
        date: $scope.today,
        comment: '',
        paid_by: '',
        currency: '',
        mode: 'cash',
        account_id: 0,
        files: [],
        balance: 0
    };
    $scope.getIncomeTypes = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/getIncomeTypes', '', config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.income_types = success.data.income_types;
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    }

    $scope.get_incomes_expenses = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/get_incomes_expenses', '', config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.incomes = success.data.incomes;
                    $scope.expenses = success.data.expenses;
                    $scope.income_total = success.data.income_total;
                    $scope.expense_total = success.data.expense_total;
                    $scope.currencies = success.data.currencies;
                    $scope.default_currency = success.data.default_currency;
                    $scope.fees = success.data.fees;
                    $scope.fee_total = success.data.fee_total;
                    $scope.fee_date = success.data.fee_date;
                    $scope.payrolls = success.data.payrolls;
                    $scope.payroll_date = success.data.payroll_date;
                    $scope.pay_total = success.data.pay_total;
                    $scope.fee_collected = success.data.fee_collected;
                    $scope.total_deductions = success.data.total_deductions;
                    $scope.payroll_amount = success.data.payroll_amount;
                    $scope.total_income = success.data.total_income;
                    $scope.total_expense = success.data.total_expense;
                    $scope.school_currency = success.data.school_currency;
                    $scope.fee_date_search = angular.copy($scope.fee_date);
                    $scope.payroll_date_search = angular.copy($scope.payroll_date);
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    }

    $scope.fetchfeedetails = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/fetchfeedetails', {date : $scope.fee_date_search}, config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.fees = success.data.fees;
                    $scope.fee_total = success.data.fee_total;
                    $scope.fee_date = success.data.fee_date;
                    $scope.fee_date_search = angular.copy($scope.fee_date);
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    }

    $scope.fetchfeesformodal = function(date,date_heading){
        $scope.fee_date_modal = date_heading;
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/fetchfeedetails', {date : date}, config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.fees_modal = success.data.fees;
                    $scope.fee_total_modal = success.data.fee_total;
                    $scope.fee_date_f = success.data.fee_date;
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    }

    $scope.fetchpayrolldetails = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/fetchpayrolldetails', {date : $scope.payroll_date_search}, config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.payrolls = success.data.payrolls;
                    $scope.pay_total = success.data.pay_total;
                    $scope.payroll_date = success.data.payroll_date;
                    $scope.payroll_date_search = angular.copy($scope.payroll_date);
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    }

    $scope.fetchpayrollformodal = function(date,date_heading){
        $scope.payroll_date_modal = date_heading;
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/fetchpayrolldetails', {date : date}, config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.payrolls_modal = success.data.payrolls;
                    $scope.payroll_total_modal = success.data.pay_total;
                    $scope.payroll_date_f = success.data.payroll_date;
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    }

    $scope.fetchdeductions = function(date,date_heading){
        $scope.deduction_date_modal = date_heading;
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/fetchdeductions', {date : date}, config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.deductions = success.data.deductions;
                    $scope.total_deductions = success.data.total_deductions;
                    $scope.deduction_date = success.data.deduction_date;
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    }

    $scope.getExpenseTypes = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/getExpenseTypes', '', config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.expense_types = success.data.expense_types;
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    }

    $scope.setMaximumIncome = function(id){
        obj = $scope.income_categories.find(o => o.id === id);
        $scope.income.max_price = obj.max_price;
        $scope.income.min_price = obj.min_price;
        if(obj.repeated == "Yes"){
            $scope.income.maximum = obj.price;
        }else{
            $scope.income.maximum = obj.price - obj.total;
        }
        
        $scope.income.fixed = obj.fixed;
    }

    $scope.setMaximumIncomeEdit = function(id){
        obj = $scope.editIncome.income_categories.find(o => o.id === id);
        $scope.editIncome.max_price = obj.max_price;
        $scope.editIncome.min_price = obj.min_price;
        if(obj.repeated == "Yes"){
            $scope.editIncome.maximum = obj.price;
        }else{
            $scope.editIncome.maximum = obj.price - obj.total;
        }
        $scope.editIncome.fixed = obj.fixed;
    }

    $scope.setMaximumExpense = function(id){
        obj = $scope.expense_categories.find(o => o.id === id);
        if(obj.repeated == "Yes"){
            $scope.expense.maximum = obj.price;
        }else{
            $scope.expense.maximum = obj.price - obj.total;
        }
        $scope.expense.fixed = obj.fixed;
    }

    $scope.setMaximumExpenseEdit = function(id){
        obj = $scope.editExpense.expense_categories.find(o => o.id === id);
        if(obj.repeated == "Yes"){
            $scope.editExpense.maximum = obj.price;
        }else{
            $scope.editExpense.maximum = obj.price - obj.total;
        }
        $scope.editExpense.fixed = obj.fixed;
    }

    $scope.getIncomeCategories = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/getIncomeCategories', {id:$scope.income.income_id}, config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.income_categories = success.data.income_categories;
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    }
    
    $scope.getExpenseCategories = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/getExpenseCategories', {id:$scope.expense.expense_id}, config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.expense_categories = success.data.expense_categories;
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    }
    $scope.getVirtualAccounts = function(id = false){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/getVirtualAccounts', '', config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.accounts = success.data.accounts;
                    if(id != false){
                        obj = $scope.accounts.find(o => o.account_id === id);
                        $scope.deposits = obj.deposits;
                        $scope.withdraws = obj.withdraws;
                    }
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    }

    $scope.setDetails = function(account_id,deposits, withdraws){
        $scope.account_id = account_id;
        $scope.deposits = deposits;
        $scope.withdraws = withdraws;
    }


    $scope.setCollectModal = function(){
        $scope.income = {
            income_id: '',
            income_category_id: '',
            date: $scope.today,
            amount: 0,
            currency: $scope.default_currency,
            collected_by: '',
            comment: '',
            mode: 'cash',
            files: [],
            fixed: 'Yes'
        };
        $scope.income_categories = [];
        $scope.income_form.$setUntouched();
        $scope.income_form.$setPristine();
        Dropzone.forElement("#my-awesome-dropzone3").removeAllFiles(true);
        $('.yasir-payroll-select2').val(null).trigger('change.select2');
        $('.currency-dropdown').val($scope.default_currency).trigger('change.select2');
    }

    $scope.setPayModal = function(){
        $scope.expense = {
            expense_id: '',
            expense_category_id: '',
            date: $scope.today,
            amount: 0,
            currency: $scope.default_currency,
            collected_by: '',
            comment: '',
            mode: 'cash',
            files: [],
            fixed: 'Yes'
        };
        $scope.expense_categories = [];
        $scope.deposit_form.$setUntouched();
        $scope.deposit_form.$setPristine();
        Dropzone.forElement("#my-awesome-dropzone4").removeAllFiles(true);
        $('.yasir-payroll-select2').val(null).trigger('change.select2');
        $('.currency-dropdown').val($scope.default_currency).trigger('change.select2');
    }


    $scope.depositDropzoneConfig = {
        'options': {// passed into the Dropzone constructor
            'url': base_url + 'study_material/upload_attachments',
            'autoProcessQueue': true,
            'autoDiscover': false,
            'uploadMultiple': true,
            'parallelUploads': 10,
            'maxFilesize': 10, //MB
            'maxFiles': 100,
            'acceptedFiles': "image/*, application/pdf, .doc, .docx, .ppt, pptx",
            'addRemoveLinks': true,
            'dictRemoveFile': '<?php echo lang("lbl_remove");?>',
            'dictDefaultMessage': '<?php echo lang("drop_here");?>',
            'dictFileTooBig': 'File is bigger than 10MB',
            init: function () {
                var submitButton = document.querySelector("#depositBtn");
                myDropzone = this;
                submitButton.addEventListener("click", function () {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                    

                    $scope.newDeposit();
                    
                });
                myDropzone.on("complete", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
                myDropzone.on("addedfile", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                });
                myDropzone.on("removedfile", function (file) {
                    $scope.deposit.files = $scope.deposit.files.filter(function( obj ) {
                        return obj.name !== file.name;
                        $scope.$apply();
                    });
                    
                });
            }
        },
        'eventHandlers': {
            'sending': function (file, xhr, formData) {
            },
            'success': function (file, response) {
                $scope.deposit.files = $scope.deposit.files.concat(JSON.parse(response));
                $scope.$apply();
            }
        }
    };

    $scope.incomeDropzoneConfig = {
        'options': {// passed into the Dropzone constructor
            'url': base_url + 'study_material/upload_attachments',
            'autoProcessQueue': true,
            'autoDiscover': false,
            'uploadMultiple': true,
            'parallelUploads': 10,
            'maxFilesize': 10, //MB
            'maxFiles': 100,
            'acceptedFiles': "image/*, application/pdf, .doc, .docx, .ppt, pptx",
            'addRemoveLinks': true,
            'dictRemoveFile': '<?php echo lang("lbl_remove");?>',
            'dictDefaultMessage': '<?php echo lang("drop_here");?>',
            'dictFileTooBig': 'File is bigger than 10MB',
            init: function () {
                var submitButton = document.querySelector("#incomeBtn");
                myDropzone = this;
                submitButton.addEventListener("click", function () {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                    

                    $scope.newIncome();
                    
                });
                myDropzone.on("complete", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
                myDropzone.on("addedfile", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                });
                myDropzone.on("removedfile", function (file) {
                    $scope.income.files = $scope.income.files.filter(function( obj ) {
                        return obj.name !== file.name;
                        $scope.$apply();
                    });
                    
                });
            }
        },
        'eventHandlers': {
            'sending': function (file, xhr, formData) {
            },
            'success': function (file, response) {
                $scope.income.files = $scope.income.files.concat(JSON.parse(response));
                $scope.$apply();
            }
        }
    };

    $scope.editIncomeDropzoneConfig = {
        'options': {// passed into the Dropzone constructor
            'url': base_url + 'study_material/upload_attachments',
            'autoProcessQueue': true,
            'autoDiscover': false,
            'uploadMultiple': true,
            'parallelUploads': 10,
            'maxFilesize': 10, //MB
            'maxFiles': 100,
            'acceptedFiles': "image/*, application/pdf, .doc, .docx, .ppt, pptx",
            'addRemoveLinks': true,
            'dictRemoveFile': '<?php echo lang("lbl_remove");?>',
            'dictDefaultMessage': '<?php echo lang("drop_here");?>',
            'dictFileTooBig': 'File is bigger than 10MB',
            init: function () {
                var submitButton = document.querySelector("#editIncomeBtn");
                myDropzone = this;
                submitButton.addEventListener("click", function () {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                    

                    $scope.updateIncome();
                    
                });
                myDropzone.on("complete", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
                myDropzone.on("addedfile", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                });
                myDropzone.on("removedfile", function (file) {
                    $scope.editIncome.files = $scope.editIncome.files.filter(function( obj ) {
                        return obj.name !== file.name;
                        $scope.$apply();
                    });
                    
                });
            }
        },
        'eventHandlers': {
            'sending': function (file, xhr, formData) {
            },
            'success': function (file, response) {
                $scope.editIncome.files = $scope.editIncome.files.concat(JSON.parse(response));
                $scope.$apply();
            }
        }
    };

    $scope.expenseDropzoneConfig = {
        'options': {// passed into the Dropzone constructor
            'url': base_url + 'study_material/upload_attachments',
            'autoProcessQueue': true,
            'autoDiscover': false,
            'uploadMultiple': true,
            'parallelUploads': 10,
            'maxFilesize': 10, //MB
            'maxFiles': 100,
            'acceptedFiles': "image/*, application/pdf, .doc, .docx, .ppt, pptx",
            'addRemoveLinks': true,
            'dictRemoveFile': '<?php echo lang("lbl_remove");?>',
            'dictDefaultMessage': '<?php echo lang("drop_here");?>',
            'dictFileTooBig': 'File is bigger than 10MB',
            init: function () {
                var submitButton = document.querySelector("#expenseBtn");
                myDropzone = this;
                submitButton.addEventListener("click", function () {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                    

                    $scope.newExpense();
                    
                });
                myDropzone.on("complete", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
                myDropzone.on("addedfile", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                });
                myDropzone.on("removedfile", function (file) {
                    $scope.expense.files = $scope.expense.files.filter(function( obj ) {
                        return obj.name !== file.name;
                        $scope.$apply();
                    });
                    
                });
            }
        },
        'eventHandlers': {
            'sending': function (file, xhr, formData) {
            },
            'success': function (file, response) {
                $scope.expense.files = $scope.expense.files.concat(JSON.parse(response));
                $scope.$apply();
            }
        }
    };


    $scope.expenseEditDropzoneConfig = {
        'options': {// passed into the Dropzone constructor
            'url': base_url + 'study_material/upload_attachments',
            'autoProcessQueue': true,
            'autoDiscover': false,
            'uploadMultiple': true,
            'parallelUploads': 10,
            'maxFilesize': 10, //MB
            'maxFiles': 100,
            'acceptedFiles': "image/*, application/pdf, .doc, .docx, .ppt, pptx",
            'addRemoveLinks': true,
            'dictRemoveFile': '<?php echo lang("lbl_remove");?>',
            'dictDefaultMessage': '<?php echo lang("drop_here");?>',
            'dictFileTooBig': 'File is bigger than 10MB',
            init: function () {
                var submitButton = document.querySelector("#editExpenseBtn");
                myDropzone = this;
                submitButton.addEventListener("click", function () {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                    

                    $scope.updateExpense();
                    
                });
                myDropzone.on("complete", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
                myDropzone.on("addedfile", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                });
                myDropzone.on("removedfile", function (file) {
                    $scope.editExpense.files = $scope.editExpense.files.filter(function( obj ) {
                        return obj.name !== file.name;
                        $scope.$apply();
                    });
                    
                });
            }
        },
        'eventHandlers': {
            'sending': function (file, xhr, formData) {
            },
            'success': function (file, response) {
                $scope.editExpense.files = $scope.editExpense.files.concat(JSON.parse(response));
                $scope.$apply();
            }
        }
    };

   

    $scope.withdrawDropzoneConfig = {
        'options': {// passed into the Dropzone constructor
            'url': base_url + 'study_material/upload_attachments',
            'autoProcessQueue': true,
            'autoDiscover': false,
            'uploadMultiple': true,
            'parallelUploads': 10,
            'maxFilesize': 10, //MB
            'maxFiles': 100,
            'acceptedFiles': "image/*, application/pdf, .doc, .docx, .ppt, pptx",
            'addRemoveLinks': true,
            'dictRemoveFile': '<?php echo lang("lbl_remove");?>',
            'dictDefaultMessage': '<?php echo lang("drop_here");?>',
            'dictFileTooBig': 'File is bigger than 10MB',
            init: function () {
                var submitButton = document.querySelector("#withdrawBtn");
                myDropzone = this;
                submitButton.addEventListener("click", function () {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                    

                    $scope.newWithdraw();
                    
                });
                myDropzone.on("complete", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
                myDropzone.on("addedfile", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                });
                myDropzone.on("removedfile", function (file) {
                    $scope.withdraw.files = $scope.withdraw.files.filter(function( obj ) {
                        return obj.name !== file.name;
                        $scope.$apply();
                    });
                });
            }
        },
        'eventHandlers': {
            'sending': function (file, xhr, formData) {
            },
            'success': function (file, response) {
                $scope.withdraw.files = $scope.withdraw.files.concat(JSON.parse(response));
                $scope.$apply();
            }
        }
    };


    $scope.editDepositDropzoneConfig = {
        'options': {// passed into the Dropzone constructor
            'url': base_url + 'study_material/upload_attachments',
            'autoProcessQueue': true,
            'autoDiscover': false,
            'uploadMultiple': true,
            'parallelUploads': 10,
            'maxFilesize': 10, //MB
            'maxFiles': 100,
            'acceptedFiles': "image/*, application/pdf, .doc, .docx, .ppt, pptx",
            'addRemoveLinks': true,
            'dictRemoveFile': '<?php echo lang("lbl_remove");?>',
            'dictDefaultMessage': '<?php echo lang("drop_here");?>',
            'dictFileTooBig': 'File is bigger than 10MB',
            init: function () {
                var submitButton = document.querySelector("#editDepositBtn");
                myDropzone = this;
                submitButton.addEventListener("click", function () {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                    

                    $scope.updateDeposit();
                    
                });
                myDropzone.on("complete", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
                myDropzone.on("addedfile", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                });
                myDropzone.on("removedfile", function (file) {
                    $scope.depositEdit.files = $scope.depositEdit.files.filter(function( obj ) {
                        return obj.name !== file.name;
                        $scope.$apply();
                    });
                });
            }
        },
        'eventHandlers': {
            'sending': function (file, xhr, formData) {
            },
            'success': function (file, response) {
                $scope.depositEdit.files = $scope.depositEdit.files.concat(JSON.parse(response));
                $scope.$apply();
            }
        }
    };

    $scope.withdrawEditDropzoneConfig = {
        'options': {// passed into the Dropzone constructor
            'url': base_url + 'study_material/upload_attachments',
            'autoProcessQueue': true,
            'autoDiscover': false,
            'uploadMultiple': true,
            'parallelUploads': 10,
            'maxFilesize': 10, //MB
            'maxFiles': 100,
            'acceptedFiles': "image/*, application/pdf, .doc, .docx, .ppt, pptx",
            'addRemoveLinks': true,
            'dictRemoveFile': '<?php echo lang("lbl_remove");?>',
            'dictDefaultMessage': '<?php echo lang("drop_here");?>',
            'dictFileTooBig': 'File is bigger than 10MB',
            init: function () {
                var submitButton = document.querySelector("#editWithdrawBtn");
                myDropzone = this;
                submitButton.addEventListener("click", function () {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                    

                    $scope.updateWithdraw();
                    
                });
                myDropzone.on("complete", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
                myDropzone.on("addedfile", function (file) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                });
                myDropzone.on("removedfile", function (file) {
                    $scope.withdrawEdit.files = $scope.withdrawEdit.files.filter(function( obj ) {
                        return obj.name !== file.name;
                        $scope.$apply();
                    });
                });
            }
        },
        'eventHandlers': {
            'sending': function (file, xhr, formData) {
            },
            'success': function (file, response) {
                $scope.withdrawEdit.files = $scope.withdrawEdit.files.concat(JSON.parse(response));
                $scope.$apply();
            }
        }
    };

    $scope.removeDepositFile = function(name){
        $scope.depositEdit.old_files = $scope.depositEdit.old_files.filter(function( obj ) {
                        return obj.name !== name;
                        $scope.$apply();
                    });
    }

    $scope.removeExpenseFile = function(name){
        $scope.editExpense.old_files = $scope.editExpense.old_files.filter(function( obj ) {
                        return obj.name !== name;
                        $scope.$apply();
                    });
    }

    $scope.setIncomeDeleteId = function(id){
        $scope.deleteIncomeId = id;
    }

    $scope.setExpenseDeleteId = function(id){
        $scope.deleteExpenseId = id;
    }

    $scope.setDepositDeleteId = function(id){
        $scope.deleteDepositId = id;
    }

    $scope.setWithdrawDeleteId = function(id){
        $scope.deleteWithdrawId = id;
    }

    $scope.deleteIncome = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/deleteIncome', {id: $scope.deleteIncomeId}, config).then(
                function (success) {
                    $scope.get_incomes_expenses();
                    $('#deleteIncome').modal('hide');
                    showNotification('Success', success.data.message, 'success');
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    }

    $scope.deleteExpense = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/deleteExpense', {id: $scope.deleteExpenseId}, config).then(
                function (success) {
                    $scope.get_incomes_expenses();
                    $('#deleteExpense').modal('hide');
                    showNotification('Success', success.data.message, 'success');
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    }

    $scope.deleteDeposit = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/deleteDeposit', {id: $scope.deleteDepositId}, config).then(
                function (success) {
                    $scope.getVirtualAccounts($scope.account_id);
                    $('#deleteDeposit').modal('hide');
                    $('#detailsModal').modal('toggle');
                    showNotification('Success', success.data.message, 'success');
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    }

    $scope.deleteWithdraw = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/deleteWithdraw', {id: $scope.deleteWithdrawId}, config).then(
                function (success) {
                    $scope.getVirtualAccounts($scope.account_id);
                    $('#deleteWithdraw').modal('hide');
                    $('#detailsModal').modal('toggle');
                    showNotification('Success', success.data.message, 'success');
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    }

    $scope.removeIncomeFile = function(name){
        $scope.editIncome.old_files = $scope.editIncome.old_files.filter(function( obj ) {
                        return obj.name !== name;
                        $scope.$apply();
                    });
    }

    $scope.removeWithdrawFile = function(name){
        $scope.withdrawEdit.old_files = $scope.withdrawEdit.old_files.filter(function( obj ) {
                        return obj.name !== name;
                        $scope.$apply();
                    });
    }

    $scope.getDate = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/getDate', '', config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.today = success.data.date;
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    }

    $scope.updateDeposit = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/updateDeposit', $scope.depositEdit, config).then(
                function (success) {
                    $scope.getVirtualAccounts($scope.account_id);
                    Dropzone.forElement("#my-awesome-dropzone5").removeAllFiles(true);
                    $scope.edit_deposit_form.$setUntouched();
                    $scope.edit_deposit_form.$setPristine();
                    $('#editDepositModal').modal('hide');
                    $('#detailsModal').modal('toggle');
                    showNotification('Success', success.data.message, 'success');
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    }

    $scope.updateWithdraw = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/updateWithdraw', $scope.withdrawEdit, config).then(
                function (success) {
                    $scope.getVirtualAccounts($scope.account_id);
                    Dropzone.forElement("#my-awesome-dropzone6").removeAllFiles(true);
                    $scope.edit_withdraw_form.$setUntouched();
                    $scope.edit_withdraw_form.$setPristine();
                    $('#editWithdrawModal').modal('hide');
                    $('#detailsModal').modal('toggle');
                    showNotification('Success', success.data.message, 'success');
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    }

    $scope.getAllData = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/getAllData', '', config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.accounts = success.data.accounts;
                    $scope.employees = success.data.employees;
                    $scope.today = success.data.date;
                    $scope.income_types = success.data.income_types;
                    $scope.expense_types = success.data.expense_types;
                    $scope.incomes = success.data.incomes;
                    $scope.expenses = success.data.expenses;
                    $scope.income_total = success.data.income_total;
                    $scope.expense_total = success.data.expense_total;
                    $scope.currencies = success.data.currencies;
                    $scope.default_currency = success.data.default_currency;
                    $scope.fees = success.data.fees;
                    $scope.fee_total = success.data.fee_total;
                    $scope.fee_date = success.data.fee_date;
                    $scope.payrolls = success.data.payrolls;
                    $scope.payroll_date = success.data.payroll_date;
                    $scope.pay_total = success.data.pay_total;
                    $scope.fee_collected = success.data.fee_collected;
                    $scope.total_deductions = success.data.total_deductions;
                    $scope.payroll_amount = success.data.payroll_amount;
                    $scope.total_income = success.data.total_income;
                    $scope.total_expense = success.data.total_expense;
                    $scope.school_currency = success.data.school_currency;
                    $scope.fee_date_search = angular.copy($scope.fee_date);
                    $scope.payroll_date_search = angular.copy($scope.payroll_date);
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    }

    $scope.getEmployees = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/getEmployees', '', config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.employees = success.data.employees;
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    }

    $scope.setDepositEditData = function(d){
        $scope.depositEdit = angular.copy(d);
        var check = false;
        angular.forEach($scope.currencies, function (value, key) {
            if(value.currency_id == $scope.depositEdit.currency){
                check = true;
            }
        });
        if(check == false){
            $scope.depositEdit.currency = "";
        }
        $scope.depositEdit.amount = parseInt($scope.depositEdit.amount);
        $scope.depositEdit.files = [];
        Dropzone.forElement("#my-awesome-dropzone5").removeAllFiles(true);
        $('.yasir-payroll-select2').val($scope.depositEdit.deposit_by).trigger('change.select2');
        $('.currency-dropdown').val($scope.depositEdit.currency).trigger('change.select2');
        $scope.edit_deposit_form.$setUntouched();
        $scope.edit_deposit_form.$setPristine();
    }

    $scope.setCollectEditData = function(i){
        $scope.editIncome = angular.copy(i);
        var check = false;
        angular.forEach($scope.currencies, function (value, key) {
            if(value.currency_id == $scope.editIncome.currency){
                check = true;
            }
        });
        if(check == false){
            $scope.editIncome.currency = "";
        }
        $scope.editIncome.amount = parseInt($scope.editIncome.amount);
        $scope.editIncome.files = [];
        Dropzone.forElement("#my-awesome-dropzone7").removeAllFiles(true);
        $('.yasir-payroll-select2').val($scope.editIncome.income_id).trigger('change.select2');
        $('.secondSelect').val($scope.editIncome.income_category_id).trigger('change.select2');
        $('.thirdSelect').val($scope.editIncome.collected_by_id).trigger('change.select2');
        $('.currency-dropdown').val($scope.editIncome.currency).trigger('change.select2');
        $scope.edit_income_form.$setUntouched();
        $scope.edit_income_form.$setPristine();
    }

    $scope.setCollectViewData = function(i){
        $scope.viewIncome = angular.copy(i);
    }

    $scope.setPayViewData = function(e){
        $scope.viewExpense = angular.copy(e);
    }

    $scope.setPayEditData = function(e){
        $scope.editExpense = angular.copy(e);
        var check = false;
        angular.forEach($scope.currencies, function (value, key) {
            if(value.currency_id == $scope.editExpense.currency){
                check = true;
            }
        });
        if(check == false){
            $scope.editExpense.currency = "";
        }
        $scope.editExpense.amount = parseInt($scope.editExpense.amount);
        $scope.editExpense.files = [];
        Dropzone.forElement("#my-awesome-dropzone8").removeAllFiles(true);
        $('.yasir-payroll-select2').val($scope.editExpense.expense_id).trigger('change.select2');
        $('.secondSelect').val($scope.editExpense.expense_category_id).trigger('change.select2');
        $('.thirdSelect').val($scope.editExpense.paid_by_id).trigger('change.select2');
        $('.currency-dropdown').val($scope.editExpense.currency).trigger('change.select2');
        $scope.edit_expense_form.$setUntouched();
        $scope.edit_expense_form.$setPristine();
    }

    $scope.setWithdrawEditData = function(w){
        $scope.withdrawEdit = angular.copy(w);
        var check = false;
        angular.forEach($scope.currencies, function (value, key) {
            if(value.currency_id == $scope.withdrawEdit.currency){
                check = true;
            }
        });
        if(check == false){
            $scope.withdrawEdit.currency = "";
        }
        $scope.withdrawEdit.amount = parseInt($scope.withdrawEdit.amount);
        $scope.withdrawEdit.files = [];
        Dropzone.forElement("#my-awesome-dropzone6").removeAllFiles(true);
        $('.yasir-payroll-select2').val($scope.withdrawEdit.withdraw_by).trigger('change.select2');
        $('.currency-dropdown').val($scope.withdrawEdit.currency).trigger('change.select2');
        $scope.edit_withdraw_form.$setUntouched();
        $scope.edit_withdraw_form.$setPristine();
    }

    $scope.setDepositId = function(id){
        $scope.deposit = {
            amount: 0,
            deposit_by: '',
            date: $scope.today,
            comment: '',
            collected_by: '',
            currency: $scope.default_currency,
            mode: 'cash',
            account_id: id,
            files: []
        };
        Dropzone.forElement("#my-awesome-dropzone").removeAllFiles(true);
        $('.yasir-payroll-select2').val(null).trigger('change.select2');
        $('.currency-dropdown').val($scope.default_currency).trigger('change.select2');
    }

    $scope.setWithdrawId = function(id,balance){
        $scope.withdraw = {
            amount: 0,
            withdraw_by: '',
            date: $scope.today,
            comment: '',
            paid_by: '',
            currency: $scope.default_currency,
            mode: 'cash',
            account_id: id,
            files: [],
            balance: balance
        };
        Dropzone.forElement("#my-awesome-dropzone2").removeAllFiles(true);
        $('.yasir-payroll-select2').val(null).trigger('change.select2');
        $('.currency-dropdown').val($scope.default_currency).trigger('change.select2');
    }

    $scope.newDeposit = function () {
    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/newDeposit', $scope.deposit).then(
                function (response) {
                    $scope.getVirtualAccounts();
                    $scope.deposit = {
                        amount: 0,
                        deposit_by: '',
                        date: $scope.today,
                        comment: '',
                        collected_by: '',
                        currency: '',
                        mode: 'cash',
                        account_id: 0,
                        files: []
                    };
                    Dropzone.forElement("#my-awesome-dropzone").removeAllFiles(true);
                    $scope.deposit_form.$setUntouched();
                    $scope.deposit_form.$setPristine();
                    $('#depositModal').modal('hide');
                    showNotification('Success', response.data.message, 'success');
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
    }

    $scope.newIncome = function () {
    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/newIncome', $scope.income).then(
                function (response) {
                    $scope.income = {
                        income_id: '',
                        income_category_id: '',
                        date: $scope.today,
                        amount: 0,
                        currency: '',
                        collected_by: '',
                        comment: '',
                        mode: 'cash',
                        files: []
                    };
                    $scope.income_categories = [];
                    $scope.get_incomes_expenses();
                    Dropzone.forElement("#my-awesome-dropzone3").removeAllFiles(true);
                    $scope.income_form.$setUntouched();
                    $scope.income_form.$setPristine();
                    $('#collectModal').modal('hide');
                    showNotification('Success', response.data.message, 'success');
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
    }

    $scope.updateIncome = function () {
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/updateIncome', $scope.editIncome).then(
                function (response) {
                    $scope.get_incomes_expenses();
                    Dropzone.forElement("#my-awesome-dropzone7").removeAllFiles(true);
                    $scope.edit_income_form.$setUntouched();
                    $scope.edit_income_form.$setPristine();
                    $('#editCollectModal').modal('hide');
                    showNotification('Success', response.data.message, 'success');
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
    }

    $scope.getIncomeCategoriesforEdit = function(editIncome){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/getIncomeCategoriesforEdit', {income_id: editIncome.income_id, id: editIncome.id}).then(
                function (response) {
                    editIncome.income_categories = response.data.income_categories;
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
    }

    $scope.getExpenseCategoriesforEdit = function(editExpense){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/getExpenseCategoriesforEdit', {expense_id: editExpense.expense_id, id: editExpense.id}).then(
                function (response) {
                    editExpense.expense_categories = response.data.expense_categories;
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
    }

    $scope.newExpense = function () {
    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/newExpense', $scope.expense).then(
                function (response) {
                    $scope.expense = {
                        expense_id: '',
                        expense_category_id: '',
                        date: $scope.today,
                        amount: 0,
                        currency: '',
                        collected_by: '',
                        comment: '',
                        mode: 'cash',
                        files: []
                    };
                    $scope.expense_categories = [];
                    $scope.get_incomes_expenses();
                    Dropzone.forElement("#my-awesome-dropzone3").removeAllFiles(true);
                    $scope.expense_form.$setUntouched();
                    $scope.expense_form.$setPristine();
                    $('#payModal').modal('hide');
                    showNotification('Success', response.data.message, 'success');
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
    }

    $scope.updateExpense = function () {
    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/updateExpense', $scope.editExpense).then(
                function (response) {
                    $scope.get_incomes_expenses();
                    Dropzone.forElement("#my-awesome-dropzone8").removeAllFiles(true);
                    $scope.edit_expense_form.$setUntouched();
                    $scope.edit_expense_form.$setPristine();
                    $('#editPayModal').modal('hide');
                    showNotification('Success', response.data.message, 'success');
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
    }




    $scope.newWithdraw = function () {
    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/newWithdraw', $scope.withdraw).then(
                function (response) {
                    $scope.getVirtualAccounts();
                    $scope.withdraw = {
                        amount: 0,
                        withdraw_by: '',
                        date: $scope.today,
                        comment: '',
                        paid_by: '',
                        currency: '',
                        mode: 'cash',
                        account_id: 0,
                        files: [],
                        balance: 0
                    };
                    Dropzone.forElement("#my-awesome-dropzone2").removeAllFiles(true);
                    $scope.withdraw_form.$setUntouched();
                    $scope.withdraw_form.$setPristine();
                    $('#withdrawModal').modal('hide');
                    showNotification('Success', response.data.message, 'success');
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                });
    }


});

app2.controller("monitoringController", function ($scope, $http, $window, $location, $sce) {
    $scope.filterModel = {};
    $scope.academicyears = [];
    $scope.classes = [];
    $scope.monitoring = [];
    $scope.col_width='10';
    $scope.is_holiday = false;
    $scope.message = "";
    $scope.initAcademicYears =  function(){
        Loading("#acMFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'examination/getAcademicYears', "", config).then(
            function (success) {
                Loading("#acMFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.initClasses(success.data.current_academic_year_id);
                $scope.academicyears = success.data.data;
                $scope.filterModel.academic_year_id = success.data.current_academic_year_id;
                $scope.filterModel.class_id = "";
            },
            function (error) {
                Loading("#acMFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $window.location.href = 'errors/' + error.status;
            }
        );
    };
    
    $scope.initClasses = function (academic_year_id) {
        if(academic_year_id) {    
            Loading("#acMFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'examination/getClasses', { 'academic_year_id': academic_year_id }, config).then(
                function (success) {
                    Loading("#acMFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.classes = success.data;
                },
                function (error) {
                    Loading("#acMFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
            );
        }
    };
    $scope.onSubmit = function(){
        Loading("#academic-monitoring-container", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'monitoring/fetchAcademicMonitoringReport', $scope.filterModel, config).then(
            function (success) {
                Loading("#academic-monitoring-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                if(success.data.status == "error"){
                    $scope.is_holiday = true;
                    $scope.message = success.data.message;
                } else if(success.data.status == "success"){
                    $scope.is_holiday = false;
                    $scope.monitoring = success.data.data;
                    $scope.col_width = 100/$scope.monitoring[0].batches[0].subjects.length;
                    $scope.col_width = $scope.col_width;
                }
            },
            function (error) {
                Loading("#academic-monitoring-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };
});

app2.controller("reportExamination", function ($scope, $http, $window, $timeout) {
    $scope.academicyears = [];
    $scope.fcModel = [];
    $scope.filterModel = {};
    $scope.classes = [];
    $scope.batches = [];
    $scope.exams = [];
    $scope.classeswithacdmicyear = [];
    $scope.students = [];

    $scope.initAcademicYears =  function(){
        Loading("#examReportFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'fee/getAcademicYears', "", config).then(
            function (success) {
                Loading("#examReportFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.fcModel.academic_year_id = success.data.current_academic_year_id;
                $scope.filterModel.academic_year_id = success.data.current_academic_year_id;
                $scope.initClasses(success.data.current_academic_year_id);
                $scope.academicyears = success.data.data;
                $scope.initExams(success.data.current_academic_year_id);
            },
            function (error) {
                Loading("#examReportFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $window.location.href = 'errors/' + error.status;
            }
        );
    };

    $scope.initClasses = function (academic_year_id) {
        if(academic_year_id) { 
            Loading("#examFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'fee/getClasses', { 'academic_year_id': academic_year_id }, config).then(
                function (success) {
                    Loading("#examFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.classeswithacdmicyear = success.data;

                },
                function (error) {
                    Loading("#examFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
            );
        }
    };


    $scope.initBatches = function () {
        $scope.batches = [];
    
        if($scope.classes != undefined){
            
            $("#batches_selectall").attr("checked",false);

        
        Loading("#examFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
        if ($scope.classes && $scope.filterModel.academic_year_id) {
           
            Loading("#examFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'examination/getClassBatches', {'class_id': $scope.classes, academic_year_id: $scope.filterModel.academic_year_id}, config).then(
                function (success) {
                    Loading("#examFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.batches = success.data;
                },
                function (error) {
                
                    Loading("#examFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    //$window.location.href = 'errors/' + error.status;
                });
        }else{
             $scope.batches = "all";
        }
    }

    };


    $scope.initExams = function (academic_year_id) {
        if(academic_year_id) {
            Loading("#examFilterExam", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'examination/getSchoolExams',{academic_year_id:academic_year_id}, config).then(
                function (success) {
                    Loading("#examFilterExam", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.exams = success.data;
                },
                function (error) {
                    console.log(error.data);
                }
            );
        }
    };

    $scope.onsubmit = function(){
        $scope.students = [];
        $scope.filterModel.classes = $scope.classes;
        $scope.filterModel.batches = $scope.BatchSelect;
       
         Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'reports/getExamReport', $scope.filterModel, config).then(
            function(success){
                
                $scope.students = success.data.data;
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
            },function(error){
                console.log(error.data);
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
            });
    }



    $scope.onsubmit2 = function(){
   
        $scope.filterModel.classes = $scope.classes;
        $scope.filterModel.batches = $scope.BatchSelect;
        
        $('#myTablee').DataTable({
            "language": {

                "decimal":        "",
                "emptyTable":     '<?php echo lang("no_data_table"); ?>',
                "info":           '<?php echo lang("data_info"); ?>',
                "infoEmpty":      '<?php echo lang("infoempty"); ?>',
                "infoFiltered":   '<?php echo lang("filter_datatable"); ?>',
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     '<?php echo lang("show_datatable"); ?>',
                "loadingRecords": '<?php echo lang("loading_datatable"); ?>',
                "processing":     '<?php echo lang("processing_datatable"); ?>',
                "search":         '<?php echo lang("search"); ?>:',
                "zeroRecords":    '<?php echo lang("no_record_datatable"); ?>',
                "paginate": {
                    "first":      '<?php echo lang("first"); ?>',
                    "last":       '<?php echo lang("last"); ?>',
                    "next":       '<?php echo lang("btn_next"); ?>',
                    "previous":   '<?php echo lang("previous"); ?>'
                },
                "aria": {
                    "sortAscending":  ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                }
            },
            dom: 'Bfrtip',
            ajax: {
                type: "POST",
                url: base_url+'reports/getExamReport',
                data: {'formData':$scope.filterModel},
                dataSrc: ''
            },
           
            "order": [[ 0, "asc" ]],
            columns: [
                {title: '<?php echo lang("imp_sr");?>', data: 'index'},
                {title: '<?php echo lang("lbl_avatar");?>', data: '', render : function (data, type, row) {
                    
                    return '<img src="<?php echo base_url(); ?>uploads/user/' +row.student_avatar+ '" class="img-circle" style="height: 60px;width: 60px" />';
                    
                    }
                },
                {title: '<?php echo lang("student_name");?>', data: '', render : function (data, type, row) {
                     if (row.is_shifted){
                       return row.student_name+'<p><a href="javascript:void(0)" data-html="true" data-toggle="tooltip" data-placement="top" title="<?php echo lang("student_shifted"); ?>"><i class="fa fa-info-circle"></i></a></p>';
                        }
                       else{
                            return row.student_name;
                        }
                    }
                 },
                {title: '<?php echo lang("lbl_rollno");?>', data: 'rollno'},
                {title: '<?php echo lang("lbl_class");?>', data: 'class_name'},
                {title: '<?php echo lang("lbl_batch");?>', data: 'batch_name'},
                //{title: '<?php echo lang("subject_group");?>', data: 'subject_group_name'},
                {title: '<?php echo lang("lbl_result");?>', data: 'obtained_total'},
                {title: '<?php echo lang("percentage");?>', data: 'percentage'},
                {title: '<?php echo lang("exam_position");?>', data: 'new_position'},
                {title: '<?php echo lang("lbl_status");?>', data: '', render: function(data, type, row)
                    {

                        if(row.result ==='Pass'){
                            return '<span class="text-success"><?php echo lang("pass");?></span>';
                        }else if(row.result === 'Fail'){
                           return '<span class="text-danger"><?php echo lang("fail"); ?></span>';
                        }else{
                            return '<span class="text-info">-</span>';
                        }
                           
                    }
                },
                {title: '<?php echo lang("lbl_remarks");?>', data: 'teacher_remark'}
                
            ],
            buttons: [
                {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
                },
                {
                extend: 'excelHtml5',
                orientation: 'landscape'
                
                },
                {
                extend: 'csvHtml5'
                }
                 
            ],
            destroy: true
                
        });
    }
   

});


function save_online_selection_value(id){
    if($("#std_"+id).is(":checked")){
        global_online_admission_object.push(id)
    } else {
        global_online_admission_object.splice($.inArray(id, global_online_admission_object), 1);
    }
    if(global_online_admission_object.length == 0){
        $("#mysubmitbtn").addClass("custom_disable");
    } else {
        $("#mysubmitbtn").removeClass("custom_disable");
    }
}

function save_online_selection_value2(id){
    $("#myNav").css({"width":"100%"});
    Loading("body", "", "", "show");
    $.ajax({
        url: base_url + "online_admission/get_student_online_form",
        type: "POST",
        dataType: "html",
        data: {"id": id},
        success: function (data) {
            Loading("body", "", "", "hide");
            $("#overlay-content").html(data);
        },
        error: function (error) {
            Loading("body", "", "", "hide");
            console.log(error);
        }
    });
}

function customPrintForOnlineAdmission(id) {
    $("#" + id).print({
        globalStyles: false,
        mediaPrint: false,
        stylesheet: "<?php echo base_url(); ?>assets/css/custom-result-card.css?v=<?= date("h.i.s") ?>",
        noPrintSelector: ".no-print",
        iframe: true,
        append: null,
        prepend: null,
        manuallyCopyFormValues: true,
        deferred: $.Deferred(),
        timeout: 750,
        title: null,
        doctype: '<!doctype html>'
    });
}

app2.controller("onlineAdmissionCtrl", function ($scope, $http, $window, FileUploader) {
    $scope.model={};
    $scope.countries = {};
    $scope.serverResponse = {};
    $scope.finalServerResponse = {};
    $scope.oAStudents = [];
    $scope.a = {};
    $scope.academicyears = {};
    $scope.filterModel = {};
    $scope.classes = {};
    $scope.groups = {};
    $scope.discounts = {};
    $scope.shiftModel = {};
    $scope.isEmailExistMessage = "";
    $scope.isEmailExistAlertType = "danger";
    $scope.parentInfo = {};
    $scope.childrens = [];
    $scope.selectedOption = 'opt1';
    $scope.permissions = [
       {label: '<?php echo lang("students-show");?>', permission: 'students-show', val: false},
       {label: '<?php echo lang("students-add");?>', permission: 'students-add', val: false},
       {label: '<?php echo lang("students-edit");?>', permission: 'students-edit', val: false},
       {label: '<?php echo lang("students-view");?>', permission: 'students-view', val: false},
       {label: '<?php echo lang("attendance-show");?>', permission: 'attendance-show', val: false},
       {label: '<?php echo lang("attendance-report");?>', permission: 'attendance-report', val: false},
       {label: '<?php echo lang("parents-all");?>', permission: 'parents-all', val: false},
       {"label": '<?php echo lang("parents-add");?>', "permission": "parents-add", "val": false},
       {"label": '<?php echo lang("parents-edit");?>', "permission": "parents-edit", "val": false},
       {label: '<?php echo lang("parents-view");?>', permission: 'parents-view', val: false},
       {"label": '<?php echo lang("attendance-employee");?>', "permission": "attendance-employee", "val": false},
       {"label": '<?php echo lang("attendance-emp_report");?>', "permission": "attendance-emp_report", "val": false},
       {"label": '<?php echo lang("employee-all");?>', "permission": "employee-all", "val": false},
       {"label": '<?php echo lang("employee-view");?>', "permission": "employee-view", "val": false},
       {"label": '<?php echo lang("employee-add");?>', "permission": "employee-add", "val": false},
       {"label": '<?php echo lang("employee-edit");?>', "permission": "employee-edit", "val": false},
       {label: '<?php echo lang("study_material-upload");?>', permission: 'study_material-upload', val: false},
       {label: '<?php echo lang("study_material-download");?>', permission: 'study_material-download', val: false},
       {label: '<?php echo lang("study_material-book_shop");?>', permission: 'study_material-book_shop', val: false},
       {"label": '<?php echo lang("forms-all");?>', "permission": "forms-all", "val": true},
       {"label": '<?php echo lang("forms-create");?>', "permission": "forms-create", "val": false},
       {"label": '<?php echo lang("forms-edit");?>', "permission": "forms-edit", "val": false},
       {"label": '<?php echo lang("forms-category_create");?>', "permission": "forms-category_create", "val": false},
       {"label": '<?php echo lang("profile-index");?>', "permission": "profile-index", "val": true},
       {"label": '<?php echo lang("profile-edit");?>', "permission": "profile-edit", "val": true},
       {label: '<?php echo lang("students-shift");?>', permission: 'students-shift', val: false},
       {label: '<?php echo lang("fee-collection");?>', permission: 'fee-collection', val: false},
       {label: '<?php echo lang("collection-allow");?>', permission: 'collection-allow', val: false},
       {label: '<?php echo lang("view-collection");?>', permission: 'view-collection', val: false},
       {"label": '<?php echo lang("fee-statistics");?>', "permission": "fee-statistics", "val": false},
       {label: '<?php echo lang("timetable-show");?>', permission: 'timetable-show', val: false},
       {label: '<?php echo lang("timetable-edit");?>', permission: 'timetable-edit', val: false},
       {label: '<?php echo lang("reports-all");?>', permission: 'reports-all', val: false},
       {label: '<?php echo lang("syllabus-add");?>', permission: 'syllabus-add', val: false},
       {label: '<?php echo lang("assign-teacher");?>', permission: 'assign-teacher', val: false},
       {label: '<?php echo lang("examination-add");?>', permission: 'examination-add', val: false},
       {label: '<?php echo lang("examination-marks");?>', permission: 'examination-marks', val: false},
       {label: '<?php echo lang("examination-majorSheet");?>', permission: 'examination-majorSheet', val: false},
       {label: '<?php echo lang("applications-student");?>', permission: 'applications-student', val: false},
       {label: '<?php echo lang("applications-employee");?>', permission: 'applications-employee', val: false},
       {label: '<?php echo lang("applications-studyplan");?>', permission: 'applications-studyplan', val: false},
       {label: '<?php echo lang("applications-marksheet");?>', permission: 'applications-marksheet', val: false},
       {label: '<?php echo lang("payroll-index");?>', permission: 'payroll-index', val: false},
       {label: '<?php echo lang("payroll-pay");?>', permission: 'payroll-pay', val: false},
       {label: '<?php echo lang("payroll-delete");?>', permission: 'payroll-delete', val: false},
       {label: '<?php echo lang("payroll-settings");?>', permission: 'payroll-settings', val: false},
       {label: '<?php echo lang("payroll-settingsadd");?>', permission: 'payroll-settingsadd', val: false},
       {label: '<?php echo lang("payroll-settingsedit");?>', permission: 'payroll-settingsedit', val: false},
       {label: '<?php echo lang("payroll-settingsdelete");?>', permission: 'payroll-settingsdelete', val: false},
       {label: '<?php echo lang("settings-evaluation");?>', permission: 'settings-evaluation', val: false},
       {label: '<?php echo lang("settings-evaladd");?>', permission: 'settings-evaladd', val: false},
       {label: '<?php echo lang("settings-evaledit");?>', permission: 'settings-evaledit', val: false},
       {label: '<?php echo lang("settings-evaledelete");?>', permission: 'settings-evaledelete', val: false},
       {label: '<?php echo lang("students-evaluate");?>', permission: 'students-evaluate', val: false},
       {label: '<?php echo lang("students-report_card");?>', permission: 'students-report_card', val: false},
       {label: '<?php echo lang("trash-index");?>', permission: 'trash-index', val: false},
       {label: '<?php echo lang("trash-recover");?>', permission: 'trash-recover', val: false},
       {label: '<?php echo lang("trash-delete");?>', permission: 'trash-delete', val: false}
    ];
    $scope.formModel = {
        'parent_avatar':'',
        'parent_dob':'',
        'parent_occupation': '',
        'parent_income':0,
        'parent_street':'',
        'parent_phone_no':'',
        'parent_ic_number':'',
        'parent_city':'',
        's_g_name':'',
        's_g_relation':'',
        's_g_phone_no':'',
        'child_avatar':'',
        'child_religion':'',
        'child_dob':'',
        'child_blood_group':'',
        'child_birth_place':'',
        'child_language':'',
        'child_nic':'',
        'child_email':'',
        'child_phone_no':'',
        'child_city':'',
        'child_address':'',
        'is_terms_and_conditions_agreed':''
    };

    $scope.classes = {};

    $scope.initCountries = function () {
        $http.post(base_url + 'online_admission/getCountries', "", config).then(
            function (success) {
                $scope.countries = success.data;
            },
            function (error) {
                console.log(error.data);
            }
        );
    };

    $scope.initClasses = function (school_id){
        $http.post(base_url + 'online_admission/getClasses', {'school_id': school_id}, config).then(
            function(success){
                $scope.classes = success.data;  
            },
            function(error){
                console.log(error.data);
            }
        );
    };

    $scope.details = function(){
        var formData = {};
        if($scope.selectedOption === 'opt1') {
            $scope.formModel.parent_id = "";
            $scope.formModel.permissions = $scope.permissions;
            formData = $scope.formModel;
        } else if ($scope.selectedOption === 'opt2') {
            
            formData.parent_id = $scope.parentInfo.id;
            formData.parent_avatar = $scope.parentInfo.avatar;
            formData.parent_name = $scope.parentInfo.name;
            formData.parent_gender = $scope.parentInfo.gender;
            formData.parent_dob = $scope.parentInfo.dob;
            formData.parent_occupation = $scope.parentInfo.occupation;
            formData.parent_income = $scope.parentInfo.income;
            formData.parent_email = $scope.parentInfo.email;
            formData.parent_phone_no = $scope.parentInfo.contact;
            formData.parent_ic_number = $scope.parentInfo.ic_number;
            formData.parent_country_id = $scope.parentInfo.country;
            formData.parent_city = $scope.parentInfo.city;
            formData.s_g_name = $scope.parentInfo.guardian2_name;
            formData.s_g_relation = $scope.parentInfo.guardian2_relation;
            formData.s_g_phone_no = $scope.parentInfo.guardian2_contact;
            
            formData.child_religion = $scope.formModel.child_religion;
            formData.child_name = $scope.formModel.child_name;
            formData.child_gender = $scope.formModel.child_gender;
            formData.child_dob = $scope.formModel.child_dob;
            formData.child_blood_group = $scope.formModel.child_blood_group;
            formData.child_birth_place = $scope.formModel.child_birth_place;
            formData.child_nationality_id = $scope.formModel.child_nationality_id;
            formData.child_language = $scope.formModel.child_language;
            formData.child_nic = $scope.formModel.child_nic;
            formData.child_class_id = $scope.formModel.child_class_id;
            formData.child_email = $scope.formModel.child_email;
            formData.child_phone_no = $scope.formModel.child_phone_no;
            formData.child_city = $scope.formModel.child_city;
            formData.child_address = $scope.formModel.child_address;
            formData.is_terms_and_conditions_agreed = $scope.formModel.is_terms_and_conditions_agreed;
        }
        if ($scope.imageDataURI) {
            formData.child_avatar = $scope.resImageDataURI;
        } else {
            formData.child_avatar = "";
        }
        formData.school_id = $scope.school_id;
        if($scope.parentEmail == undefined || $scope.parentEmail == ""){
            formData.parent_email = '';
        } else {
            formData.parent_email = $scope.parentEmail;
        }
        formData.selectedOption = $scope.selectedOption;
        
        $http.post(base_url + 'online_admission/insertData', formData, config).then(
            function(success){
                $scope.finalServerResponse = success.data;
                if (success.data.status == 'success'){
                    $scope.formModel = {
                        'parent_avatar':'',
                        'parent_dob':'',
                        'parent_occupation': '',
                        'parent_income':0,
                        'parent_street':'',
                        'parent_phone_no':'',
                        'parent_ic_number':'',
                        'parent_city':'',
                        's_g_name':'',
                        's_g_relation':'',
                        's_g_phone_no':'',
                        'child_avatar':'',
                        'child_religion':'',
                        'child_dob':'',
                        'child_blood_group':'',
                        'child_birth_place':'',
                        'child_language':'',
                        'child_nic':'',
                        'child_email':'',
                        'child_phone_no':'',
                        'child_city':'',
                        'child_address':'',
                        'is_terms_and_conditions_agreed':''
                    };
                    $scope.parentEmail = "";
                    $scope.resImageDataURI = "";
                    $scope.msform.$setUntouched();
                    $scope.msform.$setPristine();
                    $("#msform").addClass("hide");
                }
            },
            function(error){
                console.log(error.data);
            }
        );
    };

    $scope.closeNav = function (){
        $("#myNav").css({"width":"0"});
    };

    $scope.updateModel = {};
    $scope.edit_application = function(id){
        $("#myNav").css({"width":"100%"});
        Loading("body", "", "", "show");
        
        $http.post(base_url + 'online_admission/get_application', {'id': id}, config).then(
            function(success){
                Loading("body", "", "", "hide");
                $scope.updateModel = success.data;
            },
            function(error){
                Loading("body", "", "", "hide");
                console.log(error.data);
            }
        );
    };

    $scope.getOnlineAdmissions = function(){
        $('#myTablee_onlineAdmission').DataTable({
            "language": {
                "decimal":        "",
                "emptyTable":     '<?php echo lang("no_data_table"); ?>',
                "info":           '<?php echo lang("data_info"); ?>',
                "infoEmpty":      '<?php echo lang("infoempty"); ?>',
                "infoFiltered":   '<?php echo lang("filter_datatable"); ?>',
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     '<?php echo lang("show_datatable"); ?>',
                "loadingRecords": '<?php echo lang("loading_datatable"); ?>',
                "processing":     '<?php echo lang("processing_datatable"); ?>',
                "search":         '<?php echo lang("search"); ?>:',
                "zeroRecords":    '<?php echo lang("no_record_datatable"); ?>',
                "paginate": {
                    "first":      '<?php echo lang("first"); ?>',
                    "last":       '<?php echo lang("last"); ?>',
                    "next":       '<?php echo lang("btn_next"); ?>',
                    "previous":   '<?php echo lang("previous"); ?>'
                },
                "aria": {
                    "sortAscending":  ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                }
            },
            columnDefs: [
                {
                    targets: -1,
                    className: 'dt-body-center'
                }
            ],
            dom: 'Bfrtip',
            ajax: {
                type: "POST",
                url: base_url+'students/getOnlineAdmissions',
                data: '',
                dataSrc: ''
            },
            columns: [
                {title: '', data: '', render: function(data, type, row) {
                        if(row.is_admit == 'yes'){
                            return '<div class="checkbox checkbox-info"><input onClick="save_online_selection_value('+row.id+')" id="std_'+row.id+'" value="'+row.id+'" disabled="disabled" type="checkbox" name="checkbox"><label for="std'+row.id+'"></label></div>';
                        } else if(row.is_admit == 'no'){
                            return '<div class="checkbox checkbox-info"><input onClick="save_online_selection_value('+row.id+')" id="std_'+row.id+'" value="'+row.id+'" type="checkbox" name="checkbox"><label for="std'+row.id+'"></label></div>';
                        }
                    } 
                },
                {title: '<?php echo lang("lbl_status");?>', data: 'status', render: function(data, type, row) {
                        if(row.is_admit == 'no'){
                            return '<span class="badge badge-pill badge-danger">Review</span>';
                        } else {
                            return '<span class="badge badge-pill badge-success">Accepted</span>';
                        }
                    } 
                },
                {title: '<?php echo lang("lbl_avatar");?>', data: '', render : function (data, type, row) {
                        if(row.std_avatar == null){
                            return '<img src="<?php echo base_url(); ?>uploads/user/profile.png" class="img-circle" style="height: 40px;width: 40px" />';
                        } else {
                            return '<img src="<?php echo base_url(); ?>uploads/user/' +row.std_avatar+ '" class="img-circle" style="height: 40px;width: 40px" />';
                        }                  
                    }
                },
                {title: '<?php echo lang("student_name");?>', data: 'std_full_name' },
                {title: '<?php echo lang("lbl_guardian");?>', data: 'parent_name' },
                {title: '<?php echo lang("lbl_rollno");?>', data: 'std_rollno'}, 
                {title: 'Action', data: '', render: function (data, type, row) {
                        if(row.is_admit == 'yes'){
                            return '<a onClick="save_online_selection_value2('+row.id+')" href="javascript:void(0);" class="btn btn-circle btn-success"><i class="fa fa-eye"></i></a><button type="button" value="'+row.id+',online_admission/softDelete" class="btn btn-circle btn-danger sa-warning"><i class="fa fa-trash"></i></button>';
                        } else if(row.is_admit == 'no'){
                            var method = "javascript:angular.element('#page-wrapper').scope().edit_application("+row.id+")";
                            return '<a href="'+method+'" class="btn btn-circle btn-info"><i class="fa fa-pencil"></i></a><a onClick="save_online_selection_value2('+row.id+')" href="javascript:void(0);" class="btn btn-circle btn-success"><i class="fa fa-eye"></i></a><button type="button" value="'+row.id+',online_admission/softDelete" class="btn btn-circle btn-danger sa-warning"><i class="fa fa-trash"></i></button>';
                        }
                    } 
                }, 
            ],        
            buttons: [
                {
                    extend: 'copyHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    exportOptions: {
                        columns: [1, 3, 4, 5]
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [1, 3, 4, 5]
                    }
                },
                {
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: [1, 3, 4, 5]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [1, 3, 4, 5]
                    }
                }
            ],
            destroy: true
        });
        $(this).html( '<input type="button" value="view"/>' );
    };

    $scope.initAcademicYears =  function(){
        Loading("#feeFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'online_admission/getAcademicYears', "", config).then(
            function (success) {
                Loading("#feeFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.filterModel.academic_year_id = success.data.current_academic_year_id;
                $scope.initClasses2(success.data.current_academic_year_id);
                $scope.academicyears = success.data.data;
            },
            function (error) {
                Loading("#feeFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };

    $scope.initClasses2 = function () {
        Loading("#oAFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'fee/getClasses2', {}, config).then(
            function (success) {
                Loading("#oAFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.classes = success.data;
                $scope.filterModel.class_id = 'all';
            },
            function (error) {
                Loading("#oAFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };

    $scope.fetchAllStdsOfClassAndBatch = function () {
        var formData = $scope.filterModel;
        $('#myTablee_onlineAdmission').DataTable({
            "language": {
                "decimal":        "",
                "emptyTable":     '<?php echo lang("no_data_table"); ?>',
                "info":           '<?php echo lang("data_info"); ?>',
                "infoEmpty":      '<?php echo lang("infoempty"); ?>',
                "infoFiltered":   '<?php echo lang("filter_datatable"); ?>',
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     '<?php echo lang("show_datatable"); ?>',
                "loadingRecords": '<?php echo lang("loading_datatable"); ?>',
                "processing":     '<?php echo lang("processing_datatable"); ?>',
                "search":         '<?php echo lang("search"); ?>:',
                "zeroRecords":    '<?php echo lang("no_record_datatable"); ?>',
                "paginate": {
                    "first":      '<?php echo lang("first"); ?>',
                    "last":       '<?php echo lang("last"); ?>',
                    "next":       '<?php echo lang("btn_next"); ?>',
                    "previous":   '<?php echo lang("previous"); ?>'
                },
                "aria": {
                    "sortAscending":  ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                }
            },
            columnDefs: [
                {
                    targets: -1,
                    className: 'dt-body-center'
                }
            ],
            dom: 'Bfrtip',
            ajax: {
                type: "POST",
                url: base_url+'students/getOnlineAdmissions2',
                data: formData,
                dataSrc: ''
            },
            columns: [
                {title: '', data: '', render: function(data, type, row) {
                        if(row.is_admit == 'yes'){
                            return '<div class="checkbox checkbox-info"><input onClick="save_online_selection_value('+row.id+')" id="std_'+row.id+'" value="'+row.id+'" disabled="disabled" type="checkbox" name="checkbox"><label for="std'+row.id+'"></label></div>';
                        } else if(row.is_admit == 'no'){
                            return '<div class="checkbox checkbox-info"><input onClick="save_online_selection_value('+row.id+')" id="std_'+row.id+'" value="'+row.id+'" type="checkbox" name="checkbox"><label for="std'+row.id+'"></label></div>';
                        }
                    } 
                },
                {title: '<?php echo lang("lbl_status");?>', data: 'status', render: function(data, type, row) {
                        if(row.is_admit == 'no'){
                            return '<span class="badge badge-pill badge-danger">Review</span>';
                        } else {
                            return '<span class="badge badge-pill badge-success">Accepted</span>';
                        }
                    } 
                },
                {title: '<?php echo lang("lbl_avatar");?>', data: '', render : function (data, type, row) {
                        if(row.std_avatar == null){
                            return '<img src="<?php echo base_url(); ?>uploads/user/profile.png" class="img-circle" style="height: 40px;width: 40px" />';
                        } else {
                            return '<img src="<?php echo base_url(); ?>uploads/user/' +row.std_avatar+ '" class="img-circle" style="height: 40px;width: 40px" />';
                        }                  
                    }
                },
                {title: '<?php echo lang("student_name");?>', data: 'std_full_name' },
                {title: '<?php echo lang("lbl_guardian");?>', data: 'parent_name' },
                {title: '<?php echo lang("lbl_rollno");?>', data: 'std_rollno'}, 
                {title: 'Action', data: '', render: function (data, type, row) {
                        if(row.is_admit == 'yes'){
                            return '<a onClick="save_online_selection_value2('+row.id+')" href="javascript:void(0);" class="btn btn-circle btn-success"><i class="fa fa-eye"></i></a><button type="button" value="'+row.id+',online_admission/softDelete" class="btn btn-circle btn-danger sa-warning"><i class="fa fa-trash"></i></button>';
                        } else if(row.is_admit == 'no'){
                            var method = "javascript:angular.element('#page-wrapper').scope().edit_application("+row.id+")";
                            return '<a href="'+method+'" class="btn btn-circle btn-info"><i class="fa fa-pencil"></i></a><a onClick="save_online_selection_value2('+row.id+')" href="javascript:void(0);" class="btn btn-circle btn-success"><i class="fa fa-trash"></i></a><button type="button" value="'+row.id+',online_admission/softDelete" class="btn btn-circle btn-danger sa-warning"><i class="fa fa-eye"></i></button>';
                        }
                    } 
                }, 
            ],        
            buttons: [
                {
                    extend: 'copyHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    exportOptions: {
                        columns: [1, 3, 4, 5]
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [1, 3, 4, 5]
                    }
                },
                {
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: [1, 3, 4, 5]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [1, 3, 4, 5]
                    }
                }
            ],
            destroy: true
        });
        $(this).html( '<input type="button" value="view"/>' );
     
    };

    $scope.fetchNewClassBatches = function() {
        Loading("#newdropdownBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
        if($scope.new_course == undefined){
            $scope.new_course ='';
            $scope.new_batches = [];
            $scope.new_batch = '';
            Loading("#newdropdownBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
        }else{
            $http.post(base_url + 'attendance/getClassBatches', {class_id: $scope.new_course}, config).then(
                function (success) {
                    Loading("#newdropdownBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.new_batches = success.data;
                    $scope.new_batches = $scope.new_batches.filter(function( obj ) {
                        return obj.id !== $scope.formModel.batch_id;
                    });
                    $scope.new_batch = '';
                },
                function (error) {
                    Loading("#newdropdownBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
            );
        }
    };

    $scope.fetchSubjectGroups = function(batch_id){
        Loading("#newdropdownSubectGroups", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'students/get_subject_groups', {batch_id : batch_id}, config).then(
            function (response) {
                Loading("#newdropdownSubectGroups", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.groups = response.data.groups;
                $scope.new_subject_group_id = '';
            }, function (error){
                Loading("#newdropdownSubectGroups", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };

    $scope.initDiscounts = function(){
        Loading("#newdropdownDiscount", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'fee/get_discounts', "", config).then(
            function (response) {
                Loading("#newdropdownDiscount", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.discounts = response.data.discounts;
                $scope.new_discount_id = '';
            }, function (error) {
                Loading("#newdropdownDiscount", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);   
            }
        );
    };

    $scope.shiftStudents = function (){
        $scope.shiftModel.class_id = $scope.new_course;
        $scope.shiftModel.batch_id = $scope.new_batch;
        $scope.shiftModel.subject_group_id = $scope.new_subject_group_id;
        $scope.shiftModel.rollno = $scope.rollno;
        $scope.shiftModel.discount_id = $scope.new_discount_id;
        $scope.shiftModel.admission_date = $scope.admission_date;
        $scope.shiftModel.student_ids = global_online_admission_object;

        Loading("#shiftModal", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'online_admission/admit_students', $scope.shiftModel, config).then(
            function (response) {
                Loading("#shiftModal", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.shiftModel = {};
                $scope.new_course = '';
                $scope.new_batch = '';
                $scope.new_subject_group_id = '';
                $scope.rollno = '';
                $scope.new_discount_id = '';
                $scope.admission_date = '';
                global_online_admission_object = [];
                $scope.shiftForm.$setUntouched();
                $scope.shiftForm.$setPristine();
                $("#shiftModal").modal("hide");
                $scope.getOnlineAdmissions();
                angular.forEach(response.data.data, function (value, key) {
                    if(value.status === 'success'){
                        showNotification('<?php echo lang("success_app") ?>', value.message, "success");
                    } else if(value.status === 'error'){
                        showNotification('<?php echo lang("error_app") ?>', value.message, "error");
                    }
                });
            }, function (error) {
                Loading("#shiftModal", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);   
            }
        );
    };

    $scope.checkParentExists = function(school_id){
        $http.post(base_url + 'online_admission/checkParentEmailIsExists', {'email': $scope.parentEmail, 'school_id': school_id }, config).then(
            function (success) {
                $("#mainDiv").removeClass("show");
                $("#mainDiv").addClass("hide");
                $("#subDiv").removeClass("hide");
                $scope.isEmailExistMessage = success.data.message;
                if(success.data.status == 'error'){
                    $scope.isEmailExistAlertType = 'danger';
                    $scope.formModel = {
                        'parent_id': success.data.data.id,
                        'avatar': success.data.data.avatar,
                        'name': success.data.data.name,
                        'dob': success.data.data.dob,
                        'gender': success.data.data.gender,
                        'occupation': success.data.data.occupation,
                        'income': parseInt(success.data.data.income),
                        'email': success.data.data.email,
                        'street': '',
                        'ic_number': success.data.data.ic_number,
                        'country_id': success.data.data.country,
                        'city': success.data.data.city,
                        'phone_no': success.data.data.mobile_phone,
                        's_g_name': success.data.data.guardian2_name,
                        'relation': success.data.data.guardian2_relation,
                        's_g_phone_no': success.data.data.guardian2_contact,
                        'child_email':'',
                        'child_phone_no':'',
                        'child_city':'',
                        'child_address':'',
                        'is_terms_and_conditions_agreed':''
                    };
                } else if(success.data.status == 'success'){
                    $scope.isEmailExistAlertType = 'success';
                    $scope.formModel = {
                        'parent_id': '',
                        'avatar': '',
                        'name': '',
                        'dob': '',
                        'gender': '',
                        'occupation': '',
                        'income': 0,
                        'email': $scope.parentEmail,
                        'street': '',
                        'ic_number': '',
                        'country_id': '',
                        'city': '',
                        'phone_no': '',
                        's_g_name': '',
                        'relation': '',
                        's_g_phone_no': '',
                        'child_email':'',
                        'child_phone_no':'',
                        'child_city':'',
                        'child_address':'',
                        'is_terms_and_conditions_agreed':''
                    };
                }
                console.log($scope.formModel);
            },
            function (error) {
                console.log(error.data);
            }
        );
    };

    $scope.checkparent = function(){
        $http.post(base_url + 'online_admission/isParentExists', { 'email': $scope.parentEmail, 'school_id': $scope.school_id }, config).then(
            function(success){
            console.log('hello haseeb');
            console.log($scope.parentEmail);
            console.log($scope.selectedOption);
            console.log(success.data);
                if($scope.selectedOption === "opt1"){
                    $scope.parentInfo = success.data.data;
                    if(success.data.status == 'danger'){
                        $scope.childrens = success.data.data.childrens;
                        $scope.serverResponse = success.data;
                    } else if(success.data.status == 'success'){
                        $scope.serverResponse = "";
                        $("#sNext").click();
                    }
                } else if($scope.selectedOption === "opt2"){
                    if(success.data.status == 'danger'){
                        $scope.parentInfo = success.data.data;
                        $scope.serverResponse = "";
                        $scope.childrens = success.data.data.childrens;
                        $("#sNext").click();
                    } else if(success.data.status == 'success'){
                        $scope.serverResponse = success.data;
                        $scope.serverResponse.status = "danger";
                        $scope.serverResponse.message = "<?php echo lang('parent_does_not_exists_please_choose_new_parent_option'); ?>";
                    } 
                }
            },
            function(error){
                console.log(error.data);
            }
        );
    };

    $scope.checkvalidateparent = function(){
        $http.post(base_url + 'online_admission/isParentExists', { 'email': $scope.parentEmail, 'school_id': $scope.school_id }, config).then(
            function(success){
            console.log('hello haseeb');
            console.log($scope.parentEmail);
            console.log($scope.selectedOption);
            console.log(success.data);
                if($scope.selectedOption === "opt1"){
                    $scope.parentInfo = success.data.data;
                    if(success.data.status == 'danger'){
                        angular.element('#sNext').removeClass("next");
                        $scope.childrens = success.data.data.childrens;
                        $scope.serverResponse = success.data;
                    } else if(success.data.status == 'success'){
                        angular.element('#sNext').addClass("next");
                        $scope.serverResponse = "";
                        angular.element(document.getElementById('#sNext')).trigger('click');
                        $("#sNext").click();
                    }
                } else if($scope.selectedOption === "opt2"){
                    if(success.data.status == 'danger'){
                        $scope.parentInfo = success.data.data;
                        $scope.serverResponse = "";
                        $scope.childrens = success.data.data.childrens;
                        $("#sNext").click();
                    } else if(success.data.status == 'success'){
                        $scope.serverResponse = success.data;
                        $scope.serverResponse.status = "danger";
                        $scope.serverResponse.message = "<?php echo lang('parent_does_not_exists_please_choose_new_parent_option'); ?>";
                    } 
                }
            },
            function(error){
                console.log(error.data);
            }
        );
    };

    $scope.update_application = function(){
        $scope.updateModel.is_image_uploaded = false;
        if ($scope.imageDataURI) {
            $scope.updateModel.std_avatar = $scope.resImageDataURI;
            $scope.updateModel.is_image_uploaded = true;
        }
        Loading("#application_update_form", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'online_admission/update_application', $scope.updateModel, config).then(
            function (response) {
                Loading("#application_update_form", '<?php echo lang("loading_datatable") ?>', "", "hide");
                if(response.data == "success"){
                    $("#myNav").css({"width":"0%"});
                    showNotification('<?php echo lang("success_app") ?>', "Online admission application updated successfully!", "success");
                    $scope.getOnlineAdmissions();
                }
            }, function (error) {
                Loading("#application_update_form", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);   
            }
        );
    };

    $scope.showSearchAgain = function(){
        $("#mainDiv").addClass("show");
        $("#mainDiv").removeClass("hide");
        $("#subDiv").removeClass("show");
        $("#subDiv").addClass("hide");
    };

    /*--- Image cropper ---*/
    $scope.blockingObject = {block: true};
    $scope.callTestFuntion = function () {
        $scope.blockingObject.render(function (dataURL) {
            //console.log($scope.blockingObject);
            //console.log('via render');
            //console.log(dataURL.length);
        });
    };
    $scope.blockingObject.callback = function (dataURL) {
        //console.log('via function');
        //console.log(dataURL.length);
    };
    $scope.size = 'small';
    $scope.type = 'square';
    $scope.imageDataURI = '';
    $scope.resImageDataURI = '';
    $scope.resBlob = {};
    $scope.urlBlob = {};
    $scope.resImgFormat = 'image/jpeg';
    $scope.resImgQuality = 0.9;
    $scope.selMinSize = 100;
    $scope.selInitSize = [{w: 200, h: 80}];
    $scope.resImgSize = [{w: 300, h: 300}, {w: 300, h: 300}];
    //$scope.aspectRatio=1.2;
    $scope.onChange = function ($dataURI) {
        //console.log('onChange fired');
    };
    $scope.onLoadBegin = function () {
        //console.log('onLoadBegin fired');
    };
    $scope.onLoadDone = function () {
        //console.log('onLoadDone fired');
    };
    $scope.onLoadError = function () {
        //console.log('onLoadError fired');
    };
    $scope.getBlob = function () {
        //console.log($scope.resBlob);
    };
    var handleFileSelect = function (evt) {
        var file = evt.currentTarget.files[0],
                reader = new FileReader();
        if (navigator.userAgent.match(/iP(hone|od|ad)/i)) {
            var canvas = document.createElement('canvas'),
                    mpImg = new MegaPixImage(file);

            canvas.width = mpImg.srcImage.width;
            canvas.height = mpImg.srcImage.height;

            EXIF.getData(file, function () {
                var orientation = EXIF.getTag(this, 'Orientation');

                mpImg.render(canvas, {
                    maxHeight: $scope.resImgSize,
                    orientation: orientation
                });
                setTimeout(function () {
                    var tt = canvas.toDataURL("image/jpeg", 1);
                    $scope.$apply(function ($scope) {
                        $scope.imageDataURI = tt;
                    });
                }, 100);
            });
        } else {
            reader.onload = function (evt) {
                $scope.$apply(function ($scope) {
                    //console.log(evt.target.result);
                    $scope.imageDataURI = evt.target.result;
                });
            };
            reader.readAsDataURL(file);
        }
    };
    angular.element(document.querySelector('#fileInput')).on('change', handleFileSelect);
    $scope.$watch('resImageDataURI', function () {
        //console.log('Res image', $scope.resImageDataURI);
    });
    /*--- Image cropper ---*/


    $scope.attachments = [];
    $scope.exisiting_attachments = {};
    var uploader = $scope.uploader = new FileUploader({
        url: base_url + 'online_admission/upload'
    });
    uploader.onSuccessItem = function(fileItem, response, status, headers) {
        if(response.status == 'success') {
            $scope.attachments.push(response.uploaded_file_name);
            $('#message').hide();
        } else {
            $("#remove").click()
            $('#message').show();
      }
    };
    uploader.onErrorItem = function(fileItem, response, status, headers) {
        console.info('onErrorItem', fileItem, response, status, headers);
    };
    uploader.onCancelItem = function(fileItem, response, status, headers) {
        console.info('onCancelItem', fileItem, response, status, headers);
    };

    $scope.oASModal = {};
    $scope.saveOrReplaceOnlineAdmissionSettings = function(){
        $scope.oASModal.attachments = $scope.attachments;
        $scope.oASModal.description = $("#yasir_textarea_editor").val();
        Loading("#oASettingForm", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'online_admission/save_settings', $scope.oASModal, config).then(
            function (success) {
                Loading("#oASettingForm", '<?php echo lang("loading_datatable") ?>', "", "hide");
                if(success.data.status == "success"){
                    showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                    $scope.initoASettings();
                } else if(success.data.status == "error"){
                    showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                }
            }, function (error) {
                Loading("#oASettingForm", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);   
            }
        );
    };

    $scope.initoASettings = function() {
        Loading("#oASettingForm", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'online_admission/get_online_admission_settings', {}, config).then(
            function (success) {
                Loading("#oASettingForm", '<?php echo lang("loading_datatable") ?>', "", "hide");
                if(success.data.status == "success"){
                    $("#yasir_textarea_editor").data("wysihtml5").editor.setValue(success.data.data.description);
                    $scope.exisiting_attachments = success.data.data.attachments;
                }
            }, function (error) {
                Loading("#oASettingForm", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);   
            }
        );
    };

    $scope.setTermAndCondition = function(ans){
        $scope.formModel.is_terms_and_conditions_agreed = ans;
        if($scope.formModel.is_terms_and_conditions_agreed == 'agree'){
            $("#myNav").css({"width":"0"});
        } else {
            alert("Please accept terms and condition / school instructions to further proceed online admission form.");
        }
    };

    $scope.changeLanguage = function (){
        window.location.href = base_url + 'LanguageSwitcher/switchLang/' +$scope.selectedlng;
    };
});

app2.controller("onlineClassesCtrl", function ($scope, $http, $window, $location) {
    //var domain = "online.uvschools.com";
    var domain = "meet.jit.si";
    $scope.activeData = {};
    $scope.showStart = true;
    $scope.classData = {};
    $scope.showJoinBtn = true;
    <?php $ci = & get_instance();
        $sess_role_id = $ci->session->userdata("userdata")['role_id'];
        $sess_started_by = $ci->session->userdata("userdata")['user_id'];
    ?>
    $scope.initClasses = function () {
        Loading("#attFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'attendance/getClasses', "", config).then(
                function (success) {
                    Loading("#attFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.classes = success.data;
                    //sconsole.log(success.data);
                },
                function (error) {
                    Loading("#attFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
        );
    };

    $scope.initBatches = function (class_id) {
        Loading("#attFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (class_id) {
            Loading("#attFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'attendance/getClassBatches', {class_id: class_id}, config).then(
                    function (success) {
                        Loading("#attFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.batches = success.data;
                        $scope.filterModel.batch_id = "";
                    },
                    function (error) {
                        console.log(error.data);
                        //Loading("#attFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        //$window.location.href = 'errors/' + error.status;
                    }
            );
        }
    };

    $scope.initSubjects = function (class_id, batch_id) {
        Loading("#syllabusFilterSubjects", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (class_id && batch_id) {
            Loading("#syllabusFilterSubjects", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'syllabus/getSubjects', {class_id: class_id, batch_id:batch_id}, config).then(
                function (success) {
                    Loading("#syllabusFilterSubjects", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.subjects = success.data;
                    $scope.filterModel.subject_id = "";
                },
                function (error) {
                    console.log(error.data);
                }
            );
        }
    };


    $scope.onSubmit = function (valid) {
        var el = document.getElementsByTagName("iframe")[0];
        if(el != undefined){
             el.parentNode.removeChild(el);
        }
   
       if (valid) {
           Loading("#att_search_filter", '<?php echo lang("loading_datatable") ?>', "", "show");
           $http.post(base_url + 'online_classes/getClassStatus', $scope.filterModel, config).then(
                   function (success) {
                       Loading("#att_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                       $scope.activeData.class_found = success.data.class_found;
                       $scope.activeData.class_name = success.data.class_name;
                       $scope.activeData.class_id = success.data.class_id;
                       $scope.activeData.batch_id = success.data.batch_id;
                       $scope.activeData.subject_id = success.data.subject_id;
                       $scope.activeData.class_name = success.data.class_name;
                       $scope.activeData.user_name = success.data.user_name;
                       $scope.activeData.subject = success.data.subject_name;
                       $scope.activeData.started_by = success.data.started_by;
                       console.log($scope.activeData);
                       if(success.data.class_found){
                            if($scope.activeData.started_by=='<?php echo $sess_started_by; ?>'){
                               console.log('show end class');
                               $scope.activeData.end_class = 'true';
                            }
                       }
                       
                       $scope.showStart = true;
                      
                   },
                   function (error) {
                       Loading("#att_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $window.location.href = 'errors/' + error.status;
                       // console.log(erro.status);
                   }
           );
       }
   };

   $scope.onSubmitAfterEnd = function (class_id, batch_id) {
   console.log($scope.activeData);
           Loading("#att_search_filter", '<?php echo lang("loading_datatable") ?>', "", "show");
           $http.post(base_url + 'online_classes/getClassStatus', {class_id : class_id, batch_id : batch_id}, config).then(
                   function (success) {
                    console.log(success);
                       Loading("#att_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                       $scope.activeData.class_found = success.data.class_found;
                       $scope.activeData.class_name = success.data.class_name;
                       $scope.activeData.class_id = success.data.class_id;
                       $scope.activeData.batch_id = success.data.batch_id;
                       $scope.activeData.subject_id = success.data.subject_id;
                       $scope.activeData.class_name = success.data.class_name;
                       $scope.activeData.user_name = success.data.user_name;
                       $scope.activeData.subject = success.data.subject_name;
                       $scope.activeData.started_by = success.data.started_by;
                       console.log($scope.activeData);
                       $scope.showStart = true;
                   },
                   function (error) {
                       Loading("#att_search_filter", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $window.location.href = 'errors/' + error.status;
                       // console.log(erro.status);
                   }
           );
   };

   $scope.startClass = function () {
           Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
           $http.post(base_url + 'online_classes/startClass', $scope.activeData, config).then(
                   function (success) {
                       Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                       $scope.activeData.class_name = success.data.class_name;
                       $scope.activeData.started_by = success.data.started_by;
                       $scope.showStart = false;
                       $scope.callApi(success.data.class_name, success.data.user_name, success.data.subject_name);
                       
                   },
                   function (error) {
                       Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $window.location.href = 'errors/' + error.status;
                       // console.log(erro.status);
                   }
           );
   };

   $scope.endClass = function () {
   
           Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
           $http.post(base_url + 'online_classes/endClass', $scope.activeData, config).then(
                   function (success) {
                   
                       Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                       $scope.onSubmitAfterEnd($scope.activeData.class_id, $scope.activeData.batch_id);
                       //$window.location.reload();
                       
                   },
                   function (error) {
                       Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $window.location.href = 'errors/' + error.status;
                       // console.log(erro.status);
                   }
           );
   };

   $scope.callApi = function(class_name, user_name, subject){
       var options = {
           roomName: class_name,
           height: '600px',
           parentNode: document.querySelector('#callDiv'),
           interfaceConfigOverwrite: {
                
                SHOW_BRAND_WATERMARK: false,
                SHOW_JITSI_WATERMARK: false,
                SHOW_WATERMARK_FOR_GUESTS: false,
                SHOW_POWERED_BY: false,
                
               TOOLBAR_BUTTONS: [
                   
                    'microphone', 'camera', 'closedcaptions', 'desktop', 'fullscreen',
                    'fodeviceselection', 'hangup', 'profile', 'chat', 'recording',
                    'livestreaming', 'etherpad', 'sharedvideo', 'settings', 'raisehand',
                    'videoquality', 'filmstrip', 'invite', 'feedback', 'stats', 'shortcuts',
                    'tileview', 'videobackgroundblur', 'download', 'help', 'mute-everyone'
                   
               ],
                APP_NAME: 'UVSchools',
                NATIVE_APP_NAME: 'UVSchools',
                PROVIDER_NAME: 'UVSchools',
                SHOW_CHROME_EXTENSION_BANNER: false,
                MOBILE_APP_PROMO: false,
                HIDE_DEEP_LINKING_LOGO: true,
                SHOW_PROMOTIONAL_CLOSE_PAGE: false,

            },
            <?php if($sess_role_id == '2' || $sess_role_id == '3'){?>
                configOverwrite: {
                    disableDeepLinking: true,
                    disableInviteFunctions: true,
                    doNotStoreRoom: true,
                    disableRemoteMute: true,
                    enableNoisyMicDetection: false,
                    enableDisplayNameChange: false,
                    remoteVideoMenu:{
                        disableKick: true,
                        
                    }
                },
            <?php } else{ ?>
                configOverwrite: {
                    enableDisplayNameChange: false,
                    doNotStoreRoom: true,
                    enableNoisyMicDetection: false
                    
                },
            <?php }?>
            
            userInfo: {
                displayName: user_name
            }
        }
        api = new JitsiMeetExternalAPI(domain, options);
        api.executeCommand('subject', subject);
        api.addEventListener('readyToClose', function(){
            api.dispose();
            if($scope.activeData.started_by == '<?php echo $sess_started_by; ?>'){
              $scope.endClass();
            }
        });
        }

        $scope.joinClassAsTeacher = function(class_name, user_name, subject){
            $scope.showStart = false;
            $('#join_class_of').hide();
           var options = {
               roomName: class_name,
               height: '600px',
               parentNode: document.querySelector('#callDiv'),
               interfaceConfigOverwrite: {
                    SHOW_BRAND_WATERMARK: false,
                    SHOW_JITSI_WATERMARK: false,
                    SHOW_WATERMARK_FOR_GUESTS: false,
                    SHOW_POWERED_BY: false,
                    
                   TOOLBAR_BUTTONS: [
                    'microphone', 'camera', 'closedcaptions', 'desktop', 'fullscreen',
                    'fodeviceselection', 'hangup', 'profile', 'chat', 'recording',
                    'livestreaming', 'etherpad', 'sharedvideo', 'settings', 'raisehand',
                    'videoquality', 'filmstrip', 'invite', 'feedback', 'stats', 'shortcuts',
                    'tileview', 'videobackgroundblur', 'download', 'help', 'mute-everyone'
                  
               ],
                    APP_NAME: 'UVSchools',
                    NATIVE_APP_NAME: 'UVSchools',
                    PROVIDER_NAME: 'UVSchools',
                    SHOW_CHROME_EXTENSION_BANNER: false,
                    MOBILE_APP_PROMO: false,
                    HIDE_DEEP_LINKING_LOGO: true,
                    SHOW_PROMOTIONAL_CLOSE_PAGE: false,

                },
                <?php if($sess_role_id == '2' || $sess_role_id == '3'){?>
                    configOverwrite: {
                        disableDeepLinking: true,
                        disableInviteFunctions: true,
                        doNotStoreRoom: true,
                        disableRemoteMute: true,
                        enableDisplayNameChange: false,
                        enableNoisyMicDetection: false,
                        remoteVideoMenu:{
                            disableKick: true,
                        }
                    },
                <?php } else{ ?>
                    configOverwrite: {
                        enableDisplayNameChange: false,
                        doNotStoreRoom: true,
                        enableNoisyMicDetection: false
                        
                    },
                <?php }?>
            
                userInfo: {
                    displayName: user_name
                }
            }
            api = new JitsiMeetExternalAPI(domain, options);
            api.executeCommand('subject', subject);
            api.addEventListener('readyToClose', function(){
                api.dispose();
                if($scope.activeData.started_by == '<?php echo $sess_started_by; ?>'){
                  $scope.endClass();
                }
               
            });
        };

        $scope.checkClasses = function () {
            var el = document.getElementsByTagName("iframe")[0];
            if(el != undefined){
                 el.parentNode.removeChild(el);
            }
            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'online_classes/checkClasses', '', config).then(
                   function (success) {
                       Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                       $scope.classData = success.data;
                       $scope.showJoinBtn = true;
                   },
                   function (error) {
                       Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $window.location.href = 'errors/' + error.status;
                       // console.log(error.status);
                   }
            );
   };

   $scope.joinClassAsStudent = function(class_name, student_name, subject){
        $scope.showJoinBtn = false;
       var options = {
           roomName: class_name,
           height: '600px',
           parentNode: document.querySelector('#callDiv'),
           interfaceConfigOverwrite: {
               SHOW_BRAND_WATERMARK: false,
                SHOW_JITSI_WATERMARK: false,
                SHOW_WATERMARK_FOR_GUESTS: false,
                SHOW_POWERED_BY: false,
               TOOLBAR_BUTTONS: [
                    'microphone', 'camera', 'desktop', 'fullscreen',
                    'hangup', 'chat', 'recording', 'raisehand',
                   
                  
               ],
               APP_NAME: 'UVSchools',
                NATIVE_APP_NAME: 'UVSchools',
                PROVIDER_NAME: 'UVSchools',
                SHOW_CHROME_EXTENSION_BANNER: false,
                MOBILE_APP_PROMO: false,
                HIDE_DEEP_LINKING_LOGO: true,
                SHOW_PROMOTIONAL_CLOSE_PAGE: false,

            },
            
            <?php if($sess_role_id == '2' || $sess_role_id == '3'){?>
                configOverwrite: {
                    disableDeepLinking: true,
                    disableInviteFunctions: true,
                    doNotStoreRoom: true,
                    disableRemoteMute: true,
                    enableNoisyMicDetection: false,
                    startWithAudioMuted: true,
                    startWithVideoMuted: true,
                    enableDisplayNameChange: false,
                    remoteVideoMenu:{
                        disableKick: true,
                    }
                },
            <?php } else{ ?>
                configOverwrite: {
                    disableInviteFunctions: true,
                    doNotStoreRoom: true,
                    disableRemoteMute: true,
                    enableNoisyMicDetection: false,
                    enableDisplayNameChange: false,
                    remoteVideoMenu:{
                        disableKick: true,
                    }
                },
            <?php }?>
            userInfo: {
                displayName: student_name
            }
        }
        api = new JitsiMeetExternalAPI(domain, options);
        api.executeCommand('subject', subject);
        api.addEventListener('readyToClose', function(){
            api.dispose();
            $scope.checkClasses();
        });        
        }


});

app2.controller("syllabusControllerTest", function ($scope, $http, $window, $location, $filter) {
    $scope.filterModel = {};
    $scope.classes = {};
    $scope.batches = {};
    $scope.subjects = {};
    $scope.weeks = {};
    $scope.addWeekModel = {};
    $scope.addCommentModel = {};
    $scope.addWeekDetailModel = {};
    $scope.editWeekDetailModel = {};
    $scope.editWeekModel = {};
    $scope.schoolWorkingDays = {};
    $scope.weeklySyllabus = {};
    $scope.workingDays = {};
    $scope.requestId="";
    $scope.requestStatus;
    $scope.syllabusCanEdit;
    $scope.isClick = false;
    $scope.adminIDs = [];
    $scope.confirmDoneId = '';
    $scope.ccModel = {};
    $scope.cModelClasses = [];
    $scope.cModelBatches = [];
    $scope.cModelSubjects = [];
    $scope.cModelWeeks = [];
    $scope.cModelWeekDetails = [];
    $scope.what_to_copy = {};
    $scope.temp={};
    $scope.initClasses = function () {
        
        Loading("#syllabusFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'attendance/getClasses', "", config).then(
            function (success) {
                Loading("#syllabusFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.classes = success.data;
                //console.log(success.data);
                
            },
            function (error) {
                Loading("#syllabusFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.status);
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };
    
    
    $scope.initBatches = function (class_id) {
        console.log("Batches");
        Loading("#syllabusFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (class_id) {
            Loading("#syllabusFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'attendance/getClassBatches', {class_id: class_id}, config).then(
                    function (success) {
                        Loading("#syllabusFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.batches = success.data;
                        $scope.filterModel.batch_id = "";
                    },
                    function (error) {
                        console.log(error.data);
                    }
            );
        }
    };
    
    $scope.initSubjects = function (class_id, batch_id) {
        Loading("#syllabusFilterSubjects", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (class_id && batch_id) {
            Loading("#syllabusFilterSubjects", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'study_plan/getSubjects', {class_id: class_id, batch_id:batch_id}, config).then(
                function (success) {
                    Loading("#syllabusFilterSubjects", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.subjects = success.data;
                    $scope.filterModel.subject_id = "";
                },
                function (error) {
                    console.log(error.data);
                }
            );
        }
    };
    
    $scope.initClasses2 = function () {
        Loading("#cModelClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'attendance/getClasses', "", config).then(
            function (success) {
                Loading("#cModelClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.cModelClasses = success.data;
                //sconsole.log(success.data);
            },
            function (error) {
                Loading("#cModelClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.status);
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };
    
    $scope.initBatches2 = function (class_id) {
        Loading("#cModelBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (class_id) {
            Loading("#cModelBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'attendance/getClassBatches', {class_id: class_id}, config).then(
                function (success) {
                    Loading("#cModelBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.cModelBatches = [];
                    angular.forEach(success.data, function(val, key) {
                        if(val.id !== $scope.filterModel.batch_id){
                            $scope.cModelBatches.push(val);
                        }
                    });
                    $scope.ccModel.batch_id = "";
                },
                function (error) {
                    console.log(error.data);
                }
            );
        }
    };
    
    $scope.initSubjects2 = function (class_id, batch_id) {
        Loading("#cModelSubjects", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (class_id && batch_id) {
            Loading("#cModelSubjects", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'study_plan/getSubjects', {class_id: class_id, batch_id:batch_id}, config).then(
                function (success) {
                    Loading("#cModelSubjects", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.cModelSubjects = success.data;
                    $scope.ccModel.subject_id = "";
                },
                function (error) {
                    console.log(error.data);
                }
            );
        }
    };
    
    $scope.initCopyData = function(data){
        $scope.copyWeekForm.$setUntouched();
        $scope.copyWeekForm.$setPristine();
        $scope.ccModel = {};
        $scope.what_to_copy = data;
    };
    
    $scope.saveCopiedWeek = function(){
        Loading("#save-copied-week-model-content", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'study_plan/copySyllabus', {what:$scope.what_to_copy, 'where':$scope.ccModel}, config).then(
            function (success) {
                Loading("#save-copied-week-model-content", '<?php echo lang("loading_datatable") ?>', "", "hide");
                if(success.data.status == 'success'){
                    $("#copyWeekModal").modal("hide");
                    showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                    $scope.onSubmit();
                } else if(success.data.status == 'error'){
                    showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                }
                //console.log(success.data);
            },
            function (error) {
                Loading("#save-copied-week-model-content", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };
    
        $scope.onSubmitNew = function(){
             Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
             $scope.request_idstudyplan();
        };
        $scope.request_idstudyplan = function(){
            $http.post(base_url + 'study_plan/getrequestidforstudyplan',  $scope.filterModel, config).then(
                    function (success) {
                        var my_dates = [];
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        //console.log(success.data);
                        //$scope.weeklySyllabus = success.data.syllabus;
                        $scope.syllabusCanEdit = success.data.can_syllabus_edit;
                        $scope.requestId = success.data.request_id;
                        $scope.requestStatus = success.data.reqeust_status;
                         //$.getScript("assets/fullcalendar/js/studyplan.php");
                         $('#calendar').fullCalendar('refetchEvents');
                         if(success.data.reqeust_status == 'inprocess' || success.data.reqeust_status == 'approved' || success.data.reqeust_status == 'edit-request') {
                             //$("#calendar").addClass("disabledbutton");
                         }
                        if(success.data.reqeust_status == 'draft'){
                            //$('#calendar').removeClass("disabledbutton");
                        }
                         $("#calendar").show();
                        //console.log(success.data.reqeust_status);
                    },
                    function (error) {
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        console.log(error.data);
                    }
            );
        };
   
    $scope.saveWeek = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'study_plan/saveWeek', angular.extend($scope.addWeekModel, $scope.filterModel), config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    if(success.data.status == 'success'){
                        $("#addWeekModal").modal("hide");
                        showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                        $scope.onSubmit();
                    } else if(success.data.status == 'error'){
                        showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                    }
                    
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    };
    
    $scope.initWeekDetailModal = function(id, day){
        $scope.addWeekDetailModel.selectedDate = day;
        $scope.addWeekDetailModel.selectedWeekId = id; 
    };
    
    $scope.initEditWeekModal = function(week){
        var start_date = $filter('date')(new Date(week.start_date.split('-').join('/')), "dd/M/yyyy");
        var end_date = $filter('date')(new Date(week.end_date.split('-').join('/')), "dd/M/yyyy");
        
        $scope.editWeekModel.id = week.id;
        $scope.editWeekModel.class_id = week.class_id;
        $scope.editWeekModel.batch_id = week.batch_id;
        $scope.editWeekModel.start_date = start_date;
        $scope.editWeekModel.end_date = end_date;
        $scope.editWeekModel.subject_id = week.subject_id;
        $scope.editWeekModel.week = week.week;
    };
    
    $scope.saveEditWeek = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'study_plan/updateWeek', $scope.editWeekModel, config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    if(success.data.status === 'success'){
                        $("#editWeekModal").modal("hide");
                        $scope.onSubmit();
                    }else if(success.data.status === 'error'){
                        showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                    }
                    //console.log(success.data);
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    };
    
    $scope.saveWeekDetail_test = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        console.log($scope.addWeekDetailModel);
        //angular.extend($scope.addWeekDetailModel, $scope.filterModel)
        $http.post(base_url + 'study_plan/saveWeekDetail', angular.extend($scope.addWeekDetailModel, $scope.filterModel), config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $("#addWeekDetailModal").modal("hide");
                    $scope.addWeekDetailModel.topic = "";
                    $scope.addWeekDetailModel.status = "Pending";
                    $scope.addWeekDetailModel.comment = "";
                    $scope.onSubmit();
                    //console.log(success.data);
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    };
    
    $scope.changeStatusWithConfirmation = function(id){
        $("#doneSyllabusModal").modal("show");
        $scope.confirmDoneId = id;
    };
    
    $scope.changeStatus = function(status, id){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        if(status === 'Partially Done' || status==='Reschedule'){
            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
            $scope.addCommentModel.status = status;
            $scope.addCommentModel.id = id;
            $("#addCommentModal").modal("show");
        } else {
            if(status == "Done"){
                $("#doneSyllabusModal").modal("hide");
                $scope.confirmDoneId = '';
            }
            $http.post(base_url + 'syllabus/changeSyllabusStatus', {status:status, id: id}, config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    if(success.data.status === "success"){
                        if(status == "Done"){
                            var otherData = {'class_id':$scope.filterModel.class_id, 'batch_id':$scope.filterModel.batch_id, subject_id:$scope.filterModel.subject_id};
                            $scope.getAllGuardians(otherData);
                        }
                        showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                        $scope.onSubmit();
                    } else {
                        showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                    }
                    //console.log(success.data);
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
            );
        }
    };
    
    $scope.saveComment = function(){
        $http.post(base_url + 'syllabus/addCommentAndChangeStatus', $scope.addCommentModel, config).then(
            function (success) {
                if(success.data.status === "success"){
                    $("#addCommentModal").modal("hide");
                    showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                    $scope.onSubmit();
                } else {
                    showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                }
                //console.log(success.data);
            },
            function (error) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };
    
    $scope.initEditWeekDetailModal = function(obj){
        $scope.editWeekDetailModel = obj;
        //$scope.editWeekDetailModel.syllabus_week_id = id;
    };
    
    $scope.updateWeekDetail = function(){
        $http.post(base_url + 'syllabus/updateWeekDetails', $scope.editWeekDetailModel, config).then(
            function (success) {
                if(success.data.status === "success"){
                    $("#editWeekDetailModal").modal("hide");
                    showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                    $scope.onSubmit();
                } else {
                    showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                }
                //console.log(success.data);
            },
            function (error) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };
    
    $scope.request = function(id, status){
        console.log(id);
        $scope.my_new_id = id;
        $scope.my_new_status = status;
        
        };
   $scope.confirm_request2 = function(){
   console.log($scope.requestText);
}
        $scope.confirm_request = function(state){
        console.log($scope.requestText);
            if($scope.requestText != null){
                
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
                $http.post(base_url + 'study_plan/reqForApprovalSyls', {id:$scope.my_new_id,status:$scope.my_new_status,reason:$scope.requestText, state:state}, config).then(
                   function (success) {
                   $('.edit_attendance_request_model').modal('hide');
                        showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                        $scope.r_id = $scope.my_new_id;
                        var otherData = {class_id:$scope.filterModel.class_id,batch_id:$scope.filterModel.batch_id,subject_id:$scope.filterModel.subject_id};
                        $scope.getSchoolAdmins(otherData);
                        
                        $scope.onSubmitNew();
                        
                        $scope.requestText ="";
                        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                   },
                   function (error) {
                       Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                       console.log(error.data);
                   });
            }else{
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $('#request_error').show();
            }
           };
    $scope.getSchoolAdmins = function(otherData){
        $http.post(base_url + 'study_plan/getSchoolAdmins', {}, config).then(
            function(success){
                publicNotificationViaPusher("lbl_approval_syllabus", otherData, success.data.new_ids, "applications/all", {'sender': success.data.sender}, $scope.r_id);
                //$scope.adminIDs = success.data;
            },
            function(error){
                console.log(error.data);
            }
        );
    };
    
    $scope.getAllGuardians = function(otherData){
        $http.post(base_url + 'study_plan/getAllGuardians', otherData, config).then(
            function(success){
                publicNotificationViaPusher("lbl_study_plan_status_change_to_done", otherData, success.data.new_ids, "applications/all", {'sender': success.data.sender}, $scope.r_id);
            },
            function(error){
                console.log(error.data);
            }
        );
    };
    
    $scope.deleteSyllabusOfDay = function(d){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'study_plan/deleteSyllabusOfDay', d, config).then(
                function (success) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                    $scope.onSubmit();

                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    };
    
    
    $scope.deleteSyllabusOfWeek = function(id){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'study_plan/deleteSyllabusOfWeek', {id:id}, config).then(
            function (success) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                $scope.onSubmit();
            },
            function (error) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };
});

// added by sheraz
app2.controller("userManagementCrtl", function ($scope, $http) {

<?php $ci = & get_instance();
  $sess_role_id = $ci->session->userdata("userdata")['role_id'];
  $arr = $ci->session->userdata("userdata")['persissions'];
  $array = json_decode($arr);
  $changeEmpStatus = $changeStdStatus = $changeGuardianStatus = 0;
  if($array){

    foreach ($array as $key => $value) {
       if (in_array('manage-changeEmpStatus', array($value->permission)) && $value->val == 'true') {
          $changeEmpStatus = '1';
      }
      if (in_array('manage-changeStdStatus', array($value->permission)) && $value->val == 'true') {
          $changeStdStatus = '1';
      }
      if (in_array('manage-changeGuardianStatus', array($value->permission)) && $value->val == 'true') {
          $changeGuardianStatus = '1';
      }
    }
  }
?>

    $scope.getUsers = function(role_id){
        
        $("#tableContainer").removeClass("hide");
        $("#tableContainer").addClass("show");
        if(role_id == <?php echo STUDENT_ROLE_ID; ?>){
            $("#studentTab").addClass("myactive");
            $("#employeeTab").removeClass("myactive");
            $("#parentTab").removeClass("myactive");
            var table = $('#myTablee_users').DataTable({
                "language": {
                    "decimal":        "",
                    "emptyTable":     '<?php echo lang("no_data_table"); ?>',
                    "info":           '<?php echo lang("data_info"); ?>',
                    "infoEmpty":      '<?php echo lang("infoempty"); ?>',
                    "infoFiltered":   '<?php echo lang("filter_datatable"); ?>',
                    "infoPostFix":    "",
                    "thousands":      ",",
                    "lengthMenu":     '<?php echo lang("show_datatable"); ?>',
                    "loadingRecords": '<?php echo lang("loading_datatable"); ?>',
                    "processing":     '<?php echo lang("processing_datatable"); ?>',
                    "search":         '<?php echo lang("search"); ?>:',
                    "zeroRecords":    '<?php echo lang("no_record_datatable"); ?>',
                    "paginate": {
                        "first":      '<?php echo lang("first"); ?>',
                        "last":       '<?php echo lang("last"); ?>',
                        "next":       '<?php echo lang("btn_next"); ?>',
                        "previous":   '<?php echo lang("previous"); ?>'
                    },
                    "aria": {
                        "sortAscending":  ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    }
                },
                columnDefs: [
                    {
                        targets: -1,
                        className: 'dt-body-center'
                    }
                ],
                dom: 'Bfrtip',
                ajax: {
                    type: "POST",
                    url: base_url+'settings/getUsers/'+role_id,
                    data: '',
                    dataSrc: '',
                },
                columns: [
                    {
                        'className':      'details-control',
                        'orderable':      false,
                        'data':           null,
                        'defaultContent': ''
                    },
                    {title: '<?php echo lang("lbl_avatar");?>', data: '', render : function (data, type, row) {
                            if(row.avatar == null){
                                return '<img src="<?php echo base_url(); ?>uploads/user/profile.png" class="img-circle" style="height: 40px;width: 40px" />';
                            } else {
                                return '<img src="<?php echo base_url(); ?>uploads/user/' +row.avatar+ '" class="img-circle" style="height: 40px;width: 40px" />';
                            }                  
                        }
                    },
                    {title: '<?php echo lang("lbl_name");?>', data: 'name' },
                    {title: '<?php echo lang("father_name"); ?>', data: 'guardian_name' },
                    {title: '<?php echo lang("class_name"); ?>', data: 'class_name' },
                    {title: '<?php echo lang("lbl_rollno"); ?>', data: 'rollno' },
                    {title: '<?php echo lang("lbl_status");?>', data: 'status', render: function(data, type, row) {
                            if(row.status == '0'){
                                return "<span class='text-success'><i class='fa fa-check'></i></span>";
                            } else if(row.status == '1'){
                                return "<span class='text-danger'><i class='fa fa-times'></i></span>";
                            }
                        } 
                    },
                    {title: '<?php echo lang("login_datetime");?>', data: 'login_time'},
                    {title: '<?php echo lang("logout_datetime");?>', data: 'logout_time'},
                    {title: '<?php echo lang("lbl_action");?>', data: '', render: function(data, type, row) {
                            if(row.status == '0'){
                                var activeShow = "style='display:none';";
                                var deactiveShow = "style='display:inline-block';";
                            } else if(row.status == '1'){
                                var activeShow = "style='display:inline-block';";
                                var deactiveShow = "style='display:none';";
                            }
                            return "<?php if($sess_role_id == '1' || ($sess_role_id == '4' && $changeStdStatus == '1')) {?><a href='javascript:void(0);' class='btn btn-success btn-circle sa-status1' title='Activate the user' value='"+row.id+",students/statusActivate' "+activeShow+"><i class='fa fa-check'></i></a>"+
                            "<a  href='javascript:void(0);' class='btn btn-danger btn-circle sa-status' value='"+row.id+",students/statusDeactivate' "+deactiveShow+" title='Deactivate the user'><i class='fa fa-times'></i><?php } ?>"
                            +"</a>";
                        } 
                    },
                ],
                buttons: [
                    {
                        extend: 'copyHtml5',
                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                    },
                    {
                        extend: 'excelHtml5',
                    },
                    {
                        extend: 'csvHtml5',
                    },
                    {
                        extend: 'pdfHtml5',
                    }
                ],
                destroy: true
            });
        } else if(role_id == <?php echo EMPLOYEE_ROLE_ID; ?>){
            $("#studentTab").removeClass("myactive");
            $("#employeeTab").addClass("myactive");
            $("#parentTab").removeClass("myactive");
            var table = $('#myTablee_users').DataTable({
                "language": {
                    "decimal":        "",
                    "emptyTable":     '<?php echo lang("no_data_table"); ?>',
                    "info":           '<?php echo lang("data_info"); ?>',
                    "infoEmpty":      '<?php echo lang("infoempty"); ?>',
                    "infoFiltered":   '<?php echo lang("filter_datatable"); ?>',
                    "infoPostFix":    "",
                    "thousands":      ",",
                    "lengthMenu":     '<?php echo lang("show_datatable"); ?>',
                    "loadingRecords": '<?php echo lang("loading_datatable"); ?>',
                    "processing":     '<?php echo lang("processing_datatable"); ?>',
                    "search":         '<?php echo lang("search"); ?>:',
                    "zeroRecords":    '<?php echo lang("no_record_datatable"); ?>',
                    "paginate": {
                        "first":      '<?php echo lang("first"); ?>',
                        "last":       '<?php echo lang("last"); ?>',
                        "next":       '<?php echo lang("btn_next"); ?>',
                        "previous":   '<?php echo lang("previous"); ?>'
                    },
                    "aria": {
                        "sortAscending":  ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    }
                },
                columnDefs: [
                    {
                        targets: -1,
                        className: 'dt-body-center'
                    }
                ],
                dom: 'Bfrtip',
                ajax: {
                    type: "POST",
                    url: base_url+'settings/getUsers/'+role_id,
                    data: '',
                    dataSrc: '',
                },
                columns: [
                    {
                        'className':      'details-control',
                        'orderable':      false,
                        'data':           null,
                        'defaultContent': ''
                    },
                    {title: '<?php echo lang("lbl_avatar");?>', data: '', render : function (data, type, row) {
                            if(row.avatar == null){
                                return '<img src="<?php echo base_url(); ?>uploads/user/profile.png" class="img-circle" style="height: 40px;width: 40px" />';
                            } else {
                                return '<img src="<?php echo base_url(); ?>uploads/user/' +row.avatar+ '" class="img-circle" style="height: 40px;width: 40px" />';
                            }                  
                        }
                    },
                    {title: '<?php echo lang("lbl_name");?>', data: 'name' },
                    {title: '<?php echo lang("heading_position"); ?>', data: 'job_title' },
                    {title: '<?php echo lang("lbl_role_category"); ?>', data: 'role_category' },
                    {title: '<?php echo lang("title_department"); ?>', data: 'title_department' },
                    {title: '<?php echo lang("lbl_status");?>', data: 'status', render: function(data, type, row) {
                            if(row.status == '0'){
                                return "<span class='text-success'><i class='fa fa-check'></i></span>";
                            } else if(row.status == '1'){
                                return "<span class='text-danger'><i class='fa fa-times'></i></span>";
                            }
                        } 
                    },
                    {title: '<?php echo lang("login_datetime");?>', data: 'login_time'},
                    {title: '<?php echo lang("logout_datetime");?>', data: 'logout_time'},
                    {title: '<?php echo lang("lbl_action");?>', data: '', render: function(data, type, row) {
                            if(row.status == '0'){
                                var activeShow = "style='display:none';";
                                var deactiveShow = "style='display:inline-block';";
                            } else if(row.status == '1'){
                                var activeShow = "style='display:inline-block';";
                                var deactiveShow = "style='display:none';";
                            }
                            return "<?php if($sess_role_id == '1' || ($sess_role_id == '4' && $changeEmpStatus == '1')) {?><a href='javascript:void(0);' class='btn btn-success btn-circle sa-status1' title='Activate the user' value='"+row.id+",students/statusActivate' "+activeShow+"><i class='fa fa-check'></i></a>"
                            +"<a  href='javascript:void(0);' class='btn btn-danger btn-circle sa-status' value='"+row.id+",students/statusDeactivate' "+deactiveShow+" title='Deactivate the user'><i class='fa fa-times'></i><?php } ?></a>";
                        } 
                    },
                ],
                buttons: [
                    {
                        extend: 'copyHtml5',
                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                    },
                    {
                        extend: 'excelHtml5',
                    },
                    {
                        extend: 'csvHtml5',
                    },
                    {
                        extend: 'pdfHtml5',
                    }
                ],
                destroy: true
            });
        } else if(role_id == <?php echo PARENT_ROLE_ID; ?>){
            $("#studentTab").removeClass("myactive");
            $("#employeeTab").removeClass("myactive");
            $("#parentTab").addClass("myactive");
            var table = $('#myTablee_users').DataTable({
                "language": {
                    "decimal":        "",
                    "emptyTable":     '<?php echo lang("no_data_table"); ?>',
                    "info":           '<?php echo lang("data_info"); ?>',
                    "infoEmpty":      '<?php echo lang("infoempty"); ?>',
                    "infoFiltered":   '<?php echo lang("filter_datatable"); ?>',
                    "infoPostFix":    "",
                    "thousands":      ",",
                    "lengthMenu":     '<?php echo lang("show_datatable"); ?>',
                    "loadingRecords": '<?php echo lang("loading_datatable"); ?>',
                    "processing":     '<?php echo lang("processing_datatable"); ?>',
                    "search":         '<?php echo lang("search"); ?>:',
                    "zeroRecords":    '<?php echo lang("no_record_datatable"); ?>',
                    "paginate": {
                        "first":      '<?php echo lang("first"); ?>',
                        "last":       '<?php echo lang("last"); ?>',
                        "next":       '<?php echo lang("btn_next"); ?>',
                        "previous":   '<?php echo lang("previous"); ?>'
                    },
                    "aria": {
                        "sortAscending":  ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    }
                },
                columnDefs: [
                    {
                        targets: -1,
                        className: 'dt-body-center'
                    }
                ],
                dom: 'Bfrtip',
                ajax: {
                    type: "POST",
                    url: base_url+'settings/getUsers/'+role_id,
                    data: '',
                    dataSrc: '',
                },
                columns: [
                    {
                        'className':      'details-control',
                        'orderable':      false,
                        'data':           null,
                        'defaultContent': ''
                    },
                    {title: '<?php echo lang("lbl_avatar");?>', data: '', render : function (data, type, row) {
                            if(row.avatar == null){
                                return '<img src="<?php echo base_url(); ?>uploads/user/profile.png" class="img-circle" style="height: 40px;width: 40px" />';
                            } else {
                                return '<img src="<?php echo base_url(); ?>uploads/user/' +row.avatar+ '" class="img-circle" style="height: 40px;width: 40px" />';
                            }                  
                        }
                    },
                    {title: '<?php echo lang("lbl_name");?>', data: 'name' },
                    {title: '<?php echo lang("imp_std_email"); ?>', data: 'email' },
                    {title: '<?php echo lang("lbl_contact_std"); ?>', data: 'contact' },
                    {title: '<?php echo lang("lbl_gender"); ?>', data: 'gender' },
                    {title: '<?php echo lang("lbl_status");?>', data: 'status', render: function(data, type, row) {
                            if(row.status == '0'){
                                return "<span class='text-success'><i class='fa fa-check'></i></span>";
                            } else if(row.status == '1'){
                                return "<span class='text-danger'><i class='fa fa-times'></i></span>";
                            }
                        } 
                    },
                    {title: '<?php echo lang("login_datetime");?>', data: 'login_time'},
                    {title: '<?php echo lang("logout_datetime");?>', data: 'logout_time'},
                    {title: '<?php echo lang("lbl_action");?>', data: '', render: function(data, type, row) {
                            if(row.status == '0'){
                                var activeShow = "style='display:none';";
                                var deactiveShow = "style='display:inline-block';";
                            } else if(row.status == '1'){
                                var activeShow = "style='display:inline-block';";
                                var deactiveShow = "style='display:none';";
                            }
                            return "<?php if($sess_role_id == '1' || ($sess_role_id == '4' && $changeGuardianStatus == '1')) {?><a href='javascript:void(0);' class='btn btn-success btn-circle sa-status1' title='Activate the user' value='"+row.id+",students/statusActivate' "+activeShow+"><i class='fa fa-check'></i></a><a  href='javascript:void(0);' class='btn btn-danger btn-circle sa-status' value='"+row.id+",students/statusDeactivate' "+deactiveShow+" title='Deactivate the user'><i class='fa fa-times'></i><?php } ?></a>";
                        } 
                    },
                ],
                buttons: [
                    {
                        extend: 'copyHtml5',
                        orientation: 'landscape',
                        pageSize: 'LEGAL'
                    },
                    {
                        extend: 'excelHtml5'
                    },
                    {
                        extend: 'csvHtml5'
                    },
                    {
                        extend: 'pdfHtml5'
                    }
                ],
                destroy: true
            });
        }

        // Add event listener for opening and closing details
        $('#myTablee_users tbody').on('click', 'td.details-control', function(){
            var tr = $(this).closest('tr');
            var row = table.row( tr );

            if(row.child.isShown()){
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                row.child(format(row.data())).show();
                tr.addClass('shown');
            }
        });

    };
});

// announcement controller added by yasir
app2.controller("announcementsCtrl", function ($scope, $http, FileUploader, $sce) {
    $scope.bModel = {};
    $scope.sModel = {'attachments':[]};
    $scope.classes = [];
    $scope.batches = [];
    $scope.departments = [];
    $scope.categories = [];
    $scope.employees = [];
    $scope.yannouncements = [];
    $scope.eModel = {};

    var uploader = $scope.uploader = new FileUploader({
        url: base_url + 'announcements/upload'
    });
    uploader.onSuccessItem = function(fileItem, response, status, headers) {
        if(response.status == 'success') {
            $scope.sModel.attachments.push(response.uploaded_file_name);
            $('#message').hide();
        } else {
            $("#remove").click()
            $scope.message = response.message;
            $('#message').show();
      }
    };
    uploader.onErrorItem = function(fileItem, response, status, headers) {
        console.info('onErrorItem', fileItem, response, status, headers);
    };
    uploader.onCancelItem = function(fileItem, response, status, headers) {
        console.info('onCancelItem', fileItem, response, status, headers);
    };

    var uploader2 = $scope.uploader2 = new FileUploader({
        url: base_url + 'announcements/upload'
    });
    uploader2.onSuccessItem = function(fileItem, response, status, headers) {
        if(response.status == 'success') {
            $scope.eModel.attachments.push(response.uploaded_file_name);
            $('#messageEdit').hide();
        } else {
            $("#removeEdit").click()
            $scope.message = response.message;
            $('#messageEdit').show();
      }
    };
    uploader2.onErrorItem = function(fileItem, response, status, headers) {
        console.info('onErrorItem', fileItem, response, status, headers);
    };
    uploader2.onCancelItem = function(fileItem, response, status, headers) {
        console.info('onCancelItem', fileItem, response, status, headers);
    };

    $scope.setBrowseModel = function(ann){
        $("#annBrowseModal").modal("show");
        $('.textarea_editor_announcement1').data("wysihtml5").editor.setValue(ann.details);
        $scope.bModel.details = ann.details;
        $scope.bModel.title = ann.title;
        $scope.bModel.from_date = ann.from_date;
        $scope.bModel.to_date = ann.to_date;
        $scope.bModel.level = ann.level;
        $scope.bModel.department_names_string = ann.department_names_string;
        $scope.bModel.category_names_string = ann.category_names_string;
        $scope.bModel.employee_names_string = ann.employee_names_string;
        $scope.bModel.class_names_string = ann.class_names_string;
        $scope.bModel.section_names_string = ann.section_names_string;
        $scope.bModel.status = ann.status;
        $scope.bModel.created_at = ann.created_at;
        $scope.bModel.img_or_document_type = ann.img_or_document_type;
        $scope.bModel.img_or_document = ann.img_or_document;
        
    };

    $scope.showAddModal = function(){
        $("#annAddModal").modal({
            backdrop: 'static'
        });
    };

    $scope.initClasses = function () {
        $http.post(base_url + 'attendance/getClasses', "", config).then(
            function (success) {
                $scope.classes = success.data;
            },
            function (error) {
                $window.location.href = 'errors/' + error.status;
            }
        );
    };

    $scope.initDepartments = function(){
        $http.post(base_url + 'employee/getDepartments', "", config).then(
            function (success) {
                $scope.departments = success.data.departments;
            }, 
            function(error){
                console.log(error.data);
            }
        );
    };

    $scope.initsModal = function(){
        $("#uploadfile").val(null);
        uploader.queue = [];
        if($scope.sModel.level == 'employees'){
            $scope.sModel.classes = [];
            $scope.sModel.section = [];
        } else if($scope.sModel.level == 'students'){
            $scope.sModel.departments = [];
            $scope.sModel.employees = [];        
            $scope.sModel.categories = [];        
        }  
    };

    $scope.initeModal = function(){
        $("#uploadfile2").val(null);
        uploader2.queue = [];
        if($scope.eModel.level == 'employees'){
            $scope.eModel.classes = [];
            $scope.eModel.section = [];
        } else if($scope.eModel.level == 'students'){
            $scope.eModel.departments = [];
            $scope.eModel.employees = [];        
            $scope.eModel.categories = [];        
        }  
    };

    $scope.save = function(){
        Loading("#ann-add-modal-contents", '<?php echo lang("loading_datatable") ?>', "", "show");
        var text = $('.textarea_editor_announcement').val();
        $scope.sModel.details = text;
        $http.post(base_url + 'announcements/save', $scope.sModel, config).then(
            function (success) {
                Loading("#ann-add-modal-contents", '<?php echo lang("loading_datatable") ?>', "", "hide");
                if(success.data.status == "success"){
                    $scope.sModel = {'attachments':[], 'level': 'all', 'status': 'active', 'details': ''};
                    $scope.annAddForm.$setUntouched();
                    $scope.annAddForm.$setPristine();
                    $("#annAddModal").modal("hide");
                    uploader.queue = [];
                    $("#uploadfile").val(null);
                    $scope.initAnnouncements();
                    showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                    var otherData = {};
                    publicNotificationViaPusher("new_announcement", otherData,  success.data.recipants, 'announcements/details/'+success.data.new_announcement_id, {'sender': success.data.sender}, 0);
                }
            },
            function (error) {
                Loading("#ann-add-modal-contents", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };

    $scope.initBatches = function(){
        Loading("#select2-section-container", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'announcements/getBatches', {class_ids: $scope.sModel.classes}, config).then(
            function (success) {
                Loading("#select2-section-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.batches = success.data;
            },
            function (error) {
                Loading("#select2-section-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };

    $scope.initEditBatches = function(){
        Loading("#select2-edit-section-container", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'announcements/getBatches', {class_ids: $scope.eModel.classes}, config).then(
            function (success) {
                $scope.batches = success.data;
                setTimeout(function(){
                    var arr = $scope.eModel.sections.split(",");
                    $("#select2-edit-section").select2({}).val(arr).change();
                    Loading("#select2-edit-section-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                }, 100);
            },
            function (error) {
                Loading("#select2-edit-section-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };

    $scope.initRoleCategories = function () {
        Loading("#select2-categories-container", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'announcements/getCategoriesByDepartmentID', {departments: $scope.sModel.departments}, config).then(
            function (success) {
                $scope.categories = success.data;
                Loading("#select2-categories-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
            },
            function (error) {
                console.log(error.data);
                Loading("#select2-categories-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
            }
        );
    };

    $scope.initEditRoleCategories = function () {
        Loading("#select2-edit-categories-container", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'announcements/getCategoriesByDepartmentID', {departments: $scope.eModel.departments}, config).then(
            function (success) {
                $scope.categories = success.data;
                setTimeout(function(){
                    if($scope.eModel.categories != ""){
                        var arr = $scope.eModel.categories.split(",");
                        $("#select2-edit-categories").select2({}).val(arr).change();
                    } else {
                        $("#select2-edit-categories").select2();
                    }
                    Loading("#select2-edit-categories-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                }, 100);
            },
            function (error) {
                console.log(error.data);
                Loading("#select2-edit-categories-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
            }
        );
    };

    $scope.initEmployees = function(){
        Loading("#select2-employees-container", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/getEmployees', {categories: $scope.sModel.categories, departments: $scope.sModel.departments}, config).then(
            function (success) {
                Loading("#select2-employees-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.employees = success.data.employees;
            },
            function (error) {
                Loading("#select2-employees-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    }

    $scope.initEditEmployees = function(){
        Loading("#select2-edit-employees-container", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'accounts/getEmployees', {categories: $scope.eModel.categories, departments: $scope.eModel.departments}, config).then(
            function (success) {
                $scope.employees = success.data.employees;

                setTimeout(function(){
                    if($scope.eModel.employees != ""){
                        var arr = $scope.eModel.employees.split(",");
                        $("#select2-edit-employees").select2({}).val(arr).change();
                    } else {
                        $("#select2-edit-employees").select2();
                    }
                    Loading("#select2-edit-employees-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                }, 100);
            },
            function (error) {
                Loading("#select2-edit-employees-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    }

    $scope.setEditModel = function(item){
        $scope.eModel = angular.copy(item);
        $('.textarea_editor_announcement1').data("wysihtml5").editor.setValue($scope.eModel.details);
        $scope.eModel.attachments = [];
        $("#uploadfile2").val(null);
        uploader2.queue = [];
        $("#annEditModal").modal("show");
        setTimeout(function(){
            if($scope.eModel.level == 'employees'){
                if($scope.eModel.departments != ""){
                    var arr = $scope.eModel.departments.split(",");
                    $("#select2-edit-departments").select2({}).val(arr).change();
                } else {
                    $("#select2-edit-departments").select2();
                    $("#select2-edit-categories").select2();
                    $("#select2-edit-employees").select2();
                }
            } else if($scope.eModel.level == 'students'){
                if($scope.eModel.classes != ""){
                    var arr2 = $scope.eModel.classes.split(",");
                    $("#select2-edit-classes").select2({}).val(arr2).change();
                } else {
                    $("#select2-edit-classes").select2();
                    $("#select2-edit-section").select2();
                }
            }
        }, 100);
    };

    $scope.initAnnouncements = function(){
        Loading("#announcements-table", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'announcements/all', {}, config).then(
            function (response) {
                Loading("#announcements-table", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.yannouncements = response.data;
                for (var i = 0, len = $scope.yannouncements.length; i < len; i++) {

                        $scope.yannouncements[i].details = $sce.trustAsHtml($scope.yannouncements[i].details);

                    }
            }, function (error) {
                Loading("#announcements-table", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);   
            }
        );
    };

    $scope.update = function(){
        Loading("#ann-edit-modal-contents", '<?php echo lang("loading_datatable") ?>', "", "show");
        var text = $('.textarea_editor_announcement1').val();
        $scope.eModel.details = text;
        $http.post(base_url + 'announcements/update', $scope.eModel, config).then(
            function (success) {
                Loading("#ann-edit-modal-contents", '<?php echo lang("loading_datatable") ?>', "", "hide");
                if(success.data.status == "success"){
                    $scope.eModel = {};
                    $scope.annEditForm.$setUntouched();
                    $scope.annEditForm.$setPristine();
                    $("#annEditModal").modal("hide");
                    uploader2.queue = [];
                    $("#uploadfile2").val(null);
                    $scope.initAnnouncements();
                    showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                    var otherData = {};
                    publicNotificationViaPusher("new_announcement", otherData,  success.data.recipants, 'announcements/details/'+success.data.new_announcement_id, {'sender': success.data.sender}, 0);
                }
            },
            function (error) {
                Loading("#ann-edit-modal-contents", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
                //$window.location.href = 'errors/' + error.status;
            }
        );
    };
});

// added by yasir online exams controller
app2.controller("onlineExamsController", function ($scope, $http, $interval, $window) {
    $scope.filterModel = {};
    $scope.filterModel3 = {};
    $scope.resultModel = {};
    $scope.exams = [];
    $scope.exams2 = [];
    $scope.papers = [];
    $scope.questionModel = [];
    $scope.questionModelHasData = false;
    $scope.submitted = '';
    $scope.selected_exam_id = '';
    $scope.selected_paper_id = '';
    $scope.paper = '';
    $scope.results = [];
    $scope.selected_paper_number_of_questions = [];
    $scope.browseModel = {};
    $scope.editModel = {};
    $scope.editRequestData = {};
    $scope.retakeRequestData = {};
    $scope.requestText = '';
    $scope.requestText2 = '';
    $scope.selected_paper_details = {};
    $scope.publishModel = {};
    $scope.main_exams = [];

    $scope.remark = "";

    $scope.setEditRequestData = function(r){
        $scope.editRequestData.class_id = r.class_id;
        $scope.editRequestData.batch_id = r.batch_id;
        $scope.editRequestData.student_id = r.id;
        $scope.editRequestData.exam_detail_id = r.paper_record[0].paper_id;
    }

    $scope.setRetakeRequestData = function(r){
        $scope.retakeRequestData.class_id = r.class_id;
        $scope.retakeRequestData.batch_id = r.batch_id;
        $scope.retakeRequestData.student_id = r.id;
        $scope.retakeRequestData.exam_detail_id = r.paper_record[0].paper_id;
    }

    

    $scope.getSchoolAdmins = function(otherData){
        $http.post(base_url + 'syllabus/getSchoolAdmins', {}, config).then(
        function(success){
        publicNotificationViaPusher("lbl_online_exam_edit_approval", otherData,  success.data.new_ids, "applications/all", {'sender': success.data.sender}, $scope.r_id);
        },
        function(error){
            console.log(error.data);
            //$window.location.href = 'errors/' + error.status;
        }
        );
    };

    $scope.inProcessEdit = function(){
    $scope.editRequestData.reason = $scope.requestText;
    if($scope.requestText != null && $scope.requestText != ''){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'online_exams/inProcessEdit', $scope.editRequestData, config).then(
            function (success) {
                $('#requestModelEdit').modal('hide');
                    showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                    $scope.r_id = success.data.r_id;
                    var otherData = {class_id:$scope.editRequestData.class_id,
                        batch_id:$scope.editRequestData.batch_id, student_id:$scope.editRequestData.student_id,
                        exam_detail_id:$scope.editRequestData.exam_detail_id};
                    $scope.getSchoolAdmins(otherData);
                    $scope.requestText ="";
                    $scope.getResults($scope.selected_paper_details);
            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

            },
            function (error) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                //$window.location.href = 'errors/' + error.status;
                //console.log(error);
            }
        );
    }else{
         $('#request_error').show();
    }
    };

    $scope.inProcessRetake = function(){
    $scope.retakeRequestData.reason = $scope.requestText2;
    if($scope.requestText2 != null && $scope.requestText2 != ''){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'online_exams/inProcessRetake', $scope.retakeRequestData, config).then(
            function (success) {
                $('#requestModelRetake').modal('hide');
                    showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                    $scope.r_id = success.data.r_id;
                    var otherData = {class_id:$scope.editRequestData.class_id,
                        batch_id:$scope.editRequestData.batch_id, student_id:$scope.editRequestData.student_id,
                        exam_detail_id:$scope.editRequestData.exam_detail_id};
                    $scope.getSchoolAdmins(otherData);
                    $scope.requestText2 ="";
                    $scope.getResults($scope.selected_paper_details);
            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");

            },
            function (error) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                //$window.location.href = 'errors/' + error.status;
                //console.log(error);
            }
        );
    }else{
         $('#request_error').show();
    }
    };

    $scope.initExams = function(){
        Loading("#exam-select-container", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'online_exams/getExams', "", config).then(
            function (success) {
                Loading("#exam-select-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.exams = success.data.data;
            }, 
            function(error){
                Loading("#exam-select-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };

    $scope.initPapers = function(exam_id, class_id){
        Loading("#paper-select-container", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'online_exams/getPapers', {exam_id: exam_id, class_id: class_id}, config).then(
            function (success) {
                Loading("#paper-select-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.papers = success.data.data;
            }, 
            function(error){
                Loading("#paper-select-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };

    $scope.initPapersForStudent = function(exam_id){
        Loading("#paper-select-container", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'online_exams/getPapersForStudent', {exam_id: exam_id}, config).then(
            function (success) {
                Loading("#paper-select-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.papers = success.data.data;
            }, 
            function(error){
                Loading("#paper-select-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };

    $scope.getQuestionsTemplate = function(){
        Loading("#filterForm", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'online_exams/getPaperQuestions', $scope.filterModel, config).then(
            function (success) {
                Loading("#filterForm", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.questionModel = success.data.data;
                $scope.selectedPaper = angular.copy($scope.filterModel);
                $scope.published = success.data.published;
            }, 
            function(error){
                Loading("#filterForm", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };


    $scope.initStartExam = function(exam_id, paper_id){
        Loading("#filterForm", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'online_exams/getPaperQuestionsForStudent', {'exam_id': exam_id, 'paper_id': paper_id}, config).then(
            function (success) {
                Loading("#filterForm", '<?php echo lang("loading_datatable") ?>', "", "hide");
                
                $scope.questionModel = success.data.data;
                
                $scope.countDown = parseInt($scope.questionModel[0].duration_in_minutes * 60);    
                var promise = $interval(function(){
                    $scope.countDown--;
                    if($scope.countDown === 0){
                        $interval.cancel(promise);
                        $window.location.href = base_url + "online_exams/student";
                    }
                    
                    var h = Math.floor($scope.countDown % (3600*24) / 3600);
                    var m = Math.floor($scope.countDown % 3600 / 60);
                    var s = Math.floor($scope.countDown % 60);
                    
                    $("#timer").text(h+":"+m+":"+s);
                },1000,0);
                
                /*$scope.submitted = success.data.submitted;
                $scope.paper = success.data.paper;
                $scope.selected_exam_id = $scope.filterModel.exam_id;
                $scope.selected_paper_id = $scope.filterModel.paper_id;*/
            }, 
            function(error){
                Loading("#filterForm", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };

    $scope.getPaperForStudentAfterSubmit = function(){
        Loading("#filterForm", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'online_exams/getPaperQuestionsForStudent', {exam_id : $scope.selected_exam_id, paper_id : $scope.selected_paper_id}, config).then(
            function (success) {
                Loading("#filterForm", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.questionModel = success.data.data;
                $scope.submitted = success.data.submitted;
                $scope.selected_exam_id = $scope.filterModel.exam_id;
                $scope.selected_paper_id = $scope.filterModel.paper_id;
            }, 
            function(error){
                Loading("#filterForm", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };

    $scope.saveSingleQuestion = function(obj){
        obj.exam_id = $scope.selectedPaper.exam_id;
        obj.paper_id = $scope.selectedPaper.paper_id;

        var exist = obj.id;
        
        Loading("#"+obj.question_form_id, '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'online_exams/saveQuestion', obj, config).then(
            function (success) {
                Loading("#"+obj.question_form_id, '<?php echo lang("loading_datatable") ?>', "", "hide");
                showNotification('<?php echo lang("success_app") ?>', success.data.message, success.data.status);
                obj.id = 1;
                if(exist != null){
                    obj.updated = true;
                    obj.saved = false;
                }else{
                    obj.saved = true;
                    obj.updated = false;
                }
            }, 
            function(error){
                Loading("#"+obj.question_form_id, '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };

    $scope.initExamsForStudent = function(){
        Loading("#exam-select-container", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'online_exams/getExamsForStudent', "", config).then(
            function (success) {
                Loading("#exam-select-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.exams = success.data.data;
            }, 
            function(error){
                Loading("#exam-select-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };
    
    $scope.submitPaper = function(){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'online_exams/submitPaper', $scope.questionModel, config).then(
            function (success) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                if(success.data.status == "success"){
                    $('#confirmModal').modal('hide');
                    $window.location.href = base_url + "online_exams/student";
                }
            }, 
            function(error){
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };


    $scope.initAcademicYears =  function(){
        Loading("#marksFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'examination/getAcademicYears', "", config).then(
            function (success) {
                Loading("#marksFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.initClasses(success.data.current_academic_year_id);
                $scope.academicyears = success.data.data;
                $scope.resultModel.academic_year_id = success.data.current_academic_year_id;
                $scope.filterModel.academic_year_id = success.data.current_academic_year_id;
                $scope.resultModel.class_id = "";
                $scope.filterModel.class_id = "";
                $scope.resultModel.batch_id = "";
            },
            function (error) {
                Loading("#marksFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $window.location.href = 'errors/' + error.status;
            }
        );
    };

    $scope.initAcademicYearsForPublish =  function(){
        Loading("#marksFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'examination/getAcademicYears', "", config).then(
            function (success) {
                Loading("#marksFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.initClasses(success.data.current_academic_year_id);
                $scope.academicyears = success.data.data;
                $scope.publishModel.academic_year_id = success.data.current_academic_year_id;
                $scope.publishModel.class_id = "";
            },
            function (error) {
                Loading("#marksFilterAcademicYears", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $window.location.href = 'errors/' + error.status;
            }
        );
    };
    
    $scope.initClasses = function (academic_year_id) {
        if(academic_year_id) {    
            Loading("#marksFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'examination/getClasses', { 'academic_year_id': academic_year_id }, config).then(
                function (success) {
                    Loading("#marksFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.classes = success.data;
                    //sconsole.log(success.data);
                },
                function (error) {
                    Loading("#marksFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
            );
        }
    };
    
    
    $scope.initBatches = function (class_id, academic_year_id) {
        if (class_id && academic_year_id) {
            Loading("#marksFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'examination/getClassBatches', {'class_id': class_id, 'academic_year_id':academic_year_id}, config).then(
                function (success) {
                    Loading("#marksFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.batches = success.data;
                    $scope.resultModel.batch_id = "";
                },
                function (error) {
                    console.log(error.data);
                    Loading("#marksFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
            );
        }
    };
    
    $scope.initSubjects = function (class_id, batch_id, academic_year_id) {
        Loading("#marksFilterSubjects", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (class_id && batch_id && academic_year_id) {
            Loading("#marksFilterSubjects", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'examination/getSubjects', { 'class_id': class_id, 'batch_id':batch_id, 'academic_year_id': academic_year_id}, config).then(
                function (success) {
                    Loading("#marksFilterSubjects", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.subjects = success.data;
                    $scope.resultModel.subject_id = "";
                },
                function (error) {
                    console.log(error.data);
                }
            );
        }
    };
    
    $scope.initExams2 = function (class_id, batch_id, subject_id, academic_year_id) {
        if (class_id && batch_id && subject_id && academic_year_id) {
            Loading("#marksFilterExams", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'online_exams/getSpecificExams', {class_id: class_id, batch_id:batch_id, subject_id:subject_id, academic_year_id:academic_year_id}, config).then(
                function (success) {
                    Loading("#marksFilterExams", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.exams2 = success.data;
                    $scope.resultModel.exam_detail_id = "";
                },
                function (error) {
                    console.log(error.data);
                }
            );
        }
    };

    $scope.saveExamId = function (exam_detail_id){
        angular.forEach($scope.exams2, function (value, key) {
            if(value.id == exam_detail_id){
                $scope.resultModel.exam_id = value.exam_id;
            }
        });
    };

    $scope.getResults = function (resubmit = false){
        if(resubmit == false){
            var values = $scope.resultModel;
            $scope.selected_paper_details = angular.copy(values);
        }else{
            $scope.resultModel = angular.copy(resubmit);
            var values = resubmit;
        }
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'online_exams/get_results', values, config).then(
            function (success) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.results = success.data.data;
                $scope.selected_paper_number_of_questions = [];
                for(i=1; i <= parseInt(success.data.number_of_questions); i++){
                    $scope.selected_paper_number_of_questions.push(i);
                }
            }, 
            function(error){
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };

    $scope.getMajorSheet = function (){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'online_exams/getMajorSheet', $scope.resultModel, config).then(
            function (success) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.results = success.data.students;
                $scope.papers = success.data.papers;
                $scope.selected_exam_id = success.data.exam_id;
                $scope.selectedClassName = success.data.class_name;
                $scope.selectedBatchName = success.data.batch_name;
                $scope.selectedExamName = success.data.exam_name;
            }, 
            function(error){
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };

    $scope.setStudentID = function(id, remark){
      $scope.selected_student_id = id;
      $scope.remark = remark;
    }

    $scope.saveTeacherRemarks = function(valid){

        Loading("#remarks-modal", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'online_exams/save_teacher_remarks', {student_id: $scope.selected_student_id, exam_id: $scope.selected_exam_id, remark: $scope.remark}, config).then(
            function (success) {
                Loading("#remarks-modal", '<?php echo lang("loading_datatable") ?>', "", "hide");
                if(success.data.status == "success"){
                    $("#remarks-modal").modal("hide");
                    showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                    $scope.getMajorSheet();
                }if(success.data.status == "danger"){
                    showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                }
            },
            function (error) {
                Loading("#remarks-modal", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    }

    $scope.majorsheetPrint = function(id, logo, name, dirr){
        var d = '<div style='+ dirr +'>'+
            '<p style="text-align:center;"><img src="<?php echo base_url(); ?>uploads/logos/'+logo+'" width="150px"/></p>'+
            '<h3 style="text-align:center;"><b>'+name+'</b></h3>'+
            '<table style="width:100%; border-spacing: 5px; margin-bottom: 10px; border-collapse: separate;">'+
            '<tr><td><strong><?php echo lang("class_name"); ?></strong></td><td><u>'+$scope.selectedClassName+'</u></td>'+
            '<td><strong><?php echo lang("lbl_batch"); ?></strong></td><td><u>'+$scope.selectedBatchName+'</u></td>'+
            '</tr><tr><td><strong><?php echo lang("lbl_exam_session"); ?></strong></td><td><u>'+$scope.selectedExamName+'</u></td></tr>'+
            '</table>'+
        '</div>';
    
    
        $("#" + id).print({
            globalStyles: false,
            mediaPrint: false,
            stylesheet: "<?php echo base_url(); ?>assets/css/custom-majorsheet.css",
            noPrintSelector: ".no-print",
            iframe: true,
            append: null,
            prepend: d,
            manuallyCopyFormValues: true,
            deferred: $.Deferred(),
            timeout: 750,
            title: null,
            doctype: '<!doctype html>'
        });
    };

    $scope.setResultViewModal = function(obj){
        $scope.browseModel = obj;
    };

    $scope.setResultEditModal = function(obj){
        $scope.editModel = angular.copy(obj);
    };

    $scope.updateResult = function (){
        Loading("#result-update-modal-form", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'online_exams/update_paper', $scope.editModel, config).then(
            function (success) {
                Loading("#result-update-modal-form", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $("#resutl-edit-modal").modal("hide");
                $scope.getResults();
                showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
            },
            function (error) {
                Loading("#result-update-modal-form", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };

    $scope.getPapersForPublish = function (){
        $scope.publishModelSelected = angular.copy($scope.publishModel);
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'online_exams/getPapersForPublish', $scope.publishModel, config).then(
            function (success) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.papers_to_publish = success.data.papers;
            },
            function (error) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };

    $scope.getPapersForPublishSelected = function (selectedModel){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'online_exams/getPapersForPublish', selectedModel, config).then(
            function (success) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.papers_to_publish = success.data.papers;
            },
            function (error) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };

    $scope.publishPaper = function (id, status){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'online_exams/publishPaper', {id: id, status: status}, config).then(
            function (success) {
                showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                $scope.getPapersForPublishSelected($scope.publishModelSelected);
            },
            function (error) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };

    $scope.initMainExams = function (class_id, academic_year_id) {
        if (class_id && academic_year_id) {
            Loading("#marksFilterExams", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'online_exams/getMainExams', {'class_id': class_id, 'academic_year_id':academic_year_id}, config).then(
                function (success) {
                    Loading("#marksFilterExams", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.main_exams = success.data.exams;
                    $scope.filterModel.exam_id = "";
                },
                function (error) {
                    console.log(error.data);
                    Loading("#marksFilterExams", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
            );
        }
    };

    $scope.initMainExamsForQuestions = function (class_id, academic_year_id) {
        if (class_id && academic_year_id) {
            Loading("#exam-select-container", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'online_exams/getMainExams', {'class_id': class_id, 'academic_year_id':academic_year_id}, config).then(
                function (success) {
                    Loading("#exam-select-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.exams = success.data.exams;
                    $scope.papers = [];
                    $scope.filterModel.exam_id = "";
                },
                function (error) {
                    console.log(error.data);
                    Loading("#exam-select-container", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
            );
        }
    };
});

app2.controller("landingPageControler", function ($scope, $http) {
    
    $scope.filterModel = {};
    $scope.alert = {};

    $scope.gallery = function () {
        $('#gallery').addClass('active');
        $('#classes').removeClass('active');
        $('#teachers').removeClass('active');
        $('#slider').removeClass('active');
        $('#video').removeClass('active');
        $('#background_images').removeClass('active');
        $('#links').removeClass('active');
        $('#news').removeClass('active');
        $('#stats').removeClass('active');
        $('#theme').removeClass('active');
        $('#galleryDiv').show();
        $('#classesDiv').hide();
        $('#teachersDiv').hide();
        $('#sliderDiv').hide();
        $('#backgroundDiv').hide();
        $('#videoDiv').hide();
        $('#linksDiv').hide();
        $('#newsDiv').hide();
        $('#statsDiv').hide();
        $('#themeDiv').hide();
    }

    $scope.teachers = function () {
        $('#gallery').removeClass('active');
        $('#classes').removeClass('active');
        $('#teachers').addClass('active');
        $('#slider').removeClass('active');
        $('#video').removeClass('active');
        $('#background_images').removeClass('active');
        $('#links').removeClass('active');
        $('#news').removeClass('active');
        $('#stats').removeClass('active');
        $('#theme').removeClass('active');
        $('#galleryDiv').hide();
        $('#classesDiv').hide();
        $('#teachersDiv').show();
        $('#sliderDiv').hide();
        $('#backgroundDiv').hide();
        $('#videoDiv').hide();
        $('#linksDiv').hide();
        $('#newsDiv').hide();
        $('#statsDiv').hide();
        $('#themeDiv').hide();
    }

    $scope.classes = function () {
        $('#gallery').removeClass('active');
        $('#classes').addClass('active');
        $('#teachers').removeClass('active');
        $('#slider').removeClass('active');
        $('#video').removeClass('active');
        $('#links').removeClass('active');
        $('#background_images').removeClass('active');
        $('#news').removeClass('active');
        $('#stats').removeClass('active');
        $('#theme').removeClass('active');
        $('#galleryDiv').hide();
        $('#classesDiv').show();
        $('#teachersDiv').hide();
        $('#backgroundDiv').hide();
        $('#sliderDiv').hide();
        $('#videoDiv').hide();
        $('#linksDiv').hide();
        $('#newsDiv').hide();
        $('#themeDiv').hide();
        $('#statsDiv').hide();
    }

    $scope.slider = function () {
        $('#gallery').removeClass('active');
        $('#classes').removeClass('active');
        $('#teachers').removeClass('active');
        $('#video').removeClass('active');
        $('#background_images').removeClass('active');
        $('#links').removeClass('active');
        $('#news').removeClass('active');
        $('#stats').removeClass('active');
        $('#theme').removeClass('active');
        $('#slider').addClass('active');
        $('#galleryDiv').hide();
        $('#classesDiv').hide();
        $('#teachersDiv').hide();
        $('#backgroundDiv').hide();
        $('#videoDiv').hide();
        $('#linksDiv').hide();
        $('#newsDiv').hide();
        $('#statsDiv').hide();
        $('#themeDiv').hide();
        $('#sliderDiv').show();
    }

    $scope.background_images = function () {
        $('#gallery').removeClass('active');
        $('#classes').removeClass('active');
        $('#teachers').removeClass('active');
        $('#slider').removeClass('active');
        $('#video').removeClass('active');
        $('#links').removeClass('active');
        $('#news').removeClass('active');
        $('#stats').removeClass('active');
        $('#theme').removeClass('active');
        $('#background_images').addClass('active');
        $('#galleryDiv').hide();
        $('#classesDiv').hide();
        $('#teachersDiv').hide();
        $('#sliderDiv').hide();
        $('#videoDiv').hide();
        $('#linksDiv').hide();
        $('#newsDiv').hide();
        $('#statsDiv').hide();
        $('#themeDiv').hide();
        $('#backgroundDiv').show();
    }

    $scope.video = function () {
        $('#gallery').removeClass('active');
        $('#classes').removeClass('active');
        $('#teachers').removeClass('active');
        $('#slider').removeClass('active');
        $('#background_images').removeClass('active');
        $('#links').removeClass('active');
        $('#news').removeClass('active');
        $('#stats').removeClass('active');
        $('#theme').removeClass('active');
        $('#video').addClass('active');
        $('#galleryDiv').hide();
        $('#classesDiv').hide();
        $('#teachersDiv').hide();
        $('#sliderDiv').hide();
        $('#backgroundDiv').hide();
        $('#linksDiv').hide();
        $('#newsDiv').hide();
        $('#statsDiv').hide();
        $('#themeDiv').hide();
        $('#videoDiv').show();
    }

    $scope.links = function () {
        $('#gallery').removeClass('active');
        $('#classes').removeClass('active');
        $('#teachers').removeClass('active');
        $('#slider').removeClass('active');
        $('#background_images').removeClass('active');
        $('#video').removeClass('active');
        $('#news').removeClass('active');
        $('#stats').removeClass('active');
        $('#theme').removeClass('active');
        $('#links').addClass('active');
        $('#galleryDiv').hide();
        $('#classesDiv').hide();
        $('#teachersDiv').hide();
        $('#sliderDiv').hide();
        $('#backgroundDiv').hide();
        $('#videoDiv').hide();
        $('#newsDiv').hide();
        $('#statsDiv').hide();
        $('#themeDiv').hide();
        $('#linksDiv').show();
    }

    $scope.news = function () {
        $('#gallery').removeClass('active');
        $('#classes').removeClass('active');
        $('#teachers').removeClass('active');
        $('#slider').removeClass('active');
        $('#background_images').removeClass('active');
        $('#video').removeClass('active');
        $('#links').removeClass('active');
        $('#stats').removeClass('active');
        $('#theme').removeClass('active');
        $('#news').addClass('active');
        $('#galleryDiv').hide();
        $('#classesDiv').hide();
        $('#teachersDiv').hide();
        $('#sliderDiv').hide();
        $('#backgroundDiv').hide();
        $('#videoDiv').hide();
        $('#linksDiv').hide();
        $('#statsDiv').hide();
        $('#themeDiv').hide();
        $('#newsDiv').show();
    }
     $scope.stats = function () {
        $('#gallery').removeClass('active');
        $('#classes').removeClass('active');
        $('#teachers').removeClass('active');
        $('#slider').removeClass('active');
        $('#background_images').removeClass('active');
        $('#video').removeClass('active');
        $('#links').removeClass('active');
        $('#news').removeClass('active');
        $('#theme').removeClass('active');
        $('#stats').addClass('active');
        $('#galleryDiv').hide();
        $('#classesDiv').hide();
        $('#teachersDiv').hide();
        $('#sliderDiv').hide();
        $('#backgroundDiv').hide();
        $('#videoDiv').hide();
        $('#linksDiv').hide();
        $('#newsDiv').hide();
        $('#themeDiv').hide();
        $('#statsDiv').show();
    }

    $scope.theme = function () {
        $('#gallery').removeClass('active');
        $('#classes').removeClass('active');
        $('#teachers').removeClass('active');
        $('#slider').removeClass('active');
        $('#background_images').removeClass('active');
        $('#video').removeClass('active');
        $('#links').removeClass('active');
        $('#news').removeClass('active');
        $('#stats').removeClass('active');
        $('#theme').addClass('active');
        $('#galleryDiv').hide();
        $('#classesDiv').hide();
        $('#teachersDiv').hide();
        $('#sliderDiv').hide();
        $('#backgroundDiv').hide();
        $('#videoDiv').hide();
        $('#linksDiv').hide();
        $('#newsDiv').hide();
        $('#statsDiv').hide();
        $('#themeDiv').show();
    }

    $scope.update = function() {
      Loading("body", "Updating", "", "show");
      $http.post(base_url + 'settings/updateStats', $scope.filterModel, config).then(
      function (success) {
                Loading("body", "", "", "hide");
                $scope.alert.message = success.data.message;
                $scope.alert.type = "alert-success"
            }, function (error) {
                console.log(error.data);
                Loading("body", "", "", "hide");
            });
    }

    $scope.updateTheme = function() {
     $scope.filterModel.heading_color = $('#hexcode').val();
     $scope.filterModel.sub_heading_color = $('#hexcode1').val();
     $scope.filterModel.description_color = $('#hexcode2').val();
     Loading("body", "Updating", "", "show");
     $http.post(base_url + 'settings/updateThemeSettings', $scope.filterModel, config).then(
      function (success) {
                Loading("body", "", "", "hide");
                $scope.alert.message1 = success.data.message;
                $scope.alert.type1 = "alert-success"
            }, function (error) {
                console.log(error.data);
                Loading("body", "", "", "hide");
            });
  }

  $scope.initTheme = function() {
      $http.post(base_url + 'settings/showTheme', $scope.filterModel, config).then(
      function (success) {
                $scope.filterModel.heading_size = success.data.data.heading_font_size;
                $scope.filterModel.heading_color = success.data.data.heading_color;
                $scope.filterModel.sub_heading_size = success.data.data.sub_heading_font_size;
                $scope.filterModel.sub_heading_color = success.data.data.sub_heading_color;
                $scope.filterModel.description_size = success.data.data.description_font_size;
                $scope.filterModel.description_color = success.data.data.description_color;
            }, function (error) {
                console.log(error.data);
            });
  }

    $scope.initStats = function() {
      $http.post(base_url + 'settings/showStats', $scope.filterModel, config).then(
      function (success) {
                $scope.filterModel.students = success.data.data.students;
                $scope.filterModel.classes = success.data.data.classes;
                $scope.filterModel.emp = success.data.data.employees;
                $scope.filterModel.bus = success.data.data.bus;
                //$scope.students = success.data.data.students;
                //$scope.classes = success.data.data.classes;
                //$scope.employees = success.data.data.employees;
                $scope.bus = success.data.data.bus;
            }, function (error) {
                console.log(error.data);
            });
  }
});


app2.controller("accountsReportController", function ($scope, $http,) {
    $scope.accounts = [];
    $scope.income_types = [];
    $scope.income_categories = [];
    $scope.expense_types = [];
    $scope.expense_categories = [];
    $scope.incomes = [];
    $scope.expenses = [];
    $scope.income_total = 0;
    $scope.expense_total = 0;
    $scope.currencies = [];
    $scope.default_currency = "";
    $scope.income = {
        income_id: '',
        income_category_id: '',
        date: $scope.today,
        amount: 0,
        currency: '',
        collected_by: '',
        comment: '',
        mode: 'cash',
        files: [],
        fixed: 'Yes'
    };
    $scope.expense = {
        expense_id: '',
        expense_category_id: '',
        date: $scope.today,
        amount: 0,
        currency: '',
        paid_by: '',
        comment: '',
        mode: 'cash',
        files: [],
        fixed: 'Yes'
    };

    $scope.getIncomeTypes = function(){
        Loading("#incomeType", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'reports/getIncomeTypes', '', config).then(
                function (success) {
                    Loading("#incomeType", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.income_types = success.data.income_types;
                    $scope.getIncomeCategories('all');
                },
                function (error) {
                    Loading("#incomeType", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
                
        );
    }

    $scope.getIncomeCategories = function(id){ 
        console.log(id);
        Loading("#incomeCategory", '<?php echo lang("loading_datatable") ?>', "", "show");
      // if(id == 'all'){
           Loading("#incomeCategory", '<?php echo lang("loading_datatable") ?>', "", "hide");
            $scope.incomeModel.category_id = 'all';
            $scope.income_categories = {};
       //} else {
        $http.post(base_url + 'reports/getIncomeCategories', {id:id}, config).then(
                function (success) {
                    console.log(success);
                    Loading("#incomeCategory", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.income_categories = success.data.income_categories;
                },
                function (error) {
                    Loading("#incomeCategory", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
               }
         //   }
        );
    }

    $scope.getIncomeCollectedBy = function(){
        Loading("#incomeCollectedBy", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'reports/getIncomeCollectedBy', '', config).then(
            function (success){
                console.log(success);
                    Loading("#incomeCollectedBy", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.income_collectedBy = success.data.income_collectedBy;
            },
            function(error){
                Loading("#incomeCollectedBy", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
            }
        );
    }

    $scope.incomePayroll = function()
    {
        $('#incomeReportTable').DataTable({
            "language": {

                "decimal":        "",
                "emptyTable":     '<?php echo lang("no_data_table"); ?>',
                "info":           '<?php echo lang("data_info"); ?>',
                "infoEmpty":      '<?php echo lang("infoempty"); ?>',
                "infoFiltered":   '<?php echo lang("filter_datatable"); ?>',
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     '<?php echo lang("show_datatable"); ?>',
                "loadingRecords": '<?php echo lang("loading_datatable"); ?>',
                "processing":     '<?php echo lang("processing_datatable"); ?>',
                "search":         '<?php echo lang("search"); ?>:',
                "zeroRecords":    '<?php echo lang("no_record_datatable"); ?>',
                "paginate": {
                    "first":      '<?php echo lang("first"); ?>',
                    "last":       '<?php echo lang("last"); ?>',
                    "next":       '<?php echo lang("btn_next"); ?>',
                    "previous":   '<?php echo lang("previous"); ?>'
                },
                "aria": {
                    "sortAscending":  ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                }
            },
            dom: 'Bfrtip',
            ajax: {
                type: "POST",
                url: base_url+'reports/fetchPayrollIncome',
                data: {'formData':$scope.incomeModel},
                dataSrc: '',
            },
            columns: [
                
               
               
                {title: 'Income Type', data: 'income_type' },
                {title: 'Category', data: 'category_name' },
                {title: 'Paid Amount', data: 'amount' },
                //{title: 'Paid Symbol', data: 'symbol' },
                {title: 'Date', data: 'date' },
                {title: 'Collected By', data: 'collected_by' }

               // {title: 'Avatar', data: '', render : function (data, type, row) {
                    
                    <!-- return '<img src="<?php echo base_url(); ?>uploads/user/' +row.avatar+ '" class="img-circle" style="height: 60px;width: 60px" />'; -->
                    
                   // }
               // },
                
            ],
            
                          
            buttons: [
                {
                extend: 'copyHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
                },
                {
                extend: 'excelHtml5'
                },
                {
                extend: 'csvHtml5'
                },
                 {
                extend: 'pdfHtml5'
                }
               ],
            destroy: true
        });
    }

        <!-- expense report -->

    $scope.getExpenseTypes = function(){
        Loading("#expenseType", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'reports/getExpenseTypes', '', config).then(
                function (success) {
                    Loading("#expenseType", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.expense_types = success.data.expense_types;
                    $scope.getExpenseCategories('all');
                },
                function (error) {
                    Loading("#expenseType", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
                
        );
    }

    $scope.getExpenseCategories = function(id){ 
        console.log(id);
        Loading("#expenseCategory", '<?php echo lang("loading_datatable") ?>', "", "show");
      // if(id == 'all'){
           Loading("#expenseCategory", '<?php echo lang("loading_datatable") ?>', "", "hide");
            $scope.expenseModel.category_id = 'all';
            $scope.expense_categories = {};
       //} else {
        $http.post(base_url + 'reports/getExpenseCategories', {id:id}, config).then(
                function (success) {
                    console.log(success);
                    Loading("#expenseCategory", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.expense_categories = success.data.expense_categories;
                },
                function (error) {
                    Loading("#expenseCategory", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
               }
         //   }
        );
    }
    $scope.getExpensePaidBy = function(){
        Loading("#expensePaidBy", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'reports/getExpensePaidBy', '', config).then(
            function (success){
                console.log(success);
                    Loading("#expensePaidBy", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.expense_paidBy = success.data.expense_paidBy;
            },
            function(error){
                Loading("#expensePaidBy", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
            }
        );
    }
    $scope.expensePayroll = function()
    {
        $('#expenseTableReport').DataTable({
            "language": {

                "decimal":        "",
                "emptyTable":     '<?php echo lang("no_data_table"); ?>',
                "info":           '<?php echo lang("data_info"); ?>',
                "infoEmpty":      '<?php echo lang("infoempty"); ?>',
                "infoFiltered":   '<?php echo lang("filter_datatable"); ?>',
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     '<?php echo lang("show_datatable"); ?>',
                "loadingRecords": '<?php echo lang("loading_datatable"); ?>',
                "processing":     '<?php echo lang("processing_datatable"); ?>',
                "search":         '<?php echo lang("search"); ?>:',
                "zeroRecords":    '<?php echo lang("no_record_datatable"); ?>',
                "paginate": {
                    "first":      '<?php echo lang("first"); ?>',
                    "last":       '<?php echo lang("last"); ?>',
                    "next":       '<?php echo lang("btn_next"); ?>',
                    "previous":   '<?php echo lang("previous"); ?>'
                },
                "aria": {
                    "sortAscending":  ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                }
            },
            dom: 'Bfrtip',
            ajax: {
                type: "POST",
                url: base_url+'reports/fetchPayrollExpense',
                data: {'formData':$scope.expenseModel},
                dataSrc: '',
            },
            columns: [
                
               
               
                {title: 'Expense Type', data: 'expense_type' },
                {title: 'Category', data: 'category_name' },
                {title: 'Paid Amount', data: 'amount' },
                //{title: 'Paid Symbol', data: 'symbol' },
                {title: 'Date', data: 'date' },
                {title: 'Paid By', data: 'paid_by' }

               // {title: 'Avatar', data: '', render : function (data, type, row) {
                    
                    <!-- return '<img src="<?php echo base_url(); ?>uploads/user/' +row.avatar+ '" class="img-circle" style="height: 60px;width: 60px" />'; -->
                    
                   // }
               // },
                
            ],
            
                          
            buttons: [
                {
                extend: 'copyHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
                },
                {
                extend: 'excelHtml5'
                },
                {
                extend: 'csvHtml5'
                },
                 {
                extend: 'pdfHtml5'
                }
               ],
            destroy: true
        });
    }


});

app2.controller("emailController", function ($scope, $http) {
    $scope.type = {};
    $scope.status = {};  
    
        $scope.getEmails = function(){
          
            $http.post(base_url + 'settings/getEmails', '', config).then(
                function (success) {
                   
                    $scope.email = success.data;
                    $scope.email = success.data.emails;
                },
                function (error) {
                    console.log(error.data);
                }
            );
        };
        $scope.reSend = function(id){
            Loading("#myTable", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'settings/resendEmail', {id : id}, config).then(
                function (success) {
                    $scope.getEmails();
                Loading("#myTable", '<?php echo lang("loading_datatable") ?>', "", "hide");
                $scope.alert.hasMessage = true;
                $scope.alert.title = "Success";
                $scope.alert.class = "alert-success";
                $scope.alert.message = success.data.message;
                  
                },
                function (error) {
                    Loading("#myTable", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
            );

        };

});
