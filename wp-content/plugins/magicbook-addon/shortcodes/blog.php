<?php
vc_map( array(
   "name" => __("MagicBook Blog",'magicbook-addon'),
   "base" => "van_blog",
   "class" => "wpb_van_blog",
   "icon" =>"icon-wpb-van_blog",
   "category" => __('MagicBook','magicbook-addon'),
   'admin_enqueue_js' => MBA_DIR_URI.'assets/js/vc.js',
   'admin_enqueue_css' => MBA_DIR_URI.'assets/css/magicbook-vc.css',
   'custom_markup'=>'',
   "params" => array(
	 
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "wpb_van_blog_number",
         "heading" => __("Number of posts to show",'magicbook-addon'),
         "param_name" => "posts_per_page",
         "value" =>  5,
         "description" => __('Number of posts you want to display.','magicbook-addon')
      ),

      array(
         "type" => "dropdown",
         "holder" => "div",
         "class" => "wpb_van_blog_order",
         "heading" => __("Post Order",'magicbook-addon'),
         "param_name" => "order",
         "value" => array('desc','asc'),
         "description" => __('The post is ordered by post data, you can choose desc or asc.','magicbook-addon')
      ),
	  
	  array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "wpb_van_blog_categories",
         "heading" => __("From which categories?",'magicbook-addon'),
         "param_name" => "category_name",
         "value" => '',
         "description" => __('If you leave it empty, it will display the posts from all categories, Please note, you should add category slug here, please separate multiple category slugs with English commas,for example: slug-1,slug-2 ','magicbook-addon')
      ),
	  
   )
) );


/*Custom Blog Post loop with pagination*/
if( !function_exists( 'van_blog_shortcode') ){
	function van_blog_shortcode( $atts ){
		
		static $van_custom_loop;
		if( !isset($van_custom_loop) )
			$van_custom_loop = 1;
		else
			$van_custom_loop ++;

		$atts = shortcode_atts( array(
			'paging'		=> 'paginate'. $van_custom_loop,
			'post_type' 		=> 'post',
			'posts_per_page' 	=> '10000000',
			'post_status' 		=> 'publish',
			'category_name'     => '',
			'orderby'			=> 'date',
			'order'				=> 'desc'
		), $atts );

		$paging = $atts['paging'];
		unset( $atts['paging'] );

		if( isset($_GET[$paging]) )
			$atts['paged'] = $_GET[$paging];
		else
			$atts['paged'] = 1;

		$custom_query = new WP_Query( $atts );

		$pagination_base = add_query_arg( $paging, '%#%',home_url());


		if( $custom_query->have_posts() ):
			$html = '<div class="blog-wrapper">';
		
		    while( $custom_query->have_posts()) : $custom_query->the_post();
			
				global $MB_VAN;
				$thumbnail='';
				$more='<a class="more-link '.van_ajax_post().'" href="'.get_permalink().'">'.__('Continue to read','magicbook-addon').'</a>';
				  
				if(isset($MB_VAN['blog_cover']) && $MB_VAN['blog_cover']=="1"){
				   $thumbnail='<a class="blog-banner '.van_ajax_post().'" href="'.get_permalink().'">'.get_the_post_thumbnail(get_the_ID(),'large').'</a>';
				   
				}
				
				$html .= sprintf( 
					'<div class="blog-item" id="post-'.get_the_ID().'">
					   %4$s  
					   <a href="%1$s" title="%2$s" class="%7$s"><h3>%2$s</h3></a>
					   %5$s
					   <p class="article">%3$s</p>
					   %6$s
					 </div>',
					get_permalink(),
					get_the_title(),
					get_the_excerpt(),
					$thumbnail,
					van_posted_on(),
					$more,
					van_ajax_post()
				);
				
		    endwhile;
			   
		   if(!is_home()):
				$html .= '<div class="van_pagination">';
				$html .= paginate_links( array(
					'type' 		=> '',
					'base' 		=> $pagination_base,
					'format' 	=> '?'. $paging .'=%#%',
					'current' 	=> max( 1, $custom_query->get('paged') ),
					'total' 	=> $custom_query->max_num_pages
				));
				$html.='</div>';
			endif;
		$html.='</div>';	
				
		endif;

		return $html;
	}
}
add_shortcode( 'van_blog', 'van_blog_shortcode' );
?>