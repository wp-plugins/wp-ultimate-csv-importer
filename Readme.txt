=== WP Ultimate CSV Importer Plugin ===
Contributors: smackcoders
Donate link: http://www.smackcoders.com/donate.html
Tags: batch, excel, import, spreadsheet, plugin, admin, csv, importer,
Requires at least: 3.4.0
Tested up to: 3.8
Stable tag: 3.3.0
Version: 3.3.0
Author: smackcoders
Author URI: http://profiles.wordpress.org/smackcoders/
License: GPLv2 or later

A plugin that turns your offline data as wordpress post, page or custom post data's by simple mapping feature as csv file import.


== Description ==

Version 3.3.0 is now available with WP 3.8 compatibility and added bulk user,comments import feature.
[Please visit for guides and tutorials.] (http://www.smackcoders.com/category/free-wordpress-plugins.html)

WP Ultimate CSV Importer Plugin helps you to import any CSV file as post, page or even as custom post type. Convert your offline database maintained for years into your valuable website content. Also do periodical content changes, maintenance, content update, prices, offers, coupons and inventory etc.

This simple but ultimate plugin as you can import everything needed to create as any WP post types from admin as simple as CSV file. Just in few clicks to map your CSV data set to match the Wordpress fields to import. That�s all, now your imports are turned as Wordpress site content in matter of seconds. No more pain of creating post content one by one of converting your offline data. You can import all the essential data as Wordpress post and skip unwanted things. Or you can import as many as custom fields without missing even a single data. Provided you should have a theme that supports custom fields. So you can now make use of your offline data, inventory, price catalog, information data, database and any other excel data sheet for online content distribution to your valuable visitor or customers. 

1.	Admin can import the data's from any CSV file. 
2.	Can import as post, page or custom post. 
3.	Compatible with Custom post type UI plugin support
4.	Can define the type of post and post status while importing.
5.	Powerful mapping feature enable importing the data's as perfect Wordpress post types.
6.	Users can map column headers to existing fields or create and assign as unlimited custom fields.
7.	Import unlimited data as any post type.
8.	Make imported post as published, private, pending, draft, sticky or even as password protected.
9.	Define different post status for every individual post via CSV.
10.	Add featured image Url to every post.
11.	Assign authors to every post.
12.	Add title, content, excerpt and slug to posts
13.	Assign multiple tags and categories to post
14.	Non existing tags and categories are created automatically
15.	Assign date of publishing either a previous date or futures date for scheduled publishing.
16.	Skip Duplicate titles or content or both to avoid duplicates at time of import itself
17.	Only option we missed is post format, will be added in next major update.

<p>http://www.youtube.com/watch?v=OwKdt_NlT2U&list=PL2k3Ck1bFtbQqFhOK7g08kxENI4qQkmC</p>


Important Notes:  
You can schedule your post for future publishing automatically by mentioning futuredate and time in date field of partcular post in your csv file.
Your theme should support featured image function. If not, please add the following code to header.php or where you need to diplay. 	add_theme_support( 'post-thumbnails' );
You can follow the instructions as given here 
	[http://codex.wordpress.org/Function_Reference/the_post_thumbnail](http://codex.wordpress.org/Function_Reference/the_post_thumbnail)
    [http://codex.wordpress.org/Post_Thumbnails](http://codex.wordpress.org/Post_Thumbnails)	[http://wordpress.org/support/topic/featured-image-not-showing-7?replies=5](http://wordpress.org/support/topic/featured-image-not-showing-7?replies=5)

Posts and Pages Module - This module will import all your data into bulk posts or pages. There are 9 fields to map, in which post title and content are mandatory. All other fields are optional. You can also have published date field which can also have a future date that reflects as scheduled post. Optionally you can import as many fields you want as custom fields. You can name these fields as your wish while importing. Pages don't need category and tags. You can assign a feature image for each post or page through a list of image urls.

Custom posts - Similar to post and pages you can import any custom post types that is configured in your WordPress. You can also assign feature image for each post created.
	
The pro version of this plugin is available now with lot more new features, functionalities, controls and improved usability. Please upgrade to pro version to enjoy the powerful features like importing nested categories, WP-e-commerce products, eShop products, custom taxonomies in bulk with simple clicks.For more powerful features upgrade to pro version of ultimate csv importer plugin have many more features like

One click Import of Nested category with complex hierarchies to any no. of levels with proper description and seo slugs
One click Import of bulk tags with proper description and seo slugs
One click Import of Users with roles
One click Import of Custom taxonomies with proper description and seo slugs
Import, update and maintain your WP Commerce / eshop inventory, prices, periodical and short term offers, coupons, bonus etc.



== Installation ==
Wp Ultimate CSV Importer is very easy to install like any other wordpress plugin. No need to edit or modify anything here.

1.    Unzip the file 'wp-ultimate-csv-importer.zip'.
2.    Upload the ' wp-ultimate-csv-importer ' directory to '/wp-content/plugins/' directory using ftp client or upload and install wp-ultimate-csv-importer.zip through plugin install wizard in wp admin panel .
3.    Activate the plugin through the 'Plugins' menu in WordPress.
4.    After activating, you will see an option for 'Wp Ultimate CSV Importer' in the admin menu (left navigation) and you will import the csv files to import the data's.

Please click here for [Detailed Installation Instructions](http://www.smackcoders.com/blog/how-to-guide-for-free-wordpress-ultimate-csv-importer-plugin.html)
Or view our how to guide video guide in our [Youtube Channel](www.youtube.com/user/smackcoders)


== Screenshots ==

1. Browse and Import CSV with delimiter
2. Explained -How to CSV Mapping Headers works and how CSV can be related to wordpress fields?
3. Simple click mapping option to relate csv field headers with wordpress post type fields
4. Wp Ultimate CSV Importer Settings 
5. Wp Ultimate CSV Importer Dashboard, Reports.

== Frequently Asked Questions ==

1. How to install the plugin?
Like other plugins wp-ultimate-csv-importer is easy to install. Upload the wp-ultimate-csv-importer.zip file through plugin install page through wp admin. Everything will work fine with it.

2. How to use the plugin?
After plugin activation you can see the ' Wp Ultimate CSV Importer ' menu in admin backend.
1)Browse csv file to import the data's.
2)You can mapping the headers to import the data's.
3)If you checked the import as draft,it will import as draft then you can publish the data's later.
4)The data's are imported based on the post type you selected.
Configuring our plugin is as simple as that.

