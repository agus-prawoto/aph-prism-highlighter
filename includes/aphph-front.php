<?php

class Aphph_Front
{
	private $options;
	private $regex;
	private $match_post;
	private $match_comment = false;

	public function __construct()
	{
		/**
		 * Regex to match all <pre> tag that have class value of lang:
		 * Pattern: <pre - space \s - any character [^>]* - class - space character(s)
						 - = - space character(s) - " or ' - any character [^>]* (for additional class before) - lang - space character(s) : - any character(s) - >
					any character until next group (.*)
					</pre>
			example: <pre class = " lang:  or <pre class="lang: or <pre
																		class = "lang:
		 */

		$this->options = get_option(APHPH_OPTION);
		$this->regex = "/(<pre\s[^>]*class\s*=\s*[\"\'][^>]*lang\s*:[^>]*>)(.*)(<\s*\/pre\s*>)/isU";
		add_action( 'wp_enqueue_scripts', array($this, 'load_scripts') );
		add_filter( 'the_content', array($this, 'filter_content'), 10, 1 );
		add_filter( 'comment_text', array($this, 'filter_comments'), 10, 1);
	}

	public function load_scripts()
	{
		// If not post or page, we cannot check this earlier
		// if (!is_single())
			// return;

		// echo 'ttt'; die;
		/**
			Only load javascript and css file when needed,
			we check it in the post and comment content
		*/

		global $post;
		preg_match_all($this->regex, $post->post_content, $match_post);
		if ($match_post[0])
		{
			$this->match_post = $match_post;
		}
 // print_r($post->post_content);
// echo '<pre>'; print_r($match_post); die;
		if ($post->comment_count)
		{
			$comments = get_comments( array('post_id' => $post->ID, 'status' => 'approve') );
			if ($comments)
			{
				foreach ($comments as $comment)
				{
					preg_match_all($this->regex, $comment->comment_content, $match_comment);
					if ($match_comment[0])
					{
						$this->match_comment = true;
						break;
					}
				}
			}
		}

		if ($this->match_post || $this->match_comment)
		{
			// New version
			$upload_dir = wp_upload_dir();
			$path = $upload_dir['basedir'] . APHPH_DS . 'aphph';

			$css_path = $upload_dir['basedir'] . '/aphph/aphph-prism-' . $this->options['token'] . '.css';
			$js_path = $upload_dir['basedir'] . '/aphph/aphph-prism-' . $this->options['token'] . '.js';

			if (!file_exists($css_path) || !file_exists($js_path))
			{
				require_once 'aphph-build.php';
				$obj = new Aphph_Build;
				$obj->build_files();
			}

			// $css_path = APHPH_PLUGIN_PATH . '/css/prism/aphph-prism-' . $this->options['token'] . '.css';
			// $js_path = APHPH_PLUGIN_PATH . '/js/prism/aphph-prism-' . $this->options['token'] . '.js';

			// if (!file_exists($css_path) || !file_exists($js_path))
			// {
				// require_once 'aphph-admin.php';
				// $obj = new Aphph_Admin();
				// $obj->build_files();
			// }

			wp_enqueue_script( 'aphsh-prism-js',  $upload_dir['baseurl'] . '/aphph/aphph-prism-' . $this->options['token'] . '.js' );
			wp_enqueue_style( 'aphsh-prism-css',  $upload_dir['baseurl'] . '/aphph/aphph-prism-' . $this->options['token'] . '.css' );
		}
	}

	public function filter_comments($comment)
	{
		if ($this->match_comment)
		{
			preg_match_all($this->regex, $comment, $matches);

			if ($matches[0])
			{
				$comment = $this->alter_content($comment, $matches);
			}
		}
		return $comment;
	}

	public function filter_content($content)
	{
		// If not post or page, we cannot check this earlier
		// if (is_single())
		// {
			if ($this->match_post)
			{
				return $this->alter_content($content, $this->match_post);
			}
		// }
		return $content;
	}

