<?php 
/* --------------------------
 * Portfolio Single Page
 ---------------------------*/
global $MB_VAN;
get_header();
get_header('default');
?>

    <div class="blog-page">
        <?php 
		  while (have_posts()) : the_post(); 
		   $portfolio_type=get_post_meta($post->ID, "portfolio_type_value", true);
		   $portfolio_slider=get_post_meta($post->ID, "portfolio_slider_value", true);

		   /* Detect the fullwidth container of the portfolio page. */
		   $portfolio_fullwidth=get_post_meta($post->ID, "portfolio_fullwidth_value",true);
		   $fullwidth_css = '';
		   if($portfolio_fullwidth=='Yes' && $portfolio_slider=='No'){
				$fullwidth_css = 'mb_fullwidth';
		   }
		?>
         <div id="ajax-body">
          <div class="post <?php echo $fullwidth_css;?>">
           
	               <?php
					if(empty($portfolio_type)){$portfolio_type="image";}
					if($portfolio_type=="image"){
						if ( $images = get_children(array(
						'post_parent' => get_the_ID(),
						'post_type' => 'attachment',
						'numberposts' => 50,
						'order' => 'ASC',
						'orderby' => 'menu_order',
						'exclude' => get_post_thumbnail_id(),
						'post_mime_type' => 'image',)))
						{
				   ?>
	               <div class="portfolio-gallery">
		               <div <?php if($portfolio_slider=='No'):?>id="portfolio-slider-<?php the_ID();?>"<?php endif;?> class="portfolio-slider<?php if($portfolio_slider=='No'):?> flexslider<?php endif;?> loading">
		                 <ul<?php if($portfolio_slider=='No'):?> class="slides"<?php endif;?>>
		                   <?php
							 foreach( $images as $image ) {
								$attachmenturl=wp_get_attachment_url($image->ID);
								$attachmentimage=wp_get_attachment_image($image->ID, 'full');
								$img_title = $image->post_title;
								$img_desc = $image->post_excerpt;
								$img_caption = $image->post_content;
								echo '<li><a href="'.$attachmenturl.'" rel="group-'.$post->ID.'" class="single_image" kesrc="'.$attachmenturl.'">'.$attachmentimage.'</a></li>';
							 }
					       ?>
		                 </ul>
		                </div>
	               	</div>
	               <?php
						}
					}elseif($portfolio_type=="video"){
					  $portfolio_video=trim(get_post_meta($post->ID, "portfolio_video_value", true));
					  echo '<div>'.stripslashes($portfolio_video).'</div>';
					}elseif($portfolio_type=="audio"){
					  $portfolio_audio=trim(get_post_meta($post->ID, "portfolio_audio_value", true));
					  echo '<div>'.stripslashes($portfolio_audio).'</div>';
					}
				   ?>
				   <div class="wrapper_960">
			            <h2><?php the_title();?></h2>
			            <?php echo van_posted_on();?>
			            <div class="entry">
			               <?php 
						    van_content(true,true);
						    wp_link_pages('before=<div class="link_pages">&after=</div>&next_or_number=number&pagelink=%');
						   ?>
			            </div>
			            <div class="clearfix"></div>
		           </div>

	          </div>

	          <div class="wrapper_960">
	          	<?php
		            if($MB_VAN['enable_comment']['portfolio']){
				       comments_template(); 
				     }
	          	?>
	          </div>
        
         </div>
         <?php wp_footer();?>
        <?php endwhile;?> 
        
    </div>

<?php 
get_footer('default');
get_footer();
?>