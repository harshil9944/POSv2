Vue.component('general-list',{
    template: '#general-list-template',
    data: function() {
        return {
            module: 'orders',
            modal: {
                obj: {
                    payments: [],
                    customer:[]
                }
            }
        }
    },
    methods: {
        getTotalPaid: function(amount) {
            var payments = this.modal.obj.payments;
            var totalPaid = 0;
            if(payments.length) {
                totalPaid = payments.reduce(function(totalPaid,payment){
                    return Number(totalPaid) + Number(payment.amount);
                },totalPaid);
            }
            return totalPaid.toFixed(2);
        },
        hasAddons: function(addons) {
            var has = false;
            if (addons.length) {
                addons.forEach(function (addon) {
                    if(addon.enabled===true) {
                        has = true;
                    }
                });
            }
            return has;
        },
        getAddons: function(addons) {
            var string = '';
            if (addons.length) {
                addons.forEach(function (addon) {
                    if(addon.enabled===true) {
                        if (string !== '') {
                            string += ', ' + addon.title;
                        } else {
                            string += addon.title;
                        }
                    }
                });
            }
            return string;
        },
        getNotes: function(notes) {
            if(typeof notes === 'object') {
                var string = '';
                if (notes.length) {
                    notes.forEach(function (note) {
                        if (string !== '') {
                            string += ', ' + note.title;
                        } else {
                            string += note.title;
                        }
                    });
                }
                return string;
            }else{
                return notes;
            }
        },
        handleAdd: function() {
            bus.$emit('addItem','');
        },
        handleViewOrder: function(id) {
            var self = this;
            var data = {
                module: this.module,
                method: 'single_view',
                id:id
            };

            var request = submitRequest(data,'get');
            request.then(function(response) {
                self.modal.obj = response.obj;
                self.$bvModal.show('order-details-modal');
            });
        },
        handleDownloadPdf: function() {
            Object.assign(document.createElement('a'), { target: '_blank', href: this.modal.obj.pdfUrl}).click();
        },
        handleCreateInvoice() {
            if(this.modal.obj.invoiceUrl) {
                window.location = this.modal.obj.invoiceUrl;
            }
        },
        handleEdit: function(id) {
            bus.$emit('editItem',id);
        },
        handleRemove: function(id) {

            if(ds_confirm('Are you sure to delete this item?')) {
                var data = {
                    module  :   'orders',
                    id      :   id
                };
                var request = submitRequest(data,'delete');
                request.then(function(response){
                    if(response.status ==='ok') {
                        window.location = response.redirect;
                    }else{
                        var message = '';
                        if(typeof response.message != "undefined") {
                            message = response.message;
                        }
                        ds_alert(message);
                    }
                });
            }
        }
    },
    mounted: function() {
        //loadDataTable();
    }
});
