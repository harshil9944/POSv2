<?php
$code = 'item';
$group_1 = [
    ['control'=>'text','code'=>['id'=>$code.'-order-no','title'=>'Transfer Order#','attribute'=>'required']],
    ['control'=>'text','code'=>['id'=>$code.'-date','title'=>'Date','attribute'=>'required']],
    ['control'=>'text','code'=>['id'=>$code.'-reason','title'=>'Reason','attribute'=>'required']]
];
$group_2 = [
    ['control'=>'select','code'=>['id'=>$code.'-source-wh','title'=>'Source Warehouse','attribute'=>'required']],
    ['control'=>'select','code'=>['id'=>$code.'-dest-wh','title'=>'Destination Warehouse','attribute'=>'required']]
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
            <div class="col-md-12 mb-3">
                <div class="block">
                    <div class="block-content block-content-full">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <?php foreach ($group_2 as $item) { ?>
                                        <div class="col-md-7">
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
                            <div class="col-xl-8 col-lg-12">
                                <span class="text-danger font-weight-600 mb-10 d-block">Items*<?php if(1==2){ ?><a class="float-right" href="javascript:void(0);"><i class="fa fa-plus mr-5"></i>Add Item</a><?php } ?></span>
                                <input type="text" class="d-none" name="items" v-model="salesorder.items" required data-parsley-required-message="Atleast 1 item is required."/>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-vcenter">
                                        <thead class="thead-light">
                                        <tr class="font-11 bg-gray-lighter">
                                            <th>Item Details</th>
                                            <th class="w-150p text-center">Quantity</th>
                                            <th class="w-130p text-right">Rate</th>
                                            <th class="w-150p text-right">Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(single,index) in salesorder.items">
                                            <th>{{ single.title }}</th>
                                            <td class="text-right">
                                                <b-input-group class="w-150p text-right">
                                                    <b-input-group-prepend>
                                                        <b-btn variant="secondary" @click="qtyDecrement(index)">-</b-btn>
                                                    </b-input-group-prepend>

                                                    <b-form-input type="text" class="text-center" min="1" v-model="single.quantity"></b-form-input>

                                                    <b-input-group-append>
                                                        <b-btn variant="secondary" @click="qtyIncrement(index)">+</b-btn>
                                                    </b-input-group-append>
                                                </b-input-group>
                                            </td>
                                            <td class="text-right"><input type="text" class="form-control text-right border-0" v-model="single.rate" /></td>
                                            <td class="text-right">{{ single.rate * single.quantity }}</td>
                                        </tr>
                                        <tr v-if="!salesorder.items.length">
                                            <td colspan="4" class="text-center">No Item added</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"><vue-multiselect @select="onItemSelect" id="ajax" label="value" track-by="itemId" placeholder="Type to search item by Name or SKU" open-direction="bottom" :options="items" :multiple="false" :searchable="true" :loading="itemLoading" :internal-search="false" :clear-on-select="true" :close-on-select="true" :options-limit="300" :limit="3" :max-height="600" :show-no-results="false" :hide-selected="true" @search-change="queryItems"></vue-multiselect></td>
                                        </tr>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-right">Sub Total</th>
                                            <td class="text-right">{{ salesorder.subTotal }}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="text-right">Tax ({{ masters.taxRate }}%)</th>
                                            <td class="text-right">{{ salesorder.taxTotal }}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="text-right">Total</th>
                                            <td class="text-right">{{ salesorder.grandTotal }}</td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="block">
                    <div class="block-content block-content-full">
                        <a href="#" @click.prevent="handleSubmit" class="btn btn-light btn-noborder">Transfer and Receive</a>
                        <a href="#" @click.prevent="cancel" class="btn btn-white btn-noborder">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
