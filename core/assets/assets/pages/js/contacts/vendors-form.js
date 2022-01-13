Vue.component('vendor-form',{
    template: '#vendor-form-template',
    data: function() {
        return {
            masters: {
                countries: [],
                states: {
                    billing: [],
                    shipping: []
                }
            },
            mode: _s('mode'),
            salutations: [],
            currencies: [],
            priceLists: [],
            email: '',
            paymentTerms: [],
            vendor: {},
            vueTagInput: {
                addOnKey: [13]
            },
            initialBillingStateLoad: false,
            initialShippingStateLoad: false
        }
    },
    watch: {
        'vendor.billing.country': {
            handler: function (after, before) {
                if(after!=='' && before!==after) {
                    this.populateStates(after,'billing');
                }
            },
            deep: true
        },
        'vendor.shipping.country': {
            handler: function (after, before) {
                if(after!=='' && before!==after) {
                    this.populateStates(after,'shipping');
                }
            },
            deep: true
        }
    },
    methods: {
        populateMeta: function() {
            var self = this;
            var data = {
                module: 'contacts/vendors',
                method: 'populate'
            };
            var request = submitRequest(data,'get');
            request.then(function(response){
                if(response.status==='ok') {
                    self.salutations = response.salutations;
                    self.currencies = response.currencies;
                    self.masters.countries = response.countries;
                }
            });
        },
        populateStates: function(id,type) {
            if(!this.initialBillingStateLoad) {
                this.vendor.billing.state = '';
                this.initialBillingStateLoad = true;
            }
            if(!this.initialShippingStateLoad) {
                this.vendor.shipping.state = '';
                this.initialShippingStateLoad = true;
            }
            var self = this;
            var data = {
                country_id: id,
                module: 'core/states',
                method: 'select_data'
            };
            var request = submitRequest(data, 'get');
            request.then(function (response) {
                if (response.status === 'ok') {
                    self.masters.states[type] = response.states;
                }
            });
        },
        populateSingle: function() {
            var self = this;
            var method = 'get';
            var data = {
                module  :   'contacts/vendors',
                method  :   'single',
                id      :   _s('id')
            };
            if(method) {
                var request = submitRequest(data, method);
                request.then(function (response) {
                    if (response.status === 'ok') {
                        var obj = response.obj;
                        self.vendor = obj;
                    }
                });
            }
        },
        onCompanyName: function() {
            if(this.vendor.displayName==='') {
                this.vendor.displayName = this.vendor.companyName;
            }
        },
        handleSubmit: function() {
            var form = $('#frm-vendor');
            if(form.parsley().validate()) {

                var method = '';
                if(this.mode==='add') {
                    method = 'put';
                }else if(this.mode==='edit') {
                    method = 'post';
                }

                var data = {
                    module  :   'contacts/vendors',
                    obj     :   this.vendor
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
        copyBillingAddress: function() {
            this.vendor.shipping.address1 = this.vendor.billing.address1;
            this.vendor.shipping.address2 = this.vendor.billing.address2;
            this.vendor.shipping.attention = this.vendor.billing.attention;
            this.vendor.shipping.city = this.vendor.billing.city;
            this.vendor.shipping.country = this.vendor.billing.country;
            this.vendor.shipping.phone = this.vendor.billing.phone;
            this.vendor.shipping.state = this.vendor.billing.state;
            this.vendor.shipping.zipCode = this.vendor.billing.zipCode;
        },
        onTagChange: function(obj) {
            if(validateEmail(obj.tag.text) && !this.isDuplicateTag(this.vendor.additionalEmails,obj.tag)) {
                obj.addTag();
                this.vendor.additionalEmails.push({text:obj.tag.text});
            }
            this.email = '';
        },
        onTagDelete: function(obj) {
            var self = this;
            self.vendor.additionalEmails.forEach(function(email,index) {
                if (email.text === obj.tag.text) {
                    self.vendor.additionalEmails.splice(index, 1);
                }
            });
            obj.deleteTag();
        },
        isDuplicateTag(tags, tag) {
            return tags.map(t => t.text).indexOf(tag.text) !== -1;
        },
        cancel: function() {
            window.location = _s('back_url');
        }
    },
    created: function() {
        this.vendor = {
            vendorId: _s('newVendorId'),
            salutation: '',
            firstName: '',
            lastName: '',
            companyName: '',
            displayName: '',
            email: '',
            additionalEmails: [],
            phone: '',
            designation: '',
            department: '',
            currencyId: _s('defaultCurrency'),
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
            },
            additionalContacts: [{
                id: '',
                contactId:'',
                salutationId:'',
                departmentId:'',
                firstName:'',
                lastName:'',
                email:'',
                phone:''
            }]
        };
        this.populateMeta();
        if(this.mode==='edit') {
            this.initialBillingStateLoad = true;
            this.initialShippingStateLoad = true;
            this.populateSingle();
        }
    }
});
