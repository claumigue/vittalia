<?php
/**
 *  Agregar campos personalizados adicionales al metabox doctor_info
 */
function vittalia_add_custom_doctor_info_fields( $fields ) {
	
	// definir los campos adicionales
	$new_fields = array(
		'medilink_whatsapp'        => array(
			'label' => esc_html__( 'WhatsApp', 'medilink-core' ),
			'type'  => 'text',
		),
		'medilink_website'         => array(
			'label' => esc_html__( 'Sitio web', 'medilink-core' ),
			'type'  => 'text',
		),
		'medilink_doctor_os'       => array(
			'label'   => esc_html__( 'Acepta obra social', 'medilink-core' ),
			'type'    => 'checkbox',
			'default' => '',
		),
		'medilink_office_floor'    => array(
			'label'   => esc_html__( 'Piso', 'medilink-core' ),
			'type'    => 'number',
			'default' => '',
			'desc'    => esc_html__( '(del 2 al 15) ', 'medilink-core' ),
		),
		'medilink_office_location' => array(
			'label'   => esc_html__( 'Ubicación del consultorio', 'medilink-core' ),
			'type'    => 'select',
			'options' => array(
				'front' => esc_html__( 'Frente', 'medilink-core' ),
				'quiet' => esc_html__( 'Contrafrente', 'medilink-core' ),
				'full'  => esc_html__( 'Piso completo', 'medilink-core' ),
			),
			'default' => 'front',
		),
	);
	
	// Añadir los nuevos campos al array de campos existentes
	$fields['fields'] += $new_fields;
	// print '<pre>';print_r($fields);print '</pre>';die(); // Imprimir el array de campos (descomentar para depurar)
	return $fields;
}
add_filter( 'rt_postmeta_field_doctor_info', 'vittalia_add_custom_doctor_info_fields' );
