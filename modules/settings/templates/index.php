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
?>
<div style ='text-align:center;margin:0;color:red;font-size:smaller;'> Your Required Settings Configuration Please Select Security and Performance tab </div></br>
<div id="ShowMsg" style="display:none;"><p id="warning-msg" class="alert alert-warning"><?php echo $skinnyData['plugStatus'];?></p></div>
<?php if(isset($skinnyData['savesettings']) && $skinnyData['savesettings'] == 'done'){ ?>
	<div id="deletesuccess"><p class="alert alert-success">Settings Saved</p></div>
		<?php
		$skinnyData['savesettings'] == 'notdone';
	?>
		<script type="text/javascript"> 
		jQuery(document).ready( function() {
				jQuery('#ShowMsg').delay(2000).fadeOut();
				jQuery('#ShowMsg').css("display", "none");
				jQuery('#deletesuccess').delay(2000).fadeOut();
				});
	</script>
		<?php
} 
global $wpdb; ?>
<div class="uifree-settings">
<form class="add:the-list: validate" action="" name="importerSettings" method="post" enctype="multipart/form-data">
<div id="settingheader">
        <span class="corner-icon"> <img src="<?php echo WP_CONTENT_URL;?>/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG;?>/images/lSettingsCorner.png" width="24" height="24" /> </span>
        <span><label id="activemenu">General Settings</label></span>
        <button class="action btnn btn-primary" onclick="saveSettings();" style="float:right;position:relative; margin: 7px 15px 5px;padding:5px 10px;" value="Save" name="savesettings" type="submit">Save Changes </button>
</div>
<div id="settingsholder">
        <div id="sidebar">
        <ul>
                <li id="1" class="bg-sidebar selected" onclick="showsettingsoption(this.id);">
                        <span class="settings-icon"> <img src="<?php echo WP_CONTENT_URL;?>/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG;?>/images/settings.png" width="24" height="24" /> </span>
                        <span id="settingmenu1" >General Settings</span>
                        <span id="arrow1" class="list-arrow"></span>
                </li>
                <li id="2" class="bg-sidebar" onclick="showsettingsoption(this.id);">
                        <span class="settings-icon"> <img src="<?php echo WP_CONTENT_URL;?>/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG;?>/images/lcustomposts.png" width="24" height="24" /> </span>
                        <span id="settingmenu2" >Custom Posts & Taxonomy</span>
                        <span id="arrow2" class="list-arrow" style="display:none;" ></span>
                </li>
                <li id="3" class="bg-sidebar" onclick="showsettingsoption(this.id);">
                        <span class="settings-icon"> <img src="<?php echo WP_CONTENT_URL;?>/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG;?>/images/lcustomfields.png" width="24" height="24" /> </span>
                        <span id="settingmenu3" >Custom Fields</span>
                        <span id="arrow3" class="list-arrow" style="display:none;" ></span>
                </li>
                <li id="4" class="bg-sidebar" onclick="showsettingsoption(this.id);">
                        <span class="settings-icon"> <img src="<?php echo WP_CONTENT_URL;?>/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG;?>/images/lcart.png" width="24" height="24" /> </span>
                        <span id="settingmenu4" >Ecommerce Settings</span>
                        <span id="arrow4" class="list-arrow" style="display:none;" ></span>
                </li>
                <li id="5" class="bg-sidebar" onclick="showsettingsoption(this.id);">
                        <span class="settings-icon"> <img src="<?php echo WP_CONTENT_URL;?>/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG;?>/images/seo24.png" width="24" height="24" /> </span>
                        <span id="settingmenu5" >SEO Settings</span>
<span id="arrow5" class="list-arrow" style="display:none;" ></span>
                </li>
                <li id="6" class="bg-sidebar" onclick="showsettingsoption(this.id);">
                        <span class="settings-icon"> <img src="<?php echo WP_CONTENT_URL;?>/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG;?>/images/additionalfeatures.png" width="24" height="24" /> </span>
                        <span id="settingmenu6" >Additional Features</span>
                        <span id="arrow6" class="list-arrow" style="display:none;" ></span>
                </li>
                <li id="7" class="bg-sidebar" onclick="showsettingsoption(this.id);">
                        <span class="settings-icon"> <img src="<?php echo WP_CONTENT_URL;?>/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG;?>/images/DBOptimize.png" width="24" height="24" /> </span>
                        <span id="settingmenu7" >Database Optimization</span>
                        <span id="arrow7" class="list-arrow" style="display:none;" ></span>
                </li>
                <li id="8" class="bg-sidebar" onclick="showsettingsoption(this.id);">
                        <span class="settings-icon"> <img src="<?php echo WP_CONTENT_URL;?>/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG;?>/images/security.png" width="24" height="24" /> </span>
                        <span id="settingmenu8" >Security and Performance</span>
                        <span id="arrow8" class="list-arrow" style="display:none;" ></span>
                </li>
                <li id="9" class="bg-sidebar" onclick="showsettingsoption(this.id);">
                        <span class="settings-icon"> <img src="<?php echo WP_CONTENT_URL;?>/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG;?>/images/ldocs24.png" width="24" height="24" /> </span>
                        <span id="settingmenu9" >Documentation</span>
                        <span id="arrow9" class="list-arrow" style="display:none;" ></span>
                </li>
         </ul>
        </div>
