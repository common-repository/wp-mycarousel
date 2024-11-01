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
 * @file    : mynix-class-carousel.php $
 * 
 * @id      : mynix-class-carousel.php | Wed Dec 7 22:53:26 2016 +0100 | eugenmihailescu <eugenmihailescux@gmail.com> $
*/

namespace MynixCarousel;

! defined( '\\ABSPATH' ) && exit();
if ( ! class_exists( __NAMESPACE__ . '\\MynixCarousel' ) ) {
class MynixCarousel {
private $custom_styles = array();
private $plugin_id;
private $carousel_timer;
private $carousel_progress;
private $carousel_progress_color;
private $carousel_slides_count;
private $carousel_width;
private $carousel_width_scale;
private $carousel_height;
private $carousel_background;
private $carousel_navcolor;
private $carousel_shadow;
private $carousel_effect;
function __construct( $plugin_id ) {
$this->plugin_id = $plugin_id;
add_action( 'wp_footer', array( &$this, 'enqueue_carousel_footer' ) );
}
function hex_to_rgb( $hex ) {
$hex = str_replace( '#', '', $hex );
( strlen( $hex ) < 6 ) && $hex = substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) . substr( $hex, 1, 1 ) .
substr( $hex, 1, 1 ) . substr( $hex, 2, 1 ) . substr( $hex, 2, 1 );
$dec = hexdec( $hex );
$i = 256 * 256;
$r = $dec >> 16;
$g = ( $dec - $r * $i ) >> 8;
$b = $dec - $r * $i - $g * 256;
return array( $r, $g, $b );
}
function hex_to_rgba( $hex, $opacity ) {
$rgb = $this->hex_to_rgb( $hex );
return sprintf( 'rgba(%d,%d,%d,%f)', $rgb[0], $rgb[1], $rgb[2], $opacity );
}
function enqueue_carousel_scripts() {
$suffix = '.min';
$handle = $this->plugin_id;
$plugin_css = 'assets/css/' . $handle;
file_exists( plugin_dir_path( __DIR__ ) . $plugin_css . $suffix . '.css' ) && $plugin_css .= $suffix;
wp_enqueue_style( $handle, plugins_url( $plugin_css . '.css', __DIR__ ) );
}
function enqueue_carousel_footer() {
$this->enqueue_carousel_scripts();
echo PHP_EOL, '<!-- Mynix Carousel inline style -->';
echo PHP_EOL, '<style id="mynix-carousel-style-inline-css" type="text/css">', PHP_EOL;
echo $this->generate_carousel_css();
echo PHP_EOL, '</style>', PHP_EOL;
if ( ! $this->carousel_timer )
return;
echo PHP_EOL, '<!-- Mynix Carousel inline script -->';
echo PHP_EOL, '<script id="mynix-carousel-style-inline-script" type="text/javascript">', PHP_EOL;
echo $this->generate_caoursel_js( $this->carousel_timer, $this->carousel_slides_count );
echo PHP_EOL, '</script>', PHP_EOL;
}
function generate_caoursel_js() {
$mobile_pb_div = sprintf( 
'<div class="mynix-progressbar" style="background-color:%s !important;"></div>', 
$this->carousel_progress_color );
ob_start();
?>
(function($){
var width=0, carousel_slide_index=0, carousel_slide_from=0, carousel_slide_to=<?php echo $this->carousel_slides_count-1;?>;
var reset=function(){width=0;refresh();};
var refresh=function(){
sel=$('.mynix-slideshow-wrapper');
if(sel.length){
sel=sel.get(0);
sel.style.visibility='hidden';
sel.offsetHeight; 
sel.style.visibility='visible';
}
};
var animateSlide=function(){
carousel_slide_index++;
if(carousel_slide_index>carousel_slide_to){
(carousel_slide_index=carousel_slide_from);
}
$('#button-'+carousel_slide_index).prop('checked',true);
reset();
refresh();	
};
setInterval(function(){
animateSlide();
},<?php echo $this->carousel_timer;?>);
if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
var timer, reset=function(){width=0;};
$('.mynix-slideshow-wrapper .arrows').addClass('arrows-mobile');
$('.show-description-label').addClass('arrows-mobile');
$('.mynix-slideshow-wrapper>input[type="radio"]').click(reset).change(reset);	
$('ul.mynix-carousel').after('<?php echo $mobile_pb_div;?>');
setInterval(function(){
$('.mynix-progressbar').css('width',width+'%');
if (width++>=100) {
reset();
}
},<?php echo intval($this->carousel_timer/100);?>);
}
else{
$('ul.mynix-carousel').addClass('mynix-carousel-desktop');
}
})(jQuery);
<?php
return ob_get_clean();
}
function generate_carousel_css() {
$get_opacity_css = function ( $opacity ) {
return sprintf( 
'-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=%d)";filter: alpha(opacity=%d);-moz-opacity: %f;-khtml-opacity: %f;opacity:%f;', 
100 * $opacity, 
100 * $opacity, 
$opacity, 
$opacity, 
$opacity );
};
$get_css_by_effect = function ( $i, $effect ) use(&$get_opacity_css){
$selector = '.mynix-slideshow-wrapper input[type=radio]#button-%d:checked~.mynix-slideshow-inner';
switch ( $effect ) {
case 'slide' :
$css = sprintf( $selector . '>ul {left: %d%%}', $i, - 100 * $i );
break;
case 'fade' :
$css = sprintf( $selector . ' li[id="slide%d"] {%sz-index:10;}', $i, $i, $get_opacity_css( 1 ) );
$css .= sprintf( $selector . ' li[id="slide%d"] a{visibility:visible;}', $i, $i );
break;
case 'flipX' :
case 'flipY' :
$css = sprintf( 
$selector .
' li[id="slide%d"] {%svisibility:visible;-webkit-transform:rotate%s(0deg);-moz-transform:rotate%s(0deg);-ms-transform:rotate%s(0deg); -o-transform:rotate%s(0deg);transform: rotate%s(0deg);-webkit-transition:all 1s;-moz-transition:all 1s;-o-transition:all 1s;transition:all 1s;}', 
$i, 
$i, 
$get_opacity_css( 1 ), 
'flipX' == $effect ? 'X' : 'Y', 
'flipX' == $effect ? 'X' : 'Y', 
'flipX' == $effect ? 'X' : 'Y', 
'flipX' == $effect ? 'X' : 'Y', 
'flipX' == $effect ? 'X' : 'Y' );
break;
}
return '/* CSS for ' . $effect . ' transition */' . PHP_EOL . $css . PHP_EOL . '/* End ' . $effect .
' CSS */' . PHP_EOL;
};
$label_margin = 18; 
$from_margin = - ( $this->carousel_slides_count - 1 ) * $label_margin / 2;
$a0 = array();
$a1 = array();
$a2 = array();
$a3 = array();
$a4 = array();
ob_start();
for ( $i = 0; $i < $this->carousel_slides_count; $i++ ) {
$a0[] = sprintf( 
'.mynix-slideshow-wrapper input[type=radio]#button-%d:checked~label[for=button-%d]', 
$i, 
$i );
?>
.mynix-slideshow-wrapper label[for=button-<?php echo $i;?>]:not(.arrows) { margin-left: <?php echo $from_margin;?>px }
<?php
echo $get_css_by_effect( $i, $this->carousel_effect );
if ( $i < $this->carousel_slides_count - 1 ) {
$a1[] = sprintf( 'input[type=radio]#button-%d:checked~.arrows#arrow-%d', $i, $i + 1 );
}
if ( $i ) {
$style = sprintf( 'input[type=radio]#button-%d:checked~.arrows#arrow-%d', $i, $i - 1 );
$a2[] = $style;
$a3[] = sprintf( '%s {left: %dpx}', $style, - ( 1 + 0 * ( $i + 1 ) ) );
}
if ( $this->carousel_progress ) {
$a4[] = sprintf( 
'.mynix-slideshow-wrapper input[type=radio]#button-%d~.mynix-slideshow-inner li[id=slide%d]:after {width:0%%;}', 
$i, 
$i );
$a4[] = sprintf( 
'.mynix-slideshow-wrapper input[type=radio]#button-%d:checked~.mynix-slideshow-inner li[id=slide%d]:after {width:100%%;-webkit-transition:width %ds linear;-moz-transition:width %ds linear;-o-transition:width %ds linear;transition:width %ds linear;}', 
$i, 
$i, 
$this->carousel_timer / 1000, 
$this->carousel_timer / 1000, 
$this->carousel_timer / 1000, 
$this->carousel_timer / 1000 );
}
$from_margin += $label_margin;
}
if ( 'fade' == $this->carousel_effect ) {
?>
.mynix-slideshow-wrapper input[type=radio]:checked~.mynix-slideshow-inner li { z-index:0;<?php echo $get_opacity_css(0);?> -webkit-transition:opacity 1s ease-in-out;-moz-transition:opacity 1s ease-in-out;-o-transition:opacity 1s ease-in-out; transition: opacity 1s ease-in-out;}
.mynix-slideshow-wrapper input[type=radio]:checked~.mynix-slideshow-inner li a { visibility:hidden;}
.mynix-slideshow-inner > ul > li{position:absolute !important;}
<?php
}
if ( preg_match( '/flip[XY]/', $this->carousel_effect ) ) {
$transform = 'rotate' . ( 'flipX' == $this->carousel_effect ? 'X' : 'Y' ) . '(180deg)';
?>
.mynix-slideshow-wrapper input[type=radio]:checked~.mynix-slideshow-inner li { <?php echo $get_opacity_css(0);?>visibility:hidden;transform: <?php echo $transform;?>;-webkit-transform:<?php echo $transform;?>;-ms-transform:<?php echo $transform;?>;-webkit-transition:all 1s;-moz-transition:all 1s;-o-transition:all 1s; transition:all 1s;}
.mynix-slideshow-inner > ul > li{position:absolute !important;}		
<?php
}
$arrow_relative_margin = 0;
echo PHP_EOL, implode( ',', $a1 ), sprintf( '{right: %dpx; display: block;}', $arrow_relative_margin );
echo PHP_EOL, implode( ',', $a2 ), sprintf( 
'{left: %dpx;display: block;-webkit-transform: scaleX(-1);-moz-transform: scaleX(-1);-ms-transform: scaleX(-1);-o-transform: scaleX(-1);transform: scaleX(-1);}', 
$arrow_relative_margin );
echo PHP_EOL, implode( PHP_EOL, $a3 );
echo PHP_EOL, implode( PHP_EOL, $a4 );
echo PHP_EOL, sprintf( '.mynix-slideshow-wrapper{min-height: %s;}', $this->carousel_height );
$calc_width = sprintf( 'calc(%s %s)', $this->carousel_width, '- 3px' );
$width_prop = ( 'stretch' == $this->carousel_width_scale ? 'max-' : '' ) . 'width';
echo PHP_EOL, sprintf( 
".mynix-slideshow-wrapper{ $width_prop: %s;$width_prop:-webkit-%s;$width_prop:-moz-%s;$width_prop:%s;}", 
$this->carousel_width, 
$calc_width, 
$calc_width, 
$calc_width );
echo PHP_EOL, sprintf( '.mynix-slideshow-inner>ul {width: %d%%;}', 100 * $this->carousel_slides_count );
echo PHP_EOL, sprintf( 
'.mynix-slideshow-inner>ul>li {width:%.4f%%;height: %s;}', 
100 / $this->carousel_slides_count, 
$this->carousel_height );
$this->carousel_shadow &&
print 
( PHP_EOL .
'.mynix-slideshow-wrapper {-webkit-box-shadow:0px 0px 5px rgba(0,0,0,.8);box-shadow: 0px 0px 5px rgba(0,0,0,.8);}' ) ;
$this->carousel_background &&
printf( PHP_EOL . 'div.mynix-slideshow-inner {background:%s;}', $this->carousel_background );
if ( $this->carousel_navcolor ) {
$color = sprintf( 'rgba(%s,0.7)', implode( ',', $this->hex_to_rgb( $this->carousel_navcolor ) ) );
echo PHP_EOL, implode( ',', $a0 ), sprintf( 
'{background-color: %s;-webkit-box-shadow:0px 0px 5px %s;box-shadow:0 0 5px %s;}', 
$color, 
$color, 
$color );
printf( PHP_EOL . 'label.arrows{background-color: %s !important;}', $this->carousel_navcolor );
printf( 
PHP_EOL .
'.mynix-slideshow-wrapper label:not(.arrows ):not(.show-description-label ){background-color: rgba(%s,0.3);-webkit-box-shadow:0px 0px 5px %s !important;box-shadow:0 0 5px %s !important;}', 
implode( ',', $this->hex_to_rgb( $this->carousel_navcolor ) ), 
$color, 
$color );
}
if ( $this->carousel_progress_color )
echo PHP_EOL . sprintf( 
'.mynix-carousel-desktop li::after{background-color:%s !important;}', 
$this->carousel_progress_color );
echo PHP_EOL, implode( PHP_EOL, $this->custom_styles );
echo PHP_EOL, sprintf( 
'.show-description-label{background-color:%s !important;}', 
$this->hex_to_rgba( $this->carousel_navcolor, 0.7 ) );
return ob_get_clean();
}
function generate_carousel_controls_html( $start_slide_id = 0 ) {
$a = array();
ob_start();
for ( $i = 0; $i < $this->carousel_slides_count; $i++ ) {
?>
<input type="radio" id="button-<?php echo $i;?>" name="controls"
<?php echo $i==$start_slide_id?' checked="checked"':'';?> />
<label for="button-<?php echo $i;?>" class="quick-slide"></label>
<?php
$a[] = sprintf( '<label for="button-%d" class="arrows" id="arrow-%d">></label>', $i, $i );
}
echo implode( PHP_EOL, $a );
return ob_get_clean();
}
function generate_carousel_slides_html( $image, $description, $annotation, $event, $target, $click ) {
ob_start();
?>
<ul style="height: 100%; position: relative; float: left;"
class="mynix-carousel">
<?php
foreach ( $image as $slide_id => $image_url ) {
$desc = array();
$onclick = array();
isset( $description[$slide_id] ) && preg_match( 
'/(([^=;]*)=)?([^;]*)/', 
$description[$slide_id], 
$desc );
isset( $click[$slide_id] ) && preg_match( 
'/(^(javascript|http):\/\/.+)?/', 
$click[$slide_id], 
$onclick );
echo $this->generate_carousel_slide_html( 
$slide_id, 
$image_url, 
isset( $desc[2] ) ? $desc[2] : '', 
isset( $desc[3] ) ? $desc[3] : '', 
isset( $annotation[$slide_id] ) ? $annotation[$slide_id] : '', 
isset( $event[$slide_id] ) ? $event[$slide_id] : '', 
isset( $target[$slide_id] ) ? $target[$slide_id] : '', 
isset( $click[$slide_id] ) ? htmlentities( trim( stripslashes( $click[$slide_id] ) ) ) : '' );
}
?>
</ul>
<?php
return ob_get_clean();
}
function generate_carousel_slide_html( 
$slide_id, 
$image_url, 
$caption, 
$description, 
$annotation, 
$event, 
$target, 
$click ) {
ob_start();
?>
<li id="slide<?php echo $slide_id;?>"
<?php if(empty($annotation))$this->enqueue_custom_style(sprintf('.mynix-carousel li[id=slide%d]:BEFORE {z-index:-1 !important;}',$slide_id));else printf(' annot="%s"',$annotation);?>>
<?php
$img = '';
empty( $image_url ) || $img = sprintf( '<img src="%s">', $image_url );
$link = ( 'javascript' == $event ? 'javascript:{' : '' ) . $click . ( 'javascript' == $event ? '}' : '' );
empty( $img ) || empty( $event ) || empty( $click ) || $img = sprintf( 
'<a href="%s" target="%s">%s</a>', 
$link, 
$target, 
$img );
echo $img;
if ( ! ( empty( $description ) && empty( $caption ) ) ) {
?>
<div class="description">
<input id="show-description-<?php echo $slide_id;?>" type="checkbox"> <label
for="show-description-<?php echo $slide_id;?>" class="show-description-label">i</label>
<div class="description-text">
<?php
printf( '<h2>%s</h2>', $caption );
empty( $description ) || printf( '<p>%s</p>', $description );
?>			
</div>
</div>
<?php }?>
</li>
<?php
return ob_get_clean();
}
function generate_carousel_html( $atts ) {
$true_values = array( '1', 'yes', 'true', 'on' );
$image = isset( $atts['image'] ) ? explode( ';', $atts['image'] ) : array();
$description = isset( $atts['description'] ) ? explode( ';', $atts['description'] ) : array();
$annotation = isset( $atts['annotation'] ) ? explode( ';', $atts['annotation'] ) : array();
$event = isset( $atts['event'] ) ? explode( ';', $atts['event'] ) : array();
$target = isset( $atts['target'] ) ? explode( ';', $atts['target'] ) : array();
$click = isset( $atts['click'] ) ? explode( '$', $atts['click'] ) : array(); 
$wrapper_class = isset( $atts['wrapper-class'] ) ? $atts['wrapper-class'] : false;
$slide_class = isset( $atts['slide-class'] ) ? $atts['slide-class'] : false;
$arrow_class = isset( $atts['arrow-class'] ) ? $atts['arrow-class'] : false;
$slider_class = isset( $atts['slider-class'] ) ? $atts['slider-class'] : false;
$this->carousel_timer = isset( $atts['timer'] ) && $atts['timer'] ? $atts['timer'] : false;
$this->carousel_progress = isset( $atts['progress'] ) && $this->carousel_timer ? $atts['progress'] : $this->carousel_timer;
$this->carousel_progress_color = isset( $atts['progress_color'] ) ? $atts['progress_color'] : '#888888';
$this->carousel_slides_count = count( $image );
$this->carousel_width = isset( $atts['width'] ) ? $atts['width'] : 'auto';
$this->carousel_width_scale = isset( $atts['width_scale'] ) ? $atts['width_scale'] : '100';
$this->carousel_height = isset( $atts['height'] ) ? $atts['height'] : 'auto';
$this->carousel_background = isset( $atts['background'] ) ? $atts['background'] : '';
$this->carousel_navcolor = isset( $atts['navcolor'] ) ? $atts['navcolor'] : '';
$this->carousel_shadow = isset( $atts['shadow'] ) ? $atts['shadow'] : true;
$this->carousel_effect = isset( $atts['effect'] ) ? $atts['effect'] : 'fade';
if ( empty( $image ) )
return '';
echo PHP_EOL, '<!-- Mynix Carousel starts here', PHP_EOL;
echo ' Generated with Mynix Carousel', PHP_EOL;
echo ' http://www.wordpress.org/plugins/mynix-carousel', PHP_EOL;
echo '-->', PHP_EOL;
echo '<div class="mynix-slideshow-wrapper mynix-carousel">';
echo PHP_EOL, '<!-- Mynix Carousel controls -->', PHP_EOL;
echo $this->generate_carousel_controls_html();
echo PHP_EOL, '<!-- Mynix Carousel slides -->', PHP_EOL;
echo '<div class="mynix-slideshow-inner mynix-carousel">';
echo $this->generate_carousel_slides_html( $image, $description, $annotation, $event, $target, $click );
echo '</div>';
echo '</div>';
echo PHP_EOL, '<!-- Mynix Carousel ends here -->', PHP_EOL;
}
public function enqueue_custom_style( $custom_styles ) {
$this->custom_styles[] = $custom_styles;
}
}
}
?>