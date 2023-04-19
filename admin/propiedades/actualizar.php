<?php
require '../../includes/funciones.php';
$auth = estaAutenticado();
// para iniciar sesion y que el usuario vea la pagina 

if(!$auth) {
    header('Locacion: /');

}

//esto es para validar que le id no sea un string 
$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: "/admin"');
}


// base de datos 

require '../../includes/config/database.php';
$db = conectarDB();

//obtener datos de la propiedad 

$consulta = "SELECT * FROM propiedades WHERE id = $id";
$resultado = mysqli_query($db, $consulta);
$propiedad = mysqli_fetch_assoc($resultado);

//consultar base de datos

$consulta = "SELECT * FROM vendedores";
$resultado = mysqli_query($db, $consulta);


// Arreglo con mensajes de errores 

$errores = [];

$titulo = $propiedad['titulo'];
$precio = $propiedad['precio'];
$descripcion = $propiedad['descripcion'];
$habitaciones = $propiedad['habitaciones'];
$bano = $propiedad['bano'];
$estacionamiento = $propiedad['estacionamiento'];
$vendedores_id = $propiedad['vendedores_id'];
$imagenPropiedad = $propiedad['imagen'];

//ejecutar el codigo despues de que el usuario envia el formulario

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        
        //Subida de archivos

        //Crear una carpeta
        $carpetaImagenes = '../../imagenes/';

        if (!is_dir($carpetaImagenes)) {
            mkdir($carpetaImagenes);
        }
        //Generar un nombre único
        $nombreImagen = md5(uniqid(rand(), true)) . '.jpg';

        //Subir la imágen
        chmod($carpetaImagenes, 0777);
        move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen);
        



        //Insertar en la base de datos
        $query = " UPDATE propiedades SET titulo = '{$titulo}', precio = '{$precio}', imagen = '{$nombreImagen}', descripcion = '{$descripcion}', habitaciones = {$habitaciones}, bano = {$bano}, estacionamiento = {$estacionamiento}, vendedores_id = {$vendedores_id} WHERE id = {$id} ";


        //echo $query,'<br>';

        $resultado = mysqli_query($db, $query);

        if ($resultado) {

            //Redireccionar al usuario
            //echo 'Insertado Correctmente';

            header('Location: /bienesraices2/admin?resultado=2');
        }
    }
}






incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Actualizar Propiedad</h1>

    <a href="/bienesraices2/admin" class="boton boton-verde">Volver</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>

    <form class="formulario" method="POST" enctype="multipart/form-data">
        <fieldset>
            <legend>Informacion General</legend>

            <label for="titulo">Titulo:</label>
            <input type="text" id="titulo" name="titulo" placeholder="Titulo Propiedad" value="<?php echo $titulo; ?>">

            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php echo $precio; ?>">

            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

            <img src="/bienesraices2/imagenes/<?php echo $imagenPropiedad; ?>" class="imagen-small">

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

        <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">

    </form>
</main>


<?php
incluirTemplate('footer');
?>