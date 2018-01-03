<?php
class SocialPlusAdmin {

	private static $initialized;

	public static function init(){
		if(self::$initialized){
			return;
		}
		load_plugin_textdomain('socialplus', false, SOCIALPLUS_PLUGIN_PATH . '/languages/');
		foreach(SocialPlus::getAllThemes() as $theme){
			add_action('admin_head', array(get_class($theme), 'initHead'));
		}
		add_action('admin_head', array('SocialPlusAdmin', 'initHead'));
		add_action('wp_ajax_socialplus_save', array('SocialPlusAdmin', 'ajaxSave'));
		add_action('admin_menu', array('SocialPlusAdmin', 'initMenu'));
		self::$initialized = true;
	}
	
	public static function initHead(){
		wp_enqueue_style('SocialPlus', SOCIALPLUS_PLUGIN_URL . 'include/style.css');
		wp_enqueue_script('SocialPlus', SOCIALPLUS_PLUGIN_URL . 'include/script.js', array('jquery', 'jquery-ui-sortable'));
		wp_localize_script('SocialPlus', 'SPAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
	}

	public static function initMenu(){
		add_menu_page('SocialPlus', 'SocialPlus', 'administrator', 'socialplus', array('SocialPlusAdmin', 'fillSettings'), 'dashicons-share');
		add_action('admin_init', array('SocialPlusAdmin', 'initSettings'));
	}
	
	public static function initSettings(){
		//Share Buttons section
		add_settings_section('share_buttons', __('Share buttons settings', 'socialplus'), array('SocialPlusAdmin', 'fillShareSubtitle'), 'share_buttons_page');
		add_settings_field('visibility', __('Visibility', 'socialplus'), array('SocialPlusAdmin', 'fillShareVisibility'), 'share_buttons_page', 'share_buttons');
		add_settings_field('position', __('Position', 'socialplus'), array('SocialPlusAdmin', 'fillSharePosition'), 'share_buttons_page', 'share_buttons');
		add_settings_field('buttons', __('Buttons', 'socialplus'), array('SocialPlusAdmin', 'fillShareButtons'), 'share_buttons_page', 'share_buttons');
		add_settings_field('themes', __('Theme', 'socialplus'), array('SocialPlusAdmin', 'fillShareThemes'), 'share_buttons_page', 'share_buttons');
		//Profile section
		add_settings_section('profile_settings', __('Profile buttons settings', 'socialplus'), array('SocialPlusAdmin', 'fillProfileSubtitle'), 'profile_page');
		$links = get_option('socialplus_profile_links');
		foreach(SocialPlus::$pbuttons as $k => $v){
			add_settings_field($k, $v->getName(), array('SocialPlusAdmin', 'fillProfileUrl'), 'profile_page', 'profile_settings', array($v, $links));
		}
		add_settings_field('buttons', __('Buttons', 'socialplus'), array('SocialPlusAdmin', 'fillProfileButtons'), 'profile_page', 'profile_settings');
		add_settings_field('newtab', __('Open in new tab', 'socialplus'), array('SocialPlusAdmin', 'fillProfileNewtab'), 'profile_page', 'profile_settings');
		add_settings_field('themes', __('Theme', 'socialplus'), array('SocialPlusAdmin', 'fillProfileThemes'), 'profile_page', 'profile_settings');
		//Register settigns now
		register_setting('socialplus_settings_ss', 'socialplus_share_settings', array('SocialPlusAdmin', 'validateShareSettings'));
		register_setting('socialplus_settings_ss', 'socialplus_share_buttons', array('SocialPlusAdmin', 'validateShareButtons'));
		register_setting('socialplus_settings_ps', 'socialplus_profile_settings', array('SocialPlusAdmin', 'validateProfileSettings'));		
		register_setting('socialplus_settings_ps', 'socialplus_profile_links', array('SocialPlusAdmin', 'validateProfileLinks'));		
		register_setting('socialplus_settings_ps', 'socialplus_profile_buttons', array('SocialPlusAdmin', 'validateProfileButtons'));
	}
	
	public static function fillShareSubtitle(){?>
		<div class="do-section-desc"><p class="description"><?php _e('Configure share buttons that will be shown on homepage, blog pages or posts.', 'socialplus');?></p></div><?php
	}
	
	public static function fillShareVisibility(){
		$options = get_option('socialplus_share_settings');?>
		<fieldset><label><input type="checkbox" name="socialplus_share_settings[show_home]" value="1" <?php echo checked(1, $options['show_home'], false);?>><span class="dashicons dashicons-admin-home"></span> <?php _e('Show on home (some themes may not show them)', 'socialplus');?></label><br>
		<label><input type="checkbox" name="socialplus_share_settings[show_pages]" value="1" <?php echo checked(1, $options['show_pages'], false);?>><span class="dashicons dashicons-admin-page"></span> <?php _e('Show on pages', 'socialplus');?></label><br>
		<label><input type="checkbox" name="socialplus_share_settings[show_posts]" value="1" <?php echo checked(1, $options['show_posts'], false);?>><span class="dashicons dashicons-admin-post"></span> <?php _e('Show on posts', 'socialplus');?></label><br></fieldset><?php
	}

	public static function fillSharePosition(){
		$options = get_option('socialplus_share_settings');?>
		<fieldset><label><input type="radio" name="socialplus_share_settings[position]" value="1" <?php echo checked(1, $options['position'], false);?>> <?php _e('Above', 'socialplus');?></label><br>
		<label><input type="radio" name="socialplus_share_settings[position]" value="2" <?php echo checked(2, $options['position'], false);?>> <?php _e('Below', 'socialplus');?></label><br>
		<label><input type="radio" name="socialplus_share_settings[position]" value="3" <?php echo checked(3, $options['position'], false);?>> <?php _e('Both', 'socialplus');?></label><br></fieldset><?php
	}
	
	public static function fillShareButtons(){?>
		<ol class="listbox" id="share_buttons"><?php
		$options = get_option('socialplus_share_buttons');
		$i = 0;
		$btns = SocialPlus::getShareButtonsOrdered();
		foreach($btns as $s){
			isset($options[$s->getRawName()]) ? $e = $options[$s->getRawName()] : $e = false;
			echo '<li id="' . $s->getRawName() . '" index="' . $i . '"><div style="display: table"><div class="tabcell listicon ' . $s->getRawName() . '" style="background-color: ' . $s->getColor() . '">' . file_get_contents(SOCIALPLUS_PLUGIN_PATH . "include/" .  $s->getRawName() . ".svg") . '</div><div class="tabcell middle">' . $s->getName() . '</div><div class="tabcell"><input type="checkbox" name="socialplus_share_buttons[' . $s->getRawName() . ']" value="1" '. checked(1, $e, false) .'></div></div></li>';
			$i++;
		}?>
		</ol><span class="description"><i><?php _e('Drag the elements in the list to order your share buttons.', 'socialplus');?></i></span><?php
	}
	
	public static function fillShareThemes(){?>
		<fieldset><?php
		$options = get_option('socialplus_share_settings');
		$i = 0;
		foreach(SocialPlus::getAllThemes() as $theme){
			echo '<label><input type="radio" id="themes" name="socialplus_share_settings[theme]" value="' . $i . '"' . checked($i, $options['theme'], false) . '>' . $theme->getThemeName() . '</label><br><div style="display: inline-block">';
			$all = SocialPlus::getShareButtonsOrdered();
			if(empty($all)){
				$all = SocialPlus::getAllShareButtons();
			}
			foreach($all as $s){
				echo $theme->drawSharePreview($s); 
			}
			$i++;?>
			</div><br><?php
		}?>
		</fieldset><?php
	}
	
	public static function fillProfileSubtitle(){?>
		<div class="do-section-desc"><p class="description"><?php _e('Configure profile buttons available on the widget.', 'socialplus');?></p></div><?php
	}
	
	public static function fillProfileUrl($v){
		$p = $v[0];
		$options = $v[1];
		if(isset($options[$p->getRawName()])){
			$profile = $options[$p->getRawName()];
		}else{
			$profile = $p->profile;
		}
		$hprofile = $profile;
		if($hprofile == ''){
			$hprofile = '&lt;' . __('your_profile', 'socialplus') . '&gt;';
		}?>
		<input type="text" class="regular-text" name="socialplus_profile_links[<?php echo $p->getRawName();?>]" value="<?php echo $profile;?>"><br><span class="description"><i><?php printf(__('Your %s profile link will be: %s', 'socialplus'), '<b>' . $p->getName() . '</b>', '<b>' . $p->getProfileUrl($hprofile) . '</b>');?></i></span><?php
	}
	
	public static function fillProfileButtons(){?>
		<ol class="listbox" id="profile_buttons"><?php
		$options = get_option('socialplus_profile_buttons');
		$i = 0;
		$btns = SocialPlus::getProfileButtonsOrdered();
		foreach($btns as $p){
			isset($options[$p->getRawName()]) ? $e = $options[$p->getRawName()] : $e = false;
			echo '<li id="' . $p->getRawName() . '" index="' . $i . '"><div style="display: table"><div class="tabcell listicon ' . $p->getRawName() . '" style="background-color: ' . $p->getColor() . '">' . file_get_contents(SOCIALPLUS_PLUGIN_PATH . "include/" .  $p->getRawName() . ".svg") . '</div><div class="tabcell middle">' . $p->getName() . '</div><div class="tabcell"><input type="checkbox" name="socialplus_profile_buttons[' . $p->getRawName() . ']" value="1" '. checked(1, $e, false) .'></div></div></li>';
			$i++;
		}?>
		</ol><span class="description"><i><?php _e('Drag the elements in the list to order your profile buttons.', 'socialplus');?></i></span><?php
	}
	
	public static function fillProfileNewtab(){
		$options = get_option('socialplus_profile_settings');
		isset($options['newtab']) ? $nt = $options['newtab'] : $nt = false;
		echo '<input type="checkbox" name="socialplus_profile_settings[newtab]" value="1" '. checked(1, $nt, false) .'>';
	}	
		
	public static function fillProfileThemes(){?>
		<fieldset><?php
		$options = get_option('socialplus_profile_settings');
		$i = 0;
		foreach(SocialPlus::getAllThemes() as $theme){
			echo '<label><input type="radio" id="themes" name="socialplus_profile_settings[theme]" value="' . $i . '"' . checked($i, $options['theme'], false) . '>' . $theme->getThemeName() . '</label><br><div style="display: inline-block">';
			$all = SocialPlus::getProfileButtonsOrdered();
			if(empty($all)){
				$all = SocialPlus::getAllProfileButtons();
			}
			foreach($all as $p){
				echo $theme->drawProfilePreview($p); 
			}
			$i++;?>
			</div><br><?php
		}?>
		</fieldset><?php
	}
	
	public static function fillSettings(){
		if(!current_user_can('manage_options')){
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}
		if(isset($_GET['tab'])){
			$active = $_GET['tab'];
		}else{
			$active = "share_buttons_page";
		}?>
		<div class="wrap">
		<h1><?php _e('SocialPlus Settings', 'socialplus');?></h1>
		<div class="settings_left"><h2 class="nav-tab-wrapper"><a href="?page=socialplus&tab=share_buttons_page" class="nav-tab<?php echo ($active == "share_buttons_page" ? " nav-tab-active" : "");?>"><?php _e('Share buttons', 'socialplus');?></a>
		<a href="?page=socialplus&tab=profile_page" class="nav-tab<?php echo ($active == "profile_page" ? " nav-tab-active" : "");?>"><?php _e('Profile buttons', 'socialplus');?></a></h2>
		<form method="post" action="options.php"><?php
		settings_errors();
		if($active == "share_buttons_page"){
			settings_fields('socialplus_settings_ss');
			do_settings_sections('share_buttons_page');
		}else{
			settings_fields('socialplus_settings_ps');
			do_settings_sections('profile_page');
		}
		submit_button();?>
		</form></div>
		<?php self::fillRight();?>
		</div><?php
	}
	
	public static function ajaxSave(){
		if(!isset($_POST['page']) || !isset($_POST['data'])){
			die();
		}
		if($_POST['page'] == 'share_buttons'){
			register_setting('socialplus_settings_ss', 'socialplus_share_pos', array('SocialPlusAdmin', 'validateSharePos'));
			update_option('socialplus_share_pos', $_POST['data']);
		}else if($_POST['page'] == 'profile_buttons'){
			register_setting('socialplus_settings_ps', 'socialplus_profile_pos', array('SocialPlusAdmin', 'validateProfilePos'));
			update_option('socialplus_profile_pos', $_POST['data']);
		}
		die();
	}

	public static function fillRight(){?>
		<div class="settings_right">
		<div class="box"><div class="box-content"><h2><?php _e('Rate the plugin', 'socialplus');?></h2><br><p><?php printf(__('Let me know what do you think about this plugin. Please rate it on %s.', 'socialplus'), '<a href="https://wordpress.org/plugins/socialplus/" target="_blank">WordPress.org</a>');?></p></div></div>
		<div class="box"><div class="box-content"><h2><?php _e('Donate', 'socialplus');?></h2><br>
		<center><p><?php _e('If you want you can support the development of this plugin by making a small donation.', 'socialplus');?><br><?php _e('Thank you for your generosity', 'socialplus');?> :)</p><br><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="KQUV56MK3BS9U">
		<input type="image" src="https://www.paypalobjects.com/en_US/IT/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypalobjects.com/it_IT/i/scr/pixel.gif" width="1" height="1">
		</form><br>
		<p><?php printf(__('You can also donate %s to: %s', 'socialplus'), '<a href="https://bitcoin.org" target="_blank">Bitcoins</a>', '<i>1ZPwiihBwgAgApgBTytj5qH3CNWcBqro8</i>');?></p></center></div></div>
		<div class="box"><div class="box-content"><p>Made with <span class="dashicons dashicons-heart" style="color: #d44848"></span> by <a href="https://www.flavius12.net" target="_blank">Flavius12</a>.</p></div></div>
		</div><?php
	}
	
