<?php
/** 
 * The base configurations of bbPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys and bbPress Language. You can get the MySQL settings from your
 * web host.
 *
 * This file is used by the installer during installation.
 *
 * @package bbPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for bbPress */
define( 'BBDB_NAME', 'par_db' );

/** MySQL database username */
define( 'BBDB_USER', 'root' );

/** MySQL database password */
define( 'BBDB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'BBDB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'BBDB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'BBDB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/bbpress/ WordPress.org secret-key service}
 *
 * @since 1.0
 */
define( 'BB_AUTH_KEY', '6d799ac6176d1efa5f567dbfda81eb72c173614166dbcf3a1f8b36ea7f65ad3e' );
define( 'BB_SECURE_AUTH_KEY', 'b9f2065d93cbe3405c5916a8d2eb3769d35a6cbf84c9ba17464016d4f80ee420' );
define( 'BB_LOGGED_IN_KEY', 'f13a2ca47d74f55f2c9b2709fb160b8f2cc0f3ff37076277da6e364aca7555c4' );
define( 'BB_NONCE_KEY', '45b6dfd34410a7251db1c6d21cde0b593e91aa1a314a753e96615076ac1e3ea1' );
/**#@-*/

/**
 * bbPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$bb_table_prefix = 'wp_bb_';

/**
 * bbPress Localized Language, defaults to English.
 *
 * Change this to localize bbPress. A corresponding MO file for the chosen
 * language must be installed to a directory called "my-languages" in the root
 * directory of bbPress. For example, install de.mo to "my-languages" and set
 * BB_LANG to 'de' to enable German language support.
 */
define( 'BB_LANG', '' );

$bb->custom_user_table = "wp_users";
$bb->custom_user_meta_table = "wp_usermeta";

$bb->uri = "http://127.0.0.1/par/wp-content/plugins/buddypress/bp-forums/bbpress/";
$bb->name = " Forums";
$bb->wordpress_mu_primary_blog_id = 1;

define('BB_AUTH_SALT', "5161a73cc2b33da4f70cc11286907729a067e95aaa1a7c28eee048a90c4b26ef");
define('BB_LOGGED_IN_SALT', "ae5051afd1b1220c0b1841f61abb4a5c54108f0d2d1260dc8720fd889e994c1f");
define('BB_SECURE_AUTH_SALT', "ae7951830b6816beca25e4d63ba00509b9eed964f1c720b2bf3c322b1dd12369");

define('WP_AUTH_COOKIE_VERSION', 2);

?>