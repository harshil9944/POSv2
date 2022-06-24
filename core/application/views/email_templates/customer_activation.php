<?php
$details = _get_var('details');
$th_styles = 'font-size: 14px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;';
$td_styles = 'font-size: 14px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;';
$p_styles = 'margin-top: 0px; margin-bottom: 20px;font-size:14px;';
?>
<?php _eview('email_templates/components/header'); ?>
<p style="margin-top: 10px; margin-bottom: 20px;">Hi <strong><?php echo $details['full_name']; ?></strong>,</p>
<p style="margin-top: 10px; margin-bottom: 20px;">You have successfully created your <?php echo $details['company']; ?> account.</p>
<p style="margin-top: 10px; margin-bottom: 20px;">Congratulations and welcome to <?php echo $details['company']; ?>!</p>
<p style="margin-top: 10px; margin-bottom: 20px;">Following are your details:<br/>
    <strong>Your login id is:</strong> <?php echo $details['email']; ?><br/>
    <?php if($details['mobile']) { ?><strong>Your Mobile No is:</strong> <?php echo $details['mobile']; ?><?php } ?></p>
<p style="margin-top: 10px; margin-bottom: 20px;">You can check and update the details of your Email ID and other details anytime in the Account.</p>
<p style="margin-top:30px;margin-bottom:20px;"><strong>Thanks,</strong><br/><strong><?php echo $details['company']; ?></strong></p>
<?php _eview('email_templates/components/footer'); ?>
