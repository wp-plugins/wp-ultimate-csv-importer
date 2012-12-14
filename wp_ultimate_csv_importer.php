<?php
/*
*Plugin Name: Wp Ultimate CSV Importer
*Plugin URI: http://www.smackcoders.com/category/free-wordpress-plugins.html
*Description: A plugin that helps to import the data's from a CSV file.
*Version: 1.1.1
*Author: smackcoders.com
*Author URI: http://www.smackcoders.com
*
* Copyright (C) 2012 Fredrick SujinDoss.M (email : fredrickm@smackcoders.com)
*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*
* @link http://www.smackcoders.com/category/free-wordpress-plugins.html

***********************************************************************************************
*/

@ini_set( 'display_errors', false );

// Global variable declaration
global $data_rows;
$data_rows = array();
global $headers ;
$headers = array();
global $defaults;
global $wpdb;
global $keys;
global $delim;
$delim = $_POST['delim'];
// Get the custom fields
$limit = (int) apply_filters( 'postmeta_form_limit', 30 );
$keys = $wpdb->get_col( "
        SELECT meta_key
        FROM $wpdb->postmeta
        GROUP BY meta_key
        HAVING meta_key NOT LIKE '\_%'
        ORDER BY meta_key
        LIMIT $limit" );
// Default header array
// Code modified at version 1.1.1 by fredrick
$defaults = array(
        'post_title'      => null,
        'post_content'    => null,
        'post_excerpt'    => null,
        'post_date'       => null,
        'post_tag'        => null,
        'post_category'	  => null,
        'post_author'     => null,
	'featured_image'  => null,
        'post_parent'     => 0,
    );
foreach($keys as $val){
	$defaults[$val]=$val;
}
// Admin menu settings
function wp_ultimate_csv_importer() {  
	add_menu_page('CSV importer settings', 'Wp Ultimate CSV Importer', 'manage_options',  
	       'upload_csv_file', 'upload_csv_file');
}  

add_action("admin_menu", "wp_ultimate_csv_importer");  
wp_enqueue_script("upload_csv_file", site_url()."/wp-content/plugins/wp-ultimate-csv-importer/wp_ultimate_csv_importer.js", array("jquery"));

// Plugin description details
function description(){
	$string = "<p>Wp Ultimate CSV Importer Plugin helps you to manage the post,page and </br> custom post data's from a CSV file.</p> 
<p>
1. Admin can import the data's from any csv file.
</p>
<p>
2. Can define the type of post and post status while importing.
</p>
<p>
3. Provides header mapping feature to import the data's as your need.
</p>
<p>
4. Users can map coloumn headers to exixting fields or assign as custom fileds.
</p>
<p>
5. Import unlimited datas as post.
</p>
<p>
6. Make imported post as published or make it as draft.
</p>
<p>Configuring our plugin is as simple as that. If you have any questions, issues and request on new features, plaese visit <a href='http://www.smackcoders.com/category/free-wordpress-plugins.html' target='_blank'>Smackcoders.com Blog </a></p>

	<div align='center' style='margin-top:40px;'> 'While the scripts on this site are free, donations are greatly appreciated. '<br/><br/><a href='http://www.smackcoders.com/donate.html' target='_blank'><img src='".site_url()."/wp-content/plugins/wp-ultimate-csv-importer/images/paypal_donate_button.png' /></a><br/><br/><a href='http://www.smackcoders.com/' target='_blank'><img src='http://www.smackcoders.com/wp-content/uploads/2012/09/Smack_poweredby_200.png'></a>
	</div><br/>";
	return $string;
}

// CSV File Reader
function csv_file_data($file,$delim)
{
	ini_set("auto_detect_line_endings", true);
	global $data_rows;
	global $headers;
	global $delim;
        $resource = fopen($file, 'r');
        while ($keys = fgetcsv($resource,'',"$delim",'"')) {
            if ($c == 0) {
                $headers = $keys;
            } else {
                array_push($data_rows, $keys);
            }
            $c ++;
        }
        fclose($resource);
	ini_set("auto_detect_line_endings", false);
}

// Move file
function move_file()
{
$uploads_dir = getcwd ().'/../wp-content/plugins/wp-ultimate-csv-importer/imported_csv';
    if ($_FILES["csv_import"]["error"] == 0) {
        $tmp_name = $_FILES["csv_import"]["tmp_name"];
        $name = $_FILES["csv_import"]["name"];
        move_uploaded_file($tmp_name, "$uploads_dir/$name");
    }
}

// Remove file
function fileDelete($filepath,$filename) {
	$success = FALSE;
	if (file_exists($filepath.$filename)&&$filename!=""&&$filename!="n/a") {
		unlink ($filepath.$filename);
		$success = TRUE;
	}
	return $success;	
}

// Mapping the fields and upload data's
function upload_csv_file()
{
	global $headers;
	global $data_rows;
	global $defaults;
	global $keys;
	global $custom_array;
	global $delim;
	$custom_array = array();
	if(isset($_POST['Import']))
	{
		csv_file_data($_FILES['csv_import']['tmp_name'],$delim);
		move_file();
		?>
	<div style="background-color: #FFFFE0;border-color: #E6DB55;border-radius: 3px 3px 3px 3px;border-style: solid;border-width: 1px;margin: 5px 15px 2px; margin-top:15px;padding: 5px;text-align:center"> Please check out <a href="http://smackcoders.com/category/free-wordpress-plugins.html" target="_blank">www.smackcoders.com</a> for the latest news and details of other great plugins and tools. </div><br/>
		<?php if ( count($headers)>=1 &&  count($data_rows)>=1 ){?>
		<div style="float:left;min-width:45%">
		<form class="add:the-list: validate" method="post" onsubmit="return import_csv();">
			<h3>Import Data Configuration</h3>
			<div style="margin-top:30px;>
			<input name="_csv_importer_import_as_draft" type="hidden" value="publish" />
			 <label><input name="csv_importer_import_as_draft" type="checkbox" <?php if ('draft' == $opt_draft) { echo 'checked="checked"'; } ?> value="draft" /> Import as drafts </label>&nbsp;&nbsp;
			</p>
			<label> Select Post Type </label>&nbsp;&nbsp;
			<select name='csv_importer_cat'>
				<?php
				$post_types=get_post_types();
				      foreach($post_types as $key => $value){
					if(($value!='featured_image') && ($value!='revision') && ($value!='nav_menu_item')){ ?>
					<option id="<?php echo($value);?>" name="<?php echo($value);?>"> <?php echo($value);?> </option>
				<?php   }
				      }
				?>
			<select>
			<br/></div><br/>
			<h3>Mapping the Fields</h3>
			<div id='display_area'>
			<?php $cnt =count($defaults)+2; $cnt1 =count($headers); ?>
			<input type="hidden" id="h1" name="h1" value="<?php echo $cnt; ?>"/>
			<input type="hidden" id="h2" name="h2" value="<?php echo $cnt1; ?>"/>
			<input type="hidden" id="delim" name="delim" value="<?php echo $_POST['delim']; ?>" />
			<input type="hidden" id="header_array" name="header_array" value="<?php print_r($headers);?>" />
			<table style="font-size:12px;">
			 <?php
			  $count = 0;
			  foreach($headers as $key=>$value){ 
			 ?>
			 <tr>
			    <td>
				<label><?php print($value);?></label>
			    </td>
			    <td>
			    <select  name="mapping<?php print($count);?>" id="mapping<?php print($count);?>" class ='uiButton' onchange="addcustomfield(this.value,<?php echo $count; ?>);">
				<option id="select" name="select">-- Select --</option>
			    <?php 
				  foreach($defaults as $key1=>$value1){
			    ?>
					<option value ="<?php print($key1);?>"><?php print($key1);?></option>
			    <?php }
		   	    ?>
				<option value="add_custom<?php print($count);?>">Add Custom Field</option>
			    </select>
			    <input type="text" id="textbox<?php print($count); ?>" name="textbox<?php print($count); ?>" style="display:none;"/>
			  </td>
			 </tr>
			 <?php
			   $count++; } 
			 ?>
			</table>
			</div><br/> 
			<input type='hidden' name='filename' id='filename' value="<?php echo($_FILES['csv_import']['name']);?>" />
			<input type='submit' name= 'post_csv' id='post_csv' value='Import' />
		</form>
		</div>
		<div style="min-width:45%;">
			<?php $result = description(); print_r($result); ?>
		</div>
	<?php
		}
		else { ?>
		<div style="font-size:16px;margin-left:20px;">Your CSV file cannot be processed. It may contains wrong delimiter or please choose the correct delimiter.
		</div><br/>
		<div style="margin-left:20px;">
		<form class="add:the-list: validate" method="post" action="">
			<input type="submit" class="button" name="Import Again" value="Import Again"/>
		</form>
		</div>
		<div style="margin-left:20px;margin-top:30px;">
			<b>Note :-</b>
			<p>1. Your CSV should contain "," or ";" as delimiters.</p>
			<p>2. In CSV, tags should be seperated by "," to import mutiple tags and categories should be seperated by "|" to import multiple categories.</p>
		</div>
	<?php	}
	}
	else if(isset($_POST['post_csv']))
	{
		$dir = getcwd ().'/../wp-content/plugins/wp-ultimate-csv-importer/imported_csv/';
		csv_file_data($dir.$_POST['filename'],$delim);
		foreach($_POST as $postkey=>$postvalue){
			if($postvalue != '-- Select --'){
				$ret_array[$postkey]=$postvalue;
			}
		}
		foreach($data_rows as $key => $value){
			for($i=0;$i<count($value) ; $i++)
			{
				if(array_key_exists('mapping'.$i,$ret_array)){
					if($ret_array['mapping'.$i]!='add_custom'.$i){
						$new_post[$ret_array['mapping'.$i]] = $value[$i];
					}
					else{
						$new_post[$ret_array['textbox'.$i]] = $value[$i];
						$custom_array[$ret_array['textbox'.$i]] = $value[$i];
					}
				}
			}
			for($inc=0;$inc<count($value);$inc++){
			   foreach($keys as $k => $v){
			     if(array_key_exists($v,$new_post)){
				$custom_array[$v] =$new_post[$v];
			     }
			   }
			}
			foreach($new_post as $ckey => $cval){
			   if($ckey!='post_category' && $ckey!='post_tag' && $ckey!='featured_image'){ // Code modified at version 1.0.2 by fredrick
				if(array_key_exists($ckey,$custom_array)){
					$darray[$ckey]=$new_post[$ckey];
				}
				else{
					$data_array[$ckey]=$new_post[$ckey];
				}
			   }
			   else{
				if($ckey == 'post_tag'){
					$tags[$ckey]=$new_post[$ckey];
				}
				if($ckey == 'post_category'){
					$categories[$ckey]=$new_post[$ckey];
				}
				if($ckey == 'featured_image'){ // Code added at version 1.1.0 by fredrick
					$file_url=$filetype[$ckey]=$new_post[$ckey];
					$file_type = explode('.',$filetype[$ckey]);
					$count = count($file_type);
					$type= $file_type[$count-1];
					if($type == 'png'){
						$file['post_mime_type']='image/png';
					}
					else if($type == 'jpg'){
						$file['post_mime_type']='image/jpeg';
					}
					else if($type == 'gif'){
						$file['post_mime_type']='image/gif';
					}
					$img_name = explode('/',$file_url);
					$imgurl_split = count($img_name);
					$img_name = explode('.',$img_name[$imgurl_split-1]);
					$img_title = $img_name = $img_name[0];
					$dir = wp_upload_dir(); 
					$dirname = 'featured_image';
					$full_path = $dir['basedir'].'/'.$dirname;
					$baseurl = $dir['baseurl'].'/'.$dirname;
					$filename = explode('/',$file_url);
					$file_split = count($filename);
					$filepath = $full_path.'/'.$filename[$file_split-1];
					$fileurl = $baseurl.'/'.$filename[$file_split-1];
					if(is_dir($full_path)){
						copy($file_url,$filepath);
					}
					else{
						wp_mkdir_p($full_path);
						copy($file_url,$filepath);
					}
					$file['guid']=$fileurl;
					$file['post_title']=$img_title;
					$file['post_content']='';
					$file['post_status']='inherit';
				}
			   }
			}
			$data_array['post_status']='publish';
			if(isset($_POST['csv_importer_import_as_draft'])){
				$data_array['post_status']='draft';
			}
			$data_array['post_type']=$_POST['csv_importer_cat'];
			$post_id = wp_insert_post( $data_array );
			if(!empty($custom_array)){
				foreach($custom_array as $custom_key => $custom_value){
					add_post_meta($post_id, $custom_key, $custom_value);
				}
			}

			// Create/Add tags to post
			if(!empty($tags)){
				foreach($tags as $tag_key => $tag_value){
					wp_set_post_tags( $post_id, $tag_value );
				}
			}  // End of code to add tags

			// Create/Add category to post
			if(!empty($categories)){
				$split_line = explode('|',$categories['post_category']);
				wp_set_object_terms($post_id, $split_line, 'category');

			}  // End of code to add category

			// Code added to import featured image at version 1.1.0 by fredrick
			if(!empty($file)){
				$file_name=$dirname.'/'.$img_title.'.'.$type;
				$attach_id = wp_insert_attachment($file, $file_name, $post_id);
				require_once(ABSPATH . 'wp-admin/includes/image.php');
				$attach_data = wp_generate_attachment_metadata( $attach_id, $fileurl );
				wp_update_attachment_metadata( $attach_id, $attach_data );
				//add_post_meta($post_id, '_thumbnail_id', $attach_id, true);
				set_post_thumbnail( $post_id, $attach_id );
			}
		}
	?>
		<div style="background-color: #FFFFE0;border-color: #E6DB55;border-radius: 3px 3px 3px 3px;border-style: solid;border-width: 1px;margin: 5px 15px 2px; padding: 5px;text-align:center"><b> Successfully Imported ! </b></div>
		<div style="margin-top:30px;margin-left:10px">
		    <form class="add:the-list: validate" method="post" enctype="multipart/form-data">
			<input type="submit" id="goto" name="goto" value="Continue" />
		    </form>
		</div>
	<?php 
// Code modified at version 1.1.1
	// Remove CSV file
$csvdir = getcwd ().'/../wp-content/plugins/wp-ultimate-csv-importer/imported_csv/';
$CSVfile = $_POST['filename'];
		if(file_exists($csvdir.$CSVfile)){
			chmod("$csvdir"."$CSVfile", 755);
			fileDelete($csvdir,$CSVfile); 
		}
	}
	else
	{
	?>
		<div class="wrap">
		     <div style="background-color: #FFFFE0;border-color: #E6DB55;border-radius: 3px 3px 3px 3px;border-style: solid;border-width: 1px;margin: 5px 15px 2px; margin-top:15px;padding: 5px;text-align:center"> Please check out <a href="http://smackcoders.com/category/free-wordpress-plugins.html" target="_blank">www.smackcoders.com</a> for the latest news and details of other great plugins and tools. </div><br/>
		     <div style="min-width:45%;float:left;height:500px;">
			<h2>Import CSV</h2>
			<form class="add:the-list: validate" method="post" enctype="multipart/form-data" onsubmit="return file_exist();">

			<!-- File input -->
			<p><label for="csv_import">Upload file:</label><br/>
			    <input name="csv_import" id="csv_import" type="file" value="" aria-required="true" /></p><br/>
			<p><label>Delimiter</label>&nbsp;&nbsp;&nbsp;
			    <select name="delim" id="delim">
				<option value=",">,</option>
				<option value=";">;</option>
			    </select>
			</p>
			<p class="submit"><input type="submit" class="button" name="Import" value="Import" /></p>
			</form>
		     </div>
		     <div style="min-width:45%;">
			<?php $result = description(); print_r($result); ?>
		     </div>
		</div><!-- end wrap -->
	<?php
	}
}

?>
