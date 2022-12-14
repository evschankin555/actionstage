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

define( 'DB_NAME', 'msmaxitf_wp4' );

/** MySQL database username */
define( 'DB_USER', 'msmaxitf_wp4' );

/** MySQL database password */
define( 'DB_PASSWORD', '1u4&LaD^$' );

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
define( 'AUTH_KEY',         'K|seqM-%&3|Hn Nb,00z#aJdw=4+!u]a8eP]z~_{@<KOof<~DfH:~%o<w.d}[ .I' );
define( 'SECURE_AUTH_KEY',  'QAC>7x|5ACRj2OH9Gs}?cW6g)} 1F21^J4bqx7R=7y0K)b6%%2bwHBk<,o5<3?Gl' );
define( 'LOGGED_IN_KEY',    'd&q,Q`@?83,;HDJrF&[lHXTp:{P`W+4cK$9r!.A*@gs`;bZk >XcD>5dSS9 nS)^' );
define( 'NONCE_KEY',        'R$kVW*C!D}|o]hjf4*[3.X{;Iwoe73_%WRhbQb6^~Dii-PDze>Si9 >;~&Pl~F?l' );
define( 'AUTH_SALT',        'Gr_5}{C#P~~>W`/D#Ay%82~6Q=B}*Q#}?+*Vr*l*C&JPVFx5KE.$ NX+DAiKlMRf' );
define( 'SECURE_AUTH_SALT', ';Z/TlMY@8cJBp>2lxK^FdA7,%wQPjqI-%*k[1F[AgD@i1o}-%+qsnn$ciy8zm2H;' );
define( 'LOGGED_IN_SALT',   '~(1#vPULbv}x`yHo=N/|$?tA;A+<h<oc9X^WFjgRJKsl39KXTj6Ds,bYv,2Qk(m7' );
define( 'NONCE_SALT',       '2 iPN52-jZQM8(.&Fhy|POJo_Il~wzceXD]H}vM+4-=xTC![eFA4gDUZ`~QOP3)@' );

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
define('WP_MEMORY_LIMIT', '64m');

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

define('WP_HOME','https://fishing-report.ru/');
define('WP_SITEURL','https://fishing-report.ru/');