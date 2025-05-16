<?php
/**
 * Plugin Name:       Tomatillo Design ~ Site Manager Role
 * Description:       Adds a custom WP Role – Site Manager
 * Version:           1.0.0
 * Author:            Chris Liu-Beers @ Tomatillo Design
 * Author URI:        http://www.tomatillodesign.com
 * Text Domain:       tomatillo-design-site-manager
 * Requires at least: 6.0
 * Requires PHP:      7.4
 */

defined( 'ABSPATH' ) || exit;

/**
 * Plugin activation: add Site Manager role with custom capabilities.
 */
function smr_add_site_manager_role() {
	add_role( 'site-manager', __( 'Site Manager', 'tomatillo-design-site-manager' ), [

		// 🧱 Core Content Management
		'read'                     => true,
		'edit_posts'              => true,
		'edit_pages'              => true,
		'edit_others_posts'       => true,
		'edit_others_pages'       => true,
		'edit_published_posts'    => true,
		'edit_published_pages'    => true,
		'publish_posts'           => true,
		'publish_pages'           => true,
		'delete_posts'            => true,
		'delete_pages'            => true,
		'delete_others_posts'     => true,
		'delete_others_pages'     => true,
		'delete_published_posts'  => true,
		'delete_published_pages'  => true,
		'manage_categories'       => true,
		'upload_files'            => true,
		'unfiltered_html'         => true,

		// 🎨 Appearance / Widgets / Editor UI
		'edit_theme_options'      => true, // Menus, Widgets, Customizer

		// 👥 User Management (limited)
		'list_users'              => true,
		'create_users'            => true,
		'promote_users'           => true,
		'delete_users'            => true,

		// 📅 The Events Calendar (Modern Tribe)
		'edit_tribe_events'               => true,
		'edit_others_tribe_events'        => true,
		'publish_tribe_events'            => true,
		'delete_tribe_events'             => true,
		'delete_others_tribe_events'      => true,
		'delete_published_tribe_events'   => true,
		'edit_published_tribe_events'     => true,
		'read_private_tribe_events'       => true,

		// 🧩 Gravity Forms
		'gravityforms_view_entries'       => true,
		'gravityforms_edit_entries'       => true,
		'gravityforms_delete_entries'     => true,
		'gravityforms_export_entries'     => true,
		'gravityforms_view_entry_notes'   => true,
		'gravityforms_edit_entry_notes'   => true,
		'gravityforms_view_forms'         => true,
		'gravityforms_edit_forms'         => true,
		'gravityforms_delete_forms'       => true,
		'gravityforms_create_form'        => true,
		'gravityforms_preview_forms'      => true,
		'gravityforms_uninstall'          => false, // Block uninstalling

		// 🛒 WooCommerce
		'manage_woocommerce'             => true,
		'view_woocommerce_reports'       => true,
		'edit_shop_orders'               => true,
		'read_shop_order'                => true,
		'edit_products'                  => true,
		'publish_products'               => true,
		'delete_products'                => true,
		'edit_product'                   => true,
		'read_product'                   => true,
		'delete_product'                 => true,

		// 🔍 SEO Plugins
		'wpseo_manage_options'           => true,
		// 'rank_math_settings_access'   => true, // Uncomment if using Rank Math

		// 📅 Editorial Workflow (PublishPress)
		'pp_manage_settings'             => true,
		'pp_view_calendar'               => true,

		// 📊 Analytics Plugins
		'view_fathom_analytics'          => true,
		'monsterinsights_view_reports'   => true,

		//
		// Add any additional custom WP Capabilities here
		// https://developer.wordpress.org/plugins/users/roles-and-capabilities/
		//

		// 🚫 Restricted admin-level capabilities (intentionally excluded)
		'edit_plugins'                   => false,
		'install_plugins'                => false,
		'delete_plugins'                 => false,
		'activate_plugins'              => false,
		'edit_files'                     => false,
		'manage_options'                 => false,

	] );

	smr_restrict_site_manager_caps();
}

register_activation_hook( __FILE__, 'smr_add_site_manager_role' );

/**
 * Restrict Site Manager from managing admin-level capabilities.
 */
function smr_restrict_site_manager_caps() {
	$role = get_role( 'site-manager' );
	if ( ! $role ) {
		return;
	}

	// Prevent plugin management
	$role->remove_cap( 'delete_plugins' );
	$role->remove_cap( 'install_plugins' );
	$role->remove_cap( 'activate_plugins' );
	$role->remove_cap( 'edit_plugins' );
}

/**
 * Prevent Site Manager from assigning or editing Administrator users.
 */
add_filter( 'editable_roles', 'smr_prevent_admin_role_editing' );
function smr_prevent_admin_role_editing( $roles ) {
	if ( current_user_can( 'site-manager' ) ) {
		unset( $roles['administrator'] );
	}
	return $roles;
}

add_filter( 'map_meta_cap', 'smr_block_admin_user_editing', 10, 4 );
function smr_block_admin_user_editing( $caps, $cap, $user_id, $args ) {
	if ( in_array( $cap, [ 'edit_user', 'remove_user', 'delete_user' ], true ) ) {
		if ( isset( $args[0] ) && user_can( $args[0], 'administrator' ) ) {
			if ( current_user_can( 'site-manager' ) ) {
				return [ 'do_not_allow' ];
			}
		}
	}
	return $caps;
}

/**
 * Downgrade all Site Manager users to Editor.
 */
function smr_downgrade_site_manager_users() {
	if ( ! get_role( 'site-manager' ) ) {
		return;
	}

	$users = get_users( [ 'role' => 'site-manager' ] );

	foreach ( $users as $user ) {
		$user->set_role( 'editor' );
	}

	if ( ! empty( $users ) ) {
		update_option( 'smr_downgraded_users', true );
	}
}

/**
 * Plugin deactivation hook – downgrade users.
 */
register_deactivation_hook( __FILE__, 'smr_downgrade_site_manager_users' );

/**
 * Plugin uninstall hook – downgrade users and remove the role.
 */
register_uninstall_hook( __FILE__, 'smr_on_uninstall_cleanup' );
function smr_on_uninstall_cleanup() {
	smr_downgrade_site_manager_users();
	remove_role( 'site-manager' );
}

/**
 * Admin notice after plugin deactivation.
 */
add_action( 'admin_notices', function () {
	if ( get_option( 'smr_downgraded_users' ) ) {
		echo '<div class="notice notice-warning is-dismissible"><p>';
		echo esc_html__( 'All Site Manager users have been reassigned to Editor after plugin deactivation.', 'tomatillo-design-site-manager' );
		echo '</p></div>';
		delete_option( 'smr_downgraded_users' );
	}
} );
