jQuery(document).ready(function ($) {
	'use strict';

	$('#nuevoProfesional').submit(function (e) {
		e.preventDefault();
		// Obtener el estado del checkbox #doctor_os (true o false)
		const doctor_os_checked = $('#doctor_os').prop('checked');
		// Asignar el valor 0 o 1 según el estado
		let doctor_os_value = doctor_os_checked ? 1 : 0;

		// Crear un array vacío para almacenar los datos de cada día
		let days_data = [];
		
		// Crear un array con los nombres de los días en inglés
		let days_en = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
		
		// Usar un bucle for para recorrer el array de los días
		days_en.forEach(day_en => {
			// Obtener el valor del checkbox, el tiempo de inicio y el tiempo de fin de cada día
			let day_checked = $('#' + day_en).prop('checked');
			if (day_checked) {
				
				let day_start_a = $('#' + day_en + '_start_a').val();
				let day_end_a = $('#' + day_en + '_end_a').val();
				let day_start_b = $('#' + day_en + '_start_b').val();
				let day_end_b = $('#' + day_en + '_end_b').val();

				// Crear un objeto con las propiedades day, start y end y asignarles los valores obtenidos
				let day_data = {
					day: day_en,
					checked: true,
					start_a: day_start_a,
					end_a: day_end_a,
				};

				if (day_start_b && day_end_b) {
					day_data.start_b = day_start_b;
					day_data.end_b = day_end_b;
				}

				// Añadir el objeto al array usando el método push()
				days_data.push(day_data);
			}
		});

        // Obtener el ID del post si existe
        let post_id = $('#post_id').val();

        // Crear un objeto con los datos del formulario y el ID del post si existe
        let data = {
            action: 'vittalia_ajax_form',
            _ajax_nonce: ajax_object.nonce,
            title: $('#doctor_title').val(),
            especialidad: $('#doctor_category').val(),
            subespecialidad: $('#doctor_designation').val(),
            about: $('#doctor_about').val(),
            floor: $('#office_floor').val(),
            location: $('input:radio[name="office_location"]:checked').val(),
            doctor_os: doctor_os_value,
            med_group: $('#medical_group').val(),
            whatsapp: $('#doctor_whatsapp').val(),
            phone: $('#doctor_phone').val(),
            email: $('#doctor_email').val(),
            website: $('#doctor_web').val(),
            instagram: $('#doctor_instagram').val(),
            facebook: $('#doctor_facebook').val(),
            linkedin: $('#doctor_linkedin').val(),
            youtube: $('#doctor_youtube').val(),
            twitter: $('#doctor_twitter').val(),
            days: days_data,
        };

        // Si hay un ID del post, añadirlo al objeto data
        if (post_id) {
            data.id = post_id;
        }

        // Enviar la solicitud ajax con el objeto data
		$.ajax({
			url: ajax_object.ajax_url,
			type: 'post',
			dataType: 'json',
			data: data,
			beforeSend: function () {
				$('.status-msg').show().removeClass(['error', 'success']).text('Enviando...');
				$('#submit').prop('disabled', true);
			}
		})
			// Code to run if the request succeeds (is done);
			// The response is passed to the function
			.done(function (res) {
				// Obtener el link del post creado desde la respuesta
				const link = res.link;
				// Añadir el link al mensaje que se muestra en el frontend
				const noticeClass = res.status === 1 ? 'success' : 'error';
				const noticeMsg = res.status === 1 ? res.msg + ' Puedes verla desde este <a href="' + link + '">enlace</a>.' : res.msg;
				$('.status-msg').removeClass([['error', 'success']]).addClass(noticeClass).html(noticeMsg);
			})
			// Code to run if the request fails; the raw request and
			// status codes are passed to the function
			.fail(function (xhr, status, errorThrown) {
				$('.status-msg').removeClass(['error', 'success']).addClass('error').text('Disculpa, ha habido un problema. Intenta más tarde.');
				console.log("Error: " + errorThrown);
				console.log("Status: " + status);
				console.dir(xhr);
			})
			.always(function () {
				console.log("The request is complete!");
				$('#submit').prop('disabled', false);
				$('#nuevoProfesional')[0].reset();
			})
	});

});