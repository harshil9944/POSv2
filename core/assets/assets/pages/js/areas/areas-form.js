Vue.component('area-form',{
    template: '#area-form-template',
    data: function() {
        return {
            mode: _s('mode'),
            area: {},
            routes: []
        }
    },
    methods: {
        submit: function() {
            var form = $('#frm-area');
            if(form.parsley().validate()) {

                var method = '';
                if(this.mode==='add') {
                    method = 'put';
                }else if(this.mode==='edit') {
                    method = 'post';
                }

                var data = {
                    module  :   'areas',
                    area   :   this.area
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
            this.user = {
                title: '',
                description: '',
                sortOrder:''
            }
        }else if(this.mode=='edit') {
            var area = _s('area');
            this.area = area;
           
        }
    }
});
