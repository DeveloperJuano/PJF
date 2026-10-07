console.log("conectadoo")

// ==================================================================================================================
//CONTANTES/VARIABLES
// ==================================================================================================================
// categorias
const botonesCategoria = document.querySelectorAll('[data-filtro]');
const tarjetas = document.querySelectorAll('[data-categoria]');
// ==================================================================================================================


//agregar producto
//nombre
const botonesAgregar = document.querySelectorAll('[data-agregar]');
const pedido = []

const contenedorPedido = document.querySelector('#pedido');
const botonRealizarPedido = document.querySelector('#realizarPedido');

// PEDIDO
const botonAbrirPedido = document.querySelector('#abrirPedido');
const botonCerrarPedido = document.querySelector('#cerrarPedido');
const panelPedido = document.querySelector('#panelPedido');

// ==================================================================================================================
// FUNCIONES
// ==================================================================================================================

/*
proporciona el pedido en forma de comanda
*/

function mostrarPedido(){
    let total = 0;
    contenedorPedido.innerHTML = '';
    
    /*
    Recorre el array pedido para mostrar cada producto
    y gestionar su cantidad mediante operaciones de actualización
    y eliminación.
    */
    pedido.forEach(function(producto, indice){
    // ==================================================================================================================

        // constantes y variables
        const item = document.createElement('div');

        const subtotal = producto.precio * producto.cantidad;

        const botonMenos = document.createElement("button");
        const botonMas = document.createElement("button");
        const botonEliminar = document.createElement('button');

        total += subtotal ;
        item.classList.add('item-pedido')
        
        // ==================================================================================================================

        // BOTONES DE TEXTO
        item.textContent = `${producto.nombre} x${producto.cantidad} = $${subtotal.toLocaleString('es-CO')}   `;
        botonEliminar.textContent ="Eliminar";
        botonMas.textContent ="+";
        botonMenos.textContent ="-";


        // ==================================================================================================================
        /*
        BOTONES ELIMAR, AUMENTAR Y ELIMAR CANTIDAD
        */

        // eliminar
        botonEliminar.addEventListener('click', function(){

            pedido.splice(indice, 1);
            
            mostrarPedido();

        });
        // añadir
        botonMas.addEventListener('click', function(){

            producto.cantidad++;

            mostrarPedido();

        });
        // disminuir
        botonMenos.addEventListener('click', function(){

            producto.cantidad--;

            if (producto.cantidad <= 0) {
                // eliminar producto del array
                pedido.splice(indice, 1)
            }

            mostrarPedido();

        });
        // ==================================================================================================================
            
        // añadidores
        item.appendChild(botonMenos)
        item.appendChild(botonMas)
        item.appendChild(botonEliminar);
        contenedorPedido.appendChild(item);
        
        // ==================================================================================================================

    });


    const totalPedido = document.createElement('p');

    totalPedido.textContent = `Total: $${total.toLocaleString('es-CO')}`;

    contenedorPedido.appendChild(totalPedido);
}

// ==================================================================================================================
/*
abre y cierra el pane del pedido
*/

botonAbrirPedido.addEventListener('click', function(){

    panelPedido.classList.add('abierto');

});
botonCerrarPedido.addEventListener('click', function(){

    panelPedido.classList.remove('abierto');

});




// ==================================================================================================================

/*
validacion al agregar producto
*/

botonesAgregar.forEach(function(boton){

        boton.addEventListener('click', function(){


            // ==================================================================================================================

            const producto = { 
                nombre : boton.dataset.agregar,
                precio : Number(boton.dataset.precio),
                cantidad : 1
            };
            // ==================================================================================================================

            // identidicar si existe mas de un mismo producto
            const productoExistente = pedido.find(function(item){
                return item.nombre === producto.nombre;

            });

            if(productoExistente){
                productoExistente.cantidad++;
            }else{
                pedido.push(producto);
            }
            mostrarPedido();

        });
});
// ==================================================================================================================
/*
CONFIRMACION AL REALIZAR PEDIDO
*/
botonRealizarPedido.addEventListener('click', function(){

    if (pedido.length === 0) {
        alert('No hay productos en el pedido.');
        return;
    }

    alert('Pedido realizado correctamente.');

});

// ==================================================================================================================

/*
VALIDACION DE  CATEGORIA
*/

botonesCategoria.forEach(function(boton){
    // ==================================================================================================================
    boton.addEventListener('click', function(){
    
        const categoria = boton.dataset.filtro;
        // botones iluminados
        botonesCategoria.forEach(function(boton){
            boton.classList.remove('btn-primary');
            boton.classList.add('btn-outline-primary');
        });
        // ==================================================================================================================

        boton.classList.remove('btn-outline-primary');
        boton.classList.add('btn-primary');
        // ==================================================================================================================

        // categorizacion de categorias 
        tarjetas.forEach(function(tarjeta){

            const categoriaTarjeta = tarjeta.dataset.categoria;
            
            if(categoria === "todo"){
                tarjeta.style.display = '';

            }else if (categoria === categoriaTarjeta) {
                tarjeta.style.display = '';

            }else {
                tarjeta.style.display = 'none';
            }

        });
        // ==================================================================================================================

    });
});

