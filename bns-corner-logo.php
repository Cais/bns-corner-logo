<?php
/*
Plugin Name: BNS Corner Logo
Plugin URI: http://buynowshop.com/plugins/bns-corner-logo/
Description: Widget to display a user selected image as a logo; or, used as a plugin that displays the image fixed in one of the four corners of the display.
Version: 1.6
Author: Edward Caissie
Author URI: http://edwardcaissie.com/
License: GPL2
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

/* Last Updated: July 21, 2011 v1.6 */

/*  Copyright 2009-2011  Edward Caissie  (email : edward.caissie@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2,
    as published by the Free Software Foundation.

    You may NOT assume that you can use any other version of the GPL.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

    The license for this software can also likely be found here:
    http://www.gnu.org/licenses/gpl-2.0.html
*/

global $wp_version;
$exit_message = 'BNS Corner Logo requires WordPress version 3.0 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please Update!</a>';
if (version_compare($wp_version, "3.0", "<")) { // per the use of home_url()
	exit ($exit_message);
}

// Add BNS Corner Logo Scripts and Styles
function BNS_Corner_Logo_Scripts_and_Styles_Action() {
    /* Scripts */
    /* Styles */
  	wp_enqueue_style( 'BNS-Corner-Logo-Style', plugin_dir_url( __FILE__ ) . '/css/bns-corner-logo-style.css', array(), '1.5', 'screen' );
}
add_action('wp_enqueue_scripts', 'BNS_Corner_Logo_Scripts_and_Styles_Action');

/* Add our function to the widgets_init hook. */
add_action( 'widgets_init', 'load_bns_corner_logo_widget' );

/* Function that registers our widget. */
function load_bns_corner_logo_widget() {
	register_widget( 'BNS_Corner_Logo_Widget' );
}

class BNS_Corner_Logo_Widget extends WP_Widget {

