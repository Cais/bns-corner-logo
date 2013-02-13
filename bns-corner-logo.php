<?php
/*
Plugin Name: BNS Corner Logo
Plugin URI: http://buynowshop.com/plugins/bns-corner-logo/
Description: Widget to display a user selected image as a logo; or, used as a plugin that displays the image fixed in one of the four corners of the display.
Version: 1.8
Text Domain: bns-cl
Author: Edward Caissie
Author URI: http://edwardcaissie.com/
License: GNU General Public License v2
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

/**
 * BNS Corner Logo
 *
 * Widget to display a user selected image as a logo; or, used as a plugin that
 * displays the image fixed in one of the four corners of the display.
 *
 * @package     BNS_Corner_Logo
 * @link        http://buynowshop.com/plugins/bns-corner-logo/
 * @link        https://github.com/Cais/bns-corner-logo/
 * @link        http://wordpress.org/extend/plugins/bns-corner-logo/
 * @version     1.8
 * @author      Edward Caissie <edward.caissie@gmail.com>
 * @copyright   Copyright (c) 2009-2013, Edward Caissie
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License version 2, as published by the
 * Free Software Foundation.
 *
 * You may NOT assume that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to:
 *
 *      Free Software Foundation, Inc.
 *      51 Franklin St, Fifth Floor
 *      Boston, MA  02110-1301  USA
 *
 * The license for this software can also likely be found here:
 * http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @version 1.8
 * @date    February 12, 2013
 * Removed `load_textdomain` as redundant
 * Move all code into class structure
 */

class BNS_Corner_Logo_Widget extends WP_Widget {
    /**
     * BNS Corner Logo Widget
     * Used to extend WP_Widget class
     *
     * @package BNS_Corner_Logo
     * @since   0.1
     *
     * @uses    WP_Widget
     *
     * @return  void
     */
    function BNS_Corner_Logo_Widget() {
        /** Widget settings */
        $widget_ops = array( 'classname' => 'bns-corner-logo', 'description' => __( 'Widget to display a logo; or, used as a plugin displays image fixed in one of the four corners.', 'bns-cl' ) );
        /** Widget control settings */
        $control_ops = array( 'width' => 200, 'id_base' => 'bns-corner-logo' );
        /** Create the widget */
        $this->WP_Widget( 'bns-corner-logo', 'BNS Corner Logo', $widget_ops, $control_ops );

        /**
         * Check installed WordPress version for compatibility
         *
         * @package     BNS_Corner_Logo
         * @since       1.0
         * @internal    Version 3.0 being used in reference to home_url()
         *
         * @uses        (global) wp_version
         *
         * @version     1.6
         * @date        November 18, 2011.
         * Re-written to be i18n compatible
         */
        global $wp_version;
        $exit_message = __( 'BNS Corner Logo requires WordPress version 3.0 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please Update!</a>', 'bns-cl' );
        if ( version_compare( $wp_version, "3.0", "<" ) ) {
            exit( $exit_message );
        } /** End if = version compare */

        /** Add widget */
        add_action( 'widgets_init', array( $this, 'load_bnscl_widget' ) );

        /** Add scripts and style */
        add_action('wp_enqueue_scripts', array( $this, 'BNS_Corner_Logo_Scripts_and_Styles') );

    } /** End function - bns corner logo widget */