<div id="contentbar">
<!-- div-1-->
                <div id="section1" class="generalsettings">
                        <div class="title">
                                        <h3>Enabled Modules</h3>
                                        <span style="float:right;margin-right:92px;margin-top:-34px;">
						<a href="#" id='checkallModules'  value = 'Check All' name='checkallModules'  onclick="selectModules(this.id);">Check All</a>
                                        </span>
                                        <span style="float:right;margin-right:5px;margin-top:-34px;">
						<a href="#" id='uncheckallModules' name='checkallModules'  value = 'Un Check All' onclick='selectModules(this.id);'> / Uncheck All</a>
                                        </span>
                        </div>
                        <div id="data">
				<table>
				<tr><td>
					<h3 id="innertitle">Post</h3>
                                        <label>
						<div>Enables to import posts with custompost and customfields.</div>
                                        <div>Enable to import posts with attributes from csv.</div>
					</label>
				</td><td>
				</td><td style="width:112px">
					<label id="postlabel" class="<?php echo $skinnyData['post']; ?>"><input type='checkbox' name='post' id='post' value='post' <?php echo $skinnyData['post']; ?> onclick="postsetting(this.id);" checked disabled style="display:none">Enable</label>
<input type="hidden" name="post" id="post" value="post" />
					<label id="nopostlabel" class="<?php echo $skinnyData['nopost']; ?>"><input type='checkbox' name='post' onclick="postsetting(this.id);" checked disabled style="display:none">Disable</label>
				</td></tr>
                                <tr><td>
				<h3 id="innertitle">Page</h3>
                                        <label><div>Enables to import pages with custompost and customfields.</div>
                                        <div>Enable to import pages with attributes from csv.</div></label>
				</td><td>
                                </td><td style="width:112px">
					<label id="pagelabel" class="<?php echo $skinnyData['page']; ?>"><input type='checkbox' name='page' id='page' style="display:none" value='page' <?php echo $skinnyData['page']; ?> onclick="pagesetting(this.id);" checked disabled >Enable</label>
<input type="hidden" name="page" id="page" value="page" />
					<label id="nopagelabel" class="<?php echo $skinnyData['nopage']; ?>"><input type='checkbox' name='page' style="display:none" onclick="pagesetting(this.id);" checked disabled >Disable</label>
				</td></tr>
                                <tr><td>
                                <h3 id="innertitle">Users</h3>
                                        <label>Enable to import users with attributes from csv.</label>
				</td><td>
                                </td><td style="width:112px">
					<label id="userlabel" class="<?php echo $skinnyData['users']; ?>" ><input type='checkbox' name='users' id='users' style="display:none" value='users' <?php echo $skinnyData['users']; ?> onclick="usersetting(this.id);" >Enable</label>
					<label id="nouserlabel" class="<?php echo $skinnyData['nousers']; ?>" ><input type='checkbox' name='users' style="display:none" onclick="usersetting(this.id);" >Disable</label>
				</td></tr>
                                <tr><td>
                                <h3 id="innertitle">Comments</h3>
                                        <label><div>Enables to import posts with custompost and customfields.</div>
						<div>Enable to import comments for post ids from csv.</div>
					</label>
				</td><td>
                                </td><td style="width:112px">
					<label id="commentslabel" class="<?php echo $skinnyData['comments']; ?>"><input type='checkbox' name='comments' id='comments' style="display:none" value='comments' <?php echo $skinnyData['comments']; ?> onclick="commentsetting(this.id);" >Enable </label>
					<label id="nocommentslabel" class="<?php echo $skinnyData['nocomments']; ?>"><input type='checkbox' name='comments' style="display:none" onclick="commentsetting(this.id);" >Disable </label>
				</td></tr>
                                <tr><td>
                                <h3 id="innertitle">Custom Post</h3>
                                        <label><div>Enables to import Customposts.</div>
<div>Enable to import custom posts with attributes from csv</div></label></td><td>
                                </td><td style="width:112px">
					<label id="cplabel" class="<?php echo $skinnyData['custompost']; ?>"><input type='checkbox' name='custompost' id='custompost'  style="display:none" value='custompost' <?php echo $skinnyData['custompost']; ?> onclick="cpsetting(this.id);" checked disabled >Enable </label>
<input type="hidden" name="custompost" id="custompost" value="custompost" />
					<label id="nocplabel" class="<?php echo $skinnyData['nocustompost']; ?>"><input type='checkbox' name='custompost' style="display:none" onclick="cpsetting(this.id);" checked disabled >Disable</label>
				</td></tr>
                                <tr><td>
                                <h3 id="innertitle">Custom Taxonomy</h3>
                                        <label><div>Enables to import Custom taxonomy.</div>
<div>Enable to import nested custom taxonomies with description and slug for each from csv</div></label>
				</td><td>
                                </td><td style="width:112px">
					<label id="custaxlabel" class="<?php echo $skinnyData['customtaxonomy']; ?>" ><input type='checkbox' name='customtaxonomy' id='customtaxonomy' style="display:none" value='customtaxonomy' <?php echo $skinnyData['customtaxonomy']; ?> onclick="check_if_avail(this.name);" >Enable</label>
					<label id="nocustaxlabel" class="<?php echo $skinnyData['nocustomtaxonomy']; ?>" ><input type='checkbox' name='customtaxonomy' style="display:none" onclick="check_if_avail(this.name);" >Disable</label>
				</td></tr>
                                <tr><td>
                                <h3 id="innertitle">Categories/Tags</h3>
                                        <label><div>Enables to import Categories.</div>
					<div>Enable to import nested categories with description and slug for each from csv.</div></label></td><td>
                                </td><td style="width:112px">
					<label id="catlabel" class="<?php echo $skinnyData['categories']; ?>"><input type='checkbox' name='categories' id='categories' style="display:none" value='categories' <?php echo $skinnyData['categories']; ?> onclick="check_if_avail(this.name);" >Enable</label>
					<label id="nocatlabel" class="<?php echo $skinnyData['nocategories']; ?>"><input type='checkbox' name='categories' style="display:none" onclick="check_if_avail(this.name);" >Disable</label>
				</td></tr>
                                <tr><td>
                                <h3 id="innertitle">Customer Reviews</h3>
                                        <label><div>Enables to import Customer reviews.</div>
