<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */

global $jal_db_version;
$jal_db_version = '1.0';


 function get_latest_post ( $params ){
	$category_name="sads";
  	$post = get_posts( array(
            'category_name'      => $category_name,
            'posts_per_page'  => 1,
            'offset'      => 0
      ) );
	
	if( empty( $post ) ){
		return new WP_Error( 'no_post_found', 'there is no post in this category', array( 'status' => 404 ) );
  	 	}
 
  	 	return $post[0]->post_title;
  	 }


class OssipTable {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */

	public static $name='OssipPluginTable';
	public static $configtable='OssipPluginConfigTable';

	
 	public static function createConfigTable(){
		// Create the configuration table holding every table name that belongs
		// to this plugin.
		global $wpdb;
		global $jal_db_version;
		$charset_collate = $wpdb->get_charset_collate();

		$table_name = $wpdb->prefix . self::$configtable;
	
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name text DEFAULT '' NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		add_option( 'jal_db_version', $jal_db_version );
	}

	public static function createFromFields($table_name,$fields){
		// Create a table from with a name (no prefix) and fields given by
		// an array of field objects (field->name, field->type)
		// where name is the name of the table an type could be 'numeric' or 'text'

		global $wpdb;
		global $jal_db_version;
		$charset_collate = $wpdb->get_charset_collate();

		$table_name=$wpdb->prefix . $table_name;

		//Check if table exists
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
			throw new Exception('Table already exists');
		}
		
		//Create the table	
		$sql_pre="CREATE TABLE $table_name(
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
			origin text DEFAULT '' NOT NULL";
		$sql=$sql_pre;
		foreach( $fields as $field ) {			
			$name=$field->name;				
			if ($field->type=='numeric'){
				$name=$field->name;
				$sql=$sql . ",$name double NOT NULL";
			}
			elseif ($field->type=='text'){
				$sql=$sql . ",$name text NOT NULL";
			}
			else{
				 throw new Exception('Wrong field');
			}
 		}
		$sql=$sql . ",PRIMARY KEY  (id)) $charset_collate;";
		
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );			
		dbDelta( $sql );
		add_option( 'jal_db_version', $jal_db_version );
		//Since the table was created now we should keep track of this table using
		// configtable.
		$wpdb->insert( $wpdb->prefix . self::$configtable,
		array(
			'name'=>$table_name
		));
		
	}

	// Table dropping
	public static function drop($table_name){
		global $wpdb;
		global $jal_db_version;		
		$sql = "DROP TABLE IF EXISTS $table_name";
 		$wpdb->query($sql);
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	}
	
	public static function dropConfig(){
		global $wpdb;
		$table_name=$wpdb->prefix . self::$configtable;
		self::drop($table_name);	
	}

	public static function dropAll(){
		// Get the tables from configTable and drop each one of them
		global $wpdb;
		$query='SELECT  name FROM ' . $wpdb->prefix . self::$configtable;
		$results=$wpdb->get_results($query,OBJECT);
		var_dump($results);
		foreach ($results as $result){
 			self::drop($result->name);
		}
		//Drop configTable
		self::dropConfig();
	}

	
	public static function insert($table,$origin="",$param,$cost){
		global $wpdb;
		
		$table_name = $wpdb->prefix . $table;
	
		$wpdb->insert( 
		$table_name, 
		array( 
			'time' => current_time( 'mysql' ), 
			'origin' =>  $origin,
			'param' => $param, 
			'cost' => $cost
		) 
	);

	}

	public static function isTable($table){
		global $wpdb;
		global $jal_db_version;
		$charset_collate = $wpdb->get_charset_collate();
		$table_name=$wpdb->prefix . $table;
		//Check if table exists
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
			return True;
		}
		else{
			return False;		
		}
	}
	

	// REST API exposed functions

	public static function createDefaultTable(){
		$name=self::$name;
		$field1=new StdClass;
		$field1->type='numeric';
		$field1->name='test1';
		$field2=new StdClass;
		$field2->type='numeric';
		$field2->name='cost';
		$field3=new StdClass;
		$field3->type='text';
		$field3->name='test2';
		
		$fields=array($field1,$field2,$field3);
		self::createFromFields($name,$fields);
	
	}


	

	

	public static function get_latest_row ( $params ){
	global $wpdb;

	$query='SELECT * FROM ' . $wpdb->prefix . self::$name  . ' ORDER BY ID DESC LIMIT 0, 1';

	$result=$wpdb->get_row($query,OBJECT);
	
	if( $wpdb->last_error ){
		return new WP_Error( 'no_post_found', 'there is no post in this category', array( 'status' => 404 ) );
  	 	}
 	else{
		$result->cost=(float)$result->cost;
		$result->param=(float)$result->param;
  	 	return rest_ensure_response($result);
  	 }
	}

	public static function get_history ( WP_REST_Request $request ){
	global $wpdb;

	$body=$request->get_params();
	$table=$wpdb->prefix . $body['table'];

	//Get all the fields from the selected table and send that as a consolidate:

	$query='SELECT * FROM ' . $table  . ' ORDER BY id ASC';

	$results=$wpdb->get_results($query,OBJECT);
	
	if( $wpdb->last_error ){
		return new WP_Error( 'no_post_found', 'there is no post in this category', array( 'status' => 404 ) );
  	 	}
 	else{
		$existing_columns = $wpdb->get_col("DESC {$table}", 0);
		$sql_types= $wpdb->get_col("DESC {$table}", 1);
		$consolidate=[];
		for ($i=0;$i<count($existing_columns);$i++){
			$column=$existing_columns[$i];
			$sql_type=$sql_types[$i];
			$column_data=[];
			foreach( $results as $result ) {
				$result->$column;
				if (!($sql_type=='text' || $sql_type=='datetime')){
					$result->$column=(float)$result->$column;				
				}
				$column_data[]=$result->$column;
  		 	}
			$consolidate[]=$column_data;
		}
		return rest_ensure_response($consolidate);
  	 }
	}

	

	public static function rest_insert ( WP_REST_Request $request ){
	global $wpdb;

	$response=0;
	$headers=$request->get_headers();
	$body=$request->get_params();
	
	$table = $body['table'];
	unset($body['table']);
	$origin= $body['origin'];
	unset($body['origin']);

	//Check if table exists
	if (!self::isTable($table)) {
		return new WP_Error($table . 'Table not found', 'Table is not here, add from plugin menu', array( 'status' => 404 ) );
	}	

	//Get all the fields:
	$keys=array_keys($body);

	$allvalues=array();
	$allvalues['origin']=$origin;

	foreach ($keys as $key){
		$allvalues[$key]=$body[$key];			
	}
	
	$wpdb->insert( $wpdb->prefix . $table ,$allvalues);

	if( $wpdb->last_error ){
		return new WP_Error( 'no_post_found', 'there is no post in this category', array( 'status' => 404 ) );
  	 	}
	else{		
		$response='hello';
		return $response;
	}
	}

	

}


