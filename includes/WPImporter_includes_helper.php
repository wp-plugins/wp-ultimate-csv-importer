<?php
/******************************
 * Filename	: includes/WPImporter_includes_helper.php
 * Description	: Helper class for WP Ultimate CSV Importer
 * Author 	: Fredrick
 * Owner  	: smackcoders.com
 * Date   	: Jan31,2014
 */

class WPImporter_includes_helper {

	public function __construct()
	{
		$this->getKeyVals();
	}

	// @var string CSV upload directory name
	public $uploadDir = 'ultimate_importer';

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

	// @var string delimiter
	public $delim = ",";

	// @var array delilimters supported by CSV importer
	public $delim_avail = array(
			',',
			';'
			);

	// @var array wp field keys
	public $keys = array();

	// @var array for default columns
	public $defCols = array(
			'post_title' => null,
			'post_content' => null,
			'post_excerpt' => null,
			'post_date' => null,
			'post_name' => null,
			'post_tag' => null,
			'post_category' => null,
			'post_author' => null,
			'featured_image' => null,
			'post_parent' => 0,
			'post_status' => 0,
			'menu_order'  => 0,
			);

	// @var array CSV headers
	public $headers = array();

	public $capturedId=0;

	/* getImportDataConfiguration */
	public function getImportDataConfiguration(){
		$importDataConfig = "<div class='importstatus'id='importallwithps_div'>
			<table><tr><td>
			<label>Import with post status</label><span class='mandatory'> *</span></td><td>
			<div style='float:left;'>
			<select name='importallwithps' id='importallwithps' onChange='selectpoststatus();' >
			<option value='0'>Status as in CSV</option>
			<option value='1'>Publish</option>
			<option value='2'>Sticky</option>
			<option value='4'>Private</option>
			<option value='3'>Protected</option>
			<option value='5'>Draft</option>
			<option value='6'>Pending</option>
			</select></div>
			<div style='float:right;'>
			<a href='#' class='tooltip'>
			<img src='".WP_CONST_ULTIMATE_CSV_IMP_DIR."images/help.png' />
			<span class='tooltipPostStatus'>
			<img class='callout' src='".WP_CONST_ULTIMATE_CSV_IMP_DIR."images/callout.gif' />
			Select the status for the post  imported, if not defined within your csv .E.g.publish
			<img src='". WP_CONST_ULTIMATE_CSV_IMP_DIR."images/help.png' style='margin-top: 6px;float:right;' />
			</span></a> </div>
			</td></tr><tr><td>
			<div id='globalpassword_label' class='globalpassword' style='display:none;'><label>Password</label><span class='mandatory'> *</span></div></td><td>
			<div id='globalpassword_text' class='globalpassword' style='display:none;'><input type = 'text' id='globalpassword_txt' name='globalpassword_txt' placeholder='Password for all post'></div></td></tr></table>
			</div>";
		return $importDataConfig;
	}

	/**
	 * Get upload directory
	 */
	public function getUploadDirectory($check = 'plugin_uploads')
	{
		$upload_dir = wp_upload_dir();
		if($check == 'plugin_uploads'){
			return $upload_dir ['basedir'] . "/" . $this->uploadDir;
		}else{
			return $upload_dir ['basedir'];
		}
	}

	/**
	 *	generate help tooltip
	 *	@param string $content ** content to show on tooltip **
	 *	@return string $html ** generated HTML **
	 **/
	public function generatehelp($content, $mapping_style = NULL)
	{
		$html = '<div style = "'.$mapping_style.'"> <a href="#" class="tooltip">
			<img src="'.WP_CONST_ULTIMATE_CSV_IMP_DIR.'images/help.png" />
			<span class="tooltipPostStatus">
			<img class="callout" src="'.WP_CONST_ULTIMATE_CSV_IMP_DIR.'images/callout.gif" />
			'.$content.'
			<img src="'.WP_CONST_ULTIMATE_CSV_IMP_DIR.'images/help.png" style="margin-top: 6px;float:right;" />
			</span> </a> </div>';
		return $html;
	}

