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
define('AUTH_KEY',         '8ng}#xeX^r-z+#[][,+[iER9c9(Y:IuXFwG}p:X2cx4+hETd;5-%0:e:gZLalYRs');
define('SECURE_AUTH_KEY',  '3WQTV8/q:O2=~FdK05,:=Mk}:qG`i*[&b~s>5<P@dZ-:mewHX7*dY|N~bt0mCllx');
define('LOGGED_IN_KEY',    'N&wOiF,.@AXSb+gA4P%7YG+M_+d]p-+P{GVD:+#fBp=0;u,ih,|9STSF<DhrR~($');
define('NONCE_KEY',        'u>GR0+pOV?.0[f=l6WZ$vdudwx~Eb*>(g<-f1>viEOFf@2]|[cy |/3j h[oqM3u');
define('AUTH_SALT',        'v9An`sBop+H,c}v|r<j@[9IVm}}E[hW:TOOpFCRO,?+09<E!5!Spd4bN%C3D+rY`');
define('SECURE_AUTH_SALT', 'rjj. @XwS;|-sDJ]h,n(LaEW=_{+E)s {V_|^Kn];x}(L7sbx,xVmn+jeP<ux*HO');
define('LOGGED_IN_SALT',   'U~2N)T>)q*7yMcL-;)x}i|ddvZR^dU1E)(e8h3CRGOiHLu1<-T-osfFV6=&M_E*A');
define('NONCE_SALT',       'IyIQs+PfdS;{gR/9wV<n8U5goyH*+w[G!K9jmj+#Nw$y@J|u,ou5d>a/=-!h40s0');

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
define('WPLANG', 'uk_UA');

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
