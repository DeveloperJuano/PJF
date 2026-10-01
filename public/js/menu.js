console.log("conectadoo")

// categorias
const botonesCategoria = document.querySelectorAll('[data-filtro]');
const tarjetas = document.querySelectorAll('[data-categoria]');

/*
agregar producto
*/
//nombre
const botonesAgregar = document.querySelectorAll('[data-agregar]');
const pedido = []

const contenedorPedido = document.querySelector('#pedido');




function mostrarPedido(){
    let total = 0;
    contenedorPedido.innerHTML = '';

    pedido.forEach(function(producto){
        
        const item = document.createElement('p');
        const subtotal = producto.precio * producto.cantidad
        total =+ subtotal ;
        item.textContent = `${producto.nombre} x${producto.cantidad} = ${subtotal}  `;
        

        contenedorPedido.appendChild(item);

    });
    const totalPedido = document.createElement('p');

    totalPedido.textContent = `Total: ${total}`;

    contenedorPedido.appendChild(totalPedido);
}
/*
validacion al agregar producto
*/
botonesAgregar.forEach(function(boton){

        boton.addEventListener('click', function(){


            
            const producto = { 
                nombre : boton.dataset.agregar,
                precio : Number(boton.dataset.precio),
                cantidad : 1
            };

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

/*
validacion de categirias
*/
botonesCategoria.forEach(function(boton){

    boton.addEventListener('click', function(){

        const categoria = boton.dataset.filtro;
        // botones iluminados
        botonesCategoria.forEach(function(boton){
            boton.classList.remove('btn-primary');
            boton.classList.add('btn-outline-primary');
        });

        boton.classList.remove('btn-outline-primary');
        boton.classList.add('btn-primary');

        

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

    });
});