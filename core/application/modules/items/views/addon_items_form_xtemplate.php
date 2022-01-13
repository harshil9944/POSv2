<?php
$code = 'item';
$add_unit_btn = '<b-button v-b-modal.add-unit-modal size="xs" class="p-0 ml-5" variant="link"><i class="fa fa-plus"></i> </b-button>';
$group_1 = [
    ['control'=>'text','code'=>['id'=>$code.'-name','title'=>'Item Name','attribute'=>'required','vue_model'=>$code.'.name']],
    ['control'=>'text','code'=>['id'=>$code.'-sku','title'=>'SKU','attribute'=>'required ref="sku"','vue_model'=>$code.'.sku']],
    //['control'=>'select','code'=>['id'=>$code.'-unit','title'=>'Base Unit','attribute'=>'required @change="onUnitSelect"','vue_model'=>$code.'.unit','vue_for'=>'masters.units','button'=>$add_unit_btn]],
    //['control'=>'select','code'=>['id'=>$code.'-purchase-unit','title'=>'Default Purchase Unit','attribute'=>'@change="updatePrices"','vue_model'=>$code.'.purchaseUnit','vue_for'=>'masters.subUnits']],
    //['control'=>'select','code'=>['id'=>$code.'-sale-unit','title'=>'Default Sale Unit','attribute'=>'@change="updatePrices"','vue_model'=>$code.'.saleUnit','vue_for'=>'masters.subUnits']],
    //['control'=>'select','code'=>['id'=>$code.'-taxable','title'=>'Taxable Goods?','attribute'=>'required','vue_model'=>$code.'.taxable','vue_for'=>'masters.taxable']],
    //['control'=>'select','code'=>['id'=>$code.'-print-location','title'=>'Print Location','attribute'=>'','vue_model'=>$code.'.printLocation','vue_for'=>'masters.printLocations']],
    //['control'=>'select','code'=>['id'=>$code.'-has-spice-level','title'=>'Has Spice Level','attribute'=>'required','vue_model'=>$code.'.hasSpiceLevel','vue_for'=>'masters.hasSpiceLevel']],
    //['control'=>'text','code'=>['id'=>$code.'-icon','title'=>'Icon (Icon Class)','attribute'=>'','vue_model'=>$code.'.icon']],


];
$add_category_btn = '<b-button v-b-modal.add-category-modal size="xs" class="p-0 ml-5" variant="link"><i class="fa fa-plus"></i> </b-button>';
$group_2 = [
    ['control'=>'select','code'=>['id'=>$code.'-category','title'=>'Category','attribute'=>'required','vue_model'=>$code.'.categoryId','vue_for'=>'masters.categories','button'=>$add_category_btn]],
  /*  ['control'=>'text','code'=>['id'=>$code.'-weight','title'=>'Weight','vue_model'=>$code.'.weight']],*/
  /*  ['control'=>'text','code'=>['id'=>$code.'-manufacturer','title'=>'Brand','vue_model'=>$code.'.manufacturer']],
    ['control'=>'text','code'=>['id'=>$code.'-upc','title'=>'UPC','vue_model'=>$code.'.upc']],
    /*['control'=>'text','code'=>['id'=>$code.'-mpn','title'=>'MPN','vue_model'=>$code.'.mpn']],
    ['control'=>'text','code'=>['id'=>$code.'-ean','title'=>'EAN','vue_model'=>$code.'.ean']],*/
    ['control'=>'select','code'=>['id'=>$code.'-is-veg','title'=>'Veg','attribute'=>'required','vue_model'=>$code.'.isVeg','vue_for'=>'masters.isVeg']]


];

$group_4 = [
   /* ['control'=>'text','code'=>['id'=>$code.'-opening-stock','title'=>'Opening Stock','vue_model'=>$code.'.openingStock']],
    ['control'=>'text','code'=>['id'=>$code.'-opening-stock-value','title'=>'Opening Stock Value','attribute'=>'','vue_model'=>$code.'.openingStockValue']],
    ['control'=>'text','code'=>['id'=>$code.'-reorder-level','title'=>'Reorder Level','attribute'=>'','vue_model'=>$code.'.reorderLevel']],
    ['control'=>'select','code'=>['id'=>$code.'-preferred-vendor','title'=>'Preferred Vendor','vue_model'=>$code.'.preferredVendor','vue_for'=>'masters.vendors']]*/
];
?>
<script type="text/x-template" id="items-form-template">
    <div id="<?php echo $code; ?>-form" class="row">
        <form id="frm-item">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="block">
                            <div class="block-content block-content-full">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <?php foreach ($group_1 as $item) { ?>
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <?php $func = 'get_'.$item['control'] ?>
                                                            <?php echo $func($item['code']); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <img class="img-fluid img-thumbnail mb-20 w-50" v-if="item.imageUrl" :src="item.imageUrl" alt="">
                                         <div class="form-group">
                                            <label class="w-100">Image</label>
                                            <b-form-file v-if="!item.image" v-model="file.image"></b-form-file>
                                            <span v-if="item.image">{{ item.image }} <a href="#" class="font-18 ml-2" @click.prevent="removeImage" title="Remove Image"><i class="fa fa-remove text-danger"></i></a></span>
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
                    <div class="col-md-12">
                        <div class="block">
                            <div class="block-content block-content-full">
                                <p class="list-item-heading mb-4">Prices</p>
                                <div v-if="item.prices.length" class="row">
                                    <div class="col-md-9 table-responsive">
                                        <table class="table table-bordered font-11 table-vcenter">
                                            <thead>
                                            <tr>
                                                <th class="text-center">Unit</th>
                                                <th class="text-danger">Purchase Price *</th>
                                                <th class="text-danger">Sale Price *</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="single in item.prices">
                                                <td class="text-center">{{ unitTitle(single.unitId) }}</td>
                                                <td><?php echo get_text(['id'=>'purchase-price','title'=>'Purchase Price','attribute'=>'required data-parsley-group="price-group"','vue_model'=>'single.purchasePrice'],'text',true) ?></td>
                                                <td><?php echo get_text(['id'=>'sale-price','title'=>'Sale Price','attribute'=>'required data-parsley-group="price-group"','vue_model'=>'single.salePrice'],'text',true) ?></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div v-else class="row">
                                    <div class="col-md-12 text-center">
                                        <h5>Select Unit First</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="block">
                            <div class="block-content block-content-full">
                                <a href="#" @click.prevent="handleSubmit" class="btn btn-primary btn-noborder">Save</a>
                                <a href="#" @click.prevent="cancel" class="btn btn-white btn-noborder">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</script>
