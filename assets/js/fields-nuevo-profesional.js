// Esperar a que el documento esté listo
document.addEventListener("DOMContentLoaded", function () {

    
    /* Ocultar o mostrar el campo oculto según el estado del checkbox */
    function toggleHidden(checkbox) {
        const hidden = document.getElementById(checkbox.id + "-hidden");
        const timeStartA = document.getElementById(checkbox.id + "_start_a");
        const timeEndA = document.getElementById(checkbox.id + "_end_a");
        const timeStartB = document.getElementById(checkbox.id + "_start_b");
        const timeEndB = document.getElementById(checkbox.id + "_end_b");
        if (checkbox.checked) {
            hidden.style.display = "none";
            timeStartA.setAttribute("required", "");
            timeEndA.setAttribute("required", "");
            // Deshabilitar los inputs del segmento B si el checkbox está marcado
            timeStartB.setAttribute("disabled", "");
            timeEndB.setAttribute("disabled", "");
        } else {
            hidden.style.display = "block";
            timeStartA.removeAttribute("required");
            timeEndA.removeAttribute("required");
            timeStartB.removeAttribute("required");
            timeEndB.removeAttribute("required");
            timeStartA.value = "";
            timeEndA.value = "";
            timeStartB.value = "";
            timeEndB.value = "";
        }
    }

    // Obtener todos los checkboxes con la clase 'day-checkbox'
    const checkboxes = document.querySelectorAll(".day-checkbox");

    // Recorrer los checkboxes y asignarles el evento click con la función toggleHidden
    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener("click", function () {
            toggleHidden(this);
        });
    });

    // Función auxiliar para habilitar o deshabilitar los inputs del segmento B
    function toggleSegmentB(startIdA, endIdA, startIdB, endIdB) {
        // Seleccionar los inputs correspondientes a los ids
        let startInputA = document.getElementById(startIdA);
        let endInputA = document.getElementById(endIdA);
        let startInputB = document.getElementById(startIdB);
        let endInputB = document.getElementById(endIdB);

        // Obtener los valores de los inputs del segmento A
        let startA = startInputA.value;
        let endA = endInputA.value;

        // Habilitar o deshabilitar los inputs del segmento B según si el input de _end_a tiene valor o no
        if (startA && endA) {
            startInputB.removeAttribute("disabled");
            endInputB.removeAttribute("disabled");
            endInputB.min = startInputB.value;
        } else {
            startInputB.setAttribute("disabled", "");
            endInputB.setAttribute("disabled", "");
        }
    }

    /* Validar que el tiempo de fin sea mayor o igual al tiempo de inicio en cada día */
    // Seleccionar todos los elementos input de tipo time
    const timeInputs = document.querySelectorAll('.time input[type="time"]');

    // Añadir un evento change a cada input
    timeInputs.forEach(function (timeInput) {
        timeInput.addEventListener('change', function () {

            // Obtener el id del input que cambió
            let id = timeInput.id;

             /* Validar que el tiempo de fin sea mayor o igual al tiempo de inicio en cada día */
            // Comprobar si el id termina con "_start"
            if (id.endsWith("_start", 9)) {

                // Obtener el valor del input que cambió
                let start = timeInput.value;

                // Obtener el id del input correspondiente al tiempo de fin, reemplazando "_start" por "_end"
                let endId = id.replace("_start", "_end");

                // Seleccionar el input correspondiente al tiempo de fin
                let endInput = document.getElementById(endId);

                // Cambiar el valor del atributo min del input de fin por el valor del input de inicio
                endInput.min = start;

            }

            /* Asegurar que el volor de inicio del segundo segmento horario no sea menor que el valor de fin del primero */
            if (id.endsWith("_end_a")) {

                // Obtener el valor de fin del segmento A
                let endA = timeInput.value;

                // Obtener el id del input correspondiente al tiempo de inicio del segmento B
                let startIdB = id.replace("_end_a", "_start_b");
                // Seleccionar el input correspondiente al tiempo de _start_b
                let startInputB = document.getElementById(startIdB);

                // Cambiar el valor del atributo min de _start_b por el valor de _end_a
                startInputB.min = endA;

                // Llamar a la función auxiliar para habilitar o deshabilitar los inputs del segmento B
                toggleSegmentB(id.replace("_end_a", "_start_a")
                    , id
                    , startIdB
                    , id.replace("_end_a", "_end_b")
                    );
            }

            if (id.endsWith("_start_a")) {
                // Llamar a la función auxiliar para habilitar o deshabilitar los inputs del segmento B
                toggleSegmentB(id
                    , id.replace("_start_a", "_end_a")
                    , id.replace("_start_a", "_start_b")
                    , id.replace("_start_a", "_end_b")
                    );
            }
        });
    });

});
