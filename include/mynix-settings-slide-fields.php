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
 * @file    : mynix-settings-slide-fields.php $
 * 
 * @id      : mynix-settings-slide-fields.php | Wed Dec 7 22:53:26 2016 +0100 | eugenmihailescu <eugenmihailescux@gmail.com> $
*/

namespace MynixCarousel;

if ( ! defined( '\\ABSPATH' ) )
exit(); 
$page_fields = array( 
'ID' => array( 'title' => 'ID', 'type' => 'label' ), 
'media' => array( 
'title' => __( 'Media', $this->plugin_id ), 
'description' => __( 'Select the image for this slide', $this->plugin_id ), 
'desc_tip' => __( 'Select only images supported by the HTML [img] tag', $this->plugin_id ), 
'type' => 'media', 
'default' => '' ), 
'description' => array( 
'title' => __( 'Slide log description', $this->plugin_id ), 
'description' => __( 'Enter a description shown on hover when user clicks the `i` button', $this->plugin_id ), 
'desc_tip' => __( 'This should be maximum 1000 words or so', $this->plugin_id ), 
'type' => 'text', 
'default' => '', 
'placeholder' => 'title=description', 
'custom_attributes' => array( 
'pattern' => '([^=;]*=)?([^;]*)', 
'title' => __( 'no `;` or `=` in your title/description', $this->plugin_id ) ) ), 
'annotation' => array( 
'title' => __( 'Footer annotation', $this->plugin_id ), 
'description' => __( 'Enter a short annotation shown on footer at slide hover', $this->plugin_id ), 
'desc_tip' => __( 'No more than 10 words', $this->plugin_id ), 
'type' => 'text', 
'default' => '', 
'custom_attributes' => array( 
'pattern' => '[^;]*', 
'title' => __( 'no `;` in your annotation', $this->plugin_id ) ) ), 
'event' => array( 
'title' => __( 'Click type', $this->plugin_id ), 
'description' => __( 'Specify the event type on slide click', $this->plugin_id ), 
'desc_tip' => __( '', $this->plugin_id ), 
'type' => 'select', 
'options' => array( '' => __( 'none', $this->plugin_id ), 'javascript' => 'JavaScript', 'url' => 'URL' ), 
'default' => '' ), 
'target' => array( 
'title' => __( 'Link target', $this->plugin_id ), 
'description' => sprintf( 
__( 'Enter a valid HTML %s', $this->plugin_id ), 
'<a href="http://www.w3schools.com/tags/att_form_target.asp" target="_blank">' .
__( 'target attribute', $this->plugin_id ) . '</a>' ), 
'desc_tip' => implode( 
PHP_EOL, 
array( 
__( '_blank: in a new tab', $this->plugin_id ), 
__( '_self: the same frame', $this->plugin_id ), 
__( '_parent: in the parent frame', $this->plugin_id ), 
__( '_top: in the full body', $this->plugin_id ) ) ), 
'type' => 'text', 
'default' => '' ), 
'click' => array( 
'title' => __( 'JS code or URL', $this->plugin_id ), 
'description' => __( 'Enter the URL or the JavaScript code', $this->plugin_id ), 
'desc_tip' => implode( 
PHP_EOL, 
array( 
__( 'Example:', $this->plugin_id ), 
__( 'http://your-link.com', $this->plugin_id ), 
__( 'window.location.assign("url");', $this->plugin_id ) ) ), 
'type' => 'text', 
'default' => '', 
'placeholder' => __( 'http://your-link or {js-code}', $this->plugin_id ) ), 
'carousel_id' => array( 'type' => 'hidden', 'default' => - 1 ) );
?>