<?php $impCE = new WPImporter_includes_helper(); ?>
<div style="width:100%;">
    <div class="contactus" id="contactus">
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                    CONTACT US </a>
            </div>
            <div class="accordion-body in collapse">
                <div class="accordion-inner">
                    <form
                        action='<?php echo admin_url() . 'admin.php?page=' . WP_CONST_ULTIMATE_CSV_IMP_SLUG . '/index.php&__module=' . $_REQUEST['__module'] . '&step=sendmail2smackers' ?>'
                        id='send_mail' method='post' name='send_mail' onsubmit="return sendemail2smackers();">
                        <div style='float:left;'><a class='label label-info'
                                                    href='http://wordpress.org/support/plugin/wp-ultimate-csv-importer'
                                                    target="_blank">WP Forum</a></div>
                        <div style='float:right;'><a class='label label-info'
                                                     href='http://forge.smackcoders.com/projects/customer-support/issues'
                                                     target="_blank">Issue Tracker</a></div>
                        <br><br>
                        <table class="table table-condensed">
                            <tr>
                                <td>First name <span class="mandatory">*</span></td>
                                <td><input type="text" id="firstname" placeholder="First name" name="firstname"/></td>
                                <td>Last name <span class="mandatory">*</span></td>
                                <td><input type="text" id="lastname" placeholder="Last name" name="lastname"/>
                                    <input type="hidden" id="smackmailid" name="smackmailid"
                                           value="info@smackcoders.com"/>
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
                        <div style="float:right;padding:10px;"><input class="btn btn-primary" type="submit"
                                                                      name="send_mail"/></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div style="float:right;" id="promobox">
        <div class="promobox">
            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                        SHARE YOUR LOVE </a>
                </div>
                <div class="accordion-body in collapse">
                    <div class="accordion-inner">
                        <table class="table table-condensed">
                            <tr>
                                <td>Rate Our Plugin</td>
                                <td>
                                    <a href="http://wordpress.org/support/view/plugin-reviews/wp-ultimate-csv-importer"
                                       target="_blank">
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
                                <td colspan=2>
                                    <div align="center"><a href="http://www.smackcoders.com/donate.html"
                                                           target="_blank"><img
                                                src="<?php echo WP_CONTENT_URL; ?>/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG; ?>/images/donatenow.png"
                                                width=75/></a></div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Promotion footer for other useful plugins -->
    <div class="promobox" id="pluginpromo" style="width:99%;">
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"> OTHER
                    USEFUL PLUGINS BY SMACKCODERS </a>
            </div>
            <div class="accordion-body in collapse">
                <div>
                    <?php $impCE->common_footer_for_other_plugin_promotions(); ?>
                </div>
            </div>
        </div>
    </div>