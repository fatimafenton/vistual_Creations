<?php
/* --------------------------
 * Default Index Page
 ---------------------------*/
global $MB_VAN;
get_header();
get_template_part('default','menu');

echo "<div id='body-container'>";
if($MB_VAN['book-homepage']=='1'){
  get_template_part('home','book');
}else{
  get_template_part('home','blog');
}
get_footer();
echo'</div>';
?>