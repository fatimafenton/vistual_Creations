<?php 
/* ---------------------------------
 * Template Name: Portfolios Archive
 -----------------------------------*/

get_header();
get_header('default');
?>

<div class="blog-page">
   <?php 
   while (have_posts()) : the_post(); 
     echo '<div class="content-title title-1 page_title"><h2>'.get_the_title().'</h2></div>';
     echo van_shortcode('[van_portfolios posts_per_page=6]');
   endwhile;
   ?>
</div>

<?php 
get_footer('default');
get_footer();
?>