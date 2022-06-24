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
Vue.component("report-shifts", {
	template: "#report-shifts-template",
	data: function () {
		return {
			module: "reports/shifts",
			params: {
				perPage: _s("paginationLimit"),
				itemCurrentPage: 1,
				fields: [
					{
						key: "empName",
						label: "Employee",
						tdClass: "text-left",
						thClass: "text-left",
					},
					{
						key: "totalOrder",
						label: "orders",
						tdClass: "text-right",
						thClass: "text-right",
					},
					{
						key: "startShift",
						label: "Shift Start",
						tdClass: "text-center",
						thClass: "text-center",
					},
					{
						key: "endShift",
						label: "Shift End",
						tdClass: "text-center",
						thClass: "text-center",
					},
					{
						key: "status",
						label: "status",
						tdClass: "text-center",
						thClass: "text-center",
					},
					{
						key: "tip",
						label: "Tip",
						tdClass: "text-right",
						thClass: "text-right",
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
			employees:_s('employees'),
			employeeId:'',
		};
	},
	computed: {
		itemRows() {
			return this.reportsCount;
		},
	},
	methods: {
		handleViewSession: function (empId,sessionId) {
			var self = this;
			var data = {
				module: this.module,
				method: "single",
				empId: empId,
				sessionId: sessionId,
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				self.session = response.shift;
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
				employeeId:this.employeeId,
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
				employeeId:this.employeeId,
			};

			var response = await submitRequest(data, "get");
			if (response.status === "ok") {
				self.reportsCount = response.reportsCount;
			}
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
				_s("shiftsPDFUrl") +
				"?startDate=" +
				startDate +
				"&endDate=" +
				endDate +
				"&employeeId=" +
				this.employeeId
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
				_s("shiftsCSVUrl") +
				"?startDate=" +
				startDate +
				"&endDate=" +
				endDate +
				"&employeeId=" +
				this.employeeId
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
		handleClearFilter: function () {
			this.filteredDateRange = {
				startDate: moment().subtract(1, "weeks"),
				endDate: moment(),
			};
			this.employeeId = '';
			this.totalReports();
			this.filterData(1);
		},
	},
	mounted: function () {
		if (this.reportsCount === null) {
			this.totalReports();
		}
		this.filterData(1);
	},
});
