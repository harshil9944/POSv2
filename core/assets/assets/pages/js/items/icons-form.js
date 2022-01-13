Vue.component('icon-form',{
    template: '#icon-form-template',
    data: function() {
        return {
            mode: _s('mode'),
            icon: {},
        }
    },
    methods: {
        submit: function() {
            var form = $('#frm-icon');
            if(form.parsley().validate()) {

                var method = '';
                if(this.mode==='add') {
                    method = 'put';
                }else if(this.mode==='edit') {
                    method = 'post';
                }

                var data = {
                    module  :   'items/icons',
                    icon   :   this.icon
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
    mounted: function() {
        if(this.mode==='add') {
            this.icon = {
                id: '',
                title: '',
            }
        }else if(this.mode==='edit') {
            this.icon = _s('icon');

        }
    }
});
