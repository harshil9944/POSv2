<?php
$code = 'item';
$add_unit_btn = '<b-button v-b-modal.add-unit-modal size="xs" class="p-0 ml-5" variant="link"><i class="fa fa-plus"></i> </b-button>';
$group_1 = [
    ['control'=>'text','code'=>['id'=>$code.'-name','title'=>'Item Name','attribute'=>'required','vue_model'=>$code.'.name']],
    ['control'=>'text','code'=>['id'=>$code.'-sku','title'=>'SKU','attribute'=>'required ref="sku"','vue_model'=>$code.'.sku']],
    //['control'=>'select','code'=>['id'=>$code.'-unit','title'=>'Base Unit','attribute'=>'required @change="onUnitSelect"','vue_model'=>$code.'.unit','vue_for'=>'masters.units']],
    //['control'=>'select','code'=>['id'=>$code.'-purchase-unit','title'=>'Default Purchase Unit','attribute'=>'@change="updatePrices"','vue_model'=>$code.'.purchaseUnit','vue_for'=>'masters.subUnits']],
    //['control'=>'select','code'=>['id'=>$code.'-sale-unit','title'=>'Default Sale Unit','attribute'=>'@change="updatePrices"','vue_model'=>$code.'.saleUnit','vue_for'=>'masters.subUnits']],
    ['control'=>'select','code'=>['id'=>$code.'-taxable','title'=>'Taxable Goods?','attribute'=>'required','vue_model'=>$code.'.taxable','vue_for'=>'masters.taxable']],
    ['control'=>'select','code'=>['id'=>$code.'-print-location','title'=>'Print Location','attribute'=>'','vue_model'=>$code.'.printLocation','vue_for'=>'masters.printLocations']],
    ['control'=>'select','code'=>['id'=>$code.'-has-spice-level','title'=>'Has Spice Level','attribute'=>'','vue_model'=>$code.'.hasSpiceLevel','vue_for'=>'masters.hasSpiceLevel']],
    //['control'=>'select','code'=>['id'=>$code.'-icon','title'=>'Icon (Icon Class)','attribute'=>'','vue_model'=>$code.'.icon','vue_for'=>'masters.icons']],
    ['control'=>'select','code'=>['id'=>$code.'-pos-status','title'=>'Show in POS?','attribute'=>'','vue_model'=>$code.'.posStatus','vue_for'=>'masters.posStatuses']],
    ['control'=>'select','code'=>['id'=>$code.'-web-status','title'=>'Show in WEB?','attribute'=>'','vue_model'=>$code.'.webStatus','vue_for'=>'masters.webStatuses']],
    ['control'=>'select','code'=>['id'=>$code.'-app-status','title'=>'Show in APP?','attribute'=>'','vue_model'=>$code.'.appStatus','vue_for'=>'masters.appStatuses']],


];
$add_category_btn = '<b-button v-b-modal.add-category-modal size="xs" class="p-0 ml-5" variant="link"><i class="fa fa-plus"></i> </b-button>';
$group_2 = [
    ['control'=>'select','code'=>['id'=>$code.'-category','title'=>'Category','vue_model'=>$code.'.categoryId','vue_for'=>'masters.categories','button'=>$add_category_btn]],
  /*  ['control'=>'text','code'=>['id'=>$code.'-weight','title'=>'Weight','vue_model'=>$code.'.weight']],*/
  /*  ['control'=>'text','code'=>['id'=>$code.'-manufacturer','title'=>'Brand','vue_model'=>$code.'.manufacturer']],
    ['control'=>'text','code'=>['id'=>$code.'-upc','title'=>'UPC','vue_model'=>$code.'.upc']],
    /*['control'=>'text','code'=>['id'=>$code.'-mpn','title'=>'MPN','vue_model'=>$code.'.mpn']],
    ['control'=>'text','code'=>['id'=>$code.'-ean','title'=>'EAN','vue_model'=>$code.'.ean']],*/
    ['control'=>'select','code'=>['id'=>$code.'-is-veg','title'=>'Veg','attribute'=>'','vue_model'=>$code.'.isVeg','vue_for'=>'masters.isVeg']]


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
                                <p class="list-item-heading mb-4">Icon&nbsp;<a v-if="item.icon!==''" class="btn btn-sm btn-danger pull-right" href="#" @click.prevent="item.icon=''">Set No Icon</a></p>
                                <div class="row">
                                    <div v-if="masters.icons" class="col-12">
                                        <ul class="list-unstyled">
                                            <li class="d-inline mr-3 mb-3" v-for="i in masters.icons">
                                                <button class="btn fa-2x" @click.prevent="item.icon=i.id" :class="item.icon === i.id ? 'btn-danger' : 'btn-secondary'"><i v-if="i.id" :class="i.value"></i><span v-if="!i.id">&nbsp;</span></button>
                                            </li>
                                        </ul>
                                    </div>
                                    <div v-if="!masters.icons" class="text-center col-12">No icon available</div>
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
                                <div v-if="item.prices" class="row">
                                    <div class="col-md-12 table-responsive">
                                        <table class="table table-bordered font-11 table-vcenter">
                                            <thead>
                                            <tr>
                                                <!-- <th class="text-center">Unit</th> -->
                                                <th class="text-danger">Purchase Price *</th>
                                                <th class="text-danger">Sale Price *</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="single in item.prices">
                                               <!--  <td class="text-center">{{ unitTitle(single.unitId) }}</td> -->
                                                <td><?php echo get_text(['id'=>'purchase-price','title'=>'Purchase Price','attribute'=>'required data-parsley-group="price-group"','vue_model'=>'single.purchasePrice'],'text',true) ?></td>
                                                <td><?php echo get_text(['id'=>'sale-price','title'=>'Sale Price','attribute'=>'required data-parsley-group="price-group"','vue_model'=>'single.salePrice'],'text',true) ?></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                               <!--  <div v-else class="row">
                                    <div class="col-md-12 text-center">
                                        <h5>Select Unit First</h5>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-8">
                        <div id="addons" class="block">
                            <div class="block-content block-content-full">
                                <p class="list-item-heading mb-4">Addons&nbsp;<a href="#" @click.prevent="handleBlankAddon" class="btn btn-danger btn-sm pull-right">Add Addon</a></p>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-bordered font-11 table-vcenter">
                                            <thead>
                                                <tr>
                                                    <th>Item</th>
                                                    <th class="w-250p">Display Title</th>
                                                    <th class="w-150p">Addon Price</th>
                                                    <th class="w-25p">&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-if="item.addons.length" v-for="(a,i) in item.addons">
                                                    <td><vue-multiselect label="title" track-by="id" placeholder="Type to search Items" open-direction="bottom" :options="masters.addonItems" v-model="a.addonItemId" :multiple="false" :searchable="true" required></vue-multiselect></td>
                                                    <td><b-form-input v-model="a.title" placeholder="Title"></b-form-input></td>
                                                    <td><b-form-input class="text-right" v-model="a.salePrice" placeholder="Addon Price"></b-form-input></td>
                                                    <td class="text-center"><a href="#" @click.prevent="handleRemoveAddon(i)" class="text-danger" title="Remove this Addon"><i class="fas fa-trash"></i></a></td>
                                                </tr>
                                                <tr v-if="!item.addons.length"><td class="text-center" colspan="4">No Addons</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div id="notes" class="block">
                            <div class="block-content block-content-full">
                                <p class="list-item-heading mb-4">Notes&nbsp;<a href="#" @click.prevent="handleBlankNote" class="btn btn-danger btn-sm pull-right">Add Note</a></p>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-bordered font-11 table-vcenter">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th class="w-25p">&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-if="item.notes.length" v-for="(n,i) in item.notes">
                                                    <td><b-form-input v-model="n.title" placeholder="Note"></b-form-input></td>
                                                    <td class="text-center"><a href="#" @click.prevent="handleRemoveNote(i)" class="text-danger" title="Remove this Note"><i class="fas fa-trash"></i></a></td>
                                                </tr>
                                                <tr v-if="!item.notes.length"><td class="text-center" colspan="2">No Notes</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div id="feature" class="d-none">
                            <div class="block-content block-content-full">
                                <p class="list-item-heading mb-4">Features</p>
                                <div class="row">
                                    <div class="col-md-12 table-responsive">
                                        <table class="table table-bordered font-11">
                                            <thead>
                                            <tr>
                                                <th class="w-50">Feature&nbsp;<b-button v-b-modal.add-feature-modal size="xs" class="p-0" variant="link"><i class="fa fa-plus"></i> </b-button></th>
                                                <th class="w-50">Value</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="(feature,index) in item.features">
                                                <td>
                                                    <select class="form-control" v-model="feature.featureId">
                                                        <option v-for="single in masters.features" :value="single.id">{{ single.value }}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" v-model="feature.title">
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12">
                                        <a href="#" @click.prevent="handleBlankFeature"><i class="fa fa-plus"></i>&nbsp;Add Feature</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div  class="col-md-12">
                        <div  id="inventory" class="d-none">
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
        <add-feature></add-feature>
        <add-unit></add-unit>
        <add-category></add-category>
    </div>
</script>
<script type="text/x-template" id="add-feature-template">
    <div>
        <b-modal no-fade id="add-feature-modal" size="xs" @ok="handleAddFeature" title="Add Feature">
            <form id="frm-add-feature" data-parsley-validate="true" @submit.prevent="handleAddFeature">
                <b-form-group
                        label="Title"
                        label-for="feature-title"
                >
                    <b-form-input
                            id="feature-title"
                            v-model="feature.title"
                            required
                            data-parsley-required-message="Title is required"
                    ></b-form-input>
                </b-form-group>
            </form>
        </b-modal>
    </div>
</script>
<script type="text/x-template" id="add-category-template">
    <div>
        <b-modal no-fade id="add-category-modal" size="xs" @ok="handleAddCategory" title="Add Category">
            <form id="frm-add-category" data-parsley-validate="true" @submit.prevent="handleAddCategory">
                <b-form-group
                        label="Title"
                        label-for="category-title"
                >
                    <b-form-input
                            id="category-title"
                            v-model="category.title"
                            required
                            data-parsley-required-message="Title is required"
                    ></b-form-input>
                </b-form-group>
            </form>
        </b-modal>
    </div>
</script>
<script type="text/x-template" id="add-unit-template">
    <div>
        <b-modal no-fade id="add-unit-modal" size="xs" @ok="handleAddUnit" title="Add Unit">
            <form id="frm-add-unit" data-parsley-validate="true" @submit.prevent="handleAddUnit">
                <b-form-group
                        label="Title"
                        label-for="unit-title"
                >
                    <b-form-input
                            id="unit-title"
                            v-model="unit.title"
                            required
                            data-parsley-required-message="Title is required"
                    ></b-form-input>
                </b-form-group>
            </form>
        </b-modal>
    </div>
</script>
