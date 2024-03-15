<?php
    header('Content-Type: application/javascript');
    ob_start();
    //require_once($_SERVER['DOCUMENT_ROOT'] . '/uv/myschool2/index.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/sandbox/index.php');
    ob_end_clean();
?>

var base_url = "https://"+window.location.host+"/sandbox/";
//var base_url = "https://"+window.location.host+"/uv/myschool2/";

var global_signup_date = {};
var app = angular.module('myschool', ['jcs-autoValidate']);
var config = {
    headers: {
        'Content-Type': 'application/json;charset=utf-8;'
    }
};
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

var app2 = angular.module('myschool2', ['jcs-autoValidate', 'angularUtils.directives.dirPagination', 'uiCropper']);

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
        experience_info: ''
    };
    $scope.alert = {};
    $scope.errLength;
    $scope.areFieldsNotFilled = false;
    $scope.permissions = [
        {label: 'View Students', permission: 'students-show', val: true},
        {label: 'Add Students', permission: 'students-add', val: false},
        {label: 'Edit Students', permission: 'students-edit', val: false},
        {label: 'View Students Details', permission: 'students-view', val: false},
        {label: 'View Parents', permission: 'parents-all', val: true},
        {"label": 'Add Parents', "permission": "parents-add", "val": false},
        {"label": 'Edit Parents', "permission": "parents-edit", "val": false},
        {label: 'View Parents Details', permission: 'parents-view', val: false},
        {label: 'Student Attendance', permission: 'attendance-show', val: true},
        {label: 'Student Report', permission: 'attendance-report', val: true},
        {label: 'View Time Table', permission: 'timetable-show', val: true},

        {"label": "Employee Attendance", "permission": "attendance-employee", "val": false},
        {"label": "Employee Report", "permission": "attendance-report_employee", "val": false},
        {"label": "View Employees", "permission": "employee-all", "val": false},
        {"label": "Single Employee Details", "permission": "employee-view", "val": false},
        {"label": "Add Employee", "permission": "employee-add", "val": false},
        {"label": "Edit Employee", "permission": "employee-edit", "val": false},

        {label: 'Upload Study Material', permission: 'study_material-upload', val: true},
        {label: 'Download Study Material', permission: 'study_material-download', val: true},
        {label: 'Buy Books', permission: 'study_material-book_shop', val: true},

        {"label": "Download Forms", "permission": "forms-all", "val": true},
        {"label": "Create Form", "permission": "forms-create", "val": false},
        {"label": "Edit Form", "permission": "forms-edit", "val": false},
        {"label": "Create Form Category", "permission": "forms-category_create", "val": false},

        {"label": "View Profile", "permission": "profile-index", "val": true},
        {"label": "Edit Profile", "permission": "profile-edit", "val": false},

        {label: 'View Student Fee', permission: 'fee-collection', val: false}

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
                    Loading("#categories", '<?php echo lang("loading_datatable") ?>', "", "hide");
                },
                function (error) {
                    console.log(error.data);
                    Loading("#categories", '<?php echo lang("loading_datatable") ?>', "", "hide");
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
                            $scope.formModel = {};
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
    $scope.resImgQuality = 1;
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
    $scope.resImgQuality = 1;
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
    $scope.resImgQuality = 1;
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
    $scope.onSubmit = function (valid) {
        if (valid) {
            Loading("#addDeptModal", "Saving", "", "show");
            $http.post(base_url + 'settings/saveDepartment', $scope.formModel, config).then(
                    function (success) {
                        console.log(success.data);
                        Loading("#addDeptModal", "", "", "hide");
                        if (success.data.status === "error") {
                            showNotification('<?php echo lang("error_app") ?>', success.data.message, "error");
                        } else {
                            showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                            setTimeout(function () {
                                $window.location.reload();
                            }, 2000);
                        }
                    },
                    function (error) {
                        showNotification("Duplicate!", error.data, "info");
                        Loading("#addDeptModal", "", "", "hide");
                    }
            );
        }
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
        lastname: '',
        birthPlace: '',
        nationality: '',
        language: '',
        email: '',
        address: '',
        city: '',
        phone: '',
        dob: ''
    };
    $scope.batches = {};
    $scope.discounts = {};
    $scope.alert = {};
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
                            $scope.formModel = {};
                            $scope.resImageDataURI = '';
                            $scope.stdAddmissionForm.$setUntouched();
                            $scope.stdAddmissionForm.$setPristine();
                            $scope.alert.message = success.data.message;
                            $scope.alert.type = "alert-success";
                            $scope.image2 = false;
                            $("#rollno").removeClass("error");
                            $('.js-data-example-ajax').val(null).trigger('change.select2');
                            //console.log(success.data);
                        } else if (success.data.status === "error") {
                            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                            $("#rollno").addClass("error");
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
        $http.post(base_url + 'settings/getClassBatches', {id: class_id}, config).then(
                function (success) {
                    Loading("#frmBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.batches = success.data;
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
    $scope.resImgQuality = 1;
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
    $scope.fetchAllStdsOfClassAndBatch = function (class_id, batch_id, status) {
        Loading("#stdTableContianer", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'students/getClassBatchesStudents', {class_id: class_id, batch_id: batch_id, status: status}, config).then(
                function (success) {
                    Loading("#stdTableContianer", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $("#stdTableContianer").html(success.data);
                },
                function (error) {
                    Loading("#stdTableContianer", '<?php echo lang("loading_datatable") ?>', "", "hide");
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

    $scope.fetchStudent = function (id) {
        $http.post(base_url + 'students/getStudent', {'student_id': id}, config).then(
                function (success) {
                    $scope.formModel = success.data;
                    //console.log(success.data);


                    $scope.formModel.guardian = [success.data.parentId];


                },
                function (error) {
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
                            setTimeout(function () {
                                $scope.resImageDataURI = '';
                                $window.location.href = 'students/show';
                            }, 1000);

                        } else if (success.data.status === "error") {
                            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
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

    $scope.fetchClassBatches = function (class_id) {
        Loading("#frmEditBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'settings/getClassBatches', {id: class_id}, config).then(
                function (success) {
                    Loading("#frmEditBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.batches = success.data;
                    //$scope.formModel.batch = $;
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
    $scope.resImgQuality = 1;
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
    $scope.students = {};
    $scope.statuss = {};
    $scope.message;
    $scope.selectedDate;
    $scope.attendModel = {};
    $scope.data = [];

    $scope.initClasses = function () {
        Loading("#attFilterClasses", "Loading...", "", "show");
        $http.post(base_url + 'attendance/getClasses', "", config).then(
                function (success) {
                    Loading("#attFilterClasses", "Loading...", "", "hide");
                    $scope.classes = success.data;
                    //sconsole.log(success.data);
                },
                function (error) {
                    Loading("#attFilterClasses", "Loading...", "", "hide");
                    $window.location.href = 'errors/' + error.status;
                }
        );
    };
    
    $scope.initBatches = function (class_id) {
        Loading("#attFilterBatches", "Loading...", "", "show");
        if (class_id) {
            Loading("#attFilterBatches", "Loading...", "", "show");
            $http.post(base_url + 'attendance/getClassBatches', {class_id: class_id}, config).then(
                    function (success) {
                        Loading("#attFilterBatches", "Loading...", "", "hide");
                        $scope.batches = success.data;
                        $scope.filterModel.batch_id = "";
                    },
                    function (error) {
                        console.log(error.data);
                        //Loading("#attFilterBatches", "Loading...", "", "hide");
                        //$window.location.href = 'errors/' + error.status;
                    }
            );
        }
    };
    
    $scope.onSubmit = function (valid) {
       if (valid) {
           Loading("#attStudentsTable", "Loading...", "", "show");
           $http.post(base_url + 'attendance/fetchStudentsAttendance', $scope.filterModel, config).then(
                   function (success) {
                       Loading("#attStudentsTable", "Loading...", "", "hide");
                       $scope.students = success.data.students;
                       $scope.message = success.data.message;
                       $scope.disable = success.data.disable;
                       $scope.selectedDate = $scope.filterModel.date;
                       $scope.action = success.data.edit;
                     
                   },
                   function (error) {
                       Loading("#attStudentsTable", "Loading...", "", "hide");
                        $window.location.href = 'errors/' + error.status;
                       // console.log(erro.status);
                   }
           );
       }
   };
   
   $scope.inProcessAttendance = function(){
     $http.post(base_url + 'attendance/inProcessAttendance', $scope.filterModel, config).then(
          function (success) {
              if(success.data.status === 'success'){
                  showNotification("Success!", success.data.message, "success");
                  $scope.selectedDate = $scope.filterModel.date;
                 
                  $scope.disable = success.data.disable;
                  $scope.action = success.data.edit;
                  $scope.getSchoolAdmins();
              }
             
          },
          function (error) {
               $window.location.href = 'errors/' + error.status;
              console.log(error);
          }
      );
  };

   $scope.getSchoolAdmins = function(){
      $http.post(base_url + 'syllabus/getSchoolAdmins', {}, config).then(
          function(success){
              publicNotificationViaPusher("lbl_approval_modify_atttence", success.data, "notification/index", {firstname:'yasir',lastname:'mirza'});
          },
          function(error){
               $window.location.href = 'errors/' + error.status;
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

           Loading("#attStudentsTable", "Loading...", "", "show");
           $http.post(base_url + 'attendance/save', $scope.data, config).then(
                   function (success) {
                       Loading("#attStudentsTable", "Loading...", "", "hide");

                       showNotification("Success!", success.data.message, success.data.status);
                       
                   },
                   function (error) {
                       Loading("#attStudentsTable", "Loading...", "", "hide");
                       showNotification("Error!", error.data.message, error.data.status);
                       $window.location.href = 'errors/' + error.status;
                   }
           );
       }
   };
});

app2.controller("arController", function ($scope, $http, $window, $location, $sce) {
    $scope.classes = {};
    $scope.batches = {};
    $scope.months = [
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
    ];
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
            angular.forEach($scope.batches, function (value) {
                if (value.id === $scope.arModel.batch_id) {
                    $scope.arModel.academic_year_id = value.academic_year_id;
                }
            });
            Loading(".attendance-table", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + "attendance/generate_report", $scope.arModel, config).then(
                    function (success) {
                        Loading(".attendance-table", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.finalReport = success.data.att;
                        //console.log($scope.finalReport);
                    },
                    function (error) {
                        Loading(".attendance-table", '<?php echo lang("loading_datatable") ?>', "", "hide");
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
            Loading("#tbResultContainer", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'timetable/getSubjectsWiseTimeTable', {class_id: $scope.tbModel.class_id, batch_id: $scope.tbModel.batch_id}, config).then(
                    function (success) {
                        Loading("#tbResultContainer", '<?php echo lang("loading_datatable") ?>', "", "hide");
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
                        Loading("#tbResultContainer", '<?php echo lang("loading_datatable") ?>', "", "hide");
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
        $scope.etModel = obj;
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
                            $scope.yModel = {};
                            $scope.addTimetableForm.$setUntouched();
                            $scope.addTimetableForm.$setPristine();
                            $scope.fetchSubjects(true);
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
                            $scope.yyModel = {};
                            $scope.editTimetableForm.$setUntouched();
                            $scope.editTimetableForm.$setPristine();
                            $scope.fetchSubjects(true);
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
    $scope.subjects = {};
    $scope.alreadyExists = {};
    $scope.selectedSubjectsToAssign = {};

    $scope.fetchClasses = function () {
        $http.post(base_url + 'settings/getSchoolClasses', "", config).then(
                function (success) {
                    $scope.classes = success.data;
                },
                function (error) {
                    console.log(error.data);
                }
        );
    };
    $scope.loadClassBatches = function (id) {
        $http.post(base_url + 'settings/getClassBatches', {id: id}, config).then(
                function (success) {
                    //Loading("#yasir_batches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.batches = success.data;
                    $scope.selecedVal22 = '';
                },
                function (error) {
                    //Loading("#yasir_batches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    };

    $scope.onSubmitFetchSubAndThr = function (valid) {
        if (valid) {
            $http.post(base_url + "assignsubjects/getSubjsThrs", {class_id: $scope.selecedVal11, batch_id: $scope.selecedVal22}, config).then(
                    function (success) {
                        $scope.subjects = success.data.subjects;
                        $scope.teachers = success.data.teachers;
                        $scope.alreadyExists = success.data.exists;
                    }, function (error) {
                console.log(error.data);
            }
            );
        }
    };
    $scope.saveSubjectAssignments = function (valid) {
        if (valid) {
            $http.post(base_url + "assignsubjects/save", {'data': $scope.selectedSubjectsToAssign, 'class_id': $scope.selecedVal11, 'batch_id': $scope.selecedVal22}, config).then(
                    function (success) {
                        if (success.data.status === "success") {
                            showNotification(success.data.status, success.data.message, success.data.status);
                            $scope.onSubmitFetchSubAndThr(true);
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

    $scope.onSubmitFetchClassFeeTypes = function (valid) {
        if (valid) {
            //Loading("#feetypecontainer", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + "fee/getClassFeetypes", $scope.fModel, config).then(
                    function (success) {
                        //Loading("#feetypecontainer", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.classFeetypes = success.data;
                    },
                    function (error) {
                        //Loading("#feetypecontainer", '<?php echo lang("loading_datatable") ?>', "", "hide");
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
                            $scope.adModel = {};
                            $scope.adModel.checkall = {};
                            $scope.addFeetypeForm.$setUntouched();
                            $scope.addFeetypeForm.$setPristine();
                            showNotification(success.data.status, success.data.message, success.data.status);
                        },
                        function (error) {
                            Loading("#addfeetype-content", '<?php echo lang("loading_datatable") ?>', "", "hide");
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
});

app2.controller("feeCollectionController", function ($scope, $http, $window, $filter) {
    $scope.classes = {};
    $scope.batches = {};
    $scope.fModel = {};  //add new fee collection model
    $scope.fcModel = {};  //fee collection (fc)
    $scope.afcModel = {}; //add fee collection (afc)
    $scope.feeCollectionStudents = {};
    $scope.selectedStd = {};
    $scope.stdFeeRecords = {};
    $scope.feetypes = {};
    $scope.today = $filter('date')(new Date(), 'dd/MM/yyyy');
    $scope.mode = 'cash';
    $scope.isSendEmailToParent = false;
    $scope.parent_id = 0;
    $scope.selectedSpecificFeetype = "All Feetypes";
    $scope.fcModel.selectedFeetype = 'all';

    $scope.initClasses = function () {
        //Loading("#dfClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'attendance/getClasses', "", config).then(
                function (success) {
                    //Loading("#dfClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.classes = success.data;
                },
                function (error) {
                    console.log(error.data);
                    //Loading("#dfClasses", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    //$window.location.href = 'errors/' + error.status;
                }
        );
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

    $scope.initBatches = function (class_id) {
        //Loading("#dfBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (class_id) {
            //Loading("#dfBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'attendance/getClassBatches', {class_id: class_id}, config).then(
                    function (success) {
                        //Loading("#dfBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.batches = success.data;
                        $scope.fcModel.batch_id = "all";
                    },
                    function (error) {
                        console.log(error.data);
                        //Loading("#dfBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        //$window.location.href = 'errors/' + error.status;
                    }
            );
    
            $http.post(base_url + 'fee/getSpecificFeetypes', {class_id: class_id}, config).then(
                function (success) {
                    $scope.feetypes = success.data;
                },
                function (error) {
                    console.log(error.data);
                }
            );
        }
    };

    $scope.fetchFeeCollections = function (valid) {
        if (valid) {
            var formData = {
                class_id: $scope.fcModel.class_id ? $scope.fcModel.class_id : null, 
                batch_id: $scope.fcModel.batch_id, searchBy: $scope.fcModel.searchBy ? $scope.fcModel.searchBy : null, 
                isDue: $scope.fcModel.isDue ? $scope.fcModel.isDue : 0, 
                specificFeeType: $scope.fcModel.selectedFeetype
            };
            $http.post(base_url + "fee/fetchfeeCollectionStudents", formData, config).then(
                    function (success) {
                        //console.log(success.data);
                        $scope.feeCollectionStudents = success.data;
                    },
                    function (error) {
                        console.log(error.data);
                    }
            );
        }
    };

    $scope.showDetails = function (std) {
        $("#feeCollectionContainer1").addClass("hidden");
        $("#feeCollectionContainer2").removeClass("hidden");
        $scope.selectedStd = std;
        $scope.fetchStudentFeeRecords($scope.selectedStd);
    };

    $scope.back = function () {
        $("#feeCollectionContainer2").addClass("hidden");
        $("#feeCollectionContainer1").removeClass("hidden");
    };

    $scope.fetchStudentFeeRecords = function (obj) {
        $http.post(base_url + "fee/getStudentFeeRecrods", {std_id: obj.id, class_id: obj.class_id, school_id: obj.school_id, discount_id: obj.discount_id, discount_amount: obj.discount_amount}, config).then(
                function (success) {
                    //console.log(success.data);
                    $scope.stdFeeRecords = success.data;
                },
                function (error) {
                    console.log(error.data);
                }
        );
    };

    $scope.setAddFeeCollectionModel = function (obj) {
        $scope.afcModel = obj;
        $scope.afcModel.student_id = $scope.selectedStd.id;
        $scope.afcModel.created_at = $scope.today;
    };

    $scope.collectFee = function (valid) {
        if (valid) {
            $http.post(base_url + "fee/collectFee", {'obj': $scope.afcModel, 'mode': $scope.mode, 'is_send_email':$scope.isSendEmailToParent}, config).then(
                    function (success) {
                        if (success.data.status === "success") {
                            $('#feeCollectionAddModel').modal('hide');
                            $scope.mode = 'cash';
                            $scope.mode = 'false';
                            $scope.paid_amount = "";
                            $scope.feeCollectionAddModelForm.$setUntouched();
                            $scope.feeCollectionAddModelForm.$setPristine();
                            $scope.fetchStudentFeeRecords($scope.selectedStd);
                            showNotification(success.data.status, success.data.message, success.data.status);
                        }

                    },
                    function (error) {
                        console.log(error.data);
                    }
            );
        }
    };
    
    $scope.setSpecificFeetype = function(feetype){
        //console.log(feetype);
        $scope.fcModel.selectedFeetype = feetype.id;
        $scope.selectedSpecificFeetype = feetype.name;
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
            'maxFilesize': 50, //MB
            'maxFiles': 100,
            'acceptedFiles': "image/*, application/pdf, .doc, .docx",
            'addRemoveLinks': true,
            'dictRemoveFile': "Remove",
            'dictFileTooBig': 'File is bigger than 50MB',
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

                        publicNotificationViaPusher('new_conversation', response.data.part, 'messages/view/' + response.data.con_id, {'sender': response.data.sender});
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

app2.controller('conversation', function ($scope, $http, $sce) {
    $scope.init = function () {
        $http.post(base_url + 'messages/getMessages', $scope.conver).then(
                function (response) {
                    $scope.messages = response.data.messages;
                    for (var i = 0, len = $scope.messages.length; i < len; i++) {
                        $scope.messages[i]['text'] = $sce.trustAsHtml($scope.messages[i]['message_body']);
                    }
                })
    }

    $scope.dropzoneConfig = {
        'options': {// passed into the Dropzone constructor
            'url': base_url + 'messages/upload_attachments',
            'autoProcessQueue': false,
            'autoDiscover': false,
            'uploadMultiple': true,
            'parallelUploads': 5,
            'maxFilesize': 50, //MB
            'maxFiles': 100,
            'acceptedFiles': "image/*, application/pdf, .doc, .docx",
            'addRemoveLinks': true,
            'dictRemoveFile': "Remove",
            'dictFileTooBig': 'File is bigger than 50MB',
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
                        publicNotificationViaPusher('new_conversation', response.data.part, 'messages/view/' + response.data.con_id, {'sender': response.data.sender});
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


app2.controller('uploadController', function ($scope, $http, $sce) {
    $scope.study = {};
    $scope.edit = {};
    $scope.details = {};

    $scope.init = function () {
        $http.post(base_url + 'attendance/getClasses', "", config).then(
                function (response) {
                    $scope.study.classes = response.data;
                });
    };

    $scope.editData = function (mat) {
        $('#edit_alert').hide();
        $scope.study.title = mat.title;
        $scope.study.type = mat.content_type;
        $scope.study.class = mat.class_id;
        $scope.study.editId = mat.id;
        $scope.getSectionsEdit(mat);
    }

    $scope.getMaterials = function () {
        $http.post(base_url + 'study_material/getMaterials').then(
                function (response) {
                    $scope.study.materials = response.data;
                    for (var i = 0, len = $scope.study.materials.length; i < len; i++) {

                        $scope.study.materials[i].details = $sce.trustAsHtml($scope.study.materials[i].details);

                    }
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

    $scope.details_set = function (mat) {

        $scope.details.title = mat.title;
        $scope.details.content_type = mat.content_type;
        $scope.details.subject_name = mat.subject_name;
        $scope.details.uploaded_time = mat.uploaded_time;
        $scope.details.files = mat.files;
        $scope.details.details = mat.details;
        $scope.details.id = mat.id;
    }

    $scope.download = function () {
        Loading("body", "", "", "show");
        $http.post(base_url + 'study_material/zip', $scope.details).then(
                function (response) {

                    window.location.href = response.data.path;
                    Loading("body", "", "", "hide");
                })
    }

    $scope.getSectionsEdit = function (mat) {
        $http.post(base_url + 'study_material/getBatches', $scope.study).then(
                function (response) {
                    $scope.study.batches = response.data.batches;
                    if (mat.batch_id == 0) {
                        $scope.study.section = "all";
                    } else {
                        $scope.study.section = mat.batch_id;
                    }

                    $scope.getSubjectsEdit(mat);
                })
    }

    $scope.getSubjectsEdit = function (mat) {
        $http.post(base_url + 'study_material/getSubjects', $scope.study).then(
                function (response) {
                    $scope.study.subjects = response.data.subjects;
                    $scope.study.subject = mat.subject_id;
                })
    }


    $scope.getSubjects = function () {
        $http.post(base_url + 'study_material/getSubjects', $scope.study).then(
                function (response) {
                    $scope.study.subjects = response.data.subjects;
                    $scope.study.subject = "";
                })
    }

    $scope.newMaterial = function () {
        $http.post(base_url + 'study_material/newMaterial', $scope.study).then(
                function (response) {
                    $scope.getMaterials();
                    $('#upload').modal('toggle');
                    showNotification('Success', response.data.message, 'success');
                    publicNotificationViaPusher('new_study_material', response.data.part, 'study_material/upload', {'sender': response.data.sender});
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

    $scope.update_material = function () {
        $http.post(base_url + 'study_material/updateMaterial/', $scope.study).then(
                function (response) {
                    if (response.data.updated == true) {
                        $('#editupload').modal('toggle');
                        $scope.getMaterials();
                        showNotification('Success', response.data.message, 'success');
                    } else {
                        $('#edit_alert').removeClass('alert-success').addClass('alert-danger').html(response.data.message).show();
                    }
                    //console.log(response.data);
                }
        )
    }

    $scope.resetModal = function () {
        $scope.study.title = "";
        $scope.study.type = "";
        $scope.study.class = "";
        $scope.study.section = "";
        $scope.study.subject = "";
        $('.textarea_editor').data("wysihtml5").editor.clear();
        $('#message_alert').hide();
        Dropzone.forElement("#my-awesome-dropzone").removeAllFiles(true);
    }

    $scope.dropzoneConfig = {
        'options': {// passed into the Dropzone constructor
            'url': base_url + 'study_material/upload_attachments',
            'autoProcessQueue': false,
            'autoDiscover': false,
            'uploadMultiple': true,
            'parallelUploads': 10,
            'maxFilesize': 50, //MB
            'maxFiles': 100,
            'acceptedFiles': "image/*, application/pdf, .doc, .docx",
            'addRemoveLinks': true,
            'dictRemoveFile': "Remove",
            'dictFileTooBig': 'File is bigger than 50MB',
            init: function () {
                var submitButton = document.querySelector("#upload_material");
                myDropzone = this;
                submitButton.addEventListener("click", function () {
                    $scope.study.files = "";
                    var text = $('.textarea_editor').val();
                    $scope.study.text = text;

                    if ($scope.study.title == "" || $scope.study.title == undefined)
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("material_title_req"); ?>').show();
                    else if ($scope.study.type == "" || $scope.study.type == undefined)
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("material_type_req"); ?>').show();
                    else if ($scope.study.class == "" || $scope.study.class == undefined)
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("class_req"); ?>').show();
                    else if ($scope.study.section == "" || $scope.study.section == undefined)
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("section_req"); ?>').show();
                    else if ($scope.study.subject == "" || $scope.study.subject == undefined)
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("subject_req"); ?>').show();

                    else if (myDropzone.files.length == 0 && $scope.study.text != undefined && $scope.study.text != "")
                        $scope.newMaterial();
                    else if (myDropzone.files.length == 0 && ($scope.study.text == undefined || $scope.study.text == ""))
                        $('#message_alert').removeClass('alert-success').addClass('alert-danger').html('<?php echo lang("write_details"); ?>').show();
                    else if (myDropzone.files.length != 0)
                        myDropzone.processQueue();
                });
                myDropzone.on("complete", function (file) {
                    if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                        $scope.newMaterial();
                    }
                });
            }
        },
        'eventHandlers': {
            'sending': function (file, xhr, formData) {
            },
            'success': function (file, response) {
                $scope.study.files = response;
            }
        }
    };

});

app2.controller('downloadController', function ($scope, $http, $sce) {
    $scope.study = {};
    $scope.details = {};
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

        if ($check) {
            Loading("body", "", "", "show");
            $http.post(base_url + 'study_material/filter', $scope.study).then(
                    function (response) {



                        $scope.study.materials = response.data.materials;

                        for (var i = 0, len = $scope.study.materials.length; i < len; i++) {

                            $scope.study.materials[i]['details'] = $sce.trustAsHtml($scope.study.materials[i]['details']);
                        }
                        Loading("body", "", "", "hide");


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
    };

    $scope.download = function () {
        Loading("body", "", "", "show");
        $http.post(base_url + 'study_material/zip', $scope.details).then(
                function (response) {
                    window.location.href = response.data.path;
                    Loading("body", "", "", "hide");
                });
    };
});


//*************************************//
//   Notification Profile controllers //
//************************************//

app2.controller('profileController', function ($scope, $http) {
    $scope.countNotification = 0;
    $scope.notifications = {};
    $scope.allCount = 0;
    $scope.requestResponse = {};
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
});

function publicNotificationViaPusher(msg_key, recipient, url, data) {
    $.ajax({
        url: base_url + "notification/sendNotificationViaPusher",
        method: "post",
        data: {msg_key: msg_key, recipient: recipient, url: url, data: data},
        success: function (result) {
            console.log(result.response);
        },
        error: function (result) {
            console.log(result.response);
        }
    });
}

//***************************************//
// End Notification Profile controllers  //
//***************************************//

app2.controller('parentAdmissionController', function ($scope, $http, $window, $location) {
    $scope.formModel = {
        pCity: "",
        pDob: "",
        pOccupation: "",
        pIncome: "",
        pPhone: "",
        pStreet: "",
        pIdNumber: "",
        pCity: ""
    };

    $scope.alert = {};
    $scope.onSubmit = function (valid, image, image1) {
        if (valid) {
            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");

            if (image1) {
                $scope.formModel.pAvatar = image1.dataURL;
            } else {
                $scope.formModel.pAvatar = null;
            }


            $http.post(base_url + 'parents/save', $scope.formModel, config).then(
                    function (success) {
                        $window.scrollTo(0, 0);
                        if (success.data.status === "success") {
                            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                            $scope.formModel = {};
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
    $scope.formModel = {};

    $scope.alert = {};


    $scope.fetchParent = function (id) {
        $http.post(base_url + 'parents/getParent', {'parent_id': id}, config).then(
                function (success) {
                    $scope.formModel = success.data;
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



            if (image1) {
                $scope.formModel.avatar = image1.dataURL;
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
    $scope.showstd = {};
    $scope.parentId = {};
    $scope.email = {};
    $scope.batches = {};
    $scope.parentIdResponse = [];
    $scope.final_students = [];

    $scope.init_csv = function (csv_data) {

        $scope.csv_students = angular.fromJson(csv_data);
        angular.forEach($scope.csv_students, function (value, key) {
            $http.post(base_url + 'import/is_Exist', value, config).then(
                    function (success) {
                        if (success.data.is_exist === '1') {
                            value.is_exist = 1;
                        } else {
                            value.is_exist = 0;
                        }
                    }, function (error) {
                console.log(error.data);
            }
            );
        });
    };
    $scope.removeItem = function (item) {
        var index = $scope.csv_students.indexOf(item);
        $scope.csv_students.splice(index, 1);
//        console.log($scope.csv_students);
        if ($scope.csv_students.length === 0) {
            alert('<?php echo lang("new_import"); ?>');
            $window.location.href = base_url + 'students/show';
        }
    };

    $scope.fetchClassBatches = function (class_id) {
        Loading("#frmBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'settings/getClassBatches', {id: class_id}, config).then(
                function (success) {
                    Loading("#frmBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    $scope.batches = success.data;

                },
                function (error) {
                    Loading("#frmBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    };

    $scope.ShowImport = function (data) {
        $scope.showstd = data;
        //console.log($scope.showstd);
        $scope.email['email'] = $scope.showstd.Parent_Email;

        $http.post(base_url + 'import/parentId', $scope.email, config).then(
                function (success) {
                    $scope.parentIdResponse = success.data;
                }, function (error) {
            $scope.parentId = 0;
            console.log(error.data);
            ;
        });
        $('#stdForm').show();
        $('#inbox_div').hide();
    };

    $scope.ShowList = function () {
        $('#inbox_div').show();
        $('#stdForm').hide();

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
            $scope.showstd['parentId'] = $scope.parentIdResponse;
            //console.log($scope.csv_students);
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
                        console.log(error.data);
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
    $scope.resImgQuality = 1;
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

    $scope.onSubmitEmp = function (valid) {
        if (valid) {
            Loading("#attEmployeeTable", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'attendance/fetchEmployeesAttendance', $scope.filterModel, config).then(
                    function (success) {
                        Loading("#attEmployeeTable", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.employees = success.data.employees;
                        $scope.message = success.data.message;
                        $scope.selectedDate = $scope.filterModel.date;
                    },
                    function (error) {
                        Loading("#attEmployeeTable", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $window.location.href = 'errors/' + error.status;
                        //console.log(error);
                    }
            );
        }
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

            Loading("#attEmployeeTable", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'attendance/saveEmployee', $scope.data, config).then(
                    function (success) {
                        Loading("#attEmployeeTable", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        showNotification("Success!", success.data.message, "success");
                        console.log(success.data);
                    },
                    function (error) {
                        Loading("#attEmployeeTable", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        //$window.location.href = 'errors/' + error.status;
                        console.log(error.data);
                    }
            );
        }
    };
});
/*------employee Report Controller*/
app2.controller("reportEmployeeController", function ($scope, $http, $window, $location, $sce) {

    $scope.months = [
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
    ];
    $scope.report = {};
    $scope.range = [];
    $scope.finalReport = {};

    $scope.onSubmitFetchReport = function (valid) {
        if (valid) {
//            angular.forEach($scope.batches, function (value) {
//                if (value.id === $scope.arModel.batch_id) {
//                    $scope.arModel.academic_year_id = value.academic_year_id;
//                }
//            });
            Loading(".attendance-table", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + "attendance/generate_employee_report", $scope.arModel, config).then(
                    function (success) {
                        Loading(".attendance-table", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.finalReport = success.data.att;
                        //console.log($scope.finalReport);
                    },
                    function (error) {
                        Loading(".attendance-table", '<?php echo lang("loading_datatable") ?>', "", "hide");
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

app2.controller("licenserenewController", function($scope, $http, $window, $location, $timeout){
    $scope.selectedValues = {};
    $scope.alert = {};
    $scope.setSelectedVlu = function(id,end_date,liId){
        $scope.selectedValues.id = id;
        $scope.selectedValues.end_date = end_date;
        $scope.selectedValues.li = liId;
    };
    
    $scope.updateLicense = function(){
        $scope.selectedValues.end_date = $scope.reneweditmodalform.$$element[0].end_date.value;
        $http.post(base_url + "licensesrenew/update", $scope.selectedValues, config).then(
           function(success){
                $timeout(function () {
                    $scope.alert = {};
                    $('#renew_edit_modal').modal('toggle');
                    $window.location.reload();
                }, 500);
               $scope.alert.message = success.data.message;
               $scope.alert.status = success.data.status;
           },
           function(error){
               console.log(error.data);
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
                {title: '<?php echo lang("imp_std_roll_no");?>', data: 'rollno' },
                {title: '<?php echo lang("lbl_class");?>', data: 'class'},
                {title: '<?php echo lang("lbl_batch");?>', data: 'batch'},
                {title: '<?php echo lang("lbl_gender");?>', data: 'gender'},
                {title: '<?php echo lang("lbl_email");?>', data: 'email'},
                {title: '<?php echo lang("lbl_city");?>', data: 'city'},
                {title: '<?php echo lang("lbl_mobile");?>', data: 'mobile_phone'},
                {title: '<?php echo lang("lbl_religion");?>', data: 'religion'},
                {title: '<?php echo lang("lbl_dob");?>', data: 'dob'},
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
                {title: '<?php echo lang("job_title"); ?>', data: 'job_title'},
                {title: '<?php echo lang("type_dt"); ?>', data: 'category_name'},
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
    
    $scope.iniType = function () {
        $http.post(base_url + 'reports/getFeeType', "", config).then(
                function (success) {
                    $scope.feeType = success.data.feeTypes;
                },
                function (error) {
                    $window.location.href = 'errors/' + error.status;
                    //console.log(error);
                }
        );
    };

    //fee Types multi dropdown
    $scope.existType = function (item) {
        return $scope.typeSelected.indexOf(item) > -1;
    };
    $scope.toggleType = function (item) {

        var idx = $scope.typeSelected.indexOf(item);
        if (idx > -1) {
            $scope.typeSelected.splice(idx, 1);
            $scope.type_id.splice(idx, 1);
        } else {
            $scope.typeSelected.push(item);
            $scope.type_id.push(item.id);
        }
    }

    $scope.checkAllType = function () {
        if ($scope.TypeAll) {
            angular.forEach($scope.feeType, function (item) {
                idx = $scope.typeSelected.indexOf(item);
                if (idx >= 0) {
                    return true;
                } else {
                    $scope.typeSelected.push(item);
                    $scope.type_id.push(item.id);
                }
            });
        } else {
            $scope.typeSelected = [];
            $scope.type_id = [];
        }
        
    };
   
    
    $scope.iniDiscountType = function () {
        $http.post(base_url + 'reports/getDiscountTypes', "", config).then(
                function (success) {
                    $scope.feeDiscount = success.data.feeDiscount;
                },
                function (error) {
                    $window.location.href = 'errors/' + error.status;
                    //console.log(error);
                }
        );
    };

    //discount Type multi dropdown
    $scope.existDiscount = function (item) {
        return $scope.DiscountSelected.indexOf(item) > -1;
    };
    $scope.toggleDiscount = function (item) {

        var idx = $scope.DiscountSelected.indexOf(item);
        if (idx > -1) {
            $scope.DiscountSelected.splice(idx, 1);
             $scope.discount_id.splice(idx, 1);
        } else {
            $scope.DiscountSelected.push(item);
            $scope.discount_id.push(item.id);
        }
       
    };

    $scope.checkAllDsicount = function () {
        if ($scope.disountAll) {
            angular.forEach($scope.feeDiscount, function (item) {
                idx = $scope.DiscountSelected.indexOf(item);
                if (idx >= 0) {
                    return true;
                } else {
                    $scope.DiscountSelected.push(item);
                    $scope.discount_id.push(item.id);
                }
            });
        } else {
            $scope.DiscountSelected = [];
            $scope.discount_id = [];
        }
        
    };

    $scope.iniCollector = function () {
        $http.post(base_url + 'reports/getCollector', "", config).then(
                function (success) {
                    $scope.collectors = success.data.collectors;
                },
                function (error) {
                    $window.location.href = 'errors/' + error.status;
                    //console.log(error);
                }
        );
    };

    //Collector Type multi dropdown
    $scope.existCollector = function (item) {
        return $scope.collectorSelected.indexOf(item) > -1;
    };
    $scope.toggleCollector = function (item) {

        var idx = $scope.collectorSelected.indexOf(item);
        if (idx > -1) {
            $scope.collectorSelected.splice(idx, 1);
            $scope.collect_id.splice(idx, 1);
        } else {
            $scope.collectorSelected.push(item);
            $scope.collect_id.push(item.id);
        }
       
    };

    $scope.checkAllCollector = function () {
        if ($scope.collectorAll) {
            angular.forEach($scope.collectors, function (item) {
                idx = $scope.collectorSelected.indexOf(item);
                if (idx >= 0) {
                    return true;
                } else {
                    $scope.collectorSelected.push(item);
                    $scope.collect_id.push(item.id);
                }
            });
        } else {
            $scope.collectorSelected = [];
            $scope.collect_id = [];
        }
        
    };
    
    $scope.onsubmit = function () {

        $scope.filterModel.types_id = $scope.type_id;
        $scope.filterModel.discounts_id = $scope.discount_id;
        $scope.filterModel.collects_id = $scope.collect_id;
        
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
           
            
            columns: [
                {title: '<?php echo lang("lbl_student");?>', data: 'std_name'},
                {title: '<?php echo lang("fee_type");?>', data: 'type'},
                {title: '<?php echo lang("lbl_discount_type");?>', data: 'discount'},
                {title: '<?php echo lang("lbl_fee_type_amount");?>', data: 'feetype_amount'},
                {title: '<?php echo lang("lbl_fee_discount_amount");?>', data: 'discount_amount'},
                {title: '<?php echo lang("paid_mode");?>', data: 'mode'},
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
                },
                 {
                extend: 'pdfHtml5'
                }
            ],
            
            destroy: true
        });
    
    };
    
});

app2.controller("marksheetController", function ($scope, $http, $window, $location) {
    $scope.classes = {};
    $scope.batches = {};
    $scope.subjects = {};
    $scope.exams = {};
    $scope.students = {};
    $scope.filterModel = {};
    $scope.enteredMarks = {};
    $scope.message;

    $scope.initClasses = function () {
        Loading("#marksFilterClasses", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'attendance/getClasses', "", config).then(
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
    };
    
    $scope.initBatches = function (class_id) {
        Loading("#marksFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (class_id) {
            Loading("#marksFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'attendance/getClassBatches', {class_id: class_id}, config).then(
                    function (success) {
                        Loading("#marksFilterBatches", '<?php echo lang("loading_datatable") ?>', "", "hide");
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
        Loading("#marksFilterSubjects", '<?php echo lang("loading_datatable") ?>', "", "show");
        if (class_id && batch_id) {
            Loading("#marksFilterSubjects", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'examination/getSubjects', {class_id: class_id, batch_id:batch_id}, config).then(
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
    
    $scope.initExams = function (class_id, batch_id, subject_id) {
        if (class_id && batch_id && subject_id) {
            Loading("#marksFilterExams", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'examination/getExams', {class_id: class_id, batch_id:batch_id, subject_id:subject_id}, config).then(
                    function (success) {
                        Loading("#marksFilterExams", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.exams = success.data;
                        $scope.filterModel.exam_id = "";
                    },
                    function (error) {
                        console.log(error.data);
                    }
            );
        }
    };
    
    $scope.onSubmit = function (valid) {
        if (valid) {
            Loading("#marksStudentsTable", '<?php echo lang("loading_datatable") ?>', "", "show");
            $http.post(base_url + 'examination/fetchStudents', $scope.filterModel, config).then(
                    function (success) {
                        Loading("#marksStudentsTable", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $scope.students = success.data.students;
                        $scope.message = success.data.message;
                    },
                    function (error) {
                        Loading("#marksStudentsTable", '<?php echo lang("loading_datatable") ?>', "", "hide");
                        $window.location.href = 'errors/' + error.status;
                        //console.log(error);
                    }
            );
        }
    };
    
    $scope.saveMarksheet = function () {
        $scope.data = [];
        var passing_marks;
        angular.forEach($scope.exams, function (value, key) {
            if(value.id == $scope.filterModel.exam_id){
                passing_marks = value.passing_marks;
            }
        });
        angular.forEach($scope.students, function (value, key) {
            var obtain_marks = $("#id_"+key).val();
            $scope.data.push({
                id: value.id, 
                class_id: value.class_id, 
                batch_id: value.batch_id, 
                obtain_marks:obtain_marks,
                subject_id:$scope.filterModel.subject_id,
                exam_detail_id:$scope.filterModel.exam_id,
                passing_marks: passing_marks
            });
        });
        
        Loading("#marksStudentsTable", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'examination/save', $scope.data, config).then(
                function (success) {
                    Loading("#marksStudentsTable", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    showNotification("Success!", success.data.message, "success");
                },
                function (error) {
                    console.log(error.data);
                    //Loading("#marksStudentsTable", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    //$window.location.href = 'errors/' + error.status;
                }
        );
    };
    
    $scope.moveNext = function(keyEvent, index){
        if (keyEvent.which === 13) {
            $("#id_"+(index+1)).focus();
        }
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
    
    $scope.onSubmit = function(){
        $scope.isClick = true;
        Loading("#syllabusContainer", '<?php echo lang("loading_datatable") ?>', "", "show");
        $scope.filterModel.type = 'syllabus';
        $http.post(base_url + 'syllabus/getSyllabus', $scope.filterModel, config).then(
            function (success) {
                Loading("#syllabusContainer", '<?php echo lang("loading_datatable") ?>', "", "hide");
                //console.log(success.data);
                $scope.weeklySyllabus = success.data.syllabus;
                $scope.syllabusCanEdit = success.data.can_syllabus_edit;
                $scope.requestId = success.data.request_id;
                $scope.requestStatus = success.data.reqeust_status;
            },
            function (error) {
                Loading("#syllabusContainer", '<?php echo lang("loading_datatable") ?>', "", "hide");
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
                    $scope.addWeekDetailModel.topic = " ";
                    $scope.addWeekDetailModel.status = "Pending";
                    $scope.addWeekDetailModel.comment = " ";
                    $scope.onSubmit();
                    //console.log(success.data);
                },
                function (error) {
                    Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                    console.log(error.data);
                }
        );
    };
    
    $scope.changeStatus = function(status, id){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        if(status === 'Partially Done' || status==='Reschedule'){
            Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
            $scope.addCommentModel.status = status;
            $scope.addCommentModel.id = id;
            $("#addCommentModal").modal("show");
        } else {
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
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $http.post(base_url + 'syllabus/reqForApprovalSyls', {id:id,status:status}, config).then(
            function (success) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                showNotification('<?php echo lang("success_app") ?>', success.data.message, "success");
                $scope.getSchoolAdmins();
                $scope.onSubmit();
            },
            function (error) {
                Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
                console.log(error.data);
            }
        );
    };
    
    $scope.getSchoolAdmins = function(){
        $http.post(base_url + 'syllabus/getSchoolAdmins', {}, config).then(
            function(success){
                publicNotificationViaPusher("lbl_approval_notify_msg", success.data, "notification/index", {firstname:'yasir',lastname:'mirza'});
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

app2.controller("appCtrl", function ($scope, $http, $window, $location, $filter) {

    $scope.temp={};

    $scope.onView = function (class_id,batch_id,subject_id,name){
        Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
        $scope.temp.class_id = class_id;
        $scope.temp.batch_id = batch_id;
        $scope.temp.subject_id = subject_id;
        $scope.temp.type = 'syllabus';
        $scope.temp.subjectname=name;
        console.log($scope.temp);
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
    }

    $scope.viewAttendance = function(date, class_id, batch_id){
       Loading("body", '<?php echo lang("loading_datatable") ?>', "", "show");
       var flag = "true";
        $http.post(base_url + 'attendance/fetchStudentsAttendance', {date, class_id, batch_id, flag}, config).then(
           function (success) {
               Loading("body", '<?php echo lang("loading_datatable") ?>', "", "hide");
               $scope.students = success.data.students;
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
    }
});