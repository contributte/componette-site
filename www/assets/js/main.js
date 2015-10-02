$(function () {

    // Tooltip
    $('[data-toggle="tooltip"]').tooltip();

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
        var $el = $('form input.search');

        if ($el.length) {
            var packages = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.whitespace,
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: $el.data('handle'),
                    wildcard: '_QUERY_'
                }
            });
            $el.typeahead({
                    hint: true,
                    highlight: true,
                    minLength: 3
                },
                {
                    name: 'packages',
                    source: packages,
                    limit: 10,
                    display: 'value'
                });

            $el.bind('typeahead:select', function (ev, suggestion) {
                top.location.href = suggestion.link;
            });
            $el.bind('typeahead:asyncreceive', function (ev, query, dataset) {
                ga('send', 'event', 'search', 'suggestion', $el.val());
            });
        }
    })();
});
