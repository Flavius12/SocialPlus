<?php
defined('ABSPATH') or die('This script can\'t be called directly!');

add_action('init', array('SocialPlusWrapper', 'init'));

class SocialPlusWrapper {
	public static function init(){
		add_action('wp_head', array(get_class(SocialPlus::getShareButtonsTheme()), 'initHead'));
		add_action('wp_head', array(get_class(SocialPlus::getProfileButtonsTheme()), 'initHead'));
		add_filter('the_content', array('SocialPlusWrapper', 'pages'));
	}
	
	public static function pages($content){
		$options = get_option('socialplus_share_settings');
		$before = null;
		$after = null;
		if($options['position'] & 1){
			$before = SocialPlusWrapper::getButtons();
		}
		if($options['position'] & 2){
			$after = SocialPlusWrapper::getButtons();
		}
		if(is_home() && $options['show_home']){
			echo $before . $content . $after;
			return;
		}else if(is_single() && $options['show_posts']){
			return $before . $content . $after;
		}else if(is_page() && $options['show_pages']){
			return $before . $content . $after;
		}
		return $content;
	}
	
	private static function getButtons(){
		global $wp;
		$options = get_option('socialplus_share_settings');
		$btns = get_option('socialplus_share_buttons');
		$text = '<div style="display: inline-block">';
		foreach(SocialPlus::getShareButtonsOrdered() as $s){
			isset($btns[$s->getRawName()]) ? $enabled = $btns[$s->getRawName()] : $enabled = false;
			if($enabled){
				$text .= SocialPlus::getShareButtonsTheme()->getShareButton($s, get_permalink());
			}
		}
		$text .= '</div><br>';
		return $text;
	}
}
?>