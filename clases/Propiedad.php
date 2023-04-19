<?php 

namespace App;


//estamos creando un active record
class Propiedad {
    
    public $id;
    public $titulo;
    public $precio;
    public $imagen;
    public $descripcion;
    public $habitaciones;
    public $bano;
    public $estacionamiento;
    public $creado;
    public $vendedores_id;


    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? '';
        $this->titulo = $args['titulo'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->habitaciones = $args['habitaciones'] ?? '';
        $this->bano = $args['bano'] ?? '';
        $this->estacionamiento = $args['estacionamiento'] ?? '';
        $this->creado = date('Y/m/d') ?? '';
        $this->vendedores_id = $args['vendedores_id'] ?? '';
    }

    public function guardar() {
        echo "guardando en la base de datos";
    }
}