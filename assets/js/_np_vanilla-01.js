
// Esperar a que el documento esté listo
document.addEventListener("DOMContentLoaded", function() {
  // Seleccionar el formulario por su ID
  var form = document.getElementById("nuevoProfesional");
  // Añadir un evento de submit al formulario
  form.addEventListener("submit", function(e) {
    // Prevenir el comportamiento por defecto del formulario
    e.preventDefault();
    // Crear un objeto FormData con los datos del formulario
    var formData = new FormData(form);
    // Añadir los datos adicionales al objeto FormData
    formData.append("action", "vittalia_ajax_form");
    formData.append("_ajax_nonce", ajax_object.nonce);
    // Crear un objeto XMLHttpRequest para enviar la solicitud AJAX
    var xhr = new XMLHttpRequest();
    // Abrir la conexión con el método POST y la URL del archivo PHP que procesa la solicitud
    xhr.open("POST", ajax_object.ajax_url);
    // Establecer el tipo de respuesta a JSON
    xhr.responseType = "json";
    // Añadir un evento de load para manejar la respuesta
    xhr.addEventListener("load", function() {
      // Comprobar si la solicitud fue exitosa (código 200)
      if (xhr.status === 200) {
        // Obtener la respuesta JSON desde el objeto xhr
        var res = xhr.response;
        // Obtener el elemento para mostrar los mensajes por su clase
        var statusMsg = document.querySelector(".status-msg");
        // Mostrar el elemento de mensaje y quitar las clases anteriores
        statusMsg.style.display = "block";
        statusMsg.classList.remove("error", "success");
        // Comprobar el estado de la respuesta y añadir la clase correspondiente
        var noticeClass = res.status === 1 ? "success" : "error";
        statusMsg.classList.add(noticeClass);
        // Comprobar si la respuesta contiene un link y crear un elemento <a> con el link
        var link = "";
        if (res.link) {
          link = document.createElement("a");
          link.href = res.link;
          link.textContent = "enlace";
          link.target = "_blank";
        }
        // Crear un elemento <span> con el mensaje de la respuesta
        var msg = document.createElement("span");
        msg.textContent = res.msg + " ";
        // Vaciar el contenido del elemento de mensaje y añadir el <span> y el <a> si existe
        statusMsg.innerHTML = "";
        statusMsg.appendChild(msg);
        if (link) {
          statusMsg.appendChild(link);
        }
      } else {
        // La solicitud no fue exitosa, mostrar un mensaje de error genérico
        alert("Lo siento, ha habido un problema.");
      }
    });
    // Añadir un evento de error para manejar posibles fallos de red
    xhr.addEventListener("error", function() {
      alert("Lo siento, ha habido un problema.");
    });
    // Añadir un evento de always para ejecutar acciones al finalizar la solicitud
    xhr.addEventListener("loadend", function() {
      console.log("La solicitud se ha completado!");
      // Habilitar el botón de enviar y resetear el formulario
      form.querySelector("#submit").disabled = false;
      form.reset();
    });
    // Enviar la solicitud AJAX con el objeto FormData
    xhr.send(formData);
    // Mostrar un mensaje de enviando y deshabilitar el botón de enviar mientras se espera la respuesta
    var statusMsg = document.querySelector(".status-msg");
    statusMsg.style.display = "block";
    statusMsg.classList.remove("error", "success");
    statusMsg.textContent = "Enviando...";
    form.querySelector("#submit").disabled = true;
  });
});

