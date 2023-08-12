<?php

if ( ! defined( 'ABSPATH' ) ) exit;

// Definir la función que devuelve el array de campos para el meta box
function definir_campos_de_horarios() {
    
    // Crear un array vacío para almacenar los campos de cada día
	$days_fields = array();
    
    // Agregar el encabezado  
	$days_fields[ 'medilink_doctor_horarios_header' ] = array(
		'label' => esc_html__( 'Horarios de consulta', 'medilink-core' ),
		'type'  => 'header',
		'desc'  => esc_html__( 'Agrega tus horarios en los días que correspondan', 'medilink-core' ),
	);
	
	// Crear un array con los nombres de los días en inglés y en español
	$days = array(
		'mon' => 'Lunes',
		'tue' => 'Martes',
		'wed' => 'Miércoles',
		'thu' => 'Jueves',
		'fri' => 'Viernes',
		'sat' => 'Sábado',
		'sun' => 'Domingo',
	);

	// Usar un bucle foreach para recorrer el array de los días
	foreach ( $days as $day_en => $day_es ) {

        // Obtener el nombre del día en inglés y en minúsculas
		$day_en = strtolower( $day_en );
        
		// Crear un campo de tipo grupo para cada día
		$days_fields[ 'medilink_doctor_horarios_' . $day_en ] = array(
			'type'  => 'group',
			'value' => array(
                // Crear un campo de tipo checkbox para cada día
				'active' => array(
                    'label'   => esc_html__( $day_es . ' - Activar horarios', 'medilink-core' ),
					'type'    => 'checkbox',
					'default' => '',
					'class' => 'day-checkbox',
				),
				// Crear dos campos de tipo time para cada día
				'aa_time'   => array(
                    'label'   => str_repeat( '&nbsp;', 3 ) . esc_html__( 'A --inicio:', 'medilink-core' ),
					'type'    => 'time_picker_24',
					'default' => '',
				),
				'ab_time'     => array(
                    'label'   => str_repeat( '&nbsp;', 7 ) . esc_html__( '--fin:', 'medilink-core' ),
					'type'    => 'time_picker_24',
					'default' => '',
				),
				'ba_time'     => array(
                    'label'   => str_repeat( '&nbsp;', 3 ) . esc_html__( 'B --inicio:', 'medilink-core' ),
					'type'    => 'time_picker_24',
					'default' => '',
				),
				'bb_time'     => array(
                    'label'   => str_repeat( '&nbsp;', 7 ) . esc_html__( '--fin:', 'medilink-core' ),
					'type'    => 'time_picker_24',
					'default' => '',
				),

			),
		);
        
	}
    
	return $days_fields;
}

if ( !class_exists( 'RT_Postmeta' ) ) {
    return;
}
$vittalia_Postmeta  = RT_Postmeta::getInstance();

$vittalia_Postmeta->add_meta_box( 'doctor_horarios', esc_html__( 'Horarios de consulta', 'medilink-core' ), array( "medilink_doctor" ), '', '', 'high', array(
    
    // Usar la función definida anteriormente para obtener el array de campos
    'fields' => definir_campos_de_horarios(),
    )
);

