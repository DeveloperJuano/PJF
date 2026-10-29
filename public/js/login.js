const formLogin = document.querySelector('#form-login');
const inputCorreo = document.querySelector('#correo');
const inputPassword = document.querySelector('#password');
const mensajeJs = document.querySelector('#mensaje-js');
const btnMostrarPassword = document.querySelector('#btn-mostrar-password');

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

btnMostrarPassword.addEventListener('click', () => {
    if (inputPassword.type === 'password') {
        inputPassword.type = 'text';
        btnMostrarPassword.textContent = 'Ocultar';
    }
    else {
        inputPassword.type = 'password';
        btnMostrarPassword.textContent = 'Mostrar';
    }
});