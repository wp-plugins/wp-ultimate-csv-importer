<?php $impCE = new WPImporter_includes_helper(); ?>
<div style="width:100%;">
<div class= "contactus" id="contactus">
<div class="accordion-group" >
<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"> CONTACT US </a>
</div>
<div class="accordion-body in collapse">
<div class="accordion-inner">
<form action='<?php echo admin_url().'admin.php?page='.WP_CONST_ULTIMATE_CSV_IMP_SLUG.'/index.php&__module='.$_REQUEST['__module'].'&step=sendmail2smackers'?>' id='send_mail' method='post' name='send_mail' onsubmit="return sendemail2smackers();" >
<div style='float:left;'><a class='label label-info' href='http://wordpress.org/support/plugin/wp-ultimate-csv-importer' target="_blank">WP Forum</a></div>
<div style='float:right;'><a class='label label-info' href='http://forge.smackcoders.com/projects/customer-support/issues' target="_blank">Issue Tracker</a></div><br><br>
<table class="table table-condensed">
<tr>
<td>First name <span class="mandatory">*</span></td><td><input type="text" id="firstname" placeholder="First name" name="firstname" /></td>
<td>Last name <span class="mandatory">*</span></td><td><input type="text" id="lastname" placeholder="Last name" name="lastname" />
<input type="hidden" id="smackmailid" name="smackmailid" value="info@smackcoders.com" />
</td>
</tr>
<!--<tr>
<td>From <span class="mandatory">*</span></td><td><input type="email" id="usermailid" placeholder="sample@gmail.com" name="usermailid" /></td>
<td></td><td></td>
</tr> -->
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
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"> SHARE YOUR LOVE </a>
</div>
<div class="accordion-body in collapse">
<div class="accordion-inner">
<table class="table table-condensed">
<tr>
<td>Rate Our Plugin</td>
<td>
<a href="http://wordpress.org/support/view/plugin-reviews/wp-ultimate-csv-importer?rate=5#postform" target="_blank">
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
<!--<tr>
<td><div align="center"><a href="http://www.smackcoders.com/wp-ultimate-csv-importer-pro.html" target="_blank">GO PRO NOW</a></div></td>
<td><div align="center"><a href="http://demo.smackcoders.com/demowpfour/wp-admin/admin.php?page=upload_csv_file" target="_blank">TRY PRO LIVE DEMO NOW</a></div></td>
</tr>-->
</table>
</div>
</div>
</div>
</div>
<!--<div class= "usefullinks">
<div class="accordion-group" >
<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"> NEED HELP? TRY ONE OF THE LINK BELOW.</a>
</div>
<div class="accordion-body in collapse">
<div class="accordion-inner">
<label class="plugintags"><a href="http://wiki.smackcoders.com/" target="_blank">WIKI</a></label>
<label class="plugintags"><a href="http://wiki.smackcoders.com/WP_Ultimate_CSV_Importer#FAQ" target="_blank">FAQ</a></label>
<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/wordpress-ultimate-csv-importer-plugin/" target="_blank">TUTORIALS</a></label>
<label class="plugintags"><a href="http://wiki.smackcoders.com/WP_Ultimate_CSV_Importer#Videos" target="_blank">VIDEOS</a></label>
<label class="plugintags"><a href="http://forum.smackcoders.com/" target="_blank">FORUM</a></label>
<label class="plugintags"><a href="http://blog.smackcoders.com/wordpress-ultimate-csv-importer-csv-sample-files-and-updates.html" target="_blank">SAMPLE FILES</a></label>
<label class="plugintags"><a href="http://blog.smackcoders.com/how-to-make-one-click-easy-csv-import-in-wordpress-free-cheat-sheet-downloads.html" target="_blank">CHEAT SHEETS</a></label>
<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/wordpress-ultimate-csv-importer-plugin/" target="_blank">RELATED DOWNLOADS</a></label>
<label class="plugintags"><a href="http://wiki.smackcoders.com/WP_Ultimate_CSV_Importer" target="_blank">CHANGE LOG</a></label>
<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/wordpress-ultimate-csv-importer-plugin/" target="_blank">CURRENT VERSION NEWS</a></label>
</div>
</div>
</div>
</div>  -->
<!-- <div class= "promobox" id="pluginpromo">
<div class="accordion-group" >
<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"> OTHER PLUGINS </a>
</div>
<div class="accordion-body in collapse">
<div class="accordion-inner">
<label class="plugintags"><a href="http://www.smackcoders.com/products-46/wordpress/wordpress-sugar-integration-automated-multi-web-forms-generator-pro.html" target="_blank">WordPress Sugar Integration Automated Multi Web Forms Generator Pro</a></label>
<label class="plugintags"><a href="http://www.smackcoders.com/products-46/wordpress/zoho-crm-integration-for-wordpress-automated-multi-web-forms-generator-pro-84.html" target="_blank">Zoho CRM Integration For Wordpress Multi Web Forms Generator Pro</a></label>
<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/wp-vtiger/" target="_blank">Easy Lead capture Vtiger Webforms and Contacts synchronization</a></label>
<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/" target="_blank">WP Advanced Importer</a></label>
<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/" target="_blank">Social All in One Bot</a></label>
<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/" target="_blank">A Zoho crm integerator for WordPress to capture Leads and Contacts</a></label>
<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/google-seo-author-snippet-plugin/" target="_blank">Google SEO Author Snippet Plugin</a></label>
<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/" target="_blank">Free WordPress SugarCRM Integration Advanced Multi Web Forms Creator</a></label>
</div>
</div>
</div>
</div>-->
</div>
<!-- Promotion footer for other useful plugins -->
<div class= "promobox" id="pluginpromo" style="width:99%;">
<div class="accordion-group" >
<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"> OTHER USEFUL PLUGINS BY SMACKCODERS </a>
</div>
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
