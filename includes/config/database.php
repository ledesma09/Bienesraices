<?php

function conectarDB () : mysqli {
    $db = mysqli_connect('localhost', 'root', 'ledesma2109', 'bienesraices_crud');

    if(!$db) {
        echo "error no se pudo conectar";
        exit;
    } 

    return $db;
}