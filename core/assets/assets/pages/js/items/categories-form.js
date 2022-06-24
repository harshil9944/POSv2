Vue.component('category-form',{
    template: '#category-form-template',
    data: function() {
        return {
            mode: _s('mode'),
            category: {},

            masters: {
                types: [{id:'liquor',value:'Liquor'},{id:'food',value:'Food'}],
                webStatuses: [{id:1,value:'Yes'},{id:0,value:'No'}],
                posStatuses:[{id:1,value:'Yes'},{id:0,value:'No'}],
                appStatuses:[{id:1,value:'Yes'},{id:0,value:'No'}]
            }
        }
    },
    methods: {
        submit: function() {
            var form = $('#frm-category');
            if(form.parsley().validate()) {

                var method = '';
                if(this.mode==='add') {
                    method = 'put';
                }else if(this.mode==='edit') {
                    method = 'post';
                }

                var data = {
                    module  :   'items/categories',
                    category   :   this.category
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
            this.category = {
                title: '',
                type: 'food',
                sortOrder: '',
                webStatus:1,
                posStatus:1,
                appStatus:1,

            }
        }else if(this.mode==='edit') {
            this.category = _s('category');

        }
    }
});
