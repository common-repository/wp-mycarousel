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
 * @file    : autoloader.php $
 * 
 * @id      : autoloader.php | Wed Dec 7 22:53:26 2016 +0100 | eugenmihailescu <eugenmihailescux@gmail.com> $
*/

namespace MynixCarousel;

global $classes_path_255957562;
$classes_path_255957562 = array (
'Extended_WP_List_Table' => INCLUDE_PATH . 'mynix-class-list-table.php',
'MynixCarousel' => INCLUDE_PATH . 'mynix-class-carousel.php',
'MynixSettingsEditor' => INCLUDE_PATH . 'mynix-settings-editor.php',
'Mynix_Carousel' => ROOT_PATH . 'mynix-carousel.php'
);
spl_autoload_register ( function ($class_name) {
global $classes_path_255957562;
$class_name = preg_replace ( "/" . __NAMESPACE__ . "\\\\/", "", $class_name );
isset ( $classes_path_255957562 [$class_name] ) && include_once $classes_path_255957562 [$class_name];});
?>