const formLogin = document.querySelector('#form-login');
const inputCorreo = document.querySelector('#correo');
const inputPassword = document.querySelector('#password');
const mensajeJs = document.querySelector('#mensaje-js');

function mostrarError(texto) {
    mensajeJs.textContent = texto;
    mensajeJs.classList.remove('d-none');
}