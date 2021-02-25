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
define('AUTH_KEY',         'FyDujeuEPs7*Pl{X~N#t,)&#~sR;m-H|vy5i/{=NA^yU3ch#Lp|;TZwDN|,G#afc');
define('SECURE_AUTH_KEY',  'rWeLlH<<#u5Pe+ oMdzufsYWO}>>pk&r~n%b,U<t=Pix$GXDZri-f,kujc]B(wuk');
define('LOGGED_IN_KEY',    'PaS(Obfq2KVxXkdHt0CSrxz$xK#B*]y`)[2-oVqlE Xkl/UdKBL=aF-`V-VhjlBm');
define('NONCE_KEY',        'iy2IhRX2Q:oK={rM/~2B]3~GQa<Ypx&~`|v*^vaY|K/EIEa/_QN_gb`5O_1ZTBs:');
define('AUTH_SALT',        '5*e|Tqw|fd}ceG$[T/,zAv9 Q%)=bBF@x$nKC5Ol(doHWK[tVr|{l_uCn^)-3S4g');
define('SECURE_AUTH_SALT', 'Opj~=Rm>)CVZ,nxWZ7Jt9U!j23Zv]&{|*U|OP>80Kpqg>?-3ymxH/Uek$eG-R;E[');
define('LOGGED_IN_SALT',   'ZO#B0V:wu!@Ll0tlIq:w|Lp4F@]U Z28-v=&1D=%TS>AYz:~]b2il7_F[P+kA:Z.');
define('NONCE_SALT',       'C77-K+y(?xF|[4XVVMY/u#aEBSB O:`U?wXWsKQ;De+YSyF4he.PuY~TP0-N|!i5');

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
define( 'WP_DEBUG', true );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';


