Vue.component('vuejs-datepicker', window.vuejsDatepicker);
Vue.component("inquiry-form", {
    template: "#inquiry-form-template",
    data: function () {
        return {
            mode: _s('mode'),
            module: 'bookings/inquiries',
            lists: {
                statuses: [],
            },
            entry: {},
        }
    },
    methods: {
        populateMeta: function () {
            var self = this;
            var data = {
                module: self.module,
                method: 'populate'
            };
            var request = submitRequest(data, 'get');
            request.then(function (response) {
                if (response.status === 'ok') {
                    self.lists.statuses = response.statuses;
                }
            });
        },
        populateSingle: function () {
            var self = this;
            var method = 'get';
            var data = {
                module: self.module,
                method: 'single',
                id: _s('id')
            };
            if (method) {
                var request = submitRequest(data, method);
                request.then(function (response) {
                    if (response.status === 'ok') {
                        self.entry = response.obj;
                    }
                });
            }
        },
        handleSubmit: function () {
            var self = this;
            var form = $('#frm-inquiry');
            if (form.parsley().validate()) {
                var method = null;
                if (self.mode === 'add') {
                    method = 'put';
                } else if (self.mode === 'edit') {
                    method = 'post';
                }
                var entry = JSON.parse(JSON.stringify(self.entry));
                entry.date = moment(entry.date).format('YYYY-MM-DD');

                var data = {
                    module: self.module,
                    obj: entry,
                };
                if (method) {
                    var request = submitRequest(data, method);
                    request.then(function (response) {
                        if (response.status === 'ok') {
                            window.location = response.redirect;
                        }
                    });
                } else {
                    alert('Something went wrong!');
                }

            }
        },
        cancel: function () {
            window.location = _s('back_url');
        },
        dtFormat: function(date) {
            return moment(date).format('DD/MM/YYYY');
        },
    },
    created: function () {
        this.entry = {
            id: null,
            date: null,
            bookingName: null,
            startTime: null,
            endTime: null,
            numberOfPerson: null,
            areaId: null,
            email: null,
            phone: null,
            description: null,
            advance: null,
            menu: null,
            remark: null,
            status: null,
        };
        this.populateMeta();
        if (this.mode === 'edit') {
            this.populateSingle();
        }
    }
});