<div>Enable to import customer reviews with attributes from csv.</div></label></td><td>
                                </td><td style="width:112px">
					<label id="custrevlabel" class="<?php echo $skinnyData['customerreviews'].' '.$skinnyData['acf'];?>" ><input type ='checkbox' name = 'rcustomfield' id='Customerreviews' style="display:none" value='Customerreviews' <?php echo $skinnyData['acf']; ?> onclick="check_if_avail(this.id);" >Enable</label>
					<label id="nocustrevlabel" class="<?php echo $skinnyData['nocustomerreviews'];?>" ><input type ='checkbox' name = 'rcustomfield' style="display:none" onclick="check_if_avail(this.name);" >Disable</label>
					<span id="pluginavail" class="moduleavail <?php echo $skinnyData['customerreviewstd'] ?>"> </span>
				</td></tr>
                                </tbody></table><br />
                                <label style='color:red;'><?php echo __("Note: Supports WordPress Custom Post by default. For Custom Post Type UI plugin, please enable it under Custom Posts & Taxonomy"); ?></label>
                        </div>
                </div>
<!--div-2 -->
                <div id="section2" class="custompost" style="display:none;">
                        <div class="title" class="databorder" >
                                <h3>Custom Posts & Taxonomy </h3>
                        </div>
                        <div id="data">
                               <table>
                               <tbody>
                               <tr><td>
                               <h3 id="innertitle" >Default</h3>
                        	       <label>Custom post types that are coded within wordpress codex apart from plugins.</label>
			       </td><td>
		    		       <label id="custompostsetting1" class="<?php echo $skinnyData['nonercustompost']; ?>" ><input type = 'radio' name ='rcustompost' id='nonercustompost'style="display:none" value='nonercustompost' <?php echo $skinnyData['nonercustompost']; ?> class='ecommerce' onclick="custompostsetting(this.id);"><span id="custompost1text"> <?php echo $skinnyData['default_status']; ?> </span></label>
			       </td></tr>
                               <tr><td>
                               <h3 id="innertitle">Custom Post Type UI</h3>
                               <label>Import support for Custom Post Type UI data.</label></td><td>
					<label id="custompostsetting2" class="<?php echo $skinnyData['custompostuitype'];?>" ><input type ='radio' name = 'rcustompost' id='custompostuitype' style="display:none" value='custompostuitype' <?php echo $skinnyData['custompostuitype']; ?> onclick="custompostsetting(this.id);"><span id="custompost2text"> <?php echo $skinnyData['cptui_status']; ?> </span></label>
					<div id="pluginavail" class="<?php echo $skinnyData['cptutd'] ?>"> </div>
                               </td></tr>
                               <tr><td>
                               <h3 id="innertitle">Types Custom Posts & Taxonomy</h3>
                               <label>Import support for Types Custom Post Type and taxonomies data.</label> </td><td>
					<label id="custompostsetting3" class="<?php echo $skinnyData['cctm'];?>" >
