<?php
class Aphph_Admin_Editor
{
    private $options;
    private $options_phdata;
    
    public function __construct()
    {
        add_action('edit_form_after_title', array($this, 'add_modal_dialog'));
                
        /**
            ADD icon button to tinyMCE toolbar and add functionality
            to be able to show pop up window of APH Syntax Highlighter
        */
        add_action('init', array($this, 'aphph_button'));
        
        /**
            EMBED style to tinymce textarea-iframe editor (visual editor)
            we can not add it using wp_enqueue_style
        */
        add_filter('mce_css', array($this, 'aphph_tinymce_editor_css'));
        
        /**
            Add style to APH Syntax Highlighter pop up windows
        */
        add_action('admin_enqueue_scripts', array($this, 'register_scripts_post'));
        
        
        /**
         * Add dialog and necessari scripts to admin edit comments page
        */
        add_action('current_screen', array($this, 'register_scripts_comments'), 10, 2);
        add_action('current_screen', array($this, 'admin_comments_form'));
        
        /**
         * When the cursor inside the <pre> tag, then the tag will be highlighted using a class named aphph-pretag-focused
         * so we need to remove it before save into database
        */
        add_action('content_save_pre', array($this, 'clean_tag'), 10, 2);
        
        $this->options = get_option(APHPH_OPTION);
        $this->options_phdata = get_option(APHPH_OPTION_PHDATA);
    }
    
    public function admin_comments_form($screen)
    {
        if ($screen->id == 'edit-comments') {
            $this->add_modal_dialog();
        }
    }
    
    public function add_modal_dialog()
    {
        $lang_list = $this->options['lang-used'];
        $lang_options = '';
        
        foreach ($lang_list as $lang) {
            if ($lang == 'core') {
                continue;
            }
            
            $lang_name = $this->options_phdata['lang-list'][$lang];
            $selected = $lang == $this->options['default-lang'] ? ' selected="selected"' : '';
            $lang_options .= '<option value="' . $lang . '"' . $selected . '>' . $lang_name . '</option>';
        }
        
        echo '
		<div class="aphph-overlay" id="aphph-editor-overlay"></div>
		<div class="aphph-editor-wrap" id="aphph-editor-wrap">
			<div class="aphph-editor-title" id="aphph-editor-title">
				APH Prism Highlighter
				<button type="button" class="aphph-editor-closebtn">
			</div>
			<div class="aphph-editor-body" id="aphph-editor-body">
				<div class="aphph-inline-options aphph-clearfix">
					<span>Language</span>
					<select name="aphph-language" id="aphph-language">'.
                        $lang_options . '
					</select>
					<span class="aphph-te-section">Highlight Line</span>
					<input type="text" class="aphph-small-text" name="aphph_highlight_lines" id="aphph-highlight-lines"/><span class="description">e.q: 1,2,3-6</span>
					
				</div>
				<textarea placeholder="Code..." class="aphph-editor-code" id="aphph-editor-code"></textarea>
				<h2 id="aphph-other-options"><i class="aphph-icon-circle-down"></i>Other Options</h2>
				<div id="aphph-other-options-container" style="display:none">
					<table class="aphph-options-bottom">
						<tr>
							<td>Add Class</td>
							<td><input type="text" class="medium-text" name="aphph_input_class_name" id="aphph-input-class-name"/><span class="description">*) Without space</span></td>
						</tr>
					</table>
					<h2 class="aphph-small-title">Override Default Options</h2>
					<table class="aphph-override-options">
						<tr>
							<th>Override</th>
							<th>Option</th>
						</tr>
						<tr>
							<td>
								<input type="checkbox" name="aphph_overr_showln" id="aphph-overr-showln"/>
							</td>
							<td>
								<span>Show Line Numbers:</span> 
								<select name="aphph_opt_showln" id="aphph-opt-showln">
									<option value="false">No</option>
									<option value="true">Yes</option>
								</select>
								Start Number: <input type="text" class="aphph-small-text" name="aphph_start_number" id="aphph-start-number" value="1"/>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="aphph-editor-submitbox">
				<div class="aphph-cancel-btn">
					<input type="button" class="button" id="aphph-cancel" value="Cancel"/>
				</div>
				<div class="aphph-insert-btn">
					<input type="button" value="Insert Code" class="button button-primary" id="aphph-submit">
				</div>
			</div>
		</div>';
        
        /* Options, used to change the language dropdown menu to default value,
         * we don,t use global variable, so we use this
        */
        echo '<span id="aphph-json-user-options" style="display:none">'.json_encode($this->options).'</span>';
    }

    public function aphph_button()
    {
        add_filter('mce_external_plugins', array($this, 'aphph_add_buttons'));
        add_filter('mce_buttons', array($this, 'aphph_register_buttons'));
    }
    
    public function aphph_add_buttons($plugin_array)
    {
        $plugin_array['aphph_tinymce_btn'] = APHPH_PLUGIN_URL . '/js/aphph-tinymce.js?r='.time();
        return $plugin_array;
    }
    
    public function aphph_register_buttons($buttons)
    {
        array_push($buttons, 'aphph');
        return $buttons;
    }
    
    private function register_scripts()
    {
        wp_enqueue_style('aphph-code-editor', APHPH_PLUGIN_URL . '/css/aphph-code-editor.css');
        wp_enqueue_style('aphph-icomoon', APHPH_PLUGIN_URL . '/css/icomoon/style.css');
        wp_enqueue_script('aphph-taboverride', APHPH_PLUGIN_URL . '/js/taboverride/taboverride.min.js');
        wp_enqueue_script('aphph-admin-editor', APHPH_PLUGIN_URL . '/js/aphph-admin-editor.js', 'jquery', '');
    }
    
    // Add editor to admin comment
    public function register_scripts_comments($screen)
    {
        if ($screen->id == 'edit-comments') {
            $this->register_scripts();
        }
    }
    
    // Add editor to add or edit post / page
    public function register_scripts_post($hook)
    {
        if ($hook == 'post-new.php' || $hook == 'post.php') {
            $this->register_scripts();
        }
    }
    
    public function aphph_tinymce_editor_css($wp)
    {
        $wp .= ',' . APHPH_PLUGIN_URL . '/css/aphph-tinymce-editor.css';
        return $wp;
    }

    public function clean_tag($content)
    {
        return preg_replace('/\s*aphph-pretag-focused\s*/', '', $content);
    }
}
