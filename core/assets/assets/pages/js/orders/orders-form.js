Vue.component('vue-multiselect', window.VueMultiselect.default);
Vue.component('add-customer',{
    template: '#add-customer-template',
    props: ['mode'],
    data: function() {
        return {
            masters: {
                countries: [],
                states: {
                    billing: [],
                    shipping: []
                }
            },
            salutations: [],
            currencies: [],
            priceLists: [],
            paymentTerms: [],
            customer: {},
            initialBillingStateLoad: false,
            initialShippingStateLoad: false
        }
    },
    watch: {
        'customer.billing.country': {
            handler: function (after, before) {
                if(after!=='' && before!==after) {
                    this.populateStates(after,'billing');
                }
            },
            deep: true
        },
        'customer.shipping.country': {
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
                module: 'contacts/customers',
                method: 'populate'
            };
            var request = submitRequest(data,'get');
            request.then(function(response){
                if(response.status==='ok') {
                    self.salutations = response.salutations;
                    self.currencies = response.currencies;
                    self.masters.countries = response.countries;
                    self.customer.customerId = response.newCustomerId;
                    self.customer.currencyId = response.defaultCurrency;
                }
            });
        },
        populateStates: function(id,type) {
            if(!this.initialBillingStateLoad) {
                this.vendor.billing.state = '';
                this.initialBillingStateLoad = false;
            }
            if(!this.initialShippingStateLoad) {
                this.vendor.shipping.state = '';
                this.initialShippingStateLoad = false;
            }
            var self = this;
            var data = {
                country_id: id,
                module: 'core/states',
                method: 'select_data'
            };
            var request = submitRequest(data, 'get');
            request.then(function (response) {
                if (response.status == 'ok') {
                    self.masters.states[type] = response.states;
                }
            });
        },
        onCompanyName: function() {
            if(this.customer.displayName==='') {
                this.customer.displayName = this.customer.companyName;
            }
        },
        handleSubmit: function() {
            var self = this;
            var form = $('#frm-add-customer');
            if(form.parsley().validate()) {

                var method = '';
                if(this.mode=='add') {
                    method = 'put';
                }else if(this.mode=='edit') {
                    method = 'post';
                }

                var data = {
                    module  :   'contacts/customers',
                    obj     :   this.customer
                };
                if(method) {
                    var request = submitRequest(data, method);
                    request.then(function (response) {
                        if (response.status == 'ok') {
                            self.$emit('customer-updated',response.customer);
                        }
                    });
                }else{
                    alert('Something went wrong!');
                }

            }
        },
        copyBillingAddress: function() {
            this.customer.shipping.address1 = this.customer.billing.address1;
            this.customer.shipping.address2 = this.customer.billing.address2;
            this.customer.shipping.attention = this.customer.billing.attention;
            this.customer.shipping.city = this.customer.billing.city;
            this.customer.shipping.country = this.customer.billing.country;
            this.customer.shipping.phone = this.customer.billing.phone;
            this.customer.shipping.state = this.customer.billing.state;
            this.customer.shipping.zipCode = this.customer.billing.zipCode;
        },
        blankCustomerObj: function() {
            this.customer = {
                customerId: '',
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
        },
        handleCancel: function() {
            this.blankCustomerObj();
            this.$emit('addCustomerCancel','');
        }
    },
    created: function() {
        this.blankCustomerObj();
        this.populateMeta();
    }
});
Vue.component('add-salesperson',{
    template: '#add-salesperson-template',
    data: function() {
        return {
            salesperson: {
                id: '',
                name:'',
                email:''
            }
        }
    },
    methods: {
        handleAddSalesPerson: function(bvModalEvt) {
            var self = this;
            bvModalEvt.preventDefault();
            var form = $('#frm-add-salesperson');
            var field = this.$refs.email;
            if(form.parsley().validate()) {
                if(!this.isEmailDuplicate(this.salesperson.email,field)) {
                    var data = {
                        module: 'salespersons',
                        obj: this.salesperson
                    }
                    var request = submitRequest(data, 'put');
                    request.then(function (response) {
                        if (response.status == 'ok') {
                            var obj = {
                                id: response.obj.id,
                                value: response.obj.name
                            }
                            bus.$emit('new_salesperson_added', obj);
                            self.$bvModal.hide('add-salesperson-modal');
                        }
                    });
                }else{
                    $(field).parsley().addError('email_duplicate', {message: "Email already exists."});
                }
            }
        },
        isEmailDuplicate: function(string,field) {
            var result = false;
            /*var field = this.$refs.email;*/
            //console.log($(field));
            $(field).parsley().removeError('email_duplicate');
            var url = _s('action')+'?module=users&method=duplicate_email&email='+string;

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if(this.status===200) {
                    var response = JSON.parse(this.responseText);
                    result = response.result;
                }
            };
            xhttp.open("GET", url, false);
            xhttp.send();
            return result;
        }
    }
});
var self = new Vue({
    el: '#salesorder-form',
    components: {
        vuejsDatepicker:vuejsDatepicker
    },
    data: function() {
        return {
            masters: {
                tax: {},
                units: [],
                customers: [],
                warehouses: [],
                salespersons: []
            },
            module: 'salesorders',
            itemLoading: false,
            items: [],
            customers: [],
            customer: {},
            dpBootstrap: true,
            salesorder: {},
            activeItemIndex: -1
        }
    },
    watch: {
        'salesorder.items': {
            handler: function (values, oldValues) {
                this.itemCalculations();
            },
            deep: true
        },
        'salesorder.freightTotal': {
            handler: function (values, oldValues) {
                this.itemCalculations();
            }
        },
        'salesorder.dutyCost': {
            handler: function (values, oldValues) {
                this.itemCalculations();
            }
        }
    },
    methods: {
        itemCalculations: function() {
            var self = this;
            var subTotal = 0;
            var taxTotal = 0;

            if(self.salesorder.items.length) {
                self.salesorder.items.forEach(function (item, index) {
                    var unitPrice = item.prices.find(function(price){
                        return price.unitId===item.saleUnitId;
                    });
                    self.salesorder.items[index].unitRate = Number(unitPrice.salePrice);
                    self.salesorder.items[index].unitQuantity = Number(self.salesorder.items[index].unitQuantity);

                    var qtyInBaseUnit = Number(self.salesorder.items[index].unitQuantity) / Number(unitPrice.conversionRate);
                    self.salesorder.items[index].quantity = Math.ceil(qtyInBaseUnit);

                    subTotal = Number(subTotal) + (Number(self.salesorder.items[index].quantity) * Number(item.rate));
                });
                taxTotal = (Number(subTotal) * Number(self.masters.tax.rate)) / Number(100);
            }
            self.salesorder.subTotal = subTotal;
            self.salesorder.taxTotal = taxTotal;

            var freight = (!isNaN(self.salesorder.freightTotal))?self.salesorder.freightTotal:0;
            var duty = (!isNaN(self.salesorder.dutyTotal))?self.salesorder.dutyTotal:0;

            self.salesorder.grandTotal = Number(freight) + Number(duty) + Number(subTotal) + Number(taxTotal);
        },
        getOverheads: function() {
            var freight = (!isNaN(this.salesorder.freightTotal))?this.salesorder.freightTotal:0;
            var duty = (!isNaN(this.salesorder.dutyTotal))?this.salesorder.dutyTotal:0;
            return Number(freight) + Number(duty);
        },
        populateMeta: function() {
            var mode = _s('mode');
            var self = this;
            var data = {
                module: self.module,
                method: 'populate'
            };
            var request = submitRequest(data,'get');
            request.then(function(response){
                if(response.status==='ok') {
                    self.masters.customers = response.customers;
                    self.masters.warehouses = response.warehouses;
                    self.masters.salespersons = response.salespersons;
                    self.masters.tax = response.tax;
                    self.masters.units = response.units;
                    if(mode==='add') {
                        self.salesorder.orderNo = response.orderNo;
                        self.salesorder.warehouseId = response.defaultWarehouse;
                        self.salesorder.taxRate = response.tax.rate;
                    }
                }
            });
        },
        populateSingle: function() {
            var method = 'get';
            var data = {
                module  :   this.module,
                method  :   'single',
                id      :   _s('id')
            };
            if(method) {
                var self = this;
                var request = submitRequest(data, method);
                request.then(function (response) {
                    if (response.status === 'ok') {
                        self.salesorder = response.obj;
                        self.customer = self.masters.customers.find(function(customer){
                            return customer.id === self.salesorder.customerId;
                        });
                        //self.masters.taxRate = response.obj.taxRate;
                        //self.item.weight = self.item.weight.toFixed(2);
                    }
                });
            }
        },
        getUnitLabel: function(id) {
            //TODO make it mixins
            var unit = this.masters.units.find(function(unit){
                return unit.id===id;
            });
            return unit.value;
        },
        queryItems: function(query) {
            if(query.length>1) {
                var self = this;
                self.itemLoading = true;

                var data = {
                    module: 'items',
                    method: 'query_so',
                    query: query
                };
                var request = submitRequest(data, 'get');
                request.then(function (response) {
                    if(response.status=='ok') {
                        self.items = response.items;
                        self.itemLoading = false;
                    }
                });
            }
        },
        onItemSelect: function(selected,id){
            var matched = false;

            var basePrice = selected.prices.find(function(price){
                return price.unitId===selected.baseUnitId;
            });
            selected.rate = Number(basePrice.salePrice);

            if(this.salesorder.items.length) {
                this.salesorder.items.forEach(function(item,index){
                    if(item.itemId===selected.itemId && item.skuId===selected.skuId) {
                        matched = true;
                    }
                });
                if(matched==false) {
                    self.salesorder.items.push(selected);
                    self.items = [];
                }
            }else{
                self.salesorder.items.push(selected);
                self.items = [];
            }
        },
        qtyIncrement: function(index) {
            this.salesorder.items[index].quantity++;
        },
        qtyDecrement: function(index) {
            if (this.salesorder.items[index].quantity === 1) {
                alert("Negative quantity not allowed");
            } else {
                this.salesorder.items[index].quantity--;
            }
        },
        dtFormat: function(date) {
            return moment(date).format('DD/MM/YYYY');
        },
        onNewCustomer: function(customer) {
            this.customer = {
                id: customer.id,
                value: customer.displayName
            };
            this.salesorder.customerId = customer.id;
            this.$bvModal.hide('add-customer-modal');
        },
        onNewCustomerCancel: function() {
            this.$bvModal.hide('add-customer-modal');
        },
        onCustomerSelected: function(selected) {
            this.salesorder.customerId = '';
            var customerId = parseInt(selected.id);
            if(customerId!==0) {
                this.salesorder.customerId = customerId;
            }
        },
        handleDraftSubmit: function() {
            this.salesorder.orderStatus = 'Draft';
            this.handleSubmit();
        },
        handleConfirmedSubmit: function() {
            this.salesorder.orderStatus = 'Confirmed';
            this.handleSubmit();
        },
        handleSubmit: function() {
            var form = $('#frm-salesorder');
            if(form.parsley().validate()) {
                var method = '';
                var mode = _s('mode');
                if (mode == 'add') {
                    method = 'put';
                } else if (mode === 'edit') {
                    method = 'post';
                }

                if(this.salesorder.date!='') {
                    this.salesorder.date = moment(this.salesorder.date).format('YYYY/MM/DD HH:mm:ss');
                }else{
                    this.salesorder.date = moment().format('YYYY/MM/DD HH:mm:ss');
                }

                if(this.salesorder.expectedDeliveryDate!='') {
                    this.salesorder.expectedDeliveryDate = moment(this.salesorder.expectedDeliveryDate).format('YYYY/MM/DD HH:mm:ss');
                }else{
                    this.salesorder.expectedDeliveryDate = moment().format('YYYY/MM/DD HH:mm:ss');
                }

                var data = {
                    module: self.module,
                    obj: this.salesorder
                };
                if (method) {
                    var request = submitRequest(data, method);
                    request.then(function (response) {
                        if (response.status == 'ok') {
                            window.location = response.redirect;
                        }
                    });
                } else {
                    ds_alert('Something went wrong!','danger');
                }
            }else{
                console.log('Not Valid');
            }
        },
        cancel: function() {
            window.location = _s('back_url');
        }
    },
    created: function() {
        bus.$on('new_salesperson_added',function(person) {
            self.masters.salespersons.push(person);
            self.salesorder.salesPersonId = person.id;
        });
        var mode = _s('mode');
        this.salesorder = {
            orderStatus: 'Draft',
            customerId: '',
            warehouseId: _s('default_warehouse'),
            salesPersonId: '',
            orderNo: '',
            referenceNo: '',
            date: '',
            expectedDeliveryDate: '',
            items: [],
            subTotal: 0,
            discount: 0,
            discountType: 'r',
            adjustment: 0,
            grandTotal: 0
        }
        this.populateMeta();
        if(mode=='edit') {
            this.populateSingle();
        }

    }
});
