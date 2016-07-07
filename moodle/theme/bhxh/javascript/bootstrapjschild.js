require(['core/first'], function() {
    require(['theme_bootstrap/bootstrap', 'core/log', 'jquery'], function(b, log, $) {
        log.debug('Bootstrap JavaScript BHXH initialised');
        $('.dropdown-toggle').dropdown();
        log.debug('Bootstrap BHXH after dropdown');
    });
});
