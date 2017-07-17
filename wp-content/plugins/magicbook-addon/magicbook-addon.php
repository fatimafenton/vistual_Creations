<?php
/*
Plugin Name: MagicBook Addon
Plugin URI: https://themeforest.net/item/magicbook-a-3d-flip-book-wordpress-theme/8808169
Author: ThemeVan
Author URI: http://www.themevan.com
Version: 1.0.4
Description: MagicBook Addon includes some shortcodes and modules for MagicBook theme.
Text Domain: magicbook-addon
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if(!class_exists('MagicBookAddon')){
	class MagicBookAddon{

		public static function instance() {
			// Store the instance locally to avoid private static replication
			static $instance = null;
			
			// Only run these methods if they haven't been ran previously
			if ( null === $instance ) {
				$instance = new MagicBookAddon;
				$instance->init();
			}

			return $instance;
		}
        
        /**
         * Initialize
         */
		private function init() {
			self::define_constants();
			add_action( 'init', array($this,'textdomain')); 
			add_action( 'setup_theme', array($this,'load_modules'));
			add_action( 'after_setup_theme', array($this,'load_files'));
			add_action( 'init', array($this,'create_portfolio'));
			add_action( 'init', array($this,'create_set'));
			add_action( 'init',  array($this, 'check_update'));
			add_action('admin_init', 'flush_rewrite_rules');
			add_action( 'get_footer', array($this,'load_scripts'),30);
		}

		/**
		 * Define constants
		 */ 
		static private function define_constants()
		{
			define('MBA_NAME', 'MagicBook Addon');
			define('MBA_VERSION', '1.0.4');
			define('MBA_DIR', plugin_dir_path(dirname(__FILE__)));
			define('MBA_DIR_URI', plugins_url( '/', __FILE__ ));
			define('MBA_FILE', trailingslashit(MBA_DIR) . 'magicbook-addon.php');
		}

		/**
         * Localize
         */
		public function textdomain() {
		    $domain = 'magicbook-addon';
		    // The "plugin_locale" filter is also used in load_plugin_textdomain()
		    $locale = apply_filters('plugin_locale', get_locale(), $domain);
		    load_textdomain($domain, WP_LANG_DIR.'/magicbook-addon/'.$domain.'-'.$locale.'.mo');
		    load_plugin_textdomain($domain,FALSE,dirname(plugin_basename(__FILE__)).'/languages/');
		}

		/**
         * Load Files
         */
		public function load_files(){

			if ( function_exists( 'vc_map')){	
				require_once("shortcodes/blog.php");
				require_once("shortcodes/feature.php");
				require_once("shortcodes/portfolios.php");
				require_once("shortcodes/social_media.php");
				require_once("shortcodes/testimonial.php");
				require_once("shortcodes/timeline.php");
			}
			require_once("plugin-update-checker/plugin-update-checker.php");
		}

		public function load_modules(){
			if(!class_exists('OCDI_Plugin')){
			   require_once("demo-importer/one-click-demo-import.php");
		    }
		}

		public function check_update(){
			$TMVS_UpdateChecker = Puc_v4_Factory::buildUpdateChecker(
				'https://www.themevan.com/update-server/?action=get_metadata&slug=magicbook-addon',
				__FILE__,
				'magicbook-addon'
			);
		}

		/**
         * Load CSS and JS files
         */
		public function load_scripts(){
		   wp_enqueue_style("magicbook-addon", MBA_DIR_URI."assets/css/magicbook-addon.css", false, null, "all");
		}

		/**
		 *  Register portfolio post type
		 */
		function create_portfolio() {
	
			  $labels = array(
			    'name' => __('Portfolios','magicbook-addon'),
			    'singular_name' => __('All Portfolios','magicbook-addon'),
			    'add_new' => __('Add Portfolio','magicbook-addon'),
			    'add_new_item' => __('Add New Portfolio','magicbook-addon'),
			    'edit_item' => __('Edit Portfolio','magicbook-addon'),
			    'new_item' => __('Add New','magicbook-addon'),
			    'view_item' => __('Browse Portfolio','magicbook-addon'),
			    'search_items' => __('Search Portfolio','magicbook-addon'),
			    'not_found' =>  __('No portfolios found.','magicbook-addon'),
			    'not_found_in_trash' => __('No portfolios found in trash.','magicbook-addon'),
			    'parent_item_colon' => ''
			  );
			 
			  $supports = array('title','thumbnail','editor','comments','author');
			 
			  register_post_type( 'portfolio',
			    array(
			      'labels' => $labels,
			      'public' => true,
				  'publicly_queryable' => true,
				  'query_var' => true,
			      'supports' => $supports,
				  'menu_position' => 20,
				  'has_archive' => true,
				  'rewrite' => array( 'slug' => 'portfolio' ),
				  'capability_type' => 'post',
				  'show_in_nav_menus'=>false
			    )
			  );
		}

		/**
		 *  Register portfolios taxonomy
		 */
		function create_set() {
			 $labels = array(
			    'name' => __( 'Portfolio category', 'magicbook-addon'),
			    'singular_name' => __( 'Portfolio category','magicbook-addon'),
			    'search_items' =>  __( 'Search category','magicbook-addon'),
			    'all_items' => __( 'All portfolio categories','magicbook-addon'),
			    'parent_item' => __( 'Parent portfolio category','magicbook-addon'),
			    'parent_item_colon' => __( 'Parent portfolio category','magicbook-addon'),
			    'edit_item' => __( 'Edit category','magicbook-addon'),
			    'update_item' => __( 'Update category','magicbook-addon'),
			    'add_new_item' => __( 'Add new category','magicbook-addon'),
			    'new_item_name' => __( 'New category name','magicbook-addon'),
			  );

			  register_taxonomy('portfolios','portfolio',array(
			    'hierarchical' => true,
			    'labels' => $labels,
				'public'=>true,
				'show_ui' => true,
				'rewrite' => true,
				'rewrite' => array( 'slug' => 'portfolios' ),
				'query_var' => 'portfolios',
			  ));
		}
	}

	MagicBookAddon::instance();
}
