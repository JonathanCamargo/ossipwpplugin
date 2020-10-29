<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 * @author     Your Name <email@example.com>
 */
class Plugin_Name_Admin {

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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-name-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plugin-name-admin.js', array( 'jquery' ), $this->version, false );

	}

	// Create the menu page:
	public function add_admin_page() {
	    	add_menu_page(
		'Ossip Restful',
		'Ossip Restful',
		'manage_options',
		$this->plugin_name,
		array( $this, 'load_admin_page_content' ), // Calls function to require the partial
		plugins_url( 'plugin-name/images/icon.png' ),
		6
	    );
	}

	// Process the add new table form
	public function processform() {
		global $post;
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$name = $_POST["name"];
 		$fields = $_POST["fields"];
 		$types = $_POST["types"];

		$my_plugin = WP_PLUGIN_DIR . '/' . $this->plugin_name;

		require_once $my_plugin. '/includes/OssipTable.php';
		$fields=explode(',',$fields);
		$types=explode(',',$types);
		if (count($types)!=count($fields)){
			 add_settings_error('title_long_error', '', 'Wrong settings', 'error');
        		 settings_errors( 'title_long_error' );
			 wp_update_post($post);
			 throw new Exception('Wrong settings');
		}
		// Use OssipTable class to create the table
		$allfields=array();		
		for($i=0;$i<count($fields);$i++){
			$afield=new StdClass;
			$afield->name=$fields[$i];
			$afield->type=$types[$i];
			$allfields[]=$afield;		
		}
		OssipTable::createFromFields($name,$allfields);
		echo '<h1>Table ' . $name . ' created</h1>';
		}
	}	

	// Load the plugin admin page partial.
	public function load_admin_page_content() {
    		require_once plugin_dir_path( __FILE__ ). 'partials/plugin-name-admin-display.php';
	}

}
