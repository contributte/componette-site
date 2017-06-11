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
	el: 'input.search-input',
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

	// Tabs
	$('a[data-toggle="tab"]').on('click', function (e) {
		e.preventDefault();
		if (history.pushState) {
			history.pushState(null, null, $(e.target).attr('href'));
		} else {
			window.location.hash = $(e.target).attr('href');
		}
	});

	// Window history
	$(window).on("popstate", function () {
		var anchor = location.hash || $("a[data-toggle='tab']").first().attr("href");
		$("a[href='" + anchor + "']").tab("show");
	});

	// Window fragment
	if (window.location.hash) {
		console.log(window.location);
		var el = $('a[href="' + window.location.hash + '"]');
		if (el.length > 0) {
			el.tab('show');
		} else {
			$('a[data-toggle="tab"]:first').tab('show');
		}
	} else {
		$('a[data-toggle="tab"]:first').tab('show');
	}

	// Composer clipboard
	var clipboard = new Clipboard('span.composer-clipboard', {
		text: function (trigger) {
			return trigger.getAttribute('data-composer');
		}
	});
	clipboard.on('success', function (e) {
		$(e.trigger).attr('title', 'Copied!').tooltip('fixTitle').tooltip('show');
	});
	$('span.composer-clipboard').on('mouseleave', function (e) {
		$(e.currentTarget).attr('title', 'Click & copy').tooltip('fixTitle');
	});


	// Composer code
	$('span.composer-code').on('click', function () {
		ga('send', 'event', 'click', 'composer-code', $(this).text());
		Componette.selectable($(this));
	});

	// Embedded images in README
	$('.readme').find('img').each(function (i, e) {
		var $img = $(e).closest('a');
		if ($img.attr('href').match(/\.(jpeg|jpg|gif|png)$/) != null) {
			$img.magnificPopup({
				type: 'image',
				callbacks: {
					open: function () {
						ga('send', 'event', 'click', 'embbeded-image', $img.attr('href'));
					}
				}
			});
		}
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
