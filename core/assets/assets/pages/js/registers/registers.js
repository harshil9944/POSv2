Vue.component('general-list', {
    template: '#general-list-template',
    data: function() {
        return {}
    },
    methods: {
        handleSetRegister: function(registerId) {
            if (ds_confirm('Are You Change Your Device?')) {
                var key = generateUUID();
                var obj = {
                    id: registerId,
                    key: key

                }
                var data = {
                    module: 'registers',
                    method: 'set_register_key',
                    obj: obj
                };
                var request = submitRequest(data, 'post');
                request.then(function(response) {
                    if (response.status === 'ok') {
                        console.log(response);
                        localStorage.setItem("registerDeviceId", key);
                        localStorage.setItem("registerId", registerId);
                        localStorage.setItem("registerType", response.result.registerType);
                        window.location = response.result.redirect;
                    } else {
                        var message = '';
                        if (typeof response.message != "undefined") {
                            message = response.message;
                        }
                        ds_alert(message);
                    }
                });
            };


        },
        handleAdd: function() {
            bus.$emit('addItem', '');
        },
        handleEdit: function(id) {
            bus.$emit('editItem', id);
        },
        handleRemove: function(id) {
            alert('This feature will be available soon');
            //bus.$emit('removeItem',id);
        }
    },
    mounted: function() {
        loadDataTable();
        //console.log(Codebase);
        //codebase.blocks('#block-list', 'state_loading');
    }
});