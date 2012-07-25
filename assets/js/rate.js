jQuery(document).ready(function($) {
	$('div.coupon div.actions div.rate-it a').click(function() {
		
		le_coupon_ID = $(this).parents('div.coupon').attr('id').replace('coupon_', '');
		le_action = $(this).attr('class');
		le_str = le_coupon_ID + '-' + le_action;
		console.log(le_str);

		$.post('', {
			ID: le_coupon_ID,
			action: le_action
		}, function (response) {

		});

	});
});