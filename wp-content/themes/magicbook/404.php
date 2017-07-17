<?php 
/* ---------------------------------
 * Template Name: Blog Archive
 -----------------------------------*/

get_header();?>

<div class="blog-page">
   <div class="content-title title-1 page_title"><h2><?php _e('Oops! 404','magicbook');?></h2></div>
   <div class="post">
        <div class="entry">
           <p align="center"><?php _e('No page found! Maybe this page doesn\'t exist or administrator has already removed it.','magicbook');?></p>
           <p><div class="download-wrapper"><a target="_blank" href="<?php echo home_url();?>"><button class="btn btn-2"><?php _e('Back To Homepage','magicbook');?></button></a></div></p>
           <div class="clearfix"></div>
       </div>
    </div>

    
</div>
<?php get_footer();?>