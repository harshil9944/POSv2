Vue.filter("imagePath", function (src) {
	if (src !== "" && src != null) {
		return _s("imgCacheUrl") + src;
	} else {
		return _s("noImgUrl");
	}
});
Vue.component("items-import-export", {
	template: "#items-import-export-template",
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
Vue.component("items-import", {
	template: "#items-import-template",
	data: function () {
		return {
			modal: {
				id: "items-import-modal",
				title: "Choose Excel File",
			},
			url: {},
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
					type: "group",
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
		bus.$on("showImportModal", function () {
			self.initImport();
		});
	},
});
Vue.component("general-list", {
	template: "#general-list-template",
	data: function () {
		return {
			modal: {
				obj: {
					addons: [],
					notes: [],
				},
				activeWhIndex: 0,
				activeWh: {
					warehouse: "",
					price: [],
				},
			},
			module: "items/item_groups",
		};
	},
	computed: {
		getTaxable: function () {
			return this.modal.obj.taxable === "1" ? "Yes" : "No";
		},
		getHasSpiceLevel: function () {
			return this.modal.obj.hasSpiceLevel === "1" ? "Yes" : "No";
		},
		getPosStatus: function () {
			return this.modal.obj.posStatus === "1" ? "Yes" : "No";
		},
		getWebStatus: function () {
			return this.modal.obj.webStatus === "1" ? "Yes" : "No";
		},
		getAppStatus: function () {
			return this.modal.obj.appStatus === "1" ? "Yes" : "No";
		},
		getUnit: function () {
			return this.modal.obj.unit === "1" ? "Plate" : "Pcs";
		},
		getCategoryTitle: function () {
			return this.modal.obj.categoryTitle;
		},
		getPrintLocation: function () {
			if (this.modal.obj.printLocation === "default") {
				return "No Printout";
			} else if (this.modal.obj.printLocation === "kitchen") {
				return "Kitchen";
			} else {
				return "Front Kitchen";
			}
		},
	},
	methods: {
		handleAdd: function () {
			bus.$emit("addItem", "");
		},
		handleEdit: function (id) {
			bus.$emit("editItem", id);
		},
		handleViewStock: function (id) {
			var self = this;
			var data = {
				module: this.module,
				method: "single_stock",
				id: id,
			};

			var request = submitRequest(data, "get");
			request.then(function (response) {
				self.modal.obj = response.obj;
				//self.switchWh(0);
				self.$bvModal.show("item-details-modal");
			});
		},
		handleRemove: function (id) {
			if (ds_confirm("Are you sure to delete this item?")) {
				var data = {
					module: this.module,
					id: id,
				};
				var request = submitRequest(data, "delete");
				request.then(function (response) {
					if (response.status === "ok") {
						window.location = response.redirect;
					} else {
						var message = "";
						if (typeof response.message != "undefined") {
							message = response.message;
						}
						ds_alert(message);
					}
				});
			}
		},
		switchWh: function (index) {
			var self = this;
			self.modal.activeWhIndex = index;
			if (self.modal.obj.price) {
				self.modal.activeWh = self.modal.obj.price[self.modal.activeWhIndex];
			}
		},
	},
	mounted: function () {
		//loadDataTable();
	},
});
