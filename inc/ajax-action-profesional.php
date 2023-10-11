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
			'especialidad_id' => (int) $_POST['especialidad'], // Obtener el ID de la especialidad seleccionada en el formulario
			'subespecialidad' => sanitize_text_field( $_POST['subespecialidad'] ),
			'about'           => sanitize_textarea_field( $_POST['about'] ),
			'floor'           => $floor ? $floor : 2, // Asignar un valor por defecto en caso de que el dato recibido por $_POST[‘floor’] no sea válido
			'location'        => sanitize_text_field( $_POST['location'] ),
			'doctor_os'       => filter_var( $_POST['doctor_os'], FILTER_VALIDATE_BOOLEAN ), // Validar el valor del checkbox como booleano
			'med_group'       => sanitize_text_field( $_POST['med_group'] ),
			'whatsapp'        => sanitize_text_field( $_POST['whatsapp'] ),
			'phone'           => sanitize_text_field( $_POST['phone'] ),
			'email'           => sanitize_email( $_POST['email'] ),
			'website'         => sanitize_url( $_POST['website'] ),
			'instagram'       => sanitize_url( $_POST['instagram'] ),
			'facebook'        => sanitize_url( $_POST['facebook'] ),
			'linkedin'        => sanitize_url( $_POST['linkedin'] ),
			'youtube'         => sanitize_url( $_POST['youtube'] ),
			'twitter'         => sanitize_url( $_POST['twitter'] ),
		);

		// Obtener los datos de los horarios
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
			'twitter'   => $datos_formulario['twitter'],
		);

		$meta_input = array(
			'medilink_doctor_about_title' => $datos_formulario['title'],
			'medilink_doctor_about'       => $datos_formulario['about'],
			'medilink_designation'        => $datos_formulario['subespecialidad'],
			'medilink_office_floor'       => $datos_formulario['floor'],
			'medilink_office_location'    => $datos_formulario['location'],
			'medilink_doctor_os'          => $datos_formulario['doctor_os'],
			'medilink_medical_group'      => $datos_formulario['med_group'],
			'medilink_whatsapp'           => $datos_formulario['whatsapp'],
			'medilink_phone'              => $datos_formulario['phone'],
			'medilink_email'              => $datos_formulario['email'],
			'medilink_website'            => $datos_formulario['website'],
			'medilink_doctor_social'      => $socials,
			// 'medilink_schedule_title'     => 'Horarios de consulta',
		);

		$meta_input += $days_sanitized;

		$thumbnail_id = isset( $_POST['thumbnail'] ) ? (int) $_POST['thumbnail'] : 0;

		// Obtener el ID del post si existe
		$post_id = isset( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		// Crear un array con los datos del post
		$_post_array = array(
			'post_type'   => 'medilink_doctor',
			'post_title'  => $datos_formulario['title'],
			'post_status' => 'pending',
			'tax_input'   => array(
				'medilink_doctor_category' => $datos_formulario['especialidad_id'], // Asignar el término $id_especialidad de la taxonomía 'medilink_doctor_category'
			),
			'meta_input'  => $meta_input,
		);

		// Comprobar si hay un ID del post
		if ( $post_id ) {
			// Si hay un ID, actualizar el post existente con los nuevos datos
			$_post_array['ID'] = $post_id;

			// Limpiar campos de horarios
			vittalia_delete_horarios_postmeta( $post_id );

			$profesional_id = wp_update_post( $_post_array );
			$post_action    = 'update';
		} else {
			// Si no hay un ID, agregar la imagen destacada por defecto
			$_post_array['_thumbnail_id'] = $thumbnail_id;

			// Crear un nuevo post con los datos del formulario
			$profesional_id = wp_insert_post( $_post_array );
			$post_action    = 'create';
		}

		// Si el post se ha creado o actualizado correctamente...
		if ( $profesional_id ) {
			// Obtener el link del post creado o actualizado
			$profesional_link = get_permalink( $profesional_id );
			// Enviar un correo con los datos del post y un mensaje personalizado y guardar la confirmación de envío
			$enviado = enviar_correo_nuevo_profesional( $datos_formulario, $days_array, $profesional_link, $post_action );
			// Enviar mensajes de confirmación o de error según el resultado del envío
			// Cambiar el mensaje según si hay un ID o no
			$res = $enviado ? array(
				'status' => 1,
				'msg'    => $post_id ? 'La página del profesional se ha actualizado correctamente y se ha dado aviso al administrador. Una vez que la apruebe estará visible en el sitio web.' : 'La página del profesional se ha creado correctamente y se ha dado aviso al administrador. Una vez que la apruebe estará visible en el sitio web.',
				'link'   => $profesional_link,
			)
				: array(
					'status' => 0,
					'msg'    => $post_id ? 'La página del profesional se ha actualizado correctamente pero no se ha podido enviar el mail de aviso. Contacta con el administrador.' : 'La página del profesional se ha creado correctamente pero no se ha podido enviar el mail de aviso. Contacta con el administrador.',
					'link'   => $profesional_link,
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
function enviar_correo_nuevo_profesional( $datos, $horarios, $link, $post_action ) {
	// Cambiar el tipo de contenido del correo a HTML (usando el filtro wp_mail_content_type)
	add_filter(
		'wp_mail_content_type',
		function () {
			return 'text/html'; }
	);

	// Definir destinatarios del correo
	$destinatarios    = array();
	$main_admin_email = get_bloginfo( 'admin_email' );
	$admin_emails     = get_admin_emails();
	$editor_emails    = get_editor_emails();
	$to_admins        = (int) $_POST['to_admins'];
	$to_editors       = (int) $_POST['to_editors'];

	if ( ! $to_admins && ! $to_editors ) {
		$destinatarios[] = $main_admin_email;
	}
	$_admin_emails  = array_filter(
		$admin_emails,
		function( $email ) use ( $to_admins ) {
			return $to_admins;
		}
	);
	$_editor_emails = array_filter(
		$editor_emails,
		function( $email ) use ( $to_editors ) {
			return $to_editors;
		}
	);
	$destinatarios  = array_merge( $destinatarios, $_admin_emails, $_editor_emails );

	// Definir el asunto del correo según la acción
	if ( $post_action == 'create' ) {
		$asunto = 'Nuevo perfil creado desde el Panel Profesional';
	} else {
		$asunto = 'Perfil actualizado desde el Panel Profesional';
	}

	// Obtener la URL del sitio
	$site_url = get_site_url();
	// Extraer el dominio de la URL
	$site_domain = parse_url( $site_url, PHP_URL_HOST );

	// Definir el contenido del correo en formato HTML
	$especialidad = get_term( $datos['especialidad_id'], 'medilink_doctor_category' )->name;
	$doctor_os    = $datos['doctor_os'] ? 'Sí' : 'No';
	switch ( $datos['location'] ) {
		case 'front':
			$ubicacion = 'Frente';
			break;
		case 'quiet':
			$ubicacion = 'Contrafrente';
			break;
		default:
			$ubicacion = 'Piso completo';
			break;
	}

	$contenido  = '<p>Se ha ' . ( $post_action == 'create' ? 'creado' : 'actualizado' ) . ' el perfil de un profesional con los siguientes datos:</p>';
	$contenido .= '<ul>';
	$contenido .= '<li>Título: ' . esc_html( $datos['title'] ) . '</li>';
	$contenido .= '<li>Acerca de: ' . esc_html( $datos['about'] ) . '</li>';
	$contenido .= '<li>Especialidad: ' . esc_html( $especialidad ) . '</li>';
	$contenido .= '<li>Subespecialidad: ' . esc_html( $datos['subespecialidad'] ) . '</li>';
	// Añadir los campos que faltan
	$contenido .= '<li>Piso: ' . esc_html( $datos['floor'] ) . '</li>';
	$contenido .= '<li>Ubicación: ' . esc_html( $ubicacion ) . '</li>';
	$contenido .= '<li>Acepta obras sociales: ' . esc_html( $doctor_os ) . '</li>';
	$contenido .= '<li>Grupo médico: ' . esc_html( $datos['med_group'] ) . '</li>';
	$contenido .= '<li>Sitio web: ' . esc_html( $datos['website'] ) . '</li>';
	$contenido .= '<li>Instagram: ' . esc_html( $datos['instagram'] ) . '</li>';
	$contenido .= '<li>Facebook: ' . esc_html( $datos['facebook'] ) . '</li>';
	$contenido .= '<li>Linkedin: ' . esc_html( $datos['linkedin'] ) . '</li>';
	$contenido .= '<li>Youtube: ' . esc_html( $datos['youtube'] ) . '</li>';
	$contenido .= '<li>Twitter: ' . esc_html( $datos['twitter'] ) . '</li>';
	$contenido .= '<li>Teléfono: ' . esc_html( $datos['phone'] ) . '</li>';
	$contenido .= '</ul>';

	// Añadir una tabla con los horarios
	$contenido .= '<p>Estos son los horarios de consulta:</p>';
	$contenido .= '<table border="1">';
	$contenido .= '<tr><th>Día</th><th>Segmento A</th><th>Segmento B</th></tr>';
	// Crear un array asociativo con los nombres de los días en inglés y español
	$days = array(
		'mon' => 'Lunes',
		'tue' => 'Martes',
		'wed' => 'Miércoles',
		'thu' => 'Jueves',
		'fri' => 'Viernes',
		'sat' => 'Sábado',
		'sun' => 'Domingo',
	);
	// Usar un bucle for para recorrer el array de los horarios
	for ( $i = 0; $i < count( $horarios ); $i++ ) {
		// Obtener el nombre del día, el tiempo de inicio y fin de cada segmento
		$en_day  = $horarios[ $i ]['day'];
		$es_day  = $days[ $en_day ];
		$start_a = $horarios[ $i ]['start_a'];
		$end_a   = $horarios[ $i ]['end_a'];
		// Comprobar si hay datos del segmento B
		if ( isset( $horarios[ $i ]['start_b'] ) && isset( $horarios[ $i ]['end_b'] ) ) {
			$start_b = $horarios[ $i ]['start_b'];
			$end_b   = $horarios[ $i ]['end_b'];
		} else {
			// Si no hay datos, asignar valores vacíos
			$start_b = '';
			$end_b   = '';
		}
		// Imprimir una fila de la tabla con los datos del día
		$contenido .= "<tr><td>$es_day</td><td>$start_a - $end_a</td><td>$start_b - $end_b</td></tr>";
	}
	$contenido .= '</table>';

	// Incluir el link del post creado o actualizado en el contenido del correo
	// Cambiar el texto según la acción
	if ( $post_action == 'create' ) {
		$contenido .= '<p>El nuevo profesional ha sido creado desde el formulario del Panel Profesional.</p>';
	} else {
		$contenido .= '<p>El profesional ha sido actualizado desde el formulario del Panel Profesional.</p>';
	}
	$contenido .= '<p>Puedes revisar y editar el post desde el panel de administración o desde este <a href="' . esc_url( $link ) . '">enlace</a>.</p>';

	// Definir las cabeceras del correo
	$cabeceras = array(
		"From: Vitttalia Web <noreply@$site_domain>", // remitente
		"Reply-To: Administrador del sitio <$main_admin_email>", // dónde responder
	);
	// Cc header
	if ( $cc_list = validate_emails( $_POST['cc_list'] ) ) {
		$cabeceras[] = "Cc: $cc_list";
	}

	// Enviar el correo usando la función wp_mail() y guardar el resultado
	$resultado = wp_mail( $destinatarios, $asunto, $contenido, $cabeceras );

	// Restablecer el tipo de contenido para evitar conflictos
	remove_filter( 'wp_mail_content_type', '__return_false' );

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
