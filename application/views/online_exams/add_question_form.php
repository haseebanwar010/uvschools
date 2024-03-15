<?php include(APPPATH . "views/inc/header.php"); ?>
<?php include(APPPATH . "views/inc/menu.php"); ?>
<?php include(APPPATH . "views/inc/sidebar.php"); ?>
<div>
    <!-- Page Content -->
    <div id="page-wrapper" ng-controller="onlineExamsController">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?php echo lang('add_questions');?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="#"><?= lang('crumb_dashboard') ?></a></li>
                        <li><a href="#"><?php echo lang('lbl_online_exam');?></a></li>
                        <li class="active">Questions</li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <div class="hint"><?php echo lang('help_online_exams_settings');?></div>
            <!-- Page Content start here -->
            <!--.row-->
            <div class="row">
                
                <div class="col-md-12">
                    <form name="filterForm" class="form-material" id="filterForm" ng-submit="getQuestionsTemplate(filterForm.$valid)" novalidate>
                        <div class="white-box">
                            <div class="row">
                                <div class="col-md-3" id="marksFilterAcademicYears">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang("lbl_academic_year"); ?></label>
                                        <select class="form-control" name="academic_year_id" ng-model="filterModel.academic_year_id" required="" ng-init="initAcademicYears()" ng-change="initClasses(filterModel.academic_year_id)">
                                            <option value="-1"><?php echo lang("lbl_select_academic_year"); ?></option>
                                            <option ng-repeat="ay in academicyears" value="{{ ay.id }}">{{ ay.name }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3" id="marksFilterClasses">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('lbl_class') ?></label>
                                        <select class="form-control" ng-model="filterModel.class_id" ng-change="initMainExamsForQuestions(filterModel.class_id, filterModel.academic_year_id);" required="">
                                            <option value=""><?php echo lang('select_course') ?></option>
                                            <option ng-repeat="cls in classes" value="{{cls.id}}">{{cls.name}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3" id="exam-select-container">
                                    <div class="form-group">
                                        <label><?php echo lang('lbl_exam_session');?></label>
                                        <select name="exam_name" required class="form-control" ng-model="filterModel.exam_id" ng-init="filterModel.exam_id=''" ng-change="initPapers(filterModel.exam_id, filterModel.class_id)">
                                            <option value=''><?php echo lang('select_an_exam') ?></option>
                                            <option ng-repeat="ex in exams" value="{{ex.id}}">{{ex.title}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3" id="paper-select-container">
                                    <div class="form-group">
                                        <label><?php echo lang('lbl_papers') ?></label>
                                        <select name="paper" required class="form-control" ng-model="filterModel.paper_id"  ng-init="filterModel.paper_id=''">
                                            <option value=''><?php echo lang('select_a_paper') ?></option>
                                            <option ng-repeat="p in papers" value="{{p.id}}">
                                                {{p.class_name}} - Sections ({{p.batch_name}}) - Paper ({{p.paper_name}})
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-sm btn-primary" type="submit"><?php echo lang('search') ?></button>
                                </div>
                            </div>    
                        </div>
                    </form>
                </div>
                
                <div class="col-md-12">
                
                
                
                <div class="row">
                    <div class="col-md-12" ng-show="questionModel['1']">
                        <div class="white-box">
                            <table style="width: 100%;">
                                <tr>
                                    <td>
                                        <img src="uploads/logos/<?php echo $this->session->userdata('userdata')['sh_logo']; ?>" width='100' height="100" />
                                    </td>
                                    <td class="text-center">
                                        <h3><?php echo lang('subject_for_exam');?>: {{questionModel["1"].subject_name}}</h3>
                                        <h4><?php echo lang('paper_for_exam');?>: {{questionModel["1"].paper_name}}</h4>
                                    </td>
                                    <td class="text-right">
                                        <strong><?php echo lang('time_for_exam');?>:</strong> <span>{{questionModel["1"].duration_in_minutes}} Minutes</span><br/>
                                        <strong><?php echo lang('total_question_for_exam');?>:</strong> <span>{{questionModel["1"].number_of_questions}}</span><br/>
                                        <strong><?php echo lang('publish_for_exam');?>:</strong> <span>{{published.charAt(0).toUpperCase() + published.slice(1)}}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
​  <fieldset ng-disabled="published == 'yes'">
    <div class="row">
                    <div class="col-md-4" ng-repeat="(key,q) in questionModel">
                        <div class="white-box">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="button" class="btn btn-sm btn-outline-success btn-rounded" ng-show="q.id!=null && q.saved" disabled="">
                                    <?php echo lang('saved_for_exam');?> <i class="fa fa-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-success btn-rounded" ng-show="q.id!=null && q.updated" disabled="">
                                    <?php echo lang('updated_for_exam');?> <i class="fa fa-check"></i>
                                    </button>
                                </div>
                                <br><br>
                                <form name="questionForm" id="{{q.question_form_id}}" ng-submit="saveSingleQuestion(q)" novalidate>
                                    <div class="col-md-6">
                                        <div class="form-group m-b-10">
                                            <!--<label>Question number:</label>-->
                                            <select class="form-control" ng-model="q.question_no" required disabled="">
                                                <option value=""><?php echo lang('select_question_number');?></option>
                                                <option ng-repeat="n in q.question_numbers" value="{{n.value}}">{{n.title}}</option>
                                            </select>
                                        </div>
                                    </div>
​
                                    <div class="col-md-6">
                                        <div class="form-group m-b-10">
                                            <!--<label>Question Marks:</label>-->
                                            <input type="number" name="questionmarks" class="form-control" ng-model="q.question_marks" required placeholder="<?php echo lang('question_marks');?>"/>
                                        </div>
                                    </div>
​
                                    <div class="col-md-12">
                                        <div class="form-group m-b-10">
                                            <!--<label>Question Type:</label>-->
                                            <select class="form-control" ng-model="q.question_type" required>
                                                <option value=""><?php echo lang('Select_question_type');?></option>
                                                <option value="single_answer"><?php echo lang('Single_Answer');?></option>
                                                <option value='true_false'><?php echo lang('True_False');?></option>
                                                <option value="multi_answer"><?php echo lang('Multiple_Answer');?>r</option>
                                                <option value="single_fill_in_the_blank"><?php echo lang('Single_Fill_In_The_Blank');?></option>
                                                <option value="double_fill_in_the_blank"><?php echo lang('Double_Fill_In_The_Blank');?></option>
                                            </select>
                                        </div>
                                    </div>
​
                                    <div class="col-md-12">
                                        <div class="form-group m-b-10">
                                            <!--<label>Question:</label>-->
                                            <textarea cols="100" rows="3" class="form-control" placeholder="<?php echo lang('lbl_enter_question'); ?>" required ng-model="q.question" required></textarea>
                                        </div>
                                    </div>
​
                                    <div class="" ng-show="q.question_type=='single_fill_in_the_blank'">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input type="text" name="correct_answer" class="form-control" ng-model="q.correct_answer" placeholder="Enter correct answer" ng-required="q.question_type=='single_fill_in_the_blank'"/>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="" ng-show="q.question_type=='true_false'">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <select class="form-control" ng-model="q.correct_answer" ng-required="q.question_type=='true_false'">
                                                    <option value=""><?php echo lang('select_correct_answer');?></option>
                                                    <option value="true"><?php echo lang('true_for_exam');?></option>
                                                    <option value="false"><?php echo lang('flase_for_exam');?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="" ng-show="q.question_type=='double_fill_in_the_blank'">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" name="correct_answer_1" class="form-control" ng-model="q.correct_answer_1" placeholder="<?php echo lang('Enter_correct_answer_1');?>" ng-required="q.question_type=='double_fill_in_the_blank'"/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" name="correct_answer_2" class="form-control" ng-model="q.correct_answer_2" placeholder="<?php echo lang('Enter_correct_answer_2');?>" ng-required="q.question_type=='double_fill_in_the_blank'"/>
                                            </div>
                                        </div>
                                    </div>
​                                       
                                    <div class="" ng-show="q.question_type=='single_answer'">
                                        <div class="col-md-6">
                                            <div class="form-group form-inline">
                                                <label class="control-label col-3 text-center" style="border:1px solid #e3e3e3; border-radius: 5px; line-height: 30px; padding-top: 0px; padding-bottom:5px;">
                                                    <input type="radio" class="check" id="flat-radio-{{key}}-1" name="flat-radio" data-radio="iradio_flat-red" value="a" ng-model="q.correct_answer">
                                                </label>
                                                <div class="col-9 p-r-0"><input type="text" name="option1" class="form-control" ng-model="q.options.option_1" placeholder="<?php echo lang('Enter_correct_answer_2');?>Enter Optoin 1"/></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-inline">
                                                <label class="control-label col-3 text-center" style="border:1px solid #e3e3e3; border-radius: 5px; line-height: 30px; padding-top: 0px; padding-bottom:5px;">
                                                    <input type="radio" class="check" id="flat-radio-{{key}}-2" name="flat-radio" data-radio="iradio_flat-red" value="b" ng-model="q.correct_answer">
                                                </label>
                                                <div class="col-9 p-r-0"><input type="text" name="option2" class="form-control"  ng-model="q.options.option_2" placeholder="<?php echo lang('enter_option_2');?>"/></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-inline">
                                                <label class="control-label col-3 text-center" style="border:1px solid #e3e3e3; border-radius: 5px; line-height: 30px; padding-top: 0px; padding-bottom:5px;">
                                                    <input type="radio" class="check" id="flat-radio-{{key}}-3" name="flat-radio" data-radio="iradio_flat-red" value="c" ng-model="q.correct_answer">
                                                </label>
                                                <div class="col-9 p-r-0"><input type="text" name="option3" class="form-control"  ng-model="q.options.option_3" placeholder="<?php echo lang('enter_option_3');?>"/></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-inline">
                                                <label class="control-label col-3 text-center" style="border:1px solid #e3e3e3; border-radius: 5px; line-height: 30px; padding-top: 0px; padding-bottom:5px;">
                                                    <input type="radio" class="check" id="flat-radio-{{key}}-4" name="flat-radio" data-radio="iradio_flat-red" value="d" ng-model="q.correct_answer">
                                                </label>
                                                <div class="col-9 p-r-0"><input type="text" name="option4" class="form-control"  ng-model="q.options.option_4" placeholder="<?php echo lang('enter_option_4');?>"/></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="" ng-show="q.question_type=='multi_answer'">
                                        <div class="col-md-6">
                                            <div class="form-group form-inline">
                                                <label class="control-label col-3 text-center" style="border:1px solid #e3e3e3; border-radius: 5px; line-height: 30px; padding-top: 0px; padding-bottom:5px;">
                                                    <input type="checkbox" id="flat-radio-{{key}}-1" name="a" value="a" ng-model="q.correct_answer_1" ng-checked="q.correct_answer_1=='a'">
                                                </label>
                                                <div class="col-9 p-r-0"><input type="text" name="option1" class="form-control" ng-model="q.options.option_1" placeholder="<?php echo lang('enter_option_1');?>"/></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-inline">
                                                <label class="control-label col-3 text-center" style="border:1px solid #e3e3e3; border-radius: 5px; line-height: 30px; padding-top: 0px; padding-bottom:5px;">
                                                    <input type="checkbox" id="flat-radio-{{key}}-2" name="b" value="b" ng-model="q.correct_answer_2"  ng-checked="q.correct_answer_2=='b'">
                                                </label>
                                                <div class="col-9 p-r-0"><input type="text" name="option2" class="form-control"  ng-model="q.options.option_2" placeholder="<?php echo lang('enter_option_2');?>"/></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-inline">
                                                <label class="control-label col-3 text-center" style="border:1px solid #e3e3e3; border-radius: 5px; line-height: 30px; padding-top: 0px; padding-bottom:5px;">
                                                    <input type="checkbox" id="flat-radio-{{key}}-3" name="c" value="c" ng-model="q.correct_answer_3" ng-checked="q.correct_answer_3=='c'">
                                                </label>
                                                <div class="col-9 p-r-0"><input type="text" name="option3" class="form-control"  ng-model="q.options.option_3" placeholder="<?php echo lang('enter_option_3');?>"/></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-inline">
                                                <label class="control-label col-3 text-center" style="border:1px solid #e3e3e3; border-radius: 5px; line-height: 30px; padding-top: 0px; padding-bottom:5px;">
                                                    <input type="checkbox" id="flat-radio-{{key}}-4" name="d" value="d" ng-model="q.correct_answer_4" ng-checked="q.correct_answer_4=='d'">
                                                </label>
                                                <div class="col-9 p-r-0"><input type="text" name="option4" class="form-control"  ng-model="q.options.option_4" placeholder="<?php echo lang('enter_option_4');?>"/></div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group form-inline">
                                                <label class="control-label col-3 text-center" style="border:1px solid #e3e3e3; border-radius: 5px; line-height: 30px; padding-top: 0px; padding-bottom:5px;">
                                                    <input type="checkbox" id="flat-radio-{{key}}-5" name="e" value="e" ng-model="q.correct_answer_5" ng-checked="q.correct_answer_5=='e'">
                                                </label>
                                                <div class="col-9 p-r-0"><input type="text" name="option4" class="form-control"  ng-model="q.options.option_5" placeholder="<?php echo lang('enter_option_5');?>"/></div>
                                            </div>
                                        </div>
                                    </div>
​
                                    
                                    <!--<div class="col-md-12">
                                        <div class="form-group">
                                            <label>Correct Answer:</label>
                                            <input type="text" name="correctanswer" class="form-control" ng-model="q.correct_answer" placeholder="Correct Answer" required/>
                                        </div>
                                    </div>-->
​
                                    <div class="col-md-12 m-t-10">
                                        <button type="submit" class="btn btn-primary btn-rounded" ng-if="q.id==null">
                                            <?php echo lang('Save_Question');?>
                                        </button>
                                        <button type="submit" class="btn btn-success btn-rounded" ng-if="q.id!=null">
                                        <?php echo lang('Update_Question');?>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                </fieldset>
                    <!-- div ends here -->
                </div>
            </div>
            <!--./row-->
            <!--page content end here-->
        </div>
    </div>
    <!-- /.container-fluid -->
<?php include(APPPATH . "views/inc/footer.php"); ?>