var vueObj = new Vue({
    el: '#contact-form',
    data: function() {
        return {
            mode: _s('mode'),
            salutations: [],
            currencies: [
                {id:'',value:'Select Currency'},
                {id:1,value:'INR - Indian Rupee'},
                {id:2,value:'GBP - British Pound'},
                {id:3,value:'USD - US Dollar'}
            ],
            priceLists: [],
            paymentTerms: [],
            contact: {}
        }
    },
    methods: {
        populateMeta: function() {
            var self = this;
            var data = {
                module: 'contacts',
                method: 'populate'
            };
            var request = submitRequest(data,'get');
            request.then(function(response){
                if(response.status=='ok') {
                    self.salutations = response.salutations;
                    self.currencies = response.currencies;
                }
            });
        },
        populateSingle: function() {
            var self = this;
            var method = 'get';
            var data = {
                module  :   'contacts',
                method  :   'single',
                id      :   _s('id')
            };
            if(method) {
                var request = submitRequest(data, method);
                request.then(function (response) {
                    if (response.status == 'ok') {
                        var obj = response.obj;
                        self.contact = obj;
                    }
                });
            }
        },
        handleSubmit: function() {
            var form = $('#frm-contact');
            if(form.parsley().validate()) {

                var method = '';
                if(vueObj.mode=='add') {
                    method = 'put';
                }else if(vueObj.mode=='edit') {
                    method = 'post';
                }

                var data = {
                    module  :   'contacts',
                    obj     :   vueObj.contact
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
    created: function() {
        this.contact = {
            contactType: '',
            salutation: '',
            firstName: '',
            lastName: '',
            companyName: '',
            displayName: '',
            email: '',
            phone: '',
            designation: '',
            department: '',
            currency: '',
            priceListId: '',
            paymentTerm: '',
            notes: '',
            billing: {
                id: '',
                attention: '',
                address1: '',
                address2: '',
                city: '',
                state: '',
                zipCode: '',
                country: '',
                phone: ''
            },
            shipping: {
                id: '',
                attention: '',
                address1: '',
                address2: '',
                city: '',
                state: '',
                zipCode: '',
                country: '',
                phone: ''
            }
        };
        this.populateMeta();
        if(this.mode=='edit') {
            this.populateSingle();
        }
    }
});
