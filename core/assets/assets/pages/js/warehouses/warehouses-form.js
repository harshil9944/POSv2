Vue.component('warehouse-form',{
    template: '#warehouse-form-template',
    data: function() {
        return {
            masters: {
                countries: [],
                states: [],
                vendors: [],
                customers: []
            },
            mode: _s('mode'),
            warehouse: {},
            initialLoad: false
        }
    },
    watch: {
        'warehouse.countryId': {
            handler: function (after, before) {
                if(after!=='') {
                    this.populateStates(after);
                }
            },
            deep: true
        }
    },
    methods: {
        populateMeta: function() {
            var self = this;
            var data = {
                module: 'warehouses',
                method: 'populate'
            };
            var request = submitRequest(data,'get');
            request.then(function(response){
                if(response.status==='ok') {
                    self.warehouse.code = response.code;
                    self.masters.countries = response.countries;
                    self.masters.vendors = response.vendors;
                    self.masters.customers = response.customers;
                }
            });
        },
        populateStates: function(id) {
            if(!this.initialLoad) {
                this.warehouse.stateId = '';
            }
            this.initialLoad = false;
            var self = this;
            var data = {
                country_id: id,
                module: 'core/states',
                method: 'select_data'
            };
            var request = submitRequest(data, 'get');
            request.then(function (response) {
                if (response.status === 'ok') {
                    self.masters.states = response.states;
                }
            });
        },
        populateSingle: function() {
            var self = this;
            var method = 'get';
            var data = {
                module  :   'warehouses',
                method  :   'single',
                id      :   _s('id')
            };
            if(method) {
                var request = submitRequest(data, method);
                request.then(function (response) {
                    if (response.status === 'ok') {
                        var obj = response.obj;
                        self.warehouse = obj;
                    }
                });
            }
        },
        handleSubmit: function() {
            var form = $('#frm-warehouse');
            if(form.parsley().validate()) {

                var method = '';
                if(this.mode==='add') {
                    method = 'put';
                }else if(this.mode==='edit') {
                    method = 'post';
                }

                var data = {
                    module  :   'warehouses',
                    obj     :   this.warehouse
                };
                if(method) {
                    var request = submitRequest(data, method);
                    request.then(function (response) {
                        if (response.status === 'ok') {
                            window.location = response.redirect;
                        }
                    });
                }else{
                    alert('Something went wrong!');
                }

            }
        },
        cancel: function() {
            window.location = _s('back_url');
        }
    },
    created: function() {
        this.warehouse = {
            id: '',
            vendorId: '',
            customerId: '',
            code: '',
            type: '',
            name: '',
            address1: '',
            address2: '',
            city: '',
            stateId: '',
            zipCode: '',
            countryId: '',
            phone: '',
            email: '',
            status: 1
        };
        this.populateMeta();
        if(this.mode==='edit') {
            this.initialLoad = true;
            this.populateSingle();
        }
    }
});
