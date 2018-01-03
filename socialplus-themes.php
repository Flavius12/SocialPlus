<?php
interface SocialPlusTheme {
	public static function getThemeID();
	public static function getThemeName();
	public static function initHead();
	public function getShareButton(ShareButton $s, $url = null);
	public function drawShareButton(ShareButton $s, $url = null);
	public function drawSharePreview(ShareButton $s);
	public function getProfileButton(ProfileButton $p, $profile = null, $newtab = false);
	public function drawProfileButton(ProfileButton $p, $profile = null, $newtab = false);
	public function drawProfilePreview(ProfileButton $p);
}

class SocialPlusFlatTheme implements SocialPlusTheme {

	public static function getThemeID(){
		return 'flat';
	}
	
	public static function getThemeName(){
		return "Flat";
	}
	
	public static function initHead(){
		echo '<link rel="stylesheet" href="' . SOCIALPLUS_PLUGIN_URL . 'themes/flat/style.css' . '" type="text/css">';
	}
	
	public function getShareButton(ShareButton $s, $url = null){
		$url ? $link = ' target="_blank" href="' . $s->getShareUrl($url) . '"' : $link = '';
		return '<a class="flatstyle-button ' . $s->getRawName() . '"' . $link . '>' . file_get_contents(SOCIALPLUS_PLUGIN_PATH . "include/" .  $s->getRawName() . ".svg") . '<span class="flatstyle-title"> ' . $s->getName() . '</span></a>';
	}
	
	public function drawShareButton(ShareButton $s, $url = null){
		echo $this->getShareButton($s, $url);
	}
	
	public function drawSharePreview(ShareButton $s){
		echo '<a class="flatstyle-button ' . $s->getRawName() . '">' . file_get_contents(SOCIALPLUS_PLUGIN_PATH . "include/" .  $s->getRawName() . ".svg") . '<span class="flatstyle-title"> ' . $s->getName() . '</span></a>';
	}
	
	public function getProfileButton(ProfileButton $p, $profile = null, $newtab = false){
		return '<a class="flatstyle-profile ' . $p->getRawName() . '"' . (($profile) ? ' href="' . $p->getProfileUrl($profile) . '"' : '') . (($newtab) ? ' target="_blank"' : '') . '>' . file_get_contents(SOCIALPLUS_PLUGIN_PATH . "include/" .  $p->getRawName() . ".svg") . '</a>';
	}
	
	public function drawProfileButton(ProfileButton $p, $profile = null, $newtab = false){
		echo $this->getProfileButton($p, $profile, $newtab);
	}
	
	public function drawProfilePreview(ProfileButton $p){
		echo $this->getProfileButton($p, null);
	}
}

class SocialPlusRoundedTheme implements SocialPlusTheme {
	
	public static function getThemeID(){
		return 'rounded';
	}
	
	public static function getThemeName(){
		return "Rounded";
	}
	
	public static function initHead(){
		echo '<link rel="stylesheet" href="' . SOCIALPLUS_PLUGIN_URL . 'themes/rounded/style.css' . '" type="text/css">';
	}
	
	public function getShareButton(ShareButton $s, $url = null){
		$url ? $link = ' target="_blank" href="' . $s->getShareUrl($url) . '"' : $link = '';
		return '<a class="roundedstyle-button ' . $s->getRawName() . '"' . $link . '>' . file_get_contents(SOCIALPLUS_PLUGIN_PATH . "include/" .  $s->getRawName() . ".svg") . '<span class="roundedstyle-title"> ' . $s->getName() . '</span></a>';
	}
	
	public function drawShareButton(ShareButton $s, $url = null){
		echo $this->getShareButton($s, $url);
	}
	
	public function drawSharePreview(ShareButton $s){
		echo '<a class="roundedstyle-button ' . $s->getRawName() . '">' . file_get_contents(SOCIALPLUS_PLUGIN_PATH . "include/" .  $s->getRawName() . ".svg") . '<span class="roundedstyle-title"> ' . $s->getName() . '</span></a>';
	}
	
	public function getProfileButton(ProfileButton $p, $profile = null, $newtab = false){
		return '<a class="roundedstyle-profile ' . $p->getRawName() . '"' . (($profile) ? ' href="' . $p->getProfileUrl($profile) . '"' : '') . (($newtab) ? ' target="_blank"' : '') . '>' . file_get_contents(SOCIALPLUS_PLUGIN_PATH . "include/" .  $p->getRawName() . ".svg") . '</a>';
	}
	
