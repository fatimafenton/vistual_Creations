<?php
vc_map( array(
   "name" => __("MagicBook Portfolios",'magicbook-addon'),
   "base" => "van_portfolios",
   "class" => "wpb_van_portfolios",
   "icon" =>"icon-wpb-van_portfolios",
   "category" => __('MagicBook','magicbook-addon'),
   'admin_enqueue_js' => MBA_DIR_URI.'assets/js/vc.js',
   'admin_enqueue_css' => MBA_DIR_URI.'assets/css/magicbook-vc.css',
   'custom_markup'=>'',
   "params" => array(

      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "wpb_van_portflio_number",
         "heading" => __("Number of portfolios to show",'magicbook-addon'),
         "param_name" => "posts_per_page",
         "value" => 8,
         "description" => __('Number of posts you want to display.','magicbook-addon')
      ),
      
      array(
         "type" => "dropdown",
         "holder" => "div",
         "class" => "wpb_van_portfolio_open",
         "heading" => __("Open Method",'magicbook-addon'),
         "param_name" => "open",
		 "value" => array('ajax','lightbox'),
         "description" => __("Open the portfolio post as lightbox or ajax.",'magicbook-addon')
      ),
	  
	  array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "wpb_van_portfolio_categories",
         "heading" => __("From which categories?",'magicbook-addon'),
         "param_name" => "portfolios",
         "value" => '',
         "description" => __('If you leave it empty, it will display the portfolios from all categories, Please note, you should add category slug here, please separate multiple category slugs with English commas,for example: slug-1,slug-2 ','magicbook-addon')
      ),
	  
   )
) );

/*Custom Portfolios Post loop with pagination*/
if( !function_exists( 'van_portfolios_shortcode') ){
	function van_portfolios_shortcode( $atts ){
		global $post;
		
		$portfolio_object_id=van_random_string(10);
		$html ='';
		static $van_custom_loop;
		if( !isset($van_custom_loop) )
			$van_custom_loop = 1;
		else
			$van_custom_loop ++;

		$atts = shortcode_atts( array(
			'paging'		=> 'paginate'. $van_custom_loop,
			'post_type' 		=> 'portfolio',
			'posts_per_page' 	=> '100000000',
			'post_status' 		=> 'publish',
			'portfolios'     => '',
			'open' => 'ajax'
		), $atts );

		$paging = $atts['paging'];
		unset( $atts['paging'] );
		
		$open = $atts['open'];
		unset( $atts['open'] );

		if( isset($_GET[$paging]) )
			$atts['paged'] = $_GET[$paging];
		else
			$atts['paged'] = 1;

		$custom_query = new WP_Query( $atts );

		$pagination_base = add_query_arg( $paging, '%#%',home_url());

		if( $custom_query->have_posts() ):
		    $html = van_portfolios_filter($portfolio_object_id,'portfolios',$atts['portfolios'],false); 
		    $html .= '<div id="portfolio-container-'.$portfolio_object_id.'"" class="portfolio-container">';
			
			while( $custom_query->have_posts()) : $custom_query->the_post();
			$terms = get_the_terms($post->ID,'portfolios');
			$image_id = get_post_thumbnail_id($post->ID);
			$link= get_permalink();
			$class=van_ajax_post();
			$readmore='<a href="'.get_permalink().'"  target="_blank" class="more '.$class.'"><i class="fa fa-info-circle"></i><span>'.__('Read more','magicbook').'</span></a>';
			
			if($open=='lightbox'){
				$link_array=wp_get_attachment_image_src($image_id, 'full', true);
				$link=$link_array[0];
				$class='lightbox';
				$readmore='';
			}
			
			$slug=array();
			if ( $terms && ! is_wp_error( $terms ) ){
				foreach($terms as $term) {
					$slug[] = $term->slug;
				}
			}
			$on_slug = join( " ", $slug);
			
			$html .= sprintf( 
				'<div class="portfolio-item '.$on_slug.'">
				     %4$s
					 <div class="mask">
						<a href="%1$s" target="_blank"  rel="portfolio" class="fancybox %7$s" rel="'.$on_slug.'">
							<h4>%2$s</h4>
							<p>%3$s</p>
						</a>
						%8$s
					 </div>
				 </div>',
				$link,
				get_the_title(),
				van_truncate(get_the_excerpt(),80),
				get_the_post_thumbnail($post->ID, 'portfolio_thumbnail'),
				van_posted_on(),
				__('Read more','magicbook'),
				$class,
				$readmore
				
			);
			endwhile;

			
			if(!is_home()):
				$html .= '<br class="clearfix" /><div class="van_pagination">';
				$html .= paginate_links( array(
					'type' 		=> 'portfolio',
					'base' 		=> $pagination_base,
					'format' 	=> '?'. $paging .'=%#%',
					'current' 	=> max( 1, $custom_query->get('paged') ),
					'total' 	=> $custom_query->max_num_pages
				));
				$html.='</div>';
		    endif;
		    $html.='</div>';
		    $html.="<script type='text/javascript'>
		 jQuery(function ($) {
		    $('#portfolio-filter-".$portfolio_object_id." li').on('click',function(){
				var selector = $(this).attr('data-filter');
				$('#portfolio-container-".$portfolio_object_id."').isotope({ filter: selector });
			});
		 });
		 </script>";
		endif;
		
		return $html;
	}
}
add_shortcode( 'van_portfolios', 'van_portfolios_shortcode' );

/*Portfolios filter*/
if( !function_exists( 'van_portfolios_filter') ){
	function van_portfolios_filter($portfolio_object_id,$taxonomy,$category_slug='',$echo=true){
		if($category_slug<>''){
		   $category_slug_array=explode(',',$category_slug);
		   $include='';
		   for($i=0;$i<count($category_slug_array);$i++){
			 $terms = get_term_by('slug', $category_slug_array[$i], 'portfolios'); 
			 $include .= $terms->term_id.',';
		   }
		   $include = trim($include, ',');
		}else{
		   $include='';
		}
		$terms = get_terms($taxonomy,array('hide_empty'=>true,'parent'=>0,'include'=>$include));
		$count = count($terms);

		$return_html='';
		if($count > 0){
	     $return_html.='<ul id="portfolio-filter-'.$portfolio_object_id.'"" class="portfolio-filters">
				<li class="active" data-filter="*">'.__('All','magicbook').'</li>';
		 foreach($terms as $term) {
			$return_html .= '<li data-filter=".'.$term->slug.'">'.$term->name.'</li>';
		 }
		 $return_html.='</ul>';
		}
		if($echo)
			{
				echo $return_html;
			}
			else
			{
				return $return_html;
		}
	}
}
?>