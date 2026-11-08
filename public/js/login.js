const formLogin = document.querySelector('#form-login');
const inputCorreo = document.querySelector('#correo');
const inputPassword = document.querySelector('#password');
const mensajeJs = document.querySelector('#mensaje-js');

// CONSTANTES DE LOGIN MODAL
const tituloModal = document.getElementById('authModalLabel');
const contenedorLogin = document.getElementById('contenedorLogin');
const contenedorRegistro = document.getElementById('contenedorRegistro');
// BOTÓN PARA MOSTRAR REGISTRO
const mostrarRegistro = document.getElementById('mostrarRegistro');
// BOTÓN PARA MOSTRAR LOGIN
const mostrarLogin = document.getElementById('mostrarLogin');

function mostrarError(texto) {
    mensajeJs.textContent = texto;
    mensajeJs.classList.remove('d-none');
};

formLogin.addEventListener('submit', (evento) => {
    const correo = inputCorreo.value.trim();
    const password = inputPassword.value;

    if (correo === '' || password === '') {
        evento.preventDefault();
        mostrarError('Ingresa tu correo y tu contraseña');
        return;
    }

    if (!correo.includes('@')) {
        evento.preventDefault();
        mostrarError('Ingresa un correo valido');
        return;
    }

    mensajeJs.classList.add('d-none');
});
// =====================================================
// LOGIN MODAL
// =====================================================
// REPIDE EL LOGIN SI ESTA MAL
document.addEventListener('DOMContentLoaded', function(){
    //OBTENER MODAL
    const modal = document.getElementById('authModal');

    if (!modal){
        return;
    }

    // VERIFICAR SI EXISTE UN ERROR DE LOGIN 
    const errorLogin = modal.dataset.errorLogin === 'true';
    const errorRegistro = modal.dataset.errorRegistro === 'true';
    const exitoRegistro = modal.dataset.exitoRegistro === 'true';

    if (errorRegistro) {

        // MOSTRAR REGISTRO CON SU ERROR
        contenedorLogin.classList.add('d-none');
        contenedorRegistro.classList.remove('d-none');
        tituloModal.textContent = 'Crear cuenta';

        bootstrap.Modal.getOrCreateInstance(modal).show();

    } else if (errorLogin || exitoRegistro) {

        // MOSTRAR LOGIN CON ERROR O MENSAJE DE EXITO
        contenedorRegistro.classList.add('d-none');
        contenedorLogin.classList.remove('d-none');
        tituloModal.textContent = 'Iniciar sesión';

        bootstrap.Modal.getOrCreateInstance(modal).show();
    }
});
// CAMBIAR ENTRE LOGIN Y REGISTRO
mostrarRegistro.addEventListener('click', function () {

    contenedorLogin.classList.add('d-none');
    contenedorRegistro.classList.remove('d-none');

    tituloModal.textContent = 'Crear cuenta';
});
// VOLVER AL FORMULARIO DE LOGIN
mostrarLogin.addEventListener('click', function () {

    contenedorRegistro.classList.add('d-none');
    contenedorLogin.classList.remove('d-none');

    tituloModal.textContent = 'Iniciar sesión';
});