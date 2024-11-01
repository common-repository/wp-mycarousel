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
 * @file    : mynix-settings-admin.php $
 * 
 * @id      : mynix-settings-admin.php | Wed Dec 7 22:53:26 2016 +0100 | eugenmihailescu <eugenmihailescux@gmail.com> $
*/

namespace MynixCarousel;

if ( ! defined( '\\ABSPATH' ) )
exit(); 
?>
<div class="wrap mynix-carousel-admin">
<form method="post" id="mainform" action="<?php echo $admin_action;?>"
enctype="multipart/form-data">
<div class="icon32 icon32-mynix-carousel-settings" id="icon-mynix-carousel">
<br />
</div>
<?php
if ( ! empty( $admin_title ) ) {
?>
<h2 class="nav-tab-wrapper mynix-nav-tab-wrapper"><?php echo $admin_title;?></h2>
<?php
}
echo $admin_panel;
?>
<p class="mynix-editor-submit">
<input name="save" class="button-primary" type="submit"
value="<?php esc_attr_e( 'Save changes', $this->plugin_id ); ?>" /> <input
type="hidden" name="subtab" id="last_tab" />
<?php wp_nonce_field( 'mynix-carousel-settings' ); ?>
</p>
<p class="mynix-editor-cancel">
<input name="cancel" class="button button-discard-changes" type="button"
value="<?php esc_attr_e( 'Back', $this->plugin_id ); ?>" />
</p>
</form>
</div>