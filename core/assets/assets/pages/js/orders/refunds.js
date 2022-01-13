Vue.component('general-list',{
    template: '#general-list-template',
    data: function() {
        return {
            module: 'orders'
        }
    },
    methods: {
    },
    mounted: function() {
        loadDataTable();
    }
});
