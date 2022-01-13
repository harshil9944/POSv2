//var codebase = Codebase;
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
			console.log("op");
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
	},
	methods: {
		addressBlankObj: function () {
			return {
				id: "",
				title: "",
				customerId: "",
				address1: "",
				address2: "",
				countryId: 38,
				stateId: "",
				cityId: "",
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
