(function($) {
	$(document).ready(function()
	{
		var delAllAction = false,
			lang_tobe_checked = [];
		
		$('#aphph-langused-container').delegate('a.aphph-del-lang', 'click', function(e, type)
		{
			e.preventDefault(e);
			var lang = $(this).parent().children('input').val();
			$('#aphph-langlist-' + lang).trigger('click', type);
		});
		
		$('#aphph-langlist-container').delegate('input', 'click', function(e, type)
		{
			var $this = $(this),
				lang = $this.val(),
				lang_name = $.trim($this.parent().text());
			
			// Change state from unchecked to checked
			if ($this.is(':checked'))
			{
				var prefix = lang.substr(0,3);
				// Check dependency
				
				if (prefix == 'add') {
					var lang_component = {};
					lang_component.require = undefined;
				} else {
					var lang_component = prism_components.languages[lang];
				}
				
				var lang_required = [],
					curr_lang = {'lang':lang, 'lang_name': lang_name};
				
				// Save dependency fo build later				
				lang_tobe_checked.push(curr_lang);
				
				if ( lang_component.require != undefined)
				{
					lang_required = lang_component.require;
					if (typeof lang_required == 'string') {
						lang_required = [lang_required];
					}
					for (k in lang_required)
					{
						var $checkbox = $('#aphph-langlist-' + lang_required[k]);
						
						// Click the dependency
						if (!$checkbox.is(':checked')) {
							$checkbox.click();
						} else {
							// Build the lang_tobe_checked
							lang_required = [];
						}
					}
				}
				
				// Show the delete all button
				$('#aphph-delall-langused').fadeIn('fast');
				
				// Build
				if (!lang_required.length)
				{
					// Reverse the order of dependency
					reverse = [];
					for (k = lang_tobe_checked.length - 1; k >= 0 ;k--)
					{
						reverse.push(lang_tobe_checked[k]);
					}
					
					// Build html button
					for (index in reverse) {
						var html = '<div class="aphph-langused-item" id="aphph-langused-' + reverse[index].lang + '">' +
										'<input type="hidden" name="aphph_options[lang-used][]" value="' + reverse[index].lang + '">' + reverse[index].lang_name +
										'<a href="#" class="aphph-del-lang"><i class="aphph-icon-cross"></i></a>' +
									'</div>';
						$(html).appendTo('#aphph-langused-container').hide().fadeIn('fast');
					}
					lang_tobe_checked = [];
				}
			}
			
			// Change state from checked to unchecked
			else 
			{
				$('#aphph-langused-' + lang).fadeOut('fast', function()
				{
					$(this).remove();
					if ($('#aphph-langused-container').find('a').length == 0)
						$('#aphph-delall-langused').fadeOut('fast');
				});
				
				if (delAllAction) {
					return;
				}

				// Remove all language that depend on this lang
				for (prism_lang in prism_components.languages)
				{
					if (prism_components.languages[prism_lang].require == lang) {
						// console.log('#aphph-langlist-' + lang);
						var $checkbox = $('#aphph-langlist-' + prism_lang);
						if ($checkbox.is(':checked'))
							$checkbox.click();
					}
				}
				
			}
		});
		
		// Add Language
		$('#aphph-show-lang').click(function(e)
		{
			e.preventDefault();
			$('#aphph-langlist-container').fadeToggle();
		});
		
		// Delete all lang used
		$('#aphph-delall-langused').click(function(e)
		{
			delAllAction = true;
			e.preventDefault();
			$('#aphph-langused-container').find('a').trigger('click', 'delall');
			$(this).fadeOut('fast', function(){
				delAllAction = false;
			});
		});
		// Restore to defaults settings
		$('#aphph-defaults').click(function()
		{
			var popup_confirm = confirm('Are you sure want to restore to the default settings?');
			if (popup_confirm == false)
				return false;
		});
		
		$('#aphph-add-css-option').change(function()
		{ 
			if ($(this).val() == 1)
			{
				$('#aphph-add-css-container').fadeIn('fast');
			} else {
				$('#aphph-add-css-container').fadeOut('fast');
			}
		});
		
		$('#aphph-css-example-btn').click(function()
		{
			$('#aphph-css-example').fadeToggle('fast');
			return false;
		});
		var textarea = document.getElementById('aphph-add-css-textarea');
		tabOverride.set(textarea);
	});
})(jQuery);
