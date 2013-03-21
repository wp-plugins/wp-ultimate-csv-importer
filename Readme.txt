=== WP Ultimate CSV Importer Plugin ===
Contributors: Smackcoders
Donate link: http://www.smackcoders.com/donate.html
Tags: batch, excel, import, spreadsheet, plugin, admin, csv, importer,
Requires at least: 3.4
Tested up to: 3.5.1
Stable tag: 2.6.0
Version: 2.6.0
Author: Smackcoders
Author URI: http://profiles.wordpress.org/smackcoders/
License: GPLv2 or later

A plugin that helps to import any csv file as post, page or custom post data's by matching csv headers to wp fields.


== Description ==

WP Ultimate CSV Importer Plugin helps you to import any csv file as post, page or even as custom post data's by matching its headers to relevant fields or custom fields.

1. Admin can import the data's from any csv file. 
2. Can define the type of post and post status while importing.
3. Provides header mapping feature to import the data's as your need.
4. Users can map column headers to existing fields or assign as custom fields.
5. Import unlimited data as post.
6. Make imported post as published or make it as draft.
7. Added featured image import functionality.

The pro version of this plugin is available now with lot more new features, functionalities, controls and usability. Please upgrade to pro version to enjoy the powerful features like importing nested categories, WP-e-commerce products, eShop products, custom taxonomies in bulk with simple clicks.

Important Note:  To import your posts for scheduled publishing in future, have a date coloumn in your csv with the date of post to be published on. If the date is a future date, then the post will be automatically scheduled for publishing on particular date as mentioned in csv.

Note: Your theme should support featured image function. If not, please add the following code to header.php or where it needed.
		add_theme_support( 'post-thumbnails' );

You can follow the instructions as given here 
	[http://codex.wordpress.org/Function_Reference/the_post_thumbnail](http://codex.wordpress.org/Function_Reference/the_post_thumbnail)
    [http://codex.wordpress.org/Post_Thumbnails](http://codex.wordpress.org/Post_Thumbnails)	[http://wordpress.org/support/topic/featured-image-not-showing-7?replies=5](http://wordpress.org/support/topic/featured-image-not-showing-7?replies=5)

Posts and Pages Module - This module will import all your data into bulk posts or pages. There are 9 fields to map, in which post title and content are mandatory. All other fields are optional. You can also have published date field which can also have a future date that reflects as scheduled post. Optionally you can import as many fields you want as custom fields. You can name these fields as your wish while importing. Pages don't need category and tags. You can assign a feature image for each post or page through a list of image urls.

Custom posts - Similar to post and pages you can import any custom post types that is configured in your WordPress. You can also assign feature image for each post created.
	
For more powerful features upgrade to pro version of ultimate csv importer plugin have many more features like

Category - You can import any number category you want. Category name fields are mandatory. Slug and description field is optional. Slugs are created automatically if not mapped.

Nested category - This module is just like category module, provided you can import nested categories. You can import nested categories through name field like category1 | category2 | category3. If the category doesn't exist it will be created in a hierarchy as mention in name field.

Tags - Import bulk tags using this module and assign to post, custom post or products.

Users with roles - Import bulk users with their roles by role id. There 11 fields to map in which user login, email and role are mandatory fields. Roles are mentioned in CSV as ids. Please ensure roles are created in advance before import. Other wise default role is assigned for missing role ids.

Custom taxonomy - Is your Wordpress is configured for custom taxonomy, you can import bulk custom taxonomies as like as nested categories.

WP Commerce/ eshop - You can import products in bulk if these modules are installed in your Wordpress. 


Support and Feature request.
----------------------------

Please create issues only in our tracker http://forge.smackcoders.com/projects/wp-ultimate-csv-importer-free/issues instead of WordPress support forum. 

For guides and tutorials, visit http://forge.smackcoders.com/projects/wp-ultimate-csv-importer-free . 



== Installation ==


Please click here for [Detailed Installation Instructions](http://www.smackcoders.com/blog/category/free-wordpress-plugins/wordpress-ultimate-csv-importer-plugin/)



== Screenshots ==

1. Admin settings for WP Ultimate CSV Importer Plugin .
2. Admin settings for Import Data and Header Mapping configuration to import data's from a csv file.



== Changelog ==

= 2.6.0 =	Major Bug fixed
		-- Added UTF-8 support.
		-- Fixed Html tag conflicts.

= 2.5.0 = 	Major issues fixed and updated to WordPress-3.5.1 compatibility.

= 2.0.1 =	Update to WordPress-3.5 compatibility.

= 2.0.0 =	WPDEBUG errors fixed. CSV import folder changed to WP native uploads folder.

= 1.1.1 =	Renamed the mapping field attachment as featured_image and category as post_category.

= 1.1.0 =	Added featured image import feature along with post/page/custom post.

= 1.0.2 =	- Bug fixed to recognize the trimmed trailing space in the CSV file 
            	- Added validation for the duplicate field mapping.

= 1.0.1 =	Added features to import multiple tags and categories with different delimiters.

= 1.0.0 =	Initial release version. Tested and found works well without any issues.




== Upgrade Notice ==

=v 2.6.0 =	Major Bug fixed and should upgrade.

=v 2.5.0 =	Major issues fixed and updated to WordPress-3.5.1 compatibility.
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

Please click here for [ Detailed Frequently Asked Questions](http://www.smackcoders.com/blog/category/free-wordpress-plugins/wordpress-ultimate-csv-importer-plugin/)

For quick response and reply please create issues in our [support](http://forge.smackcoders.com/projects/wp-ultimate-csv-importer-free/issues) instead of WordPress support forum.




