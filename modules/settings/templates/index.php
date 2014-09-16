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
} ?>
<form class="add:the-list: validate" action="" name="importerSettings" method="post" enctype="multipart/form-data">
<div class="container-fluid">
<div class="accordion" id="accordion2">
<div class="accordion-group">
<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">  <?php echo __("Modules"); ?> <img src="<?php echo WP_CONTENT_URL;?>/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG;?>/images/arrow_down.gif" style="float:right;" /></a>

</div>
<div id="collapseTwo" class="accordion-body in collapse">
<div class="accordion-inner">
<div id = 'moduleBox' class = 'switchercontent newboxes2'>
<table>
<tr>
<td>
<ul>
<label class="<?php echo $skinnyData['post']; ?>"><input type='checkbox' name='post' id='post' value='post' <?php echo $skinnyData['post']; ?> onclick="check_if_avail(this.name);" checked disabled > Post </label>
<input type="hidden" name="post" id="post" value="post" />
<label class="<?php echo $skinnyData['users']; ?>" ><input type='checkbox' name='users' id='users' value='users' <?php echo $skinnyData['users']; ?> onclick="check_if_avail(this.name);" > Users/Roles </label>									
<label class="<?php echo $skinnyData['page']; ?>"><input type='checkbox' name='page' id='page' value='page' <?php echo $skinnyData['page']; ?> onclick="check_if_avail(this.name);" checked disabled > Page </label>
<input type="hidden" name="page" id="page" value="page" />
<label class="<?php echo $skinnyData['comments']; ?>"><input type='checkbox' name='comments' id='comments' value='comments' <?php echo $skinnyData['comments']; ?> onclick="check_if_avail(this.name);" > Comments </label>
<label class="<?php echo $skinnyData['categories']; ?>"><input type='checkbox' name='categories' id='categories' value='categories' <?php echo $skinnyData['categories']; ?> onclick="check_if_avail(this.name);" > Categories/Tags </label>
<label class="<?php echo $skinnyData['customtaxonomy']; ?>" ><input type='checkbox' name='customtaxonomy' id='customtaxonomy' value='customtaxonomy' <?php echo $skinnyData['customtaxonomy']; ?> onclick="check_if_avail(this.name);" > Custom Taxonomy </label>

<label class="<?php echo $skinnyData['custompost']; ?>"><input type='checkbox' name='custompost' id='custompost' value='custompost' <?php echo $skinnyData['custompost']; ?> onclick="check_if_avail(this.name);" checked disabled > Custom Post </label>
<input type="hidden" name="custompost" id="custompost" value="custompost" />
<label style='color:red;position:relative;margin-top:1%;'> Note: Supports WordPress Custom Post by default. For Custom Post Type UI plugin enable it under supported 3rd party plugins</label>
</ul>
</td>
</tr>
</table>
<span style = "margin-left:73%;margin-top:1%;"><a href="#" id='checkallModules'  value = 'Check All' name='checkallModules'  onclick="selectModules(this.id);">Check All</a></span> 
<span style = "margin-left:0%;margin-top:1%;"><a href="#" id='uncheckallModules' name='checkallModules'  value = 'Un Check All' onclick='selectModules(this.id);'> / Uncheck All</a></span>
<button type='submit' class='action btn btn-primary' name='savesettings' value='Save' style='float:right;margin-top:-2%;' onclick="saveSettings();">Save</button>

<!--							<input type='hidden' name='post' value='post' />
<input type='hidden' name='custompost' value='custompost' />
<input type='hidden' name='page' value='page' />-->
</div>
</div>
</div>
</div>
<div class="accordion-group">
<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree">   <?php echo __("Third Party Plugins"); ?> <img src="<?php echo WP_CONTENT_URL;?>/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG;?>/images/arrow_down.gif" style="float:right;" /></a>
 
