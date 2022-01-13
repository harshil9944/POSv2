<script type="text/x-template" id="report-orders-template">
    <div id="reports-orders" class="row">
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
                        <template slot="date" slot-scope="row">
                            {{ row.value | beautifyDateTime }}
                        </template>
                        <template slot="type" slot-scope="row">
                            {{ row.value == 'p' ? 'Pickup' : 'Delivery' }}
                        </template>
                        <template slot="grandTotal" slot-scope="row">
                            {{ row.value | beautifyCurrency }}
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
                    <h4 v-if="!reports.length" class="text-center">No Order</h4>
                </div>
            </div>
        </div>
    </div>
</script>
