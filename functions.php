<?php
/**
 * Twenty Twelve Demo functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Twenty_Twelve_Demo
 */

/**
 * Create a helper function for easy SDK access.
 */
function twentytwelve_demo() {
	global $twentytwelve_demo;

	if ( ! isset( $twentytwelve_demo ) ) {
		// Include Freemius SDK.
		require_once dirname( __FILE__ ) . '/freemius/start.php';

		$twentytwelve_demo = fs_dynamic_init( array(
			'id'             => '1747',
			'slug'           => 'twentytwelve-demo',
			'type'           => 'theme',
			'public_key'     => 'pk_f2228cc5d6dcab307e2b353521f66',
			'is_premium'     => false,
			'has_addons'     => false,
			'has_paid_plans' => false,
			'menu'           => array(
				'override_exact' => true,
				'first-path'     => 'themes.php',
				'account'        => false,
				'contact'        => false,
				'support'        => false,
			),
		) );
	}

	return $twentytwelve_demo;
}

// Init Freemius.
twentytwelve_demo();

// Signal that SDK was initiated.
do_action( 'twentytwelve_demo_loaded' );

/**
 * Enqueue scripts.
 */
function twentytwelve_child_enqueue_scripts() {
	wp_enqueue_style( 'twentytwelve-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'twentytwelve-child-style', get_stylesheet_directory_uri() . '/style.css', array( 'twentytwelve-style' ), wp_get_theme()->get( 'Version' ) );
}
add_action( 'wp_enqueue_scripts', 'twentytwelve_child_enqueue_scripts' );

/**
 * Add support for header button for in Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function twentytwelve_child_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'header_button', array(
		'title'    => __( 'Header Button', 'twentytwelve-child' ),
		'priority' => 60, // Before Background Image.
	) );

	// Header Button text.
	$wp_customize->add_setting( 'header_button_text', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'header_button_text', array(
		'label'   => __( 'Button Text', 'twentytwelve-child' ),
		'section' => 'header_button',
		'type'    => 'text',
	) );

	// Header Button link.
	$wp_customize->add_setting( 'header_button_link', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );

	$wp_customize->add_control( 'header_button_link', array(
		'label'      => __( 'Button Link', 'twentytwelve-child' ),
		'section'    => 'header_button',
		'type'       => 'text',
	) );
}
add_action( 'customize_register', 'twentytwelve_child_customize_register' );

if ( class_exists( 'WooCommerce' ) ) {
	add_action( 'post_class', 'wc_order_post_class', 20, 3 );
	add_action( 'template_redirect', 'wc_add_product_to_cart' );
	add_action( 'after_setup_theme', 'wc_remove_frame_options_header', 11 );
}

/**
 * Add extra post class for orders.
 *
 * Add the 'no-link' class to prevent order view link.
 *
 * @param array        $classes Current classes.
 * @param string|array $class   Additional class.
 * @param int          $post_id Post ID.
 * @return array
 */
function wc_order_post_class( $classes, $class = '', $post_id = '' ) {
	if ( ! $post_id || ! in_array( get_post_type( $post_id ), wc_get_order_types( 'order-meta-boxes' ), true ) ) {
		return $classes;
	}

	$order = wc_get_order( $post_id );

	if ( $order ) {
		$classes[] = 'no-link';
	}

	return $classes;
}


/**
 * Add product item to cart on site visit.
 */
function wc_add_product_to_cart() {
	$product = get_page_by_title( 'Sunglasses', OBJECT, 'product' );

	if ( is_object( $product ) && $product->ID ) {
		$found = false;

		// Check if product already in cart.
		if ( sizeof( WC()->cart->get_cart() ) > 0 ) {
			foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
				$_product = $values['data'];

				if ( $_product->id == $product->ID ) {
					$found = true;
				}
			}

			// If product not found, add it.
			if ( ! $found ) {
				WC()->cart->add_to_cart( $product->ID );
			}
		} else {
			// If no products in cart, add it.
			WC()->cart->add_to_cart( $product->ID );
		}
	}
}

/**
 * Allow rendering of checkout and account pages in iframes.
 */
function wc_remove_frame_options_header() {
	remove_action( 'template_redirect', 'wc_send_frame_options_header' );
}
