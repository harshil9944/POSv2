<script type="text/x-template" id="report-sessions-template">
    <div id="report-sessions" class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="block-content p-20">
                    <p class="mb-0 font-weight-700">Filters</p>
                    <date-range-picker
                        opens="right"
                        v-model="filteredDateRange"
                    ></date-range-picker>
                    <button :disabled="!enableFilterBtn" class="btn btn-danger ml-2" @click="handleFilter">Filter</button>
                    <a href="#" @click.prevent="handleExportPDF" class="float-right">Export PDF</a>
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
                        <template slot="openingDate" slot-scope="row">
                            {{ row.value | beautifyDateTime }}
                        </template>
                        <template slot="status" slot-scope="row">
                            <span v-if="row.value=='Close'">{{ row.value }}</span>
                            <span v-if="row.value=='Open'" class="text-danger">{{ row.value }}</span>
                        </template>
                        <template slot="ordersCount" slot-scope="row">
                            {{ row.value }}
                        </template>
                        <template slot="openingCash" slot-scope="row">
                            <span v-if="row.value>0" class="text-right">{{ row.value | beautifyCurrency }}</span>
                            <span v-if="row.value<=0">0</span>
                        </template>
                        <template slot="cashTransactionsTotal" slot-scope="row">
                            <span v-if="row.value>0" class="text-right">{{ row.value | beautifyCurrency }}</span>
                            <span v-if="row.value<=0">0</span>
                        </template>
                        <template slot="cardTransactionsTotal" slot-scope="row">
                            <span v-if="row.value>0" class="text-right">{{ row.value | beautifyCurrency }}</span>
                            <span v-if="row.value<=0">0</span>
                        </template>
                        <template slot="transactionsTotal" slot-scope="row">
                            <span v-if="row.value>0" class="text-right">{{ row.value | beautifyCurrency }}</span>
                            <span v-if="row.value<=0">0</span>
                        </template>
                        <template slot="changeTotal" slot-scope="row">
                            <span v-if="row.value>0" class="text-right">{{ row.value | beautifyCurrency }}</span>
                            <span v-if="row.value<=0">0</span>
                        </template>
                        <template slot="discountTotal" slot-scope="row">
                            <span v-if="row.value>0" class="text-right">{{ row.value | beautifyCurrency }}</span>
                            <span v-if="row.value<=0">0</span>
                        </template>
                        <template slot="tipTotal" slot-scope="row">
                            <span v-if="row.value>0" class="text-right">{{ row.value | beautifyCurrency }}</span>
                            <span v-if="row.value<=0">0</span>
                        </template>
                        <template slot="closingCash" slot-scope="row">
                            <span v-if="row.value>0" class="text-right">{{ row.value | beautifyCurrency }}</span>
                            <span v-if="row.value<=0">0</span>
                        </template>
                        <template slot="closingDate" slot-scope="row">
                        <span v-if="row.value != ''">
                            {{ row.value | beautifyDateTime }}
                        </span>
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
                    <h4 v-if="!reports.length" class="text-center">No Items</h4>
                </div>
            </div>
        </div>
    </div>
</script>
