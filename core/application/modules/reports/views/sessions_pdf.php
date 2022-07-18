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
        <p style="margin:0;margin-bottom:5px;font-size:14px;text-align:right;text-transform: uppercase;"><strong>POS Sessions Report</strong></p>
    </div>
    <table style="border-collapse: collapse; width: 100%; border-top: 1px solid <?php echo $table_border_color; ?>; border-left: 1px solid <?php echo $table_border_color; ?>; margin-bottom: 20px;">
        <thead>
        <tr>
            <td style="<?php echo $th_styles; ?>">Status</td>
            <td style="<?php echo $th_styles; ?>">Opened on</td>
            <td style="<?php echo $th_styles; ?>">Open by</td>
            <td style="<?php echo $th_styles_right; ?>">Opening Cash</td>
            <td style="<?php echo $th_styles_right; ?>">Orders</td>
            <td style="<?php echo $th_styles_right; ?>">Tax</td>
            <td style="<?php echo $th_styles_right; ?>">Total</td>
            <td style="<?php echo $th_styles_right; ?>">Discount</td>
            <td style="<?php echo $th_styles_right; ?>">Change</td>
            <td style="<?php echo $th_styles_right; ?>">Tip</td>
            <td style="<?php echo $th_styles_right; ?>">Closing Cash</td>
            <td style="<?php echo $th_styles; ?>">Closed on</td>
            <td style="<?php echo $th_styles; ?>">Closed by</td>
        </tr>
        </thead>
        <tbody>
            <?php foreach($obj as $single){ ?>
                <tr>
                    <td style="<?php echo $td_styles; ?>"><?php echo $single['status']; ?></td>
                    <td style="<?php echo $td_styles; ?>"><?php echo custom_date_format($single['openingDate']); ?></td>
                    <td style="<?php echo $td_styles; ?>"><?php echo $single['openingEmployee']; ?></td>
                    <td style="<?php echo $td_styles_right; ?>"><?php echo $single['openingCash']?$single['openingCash']:0; ?></td>
                    <td style="<?php echo $td_styles_right; ?>"><?php echo $single['ordersCount']?$single['ordersCount']:0; ?></td>
                    <td style="<?php echo $td_styles_right; ?>"><?php echo $single['taxTotal']?$single['taxTotal']:0; ?></td>
                    <td style="<?php echo $td_styles_right; ?>"><?php echo $single['transactionsTotal']?$single['transactionsTotal']:0; ?></td>
                    <td style="<?php echo $td_styles_right; ?>"><?php echo $single['discountTotal']?$single['discountTotal']:0; ?></td>
                    <td style="<?php echo $td_styles_right; ?>"><?php echo $single['changeTotal']?$single['changeTotal']:0; ?></td>
                    <td style="<?php echo $td_styles_right; ?>"><?php echo $single['tipTotal']?$single['tipTotal']:0; ?></td>
                    <td style="<?php echo $td_styles_right; ?>"><?php echo $single['expectedClosingCash']?$single['expectedClosingCash']:0; ?></td>
                    <td style="<?php echo $td_styles; ?>"><?php echo ($single['status']==='Close')?custom_date_format($single['closingDate']):''; ?></td>
                    <td style="<?php echo $td_styles; ?>"><?php echo $single['closingEmployee']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
