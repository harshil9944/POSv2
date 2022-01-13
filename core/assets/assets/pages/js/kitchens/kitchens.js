Vue.component('general-list',{
    template: '#general-list-template',
    data: function() {
        return {
        }
    },
    methods: {
        handleRemove: function(id) {

            if(ds_confirm('Are you sure to delete this item?')) {
                var data = {
                    module  :   'kitchens',
                    id      :   id
                };
                var request = submitRequest(data,'delete');
                request.then(function(response){
                    if(response.status ==='ok') {
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
        loadDataTable();
    }
});
