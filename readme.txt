=== AKB Member Directory ===
Contributors: akash054 
Tags: member directory, team management, custom post type, member-team relationship  
Requires at least: 5.5  
Tested up to: 6.5  
Requires PHP: 7.4  
Stable tag: 1.0.0  
License: GPLv2 or later  
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A custom plugin to manage members and teams with a built-in connection system between them.

== Description ==

**AKB Member Directory** is a custom WordPress plugin for creating and managing a directory of members and their respective teams. It uses two custom post types — **Members** and **Teams** — and provides a connection system between them, allowing members to be grouped under specific teams.

**Key Features:**

- Custom Post Type: **Members**
- Custom Post Type: **Teams**
- Relationship system to connect Members with Teams
- Archive and single templates for both Members and Teams
- Clean, object-oriented architecture
- Modular structure following PSR-4 autoloading
- Built-in activation and deactivation hooks with rewrite rule flushing

== Installation ==

1. Upload the plugin to your WordPress site's `/wp-content/plugins/` directory.
2. Activate the plugin from the Plugins menu in WordPress.
3. Add Teams and Members from the dashboard.
4. While adding or editing a Member, select the Team to associate them with.
5. Use the archive pages to list all teams and members.

== Usage ==

- Go to **Teams** to create team entries.
- Go to **Members** to add members and assign them to a team.
- Visit the **/members/** and **/teams/** archive URLs to view listings.
- Use the plugin’s single templates for detailed profiles.

== Frequently Asked Questions ==

= Can I assign a member to a team? =  
Yes, the plugin provides a meta box to select and connect a member with a team.

= Are there frontend templates for listing members and teams? =  
Yes, archive and single templates are included and used automatically.

== Changelog ==

= 1.0.0 =
* Initial release with Member and Team CPTs and team-member relationship functionality.

== Upgrade Notice ==

= 1.0.0 =
First stable release. No upgrade actions needed.

== Credits ==

Developed by Akash Kumar Bhowmik.
