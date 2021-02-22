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
define('AUTH_KEY',         'L29(HDa$EI+<pHh~^e|WCP~]7xu-eNP_?20/Gyig(#`D-JYU&ra.|7#_dm6-Hi@Q');
define('SECURE_AUTH_KEY',  'd(84EG|8+.U.&2Rx2AP.3LySqOqb+V&*>l6z%N[dIRJC^=pkDWEcw:31o}LiTz7?');
define('LOGGED_IN_KEY',    '>3gl|eT[5Q&d*TR:TCQQI.q:;9~8S;mFa2=O1YzXQW{(ABsNT~x>$_ewES_U5D_}');
define('NONCE_KEY',        '&]4AMh(|9j%($U,H5^+,YmqY+LSarA6X1qS#V<B:%T~+Eqt:x-VkQ6*FF4G<]&XT');
define('AUTH_SALT',        'S}PXFk{Yl{IPNMH]Wx[9fMLtw-WE9|lKQ)1Wr+U&<w5!H1u+yebwp2[QT:d4;=0O');
define('SECURE_AUTH_SALT', '6=FIv#Z&%++l^+=?L?O|wJ6KbB`=l{Fd:aXqW3e9P@+PA9(+w FR&-0Q>0Ek{rZb');
define('LOGGED_IN_SALT',   'D@ItnjB/,u0iJ)}rtqOxL{|j3)TY:z&l-R9&s=8q3`Z~A;=D%TNWg8V0F}rx)-Is');
define('NONCE_SALT',       '0gut^C71VJcn7I)~raOYxXQx$^Wh-#^`T-F9_4FN-KF/EBJq3Idz#=RfC$Ijj]6x');

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

