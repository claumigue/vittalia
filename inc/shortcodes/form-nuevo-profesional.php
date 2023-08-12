<?php

function create_form_shortcode() {

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

		<!-- Añadir los demás campos del formulario -->
		<p><label for="doctor_title">Título
				<input type="text" id="doctor_title" name="doctor_title" size="40" required></label>
		</p>

		<p><label for="doctor_about">Acerca de
				<textarea id="doctor_about" name="doctor_about" cols="40" rows="10" required></textarea></label>
		</p>
		
		<p><label for="doctor_designation">Designación
				<input type="text" id="doctor_designation" name="doctor_designation" size="40" required></label>
		</p>
		
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
				'selected'          => '',
				'required'          => true,
				'class'             => 'np-form--select',
			)
		);
		?>
		<!-- Imprimir la lista desplegable mostrando los términos como opciones -->
		<p><label for="doctor_category">Especialidad
			<?php echo $especialidades; ?></label>
		</p>

		
		<p><label for="doctor_degree">Subspecialidad
			<input type="text" id="doctor_degree" name="doctor_degree" size="40" required></label>
		</p>
		
		<p><label for="office_floor">Piso
			<input type="number" id="office_floor" name="office_floor" min="2" max="15" placeholder="(del 2 al 15)" required></label>
		</p>
		
		<p><label for="office_location">Ubicación del consultorio
			<select id="office_location" name="office_location">
				<option value="" disabled selected>Seleccionar ubicación</option>
				<option value="front">Frente</option>
				<option value="quiet">Contrafrente</option>
				<option value="full">Piso completo</option>
			</select></label>
		</p>
		
		<div class="consultation-hours"><strong>Horarios</strong>
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
				// Imprimir el marcado html con el nombre del día en español y el valor en inglés
				echo "<div class='day-service-hours'>
					<input type='checkbox' id='$day_en' name='$day_en' value='true' class='day-checkbox'>
					<label for='$day_en'>$day_es</label>
					<input type='hidden' id='{$day_en}-hidden' name='$day_en' value='false'>
					<div class='time'>
						<div class='start-time'>
							<label for='{$day_en}_start_a'>A-desde:</label>
							<input type='time' id='{$day_en}_start_a' name='{$day_en}_start_a' min='08:00' max='21:00'>
						</div>
						<div class='end-time'>
							<label for='{$day_en}_end_a'>A-hasta:</label>
							<input type='time' id='{$day_en}_end_a' name='{$day_en}_end_a' min='08:00' max='21:00'>
						</div>
						<br>
						<div class='start-time'>
							<label for='{$day_en}_start_b'>B-desde:</label>
							<input type='time' id='{$day_en}_start_b' name='{$day_en}_start_b' min='08:00' max='21:00' disabled>
						</div>
						<div class='end-time'>
							<label for='{$day_en}_end_b'>B-hasta:</label>
							<input type='time' id='{$day_en}_end_b' name='{$day_en}_end_b' min='08:00' max='21:00' disabled>
						</div>
					</div>
				</div>";
			}
			?>
		</div>
		
		<p><label for="doctor_os"><input type="checkbox" id="doctor_os" name="doctor_os" value="1"> ¿Acepta obras sociales?</label></p>
		
		<p><label for="doctor_whatsapp"><i class="fab fa-whatsapp" aria-hidden="true"></i> Celular
			<input type="tel" id="doctor_whatsapp" name="doctor_whatsapp" minlength="10" placeholder="381-444-4444" list="defaultTels"></label>
		</p>
		
		<p><label for="doctor_phone"><i class="fa fa-phone" aria-hidden="true"></i> Fijo
			<input type="tel" id="doctor_phone" name="doctor_phone" minlength="10" placeholder="381-444-4444" list="defaultTels"></label>
		</p>

		<datalist id="defaultTels">
			<option value="381-1111-1111"></option>
			<option value="3865-22-2222"></option>
			<option value="11-3333-3333"></option>
		</datalist>

		<p><label for="doctor_email"><i class="far fa-envelope" aria-hidden="true"></i> E-mail
			<input type="email" id="doctor_email" name="doctor_email" size="40"></label>
		</p>

		<p><label for="doctor_web"><i class="fas fa-globe"></i> Sitio web
			<input type="url" id="doctor_web" name="doctor_web" size="40"></label>
		</p>
		
		<div class="redes-sociales"><p><strong>Redes sociales</strong></p>

			<p><label for="doctor_instagram"><i class="fab fa-instagram"></i> Instagram
				<input type="url" id="doctor_instagram" name="doctor_instagram" size="40"></label>
			</p>
	
			<p><label for="doctor_facebook"><i class="fab fa-facebook-f"></i> Facebook
				<input type="url" id="doctor_facebook" name="doctor_facebook" size="40"></label>
			</p>
	
			<p><label for="doctor_linkedin"><i class="fab fa-linkedin-in"></i> Linkedin
				<input type="url" id="doctor_linkedin" name="doctor_linkedin" size="40"></label>
			</p>
	
			<p><label for="doctor_youtube"><i class="fab fa-youtube"></i> Youtube
				<input type="url" id="doctor_youtube" name="doctor_youtube" size="40"></label>
			</p>

		</div>
		
		<!-- Añadir un botón para enviar el formulario -->
		<p><button id="submit" type="submit">Enviar datos</button></p>
		
		<!-- Añadir un campo para los mensajes de confirmación y error -->
		<p class="status-msg" style="display: none"></p>

	</form>
	
	<?php
	// End output buffering and return the form HTML
	return ob_get_clean();
}
add_shortcode( 'new_doctor_form', 'create_form_shortcode' ); // Now you can use the shortcode [new_doctor_form] in any page or post where you want to display the form.
