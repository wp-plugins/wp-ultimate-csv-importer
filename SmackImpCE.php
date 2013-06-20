<?php
/**
 * @author fenzik
 * Common class for Smackcoder's CSV Importer CE
 */
require_once ("SmackWpHandler.php");
class SmackImpCE extends SmackWpHandler {
	
	// @var string CSV upload directory name
	public $uploadDir = 'ultimate_importer';
	
	// @var boolean post title check
	public $titleDupCheck = false;
	
	// @var boolean content title check
	public $conDupCheck = false;
	
	// @var string delimiter
	public $delim = ",";
	
	// @var array delilimters supported by CSV importer
	public $delim_avail = array (
			',',
			';' 
	);
	
	// @var array wp field keys
	public $keys = array ();
	
	// @var array for default columns
	public $defCols = array (
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
			'post_status' => 0
	);
	
	// @var array CSV headers
	public $headers = array ();
	
	// @var boolean for post flag
	public $postFlag = false;
	
	// @var int duplicate post count
	public $dupPostCount = 0;
	
	// @var int inserted post count
	public $insPostCount = 0;
	
	// @var int no post author count
	public $noPostAuthCount = 0;
	
	// @var string CSV file name
	public $csvFileName;
	
	/**
	 */
	function __construct() {
		$this->getKeyVals ();
	}
	
	/**
	 * Manage duplicates
	 *
	 * @param
	 *        	string type = (title|content), string content
	 */
	function duplicateChecks($type = 'title', $text, $gettype) {
		global $wpdb;
		
		if ($type == 'content') {
			$contentLength = strlen ( $text );
			$post_exist = $wpdb->get_results ( "select ID from " . $wpdb->posts . " where length(post_content) = \"{$contentLength}\"" );
			if (count ( $post_exist ) > 0) {
				$chkforeach = 0;
				foreach ( $post_exist as $singlepost ) {
					$postdata = $wpdb->get_results ( "select post_content from " . $wpdb->posts . " where id = \"{$singlepost->ID}\"" );
					if (substr ( $postdata [0]->post_content, 0, 50 ) == substr ( $text, 0, 50 )) {
						$this->dupPostCount ++;
						return false;
					}
				}
			} else {
				return true;
			}
		} else if ($type == 'title') {
			$post_exist = $wpdb->get_results ( "select ID from " . $wpdb->posts . " where post_title = \"{$text}\" and post_type = \"{$gettype}\" and post_status in('publish','future','draft','pending','private')" );
			if (count ( $post_exist ) == 0 && ($text != null || $text != ''))
				return true;
		}
		$this->dupPostCount ++;
		return false;
	}
	
	/**
	 * Get upload directory
	 */
	function getUploadDirectory() {
		$upload_dir = wp_upload_dir ();
		return $upload_dir ['basedir'] . "/" . $this->uploadDir;
	}
	
	/**
	 * Move CSV to the upload directory
	 */
	function move_file() {
		if ($_FILES ["csv_import"] ["error"] == 0) {
			$tmp_name = $_FILES ["csv_import"] ["tmp_name"];
			$this->csvFileName = $_FILES ["csv_import"] ["name"];
			move_uploaded_file ( $tmp_name, $this->getUploadDirectory () . "/$this->csvFileName" );
		}
	}
	
