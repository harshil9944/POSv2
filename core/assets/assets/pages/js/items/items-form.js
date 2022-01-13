Vue.component("vue-multiselect", window.VueMultiselect.default);
Vue.directive("select", {
	twoWay: true,
	bind: function (el, binding, vnode) {
		$(el)
			.select2()
			.on("select2:select", function (e) {
				// v-model looks for
				//  - an event named "change"
				//  - a value with property path "$event.target.value"
				el.dispatchEvent(new Event("change", { target: e.target }));
			});
	},
});

Vue.component("add-feature", {
	template: "#add-feature-template",
	data: function () {
		return {
			feature: {
				title: "",
			},
		};
	},
	methods: {
		handleAddFeature: function (bvModalEvt) {
			var self = this;
			bvModalEvt.preventDefault();
			var form = $("#frm-add-feature");
			if (form.parsley().validate()) {
				var data = {
					module: "items",
					method: "add_feature",
					obj: this.feature,
				};
				var request = submitRequest(data, "put");
				request.then(function (response) {
					if (response.status == "ok") {
						var obj = {
							id: response.obj.id,
							value: response.obj.title,
						};
						bus.$emit("new_feature_added", obj);
						self.$bvModal.hide("add-feature-modal");
					}
				});
			}
		},
	},
});

Vue.component("add-category", {
	template: "#add-category-template",
	data: function () {
		return {
			category: {
				title: "",
				parent: "",
			},
		};
	},
	methods: {
		handleAddCategory: function (bvModalEvt) {
			var self = this;
			bvModalEvt.preventDefault();
			var form = $("#frm-add-category");
			if (form.parsley().validate()) {
				var data = {
					module: "items",
					method: "add_category",
					obj: this.category,
				};
				var request = submitRequest(data, "put");
				request.then(function (response) {
					if (response.status == "ok") {
						var obj = {
							id: response.obj.id,
							value: response.obj.title,
						};
						bus.$emit("new_category_added", obj);
						self.$bvModal.hide("add-category-modal");
					}
				});
			}
		},
	},
});

Vue.component("add-unit", {
	template: "#add-unit-template",
	data: function () {
		return {
			unit: {
				title: "",
				parent: "",
			},
		};
	},
	methods: {
		handleAddUnit: function (bvModalEvt) {
			var self = this;
			bvModalEvt.preventDefault();
			var form = $("#frm-add-unit");
			if (form.parsley().validate()) {
				var data = {
					module: "core/units",
					obj: this.unit,
				};
				var request = submitRequest(data, "put");
				request.then(function (response) {
					if (response.status == "ok") {
						var obj = {
							id: response.obj.id,
							value: response.obj.title,
						};
						bus.$emit("new_unit_added", obj);
						self.$bvModal.hide("add-unit-modal");
					}
				});
			}
		},
	},
});

