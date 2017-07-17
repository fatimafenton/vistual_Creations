<?php
function magicbook_customize_register( $wp_customize )
	{
		//=====================Fonts======================================
		$wp_customize->add_section( 'fonts_setting' , 
			array(
				'title' => __( 'Fonts', 'magicbook' ),
				'priority' => 25
			) 
		);
																
		include_once 'fonts.php';														
		
		
		$wp_customize->add_setting( 'main_heading_font', 
			array(	
				'default' => 'Tulpen One',											
				'transport' => 'refresh',
				'sanitize_callback' => 'esc_attr'
			) 
		);
																		
		$wp_customize->add_control( 'control_main_heading_font', 
			array(	
				'label' => 'Page Heading Font',
				'section' => 'fonts_setting',
				'settings' => 'main_heading_font',
				'type' => 'select',
				'choices' => $all_fonts 
			) 
		);
																		
		$wp_customize->add_setting( 'body_font', 
			array(	
				'default' => 'Arimo',
				'transport' => 'refresh',
				'sanitize_callback' => 'esc_attr'
			) 
		);
																		
		$wp_customize->add_control( 'control_body_font', 
			array(	
				'label' => 'Body Text Font',
				'section' => 'fonts_setting',
				'settings' => 'body_font',
				'type' => 'select',
				'choices' => $all_fonts 
			) 
		);

	
		$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
		$wp_customize->get_setting( 'main_heading_font' )->transport = 'postMessage';
		$wp_customize->get_setting( 'body_font' )->transport = 'postMessage';
	}
	// end my_customize_register
	
	add_action( 'customize_register', 'magicbook_customize_register' );
	
	function magicbook_customize_css()
	{ 
		?>
	<?php if(get_theme_mod( 'main_heading_font', 'Tulpen One' )<>'Tulpen One' || get_theme_mod( 'body_font', 'Arimo' )<>'Arimo'):?>

	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=<?php echo get_theme_mod( 'main_heading_font', 'Tulpen One' );?>&subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic">

	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=<?php echo get_theme_mod( 'body_font', 'Arimo' );?>&subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic">

	<style type="text/css">
	<?php if(get_theme_mod( 'body_font', 'Arimo' )<>'Arimo'):?>
	body{font-family:<?php echo get_theme_mod( 'body_font', 'Arimo' );?>,Arial, Helvetica, sans-serif;}
	<?php endif;?>
	h1,h2,h3,h4,h5,h6{font-family:<?php echo get_theme_mod( 'main_heading_font', 'Tulpen One' );?>,Arial, Helvetica, sans-serif;}
	</style>
	<?php endif;?>

	<?php
	}
	// end my_customize_css
	
	add_action( 'wp_head', 'magicbook_customize_css' );
	
	
	function magicbook_customize_preview_js()
	{
		wp_enqueue_script( 'my-customizer', get_template_directory_uri() . '/includes/customize/wp-theme-customizer.js', array( 'customize-preview' ), rand(), true );
	}
	
	add_action( 'customize_preview_init', 'magicbook_customize_preview_js' );
?>