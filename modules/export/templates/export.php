<?php
/******************************************************************************************
 * Copyright (C) Smackcoders 2014 - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * You can contact Smackcoders at email address info@smackcoders.com.
 *******************************************************************************************/

require_once('../../../../../../wp-load.php');
require_once('../../../includes/Importer.php');

$ExportObj = new WPCSVProExportData();
#print('<pre>'); print_r($_POST); //die;
$ExportObj->executeIndex($_POST);
class WPCSVProExportData {
	public function __construct() {

	}

	/**
	 * The actions index method
	 * @param array $request
	 * @return array
	 */
	public function executeIndex($request) {
		#print('<pre>'); print_r($request); print('</pre>'); die;
		if($request['export'] == 'category') {
			$this->WPImpExportCategories($request);
		}
		else if($request['export'] == 'tags') {
			$this->WPImpExportTags($request);
		}
		else if($request['export'] == 'customtaxonomy') {
			$this->WPImpExportTaxonomies($request);
		}
		else if($request['export'] == 'customerreviews') {
			$this->WPImpExportCustomerReviews($request);
		}
		else if($request['export'] == 'comments') {
			$this->WPImpExportComments($request);
		}
		else if($request['export'] == 'users') {
			$this->WPImpExportUsers($request);
		}
		else {
			$this->WPImpFreeExportData($request);#die;
		}
	}

	/**
	 *
	 */
	public function generateCSVHeaders($exporttype){
		global $wpdb;
		$post_type = $exporttype;
		$unwantedHeader = array('_eshop_product', '_wp_attached_file', '_wp_page_template', '_wp_attachment_metadata', '_encloseme');
		if($exporttype == 'eshop')
			$post_type = 'post';
                if($exporttype == 'custompost') {
                        $post_type = $_POST['export_post_type'];
                }
		$header_query1 = "SELECT wp.* FROM  $wpdb->posts wp where post_type = '$post_type'";
		$header_query2 = "SELECT post_id, meta_key, meta_value FROM  $wpdb->posts wp JOIN $wpdb->postmeta wpm  ON wpm.post_id = wp.ID where meta_key NOT IN ('_edit_lock','_edit_last') and meta_key NOT LIKE 'field_%' and meta_key NOT LIKE '_wp_types%'";
		$result_header_query1 = $wpdb->get_results($header_query1);
		$result_header_query2 = $wpdb->get_results($header_query2);

		foreach ($result_header_query1 as $rhq1_key) {
			foreach ($rhq1_key as $rhq1_headkey => $rhq1_headval) {
				if (!in_array($rhq1_headkey, $Header))
					$Header[] = $rhq1_headkey;
			}
		}
		foreach($this->getAIOSEOfields() as $aioseokey => $aioseoval) {
			$Header[] = $aioseoval;
			$unwantedHeader[] = $aioseokey;
		}
		foreach ($result_header_query2 as $rhq2_headkey) {
			if (!in_array($rhq2_headkey->meta_key, $Header)) {
				if(!in_array($rhq2_headkey->meta_key, $unwantedHeader)) {
					$Header[] = $rhq2_headkey->meta_key;
				}
			}
		}

#print('<pre>'); print_r($Header); die;
		if($exporttype == 'eshop') {
			foreach($this->EshopHeaders() as $eshop_hkey => $eshop_hval) {
				if(in_array($eshop_hval, $Header))
					$ProHeader[] = $eshop_hkey;
				else
					$ProHeader[] = $eshop_hkey;
			}

			foreach($this->getAIOSEOfields() as $aioseokey => $aioseoval) {
				$ProHeader[] = $aioseoval;
			}
			return $ProHeader;
		}
		if(!in_array('', $Header) && !in_array('', $Header)){
			$Header[] = 'post_tag';
			$Header[] = 'post_category';
		}
		return $Header;
	}

