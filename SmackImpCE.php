<?php
/*********************************************************************************
 * WordPress ultimate CSV Importer is a Tool for importing CSV for the Wordpress
 * plugin developed by Smackcoder. Copyright (C) 2013 Smackcoders.
 *
 * WordPress ultimate CSV Importer is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Affero General Public License version 3 as
 * published by the Free Software Foundation with the addition of the following
 * permission added to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE
 * COVERED WORK IN WHICH THE COPYRIGHT IS OWNED BY WordPress ultimate CSV Importer,
 * WordPress ultimate CSV Importer DISCLAIMS THE WARRANTY OF NON INFRINGEMENT OF THIRD
 * PARTY RIGHTS.
 *
 * WordPress ultimate CSV Importer is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for
 * more details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact Smackcoders at email address info@smackcoders.com.
 *
 * The interactive user interfaces in original and modified versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the WordPress ultimate
 * CSV Importer copyright notice. If the display of the logo is not reasonably feasible
 * for technical reasons, the Appropriate Legal Notices must display the words
 * "Copyright Smackcoders. 2013. All rights reserved".
 ********************************************************************************/

require_once ("SmackWpHandler.php");

class SmackImpCE extends SmackWpHandler
{

    // @var string CSV upload directory name
    public $uploadDir = 'ultimate_importer';

    // @var boolean post title check
    public $titleDupCheck = false;

    // @var boolean content title check
    public $conDupCheck = false;

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
        'post_status' => 0
    );

    // @var array CSV headers
    public $headers = array();

    // @var boolean for post flag
    public $postFlag = true;

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
    function __construct()
    {
        $this->getKeyVals();
    }

    /*
     * Function to get the plugin row
     * @$plugin_name as string
     */
    public static function plugin_row($plugin_name)
    {
        echo '</tr><tr class="plugin-update-tr"><td colspan="3" class="plugin-update"><div class="update-message"> Upgrade to Pro Version Now for more features and 3rd party plugins  (AIO, WP SEO YOAST, WooCommerce, WP e-Commerce, eShop, CCTM,ACF) support. <a href="http://www.smackcoders.com/wp-ultimate-csv-importer-pro.html">Purchase pro version now!</a></div></td>';
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
     * Get upload directory
     */
    function getUploadDirectory()
    {
        $upload_dir = wp_upload_dir();
        return $upload_dir ['basedir'] . "/" . $this->uploadDir;
    }

    /**
     * Move CSV to the upload directory
     */
    function move_file()
    {
        if ($_FILES ["csv_import"] ["error"] == 0) {
            $tmp_name = $_FILES ["csv_import"] ["tmp_name"];
            $this->csvFileName = $_FILES ["csv_import"] ["name"];
            move_uploaded_file($tmp_name, $this->getUploadDirectory() . "/$this->csvFileName");
        }
    }

    /**
     * Remove CSV file
     */
    function fileDelete($filepath, $filename)
    {
        if (file_exists($filepath . $filename) && $filename != "" && $filename != "n/a") {
            unlink($filepath . $filename);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Get field colum keys
     */
    function getKeyVals()
    {
        global $wpdb;
        $limit = ( int )apply_filters('postmeta_form_limit', 30);
        $this->keys = $wpdb->get_col("SELECT meta_key FROM $wpdb->postmeta
				GROUP BY meta_key
				HAVING meta_key NOT LIKE '\_%'
				ORDER BY meta_key
				LIMIT $limit");

        foreach ($this->keys as $val) {
            $this->defCols ["CF: " . $val] = $val;
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
        $this->checkUploadDirPermission();
        ini_set("auto_detect_line_endings", true);

        $data_rows = array();

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
            #	require_once "class.rendercsv.php";
            #	$impRen = new RenderCSVCE;
            #	echo $impRen->showMessage('error', "File Not Exists in this location $file");
        }
        return $data_rows;
    }

    /**
     * function to map the csv file and process it
     *
     * @return boolean
     */
    function processDataInWP()
    {
        global $wpdb;

        $smack_taxo = array();
        $custom_array = array();

        $data_rows = $this->csv_file_data($this->getUploadDirectory() . "/" . $_POST ['filename'], $this->delim);

        foreach ($_POST as $postkey => $postvalue) {
            if ($postvalue != '-- Select --') {
                $ret_array [$postkey] = $postvalue;
            }
        }

        foreach ($data_rows as $key => $value) {
            for ($i = 0; $i < count($value); $i++) {
                if (array_key_exists('mapping' . $i, $ret_array)) {
                    if ($ret_array ['mapping' . $i] != 'add_custom' . $i) {
                        $strip_CF = strpos($ret_array['mapping' . $i], 'CF: ');
                        if ($strip_CF === 0) {
                            $custom_key = substr($ret_array['mapping' . $i], 4);
                            $custom_array[$custom_key] = $value[$i];
                        } else {
                            $new_post[$ret_array['mapping' . $i]] = $value[$i];
                        }
                    } else {
                        $new_post [$ret_array ['textbox' . $i]] = $value [$i];
                        $custom_array [$ret_array ['textbox' . $i]] = $value [$i];
                    }
                }
            }

            for ($inc = 0; $inc < count($value); $inc++) {
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

            $data_array['post_type'] = $_POST['csv_importer_cat'];

            if ($this->titleDupCheck)
                $this->postFlag = $this->duplicateChecks('title', $data_array ['post_title'], $data_array ['post_type']);

            if ($this->conDupCheck && $this->postFlag)
                $this->postFlag = $this->duplicateChecks('content', $data_array ['post_content'], $data_array ['post_type']);


            if ($this->postFlag) {
                unset ($sticky);
                if (empty($data_array['post_status']))
                    $data_array['post_status'] = null;

                if ($_POST['importallwithps'] != 0)
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

                if ($data_array)
                    $post_id = wp_insert_post($data_array);
                unset($data_array);
                unset($postauthor);
                if ($post_id) {
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
                        $wp_filetype = wp_check_filetype(basename($attachmentName), null);
                        $wp_upload_dir = wp_upload_dir();
                        $attachment = array(
                            'guid' => $wp_upload_dir['url'] . '/' . basename($attachmentName),
                            'post_mime_type' => $wp_filetype['type'],
                            'post_title' => preg_replace('/\.[^.]+$/', '', basename($attachmentName)),
                            'post_content' => '',
                            'post_status' => 'inherit'
                        );
                        if($get_media_settings == 1){
                                $generate_attachment = $dirname . '/' . $attachmentName;
                        }else{
                                $generate_attachment = $attachmentName;
                        }
                        $uploadedImage = $wp_upload_dir['path'] . '/' . $attachmentName;
                        $attach_id = wp_insert_attachment($attachment, $generate_attachment, $post_id);
                        require_once(ABSPATH . 'wp-admin/includes/image.php');
                        $attach_data = wp_generate_attachment_metadata($attach_id, $uploadedImage);
                        wp_update_attachment_metadata($attach_id, $attach_data);
                        set_post_thumbnail($post_id, $attach_id);
                    }
                }
            }
        }

        if (file_exists($this->getUploadDirectory() . '/' . $_POST ['filename'])) {
            $filePath = $this->getUploadDirectory() . '/';
            $this->fileDelete($filePath, $_POST ['filename']);
        }
    }
}
?>
