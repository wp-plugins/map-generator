<?php
/*
Plugin Name: Map-Generator
Plugin URI: http://map-generator.net/de
Description: This Plugin adds custom maps from map-generator.net to your blog. You can now add multible marker, change the Marker apperance, size, view the map as StreetView and much more.
Version: 1.1
Author: Map-Generator.net
Author Email: mailus@map-generator.net
License:

  Copyright 2012 map-generator.net (mailus@map-generator.net)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 3, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

  
*/


class MapGenerator {

	/*--------------------------------------------*
	 * Constants
	 *--------------------------------------------*/
	const name = 'MapGenerator';
	const slug = 'mapgenerator';
	
	/**
	 * Constructor
	 */
	function __construct() {
		//Hook up to the init action
		add_action( 'init', array( &$this, 'init_mapgenerator' ) );
	}
  
	/**
	 * Runs when the plugin is activated
	 */  
	function install_mapgenerator() {
		// do not generate any output here
	}
  
	/**
	 * Runs when the plugin is initialized
	 */
	function init_mapgenerator() {
		// Load JavaScript and stylesheets
		$this->register_scripts_and_styles();

		// Register the shortcode [MAP]
		add_shortcode('mapgen', array( &$this, 'render_shortcode' ));
   
	}

	function render_shortcode($atts) {
		// Extract the attributes
		extract(shortcode_atts(array(
			'url' => 'http://map-generator.net/de/maps/12000', //default url
			'width' => '640px', // default width
			'height' => '350px' // default height
			), $atts));
		// you can now access the attribute values using $attr1 and $attr2
		
		// def regex for url
		$reg_exp = '/\d+/';
		
		// extract the url from the link
		preg_match($reg_exp, $url, $map_gen_id, NULL, 0);
		
		// build include tag
		$tag = "<div id=\"map_canvas_custom_".$map_gen_id[0]."\" style=\"width:".$width."; height:".$height."\" ></div><script type=\"text/javascript\">(function(d, t) {var g = d.createElement(t),s = d.getElementsByTagName(t)[0];g.src = \"http://map-generator.net/de/maps/".$map_gen_id[0].".js\";s.parentNode.insertBefore(g, s);}(document, \"script\"));</script>";
		
		return $tag;
	}

	/**
	 * Registers and enqueues stylesheets for the administration panel and the
	 * public facing site.
	 */
	private function register_scripts_and_styles() {
		if ( is_admin() ) {
		    add_filter( 'mce_buttons', array( $this, 'filter_mce_button' ) );
        add_filter( 'mce_external_plugins', array( $this, 'filter_mce_plugin' ) );

		} else {

		} // end if/else
	} // end register_scripts_and_styles
	
	function filter_mce_button( $buttons ) {
      // add a separation before our button, here our button's id is &quot;mygallery_button&quot;
      array_push( $buttons, '|', 'mapgen_button' );
      return $buttons;
  }
   
  function filter_mce_plugin( $plugins ) {
      // this plugin file will work the magic of our button
      $plugins['mapgen'] = plugin_dir_url( __FILE__ ) . 'js/map-generator.js';
      return $plugins;
  }
	
  
} // end class
new MapGenerator();

?>