Vue.component("vue-multiselect", window.VueMultiselect.default);
var f = function (arr) {
	if (typeof arr !== "object") {
		return false;
	}
	arr = arr.filter(function (elem) {
		return elem !== null;
	}); // remove empty elements - make sure length is correct
	var len = arr.length;
	var nextPerm = function () {
		// increase the counter(s)
		var i = 0;
		while (i < len) {
			arr[i].counter++;
			if (arr[i].counter >= arr[i].length) {
				arr[i].counter = 0;
				i++;
			} else {
				return false;
			}
		}
		return true;
	};
	var getPerm = function () {
		// get the current permutation
		var perm_arr = [];
		for (var i = 0; i < len; i++) {
			perm_arr.push(arr[i][arr[i].counter]);
		}
		return perm_arr;
	};
	var new_arr = [];
	for (var i = 0; i < len; i++) {
		arr[i].counter = 0;
	}
	while (true) {
		new_arr.push(getPerm()); // add current permutation to the new array
		if (nextPerm() === true) {
			// get next permutation, if returns true, we got them all
			break;
		}
	}
	return new_arr;
};
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
Vue.component("add-option", {
	template: "#add-option-template",
	data: function () {
		return {
			option: {
				title: "",
			},
		};
	},
	methods: {
		handleAddOption: function (bvModalEvt) {
			/* var self = this;
            bvModalEvt.preventDefault();
            var form = $('#frm-add-option');
            if(form.parsley().validate()) {
                var data = {
                    module: 'items/item_groups',
                    method: 'add_option',
                    obj: this.option
                }
                var request = submitRequest(data, 'put');
                request.then(function (response) {
                    if (response.status == 'ok') {
                        var obj = {
                            id: response.obj.id,
                            value: response.obj.title
                        }
                        bus.$emit('new_option_added', obj);
                        self.$bvModal.hide('add-option-modal');
                    }
                });
            }*/
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
Vue.component("item-group-form", {
	template: "#item-group-form-template",
	data: function () {
		return {
			masters: {
				categories: [],
				units: [],
				subUnits: [{ id: "", value: "Select Unit first" }],
				options: [],
				printLocations: [],
				hasSpiceLevel: [
					{ id: 0, value: "No" },
					{ id: 1, value: "Yes" },
				],
				taxInclusive: [
					{ id: 0, value: "No" },
					{ id: 1, value: "Yes" },
				],
				taxable: [
					{ id: 1, value: "Yes" },
					{ id: 2, value: "No" },
				],
				isVeg: [
					{ id: "", value: "Select" },
					{ id: 1, value: "Yes" },
					{ id: 0, value: "No" },
				],
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
				icons: [],
			},
			vueTagInput: {
				addOnKey: [13],
			},
			item: {},
			obj: {},
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

		"item.skus": {
			handler: function (after, before) {
				/*var self = this;
               if(typeof before != "undefined") {
                    if(before.length) {
                        var numberFields = ['purchasePrice', 'sellingPrice'];
                       // var numberFields = ['purchasePrice'];
                        numberFields.forEach(function (field) {
                            self.item.skus.forEach(function (sku, index) {
                                var val = sku[field];
                                if (val.toString() !== '') {
                                    val = val.replace(/[^0-9\.]/g, '');
                                    if (val.split('.').length > 2) {
                                        val = val.replace(/\.+$/, "");
                                    }
                                    self.item.skus[index][field] = val;
                                }
                            });
                        });
                    }
               }*/
			},
			deep: true,
		},
	},
	methods: {
		populateMeta: function () {
			var self = this;
			var data = {
				module: "items/item_groups",
				method: "populate",
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				if (response.status === "ok") {
					self.masters.addonItems = response.addonItems;
					self.masters.categories = response.categories;
					self.masters.units = response.units;
					self.masters.options = response.options;
					self.masters.icons = response.icons;
					self.masters.printLocations = response.printLocations;
				}
			});
		},
		populateSingle: function () {
			var self = this;
			var method = "get";
			var data = {
				module: "items/item_groups",
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
						self.onUnitSelect("load");
					}
				});
			}
		},
		removeImage: function () {
			this.item.image = "";
			this.file.image = null;
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
		handleBlankAttribute: function () {
			var newAttribute = {
				id: "",
				tag: "",
				values: [],
			};
			this.item.options.push(newAttribute);
		},
		handleBlankVariation: function () {
			var newVariation = {
				id: "",
				itemId: "",
				isVeg: "",
				skuId: "",
				sku: "",
				title: "",
				upc: "",
				ean: "",
				purchasePrice: "",
				sellingPrice: "",
				weight: "",
				reorderLevel: "",
			};
			this.item.skus.push(newVariation);
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
		handleRemoveVariation: function (i) {
			this.item.skus.splice(i, 1);
		},
		onUnitSelect: function (ref) {
			var self = this;

			if (this.item.unit !== "") {
				if (ref !== "load") {
					self.item.prices = [];
					self.item.purchaseUnit = null;
					self.item.saleUnit = null;
				}
				self.masters.subUnits = [];
				self.masters.units.forEach(function (unit) {
					if (unit.id !== "") {
						self.masters.subUnits.push(unit);
					}
				});
				if (ref !== "load") {
					self.item.purchaseUnit =
						self.item.purchaseUnit == null ? self.item.unit : "";
					self.item.saleUnit = self.item.saleUnit == null ? self.item.unit : "";
				}
				//self.updatePrices();

				/*var method = 'get';
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
                }*/
			}
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
						unitId: unit,
						conversionRate: self.item.unit === unit ? 1 : "",
						purchaseCurrency: "",
						saleCurrency: "",
						purchasePrice: "",
						sellingPrice: "",
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
		onTagChange: function (option) {
			option.values.push({ id: "", text: option.tag });
			option.tag = "";
			this.updateSkus();
		},
		onTagDelete: function (obj) {
			var self = this;
			self.item.options.forEach(function (option, index) {
				if (typeof option.values[obj.index] != "undefined") {
					if (option.values[obj.index].text === obj.tag.text) {
						self.item.options[index].values.splice(obj.index, 1);
					}
				}
			});
			obj.deleteTag();
			this.updateSkus();
		},
		copyToAll: function (key) {
			var self = this;
			var value = self.item.skus[0][key];
			if (!isNaN(value)) {
				self.item.skus.forEach(function (sku, index) {
					self.item.skus[index][key] = value;
				});
			}
		},
		generateSku: function (obj) {
			var result = "";
			var seperator = "-";
			obj.forEach(function (single) {
				var string = single.toUpperCase();
				result += result === "" ? string : seperator + string;
			});
			return result;
		},
		updateSkus: function () {
			var self = this;
			var temp = [];
			var options = [];
			this.item.options.forEach(function (option) {
				if (option.values.length) {
					if (typeof temp[option.id] == "undefined") {
						temp["option-" + option.id] = [];
					}
					option.values.forEach(function (value) {
						temp["option-" + option.id].push(value.text);
					});
					options.push(temp["option-" + option.id]);
				}
			});
			var combinations = f(options);
			self.item.skus = [];
			combinations.forEach(function (combination) {
				if (combination.length) {
					var title = combination.join(" / ");
					var sku = self.generateSku(combination);
					var obj = {
						skuId: "",
						name: title,
						sku: sku,
						//purchasePrice: '',
						// sellingPrice: '',
						upc: "",
						ean: "",
						reorderLevel: "",
						weight: 0,
						isVeg: "",
						/*inventory: {
                            openingStock: '',
                            openingStockValue: '',
                        */
					};
					self.item.skus.push(obj);
				}
			});
			/*var skus = getCombinations(result);
            console.log(skus);*/
		},
		handleSubmit: function () {
			var form = $("#frm-item-group");
			//var field = this.$refs.sku;
			if (form.parsley().validate("item-group")) {
				//if(!this.duplicateSku(this.item.sku,field)) {
				var method = "";
				var mode = _s("mode");
				if (mode === "add") {
					method = "put";
				} else if (mode === "edit") {
					method = "post";
				}

				var data = {
					module: "items/item_groups",
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
				/*}else{
                    $(field).parsley().addError('sku_duplicate', {message: "SKU already exists."});
                }*/
			} else {
			}
		},
		cancel: function () {
			window.location = _s("back_url");
		},
	},
	created: function () {
		var mode = _s("mode");
		var self = this;
		bus.$on("new_option_added", function (option) {
			self.masters.options.push(option);
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
			unit: 1,
			purchaseUnit: null,
			saleUnit: null,
			manufacturer: "",
			options: [],
			skus: [],
			sku: [],
			taxable: _s("defaultTaxable"),
			printLocation: "default",
			taxInclusive: 0,
			hasSpiceLevel: 0,
			purchasePrice: "",
			sellingPrice: "",
			openingStock: "",
			openingStockValue: "",
			reorderLevel: "",
			categoryId: "",
			prices: [],
			addons: [],
			notes: [],
			isVeg: "",
			image: "",
			icon: "",
			webStatus: 1,
			posStatus: 1,
			appStatus: 1,
		};
		this.populateMeta();
		if (mode === "edit") {
			this.populateSingle();
		}
		if (mode === "add") {
			this.handleBlankAttribute();
		}
	},
	updated: function () {
		Codebase.helpers(["select2"]);
	},
});
