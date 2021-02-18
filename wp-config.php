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
define('AUTH_KEY',         '(rjK)0mXPW*~LdggqhSz]9?0`v2V2!{^FeQHaMGt}[d1b!Ze>lX>R]Diy0xdA*3F');
define('SECURE_AUTH_KEY',  'NQ ,|V=m|uw##Q,~Hzq(<~U0OJ`4=#/9>LB2x^Wa.,E5;hl)RrMF?5_P*;SfmWwl');
define('LOGGED_IN_KEY',    'G@wxl/:m-t_)ZNus{xSJfQum)rL?^5]sBRENGi[KAX4|(u)#)+q;.Bq6/;3pmhQP');
define('NONCE_KEY',        '4/)yKMrZQ+Cf:4~je5wL+84%DvDID!p&_LsNbwa0hA}z_&p0F*NH65W?@9-zJP=r');
define('AUTH_SALT',        'iI?j,Q? q{Ya06Eb6~0|^07Lmfp?{RJ{!9KO^!Y(Xb<EnYg.+pf.v@k[%1-<v&&x');
define('SECURE_AUTH_SALT', '^s_aIgsN5|?`-<!ht6?+JSs+h&b:U;>Og4cK*SG:q-Lik7}:0+z8y7O|8&WQ+8Bz');
define('LOGGED_IN_SALT',   'QejM3gFB]S:-k88l&f)gM4j!IufH5{zVe.L{1x{3E)`q*#m~e/@EmOY,h6bwuV$!');
define('NONCE_SALT',       'u_d^im^LNRChw.&k@LMeA&F^lB$N[h4 8J#c%yq1Ruwld|<?XOPiZ^u>O_7%t9Q(');

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

