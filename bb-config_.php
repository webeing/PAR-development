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
define( 'BBDB_PASSWORD', 'c0gn1t10n.' );

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
define( 'BB_AUTH_KEY', '6188eb8dc82d826cdee90206250ea67ae5dc333b592ab0c52526c7c745ae0e20' );
define( 'BB_SECURE_AUTH_KEY', '28a60c74462e1ed5f05e57fa5f5bb669e83e7d0e3955c2cb6c7a77dbc277c67d' );
define( 'BB_LOGGED_IN_KEY', '530f3e5f97708e2016f957b77c896d6bcdcacffb047645a1ec1a5038dde2d27f' );
define( 'BB_NONCE_KEY', 'c9c2bf0d63054341b828e8e7cc52f11869e281c0ac8a36541c22b0c0a9128204' );
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

$bb->uri = "http://r20291.ovh.net/par/wp-content/plugins/buddypress/bp-forums/bbpress/";
$bb->name = " Forums";
$bb->wordpress_mu_primary_blog_id = 1;

define('BB_AUTH_SALT', "15db4d81f77874651acdc6d2cb3f8000c14eb4a092fd2422d488df52e2da0700");
define('BB_LOGGED_IN_SALT', "65847d5416d62be63fcb0e1f52f1cc41b92c6cb02fcaf94b1c91d5b762ae24e3");
define('BB_SECURE_AUTH_SALT', "1fa87c9f40fdb714e82b54794f2f68a039f738ffef4cc0bb060f2a2203368a75");

define('WP_AUTH_COOKIE_VERSION', 2);

?>