3. What to do when an import broke in the middle of import?
Check your CSV format. It should be UTF-8. If you get memory related issue, change or create	a custom php.ini with increased value for max_execution_time and memory limt
   
4. I am cant get my featured image imported?
Check that allow_url_fopen is allowed in your php ini. If not, request your hosting or you can enable it in custom php ini settings. 
	
5. How to import other languages using ultimate csv importer?
It should strictly UTF-8 format. Users have reported that utf-8 without BOM works well for languages like Polish characters etc. Follow these simple steps to import other languages. Thanks for the steps mojeprogramy.com.
1) Prepare a CSV file with the data (I used Excel)
2) Saving files to CSV (Excel function)
3) I opened the CSV file in Notepad + +
4) CTR + A, CTR + C (copy all the text)
5) The "Encoding"> "Encode without BOM"
6) CTR + V to paste all text
7) Save the file
8) Imports for WordPress ;) 

6. How to Format a CSV file for WP Ultimate CSV Importer Plugin?
This video helps you to tweak/format your CSV file to make a fast, simple and easy import using WP Ultimate CSV importer plugin without missing all the 
features. We have used MS Excel as CSV editor here. You can use other applications also. Take advantage of our importer format by slightly tweaking your csv.
<p>http://www.youtube.com/watch?v=pnObJdiedus</p>
For more details visit www.smackcoders.com

7. How to creat a well formatted csv? - the other way to learn tweaking
We have used text editor here to explain how to create a well formatted csv in seconds for importing. You can use this format to take advantage of our importer format by slightly tweaking your csv. This video helps you to tweak/format your CSV file to make a fast, simple and easy import using WP Ultimate CSV importer plugin without missing all the features.
<p>http://www.youtube.com/watch?v=9W_my0rSybE</p>

