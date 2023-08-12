<?php

// $wp_roles = new WP_Roles(); // create new role object
// $todos    = $wp_roles->get_names();
// $wp_roles->remove_role( 'profesional' );
// print '<pre>';print_r($todos);print '</pre>';die();

if (!get_role('profesional')) {
    /* Create Profesional Role */
    add_action( 'init', 'add_role_profesional' );
    function add_role_profesional() {
        add_role(
            'profesional', // System name of the role.
            __( 'Profesional' ), // Display name of the role.
            array(
                'read'                   => true,
                'edit_posts'             => true,
                'publish_posts'          => true,
                'delete_posts'           => true,
                'edit_published_posts'   => true,
                'delete_published_posts' => true,
                // 'upload_files'           => false,
                // 'edit_pages'             => false,
                // 'edit_published_pages'   => false,
                // 'publish_pages'          => false,
                // 'delete_published_pages' => false, // This user will NOT be able to  delete published pages.
            )
        );
    }
}

// // Discriminar por rol el acceso a la página para añadir profesionales
// add_action( 'template_redirect', 'restringir_pagina_por_rol' );
// function restringir_pagina_por_rol() {

// 	// Obtener el ID de la página actual
// 	$pagina_actual = get_queried_object_id();

// 	// Obtener el ID de la página que quieres restringir
// 	$pagina_restringida = 5292; // Cambia este valor por el ID real de tu página

// 	// Comprobar si la página actual es la página restringida
// 	if ( $pagina_actual == $pagina_restringida ) {

// 		// Obtener el rol del usuario actual
// 		$rol_usuario = wp_get_current_user()->roles[0];

// 		// Definir los roles que pueden acceder a la página restringida
// 		$roles_permitidos = array( 'administrator', 'editor' );

// 		// Comprobar si el rol del usuario está entre los roles permitidos
// 		if ( ! in_array( $rol_usuario, $roles_permitidos ) ) {

// 			// Redirigir al usuario a otra página si no tiene permiso
// 			wp_redirect( home_url() ); // Cambia esta URL por la que quieras redirigir
// 			exit;
// 		}
// 	}
// }

// add_action( 'template_redirect', 'restringir_pagina_por_template' );
// function restringir_pagina_por_template() {
// $template_actual = basename( get_page_template() ); // Obtener el nombre del archivo del template de la página actual
// $template_restringido = 'page-nuevo-profesional.php'; // nombre del archivo del template que quieres restringir

// Comprobar si el template actual es el template restringido
// if ( $template_actual == $template_restringido ) {

// $rol_usuario = wp_get_current_user()->roles[0]; // Obtener el rol del usuario actual
// $roles_permitidos = array( 'administrator', 'editor' ); // Definir los roles que pueden acceder al template restringido

// Comprobar si el rol del usuario está entre los roles permitidos
// if ( ! in_array( $rol_usuario, $roles_permitidos ) ) {

// wp_redirect( home_url() ); // Redirigir al usuario a otra página si no tiene permiso
// exit;
// }
// }
// }