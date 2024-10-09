<?php

class Entrada {
    public $id;
    public $fecha_creacion;
    public $tipo;
    public $titulo;
    public $descripcion;

    public function __construct($datos = []) {
        foreach ($datos as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}

class GestorBlog {
    private $entradas = [];

    public function cargarEntradas() {
        if (file_exists('blog.json')) {
            $json = file_get_contents('blog.json');
            $data = json_decode($json, true);
            foreach ($data as $entradaData) {
                $this->entradas[] = new Entrada($entradaData);
            }
        }
    }

    public function guardarEntradas() {
        $data = array_map(function($entrada) {
            return get_object_vars($entrada);
        }, $this->entradas);
        file_put_contents('blog.json', json_encode($data, JSON_PRETTY_PRINT));
    }

    public function obtenerEntradas() {
        return $this->entradas;
    }

    public function agregarEntrada(Entrada $entrada){
        $this->entradas[] = $entrada;
        $this->guardarEntradas();
    }

    public function editarEntrada(Entrada $entrada)
    {
        foreach ($this->entradas as $key => $e) {
            if ($e->id == $entrada->id) {
                $this->entradas[$key] = $entrada;
                $this->guardarEntradas();
                return;
            }
        }
    }
 
    public function eliminarEntrada($id)
    {
        foreach ($this->entradas as $key => $e) {
            if ($e->id == $id) {
                unset($this->entradas[$key]);
                $this->guardarEntradas();
                return;
            }
        }
    }
 
    public function obtenerEntrada($id): ?Entrada
    {
        foreach ($this->entradas as $e) {
            if ($e->id == $id) {
                return $e;
            }
        }
        return null;
    }
 
    public function moverEntrada($id, $direccion)
    {
        $index = array_search($id, array_column($this->entradas, 'id'));
        if ($index !== false) {
            $newIndex = $direccion === 'up' ? $index - 1 : $index + 1;
            if (isset($this->entradas[$newIndex])) {
                $tmp = $this->entradas[$index];
                $this->entradas[$index] = $this->entradas[$newIndex];
                $this->entradas[$newIndex] = $tmp;
                $this->guardarEntradas();
            }
        }
    }

}   