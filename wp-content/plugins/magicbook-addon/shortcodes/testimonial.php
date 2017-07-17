<?php
vc_map( array(
   "name" => __("MagicBook Testimonial",'magicbook-addon'),
   "base" => "van_testimonial",
   "class" => "wpb_van_testimonial",
   "icon" =>"icon-wpb-van_testimonial",
   "category" => __('MagicBook','magicbook-addon'),
   'admin_enqueue_js' => MBA_DIR_URI.'assets/js/vc.js',
   'admin_enqueue_css' => MBA_DIR_URI.'assets/css/magicbook-vc.css',
   'custom_markup'=>'',
   "params" => array(
      array(
         "type" => "attach_image",
         "holder" => "div",
         "class" => "wpb_van_testimonial_avatar",
         "heading" => __("Avatar",'magicbook-addon'),
         "param_name" => "avatar",
         "value" => '',
         "description" => __("Avatar need to be square.",'magicbook-addon')
      ),
   
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "wpb_van_testimonial_name",
         "heading" => __("Name",'magicbook-addon'),
         "param_name" => "name",
         "value" => '',
         "description" => __("Your client's name.",'magicbook-addon')
      ),
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "wpb_van_testimonial_job",
         "heading" => __("Job",'magicbook-addon'),
         "param_name" => "job",
         "value" => '',
         "description" => __("Your client's job position",'magicbook-addon')
      ),
	  array(
         "type" => "textarea",
         "holder" => "div",
         "class" => "wpb_van_testimonial_content",
         "heading" => __("Content",'magicbook-addon'),
         "param_name" => "content",
         "value" => '',
         "description" => __("Short introduction.",'magicbook-addon')
      )
   )
) );

/*Custom testimonial*/
function van_testimonial_shortcode( $atts, $content) {
   extract(shortcode_atts(array(
		'avatar' => '',
		'name' => '',
		'job'=>''
	), $atts));
   $str=' <div class="testimonials-wrapper">                      
			<div class="testimonials-item">';
			    if($avatar<>''){
				 $str.='<img src="'.wp_get_attachment_url($avatar).'" alt="'.$name.'" />';
				}else{
				  $str.='<img src="'.MBA_DIR.'/assets/images/avatar.png" alt="'.$name.'" />';
				}
				 $str.='<div class="testimonial">                                        
					<p>'.van_shortcode($content).'</p>
					<h4>'.$name.'<span> — '.$job.'</span></h4>
				</div>
			</div>
		 </div>'.PHP_EOL;
   return $str;
}
add_shortcode( 'van_testimonial', 'van_testimonial_shortcode' );
?>