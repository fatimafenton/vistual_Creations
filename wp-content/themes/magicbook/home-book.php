<?php
global $MB_VAN,$post;
?>
<div id="main-loading"></div>

<div id="phone-menu-default">   
   <a class="menu-button-default">"Show Menu"<div></div><div></div><div></div></a>
</div>

<div id="scroll-wrap" class="main">

<div class="book" data-book="book-1"><!-- Small Book -->
    <a href="#" ><img src="<?php echo get_template_directory_uri();?>/img/necessity/page-corner.png" id="page-corner" alt="corner" /></a>
</div> 

<div class="intro-wrapper">
      <div class="intro-content">
         <h1><?php if(!isset($MB_VAN['book-logo']['url']) || $MB_VAN['book-logo']['url']==''){
			   echo $MB_VAN['book-name'];
			 }else{
			   echo '<img src="'.$MB_VAN['book-logo']['url'].'" class="logo" />';
			 }?></h1>
         <div id="aline"></div>
         
         <?php if(isset($MB_VAN['book-introduction']) || $MB_VAN['book-introduction']<>''):?>
         <p><?php echo $MB_VAN['book-introduction'];?></p>
         <?php endif;?>
         
         <?php 
		   $button_text='Read me';
		  if(isset($MB_VAN['book-button-text']) || $MB_VAN['book-button-text']<>''){
		    $button_text=$MB_VAN['book-button-text'];
		  }
		 ?>
          <button id="open-it" class="btn"><?php echo $button_text;?></button>
      </div>
</div>
   
</div><!-- /scroll-wrap -->     

<div id="top-perspective" class="effect-moveleft"><!-- Fullscreen BookBlock -->
	<div id="top-wrapper">           
		<div class="bb-custom-wrapper" id="book-1">
			<div class="bb-bookblock">
                 <?php 
				 //If user haven't actived the visual composer plugin
				 if(is_plugin_active('js_composer/js_composer.php')===false){
					$args = array( 
						'numberposts' => 1, 
						'orderby' => 'post_date',
						'post_type'=>'page',
						'order'=>'DESC',
					);
				    $posts = get_posts($args);
					foreach($posts as $post){
					 setup_postdata($post);
					 $content=get_the_content();
				     echo '<div class="bb-item"> 
                    <div class="bb-custom-side">
                        <div class="content-wrapper">
                            <div class="container">
							   <p align="center"><img src="'.get_template_directory_uri().'/img/warning.png" width="200" /></p>
                               <div class="content-title title-3"><h3 style="color:#f00">'.esc_html__('Visual Composer Plugin Needed','magicbook').'</h3></div>
							   <p>'.esc_html__('<strong>Please install and active the Visual Composer plugin!</strong> The MagicBook Theme is depend on Visual Composer page builder, or the HTML structure will be incorrect and the book framework will not work. The pages will appear as normal until the Visual Composer will be actived.','magicbook').'</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bb-custom-side">
                        <div class="content-wrapper">
                            <div class="container">
							   <div class="content-title title-3"><h3>'.get_the_title().'</h3></div>
							  '.do_shortcode($content).'
                            </div>
                        </div>
                    </div>
                    
                    </div>';
				   }
				 }else{
				 
					 van_check_menu();
					 while (have_posts()) : the_post();
					   $content=get_the_content();
					   
					   //Detect the content structure is correct or not.
					   if(strpos($content,'[vc_row]')!== false){
						   van_content(true,true);
					   }else{
						   echo '<div class="bb-item"> 
						<div class="bb-custom-side">
							<div class="content-wrapper">
								<div class="container">
									  <p align="center"><img src="'.get_template_directory_uri().'/img/warning.png" width="200" /></p>
                               <div class="content-title title-3"><h3 style="color:#f00">'.__('This Page Need to Rebuild','magicbook').'</h3></div>
							   <p>'.esc_html__('<strong>Please rebuild this page with Visual Composer page builder!</strong> The old content does not support the two columns layout, because it is not include [vc_row] and [vc_column] shortcode which are from Visual Composer, so you must to edit this page via the Visual Composer and make it from one column to two columns or just create a new page to instead of this page, then the pages will appear as normal.','magicbook').'</p>
								</div>
							</div>
						</div>
						
						<div class="bb-custom-side"> 
							<div class="content-wrapper">
								<div class="container">
								 <div class="content-title title-3"><h3>'.get_the_title().'</h3></div>
							     '.do_shortcode($content).'
								</div>
							</div>
						</div>
						
					    </div>';
					 }
					 endwhile;
					 wp_reset_query();
				 }
				 ?>
			</div><!-- /bb-bookblock -->
		</div><!-- /bb-custom-wrapper -->        
	</div><!-- /top-wrapper  -->
    <?php get_sidebar();?>
</div><!-- /top-perspective  -->