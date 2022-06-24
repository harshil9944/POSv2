<?php
$headers = _get_var('headers',[]);
$rows = _get_var('rows',[]);
$th_styles = 'font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;';
$td_styles = 'font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;';
$p_styles = 'margin-top: 0px; margin-bottom: 20px;font-size:13px;';
?>
<?php _eview('email_templates/components/header'); ?>
<?php if(_get_var('title')){ ?>
<p style="font-size:14px;text-align:center;margin-bottom:5px;"><strong><?php echo _get_var('title'); ?></strong></p>
<?php } ?>
<?php if(_get_var('subtitle')){ ?>
<p style="font-size:12px;text-align:center;"><strong><?php echo _get_var('subtitle'); ?></strong></p>
<?php } ?>
<table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
    <thead>
    <tr>
    <?php foreach($headers as $header){ ?>
        <th style="<?php echo $th_styles; ?>"><?php echo strtoupper($header); ?></th>
    <?php } ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach($rows as $columns){ ?>
        <tr>
            <?php foreach ($columns as $column) { ?>
                <td style="<?php echo $td_styles; ?>"><?php echo $column; ?></td>
            <?php } ?>
        </tr>
    <?php } ?>
    </tbody>
</table>
<?php _eview('email_templates/components/footer'); ?>
