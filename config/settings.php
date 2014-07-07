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

global $wpdb;
DEFINE('WP_DBNAME_CSVIMPORTER', $wpdb->dbname);
DEFINE('WP_DBHOST_CSVIMPORTER', $wpdb->dbhost);
DEFINE('WP_DBUSER_CSVIMPORTER', $wpdb->dbuser);
DEFINE('WP_DBPASSWORD_CSVIMPORTER', $wpdb->dbpassword);

class SkinnySettings { public static $CONFIG = array(


"project name"    => WP_CONST_ULTIMATE_CSV_IMP_NAME,
"debug"           => false,
"preload model"   => true,  //true = all model classes will be loaded with each request;
                            //false = model classes will be loaded only if explicitly required (use require_once)

"session persistency" => false, //tmp in your project dir must be writeable by the server!
"session timeout" => 1800, //in seconds!

"unauthenticated default module" => "default", //set this to where you want unauthenticated users redirected.
"unauthenticated default action" => "index",
);}
    
