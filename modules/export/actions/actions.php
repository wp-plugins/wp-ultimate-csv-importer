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

class ExportActions extends SkinnyActions
{
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
		if(!empty($request['POST']))
		{
			$type = $request['POST']['export'];
			$filename = $request['POST']['export_filename'];
			if(!empty($type) && !empty($filename))
			{
				$helper = new ultimatecsv_include_helper();
				$helper->generateanddownloadcsv($type, $filename);
			}
		}
		return $data;
	}

	public function executeExport($request)
	{
		$data = array();
		ob_start();
		global $wpdb;
		$Header = $PostData = $PostMetaData = $TermsData = $ExportData = array();
		$exporttype = $request['POST']['export'];
		$export_filename = $request['POST']['export_filename'];
		if($export_filename)
			$csv_file_name = $export_filename.'.csv';
		else
			$csv_file_name='exportas_'.$exporttype.'_'.date("Y-m-d").'.csv';;

		if($exporttypei == 'post' || $exporttype == 'page')
		{
			$header_query1 = "SELECT wp.* FROM  $wpdb->posts wp where post_type = '$exporttype'";
			$header_query2 = "SELECT post_id, meta_key, meta_value FROM  $wpdb->posts wp JOIN $wpdb->postmeta wpm  ON wpm.post_id = wp.ID where meta_key NOT IN ('_edit_lock','_edit_last') and meta_key NOT LIKE 'field_%'";
			$result_header_query1 = $wpdb->get_results($header_query1);
			$result_header_query2 = $wpdb->get_results($header_query2);

			foreach ($result_header_query1 as $rhq1_key) {
				foreach ($rhq1_key as $rhq1_headkey => $rhq1_headval) {
					if (!in_array($rhq1_headkey, $Header))
						$Header[] = $rhq1_headkey;
				}
			}

			foreach ($result_header_query2 as $rhq2_headkey) {
				if (!in_array($rhq2_headkey->meta_key, $Header)) {
					if ($rhq2_headkey->meta_key != '_eshop_product' && $rhq2_headkey->meta_key != '_wp_attached_file')
						$Header[] = $rhq2_headkey->meta_key;
				}
			}

			$Header[] = 'post_tag';
			$Header[] = 'featured_image';
			$Header[] = 'post_category';
			// Code for ACF fields 
			$limit = ( int ) apply_filters ( 'postmeta_form_limit', 30 );
			$get_acf_fields = $wpdb->get_col ( "SELECT meta_value FROM $wpdb->postmeta
					GROUP BY meta_key
					HAVING meta_key LIKE 'field_%'
					ORDER BY meta_key
					LIMIT $limit" );

			foreach ( $get_acf_fields as $acf_value ){
				$get_acf_field = unserialize($acf_value);
				$acf_fields[$get_acf_field['name']] = "CF: ".$get_acf_field['name'];
				$acf_fields_slug[$get_acf_field['name']] = "_".$get_acf_field['name'];
				if(in_array("_".$get_acf_field['name'],$Header)){
					$Header = array_diff($Header,$acf_fields_slug);
				}
				if($get_acf_field['type'] == 'checkbox'){
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
								if(is_array($acf_fields_slug) && !in_array($postmeta->meta_key,$acf_fields_slug))
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
								$get_attachement = "select guid from $wpdb->posts where ID = $postmeta->meta_value AND post_type = 'attachment'";
								$attachment = $wpdb->get_results($get_attachement);
								$attachment_file = $attachment[0]->guid;
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = '';
								$postmeta->meta_key = 'featured_image';
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = $attachment_file;
							}
							if(is_array($checkbox_option_fields) && in_array($postmeta->meta_key,$checkbox_option_fields)){
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
						if (empty($ExportData[$pd_key]))
							$ExportData[$pd_key] = array();
						$ExportData[$pd_key] = array_merge($ExportData[$pd_key], $TermsData[$pd_key]);
					}
				}

				foreach ($Header as $header_key) {
					foreach ($ExportData as $ED_key) {
						if (array_key_exists($header_key, $ED_key))
							$CSVContent[$header_key][] = $ED_key[$header_key];
						else
							$CSVContent[$header_key][] = '';
					}
				}
			}

# GENERATE AS CSV
			for ($j = 0; $j < $fieldsCount; $j++) {
				foreach ($Header as $value) {
					$CSVDATA[$j] .= '"' . $CSVContent[$value][$j] . '",';
				}
			}

			foreach ($Header as $csv_header) {
				if ($csv_header == '_eshop_stock')
					$csv_header = 'stock_available';
				if ($csv_header == 'cart_radio')
					$csv_header = 'cart_option';
				if ($csv_header == 'sale')
					$csv_header = 'product_in_sale';
				if ($csv_header == 'featured')
					$csv_header = 'featured_product';
				if ($csv_header == 'sku' || $csv_header == '_wpsc_sku')
					$csv_header = 'SKU';
				if ($csv_header == '_aioseop_keywords')
					$csv_header = 'seo_keywords';
				if ($csv_header == '_aioseop_description')
					$csv_header = 'seo_description';
				if ($csv_header == '_aioseop_title')
					$csv_header = 'seo_title';
				if ($csv_header == '_aioseop_noindex')
					$csv_header = 'seo_noindex';
				if ($csv_header == '_aioseop_nofollow')
					$csv_header = 'seo_nofollow';
				if ($csv_header == '_aioseop_disable')
					$csv_header = 'seo_disable';
				if ($csv_header == '_aioseop_disable_analytics')
					$csv_header = 'seo_disable_analytics';
				if ($csv_header == '_yoast_wpseo_focuskw')
					$csv_header = 'focus_keyword';
				if ($csv_header == '_yoast_wpseo_title')
					$csv_header = 'title';
				if ($csv_header == '_yoast_wpseo_metadesc')
					$csv_header = 'meta_desc';
				if ($csv_header == '_yoast_wpseo_meta-robots-noindex')
					$csv_header = 'meta-robots-noindex';
				if ($csv_header == '_yoast_wpseo_meta-robots-nofollow')
					$csv_header = 'meta-robots-nofollow';
				if ($csv_header == '_yoast_wpseo_meta-robots-adv')
					$csv_header = 'meta-robots-adv';
				if ($csv_header == '_yoast_wpseo_sitemap-include')
					$csv_header = 'sitemap-include';
				if ($csv_header == '_yoast_wpseo_sitemap-prio')
					$csv_header = 'sitemap-prio';
				if ($csv_header == '_yoast_wpseo_canonical')
					$csv_header = 'canonical';
				if ($csv_header == '_yoast_wpseo_redirect')
					$csv_header = 'redirect';
				if ($csv_header == '_yoast_wpseo_opengraph-description')
					$csv_header = 'opengraph-description';
				if ($csv_header == '_yoast_wpseo_google-plus-description')
					$csv_header = 'google-plus-description';
				if (array_key_exists($csv_header,$acf_fields))
					$csv_header = $acf_fields[$csv_header];

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