<input type ='radio' name = 'rcustompost' id='types' style="display:none" value='types' <?php echo $skinnyData['cctm']; ?> onclick="check_if_avail(this.id);" ><span id="custompost3text"> <?php echo $skinnyData['wptypes_status']; ?> </span></label>
					<div id="pluginavail" class="<?php echo $skinnyData['wptypestd'] ?>"> </div>
				 </td></tr>
                                <tr><td>
                                <h3 id="innertitle">CCTM Custom Posts</h3>
                                <label>Import support for CCTM Custom Posts from csv.</label> </td><td>
					<label id="custompostsetting4" class="<?php echo $skinnyData['cctm'];?>" ><input type ='radio' name = 'rcustompost' id='cctm' style="display:none" value='cctm' <?php echo $skinnyData['cctm']; ?> onclick="check_if_avail(this.id);" ><span id="custompost4text"><?php echo $skinnyData['cctm_status']; ?></span> </label>
					<div id="pluginavail" class="<?php echo $skinnyData['cctmtd'] ?>"> </div>
                                </td></tr>
				<tr><td>
                                <h3 id="innertitle">PODS Custom Posts & Taxonomy</h3>
                                <label>Import support for PODS Custom Posts.</label> </td><td>
                                <label id="custompostsetting5" class="<?php echo $skinnyData['podspost']; ?>"><input type='radio' name='rcustompost' id='podspost' value='podspost' <?php echo $skinnyData['podspost']; ?> style="display:none" onclick="check_if_avail(this.id);"><span id="custompost5text"> <?php echo $skinnyData['podspost_status']; ?></span></label>
                                <div id="pluginavail" class="<?php echo $skinnyData['podstd'] ?>"> </div>
                                </td></tr>
                                </tbody>
                                </table>
                        </div>
                </div>
		<!--div-3-->
                <div id="section3" class="Customfields" style="display:none;">
                        <div class="title">
                                        <h3>Custom Fields</h3>
                                        <span id="resetcustfield"><a id="resetopt" href="#" value="reset" name="resetcustfield" onclick="resetOption(this.id);">Reset Custom Field</a> </span>
                        </div>
                        <div id="data" class="databorder custom-fields" >
                                <table>
                                <tbody>
                                <tr><td>
                                <h3 id="innertitle">WP-Members for Users</h3>
                                <label>Enable to add import support WP-Members user fields.</label> </td><td>
					<label id="wpusercheck" class="<?php echo $skinnyData['checkuser'].' '.$skinnyData['acf'];?>" ><input type ='radio' name = 'rwpmembers' id='WP-Members' style="display:none" value='WP-Members' <?php echo $skinnyData['acf']; ?> onclick="check_if_avail(this.id);" ><span id="checkuser">Enabled</span></label>
					<label id="wpuseruncheck" class="<?php echo $skinnyData['uncheckuser'];?>" ><input type ='radio' name = 'rwpmembers' style="display:none" onclick="check_if_avail(this.id);" >Disabled</label>
				</td></tr>
                                <tr><td>
                                <h3 id="innertitle">WP e-Commerce Custom Fields </h3>
                                <label>Enable to add import support for WP e-Commerce custom fields.</label> </td>
                                <td style="padding-left: 20px;">
					<input type='checkbox' name='recommerce' id='wpcommerce' value='wpcommerce' <?php echo $skinnyData['wpcommerce']; ?>  class = 'ecommerce' onclick='check_if_avail(this.id);'> 
				</td></tr>
                                <tr><td>
                                <h3 id="innertitle">ACF Custom Fields</h3>
                                <label>Enable to add import support for ACF Custom Fields.</label> </td><td>
					<label id="customfieldsetting1" class="<?php echo $skinnyData['acf'];?>" ><input type ='checkbox' name = 'rcustomfield' id='acf' style="display:none" value='acf' <?php echo $skinnyData['acf']; ?> onclick="check_if_avail(this.id);" ><span id="customfield1text" > <?php echo $skinnyData['acf_status']; ?> </span></label>
					<div id="pluginavail" class="<?php echo $skinnyData['acftd'] ?>"> </div>
				</td></tr>
                                <tr><td>
                                <h3 id="innertitle">CCTM Custom Fields</h3>
                                <label>Enable to add import support for CCTM Custom Fields.</label> </td><td>
					<label id="customfieldsetting2" class="<?php echo $skinnyData['cctmcustfields'].' '.$skinnyData['cctm'];?>" ><input type ='radio' name = 'rcustompost' id='cctmcustomfields' style="display:none" value='cctm' <?php echo $skinnyData['cctm']; ?> onclick="check_if_avail(this.id);" ><span id="customfield2text" > <?php echo $skinnyData['cctmfield_status']; ?> </span></label>	   
					<div id="pluginavail" class="<?php echo $skinnyData['cctmtd'] ?>"> </div>
				</td></tr>
                                <tr><td>
                                <h3 id="innertitle">Types Custom Fields</h3>
                                <label>Enable to add import support for Types custom fields.</label> </td><td>
                                <label id="customfieldsetting3" class="<?php echo $skinnyData['wptypescustfields']; ?>"><input type='radio' name='rcustomfield' id='typescustomfield' value='wptypescustfields' <?php echo $skinnyData['wptypes']; ?> style="display:none" onclick="check_if_avail(this.id);" /><span id="customfield3text" > <?php echo $skinnyData['typesfield_status']; ?> </span></label>
                                <div id="pluginavail" class="<?php echo $skinnyData['wptypestd'] ?>"> </div>
                                </td></tr>
                                <tr><td>
                                <h3 id="innertitle">PODS Custom Fields </h3>
				<label>Enable to add import support for PODS custom fields.</label> </td><td>
                                <label id="customfieldsetting4" class="<?php echo $skinnyData['podscustomfields']; ?>"><input type='radio' name='rcustomfield' id='podscustomfield' value='podscustomfields' <?php echo $skinnyData['podscustomfields']; ?> style="display:none" onclick="check_if_avail(this.id);" /><span id="customfield4text" > <?php echo $skinnyData['podsfield_status']; ?> </span></label>
                                <div id="pluginavail" class="<?php echo $skinnyData['podstd'] ?>"> </div>
                                </td></tr>
                                </tbody>
                                </table>
                        </div>
                </div>
	<!--div-4 -->
                <div id="section4" class="ecommercesettings" style="display:none;">
                        <div class="title">
                        <h3>Ecommerce Settings</h3>
                        </div>
                        <div id="data" class="databorder" >
                                <table>
                                <tbody>
                                <tr><td>
                                <h3 id="innertitle">None</h3>
                                <label>Ecommerce import is disabled.</label> </td><td>
					<label id="ecommercesetting1"class="<?php echo $skinnyData['nonerecommerce']; ?>"><input type = 'radio' name ='recommerce' id='nonerecommerce' value='nonerecommerce' <?php echo $skinnyData['nonerecommerce']; ?> class='ecommerce' checked style="display:none" onclick="ecommercesetting(this.id);" ><span id="ecommerce1text"> <?php echo $skinnyData['ecomnone_status']; ?> </span></label>
				</td></tr>
                                <tr><td>
                                <h3 id="innertitle">Eshop </h3>
                                <label>Enable ecommerce import for Eshop.</label> </td><td>
					<label id="ecommercesetting2" class="<?php echo $skinnyData['eshop']; ?>">
