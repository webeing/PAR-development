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
define('DB_NAME', 'par_db');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'c0gn1t10n.');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
define('VHOST', 'no'); 
$base = '/par/';
define('DOMAIN_CURRENT_SITE', 'r20291.ovh.net' );
define('PATH_CURRENT_SITE', '/par/' );
define('SITE_ID_CURRENT_SITE', 1);
define('BLOGID_CURRENT_SITE', '1' );

/*
 * Hide Admin Bar permanently...
 * Enrico Corinti 04.01.2010
 */
define( 'BP_DISABLE_ADMIN_BAR', true );


/**#@+
 * Authentication Unique Keys.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link http://api.wordpress.org/secret-key/1.1/wpmu/salt WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '6188eb8dc82d826cdee90206250ea67ae5dc333b592ab0c52526c7c745ae0e20');
define('SECURE_AUTH_KEY', '28a60c74462e1ed5f05e57fa5f5bb669e83e7d0e3955c2cb6c7a77dbc277c67d');
define('LOGGED_IN_KEY', '530f3e5f97708e2016f957b77c896d6bcdcacffb047645a1ec1a5038dde2d27f');
define('NONCE_KEY', 'c9c2bf0d63054341b828e8e7cc52f11869e281c0ac8a36541c22b0c0a9128204');
define('AUTH_SALT', '15db4d81f77874651acdc6d2cb3f8000c14eb4a092fd2422d488df52e2da0700');
define('LOGGED_IN_SALT', '65847d5416d62be63fcb0e1f52f1cc41b92c6cb02fcaf94b1c91d5b762ae24e3');
define('SECURE_AUTH_SALT', '1fa87c9f40fdb714e82b54794f2f68a039f738ffef4cc0bb060f2a2203368a75');
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
