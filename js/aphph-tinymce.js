(function($) {
	var node_pre_onclick = '',
		show_line_number = true;
		show_language = true,
		data_line = '',
		$iframe_body = '',
		$aphph_btn = '',
		default_lang = 'php';
		new_lang = default_lang,
		
		pre_tag_highlighted = 0,
		aphph_tag_highlighted = 0,
		
		options = $.parseJSON($.trim($('#aphph-json-user-options').text()));
	
    tinymce.create('tinymce.plugins.Aph_Prism_Highlighter', {
        init : function(ed, url) 
		{
			ed.onKeyUp.add(function(){
				checkCursor();
			});
			
            ed.addButton('aphph', {
				// Button title
                title : 'APH Prism Highlighter',
				// Button class
				classes: 'aphph-btn',
				// Button command
                cmd : 'aphph',
				// Button image
                image : url + '/img/aphph-button.png',
				// onClick event
				onClick: function(e)
				{
					$aphph_btn = $(e.target).parent().parent();
				}
            });
			
			// When the button in the toolbar is clicked
			ed.addCommand('aphph', function() 
			{

				/* Check cursor within pre or code, if it not empty 
				   fill the code editor with the text inside the <pre> tag
				   alsu add aother parameters
				*/
				
				var cursor_node = ed.selection.getNode(),
					tag_name = cursor_node.nodeName.toLowerCase(),
					code_value = ed.selection.getContent({format : 'text'}),
					highlight_line = '',
					override_line_number = 0,
					start_number = '',
					language = options['default-lang'];
					class_name = '',
					show_ln = 'false';
				
				if (tag_name == 'pre')
				{
					$pre = $(cursor_node);
					var classes = $pre.attr('class');
					if (classes.match(/lang\s*:/))
					{
						/* fix space around colon => "  : " become ":" */
						classes = classes.replace(/\s*:\s*/, ':');
						list_classes = classes.split(' ');
						for (k in list_classes)
						{
							
							var split = list_classes[k].split(':'),
								value = $.trim(split[1]);
								
							if (list_classes[k].indexOf('lang') != -1)
							{
								language = value;
							}
							else if (list_classes[k].indexOf('mark') != -1)
							{
								highlight_line = value;
							} 
							else if (list_classes[k].indexOf('gutter') != -1)
							{
								override_line_number = 1;
								show_ln = value
							}
							else if (list_classes[k].indexOf('start') != -1)
							{
								start_number = value;
							}
							else if (list_classes[k].indexOf('class') != -1)
							{
								class_name = value.replace(/\s+/gi, ';');
							}
						}
						
						// set default value to aphph textarea popup editor
						code_value       = $pre.text();
						
						/*
							when user click insert code, then we know that we want to change 
							the code within existing <pre> tag
						*/
						node_pre_onclick = $pre;
					}
				}
				
				// lang:php
				$('#aphph-language').val(language);
				// highlight:4
				$('#aphph-highlight-lines').val(highlight_line);
				// insert code
				$('#aphph-editor-code').val(code_value);
				
				// gutter:true
				if (override_line_number)
					$('#aphph-overr-showln').attr('checked', 'checked');
				else
					$('#aphph-overr-showln').removeAttr('checked');
				
				$('#aphph-opt-showln').val(show_ln);
				// start:1
				$('#aphph-start-number').val(start_number);
				
				// class-name
				$('#aphph-input-class-name').val(class_name);
				
				$('#aphph-editor-overlay').show();
				$('#aphph-editor-wrap').show();
				
				// Other options
				if (override_line_number || class_name)
				{
					if ($('#aphph-other-options-container').is(':hidden'))
					{
						$('#aphph-other-options').trigger('click');
						$('#aphph-editor-body').scrollTop(0);
					}
				} else {
					if (!$('#aphph-other-options-container').is(':hidden'))
					{
						$('#aphph-other-options').trigger('click');
					}
				}
				
				/* If submit button is clicked then insert code to tinyMCE editor
				   using <pre> tag with class attribute
				*/
				$('#aphph-submit').unbind('click').click(function(e)
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
					
					var // class-name
						input_class_name = $.trim($('#aphph-input-class-name').val()),
						add_class_name = input_class_name ? ' class:' + input_class_name : '';					
					
						// highlight:true
						highlight_line = $.trim($('#aphph-highlight-lines').val()),
						data_highlight_line = highlight_line ? ' mark:' + highlight_line : '',
						
						// Encode TAG
						$div = $('<div/>'),
						clean_code = $.trim($('#aphph-editor-code').val()),
						encoded_html = $div.text(clean_code).html(),
						$div.remove(),
						
						// Class name
						class_name = new_language + data_highlight_line + class_line_number + add_class_name;
				
					/* 
						If the cursor at the <pre> tag and 
						user click the submit button (insert code)
					*/
					if (node_pre_onclick.length) 
					{
						if (clean_code == '') {
							node_pre_onclick.remove();
							return;
						}
						
						node_pre_onclick.attr('class', class_name).html(encoded_html);
					} 
					else 
					{
						if (clean_code == '') {
							return;
						}
						ed.insertContent('<pre class="' + class_name + '">' + encoded_html + '</pre><br/>');
						// node_pre_onclick = $(ed.editorContainer).find('iframe').contents().find('pre[class*="aphph-pretag-focused"]');
					}
					
					node_pre_onclick = '';
					$aphph_btn.removeClass('mce-active');
					$('#aphph-cancel').trigger('click');
				});
            });
			
			// Aphsh Btn
			function setAphshBtn() {
				if (!$aphph_btn) {
					$aphph_btn = $(ed.editorContainer).find("div[class*='aphph-btn']");
				}
			}
			
			function removeHighlightAphshBtn() {
				if (!aphph_tag_highlighted) {
					return;
				}
				setAphshBtn();
				$aphph_btn.removeClass('mce-active');
				aphph_tag_highlighted = 0;
			}
			
			function highlightAphshBtn()
			{
				setAphshBtn();
				$aphph_btn.addClass('mce-active');
				aphph_tag_highlighted = 1;
				pre_tag_highlighted = 1;
			}
			
			// Handle Pre Container in TinyMCE Editor
			function removeHighlightPre()
			{
				if (!pre_tag_highlighted) {
					return;
				}
				
				if (!$iframe_body) {
					$iframe_body = $(ed.editorContainer).find('iframe').contents().find('body');
				}
				$iframe_body.find('pre').removeClass('aphph-pretag-focused');
				pre_tag_highlighted = 0;
			}
			
			function checkCursor()
			{
				var cursor_node = ed.selection.getNode();
				$cursor_node = $(cursor_node);
				if (cursor_node.nodeName.toLowerCase() == 'pre')
				{
					var classes = $cursor_node.attr('class');
					if (classes.match(/lang\s*:/))
					{
						removeHighlightPre();
						highlightAphshBtn();
						$cursor_node.addClass('aphph-pretag-focused');
					} else {
						removeHighlightPre();
						removeHighlightAphshBtn();
					}
				} else {
						removeHighlightPre();
						removeHighlightAphshBtn();
				}
			}
			
			// When the text editor is clicked, or cursor moved
			ed.on('click', function(e) {
				checkCursor();
			});
        },
 
        /**
         * Returns information about the plugin as a name/value array.
         * The current keys are longname, author, authorurl, infourl and version.
         *
         * @return {Object} Name/value array containing information about the plugin.
         */
        getInfo : function() {
            return {
                longname : 'APH Prism Highlighter',
                author : 'Agus Prawoto Hadi',
                authorurl : 'http://www.webdevcorner.com',
                infourl : 'http://www.webdevcorner.com',
                version : "1.0"
            };
        }
    });
	
    // Register plugin
    tinymce.PluginManager.add( 'aphph_tinymce_btn', tinymce.plugins.Aph_Prism_Highlighter );
	
})(jQuery);