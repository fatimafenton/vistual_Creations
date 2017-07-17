<?php
/**
 * van Common Functions
 * @package VAN Framework
 */

 /* TABLE OF CONTENTS
 *
 * - van_category_root_id($cat)
 * - van_cat_slug($cate_name)
 * - van_cat_name($cate_ID)
 * - van_auto_thumbnail()
 * - van_truncate($full_str,$max_length) 
 * - van_pagenavi()
 * - van_format($content) 
 * - van_content()
 * - van_breadcrumbs()
 * - van_ad()
 * - van_category_tags($cate_slug,$number,$format)
 * - van_is_mobile()
 * - van_social()
 * - van_shortcode()
 * - van_strip_tags()
 * - van_random_string()
 * - van_color_hex2rgba()
 * - van_comments_popup_link()
 /

/*Get sub category ID
 * parameter:
 * $root_cat_id: The parent category id
*/
function van_category_root_id($root_cat_id)   {   
	$current_category = get_category($root_cat_id); 
	while($current_category->category_parent)  {   
	 $current_category = get_category($current_category->category_parent);  
	}
	$term_id=$current_category->term_id;
	$term_id = apply_filters('van_category_root_id', $term_id);
	return $term_id;
}

/*Get category slug
 * Parameter:
 * $cate_name: Category name
*/
function van_cat_slug($cate_name){
	$cat_ID = get_cat_ID($cate_name); 
	$thisCat = get_category($cat_ID);
	$cat_slug = $thisCat->slug;
	$cat_slug = apply_filters('van_cat_slug', $cat_slug);
	return $cat_slug;
}

/*Get category name
 *Parameter:
 *$cate_ID:Category id
*/
function van_cat_name($cate_ID){
	$current_cat = get_category($cate_ID);
	$cate_name = $current_cat->name;
	$cate_name = apply_filters('van_cat_name', $cate_name);
	return $cate_name;
}

/*Get page id by slug
 *Parameter:
 *$page_slug: Page slug
*/
function van_page_id($page_slug) {
    $page = get_page_by_path($page_slug);
    if ($page) {
        return $page->ID;
    } else {
        return null;
    }
}
	
/*Auto get the first images in post content and it will serve for the post thumbnail*/
function van_auto_thumbnail($ex_class) {
    global $post, $posts;
    $first_img = '';
	$html = '';
	$output= '';
    ob_start();
    ob_end_clean();
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', get_the_content(), $matches);
    $first_img = $matches[1][0];
    if(!empty($first_img)){
      $html ='<a href="'.get_permalink().'" title="'.get_the_title().'" class="'.$ex_class.'"><img src="'.$first_img.'" alt="'.get_the_title().'" /></a>';
	}
	$html = apply_filters('van_auto_thumbnail', $html);
    return $html;
}

/*Truncate the long string
 *Parameter:
 *$full_start: The string you want to truncate.
 *$max_length: The max length of output. 
*/
function van_truncate($full_str,$max_length) {
	if (mb_strlen($full_str,'utf-8') > $max_length ) {
	  $full_str = mb_substr($full_str,0,$max_length,'utf-8').'...';
	}
	$full_str = apply_filters('van_truncate', $full_str);
return $full_str;
}

/*Output page navi
 *Parameter:
 *$p: Default page number
*/

function van_pagenavi( $p = 5 ) { 
  if ( is_singular() ) return; 
  global $wp_query, $paged;
  $max_page = $wp_query->max_num_pages;
  if ( $max_page == 1 ) return; 
  if ( empty( $paged ) ) $paged = 1;
  echo '<div class="van-pagenavi">'; 
  //if ( $paged > 1 )  posts_nav_link('','','&laquo; Previous');
  if ( $paged > $p + 1 ) p_link( 1, 'Oldest' );
  if ( $paged > $p + 2 ) echo'<em>...</em>';
  for( $i = $paged - $p; $i <= $paged + $p; $i++ ) { 
	  if ( $i > 0 && $i <= $max_page ) $i == $paged ? print"<span class='current'>{$i}</span> " : p_link( $i );
  }
  if ( $paged < $max_page - $p - 1 ) $return_navi.='<em>...</em>';
  if ( $paged < $max_page - $p ) p_link( $max_page, 'Newest' );
  //if ( $paged < $max_page ) posts_nav_link('','','Next &raquo;');
  echo  '</div>';
}
function p_link( $i, $title = '', $linktype = '' ) {
  if ( $title == '' ) $title = "{$i}";
  if ( $linktype == '' ) { $linktext = $i; } else { $linktext = $linktype; }
  echo "<a class='page' href='", esc_html( get_pagenum_link( $i ) ), "' title='{$title}'>{$linktext}</a> ";
}

