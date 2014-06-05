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

<div style="margin-top:30px;">
    <div style="display:none;" id="ShowMsg"><p class="alert alert-warning" id="warning-msg"></p></div>
    <form class="form-horizontal" method="post" name="exportmodule"
          action="<?php echo WP_CONST_ULTIMATE_CSV_IMP_DIR; ?>modules/export/templates/export.php"
          onsubmit="return export_module();">
        <div class='form-group'>
            <div class='col-sm-3 export_action'><label> <input type="radio" name="export" value="post"> Post </label>
            </div>
        </div>
        <div class='form-group'>
            <div class='col-sm-3 export_action'><label> <input type="radio" name="export" value="page"> Page </label>
            </div>
        </div>
        <div class='form-group'>
            <div class='col-sm-3 export_action'><label> <input type="radio" name="export" value="custompost"> Custom
                    Post </label>
                <select name="export_post_type">
                    <?php
                    foreach (get_post_types() as $key => $value) {
                        if (($value != 'featured_image') && ($value != 'attachment') && ($value != 'wpsc-product') && ($value != 'wpsc-product-file') && ($value != 'revision') && ($value != 'nav_menu_item') && ($value != 'post') && ($value != 'page') && ($value != 'wp-types-group') && ($value != 'wp-types-user-group') && ($value != 'product') && ($value != 'product_variation') && ($value != 'shop_order') && ($value != 'shop_coupon') && ($value != 'acf')) {
                            ?>
                            <option id="<?php echo($value); ?>"> <?php echo($value); ?> </option>
                        <?php
                        }
                    }
                    ?>
                </select></div>
        </div>
        <div class='form-group'>
            <div class='col-sm-3 export_action'><label> <input type="radio" name="export" value="users" id='users'
                                                               onclick="export_check(this.value)"> Users <span
                        class="mandatory">*</span></label></div>
        </div>
        <div class='form-group'>
            <div class='col-sm-3 export_action'><label> <input type="radio" name="export" value="category" id='category'
                                                               onclick="export_check(this.value)"> Category <span
                        class="mandatory">*</span></label></div>
        </div>
        <div class='form-group'>
            <div class='col-sm-3 export_action'><label> <input type="radio" name="export" value="tags" id='tags'
                                                               onclick="export_check(this.value)"> Tags <span
                        class="mandatory">*</span></label></div>
        </div>
        <div class='form-group'>
            <div class='col-sm-3 export_action'><label> <input type="radio" name="export" value="customtaxonomy"
                                                               id='customtaxonomy' onclick="export_check(this.value)">
                    Custom Taxonomy <span class="mandatory">*</span></label>
                <select name="export_taxo_type">
                    <option>--Select--</option>
                </select></div>
        </div>
        <div class='form-group'>
            <div class='col-sm-3 export_action'><label> <input type='radio' name='export' value='eshop' id='eshop'
                                                               onclick="export_check(this.value)"> Eshop <span
                        class="mandatory">*</span></label></div>
        </div>
        <div class='form-group'>
            <div class='col-sm-3 export_action'><label> <input type="radio" name="export" id='wpcommerce'
                                                               value="wpcommerce" onclick="export_check(this.value)">
                    Wp-Commerce <span class="mandatory">*</span></label></div>
        </div>
        <div class='form-group'>
            <div class='col-sm-3 export_action'><label> <input type="radio" name="export" id='woocommerce'
                                                               value="woocommerce" onclick="export_check(this.value)">
                    Woo-Commerce <span class="mandatory">*</span></label></div>
        </div>
        <div class='form-group'>
            <div class='col-sm-3 export_action'><label> <input type="radio" name="export" id='marketpress'
                                                               value="marketpress" onclick="export_check(this.value)">
                    Marketpress <span class="mandatory">*</span></label></div>
        </div>
        <div class='form-group'>
            <label class='col-sm-1 control-label'> File Name: </label>

            <div class='col-sm-6'>
                <input class='form-control' type='text' name='export_filename' id='export_filename' value=''
                       placeholder="exportas_<?php echo(date("Y-m-d")); ?>" size="18">
            </div>
            <div class='col-sm-3'><input type="submit" name="exportbutton" value="Export" class='btn btn-primary'></div>
    </form>
</div>
<div align=center style=''><label style="font-size:1.2em;">Note:- <span class="mandatory">*</span> Available in PRO
        version!</label></div>
<!-- Promotion footer for other useful plugins -->
<?php $impCE = new WPImporter_includes_helper(); ?>
<div class="promobox" id="pluginpromo" style="width:99%;">
    <div class="accordion-group">
        <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"> OTHER
                USEFUL LINKS </a>
        </div>
        <div class="accordion-body in collapse">
            <div>
                <?php // $impCE->common_footer_for_other_plugin_promotions(); ?>
                <?php $impCE->common_footer(); ?>
            </div>
        </div>
    </div>
</div>

