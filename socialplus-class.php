<?php
class SocialPlus {

	private static $initialized;

	public static $sbuttons = array();
	public static $pbuttons = array();
	private static $themes;
	private static $config = array(
		'socialplus_share_settings' => array('show_home' => 0, 'show_pages' => 1, 'show_posts' => 1, 'position' => 1, 'theme' => 0),
		'socialplus_share_buttons' => array('facebook' => 1, 'twitter' => 1, 'googleplus' => 1, 'print' => 1),
		'socialplus_share_pos' => array(0 => 'facebook', 1 => 'twitter', 2 => 'googleplus'),
		'socialplus_profile_settings' => array('newtab' => 1, 'theme' => 0),
		'socialplus_profile_buttons' => array('facebook' => 1, 'twitter' => 1, 'googleplus' => 1, 'rss' => 1),
		'socialplus_profile_pos' => array(0 => 'facebook', 1 => 'twitter', 2 => 'googleplus')
	);
	
	public static function init(){
		if(self::$initialized){
			return;
		}
		//Share Buttons
		self::$sbuttons["facebook"] = new ShareButton("Facebook", "facebook", "https://www.facebook.com/sharer/sharer.php?u={URL}", "#3a589e");
		self::$sbuttons["twitter"] = new ShareButton("Twitter", "twitter", "https://twitter.com/intent/tweet?url={URL}", "#00aced");
		self::$sbuttons["googleplus"] = new ShareButton("Google+", "googleplus", "https://plus.google.com/share?url={URL}", "#d34836");
		self::$sbuttons["pinterest"] = new ShareButton("Pinterest", "pinterest", "http://pinterest.com/pin/create/button/?url={URL}", "#cd2029");
		self::$sbuttons["linkedin"] = new ShareButton("LinkedIn", "linkedin", "https://www.linkedin.com/shareArticle?url={URL}", "#0077b5");
		self::$sbuttons["print"] = new ShareButton("Print", "print", "http://www.printfriendly.com/print?url={URL}", "#777");
		self::$sbuttons["mail"] = new ShareButton("Email", "mail", "mailto:address@email.com?body={URL}", "#777");
		//Profile Buttons
		self::$pbuttons["facebook"] = new ProfileButton("Facebook", "facebook", "https://www.facebook.com/{PROFILE}", "#3a589e");
		self::$pbuttons["twitter"] = new ProfileButton("Twitter", "twitter", "https://twitter.com/{PROFILE}", "#00aced");
		self::$pbuttons["googleplus"] = new ProfileButton("Google+", "googleplus", "https://plus.google.com/{PROFILE}", "#d34836");
		self::$pbuttons["pinterest"] = new ProfileButton("Pinterest", "pinterest", "https://pinterest.com/{PROFILE}", "#cd2029");
		self::$pbuttons["linkedin"] = new ProfileButton("LinkedIn", "linkedin", "https://linkedin.com/in/{PROFILE}", "#0077b5");
		self::$pbuttons["instagram"] = new ProfileButton("Instagram", "instagram", "https://www.instagram.com/{PROFILE}", "#e95950");
		self::$pbuttons["github"] = new ProfileButton("GitHub", "github", "http://github.com/{PROFILE}", "transparent");
		self::$pbuttons["soundcloud"] = new ProfileButton("SoundCloud", "soundcloud", "https://soundcloud.com/{PROFILE}", "#ff3300");
		self::$pbuttons["youtube"] = new ProfileButton("YouTube", "youtube", "https://www.youtube.com/channel/{PROFILE}", "#cc181e");
		self::$pbuttons["rss"] = new ProfileButton("RSS", "rss", "{PROFILE}", "#f26522", get_bloginfo('rss2_url', 'raw'));
		self::$pbuttons["mail"] = new ProfileButton("Email", "mail", "mailto:{PROFILE}", "#777", get_bloginfo('admin_email', 'raw'));
		//Themes
		self::$themes = array(
			new SocialPlusFlatTheme(),
			new SocialPlusRoundedTheme(),
			new SocialPlusCleanTheme(),
			new SocialPlusSolidTheme()
		);
		self::$initialized = true;
	}
	
