<?php

//proteger pagina para que al copiar el link se cierre la sesion
require '../../includes/app.php';


//esto esta llamando una clase
    use App\Propiedad;


estaAutenticado();
// para iniciar sesion y que el usuario vea la pagina 

// base de datos 

$db = conectarDB();

//consultar base de datos

$consulta = "SELECT * FROM vendedores";
$resultado = mysqli_query($db, $consulta);


// Arreglo con mensajes de errores 

$errores = [];

$titulo = '';
$precio = '';
$descripcion = '';
$habitaciones = '';
$bano = '';
$estacionamiento = '';
$vendedores_id = '';

//ejecutar el codigo despues de que el usuario envia el formulario

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $propiedad = new Propiedad($_POST);

    $propiedad->guardar();

    // echo "<pre>";
    //  var_dump($_POST);
    // echo "</pre>";

    $titulo = mysqli_real_escape_string($db, $_POST['titulo']);
    $precio =  mysqli_real_escape_string($db, $_POST['precio']);
    $descripcion = mysqli_real_escape_string($db, $_POST['descripcion']);
    $habitaciones =  mysqli_real_escape_string($db, $_POST['habitaciones']);
    $bano = mysqli_real_escape_string($db, $_POST['bano']);
    $estacionamiento = mysqli_real_escape_string($db, $_POST['estacionamiento']);
    $vendedores_id = mysqli_real_escape_string($db, $_POST['vendedores_id']);
    $creado = date('Y/m/d');


    //asignar files hacia una variable
    $imagen = $_FILES['imagen'];

    if (!$titulo) {
        $errores[] = " El campo Titulo es obligatorio";
    }

    if (!$precio) {
        $errores[] = " El campo Precio es obligatorio";
    }

    if (strlen($descripcion) < 50) {
        $errores[] = " El campo Descripcion es obligatorio";
    }

    if (!$habitaciones) {
        $errores[] = " El campo Habitaciones es obligatorio";
    }

    if (!$bano) {
        $errores[] = " El campo baÑo es obligatorio";
    }

    if (!$estacionamiento) {
        $errores[] = " El campo Estacionamiento es obligatorio";
    }

    if (!$vendedores_id) {
        $errores[] = " Elegir un vendedor es obligatorio";
    }

    if (!$imagen['name'] || $imagen['error']) {
        $errores[] = "la imagen es obligatoria";
    }

    // validar por tamano 
    $medida = 1000 * 1000;

    if ($imagen['size'] > $medida) {
        $errores[] = "la imagen es muy pesada";
    }

    // echo "<pre>";
    //   var_dump($errores);
    //  echo "</pre>";


    //revisar arreglo este vacio 

    if (empty($errores)) {
        /** SUBIDA DE ARCHIVOS  */

        //Crear una carpeta
        $carpetaImagenes = '../../imagenes/';

        if (!is_dir($carpetaImagenes)) {
            mkdir($carpetaImagenes);
        }
        //Generar un nombre único
        $nombreImagen = md5(uniqid(rand(), true)) . '.jpg';

        //Subir la imágen
        move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen);



        // insertar en la base de datos

        $query = "INSERT INTO propiedades (titulo, precio, imagen, descripcion, habitaciones, bano, estacionamiento, creado, vendedores_id)
        VALUES ('$titulo', '$precio','$nombreImagen', '$descripcion', '$habitaciones', '$bano', '$estacionamiento', '$creado', '$vendedores_id') ";

        // echo $query;

        $resultado = mysqli_query($db, $query);

        if ($resultado) {
            //redireccionar al usuario 

            header('Location: /bienesraices2/admin/index.php?resultado=1');
        }
    }
}

incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Crear</h1>

    <a href="/bienesraices2/admin" class="boton boton-verde">Volver</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>

    <form class="formulario" method="POST" action="/bienesraices2/admin/propiedades/crear.php" enctype="multipart/form-data">
        <fieldset>
            <legend>Informacion General</legend>

            <label for="titulo">Titulo:</label>
            <input type="text" id="titulo" name="titulo" placeholder="Titulo Propiedad" value="<?php echo $titulo; ?>">

            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php echo $precio; ?>">

            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

            <label for="descripcion">Descripcion:</label>
            <textarea id="descripcion" name="descripcion"><?php echo $descripcion; ?></textarea>


            <fieldset>
                <legend>Informacion Propiedad</legend>


                <label for="habitaciones">Habitaciones:</label>
                <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej: 3" min="1" max="10" value="<?php echo $habitaciones; ?>">


                <label for="bano">BaÑos:</label>
                <input type="number" id="bano" name="bano" placeholder="Ej: 3" min="1" max="10" value="<?php echo $bano; ?>">

                <label for="estacionamiento">Estacionamiento:</label>
                <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ej: 3" min="1" max="8" value="<?php echo $estacionamiento; ?>">


            </fieldset>
            <fieldset>
                <legend>Vendedor</legend>

                <select name="vendedores_id">
                    <option value="">-- Seleccione --</option>
                    <?php while ($vendedores = mysqli_fetch_assoc($resultado)) : ?>
                        <option <?php echo $vendedores_id === $vendedores['id'] ? 'selected' : ''; ?> value="<?php echo $vendedores['id']; ?>"> <?php echo $vendedores['nombre'] . " " . $vendedores['apellido']; ?> </option>

                    <?php endwhile; ?>
                </select>
            </fieldset>
        </fieldset>

        <input type="submit" value="Crear Propiedad" class="boton boton-verde">

    </form>
</main>


<?php
incluirTemplate('footer');
?>