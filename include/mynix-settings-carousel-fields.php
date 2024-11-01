<?php
/**
 * ################################################################################
 * WP MyCarousel
 * 
 * Copyright 2016 Eugen Mihailescu <eugenmihailescux@gmail.com>
 * 
 * This program is free software: you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any later
 * version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along with
 * this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * ################################################################################
 * 
 * Short description:
 * URL: http://mycarousel.mynixworld.info
 * 
 * Git revision information:
 * 
 * @version : 0.1-10 $
 * @commit  : 2757080da1745ce2d7d12fd377c87e3367ab56a3 $
 * @author  : eugenmihailescu <eugenmihailescux@gmail.com> $
 * @date    : Wed Dec 7 22:53:26 2016 +0100 $
 * @file    : mynix-settings-carousel-fields.php $
 * 
 * @id      : mynix-settings-carousel-fields.php | Wed Dec 7 22:53:26 2016 +0100 | eugenmihailescu <eugenmihailescux@gmail.com> $
*/

namespace MynixCarousel;

if ( ! defined( '\\ABSPATH' ) )
exit(); 
$size_sample = '320px|75%|auto|50em';
$size_pattern = '(\d+(px|em|%)|auto)';
$page_fields = array( 
'ID' => array( 
'title' => 'ID', 
'type' => 'label', 
'desc_tip' => __( 'The carousel\'s unique identifier', $this->plugin_id ) ), 
'name' => array( 
'title' => __( 'Name', $this->plugin_id ), 
'description' => __( 'Enter a name that describes well how you use this carousel', $this->plugin_id ), 
'desc_tip' => __( 'It allows you to identify it easier later', $this->plugin_id ), 
'type' => 'text', 
'default' => '', 
'placeholder' => __( 'short-descriptive-name', $this->plugin_id ), 
'custom_attributes' => array( 
'pattern' => '[\w\s\d\-]+', 
'title' => __( 'dash,letters,numbers,whitespaces', $this->plugin_id ), 
'required' => true ) ), 
'effect' => array( 
'title' => __( 'Carousel effect', $this->plugin_id ), 
'description' => __( 
'Select the transition effect that applies to all slides within carousel', 
$this->plugin_id ), 
'desc_tip' => __( 'This is the transition from one slide to the next slide', $this->plugin_id ), 
'type' => 'select', 
'options' => array( 
'fade' => __( 'Fade', $this->plugin_id ), 
'slide' => __( 'Slide right-to-left', $this->plugin_id ), 
'flipX' => __( 'Flip around X-axis', $this->plugin_id ), 
'flipY' => __( 'Flip around Y-axis', $this->plugin_id ) ), 
'default' => 'fade' ), 
'timer' => array( 
'title' => __( 'Autoplay (sec)', $this->plugin_id ), 
'description' => __( 'Specify the number of seconds between each slide', $this->plugin_id ), 
'desc_tip' => __( 'If zero then the autoplay is turned off', $this->plugin_id ), 
'type' => 'range', 
'default' => 10, 
'class' => 'input_number', 
'custom_attributes' => array( 'min' => 0, 'max' => 120 ) ), 
'progress' => array( 
'title' => __( 'Timer progress', $this->plugin_id ), 
'description' => __( 'Display a linear time progress between slides', $this->plugin_id ), 
'label' => __( 'Show timer progress', $this->plugin_id ), 
'type' => 'checkbox', 
'default' => 'yes' ), 
'progress_color' => array( 
'title' => __( 'Progress color', $this->plugin_id ), 
'description' => __( 
'The color of the thin progress indicator shown on the bottom of the slide', 
$this->plugin_id ), 
'desc_tip' => __( 'By default we apply an opacity of 0.5', $this->plugin_id ), 
'type' => 'color', 
'default' => '#000000' ), 
'shadow' => array( 
'title' => __( 'Shadowed container', $this->plugin_id ), 
'description' => __( 
'Whether you want or not to drop a thin shadow around the carousel container', 
$this->plugin_id ), 
'label' => __( 'Apply a shadow around container', $this->plugin_id ), 
'type' => 'checkbox', 
'default' => 'yes' ), 
'width' => array( 
'title' => __( 'Carousel width', $this->plugin_id ), 
'description' => sprintf( 
__( 'Enter the width that the carousel will have (eg: %s)', $this->plugin_id ), 
$size_sample ), 
'desc_tip' => __( 'This applies to the entire carousel frame', $this->plugin_id ), 
'type' => 'text', 
'placeholder' => $size_sample, 
'default' => 'auto', 
'class' => 'input_number', 
'custom_attributes' => array( 'pattern' => $size_pattern, 'required' => true ) ), 
'width_scale' => array( 
'title' => __( 'Width scaling', $this->plugin_id ), 
'description' => __( 
'How to handle the width on devices with screen width smaller than the carousel width', 
$this->plugin_id ), 
'desc_tip' => __( 'If not sure then choose Full width 100%', $this->plugin_id ), 
'type' => 'select', 
'options' => array( 
'stretch' => __( 'Stretched', $this->plugin_id ), 
'100' => __( 'Full width (100%)', $this->plugin_id ) ), 
'default' => '100' ), 
'height' => array( 
'title' => __( 'Carousel height', $this->plugin_id ), 
'description' => sprintf( 
__( 'Enter the height that the carousel will have (eg: %s)', $this->plugin_id ), 
$size_sample ), 
'desc_tip' => __( 'This applies to the entire carousel frame', $this->plugin_id ), 
'type' => 'text', 
'placeholder' => $size_sample, 
'default' => 'auto', 
'class' => 'input_number', 
'custom_attributes' => array( 'pattern' => $size_pattern, 'required' => true ) ), 
'background' => array( 
'title' => __( 'Background color', $this->plugin_id ), 
'description' => sprintf( 
__( 'The carousel background (any valid %s)', $this->plugin_id ), 
sprintf( 
'<a href="http://www.w3schools.com/cssref/css3_pr_background.asp" target="_blank">%s</a>', 
__( 'CSS background attribute', $this->plugin_id ) ) ), 
'type' => 'text', 
'default' => '#FFF' ), 
'navcolor' => array( 
'title' => __( 'Navigator color', $this->plugin_id ), 
'description' => __( 'Select the color for arrows and slide indicator', $this->plugin_id ), 
'desc_tip' => __( 'An opacity of 0.7 is applied for arrows, 0.3 for quick slide navigators', $this->plugin_id ), 
'type' => 'color', 
'default' => '#000000' ), 
'enabled' => array( 
'title' => __( 'Enabled', $this->plugin_id ), 
'description' => __( 'Whether this carousel may be used on frontend or not', $this->plugin_id ), 
'label' => __( 'Enabled this carousel on front-end', $this->plugin_id ), 
'desc_tip' => __( 'Disable carousels do not appear/run on front-end', $this->plugin_id ), 
'type' => 'checkbox', 
'default' => 'yes' ), 
'shortcode' => array( 
'title' => __( 'Shortcode', $this->plugin_id ), 
'type' => 'label', 
'description' => __( 'Insert the carousel in your post by using the shortcode above', $this->plugin_id ) ), 
'deleted' => array( 'type' => 'hidden', 'default' => false ) );
?>