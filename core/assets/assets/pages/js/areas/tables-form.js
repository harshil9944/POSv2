Vue.component("table-form", {
	template: "#table-form-template",
	data: function () {
		return {
			mode: _s("mode"),
			table: {},
			masters: {
				areas: [],
				statuses: [],
			},
		};
	},
	methods: {
		submit: function () {
			var form = $("#frm-table");
			if (form.parsley().validate()) {
				var method = "";
				if (this.mode === "add") {
					method = "put";
				} else if (this.mode === "edit") {
					method = "post";
				}

				var data = {
					module: "areas/tables",
					table: this.table,
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
	mounted: function () {
		if (this.mode == "add") {
			this.table = {
				title: "",
				description: "",
				max_seat: "",
				short_name: "",
				status: "available",
				sort_order: "",
				area_id: "",
			};
		} else if (this.mode == "edit") {
			var table = _s("table");
			this.table = table;
		}
		this.masters.areas = _s("areas");
		this.masters.statuses = _s("statuses");
	},
});
