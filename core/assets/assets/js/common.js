Vue.component("vue-modal", window.bootstrapVue.default);

function _s(key, namespace) {
    if (typeof namespace == "undefined") {
        namespace = "erpData";
    }
    return window[namespace][key];
}

function _can(key) {
    var permissions = _s("permissions");
    var index = permissions.indexOf(key);
    return index === 0;
}

function loadDataTable(params) {
    var pageLength = typeof params !== "undefined" ? params.pageLength : 15;
    var table = $("#data-list-table");
    if (table.length) {
        $(table).DataTable({
            responsive: true,
            pageLength: pageLength,
            autoWidth: !1,
            searching: !1,
            oLanguage: {
                sLengthMenu: "",
            },
            columnDefs: [{ targets: "no-sort", orderable: false }],
            dom: "<'row'<'col-sm-12'tr>><'row'<'col-sm-6'i><'col-sm-6'p>>",
        });
    }
}

function validateEmail(email) {
    var re =
        /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}
async function asyncForEach(array, callback) {
    for (let index = 0; index < array.length; index++) {
        await callback(array[index], index, array);
    }
}

function generateUUID() {
    // Public Domain/MIT
    var d = new Date().getTime(); //Timestamp
    var d2 =
        (typeof performance !== "undefined" &&
            performance.now &&
            performance.now() * 1000) ||
        0; //Time in microseconds since page-load or 0 if unsupported
    return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(
        /[xy]/g,
        function(c) {
            var r = Math.random() * 16; //random number between 0 and 16
            if (d > 0) {
                //Use timestamp until depleted
                r = (d + r) % 16 | 0;
                d = Math.floor(d / 16);
            } else {
                //Use microseconds since page-load if supported
                r = (d2 + r) % 16 | 0;
                d2 = Math.floor(d2 / 16);
            }
            return (c === "x" ? r : (r & 0x3) | 0x8).toString(16);
        },
    );
}

function submitRequest(data, requestMethod, config) {
    if (typeof config === "undefined") {
        config = {
            stringify: false,
        };
    }
    var module = typeof data.module !== "undefined" ? data.module : null;
    var method = typeof data.method !== "undefined" ? data.method : null;
    delete data.module;
    delete data.method;
    data = config.stringify ? { payload: JSON.stringify(data) } : data;
    return Promise.resolve(
        $.ajax({
            url: _s("action"),
            type: requestMethod.toUpperCase(),
            crossDomain: true,
            dataType: "json",
            data: data,
            beforeSend: function(xhr) {
                xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
                xhr.setRequestHeader("key", _s("key"));
                xhr.setRequestHeader("id", _s("userId"));
                if (module) {
                    xhr.setRequestHeader("module", module);
                }
                if (method) {
                    xhr.setRequestHeader("method", method);
                }
                if (config.stringify) {
                    xhr.setRequestHeader("Convert-Payload", "array");
                }
            },
        }),
    );
}

function uploadRequest(data, files, requestMethod) {
    var module = typeof data.module !== "undefined" ? data.module : null;
    var method = typeof data.method !== "undefined" ? data.method : null;
    delete data.module;
    delete data.method;
    var formData = new FormData();
    var dataKeys = Object.keys(data);
    var fileKeys = Object.keys(files);
    dataKeys.forEach(function(key) {
        if (typeof data[key] === "object") {
            formData.append(key, JSON.stringify(data[key]));
        } else {
            formData.append(key, data[key]);
        }
    });
    fileKeys.forEach(function(key) {
        formData.append(key, files[key]);
    });
    return Promise.resolve(
        $.ajax({
            url: _s("action"),
            enctype: "multipart/form-data",
            type: "POST",
            dataType: "json",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function(xhr) {
                xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
                xhr.setRequestHeader("key", _s("key"));
                xhr.setRequestHeader("id", _s("userId"));
                if (module) {
                    xhr.setRequestHeader("module", module);
                }
                if (method) {
                    xhr.setRequestHeader("method", method);
                }
                if (requestMethod.toLowerCase() === "put") {
                    xhr.setRequestHeader("Method-Override", requestMethod);
                }
            },
        }),
    );
}

function ds_alert(message, type) {
    if (typeof type === "undefined") {
        type = "info";
    }
    var icon = "mr-5 fa fa-info"; // + type;

    Codebase.helpers("notify", {
        align: "center", // 'right', 'left', 'center'
        from: "top", // 'top', 'bottom'
        type: type, // 'info', 'success', 'warning', 'danger'
        icon: icon, // Icon class
        message: message,
    });

    /*if(!message) {
        message = 'Something went wrong!'
    }
    alert(message);*/
}

