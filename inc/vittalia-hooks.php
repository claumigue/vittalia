<?php

/**
 * Change the parameters of custom taxonomies before being registered in the 'RT Framework' plugin (rt-posts.php)
 */
function vittalia_change_tax_params( $taxonomies ) {
	// change the 'rewrite' option of custom taxonomies to take their slug value from the 'Medilink' theme options
	$taxonomies['medilink_doctors_category']['rewrite']['slug']     = radiustheme\Medilink\RDTheme::$options['doctors_cat_slug'];
	$taxonomies['medilink_departments_category']['rewrite']['slug'] = radiustheme\Medilink\RDTheme::$options['departments_cat_slug'];
	return $taxonomies;
}
add_filter( 'rt_framework_taxonomies', 'vittalia_change_tax_params' );

/**
 * Función para agregar campos personalizados al panel de opciones de Redux
 * @param array $sections El array de secciones de opciones existentes
 * @return array El array modificado con el nuevo campo agregado
 */
function my_custom_redux_fields( $sections ) {
    // Buscar la sección header_section por su id
    foreach ( $sections as $key => $section ) {
        if ( $section['id'] == 'header_section' ) {
            // Agregar el nuevo campo al final del array de fields
            $sections[$key]['fields'][] = array(
                'id'      => 'header_buttonLinkTarget',
                'type'    => 'switch',
                'title'   => esc_html__( 'Open button link in a new tab', 'medilink' ),
                'on'      => esc_html__( 'Enabled', 'medilink' ),
                'off'     => esc_html__( 'Disabled', 'medilink' ),
                'default' => false,
            );
            break;
        }
    }
    return $sections;
}
add_filter( 'redux/options/medilink/sections', 'my_custom_redux_fields' );