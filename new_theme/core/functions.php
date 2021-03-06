<?php

if ( ! function_exists( 'et_get_safe_localization' ) ) :
function et_get_safe_localization( $string ) {
	return apply_filters( 'et_get_safe_localization', wp_kses( $string, et_get_allowed_localization_html_elements() ) );
}
endif;

if ( ! function_exists( 'et_allow_ampersand' ) ) :
/**
 * Convert &amp; into &
 * Escaped ampersand by wp_kses() which is used by et_get_safe_localization()
 * can be a troublesome in some cases, ie.: outputted string is sent as email
 * @param string  original string
 * @return string modified string
 */
function et_allow_ampersand( $string ) {
	return str_replace('&amp;', '&', $string);
}
endif;

if ( ! function_exists( 'et_get_allowed_localization_html_elements' ) ) :
function et_get_allowed_localization_html_elements() {
	$whitelisted_attributes = array(
		'id'    => array(),
		'class' => array(),
		'style' => array(),
	);

	$whitelisted_attributes = apply_filters( 'et_allowed_localization_html_attributes', $whitelisted_attributes );

	$elements = array(
		'a'      => array(
			'href'   => array(),
			'title'  => array(),
			'target' => array(),
			'rel'    => array(),
		),
		'b'      => array(),
		'em'     => array(),
		'p'      => array(),
		'span'   => array(),
		'div'    => array(),
		'strong' => array(),
	);

	$elements = apply_filters( 'et_allowed_localization_html_elements', $elements );

	foreach ( $elements as $tag => $attributes ) {
		$elements[ $tag ] = array_merge( $attributes, $whitelisted_attributes );
	}

	return $elements;
}
endif;

if ( ! function_exists( 'et_core_get_main_fonts' ) ) :
function et_core_get_main_fonts() {
	global $wp_version;

	if ( version_compare( $wp_version, '4.6', '<' ) ) {
		return '';
	}

	$fonts_url = '';

	/* Translators: If there are characters in your language that are not
	 * supported by Open Sans, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$open_sans = _x( 'on', 'Open Sans font: on or off', 'Divi' );

	if ( 'off' !== $open_sans ) {
		$font_families = array();

		if ( 'off' !== $open_sans )
			$font_families[] = 'Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800';

		$protocol = is_ssl() ? 'https' : 'http';
		$query_args = array(
			'family' => implode( '%7C', $font_families ),
			'subset' => 'latin,latin-ext',
		);
		$fonts_url = add_query_arg( $query_args, "$protocol://fonts.googleapis.com/css" );
	}

	return $fonts_url;
}
endif;

if ( ! function_exists( 'et_core_load_main_fonts' ) ) :
function et_core_load_main_fonts() {
	$fonts_url = et_core_get_main_fonts();
	if ( empty( $fonts_url ) ) {
		return;
	}

	wp_enqueue_style( 'et-core-main-fonts', esc_url_raw( $fonts_url ), array(), null );
}
endif;

//Changes login logo of site
function my_custom_login_logo() { ?>

	<style type="text/css">
		#login h1 a,
		.login h1 a {
			background-image: url(<?php echo get_stylesheet_directory_uri();
			?>/images/riipen-logo.svg);
			padding-bottom: 30px;
			background-size: 220px !important;
			width: 230px !important;
			background-position: bottom !important;
		}
	</style>
	<?php }

add_action( 'login_enqueue_scripts', 'my_custom_login_logo' );

function my_login_logo_url() {
	return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );
function my_login_logo_url_title() {
	return 'Riipen';
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );