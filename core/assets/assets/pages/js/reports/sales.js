Vue.component("date-range-picker", window["vue2-daterange-picker"].default);
Vue.mixin({
	methods: {
		dtFormat: function (date) {
			return moment(date).format("DD/MM/YYYY");
		},
		beautifyDate: function (value) {
			return this.$options.filters.beautifyDate(value);
		},
		handleOpenModal: function (modal) {
			this.$bvModal.show(modal);
		},
		handleCloseModal: function (modal) {
			this.$bvModal.hide(modal);
		},
	},
});
Vue.component("report-sales", {
	template: "#report-sales-template",
	data: function () {
		return {
			module: "reports/sales",
			params: {
				perPage: _s("paginationLimit"),
				itemCurrentPage: 1,
				fields: [
					{ key: "orderDate", label: "Date" },
					{
						key: "totalOrders",
						label: "Total Orders",
						tdClass: "text-right w-180p",
						thClass: "text-right w-180p",
					},
					/* {
						key: "totalItems",
						label: "Total Items",
						tdClass: "text-right w-180p",
						thClass: "text-right w-180p",
					}, */
					{
						key: "subTotal",
						label: "Sub Total",
						tdClass: "text-right w-180p",
						thClass: "text-right w-180p",
					},
					{
						key: "tip",
						label: "Tip",
						tdClass: "text-right w-180p",
						thClass: "text-right w-180p",
					},
					{
						key: "discount",
						label: "Discount",
						tdClass: "text-right w-180p",
						thClass: "text-right w-180p",
					},
					{
						key: "totalTax",
						label: "Total Tax",
						tdClass: "text-right w-180p",
						thClass: "text-right w-180p",
					},
					{
						key: "totalAmount",
						label: "Amount",
						tdClass: "text-right w-180p",
						thClass: "text-right w-180p",
					},
				],
			},
			filteredDateRange: {
				startDate: moment().subtract(1, "weeks"),
				endDate: moment(),
			},
			enableFilterBtn: true,
			reportsCount: null,
			reports: [],
		};
	},
	computed: {
		itemRows() {
			return this.reportsCount;
		},
	},
	methods: {
		handleFilter: function () {
			this.totalReports();
			this.filterData(1);
		},
		filterData: async function (currentPage) {
			this.enableFilterBtn = false;
			this.params.itemCurrentPage = currentPage;
			var self = this;
			var startDate = moment(this.filteredDateRange.startDate).format(
				"YYYY/MM/DD",
			);
			var endDate = moment(this.filteredDateRange.endDate).format("YYYY/MM/DD");
			var data = {
				module: this.module,
				method: "filter_list",
				filterStartDate: startDate,
				filterEndDate: endDate,
				currentPage: this.params.itemCurrentPage,
			};
			var response = await submitRequest(data, "get");
			if (response.status === "ok") {
				self.reports = response.reports;
			}
			self.enableFilterBtn = true;
		},
		getSum: function (field) {
			var total = 0;
			if (this.reports.length) {
				total = this.reports.reduce(function (total, row) {
					return Number(total) + Number(row[field]);
				}, total);
			}
			return total;
		},
		totalReports: async function () {
			var self = this;
			var startDate = moment(this.filteredDateRange.startDate).format(
				"YYYY/MM/DD",
			);
			var endDate = moment(this.filteredDateRange.endDate).format("YYYY/MM/DD");
			var data = {
				module: this.module,
				method: "filter_list_total",
				filterStartDate: startDate,
				filterEndDate: endDate,
			};

			var response = await submitRequest(data, "get");
			if (response.status === "ok") {
				self.reportsCount = response.reportsCount;
			}
		},
		getSalesUrl: function (date) {
			var mySqlDate = moment(date, "YYYY/MM/DD HH:mm:ss").format("YYYY-MM-DD");
			return (
				_s("ordersReportUrl") +
				"?startDate=" +
				mySqlDate +
				"&endDate=" +
				mySqlDate
			);
		},
		handleExportPDF: function () {
			var startDate = moment(
				this.filteredDateRange.startDate,
				"YYYY/MM/DD HH:mm:ss",
			).format("YYYY-MM-DD");
			var endDate = moment(
				this.filteredDateRange.endDate,
				"YYYY/MM/DD HH:mm:ss",
			).format("YYYY-MM-DD");
			var pdfUrl =
				_s("salesReportPDFUrl") +
				"?startDate=" +
				startDate +
				"&endDate=" +
				endDate;
			Object.assign(document.createElement("a"), {
				target: "_blank",
				href: pdfUrl,
			}).click();
		},
		handleExportCSV: function () {
			var startDate = moment(
				this.filteredDateRange.startDate,
				"YYYY/MM/DD HH:mm:ss",
			).format("YYYY-MM-DD");
			var endDate = moment(
				this.filteredDateRange.endDate,
				"YYYY/MM/DD HH:mm:ss",
			).format("YYYY-MM-DD");
			var csvUrl =
				_s("salesReportCSVUrl") +
				"?startDate=" +
				startDate +
				"&endDate=" +
				endDate;
			Object.assign(document.createElement("a"), {
				target: "_blank",
				href: csvUrl,
			}).click();
		},
	},
	mounted: function () {
		if (this.reportsCount === null) {
			this.totalReports();
		}
		this.filterData(1);
	},
});
