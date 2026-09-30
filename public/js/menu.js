console.log("conectadoo")

const botonesCategoria = document.querySelectorAll('[data-filtro]');
const tarjetas = document.querySelectorAll('[data-categoria]');

botonesCategoria.forEach(function(boton){

    boton.addEventListener('click', function(){

        const categoria = boton.dataset.filtro;

        console.log(categoria)

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