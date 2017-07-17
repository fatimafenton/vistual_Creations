<?php
/** 
 * Theme Initialize
 * @package VAN Framework
 * You can initialize this theme functions like menu,sidebar,thumbnail size,post format and so on.
 * And you can also include more advanced extendsions like custom post type or widgets here. 
 */

/*Portfolio settings*/
require_once(get_template_directory()."/includes/extensions/portfolio-settings.php"); 

/*Load Functions*/
require_once(get_template_directory()."/includes/theme-functions.php");

/*Add live customize support*/
require_once(get_template_directory() . '/includes/customize/customize.php');

/*Demo Importer*/
require_once(get_template_directory()."/includes/demo/demo-importer.php");  

/*Remove the default gallery style*/
add_filter( 'use_default_gallery_style', '__return_false' );

/*Add title tag support*/
add_theme_support( "title-tag" );

/*Add some useful support*/
add_editor_style('editor-style.css');
add_theme_support( 'automatic-feed-links' );

/**
 * Load Google Fonts
 */
add_action( 'wp_enqueue_scripts', 'van_font_styles' );
function van_font_styles() {
    wp_enqueue_style( 'van-default-fonts', van_fonts_url(), array(), null );
}
function van_fonts_url() {
    $fonts_url = '';
    $tulpen_one = _x( 'on', 'Tulpen One font: on or off', 'magicbook' );
    $roboto = _x( 'on', 'Roboto: on or off', 'magicbook' );
    $arimo = _x( 'on', 'Arimo: on or off', 'magicbook' );
    $raleway = _x( 'on', 'Raleway: on or off', 'magicbook' );
    $marvel = _x( 'on', 'Marvel: on or off', 'magicbook' );
    
    if ('off' !== $tulpen_one || 'off' !== $roboto || 'off' !== $arimo || 'off' !== $raleway || 'off' !== $marvel) {
            $font_families = array();
         
            if ( 'off' !== $roboto ) {
                $font_families[] = 'Roboto:100,300,400,400italic,500,500italic,700,700italic,900,900italic';
            }
            
            if ( 'off' !== $tulpen_one ) {
                $font_families[] = 'Tulpen One';
            }
            
            if ( 'off' !== $arimo ) {
                $font_families[] = 'Arimo:400,700';
            }

            if ( 'off' !== $raleway ) {
                $font_families[] = 'Raleway:400,700,500';
            }

            if ( 'off' !== $marvel ) {
                $font_families[] = 'Marvel:400,700';
            }
            
            $query_args = array(
            'family' => urlencode( implode( '|', $font_families ) ),
            'subset' => urlencode( 'latin,latin-ext,vietnamese,cyrillic-ext,cyrillic,greek,greek-ext' ),
        );
 
        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
        }
    
    return esc_url_raw( $fonts_url );
}

/*Multiple Languages*/
load_theme_textdomain( 'magicbook', get_template_directory().'/languages' ); 
$locale = get_locale(); 
$locale_file = get_template_directory_uri()."/languages/$locale.php"; 
if ( is_readable($locale_file) ) require_once($locale_file);

if ( ! isset( $content_width ) ) $content_width = 960;
add_filter('widget_text', 'do_shortcode');

/*Add diffierent size for post thumbnails*/
add_theme_support('post-thumbnails');
if ( function_exists( 'add_image_size')){  
	add_image_size('portfolio_thumbnail', 800, 800,true);
}

/*Init nav menus*/
register_nav_menus(array('primary_navi' => 'Primary Menu'));


/*Set the default layout to two columns*/
add_filter( 'default_content', 'magicbook_editor_content', 10, 2 );
function magicbook_editor_content( $content, $post ) {
    switch( $post->post_type ) {
        case 'post':
            $content = '';
        break;
        case 'page':
            $content = '[vc_row][vc_column width="1/2"][/vc_column][vc_column width="1/2"][/vc_column][/vc_row]';
        break;
    }
    return $content;
}

/*Add the sample page.*/
if (isset($_GET['activated']) && is_admin()){
        wp_delete_post(2,true);

        $new_page_title = 'Example Page';
        $new_page_content = '[vc_row][vc_column width="1/2"][vc_text_separator title="Instruction " title_align="separator_align_center" color="grey" style="double"][vc_column_text css_animation="bottom-to-top"]Hi,friend!

Thanks for you are using MagicBook theme! This is a sample page which will tell you how to create a book page like the demo does.

- When you edit this page, you will see the Visual Composer page builder interface, there are two columns represent respectively the left side and the right side of the book page, then, just click "+" button to add the elements into the book page which you want.

- Go to Appearance > Menus, create your own custom menu and set the "Theme locations" to "Primary Menu" . That\'s it.

You can delete this page and create your own pages after you got it.

Finally, hope you will enjoy in MagicBook Theme!
[/vc_column_text][/vc_column][vc_column width="1/2"][vc_text_separator title="Recent Blog" title_align="separator_align_center" color="grey" style="double"][van_blog posts_per_page="5"][/vc_column][/vc_row]'; 
		
	    $page_check = get_page_by_title($new_page_title);
        $new_page = array(
		        'post_id' => '2',
                'post_type' => 'page',
                'post_title' => $new_page_title,
                'post_content' => $new_page_content,
                'post_status' => 'publish',
                'post_author' => 1,
        );
        if(!isset($page_check->ID)){
                $new_page_id = wp_insert_post($new_page);				
        }
}
?>