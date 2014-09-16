<?php
/******************************************************************************************
 * Copyright (C) Smackcoders 2014 - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * You can contact Smackcoders at email address info@smackcoders.com.
 *******************************************************************************************/

class ExportActions extends SkinnyActions {
	public function __construct() {

	}

	/**
	 * The actions index method
	 * @param array $request
	 * @return array
	 */
	public function executeIndex($request) {
		// return an array of name value pairs to send data to the template
		$data = array();
		if (!empty($request['POST'])) {
			$type = $request['POST']['export'];
			$filename = $request['POST']['export_filename'];
			if (!empty($type) && !empty($filename)) {
				$helper = new ultimatecsv_include_helper();
				$helper->generateanddownloadcsv($type, $filename);
			}
		}
		return $data;
	}

	/**
	 * @param $request
	 * @return array
	 */
	public function executeExport($request) {
		#TODO: Update phpdoc
		$data = array();
		#TODO: $data = array() not used in this function / overwritten immediately
		ob_start();
		global $wpdb;
		$Header = $PostData = $PostMetaData = $TermsData = $ExportData = array();
		$exporttype = $request['POST']['export'];
		$export_filename = $request['POST']['export_filename'];
		if ($export_filename) {
			$csv_file_name = $export_filename . '.csv';
		} else {
			$csv_file_name = 'exportas_' . $exporttype . '_' . date("Y-m-d") . '.csv';
		}
		#TODO: $csv_file_name  not used in this function / overwritten immediately

		if ($exporttype == 'post' || $exporttype == 'page') {
			$header_query1 = "SELECT wp.* FROM  $wpdb->posts wp where post_type = '$exporttype'";
			$header_query2 = "SELECT post_id, meta_key, meta_value FROM  $wpdb->posts wp JOIN $wpdb->postmeta wpm  ON wpm.post_id = wp.ID where meta_key NOT IN ('_edit_lock','_edit_last') and meta_key NOT LIKE 'field_%'";
			$result_header_query1 = $wpdb->get_results($header_query1);
			$result_header_query2 = $wpdb->get_results($header_query2);

			foreach ($result_header_query1 as $rhq1_key) {
				foreach ($rhq1_key as $rhq1_headkey => $rhq1_headval) {
					if (!in_array($rhq1_headkey, $Header)) {
						$Header[] = $rhq1_headkey;
					}
				}
			}

			foreach ($result_header_query2 as $rhq2_headkey) {
				if (!in_array($rhq2_headkey->meta_key, $Header)) {
					if ($rhq2_headkey->meta_key != '_eshop_product' && $rhq2_headkey->meta_key != '_wp_attached_file') {
						$Header[] = $rhq2_headkey->meta_key;
					}
				}
			}

			$Header[] = 'post_tag';
			$Header[] = 'featured_image';
			$Header[] = 'post_category';
			// Code for ACF fields 
			$limit = ( int )apply_filters('postmeta_form_limit', 30);
			$get_acf_fields = $wpdb->get_col("SELECT meta_value FROM $wpdb->postmeta
					GROUP BY meta_key
					HAVING meta_key LIKE 'field_%'
					ORDER BY meta_key
					LIMIT $limit");

			foreach ($get_acf_fields as $acf_value) {
				$get_acf_field = unserialize($acf_value);
				$acf_fields[$get_acf_field['name']] = "CF: " . $get_acf_field['name'];
				$acf_fields_slug[$get_acf_field['name']] = "_" . $get_acf_field['name'];
				if (in_array("_" . $get_acf_field['name'], $Header)) {
					$Header = array_diff($Header, $acf_fields_slug);
				}
				if ($get_acf_field['type'] == 'checkbox') {
					$checkbox_option_fields[] = $get_acf_field['name'];
				}
			} // Code ends here
			$get_post_ids = "select DISTINCT ID from $wpdb->posts where post_type = '$exporttype' and post_status in ('publish','draft','future','private','pending')";

			$result = $wpdb->get_col($get_post_ids);
			$fieldsCount = count($result);
			if ($result) {
				foreach ($result as $postID) {
					$pId = $pId . ',' . $postID;
					$query1 = "SELECT wp.* FROM $wpdb->posts wp where ID=$postID";
					$result_query1 = $wpdb->get_results($query1);
					if (!empty($result_query1)) {
						foreach ($result_query1 as $posts) {
							foreach ($posts as $post_key => $post_value) {
								if ($post_key == 'post_status') {
									if (is_sticky($postID)) {
										$PostData[$postID][$post_key] = 'Sticky';
										$post_status = 'Sticky';
									} else {
										$PostData[$postID][$post_key] = $post_value;
										$post_status = $post_value;
									}
								} else {
									$PostData[$postID][$post_key] = $post_value;
								}
								if ($post_key == 'post_password') {
									if ($post_value) {
										$PostData[$postID]['post_status'] = "{" . $post_value . "}";
									} else {
										$PostData[$postID]['post_status'] = $post_status;
									}
								}
							}
						}
					}
					$query2 = "SELECT post_id, meta_key, meta_value FROM $wpdb->posts wp JOIN $wpdb->postmeta wpm  ON wpm.post_id = wp.ID where meta_key NOT IN ('_edit_lock','_edit_last') AND ID=$postID";
					$result_query2 = $wpdb->get_results($query2);
					if (!empty($result_query2)) {
						foreach ($result_query2 as $postmeta) {
							if ($postmeta->meta_key != '_eshop_product' && $postmeta->meta_key != '_thumbnail_id') {
								if (is_array($acf_fields_slug) && !in_array($postmeta->meta_key, $acf_fields_slug)) {
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = $postmeta->meta_value;
								}
							}
							$eshop_products = $postmeta->meta_value;
							if ($postmeta->meta_key == 'products') {
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = '';
								$eshop_products = unserialize($eshop_products);
								foreach ($eshop_products as $key) {
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] .= $key['option'] . '|' . $key['price'] . '|' . $key['saleprice'] . ',';
								}
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = substr($PostMetaData[$postmeta->post_id][$postmeta->meta_key], 0, -1);
							}
							if ($postmeta->meta_key == '_thumbnail_id') {
								$attachment_file = '';
								$get_attachement = "select guid from $wpdb->posts where ID = $postmeta->meta_value AND post_type = 'attachment'";
								$attachment = $wpdb->get_results($get_attachement);
								$attachment_file = $attachment[0]->guid;
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = '';
								$postmeta->meta_key = 'featured_image';
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = $attachment_file;
							}
							if (is_array($checkbox_option_fields) && in_array($postmeta->meta_key, $checkbox_option_fields)) {
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = '';
								$eshop_products = unserialize($eshop_products); //print_r($eshop_products);
								foreach ($eshop_products as $key) {
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] .= $key . ',';
								}
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = substr($PostMetaData[$postmeta->post_id][$postmeta->meta_key], 0, -1);
							}
						}

					}
					// Tags & Categories
					$get_tags = wp_get_post_tags($postID, array('fields' => 'names'));
					$postTags = $postCategory = '';
					foreach ($get_tags as $tags) {
						$postTags .= $tags . ',';
					}
					$postTags = substr($postTags, 0, -1);
					$TermsData[$postID]['post_tag'] = $postTags;
					$get_categotries = wp_get_post_categories($postID, array('fields' => 'names'));
					foreach ($get_categotries as $category) {
						$postCategory .= $category . '|';
					}
					$postCategory = substr($postCategory, 0, -1);
					$TermsData[$postID]['post_category'] = $postCategory;
				}
				$ExportData = array();
				// Merge all arrays
				foreach ($PostData as $pd_key => $pd_val) {
					if (array_key_exists($pd_key, $PostMetaData)) {
						$ExportData[$pd_key] = array_merge($PostData[$pd_key], $PostMetaData[$pd_key]);
					} else {
						$ExportData[$pd_key] = $PostData[$pd_key];
					}
					if (array_key_exists($pd_key, $TermsData)) {
						if (empty($ExportData[$pd_key])) {
							$ExportData[$pd_key] = array();
						}
						$ExportData[$pd_key] = array_merge($ExportData[$pd_key], $TermsData[$pd_key]);
					}
				}

				foreach ($Header as $header_key) {
					foreach ($ExportData as $ED_key) {
						if (array_key_exists($header_key, $ED_key)) {
							$CSVContent[$header_key][] = $ED_key[$header_key];
						} else {
							$CSVContent[$header_key][] = '';
						}
					}
				}
			}

			# GENERATE AS CSV
			$CSVDATA = array();
			for ($j = 0; $j < $fieldsCount; $j++) {
				foreach ($Header as $value) {
					$CSVDATA[$j] .= '"' . $CSVContent[$value][$j] . '",';
				}
			}
			$CSV_FILE_CONTENT = array();
			foreach ($Header as $csv_header) {
				if ($csv_header == '_eshop_stock') {
					$csv_header = 'stock_available';
				}
				if ($csv_header == 'cart_radio') {
					$csv_header = 'cart_option';
				}
				if ($csv_header == 'sale') {
					$csv_header = 'product_in_sale';
				}
				if ($csv_header == 'featured') {
					$csv_header = 'featured_product';
				}
				if ($csv_header == 'sku' || $csv_header == '_wpsc_sku') {
					$csv_header = 'SKU';
				}
				if ($csv_header == '_aioseop_keywords') {
					$csv_header = 'seo_keywords';
				}
				if ($csv_header == '_aioseop_description') {
					$csv_header = 'seo_description';
				}
				if ($csv_header == '_aioseop_title') {
					$csv_header = 'seo_title';
				}
				if ($csv_header == '_aioseop_noindex') {
					$csv_header = 'seo_noindex';
				}
				if ($csv_header == '_aioseop_nofollow') {
					$csv_header = 'seo_nofollow';
				}
				if ($csv_header == '_aioseop_disable') {
					$csv_header = 'seo_disable';
				}
				if ($csv_header == '_aioseop_disable_analytics') {
					$csv_header = 'seo_disable_analytics';
				}
				if ($csv_header == '_yoast_wpseo_focuskw') {
					$csv_header = 'focus_keyword';
				}
				if ($csv_header == '_yoast_wpseo_title') {
					$csv_header = 'title';
				}
				if ($csv_header == '_yoast_wpseo_metadesc') {
					$csv_header = 'meta_desc';
				}
				if ($csv_header == '_yoast_wpseo_meta-robots-noindex') {
					$csv_header = 'meta-robots-noindex';
				}
				if ($csv_header == '_yoast_wpseo_meta-robots-nofollow') {
					$csv_header = 'meta-robots-nofollow';
				}
				if ($csv_header == '_yoast_wpseo_meta-robots-adv') {
					$csv_header = 'meta-robots-adv';
				}
				if ($csv_header == '_yoast_wpseo_sitemap-include') {
					$csv_header = 'sitemap-include';
				}
				if ($csv_header == '_yoast_wpseo_sitemap-prio') {
					$csv_header = 'sitemap-prio';
				}
				if ($csv_header == '_yoast_wpseo_canonical') {
					$csv_header = 'canonical';
				}
				if ($csv_header == '_yoast_wpseo_redirect') {
					$csv_header = 'redirect';
				}
				if ($csv_header == '_yoast_wpseo_opengraph-description') {
					$csv_header = 'opengraph-description';
				}
				if ($csv_header == '_yoast_wpseo_google-plus-description') {
					$csv_header = 'google-plus-description';
				}
				if (array_key_exists($csv_header, $acf_fields)) {
					$csv_header = $acf_fields[$csv_header];
				}

				$CSV_FILE_CONTENT .= $csv_header . ",";
			}

			$CSV_FILE_CONTENT = substr($CSV_FILE_CONTENT, 0, -1);
			$CSV_FILE_CONTENT .= "\n";
			if ($CSVDATA) {
				foreach ($CSVDATA as $csv_content) {
					$csv_content = substr($csv_content, 0, -1);
					$CSV_FILE_CONTENT .= $csv_content . "\n";
				}
			}
		} elseif ($exporttype == 'customerreviews') {
			$header_query1 = "SELECT * FROM  wp_wpcreviews";
			#TODO: Remove * from the SQL

			$result_header_query1 = $wpdb->get_results($header_query1);
			foreach ($result_header_query1 as $rhq1_key) {
				foreach ($rhq1_key as $rhq1_headkey => $rhq1_headval) {
					if (!in_array($rhq1_headkey, $Header)) {
						$Header[] = $rhq1_headkey;
					}
				}
			}

			$Header[] = 'date_time';
			$Header[] = 'reviewer_name';
			$Header[] = 'reviewer_email';
			$Header[] = 'reviewer_ip';
			$Header[] = 'review_title';
			$Header[] = 'review_text';
			$Header[] = 'review_response';
			$Header[] = 'status';
			$Header[] = 'review_rating';
			$Header[] = 'reviewer_url';
			$Header[] = 'page_id';
			$Header[] = 'custom_fields';

			# GENERATE AS CSV
			$CSVDATA = array();
			for ($j = 0; $j < 11; $j++) {
				foreach ($Header as $value) {
					$CSVDATA[$j] .= '"' . $value[$j] . '",';
				}
			}
			$CSV_FILE_CONTENT = array();
			foreach ($Header as $csv_header) {
				if ($csv_header == 'date_time') {
					$csv_header = 'date_time';
				}
				if ($csv_header == 'reviewer_name') {
					$csv_header = 'reviewer_name';
				}
				if ($csv_header == 'reviewer_email') {
					$csv_header = 'reviewer_email';
				}
				if ($csv_header == 'reviewer_ip') {
					$csv_header = 'reviewer_ip';
				}
				if ($csv_header == 'review_title') {
					$csv_header = 'review_title';
				}
				if ($csv_header == 'review_text') {
					$csv_header = 'review_text';
				}
				if ($csv_header == 'review_response') {
					$csv_header = 'review_response';
				}
				if ($csv_header == 'status') {
					$csv_header = 'status';
				}
				if ($csv_header == 'review_rating') {
					$csv_header = 'review_rating';
				}
				if ($csv_header == 'reviewer_url') {
					$csv_header = 'reviewer_url';
				}
				if ($csv_header == 'page_id') {
					$csv_header = 'page_id';
				}
				if ($csv_header == 'custom_fields') {
					$csv_header = 'custom_fields';
				}

				$isActive = false;
				$active_plugins = get_option('active_plugins');
				if (in_array('wp-customer-reviews/wp-customer-reviews.php', $active_plugins)) {
					$isActive = true;
				}
				#TODO: $isActive not used in this function / overwritten immediately
				$CSV_FILE_CONTENT .= $csv_header . ",";
			}
			$CSV_FILE_CONTENT = substr($CSV_FILE_CONTENT, 0, -1);
			$CSV_FILE_CONTENT .= "\n";
			if ($CSVDATA) {
				foreach ($CSVDATA as $csv_content) {
					$csv_content = substr($csv_content, 0, -1);
					$CSV_FILE_CONTENT .= $csv_content . "\n";
				}
			}
		} elseif ($exporttype == 'eshop') {
			$exporttype = 'post';
			$header_query1 = "SELECT wp.* FROM  wp_posts wp where post_type = '$exporttype'";
			$header_query2 = "SELECT post_id, meta_key, meta_value FROM  wp_posts wp JOIN wp_postmeta wpm  ON wpm.post_id = wp.ID where meta_key NOT IN ('_edit_lock','_edit_last')";
			$result_header_query1 = $wpdb->get_results($header_query1);
			$result_header_query2 = $wpdb->get_results($header_query2);
			foreach ($result_header_query1 as $rhq1_key) {
				foreach ($rhq1_key as $rhq1_headkey => $rhq1_headval) {
					if (!in_array($rhq1_headkey, $Header)) {
						if ($rhq1_headkey != 'to_ping' && $rhq1_headkey != 'pinged' && $rhq1_headkey != 'post_mime_type') {
							$Header[] = $rhq1_headkey;
						}
					}
				}
			}
			foreach ($result_header_query2 as $rhq2_headkey) {
				if (!in_array($rhq2_headkey->meta_key, $Header)) {
					if ($rhq2_headkey->meta_key != '_eshop_product' && $rhq2_headkey->meta_key != '_wp_attached_file' && $rhq2_headkey->meta_key != 'products' && $rhq2_headkey->meta_key != '_eshop_sale' && $rhq2_headkey->meta_key != 'post_mime_type' && $rhq2_headkey->meta_key != '_thumbnail_id' && $rhq2_headkey->meta_key != '_wp_attachment_metadata' && $rhq2_headkey->meta_key != '_eshop_featured') {
						$Header[] = $rhq2_headkey->meta_key;
					}
				}
			}
			$Header[] = 'products_option';
			$Header[] = 'regular_price';
			$Header[] = 'sale_price';
			$Header[] = 'featured_image';
			$Header[] = 'post_tag';
			$Header[] = 'post_category';
			$get_post_ids = "select DISTINCT ID from wp_posts p join wp_postmeta pm on pm.post_id = p.ID where post_type = '$exporttype' and post_status in ('publish','draft','future','private','pending') and pm.meta_key = 'sku'";

			$result = $wpdb->get_col($get_post_ids);
			$fieldsCount = count($result);
			if ($result) {
				foreach ($result as $postID) {
					$pId = $pId . ',' . $postID;
					$query1 = "SELECT wp.* FROM  wp_posts wp where ID=$postID";
					$result_query1 = $wpdb->get_results($query1);
					if (!empty($result_query1)) {
						foreach ($result_query1 as $posts) {
							foreach ($posts as $post_key => $post_value) {
								if ($post_key == 'post_status') {
									if (is_sticky($postID)) {
										$PostData[$postID][$post_key] = 'Sticky';
										$post_status = 'Sticky';
									} else {
										$PostData[$postID][$post_key] = $post_value;
										$post_status = $post_value;
									}
								} else {
									$PostData[$postID][$post_key] = $post_value;
								}
								if ($post_key == 'post_password') {
									if ($post_value) {
										$PostData[$postID]['post_status'] = "{" . $post_value . "}";
									} else {
										$PostData[$postID]['post_status'] = $post_status;
									}
								}
							}
						}
					}
					$query2 = "SELECT post_id, meta_key, meta_value FROM  wp_posts wp JOIN wp_postmeta wpm  ON wpm.post_id = wp.ID where meta_key NOT IN ('_edit_lock','_edit_last') AND ID=$postID";
					$result_query2 = $wpdb->get_results($query2);
					if (!empty($result_query2)) {
						foreach ($result_query2 as $postmeta) {
							if ($postmeta->meta_key != '_eshop_product' && $postmeta->meta_key != '_thumbnail_id') {
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = $postmeta->meta_value;
							}
							$eshop_products = $postmeta->meta_value;
							if ($postmeta->meta_key == 'products') {
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = '';
								$eshop_products = unserialize($eshop_products);
								foreach ($eshop_products as $key) {
									$PostMetaData[$postmeta->post_id]['products_option'] .= $key['option'] . ',';
									$PostMetaData[$postmeta->post_id]['sale_price'] .= $key['saleprice'] . ',';
									$PostMetaData[$postmeta->post_id]['regular_price'] .= $key['price'] . ',';
								}
								$PostMetaData[$postmeta->post_id]['products_option'] = substr($PostMetaData[$postmeta->post_id]['products_option'], 0, -1);
								$PostMetaData[$postmeta->post_id]['sale_price'] = substr($PostMetaData[$postmeta->post_id]['sale_price'], 0, -1);
								$PostMetaData[$postmeta->post_id]['regular_price'] = substr($PostMetaData[$postmeta->post_id]['regular_price'], 0, -1);
							}
							if ($postmeta->meta_key == '_thumbnail_id') {
								$attachment_file = '';
								#TODO: $attachment_file not used in this function / overwritten immediately
								$get_attachement = "select guid from wp_posts where ID = $postmeta->meta_value AND post_type = 'attachment'";
								$attachment = $wpdb->get_results($get_attachement);
								$attachment_file = $attachment[0]->guid;
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = '';
								$postmeta->meta_key = 'featured_image';
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = $attachment_file;
							}
						}

					}
					// Tags & Categories
					$get_tags = wp_get_post_tags($postID, array('fields' => 'names'));
					$postTags = $postCategory = '';
					foreach ($get_tags as $tags) {
						$postTags .= $tags . ',';
					}
					$postTags = substr($postTags, 0, -1);
					$TermsData[$postID]['post_tag'] = $postTags;
					$get_categotries = wp_get_post_categories($postID, array('fields' => 'names'));
					foreach ($get_categotries as $category) {
						$postCategory .= $category . '|';
					}
					$postCategory = substr($postCategory, 0, -1);
					$TermsData[$postID]['post_category'] = $postCategory;
				}

				$ExportData = array();
				// Merge all arrays
				foreach ($PostData as $pd_key => $pd_val) {
					if (array_key_exists($pd_key, $PostMetaData)) {
						$ExportData[$pd_key] = array_merge($PostData[$pd_key], $PostMetaData[$pd_key]);
					}
					if (array_key_exists($pd_key, $TermsData)) {
						$ExportData[$pd_key] = array_merge($ExportData[$pd_key], $TermsData[$pd_key]);
					}
				}
				foreach ($Header as $header_key) {
					foreach ($ExportData as $ED_key) {
						if (array_key_exists($header_key, $ED_key)) {
							$CSVContent[$header_key][] = $ED_key[$header_key];
						} else {
							$CSVContent[$header_key][] = '';
						}
					}
				}
			}
			# GENERATE AS CSV
			$CSVDATA = array();
			for ($j = 0; $j < $fieldsCount; $j++) {
				foreach ($Header as $value) {
					$CSVDATA[$j] .= '"' . $CSVContent[$value][$j] . '",';
				}
			}
			$CSV_FILE_CONTENT = array();
			foreach ($Header as $csv_header) {
				if ($csv_header == '_eshop_stock') {
					$csv_header = 'stock_available';
				}
				if ($csv_header == 'cart_radio') {
					$csv_header = 'cart_option';
				}
				if ($csv_header == 'sale') {
					$csv_header = 'product_in_sale';
				}
				if ($csv_header == 'featured') {
					$csv_header = 'featured_product';
				}
				if ($csv_header == 'sku') {
					$csv_header = 'SKU';
				}
				if ($csv_header == '_aioseop_keywords') {
					$csv_header = 'seo_keywords';
				}
				if ($csv_header == '_aioseop_description') {
					$csv_header = 'seo_description';
				}
				if ($csv_header == '_aioseop_title') {
					$csv_header = 'seo_title';
				}
				if ($csv_header == '_aioseop_noindex') {
					$csv_header = 'seo_noindex';
				}
				if ($csv_header == '_aioseop_nofollow') {
					$csv_header = 'seo_nofollow';
				}
				if ($csv_header == '_aioseop_disable') {
					$csv_header = 'seo_disable';
				}
				if ($csv_header == '_aioseop_disable_analytics') {
					$csv_header = 'seo_disable_analytics';
				}
				if ($csv_header == '_yoast_wpseo_focuskw') {
					$csv_header = 'focus_keyword';
				}
				if ($csv_header == '_yoast_wpseo_title') {
					$csv_header = 'title';
				}
				if ($csv_header == '_yoast_wpseo_metadesc') {
					$csv_header = 'meta_desc';
				}
				if ($csv_header == '_yoast_wpseo_meta-robots-noindex') {
					$csv_header = 'meta-robots-noindex';
				}
				if ($csv_header == '_yoast_wpseo_meta-robots-nofollow') {
					$csv_header = 'meta-robots-nofollow';
				}
				if ($csv_header == '_yoast_wpseo_meta-robots-adv') {
					$csv_header = 'meta-robots-adv';
				}
				if ($csv_header == '_yoast_wpseo_sitemap-include') {
					$csv_header = 'sitemap-include';
				}
				if ($csv_header == '_yoast_wpseo_sitemap-prio') {
					$csv_header = 'sitemap-prio';
				}
				if ($csv_header == '_yoast_wpseo_canonical') {
					$csv_header = 'canonical';
				}
				if ($csv_header == '_yoast_wpseo_redirect') {
					$csv_header = 'redirect';
				}
				if ($csv_header == '_yoast_wpseo_opengraph-description') {
					$csv_header = 'opengraph-description';
				}
				if ($csv_header == '_yoast_wpseo_google-plus-description') {
					$csv_header = 'google-plus-description';
				}

				if ($csv_header == 'post_tag') {
					$csv_header = 'tags';
				}
				if ($csv_header == 'post_category') {
					$csv_header = 'category';
				}
				$CSV_FILE_CONTENT .= $csv_header . ",";
			}
			$CSV_FILE_CONTENT = substr($CSV_FILE_CONTENT, 0, -1);
			$CSV_FILE_CONTENT .= "\n";
			if ($CSVDATA) {
				foreach ($CSVDATA as $csv_content) {
					$csv_content = substr($csv_content, 0, -1);
					$CSV_FILE_CONTENT .= $csv_content . "\n";
				}
			}
		} elseif ($exporttype == 'wpcommerce') {
			$exporttype = 'wpsc-product';
			$header_query1 = "SELECT wp.* FROM  wp_posts wp where post_type = '$exporttype'";
			$header_query2 = "SELECT post_id, meta_key, meta_value FROM  wp_posts wp JOIN wp_postmeta wpm  ON wpm.post_id = wp.ID where meta_key NOT IN ('_edit_lock','_edit_last')";
			$result_header_query1 = $wpdb->get_results($header_query1);
			$result_header_query2 = $wpdb->get_results($header_query2);
			foreach ($result_header_query1 as $rhq1_key) {
				foreach ($rhq1_key as $rhq1_headkey => $rhq1_headval) {
					if (!in_array($rhq1_headkey, $Header)) {
						$Header[] = $rhq1_headkey;
					}
				}
			}
			foreach ($result_header_query2 as $rhq2_headkey) {
				if (!in_array($rhq2_headkey->meta_key, $Header)) {
					if ($rhq2_headkey->meta_key != '_eshop_product' && $rhq2_headkey->meta_key != '_wp_attached_file' && $rhq2_headkey->meta_key != '_wpsc_product_metadata') {
						$Header[] = $rhq2_headkey->meta_key;
					}
				}
			}
			$Header[] = 'featured_image';
			$Header[] = 'product_tag';
			$Header[] = 'wpsc_product_category';
			$Header[] = 'notify_when_none_left';
			$Header[] = 'unpublish_when_none_left';
			$Header[] = 'wpec_taxes_taxable_amount';
			$Header[] = 'wpec_taxes_taxable';
			$Header[] = 'external_link';
			$Header[] = 'external_link_text';
			$Header[] = 'external_link_target';
			$Header[] = 'no_shipping';
			$Header[] = 'weight';
			$Header[] = 'weight_unit';
			$Header[] = 'height';
			$Header[] = 'height_unit';
			$Header[] = 'width';
			$Header[] = 'width_unit';
			$Header[] = 'length';
			$Header[] = 'length_unit';
			$Header[] = 'merchant_notes';
			$Header[] = 'enable_comments';
			$Header[] = 'quantity_limited';
			$Header[] = 'special';
			$Header[] = 'display_weight_as';
			$Header[] = 'table_rate_price';
			$Header[] = 'state';
			$Header[] = 'quantity';
			$Header[] = 'shipping';
			$Header[] = 'table_price';
			$Header[] = 'google_prohibited';

			$get_post_ids = "select DISTINCT ID from wp_posts p join wp_postmeta pm on pm.post_id = p.ID where post_type = '$exporttype' and post_status in ('publish','draft','future','private','pending') and pm.meta_key = '_wpsc_sku'";
			$result = $wpdb->get_col($get_post_ids);
			$fieldsCount = count($result);
			if ($result) {
				foreach ($result as $postID) {
					$pId = $pId . ',' . $postID;
					$query1 = "SELECT wp.* FROM  wp_posts wp where ID=$postID";
					$result_query1 = $wpdb->get_results($query1);
					if (!empty($result_query1)) {
						foreach ($result_query1 as $posts) {
							foreach ($posts as $post_key => $post_value) {
								if ($post_key == 'post_status') {
									if (is_sticky($postID)) {
										$PostData[$postID][$post_key] = 'Sticky';
										$post_status = 'Sticky';
									} else {
										$PostData[$postID][$post_key] = $post_value;
										$post_status = $post_value;
									}
								} else {
									$PostData[$postID][$post_key] = $post_value;
								}
								if ($post_key == 'post_password') {
									if ($post_value) {
										$PostData[$postID]['post_status'] = "{" . $post_value . "}";
									} else {
										$PostData[$postID]['post_status'] = $post_status;
									}
								}
							}
						}
					}
					$query2 = "SELECT post_id, meta_key, meta_value FROM  wp_posts wp JOIN wp_postmeta wpm  ON wpm.post_id = wp.ID where meta_key NOT IN ('_edit_lock','_edit_last') AND ID=$postID";
					$result_query2 = $wpdb->get_results($query2);
					if (!empty($result_query2)) {
						foreach ($result_query2 as $postmeta) {
							if ($postmeta->meta_key != '_eshop_product' && $postmeta->meta_key != '_thumbnail_id') {
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = $postmeta->meta_value;
							}
							$eshop_products = $postmeta->meta_value;
							if ($postmeta->meta_key == 'products') {
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = '';
								$eshop_products = unserialize($eshop_products);
								foreach ($eshop_products as $key) {
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] .= $key['option'] . '|' . $key['price'] . '|' . $key['saleprice'] . ',';
								}
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = substr($PostMetaData[$postmeta->post_id][$postmeta->meta_key], 0, -1);
							}
							if ($postmeta->meta_key == '_thumbnail_id') {
								$attachment_file = '';
								$get_attachement = "select guid from wp_posts where ID = $postmeta->meta_value AND post_type = 'attachment'";
								$attachment = $wpdb->get_results($get_attachement);
								$attachment_file = $attachment[0]->guid;
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = '';
								$postmeta->meta_key = 'featured_image';
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = $attachment_file;
							}
							if ($postmeta->meta_key == '_wpsc_product_metadata') {
								$productMeta = unserialize($postmeta->meta_value);
								foreach ($productMeta as $metaKey => $metaValue) {
									if ($metaKey == 'notify_when_none_left' || $metaKey == 'unpublish_when_none_left' || $metaKey == 'external_link' || $metaKey == 'external_link_text' || $metaKey == 'external_link_target' || $metaKey == 'no_shipping' || $metaKey == 'weight' || $metaKey == 'weight_unit' || $metaKey == 'merchant_notes' || $metaKey == 'enable_comments' || $metaKey == 'quantity_limited' || $metaKey == 'special' || $metaKey == 'display_weight_as' || $metaKey == 'google_prohibited') {
										$PostMetaData[$postmeta->post_id][$metaKey] = $metaValue;
									}
									if ($metaKey == 'wpec_taxes_taxable_amount') {
										$PostMetaData[$postmeta->post_id]['taxable_amount'] = $metaValue;
									}
									if ($metaKey == 'wpec_taxes_taxable') {
										$PostMetaData[$postmeta->post_id]['is_taxable'] = $metaValue;
									}
									if ($metaKey == 'dimensions') {
										foreach ($metaValue as $mk => $mv) {
											$PostMetaData[$postmeta->post_id][$mk] = $mv;
										}
									}
									if ($metaKey == 'shipping') {
										$PostMetaData[$postmeta->post_id]['shipping'] = $metaKey['local'] . '|' . $metaKey['international'];
									}
									if ($metaKey == 'table_rate_price') {
										$PostMetaData[$postmeta->post_id]['state'] = $metaKey['table_rate_price']['state'];
										$PostMetaData[$postmeta->post_id]['quantity'] = $metaKey['table_rate_price']['quantity'][0];
										$PostMetaData[$postmeta->post_id]['table_price'] = $metaKey['table_rate_price']['table_price'][0];
									}
								}
							}
						}

					}
					// Tags & Categories
					$postTags = $postCategory = '';
					$taxonomies = get_object_taxonomies($exporttype);
					foreach ($taxonomies as $taxonomy) {
						if ($taxonomy == 'product_tag') {
							$get_tags = get_the_terms($postID, $taxonomy);
							if ($get_tags) {
								foreach ($get_tags as $tags) {
									$postTags .= $tags->name . ',';
								}
							}
							$postTags = substr($postTags, 0, -1);
							$TermsData[$postID]['product_tag'] = $postTags;
						}
						if ($taxonomy == 'wpsc_product_category') {
							$get_categotries = wp_get_post_terms($postID, $taxonomy);
							if ($get_categotries) {
								foreach ($get_categotries as $category) {
									$postCategory .= $category->name . '|';
								}
							}
							$postCategory = substr($postCategory, 0, -1);
							$TermsData[$postID][$taxonomy] = $postCategory;
						}
					}
				}

				$ExportData = array();
				// Merge all arrays
				foreach ($PostData as $pd_key => $pd_val) {
					if (array_key_exists($pd_key, $PostMetaData)) {
						$ExportData[$pd_key] = array_merge($PostData[$pd_key], $PostMetaData[$pd_key]);
					}
					if (array_key_exists($pd_key, $TermsData)) {
						$ExportData[$pd_key] = array_merge($ExportData[$pd_key], $TermsData[$pd_key]);
					}
				}
				foreach ($Header as $header_key) {
					foreach ($ExportData as $ED_key) {
						if (array_key_exists($header_key, $ED_key)) {
							$CSVContent[$header_key][] = $ED_key[$header_key];
						} else {
							$CSVContent[$header_key][] = '';
						}
					}
				}
			}
			# GENERATE AS CSV
			$CSVDATA = array();
			for ($j = 0; $j < $fieldsCount; $j++) {
				foreach ($Header as $value) {
					$CSVDATA[$j] .= '"' . $CSVContent[$value][$j] . '",';
				}
			}