function ds_confirm(message) {
    return confirm(message);
}

function sortByKey(array, key) {
    return array.sort(function(a, b) {
        var x = a[key];
        var y = b[key];

        if (typeof x == "string") {
            x = ("" + x).toLowerCase();
        }
        if (typeof y == "string") {
            y = ("" + y).toLowerCase();
        }

        return x < y ? -1 : x > y ? 1 : 0;
    });
}
if (document.getElementById("user-profile-update-template") !== null) {
    Vue.component("user-profile-update", {
        template: "#user-profile-update-template",
        data: function() {
            return {
                user: _s("user"),
            };
        },
        methods: {
            handleSubmit: function() {
                var self = this;
                var form = $("#frm-user-profile-update");
                if (form.parsley().validate()) {
                    var data = {
                        module: "users",
                        method: "update_password",
                        user: {
                            currentPassword: this.user.currentPassword,
                            newPassword: this.user.newPassword,
                            confirmPassword: this.user.confirmPassword,
                        },
                    };
                    var request = submitRequest(data, "post");
                    request.then(function(response) {
                        var messageType = response.status === "ok" ? "success" : "danger";
                        ds_alert(response.message, messageType);
                        if (response.status === "ok") {
                            self.resetForm();
                        }
                    });
                }
            },
            resetForm: function() {
                this.user.currentPassword = "";
                this.user.newPassword = "";
                this.user.confirmPassword = "";
            },
        },
    });
}
if (document.getElementById("top-notification-template") !== null) {
    Vue.component("top-notification", {
        template: "#top-notification-template",
        data: function() {
            return {
                notifications: [],
            };
        },
        methods: {
            populate: function() {
                var self = this;
                var data = {
                    module: "notifications",
                    method: "populate",
                };
                var request = submitRequest(data, "get");
                request.then(function(response) {
                    self.notifications = response.notifications;
                });
            },
        },
        mounted: function() {
            var self = this;
            setTimeout(function() {
                self.populate();
            }, _s("notificationLoadDelay"));
        },
    });
}
Vue.directive("float", {
    inserted(el) {
        el.oninput = (event) => {
            var value = event.target.value;
            var oldValue = value.substring(0, value.length - 1);
            var letter = "[.]";
            var dotCount = (value.match(RegExp(letter, "g")) || []).length;
            if (dotCount < 2 && value[value.length - 1] === ".") {
                value = value + "0";
            }
            const formattedValue = value;
            el.value = isNaN(formattedValue) ? oldValue : event.target.value;
        };
    },
});
Vue.filter("toTwoDecimal", function(d) {
    if (typeof d != "undefined") {
        return Number(d).toFixed(2);
    }
});
Vue.filter("toThreeDecimal", function(d) {
    if (typeof d != "undefined") {
        return Number(d).toFixed(3);
    }
});
Vue.filter("toFourDecimal", function(d) {
    if (typeof d != "undefined") {
        return Number(d).toFixed(4);
    }
});
Vue.filter("toNoDecimal", function(d) {
    if (typeof d != "undefined") {
        return Number(d).toFixed(0);
    }
});
Vue.filter("beautifyCurrency", function(value) {
    if (!isNaN(value)) {
        return _s("currencySign") + value.toLocaleString("hi-IN");
    } else {
        return value;
    }
});
Vue.filter("beautifyDate", function(date) {
    if (date != null) {
        var format = _s("mDateFormat");
        return moment(date).format(format);
    } else {
        return "";
    }
});
Vue.filter("beautifyDateTime", function(date) {
    if (date != null) {
        var format = _s("mDateTimeFormat");
        return moment(date).format(format);
    } else {
        return "";
    }
});
Vue.filter("beautifyTime", function(date) {
    if (date != null) {
        var format = _s("mTimeFormat");
        var output = moment(date).format(format);
        return output !== "Invalid date" ? output : "NA";
    } else {
        return "NA";
    }
});
Vue.mixin({
    data: function() {
        return {
            search: {
                string: _s("searchString") ? _s("searchString") : "",
                url: _s("searchUrl") ? _s("searchUrl") : null,
                filterDropdown: _s("filterDropdown") ? _s("filterDropdown") : null,
            },
        };
    },
    methods: {
        handleSearchSubmit: function() {
            var validRequest = false;
            var url = "";
            if (this.search.url) {
                url = this.search.url;
            }
            if (this.search.string.trim() !== "") {
                url += "?search=" + this.search.string.trim();
                validRequest = true;
            }
            if (this.search.filterDropdown !== null) {
                if (typeof this.search.filterDropdown.value !== "undefined") {
                    if (validRequest) {
                        url += "&filterDropdown=" + this.search.filterDropdown.value;
                    } else {
                        url += "?filterDropdown=" + this.search.filterDropdown.value;
                    }
                    validRequest = true;
                }
            }
            if (validRequest) {
                window.location = url;
            }
        },
        handleSearchClear: function() {
            var validRequest = false;
            var url = "";
            if (this.search.url) {
                url = this.search.url;
            }
            if (this.search.string.trim() !== "") {
                validRequest = true;
            }
            if (this.search.filterDropdown !== null) {
                if (typeof this.search.filterDropdown.value !== "undefined") {
                    if (
                        this.search.filterDropdown.value !==
                        this.search.filterDropdown.defaultValue
                    ) {
                        validRequest = true;
                    }
                }
            }
            if (validRequest) {
                window.location = url;
            }
        },
    },
});
Vue.mixin({
    methods: {
        handleOpenModal: function(modal) {
            this.$bvModal.show(modal);
        },
        handleCloseModal: function(modal) {
            this.$bvModal.hide(modal);
        },
        vegImg: function() {
            return _s("assetUrl") + "assets/img/veg.svg";
        },
        nVegImg: function() {
            return _s("assetUrl") + "assets/img/nveg.svg";
        },
        getVegNVegImg: function(type) {
            if (type === "1") {
                return this.vegImg();
            } else if (type === "0") {
                return this.nVegImg();
            }
        },
        pluck: function(array, key) {
            return array.map((o) => o[key]);
        },
    },
});
var bus = new Vue({
    data: {
        isLoading: false,
        category: "",
    },
    methods: {
        showLoader: function() {
            this.isLoading = true;
        },
        hideLoader: function() {
            this.isLoading = false;
        },
    },
});
if (typeof obj != "undefined") {
    Vue.component("gen-form", {
        template: "#general-form-template",
        data: function() {
            return {
                defaultObj: JSON.parse(JSON.stringify(obj)),
                obj: obj,
                module: _s("currentModule"),
                active: false,
                mode: "",
                updateBtnText: "",
            };
        },
        methods: {
            handleChange: function(event_name, value) {
                bus.$emit(event_name, value);
            },
            handleAdd: function() {
                this.mode = "add";
                this.updateBtnText = "Add";
                this.active = true;
            },
            handleEdit: function(id) {
                var self = this;
                this.mode = "edit";
                this.updateBtnText = "Save Changes";
                this.active = true;

                var data = {
                    id: id,
                    module: this.module.path,
                };
                var request = submitRequest(data, "get");
                request.then(function(response) {
                    if (response.status == "ok") {
                        self.obj = response.obj;
                    }
                });
            },
            handleRemove: function(id) {
                if (ds_confirm("Are you sure to delete this item?")) {
                    return;
                    var data = {
                        module: this.module.path,
                        id: id,
                    };
                    var request = submitRequest(data, "delete");
                    request.then(function(response) {
                        if (response.status == "ok") {
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
            handleSubmit: function() {
                if ($("#frm-general-edit").parsley().validate()) {
                    var data = {
                        module: this.module.path,
                        obj: this.obj,
                    };
                    var method = "";
                    if (this.mode == "add") {
                        method = "put";
                    }
                    if (this.mode == "edit") {
                        method = "post";
                    }
                    if (method != "") {
                        var request = submitRequest(data, method);
                        request.then(function(response) {
                            if (response.status == "ok") {
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
                }
            },
            handleCancel: function() {
                this.resetForm();
            },
            resetForm: function() {
                this.obj = JSON.parse(JSON.stringify(this.defaultObj));
                this.mode = "";
                this.updateBtnText = "";
                this.active = false;
            },
        },
        created: function() {
            var self = this;
            bus.$on("addItem", function() {
                self.handleAdd();
            });
            bus.$on("editItem", function(value) {
                self.handleEdit(value);
            });
            bus.$on("removeItem", function(value) {
                self.handleRemove(value);
            });
        },
    });
}
var customerCustomFieldsMixin = {
    data: function() {
        return {
            customerCustomFields: [],
            vaccination: [
                { id: 1, value: "Yes" },
                { id: 0, value: "No" },
            ],
        };
    },
    computed: {
        isFullVaccinated: function() {
            return this.customerCustomFields.indexOf("fullVaccinated") > -1;
        },
    },
    methods: {
        isCustomFields: function(field) {
            return this.customerCustomFields.indexOf(field) > -1;
        },
    },
};