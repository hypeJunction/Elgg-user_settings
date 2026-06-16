import $ from 'jquery';

$(document).on('click', '.elgg-subscriptions-show-members', function (e) {
	e.preventDefault();
	$(this).closest('.elgg-subscriptions-collection').nextUntil('.elgg-subscriptions-collection').slideToggle();
	$(this).closest('.elgg-subscriptions-collection').find('.elgg-icon')
			.toggleClass('elgg-icon-angle-right elgg-icon-angle-down fa-angle-right fa-angle-down');
});

$(document).on('change', '.elgg-subscriptions-toggle', function (e) {
	var $elem = $(this);
	var method = $elem.data('method');
	$elem.parent()
			.toggleClass('elgg-state-inactive elgg-state-active')
			.toggleClass(method + 'toggleOff')
			.toggleClass(method + 'toggleOn');

	if ($elem.is('[data-collection-id]')) {
		var members = $elem.data('members');
		$.each(members, function (i, guid) {
			$('.elgg-subscriptions-toggle[data-method="' + method + '"][data-guid="' + guid + '"]').prop('checked', $elem.is(':checked'));
		});
	} else if ($elem.is('[data-guid]')) {
		var guid = $elem.data('guid');
		$('.elgg-subscriptions-toggle[data-method="' + method + '"][data-guid="' + guid + '"]').prop('checked', $elem.is(':checked'));
	}
});
