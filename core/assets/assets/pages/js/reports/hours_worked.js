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
Vue.component("hours-worked", {
	template: "#hours-worked-template",
	data: function () {
		return {
			module: "reports/hours_worked",
			params: {
				perPage: _s("paginationLimit"),
				itemCurrentPage: 1,
				fields: [
					{
						key: "name",
						label: "Employee",
						tdClass: "text-left",
						thClass: "text-left",
					},
					{
						key: "time",
						label: "Shift Time",
						tdClass: "text-center",
						thClass: "text-center",
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
				_s("PDFUrl") +
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
				_s("CSVUrl") +
				"?startDate=" +
				startDate +
				"&endDate=" +
				endDate;
			Object.assign(document.createElement("a"), {
				target: "_blank",
				href: csvUrl,
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
