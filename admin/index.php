<?php
//autenticar para que al copiar la url se cierre la sesion
// para iniciar sesion y que el usuario vea la pagina 
require '../includes/funciones.php';
$auth = estaAutenticado();


if(!$auth) {
    header('Locacion: /bienesraices2');

}


//importar la conexion de la BD
  
require '../includes/config/database.php';
$db = conectarDB();

// Escribir el query
$query = "SELECT * FROM propiedades";

//consultar la BD
$resultadoConsulta = mysqli_query($db, $query);

   

//Muestra mensaje condicional  
$resultado = $_GET['resultado'] ?? null;






if ($_SERVER['REQUEST_METHOD']=== 'POST') {
    $id = $_POST['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);


    if($id) {
        //Eliminar el archivo
        $query = "SELECT imagen FROM propiedades WHERE id = $id";
        $resultado = mysqli_query($db, $query);
        $propiedad = mysqli_fetch_assoc($resultado);
        unlink('../imagenes/' . $propiedad['imagen']);

        //Eliminar la propiedad
        $query = "DELETE FROM propiedades WHERE id = $id";

        $resultado = mysqli_query($db, $query);
        if($resultado) {
            header('Location: /bienesraices2/admin?resultado=3');
        }
    }
}
  
  
  //incluye el template
    incluirTemplate('header');
?>

    <main class="contenedor seccion">
        <h1>Administrador de Bienes Raices</h1>
        
        <?php if(intval( $resultado) === 1): ?>

            <p class="alerta exito">Anuncio creado correctamente</p>

            <?php elseif (intval( $resultado) === 2): ?>
                <p class="alerta exito">Anuncio Actualizado correctamente</p>

                <?php elseif (intval( $resultado) === 3): ?>
                <p class="alerta exito">Anuncio Eliminado correctamente</p>
            <?php endif; ?>

        
            <a href="/bienesraices2/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>


            <table class="propiedades"> 

                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Titulo</th>
                        <th>Imagen</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody> <!--Mostrar los resultados de la DB-->
                <?php while($propiedad = mysqli_fetch_assoc($resultadoConsulta)): ?>
                    <tr>
                        <td> <?php echo $propiedad['id']; ?> </td>
                        <td><?php echo $propiedad['titulo']; ?></td>
                        <td><img src="/bienesraices2/imagenes/<?php echo $propiedad['imagen']; ?>" alt="" class="imagen-tabla"></td>
                        <td>$ <?php echo $propiedad['precio']; ?></td>
                        <td>
                            <form method="POST" class="w-100">
                                <input type="hidden" name="id" value="<?php echo $propiedad['id']; ?>">

                            <input type="submit" class="boton-rojo-block" value="Eliminar">
                            </form> 
                           
                            <a href="/bienesraices2/admin/propiedades/actualizar.php?id=<?php echo $propiedad['id']; ?>" 
                            class="boton-amarillo-block">Actualizar</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
    </main>

    <?php


//cerrar conexion DB
mysqli_close($db);

    incluirTemplate('footer');
?>
