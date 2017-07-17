<?php

/**
  ReduxFramework Sample Config File
  For full documentation, please visit: https://docs.reduxframework.com
 * */


if (!class_exists('admin_folder_Redux_Framework_config')) {

    class admin_folder_Redux_Framework_config {

        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if (!class_exists('ReduxFramework')) {
                return;
            }

            // This is needed. Bah WordPress bugs.  ;)
            if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }

        }

        public function initSettings() {

            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            // If Redux is running as a plugin, this will remove the demo notice and links
            add_action( 'redux/loaded', array( $this, 'remove_demo' ) );
            
            // Function to test the compiler hook and demo CSS output.
            // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
            //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 2);
            
            // Change the arguments after they've been declared, but before the panel is created
            //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
            
            // Change the default value of a field after it's been set, but before it's been useds
            //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
            
            // Dynamically add a section. Can be also used to modify sections/fields
            //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        /**

          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field	set with compiler=>true is changed.

         * */
        function compiler_action($options, $css) {
            //echo '<h1>The compiler hook has run!';
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

            /*
              // Demo of how to use the dynamic CSS and write your own static CSS file
              $filename = dirname(__FILE__) . '/style' . '.css';
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
                require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }

              if( $wp_filesystem ) {
                $wp_filesystem->put_contents(
                    $filename,
                    $css,
                    FS_CHMOD_FILE // predefined mode settings for WP files
                );
              }
             */
        }

        /**

          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.

          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons

         * */
        function dynamic_section($sections) {
            //$sections = array();
            $sections[] = array(
                'title' => __('Section via hook', 'magicbook'),
                'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'magicbook'),
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }

        /**

          Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.

         * */
        function change_arguments($args) {
            //$args['dev_mode'] = true;

            return $args;
        }

        /**

          Filter hook for filtering the default value of any given field. Very useful in development mode.

         * */
        function change_defaults($defaults) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }

        // Remove the demo link and the notice of integrated demo from the redux-framework plugin
        function remove_demo() {

            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
            }
        }

        public function setSections() {

            /**
              Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
             * */
            // Background Patterns Reader
            $sample_patterns_path   = get_template_directory() . '/img/patterns/';
            $sample_patterns_url    = get_template_directory_uri() . '/img/patterns/';

            $sample_patterns        = array();

            if (is_dir($sample_patterns_path)) :

                if ($sample_patterns_dir = opendir($sample_patterns_path)) :
                    $sample_patterns = array();

                    while (( $sample_patterns_file = readdir($sample_patterns_dir) ) !== false) {

                        if (stristr($sample_patterns_file, '.png') !== false || stristr($sample_patterns_file, '.jpg') !== false) {
                            $name = explode('.', $sample_patterns_file);
                            $name = str_replace('.' . end($name), '', $sample_patterns_file);
                            $sample_patterns[]  = array('alt' => $name, 'img' => $sample_patterns_url . $sample_patterns_file);
                        }
                    }
                endif;
            endif;
			

            ob_start();

            $ct             = wp_get_theme();
            $this->theme    = $ct;
            $item_name      = $this->theme->get('Name');
            $tags           = $this->theme->Tags;
            $screenshot     = $this->theme->get_screenshot();
            $class          = $screenshot ? 'has-screenshot' : '';

            $customize_title = sprintf(__('Customize &#8220;%s&#8221;', 'magicbook'), $this->theme->display('Name'));
            
            ?>
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
            <?php if ($screenshot) : ?>
                <?php if (current_user_can('edit_theme_options')) : ?>
                        <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                            <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview','magicbook'); ?>" />
                        </a>
                <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview','magicbook'); ?>" />
                <?php endif; ?>

                <h4><?php echo $this->theme->display('Name'); ?></h4>

                <div>
                    <ul class="theme-info">
                        <li><?php printf(__('By %s', 'magicbook'), $this->theme->display('Author')); ?></li>
                        <li><?php printf(__('Version %s', 'magicbook'), $this->theme->display('Version')); ?></li>
                        <li><?php echo '<strong>' . __('Tags', 'magicbook') . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
            <?php
            if ($this->theme->parent()) {
                printf(' <p class="howto">' . __('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.','magicbook') . '</p>', __('http://codex.wordpress.org/Child_Themes', 'magicbook'), $this->theme->parent()->display('Name'));
            }
            ?>

                </div>
            </div>

            <?php
            $item_info = ob_get_contents();

            ob_end_clean();

            $sampleHTML = '';
            if (file_exists(dirname(__FILE__) . '/info-html.html')) {
                /** @global WP_Filesystem_Direct $wp_filesystem  */
                global $wp_filesystem;
                if (empty($wp_filesystem)) {
                    require_once(ABSPATH . '/wp-admin/includes/file.php');
                    WP_Filesystem();
                }
                $sampleHTML = $wp_filesystem->get_contents(dirname(__FILE__) . '/info-html.html');
            }

            // ACTUAL DECLARATION OF SECTIONS

			 $this->sections[] = array(
                'title'     => __('Global Setting', 'magicbook'),
                'desc'      => __('', 'magicbook'),
                'icon'      => 'el-icon-website',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    array(
                        'id'        => 'book-homepage',
                        'type'      => 'button_set',
						'multi'    => false,
                        'title'     => __('Enable The Book Template for Homepage', 'magicbook'),
                        'subtitle'  => __('After you enable it, the homepage will be a book like page, or it will only appear the blogroll by default.', 'magicbook'),
						'options'  => array(
							'1' => 'Yes',
							'0' => 'No'
						),
                        'default'   => '1'
                    ),


                    array(
                        'id'        => 'ajax',
                        'type'      => 'button_set',
						'multi'    => false,
                        'title'     => __('Open Post With Ajax Method', 'magicbook'),
                        'subtitle'  => __('After you enable the Ajax method, the single post will be opened without refresh.', 'magicbook'),
						'options'  => array(
							'1' => 'Yes',
							'0' => 'No'
						),
                        'default'   => '1'
                    ),
                    
                    array(
                        'id'        => 'flip_direction',
                        'type'      => 'button_set',
						'multi'    => false,
                        'title'     => __('Page Flipping Direction', 'magicbook'),
                        'subtitle'  => __('LTR: left to right, RTL: right to left', 'magicbook'),
						'options'  => array(
							'rtl' => 'LTR',
							'ltr' => 'RTL'
						),
                        'default'   => 'ltr'
                    ),
                    
                    array(
                        'id'        => 'default_menu_button',
                        'type'      => 'button_set',
						'multi'    => false,
                        'title'     => __('Remove Default Menu Button', 'magicbook'),
                        'subtitle'  => __('The default menu button is placed at the top right of screen', 'magicbook'),
						'options'  => array(
							'1' => 'Yes',
							'0' => 'No'
						),
                        'default'   => '1'
                    ),

					
				   array(
                        'id'        => 'favicon',
                        'type'      => 'media',
						'url'       => true,
                        'title'     => __('Upload Your Own Favicon', 'magicbook'),
					    'compiler'  => 'true',
                        'desc'      => '',
                        'subtitle'  => __('The favicon will displayed in the front of the browser address bar.', 'magicbook'),
						'default'   => '',
                    ),
                    
                    array(
                        'id'        => 'global_footer_text',
                        'type'      => 'text',
						'multi'    => false,
                        'title'     => __('Global Footer Text', 'magicbook'),
                        'subtitle'  => __('This footer text will show up in the bottom of all the pages.', 'magicbook'),
                        'default'   => ''
                    ),
                   
                    array(
                        'id'        => 'copyright',
                        'type'      => 'text',
						'multi'    => false,
                        'title'     => __('Copyright', 'magicbook'),
                        'subtitle'  => __('Copyright  text will displayed at the bottom of the side menu.', 'magicbook'),
                        'default'   => '&copy; MagicBook Theme.'
                    ),
           
					
                    
                ),
            );
			
			$this->sections[] = array(
                'title'     => __('Cover Settings', 'magicbook'),
                'desc'      => __('', 'magicbook'),
                'icon'      => 'el-icon-home',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(

                    array(
                        'id'        => 'book-cover',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Book Cover', 'magicbook'),
                        'compiler'  => 'true',
                        //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                        'desc'      => __('Recommend size is 370 x 560px', 'magicbook'),
                        'subtitle'  => __('Upload your own picture for the magic book cover', 'magicbook'),
                        'default'   => array('url' => get_template_directory_uri().'/img/default_cover.jpg'),
                        //'hint'      => array(
                        //    'title'     => 'Hint Title',
                        //    'content'   => 'This is a <b>hint</b> for the media field with a Title.',
                        //)
                    ),
					
					array(
                        'id'        => 'res-book-cover',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Book Cover for High Resolution', 'magicbook'),
                        'compiler'  => 'true',
                        //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                        'desc'      => __('Recommend size is 740 x 1120px', 'magicbook'),
                        'subtitle'  => __('Upload your a double size cover picture for retina screen', 'magicbook'),
                        'default'   => array('url' => get_template_directory_uri().'/img/default_cover@2x.jpg'),
                        //'hint'      => array(
                        //    'title'     => 'Hint Title',
                        //    'content'   => 'This is a <b>hint</b> for the media field with a Title.',
                        //)
                    ),
					
					array(
                        'id'        => 'preset-page-background',
                        'type'      => 'select_image',
                        'title'     => __('Preset Page Background Picture', 'magicbook'),
                        'desc'      => '',
                        'subtitle'  => __('Select the preset page background picture below', 'magicbook'),
						'options'  => $sample_patterns,
						'default'   => get_template_directory_uri().'/img/patterns/watercolor.jpg',
                    ),
                   
                    array(
                        'id'        => 'page-background',
                        'type'      => 'media',
						'url'       => true,
                        'title'     => __('Custom Page Background Picture', 'magicbook'),
					    'compiler'  => 'true',
                        'desc'      => '',
                        'subtitle'  => __('Upload your own picture for the page background', 'magicbook'),
						'default'   => '',
                    ),
					
					array(
                        'id'        => 'background-cover',
                        'type'      => 'button_set',
						'multi'    => false,
                        'title'     => __('Force The Background Picture to Cover The Full Screen', 'magicbook'),
                        'subtitle'  => __('If you want to make the background picture to repeat, please select No.', 'magicbook'),
						'options'  => array(
							'1' => 'Yes',
							'0' => 'No'
						),
                        'default'   => '1'
                    ),
					
					array(
                        'id'        => 'inverse-color',
                        'type'      => 'button_set',
						'multi'    => false,
                        'title'     => __('Inverse The Font Color', 'magicbook'),
                        'subtitle'  => __('The default font color is deep grey, when you click Yes, the font color will be white.', 'magicbook'),
						'options'  => array(
							'1' => 'Yes',
							'0' => 'No'
						),
                        'default'   => '0'
                    ),

					array(
                        'id'        => 'book-name',
                        'type'      => 'text',
                        'title'     => __('Book Name', 'magicbook'),
                        'desc'      => '',
                        'subtitle'  => __('Write your book name to instead of MagicBook text', 'magicbook'),
						'default'   => 'magicbook',
                    ),
					
					array(
                        'id'        => 'book-name-size',
                        'type'      => 'text',
                        'title'     => __('The Font Size of Book Name', 'magicbook'),
                        'desc'      => __('You can go to "Appearance > Customize" to change the font of book name.', 'magicbook'),
                        'subtitle'  => __('The default size is 3.6em, you can also use px unit.', 'magicbook'),
						'default'   => '3.6em',
                    ),
					
			        array(
                        'id'        => 'book-logo',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Book LOGO', 'magicbook'),
                        'compiler'  => 'true',
                        //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                        'desc'      => __('', 'magicbook'),
                        'subtitle'  => __('Upload your own logo to instead of the book name', 'magicbook'),
                        'default'   => ''
                    ),
				
					
					array(
                        'id'        => 'book-introduction',
                        'type'      => 'textarea',
						'validate' => 'html',
						'allowed_html' => array(
							'a' => array(
								'href' => array(),
								'title' => array()
							),
							'br' => array(),
							'em' => array(),
							'strong' => array()
						),
                        'title'     => __('Book Introduction', 'magicbook'),
                        'desc'      => __('About 140 words, only allow to use a, br, em, strong HTML tags.', 'magicbook'),
                        'subtitle'  => __('Write a short introduction for your book', 'magicbook'),
						'default'   => 'MagicBook is a real 3D flip book template with unlimited page number,cool 3D menu, responsive feature and easy customization etc. ',
                    ),
					
					array(
                        'id'        => 'book-button-text',
                        'type'      => 'text',
                        'title'     => __('Button Text', 'magicbook'),
                        'desc'      => '',
                        'subtitle'  => __('The default text is Read me', 'magicbook'),
						'default'   => 'Read me',
                    ),
					
					array(
                        'id'        => 'preset-loader',
                        'type'      => 'image_select',
                        'title'     => __('Preset Loader Pictures', 'magicbook'),
                        'desc'      => '',
                        'subtitle'  => __('Select a loader picture below', 'magicbook'),
						'options'  => array(
							'1'      => array(
								'alt'   => '1', 
								'img'   => get_template_directory_uri().'/img/necessity/loadinfo.net-1.gif'
							),
							'2'      => array(
								'alt'   => '2', 
								'img'   => get_template_directory_uri().'/img/necessity/loadinfo.net-2.gif'
							),
							'3'      => array(
								'alt'   => '3', 
								'img'   => get_template_directory_uri().'/img/necessity/loadinfo.net-3.gif'
							),
							'4'      => array(
								'alt'   => '4', 
								'img'   => get_template_directory_uri().'/img/necessity/loadinfo.net-4.gif'
							),
							'5'      => array(
								'alt'   => '5', 
								'img'   => get_template_directory_uri().'/img/necessity/loadinfo.net-5.gif'
							),
							'6'      => array(
								'alt'  => '6', 
								'img'   => get_template_directory_uri().'/img/necessity/loadinfo.net-6.gif'
							),
							'7'      => array(
								'alt'  => '7', 
								'img'   => get_template_directory_uri().'/img/necessity/loadinfo.net-7.gif'
							)
						),
						'default' => '1'
                    ),
                    
                ),
            );
			
			 $this->sections[] = array(
                'title'     => __('Blog Page', 'magicbook'),
                'desc'      => __('', 'magicbook'),
                'icon'      => 'el-icon-pencil',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(

                    array(
                        'id'        => 'blog_meta',
                        'type'      => 'checkbox',
						'multi'    => true,
                        'title'     => __('Post Meta Information', 'magicbook'),
                        'subtitle'  => __('Select the post meta information which you want to display', 'magicbook'),
						'options'  => array(
							'author' => 'Author',
							'date' => 'Date',
							'category' => 'Category',
							'comment' => 'Comment'
						),
                        'default'   => array(
							'author' => '1',
							'date' => '1',
							'category' => '0',
							'comment' => '1'
						),
                    ),
					
				  array(
                        'id'        => 'enable_comment',
                        'type'      => 'checkbox',
						'multi'    => true,
                        'title'     => __('Enable The Comment In Post', 'magicbook'),
                        'subtitle'  => __('There are three post type below, you can enable the comment for both of them or specific one or two item.', 'magicbook'),
						'options'  => array(
							'post' => 'Post',
							'portfolio' => 'Portfolio',
							'page' => 'Page'
						),
                         'default'   => array(
							'post' => '1',
							'portfolio' => '1',
							'page' => '0'
						),
                   ),
					
				   array(
                        'id'        => 'blog_cover',
                        'type'      => 'button_set',
						'multi'    => false,
                        'title'     => __('Show The Featured Cover Image', 'magicbook'),
                        'subtitle'  => __('The featured cover image will be displayed in the blog archive.', 'magicbook'),
						'options'  => array(
							'1' => 'Yes',
							'0' => 'No'
						),
                        'default'   => '1'
                    ),
     
                ),
            );
			
			 $this->sections[] = array(
                'title'     => __('Custom Code', 'magicbook'),
                'desc'      => __('', 'magicbook'),
                'icon'      => 'el-icon-quotes',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(

                    array(
                        'id'        => 'custom_css',
                        'type'      => 'textarea',
						'multi'    => false,
                        'title'     => __('Custom CSS', 'magicbook'),
                        'subtitle'  => __('Add your own custom CSS here.', 'magicbook')
                    ),
					
				   array(
                        'id'        => 'javascript_code',
                        'type'      => 'textarea',
						'url'       => true,
                        'title'     => __('Custom Javascript', 'magicbook'),
                        'desc'      => '',
                        'subtitle'  => __('Add your own custom Javascript code here.', 'magicbook'),
                    ),
                    
                ),
            );
			
			 $this->sections[] = array(
                'title'     => __('Additionals', 'magicbook'),
                'desc'      => __('', 'magicbook'),
                'icon'      => 'el-icon-list',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(

                    array(
                        'id'        => 'header_code',
                        'type'      => 'textarea',
						'multi'    => false,
                        'title'     => __('Head Code', 'magicbook'),
                        'subtitle'  => __('You can add Google Analysis,meta info and link the CSS/JS files here.', 'magicbook')
                    ),
					
				   array(
                        'id'        => 'footer_code',
                        'type'      => 'textarea',
						'url'       => true,
                        'title'     => __('Footer Code', 'magicbook'),
                        'subtitle'  => __('You can add some additional code to the footer of your page.', 'magicbook'),
                    ),
					
                    
                ),
            );
       

            $theme_info  = '<div class="redux-framework-section-desc">';
            $theme_info .= '<p class="redux-framework-theme-data description theme-uri">' . __('<strong>Theme URL:</strong> ', 'magicbook') . '<a href="' . $this->theme->get('ThemeURI') . '" target="_blank">' . $this->theme->get('ThemeURI') . '</a></p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-author">' . __('<strong>Author:</strong> ', 'magicbook') . $this->theme->get('Author') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-version">' . __('<strong>Version:</strong> ', 'magicbook') . $this->theme->get('Version') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-description">' . $this->theme->get('Description') . '</p>';
            $tabs = $this->theme->get('Tags');
            if (!empty($tabs)) {
                $theme_info .= '<p class="redux-framework-theme-data description theme-tags">' . __('<strong>Tags:</strong> ', 'magicbook') . implode(', ', $tabs) . '</p>';
            }
            $theme_info .= '</div>';

            if (file_exists(dirname(__FILE__) . '/../README.md')) {
                $this->sections['theme_docs'] = array(
                    'icon'      => 'el-icon-list-alt',
                    'title'     => __('Documentation', 'magicbook'),
                    'fields'    => array(
                        array(
                            'id'        => '17',
                            'type'      => 'raw',
                            'markdown'  => true,
                            'content'   => file_get_contents(dirname(__FILE__) . '/../README.md')
                        ),
                    ),
                );
            }

            $this->sections[] = array(
                'title'     => __('Import / Export', 'magicbook'),
                'desc'      => __('Import and Export your Redux Framework settings from file, text or URL.', 'magicbook'),
                'icon'      => 'el-icon-refresh',
                'fields'    => array(
                    array(
                        'id'            => 'opt-import-export',
                        'type'          => 'import_export',
                        'title'         => 'Import Export',
                        'subtitle'      => 'Save and restore your Redux options',
                        'full_width'    => false,
                    ),
                ),
            );                     
                    
            $this->sections[] = array(
                'type' => 'divide',
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-info-sign',
                'title'     => __('Theme Information', 'magicbook'),
                'desc'      => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'magicbook'),
                'fields'    => array(
                    array(
                        'id'        => 'opt-raw-info',
                        'type'      => 'raw',
                        'content'   => $item_info,
                    )
                ),
            );

            if (file_exists(trailingslashit(dirname(__FILE__)) . 'README.html')) {
                $tabs['docs'] = array(
                    'icon'      => 'el-icon-book',
                    'title'     => __('Documentation', 'magicbook'),
                    'content'   => nl2br(file_get_contents(trailingslashit(dirname(__FILE__)) . 'README.html'))
                );
            }
        }

        public function setHelpTabs() {

            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-1',
                'title'     => __('Theme Information 1', 'magicbook'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'magicbook')
            );

            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-2',
                'title'     => __('Theme Information 2', 'magicbook'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'magicbook')
            );

            // Set the help sidebar
            $this->args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'magicbook');
        }

        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array(
                'opt_name' => 'magicbook_opt',
                'display_name' => $theme->get('Name'),
                'display_version' => $theme->get('Version'),
				'page_priority'    => 30,
                'page_slug' => 'magicbook_options',
                'page_title' => 'Magic Book Options',
                'dev_mode' => false,
                'update_notice' => '0',
                'intro_text' => '<p>You can manage the settings of site with the following options</p>',
                'footer_text' => '<p>If you have any questions, please go to <a href="http://www.themevan.com/support" target="_blank">ThemeVan Support Forum</a>. You can also visit our <a href="http://www.themevan.com/" target="_blank">official site</a>.</p>',
                'admin_bar' => '1',
                'menu_type' => 'menu',
                'menu_title' => 'MagicBook Options',
                'allow_sub_menu' => true,
                'page_parent_post_type' => 'your_post_type',
                'customizer' => false,
                'hints' => 
                array(
                  'icon' => 'el-icon-question-sign',
                  'icon_position' => 'right',
                  'icon_size' => 'normal',
                  'tip_style' => 
                  array(
                    'color' => 'light',
                  ),
                  'tip_position' => 
                  array(
                    'my' => 'top left',
                    'at' => 'bottom right',
                  ),
                  'tip_effect' => 
                  array(
                    'show' => 
                    array(
                      'duration' => '500',
                      'event' => 'mouseover',
                    ),
                    'hide' => 
                    array(
                      'duration' => '500',
                      'event' => 'mouseleave unfocus',
                    ),
                  ),
                ),
                'output' => '1',
                'output_tag' => '1',
                'compiler' => '1',
                'page_icon' => 'icon-themes',
                'page_permissions' => 'manage_options',
                'save_defaults' => '1',
                'show_import_export' => '1',
                'transient_time' => '3600',
                'network_sites' => '1',
              );

            // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            $this->args['share_icons'][] = array(
                'url'   => 'https://www.facebook.com/ThemeVan',
                'title' => 'Like us on Facebook',
                'icon'  => 'el-icon-facebook'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://twitter.com/ThemeVan',
                'title' => 'Follow us on Twitter',
                'icon'  => 'el-icon-twitter'
            );

        }

    }
    
    global $reduxConfig;
    $reduxConfig = new admin_folder_Redux_Framework_config();
}

/**
  Custom function for the callback referenced above
 */
if (!function_exists('admin_folder_my_custom_field')):
    function admin_folder_my_custom_field($field, $value) {
        print_r($field);
        echo '<br/>';
        print_r($value);
    }
endif;

/**
  Custom function for the callback validation referenced above
 * */
if (!function_exists('admin_folder_validate_callback_function')):
    function admin_folder_validate_callback_function($field, $value, $existing_value) {
        $error = false;
        $value = 'just testing';

        /*
          do your validation

          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            $field['msg'] = 'your custom error message';
          }
         */

        $return['value'] = $value;
        if ($error == true) {
            $return['error'] = $field;
        }
        return $return;
    }
endif;
?>
