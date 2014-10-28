<?php
class WPImporter_inlineImages {

	/**
         * Function for update with inline images data
         * @param $array
         * @param $postId
         */
	function importwithInlineImages ($recordID, $currentLimit, $data_array, $impObj, $import_image_method, $imgLoc, $sampleURL) {
		$helperObj = new WPImporter_includes_helper();
		$res_array = $this->process_multi_images($data_array, $helperObj, $currentLimit, $impObj, $import_image_method, $imgLoc, $sampleURL);
		#print_r($res_array); die('am from importwithInlineImages function');
		$data_array['post_content'] = $res_array;
		$post_id = wp_insert_post($data_array);
		$impObj->insPostCount++;
		$impObj->detailedLog[$currentLimit]['post_id'] = "<b>Created Post_ID - </b>" . $post_id . " - success";
		return $post_id;
	}

	/**
	 * Function to process the multi image if the image url given in post content
	 * @param data
	 * @return 
	 */
	public function process_multi_images($data_array, $helperObj, $currentLimit, $impObj, $import_image_method, $imgLoc, $sampleURL) {
		$content = $data_array['post_content'];
		$doc = new DOMDocument();
		$doc->loadHTML($content);
		$res = $this->getHtmlChar($doc, 'img');
		foreach($res as $key => $image_url) {
			foreach($image_url as $img_path) {
				$get_name=explode('/',$img_path);
				$count=count($get_name);
				$img_real_name = $get_name[$count - 1];
				$inline_img_slug = preg_replace("/[^a-zA-Z0-9._\s]/", "", $data_array['post_title']);
				$inline_img_slug = preg_replace('/\s/', '-', $inline_img_slug);
				$post_slug_value = strtolower($inline_img_slug);
				$dir = wp_upload_dir();
				$media_location = $dir ['baseurl'];
				$get_media_settings = get_option('uploads_use_yearmonth_folders');
				if ($get_media_settings == 1) {
					$dirname = date('Y') . '/' . date('m');
					$full_path = $dir ['basedir'] . '/' . $dirname;
					$baseurl = $dir ['baseurl'] . '/' . $dirname;
				} else {
					$full_path = $dir ['basedir'];
					$baseurl = $dir ['baseurl'];
				}
				$inline_img_path = $full_path;
				#$img_real_name = wp_unique_filename($inline_img_path, $img_real_name, $unique_filename_callback = null);
				if($import_image_method == 'imagewithextension' && $count == 1 ) {
					$new_img_path = $imgLoc . '/' . $img_real_name;
					$inline = $helperObj->get_fimg_from_URL($new_img_path, $inline_img_path, $img_real_name, $post_slug_value, $currentLimit, $impObj);
				} else {
					if($sampleURL == null) {
						$inline = $helperObj->get_fimg_from_URL($img_path, $inline_img_path, $img_real_name, $post_slug_value, $currentLimit, $impObj);
					} else {
						$new_img_path = $sampleURL . '/' . $img_real_name;
						$inline = $helperObj->get_fimg_from_URL($new_img_path, $inline_img_path, $img_real_name, $post_slug_value, $currentLimit, $impObj);
					}
				}
				$inline_filepath = $inline_img_path . "/" . $inline;
				if (@getimagesize($inline_filepath)) {
					$img = wp_get_image_editor($inline_filepath);
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
					$inline_file ['guid'] = $baseurl . "/" . $inline;
					$inline_file ['post_title'] = $inline;
					$inline_file ['post_content'] = '';
					$inline_file ['post_status'] = 'attachment';
					$wp_upload_dir = wp_upload_dir();
					$attachment = array('guid' => $inline_file ['guid'], 'post_mime_type' => 'image/jpg', 'post_title' => preg_replace('/\.[^.]+$/', '', @basename($inline_file ['guid'])), 'post_content' => '', 'post_status' => 'inherit');
					if ($get_media_settings == 1) {
						$generate_attachment = $dirname . '/' . $inline;
					} else {
						$generate_attachment = $inline;
					}
					$uploadedImage = $wp_upload_dir['path'] . '/' . $inline;
					$attach_id = wp_insert_attachment($attachment, $generate_attachment, $post_id);
					$attach_data = wp_generate_attachment_metadata($attach_id, $uploadedImage);
					wp_update_attachment_metadata($attach_id, $attach_data);
					set_post_thumbnail($post_id, $attach_id);
					$oldWord = $img_path; 
					$newWord = $inline_file['guid']; 
					$content = str_replace($oldWord , $newWord , $content); 
				} else {
					$inline_file = false;
				}
			}     
		}
		return $content;
	}  

	public function getHtmlChar($dom_document,$tagname) {
		$tagcontent = array();
		$dom_xpath = new DOMXpath($dom_document);
		$elements = $dom_document->getElementsByTagName($tagname);
		if(!is_null($elements))
		{
			$i = 0;
			foreach ($elements as $element)
			{
				if($tagname == 'img')
					$nodes = $element->attributes;
				else
					$nodes = $element->childNodes;

				foreach ($nodes as $node)
				{
					$nodevalue = trim($node->nodeValue);
					if($tagname == 'img')
					{
						$nodename = trim($node->nodeName);
						if(isset($nodename) && !empty($nodename) && $nodename != NULL && $nodename == 'src')
						{
							$tagcontent[$tagname][$i] = $nodevalue;
							$i ++;
						}
					}
					else
					{
						if(isset($nodevalue) && !empty($nodevalue) && $nodevalue != NULL)
						{
							$tagcontent[$tagname][$i] =  $nodevalue;
							{
								$tagcontent[$tagname][$i] =  $nodevalue;
								$i ++;
							}
						}
					}
				}
			}
			return $tagcontent;
		}
	}
}
