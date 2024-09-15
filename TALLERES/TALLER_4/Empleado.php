<?php
// Clase base Empleado
class Empleado {
    public $nombre;
    public $idEmpleado;
    public $salarioBase;

    // Constructor para inicializar las propiedades
    public function __construct($nombre, $idEmpleado, $salarioBase) {
        $this->nombre = $nombre;
        $this->idEmpleado = $idEmpleado;
        $this->salarioBase = $salarioBase;
    }

    // Métodos getter y setter
    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getIdEmpleado() {
        return $this->idEmpleado;
    }

    public function setIdEmpleado($idEmpleado) {
        $this->idEmpleado = $idEmpleado;
    }

    public function getSalarioBase() {
        return $this->salarioBase;
    }

    public function setSalarioBase($salarioBase) {
        $this->salarioBase = $salarioBase;
    }

    // Método para obtener la información básica del empleado
    public function obtenerInformacion() {
        return "Empleado: {$this->nombre}, ID: {$this->idEmpleado}, Salario Base: {$this->salarioBase}";
    }
}
?>
