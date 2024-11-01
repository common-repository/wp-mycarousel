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
 * @file    : mynix-class-list-table.php $
 * 
 * @id      : mynix-class-list-table.php | Wed Dec 7 22:53:26 2016 +0100 | eugenmihailescu <eugenmihailescux@gmail.com> $
*/

namespace MynixCarousel;

if ( ! class_exists( 'WP_List_Table' ) ) {
require_once ( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
if ( ! class_exists( __NAMESPACE__ . '\\Extended_WP_List_Table' ) ) {
class Extended_WP_List_Table extends \WP_List_Table {
private $first_colname;
private $columns = array();
public $container_class;
private $sortable_columns = array();
public $searchable_columns = array();
private $filter_links = '';
public $data = array();
public $screen_options = null;
public $filter_function = null;
public $sort_function = null;
public $count_function = null;
public $paged_function = null;
public $row_actions = null;
public $plugin_id = '';
public $level = '';
public $bulk_actions = array();
public $table_actions = array();
public $has_cb = true;
public function set_columns( $columns ) {
$this->columns = $columns['columns'];
$this->first_colname = key( $this->columns );
$this->columns = array( 'key_' => $this->columns[$this->first_colname] ) + $this->columns;
unset( $this->columns[$this->first_colname] );
$found = array_search( $this->first_colname, $columns['sortable'] );
if ( false !== $found ) {
$columns['sortable'][$found] = 'key_';
}
$this->sortable_columns = array();
foreach ( $columns['sortable'] as $sortable_column ) {
$this->sortable_columns[$sortable_column] = array( $sortable_column, false );
}
$this->searchable_columns = isset( $columns['searchable'] ) ? $columns['searchable'] : array();
}
public function set_filter_links( $filter_links ) {
isset( $filter_links['all'] ) || $filter_links = array( 
'all' => array( 'class' => 'current', 'count' => 0, 'title' => __( 'All', $this->plugin_id ) ) ) +
$filter_links;
$li = array();
foreach ( $filter_links as $key => $options ) {
if ( ! $options['count'] )
continue;
$count = sprintf( '<span class="count"> (%d) </span>', $options['count'] );
$li[] = '<li class="' . $options['class'] . '"><a href="' .
sprintf( '%s?page=%s&status=%s', get_admin_url(), $this->plugin_id, $key ) . '" class="' .
$options['class'] . '">' . $options['title'] . $count . '</a>';
}
ob_start();
echo '<ul class="subsubsub">';
echo implode( ' |</li>', $li );
empty( $li ) || print ( '</li>' ) ;
echo '</ul>';
$this->filter_links = ob_get_clean();
}
public function get_columns() {
$result = array();
empty( $this->bulk_actions ) || $result['cb'] = '<input type="checkbox" />';
return $result + $this->columns;
}
public function usort_reorder( $a, $b ) {
$columns = $this->sortable_columns;
$orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : $this->first_colname;
$orderby = 'key_' == $orderby ? $this->first_colname : $orderby;
$order = ( ! empty( $_GET['order'] ) ) ? $_GET['order'] : 'asc';
$result = strcmp( $a[$orderby], $b[$orderby] );
return ( $order === 'asc' ) ? $result : - $result;
}
public function prepare_items() {
$screen_options = isset( $this->screen_options ) ? $this->screen_options : array( 
'label' => __( 'Items', $this->plugin_id ), 
'per_page' => 5, 
'option' => 'per_page', 
'option_name' => 'per_page' );
$status = isset( $_GET['status'] ) ? $_GET['status'] : 'all';
$this->_column_headers = array( $this->get_columns(), array( 'deleted' ), $this->get_sortable_columns() );
if ( isset( $_POST['s'] ) && ! empty( $_POST['s'] ) ) {
$this->data = is_callable( $this->filter_function ) ? call_user_func( 
$this->filter_function, 
$this, 
$_POST['s'] ) : array_filter( 
$this->data, 
function ( $item ) {
foreach ( $this->searchable_columns as $colname ) {
if ( false !== stripos( $item[$colname], $_POST['s'] ) )
return true;
}
return false;
} );
}
isset( $this->sort_function ) && call_user_func( $this->sort_function, $this->data ) ||
usort( $this->data, array( &$this, 'usort_reorder' ) );
$per_page = empty( $this->screen_options ) ||
! isset( $this->screen_options[$this->screen_options['option_name']] ) ? 10 : $this->get_items_per_page( 
$this->screen_options[$this->screen_options['option_name']], 
5 );
$current_page = $this->get_pagenum();
$total_items = is_callable( $this->count_function ) ? call_user_func( 
$this->count_function, 
$this->data, 
$status ) : count( $this->data );
$paged_data = is_callable( $this->paged_function ) ? call_user_func( 
$this->paged_function, 
( $current_page - 1 ) * $per_page, 
$per_page ) : array_slice( $this->data, ( $current_page - 1 ) * $per_page, $per_page );
$this->set_pagination_args( 
array( 'total_items' => $total_items, $this->screen_options['option_name'] => $per_page ) );
$this->items = $paged_data;
}
public function column_default( $item, $column_name ) {
$column_name = 'key_' == $column_name ? $this->first_colname : $column_name;
$value = isset( $item[$column_name] ) ? $item[$column_name] : '';
return is_bool( $value ) ? ( $value ? '<span class="data-true"/>' : '<span class="data-false"/>' ) : $value;
}
public function get_sortable_columns() {
return $this->sortable_columns;
}
public function get_default_actions() {
return array( 'edit' => __( 'Edit', $this->plugin_id ), 'delete' => __( 'Delete', $this->plugin_id ) );
}
public function column_key_( $item ) {
$status = isset( $_GET['status'] ) ? $_GET['status'] : 'all';
$level = empty( $this->level ) ? ( isset( $_GET['level'] ) ? $_GET['level'] : '' ) : $this->level;
$default_row_actions = $this->get_default_actions();
$default_row_actions = is_array( $this->row_actions ) ? $this->row_actions : $default_row_actions;
$default_row_actions = array( 'id' => sprintf( 'ID: %d', $item['ID'] ) ) + $default_row_actions;
foreach ( $default_row_actions as $key => $title )
$actions[$key] = 'id' == $key ? sprintf( 'ID: %d', $item['ID'] ) : sprintf( 
'<a href="?page=%s&action=%s&status=%s&%s_id=%s%s">%s</a>', 
$_REQUEST['page'], 
$key, 
$status, 
$level, 
$item['ID'], 
empty( $level ) ? '' : ( '&level=' . $level ), 
$title );
return sprintf( '%1$s %2$s', $item[$this->first_colname], $this->row_actions( $actions ) );
}
public function get_bulk_actions() {
return $this->bulk_actions;
}
public function column_cb( $item ) {
return sprintf( '<input type="checkbox" name="key[]" value="%s" />', $item['ID'] );
}
public function no_items() {
echo __( 'No item found, the list is empty.', $this->plugin_id );
}
public function generate_table_html( $title = '', $level = '' ) {
$this->level = $level;
$this->prepare_items();
ob_start();
?>
<a
href="<?php printf('%s?page=%s&action=new%s',get_admin_url(),$this->plugin_id,empty($level)?'':('&level='.$level));?>"
class="page-title-action table-action-new"><?php echo __('Add New',$this->plugin_id);?></a>
<?php
$default_action = array( ob_get_clean() );
$table_actions = $default_action + $this->table_actions;
?>
<div class="wrap wp-list-extended-table <?php echo $this->container_class;?>">
<h2><?php echo $title;?>
<?php echo implode('', $table_actions)?>
</h2>
<?php
echo $this->filter_links;
if ( ! empty( $this->searchable_columns ) ) {
?>
<form method="post">
<input type="hidden" name="page" value="<?php echo $this->plugin_id;?>" />
<?php $this->search_box('search', 'search_id'); ?>
</form>
<?php
}
$this->display();
?>
</div>
<?php
}
}
}
?>