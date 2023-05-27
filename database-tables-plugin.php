<?php
/**
 * Plugin Name: Database Tables
 * Description: Displays database tables and allows users to change their engines.
 * Version: 1.0
 * Author: plugins.club
 */

function database_tables_menu() {
  add_management_page(
    'Database Tables',
    'Database Tables',
    'manage_options',
    'database-tables',
    'database_tables_page'
  );
}
add_action( 'admin_menu', 'database_tables_menu' );

function database_tables_page() {
  if ( ! current_user_can( 'manage_options' ) ) {
    return;
  }

  global $wpdb;

  if ( isset( $_POST['table'] ) && isset( $_POST['engine'] ) ) {
    $table = sanitize_text_field( $_POST['table'] );
    $engine = sanitize_text_field( $_POST['engine'] );
    $wpdb->query( "ALTER TABLE $table ENGINE = $engine" );
  }

  $tables = $wpdb->get_results( "SHOW TABLE STATUS" );

  echo '<h1>Database Tables</h1>';
  echo '<p class="wp-ui-primary"></p>';
  echo '<table class="widefat" style="width:auto">';
  echo '<tr><th><b>Table Name</b></th><th><b>Engine</b></th><th><b>Rows</b></th><th><b>Size</b></th><th><b>Change Engine</b></th></tr>';
  foreach ( $tables as $table ) {
    echo '<tr>';
    echo '<td>' . $table->Name . '</td>';
    echo '<td>' . $table->Engine . '</td>';
    echo '<td>' . $table->Rows . '</td>';
    echo '<td>' . size_format( $table->Data_length + $table->Index_length ) . '</td>';
    echo '<td>';
    echo '<form method="post">';
    echo '<input type="hidden" name="table" value="' . $table->Name . '">';
    echo '<select name="engine">';
    echo '<option value="InnoDB">InnoDB</option>';
    echo '<option value="MyISAM">MyISAM</option>';
    echo '</select>';
    echo '<button type="submit" class="button">Change</button>';
    echo '</form>';
    echo '</td>';
    echo '</tr>';
  }
  echo '</table>';
}
