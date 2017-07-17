<?php 
/* --------------------------
 * Blog Home Page
 ---------------------------*/
global $MB_VAN;
get_header('default');
?>
<div class="blog-page">
    <?php if(is_category()):?>
    <div class="content-title title-1 page_title"><h2><?php echo single_cat_title( '', false);?></h2></div>
    <?php elseif(is_tag()):?>
    <div class="content-title title-1 page_title"><h2><?php echo __('Tag: ','magicbook'). single_tag_title('', false);?></h2></div>
    <?php elseif(is_day()):?>
    <div class="content-title title-1 page_title"><h2><?php echo __('Date: ','magicbook'). get_the_time('F jS, Y');?></h2></div>
    <?php elseif(is_month()):?>
    <div class="content-title title-1 page_title"><h2><?php echo __('Month: ','magicbook'). get_the_time('F, Y');?></h2></div>
    <?php elseif(is_year()):?>
    <div class="content-title title-1 page_title"><h2><?php echo __('Year: ','magicbook'). get_the_time('Y');?></h2></div>
    <?php elseif(is_search()):?>
    <div class="content-title title-1 page_title"><h2><?php echo __('Search: ','magicbook').$_GET['s'];?></h2></div>
    <?php elseif(is_author()):?>
    <div class="content-title title-1 page_title"><h2><?php the_author();?></h2></div>
    <?php endif;?>
   
    <?php get_template_part('loop','post');?>
</div>

<?php 
get_footer('default');
get_footer();
?>