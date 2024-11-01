=== Super Simple Post / Page Restrictor ===
Contributors: arippberger
Tags: restrict content, restrictor, super simple, user login, login, restrict
Donate link: http://alecrippberger.com
Requires at least: 3.0.1
Tested up to: 4.5
Stable tag: 1.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Restict content on a post-by-post or page-by-page basis. Minimal configuration required.

== Description ==

SSPPR provides a **super simple** way to restrict specific post/pages/custom post types. The plugin adds a checkbox to the post type you'd like restricted. If the checkbox is checked, **POW**, that post is restricted --accessible only to logged-in users.

== Installation ==

**Manual Installation**

  - Download the plugin zip file and extract in your plugins directory
  - Configure the plugin setting found under Settings->Super Simple Post / Page Restrictor
  - Edit the post / page you'd like to restrict. There should be a checkbox labeled "Restrict Post?" - check this to restrict the current post / page.

**Automated Installation**

  - From the WordPress dashboard, click "Plugins->Add New"
  - Search for "Super Simple Post / Page Restrictor"
  - Click "Install Now" and then "Activate Plugin"
  - Configure the plugin setting found under Settings->Super Simple Post / Page Restrictor
  - Edit the post / page you'd like to restrict. There should be a checkbox labeled "Restrict Post?" - check this to restrict the current post / page.

== Configuration ==

**Page unavailable text**

  - Here you can customize the text that will be displayed to users who do not have access to a post / page.
  - If left blank, this defaults to "This content is currently unavailable to you".

**Apply to which post types?**

  - The post restriction metabox/checkbox will only appear on the backend of the post types selected here.
  - Additionally, if post types not selected here will not be restricted. If you've restricted certain pages and then deselect pages from this list, those pages will become accessible to all users (even though you previously restricted them).

**Prevent restriction for which user types?**

  - User roles selected here will never be able to view restricted content
  - User roles selected here will **never** be able to see restricted content, regardless of whether they are logged in.

**Default restriction?**

  - If this option is checked, posts / pages that are auto-generated should be restricted in most cases.
  - If this option is checked, newly created posts / pages should have their 'restrict content' checkboxes checked.

== Future Development ==

I'd like to add the following features to the plugin. If you have suggestions for added features please email me at arippberger@gmail.com.

  - Add shortcode to restrict content - content placed between start/end shortcodes would be restricted
  - Resctrict content in RSS feeds 

== Frequently Asked Questions ==

  - Q - How can I suggest features?
  - A - Email arippberger@gmail.com

  - Q - I just installed your password protection plug-in, but don't see how to set the password.
  - A - 
All plugin plugin settings are located under Dashboard->Settings->Super Simple Post / Page Restrictor Settings. The plugin doesn't add a global password to the site, it adds a checkbox to the backend of posts/pages. If that checkbox is checked a user can only view the page content if they are logged in (you can configure the options to prevent the content from EVER showing up for certain user types). 

You could potentially use the plugin as a global lockout. To do so you'd want to 1) check the restrict content checkbox on the backend of the content you want to restrict 2) assign your user (someone who you want to grant access to the content) a username / password or direct them to create one for themselves 3) ensure the plugin settings allow the correct user type access. I'd imagine you'd want to restrict access to subscribers as WordPress usually allows these users to be created by anyone. 

If that won't work for you I'd try a plugin like https://wordpress.org/plugins/password-protected/

== Changelog ==

  - 1.0 - Initial commit
  - 1.0.1 - Plugin cleanup and added translation support