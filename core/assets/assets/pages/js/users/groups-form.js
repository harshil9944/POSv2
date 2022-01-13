Vue.component('group-form',{
    template: '#group-form-template',
    data: function() {
        return {
            mode: _s('mode'),
            group: {},
            routes: _s('routes')
        }
    },
    methods: {
        selectAllPermissions: function() {
            var self = this;
            self.deselectAllPermissions();
            self.routes.forEach(function(route){
                self.group.permissions.push(route.slug);
            });
        },
        deselectAllPermissions: function() {
            self = this;
            self.group.permissions = [];
        },
        submit: function() {
            var form = $('#frm-group');
            if(form.parsley().validate()) {

                var method = '';
                if(this.mode==='add') {
                    method = 'put';
                }else if(this.mode==='edit') {
                    method = 'post';
                }

                var data = {
                    module  :   'users/groups',
                    group   :   this.group
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
            this.group = {
                title: '',
                default_page: '',
                permissions: []
            }
        }else if(this.mode==='edit') {
            var self = this;
            this.group = _s('group');
            if(this.routes.length<this.group.permissions.length) {
                var arr = [];
                self.routes.forEach(function(route){
                    self.group.permissions.forEach(function(permission){
                        if(route.slug===permission) {
                            arr.push(permission);
                            return false;
                        }
                    });
                });
                this.group.permissions = arr;
            }
        }
    }
});
