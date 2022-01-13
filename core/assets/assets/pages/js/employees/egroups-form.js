Vue.component('egroup-form',{
    template: '#egroup-form-template',
    data: function() {
        return {
            module: 'employees/egroups',
            mode: _s('mode'),
            group: {},
            routes: _s('routes'),
            subPermissions: _s('subPermissions')
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
            this.group.permissions = [];
        },
        selectAllSubPermissions: function() {
            var self = this;
            self.deselectAllSubPermissions();
            console.log(self.subPermissions);
            self.subPermissions.forEach(function(single){
                self.group.subPermissions.push(single.code);
            });
        },
        deselectAllSubPermissions: function() {
            this.group.subPermissions = [];
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
                    module  :   this.module,
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
                permissions: [],
                subPermissions: []
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
            if(this.subPermissions.length<this.group.subPermissions.length) {
                console.log(self.group);
                var arr = [];
                self.subPermissions.forEach(function(route){
                    self.group.subPermissions.forEach(function(permission){
                        if(route.code===permission) {
                            arr.push(permission);
                            return false;
                        }
                    });
                });
                console.log(arr);
                this.group.subPermissions = arr;
            }
        }
    }
});
