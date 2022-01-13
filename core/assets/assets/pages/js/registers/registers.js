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
        handleRemove: function(id) {
            alert('This feature will be available soon');
            //bus.$emit('removeItem',id);
        }
    },
    mounted: function() {
        loadDataTable();
        //console.log(Codebase);
        //codebase.blocks('#block-list', 'state_loading');
    }
});
