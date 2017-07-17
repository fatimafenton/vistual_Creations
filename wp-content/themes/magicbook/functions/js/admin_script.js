/* Admin javascript */
jQuery(document).ready(function(){
	"use strict";
	
	//Show page options
	jQuery('#portfolio_columns,#google_map,#post_cover').hide();
	var page_template=jQuery('select[name="page_template"]');
	
	function van_page_options(){
	    if(page_template.val()=='page-contact.php'){
             jQuery('#google_map').show().addClass('important_field');
			 jQuery('#portfolio_columns,#page_fullscreen,#page_header_img').hide();
		}else if(page_template.val()=='page-portfolios.php'){
             jQuery('#google_map,#page_fullscreen').hide();
			 jQuery('#portfolio_columns').show().addClass('important_field');
			 jQuery('#page_header_img').show();
		}else if(page_template.val()=='page-homepage.php'){
			 jQuery('#page_fullscreen').show().addClass('important_field');
			 jQuery('#portfolio_columns,#google_map,#page_header_img,#page_title,#page_desc,#hide_title').hide();
		}else if(page_template.val()=='default' || page_template.val()=='page-masonry.php' || page_template.val()=='page-blog.php'){
			 jQuery('#page_header_img').show();
			 jQuery('#portfolio_columns,#page_fullscreen,#google_map').hide();
		}
	}van_page_options();
	
	page_template.change(function(){
	    van_page_options();
	});
	
	//Show post options
	var post_thumbnail=jQuery('input[name=post_thumbnail_value]');
	function van_post_options(){
	    if(jQuery('input[name=post_thumbnail_value]:checked').val()=="Yes"){
		   jQuery('#post_cover').fadeIn();
		}else{
		   jQuery('#post_cover').fadeOut();
		}
	}van_post_options();
	
	post_thumbnail.click(function(){
	   van_post_options();
	})
	
	//Show Portfolio options
	/* Admin javascript */
	
	//Show portfolio options
	jQuery('#portfolio_video').add('#portfolio_audio').add('#portfolio_link').hide();

	var portfolio_type=jQuery('select[name="portfolio_type_value"]');
	function van_portfolio_types(){
	    if(portfolio_type.val()=='image'){
             jQuery('#portfolio_video').add('#portfolio_audio').add('#portfolio_link').hide();
			 jQuery('#portfolio_slider').add('#be_gallery_metabox').add('#portfolio_fullwidth').show();
		}else if(portfolio_type.val()=='video'){
             jQuery('#portfolio_video').show();
			 jQuery('#portfolio_audio').add('#portfolio_slider').add('#portfolio_link').add('#be_gallery_metabox').add('#portfolio_fullwidth').hide();
		}else if(portfolio_type.val()=='audio'){
		     jQuery('#portfolio_audio').show();
			 jQuery('#portfolio_video').add('#portfolio_slider').add('#portfolio_link').add('#be_gallery_metabox').add('#portfolio_fullwidth').hide();
		}else if(portfolio_type.val()=='link'){
		     jQuery('#portfolio_link').show();
			 jQuery('#portfolio_video').add('#portfolio_audio').add('#portfolio_slider').add('#be_gallery_metabox').add('#portfolio_fullwidth').hide();
		}
	}van_portfolio_types();
	
	portfolio_type.change(function(){
	    van_portfolio_types();
	});
	
    jQuery('a#vc_row').closest('li.wpb-layout-element-button').hide();
	
});