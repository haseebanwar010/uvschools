<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<div>
    <!-- Page Content -->
    <div id="page-wrapper" ng-controller="onlineExamsController">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?= lang('lbl_major_sheet') ?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="#"><?= lang('crumb_dashboard') ?></a></li>
                        <li><a href="#"><?= lang('lbl_online_exam') ?></a></li>
                        <li class="active"><?= lang('lbl_major_sheet') ?></li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <div class="hint"><?= lang('help_online_exams_major_sheet') ?></a></div>
            <!-- Page Content start here -->
            

            

            <!--.row-->
            <div class="white-box well">
                <form class="form-material" name="marktsFilterForm" ng-submit="getMajorSheet()" novalidate="">
                    <div class="row">
                        <div class="col-md-3" id="marksFilterAcademicYears">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang("lbl_academic_year"); ?></label>
                                <select class="form-control" name="academic_year_id" ng-model="resultModel.academic_year_id" required="" ng-init="initAcademicYears()" ng-change="initClasses(resultModel.academic_year_id)">
                                    <option value="-1"><?php echo lang("lbl_select_academic_year"); ?></option>
                                    <option ng-repeat="ay in academicyears" value="{{ ay.id }}">{{ ay.name }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3" id="marksFilterClasses">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                <select class="form-control" ng-model="resultModel.class_id" ng-change="initBatches(resultModel.class_id, resultModel.academic_year_id); initMainExams(resultModel.class_id, resultModel.academic_year_id);" required="">
                                    <option value=""><?php echo lang('select_course') ?></option>
                                    <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3" id="marksFilterBatches">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('lbl_batch') ?></label>
                                <select class="form-control" ng-model="resultModel.batch_id" required="">
                                    <option value=""><?php echo lang('select_batch') ?></option>
                                    <option ng-repeat="bth in batches" value="{{bth.id}}">{{bth.name}}</option>
                                </select>
                            </div>
                        </div>
                    
                    
                        <div class="col-md-3" id="marksFilterExams">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('lbl_exam_session') ?></label>
                                <select class="form-control" ng-show="main_exams.length == 0" ng-model="resultModel.exam_id">
                                    <option value=""><?php echo lang("no_record"); ?></option>
                                </select>
                                <select class="form-control" ng-show="main_exams.length != 0" ng-model="resultModel.exam_id" required="">
                                    <option value=""><?php echo lang('lbl_select_exam') ?></option>
                                    <option ng-repeat="em in main_exams" value="{{em.id}}">{{em.title}}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <input type="submit" class="btn btn-primary pull-right" value="<?php echo lang('search') ?>" />
                        </div>
                    </div>

                </form>
            </div>
            <!--./row-->

            <div class="white-box" ng-show="results.length>0">
                <div class="row no-print">
                    <div class="col-md-12">
                        <div class="col-md-9 p-0">
                            <div class="form-group">
                                <button type="button" ng-click="majorsheetPrint('marjorsheet_print_container','<?php echo $this->session->userdata("userdata")["sh_logo"]; ?>','<?php echo $this->session->userdata("userdata")["sh_name"]; ?>','<?php if($this->session->userdata("site_lang") != "english") { echo "direction:rtl;"; }?>')" class="btn btn-info"><i class="fa fa-print"></i> <?php echo lang("lbl_print_majorsheet"); ?></button>
                                <button type="button" class="btn btn-warning" id="button-a"><i class="fa fa-file-excel-o"></i> <?php echo lang("lbl_export_to_excel"); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <table id="mytabletemp" style="display: none;">
                    <tr>
                        <th>#</th>
                        <th><?= lang('lbl_name') ?></th>
                        <th><?= lang('imp_std_roll_no') ?></th>
                        <th ng-repeat="p in papers" class="text-center">{{p.paper_name}}</th>
                        <th class="text-center"><?= lang('lbl_total') ?></th>
                        <th class="text-center"><?= lang('percentage') ?></th>
                        <th class="text-center"><?= lang('exam_position') ?></th>
                        <th class="text-center"><?= lang('lbl_remarks') ?></th>
                    </tr>
                    <tr ng-repeat="r in results">
                        <td>{{$index+1}}</td>
                        <td>{{r.name}}</td>
                        <td>{{r.rollno}}</td>
                        <td class="text-center" ng-repeat="pd in r.paper_data">
                            <span ng-if="pd.valid">{{pd.obtained_marks}} | {{pd.total_marks}}</span>
                            <span ng-if="!pd.valid">-</span>
                        </td>
                        <td class="text-center">
                            <span ng-if="r.total_marks == 0">-</span>
                            <span ng-if="r.total_marks != 0">{{r.obtained_marks}} | {{r.total_marks}}</span>
                        </td>
                        <td class="text-center">
                            <span ng-if="r.total_marks == 0">-</span>
                            <span ng-if="r.total_marks != 0">{{r.percentage}}</span>

                        </td>
                        <td class="text-center">
                            {{r.position}}
                        </td>
                        <td class="text-center">
                            {{r.remark}}
                        </td>
                    </tr>
                </table>
                <div class="row">
                    <div class="col-md-12" id="marjorsheet_print_container">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <tr>
                                    <th>#</th>
                                    <th><?= lang('lbl_avatar') ?></th>
                                    <th><?= lang('lbl_name') ?></th>
                                    <th><?= lang('imp_std_roll_no') ?></th>
                                    <th ng-repeat="p in papers" class="text-center">{{p.paper_name}}</th>
                                    <th class="text-center"><?= lang('lbl_total') ?></th>
                                    <th class="text-center"><?= lang('percentage') ?></th>
                                    <th class="text-center"><?= lang('exam_position') ?></th>
                                    <th class="text-center"><?= lang('lbl_remarks') ?></th>
                                </tr>
                                <tr ng-repeat="r in results">
                                    <td>{{$index+1}}</td>
                                    <td><img src="<?php echo base_url(); ?>uploads/user/{{r.avatar}}" width="50px" height="50px" class="img-circle" /></td>
                                    <td>{{r.name}}</td>
                                    <td>{{r.rollno}}</td>
                                    <td class="text-center" ng-repeat="pd in r.paper_data">
                                        <span ng-if="pd.valid">{{pd.obtained_marks}}/{{pd.total_marks}}</span>
                                        <span ng-if="!pd.valid">-</span>
                                    </td>
                                    <td class="text-center">
                                        <span ng-if="r.total_marks == 0">-</span>
                                        <span ng-if="r.total_marks != 0">{{r.obtained_marks}}/{{r.total_marks}}</span>
                                    </td>
                                    <td class="text-center">
                                        <span ng-if="r.total_marks == 0">-</span>
                                        <span ng-if="r.total_marks != 0">{{r.percentage}}%</span>
                                        
                                    </td>
                                    <td class="text-center">
                                        {{r.position}}
                                    </td>
                                    <td class="text-center">
                                        <p style="white-space: nowrap; overflow: hidden;text-overflow: ellipsis;" class="setPointer"><small ng-if="r.remark != '' && r.remark != null" data-toggle="modal" data-target="#remarks-modal" ng-click="setStudentID(r.id, r.remark)">{{r.remark}}</small></p>
                                        <a href="javascript:void();" ng-if="r.remark == '' || r.remark == null" ng-click="setStudentID(r.id, r.remark)" data-toggle="modal" data-target="#remarks-modal" class="btn btn-primary btn-circle no-print"><i class="fa fa-plus"></i></a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    
                
                    <!--./row-->
                    <!--page content end here-->
                </div>
            </div>
            <div id="remarks-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><?php echo lang('add_teacher_remarks'); ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    </div>
                    <div class="modal-body">
                        <form name="teacherRemarksForm" ng-submit="saveTeacherRemarks(teacherRemarksForm.$valid)" novalidate="">
                            <div class="form-group">
                                <label><?php echo lang('lbl_remarks'); ?></label>
                                <textarea cols="3" class="form-control" rows="4" required="" ng-model="remark"></textarea>
                            </div>
                            <div class="pull-right">
                                <input type="reset" data-dismiss="modal" aria-hidden="true" value="<?php echo lang('btn_cancel'); ?>" class="btn btn-default" />
                                <input type="submit" value="<?php echo lang('btn_save'); ?>" class="btn btn-success" />
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
            

        </div>
    <!-- /.container-fluid -->
    <script type="text/javascript">
    
        function s2ab(s) {
                        var buf = new ArrayBuffer(s.length);
                        var view = new Uint8Array(buf);
                        for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                        return buf;
        }
        $("#button-a").click(function(){
        var wb = XLSX.utils.table_to_book(document.getElementById('mytabletemp'), {sheet:"Sheet JS"});
        var wbout = XLSX.write(wb, {bookType:'xlsx', bookSST:true, type: 'binary'});
        saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), 'test.xlsx');
        });
var tableToExcel = (function() {
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name) {
    if (!table.nodeType) table = document.getElementById(table)
    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    window.location.href = uri + base64(format(template, ctx))
  }
})()
</script>
<?php include(APPPATH . "views/inc/footer.php"); ?>