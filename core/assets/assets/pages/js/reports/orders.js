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
Vue.component("report-orders", {
	template: "#report-orders-template",
	data: function () {
		return {
			module: "reports/orders",
			params: {
				perPage: _s("paginationLimit"),
				itemCurrentPage: 1,
				fields: [
					{ key: "date", label: "Date" },
					{ key: "type", label: "Type" },
					{ 
						key: "sessionOrderNo",
					 	label: "Session Order No",
					 	tdClass: "text-center w-180p",
						thClass: "text-center w-180p"
				    },
					{ key: "orderStatus", label: "Status" },
					{ key: "billingName", label: "Customer" },
					{
						key: "subTotal",
						label: "Sub Total",
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
						key: "taxTotal",
						label: "Tax Total",
						tdClass: "text-right w-180p",
						thClass: "text-right w-180p",
					},
					{
						key: "grandTotal",
						label: "Total",
						tdClass: "text-right w-180p",
						thClass: "text-right w-180p",
					},
				],
			},
			filteredDateRange: {
				startDate: _s("startDate")
					? _s("startDate")
					: moment().subtract(1, "weeks"),
				endDate: _s("endDate") ? _s("endDate") : moment(),
			},
			enableFilterBtn: true,
			reportsCount: null,
			reports: [],
			modal: {
				obj: {
					payments: [],
					customer: [],
					refundPayments: [],
				},
			},
			paymentMethods: [],
			allowGratuity: _s("allowGratuity"),
		};
	},
	computed: {
		itemRows() {
			return this.reportsCount;
		},
		afterRefundGrandTotal() {
			var totalPaid =
				Number(this.modal.obj.grandTotal) + Number(this.modal.obj.tip);
			var refundTotal = this.getRefundTotalPaid();
			return Number(Number(totalPaid) - Number(refundTotal)).toFixed(2) > 0
				? Number(Number(totalPaid) - Number(refundTotal)).toFixed(2)
				: 0;
		},
	},
	methods: {
		getPaymentMethodName: function (id) {
			if (this.paymentMethods.length) {
				var paymentMethod = this.paymentMethods.find(function (method) {
					return Number(method.id) === Number(id);
				});
				return paymentMethod.value;
			}
		},
		populateMeta: function () {
			var self = this;
			var data = {
				module: "pos",
				method: "populate_payment",
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				if (response.status === "ok") {
					self.paymentMethods = response.paymentMethods;
				}
			});
		},
		getRefundTotalPaid: function () {
			var payments = this.modal.obj.refundPayments;
			var totalPaid = 0;
			if (payments.length) {
				totalPaid = payments.reduce(function (totalPaid, payment) {
					return Number(totalPaid) + Number(payment.amount);
				}, totalPaid);
			}
			return totalPaid.toFixed(2);
		},
		getTotalPaid: function (amount) {
			var payments = this.modal.obj.payments;
			var totalPaid = 0;
			if (payments.length) {
				totalPaid = payments.reduce(function (totalPaid, payment) {
					return Number(totalPaid) + Number(payment.amount);
				}, totalPaid);
			}
			return totalPaid.toFixed(2);
		},
		hasAddons: function (addons) {
			var has = false;
			if (addons.length) {
				addons.forEach(function (addon) {
					if (addon.enabled === true) {
						has = true;
					}
				});
			}
			return has;
		},
		getAddons: function (addons) {
			var string = "";
			if (addons.length) {
				addons.forEach(function (addon) {
					if (addon.enabled === true) {
						if (string !== "") {
							string += ", " + addon.title;
						} else {
							string += addon.title;
						}
					}
				});
			}
			return string;
		},
		getNotes: function (notes) {
			if (typeof notes === "object") {
				var string = "";
				if (notes.length) {
					notes.forEach(function (note) {
						if (string !== "") {
							string += ", " + note.title;
						} else {
							string += note.title;
						}
					});
				}
				return string;
			} else {
				return notes;
			}
		},
		handleViewOrder: function (id) {
			var self = this;
			var data = {
				module: "orders",
				method: "single_view",
				id: id,
			};

			var request = submitRequest(data, "get");
			request.then(function (response) {
				self.modal.obj = response.obj;
				self.$bvModal.show("order-details-modal");
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
				_s("ordersReportPDFUrl") +
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
				_s("ordersReportCSVUrl") +
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
