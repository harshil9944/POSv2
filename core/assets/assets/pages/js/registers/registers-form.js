Vue.component('register-form', {
    template: '#registers-form-template',
    data: function() {
        return {
            masters: {
                warehouses: [],
                types: [
                    { id: 'Register', value: "Register" },
                    { id: 'Tablet', value: "Tablet" },
                ],
            },
            mode: _s('mode'),
            register: {}
        }
    },
    methods: {

        populateMeta: function() {
            var self = this;
            var data = {
                module: 'registers',
                method: 'populate'
            };
            var request = submitRequest(data, 'get');
            request.then(function(response) {
                if (response.status === 'ok') {
                    self.register.code = response.code;
                    self.masters.warehouses = response.warehouses;
                }
            });
        },
        populateSingle: function() {
            var self = this;
            var method = 'get';
            var data = {
                module: 'registers',
                method: 'single',
                id: _s('id')
            };
            if (method) {
                var request = submitRequest(data, method);
                request.then(function(response) {
                    if (response.status === 'ok') {
                        self.register = response.obj;
                    }
                });
            }
        },
        handleSubmit: function() {
            var form = $('#frm-register');
            if (form.parsley().validate()) {

                var method = '';
                if (this.mode === 'add') {
                    method = 'put';
                } else if (this.mode === 'edit') {
                    method = 'post';
                }

                var data = {
                    module: 'registers',
                    obj: this.register
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
    created: function() {
        this.register = {
            id: '',
            code: '',
            outletId: '',
            key: '',
            type: 'Tablet',
            title: '',
            status: 1,
            primary: false,
        };
        this.populateMeta();
        if (this.mode === 'edit') {
            this.populateSingle();
        }
    }
});