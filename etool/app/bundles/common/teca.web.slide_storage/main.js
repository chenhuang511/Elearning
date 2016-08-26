define(["./SlideStorageProvider"],
    function(SlideStorageProvider) {
        var service = new SlideStorageProvider();

        return {
            initialize: function(registry) {
                registry.register({
                    interfaces: 'tantaman.web.StorageProvider'
                }, service);
            }
        };
    });
