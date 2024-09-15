<?php
require_once 'Empleado.php';
require_once 'Evaluable.php';

class Gerente extends Empleado implements Evaluable {
    private $departamento;
    private $bono;

    // Constructor
    public function __construct($nombre, $idEmpleado, $salarioBase, $departamento, $bono = 0) {
        parent::__construct($nombre, $idEmpleado, $salarioBase);
        $this->departamento = $departamento;
        $this->bono = $bono;
    }

    // Getter y setter para el departamento
    public function getDepartamento() {
        return $this->departamento;
    }

    public function setDepartamento($departamento) {
        $this->departamento = $departamento;
    }

    // Método para asignar un bono
    public function asignarBono($bono) {
        $this->bono = $bono;
    }

    // Implementación de la interfaz Evaluable
    public function evaluarDesempenio() {
        return "El desempeño del gerente {$this->nombre} ha sido evaluado. Bono asignado: {$this->bono}";
    }

    // Sobrescribir obtenerInformacion para incluir el departamento y bono
    public function obtenerInformacion() {
        return parent::obtenerInformacion() . ", Departamento: {$this->departamento}, Bono: {$this->bono}";
    }
}
?>
