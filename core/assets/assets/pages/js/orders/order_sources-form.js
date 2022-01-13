Vue.component('order-source-form',{
    template: '#order-source-form-template',
    data: function() {
        return {
            mode: _s('mode'),
            orderSource: {},
           
            
        }
    },
    methods: {
        submit: function() {
            var form = $('#frm-order-source');
            if(form.parsley().validate()) {

                var method = '';
                if(this.mode==='add') {
                    method = 'put';
                }else if(this.mode==='edit') {
                    method = 'post';
                }

                var data = {
                    module  :   'orders/order_sources',
                    source   :   this.orderSource
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
            this.orderSource = {
                title: '',
                printLabel:''
               
            }
        }else if(this.mode=='edit') {
            this.orderSource = _s('order_source');
        }
    }
});
