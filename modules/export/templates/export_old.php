<?php
/******************************************************************************************
 * Copyright (C) Smackcoders 2014 - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * You can contact Smackcoders at email address info@smackcoders.com.
 *******************************************************************************************/

require_once('../../../../../../wp-load.php');
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
			$this->WPImpPROExportData($request);#die;
		}
	}

	/**
	 *
	 */
	public function generateCSVHeaders($exporttype){
		global $wpdb;
		$post_type = $exporttype;
		$unwantedHeader = array('_eshop_product', '_wp_attached_file', '_wp_page_template', '_wp_attachment_metadata', '_encloseme');
		if($exporttype == 'woocommerce' || $exporttype == 'marketpress')
			$post_type = 'product';
		if($exporttype == 'wpcommerce')
			$post_type = 'wpsc-product';
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
		foreach($this->getACFvalues() as $acfKey => $acfVal) {
			$unwantedHeader[] = '_' . $acfKey;
		}
		foreach($this->getACFvalues() as $acfKey => $acfVal) {
			if(!in_array($acfKey, $unwantedHeader)) {
				$Header[] = $acfKey;
				$unwantedHeader[] = $acfKey;
			}
		}
		foreach($this->getTypesFields() as $typeKey => $typeVal) {
			if(!in_array($typeKey, $unwantedHeader)) {
				$Header[] = $typeVal;
				$unwantedHeader[] = $typeKey;
			}
		}
		foreach($this->getAIOSEOfields() as $aioseokey => $aioseoval) {
			$Header[] = $aioseoval;
			$unwantedHeader[] = $aioseokey;
		}
		foreach($this->getYoastSEOfields() as $yoastseokey => $yoastseoval) {
			$Header[] = $yoastseoval;
			$unwantedHeader[] = $yoastseokey;
		}
		foreach ($result_header_query2 as $rhq2_headkey) {
			if (!in_array($rhq2_headkey->meta_key, $Header)) {
				if(!in_array($rhq2_headkey->meta_key, $unwantedHeader)) {
					$Header[] = $rhq2_headkey->meta_key;
				}
			}
		}

#print('<pre>'); print_r($Header); die;
		if($exporttype == 'woocommerce' || $exporttype == 'marketpress' || $exporttype == 'wpcommerce' || $exporttype == 'eshop') {
			if($exporttype == 'woocommerce') {
				foreach($this->WoocommerceMetaHeaders() as $woo_hkey => $woo_hval) {
					if(in_array($woo_hval, $Header)) 
						$ProHeader[] = $woo_hkey;
					else
						$ProHeader[] = $woo_hkey;
				}
			} 
			else if($exporttype == 'marketpress') {
				foreach($this->MarketPressHeaders() as $mp_hkey => $mp_hval) {
					if(in_array($mp_hval, $Header))
						$ProHeader[] = $mp_hkey;
					else
						$ProHeader[] = $mp_hkey;
				}
			}
                        else if($exporttype == 'wpcommerce') {
                                foreach($this->WpeCommerceHeaders() as $wpcom_hkey => $wpcom_hval) {
                                        if(in_array($wpcom_hval, $Header))
                                                $ProHeader[] = $wpcom_hkey;
                                        else
                                                $ProHeader[] = $wpcom_hkey;
                                }
                        }
                        else if($exporttype == 'eshop') {
                                foreach($this->EshopHeaders() as $eshop_hkey => $eshop_hval) {
                                        if(in_array($eshop_hval, $Header))
                                                $ProHeader[] = $eshop_hkey;
                                        else
                                                $ProHeader[] = $eshop_hkey;
                                }
                        } 
			foreach($this->getACFvalues() as $acfKey => $acfVal) {
				if(!in_array($acfKey, $unwantedHeader)) {
					$ProHeader[] = $acfKey;
				}
			}
			foreach($this->getTypesFields() as $typeKey => $typeVal) {
				if(!in_array($typeKey, $unwantedHeader)) {
					$ProHeader[] = $typeVal;
				}
			}
			foreach($this->getAIOSEOfields() as $aioseokey => $aioseoval) {
				$ProHeader[] = $aioseoval;
			}
			foreach($this->getYoastSEOfields() as $yoastseokey => $yoastseoval) {
                                $ProHeader[] = $yoastseoval;
                        }
			$ProHeader[] = 'featured_image';
			return $ProHeader;
		}
		$Header[] = 'featured_image';
		return $Header;
	}

	/**
	 *
	 */
	public function get_all_record_ids($exporttype, $request) {
		global $wpdb;
		$post_type = $exporttype;
		$get_post_ids = "select DISTINCT ID from $wpdb->posts p join $wpdb->postmeta pm on pm.post_id = p.ID";
		if($post_type == 'woocommerce' || $post_type == 'marketpress') 
                        $post_type = 'product';
		if($post_type == 'wpcommerce')
			$post_type = 'wpsc-product';
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
		if($post_type == 'woocommerce')
			$get_post_ids .= " and pm.meta_key = '_sku'";
		if($post_type == 'marketpress')
			$get_post_ids .= " and pm.meta_key = 'mp_sku'";
		if($post_type == 'wpcommerce')
                        $get_post_ids .= " and pm.meta_key = '_wpsc_sku'";

/*                if($exporttype == 'woocommerce') {
                        $post_type = 'product';
			$get_post_ids = "select DISTINCT ID from $wpdb->posts p join $wpdb->postmeta pm on pm.post_id = p.ID where post_type = '$post_type' and post_status in ('publish','draft','future','private','pending') and pm.meta_key = '_sku'";
		} */
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
	public function getTypesFields() {
		$wptypesfields = get_option('wpcf-fields'); 
		#print('<pre>'); print_r($wptypesfields);
		foreach($wptypesfields as $typeFkey){
			$typesfields[$typeFkey['meta_key']] = $typeFkey['name'];
		}
		#print_r($typesfields);
		return $typesfields;
	}

	/**
	 *
	 */
	public function getACFvalues($getspecificfieldtype = null) {
		global $wpdb;
                // Code for ACF fields 
                $get_acf_fields = $wpdb->get_col ( "SELECT meta_value FROM $wpdb->postmeta
                                GROUP BY meta_key
                                HAVING meta_key LIKE 'field_%'
                                ORDER BY meta_key" );

                foreach ( $get_acf_fields as $acf_value ){
                        $get_acf_field = unserialize($acf_value);
                        $acf_fields[$get_acf_field['name']] = "CF: ".$get_acf_field['name'];
                        $acf_fields_slug[$get_acf_field['name']] = "_".$get_acf_field['name'];

                        if($get_acf_field['type'] == 'checkbox'){
                                $checkbox_option_fields[] = $get_acf_field['name'];
                        }
                } // Code ends here
		if($getspecificfieldtype == 'checkbox')
			return $checkbox_option_fields;
		else
			return $acf_fields;
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
        public function getYoastSEOfields () {
		$yoastseofields = array('_yoast_wpseo_focuskw'	=> 'focus_keyword',
				'_yoast_wpseo_title'	=> 'title',
				'_yoast_wpseo_metadesc'	=> 'meta_desc',
				'_yoast_wpseo_meta-robots-noindex' => 'meta-robots-noindex',
				'_yoast_wpseo_meta-robots-nofollow' => 'meta-robots-nofollow',
				'_yoast_wpseo_meta-robots-adv'	=> 'meta-robots-adv',
				'_yoast_wpseo_sitemap-include'	=> 'sitemap-include',
				'_yoast_wpseo_sitemap-prio'	=> 'sitemap-prio',
				'_yoast_wpseo_canonical'	=> 'canonical',
				'_yoast_wpseo_redirect'		=> 'redirect',
				'_yoast_wpseo_opengraph-description' =>	'opengraph-description',
				'_yoast_wpseo_google-plus-description'	=> 'google-plus-description',
			);
		return $yoastseofields;
	}

	/**
	 *
	 */
	public function getAllTerms($postID, $type) {
			// Tags & Categories
			if($type == 'woocommerce' || $type == 'marketpress') {
				$exporttype = 'product';
				$postTags = $postCategory = '';
				$taxonomies = get_object_taxonomies($exporttype);
				$get_tags = get_the_terms( $postID, 'product_tag' );
				if($get_tags){
					foreach($get_tags as $tags){
						$postTags .= $tags->name.',';
					}
				}
				$postTags = substr($postTags,0,-1);
				$TermsData['product_tag'] = $postTags;
				foreach ($taxonomies as $taxonomy) {
					if($taxonomy == 'product_cat' || $taxonomy == 'product_category'){
						$get_categotries =wp_get_post_terms( $postID, $taxonomy ); 
						if($get_categotries){
							foreach($get_categotries as $category){
								$postCategory .= $category->name.'|';
							}
						}
						$postCategory = substr($postCategory, 0 , -1);
						$TermsData['product_category'] = $postCategory;
					}
				}
			} else if($type == 'wpcommerce') {
				$exporttype = 'wpsc-product';
				$postTags = $postCategory = '';
                                $taxonomies = get_object_taxonomies($exporttype);
                                $get_tags = get_the_terms( $postID, 'product_tag' );
                                if($get_tags){
                                        foreach($get_tags as $tags){
                                                $postTags .= $tags->name.',';
                                        }
                                }
                                $postTags = substr($postTags,0,-1);
                                $TermsData['product_tag'] = $postTags;
                                foreach ($taxonomies as $taxonomy) {
                                        if($taxonomy == 'wpsc_product_category'){
                                                $get_categotries =wp_get_post_terms( $postID, $taxonomy );
                                                if($get_categotries){
                                                        foreach($get_categotries as $category){
                                                                $postCategory .= $category->name.'|';
                                                        }
                                                }
                                                $postCategory = substr($postCategory, 0 , -1);
                                                $TermsData['product_category'] = $postCategory;
                                        }
                                }
			} else {
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
			}
		#print('<pre>'); print_r($TermsData); die;
		return $TermsData;
	}

	/**
	 *
	 */ 
	public function MarketPressHeaders() {
	        $marketpressHeaders = array('product_title' => 'post_title', 'product_content' => 'post_content', 'product_excerpt' => 'post_excerpt', 'product_publish_date' => 'post_date', 'product_slug' => 'post_name', 'product_status' => 'post_status', 'product_parent' => 'post_parent', 'comment_status' => 'open', 'ping_status' => 'open', 'variation' => 'mp_var_name', 'SKU' => 'mp_sku', 'regular_price' => 'mp_price', 'is_sale' => 'mp_is_sale', 'sale_price' => 'mp_sale_price', 'track_inventory' => 'mp_track_inventory', 'inventory' => 'mp_inventory', 'track_limit' => 'mp_track_limit', 'limit_per_order' => 'mp_limit', 'product_link' => 'mp_product_link', 'is_special_tax' => 'mp_is_special_tax', 'special_tax' => 'mp_special_tax', 'sales_count' => 'mp_sales_count', 'extra_shipping_cost' => 'mp_shipping', 'file_url' => 'mp_file', 'product_category' => 'post_category', 'product_tag' => 'post_tag', 'featured_image' => 'featured_image',);
		return $marketpressHeaders;
	}

	/**
	 *
	 */
	public function WpeCommerceHeaders() {
		$wpecommerceHeaders = array('post_date' => 'post_date', 'post_content' => 'post_content', 'post_title' => 'post_title', 'post_excerpt' => 'post_excerpt', 'post_name' => 'post_name', 'stock' => '_wpsc_stock', 'price' => '_wpsc_price', 'sale_price' => '_wpsc_special_price', 'SKU' => '_wpsc_sku', 'product_tags' => 'product_tag', 'product_category' => null, 'featured_image' => 'featured_image', 'custom_meta' => null, 'wpsc_is_donation' => '_wpsc_is_donation', 'notify_when_none_left' => 'notify_when_none_left', 'unpublish_when_none_left' => 'unpublish_when_none_left', 'taxable_amount' => 'wpec_taxes_taxable_amount', 'is_taxable' => 'wpec_taxes_taxable', 'external_link' => 'external_link', 'external_link_text' => 'external_link_text', 'external_link_target' => 'external_link_target', 'no_shipping' => 'no_shipping', 'weight' => 'weight', 'weight_unit' => 'weight_unit', 'height' => 'height', 'height_unit' => 'height_unit', 'width' => 'width', 'width_unit' => 'width_unit', 'length' => 'length', 'length_unit' => 'length_unit', 'dimension_unit' => null, 'shipping' => 'shipping', 'merchant_notes' => 'merchant_notes', 'enable_comments' => 'enable_comments', 'quantity_limited' => 'quantity_limited', 'special' => 'special', 'display_weight_as' => 'display_weight_as', 'state' => 'state', 'quantity' => 'quantity', 'table_price' => 'table_price', 'google_prohibited' => 'google_prohibited',);
		return $wpecommerceHeaders;
	}

	/**
	 *
	 */
	public function WoocommerceMetaHeaders() {
		$woocomHeaders = array('product_publish_date' => 'post_date', 'product_content' => 'post_content', 'product_name' => 'post_title', 'product_short_description' => 'post_excerpt', 'product_slug' => 'post_name', 'post_parent' => 0, 'product_category' => 'post_category', 'product_tag' => 'post_tag', 'post_type' 	=> null, 'product_type'  => '_product_type', 'product_shipping_class' => '_product_shipping_class', 'product_status' => 'post_status', 'visibility' => '_visibility', 'tax_status' => '_tax_status', 'product_attribute_name' => '_product_attribute_name', 'product_attribute_value' => '_product_attribute_value', 'product_attribute_visible' => '_product_attribute_visible', 'product_attribute_variation' => '_product_attribute_variation', 'featured_image' => null, 'product_attribute_taxonomy' => '_product_attribute_taxonomy', 'tax_class' => '_tax_class', 'file_paths' => '_file_paths', 'comment_count' => null, 'menu_order' 	=> 0, 'comment_status'=> null, 'edit_last' => null, 'edit_lock' => null, 'thumbnail_id' => null, 'visibility' => '_visibility', 'stock_status' => '_stock_status', 'stock_qty' => '_stock', 'total_sales' => null, 'downloadable' => 'downloadable', 'downloadable_files' => '_downloadable_files', 'virtual' => '_virtual', 'regular_price' => '_regular_price', 'sale_price' => '_sale_price', 'purchase_note' => null, 'featured_product' => '_featured', 'weight' => null, 'length' => null, 'width' => null, 'height' => null, 'sku' => '_sku', 'upsell_ids' => '_upsell_ids', 'crosssell_ids' => '_crosssell_ids', 'sale_price_dates_from' => '_sale_price_dates_from', 'sale_price_dates_to' => '_sale_price_dates_to', 'price' => null,'sold_individually' => '_price', 'manage_stock' => '_manage_stock', 'backorders' => '_backorders', 'product_image_gallery' => '__product_image_gallery', 'product_url' => '_product_url', 'button_text' => '_button_text', 'downloadable_files' => null, 'download_limit' => '_download_limit', 'download_expiry' => '_download_expiry', 'download_type'=> null, 'min_variation_price' => null, 'max_variation_price'=> null, 'min_price_variation_id' => null, 'max_price_variation_id' => null, 'min_variation_regular_price' => null, 'max_variation_regular_price' => null, 'min_regular_price_variation_id' => null, 'max_regular_price_variation_id' => null, 'min_variation_sale_price' => null, 'max_variation_sale_price' => null, 'min_sale_price_variation_id' => null, 'max_sale_price_variation_id' => null, 'default_attributes' => null,);
		return $woocomHeaders;
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
	public function WPImpPROExportData($request) {
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
							// ACF checkbox fields
							$acfserialized = 0;
							if(array_key_exists($postmeta->meta_key, $this->getACFvalues())) {
								foreach($possible_values as $posval){
									if(strpos($postmeta->meta_value,$posval)){
										$acfserialized = 1;
									} else {
										$acfserialized = 0;
									}
								}
								if($acfserialized == 0) {
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = $postmeta->meta_value;
								} else {
									$acf_checkboxes = $this->getACFvalues('checkbox');
									if(in_array($postmeta->meta_key, $acf_checkboxes)) {
										$get_all_values = unserialize($postmeta->meta_value);
										foreach($get_all_values as $optKey => $optVal) {
											$PostMetaData[$postmeta->post_id][$postmeta->meta_key] .= $optVal . ',';
										}
										$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = substr($PostMetaData[$postmeta->post_id][$postmeta->meta_key], 0, -1);
									}
								}
							}
							// ACF checkbox fields ends here
							// WooCommerce product meta datas
							else if ($postmeta->meta_key == '_product_attributes') {
#print_r($postmeta->meta_value); #die;
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = '';
								$product_attribute_name = $product_attribute_value = $product_attribute_visible = $product_attribute_variation = '';
								$PostMetaData[$postmeta->post_id]['_product_attribute_name'] = '';
								$PostMetaData[$postmeta->post_id]['_product_attribute_value'] = '';
								$PostMetaData[$postmeta->post_id]['_product_attribute_visible'] = '';
								$PostMetaData[$postmeta->post_id]['_product_attribute_variation'] = '';
								$eshop_products_unser1 = unserialize($postmeta->meta_value); 
								$check_attr_count1 = count($eshop_products_unser1);
								if($check_attr_count1 == 1){
									$eshop_products_unser2 = unserialize($eshop_products_unser1); 
									$check_attr_count2 = count($eshop_products_unser2);
								}
								if($check_attr_count1 < $check_attr_count2){
									$unserialized_attributes = $eshop_products_unser2;
								}else{
									$unserialized_attributes = $eshop_products_unser1;
								}

								foreach ($unserialized_attributes as $key) {
									foreach($key as $attr_header => $attr_value){
										if($attr_header == 'name')
											$product_attribute_name .= $attr_value.'|';
										if($attr_header == 'value')
											$product_attribute_value .= $attr_value.'|';
										if($attr_header == 'is_visible')
											$product_attribute_visible .= $attr_value.'|';
										if($attr_header == 'is_variation'){
											if(isset($attr_value))
												$product_attribute_variation .= $attr_value.'|';
										}
									}
								}
								$PostMetaData[$postmeta->post_id]['_product_attribute_name'] = substr($product_attribute_name, 0, -1);
								$PostMetaData[$postmeta->post_id]['_product_attribute_value'] = substr($product_attribute_value, 0, -1);
								$PostMetaData[$postmeta->post_id]['_product_attribute_visible'] = substr($product_attribute_visible, 0, -1);
								$PostMetaData[$postmeta->post_id]['_product_attribute_variation'] = substr($product_attribute_variation, 0, -1);
#print('<pre>'); print_r($PostMetaData); die;
							}
							else if ($postmeta->meta_key == '_upsell_ids') {
        	                                                $upsellids = array();
	                                                        $crosssellids = array();
#print('<pre>'); print('VALUE for _upsell_ids: '); print_r($postmeta->meta_value); #print_r($PostMetaData); print('</pre>'); #die;
								if($postmeta->meta_value != '' && $postmeta->meta_value != null) {
									$upsellids = unserialize($postmeta->meta_value); 
									if(is_array($upsellids)){
										foreach($upsellids as $upsellID){
											$upsell_ids .= $upsellID.',';
										}
										$PostMetaData[$postmeta->post_id]['_upsell_ids'] = substr($upsell_ids, 0, -1);
									}else{
										$PostMetaData[$postmeta->post_id]['_upsell_ids'] = '';
									}
#print('<pre>'); print('VALUE for _upsell_ids: '); print_r($postmeta->meta_value); print_r($PostMetaData);
								}
							}
#print('<pre>'); print('VALUE for _upsell_ids: '); print_r($postmeta->meta_value); print_r($PostMetaData); print('</pre>'); #die;
							else if ($postmeta->meta_key == '_crosssell_ids') {
								if($postmeta->meta_value != '' && $postmeta->meta_value != null) {
									$crosssellids = unserialize($postmeta->meta_value);
									if(is_array($crosssellids)){
										foreach($crosssellids as $crosssellID){
											$crosssell_ids .= $crosssellID.',';
										}
										$PostMetaData[$postmeta->post_id]['_crosssell_ids'] = substr($crosssell_ids, 0, -1);
									}else{
										$PostMetaData[$postmeta->post_id]['_crosssell_ids'] = '';
									}
								}
							}
							else if ($postmeta->meta_key == '_downloadable_files') {
								if($postmeta->meta_value != '' && $postmeta->meta_value != null) {
									$downloadable_files = unserialize($postmeta->meta_value);
									if(is_array($downloadable_files)){
										foreach($downloadable_files as $dkey => $dval){
											$downloadable_key = $dkey;
											foreach($dval as $down_key => $down_val) { 
												$downloadable_value .= $down_val . ',';
											}
										} 
										$downloadable_all .= $downloadable_key . ',' . $downloadable_value;
										$PostMetaData[$postmeta->post_id]['_downloadable_files'] = substr($downloadable_all ,0, -1);
									}else{
										$PostMetaData[$postmeta->post_id]['_downloadable_files'] = '';
									}
								}
							} 
							else if ($postmeta->meta_key == '_thumbnail_id') {
								$attachment_file = '';
								$get_attachement = "select guid from $wpdb->posts where ID = $postmeta->meta_value AND post_type = 'attachment'";
								$attachment = $wpdb->get_results($get_attachement);
								$attachment_file = $attachment[0]->guid;
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = '';
								$postmeta->meta_key = 'featured_image';
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = $attachment_file;
							}
							else if ($postmeta->meta_key == '_visibility') {
								if($postmeta->meta_value == 'visible')
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 1;
								if($postmeta->meta_value == 'catalog')
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 2;
								if($postmeta->meta_value == 'search')
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 3;
								if($postmeta->meta_value == 'hidden')
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 4;
							}
							else if ($postmeta->meta_key == '_stock_status') {
								/*if($postmeta->meta_value == 'instock')
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 1;
								if($postmeta->meta_value == 'outofstock')
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 2;*/
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = $postmeta->meta_value;
							}
							else if ($postmeta->meta_key == '_tax_status') {
								if($postmeta->meta_value == 'taxable')
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 1;
								if($postmeta->meta_value == 'shipping')
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 2;
								if($postmeta->meta_value == 'none')
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 3;
							}
							else if ($postmeta->meta_key == '_tax_class') {
								if($postmeta->meta_value == '')
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 1;
								if($postmeta->meta_value == 'reduced-rate')
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 2;
								if($postmeta->meta_value == 'zero-rate')
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 3;
							}
							else if ($postmeta->meta_key == '_backorders') {
								if($postmeta->meta_value == 'no')
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 1;
								if($postmeta->meta_value == 'notify')
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 2;
								if($postmeta->meta_value == 'yes')
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = 3;
							}
							else if ($postmeta->meta_key == '_featured') {
								if($postmeta->meta_value == 'no')
									$PostMetaData[$postmeta->post_id]['featured_product'] = 1;
								if($postmeta->meta_value == 'yes')
									$PostMetaData[$postmeta->post_id]['featured_product'] = 2;
								if($postmeta->meta_value == 'zero-rate')
									$PostMetaData[$postmeta->post_id]['featured_product'] = 3;
							}
							else if ($postmeta->meta_key == '_product_type') {
								if($postmeta->meta_value == 'simple')
									$PostMetaData[$postmeta->post_id]['product_type'] = 1;
								if($postmeta->meta_value == 'grouped')
									$PostMetaData[$postmeta->post_id]['product_type'] = 2;
								if($postmeta->meta_value == 'variable')
									$PostMetaData[$postmeta->post_id]['product_type'] = 4;
							}
							/* else if($postmeta->meta_key != '_eshop_product' && $postmeta->meta_key != '_thumbnail_id') {
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = $postmeta->meta_value;
							} */
							#$eshop_products = $postmeta->meta_value;
							else if ($postmeta->meta_key == 'products') {
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = '';
								$eshop_products = unserialize($eshop_products);
								foreach ($eshop_products as $key) {
									$PostMetaData[$postmeta->post_id][$postmeta->meta_key] .= $key['option'] . '|' . $key['price'] . '|' . $key['saleprice'] . ',';
								}
								$PostMetaData[$postmeta->post_id][$postmeta->meta_key] = substr($PostMetaData[$postmeta->post_id][$postmeta->meta_key], 0, -1);
							} // WooCommerce product meta datas end here
							// MarketPress product meta datas starts here
							else if ($postmeta->meta_key == 'mp_var_name') {
								$mp_variations = null;
                                                		$all_variations = unserialize($postmeta->meta_value);
								foreach($all_variations as $variation_name) {
									$mp_variations .= $variation_name . ',';
								}
								$mp_variations = substr($mp_variations, 0, -1);
                                			        $PostMetaData[$postmeta->post_id][$postmeta->meta_key] = $mp_variations;
							}
							else if ($postmeta->meta_key == 'mp_sale_price') {
								$mp_sale_prices = null;
                                                                $all_sale_prices = unserialize($postmeta->meta_value);
                                                                foreach($all_sale_prices as $mp_sale_price_value) {
                                                                        $mp_sale_prices .= $mp_sale_price_value . ',';
                                                                }
                                                                $mp_sale_prices = substr($mp_sale_prices, 0, -1);
                                                                $PostMetaData[$postmeta->post_id][$postmeta->meta_key] = $mp_sale_prices;
							}
                                                        else if ($postmeta->meta_key == 'mp_price') {
								$mp_prod_prices = null;
                                                                $all_mp_prod_prices = unserialize($postmeta->meta_value);
                                                                foreach($all_mp_prod_prices as $mp_prod_price_value) {
                                                                        $mp_prod_prices .= $mp_prod_price_value . ',';
                                                                }
                                                                $mp_prod_prices = substr($mp_prod_prices, 0, -1);
                                                                $PostMetaData[$postmeta->post_id][$postmeta->meta_key] = $mp_prod_prices;
                                                        }
							else if ($postmeta->meta_key == 'mp_sku') {
								$mp_sku = null;
                                                                $all_mp_prod_sku = unserialize($postmeta->meta_value);
                                                                foreach($all_mp_prod_sku as $mp_prod_sku) {
                                                                        $mp_sku .= $mp_prod_sku. ',';
                                                                }
                                                                $mp_sku = substr($mp_sku, 0, -1);
                                                                $PostMetaData[$postmeta->post_id][$postmeta->meta_key] = $mp_sku;
                                                        }
							else if ($postmeta->meta_key == 'mp_shipping') {
                                                                $mp_prod_shipping_value = unserialize($postmeta->meta_value);
                                                                $mp_shipping_value = $mp_prod_shipping_value['extra_cost'];
                                                                $PostMetaData[$postmeta->post_id][$postmeta->meta_key] = $mp_shipping_value;
                                                        }
                                                        else if ($postmeta->meta_key == 'mp_inventory') {
								$mp_inventory_value = null;
                                                                $mp_prod_inventory_values = unserialize($postmeta->meta_value);
                                                                foreach($mp_prod_inventory_values as $inventory_values) {
                                                                        $mp_inventory_value .= $inventory_values. ',';
                                                                }
                                                                $mp_inventory_value = substr($mp_inventory_value, 0, -1);
                                                                $PostMetaData[$postmeta->post_id][$postmeta->meta_key] = $mp_inventory_value;
                                                        } // MarketPress product meta datas ends here
							// WP e-Commerce product meta datas starts here
							else if ($postmeta->meta_key == '_wpsc_product_metadata') {
								$wpecomm_product_metadata = unserialize($postmeta->meta_value);
#print('<pre>'); print_r($wpecomm_product_metadata); die;
								foreach($wpecomm_product_metadata as $prod_md_key => $prod_md_val) {
									if($prod_md_key == 'dimensions') { #die('summa');
										foreach($prod_md_val as $prod_dimen_key => $prod_dimen_val) {
											$PostMetaData[$postmeta->post_id][$prod_dimen_key] = $prod_dimen_val;
										}
									}
									else if($prod_md_key == 'shipping') {
										$shipping = null;
										foreach($prod_md_val as $prod_ship_key => $prod_ship_val) {
                                                                                        $shipping .= $prod_ship_val . ',';
                                                                                }
										$shipping = substr($shipping, 0, -1);
										$PostMetaData[$postmeta->post_id][$prod_md_key] = $shipping;
									}
									else if($prod_md_key == 'table_rate_price') {
										foreach($prod_md_val as $table_rate_key => $table_rate_val) {
											if($table_rate_key == 'quantity') {
												$trq_val = null;
												foreach($table_rate_val as $trq) {
													$trq_val .= $trq . '|';
												}
												$trq_val = substr($trq_val, 0, -1);
												$PostMetaData[$postmeta->post_id][$table_rate_key] = $trq_val;
											} else if($table_rate_key == 'table_price') {
												$tbl_price_amt = null;
												foreach($table_rate_val as $tbl_price) {
													$tbl_price_amt .= $tbl_price . '|';
												}
												$tbl_price_amt = substr($tbl_price_amt, 0, -1);
												$PostMetaData[$postmeta->post_id][$table_rate_key] = $tbl_price_amt;
											} else {
												$PostMetaData[$postmeta->post_id][$table_rate_key] = $table_rate_val;
											}
										}
									} 
									else {
										$PostMetaData[$postmeta->post_id][$prod_md_key] = $prod_md_val;
									}
								}
							}
							// Wp e-Commerce product meta datas ends here
							// Eshop product meta datas starts here
							else if ($postmeta->meta_key == 'featured') {
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
/*				
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
*/
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
			if($exporttype == 'woocommerce') {
				foreach($Header as $hkey) {
					foreach($ExportData as $edkey => $edval) { 
						/*if(array_key_exists($hkey, $this->getACFvalues())) {
							$ExportData[$edkey][$hkey] = $edval[$hkey];
							unset($ExportData[$edkey]['_'.$hkey]);
						}
						else if(in_array($hkey, $this->getAIOSEOfields())) { 
							foreach($this->getAIOSEOfields() as $aioseokey => $aioseoval) {
								$ExportData[$edkey][$aioseoval] = $edval[$aioseokey];
								unset($ExportData[$edkey][$hkey]);
							}
						}
						else if(array_key_exists('_'.$hkey, $edval)) {
							$ExportData[$edkey][$hkey] = $edval['_'.$hkey];
							unset($ExportData[$edkey]['_'.$hkey]);
						} */
						foreach($this->WoocommerceMetaHeaders() as $woohkey => $woohval) {
							if(array_key_exists($woohval, $ExportData[$edkey])) { 
								$ExportData[$edkey][$woohkey] = $edval[$woohval];
								unset($ExportData[$edkey][$woohval]);
							}
						}
					}
				}
			}
#			MarketPressHeaders
                        if($exporttype == 'marketpress') {
                                foreach($Header as $hkey) {
                                        foreach($ExportData as $edkey => $edval) {
                                                foreach($this->MarketPressHeaders() as $mphkey => $mphval) {
                                                        if(array_key_exists($mphval, $ExportData[$edkey])) {
                                                                $ExportData[$edkey][$mphkey] = $edval[$mphval];
//                                                                unset($ExportData[$edkey][$mphval]);
                                                        }
                                                }
                                        }
                                }
                        }
                        if($exporttype == 'wpcommerce') {
                                foreach($Header as $hkey) {
                                        foreach($ExportData as $edkey => $edval) {
                                                foreach($this->WpeCommerceHeaders() as $wpcomhkey => $wpcomhval) {
                                                        if(array_key_exists($wpcomhval, $ExportData[$edkey])) {
                                                                $ExportData[$edkey][$wpcomhkey] = $edval[$wpcomhval];
//                                                                unset($ExportData[$edkey][$wpcomhval]);
                                                        }
                                                }
                                        }
                                }
                        }
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
						} else if (in_array($header_key, $this->getYoastSEOfields())) {
							foreach($this->getYoastSEOfields() as $yoastseokey => $yoastseoval) {
								$CSVContent[$ED_key][$yoastseoval] = $ED_val[$yoastseokey];
								#unset($CSVContent[$ED_key][$header_key]);
							}
						} else if (in_array($header_key, $this->getTypesFields())) {
							foreach($this->getTypesFields() as $typesFkey => $typesFval) {
								$CSVContent[$ED_key][$typesFval] = $ED_val[$typesFkey];
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
			$csv = new WPImpCSVParserLib();
			$csv->output ($csv_file_name, $CSVContent, $Header, $export_delimiter);	
	}
		
	/**
	 *
	 */
	public function WPImpExportCategories($request) {
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

		$Header[] = 'name';
		$Header[] = 'slug';
		$Header[] = 'description';
		$get_all_categories = get_categories('hide_empty=0');
		$fieldsCount = count($get_all_categories);
		foreach( $get_all_categories as $cateKey => $cateValue ) {
			$categID = $cateValue->term_id;
			$categName = $cateValue->cat_name;
			$categSlug = $cateValue->slug;
			$categDesc = $cateValue->category_description;
			$categParent = $cateValue->parent;
			if($categParent == 0) {
				$TERM_DATA[$categID]['name'] = $categName;
			} else {
				$categParentName = get_cat_name( $categParent );
				$TERM_DATA[$categID]['name'] = $categParentName . '|' . $categName;
			}
			$TERM_DATA[$categID]['slug'] = $categSlug;
			$TERM_DATA[$categID]['description'] = $categDesc;
		}
		$csv = new WPImpCSVParserLib();
		$csv->output ($csv_file_name, $TERM_DATA, $Header, $export_delimiter);
	}

	/**
	 *
	 */
	public function WPImpExportTags($request) {
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

		$Header[] = 'name';
		$Header[] = 'slug';
		$Header[] = 'description';
		$get_all_tags = get_tags('hide_empty=0');
		$fieldsCount = count($get_all_tags);
		foreach( $get_all_tags as $tagKey => $tagValue ) {
			$tagID = $tagValue->term_id;
			$tagName = $tagValue->name;
			$tagSlug = $tagValue->slug;
			$tagDesc = $tagValue->description;
			$TERM_DATA[$tagID]['name'] = $tagName;
			$TERM_DATA[$tagID]['slug'] = $tagSlug;
			$TERM_DATA[$tagID]['description'] = $tagDesc;
		}
		$csv = new WPImpCSVParserLib();
		$csv->output ($csv_file_name, $TERM_DATA, $Header, $export_delimiter);
	}

	/**
	 *
	 */
	public function WPImpExportTaxonomies($request) {
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

		$taxonomy_name = $_POST['export_taxo_type'];
		$taxonomies = get_terms( $taxonomy_name, 'orderby=count&hide_empty=0' );
		$Header[] = 'name';
		$Header[] = 'slug';
		$Header[] = 'description';
		$fieldsCount = count($taxonomies);
		foreach( $taxonomies as $taxoKey => $taxoValue ) {
			$taxoID = $taxoValue->term_id;
			$taxoName = $taxoValue->name;
			$taxoSlug = $taxoValue->slug;
			$taxoDesc = $taxoValue->description;
			$taxoParent = $taxoValue->parent;
			if($taxoParent == 0) {
				$TERM_DATA[$taxoID]['name'] = $taxoName;
			} else {
				$taxoParentName = get_term( $taxoParent, $taxonomy_name );
				$TERM_DATA[$taxoID]['name'] = $taxoParentName->name . '|' . $taxoName;
			}
			$TERM_DATA[$taxoID]['slug'] = $taxoSlug;
			$TERM_DATA[$taxoID]['description'] = $taxoDesc;
		}
		$csv = new WPImpCSVParserLib();
		$csv->output ($csv_file_name, $TERM_DATA, $Header, $export_delimiter);
	}

	/**
	 *
	 */
	public function WPImpExportCustomerReviews($request) {
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

		$header_query1 = "SELECT * FROM  wp_wpcreviews";
		$header_query2 = "SELECT id FROM  wp_wpcreviews";

		$result_header_query1 = $wpdb->get_results($header_query1);
		foreach($result_header_query1 as $rhq1_key){
			foreach($rhq1_key as $rhq1_headkey => $rhq1_headval){
				if(!in_array($rhq1_headkey,$Header))
					$Header[] = $rhq1_headkey;
			}
		}

		$result = $wpdb->get_results($header_query1);
		$result1 = $wpdb->get_results($header_query2);
		$fieldsCount = count($result);
		if ($result) {
			$i =0;
			foreach ($result as $postID) {
				foreach ($postID as $Key => $Val){
					$postData[$i][$Key] = $Val;
				}
				$i++;
			}
			$ExportData = $postData;
			$j=0;
			foreach($Header as $key)
			{
				$key=$j;
				$header[] = $key;
				$j++;
			}

			$csv = new WPImpCSVParserLib();
			$csv->output ($csv_file_name, $ExportData, $Header, $export_delimiter);
		}
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
		$csv = new WPImpCSVParserLib();
		$csv->output ($csv_file_name, $ExportData, $Header, $export_delimiter);
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

	                $csv = new WPImpCSVParserLib();
        	        $csv->output ($csv_file_name, $CSVContent, $UserHeader, $export_delimiter);
		}
}
