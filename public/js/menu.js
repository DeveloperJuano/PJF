console.log("conectadoo")

// categorias
const botonesCategoria = document.querySelectorAll('[data-filtro]');
const tarjetas = document.querySelectorAll('[data-categoria]');

// agregar
const botonesAgregar = document.querySelectorAll('[data-agregar]');
const pedido = []

/*
validacion al agregar producto
*/
botonesAgregar.forEach(function(boton){

        boton.addEventListener('click', function(){

            
            const producto = { 
                nombre : boton.dataset.agregar,
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