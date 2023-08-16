<?php

/**
 * Mediplus child theme functions and definitions.
 *
 * Add your custom PHP in this file.
 * Only edit this file if you have direct access to it on your server (to fix errors if they happen).
 */

function medilink_child_styles() {
	wp_enqueue_style( 'medilink-child-style', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'medilink_child_styles', 18 );

// Enqueue javascript file
function vittalia_insert_custom_js() {
	wp_register_script( 'ajax-script', get_stylesheet_directory_uri() . '/assets/js/ajax-nuevo-profesional.js', array( 'jquery' ), '1.0.0', true );
	wp_enqueue_script( 'ajax-script' );
	wp_localize_script(
		'ajax-script',
		'ajax_object',
		array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'crear_profesional' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'vittalia_insert_custom_js' );

/**
 * Includes --------------------------------------------------------------------------
 */
require_once 'inc/vittalia-tools.php';
require_once 'inc/vittalia-helper.php';
require_once 'inc/vittalia-hooks.php';
require_once 'inc/custom-fields/rt-fields-profesionales.php';
require_once 'inc/custom-fields/rt-metabox-horarios.php';
require_once 'inc/custom-fields/acf.php';
require_once 'inc/custom-roles.php';
require_once 'inc/shortcodes/formulario-profesional.php';
require_once 'inc/ajax-action-profesional.php';

// // Define el directorio base
// $base_dir = get_stylesheet_directory() . '/inc/';

// // Busca todos los archivos .php dentro del directorio base y sus subdirectorios
// $files = glob( $base_dir . '{*,*/*}.php', GLOB_BRACE );

// // print '<pre>';print_r($files);print '</pre>';die();

// // Recorrer el array con un bucle foreach
// foreach ( $files as $file ) {
// 	// Incluir el archivo con la ruta relativa al script principal
// 	require_once $file;
// }
// -----------------------------------------------------------------------------------
