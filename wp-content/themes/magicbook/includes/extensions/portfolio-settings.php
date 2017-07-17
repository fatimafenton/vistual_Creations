<?php
ob_start();
/**
 * Portfolio Post Type
 * @package VAN Framework
 */

$van_portfolio_meta_boxes =
array(
	"portfolio_type" => array(
	"name" => "portfolio_type",
	"types" => "select",
	'std'=>'image',
	'desc'=>'',
	'options'=>array('image','video','audio'),
	"title" => esc_html__('portfolio type','magicbook')),
	
	"portfolio_slider" => array(
	"name" => "portfolio_slider",
	"types" => "radio",
	'std'=>'No',
	'options'=>array('Yes','No'),
	'desc'=>esc_html__('After you disabled the slideshow, all images will be laid end to end.','magicbook'),
	"title" => esc_html__('Disable the slideshow in this portfolio ','magicbook')),

	"portfolio_fullwidth" => array(
	"name" => "portfolio_fullwidth",
	"types" => "radio",
	'std'=>'No',
	'options'=>array('Yes','No'),
	'desc'=>'',
	"title" => esc_html__('Display full width Slider.','magicbook')),
	
	"portfolio_video" => array(
	"name" => "portfolio_video",
	"types" => "textarea",
	'std'=>'',
	'desc'=>'<a href="'.get_template_directory_uri().'/includes/extensions/help/video.jpg'.'" target="_blank">'.esc_html__('How to get Youtube/Vimeo code?','magicbook').'</a>',
	"title" => esc_html__("Youtube or Vimeo Iframe Embed Code",'magicbook')),
	
	"portfolio_audio" => array(
	"name" => "portfolio_audio",
	"types" => "textarea",
	'std'=>'',
	'desc'=>'<a href="'.get_template_directory_uri().'/includes/extensions/help/sc.jpg'.'" target="_blank">'.esc_html__('Example: How to get SoundCloud code?','magicbook').'</a> - <a href="http://soundcloud.com" target="_blank">'.esc_html__('Go to SoundCloud now!','magicbook').'</a>',
	"title" => esc_html__("SoundCloud or MixCloud Iframe Embed Code",'magicbook'))
);

function van_portfolios_meta_boxes() {
    global $post, $van_portfolio_meta_boxes;
    foreach($van_portfolio_meta_boxes as $meta_box) {
        $meta_box_value = get_post_meta($post->ID, $meta_box['name'].'_value', true);
        if($meta_box_value == "")
            $meta_box_value = $meta_box['std'];
        echo'<div id="'.$meta_box['name'].'" class="metabox">';
		echo'<input type="hidden" name="'.$meta_box['name'].'_noncename" id="'.$meta_box['name'].'_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
        echo'<h4>'.$meta_box['title'].'</h4>';
		switch($meta_box['types']){
		  case 'textarea':
            echo '<textarea cols="80" rows="3" name="'.$meta_box['name'].'_value" style="width:70%;height:100px;">'.$meta_box_value.'</textarea><br /><span>'.$meta_box['desc'].'</span>';
			break;
		  case 'text':
		    echo '<input type="text" name="'.$meta_box['name'].'_value" value="'.$meta_box_value.'" style="width:20%" /><span>'.$meta_box['desc'].'</span>';
			break;
		  case 'colorpicker':
		    echo '<input type="text" id="'.$meta_box['name'].'_value" name="'.$meta_box['name'].'_value" value="'.$meta_box_value.'" style="width:10%" /><span>'.$meta_box['desc'].'</span>';
			echo ' <div class="colorpicker" id="colorpicker_'.$meta_box['name'].'_value"></div>';
			?>
            <script type="text/javascript">
			 jQuery(document).ready(function() {
				jQuery('#colorpicker_<?php echo $meta_box['name']; ?>_value').farbtastic("#<?php echo $meta_box['name']; ?>_value");
				jQuery("#<?php echo $meta_box['name']; ?>_value").click(function(){jQuery('#colorpicker_<?php echo $meta_box['name']; ?>_value').slideToggle()});
				
			  });
			 </script>
            <?php
			break;
	      case 'select':
		    echo '<select name="'.$meta_box['name'].'_value">';
			if(!empty($meta_box['options']))
			{
				foreach ($meta_box['options'] as $option)
				{
					 $select = '';
				     if($meta_box_value==$option) {
					  $select = 'selected=selected"';
				     }
					echo '<option value="'.$option.'" '.$select.'>'.$option.'</option>';
				}
			}
			echo'</select><span>'.$meta_box['desc'].'</span>';
			break;
		 case 'checkbox':
		    foreach ($meta_box['options'] as $option){
				$checkyes = '';
				if(strpos($meta_box_value, $option) !== false) {
					$checkyes = 'checked="checked"';
				}
		       echo'<input type="checkbox" name="'.$meta_box['name'].'_value[]" value="'.$option.'" '.$checkyes.' /> '.$option.'&nbsp;&nbsp;';
			}
			echo'<span>'.$meta_box['desc'].'</span>';
			break;
		 case 'radio':
		    foreach ($meta_box['options'] as $option){
		  ?>
		       <input type="radio" name="<?php echo $meta_box['name']?>_value" value="<?php echo $option;?>" <?php if ($option==$meta_box_value) { echo ' checked="checked"'; } ?> /> <?php echo $option; ?> &nbsp;&nbsp;
		  <?php	
            }
			echo'<span>'.$meta_box['desc'].'</span>';
			break;
		}
		echo'</div>';
		?>
        <?php
    }
}

function van_portfolios_create_meta_box() {
    global $theme_name;

    if ( function_exists('add_meta_box') ) {
        add_meta_box( 'portfolios-meta-boxes', __('Portfolio Setting','magicbook'), 'van_portfolios_meta_boxes', 'portfolio', 'normal', 'high' );
    }
}

function van_portfolios_save_postdata( $post_id ) {
    global $post, $van_portfolio_meta_boxes;

    foreach($van_portfolio_meta_boxes as $meta_box) {
		
		if ( ! isset( $_POST[$meta_box['name'].'_noncename'] ) ) {
		  return;
	    }
		
        if (!wp_verify_nonce($_POST[$meta_box['name'].'_noncename'], plugin_basename(__FILE__) ))  {
            return $post_id;
        }

        if ( 'page' == $_POST['post_type'] ) {
            if ( !current_user_can( 'edit_page', $post_id ))
                return $post_id;
        } 

        else {
            if ( !current_user_can( 'edit_post', $post_id ))
                return $post_id;
        }

        //$data = $_POST[$meta_box['name'].'_value'];
		if ($meta_box['types'] == 'checkbox' && is_array($_POST[$meta_box['name'].'_value'])) {
			$data = implode(",",$_POST[$meta_box['name'].'_value']);
	    }else{
			$data = $_POST[$meta_box['name'].'_value'];
		}

        if(get_post_meta($post_id, $meta_box['name'].'_value') == "")
            add_post_meta($post_id, $meta_box['name'].'_value', $data, true);
        elseif($data != get_post_meta($post_id, $meta_box['name'].'_value', true))
            update_post_meta($post_id, $meta_box['name'].'_value', $data);
        elseif($data == "")
            delete_post_meta($post_id, $meta_box['name'].'_value', get_post_meta($post_id, $meta_box['name'].'_value', true));
    }
}

add_action('admin_menu', 'van_portfolios_create_meta_box');
add_action('save_post', 'van_portfolios_save_postdata');
?>