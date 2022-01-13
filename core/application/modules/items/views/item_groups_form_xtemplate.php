<?php
$code = 'item';
$add_category_btn = '<b-button v-b-modal.add-category-modal size="xs" class="p-0 ml-5" variant="link"><i class="fa fa-plus"></i> </b-button>';
$group_1 = [
    ['control'=>'text','code'=>['id'=>$code.'-group-name','title'=>'Item Group Name','attribute'=>'required data-parsley-group="item-group"','vue_model'=>$code.'.name']],
    //['control'=>'select','code'=>['id'=>$code.'-unit','title'=>'Unit','attribute'=>'required data-parsley-group="item-group" @change="onUnitSelect"','vue_model'=>$code.'.unit','vue_for'=>'masters.units']],
    //['control'=>'select','code'=>['id'=>$code.'-purchase-unit','title'=>'Default Purchase Unit','attribute'=>'@change="updatePrices"','vue_model'=>$code.'.purchaseUnit','vue_for'=>'masters.subUnits']],
    //['control'=>'select','code'=>['id'=>$code.'-sale-unit','title'=>'Default Sale Unit','attribute'=>'@change="updatePrices"','vue_model'=>$code.'.saleUnit','vue_for'=>'masters.subUnits']],
    //['control'=>'text','code'=>['id'=>$code.'-manufacturer','title'=>'Manufacturer','vue_model'=>$code.'.manufacturer']],
    ['control'=>'select','code'=>['id'=>$code.'-taxable','title'=>'Taxable Goods?','attribute'=>'required','vue_model'=>$code.'.taxable','vue_for'=>'masters.taxable']],
    ['control'=>'select','code'=>['id'=>$code.'-print-location','title'=>'Print Location','attribute'=>'','vue_model'=>$code.'.printLocation','vue_for'=>'masters.printLocations']],
    ['control'=>'select','code'=>['id'=>$code.'-has-spice-level','title'=>'Has Spice Level','attribute'=>'','vue_model'=>$code.'.hasSpiceLevel','vue_for'=>'masters.hasSpiceLevel']],
    ['control'=>'select','code'=>['id'=>$code.'-category','title'=>'Category','attribute'=>'','vue_model'=>$code.'.categoryId','vue_for'=>'masters.categories','button'=>$add_category_btn]],
   // ['control'=>'select','code'=>['id'=>$code.'-icon','title'=>'Icon (Icon Class)','attribute'=>'','vue_model'=>$code.'.icon','vue_for'=>'masters.icons']],
    ['control'=>'select','code'=>['id'=>$code.'-pos-status','title'=>'Show in POS?','attribute'=>'','vue_model'=>$code.'.posStatus','vue_for'=>'masters.posStatuses']],
    ['control'=>'select','code'=>['id'=>$code.'-web-status','title'=>'Show in WEB?','attribute'=>'','vue_model'=>$code.'.webStatus','vue_for'=>'masters.webStatuses']],
    ['control'=>'select','code'=>['id'=>$code.'-app-status','title'=>'Show in APP?','attribute'=>'','vue_model'=>$code.'.appStatus','vue_for'=>'masters.appStatuses']],

];
?>
<script type="text/x-template" id="item-group-form-template">
   <div id="<?php echo $code; ?>-group-form" class="row">
    <form id="frm-item-group">
          <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 mb-3">
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
                <div class="col-md-12 mb-3">
                    <div class="block">
                        <div class="block-content block-content-full">
                        <p class="list-item-heading mb-4">Variation&nbsp;<a href="#" @click.prevent="handleBlankVariation" class="btn btn-danger btn-sm pull-right">Add Variation</a></p>
                            <div class="row">
                                <div class="col-md-12 table-responsive">
                                    <table class="table table-bordered font-11 table-vcenter">
                                        <thead>
                                            <tr>
                                                <th class="text-danger">Item Name*</th>
                                                <th class="text-danger">SKU*</th>
                                                <th class="text-danger">Purchase Price*<br/><a href="#" class="font-10" @click.prevent="copyToAll('purchasePrice')">Copy to all</a></th>
                                                <th class="text-danger">Selling Price*<br/><a href="#" class="font-10" @click.prevent="copyToAll('sellingPrice')">Copy to all</a></th>
                                                <th class="text-danger">is Veg?</th>
                                                <th></th>
                                                <?php if(1==2){ ?>
                                                <th class="d-none">UPC</th>
                                                <th class="d-none">EAN</th>
                                                <th class="d-none">Reorder Level<br/><a href="#" class="font-10" @click.prevent="copyToAll('reorderLevel')">Copy to all</a></th>
                                                <th class="d-none">Opening Stock</th>
                                                <th class="d-none">Opening Stock Value</th>
                                                <th>&nbsp;</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(single,i) in item.skus">
                                                <td><?php echo get_text(['id'=>'','title'=>'Item Name','attribute'=>'required data-parsley-group="item-group"','vue_model'=>'single.name'],'text',true) ?></td>
                                                <td><?php echo get_text(['id'=>'','title'=>'SKU','attribute'=>'required ref="sku" data-parsley-group="item-group"','vue_model'=>'single.sku'],'text',true) ?></td>
                                                <td><?php echo get_text(['id'=>'','title'=>'Purchase Price','class'=>'text-right','attribute'=>'required data-parsley-group="item-group"','vue_model'=>'single.purchasePrice'],'text',true) ?></td>
                                                <td><?php echo get_text(['id'=>'','title'=>'Selling Price','class'=>'text-right','attribute'=>'required data-parsley-group="item-group"','vue_model'=>'single.sellingPrice'],'text',true) ?></td>
                                                <td><?php echo get_select(['id'=>'','title'=>'Veg ','attribute'=>'required','vue_model'=>'single.isVeg','vue_for'=>'masters.isVeg'],[],'value','id',true); ?></td>
                                                <td class="text-center"><a href="#" @click.prevent="handleRemoveVariation(i)" class="text-danger" title="Remove this Variation"><i class="fas fa-trash"></i></a></td>
                                                <?php if(1==2){ ?>
                                                <td class="d-none"><?php echo get_text(['id'=>'','title'=>'UPC','attribute'=>'data-parsley-group="item-group"','vue_model'=>'single.upc'],'text',true) ?></td>
                                                <td class="d-none"><?php echo get_text(['id'=>'','title'=>'EAN','attribute'=>'data-parsley-group="item-group"','vue_model'=>'single.ean'],'text',true) ?></td>
                                                <td class="d-none"><?php echo get_text(['id'=>'','title'=>'Reorder Level','class'=>'text-right','attribute'=>'data-parsley-group="item-group"','vue_model'=>'single.reorderLevel'],'text',true) ?></td>
                                                <td class="d-none"><?php echo get_text(['id'=>'','title'=>'Opening Stock','class'=>'text-right','atttribute'=>'data-parsley-group="item-group"','vue_model'=>'single.openingStock'],'text',true) ?></td>
                                                <td class="d-none"><?php echo get_text(['id'=>'','title'=>'Opening Stock Value','class'=>'text-right','attribute'=>'data-parsley-group="item-group"','vue_model'=>'single.openingStockValue'],'text',true) ?></td>
                                                <td style="vertical-align: middle !important;"><a href="#"><i class="fa fa-trash text-danger font-14"></i></a></td>
                                                <?php } ?>
                                            </tr>
                                            <tr v-if="!item.skus.length"><td colspan="6" class="text-center">No Item</td></tr>
                                        </tbody>

                                    </table>
                                </div>
                                <add-option></add-option>
                            </div>
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
                <div class="col-md-12 mb-3">
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
    <add-category></add-category>
  </div>

</script>
<script type="text/x-template" id="add-option-template">
        <div>
            <b-modal id="add-option-modal" size="xs" @ok="handleAddOption" title="Add Option">
                <form id="frm-add-option" data-parsley-validate="true" @submit.prevent="handleAddOption">
                    <b-form-group
                            label="Title"
                            label-for="option-title"
                    >
                        <b-form-input
                                id="option-title"
                                v-model="option.title"
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

