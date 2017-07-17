<?php
/* --------------------------
 * Sidebar Menu
 ---------------------------*/
Global $MB_VAN;
?>

 <div id="phone-menu"> <!-- Menu for phone scroll  -->
      <a class="menu-button open-menu">"Show Menu"<div></div><div></div><div></div></a>
    </div>
    
  <div id="menu-wrapper"><!-- Main menu  -->
    	<span id="close-tip"><?php _e('back to cover','magicbook');?></span>
    	<a href="#" id="close-button">×</a>
        <nav class="outer-nav">     
          <?php $menuParameters=array(
				  'theme_location' => 'primary_navi',
				  'container_id' => 'nav-scroll',
				  'echo' => false,
				  'fallback_cb' => 'van_default_menu',
				  'walker'=> new Description_Walker,
                  'depth' => 4
				 );

          // echo strip_tags(wp_nav_menu( $menuParameters ), '<a>' );	
		   echo wp_nav_menu( $menuParameters );	

		 ?>
        </nav>

        <span id="menu-copy"><?php echo $MB_VAN['copyright'];?></span>
  </div><!-- /menu-wrapper  -->