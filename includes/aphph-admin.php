<?php
class Aphph_Admin
{
	/**
		Initiate options when the plugin is activated
	*/
	private $default_param = array(

							'lang-list'	=> array(
												'abap' => 'ABAB',
												'actionscript' => 'ActionScript',
												'ada' => 'Ada',
												'apacheconf' => 'Apache Configuration',
												'apl' => 'APL',
												'applescript' => 'AppleScript',
												'asciidoc' => 'AsciiDoc',
												'aspnet' => 'ASP.NET',
												'autohotkey' => 'AutoHotkey',
												'autoit' => 'AutoIt',
												'bash' => 'Bash',
												'basic' => 'BASIC',
												'batch' => 'Batch',
												'bison' => 'Bison',
												'brainfuck' => 'Brainfuck',
												'bro' => 'Bro',
												'c' => 'C',
												'clike' => 'C-Like',
												'coffeescript' => 'CoffeeScript',
												'core' => 'Core',
												'cpp' => 'C++',
												'crystal' => 'Crystal',
												'csharp' => 'C#',
												'css-extras' => 'CSS Extras',
												'css' => 'CSS',
												'd' => 'D',
												'dart' => 'Dart',
												'diff' => 'Diff',
												'docker' => 'Docker',
												'eiffel' => 'Eiffel',
												'elixir' => 'Elixir',
												'erlang' => 'Erlang',
												'fortran' => 'Fortran',
												'fsharp' => 'F#',
												'gherkin' => 'Gherkin',
												'git' => 'Git',
												'glsl' => 'GLSL',
												'go' => 'Go',
												'graphql' => 'GraphQl',
												'groovy' => 'Groovy',
												'haml' => 'Haml',
												'handlebars' => 'Handlebars',
												'haskell' => 'Haskell',
												'haxe' => 'Haxe',
												'http' => 'HTTP',
												'icon' => 'Icon',
												'inform7' => 'Inform 7',
												'ini' => 'Ini',
												'j' => 'J',
												'jade' => 'Jade',
												'java' => 'Java',
												'javascript' => 'JavaScript',
												'json' => 'JSON',
												'jsx' => 'JSX (React JSX)',
												'julia' => 'Julia',
												'keyman' => 'Keyman',
												'kotlin' => 'Kotlin',
												'latex' => 'LaTeX',
												'less' => 'Less',
												'livescript' => 'LiveScript',
												'lolcode' => 'LOLCODE',
												'lua' => 'Lua',
												'makefile' => 'Makefile',
												'markdown' => 'Markdown',
												'markup' => 'Markup/HTML/XML',
												'matlab' => 'MATLAB',
												'mel' => 'MEL',
												'mizar' => 'Mizar',
												'monkey' => 'Monkey',
												'nasm' => 'NASM',
												'nginx' => 'nginx',
												'nim' => 'Nim',
												'nix' => 'Nix',
												'nsis' => 'NSIS',
												'objectivec' => 'Objective-C',
												'ocaml' => 'OCaml',
												'oz' => 'Oz',
												'parigp' => 'PARI/GP',
												'parser' => 'Parser',
												'pascal' => 'Pascal',
												'perl' => 'Perl',
												'php-extras' => 'PHP Extras',
												'php' => 'PHP',
												'powershell' => 'PowerShell',
												'processing' => 'Processing',
												'prolog' => 'Prolog',
												'properties' => 'Properties',
												'protobuf' => 'Protocol Buffer',
												'puppet' => 'Puppet',
												'pure' => 'Pure',
												'python' => 'Python',
												'q' => 'Q',
												'qore' => 'Qore',
												'r' => 'R',
												'rest' => 'ReST (reStructuredText)',
												'rip' => 'Rip',
												'roboconf' => 'Roboconf',
												'ruby' => 'Ruby',
												'rust' => 'Rust',
												'sas' => 'SAS',
												'sass' => 'Sass',
												'scala' => 'Scala',
												'scheme' => 'Scheme',
												'scss' => 'Scss',
												'smalltalk' => 'Smalltalk',
												'smarty' => 'Smarty',
												'sql' => 'SQL',
												'stylus' => 'Stylus',
												'swift' => 'Swift',
												'tcl' => 'Tcl',
												'textile' => 'Textile',
												'twig' => 'Twig',
												'typescript' => 'TypeScript',
												'verilog' => 'Verilog',
												'vhdl' => 'VHDL',
												'vim' => 'Vim',
												'wiki' => 'Wiki Markup',
												'xojo' => 'Xojo (REAL Basic)',
												'yaml' => 'YAML'
											),
							'themes'	=>	array(
												'default' => 'Default',
												'coy' => 'Coy',
												'dark' => 'Dark',
												'funky' => 'Funky',
												'okaidia' => 'Okaidia',
												'solarizedlight' => 'Solarizedlight',
												'tomorrow' => 'Tomorrow',
												'twilight' => 'Twilight'
											)
						);
	
