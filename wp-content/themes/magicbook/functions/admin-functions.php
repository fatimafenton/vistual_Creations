<?php
/** 
 * Admin Functions
 * @package VAN Framework 
 */
 
/* TABLE OF CONTENTS
 * - van_head()
 * - van_footer()
 * - van_favicon()
 * - van_seo_setting()
 * - van_add_styles()
 * - van_additional_code()
 * - van_statistics_code()
 * - van_customize_meta_boxes()
 * - van_page_excerpt_metabox()
 * - van_custom_profile( $contactmethods )
 * - van_dashboard_widget() 
 */
 
/*Integration Head*/
add_action('wp_head', 'van_head');
if( !function_exists( 'van_head') ){
	function van_head(){
		van_custom_css();
	    van_favicon();
		van_additional_code();
		if ( is_singular() && get_option('thread_comments'))wp_enqueue_script( 'comment-reply' );
	}
}

/*Integration Footer*/
add_action('wp_footer', 'van_footer',99);
if( !function_exists( 'van_footer') ){
	function van_footer(){
	   van_footer_code();
	}
}

/*Add a lovely favicon*/
if( !function_exists( 'van_favicon') ){
	function van_favicon() {
	   global $MB_VAN;
	   if(isset($MB_VAN['favicon'])&&$MB_VAN['favicon'] <> ''){
	     echo '<link rel="shortcut icon" href="'.$MB_VAN['favicon']['url'].'" type="image/x-icon">'.PHP_EOL; 
	   } 
	}
}

/*Add Cunstom CSS*/
if( !function_exists( 'van_custom_css') ){
	function van_custom_css() {
		
	    global $MB_VAN;
	    $custom_css='';

		$custom_css_before='<style type="text/css">';
		$custom_css_after='</style>';
		
		$custom_css.=$custom_css_before.PHP_EOL;
		
	    $custom_css.='#main-loading{background-image:url('.get_template_directory_uri().'/img/necessity/loadinfo.net-'.$MB_VAN['preset-loader'].'.gif);}';
		 
		if(isset($MB_VAN['page-background']['url']) && $MB_VAN['page-background']['url']<>''){
		  $custom_css.='body{background-image:url('.$MB_VAN['page-background']['url'].');}';
		}else{
		  if( isset($MB_VAN['preset-page-background']) && $MB_VAN['preset-page-background']<>''){
	        $custom_css.='body{background-image:url('.$MB_VAN['preset-page-background'].');}';
		  }
		}
		if($MB_VAN['background-cover']=='0'){
	        $custom_css.='body{background-size:auto;background-repeat:repeat;}';
		}
		if($MB_VAN['inverse-color']=='1'){
	        $custom_css.='.intro-content h1,.intro-content p,.btn,.effect-moveleft.animate .outer-nav a{color:#fff;}';
			$custom_css.='#aline,.btn{border-color:#fff;}';
			$custom_css.='.btn:after{background:#fff;}';
			$custom_css.='.btn:hover{color:#333;}';
			$custom_css.='#default_top #logo a,#default_top #description{color:#fff;}';
			$custom_css.='.book{box-shadow:3px 1px 13px #000;}';
		}
		if($MB_VAN['default_menu_button']=='1'){
			$custom_css.='#phone-menu-default .menu-button-default{display:none;}';
		}
		
		if($MB_VAN['book-cover']['url']<>'' && isset($MB_VAN['book-cover']['url'])){
			$custom_css.='.no-csstransforms3d .book, .no-js .book, .front{
				background: url('.$MB_VAN['book-cover']['url'].');
				background: -webkit-linear-gradient(left, rgba(0, 0, 0, 0.2) 0%, rgba(255, 255, 255, 0.1) 15%, rgba(255, 255, 255, 0.05) 100%), url('.$MB_VAN['book-cover']['url'].'), #333;
				background: linear-gradient(to right, rgba(0, 0, 0, 0.2) 0%, rgba(255, 255, 255, 0.1) 15%, rgba(255, 255, 255, 0.05) 100%), url('.$MB_VAN['book-cover']['url'].'), #333;
				background-size: cover;
				background-repeat: no-repeat;
				background-position: top;
			}';
		}
		if(isset($MB_VAN['res-book-cover']['url']) && $MB_VAN['res-book-cover']['url']<>''){
			$custom_css.='@media only screen and (-Webkit-min-device-pixel-ratio: 1.5), only screen and (-moz-min-device-pixel-ratio: 1.5), only screen and (-o-min-device-pixel-ratio: 3/2), only screen and (min-device-pixel-ratio: 1.5) {
				.no-csstransforms3d .book, .no-js .book, .front{
				background: url('.$MB_VAN['res-book-cover']['url'].');
				background: -webkit-linear-gradient(left, rgba(0, 0, 0, 0.2) 0%, rgba(255, 255, 255, 0.1) 15%, rgba(255, 255, 255, 0.05) 100%), url('.$MB_VAN['res-book-cover']['url'].'), #333;
				background: linear-gradient(to right, rgba(0, 0, 0, 0.2) 0%, rgba(255, 255, 255, 0.1) 15%, rgba(255, 255, 255, 0.05) 100%), url('.$MB_VAN['res-book-cover']['url'].'), #333;
				background-size: cover;
				background-repeat: no-repeat;
				background-position: top;
			  }
			}';
		}
		if($MB_VAN['book-name-size']<>'3.6em' || $MB_VAN['book-name-size']<>''){
	        $custom_css.='.intro-content h1{font-size:'.$MB_VAN['book-name-size'].';}';
		}
		
		if(isset($MB_VAN['custom_css']) && $MB_VAN['custom_css']<>''){
	    	$custom_css.=stripslashes($MB_VAN['custom_css']);
		}
		$custom_css.=$custom_css_after.PHP_EOL;
		
	    $custom_css = apply_filters('van_custom_css', $custom_css);
	    echo $custom_css;
	}
}

/*Add additional codes to head*/
if( !function_exists( 'van_additional_code') ){
	function van_additional_code() {
	   global $MB_VAN;
	   if(isset($MB_VAN['header_code'])&&$MB_VAN['header_code'] <> ""){  
	     echo stripslashes($MB_VAN['header_code']).PHP_EOL;  
	   }
	}
}

/*Add additional/js codes to footer*/
if( !function_exists( 'van_footer_code') ){
	function van_footer_code() {
	   global $MB_VAN;
	   if(isset($MB_VAN['javascript_code'])&&$MB_VAN['javascript_code'] <> ""){  
	     echo '<script type="text/javascript">'.PHP_EOL;
		 echo '//Custom javascript'.PHP_EOL;
	     echo stripslashes($MB_VAN['javascript_code']).PHP_EOL;  
		 echo '</script>'.PHP_EOL;
	   }
	   if(isset($MB_VAN['footer_code'])&&$MB_VAN['footer_code'] <> ""){  
	   echo stripslashes($MB_VAN['footer_code']); 
	   } 
	}
}

/*Customize meta box*/
function van_customize_meta_boxes() {
     remove_meta_box('postcustom','post','normal');
}
//add_action('admin_init','van_customize_meta_boxes');


/*Add page excerpt textarea*/
function van_page_excerpt_metabox() {
    add_meta_box( 'postexcerpt', __('Excerpt','magicbook'), 'post_excerpt_meta_box', 'page', 'side', 'high' );
}
add_action( 'admin_menu', 'van_page_excerpt_metabox' );
?>