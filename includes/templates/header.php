<?php

if(!isset($_SESSION)) {
    session_start();
}


    $auth = $_SESSION['login'] ?? null;

    //var_dump($auth);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienes Raices</title>
    <link rel="stylesheet" href="/bienesraices2/build/css/app.css">
</head>
<body>
    
    <header class="header <?php echo $inicio ? 'inicio' :''; ?>">
        <div class="contenedor contenido-header">
            <div class="barra">
                <a href="/bienesraices2/index.php">
                    <img src="/bienesraices2/src/img/logo.svg" alt="Logotipo de Bienes Raices">
                </a>

                <div class="mobile-menu">
                    <img src="/build/img/barras.svg" alt="icono menu responsive">
                </div>

                <div class="derecha">
                    <img class="dark-mode-boton" src="/bienesraices2/build/img/dark-mode.svg">
                    <nav class="navegacion">
                        <a href="nosotros.php">Nosotros</a>
                        <a href="anuncios.php">Anuncios</a>
                        <a href="blog.php">Blog</a>
                        <a href="contacto.php">Contacto</a>

                        <?php if($auth):  ?>
                            <a href="cerrar-sesion.php">Cerrar Sesion</a>
                            <?php endif; ?>
                    </nav>
                </div>
   
                
            </div> <!--.barra-->

              
            <?php if($inicio) { 
           echo "<h1>Venta de Casas y Departamentos  Exclusivos de Lujo</h1>";
             }?> 
        </div> 
    </header>