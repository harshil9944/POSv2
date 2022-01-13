Vue.component("customer-form", {
	template: "#customer-form-template",
	mixins: [customerCustomFieldsMixin],
	data: function () {
		return {
			module: "contacts/customers",
			masters: {
				countries: [],
				states: [],
				groups: [],
			},
			mode: _s("mode"),
			customer: {},
			allowCustomerGroup: _s("allowCustomerGroup"),
			allowCustomerNotes: _s("allowCustomerNotes"),
		};
	},
	watch: {},
	methods: {
		onName: function () {
			if (
				this.customer.displayName.trim() === "" ||
				this.customer.displayName.trim() === this.customer.firstName.trim() ||
				this.customer.displayName.trim() === this.customer.lastName.trim()
			) {
				this.customer.displayName =
					this.customer.firstName.trim() + " " + this.customer.lastName.trim();
			}
		},
		populateMeta: function () {
			var self = this;
			var data = {
				module: "contacts/customers",
				method: "populate",
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				if (response.status === "ok") {
					self.masters.countries = response.countries;
					self.masters.states = response.states;
					self.masters.groups = response.groups;
					if (self.mode === "add") {
						self.customer.customerId = response.newCustomerId;
					}
				}
			});
		},
		/* populateStates: function (id, type) {
			var self = this;
			var data = {
				country_id: id,
				module: "core/states",
				method: "select_data",
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				if (response.status == "ok") {
					self.masters.states[type] = response.states;
				}
			});
		}, */
		populateSingle: function () {
			var self = this;
			var method = "get";
			var data = {
				module: "contacts/customers",
				method: "single",
				id: _s("id"),
			};
			if (method) {
				var request = submitRequest(data, method);
				request.then(function (response) {
					if (response.status == "ok") {
						var obj = response.obj;
						self.customer = obj;
					}
				});
			}
		},
		onCompanyName: function () {
			if (this.customer.displayName === "") {
				this.customer.displayName = this.customer.companyName;
			}
		},
		handleSubmit: function () {
			var error = false;
			var self = this;
			var form = $("#frm-customer");

			if (!form.parsley().validate()) {
				error = true;
			}
			if (this.customer.email) {
				if (this.isEmailDuplicate()) {
					var email_field = this.$refs.email;
					$(email_field)
						.parsley()
						.addError("email_duplicate", { message: "Email already exists." });
					error = true;
				}
			}
			if (this.customer.phone) {
				if (this.isPhoneDuplicate()) {
					var phone_field = this.$refs.phone;
					$(phone_field)
						.parsley()
						.addError("phone_duplicate", { message: "Mobile already exists." });
					error = true;
				}
			}

			if (!error) {
				var method = "";
				if (this.mode === "add") {
					method = "put";
				} else if (this.mode === "edit") {
					method = "post";
				}

				var data = {
					module: "contacts/customers",
					obj: this.customer,
				};
				if (method) {
					var request = submitRequest(data, method);
					request.then(function (response) {
						if (response.status === "ok") {
							window.location = response.redirect;
						}
					});
				} else {
					alert("Something went wrong!");
				}
			}
		},
		isEmailDuplicate: function () {
			var result = false;
			var string = this.customer.email;
			var field = this.$refs.email;
			var id = this.customer.id;
			$(field).parsley().removeError("email_duplicate");
			var url =
				_s("action") +
				"?module=" +
				this.module +
				"&method=duplicate_email&email=" +
				string;
			if (id) {
				url += "&id=" + id;
			}
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function () {
				if (this.status === 200) {
					var response = JSON.parse(this.responseText);
					result = response.result;
				}
			};
			xhttp.open("GET", url, false);
			xhttp.send();
			return result;
		},
		isPhoneDuplicate: function () {
			var result = false;
			var string = this.customer.phone;
			var field = this.$refs.phone;
			var id = this.customer.id;
			$(field).parsley().removeError("phone_duplicate");
			var url =
				_s("action") +
				"?module=" +
				this.module +
				"&method=duplicate_phone&phone=" +
				string;
			if (id) {
				url += "&id=" + id;
			}
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function () {
				if (this.status === 200) {
					var response = JSON.parse(this.responseText);
					result = response.result;
				}
			};
			xhttp.open("GET", url, false);
			xhttp.send();
			return result;
		},
		cancel: function () {
			window.location = _s("back_url");
		},
		AddBlankAddress: function () {
			var self = this;
			var address = self.blankAddress();
			this.customer.push(address);
		},
		blankAddress: function () {
			return {
				id: "",
				title: "",
				address1: "",
				address2: "",
				cityId: "",
				stateId: "",
				zipCode: "",
				countryId: 38,
				customerId: "",
			};
		},
	},
	created: function () {
		this.customer = {
			customerId: "",
			groupId: 1,
			firstName: "",
			lastName: "",
			displayName: "",
			email: "",
			phone: "",
			memberNumber: "",
			fullVaccinated: 0,
			notes: "",
			address: this.blankAddress(),
			status: 1,
			defaultAddressId: "",
		};
		this.populateMeta();
		if (this.mode === "edit") {
			this.populateSingle();
		}
		this.customerCustomFields = _s("customerCustomFields");
	},
});
