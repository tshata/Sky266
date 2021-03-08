<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'sky266' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'password' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '<b2mvDQ%E~I=jPx@%E(iPs~R]DF#c2-{ZhT!.eo 2o)r1+tJS*G|kD.Wfm(?-:5~');
define('SECURE_AUTH_KEY',  ';87hn?Q<SA|{`B7= +YP-$[pJu-,uS68Dn4[)z5A-~<a+*r:?C+UfJBk0 rNYRF,');
define('LOGGED_IN_KEY',    '}.*J:-c]l44Vdu;%}<Wth7d1b0U;V*K1+Vdkv5%1|(HBo4Lzy,#$JvRk%[|}T*)4');
define('NONCE_KEY',        'Pp,0dcCUe/ QQ#@(}ID%yM+(~@8J>R [`r>[OX8 &AzK{OI?ypio^D6bCZ_Gd:4|');
define('AUTH_SALT',        ' 5N/-^h{l&;HB<J,)^B 6p;rx7_$18BUdxAE37{;*$>|;y;LtqS.~]>Pi-lp6){w');
define('SECURE_AUTH_SALT', '/@UN|V))4L`e+q|u3rC]{7tl?D*j-U)P.&Yd)k|T#K6reR+yXX?-5Y/:2Ti_-bJ5');
define('LOGGED_IN_SALT',   '6{IOKbac`o&s96*p/MnTFVGawF3O({-9vv:^(e6a(Tf]gi1DXI_:ng2uD:<kQ.h+');
define('NONCE_SALT',       'i#1.M_T-R-<`@w1h=TG($ph5+LWr]Ym?c/1[a|+14 oQI/&pzDspmm%*mIM_2m?z');

/**#@-*/

/**
 * WordPress Database Table prefix.
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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';



