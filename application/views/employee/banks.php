<div class="row">
    <div class="alert" id="bank_delete_alert" style="width:100%;display: none"></div>
    <div class="alert" id="bank_add_alert_outside" style="width:100%;display: none"></div>
    <div class="alert" id="bank_update_alert_outside" style="width:100%;display: none"></div> 
    <div class="table-responsive col-md-12">
        <table id="myTable" class="table ">
            <thead>
                <tr>
                    <th><?php echo lang('lbl_bank_name') ?></th>
                    <th><?php echo lang('lbl_account_number') ?></th>
                    <th><?php echo lang('lbl_beneficiary_name') ?></th>
                    <th><?php echo lang('lbl_swift') ?></th>
                    <th><?php echo lang('lbl_iban') ?></th>
                    <th><?php echo lang('lbl_primary_bank') ?></th>
                    <th class="text-right "><?php echo lang('lbl_action_bank') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($banks as $bank) { ?>
                    <tr>
                        <td><?php echo $bank->bank_name ?></td>
                        <td><?php echo $bank->account_number ?></td>
                        <td><?php echo $bank->beneficiary_name ?></td>
                        <td><?php echo $bank->swift_code ?></td>
                        <td><?php echo $bank->iban_code ?></td>
                        <td <?php if ($bank->is_primary == "Y") echo "class='text-center'" ?>><?php
                            if ($bank->is_primary == "Y")
                                echo "<i class='fa fa-check' id='primary'></i>";
                            else
                                echo "<i class='fa' id='primary'";
                            ?></td>
                        <td class="text-right ">
                            <button type="button" data-toggle="modal" data-target="#editBankModal" class="btn btn-info btn-circle editBank"  id="<?php echo $bank->id ?>"><i
                                    class="fa fa-pencil"></i></button>
                            <button type="button" class="btn btn-danger btn-circle deleteBank" data-toggle="modal" data-target="#deleteBankModal" id="<?php echo $bank->id ?>"><i
                                    class="fa  fa-trash-o"></i></button>
                        </td>
                    </tr>
<?php } ?>
            </tbody>
        </table>
    </div>
</div>
<!-- Modal Button -->
<div >
    <!--/row-->
    <button type="button" data-toggle="modal" data-target="#addBank"
            class="btn btn-primary pull-right" id="add_bank_btn"><?php echo lang('lbl_add_new_bank') ?>
    </button>
    <div class="clear"></div>
    <!-- /Modal Button -->

</div>
<!-- Modal Content -->
<div class="modal fade" id="addBank" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="bank_modal_content">


            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo lang('lbl_add_new_bank') ?></div>
                <div class="panel-body">
                    <div class="alert" id="bank_add_alert" style="display: none"></div>
                    <form class="form-material" id="newbankform">
                        <div class="form-body">

                            <div class="row">
                                <div class="col-md-6">
                                    <input type="hidden" name="emp_id" value="<?php echo $employee->id ?>" >
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('lbl_bank_name') ?>*</label>
                                        <input type="text" name="name" id="name"
                                               class="form-control "
                                               ></div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('lbl_account_number') ?>*</label>
                                        <input type="text" name="accountno" id="accountno"
                                               class="form-control"
                                               ></div>
                                </div>
                                <!--/span-->
                            </div>
                            <!--/row-->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('lbl_beneficiary_name') ?>*
                                        </label>
                                        <input type="text" name="beneficiary" id="beneficiary"
                                               class="form-control"
                                               ></div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('lbl_swift') ?></label>
                                        <input type="text" name="swift" id="swift" class="form-control">
                                    </div>
                                </div>
                                <!--/span-->

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label "><?php echo lang('lbl_iban') ?></label>
                                        <input type="text" name="iban" id="iban" class="form-control">
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label "><?php echo lang('lbl_primary_bank') ?></label><br>
                                        <input type="checkbox" name="primary_bank" id="primary_bank" data-size="normal">
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                        </div>
                        <div class="row pull-right">
                            <div style="margin-right: 8px">
                                <button type="button" class="btn btn-default"
                                        data-dismiss="modal" id="close_add_bank"><?php echo lang('btn_close_bank') ?>
                                </button>
                            </div>
                            <div>
                                <button type="button" id="addnewbank" class="btn btn-primary"><?php echo lang('btn_save_bank') ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/Modal end here-->

<!-- Edit Bank Modal  -->
<div class="modal fade" id="editBankModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        
        <div class="modal-content" id="edit_modal_content">


            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo lang('lbl_edit_bank') ?></div>
                <div class="panel-body">
                    <div class="alert" id="bank_edit_alert" style="display: none"></div>
                    <form class="form-material" id="editbankform">
                        <div class="form-body">

                            <div class="row">
                                <div class="col-md-6">
                                    <input type="hidden" name="bank_edit_id" id="bank_edit_id">

                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('lbl_bank_name') ?>*</label>
                                        <input type="text" name="name" id="editname"
                                               class="form-control "
                                               ></div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('lbl_account_number') ?>*</label>
                                        <input type="text" name="accountno" id="editaccountno"
                                               class="form-control"
                                               ></div>
                                </div>
                                <!--/span-->
                            </div>
                            <!--/row-->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('lbl_beneficiary_name') ?>*
                                        </label>
                                        <input type="text" name="beneficiary" id="editbeneficiary"
                                               class="form-control"
                                               ></div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo lang('lbl_swift') ?></label>
                                        <input type="text" name="swift" id="editswift" class="form-control">
                                    </div>
                                </div>
                                <!--/span-->

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label "><?php echo lang('lbl_iban') ?></label>
                                        <input type="text" name="iban" id="editiban" class="form-control">
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label "><?php echo lang('lbl_primary_bank') ?></label><br>
                                        <input type="checkbox" name="primary_bank" id="editprimary_bank" data-size="normal">
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                        </div>
                        <div class="row pull-right">
                            <div style="margin-right: 8px">
                                <button type="button" class="btn btn-default"
                                        data-dismiss="modal" id="close_edit_bank"><?php echo lang('btn_close_bank') ?>
                                </button>
                            </div>
                            <div>
                                <button type="button" id="updatebank" class="btn btn-primary"><?php echo lang('btn_update_bank') ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- // Edit Bank Modal -->

<!--  Confirmation modal  -->

<div id="deleteBankModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo lang('lbl_delete_confirmation') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <p><?php echo lang('delete_confirmation_message') ?></p>

                <input type="hidden" id="bank_delete_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><?php echo lang('btn_close_bank') ?></button>
                <button type="button" class="btn btn-danger waves-effect waves-light" id="confirm_delete"><?php echo lang('btn_delete_bank') ?></button>
            </div>
        </div>
    </div>
</div>
<!-- // Confirmation modal  -->