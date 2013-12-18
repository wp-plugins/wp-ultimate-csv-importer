<?php 
require_once('class.modulehandler.php');
class Users extends Modulehandler{
        // @var int inserted post count
        public $insUserCount = 0;

	public $skipUserCount = 0;
	/* get roles for users */
	public function getRoles(){
		global $wp_roles;
	        foreach($wp_roles->roles as $rkey => $rval){
        	        for($cnt=0;$cnt<count($rval['capabilities']);$cnt++){
                	        $findval = "level_".$cnt;
                        	if(array_key_exists($findval,$rval['capabilities']))
                                	$roles[$rkey] = $roles[$rkey].$cnt.',';
                	}
        	}
	return $roles;
	}

	public function addUsers($csvValues){
	   global $wpdb;
	   $user_table = $wpdb->users;
	   $UC = $wpdb->get_results("select count(ID) as users from $user_table");
	   $initial_count = $UC[0]->users;
	   $ret_array = $this->getReturnArray();
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

     	   foreach($csvValues as $key => $value){
        	for($i=0;$i<count($value) ; $i++){
	                if(array_key_exists('mapping'.$i,$ret_array)){
        	                if($ret_array['mapping'.$i]!='add_role'.$i){
                	                $new_post[$ret_array['mapping'.$i]] = $value[$i];
                        	}
	                        else{
        	                        $new_post[$ret_array['textbox'.$i]] = $value[$i];
                	                $custom_array[$ret_array['textbox'.$i]] = $value[$i];
                        	}
                	}
        	}
	        for($inc=0;$inc<count($value);$inc++){
        		foreach($keys as $k => $v){
		             if(array_key_exists($v,$new_post)){
                		$custom_array[$v] =$new_post[$v];
             		     }
           		}
        	}
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
	        foreach($roles as $rkey => $rval){
        	        if($rval == $data_array['role']){
                	        $data_array['role'] = $rkey;
                	}
        	}
	        if(! array_key_exists($data_array['role'],$roles)){
                	$data_array['role'] = $ret_array['userrole'];
        	}
	        if(isset($_POST['existRecords']) && ($_POST['existRecords'] == 'updatedcsv')){
        	        $getUserId = $wpdb->get_results("select ID from $user_table where user_email = '".$data_array["user_email"]."'");
                	$user_id = $getUserId[0]->ID;
	                if($user_id){
        	                $data_array['ID'] = $user_id;
                	        wp_update_user( $data_array );
                	}
	                else{
        	                $user_id = wp_insert_user( $data_array );
                	        $getUsers1 = $wpdb->get_results("select count(ID) as users from $user_table");
                        	$no_of_users = ($getUsers1[0]->users) - ($getUsers[0]->users);
	                        $termcount = $userscount+$no_of_users;       if($no_of_users > 0){
                	                $newUsers['user'][] = $user_id;
                        	}
	                        $getDashboard = $wpdb->get_results("select * from smack_csv_dashboard where type ='Users'");
        	                $getusercount = $getDashboard[0]->value;
                	        $usercount = $getusercount+$no_of_users;
                        	$table = 'smack_csv_dashboard';
	                        $data_user = array('value'=>$usercount);
        	                $where = array('id'=>$getDashboard[0]->id);
                	        $wpdb->update($table, $data_user, $where);
                        	$current_user = wp_get_current_user();
	                        $admin_email = $current_user->user_email;
        	                $headers = "From: Administrator <$admin_email>" . "\r\n";
                	        $message = "Hi,You've been invited with the role of ".$ret_array['userrole'].". Here, your login details."."\n"."username: ".$data_array['user_login']."\n"."userpass: ".$data_array['user_pass']."\n"."Please click here to login ".wp_login_url();
                        	$emailaddress = $data_array['user_email'];
	                        $subject = 'Login Details';
				if(isset($_POST['send_password'])){
	        	                wp_mail($emailaddress, $subject, $message, $headers);
				}
                	}
        	}
	        else{
                        $getUserId = $wpdb->get_results("select ID from $user_table where user_email = '".$data_array["user_email"]."'");
                        $user_id = $getUserId[0]->ID;
                        if($user_id){
                                $this->skipUserCount = $this->skipUserCount+1;
                        }
			else{
                        	$user_id = wp_insert_user( $data_array );
	                        $getUsers1 = $wpdb->get_results("select count(ID) as users from $user_table");
        	                $no_of_users = ($getUsers1[0]->users) - ($getUsers[0]->users);
                	        $termcount = $userscount+$no_of_users; //print($termcount);
                        	if($no_of_users > 0){
                                	$newUsers['user'][] = $user_id;//print_r($newUsers);
	                        }
        	                $getDashboard = $wpdb->get_results("select *from smack_csv_dashboard where type = 'Users'");
                	        $getusercount = $getDashboard[0]->value;
                        	$usercount = $getusercount+$no_of_users;
	                        $table = 'smack_csv_dashboard';
        	                $data_user = array('value'=>$usercount);
                	        $where = array('id'=>$getDashboard[0]->id);
                        	$wpdb->update($table, $data_user, $where);
	                        $current_user = wp_get_current_user();
        	                $admin_email = $current_user->user_email;
                	        $headers = "From: Administrator <$admin_email>" . "\r\n";
                        	$message = "Hi,You've been invited with the role of ".$ret_array['userrole'].". Here, your login details."."\n"."username: ".$data_array['user_login']."\n"."userpass: ".$data_array['user_pass']."\n"."Please click here to login ".wp_login_url();
	                        $emailaddress = $data_array['user_email'];
        	                $subject = 'Login Details';
				if(isset($_POST['send_password'])){
	                	        wp_mail($emailaddress, $subject, $message, $headers);
				}
                	}
        	}
	    }
        $UC1 = $wpdb->get_results("select count(ID) as users from $user_table");
        $last_count = $UC1[0]->users;
        $this->insUserCount = $last_count - $initial_count;
	return $this->insUserCount;
	}
	
}
