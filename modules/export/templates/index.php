<div style="margin-top:30px;">
<div style="display:none;" id="ShowMsg"><p class="alert alert-warning" id="warning-msg"></p></div>
<form class="form-horizontal" method="post" name="exportmodule" action = "<?php echo WP_CONST_ULTIMATE_CSV_IMP_DIR; ?>modules/export/templates/export.php" onsubmit="return export_module();" >
	<div class = 'form-group'> <div class = 'col-sm-3 export_action'> <label> <input type="radio" name="export" value="post"> Post </label> </div> </div>
	<div class = 'form-group'> <div class = 'col-sm-3 export_action'> <label> <input type="radio" name="export" value="page"> Page </label> </div> </div>
        <div class = 'form-group'> <div class = 'col-sm-3 export_action'> <label> <input type="radio" name="export" value="custompost"> Custom Post </label>
	<select name="export_post_type">
	<?php
	foreach (get_post_types() as $key => $value) {
		if (($value != 'featured_image') && ($value != 'attachment') && ($value != 'wpsc-product') && ($value != 'wpsc-product-file') && ($value != 'revision') && ($value != 'nav_menu_item') && ($value != 'post') && ($value != 'page') && ($value != 'wp-types-group') && ($value != 'wp-types-user-group') && ($value != 'product') && ($value != 'product_variation') && ($value != 'shop_order') && ($value != 'shop_coupon') && ($value != 'acf')) {?>
			<option id="<?php echo($value); ?>"> <?php echo($value);?> </option>
				<?php
		}
	}
	?>	
	</select> </div>
	</div>
        <div class = 'form-group'> <div class = 'col-sm-3 export_action'> <label> <input type="radio" name="export" value="users" id='users' onclick="export_check(this.value)"> Users <span class="mandatory">*</span></label> </div> </div>
        <div class = 'form-group'> <div class = 'col-sm-3 export_action'> <label> <input type="radio" name="export" value="category" id='category' onclick="export_check(this.value)"> Category <span class="mandatory">*</span></label> </div> </div>
        <div class = 'form-group'> <div class = 'col-sm-3 export_action'> <label> <input type="radio" name="export" value="tags" id='tags' onclick="export_check(this.value)"> Tags <span class="mandatory">*</span></label> </div> </div>
        <div class = 'form-group'> <div class = 'col-sm-3 export_action'> <label> <input type="radio" name="export" value="customtaxonomy" id='customtaxonomy' onclick="export_check(this.value)" > Custom Taxonomy <span class="mandatory">*</span></label>
	<select name="export_taxo_type">
		<option>--Select--</option>       
        </select> </div>
	</div>
	<div class = 'form-group'> <div class = 'col-sm-3 export_action'> <label > <input type='radio' name='export' value='eshop' id='eshop'  onclick="export_check(this.value)"> Eshop <span class="mandatory">*</span></label> </div> </div>
	<div class = 'form-group'> <div class = 'col-sm-3 export_action'> <label> <input type="radio" name="export" id='wpcommerce'value="wpcommerce" onclick="export_check(this.value)" > Wp-Commerce <span class="mandatory">*</span></label> </div> </div>
	<div class = 'form-group'> <div class = 'col-sm-3 export_action'> <label> <input type="radio" name="export" id='woocommerce'value="woocommerce" onclick="export_check(this.value)"> Woo-Commerce <span class="mandatory">*</span></label> </div> </div>
        <div class = 'form-group'> <div class = 'col-sm-3 export_action'> <label> <input type="radio" name="export" id='marketpress' value="marketpress" onclick="export_check(this.value)"> Marketpress <span class="mandatory">*</span></label> </div> </div>
	<div class = 'form-group'> 
		<label class = 'col-sm-1 control-label'> File Name: </label>
		<div class = 'col-sm-6'> 
			<input class = 'form-control' type = 'text' name = 'export_filename' id = 'export_filename' value = '' placeholder = "exportas_<?php echo (date("Y-m-d"));?>" size="18">
		</div>
		<div class = 'col-sm-3'> <input type = "submit" name = "exportbutton" value = "Export" class = 'btn btn-primary'> </div>
</form>
</div>
<div align=center style=''><label style="font-size:1.2em;">Note:- <span class="mandatory">*</span> Available in PRO version!</label></div> 
<!-- Promotion footer for other useful plugins -->
<?php $impCE = new WPImporter_includes_helper(); ?>
<div class= "promobox" id="pluginpromo" style="width:99%;">
        <div class="accordion-group" >
                <div class="accordion-heading">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"> OTHER USEFUL LINKS </a>
                </div>
                <div class="accordion-body in collapse">
                <div>
                        <?php // $impCE->common_footer_for_other_plugin_promotions(); ?>
                        <?php $impCE->common_footer(); ?>
                </div>
                </div>
        </div>
</div>

