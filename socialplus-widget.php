<?php
class SocialPlus_Widget extends WP_Widget {

	public function __construct(){
		$options = array(
			'classname' => 'SocialPlus_Widget',
			'description' => __('Displays your favourite social network profile buttons.', 'socialplus'));
		parent::__construct('SocialPlus_Widget', __('SocialPlus Profile Buttons', 'socialplus'), $options);
	}

	public function form($instance) {	
		$title = $instance['title']?>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('title'));?>"><?php esc_attr_e('Title:');?></label> 
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title'));?>" name="<?php echo esc_attr($this->get_field_name('title'));?>" type="text" value="<?php echo esc_attr($title);?>">
		</p>
		<?php 
	}
	
	public function update($new_instance, $old_instance){
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
		return $instance;
	}

	public function widget($args, $instance){
		$title = apply_filters('widget_title', !empty($instance['title']) ? $instance['title'] : esc_html__('Social Buttons', 'socialplus'));
		$options = get_option('socialplus_profile_settings');
		$btns = get_option('socialplus_profile_buttons');
		$links = get_option('socialplus_profile_links');
		echo $args['before_widget'] . $args['before_title'] . $title . $args['after_title'];?>
		<div style="display: inline-block"><?php
		foreach(SocialPlus::getProfileButtonsOrdered() as $b){
			isset($btns[$b->getRawName()]) ? $enabled = $btns[$b->getRawName()] : $enabled = false;
			if($enabled){
				isset($links[$b->getRawName()]) ? $profile = $links[$b->getRawName()] : $profile = null;
				SocialPlus::getProfileButtonsTheme()->drawProfileButton($b, $profile, isset($options['newtab']) ? $options['newtab'] : false);
			}
		}?>
		</div><br><?php
		echo $args['after_widget'];
	}
}
add_action('widgets_init', create_function('', 'return register_widget("SocialPlus_Widget");'));
?>