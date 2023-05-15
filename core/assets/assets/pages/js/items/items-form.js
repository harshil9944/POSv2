Vue.component("vue-multiselect", window.VueMultiselect.default);
Vue.directive("select", {
    twoWay: true,
    bind: function(el, binding, vnode) {
        $(el)
            .select2()
            .on("select2:select", function(e) {
                // v-model looks for
                //  - an event named "change"
                //  - a value with property path "$event.target.value"
                el.dispatchEvent(new Event("change", { target: e.target }));
            });
    },
});
Vue.component("add-category", {
    template: "#add-category-template",
    data: function() {
        return {
            category: {
                title: "",
                parent: "",
            },
        };
    },
    methods: {
        handleAddCategory: function(bvModalEvt) {
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
                request.then(function(response) {
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
    data: function() {
        return {
            unit: {
                title: "",
                parent: "",
            },
        };
    },
    methods: {
        handleAddUnit: function(bvModalEvt) {
            var self = this;
            bvModalEvt.preventDefault();
            var form = $("#frm-add-unit");
            if (form.parsley().validate()) {
                var data = {
                    module: "core/units",
                    obj: this.unit,
                };
                var request = submitRequest(data, "put");
                request.then(function(response) {
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
    data: function() {
        return {
            masters: {
                categories: [],
                units: [],
                subUnits: [{ id: "", value: "Select Unit first" }],
                printLocations: [],
                spiciness: [],
                icons: [],
                statuses: [
                    { id: 1, value: "Yes" },
                    { id: 0, value: "No" },
                ],
            },
            item: {},
            file: {
                image: "",
            },
            deletedVariant: [],
        };
    },
    watch: {},
    computed: {
        variantList() {
            return this.item.variations.filter((s) => {
                return s.removed === false;
            });
        },
        addonsList() {
            return this.item.addons.filter((s) => {
                return s.removed === false;
            });
        },
    },
    methods: {
        handleRemoveVariation: function(variant) {
            if (ds_confirm("Are You sure delete this variant ?")) {
                var self = this;
                var deletedV = self.item.variations.filter(function(v, index) {
                    return v.id === variant.id;
                });
                deletedV[0].removed = true;
                self.deletedVariant.push(deletedV[0]);
            }
        },
        unitTitle: function(id) {
            var result = this.masters.units.find(function(unit) {
                return unit.id === id;
            });
            return result ? result.value : "";
        },
        populateMeta: function() {
            var self = this;
            var data = {
                module: "items",
                method: "populate",
            };
            var request = submitRequest(data, "get");
            request.then(function(response) {
                if (response.status === "ok") {
                    self.masters.categories = response.categories;
                    self.masters.units = response.units;
                    self.masters.icons = response.icons;
                    self.masters.printLocations = response.printLocations;
                    self.masters.spiciness = response.spiciness;
                }
            });
        },
        populateSingle: function() {
            var self = this;
            var method = "get";
            var data = {
                module: "items",
                method: "single",
                id: _s("id"),
            };
            if (method) {
                var request = submitRequest(data, method);
                request.then(function(response) {
                    if (response.status === "ok") {
                        self.item = response.obj;
                        if(_s("mode") === "add") {
                            self.item.id = null;
                        }
                    }
                });
            }
        },
        removeImage: function() {
            this.item.image = "";
            this.file.image = null;
        },
        handleBlankNote: function() {
            this.item.notes.push({
                id: "",
                title: "",
            });
        },
        handleBlankAddon: function() {
            var addon = this.blankVariation()
            addon.type = "optional"
            addon.isAddon = 1
            this.item.addons.push(addon);
        },
        handleRemoveAddon: function(variant) {
            if (ds_confirm("Are You sure delete this addon ?")) {
                var self = this;
                var deletedV = self.item.addons.filter(function(v, index) {
                    return v.id === variant.id;
                });
                deletedV[0].removed = true;
                self.deletedVariant.push(deletedV[0]);
            }
        },
        handleRemoveNote: function(i) {
            this.item.notes.splice(i, 1);
        },

        handleSubmit: function() {
            var form = $("#frm-item");
            if (form.parsley().validate()) {
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
                    deletedVariant: this.deletedVariant,
                };
                if (method) {
                    var request = uploadRequest(data, this.file, method);
                    request.then(function(response) {
                        if (response.status === "ok") {
                            window.location = response.redirect;
                        }
                    });
                } else {
                    alert("Something went wrong!");
                }

            }
        },
        copyToAll: function(key) {
            var self = this;
            var value = self.item.variations[0][key];
            if (!isNaN(value)) {
                self.item.variations.forEach(function(item, index) {
                    self.item.variations[index][key] = value;
                });
            }
        },
        copyToAllAddons: function(key) {
            var self = this;
            var value = self.item.addons[0][key];
            if (!isNaN(value)) {
                self.item.addons.forEach(function(item, index) {
                    self.item.addons[index][key] = value;
                });
            }
        },
        cancel: function() {
            window.location = _s("back_url");
        },
        handleBlankVariation: function() {
            var variation = this.blankVariation()
            variation.type = "variant"
            this.item.variations.push(variation);
        },
        blankVariation: function() {
            return {
                id: 'var-' + Date.now(),
                code: "",
                type: "",
                parent: "",
                title: "",
                outletId: "",
                categoryId: "",
                unitId: this.item.unitId ? this.item.unitId : 1,
                printLocation: this.item.printLocation ? this.item.printLocation : "default",
                taxable: _s("defaultTaxable"),
                hasSpiceLevel: this.item.hasSpiceLevel ? this.item.hasSpiceLevel : 0,
                isVeg: 1,
                image: "",
                icon: "",
                rate: "",
                webStatus: this.item.webStatus ? this.item.webStatus : 1,
                posStatus: this.item.posStatus ? this.item.posStatus : 1,
                appStatus: this.item.appStatus ? this.item.appStatus : 1,
                isAddon: 0,
                isVegan:false,
                isDairyFree:false,
                isGlutenFree:false,
                removed: false,
            }
        }
    },
    created: function() {
        var self = this;
        var mode = _s("mode");
        bus.$on("new_category_added", function(category) {
            self.masters.categories.push(category);
            self.item.categoryId = category.id;
        });
        bus.$on("new_unit_added", function(unit) {
            self.masters.units.push(unit);
            self.item.unit = unit.id;
        });

        this.item = {
            id: "",
            code: "",
            type: "product",
            parent: 0,
            title: "",
            outletId: "",
            categoryId: "",
            unitId: 1,
            printLocation: "default",
            taxable: _s("defaultTaxable"),
            hasSpiceLevel: 0,
            notes: [],
            isVeg: 1,
            image: "",
            icon: "",
            rate: "",
            webStatus: 1,
            posStatus: 1,
            appStatus: 1,
            variations: [],
            isAddon: 0,
            addons: [],
            description:null,
            spiciness:'none',
            isVegan:false,
            isDairyFree:false,
            isGlutenFree:false,
        };
        this.populateMeta();
        if (mode === "edit") {
            this.populateSingle();
        }
        if(mode === 'add' && _s("id")) {
            this.populateSingle();
        }
    },
});