	private $data_options = array (
									'lang-used' => array (
													'core',
													'clike',
													'php',
													'markup',
													'css',
													'javascript',
													'sass',
													'sql'
												),
									'default-lang' => 'php',
									'max-height' => '480',
									'add-css' => 0,
									'add-css-value' => '',
									'theme' => 'default',
									'gutter' => 1,
									'start-number' => 1,
									'auto-links' => 1,
									'show-lang' => 0,
									'show-hidden-char' => 0,
									'class' => '',
									'token' => '1470914799'
								);
	
	private $options;
	private $options_phdata;
	
    public function __construct()
    {
		$this->options = get_option( APHPH_OPTION, array() );
		$this->options_phdata = get_option( APHPH_OPTION_PHDATA, array() );
		
		register_activation_hook ( APHPH_PLUGIN_PATH . APHPH_DS . APHPH_PLUGIN_FILE_NAME, array($this, 'activate_plugin') );
		
		add_action( 'admin_init', array( $this, 'page_init' ) );
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_filter( 'plugin_action_links', array($this, 'action_link'), 10, 5);
		add_action( 'admin_enqueue_scripts', array($this, 'register_scripts') );
		
		add_action( 'updated_option', array($this, 'build_files'), 10, 3); 

		// add_action( 'admin_notices', array( $this, 'adminNotices' ), 1 );
    }
	
	public function activate_plugin()
	{
		if (!$this->options) {
			update_option(APHPH_OPTION, $this->data_options);
			update_option(APHPH_OPTION_VERSION, APHPH_PLUGIN_VERSION);
			update_option(APHPH_OPTION_PHDATA, $this->default_param);
			add_action('wp_head', array($this, 'build_files'));
		}
	}
	
	public function register_scripts($hook)
	{
		if ($hook == 'settings_page_'.APHPH_PLUGIN_DIR_NAME)
		{
			wp_enqueue_style('aphph-style', APHPH_PLUGIN_URL . '/css/aphph-admin.css?rand='.time(), '', APHPH_PLUGIN_VERSION);
			wp_enqueue_style('aphph-icon', APHPH_PLUGIN_URL . '/css/icomoon/style.css?rand='.time(), '', APHPH_PLUGIN_VERSION);
			wp_enqueue_script('aphph-taboverride', APHPH_PLUGIN_URL . '/js/taboverride/taboverride.min.js', '', APHPH_PLUGIN_VERSION);
			wp_enqueue_script('aphph-prism-components', APHPH_PLUGIN_URL . '/includes/prism/components.js?rand='.time(), '', APHPH_PLUGIN_VERSION);
			wp_enqueue_script('aphph-admin', APHPH_PLUGIN_URL . '/js/aphph-admin.js?rand='.time(), 'aphph-prism-components', APHPH_PLUGIN_VERSION);
		}
	}
	
	/**
	* Add Settings ling to plugin list Settings | Deactivate | Edit
	*/
	public function action_link($links, $file)	
	{
		static $plugin;
		
		if (!isset($plugin))
			$plugin = APHPH_PLUGIN_DIR_NAME . '/' . APHPH_PLUGIN_FILE_NAME;
		
		if ($plugin == $file)
		{
			$setting_link = '<a href="'.get_bloginfo('wpurl').'/wp-admin/admin.php?page='.APHPH_PLUGIN_DIR_NAME.'">Settings</a>';
			array_unshift($links, $setting_link);
		}
		return $links;
	}

