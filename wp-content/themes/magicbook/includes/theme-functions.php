<?php
/** 
 * Theme Functions
 * @package VAN Framework
 */

/* Display default primary menu*/
if( !function_exists( 'van_default_menu') ){
	function van_default_menu() {
		$pages = get_pages('number=1&sort_column=post_date&sort_order=DESC');
		$count = count($pages);
		$default_menu='';
		for($i = 0; $i < $count; $i++)
		{
			$default_menu.= '<a href="#' . $pages[$i]->post_name . '">' . $pages[$i]->post_title . '</a>' . PHP_EOL;
		}
		$return_html = apply_filters('van_default_menu', $default_menu);
	    return $return_html;
	}
}

class description_walker extends Walker_Nav_Menu{
      function start_el(&$output, $item, $depth=0, $args = array() ,$id = 0){
           global $wp_query;
           $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
           $class_names = $value = '';
           $classes = empty( $item->classes ) ? array() : (array) $item->classes;
           $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
           $class_names = ' class="'. esc_attr( $class_names ) . '"';
		   $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
           $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
           $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
           $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
           if($item->object == 'page')
           {
                $varpost = get_post($item->object_id);
               // if(is_home()){
                //  $attributes .= ' href="#' . $varpost->post_name . '"';
               // }else{
                  $attributes .= ' href="'.home_url().'/#' . $varpost->post_name . '"';
               // }
           }
           else
                $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
            $item_output = $args->before;
            $item_output .= '<a'. $attributes .'>';
            $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID );
            $item_output .= $args->link_after;
            $item_output .= '</a>';
            $item_output .= $args->after;
            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args,$id );
     }
}

/* Check navigation menu setting*/
if( !function_exists( 'van_check_menu') ){
    function van_check_menu(){
		//If custom menu exist,get the ID of pages
		if (($locations = get_nav_menu_locations()) && $locations['primary_navi'] ) {
			$menu = wp_get_nav_menu_object( $locations['primary_navi'] );
			$menu_items = wp_get_nav_menu_items($menu->term_id);
			$pageID = array();
			foreach($menu_items as $item) {
				if($item->object == 'page')
					$pageID[] = $item->object_id;
			}
			query_posts( array( 'post_type' => 'page','post__in' => $pageID, 'posts_per_page' => count($pageID), 'orderby' => 'post__in' ) );
		}else {	
		   //If default page menu setting doesn't exist
			query_posts(array( 'post_type' => 'page','posts_per_page'=>1,'orderby' => 'date', 'order' => 'desc') );
		}
	}
}

/*Post meta info*/
if( !function_exists( 'van_posted_on') ){
  function van_posted_on(){
	  global $MB_VAN;
	  $return_html='';
	  $return_html='<div class="blog-tags">';
	  if($MB_VAN['blog_meta']['author'] && get_post_type()=='post'){
		  $return_html.='<span class="author"><a href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'">'.__('By','magicbook').' '.get_the_author().'</a></span>';
	  }
	  if($MB_VAN['blog_meta']['date']){
	      $return_html.='<span class="date">'.get_the_time(get_option('date_format')).'</span>';
	  }
	  if($MB_VAN['blog_meta']['category']){
		  if(get_post_type()=='post'){
		    $return_html.='<span class="category">'.__('in','magicbook').' '.get_the_category_list( ', ' ).'</span>';
		  }elseif(get_post_type()=='portfolio'){
		    $return_html.='<span class="category">'.__('in','magicbook').' '.get_the_term_list( '','portfolios','',', ','' ).'</span>';
		  }
	  }
	  if($MB_VAN['blog_meta']['comment'] && get_post_type()=='post'){
	      $return_html.='<span class="comment">'.van_comments_popup_link( '0', '1', '%' )."</span>";
	  }
	  
	  $return_html.="</div>";
	  $return_html = apply_filters('van_posted_on', $return_html);
	  return $return_html;
  }
}

/*Add nofollow to Read More link for SEO*/
if( !function_exists( 'van_add_nofollow_to_link') ){
	function van_add_nofollow_to_link($link) {
		return str_replace('<a', '<a rel="nofollow"', $link);
	}
}
add_filter('the_content_more_link','van_add_nofollow_to_link', 0);

/*Change excerpt more string*/
if( !function_exists( 'van_excerpt_more') ){
	function van_excerpt_more( $more ) {
		return '...';
	}
}
add_filter( 'excerpt_more', 'van_excerpt_more' );

/*Ajax Effect*/
if( !function_exists( 'van_ajax_post') ){
	function van_ajax_post(){
	    global $MB_VAN;
	    $ajax_class="";
		if($MB_VAN['ajax']=='1'){
		   $ajax_class="ajax";
		}
		return $ajax_class;
	}
}

