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

 * This has been slightly modified (to read environment variables) for use in Docker.

 *

 * @link https://wordpress.org/support/article/editing-wp-config-php/

 *

 * @package WordPress

 */


// IMPORTANT: this file needs to stay in-sync with https://github.com/WordPress/WordPress/blob/master/wp-config-sample.php

// (it gets parsed by the upstream wizard in https://github.com/WordPress/WordPress/blob/f27cb65e1ef25d11b535695a660e7282b98eb742/wp-admin/setup-config.php#L356-L392)


// a helper function to lookup "env_FILE", "env", then fallback

if (!function_exists('getenv_docker')) {

	// https://github.com/docker-library/wordpress/issues/588 (WP-CLI will load this file 2x)

	function getenv_docker($env, $default) {

		if ($fileEnv = getenv($env . '_FILE')) {

			return rtrim(file_get_contents($fileEnv), "\r\n");

		}

		else if (($val = getenv($env)) !== false) {

			return $val;

		}

		else {

			return $default;

		}

	}

}


// ** Database settings - You can get this info from your web host ** //

/** The name of the database for WordPress */

define( 'DB_NAME', getenv_docker('WORDPRESS_DB_NAME', 'wordpress') );


/** Database username */

define( 'DB_USER', getenv_docker('WORDPRESS_DB_USER', 'example username') );


/** Database password */

define( 'DB_PASSWORD', getenv_docker('WORDPRESS_DB_PASSWORD', 'example password') );


/**

 * Docker image fallback values above are sourced from the official WordPress installation wizard:

 * https://github.com/WordPress/WordPress/blob/f9cc35ebad82753e9c86de322ea5c76a9001c7e2/wp-admin/setup-config.php#L216-L230

 * (However, using "example username" and "example password" in your database is strongly discouraged.  Please use strong, random credentials!)

 */


/** Database hostname */

define( 'DB_HOST', getenv_docker('WORDPRESS_DB_HOST', 'mysql') );


/** Database charset to use in creating database tables. */

define( 'DB_CHARSET', getenv_docker('WORDPRESS_DB_CHARSET', 'utf8') );


/** The database collate type. Don't change this if in doubt. */

define( 'DB_COLLATE', getenv_docker('WORDPRESS_DB_COLLATE', '') );


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

define( 'AUTH_KEY',         getenv_docker('WORDPRESS_AUTH_KEY',         '1d6cb16e888a37254d4b73c7c654133625142505') );

define( 'SECURE_AUTH_KEY',  getenv_docker('WORDPRESS_SECURE_AUTH_KEY',  'b52137e9c485ad3106870aa5acfdf8600ec819a1') );

define( 'LOGGED_IN_KEY',    getenv_docker('WORDPRESS_LOGGED_IN_KEY',    '19317412202b96f898195ca4108cc263cb0884a7') );

define( 'NONCE_KEY',        getenv_docker('WORDPRESS_NONCE_KEY',        'f8ffffe3f79905a8f997de6d09c4225b2a07e2d8') );

define( 'AUTH_SALT',        getenv_docker('WORDPRESS_AUTH_SALT',        'aa23f17f5ef34db8a92b2cdded1b0f5616815867') );

define( 'SECURE_AUTH_SALT', getenv_docker('WORDPRESS_SECURE_AUTH_SALT', 'f10bbc4905d8b588f2045a1d82da6183bb9cd788') );

define( 'LOGGED_IN_SALT',   getenv_docker('WORDPRESS_LOGGED_IN_SALT',   'd47a789efbc60b607e23232d9ebd3d83e91076ce') );

define( 'NONCE_SALT',       getenv_docker('WORDPRESS_NONCE_SALT',       '0e29b84e95684656373e7822d82c6d29eaff5d5b') );

// (See also https://wordpress.stackexchange.com/a/152905/199287)


/**#@-*/


/**

 * WordPress database table prefix.

 *

 * You can have multiple installations in one database if you give each

 * a unique prefix. Only numbers, letters, and underscores please!

 */

$table_prefix = getenv_docker('WORDPRESS_TABLE_PREFIX', 'wp_');


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

define( 'WP_DEBUG', !!getenv_docker('WORDPRESS_DEBUG', '') );


/* Add any custom values between this line and the "stop editing" line. */


// If we're behind a proxy server and using HTTPS, we need to alert WordPress of that fact

// see also https://wordpress.org/support/article/administration-over-ssl/#using-a-reverse-proxy

if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) {

	$_SERVER['HTTPS'] = 'on';

}

// (we include this by default because reverse proxying is extremely common in container environments)


if ($configExtra = getenv_docker('WORDPRESS_CONFIG_EXTRA', '')) {

	eval($configExtra);

}


define( 'WP_REDIS_HOST', 'redis' );
define( 'WP_REDIS_PORT', 6379 );
define( 'WP_REDIS_TIMEOUT', 1 );
define( 'WP_REDIS_READ_TIMEOUT', 1 );
define( 'WP_REDIS_DATABASE', 0 );
/* That's all, stop editing! Happy publishing. */


/** Absolute path to the WordPress directory. */

if ( ! defined( 'ABSPATH' ) ) {

	define( 'ABSPATH', __DIR__ . '/' );

}


/** Sets up WordPress vars and included files. */

require_once ABSPATH . 'wp-settings.php';

