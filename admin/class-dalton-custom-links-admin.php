<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/msmjsuarez
 * @since      1.0.0
 *
 * @package    Dalton_Custom_Links
 * @subpackage Dalton_Custom_Links/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dalton_Custom_Links
 * @subpackage Dalton_Custom_Links/admin
 * @author     MJ Suarez <msmjsuarez@gmail.com>
 */
class Dalton_Custom_Links_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	private $table_name;
	private $wpdb;
	private $page;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{
		global $wpdb;
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->table_name = $wpdb->prefix . 'dalton_custom_links';
		$this->wpdb = $wpdb;
		$this->page = $_GET['page'];
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dalton_Custom_Links_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dalton_Custom_Links_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/dalton-custom-links-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/dalton-custom-links-admin.js', array('jquery'), $this->version, false);
	}

	public function add_menu()
	{

		$this->plugin_screen_hook_suffix = add_menu_page(
			__('Dalton Custom Links Settings', 'dalton-custom-links'),
			__('Course Level Links', 'dalton-custom-links'),
			'manage_options',
			$this->plugin_name . '-settings',
			array($this, 'display_options_page'),
			'dashicons-admin-links',
			6
		);

		$this->plugin_screen_hook_suffix = add_submenu_page(
			$this->plugin_name . '-settings',
			__('Home Level 1 Link', 'dalton-custom-links'),
			__('Home Level 1 button', 'dalton-custom-links'),
			'manage_options',
			$this->plugin_name . '-home-level1',
			array($this, 'display_options_page')
		);

		$this->plugin_screen_hook_suffix = add_submenu_page(
			$this->plugin_name . '-settings',
			__('Home Level 2 Link', 'dalton-custom-links'),
			__('Home Level 2 button', 'dalton-custom-links'),
			'manage_options',
			$this->plugin_name . '-home-level2',
			array($this, 'display_options_page')
		);

		$this->plugin_screen_hook_suffix = add_submenu_page(
			$this->plugin_name . '-settings',
			__('Course Page Level 2 Link', 'dalton-custom-links'),
			__('Course Page Level 2 button', 'dalton-custom-links'),
			'manage_options',
			$this->plugin_name . '-course-page-level2',
			array($this, 'display_options_page')
		);

		$this->plugin_screen_hook_suffix = add_submenu_page(
			$this->plugin_name . '-settings',
			__('Course Level 1 Link', 'dalton-custom-links'),
			__('Course Level 1 button', 'dalton-custom-links'),
			'manage_options',
			$this->plugin_name . '-course-level1',
			array($this, 'display_options_page')
		);

		$this->plugin_screen_hook_suffix = add_submenu_page(
			$this->plugin_name . '-settings',
			__('Course Level 2 Link', 'dalton-custom-links'),
			__('Course Level 2 button', 'dalton-custom-links'),
			'manage_options',
			$this->plugin_name . '-course-level2',
			array($this, 'display_options_page')
		);

		$this->plugin_screen_hook_suffix = add_submenu_page(
			$this->plugin_name . '-settings',
			__('Course Level 3 Link', 'dalton-custom-links'),
			__('Course Level 3 button', 'dalton-custom-links'),
			'manage_options',
			$this->plugin_name . '-course-level3',
			array($this, 'display_options_page')
		);

		$this->plugin_screen_hook_suffix = add_submenu_page(
			$this->plugin_name . '-settings',
			__('Course Level 4 Link', 'dalton-custom-links'),
			__('Course Level 4 button', 'dalton-custom-links'),
			'manage_options',
			$this->plugin_name . '-course-level4',
			array($this, 'display_options_page')
		);

		//remove displaying parent menu from submenu
		remove_submenu_page($this->plugin_name . '-settings', $this->plugin_name . '-settings');
	}

	public function display_options_page()
	{

		$results = $this->wpdb->get_results("SELECT * FROM $this->table_name WHERE position = '$this->page'");
		foreach ($results as $row) {
			$text = strtoupper($row->text);
			$url = $row->url;
		}

		$_SESSION['page'] = $this->page;


		$page = $_GET['page']; // to get the unique identifier of the page using page url
		$page_id = $page . '_nonce';
		$page_id_nonce = wp_create_nonce('dalton_custom_links_form_nonce'); //generate a custom nonce value to add form submit security

		//generate the page title based on the url
		$title = substr((ucwords(str_replace('-', ' ', $page))), 20);

		if (($page == 'dalton-custom-links-home-level1') || ($page == 'dalton-custom-links-home-level2')) {
			$page_url = get_site_url();
		} else if (($page == 'dalton-custom-links-course-page-level2')) {
			$page_url = get_site_url() . '/courses/';
		} else if (($page == 'dalton-custom-links-course-level1')) {
			$page_url = get_site_url() . '/course/bioenergy-level-1/';
		} else if (($page == 'dalton-custom-links-course-level2')) {
			$page_url = get_site_url() . '/level-2-bio-practitioner-training/';
		} else if (($page == 'dalton-custom-links-course-level3')) {
			$page_url = get_site_url() . '/level-3-bio-advanced-training/';
		} else if (($page == 'dalton-custom-links-course-level4')) {
			$page_url = get_site_url() . '/level-4-bio-intro-trainer/';
		}

		include 'partials/dalton-custom-links-admin-display.php';
	}

	public function store()
	{

		if (isset($_POST['dalton_custom_links_nonce']) && wp_verify_nonce($_POST['dalton_custom_links_nonce'], 'dalton_custom_links_form_nonce')) {

			$home_level1_text = $_POST['text'];
			$home_level1_url = sanitize_text_field($_POST['url']);
			$position = sanitize_text_field($_POST['dalton_custom_link_position']);

			$results = $this->wpdb->get_results("SELECT * FROM $this->table_name  WHERE position = '$position'");

			if (count($results) == 0) {
				$query = $this->wpdb->insert($this->table_name, array(
					'text' => $home_level1_text,
					'url' => $home_level1_url,
					'position' => $position
				));
			} else {
				$query = $this->wpdb->update(
					$this->table_name,
					array(
						'text' => $home_level1_text,
						'url' => $home_level1_url
					),
					array('position' => $position)
				);
			}

			$message = ($query) ? 'true' : 'false';
			$redirect = add_query_arg('msg', $message, '/admin.php?page=' . $_SESSION['page']);
			$this->custom_redirect($message, $redirect);

			exit();
		} else {
			wp_die(__('Invalid nonce specified', $this->plugin_name), __('Error', $this->plugin_name), array(
				'response' 	=> 403,
				'back_link' => 'admin.php?page=' . $this->plugin_name,

			));
		}
	}

	public function custom_redirect($message, $redirect)
	{
		wp_safe_redirect(add_query_arg(array('msg' => $message), admin_url($redirect)));
	}

	public function dalton_custom_links_home_level1_show()
	{
		$results = $this->wpdb->get_results("SELECT * FROM $this->table_name WHERE position = 'dalton-custom-links-home-level1'");

		if (!empty($results)) {
			foreach ($results as $row) {
				$text = strtoupper($row->text);
				$url = $row->url;
			}
		}

		$button = "<button class=\"btn btn-success btn-md other attend directory\" 
					value=\"Input Button\" 
					onclick=\"location.href = '" . $url . "'\">" . $text . "</button>";
		return $button;
	}

	public function dalton_custom_links_home_level2_show()
	{
		$results = $this->wpdb->get_results("SELECT * FROM $this->table_name WHERE position = 'dalton-custom-links-home-level2'");

		if (!empty($results)) {
			foreach ($results as $row) {
				$text = strtoupper($row->text);
				$url = $row->url;
			}
		}

		$button = "<button class=\"btn btn-success btn-md other register directory\" 
					value=\"Input Button\" 
					onclick=\"location.href = '" . $url . "'\">" . $text . "</button>";
		return $button;
	}

	public function dalton_custom_links_course_page_level2_show()
	{
		$results = $this->wpdb->get_results("SELECT * FROM $this->table_name WHERE position = 'dalton-custom-links-course-page-level2'");

		if (!empty($results)) {
			foreach ($results as $row) {
				$text = strtoupper($row->text);
				$url = $row->url;
			}
		}

		$button = '<a href="' . $url . '">' . $text . '</a>';
		return $button;
	}

	public function dalton_custom_links_course_level1_show($x)
	{

		$results = $this->wpdb->get_results("SELECT * FROM $this->table_name WHERE position = 'dalton-custom-links-course-level1'");

		if (!empty($results)) {
			foreach ($results as $row) {
				$text = strtoupper($row->text);
				$url = $row->url;
			}
		}

		echo '<a class="full button" href="' . $url . '">'  . $text . '</a>';
		return $x;
	}

	public function dalton_custom_links_course_level2_show()
	{
		$results = $this->wpdb->get_results("SELECT * FROM $this->table_name WHERE position = 'dalton-custom-links-course-level2'");

		if (!empty($results)) {
			foreach ($results as $row) {
				$text = strtoupper($row->text);
				$url = $row->url;
			}
		}

		$button = "<p><a class=\"full button\" href=\"" . $url . "\">" . $text . "</a></p>";
		return $button;
	}

	public function dalton_custom_links_course_level3_show()
	{
		$results = $this->wpdb->get_results("SELECT * FROM $this->table_name WHERE position = 'dalton-custom-links-course-level3'");

		if (!empty($results)) {
			foreach ($results as $row) {
				$text = strtoupper($row->text);
				$url = $row->url;
			}
		}

		$button = "<p><a class=\"full button\" href=\"" . $url . "\">" . $text . "</a></p>";
		return $button;
	}

	public function dalton_custom_links_course_level4_show()
	{
		$results = $this->wpdb->get_results("SELECT * FROM $this->table_name WHERE position = 'dalton-custom-links-course-level4'");

		if (!empty($results)) {
			foreach ($results as $row) {
				$text = strtoupper($row->text);
				$url = $row->url;
			}
		}

		$button = "<p><a class=\"full button\" href=\"" . $url . "\">" . $text . "</a></p>";
		return $button;
	}

	public function dalton_custom_links_session()
	{
		if (!session_id()) {
			session_start();
		}
	}

	/*
	replace the shortcode [] character
	*/
	public function local_fix_quotes($x)
	{
		$x = str_replace("&#91;", '[', $x);
		$x = str_replace("&#93;", ']', $x);
		return $x;
	}
}
