<?php
/** 
 * The base configurations of the WordPress.
 *
 **************************************************************************
 * Do not try to create this file manually. Read the README.txt and run the 
 * web installer.
 **************************************************************************
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information by
 * visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'PAR-development');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
define('VHOST', 'no'); 
$base = '/par-git/PAR-development/';
define('DOMAIN_CURRENT_SITE', '127.0.0.1' );
define('PATH_CURRENT_SITE', '/par-git/PAR-development/' );
define('SITE_ID_CURRENT_SITE', 1);
define('BLOGID_CURRENT_SITE', '1' );

/**#@+
 * Authentication Unique Keys.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link http://api.wordpress.org/secret-key/1.1/wpmu/salt WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'df34f452a6a1ecc62064f559fa2c76fb90792b576cd9e9c03950d79ce01ee96f');
define('SECURE_AUTH_KEY', 'eae969ca275adf2deb5981fb6b9d60fa2f4d162ebe8dd2d3ae573cacebb9ae93');
define('LOGGED_IN_KEY', '0214f1b2ec3cf7e6c585413696340670085371944d1c0ec5fbf153a0e5c1e6fe');
define('NONCE_KEY', '7e84b28c39429548d7d41bdaa96b157933029eb3b9b26d01d4f40255bd2c179c');
define('AUTH_SALT', '602f82d78a6bbb8c925bef1d8c7869f836861fc901fb43262944b8f89cd2fc63');
define('LOGGED_IN_SALT', 'd55d115ca6c790aa5d2c740bf0f2c88263cecb9c9e3d90bf55064847bf5f4228');
define('SECURE_AUTH_SALT', 'ca6137df8766869fb13f3308a532b77b0806444003d60ef94d86092a526febf7');
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de.mo to wp-content/languages and set WPLANG to 'de' to enable German
 * language support.
 */
define ('WPLANG', '');

// double check $base
if( $base == 'BASE' )
	die( 'Problem in wp-config.php - $base is set to BASE when it should be the path like "/" or "/blogs/"! Please fix it!' );

// uncomment this to enable WP_CONTENT_DIR/sunrise.php support
//define( 'SUNRISE', 'on' );

// uncomment to move wp-content/blogs.dir to another relative path
// remember to change WP_CONTENT too.
// define( "UPLOADBLOGSDIR", "fileserver" );

// If VHOST is 'yes' uncomment and set this to a URL to redirect if a blog does not exist or is a 404 on the main blog. (Useful if signup is disabled)
// For example, the browser will redirect to http://examples.com/ for the following: define( 'NOBLOGREDIRECT', 'http://example.com/' );
// Set this value to %siteurl% to redirect to the root of the site
// define( 'NOBLOGREDIRECT', '' );
// On a directory based install you must use the theme 404 handler.

// Location of mu-plugins
// define( 'WPMU_PLUGIN_DIR', '' );
// define( 'WPMU_PLUGIN_URL', '' );
// define( 'MUPLUGINDIR', 'wp-content/mu-plugins' );

define( "WP_USE_MULTIPLE_DB", false );

/* That's all, stop editing! Happy blogging. */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
