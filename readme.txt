=== Tomatillo Design – Site Manager Role ===
Contributors: tomatillodesign
Tags: user roles, capabilities, permissions, site manager, gravity forms, woocommerce, events calendar, editor
Requires at least: 6.0
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adds a custom WordPress role called "Site Manager" with full content editing and plugin-level access—without full administrator privileges.

== Description ==

This lightweight plugin registers a custom user role: **Site Manager**. It is ideal for experienced content managers or site editors who need elevated permissions without full admin access.

The Site Manager role includes:

* Full access to pages, posts, menus, widgets, categories, and media
* Full access to **Gravity Forms** forms and entries
* Full access to **The Events Calendar** event types
* Safe WooCommerce permissions: orders, products, reports (no settings or system-level access)
* View-only access to **Fathom Analytics** and **MonsterInsights**
* Ability to manage users (excluding administrators)

Includes hardcoded protection to prevent Site Managers from assigning, editing, or deleting Administrator accounts.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/` directory or install via the WordPress admin.
2. Activate the plugin.
3. A new role, **Site Manager**, will be available under Users → Add New.

== Frequently Asked Questions ==

= What happens to Site Manager users if the plugin is deactivated? =

They are automatically reassigned to the default `editor` role on deactivation or uninstall. An admin notice will confirm this downgrade if applicable.

= Can Site Managers access plugin settings? =

Only selectively. This role includes view and edit capabilities for popular plugin interfaces like Gravity Forms and WooCommerce, but it does not include destructive caps like `edit_plugins` or `manage_options`.

== Screenshots ==

1. Site Manager role available in the user role dropdown
2. Gravity Forms, WooCommerce, and Menus fully accessible to Site Managers

== Changelog ==

= 1.0.0 =
* Initial release
* Adds Site Manager role
* Includes capabilities for Gravity Forms, The Events Calendar, WooCommerce, SEO, and analytics plugins
* Automatically downgrades users on deactivation/uninstall

== Upgrade Notice ==

= 1.0.0 =
Initial release of the Site Manager Role plugin. Grants safe, editor-level access to common plugin tools and site content.

== License ==

This plugin is licensed under the GPLv2 or later.
