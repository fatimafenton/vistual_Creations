<?php global $MB_VAN;?>
<footer id="default_footer"><?php echo $MB_VAN['copyright'];?></footer>
</div>
<?php 
if(!is_home()){
   get_template_part('default','menu');
}
?>