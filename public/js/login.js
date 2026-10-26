const formLogin = document.querySelector('#form-login');
const inputCorreo = document.querySelector('#correo');
const inputPassword = document.querySelector('#password');
const mensajeJs = document.querySelector('#mensaje-js');

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