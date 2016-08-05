require(['core/first'], function() {
    require(['core/log', 'jquery'], function(log, $) {
        log.debug('Bootstrap JavaScript TECAPRO initialised');
        $('.dropdown-toggle').dropdown();
        log.debug('Bootstrap TECAPRO after dropdown');
    });
});
