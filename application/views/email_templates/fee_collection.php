<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="viewport" content="width=device-width" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Account Activation</title>
    </head>
    <body style="margin:0px; background: #f8f8f8; <?php if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {
    echo 'dir="ltr"';
} else {
    echo 'dir="rtl"';
} ?>">
        <div width="100%" style="background: #f8f8f8; padding: 0px 0px; font-family:arial; line-height:28px; height:100%;  width: 100%; color: #514d6a;">
            <div style="max-width: 700px; padding:50px 0;  margin: 0px auto; font-size: 14px">
                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
                    <tbody>
                        <tr>
                            <td style="vertical-align: top; padding-bottom:30px;" align="center">
                                <img src="<?php echo base_url(); ?>assets/plugins/images/eliteadmin-logo-dark.png" alt="MySchool" style="border:none; width:150px;" /><br/>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div style="padding: 40px; background: #fff;">
                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                        <tbody>
                            <tr>
                                <td><b><?php echo $dear_sir; ?></b>
                                    <p><?php echo $msg; ?></p>
                                    <table class="table table-bordered table-striped" style="text-align:left;">
                                        <tr><th style='width:200px;'><?php echo lang('lbl_name') ?></th><td><?= $stdInfo->name ?></td></tr>
                                        <tr><th><?php echo lang('lbl_class') ?></th><td><?= $stdclass ?></td></tr>
                                        <tr><th><?php echo lang('lbl_batch') ?></th><td><?= $stdbatch ?></td></tr>
                                        <tr><th><?php echo lang('father_name') ?></th><td><?= $fathername ?></td></tr>
                                        <tr><th><?php echo lang('fee_type') ?></th><td><?= $feetype ?></td></tr>
                                        <tr><th><?php echo lang('paid_date') ?></th><td><?= date("d/m/Y") ?></td></tr>
                                        <tr><th><?php echo lang('amount') ?></th><td><?= $amount ?></td></tr>
                                        <tr><th><?php echo lang('discount_amount') ?></th><td><?= $discountamount ?></td></tr>
                                        <tr><th><?php echo lang('collected_by') ?></th><td><?= $collectorname ?></td></tr>
                                    </table>
                                    <br/><br/><b><?php echo $thanks; ?></b> </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="text-align: center; font-size: 12px; color: #b2b2b5; margin-top: 20px">
                    <p> <?php echo $poweredBy; ?> <br>
                            <a href="javascript: void(0);" style="color: #b2b2b5; text-decoration: underline;"><?php echo $unsub; ?></a> </p>
                </div>
            </div>
        </div>
    </body>
</html>
