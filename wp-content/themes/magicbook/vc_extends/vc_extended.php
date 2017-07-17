<?php
/**
* Initialize Visual Composer
*/

add_action('init','magicbook_vc_init');
function magicbook_vc_init(){
	if ( function_exists( 'vc_map')){
		vc_set_as_theme();

		/*Remove exist shortcode*/
		vc_remove_element("vc_teaser_grid");
		vc_remove_element("vc_posts_grid");
		vc_remove_element("vc_widget_sidebar");
		vc_remove_element("vc_posts_slider");
		vc_remove_element("vc_pie");
		vc_remove_element("vc_line_chart");
		vc_remove_element("vc_round_chart");
		vc_remove_element("vc_cta_button");
		vc_remove_element("vc_cta_button2");
		vc_remove_element("vc_images_carousel"); 
		vc_remove_element("vc_carousel");
		vc_remove_element("vc_video");
		vc_remove_element("vc_wp_posts");
		vc_remove_element("vc_wp_calendar");
		vc_remove_element("vc_wp_pages");
		vc_remove_element("vc_wp_custommenu");
		vc_remove_element("vc_wp_text");
		vc_remove_element("vc_wp_links");
		vc_remove_element("vc_wp_categories");
		vc_remove_element("vc_wp_archives");
		vc_remove_element("vc_wp_search");
		vc_remove_element("vc_wp_recentcomments");
		vc_remove_element("vc_wp_tagcloud");
		vc_remove_element("vc_wp_rss");
		vc_remove_element("vc_wp_meta");

		/*Disable frontend Interface*/
		vc_disable_frontend();

		/*Override the new template folder*/
		$dir = get_template_directory() . '/vc_extends/';
		vc_set_shortcodes_templates_dir($dir);

		/*--------Add Options to single image----------*/
		vc_add_param("vc_single_image", array(
				"type" => "dropdown",
				"class" => "",
				"heading" => esc_html__("Cover Picutre",'magicbook'),
				"param_name" => "cover",
				"value" => array(
				esc_html__("No",'magicbook') => "",
				esc_html__("Yes",'magicbook') => "1",	
			)
		));

		/*--------Add Options to separate text----------*/
		vc_add_param("vc_text_separator", array(
				"type" => "dropdown",
				"class" => "",
				"heading" => esc_html__("Font Size",'magicbook'),
				"param_name" => "font_size",
				"value" => array(
				esc_html__("Big",'magicbook') => "",
				esc_html__("Medium",'magicbook') => "medium",
				esc_html__("Small",'magicbook') => "small",	
			)
		));
	}
}

/*Set the default editor to Visual composer*/
add_action('vc_after_init','magicbook_default_vc_editor',100);
function magicbook_default_vc_editor() {
 if ( function_exists( 'vc_map')){
	$vcGroupAccess = vc_settings()->get('groups_access_rules');
	$vcGroupAccess["administrator"]["show"] = "only";
	vc_settings()->set('groups_access_rules', $vcGroupAccess);
 }
}
