<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'alpeadria_work');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '3yB(Y8(S,x5-$FIN[,@iK(AU;<a@K~EF9M</sz[iQn(%R.2r7r{r0.X|vcjC5~FJ');
define('SECURE_AUTH_KEY',  '&gK+g:AZ8xow#Sxp,g$Jo^lGwU7MSE7TEAp~ZS|k6DVHv w-(oT]9mu7eR|BEP!]');
define('LOGGED_IN_KEY',    ':t.?x$*8mU(qYVp!`FTY8=E4|UL(VlR&wm?AU:&jL/V{af#EK$R4zbY{G-!=`A/j');
define('NONCE_KEY',        'S|ng^e*fE* oKawt@!=_fmDlY<^d&c{hJd)NZ#4dd>EP0@}kWeYk(x5r?yGf/c}P');
define('AUTH_SALT',        'FvN|hu%oj+-qbX!|f]MHkC%0JU3K;SuMV&r{|dny(gw,t_:CV5T;0A|:XpSh(r>U');
define('SECURE_AUTH_SALT', 'mq^i2}%Qtv]M+i4k&Zd[|M^K`$D,XW,&VkZ0I`U7p>-u@O;:|8(1FVx(EI}-5a1J');
define('LOGGED_IN_SALT',   '0L^EU,T*@]!D3h[)lle;z>l+v~&-p x[#KH}kN*E+Pl~Ru7ZQ!f)^(V?sHd;`nfx');
define('NONCE_SALT',       'F|+ij/Wv:|PRTxdBB!<F9sAW00Y`  3:`Pk5!i|ay!9@~uqIbA/~!p>xO}PFHP13');

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
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
