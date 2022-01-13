<script type="text/x-template" id="customers-import-export-template">
    <div class="col-12">
        <div class="block-header block-header-default">
            <div class="block-content block-content-full d-inline-block py-0">
                <!-- <button @click="handleImport" class="float-right btn btn-primary ml-3">Import</button> -->
                <a :href="exportUrl" target="_blank" class="float-right btn btn-primary">Export</a>
                <customers-import></customers-import>
            </div>
        </div>
    </div>
</script>
<script type="text/x-template" id="customers-import-template">
    <div>
        <b-modal no-fade :id="modal.id" size="md" hide-header hide-footer body-class="p-0" v-cloak>
            <div id="customers-import-modal-block" class="block block-themed block-transparent bg-gray-light mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">{{ modal.title }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" @click="hideModal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content bg-gray-light">
                    <div class="col-md-12">
                       <form id="frm-import-file" data-parsley-validate="true">
                            <div class="row d-flex">
                                <div class="col-md-12">
                                    <div class="block">
                                        <div class="block-content block-content-full">
                                            <b-form-file 
                                                accept=".xls,.xlsx"
                                                v-model="file.xlsx"
                                                required
                                                data-parsley-required-message="File is required">
                                            </b-form-file>
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-md-12">
                                    <div class="block">
                                        <div class="block-content block-content-full">
                                            <a href="#" @click.prevent="handleSubmit" class="btn btn-primary btn-no-border pull-center">Import</a>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</script>
<script type="text/x-template" id="edit-address-template">
    <div>
        <b-modal no-fade centered id="edit-address-modal" size="xl" hide-header hide-footer body-class="p-0" v-cloak>
            <div id="edit-address-block" class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Edit Address</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" @click="handleCloseModal(modal.id)" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content bg-gray-light">
                    <form id="frm-edit-address" data-parsley-validate="true">
                        <div class="row">
                            <div class="col-md-12">
                               <div class="block block-rounded block-bordered">
                                    <div class="block-content block-content-full">
                                        <div class="row">
                                            <div class="col-md-12"><?php echo get_text(['id'=>'address-title','title'=>'Title','attribute'=>'required','vue_model'=>'address.title']); ?></div>
                                            <div class="col-md-12"><?php echo get_text(['id'=>'address-address-1','title'=>'Address 1','attribute'=>'required','vue_model'=>'address.address1']); ?></div>
                                            <div class="col-md-12"><?php echo get_text(['id'=>'address-address-2','title'=>'Address 2','attribute'=>'','vue_model'=>'address.address2']); ?></div>
                                            <div class="col-md-12"><?php echo get_select(['id'=>'address-country','title'=>'Country','attribute'=>'disabled','vue_model'=>'address.countryId','vue_for'=>'masters.countries']); ?></div>
                                            <div class="col-md-12"><?php echo get_select(['id'=>'address-state','title'=>'State','attribute'=>'','vue_model'=>'address.stateId','vue_for'=>'masters.states']); ?></div>
                                            <div class="col-md-12"><?php echo get_select(['id'=>'address-city','title'=>'City','attribute'=>'','vue_model'=>'address.cityId','vue_for'=>'masters.cities']); ?></div>
                                            <div class="col-md-12"><?php echo get_text(['id'=>'address-zip-code','title'=>'Zip Code','attribute'=>'required','vue_model'=>'address.zipCode']); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="block block-rounded block-bordered">
                                    <div class="block-content block-content-full">
                                        <a href="javascript:void(0)" @click.prevent="handleSubmit" class="btn btn-primary btn-noborder">Save</a>
                                        <a href="javascript:void(0)" @click.prevent="handleCloseModal(modal.id)" class="btn btn-danger btn-noborder">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </b-modal>
    </div>
</script>
<script type="text/x-template" id="order-details-template">
    <b-modal id="order-details-modal" size="xl" hide-header hide-footer body-class="p-0" v-cloak>
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">{{ modal.obj.orderNo }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" @click="$bvModal.hide('order-details-modal');" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="row my-20">
                        <div class="col-4 font-weight-600 table-left">
                            <table class="table table-sm table-bordered table-vcenter">
                                <tr>
                                    <th class="text-right">Order No</th>
                                    <td>{{ modal.obj.orderNo }}</td>
                                </tr>
                                <tr>
                                    <th class="text-right">Date</th>
                                    <td>{{ modal.obj.date | beautifyDate }}</td>
                                </tr>
                                <tr>
                                    <th class="text-right">Status</th>
                                    <td>{{ modal.obj.orderStatus }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-4 font-weight-600 table-left">
                        </div>
                        <div class="col-4 font-weight-600 table-right">
                            <table class="table table-sm table-bordered table-vcenter">
                                <tr>
                                    <th class="text-right">Name</th>
                                    <td>{{ modal.obj.customer.displayName }}</td>
                                </tr>
                                <tr>
                                    <th class="text-right">Mobile</th>
                                    <td>{{ modal.obj.customer.phone }}</td>
                                </tr>
                                <tr>
                                    <th class="text-right">Email</th>
                                    <td>{{ modal.obj.customer.email }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="table-responsive push">
                        <table class="table table-bordered table-sm table-hover table-vcenter">
                            <thead>
                            <tr>
                                <th class="text-center" style="width: 60px;"></th>
                                <th>Items</th>
                                <th class="text-center" style="width: 90px;">Quantity</th>
                                <th class="text-right" style="width: 120px;">Unit</th>
                                <th class="text-right" style="width: 120px;">Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(single,index) in modal.obj.items">
                                <td class="text-center">{{ Number(index) + 1 }}</td>
                                <td>
                                    <span class="font-w600 mb-4">{{ single.title }}</span>
                                    <small v-if="hasAddons(single.addons)"><br/>{{ getAddons(single.addons) }}</small>
                                    <small v-if="single.selectedNotes.length"><br/>{{ getNotes(single.selectedNotes) }}</small>
                                    <small v-if="single.hasSpiceLevel"><br/>Spice:&nbsp;{{ single.spiceLevel }}</small>
                                    <small v-if="single.orderItemNotes.length"><br/>Note:&nbsp;{{ single.orderItemNotes }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-pill badge-primary">{{ single.quantity }}</span>
                                </td>
                                <td class="text-right">{{ single.rate | beautifyCurrency }}</td>
                                <td class="text-right">{{ single.amount | beautifyCurrency }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="font-w600 text-right">Sub Total</td>
                                <td class="text-right">{{ modal.obj.subTotal |beautifyCurrency }}</td>
                            </tr>
                            <tr v-if="allowGratuity && Number(modal.obj.gratuityTotal) !== 0">
                                <td colspan="4" class="font-w600 text-right">Gratuity ({{ modal.obj.gratuityRate }}%)</td>
                                <td class="text-right">{{ modal.obj.gratuityTotal | toTwoDecimal | beautifyCurrency  }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="font-w600 text-right">Tax ({{ modal.obj.taxRate }}%)</td>
                                <td class="text-right">{{ modal.obj.taxTotal | beautifyCurrency }}</td>
                            </tr>
                            <tr v-if="Number(modal.obj.discount) !== 0">
                                <td colspan="4" class="font-w600 text-right">Discount</td>
                                <td class="text-right">{{ modal.obj.discount | beautifyCurrency }}</td>
                            </tr>
                            <tr v-if="Number(modal.obj.tip) !== 0">
                                <td colspan="4" class="font-w600 text-right">Tip</td>
                                <td class="text-right">{{ modal.obj.tip | beautifyCurrency }}</td>
                            </tr>
                            <tr class="table-warning">
                                <td colspan="4" class="font-w700 text-uppercase text-right">Grand Total</td>
                                <td class="font-w700 text-right">{{ modal.obj.grandTotal | beautifyCurrency }}</td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-8">
                                   <div class="table-responsive">
                                       <h6 class="mb-2">Payment History</h6>
                                        <table class="table table-sm table-bordered font-12">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th class="font-w600 text-left">Date</th>
                                                    <th class="font-w600 text-left">Payment Method</th>
                                                    <th class="font-w600 text-left">Payment #</th>
                                                    <th class="font-w600 text-right">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody v-if="modal.obj.payments.length">
                                                <tr v-for="(single,index) in modal.obj.payments"  class="font-w600">
                                                    <td class="text-center">{{ Number(index) + 1 }}</td>
                                                    <td>{{ single.date | beautifyDate }}</td>
                                                    <td>{{ single.paymentMethodName }}</td>
                                                    <td>{{ single.orderNo }}</td>
                                                    <td class="text-right">{{ single.amount | beautifyCurrency }}</td>
                                                </tr>
                                            </tbody>
                                            <tbody v-else>
                                                <tr>
                                                    <td colspan="5" class="text-center font-w600">Payment Pending</td>
                                                </tr>
                                            </tbody>
                                            <tfoot v-if="modal.obj.payments.length">
                                                <tr class="font-w700 text-right">
                                                    <td colspan="4" >TOTAL</td>
                                                    <td>{{ getTotalPaid() | beautifyCurrency}}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="mb-2">Order Notes</h6>
                                    <table class="table">
                                        <tbody>
                                            <tr v-if="modal.obj.notes" class="font-14-w600">
                                                <td>{{ modal.obj.notes }}</td>
                                            </tr>
                                            <tr v-if="!modal.obj.notes">
                                                <td class="text-center font-w600">No Notes</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div v-if="modal.obj.refundPayments.length" class="col-md-12">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="mb-2">Refund Payment History</h6>
                                    <table class="table table-sm table-bordered font-12">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th class="font-w600 text-left">Payment Method</th>
                                                <th class="font-w600 text-right">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody >
                                            <tr v-for="(single,index) in modal.obj.refundPayments"  class="font-w600">
                                                <td class="text-center">{{ Number(index) + 1 }}</td>
                                                <td>{{ getPaymentMethodName(single.paymentMethodId) }}</td>
                                                <td class="text-right">{{ single.amount | beautifyCurrency }}</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="font-w700 text-right">
                                                <td colspan="2" >TOTAL</td>
                                                <td>{{ getRefundTotalPaid() | beautifyCurrency}}</td>
                                            </tr>
                                            <tr v-if="afterRefundGrandTotal > 0" class="font-w700 text-right">
                                                <td colspan="2" >After Refund Grand Total And Tip</td>
                                                <td>{{ afterRefundGrandTotal | beautifyCurrency }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
</script>