    /**
     * Override widget method of class WP_Widget
     *
     * @param   $args
     * @param   $instance
     *
     * @uses    apply_filters
     * @uses    get_avatar
     * @uses    get_userdata
     *
     * @return  void
     */
    function widget( $args, $instance ) {
        extract( $args );
        /** User-selected settings */
        $title              = apply_filters('widget_title', $instance['title'] );
        $use_gravatar       = $instance['use_gravatar'];
        $gravatar_user_id   = $instance['gravatar_user_id'];
        $gravatar_size      = $instance['gravatar_size'];
        $image_url          = $instance['image_url'];
        $image_alt_text	    = $instance['image_alt_text'];
        $image_link         = $instance['image_link'];
        $new_window         = $instance['new_window'];
        $widget_plugin	    = $instance['widget_plugin'];
        $logo_location	    = $instance['logo_location'];

        if ( ! $widget_plugin ) {
            /** @var $before_widget string - define by theme */
            echo $before_widget;

            /* Title of widget (before and after defined by themes). */
            if ( $title ) {
                /**
                 * @var $before_title   string - defined by theme
                 * @var $after_title    string - defined by theme
                 */
                echo $before_title . $title . $after_title;
            } /** End if - title */

            /** Display image based on widget settings */ ?>
            <div class="bns-logo">
                <a <?php if ( $new_window ) echo 'target="_blank"'; ?> href="<?php echo $image_link; ?>">
                    <!-- Use FIRST Admin gravatar user ID = 1 as default -->
                    <?php if ( $use_gravatar ) {
                        $user_details = get_userdata( $gravatar_user_id );
                        /** @noinspection PhpUndefinedFieldInspection */
                        $user_email = $user_details->user_email;
                        echo get_avatar( $user_email, $gravatar_size);
                    } else { ?>
                        <img style="" alt="<?php echo $image_alt_text; ?>" src="<?php echo $image_url;?>" />
                    <?php } ?>
                </a>
            </div> <!-- .bns-logo -->
        <?php
        } else {
            if ( $logo_location == "Bottom-Right" ) {
                $logo_position = 'fixed-bottom-right';
            } elseif ( $logo_location == "Bottom-Left" ) {
                $logo_position = 'fixed-bottom-left';
            } elseif ( $logo_location == "Top-Right" ) {
                $logo_position = 'fixed-top-right';
            } elseif ( $logo_location == "Top-Left" ) {
                $logo_position = 'fixed-top-left';
            } /** End if - logo location */ ?>
            <div class="bns-logo <?php echo $logo_position; ?>">
                <a <?php if ( $new_window ) echo 'target="_blank"'; ?> href="<?php echo $image_link; ?>">
                    <!-- Use FIRST Admin gravatar user ID = 1 as default -->
                    <?php if ( $use_gravatar ) {
                        $user_details = get_userdata( $gravatar_user_id );
                        /** @noinspection PhpUndefinedFieldInspection */
                        $user_email = $user_details->user_email;
                        echo get_avatar( $user_email, $gravatar_size);
                    } else { ?>
                        <img style="" alt="<?php echo $image_alt_text; ?>" src="<?php echo $image_url;?>" />
                    <?php } /** End if - use gravatar */ ?>
                </a>
            </div> <!-- .bns-logo -->
        <?php
        } /** End if - not widget plugin */
        /** End - Display image based on widget settings */

        /** After widget (defined by themes) */
        if ( ! $widget_plugin ) {
            /** @var $after_widget   string - defined by theme */
            echo $after_widget;
        } /** End if - not widget plugin */

    } /** End function - widget */