	public static function validateShareSettings($input){
		$output = array();
		$output['show_home'] = isset($input['show_home']) ? intval($input['show_home']) : 0;
		$output['show_pages'] = isset($input['show_pages']) ? intval($input['show_pages']) : 0;
		$output['show_posts'] = isset($input['show_posts']) ? intval($input['show_posts']) : 0;
		$output['position'] = isset($input['position']) ? intval($input['position']) : 1;
		$output['theme'] = isset($input['theme']) ? intval($input['theme']) : 0;
		if($output['show_home'] != 0 && $output['show_home'] != 1){
			$output['show_home'] = 0;//Default value here
		}
		if($output['show_pages'] != 0 && $output['show_pages'] != 1){
			$output['show_pages'] = 1;//Default value here
		}
		if($output['show_posts'] != 0 && $output['show_posts'] != 1){
			$output['show_posts'] = 1;//Default value here
		}
		if($output['position'] < 1 || $output['position'] > 3){
			$output['position'] = 1; //Default value here
		}
		if($output['theme'] >= count(SocialPlus::getAllThemes())){
			$output['theme'] = 0;
		}
		return $output;
	}
	
	public static function validateShareButtons($input){
		$output = array();
		if(!isset($input) && !is_array($input)){
			return get_option('socialplus_share_buttons');
		}
		foreach($input as $k => $v){
			if(SocialPlus::shareButtonExists($k)){
				$output[$k] = intval($v);
			}
		}
		return $output;
	}
	