</div>
<div id="collapseThree" class="accordion-body collapse">
<div class="accordion-inner">
<div id='thirdPartyBox' class = 'switchercontent newboxes2'>
<table class='supportedplugins'>
<tr class='typeofplugin'>
<td class="plugintype" colspan=4> <b> Ecommerce </b> </td>
</tr>
<tr>
<td> 
<label class="<?php echo $skinnyData['nonerecommerce']; ?>"><input type = 'radio' name ='recommerce' id='nonerecommerce' value='nonerecommerce' <?php echo $skinnyData['nonerecommerce']; ?> class='ecommerce' checked> None </label>
</td>
<td>
<label class="<?php echo $skinnyData['eshoptd'].' '.$skinnyData['eshop']; ?>">
<input type='radio' name='recommerce' id='eshop' value='eshop' <?php echo $skinnyData['eshop']; ?> class='ecommerce' > Eshop
</label>
</td>
<td>			
<label class="<?php echo $skinnyData['woocomtd'].' '.$skinnyData['woocommerce']; ?>"><input type='radio' name='recommerce' id='marketpress' value='marketpress' <?php echo $skinnyData['marketpress']; ?>  class = 'woocommerce' onclick='check_if_avail(this.id);'> Market Press Lite</label>
</td>
<td>			
<label class="<?php echo $skinnyData['woocomtd'].' '.$skinnyData['woocommerce']; ?>"><input type='radio' name='recommerce' id='woocommerce' value='woocommerce' <?php echo $skinnyData['woocommerce']; ?>  class = 'woocommerce' onclick='check_if_avail(this.id);'> WooCommerce </label>
</td>
</tr>
<!-- WP e-Commerce Custom Fields support -->
<tr id='wpcustomfieldstr'>
<td> 
<label class="<?php echo $skinnyData['wpcomtd'].' '.$skinnyData['wpcommerce']; ?>">
<input type='radio' name='recommerce' id='wpcommerce' value='wpcommerce' <?php echo $skinnyData['wpcommerce']; ?>  class = 'ecommerce' onclick='check_if_avail(this.id);'> WP e-Commerce </label>
</td>

</tr>
<tr class='typeofplugin'><td colspan=4><b> Custom Post and Custom Fields </b></td></tr>
<tr>
<td><label class="<?php echo $skinnyData['nonercustompost']; ?>" ><input type = 'radio' name ='rcustompost' id='nonercustompost' value='nonercustompost' <?php echo $skinnyData['nonercustompost']; ?> class='ecommerce' onclick="check_if_avail(this.id);" > Default </label></td>
<td><label class="<?php echo $skinnyData['cptutd'].' '.$skinnyData['custompostuitype'];?>" ><input type ='radio' name = 'rcustompost' id='custompostuitype' value='custompostuitype' <?php echo $skinnyData['custompostuitype']; ?> > Custom Post Type UI </label></td>
<td><label class="<?php echo $skinnyData['cctmtd'].' '.$skinnyData['cctm'];?>" ><input type ='radio' name = 'rcustompost' id='cctm' value='cctm' <?php echo $skinnyData['cctm']; ?> onclick="check_if_avail(this.id);" > CCTM </label></td>
<td><label class="<?php echo $skinnyData['cctmtd'].' '.$skinnyData['cctm'];?>" >
<input type ='radio' name = 'rcustompost' id='types' value='types' <?php echo $skinnyData['cctm']; ?> onclick="check_if_avail(this.id);" > Types </label></td>
</tr>
<tr>
<td><label class="<?php echo $skinnyData['acftd'].' '.$skinnyData['acf'];?>" ><input type ='checkbox' name = 'rcustomfield' id='acf' value='acf' <?php echo $skinnyData['acf']; ?> onclick="check_if_avail(this.id);" > ACF </label></td>
<td><label class="<?php echo $skinnyData['acftd'].' '.$skinnyData['acf'];?>" ><input type ='checkbox' name = 'rcustomfield' id='Customerreviews' value='Customerreviews' <?php echo $skinnyData['acf']; ?> onclick="check_if_avail(this.id);" > Customer Reviews </label></td>
<td><label class="<?php echo $skinnyData['acftd'].' '.$skinnyData['acf'];?>" ><input type ='checkbox' name = 'rcustomfield' id='WP-Members' value='WP-Members' <?php echo $skinnyData['acf']; ?> onclick="check_if_avail(this.id);" > WP-Members </label></td>
</tr>
<tr class='typeofplugin'>
<td colspan=4><b> SEO Options </b></td>
</tr>
<tr>
<td><label class="<?php echo $skinnyData['nonerseooption'];?>" ><input type = 'radio' name ='rseooption' id='nonerseooption' value='nonerseooption' <?php echo $skinnyData['nonerseooption']; ?> class='ecommerce' onclick="check_if_avail(this.id);" > None </label></td>
<td><label class="<?php echo $skinnyData['aioseotd'].' '.$skinnyData['aioseo']; ?>" ><input type ='radio' name = 'rseooption' id='aioseo' value='aioseo' <?php echo $skinnyData['aioseo']; ?> > All-in-SEO </label></td>
<td><label class="<?php echo $skinnyData['yoasttd'].' '.$skinnyData['yoastseo']; ?>" ><input type ='radio' name = 'rseooption' id='yoastseo' value='yoastseo' <?php echo $skinnyData['yoastseo']; ?> onclick="check_if_avail(this.id);" > Yoast SEO </label></td>
</tr>
<tr class='typeofplugin'>
<td colspan=4><b> Category Icons </b></td>
</tr>
<tr>
<td><label class="<?php echo $skinnyData['enable'];?>" ><input type = 'radio' name ='rcateicons' id='caticonenable' value='enable' <?php echo $skinnyData['enable']; ?> class='ecommerce' onclick="check_if_avail(this.id);"> Enable </label> </td>
<td>
<label><input type ='radio' name = 'rcateicons' id = 'caticondisable' value='disable' <?php echo $skinnyData['disable']; ?> checked > Disable </label></td>
</tr>
</table>
<button type='submit' class='action btn btn-primary' name='savesettings' value='Save' style='float:right;margin-top:-4%;' onclick="saveSettings();">Save</button>
</div>
</div>
</div>
</div>
                       <div class="accordion-group">
