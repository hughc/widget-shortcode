<?php
/*
Plugin Name:	Widget Shortcode
Description:	Outputs a widget using a simple shortcode.
Author:			Hassan Derakhshandeh
Version:		0.1
Author URI:		http://tween.ir/


		* 	Copyright (C) 2011  Hassan Derakhshandeh
		*	http://tween.ir/
		*	hassan.derakhshandeh@gmail.com

		This program is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License as published by
		the Free Software Foundation; either version 2 of the License, or
		(at your option) any later version.

		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_shortcode( 'widget', 'widget_shortcode' );
add_action( 'widgets_init', 'widget_shortcode_arbitrary_sidebar' );
add_action( 'in_widget_form', 'widget_shortcode_form', 10, 3 );

/**
 * output a widget using 'widget' shortcode.
 *
 * Requires the widget ID.
 * You can overwrite widget args: before_widget, before_title, after_title, after_widget
 *
 * @example [widget id="text-1"]
 * @since 0.1
 * @return string widget output
 */
function widget_shortcode( $atts, $content = null ) {
	global $_wp_sidebars_widgets, $wp_registered_widgets, $wp_registered_sidebars;

	if( ! isset( $atts['id'] ) || ! isset( $wp_registered_widgets[$atts['id']] ) )
		return;

	// get the widget instance options
	preg_match( '/(\d+)/', $atts['id'], $id );
	$options = get_option( $wp_registered_widgets[$atts['id']]['callback'][0]->option_name );
	$instance = $options[$id[0]];
	$class = get_class( $wp_registered_widgets[$atts['id']]['callback'][0] );

	// maybe the widget is removed or deregistered
	if( ! $instance || ! $class )
		return;

	$args = apply_filters( 'widget_shortcode_args', array(
		'before_widget'	=> ( isset( $atts['before_widget'] ) ) ? $atts['before_widget'] : '',
		'before_title'	=> ( isset( $atts['before_title'] ) ) ? $atts['before_title'] : '',
		'after_title'	=> ( isset( $atts['after_title'] ) ) ? $atts['after_title'] : '',
		'after_widget'	=> ( isset( $atts['after_widget'] ) ) ? $atts['after_widget'] : ''
	) );

	ob_start();
	the_widget( $class, $instance, $args );
	return ob_get_clean();
}

/**
 * Registers arbitrary widget area
 *
 * Although you can use the widget shortcode for any widget in any widget area,
 * you can use this arbitrary widget area for your widgets, since they don't show up
 * in the front-end.
 *
 * @since 0.1
 * @return void
 */
function widget_shortcode_arbitrary_sidebar() {
	register_sidebar( array(
		'name' => __( 'Arbitrary' ),
		'id' => 'arbitrary',
		'description'	=> __( 'This widget area can be used for [widget] shortcode.' ),
		'before_widget' => '',
		'after_widget'	=> '',
		'before_title'	=> '<h3 class="title">',
		'after_title'	=> '</h3>'
	) );
}

/**
 * Shows the shortcode for the widget
 *
 * @since 0.1
 * @return void
 */
function widget_shortcode_form( $widget, $return, $instance ) {
	echo '<p>' . __( 'Shortcode' ) . ': ' . ( ( $widget->number == '__i__' ) ? __( 'Please save this first.' ) : '<code>[widget id="'. $widget->id .'"]</code>' ) . '</p>';
}