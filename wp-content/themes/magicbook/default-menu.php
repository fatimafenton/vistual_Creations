<?php 
/* --------------------------
 * Default menu
 ---------------------------*/
global $MB_VAN;
if($MB_VAN['default_menu_button']=='0' || !isset($MB_VAN['default_menu_button'])):?>
<!-- Pushy Menu -->
<nav class="pushy pushy-right">
    <h4><?php _e('Content','magicbook');?> <span href="javascript:void(0);" class="pushy-close">x</span></h4>
    <ul>
         <?php $menuParameters=array(
				  'theme_location' => 'primary_navi',
				  'container_id' => 'pushy_menu',
				  'echo' => false,
				  'fallback_cb' => 'van_default_menu',
				  'walker'=> new Description_Walker,
                  'depth' => 4
				 );

          // echo strip_tags(wp_nav_menu( $menuParameters ), '<a>' );	
		   echo wp_nav_menu( $menuParameters );	
		 ?>
    </ul>
</nav>
<!-- Site Overlay -->
<div class="site-overlay"></div>
<?php endif;?>