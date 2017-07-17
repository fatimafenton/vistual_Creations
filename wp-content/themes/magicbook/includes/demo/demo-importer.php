<?php
/*Import data*/
if ( ! function_exists( 'magicbook_import_files' ) ) :
function magicbook_import_files() {
    return array(
        array(
            'import_file_name'             => 'Illustrator',
            'local_import_file'            => trailingslashit( get_template_directory() ) . 'includes/demo/illustrator/content.xml',
            'import_preview_image_url'     => trailingslashit( get_template_directory_uri() ) . 'includes/demo/illustrator/screenshot.png',
            'import_notice'                => __( 'Please waiting for a few minutes, do not close the window or refresh the page until the data is imported.', 'magicbook' ),
        ),

        array(
            'import_file_name'             => 'Fashion Designer',
            'local_import_file'            => trailingslashit( get_template_directory() ) . 'includes/demo/fashion_designer/content.xml',
            'local_import_redux'           => array(
                array(
                    'file_path'   => trailingslashit( get_template_directory() ) . 'includes/demo/fashion_designer/options.json',
                    'option_name' => 'magicbook_opt',
                ),
            ),
            'import_preview_image_url'     => trailingslashit( get_template_directory_uri() ) . 'includes/demo/fashion_designer/screenshot.png',
            'import_notice'                => __( 'Please waiting for a few minutes, do not close the window or refresh the page until the data is imported.', 'magicbook' ),
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'magicbook_import_files' );
endif;


if ( ! function_exists( 'magicbook_after_import' ) ) :
function magicbook_after_import( $selected_import ) {
    if ( 'Illustrator' === $selected_import['import_file_name'] ) {
        //Set Menu
        $primary_menu = get_term_by('name', 'Side Menu', 'nav_menu');
        set_theme_mod( 'nav_menu_locations' , 
            array( 
                  'primary_navi' => $primary_menu->term_id
                 ) 
        );
    }elseif ( 'Fashion Designer' === $selected_import['import_file_name'] ) {
         //Set Menu
        $primary_menu = get_term_by('name', 'Primary Menu', 'nav_menu');
        set_theme_mod( 'nav_menu_locations' , 
            array( 
                  'primary_navi' => $primary_menu->term_id
                 ) 
        );
    }

}
add_action( 'pt-ocdi/after_import', 'magicbook_after_import' );
endif;