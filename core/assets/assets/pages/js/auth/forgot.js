Vue.component('forgot-form', {
    template: '#forgot-template',
    data: function() {
        return {
            email: '',
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
            var form = $('#frm-forgot');
            if(form.parsley().validate()) {

                var data = {
                    module: 'auth/forgot',
                    email: this.email
                };

                var request = submitRequest(data, 'POST');
                request.then(function (response) {
                    self.sendingRequest = false;
                    if (response.status == 'ok') {
                        self.errorMessage = response.message;
                        self.showMessage = true;
                    }else if(response.status=='error') {
                        self.errorMessage = response.message;
                        self.showMessage = true;
                    }
                });
            }
        }
    }
});

new Vue({
    el: '#forgot-container'
});