	/**
     * Add options page
     */
    public function add_plugin_page()
    {
        $page_title	= 'APH Prism Highlighter Options';
		$menu_title	= 'Prism Highlighter';
		$url = 'aph-prism-highlighter';
        add_options_page(
            $page_title, 
            $menu_title, 
            'manage_options', 
            $url,
            array( $this, 'admin_setting_page' )
        );
    }

    public function admin_setting_page()
    {
		?>
		<div class="wrap">
            <h2 style="display:none">Options</h2>
			<div class="aphph-wrap">
				<h2 class="title">APH Prism Highlighter</h2>
				<div class="aphph-form-container"> 
					<form method="post" action="options.php" id="aphph-form">
					<?php settings_fields('aphph_option_group'); ?>
					<table class="form-table">
						<tr>
							<th>Choose languages</th>
							<td>
								<?php $this->option_language_list(); ?>
							</td>
						</tr>
						<tr>
							<th>Default Language</th>
							<td>
								<?php $this->option_default_language()?>
							</td>
						<tr>
							<th>Theme</th>
							<td>
								<?php $this->option_theme_list(); ?>
							</td>
						</tr>
						<tr>
							<th>Options</th>
							<td>
								<?php $this->option_plugin_options(); ?>
							</td>
						</tr>
						<tr>
							<th>Starting Line Number</th>
							<td>
								<input type="text" class="small-text" name="<?php echo APHPH_OPTION ?>[start-number]" value="<?php echo $this->options['start-number'] ?>"/>
								<p class="description">Used when "Show line numbers" is checked</p>
							</td>
						</tr>
						<tr>
							<th>Max Height</th>
							<td>
								<input type="text" class="small-text" name="<?php echo APHPH_OPTION ?>[max-height]" value="<?php echo $this->options['max-height'] ?>"/>px
								<p class="description">Set maximum height of code container. Recomended 480px. This is usefull in long code, users don't need to scroll page a lot when they want to continue reading the article</p>
							</td>
						</tr>
						<tr>
							<th>Additional CSS</th>
							<td>
								<?php
								$list = array(0 => 'No', 1 => 'Yes');
								echo '<select name="' . APHPH_OPTION . '[add-css]" id="aphph-add-css-option">';
								foreach ($list as $key => $val)
								{
									$selected = $key == $this->options['add-css'] ? ' selected="selected"' : '';
									echo '<option value="'.$key.'"'.$selected.'>'. $val .'</option>';
								}
								echo '</select>';
								
								$show_add_css = !$this->options['add-css'] ? ' style="display:none"' : '';
								?>
								<p class="description">
									Add css code to the compiled css file. This is useful for example if we want to add responsive style to the code.
								</p>
								<div id="aphph-add-css-container"<?=$show_add_css?>>
									<p>
										<textarea class="aphph-textarea" id="aphph-add-css-textarea" name="<?php echo APHPH_OPTION ?>[add-css-value]"/><?=$this->options['add-css-value']?></textarea>
									</p>
									<p class="description">
										The container class is aphph-container. <a href="#" id="aphph-css-example-btn">Click here for example of responsive style</a>
									</p>
									<pre class="aphph-css-example" id="aphph-css-example" style="display:none">pre.aphph-container {
	font-size: 17px;
}

@media screen and (max-width: 640px) {
    pre.aphph-container {
		font-size: 15px;
	}
}

@media (min-width:641px) and (max-width:800px) {
    pre.aphph-container {
		font-size: 16px;
	}
}</pre>
							</div>
							</td>
						</tr>
						<tr>
							<th>Add global class</th>
							<td>
								<input type="text" name="<?php echo APHPH_OPTION ?>[class]" value="<?php echo $this->options['class']?>"/>
								<p class="description">Add class to each code block container</p>
							</td>
						</tr>
					</table>
					<?php if (function_exists('submit_button'))
					{
						submit_button('Save Changes', 'primary', 'aphph-submit', false);
						echo ' ';
						submit_button('Restore to Defaults', 'primary', 'aphph-defaults', false);
					} else {
						echo '
							<input type="submit" name="aphph-submit" id="aphph-submit" class="button button-primary" value="Save Changes"/> 
							<input type="submit" name="aphph-defaults" id="aphph-defaults" class="button button-primary" value="Restore to Defaults"/>
						';
					}?>
					</form>
				</div>
			</div>
		</div>
		
       
        <?php
    }
	
