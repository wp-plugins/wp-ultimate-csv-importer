<?php
/*
*Plugin Name: WP Ultimate CSV Importer
*Plugin URI: http://www.smackcoders.com/blog/how-to-guide-for-free-wordpress-ultimate-csv-importer-plugin.html
*Description: A plugin that helps to import the data's from a CSV file.
*Version: 2.7.0
*Author: smackcoders.com
*Author URI: http://www.smackcoders.com
*
* Copyright (C) 2012 Smackcoders (www.smackcoders.com)
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
* @link http://www.smackcoders.com/blog/category/free-wordpress-plugins
***********************************************************************************************
*/

#Credits to Fredrick Marks
# php.ini overriding values for import a big csv file
ini_set('max_execution_time', 700);
ini_set('memory_limit', '128M');

require( dirname(__FILE__) . '/../../../wp-load.php' );

// Global variable declaration
global $data_rows;
$data_rows = array();
global $headers ;
$headers = array();
global $defaults;
global $wpdb;
global $keys;
global $delim;
global $contentUrl;
$contentUrl = WP_CONTENT_URL;
# Code added by goku 
$delim = empty($_POST['delim']) ? '' : $_POST['delim'];
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
$defaults = array(
        'post_title'      => null,
        'post_content'    => null,
        'post_excerpt'    => null,
        'post_date'       => null,
	'post_name'	  => null,
        'post_tag'        => null,
        'post_category'	  => null,
        'post_author'     => null,
	'featured_image'  => null,
        'post_parent'     => 0,
	'post_status'	  => 0,
    );
foreach($keys as $val){
	$defaults["CF: ".$val]=$val;
}
// Admin menu settings
function wp_ultimate_csv_importer() { 
	global $contentUrl;
	add_menu_page('CSV importer settings', 'WP Ultimate CSV Importer', 'manage_options',  
	       'upload_csv_file', 'upload_csv_file', $contentUrl."/plugins/wp-ultimate-csv-importer/images/icon.png");
}  

function LoadWpScript()
{
	global $contentUrl;
        wp_register_script('wp_ultimate_scripts', $contentUrl."/plugins/wp-ultimate-csv-importer/wp_ultimate_csv_importer.js", array("jquery"));
        wp_enqueue_script('wp_ultimate_scripts');
}
add_action('admin_enqueue_scripts', 'LoadWpScript');
add_action("admin_menu", "wp_ultimate_csv_importer");  

// Plugin description details
function description(){
	global $contentUrl;
	$string = "<p>WP Ultimate CSV Importer Plugin helps you to manage the post,page and </br> custom post data's from a CSV file.</p> 
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
<p>
7. Added featured image import functionality.
</p>
<p><b> Important Note:- </b></p><p><span style='color:red;'>1. Your csv should have the seperate column for post_date. <br/>2. It must be in the following format. ( yyyy-mm-dd hh:mm:ss ).</span></p>
<p>Configuring our plugin is as simple as that. If you have any questions, issues and request on new features, plaese visit <a href='http://www.smackcoders.com/blog/category/free-wordpress-plugins' target='_blank'>Smackcoders.com blog </a></p>

	<div align='center' style='margin-top:40px;'> 'While the scripts on this site are free, donations are greatly appreciated. '<br/><br/><a href='http://www.smackcoders.com/donate.html' target='_blank'><img src='".$contentUrl."/plugins/wp-ultimate-csv-importer/images/paypal_donate_button.png' /></a><br/><br/><a href='http://www.smackcoders.com/' target='_blank'><img src='http://www.smackcoders.com/wp-content/uploads/2012/09/Smack_poweredby_200.png'></a>
	</div><br/>";
	return $string;
}