	/**
	 *
	 */
	public function get_all_record_ids($exporttype, $request) {
		global $wpdb;
		$post_type = $exporttype;
		$get_post_ids = "select DISTINCT ID from $wpdb->posts p join $wpdb->postmeta pm on pm.post_id = p.ID";
		if($post_type == 'eshop')
			$post_type = 'post';
		if($post_type == 'custompost') 
			$post_type = $_POST['export_post_type'];

		$get_post_ids .= " where p.post_type = '$post_type'";
		if(isset($request['getdatawithspecificstatus'])) {
			if(isset($request['postwithstatus']) && $request['postwithstatus'] == 'All') {
				$get_post_ids .= " and p.post_status in ('publish','draft','future','private','pending')";
			} else if(isset($request['postwithstatus']) && ($request['postwithstatus'] == 'Publish' || $request['postwithstatus'] == 'Sticky')) {
				$get_post_ids .= " and p.post_status in ('publish')";
			} else if(isset($request['postwithstatus']) && $request['postwithstatus'] == 'Draft') {
				$get_post_ids .= " and p.post_status in ('draft')";
                        } else if(isset($request['postwithstatus']) && $request['postwithstatus'] == 'Scheduled') {
				$get_post_ids .= " and p.post_status in ('future')";
                        } else if(isset($request['postwithstatus']) && $request['postwithstatus'] == 'Private') {
				$get_post_ids .= " and p.post_status in ('private')";
                        } else if(isset($request['postwithstatus']) && $request['postwithstatus'] == 'Pending') {
				$get_post_ids .= " and p.post_status in ('pending')";
                        } else if(isset($request['postwithstatus']) && $request['postwithstatus'] == 'Protected') {
				$get_post_ids .= " and p.post_status in ('publish') and post_password != ''";
			}
		} else {
			$get_post_ids .= " and p.post_status in ('publish','draft','future','private','pending')";
		}
		if(isset($request['getdataforspecificperiod'])) {
			$get_post_ids .= " and p.post_date >= '" . $request['postdatefrom'] . "' and p.post_date <= '" . $request['postdateto'] . "'";
		}
		if($exporttype == 'eshop')
			$get_post_ids .= " and pm.meta_key = 'sku'";

		if(isset($request['getdatabyspecificauthors'])) {
			if(isset($request['postauthor']) && $request['postauthor'] != 0) {
				$get_post_ids .= " and p.post_author = {$request['postauthor']}";
			}
		}
		#print_r($get_post_ids); die;
		$result = $wpdb->get_col($get_post_ids);
		if(isset($request['getdatawithspecificstatus'])) {
			if(isset($request['postwithstatus']) && $request['postwithstatus'] == 'Sticky') {
				$get_sticky_posts = get_option('sticky_posts');
				foreach($get_sticky_posts as $sticky_post_id) {
					if(in_array($sticky_post_id, $result))
						$sticky_posts[] = $sticky_post_id;
				}
				return $sticky_posts;
			}
		}
		#print_r($get_sticky_posts);
		#print_r($result);die;
		return $result;
	}

	/**
	 *
	 */
	public function getPostDatas($postID) {
		global $wpdb;
		$query1 = "SELECT wp.* FROM $wpdb->posts wp where ID=$postID";
		$result_query1 = $wpdb->get_results($query1);
		if (!empty($result_query1)) {
			foreach ($result_query1 as $posts) {
				foreach ($posts as $post_key => $post_value) {
					if ($post_key == 'post_status') {
						if (is_sticky($postID)) {
							$PostData[$post_key] = 'Sticky';
							$post_status = 'Sticky';
						} else {
							$PostData[$post_key] = $post_value;
							$post_status = $post_value;
						}
					} else {
						$PostData[$post_key] = $post_value;
					}
					if ($post_key == 'post_password') {
						if ($post_value) {
							$PostData['post_status'] = "{" . $post_value . "}";
						} else {
							$PostData['post_status'] = $post_status;
						}
					}
					if ($post_key == 'comment_status') {
						if ($post_value == 'closed') {
							$PostData['comment_status'] = 0;
						}
						if ($post_value == 'open') {
							$PostData['comment_status'] = 1;
						}
					}
				}
			}
		}
		return $PostData;
	}

