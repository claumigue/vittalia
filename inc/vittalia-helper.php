<?php

/**
 * Función para obtener los posts de tipo medilink_doctor que pertenecen a un término de la taxonomía medilink_doctor_category
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