	public static function install(){
		$option = get_option('socialplus_share_settings');
		if(!$option){
			add_option('socialplus_share_settings', self::$config['socialplus_share_settings']);
		}
		$option = get_option('socialplus_share_buttons');
		if(!$option){
			add_option('socialplus_share_buttons', self::$config['socialplus_share_buttons']);
		}
		$option = get_option('socialplus_share_pos');
		if(!$option){
			add_option('socialplus_share_pos', self::$config['socialplus_share_pos']);
		}
		$option = get_option('socialplus_profile_settings');
		if(!$option){
			add_option('socialplus_profile_settings', self::$config['socialplus_profile_settings']);
		}
		$option = get_option('socialplus_profile_buttons');
		if(!$option){
			add_option('socialplus_profile_buttons', self::$config['socialplus_profile_buttons']);
		}
		$option = get_option('socialplus_profile_pos');
		if(!$option){
			add_option('socialplus_profile_pos', self::$config['socialplus_profile_pos']);
		}
	}
	
	public static function getTheme($id){
		if(isset(self::$themes[$id])){
			return self::$themes[$id];
		}
		return null;
	}
	
	public static function getAllThemes(){
		return self::$themes;
	}
	
	public static function themeExists($id){
		foreach(self::$themes as $theme){
			if($theme->getThemeID() == $id){
				return true;
			}
		}
		return false;
	}
	
	public static function getShareButtonsTheme(){
		return self::$themes[get_option('socialplus_share_settings')['theme']];
	}
	
	public static function getProfileButtonsTheme(){
		return self::$themes[get_option('socialplus_profile_settings')['theme']];
	}

	public static function getAllShareButtons(){
		return self::$sbuttons;
	}
	
	public static function getShareButtonsOrdered(){
		$pos = get_option('socialplus_share_pos');
		$array = array();
		for($i = 0; $i < count($pos); $i++){
			if(isset(self::$sbuttons[$pos[$i]])){
				array_push($array, self::$sbuttons[$pos[$i]]);
			}
		}
		//Add missing elements (if there are)
		$left = self::getAllShareButtons();
		foreach($array as $b){
			unset($left[$b->getRawName()]);
		}
		foreach($left as $b){
			array_push($array, $b);
		}
		return $array;
	}
	
	public static function shareButtonExists($id){
		return array_key_exists($id, self::$sbuttons);
	}
	
	public static function getAllProfileButtons(){
		return self::$pbuttons;
	}
	
	public static function getProfileButtonsOrdered(){
		$pos = get_option('socialplus_profile_pos');
		$array = array();
		for($i = 0; $i < count($pos); $i++){
			if(isset(self::$pbuttons[$pos[$i]])){
				array_push($array, self::$pbuttons[$pos[$i]]);
			}
		}
		//Add missing elements (if there are)
		$left = self::getAllProfileButtons();
		foreach($array as $b){
			unset($left[$b->getRawName()]);
		}
		foreach($left as $b){
			array_push($array, $b);
		}
		return $array;
	}
	
	public static function profileButtonExists($id){
		return array_key_exists($id, self::$pbuttons);
	}
}

class ShareButton {

	private $name;
	private $rawname;
	private $shareurl;
	private $color;

	public function __construct($name, $rawname, $shareurl, $color){
		$this->name = $name;
		$this->rawname = $rawname;
		$this->shareurl = $shareurl;
		$this->color = $color;
	}
	public function getName(){
		return $this->name;
	}
	
	public function getRawName(){
		return $this->rawname;
	}
	
	public function getShareUrl($url){
		return str_replace("{URL}", urlencode($url), $this->shareurl);
	}
	
	public function getColor(){
		return $this->color;
	}
}

class ProfileButton {

	private $name;
	private $rawname;
	private $profileurl;
	private $color;
	
	public $profile;

	public function __construct($name, $rawname, $profileurl, $color, $profile = null){
		$this->name = $name;
		$this->rawname = $rawname;
		$this->profileurl = $profileurl;
		$this->color = $color;
		$this->profile = $profile;
	}
	public function getName(){
		return $this->name;
	}
	
	public function getRawName(){
		return $this->rawname;
	}
	
	public function getRawProfileUrl(){
		return $this->profileurl;
	}
	
	public function getProfileUrl($user = null){
		if(!$user){
			return str_replace("{PROFILE}", $this->profile, $this->profileurl);
		}
		return str_replace("{PROFILE}", $user, $this->profileurl);
	}
	
	public function getColor(){
		return $this->color;
	}
}
?>