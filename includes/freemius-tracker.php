<?php
/**
 * Freemius Tracker.
 *
 * @package Twenty_Twelve_Demo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Create a helper function for easy SDK access.
 */
function we_fs() {
	global $we_fs;

	if ( ! isset( $we_fs ) ) {
		// Include Freemius SDK.
		require_once dirname( __FILE__ ) . '/freemius/start.php';

		$we_fs = fs_dynamic_init( array(
			'id'             => '1746',
			'slug'           => 'twentytwelve-demo',
			'type'           => 'theme',
			'public_key'     => 'pk_96342965253d0d84cee57932c4d02',
			'is_premium'     => false,
			'has_addons'     => false,
			'has_paid_plans' => false,
			'menu'           => array(
				'override_exact' => true,
				'first-path'     => 'plugins.php',
				'account'        => false,
				'contact'        => false,
				'support'        => false,
			),
		) );
	}

	return $we_fs;
}

// Init Freemius.
we_fs();

// Signal that SDK was initiated.
do_action( 'we_fs_loaded' );
