<?php

/**
 * Obtener los posts de tipo medilink_doctor que pertenecen a un término de la taxonomía medilink_doctor_category
 * (profesionales por especialidad)
 *
 * @param int $term El ID del término de la taxonomía
 * @return array El array de posts que cumplen con la condición
 */
function vittalia_get_department_doctors( $term ) {
	$_args  = array(
		'post_type' => 'medilink_doctor',
		'tax_query' => array(
			array(
				'taxonomy' => 'medilink_doctor_category',
				'field'    => 'term_id',
				'terms'    => $term,
			),
		),
	);
	$_query = new WP_Query( $_args );
	$_posts = $_query->posts; // obtienes el array de posts
	// $_ids = wp_list_pluck($_posts, 'ID'); // obtienes el array de ids
	wp_reset_postdata();
	return $_posts;
}

/**
 * Borrar los metadatos de tipo horario de determinado post
 *
 * @param int $post_id El ID del post
 */
function vittalia_delete_horarios_postmeta( $post_id ) {

	if ( get_post_type( $post_id ) == 'medilink_doctor' ) {

		// Definir el array con los nombres de los metadatos
		$meta_keys = array(
			'medilink_doctor_horarios_mon',
			'medilink_doctor_horarios_tue',
			'medilink_doctor_horarios_wed',
			'medilink_doctor_horarios_thu',
			'medilink_doctor_horarios_fri',
			'medilink_doctor_horarios_sat',
			'medilink_doctor_horarios_sun',
		);

		// Recorrer el array y borrar cada metadato
		foreach ( $meta_keys as $meta_key ) {
			delete_post_meta( $post_id, $meta_key );
		}
	}
}

/**
 * Obtiene el correo de todos los administradores del sitio
 *
 * @return array
 */
function get_admin_emails() {
	$users  = get_users( array( 'role' => 'administrator' ) );
	$emails = array();
	if ( ! empty( $users ) ) {
		$emails = wp_list_pluck( $users, 'user_email' );
	}

	return $emails;
}

/**
 * Obtiene el correo de todos los editores del sitio
 *
 * @return array
 */
function get_editor_emails() {
	$users  = get_users( array( 'role' => 'editor' ) );
	$emails = array();
	if ( ! empty( $users ) ) {
		$emails = wp_list_pluck( $users, 'user_email' );
	}

	return $emails;
}

/**
 * Define a function that takes a string of emails as an argument and validates them.
 *
 * @param string $email_string The string of emails separated by commas.
 * @param bool   $return_array Whether to return an array with valid and invalid emails or just a string of valid emails.
 * @return mixed An array with valid and invalid emails or a string of valid emails.
 */
function validate_emails( $email_string, $return_array = false ) {
	// Split the string by commas and trim any whitespace.
	$emails = array_map( 'trim', explode( ',', $email_string ) );

	// Loop through each email and check if it is valid.
	$valid_emails   = array();
	$invalid_emails = array();
	foreach ( $emails as $email ) {
		if ( is_email( $email ) ) {
			// Add the valid email to the array.
			$valid_emails[] = $email;
		} else {
			// Add the invalid email to the array.
			$invalid_emails[] = $email;
		}
	}

	// Return an array with two elements: the valid email string and the invalid email string.
	$email_array = array(
		'valid'   => implode( ', ', $valid_emails ),
		'invalid' => implode( ', ', $invalid_emails ),
	);

	// Get the current date and time in the format of YYYY-MM-DD HH:MM:SS.
	$date_time = date( 'Y-m-d H:i:s' );

	// Log the invalid email string to a file named 'invalid_emails.log' with the date and time.
	if ( $email_array['invalid'] ) {
		error_log( '[' . $date_time . '] Shortcode new_doctor_form, "cc" attribute: there are some invalid emails in the string: ' . $email_array['invalid'], 0, 'invalid_emails.log' );
	}

	// Check if the return_array parameter is true or false.
	if ( true === $return_array ) {
		// Return the email array.
		return $email_array;
	} else {
		// Return the valid email string.
		return $email_array['valid'];
	}
}
