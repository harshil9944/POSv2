<?php
$primary_color = _get_setting('primary_color','#222222','pdf');
$table_header_bg = _get_setting('table_header_bg','#EFEFEF','pdf');
$table_border_color = _get_setting('table_border_color','#DDDDDD','pdf');

$obj = _get_var('obj');

$th_styles = 'font-size: 14px; border-right: 1px solid '.$table_border_color.'; border-bottom: 1px solid '.$table_border_color.'; background-color: '.$table_header_bg.'; font-weight: bold; text-align: left; padding: 7px; color: '.$primary_color.';';
$th_styles_center = 'font-size: 14px; border-right: 1px solid '.$table_border_color.'; border-bottom: 1px solid '.$table_border_color.'; background-color: '.$table_header_bg.'; font-weight: bold; text-align: center; padding: 7px; color: '.$primary_color.';';
$th_styles_right = 'font-size: 14px; border-right: 1px solid '.$table_border_color.'; border-bottom: 1px solid '.$table_border_color.'; background-color: '.$table_header_bg.'; font-weight: bold; text-align: right; padding: 7px; color: '.$primary_color.';';
$td_styles = 'font-size: 14px;	border-right: 1px solid '.$table_border_color.'; border-bottom: 1px solid '.$table_border_color.'; text-align: left; padding: 7px;';
$td_styles_center = 'font-size: 14px; border-right: 1px solid '.$table_border_color.'; border-bottom: 1px solid '.$table_border_color.'; text-align: center; padding: 7px;';
$td_styles_right = 'font-size: 14px; border-right: 1px solid '.$table_border_color.'; border-bottom: 1px solid '.$table_border_color.'; text-align: right; padding: 7px;';
$p_styles = 'margin-top: 0px; margin-bottom: 20px;font-size:13px;';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>POS Sessions Report</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">
<div style="width:680px;">
    <div style="width:100%;color:#333;">
        <p style="margin:0;margin-bottom:5px;font-size:14px;text-align:right;text-transform: uppercase;"><strong>POS Shift Report</strong></p>
    </div>
    <table style="border-collapse: collapse; width: 100%; border-top: 1px solid <?php echo $table_border_color; ?>; border-left: 1px solid <?php echo $table_border_color; ?>; margin-bottom: 20px;">
        <thead>
        <tr>
            <td style="<?php echo $th_styles; ?>">Employee</td>
            <td style="<?php echo $th_styles_center; ?>">Orders</td>
            <td style="<?php echo $th_styles_center; ?>">Shift Start</td>
            <td style="<?php echo $th_styles_center; ?>">Shift End</td>
            <td style="<?php echo $th_styles_center; ?>">Status</td>
            <td style="<?php echo $th_styles_center; ?>">Tip</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach($obj as $single){ ?>
            <tr>
                <td style="<?php echo $td_styles; ?>"><?php echo $single['empName']; ?></td>
                <td style="<?php echo $td_styles_center; ?>"><?php echo $single['totalOrder']; ?></td>
                <td style="<?php echo $td_styles_center; ?>"><?php echo custom_date_format($single['startShift']); ?></td>
                <td style="<?php echo $td_styles_center; ?>"><?php echo $single['endShift'] ? custom_date_format($single['endShift']):''; ?></td>
                <td style="<?php echo $td_styles_center; ?>"><?php echo $single['status']; ?></td>
                <td style="<?php echo $td_styles_center; ?>"><?php echo $single['tip']?$single['tip']:0; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
