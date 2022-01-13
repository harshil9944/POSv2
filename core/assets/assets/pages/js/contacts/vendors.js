//var codebase = Codebase;
Vue.component('general-list',{
    template: '#general-list-template',
    data: function() {
        return {
        }
    },
    methods: {
        handleAdd: function() {
            bus.$emit('addItem','');
        },
        handleEdit: function(id) {
            bus.$emit('editItem',id);
        },
        handleRemove: function(url) {
            var confirm = ds_confirm('Are you sure to delete this customer?');
            if(confirm) {
                window.location = url;
            }
        }
    },
    mounted: function() {
        loadDataTable();
        //console.log(Codebase);
        //codebase.blocks('#block-list', 'state_loading');
    }
});