<input type='radio' name='recommerce' id='eshop' value='eshop' <?php echo $skinnyData['eshop']; ?> class='ecommerce' style="display:none" onclick="ecommercesetting(this.id);"><span id="ecommerce2text"><?php echo $skinnyData['eshop_status']; ?></span></label>
				<div id="pluginavail" class="<?php echo $skinnyData['eshoptd'] ?>"> </div>
                                </td></tr>
                                <tr><td>
                                <h3 id="innertitle">Marketpress Lite</h3>
                                <label>Enable ecommerce import for marketpress Lite.</label> </td><td>
					<label id="ecommercesetting3" class="<?php echo $skinnyData['woocommerce']; ?>"><input type='radio' name='recommerce' id='marketpress' value='marketpress' <?php echo $skinnyData['marketpress']; ?>  class = 'woocommerce' onclick='check_if_avail(this.id);' style="display:none"><span id="ecommerce3text"><?php echo $skinnyData['marketpress_status']; ?></span></label>
				<div id="pluginavail" class="<?php echo $skinnyData['marketpresslitetd'] ?>"> </div>
                                </td></tr>
                                <tr><td>
                                <h3 id="innertitle">Woocommerce </h3>
                                <label>Enable ecommerce import for Woocommerce.</label> </td><td>
					<label id="ecommercesetting4" class="<?php echo $skinnyData['woocommerce']; ?>"><input type='radio' name='recommerce' id='woocommerce' value='woocommerce' <?php echo $skinnyData['woocommerce']; ?>  class = 'woocommerce' onclick='check_if_avail(this.id);' style="display:none" ><span id="ecommerce4text"><?php echo $skinnyData['woocommerce_status']; ?></span> </label>			
					<div id="pluginavail" class="<?php echo $skinnyData['woocomtd'] ?>"> </div>
                                </td></tr>
                                <tr><td>
                                <h3 id="innertitle"> WP e-Commerce</h3>
                                <label>Enable ecommerce import for WP e-Commerce.</label> </td><td>
					<label id="ecommercesetting5" class="<?php echo $skinnyData['wpcommerce']; ?>">
					<input type='radio' name='recommerce' id='wpcommerce' value='wpcommerce' <?php echo $skinnyData['wpcommerce']; ?>  class = 'ecommerce' onclick='check_if_avail(this.id);' style="display:none" ><span id="ecommerce5text"><?php echo $skinnyData['wpcommerce_status']; ?></span></label>
					<div id="pluginavail" class="<?php echo $skinnyData['wpcomtd'] ?>"> </div>
                                </td></tr>
                                </tbody>
                                </table>
                        </div>
                </div>
                <!--div-5-->
                <div id="section5" class="seosettings" style="display:none;">
                        <div class="title">
                                <h3>SEO Settings</h3>
                        </div>
                        <div id="data" class="databorder" >
				<table>
                                <tbody>
                                <tr><td>
                                <h3 id="innertitle">None</h3>
                                <label>SEO Meta import is disabled.</label> </td><td>
					<label id="seosetting1" class="<?php echo $skinnyData['nonerseooption'];?>" ><input type = 'radio' name ='rseooption' id='nonerseooption' value='nonerseooption' <?php #echo $skinnyData['nonerseooption']; ?> class='ecommerce' onclick="seosetting(this.id);" style="display:none"><span id="seosetting1text"> <?php echo $skinnyData['none_status']; ?> </span> </label>
				</td></tr>
                                <tr><td>
                                <h3 id="innertitle">All-in-one SEO </h3>
                                <label>Enable All-in-one SEO import.</label> </td><td>
					<label id="seosetting2" class="<?php echo $skinnyData['aioseo']; ?>" ><input type ='radio' name = 'rseooption' id='aioseo' value='aioseo' <?php echo $skinnyData['aioseo']; ?> onclick="seosetting(this.id);" style="display:none"><span id="seosetting2text"> <?php echo $skinnyData['aioseo_status']; ?> </span></label>
					<div id="pluginavail" class="<?php echo $skinnyData['aioseotd'] ?>"> </div>
                                </td></tr>
                                <tr><td>
                                <h3 id="innertitle"> Yoast SEO</h3>
                                <label>Enable Wordpress SEO by  Yoast support.</label> </td><td>
					<label id="seosetting3" class="<?php echo $skinnyData['yoastseo']; ?>" ><input type ='radio' name = 'rseooption' id='yoastseo' value='yoastseo' <?php echo $skinnyData['yoastseo']; ?> onclick="check_if_avail(this.id);" style="display:none"><span id="seosetting3text"><?php echo $skinnyData['yoastseo_status']; ?> </span></label>
					<div id="pluginavail" class="<?php echo $skinnyData['yoasttd'] ?>" > </div>
                                </td></tr>
                                </tbody>
                                </table>
                        </div>
                </div>

                 <!--div-6-->
                <div id="section6" class="additionalfeatures" style="display:none;">
                        <div class="title">
                                <h3>Additional Features</h3>
			 </div>
                        <div id="data">
                                <table class="enablefeatures">
                                <tbody>
                                <tr><td>
					<label class=$automapping>
