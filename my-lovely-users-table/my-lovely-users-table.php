<?php

declare(strict_types=1);

// -*- coding: utf-8 -*-
/**
 * My Lovely Users Table
 *
 * @package     MyLovelyUsersTable
 * @author      Inpsyde GmbH
 * @copyright   2022 Kaushik Pitroda
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: My Lovely Users Table
 * Plugin URI:  https://inpsyde.com/
 * Description: This plugin will show list of users and their details.
 * Version:     1.0.0
 * Author:      Inpsyde GmbH
 * Author URI:  https://inpsyde.com/
 * Text Domain: my-lovely-users-table
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

add_filter('template_redirect', 'my_404_override');
function my_404_override()
{
    global $wp_query;
    $pagename = get_query_var('pagename');
    $userId = get_query_var('userId');
    if ('my-lovely-users-table' == $pagename) {
        status_header(200);
        $wp_query->is_404 = false;
    }
}

function users_plugin_rules()
{
    add_rewrite_rule(
        'my-lovely-users-table/?([^/]*)',
        'index.php?pagename=my-lovely-users-table&userId=$matches[1]',
        'top'
    );
}

function users_plugin_activate()
{
    users_plugin_rules();
    flush_rewrite_rules();
}

function users_plugin_deactivate()
{
    flush_rewrite_rules();
}

function users_plugin_query_vars($vars)
{
    $vars[] = 'userId';

    return $vars;
}

function users_plugin_display()
{
    $pagename = get_query_var('pagename');
    $userId = get_query_var('userId');
    if ('my-lovely-users-table' == $pagename) {
        if ($userId != '') {
            add_filter('template_include', static function () {
                include(WP_PLUGIN_DIR . '/my-lovely-users-table/UserDetails.php');
            });
        } else {
            add_filter('template_include', static function () {
                get_header();
                include(WP_PLUGIN_DIR . '/my-lovely-users-table/UserListTable.php');
                get_footer();
            });
        }
    }

    return false;
}

add_action('wp_enqueue_scripts', 'enqueue_users_frontend_script');
function enqueue_users_frontend_script()
{
    global $wp;
    wp_enqueue_script('users-script', plugin_dir_url(__FILE__) .
    'frontend-scripts.js', ['jquery'], null, true);
    $currentUrl = home_url($wp->request);
    $variables = [
        'ajaxurl' =>  $currentUrl,
    ];
    wp_localize_script('users-script', "users", $variables);
}

register_activation_hook(__FILE__, 'users_plugin_activate');
register_deactivation_hook(__FILE__, 'users_plugin_deactivate');
add_action('init', 'users_plugin_rules');
add_filter('query_vars', 'users_plugin_query_vars');
add_filter('template_redirect', 'users_plugin_display');
