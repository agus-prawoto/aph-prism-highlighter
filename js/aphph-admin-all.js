(function($) {
	$(document).ready(function()
	{
		$('div.aphph-always-display-notice').delegate('button.notice-dismiss', 'click', function() {
			var msg = $.trim($(this).prev().text());
			$.ajax({
				type: 'POST',
				url: aphph.ajaxurl,
				dataType: 'text',
				data: {
					action: 'aphph-dismiss-notice',
					nonce: aphph.nonce,
					msg: msg
				},
				success: function(r) {
					// console.log(r);

				},
				error: function(r) {
					// console.log('error');
				}
			});
		});
	});
})(jQuery);
