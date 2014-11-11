<?php
/*********************************************************************************
 * WP Ultimate CSV Importer is a Tool for importing CSV for the Wordpress
 * plugin developed by Smackcoder. Copyright (C) 2014 Smackcoders.
 *
 * WP Ultimate CSV Importer is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Affero General Public License version 3
 * as published by the Free Software Foundation with the addition of the
 * following permission added to Section 15 as permitted in Section 7(a): FOR
 * ANY PART OF THE COVERED WORK IN WHICH THE COPYRIGHT IS OWNED BY WP Ultimate
 * CSV Importer, WP Ultimate CSV Importer DISCLAIMS THE WARRANTY OF NON
 * INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * WP Ultimate CSV Importer is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public
 * License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program; if not, see http://www.gnu.org/licenses or write
 * to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA 02110-1301 USA.
 *
 * You can contact Smackcoders at email address info@smackcoders.com.
 *
 * The interactive user interfaces in original and modified versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License
 * version 3, these Appropriate Legal Notices must retain the display of the
 * WP Ultimate CSV Importer copyright notice. If the display of the logo is
 * not reasonably feasible for technical reasons, the Appropriate Legal
 * Notices must display the words
 * "Copyright Smackcoders. 2014. All rights reserved".
 ********************************************************************************/

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
                $message .= "\n\n Message: " . stripslashes($_POST['message']);
		wp_mail( $to, $subject, $message, $headers );
		unset($_POST);
	}
        return $data;
    }

}
