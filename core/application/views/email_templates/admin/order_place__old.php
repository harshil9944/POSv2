<?php
$details = _get_var('details');
$th_styles = 'font-size: 14px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;';
$td_styles = 'font-size: 14px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;';
$p_styles = 'margin-top: 0px; margin-bottom: 20px;font-size:14px;';
?>
<?php _eview('email_templates/components/header'); ?>
<p style="margin-top: 10px; margin-bottom: 20px;">Hi <strong><?php echo $details['full_name']; ?></strong>,</p>
<p style="margin-top: 10px; margin-bottom: 20px;"><?php echo $details['default_message']; ?></p>
<p style="margin-top:30px;margin-bottom:20px;"><strong>Thanks,</strong><br/><strong><?php echo $details['company_name']; ?></strong></p>
<?php _eview('email_templates/components/footer'); ?>
