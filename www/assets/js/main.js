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
                $el.typeahead({
                        hint: false,
                        highlight: true,
                        minLength: 3
                    },
                    {
                        name: 'packages',
                        source: packages,
                        limit: 10,
                        display: 'name',
                        templates: {
                            empty: 'No results',
                            suggestion: Handlebars.compile(
                                '<div>' +
                                '<h5><a href="{{link}}">{{name}}</a></h5>' +
                                '<p>{{description}}</p>' +
                                '<span class="octicon octicon-cloud-download"></span> {{downloads}} &nbsp;' +
                                '<span class="octicon octicon-star"></span> {{stars}}' +
                                '</div>'
                            )
                        }
                    });

                $el.bind('typeahead:select', function (ev, suggestion) {
                    top.location.href = suggestion.link;
                });
                $el.bind('typeahead:asyncreceive', function (ev, query, dataset) {
                    ga('send', 'event', 'search', 'suggestion', $el.val());
                });
            }
        }
    })();
});
