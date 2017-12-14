$.pjax.defaults.timeout = 5000;
$.pjax.defaults.maxCacheLength = 0;
$(document).pjax('a:not(a[target="_blank"],a[no-pjax])', {
    container: '#pjax-container'
});


$(document).on('pjax:timeout', function(event) { event.preventDefault(); })

$(document).on('submit', 'form[pjax-container]', function(event) {
    $.pjax.submit(event, '#pjax-container')
});