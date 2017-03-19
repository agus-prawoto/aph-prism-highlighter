<?php

class Aphph_Build {
	public function build_files() 
	{
		// BUILD PATH
		$upload_dir = wp_upload_dir();
		$build_path = $upload_dir['basedir'] . APHPH_DS . 'aphph';
		
		if (!file_exists($build_path)) {
			mkdir($build_path, 0777);
		}
		
		$options = get_option(APHPH_OPTION);
		$token = $options['token'];
		
		// Build prism with selected lang
		$path = APHPH_PLUGIN_PATH . APHPH_DS . 'includes' . APHPH_DS . 'prism' . APHPH_DS;
		// $scripts = file_get_contents($path . 'prism.js') . ';';
		
		$scripts = '';
		$addcss = '';
		foreach ($options['lang-used'] as $lang) 
		{
			$jsfile = $path . 'components' . APHPH_DS . 'prism-'.$lang.'.min.js';
			if (file_exists($jsfile)) {
				$scripts .= file_get_contents($jsfile) . ';';
			}
			
			if ($lang == 'adddarkplain' || $lang == 'addlightplain')
			{
				$addcss .= "\r\n" . 
			"pre.aphph-adddarkplain,
pre.aphph-addlightplain {
    padding: 10px 20px;
    display: block;
    font-family: Consolas, Monaco, 'Andale Mono', 'Ubuntu Mono', monospace;
    white-space: pre-wrap;
    white-space: -moz-pre-wrap;
    white-space: -o-pre-wrap;
    white-space: -pre-wrap;
    word-wrap: break-word;
    line-height: 25px;
    font-size: 95%;
    text-align: left
}
pre.aphph-addlightplain {
	background: #ececec;
    color: #52565d;
}
pre.aphph-adddarkplain {
	background: #131313;
	color: #CACACA;
}";
			}
		}
		
		/**
		 * Add Plugins...
		*/
	
		$plugin_path = $path . 'plugins' . APHPH_DS;
		$plugin_used = array();
		
		// line-numbers						
		if (@$options['gutter']) {
			$scripts .= file_get_contents($plugin_path . 'line-numbers' . APHPH_DS .'prism-line-numbers.min.js') . ';';
			$plugin_used[] = 'line-numbers';
		}
		
		// show-invisibles	
		if (@$options['show-hidden-char']) {
			$scripts .= file_get_contents($plugin_path . 'show-invisibles' . APHPH_DS .'prism-show-invisibles.min.js') . ';';
			$plugin_used[] = 'show-invisibles';
		}
		
		//show-language
		if (@$options['show-lang']) {
			$scripts .= file_get_contents($plugin_path . 'show-language' . APHPH_DS .'prism-show-language.min.js') . ';';
			$plugin_used[] = 'show-language';
		}
		
		// autolinker
		if (@$options['auto-links']) {
			$scripts .= file_get_contents($plugin_path . 'autolinker' . APHPH_DS .'prism-autolinker.min.js') . ';';
			$plugin_used[] = 'autolinker';
		}
		
		/* Default plugin */
		// file-highlight	
		$scripts .= file_get_contents($plugin_path . 'file-highlight' . APHPH_DS .'prism-file-highlight.min.js') . ';';
		$plugin_used[] = 'file-highlight';
		
		// line-highlight	
		$scripts .= file_get_contents($plugin_path . 'line-highlight' . APHPH_DS .'prism-line-highlight.min.js') . ';';
		$plugin_used[] = 'line-highlight';
		
		/**
			Script
		*/	
		/* Cleanup the build directory */
		$files = scandir($build_path);
		foreach ($files as $file) {
			if ($file == '.' || $file == '..')
				continue;
			unlink ($build_path . APHPH_DS . $file);
		}

		// We build with time() to make sure the client browser use our lastest build
		file_put_contents($build_path . APHPH_DS . 'aphph-prism-' . $token . '.js', $scripts);
		
		/**
			Theme
		*/
		
		// Get theme css
		$theme_name = $options['theme'] == 'default' ? '' : '-'.$options['theme'];
		$prism_css = file_get_contents($path . 'themes' . APHPH_DS . 'prism' . $theme_name. '.css');
		
		// Get plugins css
		foreach ($plugin_used as $plugin)
		{
			$css_file = $path . 'plugins' . APHPH_DS . $plugin . APHPH_DS . 'prism-' . $plugin . '.css';
			if (file_exists($css_file))
			{
				$prism_css .= "\r\n" . file_get_contents($css_file);
			}
		}
		
		// ADDITIONAL CSS
		$prism_css .= $addcss;
		
		if ($options['max-height']){
			$prism_css .= "\r\n" . 
			'pre.aphph-container {
	max-height: '.$options['max-height'] .'px;	
}';
		}
		
		if ($options['add-css']) {
			$prism_css .= "\r\n" . $options['add-css-value'];
		}
		
		$file_path = $build_path . APHPH_DS . 'aphph-prism-' . $token . '.css';
		// $old_path = $theme_path . APHPH_DS . 'aphph-prism-' . $token . '.css';
		file_put_contents($file_path, $prism_css);
	
	}
}