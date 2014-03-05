<?php
/******************************
 * Filename	: modules/comments/actions/actions.php
 * Description	: This module helps to import comments to the relevant post's/page's.
 * Author       : Fredrick
 * Owner        : smackcoders.com
 * Date         : Jan31,2014
 */
class CommentsActions extends SkinnyActions 
{
	public function __construct()
	{

	}

	// @var boolean post title check
	public $titleDupCheck = false;

	// @var boolean content title check
	public $conDupCheck = false;

	// @var boolean for post flag
	public $postFlag = true;

	// @var array wp field keys
	public $keys = array();

	// @var inserted comments count
	public $insPostCount = 0;

	// @var int updated post count
	public $updatedPostCount=0;

	// @var skipped comments coun
	public $dupPostCount = 0;

	/**
	 * Mapping fields
	 */
	public $defCols = array(   'comment_post_ID' => null,
			'comment_author'  => null,
			'comment_author_email'  => null,
			'comment_author_url' => null,
			'comment_content' => null,
			'comment_author_IP' => null,
			'comment_date' =>null,
			'comment_approved' =>null,
			);


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

	/**
	 * Manage duplicates
	 *
	 * @param string type = (title|content), string content
	 * @return boolean
	 */
	function duplicateChecks($type = 'title', $text, $gettype)
	{ 
		$gettype = 'post';
		global $wpdb;
		$this->dupPostCount = 0;
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
	 * function to map the csv file and process it
	 *
	 * @return boolean
	 */
	function processDataInWP($data_rows,$ret_array,$session_arr)
	{
		global $wpdb;
		$smack_taxo = array();
		$custom_array = array();
		$impCE = new WPImporter_includes_helper();
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
		for ($inc = 0; $inc < count($data_rows); $inc++) {
			foreach ($this->keys as $k => $v) {
				if (array_key_exists($v, $new_post)) {
					$custom_array [$v] = $new_post [$v];
				}
			}
		}
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
						/*
						 * TODO: Cleanup required
						 */
						$split_filename = explode('/', htmlentities($new_post [$ckey]));
						$arr_filename = count($split_filename);
						$plain_filename = $split_filename [$arr_filename - 1];
						$new_post [$ckey] = str_replace(' ', '%20', $new_post [$ckey]);
						$file_url = $filetype [$ckey] = $new_post [$ckey];
						$file_type = explode('.', $filetype [$ckey]);
						$count = count($file_type);
						$type = $file_type [$count - 1];

						if ($type == 'png') {
							$file ['post_mime_type'] = 'image/png';
						} else if ($type == 'jpg' || $type == 'jpeg') {
							$file ['post_mime_type'] = 'image/jpeg';
						} else if ($type == 'gif') {
							$file ['post_mime_type'] = 'image/gif';
						}
						$img_name = explode('/', $file_url);
						$imgurl_split = count($img_name);
						$img_name = explode('.', $img_name [$imgurl_split - 1]);
						if (count($img_name) > 2) {
							for ($r = 0; $r < (count($img_name) - 1); $r++) {
								if ($r == 0)
									$img_title = $img_name[$r];
								else
									$img_title .= '.' . $img_name[$r];
							}
						} else {
							$img_title = $img_name = $img_name [0];
						}
						$attachmentName = urldecode($img_title) . '.' . $type;
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
						$filename = explode('/', $file_url);
						$file_split = count($filename);
						$filepath = $full_path . '/' . urldecode($plain_filename);
						$fileurl = $baseurl . '/' . $filename [$file_split - 1];
						if (is_dir($full_path)) {
							$smack_fileCopy = @copy($file_url, $filepath);
						} else {
							wp_mkdir_p($full_path);
							$smack_fileCopy = @copy($file_url, $filepath);
						}
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
						if ($smack_fileCopy) {
							$file ['guid'] = $fileurl;
							$file ['post_title'] = $img_title;
							$file ['post_content'] = '';
							$file ['post_status'] = 'attachment';
						} else {
							$file = false;
						}
						break;
				}
			}
		}
		//$data_array['post_type'] = "post";
		if ($this->titleDupCheck == 'true')
		{
			//$this->postFlag = $this->duplicateChecks('title', $data_array ['post_title'], $data_array ['post_type']);
		}
		if ($this->conDupCheck == 'true')
		{
			//$this->postFlag = $this->duplicateChecks('content', $data_array ['post_content'], $data_array ['post_type']);
		}
		if ($this->postFlag) {
			$uploaded_file_name=$session_arr['uploadedFile'];
			$real_file_name = $session_arr['uploaded_csv_name'];
			$action=$session_arr['selectedImporter'];
/*			$version_arr=array();
			$version_arr=explode("(",$uploaded_file_name);
			$version_arr=explode(")",$version_arr[1]);
			$version=$version_arr[0]; */
			$imported_as = 'Comments';
			$keyword = $action;
			$cmtID=$this->addComment($data_array);
		}
	}

	
	
	//add comments
	public function addComment($dat_array){ 
		global $wpdb;
		$commentid = '';
		$post_id = $dat_array['comment_post_ID'];
		$post_exists = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE id = '" . $post_id . "' and post_status in ('publish','draft','future','private','pending')", 'ARRAY_A');
		if($post_exists)
		{
			$commentid=wp_insert_comment($dat_array); 

			if($commentid)
				$this->insPostCount+=1;
			else
				$this->dupPostCount+=1;
		}
		else
		{
			$this->dupPostCount+=1;
		}
		return $commentid;
	}//add comments ends
}
