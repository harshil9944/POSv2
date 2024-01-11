
<script type="text/x-template" id="crowd-reports-template">
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="block block-fx-shadow">
                    <div class="block-content p-20">
                        <p class="mb-0 font-weight-700">Filters</p>
                        <div class="row no-gutters">
                            <div class="col-12 col-md-6">
                                <form class="form-inline" @submit.prevent="handleFilter">
                                     <date-range-picker
                                        opens="right"
                                        v-model="filteredDateRange"
                                        :auto-apply="true"
                                    ></date-range-picker>
                                    <select class="form-control ml-2" v-model="jsWeekDayId">
                                        <option v-for="single in dbWeekDays" :value="single.id">{{ single.value }}</option>
                                    </select>
                                    <button :disabled="!enableFilterBtn" class="btn btn-danger ml-2" @click="handleFilter">Filter</button>
                                </form>
                              <!--   <button class="btn btn-danger ml-2" @click="handleFilter">PDF</button> -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="content">
            <div class="row mb-4" data-toggle="appear">
                <div class="col-12">
                    <div class="block block-rounded block-bordered">
                        <div class="block-header block-header-default border-b">
                            <h3 class="block-title">
                                Day of week  <small> (only {{ weekDay }}s)</small> <small class="pull-right">{{ dateRange }}</small>
                            </h3>
                        </div>
                        <div class="block-content block-content-full">
                            <div class="pull-all pt-50">
                                <canvas class="js-flot-day-of-week" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row invisible" data-toggle="appear">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="block block-fx-shadow block-bordered">
                                        <div class="block-header block-header-default border-b">
                                            <h3 class="block-title">
                                            Orders<small> (only {{ weekDay }}s)</small><small class="pull-right">{{ dateRange }}</small>
                                            </h3>
                                        </div>
                                        <div class="block-content block-content-full">
                                            <pie-chart-order />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="block block-fx-shadow block-bordered">
                                        <div class="block-header block-header-default border-b">
                                            <h3 class="block-title">
                                            Earnings<small> (only {{ weekDay }}s)</small><small class="pull-right">{{ dateRange }}</small>
                                            </h3>
                                        </div>
                                        <div class="block-content block-content-full">
                                            <pie-chart-earning />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row invisible" data-toggle="appear">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="block block-fx-shadow block-bordered">
                                        <div class="block-header block-header-default border-b">
                                            <h3 class="block-title">
                                            Orders By Payment<small> (only {{ weekDay }}s)</small><small class="pull-right">{{ dateRange }}</small>
                                            </h3>
                                        </div>
                                        <div class="block-content block-content-full">
                                            <pie-chart-payment-order />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="block block-fx-shadow block-bordered">
                                        <div class="block-header block-header-default border-b">
                                            <h3 class="block-title">
                                            Payment<small> (only {{ weekDay }}s)</small><small class="pull-right">{{ dateRange }}</small>
                                            </h3>
                                        </div>
                                        <div class="block-content block-content-full">
                                            <pie-chart-payment />
                                        </div>
                                    </div>
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
                                Time of Day (All Days) <small class="pull-right">{{ dateRange }}</small>
                            </h3>
                        </div>
                        <div class="block-content block-content-full">
                            <div class="pull-all pt-50">
                                <canvas class="js-flot-time-of-day" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row gutters-tiny mb-4">
                <div class="col-xl-6">
                    <div class="content-heading" style="min-height:83px;">Top 10 Selling Products <small> (only {{ weekDay }}s)</small><small class="pull-right">{{ dateRange }}</small></div>
                    <div class="block block-fx-shadow block-rounded dashboard-filter-block">
                        <div class="block-content">
                            <table class="table table-borderless table-striped">
                                <thead>
                                    <tr>
                                        <th>Items</th>
                                        <th class="text-right">Sold Count</th>
                                    </tr>
                                </thead>
                                <tbody v-if="items">
                                    <tr v-for="single in items">
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
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="content-heading" style="min-height:83px;">Most Visited Customers <small> (only {{ weekDay }}s)</small><small class="pull-right">{{ dateRange }}</small></div>
                    <div class="block block-fx-shadow block-rounded dashboard-filter-block">
                        <div class="block-content">
                            <table class="table table-borderless table-striped">
                                <thead>
                                    <tr>
                                        <th>Customer </th>
                                        <th class="text-right">Count</th>
                                    </tr>
                                </thead>
                                <tbody v-if="mostVisitedCustomers">
                                    <tr v-for="single in mostVisitedCustomers">
                                        <td>{{single.display_name}}</td>
                                        <td class="text-right">
                                            <span class="badge badge-primary">{{single.totalVisited | toNoDecimal}}</span>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>