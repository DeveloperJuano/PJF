// ==================================================================================================
// PANEL DEL EMPLEADO: se refresca solo cada 5 segundos
// Pide al servidor solo las secciones (/empleado/fragmento) y reemplaza el contenido
// ==================================================================================================

(function () {

    const contenedor = document.querySelector('#panel-datos');

    if (!contenedor) {
        return;
    }

    const SEGUNDOS = 5;
    let ultimoHtml = contenedor.innerHTML.trim();

    async function actualizar() {

        try {

            const respuesta = await fetch('/PJF/empleado/fragmento', {
                credentials: 'same-origin',
                cache: 'no-store'
            });

            // si la sesion expiro, el servidor redirige al login
            if (respuesta.redirected) {
                window.location.href = '/PJF/login';
                return;
            }

            // error temporal del servidor: se intenta de nuevo en el siguiente ciclo
            if (!respuesta.ok) {
                return;
            }

            const html = (await respuesta.text()).trim();

            // solo se toca la pantalla si algo cambio
            if (html !== ultimoHtml) {
                contenedor.innerHTML = html;
                ultimoHtml = html;
            }

        } catch (error) {
            // sin conexion: se intenta de nuevo en el siguiente ciclo
            console.warn('No se pudo actualizar el panel', error);
        }
    }

    setInterval(actualizar, SEGUNDOS * 1000);

})();