8. Where can i get sample files and other references?
We have a set of sample/template files for different purposes with supported plugin fields. All the sample/templates are auto mapping headers enabled which will almost removes manual mapping. [You can find updated news and links to download here] (http://www.smackcoders.com/category/free-wordpress-plugins.html). You can use these sample/template files to prepare your CSV to make your import just in few clicks
Post & Page
Post with All in SEO fields 
Post with SEO by YOAST fields
Post with Advanced custom fields
Custom Posts for Custom post type UI plugin
Custom Posts for CCTM plugin
Custom Posts for Types plugin
Nested Category 
Nested Category with category icons
Tags and Custom Taxonomies
Users with roles
Advanced eshop import
WP ecommerce import
WP ecommerce import with wp ecommerce custom fields,
Advanced Woo commerce import

Please click here for [ More Details](http://www.smackcoders.com/blog/category/free-wordpress-plugins.html)


== Changelog ==

= 3.3.0 =
* Added: WordPress 3.8 compatibility.
* Added: Bulk users with role import feature.
* Added: Comments import feature with relevant post ids.

= 3.2.3 = 
* Added: WordPress 3.7.1 compatibility added.
* Added: Different media path support added.
* Added: Sub folder installations support added.
* Improved: Updated plugin directory path.
* Improved: Removed unwanted warnings.
* Improved: Performance check.

= 3.2.2 = 
* Added: WordPress 3.6.1 compatibility added.
* Added: Mapping UI improved with on select dynamic update feature
* Added: Help content added
* Fixed: Post slug issue fixed and tested for 3.6 and 3.6.1

= 3.2.1 = 
* Improved: Performance improvements on SQL and CSV parsing
* Fixed: Plugin deactivation issue fixed and updated the code.
* Fixed: Links in the cells makes problems with the "quote"
* Fixed: Loading content from more than one colunm
* Fixed: Custom Post type issues fixed

= 3.2.0 = 
* Improved: User interface improvements
* Improved:WordPress 3.6 compatibility added, Much Improved UI.
* Fixed: Featured image issues fixed for WordPress-3.6.

= 3.1.0 = 
* Improved: Much Improved Featured Image feature
* Fixed: Image url for featured image issues fixed
* Fixed: PHP 5.4 upgrade fix
		
= 3.0.0 = 
* Added: Category in numericals are restricted and skipped to Uncategorized
* Added: Now password should be mentioned as {password}
* Added: Post authors can be User ID or name 
* Improved: Much improved workflow
* Improved: Add custom field option improved.
* Improved: Date format handling improved
* Improved: Any Date format is supported now
* Improved: Future scheduling and status improved
* Improved: Can apply post status for individual post via csv itself
* Improved: Featured image handling improved and fixed. More improvement are scheduled.
* Improved: Duplicate check options improved for both title and content option.
* Improved: Post author issue fixed and improved
* Improved: Wrong user id or name are automatically assigned under admin
* Improved: Multi category and tags improved
* Fixed: Custom Field mapping and import fixed
* Fixed: Overall Status option improved and issue fixed
* Fixed: Password field fixed for Protected
* Fixed: Status as in CSV option improved and fixed

= 2.7.0 =  
	
* Added: Added more post status options 
* Added: Publish, Sticky, Private, Draft and Pending Status for whole import
* Added: Protected status with a common password option added
* Added: "Status as in CSV" to assign status for individual psot thorugh CSV as ID or Field Tag		
* Added: User ID and User Name support for Post author feature added
* Added: In case of missing or false IDs post assigned to admin as draft
* Added: Add Custom Field Textbox autofilled with CSV header tag.
* Added: Duplicate detection for post content and post title added as options.
* Added: User can choose either one or both to avoid duplicate issues.
* Improved: 6 Standard date format added as dropdown to choose.
* Improved: Renamed post_name as post_slug to avoid confusion	
* Improved: Mapping Fields
* Improved: Field tags are formatted to support auto mapping option (next milestone)
* Improved: Listed custom fields with prefix as CF: Name for easy identification.
* Fixed: Date format conflict at import fixed.


= 2.6.0 =	
* Fixed: Major Bug fixed
* Fixed: Added UTF-8 support.
* Fixed: Fixed Html tag conflicts.

= 2.5.0 = 	
* Major issues fixed and updated to WordPress-3.5.1 compatibility.

= 2.0.1 =	
* Update to WordPress-3.5 compatibility.

= 2.0.0 =	
* WPDEBUG errors fixed. CSV import folder changed to WP native uploads folder.

= 1.1.1 =	
* Renamed the mapping field attachment as featured_image and category as post_category.

= 1.1.0 =	
* Added featured image import feature along with post/page/custom post.

= 1.0.2 = 
* Bug fixed to recognize the trimmed trailing space in the CSV file 
* Added validation for the duplicate field mapping.

= 1.0.1 =	
* Added features to import multiple tags and categories with different delimiters.

= 1.0.0 =	
* Initial release version. Tested and found works well without any issues.




== Upgrade Notice ==

= 3.3.0 =
* Upgrade now for WP 3.8 compatibility and added bulk user,comments feature.

= 3.2.3 = 
* Upgrade for WordPress 3.7.1 compatibility and minor bug fixes

= 3.2.2 = 
* WordPress 3.6.1 compatibile, bug fix and UI improvements

= 3.2.1 = 
* Performance improvements on SQL and CSV parsing

= 3.2.0 = 
* Now compatible with 3.6 and improved featured image

= 3.1.0 = 
* Now Much Improved Featured Image and url handling

= 3.0.0 = 
* Must upgrade to have Major improvements, performance fixes and issue fixes

= 2.7.0 = 
* Major improvements and feature changes.

= 2.6.0 = 
* Bug fixed and should upgrade.

= 2.5.0 = 
* Duplicate detection added.
* Added more information in success message.
* Import memory issues solved.

= 2.0.1 =	
* Update to WordPress-3.5 compatibility.

= 2.0.0 =	
* Major Bug fixed and should upgrade. WPDEBUG errors fixed. CSV import folder changed to WP native uploads folder.

= 1.1.1 =	
* Minor correction and fix applied.

= 1.1.0 = 	
* A major new feature added in this version. Update needed.

= 1.0.2 =	
* This version have important bug fixes and newly added features. Must be upgrade immediately.

= 1.0.1 =	
* Added features to import multiple tags and categories with different delimiters.

= 1.0.0 =	
* Initial release of plugin. 