	public function alter_content($content, $matches)
	{
		$default_pretag_class = array('aphph-container');
		$default_pretag_data = array();

		if ($this->options['class'])
			$default_pretag_class['default'] = $this->options['class'];

		if ($this->options['gutter']) {
			$default_pretag_class['line-number'] = 'line-numbers';
			if ($this->options['start-number'] != 1)
			{
				$default_pretag_data['start-line-number'] = 'data-start="'.$this->options['start-number'].'"';
			}
		}

		foreach ($matches[1] as $tag_index => $pre_tag)
		{
			$new_pre_tag = $pre_tag;
			$pretag_class = $default_pretag_class;
			$pretag_data = $default_pretag_data;

			// Get class value
			preg_match('/class\s*=\s*[\"\']([^\"\']*)[\"\']/si', $pre_tag, $attr_class);

			/**
			 * Fix class value by removing space between : sign => lang :  php become lang:php
			 * and remove multiple whitespace
			 * this is because we'll explode it using space
			*/
			$fixed_class = preg_replace('/(\s*:\s*)/', ':', trim($attr_class[1]));
			$fixed_class = preg_replace('/\s+/', ' ', trim($fixed_class));

			/* Translate the css value into prism */
			$exp = explode(' ', $fixed_class);
			$language = '';

			foreach ($exp as $key => $class_item)
			{
				$split = explode(':', $class_item);
				$param = trim($split[0]);
				$value = trim($split[1]);

				// Used to compare with default options
				$param_list[$param] = $param;

				// language
				if ($param == 'lang') {
					if (strpos($value, 'add') !== false) {
						$language = $value;
						$pretag_class['aphph-'.$value] = 'aphph-'.$value;
						$codetag_class = 'aphph-' . $value;
						$lang_name = $value;
					} else {
						$codetag_class = 'language-' . $value;
						$lang_name = $value;
					}
				}

				// highlight
				elseif ($param == 'mark') {
					$pretag_data['mark'] = 'data-line="' . $value . '"';
				}

				// class-name
				elseif ($param == 'class')
				{
					$pretag_class['add'] = $value;
				}

				// gutter
				elseif ($param == 'gutter')
				{
					if ($value == 'true')
					{
						$pretag_class['line-number'] = 'line-numbers';
					} else {
						unset($pretag_class['line-number']);
					}
				}

				// start number
				elseif ($param == 'start')
				{
					if ($value != 1)
						$pretag_data['start-line-number'] = 'data-start="'.$value.'"';
				}
			}

			/* Clean up $pretag_class
			 * Currently we can't combine line highlight with show line numbers
			 * so if the line highlight is active, we disable the show line numbers
			 * also we don't add line number form plain language
			*/
			if (strpos($language, 'add') !== false) {
				unset($pretag_class['line-number']);
			}

			if(key_exists('mark', $pretag_data))
				unset($pretag_class['line-number']);

			if(!key_exists('line-number', $pretag_class))
				unset($pretag_data['start-line-number']);

			// Add language rel.
			$langRelTag = 'rel="' . $lang_name . '"';
			$new_pre_tag = preg_replace('/([^>]*)>/', '$1 ' . $langRelTag . '>', $new_pre_tag);

			// Add data- atttribute
			if ($pretag_data) {
				$new_pre_tag = preg_replace('/([^>]*)>/', '$1 ' . join($pretag_data, ' ') . '>', $new_pre_tag);
			}

			// Add class attribute
			if ($pretag_class) {
				$new_class = join($pretag_class, ' ');
				$new_pre_tag = preg_replace('/'.$attr_class[1].'/', $new_class, $new_pre_tag);
			} else {
				// Remove class attribute
				$new_pre_tag = preg_replace('/\s*class\s*=\s*[\"\'][^\"\']*[\"\']\s*/i', ' ', $new_pre_tag);
			}

			// Clean up change <pre   > to <pre> if any
			$new_pre_tag = preg_replace('/<pre\s*>/', '<pre>', $new_pre_tag);

			/**
			 * Bulid Code
			*/
			$content = preg_replace('/('.$pre_tag.')(.*)(<\s*\/pre\s*>)/isU',
									$new_pre_tag . '<code ' . $langRelTag . ' class="'.$codetag_class.'">$2</code></pre>',
									$content);
		}
		return $content;
	}
}
