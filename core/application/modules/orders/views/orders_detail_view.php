<b-modal id="order-details-modal" size="xl" hide-header hide-footer body-class="p-0" v-cloak>
    <div class="block block-themed block-transparent mb-0">
        <div class="block-header bg-primary-dark">
            <h3 class="block-title">Order #{{ modal.obj.orderNo }}</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" @click="$bvModal.hide('order-details-modal');" aria-label="Close">
                    <i class="si si-close"></i>
                </button>
            </div>
        </div>
        <div class="row bg-black-op-10 p-3 mx-0">
            <div class="col text-right">
                <button class="btn btn-danger ml-5" @click="handleDownloadPdf" title="Download PDF"><i class="fa fa-file-pdf-o"></i></button>
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
                <table class="table table-bordered table-hover table-vcenter">
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
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="font-w600 text-right">Sub Total</td>
                            <td class="text-right">{{ modal.obj.subTotal |beautifyCurrency }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="font-w600 text-right">Tax ({{ modal.obj.taxRate }}%)</td>
                            <td class="text-right">{{ modal.obj.taxTotal | beautifyCurrency }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="font-w600 text-right">Discount</td>
                            <td class="text-right">{{ modal.obj.discount | beautifyCurrency }}</td>
                        </tr>
                        <tr class="table-warning">
                            <td colspan="4" class="font-w700 text-uppercase text-right">Grand Total</td>
                            <td class="font-w700 text-right">{{ modal.obj.grandTotal | beautifyCurrency }}</td>
                        </tr>
                    </tfoot>
                </table>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-8">
                           <div class="table-responsive">    
                                <h6 class="mb-2">Payment History</h6>
                                <table class="table table-bordered font-12">
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
                                            <td>{{ getTotalPaid('amount') | beautifyCurrency}}</td>
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
            </div>
        </div>
    </div>
</b-modal>
