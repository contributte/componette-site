$.nette.ext('search', {
    init: function () {
        var $form = $(this.form);
        var $el = $form.find(this.el);
        var interval;

        if ($form.length && $el.length) {
            var url = $el.data('handle');

            $form.on('keydown', function (e) {
                if (e.keyCode == 13) {
                    ga('send', 'event', 'search', 'suggestion', $el.val());
                    $(this).submit();
                    return false;
                }
            });
            $form.on('keyup', function (e) {
                var query = $el.val();

                if (query.length > 2) {
                    clearTimeout(interval);
                    interval = setTimeout(function () {
                        ga('send', 'event', 'search', 'suggestion', $el.val());
                        $.nette.ajax({
                            url: url.replace('_QUERY_', query)
                        });
                    }, 500);
                }
            });
        }

        $form.append(this.spinner);
    },
    before: function () {
        this.spinner.show();
    },
    complete: function () {
        this.spinner.delay(200).hide();
    }
}, {
    form: 'form.search-form',
    el: 'input.search',
    spinner: $('<div>', {
        class: 'spinner',
        css: {
            'display': 'none'
        }
    })
});

$(function () {

    // Choosen
    $(".chosen").chosen({width: '100%'});

    // Tooltip
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-tooltip]').tooltip();

    // Highlight.JS
    $('pre code').each(function (i, block) {
        hljs.highlightBlock(block);
    });

    // Google Events
    $('a[data-ga]').on('click', function (e) {
        var event = $(this).data('ga-event');
        var category = $(this).data('ga-category');
        var action = $(this).data('ga-action');
        ga('send', 'event', event, category, action);
    });

    // Search
    (function () {


    })();

    // Nette.ajax
    $.nette.init();
});
