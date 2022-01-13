<?php
$primary_color = _get_setting('primary_color','#222222','pdf');
$table_header_bg = _get_setting('table_header_bg','#EFEFEF','pdf');
$table_border_color = _get_setting('table_border_color','#DDDDDD','pdf');

$obj = _get_var('obj');

$warehouse = $obj['company'];

$company_name = $warehouse['name'];
$company_url = _get_setting('url','https://www.brahmaerp.com/','company');
$company_logo = _get_setting('logo','assets/img/brand-logo.png','company');
$company_email = $warehouse['email'];
$company_phone = $warehouse['phone'];
$company_address_1 = $warehouse['address1'];
$company_address_2 = $warehouse['address2'];
$company_city = $warehouse['city'];
$company_postcode = $warehouse['pincode'];

$address = '';

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
    <title><?php echo $obj['orderNo']; ?></title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">
<div style="width:680px;">
    <div style="width:100%;color:#333;">
        <div style="width:49%;float:left;text-align:left;">
            <p style="<?php echo $p_styles; ?>">
                <strong style="font-size: 14px;"><?php echo $company_name; ?></strong>
                <?php if(trim($company_address_1) && 1==2) { ?>
                    <br/><?php echo $company_address_1; ?>
                <?php } ?>
                <?php if(trim($company_address_2)) { ?>
                    <br/><?php echo $company_address_2; ?>
                <?php } ?>
                <?php if(trim($company_city)) { ?>
                    <br/><?php echo $company_city; ?>
                <?php } ?>
                <?php if(trim($company_postcode)) { ?>
                    <br/><?php echo $company_postcode; ?>
                <?php } ?>
                <?php if(trim($company_email)) { ?>
                    <br/><?php echo $company_email; ?>
                <?php } ?>
                <?php if(trim($company_phone)) { ?>
                    <br/><?php echo $company_phone; ?>
                <?php } ?>
            </p>
        </div>
        <div style="width:49%;float:right;text-align:right;">
            <a href="<?php echo $company_url; ?>" title="<?php echo $company_name; ?>"><img src="<?php _ebase_url($company_logo); ?>" alt="<?php echo $company_name; ?>" style="margin-bottom:20px;border:none;width:300px;" /></a>
        </div>
    </div>
    <div style="width:100%;color:#333;">
        <p style="margin:0;margin-bottom:5px;font-size:14px;text-align:right;text-transform: uppercase;"><strong>Sales Order</strong></p>
    </div>
    <div style="width:100%;padding:10px;border: 1px solid #eee;color:#333;margin-bottom: 20px;">
        <div style="width:49%;float:left;">
            <p style="margin:0;margin-bottom:5px;font-size:12px;">Customer</p>
            <p style="<?php echo $p_styles; ?>">
                <strong style="font-size:14px;"><?php echo $obj['billingName']; ?></strong>
                <?php if(1==2){ ?>
                <?php if($obj['billingAddress1']) { ?>
                    <?php $address .= '<br/>' . $obj['billingAddress1']; ?>
                <?php } ?>
                <?php if($obj['billingAddress2']) { ?>
                    <?php $address .= '<br/>' . $obj['billingAddress2']; ?>
                <?php } ?>
                <?php if($obj['billingCity']) { ?>
                    <?php $address .= '<br/>' . $obj['billingCity']; ?>
                <?php } ?>
                <?php if($obj['billingZipCode']) { ?>
                    <?php $address .= '<br/>' . $obj['billingZipCode']; ?>
                <?php } ?>
                <?php echo $address; ?>
                    <?php if($obj['email']) { ?>
                        <?php if($address){ ?>
                            <br/>
                        <?php } ?>
                        <br/><?php echo $obj['email']; ?>
                    <?php } ?>
                    <?php if($obj['phone']) { ?>
                        <br/><?php echo $obj['phone']; ?>
                    <?php } ?>
                <?php } ?>
            </p>
        </div>
        <div style="width:49%;float:right;color:#333;">
            <table style="border-collapse: collapse; width: 100%; border-top: 1px solid <?php echo $table_border_color; ?>; border-left: 1px solid <?php echo $table_border_color; ?>; margin-bottom: 20px;">
                <tbody>
                    <tr>
                        <th style="<?php echo $th_styles; ?>">Order no</th>
                        <td style="<?php echo $td_styles_right; ?>"><?php echo $obj['orderNo']; ?></td>
                    </tr>
                    <?php if($obj['referenceNo']){ ?>
                    <tr>
                        <th style="<?php echo $th_styles; ?>">Reference no</th>
                        <td style="<?php echo $td_styles_right; ?>"><?php echo $obj['referenceNo']; ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <th style="<?php echo $th_styles; ?>">Order Date</th>
                        <td style="<?php echo $td_styles_right; ?>"><?php echo custom_date_format($obj['date'],'d/m/Y'); ?></td>
                    </tr>
                    <tr>
                        <th style="<?php echo $th_styles; ?>">Expected Delivery</th>
                        <td style="<?php echo $td_styles_right; ?>"><?php echo custom_date_format($obj['expectedDeliveryDate'],'d/m/Y'); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <table style="border-collapse: collapse; width: 100%; border-top: 1px solid <?php echo $table_border_color; ?>; border-left: 1px solid <?php echo $table_border_color; ?>; margin-bottom: 20px;">
        <thead>
        <tr>
            <td style="<?php echo $th_styles; ?>">Description</td>
            <td style="<?php echo $th_styles_center; ?>">Quantity</td>
            <td style="<?php echo $th_styles_right; ?>">Unit Price</td>
            <td style="<?php echo $th_styles_right; ?>">Amount</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach($obj['items'] as $single){ ?>
            <tr>
                <td style="<?php echo $td_styles; ?>"><?php echo $single['title']; ?></td>
                <td style="<?php echo $td_styles_center; ?>"><?php echo round($single['quantity'],0); ?></td>
                <td style="<?php echo $td_styles_right; ?>"><?php echo custom_money_format(round($single['rate'],2),_get_setting('currency_sign','')); ?></td>
                <td style="<?php echo $td_styles_right; ?>"><?php echo custom_money_format(round($single['amount'],2),_get_setting('currency_sign','')); ?></td>
            </tr>
        <?php } ?>
        </tbody>
        <tfoot>
        <tr>
            <th colspan="3" style="<?php echo $th_styles_right; ?>">Sub Total</th>
            <td style="<?php echo $td_styles_right; ?>"><?php echo custom_money_format(round($obj['subTotal'],2),_get_setting('currency_sign','')); ?></td>
        </tr>
        <tr>
            <th colspan="3" style="<?php echo $th_styles_right; ?>">Tax (<?php echo $obj['taxRate']; ?>%)</th>
            <td style="<?php echo $td_styles_right; ?>"><?php echo custom_money_format(round($obj['taxTotal'],2),_get_setting('currency_sign','')); ?></td>
        </tr>
        <tr>
            <th colspan="3" style="<?php echo $th_styles_right; ?>">Overheads</th>
            <td style="<?php echo $td_styles_right; ?>"><?php echo custom_money_format(round((float)$obj['freightTotal'] + (float)$obj['dutyTotal'],2),_get_setting('currency_sign','')); ?></td>
        </tr>
        <tr>
            <th colspan="3" style="<?php echo $th_styles_right; ?>">Discount</th>
            <td style="<?php echo $td_styles_right; ?>"><?php echo custom_money_format(round($obj['discount'],2),_get_setting('currency_sign','')); ?></td>
        </tr>
        <tr>
            <th colspan="3" style="<?php echo $th_styles_right; ?>">Grand Total</th>
            <td style="<?php echo $td_styles_right; ?>"><?php echo custom_money_format(round($obj['grandTotal'],2),_get_setting('currency_sign','')); ?></td>
        </tr>
        </tfoot>
    </table>
</div>
</body>
</html>
