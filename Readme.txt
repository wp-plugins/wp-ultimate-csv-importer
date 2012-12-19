=== Wp Ultimate CSV Importer Plugin ===
Contributors: Smackcoders
Donate link: http://www.smackcoders.com/donate.html
Tags: batch, excel, import, spreadsheet, plugin, admin, csv, importer,
Requires at least: 3.4
Tested up to: 3.4.2
Stable tag: 2.0.0
Version: 2.0.0
Author: Smackcoders
Author URI: http://profiles.wordpress.org/smackcoders/
License: GPLv2 or later

A plugin that helps to import any csv file as post, page or custom post data's by matching csv headers to wp fields.


== Description ==

Wp Ultimate CSV Importer Plugin helps you to import any csv file as post, page or even as custom post data's by matching its headers to relevant fields or custom fields.

1. Admin can import the data's from any csv file. 
2. Can define the type of post and post status while importing.
3. Provides header mapping feature to import the data's as your need.
4. Users can map column headers to existing fields or assign as custom fields.
5. Import unlimited data as post.
6. Make imported post as published or make it as draft.
7. Added featured image import functionality.

Important Note:  To import your posts for scheduled publishing in future, have a date coloumn in your csv with the date of post to be published on. If the date is a future date, then the post will be automatically scheduled for publishing on particular date as mentioned in csv.

Note: Your theme should support featured image function. If not, please add the following code to header.php or where it needed.
		add_theme_support( 'post-thumbnails' );

You can follow the instructions as given here 
	[http://codex.wordpress.org/Function_Reference/the_post_thumbnail](http://codex.wordpress.org/Function_Reference/the_post_thumbnail)
    [http://codex.wordpress.org/Post_Thumbnails](http://codex.wordpress.org/Post_Thumbnails)	[http://wordpress.org/support/topic/featured-image-not-showing-7?replies=5](http://wordpress.org/support/topic/featured-image-not-showing-7?replies=5)


Support and Feature request.
----------------------------

Please create issues only in our tracker http://code.smackcoders.com/wp-csv-importer/issues instead of wordpress support forum. 

For guides and tutorials, visit http://www.smackcoders.com/category/free-wordpress-plugins/google-seo-author-snippet-plugin.html . 



== Installation ==


 Wp Ultimate CSV Importer is very easy to install like any other wordpress plugin. No need to edit or modify anything here.

1.  Unzip the file 'wp-ultimate-csv-importer.zip'.
2.  Upload the ' wp-ultimate-csv-importer ' directory to '/wp-content/plugins/' directory using ftp client or upload and install	
wp-ultimate-csv-importer.zip through plugin install wizard in wp admin panel 
3.  Activate the plugin through the 'Plugins' menu in WordPress.
4.  After activating, you will see an option for 'Wp Ultimate CSV Importer' in the admin menu (left navigation) and you will import the csv files to import the data's.



== Screenshots ==

1. Admin settings for Wp Ultimate CSV Importer Plugin .
2. Admin settings for Import Data and Header Mapping configuration to import data's from a csv file.



== Changelog ==

= 2.0.0 =		WPDEBUG errors fixed. CSV import folder changed to Wp native uploads folder.

= 1.1.1 =		Renamed the mapping field attachment as featured_image and category as post_category.

= 1.1.0 =		Added featured image import feature along with post/page/custom post.

= 1.0.2 =		- Bug fixed to recognize the trimmed trailing space in the CSV file 
	       		- Added validation for the duplicate field mapping.

= 1.0.1 =		Added features to import multiple tags and categories with different delimiters.

= 1.0.0 =		Initial release version. Tested and found works well without any issues.




== Upgrade Notice ==

=v 2.0.0=		Major Bug fixed and should upgrade. WPDEBUG errors fixed. CSV import folder changed to Wp native uploads folder.

=v 1.1.1 =		Minor correction and fix applied.

=v 1.1.0 = 		A major new feature added in this version. Update needed.

=v 1.0.2 =		This version have important bug fixes and newly added features. Must be upgrade immediately.

=v 1.0.1 =		Added features to import multiple tags and categories with different delimiters.

=v 1.0.0 =		Initial release of plugin. 




== Frequently Asked Questions ==

1. How to install the plugin?

   Like other plugins wp-ultimate-csv-importer is easy to install. Upload the wp-ultimate-csv-importer.zip file through plugin install page through wp admin. Everything will work fine with it.
   
2. How to use the plugin?

   After plugin activation you can see the ' Wp Ultimate CSV Importer ' menu in admin backend.
   
	a. Browse csv file to import the data's.
	b. Select the post type or post / page to import as.
	b. Map each header to the relevant fields using the drop downs to import.
	c. If import as draft option is checked, post will be in draft mode.
	
3. How to define the multiple tags and categories?

    In CSV, tags should be separated by "," to import multiple tags and categories should be separated by "|" to import multiple categories.
	
4. How to choose featured image to import?
	Match the coloumn contains url paths to images to Attachment field from the drop down.

Configuring our plugin is as simple as that.

For quick response and reply please create issues in our [support](http://code.smackcoders.com/wp-csv-importer/issues) instead of wordpress support forum.




