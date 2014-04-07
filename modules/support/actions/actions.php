<?php
/******************************
 * filename:    modules/support/actions/actions.php
 * description:
 */

class SupportActions extends SkinnyActions {

    public function __construct()
    {
    }

  /**
   * The actions index method
   * @param array $request
   * @return array
   */
    public function executeIndex($request)
    {
        // return an array of name value pairs to send data to the template
	require_once (ABSPATH . 'wp-load.php');
        $data = array();
	$headers = array();
	if(isset($_POST['send_mail'])){
		$to = $_POST['smackmailid'];
		$admin_mail_id = get_option('admin_email');
		$site_url = get_option('siteurl');
		$subject = WP_CONST_ULTIMATE_CSV_IMP_NAME . ': ';
		if($_POST['subject'] == 'Support')
			$subject .= 'I need some "' . $_POST['subject'] . '"';
		else if($_POST['subject'] == 'Feature Request')
			$subject .= 'I have some "' . $_POST['subject'] . '"';
		else if($_POST['subject'] == 'Customization')
			$subject .= 'I need some "' . $_POST['subject'] . '"';

		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$user_info = get_userdata(1);
		$first_name = $user_info->first_name;
		$last_name = $user_info->last_name;
		if($_POST['firstname'] == null || $_POST['firstname'] == '')
			$firstname = $first_name;
		if($_POST['lastname'] == null || $_POST['lastname'] == '')
			$lastname = $last_name;
		if($firstname == null || $firstname == '')
			$firstname = 'Anonymous';
		if($lastname == null || $lastname == '')
			$lastname = '';
		$username = $firstname . ' ' . $lastname;
		$headers[] = 'From: '. $username . ' <' . $admin_mail_id . '>';
		$headers[] = 'Cc: '. $username . ' <' . $admin_mail_id . '>';
		$message = "\n\n First Name: ".$firstname;
		$message .= "\n\n Last Name: ".$lastname;
		$message .= "\n\n WordPress URL: ".$site_url;
		$message .= "\n\n Plugin: ".WP_CONST_ULTIMATE_CSV_IMP_NAME;
		$message .= "\n\n Email: ".$admin_mail_id;
		$message .= "\n\n Message: ".$_POST['message'];
		wp_mail( $to, $subject, $message, $headers );
		unset($_POST);
	}
        return $data;
    }

}
