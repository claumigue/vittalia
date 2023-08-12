// Usar la función jQuery en lugar de document para esperar a que el documento esté listo
jQuery(document).ready(function ($) {
    // Seleccionar el formulario con el id 'editarProfesional'
    const form = $("#editarProfesional");
    // Añadir un evento submit al formulario
    form.on("submit", function (event) {
        // Prevenir el comportamiento por defecto del formulario
        event.preventDefault();
        // Obtener los datos del formulario usando el método serialize de jQuery
        const formData = form.serialize();
        // Obtener el campo para los mensajes de confirmación y error
        const statusMsg = $(".status-msg");
        // Mostrar un mensaje de espera mientras se procesa la solicitud
        statusMsg.text("Por favor, espera un momento...");
        statusMsg.show();
        // Hacer la solicitud AJAX usando el método post de jQuery
        $.post(
            // Usar la URL del archivo PHP que procesa la solicitud, cambiando 'nuevo-profesional.php' por 'guardar-profesional.php'
            "/wp-content/themes/medilink-child/guardar-profesional.php",
            // Enviar los datos del formulario como parámetro
            formData,
            // Definir una función callback que se ejecuta cuando se recibe la respuesta
            function (response) {
                // Comprobar si la respuesta tiene un status positivo
                if (response.status === 1) {
                    // Mostrar un mensaje de confirmación con el link del post creado o editado
                    statusMsg.html(
                        response.msg +
                            " Puedes ver el resultado en este <a href='" +
                            response.link +
                            "'>enlace</a>."
                    );
                    statusMsg.show();
                    // Limpiar los campos del formulario
                    form[0].reset();
                } else {
                    // Mostrar un mensaje de error con la razón
                    statusMsg.text(response.msg);
                    statusMsg.show();
                }
            },
            // Especificar el formato JSON para la respuesta
            "json"
        );
    });
});
