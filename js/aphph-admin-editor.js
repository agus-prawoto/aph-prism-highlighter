(function($) {
	$(document).ready(function(){
		var textarea = document.getElementById('aphph-editor-code');
		tabOverride.set(textarea);
		$('#aphph-editor-title button, #aphph-cancel').click(function()
		{
			if (!$('#aphph-other-options-container').is(':hidden'))
			{
				$('#aphph-other-options').trigger('click');
			}
			$('#aphph-editor-overlay, #aphph-editor-wrap').hide();
		})
		$('#aphph-other-options').click(function(){
			// $('#aphph-other-options-container').slideToggle('fast');
			var $i_elm = $(this).children('i'),
				class_name = $i_elm.attr('class');
			if (class_name == 'aphph-icon-circle-down')
			{
				$('#aphph-other-options-container').slideDown('fast');
				$i_elm.attr('class', 'aphph-icon-circle-up');
			} else {
				$('#aphph-other-options-container').slideUp('fast');
				$i_elm.attr('class', 'aphph-icon-circle-down');
			}
		})
		QTags.addButton( 'aphph_quicktag', 'APH Prism', function(btn, textarea, ed)
		{
			var $textarea = $(ed.canvas),
				text = $textarea.val(),
				selStart = ed.canvas.selectionStart,
				selEnd = ed.canvas.selectionEnd,
				selection = '';
			
			if (selEnd - selStart)
				selection = text.substr(selStart, selEnd - selStart);
			
			$('#aphph-editor-overlay').show();
			$('#aphph-editor-wrap').show();
			$('#aphph-editor-code').val(selection);
			
			// Set defaut value
			$('#aphsh-overr-showln').removeAttr('checked');
			$('#aphsh-opt-showln').val('false');
			$('#aphsh-start-number').val('');
			$('#aphph-highlight-lines').val('');
			$('#aphsh-input-class-name').val('');
			
			// Click submit button
			$('#aphph-submit').click(function()
			{
					// Lang
				var new_language = 'lang:' + $('#aphph-language').val(),
				
					// gutter:true
					override_line_number = $('#aphph-overr-showln').is(':checked'),
					class_line_number = '';
					
				if (override_line_number) {
					
					var start_number = $.trim($('#aphph-start-number').val()) ||  1,
						show_line_number = $.trim($('#aphph-opt-showln').val()),
						data_start_number = show_line_number == 'true' ? ' start:' + start_number : '',
						class_line_number = ' gutter:' + show_line_number + data_start_number;
				}	
				
					// class-name
				var	input_class_name = $.trim($('#aphph-input-class-name').val()),
					add_class_name = input_class_name ? ' class:' + input_class_name : '';					
				
					// html-script:true
					html_script = $('#aphph-html-script').val() == 'true' ? ' html-script:true' : '',
					
					// highlight:true
					highlight_line = $.trim($('#aphph-highlight-lines').val()),
					data_highlight_line = highlight_line ? ' mark:' + highlight_line : '',
					
					// Encode TAG
					$div = $('<div/>'),
					clean_code = $.trim($('#aphph-editor-code').val()),
					encoded_html = $div.text(clean_code).html(),
					$div.remove(),
					
					// Title
					title = $('#aphph-title').val(),
					
					attr_title = title ? ' title="' + title + '"' : '';
					
					// Class name
					class_name = new_language + data_highlight_line + html_script + class_line_number + add_class_name;
					
				var textBefore = text.substr(0, selStart),
					textAfter = text.substr(selStart + selection.length, text.length - selStart),
					content = '<pre class="' + class_name + '"' + attr_title + '>' + encoded_html + '</pre>';
				
				
				$textarea.val(textBefore + content + textAfter);
			
				// QTags.insertContent();
				$('#aphph-editor-overlay').hide();
				$('#aphph-editor-wrap').hide();
				if (!$('#aphph-other-options-container').is(':hidden'))
				{
					$('#aphph-other-options').trigger('click');
				}
				
			});
		});
	});
})(jQuery);
