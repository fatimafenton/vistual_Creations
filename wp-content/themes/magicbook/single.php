<?php 
/* --------------------------
 * Single Page
 ---------------------------*/
global $MB_VAN;
get_header();
get_header('default');
?>
<div class="blog-page">
    <div class="blog-header">
      <?php previous_post_link('&laquo; %link'); ?>
      <?php next_post_link('&raquo; %link'); ?> 
    </div>
    <?php while (have_posts()) : the_post(); ?>
    <div id="ajax-body">
      <div class="post wrapper_960">
        <h2><?php the_title();?></h2>
        <?php echo van_posted_on();?>
        <div class="entry">
           <?php 
		   van_content(true,true);
		   wp_link_pages('before=<div class="link_pages">&after=</div>&next_or_number=number&pagelink=%');
		   ?>
           <div class="clearfix"></div>
       </div>
      </div>

      <div class="wrapper_960">
       <?php 
       if($MB_VAN['enable_comment']['post']){
         comments_template(); 
       }
       ?>
      </div>
      <?php wp_footer();?>
    </div>
    <?php endwhile;?> 
</div>


<?php 
get_footer('default');
get_footer();
?>