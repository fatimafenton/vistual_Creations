<?php
vc_map( array(
   "name" => __("MagicBook Timeline",'magicbook-addon'),
   "base" => "van_timeline",
   "class" => "wpb_van_timeline",
   "icon" =>"icon-wpb-van_timeline",
   "category" => __('MagicBook','magicbook-addon'),
   'admin_enqueue_js' => MBA_DIR_URI.'assets/js/vc.js',
   'admin_enqueue_css' => MBA_DIR_URI.'assets/css/magicbook-vc.css',
   'custom_markup'=>'',
   "params" => array(
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "wpb_van_timeline_time",
         "heading" => __("Time",'magicbook-addon'),
         "param_name" => "time",
         "value" => '',
         "description" => __("For example, 2011-2012",'magicbook-addon')
      ),
   
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "wpb_van_timeline_title",
         "heading" => __("Title",'magicbook-addon'),
         "param_name" => "title",
         "value" => '',
         "description" => __("Item title.",'magicbook-addon')
      ),
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "wpb_van_timeline_subtitle",
         "heading" => __("Subtitle",'magicbook-addon'),
         "param_name" => "subtitle",
         "value" => '',
         "description" => __("Small subtitle below the first title.",'magicbook-addon')
      ),
	  array(
         "type" => "textarea",
         "holder" => "div",
         "class" => "wpb_van_timeline_content",
         "heading" => __("Content",'magicbook-addon'),
         "param_name" => "content",
         "value" => '&nbsp;',
         "description" => __("Short introduction.",'magicbook-addon')
      )
   )
) );

/*Custom Timeline*/
function van_timeline_shortcode( $atts, $content) {
   extract(shortcode_atts(array(
		'title' => '',
		'subtitle' => '',
		'time'=>''
	), $atts));
	
   $hoveron=''; 

   $str='<ul class="book-timeline">
			<li>
				<span class="time-data">'.$time.'</span>
				<div class="time-dot"></div>
				<div class="time-block">
					<h4>'.$title.'</h4>
					<h5>'.$subtitle.'</h5>
					<p>'.van_shortcode($content).'</p>
				</div>
			</li>
    </ul>'.PHP_EOL;
   return $str;
}
add_shortcode( 'van_timeline', 'van_timeline_shortcode' );
?>