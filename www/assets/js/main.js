$(function () {

    $(".chosen").chosen({width: '100%'});

    // Tooltip
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-tooltip]').tooltip();

    // Highlight.JS
    $('pre code').each(function (i, block) {
        hljs.highlightBlock(block);
    });

    // Nette.ajax
    $.nette.init();

    // Google Events
    $('a[data-ga]').on('click', function (e) {
        var event = $(this).data('ga-event');
        var category = $(this).data('ga-category');
        var action = $(this).data('ga-action');
        ga('send', 'event', event, category, action);
    });

    // Search

    (function () {

        var $form = $('form.search-form');
        var $el = $form.find('input.search');
        var interval;

        if ($form.length && $el.length) {
            var url = $el.data('handle');

            $form.on('keydown', function (e) {
                if (e.keyCode == 13) {
                    $(this).submit();
                    return false;
                }
            });

            $form.on('keyup', function (e) {
                var query = $el.val();

                if (query.length > 2){
                    clearTimeout(interval);
                    interval = setTimeout(function () {
                        $.nette.ajax({
                            url: url.replace('_QUERY_', query),
                        });
                    }, 500);
                }
            });
        }
    })();

});
