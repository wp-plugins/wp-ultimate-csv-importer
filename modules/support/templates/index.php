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

$impCE = new WPImporter_includes_helper();
?>

<div style="width:99%;">
<div class= "contactus" id="contactus">
<div class="accordion-group" >
<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"> Contact Us </a>
</div>
<div class="accordion-body in collapse" style="height=292px;">
<div class="accordion-inner">
<form action='<?php echo admin_url().'admin.php?page='.WP_CONST_ULTIMATE_CSV_IMP_SLUG.'/index.php&__module='.$_REQUEST['__module'].'&step=sendmail2smackers'?>' id='send_mail' method='post' name='send_mail' onsubmit="return sendemail2smackers();" >
<table class="table table-condensed">
<tr>
<td>First name <span class="mandatory">*</span></td><td><input type="text" id="firstname" placeholder="First name" name="firstname" /></td>
<td>Last name <span class="mandatory">*</span></td><td><input type="text" id="lastname" placeholder="Last name" name="lastname" />
<input type="hidden" id="smackmailid" name="smackmailid" value="csv-support@smackcoders.com" />
</td>
</tr>
<tr>
<td>Related To</td>
<td colspan=3>
<select name="subject">
<option>Support</option>
<option>Feature Request</option>
<option>Customization</option>
</select>
</td>
</tr>
<tr>
<td>Message <span class="mandatory">*</span></td>
<td colspan=3>
<textarea class="form-control" rows="3" name="message" id="message"></textarea>
</td>
</tr>
</table>
<div style="float:right;padding:10px;"><input class="btn btn-primary" type="submit" name="send_mail" /></div>
</form>
</div>
</div>

</div>
</div>
<div style="float:right;" id="promobox">
<div class= "promobox">
<div class="accordion-group" >
<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"> Share Your Love </a>
</div>
<div class="accordion-body in collapse">
<div class="accordion-inner">
<table class="table table-condensed">
<tr>
<td>Rate Our Plugin</td>
<td>
<a href="http://wordpress.org/support/view/plugin-reviews/wp-ultimate-csv-importer" target="_blank">
<ul class="stars">
    <li>1</li>
    <li>2</li>
    <li>3</li>
    <li>4</li>
    <li>5</li>
</a>
</ul>
</td>
</tr>
<tr>
<td>Social Share</td>
<td>
<?php $impCE->importer_social_profile_share(); ?>
</td>
</tr>
<tr>
<td colspan=2><div align="center"><a href="http://www.smackcoders.com/donate.html" target="_blank"><img src="<?php echo WP_CONTENT_URL; ?>/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG; ?>/images/donatenow.png" width=75 /></a></div></td>
</tr>
</table>
</div>
</div>
</div>
<div class="accordion-group" >
<div class="accordion-body in collapse">
<div class="accordion-inner" align="center">
<a href = "http://wordpress.org/plugins/wp-zoho-crm/"  style= "" ><img src="<?php echo WP_CONST_ULTIMATE_CSV_IMP_DIR ?>images/zohocrm.jpg" width=110px; height=110px; /> </a> 
<a href = "http://wordpress.org/plugins/wp-sugar-free/" style= ""><img src="<?php echo WP_CONST_ULTIMATE_CSV_IMP_DIR ?>images/sugarcrm.png" width=110px; height=110px;/> </a> 
<a href = "http://wordpress.org/plugins/wp-tiger/" style= ""><img src="<?php echo WP_CONST_ULTIMATE_CSV_IMP_DIR ?>images/tigercrm.png" width=110px;height=110px;/> </a>
</div>
</div>
</div>
</div>
</div>
<!-- Promotion footer for other useful plugins -->
<div class= "promobox" id="pluginpromo" style="width:99%;">
<div class="accordion-group" >
<div class="accordion-body in collapse">
<div>
<?php $impCE->common_footer_for_other_plugin_promotions(); ?>
</div>
</div>
</div>
</div>

<?php
 /* Put your code here */ 

?>