	public function page_init()
    {    
		register_setting(
            'aphph_option_group', // Option group
            APHPH_OPTION,
			array ($this, 'submit_validation')
        );
	}
	
	public function option_language_list() {
	
		$lang_used = $this->options['lang-used'];
		
		echo '<div class="aphph-langused-container aphph-clearfix" id="aphph-langused-container">';
		
		foreach ($lang_used as $lang)
		{
			$lang_name = $this->options_phdata['lang-list'][$lang];
			$file_name = 'prism-'.$lang.'.min.js';
			
			$msg = '';
			if (!file_exists(APHPH_PLUGIN_PATH . APHPH_DS . 'includes' . APHPH_DS . 'prism' . APHPH_DS . 'components' . APHPH_DS . $file_name))
			{
				$msg = '<span class="description">File ' . $file_name . ' not exists</span>';
			}
			$checked = in_array($lang_key, $lang_used) ? ' checked="checked"' : '';
			
			$id 	= $lang == 'core' ? 'aphph-langused-disabled' : 'aphph-langused-'.$lang;
			$class 	= $lang == 'core' ? ' aphph-langused-disabled' : '';
			$link 	= $lang == 'core' ? '<span><i class="aphph-icon-cross"></i></span>' : '<a class="aphph-del-lang" href="#"><i class="aphph-icon-cross"></i></a>';
			echo '
				<div class="aphph-langused-item'.$class.'" id="'.$id.'">
					<input type="hidden" name="'.APHPH_OPTION.'[lang-used][]" value="'.$lang.'"/>
					'.$lang_name. $link .'
				</div>' . $msg;
		}
		echo '</div>
			<a class="button" href="#" id="aphph-show-lang">Add Language</a> <a class="button" href="#" id="aphph-delall-langused">Remove All</a>
		<div class="aphph-langlist-container" id="aphph-langlist-container">';
		
		$lang_list = $this->options_phdata['lang-list'];
		unset($lang_list['core']);
		echo '<label for="aphph-langlist-core">
				<input  class="aphph-langlist-item" type="checkbox" name="core" id="aphph-langlist-core" value="core" checked="checked" disabled="disabled"/>
				Core
			</label><hr/>';
		foreach ($lang_list as $lang_key => $lang_name)
		{
			$file_name = 'prism-'.$lang_key.'.min.js';
			$msg = '';
			if (!file_exists(APHPH_PLUGIN_PATH . APHPH_DS . 'includes' . APHPH_DS . 'prism' . APHPH_DS . 'components' . APHPH_DS . $file_name))
			{
				$msg = '<span class="description">File ' . $file_name . ' not exists</span>';
			}
			$checked = in_array($lang_key, $lang_used) ? ' checked="checked"' : '';
			echo '
				<label for="aphph-langlist-'.$lang_key.'">
					<input  class="aphph-langlist-item" type="checkbox" name="'.$lang_key.'" id="aphph-langlist-'.$lang_key.'" value="'.$lang_key.'"'. $checked .'/>
					'.$lang_name.'
				</label>' . $msg;
		}
		
		echo '</div>';
	}
	
	public function option_default_language() {
		
		$lang_used = $this->options['lang-used'];
		
		echo '<select name="' . APHPH_OPTION . '[default-lang]" id="opt-aphph-default-lang">';
		foreach($lang_used as $lang)
		{
			if ($lang == 'core')
				continue;
			
			$selected = $this->options['default-lang'] == $lang ? ' selected="selected"' : '';
			echo '<option value="'.$lang.'"' . $selected . '>' . $this->options_phdata['lang-list'][$lang] . '</option>';
		}
		echo '</select>
				<p class="description">Default language in the code editor\'s drop down menu</p>';
		
		// Other list of language, hidden, displayed when the language pack changed;
		echo '<span id="aphph-json-user-options" style="display:none">'.json_encode($this->options).'</span>';
	}
	
	public function option_theme_list() {
		
		$theme_list = $this->options_phdata['themes'];

		$options = '<select name="' . APHPH_OPTION . '[theme]">';
		foreach($theme_list as $theme => $theme_name)
		{
			$selected = $this->options['theme'] == $theme ? ' selected="selected"' : '';
			$options .= '<option value="'.$theme.'"' . $selected . '>' . $theme_name . '</option>';
		}
		$options .= '</select>';
		
		echo $options;
	}
	