	public function drawProfileButton(ProfileButton $p, $profile = null, $newtab = false){
		echo $this->getProfileButton($p, $profile, $newtab);
	}
	
	public function drawProfilePreview(ProfileButton $p){
		echo $this->getProfileButton($p, null);
	}
}

class SocialPlusCleanTheme implements SocialPlusTheme {
	
	public static function getThemeID(){
		return 'clean';
	}
	
	public static function getThemeName(){
		return "Clean";
	}
	
	public static function initHead(){
		echo '<link rel="stylesheet" href="' . SOCIALPLUS_PLUGIN_URL . 'themes/clean/style.css' . '" type="text/css">';
	}
	
	public function getShareButton(ShareButton $s, $url = null){
		$url ? $link = ' target="_blank" href="' . $s->getShareUrl($url) . '"' : $link = '';
		return '<a class="cleanstyle-button ' . $s->getRawName() . '"' . $link . '>' . file_get_contents(SOCIALPLUS_PLUGIN_PATH . "include/" .  $s->getRawName() . ".svg") . '<span class="cleanstyle-title"> ' . $s->getName() . '</span></a>';
	}
	
	public function drawShareButton(ShareButton $s, $url = null){
		echo $this->getShareButton($s, $url);
	}
	
	public function drawSharePreview(ShareButton $s){
		echo '<a class="cleanstyle-button ' . $s->getRawName() . '">' . file_get_contents(SOCIALPLUS_PLUGIN_PATH . "include/" .  $s->getRawName() . ".svg") . '<span class="cleanstyle-title"> ' . $s->getName() . '</span></a>';
	}
	
	public function getProfileButton(ProfileButton $p, $profile = null, $newtab = false){
		return '<a class="cleanstyle-profile ' . $p->getRawName() . '"' . (($profile) ? ' href="' . $p->getProfileUrl($profile) . '"' : '') . (($newtab) ? ' target="_blank"' : '') . '>' . file_get_contents(SOCIALPLUS_PLUGIN_PATH . "include/" .  $p->getRawName() . ".svg") . '</a>';
	}
	
	public function drawProfileButton(ProfileButton $p, $profile = null, $newtab = false){
		echo $this->getProfileButton($p, $profile, $newtab);
	}
	
	public function drawProfilePreview(ProfileButton $p){
		echo $this->getProfileButton($p, null);
	}
}


class SocialPlusSolidTheme implements SocialPlusTheme {
	
	public static function getThemeID(){
		return 'solid';
	}
	
	public static function getThemeName(){
		return "Solid";
	}
	
	public static function initHead(){
		echo '<link rel="stylesheet" href="' . SOCIALPLUS_PLUGIN_URL . 'themes/solid/style.css' . '" type="text/css">';
	}
	
	public function getShareButton(ShareButton $s, $url = null){
		$url ? $link = ' target="_blank" href="' . $s->getShareUrl($url) . '"' : $link = '';
		return '<a class="solidstyle-button ' . $s->getRawName() . '"' . $link . '>' . file_get_contents(SOCIALPLUS_PLUGIN_PATH . "include/" .  $s->getRawName() . ".svg") . '<span class="solidstyle-title"> ' . $s->getName() . '</span></a>';
	}
	
	public function drawShareButton(ShareButton $s, $url = null){
		echo $this->getShareButton($s, $url);
	}
	
	public function drawSharePreview(ShareButton $s){
		echo '<a class="solidstyle-button ' . $s->getRawName() . '">' . file_get_contents(SOCIALPLUS_PLUGIN_PATH . "include/" .  $s->getRawName() . ".svg") . '<span class="solidstyle-title"> ' . $s->getName() . '</span></a>';
	}
	
	public function getProfileButton(ProfileButton $p, $profile = null, $newtab = false){
		return '<a class="solidstyle-profile ' . $p->getRawName() . '"' . (($profile) ? ' href="' . $p->getProfileUrl($profile) . '"' : '') . (($newtab) ? ' target="_blank"' : '') . '>' . file_get_contents(SOCIALPLUS_PLUGIN_PATH . "include/" .  $p->getRawName() . ".svg") . '</a>';
	}
	
	public function drawProfileButton(ProfileButton $p, $profile = null, $newtab = false){
		echo $this->getProfileButton($p, $profile, $newtab);
	}
	
	public function drawProfilePreview(ProfileButton $p){
		echo $this->getProfileButton($p, null);
	}
}
?>