Vue.component('template-form',{
    template: '#template-form-template',
    data: function() {
        return {
            mode: _s('mode'),
            template: {}
           
            
        }
    },
    methods: {
        submit: function() {
            var form = $('#frm-template');
            if(form.parsley().validate()) {

                var method = '';
                if(this.mode==='add') {
                    method = 'put';
                }else if(this.mode==='edit') {
                    method = 'post';
                }

                var data = {
                    module  :   'printers/templates',
                    template   :   this.template
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
        if(this.mode=='add') {
            this.template = {
                title: '',
               
            }
        }else if(this.mode=='edit') {
            this.template = _s('template');
        }
       
       
       
    }
});
