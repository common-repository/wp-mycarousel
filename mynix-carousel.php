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
 * @file    : mynix-carousel.php $
 * 
 * @id      : mynix-carousel.php | Wed Dec 7 22:53:26 2016 +0100 | eugenmihailescu <eugenmihailescux@gmail.com> $
*/

namespace MynixCarousel;

/**
 * Plugin Name: WP MyCarousel
 * Plugin URI: http://mynixworld.info/shop/wp-mycarousel
 * Description: Create with ease customizable and responsive carousel sliders.
 * Version: 0.1-10
 * Author: Eugen Mihailescu, MyNixWorld
 * Author URI: http://mynixworld.info
 * Developer: Eugen Mihailescu
 * Developer URI: http://mynixworld.info
 *
 * Text Domain: wp-mycarousel
 * Domain Path: /i18n/languages/
 *
 * Copyright: © 2015 MyNixWorld.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @category Plugin
 * @author Eugen Mihailescu
 */
! defined( '\\ABSPATH' ) && exit();
include_once __DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'mynix-class-carousel.php';
include_once __DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'mynix-class-list-table.php';
class Mynix_Carousel {
private $carousel_table;
private $plugin_id;
private $settings;
function __construct() {
$this->settings = null;
$this->carousel_table = null;
$this->plugin_id = 'wp-mycarousel';
$this->load_carousel_settings();
add_shortcode( $this->plugin_id, array( $this, 'do_carousel' ) );
add_action( 'admin_menu', array( $this, 'carousel_menu' ) );
add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
add_filter( 'set-screen-option', array( $this, 'setting_screen_options' ), 10, 3 );
add_filter( 'admin_footer_text', array( $this, 'on_admin_get_footer_text' ) );
add_filter( 'mce_external_plugins', array( $this, 'mce_register' ) );
add_filter( 'mce_buttons', array( $this, 'mce_button' ), 0 );
}
function carousel_admin_notice( $message, $type = 'updated' ) {
echo '<div class="', $type, '"><p>', $message, '</p></div>';
}
private function _get_default_carousel_options() {
return array( 
'name' => '', 
'effect' => 'fade', 
'timer' => 10000, 
'progress' => false, 
'progress_color' => '#000000', 
'shadow' => false, 
'width' => 'auto', 
'width_scale' => '100', 
'height' => 'auto', 
'background' => '#FFFFFF', 
'navcolor' => '#000000', 
'enabled' => false, 
'shortcode' => '', 
'deleted' => false );
}
private function _get_default_slide_options() {
return array( 
'media' => '', 
'description' => '', 
'annotation' => '', 
'event' => '', 
'target' => '', 
'click' => '', 
'carousel_id' => - 1 );
}
function migrate_before_do_carousel( $data ) {
is_array( $data ) || $data = array();
isset( $data['slide'] ) || $data['slide'] = array();
foreach ( $this->_get_default_carousel_options() as $key => $value )
isset( $data[$key] ) || $data[$key] = $value;
foreach ( $data['slide'] as $slide_key => $slide_options )
foreach ( $this->_get_default_slide_options() as $key => $value )
isset( $slide_options[$key] ) || $data['slide'][$slide_key][$key] = $value;
return $data;
}
function do_carousel( $atts ) {
$attributes = array();
if ( isset( $atts['id'] ) ) {
if ( isset( $this->settings[$atts['id']] ) ) {
$data = $this->settings[$atts['id']];
if ( ! $this->is_true( $data['enabled'] ) || $this->is_true( $data['deleted'] ) )
return sprintf( '<!-- %s id=%d was disabled or deleted -->', $this->plugin_id, $atts['id'] );
$data = $this->migrate_before_do_carousel( $data );
isset( $data['slide'] ) || $data['slide'] = array();
$data = wp_parse_args( $data, $this->_get_default_carousel_options() );
foreach ( $data['slide'] as $slide_key => $slide )
$data['slide'][$slide_key] = wp_parse_args( $slide, $this->_get_default_slide_options() );
$attributes['effect'] = $data['effect'];
$attributes['width'] = $data['width'];
$attributes['width_scale'] = $data['width_scale'];
$attributes['height'] = $data['height'];
$attributes['background'] = $data['background'];
$attributes['navcolor'] = $data['navcolor'];
$attributes['timer'] = 1000 * $data['timer'];
$attributes['shadow'] = $this->is_true( $data['shadow'] );
$attributes['progress'] = $this->is_true( $data['progress'] );
$attributes['progress_color'] = $data['progress_color'];
$image = array();
$description = array();
$annotation = array();
$event = array();
$target = array();
$click = array();
if ( isset( $data['slide'] ) )
foreach ( $data['slide'] as $slide_data )
if ( ! empty( $slide_data ) ) {
$image[] = $slide_data['media'];
$description[] = $slide_data['description'];
$annotation[] = $slide_data['annotation'];
$event[] = $slide_data['event'];
$target[] = $slide_data['target'];
$click[] = $slide_data['click'];
}
$attributes['image'] = implode( ';', $image );
$attributes['description'] = implode( ';', $description );
$attributes['annotation'] = implode( ';', $annotation );
$attributes['event'] = implode( ';', $event );
$attributes['target'] = implode( ';', $target );
$attributes['click'] = implode( '$', $click );
}
} else
$attributes = $atts;
$carousel = new MynixCarousel( $this->plugin_id );
$carousel->generate_carousel_html( $attributes );
}
function setting_screen_options( $skip, $option, $value ) {
return $value;
}
function carousel_menu() {
$hook = add_menu_page( 
__( 'Mynix Carousel Settings', $this->plugin_id ),  
__( 'Mynix Carousel', $this->plugin_id ),  
'administrator',  
$this->plugin_id,  
array( $this, 'carousel_plugin_settings_page' ),  
'dashicons-slides' ); 
add_action( "load-$hook", array( $this, 'carousel_settings_options' ) );
}
function carousel_settings_options() {
$option = 'per_page';
$args = array( 'label' => 'Items', 'default' => 10, 'option' => 'carousels_per_page', 'option_name' => $option );
add_screen_option( $option, $args );
$class = __NAMESPACE__ . '\\Extended_WP_List_Table';
$this->carousel_table = new $class();
$this->carousel_table->plugin_id = $this->plugin_id;
$this->carousel_table->screen_options = $args;
}
function carousel_plugin_settings_page() {
if ( ! ( isset( $_REQUEST['page'] ) && $this->plugin_id == $_REQUEST['page'] ) ) {
return;
}
if ( isset( $_REQUEST['_wp_http_referer'] ) ) {
$level = preg_replace( '/.*[?&]level=([^&]*).*/', '$1', $_REQUEST['_wp_http_referer'] );
} else {
$level = isset( $_REQUEST['level'] ) ? $_REQUEST['level'] : false;
}
if ( isset( $_POST ) ) {
$data = array();
foreach ( $_POST as $key => $value ) {
if ( 0 === strpos( $key, $this->plugin_id . '_' ) )
$data[str_replace( $this->plugin_id . '_', '', $key )] = $value;
}
if ( ! empty( $data ) ) {
$saved = false;
$error_message = __( 'The data has not been saved for one reason or another.', $this->plugin_id );
switch ( $level ) {
case 'carousel' :
if ( $saved = $this->save_carousel( $data ) )
$message = sprintf( 
__( 'The carousel %s has been saved successfully', $this->plugin_id ), 
'<strong>' . sprintf( 
'%s(id=%s)', 
! empty( $data['name'] ) ? $data['name'] . ' ' : '', 
$saved ) . '</strong>' );
break;
case 'slide' :
if ( $saved = $this->save_slide( $data ) )
$message = sprintf( 
__( 'The slide %s has been saved successfully', $this->plugin_id ), 
'<strong>id=' . $saved . '</strong>' );
break;
}
$this->carousel_admin_notice( $saved ? $message : $error_message, $saved ? 'updated' : 'error' );
}
}
if ( isset( $_GET['action'] ) ) {
$carousel_id = isset( $_GET['carousel_id'] ) ? $_GET['carousel_id'] : false;
$slide_id = isset( $_GET['slide_id'] ) ? $_GET['slide_id'] : false;
$item_id = 'carousel' == $_GET['level'] ? $carousel_id : $slide_id;
if ( $item_id ) {
if ( 'edit' == $_GET['action'] ) {
return $this->carousel_edit( 
$item_id, 
sprintf( __( 'Edit %s', $this->plugin_id ), $level ), 
$level );
} elseif ( 'delete' == $_GET['action'] ) {
return $this->carousel_delete( $item_id, $level, $carousel_id );
} elseif ( 'restore' == $_GET['action'] ) {
return $this->carousel_restore( $item_id );
}
} elseif ( 'new' == $_GET['action'] ) {
return $this->carousel_new( 
sprintf( __( 'New %s', $this->plugin_id ), $level ), 
$level, 
'slide' == $level ? $carousel_id : false );
}
}
$this->carousel_display_table();
if ( false === get_option( $this->plugin_id ) ) {
include_once __DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'mynix-welcome.php';
}
}
function enqueue_admin_scripts() {
global $post;
$editing_post = is_object( $post ) && isset( $post->post_type ) && isset( $_REQUEST ) &&
isset( $_REQUEST['action'] ) && 'edit' == $_REQUEST['action'];
$editing_carousel = isset( $_REQUEST ) && isset( $_REQUEST['page'] ) && $this->plugin_id == $_REQUEST['page'];
if ( ! ( $editing_post || $editing_carousel ) )
return;
$suffix = '.min';
$handle = $this->plugin_id . '-admin';
$plugin_css = 'assets/css/' . $handle;
file_exists( plugin_dir_path( __FILE__ ) . $plugin_css . $suffix . '.css' ) && $plugin_css .= $suffix;
wp_enqueue_style( $handle, plugins_url( $plugin_css . '.css', __FILE__ ) );
$plugin_js = 'assets/js/' . $handle;
file_exists( plugin_dir_path( __FILE__ ) . $plugin_js . $suffix . '.js' ) && $plugin_js .= $suffix;
wp_enqueue_script( $handle, plugins_url( $plugin_js . '.js', __FILE__ ), array( 'jquery' ), true );
wp_localize_script( 
$handle, 
str_replace( '-', '_', $this->plugin_id ) . '_params', 
array( 'items' => $this->get_carousel_array() ) );
wp_enqueue_media();
$handle = $this->plugin_id . '-media';
$plugin_js = 'assets/js/' . $handle;
file_exists( plugin_dir_path( __FILE__ ) . $plugin_js . $suffix . '.js' ) && $plugin_js .= $suffix;
wp_enqueue_script( $handle, plugins_url( $plugin_js . '.js', __FILE__ ), array( 'jquery' ), true );
}
function carousel_display_table() {
$status = isset( $_GET['status'] ) ? $_GET['status'] : 'all';
$this->carousel_table->set_columns( 
array( 
'columns' => array( 
'name' => __( 'Name', $this->plugin_id ), 
'shortcode' => __( 'Shortcode', $this->plugin_id ), 
'effect' => __( 'Effect', $this->plugin_id ), 
'shadow' => __( 'Shadowed', $this->plugin_id ), 
'timer' => __( 'Autoplay', $this->plugin_id ), 
'progress' => __( 'Progress', $this->plugin_id ), 
'width' => __( 'Width', $this->plugin_id ), 
'height' => __( 'Height', $this->plugin_id ), 
'enabled' => __( 'Enabled', $this->plugin_id ), 
'deleted' => __( 'Deleted', $this->plugin_id ) ), 
'sortable' => array( 'name', 'effect' ), 
'searchable' => array( 'name' ) ) );
$this->carousel_table->filter_function = array( $this, 'search_callback' );
$this->carousel_table->count_function = array( $this, '$this->filter_data_count' );
$this->carousel_table->data = $this->filter_data( $this->settings, $status );
$this->carousel_table->set_filter_links( 
array( 
'all' => array( 
'class' => '', 
'count' => $this->filter_data_count( $this->settings, 'all' ), 
'title' => __( 'All', $this->plugin_id ) ), 
'trash' => array( 
'class' => '', 
'count' => $this->filter_data_count( $this->settings, 'trash' ), 
'title' => __( 'Trash', $this->plugin_id ) ) ) );
if ( 'trash' == $status )
$this->carousel_table->row_actions = array( 
'restore' => __( 'Restore', $this->plugin_id ), 
'delete' => __( 'Delete Permanently', $this->plugin_id ) );
$level = 'carousel';
$this->carousel_table->container_class = $this->plugin_id . '-carousel-table';
$this->carousel_table->generate_table_html( __( 'Carousels', $this->plugin_id ), $level );
}
function filter_data_count( $data, $status = 'all' ) {
$data = $this->filter_data( $data, $status );
return count( $data );
}
function filter_data( $data, $status = 'all' ) {
return array_filter( 
$data, 
function ( $item ) use(&$status ) {
return ( 'trash' == $status && $item['deleted'] ) || ( 'trash' != $status && ! $item['deleted'] );
} );
}
function search_callback( $obj, $search_text ) {
$status = isset( $_GET['status'] ) ? $_GET['status'] : 'all';
$searched_data = array_filter( 
$obj->data, 
function ( $item ) use(&$obj, &$search_text ) {
foreach ( $obj->searchable_columns as $colname ) {
if ( false !== stripos( $item[$colname], $search_text ) )
return true;
}
return false;
} );
return $this->filter_data( $searched_data, $status );
}
function carousel_delete( $id, $level, $parent = false ) {
if ( 'carousel' == $level ) {
if ( $this->settings[$id]['deleted'] )
unset( $this->settings[$id] );
else
$this->settings[$id]['deleted'] = true;
} elseif ( 'slide' == $level ) {
$carousel_id = $parent ? $parent : $this->get_carousel_id( $id );
if ( $carousel_id )
if ( isset( $this->settings[$carousel_id]['slide'] ) &&
isset( $this->settings[$carousel_id]['slide'][$id] ) )
unset( $this->settings[$carousel_id]['slide'][$id] );
}
$this->save_carousel_settings();
echo '<script>window.location.assign("', urldecode( base64_decode( $_GET['uri'] ) ), '")</script>';
}
function carousel_restore( $id ) {
$this->settings[$id]['deleted'] = false;
$this->save_carousel_settings();
}
function carousel_edit( $id, $title, $level, $parent = false ) {
$default_record = array( 'ID' => - 1 );
$parent && $default_record['carousel_id'] = $parent;
$admin_title = $title;
$level = isset( $_GET['level'] ) ? $_GET['level'] : '';
include_once __DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'mynix-settings-editor.php';
include_once __DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR .
sprintf( 'mynix-settings-%s-fields.php', $level );
$editor = new MynixSettingsEditor( $this->plugin_id );
if ( 'carousel' == $level ) {
if ( ! isset( $this->settings[$id] ) )
unset( $page_fields['shortcode'] );
$editor->settings = isset( $this->settings[$id] ) ? $this->settings[$id] : $default_record;
} elseif ( 'slide' == $level ) {
$carousel_id = $this->get_carousel_id( $id );
$editor->settings = isset( $this->settings[$carousel_id] ) &&
isset( $this->settings[$carousel_id]['slide'] ) && isset( $this->settings[$carousel_id]['slide'][$id] ) ? $this->settings[$carousel_id]['slide'][$id] : $default_record;
}
$editor->fields = $page_fields;
ob_start();
$editor->admin_options();
if ( 'carousel' == $level && isset( $this->settings[$id] ) ) {
$slides_title = __( 'Slides', $this->plugin_id );
$option = 'per_page';
$args = array( 
'label' => $slides_title, 
'default' => 10, 
'option' => 'slides_per_page', 
'option_name' => $option );
add_screen_option( $option, $args );
$class = __NAMESPACE__ . '\\Extended_WP_List_Table';
$carousel_slides_table = new $class();
$carousel_slides_table->plugin_id = $this->plugin_id;
$carousel_slides_table->screen_options = $args;
$carousel_slides_table->set_columns( 
array( 
'columns' => array( 
'media' => __( 'Media', $this->plugin_id ), 
'description' => __( 'Description', $this->plugin_id ), 
'annotation' => __( 'Annotation', $this->plugin_id ), 
'event' => __( 'Event', $this->plugin_id ), 
'target' => __( 'Target', $this->plugin_id ), 
'click' => __( 'Link', $this->plugin_id ) ), 
'sortable' => array( 'description', 'annotation', 'event' ) ) );
$carousel_slides_table->data = isset( $this->settings[$id]['slide'] ) ? $this->settings[$id]['slide'] : array();
$carousel_slides_table->container_class = $this->plugin_id . '-slide-table';
$carousel_slides_table->generate_table_html( $slides_title, 'slide' );
}
$admin_panel = preg_replace( '/(?=\<form)([\s\S]*)<form[^>]*>([\s\S]*?)<\/form>/', '$1$2', ob_get_clean() );
$admin_action = isset( $_GET['uri'] ) ? urldecode( base64_decode( $_GET['uri'] ) ) : '';
include_once __DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'mynix-settings-admin.php';
}
function carousel_new( $title, $level, $parent = false ) {
$this->carousel_edit( - 1, $title, $level, $parent );
}
function load_carousel_settings() {
$this->settings = get_option( $this->plugin_id );
false === $this->settings && $this->settings = array();
return true;
}
function save_carousel_settings() {
if ( ( $old_settings = get_option( $this->plugin_id ) ) !== false ) {
return update_option( $this->plugin_id, $this->settings );
}
return add_option( $this->plugin_id, $this->settings, null, 'no' );
}
function get_carousel_id( $slide_id ) {
$found = false;
foreach ( $this->settings as $carousel_id => $data )
if ( isset( $data['slide'] ) && isset( $data['slide'][$slide_id] ) ) {
$found = $carousel_id;
break;
}
return $found;
}
function save_carousel( $data ) {
$data = wp_parse_args( $data, $this->_get_default_carousel_options() );
$bool_cols = array( 'shadow', 'progress', 'enabled', 'deleted' );
if ( isset( $data['ID'] ) && ( - 1 == $data['ID'] || isset( $this->settings[$data['ID']] ) ) ) {
if ( - 1 == $data['ID'] ) {
if ( empty( $this->settings ) )
$this->settings[1] = array();
else
$this->settings[] = array();
end( $this->settings );
$data['ID'] = key( $this->settings );
}
$id = $data['ID'];
foreach ( $data as $key => $value ) {
in_array( $key, $bool_cols ) && $value = $this->is_true( $value );
$this->settings[$id][$key] = $value;
}
$this->settings[$id]['shortcode'] = sprintf( '[%s id="%d"]', $this->plugin_id, $id );
if ( $this->save_carousel_settings() )
return $id;
}
return false;
}
function save_slide( $data ) {
$bool_cols = array();
$data = wp_parse_args( $data, $this->_get_default_slide_options() );
$carousel_id = isset( $data['carousel_id'] ) ? $data['carousel_id'] : false;
$slide_id = isset( $data['ID'] ) && - 1 != $data['ID'] ? $data['ID'] : false;
$slide_id && isset( $slide_id ) && false === $carousel_id && $carousel_id = $this->get_carousel_id( $slide_id );
if ( - 1 == $data['ID'] || $carousel_id && $this->settings[$carousel_id] &&
isset( $this->settings[$carousel_id]['slide'] ) &&
isset( $this->settings[$carousel_id]['slide'][$slide_id] ) ) {
if ( - 1 == $data['ID'] ) {
if ( ! isset( $this->settings[$carousel_id]['slide'] ) ) {
$this->settings[$carousel_id]['slide'] = array( 1 => array() );
} else
$this->settings[$carousel_id]['slide'][] = array();
end( $this->settings[$carousel_id]['slide'] );
$slide_id = key( $this->settings[$carousel_id]['slide'] );
$data['ID'] = $slide_id;
}
foreach ( $data as $key => $value ) {
in_array( $key, $bool_cols ) && $value = $this->is_true( $value );
$this->settings[$carousel_id]['slide'][$slide_id][$key] = trim( stripslashes( $value ) );
}
if ( $this->save_carousel_settings() )
return $slide_id;
}
return false;
}
function is_true( $value ) {
return in_array( $value, array( '1', 'yes', 'true', 'on' ) );
}
function on_admin_get_footer_text( $text ) {
if ( ! ( isset( $_REQUEST['page'] ) && $this->plugin_id == $_REQUEST['page'] ) ) {
return $text;
}
$plugin_data = get_plugin_data( __FILE__ );
return sprintf( 
__( 'If you like %s please leave us a %s rating. A huge thank you from %s in advance!', $this->plugin_id ), 
$plugin_data['Name'], 
sprintf( 
'<a href="%s" target="_blank" class="rating-link" data-rated="Thanks :)">★★★★★</a>', 
'https://wordpress.org/support/view/plugin-reviews/' . $this->plugin_id . '?filter=5#postform' ), 
$plugin_data['Author'] );
}
function mce_register( $external_plugins ) {
$suffix = '.min';
$handle = $this->plugin_id . '-mce';
$plugin_js = 'assets/js/' . $handle;
file_exists( plugin_dir_path( __FILE__ ) . $plugin_js . $suffix . '.js' ) && $plugin_js .= $suffix;
$external_plugins[str_replace( '-', '_', $this->plugin_id )] = plugins_url( $plugin_js . '.js', __FILE__ );
return $external_plugins;
}
function mce_button( $mce_buttons ) {
array_push( $mce_buttons, str_replace( '-', '_', $this->plugin_id ) );
return $mce_buttons;
}
function get_carousel_array( $enabled = true ) {
$array = array();
foreach ( $this->settings as $key => $value )
if ( ( isset( $value['deleted'] ) && $this->is_true( $value['deleted'] ) ) ||
( $enabled && isset( $value['enabled'] ) && ! $this->is_true( $value['enabled'] ) ) )
continue;
else
$array[] = array( 'ID' => $value['ID'], 'name' => $value['name'] );
return $array;
}
}
new Mynix_Carousel();
?>