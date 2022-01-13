Vue.component('printer-form',{
    template: '#printer-form-template',
    data: function() {
        return {
            mode: _s('mode'),
            printer: {},
            masters: {
                types:[{id:'usb',value:'USB'},{id:'network',value:'Network'} ,{id:'serial',value:'Serial'}],
                statuses: [],
               
            }
            
        }
    },
    methods: {
        submit: function() {
            var form = $('#frm-printer');
            if(form.parsley().validate()) {

                var method = '';
                if(this.mode==='add') {
                    method = 'put';
                }else if(this.mode==='edit') {
                    method = 'post';
                }

                var data = {
                    module  :   'printers',
                    printer   :   this.printer
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
            this.printer = {
                title: '',
                status: 1,
                type: 'usb',
                port:'',
                address:'',
                openCashDrawer:0,
                added:''
            }
        }else if(this.mode=='edit') {
            this.printer = _s('printer');
        }
        this.masters.statuses = _s('statuses');
       
       
    }
});