	/**
	 * Remove CSV file
	 */
	function fileDelete($filepath, $filename) {
		if (file_exists ( $filepath . $filename ) && $filename != "" && $filename != "n/a") {
			unlink ( $filepath . $filename );
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Get field colum keys
	 */
	function getKeyVals() {
		global $wpdb;
		$limit = ( int ) apply_filters ( 'postmeta_form_limit', 30 );
		$this->keys = $wpdb->get_col ( "SELECT meta_key FROM $wpdb->postmeta
				GROUP BY meta_key
				HAVING meta_key NOT LIKE '\_%'
				ORDER BY meta_key
				LIMIT $limit" );
		
		foreach ( $this->keys as $val ) {
			$this->defCols ["CF: " . $val] = $val;
		}
	}
	
	/**
	 * Check upload dirctory permission
	 */
	function checkUploadDirPermission() {
		$this->getUploadDirectory ();
		$upload_dir = wp_upload_dir ();
		if (! is_dir ( $upload_dir ['basedir'] )) {
			print " <div style='font-size:16px;margin-left:20px;margin-top:25px;'>" . $this->t ( "UPLOAD_PERMISSION_ERROR" ) . "
			</div><br/>
			<div style='margin-left:20px;'>
			<form class='add:the-list: validate' method='post' action=''>
			<input type='submit' class='button-primary' name='Import Again' value='" . $this->t ( "IMPORT_AGAIN" ) . "'/>
			</form>
			</div>";
			$this->freeze ();
		} else {
			if (! is_dir ( $this->getUploadDirectory () )) {
				wp_mkdir_p ( $this->getUploadDirectory () );
			}
		}
	}
	
	/**
	 * Function converts CSV data to formatted array.
	 *
	 * @param $file CSV
	 *        	input filename
	 * @param $delim delimiter
	 *        	for the CSV
	 * @return array formatted CSV output as array
	 */
	function csv_file_data($file, $delim) {
		$this->checkUploadDirPermission ();
		ini_set ( "auto_detect_line_endings", true );
		
		$data_rows = array ();
		$resource = fopen ( $file, 'r' );
		
		$init = 0;
		while ( $keys = fgetcsv ( $resource, '', $this->delim, '"' ) ) {
			if ($init == 0) {
				$this->headers = $keys;
			} else {
				array_push ( $data_rows, $keys );
			}
			$init ++;
		}
		fclose ( $resource );
		ini_set ( "auto_detect_line_endings", false );
		return $data_rows;
	}
	
	/**
	 * function to map the csv file and process it
	 *
	 * @return boolean
	 */
	function processDataInWP() {
		global $wpdb;
		
		$smack_taxo = array ();
		$custom_array = array ();
		
		$data_rows = $this->csv_file_data ( $this->getUploadDirectory () . "/" . $_POST ['filename'], $this->delim );
		
		foreach ( $_POST as $postkey => $postvalue ) {
			if ($postvalue != '-- Select --') {
				$ret_array [$postkey] = $postvalue;
			}
		}
		
		foreach ( $data_rows as $key => $value ) {
			
			for($i = 0; $i < count ( $value ); $i ++) {
				if (array_key_exists ( 'mapping' . $i, $ret_array )) {
					if ($ret_array ['mapping' . $i] != 'add_custom' . $i) {
						$strip_CF = strpos($ret_array['mapping'.$i],'CF: ');
						if($strip_CF === 0){
							$custom_key = substr($ret_array['mapping'.$i], 4);
							$custom_array[$custom_key] = $value[$i];
						}
						else{
							$new_post[$ret_array['mapping'.$i]] = $value[$i];
						}

					} else {
						$new_post [$ret_array ['textbox' . $i]] = $value [$i];
						$custom_array [$ret_array ['textbox' . $i]] = $value [$i];
					}
				}
			}
			for($inc = 0; $inc < count ( $value ); $inc ++) {
				foreach ( $this->keys as $k => $v ) {
					if (array_key_exists ( $v, $new_post )) {
						$custom_array [$v] = $new_post [$v];
					}
				}
			}
			
			foreach ( $new_post as $ckey => $cval ) {
				$this->postFlag = true;
				$taxo = get_taxonomies ();
				foreach ( $taxo as $taxokey => $taxovalue ) {
					if ($taxokey != 'category' && $taxokey != 'link_category' && $taxokey != 'post_tag' && $taxokey != 'nav_menu' && $taxokey != 'post_format') {
						if ($taxokey == $ckey) {
							$smack_taxo [$ckey] = $new_post [$ckey];
						}
					}
				}
				if ($ckey != 'post_category' && $ckey != 'post_tag' && $ckey != 'featured_image' && $ckey != $smack_taxo [$ckey]) {
					if (array_key_exists ( $ckey, $custom_array )) {
						$darray [$ckey] = $new_post [$ckey];
					} else {
						if (array_key_exists ( $ckey, $smack_taxo )) {
						} else {
							$data_array [$ckey] = $new_post [$ckey];
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
							/*
							 * TODO: Cleanup required
							 */
							$split_filename = explode ( '/', htmlentities ( $new_post [$ckey] ) );
							$arr_filename = count ( $split_filename );
							$plain_filename = $split_filename [$arr_filename - 1];
							$new_post [$ckey] = str_replace ( ' ', '%20', $new_post [$ckey] );
							$file_url = $filetype [$ckey] = $new_post [$ckey];
							$file_type = explode ( '.', $filetype [$ckey] );
							$count = count ( $file_type );
							$type = $file_type [$count - 1];
							if ($type == 'png') {
								$file ['post_mime_type'] = 'image/png';
							} else if ($type == 'jpg' || $type == 'jpeg') {
								$file ['post_mime_type'] = 'image/jpeg';
							} else if ($type == 'gif') {
								$file ['post_mime_type'] = 'image/gif';
							}
							$img_name = explode ( '/', $file_url );
							$imgurl_split = count ( $img_name );
							$img_name = explode ( '.', $img_name [$imgurl_split - 1] );
                                                        if(count($img_name) > 2){
                                                                for($r=0;$r<(count($img_name)-1);$r++){
                                                                        if($r==0)
                                                                                $img_title = $img_name[$r];
                                                                        else
                                                                                $img_title .= '.'.$img_name[$r];
                                                                }
                                                                $img_name = $img_title;
                                                        }
                                                        else{
                                                        $img_title = $img_name = $img_name [0];
                                                        }
							$dir = wp_upload_dir ();
                                                        $dirname = date('Y').'/'.date('m');
							$full_path = $dir ['basedir'] . '/' . $dirname;
							$baseurl = $dir ['baseurl'] . '/' . $dirname;
							$filename = explode ( '/', $file_url );
							$file_split = count ( $filename );
							$filepath = $full_path . '/' . $plain_filename;
							$fileurl = $baseurl . '/' . $filename [$file_split - 1];
							if(is_dir($full_path)){
								$smack_fileCopy = @copy($file_url,$filepath);
							}
							else{
								wp_mkdir_p($full_path);
								$smack_fileCopy = @copy($file_url,$filepath);
							}
                                                        $img = wp_get_image_editor( $filepath );
                                                        if ( ! is_wp_error( $img ) ) {

                                                            $sizes_array =     array(
                                                                // #1 - resizes to 1024x768 pixel, square-cropped image
                                                                array ('width' => 1024, 'height' => 768, 'crop' => false),
                                                                // #2 - resizes to 100px max width/height, non-cropped image
                                                                array ('width' => 150, 'height' => 150, 'crop' => false),
                                                                // #3 - resizes to 100 pixel max height, non-cropped image
                                                                array ('width' => 330, 'height' => 220, 'crop' => false),
                                                                // #3 - resizes to 624x468 pixel max width, non-cropped image
                                                                array ('width' => 624, 'height' => 468, 'crop' => false)
                                                            );
                                                            $resize = $img->multi_resize( $sizes_array );
                                                        }
							if ($smack_fileCopy) {
								$file ['guid'] = $fileurl;
								$file ['post_title'] = $img_title;
								$file ['post_content'] = '';
								$file ['post_status'] = 'inherit';
							} else {
								$file = false;
							}
							break;
					}
				}
			}

			$data_array['post_type'] = $_POST['csv_importer_cat'];
			
			if ($this->titleDupCheck) 
				$this->postFlag = $this->duplicateChecks ( 'title', $data_array ['post_title'], $data_array ['post_type'] );

			if ($this->conDupCheck && $this->postFlag)
				$this->postFlag = $this->duplicateChecks ( 'content', $data_array ['post_content'], '' );

			if ($this->postFlag) {
				unset ( $sticky );
			if($_POST['importallwithps'] != 0)
				$data_array['post_status'] = $_POST['importallwithps'];
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
						$poststatus = $data_array['post_status'] = strtolower( $data_array['post_status'] );
						if($data_array['post_status'] != 'publish' && $data_array['post_status'] != 'private' && $data_array['post_status'] != 'draft' && $data_array['post_status'] != 'pending' && $data_array['post_status'] != 'sticky'){
							$stripPSF = strpos($data_array['post_status'],'{');
							if($stripPSF === 0){
								$poststatus = substr($data_array['post_status'], 1);
								$stripPSL = substr($poststatus, -1); 
	                                                        if($stripPSL == '}'){
									$postpwd = substr($poststatus,0, -1);
									$data_array['post_status'] = 'publish';
									$data_array ['post_password'] = $postpwd;
								}
								else{
                                                                        $data_array['post_status'] = 'publish';
                                                                        $data_array ['post_password'] = $poststatus;
								}
							}
							else{
								$data_array['post_status'] = 'publish';
							}
						}
						if($data_array['post_status'] == 'sticky'){
							$data_array['post_status'] = 'publish';
	                                                $sticky = true;
						}

                                }
				// Author name/id update
				$authorLen = strlen($data_array ['post_author']);
				$postuserid = $data_array ['post_author'];
				$checkpostuserid = intval($data_array ['post_author']);
				$postAuthorLen = strlen($checkpostuserid);

				if($authorLen == $postAuthorLen){
					$postauthor = $wpdb->get_results ( "select ID from $wpdb->users where ID = \"{$postuserid}\"" );
				}
				else{ 
					$postauthor = $wpdb->get_results ( "select ID from $wpdb->users where user_login = \"{$postuserid}\"" );
				}

				if (! $postauthor [0]->ID) {
					$data_array ['post_author'] = 1;
					$this->noPostAuthCount ++;
				} else {
					$data_array ['post_author'] = $postauthor [0]->ID;
				}

				// Date format post
				if (! $data_array ['post_date'])
					$data_array ['post_date'] = date ( 'Y-m-d H:i:s' );
				$data_array ['post_date'] = date ( 'Y-m-d H:i:s', strtotime ( $data_array ['post_date'] ) );
				if ($data_array)
					$post_id = wp_insert_post ( $data_array );
				unset($data_array);
				unset($postauthor);
				if ($post_id) {
					$this->insPostCount ++;
					if ($sticky)
						stick_post ( $post_id );
					
					if (! empty ( $custom_array )) {
						foreach ( $custom_array as $custom_key => $custom_value ) {
							add_post_meta ( $post_id, $custom_key, $custom_value );
						}
					}
					
					// Create custom taxonomy to post
					if (! empty ( $smack_taxo )) {
						foreach ( $smack_taxo as $taxo_key => $taxo_value ) {
							$split_line = explode ( '|', $taxo_value );
							wp_set_object_terms ( $post_id, $split_line, $taxo_key );
						}
					}
					
					// Create/Add tags to post
					if (! empty ( $tags )) {
						foreach ( $tags as $tag_key => $tag_value ) {
							wp_set_post_tags ( $post_id, $tag_value );
						}
					}
					
					// Create/Add category to post
					if (! empty ( $categories )) {
						$split_cate = explode ( '|', $categories ['post_category'] );
						foreach ( $split_cate as $key => $val ) {
							if (is_numeric ( $val ))
								$split_cate[$key] = 'uncategorized';
						}
						wp_set_object_terms ( $post_id, $split_cate, 'category' );
					}
					// Add featured image
					if (! empty ( $file )) {
						$file_name = $dirname . '/' . $img_title . '.' . $type;
						$attach_id = wp_insert_attachment ( $file, $file_name, $post_id );
						require_once (ABSPATH . 'wp-admin/includes/image.php');
						$attach_data = wp_generate_attachment_metadata ( $attach_id, $fileurl );
						wp_update_attachment_metadata ( $attach_id, $attach_data );
						set_post_thumbnail ( $post_id, $attach_id );
					}
				}
			}
		}
		
		if (file_exists ( $this->getUploadDirectory () . $_POST ['filename'] )) {
			$this->fileDelete ( $this->getUploadDirectory (), $_POST ['filename'] );
		}
	}
}
?>
