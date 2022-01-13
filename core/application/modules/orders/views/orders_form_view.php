<?php
$code = 'salesorder';
?>
<div id="<?php echo $code; ?>-form" class="row" v-cloak>
    <form id="frm-<?php echo $code; ?>">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="block">
                        <div class="block-content block-content-full">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <label for="<?php echo $code; ?>-customer-id" class="col-sm-3 col-form-label text-danger">Customer * <b-button v-b-modal.add-customer-modal size="xs" class="p-0" variant="link"><i class="fa fa-plus" title="New Customer"></i> </b-button></label>
                                                <div class="col-sm-9">
                                                    <vue-multiselect id="<?php echo $code; ?>-customer-id" @select="onCustomerSelected" label="value" track-by="id" placeholder="Type to search Customers" open-direction="bottom" :options="masters.customers" v-model="customer" :multiple="false" :searchable="true" required></vue-multiselect>
                                                    <?php echo get_text(['id'=>$code.'-customer-id-hidden','title'=>'Customer','class'=>'d-none','attribute'=>'required readonly','vue_model'=>$code.'.customerId'],'text',true); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if(1==2){ ?>
                                            <div class="col-md-12">
                                                <div class="col-md-3">&nbsp;</div>
                                                <div class="col-md-9">

                                                </div>
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
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6"><?php echo get_text(['id'=>$code.'-order-no','title'=>'Sales Order#','attribute'=>'required readonly','vue_model'=>$code.'.orderNo']); ?></div>
                                        <div class="col-md-6">&nbsp;</div>
                                        <div class="col-md-6"><?php echo get_text(['id'=>$code.'-reference-no','title'=>'Reference#','attribute'=>'','vue_model'=>$code.'.referenceNo']); ?></div>
                                        <div class="col-md-6">&nbsp;</div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label for="salesorder-date" class="col-sm-3 col-form-label">Date</label>
                                                <div class="col-sm-9">
                                                    <vuejs-datepicker :format="dtFormat" :bootstrap-styling="true" v-model="<?php echo $code; ?>.date"></vuejs-datepicker>
                                                </div>
                                            </div>
                                            <?php //echo get_text(['id'=>$code.'-date','title'=>'Date','attribute'=>'','vue_model'=>$code.'.date']); ?>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label for="salesorder-expected-delivery-date" class="col-sm-3 col-form-label">Expected Delivery Date</label>
                                                <div class="col-sm-9">
                                                    <vuejs-datepicker :format="dtFormat" :bootstrap-styling="true" v-model="<?php echo $code; ?>.expectedDeliveryDate"></vuejs-datepicker>
                                                </div>
                                            </div>
                                            <?php //echo get_text(['id'=>$code.'-expected-delivery-date','title'=>'Expected Delivery Date','attribute'=>'','vue_model'=>$code.'.expectedDeliveryDate']); ?>
                                        </div>
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
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6"><?php echo get_select(['id'=>$code.'-warehouse-id','title'=>'Warehouse','attribute'=>'required','vue_model'=>$code.'.warehouseId','vue_for'=>'masters.warehouses']); ?></div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label for="salesorder-salesperson-id" class="col-sm-3 col-form-label">Salesperson&nbsp;<b-button v-b-modal.add-salesperson-modal size="xs" class="p-0" variant="link"><i class="fa fa-plus"></i> </b-button></label>
                                                <div class="col-sm-9">
                                                    <select name="salesorder_salesperson_id" id="salesorder-salesperson-id" class="form-control" v-model="<?php echo $code.'.salesPersonId'; ?>">
                                                        <option v-for="single in masters.salespersons" :value="single.id">{{ single.value }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if(1==2){ ?><div class="col-md-6"><?php echo get_select(['id'=>$code.'-delivery-method','title'=>'Delivery Method','attribute'=>'','vue_model'=>$code.'.deliveryMethodId']); ?></div><?php } ?>
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
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6"><?php echo get_text(['id'=>$code.'-freight-cost','title'=>'Freight Cost','attribute'=>'v-float','vue_model'=>$code.'.freightTotal']); ?></div>
                                        <div class="col-md-6"><?php echo get_text(['id'=>$code.'-duty-cost','title'=>'Duty Cost','attribute'=>'v-float','vue_model'=>$code.'.dutyTotal']); ?></div>
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
                                <div class="col-md-12">
                                    <span class="text-danger font-weight-600 mb-10 d-block">Items*<?php if(1==2){ ?><a class="float-right" href="javascript:void(0);"><i class="fa fa-plus mr-5"></i>Add Item</a><?php } ?></span>
                                    <input type="text" class="d-none" name="items" v-model="<?php echo $code; ?>.items" required data-parsley-required-message="Atleast 1 item is required."/>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-vcenter">
                                            <thead class="thead-light">
                                            <tr class="font-11 bg-gray-lighter">
                                                <th>Item Details</th>
                                                <th class="w-150p">Unit</th>
                                                <th class="w-150p text-center">Unit Quantity</th>
                                                <th class="w-130p text-center">Unit Rate</th>
                                                <th class="w-150p text-center">Quantity</th>
                                                <th class="w-150p text-right">Rate</th>
                                                <th class="w-150p text-right">Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="(single,index) in <?php echo $code; ?>.items">
                                                <td>{{ single.title }}</td>
                                                <td>
                                                    <select class="form-control" v-model="single.saleUnitId">
                                                        <option v-for="price in single.prices" :value="price.unitId">{{ getUnitLabel(price.unitId) }}</option>
                                                    </select>
                                                </td>
                                                <td class="text-right">
                                                    <b-input-group class="w-150p text-right">
                                                        <b-form-input type="text" class="text-center" v-model.number="single.unitQuantity" v-float></b-form-input>
                                                    </b-input-group>
                                                </td>
                                                <td class="text-center">{{ single.unitRate }}</td>
                                                <td class="text-center">{{ single.quantity }}</td>
                                                <td class="text-right">
                                                    <b-input-group class="w-150p text-right">
                                                        <b-form-input type="text" class="form-control text-right border-0" v-model.number="single.rate" v-float></b-form-input>
                                                    </b-input-group>
                                                </td>
                                                <td class="text-right">{{ single.rate * single.quantity | toThreeDecimal }}</td>
                                            </tr>
                                            <tr v-if="!<?php echo $code; ?>.items.length">
                                                <td colspan="7" class="text-center">No Item added</td>
                                            </tr>
                                            <tr>
                                                <td colspan="7"><vue-multiselect @select="onItemSelect" id="ajax" label="value" track-by="itemId" placeholder="Type to search item by Name or SKU" open-direction="bottom" :options="items" :multiple="false" :searchable="true" :loading="itemLoading" :internal-search="false" :clear-on-select="true" :close-on-select="true" :options-limit="300" :limit="3" :max-height="600" :show-no-results="false" :hide-selected="true" @search-change="queryItems"></vue-multiselect></td>
                                            </tr>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th colspan="6" class="text-right">Sub Total</th>
                                                <td class="text-right">{{ <?php echo $code; ?>.subTotal | toThreeDecimal }}</td>
                                            </tr>
                                            <tr>
                                                <th colspan="6" class="text-right">{{ masters.tax.title }} ({{ masters.tax.rate }}%)</th>
                                                <td class="text-right">{{ <?php echo $code; ?>.taxTotal | toThreeDecimal }}</td>
                                            </tr>
                                            <tr v-if="getOverheads()>0">
                                                <th colspan="6" class="text-right">Overheads</th>
                                                <td class="text-right">{{ getOverheads() | toThreeDecimal }}</td>
                                            </tr>
                                            <tr>
                                                <th colspan="6" class="text-right">Total</th>
                                                <td class="text-right">{{ <?php echo $code; ?>.grandTotal | toThreeDecimal }}</td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if(1==2){ ?>
                <div class="col-md-12">
                    <div class="block">
                        <div class="block-content block-content-full">
                            <div class="row">
                                <div class="col-xl-8 col-lg-12">
                                    <span class="text-danger font-weight-600 mb-10 d-block">Items*<?php if(1==2){ ?><a class="float-right" href="javascript:void(0);"><i class="fa fa-plus mr-5"></i>Add Item</a><?php } ?></span>
                                    <input type="text" class="d-none" name="items" v-model="<?php echo $code; ?>.items" required data-parsley-required-message="Atleast 1 item is required."/>
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
                                            <tr v-for="(single,index) in <?php echo $code; ?>.items">
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
                                            <tr v-if="!<?php echo $code; ?>.items.length">
                                                <td colspan="4" class="text-center">No Item added</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4"><vue-multiselect @select="onItemSelect" id="ajax" label="value" track-by="itemId" placeholder="Type to search item by Name or SKU" open-direction="bottom" :options="items" :multiple="false" :searchable="true" :loading="itemLoading" :internal-search="false" :clear-on-select="true" :close-on-select="true" :options-limit="300" :limit="3" :max-height="600" :show-no-results="false" :hide-selected="true" @search-change="queryItems"></vue-multiselect></td>
                                            </tr>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-right">Sub Total</th>
                                                <td class="text-right">{{ <?php echo $code; ?>.subTotal }}</td>
                                            </tr>
                                            <tr>
                                                <th colspan="3" class="text-right">Tax ({{ masters.taxRate }}%)</th>
                                                <td class="text-right">{{ <?php echo $code; ?>.taxTotal }}</td>
                                            </tr>
                                            <tr>
                                                <th colspan="3" class="text-right">Total</th>
                                                <td class="text-right">{{ <?php echo $code; ?>.grandTotal }}</td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <div class="col-md-12">
                    <div class="block">
                        <div class="block-content block-content-full">
                            <a href="#" @click.prevent="handleDraftSubmit" class="btn btn-light btn-noborder">Save as Draft</a>
                            <a href="#" @click.prevent="handleConfirmedSubmit" class="btn btn-primary btn-noborder">Save &amp Confirm</a>
                            <a href="#" @click.prevent="cancel" class="btn btn-white btn-noborder">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <add-salesperson></add-salesperson>
    <add-customer v-on:customer-updated="onNewCustomer" @cancel="onNewCustomerCancel" mode="add"></add-customer>
</div>
<script type="text/x-template" id="add-salesperson-template">
    <div>
        <b-modal id="add-salesperson-modal" size="lg" @ok="handleAddSalesPerson" title="Add Sales Person">
            <form id="frm-add-salesperson" data-parsley-validate="true" @submit.prevent="handleAddSalesPerson">
                <b-row>
                    <b-col>
                        <b-form-group
                                label="Name"
                                label-for="salesperson-name"
                        >
                            <b-form-input
                                    id="salesperson-name"
                                    v-model="salesperson.name"
                                    required
                                    data-parsley-required-message="Name is required"
                            ></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group
                                label="Email Address"
                                label-for="salesperson-email"
                        >
                            <input
                                    type="email"
                                    class="form-control"
                                    id="salesperson-email"
                                    v-model="salesperson.email"
                                    required
                                    data-parsley-required-message="Email Address is required"
                                    ref="email"
                            />
                        </b-form-group>
                    </b-col>
                </b-row>
            </form>
        </b-modal>
    </div>
</script>
<script type="text/x-template" id="add-customer-template">
    <?php $code = 'customer'; ?>
    <div>
        <b-modal id="add-customer-modal" size="xl" hide-footer title="Add Customer">
            <form id="frm-add-customer" data-parsley-validate="true" @submit.prevent="handleSubmit">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="block">
                                <div class="block-content block-content-full">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12"><?php echo get_text(['id'=>$code.'-customer-id','title'=>'Customer ID','attribute'=>'readonly','vue_model'=>$code.'.customerId']); ?></div>
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        <label class="col-md-<?php echo LABEL_COLUMNS; ?> col-form-label" for="<?php echo $code.'-salutation'; ?>">Primary Contact</label>
                                                        <div class="col-md-<?php echo TEXT_COLUMNS; ?> form-inline">
                                                            <?php echo get_select(['id'=>$code.'-salutation','title'=>'Salutation','class'=>'mr-2','attribute'=>'','vue_model'=>$code.'.salutation','vue_for'=>'salutations'],[],'value','id',true); ?>
                                                            <?php echo get_text(['id'=>$code.'-first-name','title'=>'First Name','placeholder'=>'First Name','class'=>'mr-2','attribute'=>'','vue_model'=>$code.'.firstName'],'text',true); ?>
                                                            <?php echo get_text(['id'=>$code.'-last-name','title'=>'Last Name','placeholder'=>'Last Name','attribute'=>'','vue_model'=>$code.'.lastName'],'text',true); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12"><?php echo get_text(['id'=>$code.'-company-name','title'=>'Company Name','attribute'=>'required @blur="onCompanyName"','vue_model'=>$code.'.companyName']); ?></div>
                                                <div class="col-md-12"><?php echo get_text(['id'=>$code.'-display-name','title'=>'Contact Display Name','attribute'=>'required','vue_model'=>$code.'.displayName']); ?></div>
                                                <div class="col-md-12"><?php echo get_text(['id'=>$code.'-email','title'=>'Contact Email','attribute'=>'','vue_model'=>$code.'.email'],'email'); ?></div>
                                                <div class="col-md-12"><?php echo get_text(['id'=>$code.'-phone','title'=>'Contact Phone','attribute'=>'','vue_model'=>$code.'.phone']); ?></div>
                                                <?php if(1==2){ ?>
                                                    <div class="col-md-12"><?php echo get_text(['id'=>$code.'-designation','title'=>'Designation','attribute'=>'','vue_model'=>$code.'.designation']); ?></div>
                                                    <div class="col-md-12"><?php echo get_text(['id'=>$code.'-department','title'=>'Department','attribute'=>'','vue_model'=>$code.'.department']); ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="block">
                                <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#tab-tax-payment-details">Tax &amp; Payment Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#tab-address">Address</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#tab-notes">Notes</a>
                                    </li>
                                </ul>
                                <div class="block-content tab-content">
                                    <div class="tab-pane active" id="tab-tax-payment-details" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-7"><?php echo get_select(['id'=>$code.'-currency','title'=>'Currency','class'=>'mr-2','attribute'=>'required','vue_model'=>$code.'.currency','vue_for'=>'currencies'],[],'value','id'); ?></div>
                                            <?php if(1==2){ ?>
                                                <div class="col-md-7"><?php echo get_select(['id'=>$code.'-price-list','title'=>'Group','class'=>'mr-2','attribute'=>'','vue_model'=>$code.'.priceListId','vue_for'=>'priceLists'],[],'value','id'); ?></div>
                                                <div class="col-md-7"><?php echo get_select(['id'=>$code.'-payment-terms','title'=>'Payment Terms','class'=>'mr-2','attribute'=>'','vue_model'=>$code.'.paymentTerm','vue_for'=>'paymentTerms'],[],'value','id'); ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab-address" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-12"><h6>Billing Address</h6></div>
                                                    <div class="col-md-12"><?php echo get_text(['id'=>$code.'-billing-attention','title'=>'Attention','attribute'=>'','vue_model'=>$code.'.billing.attention']); ?></div>
                                                    <div class="col-md-12"><?php echo get_text(['id'=>$code.'-billing-address-1','title'=>'Address 1','attribute'=>'','vue_model'=>$code.'.billing.address1']); ?></div>
                                                    <div class="col-md-12"><?php echo get_text(['id'=>$code.'-billing-address-2','title'=>'Address 2','attribute'=>'','vue_model'=>$code.'.billing.address2']); ?></div>
                                                    <div class="col-md-12"><?php echo get_select(['id'=>$code.'-billing-country','title'=>'Country','attribute'=>'','vue_model'=>$code.'.billing.country','vue_for'=>'masters.countries']); ?></div>
                                                    <div class="col-md-12"><?php echo get_select(['id'=>$code.'-billing-state','title'=>'State','attribute'=>'','vue_model'=>$code.'.billing.state','vue_for'=>'masters.states.billing']); ?></div>
                                                    <div class="col-md-12"><?php echo get_text(['id'=>$code.'-billing-city','title'=>'City','attribute'=>'','vue_model'=>$code.'.billing.city']); ?></div>
                                                    <div class="col-md-12"><?php echo get_text(['id'=>$code.'-billing-zip-code','title'=>'Zip Code','attribute'=>'','vue_model'=>$code.'.billing.zipCode']); ?></div>
                                                    <div class="col-md-12"><?php echo get_text(['id'=>$code.'-billing-phone','title'=>'Phone','attribute'=>'','vue_model'=>$code.'.billing.phone']); ?></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-12"><h6>Shipping Address<a @click.prevent="copyBillingAddress" href="#" class="float-right font-12">Copy from billing address</a></h6></div>
                                                    <div class="col-md-12"><?php echo get_text(['id'=>$code.'-shipping-attention','title'=>'Attention','attribute'=>'','vue_model'=>$code.'.shipping.attention']); ?></div>
                                                    <div class="col-md-12"><?php echo get_text(['id'=>$code.'-shipping-address-1','title'=>'Address 1','attribute'=>'','vue_model'=>$code.'.shipping.address1']); ?></div>
                                                    <div class="col-md-12"><?php echo get_text(['id'=>$code.'-shipping-address-2','title'=>'Address 2','attribute'=>'','vue_model'=>$code.'.shipping.address2']); ?></div>
                                                    <div class="col-md-12"><?php echo get_select(['id'=>$code.'-shipping-country','title'=>'Country','attribute'=>'','vue_model'=>$code.'.shipping.country','vue_for'=>'masters.countries']); ?></div>
                                                    <div class="col-md-12"><?php echo get_select(['id'=>$code.'-shipping-state','title'=>'State','attribute'=>'','vue_model'=>$code.'.shipping.state','vue_for'=>'masters.states.shipping']); ?></div>
                                                    <div class="col-md-12"><?php echo get_text(['id'=>$code.'-shipping-city','title'=>'City','attribute'=>'','vue_model'=>$code.'.shipping.city']); ?></div>
                                                    <div class="col-md-12"><?php echo get_text(['id'=>$code.'-shipping-zip-code','title'=>'Zip Code','attribute'=>'','vue_model'=>$code.'.shipping.zipCode']); ?></div>
                                                    <div class="col-md-12"><?php echo get_text(['id'=>$code.'-shipping-phone','title'=>'Phone','attribute'=>'','vue_model'=>$code.'.shipping.phone']); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab-notes" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <?php echo get_textarea(['id'=>$code.'-notes','title'=>'Notes','attribute'=>'','vue_model'=>$code.'.notes']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="block">
                                <div class="block-content block-content-full">
                                    <a href="#" @click.prevent="handleSubmit" class="btn btn-primary btn-noborder">Save</a>
                                    <a href="#" @click.prevent="handleCancel" class="btn btn-white btn-noborder">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </b-modal>
    </div>
</script>
