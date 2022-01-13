<customers-import-export></customers-import-export>
<b-modal v-cloak id="customer-address-modal"  size="xl" hide-header hide-footer body-class="p-0" v-cloak>
    <div id="info-customer-block" class="block block-themed block-transparent mb-0">
        <div class="block-header bg-primary-dark">
            <h3 class="block-title">{{ customer.displayName }}</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" @click="handleCloseModal" aria-label="Close">
                    <i class="si si-close"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="block-content block-content-full min-height-100">
        <div class="row">
            <div class="col-md-3">
                <div class="block">
                    <div class="block-content pr-0">
                        <div class="list-group push">
                            <a @click.prevent="modal.activeTab='basic'" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" :class="modal.activeTab==='basic' ? 'active':''" href="javascript:void(0)">Basic Info</a>
                            <a @click.prevent="modal.activeTab='history'" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" :class="modal.activeTab==='history' ? 'active':''" href="javascript:void(0)">Order History</a>
                            <a @click.prevent="modal.activeTab='addresses'" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" :class="modal.activeTab==='addresses' ? 'active':''" href="javascript:void(0)">Addresses</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="block">
                    <div class="block-content pl-0">
                        <div class="border-black-op rounded mnh-100p">
                            <div class="row">
                                <div v-if="modal.activeTab==='basic'" class="col-md-12 py-20 px-40">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <h6 class="mb-5">Basic Information</h6>
                                                <p class="mb-4 font-weight-500 font-16"><small>Name</small>: {{ customer.displayName }}</p>
                                                <p class="mb-4 font-weight-500 font-16"><small>Email</small> : {{ customer.email?customer.email:'-' }}</p>
                                                <p class="mb-4 font-weight-500 font-16"><small>Mobile</small> : {{ customer.phone }}</p>
                                                <p v-if="isCustomFields('memberNumber')" class="mb-4 font-weight-500 font-16"><small>Member Number</small> : {{ customer.memberNumber }}</p>
                                                <p v-if="isCustomFields('fullVaccinated')" class="mb-4 font-weight-500 font-16"><small>Fully Vaccinated</small> : {{ customer.fullVaccinated==='0' ? 'No' : 'Yes' }}</p>
                                                <p v-if="allowCustomerGroup" class="mb-4 font-weight-500 font-16"><small>Customer Group</small> : {{ groupTitle }}</p>
                                            </div>
                                            <div class="col-md-2">
                                                <a :href="customerEditUrl" class="btn btn-primary btn-sm"> <i class="fas fa-edit te"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="modal.activeTab==='history'" class="col-md-12 py-20 px-40">
                                    <div class="col-md-12" >
                                        <h6 class="mb-2">Orders</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered table-vcenter">
                                                <thead>
                                                <tr>
                                                    <th class="text-left">Date</th>
                                                    <th>Type</th>
                                                    <th>Item</th>
                                                    <th class="text-right">Total</th>
                                                    <th class="text-center">Status</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <tr  v-for="(single,index) in order">
                                                    <td ><a href="#" @click.prevent="handleOrderDetails(single.id)">{{ single.date | beautifyDate }}</a></td>
                                                        <td>{{  single.type }}</td>
                                                        <td>{{ getItems(single.items) }}</td>
                                                        <td class="text-right">{{ single.grandTotal | beautifyCurrency }}</td>
                                                        <td class="text-center">{{ single.orderStatus }}</td>
                                                    </tr>
                                                    <tr v-if="!order.length">
                                                        <td colspan="6" class="text-center">No Order</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="modal.activeTab==='addresses'" class="col-md-12 py-20 px-40">
                                    <div class="row">
                                        <div class="col-md-12" >
                                            <div class="row">
                                                <div class="col-12">
                                                    <a  href="javascript:void(0)" class="btn btn-sm btn-primary mr-2 mb-2 pull-right" @click="addAddress">Add Address</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div v-if="addressList.length" v-for="(single,index) in addressList">
                                                <div class="block-content bg-gray-light">
                                                    <div class="block block-rounded block-bordered">
                                                        <div class="block-content block-content-full">
                                                            <span class="font-w600">{{single.title}}</span><a href="javascript:void(0)" class="pull-right" @click="deleteAddress(single.id)"><i class="fa fa-trash text-danger"></i></a><a class="pull-right text-primary mr-3" href="javascript:void(0)" @click="handleEditAddress(index)"><i class="fa fa-edit"></i></a>
                                                            <br>
                                                            <span >{{getAddressTitle(single)}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="!addressList.length" >
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</b-modal>
<edit-address  @updated="onCustomerUpdated"></edit-address>
<order-details></order-details>

