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
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         ';h+?e]MC.s%1+O~S,1!:lH c9Ac540=gxHR-*zd%?_M`&?_oj![ucJ/Im3RJr.IN' );
define( 'SECURE_AUTH_KEY',  '~%w]9,ylZ;c][F2H3<{lOuXII6>xI$ucvY@SCVv#L)?HiBXiUifakDksvU=yg&wj' );
define( 'LOGGED_IN_KEY',    'FnEFNt6.#S?k1:R)vWqMC^`l%.+)y~?t2qq0QqO4]:24B]5S`pS~K@d&I.W@N;X0' );
define( 'NONCE_KEY',        'h0JM1yTly~uAsuA0/(]rh3>5eE$t6^%z~W;qD?,SV2gKtAX9^xHf?I5L_=]}}b>m' );
define( 'AUTH_SALT',        'JqY2YZ/P=AZl5!cQoIa0Pq(39V6F!*@~u;g;0Z4P}|C.~G GfyNqlU_[_ %;P?L@' );
define( 'SECURE_AUTH_SALT', '[!nschGRR8IQ}WrmKh43I.6Oc+uzb]st|Y+TrR|*36L]#L>]eChaFZz`fL5F+[bq' );
define( 'LOGGED_IN_SALT',   '<x9v#dZ?6$5#_zq%m8R3JI#/q/g80(|6r=?.K>YJkpWc7a,y$6XKN[}=(fKKPRR[' );
define( 'NONCE_SALT',       ']0*6/ely&J|+aB]arrz%+uCx/f.|kEGVj+*to2k^e$4{K+/$I^:o9%M3n,k!8Kid' );

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