/*Output format content*/
function van_content($echo=true,$format=true){
      $content = get_the_content(__('Read More &raquo;','magicbook'));
	  if($format){
	   global $more;
	   $more = 0;
	   $content = apply_filters('the_content', $content);
	   $content = str_replace(']]>', ']]&gt;', $content);
	  }
	  if($echo){
	    print do_shortcode($content);
	  }else{
	    return do_shortcode($content);
	  }
}

/*Breadcrumbs navi*/
function van_breadcrumbs() {
  $delimiter = '&raquo;';
  $name = 'Home';
  $currentBefore = '<span class="current">';
  $currentAfter = '</span>';
  if ( !is_home() && !is_front_page() || is_paged() ) {
    global $post;
    $home = home_url();
    echo '<a href="' . $home . '">' . $name . '</a> ' . $delimiter . ' ';
    if ( is_category() ) {
      global $wp_query;
      $cat_obj = $wp_query->get_queried_object();
      $thisCat = $cat_obj->term_id;
      $thisCat = get_category($thisCat);
      $parentCat = get_category($thisCat->parent);
      if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
      echo $currentBefore;
      single_cat_title();
      echo $currentAfter;
    } elseif ( is_day() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
      echo $currentBefore . get_the_time('d') . $currentAfter;
    } elseif ( is_month() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo $currentBefore . get_the_time('F') . $currentAfter;
    } elseif ( is_year() ) {
      echo $currentBefore . get_the_time('Y') . $currentAfter;
    } elseif ( is_single() && !is_attachment() ) {
      $cat = get_the_category(); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      echo $currentBefore;
      the_title();
      echo $currentAfter;
    } elseif ( is_attachment() ) {
      $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
      echo $currentBefore;
      the_title();
      echo $currentAfter;
    } elseif ( is_page() && !$post->post_parent ) {
      echo $currentBefore;
      the_title();
      echo $currentAfter;
    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
      echo $currentBefore;
      the_title();
      echo $currentAfter;
    } elseif ( is_search() ) {
      echo $currentBefore . 'Search result &#39;' . get_search_query() . '&#39;' . $currentAfter;
    } elseif ( is_tag() ) {
      echo $currentBefore;
      single_tag_title();
      echo $currentAfter;
    } elseif ( is_author() ) {
       global $author;
      $userdata = get_userdata($author);
      echo $currentBefore . 'Author:' . $userdata->display_name . $currentAfter;
    } elseif ( is_404() ) {
      echo $currentBefore . 'Error 404' . $currentAfter;
    }
    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
      echo __('Page','magicbook') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    }
  }
}

/* Taxonomy Breadcrumb */
function be_taxonomy_breadcrumb() {
// Get the current term
$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
 
// Create a list of all the term's parents
$parent = $term->parent;
while ($parent):
$parents[] = $parent;
$new_parent = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ));
$parent = $new_parent->parent;
endwhile;
if(!empty($parents)):
$parents = array_reverse($parents);
 
// For each parent, create a breadcrumb item
foreach ($parents as $parent):
$item = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ));
$url = home_url().'/'.$item->taxonomy.'/'.$item->slug;
echo '<li><a href="'.$url.'">'.$item->name.'</a></li>';
endforeach;
endif;
 
// Display the current term in the breadcrumb
echo '<li>'.$term->name.'</li>';
}

/*Get tags cloud in specified category
 *Parameter:
 *$cate_slug: category slug
 *$number: Number of ouput
 *$format: additional parameter, it will use for pass parameter to next page. 
*/
function van_category_tags($cate_slug='',$number=20,$label='') {
 query_posts('posts_per_page='.$number.'&category_name='.$cate_slug);
  if (have_posts()) :
			  $all_tags_arr=array(); 
			  $tagcloud='<div class="van_widget">';
			  while (have_posts()) :
				the_post();
				$posttags = get_the_tags();
				if ($posttags) {
				  foreach($posttags as $tag) {
				   if(in_array($tag->name,$all_tags_arr)){
					  continue;
				   }else{
					$all_tags_arr[] = $tag->name;
					if($cate_slug<>''){
		               $cat='&cat='.$cate_slug;
					}else{
					   $cat='';
					}
					if($label<>''){
					   $lab='&label='.$label;
					}else{
					   $lab='';
					}
					$tagcloud.='<a href ="'.home_url().'/?tag='.$tag->name.$cat.$lab.'" class="tagclouds-item">'.$tag->name.'</a>';
				   }
				  }
				}
			  endwhile;
			  $tagcloud.='</div>';
   endif;
   wp_reset_query();
   $tagcloud = apply_filters('van_category_tags', $tagcloud);
   return $tagcloud;
}

