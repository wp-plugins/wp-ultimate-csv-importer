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

class EshopActions extends SkinnyActions {

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
        $data = array();
        $get_importer_settings = get_option('wpcsvfreesettings');
		if (in_array('eshop', $get_importer_settings)) {
			$data['is_enable'] = 'on';
		} else {
			$data['is_enable'] = 'off';
		}
		return $data;
     
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
	public $updatedPostCount = 0;

	// @var array wp field keys
	public $keys = array();

        // @var Multi images
        public $MultiImages = false;

	public $detailedLog = array();

	/**
	 * Mapping fields
	 */
	public $defCols = array('post_title' => null, 'post_content' => null, 'post_excerpt' => null, 'post_date' => null, 'post_name' => null, 'post_status' => null, 'post_author' => null, 'post_parent' => 0, 'comment_status' => 'open', 'ping_status' => 'open', 'SKU' => null, 'products_option' => null, 'sale_price' => 0, 'regular_price' => 0, 'description' => null, 'shiprate' => 'no', 'optset' => null, 'featured_product' => 'no', 'product_in_sale' => 'no', 'stock_available' => 'no', 'cart_option' => 'null', 'category' => null, 'tags' => null, 'featured_image' => null,);
        
     /**
	 * Manage duplicates
	 *
	 * @param string type = (title|content), string content
	 * @return boolean
	 */
	function duplicateChecks($type = 'title', $text, $gettype, $currentLimit, $postTitle)
	{
		global $wpdb;
                $gettype = 'post';
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
					$this->detailedLog[$currentLimit]['post_id'] = "Created record no $currentLimit - failed";
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
		$this->detailedLog[$currentLimit]['post_id'] = "Created record no $currentLimit - failed";
		return false;
	}
         
