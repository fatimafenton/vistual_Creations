<?php 
/* --------------------------
 * Portfolios Archive Page
 ---------------------------*/

global $MB_VAN;
get_header();
get_header('default');

$term = get_term_by( 'slug', get_query_var( 'term' ), 
get_query_var( 'taxonomy' ) );
?>

<div class="blog-page">

   <div class="content-title title-1 page_title"><h2><?php echo $term->name;?></h2></div>
   <div class="portfolio-container">
   <?php while (have_posts()) : the_post(); ?>
     <div class="portfolio-item">
       <?php the_post_thumbnail();?>
       <div class="mask">
          <a href="<?php the_permalink();?>"  rel="<?php the_title();?>" class="fancybox">
              <h4><?php the_title();?></h4>
              <p><?php echo van_truncate(get_the_excerpt(),80);?></p>
          </a>
          <a href="<?php the_permalink();?>"  class="more"><i class="fa fa-info-circle"></i><span><?php _e('Read more','magicbook');?></span></a>
       </div>
	 </div>
   <?php endwhile; ?>
   </div>
   
   <?php van_pagenavi();?>
    
</div>
<?php 
get_footer('default');
get_footer();
?>