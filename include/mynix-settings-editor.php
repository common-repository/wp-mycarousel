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
 * @file    : mynix-settings-editor.php $
 * 
 * @id      : mynix-settings-editor.php | Wed Dec 7 22:53:26 2016 +0100 | eugenmihailescu <eugenmihailescux@gmail.com> $
*/

namespace MynixCarousel;

if ( ! defined( '\\ABSPATH' ) )
exit(); 
if ( ! class_exists( __NAMESPACE__ . '\\MynixSettingsEditor' ) ) {
class MynixSettingsEditor {
public $plugin_id;
public $settings;
public $fields;
public $title;
public $description;
private function _wc_format_localized_decimal( $value ) {
if ( function_exists( '\\wc_format_localized_decimal' ) )
return wc_format_localized_decimal( $value );
$locale = localeconv();
return str_replace( '.', $locale['decimal_point'], strval( $value ) );
}
public function __construct( $plugin_id = '' ) {
$this->plugin_id = $plugin_id;
}
private function _sanitize_tooltip( $var ) {
return htmlspecialchars( 
wp_kses( 
html_entity_decode( $var ), 
array( 
'br' => array(), 
'em' => array(), 
'strong' => array(), 
'small' => array(), 
'span' => array(), 
'ul' => array(), 
'li' => array(), 
'ol' => array(), 
'p' => array() ) ) );
}
private function get_custom_attribute_html( $data ) {
$custom_attributes = array();
if ( ! empty( $data['custom_attributes'] ) && is_array( $data['custom_attributes'] ) ) {
foreach ( $data['custom_attributes'] as $attribute => $attribute_value ) {
$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
}
}
return implode( ' ', $custom_attributes );
}
public function format_settings( $value ) {
return is_array( $value ) ? $value : $value;
}
public function init_settings() {
$this->settings = get_option( $this->get_field_key( 'settings' ), null );
if ( ! $this->settings || ! is_array( $this->settings ) ) {
$this->settings = array();
foreach ( $this->fields as $k => $v ) {
$this->settings[$k] = isset( $v['default'] ) ? $v['default'] : '';
}
}
if ( ! empty( $this->settings ) && is_array( $this->settings ) ) {
$this->settings = array_map( array( $this, 'format_settings' ), $this->settings );
}
}
private function get_option( $key, $empty_value = null ) {
if ( empty( $this->settings ) ) {
$this->init_settings();
}
if ( ! isset( $this->settings[$key] ) ) {
$this->settings[$key] = isset( $this->fields[$key]['default'] ) ? $this->fields[$key]['default'] : '';
}
if ( ! is_null( $empty_value ) && empty( $this->settings[$key] ) && '' === $this->settings[$key] ) {
$this->settings[$key] = $empty_value;
}
return trim( stripslashes( $this->settings[$key] ) );
}
public function plugin_url() {
return untrailingslashit( plugins_url( '/', __DIR__ ) );
}
public function plugin_path() {
return untrailingslashit( plugin_dir_path( __DIR__ ) );
}
public function get_field_key( $key ) {
return $this->plugin_id . '_' . $key;
}
public function get_tooltip_html( $data ) {
if ( $data['desc_tip'] === true ) {
$tip = $data['description'];
} elseif ( ! empty( $data['desc_tip'] ) ) {
$tip = $data['desc_tip'];
} else {
$tip = '';
}
return $tip ? '<img class="help_tip" data-tip="' . $this->_sanitize_tooltip( $tip ) . '" src="' .
$this->plugin_url() . '/assets/img/help.png" height="16" width="16" />' : '';
}
public function generate_text_html( $key, $data ) {
$field = $this->get_field_key( $key );
$defaults = array( 
'title' => '', 
'disabled' => false, 
'class' => '', 
'css' => '', 
'placeholder' => '', 
'type' => 'text', 
'desc_tip' => false, 
'description' => '', 
'custom_attributes' => array() );
$data = wp_parse_args( $data, $defaults );
ob_start();
?>
<tr valign="top">
<th scope="row" class="titledesc"><label
for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
<?php echo $this->get_tooltip_html( $data ); ?>
</th>
<td class="forminp">
<fieldset>
<legend class="screen-reader-text">
<span><?php echo wp_kses_post( $data['title'] ); ?></span>
</legend>
<input class="input-text regular-input <?php echo esc_attr( $data['class'] ); ?>" type="<?php echo esc_attr( $data['type'] ); ?>" name="<?php echo esc_attr( $field ); ?>" id="<?php echo esc_attr( $field ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" value="<?php echo esc_attr( $this->get_option( $key ) ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); ?> />
<?php echo $this->get_description_html( $data ); ?>
</fieldset>
</td>
</tr>
<?php
return ob_get_clean();
}
public function get_description_html( $data ) {
if ( $data['desc_tip'] === true ) {
$description = '';
} elseif ( ! empty( $data['desc_tip'] ) ) {
$description = $data['description'];
} elseif ( ! empty( $data['description'] ) ) {
$description = $data['description'];
} else {
$description = '';
}
return $description ? '<p class="description">' . wp_kses_post( $description ) . '</p>' . "\n" : '';
}
public function generate_checkbox_html( $key, $data ) {
$field = $this->get_field_key( $key );
$defaults = array( 
'title' => '', 
'label' => '', 
'disabled' => false, 
'class' => '', 
'css' => '', 
'type' => 'text', 
'desc_tip' => false, 
'description' => '', 
'custom_attributes' => array() );
$data = wp_parse_args( $data, $defaults );
if ( ! $data['label'] ) {
$data['label'] = $data['title'];
}
ob_start();
?>
<tr valign="top">
<th scope="row" class="titledesc"><label
for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
<?php echo $this->get_tooltip_html( $data ); ?>
</th>
<td class="forminp">
<fieldset>
<legend class="screen-reader-text">
<span><?php echo wp_kses_post( $data['title'] ); ?></span>
</legend>
<label for="<?php echo esc_attr( $field ); ?>"> <input <?php disabled( $data['disabled'], true ); ?> class="input_checkbox <?php echo esc_attr( $data['class'] ); ?>" type="checkbox" name="<?php echo esc_attr( $field ); ?>" id="<?php echo esc_attr( $field ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" value="1" <?php echo in_array($this->get_option( $key ), array('1','true','yes','on'))?'checked':''; ?> <?php echo $this->get_custom_attribute_html( $data ); ?> /> <?php echo wp_kses_post( $data['label'] ); ?></label><br />
<?php echo $this->get_description_html( $data ); ?>
</fieldset>
</td>
</tr>
<?php
return ob_get_clean();
}
public function generate_select_html( $key, $data ) {
$field = $this->get_field_key( $key );
$defaults = array( 
'title' => '', 
'disabled' => false, 
'class' => '', 
'css' => '', 
'placeholder' => '', 
'type' => 'text', 
'desc_tip' => false, 
'description' => '', 
'custom_attributes' => array(), 
'options' => array() );
$data = wp_parse_args( $data, $defaults );
ob_start();
?>
<tr valign="top">
<th scope="row" class="titledesc"><label
for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
<?php echo $this->get_tooltip_html( $data ); ?>
</th>
<td class="forminp">
<fieldset>
<legend class="screen-reader-text">
<span><?php echo wp_kses_post( $data['title'] ); ?></span>
</legend>
<select class="select <?php echo esc_attr( $data['class'] ); ?>" name="<?php echo esc_attr( $field ); ?>" id="<?php echo esc_attr( $field ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); ?>>
<?php foreach ( (array) $data['options'] as $option_key => $option_value ) : ?>
<option value="<?php echo esc_attr( $option_key ); ?>"
<?php selected( $option_key, esc_attr( $this->get_option( $key ) ) ); ?>><?php echo esc_attr( $option_value ); ?></option>
<?php endforeach; ?>
</select>
<?php echo $this->get_description_html( $data ); ?>
</fieldset>
</td>
</tr>
<?php
return ob_get_clean();
}
public function generate_range_html( $key, $data ) {
$result = $this->generate_text_html( $key, $data );
$result = preg_replace( 
'/(<input.*id=([\'"])([^\2]+?)\2.*)\/>/', 
'\1onchange=\2document.getElementById(&quot;\3_range&quot;).value=0==this.value?&quot;' .
__( 'off', $this->plugin_id ) . '&quot;:this.value\2/><output id="\3_range" class="range"></output>', 
$result );
$result = preg_replace( '/(<input.+value=([\'"])([^\2]+?)\2.+<output[^<]+)/', '\1\3', $result );
return $result;
}
public function generate_color_html( $key, $data ) {
$html = $this->generate_text_html( $key, $data );
$html = preg_replace( '/(\bclass=([\'"])[^\2]*)(input(-)text)(.*?\2)/', '\1input\4color\5', $html );
return $html;
}
public function generate_multiselect_html( $key, $data ) {
$field = $this->get_field_key( $key );
$defaults = array( 
'title' => '', 
'disabled' => false, 
'class' => '', 
'css' => '', 
'placeholder' => '', 
'type' => 'text', 
'desc_tip' => false, 
'description' => '', 
'custom_attributes' => array(), 
'options' => array() );
$data = wp_parse_args( $data, $defaults );
$value = (array) $this->get_option( $key, array() );
ob_start();
?>
<tr valign="top">
<th scope="row" class="titledesc"><label
for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
<?php echo $this->get_tooltip_html( $data ); ?>
</th>
<td class="forminp">
<fieldset>
<legend class="screen-reader-text">
<span><?php echo wp_kses_post( $data['title'] ); ?></span>
</legend>
<select multiple="multiple" class="multiselect <?php echo esc_attr( $data['class'] ); ?>" name="<?php echo esc_attr( $field ); ?>[]" id="<?php echo esc_attr( $field ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); ?>>
<?php foreach ( (array) $data['options'] as $option_key => $option_value ) : ?>
<option value="<?php echo esc_attr( $option_key ); ?>"
<?php selected( in_array( $option_key, $value ), true ); ?>><?php echo esc_attr( $option_value ); ?></option>
<?php endforeach; ?>
</select>
<?php echo $this->get_description_html( $data ); ?>
</fieldset>
</td>
</tr>
<?php
return ob_get_clean();
}
public function generate_textarea_html( $key, $data ) {
$field = $this->get_field_key( $key );
$defaults = array( 
'title' => '', 
'disabled' => false, 
'class' => '', 
'css' => '', 
'placeholder' => '', 
'type' => 'text', 
'desc_tip' => false, 
'description' => '', 
'custom_attributes' => array() );
$data = wp_parse_args( $data, $defaults );
ob_start();
?>
<tr valign="top">
<th scope="row" class="titledesc"><label
for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
<?php echo $this->get_tooltip_html( $data ); ?>
</th>
<td class="forminp">
<fieldset>
<legend class="screen-reader-text">
<span><?php echo wp_kses_post( $data['title'] ); ?></span>
</legend>
<textarea rows="3" cols="20" class="input-text wide-input <?php echo esc_attr( $data['class'] ); ?>" type="<?php echo esc_attr( $data['type'] ); ?>" name="<?php echo esc_attr( $field ); ?>" id="<?php echo esc_attr( $field ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); ?>><?php echo esc_textarea( $this->get_option( $key ) ); ?></textarea>
<?php echo $this->get_description_html( $data ); ?>
</fieldset>
</td>
</tr>
<?php
return ob_get_clean();
}
public function generate_label_html( $key, $data ) {
$field = $this->get_field_key( $key );
$defaults = array( 
'title' => '', 
'disabled' => false, 
'class' => '', 
'css' => '', 
'placeholder' => '', 
'type' => 'text', 
'desc_tip' => false, 
'description' => '', 
'custom_attributes' => array() );
$data = wp_parse_args( $data, $defaults );
$id = esc_attr( $field ) . '_';
ob_start();
?>
<tr valign="top">
<th scope="row" class="titledesc"><label for="<?php echo $id; ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
<?php echo $this->get_tooltip_html( $data ); ?>
</th>
<td class="forminp">
<fieldset>
<legend class="screen-reader-text">
<span><?php echo wp_kses_post( $data['title'] ); ?></span>
</legend>
<span class="input-text regular-input <?php echo esc_attr( $data['class'] ); ?>" name="<?php echo $id; ?>" id="<?php echo $id; ?>" style="<?php echo esc_attr( $data['css'] ); ?>" <?php echo $this->get_custom_attribute_html( $data ); ?>><?php echo esc_attr( $this->get_option( $key ) ); ?></span>
<?php echo $this->get_description_html( $data ); ?>
</fieldset>
</td>
</tr>
<?php
return ob_get_clean();
}
public function generate_title_html( $key, $data ) {
$field = $this->get_field_key( $key );
$defaults = array( 'title' => '', 'class' => '' );
$data = wp_parse_args( $data, $defaults );
ob_start();
?>
</table>
<h3 class="wc-settings-sub-title <?php echo esc_attr( $data['class'] ); ?>"
id="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></h3>
<?php if ( ! empty( $data['description'] ) ) : ?>
<p><?php echo wp_kses_post( $data['description'] ); ?></p>
<?php endif; ?>
<table class="form-table">
<?php
return ob_get_clean();
}
public function generate_numeric_html( $key, $data ) {
$field = $this->get_field_key( $key );
$defaults = array( 
'title' => '', 
'disabled' => false, 
'class' => '', 
'css' => '', 
'placeholder' => '', 
'type' => 'text', 
'desc_tip' => false, 
'description' => '', 
'custom_attributes' => array() );
$data = wp_parse_args( $data, $defaults );
ob_start();
?>
<tr valign="top">
<th scope="row" class="titledesc"><label
for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
<?php echo $this->get_tooltip_html( $data ); ?>
</th>
<td class="forminp">
<fieldset>
<legend class="screen-reader-text">
<span><?php echo wp_kses_post( $data['title'] ); ?></span>
</legend>
<input class="input_number input-text regular-input <?php echo esc_attr( $data['class'] ); ?>" type="number" name="<?php echo esc_attr( $field ); ?>" id="<?php echo esc_attr( $field ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" value="<?php echo esc_attr( $this->_wc_format_localized_decimal( $this->get_option( $key ) ) ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); ?> />
<?php echo $this->get_description_html( $data ); ?>
</fieldset>
</td>
</tr>
<?php
return ob_get_clean();
}
public function generate_media_html( $key, $data ) {
$field = $this->get_field_key( $key );
$defaults = array( 
'title' => '', 
'disabled' => false, 
'class' => '', 
'css' => '', 
'placeholder' => '', 
'desc_tip' => false, 
'description' => '', 
'custom_attributes' => array() );
$data = wp_parse_args( $data, $defaults );
ob_start();
?>
<tr valign="top">
<th scope="row" class="titledesc"><label
for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
<?php echo $this->get_tooltip_html( $data ); ?>
</th>
<td class="forminp">
<fieldset>
<legend class="screen-reader-text">
<span><?php echo wp_kses_post( $data['title'] ); ?></span>
</legend>
<input type="button" class="button hide-if-no-js"
value="<?php echo __('Select media',$this->plugin_id);?>"
id="set-footer-thumbnail">
<div id="featured-footer-image-container" class="hidden">
<img src="" alt="" title="" style="width: 128px" />
</div>
<!-- #featured-footer-image-container -->
<p class="hide-if-no-js hidden">
<a title="<?php echo __('Remove selection',$this->plugin_id);?>"
href="javascript:;" id="remove-footer-thumbnail">Remove selection</a>
</p>
<!-- #featured-footer-image-meta -->
<input type="hidden" name="<?php echo esc_attr( $field ); ?>"
class="thumbnail-src <?php echo esc_attr( $data['class'] ); ?>"
id="<?php echo esc_attr( $field ); ?>"
value="<?php echo esc_attr( $this->_wc_format_localized_decimal( $this->get_option( $key ) ) ); ?>" />
</fieldset>
</td>
</tr>
<?php
return ob_get_clean();
}
public function generate_settings_html( $fields = array() ) {
if ( empty( $fields ) ) {
$fields = $this->fields;
}
$hidden = array();
$html = '';
foreach ( $fields as $k => $v ) {
if ( ! isset( $v['type'] ) || ( $v['type'] == '' ) ) {
$v['type'] = 'text'; 
}
if ( method_exists( $this, 'generate_' . $v['type'] . '_html' ) ) {
$html .= $this->{'generate_' . $v['type'] . '_html'}( $k, $v );
} else {
$html .= $this->{'generate_text_html'}( $k, $v );
}
if ( 'label' == $v['type'] )
$hidden[] = sprintf( 
'<input type="hidden" id="%s" name="%s" value="%s"/>', 
$this->get_field_key( $k ), 
$this->get_field_key( $k ), 
esc_attr( $this->get_option( $k ) ) );
}
echo $html;
return $hidden;
}
public function admin_options() {
if ( ! empty( $this->title ) ) {
?>
<h3><?php echo $this->title ; ?></h3>
<?php
}
echo ( ! empty( $this->description ) ) ? wpautop( $this->description ) : '';
?>
<table class="form-table">
<?php $hidden=$this->generate_settings_html(); ?>
</table><?php
echo implode( PHP_EOL, $hidden );
}
}
}
?>