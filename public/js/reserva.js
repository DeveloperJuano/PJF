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

let mesaSeleccionada = null;
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

    if (mesaSeleccionada === null) {
        return;
    }

    if (personas > Number(mesaSeleccionada.capacidad)) {
        mensajeCapacidad.textContent = 'La mesa no tiene capacidad suficiente.';
    }else {
        mensajeCapacidad.textContent = '';
    }

});