			$CSV_FILE_CONTENT = array();
			foreach ($Header as $csv_header) {
				if ($csv_header == '_wpsc_stock') {
					$csv_header = 'stock';
				}
				if ($csv_header == '_wpsc_price') {
					$csv_header = 'price';
				}
				if ($csv_header == '_wpsc_special_price') {
					$csv_header = 'sale_price';
				}
				if ($csv_header == '_wpsc_sku') {
					$csv_header = 'SKU';
				}
				if ($csv_header == 'wpec_taxes_taxable_amount') {
					$csv_header = 'taxable_amount';
				}
				if ($csv_header == 'wpec_taxes_taxable') {
					$csv_header = 'is_taxable';
				}
				if ($csv_header == '_aioseop_keywords') {
					$csv_header = 'seo_keywords';
				}
				if ($csv_header == '_aioseop_description') {
					$csv_header = 'seo_description';
				}
				if ($csv_header == '_aioseop_title') {
					$csv_header = 'seo_title';
				}
				if ($csv_header == '_aioseop_noindex') {
					$csv_header = 'seo_noindex';
				}
				if ($csv_header == '_aioseop_nofollow') {
					$csv_header = 'seo_nofollow';
				}
				if ($csv_header == '_aioseop_disable') {
					$csv_header = 'seo_disable';
				}
				if ($csv_header == '_yoast_wpseo_focuskw') {
					$csv_header = 'focus_keyword';
				}
				if ($csv_header == '_yoast_wpseo_title') {
					$csv_header = 'title';
				}
				if ($csv_header == '_yoast_wpseo_metadesc') {
					$csv_header = 'meta_desc';
				}
				if ($csv_header == '_yoast_wpseo_meta-robots-noindex') {
					$csv_header = 'meta-robots-noindex';
				}
				if ($csv_header == '_yoast_wpseo_meta-robots-nofollow') {
					$csv_header = 'meta-robots-nofollow';
				}
				if ($csv_header == '_yoast_wpseo_meta-robots-adv') {
					$csv_header = 'meta-robots-adv';
				}
				if ($csv_header == '_yoast_wpseo_sitemap-include') {
					$csv_header = 'sitemap-include';
				}
				if ($csv_header == '_yoast_wpseo_sitemap-prio') {
					$csv_header = 'sitemap-prio';
				}
				if ($csv_header == '_yoast_wpseo_canonical') {
					$csv_header = 'canonical';
				}
				if ($csv_header == '_yoast_wpseo_redirect') {
					$csv_header = 'redirect';
				}
				if ($csv_header == '_yoast_wpseo_opengraph-description') {
					$csv_header = 'opengraph-description';
				}
				if ($csv_header == '_yoast_wpseo_google-plus-description') {
					$csv_header = 'google-plus-description';
				}
				if ($csv_header == '_aioseop_disable_analytics') {
					$csv_header = 'seo_disable_analytics';
				}
				if ($csv_header == 'product_tag') {
					$csv_header = 'product_tags';
				}
				if ($csv_header == 'wpsc_product_category') {
					$csv_header = 'product_category';
				}
				// Code for wp-ecommerce-custom-fields support
				$isActive = false;
				$active_plugins = get_option('active_plugins');
				if (in_array('wp-e-commerce-custom-fields/custom-fields.php', $active_plugins)) {
					$isActive = true;
				}
				if ($isActive) {
					$get_wpcf = unserialize(get_option('wpsc_cf_data'));
					foreach ($get_wpcf as $wpcf_key => $wpcf_val) {
						$key = '_wpsc_' . $wpcf_val['slug'];
						if ($csv_header == $key) {
							$csv_header = $wpcf_val['name'];
						}
					}
				} // Code ends here

				$CSV_FILE_CONTENT .= $csv_header . ",";
			}
			$CSV_FILE_CONTENT = substr($CSV_FILE_CONTENT, 0, -1);
			$CSV_FILE_CONTENT .= "\n";
			if ($CSVDATA) {
				foreach ($CSVDATA as $csv_content) {
					$csv_content = substr($csv_content, 0, -1);
					$CSV_FILE_CONTENT .= $csv_content . "\n";
				}
			}
		} elseif ($exporttype == 'woocommerce') {
			$exporttype = 'product';
			$header_query1 = "SELECT wp.* FROM  wp_posts wp where post_type = '$exporttype'";
			$header_query2 = "SELECT post_id, meta_key, meta_value FROM  wp_posts wp JOIN wp_postmeta wpm  ON wpm.post_id = wp.ID where meta_key NOT IN ('_edit_lock','_edit_last')";
			$result_header_query1 = $wpdb->get_results($header_query1);
			$result_header_query2 = $wpdb->get_results($header_query2);
			foreach ($result_header_query1 as $rhq1_key) {
				foreach ($rhq1_key as $rhq1_headkey => $rhq1_headval) {
					if (!in_array($rhq1_headkey, $Header)) {
						$Header[] = $rhq1_headkey;
					}
				}
			}
			foreach ($result_header_query2 as $rhq2_headkey) {
				if (!in_array($rhq2_headkey->meta_key, $Header)) {
					if ($rhq2_headkey->meta_key != '_eshop_product' && $rhq2_headkey->meta_key != '_wp_attached_file') {
						$Header[] = $rhq2_headkey->meta_key;
					}
				}
			}
			$Header[] = 'featured_image';
			$Header[] = 'product_tag';
			$Header[] = 'product_category';
			$Header[] = '_product_attribute_name';
			$Header[] = '_product_attribute_value';
			$Header[] = '_product_attribute_visible';
			$Header[] = '_product_attribute_variation';

			$get_post_ids = "select DISTINCT ID from wp_posts p join wp_postmeta pm on pm.post_id = p.ID where post_type = '$exporttype' and post_status in ('publish','draft','future','private','pending') and pm.meta_key = '_sku'";

			$result = $wpdb->get_col($get_post_ids);
			$fieldsCount = count($result);
			if ($result) {
				foreach ($result as $postID) {
					$pId = $pId . ',' . $postID;
					$query1 = "SELECT wp.* FROM  wp_posts wp where ID=$postID";
					$result_query1 = $wpdb->get_results($query1);
					if (!empty($result_query1)) {
						foreach ($result_query1 as $posts) {
							foreach ($posts as $post_key => $post_value) {
								if ($post_key == 'post_status') {
									if (is_sticky($postID)) {
										$PostData[$postID][$post_key] = 'Sticky';
										$post_status = 'Sticky';
									} else {
										$PostData[$postID][$post_key] = $post_value;
										$post_status = $post_value;
									}
								} else {
									$PostData[$postID][$post_key] = $post_value;
								}
								if ($post_key == 'post_password') {
									if ($post_value) {
										$PostData[$postID]['post_status'] = "{" . $post_value . "}";
									} else {
										$PostData[$postID]['post_status'] = $post_status;
									}
								}
								if ($post_key == 'comment_status') {
									if ($post_value == 'closed') {
										$PostData[$postID]['comment_status'] = 0;
									}
									if ($post_value == 'open') {
										$PostData[$postID]['comment_status'] = 1;
									}
								}

							}
						}
					}
					$query2 = "SELECT post_id, meta_key, meta_value FROM  wp_posts wp JOIN wp_postmeta wpm  ON wpm.post_id = wp.ID where meta_key NOT IN ('_edit_lock','_edit_last') AND ID=$postID";
					$result_query2 = $wpdb->get_results($query2);
					if (!empty($result_query2)) {
						foreach ($result_query2 as $postmeta) {
							if ($postmeta->meta_key != '_eshop_product' && $postmeta->meta_key != '_thumbnail_id') {
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = $postmeta->meta_value;
							}
							$eshop_products = $postmeta->meta_value;
							if ($postmeta->meta_key == '_product_attributes') {
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = '';
								$product_attribute_name = $product_attribute_value = $product_attribute_visible = $product_attribute_variation = '';
								$PostMetaData[$postmeta->post_id]['_product_attribute_name'] = '';
								$PostMetaData[$postmeta->post_id]['_product_attribute_value'] = '';
								$PostMetaData[$postmeta->post_id]['_product_attribute_visible'] = '';
								$PostMetaData[$postmeta->post_id]['_product_attribute_variation'] = '';
								$eshop_products_unser1 = unserialize($eshop_products);
								$check_attr_count1 = count($eshop_products_unser1);
								if ($check_attr_count1 == 1) {
									$eshop_products_unser2 = unserialize($eshop_products_unser1);
									$check_attr_count2 = count($eshop_products_unser2);
								}
								if ($check_attr_count1 < $check_attr_count2) {
									$unserialized_attributes = $eshop_products_unser2;
								} else {
									$unserialized_attributes = $eshop_products_unser1;
								}

								foreach ($unserialized_attributes as $key) {
									foreach ($key as $attr_header => $attr_value) {
										if ($attr_header == 'name') {
											$product_attribute_name .= $attr_value . '|';
										}
										if ($attr_header == 'value') {
											$product_attribute_value .= $attr_value . '|';
										}
										if ($attr_header == 'is_visible') {
											$product_attribute_visible .= $attr_value . '|';
										}
										if ($attr_header == 'is_variation') {
											if (isset($attr_value)) {
												$product_attribute_variation .= $attr_value . '|';
											}
										}
									}
								}
								$PostMetaData[$postmeta->post_id]['_product_attribute_name'] = substr($product_attribute_name, 0, -1);
								$PostMetaData[$postmeta->post_id]['_product_attribute_value'] = substr($product_attribute_value, 0, -1);
								$PostMetaData[$postmeta->post_id]['_product_attribute_visible'] = substr($product_attribute_visible, 0, -1);
								$PostMetaData[$postmeta->post_id]['_product_attribute_variation'] = substr($product_attribute_variation, 0, -1);
							}
							$upsellids = array();
							$crosssellids = array();
							#TODO: $upsellids, $crosssellids not used in this function / overwritten immediately
							if ($postmeta->meta_key == '_upsell_ids') {
								if ($postmeta->meta_value != '' && $postmeta->meta_value != null) {
									$upsellids = unserialize($postmeta->meta_value);
									if (is_array($upsellids)) {
										$upsell_ids = null;
										foreach ($upsellids as $upsellID) {
											$upsell_ids .= $upsellID . ',';
										}
										$PostMetaData[$postmeta->post_id]['_upsell_ids'] = substr($upsell_ids, 0, -1);
									} else {
										$PostMetaData[$postmeta->post_id]['_upsell_ids'] = '';
									}
								}
							}
							if ($postmeta->meta_key == '_crosssell_ids') {
								if ($postmeta->meta_value != '' && $postmeta->meta_value != null) {
									$crosssellids = unserialize($postmeta->meta_value);
									if (is_array($crosssellids)) {
										$crosssell_ids = null;
										foreach ($crosssellids as $crosssellID) {
											$crosssell_ids .= $crosssellID . ',';
										}
										$PostMetaData[$postmeta->post_id]['_crosssell_ids'] = substr($crosssell_ids, 0, -1);
									} else {
										$PostMetaData[$postmeta->post_id]['_crosssell_ids'] = '';
									}
								}
							}
							if ($postmeta->meta_key == '_thumbnail_id') {
								$attachment_file = '';
								$get_attachement = "select guid from wp_posts where ID = $postmeta->meta_value AND post_type = 'attachment'";
								$attachment = $wpdb->get_results($get_attachement);
								$attachment_file = $attachment[0]->guid;
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = '';
								$postmeta->meta_key = 'featured_image';
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = $attachment_file;
							}
							if ($postmeta->meta_key == '_visibility') {
								if ($postmeta->meta_value == 'visible') {
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 1;
								}
								if ($postmeta->meta_value == 'catalog') {
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 2;
								}
								if ($postmeta->meta_value == 'search') {
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 3;
								}
								if ($postmeta->meta_value == 'hidden') {
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 4;
								}
							}
							if ($postmeta->meta_key == '_stock_status') {
								if ($postmeta->meta_value == 'instock') {
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 1;
								}
								if ($postmeta->meta_value == 'outofstock') {
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 2;
								}
							}
							if ($postmeta->meta_key == '_tax_status') {
								if ($postmeta->meta_value == 'taxable') {
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 1;
								}
								if ($postmeta->meta_value == 'shipping') {
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 2;
								}
								if ($postmeta->meta_value == 'none') {
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 3;
								}
							}
							if ($postmeta->meta_key == '_tax_class') {
								if ($postmeta->meta_value == '') {
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 1;
								}
								if ($postmeta->meta_value == 'reduced-rate') {
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 2;
								}
								if ($postmeta->meta_value == 'zero-rate') {
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 3;
								}
							}
							if ($postmeta->meta_key == '_backorders') {
								if ($postmeta->meta_value == 'no') {
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 1;
								}
								if ($postmeta->meta_value == 'notify') {
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 2;
								}
								if ($postmeta->meta_value == 'yes') {
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 3;
								}
							}
							if ($postmeta->meta_key == '_featured') {
								if ($postmeta->meta_value == 'no') {
									$PostMetaData[$postmeta->post_id]['featured_product'] = 1;
								}
								if ($postmeta->meta_value == 'yes') {
									$PostMetaData[$postmeta->post_id]['featured_product'] = 2;
								}
								if ($postmeta->meta_value == 'zero-rate') {
									$PostMetaData[$postmeta->post_id]['featured_product'] = 3;
								}
							}
							if ($postmeta->meta_key == '_product_type') {
								if ($postmeta->meta_value == 'simple') {
									$PostMetaData[$postmeta->post_id]['featured_product'] = 1;
								}
								if ($postmeta->meta_value == 'grouped') {
									$PostMetaData[$postmeta->post_id]['featured_product'] = 2;
								}
								if ($postmeta->meta_value == 'variable') {
									$PostMetaData[$postmeta->post_id]['featured_product'] = 4;
								}
							}
						}

					}
					// Tags & Categories
					$postTags = $postCategory = '';
					$taxonomies = get_object_taxonomies($exporttype);
					$get_tags = get_the_terms($postID, 'product_tag');
					if ($get_tags) {
						foreach ($get_tags as $tags) {
							$postTags .= $tags->name . ',';
						}
					}
					$postTags = substr($postTags, 0, -1);
					$TermsData[$postID]['product_tag'] = $postTags;
					foreach ($taxonomies as $taxonomy) {
						if ($taxonomy == 'product_cat') {
							$get_categotries = wp_get_post_terms($postID, $taxonomy);
							if ($get_categotries) {
								foreach ($get_categotries as $category) {
									$postCategory .= $category->name . '|';
								}
							}
							$postCategory = substr($postCategory, 0, -1);
							$TermsData[$postID]['product_category'] = $postCategory;
						}
					}
				}

				$ExportData = array();
				// Merge all arrays
				foreach ($PostData as $pd_key => $pd_val) {
					if (array_key_exists($pd_key, $PostMetaData)) {
						$ExportData[$pd_key] = array_merge($PostData[$pd_key], $PostMetaData[$pd_key]);
					}
					if (array_key_exists($pd_key, $TermsData)) {
						$ExportData[$pd_key] = array_merge($ExportData[$pd_key], $TermsData[$pd_key]);
					}
				}
				foreach ($Header as $header_key) {
					foreach ($ExportData as $ED_key) {
						if (array_key_exists($header_key, $ED_key)) {
							$CSVContent[$header_key][] = $ED_key[$header_key];
						} else {
							$CSVContent[$header_key][] = '';
						}
					}
				}
			}
			# GENERATE AS CSV
			$CSVDATA = array();
			for ($j = 0; $j < $fieldsCount; $j++) {
				foreach ($Header as $value) {
					$CSVDATA[$j] .= '"' . $CSVContent[$value][$j] . '",';
				}
			}
			$CSV_FILE_CONTENT = array();
			foreach ($Header as $csv_header) {
				if ($csv_header == 'post_title') {
					$csv_header = 'product_name';
				}
				if ($csv_header == 'post_content') {
					$csv_header = 'product_content';
				}
				if ($csv_header == 'post_name') {
					$csv_header = 'product_slug';
				}
				if ($csv_header == 'post_excerpt') {
					$csv_header = 'product_short_description';
				}
				if ($csv_header == 'post_status') {
					$csv_header = 'product_status';
				}
				if ($csv_header == 'post_date') {
					$csv_header = 'product_publish_date';
				}
				if ($csv_header == '_product_type') {
					$csv_header = 'product_type';
				}
				if ($csv_header == '_product_shipping_class') {
					$csv_header = 'product_shipping_class';
				}
				if ($csv_header == '_visibility') {
					$csv_header = 'visibility';
				}
				if ($csv_header == '_stock_status') {
					$csv_header = 'stock_status';
				}
				if ($csv_header == '_manage_stock') {
					$csv_header = 'manage_stock';
				}
				if ($csv_header == '_total_sales') {
					$csv_header = 'total_sales';
				}
				if ($csv_header == '_downloadable') {
					$csv_header = 'downloadable';
				}
				if ($csv_header == '_virtual') {
					$csv_header = 'virtual';
				}
				if ($csv_header == '_product_image_gallery') {
					$csv_header = 'product_image_gallery';
				}
				if ($csv_header == '_regular_price') {
					$csv_header = 'regular_price';
				}
				if ($csv_header == '_sale_price') {
					$csv_header = 'sale_price';
				}
				if ($csv_header == '_tax_status') {
					$csv_header = 'tax_status';
				}
				if ($csv_header == '_tax_class') {
					$csv_header = 'tax_class';
				}
				if ($csv_header == '_purchase_note') {
					$csv_header = 'purchase_note';
				}
				if ($csv_header == '_featured') {
					$csv_header = 'featured_product';
				}
				if ($csv_header == '_weight') {
					$csv_header = 'weight';
				}
				if ($csv_header == '_length') {
					$csv_header = 'length';
				}
				if ($csv_header == '_width') {
					$csv_header = 'width';
				}
				if ($csv_header == '_height') {
					$csv_header = 'height';
				}
				if ($csv_header == '_sku') {
					$csv_header = 'sku';
				}
				if ($csv_header == '_upsell_ids') {
					$csv_header = 'upsell_ids';
				}
				if ($csv_header == '_crosssell_ids') {
					$csv_header = 'crosssell_ids';
				}
				if ($csv_header == '_product_attribute_name') {
					$csv_header = 'product_attribute_name';
				}
				if ($csv_header == '_product_attribute_value') {
					$csv_header = 'product_attribute_value';
				}
				if ($csv_header == '_product_attribute_visible') {
					$csv_header = 'product_attribute_visible';
				}
				if ($csv_header == '_product_attribute_variation') {
					$csv_header = 'product_attribute_variation';
				}
				if ($csv_header == '_sale_price_dates_from') {
					$csv_header = 'sale_price_dates_from';
				}
				if ($csv_header == '_sale_price_dates_to') {
					$csv_header = 'sale_price_dates_to';
				}
				if ($csv_header == '_price') {
					$csv_header = 'price';
				}
				if ($csv_header == '_stock') {
					$csv_header = 'stock';
				}
				if ($csv_header == '_backorders') {
					$csv_header = 'backorders';
				}
				if ($csv_header == '_file_paths') {
					$csv_header = 'file_paths';
				}
				if ($csv_header == '_download_limit') {
					$csv_header = 'download_limit';
				}
				if ($csv_header == '_download_expiry') {
					$csv_header = 'download_expiry';
				}
				if ($csv_header == '_product_url') {
					$csv_header = 'product_url';
				}
				if ($csv_header == '_button_text') {
					$csv_header = 'button_text';
				}
				if ($csv_header == '_sold_individually') {
					$csv_header = 'sold_individually';
				}
				if ($csv_header == '_aioseop_keywords') {
					$csv_header = 'seo_keywords';
				}
				if ($csv_header == '_aioseop_description') {
					$csv_header = 'seo_description';
				}
				if ($csv_header == '_aioseop_title') {
					$csv_header = 'seo_title';
				}
				if ($csv_header == '_aioseop_noindex') {
					$csv_header = 'seo_noindex';
				}
				if ($csv_header == '_aioseop_nofollow') {
					$csv_header = 'seo_nofollow';
				}
				if ($csv_header == '_aioseop_disable') {
					$csv_header = 'seo_disable';
				}
				if ($csv_header == '_aioseop_disable_analytics') {
					$csv_header = 'seo_disable_analytics';
				}
				if ($csv_header == '_yoast_wpseo_focuskw') {
					$csv_header = 'focus_keyword';
				}
				if ($csv_header == '_yoast_wpseo_title') {
					$csv_header = 'title';
				}
				if ($csv_header == '_yoast_wpseo_metadesc') {
					$csv_header = 'meta_desc';
				}
				if ($csv_header == '_yoast_wpseo_meta-robots-noindex') {
					$csv_header = 'meta-robots-noindex';
				}
				if ($csv_header == '_yoast_wpseo_meta-robots-nofollow') {
					$csv_header = 'meta-robots-nofollow';
				}
				if ($csv_header == '_yoast_wpseo_meta-robots-adv') {
					$csv_header = 'meta-robots-adv';
				}
				if ($csv_header == '_yoast_wpseo_sitemap-include') {
					$csv_header = 'sitemap-include';
				}
				if ($csv_header == '_yoast_wpseo_sitemap-prio') {
					$csv_header = 'sitemap-prio';
				}
				if ($csv_header == '_yoast_wpseo_canonical') {
					$csv_header = 'canonical';
				}
				if ($csv_header == '_yoast_wpseo_redirect') {
					$csv_header = 'redirect';
				}
				if ($csv_header == '_yoast_wpseo_opengraph-description') {
					$csv_header = 'opengraph-description';
				}
				if ($csv_header == '_yoast_wpseo_google-plus-description') {
					$csv_header = 'google-plus-description';
				}
				$CSV_FILE_CONTENT .= $csv_header . ",";
			}
			$CSV_FILE_CONTENT = substr($CSV_FILE_CONTENT, 0, -1);
			$CSV_FILE_CONTENT .= "\n";
			if ($CSVDATA) {
				foreach ($CSVDATA as $csv_content) {
					$csv_content = substr($csv_content, 0, -1);
					$CSV_FILE_CONTENT .= $csv_content . "\n";
				}
			}
		}
		#TODO: What is this?
		echo '<pre>';
		print_r($CSV_FILE_CONTENT);
		die('it exiusrs');
		header("Content-type: text/x-csv"); # DECLARING FILE TYPE
		header("Content-Transfer-Encoding: binary");
		header("Content-Disposition: attachment; filename=" . $csv_file_name); # EXPORT GENERATED CSV FILE
		header("Pragma: no-cache");
		header("Expires: 0");
		print($CSV_FILE_CONTENT);
		die;
		return $data;
	}
}