       /**
	 * Get field colum keys
	 */
	function getKeyVals()
	{
		$cust_fields='';
		$acf_field=array();
		$wpcsvfreesettings = array();
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
		$wpcsvfreesettings = get_option('wpcsvfreesettings');
		if($wpcsvfreesettings)
                if(in_array('aioseo',$wpcsvfreesettings)){
                        if(in_array('all-in-one-seo-pack/all_in_one_seo_pack.php', $active_plugins)){
                                $seo_custoFields =array('SEO: keywords','SEO: description','SEO: title','SEO: noindex','SEO: nofollow','SEO: titleatr','SEO: menulabel','SEO: disable','SEO: disable_analytics','SEO: noodp','SEO: noydir');
                                foreach($seo_custoFields as $val)
                                        $this->defCols[$val]=$val;
                        }
                }
	}
        /**
	 * Function for import wp-ecommerce meta-data's
	 * @param $new_post
	 * @param $post_id
	 * @return mixed
	 */
	public function eshopMetaData($new_post, $post_id, $currentLimit) {
		global $wpdb;
		$eshopoptions = get_option('eshop_plugin_settings');
		foreach ($new_post as $ckey => $cval) {
			$taxo = get_taxonomies();
			foreach ($taxo as $taxokey => $taxovalue) {
				if ($taxokey != 'category' && $taxokey != 'link_category' && $taxokey != 'post_tag' && $taxokey != 'nav_menu' && $taxokey != 'post_format') {
					if ($taxokey == $ckey) {
						$smack_taxo[$ckey] = $new_post[$ckey];
					}

				}
			}
			switch ($ckey) {
				case 'featured_product' :
					$isFeatured = strtolower($new_post[$ckey]);
					$metaDatas['featured'] = $isFeatured;
					if ($isFeatured == 'yes') {
						update_post_meta($post_id, '_eshop_featured', 'Yes');
						$metaDatas['featured'] = 'Yes';
					}
					break;
				case 'product_in_sale' :
					$inSale = strtolower($new_post[$ckey]);
					$metaDatas['sale'] = $inSale;
					if ($inSale == 'yes') {
						update_post_meta($post_id, '_eshop_sale', 'yes');
					}
					break;
				case 'stock_available' :
					$cval = strtolower($cval);
					if ($cval == 'yes' || $cval == 1) {
						update_post_meta($post_id, '_eshop_stock', 1);
					}
					break;
				case 'cart_option' :
					$cartOption = strtolower($new_post[$ckey]);
					if ($cartOption == 'yes' || $cartOption == 'no') {
						$cartOption = 0;
					} else {
						$cartOption = $cartOption;
					}
					$metaDatas['cart_radio'] = $cartOption;
					break;
				case 'description' :
					$metaDatas['description'] = $new_post[$ckey];
					break;
				case 'shiprate' :
					$shipRate = strtoupper($new_post[$ckey]);
					$metaDatas['shiprate'] = $shipRate;
					break;
				case 'SKU' :
					$metaDatas['sku'] = $new_post[$ckey];
					$this->detailedLog[$currentLimit]['SKU'] = "<b>SKU - </b>" . $metaDatas['sku'];
					break;
				case 'products_option':
					$productOptions = $new_post[$ckey];
					break;
				case 'regular_price':
					$regularPrice = $new_post[$ckey];
					break;
				case 'sale_price':
					$salePrice = $new_post[$ckey];
					break;
				case 'tags':
					$tags['post_tag'] = $new_post[$ckey];
					break;
				case 'category':
					$categories['category'] = $new_post[$ckey];
					break;
			}
		}
		if (!empty($productOptions)) {
			$get_product_option = explode(',', $productOptions);
		}
		if (!empty($regularPrice)) {
			$get_regular_price = explode(',', $regularPrice);
		}
		if (!empty($salePrice)) {
			$get_sale_price = explode(',', $salePrice);
		}


		for ($x = 0; $x <= 2; $x++) {
			if (!isset($get_product_option[$x])) {
				$get_product_option[$x] = null;
			}
		}

		for ($y = 0; $y <= 2; $y++) {
			if (!isset($get_regular_price[$y])) {
				$get_regular_price[$y] = null;
			}
		}

		for ($z = 0; $z <= 2; $z++) {
			if (!isset($get_sale_price[$z])) {
				$get_sale_price[$z] = null;
			}
		}

		$Products[1]['option'] = $get_product_option[0];
		$Products[2]['option'] = $get_product_option[1];
		$Products[3]['option'] = $get_product_option[2];
		$Products[1]['price'] = $get_regular_price[0];
		$Products[2]['price'] = $get_regular_price[1];
		$Products[3]['price'] = $get_regular_price[2];
		$Products[1]['saleprice'] = $get_sale_price[0];
		$Products[2]['saleprice'] = $get_sale_price[1];
		$Products[3]['saleprice'] = $get_sale_price[2];
		$metaDatas['products'] = $Products;
		if (!empty($metaDatas)) {
			update_post_meta($post_id, '_eshop_product', $metaDatas);
		}
		if (!empty($tags)) {
			$this->detailedLog[$currentLimit]['tags'] = "";
			foreach ($tags as $tag_key => $tag_value) {
				$this->detailedLog[$currentLimit]['tags'] .= $tag_value . "|";
				$split_line = explode(',', $tag_value);
				$term_taxonomy_id_t = wp_set_object_terms($post_id, $split_line, "post_tag");
			}
			$this->detailedLog[$currentLimit]['tags'] = "<b>Tags - </b>" .substr($this->detailedLog[$currentLimit]['tags'], 0, -1);
		}
		if (!empty($categories)) {
			$this->detailedLog[$currentLimit]['category'] = "";
			foreach ($categories as $cat_key => $cat_value) {
				$this->detailedLog[$currentLimit]['category'] .= $cat_value . "|";
				$split_line = explode('|', $cat_value);
				$term_taxonomy_id_c = wp_set_object_terms($post_id, $split_line, "category");
			}
			$this->detailedLog[$currentLimit]['category'] = "<b>Category - </b>" .substr($this->detailedLog[$currentLimit]['category'], 0, -1);
		}
		#TODO: $term_taxonomy_id_c, $term_taxonomy_id_t not used in this function / overwritten immediately
		return $metaDatas;
	}

