<?php
/**
 * Plugin Name:       Tomatillo Design ~ Site Manager Role
 * Description:       Adds a custom WP Role - Site Manager
 * Version:           1.0.0
 * Author:            Chris Liu-Beers @ Tomatillo Design
 * Author URI:        http://www.tomatillodesign.com
 * Text Domain:       tomatillo-design-site-manager
 * Requires at least: 6.0
 * Requires PHP:      7.4
 */

defined( 'ABSPATH' ) || exit;

/**
 * Plugin activation: add Site Manager role.
 */
function smr_add_site_manager_role() {
	add_role( 'site-manager', 'Site Manager', [
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
		'edit_theme_options'      => true, // Needed for Appearance menus/widgets
		'list_users'              => true,
		'create_users'            => true,
		'edit_users'              => true,
		'promote_users'           => true,
		'delete_users'            => true,
		'unfiltered_html'         => true,
	] );

	// Restrict Site Manager from creating or editing Admins
	smr_restrict_site_manager_caps();
}
register_activation_hook( __FILE__, 'smr_add_site_manager_role' );

/**
 * Restrict Site Manager from assigning or editing Administrators.
 */
function smr_restrict_site_manager_caps() {
	$role = get_role( 'site-manager' );
	if ( ! $role ) {
		return;
	}

	// Block admin-level role management
	$role->add_cap( 'list_users' );
	$role->add_cap( 'promote_users' );
	$role->add_cap( 'edit_users' );
	$role->add_cap( 'create_users' );
	$role->add_cap( 'delete_users' );

	// Remove admin-level caps
	$role->remove_cap( 'delete_plugins' );
	$role->remove_cap( 'install_plugins' );
	$role->remove_cap( 'activate_plugins' );
	$role->remove_cap( 'edit_plugins' );
	$role->remove_cap( 'edit_users' ); // prevent editing admins
}

/**
 * Optional: Remove the role on plugin uninstall.
 */
function smr_remove_site_manager_role() {
	remove_role( 'site-manager' );
}
register_uninstall_hook( __FILE__, 'smr_remove_site_manager_role' );

/**
 * Filter to prevent Site Manager from assigning administrator role.
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
