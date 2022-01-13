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
    <title>Sales Report</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">
<div style="width:680px;">
    <div style="width:100%;color:#333;">
        <p style="margin:0;margin-bottom:5px;font-size:14px;text-align:right;text-transform: uppercase;"><strong>Sales Report</strong></p>
    </div>
    <table style="border-collapse: collapse; width: 100%; border-top: 1px solid <?php echo $table_border_color; ?>; border-left: 1px solid <?php echo $table_border_color; ?>; margin-bottom: 20px;">
        <thead>
        <tr>
            <td style="<?php echo $th_styles; ?>">Order Date</td>
            <td style="<?php echo $th_styles_right; ?>">Total Orders</td>
            <td style="<?php echo $th_styles_right; ?>">Sub Total</td>
            <td style="<?php echo $th_styles_right; ?>">Discount</td>
            <td style="<?php echo $th_styles_right; ?>">Total Tax</td>
            <td style="<?php echo $th_styles_right; ?>">Total Amount</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach($obj as $single){ ?>
            <tr>
                <td style="<?php echo $td_styles; ?>"><?php echo custom_date_format($single['orderDate'],'d/m/Y'); ?></td>
                <td style="<?php echo $td_styles_right; ?>"><?php echo $single['totalOrders']; ?></td>
                <td style="<?php echo $td_styles_right; ?>"><?php echo $single['subTotal']; ?></td>
                <td style="<?php echo $td_styles_right; ?>"><?php echo $single['discount']; ?></td>
                <td style="<?php echo $td_styles_right; ?>"><?php echo $single['totalTax']; ?></td>
                <td style="<?php echo $td_styles_right; ?>"><?php echo $single['totalAmount']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
        <?php
        $total_orders = array_sum(array_map(function($item) {
            return $item['totalOrders'];
        }, $obj));
        $sub_total = array_sum(array_map(function($item) {
            return $item['subTotal'];
        }, $obj));
        $discount = array_sum(array_map(function($item) {
            return $item['discount'];
        }, $obj));
        $total_tax = array_sum(array_map(function($item) {
            return $item['totalTax'];
        }, $obj));
        $total_amount = array_sum(array_map(function($item) {
            return $item['totalAmount'];
        }, $obj));
        ?>
        <tfoot>
        <tr>
            <td style="<?php echo $th_styles_right; ?>">Total</td>
            <td style="<?php echo $th_styles_right; ?>"><?php echo $total_orders; ?></td>
            <td style="<?php echo $th_styles_right; ?>"><?php echo $sub_total; ?></td>
            <td style="<?php echo $th_styles_right; ?>"><?php echo $discount; ?></td>
            <td style="<?php echo $th_styles_right; ?>"><?php echo $total_tax; ?></td>
            <td style="<?php echo $th_styles_right; ?>"><?php echo $total_amount; ?></td>
        </tr>
        </tfoot>
    </table>
</div>
</body>
</html>