	public function activate(){
		$csvfreesettings = array();
		$csvfreesettings['post'] = 'post';
		//$csvfreesettings['custompost'] = 'custompost';
		$csvfreesettings['page'] = 'page';
		$csvfreesettings['comments'] = 'comments';
		$csvfreesettings['users'] = 'users';
		$csvfreesettings['rcustompost'] = 'nonercustompost';
		$csvfreesettings['rseooption'] = 'nonerseooption';
		update_option('wpcsvfreesettings', $csvfreesettings);
	}

	public function deactivate(){
		delete_option('wpcsvfreesettings');
	}

	public static function output_fd_page()
	{
		if(!isset($_REQUEST['__module']))
		{
			wp_redirect( get_admin_url() . 'admin.php?page='.WP_CONST_ULTIMATE_CSV_IMP_SLUG.'/index.php&__module=post&step=uploadfile');
		}
		require_once(WP_CONST_ULTIMATE_CSV_IMP_DIRECTORY.'config/settings.php');
		require_once(WP_CONST_ULTIMATE_CSV_IMP_DIRECTORY.'lib/skinnymvc/controller/SkinnyController.php');

		$c = new SkinnyControllerWPCsvFree;
		$c->main();
	}

	public function getSettings(){
		return get_option('wpcsvfreesettings');
	}

	public function renderMenu()
	{
		include(plugin_dir_path(__FILE__) . '../templates/menu.php');
	}

	public function requestedAction($action,$step){
		$actions = array('dashboard','settings','help','users','comments','eshop','wpcommerce','woocommerce','categories','customtaxonomy','export', 'mappingtemplate');
		if(!in_array($action,$actions)){
			include(plugin_dir_path(__FILE__) . '../templates/view.php');
		}else{
			include(plugin_dir_path(__FILE__) . '../modules/'.$action.'/actions/actions.php');
			include(plugin_dir_path(__FILE__) . '../modules/'.$action.'/templates/view.php');
		}
	}

	/**
	 * Move CSV to the upload directory
	 */
	public function move_file()
	{
		if ($_FILES ["csv_import"] ["error"] == 0) {
			$tmp_name = $_FILES ["csv_import"] ["tmp_name"];
			$this->csvFileName = $_FILES ["csv_import"] ["name"];
			move_uploaded_file($tmp_name, $this->getUploadDirectory() . "/$this->csvFileName");
		}
	}

	/**
	 * Check upload dirctory permission
	 */
	function checkUploadDirPermission()
	{
		$this->getUploadDirectory();
		$upload_dir = wp_upload_dir();
		if (!is_dir($upload_dir ['basedir'])) {
			print " <div style='font-size:16px;margin-left:20px;margin-top:25px;'>" . $this->t("UPLOAD_PERMISSION_ERROR") . "
				</div><br/>
				<div style='margin-left:20px;'>
				<form class='add:the-list: validate' method='post' action=''>
				<input type='submit' class='button-primary' name='Import Again' value='" . $this->t("IMPORT_AGAIN") . "'/>
				</form>
				</div>";
			$this->freeze();
		} else {
			if (!is_dir($this->getUploadDirectory())) {
				wp_mkdir_p($this->getUploadDirectory());
			}
		}
	}


