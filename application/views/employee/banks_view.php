<div class="row">
    <div class="alert" id="bank_delete_alert" style="width:100%;display: none"></div>
    <div class="alert" id="bank_add_alert_outside" style="width:100%;display: none"></div>
    <div class="alert" id="bank_update_alert_outside" style="width:100%;display: none"></div> 
    <div class="table-responsive col-md-12">
        <?php if(count($banks)>0) { ?>
        <table id="myTable" class="table ">
            <thead>
                <tr>
                    <th><?php echo lang('lbl_bank_name') ?></th>
                    <th><?php echo lang('lbl_account_number') ?></th>
                    <th><?php echo lang('lbl_beneficiary_name') ?></th>
                    <th><?php echo lang('lbl_swift') ?></th>
                    <th><?php echo lang('lbl_iban') ?></th>
                    <th><?php echo lang('lbl_primary_bank') ?></th>
                   
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
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } else { ?>
            <span class="text-danger"><?php echo lang('no_record') ?></span>
        <?php }  ?>
    </div>
</div>
<!-- Modal Button -->
