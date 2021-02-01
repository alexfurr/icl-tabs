<?php
/**
 * Plugin Name: Imperial Tabs
 * Plugin URI: https://beherit.pl/en/wordpress/simple-tabs-shortcodes/
 * Description: Forked from Simple Tabs plugin
 * Version: 0.1
 * Requires at least: 4.6
 * Requires PHP: 7.0
 * Author: Krzysztof Grochocki
 * Author URI: https://beherit.pl/
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: simple-tabs-shortcodes
 * Domain Path: /languages
 */




// Tabs wrapper shortcode
function sts_tabs_data_shortcode($atts, $content) {

    // Generate a unique ID
    $unique_id = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,10);
	// Create empty tabs data array
	global $sts_tabs_data;
	$sts_tabs_data = array();
	// Get tabs content
	$tabs_content = do_shortcode($content);
	// Start the tabs navigation
	$out = '<div class="accessibleResponsiveTabs" id="'.$unique_id.'"><ul class="tabs">';
	// Loop through the tabs data
    $current_tab=1;
	foreach($sts_tabs_data as $tabs => $tab) {
        //$active = $current_tab==1 ? 'class="active"' : '';
		$active = $tabs == 0 ? ' class="active"' : '';
		$out .= '<li rel="tab_'.$tab['id'].'" '.$active.'><a href="#'.$tab['id'].'">'.$tab['title'].'</a></li>';
        $current_tab++;
	}
	// Close the tabs navigation and add tabs content
	$out .= '</ul>';
    $out.='<div class="tabContainer">'.$tabs_content.'</div>';

    // Add the JS
    $out.='<script>
    jQuery(document).ready(function () {
    jQuery("#'.$unique_id.'").accessibleResponsiveTabs();
    });
    </script>';


	return $out;
}
add_shortcode('tabs', 'sts_tabs_data_shortcode');

// Tab item shortcode
function sts_tab_shortcode($atts, $content) {
	// Default attributes value
	$atts = shortcode_atts(
		array(
			'id' => '',
			'title' => __('Undefined title', 'simple-tabs-shortcodes')
		), $atts, 'tab');
	// Get tab ID
	$id = $atts['id'] ?: rawurldecode(sanitize_title($atts['title']));
	// Add tabs data to array
	global $sts_tabs_data;
	array_push($sts_tabs_data, array('id' => $id, 'title' => $atts['title']));
	// Make tab section
	$active = count($sts_tabs_data) == 1 ? ' active' : '';

    /*
	$out = '<section id="'.$id.'" class="tab'.$active.'">
		'.do_shortcode($content).'
	</section>';
    */

    $out = '<h2 class="accordionHeading" rel="tab_'.$id.'">'.$atts['title'].'</h2>
    <div id="tab_'.$id.'" class="tabContent">
    <h3 class="ghost contentHeading">'.do_shortcode($content).'</h3>
    '.do_shortcode($content).'
    </div>';




	return $out;
}
add_shortcode('tab', 'sts_tab_shortcode');
