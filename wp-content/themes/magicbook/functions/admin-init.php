<?php
/** 
 * Admin Init
 * @package VAN Framework 
 */

require_once(get_template_directory(). '/functions/plugins/plugins.php'); 
require_once(get_template_directory() . '/functions/admin-functions.php'); 
require_once(get_template_directory() . '/functions/common-functions.php'); 


/*Integration Head in dashbroad*/
add_action('admin_enqueue_scripts', 'van_admin_init');
function van_admin_init(){
	wp_enqueue_script('jquery');
	wp_enqueue_style( 'farbtastic' );
	wp_enqueue_script( 'farbtastic' );
	wp_enqueue_style("van-admin", get_template_directory_uri()."/functions/css/admin.css", false, "1.0", "all");
	wp_enqueue_script("admin_script", get_template_directory_uri()."/functions/js/admin_script.js");
	wp_enqueue_script( 'admin_script' );
}
?>