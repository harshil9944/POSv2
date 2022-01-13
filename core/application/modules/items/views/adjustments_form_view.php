<?php
$code = 'item';
$group_1 = [
    ['control'=>'text','code'=>['id'=>$code.'-reference','title'=>'Reference#','attribute'=>'']],
    ['control'=>'text','code'=>['id'=>$code.'-date','title'=>'Date','attribute'=>'required']],
    ['control'=>'select','code'=>['id'=>$code.'-reason','title'=>'Reason','attribute'=>'required']],
    ['control'=>'select','code'=>['id'=>$code.'-warehouse','title'=>'Warehouse','attribute'=>'required']]
];
?>
<div id="<?php echo $code; ?>-form" class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="block">
                    <div class="block-content block-content-full">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-md-12"><?php echo get_radio_set(['id'=>$code.'-type','title'=>'Mode of adjustment','attribute'=>'required'],[['title'=>'Quantity Adjustment','value'=>'q'],['title'=>'Value Adjustment','value'=>'v']]); ?></div>
                                    <?php foreach ($group_1 as $item) { ?>
                                        <div class="col-md-12">
                                            <?php $func = 'get_'.$item['control'] ?>
                                            <?php echo $func($item['code']); ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="block">
                    <div class="block-content block-content-full">
                        <div class="row">
                            <div class="col-md-8">
                                <span class="text-danger font-weight-600 mb-10 d-block">Associate Items*<a class="float-right" href="javascript:void(0);"><i class="fa fa-plus mr-5"></i>Add Item</a> </span>
                                <table class="table table-bordered table-vcenter">
                                    <thead>
                                    <tr class="font-11 bg-gray-lighter">
                                        <th>Item Details</th>
                                        <th class="w-120p text-right">Stock on Hand</th>
                                        <th class="w-170p text-right">New Quantity on Hand</th>
                                        <th class="w-140p text-right">Quantity Adjusted</th>
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
            <div class="col-md-12 mb-3">
                <div class="block">
                    <div class="block-content block-content-full">
                        <a href="#" class="btn btn-primary btn-noborder">Save</a>
                        <a href="#" class="btn btn-white btn-noborder">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
