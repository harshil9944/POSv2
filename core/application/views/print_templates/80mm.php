<?php
$colspan=2;
$order = _get_var('order');
?>
<style>

    /** {
        background: transparent !important;
        color: #000 !important;
        box-shadow: none !important;
        text-shadow: none !important;
    }
    body, table {
        font-family: Verdana;
        line-height: 1.4;
        font-size: 14px;
    }
    h1,h2,h3,h4,h5,h6 {
        margin: 0;
    }
    table {
        border-collapse: collapse;
        border-spacing: 0;
    }*/

    /* Spacing */
    .order-branding, .order-addresses, .order-info, .order-items, .order-notes, .order-thanks {
        margin-bottom: 5px;
    }

    /* Branding */
    .order-branding h1 {
        font-size: 2em;
        font-weight: bold;
    }

    /* Addresses */
    .order-addresses {
        display: table;
        width: 100%;
        font-family: Monospaced, sans-serif;
    }
    .billing-address, .shipping-address {
        display: table-cell;
        font-family: Monospaced, sans-serif;
    }

    /* Order */
    table.order-info, table.order-items {
        font-size: 16px;
        width: 100%;
        font-family: Monospaced, sans-serif;
    }
    table.order-info tr, table.order-items tr {
        border-bottom: 1px solid #dddddd;
    }
    table.order-info th, table.order-items th, table.order-info td, table.order-items td {
        padding: 2px 12px;
    }
    table.order-info {
        border-top: 3px solid #000;
    }
    table.order-info th {
        text-align: left;
    }
    table.order-items {
        border-bottom: 3px solid #000;
    }
    table.order-items thead tr th {
        border-bottom: 1px solid #000;
    }
    table.order-items tbody tr td {
        border-bottom: 1px dashed #000 !important;
    }
    table.order-items tbody tr:last-child td {
        border-bottom: 1px solid #000 !important;
    }
    .product {
        text-align: left;
    }
    .product dl {
        margin: 0;
    }
    .product dt {
        font-weight: 600;
        padding-right: 6px;
        float: left;
        clear: left;
    }
    .product dd {
        float: left;
        margin: 0;
    }
    .price {
        text-align: right;
    }
    .qty {
        text-align: center;
        width: 40px;
    }
    tfoot {
        text-align: right;
    }
    tfoot th {
        /*width: 70%;*/
    }
    tfoot tr.order-total {
        font-weight: bold;
    }
    tfoot tr.pos_cash-tendered th, tfoot tr.pos_cash-tendered td {
        border-top: 1px solid #000;
    }
    .text-center {
        text-align: center;
    }
</style>
<div class="order-branding text-center">
    <h1 class="m-b-0"><?php echo _get_setting('print_name',CORE_PRINT_NAME); ?></h1>
    <h3 style="margin-bottom: 0;">Order #&nbsp;<?php echo $order['sessionOrderNo']; ?></h3>
</div>
<table class="order-info">
    <?php if($order['billingName']){ ?>
        <tr>
            <th>Cust</th>
            <td><?php echo $order['billingName']; ?></td>
        </tr>
    <?php } ?>
    <?php if($order['billingAddress1']){ ?>
        <tr>
            <th>Addr</th>
            <td><?php echo $order['billingAddress1']; ?></td>
        </tr>
    <?php } ?>
    <tr>
        <th>Date</th>
        <td><?php echo custom_date_format($order['date']); ?></td>
    </tr>
</table>
<table class="order-items" cellpadding="0" cellspacing="0">
    <thead>
    <tr>
        <th class="qty">Qty</th>
        <th class="product">Item</th>
        <th class="price" style="display: none;">Rate</th>
        <th class="price">Amount</th>
    </tr>
    </thead>
    <tbody>
    <?php if($order['items']){ ?>
        <?php foreach($order['items'] as $row){ ?>
            <tr>
                <td class="qty"><?php echo round($row['quantity'],0); ?></td>
                <td class="product">
                    <?php echo $row['title']; ?>
                    <?php if($row['hasSpiceLevel']==='1' && $row['spiceLevel']!=='medium'){ ?>
                        <br/>Spice: <?php echo ucfirst($row['spiceLevel']); ?>
                    <?php } ?>
                    <?php if($row['notes']){ ?>
                    <br/>N: <?php echo $row['notes']; ?>
                    <?php } ?>
                </td>
                <td class="price" style="display: none;"><?php echo custom_money_format(round($row['rate'],2)); ?></td>
                <td class="price"><?php echo ($row['amount'])?custom_money_format(round($row['amount'],2)):''; ?></td>
            </tr>
        <?php } ?>
    <?php } ?>
    </tbody>
    <tfoot>
    <tr class="subtotal">
        <th colspan="<?php echo $colspan; ?>">Subtotal</th>
        <td colspan="1"><?php echo custom_money_format($order['subTotal']); ?></td>
    </tr>
    <?php if($order['type']==='d' && (float)$order['freightTotal']>0){ ?>
        <tr class="cart-discount">
            <th colspan="<?php echo $colspan; ?>">Delivery charges/extras</th>
            <td colspan="1"><?php echo custom_money_format($order['freightTotal']); ?></td>
        </tr>
    <?php } ?>
    <?php if($order['discount']>0){ ?>
        <tr class="cart-discount">
            <th colspan="<?php echo $colspan; ?>">Discount<?php echo ($order['discountType'] === 'p')?' (' . round($order['discountValue'],0) . '%)':''; ?></th>
            <td colspan="1"><?php echo custom_money_format($order['discount']); ?></td>
        </tr>
    <?php } ?>
    <tr class="tax">
        <th colspan="<?php echo $colspan; ?>">Tax (<?php echo $order['taxRate']; ?>%)</th>
        <td colspan="1"><?php echo custom_money_format($order['taxTotal']); ?></td>
    </tr>
    <tr class="order-total">
        <th colspan="<?php echo $colspan; ?>">Grand Total</th>
        <td colspan="1"><?php echo custom_money_format($order['grandTotal']); ?></td>
    </tr>
    <?php if($order['payments']){ ?>
    <?php foreach($order['payments'] as $payment){ ?>
        <tr class="pos_cash-tendered">
            <th colspan="<?php echo $colspan; ?>"><?php echo $payment['title'] ?></th>
            <td colspan="1"><?php echo custom_money_format($payment['amount']); ?></td>
        </tr>
    <?php } ?>
    <?php } ?>
    <?php if($order['change']>0){//if($order['paid_amount']){ ?>
        <tr class="pos_cash-tendered">
            <th colspan="<?php echo $colspan; ?>">Change</th>
            <td colspan="1"><?php echo custom_money_format($order['change']); ?></td>
        </tr>
    <?php } ?>
    <?php if($order['tip']>0 && _get_setting('show_tip_in_invoice',true)){//if($order['paid_amount']){ ?>
        <tr class="pos_cash-tendered">
            <th colspan="<?php echo $colspan; ?>">Tip</th>
            <td colspan="1"><?php echo custom_money_format($order['tip']); ?></td>
        </tr>
    <?php } ?>
    <?php if($order['notes']){ ?>
        <tr>
            <td style="text-align: left" colspan="<?php echo $colspan + 1; ?>"><b>Note:&nbsp;</b><?php echo $order['notes']; ?></td>
        </tr>
    <?php } ?>
    </tfoot>
</table>
<div class="order-notes text-center">78502 5271 RT0001</div>
<div class="order-notes text-center">Thank you for your business</div>