/*Check user's mobile device*/
function van_is_mobile() {
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
	$is_mobile = false;
	foreach ($mobile_agents as $device) {
		if (stristr($user_agent, $device)) {
			$is_mobile = true;
			break;
		}
	}
	$is_mobile = apply_filters('van_is_mobile', $is_mobile);
	return $is_mobile;
}

/*Social network icons*/
/*function van_social(){
  global $MB_VAN;
  $social='<div class="social-icons alignleft">'.PHP_EOL;
  if(isset($MB_VAN['googlplus']) && $MB_VAN['googlplus']<>'')
	$social.='<a href="'.$MB_VAN['googlplus'].'" title="Google+" class="gplus" target="_blank">Google+</a> '.PHP_EOL;
  if(isset($MB_VAN['facebook']) && $MB_VAN['facebook']<>'')
	$social.='<a href="'.$MB_VAN['facebook'].'" title="Facebook" class="facebook" target="_blank">Facebook</a> '.PHP_EOL;
  if(isset($MB_VAN['twitter']) && $MB_VAN['twitter']<>'')
	$social.=' <a href="'.$MB_VAN['twitter'].'" title="Twitter" class="twitter" target="_blank">Twitter</a> '.PHP_EOL;
  if(isset($MB_VAN['deviantart']) && $MB_VAN['deviantart']<>'')
	$social.='<a href="'.$MB_VAN['deviantart'].'" title="Deviantart" class="deviantart" target="_blank">Deviantart</a>'.PHP_EOL;
  if(isset($MB_VAN['tumblr']) && $MB_VAN['tumblr']<>'')
	$social.='<a href="'.$MB_VAN['tumblr'].'" title="tumblr" class="tumblr" target="_blank">Tumblr</a>  '.PHP_EOL;
  if(isset($MB_VAN['flickr']) && $MB_VAN['flickr']<>'')
	$social.='<a href="'.$MB_VAN['flickr'].'" title="Flickr" class="flickr" target="_blank">Flickr</a> '.PHP_EOL;
  if(isset($MB_VAN['behance']) && $MB_VAN['behance']<>'')
	$social.='<a href="'.$MB_VAN['behance'].'" title="Behance" class="behance" target="_blank">Behance</a> '.PHP_EOL;
  if(isset($MB_VAN['dribbble']) && $MB_VAN['dribbble']<>'')
	$social.='<a href="'.$MB_VAN['dribbble'].'" title="Dribbble" class="dribbble" target="_blank">Dribbble</a> '.PHP_EOL;
  if(isset($MB_VAN['pinterest']) && $MB_VAN['pinterest']<>'')
	$social.='<a href="'.$MB_VAN['pinterest'].'" title="Pinterest" class="pinterest" target="_blank">Pinterest</a>'.PHP_EOL;
  if(isset($MB_VAN['youtube']) && $MB_VAN['youtube']<>'')
	$social.='<a href="'.$MB_VAN['youtube'].'" title="Youtube" class="youtube" target="_blank">Youtube</a>'.PHP_EOL;
  if(isset($MB_VAN['vimeo']) && $MB_VAN['vimeo']<>'')
	$social.='<a href="'.$MB_VAN['vimeo'].'" title="Vimeo" class="vimeo" target="_blank">Vimeo</a>'.PHP_EOL;
  if(isset($MB_VAN['linkedIn']) && $MB_VAN['linkedIn']<>'')
	$social.='<a href="'.$MB_VAN['linkedIn'].'" title="linkedIn" class="linkedIn" target="_blank">linkedIn</a>'.PHP_EOL;
  if(isset($MB_VAN['myspace']) && $MB_VAN['myspace']<>'')
	$social.='<a href="'.$MB_VAN['myspace'].'" title="Myspace" class="myspace" target="_blank">Myspace</a>'.PHP_EOL;
  if(isset($MB_VAN['yahooim']) && $MB_VAN['yahooim']<>'')
	$social.='<a href="ymsgr:sendIM?'.$MB_VAN['yahooim'].'" title="Yahoo IM" class="yahooim" target="_blank">Yahoo IM</a>'.PHP_EOL;
  if(isset($MB_VAN['aim']) && $MB_VAN['aim']<>'')
	$social.='<a href="aim:GoIm?screenname=?'.$MB_VAN['aim'].'" title="AIM" class="aim" target="_blank">AIM</a>'.PHP_EOL;
  if(isset($MB_VAN['meetup']) && $MB_VAN['meetup']<>'')
	$social.='<a href="'.$MB_VAN['meetup'].'" title="meetup" class="meetup" target="_blank">meetup</a> '.PHP_EOL;
  if(isset($MB_VAN['rss']) && $MB_VAN['rss']<>'')
	$social.='<a href="'.$MB_VAN['rss'].'" title="Feed" class="feed" target="_blank">Feed</a> '.PHP_EOL;
  $social.='</div>'.PHP_EOL;
  $social = apply_filters('van_social', $social);
  return $social;
} 
*/
/*Filter shortcode*/
function van_shortcode($content){
   $content = apply_filters('van_shortcode', $content);
   return do_shortcode(strip_tags($content, "<h1><h2><h3><h4><h5><h6><a><img><embed><iframe><form><input><button><object><div><ul><li><ol><table><tbody><tr><td><th><span><p><br/><strong><em><del>"));
}

