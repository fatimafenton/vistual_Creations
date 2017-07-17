    <?php while (have_posts()) : the_post(); ?>
     <div <?php post_class('blog-item');?> id="post-<?php the_ID();?>">
        <?php if(has_post_thumbnail() && isset($MB_VAN['blog_cover']) && $MB_VAN['blog_cover']=="1"):?>
         <a class="blog-banner" href="<?php the_permalink();?>"><?php the_post_thumbnail('large');?></a>
        <?php endif;?>
        <a href="<?php the_permalink();?>" title="<?php the_title();?>"><h2><?php the_title();?></h2></a>
        <?php echo van_posted_on();?>
        <div class="entry">
           <?php 
		   if(isset($MB_VAN['blog_cover']) && $MB_VAN['blog_cover']=="1"){
		     echo '<p>'.get_the_excerpt().'</p>';
			 echo '<a class="more-link '.van_ajax_post().'" href="'.get_permalink().'">'.__('Continue to read','magicbook').'</a>';
		   }else{
		     van_content(true,true);
		   }
		   ?>
       </div>
     </div>
    <?php endwhile;?> 
    
    <?php the_posts_pagination( array( 'mid_size' => 2 ) ); van_pagenavi();?>