Vue.component('employee-form', {
    template: '#employee-form-template',
    data: function() {
        return {
            module: 'employees',
            mode: _s('mode'),
            user: {},
            statuses: _s('statuses'),
            warehouses:[],
            registers:[]
        }
    },
    methods: {
        populate: function() {
            var self = this;
            var data = {
                module: this.module,
                method: 'populate'
            };
            var request = submitRequest(data, 'get');
            request.then(function(response) {
                self.warehouses = response.warehouses;
                self.registers = response.registers;
            });
        },
        submit: function() {
            var form = $('#frm-employee');
            if (form.parsley().validate()) {

                var method = '';
                if (this.mode === 'add') {
                    method = 'put';
                } else if (this.mode === 'edit') {
                    method = 'post';
                }

                var data = {
                    module: 'employees',
                    user: this.user
                };
                if (method) {
                    var request = submitRequest(data, method);
                    request.then(function(response) {
                        if (response.status === 'ok') {
                            window.location = response.redirect;
                        }
                    });
                } else {
                    alert('Something went wrong!');
                }

            }
        },
        cancel: function() {
            window.location = _s('back_url');
        }
    },
    mounted: function() {
        this.populate();
        if (this.mode === 'add') {
            this.user = {
                id: '',
                first_name: '',
                last_name: '',
                email: '',
                mobile: '',
                code: '',
                status: 1,
                deleted: 0,
                outlet_id: '',
            }
        } else if (this.mode === 'edit') {
            this.user = _s('user');
           
        }
    }
});