// CSV File Reader
# Code modified by Fredrick Marks
function csv_file_data($file,$delim)
{
	$upload_dir = wp_upload_dir();
	if(!is_dir($upload_dir['basedir'])){
		$returnContent = " <div style='font-size:16px;margin-left:20px;margin-top:25px;'>Your WordPress doesn't have the uploads folder. Please create the uploads folders and set write permission for that.
			</div><br/>
			<div style='margin-left:20px;'>
			<form class='add:the-list: validate' method='post' action=''>
			<input type='submit' class='button-primary' name='Import Again' value='Import Again'/>
			</form>
			</div>
			";
		echo $returnContent;die;
	}
	else{
		$importdir  = $upload_dir['basedir']."/ultimate_importer/";
		if(!is_dir($importdir))
		{
			wp_mkdir_p($importdir);
		}
	}
	ini_set("auto_detect_line_endings", true);
	global $data_rows;
	global $headers;
	global $delim;
	# Code added by goku
        $c = 0;
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
    # Code added by goku
    $upload_dir = wp_upload_dir();
    $uploads_dir  = $upload_dir['basedir']."/ultimate_importer";
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
	global $wpdb;
	# Code added by goku
        $upload_dir = wp_upload_dir();
        $importdir  = $upload_dir['basedir']."/ultimate_importer/";
	$custom_array = array();
	if(isset($_POST['Import']))
	{ 
		$duplicatesDetection = $_POST['titleduplicatecheck'];
		csv_file_data($_FILES['csv_import']['tmp_name'],$delim);
		move_file();
		?>
	<br/>
	<marquee onmouseover="this.setAttribute('scrollamount', 0, 0);" onmouseout="this.setAttribute('scrollamount', 6, 0);" >Now the <a href="http://www.smackcoders.com/wp-ultimate-csv-importer-pro.html" target="_blank">Pro Version</a> is Available. For more details,please visit <a href="http://www.smackcoders.com/wp-ultimate-csv-importer-pro.html" target="_blank">here</a></marquee>
	<div style="background-color: #FFFFE0;border-color: #E6DB55;border-radius: 3px 3px 3px 3px;border-style: solid;border-width: 1px;margin: 5px 15px 2px; margin-top:15px;padding: 5px;text-align:center"> Please check out <a href="http://www.smackcoders.com/blog/category/free-wordpress-plugins" target="_blank">www.smackcoders.com</a> for the latest news and details of other great plugins and tools. </div><br/>
		<?php if ( count($headers)>=1 &&  count($data_rows)>=1 ){?>
		<div style="float:left;min-width:45%">
		<form class="add:the-list: validate" method="post" onsubmit="return import_csv();">
			<h3>Import Data Configuration</h3>
			<div style="margin-top:30px;>
			<input name="_csv_importer_import_as_draft" type="hidden" value="publish" />
			</p>
			<label> Select Post Type </label>&nbsp;&nbsp;
			<select name='csv_importer_cat'>
				<?php
				$post_types=get_post_types();
				      foreach($post_types as $key => $value){
					if(($value!='featured_image') && ($value!='attachment') && ($value!='wpsc-product') && ($value!='wpsc-product-file') && ($value!='revision') && ($value!='nav_menu_item')){ ?>
					<option id="<?php echo($value);?>" name="<?php echo($value);?>"> <?php echo($value);?> </option>
				<?php   }
				      }
				?>
			<select>
			<br/>
<br/>
<?php $cnt1 = count($headers); ?>

<label>Import with post status</label>&nbsp;&nbsp;
<select name = 'importallwithps' id= 'importallwithps' onchange = 'importAllPostStatus(this.value, "<?php echo $cnt1?>")'>
<option value = '0'>Status as in CSV</option>
<option value = '1'>Publish</option>
<option value = '2'>Sticky</option>
<option value = '3'>Protected</option>
<option value = '4'>Private</option>
<option value = '5'>Draft</option>
<option value = '6'>Pending</option>
</select>
</div><br/>
<span style='color:red;'id = 'poststatusalert'>post_status field must mapped below as in CSV</span>
<label style='display:none;' id = 'passwordlabel'>Posts Password</label>&nbsp;&nbsp;
<input type='text' style='display:none' id = 'postsPassword' name = 'postsPassword'>
			<h3>Mapping the Fields</h3>
			<div id='display_area'>
			<?php $cnt =count($defaults)+2;  ?>
			<input type="hidden" id="h1" name="h1" value="<?php echo $cnt; ?>"/>
			<input type="hidden" id="h2" name="h2" value="<?php echo $cnt1; ?>"/>
                        <input type="hidden" id="titleduplicatecheck" name = "titleduplicatecheck" value="<?php echo $_POST['titleduplicatecheck'] ?>" />
			<input type="hidden" id="contentduplicatecheck" name="contentduplicatecheck" value="<?php echo $_POST['contentduplicatecheck'] ?>" />
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
					<option value ="<?php print($key1);?>">
				<?php if($key1 != 'post_name')
				 print($key1);
			         else
				 print 'post_slug';?>
					</option>
				    <?php } $taxo = get_taxonomies();
				  foreach($taxo as $taxokey => $taxovalue){ 
					if($taxokey !='category' && $taxokey !='link_category' && $taxokey != 'post_tag' && $taxokey != 'nav_menu' && $taxokey != 'post_format'){ ?>
					<option value ="<?php print($taxokey);?>"><?php print($taxovalue);?></option>
			    <?php 	}
				  }
		   	    ?>
				<option value="add_custom<?php print($count);?>">Add Custom Field</option>
			    </select>
<!-- added to solve issue id 1072-->			    
<input type="text" id="textbox<?php print($count); ?>" name="textbox<?php print($count); ?>" style="display:none;" value="<?php echo $value ?>" />
<span style="display:none;color:red;margin-left:5px;" id="customspan<?php echo $count?>" >Name this Field</span>
		<select id="date<?php print($count); ?>" name="date<?php print($count); ?>" style="display:none;">
                          <option value="M/DD/YYYY">M/DD/YYYY</option>
                                <option value="M/DD/YYYY HH:MM">M/DD/YYYY HH:MM</option>
                                <option value="M/DD/YYYY HH:MM PM">M/DD/YYYY HH:MM PM</option>
                                <option value="MM/DD/YYYY">MM/DD/YYYY</option>
                                <option value="MM/DD/YYYY HH:MM">MM/DD/YYYY HH:MM</option>
                                <option value="MM/DD/YYYY HH:MM PM">MM/DD/YYYY HH:MM PM</option>
                                <option value="YYYY-MM-DD HH:MM:SS">YYYY-MM-DD HH:MM:SS</option>
                        </select>  
			</td>
			 </tr>
			 <?php
			   $count++; } 
			 ?>
			</table>
			</div><br/> 
			<input type='hidden' name='filename' id='filename' value="<?php echo($_FILES['csv_import']['name']);?>" />
			<input type='submit' class='button-primary' name= 'post_csv' id='post_csv' value='Import' />
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
			<input type="submit" class="button-primary" name="Import Again" value="Import Again"/>
		</form>
		</div>
		<div style="margin-left:20px;margin-top:30px;">
			<b>Note :-</b>
			<p>1. Your CSV should contain "," or ";" as delimiters.</p>
			<p>2. In CSV, tags should be seperated by "," to import mutiple tags and categories should be seperated by "|" to import multiple categories.</p>
		</div>
	<?php	}
	}
	else if(isset($_POST['post_csv'])){
		# added for issue id 1072
		for($d=0;$d<count($_POST);$d++){
		if($_POST['mapping'.$d] == 'post_date'){
		$dateformat = $_POST['date'.$d];		
		}
		}
		$insertedRecords = 0;
		$duplicates = 0;
		$wrongpostauthor = 0;
		$smack_taxo = array();
		# Code added by goku
        	$upload_dir = wp_upload_dir();
	        $dir  = $upload_dir['basedir']."/ultimate_importer/";
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
                            $taxo = get_taxonomies();
                            foreach($taxo as $taxokey => $taxovalue){
 	                           if($taxokey !='category' && $taxokey !='link_category' && $taxokey != 'post_tag' && $taxokey != 'nav_menu' && $taxokey != 'post_format'){
        	                           if($taxokey == $ckey){
                	                           $smack_taxo[$ckey] = $new_post[$ckey];
                                           }
                                   }
                            }
			   if($ckey!='post_category' && $ckey!='post_tag' && $ckey!='featured_image' && $ckey!= $smack_taxo[$ckey]){ // Code modified at version 2.5.0 by Fredrick Marks
				if(array_key_exists($ckey,$custom_array)){
					$darray[$ckey]=$new_post[$ckey];
				}
				else{
					if(array_key_exists($ckey,$smack_taxo)){

					}
					else{
						$data_array[$ckey]=$new_post[$ckey];
					}
				}
			   }
			   else{
				if($ckey == 'post_tag'){
					$tags[$ckey]=$new_post[$ckey];
				}
				if($ckey == 'post_category'){
					$categories[$ckey]=$new_post[$ckey];
				}
				if($ckey == 'featured_image'){ 
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
						$smack_fileCopy = copy($file_url,$filepath);
					}
					else{
						wp_mkdir_p($full_path);
						$smack_fileCopy = copy($file_url,$filepath);
					}
					if($smack_fileCopy){
						$file['guid']=$fileurl;
						$file['post_title']=$img_title;
						$file['post_content']='';
						$file['post_status']='inherit';
					}
					else{
						$file = false;
					}
				}
			   }
			}
			#code added for issue 1087
			$sticky = false;
			if($_POST['importallwithps'] == 0){
				unset($data_array['post_password']);
				if(($data_array['post_status'] == 1)||($data_array['post_status'] == 'Publish'))
					$data_array['post_status'] = 'publish';
				elseif(($data_array['post_status'] == 4)||($data_array['post_status'] == 'Draft'))
					$data_array['post_status'] = 'draft';
				elseif(($data_array['post_status'] == '3')||($data_array['post_status'] == 'Private'))
					$data_array['post_status'] = 'private';
				elseif(($data_array['post_status'] == 5)||($data_array['post_status'] == 'Pending'))
					$data_array['post_status'] = 'pending';
				elseif(($data_array['post_status'] == 2)||($data_array['post_status'] == 'Sticky')){
					$data_array['post_status'] = 'publish';
					$sticky = true;
					}
				elseif(($data_array['post_status'] == ''))
					$data_array['post_status'] = 'publish';
				else {
					if(strlen($data_array['post_status']) <= 10)
						$data_array['post_password'] = $data_array['post_status'];
					else
						$data_array['post_password'] = substr($data_array['post_status'],0,10);
					$data_array['post_status'] = 'publish';
				}
			}
			else{
				unset($data_array['post_password']);
				if($_POST['importallwithps'] == 1)
					$data_array['post_status'] = 'publish';
				if($_POST['importallwithps'] == 2){
					$data_array['post_status'] = 'publish';
					$sticky = true;
				}
				if($_POST['importallwithps'] == 3){
					$data_array['post_status'] = 'publish';
					$data_array['post_password'] = $_POST['postsPassword'];
				}
				if($_POST['importallwithps'] == 4)
					$data_array['post_status'] = 'private';
				if($_POST['importallwithps'] == 5)
					$data_array['post_status'] = 'draft';
				if($_POST['importallwithps'] == 6){
					$data_array['post_status'] = 'pending';
				}
			}
			$data_array['post_type']=$_POST['csv_importer_cat'];
			$permission = 'ok';
			$chkinsert =1;
			$chkedContent = 1;
		// Duplicate Check code starts
		if($_POST['titleduplicatecheck']){
		        $permission = 'notok';
		        $title = $data_array['post_title'];
		        $gettype = $data_array['post_type'];
		        $post_table = $wpdb->posts;
		        $post_exist = $wpdb->get_results("select ID from $post_table where post_title = \"{$title}\" and post_type = \"{$gettype}\" and post_status in('publish','future','draft')");
		        if(count($post_exist) == 0 && ($title != null || $title != '')){
				$chkinsert = 0;
				$permission = 'ok';
		        }
			}
		if($_POST['contentduplicatecheck']){
		$permission = 'ok';
		$content = $data_array['post_content'];
		$contentLength = strlen($content);
		$post_table = $wpdb->posts;
		$post_exist = $wpdb->get_results("select ID from $post_table where length(post_content) = \"{$contentLength}\"");
		if(count($post_exist)==0){
			$chkedContent = 0;
		}
		else{
		$chkforeach = 0;
		foreach($post_exist as $singlepost){
			$postdata = $wpdb->get_results("select post_content from $post_table where id = \"{$singlepost->ID}\"");
			$chkDbString = substr($postdata[0]->post_content,0,50);
			$chkFormString = substr($content,0,50);
			if($chkDbString == $chkFormString){
			$permission = 'notok';
			}
	
		}
	}
}
if($permission == 'notok')
$duplicates++;
if(!(is_numeric($data_array['post_author']))){
	$postuserid = $data_array['post_author'];
	$postauthor = $wpdb->get_results("select id from wp_users where user_login = \"{$postuserid}\"");
	if($postauthor == null){
		$data_array['post_author'] = 1;
		$data_array['post_status'] = 'draft';
		$wrongpostauthor++;
	}
	else
	$data_array['post_author'] = $postauthor[0]->id;
}
		// Duplicate Check code ends

			if($permission == 'ok'){

if($dateformat != 'YYYY-MM-DD HH:MM:SS'){
	if($dateformat == 'MM/DD/YYYY HH:MM'){
		$splitdate = explode(" ",$data_array['post_date']);
		$date = $splitdate[0];
		$time = $splitdate[1];
		$formatdatearray = explode("/",$date);
		$exactdate = $formatdatearray[2].'-'.$formatdatearray[0].'-'.$formatdatearray[1];
		$data_array['post_date'] = $exactdate.' '.$time;
		}
	if($dateformat == 'M/DD/YYYY HH:MM'){
                $splitdate = explode(" ",$data_array['post_date']);
                $date = $splitdate[0];
                $time = $splitdate[1];
                $formatdatearray = explode("/",$date);
                $exactdate = $formatdatearray[2].'-'.$formatdatearray[0].'-'.$formatdatearray[1];
                $data_array['post_date'] = $exactdate.' '.$time;
	}
	if(($dateformat == 'M/DD/YYYY')||($dateformat == 'MM/DD/YYYY')){
		$splitdate = explode(" ",$data_array['post_date']);
		$date = $splitdate[0];
		$formatdatearray = explode("/",$date);
		$exactdate = $formatdatearray[2].'-'.$formatdatearray[0].'-'.$formatdatearray[1];
		$data_array['post_date'] = $exactdate;
	}
	if(($dateformat == 'M/DD/YYYY HH:MM PM')||($dateformat == 'MM/DD/YYYY HH:MM PM')){
                $splitdate = explode(" ",$data_array['post_date']);
                $date = $splitdate[0];
                $time = $splitdate[1];
		$chkpm = $splitdate[2];
		if($chkpm == 'PM'||$chkpm == 'pm'){
			$time = strtotime($time .' + 12 hour');
			$time = date('G:i:s',$time);
		}
                $formatdatearray = explode("/",$date);
                $exactdate = $formatdatearray[2].'-'.$formatdatearray[0].'-'.$formatdatearray[1];
                $data_array['post_date'] = $exactdate.' '.$time;
	}
}
				$post_id = wp_insert_post( $data_array );
				if($sticky)
				stick_post($post_id);
				if($post_id){
					$insertedRecords = $insertedRecords+1;
				}
				if(!empty($custom_array)){
					foreach($custom_array as $custom_key => $custom_value){
						add_post_meta($post_id, $custom_key, $custom_value);
					}
				}
	
				// Create custom taxonomy to post
                                if(!empty($smack_taxo)){
                                        foreach($smack_taxo as $taxo_key => $taxo_value){
						$split_line = explode('|',$taxo_value);
                                                wp_set_object_terms( $post_id, $split_line, $taxo_key );
                                        }
                                }  // End of code to add custom taxonomy

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
	
				// Featured image by Fredrick Marks
				if(!empty($file)){
					$file_name=$dirname.'/'.$img_title.'.'.$type;
					$attach_id = wp_insert_attachment($file, $file_name, $post_id);
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					$attach_data = wp_generate_attachment_metadata( $attach_id, $fileurl );
					wp_update_attachment_metadata( $attach_id, $attach_data );
					set_post_thumbnail( $post_id, $attach_id );
				}
			}
		}
	if(($insertedRecords != 0) || ($duplicates != 0)){
	?>
		<div style="background-color: #FFFFE0;border-color: #E6DB55;border-radius: 3px 3px 3px 3px;border-style: solid;border-width: 1px;margin: 5px 15px 2px; padding: 5px;text-align:center"><b> <?php echo '('.$insertedRecords.')'; ?> records are successfully Imported !<br> <?php echo '('.$duplicates.')'; ?> duplicate records found !
<?php if($wrongpostauthor != 0){ ?><br>
<?php echo $wrongpostauthor; ?> posts with no valid UserID/Name are assigned admin as author.
<?php 
//ask and fill the error msg 

} ?></b></div>
	<?php }else if(($insertedRecords == 0) && ($duplicates == 0)){ ?>
                <div style="background-color: #FFFFE0;border-color: #E6DB55;border-radius: 3px 3px 3px 3px;border-style: solid;border-width: 1px;margin: 5px 15px 2px; padding: 5px;text-align:center"><b> Check your CSV file and format. </b></div>
	<?php } ?>
		<div style="margin-top:30px;margin-left:10px">
		    <form class="add:the-list: validate" method="post" enctype="multipart/form-data">
			<input type="submit" class='button-primary' id="goto" name="goto" value="Continue" />
		    </form>
		</div>
	<?php 
        	$upload_dir = wp_upload_dir();
	        $csvdir  = $upload_dir['basedir']."/ultimate_importer/";
		$CSVfile = $_POST['filename'];
		if(file_exists($csvdir.$CSVfile)){
			fileDelete($csvdir,$CSVfile); 
		}
	}
	else
	{
	?>
		<div class="wrap">
		     <marquee onmouseover="this.setAttribute('scrollamount', 0, 0);" onmouseout="this.setAttribute('scrollamount', 6, 0);" >Now the <a href="http://www.smackcoders.com/wp-ultimate-csv-importer-pro.html" target="_blank">Pro Version</a> is Available. For more details,please visit <a href="http://www.smackcoders.com/wp-ultimate-csv-importer-pro.html" target="_blank">here</a></marquee>
		     <div style="background-color: #FFFFE0;border-color: #E6DB55;border-radius: 3px 3px 3px 3px;border-style: solid;border-width: 1px;margin: 5px 15px 2px; margin-top:15px;padding: 5px;text-align:center"> Please check out <a href="http://www.smackcoders.com/blog/category/free-wordpress-plugins" target="_blank">www.smackcoders.com</a> for the latest news and details of other great plugins and tools. </div><br/>
		     <div style="min-width:45%;float:left;height:500px;">
			<h2>Import CSV File</h2>
			<form class="add:the-list: validate" method="post" enctype="multipart/form-data" onsubmit="return file_exist();">

			<!-- File input -->
			<p><label for="csv_import">Upload file:</label><br/>
			    <input name="csv_import" id="csv_import" type="file" value="" aria-required="true" /></p><br/>
			<p><input type="checkbox" name="titleduplicatecheck" value=1>  Enable Duplicate Post Title Detection<br>
			<p><input type="checkbox" name="contentduplicatecheck" value = 1>  Enable Duplicate Post Content Detection <br>
<!-- added above checkboxes for issueid 1057-->
			<p><label>Delimiter</label>&nbsp;&nbsp;&nbsp;
			    <select name="delim" id="delim">
				<option value=",">,</option>
				<option value=";">;</option>
			    </select>
			</p>

			<p class="submit"><input type="submit" class="button-primary" name="Import" value="Import" /></p>
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
