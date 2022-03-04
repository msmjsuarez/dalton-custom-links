<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/msmjsuarez
 * @since      1.0.0
 *
 * @package    Dalton_Custom_Links
 * @subpackage Dalton_Custom_Links/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Dalton_Custom_Links
 * @subpackage Dalton_Custom_Links/includes
 * @author     MJ Suarez <msmjsuarez@gmail.com>
 */
class Dalton_Custom_Links
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Dalton_Custom_Links_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		if (defined('DALTON_CUSTOM_LINKS_VERSION')) {
			$this->version = DALTON_CUSTOM_LINKS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'dalton-custom-links';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Dalton_Custom_Links_Loader. Orchestrates the hooks of the plugin.
	 * - Dalton_Custom_Links_i18n. Defines internationalization functionality.
	 * - Dalton_Custom_Links_Admin. Defines all hooks for the admin area.
	 * - Dalton_Custom_Links_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-dalton-custom-links-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-dalton-custom-links-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-dalton-custom-links-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-dalton-custom-links-public.php';

		$this->loader = new Dalton_Custom_Links_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Dalton_Custom_Links_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{

		$plugin_i18n = new Dalton_Custom_Links_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{
		$plugin_admin = new Dalton_Custom_Links_Admin($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

		$this->loader->add_action('admin_menu', $plugin_admin, 'add_menu');
		$this->loader->add_action('admin_post_dalton_custom_links_form', $plugin_admin, 'store');

		$this->loader->add_shortcode('dalton-custom-links-home-level1', $plugin_admin, 'dalton_custom_links_home_level1_show');
		$this->loader->add_shortcode('dalton-custom-links-home-level2', $plugin_admin, 'dalton_custom_links_home_level2_show');

		$this->loader->add_shortcode('dalton-custom-links-course-page-level2', $plugin_admin, 'dalton_custom_links_course_page_level2_show');

		$this->loader->add_filter('wplms_course_details_widget', $plugin_admin, 'dalton_custom_links_course_level1_show'); //to add button in course widget

		$this->loader->add_shortcode('dalton-custom-links-course-level2', $plugin_admin, 'dalton_custom_links_course_level2_show');
		$this->loader->add_shortcode('dalton-custom-links-course-level3', $plugin_admin, 'dalton_custom_links_course_level3_show');
		$this->loader->add_shortcode('dalton-custom-links-course-level4', $plugin_admin, 'dalton_custom_links_course_level4_show');

		$this->loader->add_action('init', $plugin_admin, 'dalton_custom_links_session'); // enable session to get page url after form submit
		$this->loader->add_filter("the_excerpt", $plugin_admin, "local_fix_quotes"); // to accept shortcode symbol [] in excerpt

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks()
	{

		$plugin_public = new Dalton_Custom_Links_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Dalton_Custom_Links_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}
}
