<?php

// Procesar solicitud ajax
add_action( 'wp_ajax_nopriv_vittalia_ajax_form', 'procesar_formulario_nuevo_profesional' );
add_action( 'wp_ajax_vittalia_ajax_form', 'procesar_formulario_nuevo_profesional' );
function procesar_formulario_nuevo_profesional() {
	// Inicializar la variable para guardar la respuesta JSON
	$res = array();

	// Verificar el WP nonce al recibir los datos del formulario
	if ( ! check_ajax_referer( 'crear_profesional', false, false ) ) {
		$res = array(
			'status' => 0,
			'msg'    => '✋ Error nonce validation!!',
		);
	} else {
		// Validar si la variable $floor es un entero entre 2 y 15
		$floor = filter_var(
			$_POST['floor'],
			FILTER_VALIDATE_INT,
			array(
				'options' => array(
					'min_range' => 2,
					'max_range' => 15,
				),
			)
		);

		// Validar y sanitizar los datos del formulario
		$datos_formulario = array(
			'title'           => sanitize_text_field( $_POST['title'] ),
			'about'           => sanitize_textarea_field( $_POST['about'] ),
			'designation'     => sanitize_text_field( $_POST['designation'] ),
			'subespecialidad' => sanitize_text_field( $_POST['subespecialidad'] ),
			'floor'           => $floor ? $floor : 2, // Asignar un valor por defecto en caso de que el dato recibido por $_POST[‘floor’] no sea válido
			'location'        => sanitize_text_field( $_POST['location'] ),
			'whatsapp'        => sanitize_text_field( $_POST['whatsapp'] ),
			'phone'           => sanitize_text_field( $_POST['phone'] ),
			'email'           => sanitize_email( $_POST['email'] ),
			'website'         => sanitize_url( $_POST['website'] ),
			'instagram'       => sanitize_url( $_POST['instagram'] ),
			'facebook'        => sanitize_url( $_POST['facebook'] ),
			'linkedin'        => sanitize_url( $_POST['linkedin'] ),
			'youtube'         => sanitize_url( $_POST['youtube'] ),
			'doctor_os'       => filter_var( $_POST['doctor_os'], FILTER_VALIDATE_BOOLEAN ), // Validar el valor del checkbox como booleano
			'especialidad_id' => (int) $_POST['especialidad'], // Obtener el ID de la especialidad seleccionada en el formulario
		);

		// Obtener la cadena JSON con los datos de los horarios, eliminando los caracteres escapados usando stripslashes()
		// $days_json = stripslashes( $_POST['days'] );

		// Convertir la cadena JSON en un array asociativo usando json_decode()
		// $days_array = json_decode( $days_json, true );
		$days_array = $_POST['days'];

		// Crear un array vacío para almacenar los datos sanitizados
		$days_sanitized = array();

		// Usar un bucle foreach para recorrer el array asociativo
		foreach ( $days_array as $day_data ) {

			// Sanitizar el valor del día usando sanitize_text_field()
			$day     = sanitize_text_field( $day_data['day'] );
			$day_key = "medilink_doctor_horarios_{$day}";

			// Sanitizar el valor del tiempo de inicio y fin del segmento A usando sanitize_text_field()
			$start_a = sanitize_text_field( $day_data['start_a'] );
			$end_a   = sanitize_text_field( $day_data['end_a'] );

			// Asignar el valor al array principal usando la clave $day_key
			$days_sanitized[ $day_key ] = array(
				'active'  => $day_data['checked'] ? 'true' : 'false',
				'aa_time' => $start_a,
				'ab_time' => $end_a,
				// 'ba_time' => isset($day_data['start_b']) ? $day_data['start_b'] : '',
				// 'bb_time' => isset($day_data['end_b']) ? $day_data['end_b'] : '',
			);

			// Comprobar si el array tiene los datos del segmento B
			if ( isset( $day_data['start_b'] ) && isset( $day_data['end_b'] ) ) {
				// Sanitizar el valor del tiempo de inicio y fin del segmento B usando sanitize_text_field()
				$start_b = sanitize_text_field( $day_data['start_b'] );
				$end_b   = sanitize_text_field( $day_data['end_b'] );

				// Añadir los datos sanitizados del segmento B al array
				$days_sanitized[ $day_key ]['ba_time'] = $start_b;
				$days_sanitized[ $day_key ]['bb_time'] = $end_b;
			}
		}

		$socials = array(
			'instagram' => $datos_formulario['instagram'],
			'facebook'  => $datos_formulario['facebook'],
			'linkedin'  => $datos_formulario['linkedin'],
			'youtube'   => $datos_formulario['youtube'],
		);

		$meta_input = array(
			'medilink_doctor_about_title' => $datos_formulario['title'],
			'medilink_doctor_about'       => $datos_formulario['about'],
			'medilink_designation'        => $datos_formulario['designation'],
			'medilink_degree'             => $datos_formulario['subespecialidad'],
			'medilink_whatsapp'           => $datos_formulario['whatsapp'],
			'medilink_phone'              => $datos_formulario['phone'],
			'medilink_email'              => $datos_formulario['email'],
			'medilink_website'            => $datos_formulario['website'],
			'medilink_doctor_os'          => $datos_formulario['doctor_os'],
			'medilink_doctor_social'      => $socials,
			// 'medilink_schedule_title'     => 'Horarios de consulta',
		);

		$meta_input += $days_sanitized;

		$thumbnail_id = 5360; // ID de la imagen destacada por defecto

		// Crear un array con los datos del post
		$nuevo_profesional = array(
			'post_type'     => 'medilink_doctor',
			'post_title'    => $datos_formulario['title'],
			'post_status'   => 'publish',
			'_thumbnail_id' => $thumbnail_id,
			'tax_input'     => array(
				'medilink_doctor_category' => $datos_formulario['especialidad_id'], // Asignar el término $id_especialidad de la taxonomía 'medilink_doctor_category'
			),
			'meta_input'    => $meta_input,
		);

		// Insertar el post y obtener su ID en caso de éxito
		$nuevo_profesional_id = wp_insert_post( $nuevo_profesional );

		// Si el post se ha creado correctamente...
		if ( $nuevo_profesional_id ) {
			// Obtener el link del post creado
			$nuevo_profesional_link = get_permalink( $nuevo_profesional_id );
			// Enviar un correo con los datos del post y un mensaje personalizado y guardar la confirmación de envío
			$enviado = enviar_correo_nuevo_profesional( $datos_formulario, $nuevo_profesional_link );
			// Enviar mensajes de confirmación o de error según el resultado del envío
			$res = $enviado ? array(
				'status' => 1,
				'msg'    => 'La página del profesional se ha creado correctamente y se ha dado aviso al administrador.',
				'link'   => $nuevo_profesional_link,
			)
				: array(
					'status' => 0,
					'msg'    => 'La página del profesional se ha creado correctamente pero no se ha podido dar aviso al administrador.',
					'link'   => $nuevo_profesional_link,
				);
		} else {
			// El post no se ha creado correctamente, enviar mensaje de error
			$res = array(
				'status' => 0,
				'msg'    => 'Lo siento ha habido un problema. Contacta con el administrador.',
			);
		}
	}

	// Llamar a wp_send_json($res) solo una vez al final de la función
	wp_send_json( $res );
}

