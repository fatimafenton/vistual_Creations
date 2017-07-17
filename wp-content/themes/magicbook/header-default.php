<?php 
global $MB_VAN;
?>
<div id="phone-menu-default">   
   <a class="menu-button-default">"Show Menu"<div></div><div></div><div></div></a>
</div>
<div id="body-container">
<header id="default_top">
  <h1 id="logo"><a href="<?php echo home_url();?>" title="<?php bloginfo('name');?>"><?php if(!isset($MB_VAN['book-logo']['url']) || $MB_VAN['book-logo']['url']==''){
	  		if(!isset($MB_VAN['book-name']) || $MB_VAN['book-name']==''){
	  			   bloginfo('name');
	  		}else{
				   echo $MB_VAN['book-name'];
			}
		}else{
			   echo '<img src="'.$MB_VAN['book-logo']['url'].'" class="logo" />';
		}?></a></h1>
  <div id="description"><?php bloginfo('description');?></div>
  <p class="backtohome"><a href="<?php echo home_url();?>" class="btn"><i class="fa fa-home"></i> <?php _e('Home','magicbook');?></a></p>
</header>