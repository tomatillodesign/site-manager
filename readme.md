# Tomatillo Design \~ Site Manager Role

A WordPress plugin that adds a powerful custom user role: **Site Manager**.
Ideal for editors and content managers who need advanced access without full administrator control.

---

## 🔧 Features

### ✅ Core Capabilities

* Full control over pages, posts, media, and categories
* Menu and widget management (via `edit_theme_options`)
* User management (create, edit, promote users — but cannot assign Admins)
* Safe access to unfiltered HTML

### 🔌 Plugin Integrations

Supports key capabilities for common plugins:

#### Gravity Forms

* View, edit, delete entries
* Create and edit forms

#### The Events Calendar (Modern Tribe)

* Full event management (create, edit, publish, delete)

#### WooCommerce

* Manage products and view orders
* Access sales reports (read-only)

#### SEO Plugins

* Yoast: `wpseo_manage_options`
* (Optional) Rank Math: `rank_math_settings_access`

#### Analytics

* Fathom Analytics: `view_fathom_analytics`
* MonsterInsights: `monsterinsights_view_reports`

#### Editorial Workflow (PublishPress)

* View calendar, manage settings

---

## ⚙️ Installation

1. Upload the plugin to `/wp-content/plugins/`
2. Activate it via WordPress admin
3. A new role **Site Manager** will appear under Users → Add New

---

## 🔄 Plugin Lifecycle

### On Activation

* Adds the `site-manager` role
* Assigns custom capabilities grouped by content, users, and plugins

### On Deactivation

* Any users with the `site-manager` role are **downgraded to Editor**
* Admin notice is displayed upon next login

### On Uninstall

* Downgrades users to Editor (if any remain)
* Removes the custom role

---

## 📌 Requirements

* WordPress 6.0+
* PHP 7.4+

---

## 👤 Author

**Chris Liu-Beers**
[Tomatillo Design](http://www.tomatillodesign.com)

---

## 📄 License

GPLv2 or later