Vue.component("item-form", {
	template: "#items-form-template",
	data: function () {
		return {
			masters: {
				categories: [],
				features: [],
				units: [],
				addonItems: [],
				subUnits: [{ id: "", value: "Select Unit first" }],
				vendors: [],
				taxable: [
					{ id: 1, value: "Yes" },
					{ id: 2, value: "No" },
				],
				printLocations: [],
				hasSpiceLevel: [
					{ id: 0, value: "No" },
					{ id: 1, value: "Yes" },
				],
				taxInclusive: [
					{ id: 0, value: "No" },
					{ id: 1, value: "Yes" },
				],
				isVeg: [
					{ id: 0, value: "No" },
					{ id: 1, value: "Yes" },
				],
				icons: [],
				webStatuses: [
					{ id: 1, value: "Yes" },
					{ id: 0, value: "No" },
				],
				posStatuses: [
					{ id: 1, value: "Yes" },
					{ id: 0, value: "No" },
				],
				appStatuses: [
					{ id: 1, value: "Yes" },
					{ id: 0, value: "No" },
				],
			},
			item: {},
			file: {
				image: "",
			},
		};
	},
	watch: {
		"item.openingStock": {
			handler: function (after, before) {
				this.item.openingStock = isNaN(after) ? before : after;
			},
			deep: true,
		},
		"item.openingStockValue": {
			handler: function (after, before) {
				this.item.openingStockValue = isNaN(after) ? before : after;
			},
			deep: true,
		},
		"item.reorderLevel": {
			handler: function (after, before) {
				this.item.reorderLevel = isNaN(after) ? before : after;
			},
			deep: true,
		},
	},
	methods: {
		unitTitle: function (id) {
			var result = this.masters.units.find(function (unit) {
				return unit.id === id;
			});
			return result ? result.value : "";
		},
		populateMeta: function () {
			var self = this;
			var data = {
				module: "items",
				method: "populate",
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				if (response.status === "ok") {
					self.masters.addonItems = response.addonItems;
					self.masters.categories = response.categories;
					self.masters.features = response.features;
					self.masters.units = response.units;
					self.masters.vendors = response.vendors;
					self.masters.icons = response.icons;
					self.masters.printLocations = response.printLocations;
				}
			});
		},
		populateSingle: function () {
			var self = this;
			var method = "get";
			var data = {
				module: "items",
				method: "single",
				id: _s("id"),
			};
			if (method) {
				var request = submitRequest(data, method);
				request.then(function (response) {
					if (response.status === "ok") {
						self.item = response.obj;
						self.item.addons.forEach(function (a, i) {
							var addonItem = self.masters.addonItems.find(function (ai) {
								return a.addonItemId.id === ai.id;
							});
							self.item.addons[i].addonItemId.title = addonItem.title;
						});
						self.item.weight = Number(self.item.weight).toFixed(2);
					}
				});
			}
		},
		onUnitSelect: function (ref) {
			var self = this;
			self.updatePrices();
			return true;
			/*if(this.item.unit!=='') {
                if(ref!=='load') {
                    self.item.prices = [];
                    self.item.purchaseUnit = null;
                    self.item.saleUnit = null;
                }
                self.masters.subUnits = [];
                self.masters.units.forEach(function(unit){
                    if(unit.id!=='') {
                        self.masters.subUnits.push(unit);
                    }
                });
                if(ref!=='load') {
                    self.item.purchaseUnit = (self.item.purchaseUnit == null) ? self.item.unit : '';
                    self.item.saleUnit = (self.item.saleUnit == null) ? self.item.unit : '';
                }
                self.updatePrices();

                var method = 'get';
                var data = {
                    module: 'core/units',
                    method: 'sub_select_data',
                    id: this.item.unit
                };
                if (method) {
                    var request = submitRequest(data, method);
                    request.then(function (response) {
                        if (response.status == 'ok') {
                            self.masters.subUnits = response.subUnits;
                            if(ref!='load') {
                                self.item.purchaseUnit = (self.item.purchaseUnit == null) ? self.item.unit : '';
                                self.item.saleUnit = (self.item.saleUnit == null) ? self.item.unit : '';
                            }
                            self.updatePrices();
                        }
                    });
                }
            }*/
		},
		removeImage: function () {
			this.item.image = "";
			this.file.image = null;
		},
		priceOject: function () {
			return {
				itemId: "",
				skuId: "",
				unitId: 1, //unit,
				conversionRate: 1,
				purchaseCurrency: "",
				saleCurrency: "",
				purchasePrice: "",
			};
		},
		updatePrices: function () {
			var self = this;
			var selectedUnits = this.getFilteredSelectedUnits();
			if (selectedUnits.length) {
				this.clearPrices(selectedUnits);
				selectedUnits.forEach(function (unit) {
					var price = {
						itemId: "",
						skuId: "",
						unitId: 1, //unit,
						conversionRate: self.item.unit === unit ? 1 : "",
						purchaseCurrency: "",
						saleCurrency: "",
						purchasePrice: "",
					};
					if (self.item.prices.length) {
						var index = self.item.prices.findIndex(function (row) {
							return row.unitId === unit;
						});
						if (index === -1) {
							self.item.prices.push(price);
						}
					} else {
						self.item.prices.push(price);
					}
				});
			}
		},
		getFilteredSelectedUnits: function () {
			var selectedUnits = [];
			if (this.item.unit) {
				selectedUnits.push(this.item.unit);
			}
			if (this.item.purchaseUnit) {
				if (selectedUnits.indexOf(this.item.purchaseUnit) === -1) {
					selectedUnits.push(this.item.purchaseUnit);
				}
			}
			if (this.item.saleUnit) {
				if (selectedUnits.indexOf(this.item.saleUnit) === -1) {
					selectedUnits.push(this.item.saleUnit);
				}
			}
			return selectedUnits;
		},
		clearPrices: function (newUnits) {
			var self = this;
			if (self.item.prices.length) {
				var prices = JSON.parse(JSON.stringify(self.item.prices));
				prices.forEach(function (price, index) {
					if (newUnits.indexOf(price.unitId) === -1) {
						self.item.prices.splice(index, 1);
					}
				});
			}
		},
		handleBlankFeature: function () {
			var newFeature = {
				itemId: "",
				featureId: "",
				title: "",
			};
			this.item.features.push(newFeature);
		},
		handleBlankNote: function () {
			this.item.notes.push({
				id: "",
				title: "",
			});
		},
		handleBlankAddon: function () {
			this.item.addons.push({
				id: "",
				itemId: "",
				skuId: 0,
				addonItemId: "",
				type: "optional",
				title: "",
				salePrice: "",
			});
		},
		handleRemoveAddon: function (i) {
			this.item.addons.splice(i, 1);
		},
		handleRemoveNote: function (i) {
			this.item.notes.splice(i, 1);
		},
		duplicateSku: function (string, field) {
			var mode = _s("mode");
			if (mode === "edit") {
				return false;
			}
			var result = false;
			/*var field = this.$refs.email;*/
			$(field).parsley().removeError("sku_duplicate");
			var action = _s("action");
			var url = action + "?module=items&method=duplicate_sku&sku=" + string;

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
		handleSubmit: function () {
			var form = $("#frm-item");
			var field = this.$refs.sku;
			if (form.parsley().validate()) {
				if (!this.duplicateSku(this.item.sku, field)) {
					var method = "";
					var mode = _s("mode");
					if (mode === "add") {
						method = "put";
					} else if (mode === "edit") {
						method = "post";
					}

					var data = {
						module: "items",
						obj: this.item,
					};
					if (method) {
						var request = uploadRequest(data, this.file, method);
						request.then(function (response) {
							if (response.status === "ok") {
								window.location = response.redirect;
							}
						});
					} else {
						alert("Something went wrong!");
					}
				} else {
					$(field)
						.parsley()
						.addError("sku_duplicate", { message: "SKU already exists." });
				}
			}
		},
		cancel: function () {
			window.location = _s("back_url");
		},
	},
	created: function () {
		var self = this;
		var mode = _s("mode");

		bus.$on("new_feature_added", function (feature) {
			self.masters.features.push(feature);
		});
		bus.$on("new_category_added", function (category) {
			self.masters.categories.push(category);
			self.item.categoryId = category.id;
		});
		bus.$on("new_unit_added", function (unit) {
			self.masters.units.push(unit);
			self.item.unit = unit.id;
		});

		this.item = {
			name: "",
			sku: "",
			skuId: "",
			categoryId: "",
			unit: 1,
			purchaseUnit: null,
			printLocation: "default",
			saleUnit: null,
			weight: "",
			manufacturer: "",
			upc: "",
			ean: "",
			taxable: _s("defaultTaxable"),
			taxInclusive: "",
			hasSpiceLevel: 0,
			openingStock: "",
			openingStockValue: "",
			reorderLevel: "",
			preferredVendor: "",
			features: [],
			addons: [],
			notes: [],
			prices: [],
			isVeg: 1,
			image: "",
			icon: "",
			webStatus: 1,
			posStatus: 1,
			appStatus: 1,
		};
		this.item.prices.push(this.priceOject());
		this.populateMeta();
		if (mode === "edit") {
			this.populateSingle();
		}
	},
});
