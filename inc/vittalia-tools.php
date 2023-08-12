<?php

/**
 * Insertar términos de forma masiva
 */
function vittalia_add_terms_bulk_array( $terms_array ) {

	if ( isset( $_GET['agregarterminos'] ) && absint( $_GET['agregarterminos'] ) == 1 && $terms_array ) {

			// Crea un array vacío para almacenar los resultados de la inserción de los términos
			$result = array();

			foreach ( $terms_array as $term_array ) {

				// Usa la función list para asignar cada elemento del subarray a una variable
				list( $term, $taxonomy, $parent ) = $term_array;
				// Obtener el ID del término padre
				$parent = $parent ? term_exists( $parent, $taxonomy )['term_id'] : null;

				// Usa la función wp_insert_term para insertar cada término en la taxonomía indicada y asignarle el término padre si se pasa como parámetro
				$result[] = wp_insert_term( $term, $taxonomy, array( 'parent' => $parent ) );

			}
			// Imprime el array con los resultados de la inserción de los términos y termina la ejecución del script
			print '<pre>'; print_r( $result ); print '</pre>'; die();
	}
}

// Define el array de arrays con los términos que quieres agregar
// $terms_array = array(
// 	array( 'Test10', 'medilink_doctor_category', 'Odontología' ),
// 	array( 'Test20', 'medilink_doctor_category', 'dermatologia' ),
// 	array( 'Test30', 'medilink_doctor_category', 105 ),
// );

// Usa add_action para agregar la función tool_add_terms_bulk_array al hook init
add_action(
	'init',
	function() use ( $terms_array ) {
		// Llama a la función tool_add_terms_bulk_array pasándole el argumento
		vittalia_add_terms_bulk_array( $terms_array );
	}
);
