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
 * @file    : config.php $
 * 
 * @id      : config.php | Wed Dec 7 22:53:26 2016 +0100 | eugenmihailescu <eugenmihailescux@gmail.com> $
*/

namespace MynixCarousel;

defined(__NAMESPACE__."\\JS_NAMESPACE") || define ( __NAMESPACE__."MynixCarousel\\\JS_NAMESPACE" , "jsMynixCarousel" );
defined(__NAMESPACE__.'\\ROOT_PATH') || define(__NAMESPACE__.'MynixCarousel\\\ROOT_PATH',dirname(__FILE__).DIRECTORY_SEPARATOR);
defined(__NAMESPACE__.'\\INCLUDE_PATH') || define(__NAMESPACE__.'MynixCarousel\\\INCLUDE_PATH',ROOT_PATH.'include'.DIRECTORY_SEPARATOR);
defined(__NAMESPACE__.'\\ASSETS_PATH') || define(__NAMESPACE__.'MynixCarousel\\\ASSETS_PATH',ROOT_PATH.'assets'.DIRECTORY_SEPARATOR);
defined(__NAMESPACE__.'\\CSS_PATH') || define(__NAMESPACE__.'MynixCarousel\\\CSS_PATH',ASSETS_PATH.'css'.DIRECTORY_SEPARATOR);
defined(__NAMESPACE__.'\\IMG_PATH') || define(__NAMESPACE__.'MynixCarousel\\\IMG_PATH',ASSETS_PATH.'img'.DIRECTORY_SEPARATOR);
defined(__NAMESPACE__.'\\JS_PATH') || define(__NAMESPACE__.'MynixCarousel\\\JS_PATH',ASSETS_PATH.'js'.DIRECTORY_SEPARATOR);
defined(__NAMESPACE__.'\\APP_SLUG') || define(__NAMESPACE__.'MynixCarousel\\\APP_SLUG','wp-mycarousel');
defined(__NAMESPACE__.'\\CONFIG_PATH') && ($c=CONFIG_PATH.'config-custom.php') && file_exists($c) && (include_once $c) || define (__NAMESPACE__.'MynixCarousel\\\WP_MYCAROUSEL_CONFIG_PATH_NOT_FOUND', 'CONFIG_PATH not defined. Your installation seems to be corupted.');
?>