<?php

/**
 * Obtener los posts de tipo medilink_doctor que pertenecen a un término de la taxonomía medilink_doctor_category
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
