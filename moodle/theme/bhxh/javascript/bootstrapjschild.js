require(['core/first'], function() {
    require(['core/log', 'jquery'], function(log, $) {
        log.debug('Bootstrap JavaScript BHXH initialised');
        $('.dropdown-toggle').dropdown();
        log.debug('Bootstrap BHXH after dropdown');
    });
});