<input type='checkbox' name='automapping' id='automapping' value='automapping'  checked disabled onclick="check_if_avail(this.id);" ><span id="align">Enable Auto Mapping</span></label>
				</td></tr>
                                <tr><td>
					<label class=$utfsupport><input type='checkbox' name='rutfsupport' id='utfsupport' value='utfsupport' checked disabled onclick="check_if_avail(this.id);" ><span id="align">Enable UTF Support</span></label>
				</td></tr>
                                <tr class="databorder"><td>
					<label id="align">Export Delimiter
					<select name="export_delimiter">
						<option>;</option>
						<option>,</option>
					</select>
					</label>
				</td></tr>
                                <tr class="databorder"><td>
                                        <h3 id="innertitle">Debug Mode</h3>
                                        <label>You can enable/disable the debug mode.</label> </td><td>
                                        <label id="debugmode_enable" class="<?php echo $skinnyData['debugmode_enable']; ?>"><input type='radio' name='debug_mode' value='enable_debug' <?php echo $skinnyData['debugmode_enable']; ?> id="enabled" style="display:none" onclick="debugmode_check(this.id);" > On </label>
                                <label id="debugmode_disable" class="<?php echo $skinnyData['debugmode_disable']; ?>"><input type='radio' name='debug_mode' value='disable_debug' <?php echo $skinnyData['debugmode_disable']; ?> id="disabled" style="display:none" onclick="debugmode_check(this.id);" > Off </label>
                                </td></tr>
                                <tr class="databorder"><td>
                                	<h3 id="innertitle">Scheduled log mails</h3>
					<label>Enable to get scheduled log mails.</label> </td><td>
	                                <label id="schedulecheck" class="<?php echo $skinnyData['schedulelog']; ?>"><input type='radio' name='send_log_email' value='send_log_email' <?php echo $skinnyData['send_log_email']; ?> id="scheduled" style="display:none" onclick="check_if_avail(this.id);" > Yes </label>
                                <label id="scheduleuncheck" class="<?php echo $skinnyData['schedulenolog']; ?>"><input type='radio' name='send_log_email' id="noscheduled" style="display:none" onclick="check_if_avail(this.id);" > No </label>
                                </td></tr>
                                <tr><td>
                                <h3 id="innertitle">Drop Table</h3>
                                <label>If enabled plugin deactivation will remove plugin data, this cannot be restored.</label></td>
                                <td><label id="dropon" class="<?php echo $skinnyData['drop_on'] ; ?>" ><input type='radio' name='drop_table' id='drop_table' value='on' <?php echo $skinnyData['dropon_status']; ?> style="display:none" onclick="check_if_avail(this.id);" > On </label>
                                <label id="dropoff" class="<?php echo $skinnyData['drop_off'] ; ?>" ><input type='radio' name='drop_table' id='drop_tab' value='off' <?php echo $skinnyData['dropoff_status']; ?> style="display:none" onclick="check_if_avail(this.id);" > Off</label>
                                </td></tr>
                                <tr><td>
                                <h3 id="innertitle" >Category Icons:</h3>
                                <label>Enable to import category icons for category.</label>
                                </td><td>
					<label id="catenable" class="<?php echo $skinnyData['enable'] ." ". $skinnyData['catyenable'];?>" ><input type = 'radio' name ='rcateicons' id='caticonenable' style="display:none" value='enable' <?php echo $skinnyData['enable']; ?> class='ecommerce' onclick="check_if_avail(this.id);"> Enable </label>
					<label id="catdisable" class="<?php echo $skinnyData['enable'] ." ". $skinnyData['catydisable'];?>" ><input type = 'radio' name ='rcateicons' id='caticondisable' style="display:none" value='disable' <?php echo $skinnyData['disable']; ?> checked onclick="check_if_avail(this.id);">Disable</label>
				</td>
                                </tr>
                                </tbody>
                                </table>
                        </div>
                </div>
                <!--div-7-->
		<div id="section7" class="databaseoptimization" style="display:none;">
                        <div class="title">
                                <h3>Database Optimization <img src="<?php echo WP_CONTENT_URL;?>/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG;?>/images/pro_icon.gif" title='PRO Feature' /></h3>
                                <span style="float:right;margin-right:168px;margin-top:-35px;">
                                        <a id="checkOpt" onclick="check_if_avail(this.id);" href="#"> Check All </a>
                                </span>
                                <span style="float:right;margin-right:81px;margin-top:-35px;">
                                        <a id="uncheckOpt" onclick="check_if_avail(this.id);" href="#"> / Uncheck All </a>
                                </span>
                        </div>
                        <div id="data" class="database">
                        <table class="databaseoptimization">
                        <tbody>
                        <tr><td>
                        <label><input type='checkbox' name='delete_all_orphaned_post_page_meta' id='delete_all_orphaned_post_page_meta' value='delete_all_orphaned_post_page_meta' <?php echo $skinnyData['delete_all_orphaned_post_page_meta']; ?> disabled /><span id="align"> Delete all orphaned Post/Page Meta</span></label>
                        </td><td>
	                        <label><input type='checkbox' name='delete_all_unassigned_tags' id='delete_all_unassigned_tags' value='delete_all_unassigned_tags' <?php echo $skinnyData['delete_all_unassigned_tags']; ?>  disabled/><span id="align"> Delete all unassigned tags</span></label>
                        </td></tr>
                        <tr><td>
        	                <label><input type='checkbox' name='delete_all_post_page_revisions' id='delete_all_post_page_revisions' value='delete_all_post_page_revisions' <?php echo $skinnyData['delete_all_post_page_revisions']; ?> disabled  /><span id="align"> Delete all Post/Page revisions</span></label>
			</td><td>
	                        <label><input type='checkbox' name='delete_all_auto_draft_post_page' id='delete_all_auto_draft_post_page' value='delete_all_auto_draft_post_page' <?php echo $skinnyData['delete_all_auto_draft_post_page']; ?> disabled /><span id="align"> Delete all auto drafted Post/Page</span></label>
                        </td></tr>
                        <tr><td>
        	                <label><input type='checkbox' name='delete_all_post_page_in_trash' id='delete_all_post_page_in_trash' value='delete_all_post_page_in_trash' <?php echo $skinnyData['delete_all_spam_comments']; ?> disabled /><span id="align"> Delete all Post/Page in trash</span></label>
                        </td><td>
	                        <label><input type='checkbox' name='delete_all_spam_comments' id='delete_all_spam_comments' value='delete_all_spam_comments' <?php echo $skinnyData['delete_all_comments_in_trash']; ?> disabled /><span id="align"> Delete all Spam Comments</span></label>
                        </td></tr>
                        <tr><td>
	                        <label><input type='checkbox' name='delete_all_comments_in_trash' id='delete_all_comments_in_trash' value='delete_all_comments_in_trash'  <?php echo $skinnyData['delete_all_comments_in_trash']; ?> disabled  /><span id="align"> Delete all Comments in trash</span></label>
                        </td><td>
	                        <label><input type='checkbox' name='delete_all_unapproved_comments' id='delete_all_unapproved_comments' value='delete_all_unapproved_comments'  <?php echo $skinnyData['delete_all_unapproved_comments']; ?> disabled /><span id="align"> Delete all Unapproved Comments</span></label>
                        </td></tr>
                        <tr><td>
	                        <label><input type='checkbox' name='delete_all_pingback_commments' id='delete_all_pingback_commments' value='delete_all_pingback_commments'  <?php echo $skinnyData['delete_all_pingback_commments']; ?> disabled /><span id="align"> Delete all Pingback Comments</span></label>
			</td><td>
                        <label><input type='checkbox' name='delete_all_trackback_comments' id='delete_all_trackback_comments' value='delete_all_trackback_comments'  <?php echo $skinnyData['delete_all_trackback_comments']; ?> disabled /> <span id="align"> Delete all Trackback Comments</span></label>
                        </td></tr>
                        </tbody>
                        </table>
                                <div style="float:right;padding:17px;margin-top:-2px;">
                                        <input id="database_optimization" class="action btn btn-warning" type="button" onclick="check_if_avail();" value="Run DB Optimizer" name="database_optimization">
                                </div>
                                <div id="optimizelog" style="margin-top:40px;display:none;">
                                        <h4>Database Optimization Log</h4>
                                        <div id="optimizationlog" class="optimizerlog">
                                                <div id="log" class="log">
                                                        <p style="margin:15px;color:red;" id="align">NO LOGS YET NOW.</p>
                                                </div>
                                        </div>
                                </div>
                        </div>
                </div>
		 <!--div-8-->
                <div id="section8" class="securityperformance" style="display:none;">
                        <div class="title">
                                <h3>Security and Performance</h3>
                        </div>
                        <div id="data" class="databorder security-perfoemance" >
                        <table class="securityfeatures">
                        <tr><td>
                        <h3 id="innertitle">Allow authors/editors to import</h3>
                        <label><div>It helps authors/editors can import using importer.</div>
			<div>It does not support users.</div></label></td><td>

