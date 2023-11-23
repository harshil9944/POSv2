
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
                                    <select class="form-control ml-2" v-model="weekDayId">
                                        <option v-for="single in dbWeekDays" :value="single.id">{{ single.value }}</option>
                                    </select>
                                    <button :disabled="!enableFilterBtn" class="btn btn-danger ml-2" @click="handleFilter">Filter</button>
                                </form>
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
                            Day of week  <small> - only {{ weekDay }}</small> <small class="pull-right">{{ dateRange }}</small>
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
        <div class="row mb-4" data-toggle="appear">
            <div class="col-12">
                <div class="block block-rounded block-bordered">
                    <div class="block-header block-header-default border-b">
                        <h3 class="block-title">
                            Time of Day <small class="pull-right">{{ dateRange }}</small>
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
        <div class="row invisible" data-toggle="appear">
            <div class="col-md-12">
                <div class="row">
                    
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
    </div>
</script>
