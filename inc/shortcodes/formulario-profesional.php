<?php

function create_form_shortcode() {

	// Obtener el ID del usuario actual
	$user_id = get_current_user_id();

	if ( $user_id ) {
		// Si hay un usuario conectado obtener el primer post de tipo medilink_doctor que haya creado el mismo
		$posts = get_posts(
			array(
				'post_type'      => 'medilink_doctor',
				'author'         => $user_id,
				'post_status'    => array( 'publish', 'pending' ),
				'posts_per_page' => 1,
				'orderby'        => 'date',
			)
		);
	}

	// Comprobar si el array $posts tiene algún elemento
	if ( ! empty( $posts ) ) {
		// Obtener el post con el primer elemento del array
		$post    = get_post( $posts[0] );
		$post_id = $post->ID;

		// Obtener los valores de los campos personalizados del post
		$title       = get_the_title( $post_id );
		$about       = get_post_meta( $post_id, 'medilink_doctor_about', true );
		$degree      = get_post_meta( $post_id, 'medilink_degree', true );
		$floor       = get_post_meta( $post_id, 'medilink_office_floor', true );
		$location    = get_post_meta( $post_id, 'medilink_office_location', true );
		$whatsapp    = get_post_meta( $post_id, 'medilink_whatsapp', true );
		$phone       = get_post_meta( $post_id, 'medilink_phone', true );
		$email       = get_post_meta( $post_id, 'medilink_email', true );
		$website     = get_post_meta( $post_id, 'medilink_website', true );
		$doctor_os   = get_post_meta( $post_id, 'medilink_doctor_os', true ) ? true : false;
		// $designation = get_post_meta( $post_id, 'medilink_designation', true );
		// Obtener la categoría del post
		$category = wp_get_object_terms( $post_id, 'medilink_doctor_category' );
		// Obtener los datos de las redes sociales del post
		$socials   = get_post_meta( $post_id, 'medilink_doctor_social', true );
		$instagram = $socials['instagram'];
		$facebook  = $socials['facebook'];
		$linkedin  = $socials['linkedin'];
		$youtube   = $socials['youtube'];
		$twitter   = $socials['twitter'];
	} else {
		// Dejar el post vacío o nulo
		$post = null;
		// Si no hay un id, asignar valores vacíos a los campos
		$title       = '';
		$about       = '';
		$degree      = '';
		$floor       = '';
		$location    = 'front';
		$whatsapp    = '';
		$phone       = '';
		$email       = '';
		$website     = '';
		$doctor_os   = false;
		$category    = array();
		$instagram   = '';
		$facebook    = '';
		$linkedin    = '';
		$youtube     = '';
		$twitter     = '';
		// $designation = '';
	}
	// print '<pre>';print_r($post);print '</pre>';die();

	// Encolar el script
	wp_enqueue_script( 'fnp-fields', get_stylesheet_directory_uri() . '/assets/js/fields-nuevo-profesional.js', array(), '1.0.0', true );

	// Crear un WP nonce con el nombre 'crear_profesional'
	// $nonce = wp_create_nonce('crear_profesional');

	// Start output buffering
	ob_start();
	?>
	<form id="nuevoProfesional" method="post" action="" class="wpcf7-form np-form">
		<!-- Añadir el WP nonce al formulario como un campo oculto -->
		<?php
		// wp_nonce_field('crear_profesional', 'profesional_nonce');
		?>

		<!-- Añadir un campo oculto con el ID del post, si existe, o con un valor vacío, si no existe -->
		<input type="hidden" id="post_id" name="post_id" value="<?php echo $post ? esc_attr( $post_id ) : ''; ?>">

		<!-- Añadir los demás campos del formulario -->
		<div class="npf-title mb-5 pt-5">
			<label class="h3" for="doctor_title">
				<img class="d-inline" src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/img/user.png" alt="<?php esc_html_e( 'figure', 'medilink' ); ?>">
				<span class="ml-2 align-middle">Tit., Apellido y Nombre</span>
			</label>
			<input type="text" id="doctor_title" name="doctor_title" size="40" value="<?php echo esc_attr( $title ); ?>" required>
		</div>
		
		<?php
		// Obtener la lista desplegable HTML de términos de la taxonomía 'medilink_doctor_category'
		$especialidades = wp_dropdown_categories(
			array(
				'taxonomy'          => 'medilink_doctor_category',
				'hierarchical'      => true,
				'orderby'           => 'name',
				'hide_empty'        => false,
				'echo'              => false,
				'id'                => 'doctor_category',
				'name'              => 'doctor_category',
				'show_option_none'  => 'Selecciona una opción',
				'option_none_value' => '',
				// Seleccionar el término del post si existe
				'selected'          => ! empty( $category ) ? $category[0]->term_id : '',
				'required'          => true,
				'class'             => 'np-form--select',
			)
		);
		?>
		<!-- Imprimir la lista desplegable mostrando los términos como opciones -->
		<div class="npf-category mb-5 pt-5"><label class="h3" for="doctor_category">Especialidad</label>
			<?php echo $especialidades; ?>
		</div>
		
		<div class="npf-degree mb-5 pt-5"><label class="h3" for="doctor_degree">Subspecialidad</label>
			<input type="text" id="doctor_degree" name="doctor_degree" size="40" value="<?php echo esc_attr( $degree ); ?>" required>
		</div>

		<div class="npf-about mb-5 pt-5"><label class="h3" for="doctor_about">Bio</label>
				<textarea id="doctor_about" name="doctor_about" cols="40" rows="10" required><?php echo esc_textarea( $about ); ?></textarea>
		</div>
		
		<div class="location mb-5 pt-5">
			<p class="h3">
				<img class="d-inline" src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/img/building.png" alt="<?php esc_html_e( 'figure', 'medilink' ); ?>">
				<span class="ml-2 align-middle">Ubicación</span>
			</p>
			<div class="d-flex flex-wrap align-items-baseline">
				<div class="office-floor d-flex align-items-baseline">
					<label for="office_floor">Piso</label>
					<input type="number" id="office_floor" name="office_floor" min="2" max="15" placeholder="2" value="<?php echo esc_attr( $floor ); ?>" required>
				</div><!-- .office-floor -->
				<div class="office-location">
					<label class="d-inline" for="front"><input type="radio" id="front" name="office_location" value="front" <?php checked( $location, 'front' ); ?>> Frente</label>
					<label class="d-inline" for="quiet"><input type="radio" id="quiet" name="office_location" value="quiet" <?php checked( $location, 'quiet' ); ?>> Contrafrente</label>
					<label class="d-inline" for="full"><input type="radio" id="full" name="office_location" value="full" <?php checked( $location, 'full' ); ?>> Piso completo</label>
				</div><!-- .office-location -->
			</div>
		</div><!-- .location -->

		<div class="consultation-hours pt-5">
			<p class="h3">
				<img class="d-inline" src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/img/almanac.png" alt="<?php esc_html_e( 'figure', 'medilink' ); ?>">
				<span class="ml-2 align-middle">Días y horarios de atención</span>
			</p>
			<?php
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

			// Usar un bucle foreach para recorrer el array
			foreach ( $days as $day_en => $day_es ) {
				// Obtener el nombre del día en inglés y en minúsculas
				$day_en = strtolower( $day_en );

				// Obtener el valor del campo personalizado correspondiente al día
				$day_data = get_post_meta( $post_id, "medilink_doctor_horarios_{$day_en}", true );
				// Comprobar si el campo tiene datos o no
				if ( ! empty( $day_data ) ) {
					// Si tiene datos, extraer los valores de los segmentos horarios
					$start_a = $day_data['aa_time'];
					$end_a   = $day_data['ab_time'];
					$start_b = isset( $day_data['ba_time'] ) ? $day_data['ba_time'] : '';
					$end_b   = isset( $day_data['bb_time'] ) ? $day_data['bb_time'] : '';
					// Comprobar si el día está activo o no
					$checked = $day_data['active'] == 'true';
				} else {
					// Si no tiene datos, asignar valores vacíos a los segmentos horarios
					$start_a = '';
					$end_a   = '';
					$start_b = '';
					$end_b   = '';
					// Asignar false al estado del día
					$checked = false;
				}

				// Imprimir el marcado html con el nombre del día en español y el valor en inglés
				echo "<div class='day-service-hours'>
					<input type='checkbox' id='$day_en' name='$day_en' value='true' class='day-checkbox'" . checked( $checked, true, false ) . ">
					<label for='$day_en'>$day_es</label>
					<input type='hidden' id='{$day_en}-hidden' name='$day_en' value='false'>
					<div class='time'>
						<div class='start-time'>
							<label for='{$day_en}_start_a'>A-desde:</label>
							<input type='time' id='{$day_en}_start_a' name='{$day_en}_start_a' min='08:00' max='22:00' value='$start_a'>
						</div>
						<div class='end-time'>
							<label for='{$day_en}_end_a'>A-hasta:</label>
							<input type='time' id='{$day_en}_end_a' name='{$day_en}_end_a' min='08:00' max='22:00' value='$end_a'>
						</div>
						<br>
						<div class='start-time'>
							<label for='{$day_en}_start_b'>B-desde:</label>
							<input type='time' id='{$day_en}_start_b' name='{$day_en}_start_b' min='08:00' max='22:00'" . disabled( ! $checked, true, false ) . " value='$start_b'>
						</div>
						<div class='end-time'>
							<label for='{$day_en}_end_b'>B-hasta:</label>
							<input type='time' id='{$day_en}_end_b' name='{$day_en}_end_b' min='08:00' max='22:00'" . disabled( ! $checked, true, false ) . " value='$end_b'>
						</div>
					</div>
				</div>";

			}
			?>
		</div><!-- .consultation-hours -->
		
		<div class="npf-os mb-5 pt-5">
			<p class="h3">¿Acepta obras sociales?</p>
			<label for="doctor_os">Sí
				<input class="ml-2" type="checkbox" id="doctor_os" name="doctor_os" value="1" <?php checked( $doctor_os, true ); ?>></label>
		</div>
		
		<div class="npf-info">
			<p class="h3">Info de contacto para pacientes</p>
			<p><label for="doctor_whatsapp"><i class="fab fa-whatsapp" aria-hidden="true"></i> Celular
			<input type="tel" id="doctor_whatsapp" name="doctor_whatsapp" minlength="10" placeholder="381-444-4444" list="defaultTels" value="<?php echo esc_attr( $whatsapp ); ?>"></label>
			</p>
			
			<p><label for="doctor_phone"><i class="fa fa-phone" aria-hidden="true"></i> Fijo
			<input type="tel" id="doctor_phone" name="doctor_phone" minlength="10" placeholder="381-444-4444" list="defaultTels" value="<?php echo esc_attr( $phone ); ?>"></label>
			</p>
	
			<datalist id="defaultTels">
				<option value="381-1111-1111"></option>
				<option value="3865-22-2222"></option>
				<option value="11-3333-3333"></option>
			</datalist>
	
			<p><label for="doctor_email"><i class="far fa-envelope" aria-hidden="true"></i> E-mail
			<input type="email" id="doctor_email" name="doctor_email" size="40" value="<?php echo esc_attr( $email ); ?>"></label>
			</p>
	
			<p><label for="doctor_web"><i class="fas fa-globe"></i> Sitio web
			<input type="url" id="doctor_web" name="doctor_web" size="40" placeholder="(OPCIONAL)" value="<?php echo esc_attr( $website ); ?>"></label>
			</p>
			
			<div class="redes-sociales"><p class="h4">Redes sociales</p>
				<p><label for="doctor_instagram"><i class="fab fa-instagram"></i> Instagram
					<input type="url" id="doctor_instagram" name="doctor_instagram" size="40" value="<?php echo esc_attr( $instagram ); ?>"></label>
				</p>
				<p><label for="doctor_facebook"><i class="fab fa-facebook-f"></i> Facebook
					<input type="url" id="doctor_facebook" name="doctor_facebook" size="40" value="<?php echo esc_attr( $facebook ); ?>"></label>
				</p>
				<p><label for="doctor_linkedin"><i class="fab fa-linkedin-in"></i> Linkedin
					<input type="url" id="doctor_linkedin" name="doctor_linkedin" size="40" value="<?php echo esc_attr( $linkedin ); ?>"></label>
				</p>
				<p><label for="doctor_youtube"><i class="fab fa-youtube"></i> Youtube
					<input type="url" id="doctor_youtube" name="doctor_youtube" size="40" value="<?php echo esc_attr( $youtube ); ?>"></label>
				</p>
				<p><label for="doctor_twitter"><i class="fab fa-twitter"></i> Twitter
					<input type="url" id="doctor_twitter" name="doctor_twitter" size="40" value="<?php echo esc_attr( $twitter ); ?>"></label>
				</p>
				<!-- <p><label for="doctor_twitter"><img class="d-inline" style="max-width: 1em;" src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/svg/x-twitter-2.svg" alt="<?php esc_html_e( 'icon', 'medilink' ); ?>"> Twitter
					<input type="url" id="doctor_twitter" name="doctor_twitter" size="40" value="<?php echo esc_attr( $twitter ); ?>"></label>
				</p> -->
			</div>
		</div>
		
		<!-- Añadir un botón para enviar el formulario -->
		<p><button id="submit" type="submit"><?php echo $post_id ? 'Actualizar datos' : 'Enviar datos'; ?></button></p>
		
		<!-- Añadir un campo para los mensajes de confirmación y error -->
		<p class="status-msg" style="display: none"></p>

	</form>
	
	<?php
	// End output buffering and return the form HTML
	return ob_get_clean();
}
add_shortcode( 'new_doctor_form', 'create_form_shortcode' ); // Now you can use the shortcode [new_doctor_form] in any page or post where you want to display the form.
