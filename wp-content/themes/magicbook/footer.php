<?php 
/* --------------------------
 * Footer
 ---------------------------*/
global $MB_VAN;
wp_footer();
echo '<div id="global_footer_text">'.$MB_VAN['global_footer_text'].'</div>';
?>
 
<!-- Say no to IE 8 ...  -->
<!--[if lt IE 9]>
	<div id="say-no-to-ie8"><h3>Sorry, we no longer support your browser anymore :(</h3><h5>Please update it to later version, just like:</h5><span><a target="_blank" href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">Newer IE</a></span>|<span><a target="_blank" href="https://www.mozilla.org/en-US/firefox/new/#">Firefox</a></span>|<span><a target="_blank" href="http://www.opera.com/computer/windows">Opera</a></span>|<span><a target="_blank" href="https://www.google.com/intl/en/chrome/browser/">Chrome</a></span></div>
<![endif]-->
</body>
</html>