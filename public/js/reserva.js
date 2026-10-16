/*
COSNTANTES Y VARIABLES
*/
// BOTON MESA
const botonesMesa = document.querySelectorAll('.btn-agregar-mesa');
// SELECCION DE MESA 
const contenedorMesa = document.querySelector('#mesa-seleccionada');
//CANTIDAD DE PERSONAS
const inputPersonas = document.querySelector('#personas');

const mensajeCapacidad = document.querySelector('#mensaje-capacidad');
// HORA Y FECHA DE LA RESERVA
const inputFecha = document.querySelector('#fecha');
const inputHora = document.querySelector('#hora');
const botonConfirmar = document.querySelector('#btn-confirmar-reserva');
const resumenReserva = document.querySelector('#resumen-reserva');
const mensajeReserva = document.querySelector('#mensaje-reserva');

let mesaSeleccionada = null;
//=====================================================================================
/*
ESTABLECE FECHA LIMITE PARA LA RESERVA 
*/
const hoy = new Date();
const fechaLocal = new Date(
    hoy.getTime() - hoy.getTimezoneOffset() * 60000
).toISOString().split('T')[0];

inputFecha.min = fechaLocal;
//=====================================================================================

/*
CAPTURA LA MESA SELECCIONADA PARA RESERVAR
*/
botonesMesa.forEach(boton => {

    boton.addEventListener('click', () => {

        // CAPTURA DE DATOS
        const numeroMesa = boton.dataset.mesa;
        const capacidad = boton.dataset.capacidad;

        //ARREGLO CON DATOS DE LA MESA SELECCIONADA
        mesaSeleccionada = {
            numero: numeroMesa,
            capacidad: capacidad
        };
        
        inputPersonas.value = '';
        mensajeCapacidad.textContent = '';

        // TEXTO DE SELECCION DE MESA
        contenedorMesa.innerHTML = `
            <p>
                Mesa ${mesaSeleccionada.numero} -
                ${mesaSeleccionada.capacidad} personas
            </p>
        `;

        
        // ANADE CLASE A LA MESA SELECCIONADA 
        document.querySelectorAll('.mesa-seleccionada').forEach(mesa => {
            mesa.classList.remove('mesa-seleccionada');
        });
        boton.closest('.mesa').classList.add('mesa-seleccionada');
    });

});

//=====================================================================================
/*
CAPTURA DE CANTIDAD DE PERSONAS SELECCIONADA
*/

inputPersonas.addEventListener('input', () => {
    const personas = Number(inputPersonas.value);
    
    // Verificar si hay una mesa seleccionada
    if (mesaSeleccionada === null) {
        mensajeCapacidad.textContent = 'Primero debes seleccionar una mesa.';
        return;
    }
    
    // Verificar que la cantidad sea válida
    if (inputPersonas.value === '' || personas < 1) {
        mensajeCapacidad.textContent = 'Ingresa una cantidad válida de personas.';
        return;
    }
    
    // Verificar la capacidad de la mesa
    if (personas > Number(mesaSeleccionada.capacidad)) {
        mensajeCapacidad.textContent =
        'La mesa no tiene capacidad suficiente.';
        return;
    }
    
    // Si todo está correcto
    mensajeCapacidad.textContent = '';
});

//=====================================================================================

/*
RESUMEN Y CONFIRMACION DE RESERVA
*/

function validarReserva() {
    const personas = Number(inputPersonas.value);
    const fecha = inputFecha.value;
    const hora = inputHora.value;

    if (mesaSeleccionada === null) {
        return 'Selecciona una mesa.';
    }

    if (
        inputPersonas.value === '' ||
        !Number.isInteger(personas) ||
        personas < 1
    ) {
        return 'Ingresa una cantidad válida de personas.';
    }

    if (personas > Number(mesaSeleccionada.capacidad)) {
        return 'La mesa no tiene capacidad suficiente.';
    }

    if (fecha === '' || hora === '') {
        return 'Selecciona la fecha y la hora.';
    }

    const ahora = new Date();
    const anio = ahora.getFullYear();
    const mes = String(ahora.getMonth() + 1).padStart(2, '0');
    const dia = String(ahora.getDate()).padStart(2, '0');
    const fechaActual = `${anio}-${mes}-${dia}`;

    if (fecha < fechaActual) {
        return 'No puedes reservar en una fecha pasada.';
    }

    if (fecha === fechaActual) {
        const horaActual =
            `${String(ahora.getHours()).padStart(2, '0')}:` +
            `${String(ahora.getMinutes()).padStart(2, '0')}`;

        if (hora < horaActual) {
            return 'La hora seleccionada ya pasó.';
        }
    }

    return '';
}

function actualizarResumen() {
    const error = validarReserva();

    if (error !== '') {
        resumenReserva.textContent =
            'Completa correctamente los datos para revisar tu reserva.';
        return;
    }

    const fechaFormateada = inputFecha.value
        .split('-')
        .reverse()
        .join('/');

    resumenReserva.innerHTML = `
        <p>Mesa: ${mesaSeleccionada.numero}</p>
        <p>Personas: ${Number(inputPersonas.value)}</p>
        <p>Fecha: ${fechaFormateada}</p>
        <p>Hora: ${inputHora.value}</p>
    `;
}

// Actualizar el resumen cuando cambien los datos.
[inputPersonas, inputFecha, inputHora].forEach(campo => {
    campo.addEventListener('input', () => {
        mensajeReserva.textContent = '';
        actualizarResumen();
    });
});

// Confirmar la reserva de prueba.
botonConfirmar.addEventListener('click', () => {
    const error = validarReserva();

    if (error !== '') {
        mensajeReserva.textContent = error;
        actualizarResumen();
        return;
    }

    actualizarResumen();

    mensajeReserva.textContent =
        'Datos válidos. La reserva aún no se ha guardado.';
});
