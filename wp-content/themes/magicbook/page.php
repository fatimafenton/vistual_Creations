<?php 
/* --------------------------
 * Default Page
 ---------------------------*/
global $MB_VAN;
get_header();
get_header('default');
?>

<div class="blog-page">

    <?php while (have_posts()) : the_post(); ?>
    <div id="ajax-body">
      <div class="content-title title-1 page_title"><h2><?php the_title();?></h2></div>
      <div class="post">
        <div class="entry">
           <?php 
		   the_excerpt();
		   wp_link_pages('before=<div class="link_pages">&after=</div>&next_or_number=number&pagelink=%');
		   ?>
           <div class="clearfix"></div>
       </div>
      </div>
     
     <?php 
	 if($MB_VAN['enable_comment']['page']){
       comments_template(); 
     }
     ?>
     <?php wp_footer();?>
    </div>
    <?php endwhile;?> 
</div>

<?php 
get_footer('default');
get_footer();
?>