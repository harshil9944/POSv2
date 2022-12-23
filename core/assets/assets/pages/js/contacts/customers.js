!(function (e) {
	var t = {};
	function r(o) {
		if (t[o]) return t[o].exports;
		var a = (t[o] = { i: o, l: !1, exports: {} });
		return e[o].call(a.exports, a, a.exports, r), (a.l = !0), a.exports;
	}
	(r.m = e),
		(r.c = t),
		(r.d = function (e, t, o) {
			r.o(e, t) || Object.defineProperty(e, t, { enumerable: !0, get: o });
		}),
		(r.r = function (e) {
			"undefined" != typeof Symbol &&
				Symbol.toStringTag &&
				Object.defineProperty(e, Symbol.toStringTag, { value: "Module" }),
				Object.defineProperty(e, "__esModule", { value: !0 });
		}),
		(r.t = function (e, t) {
			if ((1 & t && (e = r(e)), 8 & t)) return e;
			if (4 & t && "object" == typeof e && e && e.__esModule) return e;
			var o = Object.create(null);
			if (
				(r.r(o),
				Object.defineProperty(o, "default", { enumerable: !0, value: e }),
				2 & t && "string" != typeof e)
			)
				for (var a in e)
					r.d(
						o,
						a,
						function (t) {
							return e[t];
						}.bind(null, a),
					);
			return o;
		}),
		(r.n = function (e) {
			var t =
				e && e.__esModule
					? function () {
							return e.default;
					  }
					: function () {
							return e;
					  };
			return r.d(t, "a", t), t;
		}),
		(r.o = function (e, t) {
			return Object.prototype.hasOwnProperty.call(e, t);
		}),
		(r.p = ""),
		r((r.s = 42));
})({
	42: function (e, t, r) {
		e.exports = r(43);
	},
	43: function (e, t) {
		function r(e, t) {
			for (var r = 0; r < t.length; r++) {
				var o = t[r];
				(o.enumerable = o.enumerable || !1),
					(o.configurable = !0),
					"value" in o && (o.writable = !0),
					Object.defineProperty(e, o.key, o);
			}
		}
		var o = (function () {
			function e() {
				!(function (e, t) {
					if (!(e instanceof t))
						throw new TypeError("Cannot call a class as a function");
				})(this, e);
			}
			var t, o, a;
			return (
				(t = e),
				(a = [
					{
						key: "initClassicChartJS",
						value: function () {
							(Chart.defaults.global.defaultFontColor = "#7c7c7c"),
								(Chart.defaults.scale.gridLines.color = "#f5f5f5"),
								(Chart.defaults.scale.gridLines.zeroLineColor = "#f5f5f5"),
								(Chart.defaults.scale.display = !0),
								(Chart.defaults.scale.ticks.beginAtZero = !0),
								(Chart.defaults.global.elements.line.borderWidth = 2),
								(Chart.defaults.global.elements.point.radius = 5),
								(Chart.defaults.global.elements.point.hoverRadius = 7),
								(Chart.defaults.global.tooltips.cornerRadius = 3),
								(Chart.defaults.global.legend.display = !1);
							var e = jQuery(".js-last-12-sales");
								t = jQuery(".js-last-12-earnings");
								p = jQuery(".js-time-of-day");
							e.length &&
								new Chart(e, {
									type: "line",
									data: {
										labels: _s("this_year_months"),
										datasets: [
											{
												label: "This Year",
												fill: !0,
												backgroundColor: "rgba(114,102,186,.15)",
												borderColor: "rgba(114,102,186,.5)",
												pointBackgroundColor: "rgba(114,102,186,.5)",
												pointBorderColor: "#fff",
												pointHoverBackgroundColor: "#fff",
												pointHoverBorderColor: "rgba(114,102,186,.5)",
												data: _s("this_year_sales"),
											},
										],
									},
									options: {
										scales: { yAxes: [{ ticks: { suggestedMax: 10 } }] },
										tooltips: {
											callbacks: {
												label: function (e, t) {
													return " " + e.yLabel + " Orders";
												},
											},
										},
									},
								}),
							t.length &&
								new Chart(t, {
									type: "line",
									data: {
										labels: _s("this_year_months"),
										datasets: [
											{
												label: "This Year",
												fill: !0,
												backgroundColor: "rgba(247,93,129,.15)",
												borderColor: "rgba(247,93,129,.5)",
												pointBackgroundColor: "rgba(247,93,129,.5)",
												pointBorderColor: "#fff",
												pointHoverBackgroundColor: "#fff",
												pointHoverBorderColor: "rgba(247,93,129,.5)",
												data: _s("this_year_earning"),
											},
										],
									},
									options: {
										scales: {
											yAxes: [
												{
													ticks: {
														suggestedMax: 500,
														callback: function (value, index, values) {
															return "$" + value;
														},
													},
												},
											],
										},
										tooltips: {
											callbacks: {
												label: function (e, t) {
													return " $ " + e.yLabel;
												},
											},
										},
									},
								});

								p.length &&
								new Chart(p, {
									type: "line",
									data: {
										labels: _s("time_range"),
										datasets: [
											{
												label: "This Year",
												fill: !0,
												backgroundColor: "rgba(249,126,54,.15)",
												borderColor: "rgba(249,126,54,.5)",
												pointBackgroundColor: "rgba(249,126,54,.5)",
												pointBorderColor: "#fff",
												pointHoverBackgroundColor: "#fff",
												pointHoverBorderColor: "rgba(249,126,54,.5)",
												data: _s("popular_times"),
											},
										],
									},
									options: {
										scales: {
											yAxes: [
												{
													ticks: {
														suggestedMax: 0,
														callback: function (value, index, values) {
															return value + "%";
														},
													},
												},
											],
										},
										tooltips: {
											callbacks: {
												label: function (e, t) {
													return e.yLabel + "%";
												},
											},
										},
									},
								});
						},
					},
					{
						key: "init",
						value: function () {
							this.initClassicChartJS();
						},
					},
				]),
				(o = null) && r(t.prototype, o),
				a && r(t, a),
				e
			);
		})();
		jQuery(function () {
			o.init();
		});
	},
});
Vue.component("general-list", {
	template: "#general-list-template",
	mixins: [customerCustomFieldsMixin],
	data: function () {
		return {
			module: "contacts/customers",
			customer: {
				addresses: [],
				group: [],
			},
			masters: {
				countries: [],
				states: [],
				groups: [],
				cities: [],
			},
			modal: {
				activeTab: "basic",
			},
			allowCustomerGroup: _s("allowCustomerGroup"),
			order: {},
			customerCustomFields: _s("customerCustomFields")
				? _s("customerCustomFields")
				: [],
			editUrl: _s("editUrl"),
			detailUrl: _s("detailUrl"),
		};
	},
	computed: {
		addressList: function () {
			return this.customer.addresses;
		},
		customerEditUrl: function () {
			return this.editUrl + this.customer.id;
		},
	},
	methods: {
		handleViewCustomer: function(id) {
            Object.assign(document.createElement("a"), {
                //target: "_blank",
                href: this.detailUrl + "/" + id,
            }).click();
        },
		handleOrderDetails: function (id) {
			bus.$emit("initOrderDetails", { id: id });
		},
		getItems: function (items) {
			var string = "";
			if (items.length) {
				items.forEach(function (item) {
					if (item) {
						if (string !== "") {
							string += ", " + item.title;
						} else {
							string += item.title;
						}
					}
				});
			}
			return string;
		},
		deleteAddress: function (id) {
			console.log(id);
			if (ds_confirm("Are you sure to delete this address ?")) {
				var self = this;
				var data = {
					module: self.module,
					method: "address",
					customerId: self.customer.id,
					addressId: id,
				};
				var request = submitRequest(data, "delete");
				request.then(function (response) {
					if (response.status === "ok") {
						self.onCustomerUpdated(response.customer);
					}
				});
			}
		},
		addAddress: function () {
			bus.$emit("initEditAddress", {
				mode: "add",
				customerId: this.customer.id,
			});
		},
		handleEditAddress: function (index) {
			var add = this.getAddress(index);
			bus.$emit("initEditAddress", {
				mode: "edit",
				address: JSON.parse(JSON.stringify(add)),
			});
		},
		getAddress: function (index) {
			return this.customer.addresses[index];
		},
		detailOrder: function () {
			var self = this;
			var data = {
				module: "orders",
				method: "order",
				id: self.customer.id,
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				if (response.status === "ok") {
					self.order = response.orders;
				} else {
					ds_alert(response.message.text, response.message.type);
				}
			});
		},
		getAddressTitle: function (a) {
			var self = this;
			//this.city = {};
			//self.getCity(a.cityId);

			var title = "";
			title = a.address1 + " , " + a.address2 + " , " + a.zipCode;
			return title;
		},
		getCities: async function () {
			var self = this;
			var data = {
				module: "core/cities",
				method: "select_cities",
			};
			var response = await submitRequest(data, "get");
			if (response.status === "ok") {
				self.masters.cities = response.cities;
			}
		},
		getCityMeta: function (id) {
			var city = this.masters.cities.find(function (c) {
				return Number(c.id) === Number(id);
			});
			return city.name;
		},
		getCity: async function (id) {
			var self = this;
			var data = {
				city_id: id,
				module: "core/cities",
				method: "select_city",
			};
			var response = await submitRequest(data, "get");
			if (response.status === "ok") {
				self.city = response.city;
			}
		},
		populateMeta: async function () {
			var self = this;
			var data = {
				module: this.module,
				method: "populate",
			};
			var response = await submitRequest(data, "get");
			if (response.status === "ok") {
				self.masters.countries = response.countries;
				self.masters.states = response.states;
				self.masters.groups = response.groups;
			}
			return true;
		},
		handleAddAddress: function (id) {
			var self = this;
			var data = {
				module: this.module,
				method: "single",
				id: id,
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				self.customer = response.obj;
				self.$bvModal.show("customer-address-modal");
				self.detailOrder();
			});
		},
		handleAdd: function () {
			bus.$emit("addItem", "");
		},
		handleEdit: function (id) {
			bus.$emit("editItem", id);
		},
		handleRemove: function (url) {
			var confirm = ds_confirm("Are you sure to delete this customer?");
			if (confirm) {
				window.location = url;
			}
		},
		handleCloseModal: function () {
			this.modal.activeTab = "basic";
			this.$bvModal.hide("customer-address-modal");
		},
		onCustomerUpdated: function (payload) {
			var self = this;
			self.customer = payload;
		},
	},
	created: function () {},
	mounted: function () {
		//loadDataTable();
		//console.log(Codebase);
		//codebase.blocks('#block-list', 'state_loading');
	},
});
Vue.component("edit-address", {
	template: "#edit-address-template",
	data: function () {
		return {
			module: "contacts/customers",
			modal: {
				id: "edit-address-modal",
			},
			address: {},
			masters: {
				countries: [],
				states: [],
				cities: [],
			},
			mode: null,
			customerId: null,
		};
	},
	watch: {
		"address.stateId": {
			handler: function (after, before) {
				if (after !== "" && before !== after) {
					this.populateCites(after);
				}
			},
			deep: true,
		},
	},
	computed: {
		groupTitle: function () {
			var title = "";
			if (typeof this.customer.group !== "undefined") {
				title = this.customer.group.title;
			}
			return title;
		},
		addressList: function () {
			return this.customer.addresses;
		},
		addressTitle:function(){
			return this.mode ==='add' ?'Add Address':'Edit Address'
		}
	},
	methods: {
		addressBlankObj: function () {
			return {
				id: "",
				title: "",
				customerId: "",
				address1: "",
				address2: "",
				countryId: _s('defaultCountryId'),
				stateId: _s('defaultStateId'),
				cityId: _s('defaultCityId'),
				zipCode: "",
				added: "",
			};
		},
		populateCites: async function (id) {
			Codebase.blocks("#edit-address-block", "state_loading");
			var self = this;
			var data = {
				state_id: id,
				country_id: self.address.countryId,
				module: "core/cities",
				method: "select_data",
			};
			var response = await submitRequest(data, "get");
			if (response.status === "ok") {
				self.masters.cities = response.cities;
			}
			Codebase.blocks("#edit-address-block", "state_normal");
		},
		populateMeta: async function () {
			var self = this;
			var data = {
				module: this.module,
				method: "populate",
			};
			var response = await submitRequest(data, "get");
			if (response.status === "ok") {
				self.masters.countries = response.countries;
				self.masters.states = response.states;
				self.masters.groups = response.groups;
			}
			return true;
		},
		onInitAddress: function (payload) {
			this.$bvModal.show(this.modal.id);
			this.populateMeta();
			if (payload.mode === "edit") {
				this.mode = "edit";
				this.address = payload.address;
				//var addressId = payload.addressId;
				//this.detailAddress(addressId);
			} else {
				this.customerId = payload.customerId;
				this.address.customerId = payload.customerId;
				this.mode = "add";
			}
		},
		handleCloseModal: function (modal) {
			this.$bvModal.hide(modal);
		},
		detailAddress: function (addressId) {
			var self = this;
			var data = {
				module: self.module,
				method: "address",
				id: addressId,
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				if (response.status === "ok") {
					self.address = response.address;
				} else {
					ds_alert(response.message.text, response.message.type);
				}
			});
		},
		handleSubmit: async function () {
			var self = this;
			var form = $("#frm-edit-address");
			/* if (form.parsley().validate()) {
				 console.log("op");
			bus.$emit("initSaveAddress", {
				address: self.address,
				mode: self.mode,
			}); 
				self.hideModel();
			}  */
			if (form.parsley().validate()) {
				var method = self.mode === "edit" ? "post" : "put";
				var data = {
					module: self.module,
					method: "address",
					obj: self.address,
				};
				var response = await submitRequest(data, method);
				if (response.status === "ok") {
					self.$emit("updated", response.customer);
					self.hideModel();
				}
			}
		},

		hideModel: function () {
			this.addressBlankObj();
			this.handleCloseModal(this.modal.id);
		},
	},
	created: function () {
		var self = this;
		bus.$on("initEditAddress", function (payload) {
			self.address = self.addressBlankObj();
			self.customerId = null;
			console.log("op");
			self.onInitAddress(payload);
		});
	},
});
Vue.component("customers-import-export", {
	template: "#customers-import-export-template",
	data: function () {
		return {
			exportUrl: _s("exportUrl"),
		};
	},
	methods: {
		handleImport: function () {
			bus.$emit("showImportModal");
		},
		handleExport: function () {
			window.location = this.exportUrl;
		},
	},
});
Vue.component("customers-import", {
	template: "#customers-import-template",
	data: function () {
		return {
			modal: {
				id: "customers-import-modal",
				title: "Choose Excel File",
			},
			file: {
				xlsx: {},
			},
		};
	},
	methods: {
		initImport: function (payload) {
			this.$bvModal.show(this.modal.id);
		},
		hideModal: function () {
			this.$bvModal.hide(this.modal.id);
		},
		handleSubmit: function () {
			var self = this;
			var form = $("#frm-import-file");
			if (form.parsley().validate()) {
				Codebase.blocks("#items-import-modal-block", "state_loading");
				var data = {
					module: "items/imports",
					method: "file",
					type: "single",
				};
				var request = uploadRequest(data, this.file, "put");
				request.then(function (response) {
					if (response.status === "ok") {
						Codebase.blocks("#items-import-modal-block", "state_normal");
						self.$bvModal.hide(self.modal.id);
						window.location = response.redirect;
					}
				});
			}
		},
	},
	created: function () {
		var self = this;
		bus.$on("showImportModal", function (payload) {
			self.initImport(payload);
		});
	},
});
Vue.component("order-details", {
	template: "#order-details-template",
	data: function () {
		return {
			module: "orders",
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
		handleDownloadPdf: function () {
			Object.assign(document.createElement("a"), {
				target: "_blank",
				href: this.modal.obj.pdfUrl,
			}).click();
		},
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
		getTotalPaid: function () {
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
				module: this.module,
				method: "single_view",
				id: id,
			};

			var request = submitRequest(data, "get");
			request.then(function (response) {
				self.modal.obj = response.obj;
				self.$bvModal.show("order-details-modal");
			});
		},
	},
	created: function () {
		var self = this;
		bus.$on("initOrderDetails", function (payload) {
			self.populateMeta();
			self.handleViewOrder(payload.id);
		});
	},
});
Vue.component("customer-details", {
	template: "#customer-details-template",
	data: function () {
		return {
            customer: _s('customer'),
			filter: _s('filter'),
            tabIndex: 0,
			orders:_s('orders'),
			editUrl: _s("editUrl"),
			yearlyData: _s("yearlyData"),
		};
	},
	computed: {
		pdfUrl:function(){
			return this.customer.pdfUrl
		},
		customerFName:function(){
			return this.customer.firstName ??'-'
		},
		customerLName:function(){
			return this.customer.lastName ??'-'
		},
		customerName:function(){
			return this.customerFName ? this.customerFName + ' '+ this.customerLName :'-'
		},
		customerGroup:function(){
			return this.customer.group.title;
		},
		customerFullVaccinated:function(){
			return this.customer.fullVaccinated =='1' ? 'Yes' :'No'
		},
		customerAddresses:function(){
			return this.customer.addresses;
		},
		customerItems:function(){
			return this.customer.items
		},
		customerOrders:function(){
			return this.orders
		},
		refundTotal: function () {
			return Number(this.filter.refundTotal);
		},
		avgOrder: function () {
			return Number(this.filter.avgOrder);
		},
		avgPayOrder: function () {
			return Number(this.filter.avgPayOrder);
		},
		dineOrder: function () {
			return Number(this.filter.dineOrder);
		},
		pickUpOrder: function () {
			return Number(this.filter.pickUpOrder);
		},
		cancelled: function () {
			return Number(this.filter.cancelled);
		},
		totalDiscount: function () {
			return Number(this.filter.discount);
		},
		totalTip: function () {
			return Number(this.filter.tip);
		},
		firstOrder: function () {
			return this.filter.firstOrder;
		},
		lastOrder: function () {
			return this.filter.lastOrder;
		},
		days: function () {
			return Number(this.filter.days);
		},
		closed: function () {
			return (
				Number(this.filter.closed) +
				Number(this.filter.partialRefunded)
			);
		},
		avgVisited:function(){
			return this.closed !== 0 ? Number(this.days/this.closed).toFixed(2) + ' Days'  :0
		},
		totalEarnings: function () {
			return (
				Number(this.filter.totalEarnings) -
				Number(this.filter.refundTotal)
			);
		},
		avgYearCount: function () {
			var months = _s("this_year_months");
			var n = months.length;
			var yearCount = Number(this.yearlyData.yearCount);
			var avgYearCount = yearCount / n;
			return Math.ceil(Number(avgYearCount))
				? Math.ceil(Number(avgYearCount))
				: 0;
		},
		avgYearEarnings: function () {
			
			var months = _s("this_year_months");
			var n = months.length;
			if (Number(n) === 0) {
				n += 1;
			}

			var yearCount = Number(this.yearlyData.yearEarnings)
				? Number(this.yearlyData.yearEarnings)
				: 0;
			var avgYearEarnings = yearCount / n;

			return Number(avgYearEarnings).toFixed(2)
				? Number(avgYearEarnings).toFixed(2)
				: 0;
		},
		yearCount: function () {
			return Number(this.yearlyData.yearCount)
				? Number(this.yearlyData.yearCount)
				: 0;
		},
		yearEarnings: function () {
			return Number(this.yearlyData.yearEarnings)
				? Number(this.yearlyData.yearEarnings)
				: 0;
		},
		
	},
	methods: {
		getAddress: function (index) {
			return this.customer.addresses[index];
		},
		handleEditAddress: function (index) {
			var add = this.getAddress(index);
			bus.$emit("initEditAddress", {
				mode: "edit",
				address: JSON.parse(JSON.stringify(add)),
			});
		},
		handleSubmit: async function () {
			var self = this;
			var form = $("#frm-edit-address");
			/* if (form.parsley().validate()) {
				 console.log("op");
			bus.$emit("initSaveAddress", {
				address: self.address,
				mode: self.mode,
			}); 
				self.hideModel();
			}  */
			if (form.parsley().validate()) {
				var method = self.mode === "edit" ? "post" : "put";
				var data = {
					module: self.module,
					method: "address",
					obj: self.address,
				};
				var response = await submitRequest(data, method);
				if (response.status === "ok") {
					self.$emit("updated", response.customer);
					self.hideModel();
				}
			}
		},
		onCustomerUpdated: function (payload) {
			var self = this;
			self.customer = payload;
		},
		getAddressTitle: function (a) {
			var self = this;
			//this.city = {};
			//self.getCity(a.cityId);

			var title = "";
			title = a.address1 + " , " + a.address2 + " , " + a.zipCode;
			return title;
		},
		deleteAddress: function (id) {
			console.log(id);
			if (ds_confirm("Are you sure to delete this address ?")) {
				var self = this;
				var data = {
					module: self.module,
					method: "address",
					customerId: self.customer.id,
					addressId: id,
				};
				var request = submitRequest(data, "delete");
				request.then(function (response) {
					if (response.status === "ok") {
						self.onCustomerUpdated(response.customer);
					}
				});
			}
		},
		addAddress: function () {
			bus.$emit("initEditAddress", {
				mode: "add",
				customerId: this.customer.id,
			});
		},
		getOrders:function(){
			var self = this;
			var data = {
				module: "orders",
				method: "order",
				id: self.customer.id,
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				if (response.status === "ok") {
					self.orders = response.orders;
				} else {
					ds_alert(response.message.text, response.message.type);
				}
			});
		},
		getItems: function (items) {
			var string = "";
			if (items.length) {
				items.forEach(function (item) {
					if (item) {
						if (string !== "") {
							string += ", " + item.title;
						} else {
							string += item.title;
						}
					}
				});
			}
			return string;
		},
		handleOrderDetails: function (id) {
			bus.$emit("initOrderDetails", { id: id });
		},
	},
    created: function(){
		
    },
	mounted: function () {},
});