// Enviar el correo con los datos del post y un mensaje personalizado
function enviar_correo_nuevo_profesional( $datos, $link ) {
	 // Cambiar el tipo de contenido del correo a HTML (usando el filtro wp_mail_content_type)
	add_filter(
		'wp_mail_content_type',
		function () {
			return 'text/html';
		}
	);

	// Definir el destinatario y el asunto del correo
	$destinatario = get_bloginfo( 'admin_email' );
	$asunto       = 'Nuevo profesional creado desde el frontend';

	// Definir el contenido del correo en formato HTML
	$especialidad = get_term( $datos['especialidad_id'], 'medilink_doctor_category' )->name;
	$doctor_os    = $datos['doctor_os'] ? 'Sí' : 'No';

	$contenido  = '<p>Se ha creado un nuevo post de tipo "medilink_doctor" desde el frontend con los siguientes datos:</p>';
	$contenido .= '<ul>';
	$contenido .= '<li>Título: ' . esc_html( $datos['title'] ) . '</li>';
	$contenido .= '<li>Acerca de: ' . esc_html( $datos['about'] ) . '</li>';
	$contenido .= '<li>Designación: ' . esc_html( $datos['designation'] ) . '</li>';
	$contenido .= '<li>Especialidad: ' . esc_html( $especialidad ) . '</li>';
	$contenido .= '<li>Subespecialidad: ' . esc_html( $datos['subespecialidad'] ) . '</li>';
	$contenido .= '<li>Teléfono: ' . esc_html( $datos['phone'] ) . '</li>';
	$contenido .= '<li>Acepta obras sociales: ' . esc_html( $doctor_os ) . '</li>';
	$contenido .= '</ul>';
	// Incluir el link del post creado en el contenido del correo
	$contenido .= '<p>Puedes revisar y editar el post desde el panel de administración o desde este <a href="' . esc_url( $link ) . '">enlace</a>.</p>';
	$contenido .= '<p>Gracias por usar nuestro servicio.</p>';

	// Definir las cabeceras del correo
	$cabeceras = array(
		'From: Vitttalia Web <noreply@vittalia.local>', // Indicar el remitente
		'Reply-To: Claudio <claumigue@gmail.com>', // Indicar a dónde responder
	);

	// Enviar el correo usando la función wp_mail() y guardar el resultado
	$resultado = wp_mail( $destinatario, $asunto, $contenido, $cabeceras );

	// Restablecer el tipo de contenido para evitar conflictos
	remove_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );

	return $resultado;
}

// Depurar errores de envío con wp_mail
add_action( 'wp_mail_failed', 'depurar_error_al_enviar_correo' );
function depurar_error_al_enviar_correo( $error ) {
	// Obtener el mensaje de error y los datos del correo que falló
	$mensaje_error = $error->get_error_message();
	$datos_correo  = $error->get_error_data();

	// Mostrar o guardar el mensaje de error y los datos del correo
	// echo 'El correo no se pudo enviar por el siguiente motivo: ' . $mensaje_error;
	error_log( 'El correo no se pudo enviar por el siguiente motivo: ' . $mensaje_error );
	error_log( print_r( $datos_correo, true ) );

}
