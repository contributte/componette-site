$(function () {

    // Tooltip
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-tooltip="top"]').tooltip();

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
    /*
    (function () {

        var $form = $('form.search-form');
        if ($form.length) {
            $form.on('keydown', function (e) {
                if (e.keyCode == 13) {
                    $(this).submit();
                    return false;
                }
            });

            var $el = $form.find('input.search');
            if ($el.length) {
                var packages = new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.whitespace,
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    remote: {
                        url: $el.data('handle'),
                        wildcard: '_QUERY_'
                    }
                });
            }
        }
    })();
    */
});