/*Ajaxify Comments*/
if( !function_exists( 'ajaxify_comments_list') ){
	function ajaxify_comments_list($comment_ID, $comment_status) {
	    global $post;
	    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	        //If AJAX Request Then
	        switch ($comment_status) {
	            case '0':
	                //notify moderator of unapproved comment
	                wp_notify_moderator($comment_ID);
	            case '1': //Approved comment
	                echo "success";
	                $array_a = ARRAY_A;
	                $commentdata = &get_comment($comment_ID, $array_a);
					//print_r( $commentdata);
	              $permaurl = get_permalink( get_the_ID());
	              $url = str_replace('http://', '/', $permaurl);
				  
				  if($commentdata['comment_parent'] == 0){
					$output = '<li class="comment byuser comment-author-admin bypostauthor odd alt thread-odd thread-alt depth-1" id="comment-' . $commentdata['comment_ID'] . '">
					<div id="div-comment-' . $commentdata['comment_ID'] . '" class="comment-body">
					<div class="comment-author vcard">'.
					 get_avatar($commentdata['comment_author_email'],80) 
						.'<cite class="fn">' . $commentdata['comment_author'] . '</cite> <span class="says">says:</span>
				   </div>
	           

	<div class="comment-meta commentmetadata">' .
		get_comment_date( 'F j, Y \a\t g:i a', $commentdata['comment_ID']) .'&nbsp;&nbsp;';
	            if ( is_user_logged_in() ){
				 $output .=  '<a class="comment-edit-link" href="'. home_url() .'/wp-admin/comment.php?action=editcomment&amp;c='. $commentdata['comment_ID'] .'">
				(Edit)</a>';
					}
			   $output .= '</div>
				        <p>' . $commentdata['comment_content'] . '</p>           
						<div class="reply">
							<a class="comment-reply-link" href="'. $url . '&amp;replytocom='. $commentdata['comment_ID'] .'#respond" 
							onclick="return addComment.moveForm(&quot;div-comment-'. $commentdata['comment_ID']  .'&quot;, &quot;'. $commentdata['comment_ID'] . '&quot;, &quot;respond&quot;, &quot;1&quot;)">Reply</a>
						</div>                   
					</div>
				 </li>' ;     
	                          
	               echo $output;
				   
				   }
				   else{			 
				 
				   $output = '<ul class="children"> <li class="comment byuser comment-author-admin bypostauthor even depth-2" id="comment-' . $commentdata['comment_ID'] . '">
	            <div id="div-comment-' . $commentdata['comment_ID'] . '" class="comment-body">
	            <div class="comment-author vcard">'.
	             get_avatar($commentdata['comment_author_email']) 
	                .'<cite class="fn">' . $commentdata['comment_author'] . '</cite> <span class="says">says:</span>           </div>           

	<div class="comment-meta commentmetadata"><a href="http://localhost/WordPress_Code/?p=1#comment-'. $commentdata['comment_ID'] .'">' .
		get_comment_date( 'F j, Y \a\t g:i a', $commentdata['comment_ID']) .'</a>&nbsp;&nbsp;';
	            if ( is_user_logged_in() ){
	         $output .=  '<a class="comment-edit-link" href="'. home_url() .'/wp-admin/comment.php?action=editcomment&amp;c='. $commentdata['comment_ID'] .'">
	        (Edit)</a>';
	            }
				
	       $output .= '</div>
	                <p>' . $commentdata['comment_content'] . '</p>           
					<div class="reply">
						<a class="comment-reply-link" href="'. $url .'&amp;replytocom='. $commentdata['comment_ID'] .'#respond" 
						onclick="return addComment.moveForm(&quot;div-comment-'. $commentdata['comment_ID']  .'&quot;, &quot;'. $commentdata['comment_ID'] . '&quot;, &quot;respond&quot;, &quot;1&quot;)">Reply</a>
					</div>                   
	            </div>
	            </li></ul>' ; 

				echo $output;
				   }
					 
	           $post = &get_post($commentdata['comment_post_ID']);
	                wp_notify_postauthor($comment_ID);
	                break;
	            default:
	                echo "error";
	        }
	        exit;
	    }
	}
}
add_action('comment_post', 'ajaxify_comments_list', 25, 2);

/* Ajax Loading Layer */
function van_ajax_loading(){
   echo '<div class="preloader"></div>
		<div id="ajax-load">
		    <a id="close_button">&times;</a>
		    <div id="ajax-content"></div>
		</div>';
}
add_action('magicbook_after_body','van_ajax_loading');


/**
 * Gallery Metabox - Only show on 'page' and 'rotator' post types
 * @author Bill Erickson
 * @link http://www.wordpress.org/extend/plugins/gallery-metabox
 * @link http://www.billerickson.net/code/gallery-metabox-custom-post-types
 * @since 1.0
 *
 * @param array $post_types
 * @return array
 */
function van_gallery_metabox_page_and_rotator( $post_types ) {
  return array( 'portfolio' );
}
add_action( 'be_gallery_metabox_post_types', 'van_gallery_metabox_page_and_rotator' );
?>