<!--<label><input type='checkbox' name='enable_plugin_access_for_author' value='enable_plugin_access_for_author' <?php echo $skinnyData['enable_plugin_access_for_author']; ?> /> Allow authors to import </label>-->


				<label id="allowimport" class="<?php echo $skinnyData['authorimport']; ?>" ><input type='radio' name='enable_plugin_access_for_author' id="enableimport" class="importauthor" value='enable_plugin_access_for_author' <?php echo $skinnyData['enable_plugin_access_for_author']; ?> style="display:none" onclick="authorimportsetting(this.id);"/>Check</label>
	                        <label id="donallowimport" class="<?php echo $skinnyData['noauthorimport']; ?>" > <input type='radio' name='enable_plugin_access_for_author' class="importauthor" style="display:none"  onclick="authorimportsetting(this.id);">Uncheck</label>
                        </td></tr>
                        </table>
			<table class="table table-striped">
                        <tr><th colspan="3" >
                        <h3 id="innertitle">Minimum required php.ini values (Ini configured values)</h3>
                        </th></tr>
                        <tr><th>
                        <label>Variables</label>
                        </th><th class='ini-configured-values'>
                        <label>System values</label>
                        </th><th class='min-requirement-values'>
                        <label>Minimum Requirements</label>
                        </th></tr>
                        <tr><td>post_max_size </td><td class='ini-configured-values'><?php echo ini_get('post_max_size') ?></td><td class='min-requirement-values'>10M</td></tr>
                        <tr><td>auto_append_file</td><td class='ini-configured-values'>-<?php echo ini_get('auto_append_file') ?></td><td class='min-requirement-values'>-</td></tr>
                        <tr><td>auto_prepend_file </td><td class='ini-configured-values'>-<?php echo ini_get('auto_prepend_file') ?></td><td class='min-requirement-values'>-</td></tr>
                        <tr><td>upload_max_filesize </td><td class='ini-configured-values'><?php echo ini_get('upload_max_filesize') ?></td><td class='min-requirement-values'>2M</td></tr>
                        <tr><td>file_uploads </td><td class='ini-configured-values'><?php echo ini_get('file_uploads') ?></td><td class='min-requirement-values'>1</td></tr>
                        <tr><td>allow_url_fopen </td><td class='ini-configured-values'><?php echo ini_get('allow_url_fopen') ?></td><td class='min-requirement-values'>1</td></tr>
                        <tr><td>max_execution_time </td><td class='ini-configured-values'><?php echo ini_get('max_execution_time') ?></td><td class='min-requirement-values'>3000</td></tr>
                        <tr><td>max_input_time </td><td class='ini-configured-values'><?php echo ini_get('max_input_time') ?></td><td class='min-requirement-values'>3000</td></tr>
                        <tr><td>max_input_vars </td><td class='ini-configured-values'><?php echo ini_get('max_input_vars') ?></td><td class='min-requirement-values'>3000</td></tr>
                        <tr><td>memory_limit </td><td class='ini-configured-values'><?php echo ini_get('memory_limit') ?></td><td class='min-requirement-values'>99M</td></tr>
                        </table>
                        <h3 id="innertitle" colspan="2" >Required Loaders and Extentions:</h3>
                        <table class="table table-striped">
                        <?php $loaders_extensions = get_loaded_extensions();?>
                        <tr><td>IonCube Loader </td><td><?php if(in_array('ionCube Loader', $loaders_extensions)) {
                                        echo '<label style="color:green;">Yes</label>';
                                } else {
                                        echo '<label style="color:red;">No</label>';
                                } ?> </td><td></td></tr>
			<tr><td>PDO </td><td><?php if(in_array('PDO', $loaders_extensions)) {
                                        echo '<label style="color:green;">Yes</label>';
                                } else {
                                        echo '<label style="color:red;">No</label>';
                                } ?></td><td></td></tr>
                        <tr><td>Curl </td><td><?php if(in_array('curl', $loaders_extensions)) {
                                        echo '<label style="color:green;">Yes</label>';
                                } else {
                                        echo '<label style="color:red;">No</label>';
                                } ?></td><td></td></tr>
                        </table>
                        <h3 id="innertitle" colspan="2" >Debug Information:</h3>
                        <table class="table table-striped">
                        <tr><td class='debug-info-name'>WordPress Version</td><td><?php echo $wp_version; ?></td><td></td></tr>
                        <tr><td class='debug-info-name'>PHP Version</td><td><?php echo phpversion(); ?></td><td></td></tr>
                       <tr><td class='debug-info-name'>MySQL Version</td><td><?php echo $wpdb->db_version(); ?></td><td></td></tr>
                        <tr><td class='debug-info-name'>Server SoftWare</td><td><?php echo $_SERVER[ 'SERVER_SOFTWARE' ]; ?></td><td></td></tr>                        <tr><td class='debug-info-name'>Your User Agent</td><td><?php echo $_SERVER['HTTP_USER_AGENT']; ?></td><td></td></tr>
                        <tr><td class='debug-info-name'>WPDB Prefix</td><td><?php echo $wpdb->prefix; ?></td><td></td></tr>
                        <tr><td class='debug-info-name'>WP Multisite Mode</td><td><?php if ( is_multisite() ) { echo '<label style="color:green;">Enabled</label>'; } else { echo '<label style="color:red;">Disabled</label>'; } ?> </td><td></td></tr>
                        <tr><td class='debug-info-name'>WP Memory Limit</td><td><?php echo (int) ini_get('memory_limit'); ?></td><td></td></tr>
                        </table>
                        </div>
                </div>
                <div id="section9" class="documentation" style="display:none;">
                        <div class="title">
                                <h3>Documentation</h3>
                        </div>
                        <div id="data">
                                <div id="video">
					<iframe width="560" height="315" src="//www.youtube.com/embed/FhTUXE5zk0o?list=PL2k3Ck1bFtbRli9VdJaqwtzTSzzkOrH4j" frameborder="0" allowfullscreen></iframe>
                                </div>
                                <div id="relatedpages">
                                        <h2 id="doctitle">Smackcoders Guidelines </h2 >
                                        <p> <a href="https://www.smackcoders.com/blog/category/web-development-news/" target="_blank"> Development News </a> </p>
					<p> <a href="http://www.wpultimatecsvimporter.com/" target="_blank"> Whats New? </a> </p>
                                        <p> <a href="http://wiki.smackcoders.com/WP_Ultimate_CSV_Importer_Pro" target="_blank"> Documentation </a> </p>
                                        <p> <a href="https://www.smackcoders.com/blog/csv-importer-a-simple-and-easy-csv-importer-tutorial.html" target="_blank"> Tutorials </a> </p>
                                        <p> <a href="http://www.youtube.com/user/smackcoders/channels" target="_blank"> Youtube Channel </a> </p>
                                        <p> <a href="https://www.smackcoders.com/store/products-46/wordpress.html" target="_blank"> Other Plugins </a> </p>
                                </div>
                        </div>
                </div>
<!--conbar-->
         </div>
</div>
<div id="bottomsave">
<!--        <span style="float:left;width:50%">
                <div id="repplugin"><span id="repavail" style="float:left" class="pluginActive"></span><label id="replabel" >Indicates active plugin.</label></div>
                <div id="repplugin"><span id="repavail" style="float:left" class="pluginAbsent"></span><label id="replabel" >Indicates the absence or inactive state of plugin.</label></div>
        </span>-->
        <span style="float:right" >
                <button class="action btnn btn-primary" onclick="saveSettings();" style="float:right;position:relative; margin: 8px 15px 5px;padding:5px 10px" value="Save" name="savesettings" type="submit">Save Changes </button>
        </span>
</div>
</form>
</div>
	
