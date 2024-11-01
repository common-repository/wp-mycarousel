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
 * @file    : mynix-welcome.php $
 * 
 * @id      : mynix-welcome.php | Wed Dec 7 22:53:26 2016 +0100 | eugenmihailescu <eugenmihailescux@gmail.com> $
*/

namespace MynixCarousel;
?>
<div class="mynix-welcome-wrapper">
<p class="mynix-welcome-header"><?php echo __('Congratulation! It seems that you just installed the WP MyCarousel.',$this->plugin_id);?></p>
<p><?php echo __('I am very pleased you chose to use my plug-in and I am genuinely hoping that you will continue to do so for a very long time. Well, I\'ll do my best to improve it regularly.',$this->plugin_id);?></p>
<h4><?php echo __('What this plug-in does',$this->plugin_id);?></h4>
<ul class="checked">
<li><?php echo __('it allows you to quickly create what is known as a `carousel`',$this->plugin_id);?></li>
<li><?php echo __('it allows you to use that carousel in any WordPress post/page',$this->plugin_id);?></li>
<li><?php echo __('you may create and use not only one but virtually infinite number of carousels',$this->plugin_id);?></li>
</ul>
<h4><?php echo __('How it works',$this->plugin_id);?></h4>
<ul class="plus">
<li><?php echo __('you define one carousel specifying its width, height, carousel effect, etc',$this->plugin_id);?></li>
<li><?php echo __('for this carousel you defined few slides, each of them containing an image/media and optionally a description/annotation',$this->plugin_id);?></li>
<li><?php echo __('any carousel has an unique shortcode like [wp-mycarousel id="123"]; you may insert this shortcode anywhere in your post/page',$this->plugin_id);?></li>
<li><?php echo __('when you visit the post/page you will see a carousel that periodically cycles through its slides (like a slideshow)',$this->plugin_id);?></li>
</ul>
<h4><?php echo __('It\'s good to know that',$this->plugin_id);?></h4>
<ul class="info">
<li><?php echo __('you can disable any carousel without the need to permanentely delete it; who knows, you may want to use it again later',$this->plugin_id);?></li>
<li><?php echo __('when deleting a carousel it just moves to a trash; you may however permanently remove it from trash',$this->plugin_id);?></li>
<li><?php echo __('the carousel does not involve JavaScript (ie. software), it uses your hardware which makes it stable and fast acceleration thanks to CSS3',$this->plugin_id);?></li>
<li><?php echo __('it works on all browsers, from IE9+, Firefox, Chrome to Opera',$this->plugin_id);?></li>
<li><?php echo __('finally you should know that this message will be discarded as soon you create/save your first carousel into WordPress',$this->plugin_id);?></li>
</ul>
</div>