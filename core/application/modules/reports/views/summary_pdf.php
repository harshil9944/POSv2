<?php
$primary_color = _get_setting('primary_color','#222222','pdf');
$table_header_bg = _get_setting('table_header_bg','#EFEFEF','pdf');
$table_border_color = _get_setting('table_border_color','#DDDDDD','pdf');

$obj = _get_var('summary');

$th_styles = 'font-size: 14px; border-right: 1px solid '.$table_border_color.'; border-bottom: 1px solid '.$table_border_color.'; background-color: '.$table_header_bg.'; font-weight: bold; text-align: left; padding: 7px; color: '.$primary_color.';';
$th_styles_center = 'font-size: 14px; border-right: 1px solid '.$table_border_color.'; border-bottom: 1px solid '.$table_border_color.'; background-color: '.$table_header_bg.'; font-weight: bold; text-align: center; padding: 7px; color: '.$primary_color.';';
$th_styles_right = 'font-size: 14px; border-right: 1px solid '.$table_border_color.'; border-bottom: 1px solid '.$table_border_color.'; background-color: '.$table_header_bg.'; font-weight: bold; text-align: right; padding: 7px; color: '.$primary_color.';';
$td_styles = 'font-size: 14px;	border-right: 1px solid '.$table_border_color.'; border-bottom: 1px solid '.$table_border_color.'; background-color: '.$table_header_bg.'; text-align: left; padding: 7px; color:'.$primary_color.';';
$td_styles_center = 'font-size: 14px; border-right: 1px solid '.$table_border_color.'; border-bottom: 1px solid '.$table_border_color.'; text-align: center; padding: 7px;';
$td_styles_right = 'font-size: 14px; border-right: 1px solid '.$table_border_color.'; border-bottom: 1px solid '.$table_border_color.'; text-align: right; padding: 7px;';
$td_styles_left = 'font-size: 14px; border-right: 1px solid '.$table_border_color.'; border-bottom: 1px solid '.$table_border_color.'; text-align: left; padding: 7px;';
$p_styles = 'margin-top: 0px; margin-bottom: 20px;font-size:13px;';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Summary</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">
<div style="width:680px;">
    <div style="width:100%;color:#333;">
        <div style="width:49%;float:left;color:#333;">
            <div style="width:100%;color:#333;">
                <p style="margin:0;margin-bottom:5px;font-size:14px;text-align:left;"><strong>Session Details</strong></p>
            </div>
            <table style="border-collapse: collapse; width: 100%; border-top: 1px solid <?php echo $table_border_color; ?>; border-left: 1px solid <?php echo $table_border_color; ?>; margin-bottom: 20px;">
                <tbody>
                    <tr>
                        <td style="<?php echo $td_styles; ?>">Opened</td>
                        <td style="<?php echo $td_styles_left; ?>"><?php echo custom_date_format($obj['openingDate']); ?></td>
                    </tr>
                    <tr>
                        <td style="<?php echo $td_styles; ?>">By employee</td>
                        <td style="<?php echo $td_styles_left; ?>"><?php echo $obj['openingEmployee']; ?></td>
                    </tr>
                    <tr>
                        <td style="<?php echo $td_styles; ?>">Closed</td>
                        <td style="<?php echo $td_styles_left; ?>"><?php echo $obj['closingDate'] != null ? custom_date_format($obj['closingDate']) : ''; ?></td>
                    </tr>
                    <tr>
                        <td style="<?php echo $td_styles; ?>">By employee</td>
                        <td style="<?php echo $td_styles_left; ?>"><?php echo $obj['closingEmployee']; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="width:49%;float:right;color:#333;">
            <div style="width:100%;color:#333;">
                <p style="margin:0;margin-bottom:5px;font-size:14px;text-align:left;"><strong>Total Orders</strong></p>
            </div>
            <table style="border-collapse: collapse; width: 100%; border-top: 1px solid <?php echo $table_border_color; ?>; border-left: 1px solid <?php echo $table_border_color; ?>; margin-bottom: 20px;">
                <tbody>
                    <tr>
                        <td style="<?php echo $td_styles; ?>">Orders Placed</td>
                        <td style="<?php echo $td_styles_center; ?>"><?php echo $obj['ordersCount']; ?></td>
                    </tr>
                    <tr>
                        <td style="<?php echo $td_styles; ?>">Orders Cancelled</td>
                        <td style="<?php echo $td_styles_center; ?>"><?php echo $obj['cancelledOrdersCount']; ?></td>
                    </tr>
                    <? if($obj['enableRefunded']){ ?>
                    <tr>
                        <td style="<?php echo $td_styles; ?>">Refunded Orders</td>
                        <td style="<?php echo $td_styles_center; ?>"><?php echo $obj['refundedOrdersCount']; ?></td>
                    </tr>
                    <? } ?>
                    <? if( $obj['openOrdersCount'] > 0){ ?>
                    <tr>
                        <td style="<?php echo $td_styles; ?>">Open Orders</td>
                        <td style="<?php echo $td_styles_center; ?>"><?php echo $obj['openOrdersCount']; ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div style="width:100%;color:#333;">
        <div style="width:49%;float:left;color:#333;">
            <div style="width:100%;color:#333;">
                <p style="margin:0;margin-bottom:5px;font-size:14px;text-align:left;"><strong>Specific Orders</strong></p>
            </div>
            <table style="border-collapse: collapse; width: 100%; border-top: 1px solid <?php echo $table_border_color; ?>; border-left: 1px solid <?php echo $table_border_color; ?>; margin-bottom: 20px;">
                <tbody>
                <?php foreach($obj['source'] as $single){ ?>
                    <tr>
                        <td style="<?php echo $td_styles; ?>"><?php echo $single['label']; ?></td>
                        <td style="<?php echo $td_styles_center; ?>"><?php echo $single['order']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <div style="width:49%;float:right;color:#333;">
            <div style="width:100%;color:#333;">
                <p style="margin:0;margin-bottom:5px;font-size:14px;text-align:left;"><strong>Specific Amounts</strong></p>
            </div>
            <table style="border-collapse: collapse; width: 100%; border-top: 1px solid <?php echo $table_border_color; ?>; border-left: 1px solid <?php echo $table_border_color; ?>; margin-bottom: 20px;">
                <tbody>
                <?php foreach($obj['source'] as $single){ ?>
                    <tr>
                        <td style="<?php echo $td_styles; ?>"><?php echo $single['label']; ?></td>
                        <td style="<?php echo $td_styles_right; ?>"><?php echo _get_setting('currency_sign',''),dsRound($single['amount'],2);?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div style="width:100%;color:#333;">
        <div style="width:49%;float:left;color:#333;">
            <div style="width:100%;color:#333;">
                <p style="margin:0;margin-bottom:5px;font-size:14px;text-align:left;"><strong>Total Amounts</strong></p>
            </div>
            <table style="border-collapse: collapse; width: 100%; border-top: 1px solid <?php echo $table_border_color; ?>; border-left: 1px solid <?php echo $table_border_color; ?>; margin-bottom: 20px;">
                <tbody>
                    <tr>
                        <td style="<?php echo $td_styles; ?>">Opening Amount</td>
                        <td style="<?php echo $td_styles_right; ?>"><?php echo _get_setting('currency_sign',''),dsRound($obj['openingCash'],2); ?></td>
                    </tr>
                    <tr>
                        <td style="<?php echo $td_styles; ?>">Discount</td>
                        <td style="<?php echo $td_styles_right; ?>"><?php echo _get_setting('currency_sign',''),dsRound($obj['discountTotal'],2); ?></td>
                    </tr>
                    <tr>
                        <td style="<?php echo $td_styles; ?>">Change Given</td>
                        <td style="<?php echo $td_styles_right; ?>"><?php echo _get_setting('currency_sign',''),dsRound($obj['changeTotal'],2); ?></td>
                    </tr>
                    <tr>
                        <td style="<?php echo $td_styles; ?>">Total Tip</td>
                        <td style="<?php echo $td_styles_right; ?>"><?php echo _get_setting('currency_sign',''),dsRound($obj['tipTotal'],2); ?></td>
                    </tr>
                    <tr>
                        <td style="<?php echo $td_styles; ?>">Total Tax</td>
                        <td style="<?php echo $td_styles_right; ?>"><?php echo _get_setting('currency_sign',''),dsRound($obj['taxTotal'],2); ?></td>
                    </tr>
                    <?php if($obj['allowGratuity']){ ?>
                    <tr>
                        <td style="<?php echo $td_styles; ?>">Total Gratuity</td>
                        <td style="<?php echo $td_styles_right; ?>"><?php echo _get_setting('currency_sign',''),dsRound($obj['gratuityTotal'],2); ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td style="<?php echo $td_styles; ?>">Overall Cancelled Orders Amount</td>
                        <td style="<?php echo $td_styles_right; ?>"><?php echo _get_setting('currency_sign',''),dsRound($obj['cancelledTransactionsTotal'],2); ?></td>
                    </tr>
                    <? if($obj['enableRefunded']){?>
                    <tr>
                        <td style="<?php echo $td_styles; ?>">Overall Refunded Orders Amount</td>
                        <td style="<?php echo $td_styles_right; ?>"><?php echo _get_setting('currency_sign',''),dsRound($obj['refundTotal'],2); ?></td>
                    </tr>
                    <? }?>
                    <tr>
                        <td style="<?php echo $td_styles; ?>">Overall Orders Amount</td>
                        <td style="<?php echo $td_styles_right; ?>"><?php echo _get_setting('currency_sign',''),dsRound($obj['transactionsTotal'],2); ?></td>
                    </tr>
                    <?php if($obj['status'] === 'Open') { ?>
                        <tr>
                            <td style="<?php echo $td_styles; ?>">Expected Closing Amount</td>
                            <td style="<?php echo $td_styles_right; ?>"><?php echo _get_setting('currency_sign',''),dsRound($obj['expectedClosingCash'] ,2); ?></td>
                        </tr>
                    <?php } ?>
                    <?php if($obj['status'] === 'Close') { ?>
                        <tr>
                            <td style="<?php echo $td_styles; ?>">Closing Amount</td>
                            <td style="<?php echo $td_styles_right; ?>"><?php echo _get_setting('currency_sign',''),dsRound($obj['takeOut'] + $obj['closingCash'] ,2); ?></td>
                        </tr>
                        <tr>
                            <td style="<?php echo $td_styles; ?>">Cash Out</td>
                            <td style="<?php echo $td_styles_right; ?>"><?php echo _get_setting('currency_sign',''),dsRound($obj['takeOut'],2); ?></td>
                        </tr>
                        <tr>
                            <td style="<?php echo $td_styles; ?>">Cash In Register</td>
                            <td style="<?php echo $td_styles_right; ?>"><?php echo _get_setting('currency_sign',''),dsRound($obj['closingCash'],2); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div style="width:49%;float:right;color:#333;">
            <div style="width:100%;color:#333;">
                <p style="margin:0;margin-bottom:5px;font-size:14px;text-align:left;"><strong>Specific Payments</strong></p>
            </div>
            <table style="border-collapse: collapse; width: 100%; border-top: 1px solid <?php echo $table_border_color; ?>; border-left: 1px solid <?php echo $table_border_color; ?>; margin-bottom: 20px;">
                <tbody>
                <?php foreach($obj['payments'] as $single){ ?>
                    <tr>
                        <td style="<?php echo $td_styles; ?>"><?php echo $single['label']; ?></td>
                        <td style="<?php echo $td_styles_right; ?>"><?php echo _get_setting('currency_sign',''),dsRound($single['amount'],2); ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <?php if(ALLOW_DISCOUNT_IN_SUMMARY) { ?>
        <div style="width:49%;float:right;color:#333;">
            <div style="width:100%;color:#333;">
                <p style="margin:0;margin-bottom:5px;font-size:14px;text-align:left;"><strong>Specific Discounts</strong></p>
            </div>
            <table style="border-collapse: collapse; width: 100%; border-top: 1px solid <?php echo $table_border_color; ?>; border-left: 1px solid <?php echo $table_border_color; ?>; margin-bottom: 20px;">
                <tbody>
                <?php foreach($obj['source'] as $single){ ?>
                    <tr>
                        <td style="<?php echo $td_styles; ?>"><?php echo $single['label']; ?></td>
                        <td style="<?php echo $td_styles_right; ?>"><?php echo _get_setting('currency_sign',''),dsRound($single['discount'],2);?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <?php } ?>
    </div>
    <div style="width:100%;color:#333;">
        <div style="width:49%;float:left;color:#333;">
            <div style="width:100%;color:#333;">
                <p style="margin:0;margin-bottom:5px;font-size:14px;text-align:left;"><strong>Opening Notes</strong></p>
            </div>
            <hr>
            <div style="width:100%;color:#333;">
                <p style="margin:0;margin-bottom:5px;font-size:14px;text-align:left;"><?php echo $obj['openingNote'] ?$obj['openingNote']: 'No notes'  ?></p>
            </div>
        </div>
        <div style="width:49%;float:right;color:#333;">
            <div style="width:100%;color:#333;">
                <p style="margin:0;margin-bottom:5px;font-size:14px;text-align:left;"><strong>Closing Notes</strong></p>
            </div>
            <hr>
            <div style="width:100%;color:#333;">
                <p style="margin:0;margin-bottom:5px;font-size:14px;text-align:left;"><?php echo $obj['closingNote'] ? $obj['closingNote'] : 'No Notes' ?></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
