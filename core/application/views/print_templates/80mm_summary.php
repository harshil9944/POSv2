<?php
$colspan=2;
$summary = _get_var('summary');
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
        font-family: Verdana;
    }
    .billing-address, .shipping-address {
        display: table-cell;
        font-family: Verdana;
    }

    /* Order */
    table.order-info, table.order-items {
        font-size: 12px;
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
        font-size: 14px;
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
        font-weight: 600;
        font-size:14px;
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
    <h3 style="margin-bottom:0;">Register Summary</h3>
</div>
<table class="order-info">
    <tr>
        <th>Opened</th>
        <td style="text-align:right;"><?php echo custom_date_format($summary['opening_date']); ?></td>
    </tr>
    <tr>
        <th>Opened by</th>
        <td style="text-align:right;"><?php echo $summary['openingEmployee']; ?></td>
    </tr>
    <tr>
        <th>Closed</th>
        <td style="text-align:right;"><?php echo custom_date_format($summary['closing_date']); ?></td>
    </tr>
    <tr>
        <th>Closed by</th>
        <td style="text-align:right;"><?php echo $summary['closingEmployee']; ?></td>
    </tr>
</table>
<table class="order-items" cellpadding="0" cellspacing="0">
    <tbody>
        <tr>
            <td class="product">Orders placed</td>
            <td class="price"><?php echo $summary['orders_count']; ?></td>
        </tr>
        <tr>
            <td class="product">Orders cancelled</td>
            <td class="price"><?php echo $summary['cancelled_orders_count']; ?></td>
        </tr>
    </tbody>
</table>
<table class="order-items" cellpadding="0" cellspacing="0">
    <tbody>
        <tr>
            <td class="product">Opening Amount</td>
            <td class="price"><?php echo custom_money_format($summary['opening_cash']) ?></td>
        </tr>
        <tr>
            <td class="product">Orders amount</td>
            <td class="price"><?php echo custom_money_format($summary['transactions_total']) ?></td>
        </tr>
        <tr>
            <td class="product">Cash amount</td>
            <td class="price"><?php echo custom_money_format($summary['cash_transactions_total']) ?></td>
        </tr>
        <tr>
            <td class="product">Card amount</td>
            <td class="price"><?php echo custom_money_format($summary['card_transactions_total']) ?></td>
        </tr>
        <tr>
            <td class="product">Discount</td>
            <td class="price"><?php echo custom_money_format($summary['discount_total']) ?></td>
        </tr>
        <tr>
            <td class="product">Change</td>
            <td class="price"><?php echo custom_money_format($summary['change_total']) ?></td>
        </tr>
        <tr>
            <td class="product">Tip</td>
            <td class="price"><?php echo custom_money_format($summary['tip_total']) ?></td>
        </tr>
        <tr>
            <td class="product">Closing Amount</td>
            <td class="price"><?php echo custom_money_format($summary['closing_cash']) ?></td>
        </tr>
    </tbody>
</table>
<div class="order-notes text-center"><?php echo _get_setting('print_name',CORE_PRINT_NAME); ?></div>
<div class="order-notes text-center">Printed <?php echo custom_date_format(sql_now_datetime()); ?></div>
