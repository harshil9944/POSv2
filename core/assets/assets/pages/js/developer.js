Vue.component("developer", {
    template: "#developer-template",
    data: function() {
        return {
            module: "developer",
        };
    },
    methods: {
        handleSetPrintServer: function() {
            if (confirm("All existing settings will be reset. Are you sure?")) {
                var id = generateUUID();
                var data = {
                    module: this.module,
                    method: "update_primary_print_server",
                    id: id,
                };
                submitRequest(data, "POST").then(function(res) {
                    localStorage.setItem("browserUniqueId", id);
                });
            }
        },
    },
});