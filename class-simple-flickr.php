<?php
/**
* Simple Flickr.
*
* @package   Simple_Flickr_Widget
* @author    Constantine Kiriaze, hello@kiriaze.com
* @license   GPL-2.0+
* @link      http://getsimple.io
* @copyright 2013 Constantine Kiriaze
*
*
*/

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Simple_Flickr_Widget' ) ) :

    // ADD FUNTION TO WIDGETS_INIT
    add_action( 'widgets_init', 'simple_flickr' );

    // REGISTER WIDGET
    function simple_flickr() {
        register_widget( 'Simple_Flickr_Widget' );
    }

    // WIDGET CLASS
    class Simple_Flickr_Widget extends WP_Widget {


    /*--------------------------------------------------------------------*/
    /*  WIDGET SETUP
    /*--------------------------------------------------------------------*/
    public function __construct() {
        parent::__construct(
            'simple_flickr', // BASE ID
            'SimpleFlicks', // NAME
            array( 'description' => __( 'A widget that displays your Flickr photos', 'simple' ), )
        );
    }


    /*--------------------------------------------------------------------*/
    /*  DISPLAY WIDGET
    /*--------------------------------------------------------------------*/
    function widget( $args, $instance ) {
        extract( $args );

        // WIDGET VARIABLES
        $title      = apply_filters('widget_title', $instance['title'] );
        $flickrID   = $instance['flickrID'];
        $type       = $instance['type'];
        $display    = $instance['display'];

        // BEFORE WIDGET
        echo $before_widget;

        if ( $title ) echo $before_title . $title . $after_title;

        ?>

        <div class="flickr-image-wrapper">

            <script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=8&amp;display=<?php echo $display ?>&amp;size=s&amp;layout=x&amp;source=<?php echo $type ?>&amp;<?php echo $type ?>=<?php echo $flickrID ?>"></script>

        </div>

        <?php

        // AFTER WIDGET
        echo $after_widget;
    }


    /*--------------------------------------------------------------------*/
    /*  UPDATE WIDGET
    /*--------------------------------------------------------------------*/
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        // STRIP TAGS TO REMOVE HTML - IMPORTANT FOR TEXT IMPUTS
        $instance['title']      = strip_tags( $new_instance['title'] );
        $instance['flickrID']   = strip_tags( $new_instance['flickrID'] );

        // NO NEED TO STRIP TAGS
        $instance['type']       = $new_instance['type'];
        $instance['display']    = $new_instance['display'];
        $instance['desc']       = $new_instance['desc'];

        return $instance;
    }


    /*--------------------------------------------------------------------*/
    /*  WIDGET SETTINGS (FRONT END PANEL)
    /*--------------------------------------------------------------------*/
    function form( $instance ) {

        // WIDGET DEFAULTS
        $defaults = array(
            'title'     => 'Flickr Widget',
            'flickrID'  => '31653563@N02',
            'type'      => 'user',
            'display'   => 'latest',
        );

        $instance = wp_parse_args( (array) $instance, $defaults ); ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'simple') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'flickrID' ); ?>"><?php _e('Flickr ID:', 'simple') ?> (<a href="http://idgettr.com/">idGettr</a>)</label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'flickrID' ); ?>" name="<?php echo $this->get_field_name( 'flickrID' ); ?>" value="<?php echo $instance['flickrID']; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e('Type (user or group):', 'simple') ?></label>
            <select id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>" class="widefat">
                <option <?php if ( 'user' == $instance['type'] ) echo 'selected="selected"'; ?>>user</option>
                <option <?php if ( 'group' == $instance['type'] ) echo 'selected="selected"'; ?>>group</option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'display' ); ?>"><?php _e('Display (random or latest):', 'simple') ?></label>
            <select id="<?php echo $this->get_field_id( 'display' ); ?>" name="<?php echo $this->get_field_name( 'display' ); ?>" class="widefat">
                <option <?php if ( 'random' == $instance['display'] ) echo 'selected="selected"'; ?>>random</option>
                <option <?php if ( 'latest' == $instance['display'] ) echo 'selected="selected"'; ?>>latest</option>
            </select>
        </p>


        <?php

        } // END FORM

    } // END CLASS

    if ( !function_exists('simple_flickr_shortcode') ) {

        function simple_flickr_shortcode( $atts ) {
            extract(shortcode_atts(array(
                'before'    => '',
                'after'     => '',
                'wrapper'   => 'ul',
                'class'     => '',
            ), $atts));

            $output = '';

            $output .= $before;

            $output .= '<'.$wrapper.' class="'.$class.'">';

            // $output .= Simple_Flickr_Widget::display_cached_content();

            $output .= '</'.$wrapper.'>';

            $output .= $after;

            return $output;
        }
        add_shortcode('flickr', 'simple_flickr_shortcode');

    }

endif;