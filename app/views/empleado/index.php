<!doctype html>
<html lang="es" data-bs-theme="dark">
    <head>
        <title>home admin</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!-- Bootstrap CSS v5.3.8 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
            crossorigin="anonymous"
        />
        <link rel="stylesheet" href="/PJF/public/css/style.css">

    </head>

    <body>
        <?php require_once __DIR__ . '/../components/navbar.php'; ?>        
        <main class="py-5">
            <div
                class="container"
            >
                <div
                    class="row justify-content-center align-items-center g-2"
                >
                    <div class="col-12 col-md-6 col-lg-4">
                        <a href="#"  class="text-decoration-none text-reset">
                        <section class="card p-4 mb-5">
                            <h2 class="h4 mb-3">PANEL PEDIDOS </h2>
                            
                        </section>
                        </a>
                    </div>
                    
                    <div class="col-12 col-md-6 col-lg-4">
                        <a href="/PJF/reserva/administrar"  class="text-decoration-none text-reset">
                        <section class="card p-4 mb-5">
                            <h2 class="h4 mb-3">PANEL RESERVAS </h2>
                        </section>
                        </a>
                    </div>
                    
            </div>
            

        </main>
    </body>
</html>