             /**
	 * function to map the csv file and process it
	 *
	 * @return boolean
	 */
	function processDataInWP($data_rows,$ret_array,$session_arr,$currentLimit,$extractedimagelocation,$importinlineimageoption,$sample_inlineimage_url = null)
	{
		global $wpdb;
		$post_id = '';
		$new_post = array();
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
						$strip_SEO = strpos($ret_array['mapping'.$i],'SEO: ');
						if ($strip_CF === 0) {
							$custom_key = substr($ret_array['mapping' . $i], 4);
							$custom_array[$custom_key] = $data_rows[$i];
						} 
						elseif($strip_SEO === 0){
                                                        $seo_key = substr($ret_array['mapping'.$i], 5);
                                                        $seo_custom_array[$seo_key] = $data_rows[$i];
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
				  if ($ckey != 'post_category' && $ckey != 'post_tag' && $ckey != 'featured_image' && $ckey != $smack_taxo [$ckey] && $ckey != 'wp_page_template') {	
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
                                               case 'wp_page_template' :
                                                        $custom_array['_wp_page_template'] = $new_post [$ckey];
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
                                                        require_once(WP_CONST_ULTIMATE_CSV_IMP_DIRECTORY.'/includes/WPImporter_includes_helper.php');
                                                        $impCE = new WPImporter_includes_helper();
							$fimg_name = wp_unique_filename($fimg_path, $fimg_name, $path_parts['extension']);
							$impCE->get_fimg_from_URL($f_img,$fimg_path,$fimg_name,$post_slug_value,$currentLimit,$this);
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
			$this->postFlag = $this->duplicateChecks('title', $data_array ['post_title'], $data_array ['post_type'], $currentLimit, $data_array ['post_title']);

		if ($this->conDupCheck == 'true' && $this->postFlag)
			$this->postFlag = $this->duplicateChecks('content', $data_array ['post_content'], $data_array ['post_type'], $currentLimit, $data_array ['post_title']);

		if ($this->postFlag) {
			unset ($sticky);
			if (empty($data_array['post_status']))
				$data_array['post_status'] = null;
                         $data_array['post_type'] = "post";

			if ($_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['importallwithps'] != 0)
				$data_array['post_status'] = $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['importallwithps'];

			switch ($data_array ['post_status']) {
				case 1 :
					$data_array['post_status'] = 'publish';
					$this->detailedLog[$currentLimit]['poststatus'] = "<b>Status - </b>publish";
					break;
				case 2 :
					$data_array['post_status'] = 'publish';
					$sticky = true;
					$this->detailedLog[$currentLimit]['poststatus'] = "<b>Status - </b>sticky";
					break;
				case 3 :
					$data_array['post_status'] = 'publish';
					$data_array ['post_password'] = $_POST ['postsPassword'];
					$this->detailedLog[$currentLimit]['poststatus'] = "<b>Status - </b>protected with password " . $data_array['post_password'];
					break;
				case 4 :
					$data_array ['post_status'] = 'private';
					$this->detailedLog[$currentLimit]['poststatus'] = "<b>Status - </b>private";
					break;
				case 5 :
					$data_array ['post_status'] = 'draft';
					$this->detailedLog[$currentLimit]['poststatus'] = "<b>Status - </b>draft";
					break;
				case 6 :
					$data_array ['post_status'] = 'pending';
					$this->detailedLog[$currentLimit]['poststatus'] = "<b>Status - </b>pending";
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
								$this->detailedLog[$currentLimit]['poststatus'] = "<b>Status - </b>protected with password " . $data_array['post_password'];
							} else {
								$data_array['post_status'] = 'publish';
								$data_array ['post_password'] = $poststatus;
								$this->detailedLog[$currentLimit]['poststatus'] = "<b>Status - </b>protected with password " . $data_array['post_password'];
							}
						} else {
							$data_array['post_status'] = 'publish';
							$this->detailedLog[$currentLimit]['poststatus'] = "<b>Status - </b>publish";
						}
					}
					if ($data_array['post_status'] == 'sticky') {
						$data_array['post_status'] = 'publish';
						$sticky = true;
						$this->detailedLog[$currentLimit]['poststatus'] = "<b>Status - </b>sticky";
					}
					else {
						$this->detailedLog[$currentLimit]['poststatus'] = "<b>Status - </b>" . $data_array['post_status'];
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
					$postauthor = $wpdb->get_results("select ID,user_login from $wpdb->users where ID = \"{$postuserid}\"");
					if(empty($postauthor) || !$postauthor[0]->ID) { // If user name are numeric Ex: 1300001
						$postauthor = $wpdb->get_results("select ID,user_login from $wpdb->users where user_login = \"{$postuserid}\"");
					}
				} else {
					$postauthor = $wpdb->get_results("select ID,user_login from $wpdb->users where user_login = \"{$postuserid}\"");
				}

				if (empty($postauthor) || !$postauthor[0]->ID) {
					$data_array ['post_author'] = 1;
					$admindet = $wpdb->get_results("select ID,user_login from $wpdb->users where ID = 1");
                                        $this->detailedLog[$currentLimit]['assigned_author'] = "<b>Author - not found (assigned to </b>" . $admindet[0]->user_login . ")";
					$this->noPostAuthCount++;
				} else {
					$data_array ['post_author'] = $postauthor [0]->ID;
					$this->detailedLog[$currentLimit]['assigned_author'] = "<b>Author - </b>" . $postauthor[0]->user_login;
				}
			}
			else{
				$data_array ['post_author'] = 1;
				$admindet = $wpdb->get_results("select ID,user_login from $wpdb->users where ID = 1");
                                $this->detailedLog[$currentLimit]['assigned_author'] = "<b>Author - not found (assigned to </b>" . $admindet[0]->user_login . ")";
				$this->noPostAuthCount++;
			}

			// Date format post
			$data_array ['post_date'] = str_replace('/', '-', $data_array ['post_date']);
			if (!isset($data_array ['post_date'])){
				$data_array ['post_date'] = date('Y-m-d H:i:s');
				$this->detailedLog[$currentLimit]['postdate'] = "<b>Date - </b>" . $data_array ['post_date'];
			}else{
				$data_array ['post_date'] = date('Y-m-d H:i:s', strtotime($data_array ['post_date']));
				$this->detailedLog[$currentLimit]['postdate'] = "<b>Date - </b>" . $data_array ['post_date'];
			}
			if(isset($data_array ['post_slug'])){
				$data_array ['post_name'] = $data_array ['post_slug'];
			}

			//add global password
			if($data_array){
				if($ret_array['importallwithps'] == 3){
					$data_array['post_password'] = $ret_array['globalpassword_txt'];
					$this->detailedLog[$currentLimit]['poststatus'] = "<b>Status - </b>protected with password " . $ret_array['globalpassword_txt'];
				}
			}
			if ($data_array) {
				if($this->MultiImages == 'true') {
                                        $inlineImagesObj = new WPImporter_inlineImages();
                                        $post_id = $inlineImagesObj->importwithInlineImages($data_array['ID'], $currentLimit, $data_array, $this, $importinlineimageoption, $extractedimagelocation, $sample_inlineimage_url);
                                } else {
                                        $post_id = wp_insert_post($data_array);
                                        $this->detailedLog[$currentLimit]['post_id'] = "<b>Created Post_ID - </b>" . $post_id . " - success";
                                }
			}

			unset($postauthor);
			if ($post_id) {
                                $custom_array = $this->eshopMetaData($new_post, $post_id, $currentLimit);
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
				if($action == 'eshop'){
					$imported_as = 'eshop-product';
				}
				$keyword = $action;
				$this->insPostCount++;
				if (isset($sticky) && $sticky)
					stick_post($post_id);

				if (!empty ($custom_array)) {
					foreach ($custom_array as $custom_key => $custom_value) {
                                       update_post_meta($post_id, $custom_key, $custom_value);
					}
				}
                                		

                                //Import SEO Values     
                                if(!empty($seo_custom_array)){
                                        $this->importSEOfields($seo_custom_array,$post_id);
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
					$this->detailedLog[$currentLimit]['tags'] = "";
					foreach ($tags as $tag_key => $tag_value) {
						$this->detailedLog[$currentLimit]['tags'] .= $tag_value . "|";
						wp_set_post_tags($post_id, $tag_value);
					}
					$this->detailedLog[$currentLimit]['tags'] = "<b>Tags - </b>" .substr($this->detailedLog[$currentLimit]['tags'], 0, -1);
				}

				// Create/Add category to post
				if (!empty ($categories)) {
					$this->detailedLog[$currentLimit]['category'] = "";
                                        $assigned_categories = array();
					$split_cate = explode('|', $categories ['post_category']);
					foreach ($split_cate as $key => $val) {
						if (is_numeric($val)) {
							$split_cate[$key] = 'uncategorized';
							$assigned_categories['uncategorized'] = 'uncategorized';
                                                }
						$assigned_categories[$val] = $val;
					}
					foreach($assigned_categories as $cateKey => $cateVal) {
                                                $this->detailedLog[$currentLimit]['category'] .= $cateKey . "|";
                                        }
                                        $this->detailedLog[$currentLimit]['category'] = "<b>Category - </b>" .substr($this->detailedLog[$currentLimit]['category'], 0, -1);
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
		$this->detailedLog[$currentLimit]['verify_here'] = "<b>Verify Here -</b> <a href='" . get_permalink( $post_id ) . "' title='" . esc_attr( sprintf( __( 'View &#8220;%s&#8221;' ), $data_array['post_title'] ) ) . "' rel='permalink'>" . __( 'Web View' ) . "</a> | <a href='" . get_edit_post_link( $post_id, true ) . "' title='" . esc_attr( __( 'Edit this item' ) ) . "'>" . __( 'Admin View' ) . "</a>";

		unset($data_array);
	}
       /**
	 * Function for importing the all in seo data 
	 * Feature added by Fredrick on version3.5.4
	 */
	function importSEOfields($array,$postId)
	{
		$seo_opt = get_option('wpcsvfreesettings');
		if(in_array('aioseo',$seo_opt)){
			if(isset($array['keywords'])) {    $custom_array['_aioseop_keywords'] = $array['keywords']; } 
			if(isset($array['description'])) { $custom_array['_aioseop_description'] = $array['description']; }
			if(isset($array['title'])) {       $custom_array['_aioseop_title'] = $array['title']; }
			if(isset($array['noindex'])) {     $custom_array['_aioseop_noindex'] = $array['noindex']; }
			if(isset($array['nofollow'])) {    $custom_array['_aioseop_nofollow'] = $array['nofollow']; }
			if(isset($array['titleatr'])) {    $custom_array['_aioseop_titleatr'] = $array['titleatr']; }
			if(isset($array['menulabel'])) {   $custom_array['_aioseop_menulabel'] = $array['menulabel']; }
			if(isset($array['disable'])) {     $custom_array['_aioseop_disable'] = $array['disable']; }
			if(isset($array['disable_analytics'])) { $custom_array['_aioseop_disable_analytics'] = $array['disable_analytics']; }
			if(isset($array['noodp'])) { $custom_array['_aioseop_noodp'] = $array['noodp']; }
			if(isset($array['noydir'])) { $custom_array['_aioseop_noydir'] = $array['noydir']; }
		}
		if (! empty ( $custom_array )) {
			foreach ( $custom_array as $custom_key => $custom_value ) {
				update_post_meta ( $postId, $custom_key, $custom_value );
			}
		}

	}//importSEOfields ends
       public function addPieChartEntry($imported_as, $count) {
                //add total counts
          global $wpdb;
          $getTypeID = $wpdb->get_results("select * from smackcsv_pie_log where type = '$imported_as'");
          if(count($getTypeID) == 0)
          $wpdb->insert('smackcsv_pie_log',array('type'=>$imported_as,'value'=>$count));
          else
          $wpdb->update('smackcsv_pie_log', array('value' =>$getTypeID[0]->value+$count), array('id'=>$getTypeID[0]->id));
        }
         function addStatusLog($inserted,$imported_as){
                global $wpdb;
                $today = date('Y-m-d h:i:s');
                $mon = date("M",strtotime($today));
                $year = date("Y",strtotime($today));
                $wpdb->insert('smackcsv_line_log', array('month'=>$mon,'year'=>$year,'imported_type'=>$imported_as,'imported_on'=>date('Y-m-d h:i:s'), 'inserted'=>$inserted ));
        }
       
      public function isplugin() {
                $allplugins = get_plugins();
                $allpluginskey = array();
                foreach ($allplugins as $key => $value) {
                        $allpluginskey[] = $key;
                }

                if ((!in_array('eshop/eshop.php', $allpluginskey))) {
                        $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['isplugin_avail'] = 'not_avail';
                } else {
                        $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['isplugin_avail'] = 'avail';
                }
                $allactiveplugins = get_option('active_plugins');
		$allactiveplugins_value = array();
		foreach ($allactiveplugins as $key => $value) {
			$allactiveplugins_value[] = $value;
		}
		if ((!in_array('eshop/eshop.php', $allactiveplugins_value))) {
			$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['isplugin_activ'] = 'not_activ';
		} else {
			$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['isplugin_activ'] = 'activ';
		}
      }


}
