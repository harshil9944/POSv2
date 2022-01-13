Vue.component('user-form',{
    template: '#user-form-template',
    data: function() {
        return {
            mode: _s('mode'),
            user: {},
            groups: _s('groups'),
            statuses: _s('statuses'),
            routes: []
        }
    },
    methods: {
        populateRoutes: function(ref) {
            var self = this;
            var group_id = this.user.group_id;
            if(group_id!=='') {
                var data = {
                    module    :   'users/groups',
                    method    :   'routes',
                    group_id  :   group_id
                };
                var request = submitRequest(data,'get');
                request.then(function(response) {
                    if(ref != 'load') {
                        self.user.default_page = '';
                    }
                    self.routes = response.routes;
                });
            }
        },
        submit: function() {
            var form = $('#frm-user');
            if(form.parsley().validate()) {

                var method = '';
                if(this.mode==='add') {
                    method = 'put';
                }else if(this.mode==='edit') {
                    method = 'post';
                }

                var data = {
                    module  :   'users',
                    user    :   this.user
                };
                if(method) {
                    var request = submitRequest(data, method);
                    request.then(function (response) {
                        if (response.status == 'ok') {
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
                group_id: '',
                first_name: '',
                last_name: '',
                email: '',
                password: '',
                default_page: '',
                status: 1
            }
        }else if(this.mode=='edit') {
            var user = _s('user');
            user.password = '';
            this.user = user;
            this.populateRoutes('load');
        }
    }
});