	/**
	 *
	 */
	public function getPostMetaDatas($postID) {
		global $wpdb;
		$query2 = "SELECT post_id, meta_key, meta_value FROM $wpdb->posts wp JOIN $wpdb->postmeta wpm  ON wpm.post_id = wp.ID where meta_key NOT IN ('_edit_lock','_edit_last') AND ID=$postID";
#print($query2); print('<br>');
		$result = $wpdb->get_results($query2);
		return $result;
	}

        /**
         *
         */
        public function getAIOSEOfields() {
		$aioseofields = array('_aioseop_keywords' => 'seo_keywords', 
				'_aioseop_description'	=> 'seo_description',
				'_aioseop_title'	=> 'seo_title',
				'_aioseop_noindex'	=> 'seo_noindex',
				'_aioseop_nofollow'	=> 'seo_nofollow',
				'_aioseop_disable'	=> 'seo_disable',
				'_aioseop_disable_analytics' => 'seo_disable_analytics',
				'_aioseop_noodp'	=> 'seo_noodp',
				'_aioseop_noydir'	=> 'seo_noydir',);
		return $aioseofields;
	}

	/**
	 *
	 */
	public function getAllTerms($postID, $type) {
		// Tags & Categories
		$get_tags = wp_get_post_tags($postID, array('fields' => 'names'));
		$postTags = $postCategory = '';
		foreach ($get_tags as $tags) {
			$postTags .= $tags . ',';
		}
		$postTags = substr($postTags, 0, -1);
		$TermsData['post_tag'] = $postTags;
		$get_categotries = wp_get_post_categories($postID, array('fields' => 'names'));
		foreach ($get_categotries as $category) {
			$postCategory .= $category . '|';
		}
		$postCategory = substr($postCategory, 0, -1);
		$TermsData['post_category'] = $postCategory;
		return $TermsData;
	}

	/**
	 * 
	 */
	public function EshopHeaders() {
		$eshopHeaders = array('post_title' => 'post_title', 'post_content' => 'post_content', 'post_excerpt' => 'post_excerpt', 'post_date' => 'post_date', 'post_name' => 'post_name', 'post_status' => 'post_status', 'post_author' => 'post_author', 'post_parent' => 0, 'comment_status' => 'open', 'ping_status' => 'open', 'SKU' => 'sku', 'products_option' => 'products_option', 'sale_price' => 'sale_price', 'regular_price' => 'regular_price', 'description' => 'description', 'shiprate' => 'shiprate', 'optset' => null, 'featured_product' => 'featured', 'product_in_sale' => '_eshop_sale', 'stock_available' => '_eshop_stock', 'cart_option' => 'cart_radio', 'category' => 'post_category', 'tags' => 'post_tag', 'featured_image' => null,);
		return $eshopHeaders;
	}

