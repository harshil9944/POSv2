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
		hideDialog: function () {
			this.handleCloseModal(this.modal.id);
		},
	},
});
Vue.component("report-sessions", {
	template: "#report-sessions-template",
	data: function () {
		return {
			module: "reports/sessions",
			params: {
				perPage: _s("paginationLimit"),
				itemCurrentPage: 1,
				fields: [
					{ key: "openingDate", label: "Open" },
					{ key: "status", label: "Status" },
					{
						key: "ordersCount",
						label: "Orders",
						tdClass: "text-right",
						thClass: "text-right",
					},
					{
						key: "openingCash",
						label: "Opening Amount",
						tdClass: "text-right",
						thClass: "text-right",
					},
					{
						key: "taxTotal",
						label: "Tax",
						tdClass: "text-right",
						thClass: "text-right",
					},
					{
						key: "transactionsTotal",
						label: "Total",
						tdClass: "text-right",
						thClass: "text-right",
					},
					{
						key: "takeOut",
						label: "Cash Out",
						tdClass: "text-right",
						thClass: "text-right",
					},
					{
						key: "closingCash",
						label: "Cash In Register",
						tdClass: "text-right",
						thClass: "text-right",
					},
					{
						key: "expectedClosingCash",
						label: "Closing Amount",
						tdClass: "text-right",
						thClass: "text-right",
					},
					{ key: "closingDate", label: "Close" },
				],
			},
			filteredDateRange: {
				startDate: moment().subtract(1, "weeks"),
				endDate: moment(),
			},
			enableFilterBtn: true,
			reportsCount: null,
			reports: [],
			session: {},
			enableRefunded: _s("enableRefunded"),
			allowGratuity: _s("allowGratuity"),
		};
	},
	computed: {
		itemRows() {
			return this.reportsCount;
		},
	},
	methods: {
		handleViewSession: function (id) {
			var self = this;
			var data = {
				module: this.module,
				method: "single",
				id: id,
			};

			var request = submitRequest(data, "get");
			request.then(function (response) {
				self.session = response.session;
				self.$bvModal.show("session-summary-modal");
			});
		},
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
				_s("sessionsPDFUrl") +
				"?startDate=" +
				startDate +
				"&endDate=" +
				endDate;
			Object.assign(document.createElement("a"), {
				target: "_blank",
				href: pdfUrl,
			}).click();
		},
		handleDownloadPdf: function () {
			Object.assign(document.createElement("a"), {
				target: "_blank",
				href: this.session.sessionPdfUrl,
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
