Vue.component('general-list',{
    template: '#general-list-template',
    data: function() {
        return {
            modal: {
                obj: {}
            },
            module: 'items/addon_items'
        }
    },
    methods: {
        handleAdd: function() {
            bus.$emit('addItem','');
        },
        handleEdit: function(id) {
            bus.$emit('editItem',id);
        },
        handleViewItem: function(id) {
            ds_alert('This feature will be available soon');
            /*var self = this;
            var data = {
                module: 'items',
                method: 'single_stock',
                id:id
            };

            var request = submitRequest(data,'get');
            request.then(function(response) {
                self.modal.obj = response.obj;
                self.$bvModal.show('item-details-modal');
            });*/
        },
        handleRemove: function(id) {
            if(ds_confirm('Are you sure to delete this item?')) {
                var data = {
                    module  :   this.module,
                    id      :   id
                };
                var request = submitRequest(data,'delete');
                request.then(function(response){
                    if(response.status==='ok') {
                        window.location = response.redirect;
                    }else{
                        var message = '';
                        if(typeof response.message != "undefined") {
                            message = response.message;
                        }
                        ds_alert(message);
                    }
                });
            }
        }
    },
    mounted: function() {
        //loadDataTable();
    }
});
