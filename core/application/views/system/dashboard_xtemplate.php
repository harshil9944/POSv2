
<script type="text/x-template" id="dashboard-summary-box">
    <div class="block block-fx-shadow block-bordered block-link-shadow dashboard-filter-block">
        <div class="block-content block-content-full clearfix">
            <div class="float-right mt-15 d-none d-sm-block">
                <i class="si fa-2x" :class="iconClass"></i>
            </div>
            <div class="font-size-h3 font-w600" :class="textColorClass">
            <span>{{value}}</span>
            </div>
            <div class="font-size-sm font-w600 text-uppercase text-muted">{{ title }}</div>
        </div>
    </div>
</script>
<script type="text/x-template" id="dashboard-template">
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="block block-fx-shadow">
                    <div class="block-content p-20">
                        <p class="mb-0 font-weight-700">Filters</p>
                        <date-range-picker
                            opens="right"
                            v-model="filteredDateRange"
                            :auto-apply="true"
                        ></date-range-picker>
                        <button :disabled="!enableFilterBtn" class="btn btn-danger ml-2" @click="handleFilter">Filter</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row invisible" data-toggle="appear">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-6 col-xl-3">
                        <dashboard-summary-box title="Earnings" icon="si-wallet" :value="totalEarnings | toTwoDecimal | beautifyUSCurrency" variant="pulse"></dashboard-summary-box>
                    </div>
                    <div class="col-6 col-xl-3">
                        <dashboard-summary-box title="Orders" :value="closed" variant="elegance"></dashboard-summary-box>
                    </div>
                    <div class="col-6 col-xl-3">
                        <dashboard-summary-box title="Cancelled" :value="cancelled" variant="earth"></dashboard-summary-box>
                    </div>
                    <div class="col-6 col-xl-3">
                        <dashboard-summary-box title="Refunds" icon="si-wallet" :value="refundTotal | toTwoDecimal | beautifyUSCurrency" variant="pulse"></dashboard-summary-box>
                    </div>
                    <div class="col-6 col-xl-3">
                        <dashboard-summary-box title="Average Order" icon="fa fa-line-chart" :value="avgPayOrder | toTwoDecimal | beautifyUSCurrency" variant="corporate"></dashboard-summary-box>
                    </div>
                    <div class="col-6 col-xl-3">
                        <dashboard-summary-box title="New Customer" icon="si si-users" :value="totalCustomers" variant="elegance"></dashboard-summary-box>
                    </div>
                    <div class="col-6 col-xl-3">
                        <dashboard-summary-box title="Dine In Order" icon="si si-users" :value="dineOrder" variant="pulse"></dashboard-summary-box>
                    </div>
                    <div class="col-6 col-xl-3">
                        <dashboard-summary-box title="Pickup Order" icon="si-users" :value="pickUpOrder " variant="corporate"></dashboard-summary-box>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="block block-fx-shadow block-bordered">
                                    <div class="block-header block-header-default border-b">
                                        <h3 class="block-title">
                                        Orders<small class="pull-right">{{ dateRange }}</small>
                                        </h3>
                                    </div>
                                    <div class="block-content block-content-full">
                                        <pie-chart-order></pie-chart-order>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="block block-fx-shadow block-bordered">
                                    <div class="block-header block-header-default border-b">
                                        <h3 class="block-title">
                                        Earnings<small class="pull-right">{{ dateRange }}</small>
                                        </h3>
                                    </div>
                                    <div class="block-content block-content-full">
                                        <pie-chart-earning></pie-chart-earning>
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
                                            Orders By Payment<small class="pull-right">{{ dateRange }}</small>
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
                                            Payment<small class="pull-right">{{ dateRange }}</small>
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
        <div class="row gutters-tiny mb-4">
            <div class="col-xl-4">
                <div class="content-heading">
                    <div class="row">
                        <div class="col-12 col-md-9 mb-md-0">Last 10 Orders</div>
                        <div class="col-12 col-md-3 pull-right py-0">
                            <select id="orderSources-id" class="form-control custom-select d-block w-100" v-model="sourceId" @change="filterOrderSource">
                                <option v-for="s in orderSources" :value="s.id">{{ s.value }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="block block-fx-shadow block-rounded order-source-block">
                    <div class="block-content">
                        <table class="table table-borderless table-striped">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Source</th>
                                    <th>Status</th>
                                    <th class="text-right">Value</th>
                                </tr>
                            </thead>
                            <tbody v-if="lastOrders">
                                <tr v-for="single in lastOrders">
                                    <td>{{ single.billing_name }}</td>
                                    <td>{{ single.title }}</td>
                                    <td class="text-earth">{{ single.order_status }}</td>
                                    <td class="text-right">{{single.grand_total | beautifyUSCurrency}}</td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="4" class="text-center">No Data</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="content-heading" style="min-height:83px;">Top 10 Selling Products<small class="pull-right">{{ dateRange }}</small></div>
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
            <div class="col-xl-4">
                <div class="content-heading" style="min-height:83px;">Most Visited Customers<small class="pull-right">{{ dateRange }}</small></div>
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
                            <canvas class="js-flot-line3" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--   <div class="row mb-4" data-toggle="appear">
            <div class="col-12">
                <div class="block block-rounded block-bordered">
                    <div class="block-header block-header-default border-b">
                        <h3 class="block-title">
                           Last 30 Days
                        </h3>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="pull-all pt-50">
                        <canvas class="js-flot-last-30-days"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <div class="row mb-4" data-toggle="appear">
            <div class="col-12">
                <div class="block block-fx-shadow">
                    <ul id="30-days" class="nav nav-tabs nav-tabs-block align-items-center" data-toggle="tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="#last-30-orders">
                                <span class="d-none d-sm-inline">Last 30 Days Orders</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#last-30-earnings ">
                                <span class="d-none d-sm-inline">Last 30 Days Earnings </span>
                            </a>
                        </li>
                    </ul>
                    <div class="block-content block-content-full tab-content">
                        <div class="tab-pane fade show active " id="last-30-orders" role="tabpanel" aria-labelledby="last-30-orders">
                            <canvas class="js-flot-last-30-days" height="100"></canvas>
                        </div>
                        <div class="tab-pane fade show" id="last-30-earnings" role="tabpanel" aria-labelledby="last-30-earnings">
                            <canvas class="js-flot-last-30-earnings" height="100"></canvas>
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
                            <canvas class="js-flot-line"></canvas>
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
                            <canvas class="js-flot-line2"></canvas>
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
            <div class="col-md-6">
                <div class="block block-fx-shadow block-rounded block-bordered">
                    <div class="block-header block-header-default border-b">
                        <h3 class="block-title">
                            Tips <small>Last 12 Months</small>
                        </h3>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="pull-all pt-50">
                            <canvas class="js-flot-line-tips"></canvas>
                        </div>
                    </div>
                   <!--  <div class="block-content">
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
                    </div> -->
                </div>
            </div>
            <div class="col-md-6">
                <div class="block block-fx-shadow block-rounded block-bordered">
                    <div class="block-header block-header-default border-b">
                        <h3 class="block-title">
                            Discount <small>Last 12 Months</small>
                        </h3>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="pull-all pt-50">
                            <canvas class="js-flot-line-discount"></canvas>
                        </div>
                    </div>
                    <!-- <div class="block-content bg-white">
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
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</script>
