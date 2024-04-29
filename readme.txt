=== Ultimate Member - JobBoardWP integration ===
Author URI: https://ultimatemember.com/
Plugin URI: https://wordpress.org/plugins/um-jobboardwp/
Contributors: ultimatemember, nsinelnikov, champsupertramp
Tags: job, job board, job listing, job manager, bookmarks, profile tabs
Requires PHP: 5.6
Requires at least: 5.5
Tested up to: 6.5
Stable tag: 1.0.8
License: GNU Version 2 or Any Later Version
License URI: http://www.gnu.org/licenses/gpl-3.0.txt
Requires UM core at least: 2.7.0

Integrates Ultimate Member with JobBoardWP listings plugin

== Description ==

Ultimate Member - JobBoardWP integration is an easy to use and lightweight plugin that enables you to add integration between job board functionality and user profiles to your website.

= Integrations: =

Ultimate Member

* User Profile tab with the user's posted jobs;
* Add tab to account page for users with employer role;

Ultimate Member - Social Activity

* Social Activity post about job posted;
* Social Activity post about the job was filled;

Ultimate Member - Private Messaging

* Individual job post message to author near the "apply a job" button;

Ultimate Member - Real-time notifications

* Notification then your job is approved;
* Notification then your job is expired;

Ultimate Member - User Bookmarks

* Show bookmark icon on jobs list;

Ultimate Member - Verified users

* Only verified users can apply for jobs;

= Admin features: =

The plugin makes it easy for you to manage settings from the wp-admin.

Ultimate Member

* Show account tab with jobs dashboard ( Ultimate Member > Settings > Extensions > JobBoardWP );
* Show profile tab with the user's jobs list ( Ultimate Member > Settings > Appearance > Profile Menu generally and for every role Ultimate Member > User Roles > Edit > JobBoardWP metabox);

Ultimate Member - Social Activity

* Checkboxes to enable/disable activity posts regarding jobs in Ultimate Member > Settings > Extensions > Social Activity

Ultimate Member - Private Messaging

* "Show messages button in individual job post" option in Ultimate Member > Settings > Extensions > Private Messages

Ultimate Member - Real-time notifications

* Notifications templates in Ultimate Member > Settings > Extensions > Notifications

Ultimate Member - Verified users

* "Only verified users can apply for jobs" option in Ultimate Member > Settings > Extensions > Verified users

= Documentation & Support =

Got a problem or need help with Ultimate Member - JobBoardWP? Head over to our [documentation](https://docs.ultimatemember.com/category/1573-jobboardwp) and perform a search of the knowledge base. If you can’t find a solution to your issue then you can create a topic on the [support forum](https://wordpress.org/support/plugin/um-jobboardwp/).

== Installation ==

1. Activate the plugin
2. That's it. Go to Ultimate Member > Settings > Extensions > JobBoardWP to customize plugin options
3. For more details, please visit the official [Documentation](https://docs.ultimatemember.com/category/1573-jobboardwp) page.

== Screenshots ==

1. Screenshot 1
2. Screenshot 2
3. Screenshot 3
4. Screenshot 4

== Changelog ==

= 1.0.8: April 29, 2024 =

* Tweak: Added Ultimate Member and JobBoardWP as required plugins

= 1.0.7: October 11, 2023 =

* Updated: Dependencies versions based on the recent changes for `UM()->frontend()->enqueue()::get_suffix();`

= 1.0.6: August 23, 2023 =

* Fixed: the "Bookmark" button is hidden if the role setting "Enable bookmark feature?" is turned off

= 1.0.5: May 30, 2023 =

* Added: Jobs Dashboard tab to Ultimate Member profile

= 1.0.4: August 17, 2022 =

* Fixed: Exclude expired and filled bookmarks from query if needed

= 1.0.3: February 9, 2022 =

* Fixed: Extension settings structure

= 1.0.2: December 20, 2021 =

* Added: The social activity post about creating a new user after job submission
* Fixed: Account tab "Jobs Dashboard" responsibility
* Fixed: Applying the job if the user isn't logged in but applying is available only for verified users
* Fixed: Bookmark link visibility for not logged in users
* Fixed: Verifying users after registration when post a job
* Fixed: Displaying a message button if UM: Private Messages is disabled

= 1.0.1: August 31, 2020 =

* Fixed: Fatal errors when Ultimate Member extensions are deactivated

= 1.0.0: August 11, 2020 =

* Initial Release
