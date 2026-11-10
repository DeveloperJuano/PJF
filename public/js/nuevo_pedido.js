// Formulario "Nuevo pedido": en el local (mesa) o para llevar (hora de recogida)
(function () {
    const radios = document.querySelectorAll('input[name="tipo"]');
    const bloqueMesa = document.querySelector('#bloque-mesa');
    const selectMesa = document.querySelector('#idMesa');
    const bloqueRecogida = document.querySelector('#bloque-recogida');

    function actualizarTipo() {
        const llevar = document.querySelector('input[name="tipo"]:checked').value === 'llevar';

        bloqueMesa.hidden = llevar;
        selectMesa.required = !llevar;
        bloqueRecogida.hidden = !llevar;
    }

    radios.forEach(function (radio) {
        radio.addEventListener('change', actualizarTipo);
    });

    actualizarTipo();
})();