	public function option_plugin_options() {
		$options = $this->options['options'];
		?>
		<p>
			<label for="aphph-gutter">
				<?php $checked = $this->options['gutter'] == 1 ? ' checked="checked"' : ''; ?>
				<input type="checkbox" name="<?php echo APHPH_OPTION?>[gutter]" id="aphph-gutter" value="1"<?php echo $checked?>/>
				Show line numbers
			</label>
		</p>
		<p>
			<label for="aphph-auto-links">
				<?php $checked = $this->options['auto-links'] == 1 ? ' checked="checked"' : ''; ?>
				<input type="checkbox" name="<?php echo APHPH_OPTION?>[auto-links]" id="aphph-auto-links" value="1"<?php echo $checked?>/>
				Make all url links in the code clickable
			</label>
		</p>
		<p>
			<label for="aphph-show-langtitle">
				<?php $checked = $this->options['show-lang'] == 1 ? ' checked="checked"' : ''; ?>
				<input type="checkbox" name="<?php echo APHPH_OPTION?>[show-lang]" id="aphph-show-langtitle" value="1"<?php echo $checked?>/>
				Show language title to each code block container
			</label>
		</p>
		<p>
			<label for="aphph-show-hiddenchar">
				<?php $checked = $this->options['show-hidden-char'] == 1 ? ' checked="checked"' : ''; ?>
				<input type="checkbox" name="<?php echo APHPH_OPTION?>[show-hidden-char]" id="aphph-show-hiddenchar" value="1"<?php echo $checked?>/>
				Show hidden character. Show tabs and line breaks with symbol <a href="http://prismjs.com/plugins/show-invisibles/index.html" target="_blank">demo</a>
			</label>
		</p>
		<?php
	}
	
	public function submit_validation($inputs)
	{
		$token = time();
		$inputs['token'] = $token;
		
		if (key_exists('aphph-defaults', $_POST))
		{
			$inputs = $this->data_options;
		} else {
			foreach ($this->options as $key => $val)
			{
				if (!key_exists($key, $inputs))
				{
					$inputs[$key] = 0;
				}
			}
		}
		return $inputs;
	}
	
	public function build_files()
	{
		$options = get_option(APHPH_OPTION);
		$token = $options['token'];
		
		// Build prism with selected lang
		$path = APHPH_PLUGIN_PATH . APHPH_DS . 'includes' . APHPH_DS . 'prism' . APHPH_DS;
		// $scripts = file_get_contents($path . 'prism.js') . ';';
		
		$scripts = '';
		foreach ($options['lang-used'] as $lang) {
			$scripts .= file_get_contents($path . 'components' . APHPH_DS . 'prism-'.$lang.'.min.js') . ';';
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
		/* Cleanup the directory */
		$result_path = APHPH_PLUGIN_PATH . APHPH_DS . 'js' . APHPH_DS . 'prism';
		$files = scandir($result_path);
		
		foreach ($files as $file) {
			if ($file == '.' || $file == '..')
				continue;
			unlink ($result_path . APHPH_DS . $file);
		}

		// We build with time() to make sure the client browser use our lastest build
		file_put_contents($result_path . APHPH_DS . 'aphph-prism-' . $token . '.js', $scripts);
		
		/**
			Theme
		*/
		// Cleanup theme directory
		$theme_path = APHPH_PLUGIN_PATH . APHPH_DS . 'css' . APHPH_DS . 'prism';
		$files = scandir($theme_path);
		
		foreach ($files as $file) {
			if ($file == '.' || $file == '..')
				continue;
			unlink ($theme_path . APHPH_DS . $file);
		}
		
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
		if ($options['max-height']){
			$prism_css .= "\r\n" . 
			'pre.aphph-container {
	max-height: '.$options['max-height'] .'px;	
}';
		}
		
		if ($options['add-css']) {
			$prism_css .= "\r\n" . $options['add-css-value'];
		}
		file_put_contents($theme_path . APHPH_DS . 'aphph-prism-' . $token . '.css', $prism_css);
	}
}

?>