Vue.component('login-form', {
    template: '#login-template',
    data: function() {
        return {
            login: {
                email: '',
                password: ''
            },
            errorMessage:'',
            showMessage: false,
            sendingRequest: false
        }
    },
    methods: {
        handleSubmit: function() {
            var self = this;
            self.sendingRequest = true;
            self.showMessage = false;
            var form = $('#frm-login');
            if(form.parsley().validate()) {

                var data = {
                    module: 'auth/login',
                    email: this.login.email,
                    password: this.login.password
                };

                var request = submitRequest(data, 'POST');
                request.then(function (response) {
                    self.sendingRequest = false;
                    if (response.status === 'ok') {
                        window.location = response.redirect;
                    }else if(response.status === 'error') {
                        self.errorMessage = response.message;
                        self.showMessage = true;
                    }
                });
            }else{
                self.sendingRequest = false;
            }
        }
    }
});
new Vue({
    el: '#login-container'
});