// Añadir una función para guardar los metadatos del meta box
function vittalia_save_postmeta( $post_id, $post, $update ) {

    // Comprobar si el post es del tipo correcto
    if ( $post->post_type != "medilink_doctor" ) {
        return;
    }

    // Comprobar si se ha enviado el formulario del meta box
    if ( empty( $_POST['rt_metabox_nonce_secret'] ) || !check_admin_referer( 'rt_metabox_nonce', 'rt_metabox_nonce_secret' ) ) {
        return;
    }

    // Comprobar si se está haciendo un auto-guardado
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Comprobar si el usuario tiene permisos para editar el post
    if ( ! current_user_can( "edit_post", $post_id ) ) {
        return;
    }
    
    // Obtener el array de los días
    $days = array(
		'mon' => 'Lunes',
		'tue' => 'Martes',
		'wed' => 'Miércoles',
		'thu' => 'Jueves',
		'fri' => 'Viernes',
		'sat' => 'Sábado',
		'sun' => 'Domingo',
	);

    $retornos = array();

    // Usar un bucle foreach para recorrer el array de los días
    foreach ( $days as $day_en => $day_es ) {
        
        // Obtener el valor del campo checkbox del día
        $active = isset( $_POST['medilink_doctor_horarios_' . $day_en]['active'] ) ? 'true' : '';
        
        // Obtener el valor del campo time del inicio del día
        $aa_time = isset( $_POST['medilink_doctor_horarios_' . $day_en]['aa_time'] ) ? $_POST['medilink_doctor_horarios_' . $day_en]['aa_time'] : '';
        
        // Obtener el valor del campo time del fin del día
        $ab_time = isset( $_POST['medilink_doctor_horarios_' . $day_en]['ab_time'] ) ? $_POST['medilink_doctor_horarios_' . $day_en]['ab_time'] : '';
        
        // Obtener el valor del campo time del fin del día
        $ba_time = isset( $_POST['medilink_doctor_horarios_' . $day_en]['ba_time'] ) ? $_POST['medilink_doctor_horarios_' . $day_en]['ba_time'] : '';
        
        // Obtener el valor del campo time del fin del día
        $bb_time = isset( $_POST['medilink_doctor_horarios_' . $day_en]['bb_time'] ) ? $_POST['medilink_doctor_horarios_' . $day_en]['bb_time'] : '';
        
        // Validar que el horario no exceda el límite de 23:59
        if ( $aa_time > '23:59' || $ab_time > '23:59' || $ba_time > '23:59' || $bb_time > '23:59' ) {
            // Mostrar un mensaje de error y no guardar el post
            wp_die( __( 'El valor del campo de horario debe estar entre 00:00 y 23:59 para el día ' . ucfirst( $day_es ), 'vittalia' ), __( 'Error al guardar el post', 'vittalia' ), array( 'back_link' => true ) );
            return;
        }

        // Validar que el usuario haya completado ambos campos
        if ( ( ! empty( $aa_time ) && empty( $ab_time ) ) 
            || ( empty( $aa_time ) && ! empty( $ab_time ) ) 
            || ( empty( $ba_time ) && ! empty( $bb_time ) ) 
            || ( empty( $ba_time ) && ! empty( $bb_time ) ) ) {
            // Mostrar un mensaje de error y no guardar el post
            wp_die( __( 'Te falta completar un campo de inicio o fin para el día ' . ucfirst( $day_es ), 'vittalia' ), __( 'Error al guardar el post', 'vittalia' ), array( 'back_link' => true ) );
            return;
        }

        // Validar que no pueda completar el segundo segmento a menos que haya completado el primero
        if ( empty( $aa_time ) && !empty( $ba_time ) ) {
            // Mostrar un mensaje de error y no guardar el post
            wp_die( __( 'Tienes que completar el primer segmento de horarios si quieres completar el segundo, para el día ' . ucfirst( $day_es ), 'vittalia' ), __( 'Error al guardar el post', 'vittalia' ), array( 'back_link' => true ) );
        }

        // Validar que el horario de inicio sea menor que el de fin
        if ( !empty( $aa_time ) && $aa_time > $ab_time ) {
            // Mostrar un mensaje de error y no guardar el post
            wp_die( __( 'El horario de inicio debe ser menor que el de fin para el día ' . ucfirst( $day_es ), 'vittalia' ), __( 'Error al guardar el post', 'vittalia' ), array( 'back_link' => true ) );
        }

        if ( !empty( $ba_time ) && ( $ab_time > $ba_time || $ba_time > $bb_time ) ) {
            // Mostrar un mensaje de error y no guardar el post
            wp_die( __( 'El horario de inicio debe ser menor que el de fin para el día ' . ucfirst( $day_es ), 'vittalia' ), __( 'Error al guardar el post', 'vittalia' ), array( 'back_link' => true ) );
        }
        
        $horario = array(
            'active' => $active,
            'aa_time' => $aa_time,
            'ab_time' => $ab_time,
            'ba_time' => $ba_time,
            'bb_time' => $bb_time,
        );
        // print '<pre>';print_r($horario);print '</pre>';die();
        
        // Guardar los valores de los campos en la base de datos
        $retornos[] = update_post_meta( $post_id, 'medilink_doctor_horarios_' . $day_en, $horario );
        
    }
    // print '<pre>';print_r($retornos);print '</pre>';die();

}
// Añadir la acción para guardar los metadatos al guardar el post
add_action( 'save_post', 'vittalia_save_postmeta', 11, 3 );