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

var Componette = {};

Componette.selectable = function ($el) {
    if (document.selection) {
        var div = document.body.createTextRange();
        div.moveToElementText($el[0]);
        div.select();
    } else {
        var div = document.createRange();
        div.setStartBefore($el[0]);
        div.setEndAfter($el[0]);
        window.getSelection().addRange(div);
    }
};

$(function () {
    // Choosen
    $(".chosen").chosen({width: '100%'});

    // Tooltip
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-tooltip]').tooltip();

    // Google Events
    $('a[data-ga]').on('click', function (e) {
        var event = $(this).data('ga-event');
        var category = $(this).data('ga-category');
        var action = $(this).data('ga-action');
        ga('send', 'event', event, category, action);
    });

    // Composer clipboard
    $('span.composer-clipboard').on('click', function () {
        var clipboard = new Clipboard('span.composer-clipboard', {
            text: function (trigger) {
                return trigger.getAttribute('data-composer');
            }
        });
    });

    // Composer code
    $('span.composer-code').on('click', function () {
        Componette.selectable($(this));
    });

    // Stats
    (function () {
        var $addon = $('#addon-stats');
        if ($addon.length) {
            var $totaldownloads = $addon.find('#addon-total-downloads');
            var $stats = $totaldownloads.data('stats');
            $stats.forEach(function (entry) {
                entry.x = new Date(Date.parse(entry.x));
            });
            if ($stats) {
                var chart = new CanvasJS.Chart($totaldownloads.get(0), {
                    title: {
                        text: "Total downloads"
                    },
                    animationEnabled: true,
                    zoomEnabled: true,
                    axisX: {
                        valueFormatString: "DD.MM.YY",
                        interval: 3,
                        intervalType: "month",
                        labelAngle: -50,
                        labelFontColor: "rgb(52,132,210)"
                    },
                    axisY: {
                        title: "Downloads",
                        interlacedColor: "rgba(52,132,210,0.1)"
                    },
                    data: [{
                        name: 'total-downloads',
                        type: 'column',
                        color: "rgba(52,132,210,0.7)",
                        markerSize: 8,
                        xValueFormatString: "DD.MM.YYYY",
                        dataPoints: $stats
                    }]
                });
                chart.render();
            }
        }
    })();

    // Nette.ajax
    $.nette.init();
});
