<?php
$details = _get_var('details');
$th_styles = 'font-size: 14px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;';
$td_styles = 'font-size: 14px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;';
$p_styles = 'margin-top: 0px; margin-bottom: 20px;font-size:14px;';
?>
<?php _eview('email_templates/components/header'); ?>
<p style="margin-top: 10px; margin-bottom: 20px;">Hi <strong><?php echo $details['company']; ?></strong>,</p>
<p style="margin-top: 10px; margin-bottom: 20px;">You have new inquiry for booking</p>
<p style="margin-top: 10px; margin-bottom: 20px;">Following are your details:<br/>
    <strong>Name :</strong> <?php echo $details['name']; ?><br/>
    <strong>Email :</strong> <?php echo $details['email']; ?><br/>
    <strong>Phone :</strong> <?php echo $details['phone']; ?><br/>
    <strong>Number Of Person :</strong> <?php echo $details['number_of_person']; ?><br/>
    <strong>Date :</strong> <?php echo $details['date']; ?><br/>
    <strong>Description :</strong> <?php echo $details['description']; ?><br/>
    <strong>Menu :</strong> <?php echo $details['menu']; ?><br/>
    <strong>Remark :</strong> <?php echo $details['remark']; ?><br/>
<p style="margin-top:30px;margin-bottom:20px;"><strong>Thanks,</strong><br/><strong><?php echo $details['company']; ?></strong></p>
<?php _eview('email_templates/components/footer'); ?>
