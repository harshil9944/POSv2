<script type="text/x-template" id="customer-details-template">
    <div id="customer-details-block">
        <div class="block">
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-sm">
                                <b-button class="mb-10" variant="danger" :href="customer.backUrl">Back</b-button>
                            </div>
                            <div class="col-sm text-right">
                                <a :href="editUrl" class="btn btn-primary btn-sm"> <i class="fas fa-edit te"></i></a>
                            </div>
                            <!-- <div class="col-sm text-left">
                                <a :href="pdfUrl" target='_blank' class="btn btn-primary btn-sm"> <i class="fas fa-edit te"></i></a>
                            </div> -->
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <p><small>Customer ID</small><br/><span class="font-weight-600">{{ customer.customerId}}</span></p>
                            </div>
                            <div class="col-sm">
                                <p><small>Display Name</small><br/><span class="font-weight-600">{{ customer.displayName }}</span></p>
                            </div>
                            <div class="col-sm">
                                <p><small>Name</small><br/><span class="font-weight-600">{{ customerName }}</span></p>
                            </div>
                            <div class="col-sm">
                                <p><small>Group</small><br/><span class="font-weight-600">{{ customerGroup }}</span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <p><small>Email</small><br/><span class="font-weight-600">{{ customer.email ??'-'}}</span></p>
                            </div>
                            <div class="col-sm">
                                <p><small>Phone</small><br/><span class="font-weight-600">{{ customer.phone ?? '-' }}</span></p>
                            </div>
                            <div class="col-sm">
                                <p><small>Member Number</small><br/><span class="font-weight-600">{{ customer.memberNumber ??'-' }}</span></p>
                            </div>
                            <div class="col-sm">
                                <p><small>Fully Vaccinated</small><br/><span class="font-weight-600">{{ customerFullVaccinated }}</span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <p><small>Total Orders</small><br/><span class="font-weight-600">{{ closed }}</span></p>
                            </div>
                            <div class="col-sm">
                                <p><small>DineIn Orders</small><br/><span class="font-weight-600">{{ dineOrder }}</span></p>
                            </div>
                            <div class="col-sm">
                                <p><small>PickUp Orders</small><br/><span class="font-weight-600">{{ pickUpOrder }}</span></p>
                            </div>
                            <div class="col-sm">
                                <p><small>Cancelled Orders</small><br/><span class="font-weight-600">{{ cancelled }}</span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <p><small>Earnings</small><br/><span class="font-weight-600">{{ totalEarnings | toTwoDecimal | beautifyUSCurrency }}</span></p>
                            </div>
                            <div class="col-sm">
                                <p><small>Average Order</small><br/><span class="font-weight-600">{{ avgOrder | toTwoDecimal |beautifyUSCurrency }}</span></p>
                            </div>
                            <div class="col-sm">
                                <p><small>Total Discount</small><br/><span class="font-weight-600">{{ totalDiscount | toTwoDecimal | beautifyUSCurrency  }}</span></p>
                            </div>
                            <div class="col-sm">
                                <p><small>Tip Total</small><br/><span class="font-weight-600">{{ totalTip | toTwoDecimal | beautifyUSCurrency  }}</span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <p><small>Average Visited</small><br/><span class="font-weight-600">{{ avgVisited }}</span></p>
                            </div>
                            <div class="col-sm">
                                <p><small>Total Refund</small><br/><span class="font-weight-600">{{ refundTotal | toTwoDecimal | beautifyUSCurrency  }}</span></p>
                            </div>
                            <div class="col-sm">
                                <p><small>First Order</small><br/><span class="font-weight-600">{{ firstOrder | beautifyDateTime }}</span></p>
                            </div>
                            <div class="col-sm">
                                <p><small>Last Order</small><br/><span class="font-weight-600">{{ lastOrder | beautifyDateTime }}</span></p>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4" data-toggle="appear">
            <div class="col-md-6">
                <div class="block block-fx-shadow block-rounded block-bordered">
                    <div class="block-header block-header-default border-b">
                        <h3 class="block-title">
                            Sales <small>Last 12 Months</small>
                        </h3>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="pull-all pt-50">
                            <canvas class="js-last-12-sales"></canvas>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="row items-push text-center">
                            <div class="col-6">
                                <div class="font-size-h4 font-w600">{{ yearCount }}</div>
                                <div class="font-size-sm font-w600 text-uppercase text-muted">Total sales</div>
                            </div>
                            <div class="col-6">
                                <div class="font-size-h4 font-w600">{{ avgYearCount }}</div>
                                <div class="font-size-sm font-w600 text-uppercase text-muted">Average Sales</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="block block-fx-shadow block-rounded block-bordered">
                    <div class="block-header block-header-default border-b">
                        <h3 class="block-title">
                            Earnings <small>Last 12 Months</small>
                        </h3>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="pull-all pt-50">
                            <canvas class="js-last-12-earnings"></canvas>
                        </div>
                    </div>
                    <div class="block-content bg-white">
                        <div class="row items-push text-center">
                            <div class="col-6">
                                <div class="font-size-h4 font-w600">{{ yearEarnings | toTwoDecimal | beautifyUSCurrency }}</div>
                                <div class="font-size-sm font-w600 text-uppercase text-muted">Total Earnings</div>
                            </div>
                            <div class="col-6">
                                <div class="font-size-h4 font-w600">{{ avgYearEarnings | toTwoDecimal | beautifyUSCurrency }}</div>
                                <div class="font-size-sm font-w600 text-uppercase text-muted">Average Earnings</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4" data-toggle="appear">
            <div class="col-12">
                <div class="block block-rounded block-bordered">
                    <div class="block-header block-header-default border-b">
                        <h3 class="block-title">
                            Time of Day
                        </h3>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="pull-all pt-50">
                            <canvas class="js-time-of-day" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block">
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-12">
                        <b-card no-body>
                            <b-tabs v-model="tabIndex" pills card>
                                <b-tab title="Past Orders">
                                    <div class="table-responsive mt-10">
                                        <table class="table table-borderless table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-left">Date</th>
                                                    <th class="text-center">Type</th>
                                                    <th class="text-left">Item</th>
                                                    <th class="text-center">Total</th>
                                                    <th class="text-center">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr  v-for="(single,index) in customerOrders">
                                                    <td><a href="#" @click.prevent="handleOrderDetails(single.id)">{{ single.date | beautifyDate }}</a></td>
                                                    <td class="text-center">{{  single.type }}</td>
                                                    <td class="text-left">{{ getItems(single.items) }}</td>
                                                    <td class="text-center">{{ single.grandTotal | beautifyCurrency }}</td>
                                                    <td class="text-center text-earth">{{ single.orderStatus }}</td>
                                                </tr>
                                                <tr v-if="!customerOrders.length">
                                                    <td colspan="6" class="text-center">No Order</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </b-tab>
                                <b-tab title="Order Items">
                                    <div class="table-responsive mt-10">
                                        <table class="table table-borderless table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Items</th>
                                                    <th class="text-right">Sold Count</th>
                                                </tr>
                                            </thead>
                                            <tbody v-if="customerItems">
                                                <tr v-for="single in customerItems">
                                                    <td>{{single.title}}</td>
                                                    <td class="text-right">
                                                        <span class="badge badge-primary">{{single.total_quantity | toNoDecimal}}</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tbody v-else>
                                                <tr>
                                                    <td colspan="2" class="text-center">No Data</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </b-tab>
                                <b-tab title="Addresses">
                                    <div class="row">
                                        <div class="col-md-12" >
                                            <div class="row">
                                                <div class="col-12">
                                                    <a  href="javascript:void(0)" class="btn btn-sm btn-primary mr-2 mb-2 pull-right" @click="addAddress">Add Address</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div v-if="customerAddresses.length" v-for="(single,index) in customerAddresses">
                                                <div class="block-content ">
                                                    <div class="block block-rounded block-bordered">
                                                        <div class="block-content bg-gray-light block-content-full">
                                                            <span class="font-w600">{{single.title}}</span><a href="javascript:void(0)" class="pull-right" @click="deleteAddress(single.id)"><i class="fa fa-trash text-danger"></i></a><a class="pull-right text-primary mr-3" href="javascript:void(0)" @click="handleEditAddress(index)"><i class="fa fa-edit"></i></a>
                                                            <br>
                                                            <span >{{getAddressTitle(single)}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="!customerAddresses.length" >
                                                <div class="block-content">
                                                    <div class="block block-rounded block-bordered text-center">
                                                        <div class="block-content block-content-full">
                                                            <h5 class="text-center">No Address</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </b-tab>
                            </b-tabs>
                        </b-card>
                    </div>
                </div>
            </div>
        </div>
        <order-details></order-details>
        <edit-address  @updated="onCustomerUpdated"></edit-address>
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
<script type="text/x-template" id="edit-address-template">
    <div>
        <b-modal no-fade centered id="edit-address-modal" size="xl" hide-header hide-footer body-class="p-0" v-cloak>
            <div id="edit-address-block" class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">{{ addressTitle}}</h3>
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