	/**
	 * Get field colum keys
	 */
	function getKeyVals()
	{
		$cust_fields='';
		$acf_field=array();
		global $wpdb;
		$active_plugins = get_option('active_plugins');
		$limit = ( int )apply_filters('postmeta_form_limit', 150);
		$this->keys = $wpdb->get_col("SELECT meta_key FROM $wpdb->postmeta
				GROUP BY meta_key
				HAVING meta_key NOT LIKE '\_%' and meta_key NOT LIKE 'field_%'
				ORDER BY meta_key
				LIMIT $limit");

		foreach ($this->keys as $val) {
			$this->defCols ["CF: " . $val] = $val;
		}



	}

	/**
	 * Function converts CSV data to formatted array.
	 *
	 * @param $file CSV
	 *            input filename
	 * @param $delim delimiter
	 *            for the CSV
	 * @return array formatted CSV output as array
	 */
	function csv_file_data($file, $delim)
	{
		$file = $this->getUploadDirectory() .'/'. $file;
		ini_set("auto_detect_line_endings", true);

		$data_rows = array();
		$this->delim = $delim;
		//print($this->delim);die;
# Check whether file is present in the given file location
		$fileexists = file_exists($file);

		if ($fileexists) {
			$resource = fopen($file, 'r');

			$init = 0;
			while ($keys = fgetcsv($resource, '', $this->delim, '"')) { 
				if ($init == 0) {
					$this->headers = $keys;
				} else {
					if (!(($keys[0] == null) && (count($keys) == 1)))
						array_push($data_rows, $keys);
				}
				$init++;
			}
			fclose($resource);
			ini_set("auto_detect_line_endings", false);
		} else {

		}
		return $data_rows;
	}


	/**
	 * Manage duplicates
	 *
	 * @param string type = (title|content), string content
	 * @return boolean
	 */
	function duplicateChecks($type = 'title', $text, $gettype)
	{
		global $wpdb;
		//$this->dupPostCount = 0;
		if ($type == 'content') {
			$htmlDecode = html_entity_decode($text);
			$strippedText = strip_tags($htmlDecode);
			$contentLength = strlen($strippedText);
			$allPosts_count = $wpdb->get_results("SELECT COUNT(ID) as count FROM $wpdb->posts WHERE post_type = \"{$gettype}\" and post_status IN('publish','future','draft','pending','private')");
			$allPosts_count = $allPosts_count[0]->count;
			$allPosts = $wpdb->get_results("SELECT ID,post_title,post_date,post_content FROM $wpdb->posts WHERE post_type = \"{$gettype}\" and post_status IN('publish','future','draft','pending','private')");
			foreach ($allPosts as $allPost) {
				$htmlDecodePCont = html_entity_decode($allPost->post_content);
				$strippedTextPCont = strip_tags($htmlDecodePCont);
				similar_text($strippedTextPCont, $strippedText, $p);
				if ($p == 100) {
					$this->dupPostCount++;
					return false;
				}
			}
			return true;
		} else if ($type == 'title') {
			$post_exist = $wpdb->get_results("select ID from " . $wpdb->posts . " where post_title = \"{$text}\" and post_type = \"{$gettype}\" and post_status in('publish','future','draft','pending','private')");
			if (count($post_exist) == 0 && ($text != null || $text != ''))
				return true;
		}
		$this->dupPostCount++;
		return false;
	}

	/**
	 * function to fetch the featured image from remote URL
	 *
	 */
	function get_fimg_from_URL($f_img,$fimg_path,$fimg_name,$post_slug_value){
		if($fimg_path!="" && $fimg_path){
			$fimg_path = $fimg_path . "/" . $post_slug_value . "-" . $fimg_name;
		}
		$ch = curl_init ($f_img);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		$rawdata=curl_exec($ch);
		curl_close ($ch);
		if(file_exists($fimg_path)){
			unlink($fimg_path);
		}
		$fp = fopen($fimg_path,'x');
		fwrite($fp, $rawdata);
		fclose($fp);
	}

	/**
	 * function to map the csv file and process it
	 *
	 * @return boolean
	 */
	function processDataInWP($data_rows,$ret_array,$session_arr)
	{
		global $wpdb;
		$post_id = '';
		$smack_taxo = array();
		$custom_array = array();
		$seo_custom_array= array();		
		$imported_feature_img = array();

		$headr_count = $ret_array['h2'];
		for ($i = 0; $i < count($data_rows); $i++) {
			if (array_key_exists('mapping' . $i, $ret_array)) { 
				if($ret_array ['mapping' . $i] != '-- Select --'){
					if ($ret_array ['mapping' . $i] != 'add_custom' . $i) {
						$strip_CF = strpos($ret_array['mapping' . $i], 'CF: ');
						if ($strip_CF === 0) {
							$custom_key = substr($ret_array['mapping' . $i], 4);
							$custom_array[$custom_key] = $data_rows[$i];
						} 
						else {
							$new_post[$ret_array['mapping' . $i]] = $data_rows[$i];
						}
					} else {
						$new_post [$ret_array ['textbox' . $i]] = $data_rows [$i];
						$custom_array [$ret_array ['textbox' . $i]] = $data_rows [$i];
					}
				}
			}
		}
		for ($inc = 0; $inc < count($data_rows); $inc++) {
			foreach ($this->keys as $k => $v) {
				if (array_key_exists($v, $new_post)) {
					$custom_array [$v] = $new_post [$v];
				}
			}
		}
		if(is_array( $new_post )){
			foreach ($new_post as $ckey => $cval) {
				$this->postFlag = true;
				$taxo = get_taxonomies();
				foreach ($taxo as $taxokey => $taxovalue) {
					if ($taxokey != 'category' && $taxokey != 'link_category' && $taxokey != 'post_tag' && $taxokey != 'nav_menu' && $taxokey != 'post_format') {
						if ($taxokey == $ckey) {
							$smack_taxo [$ckey] = $new_post [$ckey];
						}
					}
				}

				$taxo_check = 0;
				if (!isset($smack_taxo[$ckey])) {
					$smack_taxo [$ckey] = null;
					$taxo_check = 1;
				}
				if ($ckey != 'post_category' && $ckey != 'post_tag' && $ckey != 'featured_image' && $ckey != $smack_taxo [$ckey]) {
					if ($taxo_check == 1) {
						unset($smack_taxo[$ckey]);
						$taxo_check = 0;
					}
					if (array_key_exists($ckey, $custom_array)) {
						$darray [$ckey] = $new_post [$ckey];
					} else {
						if (array_key_exists($ckey, $smack_taxo)) {
							$data_array[$ckey] = null;
						} else {
							$data_array[$ckey] = $new_post [$ckey];
						}
					}
				} else {
					switch ($ckey) {
						case 'post_tag' :
							$tags [$ckey] = $new_post [$ckey];
							break;
						case 'post_category' :
							$categories [$ckey] = $new_post [$ckey];
							break;
						case 'featured_image' :
							require_once(ABSPATH . "wp-includes/pluggable.php");
							require_once(ABSPATH . 'wp-admin/includes/image.php');
							$dir = wp_upload_dir();
							$get_media_settings = get_option('uploads_use_yearmonth_folders');
							if($get_media_settings == 1){
								$dirname = date('Y') . '/' . date('m');
								$full_path = $dir ['basedir'] . '/' . $dirname;
								$baseurl = $dir ['baseurl'] . '/' . $dirname;
							}else{
								$full_path = $dir ['basedir'];
								$baseurl = $dir ['baseurl'];
							}

							$f_img = $new_post [$ckey];
							$fimg_path = $full_path;

							$fimg_name = @basename($f_img);
							$fimg_name = preg_replace("/[^a-zA-Z0-9._\s]/", "", $fimg_name);
							$fimg_name = preg_replace('/\s/', '-', $fimg_name);
							$fimg_name = urlencode($fimg_name);
							
							$parseURL = parse_url($f_img);
							$path_parts = pathinfo($f_img);
							if(!isset($path_parts['extension']))
								$fimg_name = $fimg_name . '.jpg';

							$f_img_slug = preg_replace("/[^a-zA-Z0-9._\s]/", "", $new_post['post_title']);
							$f_img_slug = preg_replace('/\s/', '-', $f_img_slug);

							$post_slug_value = strtolower($f_img_slug);
							$this->get_fimg_from_URL($f_img,$fimg_path,$fimg_name,$post_slug_value);
							$filepath = $fimg_path."/" . $post_slug_value . "-" . $fimg_name;
	
							if(@getimagesize($filepath)){
								$img = wp_get_image_editor($filepath);
								if (!is_wp_error($img)) {
									$sizes_array = array(
											// #1 - resizes to 1024x768 pixel, square-cropped image
											array('width' => 1024, 'height' => 768, 'crop' => true),
											// #2 - resizes to 100px max width/height, non-cropped image
											array('width' => 100, 'height' => 100, 'crop' => false),
											// #3 - resizes to 100 pixel max height, non-cropped image
											array('width' => 300, 'height' => 100, 'crop' => false),
											// #3 - resizes to 624x468 pixel max width, non-cropped image
											array('width' => 624, 'height' => 468, 'crop' => false)
											);
									$resize = $img->multi_resize($sizes_array);
								}
								$file ['guid'] = $baseurl."/".$fimg_name;
								$file ['post_title'] = $fimg_name;
								$file ['post_content'] = '';
								$file ['post_status'] = 'attachment';
							}
							else	{
								$file = false;
							}
							break;
					}
				}
			}
		}

		if($_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['selectedImporter'] != 'custompost'){
			$data_array['post_type'] = $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['selectedImporter'];
		}else{
			$data_array['post_type'] = $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['custompostlist'];
		}
		if ($this->titleDupCheck == 'true')
			$this->postFlag = $this->duplicateChecks('title', $data_array ['post_title'], $data_array ['post_type']);

		if ($this->conDupCheck == 'true' && $this->postFlag)
			$this->postFlag = $this->duplicateChecks('content', $data_array ['post_content'], $data_array ['post_type']);

		if ($this->postFlag) {
			unset ($sticky);
			if (empty($data_array['post_status']))
				$data_array['post_status'] = null;

			if ($_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['importallwithps'] != 0)
				$data_array['post_status'] = $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['importallwithps'];

			switch ($data_array ['post_status']) {
				case 1 :
					$data_array['post_status'] = 'publish';
					break;
				case 2 :
					$data_array['post_status'] = 'publish';
					$sticky = true;
					break;
				case 3 :
					$data_array['post_status'] = 'publish';
					$data_array ['post_password'] = $_POST ['postsPassword'];
					break;
				case 4 :
					$data_array ['post_status'] = 'private';
					break;
				case 5 :
					$data_array ['post_status'] = 'draft';
					break;
				case 6 :
					$data_array ['post_status'] = 'pending';
					break;
				default :
					$poststatus = $data_array['post_status'] = strtolower($data_array['post_status']);
					if ($data_array['post_status'] != 'publish' && $data_array['post_status'] != 'private' && $data_array['post_status'] != 'draft' && $data_array['post_status'] != 'pending' && $data_array['post_status'] != 'sticky') {
						$stripPSF = strpos($data_array['post_status'], '{');
						if ($stripPSF === 0) {
							$poststatus = substr($data_array['post_status'], 1);
							$stripPSL = substr($poststatus, -1);
							if ($stripPSL == '}') {
								$postpwd = substr($poststatus, 0, -1);
								$data_array['post_status'] = 'publish';
								$data_array ['post_password'] = $postpwd;
							} else {
								$data_array['post_status'] = 'publish';
								$data_array ['post_password'] = $poststatus;
							}
						} else {
							$data_array['post_status'] = 'publish';
						}
					}
					if ($data_array['post_status'] == 'sticky') {
						$data_array['post_status'] = 'publish';
						$sticky = true;
					}

			}
			// Author name/id update
			if(isset($data_array ['post_author'])){
				$authorLen = strlen($data_array ['post_author']);
				$postuserid = $data_array ['post_author'];
				$checkpostuserid = intval($data_array ['post_author']);
				$postAuthorLen = strlen($checkpostuserid);
				$postauthor = array();

				if ($authorLen == $postAuthorLen) {
					$postauthor = $wpdb->get_results("select ID from $wpdb->users where ID = \"{$postuserid}\"");
					if(empty($postauthor) || !$postauthor[0]->ID) { // If user name are numeric Ex: 1300001
						$postauthor = $wpdb->get_results("select ID from $wpdb->users where user_login = \"{$postuserid}\"");
					}
				} else {
					$postauthor = $wpdb->get_results("select ID from $wpdb->users where user_login = \"{$postuserid}\"");
				}

				if (empty($postauthor) || !$postauthor[0]->ID) {
					$data_array ['post_author'] = 1;
					$this->noPostAuthCount++;
				} else {
					$data_array ['post_author'] = $postauthor [0]->ID;
				}
			}
			else{
				$data_array ['post_author'] = 1;
				$this->noPostAuthCount++;
			}

			// Date format post
			if (!isset($data_array ['post_date'])){
				$data_array ['post_date'] = date('Y-m-d H:i:s');
			}else{
				$data_array ['post_date'] = date('Y-m-d H:i:s', strtotime($data_array ['post_date']));
			}
			if(isset($data_array ['post_slug'])){
				$data_array ['post_name'] = $data_array ['post_slug'];
			}

			//add global password
			if($data_array){
				if($ret_array['importallwithps'] == 3){
					$data_array['post_password'] = $ret_array['globalpassword_txt'];

				}
			}
			//print('<pre>');print_r($data_array);die;
			if ($data_array)
				$post_id = wp_insert_post($data_array);

			unset($postauthor);
			if ($post_id) {
				$uploaded_file_name=$session_arr['uploadedFile'];
				$real_file_name = $session_arr['uploaded_csv_name'];
				//                                $version = $session_arr['currentfileversion'];
				$action = $data_array['post_type'];
				/*				$version_arr=array();
								$version_arr=explode("(",$uploaded_file_name);
								$version_arr=explode(")",$version_arr[1]);
								$version=$version_arr[0]; */
				$get_imported_feature_image = array();
				$get_imported_feature_image = get_option('IMPORTED_FEATURE_IMAGES');
				if(is_array($get_imported_feature_image)){
					$imported_feature_img = array_merge($get_imported_feature_image, $imported_feature_img);
				}
				else{
					$imported_feature_img = $imported_feature_img;
				}
				update_option('IMPORTED_FEATURE_IMAGES', $imported_feature_img);
				$created_records[$action][] = $post_id;
				if($action == 'post'){
					$imported_as = 'Post';
				}
				if($action == 'page'){
					$imported_as = 'Page';
				}
				if($action != 'post' && $action != 'page'){
					$imported_as = 'Custom Post';
				}
				$keyword = $action;
				$this->insPostCount++;
				if (isset($sticky) && $sticky)
					stick_post($post_id);

				if (!empty ($custom_array)) {
					foreach ($custom_array as $custom_key => $custom_value) {
						add_post_meta($post_id, $custom_key, $custom_value);
					}
				}


				// Create custom taxonomy to post
				if (!empty ($smack_taxo)) {
					foreach ($smack_taxo as $taxo_key => $taxo_value) {
						if (!empty($taxo_value)) {
							$split_line = explode('|', $taxo_value);
							wp_set_object_terms($post_id, $split_line, $taxo_key);
						}
					}
				}

				// Create/Add tags to post
				if (!empty ($tags)) {
					foreach ($tags as $tag_key => $tag_value) {
						wp_set_post_tags($post_id, $tag_value);
					}
				}

				// Create/Add category to post
				if (!empty ($categories)) {
					$split_cate = explode('|', $categories ['post_category']);
					foreach ($split_cate as $key => $val) {
						if (is_numeric($val))
							$split_cate[$key] = 'uncategorized';
					}
					wp_set_object_terms($post_id, $split_cate, 'category');
				}
				// Add featured image
				if (!empty ($file)) {
					//$wp_filetype = wp_check_filetype(@basename($file ['guid']), null);
					$wp_upload_dir = wp_upload_dir();
					$attachment = array(
							'guid' => $file ['guid'],
							'post_mime_type' => 'image/jpeg',
							'post_title' => preg_replace('/\.[^.]+$/', '', @basename($file ['guid'])),
							'post_content' => '',
							'post_status' => 'inherit'
							);
					if($get_media_settings == 1){
						$generate_attachment = $dirname . '/' . $post_slug_value . '-' .  $fimg_name;
					}else{
						$generate_attachment = $fimg_name;
					}
					$uploadedImage = $wp_upload_dir['path'] . '/' . $post_slug_value . '-' . $fimg_name;
					$attach_id = wp_insert_attachment($attachment, $generate_attachment, $post_id);
					$attach_data = wp_generate_attachment_metadata($attach_id, $uploadedImage);
					wp_update_attachment_metadata($attach_id, $attach_data);
					set_post_thumbnail($post_id, $attach_id);
				}
			}
			else{
				$skippedRecords[] = $_SESSION['SMACK_SKIPPED_RECORDS'];
			}
		}
		unset($data_array);
	}

	/**
	 * Delete uploaded file after import process
	 */
	function deletefileafterprocesscomplete($uploadDir) {
		//array_map('unlink', glob("$uploadDir/*"));
		$files = array_diff(scandir($uploadDir), array('.','..')); 
		foreach ($files as $file) { 
			(is_dir("$uploadDir/$file")) ? rmdir("$uploadDir/$file") : unlink("$uploadDir/$file"); 
		} 
	}

	// Function convert string to hash_key
	public function convert_string2hash_key($value) {
		$file_name = hash_hmac('md5', "$value", 'secret');
		return $file_name;
	}

	// Function for common footer
	public function common_footer_for_other_plugin_promotions(){
		$content = '<div class="accordion-inner">
			<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/" target="_blank">Social All in One Bot</a></label>
			<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/google-seo-author-snippet-plugin/" target="_blank">Google SEO Author Snippet</a></label>
			<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/" target="_blank">WP Advanced Importer</a></label>
			<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/wp-vtiger/" target="_blank">WP Tiger</a></label>
			<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/" target="_blank">WP Sugar</a></label>
			<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/" target="_blank">WP Zoho crm Sync</a></label>
			<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/" target="_blank">CRM Ecommerce Integration</a></label>

			<label class="plugintags"><a href="http://www.smackcoders.com/wp-ultimate-csv-importer-pro.html" target="_blank">WP Ultimate CSV Importer Pro</a></label>
			<label class="plugintags"><a href="http://www.smackcoders.com/pro-wordpress-vtiger-webforms-module.html" target="_blank">WP Tiger Pro</a></label>
			<label class="plugintags"><a href="http://www.smackcoders.com/wordpress-sugar-integration-automated-multi-web-forms-generator-pro.html" target="_blank">WordPress Sugar Pro</a></label>
			<label class="plugintags"><a href="http://www.smackcoders.com/vtigercrm6-magento-connector.html" target="_blank">VTiger 6 Magento Sync</a></label>
			<label class="plugintags"><a href="http://www.smackcoders.com/vtigercrm-mailchimp-integration.html" target="_blank">VTiger 6 Mailchimp</a></label>
			<label class="plugintags"><a href="http://www.smackcoders.com/vtiger-quickbooks-integration-module.html" target="_blank">Vtiger QuickBooks</a></label>
			<label class="plugintags"><a href="http://www.smackcoders.com/xero-vtiger-integration.html" target="_blank">Vtiger Xero Sync</a></label>
			<label class="plugintags"><a href="http://www.smackcoders.com/vtiger-crm-hrm-payroll-modules.html" target="_blank">Vtiger HR and Payroll</a></label>
			<label class="plugintags"><a href="http://www.smackcoders.com/hr-payroll.html" target="_blank">HR Payroll</a></label>
			<div style="position:relative;float:right;"><a href="http://www.smackcoders.com/"><img width=80 src="http://www.smackcoders.com/skin/frontend/default/megashop/images/logo.png" /></a></div>
			</div>';
		echo $content;
	}

	// Function for social sharing
	public function importer_social_profile_share() {
		$urlCurrentPage = "http://www.smackcoders.com/wp-ultimate-csv-importer.html";
		$fbimgsrc = WP_CONTENT_URL . "/plugins/" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/images/facebook.png";
		$googleimgsrc = WP_CONTENT_URL . "/plugins/" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/images/googleplus.png";
		$linkedimgsrc = WP_CONTENT_URL . "/plugins/" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/images/linkedin.png";
		$twitimgsrc = WP_CONTENT_URL . "/plugins/" . WP_CONST_ULTIMATE_CSV_IMP_SLUG . "/images/twitter.png";
		$strPageTitle = 'WP Ultimate CSV Importer';
		$linked_in_username = 'smackcoders';

		//Facebook
		$htmlShareButtons = '<span class="sociallink">';
		$htmlShareButtons .= '<a id="wpcsv_facebook_share" href="http://www.facebook.com/sharer.php?u=' . $urlCurrentPage  . '" target="_blank">';
		$htmlShareButtons .= '<img title="Facebook" class="wpcsv" src="' . $fbimgsrc . '" alt="Facebook" />';
		$htmlShareButtons .= '</a>';
		$htmlShareButtons .= '</span>';

		//Google Plus
		$htmlShareButtons .= '<span class="sociallink">';
		$htmlShareButtons .= '<a id="wpcsv_google_share" href="https://plus.google.com/share?url=' . $urlCurrentPage  . '" target="_blank" >';
		$htmlShareButtons .= '<img title="Google+" class="wpcsv" src="' . $googleimgsrc . '" alt="Google+" />';
		$htmlShareButtons .= '</a>';
		$htmlShareButtons .= '</span>';

		//Linked in
		$htmlShareButtons .= '<span class="sociallink">';
		$htmlShareButtons .= '<a id="wpcsv_linkedin_share" class="wpcsv_share_link" href="http://www.linkedin.com/shareArticle?mini=true&url=' . urlencode($urlCurrentPage)  . '&title='.urlencode($strPageTitle).'&source='.$linked_in_username.'" target="_blank" >';
		$htmlShareButtons .= '<img title="LinkedIn" class="wpcsv" src="' . $linkedimgsrc . '" alt="LinkedIn" />';
		$htmlShareButtons .= '</a>';
		$htmlShareButtons .= '</span>';

		//Twitter
		$username = "smackcoders";
		// format the URL into friendly code
		$twitterShareText = urlencode(html_entity_decode($strPageTitle . ' ', ENT_COMPAT, 'UTF-8'));
		// twitter share link
		$htmlShareButtons .= '<span class="sociallink">';
		$htmlShareButtons .= '<a id="wpcsv_twitter_share" href="http://twitter.com/share?url=' . $urlCurrentPage .'&via='.$username.'&related='.$username.'&text=' . $twitterShareText . '" target="_blank">';
		$htmlShareButtons .= '<img title="Twitter" class="wpcsv" src="' . $twitimgsrc . '" alt="Twitter" />';
		$htmlShareButtons .= '</a>';
		$htmlShareButtons .= '</span>';
		echo $htmlShareButtons;
	}

}
class CallWPImporterObj extends WPImporter_includes_helper
{
	private static $_instance = null;
	public static function getInstance()
	{
		if( !is_object(self::$_instance) )  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
			self::$_instance = new WPImporter_includes_helper();
		return self::$_instance;
	}
}// CallSkinnyObj Class Ends
?>
