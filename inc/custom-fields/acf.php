<?php
add_action(
	'acf/include_fields',
	function() {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		acf_add_local_field_group(
			array(
				'key'                   => 'group_64c184c1f122d',
				'title'                 => 'Especialidades Extras',
				'fields'                => array(
					array(
						'key'               => 'field_64c184c38fc79',
						'label'             => 'Filtrar por especialidad',
						'name'              => 'doctor_category',
						'aria-label'        => '',
						'type'              => 'taxonomy',
						'instructions'      => 'Seleccionar categoría para filtrar los profesionales que se mostrarán en la página',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '50',
							'class' => '',
							'id'    => '',
						),
						'taxonomy'          => 'medilink_doctor_category',
						'add_term'          => 0,
						'save_terms'        => 0,
						'load_terms'        => 0,
						'return_format'     => 'id',
						'field_type'        => 'select',
						'allow_null'        => 1,
						'multiple'          => 0,
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'medilink_departments',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => '',
				'show_in_rest'          => 0,
			)
		);
	}
);
