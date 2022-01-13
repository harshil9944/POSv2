<?php
$code = 'item';
$group_1 = [
    ['control'=>'text','code'=>['id'=>$code.'-name','title'=>'Name','attribute'=>'required']],
    ['control'=>'select','code'=>['id'=>$code.'-sku','title'=>'SKU','attribute'=>'required']],
    ['control'=>'select','code'=>['id'=>$code.'-unit','title'=>'Unit','attribute'=>'required']]
];
$group_2 = [
    ['control'=>'text','code'=>['id'=>$code.'-dimensions','title'=>'Dimensions']],
    ['control'=>'text','code'=>['id'=>$code.'-weight','title'=>'Weight']],
    ['control'=>'text','code'=>['id'=>$code.'-upc','title'=>'UPC']],
    ['control'=>'text','code'=>['id'=>$code.'-mpn','title'=>'MPN']],
    ['control'=>'text','code'=>['id'=>$code.'-ean','title'=>'EAN']]
];
$group_3 = [
    ['control'=>'text','code'=>['id'=>$code.'-selling-price','title'=>'Selling Price','attribute'=>'required']],
    ['control'=>'text','code'=>['id'=>$code.'-selling-price','title'=>'Purchase Price','attribute'=>'required']]
];
$group_4 = [
    ['control'=>'text','code'=>['id'=>$code.'-opening-stock','title'=>'Opening Stock']],
    ['control'=>'text','code'=>['id'=>$code.'-opening-stock-value','title'=>'Opening Stock Value']],
    ['control'=>'text','code'=>['id'=>$code.'-reorder-level','title'=>'Reorder Level']]
];
?>
<div id="<?php echo $code; ?>-form" class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-9">
                <div class="block">
                    <div class="block-content block-content-full">
                        <div class="row">
                        <?php foreach ($group_1 as $item) { ?>
                            <div class="col-md-7">
                                <?php $func = 'get_'.$item['control'] ?>
                                <?php echo $func($item['code']); ?>
                            </div>
                        <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="block">
                    <div class="block-content block-content-full">
                        <div class="row">
                            <div class="col-md-12">
                                <span class="text-danger font-weight-600 mb-10 d-block">Associate Items*<a class="float-right" href="javascript:void(0);"><i class="fa fa-plus mr-5"></i>Add Item</a> </span>
                                <table class="table table-bordered table-vcenter">
                                    <thead>
                                    <tr class="font-11 bg-gray-lighter">
                                        <th>Item Details</th>
                                        <th class="w-70p text-right">Quantity</th>
                                        <th class="w-110p text-right">Selling Price</th>
                                        <th class="w-120p text-right">Purchase Price</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th>Pepsi 2 Litres</th>
                                        <td class="text-right">1</td>
                                        <td class="text-right">80.00</td>
                                        <td class="text-right">60.00</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="block">
                    <div class="block-content block-content-full">
                        <div class="row">
                            <?php foreach ($group_2 as $item) { ?>
                                <div class="col-md-6">
                                    <?php $func = 'get_'.$item['control'] ?>
                                    <?php echo $func($item['code']); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="block">
                    <div class="block-content block-content-full">
                        <p class="list-item-heading mb-4">Prices</p>
                        <div class="row">
                            <?php foreach ($group_3 as $item) { ?>
                                <div class="col-md-6">
                                    <?php $func = 'get_'.$item['control'] ?>
                                    <?php echo $func($item['code']); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="block">
                    <div class="block-content block-content-full">
                        <p class="list-item-heading mb-4">Inventory</p>
                        <div class="row">
                            <?php foreach ($group_4 as $item) { ?>
                                <div class="col-md-6">
                                    <?php $func = 'get_'.$item['control'] ?>
                                    <?php echo $func($item['code']); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="block">
                    <div class="block-content block-content-full">
                        <a href="#" class="btn btn-primary">Save</a>
                        <a href="#" class="btn btn-white">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
