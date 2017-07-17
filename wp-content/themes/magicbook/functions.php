<?php
/*
 * ThemeVan Functions
 * @package VAN Framework
 * @since VAN Framework 1.0
 */

 
/* -----------------------------------------------------------------------------------------------------
Hi,there!
I must emphasize a point that please refrain from editing this file,
or you cannot smooth update in the future.
If you wanna customize your own functions,please add them in custom-functions.php in includes folder.

-------------------------------------------------------------------------------------------------------*/
$MB_VAN = get_option( 'magicbook_opt' );
include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 

/*-------------------------------------------------------
* The path to VAN Framework and theme specific functions
-------------------------------------------------------*/

$functions_path = get_template_directory() . '/functions/';
$includes_path = get_template_directory() . '/includes/';
$vc_path = get_template_directory() . '/vc_extends/';

/*The theme functions*/
require_once($includes_path."theme-init.php");
require_once($functions_path."admin-init.php");

/*Admin Options*/
if(is_admin()){require_once($includes_path.'options-init.php'); }

/*Admin functions*/
require_once($vc_path."vc_extended.php"); 

/*Load scripts in footer*/
add_action('wp_enqueue_scripts', 'van_scripts');
function van_scripts(){
    global $MB_VAN;

    //CSS Files
	wp_enqueue_style("style", get_stylesheet_uri(), false, null, "all");
	wp_enqueue_style("bookblock", get_template_directory_uri()."/css/bookblock.css", false, null, "all");
	wp_enqueue_style("component", get_template_directory_uri()."/css/component.css", false, null, "all");
	wp_enqueue_style("bjqs", get_template_directory_uri()."/css/bjqs.css", false, null, "all");
	wp_enqueue_style("perfect-scrollbar", get_template_directory_uri()."/css/perfect-scrollbar.min.css", false, null, "all");
	wp_enqueue_style("reset", get_template_directory_uri()."/css/reset.css", false, null, "all");
	wp_enqueue_style("main", get_template_directory_uri()."/css/main.css", false, null, "all");
	wp_enqueue_style("custom", get_template_directory_uri()."/custom.css", false, null, "all");
	wp_enqueue_style("layout-responsive", get_template_directory_uri()."/css/responsive.css", false, null, "all");
	wp_enqueue_style("font-awesome", get_template_directory_uri()."/font-awesome/css/font-awesome.min.css", false, null, "all");

	//plugin
	wp_enqueue_style("flexslider", get_template_directory_uri()."/js/flexslider/flexslider.css", false, null, "all");
	wp_enqueue_style("colorbox", get_template_directory_uri()."/js/colorbox/colorbox.css", false, "1.2", "all");
	
	//JS Files
	wp_enqueue_script( 'easing', get_template_directory_uri() . '/js/jquery.easing.min.js', array( 'jquery' ), null, false );
    wp_enqueue_script( 'modernizr-custom', get_template_directory_uri() . '/js/modernizr.custom.38010.js', array( 'jquery' ), null, false );
    wp_enqueue_script( 'isotope', get_template_directory_uri() . '/js/jquery.isotope.min.js', array( 'jquery' ), null, false );
    wp_enqueue_script( 'hoverIntent');

	wp_enqueue_script( 'bookblock', get_template_directory_uri() . '/js/jquery.bookblock.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'colorbox', get_template_directory_uri() . '/js/colorbox/jquery.colorbox.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'bjqs', get_template_directory_uri() . '/js/bjqs-1.3.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'jquerypp-custom', get_template_directory_uri() . '/js/jquerypp.custom.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'placeholder', get_template_directory_uri() . '/js/jquery.placeholder.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'perfect-scrollbar', get_template_directory_uri() . '/js/perfect-scrollbar.min.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'mousewheel', get_template_directory_uri() . '/js/jquery.mousewheel.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'classie', get_template_directory_uri() . '/js/classie.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'imagesLoaded', get_template_directory_uri() . '/js/jquery.imagesLoaded.min.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'flexslider', get_template_directory_uri() . '/js/flexslider/jquery.flexslider-min.js', array( 'jquery' ), null, true );
	
	if($MB_VAN['default_menu_button']=='0' || !isset($MB_VAN['default_menu_button'])):
	 wp_enqueue_script( 'pushy', get_template_directory_uri() . '/js/pushy.js', array( 'jquery' ), null, true );
	endif;
	wp_enqueue_script( 'magicbook-wp', get_template_directory_uri() . '/js/custom-for-wp.js', array( 'jquery' ), null, true );

	if(is_home() && $MB_VAN['book-homepage']=='1'){
		wp_enqueue_script( 'magicbook-script', get_template_directory_uri() . '/js/script.js', array( 'jquery' ), null, true );
		
		$flip_direction='rtl';
		if(isset($MB_VAN['flip_direction'])):
		  $flip_direction=$MB_VAN['flip_direction'];
		endif;

		wp_add_inline_script( 'magicbook-script','template_url="'.get_template_directory_uri().'";
	    is_home=1;
	    flip_direction="'.$flip_direction.'";','before');
	}
}
?>