	/**
	 * @param $request
	 * @return array
	 */
	public function WPImpFreeExportData($request) {
		#print('<pre>'); print_r($this->getACFvalues()); die;
		global $wpdb;
		$export_delimiter = ',';
		$exporttype = $_POST['export'];
		$wpcsvsettings=get_option('wpcsvprosettings');
		if(isset($wpcsvsettings['export_delimiter'])){
			$export_delimiter = $wpcsvsettings['export_delimiter'];
		}
		if($_POST['export_filename'])
			$csv_file_name =$_POST['export_filename'].'.csv';
		else
			$csv_file_name='exportas_'.date("Y").'-'.date("m").'-'.date("d").'.csv';
		$wptypesfields = get_option('wpcf-fields');
#print('<pre>'); print_r($wptypesfields); die;
		#print('<pre>'); print_r($_POST); print('</pre>');
		//if($exporttype=='post' || $exporttype=='page' || $exporttype=='custompost') {
		if($exporttype == 'custompost') {
			$exporttype = $_POST['export_post_type'];
		}
		$Header = $this->generateCSVHeaders($exporttype);
		$result = $this->get_all_record_ids($exporttype, $request);
		#print('<pre>'); print_r($Header); print_r($result); print('</pre>'); die;
		$fieldsCount = count($result);
		if ($result) {
			foreach ($result as $postID) {
				$pId = $pId . ',' . $postID;
				$PostData[$postID] = $this->getPostDatas($postID);
				#print('<pre>'); print_r($PostData); #die;
				$result_query2 = $this->getPostMetaDatas($postID); 
				#print('<pre>'); print_r($result_query2); print('</pre>'); #die;

				$possible_values = array('s:', 'a:', ':{');
				if (!empty($result_query2)) {
					foreach ($result_query2 as $postmeta) { 
						$typesFserialized = 0; 
						$isFound = explode('wpcf-',$postmeta->meta_key); 
						if(count($isFound) == 2){
							foreach($wptypesfields as $typesKey => $typesVal){ 
								if($postmeta->meta_key == 'wpcf-'.$typesKey){
									foreach($possible_values as $posval){
										if(strpos($postmeta->meta_value,$posval)){
											$typesFserialized = 1;
										} else {
											$typesFserialized = 0;
										}
									}
									if($typesFserialized == 1){
										$getMetaData = get_post_meta($postID, $postmeta->meta_key); 
										if(!is_array($getMetaData[0])){
											$get_all_values = unserialize($getMetaData[0]);
											$get_values = $get_all_values[0];
										} else {
											$get_values = $getMetaData[0];
										}
										$typesFVal = null;
										if($typesVal['type'] == 'checkboxes'){
											foreach($get_values as $authorKey => $authorVal) {
												foreach($typesVal['data']['options'] as $doKey => $doVal){
													if($doKey == $authorKey)
														$typesFVal .= $doVal['title'].',';
												}
											}
											$typesFVal = substr($typesFVal, 0, -1);
										} elseif($typesVal['type'] == 'skype') {
											$typesFVal = $get_values['skypename'];
										}
										$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = $typesFVal;
									} else {
										$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = $postmeta->meta_value;
									}
								}
							}			
						} else {
							// Eshop product meta datas starts here
							if ($postmeta->meta_key == 'featured') {
								$isFeatured = strtolower($postmeta->meta_value);
								$PostMetaData[$postmeta->post_id]['featured_product'] = $isFeatured;
							}
                                                        else if ($postmeta->meta_key == 'sale') {
                                                                $is_prod_sale = strtolower($postmeta->meta_value);
                                                                $PostMetaData[$postmeta->post_id]['product_in_sale'] = $is_prod_sale;
                                                        }
							else if ($postmeta->meta_key == '_eshop_stock') {
								if($postmeta->meta_value == 1) {
									$stock_available = 'yes';
								} else {
									$stock_available = 'no';
								}
								$PostMetaData[$postmeta->post_id]['stock_available'] = $stock_available;
							}
                                                        else if ($postmeta->meta_key == 'cart_radio') {
								$PostMetaData[$postmeta->post_id]['cart_option'] = $postmeta->meta_value;
                                                        }
							else if ($postmeta->meta_key == 'shiprate') {
                                                                $PostMetaData[$postmeta->post_id]['shiprate'] = $postmeta->meta_value;
                                                        }
							else if ($postmeta->meta_key == '_eshop_product') {
								$product_attr_details = unserialize($postmeta->meta_value);
								$prod_option = $sale_price = $reg_price = null;
								#print('<pre>');print_r($product_attr_details); #die;
								foreach($product_attr_details as $prod_att_det_Key => $prod_att_det_Val) {
									if($prod_att_det_Key == 'sku') {
										$PostMetaData[$postmeta->post_id]['sku'] = $prod_att_det_Val;
									}
									else if($prod_att_det_Key == 'products') {
										foreach($prod_att_det_Val as $all_prod_options) {
											$prod_option .= $all_prod_options['option'] . ',';
											$sale_price .= $all_prod_options['saleprice'] . ',';
											$reg_price .= $all_prod_options['price'] . ',';
										}
										$prod_option = substr($prod_option, 0, -1);
										$sale_price = substr($sale_price, 0, -1);
										$reg_price = substr($reg_price, 0, -1);
                                                                                $PostMetaData[$postmeta->post_id]['products_option'] = $prod_option;
										$PostMetaData[$postmeta->post_id]['sale_price'] = $sale_price;
										$PostMetaData[$postmeta->post_id]['regular_price'] = $reg_price;
                                                                        }
								}
                                                                #$PostMetaData[$postmeta->post_id]['cart_option'] = $postmeta->meta_value;
                                                        }
							// Eshop product meta datas ends here
							else if ($postmeta->meta_key == '_thumbnail_id') {
								$attachment_file = '';
								$get_attachement = "select guid from $wpdb->posts where ID = $postmeta->meta_value AND post_type = 'attachment'";
								$attachment = $wpdb->get_results($get_attachement);
								$attachment_file = $attachment[0]->guid;
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = '';
								$postmeta->meta_key = 'featured_image';
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = $attachment_file;
							}
							else if(is_array($checkbox_option_fields) && in_array($postmeta->meta_key,$checkbox_option_fields)){
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = '';
								$eshop_products = unserialize($eshop_products); //print_r($eshop_products);
								foreach ($eshop_products as $key) {
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] .= $key . ',';
								}
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = substr($PostMetaData[$postmeta->post_id][$postmeta->meta_key], 0, -1);
							}
							else {
                                                                $PostMetaData[$postmeta->post_id][$postmeta->meta_key] = $postmeta->meta_value;
                                                        }
						}
					}
				}
#				print('PostMetaData: '); print('<pre>'); print_r($Header); print_r($PostMetaData); #die;
				$TermsData[$postID] = $this->getAllTerms($postID,$exporttype);
				}

				#$ExportData = array();
				// Merge all arrays
				#echo '<pre>'; print_r($TermsData); die;
				// echo '<pre>'; print_r($PostData); die('sds');
				foreach ($PostData as $pd_key => $pd_val) {
					if (array_key_exists($pd_key, $PostMetaData)) {
						$ExportData[$pd_key] = array_merge($PostData[$pd_key], $PostMetaData[$pd_key]);
						//  echo '<pre>'; print_r($ExportData); die('exist');
					} else {
						$ExportData[$pd_key] = $PostData[$pd_key];
					}
					if (array_key_exists($pd_key, $TermsData)) {
						if (empty($ExportData[$pd_key]))
							$ExportData[$pd_key] = array();
						$ExportData[$pd_key] = array_merge($ExportData[$pd_key], $TermsData[$pd_key]);
					}
				}
			}
#print('<pre>'); print_r($Header); die;
#print('<pre>'); print_r($ExportData); die;
                        if($exporttype == 'eshop') {
                                foreach($Header as $hkey) {
                                        foreach($ExportData as $edkey => $edval) {
                                                foreach($this->EshopHeaders() as $eshophkey => $eshophval) {
                                                        if(array_key_exists($eshophval, $ExportData[$edkey])) {
                                                                $ExportData[$edkey][$eshophkey] = $edval[$eshophval];
//                                                                unset($ExportData[$edkey][$wpcomhval]);
                                                        }
                                                }
                                        }
                                }
                        }
#print('<pre>'); print_r($Header); print('</pre>'); #die;
#			print('<pre>'); print_r($ExportData); die;
			foreach ($Header as $header_key) {
				if (is_array($ExportData)) {
					foreach ($ExportData as $ED_key => $ED_val) {
						if (in_array($header_key, $this->getAIOSEOfields())) { #die($header_key);
							foreach($this->getAIOSEOfields() as $aioseokey => $aioseoval) {
								$CSVContent[$ED_key][$aioseoval] = $ED_val[$aioseokey];
								#unset($CSVContent[$ED_key][$header_key]);
							}
						} else if (array_key_exists($header_key, $ED_val)) {
							$CSVContent[$ED_key][$header_key] = $ED_val[$header_key];
						} else { 
							$CSVContent[$ED_key][$header_key] = null;
						}
					}
				}
			}
			#print(count($CSVContent[22]));print('<br>' . count($Header));
			#print('<pre>'); print_r($CSVContent) ;print('</pre>'); die;
			$csv = new ImporterLib();
			$csv->wmyuyn_3 ($csv_file_name, $CSVContent, $Header, $export_delimiter);	
	}

        /**
         *
         */
        public function WPImpExportComments($request) {
                global $wpdb;
                $export_delimiter = ',';
                $exporttype = $request['export'];
                $wpcsvsettings=get_option('wpcsvprosettings');
                if(isset($wpcsvsettings['export_delimiter'])){
                        $export_delimiter = $wpcsvsettings['export_delimiter'];
                }
                if($_POST['export_filename'])
                        $csv_file_name =$_POST['export_filename'].'.csv';
                else
                        $csv_file_name='exportas_'.date("Y").'-'.date("m").'-'.date("d").'.csv';

                $commentQuery = "SELECT * FROM $wpdb->comments " . $orderBy;
                $comments = $wpdb->get_results( $commentQuery);
                $mappedHeader = false;
		$i = 0;
                foreach($comments as $comment){
                        foreach($comment as $key => $value){
                                if(!$mappedHeader){
                                        $Header[] = $key; // ."$export_delimiter";
                                }
                                $ExportData[$i][$key] = $value; //'"'.$value.'"'."$export_delimiter";
                        }
                        $mappedHeader = true;
			$i++;
                }
		#print('<pre>'); print_r($header); print_r($singleCommentContent);die;
		$csv = new ImporterLib();
		$csv->wmyuyn_3 ($csv_file_name, $ExportData, $Header, $export_delimiter);
	}

	/**
	 *
	 */
	public function WPImpExportUsers($request) {
                global $wpdb;
                $export_delimiter = ',';
                $exporttype = $request['export'];
                $wpcsvsettings=get_option('wpcsvprosettings');
                if(isset($wpcsvsettings['export_delimiter'])){
                        $export_delimiter = $wpcsvsettings['export_delimiter'];
                }
                if($_POST['export_filename'])
                        $csv_file_name =$_POST['export_filename'].'.csv';
                else
                        $csv_file_name='exportas_'.date("Y").'-'.date("m").'-'.date("d").'.csv';

		$uId = '';
		$header_query1 = "SELECT *FROM $wpdb->users";
		$header_query2 = "SELECT user_id, meta_key, meta_value FROM  $wpdb->users wp JOIN $wpdb->usermeta wpm ON wpm.user_id = wp.ID where meta_key NOT IN ('_edit_lock','_edit_last')";
		$result_header_query1 = $wpdb->get_results($header_query1);
		$result_header_query2 = $wpdb->get_results($header_query2);
/*		print('<pre>');	print_r($result_header_query1);
			print_r($result_header_query2);die;  */
		foreach ($result_header_query1 as $rhq1_key) {
			foreach ($rhq1_key as $rhq1_headkey => $rhq1_headval) {
				if (!in_array($rhq1_headkey, $Header))
					$Header[] = $rhq1_headkey;
			}
		}
		foreach ($result_header_query2 as $rhq2_headkey) {
			if (!in_array($rhq2_headkey->meta_key, $Header)) {
				if($rhq2_headkey->meta_key == 'mp_shipping_info' )
				{
					$mp_ship_header= unserialize($rhq2_headkey->meta_value);
					foreach($mp_ship_header as $mp_ship_key => $mp_value) { $Header[] = "msi: ".$mp_ship_key; } 
				}
				if($rhq2_headkey->meta_key == 'mp_billing_info' )
				{
					$mp_ship_header= unserialize($rhq2_headkey->meta_value);
					foreach($mp_ship_header as $mp_ship_key => $mp_value) { $Header[] = "mbi: ".$mp_ship_key; } 
				}

				if ($rhq2_headkey->meta_key != '_eshop_product' && $rhq2_headkey->meta_key != '_wp_attached_file' && $rhq2_headkey->meta_key != 'mp_shipping_info' && $rhq2_headkey->meta_key != 'mp_billing_info' )
					$Header[] = $rhq2_headkey->meta_key;
			}
		}
		#echo '<pre>'; print_r($Header); die('dsd');
		$get_user_ids = "select DISTINCT ID from $wpdb->users u join $wpdb->usermeta um on um.user_id = u.ID";

		$result = $wpdb->get_col($get_user_ids);
		$fieldsCount = count($result);
		if ($result) {
			foreach ($result as $userID) {
				$uId = $uId . ',' . $userID;
				$query1 = "SELECT *FROM $wpdb->users where ID in ($userID);";
				$result_query1 = $wpdb->get_results($query1);
				if (!empty($result_query1)) { 
					foreach ($result_query1 as $users) {
						foreach ($users as $user_key => $user_value) {
							$UserData[$userID][$user_key] = $user_value;
						}
					}
				}
				//  echo '<pre>'; print_r($UserData); die ('dfdf'); 
				$query2 = "SELECT user_id, meta_key, meta_value FROM  $wpdb->users wp JOIN $wpdb->usermeta wpm  ON wpm.user_id = wp.ID where ID=$userID";
				$possible_values = array('s:', 'a:', ':{'); 
					$result_query2 = $wpdb->get_results($query2); 
					if (!empty($result_query2)) {
						foreach ($result_query2 as $usermeta) { 
							//  echo '<pre>'; print_r($usermeta);

							foreach($possible_values as $posval){
								if(strpos($usermeta->meta_value,$posval)){
									if($usermeta->meta_key == 'mp_shipping_info' || $usermeta->meta_key == 'mp_billing_info')
										$typesFserialized = 1;
								} else {
									$typesFserialized = 0;
								}
							}
							if($typesFserialized == 1)
							{
								if($usermeta->meta_key == 'mp_shipping_info')
								{
									$UserID = $usermeta->user_id;
									$mp_ship_data = unserialize($usermeta->meta_value);
									foreach($mp_ship_data as $mp_ship_key => $mp_ship_value)
									{
										$mp_ship_tempkey = "msi: ".$mp_ship_key;       
										$UserData[$UserID][$mp_ship_tempkey]= $mp_ship_value;
									}
								}

								if($usermeta->meta_key == 'mp_billing_info')
								{
									$UserID = $usermeta->user_id;
									$mp_ship_data = unserialize($usermeta->meta_value);
									foreach($mp_ship_data as $mp_ship_key => $mp_ship_value)
									{
										$mp_ship_tempkey = "mbi: ".$mp_ship_key;       
										$UserData[$UserID][$mp_ship_tempkey]= $mp_ship_value;
									}
								}

								if($usermeta->meta_key != 'wp_capabilities' && $usermeta->meta_key !='mp_shipping_info' && $usermeta->meta_key != 'mp_billing_info') {
									$UserData[$userID][$usermeta->meta_key] = $usermeta->meta_value;
								} else {
									if($usermeta->meta_key == 'wp_capabilities') {
										$getUserRole = unserialize($usermeta->meta_value);
										//  echo '<pre>'; print_r($getUserRole); die('ddf');
										foreach($getUserRole as $urKey => $urVal) {
											$getUserRole = get_role($urKey);
										}
										$rolelevel = 0; 
										$isfound = array();
										foreach($getUserRole->capabilities as $roleKey => $roleVal){
											$isfound = explode('level_', $roleKey); 
											if(is_array($isfound) && count($isfound) == 2){
												$rolelevel = $rolelevel + 1;
											}
										} $rolelevel = $rolelevel - 1;
#$UserData[$userID][$usermeta->meta_key] = $rolelevel;
									}
								}
							} else {
								foreach($possible_values as $posval){
									if(strpos($usermeta->meta_value,$posval)){
										$UserData[$userID][$usermeta->meta_key] = null;
									} else {
										$ifSerialized = 0;
										$UserData[$userID][$usermeta->meta_key] = $usermeta->meta_value;
									}
								}

							}
						} #echo '<pre>'; print_r($UserData); die('dd');
					}
				}
			}
			$UserHeader = array();
			foreach ($Header as $header_key) {
				foreach ($UserData as $UD_key => $UD_val) {
					if(array_key_exists($header_key, $UD_val)) {
						$CSVContent[$UD_key][$header_key] = $UD_val[$header_key];
						if(!in_array($header_key, $UserHeader))
							$UserHeader[] = $header_key;
					}
					else {
						$CSVContent[$UD_key][$header_key] = null;
					}
				}
			}

	                $csv = new ImporterLib();
        	        $csv->wmyuyn_3 ($csv_file_name, $CSVContent, $UserHeader, $export_delimiter);
		}
}
