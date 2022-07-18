<script type="text/x-template" id="report-sales-template">
    <div id="reports-sales" class="row">
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
                        <template slot="orderDate" slot-scope="row">
                            <a :href="getSalesUrl(row.value)">{{ row.value | beautifyDate }}</a>
                        </template>
                        <template slot="totalItems" slot-scope="row">
                            {{ row.value | toNoDecimal }}
                        </template>
                        <template slot="subTotal" slot-scope="row">
                            <span class="text-right">{{ row.value | beautifyCurrency }}</span>
                        </template>
                        <template slot="tip" slot-scope="row">
                            <span class="text-right">{{ row.value | beautifyCurrency }}</span>
                        </template>
                        <template slot="discount" slot-scope="row">
                            <span class="text-right">{{ row.value | beautifyCurrency }}</span>
                        </template>
                        <template slot="totalTax" slot-scope="row">
                            <span class="text-right">{{ row.value | beautifyCurrency }}</span>
                        </template>
                        <template slot="totalAmount" slot-scope="row">
                            <span class="text-right">{{ row.value | beautifyCurrency }}</span>
                        </template>
                        <template slot="bottom-row">
                            <th  class="text-right">Total</th>
                            <th class="text-right">{{ getSum('totalOrders') }}</th>
                            <th class="text-right">{{ getSum('subTotal') | toTwoDecimal | beautifyCurrency }}</th>
                            <th class="text-right">{{ getSum('tip') | toTwoDecimal | beautifyCurrency }}</th>
                            <th class="text-right">{{ getSum('discount') | toTwoDecimal | beautifyCurrency }}</th>
                            <th class="text-right">{{ getSum('totalTax') | toTwoDecimal | beautifyCurrency }}</th>
                            <th class="text-right">{{ getSum('totalAmount') | toTwoDecimal | beautifyCurrency }} </th>
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
                        v-if="reports.length"
                    ></b-pagination>
                    <h4 v-if="!reports.length" class="text-center">No Items</h4>
                </div>
            </div>
        </div>
    </div>
</script>
