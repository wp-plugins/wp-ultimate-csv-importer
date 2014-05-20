<?php
/******************************
 * Filename	: modules/users/actions/actions.php
 * Description	: This module helps to import users with roles.
 * Author       : Fredrick
 * Owner        : smackcoders.com
 * Date         : Jan31,2014
 */

class UsersActions extends SkinnyActions {

	public function __construct()
	{
	}

	// @var boolean post title check
	public $titleDupCheck = false;

	// @var boolean content title check
	public $conDupCheck = false;

	// @var boolean for post flag
	public $postFlag = true;

	// @var int duplicate post count
	public $dupPostCount = 0;

	// @var int inserted post count
	public $insPostCount = 0;

	// @var int no post author count
	public $noPostAuthCount = 0;

	 // @var int updated post count
        public $updatedPostCount=0;

	// @var array wp field keys
	public $keys = array();

	/**
	 * Mapping fields
	 */
	public $defCols = array(
			'user_login'    => null,
			'first_name'    => null,
			'last_name'     => null,
			'nickname'      => null,
			'user_email'    => null,
			'user_url'      => null,
			'aim'           => null,
			'yim'           => null,
			'jabber/gtalk'  => null,
			'role'          => null,
			'description'   => null,
			);

	public function getRoles(){
		global $wp_roles;
		$roles = array();
	        foreach($wp_roles->roles as $rkey => $rval){
			$roles[$rkey] = '';
        	        for($cnt=0;$cnt<count($rval['capabilities']);$cnt++){
                	        $findval = "level_".$cnt;
                        	if(array_key_exists($findval,$rval['capabilities']))
                                	$roles[$rkey] = $roles[$rkey].$cnt.',';
                	}
        	} 
	return $roles;
	}

	/**
	 * function to map the csv file and process it
	 *
	 * @return boolean
	 */
	function processDataInWP($data_rows,$ret_array,$session_arr)
	{ 	
		$impCE = new WPImporter_includes_helper();
		$smack_taxo = array();
		$custom_array = array();
		$headr_count = $ret_array['h2'];
		for ($i = 0; $i < count($data_rows); $i++) {
			if (array_key_exists('mapping' . $i, $ret_array)) { 
				if($ret_array ['mapping' . $i] != '-- Select --'){
					if ($ret_array ['mapping' . $i] != 'add_custom' . $i) {
						$strip_CF = strpos($ret_array['mapping' . $i], 'CF: ');
						if ($strip_CF === 0) {
							$custom_key = substr($ret_array['mapping' . $i], 4);
							$custom_array[$custom_key] = $data_rows[$i];
						} else {
							$new_post[$ret_array['mapping' . $i]] = $data_rows[$i];
						}
					} else {
						$new_post [$ret_array ['textbox' . $i]] = $data_rows [$i];
						$custom_array [$ret_array ['textbox' . $i]] = $data_rows [$i];
					}
				}
			}
		}
		global $wpdb;
		$user_table = $wpdb->users;
		$UC = $wpdb->get_results("select count(ID) as users from $user_table");
		$initial_count = $UC[0]->users;
		$roles = $this->getRoles();
		$user_table = $wpdb->users;
		$limit = (int) apply_filters( 'postmeta_form_limit', 30 );
		$keys = $wpdb->get_col( "
				SELECT meta_key
				FROM $wpdb->postmeta
				GROUP BY meta_key
				HAVING meta_key NOT LIKE '\_%'
				ORDER BY meta_key
				LIMIT $limit" );
		foreach($new_post as $ckey => $cval){
			if($ckey == 'jabber/gtalk'){
				$data_array['jabber'] = $new_post[$ckey];
			}
			elseif($ckey == 'role'){
				$data_array_ckey = '';
				for($i=0 ; $i<=$new_post[$ckey] ; $i++){
					$data_array_ckey .= $i.",";
				}
				$data_array[$ckey]= $data_array_ckey;
			}
			else{
				$data_array[$ckey]=$new_post[$ckey];
			}
		}
		$data_array['user_pass'] = wp_generate_password( 12, false );
		$getUsers = $wpdb->get_results("select count(ID) as users from $user_table"); 
		$userscount = $getUsers[0]->users;
		foreach($roles as $rkey => $rval){
			if($rval == $data_array['role']){
				$data_array['role'] = $rkey;
			}
		}
		if(! array_key_exists($data_array['role'],$roles)){
			$data_array['role'] = 'subscriber';
		}
		$user_table = $wpdb->users; 
		$user_id = '';
		$user_role= '';
		$getUserId = $wpdb->get_results("select ID from $user_table where user_email = '".$data_array["user_email"]."'");
		if(!empty($getUserId)){
			$user_id = $getUserId[0]->ID;
		}
		if($user_id){
			$this->dupPostCount = $this->dupPostCount+1;
		}
		else{
			$user_id = wp_insert_user( $data_array );
			if($user_id){
				$this->insPostCount++; // = $this->insPostCount+1;
			}
			$user = new WP_User( $user_id );
			if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
				foreach ( $user->roles as $role )
					$user_role = $role;
			}
			$getUsers1 = $wpdb->get_results("select count(ID) as users from $user_table");
			$no_of_users = ($getUsers1[0]->users) - ($getUsers[0]->users);
			$termcount = $userscount+$no_of_users; 
			if($no_of_users > 0){
				$newUsers['user'][] = $user_id;
			}
			$current_user = wp_get_current_user();
			$admin_email = $current_user->user_email;
			$headers = "From: Administrator <$admin_email>" . "\r\n";
			$message = "Hi,You've been invited with the role of ".$user_role.". Here, your login details."."\n"."username: ".$data_array['user_login']."\n"."userpass: ".$data_array['user_pass']."\n"."Please click here to login ".wp_login_url();
			$emailaddress = $data_array['user_email'];
			$subject = 'Login Details';
			if(isset($_POST['send_password'])){
				wp_mail($emailaddress, $subject, $message, $headers);
			}
		}
		$UC1 = $wpdb->get_results("select count(ID) as users from $user_table");
		$last_count = $UC1[0]->users;
		//$this->insPostCount = $last_count - $initial_count;
		$uploaded_file_name=$session_arr['uploadedFile'];
		$real_file_name = $session_arr['uploaded_csv_name'];
		$action=$session_arr['selectedImporter'];
/*		$version_arr=array();
		$version_arr=explode("(",$uploaded_file_name);
		$version_arr=explode(")",$version_arr[1]);
		$version=$version_arr[0]; */
		$created_records[$action][] = $user_id;
		$imported_as = 'Users';
		$keyword = $action;
	
		return $this->insPostCount;
	}

	/**
	 * The actions index method
	 * @param array $request
	 * @return array
	 */
	public function executeIndex($request)
	{
		// return an array of name value pairs to send data to the template
		$data = array();
		return $data;
	}

}
