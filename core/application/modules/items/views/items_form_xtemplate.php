<?php
$code = 'item';
$add_unit_btn = '<b-button v-b-modal.add-unit-modal size="xs" class="p-0 ml-5" variant="link"><i class="fa fa-plus"></i> </b-button>';
$add_category_btn = '<b-button v-b-modal.add-category-modal size="xs" class="p-0 ml-5" variant="link"><i class="fa fa-plus"></i> </b-button>';
$group_1 = [
    ['control'=>'text','code'=>['id'=>$code.'-title','title'=>'Item Name','attribute'=>'required','vue_model'=>$code.'.title']],
    //['control'=>'select','code'=>['id'=>$code.'-unit','title'=>'Base Unit','attribute'=>'required @change="onUnitSelect"','vue_model'=>$code.'.unit','vue_for'=>'masters.units']],
    ['control'=>'select','code'=>['id'=>$code.'-taxable','title'=>'Taxable Goods?','attribute'=>'required','vue_model'=>$code.'.taxable','vue_for'=>'masters.statuses']],
    ['control'=>'select','code'=>['id'=>$code.'-category','title'=>'Category','vue_model'=>$code.'.categoryId','vue_for'=>'masters.categories','button'=>$add_category_btn]],
];

$group_2 = [
    ['control'=>'select','code'=>['id'=>$code.'-print-location','title'=>'Print Location','attribute'=>'','vue_model'=>$code.'.printLocation','vue_for'=>'masters.printLocations']],
    ['control'=>'select','code'=>['id'=>$code.'-has-spice-level','title'=>'Has Spice Level','attribute'=>'','vue_model'=>$code.'.hasSpiceLevel','vue_for'=>'masters.statuses']],
    ['control'=>'select','code'=>['id'=>$code.'-pos-status','title'=>'Show in POS?','attribute'=>'','vue_model'=>$code.'.posStatus','vue_for'=>'masters.statuses']],
    ['control'=>'select','code'=>['id'=>$code.'-web-status','title'=>'Show in WEB?','attribute'=>'','vue_model'=>$code.'.webStatus','vue_for'=>'masters.statuses']],
    ['control'=>'select','code'=>['id'=>$code.'-app-status','title'=>'Show in APP?','attribute'=>'','vue_model'=>$code.'.appStatus','vue_for'=>'masters.statuses']],
    ['control'=>'text','code'=>['id'=>$code.'-rate','title'=>'Rate','attribute'=>'required','vue_model'=>$code.'.rate']],
    ['control'=>'select','code'=>['id'=>$code.'-is-veg','title'=>'Veg','attribute'=>'','vue_model'=>$code.'.isVeg','vue_for'=>'masters.statuses']],
    ['control'=>'select','code'=>['id'=>$code.'-spiciness','title'=>'Spiciness','attribute'=>'','vue_model'=>$code.'.spiciness','vue_for'=>'masters.spiciness']],

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
                                <?php echo get_textarea( ['id' => $code . '-description', 'title' => 'Description', 'attribute' => '', 'vue_model' => $code . '.description'] ); ?>
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
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="css-control css-control css-control-primary css-checkbox">
                                            <input type="checkbox" class="css-control-input" v-model="item.isVegan">
                                            <span class="css-control-indicator"></span>&nbsp;Is Vegan ?
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="css-control css-control css-control-primary css-checkbox">
                                            <input type="checkbox" class="css-control-input" v-model="item.isDairyFree">
                                            <span class="css-control-indicator"></span>&nbsp;Is Dairy Free ?
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="css-control css-control css-control-primary css-checkbox">
                                            <input type="checkbox" class="css-control-input" v-model="item.isGlutenFree">
                                            <span class="css-control-indicator"></span>&nbsp;Is Gluten Free ?
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="block">
                            <div class="block-content block-content-full">
                                <p class="list-item-heading mb-4">Variants&nbsp;<a href="#" @click.prevent="handleBlankVariation" class="btn btn-danger btn-sm pull-right">New Variant</a></p>
                                <div class="row">
                                    <div class="col-md-12 table-responsive">
                                        <table class="table table-bordered font-11 table-vcenter">
                                            <thead>
                                                <tr>
                                                    <th class="text-danger">Item Name*</th>
                                                    <th class="text-danger">Veg</th>
                                                    <th class="text-danger">Rate<br/><a href="#" class="font-10" @click.prevent="copyToAll('rate')">Copy to all</a></th>
                                                    <th class="text-danger text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(single,i) in variantList">
                                                    <td><?php echo get_text(['id'=>'single-title','title'=>'Item Name','attribute'=>'required data-parsley="item"','vue_model'=>'single.title'],'text',true) ?></td>
                                                    <td> <select  id="single-status" class="form-control" v-model="single.isVeg" required>
                                                            <option v-for="single in masters.statuses" :value="single.id">{{ single.value }}</option>
                                                        </select></td>
                                                    <td><?php echo get_text(['id'=>'single-rate','title'=>'Rate','class'=>'text-right','attribute'=>'required data-parsley="item"','vue_model'=>'single.rate'],'text',true) ?></td>
                                                    <td class="text-center"><a href="#" @click.prevent="handleRemoveVariation(single)" class="text-danger" title="Remove this Variation"><i class="fa fa-trash"></i></a></td>
                                                </tr>
                                                <tr v-if="!variantList.length"><td colspan="4" class="text-center">No Item</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
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
                                                    <th class="w-250p">Display Title</th>
                                                    <th class="w-150p">Addon Price<br/> <a href="#" class="font-10" @click.prevent="copyToAllAddons('rate')">Copy to all</a></th>
                                                    <th class="w-25p">&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-if="addonsList.length" v-for="(a,i) in addonsList">
                                                    <td><b-form-input id="a-title" v-model="a.title" placeholder="Title"></b-form-input></td>
                                                    <td><b-form-input id="a-rate" class="text-right" v-model="a.rate" placeholder="Addon Price"></b-form-input></td>
                                                    <td class="text-center"><a href="#" @click.prevent="handleRemoveAddon(a)" class="text-danger" title="Remove this Addon"><i class="fas fa-trash"></i></a></td>
                                                </tr>
                                                <tr v-if="!addonsList.length"><td class="text-center" colspan="4">No Addons</td></tr>
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
