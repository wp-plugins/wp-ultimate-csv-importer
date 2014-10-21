<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

error_reporting(E_ALL | E_STRICT);
require('UploadHandler.php');
$current_user = wp_get_current_user();
if(is_multisite()) {
        if ( current_user_can( 'administrator' ) ) {
                if($current_user->ID != 0)
                        $upload_handler = new UploadHandler();
        }
}
else {
        if ( current_user_can( 'author' ) ) {
                $HelperObj = new WPImporter_includes_helper();
                $settings = $HelperObj->getSettings();
                if(isset($settings['enable_plugin_access_for_author']) && $settings['enable_plugin_access_for_author'] == 'enable_plugin_access_for_author') {
                        if($current_user->ID != 0)
                                $upload_handler = new UploadHandler();
                }
        } else if ( current_user_can( 'administrator' ) ) {
                if($current_user->ID != 0)
                        $upload_handler = new UploadHandler();
        }
}
