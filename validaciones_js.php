<!-- Validaciones en JavaScript. Las vistas con formularios incluyen este archivo. -->
<script>

// Muestra la lista de errores arriba del formulario. Devuelve true si no hay errores.
function mostrarErrores(errores) {

    const caja = document.getElementById("errores");

    caja.innerHTML = "";

    if (errores.length === 0) {
        caja.classList.add("d-none");
        return true;
    }

    const lista = document.createElement("ul");
    lista.className = "mb-0";

    errores.forEach(function (mensaje) {
        const item = document.createElement("li");
        item.textContent = mensaje;
        lista.appendChild(item);
    });

    caja.appendChild(lista);
    caja.classList.remove("d-none");
    caja.scrollIntoView({ behavior: "smooth", block: "center" });

    return false;
}


// Comprueba que el email tenga formato usuario@dominio.ext
function emailValido(valor) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(valor);
}


// Comprueba que el archivo tenga una extension permitida
function extensionPermitida(nombre) {

    const permitidas = ["pdf", "doc", "docx", "xls", "xlsx", "ppt", "pptx"];
    const partes = nombre.toLowerCase().split(".");

    return partes.length > 1 && permitidas.includes(partes[partes.length - 1]);
}


// ---------------- LOGIN ----------------
function validarLogin() {

    const errores = [];

    const email = document.getElementById("email").value.trim();
    const contrasena = document.getElementById("contrasena").value;

    if (email === "") {
        errores.push("Debes ingresar el email.");
    } else if (!emailValido(email)) {
        errores.push("El email no tiene un formato valido.");
    }

    if (contrasena === "") {
        errores.push("Debes ingresar la contraseña.");
    }

    return mostrarErrores(errores);
}


// ---------------- DOCUMENTOS (subir y editar) ----------------
function validarDocumento(archivoObligatorio) {

    const errores = [];

    const titulo = document.getElementById("titulo").value.trim();
    const archivo = document.getElementById("archivo");
    const categorias = document.getElementById("categorias");

    if (titulo === "") {
        errores.push("Debes ingresar un titulo.");
    } else if (titulo.length > 45) {
        errores.push("El titulo no puede superar los 45 caracteres.");
    }

    if (archivo.files.length === 0) {

        if (archivoObligatorio) {
            errores.push("Debes elegir un archivo.");
        }

    } else if (!extensionPermitida(archivo.files[0].name)) {

        errores.push("El formato del archivo no esta permitido (PDF, DOC, DOCX, XLS, XLSX, PPT o PPTX).");

    } else if (archivo.files[0].size > 10 * 1024 * 1024) {

        errores.push("El archivo no puede superar los 10 MB.");
    }

    if (categorias.selectedOptions.length === 0) {
        errores.push("Debes seleccionar al menos una categoria.");
    }

    return mostrarErrores(errores);
}


// ---------------- ENCUESTAS (crear y editar) ----------------
function validarEncuesta() {

    const errores = [];

    const titulo = document.getElementById("titulo").value.trim();
    const descripcion = document.getElementById("descripcion").value.trim();

    if (titulo === "") {
        errores.push("Debes ingresar un titulo.");
    } else if (titulo.length > 25) {
        errores.push("El titulo no puede superar los 25 caracteres.");
    }

    if (descripcion === "") {
        errores.push("Debes ingresar una descripcion.");
    } else if (descripcion.length > 100) {
        errores.push("La descripcion no puede superar los 100 caracteres.");
    }

    let preguntasConTexto = 0;

    document.querySelectorAll('input[name="preguntas[]"]').forEach(function (campo, i) {

        const texto = campo.value.trim();

        if (texto !== "") {

            preguntasConTexto++;

            if (texto.length > 100) {
                errores.push("La pregunta " + (i + 1) + " no puede superar los 100 caracteres.");
            }
        }
    });

    if (preguntasConTexto === 0) {
        errores.push("Debes agregar al menos una pregunta.");
    }

    return mostrarErrores(errores);
}


// ---------------- RESPONDER ENCUESTA ----------------
function validarRespuestas() {

    const errores = [];
    const grupos = {};

    // Para cada pregunta (grupo de radios) compruebo que haya una opcion marcada
    document.querySelectorAll('#formResponder input[type="radio"]').forEach(function (radio) {

        if (!(radio.name in grupos)) {
            grupos[radio.name] = false;
        }

        if (radio.checked) {
            grupos[radio.name] = true;
        }
    });

    let numero = 0;

    for (const nombre in grupos) {

        numero++;

        if (!grupos[nombre]) {
            errores.push("Debes responder la pregunta " + numero + ".");
        }
    }

    return mostrarErrores(errores);
}

</script>
