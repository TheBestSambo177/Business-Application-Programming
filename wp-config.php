<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'teambee' );

/** Database username */
define( 'DB_USER', 'Sambo' );

/** Database password */
define( 'DB_PASSWORD', 'notpassword' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '{5`Hu]cPn[0o@>{6UmGlou#qa}k7ui/Xa0B;,;3=K}yxcDT<ld|NKTn,RJ6}6si#' );
define( 'SECURE_AUTH_KEY',  'H;o}_PRdKh,jClGg~Z,ZypTHA+`_JXRUN$}vgW2|c_[veDXZNKf;th~eBZ9`<s>N' );
define( 'LOGGED_IN_KEY',    '@,iPB%vNC_zq_l21}Y+S.e].V<;h-#oeqf6X/>kT=MSzU,YWn[C-#a2S{Ew8NRh_' );
define( 'NONCE_KEY',        'J4re#+IG)vkugJpiME{q*sx<!QE<),u?)U&piWnn8s<UJghlt)V9 CnU_kOib2GQ' );
define( 'AUTH_SALT',        'LtS)*e-~P3D(B_sO$q:1`Cjsm*dC)!mOVBy(@jjrn<!?AYfH)]uSG%zsc@^Dwxs^' );
define( 'SECURE_AUTH_SALT', '%%}2+R9CFG!xy=eZ10Wmz]r}=2_+R9$:_|Uc33s2rzZ*KYI8$~6PA@%|DhU_Qmg[' );
define( 'LOGGED_IN_SALT',   'Qwu+mYOqwe;j$k6,RVk(}vxU!l##YcxT E}ARx(sXn,aU3MOBz>_,,u;UPkULv`f' );
define( 'NONCE_SALT',       '/wO!<cDV2.uyq)~q9]%I:Ld +=XM. =$cs}XK%G*]|~6o!w~j:!2x0/H>z6&[A;Z' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
