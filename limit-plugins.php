<?php
/**
 * Plugin Name: Limit Plugins by User
 * Plugin URI: http://wpranger.co.uk
 * Description: Hide selected plugins from specific users. 
 * Author: Caramboo
 * Author URI: http://wpranger.co.uk
 * Version: 1.0
 * License: GPLv2 or later
 */

/* If you recognise this code, I copied it from an online forum (I think). */
/* I'll give due credit if I find out who you are :)                       */

add_filter( 'all_plugins', 'plugin_permissions' );

/**
 * Filter the list of plugins according to user_login
 *
 * Usage: configure the variable $plugin_credentials, which holds a list of users and their plugins.
 * To give full access, put a simple string "ALL".  Replace <admin> and <user> where appropriate.
 *
 * To deny for some plugins, create an array with the Plugin Slug, 
 * which is the file name without extension (akismet.php, hello.php)
 *
 * @return array List of plugins
 */
function plugin_permissions( $plugins )
{
    // Config
    $plugin_credentials = array(
        '<admin>' => "ALL",
        '<user>' => array(
            'nginx-helper',
            'user-switching',
            'wp-migrate-db-pro',
            'limit-plugins',
            'stream'
        ),
    );

    // Current user
    global $current_user;
    $username = $current_user->user_login;

    // Super admin, return everything
    if ( "ALL" == $plugin_credentials[ $username ] )
        return $plugins;

    // Filter the plugins of the user
    foreach ( $plugins as $key => $value ) 
    { 
        // Get the file name minus extension
        $plugin_slug = basename( $key, '.php' );

        // If not in the list of allowed plugins, remove from array
        if( in_array( $plugin_slug, $plugin_credentials[ $username ] ) )
            unset( $plugins[ $key ] );
    }

    return $plugins;
}
