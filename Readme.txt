=== WP Ultimate CSV Importer Plugin ===
Contributors: Smackcoders
Donate link: http://www.smackcoders.com/donate.html
Tags: batch, excel, import, spreadsheet, plugin, admin, csv, importer,
Requires at least: 3.4.0
Tested up to: 3.5.1
Stable tag: 3.1.0
Version: 3.1.0
Author: smackcoders
Author URI: http://profiles.wordpress.org/smackcoders/
License: GPLv2 or later

A plugin that turns your offline data as wordpress post, page or custom post data's by simple mapping feature as csv file import.


== Description ==

       Version 3.1.0 is out now. So many major improvement changes, 
	   more flexible import options and added powerful features.    

WP Ultimate CSV Importer Plugin helps you to import any CSV file as post, page or even as custom post type. Convert your offline database maintained for years into your valuable website content. Also do periodical content changes, maintenance, content update, prices, offers, coupons and inventory etc.

This simple but ultimate plugin as you can import everything needed to create as any WP post types from admin as simple as CSV file. Just in few clicks to map your CSV data set to match the Wordpress fields to import. That’s all, now your imports are turned as Wordpress site content in matter of seconds. No more pain of creating post content one by one of converting your offline data. You can import all the essential data as Wordpress post and skip unwanted things. Or you can import as many as custom fields without missing even a single data. Provided you should have a theme that supports custom fields. So you can now make use of your offline data, inventory, price catalog, information data, database and any other excel data sheet for online content distribution to your valuable visitor or customers. 

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


<p>http://www.youtube.com/embed/OwKdt_NlT2U?list=PL2k3Ck1bFtbQqFhOK7g08kxENI4qQkmC-</p>


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


Support and Feature request.
----------------------------

Please create issues only in our tracker http://forge.smackcoders.com/projects/wp-ultimate-csv-importer-free/issues instead of WordPress support forum. 
For guides and tutorials, visit http://forge.smackcoders.com/projects/wp-ultimate-csv-importer-free . 



== Installation ==


Please click here for [Detailed Installation Instructions](http://www.smackcoders.com/blog/how-to-guide-for-free-wordpress-ultimate-csv-importer-plugin.html)
Or view our hoe to guide video guide in our [Youtube Channel](www.youtube.com/user/smackcoders)


== Screenshots ==

1. Admin settings for WP Ultimate CSV Importer Plugin .
2. Admin settings for Import Data and Header Mapping configuration to import data's from a csv file.



== Changelog ==

=v 3.1.0 = Much Improved Featured Image feature
        -- Image url for featured image issues fixed
		-- PHP 5.4 upgrade fix
		
= 3.0.0 = Lot of performance improvements
		- Much improved workflow
		- Custom Field mapping and import fixed
		- Add custom field option improved.
		- Date format handling improved
		- Any Date format is supported now
		- Future scheduling and status improved
		- Overall Status option improved and issue fixed
		- Password field fixed for Protected
		- Status as in CSV option improved and fixed
		- Can apply post status for individual post via csv itself
		- Now password should be mentioned as {password}
		- Featured image handling improved and fixed. More improvement are scheduled.
		- Category in numericals are restricted and skipped to Uncategorized
		- Duplicate check options improved for both title and content option.
		- Post authors can be User ID or name 
		- Post author issue fixed and improved
		- Wrong user id or name are automatically assigned under admin
		- Multi category and tags improved

= 2.7.0 =  Post Status 
		-- Added more post status options 
		-- Publish, Sticky, Private, Draft and Pending Status for whole import
		-- Protected status with a common password option added
		-- "Status as in CSV" to assign status for individual psot thorugh CSV as ID or Field Tag
		
		Post Author 
		-- User ID and User Name support for Post author feature added
		-- In case of missing or false IDs post assigned to admin as draft
		-- 
		
		Extra date formats support added.
		-- 6 Standard date format added as dropdown to choose.
		-- Date format conflict at import fixed.
		
		Custom field feature improved.
		-- Listed custom fields with prefix as CF: Name for easy identification.
		-- Add Custom Field Textbox autofilled with CSV header tag.
        
		Added Feature
		-- Duplicate detection for post content and post title added as options.
		-- User can choose either one or both to avoid duplicate issues.
		
		Post Slug
		-- Renamed post_name as post_slug to avoid confusion
		
		Mapping Fields
		-- Field tags are formatted to support auto mapping option (next milestone)


= 2.6.0 =	Major Bug fixed
		-- Added UTF-8 support.
		-- Fixed Html tag conflicts.

= 2.5.0 = 	Major issues fixed and updated to WordPress-3.5.1 compatibility.

= 2.0.1 =	Update to WordPress-3.5 compatibility.

= 2.0.0 =	WPDEBUG errors fixed. CSV import folder changed to WP native uploads folder.

= 1.1.1 =	Renamed the mapping field attachment as featured_image and category as post_category.

= 1.1.0 =	Added featured image import feature along with post/page/custom post.

= 1.0.2 = Bug fixed to recognize the trimmed trailing space in the CSV file 
		- Added validation for the duplicate field mapping.

= 1.0.1 =	Added features to import multiple tags and categories with different delimiters.

= 1.0.0 =	Initial release version. Tested and found works well without any issues.




== Upgrade Notice ==

=v 3.1.0 = Now Much Improved Featured Image and url handling

=v 3.0.0 = Must upgrade to have Major improvements, performance fixes and issue fixes

=v 2.7.0 = Major improvements and feature changes.

=v 2.6.0 = Bug fixed and should upgrade.

=v 2.5.0 = Issues fixed and updated to WordPress-3.5.1 compatibility.
		-- Duplicate detection added.
		-- Added more information in success message.
		-- Import memory issues solved.

=v 2.0.1 =	Update to WordPress-3.5 compatibility.

=v 2.0.0 =	Major Bug fixed and should upgrade. WPDEBUG errors fixed. CSV import folder changed to WP native uploads folder.

=v 1.1.1 =	Minor correction and fix applied.

=v 1.1.0 = 	A major new feature added in this version. Update needed.

=v 1.0.2 =	This version have important bug fixes and newly added features. Must be upgrade immediately.

=v 1.0.1 =	Added features to import multiple tags and categories with different delimiters.

=v 1.0.0 =	Initial release of plugin. 




== Frequently Asked Questions ==
How to Format a CSV file for WP Ultimate CSV Importer Plugin?

This video helps you to tweak/format your CSV file to make a fast, simple and easy import using WP Ultimate CSV importer plugin without missing all the 
features. We have used MS Excel as CSV editor here. You can use other applications also. Take advantage of our importer format by slightly tweaking your csv.

<p>http://www.youtube.com/watch?v=pnObJdiedus</p>

For more details visit www.smackcoders.com

How to creat a well formatted csv? - the other way to learn tweaking

We have used text editor here to explain how to create a well formatted csv in seconds for importing. You can use this format to take advantage of our importer format by slightly tweaking your csv. This video helps you to tweak/format your CSV file to make a fast, simple and easy import using WP Ultimate CSV importer plugin without missing all the features.

<p>http://www.youtube.com/watch?v=9W_my0rSybE</p>

Please click here for [ Detailed Frequently Asked Questions](http://www.smackcoders.com/blog/category/free-wordpress-plugins/wordpress-ultimate-csv-importer-plugin/)

For quick response and reply please create issues in our [support](http://forge.smackcoders.com/projects/wp-ultimate-csv-importer-free/issues) instead of WordPress support forum.




