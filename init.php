<?php
/*
Plugin Name:	Sidebars Plus
Description:	Give you 4 sidebars that show up before and after your content on single pages, and above and below your blog posts.
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

class Sidebars_Plus {

	private $textdomain;

	function Sidebars_Plus() {
		add_action( 'widgets_init', array( &$this, 'define_sidebars' ) );
		add_action( 'loop_start', array( &$this, 'before_loop' ) );
		add_action( 'loop_end', array( &$this, 'after_loop' ) );
		add_filter( 'the_content', array( &$this, 'content_sidebars' ) );
	}

	function display( $area ) {
		if( ! is_active_sidebar( $area ) )
			return '';

		echo '<div id="'. $area . '">';
		dynamic_sidebar( $area );
		echo '</div>';
	}

	function before_loop() {
		if( is_main_query() ) {
			remove_action( 'loop_start', array( &$this, 'before_loop' ) );
			$this->display( 'before-loop' );
		}
	}

	function after_loop() {
		if( is_main_query() ) {
			remove_action( 'loop_end', array( &$this, 'after_loop' ) ); /* prevent from sinking into an endless loop */
			$this->display( 'after-loop' );
		}
	}

	function content_sidebars( $content ) {
		if( ! is_singular() || ! is_main_query() )
			return $content;
		ob_start();
		$this->display( 'above-content' );
		echo $content;
		$this->display( 'below-content' );
		return ob_get_clean();
	}

	function define_sidebars() {
		register_sidebar( array(
			'id'	=> 'above-content',
			'name'	=> __( 'Above Content', $this->textdomain ),
			'before_widget'	=> '<div id="%1$s" class="widget-container %2$s">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<h3 class="widget-title">',
			'after_title'	=> '</h3>',
		) );
		register_sidebar( array(
			'id'	=> 'below-content',
			'name'	=> __( 'Below Content', $this->textdomain ),
			'before_widget'	=> '<div id="%1$s" class="widget-container %2$s">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<h3 class="widget-title">',
			'after_title'	=> '</h3>',
		) );
		register_sidebar( array(
			'id'	=> 'before-loop',
			'name'	=> __( 'Before Loop', $this->textdomain ),
			'before_widget'	=> '<div id="%1$s" class="widget-container %2$s">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<h3 class="widget-title">',
			'after_title'	=> '</h3>',
		) );
		register_sidebar( array(
			'id'	=> 'after-loop',
			'name'	=> __( 'After Loop', $this->textdomain ),
			'before_widget'	=> '<div id="%1$s" class="widget-container %2$s">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<h3 class="widget-title">',
			'after_title'	=> '</h3>',
		) );
	}
}
new Sidebars_Plus;