Vue.component('general-list',{
    template: '#general-list-template',
    data: function() {
        return {
        }
    },
    methods: {
        handleAdd: function() {
            bus.$emit('onAddPermission','');
        }
    },
    mounted: function() {
        loadDataTable();
    }
});
Vue.component('permission-form',{
    template: '#permission-form-template',
    data: function() {
        return {
            modal: {
                id: 'permission-form-modal'
            },
            module: 'permissions',
            permission: {}
        }
    },
    methods: {
        initComponent: function() {
            this.blankObj();
            this.handleOpenModal(this.modal.id);
        },
        blankObj: function() {
            this.permission = {
                code: ''
            };
        },
        handleSubmit: function() {
            //var self = this;
            var form = $('#frm-permission');
            if(form.parsley().validate()) {
                var data = {
                    module: this.module,
                    obj: this.permission
                };
                var request = submitRequest(data, 'put');
                request.then(function (response) {
                    if (response.status === 'ok') {
                        window.location.reload();
                    }
                });
            }
        }
    },
    created: function() {
        var self = this;
        bus.$on('onAddPermission',function(payload){
            self.initComponent();
        });
    }
});