    /**
     * Override update method of class WP_Widget
     *
     * @param   $new_instance
     * @param   $old_instance
     *
     * @return  array - widget options and settings
     */
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        /** Strip tags (if needed) and update the widget settings */
        $instance['title']              = strip_tags( $new_instance['title'] );
        $instance['use_gravatar']       = $new_instance['use_gravatar'];
        $instance['gravatar_user_id']   = $new_instance['gravatar_user_id'];
        $instance['gravatar_size']	    = $new_instance['gravatar_size'];
        $instance['image_url']          = strip_tags( $new_instance['image_url'] );
        $instance['image_alt_text']	    = strip_tags( $new_instance['image_alt_text'] );
        $instance['image_link']         = strip_tags( $new_instance['image_link'] );
        $instance['new_window']         = $new_instance['new_window'];
        $instance['widget_plugin']	    = $new_instance['widget_plugin'];
        $instance['logo_location']	    = $new_instance['logo_location'];
        return $instance;
    } /** End function - update */


    /**
     * Overrides form method of class WP_Widget
     *
     * @package BNS_Corner_Logo
     * @since   1.0
     *
     * @param   $instance
     *
     * @uses    checked
     * @uses    get_field_id
     * @uses    get_field_name
     * @uses    selected
     *
     * @return  void
     *
     * @version 1.7
     * @date    November 21, 2012
     * Added i18n support to position drop-down in widget control panel
     */
    function form( $instance ) {
        /** Set up some default widget settings */
        $defaults = array( 'title'              => __( 'My Logo Image', 'bns-cl' ),
                           'use_gravatar'       => false,
                           'gravatar_user_id'   => '1',
                           'gravatar_size'      => '96',
                           'image_url'          => '',
                           'image_alt_text'     => '',
                           'image_link'         => '',
                           'new_window'         => false,
                           'widget_plugin'      => false,
                           'logo_location'      => 'Bottom-Right'
        );
        $instance = wp_parse_args( ( array ) $instance, $defaults ); ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'bns-cl' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked( ( bool ) $instance['use_gravatar'], true ); ?> id="<?php echo $this->get_field_id( 'use_gravatar' ); ?>" name="<?php echo $this->get_field_name( 'use_gravatar' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'use_gravatar' ); ?>"><?php printf( __( 'Use your %1$s image?', 'bns-cl' ), '<a href="http://gravatar.com">Gravatar</a>' ); ?></label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'gravatar_user_id' ); ?>"><?php _e( 'Set Gravatar by User ID Number', 'bns-cl' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'gravatar_user_id' ); ?>" name="<?php echo $this->get_field_name( 'gravatar_user_id' ); ?>" value="<?php echo $instance['gravatar_user_id']; ?>" style="width:100%;" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'gravatar_size' ); ?>"><?php _e( 'Gravatar size in pixels:', 'bns-cl' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'gravatar_size' ); ?>" name="<?php echo $this->get_field_name( 'gravatar_size' ); ?>" value="<?php echo $instance['gravatar_size']; ?>" style="width:100%;" />
        </p>

        <hr /> <!-- Aesthetic separator -->

        <p>
            <label for="<?php echo $this->get_field_id( 'image_url' ); ?>"><?php _e( 'URL of Image:', 'bns-cl' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'image_url' ); ?>" name="<?php echo $this->get_field_name( 'image_url' ); ?>" value="<?php echo $instance['image_url']; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'image_alt_text' ); ?>"><?php _e( 'ALT text of Image:', 'bns-cl' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'image_alt_text' ); ?>" name="<?php echo $this->get_field_name( 'image_alt_text' ); ?>" value="<?php echo $instance['image_alt_text']; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'image_link' ); ?>"><?php _e( 'URL to follow:', 'bns-cl' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'image_link' ); ?>" name="<?php echo $this->get_field_name( 'image_link' ); ?>" value="<?php echo $instance['image_link']; ?>" />
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked( ( bool ) $instance['new_window'], true ); ?> id="<?php echo $this->get_field_id( 'new-window' ); ?>" name="<?php echo $this->get_field_name( 'new_window' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'new_window' ); ?>"><?php _e( 'Open "URL to follow" in new window?', 'bns-cl' ); ?></label>
        </p>

        <hr /> <!-- Separates functionality: Widget above - plugin below -->

        <p>
            <input class="checkbox" type="checkbox" <?php checked( ( bool ) $instance['widget_plugin'], true ); ?> id="<?php echo $this->get_field_id( 'widget_plugin' ); ?>" name="<?php echo $this->get_field_name( 'widget_plugin' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'widget_plugin' ); ?>"><?php _e( 'Use like a Plugin?', 'bns-cl' ); ?></label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'logo_location' ); ?>"><?php _e( 'Plugin Logo Location:', 'bns-cl' ); ?></label>
            <select id="<?php echo $this->get_field_id( 'logo_location' ); ?>" name="<?php echo $this->get_field_name( 'logo_location' ); ?>" class="widefat">
                <option <?php selected( __( 'Bottom-Right', 'bns-cl' ), $instance['logo_location'], true ); ?>><?php _e( 'Bottom-Right', 'bns-cl'); ?></option>
                <option <?php selected( __( 'Bottom-Left', 'bns-cl' ), $instance['logo_location'], true ); ?>><?php _e( 'Bottom-Left', 'bns-cl' ); ?></option>
                <option <?php selected( __( 'Top-Right', 'bns-cl' ), $instance['logo_location'], true ); ?>><?php  _e( 'Top-Right' , 'bns-cl' ); ?></option>
                <option <?php selected( __( 'Top-Left', 'bns-cl' ), $instance['logo_location'], true ); ?>><?php _e( 'Top-Left', 'bns-cl' ); ?></option>
            </select>
        </p>
    <?php
    } /** End function - form */


    /**
     * Enqueue Plugin Scripts and Styles
     *
     * Adds plugin stylesheet and allows for custom stylesheet to be added by end-user.
     *
     * @package BNS_Corner_Logo
     * @since   1.5
     *
     * @uses    get_plugin_data
     * @uses    plugin_dir_url
     * @uses    plugin_dir_path
     * @uses    wp_enqueue_style
     *
     * @version 1.6.2.1
     * @date    August 14, 2012
     * Fixed undefined index
     * Use the plugin version data for the version number in `wp_enqueue_style`
     * rather than hard-coding a number
     *
     * @version 1.7
     * @date    November 21, 2012
     * Enqueued JavaScript 'bns-corner-logo-scripts.js'
     * Enqueued JavaScript 'bns-corner-logo-custom-scripts.css' if it exists
     */
    function BNS_Corner_Logo_Scripts_and_Styles() {
        /** Call the wp-admin plugin code */
        require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
        /** @var $bnscl_data - holds the plugin header data */
        $bnscl_data = get_plugin_data( __FILE__ );

        /** Scripts */
        wp_enqueue_script( 'BNS-Corner-Logo-Script', plugin_dir_url( __FILE__ ) . 'bns-corner-logo-scripts.js', array( 'jquery' ), $bnscl_data['Version'], 'screen' );
        /** Only enqueue if available */
        if ( is_readable( plugin_dir_path( __FILE__ ) . 'bns-corner-logo-custom-scripts.css' ) ) {
            wp_enqueue_style( 'BNS-Corner-Logo-Custom-Script', plugin_dir_url( __FILE__ ) . 'bns-corner-logo-custom-scripts.css', array( 'jquery' ), $bnscl_data['Version'], 'screen' );
        } /** End if - is readable */

        /** Styles */
        wp_enqueue_style( 'BNS-Corner-Logo-Style', plugin_dir_url( __FILE__ ) . 'bns-corner-logo-style.css', array(), $bnscl_data['Version'], 'screen' );
        /** Only enqueue if available */
        if ( is_readable( plugin_dir_path( __FILE__ ) . 'bns-corner-logo-custom-style.css' ) ) {
            wp_enqueue_style( 'BNS-Corner-Logo-Custom-Style', plugin_dir_url( __FILE__ ) . 'bns-corner-logo-custom-style.css', array(), $bnscl_data['Version'], 'screen' );
        } /** End if  - is readable */

    } /** End function - bns corner logo scripts and styles */


    /**
     * Register widget
     *
     * @uses    register_widget
     *
     * @return  void
     */
    function load_bnscl_widget() {
        register_widget( 'BNS_Corner_Logo_Widget' );
    } /** End function - load bnscl widget */

} /** End class */


/** @var $bnscl - instantiate the class */
$bnscl = new BNS_Corner_Logo_Widget();