	function BNS_Corner_Logo_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'bns-corner-logo', 'description' => __('Widget to display a logo; or, used as a plugin displays image fixed in one of the four corners.') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 200, 'height' => 350, 'id_base' => 'bns-corner-logo' );

		/* Create the widget. */
		$this->WP_Widget( 'bns-corner-logo', 'BNS Corner Logo', $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		/* User-selected settings. */
		$title          = apply_filters('widget_title', $instance['title'] );
		$use_gravatar   = $instance['use_gravatar'];
		$gravatar_size  = $instance['gravatar_size'];
		$image_url      = $instance['image_url'];
		$image_alt_text	= $instance['image_alt_text'];
		$image_link     = $instance['image_link'];
		$new_window     = $instance['new_window'];
		$widget_plugin	= $instance['widget_plugin'];		
		$logo_location	= $instance['logo_location'];
		$custom_x       = $instance['custom_x'];
		$custom_y       = $instance['custom_y'];
		
		if ( !$widget_plugin ) {
		
			/* Before widget (defined by themes). */
			echo $before_widget;
		
			/* Title of widget (before and after defined by themes). */
			if ( $title )
				echo $before_title . $title . $after_title;
			
			/* Display image based on widget settings. */ ?>
			<div class="bns-logo">
				<a <?php if ( $new_window ) echo 'target="_blank"'; ?> href="<?php echo $image_link; ?>">
					<!-- Use FIRST Admin gravatar -->
					<?php if ($use_gravatar) {
						echo get_avatar(get_bloginfo('admin_email'), $gravatar_size);
					} else { ?>
						<img style="" alt="<?php echo $image_alt_text; ?>" src="<?php echo $image_url;?>" />
					<?php } ?>
				</a>
			</div> <!-- .bns-logo -->
		
		<?php } else {
			if ( $logo_location == "Bottom-Right" ) {
				$logo_position = "bottom:0; right:0;";
			} elseif ( $logo_location == "Bottom-Left" ) {
				$logo_position = "bottom:0; left:0;";
			} elseif ( $logo_location == "Top-Right" ) {
				$logo_position = "top:0; right:0;";
			} elseif ( $logo_location == "Top-Left" ) {
				$logo_position = "top:0; left:0;"; 
			} elseif ( $logo_location == "Custom" ) {
			  $logo_position = "top:$custom_y; left:$custom_x;";
			}
			?>
			<div class="bns-logo" style="position:fixed; <?php echo $logo_position; ?> z-index:5;">
				<a <?php if ( $new_window ) echo 'target="_blank"'; ?> href="<?php echo $image_link; ?>">
					<!-- Use FIRST Admin gravatar -->
					<?php if ($use_gravatar) {
						echo get_avatar(get_bloginfo('admin_email'), $gravatar_size);
					} else { ?>
						<img style="" alt="<?php echo $image_alt_text; ?>" src="<?php echo $image_url;?>" />
					<?php } ?>
				</a>
			</div> <!-- .bns-logo -->
		<?php }
		/* End - Display image based on widget settings. */

		/* After widget (defined by themes). */
		if ( !$widget_plugin ) {
			echo $after_widget;
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		/* Strip tags (if needed) and update the widget settings. */
		$instance['title']          = strip_tags( $new_instance['title'] );
		$instance['use_gravatar']   = $new_instance['use_gravatar'];
		$instance['gravatar_size']	= $new_instance['gravatar_size'];
		$instance['image_url']      = strip_tags( $new_instance['image_url'] );
		$instance['image_alt_text']	= strip_tags( $new_instance['image_alt_text'] );
		$instance['image_link']     = strip_tags( $new_instance['image_link'] );
		$instance['new_window']     = $new_instance['new_window'];
		$instance['widget_plugin']	= $new_instance['widget_plugin'];    
		$instance['logo_location']	= $new_instance['logo_location'];
    $instance['custom_x']       = $new_instance['custom_x'];
    $instance['custom_y']       = $new_instance['custom_y'];		
		return $instance;
	}

	function form( $instance ) {
		/* Set up some default widget settings. */
		$defaults = array(
				'title'           => __('My Logo Image'),
				'use_gravatar'		=> false,
				'gravatar_size'		=> '96',
				'image_url'       => '',
				'image_alt_text'	=> '',
				'image_link'      => '',
				'new_window'      => false,
				'widget_plugin'		=> false,      
				'logo_location'		=> 'Bottom-Right',
				'custom_x'        => '50%',
				'custom_y'        => '50%'				
			);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		
		<p>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['use_gravatar'], true ); ?> id="<?php echo $this->get_field_id( 'use_gravatar' ); ?>" name="<?php echo $this->get_field_name( 'use_gravatar' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'use_gravatar' ); ?>"><?php _e('Use your <a href="http://gravatar.com">Gravatar</a> image?'); ?></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'gravatar_size' ); ?>"><?php _e('Gravatar size in pixels:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'gravatar_size' ); ?>" name="<?php echo $this->get_field_name( 'gravatar_size' ); ?>" value="<?php echo $instance['gravatar_size']; ?>" style="width:100%;" />
			<em>NB: The Gravatar used is set as the first administrator by user ID.</em>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'image_url' ); ?>"><?php _e('URL of Image:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'image_url' ); ?>" name="<?php echo $this->get_field_name( 'image_url' ); ?>" value="<?php echo $instance['image_url']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'image_alt_text' ); ?>"><?php _e('ALT text of Image:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'image_alt_text' ); ?>" name="<?php echo $this->get_field_name( 'image_alt_text' ); ?>" value="<?php echo $instance['image_alt_text']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'image_link' ); ?>"><?php _e('URL to follow:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'image_link' ); ?>" name="<?php echo $this->get_field_name( 'image_link' ); ?>" value="<?php echo $instance['image_link']; ?>" />
		</p>
		
		<p>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['new_window'], true ); ?> id="<?php echo $this->get_field_id( 'new-window' ); ?>" name="<?php echo $this->get_field_name( 'new_window' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'new_window' ); ?>"><?php _e('Open "URL to follow" in new window?'); ?></label>
		</p>		

		<hr /> <!-- Separates functionality: Widget above - plugin below -->
		
		<p>
			<input class="checkbox" type="checkbox" <?php checked( (bool) $instance['widget_plugin'], true ); ?> id="<?php echo $this->get_field_id( 'widget_plugin' ); ?>" name="<?php echo $this->get_field_name( 'widget_plugin' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'widget_plugin' ); ?>"><?php _e('Use like a Plugin?'); ?></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'logo_location' ); ?>"><?php _e('Plugin Logo Location:'); ?></label> 
			<select id="<?php echo $this->get_field_id( 'logo_location' ); ?>" name="<?php echo $this->get_field_name( 'logo_location' ); ?>" class="widefat">
				<option <?php selected( 'Bottom-Right', $instance['logo_location'], true ); ?>>Bottom-Right</option>
				<option <?php selected( 'Bottom-Left', $instance['logo_location'], true ); ?>>Bottom-Left</option>
				<option <?php selected( 'Top-Right', $instance['logo_location'], true ); ?>>Top-Right</option>
				<option <?php selected( 'Top-Left', $instance['logo_location'], true ); ?>>Top-Left</option>
				<option <?php selected( 'Custom', $instance['logo_location'], true ); ?>>Custom</option>
			</select>
      <hr />
      <p>Custom position. Include type measure (px or em); or use percentage (%). </p>
			<label for="<?php echo $this->get_field_id( 'custom_x' ); ?>"><?php _e('x-axis:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'custom_x' ); ?>" name="<?php echo $this->get_field_name( 'custom_x' ); ?>" value="<?php echo $instance['custom_x']; ?>" />
			<label for="<?php echo $this->get_field_id( 'custom_y' ); ?>"><?php _e('y-axis:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'custom_y' ); ?>" name="<?php echo $this->get_field_name( 'custom_y' ); ?>" value="<?php echo $instance['custom_y']; ?>" />
		</p>

		<?php
	}
}
?>