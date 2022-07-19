<script type="text/x-template" id="report-sessions-template">
    <div id="report-sessions" class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="block-content p-20">
                    <p class="mb-0 font-weight-700">Filters</p>
                    <date-range-picker
                        opens="right"
                        v-model="filteredDateRange"
                        :auto-apply="true"
                    ></date-range-picker>
                    <button :disabled="!enableFilterBtn" class="btn btn-danger ml-2" @click="handleFilter">Filter</button>
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
                        <template slot="openingDate" slot-scope="{row,item}">
                            <a @click.prevent="handleViewSession(item.id)" href="#">{{ item.openingDate | beautifyDateTime }}</a>
                        </template>
                        <template slot="status" slot-scope="row">
                            <span v-if="row.value=='Close'">{{ row.value }}</span>
                            <span v-if="row.value=='Open'" class="text-danger">{{ row.value }}</span>
                        </template>
                        <template slot="ordersCount" slot-scope="row">
                            {{ row.value }}
                        </template>
                        <template slot="openingCash" slot-scope="row">
                            <span v-if="row.value>0" class="text-right">{{ row.value | toTwoDecimal }}</span>
                            <span v-if="row.value<=0">0</span>
                        </template>
                        <template slot="transactionsTotal" slot-scope="row">
                            <span v-if="row.value>0" class="text-right">{{ row.value | toTwoDecimal }}</span>
                            <span v-if="row.value<=0">0</span>
                        </template>
                        <template slot="changeTotal" slot-scope="row">
                            <span v-if="row.value>0" class="text-right">{{ row.value | toTwoDecimal }}</span>
                            <span v-if="row.value<=0">0</span>
                        </template>
                        <template slot="discountTotal" slot-scope="row">
                            <span v-if="row.value>0" class="text-right">{{ row.value | toTwoDecimal }}</span>
                            <span v-if="row.value<=0">0</span>
                        </template>
                        <template slot="tipTotal" slot-scope="row">
                            <span v-if="row.value>0" class="text-right">{{ row.value | toTwoDecimal }}</span>
                            <span v-if="row.value<=0">0</span>
                        </template>
                        <template slot="taxTotal" slot-scope="row">
                            <span v-if="row.value>0" class="text-right">{{ row.value | toTwoDecimal }}</span>
                            <span v-if="row.value<=0">0</span>
                        </template>
                        <template slot="takeOut" slot-scope="row">
                            <span v-if="row.value>0" class="text-right">{{ row.value | toTwoDecimal }}</span>
                            <span v-if="row.value<=0">0</span>
                        </template>
                        <template slot="closingCash" slot-scope="row">
                            <span v-if="row.value>0" class="text-right">{{ row.value | toTwoDecimal }}</span>
                            <span v-if="row.value<=0">0</span>
                        </template>
                        <template slot="expectedClosingCash" slot-scope="row">
                            <span v-if="row.value>0" class="text-right">{{ row.value | toTwoDecimal }}</span>
                            <span v-if="row.value<=0">0</span>
                        </template>
                        <template slot="closingDate" slot-scope="row">
                        <span v-if="row.value != ''">
                            {{ row.value | beautifyDateTime }}
                        </span>
                        </template>
                        <template slot="bottom-row">
                            <th colspan="2" class="text-right">Total</th>
                            <th class="text-right">{{ getSum('ordersCount') }}</th>
                            <th class="text-right">{{ getSum('openingCash') | toTwoDecimal | beautifyCurrency }}</th>
                            <th class="text-right">{{ getSum('transactionsTotal') | toTwoDecimal | beautifyCurrency }}</th>
                            <th class="text-right">{{ getSum('discountTotal') | toTwoDecimal | beautifyCurrency }}</th>
                            <th class="text-right">{{ getSum('tipTotal') | toTwoDecimal | beautifyCurrency }}</th>
                            <th class="text-right">{{ getSum('taxTotal') | toTwoDecimal | beautifyCurrency }}</th>
                            <th class="text-right">{{ getSum('takeOut') | toTwoDecimal | beautifyCurrency }}</th>
                            <th class="text-right">{{ getSum('closingCash') | toTwoDecimal | beautifyCurrency }}</th>
                            <th class="text-right">{{ getSum('expectedClosingCash') | toTwoDecimal | beautifyCurrency }}</th>
                            <th></th>
                        </template>
                    </b-table>
                    <div class="row">
                        <div class="col-1 text-right mt-1">
                            <span v-if="reports.length">Total {{ itemRows }}</span>
                        </div>
                        <div class="col-1">
                            <b-form-select size="sm"  v-model="params.perPage"  @change="handleChangeLimit"  :options="pageOptions"></b-form-select>
                        </div>
                        <div class="col-10">
                            <b-pagination
                                @change="filterData"
                                :current-page="params.itemCurrentPage"
                                v-model="params.itemCurrentPage"
                                :total-rows="itemRows"
                                :per-page="params.perPage"
                                aria-controls="item-list-table"
                                align="right"
                                v-if="reports.length"
                            ></b-pagination>
                        </div>
                    </div>
                    <h4 v-if="!reports.length" class="text-center">No Items</h4>
                </div>
            </div>
        </div>
        <b-modal no-fade centered id="session-summary-modal" size="xl" hide-header hide-footer body-class="p-0">
            <div id="session-summary-block" class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Summary</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" @click="$bvModal.hide('session-summary-modal');" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content bg-gray-light">
                    <div class="col text-right">
                        <button class="btn btn-danger ml-5" @click="handleDownloadPdf" title="Download PDF"><i class="fa fa-file-pdf-o"></i></button>
                    </div>
                </div>
                <div class="block-content bg-gray-light">
                    <div class="row d-flex">
                        <div class="col-md-12">
                            <div class="block">
                                <div class="block-content">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <span><small>Opened</small><br>{{ session.openingDate | beautifyDateTime }}</span>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <span><small>By employee</small><br>{{ session.openingEmployee }}</span>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <span><small>Closed</small><br>{{ (session.closingDate)?session.closingDate:null | beautifyDateTime }}</span>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <span><small>By employee</small><br>{{ session.closingEmployee }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                                    <tr v-if="enableRefunded">
                                                        <td>Refunded Orders</td>
                                                        <td class="text-center">{{ session.refundedOrdersCount }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Open Orders</td>
                                                        <td class="text-center">{{ session.openOrdersCount }}</td>
                                                    </tr>
                                                </table>
                                                <h6 class="mb-2"> Specific Orders</h6>
                                                <table class="table table-bordered table-sm">
                                                    <tr v-if="session.source" v-for="single in session.source">
                                                        <td> {{ single.label }} </td>
                                                        <td class="text-center">{{ single.order }}</td>
                                                    </tr>
                                                </table>
                                                <?php if(ALLOW_DISCOUNT_IN_SUMMARY) { ?>
                                                <h6 class="mb-2">Specific Discounts</h6>
                                                <table class="table table-bordered table-sm">
                                                    <tr v-if="session.source" v-for="single in session.source">
                                                        <td>{{ single.label }}</td>
                                                        <td class="text-right">{{ single.discount | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                </table>
                                                <?php } ?>
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
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="table-responsive">
                                                <h6 class="mb-2">Total Amounts</h6>
                                                <table class="table table-bordered table-sm">
                                                    <tr>
                                                        <td>Opening Cash</td>
                                                        <td class="text-right">{{ session.openingCash | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
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
                                                        <td class="text-right">{{ session.taxTotal  | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                    <tr v-if="allowGratuity">
                                                        <td>Total Gratuity</td>
                                                        <td class="text-right">{{ session.gratuityTotal | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Overall Cancelled Orders Amount</td>
                                                        <td class="text-right">{{ session.cancelledTransactionsTotal  | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                    <tr v-if="enableRefunded">
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
                                                    <tr v-if="session.status === 'Close'">
                                                        <td>Closing Cash</td>
                                                        <td class="text-right">{{ Number(session.takeOut) + Number(session.closingCash) | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                    <tr v-else>
                                                        <td>Expected Closing Cash</td>
                                                        <td class="text-right">{{ session.expectedClosingCash | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                    <tr v-if="session.status === 'Close'">
                                                        <td>Cash Out</td>
                                                        <td class="text-right">{{ session.takeOut | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                    <tr v-if="session.status === 'Close'">
                                                        <td>Cash In Register</td>
                                                        <td class="text-right">{{ session.closingCash | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="block">
                               <div class="block-content">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="mb-2">Opening Notes</h6>
                                                <table class="table">
                                                    <tbody>
                                                        <tr v-if="session.openingNote" class="font-14-w600">
                                                            <td>{{ session.openingNote }}</td>
                                                        </tr>
                                                        <tr v-if="!session.openingNote">
                                                            <td class="text-center font-w600">No Notes</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div  class="col-md-6">
                                                <h6 class="mb-2">Closing Notes</h6>
                                                <table class="table">
                                                    <tbody>
                                                        <tr v-if="session.closingNote" class="font-14-w600">
                                                            <td>{{ session.closingNote }}</td>
                                                        </tr>
                                                        <tr v-if="!session.closingNote">
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
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</script>
