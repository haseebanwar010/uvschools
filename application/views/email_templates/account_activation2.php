<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Account Activation</title>
</head>
<body style="margin:0px; background: #f8f8f8; <?php if ($this->session->userdata('site_lang') == "english" || !$this->session->userdata('site_lang')) {
    echo 'dir:ltr';
} else {
    echo 'dir:rtl';
} ?>">
<div style="background: #f8f8f8; padding: 0px 0px; font-family:arial; line-height:28px; height:100%;  width: 100%; color: #514d6a;">
    <div style="max-width: 700px; padding:50px 0;  margin: 0px auto; font-size: 14px">
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
            <tbody>
                <tr>
                    <td style="vertical-align: top; padding-bottom:30px;" align="center">
                        <img src="<?php echo base_url(); ?>assets/plugins/images/eliteadmin-logo-dark.png" alt="UV Schools" style="border:none; width: 150px;" /><br />
                        
                        
                    </td>
                </tr>
            </tbody>
        </table>
        <div style="padding: 40px; background: #fff;">
            <div style="text-align: center;">
                <img src="<?php echo base_url(); ?>uploads/logos/<?php echo $this->session->userdata('userdata')['sh_logo'];?>" alt="School Logo" style="border:none; height: 50px;" /><h1><?php echo $this->session->userdata('userdata')['sh_name'];?></h1>
            </div>
            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                <tbody>
                    <tr>
                        <td><b><?php echo $dear_sir; ?></b>
                            <p><?php echo $msg; ?></p>
                            <p>Please activate your account by using credentials below:</p>
                            <p><label>Your Email:</label> <span><?php echo $email; ?></span></p>
                            <p><label>Your Password:</label> <span><?php echo $password; ?></span></p>
                            <a href="<?php echo $link ?>" style="display: inline-block; padding: 11px 30px; margin: 20px 0px 30px; font-size: 15px; color: #fff; background: #00c0c8; border-radius: 60px; text-decoration:none;"> <?php echo lang('tmp_activate'); ?> </a>

                            <br/><br/><b><?php echo $thanks; ?></b> </td>
                        </tr>

                    </tbody>
                </table>
            </div>
            <div style="text-align: center; font-size: 12px; color: #b2b2b5; margin-top: 20px">
                <p> <?php echo $poweredBy; ?> <br />
                    <a href="javascript: void(0);" style="color: #b2b2b5; text-decoration: underline;"><?php echo $unsub; ?></a> </p>
                </div>
            </div>
        </div>
    </body>
    </html>
