Vue.component('general-list',{
    template: '#general-list-template',
    methods: {
        handleInstallPlugin: function(plugin) {

            var data = {
                module  :  'plugins',
                method  :   'install',
                plugin  :   plugin
            };

            var request = submitRequest(data,'post');
            request.then(function(response){
                if(response.status==='ok') {
                    window.location = response.redirect;
                }
            });

        },
        handleUninstallPlugin: function() {

        },
        handleUpgradePlugin: function(plugin) {

            var data = {
                module  :   'plugins',
                method  :   'upgrade',
                plugin  :   plugin
            };

            var request = submitRequest(data,'post');
            request.then(function(response){
                if(response.status==='ok') {
                    window.location = response.redirect;
                }
            });

        }
    },
    mounted: function() {
        loadDataTable({pageLength:30});
    }
});
