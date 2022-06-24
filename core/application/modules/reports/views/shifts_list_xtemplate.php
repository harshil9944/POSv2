<script type="text/x-template" id="report-shifts-template">
    <div id="report-shifts" class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="block-content block-content-full p-20 ">
                    <p class="mb-0 font-weight-700">Filters</p>
                    <div class="row no-gutters">
                        <div class="col-10">
                            <div class="row">
                                <div class="col-12 col-md-3">
                                    <date-range-picker
                                        class="d-inline-block"
                                        opens="right"
                                        v-model="filteredDateRange"
                                        :auto-apply="true"
                                    ></date-range-picker>
                               </div>
                                <div class="col-12 col-md-3 mr-1">
                                    <select  id="employeeId" class="form-control d-block mr-1" v-model="employeeId">
                                        <option value="" selected>Select employee</option>
                                        <option v-for="single in employees" :value="single.id">{{ single.name }}</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-4 mr-2 ml-2">
                                    <button class="btn btn-primary" @click="handleFilter"><i class="fa fa-search"></i>&nbsp;Filter</button>
                                    <button class="btn btn-danger ml-2" @click="handleClearFilter">Clear</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="btn-group float-right d-block" role="group">
                                <button type="button" class="btn btn-primary dropdown-toggle" id="additional-actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>Exports</button>
                                <div class="dropdown-menu" aria-labelledby="additional-actions" x-placement="bottom-start">
                                    <a  class="dropdown-item" href="javascript:void(0)" @click.prevent="handleExportPDF"></i>PDF</a>
                                    <a  class="dropdown-item" href="javascript:void(0)" @click.prevent="handleExportCSV"></i>CSV</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div id="block-report-list" class="block">
                <div class="block-content">
                    <b-table
                        id="item-list-table"
                        :items="reports"
                        :per-page="params.perPage"
                        :no-provider-paging="true"
                        :fields="params.fields"
                        v-if="reports.length">
                        <template slot="empName" slot-scope="{row,item}">
                        <a @click.prevent="handleViewSession(item.employeeId,item.sessionId)" href="#">{{ item.empName }}</a>
                        </template>
                        <template slot="totalOrder" slot-scope="row">
                            <span class="text-right">{{ row.value }}</span>
                        </template>
                        <template slot="startShift" slot-scope="{row,item}">
                            <span class="text-center">{{ item.startShift | beautifyDateTime }}</span>
                        </template>
                        <template slot="endShift" slot-scope="{row,item}">
                            <span class="text-center">{{ item.endShift | beautifyDateTime }}</span>
                        </template>
                        <template slot="status" slot-scope="row">
                            <span v-if="row.value=='Close'">{{ row.value }}</span>
                            <span v-if="row.value=='Open'" class="text-danger">{{ row.value }}</span>
                        </template>
                        <template slot="tip" slot-scope="row">
                            <span v-if="row.value>0" class="text-right">{{ row.value | toTwoDecimal | beautifyCurrency }}</span>
                            <span v-if="row.value<=0">0</span>
                        </template>
                    </b-table>
                    <b-pagination
                        @change="filterData"
                        :current-page="params.itemCurrentPage"
                        v-model="params.itemCurrentPage"
                        :total-rows="itemRows"
                        :per-page="params.perPage"
                        aria-controls="item-list-table"
                        align="center"
                        v-if="reports.length && itemRows>params.perPage"
                    ></b-pagination>
                    <h4 v-if="!reports.length" class="text-center">No Shifts</h4>
                </div>
            </div>
        </div>
        <b-modal no-fade centered id="session-summary-modal" size="xl" hide-header hide-footer body-class="p-0">
            <div id="session-summary-block" class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Close Shift</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" @click="$bvModal.hide('session-summary-modal');" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content bg-gray-light">
                    <div class="row d-flex">
                        <div class="col-md-12">
                            <div class="block">
                                <div class="block-content">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="table-responsive">
                                                <h6 class="mb-2"> Total Orders</h6>
                                                <table class="table table-bordered table-sm">
                                                    <tr>
                                                        <td>Orders Placed</td>
                                                        <td class="text-center">{{ session.ordersCount }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Orders Cancelled</td>
                                                        <td class="text-center">{{ session.cancelledOrdersCount }}</td>
                                                    </tr>
                                                    <tr v-if="Number(session.openOrdersCount)>0">
                                                        <td>Refunded Orders</td>
                                                        <td class="text-center">{{ session.refundedOrdersCount }}</td>
                                                    </tr>
                                                    <tr v-if="session.openOrdersCount>0" class="alert-danger">
                                                        <th>Open Orders</th>
                                                        <th class="text-center">{{ session.openOrdersCount }}</th>
                                                    </tr>
                                                </table>
                                                <h6 class="mb-2"> Specific Orders</h6>
                                                <table class="table table-bordered table-sm">
                                                    <tr v-if="session.source" v-for="single in session.source">
                                                        <td> {{ single.label }} </td>
                                                        <td class="text-center">{{ single.order }}</td>
                                                    </tr>
                                                </table>
                                                <h6  class="mb-2">Specific Discounts</h6>
                                                <table   class="table table-bordered table-sm">
						                            <tr v-if="session.source" v-for="single in session.source">
                                                        <td>{{ single.amountLabel }}</td>
                                                        <td class="text-right">{{ single.discount | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="table-responsive">
                                                <h6 class="mb-2">Specific Payments</h6>
                                                <table class="table table-bordered table-sm">
                                                    <tr v-if="session.payments" v-for="single in session.payments">
                                                        <td>{{ single.label }}</td>
                                                        <td class="text-right">{{ single.amount | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                </table>
                                                <h6 class="mb-2">Specific Amounts</h6>
                                                <table class="table table-bordered table-sm">
                                                    <tr v-if="session.source" v-for="single in session.source">
                                                        <td>{{ single.amountLabel }}</td>
                                                        <td class="text-right">{{ single.amount | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                </table>
                                                <h6   class="mb-2">Specific Registers</h6>
                                                <table  class="table table-bordered table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Register</th>
                                                            <th class="text-right">Tip</th>
                                                            <th class="text-right">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr  v-show="session.registersDetail" v-for="single in session.registersDetail">
                                                            <td>{{ single.registerTitle }}</td>
                                                            <td class="text-right">{{ single.tip | toTwoDecimal | beautifyCurrency }}</td>
                                                            <td class="text-right">{{ single.grandTotal | toTwoDecimal | beautifyCurrency }}</td>
                                                        </tr>
                                                        <tr  v-show="!session.registersDetail" >
                                                            <td class="text-center" colspan="3">No order</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="table-responsive">
                                                <h6 class="mb-2">Total Amounts</h6>
                                                <table class="table table-bordered table-sm">
                                                    <tr>
                                                        <td>Discount</td>
                                                        <td class="text-right">{{ session.discountTotal | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Change Given</td>
                                                        <td class="text-right">{{ session.changeTotal | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total Tip</td>
                                                        <td class="text-right">{{ session.tipTotal | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total Tax</td>
                                                        <td class="text-right">{{ session.taxTotal | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                    <tr v-if="allowGratuity">
                                                        <td>Total Gratuity</td>
                                                        <td class="text-right">{{ session.gratuityTotal | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Overall Cancelled Orders Amount</td>
                                                        <td class="text-right">{{ session.cancelledTransactionsTotal  | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                    <tr >
                                                        <td>Overall Refunded Orders Amount</td>
                                                        <td class="text-right">{{ session.refundTotal | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Overall Orders Amount</td>
                                                        <td class="text-right">{{ session.transactionsTotal | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                    <tr class="alert-danger ">
                                                        <th>Overall Payment Received</th>
                                                        <th class="text-right">{{ session.totalPaymentReceived | toTwoDecimal | beautifyCurrency }}</th>
                                                    </tr>
                                                    <tr>
                                                        <td>Expected Closing Cash</td>
                                                        <td class="text-right">{{ session.expectedClosingCash | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>

                                                </table>
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
    </div>
</script>
