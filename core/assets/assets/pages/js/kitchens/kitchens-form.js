Vue.component('kitchen-form',{
    template: '#kitchen-form-template',
    data: function() {
        return {
            mode: _s('mode'),
            kitchen: {},
            masters:{
                printers:[],
                templates:[]
            }
            
        }
    },
    methods: {
        submit: function() {
            var form = $('#frm-kitchen');
            if(form.parsley().validate()) {

                var method = '';
                if(this.mode==='add') {
                    method = 'put';
                }else if(this.mode==='edit') {
                    method = 'post';
                }

                var data = {
                    module  :   'kitchens',
                    kitchen   :   this.kitchen
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
            this.kitchen = {
                title: '',
                printerId: '',
                templateId: '',
                added:''
            }
        }else if(this.mode=='edit') {
            this.kitchen = _s('kitchen');
        }
        this.masters.printers = _s('printers');
        this.masters.templates = _s('templates');
       
       
       
    }
});
