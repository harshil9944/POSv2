Vue.component("customer-groups-form", {
	template: "#customer-groups-template",

	data: function () {
		return {
			module: "contacts/customer_groups",
			mode: _s("mode"),
			customer_group: {},
			statuses: [
				{ id: 1, value: "Enabled" },
				{ id: 0, value: "Disabled" },
			],
		};
	},
	watch: {},
	methods: {
		populateSingle: function () {
			var self = this;
			var method = "get";
			var data = {
				module: "contacts/customer_groups",
				method: "single",
				id: _s("id"),
			};
			if (method) {
				var request = submitRequest(data, method);
				request.then(function (response) {
					if (response.status == "ok") {
						self.customer_group = response.obj;
					}
				});
			}
		},

		handleSubmit: function () {
			var form = $("#frm-customer-groups");
			if (form.parsley().validate()) {
				var method = "";
				if (this.mode === "add") {
					method = "put";
				} else if (this.mode === "edit") {
					method = "post";
				}

				var data = {
					module: "contacts/customer_groups",
					obj: this.customer_group,
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
		cancel: function () {
			window.location = _s("back_url");
		},
	},
	created: function () {
		this.customer_group = {
			id: "",
			title: "",
			posDiscount: 0,
			webDiscount: 0,
			appDiscount: 0,
			status: 1,
			added: "",
		};
		if (this.mode === "edit") {
			this.populateSingle();
		}
	},
});
