<?php
vc_map( array(
   "name" => __("MagicBook Feature",'magicbook-addon'),
   "base" => "van_feature",
   "class" => "wpb_van_feature",
   "icon" =>"icon-wpb-van_feature",
   "category" => __('MagicBook','magicbook-addon'),
   'admin_enqueue_js' => MBA_DIR_URI.'assets/js/vc.js',
   'admin_enqueue_css' => MBA_DIR_URI.'assets/css/magicbook-vc.css',
   'custom_markup'=>'',
   "params" => array(
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "wpb_van_feature_title",
         "heading" => __("Feature title",'magicbook-addon'),
         "param_name" => "title",
         "value" => '',
         "description" => __("Feature item title.",'magicbook-addon')
      ),
      array(
         "type" => "attach_image",
         "holder" => "div",
         "class" => "wpb_van_feature_icon",
         "heading" => __("Feature icon",'magicbook-addon'),
         "param_name" => "image",
         "value" => '',
         "description" => __("Upload the avatar image here.",'magicbook-addon')
      ),
	  array(
         "type" => "textarea",
         "holder" => "div",
         "class" => "wpb_van_feature_content",
         "heading" => __("Feature Content",'magicbook-addon'),
         "param_name" => "content",
         "value" => '',
         "description" => __("Short introduction.",'magicbook-addon')
      ),
	  array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "wpb_van_feature_link",
         "heading" => __("Icon Link",'magicbook-addon'),
         "param_name" => "href",
         "value" => '',
         "description" => __("Don't forget add 'http://' in front of the link.",'magicbook-addon')
      ),
	  array(
         "type" => "dropdown",
         "holder" => "div",
         "class" => "wpb_van_feature_target",
         "heading" => __("Link target",'magicbook-addon'),
         "param_name" => "target",
		 "value" => array('_self','_blank'),
         "description" => __("Open the link in new tab/window or not, select '_blank' - open in new window, select '_self' - open in same window",'magicbook-addon')
      )
   )
) );

/*Feature*/
function van_feature_shortcode( $atts, $content) {
   extract(shortcode_atts(array(
		'title' => '',
		'image' => '',
		'image_url'=>'',
		'href' => '',
		'target' => '_self'
	), $atts));

   $str='<div class="services-item">'.PHP_EOL;
   if($image<>'' || $image_url<>''){
	 if($href<>''){
	   $str.='<a href="'.$href.'" target="'.$target.'">'.PHP_EOL;
	 }
	 if($image==''){
		$str.='<img src="'.$image_url.'" alt="'.$title.'" />'.PHP_EOL;
	 }else{
		$str.='<img src="'.wp_get_attachment_url($image).'" alt="'.$title.'" />'.PHP_EOL;
	 }
	 if($href<>''){
	   $str.='</a>'.PHP_EOL;
	 } 
   }
   $str.='<p>'.PHP_EOL;
   if($title<>''){
   $str.='<span>'.$title.' :</span>'.PHP_EOL;
   }
   $str.=van_shortcode($content).PHP_EOL;
   $str.='</p>'.PHP_EOL;
   $str.='</div>'.PHP_EOL;
   return $str;
}
add_shortcode( 'van_feature', 'van_feature_shortcode' );
?>