/*Filter string*/
function van_strip_tags($tagsArr,$str) {   
	foreach ($tagsArr as $tag) {  
		$p[]="/(<(?:\/".$tag."|".$tag.")[^>]*>)/i";  
	}  
	$return_str = preg_replace($p,"",$str);
	$return_str = apply_filters('van_strip_tags', $return_str);
	return $return_str;  
}  

/*Random string*/
function van_random_string($length, $max=FALSE){
  if (is_int($max) && $max > $length){
    $length = mt_rand($length, $max);
  }
  $output = '';
  
  for ($i=0; $i<$length; $i++){
    $which = mt_rand(0,2);
    
    if ($which === 0){
      $output .= mt_rand(0,9);
    }
    elseif ($which === 1){
      $output .= chr(mt_rand(65,90));
    }else{
      $output .= chr(mt_rand(97,122));
    }
  }
  $output = apply_filters('van_random_string', $output);
  return $output;
  
}

/*Color code convert to rgba*/
function van_color_hex2rgba($color, $opacity = false) {

	$default = 'rgb(0,0,0)';

	//Return default if no color provided
	if(empty($color))
          return $default; 

	//Sanitize $color if "#" is provided 
        if ($color[0] == '#' ) {
        	$color = substr( $color, 1 );
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
                return $default;
        }

        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if($opacity){
        	if(abs($opacity) > 1)
        		$opacity = 1.0;
        	$output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
        	$output = 'rgb('.implode(",",$rgb).')';
        }

        //Return rgb(a) color string
		$output = apply_filters('van_color_hex2rgba', $output);
        return $output;
}

function van_comments_popup_link( $zero = false, $one = false, $more = false, $css_class = '', $none = false ) {
    global $wpcommentspopupfile, $wpcommentsjavascript;
 
    $id = get_the_ID();
 
    if ( false === $zero ) $zero = __( 'No Comments','magicbook' );
    if ( false === $one ) $one = __( '1 Comment' ,'magicbook');
    if ( false === $more ) $more = __( '% Comments' ,'magicbook');
    if ( false === $none ) $none = __( 'Comments Off' ,'magicbook');
 
    $number = get_comments_number( $id );
 
    $str = '';
 
    if ( 0 == $number && !comments_open() && !pings_open() ) {
        $str = '<span' . ((!empty($css_class)) ? ' class="' . esc_attr( $css_class ) . '"' : '') . '>' . $none . '</span>';
        return $str;
    }
 
    if ( post_password_required() ) {
        $str = __('Enter your password to view comments.','magicbook');
        return $str;
    }
 
    $str = '<a href="';
    if ( $wpcommentsjavascript ) {
        if ( empty( $wpcommentspopupfile ) )
            $home = home_url();
        else
            $home = get_option('siteurl');
        $str .= $home . '/' . $wpcommentspopupfile . '?comments_popup=' . $id;
        $str .= '" onclick="wpopen(this.href); return false"';
    } else { // if comments_popup_script() is not in the template, display simple comment link
        if ( 0 == $number )
            $str .= get_permalink() . '#respond';
        else
            $str .= get_comments_link();
        $str .= '"';
    }
 
    if ( !empty( $css_class ) ) {
        $str .= ' class="'.$css_class.'" ';
    }
    $title = the_title_attribute( array('echo' => 0 ) );
 
    $str .= apply_filters( 'comments_popup_link_attributes', '' );
 
    $str .= ' title="' . esc_attr( sprintf( __('Comment on %s','magicbook'), $title ) ) . '">';
    $str .= van_comments_number_str( $zero, $one, $more );
    $str .= '</a>';
     
	$str = apply_filters('van_comments_popup_link', $str);
    return $str;
}

function van_comments_number_str( $zero = false, $one = false, $more = false, $deprecated = '' ) {
    if ( !empty( $deprecated ) )
        _deprecated_argument( __FUNCTION__, '1.3' );
 
    $number = get_comments_number();
 
    if ( $number > 1 )
        $output = str_replace('%', number_format_i18n($number), ( false === $more ) ? __('% Comments','magicbook') : $more);
    elseif ( $number == 0 )
        $output = ( false === $zero ) ? __('No Comments','magicbook') : $zero;
    else // must be one
        $output = ( false === $one ) ? __('1 Comment','magicbook') : $one;
 
    return apply_filters('comments_number', $output, $number);
}
?>