<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">   <?php echo __("Features"); ?> <img src="<?php echo WP_CONTENT_URL;?>/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG;?>/images/arrow_down.gif" style="float:right;" /></a>

</div>
<div id="collapseOne" class="accordion-body collapse" style="height: 0px; ">
<div class="accordion-inner">
<div id='featuresBox' class = 'switchercontent newboxes2'>
<table>
<tr>
<td>
<label class=$automapping>
<input type='checkbox' name='automapping' id='automapping' value='automapping'  checked disabled onclick="check_if_avail(this.id);" >
Enable Auto Mapping
</label>
</td>
</tr>
<tr>
<td>
<label class=$utfsupport><input type='checkbox' name='rutfsupport' id='utfsupport' value='utfsupport' checked disabled onclick="check_if_avail(this.id);" >
Enable UTF Support</label>
</td>
</tr>
<tr>
<td>
<label style="padding:5px 0px 0px 15px;">Export Delimiter
<select name="export_delimiter">
<option>;</option>
<option>,</option>
</select>
</label>
</td>
</tr>
</table>
<button type='submit' class='action btn btn-primary' name='savesettings' value='Save' style='float:right;margin-top:-4%;' onclick="saveSettings();">Save</button>
</div>
</div>
</div>
</div>

					<div class="accordion-heading">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseFour">
                                                <?php echo __("Security"); ?> <img src="<?php echo WP_CONTENT_URL;?>/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG;?>/images/arrow_down.gif" style="float:right;" /></a>
                                </div>
                                <div id="collapseFour" class="accordion-body collapse" style="height: 0px; ">
                                        <div class="accordion-inner">
                                                <div id='securityBox' class='switchercontent newboxes2'>
							 <table class="securityfeatures">
                                                                <tr>
                                                                        <td>
                                                                                <label><input type='checkbox' name='enable_plugin_access_for_author' value='enable_plugin_access_for_author' <?php echo $skinnyData['enable_plugin_access_for_author']; ?> /> Allow authors to import </label>
                                                                        </td>
                                                                </tr>
                                                        </table>
                                   <label style='color:red;margin-top:0%;'> Note: Enable author support excerpt for users</label>
<button type='submit' class='action btn btn-primary' name='savesettings' value='Save' style='float:right;margin-top:-4.5%;' onclick="saveSettings();">Save</button>
                                                </div>
                                        </div>
                                </div>
</div>
</div>
</form>

<!-- Promotion footer for other useful plugins -->
<?php $impCE = new WPImporter_includes_helper(); ?>
<div class= "promobox" id="pluginpromo" style="width:98%;">
        <div class="accordion-group" >
<!--                <div class="accordion-heading">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"> OTHER USEFUL LINKS </a>
                </div> -->
                <div class="accordion-body in collapse">
                <div>
                        <?php // $impCE->common_footer_for_other_plugin_promotions(); ?>
                        <?php $impCE->common_footer(); ?>
                </div>
                </div>
        </div>
</div>