	public static function validateSharePos($input){
		if(!isset($input) && !is_array($input)){
			return get_option('socialplus_share_pos');
		}
		foreach($input as $k => $v){
			if(is_int($k) && SocialPlus::shareButtonExists($v)){
				$output[$k] = $v;
			}
		}
		return $output;
	}
	
	public static function validateProfileSettings($input){
		$output = array();
		$output['newtab'] = isset($input['newtab']) ? intval($input['newtab']) : 1;
		$output['theme'] = isset($input['theme']) ? intval($input['theme']) : 0;
		if($output['newtab'] != 0 && $output['newtab'] != 1){
			$output['newtab'] = 1; //Default value here
		}
		if($output['theme'] >= count(SocialPlus::getAllThemes())){
			$output['theme'] = 0;
		}
		return $output;
	}
	
	public static function validateProfileLinks($input){
		$output = array();
		if(!isset($input) && !is_array($input)){
			return get_option('socialplus_profile_links');
		}
		foreach($input as $k => $v){
			if(SocialPlus::profileButtonExists($k)){
				$output[$k] = sanitize_text_field($v);
			}
		}
		return $output;
	}
	
	public static function validateProfileButtons($input){
		$output = array();
		if(!isset($input) && !is_array($input)){
			return get_option('socialplus_profile_buttons');
		}
		foreach($input as $k => $v){
			if(SocialPlus::profileButtonExists($k)){
				$output[$k] = intval($v);
			}
		}
		return $output;
	}
	
	public static function validateProfilePos($input){
		if(!isset($input) && !is_array($input)){
			return get_option('socialplus_profile_pos');
		}
		foreach($input as $k => $v){
			if(is_int($k) && SocialPlus::profileButtonExists($v)){
				$output[$k] = $v;
			}
		}
		return $output;
	}
}
?>